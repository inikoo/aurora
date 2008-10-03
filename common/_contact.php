<?


function get_contact_data($contact_id,$get_children=true){
  $db =& MDB2::singleton();

  $sql=sprintf("select * from contact where id=%d",$contact_id);
  $res = $db->query($sql);  
  if ($contact=$res->fetchRow()){
    if($get_children){
      // get also the child data
      $sql=sprintf("select child_id from contact_relations where parent_id=%d",$contact_id);
      $res2 = $db->query($sql);  
      $contact['child']=array();
      while ($row2=$res2->fetchRow()){
	$child_id=$row2['child_id'];
	$contact['child'][]=get_contact_data($child_id,false);
      }
  }

    return $contact;
  }else
    return false;
}

//   if($act_data['postcode']=="") 
// //  and 
// //  preg_match('/\s*\d{4,5}\s*/i'
// //,$act_data['town']
// //,$match)
// //)
// {
// //     print"caacacacaca";
//      $act_data['postcode']=_trim($match[0]);
//      $act_data['town']=preg_replace('/\s*\d{4,5}\s*/','',$act_data['town']);
//    }

function get_name($contact_id){
  $db =& MDB2::singleton();

  $sql=sprintf("select name from contact where id=%d",$contact_id);
  $res = $db->query($sql);  
  if ($row=$res->fetchRow()){
    return $row['name'];
  }else
    return false;
}

function get_name_data($contact_id){
  $db =& MDB2::singleton();

  $sql=sprintf("select genero,prefix,first,middle,last,suffix,alias from name where contact_id=%d",$contact_id);
  $res = $db->query($sql);  
  if ($row=$res->fetchRow()){
    return $row;
  }else
    return false;
}



function get_customer_id($contact_id){
  $db =& MDB2::singleton();

  $sql=sprintf("select id from customer where contact_id=%d",$contact_id);
  $res = $db->query($sql);  
  if ($row=$res->fetchRow()){
    return $row['id'];
  }else
    return false;
}



function get_addresses($contact_id,$_tipo='shop'){
  $db =& MDB2::singleton();
  
  
  switch($_tipo){
  case('bill'):
    $tipo=' and tipo=2';
    break;
  case('del'):
    $tipo=' and tipo=3';
    break;
  case('shop'):
    $tipo=' and tipo=1';
    break;
  case('all'):
    $tipo='';
    break;
  }
  





  
  $addresses=array();
  $sql=sprintf("select group_concat(tipo) as tipos ,address_id from address2contact where contact_id=%d %s group by address_id ",$contact_id,$tipo);
  $res = $db->query($sql);  
  while($row=$res->fetchRow()){
    $address_data=get_address_data($row['address_id']);
    $address_data['tipos']=$row['tipos'];
    $addresses[$row['address_id']]=$address_data;
  }
  return $addresses;
}


function get_telecoms($contact_id){
  $tels=get_tels($contact_id);
  $faxes=get_faxes($contact_id);
  $mobs=get_mobiles($contact_id);
  $emails=get_emails($contact_id,false);
  $telecom=array();
  foreach($emails as $email){
    $telecom[]=array('email',display_email_link($email['id']));
  }

  foreach($tels as $id=>$tel){
    
    $telecom[]=array('tel',display_tel($id));
  }
  foreach($faxes as $id=>$fax){
      $telecom[]=array('fax',display_tel($id));
  }
 foreach($mobs as $id=>$mob){
   $telecom[]=array('mob',display_tel($id));
  }

  return $telecom;
}

function get_tels($contact_id){

  $db =& MDB2::singleton();
  $tels=array();
  $sql=sprintf("select telecom_id from telecom2contact where (tipo=1 or tipo=4) and contact_id=%d  group by telecom_id",$contact_id);
  $res = $db->query($sql);  

  
  while($row=$res->fetchRow()){

    $tels[$row['telecom_id']]=get_tel_data($row['telecom_id']);

  }

    $sql=sprintf("select telecom_id from telecom2contact left join contact_relations on (contact_id=child_id) where tipo=4 and parent_id=%d  group by telecom_id",$contact_id);
  $res = $db->query($sql);  
  while($row=$res->fetchRow()){
    $tels[$row['telecom_id']]=get_tel_data($row['telecom_id']);
  }


  return $tels;
}




