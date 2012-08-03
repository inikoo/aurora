<?php
//date_default_timezone_set('UTC');


function make_seed()
{
  list($usec, $sec) = explode(' ', microtime());
  return (float) $sec + ((float) $usec * 100000);
}
mt_srand(make_seed());
$randval = mt_rand();


$since='2004-06-14';

//require_once '/var/www/aw/stock_functions.php';     


$home_country_id=30;

$number_of_digits=5;

//require_once '/home/raul/www/inikoo/dns/dns.php';         // DB connecton configuration file



$tmp_directory='/tmp/';
$out_cvs='/tmp/t3tmp.csv';
$xls_dir="/data/old_orders_w/";
//$xls_dir="/data/ordersy/";
//$xls_dir="tmp/";






$tax_rate=0.175;




$number_header_rows=22;


$_y_map['code']=5;
$_y_map['description']=8;
$_y_map['price']=9;
$_y_map['order']=10;
$_y_map['reorder']=11;
$_y_map['bonus']=13;
$_y_map['credit']=16;
$_y_map['rrp']=18;
$_y_map['discount']=20;
$_y_map['units']=7;
$_y_map['supplier_code']=23;
$_y_map['supplier_product_code']=22;
$_y_map['supplier_product_cost']=27;
$_y_map['w']=30;

$_y_map_old['code']=3;
$_y_map_old['description']=5;
$_y_map_old['price']=6;
$_y_map_old['order']=7;
$_y_map_old['reorder']=8;
$_y_map_old['bonus']=10;
$_y_map_old['credit']=13;
$_y_map_old['rrp']=15;
$_y_map_old['discount']=17;
$_y_map_old['units']=4;
$_y_map_old['supplier_code']=20;
$_y_map_old['supplier_product_code']=19;
$_y_map_old['supplier_product_cost']=24;
$_y_map_old['w']=26;

$_map['stipo']=array('row'=>2,'col'=>0);
$_map['ltipo']=array('row'=>2,'col'=>8);
$_map['pickedby']=array('row'=>2,'col'=>16);
$_map['parcels']=array('row'=>2,'col'=>20);
$_map['packedby']=array('row'=>3,'col'=>16);
$_map['weight']=array('row'=>3,'col'=>20);
$_map['trade_name']=array('row'=>6,'col'=>8,'tipo'=>'name');
$_map['takenby']=array('row'=>6,'col'=>9,'tipo'=>'name');
$_map['customer_num']=array('row'=>6,'col'=>10);
$_map['order_num']=array('row'=>6,'col'=>13);
$_map['date_order']=array('row'=>6,'col'=>16,'tipo'=>'date');
$_map['date_inv']=array('row'=>6,'col'=>18,'tipo'=>'date');
$_map['pay_method']=array('row'=>6+1,'col'=>2+2);
$_map['address1']=array('row'=>6+1,'col'=>6+2,'tipo'=>'name');
$_map['history']=array('row'=>7+1,'col'=>2+2);
$_map['address2']=array('row'=>7+1,'col'=>6+2,'tipo'=>'name');
$_map['notes']=array('row'=>7+1,'col'=>8+2);
$_map['total_net']=array('row'=>7+1,'col'=>18+2);
$_map['gold']=array('row'=>8+1,'col'=>2+2);
$_map['address3']=array('row'=>8+1,'col'=>6+2,'tipo'=>'name');
$_map['charges']=array('row'=>8+1,'col'=>14+2);
$_map['tax1']=array('row'=>8+1,'col'=>18+2);
$_map['city']=array('row'=>9+1,'col'=>6+2,'tipo'=>'name');
$_map['total_topay']=array('row'=>11,'col'=>18+2);
$_map['tax2']=array('row'=>9+1,'col'=>18+2);
$_map['postcode']=array('row'=>10+1,'col'=>6+2);
$_map['notes2']=array('row'=>10+1,'col'=>8+2);
$_map['shipping']=array('row'=>11+1,'col'=>14+2);
$_map['customer_contact']=array('row'=>13+1,'col'=>6+2,'tipo'=>'name');
$_map['phone']=array('row'=>14+1,'col'=>6+2,'tipo'=>'string');
$_map['total_order']=array('row'=>14+1,'col'=>$_y_map['order']);
$_map['total_reorder']=array('row'=>14+1,'col'=>$_y_map['reorder']);
$_map['total_bonus']=array('row'=>14+1,'col'=>$_y_map['bonus']);
$_map['total_items_charge_value']=array('row'=>14+1,'col'=>14+2);
$_map['total_rrp']=array('row'=>14+1,'col'=>16+2);
$_map['feedback']=array('row'=>1,'col'=>20+2);
$_map['source_tipo']=false;
$_map['extra_id1']=false;
$_map['extra_id2']=false;


