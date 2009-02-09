<?
require_once 'Name.php';
require_once 'Email.php';

class Staff{

  var $data=array();
  var $items=array();
  var $status_names=array();
  var $id;
  var $tipo;





  function __construct($key='id',$data=false) {

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

   $result=mysql_query($sql);
   if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
     $this->id=$this->data['Employee Key'];
    

  }


}