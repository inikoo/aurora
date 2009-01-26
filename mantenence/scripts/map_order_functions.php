<?






function insert_orden_files($order_id,$filename,$checksum,$checksum_header,$checksum_products,$file_date){
  $db =& MDB2::singleton();




  $sql=sprintf("insert into orden_file (order_id,filename,checksum,checksum_header,checksum_products,date) values (%d,'%s','%s','%s','%s','%s')",$order_id,$filename,$checksum,$checksum_header,$checksum_products,date("Y-m-d H:i:s",strtotime('@'.$file_date)));
  // print "$sql\n";

  // $db->exec($sql);
  mysql_query($sql);
}

function update_orden_files($order_id,$filename,$checksum,$checksum_header,$checksum_products,$file_date){
  $db =& MDB2::singleton();
  $sql=sprintf("update orden_file set order_id=%d ,checksum='%s',checksum_header='%s',checksum_products='%s',date='%s' where filename=%s",$order_id,$checksum,$checksum_header,$checksum_products,date("Y-m-d H:i:s",strtotime('@'.$file_date)),prepare_mysql($filename));
  //    print "$sql\n";

  // $db->exec($sql);
  mysql_query($sql);
}


function create_orden($customer_id,
		      $header,
		      $act,
		      $date_index,
		      $date_order,
		      $date_inv,
		      $tipo,
		      $address_del_id='',
		      $address_bill_id='',
		      $new_customer,
		      $is_island,$parent_order_id
		      ,$partner=0
		      ,$co=''
		      ){
  



  $db =& MDB2::singleton();
  global $tax_rate,$home_country_id;
  $tax_factor=$tax_rate;
  $total_tax=$header['tax1']+$header['tax2'];
  $total_net=$header['total_net'];
  $total=$header['total_topay'];
  if($total!=0 and $total_tax==0){
    $tax_factor=0;
  }

  $ajuste_in_net=$total_net-$header['total_items_charge_value']-$header['charges']-$header['shipping'];
  $ajuste_in_tax=$total_tax-($total_net*$tax_factor);
  $ajuste_in_total=$total-$total_net-$total_tax;

  //  $balance=$total-(($total_net+$balance_net)*(1+$tax_factor));
  
  //   $balance_total=number_format($balance_net*(1+$tax_factor)+$balance,2);
  //   $balance_net=number_format($balance_net,2);
  //   $balance=number_format($balance,2);
			 
  





  if($new_customer){
    

    
    

    if($is_island){
      $sql="insert into nodata_island (customer_id,history,history2) values ($customer_id,".$header['history'].",".$header['history'].")";
      // print "$sql\n";
      mysql_query($sql);
    }
      

    $number_of_orders_old=$header['history'];
    if(!is_numeric($number_of_orders_old))
      $number_of_orders_old=0;

    if($number_of_orders_old>1){
      $number_orders_no_data=$number_of_orders_old-1;
      $sql="update customer set num_orders_nd=$number_orders_no_data where id=$customer_id";
      mysql_query($sql);
      //      $_date_index=date('Y-m-d H:i:s',  strtotime(date('U',strtotime($date_index))-1)  );
      $_date_index=date('Y-m-d H:i:s',    strtotime(str_replace("'",'',$date_index))-1);
      
      if($number_orders_no_data==1)
	$sql="insert into note (texto,op,op_id,date_index,code) values ('There is one previous order for which no details are available','Customer',$customer_id,'$_date_index',1)";
      else
	$sql="insert into note (texto,op,op_id,date_index,code) values ('There are $number_orders_no_data previous orders for which no details are available','Customer',$customer_id,'$_date_index',1)";
      mysql_query($sql);
      $sql="insert into history (tipo,sujeto,sujeto_id,objeto,objeto_id,date) values ('ISL','Customer',$customer_id,'End',NULL,$date_index)";
      mysql_query($sql);
      $history_id=mysql_insert_id();
      //print "$sql\n";      
      $sql="insert into history_item (history_id,columna,old_value,new_value) values ($history_id,'Orders',0,$number_orders_no_data)";
      mysql_query($sql);

      //    print "$sql\n";
      //exit;
    }


  }else{
    


    if($is_island){
      $sql="update nodata_island set history=".$header['history']."  where customer_id=$customer_id";
      //	print "$sql\n";
      mysql_query($sql);
    }else{
      // find is the customer was in a island
      $sql=sprintf("select history,id  from nodata_island  where done=0 and customer_id=%d",$customer_id);

      $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
      if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if(($diff_history=$header['history']-$row['history']-1)>0){
	  // update nodata orders and set a note ---------------------
	  $customer_data=get_customer_data($customer_id);
	    
	  $number_orders_no_data=$diff_history+$customer_data['num_orders_nd'];
	  $sql="update customer set num_orders_nd=$number_orders_no_data where id=$customer_id";
	  //  print "$diff_history $number_orders_no_data     $sql";
	  mysql_query($sql);
	  $sql="update nodata_island set history='".$header['history']."',done=1  where customer_id=$customer_id";
	  //	print "$sql\n";
	  mysql_query($sql);

	  //      $_date_index=date('Y-m-d H:i:s',  strtotime(date('U',strtotime($date_index))-1)  );
	  $_date_index=date('Y-m-d H:i:s',    strtotime(str_replace("'",'',$date_index))-1);
	    
	  if($diff_history==1)
	    $sql="insert into note (texto,op,op_id,date_index,code) values ('It\'s one order for which no details are available in this period','Customer',$customer_id,'$_date_index',2)";
	  else
	    $sql="insert into note (texto,op,op_id,date_index,code) values ('There are $diff_history orders for which no details are available in this period','Customer',$customer_id,'$_date_index',2)";
	  mysql_query($sql);
	  // print "$sql\n";
	  $sql="insert into history (tipo,sujeto,sujeto_id,objeto,objeto_id,date) values ('ISL','Customer',$customer_id,'End',NULL,$date_index)";
	  mysql_query($sql);
	  $history_id=mysql_insert_id();

	  $sql="insert into history_item (history_id,columna,old_value,new_value) values ($history_id,'Orders',0,$diff_history)";
	  mysql_query($sql);

	  //---------------------------------------------------------


	}
      }      

    }


    $customer_data=get_customer_data($customer_id);

    $number_of_orders_old=$customer_data['num_invoices']+ $customer_data['num_pro_invoices']+ $customer_data['num_cancels']+$customer_data['num_orders_nd'];
    if($number_of_orders_old==0){
      $number_of_orders_old=$header['history'];
      
      $number_of_orders_old=$number_of_orders_old-$customer_data['num_orders'];
      if($number_of_orders_old<0)
	$number_of_orders_old=0;
      
    }


  }

  if($tipo==1 or $tipo==2 or $tipo==3)
    $number_of_orders=$number_of_orders_old+1;
  else
    $number_of_orders=$number_of_orders_old;



  //  print_r($header);
  //source tipo - can be i(internet),t(telephone),f(fax),p(post),s(showroom),a(staffsales),u(unknown)
  if($header['source_tipo']=='')$header['source_tipo']='u';
  $_gold=($header['gold']=='Gold Reward'?'1':'0');





  if($header['customer_contact']=='')
    $header['customer_contact']=$header['trade_name'];

  if($header['trade_name']=='')
    $header['trade_name']=$header['customer_contact'];

  // print_r($header);

  $payment_method=get_payment_method($header['pay_method']);
  //print "$payment_method\n";
  //exit;

  $del_country_id=country_id($address_del_id,$home_country_id);


  $sql=sprintf("insert into orden (fao,feedback_id,source_tipo,customer_name,contact_name,customer_id2,customer_id3,tel,public_id,parcels,weight,order_hist,gold,taken_by,net
				       ,tax
				       ,total
				       ,balance_net
				       ,balance_tax
				       ,balance_total
				       ,payment_method,date_creation,date_processed,date_invoiced,titulo,customer_id,address_del,address_bill,tipo,date_index,parent_id,partner,del_country_id) values
				       (%s,%d,'%s',%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s
				       ,%.2f
				       ,%.2f
				       ,%.2f
				       ,%.2f
				       ,%.2f
				       ,%.2f
				       ,%s,%s,%s,%s,%s,%d,%s,%s,%s,%s,%s,%d,%d)
				       ",prepare_mysql($co),
	       $header['feedback'],
	       $header['source_tipo'],
	       prepare_mysql($header['trade_name']),
	       prepare_mysql($header['customer_contact']),
	       prepare_mysql($header['extra_id1']),
	       prepare_mysql($header['extra_id2']),
	       prepare_mysql($header['phone']),
	       prepare_mysql($header['order_num']),
	       prepare_mysql($header['parcels']),
	       prepare_mysql($header['weight']),
	       $number_of_orders,
	       $_gold,
	       'null',
	       $total_net,
	       $total_tax,
	       $total,
	       $ajuste_in_net,
	       $ajuste_in_tax,
	       $ajuste_in_total,
	       prepare_mysql($payment_method),
	       $date_order,
	       $date_order,
	       $date_inv,
	       prepare_mysql(mb_ucwords($header['ltipo'])),
	       $customer_id,
	       prepare_mysql(display_full_address($address_del_id)),
	       prepare_mysql(display_full_address($address_bill_id)),
	       $tipo,
	       $date_index,
	       prepare_mysql($parent_order_id),$partner,$del_country_id
	       );
  
  $sql=preg_replace('/\n/','',$sql);
  $sql=preg_replace('/\s{2,}/',' ',$sql);
    
  mysql_query($sql);
  //  exit("$sql\n");
  // $order_id = $db->lastInsertID();
  $order_id=mysql_insert_id();



  $a_taken=get_user_id($header['takenby'],$order_id,'taken');
  if(count($a_taken)==1)
    $_taken=$a_taken[0];
  else
    $_taken='null';

  $sql=sprintf("update orden set taken_by=%s where id=%d",$_taken,$order_id);
  mysql_query($sql);


  if($order_id<1 or !is_numeric($order_id))
    exit("$order_id ->   $sql\n");
  //   if($balance!=0){
  //    $sql=sprintf("insert into balance (order_id,tax_code,value) values (%d,NULL,%.2f)",$order_id,$balance);
  //    print "$sql\n";
  //   mysql_query($sql);
  //   }
  //   if($balance_net!=0){
  //    $sql=sprintf("insert into balance (order_id,tax_code,value) values (%d,'S',%.2f)",$order_id,$balance_net);
  //    print "$sql\n";
  //  mysql_query($sql);
  //  }

  if($tax_factor==0){
    $tax_id=1;
    $tax_code='NULL';
  }else{
    $tax_id=2;
    $tax_code='S';
    $sql=sprintf("insert into tax (order_id,code,value) values (%d,'S',%.2f)",$order_id,$total_tax);
    //print "$sql\n";
    mysql_query($sql);


  }
  if($header['charges']!=0){
   
    $sql=sprintf("insert into charge (tipo,order_id,tax_code,value) values (1,%d,%s,%.2f)",$order_id,prepare_mysql($tax_code),$header['charges']);
    // print $header['charges']." $sql\n";
    mysql_query($sql);
  }


  $notes=$header['notes'];

  if($notes=='0')
    $notes='';


  $notes2=$header['notes2'];
  
  if(isset($act['tax_number'])){
    $tax_number_act=get_tax_number($act['tax_number']);
  }else
    $tax_number_act=false;

  $tax_number=false;
  $country_id= get_customer_country_id($customer_id);
  // print "$country_id\n";
  if(is_numeric($country_id) and $country_id>0 and $country_id!=$home_country_id){

    $tax_number=get_tax_number($notes2);
  }

  if($tax_number){
    change_tax_number($customer_id,$tax_number,$date_index,($new_customer?false:true));
    $notes2='';
  }elseif($tax_number_act){
    change_tax_number($customer_id,$tax_number_act,$date_index,($new_customer?false:true));
    
  }




  //exit;

  if(preg_match('/showroom|staff|local|colle/i',$notes) and $header['shipping']==0){// Collected
    $tipo_deliver=1;
  }else{
    $tipo_deliver=2;

    // Try to get the delevery comapny

    $shipping_supplier_id=get_shipping_supplier($notes,$order_id);
    //print "$shipping_supplier_id\n";
    
    if(is_numeric($shipping_supplier_id) and $shipping_supplier_id>0){
      $notes='';
    }else
      $shipping_supplier_id='';
    $sql=sprintf("insert into shipping (supplier_id,order_id,value,tax_code) values (%s,%d,%.2f,%s)",prepare_mysql($shipping_supplier_id),$order_id,$header['shipping'],prepare_mysql($tax_code));
    // print "$sql\n";
    mysql_query($sql);
  }
  
  $sql=sprintf("update orden set note=%s,note2=%s,tax_code=%s where id=%d",prepare_mysql($notes),
	       prepare_mysql($notes2),prepare_mysql($tax_code),$order_id);
  mysql_query($sql);

  if($date_order!='null' or $date_order!=''){
    $tipo=2;
    $sql=sprintf("insert into orden_history (tipo,order_id,fecha) values (%d,%d,%s)",$tipo,$order_id,$date_order);
    mysql_query($sql);
  }
  if($date_inv!='null' or $date_inv!=''){
    $tipo=5;
    $sql=sprintf("insert into orden_history (tipo,order_id,fecha) values (%d,%d,%s)",$tipo,$order_id,$date_inv);
    mysql_query($sql);
  }


  if($header['tax1']>0 or $header['tax1']<0)
    $tax_code='S';
  else
    $tax_code='';




  return array($order_id,$tax_code);

}

function get_payment_method($method){
  

  $method=_trim($method);
  //  print "$method\n";
  if($method=='' or $method=='0')
    return 0;
  if(preg_match('/^(Card Credit|credit  card|Debit card|Crredit Card|Credit Card|Solo|Cr Card|Switch|visa|electron|mastercard|card|credit Card0|Visa Electron|Credi Card|Credit crad)$/i',$method))
    return 2;

  //  print "$method\n";
  if(preg_match('/^(Cheque receiv.|APC|\*Cheque on Delivery\s*|Cheque|APC to Collect|chq|PD CHQ|APC collect CHQ|APC to coll CHQ|APC collect cheque)$/i',$method))
    return 4;
  if(preg_match('/^(Account|7 Day A.C|Pay into a.c|pay into account)$/i',$method))
    return 5;
  if(preg_match('/^(cash|casg|casn)$/i',$method))
    return 1;
  if(preg_match('/^(Paypal|paypall|pay pal)$/i',$method))
    return 6;
  if(preg_match('/^(bacs|Bank Transfer|Bank Transfert|Direct Bank)$/i',$method))
    return 3;
  if(preg_match('/^(draft|bank draft|bankers draft)$/i',$method))
    return 7;
  if(preg_match('/^(postal order)$/i',$method))
    return 8;
  if(preg_match('/^(Moneybookers)$/i',$method))
    return 9;

  print "Warning: unnkown pay method $method \n";
  return 0;

}


function get_tax_number($tax_number){
  
  $tax_number=_trim($tax_number);
  // print "$tax_number\n";
  $tax_number=preg_replace('/^vat no\s*(\.|:)?\s*/i','',$tax_number);
  $tax_number=preg_replace('/^vat\s*(\:|\-)?\s*/i','',$tax_number);
  $tax_number=preg_replace('/^vat\s*reg\*(\:|\-)?\s*/i','',$tax_number);

  $tax_number=preg_replace('/\-?\s*ok$/i','',$tax_number);
  $tax_number=preg_replace('/\-?\s*checked$/i','',$tax_number);
  $tax_number=preg_replace('/\s*ckecked$/i','',$tax_number);
  $tax_number=preg_replace('/\-?\s*checked\s+valid\.?$/i','',$tax_number);
  $tax_number=_trim($tax_number);
  //print "$tax_number\n";
  if(preg_match('/^[a-z]{1,2}\s*\-?\s*[a-z0-9]{8,12}\s*$/i',$tax_number)){
    $tax_number=preg_replace('/\s/','',$tax_number);
    if(!($tax_number[2]=='-'  or $tax_number[1]=='-')){

      if(preg_match('/^[a-z]{2}\d/i',$tax_number)){
	$t1=substr($tax_number,0,2);
	$t2=substr($tax_number,2);
	$tax_number=$t1.'-'.$t2;
      }elseif(preg_match('/^[a-z]\d/i',$tax_number)){
	$t1=substr($tax_number,0,1);
	$t2=substr($tax_number,1);
	$tax_number=$t1.'-'.$t2;
      }      
      

    }
    // print "$tax_number\n";
    return $tax_number;
  }elseif(preg_match('/^\d{7,12}$/i',$tax_number)){
    // print "$tax_number\n";
    return $tax_number;
      
  }
    

  return false;

}


function update_orden($order_id,
		      $customer_id,
		      $header,
		      $act,
		      $date_index,
		      $date_order,
		      $date_inv,
		      $tipo,
		      $address_del_id='',
		      $address_bill_id='',
		      $new_customer,
		      $is_island,$parent_order_id,$partner=0,$co=''
		      ){



  $db =& MDB2::singleton();
  global $tax_rate;
  global $home_country_id;
  // first delete all the related sub tables
  $sql="delete from tax where order_id=$order_id";mysql_query($sql);
  $sql="delete from charge where order_id=$order_id";mysql_query($sql);
  $sql="delete from shipping where order_id=$order_id";mysql_query($sql);
  $sql="delete from balance where order_id=$order_id";mysql_query($sql);
  $sql="delete from todo_users where order_id=$order_id";mysql_query($sql);
  $sql="delete from todo_shipping_supplier where order_id=$order_id";mysql_query($sql);
  
 $sql="delete from pick where order_id=$order_id";mysql_query($sql);
      $sql="delete from pack where order_id=$order_id";mysql_query($sql);

  $tax_factor=$tax_rate;
  $total_tax=$header['tax1']+$header['tax2'];
  $total_net=$header['total_net'];
  $total=$header['total_topay'];
  if($total!=0 and $total_tax==0){
    $tax_factor=0;
  }

  $ajuste_in_net=$total_net-$header['total_items_charge_value']-$header['charges']-$header['shipping'];
  $ajuste_in_tax=$total_tax-($total_net*$tax_factor);
  $ajuste_in_total=$total-$total_net-$total_tax;



  //  print_r($header);
  //source tipo - can be i(internet),t(telephone),f(fax),p(post),s(showroom),a(staffsales),u(unknown)
  if($header['source_tipo']=='')$header['source_tipo']='u';
  $_gold=($header['gold']=='Gold Reward'?'1':'0');

  $a_taken=get_user_id($header['takenby'],addslashes($order_id),'taken');
  //print "---------  ".$header['takenby']."  ------\n";
  // print_r($a_taken);
 
  if(count($a_taken)==1)
    $_taken=$a_taken[0];
  else
    $_taken='null';
  $payment_method=get_payment_method($header['pay_method']);
  $del_country_id=country_id($address_del_id,$home_country_id);

  $sql=sprintf("update orden set fao=%s,feedback_id=%d,source_tipo='%s',customer_name='%s',contact_name='%s',customer_id2=%s,customer_id3=%s,tel='%s',public_id='%s',parcels=%s,weight=%s,gold='%s',taken_by=%s
				       ,net=%.2f
				       ,tax=%.2f
				       ,total=%.2f
				       ,balance_net=%.2f
 ,balance_tax=%.2f ,balance_total=%.2f
				       ,payment_method='%s',date_creation=%s,date_processed=%s,date_invoiced=%s,titulo='%s',customer_id=%d,address_del=%s,address_bill=%s,tipo=%s,date_index=%s,parent_id=%s,partner='%s',del_country_id=%d where id=%d", prepare_mysql($co),
	       $header['feedback'],
	       $header['source_tipo'],
	       addslashes($header['trade_name']),
	       addslashes($header['customer_contact']),
	       prepare_mysql($header['extra_id1']),
	       prepare_mysql($header['extra_id2']),
	       addslashes($header['phone']),
	       addslashes($header['order_num']),
	       prepare_mysql($header['parcels']),
	       prepare_mysql($header['weight']),
	       $_gold,
	       $_taken,
	       $total_net,
	       $total_tax,
	       $total,
	       $ajuste_in_net,
	       $ajuste_in_tax,
	       $ajuste_in_total,
	       addslashes($payment_method),
	       $date_order,
	       $date_order,
	       $date_inv,
	       addslashes(mb_ucwords($header['ltipo'])),
	       $customer_id,
	       prepare_mysql(display_full_address($address_del_id)),
	       prepare_mysql(display_full_address($address_bill_id)),
	       $tipo,
	       $date_index,prepare_mysql($parent_order_id),$partner,$del_country_id,
	       $order_id
	       );
  
  mysql_query($sql);

      print "$sql";







  if($tax_factor==0){
    $tax_id=1;
    $tax_code='NULL';
  }else{
    $tax_id=2;
    $tax_code='S';
    $sql=sprintf("insert into tax (order_id,code,value) values (%d,'S',%.2f)",$order_id,$total_tax);
    //print "$sql\n";
    mysql_query($sql);


  }
  if($header['charges']!=0){
   
    $sql=sprintf("insert into charge (tipo,order_id,tax_code,value) values (1,%d,%s,%.2f)",$order_id,prepare_mysql($tax_code),$header['charges']);
    // print $header['charges']." $sql\n";
    mysql_query($sql);
  }


  $notes2=$header['notes2'];
  
  if(isset($act['tax_number'])){
    $tax_number_act=get_tax_number($act['tax_number']);
  }else
    $tax_number_act=false;

  $tax_number=false;
  $country_id= get_customer_country_id($customer_id);
  //print "$country_id\n";
  if(is_numeric($country_id) and $country_id>0 and $country_id!=$home_country_id){

    $tax_number=get_tax_number($notes2);
  }

  if($tax_number){
    change_tax_number($customer_id,$tax_number,$date_index,($new_customer?false:true));
    $notes2='';
  }elseif($tax_number_act){
    change_tax_number($customer_id,$tax_number_act,$date_index,($new_customer?false:true));
    
  }










  $notes=$header['notes'];

  if($notes=='0')
    $notes='';

  if(preg_match('/showroom|staff|local|colle/i',$notes) and $header['shipping']==0){// Collected
    $tipo_deliver=1;
  }else{
    $tipo_deliver=2;

    // Try to get the delevery comapny

    $shipping_supplier_id=get_shipping_supplier($notes,$order_id);
    //print "$shipping_supplier_id\n";
    
    if(is_numeric($shipping_supplier_id) and $shipping_supplier_id>0){
      $notes='';
    }else
      $shipping_supplier_id='';
    $sql=sprintf("insert into shipping (supplier_id,order_id,value,tax_code) values (%s,%d,%.2f,%s)",prepare_mysql($shipping_supplier_id),$order_id,$header['shipping'],prepare_mysql($tax_code));
    // print "$sql\n";
    mysql_query($sql);
  }
  
  $sql=sprintf("update orden set note=%s,note2=%s,tax_code=%s where id=%d",prepare_mysql($notes),
	       prepare_mysql($notes2),prepare_mysql($tax_code),$order_id);
  mysql_query($sql);

  if($date_order!='null' or $date_order!=''){
    $tipo=2;
    $sql=sprintf("insert into orden_history (tipo,order_id,fecha) values (%d,%d,%s)",$tipo,$order_id,$date_order);
    mysql_query($sql);
  }
  if($date_inv!='null' or $date_inv!=''){
    $tipo=5;
    $sql=sprintf("insert into orden_history (tipo,order_id,fecha) values (%d,%d,%s)",$tipo,$order_id,$date_inv);
    mysql_query($sql);
  }


  if($header['tax1']>0 or $header['tax1']<0)
    $tax_code='S';
  else
    $tax_code='';



  //   if($date_order!='null' or $date_order!=''){
  //     $tipo=2;
  //     $sql=sprintf("update orden_history set fecha=%s where tipo=%d and order_id=%d",$date_order,$tipo,$order_id);
  //     mysql_query($sql);
  //   }else{
  //     $sql=sprintf("delete orden_history where tipo=%d and order_id=%d",$tipo,$order_id);
  //     mysql_query($sql);

  //   }

  //   if($date_inv!='null' or $date_inv!=''){
  //     $tipo=2;
  //     $sql=sprintf("update orden_history set fecha=%s where tipo=%d and order_id=%d",$date_inv,$tipo,$order_id);
  //     mysql_query($sql);
  //   }else{
  //     $sql=sprintf("delete orden_history where tipo=%d and order_id=%d",$tipo,$order_id);
  //     mysql_query($sql);

  //   }


  return $tax_code;

  //   print "$sql\n";
  // exit;


}







