<?
abstract class DB_Table
{

  protected $table_name;
  protected  $ignore_fields=array();
  
    // Array: data
  // Class data
  public $data=array();
  // Integer: id
  // Database Primary Key
  public  $id=0;
 // Boolean: warning
  // True if a warning
  public $warning=false;
  // Boolean: error
  // True if error occuers
  public $error=false;
  // String: msg
  // Messages
  public $msg='';
  // Boolean: new
  // True if company has been created
  public $new=false;
 // Boolean: updated
  // True if company has been updated
  public $updated=false;
 // Boolean: found
  // True if company founded
  public $found=false;
 public $found_key=false;
  // Array: candidate
  // array with the posible matches
  public $candidate=array();

  public $editor=array(
		       'Author Name'=>false,
		       'Author Key'=>0,
		       'User Key'=>0,
		       'Date'=>false 
		       );

  /*
    Function: base_data
    Initialize data  array with the default field values
   */
  function base_data(){
    $data=array();
    $result = mysql_query("SHOW COLUMNS FROM `".$this->table_name." Dimension`");
    //  print "SHOW COLUMNS FROM `".$this->table_name." Dimension`\n\n\n\n\n";
    if (!$result) {
      echo 'Could not run query: ' . mysql_error();
     exit;
    }
    if (mysql_num_rows($result) > 0) {
     while ($row = mysql_fetch_assoc($result)) {
       if(!in_array($row['Field'],$this->ignore_fields))
	 $data[$row['Field']]=$row['Default'];
     }
   }
    return $data;
  }

  /*Method: update
    Switcher calling the apropiate update method
    Parameters:
    $data - associated array with Email Dimension fields
    */
  public function update($data,$options=''){



    $base_data=$this->base_data();
  
    foreach($data as $key=>$value){
      if(preg_match('/^Address.*Data$/',$key))
	$this->update_field_switcher($key,$value,$options);
      elseif(array_key_exists($key,$base_data)){

	if($value!=$this->data[$key]){
	  print "XXX data to update ".$this->data[$key]." ->  $value in $key ".$this->table_name.":\n";
	  $this->update_field_switcher($key,$value,$options);
	}
      }
    }
    //  print "=============================\n";
    
    if(!$this->updated)
      $this->msg.=' '._('Nothing to be updated')."\n";
    }
  /*Function: update_field
   */
 protected function update_field_switcher($field,$value,$options=''){
   $this->update_field($field,$value,$options);
   
 }

 protected function translate_data($data,$options=''){
   $_data=array();
   foreach($data as $key => $value){
     
     if(preg_match('/supplier/i',$options))
       $regex='/^Supplier /i';
     elseif(preg_match('/customer/i',$options))
       $regex='/^Customer /i';
     elseif(preg_match('/company/i',$options))
       $regex='/^Company /i';
     elseif(preg_match('/contact/i',$options))
       $regex='/^Contact /i';
     
     $rpl=$this->table_name.' ';


     $_key=preg_replace($regex,$rpl,$key);
     $_data[$_key]=$value;
   }
   
  


   return $_data;
 }

protected function update_field($field,$value,$options=''){
  
  //  print "** Update Field $field $value\n";

  $old_value=_('Unknown');
  $sql="select `".$field."` as value from  `".$this->table_name." Dimension`  where `".$this->table_name." Key`=".$this->id;
  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
    $old_value=$row['value'];
  }
   

  $sql="update `".$this->table_name." Dimension` set `".$field."`=".prepare_mysql($value)." where `".$this->table_name." Key`=".$this->id;
  //print $sql;

   mysql_query($sql);
  $affected=mysql_affected_rows();
  if($affected==-1){
    $this->msg.=' '._('Record can not be updated')."\n";
    $this->error=true;
    return;
  }elseif($affected==0){
    //$this->msg.=' '._('Same value as the old record');
    
  }else{
    $this->data[$field]=$value;
    $this->msg.=" $field "._('Record updated').", \n";
    $this->updated=true;
  

    if(preg_match('/customer|contact|company|order|staff|supplier|address/i',$this->table_name)){


      $history_data=array(
			  'indirect_object'=>$field
			  ,'old_value'=>$old_value
			  ,'new_value'=>$value
			  );
      $this->add_history($history_data);
      
    }

  }
  
 


}

protected function add_history($raw_data){
  
  print_r( $raw_data);
  
  $data['subject']='user';
  $data['subject_key']=$this->editor['User Key'];
  $data['action']='edited';
  $data['direct_object']=$this->table_name;
  $data['direct_object_key']=$this->id;
  $data['preposition']='to';
  $data['indirect_object']='';
  $data['indirect_object_key']=0;


   if($this->editor['Date'])
   $data['date']=$this->editor['Date'];
 else
   $data['date']=date("Y-m-d H:i:s");

 if($this->editor['Author Name'])
   $data['author']=$this->editor['Author Name'];
 else
   $data['author']=_('System');
 $data['author_key']=$this->editor['Author Key'];
 if(isset($raw_data['indirect_object']))
   $data['note']=$raw_data['indirect_object'].' '._('changed');
 else
   $data['note']='Unknown';
 $data['details']=$data['note'];
  if(isset($data['action']) and $data['action']=='created'){
    $data['preposition']='';
  }
 

  foreach($raw_data as $key=>$value){
    $data[$key]=$value;
  }
  
  if(isset($raw_data['old_value']) and  isset($raw_data['new_value']) ){
    $data['details']=$data['indirect_object'].' '._('changed from')." \"".$raw_data['old_value']."\" "._('to')." \"".$raw_data['new_value']."\"";
  } elseif(  isset($raw_data['new_value']) ){
    $data['details']=$data['indirect_object'].' '._('changed to')." \"".$raw_data['new_value']."\"";
  }


 print_r( $data);
 
 $sql=sprintf("insert into `History Dimension` (`History Date`,`Subject`,`Subject Key`,`Action`,`Direct Object`,`Direct Object Key`,`Preposition`,`Indirect Object`,`Indirect Object Key`,`History Abstract`,`History Details`,`Author Name`,`Author Key`) values (%s,%s,%d,%s,%s,%d,%s,%s,%d,%s,%s,%s,%d)"
	      ,prepare_mysql($data['date'])
	      ,prepare_mysql($data['subject'])
	      , $data['subject_key']
	      ,prepare_mysql($data['action'])
	      ,prepare_mysql($data['direct_object'])
	      ,$data['direct_object_key']
	      ,prepare_mysql($data['preposition'],false)
	      ,prepare_mysql($data['indirect_object'],false)
	      ,$data['indirect_object_key']
	      ,prepare_mysql($data['note'])
	      ,prepare_mysql($data['details'])
	      ,prepare_mysql($data['author'])
	      , $data['author_key']
		  );

   mysql_query($sql);


}



}

?>