<?php

require_once('Classes/WooStartSettings.php');
require_once('Classes/WoocoomerceSettings.php');

use Classes\WooStartSettings;
use Classes\WoocoomerceSettings;

class WooThemeClass
{
    public $startSettings;
    function __construct()
    {
        /**
         * Start settings
         */
        add_action( 'after_setup_theme', array( new WooStartSettings(), '__construct' ) );

        /**d
         * Woocoomerce settings
         */
        add_filter( 'after_setup_theme', array( new WoocoomerceSettings(), '__construct') );
    }
}

new WooThemeClass();