function get_faxes($contact_id){
  $db =& MDB2::singleton();
  $tels=array();
  $sql=sprintf("select telecom_id from telecom2contact where (tipo=2 or tipo=5) and contact_id=%d  group by telecom_id",$contact_id);
  $res = $db->query($sql);  
  while($row=$res->fetchRow()){
    $tels[$row['telecom_id']]=get_tel_data($row['telecom_id']);
  }

    $sql=sprintf("select telecom_id from telecom2contact left join contact_relations on (contact_id=child_id) where tipo=5 and parent_id=%d  group by telecom_id",$contact_id);
  $res = $db->query($sql);  
  while($row=$res->fetchRow()){
    $tels[$row['telecom_id']]=get_tel_data($row['telecom_id']);
  }


  return $tels;
}

function get_mobiles($contact_id){
  $db =& MDB2::singleton();

  $sql=sprintf("select telecom_id from telecom2contact where (tipo=3) and contact_id=%d  group by telecom_id",$contact_id);
  $res = $db->query($sql);  
  $tels=array();
  while($row=$res->fetchRow()){
    $tels[$row['telecom_id']]=get_tel_data($row['telecom_id']);
  }


   $sql=sprintf("select telecom_id from telecom2contact left join contact_relations on (contact_id=child_id) where tipo=3 and parent_id=%d  group by telecom_id",$contact_id);
  $res = $db->query($sql);  
  while($row=$res->fetchRow()){
    $tels[$row['telecom_id']]=get_tel_data($row['telecom_id']);
  }


  return $tels;
}

function get_child_names($contact_id){
  $db =& MDB2::singleton();

  $sql=sprintf("select name,contact.id from contact left join contact_relations on (contact.id=child_id) where parent_id=%d",$contact_id);
  //print "$sql\n";
  $res = $db->query($sql);  
  $names=array();
  while($row=$res->fetchRow()){
    $names[$row['id']]=get_name_data($row['id']);
  }
  return $names;
}


function get_emails($contact_id,$children=true){
  $db =& MDB2::singleton();

  $emails=array();
  $sql=sprintf("select id from email where contact_id=%d",$contact_id);
  $res = $db->query($sql);  
  while($row=$res->fetchRow()){

    $emails[$row['id']]=get_email_data($row['id']);
  }
  if($children){
  $sql=sprintf("select email.id as id  from email left join contact_relations on (contact_id=child_id) where parent_id=%d",$contact_id);
  $res = $db->query($sql);  
  // print "$sql\n";
  while($row=$res->fetchRow()){
    //print "xxcaca";
    $emails[$row['id']]=get_email_data($row['id']);
  }
  }
  return $emails;
}

function get_children_id($contact_id){
 $db =& MDB2::singleton();
 $child_id=array();
 $sql=sprintf("select child_id from contact_relations where parent_id=%d",$contact_id);
  $res = $db->query($sql);  
  while($row=$res->fetchRow()){
    $child_id[]=$row['child_id'];
  }
  return $child_id;

}



