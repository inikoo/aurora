<?php
function get_matches($email,$mobile,$tel,$fax,$name,$contact,$address_data,$extra_id1='',$extra_id2=''){
  global $debug;


  

  $debug=false;
  $similar_email=checksimilar('email',$email);
  $similar_tel=checksimilar('tel',$tel);
  $similar_mobile=checksimilar('mobile',$mobile);
  $similar_fax=checksimilar('fax',$fax);
  $similar_name=checksimilar('name',$name);
  $similar_contact=checksimilar('name',$contact);
  $similar_address=checksimilar('address',$address_data);
  
  $similar_id2=checksimilar('extra_id1',$extra_id1);
  $similar_id3=checksimilar('extra_id2',$extra_id2);

  // print_r($address_data);
  
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
  foreach($similar_id2 as $i)
    $similar[]=$i;
  foreach($similar_id3 as $i)
    $similar[]=$i;

  
  $similar=array_unique($similar);
  
  

  $match=array();


  // print_r($similar);

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
    foreach($similar_id3 as $x){
      if($x==$sim){
	$score+=99;
	if($debug)print"s id3\n";

	break;
      }
    }
    foreach($similar_id2 as $x){
      if($x==$sim){
	$score+=80;
	if($debug)print"s id2\n";

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
  
  
  // print_r($match);
  return $match;

}


function checksimilar($tipo,$value,$value1='',$value2=''){
  global $debug;
  switch($tipo){

  case('extra_id1'):
    if($value==''){
      $a=array();
      return $a;
    }
    $sql2="select contact.id as contact_id,parent_id  from contact left join contact_relations on (contact.id=child_id)  where  extra_id1='".addslashes($value)."' ";

    $result2 = mysql_query($sql2) or die('Query faileda3: ' . mysql_error());
    $matches=array();
    while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
     
      $contact_id=$row2['contact_id'];
      if(is_numeric($row2['parent_id']))
	$contact_id=$row2['parent_id'];
      $matches[]= $contact_id;
    }
    $result = array_unique($matches);
    return $result;
    
    break;
  
  case('extra_id2'):
    if($value==''){
      $a=array();
      return $a;
    }
    $sql2="select contact.id as contact_id,parent_id  from contact left join contact_relations on (contact.id=child_id)  where  extra_id2='".addslashes($value)."' ";

    $result2 = mysql_query($sql2) or die('Query faileda3: ' . mysql_error());
    $matches=array();
    while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
     
      $contact_id=$row2['contact_id'];
      if(is_numeric($row2['parent_id']))
	$contact_id=$row2['parent_id'];
      $matches[]= $contact_id;
    }
    $result = array_unique($matches);
    return $result;
    
    break;

  case('tel'):
    if($value==''){
      $a=array();
      return $a;
    }
    $value=preg_replace('/\s/','',$value);
    $sql2="select contact_id,parent_id  from telecom  left join telecom2contact as t2c on (telecom_id=telecom.id)  left join contact_relations on (contact_id=child_id)   where tipo=1  and number like '%".addslashes($value)."%' ";
    $result2 = mysql_query($sql2) or die('Query faileda3a: ' . mysql_error());
    $matches=array();
    while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
     
      $contact_id=$row2['contact_id'];
      if(is_numeric($row2['parent_id']))
	$contact_id=$row2['parent_id'];
      $matches[]= $contact_id;
    }
    $result = array_unique($matches);
    return $result;

    break;
  case('fax'):
    if($value==''){
      $a=array();
      return $a;
    }

    $value=preg_replace('/\s/','',$value);
    $sql2="select contact_id,parent_id  from telecom  left join telecom2contact as t2c on (telecom_id=telecom.id)  left join contact_relations on (contact_id=child_id)  where (tipo=2) and number like '%".addslashes($value)."%' ";
    $result2 = mysql_query($sql2) or die('Query faileda4: ' . mysql_error());
    $matches=array();
    while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
     
      $contact_id=$row2['contact_id'];
      if(is_numeric($row2['parent_id']))
	$contact_id=$row2['parent_id'];
      $matches[]= $contact_id;
    }
    $result = array_unique($matches);
    return $result;

    break;
  case('mobile'):
    if($value==''){
      $a=array();
      return $a;
    }
    $value=preg_replace('/\s/','',$value);
    $sql2="select contact_id,parent_id  from telecom  left join telecom2contact as t2c on (telecom_id=telecom.id) left join contact_relations on (contact_id=child_id)  where (tipo=3) and number like '%".addslashes($value)."%' ";
    //  print "$sql2\n";
    // exit;
    $result2 = mysql_query($sql2) or die('Query faileda5: ' . mysql_error());
    $matches=array();
    while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
     
      $contact_id=$row2['contact_id'];
      if(is_numeric($row2['parent_id']))
	$contact_id=$row2['parent_id'];
      $matches[]= $contact_id;
    }
    $result = array_unique($matches);
    return $result;

    break;

  case('email'):
    if($value==''){
      $a=array();
      return $a;
    }
    $value=preg_replace('/\s/','',$value);
    $sql2="select contact_id,parent_id  from email  left join contact_relations on (contact_id=child_id)  where email like '".addslashes($value)."' ";
    $result2 = mysql_query($sql2) or die('Query faileda6: ' . mysql_error());
    $matches=array();
    while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
     
      $contact_id=$row2['contact_id'];
      if(is_numeric($row2['parent_id']))
	$contact_id=$row2['parent_id'];
      $matches[]= $contact_id;
    }
    $result = array_unique($matches);
    return $result;

    break;
  case('fuzzy_address'):
    if($value==''){
      $a=array();
      return $a;
    }
    $sql2="select contact_id,parent_id, match(full_address) against ('".addslashes($value)."') as ma   from address  left join contact_relations on (contact_id=child_id)  where ma>90 ";
    $result2 = mysql_query($sql2) or die('Query faileda7: ' . mysql_error());
    $matches=array();
    while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
     
      $contact_id=$row2['contact_id'];
      if(is_numeric($row2['parent_id']))
	$contact_id=$row2['parent_id'];
      $matches[]= $contact_id;
    }
    $result = array_unique($matches);
    return $result;
     
  case('address'):


    if(!$value){
      $a=array();
      return $a;
    }
      

    //print_r($value);
   
    $check_street=($value['street_address']==''?'isnull(street_address) and ':"street_address='".addslashes($value['street_address'])."' and ");
    $check_building=($value['building_address']==''?'isnull(building_address) and ':"building_address='".addslashes($value['building_address'])."' and ");
    $check_internal=($value['internal_address']==''?'isnull(internal_address) and ':"internal_address='".addslashes($value['internal_address'])."' and ");
    $postcode=($value['postcode']==''?'isnull(postcode) and ':"postcode='".addslashes($value['postcode'])."' and ");
    $country_d2=($value['country_d2']==''?'isnull(country_d2) and ':"country_d2='".addslashes($value['country_d2'])."' and ");
    $town=($value['town']==''?'isnull(town) and ':"town='".addslashes($value['town'])."' and ");



    $sql2=sprintf("select contact_id,parent_id   from address  left join address2contact on (address_id=address.id) left join contact_relations on (contact_id=child_id)  where  $check_street $check_building $check_internal $postcode $town $country_d2  true");
    
    


    //    print "$sql2\n";;
    $result2 = mysql_query($sql2) or die('Query faileda8: ' . mysql_error());
    $matches=array();
    while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
     
      $contact_id=$row2['contact_id'];
      if(is_numeric($row2['parent_id']))
	$contact_id=$row2['parent_id'];
      $matches[]= $contact_id;
    }
    $result = array_unique($matches);
    return $result;
    break;

  case('postcode'):
    $value=preg_replace('/\s/','',$value);
    $sql2="select contact_id,parent_id   from address  left join address2contact on (address_id=address.id) left join contact_relations on (contact_id=child_id)  where  postcode like '".addslashes($value)."' ";
    $result2 = mysql_query($sql2) or die('Query faileda:9 ' . mysql_error());
    $matches=array();
    while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
     
      $contact_id=$row2['contact_id'];
      if(is_numeric($row2['parent_id']))
	$contact_id=$row2['parent_id'];
      $matches[]= $contact_id;
    }
    $result = array_unique($matches);
    return $result;
    break;
  case('fuzzy_name'):
    if($value=='' ){
      $a=array();
      return $a;
    }
    $sql2="select contact_id,parent_id, match(name) against ('".$value."') as ma   from contact  left join contact_relations on (contact.id=child_id)  where ma>90 ";
    $result2 = mysql_query($sql2) or die('Query faileda:10 ' . mysql_error());
    $matches=array();
    while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
     
      $contact_id=$row2['contact_id'];
      if(is_numeric($row2['parent_id']))
	$contact_id=$row2['parent_id'];
      $matches[]= $contact_id;
    }
    $result = array_unique($matches);
    return $result;
    
    break;
    
  case('name'):
    if($value=='' ){
      $a=array();
      return $a;
    }
    $sql2="select contact.id as contact_id,parent_id  from contact  left join contact_relations on (contact.id=child_id)  where name='".addslashes($value)."' ";
    // print "$sql2\n";
    $result2 = mysql_query($sql2) or die('Query faileda:11 ' . mysql_error());
    $matches=array();
    while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
     
      $contact_id=$row2['contact_id'];
      if(is_numeric($row2['parent_id']))
	$contact_id=$row2['parent_id'];
      $matches[]= $contact_id;
    }
    $result = array_unique($matches);
    return $result;

    break;
  }


  $a=array();
  return $a;
  
}


