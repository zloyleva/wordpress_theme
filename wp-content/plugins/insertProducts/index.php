<?php
/*
Plugin Name: Insert products to WooCommerce from CSV v2
Plugin URI: http://wordpress.org/plugins/my-plugin/
Description: This is a plugin for Insert products to WooCommerce from CSV + and add categories
Author: ZloyLeva
Version: 2.2.0
Author URI: http://localhost/
*/
set_time_limit(0);
require_once( plugin_dir_path( __FILE__ ) . 'Classes/Settings.php');
require_once( plugin_dir_path( __FILE__ ) . 'Classes/FileManager.php');
require_once( plugin_dir_path( __FILE__ ) . 'Classes/DatabaseManager.php');


use insertProducts\Classes\Settings;
use insertProducts\Classes\FileManager;
use insertProducts\Classes\DatabaseManager;
use \PDO;

class InsertProducts{

	public $upload_dir;

	function __construct(){
		error_reporting(E_ALL);
		ini_set('display_errors',1);

		$this->upload_dir = ABSPATH . 'wp-content/uploads';

		add_action( 'admin_init', array( new Settings(), '__construct' ) );
		register_activation_hook(__FILE__, array( $this, 'setUniqueColumnInDb' ));
		register_deactivation_hook(__FILE__, array( $this, 'unsetUniqueColumnInDb' ));

	}

	public function setUniqueColumnInDb(){
		global $wpdb;
		$wpdb->query("alter table {$wpdb->postmeta} add unique unique_meta_pair (post_id, meta_key)");
	}

	public function unsetUniqueColumnInDb(){
		global $wpdb;

		$wpdb->query("drop index unique_meta_pair on {$wpdb->postmeta}");
	}


}

new InsertProducts();