<?php
if (!defined('ABSPATH')) exit;
if (!function_exists('chld_thm_cfg_locale_css')):
    function chld_thm_cfg_locale_css($uri)
    {
        if (empty($uri) && is_rtl() && file_exists(get_template_directory() . '/rtl.css'))
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter('locale_stylesheet_uri', 'chld_thm_cfg_locale_css');

if (!function_exists('chld_thm_cfg_parent_css')):
    function chld_thm_cfg_parent_css()
    {
        // No dependency on Owl Carousel / theme Font Awesome: both are removed below.
        wp_enqueue_style(
            'chld_thm_cfg_parent',
            trailingslashit(get_template_directory_uri()) . 'style.css',
            array(),
            filemtime(get_template_directory() . '/style.css')
        );
    }
endif;
add_action('wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10);

/**
 * Asset clean-up (Unused CSS/JS audit, 19 Nov 2026).
 *
 * Runs after the parent theme (10), ElementsKit (99) and Header Footer
 * Elementor have enqueued their files. Each removal was checked against
 * the 25 published pages; see docs/audit-remediation-2026-11-19.md.
 */
function itech_asset_cleanup()
{
    if (is_admin() || (defined('ELEMENTOR_VERSION') && isset($_GET['elementor-preview']))) {
        return;
    }

    // 1. Owl Carousel: no .owl-carousel markup on any page.
    wp_dequeue_script('owl-carousel');
    wp_dequeue_style('owl-carousel');

    // 2. ElementsKit force-loads header-builder assets on every page. The site
    //    header is the theme's own header.php, so none of these widgets exist.
    //    If one is ever added to a page, Elementor enqueues its files again
    //    through the widget's own script/style dependencies.
    foreach (array('ekit-nav-menu', 'ekit-menu', 'magnific-popup', 'ekit-header-search', 'ekit-header-offcanvas') as $handle) {
        wp_dequeue_script($handle);
    }
    foreach (array('ekit-nav-menu', 'ekit-header-search', 'ekit-header-offcanvas', 'ekit-header-info') as $handle) {
        wp_dequeue_style($handle);
    }

    // 3. Font Awesome: one copy instead of up to five. Every icon on the site
    //    is a Font Awesome 5 solid or brands icon, so Elementor's FA5 files
    //    cover them. Only swap when Elementor has registered its copies.
    //    Elementor registers these handles only when its "inline font icons"
    //    option is off (as on live); register them from Elementor's bundled
    //    files otherwise, under the same handle names so nothing loads twice.
    if (defined('ELEMENTOR_ASSETS_URL') && defined('ELEMENTOR_ASSETS_PATH')) {
        $fa = array(
            'elementor-icons-shared-0'  => array('fontawesome.min.css', array()),
            'elementor-icons-fa-solid'  => array('solid.min.css', array('elementor-icons-shared-0')),
            'elementor-icons-fa-brands' => array('brands.min.css', array('elementor-icons-shared-0')),
        );
        foreach ($fa as $handle => $file) {
            if (!wp_style_is($handle, 'registered') && file_exists(ELEMENTOR_ASSETS_PATH . 'lib/font-awesome/css/' . $file[0])) {
                wp_register_style($handle, ELEMENTOR_ASSETS_URL . 'lib/font-awesome/css/' . $file[0], $file[1], '5.15.3');
            }
        }
    }
    if (wp_style_is('elementor-icons-fa-solid', 'registered') && wp_style_is('elementor-icons-fa-brands', 'registered')) {
        wp_dequeue_style('fontawesome-4-5');                        // theme FA 5.11 all.min.css
        wp_dequeue_style('hfe-social-share-icons-fontawesome');     // unminified duplicate of elementor-icons-shared-0
        wp_dequeue_style('hfe-social-share-icons-brands');          // duplicate of elementor-icons-fa-brands
        wp_dequeue_style('hfe-nav-menu-icons');                     // duplicate of elementor-icons-fa-solid
        wp_enqueue_style('elementor-icons-fa-solid');
        wp_enqueue_style('elementor-icons-fa-brands');
    }

    // 4. Elementor icon font (eicons) is enqueued twice: by Elementor (5.53)
    //    and by Header Footer Elementor (5.34). Keep Elementor's.
    if (wp_style_is('elementor-icons', 'enqueued')) {
        wp_dequeue_style('hfe-elementor-icons');
    }

    //    Header Footer Elementor also loads old copies of Elementor's icon-list
    //    and social-icons widget CSS; Elementor then prints its own copy again
    //    when the footer widgets render. Load Elementor's copy once, in <head>.
    $widget_swaps = array('hfe-icons-list' => 'widget-icon-list', 'hfe-social-icons' => 'widget-social-icons');
    foreach ($widget_swaps as $hfe_handle => $elementor_handle) {
        if (wp_style_is($elementor_handle, 'registered')) {
            wp_dequeue_style($hfe_handle);
            wp_enqueue_style($elementor_handle);
        }
    }

    // 5. Child theme style.css holds only the theme header comment. Keep the
    //    handle (the theme's customizer CSS is attached to it as inline CSS)
    //    but stop requesting the empty file.
    $styles = wp_styles();
    if (isset($styles->registered['appzend-style'])) {
        $styles->registered['appzend-style']->src = false;
    }

    // 6. Fonts. The theme requests Montserrat (unused) + Livvic from Google
    //    Fonts, and Elementor serves its own local Livvic copy as well. Load
    //    only Elementor's local Livvic (all weights/styles), under Elementor's
    //    own handle so Elementor doesn't add it a second time.
    $uploads = wp_upload_dir();
    $livvic  = '/elementor/google-fonts/css/livvic.css';
    if (file_exists($uploads['basedir'] . $livvic)) {
        wp_dequeue_style('appzend-fonts');
        wp_enqueue_style(
            'elementor-gf-local-livvic',
            set_url_scheme($uploads['baseurl'] . $livvic),
            array(),
            filemtime($uploads['basedir'] . $livvic)
        );
    }
}
add_action('wp_enqueue_scripts', 'itech_asset_cleanup', 100);

/**
 * Elementor loads local Roboto and Roboto Slab (its default kit fonts) on
 * every page, but no text on the site is set in either. Elementor queues
 * fonts from wp_head and again from wp_footer, so drop them at output time.
 */
add_filter('style_loader_tag', function ($tag, $handle) {
    if (is_admin() || isset($_GET['elementor-preview'])) {
        return $tag;
    }
    if (in_array($handle, array('elementor-gf-local-roboto', 'elementor-gf-local-robotoslab'), true)) {
        return '';
    }
    return $tag;
}, 10, 2);
function itech_child_common_css()
{
    // Version by file modification time so browsers cache until the file changes.
    wp_enqueue_style(
        'itech-common-css',
        get_stylesheet_directory_uri() . '/assets/css/common.css',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/css/common.css')
    );
    // newhome.css styles the homepage template only (checked against every
    // published page); its two site-wide rules now live in common.css.
    if (is_page_template('custom-pages/new-homepage.php')) {
        wp_enqueue_style(
            'itech-newhome-css',
            get_stylesheet_directory_uri() . '/assets/css/newhome.css',
            array(),
            filemtime(get_stylesheet_directory() . '/assets/css/newhome.css')
        );
    }
}
add_action('wp_enqueue_scripts', 'itech_child_common_css');

/**
 * Site-wide script: "Get started" popup + HubSpot form loader.
 * Previously the popup code only loaded on the homepage (inside why-choose.js),
 * so popup buttons on service pages did nothing.
 */
function itech_enqueue_site_script()
{
    wp_enqueue_script(
        'itech-site',
        get_stylesheet_directory_uri() . '/assets/js/itech-site.js',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/js/itech-site.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'itech_enqueue_site_script');

/**
 * Slick carousel (homepage mobile sliders). Self-hosted copy of
 * slick-carousel 1.8.1 (MIT) instead of jsDelivr tags in the template body:
 * no third-party dependency, loaded only where the homepage template is used.
 */
function itech_enqueue_homepage_assets()
{
    if (!is_page_template('custom-pages/new-homepage.php')) {
        return;
    }
    $uri = get_stylesheet_directory_uri() . '/assets/vendor/slick/';
    wp_enqueue_style('slick', $uri . 'slick.css', array(), '1.8.1');
    wp_enqueue_script('slick', $uri . 'slick.min.js', array('jquery'), '1.8.1', true);
}
add_action('wp_enqueue_scripts', 'itech_enqueue_homepage_assets');

/**
 * HubSpot form placeholder. itech-site.js loads the HubSpot embed script once
 * per page and creates each form in its placeholder.
 *
 * @param string $form_id HubSpot form GUID.
 * @param bool   $lazy    Create the form only when its popup is first opened.
 * @return string
 */
function itech_hubspot_form($form_id, $lazy = false)
{
    static $count = 0;
    $count++;
    return sprintf(
        '<div class="itech-hs-form" id="itech-hs-form-%1$d" data-portal-id="22824688" data-form-id="%2$s" data-region="na1"%3$s></div>',
        $count,
        esc_attr($form_id),
        $lazy ? ' data-lazy="1"' : ''
    );
}

// Override the parent theme's HubSpot shortcodes, which each printed their own
// copy of the HubSpot embed script (up to 4 copies per page).
function itech_override_hubspot_shortcodes()
{
    remove_shortcode('hubspot_vertical_form');
    remove_shortcode('hubspot_horizontal_form');
    add_shortcode('hubspot_vertical_form', function () {
        return itech_hubspot_form('45b7d1ce-b5de-45bc-b793-223818478cbb');
    });
    add_shortcode('hubspot_horizontal_form', function () {
        return itech_hubspot_form('a562e859-bc5b-49e3-8d9a-36b0b3316286');
    });
}
add_action('init', 'itech_override_hubspot_shortcodes', 20);


// image urls replace code start
function indigenetech_replace_local_upload_url($value) {
    $scheme = is_ssl() ? 'https://' : 'http://';
    $host = isset($_SERVER['HTTP_HOST'])
        ? sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST']))
        : wp_parse_url(home_url(), PHP_URL_HOST);

    $live_url = $scheme . $host . '/wp-content/uploads/';
    $local_urls = array(
        'http://indigenetech.local/wp-content/uploads/',
        'https://indigenetech.local/wp-content/uploads/',
        'http:\/\/indigenetech.local\/wp-content\/uploads\/',
        'https:\/\/indigenetech.local\/wp-content\/uploads\/',
    );
    if (is_string($value)) {
        $value = str_replace(
            $local_urls,
            $live_url,
            $value
        );
        return $value;
    }
    if (is_array($value)) {
        foreach ($value as $key => $item) {
            $value[$key] =
                indigenetech_replace_local_upload_url($item);
        }
        return $value;
    }
    if (is_object($value)) {
        foreach ($value as $key => $item) {
            $value->$key =
                indigenetech_replace_local_upload_url($item);
        }
        return $value;
    }
    return $value;
}
add_filter(
    'the_content',
    'indigenetech_replace_local_upload_url',
    9999
);
add_filter(
    'the_excerpt',
    'indigenetech_replace_local_upload_url',
    9999
);
add_filter(
    'widget_text',
    'indigenetech_replace_local_upload_url',
    9999
);
add_filter(
    'acf/load_value',
    'indigenetech_replace_local_upload_url',
    9999
);
add_filter(
    'elementor/frontend/builder_content_data',
    'indigenetech_replace_local_upload_url',
    9999
);
add_filter(
    'elementor/frontend/the_content',
    'indigenetech_replace_local_upload_url',
    9999
);
add_filter(
    'wp_get_attachment_url',
    'indigenetech_replace_local_upload_url',
    9999
);
add_filter(
    'wp_get_attachment_image_src',
    function ($image) {
        if (is_array($image) && ! empty($image[0])) {
            $image[0] =
                indigenetech_replace_local_upload_url(
                    $image[0]
                );
        }
        return $image;
    },
    9999
);
add_filter(
    'wp_calculate_image_srcset',
    function ($sources) {
        if (is_array($sources)) {
            foreach ($sources as $width => $source) {
                if (isset($source['url'])) {
                    $sources[$width]['url'] =
                        indigenetech_replace_local_upload_url(
                            $source['url']
                        );
                }
            }
        }
        return $sources;
    },
    9999
);
// image urls code end


require_once get_stylesheet_directory() . '/custom-parts/custom-schema.php';


function custom_why_choose_section() {
    // Load the slider's CSS/JS only on pages that actually use this shortcode.
    wp_enqueue_style(
        'why-choose-style',
        get_stylesheet_directory_uri() . '/assets/css/why-choose.css',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/css/why-choose.css')
    );
    wp_enqueue_script(
        'why-choose-script',
        get_stylesheet_directory_uri() . '/assets/js/why-choose.js',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/js/why-choose.js'),
        true
    );
    ob_start(); ?>
    <section class="why-choose">
        <div class="slider-container">
            <div class="slider-track" id="sliderTrack">
                <!-- Slide items -->
                <div class="feature-box">
                    <span class="number">01</span>
                    <h3>Engineering Built to Scale</h3>
                    <p>Proven engineering standards ensure software remains reliable, secure, high-performing, and scalable as business requirements grow over time.</p>
                </div>
                <div class="feature-box">
                    <span class="number">02</span>
                    <h3>Experience That Delivers</h3>
                    <p>Experience across startups and global enterprises supports consistent delivery, practical insight, and dependable outcomes through tested development practices.</p>
                </div>
                <div class="feature-box">
                    <span class="number">03</span>
                    <h3>Flexible Working Models</h3>
                    <p>Flexible engagement models are available, aligned with project goals, timelines, scope requirements, and budget expectations for better control.</p>
                </div>
                <div class="feature-box">
                    <span class="number">04</span>
                    <h3>Always-On Client Support</h3>
                    <p>Clear communication and dependable support remain available day and night, ensuring timely updates, issue resolution, and smooth collaboration.</p>
                </div>
                <div class="feature-box">
                    <span class="number">05</span>
                    <h3>Compliance & Quality Focus</h3>
                    <p>Quality assurance and compliance-first processes ensure stable releases, reduced risk, and stronger long-term performance across all digital solutions.</p>
                </div>
                <div class="feature-box">
                    <span class="number">06</span>
                    <h3>Transparent Delivery Process</h3>
                    <p>Clear planning, regular updates, and milestone-based execution support transparency, faster decisions, and predictable delivery outcomes for every project stage.</p>
                </div>
            </div>

            <div class="slider-buttons">
                <button class="arrow left" onclick="moveSlide(-1)">&#10094;</button>
                <button class="arrow right" onclick="moveSlide(1)">&#10095;</button>
            </div>
        </div>
    </section>
<?php
    return ob_get_clean();
}
add_shortcode('why_choose_section', 'custom_why_choose_section');

function enqueue_why_choose_assets()
{
    // custom.css: site-wide overrides. why-choose.css/.js are now enqueued by
    // the [why_choose_section] shortcode itself (no page uses it today).
    wp_enqueue_style(
        'custom-style',
        get_stylesheet_directory_uri() . '/assets/css/custom.css',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/css/custom.css')
    );
}
add_action('wp_enqueue_scripts', 'enqueue_why_choose_assets');

add_action('wp_nav_menu_item_custom_fields', function ($item_id, $item) {

    if ($item->menu_item_parent != 0) return;

    $cta_title = get_post_meta($item_id, '_mega_cta_title', true);
    $cta_desc  = get_post_meta($item_id, '_mega_cta_desc', true);
    $cta_btn   = get_post_meta($item_id, '_mega_cta_btn', true);
    $cta_url   = get_post_meta($item_id, '_mega_cta_url', true);
?>
    <div class="description-wide">
        <strong>Mega Menu CTA (30%)</strong>
        <p>
            <label>CTA Title
                <input type="text" class="widefat"
                    name="mega_cta_title[<?php echo $item_id; ?>]"
                    value="<?php echo esc_attr($cta_title); ?>">
            </label>
        </p>
        <p>
            <label>CTA Description
                <textarea class="widefat"
                    name="mega_cta_desc[<?php echo $item_id; ?>]"><?php echo esc_textarea($cta_desc); ?></textarea>
            </label>
        </p>
        <p>
            <label>Button Text
                <input type="text" class="widefat"
                    name="mega_cta_btn[<?php echo $item_id; ?>]"
                    value="<?php echo esc_attr($cta_btn); ?>">
            </label>
        </p>
        <p>
            <label>Button URL
                <input type="url" class="widefat"
                    name="mega_cta_url[<?php echo $item_id; ?>]"
                    value="<?php echo esc_url($cta_url); ?>">
            </label>
        </p>
    </div>
<?php
}, 20, 2);

add_action('wp_nav_menu_item_custom_fields', function ($item_id, $item) {
    if ($item->menu_item_parent == 0) return;
    $icon = get_post_meta($item_id, '_menu_item_icon', true);
?>
    <p class="description description-wide">
        <label>
            Sub Menu Icon URL
            <input type="text"
                class="widefat"
                name="menu_item_icon[<?php echo $item_id; ?>]"
                value="<?php echo esc_attr($icon); ?>">
        </label>
    </p>
<?php
}, 25, 2);

add_action('wp_update_nav_menu_item', function ($menu_id, $item_id) {
    $fields = [
        'mega_cta_title' => '_mega_cta_title',
        'mega_cta_desc'  => '_mega_cta_desc',
        'mega_cta_btn'   => '_mega_cta_btn',
        'mega_cta_url'   => '_mega_cta_url',
    ];
    foreach ($fields as $post => $meta) {
        if (isset($_POST[$post][$item_id])) {
            update_post_meta(
                $item_id,
                $meta,
                sanitize_text_field($_POST[$post][$item_id])
            );
        }
    }
    if (isset($_POST['menu_item_icon'][$item_id])) {
        update_post_meta(
            $item_id,
            '_menu_item_icon',
            esc_url_raw($_POST['menu_item_icon'][$item_id])
        );
    }
}, 10, 2);

/**
 * True for menu items whose URL is only a placeholder ("#" or empty).
 */
function itech_is_placeholder_url($url)
{
    $url = trim((string) $url);
    return $url === '' || $url === '#';
}

/**
 * Mobile menu (parent theme walker): a "#" parent item becomes a toggle for
 * its sub-menu instead of a link that jumps to the top of the page.
 * itech-site.js forwards clicks on it to the sub-menu's expand button.
 */
add_filter('nav_menu_link_attributes', function ($atts) {
    if (isset($atts['href']) && itech_is_placeholder_url($atts['href'])) {
        unset($atts['href']);
        $atts['role'] = 'button';
        $atts['tabindex'] = '0';
        $atts['data-itech-menu-toggle'] = '1';
    }
    return $atts;
});

class Menu_Icon_Walker extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        if ($depth === 0) {
            $output .= '<li class="menu-item has-mega">';
            if (itech_is_placeholder_url($item->url)) {
                // "What We Do": opens the mega menu, has no page of its own.
                // A link to "#" jumped to the top of the page; render a focusable
                // toggle instead (the menu opens on hover and on keyboard focus).
                $output .= '<a role="button" tabindex="0" aria-haspopup="true">' . esc_html($item->title) . '</a>';
            } else {
                $output .= '<a href="' . esc_url($item->url) . '">' . esc_html($item->title) . '</a>';
            }
            $args->mega_parent = $item;
            return;
        }

        $icon = '';
        $icon_url = get_post_meta($item->ID, '_menu_item_icon', true);

        if (! empty($icon_url)) {
            // Lazy: the mega menu is hidden until opened, so these icons
            // (10 images, ~250 KB) no longer download on every page view.
            $icon = '<img src="' . esc_url($icon_url) . '" class="submenu-icon" alt="" loading="lazy" decoding="async">';
        }

        $output .= '<li class="menu-item">';
        $output .= '<a href="' . esc_url($item->url) . '">';
        $output .= $icon;
        $output .= '<span>' . esc_html($item->title) . '</span>';
        $output .= '</a>';
    }

    function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= '</li>';
    }

    function start_lvl(&$output, $depth = 0, $args = null) {
        if ($depth === 0) {
            $parent = $args->mega_parent;
            $title = get_post_meta($parent->ID, '_mega_cta_title', true);
            $desc  = get_post_meta($parent->ID, '_mega_cta_desc', true);
            $btn   = get_post_meta($parent->ID, '_mega_cta_btn', true);
            $url   = get_post_meta($parent->ID, '_mega_cta_url', true);
            $output .= '<div class="mega-menu">';
            $output .= '<div class="mega-cta">';
            if ($title) {
                $output .= '<div class="mega-cta-inner">';
                $output .= '<p class="mega-cta-main-heading">' . esc_html($title) . '</p>';
                $output .= '<p>' . esc_html($desc) . '</p>';
                $output .= '<a class="btn btn-primary mega-cta-btn" href="' . esc_url($url) . '">' . esc_html($btn) . '</a>';

                $output .= '</div>';
            }
            $output .= '</div>';
            $output .= '<div class="mega-menu-right">';
        }
        $output .= '<ul class="sub-menu">';
    }

    function end_lvl(&$output, $depth = 0, $args = null) {
        $output .= '</ul>';
        if ($depth === 0) {
            $output .= '</div>'; // 70%
            $output .= '</div>'; // mega-menu
        }
    }
}
function itech_popup_full_code() {
?>
    <div id="itechPopupOverlay" class="itech-popup-overlay">
        <div class="itech-popup">
            <span class="itech-close" id="itechClosePopup">&times;</span>
            <div class="itech-popup-left hide-mob">
                <h2>Grow Your Company with <span>IndigeneTech</span>, a Trusted Indigenous Innovator </h2>
                <p class="white-text">Strengthen your organization’s future using practical digital solutions, covering data systems, automation, and cloud platforms. As a verified Indigenous enterprise, we help move your goals forward while supporting progress and change, beside you throughout. </p>
            </div>
            <div class="itech-popup-right">
                <h3>Start Your Digital Growth with IndigeneTech</h3>
                <p class="itech-form-desc">Send your information and our team will connect with you within 24 hours.</p>
                <div class="itech-form">
                    <?php echo itech_hubspot_form('45b7d1ce-b5de-45bc-b793-223818478cbb', true); ?>
                </div>
            </div>

        </div>
    </div>
<?php
}

