<?php
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Deal.php';
include_once '../../class.DealCampaign.php';

include_once '../../class.Charge.php';
include_once '../../class.Image.php';

include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Warehouse.php';
include_once '../../class.Node.php';
include_once '../../class.Shipping.php';
include_once '../../class.SupplierProduct.php';
error_reporting(E_ALL);

date_default_timezone_set('UTC');

include_once '../../set_locales.php';
require '../../locale.php';
$_SESSION['locale_info'] = localeconv();
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {print "Error can not connect with database server\n";exit;}
//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {print "Error can not access the database\n";exit;}
$codigos=array();


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';







$sql=sprintf("select `Part Reference`,`Part SKU` from `Part Dimension` where `Part Reference`!='' order by `Part SKU` desc ");
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {
	$reference=$row['Part Reference'];
	$part=new Part($row['Part SKU']);


	$sufixes=array('c','b','d','','_p');

$extensions=array('jpg','JPG');
foreach($extensions as $extension)
	foreach ($sufixes as $sufix) {
		$image_name=strtolower($reference).$sufix.".".$extension;

		$tmp_file='/tmp/'.$image_name;
		$url='http://www.aw-regalos.com/fotos/'.$image_name;
		//print "$url\n";
		if (@getimagesize($url)) {
			if (file_put_contents($tmp_file, file_get_contents($url))) {
				print "$reference $tmp_file\n";

				$image_data=array(
					'file'=>$tmp_file,
					'source_path'=>'',
					'name'=>$image_name,
					'caption'=>''
				);

				$image=new Image('find',$image_data,'create');
				


				if (!$image->error) {
					$part->add_image($image->id);
					$part->update_main_image($image->id);
					$products_pids=$part->get_all_product_ids();
					foreach ($products_pids as $tmp) {

						$product=new Product('pid',$tmp['Product ID']);
						if ($product->data['Product Use Part Pictures']=='Yes')
							$product->add_image($image->id);
							$product->update_main_image($image->id);

					}
				}
				unlink($tmp_file);
			}

		}



	}

}






exit;



/* $sql="select `Product Code` from `Product Dimension`  group by `Product Code` "; */

/* $result=mysql_query($sql); */
/* while($row=mysql_fetch_array($result)   ){ */
/*   $code=$row['Product Code']; */
/*   print "$code\n"; */

$pics=array();

$path="/home/raul/aw_pic_bk/";
$img_array_full_path = glob($path."*.jpg");
//print_r($img_array_full_path);
foreach($img_array_full_path as $pic_path){
   $_pic_path=preg_replace('/.*\//','',$pic_path);
  if(preg_match('/[a-z0-9\-]+(|_l|_bis|_tris|_quad|_display|_displ|dpl|_box)+.jpg/',$_pic_path)){
    // print "$pic_path\n";
    if(preg_match('/^[a-z]+\-\d+[a-z]{0,3}/',$_pic_path,$match)){
      $root=$match[0];
      //print "$root -> $pic_path\n";
      if(array_key_exists($root,$pics))
	$pics[$root][]=$pic_path;
      else
	$pics[$root]=array($pic_path);
    }
  }
} 
//print_r($pics);


chdir('../../');
foreach($pics as $key=>$value){
  $sql=sprintf("select `Product ID` from `Product Dimension` where `Product Code`=%s ",prepare_mysql($key));
  //print "$sql\n";
  $res=mysql_query($sql);
  while($row=mysql_fetch_array($res)){
    $product=new Product('pid',$row['Product ID']);
    foreach($value as $img_filename){
     
      //print "---------- ".$img_filename."\n";
      $rand=rand().rand();
      $tmp_file='app_files/pics/tmp.jpg';
      copy($img_filename,$tmp_file );
      
      $product->add_image($tmp_file,'principal');
      //print_r($product);
      // print $product->msg."\n";
      $product->update_main_image();
      // unlink($tmp_file);
    }

  }
}


?>