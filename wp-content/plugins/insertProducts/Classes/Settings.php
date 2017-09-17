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

                <?php
                    //&status=insert_products
                    if(isset($_GET['status']) && $_GET['status'] == 'insert_products'){
                        echo 'insert_products<br>';

                        $dbHandler = new DatabaseManager();
	                    $dbhc = $dbHandler->connectToDB('localhost','woocoomerce','UTF8','root','root');

	                    $dir = wp_upload_dir()['basedir'] . '/price';
                        $fileHandler = new FileManager();

                        //Find price file in dir
                        $priceFileLink = $fileHandler->findPriceFile($dir,'price');

                        //Get file resource for next read it
	                    $fileResource = $fileHandler->tryToOpenPrice($priceFileLink["priceFile"]);


	                    while ( ( $priceRow = fgets( $fileResource ) ) !== false ) {
                            //Here we work with row of file
                            $productData = json_decode($priceRow);

                            $categoryId = $dbHandler->getCategoryId($dbhc, $productData->category);

		                    $productId = $dbHandler->takeProductId($dbhc,$productData->sku);
		                    $productPostId = $dbHandler->insertOrUpdateProduct( $dbhc,$productData, $productId );
		                    $dbHandler->insertOrUpdateProductMeta( $dbhc, $productPostId,$categoryId,
                                [
                                    '_stock'=>$productData->stock,
                                    '_sku'=>$productData->sku,
                                    '_regular_price'=>$productData->price,
                                    '_price'=>$productData->price,
                                ]
                            );

                            echo "<pre>";
		                    echo "SKU: {$productData->sku}<br>";
		                    echo "productId: $productId<br>";
		                    echo "productPostId: $productPostId<br>";
		                    echo "</pre>";
	                    }
	                    if ( ! feof( $fileResource ) ) {
		                    echo "Error: unexpected fgets() fail\n Didn't read file to the end";
	                    }

                        //Close price file
	                    $fileHandler->closePriceFile($fileResource);

                    }

                ?>

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