function edit_contact($operation,$tipo,$contact_id,$date_index='',$value='',$value2=false,$value3=false,$history=true){

 $db =& MDB2::singleton();
 switch($tipo){
 case('main_address'):
 case('shop_address'):

   switch($operation){
   case('update'):

     update_address($value2,$value,$date_index);
     break;
   case('add'):
     if(!$value)
       return false;
     $address_id=insert_address($value);
     // print "*****  $address_id  $contact_id***";
     associate_address($address_id,$contact_id,1,'',$date_index,$history);
     return $address_id;
     break;
    case('set_principal'):
      //$value2 means the kind of history (update or not)
      set_principal_address($contact_id,1,$value,$date_index,$history,$value2);
      break;
   case('associate'):
     associate_address($value,$contact_id,1,'',$date_index,$history);
     break;

   }
   break;
 case('bill_address'):
   switch($operation){
   case('update'):
     update_address($value2,$value,$date_index);
     break;
   case('add'):
     if(!$value)
       return false;
     $address_id=insert_address($value);
     //print "*****  $address_id  ***";
     associate_address($address_id,$contact_id,2,'',$date_index,$history);
     return $address_id;
     break;
    case('set_principal'):
      //$value2 means the kind of history (update or not)
      set_principal_address($contact_id,2,$value,$date_index,$history,$value2);
      break;
 case('associate'):
     associate_address($value,$contact_id,2,'',$date_index,$history);
     break;
   }
   break;
 case('del_address'):
    switch($operation){
    case('associate'):
      // print "*****  $address_id  $contact_id***";
      associate_address($value,$contact_id,3,'',$date_index,$history);
      break;
      
    case('update'):
      update_address($value2,$value,$date_index);
      break;
    case('add'):
      if(!$value)
	return false;
      $address_id=insert_address($value);
      // print "*****  $address_id  $contact_id***";
      associate_address($address_id,$contact_id,3,'',$date_index,$history);
      return $address_id;
      break;
    case('set_principal'):
      //$value2 means the kind of history (update or not)
      set_principal_address($contact_id,3,$value,$date_index,$history,$value2);
      break;
      //case('associate'):
      // associate_address($address_id,$contact_id,3,'',$date_index,$history);
      //break;
    }
    break;
  case('email'):
    switch($operation){
    case('update'):
      update_email($value2,$value,$date_index);
      break;
    case('add'):
      if(!$value)
	return false;
      $email_id=insert_email($value,$contact_id,$date_index,$history);
      return $email_id;
      break;
    case('set_principal'):
      set_principal_telecom($contact_id,'email',$value,$date_index,$history);
      break;
    }
    break;
 case('tel'):
    switch($operation){
    case('associate'):
      	if(!$value)
	  return false;
	associate_telecom($value,$contact_id,$value2,$value3,$date_index,$history);
       break;
  case('update'):
      update_telecom($value2,$value,$date_index);
      break;
    case('add'):
      if(!$value)
	return false;
      $telecom_id=insert_telecom($value);
      // associate_telecom($telecom_id,$contact_id,1,,$date_index);
      return $telecom_id;
      break;
    case('set_principal'):
      set_principal_telecom($contact_id,'tel',$value,$date_index,$history);
      break;
    }
    break;
 case('fax'):
   switch($operation){
   case('associate'):
	if(!$value)
	  return false;
	associate_telecom($value,$contact_id,$value2,$value3,$date_index,$history);
	break;
   case('update'):
      update_telecom($value2,$value,$date_index);
      break;
   case('add'):
     if(!$value)
       return false;
      $telecom_id=insert_telecom($value);
      //      associate_telecom($telecom_id,$contact_id,2,,$date_index);
      return $telecom_id;
      break;
   case('set_principal'):
     //     set_principal_telecom('contact',$contact_id,'fax',$value,$history);
      break;
   }
   break;
 case('child'):

   switch($operation){
   case('update'):
     $child_id=$contact_id;
     $child_name=$value['name'];
     $child_name_data=guess_name($child_name);
     $name=display_person_name($child_name_data);
      $file_as=file_as($child_name_data,'person');

     if($file_as=='')
       $file_as=$name;


     $old_name=get_name($child_id);

     $sql=sprintf("update contact set name=%s , file_as=%s where id=%d" ,prepare_mysql($name),prepare_mysql($file_as),$child_id);
     //$db->exec($sql);
      mysql_query($sql);

     update_name($child_id,$child_name_data);

     
     $sql=sprintf("insert into history (tipo,sujeto,sujeto_id,objeto,objeto_id,date) values ('UPD','Contact',%d,'Name',NULL,%s)",$child_id,$date_index);
     //     print "$sql\n";
     mysql_query($sql);
     $history_id=mysql_insert_id();

     $old_name=

     $sql=sprintf("insert into history_item (history_id,columna,old_value,new_value) values (%d,'Contact Name',%s,%s)",$history_id,prepare_mysql($old_name),prepare_mysql($name));
     mysql_query($sql);

     break;
   case('add'):
     if(!$value)
       return false;
     
     $child_name=$value['name'];
     $date_index=$value['date'];

     //print_r($value);

     $child_id=insert_contact($child_name,'person',1,0,$date_index,'','','',true,true);
     
     if($history){
     $sql=sprintf("insert into history (tipo,sujeto,sujeto_id,objeto,objeto_id,date) values ('NEW','Contact',%d,'Contact',%d,%s)",$contact_id,$child_id,$date_index);
     //     print "$sql\n";
     mysql_query($sql);
     $history_id=mysql_insert_id();
     $sql=sprintf("insert into history_item (history_id,columna,old_value,new_value) values (%d,'Contact',NULL,%s)",$history_id,prepare_mysql($child_name));
     }
     set_principal_child($contact_id,$child_id,$date_index,$history);

     

     //print "$sql\n";

     //  print "********** $child_id  $child_name *******\n";
     set_contact_relation($contact_id,$child_id);
     return $child_id;
     break;
   case('set_principal'):
     set_principal_child($contact_id,$value,$date_index,$history);
     break;  

   }   
   break;
 case('mob'):
   switch($operation){
   case('associate'):
      if(!$value)
       return false;
      associate_telecom($value,$contact_id,$value2,$value3,$date_index,$history);
       break;
   case('update'):
     update_telecom($value2,$value,$date_index);
     break;
 
   case('set_principal'):
     set_principal_telecom($contact_id,'tel',$value,$date_index,$history);
     break;  

  case('add'):
     if(!$value)
       return false;
     
     $telecom_id=insert_telecom($value);
     //  associate_telecom($telecom_id,$contact_id,3,$date_index);
     return $telecom_id;
	break;
   }   
 }
 
}

