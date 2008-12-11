<?

include_once('Contact.php');
include_once('Telecom.php');
include_once('Email.php');
include_once('Address.php');
include_once('Name.php');

class supplier{
  var $db;
  var $data=array();
  var $items=array();

  var $id;
  var $tipo;

  function __construct($arg1=false,$arg2=false) {
     $this->db =MDB2::singleton();
     if(is_numeric($arg1)){
       $this->get_data($arg1);
       return ;
     }
  }


  function get_data($id){
    $sql=sprintf("select id,name,code,contact_id from supplier where id=%d",$id);
    $result =& $this->db->query($sql);
    if($row=$result->fetchRow()){
      $this->data['name']=$row['name'];
      $this->data['code']=$row['code'];
      $this->data['contact_id']=$row['contact_id'];
      
      $this->id=$row['id'];
      $this->contact=false;
      return true;
    }else
      return false;
  }

  function load($key=''){
    switch($key){
   
    case('contacts'):
    case('contact'):
      $this->contact=new Contact($this->data['contact_id']);
      if($this->contact->id){
	$this->contact->load('telecoms');
	$this->contact->load('contacts');
      }

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