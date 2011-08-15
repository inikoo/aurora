<?php
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Order.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
include_once('../../class.Invoice.php');
include_once('../../class.DeliveryNote.php');

$store_code='E';

error_reporting(E_ALL);
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  
require_once '../../common_functions.php';
require_once '../../conf/timezone.php';   
date_default_timezone_set(TIMEZONE) ;


include_once('../../set_locales.php');

require_once '../../conf/conf.php';   

mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           
date_default_timezone_set('Europe/Madrid');
$_SESSION['lang']=1;
include_once('ci_local_map.php');
include_once('ci_map_order_functions.php');

$software='Get_Orders_DB.php';
$version='V 1.0';//75693

$Data_Audit_ETL_Software="$software $version";
srand(12345);

$store_key=1;
$dept_no_dept=new Department('code_store','ND',$store_key);
if(!$dept_no_dept->id){
  $dept_data=array(
		   'code'=>'ND',
		   'name'=>'Products sin departmaneto',
		   'store_key'=>$store_key
		   );
  $dept_no_dept=new Department('create',$dept_data);
  $dept_no_dept_key=$dept_no_dept->id;
}
$dept_promo=new Department('code_store','Promo',$store_key);
if(!$dept_promo->id){
  $dept_data=array(
		   'code'=>'Promo',
		   'name'=>'Articulos Promocionales',
		   'store_key'=>$store_key
		   );
  $dept_promo=new Department('create',$dept_data);
  
}
$dept_no_dept_key=$dept_no_dept->id;
$dept_promo_key=$dept_promo->id;

$fam_no_fam=new Family('code_store','PND_GB',$store_key);
if(!$fam_no_fam->id){
  $fam_data=array(
		   'Product Family Code'=>'PSF_ES',
		   'Product Family Name'=>'Productos sin Familia',
		   'Product Family Main Department Key'=>$dept_no_dept_key
		   );
  $fam_no_fam=new Family('create',$fam_data);
  $fam_no_fam_key=$fam_no_fam->id;
}
$fam_promo=new Family('code_store','Promo_ES',$store_key);
if(!$fam_promo->id){
  $fam_data=array(
		   'code'=>'Promo_ES',
		   'name'=>'Articulos Promocionales',
		   'Product Family Main Department Key'=>$dept_promo_key
		   );
  $fam_promo=new Family('create',$fam_data);
  
}

$fam_no_fam_key=$fam_no_fam->id;
$fam_promo_key=$fam_promo->id;

