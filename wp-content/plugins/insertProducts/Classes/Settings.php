<?php

namespace insertProducts\Classes;


class Settings{
	public function __construct() {
		add_action( 'admin_init', array( $this, 'register_require_scripts' ) );
		add_action( 'admin_menu', array( $this, 'add_field_to_admin_page') );
	}

	//Register scripts on create object
	function register_require_scripts() {
		wp_register_script( 'insert-product', plugins_url( '../scripts/script.js', __FILE__ ), array('jquery'), '1.0.1', true );
	}
	// Connect script
	function load_required_scripts() {
		wp_enqueue_script( 'insert-product' );
	}

	// Init menu's item on admin page
	function add_field_to_admin_page(){
		$page = add_menu_page( 'On this page you can add products to your store. (v3)', 'Add products', 'manage_options', 'insert_products', array( $this, 'add_products_field'), 'dashicons-cart', 4 );
		add_action( 'admin_print_scripts-' . $page, array( $this, 'load_required_scripts') );
	}

	/**
	 * Create page template for admin page
	 */
	function add_products_field(){
		$mem_start = memory_get_usage();
		$time_start = time();
		?>
		<div class="wrap">
			<h2><?php echo get_admin_page_title() ?></h2>

			<input class="button button-primary start_insert_products" value="Insert product into Shop" />
			<div class="show_results">

                <?php new ReadProductsFile();?>

            </div>
		</div>
		<?php
		echo "<pre>";
        print_r([
            'start_m'=>$mem_start,
            'max_m'=>memory_get_usage(),
        ]);
		print_r([
			'start_time'=>$time_start,
			'end_time'=>time(),
		]);
		echo "</pre>";
	}
}