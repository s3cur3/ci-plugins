<?php



// Create the slides custom post type
add_action('init', 'ciCreateSlidePostType');
function ciCreateSlidePostType() {
    $args = array(
        'labels' => array(
            'name' => 'Slides',
            'singular_name' => 'Slide',
            'all_items' => 'All Slides',
            'add_new' => 'New Slide',
            'add_new_item' => 'Add New Slide',
            'new_item' => 'New Slide',
            'edit_item' => 'Edit Slide',
            'view_item' => 'View Slide'
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'menu_icon' => 'dashicons-format-gallery', // A Dashicon: http://melchoyce.github.io/dashicons/
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'slide'),
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => 6,
        'supports' => array(
            'title',
            'editor',
            'thumbnail',
        ),
        'taxonomies' => array('category')
    );

    register_post_type( CI_SLIDE_TYPE, $args );



    if ( function_exists( 'add_image_size' ) ) {
        add_image_size( CI_SIZE_MD, 1170, 99999 );
        add_image_size( CI_SIZE_LG, 1920, 99999 );
    }
    flush_rewrite_rules();
}

/**
 * Adds a note about the sizes of images we need
 */
function ciAddSliderImgSizeNote() {
    add_meta_box(
        'ci_image_size_note',
        '<strong>Note</strong>: Featured Image Sizes',
        'ciPrintSliderImgSizeNote',
        CI_SLIDE_TYPE,
        'side',
        'low'
    );
}
add_action( 'add_meta_boxes', 'ciAddSliderImgSizeNote' );

function ciPrintSliderImgSizeNote() {
    echo "<p>Recommended sizes for slider images:</p>";
    echo "<ul>";
    echo "    <li>For slides used at the top of the page: 1920&times;657</li>";
    echo "    <li>For slides inserted within the page: 1170&times;400</li>";
    echo "</ul>";
}



function ciSliderTypeUpdatedMessages( $messages ) {
    global $post, $post_ID;

    $messages[CI_SLIDE_TYPE] = array(
        0 => '', // Unused. Messages start at index 1.
        1 => sprintf( __('Slide updated. <a href="%s">View slide</a>'), esc_url( get_permalink($post_ID) ) ),
        2 => __('Custom field updated.', CI_TEXT_DOMAIN),
        3 => __('Custom field deleted.', CI_TEXT_DOMAIN),
        4 => __('Slide updated.', CI_TEXT_DOMAIN),
        /* translators: %s: date and time of the revision */
        5 => isset($_GET['revision']) ? sprintf( __('Slide restored to revision from %s', CI_TEXT_DOMAIN), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
        6 => sprintf( __('Slide published. <a href="%s">View slide</a>'), esc_url( get_permalink($post_ID) ) ),
        7 => __('Slide saved.', CI_TEXT_DOMAIN),
        8 => sprintf( __('Slide submitted. <a target="_blank" href="%s">Preview slide</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
        9 => sprintf( __('Slide scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview slide</a>'),
            // translators: Publish box date format, see http://php.net/date
                      date_i18n( 'M j, Y @ G:i', strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
        10 => sprintf( __('Slide draft updated. <a target="_blank" href="%s">Preview slide</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    );

    return $messages;
}
add_filter('post_updated_messages', 'ciSliderTypeUpdatedMessages');


