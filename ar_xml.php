<?
require_once 'common.php';
include_once('aes.php');
require_once('classes/Product.php');
require_once('external_libs/xml/minixml.inc.php');

$key ="mc49015kfkuto0lk,rbijr0gl*(&172225224961644tui0f9jf98d345hpyl09jpl9fhlptjip_[[gdo;dlfkglklyndfUIHNNUIOSO832&*^)*(^^&*^*32KJLSDJFSNXRJ";
$key2="dsoap968m0()*)mERG048m03495xm3[7eyf7ERG8awe8723mx7o0sjt6pvp[rp9uyt87JYRTdr6erwet6r7twe6rt71wert7FWEw6u1s7t6dv1t71ry7i6yv1i78r6ui78rvu";
//$path="app_files/p_xmldb/";

$data=array('key'=>$key2,'trash'=>md5(date('U')));
$tipo='sincronize';

switch($tipo){
 case('sincronize'):
   for($i=1;$i<13000;$i++){
     sincronize_product($i,$data) ;
   }
 }

function sincronize_product($product_id,$data){
  global $key,$key2;
  $product=new Product($product_id);
  if($product->id){
   $data['operation']='update';
   $data['code']=$product->get('code');
   $data['product_data']=array(
		       'id'=>$product->id,
		       'units'=>number($product->get('units')),
		       'units_tipo'=>$product->get('units_tipo'),
		       'price'=>$product->get('price'),
		       'description'=>$product->get('description'),
		       'sdescription'=>$product->get('sdescription'),
		       );
   $data_encoded= json_encode($data);
  
   $request=base64_encode(AESEncryptCtr($data_encoded,'123',256));
   print file_get_contents('http://localhost/aw/ar_end_xml.php?data='.$request)."\n";
  }


}

?>