function change_tipo_contact($contact_id,$tipo,$date_index,$history=false){
  $sql=sprintf("update contact set tipo=%d where id=%d",$tipo,$contact_id);
  mysql_query($sql);

  if($history){
    $customer_id=get_customer_from_contact($contact_id);
    if($customer_id>0){
      $sql=sprintf("insert into history (tipo,sujeto,sujeto_id,objeto,objeto_id,date) values ('P2C','Customer',%d,NULL,NULL,%s)",$customer_id,prepare_mysql_date($date_index));
      mysql_query($sql);
      //print $sql;
    }
  }



  //print "$sql\n";
  //exit;
}

function update_contact_name($contact_id,$name,$old_name,$date_index,$history){
 $db =& MDB2::singleton();

 // first check if it is a person o a company;

 $contact_data= get_contact_data($contact_id,false);
 
 $tipo=$contact_data['tipo'];
 if($tipo==0){// COmpany
   $name=mb_ucwords($name);
   $file_as=file_as($name,'company');
   if($file_as=='')
     $file_as=$name;
   $sql=sprintf("update contact set name=%s , file_as=%s where id=%d",prepare_mysql($name),prepare_mysql($file_as),$contact_id);
   mysql_query($sql);



   if(is_numeric($customer_id= get_customer_from_contact($contact_id))){
      $sql=sprintf("update customer set name=%s , file_as=%s where id=%d",prepare_mysql($name),prepare_mysql($file_as),$customer_id);
      mysql_query($sql);
   }

   



 }else{// Person

     $contact_name=$name;
     $contact_name_data=guess_name($contact_name);
     $name=display_person_name($contact_name_data);
     $file_as=file_as($contact_name_data,'person');
     if($file_as=='')
       $file_as=$name;

     $sql=sprintf("update contact set name=%s , file_as=%s where id=%d" ,prepare_mysql($contact_name),prepare_mysql($file_as),$contact_id);
     //$db->exec($sql);
      mysql_query($sql);

     update_name($contact_id,$contact_name_data);
     
     if(is_numeric($customer_id= get_customer_from_contact($contact_id))){
       $sql=sprintf("update customer set name=%s , file_as=%s where id=%d",prepare_mysql($contact_name),prepare_mysql($file_as),$customer_id);
       mysql_query($sql);
     }
       


 }
 if($history){
  $sql=sprintf("insert into history (tipo,sujeto,sujeto_id,objeto,objeto_id,date) values ('UPD','Contact',%d,'Name',NULL,%s)",$contact_id,$date_index);
     //     print "$sql\n";
     mysql_query($sql);
     $history_id=mysql_insert_id();
     $sql=sprintf("insert into history_item (history_id,columna,old_value,new_value) values (%d,'Contact Name',%s,%s)",$history_id,prepare_mysql($old_name),prepare_mysql($name));

     mysql_query($sql);

 }

}

