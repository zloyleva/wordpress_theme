<?php

require_once('inc/WooStartSettings.php');

class WooThemeClass
{
    public $startSettings;
    function __construct()
    {
        add_action('after_setup_theme', array('WooStartSettings', 'start_setup'));
        add_action('wp_enqueue_scripts', array('WooStartSettings', 'load_woo_theme_scripts'));
    }
}

new WooThemeClass();