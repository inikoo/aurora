<?php
function savecontact(){
  
  $db =& MDB2::singleton();
  global $debug;

  if(!isset($_SESSION['new_contact']['tipo']))
    break;
     



  // print_r($_SESSION['new_contact']);
  $tipo=$_SESSION['new_contact']['tipo'];
   
   
   


  $name=addslashes($_SESSION['new_contact']['name'][4]);
  $order=addslashes($_SESSION['new_contact']['name'][5]);

  $sql=sprintf("insert into contact (name,order_name,tipo,date_creation,date_updated) values ('%s','%s',%d,NOW(),NOW())",$name,$order,$tipo);
  if($debug)print "xx $sql\n";
  mysql_query($sql);
  $contact_id =  mysql_insert_id();
  

  if($tipo>0){
    $aname=$_SESSION['new_contact']['name'];
    $prefix=($aname[0]!=''?'"'.addslashes($aname[0]).'"':'null');
    $first=($aname[1]!=''?'"'.addslashes($aname[1]).'"':'null');
    $last=($aname[2]!=''?'"'.addslashes($aname[2]).'"':'null');
    $suffix=($aname[3]!=''?'"'.addslashes($aname[3]).'"':'null');
    $middle=($aname[6]!=''?'"'.addslashes($aname[6]).'"':'null');
    $alias=($aname[7]!=''?'"'.addslashes($aname[7]).'"':'null');
    $sql=sprintf("insert into name (contact_id,prefix,first,last,suffix,middle,alias) values (%d,%s,%s,%s,%s,%s,%s)",$contact_id,$prefix,$first,$last,$suffix,$middle,$alias);
    mysql_query($sql);
    if($debug)print "$sql\n";
  }

  $main_name=$name;
  if(isset($_SESSION['new_contact']['contact'])){
    $tipo=$_SESSION['new_contact']['contact'][0];
    $name=addslashes($_SESSION['new_contact']['contact'][5]);
    $order=addslashes($_SESSION['new_contact']['contact'][6]);
    $sql=sprintf("insert into contact (name,order_name,tipo,date_creation,date_updated) values ('%s','%s',%d,NOW(),NOW())",$name,$order,$tipo);
    mysql_query($sql);
    if($debug)print "x $sql\n";
    $contactincompany_id =  mysql_insert_id();
    $aname=$_SESSION['new_contact']['contact'];

    $prefix=($aname[1]!=''?'"'.addslashes($aname[1]).'"':'null');
    $first=($aname[2]!=''?'"'.addslashes($aname[2]).'"':'null');
    $last=($aname[3]!=''?'"'.addslashes($aname[3]).'"':'null');
    $suffix=($aname[4]!=''?'"'.addslashes($aname[4]).'"':'null');
    $middle=($aname[7]!=''?'"'.addslashes($aname[7]).'"':'null');
    $alias=($aname[8]!=''?'"'.addslashes($aname[8]).'"':'null');
    $sql=sprintf("insert into name (contact_id,prefix,first,last,suffix,middle,alias) values (%d,%s,%s,%s,%s,%s,%s)",$contactincompany_id,$prefix,$first,$last,$suffix,$middle,$alias);
    //     print $sql;
    if($debug)print "$sql\n";

    $contact_name=$name;
    $sql=sprintf("insert into contact_relations (child_id,parent_id) values (%d,%d)",$contactincompany_id,$contact_id);
    if($debug)print "y $sql\n";

    mysql_query($sql);
     
  }



  if(isset($_SESSION['new_contact']['email']))
    foreach($_SESSION['new_contact']['email'] as $aemail){
       
      if($aemail[2]=='')
	continue;
       
      $tipo=$aemail[0];
      $name=addslashes($aemail[1]);
      $email=addslashes($aemail[2]);
       
      if(($tipo==0 or $tipo==1) and (isset($contactincompany_id))){
	if($name=='')
	  $name=$contact_name;
	$sql=sprintf("insert into email (contact,email,tipo,contact_id) values ('%s','%s',%d,%d)",$name,$email,$tipo,$contactincompany_id);
	mysql_query($sql);
      }elseif($tipo==2){
	if($name=='')
	  $name=$main_name;
	$sql=sprintf("insert into email (contact,email,tipo,contact_id) values ('%s','%s',%d,%d)",$name,$email,$tipo,$contact_id);
      }
       
      //print $sql;
    }
  if(isset($_SESSION['new_contact']['tel']))
    foreach($_SESSION['new_contact']['tel'] as $atel){
      if($atel[3]==''  )
	continue;
       
       
      $tipotel=$atel[0];
      $name=($atel[1]!=''?'"'.addslashes($atel[1]).'"':'null');
      $code=(is_numeric($atel[2])?$atel[2]:'null');
      $number=(is_numeric($atel[3])?$atel[3]:'null');
      $ext=(is_numeric($atel[4])?$atel[4]:'null');
       
      $sql=sprintf("insert into telecom (name,code,number,ext,tipo,contact_id) values (%s,%s,%s,%s,%d,%d)",$name,$code,$number,$ext,$tipotel,$contact_id);
      mysql_query($sql);
      if($tipotel==1 and isset($contactincompany_id))
	{
   
	  $sql=sprintf("insert into telecom (name,code,number,ext,tipo,contact_id) values (%s,%s,%s,%s,%d,%d)",$name,$code,$number,$ext,$tipotel,$contactincompany_id);
	  mysql_query($sql);
	  // print "$sql\n";
	   
	}

    }
  if(isset($_SESSION['new_contact']['www']))
    foreach($_SESSION['new_contact']['www'] as $awww){
	 

      if($awww[1]=='')
	continue;
	 
      $title=($awww[0]!=''?"'".addslashes($awww[0]).'"':'null');
      $www=addslashes($awww[1]);
	 
      $sql=sprintf("insert into www (title,www,contact_id) values (%s,%s,%d)",$title,$www,$contact_id);
      mysql_query($sql);
    }
  if(isset($_SESSION['new_contact']['address']))
    foreach($_SESSION['new_contact']['address'] as $aadd){
       
       
      $tipo=$aadd[0];

      $pc=addslashes($aadd[6]);
      $address1=($aadd[1]!=''?'"'.addslashes(trim($aadd[1])).'"':'null');
      $address2=($aadd[2]!=''?'"'.addslashes($aadd[2]).'"':'null');
      $address3=($aadd[3]!=''?'"'.addslashes($aadd[3]).'"':'null');
      $town=($aadd[4]!=''?'"'.addslashes($aadd[4]).'"':'null');
      $subdistrict=($aadd[5]!=''?'"'.addslashes($aadd[5]).'"':'null');
      $postcode=($aadd[6]!=''?'"'.addslashes(str_replace(' ','',$aadd[6])).'"':'null');
      $country=($aadd[7]!=''?'"'.addslashes($aadd[7]).'"':'null');
      $country_id=$aadd[8];
      $full_address=($aadd[9]!=''?'"'.addslashes($aadd[9]).'"':'null');
      $principal=($aadd[10]==''?0:$aadd[10]);

  //    $full_address=($address1!='null'?preg_replace('/^.|.$/','',$address1)."\n":'').($address2!='null'?preg_replace('/^.|.$/','',$address2)."\n":'').($address3!='null'?preg_replace('/^.|.$/','',$address3)."\n":'').($town!='null'?preg_replace('/^.|.$/','',$town)."\n":'').($subdistrict!='null'?preg_replace('/^.|.$/','',$subdistrict)."\n":'').($postcode!='null'?$pc."\n":'').($country!='null'?preg_replace('/^.|.$/','',$country)."\n":'');


      $sql=sprintf("insert into address (principal,tipo,full_address,address1,address2,address3,town,subdistrict,postcode,country,country_id,contact_id) values (%d,%d,%s,%s,%s,%s,%s,%s,%s,%s,%d,%d)",
		   $principal,$tipo,$full_address,$address1,$address2,$address3,$town,$subdistrict,$postcode,$country,$country_id,$contact_id
		   );
     // if($debug)print "$sql\n";
      mysql_query($sql);

    }


  return $contact_id;

}



