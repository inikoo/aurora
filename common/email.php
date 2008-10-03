<?



function display_email_link($email_id){
$db =& MDB2::singleton();
   $sql=sprintf("select email from email where id=%d",$email_id);
   //   print "$sql\n";
    $res = $db->query($sql);  
  if ($row=$res->fetchRow()){
    return '<a href="mailto:'.$row['email'].'">'.$row['email'].'</a>';
  }else
    return false;
}

function display_email($email_id){
$db =& MDB2::singleton();
   $sql=sprintf("select email from email where id=%d",$email_id);
   //   print "$sql\n";
    $res = $db->query($sql);  
  if ($row=$res->fetchRow()){
    return $row['email'];
  }else
    return false;
}

function insert_email($email_data,$contact_id,$date_index='',$history){
 global $_contact_tipo;
 global $_email_tipo;
 
 if($contact_id==0 or !is_numeric($contact_id))
   exit("woring email insert $contact_id \n");

  $db =& MDB2::singleton();
  if($email_data['email']=='')
    return 0;
  $email=addslashes(strtolower($email_data['email']));
  $sql=sprintf("insert into email  (email,contact,tipo,contact_id) values ('%s',%s,%d,%d)",$email,prepare_mysql($email_data['contact']),$email_data['tipo'],$contact_id);
  // print "$sql\n";
  //$db->exec($sql);
  //$email_id= $db->lastInsertID();

   mysql_query($sql);
   $email_id=mysql_insert_id();
   if($history){
     $email_tipo=$_email_tipo[$email_data['tipo']];
     $contact_data=get_contact_data($contact_id);
     $contact_tipo=$_contact_tipo[$contact_data['tipo']];
     
     
//      if(is_numeric($customer_id= get_customer_from_contact($contact_id))){
//        $recipient_id=$customer_id;
//        $sujeto='Customer';
//        $email_tipo='Email';
//      }else{
//        $sujeto='Contact';
//        $recipient_id=$contact_id;
//        $email_tipo=$_email_tipo[$email_data['tipo']];
//      }

     $sujeto='Contact';
     $recipient_id=$contact_id;
     $email_tipo=$_email_tipo[$email_data['tipo']];
  $sql=sprintf("insert into history (tipo,sujeto,sujeto_id,objeto,objeto_id,date) values ('NEW','%s',%d,'%s',%d,%s)",$sujeto,$recipient_id,$email_tipo,$email_id,prepare_mysql_date($date_index));
  //print "$sql\n";
  //$db->exec($sql);
  //$history_id=$db->lastInsertID();
     mysql_query($sql);
      $history_id=mysql_insert_id();



  $email= display_email($email_id);
  $sql=sprintf("insert into history_item (history_id,columna,old_value,new_value) values (%d,'%s',NULL,%s)",$history_id,'email',prepare_mysql($email));
  //print "$sql\n";
   // $db->exec($sql);
   //$email_id=$db->lastInsertID();
   mysql_query($sql);
   $email_id=mysql_insert_id();
   }
   return $email_id;


}


function get_email_metadata($email_id){
  $db =& MDB2::singleton();
  global $_contact_tipo;
  global $_email_tipo;
  $sql=sprintf("select contact_id,contact.tipo as c_tipo,email.tipo as email_tipo  from email left join contact on (contact_id=contact.id) where email.id=%d",$email_id);
  // print "$sql\n";
  $res = $db->query($sql);  
  $metadata=array();
  while ($row=$res->fetchRow()){
    
    $metadata[]=array(
		      'sujeto'=>'Contact',
		      'objeto'=>$_email_tipo[$row['email_tipo']],
		      'objeto_id'=>$email_id,
		      'sujeto_id'=>$row['contact_id']
		      );

    // check if this contact has a parent and if this parent has it as principal
    $sql="select contact.tipo as c_tipo,parent_id as contact_id from contact_relations left join contact on (parent_id=contact.id) where main_email='".$email_id."' and child_id=".$row['contact_id'] ;
    //print "$sql\n";
 $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
    while($row2 = mysql_fetch_array($result, MYSQL_ASSOC)) {


      $contact_id=$row2['contact_id'];
      if(is_numeric($customer_id= get_customer_from_contact($contact_id))){
	$recipient_id=$customer_id;
	$sujeto='Customer';
	$email_tipo='Email';
      }else{
	$sujeto='Contact';
	$recipient_id=$contact_id;
	$email_tipo=$metadata['email_tipo'];
      }
      
      $metadata[]=array(
			'sujeto'=>$sujeto,
			'objeto'=>'Main Email',
			'objeto_id'=>$email_id,
			'sujeto_id'=>$recipient_id
		      );

      
   }



  }

  



   return $metadata;
}



