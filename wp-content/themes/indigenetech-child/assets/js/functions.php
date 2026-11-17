<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );


if ( ! function_exists( 'twentytwentyone_child_enqueue_assets' ) ) {
    function twentytwentyone_child_enqueue_assets() {

        // --- Parent Theme Style ---
        wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

        // --- Child Theme Styles ---
        wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'parent-style' ), filemtime( get_stylesheet_directory() . '/style.css' ) );
    wp_enqueue_style('child-fonts-css', get_stylesheet_directory_uri() . '/assets/fonts/stylesheet.css', array(), filemtime( get_stylesheet_directory() . '/assets/fonts/stylesheet.css' )
);
        // --- Blog CSS ---
        wp_enqueue_style( 'child-blog-css', get_stylesheet_directory_uri() . '/assets/css/blog.css', array( 'child-style' ), filemtime( get_stylesheet_directory() . '/assets/css/blog.css' ) );

        // --- Common CSS ---
        wp_enqueue_style( 'child-common-css', get_stylesheet_directory_uri() . '/assets/css/common.css', array( 'child-style' ), filemtime( get_stylesheet_directory() . '/assets/css/common.css' ) );

        // --- Typography CSS ---
        wp_enqueue_style( 'child-typography-css', get_stylesheet_directory_uri() . '/assets/css/typography.css', array( 'child-style' ), filemtime( get_stylesheet_directory() . '/assets/css/typography.css' ) );

        // --- Custom CSS ---
        wp_enqueue_style( 'child-custom-css', get_stylesheet_directory_uri() . '/assets/css/custom.css', array( 'child-style' ), filemtime( get_stylesheet_directory() . '/assets/css/custom.css' ) );

        wp_enqueue_style( 'slick-css', get_stylesheet_directory_uri() . '/assets/css/slick.css', array( 'child-style' ), filemtime( get_stylesheet_directory() . '/assets/css/slick.css' ) );
        
        wp_enqueue_style( 'child-all-font-awesome-css', get_stylesheet_directory_uri() . '/assets/css/all.min.css', array( 'child-style' ), filemtime( get_stylesheet_directory() . '/assets/css/all.min.css' ) );
        
        wp_enqueue_style('fontawesome-theme-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css');
       
        // Slick JS (depends on jQuery) - enqueue first
        wp_enqueue_script( 'slick-js', get_stylesheet_directory_uri() . '/assets/js/slick.min.js', array( 'jquery' ), filemtime( get_stylesheet_directory() . '/assets/js/slick.min.js' ), true );
        wp_enqueue_script(
    'slider-js',
    get_stylesheet_directory_uri() . '/assets/js/slider.js',
    array('jquery', 'slick-js'),
    filemtime(get_stylesheet_directory() . '/assets/js/slider.js'),
    true
);
        
        // --- Custom JS ---
        wp_enqueue_script( 'child-custom-js', get_stylesheet_directory_uri() . '/assets/js/custom.js', array( 'jquery' ), filemtime( get_stylesheet_directory() . '/assets/js/custom.js' ), true );
        
    }
}
add_action( 'wp_enqueue_scripts', 'twentytwentyone_child_enqueue_assets', 20 );

add_action( 'wp_enqueue_scripts', function() {
        wp_enqueue_style(
            'mega-menu',
            get_stylesheet_directory_uri() . '/assets/css/mega-menu.css',
            array(),
            wp_get_theme()->get( 'Version' )
        );
});

function my_theme_enqueue_scripts() {
    // Enqueue slick carousel CSS and JS
    wp_enqueue_style( 'slick-css', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css' );
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'slick-js', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), '', true );

    wp_enqueue_script( 'mega-menu-jquery', get_stylesheet_directory_uri() . '/assets/js/mega-menu.js', array('jquery'), wp_get_theme()->get( 'Version' ), true );

    // Enqueue home-specific JS
    wp_enqueue_script( 'home-scripts', get_stylesheet_directory_uri() . '/assets/js/home-scripts.js', array('jquery', 'slick-js'), '', true );

    // Enqueue online food ordering CSS
   // wp_enqueue_style( 'online-food-ordering-css', get_stylesheet_directory_uri() . '/assets/css/online-food-ordering.css' );
    wp_enqueue_script( 'tailwind-js', get_stylesheet_directory_uri() . '/assets/js/tailwindcss.js', array(), null, true );
    wp_enqueue_script( 'lucide-js', get_stylesheet_directory_uri() . '/assets/js/lucide.min.js', array(), null, true );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_scripts' );

