<?
include_once('classes/Contact.php');
class Customer{
  var $db; 
  var $id=false;


  function __construct($tipo_id='id',$id=false) {
    $this->db =MDB2::singleton();
    $this->status_names=array(0=>'new');
    
    if(is_numeric($tipo_id) and !$id){
      $id= $tipo_id;
       $tipo_id='id';
    }
    
    
    if($tipo_id=='id'){//load from id
      $this->get_data($id);
    }
    
    
    
  }




 function get_data($id){
   $sql=sprintf("select * from customer where id=%s",prepare_mysql($id));
   $result =& $this->db->query($sql);
   if($this->data=$result->fetchRow()){	     
      $this->id=$this->data['id'];
      
      $o_main_bill_address=new address($this->data['main_bill_address']);
      if($o_main_bill_address->id){
	$this->data['main_bill_address_id']=$o_main_bill_address->id;
	$this->data['main_bill_address']=$o_main_bill_address->display('html');
	
      }else{
	  $this->data['main_bill_address_id']=false;
	  $this->data['main_bill_address']='';
      }
      unset($o_main_bill_address);

      $o_main_contact_name=new name($this->data['main_contact_name']);
      if($o_main_contact_name->id){
	$this->data['main_contact_name_id']=$o_main_contact_name->id;
	$this->data['main_contact_name']=$o_main_contact_name->display('html');
      }else{
	//try to auto fix it
	$this->data['main_contact_name_id']=false;
	$this->data['main_contact_name']='';
      }
      unset($o_main_contact_name);

      $o_main_email=new email($this->data['main_email']);
      if($o_main_email->id){
	$this->data['main_email_id']=$o_main_email->id;
	$this->data['main_email']=$o_main_email->display('link');
      }else{
	//try to auto fix it
	$this->data['main_email_id']=false;
	$this->data['main_email']='';
      }
      unset($o_main_email);

      $o_main_tel=new telecom($this->data['main_tel']);
      if($o_main_tel->id){
	$this->data['main_tel_id']=$o_main_tel->id;
	$this->data['main_tel']=$o_main_tel->display('link');
      }else{
	//try to auto fix it
	$this->data['main_tel_id']=false;
	$this->data['main_tel']='';
      }
      unset($o_main_tel);

    }


  }
 function load($key=''){
    switch($key){
   
    case('contacts'):
    case('contact'):
      if($this->contact=new contact($this->data['contact_id'])){
	
	$this->contact->load('telecoms');
	$this->contact->load('contacts');
      }
    }
    
  }

 function create_new(){

 }


}
?>