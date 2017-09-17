<?php
/**
 * Open dir find such price file.
 * Open and close price file.
 */
namespace insertProducts\Classes;



class FileManager {

	public function __construct() {}

	public function findPriceFile($dir, $searchFilePhrase){
		$priceFile = '';
		try{
			$dirCollection = scandir( $dir );
		}catch (Exception $e){
			return ['error' => $e->getMessage()];
		}

		if (!$dirCollection){
			return ['error' => "Can't open such directory"];
		}

		foreach ( $dirCollection as $item ) {
			echo "<pre>";
			print_r( $item );
			echo "</pre>";
			if ( ! ( strpos( $item, $searchFilePhrase ) === false ) ) {
				$priceFile = $dir . "/" . $item;//todo add check: how many price files in directory. And how long time it's in there
			}
			//No need to check exist "price" file, we do it late
		}
		return ($priceFile)?['priceFile'=>$priceFile]:['error' => "Don't find price file"];
	}

	public function tryToOpenPrice($priceFileLink){
		try {
			return fopen( $priceFileLink, 'r' );
		} catch ( Exception $e ) {
			return ['error' => $e->getMessage()];
		}
	}

	public function closePriceFile($file){
		fclose( $file );
	}

}