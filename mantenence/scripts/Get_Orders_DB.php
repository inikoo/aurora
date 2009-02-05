<?
include_once('../../app_files/db/dns.php');
include_once('../../classes/Department.php');
include_once('../../classes/Family.php');
include_once('../../classes/Product.php');
include_once('../../classes/Supplier.php');
include_once('../../classes/Order.php');
error_reporting(E_ALL);

require_once 'MDB2.php';            // PEAR Database Abstraction Layer
require_once '../../common_functions.php';
$db =& MDB2::factory($dsn);       
if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}

$db->setFetchMode(MDB2_FETCHMODE_ASSOC);  
$db->query("SET time_zone ='UTC'");
$db->query("SET NAMES 'utf8'");
$PEAR_Error_skiptrace = &PEAR::getStaticProperty('PEAR_Error','skiptrace');$PEAR_Error_skiptrace = true;// Fix memory leak
require_once '../../myconf/conf.php';           
date_default_timezone_set('Europe/London');

include_once('local_map.php');

include_once('map_order_functions.php');

$software='Get_Orders_DB.php';
$version='V 1.0';

$Data_Audit_ETL_Software="$software $version";


$sql="select id from orders_data.order_data ";
$res = $db->query($sql);
while($row2=$res->fetchRow()) {
  
  //  print $row2['id']."\r";
  $sql="select * from orders_data.order_data where id=".$row2['id'];
  $res2 = $db->query($sql);
  if($row=$res2->fetchRow()) {
  
    
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
  $data['order customer message']=$header_data['notes2'];
  $data['order original data mime type']='application/vnd.ms-excel';
  $data['order original data']=$row['filename'];
  $data['order original data source']='DB:orders_data.order.data';
  $data['order original metadata']=$row['id'];
  
  //  print_r($products);
  $product_data=array();
  foreach($transactions as $transaction){
    //    print_r($transaction);

    if(preg_match('/^credit/i',$transaction['code']))
      continue;
      
    


    if($transaction['units']=='')
      $transaction['units']=1;
    if($transaction['supplier_product_code']=='')
      $transaction['supplier_product_code']='?'.$transaction['code'];
    

    

    if(preg_match('/^SG\-/i',$transaction['code']))
     $transaction['supplier_code'] ='AW';
    if($transaction['supplier_code']=='AW')
      $transaction['supplier_product_code']=$transaction['code'];


    if($transaction['supplier_code']=='')
      $transaction['supplier_code']='Unknown';

    $unit_type='Piece';

    $description=_trim($transaction['description']);
     $description=str_replace("\\\"","\"",$description);


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
			
			,'supplier product cost'=>sprintf("%.4f",$transaction['supplier_product_cost'])
			,'supplier product code'=>_trim($transaction['supplier_product_code'])
			,'supplier product name'=>$description
			
			,'auto_add'=>true
			,'date'=>$date_order
			);
    //   print_r($product_data);

 //    if(preg_match('/ish-33/i',$transaction['code'])){
//       print $row2['id']."\n";
//       print_r($product_data);
//     }
    

    $product=new Product('code-name-units-price',$product_data);
    //     "Ahh canto male pedict\n";
    if(!$product->id){
      
      print_r($product_data);
      print "Ahh canto male pedict\n";
      exit;
    
    }
 
    


  }

  //print_r($product_data);

  }
 
 }

function mb_unserialize($serial_str) {
$out = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", $serial_str );
return unserialize($out);
} 


?>
