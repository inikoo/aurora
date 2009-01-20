<?

class deal{

  var $db;
  var $id=false;


  function __construct($a1,$a2=false) {
    $this->db =MDB2::singleton();


    if(is_numeric($a1) and !$a2){
      $this->get_data('id',$a1);
    }
    else if(($a1=='new' or $a1=='create') and is_array($a2) ){
      $this->msg=$this->create($a2);
      
    }else
      $this->get_data($a1,$a2);

  }
  



  function get_data($tipo,$tag){

    if($tipo=='id')
      $sql=sprintf("select * from `Deal Dimension` where `Deal Key`=%d",$tag);
    //    elseif($tipo=='code')
    //  $sql=sprintf("select * from `Deal Dimension` where `Deal Code`=%s",prepare_mysql($tag));
    // print $sql;

    if($result =& $this->db->query($sql)){
      $this->data=$result->fetchRow();
      $this->calculate_deal=create_function('$transaction_data,$customer_id,$date', $this->get('Deal Metadata'));
      $this->id=$this->data['deal key'];
    }
  }
  

  function create($data){




  }

  function get($key=''){
    $key=strtolower($key);
    if(isset($this->data[$key]))
      return $this->data[$key];
    
    switch($key){
      
    }
    
    return false;
  }

}