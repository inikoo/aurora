<?
//include("../../external_libs/adminpro/adminpro_config.php");

include_once('../../app_files/db/dns.php');
include_once('../../classes/Department.php');
include_once('../../classes/Family.php');
include_once('../../classes/Product.php');
include_once('../../classes/Supplier.php');
include_once('../../classes/Order.php');
error_reporting(E_ALL);
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}



require_once '../../common_functions.php';

mysql_query("SET time_zone ='UTC'");
mysql_query("SET NAMES 'utf8'");

require_once '../../myconf/conf.php';           
date_default_timezone_set('Europe/London');
$_SESSION['lang']=1;
include_once('local_map.php');

include_once('map_order_functions.php');

$software='Get_Orders_DB.php';
$version='V 1.0';

$Data_Audit_ETL_Software="$software $version";


$sql="select id from orders_data.order_data ";
$res=mysql_query($sql);
while($row2=mysql_fetch_array($res, MYSQL_ASSOC)){
  print $row2['id']."\r";
  $sql="select * from orders_data.order_data where id=".$row2['id'];
  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $header=mb_unserialize($row['header']);
     $products=mb_unserialize($row['products']);
     
     $filename_number=str_replace('.xls','',str_replace($row['directory'],'',$row['filename']));
     $map_act=$_map_act;$map=$_map;$y_map=$_y_map;
     
     // tomando en coeuntas diferencias en la posicion de los elementos
     if($filename_number<18803){// Change map if the orders are old
       $y_map=$_y_map_old;
       foreach($_map_old as $key=>$value)
	 $map[$key]=$value;
     }
     $prod_map=$y_map;
     if($filename_number==53378){
       $prod_map['no_price_bonus']=true;
       $prod_map['no_reorder']=true;
       $prod_map['bonus']=11;
  }
  list($act_data,$header_data)=read_header($header,$map_act,$y_map,$map);
  $header_data=filter_header($header_data);
  list($tipo_order,$parent_order_id)=get_tipo_order($header_data['ltipo'],$header_data);
  list($date_index,$date_order,$date_inv)=get_dates($row['timestamp'],$header_data,$tipo_order,true);
  if($date_order=='')$date_index2=$date_index;else$date_index2=$date_order;


  $transactions=read_products($products,$prod_map);
  $customer_data=setup_contact($act_data,$header_data,$date_index2);


  $data=array();
  $data['order date']=$date_order;
  $data['order id']=$header_data['order_num'];
  $data['order customer message']=$header_data['notes2'];
  $data['order original data mime type']='application/vnd.ms-excel';
  $data['order original data']=$row['filename'];
  $data['order original data source']='DB:orders_data.order.data';
  $data['order original metadata']=$row['id'];
  
  //  print_r($products);
  $products_data=array();
  foreach($transactions as $transaction){
    if(preg_match('/^credit/i',$transaction['code']))
      continue;
    $supplier_product_cost=sprintf("%.4f",$transaction['supplier_product_cost']);
    // print_r($transaction);
    if($transaction['units']=='')
      $transaction['units']=1;
    if($transaction['supplier_product_code']=='')
      $transaction['supplier_product_code']='?'.$transaction['code'];
    
    if( preg_match('/\d/',$transaction['supplier_code']) ){
      $transaction['supplier_code'] ='';
      $supplier_product_cost='';
    }
    if(preg_match('/^(SG|FO)\-/i',$transaction['code']))
     $transaction['supplier_code'] ='AW';
    if($transaction['supplier_code']=='AW')
      $transaction['supplier_product_code']=$transaction['code'];
    if($transaction['supplier_code']=='' or preg_match('/\d/',$transaction['supplier_code']) )
      $transaction['supplier_code']='Unknown Supplier';
    $unit_type='Piece';
    $description=_trim($transaction['description']);$description=str_replace("\\\"","\"",$description);
    if(preg_match('/Joie/i',$description) and preg_match('/abpx-01/i',$transaction['code']))
      $description='2 boxes joie (replacement due out of stock)';
    $product_data=array(
			'product code'=>_trim($transaction['code'])
			,'product name'=>$description
			,'product unit type'=>$unit_type
			,'product units per case'=>$transaction['units']
			,'product rrp'=>sprintf("%.2f",$transaction['rrp']*$transaction['units'])
			,'product price'=>sprintf("%.2f",$transaction['price'])
			,'supplier code'=>_trim($transaction['supplier_code'])
			,'supplier name'=>_trim($transaction['supplier_code'])
			,'supplier product cost'=>$supplier_product_cost
			,'supplier product code'=>_trim($transaction['supplier_product_code'])
			,'supplier product name'=>$description
			,'auto_add'=>true
			,'date'=>$date_order
			);
 //    if(is_numeric($transaction['supplier_code'])  or preg_match('/\d/',$transaction['supplier_code'])){
//       print $row2['id']."\n";
//       print_r($product_data);
//     }

//      print_r($products);

 //    if(preg_match('/ish-33/i',$transaction['code'])){
//       print $row2['id']."\n";
//       print_r($product_data);
//     }
    
    //print_r($product_data);
    $product=new Product('code-name-units-price',$product_data);
    //     "Ahh canto male pedict\n";
    if(!$product->id){
      print_r($product_data);
      print "Ahh canto male pedict\n";
      exit;
    }
    $products_data[]=array(
			   'product_id'=>$product->id
			   ,'qty'=>$transaction['order']
			   ,'gross_amount'=>$transaction['order']*$transaction['price']
			   ,'discount_amount'=>$transaction['order']*$transaction['price']*$transaction['discount']
	 $products_invoice[]=array(
			   'product_id'=>$product->id
			   ,'invoice qty'=>$transaction['order']-$transaction['reorder']
			   ,'gross amount'=>($transaction['order']-$transaction['reorder'])*$transaction['price']
			   ,'discount amount'=>($transaction['order']-$transaction['reorder'])  *$transaction['price']*$transaction['discount']
			   );		   );



  }


  $data['type']='direct_data_injection';
  $data['products']=$products_data;
  $data['cdata']=$customer_data;

  //Tipo order
  // 1 DELIVERY NOTE
  // 2 INVOICE
  // 3 CANCEL
  // 4 SAMPLE
  // 5 donation
  // 6 REPLACEMENT
  // 7 MISSING
  // 8 follow
  // 9 refund
  // 10 credit
  // 11 quote

  if($tipo_order<=5){
    //print_r($data);
    
    $order= new Order('new',$data);
    if($tipo_order==2){
      $order->add_invoice_transactions($data_invoice);
    }
      
  }

  
  //  print_r($data);
  //print "\n$tipo_order\n";
  
  }
 
 }

function mb_unserialize($serial_str) {
$out = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str );
return unserialize($out);
} 


?>
