<?php
/**
 * Approved SEO content/settings changes (docs/seo-proposals-2026-11-20.md).
 *
 *   wp --path="$SITE_ROOT" eval-file seo-content.php            # dry run: show the plan, change nothing
 *   wp --path="$SITE_ROOT" eval-file seo-content.php apply      # back up, then apply
 *   wp --path="$SITE_ROOT" eval-file seo-content.php revert <backup.json>
 *
 * Every change checks the current value first and is skipped (WARN) if it is
 * not what the proposal saw, so nothing is overwritten blindly. Each value is
 * saved to a JSON backup before it changes; "revert" restores them exactly.
 * Not included: S3 (needs a designed image), L3 (needs legal text).
 */

if (!defined('WP_CLI')) {
    exit("Run with: wp eval-file seo-content.php [apply|revert <file>]\n");
}

$mode = isset($args[0]) ? $args[0] : 'dry-run';
$backup_dir = (getenv('BACKUP_BASE') ?: (getenv('HOME') . '/indigenetech-backups'));
// wp eval-file runs this file inside a function, so shared state lives in $GLOBALS.
$GLOBALS['itech_seo'] = array('apply' => $mode === 'apply', 'backup' => array(),
    'file' => $backup_dir . '/seo-content-' . gmdate('Ymd-His') . '.json',
    'counts' => array('planned' => 0, 'applied' => 0, 'already' => 0, 'warn' => 0));

function itech_out($tag, $id, $msg) { WP_CLI::log(sprintf('%-8s %-4s %s', $tag, $id, $msg)); }

// ---------------------------------------------------------------- revert
if ($mode === 'revert') {
    $file = isset($args[1]) ? $args[1] : '';
    $data = $file && is_readable($file) ? json_decode(file_get_contents($file), true) : null;
    if (!is_array($data)) {
        WP_CLI::error("Pass the backup file written by 'apply', e.g. $backup_dir/seo-content-YYYYMMDD-HHMMSS.json");
    }
    foreach (array_reverse($data) as $b) {
        try {
        switch ($b['kind']) {
            case 'option':
                update_option($b['name'], $b['value']);
                break;
            case 'meta':
                if ($b['exists']) { update_post_meta($b['post'], $b['key'], wp_slash($b['value'])); } else { delete_post_meta($b['post'], $b['key']); }
                break;
            case 'post':
                wp_update_post(wp_slash(array('ID' => $b['post'], $b['field'] => $b['value'])));
                break;
        }
        } catch (\Throwable $e) {
            itech_out('WARN', $b['id'], 'a plugin hook failed after restoring (' . $e->getMessage() . '); check: ' . $b['what']);
            continue;
        }
        itech_out('REVERTED', $b['id'], $b['what']);
    }
    if (class_exists('\Elementor\Plugin')) { \Elementor\Plugin::$instance->files_manager->clear_cache(); }
    WP_CLI::success('Restored ' . count($data) . ' values from ' . $file);
    return;
}

// ---------------------------------------------------------------- helpers
function itech_post($path, $type = 'page')
{
    if ($path === '__front__') { $id = (int) get_option('page_on_front'); return $id ? get_post($id) : null; }
    if ($path === '__posts__') { $id = (int) get_option('page_for_posts'); return $id ? get_post($id) : null; }
    return get_page_by_path($path, OBJECT, $type);
}

