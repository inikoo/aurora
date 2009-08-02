<?php

// function is_mobile($tel,$country_id='',$strict=0){

//   if($country_id==30){// UK
//     if($strict){
//       if(preg_match('/^(\+44\s?7\d{3}|\(?07\d{3}\)?)\s?\d{3}\s?\d{3}$/',$tel))
// 	return 1;
//       else
// 	return 0;
//     }
//   }
//   return 2;
  
// }



function guess_tel($raw_tel,$country_id='',$city_id=''){
  if($raw_tel=='')
    return false;
  $is_mobile=2; // 2 unknown 1 yes 0 no
  $icode='';
  $ncode='';
  $number='';
  $ext='';
  // fisrt try to see if it has an extension;
  $tel_ext=preg_split('/ext|#/i',$raw_tel);

  if(count($tel_ext)==2){
    $ext=preg_replace('/[^0-9]/','',$tel_ext[1]);
  }   
  
  $number=$tel_ext[0];
  // if (*) founf the numbers at the left could be  icodes and the number iside could be the ncode ane the number ate the rigth the numbner
  
 //  if(preg_match('/\(.*\)/i',$number,$possible_ncode)){
//     $possible_ncode=preg_replace('/\(|\)/','', $possible_ncode[0]);
//     if(preg_match('/^0*$/', $possible_ncode)){
//       // forget it
//     }else{
//       $ncode=$possible_ncode;
//     }
//     $number_parts=preg_split('/\(.*\)/i',$number);
//     $icode=$number_parts[0];
//     $number=$number_parts[1];
    
//   }
  
  

//   // remove the internatinal code if found
//   if($country_code=get_icode($country_id))
//     $icode_match="/^\+?$country_code\s*/";
//   else 
//     $icode_match='/^\+\d{1,3}\s+/';

//   if(preg_match($icode_match,$number,$a_ncode)){
//     $icode= $a_ncode[0];
//     $number=str_replace($icode,'',$number);
//   }
//   //$number=preg_replace('/\[\d*\]/','',$number);

  $icode=preg_replace('/[^0-9]/','',$icode);
  $ncode=preg_replace('/[^0-9]/','',$ncode);
  $number=preg_replace('/[^0-9]/','',$number);
  $ext=preg_replace('/[^0-9]/','',$ext);
  
  if($icode=get_icode($country_id)){
    $regex_icode="/^0{0,2}$icode/";
    //    print "$regex_icode  xxxxxxxxxxxxx\n";
    $number=preg_replace($regex_icode,'',$number);
  }
  

  // country expcific

  switch($country_id){
     
  case(30)://UK
    if(preg_match('/^0845/',$number)){
      $icode='';
      $ncode='0845';
      $number=preg_replace('/^0845/','',$number);
    }
    $number=preg_replace('/^0/','',$number);
    if(preg_match('/^7/',$number))
      $is_mobile=1;
    else
      $is_mobile=0;
    break;
  case(75)://Ireland
    if(preg_match('/^0?8(2|3|5|6|7|8|9)/',$number))
      $is_mobile=1;
    else
      $is_mobile=0;
    break;
  case(47)://Spain
  case(165)://France
    if(preg_match('/^0?6/',$number))
      $is_mobile=1;
    else
      $is_mobile=0;
    break;
  }
  
  
  $number=preg_replace('/[^\d]/','',$number);
  $telecom_data=array('icode'=>$icode,'ncode'=>$ncode,'number'=>$number,'ext'=>$ext,'is_mobile'=>$is_mobile);
  //print "$raw_tel\n";
   //print_r($telecom_data);
   // if(!($country_id==30 ))
   //  exit;
  return $telecom_data;

}



function get_icode($country_id){
  $db =& MDB2::singleton();
  $sql=sprintf("select tel_code from list_country  where id=%d",$country_id);
  $res=mysql_query($sql); 
  if ($row=$res->fetchRow()){
    if($row['tel_code']!='')
      return $row['tel_code'];
  }else
    return '';
}