function insert_contact($name,$tipo,$has_parent,$has_child,$date,$extra_id1='',$extra_id2='',$main_address='',$history=true,$safe_stats_date=true){

  // print " $date ****";


  $extra_id1=preg_replace('/\s*|\,|\./','',$extra_id1);
  $extra_id2=preg_replace('/\s*|\,|\./','',$extra_id2);

  if($name=='')
    return;

  $db =& MDB2::singleton();



  switch($tipo){
  case('company'):
    $name=mb_ucwords($name);
    $file_as=file_as($name,'company');
    if($file_as=='')
      $file_as=$name;
    $sql=sprintf("insert into contact (has_child,has_parent,name,file_as,tipo, extra_id1,extra_id2,main_address) values (%d,%d,'%s','%s',0,%s,%s,%s)",$has_child,$has_parent,addslashes($name),addslashes($file_as),prepare_mysql($extra_id1),prepare_mysql($extra_id2),prepare_mysql($main_address));

    //  print "$sql\n";
    // $db->exec($sql);
    // $contact_id = $db->lastInsertID();
    mysql_query($sql);
    $contact_id=mysql_insert_id();


    break;
  case('person'):
    
    $name_data=guess_name($name);
    $file_as=file_as($name_data,'person');
    $name=display_person_name($name_data);
    if($file_as=='')
      $file_as=$name;
    
    $sql=sprintf("insert into contact (has_child,has_parent,name,file_as,tipo,genero,date_creation,date_updated,extra_id1,extra_id2) values (%d,%d,'%s','%s',%d,%s,%s,%s,%s,%s)",$has_child,$has_parent,addslashes($name),addslashes($file_as),1,$name_data['genero'],$date,$date,prepare_mysql($extra_id1),prepare_mysql($extra_id2),prepare_mysql($main_address));
   
    //   print "$sql\n";

	//$db->exec($sql);
	//$contact_id = $db->lastInsertID();
	mysql_query($sql);
	$contact_id=mysql_insert_id();
	$name_id=insert_name($name_data,$contact_id);
	$name=display_person_name($name_id);
	$sql=sprintf("update contact set name='%s' where id=contact_id",addslashes($name),$contact_id);
	//$db->exec($sql);
	mysql_query($sql);
	break; 
    
  }

  if($history){
    if(!$safe_stats_date)
      $date='NULL';


  $tipo=mb_ucwords($tipo);

  
  $sql=sprintf("insert into history (tipo,sujeto,sujeto_id,objeto,objeto_id,date) values ('NEW','Contacts',null,'%s',%d,%s)",$tipo,$contact_id,$date);
  // print "$sql\n";
  //$db->exec($sql);
  //$history_id=$db->lastInsertID();
   mysql_query($sql);
   $history_id=mysql_insert_id();

 

  $sql=sprintf("insert into history_item (history_id,columna,old_value,new_value) values (%d,'%s',NULL,%s)",$history_id,$tipo,prepare_mysql($name));
  //print "$sql\n";
   
   //$db->exec($sql);
 mysql_query($sql);

//exit("new contact");
  }

  return $contact_id;
}

function set_contact_relation($parent,$child){
  $db =& MDB2::singleton();
  $sql=sprintf("insert into contact_relations (child_id,parent_id) values (%d,%d)",$child,$parent);
  //$db->exec($sql);
  mysql_query($sql);
 $sql=sprintf("update contact  set has_child=1  where id=%d",$parent);
  //$db->exec($sql);
  mysql_query($sql);
  $sql=sprintf("update contact  set has_parent=1  where id=%d",$child);
  //$db->exec($sql);
  mysql_query($sql);

//  print "$sql\n";
  //exit;

}





