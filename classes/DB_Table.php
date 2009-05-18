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





  /*
    Function: base_data
    Initialize data  array with the default field values
   */
  function base_data(){
    $data=array();
    $result = mysql_query("SHOW COLUMNS FROM `".$this->table_name." Dimension`");
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

    $this->msg='';  

    $base_data=$this->base_data();
    //   print "-----------------------\n";
    // print_r($base_data);
      foreach($data as $key=>$value){
	if(array_key_exists($key,$base_data)){
	  
	  if($value!=$this->data[$key]){
	    print "$key,old value: ".$this->data[$key]." New value:  $value,$options\n";
	    $this->update_field_switcher($key,$value,$options);
	    
	    //    $function_name='update_'.preg_replace('/\s/','',ucwords($key));
	    // call_user_func(array($this,$function_name),$value,$options);
	  }
	}
      }
      
      if(!$this->updated)
	$this->msg.=' '._('Nothing to be updated')."\n";
      
    }
  /*Function: update_field
   */
 protected function update_field_switcher($field,$value,$options=''){
   $this->update_field($field,$value,$options);
   
 }



protected function update_field($field,$value,$options=''){
   
  $value=prepare_mysql($value);
  
  $sql="update `".$this->table_name." Dimension` set `".$field."`=".$value." where `".$this->table_name." Key`=".$this->id;
  // print $sql;
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
  }
  
}



}

?>