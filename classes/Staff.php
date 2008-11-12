<?
include_once('common/string.php');


class Staff{
  var $db;
  var $data=array();
  var $items=array();
  var $status_names=array();
  var $id;
  var $tipo;





  function __construct($tipo_id='id',$id=false) {
     $this->db =MDB2::singleton();
     $this->status_names=array(0=>'new');
     
     if(is_numeric($tipo_id) and !$id){
       $id= $tipo_id;
       $tipo_id='id';
     }


     if($tipo_id=='id'){//load from id
       $this->id=$id;
       if(!$this->get_data($tipo_id))
	 return false;
     }else if(is_array($id)){// Create a new order
       $this->create_order($id);
     }

     return true;

  }

  

  function get_data($tipo_id){
    

    $sql=sprintf("select * from staff where id=%s",prepare_mysql($this->id));

    $result =& $this->db->query($sql);
    if(!$this->data=$result->fetchRow()){	     
        return false;
    }
    $this->id=$this->data['id'];
  }


}