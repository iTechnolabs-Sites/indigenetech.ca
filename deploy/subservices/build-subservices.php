<?php
/**
 * Build sub-service pages from an approved spec (deploy/subservices/<parent>.json).
 *
 *   wp eval-file build-subservices.php                       # dry run: show the plan, change nothing
 *   wp eval-file build-subservices.php apply                 # back up, create/update pages, link the parent's cards
 *   wp eval-file build-subservices.php revert <backup.json>  # delete created pages, restore everything else
 *
 * SPEC=<path to json> picks the spec (default: enterprise-custom-software-development.json).
 * STATUS=draft creates pages as drafts (default: publish).
 *
 * Each page is a copy of the parent page's real Elementor layout with only the
 * mapped widgets rewritten, so design and editing in Elementor stay identical.
 */
if (!defined('WP_CLI')) { exit("Run with: wp eval-file build-subservices.php [apply|revert <file>]\n"); }

$mode = isset($args[0]) ? $args[0] : 'dry-run';
$spec_file = getenv('SPEC') ?: __DIR__ . '/enterprise-custom-software-development.json';
$backup_dir = getenv('BACKUP_BASE') ?: (getenv('HOME') . '/indigenetech-backups');
$status = getenv('STATUS') === 'draft' ? 'draft' : 'publish';

function itsb_log($tag, $msg) { WP_CLI::log(sprintf('%-8s %s', $tag, $msg)); }
/** Drop a page's cached Elementor CSS and cached widget HTML so it is rebuilt from the saved layout. */
function itsb_flush_page($id) { delete_post_meta($id, '_elementor_css'); delete_post_meta($id, '_elementor_element_cache'); }

// ------------------------------------------------------------------ revert
if ($mode === 'revert') {
    $file = isset($args[1]) ? $args[1] : '';
    $b = $file && is_readable($file) ? json_decode(file_get_contents($file), true) : null;
    if (!is_array($b)) { WP_CLI::error('Pass the backup file written by apply.'); }
    foreach ($b['created'] as $id) { wp_delete_post((int) $id, true); itsb_log('DELETED', "page $id"); }
    foreach ($b['updated'] as $u) {
        wp_update_post(wp_slash(array('ID' => $u['id'], 'post_title' => $u['post_title'], 'post_status' => $u['post_status'])));
        foreach ($u['meta'] as $k => $v) { if ($v === null) { delete_post_meta($u['id'], $k); } else { update_post_meta($u['id'], $k, wp_slash($v)); } }
        itsb_log('RESTORED', "page {$u['id']}");
    }
    if (!empty($b['parent'])) { update_post_meta($b['parent']['id'], '_elementor_data', wp_slash($b['parent']['elementor_data'])); itsb_flush_page($b['parent']['id']); itsb_log('RESTORED', 'parent page cards (Elementor data)'); }
    foreach ($b['updated'] as $u) { itsb_flush_page($u['id']); }
    WP_CLI::success('Reverted from ' . $file);
    return;
}

// ------------------------------------------------------------------ load spec + parent
$spec = json_decode((string) @file_get_contents($spec_file), true);
if (!is_array($spec)) { WP_CLI::error("Cannot read spec $spec_file"); }
$P = $spec['parent']; $map = $P['elementor_map'];
$parent = get_page_by_path($P['slug']);
if (!$parent) { WP_CLI::error("Parent page /{$P['slug']}/ not found"); }
$parent_raw = (string) get_post_meta($parent->ID, '_elementor_data', true);
$parent_tree = json_decode($parent_raw, true);
if (!is_array($parent_tree)) { WP_CLI::error('Parent page has no Elementor data'); }
WP_CLI::log("== Sub-service pages ($mode) for /{$P['slug']}/ (page {$parent->ID}) on " . home_url('/'));

// ------------------------------------------------------------------ tree helpers
/** Return a reference to the element with this id (or null). */
function &itsb_find(array &$els, $id) {
    $null = null;
    foreach ($els as &$el) {
        if (isset($el['id']) && $el['id'] === $id) { return $el; }
        if (!empty($el['elements'])) { $r = &itsb_find($el['elements'], $id); if ($r !== null) { return $r; } }
    }
    return $null;
}
function itsb_contains(array $el, $id) {
    if (isset($el['id']) && $el['id'] === $id) { return true; }
    foreach ($el['elements'] ?? array() as $c) { if (itsb_contains($c, $id)) { return true; } }
    return false;
}
function itsb_remove(array &$els, $id) {
    foreach ($els as $i => &$el) {
        if (isset($el['id']) && $el['id'] === $id) { array_splice($els, $i, 1); return true; }
        if (!empty($el['elements']) && itsb_remove($el['elements'], $id)) { return true; }
    }
    return false;
}
function itsb_uid() { return substr(md5(uniqid('', true)), 0, 7); }
/** New random ids for every element, so a copied section never clashes with its original on the same page. */
function itsb_reid(array $el) { $el['id'] = itsb_uid(); foreach ($el['elements'] ?? array() as $i => $c) { $el['elements'][$i] = itsb_reid($c); } return $el; }

