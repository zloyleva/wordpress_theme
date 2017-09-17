<?php

namespace insertProducts\Classes;
use \PDO;

class DatabaseManager {

	private $dbName;
	private $taxonomy = 'product_cat';
	private $iso = array(
		"Є"=>"YE","І"=>"I","Ѓ"=>"G","і"=>"i","№"=>"#","є"=>"ye","ѓ"=>"g",
		"А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D",
		"Е"=>"E","Ё"=>"YO","Ж"=>"ZH",
		"З"=>"Z","И"=>"I","Й"=>"J","К"=>"K","Л"=>"L",
		"М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R",
		"С"=>"S","Т"=>"T","У"=>"U","Ф"=>"F","Х"=>"X",
		"Ц"=>"C","Ч"=>"CH","Ш"=>"SH","Щ"=>"SHH","Ъ"=>"'",
		"Ы"=>"Y","Ь"=>"","Э"=>"E","Ю"=>"YU","Я"=>"YA",
		"а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d",
		"е"=>"e","ё"=>"yo","ж"=>"zh",
		"з"=>"z","и"=>"i","й"=>"j","к"=>"k","л"=>"l",
		"м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
		"с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"x",
		"ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"shh","ъ"=>"",
		"ы"=>"y","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
		"—"=>"","«"=>"","»"=>"","…"=>"",
		"\""=>"","'"=>"",
		"    "=>"-","   "=>"-","  "=>"-"," "=>"-",
		"."=>"",","=>"","\n"=>"","\\"=>"","?"=>"",
		"("=>"",")"=>"","["=>"","]"=>"","*"=>"","+"=>"","/"=>""
	);

	public function __construct() {
	}

	public function connectToDB($host,$dbName,$charset,$user,$password){

		$this->dbName = $dbName;

		$dsn = "mysql:host=$host;dbname=$dbName;charset=$charset";
		try {
			return $dbHandler = new PDO($dsn, $user, $password, [
				PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				PDO::ATTR_EMULATE_PREPARES   => false,
				PDO::ATTR_PERSISTENT => true
			]);
		} catch (\PDOException $e) {
			echo $e->getMessage();
			die;
		}
	}

	public function takeProductId($dbHandler,$sku){
		$sql_find = "SELECT post_id FROM ".$this->dbName.".wp_postmeta WHERE meta_key='_sku' AND meta_value='{$sku}'";
		$result = $dbHandler->prepare( $sql_find );
		$result->execute();
		return $result->fetchColumn();
	}

	/**
	 * Insert or update main data of product. Return post ID if product exist and 0 if not
	 * @param $dbHandler
	 * @param $product
	 * @param int $productId
	 *
	 * @return mixed
	 */
	public function insertOrUpdateProduct( $dbHandler, $product, $productId = 0 ){
		$id = ($productId)?"ID,":"";
		$idValue = ($productId)?$productId.',':'';
		$post_excerpt = ($product->short_desc)??'';

		$insertQuery = "INSERT INTO ".$this->dbName.".wp_posts 
			(". $id ."post_author,post_content,post_date,post_date_gmt,post_content_filtered,
			post_title,post_excerpt,post_status,ping_status,post_name,to_ping,pinged,
			post_modified,post_modified_gmt,guid,post_type) 
			VALUES
			(". $idValue ."1,'".addslashes($product->content)."',curdate(),curdate(),'',
			'".addslashes($product->name)."','".addslashes($post_excerpt)."','publish','closed','".$this->convertChar($product->name)."','','',
			curdate(),curdate(),'guid','product')
			ON DUPLICATE KEY UPDATE
			post_content = '".addslashes($product->content)."',
			post_title = '".addslashes($product->name)."',
			post_excerpt = '".addslashes($post_excerpt)."';
		";

		$result = $dbHandler->prepare($insertQuery);
		$result->execute();
		return  $dbHandler->lastInsertId('ID');
	}

	private function convertChar($string){
		$string = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()',$string);
		preg_match_all("/(\b\w+\b)/i",$string,$matches);
		return implode('-',$matches[0]);
	}

	function insertOrUpdateProductMeta( $dbHandler, $post_id,$categoryId, $productArray ){

		$staticArgs = [
			'_manage_stock'=>'yes',
			'total_sales'=>'0',
			'_stock_status'=>'instock',
			'_visibility'=>'visible',
			'_backorders'=>'no',
			'_featured'=>'no',
			'_virtual'=>'no',
			'_downloadable'=>'no',
		];

		$args = array_merge($productArray,$staticArgs);

		foreach ($args as $keyName => $arg){
			$sqlQuery = "
			INSERT INTO ".$this->dbName.".wp_postmeta 
			(post_id,meta_key,meta_value) 
			VALUES
			({$post_id},'{$keyName}','{$arg}')
			ON DUPLICATE KEY UPDATE
			meta_value = '{$arg}';
			";
			$result = $dbHandler->prepare($sqlQuery);
			$result->execute();
		}

		try{
			$sqlQuery = "
			INSERT INTO ".$this->dbName.".wp_term_relationships 
			(object_id,term_taxonomy_id,term_order) 
			VALUES
			({$post_id},'{$categoryId}',0)
			";
			$result = $dbHandler->prepare($sqlQuery);
			$result->execute();
		}catch (\PDOException $e){}
	}

	function getCategoryId($dbHandler, $cat_array){
		$parent_id = 0;
		$current_item = 0;

		foreach ($cat_array as $item){
			$item = addslashes($item);
			$check_item = $this->isCategoryExist($dbHandler, $item, $this->taxonomy, $parent_id);
			if($check_item){
				$parent_id = $check_item;
				echo "<b>find {$item}</b><br>";
			}else{
				echo "dont find {$item}<br>";

				$sqlQuery = "
				INSERT INTO ".$this->dbName.".wp_terms
				(name, slug)
				VALUES
				('{$item}','{$this->convertChar($item)}');
				";
				$result = $dbHandler->prepare($sqlQuery);
				$result->execute();
				$categoryId = $dbHandler->lastInsertId('term_id');

				$sqlQuery = "
				INSERT INTO ".$this->dbName.".wp_term_taxonomy
				(term_taxonomy_id, term_id,taxonomy,description,parent,count)
				VALUES
				('{$categoryId}','{$categoryId}','{$this->taxonomy}','',{$parent_id},0);
				";
				$result = $dbHandler->prepare($sqlQuery);
				$result->execute();

				echo "insert: ". $categoryId ."<br>";
				$parent_id = $categoryId;
			}
		}
		return $parent_id;
	}

	private function isCategoryExist($dbHandler, $categoryName, $taxonomy, $parent){
		$sqlQuery = "
		SELECT terms.term_id as category_id 
		FROM ".$this->dbName.".wp_terms as terms
		LEFT JOIN woocoomerce.wp_term_taxonomy as taxonomy
		ON terms.term_id = taxonomy.term_id
		WHERE name = '{$categoryName}' AND taxonomy = '{$taxonomy}' AND parent = {$parent};
		";
		$result = $dbHandler->prepare($sqlQuery);
		$result->execute();
		return $result->fetchColumn();
	}

}