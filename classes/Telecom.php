<?
class Telecom{
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
   $sql=sprintf("select * from telecom where  id=%d",$id);
   $result =& $this->db->query($sql);
    if($this->data=$result->fetchRow()){
      $this->id=$this->data['id'];
      return true;
    }
    return false;

}

 function display($tipo=''){

   switch($tipo){
   default:
     $tmp=($this->data['icode']!=''?'+'.$this->data['icode'].' ':'').($this->data['ncode']!=''?$this->data['ncode'].' ':'').$this->get('spaced_number').($this->data['ext']!=''?' '._('ext').' '.$this->data['ext']:'');
     return $tmp;
   }
 }
 

 function get($tipo='')
 {
   switch($tipo){
   case('spaced_number'):
     return _trim(strrev(chunk_split(strrev($this->data['number']),4," ")));
     break;
   default:
     return false;
   }
   
 }

}
?>