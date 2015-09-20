<?php

if( !defined('CI_THEME_PREFIX') ) define('CI_THEME_PREFIX', 'ci');
if( !defined('CI_SLUG') ) define('CI_SLUG', 'conversion-insights');
if( !defined('CI_TEXT_DOMAIN') ) define('CI_TEXT_DOMAIN', CI_SLUG);

if( !defined('CI_SLIDE_TYPE') ) define("CI_SLIDE_TYPE", "ci-slide");
if( !defined('CI_SIZE_LG') ) define("CI_SIZE_LG", CI_SLIDE_TYPE . '-fullscreen');
if( !defined('CI_SIZE_MD') ) define("CI_SIZE_MD", CI_SLIDE_TYPE . '-contentwidth');
if( !defined('CI_SIZE_INPAGE') ) define('CI_SIZE_INPAGE', CI_SLIDE_TYPE . '-inpage');

if( !defined('CI_STAFF_TYPE') ) define("CI_STAFF_TYPE", "ci-staff");
if( !defined('CI_STAFF_IMG') ) define("CI_STAFF_IMG", CI_STAFF_TYPE . '-photo');
if( !defined('CI_STAFF_IMG_SM') ) define("CI_STAFF_IMG_SM", CI_STAFF_TYPE . '-thumbnail');

if( !defined('CI_FULL_WIDTH_WITH_SIDEBAR_IMG') ) define("CI_FULL_WIDTH_WITH_SIDEBAR_IMG", "ci-full-width");
if( !defined('CI_THUMBNAIL_IMG') ) define("CI_THUMBNAIL_IMG", "ci-thumb");