function read_products($raw_product_data,$y_map){
  
  if(isset($y_map['no_reorder']) and $y_map['no_reorder'])
    $re_order=false;
  else
    $re_order=true;

  if(isset($y_map['no_price_bonus']) and $y_map['no_price_bonus'])
    $no_price_bonus=true;
  else
    $no_price_bonus=false;


  $transactions=array();
  foreach($raw_product_data as $raw_data){
    foreach($y_map as $key=>$value){
      $_data=$raw_data[$value];
      if(preg_match('/order|reorder|bonus/',$key))
	if($_data=='')$_data=0;
      
      if(!$re_order and ($key=='reorder' or $key=='rrp')  )
	$_data=0;
      
      if($no_price_bonus){
	if($key=='order' and $transaction['price']==0)
	  $_data=0;
	if($key=='bonus' and $transaction['price']==0)
	  $_data=$_data+ $raw_data[$y_map['order']]  ;


      }



      $transaction[$key]=$_data;
    }
    $transaction['fob']=$raw_data['fob'];
    $transactions[]=$transaction;
  }
  // print_r($transactions);
  return $transactions;
}

function set_transactions($transactions,$order_id,$tipo_order,$parent_order_id,$date_index,$record_out_stock=true,$tax_code='S'){
  $db =& MDB2::singleton();


  $date_index=str_replace("'",'',$date_index);


  $my_total_net=0;
  $my_total_rrp=0;
  $my_total_items_order=0;
  $my_total_items_reorder=0;
  $my_total_items_bonus=0;
  $my_total_items_free=0;
  $my_total_items_dispached=0;
  $value_outstoke=0;
  $credit_value=0;

  //   print_r($transactions);
  foreach($transactions as $transaction){

    
    if($transaction['fob'])
      $promotion_id=1;
    else
      $promotion_id='NULL';

    
    if($transaction['order']=='')$transaction['order']=0;
    if($transaction['reorder']=='')$transaction['reorder']=0;
    if($transaction['bonus']=='')$transaction['bonus']=0;
    if($transaction['discount']=='')$transaction['discount']=0;
    
    $my_items_to_charge=$transaction['order']-$transaction['reorder'];
    $my_items_to_charge_value=$my_items_to_charge*($transaction['price'] * (1-$transaction['discount']));
    //  print_r($transaction);
    $my_items_to_dispach=$my_items_to_charge+$transaction['bonus'];
    if(preg_match('/credit/i',$transaction['code'])){
      //	    $transaction['credit']=-abs( $transaction['credit']);
      $credit_parent=$transaction['description'];
      $my_items_to_charge_value=$transaction['credit'];
    }
    
    $my_total_rrp+=$my_items_to_charge*($transaction['rrp']*$transaction['units']);
    $my_total_net+=$my_items_to_charge_value;
    //	  print $transaction['code']." caca $my_total_net =$my_items_to_charge_value \n ";
    $my_total_items_order+=$transaction['order'];
    $my_total_items_reorder=$transaction['reorder'];
    $my_total_items_bonus+=$transaction['bonus'];
    $my_total_items_dispached+=$my_items_to_dispach;

    if($transaction['discount']==1)
      $my_total_items_free+=$my_total_items_dispached;
    $tipo_t=1;
    if($transaction['discount']==1)
      $tipo_t=2;


    // Credits




    if(preg_match('/credit/i',$transaction['code'])){
      $parent_id='';
      $parent='xxxx';
      $tipo=0;
      $parent_note=$transaction['description'];
      
      if(preg_match('/^Credit owed for order no\.:/i',$parent_note)){
	$tipo=1;
	if(preg_match('/\d{4,5}/',$parent_note,$thismatch))
	  {
	    $parent=$thismatch[0];
	  }
	      
      }


      $sql=sprintf("select id from orden where public_id=%d",$parent);
      //  print "$parent_note $sql\n";
      $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
      if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$parent_id=$row['id'];
      }      
      global $tax_rate;
      $tax_factor=$tax_rate;
    
      $credit_value_net=-$transaction['price'];
      if($tax_code=='S')
	$credit_value_tax=$tax_factor*$credit_value_net;
      else
	$credit_value_tax=0;

      $tipo=2;// Debit done
      $parent_note=preg_replace('/^Credit owed for order no..$/i','',$parent_note);
      //  if(is_numeric($parent_id)){

      


      $sql=sprintf("insert into debit (tipo,order_affected_id,order_original_id,note,value_net,value_tax,date_done,tax_code) value (%d,%d,%s,%s ,'%.2f','%.2f',%s,%s)"
		   ,$tipo
		   ,$order_id
		   ,prepare_mysql($parent_id)
		   ,prepare_mysql($parent_note)
		   ,$credit_value_net
		   ,0
		   ,prepare_mysql_date($date_index)
		   ,prepare_mysql($tax_code));
      // print "$sql\n";
      mysql_query($sql);// $db->exec($sql);

  
    }
	


    //$sql=sprintf("update orden set debits='%.2f' where id=%d",$credit_value,$order_id);
    //mysql_query($sql);//$db->exec($sql);


      
    // do a todo_debit
    //	$sql=sprintf("insert into todo_debit (tipo,order_affected_id,note,value,date_creation,date_done) value (%d,%d,%s,%.2f,%s)",$tipo,$order_id,prepare_mysql($parent_note),$credit_value,$date_index);
    //	print "$sql\n";
    //	mysql_query($sql);// $db->exec($sql);


    //      }


    //}

    $is_cash_promo=false;
    if(preg_match('/Promo$/i',$transaction['code']) and ($transaction['price']*$transaction['order']-$transaction['reorder']+$transaction['bonus'])<0){
      $sql=sprintf("insert into debit (tipo,order_affected_id,order_original_id,note,value_net,value_tax,date_done,tax_code) value (%d,%d,%s,%s ,'%.2f','%.2f',%s,%s)"
		   ,6
		   ,$order_id
		   ,'NULL'
		   ,prepare_mysql($transaction['code'])
		   ,$transaction['price']*$transaction['order']-$transaction['reorder']+$transaction['bonus']
		   ,0
		   ,prepare_mysql_date($date_index)
		   ,prepare_mysql($tax_code));
      //print "$sql\n";
      $is_cash_promo=true;
      mysql_query($sql);// $db->exec($sql);
    }

  
    //print $transaction['code']."\n";
    if($tipo_order==6 or $tipo_order==7 or $tipo_order==8){
      if(is_numeric($parent_order_id))
	$original_order=$parent_order_id;
      else
	$original_order=0;
    }else
      $original_order='NULL';


    if(preg_match('/^PI-/i',$transaction['code']))
      $sql=sprintf("select id from product where description='%s' and code='%s'",addslashes($transaction['description']),addslashes($transaction['code']));
    else
      $sql=sprintf("select id from product where code='%s'",addslashes($transaction['code']));


    $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
    if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      // Found Product
      $product_id=$row['id'];
     
      //      if(!is_numeric($order_id) or $order_id<1)
      //	exit('Error order id can no be this');
	

      $sql=sprintf("insert into transaction (promotion_id,tipo,order_id,product_id,ordered,dispached,discount,charge,tax_code,original_order_id) value (%s,%d,%d,%d,%.2f,%.2f,%.3f,%.2f,%s,%s)",$promotion_id,$tipo_t,$order_id,$product_id,$transaction['order'],$my_items_to_dispach,$transaction['discount'],$my_items_to_charge_value,prepare_mysql($tax_code),$original_order);
      
      //print "x $sql\n";
      //exit;
      mysql_query($sql);
      
	if($transaction['reorder']>0 and $record_out_stock){
	    $value_outstoke=$value_outstoke+($transaction['reorder'] * ($transaction['price'] * (1-$transaction['discount'])));
	}
	
	if($transaction['reorder']>0) {
	    $sql=sprintf("insert into outofstock (order_id,product_id,qty,status) value (%d,%d,%.2f,%s)",$order_id,$product_id,$transaction['reorder'],($record_out_stock?1:2));
	    mysql_query($sql);
	    
	}
	   
      if($transaction['bonus']>0  or $transaction['discount']==1) {
	$qty=$transaction['bonus'];
	if($transaction['discount']==1)
	  $qty+=$my_items_to_charge;
	$sql=sprintf("insert into bonus (order_id,product_id,qty,promotion) value (%d,%d,%.2f,%d)",$order_id,$product_id,$qty,$promotion_id);
	//		print "$sql\n";
	mysql_query($sql);
      }
    }else{

      if(!preg_match('/credit/i',$transaction['code'])   and !$is_cash_promo){
	
	$sql=sprintf("insert into todo_transaction (promotion_id,code,description,order_id,ordered,reorder,bonus,price,discount,tax_code,original_order_id) value (%s,'%s','%s',%d,  %.2f,%.2f,%.2f,%.2f,%.2f,%s,%s)",$promotion_id,addslashes($transaction['code']),addslashes($transaction['description']),$order_id,$transaction['order'],$transaction['reorder'],$transaction['bonus'],$transaction['price'],$transaction['discount'],prepare_mysql($tax_code),$original_order);
	//  print "x $sql\n";
	mysql_query($sql);
      }


    }








  }

  $sql=sprintf("update orden set outofstock='%.2f' where id=%d",$value_outstoke,$order_id);
  $db->exec($sql);







  // Blamce the originals
  
  if(is_numeric($parent_order_id)){
    $debit_value_net=0;
    $debit_value_tax=0;
    $sql=sprintf("select value_net,value_tax from debit where order_original_id=%d",$parent_order_id);
    // print "$sql\n";
    $result = mysql_query($sql) or die('Query failed:zzasa ' . mysql_error());
    while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      $debit_value_net+=$row['value_net'];
      $debit_value_tax+=$row['value_tax'];
    }      
    $sql=sprintf("select total,net,tax from orden where id=%d",$parent_order_id);
  
    $result = mysql_query($sql) or die('Query failed:zz ' . mysql_error());
    if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      $balance_net=$row['net']+$debit_value_net;
      $balance_tax=$row['tax']+$debit_value_tax;
      $balance_total=$row['total']+$debit_value_net+$debit_value_tax;
    
      $sql=sprintf("update orden set balance_net='%.2f' , balance_tax='%.2f' , balance_total='%.2f' where id=%d",$balance_net,$balance_tax,$balance_total,$parent_order_id);
      mysql_query($sql);//$db->exec($sql);
    }     
  
  }

  // Balance this one


  // money due to cash promotions

  $debit_value_net=0;
  $debit_value_tax=0;

  $sql=sprintf("select value_net,value_tax from debit where (tipo!=6 and tipo!=5) and order_affected_id=%d",$order_id);
  $result = mysql_query($sql) or die('Query failed:zzasa ' . mysql_error());
  while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $debit_value_net-=$row['value_net'];
    $debit_value_tax-=$row['value_tax'];
  }      
  $sql=sprintf("select total,net,tax from orden where id=%d",$order_id);
  
  $result = mysql_query($sql) or die('Query failed:zz ' . mysql_error());
  if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
 

    $balance_net=$row['net']+$debit_value_net;
    $balance_tax=$row['tax']+$debit_value_tax;
    $balance_total=$row['total']+$debit_value_net+$debit_value_tax;
    
    $sql=sprintf("update orden set balance_net='%.2f' , balance_tax='%.2f' , balance_total='%.2f' where id=%d",$balance_net,$balance_tax,$balance_total,$order_id);
    
    //    print "$sql\n";
    mysql_query($sql);//$db->exec($sql);
  }     






}


function get_dates($filedate,$header_data,$tipo_order,$new_file=true){


  $datetime_updated=date("Y-m-d H:i:s",$filedate);
  $time_updated_menos30min=date("H:i:s",$filedate-1800);

  list($date_updated,$time_updated)=split(' ',$datetime_updated);
  if($new_file){
    if($tipo_order==2){
      print "$date_updated ".$header_data['date_inv']."  \n";
      if($date_updated ==$header_data['date_inv']){
	$date_charged="'".$date_updated." ".$time_updated."'";
	if($header_data['date_inv']==$header_data['date_order'])
	  $date_processed=$header_data['date_order']." ".$time_updated_menos30min."'";
	$date_processed="'".$header_data['date_order']." 09:30:00'";
      }else{
	$date_charged="'".$header_data['date_inv']." 16:30:00'";
	$date_processed="'".$header_data['date_order']." 09:30:00'";
      }
      $date_index=$date_charged;
    }else{



      $date_charged="NULL";
      if($date_updated ==$header_data['date_order']){
	//print $header_data['date_order']." xssssssssssssxx";
	$date_processed="'".$date_updated." ".$time_updated."'";
	// print "$date_processed  xssssssssssssxx\n";

      }
      else
	$date_processed="'".$header_data['date_order']." 08:30:00'";
      $date_index=$date_processed;

      // print $date_index." xxx\n";

    }
  }
  print "$date_index,$date_processed,$date_charged\n";
  return array($date_index,$date_processed,$date_charged);

}