/** Record the old value, then change it (apply mode only). */
function itech_set($id, $what, $kind, $target, $new)
{
    $st = &$GLOBALS['itech_seo'];
    $st['counts']['planned']++;
    if (!$st['apply']) { itech_out('PLAN', $id, $what); return; }
    try {
    switch ($kind) {
        case 'option':
            $st['backup'][] = array('id' => $id, 'what' => $what, 'kind' => 'option', 'name' => $target, 'value' => get_option($target));
            update_option($target, $new);
            break;
        case 'meta':
            list($post, $key) = $target;
            $exists = metadata_exists('post', $post, $key);
            $st['backup'][] = array('id' => $id, 'what' => $what, 'kind' => 'meta', 'post' => $post, 'key' => $key, 'exists' => $exists, 'value' => $exists ? get_post_meta($post, $key, true) : null);
            update_post_meta($post, $key, wp_slash($new));
            break;
        case 'post':
            list($post, $field) = $target;
            $st['backup'][] = array('id' => $id, 'what' => $what, 'kind' => 'post', 'post' => $post, 'field' => $field, 'value' => get_post_field($field, $post, 'raw'));
            wp_update_post(wp_slash(array('ID' => $post, $field => $new)));
            break;
    }
    } catch (\Throwable $e) {
        // The value is normally saved before plugin hooks run; the backup above still covers it.
        itech_save_backup();
        itech_warn($id, "$what: a plugin hook failed after saving (" . $e->getMessage() . '); check this item');
        return;
    }
    itech_save_backup();
    $st['counts']['applied']++;
    itech_out('APPLIED', $id, $what);
}
function itech_save_backup()
{
    $st = $GLOBALS['itech_seo'];
    if (!$st['apply']) { return; }
    if (!is_dir(dirname($st['file']))) { wp_mkdir_p(dirname($st['file'])); }
    file_put_contents($st['file'], wp_json_encode($st['backup'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
}
function itech_already($id, $what) { $GLOBALS['itech_seo']['counts']['already']++; itech_out('ALREADY', $id, $what); }
function itech_warn($id, $what) { $GLOBALS['itech_seo']['counts']['warn']++; itech_out('WARN', $id, $what); }

/** Rank Math title/description: only if unset, a %pattern%, or matching what the proposal saw. */
function itech_rm_meta($id, $path, $type, $key, $new, $seen_prefix = '')
{
    $p = itech_post($path, $type);
    if (!$p) { itech_warn($id, "$path not found"); return; }
    $cur = (string) get_post_meta($p->ID, $key, true);
    if ($cur === $new) { itech_already($id, "$path $key"); return; }
    $ok = $cur === '' || strpos($cur, '%') !== false || ($seen_prefix !== '' && strpos($cur, $seen_prefix) === 0);
    if (!$ok) { itech_warn($id, "$path $key is \"" . mb_substr($cur, 0, 80) . "\" (not what the proposal saw); left unchanged"); return; }
    itech_set($id, "$path $key: \"" . mb_substr($cur, 0, 60) . "\" -> \"$new\"", 'meta', array($p->ID, $key), $new);
}

/**
 * Apply a text transform to a post's content, and to its Elementor data when
 * the post is built with Elementor (that is what the page then renders).
 * $fn returns the new string; $label describes the change.
 */
function itech_edit_text($id, $path, $type, $label, callable $fn)
{
    $p = itech_post($path, $type);
    if (!$p) { itech_warn($id, "$path not found"); return; }
    $changed = false;
    $new = $fn($p->post_content);
    if ($new !== $p->post_content) { itech_set($id, "$path content: $label", 'post', array($p->ID, 'post_content'), $new); $changed = true; }
    if (get_post_meta($p->ID, '_elementor_edit_mode', true) === 'builder') {
        $raw = get_post_meta($p->ID, '_elementor_data', true);
        $data = is_string($raw) ? json_decode($raw, true) : null;
        if (is_array($data)) {
            $walk = function ($v) use (&$walk, $fn) { if (is_array($v)) { return array_map($walk, $v); } return is_string($v) ? $fn($v) : $v; };
            $new_data = $walk($data);
            if ($new_data !== $data) { itech_set($id, "$path Elementor data: $label", 'meta', array($p->ID, '_elementor_data'), wp_json_encode($new_data)); $changed = true; }
        }
    }
    if (!$changed) { itech_warn($id, "$path: nothing to change for \"$label\" (already done or text not found)"); }
}

/** Set alt="" -> alt on <img> tags whose src contains $stem. */
function itech_alt_fn($stem, $alt)
{
    return function ($s) use ($stem, $alt) {
        return preg_replace_callback('/<img\b[^>]*>/i', function ($m) use ($stem, $alt) {
            $t = $m[0];
            if (stripos($t, $stem) === false || !preg_match('/\salt=(["\'])\1/', $t)) { return $t; }
            return preg_replace('/\salt=(["\'])\1/', ' alt="' . esc_attr($alt) . '"', $t, 1);
        }, $s);
    };
}

/** Attachment alt text by file name stem (no size suffix), only where empty or a file-name placeholder. */
function itech_attachment_alt($id, $stem, $alt, $placeholder = '')
{
    global $wpdb;
    $ids = $wpdb->get_col($wpdb->prepare("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND meta_value LIKE %s", '%' . $wpdb->esc_like('/' . $stem . '.') . '%'));
    if (!$ids) { itech_warn($id, "no media file named $stem"); return; }
    foreach ($ids as $aid) {
        $cur = (string) get_post_meta($aid, '_wp_attachment_image_alt', true);
        if ($cur === $alt) { itech_already($id, "media $aid ($stem) alt"); continue; }
        if ($cur !== '' && $cur !== $placeholder) { itech_warn($id, "media $aid ($stem) alt is \"$cur\"; left unchanged"); continue; }
        itech_set($id, "media $aid ($stem) alt: \"$cur\" -> \"$alt\"", 'meta', array((int) $aid, '_wp_attachment_image_alt'), $alt);
    }
}

WP_CLI::log("== SEO content changes ($mode) on " . home_url('/'));

// ---------------------------------------------------------------- S1 Site Title
$cur = get_option('blogname');
if ($cur === 'IndigeneTech') { itech_already('S1', 'Site Title'); }
elseif ($cur === '') { itech_set('S1', 'Site Title: "" -> "IndigeneTech"', 'option', 'blogname', 'IndigeneTech'); }
else { itech_warn('S1', "Site Title is \"$cur\"; left unchanged"); }

// ---------------------------------------------------------------- S2 / S4 Rank Math titles options
$titles = get_option('rank-math-options-titles', array());
if (!is_array($titles)) { $titles = array(); }
$want = $titles;
$want['knowledgegraph_type'] = 'company';
$want['knowledgegraph_name'] = 'IndigeneTech';
$want['website_name'] = 'IndigeneTech';   // og:site_name and the WebSite schema name
$logo_id = (int) get_theme_mod('custom_logo');
if ($logo_id && empty($titles['knowledgegraph_logo'])) {
    $want['knowledgegraph_logo'] = wp_get_attachment_url($logo_id);
    $want['knowledgegraph_logo_id'] = $logo_id;
}
$want['tax_category_robots'] = array('noindex');
$want['tax_category_custom_robots'] = 'on';
$diff = array();
foreach (array('knowledgegraph_type', 'knowledgegraph_name', 'website_name', 'knowledgegraph_logo', 'tax_category_robots', 'tax_category_custom_robots') as $k) {
    if ((isset($titles[$k]) ? $titles[$k] : null) != (isset($want[$k]) ? $want[$k] : null)) {
        $diff[] = $k . ': ' . wp_json_encode(isset($titles[$k]) ? $titles[$k] : null) . ' -> ' . wp_json_encode($want[$k]);
    }
}
if (!$diff) { itech_already('S2', 'Rank Math: Organization "IndigeneTech"; S4 category archives noindex'); }
else { itech_set('S2/4', 'Rank Math titles options: ' . implode('; ', $diff), 'option', 'rank-math-options-titles', $want); }

// ---------------------------------------------------------------- SC2 homepage schema box: one LocalBusiness
$front = itech_post('__front__');
$raw = $front ? (string) get_post_meta($front->ID, '_itechnolabs_schema_code', true) : '';
if ($raw === '') { itech_warn('SC2', 'homepage schema box is empty'); }
elseif (strpos($raw, '/#organization') !== false) { itech_already('SC2', 'homepage schema box'); }
elseif (preg_match_all('#<script[^>]*application/ld\+json[^>]*>(.*?)</script>#is', $raw, $m)) {
    $blocks = array_map(function ($j) { return json_decode($j, true); }, $m[1]);
    $org = $lb = null;
    foreach ($blocks as $i => $b) { if (isset($b['@type'])) { if ($b['@type'] === 'Organization') { $org = $i; } if ($b['@type'] === 'LocalBusiness') { $lb = $i; } } }
    if ($org === null || $lb === null) { itech_warn('SC2', 'expected one Organization and one LocalBusiness block; left unchanged'); }
    else {
        $merged = array('@context' => 'https://schema.org', '@type' => 'LocalBusiness', '@id' => home_url('/#organization'));
        foreach ($blocks[$lb] as $k => $v) { if ($k !== '@context' && $k !== '@type' && $k !== '@id') { $merged[$k] = $v; } }
        foreach (array('logo', 'contactPoint') as $k) { if (isset($blocks[$org][$k]) && !isset($merged[$k])) { $merged[$k] = $blocks[$org][$k]; } }
        $same = array_values(array_unique(array_merge((array) ($blocks[$lb]['sameAs'] ?? array()), (array) ($blocks[$org]['sameAs'] ?? array()))));
        if ($same) { $merged['sameAs'] = $same; }
        $json = wp_json_encode($merged, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $new = str_replace($m[0][$lb], '<script type="application/ld+json">' . "\n" . $json . "\n</script>", $raw);
        $new = preg_replace('/\s*' . preg_quote($m[0][$org], '/') . '/', '', $new, 1);
        itech_set('SC2', 'homepage schema box: Organization + LocalBusiness -> one LocalBusiness @id ' . home_url('/#organization') . ' (values unchanged)', 'meta', array($front->ID, '_itechnolabs_schema_code'), $new);
    }
} else { itech_warn('SC2', 'no JSON-LD blocks found in the homepage schema box'); }

// ---------------------------------------------------------------- T1-T9 titles and descriptions
itech_rm_meta('T1', 'about-us', 'page', 'rank_math_title', 'About Us | IndigeneTech');
itech_rm_meta('T2', 'contact-us', 'page', 'rank_math_title', 'Contact Us | IndigeneTech');
itech_rm_meta('T3', 'contact-us', 'page', 'rank_math_description', 'Contact IndigeneTech in Fort McMurray, Alberta. Call +1 (825) 901-9111 or send a message and our team will get back to you within 24 hours.', 'Whether you');
itech_rm_meta('T4', '__posts__', 'page', 'rank_math_title', 'Blog | IndigeneTech');
itech_rm_meta('T5', '__posts__', 'page', 'rank_math_description', 'Articles from IndigeneTech on software, AI, cloud and digital growth for Indigenous businesses and communities in Canada.');
itech_rm_meta('T6', 'indigenous-owned-and-operated-tech-businesses', 'post', 'rank_math_title', 'Why Indigenous Owned and Operated Tech Businesses Matter in Canada', 'Why Indigenous Owned and Operated Tech Businesses Are Essential');
itech_rm_meta('T7', 'indigenous-owned-business', 'post', 'rank_math_title', 'IndigeneTech: Indigenous-owned Business Providing IT Consulting', 'Indigene Tech');
itech_rm_meta('T9', 'how-technology-is-helping-indigenous-businesses-grow', 'post', 'rank_math_description', 'How software, cloud tools and automation help Indigenous businesses work more efficiently, reach more customers and grow.', 'This blog explores');

// T8: brand spelling in the post title (H1) and description
$p = itech_post('indigenous-owned-business', 'post');
if ($p) {
    if (strpos($p->post_title, 'Indigene Tech') !== false) {
        itech_set('T8', 'post title: "Indigene Tech" -> "IndigeneTech"', 'post', array($p->ID, 'post_title'), str_replace('Indigene Tech', 'IndigeneTech', $p->post_title));
    } else { itech_already('T8', 'post title spelling'); }
    $d = (string) get_post_meta($p->ID, 'rank_math_description', true);
    if (strpos($d, 'IndgineTech') !== false) {
        itech_set('T8', 'description: "IndgineTech" -> "IndigeneTech"', 'meta', array($p->ID, 'rank_math_description'), str_replace('IndgineTech', 'IndigeneTech', $d));
    } elseif (strpos($p->post_content, 'IndgineTech') !== false || get_post_meta($p->ID, '_elementor_edit_mode', true) === 'builder') {
        itech_edit_text('T8', 'indigenous-owned-business', 'post', '"IndgineTech" -> "IndigeneTech" (the description is taken from this text)', function ($s) { return str_replace('IndgineTech', 'IndigeneTech', $s); });
    } else { itech_already('T8', 'description spelling'); }
} else { itech_warn('T8', 'indigenous-owned-business not found'); }

// ---------------------------------------------------------------- A1-A7 alt text
if ($logo_id) {
    $cur = (string) get_post_meta($logo_id, '_wp_attachment_image_alt', true);
    if ($cur === 'IndigeneTech') { itech_already('A1', 'logo alt'); }
    elseif ($cur === '' || $cur === 'indigenetech-logo') { itech_set('A1', "logo alt: \"$cur\" -> \"IndigeneTech\"", 'meta', array($logo_id, '_wp_attachment_image_alt'), 'IndigeneTech'); }
    else { itech_warn('A1', "logo alt is \"$cur\"; left unchanged"); }
} else { itech_warn('A1', 'no custom logo set'); }

$alts = array(
    array('A2', 'digital-tools-empowering-indigenous-communities-entrepreneurs', 'Benefits-of-AI-in-Surveillance-System', 'Benefits of digital tools for Indigenous communities'),
    array('A2', 'digital-tools-empowering-indigenous-communities-entrepreneurs', 'Challenges-Considerations', 'Challenges and considerations for digital tools'),
    array('A2', 'digital-tools-empowering-indigenous-communities-entrepreneurs', 'What-Best-Practices-Enable-Success-1', 'Best practices that help digital tools succeed'),
    array('A3', 'guide-to-mobile-app-development', 'a-complete-guide-to-mobile-app-development-indigenetech', 'A complete guide to mobile app development'),
    array('A4', 'how-to-showcase-your-indigenous-business-to-a-global-market', 'Why-Going-Global-Matters-for-Indigenous-Entrepreneurs', 'Why going global matters for Indigenous entrepreneurs'),
    array('A4', 'how-to-showcase-your-indigenous-business-to-a-global-market', 'Strategies-to-Showcase-Your-Indigenous-Business-to-the-World-2', 'Strategies to showcase your Indigenous business to the world'),
    array('A5', 'indigenous-owned-and-operated-tech-businesses', 'why-an-indigenous-owned-and-operated-business-create-a-longer-term-impact', 'Why Indigenous owned and operated businesses create longer-term impact'),
    array('A5', 'indigenous-owned-and-operated-tech-businesses', 'how-indigenous-operated-teams-work-in-a-practical-and-community-ready-way', 'How Indigenous operated teams work in a practical, community-ready way'),
    array('A5', 'indigenous-owned-and-operated-tech-businesses', 'how-does-social-sustainability-help-services-fit-real-needs-not-just-fancy-features', 'How social sustainability helps services fit real needs'),
    array('A5', 'indigenous-owned-and-operated-tech-businesses', 'where-indigenous-owned-and-operated-tech-businesses-help-most-in-canada', 'Where Indigenous owned and operated tech businesses help most in Canada'),
    array('A6', 'indigenous-tech-solutions-shaping-the-future-of-innovation', 'The-Future-of-Innovation-Through-Indigenous-Technology-Solutions', 'The future of innovation through Indigenous technology solutions'),
    array('A6', 'indigenous-tech-solutions-shaping-the-future-of-innovation', 'Real-World-Examples-of-Indigenous-Technology-Solutions', 'Real-world examples of Indigenous technology solutions'),
    array('A6', 'indigenous-tech-solutions-shaping-the-future-of-innovation', 'How-Indigenous-Technology-Solutions-Are-Influencing-Global-Innovation', 'How Indigenous technology solutions influence global innovation'),
);
foreach ($alts as list($id, $slug, $stem, $alt)) {
    itech_attachment_alt($id, $stem, $alt);
    $p = itech_post($slug, 'post');
    if ($p && (stripos($p->post_content, $stem) !== false || get_post_meta($p->ID, '_elementor_edit_mode', true) === 'builder')) {
        $fn = itech_alt_fn($stem, $alt);
        if ($fn($p->post_content) !== $p->post_content || get_post_meta($p->ID, '_elementor_edit_mode', true) === 'builder') {
            itech_edit_text($id, $slug, 'post', "alt for $stem", $fn);
        }
    }
}
// A7: service-page images come from the media library alt text
itech_attachment_alt('A7', 'AR-VR-Development-Banner-Image', 'AR and VR development');
itech_attachment_alt('A7', 'IT-Consulting-Support-continuous-optimization', 'IT consulting support and continuous optimisation');
itech_attachment_alt('A7', 'QA-Testing-1', 'QA and testing');

// ---------------------------------------------------------------- L1, L2 links
itech_edit_text('L1', 'indigenous-owned-business', 'post', 'remove the dead itenterprise.co.uk link (text kept)', function ($s) {
    return preg_replace('#<a\b[^>]*href=["\']https?://itenterprise\.co\.uk/bespoke-software-go-bespoke/?["\'][^>]*>(.*?)</a>#is', '$1', $s);
});
itech_edit_text('L2', 'indigenetech-joins-ccib', 'post', 'http://www.ccib.ca -> https://www.ccib.ca', function ($s) {
    return str_replace(array('href="http://www.ccib.ca', "href='http://www.ccib.ca"), array('href="https://www.ccib.ca', "href='https://www.ccib.ca"), $s);
});

WP_CLI::log('NOT DONE S3  needs a designed 1200x630 share image (code falls back to the homepage banner)');
WP_CLI::log('NOT DONE L3  needs privacy policy text from the owner');

// ---------------------------------------------------------------- finish
$backup = $GLOBALS['itech_seo']['backup'];
$counts = $GLOBALS['itech_seo']['counts'];
if ($mode === 'apply') {
    itech_save_backup();
    $file = $GLOBALS['itech_seo']['file'];
    if (class_exists('\Elementor\Plugin')) { \Elementor\Plugin::$instance->files_manager->clear_cache(); }
    wp_cache_flush();
    WP_CLI::log("Backup of the " . count($backup) . " changed values: $file");
    WP_CLI::log("Undo: wp --path=\"" . ABSPATH . "\" eval-file " . __FILE__ . " revert $file");
}
WP_CLI::log(sprintf('Summary: %d %s, %d already done, %d warnings', $mode === 'apply' ? $counts['applied'] : $counts['planned'], $mode === 'apply' ? 'applied' : 'planned', $counts['already'], $counts['warn']));
WP_CLI::log($counts['warn'] ? 'RESULT: CHECK WARNINGS' : 'RESULT: OK');
