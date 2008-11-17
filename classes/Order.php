<?

include_once('classes/Staff.php');
include_once('classes/Supplier.php');

class Order{
  var $db;
  var $data=array();
  var $items=array();
  var $status_names=array();
  var $id=false;
  var $tipo;
  var $staus='new';

  function __construct($tipo='order',$id=false) {
     $this->db =MDB2::singleton();
     $this->status_names=array(0=>'new');
     
     if(is_numeric($tipo) and !$id){
       $id=$tipo;
       $tipo='order';
     }
     

     if(preg_match('/^order$/',$tipo))
       $this->tipo='order';
     else if(preg_match('/^(po|purchase order)$/',$tipo))
       $this->tipo='po';
     else
       return;

     
     if(is_numeric($id)){//load from id
       $this->id=$id;
       if(!$this->get_data($tipo))
	 return false;
     }else if(is_array($id)){// Create a new order
       $this->create_order($id);
     }



  }

  function create_order($data){
    switch($this->tipo){
    case('po'):
      $sql=sprintf("insert into porden (date_creation,date_index,supplier_id) values (NOW(),NOW(),%d)",$data['supplier_id']);
      mysql_query($sql);
      $this->id=mysql_insert_id();
      $this->get_data();
      $this->status='new';
      break;
    }

  }