function setup_contact($act_data,$header_data,$date_index){
  $co='';
  $header_data['country_d2']='';
  $header_data['country']='';
  $header_data['country_d1']='';

  $new_customer=false;



  $this_is_order_number=$header_data['history'];
  if(!is_numeric($this_is_order_number)){
    print "Warning history not numeric\n";
    $this_is_order_number=1;

  }

  //  print_r($header_data);
  // print_r($act_data);

  if(preg_match('/cash sale/i',$header_data['trade_name'])){
    
    if($header_data['address1']=='' and$header_data['address2']=='' and $header_data['address3']=='' and $header_data['city']=='' and $header_data['postcode']==''  and isset($act_data) 
       ){
      
      $staff_name=$act_data['contact'];
      $staff_id=get_user_id($staff_name,'' , '',false);
      if((count($staff_id)==1 and $staff_id[0]>0)){
	print "Staff $staff_name  sale\n";
	$header_data['address1']=$act_data['contact'];
      }
      unset($act_data);

    }
    

    $staff_name=$header_data['address1'];
    $staff_id=get_user_id($staff_name,'' , '',false);
    if(count($staff_id)==1 and $staff_id[0]>0){
      print "Staff sale\n";
      unset($act_data);
    }
  }


  $skip_del_address=false;
  $mob_data=false;
  $tel_data=false;
  $fax_date=false;
  $email_data=false;
  
  if(isset($header_data['phone'])  and $header_data['phone']=='0'  )
    $header_data['phone']='';
  if(isset($header_data['postcode'])  and $header_data['postcode']=='0'  )
    $header_data['postcode']='';

  // print_r($act_data);
  // print_r($header_data);
    
  if(!isset($act_data) or count($act_data)==0){
    $act_data['name']='';
    $act_data['contact']='';
    $act_data['a1']='';
    $act_data['a2']='';
    $act_data['postcode']='';
    $act_data['country_d2']='';
    $act_data['name']='';
    $act_data['a3']='';
    $act_data['town']='';
    $act_data['tel']='';
    $act_data['fax']='';
    $act_data['mob']='';
    $act_data['source']='';
    $act_data['act']='';
    $act_data['email']='';
    $act_data['country']='';
    
    $act_data['town_d1']='';
    $act_data['town_d2']='';


    //print_r($header_data);
    //exit;

    if(preg_match('/sale - Philip|staff|staff order|cash sale|staff sale|cash - sale/i',$header_data['trade_name']) or 
       preg_match('/staff|staff sale|cash sale/i',$header_data['city']) or
       preg_match('/staff|staff sale|cash sale/i',$header_data['address1']) or
       preg_match('/staff|staff sale|cash sale/i',$header_data['address2']) or
       preg_match('/staff|staff sale|cash sale/i',$header_data['address3']) or
       preg_match('/^staff$|staff sale/i',$header_data['notes']) or

       preg_match('/staff|staff sale|cash sale/i',$header_data['postcode'])){
      //print "cash\n";
      // Chash tipe try to get staff name
      if($header_data['address1']=='Al & Bev')
	$header_data['address1']='Bev';
	
      $regex='/staff orders?|staff|sales?|cash|\-|:|Mark postage to France/i';
	
      $header_data['city']=_trim(preg_replace($regex,'',$header_data['city']));
      $header_data['postcode']=_trim(preg_replace($regex,'',$header_data['postcode']));
      $header_data['trade_name']=_trim(preg_replace($regex,'',$header_data['trade_name']));
      $header_data['address1']=_trim(preg_replace($regex,'',$header_data['address1']));
      $header_data['address2']=_trim(preg_replace($regex,'',$header_data['address2']));
      $header_data['address3']=_trim(preg_replace($regex,'',$header_data['address3']));
      $header_data['customer_contact']=_trim(   preg_replace($regex,'',$header_data['customer_contact'])      );
      $header_data['phone']=_trim(preg_replace($regex,'',$header_data['phone']));

	
      if($header_data['address1']=='' and $header_data['postcode']=='' and $header_data['city']!='' and $header_data['customer_contact']=='' )
	$header_data['address1']=$header_data['city'];
      if($header_data['address1']=='' and $header_data['postcode']!='' and $header_data['city']==''   and $header_data['customer_contact']=='' )
	$header_data['address1']=$header_data['postcode'];
      if($header_data['address1']=='' and $header_data['postcode']=='' and $header_data['city']==''  and $header_data['customer_contact']!=''  )
	$header_data['address1']=$header_data['customer_contact'];
      if($header_data['address1']=='' and $header_data['postcode']==''  and $header_data['city']==''  and $header_data['customer_contact']=='' and  $header_data['trade_name']!='')
	$header_data['address1']=$header_data['trade_name'];
      if($header_data['address1']=='' and $header_data['address2']!='' and $header_data['address3']=='' and $header_data['phone']=='' and $header_data['postcode']==''  and $header_data['city']==''  and $header_data['customer_contact']=='' and  $header_data['trade_name']=='')
	$header_data['address1']=$header_data['address2'];
      if($header_data['address1']=='' and $header_data['address2']=='' and $header_data['address3']!='' and $header_data['phone']=='' and $header_data['postcode']==''  and $header_data['city']==''  and $header_data['customer_contact']=='' and  $header_data['trade_name']=='')
	$header_data['address1']=$header_data['address3'];
      if($header_data['address1']=='' and $header_data['address2']=='' and $header_data['address3']=='' and $header_data['phone']=='' and $header_data['postcode']==''  and $header_data['notes']!=''       and $header_data['city']==''  and $header_data['customer_contact']=='' and  $header_data['trade_name']==''){
	// Unkown
	  
	$header_data['address1']=$header_data['notes'];
      }


      if($header_data['address1']=='' and $header_data['address2']=='' and $header_data['address3']=='' and $header_data['phone']=='' and $header_data['postcode']==''  and $header_data['city']==''  and $header_data['customer_contact']=='' and  $header_data['trade_name']==''){
	// Unkown
	// Create unknowen customer
	$customer_id=insert_customer('NULL',array(7,1,2,3,11,10),$date_index,($this_is_order_number==1?true:false));
	return array(false,$customer_id,false,false,false,true,$co);
      }

      

	
      if($header_data['address1']!=''){
	$staff_name=$header_data['address1'];

	$staff_id=get_user_id($staff_name,'' , '',false);
	//	    print "$staff_name";
	//	  print_r($staff_id);
	//	  exit;

	if(count($staff_id)==1 and $staff_id[0]>0){
	    
	  $staff_id=$staff_id[0];
	  $staff_data=get_staff_data($staff_id);
	  //print_r($staff_data);
	  $contact_id=$staff_data['contact_id'];
	  // print_r(get_contact_data($contact_id));
	  // exit;
	  if(!$staff_data['customer_id']){
	    $customer_id = insert_customer($contact_id,array(9,1,2,3,7,10),$date_index,($this_is_order_number==1?true:false));
	    $new_customer=true;
	  }else{
	    $customer_id = $staff_data['customer_id'];
	    $new_customer=false;
	  }
	  return array($contact_id,$customer_id,false,false,false,$new_customer,$co);
	}else{
	  // print $staff_name;
	  if(preg_match('/|maureen|church|Parcel Force Driver|sarah|Money in Petty|church|Parcel Force Driver|craig|malcol|Joanne/i',$staff_name)){
	    $customer_id=insert_customer('NULL',array(7,1,2,3,11,10),$date_index,($this_is_order_number==1?true:false));
	    return array(false,$customer_id,false,false,false,true,$co);
	       
	       
	  }
	      
	    





	}
	    
      }
	

    }

  



    // Try to fix it
    if(isset($header_data['order_num'])){

      switch($header_data['order_num']){
  case(77781):
	$skip_del_address=true;
	$act_data['a1']='20 Thrilmere Avenue';
	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town']='Elland';
	$act_data['country_d2']='';
	$act_data['country_d1']='';
	$act_data['country']='UK';
	$act_data['postcode']='HX5 9PN';
	$act_data['contact']='Gareth Walker';
	$act_data['name']='Lazer-Me';
	$act_data['tel']='01422250350';
		$act_data['tel']='';
	$act_data['fax']='';
	$act_data['mobile']='';
	$act_data['email']='';
	break;
      case(59470):
	$skip_del_address=true;
	$different_delivery_address=true;
	$header_data['address1']='46 Moorland Rise';
	$header_data['address2']='';
	$header_data['address3']='';
	$header_data['city']='Haslingden';
	$header_data['postcode']='BB4 6UA';
	$header_data['country']='UK';
	$act_data['contact']='Susan Sanchia';
	$act_data['name']='Crystal Man of Almeria';
	$act_data['a1']='Calle Gines Parra 0010';
	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town_d1']='El Cucador';
	$act_data['town']='Zurgena';
	$act_data['country_d2']='Almeria';
	$act_data['country_d1']='Adalucia';
	$act_data['country']='Spain';
	$act_data['postcode']='04661';
	$act_data['tel']='';
	$act_data['fax']='';
	$act_data['mobile']='';
	$act_data['email']='';
	break;
 case(83652):
	$skip_del_address=true;
	$act_data['a1']='7-9 Filey Road';
	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town']='Scarborough';
	$act_data['country_d2']='';
	$act_data['country_d1']='';
	$act_data['country']='UK';
	$act_data['postcode']='YO11 2SE';
	$act_data['email']='karensmith@hotmail.com';
	$act_data['contact']='Karen Smith';
	$act_data['name']='Bradley Court Hotel';
	break;
      case(12636):
	$skip_del_address=true;
	$act_data['a1']='Leoforos Salaminas 103';
	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town']='Salamina';
	$act_data['country_d2']='';
	$act_data['country_d1']='Attoka';
	$act_data['country']='Greece';
	$act_data['postcode']='18900';
	break;
      case(44059):
	$skip_del_address=true;
	$different_delivery_address=true;
	$header_data['address1']='C/O Mondial Forwarding';
	$header_data['address2']='';
	$header_data['address3']='46 Lockfield Avenue';
	$header_data['city']='London';
	$header_data['postcode']='EN3 7PX';
	$header_data['country']='UK';
	$act_data['a1']='PO BOX 493491';
	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town']='Neapoly';
	$act_data['country_d2']='';
	$act_data['country_d1']='Lakonia';
	$act_data['country']='Greece';
	$act_data['postcode']='';


	break;
      case(8192):
      case(19295):
      case(43870):
      case(28867):
	$skip_del_address=true;
	$different_delivery_address=true;
	$header_data['address1']='C/O Frans Maas (UK) Ltd';
	$header_data['address2']='Timpson Road';
	$header_data['address3']='';
	$header_data['city']='Manchester';
	$header_data['postcode']='M23 9NT';
	$header_data['country']='UK';
	$act_data['a1']='Petrou Fouriki & N.';
	$act_data['a2']='Papanikolaou 6';
	$act_data['a3']='';
	$act_data['town']='Salamina';
	$act_data['country_d2']='';
	$act_data['country_d1']='Attoka';
	$act_data['country']='Greece';
	$act_data['postcode']='18900';


	break;
      case(16339):

	$skip_del_address=true;
	$different_delivery_address=true;
	$header_data['address1']='C/O Frans Maas (UK) Ltd';
	$header_data['address2']='Timpson Road';
	$header_data['address3']='';
	$header_data['city']='Manchester';
	$header_data['postcode']='M23 9NT';
	$header_data['country']='UK';
	$act_data['a1']='Dynamidi 22';
	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town']='Salamina';
	$act_data['country_d2']='';
	$act_data['country_d1']='Attoka';
	$act_data['country']='Greece';
	$act_data['postcode']='18902';


	break;
      case(8192):

      case(13577):

	$skip_del_address=true;
	$different_delivery_address=true;
	$header_data['address1']='C/O Frans Maas (UK) Ltd';
	$header_data['address2']='Timpson Road';
	$header_data['address3']='';
	$header_data['city']='Manchester';
	$header_data['postcode']='M23 9NT';
	$header_data['country']='UK';
	$act_data['a1']='Leoforos Salaminas 103';
	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town']='Salamina';
	$act_data['country_d2']='';
	$act_data['country_d1']='Attoka';
	$act_data['country']='Greece';
	$act_data['postcode']='18900';


	break;
      case(28867):
	$skip_del_address=true;
	$different_delivery_address=true;
	$header_data['address1']='C/O Frans Maas (UK) Ltd';
	$header_data['address2']='Timpson Road';
	$header_data['address3']='';
	$header_data['city']='Manchester';
	$header_data['postcode']='M23 9NT';
	$header_data['country']='UK';
	$act_data['a1']='N. Papanikolaou 6';
	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town']='Salamina';
	$act_data['country_d2']='';
	$act_data['country_d1']='Attoka';
	$act_data['country']='Greece';
	$act_data['postcode']='18900';
	break;
      case(21169):

	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town']='Oughterard';
	$act_data['country_d2']='Galway';
	$skip_del_address=true;
	   break;
      case(40508):
      case(50878):
	$act_data['a1']='Calle Gines Parra 0010';
	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town_d1']='El Cucador';
	$act_data['town']='Zurgena';
	$act_data['country_d2']='Almeria';
	$act_data['country_d1']='Adalucia';
	$act_data['country']='Spain';
	$act_data['postcode']='04661';
	$skip_del_address=true;
	break;
      case(33459):
	$act_data['town']='Alfaz del Pi';
	$act_data['country_d2']='Alicante';
	$act_data['country_d1']='Valencian Community';
	$act_data['country']='Spain';
	$act_data['postcode']='03580';
	$skip_del_address=true;
	break;
      case(33459):
	$act_data['town']='San Miguel de las Salinas';
	$act_data['country_d2']='Alicante';
	$act_data['country_d1']='Valencian Community';
	$act_data['country']='Spain';
	$act_data['postcode']='03193';
	$skip_del_address=true;
	break;

      case(54503):
      case(52941):
      case(52712):
      case(49477):
      case(44644):
      case(44052):
      case(41321):
	$skip_del_address=true;
	$different_delivery_address=true;
	$header_data['address1']='15 Kestrel House';
	$header_data['address2']='';
	$header_data['address3']='';
	$header_data['city']='Farnham';
	$header_data['postcode']='GU9 8UY';
	$header_data['country']='UK';
	$act_data['contact']='Ms Pauline Murdock';
	$act_data['name']='Crystal Man of Almeria';
	$act_data['a1']='Calle Gines Parra 0010';
	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town_dq']='El Cucador';
	$act_data['town']='Zurgena';
	$act_data['country_d2']='Almeria';
	$act_data['country_d1']='Adalucia';
	$act_data['country']='Spain';
	$act_data['postcode']='04661';
	$act_data['tel']='';
	$act_data['fax']='';
	$act_data['mobile']='';
	$act_data['email']='';
	break;
      case(52060):
      case(54502):
      case(56510):
	$skip_del_address=true;
	$different_delivery_address=true;
	$header_data['address1']='Swan Lodge';
	$header_data['address2']='Hassock Hill Drove';
	$header_data['address3']='';
	$header_data['city']='Gorefield';
	$header_data['postcode']='PE13 4QF';
	$header_data['country']='UK';
	$act_data['tel']='';
	$act_data['fax']='';
	$act_data['mobile']='';
	$act_data['email']='';
	$act_data['contact']='Ms Telma Pope';
	$act_data['name']='Crystal Man of Almeria';
	$act_data['a1']='Calle Gines Parra 0010';
	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town']='El Cucador';
	$act_data['country_d2']='Almeria';
	$act_data['country_d1']='Adalucia';
	$act_data['country']='Spain';
	$act_data['postcode']='04661';
	break;	
      case(59505):
      case(60970):
      case(68058):
      case(65639):
      case(62012):
      case(63810):
      case(71447):
      case(74506):

	$skip_del_address=true;
	$different_delivery_address=true;
	$header_data['address1']='Read Coat Express';
	$header_data['address2']='Global House';
	$header_data['address3']='Manor Court';
	$header_data['city']='Crawley';
	$header_data['postcode']='RH10 9PY';
	$header_data['country']='UK';
	$act_data['tel']='';
	$act_data['fax']='';
	$act_data['mobile']='';
	$act_data['email']='';
	$act_data['name']='Crystal Man of Almeria';
	$act_data['a1']='Calle Gines Parra 0010';
	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town']='El Cucador';
	$act_data['country_d2']='Almeria';
	$act_data['country_d1']='Adalucia';
	$act_data['country']='Spain';
	$act_data['postcode']='04661';
	break;	

      case(15320):
      case(17357):
      case(60454):
      case(39099):
	$customer_id=insert_customer('NULL',array(7,1,2,3,11,10),$date_index,($this_is_order_number==1?true:false));
	return array(false,$customer_id,false,false,false,true,$co);
	
	break;
      case(37736):
	$act_data['name']='Steel City Lighting';
	$skip_del_address=true;
	break;
      case(53380):
	$skip_del_address=true;
	break;
      case(34467):
	$act_data['name']='Kathy Van Pelt';
	$act_data['contact']='Kathy Van Pelt';
	$act_data['a1']='1605 Lomax Lane';
	$act_data['postcode']='90278';
	$act_data['country_d2']='California';
	$act_data['town']='Redondo Beach';
	$act_data['country']='USA';
	$act_data['tel']='001 310 318 30 80';
	$skip_del_address=true;
	break;

      case(33833):
	$act_data['name']='IA.CO';
	$act_data['contact']='Debbie Lemke';
	$act_data['a1']='43555 Grimmer Blvd #k188';
	$act_data['postcode']='94538';
	$act_data['country_d2']='California';
	$act_data['town']='Fremont';
	$act_data['country']='USA';
	$act_data['tel']='0015104400120';
	$skip_del_address=true;
	break;

      case(22245):
	$act_data['name']='Living TV - Web Shop';
	$act_data['contact']='Steve Deakin Davies';
	$act_data['mob']='07887985166';
	$skip_del_address=true;
	break;
      case(22502):
	$act_data['name']='Ashok Jhunjhunwala';
	$act_data['a1']='GD-213, Ground Fllor';
	$act_data['a2']='Salt Lake, Sector III';
	$act_data['postcode']='700106';
	$act_data['town']='Kilkaya';
	$act_data['country']='India';
	$act_data['tel']='00919830020595';
	$skip_del_address=true;
	break;
      case(23765):
	$act_data['name']='Ramzi Saade';
	$act_data['contact']='Mr Ramsey Saade';
	$act_data['a1']='Ballafletcher Cottage';
	$act_data['a2']='Peel Road';
	$act_data['postcode']='IM4 4LD';
	$act_data['town']='Braddan';
	$act_data['country']='Isle Of Man';
	$act_data['tel']='07624464629';
	$skip_del_address=true;
	break;

      case(22501):
	$act_data['name']='Taurus Corporation';
	$act_data['contact']='Arun Jhunjhunwala';
	$act_data['a1']='210 Tirupati Udyog';
	$act_data['a2']='IB Patel Road';
	$act_data['postcode']='400063';
	$act_data['town']='Mumbai';
	$act_data['country']='India';
	$act_data['tel']='912230966762';
	$skip_del_address=true;
	break;
      case(29798):
	$act_data['name']="Maria A Aranega";
	$act_data['tel']='0033386649331';
	$act_data['a1']='11/13 Rue de la Grande Juiverie';
	$act_data['postcode']='89100';
	
	$act_data['town']='Sens';
	$act_data['country']='France';
	$skip_del_address=true;
	break;
      case(30371):
	$act_data['name']="The Ambassadors";
	$act_data['contact']='Kim Skonier';
	$act_data['tel']='01483545825';
	$act_data['a1']='New Victoria Theatre';
	$act_data['a2']='Ther Peacocks Centre';
	$act_data['postcode']='GU21 6GQ';
	
	$act_data['town']='Woking';

	$skip_del_address=true;
	break;
      case(22405):
	$act_data['name']="Mrs M's Herbals";
	$act_data['contact']='Nicola Manghall';
	$act_data['tel']='01246850186';
	$act_data['a1']='5 Berry Street';
	$act_data['postcode']='S42 5JD';
	
	$act_data['town']='Chesterfield';

	$skip_del_address=true;
	break;


      case(70387):
	$act_data['name']='Le Spa Lelalei o Samoa';
	$act_data['contact']='Ivy Warner';
	$act_data['a1']='PO BOX 460';
	$act_data['a2']='Vaoala';
	$act_data['town']='Apia';
	$act_data['country']='Samoa';
	$act_data['email']='lelaleleiosamoa@hotmail.com';
	$skip_del_address=true;
	break;
      case(7667):
	$act_data['name']='Igneus Products';
	$act_data['a1']='Beta Works';
	$act_data['a2']='New Road';
	$act_data['postcode']='SK23 7JG';
	$act_data['country_d2']='Derbyshire';
	$act_data['town']='Whaley Bridge';
	$skip_del_address=true;

	break;
      case(7796):
	$act_data['name']='Ian Spencer';
	$act_data['contact']='Ian Spencer';
	$act_data['a1']='Bosworth College';
	$act_data['a2']='Leicester Lane';
	$act_data['postcode']='LE9 9JL';
	$act_data['country_d2']='Leicestershire';
	$act_data['town']='Desford';
	$act_data['tel']='1455822841';
	$skip_del_address=true;
	break;
      case(9620):
	$act_data['name']='T A Manson';
	$act_data['contact']='T A Manson';
	$act_data['a1']='21 North lane';
	$act_data['postcode']='CO6 1EG';
	$act_data['town']='Marks Tey';
	$act_data['tel']='01206210927';
	$skip_del_address=true;
	break;
      case(40146):
	$act_data['name']='Mrs M Lindfield';
	$act_data['a1']='6 Summers Mead';
	$act_data['postcode']='BS37 7RB';
	$act_data['town']='Yate';
	$act_data['tel']='0145311307';
	$act_data['mob']='07812058252';
	$skip_del_address=true;
	break;
      case(41102):
	$act_data['name']='Forget me not';
	$act_data['contact']='Dave & Jill Cotton';
	$act_data['a1']='48 Wessington Lane';
	$act_data['postcode']='DE55 7NB';
	$act_data['town']='Alferton';
	$act_data['tel']='01773546724';

	$skip_del_address=true;
	break;
      case(42500):
	$act_data['name']='Julia Lynn';
	$act_data['a1']='37 Meadowhead';
	$act_data['postcode']='S8 7UB';
	$act_data['town']='Sheffield';

	$skip_del_address=true;
	break;
      case(43767):
	$act_data['name']='Lillipots Florits';
	$act_data['contact']='Sharon Janson';
	$act_data['a1']='13 Church Street';
	$act_data['postcode']='S63 7QZ';
	$act_data['town']='Rotherham';
	$act_data['tel']='01709761414';
	$skip_del_address=true;
	break;
      case(44501):
	$act_data['name']='Jane Beale';
	$act_data['a1']='109 Alexandra Road';
	$act_data['postcode']='S2 3EH';
	$act_data['town']='Sheffield';
	$act_data['tel']='01142498811';
	$skip_del_address=true;
	break;
      case(45902):
	$act_data['name']='FGX International';
	$act_data['contact']='Alison Marstson';
	$act_data['a1']='500 George Washington Highway';
	$act_data['postcode']='02917';
	$act_data['town']='Smithfield';
	$act_data['country_d2']='RI';
	$act_data['tel']='14017192211';
	$act_data['country']='USA';
	$skip_del_address=true;
	break;
      case(47954):
	$act_data['name']='Mandy Lewis';
	$act_data['a1']='22 Albert Avenue';
	$act_data['postcode']='PE25 3DQ';
	$act_data['town']='Skegness';
	$act_data['tel']='01754611161';
	$skip_del_address=true;
	break;
      case(48152):
	$act_data['name']='Beeston Rylands Junior Scool';
	$act_data['contact']='Mrs Nicola Langley';
	$act_data['a1']='Trent Road';
	$act_data['postcode']='NG9 1LJ';
	$act_data['town']='Nottingham';
	$act_data['tel']='07792662802';
	$act_data['email']='celebrity.auction@nltworld.com';
	$skip_del_address=true;
	break;
      case(48232):
	$act_data['name']='Spa Moment (UK) Ltd';
	$act_data['contact']='Carolyn Sovatabua';
	$act_data['a1']='Dunston Hole Farm';
	$act_data['a2']='Dunston Road';
	$act_data['postcode']='S41 9RL';
	$act_data['town']='Chesterfield';
	$skip_del_address=true;
	break;
      case(52602):
	$act_data['name']='Stonemen Crafts';
	$act_data['contact']='Shirshir Asthana';
	$act_data['a1']='28/139 Gokulpura';
	$act_data['postcode']='282002';
	$act_data['town']='Agra';
	$act_data['country']='India';
	$skip_del_address=true;
	break;
      case(54856):
	$act_data['name']='Another Paraise';
	$act_data['contact']='Dawn Hopkins';
	$act_data['a1']='12 Ramseyburg Road';
	$act_data['postcode']='07832';
	$act_data['town']='Columbia';
	$act_data['country_d2']='NJ';
	$act_data['country']='USA';
	$skip_del_address=true;
	break;

      case(55732):
	$act_data['name']='John Jackson';
	$act_data['a1']='35 Rustlings Road';
	$act_data['postcode']='S11 7AA';
	$act_data['town']='Sheffield';
	$act_data['mob']='07743877474';
	$skip_del_address=true;
	break;
      case(62459):
	$act_data['name']='Ventura';
	$act_data['contact']='Leslie Jordan';
	$act_data['a1']='Magna Main Reception';
	$act_data['a2']='Sheffield Road';
	$act_data['postcode']='S60 1DX';
	$act_data['town']='Sheffield';
	$act_data['mob']='07810767418';
	$skip_del_address=true;
	break;
      case(67799):
	$act_data['name']='Muhammad Ridhuan';
	$act_data['a1']='Blk 450 Jorong West Street #01-86';
	$act_data['postcode']='640450';
	$act_data['town']='Singapore';
	$act_data['country']='Singapore';
	$skip_del_address=true;
	break;


      case(55957):
	$act_data['name']='Gopal Magic Moments';
	$act_data['contact']='Ashok Sood';
	$act_data['a1']='Corporate Office';
	$act_data['a2']='240 Okhla Industrial Estate Phase III';
	$act_data['postcode']='110020';
	$act_data['town']='New Delhi';
	$act_data['country']='India';
	$skip_del_address=true;
	break;
      case(60760):
	$act_data['name']='ASP';
	$act_data['contact']='Ron Jordan';
	$act_data['a1']='82 Tranwands Brigg';
	$act_data['a2']='Heelands';
	$act_data['postcode']='MK13 7PB';
	$act_data['town']='Milton Keynes';
	$skip_del_address=true;
	break;
      case(63524):
      case(62948):
      case(64321):
      case(67021):
      case(67627):
	$act_data['name']='Cadoworld';
	$act_data['contact']='Philippe Buchy';
	$act_data['a1']='116 Findon Street';
	$act_data['postcode']='S6 4QP';
	$act_data['town']='Sheffield';
	$act_data['email']='buchyp@yahoo.fr';
	$skip_del_address=true;
	break;
      case(26178):
	$act_data['name']='Incentive Ideas Ltd';
	$act_data['contact']='Nicola Standing';
	$act_data['a1']='Enterprise 5';
	$act_data['a2']='Five Lane Ends';
	$act_data['postcode']='BD10 8EW';
	$act_data['town']='Bradfrods';
	$skip_del_address=true;
	break;
      case(73674):
	$act_data['name']='Manse Jewekker';
	$act_data['contact']='Iman Sukhani';
	$act_data['a1']='245 Main Street';
	$act_data['town']='Gibraltar';
	$act_data['country']='Gibraltar';
	$act_data['email']='imansukhani@yahoo.com';
	$act_data['tel']='35077903';
	$skip_del_address=true;
	break;

      case(26701):
	$act_data['name']='Fortune by Alison.com';
	$act_data['contact']='Alison Alden';
	$act_data['a1']='23 York Road';

	$act_data['postcode']='NR30 2NA';
	$act_data['town']='Great Yarmouth';
	$act_data['tel']='01493331227';
	$skip_del_address=true;
	break;
      case(73106):
	$act_data['name']='Tresure Trove Wholesale';
	$act_data['contact']='Dave Sandy';
	$act_data['a1']='3 Westham Road';

	$act_data['postcode']='DT4 8NP';
	$act_data['town']='Weymouth';
	$skip_del_address=true;
	break;
      case(13231):
	$act_data['name']='AA Chivers';
	$act_data['a1']='1 Cherry Wood Grove';
	$act_data['a2']='Lightwood Road';
	$act_data['postcode']='ST3 7XL';
	$act_data['town']='Stoke on Trent';
	$skip_del_address=true;
	break;
      case(27406):
	$act_data['name']='Sue Jackson';
	$act_data['a1']='521 Littleworth Road';
	$act_data['postcode']='WS12 1JA';
	$act_data['town']='Cannock';
	$skip_del_address=true;
	break;
      case(26550):
	$act_data['name']='Lavender Laine Creations';
	$act_data['contact']='Mrs Maguire';
	$act_data['a1']='8 Ripley Street';

	$act_data['postcode']='HX3 8UA';
	$act_data['town']='Halifax';
	$act_data['tel']='01422208929';
	$skip_del_address=true;
	break;
      case(24182):
	$act_data['name']='Tim Shortland';
	$act_data['a1']='1 Haywood Avenue';
	$act_data['postcode']='S36 2QD';
	$act_data['town']='Sheffield';
	$act_data['tel']='01142882824';
	$skip_del_address=true;
	break;
      case(66002):
	// this is a foloe order
	$act_data['name']='Petit Cadeaux';
	$act_data['contact']='Rachel Mackenzie';
	$act_data['a1']='5/A Hutchinson Terrace';
	$act_data['postcode']='EH14 1QB';
	$act_data['town']='Edinburgh';
	$act_data['mob']='07852965703';
	$act_data['email']='xxbabiemackxx@hotmail.com';
	$skip_del_address=true;
	break;
      case(76253):
	$customer_id=insert_customer('NULL',array(7,1,2,3,11,10),$date_index,($this_is_order_number==1?true:false));
	return array(false,$customer_id,false,false,false,true,$co);
	$skip_del_address=true;
	break;
      case(39135):
	$act_data['name']=$staff_name;
	break;
      case(77175):
	$name="adriana";
	$staff_id=get_user_id($name,'' , '',false);
	$staff_data=get_staff_data($staff_id);
	$contact_id=$staff_data['contact_id'];
	$customer_id = $staff_data['customer_id'];
	$new_customer=false;
	return array($contact_id,$customer_id,false,false,false,$new_customer,$co);

	break;
      default:
	//print_r($act_data);
	exit("NO ACT DATzA\n");
      }

    }else
      exit("NO num_inv \n");
    
  
  }


  if(preg_match('/ancient wisdom/i',$act_data['name']) and $act_data['act']!='35871' ){
    // Pecial case of staff sales or show room sales
    //    print_r($act_data);
    //exit("aca");


    $name=_trim($act_data['contact']);
   
    if(preg_match('/^staff sale$/i',$name))
      $name=preg_replace('/staff sale/i','',$header_data['notes']);
       
    $staff_id=get_user_id($name,'' , '',false);



    if(count($staff_id)==1){
      
      $staff_id=$staff_id[0];
      if($staff_id==0){

	if(preg_match('/staff sale/i',$act_data['contact']) and $act_data['act']=='60057'){
	  $customer_id=insert_customer('NULL',array(9,7,1,2,3,11,10),$date_index,($this_is_order_number==1?true:false));
	  return array(false,$customer_id,false,false,false,true,$co);

	}
	exit("Mierda xxxx11\n");
      }

      $staff_data=get_staff_data($staff_id);
      //print_r($header_data);
      $contact_id=$staff_data['contact_id'];
      if(!$staff_data['customer_id']){
	$customer_id = insert_customer($contact_id,array(9,1,7,10),$date_index,($this_is_order_number==1?true:false));
	$new_customer=true;
      }else{
	$customer_id = $staff_data['customer_id'];
	$new_customer=false;
      }
      return array($contact_id,$customer_id,false,false,false,$new_customer,$co);
    }else


      exit("Mierda xxx12x\n");
    
  }

    


  if($act_data['name']=='Michelle A(aromatherapy,indian Head Massage Therap'){
    $act_data['name']='Michelle Angus';
  }

 if($act_data['name']=="'magpies'"){
    $act_data['name']='magpies';
  }

  if(preg_match('/Stinkers.*duglas laver/i',$act_data['name'])){
    $act_data['name']='Stinkers';
    if($act_data['contact']=='')
      $act_data['contact']='Duglas Laver';
  }

  // print_r($act_data);

  if(preg_match('/J.t Tools.*Mr.*a.*Hammans/i',$act_data['name'])){
    //   print "yyy\n";
    $act_data['name']='J&t Tools';
    if($act_data['contact']=='')
      $act_data['contact']='Anthiny Hammans';


  }






  $different_delivery_address=false;



  switch($header_data['order_num']){
      case(16339):

	$skip_del_address=true;
	$different_delivery_address=true;
	$header_data['address1']='C/O Frans Maas (UK) Ltd';
	$header_data['address2']='Timpson Road';
	$header_data['address3']='';
	$header_data['city']='Manchester';
	$header_data['postcode']='M23 9NT';
	$header_data['country']='UK';
	$act_data['a1']='Dynamidi 22';
	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town']='Salamina';
	$act_data['country_d2']='';
	$act_data['country_d1']='Attoka';
	$act_data['country']='Greece';
	$act_data['postcode']='18902';


	break;
    
  case(59470):
    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['address1']='46 Moorland Rise';
    $header_data['address2']='';
    $header_data['address3']='';
    $header_data['city']='Haslingden';
    $header_data['postcode']='BB4 6UA';
    $header_data['country']='UK';
    $act_data['contact']='Susan Sanchia';
    $act_data['name']='Crystal Man of Almeria';
    $act_data['a1']='Calle Gines Parra 0010';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='El Cucador';
    $act_data['country_d2']='Almeria';
    $act_data['country_d1']='Adalucia';
    $act_data['country']='Spain';
    $act_data['postcode']='04661';
    $act_data['tel']='';
    $act_data['fax']='';
    $act_data['mobile']='';
    $act_data['email']='';
    break;
  case(12891):
    $act_data['town']='Waterdown';
    $act_data['country_d2']='Ontario';
    $header_data['address1']='Global Aerospace M&M forwarding';
    $header_data['address2']='600 Main Street';
    $header_data['address3']='';
    $header_data['country_d2']='New York';
    $header_data['city']='Tonawanda';
    $header_data['postcode']='14151';
    $header_data['country']='USA';
    break;
 case(42804):
	$act_data['postcode']='CV3 2BD';
	$skip_del_address=true;
	break;

 case(37807):
	$act_data['postcode']='WS12 2GL';
	$act_data['town']='Cannock';
	$skip_del_address=true;
	break;
  case(21169):

	$act_data['a2']='';
	$act_data['a3']='';
	$act_data['town']='Oughterard';
	$act_data['country_d2']='Galway';
	$skip_del_address=true;
	   break;
  case(54503):
  case(52941):
  case(52712):
  case(49477):
  case(44644):
  case(44052):
  case(41321):
    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['address1']='15 Kestrel House';
    $header_data['address2']='';
    $header_data['address3']='';
    $header_data['city']='Farnham';
    $header_data['postcode']='GU9 8UY';
    $header_data['country']='UK';
    $act_data['contact']='Ms Pauline Murdock';
    $act_data['name']='Crystal Man of Almeria';
    $act_data['a1']='Calle Gines Parra 0010';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='El Cucador';
    $act_data['country_d2']='Almeria';
    $act_data['country_d1']='Adalucia';
    $act_data['country']='Spain';
    $act_data['postcode']='04661';
    $act_data['tel']='';
    $act_data['fax']='';
    $act_data['mobile']='';
    $act_data['email']='';
    break;
 case(42844):
   print "hols";
	$act_data['a2']="14 Gray's Inn Road";
	$skip_del_address=true;
	break;
  case(53600):
    $header_data['address1']='Viking Forwarders Inc';
    $header_data['address2']='Suite 5';
    $header_data['address3']='10800 NW - 103 Rd Street';
    $header_data['city']='Miami';
    $header_data['country_d2']='Florida';
    $header_data['postcode']='33178';
    $header_data['country']='USA';
    break;
 case(14175):
 case(12809):

    $header_data['country']='Spain';
    $act_data['town']='Olivia';
    $act_data['a3']='';

    $act_data['country_d2']='Valencia';
    $act_data['country_d1']='Valencia';
    $skip_del_address=true;
    break;
  case(52060):
  case(54502):
  case(56510):
    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['address1']='Swan Lodge';
    $header_data['address2']='Hassock Hill Drove';
    $header_data['address3']='';
    $header_data['city']='Gorefield';
    $header_data['postcode']='PE13 4QF';
    $header_data['country']='UK';
    $act_data['tel']='';
    $act_data['fax']='';
    $act_data['mobile']='';
    $act_data['email']='';
    $act_data['contact']='Ms Telma Pope';
    $act_data['name']='Crystal Man of Almeria';
    $act_data['a1']='Calle Gines Parra 0010';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='El Cucador';
    $act_data['country_d2']='Almeria';
    $act_data['country_d1']='Adalucia';
    $act_data['country']='Spain';
    $act_data['postcode']='04661';
    break;	

  case(18235):


    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['address1']='C/o Vitesse Courier Services';
    $header_data['address2']='Wraysbury House';
    $header_data['address3']='';
    $header_data['city']='Colnbrook';
    $header_data['postcode']='SL30AY';
    $header_data['country']='UK';
    $act_data['a1']='Unit 16 Eko Hotel';
    $act_data['a2']='Shopping Complex';
    $act_data['a3']='1 Ajoce Adeogun St';
    $act_data['town']='Victoria Island';
    $act_data['country']='Nigeria';
    $act_data['postcode']='23401';
    $act_data['country_d1']='Lagos State';
    break;	

  case(12636):
    $skip_del_address=true;
    $act_data['a1']='Leoforos Salaminas 103';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Salamina';
    $act_data['country_d2']='';
    $act_data['country_d1']='Attoka';
    $act_data['country']='Greece';
    $act_data['postcode']='18900';
    break;
  case(8192):

  case(13577):

    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['address1']='C/O Frans Maas (UK) Ltd';
    $header_data['address2']='Timpson Road';
    $header_data['address3']='';
    $header_data['city']='Manchester';
    $header_data['postcode']='M23 9NT';
    $header_data['country']='UK';
    $act_data['a1']='Leoforos Salaminas 103';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Salamina';
    $act_data['country_d2']='';
    $act_data['country_d1']='Attoka';
    $act_data['country']='Greece';
    $act_data['postcode']='18900';


    break;
 case(46812):
 $header_data['postcode']='NR3 1UA';
 $header_data['city']='Norwich';
$header_data['country_d1']='Norfolk';
$header_data['address3']='';

 break;

  case(28867):
 $act_data['a1']='N. Papanikolaou 6';
 case(43870):
    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['address1']='C/O Frans Maas (UK) Ltd';
    $header_data['address2']='Timpson Road';
    $header_data['address3']='';
    $header_data['city']='Manchester';
    $header_data['postcode']='M23 9NT';
    $header_data['country']='UK';
    $act_data['a1']='Petrou Fouriki & N. Papanikolaou 6';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Salamina';
    $act_data['country_d2']='';
    $act_data['country_d1']='Attoka';
    $act_data['country']='Greece';
    $act_data['postcode']='18900';
    break;	
  case(20022):
    $skip_del_address=true;
    $act_data['a1']='Unit 16 Eko Hotel';
    $act_data['a2']='Shopping Complex';
    $act_data['a3']='1 Ajoce Adeogun St';
    $act_data['town']='Victoria Island';
    $act_data['country']='Nigeria';
    $act_data['postcode']='23401';
    $act_data['country_d1']='Lagos State';
    break;
  case(57653): $skip_del_address=true;

    $act_data['town']='Lagos';
    $act_data['country_d1']='Lagos State';
    break;
  case(22279):
  case(42170):
    $skip_del_address=true;
    $act_data['a1']='Gra 11';
    $act_data['a2']='109 Woji Rd';
    $act_data['a3']='';
    $act_data['town']='Port Harcourt';
    $act_data['country']='Nigeria';
    $act_data['postcode']='41130';
    $act_data['country_d1']='Rivers State';
    break;	
  case(24811):
    $skip_del_address=true;
    $act_data['a2']='';
    $act_data['town']='Dawson Creek';
    $act_data['country_d2']='British Columbia';
    break; 
  case(60516):
    $skip_del_address=true;
    $act_data['a2']='';
    $act_data['town']='North Bay';
    $act_data['country_d2']='Ontario';
    break;
  case(60679):
    $skip_del_address=true;
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Chillwack';
    $act_data['country_d2']='British Columbia';
    break;

  case(8837):
    $skip_del_address=true;
    $different_delivery_address=true;
    $act_data['a1']='';
    $act_data['a2']='Unit 12';
    $act_data['a3']='Old Clarendon Dye Works';
    $act_data['town']='Leicester';
    $act_data['postcode']='LE2 6AR';
    $act_data['mob']='';
    $act_data['tel']='';
    break;

  case(53921):
    $skip_del_address=true;

    $act_data['town']='Ottawa';
    $act_data['country_d2']='Ontario';
    break;


  case(35020):
    $skip_del_address=true;
    $act_data['a1']='App 3';
    $act_data['a2']='80 Rue Carrier';
    $act_data['a3']='';
    $act_data['town']='Lévis';
    $act_data['country_d2']='Québec';
    break;
  case(34266):
    $skip_del_address=true;
    $act_data['a2']='';
    $act_data['town']='Qualicum';
    $act_data['country_d2']='British Columbia';
    $act_data['postcode']='V9K1T2';
    break;
    
  case(24864):
    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['address1']='PO Box 56866';
    $header_data['city']='Limassol';
    $header_data['postcode']='CY3310';
    $header_data['country']='Cyprus';
    
    break;	
    
  case(8192):
    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['address1']='C/O Frans Maas (UK) Ltd';
    $header_data['address2']='Timpson Road';
    $header_data['address3']='';
    $header_data['city']='Manchester';
    $header_data['postcode']='M23 9NT';
    $header_data['country']='UK';
    $act_data['a1']='Leoforos Salaminas 103';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Salamina';
    $act_data['country_d2']='';
    $act_data['country_d1']='Attoka';
    $act_data['country']='Greece';
    $act_data['postcode']='18900';
    break;
  case(71271):
    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['country']='Italy';
    $header_data['city']='Mestre';
    $header_data['postcode']='30174';
    $header_data['country_d1']='Veneto';
    $header_data['country_d2']='Venezia';
    $act_data['a1']='C/o CC Futura2';
    $act_data['a2']='Via Chiesanuova 71';
    $act_data['country_d1']='Veneto';
    $act_data['country_d2']='Padova';

    break;
  case(71286):

    $skip_del_address=true;
    $act_data['a1']='68 The Glade';
    $act_data['town']='Athenry';
    $act_data['country_d2']='Co Galway';

    break;


  case(7772):
  case(6979):
  case(8073):
    $skip_del_address=true;
    $act_data['a1']='111 S Central Avenue';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Okmulgee';
    $act_data['country_d2']='Oklahoma';
    $act_data['country_d1']='';
    $act_data['country']='USA';
    $act_data['postcode']='74447';
    break;

  case(7941):
  case(7439):
    $skip_del_address=true;
    $act_data['a2']='Line E, Block 7';
    $act_data['a1']='Ijeh Police Barracks';
    $act_data['a3']='';
    $act_data['town']='Ikoyi';
    $act_data['country_d2']='Lagos Island';
    $act_data['country_d1']='Lagos State';
    $act_data['country']='Nigeria';
    $act_data['postcode']='23401';
    break;

  case(8557):
    $skip_del_address=true;
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Mushin';
    $act_data['country_d2']='Mushin';
    $act_data['country_d1']='Lagos State';
    $act_data['country']='Nigeria';
    $act_data['postcode']='23401';
    break;


  case(15952):
  case(14965):
    $skip_del_address=true;
    $act_data['a1']='10 Robert Memorial Drive';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Clinton';
    $act_data['country_d2']='Massachusetts';
    $act_data['country_d1']='';
    $act_data['country']='USA';
    $act_data['postcode']='01510';
    break;
  case(21453):
    $skip_del_address=true;
    $act_data['a1']='Hyland Plaza';
    $act_data['a2']='2152 South Highland Drive';
    $act_data['a3']='';
    $act_data['town']='Salt Lake City';
    $act_data['country_d2']='Utah';
    $act_data['country_d1']='';
    $act_data['country']='USA';
    $act_data['postcode']='84106';
    break;
  case(23707):
    $skip_del_address=true;
    $act_data['country']='Netherlands';

    break;

  case(34145):
    $skip_del_address=true;
    $act_data['country']='USA';

    break;
  case(40809):
  case(48464):
    $skip_del_address=true;
    $act_data['country']='Spain';
    $act_data['postcode']='03189';
    break;


  case(34148):
  case(34689):
    $skip_del_address=true;
    $act_data['country']='USA';
    $act_data['town']='Alexandria';
    $act_data['country_d2']='Minnesota';
    $act_data['a2']='';
    $act_data['a3']='';
    break;

  case(57927):
    $skip_del_address=true;
    $act_data['country']='USA';
    break;
  case(8643):
    $skip_del_address=true;
    $act_data['country']='Belgium';
    $act_data['country_d2']='';
    break;
 case(41297):
    $header_data['country']='UK';

    break;
  case(64129):
    $skip_del_address=true;
    $act_data['country']='Spain';
    $act_data['town']='Jávea';
    $act_data['country_d1']='Valencia';
    $act_data['country_d2']='Alicante';
    $act_data['a2']='';
    $act_data['a1']='Calle J. Reynolds 3/452';
    break;
  case(23540):
  case(37184):
    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['country']='Finland';
    $header_data['city']='Kärrby';
    $header_data['country_d1']='';
    $header_data['country_d2']='';
    $header_data['address1']='Svartbäcksvägen 144';
    $header_data['address2']='';
    $header_data['address3']='';
    $header_data['postcode']='06880';

  case(37599):
    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['country']='United Kingdom';
    $header_data['city']='Orpington';
    $header_data['country_d1']='';
    $header_data['country_d2']='Kent';
    $header_data['address1']='86 Glentrammon Road';
    $header_data['address2']='';
    $header_data['address3']='';
    $header_data['postcode']='BR6 6DG';
    break;
  case(37661):
    $skip_del_address=true;
    $different_delivery_address=true;
    $act_data['a1']='38 Wellgate';
    $act_data['country_d1']='';
    $act_data['country_d2']='';
    $header_data['country']='United Kingdom';
    $header_data['city']='Lanark';
    $header_data['country_d1']='';
    $header_data['country_d2']='';
    $header_data['address1']='44 Wellgate';
    $header_data['address2']='';
    $header_data['address3']='';
    $header_data['postcode']='ML11 9DT';
    break;

  case(37445):
    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['country']='Norway';
    $header_data['city']='Straume';
    $header_data['country_d1']='';
    $header_data['country_d2']='';
    $header_data['address1']="Wenche's Heste-Og Hundemassasje";
    $header_data['address2']='';
    $header_data['address3']='Postboks 407';
    $header_data['postcode']='5343';


    break;
  case(75371):

   $header_data['country']='UK';
    break;

  case(75020):
  case(75168):
 $different_delivery_address=true;
    break;
  case(75138):
$header_data['city']='Douglas';
     $header_data['address3']='';
    $header_data['address2']='';
    break;
 case(62926):
    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['country']='Spain';
    $header_data['city']='Triquivijate';
    $header_data['country_d1']='Islas Canarias';
    $header_data['country_d2']='Provincia de Las Palmas';
    $header_data['address3']='';
    break;
  case(74523):
   $header_data['city']='Teguise';
    $header_data['country_d1']='Islas Canarias';
    $header_data['country_d2']='Provincia de Las Palmas';
    $header_data['address3']='';
    $header_data['address2']='';

      $header_data['country']='Spain';
   $header_data['postcode']='35558';
    break;
  case(25192):
  case(33201):
    $skip_del_address=true;
    break;

  case(7759):
  case(7577):
    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['country']='UK';
    $header_data['city']='Edinburgh';

    $header_data['address2']='';
    $header_data['address3']='';
    break;

  case(71485):
    $skip_del_address=true;
    // $different_delivery_address=true;
    $header_data['country']='Spain';
    break;
    //  case(71923):
    //     $skip_del_address=true;
    //     $different_delivery_address=true;
    //     $header_data['country']='France';
    //     $header_data['address2']='';
    //     $header_data['address3']='';
    //     $header_data['address1']='Quenequen';
    //     $header_data['city']='Scrignac';
    //     $header_data['postcode']='France';
    //    break;
  case(32288):
    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['country']='Spain';
    $header_data['address2']='';
    $header_data['address3']='';
    $header_data['address1']='CallaeMolins 22';
    $header_data['city']='Majorca';
    $header_data['postcode']='07560';

	
    break;

  case(22340):
  case(23385):
    $skip_del_address=true;
    $act_data['a1']='1779 Vermont Drive';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Elk Grove Village';
    $act_data['country_d2']='Illinois';
    $act_data['country_d1']='';
    $act_data['country']='USA';
    $act_data['postcode']='60007';
    break;
  case(14571):

    $act_data['a1']='Grange';
    $act_data['postcode']='';
    $act_data['town']='Kilmore';
    $act_data['country_d2']='Co Wexford';
    $act_data['country']='Ireland';
    $skip_del_address=true;
    break;

  case(24193):
  
    $act_data['a1']='28 Hodder Lane';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Framingham';
    $act_data['country_d2']='Massachusetts';
    $act_data['country_d1']='';
    $act_data['country']='USA';
    $act_data['postcode']='01701';
    $header_data['address1']='1 Central Street';
    $header_data['address2']='';
    $header_data['address3']='';
    $header_data['city']='Framingham';
    $header_data['postcode']='01701';
    $header_data['country']='USA';
    $header_data['country_d2']='Massachusetts';
    break;
  case(76804):
    $skip_del_address=true;

    $act_data['a2']='Petersfield Road';
    $act_data['a3']='';

    break;

  case(49012):
    $skip_del_address=true;
    $act_data['a1']='6708 Foothill Blvd';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Tujunga';
    $act_data['country_d2']='California';
    $act_data['country_d1']='';
    $act_data['country']='USA';
    $act_data['postcode']='91042';
    break;
  case(49012):
    $skip_del_address=true;
    $act_data['a1']='6708 Foothill Blvd';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Tujunga';
    $act_data['country_d2']='California';
    $act_data['country_d1']='';
    $act_data['country']='USA';
    $act_data['postcode']='91042';
    break;

  case(7699):
    $skip_del_address=true;
    $act_data['a1']='Flat 51';
    $act_data['a2']='Sina 20';
    $act_data['a3']='';
    $act_data['town_d1']='Engomi';
    $act_data['town']='Nicosia';
    $act_data['postcode']='2406';
    break;
  case(23335):
    $skip_del_address=true;
    $act_data['town']='Plainfield';
    $act_data['postcode']='46168';
    $act_data['country_d2']='In';
    break;
  case(30218):
  case(27832):
  case(35721):
  case(51911):
  case(70492):
  case(78129):
    $skip_del_address=true;
 
    $act_data['a1']='PO BOX 32112';
    $act_data['a2']='Galleria Plaza';
    $act_data['a3']='';
    $act_data['town']='Seven Mile Beach';
    $act_data['country_d1']='';
    $act_data['postcode']='';
    $act_data['country']='Cayman Islands';

    break;
  case(30532):
    $skip_del_address=true;
 
    $act_data['a1']='Calle Doña Romera No1 1oC ';
    $act_data['a2']='Getafe';
    $act_data['a3']='';
    $act_data['town']='Madrid';
    $act_data['postcode']='28901';
    $act_data['country']='Spain';

    break;

  case(30597):
    $skip_del_address=true;
 
    $act_data['town']='Pescia';
    $act_data['a2']='';
    $act_data['country_d1']='Tuscany';
    $act_data['country_d2']='Pistoia';
    break;

  
  case(23335):
    $skip_del_address=true;
    $act_data['a1']='725 Normandy Dr';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Euless';
    $act_data['country_d2']='Texas';
    $act_data['country_d1']='';
    $act_data['country']='USA';
    $act_data['postcode']='76039';
    break;
  case(21453):
    $skip_del_address=true;
    $act_data['a1']='Hyland Plaza';
    $act_data['a2']='2152 South Highland Drive';
    $act_data['a3']='';
    $act_data['town']='Salt Lake City';
    $act_data['country_d2']='Utah';
    $act_data['country_d1']='';
    $act_data['country']='USA';
    $act_data['postcode']='84106';
    break;
  case(20736):
    $skip_del_address=true;
    $act_data['a1']='3065 Saint James Drive';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Boca Raton';
    $act_data['country_d2']='Florida';
    $act_data['country_d1']='';
    $act_data['country']='USA';
    $act_data['postcode']='33434';
    break;
  case(19802):
    $skip_del_address=true;
    $act_data['a1']='15 View Drive';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Miller Place';
    $act_data['country_d2']='New York';
    $act_data['country_d1']='';
    $act_data['country']='USA';
    $act_data['postcode']='11764';
    break;
  case(21262):
    $skip_del_address=true;
    $act_data['a1']='Po Box 1047';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Townsville';
    $act_data['country_d2']='';
    $act_data['country_d1']='';
    $act_data['country']='Australia';
    $act_data['postcode']='4810';
    break;
  case(24400):
    $skip_del_address=true;
    $act_data['a1']='Rua de Angloa 36-2 (Esq)';
    $act_data['postcode']='4430-014';
    $act_data['town']='Vila Nova de Gaia';
    break;

  case(7173):
    $skip_del_address=true;
    $act_data['a1']='';
    break;
  case(14622):
    $skip_del_address=true;
    $act_data['a1']='12 Forest Dale';
    $act_data['a2']='Rivervalley';
    $act_data['a3']='';
    $act_data['town']='Swords';
    $act_data['country_d2']='Fingal';
    $act_data['country_d1']='';
    $act_data['country']='Ireland';
    $act_data['postcode']='';
    break;
  case(31843):
    $skip_del_address=true;
    $act_data['town']='Conley';
    $act_data['country_d2']='GA';
    $act_data['country']='USA';
    $act_data['postcode']='30288';
    break;
  case(68623):
    $skip_del_address=true;
    $act_data['a1']='Suite 51';
    $act_data['a2']='7 Essex Green Drive';
    $act_data['town']='Peabody,';
    $act_data['country_d2']='MA';
    $act_data['country']='USA';
    $act_data['postcode']='01960';
    break;
  case(8363):
    $skip_del_address=true;
    $act_data['country']='USA';

    break;

  case(22478):
    $skip_del_address=true;
    $act_data['postcode']='';
    break;

  case(70358):
  case(72026):
    $skip_del_address=true;
    $act_data['town']='Pittsfield';
    $act_data['country_d2']='MA';
    $act_data['country']='USA';
    $act_data['postcode']='01201';
    break;
  case(30211):
    $skip_del_address=true;
    $act_data['town']='Campbell'; 
    $act_data['country_d2']='California';
    $act_data['country']='USA';
    break;

  case(23293):
    $skip_del_address=true;
    $act_data['a1']='4544 Alhambra St';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='San Diego';
    $act_data['country_d2']='Texas';
    $act_data['country']='USA';
    $act_data['postcode']='92107';
    break;
  case(8558):
    $skip_del_address=true;
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Rocklin';
    $act_data['country_d2']='California';
    $act_data['country']='USA';

    break;
  case(68689):
    $skip_del_address=true;
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Bloomfield';
    $act_data['country_d2']='New Jersey';
    $act_data['country']='USA';

    break;

  case(64689):
    $skip_del_address=true;

    $act_data['country_d1']='French Polynesia';
    $act_data['country']='France';

    break;
  case(73721):
    $skip_del_address=true;
    $act_data['a1']='';
    $act_data['a2']='Camelot Longue Rue Clos';
    $act_data['a3']='Longue Rue, Burnt Lane';
    $act_data['town']='St Martins';

    $act_data['country_d2']='';
    $act_data['country_d1']='';
    $act_data['country']='Guernsey';
    $act_data['postcode']='GY4 6HE';
    

    break;

  case(63376):
    $skip_del_address=true;
    $act_data['country']='UK';

    

    break;
  case(25079):
  case(16597):

    $skip_del_address=true;
    $act_data['country']='Ireland';
    break;


  
  case(36910):
    $skip_del_address=true;
    $act_data['a1']='Calle Desamparados 5';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Denia';
    $act_data['country_d2']='Alicante';
    $act_data['country_d1']='Valencia';
    $act_data['country']='Spain';
    $act_data['postcode']='03700';
    break;
  case(7054):
    $skip_del_address=true;
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Tonsberg';
    $act_data['postcode']='3120';
    break;
  case(61835):
    $skip_del_address=true;
    $act_data['a2']='';
    $act_data['town']='Ylistaro';
    break;
  case(52273):
    $skip_del_address=true;
    $act_data['a2']='';
    $act_data['town_d2']='Daeya-dong';
    $act_data['town']='Siheung-si';
    $act_data['country_d1']='Gyeonggi-do';
    break;
  case(52598):
     $act_data['country']='UK';
  case(37556):
    $skip_del_address=true;
    $act_data['a2']='Stadhoudershof 6';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Harmelan';
    $act_data['country']='Netherlands';
    $act_data['postcode']='3481HT';
    $skip_del_address=true;
    break;
  case(73612):
  case(76735):
    $skip_del_address=true;
    $act_data['postcode']='';
    $act_data['country_d1']='Curacao';
    
    break;
  case(61801):
    $skip_del_address=true;
    $act_data['postcode']='24076';
    $act_data['country_d1']='Virginia';
    $act_data['town']='Claudville';
    $act_data['a2']='';
    $act_data['a3']='';
    break;
  case(8660):
    $skip_del_address=true;
    $act_data['a2']='Llanfair Talhaiarn';

    break;

  case(76925):
    $skip_del_address=true;
    
    $act_data['town']='Westbury';
    $act_data['a1']='Unit C-3';
    $act_data['a2']='Z10900 609 Cantiague Rock Rd';
    $act_data['country_d1']='NY';
    break;
  case(35072):
    $skip_del_address=true;
    $act_data['postcode']='IV17 0QG';
    break;
  case(13908):
    $skip_del_address=true;
    $act_data['postcode']='NE63 5QW';
    $act_data['a2']='Rotary Parkway';
    break;
  case(35142):
    $skip_del_address=true;
    $act_data['postcode']='123812';
    break;
  case(43769):
    $skip_del_address=true;
    $act_data['postcode']='PE31 7QU';
    break;
  case(62196):
    $skip_del_address=true;
    $act_data['country_d1']='New South Wales';
    $act_data['town']='Bilambil Heights';
    $act_data['a2']='';
    $act_data['a3']='';
    break;
 case(71271):
$header_data['country']='Italy';
 break;
  case(30141):
    $act_data['country']='Lebanon';
    $act_data['town']='Beirut';
    $act_data['a1']='3rd Floor';
    $act_data['a2']='164 Debbas Street';
    $act_data['a3']='';
    $act_data['town_d1']='Saifi';
    $act_data['postcode']='';
    	$skip_del_address=true;
	$different_delivery_address=true;
	$header_data['address2']='Ballafletcher Cottage';
	$header_data['address1']='NULL';
	$header_data['address3']='Peel Road';
	$header_data['city']='Braddan';
	$header_data['postcode']='IM4 4LD';
	$header_data['country']='Isle of Man';
    break;
  case(28500):
  case(36619):
    $skip_del_address=true;
    $act_data['country']='Lebanon';
    $act_data['town']='Beirut';
    $act_data['a1']='3rd Floor';
    $act_data['a2']='164 Debbas Street';
    $act_data['a3']='';
    $act_data['town_d1']='Saifi';
    $act_data['postcode']='';
    break;

   case(69405):
    $act_header['postcode']='GU12 4TD';
    break;
  case(71271):
     $act_header['country']='Italy';
    break;
  case(71485):
$act_act['country']='Spain';
    break;
  case(70511):
  case(7165):
  case(53811):
  case(8294):
  
  case(8411):
  case(12819):
  case(13954):
  case(16687):
  case(26028):
  case(72146):
  case(46797):
  case(35803):
  case(33867):
  case(28966):
  case(65549):
  case(52964):
  case(44185):
  case(39120):
  
  case(33821):
    
  case(69305):
    
  case(72754):
  case(68024):
  case(8877):
  
  case(6925):
  case(7272):
  case(7427):
  case(8370):
  case(9448):
  
  case(13003):
  case(53841):
  case(46248):
  case(47855):
  case(25079):
  case(36511):
  case(7414):
  
  case(7650):
  case(8441):
  case(8442):
  case(8503):
  case(8629):
  case(8664):
  case(7650):
  case(8044):
  case(8441):
  case(8442):
  case(8503):
  case(8703):
  case(8745):
  case(8906):
  case(7065):
  case(8061):
  case(12486):
  case(12864):
  case(13075):
  case(13317):
  case(13335):
  case(13598):
  case(14209):
  case(14308):
  case(14512):
  case(14650):
  case(15575):
  case(15805):
  case(15828):
  case(17573):
  case(17831):
  case(7225):
  case(16519):
  case(16486):
  case(16569):
  case(16877):
  case(18746):
  case(19563):
  case(19615):
  case(19652):
  case(19685):
  case(20201):
  case(20357):
  case(21422):
  case(21625):
  case(21915):
  case(22067):
  case(22362):
  case(22731):
  case(22818):
  case(22887):
  case(22906):
  case(22907):
  case(22929):
  case(22998):
  case(23246):
  case(23357):
  case(23579):
  case(23712):
  case(23756):
  case(12847):
  case(14261):
  case(17130):
  case(18010):
  case(19576):
  case(31020):
  case(31205):
  case(31371):
  case(31556):
  case(31745):
  case(32178):
  case(32289):
  case(32345):
  case(32606):
  case(33381):
  case(33771):
  case(35731):
  case(36867):
  case(37782):
  case(39441):
  case(39573):
  case(41943):
 case(45804):
 case(45914):
     case(50950):
  case(51025):
 case(51256):
case(52331):
case(52364):
case(53138):
  case(53384):
 case(54343):
 case(55154):
 case(55256):
 case(55566):
 case(57096):
case(59588):
case(59842):
case(47051):
case(29145):
  case(47825):
  case(15593):
  case(24266):
  case(25214):
 case(25305):
  case(25375):
  case(25977):
 case(25977):
  case(29392):
  case(29550):
    case(29710):
  case(29710):
case(30587):
case(30763):
case(31049):
  case(31677):
  case(33216):
case(33246):
case(33531):
  case(34812):
    case(35098):
      case(78012):
  case(69453):
 case(16121):
 case(36842):
 case(71485):
  case(64945):
  case(75294):
 case(76777):
 case(78383):
  case(78294):
  case(25453):
case(28990):

$skip_del_address=true;
   break;
  case(71485):
$skip_del_address=true;
 $act_data['country']='Spain';
 break;
case(35467):
case(44604):
  case(45817):
  case(50532):
  case(53698):
  case(53698):
  case(62036):
  case(73120):
$skip_del_address=true;
 $act_data['country']='Sweden';
 $act_data['postcode']='52495';
 $act_data['town']='Ljung';
 break;
  case(19937):
$skip_del_address=true;
 $act_data['country']='USA';
 $act_data['postcode']='55040';
 $act_data['town']='Isanti';
 $act_data['country_d1']='Minnesota';
 break;
 case(25712):
$skip_del_address=true;
 $act_data['country']='USA';
 $act_data['a2']='';

 $act_data['town']='Mililani';
 $act_data['country_d1']='Hawaii';
 break;


 case(74033):
  case(73998):
  case(74624):
$skip_del_address=true;
 $act_data['country']='USA';
 $act_data['postcode']='09142';
 $act_data['town']='APO, AE';
 break;


 case(64045):
  case(73723):

$skip_del_address=true;
 $act_data['a1']='34/1 Dolmen Court';
 $act_data['a2']='Dolmen Street';

 break;


  case(24771):
  
    $header_data['country']='UK';
 break;
 case(60300):
  $skip_del_address=true;
    $act_data['a1']='34 St Paul Street';
  break;
  case(37313):
    $act_date['a1']='Local 02';
    $act_date['a2']='Calle Coso 35';
    $act_date['address1']='N-2 Local 5';
    $act_date['address2']='Plaza Jose Maria Forquet';
    break;
  case(37966):
  case(39228):
     $act_date['address1']='N-2 Local 5';
    $act_date['address2']='Plaza Jose Maria Forquet';
    break;
case(71983):
  $skip_del_address=true;
$act_data['a1']='Casa 50';
 $act_data['a2']='Calzada del Hueso 151 ';
 $act_data['a3']='';
$act_data['town']='Mexico City';
$act_data['town_d1']='Coyoacan';
$act_data['town_d2']='Ex-Hacienda Coapa';
$act_data['country_d1']='Distrito Federal';
$act_data['country_d2']='';

 break;



 case(37105):
$skip_del_address=true;
 $act_data['a2']='';
 $act_data['a3']='';
 $act_data['postcode']='22';
 $act_data['town']='Dublin';
    break;
 case(39363):
$skip_del_address=true;
 $act_data['a2']='408 The Spa';
    break;
  case(76426):
   $skip_del_address=true;
    $act_data['postcode']='NR33 8NX';
    break;
 case(34947):
    $header_data['country']='UK';
    $header_data['postcode']='Bh15 3as';
     $header_data['address3']='';
    $header_data['city']='Poole';
    break;
   case(25885):
    $skip_del_address=true;
    $act_data['a2']='Stratham High Road';
    break;
  case(48227):
    $skip_del_address=true;
    $act_data['a1']='29-33 Newton Road';
    break;
   case(56219):
    $skip_del_address=true;
    $act_data['town']='Lerwick';
    break;
  case(56689):

    $header_data['country']='United Kingdom';
    break;
 case(51029):
    $skip_del_address=true;
    $act_data['a1']='Bulwark Shopping Centre';
    break;
  case(42294):
    $skip_del_address=true;
    $act_data['a1']='14 Peveril Street';
    break;
  case(51360):
    $skip_del_address=true;
    $act_data['a1']='Glebe Barn';
    break;
  case(51731):
    $skip_del_address=true;
    $act_data['a1']='Hay Castle';
    $act_data['a2']='Oxford Road';
    break;
      case(50680):
    $skip_del_address=true;
    $act_data['a1']='4 Castlefin Road';
    break;
case(46636):
    $skip_del_address=true;
    $act_data['a1']='47 Nightingales Drive';
    break;
  case(33181):
    $skip_del_address=true;
    $act_data['postcode']='BS4 3QF';
    break;
  case(33181):
    $header_data['postcode']='ML2 0RR';
    break;
  case(43858):
    $skip_del_address=true;
    $header_data['city']='Zagreb';
    $header_data['postcode']='10020';
    $header_data['address2']='';
    $header_data['address3']='';
    $header_data['address1']='Siget 18C';

    break;

  case(20188):
    $skip_del_address=true;
    $act_data['a1']='1a Jubilee Terrace';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town_d2']='Crawcrook';
    $act_data['town']='Newcastle';
    $act_data['postcode']='NE40 4HL';

    break;
  case(16885):
    $skip_del_address=true;
    $act_data['a1']='Ellon Indoor Market';
    $act_data['a2']='71 Station Road';
    $act_data['a3']='';
    $act_data['town']='Ellon';
    $act_data['postcode']='AB41 9AR';

    break;
  case(19821):
    $skip_del_address=true;
    $act_data['a1']='3a Tudor Parade';
    $act_data['a2']='Berry Lane';
    $act_data['a3']='';
    $act_data['town']='Rickmansworth';
    $act_data['postcode']='WD3 4DF';

    break;
  case(16847):
    $skip_del_address=true;
    $act_data['postcode']='BT28 1TR';
    break;
  case(77342):
    $skip_del_address=true;
    $act_data['a1']='PO BOX 21';
    $act_data['a2']='30th Km Athens-Lavrio NTL Rd.';
    break;
  case(7912):
     $header_data['country']='UK';
    break;
  case(32329):
    $skip_del_address=true;
    $act_data['postcode']='EX39 2DX';
    break;
  case(28867):
 $act_data['a1']='N. Papanikolaou 6';
 case(43870):
  case(19295):
    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['address1']='C/O Frans Maas (UK) Ltd';
    $header_data['address2']='Timpson Road';
    $header_data['address3']='';
    $header_data['city']='Manchester';
    $header_data['postcode']='M23 9NT';
    $header_data['country']='UK';
    $act_data['a1']='Petrou Fouriki & N. Papanikolaou 6';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Salamina';
    $act_data['country_d2']='';
    $act_data['country_d1']='Attoka';
    $act_data['country']='Greece';
    $act_data['postcode']='18900';
    break;
  case(13086):
  case(13906):

    $skip_del_address=true;
    $act_data['postcode']='L12 0QY';
    break;
  case(16153):
    $skip_del_address=true;
    $act_data['town']='Castlederg';
    $act_data['postcode']='BT81 7AT';
    break;
  case(15996):
    $skip_del_address=true;
    $act_data['postcode']='HP13 6AZ';
    break;
  case(16002):
    $skip_del_address=true;
    $act_data['postcode']='HX1 3UZ';
    break;
  case(16033):
    $skip_del_address=true;
    $act_data['postcode']='G4 0TT';
    break;
  case(14040):
    $skip_del_address=true;
    $act_data['postcode']='LE10 1NL';
    break;
  case(18435):
    $skip_del_address=true;
    $act_data['postcode']='NG24 1UD';
    break;

  case(13086):
    $skip_del_address=true;
    $act_data['postcode']='S8 8AD';
    $act_data['town']='Sheffield';
    $act_data['a1']='NULL';
    break;

  case(8629):
    $skip_del_address=true;
    $act_data['postcode']='BN1 4AZ';

    break;
  case(8044):
    $skip_del_address=true;
    $act_data['country_d1']='Tipperary';
    $act_data['country_d2']='';

    break;
  case(70883):
    $skip_del_address=true;
    $act_data['a1']='10 Rue Bartholmy';
    $act_data['town']='Howald';
    break;
  case(51426):
  case(54164):
  case(64439):

    $skip_del_address=true;
    $act_data['a3']='';
    $act_data['a2']='';
    $act_data['town']='Tandragee';
    break;
  case(17445):
  case(18545):
  case(18792):
  case(19291):

  case(32318):
  case(20756):

    $skip_del_address=true;
    $act_data['town']='Emmen';
    $act_data['postcode']='7823PM';
    break;
  case(70819):
    $skip_del_address=true;
    $act_data['a2']='';
    $act_data['town']='Spijkenisse';
    $act_data['country_d1']='Zuid-Holland';
    break;

  case(70731):
    $skip_del_address=true;
    $act_data['a1']='Ashford';
    $act_data['town']='Ballagh';
    $act_data['country_d2']='Limerick';
    break;
  case(44600):
  case(46505):
  case(50287):
  case(70138):
  case(71302):
  case(73580):
  case(75313):
    $skip_del_address=true;
    $act_data['town']='Algarve';
    $act_data['country_d2']='';
    $act_data['postcode']='495-8400';


    break;
   case(61118):
    $skip_del_address=true;
    $co='Hotel Garbe';
    break;
  case(70582):
    $skip_del_address=true;
    $act_data['a1']='P2 Casa 4 Urb Calas Picas';
    $act_data['a2']=' Av Pont Den Gil';
    $act_data['country_d1']='Balearic Islands';
    $act_data['country_d2']='Balearic Islands';
    $act_data['town']='Ciutadella';
    break;

  case(72265):
    $skip_del_address=true;
    $act_data['country']='Australia';
    $act_data['country_d1']='Western Australia';
    $act_data['a1']='Unit 4';
    $act_data['a2']='39 Shakespeare Avenue';
    $act_data['a3']='';
    $act_data['town']='Yokine';
    break;
  case(71925):
    $act_data['country_d2']='';
    BREAK;
  case(18454):
    $act_data['country']='Saudi Arabia';
    break;
case(62426):
  $skip_del_address=true;
  $act_data['country']='Spain';
  $act_data['town']='Marbella';
  $act_data['a3']='';

    break;
  case(72737):
    $skip_del_address=true;
    $act_data['country_d1']='Midi-Pyrénées';
    $act_data['country_d2']='Tarn';
    $act_data['town']='Soreze';
    $act_data['a2']='';

    break;

  case(60909):
    $skip_del_address=true;
    $act_data['a2']='';
    $act_data['town']='Voru';
    $act_data['postcode']='65610';
    break;

  case(35006):
    $skip_del_address=true;
    $act_data['a1']=' 52 High Street';

    break;


  case(27966):
    $skip_del_address=true;
    $act_data['a2']='';
    $act_data['town']='New Windsor';
    $act_data['country_d2']='NY';
    $act_data['country']='USA';
    break;

  case(61990):
  case(62814):
$act_data['postcode']='G31 5NZ';
 $act_data['country_d2']='Lanarkshire';
 $act_data['country_d1']='';

 $skip_del_address=true;
    break;

  case(29948):
    $act_data['town']='Stillorgan';
    $skip_del_address=true;
    break;
  case(65336):
    $act_data['town']='Totland Bay';
     $act_data['country_d1']='Isle of Wight';
    $skip_del_address=true;
    break;
  case(68277):
    $act_data['town']='Merthyr Tydfil';
    
     $act_data['country_d1']='';
    $skip_del_address=true;
    break;
 case(68830):
    $header_data['postcode']='4051';
    $header_data['city']='Sousse';
     $header_data['country']='Tunisia';
$header_data['address3']='Khazama Est';
    break;
 case(75064):
  case(75838):
  case(75954):
case(77065):
  case(77502):
$act_data['a1']='16 Market Square';
$act_data['a2']='';
 $act_data['a3']='';
$act_data['town']='Enniscorthy';
 $act_data['postcode']='';
$header_data['country_d2']='Wexford';
$header_data['country_d1']='';
$skip_del_address=true;
   break;
  case(54756):
$act_data['a1']='Suite 2000';
$act_data['a2']='';
 $act_data['a3']='1200 Route 22 East';
$act_data['town']='Bridgewater';
 $act_data['postcode']='08807';
$act_data['country_d1']='NY';
$act_data['country']='USA';
$skip_del_address=true;
   break;
 case(60522):
$act_data['a1']='';
$act_data['a2']='Apt 1a';
 $act_data['a3']='1 East 93rd Street';
 $act_data['postcode']='10128';
$act_data['country_d1']='NY';
$act_data['country']='USA';
$skip_del_address=true;
   break;
  case(19715):
$header_data['country']='UK';
 break;

case(61637):
case(63251):
case(65048):
case(72539):
 $skip_del_address=true;
$act_data['a1']='17 Belton Park Avenue';
$act_data['a2']='';
 $act_data['a3']='';
$act_data['town_d2']='Donnycarney';
$act_data['town']='Dublin';
 $act_data['postcode']='';
$header_data['country_d2']='';
$header_data['country_d1']='';
$header_data['country']='Ireland';
 break;
case(44898):
case(45624):
case(45978):
case(46181):
case(46834):
case(47481):
case(48108):
case(48502):
case(48694):
case(51512):
case(59554):
case(59870):
case(60604):
case(61822):
case(62979):
case(65457):
case(67668):
case(73319):
 $skip_del_address=true;
$act_data['a1']='23 Elmdale Crescent';
$act_data['a2']='';
 $act_data['a3']='';
$act_data['town_d2']='Ballyfermot';
$act_data['town']='Dublin';
 $act_data['postcode']='';
$header_data['country_d2']='';
$header_data['country_d1']='';
$header_data['country']='Ireland';
 break;
case(27223):
case(27527):
case(28147):
case(36229):
case(38735):
case(40459):
case(48202):
case(55349):
case(62007):

 $skip_del_address=true;
$act_data['a1']='28 The Dunes Somerville';
$act_data['a2']='';
 $act_data['a3']='';
$act_data['town_d2']='Somerville';
$act_data['town']='Tramore';
 $act_data['postcode']='';
$header_data['country_d2']='Waterford';
$header_data['country_d1']='';
$header_data['country']='Ireland';
 break;

case(65485):
  case(68764):
 $skip_del_address=true;
$act_data['a1']='21 Kilmore Avenue';
$act_data['a2']='';
 $act_data['a3']='';
$act_data['town_d2']='Artane';
$act_data['town']='Dublin';
 $act_data['postcode']='D5';
$header_data['country_d2']='';
$header_data['country_d1']='';
$header_data['country']='Ireland';
 break;

 case(44490):
  case(45495):
case(46899):
case(49377):
  case(50659):
  case(52231):
  case(53098):
case(65835):
 $skip_del_address=true;
$act_data['a1']='22 Connolly Avenue';
$act_data['a2']='';
 $act_data['a3']='';
$act_data['town_d2']='';
$act_data['town']='Newcastle West';
 $act_data['postcode']='D5';
$header_data['country_d2']='Limerick';
$header_data['country_d1']='';
$header_data['country']='Ireland';
 break;
case(39427):
case(39778):
case(40175):
case(40476):
case(41488):
case(42178):
case(42698):

 $skip_del_address=true;
$act_data['a1']='ST Helier';
$act_data['a2']='';
 $act_data['a3']='22 Grange Lawn';
$act_data['town_d2']='';
$act_data['town']='Waterford';
 $act_data['postcode']='D5';
$header_data['country_d2']='Waterford';
$header_data['country_d1']='';
$header_data['country']='Ireland';
 break;


case(63234):
    $act_data['town']='Hull';
$act_data['postcode']='HU5 5PL';
 $act_data['a2']='';
 $act_data['a3']='';
$act_data['country_d2']='';
 $act_data['country_d1']='';
    $skip_del_address=true;
    break;
  case(68683):
  case(68578):
  case(68368):
  case(54101):
  case(54717):
  case(65907):
 case(63790):
  case(62631):
 case(64383):
case(66006):
  case(67614):
 case(68914):
  case(69563):
  case(69656):
  case(69812):
  case(69817):
  case(70340):
  case(70378):
  case(70390):
  case(70431):
  case(70953):
 case(71002):
  case(71036):
  case(71131):
  case(71517):
  case(71746):
 case(72356):
  case(73284):
  case(73360):
  case(74227):
  case(74371):
case(14622):
case(14960):
  case(57083):
    $skip_del_address=true;
    break;
case(43780):
$act_data['a1']='Tri Na Ri';
 $act_data['a2']='';
 $act_data['a3']='Slieve Rua';
    $skip_del_address=true;
    break;

case(30793):

case(31080):
  $act_data['country']='Sweden';
    $skip_del_address=true;
    break;

  case(73833):
    $header_data['city']='Calabasas';
    $header_data['country_d2']='CA';
     $act_data['town']='Beverly Hills';
    $act_data['country_d2']='CA';
    break;
  case(72481):
    $header_data['postcode']='CY-4529';
    break;
 case(71108):
   $act_data['postcode']='2820-564';
   $skip_del_address=true;
   break;
 case(71697):
   $act_data['a1']='10-12 Station Road';
   $skip_del_address=true;
   break;
   case(74146):
   $act_data['postcode']='34 - 120';
   $skip_del_address=true;
   break;
  case(71485):
   $act_data['country']='Spain';
   $skip_del_address=true;
   break;
 case(30793):
   $act_data['country']='Sweden';
   $skip_del_address=true;
   break;
case(50918):
   $act_data['town']='St Lawrence';
   $skip_del_address=true;
   break;

  case(67934):
  $act_data['a1']='10  Rue Bartholmy';
  $skip_del_address=true;
  break;

case(64005):
case(72481):
  case(53207):
 $skip_del_address=true;
 $act_data['a1']='PO BOX  50715';
 $act_data['a3']='38-E Karaiskaki Street';
 $act_data['a2']='Kanika Alexander Centre';
 break;
case(13164):
$header_data['address1']='C/O Di Roberta Vianello & Co';
$header_data['address2']='Via Bissa 11/2';
$header_data['city']='Mestre';

    break;
case(65399):
$header_data['address1']='via Zanotto 20/3';
$header_data['address2']='';
$header_data['address3']='';

$header_data['city']='Mestre';
 $header_data['postcode']='30173';
    break;
  case(39546):
case(40197):
$skip_del_address=true;
$act_data['a1']='Acharavi Cafenio Mitsuras 1';
$act_data['a2']='';
$act_data['a3']='';
  break;

case(39100):
  case(40278):
  case(44048):
  case(73689):
$skip_del_address=true;
$act_data['a1']='Odos Nickos Kazantzakis';
$act_data['a2']='';
$act_data['a3']='';
 $act_data['town']='Kato Gouves';
$act_data['country_d1']='Crete';
 $act_data['country_d2']='';
  break;
case(44851):
$skip_del_address=true;
$act_data['a1']='PO BOX 493491';
$act_data['a2']='';
$act_data['a3']='';
 $act_data['town']='Velanidia';
$act_data['country_d1']='Peloponnisos';
 $act_data['country_d2']='Laconia';
  break;
case(19463):
 $act_data['town']='Luqa';
$act_data['postcode']='Lqa05';
$skip_del_address=true;
break;
case(50288):
$act_data['a2']='';
$act_data['town']='Ozu';
$act_data['country_d1']='Shikoku';
 $act_data['country_d2']='Ehime';
 $act_data['postcode']='795-0064';
 $skip_del_address=true;
 break;
case(70693):
$act_data['country_d1']='Shikoku';

 $skip_del_address=true;
 break;

case(65399):


$act_data['a1']='66B Campbell Street';
    $skip_del_address=true;
    break;
  case(59505):
  case(60970):
  case(68058):
  case(65639):
  case(62012):
  case(63810):
  case(71447):
  case(74506):

    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['address1']='Read Coat Express';
    $header_data['address2']='Global House';
    $header_data['address3']='Manor Court';
    $header_data['city']='Crawley';
    $header_data['postcode']='RH10 9PY';
    $header_data['country']='UK';
    $act_data['tel']='';
    $act_data['fax']='';
    $act_data['mobile']='';
    $act_data['email']='';
    $act_data['name']='Crystal Man of Almeria';
    $act_data['a1']='Calle Gines Parra 0010';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='El Cucador';
    $act_data['country_d2']='Almeria';
    $act_data['country_d1']='Adalucia';
    $act_data['country']='Spain';
    $act_data['postcode']='04661';
    break;	
  case(39299):
  $act_data['a1']='Calle Gines Parra 0010';
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='El Cucador';
    $act_data['country_d2']='Almeria';
    $act_data['country_d1']='Adalucia';
    $act_data['country']='Spain';
    $act_data['postcode']='04661';
     $skip_del_address=true;
     break;
  case(68922):
    $header_data['city']='Turre';
    $header_data['postcode']='04639';
    $header_data['country_d2']='Almeria';
    $header_data['country_d1']='Adalucia';
    $header_data['country']='Spain';
    break;
  case(69405):
 $header_data['country']='UK';
  break;
 case(69327):
  case(72295):
  case(74467):
 $act_data['a3']='Niittykuja 2 A2';
 $act_data['a1']='';
    $act_data['a2']='';
    $act_data['town']='Rovaniemi';
     $act_data['country_d1']='';
     $act_data['country_d2']='';
  $skip_del_address=true;
  break;

 case(79117):
 $act_data['a3']='Ste 310';
 $act_data['a1']='PMVB 284';
 $act_data['a2']='8168 Crown Bay Marine';
 $act_data['town']='St Thomas';
 $act_data['country_d1']='';
 $act_data['country_d2']='';
 $act_data['postcode']='802';
 $act_data['country']='Virgin Islands, U.S.';
 $skip_del_address=true;
 break;


 case(23195):
  case(37248):
  case(22234):
 $act_data['a3']='Banegaardsgade 1, 2., -18';
    $act_data['a1']='NULL';
    $act_data['a2']='NULL';
  $skip_del_address=true;
  break;
  case(14648):

    $skip_del_address=true;
    $different_delivery_address=true;
    $header_data['address2']='';
    $header_data['city']='Ambleside';
    $header_data['country']='UK';
    $act_data['a2']='';
    $act_data['town']='Rydal';

    break;
  case(69467):
     $header_data['postcode']='6';
	 break;
  case(69453):
    $act_data['a1']='Unit G06 Clerkenwell Workshops';
    break;
  case('23230'):
    $header_data['city']='Dignes Les Bains';
    $header_data['postcode']='04000';
    $header_data['country']='France';
    break;

  case('50411'):
    $header_data['city']='Hammam Sousse';
    $header_data['postcode']='4011';
    $header_data['country']='Tunisia';
    break;

  case('50370'):
  $skip_del_address=true;
    $act_data['a1']='Woodhouse';
    $act_data['a2']='3 Kirks Close';

    break;
  case(73501):
      $skip_del_address=true;
       $act_data['country']='UK';
    break;
 case(36140):
  $skip_del_address=true;
    $act_data['town']='St Leonards On Sea';


    break;
 case(77185):
  $skip_del_address=true;
  $act_data['town']='St. Thomas';
  $act_data['country']='Virgin Islands, U.S.';

    break;


  case(30563):
  case('24356'):
    $header_data['address2']='';
    $header_data['postcode']='06880';
    $header_data['city']=mb_ucwords('KÄRRBY');
    $header_data['country']='FINLAND';

    break;
 case('25026'):
    $header_data['address2']='';
    $header_data['address3']='';
    $header_data['country_d2']='Lagos';
    $header_data['postcode']='1800-174';
    $header_data['city']='Praia da Luz';
    $header_data['country']='Portugal';

    break;

    break;
 case(29541):
    $header_data['address2']='';
    $header_data['address3']='';
    $header_data['country_d2']='';
    $header_data['postcode']='07560';
    $header_data['city']='Majorca';
    $header_data['country']='Spain';

    break;
  }






  if($act_data['country']=='Norway'  and 
     (
      $act_data['a1']=='Postboks 407'  
      or $act_data['a2']=='Postboks 407' 
      or $act_data['a3']=='Postboks 407') 
     ){
    $act_data['town']='Straume';
    $act_data['postcode']='5343';
 $skip_del_address=true;
  }


 if($act_data['country']=='Norway'  and 
     (
      $act_data['a1']=='Straumsfjellsvegen 9'  
      or $act_data['a3']=='Straumsfjellsvegen 9' 
      or $act_data['a2']=='Straumsfjellsvegen 9') 
     ){
    $skip_del_address=true;
    $act_data['town']='Straume';
    $act_data['postcode']='5343';

  }
 
 if(
    (
     preg_match('/^Via Bssa$|Via Bssa.*11/i',$act_data['a1'])
     or  preg_match('/^Via Bssa$|Via Bssa.*11/i',$act_data['a2'])
     or  preg_match('/^Via Bssa$|Via Bssa.*11/i',$act_data['a3'])
     )

) { 
      
      $act_data['town']='Mestre';
      $act_data['postcode']='30173';
      $act_data['country_d1']=''; 
      $act_data['country_d2']=''; 
      $act_data['a1']='Via Bssa, 11'; 
      $act_data['a2']='';
      $act_data['a3']='';
   }


 if(
(
 

     preg_match('/^Via Bssa$|Via Bssa.*11/i',$header_data['address1'])
     or  preg_match('/^Via Bssa$|Via Bssa.*11/i',$header_data['address2'])
     or  preg_match('/^Via Bssa$|Via Bssa.*11/i',$header_data['address3'])
     )

) { 
      $header_data['country']='Italy'; 
      $header_data['city']='Mestre';
      $header_data['postcode']='30173';
      $header_data['country_d1']=''; 
      $header_data['country_d2']=''; 
      $header_data['address1']='Via Bssa, 11'; 
      $header_data['address2']='';
      $header_data['address3']='';
   }
//print_r($act_data);
 //print_r($header_data);
 //exit;


  if($act_data['town']=='Korea South' and $act_data['country']=='' ){
    $act_data['country']='Korea South'; 
    $act_data['town']='';

    if($act_data['a3']=='Seoul'){
      $act_data['town']='Seoul';
      $act_data['a3']='';}
  }

  if($act_data['postcode']=='Korea South' and ($act_data['country']=='' or $act_data['country']=='Korea South' ) ){
    $act_data['country']='Korea South'; 
    $act_data['postcode']='';

    if($act_data['a3']=='Seoul'){
      $act_data['town']='Seoul';
      $act_data['a3']='';}
  }


  if(preg_match('/^eire$/i',$act_data['postcode'])){
    $act_data['country']='Ireland'; 
    $act_data['postcode']='';

  }

if(preg_match('/^524 95 Ljung$/i',$act_data['town']) and $act_data['postcode']=''){
    $act_data['town']='Ljung$'; 
    $act_data['postcode']='52495';

  }



  if($act_data['name']=='Incensed ! / Sarah Ismaeel'){
    $act_data['name']='Incensed';
  }
  if($act_data['name']=="Wax N Wicca" or $act_data['name']=="Wax 'N' Wicca"){
    $act_data['name']="Wax 'n' Wicca";
    $act_data['act']='32279';
  }

  if($act_data['name']=="Attah-Hicks" or $act_data['act']=="32437"){
    $act_data['act']='29980';
  }

  if($act_data['name']=="Wax 'n' Wicca" and $act_data['contact']='P Lewis'){
    $act_data['contact']="Pam Lewis";
    $act_data['first_name']="Pam";
  }

  if(preg_match('/\(.+\)/i',$act_data['name'],$match)){
    $_contact=preg_replace('/^\(|\)$/i','',$match[0]);
    // print "$_contact\n";
    if(strtolower($_contact)==strtolower($act_data['contact'])){
      $act_data['name']=preg_replace('/\(.+\)/i','',$act_data['name']);
    }
  }
  if(preg_match('/^M/i',$act_data['postcode']) and $act_data['town']=='Manchester'){
    $act_data['country']='UK';
  }
 if(preg_match('/^M/i',$header_data['postcode']) and $header_data['city']=='Manchester'){
    $header_data['country']='UK';
  }

  // ============ EXEPTIONS
  //print_r($act_data);

  if($act_data['a1']=='Sharn Brook' and $act_data['town']==''){
    $act_data['a1']='NULL';
    $act_data['town']='Sharnbrook';
  }


  if($act_data['a2']=='Dhahran' and $act_data['town']=='East Province'){
    $act_data['a2']='';
    $act_data['town']='Dhahran';



  }

  if( preg_match('/^belfast\s*,/i',$act_data['town'])){
    $act_data['town']='Belfast';
  }
  if( preg_match('/^via cork$/i',$act_data['town'])){
    $act_data['town']='';
  }


  if( preg_match('/^co\.? (Westmeath|Meath)$/i',$act_data['town'])){
    $act_data['town']='';
  }

  
  if($header_data['address2']=='Arundel' and $header_data['city']==''){
    
    $header_data['city']=$header_data['address2'];
    $header_data['address2']='';
  }
  


//      print "A1 ".$header_data['address1']."\n";
//       print "A2 ".$header_data['address2']."\n"; 
//      print "TO ".$header_data['city']."\n";
//     print $skip_del_address."PO ".$header_data['postcode']."\n";
  
//    print_r($act_data);

  //exit;
  
  if(!$skip_del_address){
    

    if($header_data['postcode']=='07760 Ciutadella'){
      $header_data['town']='Ciutadella';
      $header_data['postcode']='07760';
    }
    
    if(!(
	 _trim(strtolower($act_data['a1']))==_trim(strtolower($header_data['address1'])) and 
	 _trim(strtolower($act_data['a2']))==_trim(strtolower($header_data['address2'])) and 
	 _trim(strtolower($act_data['town']))==_trim(strtolower($header_data['city'])) and 
	 (
	  
	  _trim(strtolower($act_data['postcode']))==_trim(strtolower($header_data['postcode'])) or 
	  _trim(strtolower($act_data['country']).' '.strtolower($act_data['postcode']))==_trim(strtolower($header_data['postcode']))
	  )  
	 
	 )
       
       )
      $different_delivery_address=true;
    

    if($different_delivery_address){ 
      //	print "cacacacacacacacacacaca";
    }
      //print "xxxxxxxxxxxxxxxxxxxxx";
      
 //    if($different_delivery_address and $act_data['town']!=''){ 
//       if(strtolower($act_data['a1'])==strtolower($header_data['address1']) and  strtolower($act_data['a2'])==strtolower($header_data['address2'])  and preg_match('/'.$act_data['town'].'/i',strtolower($header_data['city'])))
// 	$different_delivery_address=false;
//     }
    // check if a country is a valid country and if it is not assume uk
   

    if(strtolower($header_data['postcode'])== strtolower($act_data['country']) and $act_data['country']!=''){
      if(strtolower($header_data['city'])==strtolower($act_data['postcode'].' '.$act_data['town']))
	$different_delivery_address=false;
    }












   

    
    $sql=sprintf("select country.id,name, alias from list_country as country left join list_country_alias as country_alias on (country.code=country_alias.code) where alias='%s' or country.name='%s' group by country.id ",$header_data['country'],$header_data['country']);
    $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
    if(!$row = mysql_fetch_array($result, MYSQL_ASSOC)) 
      $header_data['country']=$act_data['country'];
      
    
    
      
  }

    
  //  print "xxxxxxx $different_delivery_address xxxxxxxxxxxxxx";
  //exit;
  // Ok in some cases the country is in the post code so try to get it

  if($act_data['country']==''){


    if(preg_match('/spain\s*.\s*ibiza/i',$act_data['postcode'])){
      $act_data['country']='Spain';
      $act_data['postcode']='';
      $act_data['country_d1']='Balearic Islands';
      $act_data['country_d2']='Balearic Islands';
    }
      
    

    $tmp_array=preg_split('/\s+/',$act_data['postcode']) ;

    if(count($tmp_array)==2){
      $sql=sprintf("select country.id,name, alias from list_country as country left join list_country_alias as country_alias on (country.code=country_alias.code) where alias='%s' or country.name='%s' group by country.id ",$tmp_array[0],$tmp_array[0]);
      $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
      if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$act_data['country']=$row['name'];
	$act_data['postcode']=$tmp_array[1];
      }
      $sql=sprintf("select country.id,name, alias from list_country as country left join list_country_alias as country_alias on (country.code=country_alias.code) where alias='%s' or country.name='%s' group by country.id ",$tmp_array[1],$tmp_array[1]);
      $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
      if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$act_data['country']=$row['name'];
	$act_data['postcode']=$tmp_array[0];
      }
    }elseif(count($tmp_array)==1){
      $sql=sprintf("select country.id,name, alias from list_country as country left join list_country_alias as country_alias on (country.code=country_alias.code) where alias='%s' or country.name='%s' group by country.id ",$tmp_array[0],$tmp_array[0]);

      $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
      if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$act_data['country']=$row['name'];
	$act_data['postcode']='';
      }
	

    }
  }else{
    //    print_r($act_data);
    if(strtolower(_trim($act_data['country']))==strtolower(_trim($act_data['postcode'])))
      $act_data['postcode']='';
  }
  
  if($act_data['postcode']=='3742' and $act_data['town']=='SW Baarn'){
    $act_data['town']='Baarn';
    $act_data['postcode']='3742 SW';
  }


    
    
  if($act_data['postcode']=="" and preg_match('/\s*\d{4,6}\s*/i',$act_data['town'],$match))
    {
  
      if($act_data['country']!="Netherlands"){
	$act_data['postcode']=_trim($match[0]);
	$act_data['town']=preg_replace('/\s*\d{4,6}\s*/','',$act_data['town']);
      }
    }


  if($act_data['a2']=='Ascheffel'){
    $act_data['town']='Ascheffel';
    $act_data['a2']='';
  }
  if(preg_match('/South Afrika$|South Africa$/i',$header_data['postcode'])){
    $header_data['country']='South Africa';
    $header_data['postcode']=_trim(preg_replace('/South Afrika$|South Africa$/i','',$header_data['postcode']));
  }

  if(preg_match('/South Afrika$|South Africa$/i',$act_data['postcode'])){
    $act_data['country']='South Africa';
    $act_data['postcode']=_trim(preg_replace('/South Afrika$|South Africa$/i','',$act_data['postcode']));
  }


  if(preg_match('/N-5353 Straum/i',$header_data['postcode'])){
    $header_data['postcode']='N-5353';
    $header_data['city']='Straum';
  }

 if(preg_match('/Via Chiesanuova, 71/i',$act_data['a1']) and preg_match('/C.O .c.c. Futura2./i',$act_data['a2'])  ){
   $act_data['a1']='C/O CC Futura';
  $act_data['a2']='Via Chiesanuova, 71';
  }

 if(preg_match('/30173 Mestre Ve/i',$header_data['address3'])){
    $header_data['postcode']='30173';
    $header_data['city']='Mestre';
    $header_data['address3']='';
    $header_data['country_d1']='Veneto';
    $header_data['country_d2']='Venice';

  }
