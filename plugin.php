<?php
/*
Plugin Name: WP MMOWGLI
Plugin URI:
Description: 
Version: 1.0
*/

// The full url to the plugin directory (ends with trailing slash)
if (!defined('MMOWGLI_PLUGIN_URL')) {
    define('MMOWGLI_PLUGIN_URL', plugin_dir_url(__FILE__));
}

// The full path to the plugin directory (ends with trailing slash)
if (!defined('MMOWGLI_PLUGIN_DIR')) {
    define('MMOWGLI_PLUGIN_DIR', plugin_dir_path(__FILE__));
}

// The full path to this file (used for rewrites)
if (!defined('MMOWGLI_PLUGIN_MAIN_FILE')) {
    define('MMOWGLI_PLUGIN_MAIN_FILE', __FILE__);
}

// If the autoload file exists
if (file_exists(MMOWGLI_PLUGIN_DIR . 'vendor/autoload.php')) {
    require_once(MMOWGLI_PLUGIN_DIR . 'vendor/autoload.php');
}

    \M1\Plugins\Initialize::instance();
