<?

function change_tax_number($customer_id,$tax_number,$date_index,$history=false){
  $db =& MDB2::singleton();

  $tax_number=strtoupper($tax_number);
  // Chack id thes is already a tax number
  $data=get_customer_data($customer_id);
  $old_tax_number=$data['tax_number'];

  if($old_tax_number==''){// Insert
    $sql=sprintf("update customer set tax_number=%s where id=%d",prepare_mysql($tax_number),$customer_id);
    //  print "$sql\n";
    mysql_query($sql);
    if($history){
      $sql=sprintf("insert into history (tipo,sujeto,sujeto_id,objeto,objeto_id,date) values ('NEW','Customer',%d,'Tax Number',NULL,%s)",$customer_id,$date_index);
      mysql_query($sql);
      //    print "$sql\n";
      $history_id=mysql_insert_id();
      $sql=sprintf("insert into history_item (history_id,columna,old_value,new_value) values (%d,'Tax Number',NULL,%s)",$history_id,prepare_mysql($tax_number));
       mysql_query($sql);
    }

  }else if ($old_tax_number!=$tax_number){
      $sql=sprintf("update customer set tax_number=%s where id=%d",prepare_mysql($tax_number),$customer_id);
    mysql_query($sql);
    if($history){
      $sql=sprintf("insert into history (tipo,sujeto,sujeto_id,objeto,objeto_id,date) values ('NEW','Customer',%d,'Tax Number',NULL,%s)",$customer_id,$date_index);
      mysql_query($sql);
      $history_id=mysql_insert_id();
      $sql=sprintf("insert into history_item (history_id,columna,old_value,new_value) values (%d,'Tax Number',%s,%s)",$history_id,prepare_mysql($old_tax_number),prepare_mysql($tax_number));
       mysql_query($sql);
    }
    

  }


}



function get_customer_country_id($customer_id){
  $db =& MDB2::singleton();
  $sql=sprintf("select list_country.id as x from customer left join contact on (contact_id=contact.id) left join address on (main_address=address.id) left join list_country on (country=list_country.name) where customer.id=%d",$customer_id);
  //print $sql;
   $res = $db->query($sql);  
  if ($row=$res->fetchRow()){
    return $row['x'];
  }else
    return false;


}


function get_customer_from_contact($contact_id){
$db =& MDB2::singleton();
  $sql=sprintf("select id from customer where contact_id=%d",$contact_id);
  $res = $db->query($sql);  
  if ($row=$res->fetchRow()){
    return $row['id'];
  }else
    return false;


}

function insert_customer($contact_id,$groups,$date_index='',$safe_stats_date=true){
  $db =& MDB2::singleton();

  if(!$safe_stats_date)
    $date_index='NULL';



  if(is_numeric($contact_id))
    $contact_data=get_contact_data($contact_id);
  else{ 
    $contact_data=array('name'=>'Unknown Customer','file_as'=>'Unknown Customer');
    $contact_id='NULL';
   }
  // print_r($contact_data);
$sql=sprintf("insert into customer (contact_id,name,file_as) values (%d,%s,%s)",$contact_id, prepare_mysql($contact_data['name']), prepare_mysql($contact_data['file_as']));
  

   mysql_query($sql);
   $customer_id=mysql_insert_id();
   if(!($customer_id>0)){
     print "error inserting customer\n $sql\n";
     exit;
   }
   
   // print "$sql\n";

  $tipo='Customer';
 $tipo=mb_ucwords($tipo);

  
 $sql=sprintf("insert into history (tipo,sujeto,sujeto_id,objeto,objeto_id,date) values ('NEW','Customers',null,'%s',%d,%s)",$tipo,$customer_id,$date_index);
 // print "$sql\n";
  //  $db->exec($sql);
  // $history_id=$db->lastInsertID();
    mysql_query($sql);
      $history_id=mysql_insert_id();

  $sql=sprintf("insert into history_item (history_id,columna,old_value,new_value) values (%d,'%s',NULL,%s)",$history_id,$tipo,prepare_mysql($contact_data['name']));
  //print "$sql\n";
   //$db->exec($sql);
    mysql_query($sql);
// exit;
    //  print_r($groups);
    foreach($groups as $group_id){
      $sql=sprintf("insert into customer2group  (customer_id,group_id) values (%d,%d)",$customer_id,$group_id);
       mysql_query($sql);
    }


 return $customer_id;

}

