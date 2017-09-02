<?php

require_once('Classes/WooStartSettings.php');
require_once('Classes/WoocoomerceSettings.php');
require_once('Classes/WooAjaxSettings.php');

use Classes\WooStartSettings;
use Classes\WoocoomerceSettings;
use Classes\WooAjaxSettings;

class WooThemeClass
{
    public $startSettings;
    function __construct()
    {
        /**
         * Start settings
         */
        add_action( 'after_setup_theme', array( new WooStartSettings(), '__construct' ) );

        /**
         * Woocoomerce settings
         */
        add_filter( 'after_setup_theme', array( new WoocoomerceSettings(), '__construct') );

        add_filter( 'after_setup_theme', array( new WooAjaxSettings(), '__construct') );
    }
}

new WooThemeClass();