<?php

namespace insertProducts\Classes;

// it's a factory
class InsertSingleProductFactory {

	private $productProperty = [
		'post_status'=>'publish',
		'post_type'=>'product',
		'taxonomy'=>'product_cat',
		'product_type_name'=>'simple',
		'product_type'=>'product_type',
	];

	function __construct($product) {

		$productCategoryId = $this->takeCategoryId( $product->category );

		if ($productId = $this->takeProductId( $product ) ){
			//We find product on DB and need to update it
			$productPostId = $this->insertOrUpdateProduct( $product, $productId );
			echo "<pre>";
			echo "We update product {$productId}";
			echo "</pre>";
		}else{
			//We don't find product on DB and need to insert it
			$productPostId = $this->insertOrUpdateProduct( $product, $productId );
			echo "<pre>";
			echo "We don't find product {$productPostId}->{$product->sku} on DB and need to insert it ";
			echo "We insert product {$productPostId}";
			echo "</pre>";
		}

		if ($productPostId){
			//Add meta data for product
			$this->setProductMeta( $productPostId, $product, $productCategoryId);
		}else{
			//Return error - don't insert/update product
		}

		unset($productCategoryId);
		unset($productId);
		unset($productPostId);
	}

	private function takeCategoryId( $categoryArray ){

		// todo add regex to slice numbers before category name
		// todo add hook for set slice parameters
		if($categoryArray[0] == 'Наша продукция' || $categoryArray[0] == 'Продукция других производителей'){
			array_shift($categoryArray);
		}

		$parentId = 0;
		$currentId = 0;
		for ($i = 0; $i < count($categoryArray); $i++){
			$check_item = term_exists( $categoryArray[$i], $this->productProperty['taxonomy'], $parentId );
			if($check_item):
				// 'Category is exists
				$parentId = $check_item['term_id'];
			else:
				// Category isn't exists. Created new.
				$catParams = array(
					'cat_name' => $categoryArray[$i],           // Taxonomy name
					'category_parent' => $parentId,        // ID parent category
					'taxonomy' => $this->productProperty['taxonomy']                 // Taxonomy type
				);
				$parentId = wp_insert_category( $catParams, true );
				if(!$parentId){
					return ['error' => 'We has fail after insert category'];
				}
			endif;
			$currentId = $parentId;
		}
		//Return ID current category
		return $currentId;
	}

	private function takeProductId( $product ){
		global $wpdb;
		$sql_find = "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_sku' AND meta_value='{$product->sku}'";
		$result = $wpdb->get_var( $sql_find );
		return $result;
	}

	private function insertOrUpdateProduct( $product, $productId = 0 ){
		$product_args = array(
			'post_title'    =>  $product->name,
			'post_content'  =>  ( isset($product->content) )?$product->content:'',
			'post_excerpt'  =>  ( isset($product->short_desc) )?$product->short_desc:'',
			'post_status'   =>  $this->productProperty['post_status'],
			'post_type'     =>  $this->productProperty['post_type']
		);
		if( $productId ){
			$product_args['ID'] = $productId;
		}
		$post_id = wp_insert_post( $product_args );
		unset($product_args);
		return $post_id;
	}

	private function setProductMeta( $post_id, $product, $product_cat_id = 0 ){
		wp_set_object_terms($post_id,$this->productProperty['product_type_name'], $this->productProperty['product_type']);
		wp_set_object_terms($post_id,(integer) $product_cat_id, $this->productProperty['taxonomy']);

		update_post_meta($post_id, '_visibility', 'visible');
		update_post_meta($post_id, '_stock_status', 'instock');
		update_post_meta($post_id, 'total_sales', '0');
		update_post_meta($post_id, '_downloadable', 'no');
		update_post_meta($post_id, '_virtual', 'no');
		update_post_meta($post_id, '_regular_price', $product->price);
		update_post_meta($post_id, '_sale_price', '');
		update_post_meta($post_id, '_purchase_note', '');
		update_post_meta($post_id, '_featured', 'no');
		update_post_meta($post_id, '_weight', '');
		update_post_meta($post_id, '_length', '');
		update_post_meta($post_id, '_width', '');
		update_post_meta($post_id, '_height', '');
		update_post_meta($post_id, '_sku', $product->sku);
		update_post_meta($post_id, '_product_attributes', array());
		update_post_meta($post_id, '_sale_price_dates_from', '');
		update_post_meta($post_id, '_sale_price_dates_to', '');
		update_post_meta($post_id, '_price', $product->price);
		update_post_meta($post_id, '_sold_individually', '');
		update_post_meta($post_id, '_manage_stock', 'yes');
		update_post_meta($post_id, '_backorders', 'no');
		update_post_meta($post_id, '_stock', $product->stock);
	}
}