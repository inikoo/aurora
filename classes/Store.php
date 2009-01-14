<?

class store{

  var $db;
  var $id=false;


  function __construct($a1,$a2=false) {
    $this->db =MDB2::singleton();


    if(is_numeric($a1) and !$a2){
      $this->get_data('id',$a1);
    }
    else if(($a1=='new' or $a1=='create') and is_array($a2) ){
      $this->msg=$this->create($a2);
      
    }elseif($a1=='unknown') 
       $this->get_unknown();
    else
      $this->get_data($a1,$a2);

  }
  
function get_unknown(){
   $sql=sprintf("select * from `Store Dimension` where `Store Type`='unknown'");
   if($result =& $this->db->query($sql)){
      $this->data=$result->fetchRow();
      $this->id=$this->data['store key'];
    }
}


  function get_data($tipo,$tag){

    if($tipo=='id')
      $sql=sprintf("select * from `Store Dimension` where `Store Key`=%d",$tag);
    elseif($tipo=='code')
      $sql=sprintf("select * from `Store Dimension` where `Store Code`=%s",prepare_mysql($tag));
    // print $sql;

    if($result =& $this->db->query($sql)){
      $this->data=$result->fetchRow();
      $this->id=$this->data['store key'];
    }
  }
  

  

  function get($key=''){
    $key=strtolower($key);
    if(isset($this->data[$key]))
      return $this->data[$key];
    
    switch($key){
    case('code'):
      return $this->data['store code'];
      break;
    case('type'):
      return $this->data['store type'];
      break;
      
    }
    
    return false;
  }

}