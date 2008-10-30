<?
include_once('common/string.php');
class supplier{
  var $db;
  var $data=array();
  var $items=array();

  var $id;
  var $tipo;



  function __construct($id=false) {
     $this->db =MDB2::singleton();
     
     $this->tipo=$tipo;
     if(is_numeric($id)){
       $this->id=$id;
       $this->get_data();
     }



  }


  function get_data(){
    $sql=sprintf("select name,code,contact_id from supplier id=%d",$this->id);
    $result =& $this->db->query($sql);
    if($row=$result->fetchRow()){
      $this->data['name']=$row['name'];
      $this->data['code']=$row['code'];
      $this->data['contact_id']=$row['contact_id'];
      
    }
  }

  function load($key=''){
    switch($key){
    case('contact'):
	$this->tel[]=new contact($this->contact_id);
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