add_action('wp_footer', 'itech_popup_full_code');
// Disable Gutenberg (Block Editor)
add_filter('use_block_editor_for_post', '__return_false', 10);
add_filter('use_block_editor_for_post_type', '__return_false', 10);

// Disable block widgets (optional)
add_filter('use_widgets_block_editor', '__return_false');

// WordPress emoji detection script + styles (~25 KB on every page). All
// current browsers render emoji natively.
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('wp_enqueue_scripts', 'wp_enqueue_emoji_styles');


/**
 * Normalise internal links in post/page content so they don't redirect:
 *  - https://indigenetech.ca/... (no www)  -> https://www.indigenetech.ca/...
 *  - /some-page or https://www.indigenetech.ca/some-page -> trailing slash
 * Only touches href attributes pointing at this site's pages (no files,
 * query strings, fragments or wp-* paths). Content in the database is left
 * as is; this keeps old and future links from costing a redirect.
 */
function itech_normalize_internal_links($content)
{
    if (!is_string($content) || $content === '' || strpos($content, 'href=') === false) {
        return $content;
    }
    $content = preg_replace('#href=(["\'])https?://indigenetech\.ca(/|\1)#i', 'href=$1https://www.indigenetech.ca$2', $content);
    return preg_replace_callback(
        '#href=(["\'])((?:https://www\.indigenetech\.ca)?/(?!wp-)[a-z0-9\-/]*[a-z0-9\-])\1#i',
        function ($m) {
            return 'href=' . $m[1] . $m[2] . '/' . $m[1];
        },
        $content
    );
}
add_filter('the_content', 'itech_normalize_internal_links', 10000);
add_filter('elementor/frontend/the_content', 'itech_normalize_internal_links', 10000);


