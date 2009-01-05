<?

class Country{
  var $db;
  var $data=array();
  var $id=false;

  function __construct($arg1=false,$arg2=false) {
     $this->db =MDB2::singleton();
     
     
     if($arg1=='id' and is_numeric($arg2)){
       $this->get_data('id',$arg2);
       return;
     }elseif($arg1=='code'){
       $this->get_data('code',$arg2);
       return;
     }elseif(preg_match('/^(minicode|2alpha|2 alpha code)$/i',$arg1)){
       $this->get_data('2 alpha code',$arg2);
       return;
     }elseif($arg1=='name' and $arg2!=''){
       $name=$arg2;
       $this->get_data('name',$name);
       return;
     }elseif($arg1=='new' and is_array($arg2)){
       $this->create('name',$name);
       return;
     }
     
     if(is_numeric($arg1) and !$arg2){
       $this->get_data('id',$arg1);
     }



  }


  function get_data($key,$id){
    
    if($key=='id'){
      $sql=sprintf("SELECT * FROM dw.`Country Dimension` C where `Country Key`=%d",$id); 
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	$this->data=$row;
	$this->id=$row['country key'];
      }
      return;
    }
    if($key=='2 alpha code'){
      $sql=sprintf("SELECT * FROM dw.`Country Dimension` C where `Country 2 alpha code`=%s",prepare_mysql($id)); 
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	$this->data=$row;
	$this->id=$row['country key'];
      }
      return;
    } 
    if($key=='code'){
      $sql=sprintf("SELECT * FROM dw.`Country Dimension` C where `Country Code`=%s",prepare_mysql($id)); 
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	$this->data=$row;
	$this->id=$row['country key'];
      }
      return;
    } 

    if($key=='name'){
      $sql=sprintf("SELECT * FROM dw.`Country Dimension` C where `Country Name`=%s",prepare_mysql($id)); 
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	$this->data=$row;
	$this->id=$row['country key'];
	return;
      }
      $sql=sprintf("SELECT * FROM dw.`Country Dimension` C where `Country Official Name`=%s",prepare_mysql($id)); 
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	$this->data=$row;
	$this->id=$row['country key'];
	return;
      }
      $sql=sprintf("SELECT * FROM dw.`Country Dimension` C where `Country Native Name`=%s",prepare_mysql($id)); 
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	$this->data=$row;
	$this->id=$row['country key'];
	return;
      }
    } 


  }

}


?>