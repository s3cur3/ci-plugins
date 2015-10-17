<?php
/*
Plugin Name: Practice Areas Custom Post Type
Plugin URI: http://conversioninsights.net
Description: Adds a "Practice Areas" post type to be used in the theme slider.
Version: 2.05
Author: Tyler Young
Author URI: http://conversioninsights.net
*/

require_once 'plugin-updates/plugin-update-checker.php';
require_once 'lib/constants.php';
require_once 'lib/utils.php';
require_once 'lib/cptFramework.php';
require_once 'lib/createPostType.php';
require_once 'lib/displayPostType.php';
require_once 'lib/createWidget.php';


$UpdateChecker =
    PucFactory::buildUpdateChecker(
              'http://ci-modern-accounting-firm.mystagingwebsite.com/downloads/plugins/practice-areas-cpt_version_metadata.json',
              __FILE__,
              'ci-practice-areas-cpt',
              720 // check once a month for updates -- 720 == 24*30
    );









