<?php

class WooStartSettings{

    function __construct()
    {
        add_action('after_setup_theme', array($this, 'start_setup'));
        add_action('wp_enqueue_scripts', array($this, 'load_woo_theme_scripts'));
    }

    /**
     * Add thumbnails, menus, custom logo
     */
    static function start_setup() {
        add_theme_support( 'post-thumbnails' );
        register_nav_menus( array(
            'primary' => __( 'Primary Menu' ),
            'footer'  => __( 'Footer Links Menu' ),
            'top_phone' => __('Top phone menu'),
            'top_account' => __('Top account menu'),
        ) );
        add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
        add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat' ) );
        add_theme_support( 'custom-logo', array(
            'height'      => 200,
            'width'       => 200,
            'flex-height' => true,
        ) );
    }

    static function load_woo_theme_scripts() {
        // Load our main stylesheet.
        wp_enqueue_style( 'wootheme-css',  get_template_directory_uri() . '/styles/style.css' );
    }

}