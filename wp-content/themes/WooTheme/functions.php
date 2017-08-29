<?php

require_once('inc/WooStartSettings.php');
require_once('inc/WoocoomerceSettings.php');

class WooThemeClass
{
    public $startSettings;
    function __construct()
    {
        /**
         * Start settings
         */
        add_action('after_setup_theme', array('WooStartSettings', 'start_setup'));
        add_action('wp_enqueue_scripts', array('WooStartSettings', 'load_woo_theme_scripts'));

        /**
         * Woocoomerce settings
         */
        add_filter('woocommerce_currency_symbol', array('WoocoomerceSettings', 'add_ua_currency_symbol'), 10, 2);
        add_filter( 'woocommerce_checkout_fields' , array('WoocoomerceSettings', 'custom_override_checkout_fields' ));
        add_action( 'after_setup_theme', array('WoocoomerceSettings', 'woocommerce_support') );
    }
}

new WooThemeClass();