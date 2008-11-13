<?
class Telecom{
  var $db;
  var $data=array();
  var $tipo;

  
  function __construct($id=false) {
     $this->db =MDB2::singleton();

     if(is_numeric($id)){
       if($this->get_data($id))
	 return true;
     }
     return false;


  }


function get_data($id){
   $sql=sprintf("select * from telecom where  id=%d",$id);
   $result =& $this->db->query($sql);
    if($this->data=$result->fetchRow()){
      $this->id=$this->data['id'];
      return true;
    }
    return false;

}


}
?>