function get_values($tipo,$contact_id){
  switch($tipo){
  case('name'):
    return get_name($contact_id);
    break;
  case('tel'):
    return get_tels($contact_id);
    break;
  case('fax'):
    return get_faxes($contact_id);
    break;
  case('mob'):
    return get_mobiles($contact_id);
    break;
  case('email'):
    return get_emails($contact_id);
    break;  
  case('child'):
    return get_child_names($contact_id);
    break;  
  case('shop_address'):
    return get_addresses($contact_id,'shop');
    break;  
  case('bill_address'):
    return get_addresses($contact_id,'bill');
    break;  
  case('del_address'):
    return get_addresses($contact_id,'del');
    break; 
  case('all_address'):
    return get_addresses($contact_id,'all');
    break; 

    
  }
}


function get_changes($contact_id,$new_values){

  foreach($new_values as $key=>$value){

    $oldkey=$key;
    if($key=='del_address' or $key=='shop_address' or $key=='bill_address')
      $oldkey='all_address';
    $changed[$key]=_get_changes($key,get_values($oldkey,$contact_id),$value);
  }
  return $changed;
}



function _get_changes($tipo,$old_values,$new_values){


  ///    print "======================================== $tipo\n";

  switch($tipo){
  case('name'):


    if($old_values==$new_values)
      return array(false,0);
    else
      return array(true,$old_values);
    break;
    
  case('child'):
 //    print "old contact\n";
 //    print_r($old_values);
//     print "new contact\n";
    //   print_r($new_values);

    if(count($old_values)==0){
      if($new_values)
	return  array(true,0);
      else
	return 	 array(false,0);
    }
    
    $similarity=array();
    $keys=array();
    foreach($old_values as $child_id=>$old_value ){

      // Old John NEW John Smith or viceversa
      if($old_value['first']==$new_values['first'] and $old_value['middle']==$new_values['middle'] and $old_value['last']=='' and $new_values['last']!=''     ){
	
	if( $old_value['last']=='' and $new_values['last']!='' ){
	  $changes[0]=true;
	  $changes[1]=$child_id;
	  return $changes;
	}
       	if( $old_value['last']!='' and $new_values['last']=='' ){
	  $changes[0]=false;
	  $changes[1]=0;
	  return $changes;
	}

      }



      // Smith  joth smith
      // Old John NEW John Smith or viceversa
      if( $old_value['prefix']!=$new_values['prefix'] and   $old_value['last']==$new_values['last'] and $old_value['middle']==$new_values['middle']    ){
	if( $old_value['first']=='' and $new_values['first']!='' ){
	  $changes[0]=true;
	  $changes[1]=$child_id;
	  return $changes;
	}
       	if( $old_value['first']!='' and $new_values['first']=='' ){
	  $changes[0]=false;
	  $changes[1]=0;
	  return $changes;
	}
      }
      



      $sim=0;
      
      if($old_value['first']==$new_values['first'])
	$tmp=100;
      else
	similar_text($old_value['first'],$new_values['first'],$tmp);
      $sim+=$tmp;

      if($old_value['last']==$new_values['last'])
	$tmp=100;
      else
	similar_text($old_value['last'],$new_values['last'],$tmp);
      $sim+=$tmp;
      $changed=false;
      if($old_value['prefix']!=$new_values['prefix'])$sim=$sim-1;
      if($old_value['middle']==$new_values['middle'])$sim=$sim-1;
      if($old_value['suffix']==$new_values['suffix'])$sim=$sim-1;
      if($old_value['alias']==$new_values['alias'])$sim=$sim-1;
	    
      
      
      $keys[]=$child_id;
      $similarity[]=$sim;

    }
    
    array_multisort($similarity, $keys);
    $similarity=array_reverse($similarity);
    $keys=array_reverse($keys);
    $changes[0]=false;
    $changes[1]=0;
    if($similarity[0]<100){
	$changes[0]=true;
	if($similarity[0]>90){
	  $changes[1]=$keys[0];
	}
    }
    return $changes;
    break;
  case('shop_address'):
  case('del_address'):
  case('bill_address'):
    //   print "$tipo\n";
    //print"old\n";
    //   print_r($old_values);
    //       print"new\n";
    //	print_r($new_values);


    if(count($old_values)==0){
      if($new_values)
	return  array(true,'id'=>false,'insert'=>true,'associate'=>false);
      else
	return array(false,'id'=>false,'insert'=>false,'associate'=>false);
    }
    $changes['insert']=false;
    $changes['id']=false;
    $changes['associate']=false;
    $changes['update']=false;
    $changes[0]=false;
    // now get  the similarityies of each row to try to get if is a complaty new address or if is a update//
    $similarity=array();
    $keys=array();
    $address_components=array('internal_address','building_address','street_address','town_d2','town_d1','town','country_d2','country_d1','postcode','country');
    $max_output=100*count($address_components)+20;
    $umbral=.875*$max_output;
    // print "$max_output $umbral";
    foreach( $old_values as $address_id=>$old_address_data   ){
      // text similarity
      
      
      $overall_similarity=0;
      foreach($address_components as $component){
	$the_factor=1.0;
	$important=false;
	if($component=='street_address' or $component=='postcode'){
	  $the_factor=2.0;
	  $important=true;
	}

	if($component=='country_d2' or $component=='country_d1'){
	  if(($old_address_data[$component]=='' and $new_values[$component]!='')
	     or
	     ($old_address_data[$component]!='' and $new_values[$component]=='')
	     )
	    $overall_similarity+=50;
	}
if($component=='town_d2' or $component=='town_d1'){
	  if(($old_address_data[$component]=='' and $new_values[$component]!='')
	     or
	     ($old_address_data[$component]!='' and $new_values[$component]=='')
	     )
	    $overall_similarity+=50;
	}

 if(strtolower($old_address_data[$component])==strtolower($new_values[$component])){
   //if($important)
   //  $_the_factor=$the_factor*1.1;
   // else
     $_the_factor=$the_factor;
   $overall_similarity+=(100*$_the_factor);
   //    print (100*$the_factor)." $overall_similarity $component \n";
 }else{
	  similar_text(  strtolower($old_address_data[$component]),strtolower($new_values[$component]),$tmp);
	  if($important){
	    if($tmp>90){
	      $overall_similarity+=$tmp/$the_factor;
	      //      print $tmp/$the_factor." $overall_similarity $component \n";
	    }else{
	      $overall_similarity+=($tmp-100)/$the_factor;
	      //print ($tmp-100)/$the_factor." $overall_similarity $component \n";
	    }
	  }else{
	    $overall_similarity+=$tmp/$the_factor;
	 
	    //print $tmp/$the_factor." $overall_similarity $component \n";
	  }
	}       
      }
      $similarity[]=$overall_similarity;
      $keys[]=$address_id;
    }
    //     print "$max_output $umbral $overall_similarity  xx\n";
    //    print_r($similarity);
    //exit;
    array_multisort($similarity, $keys);
    $similarity=array_reverse($similarity);
    $keys=array_reverse($keys);
    // print_r($similarity);
    if($similarity[0]>=$max_output){
      $changes[0]=false;
      $changes['id']=$keys[0];
      //print "hols\n";

    }else{
      $changes[0]=true;

      if($similarity[0]>$umbral){
	$changes['id']=$keys[0];
      	$changes['update']=true;
      }else{
	$changes['insert']=true;
	$changes['id']=false;
      }
    }

    
    if($changes['id']>0){
      $a_tipos=split(',', $old_values[$changes['id']]['tipos']  );

      if($tipo=='shop_address')
	$_tipo=1;
      elseif($tipo=='del_address')
	$_tipo=3;
      elseif($tipo=='bill_address')
	$_tipo=2;




      if(!in_array($_tipo,$a_tipos)){
	$changes['associate']=true;
	$changes[0]=true;
      }


    }







  



    //print_r($changes);
    //  exit;

    return $changes;
    break;
  case('mob'):
  case('fax'):
  case('tel'):

 //     print "old tel\n";
//      print_r($old_values);
//      print "new tel\n";
//      print_r($new_values);



    if(count($old_values)==0){
      if($new_values)
	return array(true,0);
      else
	return array(false,0);
    }
    
    
    //  print_r($old_values);
    //print_r($new_values);
    $similarity=array();
    $keys=array();

    
    foreach($old_values as $telecom_id=>$old_tel ){
    
      

      similar_text($old_tel['number'],$new_values['number'],$tmp);

      if($old_tel['icode']!=$new_values['icode'])
	$tmp=$tmp-5;
      if($old_tel['ncode']!=$new_values['ncode'])
	$tmp=$tmp-5;
      if($old_tel['ext']!=$new_values['ext'])
	$tmp=$tmp-1;
      
      
      $similarity[]=$tmp;
      $keys[]=$telecom_id;
      
      

      
    }


    array_multisort($similarity, $keys);
    $similarity=array_reverse($similarity);
    $keys=array_reverse($keys);
    $changes[0]=false;
    $changes[1]=0;
    if($similarity[0]<100){
      $changes[0]=true;
      if($similarity[0]>90){
	$changes[1]=$keys[0];
      }
    }
    return $changes;
    break;

 case('email'):

   if(count($old_values)==0){
     if($new_values)
       return array(true,0);
     else
       return array(false,0);

   }

    $similarity=array();
    $keys=array();

    // print_r($old_values);
    //print_r($new_values);
    foreach($old_values as $email_id=>$email ){
      //      print "$email $new_values \n";
      similar_text(strtolower($email['email']),strtolower($new_values['email']),$tmp);
      $similarity[]=$tmp;
      $keys[]=$email_id;
      
    }
    array_multisort($similarity, $keys);
    $similarity=array_reverse($similarity);
    $keys=array_reverse($keys);
    $changes[0]=false;
    $changes[1]=0;
    // print_r($similarity);
    if($similarity[0]<100){
      $changes[0]=true;
      if($similarity[0]>95){
	$changes[1]=$keys[0];
      }
    }

    return $changes;
    break;




  }
}




?>