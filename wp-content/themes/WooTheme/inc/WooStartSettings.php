<?php
//namespace WooTheme;
class WooStartSettings{

    function __construct()
    {
        add_action('after_setup_theme', array($this, 'start_setup'));
        add_action('wp_enqueue_scripts', array($this, 'load_woo_theme_scripts'));
    }


    function start_setup() {
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

    function load_woo_theme_scripts() {
        // Load our main stylesheet.
        wp_enqueue_style( 'my-css', get_stylesheet_uri() );
        wp_enqueue_style( 'wp-woo-styles', get_template_directory_uri() . '/css/style.css' );
        // wp_enqueue_style( 'fa-font-styles', get_template_directory_uri() . '/css/font-awesome.min.css' );
        wp_enqueue_script( 'jquery', true );
        wp_localize_script('jquery', 'get_data',
            array(
                'url' => admin_url('admin-ajax.php')
            )
        );
        wp_register_script( 'woo_theme_script',  get_template_directory_uri() . '/js/wootheme-script.js', array('jquery'), '1.0.1', true );
        wp_enqueue_script( 'woo_theme_script' );

        // Show script only on login page
        wp_register_script( 'woo_theme_login',  get_template_directory_uri() . '/js/login.js', array('jquery'), '1.0.1', true );
        if(is_page('login') ){
            wp_enqueue_script( 'woo_theme_login' );
        }
    }

}
new WooStartSettings;