  function get_data(){
    global $_order_status;
    switch($this->tipo){
    case('order'):
      
      if($tipo=='order_public_id')
	$sql=sprintf("select * from orden where public_id=%s",prepare_mysql($this->id));
      else
	$sql=sprintf("select * from orden where id=%d",$this->id);
      $result =& $this->db->query($sql);
      if(!$this->data=$result->fetchRow()){	     
        return false;
      }
      $this->id=$this->data['id'];


      if($this->data['weight']==''){
	$this->data['weight']=$this->get('estimated_weight');
	$this->data['weight_estimated']=1;
      }
       if($this->data['pick_factor']==''){
	 $this->data['pick_factor']=(int) $this->get('pick_factor');
      }
       if($this->data['pack_factor']==''){
	 $this->data['pack_factor']=(int) $this->get('pack_factor');
      }
      

    $shipping_vateable=0;
    $shipping_no_vateable=0;
    $charges_vateable=0;
    $charges_no_vateable=0;
    $items_vateable=0;
    $items_no_vateable=0;
    $credits_vateable=0;
    $credits_no_vateable=0;
      
  $deliver_by='';
    $sql=sprintf("select supplier_id  from shipping where  order_id=%d",$this->id);
    $res2 = $this->db->query($sql);  
    if ($row2=$res2->fetchRow()){
      if($row2['supplier_id']>0){
	// get the anme
	$s_id=$row2['supplier_id'];
	$supplier=new Supplier($s_id);
	$deliver_by.='<a href="supplier.php?id='.$s_id.'">'.$supplier->data['name'].'</a>, ';
      }
    }    
    $deliver_by=preg_replace('/\,\s$/','',$deliver_by);
    if($deliver_by=='')
      $deliver_by=_('Unknown');



    $picked_by='';
    $sql=sprintf("select picker_id  from pick  where  order_id=%d",$this->id);
    $res2 = $this->db->query($sql);  
    if ($row2=$res2->fetchRow()){
      if($row2['picker_id']>0){
	// get the anme
	$s_id=$row2['picker_id'];
	
	if($staff=new Staff('id',$s_id))
	  $picked_by.='<a href="staff.php?id='.$s_id.'">'.$staff->data['alias'].'</a>, ';
      }
    }    
    $picked_by=preg_replace('/\,\s$/','',$picked_by);
    if($picked_by=='')
      $picked_by=_('Unknown');

  $packed_by='';
    $sql=sprintf("select packer_id  from pack  where  order_id=%d",$this->id);
    $res2 = $this->db->query($sql);  
    if ($row2=$res2->fetchRow()){
      if($row2['packer_id']>0){
	// get the anme
	$s_id=$row2['packer_id'];
	if($staff=new Staff('id',$s_id))
	  $packed_by.='<a href="staff.php?id='.$s_id.'">'.$staff->data['alias'].'</a>, ';
      }
    }    
    $packed_by=preg_replace('/\,\s$/','',$packed_by);
    if($packed_by=='')
      $packed_by=_('Unknown');





    $sql=sprintf("select sum(value) as value from shipping where tax_code='S' and  order_id=%d",$this->id);

    $res2 = $this->db->query($sql);  
    if ($row2=$res2->fetchRow())
      $shipping_vateable=$row2['value'];
    $res2 = $this->db->query($sql);  
    $sql=sprintf("select sum(value) as value from shipping where tax_code='' and order_id=%d",$this->id);
    $res2 = $this->db->query($sql);  
    if ($row2=$res2->fetchRow())
      $shipping_no_vateable=$row2['value'];




    $sql=sprintf("select sum(value) as value from charge where tax_code='S' and  order_id=%d",$this->id);

    $res2 = $this->db->query($sql);  
    if ($row2=$res2->fetchRow())
      $charges_vateable=$row2['value'];
    $res2 = $this->db->query($sql);  
    $sql=sprintf("select sum(value) as value from charge where tax_code='' and order_id=%d",$this->id);
    $res2 = $this->db->query($sql);  
    if ($row2=$res2->fetchRow())
      $charges_no_vateable=$row2['value'];



    $sql=sprintf("select sum(charge) as value from transaction where tax_code='S' and  order_id=%d",$this->id);
    
    $res2 = $this->db->query($sql);  
    if ($row2=$res2->fetchRow())
      $items_vateable=$row2['value'];
    $res2 = $this->db->query($sql);  
    $sql=sprintf("select sum(charge) as value from transaction where tax_code='' and order_id=%d",$this->id);
    $res2 = $this->db->query($sql);  
    if ($row2=$res2->fetchRow())
      $items_no_vateable=$row2['value'];
     
    $sql=sprintf("select sum(price*(1-discount)*(ordered-reorder)) as value from todo_transaction where tax_code='S' and  order_id=%d",$this->id);

    $res2 = $this->db->query($sql);  
    if ($row2=$res2->fetchRow())
      $items_vateable=$items_vateable+$row2['value'];
    $res2 = $this->db->query($sql);  
    $sql=sprintf("select sum(price*(1-discount)*(ordered-reorder))  as value from todo_transaction where tax_code='' and order_id=%d",$this->id);
    $res2 = $this->db->query($sql);  
    if ($row2=$res2->fetchRow())
      $items_no_vateable=$items_no_vateable+$row2['value'];
     

    $sql=sprintf("select sum(value_net) as value from debit where tax_code='S' and  order_affected_id=%d",$this->id);
 
    $res2 = $this->db->query($sql);  
    if ($row2=$res2->fetchRow())
      $credits_vateable=$row2['value'];
    $res2 = $this->db->query($sql);  
    $sql=sprintf("select sum(value_net) as value from debit where tax_code='' and order_affected_id=%d",$this->id);
    $res2 = $this->db->query($sql);  
    if ($row2=$res2->fetchRow())
      $credits_no_vateable=$row2['value'];

    // number of items out of stock
    $items_out_of_stock=0;
    $sql=sprintf("select count(*) as num from outofstock where order_id=%d",$this->id);
    $res2 = $this->db->query($sql);  
    if ($row2=$res2->fetchRow())
      $items_out_of_stock=$row2['num'];
  
    if($staff=new Staff($this->data['taken_by'])){
      $this->data['taken_by']=$staff->data['alias'];
    }else
       $this->data['taken_by']=_('Unknown');
    
  
    $this->data['items_out_of_stock']=$items_out_of_stock;
    $this->data['deliver_by']=$deliver_by;
    $this->data['picked_by']=$picked_by;
    $this->data['packed_by']=$packed_by;
    
    $this->data['credits_vateable']=$credits_vateable;
    $this->data['credits_no_vateable']=$credits_no_vateable;
    $this->data['shipping_vateable']=$shipping_vateable;
    $this->data['shipping_no_vateable']=$shipping_no_vateable;
    $this->data['charges_vateable']=$charges_vateable;
    $this->data['charges_no_vateable']=$charges_no_vateable;
    $this->data['items_vateable']=$items_vateable;
   $this->data['items_no_vateable']=$items_no_vateable;
  


      return true;
    case('po'):
      $sql=sprintf("select status_id,porden.id,ifnull(received_by,-1) as received_by,ifnull(checked_by,-1) as checked_by,public_id,supplier_id,UNIX_TIMESTAMP(date_expected) as date_expected,UNIX_TIMESTAMP(date_submited) as date_submited,UNIX_TIMESTAMP(date_creation) as date_creation,UNIX_TIMESTAMP(date_invoice) as date_invoice,UNIX_TIMESTAMP(date_received) as date_received,UNIX_TIMESTAMP(date_checked) as date_checked,UNIX_TIMESTAMP(date_consolidated) as date_consolidated,tipo,goods,shipping,vat,total,charges,diff,(select count(*) from porden_item where  porden_id=porden.id )as items  from porden where id=%d ",$this->id);

      $result =& $this->db->query($sql);
      if($porder=$result->fetchRow()){
	
	$tipo=$porder['tipo'];
	if($tipo==0)
	  $this->status='new';
	elseif($tipo==1)
	  $this->status='submited';
	elseif($tipo==2)
	  $this->status='received';
	elseif($tipo==3)
	  $this->status='cancelled';
	$this->data['tipo']=$porder['tipo'];
	$this->data['id']=$porder['id'];
	$this->data['received_by']=$porder['received_by'];
	$this->data['checked_by']=$porder['checked_by'];
	$this->data['public_id']=$porder['public_id'];
	$this->data['supplier_id']=$porder['supplier_id'];
	$this->data['items']=(!is_numeric($porder['items'])?0:number($porder['items']));
	$this->data['status_id']=$porder['tipo'];
	$this->data['total']=$porder['total'];
	$this->data['vat']=$porder['vat'];
	$this->data['goods']=$porder['goods'];
	$this->data['shipping']=$porder['shipping'];
	$this->data['charges']=$porder['charges'];
	$this->data['diff']=$porder['diff'];
	$this->data['date_creation']=$porder['date_creation'];
	$this->data['date_submited']=$porder['date_submited'];
	$this->data['date_expected']=$porder['date_expected'];
	$this->data['date_received']=$porder['date_received'];
	$this->data['date_checked']=$porder['date_checked'];
	$this->data['date_consolidated']=$porder['date_consolidated'];
	$this->data['status_id']=$porder['status_id'];
	$this->data['status']=$_order_status[$porder['status_id']];
	
	$this->data['dates']=array(
			   'created'=>strftime("%e %B %Y %H:%M", $porder['date_creation']),
			   'submited'=>strftime("%e %B %Y %H:%M", $porder['date_submited']),
			   'expected'=>strftime("%e %B %Y %H:%M", $porder['date_expected']),
			   'invoice'=>strftime("%e %B %Y %H:%M", $porder['date_invoice']),
			   'received'=>strftime("%e %B %Y %H:%M", $porder['date_received']),
			   'created'=>strftime("%e %B %Y %H:%M", $porder['date_creation']),
			   'checked'=>strftime("%e %B %Y %H:%M", $porder['date_checked']),
			   'consolidates'=>strftime("%e %B %Y %H:%M", $porder['date_consolidated'])
			   );
	
	$this->data['money']=array(
				   'total'=>money($porder['total']),
				   'vat'=>money($porder['vat']),
				   'goods'=>money($porder['goods']),
				   'shipping'=>money($porder['shipping']),
				   'charges'=>money($porder['charges']),
				   'diff'=>money($porder['diff'])
			   );


      }else
	$this->msg=_('Purchese Order do not exist');


    }



  }


