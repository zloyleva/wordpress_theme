<?php

namespace insertProducts\Classes;
//use insertProducts\Classes\InsertSingleProductFactory;

class ReadProductsFile {


	public function __construct() {
		$this->readPriceFile();
	}

	private function readPriceFile() {
		//get dir
		$dir           = wp_upload_dir()['basedir'] . '/price';
		$dirCollection = scandir( $dir );

		$priceFile = '';

		foreach ( $dirCollection as $item ) {
			echo "<pre>";
			print_r( $item );
			echo "</pre>";
			if ( ! ( strpos( $item, 'price' ) === false ) ) {
				$priceFile = $dir . "/" . $item;
			}
			//No need to check exist "price" file, we do it late
		}
		if ( $priceFile ) {
			try {
				$handle = fopen( $priceFile, 'r' );
				if ( $handle ) {
					while ( ( $buffer = fgets( $handle ) ) !== false ) {
						//Call insert PART
						$s = new InsertSingleProductFactory( json_decode( $buffer ) );
						unset( $s );
					}
					if ( ! feof( $handle ) ) {
						echo "Error: unexpected fgets() fail\n";
					}
					fclose( $handle );
				}
			} catch ( \Exception $e ) {
				echo $e;
			}
		}

		if ( $priceFile ) {
//			unlink($priceFile);
			echo "detected file $priceFile";
		}
	}

}
