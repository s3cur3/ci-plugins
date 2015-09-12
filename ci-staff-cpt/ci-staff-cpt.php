<?php
/*
Plugin Name: Staff Custom Post Type
Plugin URI: http://conversioninsights.net
Description: Adds a "Staff" post type to be used in the theme.
Version: 1.02
Author: Tyler Young
Author URI: http://conversioninsights.net
*/

require_once 'plugin-updates/plugin-update-checker.php';
require_once 'lib/constants.php';
require_once 'lib/utils.php';
require_once 'lib/createStaffType.php';
require_once 'lib/displayStaffType.php';

$UpdateChecker =
    PucFactory::buildUpdateChecker(
              'http://cisandbox.mystagingwebsite.com/wp-content/plugins/staff-cpt_version_metadata.json',
              __FILE__,
              'ci-staff-cpt',
              720 // check once a month for updates -- 720 == 24*30
    );