if(preg_match('/Mrs Roberta Vianello/i',$header_data['address1'])){
    $header_data['address1']='';

  }



  if(preg_match('/United States$/i',$header_data['postcode'])){
    $header_data['country']='USA';
    $header_data['postcode']=_trim(preg_replace('/United States$/i','',$header_data['postcode']));
  }

  if(preg_match('/United States$/i',$act_data['postcode'])){
    $act_data['country']='USA';
    $act_data['postcode']=_trim(preg_replace('/United States$/i','',$act_data['postcode']));
  }


  if(preg_match('/Lewiston - Ny/i',$act_data['town'])){
    $act_data['country']='USA';
    $act_data['town']='Lewiston';
    $act_data['country_d1']='NY';
 
  }
  //print_r($act_data);
 if(preg_match('/^101 Reykjavik$/i',$act_data['postcode'])){
   $act_data['town']='Reykjavik';
    $act_data['country']='Iceland';
    $act_data['postcode']='101';
    // print_r($act_data);
  }
if(preg_match('/^101 Reykjavik$/i',$act_data['town'])){
   $act_data['town']='Reykjavik';
    $act_data['country']='Iceland';
    $act_data['postcode']='101';
    // print_r($act_data);
  }


 if(preg_match('/Fi\-\d{4,5}/i',$act_data['postcode'])  and $act_data['country_d1']==''){
   
    $act_data['country']='Finland';
    // print_r($act_data);
  }


  if(preg_match('/Drogheda.*Co Louth/i',$act_data['town'])){

    $act_data['town']='Drogheda';
    $act_data['country_d2']='Co Louth';
 
  }

  if(preg_match('/Tampa\s*.\s*Florida/i',$act_data['town'])){

    $act_data['town']='Tampa';
    $act_data['country_d1']='Florida';
 
  }

  if($act_data['country']=='USA' and   preg_match('/\-\s*ny/i',$act_data['town'])){

    $act_data['town']=preg_replace('/\-\s*ny/i','',$act_data['town']);
    $act_data['country_d1']='New York';
 
  }



  if(preg_match('/alberta/i',$act_data['town'])  and  preg_match('/Onoway/i',$act_data['a3']) ){
    $act_data['a3']='';
    $act_data['town']='Onoway';
    $act_data['country_d1']='Alberta';
 
  }




  if(preg_match('/alicante/i',$act_data['country_d2']) and $act_data['country']==''  ){
    $act_data['country']='Spain';
    $act_data['country_d1']='Valencia';
  }

  if(preg_match('/Alfaz del Pi - Alicante/i',$act_data['town'])  ){
    $act_data['town']='Alfaz del Pi';
    $act_data['country_d2']='Alicante';
    $act_data['country_d1']='Valencia';
  }


  if(preg_match('/03837 Muro de Alcoy/i',$header_data['city'])){
    $header_data['country']='Spain';
    $header_data['country_d1']='Valencia';
    $header_data['country_d2']='Alicante';
    $header_data['postcode']='03837';
    $header_data['city']='Muro de Alcoy';
  }




  if(preg_match('/Viterbo/i',$act_data['town'])  and preg_match('/Soriano Nel Cimino/i',$act_data['a2']) ){
    $act_data['country']='Italy';
    $act_data['town']='Soriano Nel Cimino';
    $act_data['country_d1']='Lazio';
    $act_data['country_d2']='Viterbo';
    $act_data['a2']='';
    $act_data['postcode']='01028';
  }



  // print_r($header_data);
  //exit;

  //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  // Her we fix the distinct speciffic errors in the input fiels
  $act_data['town']=_trim($act_data['town']);

  if($act_data['country']=='Cyprus'){
    if($act_data['a3']=='1065 Nicosia'){
      $act_data['postcode']='1065';
      $act_data['town']='Nicosia';
      $act_data['a3']='';
    }
  }
    
  if($act_data['postcode']=='Cyprus')
    {
      $act_data['country']='Cyprus';
      $act_data['postcode']='';
    }
 
 


  if($act_data['postcode']=='' and preg_match('/\s*no\-\d{4}\s*/',$act_data['town'],$match) ){
    $act_data['postcode']=_trim($match[0]);
    $act_data['town']=preg_replace('/\s*no\-\d{4}\s*/','',$act_data['town']);

  }

  // print_r($act_data);

  if($act_data['country']=='Korea South' or $act_data['country']=='South Korea'){
    if($act_data['a3']=='Seoul' ){
      $act_data['town']='Seoul';$act_data['a3']='';
    }    
    if(preg_match('/^Kangseo.Gu$/i',_trim($act_data['a2']))){
      $act_data['town_d1']='Gangseo-gu';
      $act_data['a2']='';
    }
    if($act_data['a1']=='105-207 Whagok-Dong'){
      $act_data['town_d2']='Hwagok-dong';
      $act_data['a1']='105-207';
    }

  }

  // print_r($act_data);

  if($act_data['a2']=='Yokneam Ilit' ){
    $act_data['a2']='';
    $act_data['town']='Yokneam Ilit';
  }
  if(preg_match('/Pyrgos.*Limassol/',$act_data['town'])){

    $act_data['town']='Pyrgos';
  }


  if($act_data['a1']=='Sharn Brook' and $act_data['town']=='Bedfordshire'){
    $act_data['a1']='NULL';
    $act_data['town']='Sharnbrook';
  }

  if($act_data['a2']=='Upper Marlboro' and $act_data['town']=='Md'){
    $act_data['a2']='';
    $act_data['town']='Upper Marlboro';
    $act_data['country_d1']='MD';
  }



  if($act_data['a2']=='55299 Nackenheim' and $act_data['town']=='Germany'){
    $act_data['a2']='';
    $act_data['country_d1']='';
    $act_data['postcode']='55299';
    $act_data['town']='Nackenheim';
  }

  if($act_data['town']=='Siverstone - Oregon'){
    $act_data['town']='Siverstone';
    $act_data['country_d2']='Oregon';
    $act_data['country']='USA';
  }

  if($act_data['town']=='5227 Nesttun'){
    $act_data['town']='Nesttun';
    $act_data['postcode']='5227';
  }
  if($act_data['town']=='3960 Stathelle'){
    $act_data['town']='Stathelle';
    $act_data['postcode']='3960';
  }
  if($act_data['town']=='45700 Kuusankoski'){
    $act_data['town']='Kuusankoski';
    $act_data['postcode']='45700';
  }
  if($act_data['town']=='06880 Kärrby'){
    $act_data['town']='Kärrby';
    $act_data['postcode']='06880';
  }
  if($act_data['town']=='2500 Valby'){
    $act_data['town']='Valby';
    $act_data['postcode']='2500';
  }

  if($act_data['town']=='21442 Malmö'){
    $act_data['town']='Malmö';
    $act_data['postcode']='21442';
  }
  if($act_data['town']=='11522 Stockholm'){
    $act_data['town']='Stockholm';
    $act_data['postcode']='11522';
  }

  if($act_data['town']=='1191 Jm Ouderkerk A/d Amstel'){
    $act_data['town']='Ouderkerk aan de Amstel';
    $act_data['postcode']='1191JM';
  }
  if($act_data['town']=='7823 Pm Emmen'){
    $act_data['town']='Emmen';
    $act_data['postcode']='7823PM';
  }
  if($act_data['town']=='1092 Budapest'){
    $act_data['town']='Budapest';
    $act_data['postcode']='1092';
  }


  if($act_data['town']=='Lanzarote, las Palmas' ){
    $act_data['town']='';
   
    $act_data['country_d1']='Canary Islands';
   
    $act_data['country_d2']='Las Palmas';
    $act_data['town']='';

    if( $act_data['a2']=='Costa Teguise'){
      $act_data['a2']='';
      $act_data['town']='Costa Teguise';
    }


  }


  if($act_data['town']=='Zugena - Provincia Almeria'){
    $act_data['town']='Zurgena';
    $act_data['country_d2']='Almeria';
    $act_data['country_d1']='Adalucia';
    
  }

