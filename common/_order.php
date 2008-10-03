<?

function get_orderfile_data($id){
  $db =& MDB2::singleton();
  $sql=sprintf("select *  from orden_file where order_id=%d",$id);
  // print "$sql";
  $res = $db->query($sql);  
  if ($row=$res->fetchRow())
    return $row;
  else
    return false;
  
}

function get_order_data_from_name($name){
  $db =& MDB2::singleton();
  $sql=sprintf("select id  from orden where public_id=%s",prepare_mysql($name));
  $res = $db->query($sql);  
  if ($row=$res->fetchRow())
    return get_order_data($row['id']);
  else
    return false;
  
}

function get_order_data($order_id){
  $db =& MDB2::singleton();
  $sql=sprintf("select *,UNIX_TIMESTAMP(date_invoiced) as date_invoiced ,UNIX_TIMESTAMP(date_creation) as date_creation  from orden where id=%d",$order_id);


  $res = $db->query($sql);  
  if ($row=$res->fetchRow()){
    $order_data=$row;
    
    // get other quantities
    $shipping_vateable=0;
    $shipping_no_vateable=0;
    $charges_vateable=0;
    $charges_no_vateable=0;
    $items_vateable=0;
    $items_no_vateable=0;
    $credits_vateable=0;
    $credits_no_vateable=0;

    $deliver_by='';
    $sql=sprintf("select supplier_id  from shipping where  order_id=%d",$order_id);
    $res2 = $db->query($sql);  
    if ($row2=$res2->fetchRow()){
      if($row2['supplier_id']>0){
	// get the anme
	$s_id=$row2['supplier_id'];

	$deliver_by.='<a href="supplier.php?id='.$s_id.'">'.get_supplier_code($s_id).'</a>, ';
      }
    }    
    $deliver_by=preg_replace('/\,\s$/','',$deliver_by);
    if($deliver_by=='')
      $deliver_by=_('Unknown');



    $picked_by='';
    $sql=sprintf("select picker_id  from pick  where  order_id=%d",$order_id);
    $res2 = $db->query($sql);  
    if ($row2=$res2->fetchRow()){
      if($row2['picker_id']>0){
	// get the anme
	$s_id=$row2['picker_id'];

	$picked_by.='<a href="staff.php?id='.$s_id.'">'.get_staff_alias($s_id).'</a>, ';
      }
    }    
    $picked_by=preg_replace('/\,\s$/','',$picked_by);
    if($picked_by=='')
      $picked_by=_('Unknown');

  $packed_by='';
    $sql=sprintf("select packer_id  from pack  where  order_id=%d",$order_id);
    $res2 = $db->query($sql);  
    if ($row2=$res2->fetchRow()){
      if($row2['packer_id']>0){
	// get the anme
	$s_id=$row2['packer_id'];

	$packed_by.='<a href="staff.php?id='.$s_id.'">'.get_staff_alias($s_id).'</a>, ';
      }
    }    
    $packed_by=preg_replace('/\,\s$/','',$packed_by);
    if($packed_by=='')
      $packed_by=_('Unknown');





    $sql=sprintf("select sum(value) as value from shipping where tax_code='S' and  order_id=%d",$order_id);

    $res2 = $db->query($sql);  
    if ($row2=$res2->fetchRow())
      $shipping_vateable=$row2['value'];
    $res2 = $db->query($sql);  
    $sql=sprintf("select sum(value) as value from shipping where tax_code='' and order_id=%d",$order_id);
    $res2 = $db->query($sql);  
    if ($row2=$res2->fetchRow())
      $shipping_no_vateable=$row2['value'];




    $sql=sprintf("select sum(value) as value from charge where tax_code='S' and  order_id=%d",$order_id);

    $res2 = $db->query($sql);  
    if ($row2=$res2->fetchRow())
      $charges_vateable=$row2['value'];
    $res2 = $db->query($sql);  
    $sql=sprintf("select sum(value) as value from charge where tax_code='' and order_id=%d",$order_id);
    $res2 = $db->query($sql);  
    if ($row2=$res2->fetchRow())
      $charges_no_vateable=$row2['value'];



    $sql=sprintf("select sum(charge) as value from transaction where tax_code='S' and  order_id=%d",$order_id);
    
    $res2 = $db->query($sql);  
    if ($row2=$res2->fetchRow())
      $items_vateable=$row2['value'];
    $res2 = $db->query($sql);  
    $sql=sprintf("select sum(charge) as value from transaction where tax_code='' and order_id=%d",$order_id);
    $res2 = $db->query($sql);  
    if ($row2=$res2->fetchRow())
      $items_no_vateable=$row2['value'];
     
    $sql=sprintf("select sum(price*(1-discount)*(ordered-reorder)) as value from todo_transaction where tax_code='S' and  order_id=%d",$order_id);

    $res2 = $db->query($sql);  
    if ($row2=$res2->fetchRow())
      $items_vateable=$items_vateable+$row2['value'];
    $res2 = $db->query($sql);  
    $sql=sprintf("select sum(price*(1-discount)*(ordered-reorder))  as value from todo_transaction where tax_code='' and order_id=%d",$order_id);
    $res2 = $db->query($sql);  
    if ($row2=$res2->fetchRow())
      $items_no_vateable=$items_no_vateable+$row2['value'];
     

    $sql=sprintf("select sum(value_net) as value from debit where tax_code='S' and  order_affected_id=%d",$order_id);
 
    $res2 = $db->query($sql);  
    if ($row2=$res2->fetchRow())
      $credits_vateable=$row2['value'];
    $res2 = $db->query($sql);  
    $sql=sprintf("select sum(value_net) as value from debit where tax_code='' and order_affected_id=%d",$order_id);
    $res2 = $db->query($sql);  
    if ($row2=$res2->fetchRow())
      $credits_no_vateable=$row2['value'];

    // number of items out of stock
    $items_out_of_stock=0;
    $sql=sprintf("select count(*) as num from outofstock where order_id=%d",$order_id);
    $res2 = $db->query($sql);  
    if ($row2=$res2->fetchRow())
      $items_out_of_stock=$row2['num'];
  

    
    if(!$order_data['taken_by']=get_staff_alias($order_data['taken_by']))
      $order_data['taken_by']=_('Unknown');

  
  $order_data['items_out_of_stock']=$items_out_of_stock;
  $order_data['deliver_by']=$deliver_by;
  $order_data['picked_by']=$picked_by;
      $order_data['packed_by']=$packed_by;

  $order_data['credits_vateable']=$credits_vateable;
  $order_data['credits_no_vateable']=$credits_no_vateable;
  $order_data['shipping_vateable']=$shipping_vateable;
  $order_data['shipping_no_vateable']=$shipping_no_vateable;
  $order_data['charges_vateable']=$charges_vateable;
  $order_data['charges_no_vateable']=$charges_no_vateable;
  $order_data['items_vateable']=$items_vateable;
  $order_data['items_no_vateable']=$items_no_vateable;
  return $order_data;
}else
   return false;


}

function get_parent_order_public_id($order_id){
  $db =& MDB2::singleton();
  $sql=sprintf("select parent_id from orden where id=%d",$order_id);
  $res = $db->query($sql);  
  if ($row=$res->fetchRow()){
    if(is_numeric($row['parent_id'])){
      $sql=sprintf("select public_id from orden where id=%d",$row['parent_id']);
      $res2 = $db->query($sql);  
      if ($row2=$res2->fetchRow()){
	return $row2['public_id'];
      }
    }
  }

  
  return false;


}







?>