/**
 * The homepage uses a custom template that never renders the page's stored
 * Elementor content, but Elementor still queues the assets of the widgets in
 * that content (tabs, accordion, image box) and WordPress queues the block
 * library. None of that markup is on the page. Drop them just before printing.
 */
function itech_homepage_unrendered_assets()
{
    if (!is_page_template('custom-pages/new-homepage.php') || isset($_GET['elementor-preview'])) {
        return;
    }
    foreach (array('ekit-tab', 'ekit-accordion', 'widget-image-box', 'wp-block-library') as $handle) {
        wp_dequeue_style($handle);
    }
    foreach (array('ekit-tab', 'ekit-accordion', 'ui-slim') as $handle) {
        wp_dequeue_script($handle);
    }
}
add_action('wp_print_styles', 'itech_homepage_unrendered_assets', 1);
add_action('wp_footer', 'itech_homepage_unrendered_assets', 19);


/**
 * Lazy-load images in Elementor content (pages and the footer) that don't
 * already say how to load. Skipped: the page's priority image
 * (fetchpriority="high", usually the LCP banner) and carousel slides, which
 * sit off-screen inside a clipped track and would flash empty when sliding in.
 */
function itech_lazy_content_images($content)
{
    if (is_admin() || !is_string($content) || stripos($content, '<img') === false) {
        return $content;
    }
    return preg_replace_callback('/<img\b[^>]*>/i', function ($m) {
        $tag = $m[0];
        if (preg_match('/\sloading=|fetchpriority=["\']high|swiper-slide-image/i', $tag)) {
            return $tag;
        }
        $extra = ' loading="lazy"' . (preg_match('/\sdecoding=/i', $tag) ? '' : ' decoding="async"');
        return preg_replace('/^<img\b/i', '<img' . $extra, $tag, 1);
    }, $content);
}
add_filter('elementor/frontend/the_content', 'itech_lazy_content_images', 20);

