<?
include_once('Contact.php');
class Customer{
  var $db; 
  var $id=false;


  function __construct($arg1=false,$arg2=false) {
    $this->db =MDB2::singleton();
    $this->status_names=array(0=>'new');
    
    if(is_numeric($arg1) and !$arg2){
       $this->get_data($arg1);
       return;
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
	$this->data['main']['email']=$o_main_email->data['email'];
	$this->data['main']['formated_email']=$o_main_email->display('link');
      }else{
	//try to auto fix it
	$this->data['main_email_id']=false;
	$this->data['main']['email']='';
	$this->data['main']['formated_email']='';
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


 function update($values,$args=''){
    $res=array();
    foreach($values as $data){
      
      $key=$data['key'];
      $value=$data['value'];
      $res[$key]=array('ok'=>false,'msg'=>'');
      
      switch($key){
      case('main_email'):
	$main_email=new email($value);
	if(!$main_email->id){
	  $res[$key]['msg']=_('Email not found');
	  $res[$key]['ok']=false;
	  continue;
	}
	$this->old['main_email']=$this->data['main']['email'];
	$this->data['main_email']=$value;
	$this->data['main']['email']=$main_email->data['email'];
	$res[$key]['ok']=true;


      }

    }
    return $res;
 }


 function save($key,$history_data=false){
    switch($key){
      case('main_email'):
	$sql=sprintf('update customer set %s=%s where id=%d',$key,prepare_mysql($this->data[$key]),$this->id);
	//	print "$sql\n";
	$this->db->exec($sql);

	if(is_array($history_data)){
	  $this->save_history($key,$this->old[$key],$this->data['main']['email'],$history_data);
	}


	break;
    }

 }

 function save_history($key,$old,$new,$data){
   
 }




}
?>