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

    $base_data=array(
		     'deal trigger'=>'',
		     'deal trigger key'=>'',
		     'deal campain name'=>'',
		     'deal description'=>'',
		     'deal terms type'=>'',
		     'deal terms description'=>'',
		     'deal allowance type'=>'',
		     'deal allowance target'=>'',
		     'deal allowance target key'=>'',
		     'deal allowance description'=>'',
		     'deal allowance metadata'=>'',
		     'deal begin date'=>'',
		     'deal expiration date'=>''
		     );


    foreach($data as $key=>$value){
      $base_data[$key]=_trim($value);
    }
    // print_r($base_data);
   
    if($base_data['deal allowance type']=='Percentage Off' and preg_match('/Quantity Ordered/i',$base_data['deal terms type'])){
      //   print "***********";
      if(preg_match('/order \d+ or more/i',$base_data['deal terms description'],$match))
	$a=preg_replace('/[^\d]/','',$match[0]);
      else{
	print "ohh no a not foun in deal class ".$base_data['deal terms description']."\n";
	print_r($data);
	exit;
      }
	
      if(preg_match('/^\d+\%/i',$base_data['deal allowance description'],$match))
	$b=.01*preg_replace('/\%/','',$match[0]);
      $base_data['deal allowance metadata']="$a,$b";
      //    print_r($match);
    } if($base_data['deal allowance type']=='Percentage Off' and preg_match('/Order Interval/i',$base_data['deal terms type'])){
      //   print "***********";
      if(preg_match('/last order within \d+ days/i',$base_data['deal terms description'],$match))
	$a=preg_replace('/[^\d]/','',$match[0]).' day';
       if(preg_match('/last order within \d+ .* month/i',$base_data['deal terms description'],$match))
	$a=preg_replace('/[^\d]/','',$match[0]).' month';

      if(preg_match('/^\d+\%/i',$base_data['deal allowance description'],$match))
	$b=.01*preg_replace('/\%/','',$match[0]);
      $base_data['deal allowance metadata']="$a,$b";
      //    print_r($match);
    }elseif($base_data['deal allowance type']=='Get Free' and preg_match('/Quantity Ordered^/i',$base_data['deal terms type']) ){

     $base_data['deal allowance description']=preg_replace('/ one /',' 1 ',$base_data['deal allowance description']);
     $base_data['deal allowance description']=preg_replace('/ two /',' 2 ',$base_data['deal allowance description']);
     $base_data['deal allowance description']=preg_replace('/ three /',' 3 ',$base_data['deal allowance description']);
     
      preg_match('/buy \d+/i',$base_data['deal allowance description'],$match);
      $buy=_trim(preg_replace('/[^\d]/','',$match[0]));

      preg_match('/get \d+/i',$base_data['deal allowance description'],$match);
      $get=_trim(preg_replace('/[^\d]/','',$match[0]));
      


      $base_data['deal allowance metadata']=$buy.','.$get;
    }

   
    //print_r($base_data);

    $keys='(';$values='values(';
    foreach($base_data as $key=>$value){
      $keys.="`$key`,";
      $values.=prepare_mysql($value).",";
    }
    $keys=preg_replace('/,$/',')',$keys);
    $values=preg_replace('/,$/',')',$values);
    $sql=sprintf("insert into `Deal Dimension` %s %s",$keys,$values);
    // print "$sql\n";
    $affected=& $this->db->exec($sql);
    $this->id = $this->db->lastInsertID();  
    $this->get_data('id',$this->id);

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