function update_contact($contact_id,$new_values,$date_index){
 $db =& MDB2::singleton();
 
 //print_r($new_values);
  $changed=get_changes($contact_id,$new_values);

  //print_r($changed);
  // exit;
  $new_child=false;
  $child_update=false;

  //check if shop-del address are the same;
  $shop_updated=false;
  $new_shop_id=false;
  $result = array_diff($new_values['del_address'],$new_values['shop_address']);
  if(count($result)==0)
    $same_del_shop=true;
  else
    $same_del_shop=false;


  foreach($changed as $key=>$value){
    if($value[0]){
      $child_data=get_children_id($contact_id);
      $children=count($child_data);
      
      switch($key){

      case('name'):
	//	print $value[1]."\n".print $new_values['name']."\n";
	update_contact_name($contact_id,$new_values['name'],$value[1],$date_index,true);
	//	edit_contact('update','name',$value[1],$tmp);

	  break;
      case('child'):
	//print "child hola_";
	//print_r($new_values);
	//print "child hola-";
	
	if(!$new_values['child'])
	  break;
	

	$tmp['name']=display_person_name($new_values['child']);
	
	if($value[1]>0){
	  // update old contact
	  edit_contact('update','child',$value[1],$date_index,$tmp);
	  $child_update=true;
	}else{// create new contact
	  

	  $tmp['date']=$date_index;
	  $child_id=edit_contact('add','child',$contact_id,$date_index,$tmp);
	  $new_child=true;
	  //  print_r($tmp);
	  // print "********** $child_id  *******\n";
	}
	break;
      case('email'):

	if($new_child)
	  $history=false;
	else
	  $history=true;

	
	if($value[1]>0){
	  if($new_child)
	    $new_values['email']['contact_id']=$child_id;
	  $email_id=edit_contact('update','email',false,$date_index,$new_values['email'],$value[1],'',$history);
	  

	}else{


	  if($new_child){
	    //    print "$child_id -------------- \n";
	    //print_r($new_values['email']);
	    $email_id=edit_contact('add','email',$child_id,$date_index,$new_values['email'],'','',$history);
	  }else{
	    $email_id=edit_contact('add','email',$contact_id,$date_index,$new_values['email'],'','',$history);
	  }

	  edit_contact('set_principal','email',$contact_id,$date_index,$email_id,'','',true);

	}
	break;
      case('mob'):
	//	print_r($new_values['email']);
	
	if($new_child)
	  $history=false;
	else
	  $history=true;

	if($value[1]>0){
	  $mob_id=edit_contact('update','mob',false,$date_index,$new_values['mob'],$value[1],'',$history);
	  
	}else{
	  if($new_child){
	    //print_r($new_values['email']);
	    $mob_id=edit_contact('add','mob',$child_id,$date_index,$new_values['mob'],'','',$history);
	  }else{
	    $mob_id=edit_contact('add','mob',$contact_id,$date_index,$new_values['mob'],'','',$history);
	  }
	  edit_contact('set_principal','mob',$contact_id,$date_index,$mob_id,'','',true);

	}
	break;	


      case('tel'):


	  $history=true;


	if($value[1]>0){
	  $tel_id=edit_contact('update','tel',false,$date_index,$new_values['tel'],$value[1],'',$history);
	}else{
	  $tel_id=edit_contact('add','tel',$contact_id,$date_index,$new_values['tel'],'','',$history);
	   edit_contact('set_principal','tel',$contact_id,$date_index,$tel_id,'','',true);
	}
	
	
	break;
	
      case('fax'):
	$history=true;
	if($value[1]>0){
	  $fax_id=edit_contact('update','fax',false,$date_index,$new_values['fax'],$value[1],'',$history);
	}else{
	  $fax_id=edit_contact('add','fax',$contact_id,$date_index,$new_values['fax'],'','',$history);

	}
	
	
	break;
      case('shop_address'):
	
	if($value['id']>0)
	  $shop_address_id=$value['id'];
	if($value['insert']){
	  $shop_address_id=edit_contact('add','shop_address',$contact_id,$date_index,$new_values['shop_address']);
	  $shop_updated=true;;
	  edit_contact('set_principal','shop_address',$contact_id,$date_index,$shop_address_id,true);
	  
	  

	}elseif($value['update']){
	  edit_contact('update','shop_address',false,$date_index,$new_values['shop_address'],$value['id']);
	
	  $shop_updated=true;;
	  $princial_address=get_principal_address('shop_address',$contact_id);
	  if($princial_address!=$value['id']){
	    edit_contact('set_principal','shop_address',$contact_id,$date_index,$value['id'],true);
	  }
	  
	}

	if($value['associate']){

	  edit_contact('associate','shop_address',$contact_id,$date_index,$value['id'],false,false,false);
	  edit_contact('set_principal','shop_address',$contact_id,$date_index,$value['id'],true);
	}

	$return_data[$key]=$shop_address_id;

	break;	
      case('bill_address'):

	if($value['id']>0)
	  $bill_address_id=$value['id'];

	if($value['insert']){
	  $bill_address_id=edit_contact('add','bill_address',$contact_id,$date_index,$new_values['bill_address']);
	  // $return_data[$key]=$bill_address_id;
	  edit_contact('set_principal','bill_address',$contact_id,$date_index,$bill_address_id,true);
	  
	}elseif($value['update']){
	  edit_contact('update','bill_address',false,$date_index,$new_values['bill_address'],$value['id']);
	  // $return_data[$key]=$value['id'];
	  	$princial_address=get_principal_address('bill_address',$contact_id);
	if($princial_address!=$value['id'])
	  edit_contact('set_principal','bill_address',$contact_id,$date_index,$value['id'],true);
	}

	if($value['associate']){
	  edit_contact('associate','bill_address',$contact_id,$date_index,$value['id'],false,false,false);
	  edit_contact('set_principal','bill_address',$contact_id,$date_index,$value['id'],true);
	}

	$return_data[$key]=$bill_address_id;
	break;
	
      case('del_address'):

	if($value['id']>0)
	  $del_address_id=$value['id'];



	if($value['insert']){

	  if($same_del_shop  and $shop_updated)
	    $del_address_id=$shop_address_id;
	  else
	    $del_address_id=edit_contact('add','del_address',$contact_id,$date_index,$new_values['del_address']);
	  

	  //  $return_data[$key]=$del_address_id;
	  edit_contact('set_principal','del_address',$contact_id,$date_index,$del_address_id,true);
	  
	}elseif($value['update']){


	  if(!($same_del_shop  and $shop_updated))
	    edit_contact('update','del_address',false,$date_index,$new_values['del_address'],$value['id']);
	  
	  $princial_address=get_principal_address('del_address',$contact_id);
	  if($princial_address!=$value['id'])
	    edit_contact('set_principal','del_address',$contact_id,$date_index,$value['id'],true);
	}

	if($value['associate']){
	  edit_contact('associate','del_address',$contact_id,$date_index,$value['id'],false,false,false);
	  edit_contact('set_principal','del_address',$contact_id,$date_index,$value['id'],true);
	}


	$return_data[$key]=$del_address_id;
	break;
	
	
      default:
	exit("changed\n");
      }



    }else{
      if($key=='del_address')
	$return_data[$key]=$value['id'];
      if($key=='bill_address')
	$return_data[$key]=$value['id'];
      if($key=='shop_address')
	$return_data[$key]=$value['id'];
      

    }


  }


  //  exit;
  return $return_data;

}  