if($act_data['town']=='Alhama de Almeria, Almeria'){
    $act_data['town']='Alhama de Almeria';
    $act_data['country_d2']='Almeria';
    $act_data['country_d1']='Adalucia';
    
  }
  
 if($act_data['town']=='Coulby Newham - Middlesbrough'){
   $act_data['country_d2']='Middlesbrough';
   $act_data['town']='Coulby Newham';
 }

if($act_data['town']=='Lerwick - Shetland Isles'){
   $act_data['country_d2']='Shetland Islands';
   $act_data['town']='Lerwick';
 }
if($act_data['town']=='Ollaberry - Shetland Islands'){
   $act_data['country_d2']='Shetland Islands';
   $act_data['town']='Ollaberry';
 }
if($act_data['town']=='Shetland - Shetland Islands' and $act_data['a1']=='Brae' ){
   $act_data['country_d2']='Shetland Islands';
   $act_data['town']='Brae';
   $act_data['a1']='NULL';
   
 }

 if(preg_match('/$MK40.*1hs/i',$act_data['postcode']) ){
   $act_data['country']='United Kingdom';
   
 }

 if(preg_match('/DH5.*9RS/i',$act_data['postcode'])  and $act_data['a1']=='Linden House' ){
   $act_data['a1']='Linden House';
   $act_data['a2']='2 Heather Drive';
   $act_data['a3']='';
   $act_data['town']='Houghton Le Spring';
  }

  if($act_data['town']=='Malaga' and $act_data['a2']=='Coin'){
    $act_data['town']='Coin';
    $act_data['country_d1']='Andalusia';
    $act_data['country_d2']='Malaga';
    $act_data['a2']='';
  }

 if($act_data['town']=='Villasor Pr. Cagliari'){
   $act_data['town']='Villasor';
   $act_data['country_d2']='Cagliari';
}