  function get($key=''){
     switch($key){
     case('estimated_weight'):
       if($this->tipo=='order'){
	 $w=0;
	 $sql=sprintf("select sum(dispached*units*weight)as w from transaction left join product on (product.id=product_id) where order_id=%d ",$this->id);
	 $result =& $this->db->query($sql);
	 if($row=$result->fetchRow()){
	   $w=$row['w'];
	 }
	 return $w;
	  
       }
       
       break;
     case('pick_factor'):
         if($this->tipo=='order'){
	 $factor=10;
	 $sql=sprintf("select count(distinct group_id) as families,count(distinct product_id) as products from transaction left join product on (product.id=product_id) where order_id=%d ",$this->id);
	 $result =& $this->db->query($sql);
	 if($row=$result->fetchRow()){
	   $factor=10*$row['families']+2*($row['products']-$row['families']);
	 }
	 return $this->get('estimated_weight')/2+$factor;
	  
       }
       
       break;
        case('pack_factor'):
         if($this->tipo=='order'){
	 $factor=10;
	 $sql=sprintf("select sum(dispached) as dispached ,count(distinct product_id) as products from transaction left join product on (product.id=product_id) where order_id=%d ",$this->id);
	 $result =& $this->db->query($sql);
	 if($row=$result->fetchRow()){
	   if($row['products']==0)
	     $factor=0;
	   else
	     $factor=5*$row['products']+($row['dispached']/$row['products']);
	 }
	 return $this->get('estimated_weight')/2+$factor;
	  
       }
       
       break; 

       
     }


  }


