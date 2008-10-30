<?
include_once('common/string.php');
class order{
  var $db;
  var $data=array();
  var $items=array();

  var $id;
  var $tipo;



  function __construct($tipo='order',$id=false) {
     $this->db =MDB2::singleton();
     
     $this->tipo=$tipo;
     if(is_numeric($id)){
       $this->id=$id;
       $this->get_data();
     }



  }


  function get_data(){
    
    switch($this->tipo){
    case('po'):
      $sql=sprintf("select ifnull(received_by,-1) as received_by,ifnull(checked_by,-1) as checked_by,public_id,supplier_id,UNIX_TIMESTAMP(date_expected) as date_expected,UNIX_TIMESTAMP(date_submited) as date_submited,UNIX_TIMESTAMP(date_creation) as date_creation,UNIX_TIMESTAMP(date_invoice) as date_invoice,UNIX_TIMESTAMP(date_received) as date_received,tipo,goods,shipping,vat,total,charges,diff,(select count(*) from porden_item where  porden_id=porden.id )as items  from porden where id=%d ",$this->id);
      $result =& $this->db->query($sql);
      if($porder=$result->fetchRow()){
	$this->data['received_by']=$porder['received_by'];
	$this->data['checked_by']=$porder['checked_by'];
	$this->data['public_id']=$porder['public_id'];
	$this->data['supplier_id']=$porder['supplier_id'];
	$this->data['items']=$porder['items'];
	$this->data['status']=$porder['tipo'];


	$this->dates=array(
			   'ts_created'=>$porder['date_creation'],
			   'ts_submited'=>$porder['date_submited'],
			   'ts_expected'=>$porder['date_expected'],
			   'ts_invoice'=>$porder['date_invoice'],
			   'ts_received'=>$porder['date_received']
			   );
	
	$this->monet=array(
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
  }


  function load($key=''){
    switch($key){
    case('supplier'):
      $this->supplier=new supplier($this->supplier_id);

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
  
  

}

?>