function enqueue_lucide_icons() {
    wp_enqueue_script( 'lucide-icons', 'https://unpkg.com/lucide@latest', array(), null, true );

    // Inline script to initialize Lucide after load
    wp_add_inline_script('lucide-icons', 'document.addEventListener("DOMContentLoaded", function(){ lucide.createIcons(); });');
}
add_action('wp_enqueue_scripts', 'enqueue_lucide_icons');



require_once get_stylesheet_directory() . '/custom-parts/custom-shortcode.php';
require_once get_stylesheet_directory() . '/custom-parts/faq-schema.php';
require_once get_stylesheet_directory() . '/custom-parts/custom-popup.php';
require_once get_stylesheet_directory() . '/custom-parts/latest-insights.php';
require_once get_stylesheet_directory() . '/custom-parts/pricing-table.php';



// ======================================================
// ✅ DISABLE GUTENBERG (Block Editor)
// ======================================================
add_filter('use_block_editor_for_post', '__return_false', 10);
add_filter('use_block_editor_for_post_type', '__return_false', 10);


//blog reading time
function get_reading_time( $post_id = null, $wpm = 200 ) {
    if ( ! $post_id ) {
        global $post;
        if ( empty( $post ) ) {
            return '';
        }
        $post_id = $post->ID;
    }
    $content = get_post_field( 'post_content', $post_id );
    $content = strip_shortcodes( $content );
    $content = wp_strip_all_tags( $content );

    if ( empty( $content ) ) {
        return '';
    }
    $word_count = str_word_count( $content );
    $minutes = max( 1, (int) ceil( $word_count / (float) $wpm ) );

    return sprintf( '%d min read', $minutes );
}


function custom_footer_widgets_init() {
    register_sidebar([
        'name' => 'Footer Text',
        'id' => 'footer-text',
        'before_widget' => '',
        'after_widget'  => '',
    ]);

    register_sidebar([
        'name' => 'Company',
        'id' => 'company',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '<h3 class="impact_font fw400 fs24 lh120 text-white mb-4">',
        'after_title'   => '</h3>',
    ]);

    register_sidebar([
        'name' => 'Legal',
        'id' => 'legal',
        'before_widget' => '',
        'after_widget'  => '',
        'before_title'  => '<h3 class="impact_font fw400 fs24 lh120 text-white mb-4">',
        'after_title'   => '</h3>',
    ]);
}
add_action('widgets_init', 'custom_footer_widgets_init');

// mega menu
// ==============================================
// ✅ Add crossorigin + defer for external JS
// ==============================================
function optimize_script_loader_tag( $tag, $handle ) {
    $crossorigin_scripts = array( 'slick-js', 'jquery-cdn' );
    $defer_scripts = array( 'custom-jquery', 'mega-menu-jquery', 'template-script' );

    // Add crossorigin
    if ( in_array( $handle, $crossorigin_scripts ) ) {
        $tag = str_replace( '<script ', '<script crossorigin="anonymous" ', $tag );
    }

    // Add defer for non-critical local scripts
    if ( in_array( $handle, $defer_scripts ) ) {
        $tag = str_replace( '<script ', '<script defer ', $tag );
    }

    return $tag;
}
add_filter( 'script_loader_tag', 'optimize_script_loader_tag', 10, 2 );


if ( ! function_exists( 'itm_sanitize_menu_item_attributes_string' ) ) {
    function itm_sanitize_menu_item_attributes_string( $attr_string ) {
        if ( empty( $attr_string ) || ! is_string( $attr_string ) ) {
            return '';
        }

        // Allowed attribute names
        $allowed_attributes = array( 'class', 'id', 'data-popup', 'target', 'rel' );

        $out = '';

        // Match attributes like key="value" or key='value' (DOTALL for safety)
        if ( preg_match_all( '/([a-zA-Z0-9\-_:]+)\s*=\s*(["\'])(.*?)\\2/s', $attr_string, $matches, PREG_SET_ORDER ) ) {
            foreach ( $matches as $m ) {
                $key   = $m[1];
                $value = $m[3];

                if ( in_array( $key, $allowed_attributes, true ) ) {
                    // Defensive: disallow JavaScript URIs or data URIs that might be unsafe
                    if ( stripos( $value, 'javascript:' ) !== false ) {
                        continue;
                    }

                    // Minimal length check
                    $value = trim( $value );
                    if ( $value === '' ) {
                        continue;
                    }

                    $out .= ' ' . esc_attr( $key ) . '="' . esc_attr( $value ) . '"';
                }
            }
        }

        return $out;
    }
}