if($act_data['town']=='Leebotwood (nr Church Stretton'){
   $act_data['town']='Leebotwood Nr. Church Stretton';
}
if($act_data['town']=='Nea Moudhania - Chalkidiki'){
   $act_data['town']='Nea Moudhania';
   $act_data['country_d2']='Chalkidiki';
}

if($act_data['town']=='Cradley Heath, West Midlands'){
   $act_data['town']='Cradley Heath';
   $act_data['country_d2']='';
}

if($act_data['town']=='Garswood, Ashton In Makerf'){
   $act_data['town']='Ashton-in-Makerfield';
   $act_data['town_d2']='Garswood';
}
if($act_data['town']=='Boulogne Billancourt Cedex'){
   $act_data['town']='Boulogne Billancourt';
}

if($act_data['town']=='Furzton - Milton Keynes'){
   $act_data['town']='Milton Keynes';
$act_data['town_d2']='Furzton';
}

if($act_data['town']=='Glenfield - Leicester'){
   $act_data['town']='Leicester';
$act_data['town_d2']='Glenfield';
}
if($act_data['town']=='Edinburgh - Midlothian'){
   $act_data['town']='Edinburgh';
}

if($act_data['town']=='Killorglin - Co Kerry'){
   $act_data['town']='Killorglin';
}
if($act_data['town']=='Castledawson - Co Derry'){
   $act_data['town']='Castledawson';
}
if($act_data['town']=='Douglas, Isle of Man'){
   $act_data['town']='Douglas';
   $act_data['country']='Isle of Man';
}

