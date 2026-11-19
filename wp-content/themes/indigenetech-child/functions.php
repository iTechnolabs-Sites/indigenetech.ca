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
        wp_enqueue_style('chld_thm_cfg_parent', trailingslashit(get_template_directory_uri()) . 'style.css', array('owl-carousel', 'fontawesome-4-5'));
    }
endif;
add_action('wp_enqueue_scripts', 'chld_thm_cfg_parent_css', 10);
function itech_child_common_css()
{
    // Version by file modification time so browsers cache until the file changes.
    wp_enqueue_style(
        'itech-common-css',
        get_stylesheet_directory_uri() . '/assets/css/common.css',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/css/common.css')
    );
    wp_enqueue_style(
        'itech-newhome-css',
        get_stylesheet_directory_uri() . '/assets/css/newhome.css',
        array(),
        filemtime(get_stylesheet_directory() . '/assets/css/newhome.css')
    );
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
    wp_enqueue_style('custom-style', get_stylesheet_directory_uri() . '/assets/css/custom.css');
    if (is_page('home') || is_front_page() || is_page('about')) {

        wp_enqueue_style(
            'why-choose-style',
            get_stylesheet_directory_uri() . '/assets/css/why-choose.css',
            array(),
            filemtime(get_stylesheet_directory() . '/assets/css/why-choose.css'), // Cache-busting
            'all'
        );
        wp_enqueue_script(
            'why-choose-script',
            get_stylesheet_directory_uri() . '/assets/js/why-choose.js',
            array('jquery'),
            filemtime(get_stylesheet_directory() . '/assets/js/why-choose.js'), // Cache-busting
            true // load in footer
        );
    }
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

class Menu_Icon_Walker extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        if ($depth === 0) {
            $output .= '<li class="menu-item has-mega">';
            $output .= '<a href="' . esc_url($item->url) . '">' . esc_html($item->title) . '</a>';
            $args->mega_parent = $item;
            return;
        }

        $icon = '';
        $icon_url = get_post_meta($item->ID, '_menu_item_icon', true);

        if (! empty($icon_url)) {
            $icon = '<img src="' . esc_url($icon_url) . '" class="submenu-icon" alt="">';
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