/**
 * 2) Add custom fields to menu item edit UI
 *    Hook: wp_nav_menu_item_custom_fields (WP 5.4+)
 */
add_action( 'wp_nav_menu_item_custom_fields', function( $item_id, $item, $depth, $args ) {
    // Icon URL
    $icon_url = get_post_meta( $item->ID, '_menu_item_icon_url', true );
    ?>
    <p class="field-icon-url description description-wide">
        <label for="edit-menu-item-icon-url-<?php echo esc_attr( $item->ID ); ?>">
            <?php esc_html_e( 'Icon image URL' ); ?><br />
            <input type="text"
                   id="edit-menu-item-icon-url-<?php echo esc_attr( $item->ID ); ?>"
                   class="widefat code edit-menu-item-icon-url"
                   name="menu-item-icon-url[<?php echo esc_attr( $item->ID ); ?>]"
                   value="<?php echo esc_attr( $icon_url ); ?>" />
            <span class="howto"><?php esc_html_e( 'Enter full image URL (or relative path). Example: https://example.com/wp-content/uploads/icon.webp' ); ?></span>
        </label>
    </p>
    <?php

    // Button Text
    $button_text = get_post_meta( $item->ID, '_menu_item_button_text', true );
    ?>
    <p class="description description-wide">
        <label for="edit-menu-item-button-text-<?php echo esc_attr( $item->ID ); ?>">
            <?php esc_html_e( 'Button Text' ); ?><br />
            <input type="text"
                   id="edit-menu-item-button-text-<?php echo esc_attr( $item->ID ); ?>"
                   class="widefat code edit-menu-item-button-text"
                   name="menu-item-button-text[<?php echo esc_attr( $item->ID ); ?>]"
                   value="<?php echo esc_attr( $button_text ); ?>" />
        </label>
    </p>
    <?php

    // Button URL
    $button_url = get_post_meta( $item->ID, '_menu_item_button_url', true );
    ?>
    <p class="description description-wide">
        <label for="edit-menu-item-button-url-<?php echo esc_attr( $item->ID ); ?>">
            <?php esc_html_e( 'Button URL' ); ?><br />
            <input type="text"
                   id="edit-menu-item-button-url-<?php echo esc_attr( $item->ID ); ?>"
                   class="widefat code edit-menu-item-button-url"
                   name="menu-item-button-url[<?php echo esc_attr( $item->ID ); ?>]"
                   value="<?php echo esc_attr( $button_url ); ?>" />
        </label>
    </p>
    <?php

    // Button Attributes (free text - sanitized by helper when output)
    $button_attr = get_post_meta( $item->ID, '_menu_item_button_attr', true );
    ?>
    <p class="description description-wide">
        <label for="edit-menu-item-button-attr-<?php echo esc_attr( $item->ID ); ?>">
            <?php esc_html_e( 'Button Attributes (optional)' ); ?><br />
            <input type="text"
                   id="edit-menu-item-button-attr-<?php echo esc_attr( $item->ID ); ?>"
                   class="widefat code edit-menu-item-button-attr"
                   name="menu-item-button-attr[<?php echo esc_attr( $item->ID ); ?>]"
                   value="<?php echo esc_attr( $button_attr ); ?>"
                   placeholder='<?php echo esc_attr( 'e.g., class="open-popup" data-popup="zerocommission"' ); ?>' />
            <span class="howto"><?php esc_html_e( 'Allowed attributes: class, id, data-popup, target, rel' ); ?></span>
        </label>
    </p>
    <?php

}, 10, 4 );

/**
 * 3) Save custom menu item fields on menu update
 *    Hook: wp_update_nav_menu_item
 */
