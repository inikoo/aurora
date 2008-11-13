<?

include_once('classes/Contact.php');
include_once('classes/Telecom.php');
include_once('classes/Email.php');
include_once('classes/Address.php');
include_once('classes/Name.php');

class supplier{
  var $db;
  var $data=array();
  var $items=array();

  var $id;
  var $tipo;

  function __construct($id=false) {
     $this->db =MDB2::singleton();
     if(is_numeric($id)){
       if(!$this->get_data($id))
	 return false;
       else
	 return true;
     }else
       return false;
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
      if($this->contact=new Contact($this->data['contact_id'])){
	
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