<?php

namespace Classes;

class WoocoomerceSettings
{

    function __construct()
    {
        add_filter('woocommerce_currency_symbol', array($this, 'add_ua_currency_symbol'), 10, 2);
        add_filter( 'woocommerce_checkout_fields' , array($this, 'custom_override_checkout_fields' ));
        add_action( 'after_setup_theme', array($this, 'woocommerce_support') );
    }

    /**
     * Change UAH sign to 'grn'
     * @param $currency_symbol
     * @param $currency
     * @return string
     */
    static function add_ua_currency_symbol( $currency_symbol, $currency ) {
        switch( $currency ) {
            case 'UAH': $currency_symbol = ' грн'; break;
        }
        return $currency_symbol;
    }

    /**
     * Hide some fields on check page
     * @param $fields
     * @return mixed
     */
    static function custom_override_checkout_fields( $fields ) {
        unset($fields['billing']['billing_last_name']);
        unset($fields['billing']['billing_company']);
        unset($fields['billing']['billing_postcode']);
        unset($fields['billing']['billing_state']);

        unset($fields['shipping']['shipping_last_name']);
        unset($fields['shipping']['shipping_company']);
        unset($fields['shipping']['shipping_postcode']);
        unset($fields['shipping']['shipping_state']);

        return $fields;
    }

    /**
     * Create Woocoomerce support
     */
    static function woocommerce_support() {
        add_theme_support( 'woocommerce' );
    }
}