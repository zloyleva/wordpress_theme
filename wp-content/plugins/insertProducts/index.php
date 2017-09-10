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
require_once( plugin_dir_path( __FILE__ ) . 'Classes/ReadProductsFile.php');
require_once( plugin_dir_path( __FILE__ ) . 'Classes/InsertSingleProductFactory.php');

use insertProducts\Classes\Settings;
use insertProducts\Classes\ReadProductsFile;
use insertProducts\Classes\InsertSingleProductFactory;

class InsertProducts{

	public $db;
	public $upload_dir;

	function __construct(){
		error_reporting(E_ALL);
		ini_set('display_errors',1);

		global $wpdb;
		$this->db = $wpdb;
		$this->upload_dir = ABSPATH . 'wp-content/uploads';

		add_action( 'admin_init', array( new Settings(), '__construct' ) );

	}

}

new InsertProducts();