// ------------------------------------------------------------------ validate the map against the parent
$problems = array();
$check = function ($pair, $label) use (&$parent_tree, &$problems) {
    list($id, $type) = $pair;
    $el = &itsb_find($parent_tree, $id);
    if ($el === null) { $problems[] = "$label: element $id not found"; return; }
    if ($type !== '' && ($el['widgetType'] ?? '') !== $type) { $problems[] = "$label: element $id is " . ($el['widgetType'] ?? $el['elType']) . ", expected $type"; }
};
foreach (array('hero_heading', 'hero_text', 'hero_list', 'hero_image', 'offer_heading', 'offer_intro_remove', 'process_heading', 'faq_accordion') as $k) { $check($map[$k], $k); }
foreach ($map['offer_cards'] as $i => $c) { $check($c, "offer card $i"); }
foreach ($map['process_boxes'] as $i => $c) { $check($c, "process step $i"); }
foreach ($map['remove_sections_with'] as $r) { $check(array($r[0], ''), 'section to remove'); }
if ($problems) { foreach ($problems as $p) { itsb_log('FAIL', $p); } WP_CLI::error('The parent page layout does not match the map; nothing was changed.'); }
itsb_log('OK', 'parent page layout matches the map (' . (count($map['offer_cards']) + count($map['process_boxes']) + 8) . ' elements)');

$top_of = function (array $tree, $id) { foreach ($tree as $i => $el) { if (itsb_contains($el, $id)) { return $i; } } return null; };
$card_images = array();
foreach ($map['offer_cards'] as $i => $c) { $el = &itsb_find($parent_tree, $c[0]); $card_images[$P['card_order'][$i]] = $el['settings']['image'] ?? null; unset($el); }