  function load($key=''){
    switch($key){
    

    case('supplier'):
      $this->supplier=new supplier($this->data['supplier_id']);
      break;
    case('items'):
      switch($this->tipo){
      case('po'):
	$sql=sprintf("select * from porden_item where porden_id=%d",$this->id);
	$result =& $this->db->query($sql);
	$items=0;
	$expected_goods=0;
	$goods=0;
	while($row=$result->fetchRow()){
	  $items++;
	  $expected_goods+=($row['expected_price']);
	  $goods+=($row['price']*$row['qty']);
	}
	$this->data['items']=$items;
	$this->data['goods']=$expected_goods;
	$this->data['total']=$this->data['goods']+$this->data['vat']+$this->data['shipping']+$this->data['charges']+$this->data['diff'];
	$this->data['money']['goods']=money($this->data['goods']);
	$this->data['money']['total']=money($this->data['total']);
	$sql=sprintf("update porden set items=%d,total=%.2f,goods=%.2f",$items,$this->data['total'],$this->data['goods']);
	mysql_query($sql);

	

      }
      break;
    }
      

  }
  
  function get_date($key='',$tipo='dt'){
    if(isset($this->dates['ts_'.$key]) and is_numeric($this->dates['ts_'.$key]) ){

      switch($tipo){
      case('dt'):
      default:
	return strftime("%e %B %Y %H:%M", $porder['date_expected']);
      }
    }else
      return false;
  }

  
  function submit($data){
    if($this->data['status']<10){
      $this->data['tipo']=1;
      $this->data['status_id']=10;
      $datetime=prepare_mysql_datetime($data['sdate'].' '.$data['stime']);
      if(!$datetime[1]){

	$this->get_data();
	//	return array('ok'=>false,'msg'=>$this->save_history('submit',array('date'=>'NOW','user_id'=>$data['user_id'])));
	$this->save_history('submit',array('date'=>'NOW','user_id'=>$data['user_id']));

	return array('ok'=>true);
      }else
	return array('ok'=>false,'msg'=>_('wrong date').' '.$data['sdate'].' '.$data['stime']);
    }else{
      return array('ok'=>false,'msg'=>_('Order is already submited'));

    }

  }
  function add_item($data){

    switch($this->tipo){
    case('po'):
      
      //check if the product is related to the supplier
      $sql=sprintf("select supplier_id,product2supplier.price,units from product2supplier  left join product on (product_id=product.id) where product2supplier.id=%d",$data['product_id']);
      $res = $this->db->query($sql);  
      if ($row=$res->fetchRow()){
	if($row['supplier_id']!=$this->data['supplier_id'])
	  return array('ok'=>false,'msg'=>_('Product no related to this supplier'));
	$price=$row['price'];
	$units=$row['units'];
      }else
	return array('ok'=>false,'msg'=>_('Product not exist'));
      
      //check if already exist 
      $item_data=array('outers'=>'','est_price'=>'','id'=>$data['product_id'] );

      $sql=sprintf("select porden_item.id from porden_item  where porden_id=%d and p2s_id=%d",$this->id,$data['product_id']);

      $res = $this->db->query($sql);  
      if ($row=$res->fetchRow()){
	if($data['qty']!=0){
	  $sql=sprintf("update porden_item set expected_qty=%.3f,expected_price=%.3f where id=%d    ",$data['qty'],$price,$row['id']);
	  mysql_query($sql);
	  $item_data=array('outers'=>number($data['qty']/$units),'est_price'=>money($data['qty']*$price),'id'=>$data['product_id']  );
	}else{
	  $sql=sprintf("delete from porden_item  where id=%d    ",$row['id']);
	  //	  	return array('ok'=>false,'msg'=>$sql);
	  mysql_query($sql);
	}
      }else{
	if($data['qty']!=0){
	  $sql=sprintf("insert into porden_item (porden_id,p2s_id,expected_qty,expected_price) values (%d,%d,%.3f,%.3f)",$this->id,$data['product_id'],$data['qty'],$price*$data['qty']);
	  mysql_query($sql);
	  $item_data=array('outers'=>number($data['qty']/$units),'est_price'=>money($data['qty']*$price) ,'id'=>$data['product_id'] );
	}
	  
      }
      $this->load('items');
      return array('ok'=>true,'item_data'=>$item_data);
    }
    

  }

function set($tipo,$data){
    switch($tipo){
    case('date_expected'):
      $datetime=prepare_mysql_datetime($data['rdate'].' 12:00:00','datetime');
      if($datetime['ok']){
	if($this->data['status']>=10 and  $this->data['status']<80 ){
	$this->save('date_expected');
	$this->data['date_expected']=$datetime['ts'];
	$this->data['dates']['expected']=strftime("%e %B %Y %H:%M",$datetime['ts']);


	if(!isset($data['history']) or $data['history'])
	  $this->save_history('date_expected',array('date'=>'NOW()','user_id'=>$data['user_id']));
	return array('ok'=>true);
	}else
	  return array('ok'=>false,'msg'=>_('Order not submited or already received'));
      }else
	return array('ok'=>false,'msg'=>_('Wrong date'));
      
      break;
    case('date_received'):
      $datetime=prepare_mysql_datetime($data['date'],'datetime');
      if($datetime['ok']){
	if($this->data['status']<20){
	  $this->data['date_received']=$datetime['ts'];
	  $this->data['dates']['received']=strftime("%e %B %Y %H:%M",$datetime['ts']);
	  $this->data['status_id']=80;
	  $this->data['received_by']=$data['done_by'];
	  $this->save($tipo);
	  if(!isset($data['history']) or $data['history'])
	    $this->save_history($tipo,array('date'=>'NOW()','user_id'=>$data['user_id']);
	  return array('ok'=>true);
	}else
	    return array('ok'=>false,'msg'=>_('Already received'));
      }else
	return array('ok'=>false,'msg'=>_('Wrong date'));
    case('date_checked'):
      $datetime=prepare_mysql_datetime($data['date'],'datetime');
      if($datetime['ok']){
	if($this->data['status']<80 or $this->data['status']>=90 ){
	  $this->data['date_checked']=$datetime['ts'];
	  $this->data['dates']['checked']=strftime("%e %B %Y %H:%M",$datetime['ts']);
	  $this->data['status_id']=90;
	  $this->data['checked_by']=$data['done_by'];
	  $this->save($tipo);
	  if(!isset($data['history']) or $data['history'])
	    $this->save_history($tipo,array('date'=>'NOW()','user_id'=>$data['user_id']));
	  return array('ok'=>true);
	}else
	  return array('ok'=>false,'msg'=>_('Already checked or not received yet'));
      }else
	return array('ok'=>false,'msg'=>_('Wrong date'));
      break;
    case('date_consolidated'):
      $datetime=prepare_mysql_datetime($data['date'],'datetime');
      if($this->data['status']<=90 and $this->data['status']<100 ){
	$this->save($tipo,$datetime);
	$this->get_data();
	if(!isset($data['history']) or $data['history'])
	  $this->save_history($tipo,array('date'=>'NOW()','user_id'=>$data['user_id'],'done_by'=>$data['done_by']));
	return array('ok'=>true);
      }else
	return array('ok'=>false,'msg'=>_('Error can not be consolidated'));
      break;
    case('date_cancelled'):
      $datetime=prepare_mysql_datetime($data['rdate'],'date');
      if($this->data['status']<80 ){
	$this->save($tipo,$datetime);
	$this->get_data();
	if(!isset($data['history']) or $data['history'])
	  $this->save_history($tipo,array('date'=>'NOW()','user_id'=>$data['user_id']));
	return array('ok'=>true);
      }else
	return array('ok'=>false,'msg'=>_('Error, order already received'));
      break;


    }
    return array('ok'=>false,'msg'=>_('Operation not found')." $tipo");
}
  function save($tipo){
    switch($tipo){
    case('submit'):
      if($this->tipo='po'){
	$sql=sprintf("update porden set date_submited='%s' , tipo=%d, status_id=%d where id=%d",date("Y-m-d H:i:s",strtotime("@".$this->data['submited'])),$this->data['tipo'],$this->data['status_id'],$this->id);
      }
      mysql_query($sql);
      break;
     case('date_expected'):
      if($this->tipo='po'){
	$sql=sprintf("update porden set date_expected=%s where id=%d",date("Y-m-d H:i:s",strtotime("@".$this->data['expected'])),$this->id);
      }
      mysql_query($sql);
      break;  
    case('date_received'):
      if($this->tipo='po'){
	$sql=sprintf("update porden set date_received='%s' ,received_by=%d,status_id=%d   where id=%d",date("Y-m-d H:i:s",strtotime("@".$this->data['received'])),$this->data['consolidated_by'],$this->data['status_id'],$this->id);
      }
      mysql_query($sql);
      break;  
    case('date_checked'):
      if($this->tipo='po'){
	$sql=sprintf("update porden set date_checked='%s' ,checked_by=%d,status_id=%d   where id=%d",date("Y-m-d H:i:s",strtotime("@".$this->data['checked'])),$this->data['checked_by'],$this->data['status_id'],$this->id);
      }
      mysql_query($sql);
      break;  
    case('date_consolidated'):
      if($this->tipo='po'){
	$sql=sprintf("update porden set date_consolidated=%s , consolidated_by=%d ,status_id=%d   where id=%d",date("Y-m-d H:i:s",strtotime("@".$this->data['consolidated'])),$this->data['consolidated_by'],$this->data['status_id'],$this->id);
      }
      mysql_query($sql);
      break;  
       

    }
  }

  
  function save_history($tipo,$data){
    switch($tipo){
    case('submit'):
      if($this->tipo='po'){
	$note=_('submited')." ".$this->data['dates']['submited'];
	$sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,'PO',%d,'SDATE',NULL,'NEW',%d,NULL,'%d',%s)"
		   ,$data['date'],$this->id,$data['user_id'],$this->data['date_submited'],prepare_mysql($note)); 
      }
      mysql_query($sql);
      break;
    case('date_expected'):
      if($this->tipo='po'){
	$note=_('expected')." ".$this->data['dates']['expected'];
	$sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,'PO',%d,'EDATE',NULL,'CHG',%d,'%d','%d',%s)"
		     ,$data['date'],$this->id,$data['user_id'],$data['old_value'],$this->data['date_expected'],prepare_mysql($note)); 
      }
      mysql_query($sql);
      break;
      

    }
  }


}

?>