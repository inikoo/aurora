<?
require_once 'Name.php';
require_once 'Email.php';

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
    
    if($key=='alias')
      $sql=sprintf("select * from `Employee Dimension` where `Employee Alias`=%s",prepare_mysql($id));
    elseif($key=='id')
            $sql=sprintf("select * from  `Employee Dimension`     where `Employee Key`=%d",$id);
    else
      return;
    $result =& $this->db->query($sql);
    if($this->data=$result->fetchRow()) 
      $this->id=$this->data['employee key'];
    

  }


}