function get_customer_data($customer_id){
 $db =& MDB2::singleton();
  $sql=sprintf("select * from customer where id=%d",$customer_id);
  $res = $db->query($sql);  
  if ($row=$res->fetchRow()){

    $sql=sprintf("select group_id from customer2group  where customer_id=%d",$customer_id);
    $res2 = $db->query($sql);  
    $row['group']=array();
    while ($row2=$res2->fetchRow()){
      $row['group'][]=$row2['group_id'];
    }


    return $row;
  }else
    return false;
}


function update_customer_name($customer_id,$new_name,$date_index=''){
  $db =& MDB2::singleton(); 
  $customer_data=get_customer_data($customer_id);
  $old_name=$customer_data['name'];

  if(strcmp($new_name,$old_name) or $new_name=='')
    return false;
  
  $sql=sprintf("update customer set name=%s where id=%d",prepare_mysql($new_name),$customer_id);
  //$db->exec($sql);
   mysql_query($sql);
  $tipo='customer';$tipo=mb_ucwords($tipo);
$obj='name';$obj=mb_ucwords($obj);
 
 
 $sql=sprintf("insert into history (tipo,sujeto,sujeto_id,objeto,objeto_id,date) values (1,null,null,'%s',%d,%s)",prepare_mysql($tipo),prepare_mysql_date($date_index));
 //print "$sql\n";
 // $db->exec($sql);
 //$history_id=$db->lastInsertID();
 mysql_query($sql);
      $history_id=mysql_insert_id();


  $sql=sprintf("insert into history_item (history_id,columna,old_value,new_value) values (%d,'%s',NULL,%s)",$history_id,prepare_mysql($obj),prepare_mysql($old_name),prepare_mysql($new_name));
  //print "$sql\n";
  //$db->exec($sql);
     mysql_query($sql);
}


