<?
include_once('classes/Telecom.php');
include_once('classes/Email.php');
include_once('classes/Address.php');
include_once('classes/Name.php');

class Contact{
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
     }
     return false;


  }


  function get_data($id){
    $sql=sprintf("select id,name,file_as,alias,has_child,has_parent,UNIX_TIMESTAMP(date_creation) as date_creation,UNIX_TIMESTAMP(date_updated) as date_updated,tipo,genero,main_address,main_tel,main_email,main_contact from contact where id=%d",$id);
    $result =& $this->db->query($sql);
    if($row=$result->fetchRow()){
      $this->data['name']=$row['name'];
      $this->data['file_as']=$row['file_as'];
      $this->data['alias']=$row['alias'];
      $this->data['has_child']=$row['has_child'];
      $this->data['has_parent']=$row['has_parent'];
      $this->dates=array(
			 'ts_creation'=>$row['date_creation'],
			 'ts_updated'=>$row['date_updated']
			 );
      $this->data['tipo']=$row['tipo'];
      $this->data['genero']=$row['genero'];
      
      $this->main=array(
			'address'=>$row['main_address'],
			'tel'=>$row['main_tel'],
			'email'=>$row['main_email'],
			'contact'=>$row['main_contact']
			);
      $this->id=$row['id'];
      return true;
    }
    return false;
  }

  function load($key=''){
    switch($key){
    case('telecoms'):
      $this->load('tels');
      $this->load('emails');
      break;
    case('tels'):
      $sql=sprintf("select telecom_id from telecom2contact left join telecom on (telecom.id=telecom_id) where contact_id=%d ",$this->id);
      $result =& $this->db->query($sql);
      while($row=$result->fetchRow()){
	if($tel=new telecom($row['telecom_id']))
	  $this->tel[]=$tel;
      }
      break;
    case('emails'):
      $sql=sprintf("select id from email where contact_id=%d ",$this->id);
      $result =& $this->db->query($sql);
      while($row=$result->fetchRow()){
	if($email=new telecom($row['id']))
	  $this->email[]=$email;
      }
      break;
    case('contacts'):
      $sql=sprintf("select child_id from contact_relations where parent_id=%d ",$this->id);
      $result =& $this->db->query($sql);
      while($row=$result->fetchRow()){
	if($child=new telecom($row['child_id']))
	  $this->contacts[]=$child;
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
  
  

}

?>