function get_matches($email,$mobile,$tel,$fax,$name,$contact,$a1,$a2,$a3,$town,$state,$postcode,$country){
  global $debug;
  $similar_email=checksimilar('email',$email);
  $similar_tel=checksimilar('tel',$tel);
  $similar_mobile=checksimilar('mobile',$mobile);
  $similar_fax=checksimilar('fax',$fax);
  $similar_name=checksimilar('name',$name);
  $similar_contact=checksimilar('name',$contact);
  $similar_address=checksimilar('address',$postcode,$contact,$town);
    
  
  $similar=array();
  foreach($similar_email as $i)
    $similar[]=$i;
  foreach($similar_tel as $i)
    $similar[]=$i;
  foreach($similar_fax as $i)
    $similar[]=$i;
  foreach($similar_mobile as $i)
    $similar[]=$i;
  foreach($similar_name as $i)
    $similar[]=$i;
  foreach($similar_contact as $i)
    $similar[]=$i;
  foreach($similar_address as $i)
    $similar[]=$i;

  
  $similar=array_unique($similar);
  
  

  $match=array();
  foreach($similar as $sim){
    $score=0;
    foreach($similar_address as $x){
      if($x==$sim){
	$score+=80;
	if($debug)print"s address\n";
	break;
      }
    }
    foreach($similar_tel as $x){
      if($x==$sim){
	$score+=67.5;
	if($debug)print"s tel\n";

	break;
      }
    }
    foreach($similar_fax as $x){
      if($x==$sim){
	$score+=67.5;
	if($debug)print"s fax\n";

	break;
      }
    }
    foreach($similar_mobile as $x){
      if($x==$sim){
	$score+=75;
	if($debug)print"s mob\n";

	break;
      }
    }
    foreach($similar_email as $x){
      if($x==$sim){
	$score+=99;
	if($debug)print"s email\n";

	break;
      }
    }
    foreach($similar_name as $x){
      if($x==$sim){
	$score+=41;
	if($debug)print"s name\n";

	break;
      }
    }
    foreach($similar_contact as $x){
      if($x==$sim){
	$score+=40;
	if($debug)print"s con\n";

	break;
      }
    }



    $match[]=array('contact'=>$sim,'score'=>$score);
  }  


  //   $match[]=array('contact'=>23,'score'=>.13);
  //   $match[]=array('contact'=>121,'score'=>.9);
  //   $match[]=array('contact'=>124,'score'=>.23);
  //   $match[]=array('contact'=>253,'score'=>.14);
  //   $match[]=array('contact'=>123,'score'=>.23);


  
  $s=array();
  foreach ($match as $key => $row) {
    //    $c[$key]  = $row['contact'];
    $s[$key] = $row['score'];
  }
  
  array_multisort($s, SORT_DESC, $match);
  
  

  return $match;

}


?>