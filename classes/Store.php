<?

class store{


  var $id=false;


  function __construct($a1,$a2=false) {



    if(is_numeric($a1) and !$a2){
      $this->get_data('id',$a1);
    }
    else if(($a1=='new' or $a1=='create') and is_array($a2) ){
      $this->msg=$this->create($a2);
      
    }else
      $this->get_data($a1,$a2);

  }
  
// function get_unknown(){
//   $sql=sprintf("select * from `Store Dimension` where `Store Type`='unknown'");
//   $result=mysql_query($sql);
//   if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
//     $this->id=$this->data['Store Key'];
// }


  function get_data($tipo,$tag){

    if($tipo=='id')
      $sql=sprintf("select * from `Store Dimension` where `Store Key`=%d",$tag);
    elseif($tipo=='code')
      $sql=sprintf("select * from `Store Dimension` where `Store Code`=%s",prepare_mysql($tag));
    else
      return;

    print $sql;
    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
      $this->id=$this->data['Product Key'];
    

  }


  

  

  function get($key=''){

    if(isset($this->data[$key]))
      return $this->data[$key];
    
    switch($key){
    case('code'):
      return $this->data['Store Code'];
      break;
    case('type'):
      return $this->data['Store Type'];
      break;
      
    }
    $_key=ucfirst($key);
    if(isset($this->data[$_key]))
      return $this->data[$_key];
    print "Error $key not found in get from Product\n";
    return false; 

  }

}