if($act_data['town']=='Aberdeen, Aberdeenshire'){
   $act_data['town']='Aberdeen';
}

if($act_data['town']=='Elephant & Castle, London'){
   $act_data['town']='London';
$act_data['town_d2']='Elephant & Castle';
}
if($act_data['town']=='Muswell Hill, London'){
   $act_data['town']='London';
$act_data['town_d2']='Muswell Hill';
}

if($act_data['town']=='South Norwood, London'){
   $act_data['town']='London';
$act_data['town_d2']='South Norwood';
}

 if(preg_match('/Isle of Wight/i',$act_data['town']))
   $act_data['town']='';

if($act_data['town']=='Walkinstown - Dublin'){
   $act_data['town']='Dublin';
$act_data['town_d2']='Walkinstown';
}
if($act_data['town']=='Yarmouth - Isle of Wight'){
   $act_data['town']='Yarmouth';
   $act_data['country_d2']='Isle of Wight';
 }
if($act_data['town']=='New Port - Isle of Wight'){
   $act_data['town']='New Port';
   $act_data['country_d2']='Isle of Wight';
 }





if($act_data['town']=='Kingston-Upon Thames'){
   $act_data['town']='Kingston-Upon-Thames';
}

if($act_data['town']=='Bradford - On - Avon'){
   $act_data['town']='Bradford-On-Avon';
}

if($act_data['town']=='Tongham - Nr Farnham'){
   $act_data['town']='Tongham Nr. Farnham';
}

if($act_data['town']=='Hornbæk - Sjælland'){
   $act_data['town']='Hornbæk';
   $act_data['country_d2']='Sjælland';
}



  if($act_data['town']=='7779de Overijssel'){
    $act_data['town']='Overijssel';
    $act_data['postcode']='7779DE';
  }
  if($act_data['town']=='3015 Br Rotterdam'){
    $act_data['town']='Rotterdam';
    $act_data['postcode']='3015BR';
  }

  $act_data['postcode']=_trim(preg_replace('/the Netherlands/i','',$act_data['postcode']));

  if( preg_match('/boggon/i',$act_data['name'])  and preg_match('/35617|48051/i',$act_data['act'])    ){
    $act_data['name']='Temenos Academy';
  }
  if( preg_match('/dudden/i',$act_data['name'])  and preg_match('/25124/i',$act_data['act'])    ){
    $act_data['name']='Mr Jeff C Dudden';
  }
    

  if( preg_match('/Spain.*Canary Island/i',$act_data['country'])  and preg_match('/Arguineguin/i',$act_data['a2'])    ){
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Arguinegín';
    $act_data['country_d2']='Las Palmas';
    $act_data['country_d1']='Canary Islands';
    $act_data['country']='Spain';

  }


  if( preg_match('/Spain.*Canary Island/i',$act_data['country'])      ){
    $act_data['country_d1']='Canary Islands';
    $act_data['country']='Spain';
  }
  if( preg_match('/^Tenerife.*Canary Island/i',$act_data['town'])      ){
    $act_data['country_d1']='Canary Islands';
    $act_data['country']='Spain';
    $act_data['town']='Tenerife';

    if($act_data['a2']=='Playa de las Americas' and $act_data['a3']=='Adeje'){
      $act_data['a2']='';
      $act_data['a3']='';
      $act_data['town']='Playa de las Americas';
    }

  }

  $header_data['postcode']=_trim( $header_data['postcode']);


  if(preg_match('/Northern Ireland/i',$act_data['town'])  ){
    $act_data['town']=_trim(preg_replace('/\,?\-?\s*Northern Ireland/i','',$act_data['town']));
  }

  //  print_r($header_data);
 if(preg_match('/^GY\d/i',$header_data['postcode'])){
    $header_data['country']='Guernsey';
  }

  if(preg_match('/^GY\d/i',$act_data['postcode'])  and ($act_data['country']=='' or $act_data['country']=='Guernsey')){
    $act_data['country']='Guernsey';
    if($act_data['town']=='Guernsey')
      $act_data['town']='';

    if($act_data['a2']=='Les Baissieres, St Peter Port'){
      $act_data['a2']='Les Baissieres';
      $act_data['town']='St Peter Port';
    }
     


  }

  if( preg_match('/^bfpo\s*\d/i',$act_data['town'])  and $act_data['postcode']=='' ){
    $act_data['postcode']=strtoupper($act_data['town']);
    $act_data['town']='';
  }


  if( preg_match('/^je\d/i',$header_data['postcode'])  and $header_data['country']=='' ){
    $header_data['country']='Jersey';
    if($header_data['city']=='Jersey')
      $act_data['city']='';
  }
  if( preg_match('/^im\d/i',$header_data['postcode'])  and $header_data['country']=='' ){
    $header_data['country']='Isle of Man';

    if($header_data['address2']=='Ramsey'){
      $header_data['city']='Ramsey';
      $header_data['address']='';
    }
  }
  if( preg_match('/^je\d/i',$act_data['postcode'])  and $act_data['country']=='' ){
    $act_data['country']='Jersey';
    if($act_data['town']=='Jersey')
      $act_data['town']='';
  }
  if( preg_match('/^im\d/i',$act_data['postcode'])  and $act_data['country']=='' ){
    $act_data['country']='Isle of Man';

    if($act_data['a2']=='Ramsey'){
      $act_data['town']='Ramsey';
      $act_data['a2']='';
    }
     
    if(preg_match('/Isle of Man/i',$act_data['town'])  ){
      $act_data['town']=_trim(preg_replace('/\,?\-?\s*Isle of Man/i','',$act_data['town']));

    }
  }

  if(preg_match('/^Norfolk$|^West Midlands$/i',$act_data['town']))
    $act_data['town']='';


  if($act_data['town']=='St.pauls Bay')
    $act_data['town']='St Pauls Bay';

  if($act_data['town']=='Outside the Royal Festival Hall'){
  $act_data['town']='London';
    $act_data['country_d2']='';
    $act_data['country_d1']='';
  }

  if($act_data['town']=='Ashton Under Lyne, Tameside')
    $act_data['town']='Ashton Under Lyne';


  if(preg_match('/Las Palmas de Gran Canaria/i',$act_data['a2'])  ){
    $act_data['a2']='';
    $act_data['country_d2']='Las Palmas';
    $act_data['country_d1']='Canary Islands';
    $act_data['country']='Spain';

  }

  if($act_data['postcode']=='5260Demnark')
    $act_data['postcode']='DK-5260';
 

  if(preg_match('/ch6\s*5dz/i', $act_data['country'] )){
    
    $act_data['country']='';
    $act_data['postcode']='ch6 5dz';
  }
    
  if($act_data['country']=='Scotish Island' or $act_data['country']=='West Sussex' ){
    $act_data['country']='';
  }





  if( preg_match('/Mark Postage To France/i',$act_data['a1'])){
    $act_data['a1']='';
  }

  if( preg_match('/Spain.*Baleares/i',$act_data['country']) ){
    $act_data['country_d1']='Balearic Islands';
    $act_data['country']='Spain';
  }

  if($act_data['town']=='7182 Calvia - Mallorca'){
    $act_data['postcode']='07182';
    $act_data['town']='Calvia';
    $act_data['country_d1']='Balearic Islands';
    $act_data['country_d2']='Balearic Islands';
  }

  if($act_data['town']=='Lefkosia (nicosia)')
    $act_data['town']='Nicosia';


  if($act_data['town']=='07820 San Antonio - Ibiza')
    $act_data['postcode']='07820';

  if($act_data['postcode']=='Co Cork, Ireland')
    $act_data['postcode']='';
  if($act_data['town']=='Alicante - Spain')
    $act_data['town']='Alicante';
  if( preg_match('/San Antonio.*Ibiza/i',$act_data['town']) ){
    $act_data['town']='Sant Antoni de Portmany';
      
  }

  if( preg_match('/Perth.*Western Autralia/i',$act_data['town']) ){
    $act_data['town']='Perth';
    $act_data['country_d2']='Western Autralia';

  }
  if($act_data['a1']=='Kerem Maharal,' ){
    $act_data['a1']='NULL';
    $act_data['town']='Kerem Maharal';
  }

  if( preg_match('/Bs37 7rb|S2 3eh/i',$act_data['town'])){
    $act_data['town']='';
    $act_data['postcode']=strtoupper($act_data['town']);
  }

  if( preg_match('/castle market/i',$act_data['a3'])  and $act_data['postcode']=='' and $act_data['town']=='Sheffield' ){
    $act_data['postcode']='S1 2AD';
  }
  if($act_data['town']=='Albox, Almeria'){
    $act_data['town']='Albox';
    $act_data['country_d1']='Andalucía';
    $act_data['country_d2']='Almería';
  }

  if( preg_match('/^bfpo\s+\d+/i',$act_data['town'])){
    $act_data['country']='United Kingdom';
    $act_data['town']='';
    $act_data['postcode']=strtoupper($act_data['town']);
  }

  if($act_data['postcode']=='50004 Zaragoza'){
    $act_data['town']='Zaragoza';
    $act_data['postcode']='50004';
  }
  if($act_data['postcode']=='08530 Barcelona'){
    $act_data['town']='Barcelona';
    $act_data['postcode']='08530';
  }
  if($act_data['postcode']=='28300 Madrid'){
    $act_data['town']='Madrid';
    $act_data['postcode']='28300';
  }
  if($act_data['postcode']=='28013 Madrid'){
    $act_data['town']='Madrid';
    $act_data['postcode']='28013';
  }

  if($act_data['act']=='27821'){
    $act_data['act']='21179';
    $act_data['name']='Soap & Soak';
  }
  

  if(strtolower($act_data['town'])=='la romana (alicante)'){
    $act_data['town']='La Romana';
    $act_data['country_d2']='Alicante';
    $act_data['country_d1']='Valencia';
  }

  if($act_data['town']=='Sax (alicante)'){
    $act_data['town']='Sax';
    $act_data['country_d2']='Alicante';
    $act_data['country_d1']='Valencia';
  }


  if($act_data['postcode']=='30383 Cartagena'){
    $act_data['town']='Cartagena';
    $act_data['postcode']='30383';
  }
  if($act_data['postcode']=='07760 Ciutadella'){
    $act_data['town']='Ciutadella';
    $act_data['postcode']='07760';
  }
    

  if($act_data['town']=='Tucson Az'){
    $act_data['town']='Tucson';
    $act_data['country_d2']='Arizona';
  }

  if($act_data['country']=='Ireland' and $act_data['a3']=='Castleblaney' ){
    $act_data['town']='Castleblaney';
    $act_data['country_d2']='Monaghan';
    $act_data['a3']='';
  }


  if($act_data['town']=='Port Angeles (wa)'){
    $act_data['town']='Port Angeles';
    $act_data['country_d2']='WA';
  }
  if($act_data['town']=='Beverly Hills (ca)'){
    $act_data['town']='Beverly Hills';
    $act_data['country_d2']='California';
  }
  if($act_data['town']=='Milwaukee, Wi'){
    $act_data['town']='Milwaukee';
    $act_data['country_d2']='Wi';
  }

  if($act_data['town']=='Kingston, Ma'){
    $act_data['town']='Kingston';
    $act_data['country_d2']='Ma';
  } 
  if($act_data['town']=='Mcdonough, Ga'){
    $act_data['town']='Mcdonough';
    $act_data['country_d2']='Ga';
  }
  if($act_data['town']=='Bridgewater, Nj'){
    $act_data['town']='Bridgewater';
    $act_data['country_d2']='NJ';
  }
  if($act_data['town']=='Marietta, Ga'){
    $act_data['town']='Marietta';
    $act_data['country_d2']='Ga';
  }
  if($act_data['town']=='Duluth - Ga'){
    $act_data['town']='Duluth';
    $act_data['country_d2']='Ga';
  } 


  if($act_data['town']=='Hoffman Estates - Il.'){
    $act_data['town']='Hoffman Estates';
    $act_data['country_d2']='Il';
  } 
  if($act_data['town']=='Shelton Ct'){
    $act_data['town']='Shelton';
    $act_data['country_d2']='Ct';
  }
  if($act_data['town']=='Raton - Nm.'){
    $act_data['town']='Raton';
    $act_data['country_d2']='NM';
  }
  if($act_data['town']=='Monett, Mo'){
    $act_data['town']='Monett';
    $act_data['country_d2']='MO';
  }
  if($act_data['town']=='Alton, Il'){
    $act_data['town']='Alton';
    $act_data['country_d2']='Il';
  } 
  if($act_data['town']=='Zanesville, Ohio'){
    $act_data['town']='Zanesville';
    $act_data['country_d2']='Ohio';
  }
  if($act_data['town']=='Pinola, Ms'){
    $act_data['town']='Pinola';
    $act_data['country_d2']='MS';
  }
  if($act_data['town']=='Port Jefferson Station - Ny'){
    $act_data['town']='Port Jefferson Station';
    $act_data['country_d2']='NY';
  } 
  if($act_data['town']=='Houston - Texas'){
    $act_data['town']='Houston';
    $act_data['country_d2']='Texas';
  }
  if($act_data['town']=='Cambell Hall - Ny'){
    $act_data['town']='Cambell Hall';
    $act_data['country_d2']='NY';
  } 
  if($act_data['postcode']=='04400 Almeria - SPAIN'){
    $act_data['postcode']='04400';
    $act_data['country_d1']='Andalucía';
    $act_data['country_d2']='Almería';
  }
    
  if( preg_match('/Whaley Bridge, Derbyshire Sk23 7jg/i',$act_data['town'])){
    $act_data['country']='United Kingdom';
    $act_data['town']='Whaley Bridge';
    $act_data['postcode']='SK23 7JG';
  }

  if( preg_match('/Beirut\s*.\s*Lebanon/i',$act_data['country'])){
    $act_data['country']='Lebanon';
    $act_data['town']='Beirut';
  }

  if( preg_match('/01902 850 006|north ayrshire|stoke.on trent|Suffolk|Norfolk/i',$act_data['country']))
    $act_data['country']='';

  if( preg_match('/Channel Islands/i',$act_data['country']) ){
      
    if(preg_match('/^(jersey\s+)?\s*je/i',$act_data['postcode'])){
	
      $act_data['postcode']=preg_replace('/\s*jersey\s*/i','',$act_data['postcode']);
      $act_data['country']='Jersey';



    }
  }


  if( preg_match('/ireland/i',$act_data['country']) and preg_match('/^bt/i',$act_data['postcode']) )
    $act_data['country']='United Kingdom';
    
  if( preg_match('/Co Kerry, Ireland/i',$act_data['country'])  )
    $act_data['country']='Ireland';


  if($act_data['act']=='21808'){
    $act_data['name']='Luss Glass Studio';
    $act_data['contact']='Janine Smith';
  }

  if($act_data['act']=='33387'){
    $act_data['act']='9050';
  }


  if($act_data['name']=='Crocodile Antiques (1)')
    $act_data['name']='Crocodile Antiques';

  // print $act_data['mob'].'-'.$act_data['act']."-\n";
  if($act_data['mob']=='01723 376447' and $act_data['act']=='26456'){
    $act_data['mob']='';
  }

  if($act_data['contact']=='Thandi' and $act_data['act']=='21217'){
    $act_data['contact']='Thandi Viljoen';
  }

    
    
  if(preg_match('/G12 8aa/i',$act_data['country'])){
    $act_data['country']='';
    $act_data['postcode']='G12 8AA';
  }
    
    
  $split_town=preg_split('/\s*,\s*/i',$act_data['town']);
  if(count($split_town)==2){
    if(preg_match('/jersey/i',$split_town[1])){
      $act_data['town']=$split_town[0];
      $act_data['country']='Jersey';
    }
	
  }




  if(check_email_address($act_data['country'])){
    if($act_data['email']=='')
      $act_data['email']=$act_data['country'];
    $act_data['country']='';
  }
  if(preg_match('/Clwyd/i',$act_data['country']))$act_data['country']='';
    
  if($act_data['country']=='Harmelen (netherlands)'){
    $act_data['town']='Harmelen';
    $act_data['country']='netherlands';
  }

  if($act_data['postcode']=='USA'){
    $act_data['postcode']='';
    $act_data['country']='United States';
  }

  if($act_data['town']=='Fgura, Europe'){
    $act_data['town']='Fgura';
  }
  if($act_data['town']=='3800 Limburg'){
    $act_data['town']='Limburg';
    $act_data['postcode']='3800';
  }
  if($act_data['town']=='West Vlaanderen'){
    $act_data['town']='West Vlaanderen';
    $act_data['postcode']='8800';
  }





  if($act_data['town']=='Nordrheinwestfalen' and $act_data['a2']=='Bochum'){
    $act_data['town']='Bochum';
    $act_data['country_d1']='Nordrhein-Westfalen';
    $act_data['a2']='';
  }

  if($act_data['town']=='Schwaig, Bavaria'){
    $act_data['town']='Schwaig';
    $act_data['country_d1']='Bayern';
  }
  if($act_data['town']=='Central Milton Keynes'){
    $act_data['town']='Milton Keynes';
  }
  if($act_data['town']=='No-5353 Straume'){
    $act_data['town']='Straume';
    $act_data['postcode']='No-5353';
  }

  if($act_data['a2']=='Vibrac' and $act_data['a3']=='Charente'){
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Vibrac';
    $act_data['country_d1']='Poitou-Charentes';
    $act_data['country_d2']='Charente';

  }



  if($act_data['town']=='Tiefenau' and $act_data['postcode']=='1609'){
    $act_data['country_d1']='Sachsen';
    $act_data['postcode']='01609';
  }

  if($act_data['town']=='Abingdon Oxfordshire')
    $act_data['town']='Abingdon';

  if($act_data['town']=='Bromham, Chippenham')
    $act_data['town']='Bromham';
  if($act_data['town']=='Buckinghamshire')
    $act_data['town']='';

  // print_r($act_data);    
  if(preg_match('/\s*eire\*/i',$act_data['postcode'])){
    $act_data['postcode']='';
    $act_data['country']='Ireland';
  }
    
  if(preg_match('/MO 63136/i',$act_data['postcode']) and  $act_data['country']='USA'  ){
    $act_data['postcode']='63136';
    $act_data['country_d1']='MO';
  }
    


  if($act_data['town']=='Halle' and $act_data['postcode']=='33790'){
    $act_data['country_d1']='Nordrhein-Westfalen';
      
  }
    
    
  if(preg_match('/^\s*\d{4,6}\s*$/',$act_data['town'])){
    $act_data['postcode']=$act_data['town'];
    $act_data['town']='';
  }
  if($act_data['town']=='Bilbao - Vizcaya'){
    $act_data['town']='Bilbao';
    $act_data['country_d2']='Vizcaya';
  }

  if($act_data['country']=='Balearic Isles'){
    $act_data['country']='Spain';
    $act_data['country_d1']='Balearic Islands';

  }


  if($act_data['country']=='Guernsey, C.i')
    $act_data['country']='Guernsey';
    
  if($act_data['town']=='Guernsey, C.i'){
    $act_data['town']='Guernsey';
    $act_data['country']='Guernsey';
  }

  if($act_data['town']=='South yorkshire'){
    $act_data['town']='';
      
    if($act_data['a3']!=''){
      $act_data['town']=$act_data['a3'];
      $act_data['a3']='';
    }else if($act_data['a2']!=''){
      $act_data['town']=$act_data['a2'];
      $act_data['a2']='';
    }
  }
    
  //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  // fix contracts
    
    
  $extra_contact=false;
  if($act_data['contact']!=''){

    $_contact=$act_data['contact'];
    $split_names=preg_split('/\s+and\s+|\&|\/|\s+or\s+/i',$act_data['contact']);
    if(count($split_names)==2){
      $split_names1=preg_split('/\s+/i',$split_names[0]);
      $split_names2=preg_split('/\s+/i',$split_names[1]);
      if(count($split_names1)==1 and count($split_names2)==2 ){
	$name1=$split_names1[0].' '.$split_names2[1];
	$name2=$split_names[1];
      }else{
	$name1=$split_names[0];
	$name2=$split_names[1];
      }
      $act_data['contact']=$name1;
      $extra_contact=$name2;
      if($_contact==$act_data['name']){
	$act_data['name']=preg_replace('/\s+and\s+|\&|\/|\s+or\s+/i',' & ',$act_data['name']);
      }

    }
    $there_is_contact=true;
  }else{
    $there_is_contact=false;
    if(!preg_match('/C \& P Trading|Peter \& Paul Ltd|Health.*Beauty.*Salon|plant.*herb/i',$act_data['name']))
      $act_data['contact']=$act_data['name'];

  }
    
  //  print $act_data['contact']." >>> $extra_contact   \n ";
    
  if($act_data['name']!=$act_data['contact'] )
    $tipo_customer='company';
  else{	  
    $tipo_customer='person';
      
      
      
  }
  
  //print_r($act_data);
  // print_r($header_data);

  //-----------------------------------------
  if(!isset($act_data['town_d1']))
    $act_data['town_d1']='';
  if(!isset($act_data['town_d2']))
    $act_data['town_d2']='';

  if(preg_match('/^c\/o/i',$act_data['a1'])){
    $co=$act_data['a1'];
    $act_data['a1']='';
  }
  if(preg_match('/^c\/o/i',$act_data['a2'])){
    $co=$act_data['a2'];
    $act_data['a2']='';
  }
  if(preg_match('/^c\/o/i',$act_data['a3'])){
    $co=$act_data['a3'];
    $act_data['a3']='';
  }

  $address_raw_data=get_address_raw();
  $address_raw_data['address1']=$act_data['a1'];
  $address_raw_data['address2']=$act_data['a2'];
  $address_raw_data['address3']=$act_data['a3'];
  $address_raw_data['town']=$act_data['town'];
  $address_raw_data['town_d1']=$act_data['town_d1'];
  $address_raw_data['town_d2']=$act_data['town_d2'];
  $address_raw_data['country_d2']=$act_data['country_d2'];
  $address_raw_data['postcode']=$act_data['postcode'];
  $address_raw_data['country']=$act_data['country'];
  if(isset($act_data['country_d1']))
    $address_raw_data['country_d1']=$act_data['country_d1'];


  

  $shop_address_data=guess_address($address_raw_data,array('country_id'=>30));

    
  $extra_id1=$act_data['act'];
  $extra_id2=$shop_address_data['postcode'];

  //  print "$different_delivery_address xxx";
  if($different_delivery_address){

 if(preg_match('/^c\/o/i',$header_data['address1'])){
    $co=$header_data['address1'];
    $header_data['address1']='';
  }
 if(preg_match('/^c\/o/i',$header_data['address2'])){
    $co=$header_data['address2'];
    $header_data['address2']='';
  }
 if(preg_match('/^c\/o/i',$header_data['address3'])){
    $co=$header_data['address3'];
    $header_data['address3']='';
  }

    $address_raw_data_del=get_address_raw();
    $address_raw_data_del['address1']=$header_data['address1'];
    $address_raw_data_del['address2']=$header_data['address2'];
    $address_raw_data_del['address3']=$header_data['address3'];
    $address_raw_data_del['town']=$header_data['city'];
    $address_raw_data_del['postcode']=$header_data['postcode'];
    $address_raw_data_del['country_d2']=$header_data['country_d2'];
    $address_raw_data_del['country_d1']=$header_data['country_d1'];
    $address_raw_data_del['country']=$header_data['country'];


    //print_r($address_raw_data_del);
    $del_address_data=guess_address($address_raw_data_del,array('country_id'=>30));
   
      
    $different_del_address=true;


    


    $a_diff=array_diff_assoc($del_address_data,$shop_address_data);

    if(isset($a_diff['country_d1_id']))
      unset($a_diff['country_d1_id']);
    if(isset($a_diff['country_d2_id']))
      unset($a_diff['country_d2_id']);
    //   print"***";

//print array_key_exists('postcode',$a_diff)."\n";

       foreach($a_diff as $key=>$value){
	 //print $del_address_data[$key]."** \n";
	 if(strtolower($del_address_data[$key])==strtolower($shop_address_data[$key]))
	   unset($a_diff[$key]);
       }
       if(count($a_diff)==0){
	 $different_del_address=false;
	 print "Same address\n";
       }
       
       //  print_r($del_address_data);
       // print_r($shop_address_data);



       //  print_r($a_diff);
       //exit;   
 if($shop_address_data['country_id']==75){
   if(count($a_diff)==1 and array_key_exists('postcode',$a_diff))
     $different_del_address=false;
   if(count($a_diff)==1 and array_key_exists('country_d2',$a_diff))
     $different_del_address=false;
    if(count($a_diff)==2 and array_key_exists('country_d2',$a_diff) 
       and array_key_exists('postcode',$a_diff))
     $different_del_address=false;

 }


    if(count($a_diff)==2){
    
      if(array_key_exists('postcode',$a_diff) and array_key_exists('country_d2',$a_diff)
	 and ($shop_address_data['country_id']==30 or 
	      $shop_address_data['country_id']==240 or 
	      $shop_address_data['country_id']==241 or
	      $shop_address_data['country_id']==242 
	      )){
	print "PC of the del address taken (a)\n";
	$different_del_address=false;
	$shop_address_data['postcode']=$del_address_data['postcode'];
      }
    }


  










    if(count($a_diff)==1){
    
      if(array_key_exists('postcode',$a_diff) 
	 and ($shop_address_data['country_id']==30 or 
	      $shop_address_data['country_id']==240 or 
	      $shop_address_data['country_id']==241 or
	      $shop_address_data['country_id']==242 
	      )){
	print "PC of the del address taken\n";
	$different_del_address=false;
	$shop_address_data['postcode']=$del_address_data['postcode'];
      }
      elseif(array_key_exists('country_d2',$a_diff) 
	     or array_key_exists('country_d1',$a_diff) 
	     ){
	print "D2 x of the del address taken\n";
	$different_del_address=false;

      }

    }
    //    print_r($shop_address_data);
    //print_r($del_address_data);
    //print "xca";
    //exit;
  }else{
    $del_address_data=$shop_address_data;
    $different_del_address=false;
  }





  $country_id=$shop_address_data['country_id'];
  $act_data['tel']=preg_replace('/\[\d*\]/','',$act_data['tel']);
  $act_data['tel']=preg_replace('/\(/','',$act_data['tel']);
  $act_data['tel']=preg_replace('/\)/','',$act_data['tel']);
  $act_data['fax']=preg_replace('/\[\d*\]/','',$act_data['fax']);
  $act_data['fax']=preg_replace('/\(/','',$act_data['fax']);
  $act_data['fax']=preg_replace('/\)/','',$act_data['fax']);
  $act_data['mob']=preg_replace('/\[\d*\]/','',$act_data['mob']);
  $act_data['mob']=preg_replace('/\(/','',$act_data['mob']);
  $act_data['mob']=preg_replace('/\)/','',$act_data['mob']);
  $tel_data=guess_tel($act_data['tel'],$country_id);
  $fax_data=guess_tel($act_data['fax'],$country_id);
  $mob_data=guess_tel($act_data['mob'],$country_id);

    
  $principal_tel_is_mobile=false;
  if($mob_data and  $tel_data and !$mob_data['is_mobile'] and $tel_data['is_mobile']){
    // meansd the they swift the tel and te mobile phone is the rtpncipal contact
    $principal_tel_is_mobile=true;
    $tmp=$tel_data;
    $tel_data=$mob_data;
    $mob_data=$tmp;

  }
  // if the fax and tel is the same 
  $same_faxtel=false;
  if($fax_data and  $tel_data and $tel_data['number']==$fax_data['number']){
    // meansd the they swift the tel and te mobile phone is the rtpncipal contact
    $same_faxtel=true;
    $fax_data=false; 
  }


  if($tel_data and $tel_data['number']==$fax_data['number']){
    // meansd the they swift the tel and te mobile phone is the rtpncipal contact
    $same_faxtel=true;
    $fax_data=false; 
  }


  // first chech id we hae repetitions
  if($mob_data and $fax_data and $mob_data['number']==$fax_data['number']){
    if($mob_data['is_mobile']){
      $fax_data=false;
    }else{
      $mob_data=false;
    }
  }

  //    print $act_data['mob']."sssssssssssss\n";
  $extra_tel_data=false;
  $extra_mob_data=false;
    
  if($fax_data and $fax_data['is_mobile']){
    if(!$mob_data){
      $mob_data=$fax_data;
      $fax_data=false;
    }else{
      $extra_mob_data=$fax_data;
      $fax_data=false;
    }
  }


  if($tel_data and $tel_data['is_mobile']){
    if(!$mob_data){
      $mob_data=$tel_data;
      $tel_data=false;
    }else{
      $extra_mob_data=$tel_data;
      $tel_data=false;
    }
  }

  if($mob_data and !$mob_data['is_mobile']){
    if(!$tel_data){
      $tel_data=$mob_data;
      $mob_data=false;
    }else{
      $extra_tel_data=$mob_data;
      $mob_data=false;
    }
  }

  //print_r($tel_data);
  //print_r($fax_data);
  //print_r($mob_data);
  //print_r($extra_tel_data);
  //print_r($extra_mob_data);

  $email_data=guess_email($act_data['email']);
  
  // print_r($email_data);
  //print "$tipo_customer\n";
  //print_r($act_data);
    
  $matches=get_matches($email_data['email'],$mob_data['number'],$tel_data['number'],$fax_data['number'],$act_data['name'],$act_data['contact'],$shop_address_data,$act_data['act']);
  
    
  // print "***** $date_index  ***";
  // print_r($matches);

  if(count($matches)>0  and $matches[0]['score']==81 )
    print "Warning match 81 N:".$act_data['name']." C:".$act_data['contact']."  \n";
  if(count($matches)==0  or (   count($matches)>0 and $matches[0]['score']<100        ) ){
    // MEW CONTCT
    //      print $act_data['name']."     $tipo_customer  ddddd\n";

    $contact_id=insert_contact($act_data['name'],$tipo_customer,0,0,$date_index,$extra_id1,$extra_id2,'',true,($this_is_order_number==1?true:false));
      
    $tel_id=edit_contact('add','tel',$contact_id,$date_index,$tel_data,false,false,false);
    $fax_id=edit_contact('add','fax',$contact_id,$date_index,$fax_data,false,false,false);
    $mob_id=edit_contact('add','mob',$contact_id,$date_index,$mob_data,false,false,false);
    $extra_tel_id=edit_contact('add','tel',$contact_id,$date_index,$extra_tel_data,false,false,false);
    $extra_mob_id=edit_contact('add','fax',$contact_id,$date_index,$extra_mob_data,false,false,false);
    //print "cacacaca $contact_id \n";
    $shop_address_id= edit_contact('add','shop_address',$contact_id,$date_index,$shop_address_data,false,false,false);
    // print "cacacaca\n";
    edit_contact('set_principal','shop_address',$contact_id,$date_index,$shop_address_id,false,false,false);
      

    if($tel_id>0)
      edit_contact('set_principal','tel',$contact_id,$date_index,$tel_id,'','',false);
    else if ($mob_id>0)
      edit_contact('set_principal','mob',$contact_id,$date_index,$mob_id,'','',false);
     

 

    if($different_del_address){
      $del_address_id=edit_contact('add','del_address',$contact_id,$date_index,$del_address_data,false,false,false);
      
    }
    else{
      edit_contact('associate','del_address',$contact_id,$date_index,$shop_address_id,false,false,false);
      $del_address_id=$shop_address_id;
    }


    if($tipo_customer=='company'){
	
      //associate telecoms
      edit_contact('associate','tel',$contact_id,$date_index,$tel_id,1,false,false);
      edit_contact('associate','fax',$contact_id,$date_index,$fax_id,2,false,false);
      edit_contact('associate','tel',$contact_id,$date_index,$extra_tel_id,1,false,false);
	
      // customer is a COMPANY ----------------------------------------------------------
      if($act_data['contact']!=''){
	// have contact ----------------------------------------------------------

	$child_id=insert_contact($act_data['contact'],'person',1,0,$date_index,'','','',true,($this_is_order_number==1?true:false));
	set_contact_relation($contact_id,$child_id);

	edit_contact('set_principal','child',$contact_id,$date_index,$child_id,false,false,false);
	edit_contact('set_principal','child',$child_id,$date_index,$child_id,false,false,false);
	if($mob_id>0)
	  edit_contact('set_principal','mob',$child_id,$date_index,$mob_id,'','',false);
	else if ($tel_id>0)
	  edit_contact('set_principal','tel',$child_id,$date_index,$tel_id,'','',false);
     

	edit_contact('associate','tel',$child_id,$date_index,$tel_id,4,false,false);
	edit_contact('associate','fax',$child_id,$date_index,$fax_id,5,false,false);
	edit_contact('associate','tel',$child_id,$date_index,$extra_tel_id,4,false,false);
	edit_contact('associate','mob',$child_id,$date_index,$mob_id,3,false,false);
	edit_contact('associate','mob',$child_id,$date_index,$extra_mob_id,3,false,false);
	if($same_faxtel)
	  edit_contact('associate','fax',$contact_id,$date_index,$tel_id,2,false,false);
	$email_data=guess_email($act_data['email'],get_name($child_id));

	$email_id=edit_contact('add','email',$child_id,$date_index,$email_data,false,false,false);
	if($email_id>0){
	  edit_contact('set_principal','email',$contact_id,$date_index,$email_id,false,false,false);
	  edit_contact('set_principal','email',$child_id,$date_index,$email_id,false,false,false);
	}
	if($extra_contact){
	  $extra_child_id=insert_contact($extra_contact,'person',1,0,$date_index,'','','',true,($this_is_order_number==1?true:false));
	  set_contact_relation($contact_id,$extra_child_id);
	  edit_contact('associate','tel',$extra_child_id,$date_index,$tel_id,4,false,false);
	  edit_contact('associate','fax',$extra_child_id,$date_index,$fax_id,5,false,false);
	  edit_contact('associate','tel',$extra_child_id,$date_index,$extra_tel_id,4,false,false);
	  if ($tel_id>0)
	    edit_contact('set_principal','tel',$extra_child_id,$date_index,$tel_id,'','',false);
	  edit_contact('set_principal','child',$extra_child_id,$date_index,$extra_child_id,false,false,false);

	}
      }else{
	// DO NOT HAVE contact ----------------------------------------------------------
	edit_contact('associate','tel',$contact_id,$date_index,$mob_id,1,false,false);
	edit_contact('associate','tel',$contact_id,$date_index,$extra_mob_id,1,false,false);

	$email_data=guess_email($act_data['email'],get_name($contact_id));

	$email_id=edit_contact('add','email',$contact_id,$date_index,$email_data,false,false,false);
	edit_contact('set_principal','email',$contact_id,$date_index,$email_id,false,false,false);
	//  edit_contact('set_principal','child',$contact_id,$date_index,$contact_id,false,false,false);
	print "$tipo_customer WARNING NO CONTACT\n";
      }

      // customer is a person ---------------------------------------------------
    }else if($tipo_customer=='person'){// No child it ussualy means the customer is a pernon not a company

      edit_contact('associate','tel',$contact_id,$date_index,$tel_id,1,false,false);
      edit_contact('associate','fax',$contact_id,$date_index,$fax_id,2,false,false);
      edit_contact('associate','mob',$contact_id,$date_index,$mob_id,3,false,false);
      edit_contact('associate','mob',$contact_id,$date_index,$extra_mob_id,3,false,false);
      edit_contact('associate','tel',$contact_id,$date_index,$extra_tel_id,1,false,false);

	  
      $email_data=guess_email($act_data['email'],get_name($contact_id));
      edit_contact('add','email',$contact_id,$date_index,$email_data,false,false,false);
      edit_contact('set_principal','child',$contact_id,$date_index,$contact_id,false,false,false);
    }




    // insert new customwer
      
      
    $groups=get_customer_groups($shop_address_data,$header_data,$act_data);



    $customer_id = insert_customer($contact_id,$groups,$date_index,($this_is_order_number==1?true:false));


    $new_customer=true;

    //  print "cacaca";
    edit_contact('set_principal','del_address',$contact_id,$date_index,$del_address_id,false,false,false);


    //print "new $contact_id\n";
  }else{
    // Found older contact
    // print "Old contact\n";
    $contact_id=$matches[0]['contact'];
    //print "OLD CONTACT   $contact_id  \n";
    $contact_data=get_contact_data($contact_id);


    if($contact_data['tipo']==1 and $tipo_customer=='company'){
    
       $tmp['date']=$date_index;
      $tmp['name']=get_name($contact_id);

      change_tipo_contact($contact_id,0,$date_index,true);
      update_contact_name($contact_id,$act_data['name'],$contact_data['name'],$date_index,false);
      // insert old thin as contacr


      $child_id=edit_contact('add','child',$contact_id,$date_index,$tmp,'','',false);
      //      exit;
      
    }

    //	print "$contact_id \n";
    //	print_r( $act_data);
    //	print_r($contact_data);

    $is_a_kid=false;
    // hacemmos esto en el caso de que se encuentre un mach pero la nueva invoice es
    // del child (contacto) no de la empresa, referenciando la orden al contacto :)
    foreach($contact_data['child'] as $child_data){
      similar_text($child_data['name'],$act_data['name'],$tmp);
      if($tmp>95){
	$is_a_kid=$child_data['id'];
	break;
      }
    }

    if($is_a_kid){
      print "IS A KID OF A PREVOUS CONTACT\n";
      $contact_email=display_person_name(guess_name($act_data['name']));
      $name_data=$contact_email;
      $email_data=guess_email($act_data['email'],  $contact_email );


      $customer_id =get_customer_id($is_a_kid);
      if(!$customer_id){
	$groups=get_customer_groups($shop_address_data,$header_data,$act_data);
	$customer_id = insert_customer($is_a_kid,$groups,$date_index,($this_is_order_number==1?true:false));
	$new_customer=true;
      }else
	$new_customer=false;
      
      $tmp=update_contact($is_a_kid,array('name'=>$name_data,'child'=>false,'email'=>$email_data,
					  'tel'=>$tel_data,
					  'mob'=>$mob_data,
					  'fax'=>$fax_data
					  ,'shop_address'=>$shop_address_data,'del_address'=>$del_address_data),$date_index);

	  
      $shop_address_id=$tmp['shop_address'];
      $del_address_id=$tmp['del_address'];
	  


      $bill_address_id='false';
      return array($is_a_kid,$customer_id,$shop_address_id,$del_address_id,$bill_address_id,$new_customer,$co);
      exit;
    }


	

    $customer_id =get_customer_id($contact_id);
	
	
    //		print       $matches[0]['score']." Old order detected $contact_id  $customer_id\n";
    //      	exit;
    $contact_data=guess_name($act_data['contact']);

    //    $email_data=guess_email($act_data['email'],($act_data['name']!=$act_data['contact'] and $act_data['contact']!=''?display_person_name($contact_data):display_person_name($name_data)  ));
    if($act_data['name']!=$act_data['contact'] and $act_data['contact']!=''){
      $name_data=$act_data['name'];
      $contact_email=display_person_name($contact_data);
    }
    else{
      // no child
      $contact_email=display_person_name(guess_name($act_data['name']));
      $name_data=$contact_email;
      $contact_data=false;
    }
    $email_data=guess_email($act_data['email'],  $contact_email );
      

    //      list($address_id,$del_address_id)
    $tmp=update_contact($contact_id,array('name'=>$name_data,'child'=>$contact_data,'email'=>$email_data,'tel'=>$tel_data,'mob'=>$mob_data,'fax'=>$fax_data,'shop_address'=>$shop_address_data,'del_address'=>$del_address_data),$date_index);
    //print_r($tmp);
    $shop_address_id=$tmp['shop_address'];
    $del_address_id=$tmp['del_address'];

    //print "old $contact_id\n";
  }

  if(!isset($customer_id))
    print('error no customer id');
    
  //    print "$shop_address_id,$del_address_id\n";
    
  $bill_address_id='';
  //  exit;
  return array($contact_id,$customer_id,$shop_address_id,$del_address_id,$bill_address_id,$new_customer,$co);
}