function update_customer($customer_id,$since='0000-00-00'){

  if(!$customer_id){
    //exit('no customer id');
    return false;
  }
   $db =& MDB2::singleton();
  








  $sql="select  count(*) as orders from orden where customer_id=".$customer_id;
  //print "$sql\n";
  $res = $db->query($sql);
    if ($row = $res->fetchRow() ) {
      $total_orders=$row['orders'];
  }
      
$sql="select  sum(total) as total,sum(net) as total_net   from orden where  (tipo=2 ) and customer_id=".$customer_id;
  //print "$sql\n";
  $res = $db->query($sql);
    if ($row = $res->fetchRow() ) {
      $total=$row['total'];
      $total_net=$row['total_net'];
  }
      



   
  $sql="select  count(*) as num from orden where tipo=2 and  customer_id=".$customer_id;
  $res = $db->query($sql);
  if ($row = $res->fetchRow() )
    $invoices=$row['num'];
  else
    $invoices=0;

 $sql="select  count(*) as num from orden where tipo=1 and  customer_id=".$customer_id;
  $res = $db->query($sql);
  if ($row = $res->fetchRow() )
    $pro_invoices=$row['num'];
  else
    $pro_invoices=0;
 $sql="select  count(*) as num from orden where tipo=3 and  customer_id=".$customer_id;
  $res = $db->query($sql);
  if ($row = $res->fetchRow() )
    $cancels=$row['num'];
  else
    $cancels=0;
 $sql="select  count(*) as num from orden where (tipo=6 or tipo=7 or tipo=9 or tipo=8) and  customer_id=".$customer_id;
  $res = $db->query($sql);
  if ($row = $res->fetchRow() )
    $follows=$row['num'];
  else
    $follows=0;


  $others=$total_orders-$follows-$cancels-$pro_invoices-$invoices;
  $_total_orders=$total_orders-$follows;

  $sql=sprintf("select  date_index as last  from orden where   !(tipo=6 or tipo=7 or tipo=9 or tipo=8) and customer_id=%d order by date_index desc limit 1",$customer_id);
     //print "$sql\n";
  $res = $db->query($sql);
  if ($row = $res->fetchRow() ) {
    $date_last=$row['last'];
  }else{

     $sql=sprintf("select  date_index as last  from orden where   customer_id=%d order by date_index desc limit 1",$customer_id);
     //print "$sql\n";
     $res2 = $db->query($sql);
     if ($row2 = $res2->fetchRow() ) {
       $date_last=$row2['last'];
     }else{
       print "Warning customer with out orders!!!!!!!!!!!\n";
       $date_last='';
     }

  }


 $sql=sprintf("select  date_index as first  from orden where   !(tipo=6 or tipo=7 or tipo=9 or tipo=8) and customer_id=%d order by date_index  limit 1",$customer_id);
     //print "$sql\n";
  $res = $db->query($sql);
  if ($row = $res->fetchRow() ) {
    $date_first=$row['first'];
  }else{

    $sql=sprintf("select  date_index as first  from orden where   customer_id=%d order by date_index  limit 1",$customer_id);
     //print "$sql\n";
     $res2 = $db->query($sql);
     if ($row2 = $res2->fetchRow() ) {
       $date_first=$row2['first'];
     }else{
       print "Warning customer with out orders!!!!!!!!!!!\n";
       $date_first='';
     }


  }
   
  


    
  $sql=sprintf("select  count(*) as num  from orden where  date_index> %s and (tipo=1 or tipo=2) and customer_id=%d",prepare_mysql($since),$customer_id);
  //print "$sql\n";
    $res = $db->query($sql);
   if ($row = $res->fetchRow() ) {
     $invoices_in_interval=$row['num'];
   }else
     $invoices_in_interval=0;
   
   if($invoices_in_interval>1){
     
     $sql=sprintf("select  UNIX_TIMESTAMP(date_processed) as d2  from orden where  date_index> %s and (tipo=1 or tipo=2) and customer_id=%d order by date_index desc limit 1",prepare_mysql($since),$customer_id);
     //print "$sql\n";
   $res = $db->query($sql);
      if ($row = $res->fetchRow() ) {
	$date2=$row['d2'];
      }
      
      $sql=sprintf("select  date_index,UNIX_TIMESTAMP(date_processed) as d1  from orden where date_index>%s and  (tipo=1 or tipo=2) and customer_id=%d order by date_index  limit 1",prepare_mysql($since),$customer_id);
      //  print "$sql\n";
      $res = $db->query($sql);
      if ($row = $res->fetchRow() ) {
	$date1=$row['d1'];
      }
 
      $interval= number_format(($date2-$date1)/($invoices_in_interval-1)/24/3600,1,'.','');
   }else
     $interval='null';


   $total_nodata_net=0;
   $customer_data=get_customer_data($customer_id);
   $orders_nodata=$customer_data['num_orders_nd'];
   if($customer_data['num_orders_nd']>0){
     if($total_orders>0){
       $invoice_factor=($total_orders-$cancels)/$total_orders;
       $invoices_nodata=round($invoice_factor*$orders_nodata);
       if($invoices>0){
	 $average_invoice_total=$total/$invoices;
	 $average_invoice_total_net=$total_net/$invoices;
	 $total_nodata=$average_invoice_total*$invoices_nodata;
	 $total_nodata_net=$average_invoice_total_net*$invoices_nodata;
       }else
	 $total_nodata=0;
     }else{
       $total_nodata=0;
       print "Warning: Total number of order zero whe set up customer history\n";
     }
   }else{
     $invoices_nodata=0;
     $total_nodata=0;
   }

 

   $sql=sprintf("update customer set  first_order=%s,total_net_nd='%.2f',total_net='%.2f',num_orders=%d,total='%.2f',num_invoices='%d',num_pro_invoices='%d',num_cancels='%d',num_follows='%d',num_others='%d', order_interval=%s, last_order=%s,num_invoices_nd='%d',total_nd='%.2f' where id=%d   ",prepare_mysql_date($date_first),$total_nodata_net,$total_net,$_total_orders,$total,$invoices,$pro_invoices,$cancels,$follows,$others,$interval,prepare_mysql_date($date_last),$invoices_nodata,$total_nodata,$customer_id);
   // print "$sql\n";
   //  $db->exec($sql);
   mysql_query($sql);
  // exit("updating cust\n");


   // FIx islands
   $sql="delete from history where tipo='ISL' and objeto='Start' and sujeto_id=".$customer_id;
   //   print $sql;
   mysql_query($sql);
   $sql="select date,id  from history where tipo='ISL' and objeto='End' and sujeto_id=".$customer_id;
   //  print "$sql\n";
  $res = $db->query($sql);
  $island_number=0;
    while ($row = $res->fetchRow() ) {
      $island_number++;
        $sql="update history set objeto_id='".$island_number."' where id=".$row['id'];
	mysql_query($sql);
	//	print $sql;

      // get last recorded order;
      $sql=sprintf("select  date_processed  from orden where  customer_id=%d and date_processed<'".$row['date']."' order by date_index desc limit 1",$customer_id);
      //print "$sql\n";
      $res2 = $db->query($sql);
      if ($row2 = $res2->fetchRow() ) {
	$_date=$row2['date_processed'];
	$sql=sprintf("insert into history (tipo,sujeto,sujeto_id,objeto,objeto_id,date) values ('ISL','Customer',%d,'Start',%d,%s)",$customer_id,$island_number,prepare_mysql_date($_date));
	//	print "$sql\n";
	mysql_query($sql);

      }


  }
}
 function redo_customer_history_all($customer_id){
    $db =& MDB2::singleton();
   $sql=sprintf("select id from customer");
   $res = $db->query($sql); 
   while($row=$res->fetchRow()) {
     redo_customer_history($row['id']);
   }
   
 }

    function redo_customer_history($customer_id){
       $db =& MDB2::singleton();
      // delete provous history
      $sql=sprintf("delete from customer_history where customer_id=%d",$customer_id);
       mysql_query($sql);
       
       
       $sql=sprintf("select history.id as history_id,date as date_index  from customer left join history  on (sujeto_id=contact_id) left join history_item on (history_id=history.id)   where sujeto='Contact'   and (tipo='NEW' or tipo='UPD') and  customer.id=%d ",$customer_id);

       $res = $db->query($sql); 
       while($row=$res->fetchRow()) {
	 $data[]=array(
		       'date_index'=>$row['date_index'],
		       'op'=>'h_cont',
		       'op_id'=>$row['history_id']
		       );
       }
       
       $sql=sprintf("select history.id as history_id,date,tipo,objeto,date as date_index  from history  where sujeto='Customer' and sujeto_id=%d and tipo='NEW'  ",$customer_id);

       $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
       while($row=$res->fetchRow()) {
	 $data[]=array(
		       'date_index'=>$row['date_index'],
		   'op'=>'h_cust',
		       'op_id'=>$row['history_id']
		       );
       }
       $sql=sprintf("select orden.id as order_id,net,parent_id,tipo,id,public_id ,date_index as date_index from orden  where customer_id=%d",$customer_id);

       $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
       
       while($row=$res->fetchRow()) {
	 $data[]=array(
		       'date_index'=>$row['date_index'],
		       'op'=>'orden',
		       'op_id'=>$row['order_id']
		       );
       }
       
       $sql=sprintf("select id,texto ,date_index as date_index from note where op='customer' and op_id=%d",$customer_id);

       $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
       
       while($row=$res->fetchRow()) {
	 $data[]=array(
		       'date_index'=>$row['date_index'],
		       'op'=>'note',
		       'op_id'=>$row['id']
		       );
       }
       
       foreach($data as $_data){
	 $sql=sprintf("insert into  customer_history (customer_id,date_index,op,op_id) values(%d,'%s','%s',%d)",$customer_id,$_data['date_index'],$_data['op'],$_data['op_id']);
	 mysql_query($sql);
       

       }
       
    }


?>