function found_child($tipo,$contact_id,$val1,$val2=false){
  
  switch($tipo){
  case('etel'):
    $child_b=0;$child_a=0;
    $sql=sprintf("select child_id  from email  left join contact_relations on (contact_id=child_id)      where parent_id=%d and email='%s' ",$contact_id,$val1);
    $res = $db->query($sql);  
    if($row=$res->fetchRow() )
      $child_a=$row['child_id'];
    $sql=sprintf("select child_id  from telecom left join telecom2contact on (telecom.id=telecom_id) left join contact_relations on (contact_id=child_id)      where tipo=3 and  parent_id=%d and number='%s' ",$contact_id,$val2);
    $res = $db->query($sql);  
    if($row=$res->fetchRow() )
      $child_b=$row['child_id'];
    if($child_a>0 and $child_a==$child_b)
      return $child_a;
    else
      return false;
    break;
  case('name'):

    $sql=sprintf("select child_id  from contact  left join contact_relations on (contact_id=child_id) where parent_id=%d and name='%s' ",$contact_id,$val1);
    if($row=$res->fetchRow() )
      return $row['child_id'];
    else
      return false;
    break;

  }
}


function set_principal_child($recipient_id,$child_id,$date_index,$history){
  $db =& MDB2::singleton();
  

  $sql=sprintf("update contact set main_contact=%d where id=%d",$child_id,$recipient_id);
  mysql_query($sql);
  // print "$sql\n";
  if($history){
    $history_tipo='Change Main Contact';
    $col="main_contact";
    $col2="Main Contact";
    $contact_data=get_contact_data($recipient_id);
    $old_data=$contact_data[$col];

    if(is_numeric($customer_id= get_customer_from_contact($recipient_id))){
      $recipient_id=$customer_id;
      $sujeto='Customer';
    }else
      $sujeto='Contact';
    
    

     $sql=sprintf("insert into history (tipo,sujeto,sujeto_id,objeto,objeto_id,date) values (%s,%s,%d,%s,%d,%s)",'CHG',prepare_mysql($sujeto),$recipient_id,prepare_mysql($history_tipo),$child_id,prepare_mysql_date($date_index));

    mysql_query($sql);
    $history_id=mysql_insert_id();
    $sql=sprintf("insert into history_item (history_id,columna,old_value,new_value) values (%d,%s,%s,%s)",$history_id,prepare_mysql($col2),prepare_mysql($old_data),$child_id);
    mysql_query($sql);


  }


}







?>