function display_tel($telecom_id){
  $db =& MDB2::singleton();
  $sql=sprintf("select id,icode,ncode,number,ext from telecom where id=%d",$telecom_id);
  $res=mysql_query($sql); 
  if ($row=$res->fetchRow()){
    $tmp=($row['icode']!=''?'+'.$row['icode'].' ':'').($row['ncode']!=''?$row['ncode'].' ':'').format_tel($row['number']).($row['ext']!=''?_(' ext').' '.$row['ext']:'');
    return $tmp;
  
  }else
    return false;
}

function get_tel_data($telecom_id){
  $db =& MDB2::singleton();
  $sql=sprintf("select icode,ncode,number,ext from telecom where id=%d",$telecom_id);
  $res=mysql_query($sql); 
  if ($row=$res->fetchRow()){
    return $row;
  }else
    return false;
}

function get_tel_metadata($telecom_id){
  $db =& MDB2::singleton();
  global $_contact_tipo;
  global $_tel_tipo;
  $sql=sprintf("select contact_id,contact.tipo as c_tipo,telecom2contact.tipo as tel_tipo  from telecom2contact left join contact on (contact_id=contact.id) where telecom_id=%d",$telecom_id);
  //  print "$sql\n";
  $res=mysql_query($sql); 
  $metadata=array();
  while ($row=$res->fetchRow()){
    
    $metadata[]=array(
		    'contact_tipo'=>$_contact_tipo[$row['c_tipo']],
		    'tel_tipo'=>$_tel_tipo[$row['tel_tipo']],
		    'contact_id'=>$row['contact_id'] ,
		    'contact_tipo_id'=>$row['c_tipo'],
		    'tel_tipo_id'=>$row['tel_tipo'],
		    );
    return $metadata;
      

  }
   return $metadata;
}




function insert_telecom($telecom_data){
  $db =& MDB2::singleton();
  if($telecom_data['number']=='')
    return 0;
  //  $number=preg_replace('/[^\d]/','',$telecom_data['number']);
  //$icode=($telecom_data['icode']!=''?"'".preg_replace('/[^\d]/','',$telecom_data['icode'])."'":'NULL');
  //$ncode=($telecom_data['ncode']!=''?"'".preg_replace('/[^\d]/','',$telecom_data['ncode'])."'":'NULL');
  //$ext=($telecom_data['ext']!=''?"'".preg_replace('/[^\d]/','',$telecom_data['ext'])."'":'NULL');


  $sql=sprintf("insert into telecom  (icode,ncode,number,ext) values (%s,%s,%s,%s)"
	       ,prepare_mysql($telecom_data['icode'])
	       ,prepare_mysql($telecom_data['ncode'])
	       ,prepare_mysql($telecom_data['number'])
	       ,prepare_mysql($telecom_data['ext']));
  //mysql_query($sql);
  //$telecom_id= $db->lastInsertID();
  
  mysql_query($sql);
  $telecom_id=mysql_insert_id();
  return $telecom_id;

}

function update_telecom($telecom_id,$telecom_data,$date_index=''){
   $db =& MDB2::singleton();
  if($telecom_data['number']=='')
    return ;
  $number=$telecom_data['number'];
  $icode=$telecom_data['icode'];
  $ncode=$telecom_data['ncode'];
  $ext=$telecom_data['ext'];
  
  // get old values
  $old_values=get_tel_data($telecom_id);
  $array_metadata=get_tel_metadata($telecom_id);
  
  //print_r($telecom_data);
  //print_r($old_values);
  //print_r($array_metadata);

  $keys=array('number','icode','ncode','ext');
  $update_sql='';
  $values=array();
  foreach($keys as $key){
    //print $old_values[$key]."z".$telecom_data[$key]."zz\n";
    if(strcmp($old_values[$key],$telecom_data[$key])){

      $values[]=array('old'=>$old_values[$key],'new'=>$telecom_data[$key]);
      $array_history_sql[]="insert into history_item (history_id,columna,old_value,new_value) values (%d,'$key',%s,%s)";
      $update_sql.=" $key=".prepare_mysql($telecom_data[$key]);
	
    }
  }
  //print_r($values);

  if(count($values)>0){
    $sql=sprintf("update telecom  set %s where id=%d",$update_sql,$telecom_id);
    //print "$sql\n";
    //mysql_query($sql);
    mysql_query($sql);
    foreach($array_metadata as $metadata){
      
      $sql=sprintf("insert into history (tipo,sujeto,sujeto_id,objeto,date) values ('UPD','%s',%d,'%s',%s)",$metadata['contact_tipo'],$metadata['contact_id'],$metadata['tel_tipo'],prepare_mysql_date($date_index));
      // print "$sql\n";
       //mysql_query($sql);
       //$history_id=$db->lastInsertID();
      mysql_query($sql);
      $history_id=mysql_insert_id();
      



      if($metadata['tel_tipo']<4){
	foreach($array_history_sql as $key=>$history_sql){
	  $sql=sprintf($history_sql,$history_id,prepare_mysql($values[$key]['old']),prepare_mysql($values[$key]['new']));
	  //  print "$sql\n";
	   mysql_query($sql);
	  //mysql_query($sql);
	}
      }
    }
  }
  //exit("telecom update\n");
}    