/* $_map_old['ltipo']=array('row'=>2,'col'=>5); */
/* $_map_old['trade_name']=array('row'=>5,'col'=>5,'tipo'=>'name'); */
/* $_map_old['takenby']=array('row'=>5,'col'=>6); */
/* $_map_old['customer_num']=array('row'=>5,'col'=>7); */
/* $_map_old['order_num']=array('row'=>5,'col'=>10); */
/* $_map_old['date_order']=array('row'=>5,'col'=>13,'tipo'=>'date'); */
/* $_map_old['date_inv']=array('row'=>5,'col'=>15,'tipo'=>'date'); */
/* $_map_old['address1']=array('row'=>6,'col'=>5,'tipo'=>'name'); */

/* $_map_old['history']=array('row'=>7,'col'=>2); */
/* $_map_old['address2']=array('row'=>7,'col'=>5,'tipo'=>'name'); */
/* $_map_old['notes']=array('row'=>7,'col'=>7); */
/* $_map_old['total_net']=array('row'=>7,'col'=>17); */

/* $_map_old['gold']=array('row'=>8,'col'=>2); */
/* $_map_old['address3']=array('row'=>8,'col'=>5,'tipo'=>'name'); */
/* $_map_old['charges']=array('row'=>8,'col'=>13); */
/* $_map_old['tax1']=array('row'=>8,'col'=>17); */

/* $_map_old['city']=array('row'=>9,'col'=>5,'tipo'=>'name'); */
/* $_map_old['total_topay']=array('row'=>9,'col'=>17); */
/* $_map_old['tax2']=false; */
/* $_map_old['postcode']=array('row'=>10,'col'=>5); */
/* $_map_old['notes2']=array('row'=>10,'col'=>7); */


/* $_map_old['shipping']=array('row'=>11,'col'=>13); */

/* $_map_old['customer_contact']=array('row'=>13,'col'=>5,'tipo'=>'name'); */
/* $_map_old['phone']=array('row'=>14,'col'=>5,'tipo'=>'string'); */



/* $_map_old['total_order']=array('row'=>14,'col'=>$_y_map_old['order']); */
/* $_map_old['total_reorder']=array('row'=>14,'col'=>$_y_map_old['reorder']); */
/* $_map_old['total_bonus']=array('row'=>14,'col'=>$_y_map_old['bonus']); */
/* $_map_old['total_items_charge_value']=array('row'=>14,'col'=>13); */
/* $_map_old['total_rrp']=array('row'=>14,'col'=>15); */


$_map_act['name']=2;
$_map_act['contact']=3;
$_map_act['first_name']=17;
$_map_act['a1']=4;
$_map_act['a2']=5;
$_map_act['a3']=6;
$_map_act['town']=7;
$_map_act['country_d2']=8;
$_map_act['postcode']=9;
$_map_act['country']=10;
$_map_act['tel']=12;
$_map_act['fax']=13;
$_map_act['mob']=15;
$_map_act['source']=25;
$_map_act['act']=38;
$_map_act['tax_number']=87;


$_tipo_order=array('Unknown','Pro-invoice','Invoice','Canceled','Sample','Donation,','Replacements','Shortages','To Follow','Refund','Credit Note','Canceled After');