/**
 * Rank Math's schema still carries image URLs from the old local build
 * (http://indigenetech.local/wp-content/uploads/...) on the homepage and the
 * service pages. Rewrite them to this site's uploads URL, like the content
 * filters above already do.
 */
add_filter('rank_math/json_ld', function ($data) {
    return indigenetech_replace_local_upload_url($data);
}, 1000);

/**
 * Share image (og:image / twitter:image) for pages that have none (the
 * homepage, /blog/, IT Staff Augmentation). Rank Math's own "Default
 * OpenGraph Thumbnail" setting wins once it is set.
 */
function itech_default_share_image($url)
{
    $url = indigenetech_replace_local_upload_url((string) $url);
    if ($url !== '') {
        return $url;
    }
    $uploads = wp_upload_dir();
    $file    = '/2025/09/Indegentech-home-banner.webp';
    return file_exists($uploads['basedir'] . $file) ? set_url_scheme($uploads['baseurl'] . $file, 'https') : '';
}
add_filter('rank_math/opengraph/facebook/image', 'itech_default_share_image');
add_filter('rank_math/opengraph/twitter/image', 'itech_default_share_image');


/**
 * Repair JSON-LD blocks that are invalid only because of raw line breaks or
 * tabs inside their strings (pasted FAQ answers). Search engines ignore an
 * invalid block entirely. Wording is untouched; blocks that are still
 * invalid after the repair are left exactly as they were.
 */