function update_email($email_id,$email_data,$date_index=''){
  $db =& MDB2::singleton();
  if($email_data['email']=='')
    return 0;
  //print "updation enmail \n";
  // print_r($email_data);


  $old_values=get_email_data($email_id);
  $array_metadata=get_email_metadata($email_id);
  if(!isset($email_data['contact_id']))
    $email_data['contact_id']=$old_values['contact_id'];
  
  $keys=array('email','contact','tipo','contact_id');
    $update_sql='';
  $values=array();
  foreach($keys as $key){
    //print $old_values[$key]."z".$email_data[$key]."zz\n";
    if(strcmp($old_values[$key],$email_data[$key])){

      $values[$key]=array('old'=>$old_values[$key],'new'=>$email_data[$key]);
      //  print "$key <-\n";
      $array_history_sql[$key]="insert into history_item (history_id,columna,old_value,new_value) values (%d,'$key',%s,%s)";
      $update_sql.=" $key=".prepare_mysql($email_data[$key]);
	
    }
  }

  
 if(count($values)>0){
    $sql=sprintf("update email  set %s where id=%d",$update_sql,$email_id);
    //print "$sql\n";
    //$db->exec($sql);
    mysql_query($sql);
    foreach($array_metadata as $metadata){
      
      
      $sql=sprintf("insert into history (tipo,sujeto,sujeto_id,objeto,objeto_id,date) values ('UPD','%s',%d,'%s',%d,%s)",$metadata['sujeto'],$metadata['sujeto_id'],$metadata['objeto'],$metadata['objeto_id'],prepare_mysql_date($date_index));
      // print "$sql\n";
       //$db->exec($sql);
       //$history_id=$db->lastInsertID();
      mysql_query($sql);
      $history_id=mysql_insert_id();
      




      foreach($array_history_sql as $key=>$history_sql){

	if($key!='contact_id'){
	  $sql=sprintf($history_sql,$history_id,prepare_mysql($values[$key]['old']),prepare_mysql($values[$key]['new']));
	  //   print "$sql\n";
	  mysql_query($sql);
	//$db->exec($sql);
	}
      }
    }
  }


 // exit('xx');

   return true;
  

}


function set_principal_email($recipient,$recipient_id,$email_id){
  $db =& MDB2::singleton();
  
  if(
     ($recipient=='customer' or $recipient=='contact') 
     ){
    
    $sql=sprintf("update %s set email=%d where id=%d",$recipient,$email_id,$recipient_id);
    //$db->exec($sql);
    mysql_query($sql);
  }
}


function guess_email($email,$contact='',$tipo=1){
  if(check_email_address($email) ){

    // if($contact=='')
    //  $contact=get_name($contact_id);
    $email_data=array ('email'=>$email,'contact'=>$contact,'tipo'=>$tipo);
    
    return $email_data;
    
  }

  else
    return false;
}


function get_email_data($email_id){
  $db =& MDB2::singleton();
  $sql=sprintf("select * from email where id=%d",$email_id);
  $res = $db->query($sql);  
  if($row=$res->fetchRow()){
    //    print "hola mundo\n";
    // print_r($row);
    return $row;
  }else
    return false;
}

function check_email_address($email) {
// First, we check that there's one @ symbol, and that the lengths are right
if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
// Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
return false;
}
// Split it into sections to make life easier
$email_array = explode("@", $email);
$local_array = explode(".", $email_array[0]);
for ($i = 0; $i < sizeof($local_array); $i++) {
if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
return false;
}
}
if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
$domain_array = explode(".", $email_array[1]);
if (sizeof($domain_array) < 2) {
return false; // Not enough parts to domain
}
for ($i = 0; $i < sizeof($domain_array); $i++) {
if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
return false;
}
}
}
return true;
}

?>