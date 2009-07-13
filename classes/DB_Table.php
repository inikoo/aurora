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
    //print "data to update in ".$this->table_name.":\n";
    //print_r($data);
    foreach($data as $key=>$value){
      if(preg_match('/^Address.*Data$/',$key))
	$this->update_field_switcher($key,$value,$options);
      elseif(array_key_exists($key,$base_data)){
	if($value!=$this->data[$key]){
	  $this->update_field_switcher($key,$value,$options);
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
  

    if(preg_match('/customer|contact|company|order|staff|supplier/i',$this->table_name)){

  // Save to history

    // $sql=sprintf("insert into `History Dimension` (`Subject`)   ");
    $note=$field.' '._('changed');
    $details=$field.' '._('changed from')." \"$old_value\" "._('to')." \"$value\"";
    if($this->editor['Author Name'])
      $author=$this->editor['Author Name'];
    else
      $author=_('System');
    
 if($this->editor['Date'])
   $date=$this->editor['Date'];
 else
   $date=date("Y-m-d H:i:s");
 
 $sql=sprintf("insert into `History Dimension` (`History Date`,`Subject`,`Subject Key`,`Action`,`Direct Object`,`Direct Object Key`,`Preposition`,`Indirect Object`,`Indirect Object Key`,`History Abstract`,`History Details`,`Author Name`,`Author Key`) values (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
	      ,prepare_mysql($date)
	      ,prepare_mysql('user')
	      ,prepare_mysql($this->editor['User Key'])
	      ,prepare_mysql('edited')
	      ,prepare_mysql($this->table_name)
	      ,prepare_mysql($this->id)
	      ,prepare_mysql('to')
	      ,prepare_mysql($field)
	      ,0
	      ,prepare_mysql($note)
	      ,prepare_mysql($details)
	      ,prepare_mysql($author)
	      ,prepare_mysql($this->editor['Author Key'])
		  );

   mysql_query($sql);
    }

  }
  
 


}



}

?>