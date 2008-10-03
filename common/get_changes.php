<?
function _get_changes($tipo,$old_values,$new_values){


  switch($tipo){
  case('name'):
    print $old_values[0][1]." ->  $new_values    \n";
    if($old_values[0][1]==$new_values)
      return array(false,0);
    else
      return array(true,$old_values[0][0]);
    break;
    
  case('child'):
    
    if($new_values==''){
      return  array(false,0);
    }
    
    if(count($old_values)==0){
      return  array(true,0);
    }
    
    $similarity=array();
    $keys=array();
    foreach($old_values as $child_id=>$old_value ){
      $tmp=0;similar_text(strtolower($old_value),strtolower($new_values),$tmp);
      $similarity[]=$tmp;
      $keys[]=$child_id;
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
  case('del_address'):
  // Del address

    $a1=$new_values['address1'];
    $a2=$new_values['address2'];
    $a3=$new_values['address3'];
    $town=$new_values['town'];
    $area_unit=$new_values['area_unit'];
    $postcode=$new_values['postcode'];
    $country_id=$new_values['country_id'];
    
    


    // now get  the similarityies of each row to try to get if is a complaty new address or if is a update//

    $similarity=array();
    $keys=array();

    foreach( $old_values as $address_id=>$address_data   ){
      // text similarity
      $overall_similarity=0;
      


      
      if($address_data['address1']=='' and $a1=='')
	$tmp=100;
      else
	similar_text(strtolower($address_data['address1']),strtolower($a1),$tmp);
      $similarity_a1[]=$tmp;
      $overall_similarity+=$tmp;

      if($address_data['address2']=='' and $a2=='')
	$tmp=100;
      else
	similar_text(strtolower($address_data['address2']),strtolower($a2),$tmp);
      $similarity_a2[]=$tmp;$overall_similarity+=$tmp;

      if($address_data['address3']=='' and $a3=='')
	$tmp=100;
      else
	similar_text(strtolower($address_data['address3']),strtolower($a3),$tmp);
      $similarity_a3[]=$tmp;$overall_similarity+=$tmp;

      if($address_data['area_unit']=='' and $area_unit=='')
	$tmp=100;
      else
	similar_text(strtolower($address_data['area_unit']),strtolower($area_unit),$tmp);
      $similarity_area_unit[]=$tmp;$overall_similarity+=$tmp;

      if($address_data['town']=='' and $town=='')
	$tmp=100;
      else
	similar_text(strtolower($address_data['town']),strtolower($town),$tmp);
      $similarity_town[]=$tmp;$overall_similarity+=$tmp;

      if($address_data['postcode']=='' and $postcode=='')
	$tmp=100;
      else
	similar_text(strtolower($address_data['postcode']),strtolower($postcode),$tmp);
      $similarity_postcode[]=$tmp;$overall_similarity+=$tmp;
      
      if($address_data['country_id']==$country_id)
	$overall_similarity+=100;
      

      $similarity[]=$overall_similarity;
      $keys[]=$address_id;
      
    }

    array_multisort($similarity, $keys);
    $similarity=array_reverse($similarity);
    $keys=array_reverse($keys);
    $changes[0]=false;
    $changes[1]=0;
    if($similarity[0]<700){
      $changes[0]=true;
      if($overall_similarity>510)
	$changes[1]=$keys[0];
    }
    return $changes;
    break;
  
    
  case('tel'):
    $similarity=array();
    $keys=array();
    foreach($old_values as $telecom_id=>$tel ){
      //      print "$tel $new_values \n";
      similar_text(strtolower($tel),strtolower($new_values),$tmp);
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
    $similarity=array();
    $keys=array();
    foreach($old_values as $email_id=>$email ){
      //      print "$email $new_values \n";
      similar_text(strtolower($email),strtolower($new_values),$tmp);
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