add_action( 'wp_update_nav_menu_item', function( $menu_id, $menu_item_db_id, $args ) {
    // Icon URL
    if ( isset( $_POST['menu-item-icon-url'][ $menu_item_db_id ] ) ) {
        $raw = wp_unslash( $_POST['menu-item-icon-url'][ $menu_item_db_id ] );
        $sanitized = trim( $raw ) !== '' ? esc_url_raw( $raw ) : '';
        if ( $sanitized ) {
            update_post_meta( $menu_item_db_id, '_menu_item_icon_url', $sanitized );
        } else {
            delete_post_meta( $menu_item_db_id, '_menu_item_icon_url' );
        }
    } else {
        // If field not present, do not force-delete; but for safety we remove meta
        delete_post_meta( $menu_item_db_id, '_menu_item_icon_url' );
    }

    // Button Text
    if ( isset( $_POST['menu-item-button-text'][ $menu_item_db_id ] ) ) {
        $val = wp_unslash( $_POST['menu-item-button-text'][ $menu_item_db_id ] );
        update_post_meta( $menu_item_db_id, '_menu_item_button_text', sanitize_text_field( $val ) );
    } else {
        delete_post_meta( $menu_item_db_id, '_menu_item_button_text' );
    }

    // Button URL
    if ( isset( $_POST['menu-item-button-url'][ $menu_item_db_id ] ) ) {
        $val = wp_unslash( $_POST['menu-item-button-url'][ $menu_item_db_id ] );
        $sanitized_url = trim( $val ) !== '' ? esc_url_raw( $val ) : '';
        if ( $sanitized_url ) {
            update_post_meta( $menu_item_db_id, '_menu_item_button_url', $sanitized_url );
        } else {
            delete_post_meta( $menu_item_db_id, '_menu_item_button_url' );
        }
    } else {
        delete_post_meta( $menu_item_db_id, '_menu_item_button_url' );
    }

    // Button Attributes - store raw but will be sanitized on output
    if ( isset( $_POST['menu-item-button-attr'][ $menu_item_db_id ] ) ) {
        $raw = wp_unslash( $_POST['menu-item-button-attr'][ $menu_item_db_id ] );
        // Store raw so admin can edit; we rely on the walker to sanitize on render.
        update_post_meta( $menu_item_db_id, '_menu_item_button_attr', $raw );
    } else {
        delete_post_meta( $menu_item_db_id, '_menu_item_button_attr' );
    }

}, 10, 3 );

/**
 * 4) Custom Mega Menu Walker
 *    Reads _menu_item_icon_url and button metas.
 */
if ( ! class_exists( 'Custom_Mega_Menu_Walker' ) ) :

class Custom_Mega_Menu_Walker extends Walker_Nav_Menu {

    private $current_parent_id = 0;
    private $current_parent_item = null;

