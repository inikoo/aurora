<?
include_once('../../app_files/db/dns.php');
include_once('../../classes/Department.php');
include_once('../../classes/Family.php');
include_once('../../classes/Product.php');
include_once('../../classes/Supplier.php');
include_once('../../classes/Order.php');


require_once 'MDB2.php';            // PEAR Database Abstraction Layer
require_once '../../common_functions.php';
$db =& MDB2::factory($dsn);       
if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}
if(DEBUG)PEAR::setErrorHandling(PEAR_ERROR_RETURN);
$db->setFetchMode(MDB2_FETCHMODE_ASSOC);  
$db->query("SET time_zone ='UTC'");
$db->query("SET NAMES 'utf8'");
$PEAR_Error_skiptrace = &PEAR::getStaticProperty('PEAR_Error','skiptrace');$PEAR_Error_skiptrace = true;// Fix memory leak
require_once '../../myconf/conf.php';           
date_default_timezone_set('Europe/London');





$software='Get_Orders_Internet.php';
$version='V 1.0';

$Data_Audit_ETL_Software="$software $version";



$sql="select *,shipping.value as shipping,orden.id as orden__id  from aw.orden left join aw.orden_file on (aw.orden.id=aw.orden_file.order_id) left join aw.shipping on (aw.orden.id=aw.shipping.order_id) order by date_creation ";

$res = $db->query($sql);
while($row=$res->fetchRow()) {



  $_tel=preg_split('/ /',$row['tel']);
  $email=$_tel[count($_tel)-1];
  if(preg_match('/@/i',$email)){
    
    $tel=_trim(preg_replace('/'.$email.'/','',$row['tel']));
    $email=_trim($email);
  }else{
    $email='';
    $tel=$row['tel'];
  }
  

  
  
    $type='Unknown';
    $contact_name=$row['contact_name'];
    $company_name=$row['customer_name'];
  
    if($contact_name!=$company_name)
      $type='Company';
    else
      $type='Person';


    

    $cdata['contact_name']=$contact_name;
    $cdata['type']=$type;
    $cdata['email']=$email;
    $cdata['telephone']=$tel;
    $cdata['company_name']=$company_name;
    


    $address1=preg_split('/\<br\/\>/',$row['address_bill']);
    
    $address_lines=count($address1);
    $country=array_pop($address1);
    
    $address='';
    $city='';
    $postcode='';
    $country_d1='';
    

    if(preg_match('/\d/',$address1[count($address1)-1])){
	$postcode=array_pop($address1);
      }else{
	$country_d1=array_pop($address1);
	$postcode=array_pop($address1);
      }
      $city=array_pop($address1);
      $address=join(' ',$address1);
      

	$cdata['address_data']=array(
				     'type'=>'3line'
				     ,'name'=>$contact_name
				     ,'company'=>$company_name
				     ,'telephone'=>$tel
				     ,'address1'=>$address
				     ,'address2'=>''
				     ,'address3'=>''
				     ,'town'=>$city
				     ,'country'=>$country
				     ,'country_d1'=>$country_d1
				     ,'country_d2'=>''
				     ,'default_country_id'=>$myconf['country_id']
				     ,'postcode'=>$postcode
				     
				     );

	$address1=preg_split('/\<br\/\>/',$row['address_del']);
	$address_lines=count($address1);
	$country=array_pop($address1);
	$address='';
	$city='';
	$postcode='';
	$country_d1='';
	if(preg_match('/\d/',$address1[count($address1)-1])){
	  $postcode=array_pop($address1);
	}else{
	  $country_d1=array_pop($address1);
	  $postcode=array_pop($address1);
	}
	$city=array_pop($address1);
	$address=join(' ',$address1);
	$cdata['has_shipping']=true;
	$cdata['shipping_data']=array(
				     'type'=>'3line'
				     ,'name'=>$contact_name
				     ,'company'=>$company_name
				     ,'telephone'=>$tel
				     ,'address1'=>$address
				     ,'address2'=>''
				     ,'address3'=>''
				     ,'town'=>$city
				     ,'country'=>$country
				     ,'country_d1'=>$country_d1
				     ,'country_d2'=>''
				     ,'default_country_id'=>$myconf['country_id']
				     ,'postcode'=>$postcode
				     
				     );
	



	
  $products=array();
  $sql="select * from aw.transaction  left join aw.product on (product_id=aw.product.id)where order_id=".$row['orden__id'];
  $res2 = $db->query($sql);
  while($row2=$res2->fetchRow()) {
    $code=$row2['code'];
    $product=new product('code',$code);
    if(!$product->id){
      //print "no code $code\n";
      //exit;
      continue;
    }
      

    $products[]=array(
		   'code'=>'',
		   'amount'=>'',
		   'case_price'=>'',
		   'product_id'=>$product->id,
		   'family_id'=>'',
		   'qty'=>$row2['ordered']

		   );

  }

  $data=array(
	      'type'=>'direct_data_injection',
	      'product code exceptions'=>array(),
	      'product code replacements'=>array(),
	      'date'=>date("Y-m-d H:i:s",strtotime($row['date_creation'])),
	      'order_id'=>$row['public_id'],
	      'message'=>$row['message_their'],
	      'original_data_type'=>'file',
	      'order original data'=>$row['filename'],
	      'store code'=>'',
	      'subtotal'=>$row['net'],
	      'shipping'=>$row['shipping'],
	      'discount'=>0,
	      'voucher'=>0,
	      'tax'=>$row['tax'],
	      'products'=>$products,
	      'cdata'=>$cdata
	      );
  
  //print_r($data);
$order= new Order('new',$data);

 }
 


//$order= new Order('new',$data);





?>
