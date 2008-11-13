<?
class Email{
  var $db;
  var $data=array();
  var $id=false;

  
  function __construct($id=false) {
     $this->db =MDB2::singleton();

     if(is_numeric($id)){
       $this->get_data($id);
     }


  }


function get_data($id){
   $sql=sprintf("select * from email where  id=%d",$id);
   $result =& $this->db->query($sql);
    if($this->data=$result->fetchRow()){
      $this->id=$this->data['id'];
      return true;
    }
    return false;
}

 
 function display($tipo=''){

   switch($tipo){
   case('link'):
   default:
     return '<a href="mailto:'.$this->data['email'].'">'.$this->data['email'].'</a>';
     
}
   

 }


}
?>