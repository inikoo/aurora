<link rel="stylesheet" type="text/css" href="../../inikoo_files/css/ui.css.php" />
<link rel="stylesheet" type="text/css" href="../../inikoo_files/css/top_navigation.css.php" />

<?php

/**************************************************
This is a dummy class
***************************************************/
include_once('../../inikoo_files/common_splinter.php');
include_once('../../inikoo_files/classes/class.LightProduct.php');
include_once('../../inikoo_files/conf/checkout.php');

class Product{

	var $store_code;
	var $code;
	var $store_key;
	
	function __construct($code_store, $code, $store_key) {
		$this->store_code=3;
		$this->code=$code;
		$this->store_key=3;

	}
   
	function load($var=false){
	}
	
	function get($form_type, $option){
		global $ecommerce_url, $username, $method;
		$data=array('ecommerce_url'=>$ecommerce_url,'username'=>$username,'method'=>$method);
		$product=new LightProduct($this->code, $this->store_key);
		//$product=new LightFamily($this->code, 2);
		//print_r($product);
		$header=array('on'=>true);
		$s = empty($secure) ? '' : $_SERVER["HTTPS"];
		
		return $product->get_full_order_form('ecommerce', $data);
	}
	//print $family->get('Full Order Form',$options);
}

?>