    // ================= SUBMENU WRAPPER =================
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);

        if ( $depth === 0 && $this->current_parent_item ) {

            $menu_item_obj = get_post( $this->current_parent_id );

            // Title Attribute → post_excerpt
            $title_attr = $menu_item_obj && ! empty( $menu_item_obj->post_excerpt )
                ? trim( $menu_item_obj->post_excerpt )
                : '';

            // Description → post_content
            $menu_desc = $menu_item_obj && ! empty( $menu_item_obj->post_content )
                ? trim( $menu_item_obj->post_content )
                : '';

            // Heading fallback
            $heading = $title_attr ? $title_attr : get_the_title( $this->current_parent_id );

            // Description fallback
            $description = $menu_desc ? $menu_desc : 'Default description here...';

            // Get button custom fields from parent item
            $button_text = get_post_meta( $this->current_parent_item->ID, '_menu_item_button_text', true );
            $button_url  = get_post_meta( $this->current_parent_item->ID, '_menu_item_button_url', true );
            $button_attr_raw = get_post_meta( $this->current_parent_item->ID, '_menu_item_button_attr', true );

            // Sanitize button attributes for output
            $button_attr_safe = itm_sanitize_menu_item_attributes_string( $button_attr_raw );

            // Mega menu wrapper
            $output .= "\n$indent<div class=\"mega-menu\">\n";
            $output .= "$indent\t<div class=\"mega-left\">\n";
            $output .= "$indent\t\t<div class=\"mega-contentwrap\">\n";
            $output .= "$indent\t\t\t<h2>" . esc_html( $heading ) . "</h2>\n";
            $output .= "$indent\t\t\t<p>" . wp_kses_post( $description ) . "</p>\n";

            // Output button if text exists (URL optional)
            if ( $button_text ) {
                $href = $button_url ? ' href="' . esc_url( $button_url ) . '"' : '';
                $output .= '<a' . $href . $button_attr_safe . '>' . esc_html( $button_text ) . '</a>';
            }

            $output .= "$indent\t\t</div>\n";
            $output .= "$indent\t\t<div class=\"mega-cardimg\"></div>\n";
            $output .= "$indent\t</div>\n";
            $output .= "$indent\t<div class=\"mega-right\">\n";
            $output .= "$indent\t\t<ul class=\"sub-menu\">\n";

        } else {
            $output .= "\n$indent<ul class=\"sub-menu\">\n";
        }
    }

    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        if ( $depth === 0 ) {
            $output .= "$indent\t\t</ul>\n";
            $output .= "$indent\t</div>\n";
            $output .= "$indent</div>\n";
        } else {
            $output .= "$indent</ul>\n";
        }
    }

    // ================= MENU ITEM =================
    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
        $indent = $depth ? str_repeat("\t", $depth) : '';

        // Track parent menu ID and store the parent item
        if ( $depth === 0 && in_array( 'menu-item-has-children', (array) $item->classes, true ) ) {
            $this->current_parent_id   = $item->ID;
            $this->current_parent_item = $item;
        }

        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
        $class_names = $classes ? ' class="' . esc_attr( implode( ' ', array_filter( $classes ) ) ) . '"' : '';
        $li_id_attr  = ' id="menu-item-' . esc_attr( $item->ID ) . '"';

        // Basic attributes
        $atts = array(
            'title'  => ! empty( $item->attr_title ) ? $item->attr_title : '',
            'target' => ! empty( $item->target )     ? $item->target     : '',
            'rel'    => ! empty( $item->xfn )        ? $item->xfn        : '',
            'href'   => ! empty( $item->url )        ? $item->url        : '',
        );

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( $value !== '' ) {
                $value = ( $attr === 'href' ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        // Built-in menu description
        $desc_text = ! empty( $item->description ) ? $item->description : '';
        $desc_html = $desc_text ? '<span class="menu-desc">' . esc_html( $desc_text ) . '</span>' : '';

        // ------------------- ICON USING NEW MENU META -------------------
        $icon_html = '';
        $icon_url = get_post_meta( $item->ID, '_menu_item_icon_url', true );
        if ( ! empty( $icon_url ) ) {
            // allow relative or full; sanitize with esc_url
            $icon_html = '<img src="' . esc_url( $icon_url ) . '" alt="' . esc_attr( $item->title ) . '" class="menu-icon" />';
        }

        // Output menu item
        $item_output  = isset( $args->before ) ? $args->before : '';
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $icon_html; // <-- output icon before title
        $item_output .= ( isset( $args->link_before ) ? $args->link_before : '' );
        $item_output .= apply_filters( 'the_title', $item->title, $item->ID );
        $item_output .= ( isset( $args->link_after ) ? $args->link_after : '' );
        $item_output .= $desc_html;
        $item_output .= '</a>';
        $item_output .= isset( $args->after ) ? $args->after : '';

        $output .= $indent . '<li' . $li_id_attr . $class_names . '>' . $item_output;
    }

    public function end_el( &$output, $item, $depth = 0, $args = array() ) {
        $output .= "</li>\n";
    }
}

endif; // end walker class

// in functions.php
function mytheme_register_menus() {
    register_nav_menus( array(
        'megamenu' => __( 'Mega Menu', 'twentytwentyone' ),
    ) );
}
add_action( 'after_setup_theme', 'mytheme_register_menus' );


//Allow SVG file uploads
function add_svg_upload_for_admins( $mimes ) {
    if ( current_user_can( 'manage_options' ) ) {
        $mimes['svg'] = 'image/svg+xml';
    }
    return $mimes;
}
add_filter( 'upload_mimes', 'add_svg_upload_for_admins' );

/**
 * Shortcode: [ordering_system_section red]
 */
