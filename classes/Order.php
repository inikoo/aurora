<?
include_once('common/string.php');
include_once('classes/Staff.php');
include_once('classes/Supplier.php');

class Order{
  var $db;
  var $data=array();
  var $items=array();
  var $status_names=array();
  var $id;
  var $tipo;


  function __construct($tipo='order',$id=false) {
     $this->db =MDB2::singleton();
     $this->status_names=array(0=>'new');
     
     if(preg_match('/^order/',$tipo))
       $this->tipo='order';
     else
       $this->tipo=$tipo;

     if(is_numeric($id)){//load from id
       $this->id=$id;
       if(!$this->get_data($tipo))
	 return false;
     }else if(is_array($id)){// Create a new order
       $this->create_order($id);
     }

     return true;

  }

  function create_order($data){
    switch($this->tipo){
    case('po'):
      $sql=sprintf("insert into porden (date_creation,date_index,supplier_id) values (NOW(),NOW(),%d)",$data['supplier_id']);
      mysql_query($sql);
      $this->id=mysql_insert_id();
      $this->get_data();
      break;
    }

  }


  function get_data($tipo){
    
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
      $sql=sprintf("select porden.id,ifnull(received_by,-1) as received_by,ifnull(checked_by,-1) as checked_by,public_id,supplier_id,UNIX_TIMESTAMP(date_expected) as date_expected,UNIX_TIMESTAMP(date_submited) as date_submited,UNIX_TIMESTAMP(date_creation) as date_creation,UNIX_TIMESTAMP(date_invoice) as date_invoice,UNIX_TIMESTAMP(date_received) as date_received,tipo,goods,shipping,vat,total,charges,diff,(select count(*) from porden_item where  porden_id=porden.id )as items  from porden where id=%d ",$this->id);

      $result =& $this->db->query($sql);
      if($porder=$result->fetchRow()){
	$this->data['id']=$porder['id'];
	$this->data['received_by']=$porder['received_by'];
	$this->data['checked_by']=$porder['checked_by'];
	$this->data['public_id']=$porder['public_id'];
	$this->data['supplier_id']=$porder['supplier_id'];
	$this->data['items']=(!is_numeric($porder['items'])?0:number($porder['items']));
	$this->data['status_id']=$porder['tipo'];

	$this->data['status']=$this->status_names[$this->data['status_id']];
	
	$this->dates=array(
			   'ts_created'=>$porder['date_creation'],
			   'ts_submited'=>$porder['date_submited'],
			   'ts_expected'=>$porder['date_expected'],
			   'ts_invoice'=>$porder['date_invoice'],
			   'ts_received'=>$porder['date_received'],
			   'created'=>strftime("%e %B %Y %H:%M", $porder['date_creation'])
			   );
	
	$this->money=array(
			   'total'=>$porder['total'],
			   'vat'=>$porder['vat'],
			   'goods'=>$porder['goods'],
			   'shipping'=>$porder['shipping'],
			   'charges'=>$porder['charges'],
			   'diff'=>$porder['diff']
			   );


      }else
	$this->msg=_('Purchese Order do not exist');


    }

    // Make dates
    
	$this->dates=array(
			   'created'=>strftime("%e %B %Y %H:%M", $this->dates['ts_created'])
			   );

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
      $this->supplier=new supplier($this->supplier_id);
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
	  $expected_goods+=($row['expected_price']*$row['expected_qty']);
	  $goods+=($row['price']*$row['qty']);
	}
	$this->data['items']=$items;
	$this->money['goods_expected']=$expected_goods;
	$this->money['goods']=$expected;
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

  

  
  function add_item($data){
    $tipo=$data['tipo'];
    switch($tipo){
    case('po_new'):
      
      
      $sql=sprintf("insert into porder_item (porden_id,p2s_id,expected_qty,expected_price) values (%d,%d,%.3f,%.3f)",$this->id,$data['p2s_id'],$data['qty'],$data['price']);
      mysql_query($sql);
      $this->load('items');
      return array($this->data,$this->money);
    }
    

  }

}

?>