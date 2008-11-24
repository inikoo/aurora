<?
require_once 'classes/Name.php';
require_once 'classes/Email.php';

class Staff{
  var $db;
  var $data=array();
  var $items=array();
  var $status_names=array();
  var $id;
  var $tipo;





  function __construct($key='id',$data=false) {
     $this->db =MDB2::singleton();
     $this->status_names=array(0=>'new');


     if($key=='new' and is_array($data)){
       $this->create_order($data);
       if(!$this->id)
	 return;
       $key='id';
       $data=$this->id;
     }
     
     
     if(is_numeric($key) and !$data){
       $data=$key;
       $key='id';
     }
     $this->get_data($key,$data);
     

  }

  

  function get_data($key,$id){
    
    
    $sql=sprintf("select * from staff where id=%d",$id);

    $result =& $this->db->query($sql);
    if($this->data=$result->fetchRow()) 
      $this->id=$this->data['id'];
    
    $name=new name($this->data['name_id']);
   
    if($name->id)
      $this->data['name']=$name->display();
    else
      $this->data['name']=false;
    $email=new email($this->data['email_id']);
    if($email->id)
      $this->data['email']=$email->display();
    else
      $this->data['email']=false;

 }


}