function ishopo_ordering_system_section_shortcode( $atts ) {

    $atts = shortcode_atts( array(
        'btn_text' => 'Get Started',
        'btn_data_popup' => 'zerocommission',
    ), $atts, 'ordering_system_section' );

    $items = array(
        array(
            'title' => 'Coffee Shop',
            'img'   => 'https://ishopo.ca/wp-content/uploads/2025/12/Coffee-shop-ordering-system.webp',
            'link'  => '',
        ),
        array(
            'title' => 'Bakery',
            'img'   => 'https://ishopo.ca/wp-content/uploads/2025/12/Bakery-online-ordering.webp',
            'link'  => 'https://bakerydemo.ishopo.ca/',
        ),
        array(
            'title' => 'Retail Store',
            'img'   => 'https://ishopo.ca/wp-content/uploads/2025/12/Retail-store-ordering.webp',
            'link'  => 'https://storedemo.ishopo.ca/',
        ),
        array(
            'title' => 'Restaurant',
            'img'   => 'https://ishopo.ca/wp-content/uploads/2025/12/Restaurant-online-ordering.webp',
            'link'  => 'https://indianrestaurantdemo.ishopo.ca/',
        ),
    );

    ob_start(); ?>

        <div class="container">

            <div class="title-area">
                <h2 class="main-heading fs-40 text-white">
Get a Free Demo and See Your No-Commission Ordering System in Action

                </h2>
                <p class="sub-text text-white">
Explore guided demos that show exactly how customers move from menu to checkout with ease.

                </p>
            </div>

            <div class="cards-grid">
                <?php foreach ( $items as $item ) : 
                    $img  = esc_url( $item['img'] );
                    $tit  = esc_html( $item['title'] );
                    $link = trim( $item['link'] );
                ?>

                    <div class="card-item">

                        <?php if ( ! empty( $link ) ) : ?>
                        <a href="<?php echo esc_url( $link ); ?>" target="_blank" class="full-card-link">
                        <?php endif; ?>

                            <div class="card-image-wrapper">
                                <img src="<?php echo $img; ?>" alt="<?php echo $tit; ?>" class="card-image">
                            </div>

                            <h3 class="card-title"><?php echo $tit; ?></h3>

                        <?php if ( ! empty( $link ) ) : ?>
                        </a>
                        <?php endif; ?>

                    </div>

                <?php endforeach; ?>
            </div>
   <button data-popup="zerocommission" class="btn btn-white-red d-flex align-center radius-10 open-popup m-auto mt-20 mb-20 ">Get iShopo Now </button>
            </button>

        </div>
    </section>

    <?php
    return ob_get_clean();
}
add_shortcode( 'ordering_system_section', 'ishopo_ordering_system_section_shortcode' );
/**
 * Shortcode: [ordering_system_white]
 */
function ishopo_ordering_system_white_shortcode( $atts ) {

       $items = array(
        array(
            'title' => 'Coffee Shop',
            'img'   => 'https://ishopo.ca/wp-content/uploads/2025/12/Coffee-shop-ordering-system.webp',
            'link'  => '',
        ),
        array(
            'title' => 'Bakery',
            'img'   => 'https://ishopo.ca/wp-content/uploads/2025/12/Bakery-online-ordering.webp',
            'link'  => 'https://bakerydemo.ishopo.ca/',
        ),
        array(
            'title' => 'Retail Store',
            'img'   => 'https://ishopo.ca/wp-content/uploads/2025/12/Retail-store-ordering.webp',
            'link'  => 'https://storedemo.ishopo.ca/',
        ),
        array(
            'title' => 'Restaurant',
            'img'   => 'https://ishopo.ca/wp-content/uploads/2025/12/Restaurant-online-ordering.webp',
            'link'  => 'https://indianrestaurantdemo.ishopo.ca/',
        ),
    );

    ob_start(); ?>

<section class="ordering-system-section bg-white">
    <div class="container">

        <div class="title-area">
            <h2 class="main-heading fs-40">
Get a Free Demo and See Your No-Commission Ordering System in Action
            </h2>
            <p class="sub-text">
Explore guided demos that show exactly how customers move from menu to checkout with ease.

            </p>
        </div>

        <div class="cards-grid">

            <?php foreach ( $items as $item ) : 
                $img  = esc_url( $item['img'] );
                $tit  = esc_html( $item['title'] );
                $link = trim( $item['link'] );
            ?>

            <div class="card-item">

                <?php if ( ! empty( $link ) ) : ?>
                <a href="<?php echo esc_url( $link ); ?>" target="_blank" class="full-card-link">
                <?php endif; ?>

                    <div class="card-image-wrapper">
                        <img src="<?php echo $img; ?>" alt="<?php echo $tit; ?>" class="card-image">
                    </div>
                    
                    <h3 class="card-title text-black"><?php echo $tit; ?></h3>

                <?php if ( ! empty( $link ) ) : ?>
                </a>
                <?php endif; ?>

            </div>

            <?php endforeach; ?>

        </div>

        <button class="btn btn-red radius-10 open-popup mb-20 mt-20" data-popup="zerocommission">
          Get iShopo Now
        </button>

    </div>
</section>

<?php
    return ob_get_clean();
}
add_shortcode( 'ordering_system_white', 'ishopo_ordering_system_white_shortcode' );