function itech_repair_jsonld($html)
{
    if (!is_string($html) || stripos($html, 'application/ld+json') === false) {
        return $html;
    }
    return preg_replace_callback(
        '#(<script\b[^>]*type=["\']application/ld\+json["\'][^>]*>)(.*?)(</script>)#is',
        function ($m) {
            if (json_decode($m[2]) !== null || json_last_error() === JSON_ERROR_NONE) {
                return $m[0];
            }
            $data = json_decode(preg_replace('/[\x00-\x1F]+/', ' ', $m[2]), true);
            if ($data === null) {
                return $m[0];
            }
            return $m[1] . wp_json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . $m[3];
        },
        $html
    );
}
add_filter('the_content', 'itech_repair_jsonld', 10001);
add_filter('elementor/frontend/the_content', 'itech_repair_jsonld', 10001);


/**
 * Schema types (approved SEO proposals SC1, SC3; 20 Nov 2026).
 * SC1: the homepage is a WebPage, not an Article by a Person.
 * SC3: the 10 service pages describe a Service provided by the business,
 *      instead of an Article by a Person. FAQ schema is left as it is.
 */
function itech_service_page_slugs()
{
    return array(
        'ai-ml-development', 'app-development', 'blockchain-solutions', 'cloud-solutions',
        'custom-vr-development', 'enterprise-custom-software-development', 'erp-solutions',
        'it-consulting', 'it-staff-augmentation', 'web-development',
    );
}

