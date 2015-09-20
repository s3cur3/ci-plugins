<?php

/**
 * Creates a Custom Post Type.
 * @param string $singular The singular form of the type's name.
 * @param string $plural The plural form of the type's name. Defaults to adding an 's' to the singular form.
 * @param string $registeredID The identifier to use when registering the post type. Defaults to the singular form.
 * @param string $dashicon The dashicon to use as an icon. (Leave off the "dashicon-" bit.)
 *                         Find all available icons here: http://melchoyce.github.io/dashicons/
 */
if( !function_exists('ciCreateCPT') ) {
    function ciCreateCPT($singular, $plural='', $registeredID='', $dashicon='admin-post') {
        if( !$plural ) {
            $plural = $singular . 's';
        }
        if( !$registeredID ) {
            $registeredID = $singular;
        }

        $args = array(
            'labels' => array(
                'name' => $plural,
                'singular_name' => $singular,
                'add_new' => "New $singular",
                'add_new_item' => "Add New $singular",
                'all_items' => "All $plural",
                'new_item' => "New $singular",
                'edit_item' => "Edit $singular",
                'view_item' => "View $singular"
            ),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'menu_icon' => 'dashicons-' . $dashicon,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => sanitize_title($singular)),
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => 50,
            'supports' => array(
                'title',
                'editor',
                'thumbnail',
                'excerpt'
            ),
            //'taxonomies' => array('category')
        );

        register_post_type( $registeredID, $args );
    }
}

if( !function_exists('ciCPTUpdatedMessages') ) {
    function ciCPTUpdatedMessages( $messages, $singular, $registeredID ) {
        global $post, $post_ID;

        $messages[$registeredID] = array(
            0 => '', // Unused. Messages start at index 1.
            1 => sprintf( __("$singular updated. <a href=\"%s\">View $singular</a>"), esc_url( get_permalink($post_ID) )),
            2 => __('Custom field updated.', CI_TEXT_DOMAIN),
            3 => __('Custom field deleted.', CI_TEXT_DOMAIN),
            4 => "$singular updated.",
            /* translators: %s: date and time of the revision */
            5 => isset($_GET['revision']) ? sprintf( "$singular restored to revision from %s", wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
            6 => sprintf( __("$singular published. <a href=\"%s\">View $singular</a>"), esc_url( get_permalink($post_ID) ) ),
            7 => "$singular saved.",
            8 => sprintf( __("$singular submitted. <a target=\"_blank\" href=\"%s\">Preview $singular</a>"), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
            9 => sprintf( __( $singular . ' scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview ' . $singular .'</a>'),
                // translators: Publish box date format, see http://php.net/date
                          date_i18n( 'M j, Y @ G:i', strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
            10 => sprintf( __("$singular draft updated. <a target=\"_blank\" href=\"%s\">Preview $singular</a>"), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
        );

        return $messages;
    }
}





/**
 * @param string $postType The custom post type, like 'employee' or 'portfolio-entry'
 * @param int $maxCharLength The maximum length of each post's content, in characters.
 *                           Use -1 for unlimited length.
 * @param array|string $featuredImageSize The size to use for the posts's featured image
 *                                        (where applicable). Same as the 2nd parameter to
 *                                        wp_get_attachment_image_src().
 * @param int $maxNumPosts The maximum number of posts to return.
 * @param string $orderBy The ordering for the posts. Default is by date.
 * @param string $order 'ASC' or 'DESC'
 * @return array An array with all the relevant post data. Keys include:
 *               - id (post ID)
 *               - content (the possibly length-limited content)
 *               - fullContent (the unlimited length, full content)
 *               - title (post title)
 *               - imgURL (URL for the featured image)
 *               - imgWidth (featured image width, in pixels)
 *               - imgHeight (featured image height, in pixels)
 *               - url (permalink for the post)
 */
if( !defined('ORDER_ASC') ) define('ORDER_ASC', 'ASC');
if( !defined('ORDER_DESC') ) define('ORDER_DESC', 'DESC');
if( !function_exists('ciGetPostsOfType') ) {
    function ciGetPostsOfType( $postType = 'post', $maxCharLength = -1, $featuredImageSize = array(1000,1000), $maxNumPosts = 100, $orderBy = 'date', $order = ORDER_ASC ) {
        $query = new WP_Query("showposts={$maxNumPosts}&post_type={$postType}&orderby={$orderBy}&order={$order}");

        $postsArray = array();
        while( $query->have_posts() ) {
            $query->next_post();

            $attachment = wp_get_attachment_image_src( get_post_thumbnail_id($query->post->ID), $featuredImageSize );

            $maybeExcerpt = has_excerpt($query->post->ID) ? get_post_field('post_excerpt', $query->post->ID) : $query->post->post_content;

            $postsArray[] = array(
                'id' => $query->post->ID,
                'content' => apply_filters( 'the_content', ciFilterToMaxCharLength($maybeExcerpt, $maxCharLength) ),
                'fullContent' => apply_filters( 'the_content', $query->post->post_content ),
                'title' => $query->post->post_title,
                'imgURL' => ($attachment ? $attachment[0] : ''),
                'imgWidth' => ($attachment ? $attachment[1] : -1),
                'imgHeight' => ($attachment ? $attachment[2] : -1),
                'url' => get_permalink($query->post->ID)
            );
        }

        wp_reset_postdata();
        return $postsArray;
    }
}


