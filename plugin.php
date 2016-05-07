<?php
/*
Plugin Name: WP MMOWGLI
Plugin URI: https://github.com/echo1consulting/wp-mmowgli
Description: MMOWGLI stands for Massive Multiplayer Online War Game Leveraging the Internet. It is a message-based game used to encourage innovative thinking by many people, connected via the internet. This project is based on the original MMOWGLI project (https://portal.mmowgli.nps.edu/) initiated by the Office of Naval Research (ONR) for the United States Navy. This project aims to port MMOWGLI capabilities on the open-source WordPress framework.
Version: 1.0
Author: Echo1 Consulting
Author URI: https://github.com/echo1consulting
License: GPL2
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
