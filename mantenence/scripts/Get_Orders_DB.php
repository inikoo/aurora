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
$dns_db='dw';
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







srand(12344);


$sql="select id from orders_data.order_data  ";


$res=mysql_query($sql);
while($row2=mysql_fetch_array($res, MYSQL_ASSOC)){
  print $row2['id']."\r";
  $sql="select * from orders_data.order_data where id=".$row2['id'];
  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $order_data_id=$row['id'];
    
     $header=mb_unserialize($row['header']);
     $products=mb_unserialize($row['products']);
     
     $filename_number=str_replace('.xls','',str_replace($row['directory'],'',$row['filename']));
     $map_act=$_map_act;$map=$_map;$y_map=$_y_map;
     
     // tomando en coeuntas diferencias en la posicion de los elementos
  
       if($filename_number==19015){
	 $y_map['code']=4;
     }
     

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
  
  //print_r($header_data);

  $products_data=array();
  $data_invoice_transactions=array();
  $data_dn_transactions=array();
  $data_bonus_transactions=array();


  foreach($transactions as $transaction){
    if(preg_match('/^credit|Freight|^frc-|^cxd-|^wsl$/i',$transaction['code']))
      continue;

    $__code=strtolower($transaction['code']);

    if($__code=='eo-st' or $__code=='mol-st' or  $__code=='jbb-st' or $__code=='lwheat-st' or  $__code=='jbb-st' 
       or $__code=='scrub-st' or $__code=='eye-st' or $__code=='tbm-st' or $__code=='tbc-st' or $__code=='tbs-st'
       or $__code=='gemd-st' or $__code=='cryc-st' or $__code=='gp-st'  or $__code=='dc-st'
       ){
      continue;
      
    }
    
    $transaction['description']=preg_replace('/\s*\(\s*replacements?\s*\)\s*$/i','',$transaction['description']);
    $transaction['description']=preg_replace('/\s*(\-|\/)\s*replacements?\s*$/i','',$transaction['description']);
    $transaction['description']=preg_replace('/\s*(\-|\/)\s*SHOWROOM\s*$/i','',$transaction['description']);
    $transaction['description']=preg_replace('/\s*(\-|\/)\s*to.follow\/?\s*$/i','',$transaction['description']);
    $transaction['description']=preg_replace('/\s*(\-|\/)\s*missing\s*$/i','',$transaction['description']);
    $transaction['description']=preg_replace('/\/missed off prev.order$/i','',$transaction['description']);
    $transaction['description']=preg_replace('/\(missed off on last order\)$/i','',$transaction['description']);
    $transaction['description']=preg_replace('/\/from prev order$/i','',$transaction['description']);
    $transaction['description']=preg_replace('/\s*\(owed from prev order\)$/i','',$transaction['description']);
    $transaction['description']=preg_replace('/\s*\/prev order$/i','',$transaction['description']);
    $transaction['description']=preg_replace('/\s*\-from prev order$/i','',$transaction['description']);
    $transaction['description']=preg_replace('/TO FOLLOW$/','',$transaction['description']);



    if(preg_match('/^bot-01$/i',$transaction['code'])   and preg_match('/10ml Amber Bottles.*tamper.*ap/i',$transaction['description']))
      $transaction['description']='10ml Amber Bottles & Tamper Proof Caps';
    if(preg_match('/^bot-01$/i',$transaction['code'])   and preg_match('/10ml Amber Bottles.*only/i',$transaction['description']))
      $transaction['description']='10ml Amber Bottles Only';
    if(preg_match('/^bot-01$/i',$transaction['code'])   and preg_match('/10ml Amber Bottles.already supplied/i',$transaction['description']))
      $transaction['description']='10ml Amber Bottles';
    if(preg_match('/^bag-07$/i',$transaction['code'])   and preg_match('/^Mini Bag.*mix|^Mixed Mini Bag$/i',$transaction['description']))
      $transaction['description']='Mini Bag - Mix';
    if(preg_match('/^bag-07$/i',$transaction['code'])   and preg_match('/Mini Bag .replacement/i',$transaction['description']))
      $transaction['description']='Mini Bag';
    if(preg_match('/^bag-07$/i',$transaction['code'])   and preg_match('/Mini Organza Bags Mixed|Organza Mini Bag . Mix/i',$transaction['description']))
      $transaction['description']='Organza Mini Bag - Mixed';
    if(preg_match('/^bag-02$/i',$transaction['code']))
      $transaction['description']='Organza Bags';
    if(preg_match('/^bag-02a$/i',$transaction['code'])   and preg_match('/gold/i',$transaction['description']))
      $transaction['description']='Organza Bag - Gold';
    if(preg_match('/^bag-02a$/i',$transaction['code'])   and preg_match('/misc|mix|showroom/i',$transaction['description']))
      $transaction['description']='Organza Bag - Mix';
    if(preg_match('/^bag-07a$/i',$transaction['code'])   and preg_match('/misc|mix|showroom/i',$transaction['description']))
      $transaction['description']='Organza Mini Bag - Mix';
    if(preg_match('/^bag$/i',$transaction['code']) )
      $transaction['description']='Organza Bag - Mix';
     if(preg_match('/^eid-04$/i',$transaction['code']) )
      $transaction['description']='Nag Champa 15g';
     if(preg_match('/^ish-13$/i',$transaction['code'])   and preg_match('/Smoke Boxes Natural/i',$transaction['description'])   )
       $transaction['description']='Smoke Boxes Natural';
     if(preg_match('/^asoap-09$/i',$transaction['code'])   and preg_match('/maychang-orange tint old showrooms/i',$transaction['description'])   )
       $transaction['description']='May Chang - Orange -EO Soap Loaf';
     if(preg_match('/^asoap-02$/i',$transaction['code'])   and preg_match('/old showrooms/i',$transaction['description'])   )
       $transaction['description']='Tea Tree - Green -EO Soap Loaf';

     if(preg_match('/^wsl-1039$/i',$transaction['code'])     )
       $transaction['description']='Arty Coffee Twist Candle 24cm';
     if(preg_match('/^joie-01$/i',$transaction['code'])   and preg_match('/assorted/i',$transaction['description'])    )
       $transaction['description']='Joie Boxed - Assorted';
     if(preg_match('/^wsl-01$/i',$transaction['code'])   and preg_match('/Mixed packs of Incense.Shipp.cost covered./i',$transaction['description'])    )
    $transaction['description']='Mixed packs of Incense';
  if(preg_match('/^gp-01$/i',$transaction['code'])   and preg_match('/^(Glass Pebbles Assorted|Glass Pebbles mixed colours|Glass Pebbles-Mixed)$/i',$transaction['description'])    )
    $transaction['description']='Glass Pebbles mixed colours';

 if(preg_match('/^HemM-01$/i',$transaction['code'])   and preg_match('/Pair of Hermatite Magnets/i',$transaction['description'])    )
    $transaction['description']='Pair of Hematite Magnets';

 if(preg_match('/Box of 6 Nightlights -Flower Garden/i',$transaction['description'])    )
    $transaction['description']='Box of 6 Nightlights - Flower Garden';


     if(preg_match('/^FW-01$/i',$transaction['code'])  ){
       if(preg_match('/gift|alter/i',$transaction['description'])){
	 $transaction['code']='FW-04';
       }elseif(preg_match('/white/i',$transaction['description'])){
	 $transaction['code']='FW-02';
	 $transaction['description']='Promo Wine White';
       } elseif(preg_match('/rose/i',$transaction['description'])){
	 $transaction['code']='FW-03';
	 $transaction['description']='Promo Wine Rose';
       } elseif(preg_match('/red/i',$transaction['description'])){
	 $transaction['description']='Promo Wine Red';
       } elseif(preg_match('/Veuve/i',$transaction['description'])){
	 $transaction['description']='Veuve Clicquote Champagne';
       } elseif(preg_match('/champagne/i',$transaction['description'])){
	 $transaction['description']='Champagne';
       }
     }
  if(preg_match('/^FW-02$/i',$transaction['code'])  ){
       if(preg_match('/gift|alter/i',$transaction['description'])){
	 $transaction['code']='FW-04';
       }elseif(preg_match('/white/i',$transaction['description'])){
	 $transaction['code']='FW-02';
	 $transaction['description']='Promo Wine White';
       } elseif(preg_match('/rose/i',$transaction['description'])){
	 $transaction['code']='FW-03';
	 $transaction['description']='Promo Wine Rose';
       } elseif(preg_match('/red/i',$transaction['description'])){
	 $transaction['code']='FW-01';
	 $transaction['description']='Promo Wine Red';
	 
       } elseif(preg_match('/Veuve/i',$transaction['description'])){
	  $transaction['code']='FW-01';
	 $transaction['description']='Veuve Clicquote Champagne';
       } elseif(preg_match('/champagne/i',$transaction['description'])){
	 $transaction['code']='FW-01';
	 $transaction['description']='Champagne';
       }
     }
  if(preg_match('/^FW-03$/i',$transaction['code'])  ){
       if(preg_match('/gift|alter/i',$transaction['description'])){
	 $transaction['code']='FW-04';
       }elseif(preg_match('/white/i',$transaction['description'])){
	 $transaction['code']='FW-02';
	 $transaction['description']='Promo Wine White';
       } elseif(preg_match('/rose/i',$transaction['description'])){
	 $transaction['code']='FW-03';
	 $transaction['description']='Promo Wine Rose';
       } elseif(preg_match('/red/i',$transaction['description'])){
	 $transaction['code']='FW-01';
	 $transaction['description']='Promo Wine Red';
	 
       } elseif(preg_match('/Veuve/i',$transaction['description'])){
	  $transaction['code']='FW-01';
	 $transaction['description']='Veuve Clicquote Champagne';
       } elseif(preg_match('/champagne/i',$transaction['description'])){
	 $transaction['code']='FW-01';
	 $transaction['description']='Champagne';
       }
     }
    
 if(preg_match('/^FW-04$/i',$transaction['code'])  ){
       $transaction['description']=preg_replace('/^Alternative Gift\s*\/\s*/i','Alternative Gift to Wine: ',$transaction['description']);
       $transaction['description']=preg_replace('/^Gift\s*(\:|\-)\*/i','Alternative Gift to Wine: ',$transaction['description']);

       $transaction['description']=preg_replace('/^Alternative Gift\s*(\:|\-)\s*/i','Alternative Gift to Wine: ',$transaction['description']);
       $transaction['description']=preg_replace('/^Alternative Gift to Wine\s*(\-)\*/i','Alternative Gift to Wine: ',$transaction['description']);


       if(preg_match('/1.*box.*sim.*granules|\/simmering grans$|Alternative Gift to Wine.*Mixed simmering/i',$transaction['description'])){
	 $transaction['description']='Alternative Gift: 1 box of simmering granules';
       }

     }


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
    $description=_trim($transaction['description']);
    $description=str_replace("\\\"","\"",$description);
    if(preg_match('/Joie/i',$description) and preg_match('/abpx-01/i',$transaction['code']))
      $description='2 boxes joie (replacement due out of stock)';
    
    if(is_numeric($transaction['w'])){

      if($transaction['w']<0.001 and $transaction['w']>0)
	$w=0.001*$transaction['units'];
      else
	$w=sprintf("%.3f",$transaction['w']*$transaction['units']);
    }else
      $w='';
    $transaction['supplier_product_code']=_trim($transaction['supplier_product_code']);


    if($transaction['supplier_product_code']=='' or $transaction['supplier_product_code']=='0')
      $sup_prod_code='?'._trim($transaction['code']);
    else
      $sup_prod_code=$transaction['supplier_product_code'];


    if(preg_match('/GP-\d{2}/i',$transaction['code']) and $transaction['units']==1200){
      $transaction['units']=1;
      $w=6;
      $supplier_product_cost=4.4500;
      $transaction['rrp']=60;
    }	

    if($tipo_order==2)
      $date2=$date_inv;
    else
      $date2=$date_order;

    if($transaction['units']=='' OR $transaction['units']<=0)
      $transaction['units']=1;
    
    if(!is_numeric($transaction['price']) or $transaction['price']<=0){
      //       print "Price Zero ".$transaction['code']."\n";
       $transaction['price']=0;
     }

     if(!is_numeric($supplier_product_cost)  or $supplier_product_cost<=0 ){
       
       if(preg_match('/Catalogue/i',$description)){
	 $supplier_product_cost=.25;
       }elseif($transaction['price']==0){
	 $supplier_product_cost=.20;
       }else{
	 $supplier_product_cost=0.4*$transaction['price']/$transaction['units'];
	 //print_r($transaction);
	 //	 print $transaction['code']." assuming supplier cost of 40% $supplier_product_cost **\n";
       }
       
       
       
    }
    
     
     

    $product_data=array(
			'product code'=>_trim($transaction['code'])
			,'product name'=>$description
			,'product unit type'=>$unit_type
			,'product units per case'=>$transaction['units']
			,'product net weight'=>$w
			,'product gross weight'=>$w
			,'part gross weight'=>$w
			,'product rrp'=>sprintf("%.2f",$transaction['rrp']*$transaction['units'])
			,'product price'=>sprintf("%.2f",$transaction['price'])
			,'supplier code'=>_trim($transaction['supplier_code'])
			,'supplier name'=>_trim($transaction['supplier_code'])
			,'supplier product cost'=>$supplier_product_cost
			,'supplier product code'=>$sup_prod_code
			,'supplier product name'=>$description
			,'auto_add'=>true
			,'date'=>$date_order
			,'date2'=>$date2
			);
   
    // print_r($product_data);
    $product=new Product('code-name-units-price',$product_data);
    //     "Ahh canto male pedict\n";
    if(!$product->id){
      print_r($product_data);
      print "Ahh canto male pedict\n";
      exit;
    }
    $products_data[]=array(
			   'product_id'=>$product->id
			   ,'Estimated Weight'=>$product->data['Product Gross Weight']*$transaction['order']
			   ,'qty'=>$transaction['order']
			   ,'gross_amount'=>$transaction['order']*$transaction['price']
			   ,'discount_amount'=>$transaction['order']*$transaction['price']*$transaction['discount']
			   );
    $data_invoice_transactions[]=array(
				       'product_id'=>$product->id
				       ,'invoice qty'=>$transaction['order']-$transaction['reorder']
				       ,'gross amount'=>($transaction['order']-$transaction['reorder'])*$transaction['price']
				       ,'discount amount'=>($transaction['order']-$transaction['reorder'])  *$transaction['price']*$transaction['discount']
				       ,'current payment state'=>'Paid'



				       );		   
    
    
    $data_dn_transactions[]=array(
				  'product_id'=>$product->id
				  ,'Estimated Weight'=>$product->data['Product Gross Weight']*($transaction['order']-$transaction['reorder'])
				  ,'Product ID'=>$product->data['Product ID']
				  ,'Delivery Note Quantity'=>$transaction['order']-$transaction['reorder']
				  ,'Current Autorized to Sell Quantity'=>$transaction['order']
				  ,'Shipped Quantity'=>$transaction['order']-$transaction['reorder']
				  ,'No Shipped Due Out of Stock'=>$transaction['reorder']
				  ,'No Shipped Due No Authorized'=>0
				  ,'No Shipped Due Not Found'=>0
				  ,'No Shipped Due Other'=>0
				  ,'amount in'=>(($transaction['order']-$transaction['reorder'])*$transaction['price'])*(1-$transaction['discount'])
				  ,'given'=>0
				  ,'required'=>$transaction['order']
				  );		   

    if($transaction['bonus']>0){
  $products_data[]=array(
			   'product_id'=>$product->id
			   ,'qty'=>0
			   ,'gross_amount'=>0
			   ,'discount_amount'=>0
			   ,'Estimated Weight'=>0
			   );
       $data_invoice_transactions[]=array(
				       'product_id'=>$product->id
				       ,'invoice qty'=>$transaction['bonus']
				       ,'gross amount'=>($transaction['bonus'])*$transaction['price']
				       ,'discount amount'=>($transaction['bonus'])*$transaction['price']
				       ,'current payment state'=>'No Applicable'
				       );		   
    
    
    $data_dn_transactions[]=array(
				  'product_id'=>$product->id
				   ,'Product ID'=>$product->data['Product ID']
				  ,'Delivery Note Quantity'=>$transaction['bonus']
				  ,'Current Autorized to Sell Quantity'=>$transaction['bonus']
				  ,'Shipped Quantity'=>$transaction['bonus']
				  ,'No Shipped Due Out of Stock'=>0
				  ,'No Shipped Due No Authorized'=>0
				  ,'No Shipped Due Not Found'=>0
				  ,'No Shipped Due Other'=>0
				  ,'Estimated Weight'=>$product->data['Product Gross Weight']*($transaction['bonus'])
				  ,'amount in'=>0
				  ,'given'=>$transaction['bonus']
				  ,'required'=>0
				  );		   


      
    $sql="update orders_data.order_data set last_transcribed='".date('Y-m-d H:i:s')."' where id=".$order_data_id;
    if(!mysql_query($sql))
      exit(" $sql  error uopdatin data date las trancibes");
    }

  }


  $data['type']='direct_data_injection';
  $data['products']=$products_data;
  $data['cdata']=$customer_data;
  $data['metadata_id']=$order_data_id;

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

      $payment_method=parse_payment_method($header_data['pay_method']);

      $data_invoice=array(
			  'Invoice Date'=>$date_inv
			  ,'Invoice Public ID'=>$header_data['order_num']
			  ,'Invoice File As'=>$header_data['order_num']
			  ,'Invoice Main Payment Method'=>$payment_method
			  ,'Invoice Multiple Payment Methods'=>0
			  ,'Invoice Gross Shipping Amount'=>$header_data['shipping']
			  ,'Invoice Gross Charges Amount'=>$header_data['charges']
			  );
       $data_dn=array(
			  'Delivery Note Date'=>$date_inv
			  ,'Delivery Note ID'=>$header_data['order_num']
			  ,'Delivery Note File As'=>$header_data['order_num']
			  
			  );
       $order->create_dn_simple($data_dn,$data_dn_transactions);
       $order->create_invoice_simple($data_invoice,$data_invoice_transactions);
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



function parse_payment_method($method){


  $method=_trim($method);
  //  print "$method\n";
  if($method=='' or $method=='0')
    return 0;
  if(preg_match('/^(Card Credit|credit  card|Debit card|Crredit Card|Credit Card|Solo|Cr Card|Switch|visa|electron|mastercard|card|credit Card0|Visa Electron|Credi Card|Credit crad)$/i',$method))
    return 'Credit Card';

  //  print "$method\n";
  if(preg_match('/^(Cheque receiv.|APC|\*Cheque on Delivery\s*|Cheque|APC to Collect|chq|PD CHQ|APC collect CHQ|APC to coll CHQ|APC collect cheque)$/i',$method))
    return 'Check';
  if(preg_match('/^(Account|7 Day A.C|Pay into a.c|pay into account)$/i',$method))
    return 'Other';
  if(preg_match('/^(cash|casg|casn)$/i',$method))
    return 'Cash';
  if(preg_match('/^(Paypal|paypall|pay pal)$/i',$method))
    return 'Paypal';
  if(preg_match('/^(bacs|Bank Transfer|Bank Transfert|Direct Bank)$/i',$method))
    return 'Bank Transfer';
  if(preg_match('/^(draft|bank draft|bankers draft)$/i',$method))
    return 'Other';
  if(preg_match('/^(postal order)$/i',$method))
    return 'Other';
  if(preg_match('/^(Moneybookers)$/i',$method))
    return 'Other';


  return 'Unknown';

}



?>