function read_header($raw_header_data,$map_act,$y_map,$map){

  //$new_mem=memory_get_usage(true);
  //    print"x$new_mem x ";
     
  $act_data=array();
  $header_data=array();
  //first read the act part

  $raw_act_data=array_shift($raw_header_data);
  // print_r($raw_act_data);
  if($raw_act_data){

    foreach($raw_act_data as $key=>$col){
      $cols[$key]=mb_convert_encoding($col, "UTF-8", "ISO-8859-1");

    }
    //     print_r($cols);
    //exit;
    $act_data['name']=mb_ucwords($cols[$map_act['name']]);
    $act_data['contact']=mb_ucwords($cols[$map_act['contact']]);
    if($act_data['name']=='' and $act_data['contact']!='') // Fix only contact
      $act_data['name']=$act_data['contact'];
    $act_data['first_name']=mb_ucwords($cols[$map_act['first_name']]);
    $act_data['a1']=mb_ucwords($cols[$map_act['a1']]);
    $act_data['a2']=mb_ucwords($cols[$map_act['a2']]);
    $act_data['a3']=mb_ucwords($cols[$map_act['a3']]);
    $act_data['town']=mb_ucwords($cols[$map_act['town']]);
    $act_data['country_d2']=mb_ucwords($cols[$map_act['country_d2']]);
    $act_data['postcode']=$cols[$map_act['postcode']];
    $act_data['country']=mb_ucwords($cols[$map_act['country']]);
    $act_data['tel']=$cols[$map_act['tel']];
    $act_data['fax']=$cols[$map_act['fax']];
    $act_data['mob']=$cols[$map_act['mob']];
    $act_data['source']=$cols[$map_act['source']];
    $act_data['act']=$cols[$map_act['act']];
    $act_data['email']=$cols[count($cols)-1];
    $act_data['country_d1']='';
    //  if($act_data['a1']==0)$act_data['a1']='';
    //if($act_data['a2']==0)$act_data['a2']='';
    //if($act_data['a3']==0)$act_data['a3']='';

      

  }
  
  // print $raw_header_data[9][5]." $map\n";
  //print_r($map);
  
  //print_r($raw_header_data);

  foreach($map as $key=>$map_data){
    if($map_data){
      $_data=$raw_header_data[$map_data['row']][$map_data['col']];

      $_data=mb_convert_encoding($_data, "UTF-8", "ISO-8859-1");

      
      if(isset($map_data['tipo']))
	$tipo=$map_data['tipo'];
      else
	$tipo='';
      switch($tipo){
      case('name'):
	$_data=_trim($_data);
	if($_data=='0')$_data='';
	$header[$key]=$_data;

	break;
      case('name'):
	$_data=_trim($_data);
	if($_data=='0')$_data='';
	$header[$key]=mb_ucwords($_data);

	break;
      case('date'):

	$header[$key]=date("Y-m-d",mktime(0, 0, 0, 1 , $_data-1, 1900));
	break;
      default:
	$header[$key]=$_data;
	break;
      }
    }else
      $header[$key]='';
  }
  
  if($header['feedback']=='SinBinBoth'){
    $header['feedback']=1;
  }elseif($header['feedback']=='SinBinPick'){
    $header['feedback']=2;
  }elseif($header['feedback']=='SinBinPack'){
    $header['feedback']=3;
  }else
     $header['feedback']=0;
  

  $new_mem=memory_get_usage(true);
  // print"x$new_mem x ";
    

  return array($act_data,$header);

}



function read_records($handle_csv,$y_map,$number_header_rows){



  $first_order_bonus=false;
    
  $re_order=true;
  if(isset($y_map['no_reorder']) and $y_map['no_reorder'] )
    $re_order=false;

  $header=array(false);
  $products=array();
  $act=false;
  $row=0;
  while(($cols = fgetcsv($handle_csv))!== false){

    if($row<$number_header_rows){// is a header data
      $header[]=$cols;
    }else{
      //      i
   //    if(isset($cols[3])){
// 	if(preg_match('/wsl-1513/i',$cols[3])  ){
// 	  print_r($cols);
// 	print $y_map['bonus']."\n ";
// 	}
//       }
      // print count($cols)."\n";

      if(count($cols)<$y_map['discount'])
	continue;

      if(preg_match('/First Order Bonus - Welcome/i',$cols[$y_map['description']]))
	$first_order_bonus=true;

      //  if($cols[$y_map['code']]=='Pack-29')
      //	print $y_map['bonus'];
      if(
	 (
	    


	  $cols[$y_map['code']]!=''
	  and (is_numeric($cols[$y_map['credit']]) or $cols[$y_map['discount']]==1   )
	  and $cols[$y_map['description']]!='' 
	  and (is_numeric($cols[$y_map['price']]) or $cols[$y_map['price']]==''  ) 
	  and (  ( is_numeric($cols[$y_map['order']])   and  $cols[$y_map['order']]!=0   )   
		 or ( is_numeric($cols[$y_map['reorder']])   and  $cols[$y_map['reorder']]!=0   and $re_order   )  
		 or ( is_numeric($cols[$y_map['bonus']])   and  $cols[$y_map['bonus']]!=0   ) )  
	  )or (preg_match('/credit/i',$cols[$y_map['code']])   and  $cols[$y_map['price']]!='' and  $cols[$y_map['price']]!=0  )
	 ){
	$cols['fob']=$first_order_bonus;
	$products[]=$cols;
      }else if(preg_match('/^public\d*$|^nic$/i',$cols[0])  )
	$header[0]=$cols;
     
    }
    $row++;
  }
  // print_r($products);
  // exit;
  return array($header,$products);

}

function set_pickers_and_packers($order_id,$header_data){
  $db =& MDB2::singleton();
  $picker_ids=get_user_id($header_data['pickedby'],$order_id,'picked');
  $packer_ids=get_user_id($header_data['packedby'],$order_id,'packed');

  if(count($picker_ids)==0){
    $sql=sprintf('insert into pick (order_id,picker_id,share) values (%d,0,1.00)',$order_id);
    //$db->exec($sql);
    mysql_query($sql);
  }
  if(count($packer_ids)==0){
    $sql=sprintf('insert into pack (order_id,packer_id,share) values (%d,0,1.00)',$order_id);
    //$db->exec($sql);
    mysql_query($sql);
  } 

  foreach($picker_ids as $picker_id){
    $share=1/count($picker_ids);
    $sql=sprintf('insert into pick (order_id,picker_id,share) values (%d,%d,%.2f)',$order_id,$picker_id,$share);
    // $db->exec($sql);
    mysql_query($sql);
  }
  foreach($packer_ids as $packer_id){
    $share=1/count($packer_ids);
    $sql=sprintf('insert into pack (order_id,packer_id,share) values (%d,%d,%.2f)',$order_id,$packer_id,$share);
    //$db->exec($sql);
    mysql_query($sql);
  }
  

}


function delete_transactions($order_id){

  $sql=sprintf("delete from bonus where order_id=%d",$order_id); mysql_query($sql);
  $sql=sprintf("delete from transaction where order_id=%d",$order_id); mysql_query($sql);
  $sql=sprintf("delete from todo_transaction where order_id=%d",$order_id); mysql_query($sql);
  $sql=sprintf("delete from outofstock where order_id=%d",$order_id); mysql_query($sql);
  $sql=sprintf("delete from debit where tipo=2 and order_affected_id=%d",$order_id); mysql_query($sql);
}


?>
