<?

class part{
  

  var $id=false;

  function __construct($a1,$a2=false) {



    if(is_numeric($a1) and !$a2){
      $this->get_data('id',$a1);
    }
    else if(($a1=='new' or $a1=='create') and is_array($a2) ){
      $this->msg=$this->create($a2);
      
    } else
      $this->get_data($a1,$a2);

  }
  



  function get_data($tipo,$tag){
    if($tipo=='id')
      $sql=sprintf("select * from `Part Dimension` where `Part Key`=%d ",$tag);
    else
      return;

    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->id=$this->data['Part Key'];
    }
    

  }
  
  function create($data){
    // print_r($data);
     $base_data=array(
		     'part type'=>'Physical',
		     'part sku'=>'',
		     'part xhtml currently used in'=>'',
		     'part xhtml currently supplied by'=>'',
		     'part xhtml description'=>'',
		     'part unit description'=>'',
		     'part package size metadata'=>'',
		     'part package volume'=>'',
		     'part package minimun orthogonal volume'=>'',
		     'part gross weight'=>'',
		     'part valid from'=>'',
		     'part valid to'=>'',
		     'part most recent'=>'',
		     'part most recent key'=>''
		     );
     foreach($data as $key=>$value){
       if(isset( $base_data[strtolower($key)]) )
	 $base_data[strtolower($key)]=_trim($value);
     }
 
     if(!$this->valid_sku($base_data['part sku']) ){

       $base_data['part sku']=$this->new_sku();
     }

     $keys='(';$values='values(';
    foreach($base_data as $key=>$value){
      $keys.="`$key`,";
      $values.=prepare_mysql($value).",";
    }
    $keys=preg_replace('/,$/',')',$keys);
    $values=preg_replace('/,$/',')',$values);
    
    $sql=sprintf("insert into `Part Dimension` %s %s",$keys,$values);
    // print "$sql\n";
    // exit;
    if(mysql_query($sql)){
      $this->id = mysql_insert_id();

      if($base_data['part most recent']=='Yes')
      	$sql=sprintf("update  `Part Dimension` set `Part Most Recent Key`=%d where `Part Key`=%d",$this->id,$this->id);
	mysql_query($sql);

      $this->get_data('id',$this->id);
    }else{
      print "Error Part can not be created\n";exit;
    }

 }

  function load($data_to_be_read,$args=''){

  }
  
 function get($key=''){
   
    if(array_key_exists($key,$this->data))
      return $this->data[$key];

     $_key=preg_replace('/^part /','',$key);
    if(isset($this->data[$_key]))
      return $this->data[$key];

    
    switch($key){
      
    }
    
    return false;
  }
  

 function valid_sku($sku){
   // print "validadndo sku $sku";
   if(is_numeric($sku) and $sku>0 and $sku<9223372036854775807)
     return true;
   else
     return false;
 }

function used_sku($sku){
  $sql="select count(*) as num from `Part Dimension` where `Part SKU`=".prepare_mysql($sku);
  // print "$sql\n";
  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    if($row['num']>0)
      return true;
  }
  return false;
}

 function new_sku(){
   $sql="select max(`Part SKU`) as sku from `Part Dimension`";
   //   print "$sql\n";
   $result=mysql_query($sql);
   if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     return $row['sku']+1;
   }else
     return 1;
   
 }
 

}