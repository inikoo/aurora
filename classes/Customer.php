<?
include_once('classes/Contact.php');
class Customer{
  var $db; var $id;


function __construct($tipo_id='id',$id=false) {
     $this->db =MDB2::singleton();
     $this->status_names=array(0=>'new');
     
     if(is_numeric($tipo_id) and !$id){
       $id= $tipo_id;
       $tipo_id='id';
     }


     if($tipo_id=='id'){//load from id
       if(!$this->get_data($id))
	 return false;
     }else if(is_array($id)){// Create a new order
       $this->create_new($id);
     }

     return true;

  }




 function get_data($id){
    $sql=sprintf("select * from customer where id=%s",prepare_mysql($id));
    $result =& $this->db->query($sql);
    if($this->data=$result->fetchRow()){	     
      $this->id=$this->data['id'];


      if($o_main_bill_address=new address($this->data['main_bill_address'])){

	$this->data['main_bill_address_id']=$o_main_bill_address->id;
	$this->data['main_bill_address']=$o_main_bill_address->display('html');
	unset($o_main_bill_address);
      }
	

      return true;
    }else
      return false;

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