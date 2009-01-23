<?

class part{
  
  var $db;
  var $id=false;

  function __construct($a1,$a2=false) {
    $this->db =MDB2::singleton();


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
    elseif($tipo=='supplier_code')
      $sql=sprintf("select * from `Part Dimension` where `Part Vendor Product Code`=%s and `Vendor Key`=%d `Part Most Recent`='Yes' ",prepare_mysql($tag['supplier product code']),$tag['supplier key']);
    else
      return;

    //print "$sql\n";
    if($result =& $this->db->query($sql)){
      $this->data=$result->fetchRow();
      $this->id=$this->data['part key'];
    }
  }
  
  function create($data){
    
     $base_data=array(
		     'part type'=>'Physical',
		     'part vendor key'=>'',
		     'part vendor product code'=>'',
		     'part vendor product code save as'=>'',
		     'part vendor product upc'=>'',
		     'part vendor product unit price'=>'',
		     'part vendor product unit description'=>'',
		     'part vendor product units per part'=>'',

		     'part sku'=>'',
		     'part xhtml used in products'=>'',
		     'part unit description'=>'',
		     'part package size metadata'=>'',
		     'part package volume'=>'',
		     'part package minimun orthogonal volume'=>'',
		     'part package weight'=>'',
		     'part valid from'=>'',
		     'part valid to'=>'',
		     'part most recent'=>'',
		     'part current part key'=>''
		     );
     foreach($data as $key=>$value){
       $base_data[strtolower($key)]=_trim($value);
     }
 
     if($this->valid_sku($base_data['part sku']) or $this->used_sku($base_data['part sku'])  ){
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
    //  print "$sql\n";
    $affected=& $this->db->exec($sql);
    $this->id = $this->db->lastInsertID();  
    $this->get_data('id',$this->id);

 }

  function load($data_to_be_read,$args=''){

  }
  
 function get($key=''){
    $key=strtolower($key);
    if(isset($this->data[$key]))
      return $this->data[$key];
    
    switch($key){
      
    }
    
    return false;
  }
  

 function valid_sku($sku){
   if(is_numeric($sku) and $sku>0 and $sku<9223372036854775807)
     return true;
   else
     return false;
 }

function used_sku($sku){
  $sql="select count(*) as num from `Part Dimension` where `Part SKU`=".prepare_mysql($sku);
  if($result =& $this->db->query($sql)){
    if($row['num']>0)
      return true;
  }
  return false;
}

 function new_sku(){
   $select="select max(`Part SKU`) as sku from `Part Dimension`";
  if($result =& $this->db->query($sql)){
    $row=$result->fetchRow();
    return $row['sku']+1;
  }else
    return 1;

 }


}