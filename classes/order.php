<?
include_once('common/string.php');
class purchase_order{
  var $db;
  var $data=array();
  var $items=array();

  var $id;
  var $tipo;

  function __construct($tipo='order',$id=false) {
     $this->db =MDB2::singleton();

     if(is_numeric($id)){
       $this->id=$id;
       $this->get_data($tipo,$id);
     }



  }


  function get_data($tipo='order',$id)
    



}

?>