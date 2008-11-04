<?
include_once('common/string.php');
class order{
  var $db;
  var $data=array();
  var $items=array();
  var $status_names=array();
  var $id;
  var $tipo;



  function __construct($tipo='order',$id=false) {
     $this->db =MDB2::singleton();
     $this->status_names=array(0=>'new');
     
     $this->tipo=$tipo;

     if(is_numeric($id)){//load from id
       $this->id=$id;
       $this->get_data();
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
      break;
    }

  }


  function get_data(){
    
    switch($this->tipo){
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