function get_tipo_order($ltipo,$header){
 

  $parent_id='';
  $tipo=0;
if(preg_match('/^devoluci|^refund|VAT REFUND|abono/i',$ltipo)){
    $tipo=9;
 }elseif(preg_match('/^quote|Pro-forma|proforma/i',$ltipo)){
    $tipo=11;
  }elseif(preg_match('/DELIVERY NOTE|nota de envio|proforma invoice/i',$ltipo)){
    $tipo=1;
  }elseif(preg_match('/$INVOICE. sample order|invoice|facutura|factura$/i',_trim($ltipo))){
    $tipo=2;
  }elseif(preg_match('/^CANCEL|Pedidos Cancelados/i',$ltipo)){
    $tipo=3;
  }elseif(preg_match('/^SAMPLE|muestras/i',$ltipo)){
    $tipo=4;
    }elseif(preg_match('/donation/i',$ltipo)){
    $tipo=5; 
  }elseif(preg_match('/^\s*REPLACEMENT|Replcement|^reemplazo por roturas|Abono Por Roturas|^replacement|Damaged item|REPLACMENT|DELIVERY COLLECTION|repplacements|repalcements|Reemplazo de Merca/i',$ltipo)){
    $tipo=6;
}elseif(preg_match('/Damaged Parcel|shotages|MISSING|Missing Parcel|missing\s+\d|^reemplazo por falta|SHORTAHGE|shortages|Missing From Order|missing form order|Mising from|^Missing Item|Missing - Replacement|^Shortage|Lost Parcel/i',$ltipo)){
    $tipo=7;
  }elseif(preg_match('/^to follow|Follow.On Order/i',$ltipo)){
    $tipo=8;


  }elseif(preg_match('/credit|credit note/i',$ltipo)){
    $tipo=10;
  }elseif(preg_match('/^quote|Pro-forma/i',$ltipo)){
    $tipo=11;
}elseif(preg_match('/^return to supplier/i',$ltipo)){
        $tipo=12;
    



  }else{ 
    print "--->".$ltipo."<----\n";
    $tipo=0;
    print("tipo not found\n");
  }
  

  
  $tmp='';

  if(preg_match('/\d{8}/i',$ltipo,$tmp)){
    $parent_id=$tmp[0];
  }
  //elseif(preg_match('/\d{4}/i',$ltipo,$tmp))
   //  $parent_id=$tmp[0];
  
  
  //print "****** $ltipo *** $tmp ***\n";
  
  if($header['total_topay']==0){
    if(preg_match('/Reemplazo|Repalacements|Replcement|^reemplazo por roturas|^replacement|REPLACMENT|DELIVERY COLLECTION|repplacements|repalcements/i',$header['notes2'])){
      
      $tipo=6;
    }
    if(preg_match('/MISSING|Missing Parcel|missing\s+\d|^reemplazo por falta|shortages|Missing From Order|missing form order|Mising from|^Missing Item|Missing - Replacement|^Shortage|Lost Parcel/i',$header['notes2'])){
      $tipo=7;
    }
      
    }

  return array($tipo,$parent_id);

}

function get_customer_groups($address_data,$header_data,$act_data){

  $country_id=$address_data['country_id'];
  $_home=array(30);
  $_extended_home=array(30,241,242,240);
  $_region=array(30,241,242,240,75);
  $_eu=array(80,21,33,208,108,201,228,193,165,177,104,216,75,78,110,116,117,126,2,162,160,169,188,189,47,171,30);
  
  $groups=array();
  
  if (isset($_home[$country_id]))
    $groups[]=1;
  else
    $groups[]=4;
  
  if (isset($_extended_home[$country_id]))
    $groups[]=2;
  
  if (isset($_region[$country_id]))
    $groups[]=3;
  else
    $groups[]=5;
  
  if (isset($_eu[$country_id]))
    $groups[]=11;
  
  // Which one has to pay taxes
  
  if(isset($_eu[$country_id])){
    // has to pay taxes
    // EU TAX HEAVENS
    if($country_id==47 and preg_match('/canarias/i',$address_data['country_d2']))
      $groups[]=8;
    else
      $groups[]=7;
    
    
  }else
    $groups[]=8;
  
  
    //Special opartner 
  
//  if(preg_match('/costa imports/i',$act_data['name']))
//      $groups[]=6;
    
  // Show rom
    
  if(preg_match('/to be collected/i',$header_data['notes']) or preg_match('/showroom/i',$header_data['notes']) or  preg_match('/showroom/i',$header_data['notes2'])   )
      $groups[]=10;
  //  print_r($header_data);
  $groups=array_unique($groups);
  // print_r($groups);
  return $groups;
  
}


?>