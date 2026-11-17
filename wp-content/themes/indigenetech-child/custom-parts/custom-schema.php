<?php

/**
 * Add Schema Code Meta Box in Page Editor (Backend only)
 */
function itechnolabs_add_schema_metabox() {
    add_meta_box(
        'itechnolabs_schema_code',
        'Schema Code (JSON-LD) Multiple Schema',
        'itechnolabs_schema_metabox_callback',
        'page',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'itechnolabs_add_schema_metabox');

/**
 * Backend Meta Box HTML
 */
function itechnolabs_schema_metabox_callback($post) {
    $value = get_post_meta($post->ID, '_itechnolabs_schema_code', true);

    wp_nonce_field('itechnolabs_schema_save', 'itechnolabs_schema_nonce');

    echo '<p><strong>Add your JSON-LD schema here:</strong></p>';
    echo '<textarea style="width:100%;height:280px;" name="itechnolabs_schema_code">'
         . esc_textarea($value) .
         '</textarea>';
    echo '<p><em>You can paste multiple &lt;script type="application/ld+json"&gt; blocks.</em></p>';
}

/**
 * Save Schema Code EXACTLY as written (no filtering)
 */
function itechnolabs_save_schema_code($post_id) {

    if (!isset($_POST['itechnolabs_schema_nonce']) ||
        !wp_verify_nonce($_POST['itechnolabs_schema_nonce'], 'itechnolabs_schema_save')) {
        return;
    }

    if (!empty($_POST['itechnolabs_schema_code'])) {
        update_post_meta(
            $post_id,
            '_itechnolabs_schema_code',
            wp_unslash($_POST['itechnolabs_schema_code']) // SAVE RAW
        );
    } else {
        delete_post_meta($post_id, '_itechnolabs_schema_code');
    }
}
add_action('save_post', 'itechnolabs_save_schema_code');

/**
 * Output Schema in <head> EXACTLY as stored
 */
function itechnolabs_output_schema_code() {
    if (is_page()) {
        global $post;
        $schema_code = get_post_meta($post->ID, '_itechnolabs_schema_code', true);

        if (!empty($schema_code)) {
            echo "\n<!-- JSON-LD Schema for SEO -->\n";
            echo $schema_code . "\n"; // OUTPUT RAW (allows multiple scripts)
            echo "<!-- END JSON-LD Schema -->\n";
        }
    }
}
add_action('wp_head', 'itechnolabs_output_schema_code', 99);


?>