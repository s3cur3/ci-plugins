<?php



// Create the Staff custom post type
add_action('init', 'ciCreateStaffType');
if( !function_exists('ciCreateStaffType') ) {
    function ciCreateStaffType() {
        $args = array(
            'labels' => array(
                'name' => 'Staff',
                'all_items' => 'All Staff Members',
                'singular_name' => 'Staff Member',
                'add_new' => 'New Staff Member',
                'add_new_item' => 'Add New Staff Member',
                'new_item' => 'New Staff Member',
                'edit_item' => 'Edit Staff Member',
                'view_item' => 'View Staff Member'
            ),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'menu_icon' => 'dashicons-businessman', // A Dashicon: http://melchoyce.github.io/dashicons/
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'staff'),
            'capability_type' => 'post',
            'show_in_nav_menus' => true,
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 50,
            'supports' => array(
                'title',
                'editor',
                'thumbnail',
            ),
            //'taxonomies' => array('category')
        );

        register_post_type( CI_STAFF_TYPE, $args );

        if ( function_exists( 'add_image_size' ) ) {
            add_image_size( CI_STAFF_IMG, 400, 400 );
            add_image_size( CI_STAFF_IMG_SM, 300, 300 );
        }
        flush_rewrite_rules();
    }
}
/**
 * Adds a note about the sizes of images we need
 */
if( !function_exists('ciAddStaffImgSizeNote') ) {
    function ciAddStaffImgSizeNote() {
        add_meta_box(
            'ci_image_size_note',
            '<strong>Note</strong>: Featured Image Sizes',
            'ciPrintStaffImgSizeNote',
            CI_STAFF_TYPE,
            'side',
            'low'
        );
    }
}
add_action( 'add_meta_boxes', 'ciAddStaffImgSizeNote' );

if( !function_exists('ciPrintStaffImgSizeNote') ) {
    function ciPrintStaffImgSizeNote() {
        echo "<p>Recommended size for staff photos:<br />400&times;400</p>";
    }
}



add_filter('post_updated_messages', 'ciStaffTypeUpdatedMessages');
if( !function_exists('ciStaffTypeUpdatedMessages') ) {
    function ciStaffTypeUpdatedMessages( $messages ) {
        global $post, $post_ID;

        $messages[CI_STAFF_TYPE] = array(
            0 => '', // Unused. Messages start at index 1.
            1 => sprintf( __('Staff member updated. <a href="%s">View staff member</a>'), esc_url( get_permalink($post_ID) ) ),
            2 => __('Custom field updated.', CI_TEXT_DOMAIN),
            3 => __('Custom field deleted.', CI_TEXT_DOMAIN),
            4 => __('Staff member updated.', CI_TEXT_DOMAIN),
            /* translators: %s: date and time of the revision */
            5 => isset($_GET['revision']) ? sprintf( __('Staff member restored to revision from %s', CI_TEXT_DOMAIN), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6 => sprintf( __('Staff member published. <a href="%s">View staff member</a>'), esc_url( get_permalink($post_ID) ) ),
            7 => __('Staff member saved.', CI_TEXT_DOMAIN),
            8 => sprintf( __('Staff member submitted. <a target="_blank" href="%s">Preview staff member</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
            9 => sprintf( __('Staff member scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview staff member</a>'),
                // translators: Publish box date format, see http://php.net/date
                          date_i18n( 'M j, Y @ G:i', strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
            10 => sprintf( __('Staff member draft updated. <a target="_blank" href="%s">Preview staff member</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
        );

        return $messages;
    }
}


 