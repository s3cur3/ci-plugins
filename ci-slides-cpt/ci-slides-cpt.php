<?php
/*
Plugin Name: Slides Custom Post Type
Plugin URI: http://conversioninsights.net
Description: Adds a "Slides" post type to be used in the theme.
Version: 2.04
Author: Tyler Young
Author URI: http://conversioninsights.net
*/

require_once 'plugin-updates/plugin-update-checker.php';
require_once 'lib/constants.php';
require_once 'lib/utils.php';
require_once 'lib/createPostType.php';
require_once 'lib/displayPostType.php';


$UpdateChecker =
    PucFactory::buildUpdateChecker(
              'http://ci-modern-accounting-firm.mystagingwebsite.com/downloads/plugins/slides-cpt_version_metadata.json',
              __FILE__,
              'ci-slides-cpt',
              720 // check once a month
    );









add_filter( 'rwmb_meta_boxes', 'ciSlidesRegisterMetaBoxes' );
function ciSlidesRegisterMetaBoxes( $meta_boxes ) {
    /**
     * Prefix of meta keys (optional)
     * Use underscore (_) at the beginning to make keys hidden
     * Alt.: You also can make prefix empty to disable it
     */
    // Better has an underscore as last sign
    $prefix = (defined('CI_THEME_PREFIX') ? CI_THEME_PREFIX : 'ci') . '_';

    // Meta box for the slides custom post type
    $meta_boxes[] = array(
        // Meta box id, UNIQUE per meta box. Optional since 4.1.5
        'id' => 'slides-only',

        // Meta box title - Will appear at the drag and drop handle bar. Required.
        'title' => __( 'Slide options', 'ci-modern-accounting-firm' ),

        // Post types, accept custom post types as well - DEFAULT is array('post'). Optional.
        'pages' => array( CI_SLIDE_TYPE ),

        // Where the meta box appear: normal (default), advanced, side. Optional.
        'context' => 'normal',

        // Order of meta box: high (default), low. Optional.
        'priority' => 'high',

        // Auto save: true, false (default). Optional.
        'autosave' => true,

        // List of meta fields
        'fields' => array(
            // Caption position
            array(
                'name' => __( 'Position of caption:', 'ci-modern-accounting-firm' ),
                'id' => "{$prefix}caption_position",
                'type' => 'select',
                // Array of 'value' => 'Label' pairs for select box
                'options' => array(
                    'center' => __( 'Center', 'ci-modern-accounting-firm' ),
                    'left' => __( 'Left', 'ci-modern-accounting-firm' ),
                    'right' => __( 'Right', 'ci-modern-accounting-firm' ),
                    'none' => __( 'Not displayed', 'ci-modern-accounting-firm' ),
                ),
                // Select multiple values, optional. Default is false.
                'multiple' => false,
                'std' => 'center',
                'desc' => __('<strong>Note:</strong> On very small screens, all captions will be centered, with a transparent background.', 'ci-modern-accounting-firm' )
            ),
            // Caption background color
            array(
                'name' => __( 'Caption background color', 'ci-modern-accounting-firm' ),
                'id' => "{$prefix}caption_bg",
                'type' => 'color',
                'desc' => __('<strong>Only</strong> applies to left- or right-positioned captions. Defaults to the secondary background color.', 'ci-modern-accounting-firm' )
            ),
            array(
                'name' => __( 'Darken slide image?', 'ci-modern-accounting-firm' ),
                'id'   => "{$prefix}darken_slide",
                'desc' => __( 'If checked, image will be darkened 30% (useful for making white text readable over bright images)', 'ci-modern-accounting-firm' ),
                'type' => 'checkbox',
                // Value can be 0 or 1
                'std' => 1,
            ),
            array(
                // Field name - Will be used as label
                'name'  => __( 'Link slide to this URL:', 'ci-modern-accounting-firm' ),
                // Field ID, i.e. the meta key
                'id'    => "{$prefix}link",
                // Field description (optional)
                'desc'  => __( 'Leave blank for no link.', 'ci-modern-accounting-firm' ),
                'type'  => 'text',
                // Default value (optional)
                'std'   => '',
                // CLONES: Add to make the field cloneable (i.e. have multiple value)
                'clone' => false,
            ),

        ),
    );


    return $meta_boxes;
}