$sql="select p.id,p.code,p.description,p.units,p.price,p.rrp,g.name as fam_code,g.description as fam_name ,d.code as dept_code,d.name as dept_name from ci.product as p left join ci.product_group as g  on (g.id=group_id) left join  ci.product_department as d on (d.id=department_id) ";
$result=mysql_query($sql);
while($_product=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  $supplier_code='UNK';
  $supplier_name='Desconocido';
  //  $_product['description']=mb_convert_encoding($_product['description'], "UTF-8", "ISO-8859-1,UTF-8");
  //$_product['fam_name']=mb_convert_encoding($_product['fam_name'], "UTF-8", "ISO-8859-1,UTF-8,CP1252");
  //    $_product['dept_name']=mb_convert_encoding($_product['dept_name'], "UTF-8", "ISO-8859-1,UTF-8");

  $code=$_product['code'];
 
  $w=1;
  $_w=1;
  $deals=array();
  if(!is_numeric($_product['units']) or $_product['units']<=0)
    $_product['units']=1;
  $units=$_product['units'];

  $sql="select code,name,supplier_id,price,sup_code from ci.product2supplier left join ci.supplier on (ci.supplier.id=supplier_id) where product_id=".$_product['id'];
  //print "$sql\n";
  $res2=mysql_query($sql);
  if($row=mysql_fetch_array($res2, MYSQL_ASSOC)   ){
    $supplier_code=$row['code'];
    $supplier_name=$row['name'];
    $supplier_cost=$row['price'];
    $scode=$row['sup_code'];
    
    if($scode=='')
      $scode=$_product['code'];

    if(!is_numeric($supplier_cost) or $supplier_cost<=0)
      $supplier_cost=0.6*$_product['price']/$_product['units'];

  }


 $data=array(
		  'product sales state'=>'For Sale',
		  'product type'=>'Normal',
		  'product record type'=>'Normal',
		  'Product Web Configuration'=>'Online Auto',
		  'product locale'=>'es_ES',
		  'product currency'=>'EUR',
		  'product code'=>$_product['code'],
		  'product price'=>sprintf("%.2f",$_product['price']),
		  'product rrp'=>$_product['rrp'],
		  'product units per case'=>$_product['units'],
		  'product name'=>$_product['description'],
		  'product family code'=>$_product['fam_code'],
		  'product family name'=>$_product['fam_name'],
		  'product main department name'=>$_product['dept_name'],
		  'product main department code'=>$_product['dept_code'],
		  'product special characteristic'=>$_product['description'],
		  'product family special characteristic'=>$_product['fam_name'],
		  'product net weight'=>$_w,
		  'product gross weight'=>$_w,
		  'deals'=>$deals
		    );
      //     print_r($cols);
      //print_r($data);
 exit;
       	$product=new Product('create',$data);
	

	exit("test add product\n");



   $the_supplier_data=array(
			    'Supplier Code'=>$supplier_code
			    ,'Supplier Name'=>$supplier_name
			    );

   $supplier=new Supplier('code',$supplier_code);
   if(!$supplier->id){

     $supplier=new Supplier('find create',$the_supplier_data);
   }
  
  /*  $sp_data=array( */
/* 		  'supplier key'=>$supplier->id, */
/* 		  'supplier code'=>$supplier->data['Supplier Code'], */
/* 		    'supplier name'=>$supplier->data['Supplier Name'], */
/* 		  'Supplier Product Code'=>$scode, */
/* 		  'Supplier Product Cost'=>sprintf("%.4f",$supplier_cost), */
/* 		  'Supplier Product Name'=>$_product['description'], */
/* 		    'Supplier Product Description'=>$_product['description'] */
/* 		    );  */
   
/*    $new_supplier_product=false; */
/*    $supplier_product=new SupplierProduct('supplier-code',$sp_data); */
/* 	if(!$supplier_product->id){ */
/* 	  $new_supplier_product=true; */
/* 	  $supplier_product=new SupplierProduct('new',$sp_data); */
/* 	} */
/* 	$part_data=array( */
/* 			 'Part Most Recent'=>'Yes', */
/* 			 'Part XHTML Currently Supplied By'=>sprintf('<a href="supplier.php?id=%d">%s</a>',$supplier->id,$supplier->get('Supplier Code')), */
/* 			 'Part XHTML Currently Used In'=>sprintf('<a href="product.php?id=%d">%s</a>',$product->id,$product->get('Product Code')), */
/* 			 'Part XHTML Description'=>preg_replace('/\(.*\)\s*$/i','',$product->get('Product XHTML Short Description')), */
/* 			 'part valid from'=>date('Y-m-d H:i:s'), */
/* 			 'part valid to'=>date('Y-m-d H:i:s'), */
/* 			 'Part Gross Weight'=>$w */
/* 			 ); */
/* 	$part=new Part('new',$part_data); */
/* 	//	print_r($part->data); */
	
/* 	$rules[]=array('Part Sku'=>$part->data['Part SKU'], */
/* 		       'Supplier Product Units Per Part'=>$units */
/* 		       ,'supplier product part most recent'=>'Yes' */
/* 		       ,'supplier product part valid from'=>date('Y-m-d H:i:s') */
/* 		       ,'supplier product part valid to'=>date('Y-m-d H:i:s') */
/* 		       ,'factor supplier product'=>1 */
/* 		       ); */
/* 	$supplier_product->new_part_list('',$rules); */
	
/* 	$part_list[]=array( */
/* 			   'Product ID'=>$product->get('Product ID'), */
/* 			   'Part SKU'=>$part->get('Part SKU'), */
/* 			   'Product Part Id'=>1, */
/* 			   'requiered'=>'Yes', */
/* 			   'Parts Per Product'=>1, */
/* 			   'Product Part Type'=>'Simple Pick' */
/* 			   ); */
/* 	$product->new_part_list('',$part_list); */
/* 	$supplier_product->load('used in'); */
/* 	$product->load('parts'); */
/* 	$part->load('used in'); */
/* 	$part->load('supplied by'); */

}






?>