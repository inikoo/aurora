<?php
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Campaign.php');
include_once('../../class.Charge.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.PartLocation.php');

include_once('../../class.Warehouse.php');
include_once('../../class.Node.php');
include_once('../../class.Shipping.php');
include_once('../../class.SupplierProduct.php');
include_once('local_map.php');

error_reporting(E_ALL);
date_default_timezone_set('UTC');
include_once('../../set_locales.php');
require('../../locale.php');
$_SESSION['locale_info'] = localeconv();

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
$codigos=array();


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           
date_default_timezone_set('UTC');

$_department_code='';
$software='Get_Products.php';
$version='V 1.1';

$Data_Audit_ETL_Software="$software $version";


$_argv=$_SERVER['argv'];

if(isset($_argv[1]))
$file_name=$_argv[1];
else
$file_name='/data/excel_order/AWorder2002.xls';
if(isset($_argv[2]))
$date=$_argv[2];
else
$date=date("Y-m-d H:i:s");

if(isset($_argv[3]) and $_argv[3]=='old'){
$map=$_y_map_old;
$is_old=true;
}else{
$map=$_y_map;
$is_old=false;
}
$editor=array(
                            'Date'=>$date,
                            'Author Name'=>'',
                            'Author Alias'=>'',
                            'Author Type'=>'',
                            'Author Key'=>0,
                            'User Key'=>0,
                        );




//$csv_file='order_uk_tmp.csv';
$csv_file='gb.csv';
//print '/usr/local/bin/xls2csv    -s cp1252   -d 8859-1   '.$file_name.' > '.$csv_file;

//exit;
exec('/usr/local/bin/xls2csv    -s cp1252   -d 8859-1   '.$file_name.' > '.$csv_file);

$handle_csv = fopen($csv_file, "r");
$column=0;
$products=false;
$count=0;

$store_key=1;
$create_cat=false;

$gold_camp=new Campaign('code','UK.GR');
$vol_camp=new Campaign('code','UK.Vol');
$bogof_camp=new Campaign('code','UK.BOGOF');
$fam_promo=$fam_promo=new Family('code','Promo_UK',$store_key);
$fam_promo_key=$fam_promo->id;



$__cols=array();
$inicio=false;
while(($_cols = fgetcsv($handle_csv))!== false){
  

  $code=$_cols[$map['code']];

 
  if(($code=='FO-A1' or $code=='AWFO-01' or $code=='3DART-01') and !$inicio){
    $inicio=true;
    $x=$__cols[count($__cols)-4];
    $z=$__cols[count($__cols)-3];
    $a=$__cols[count($__cols)-2];
    $b=$__cols[count($__cols)-1];
    $c=$_cols;
    $__cols=array();
    $__cols[]=$x;
    $__cols[]=$z;
    $__cols[]=$a;
    $__cols[]=$b;
    $__cols[]=$c;

  }elseif($code=='Credit'){
    break;
  }elseif(preg_match('/First Order Bonus - Welcome/',$_cols[6])){
      break;
  }
  
  $__cols[]=$_cols;
}




$fam_name='Products Without Family';
$fam_code='PND_UK';


$new_family=true;


$department_code='ND_UK';
$department_name='Products Without Department';

$current_fam_name='';
$current_fam_code='';
$fam_position=-10000;
$promotion_position=100000;
$promotion='';


$codes=array();

$counter=0;
foreach($__cols as $cols){
  
  if(preg_match('/First Order Bonus/i',$cols[$map['description']])){
    break;
  }


  $is_product=true;
  
  $code=_trim($cols[$map['code']]);


  if(count($cols)<25 or($is_old and $code=='HOT-01')){
    continue;
    //print_r($cols);
    
  }


  $price=$cols[$map['price']];
  $supplier_code=_trim($cols[$map['supplier_code']]);
  $part_code=_trim($cols[$map['supplier_product_code']]);
  $supplier_cost=$cols[$map['supplier_product_cost']];
  $rrp=$cols[$map['rrp']];


  //    if(!preg_match('/bot-10/i',$code)){
  //  continue;
  //   }
  
  $code=_trim($code);
  
    if($code=='' or !preg_match('/\-/',$code) or preg_match('/total/i',$price)  or  preg_match('/^(pi\-|cxd\-|fw\-04)/i',$code))
    $is_product=false;
      if(preg_match('/^(ob\-108|ish\-94|rds\-47)/i',$code))
    $is_product=false;
  if(preg_match('/^staf-set/i',$code) and $price=='')
    $is_product=false;
  if(preg_match('/^hook-/i',$code) and $price=='')
    $is_product=false;
  if(preg_match('/^shop-fit-/i',$code) and $price=='')
    $is_product=false;
  if(preg_match('/^pack-01a|Pack-02a/i',$code) and $price=='')
    $is_product=false;
  if(preg_match('/^(DB-IS|EO-Sticker|ECBox-01|SHOP-Fit)$/i',$code) and $price=='')
    $is_product=false;
  
  if($is_product)
  $codes[]=strtolower($code);
  
 
  }

//print_r($codes);


  $sql=sprintf("select `Product Code` from `Product Dimension` where `Product Store Key`=1 group by `Product Code`");
    $res_code=mysql_query($sql);
    while($row=mysql_fetch_array($res_code)){
    $code=strtolower($row['Product Code']);
    if(!in_array($code,$codes)){
    $counter++;
        print "$counter $code to be discontinued\n";
        
        $product=new Product('code_store',$code,1);
    if ($product->id) {
        $current_part_skus=$product->get_current_part_skus();


        foreach($current_part_skus as $_part_sku) {
            $part=new Part($_part_sku);
            //$part->update_status('Not In Use');
            
            $supplier_products=$part->get_supplier_products();
            
            foreach($supplier_products as $supplier_product){
                $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Status`='Not In Use' where `Supplier Product Key`=%d",
                $supplier_product['Supplier Product Key']
                );
                mysql_query($sql);
                //print "$sql\n";
                $sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part In Use`='No' where `Supplier Product Part Key`=%d",
                $supplier_product['Supplier Product Part Key']
                );
                mysql_query($sql);
              //  print "$sql\n";
                
            }
            
            $part->update_availability();
            
             if($part->data['Part Current Stock']<=0 ){
    
    $part->update_status('Not In Use');
    }else{
    
    
    }
            
            
        }
    }
        
    
    }
    
    }



?>