add_filter('rank_math/json_ld', function ($data) {
    if (!is_array($data) || !(is_front_page() || is_page(itech_service_page_slugs()))) {
        return $data;
    }
    $article_key = null;
    foreach ($data as $key => $entity) {
        if (!is_array($entity) || !isset($entity['@type'])) {
            continue;
        }
        $types = (array) $entity['@type'];
        if (array_intersect($types, array('Article', 'BlogPosting', 'NewsArticle'))) {
            $article_key = $key;
        }
        // The article's author (/author/...) only exists for the Article.
        if (in_array('Person', $types, true) && isset($entity['@id']) && strpos($entity['@id'], '/author/') !== false) {
            unset($data[$key]);
        }
    }
    if ($article_key === null) {
        return $data;
    }
    $article = $data[$article_key];
    unset($data[$article_key]);

    if (is_page(itech_service_page_slugs())) {
        $publisher = isset($data['publisher']['@id']) ? $data['publisher']['@id'] : home_url('/#organization');
        $service   = array(
            '@type'       => 'Service',
            '@id'         => get_permalink() . '#service',
            // The SEO title without a trailing "- IndigeneTech" / "| IndigeneTech".
            'name'        => !empty($article['headline'])
                ? preg_replace('/\s*[-|\x{2013}]\s*' . preg_quote(get_bloginfo('name'), '/') . '\s*$/u', '', $article['headline'])
                : get_the_title(),
            'url'         => get_permalink(),
            'provider'    => array('@id' => $publisher),
            'areaServed'  => array('@type' => 'Country', 'name' => 'Canada'),
        );
        if (!empty($article['description'])) {
            $service['description'] = $article['description'];
        }
        if (!empty($article['image'])) {
            $service['image'] = $article['image'];
        }
        if (!empty($article['mainEntityOfPage'])) {
            $service['mainEntityOfPage'] = $article['mainEntityOfPage'];
        }
        $data['itechService'] = $service;
    }
    return $data;
}, 1001);

// SC1: no "Written by" / "Time to read" labels in homepage share cards.
add_filter('rank_math/opengraph/slack_enhanced_data', function ($data) {
    return is_front_page() ? array() : $data;
});
