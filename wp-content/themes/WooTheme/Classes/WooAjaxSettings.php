<?php

namespace Classes;

/**
 * Here created AJAX heandles
 * Class WooAjaxSettings
 * @package Classes
 */
class WooAjaxSettings {

	function __construct() {
		//AJAX for login user
		add_action('wp_ajax_woo_login_user', array($this, 'woo_login_user_callback'));
		add_action('wp_ajax_nopriv_woo_login_user', array($this, 'woo_login_user_callback'));
	}

	/**
	 * Login user and redirect to his blog
	 */
	function woo_login_user_callback(){
		$user = wp_signon();
		if ( is_wp_error($user) ) {
			echo json_encode($user);
			die();
		}
		// Check user's role. Need to return status and route
		$redirect_path = home_url();

		/**
		 * Here settings blog IDs
		 */
		$price_category_array = array(
			'dealer'    => 3,
			'vip'       => 5,
			'3_opt'     => 4,
			'8_opt'     => 6,
		);

		$user_data = get_userdata($user->ID);

		if($primary_blog_name = $user_data->price_category){
			$users_blog_id = $price_category_array[$primary_blog_name];
			$redirect_path = get_site_url($users_blog_id);
		}

		echo json_encode($redirect_path);
		die();
	}
}