function associate_telecom($telecom_id,$contact_id,$tipo,$description='',$date_index='',$history=false){
  global $_contact_tipo;
  global $_tel_tipo;

  $db =& MDB2::singleton();

  $sql=sprintf("insert into telecom2contact  (telecom_id,contact_id,tipo,description) values (%d,%d,%d,%s)",$telecom_id,$contact_id,$tipo,prepare_mysql($description));
  // mysql_query($sql);
  // print "$sql\n";
    mysql_query($sql);

  //$telecom_id= $db->lastInsertID();
    if($history){
  
  $contact_data=get_contact_data($contact_id);
  $contact_tipo=$_contact_tipo[$contact_data['tipo']];
  
  $sql=sprintf("insert into history (tipo,sujeto,sujeto_id,objeto,objeto_id,date) values (1,'%s',%d,'%s',%d,%s)",$contact_tipo,$contact_id,$_tel_tipo[$tipo],$telecom_id,prepare_mysql_date($date_index));
  //print "$sql\n";
  //mysql_query($sql);
  //$history_id=$db->lastInsertID();
  mysql_query($sql);
  $history_id=mysql_insert_id();


  $telephone=display_tel($telecom_id);
  $sql=sprintf("insert into history_item (history_id,columna,old_value,new_value) values (%d,'%s',NULL,%s)",$history_id,$_tel_tipo[$tipo],prepare_mysql($telephone));
  //print "$sql\n";
  //mysql_query($sql);
  mysql_query($sql);

  //exit("adding tel\n");
  // add to history
    }
 return $telecom_id;
  
}

function set_principal_telecom($recipient_id,$tipo_telecom,$telecom_id,$date_index,$history){
  $db =& MDB2::singleton();
  
  switch($tipo_telecom){
  case('tel'):
    $sql=sprintf("update contact set main_tel=%d where id=%d",$telecom_id,$recipient_id);
    mysql_query($sql);
    $history_tipo='Change Main Telephone';
    $col="main_tel";
    $col2="Main Telephone";
    break;
  case('email'):
    $sql=sprintf("update contact set main_email=%d where id=%d",$telecom_id,$recipient_id);
    mysql_query($sql);
    $history_tipo='Change Main Email';
    $col="main_email";
    $col2="Main Email";
    break;
  }


  if($history){

    $contact_data=get_contact_data($recipient_id);
    $old_data=$contact_data[$col];

//     if(is_numeric($customer_id= get_customer_from_contact($recipient_id))){
//       $recipient_id=$customer_id;
//       $sujeto='Customer';
//     }else
//       $sujeto='Contact';
    
    $sujeto='Contact';

     $sql=sprintf("insert into history (tipo,sujeto,sujeto_id,objeto,objeto_id,date) values (%s,%s,%d,%s,%d,%s)",'CHG',prepare_mysql($sujeto),$recipient_id,prepare_mysql($history_tipo),$telecom_id,prepare_mysql_date($date_index));

    mysql_query($sql);
    $history_id=mysql_insert_id();
    $sql=sprintf("insert into history_item (history_id,columna,old_value,new_value) values (%d,%s,%s,%s)",$history_id,prepare_mysql($col2),prepare_mysql($old_data),$telecom_id);
    mysql_query($sql);


  }


}



function format_tel($number){

  return trim(strrev(chunk_split(strrev($number),4," ")));


}

?>