// ------------------------------------------------------------------ build one page tree
function itsb_page_tree(array $tree, array $page, array $spec, $card_image, array $siblings, $parent_url) {
    $P = $spec['parent']; $map = $P['elementor_map'];
    // breadcrumb + hero
    $h = &itsb_find($tree, $map['hero_heading'][0]); $h['settings']['title'] = $page['h1']; unset($h);
    $t = &itsb_find($tree, $map['hero_text'][0]); $t['settings']['editor'] = '<p>' . esc_html($page['intro']) . '</p>'; unset($t);
    $l = &itsb_find($tree, $map['hero_list'][0]);
    $items = $l['settings']['icon_list'] ?? array();
    $tpl = $items ? end($items) : array('selected_icon' => array('value' => 'fas fa-check-circle', 'library' => 'fa-solid'));
    $new = array();
    foreach ($page['bullets'] as $i => $b) { $it = isset($items[$i]) ? $items[$i] : $tpl; $it['text'] = $b; $it['_id'] = itsb_uid(); unset($it['link']); $new[] = $it; }
    $l['settings']['icon_list'] = $new; unset($l);
    if ($card_image) { $im = &itsb_find($tree, $map['hero_image'][0]); $im['settings']['image'] = $card_image; unset($im); }
    // what's included
    $oh = &itsb_find($tree, $map['offer_heading'][0]); $oh['settings']['title'] = $page['included_heading']; unset($oh);
    itsb_remove($tree, $map['offer_intro_remove'][0]);
    foreach ($map['offer_cards'] as $i => $c) {
        $card = &itsb_find($tree, $c[0]);
        if (isset($page['included'][$i])) {
            $card['settings']['title_text'] = $page['included'][$i]['title'];
            $card['settings']['description_text'] = $page['included'][$i]['text'];
            $card['settings']['image'] = array('url' => '', 'id' => '');
            $card['settings']['link'] = array('url' => '', 'is_external' => '', 'nofollow' => '');
            unset($card);
        } else { unset($card); itsb_remove($tree, $c[0]); }
    }
    // process
    $ph = &itsb_find($tree, $map['process_heading'][0]); $ph['settings']['title'] = $page['process_heading']; unset($ph);
    foreach ($map['process_boxes'] as $i => $c) {
        $box = &itsb_find($tree, $c[0]);
        if (isset($page['process'][$i])) { $box['settings']['title_text'] = $page['process'][$i]['title']; $box['settings']['description_text'] = $page['process'][$i]['text']; unset($box); }
        else { unset($box); itsb_remove($tree, $c[0]); }
    }
    // FAQ
    $acc = &itsb_find($tree, $map['faq_accordion'][0]);
    $tplq = $acc['settings']['ekit_accordion_items'][0] ?? array();
    $qs = array();
    foreach ($page['faqs'] as $f) { $q = $tplq; $q['acc_title'] = $f['q']; $q['acc_content'] = '<p>' . esc_html($f['a']) . '</p>'; $q['_id'] = itsb_uid(); $qs[] = $q; }
    $acc['settings']['ekit_accordion_items'] = $qs; unset($acc);
    // remove sections that don't belong on a sub-service page
    foreach ($map['remove_sections_with'] as $r) { foreach ($tree as $i => $el) { if (itsb_contains($el, $r[0])) { array_splice($tree, $i, 1); break; } } }
    // breadcrumb: a small text widget above the H1 in the same column
    $crumb = array('id' => itsb_uid(), 'elType' => 'widget', 'widgetType' => 'text-editor', 'elements' => array(), 'settings' => array(
        'editor' => '<p class="itech-breadcrumb"><a href="' . esc_url(home_url('/')) . '">Home</a> <span aria-hidden="true">&rsaquo;</span> <a href="' . esc_url($parent_url) . '">' . esc_html($P['title']) . '</a> <span aria-hidden="true">&rsaquo;</span> <span aria-current="page">' . esc_html($page['card']) . '</span></p>'));
    itsb_insert_before($tree, $map['hero_heading'][0], $crumb);
    // related services: a new section after the FAQ section
    $links = array(array('text' => $P['title'] . ' (overview)', 'link' => array('url' => $parent_url, 'is_external' => '', 'nofollow' => ''), 'selected_icon' => array('value' => 'fas fa-chevron-right', 'library' => 'fa-solid'), '_id' => itsb_uid()));
    foreach ($siblings as $s) { $links[] = array('text' => $s['title'], 'link' => array('url' => $s['url'], 'is_external' => '', 'nofollow' => ''), 'selected_icon' => array('value' => 'fas fa-chevron-right', 'library' => 'fa-solid'), '_id' => itsb_uid()); }
    $related = array('id' => itsb_uid(), 'elType' => 'section', 'isInner' => false, 'settings' => array('padding' => array('unit' => 'px', 'top' => '40', 'right' => '0', 'bottom' => '40', 'left' => '0', 'isLinked' => false), 'css_classes' => 'itech-related-services'),
        'elements' => array(array('id' => itsb_uid(), 'elType' => 'column', 'isInner' => false, 'settings' => array('_column_size' => 100, '_inline_size' => null), 'elements' => array(
            array('id' => itsb_uid(), 'elType' => 'widget', 'widgetType' => 'heading', 'elements' => array(), 'settings' => array('title' => $P['related_heading'], 'header_size' => 'h2', 'align' => 'center')),
            array('id' => itsb_uid(), 'elType' => 'widget', 'widgetType' => 'icon-list', 'elements' => array(), 'settings' => array('icon_list' => $links, 'view' => 'traditional', 'icon_align' => 'center')),
        ))));
    $faq_top = null; foreach ($tree as $i => $el) { if (itsb_contains($el, $map['faq_accordion'][0])) { $faq_top = $i; } }
    array_splice($tree, $faq_top === null ? count($tree) : $faq_top + 1, 0, array($related));
    return $tree;
}
function itsb_insert_before(array &$els, $id, array $new) {
    foreach ($els as $i => &$el) {
        if (isset($el['id']) && $el['id'] === $id) { array_splice($els, $i, 0, array($new)); return true; }
        if (!empty($el['elements']) && itsb_insert_before($el['elements'], $id, $new)) { return true; }
    }
    return false;
}
function itsb_faq_schema(array $page) {
    $q = array();
    foreach ($page['faqs'] as $f) { $q[] = array('@type' => 'Question', 'name' => $f['q'], 'acceptedAnswer' => array('@type' => 'Answer', 'text' => $f['a'])); }
    return '<script type="application/ld+json">' . wp_json_encode(array('@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $q), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>';
}

// ------------------------------------------------------------------ plan / apply
$parent_url = get_permalink($parent);
$plan = array();
foreach ($spec['pages'] as $pg) {
    $path = $P['slug'] . '/' . $pg['slug'];
    $existing = get_page_by_path($path);
    $plan[] = array('page' => $pg, 'path' => $path, 'existing' => $existing, 'url' => home_url('/' . $path . '/'));
}
$backup = array('spec' => basename($spec_file), 'created' => array(), 'updated' => array(), 'parent' => null);
$file = $backup_dir . '/subservices-' . $P['slug'] . '-' . gmdate('Ymd-His') . '.json';
$save = function () use (&$backup, $file) { if (!is_dir(dirname($file))) { wp_mkdir_p(dirname($file)); } file_put_contents($file, wp_json_encode($backup, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)); };
$meta_keys = array('_elementor_data', '_elementor_edit_mode', '_elementor_template_type', '_elementor_version', '_elementor_page_settings', '_wp_page_template', 'rank_math_title', 'rank_math_description', 'rank_math_focus_keyword', '_itechnolabs_schema_code');

foreach ($plan as $item) {
    $pg = $item['page'];
    $siblings = array();
    foreach ($plan as $o) { if ($o['path'] !== $item['path']) { $siblings[] = array('title' => $o['page']['card'], 'url' => $o['url']); } }
    $tree = itsb_page_tree($parent_tree, $pg, $spec, $card_images[$pg['card']] ?? null, $siblings, $parent_url);
    $json = wp_json_encode($tree);
    $what = ($item['existing'] ? 'UPDATE' : 'CREATE') . " /{$item['path']}/  \"{$pg['h1']}\"  title=\"{$pg['seo_title']}\"  (" . strlen($json) . ' bytes of layout)';
    if ($mode !== 'apply') { itsb_log('PLAN', $what); continue; }

    if ($item['existing']) {
        $id = $item['existing']->ID;
        $u = array('id' => $id, 'post_title' => $item['existing']->post_title, 'post_status' => $item['existing']->post_status, 'meta' => array());
        foreach ($meta_keys as $k) { $u['meta'][$k] = metadata_exists('post', $id, $k) ? get_post_meta($id, $k, true) : null; }
        $backup['updated'][] = $u; $save();
        wp_update_post(array('ID' => $id, 'post_title' => $pg['card'], 'post_status' => $status));
    } else {
        $id = wp_insert_post(array('post_type' => 'page', 'post_status' => $status, 'post_parent' => $parent->ID, 'post_name' => $pg['slug'], 'post_title' => $pg['card'], 'post_content' => ''), true);
        if (is_wp_error($id)) { itsb_log('FAIL', $item['path'] . ': ' . $id->get_error_message()); continue; }
        $backup['created'][] = $id; $save();
    }
    update_post_meta($id, '_elementor_edit_mode', 'builder');
    update_post_meta($id, '_elementor_template_type', 'wp-page');
    update_post_meta($id, '_elementor_version', get_post_meta($parent->ID, '_elementor_version', true));
    $ps = get_post_meta($parent->ID, '_elementor_page_settings', true);
    if ($ps) { update_post_meta($id, '_elementor_page_settings', $ps); }
    $tplf = get_post_meta($parent->ID, '_wp_page_template', true);
    if ($tplf) { update_post_meta($id, '_wp_page_template', $tplf); }
    update_post_meta($id, '_elementor_data', wp_slash($json));
    update_post_meta($id, 'rank_math_title', $pg['seo_title']);
    update_post_meta($id, 'rank_math_description', $pg['meta_description']);
    update_post_meta($id, 'rank_math_focus_keyword', $pg['keyword']);
    update_post_meta($id, '_itechnolabs_schema_code', wp_slash(itsb_faq_schema($pg)));
    itsb_log('APPLIED', $what . " -> page $id");
}

// parent cards link to the new pages
$ptree = $parent_tree; $changed = array();
foreach ($map['offer_cards'] as $i => $c) {
    $title = $P['card_order'][$i];
    foreach ($plan as $item) {
        if ($item['page']['card'] !== $title) { continue; }
        $card = &itsb_find($ptree, $c[0]);
        if (($card['settings']['link']['url'] ?? '') !== $item['url']) { $card['settings']['link'] = array('url' => $item['url'], 'is_external' => '', 'nofollow' => ''); $changed[] = "$title -> {$item['url']}"; }
        unset($card);
    }
}
if (!$changed) { itsb_log('ALREADY', 'parent cards already link to the pages'); }
elseif ($mode !== 'apply') { itsb_log('PLAN', 'link parent cards: ' . implode('; ', $changed)); }
else {
    $backup['parent'] = array('id' => $parent->ID, 'elementor_data' => $parent_raw); $save();
    update_post_meta($parent->ID, '_elementor_data', wp_slash(wp_json_encode($ptree)));
    itsb_log('APPLIED', 'parent cards link to the new pages (' . count($changed) . ')');
}
foreach ($P['not_built'] ?? array() as $nb) { itsb_log('SKIPPED', $nb['card'] . ': ' . $nb['reason']); }

if ($mode === 'apply') {
    // Only these pages' cached Elementor CSS is dropped (rebuilt on next view); the site-wide cache is left alone.
    itsb_flush_page($parent->ID);
    foreach (array_merge($backup['created'], array_column($backup['updated'], 'id')) as $pid) { itsb_flush_page($pid); }
    wp_cache_flush();
    $save();
    WP_CLI::log("Backup: $file");
    WP_CLI::log("Undo: wp eval-file " . __FILE__ . " revert $file");
}
WP_CLI::log($mode === 'apply' ? 'RESULT: APPLIED' : 'RESULT: DRY RUN (nothing changed)');
