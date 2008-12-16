<?
require_once 'common.php';
include_once('aes.php');
require_once('classes/Product.php');
require_once('external_libs/xml/minixml.inc.php');

$key ="mc49015kfkuto0lk,rbijr0gl*(&172225224961644tui0f9jf98d345hpyl09jpl9fhlptjip_[[gdo;dlfkglklyndfUIHNNUIOSO832&*^)*(^^&*^*32KJLSDJFSNXRJ";
$key2="dsoap968m0()*)mERG048m03495xm3[7eyf7ERG8awe8723mx7o0sjt6pvp[rp9uyt87JYRTdr6erwet6r7twe6rt71wert7FWEw6u1s7t6dv1t71ry7i6yv1i78r6ui78rvu";
$key3='fdmc4m75nc2-387xn5982472cnp78N787HNP8n7p878NP87j8P7Jp7J87j877&^*N00980k(*k08J90J8H8GV76O78693942873NENASUHSDALL34R2PP234N384X5N857XN3';
//$path="app_files/p_xmldb/";

$data=array('key'=>$key2,'trash'=>md5(date('U')));
$tipo='sincronize';

switch($tipo){
 case('sincronize'):
   if(isset($_REQUEST['product_id']))
     $id=$_REQUEST['product_id'];
   else
     $id=$_SESSION['state']['product']['id'];
   $res=sincronize_product($id,$data) ;
   echo $res;
   
 }

function sincronize_product($product_id,$data){
  global $key,$key2,$key3;
  $product=new Product($product_id);
  if($product->id){
   $data['operation']='update';
   $data['code']=$product->get('code');
   $data['product_data']=array(
		       'id'=>$product->id,
		       'units'=>number($product->get('units')),
		       'units_tipo'=>$product->get('units_tipo'),
		       'price'=>$product->get('price'),
		       'web_status'=>$product->get('web_status'),
		       'description'=>$product->get('description'),
		       'sdescription'=>$product->get('sdescription'),
		       );
   $data_encoded= json_encode($data);
  
   $request=base64_encode(AESEncryptCtr($data_encoded,'123',256));
   $response= json_decode(file_get_contents('http://localhost/aw/ar_end_xml.php?data='.$request),true);
   //print_r($response);
   
   $fingerprint= json_decode(
			      AESDecryptCtr(
					    base64_decode($response['fingerprint'])
					    ,$key3
					    ,256)
			      ,true);
   $fingerprint_ok=false;
   if(is_array($fingerprint) and $fingerprint[0]=='ok'){
     $fingerprint_ok=true;
   }
   
   if($response['ok']==true){
     $response=array('fingerprint'=>$fingerprint_ok,'ok'=>true,'msg'=>_('Product Sincronized'));
     $product->save('sincro_db');
     echo json_encode($response);
   }else{
     $response=array('ok'=>false,'msg'=>$response['msg']);
     echo json_encode($response);
   }

   //check finger print;
   
   

  }


}

?>