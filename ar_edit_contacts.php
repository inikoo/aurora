<?php
require_once 'common.php';
require_once 'class.Company.php';
require_once 'class.Supplier.php';



if(!isset($_REQUEST['tipo']))
  {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
  }

$editor=array(
	      'Author Name'=>$user->data['User Alias'],
	      'Author Type'=>$user->data['User Type'],
	      'Author Key'=>$user->data['User Parent Key'],
	      'User Key'=>$user->id
	      );



$tipo=$_REQUEST['tipo'];
switch($tipo){
//---------------------------------------------------------------------------------------
case('new_address'):
 if( !isset($_REQUEST['value']) ){
    $response=array('state'=>400,'msg'=>'Error no value');
    echo json_encode($response);
    return;
   }
   
   $tmp=preg_replace('/\\\"/','"',$_REQUEST['value']);
   $tmp=preg_replace('/\\\\\"/','"',$tmp);
   
   $raw_data=json_decode($tmp, true);

   if(!is_array($raw_data)){
     $response=array('state'=>400,'msg'=>'Wrong value');
     echo json_encode($response);
     return;
   }


   if( !isset($_REQUEST['subject'])  
       or !is_numeric($_REQUEST['subject_key'])
       or $_REQUEST['subject_key']<=0
       or !preg_match('/^company|contact$/i',$_REQUEST['subject'])
       
       ){
     $response=array('state'=>400,'msg'=>'Error wrong subject/subject key');
      echo json_encode($response);
    return;
   }
 
   $subject=$_REQUEST['subject'];
   $subject_key=$_REQUEST['subject_key'];

   switch($subject){
   case('Company'):
     $subject_object=new Company($subject_key);

     break;
   default:
       
     $response=array('state'=>400,'msg'=>'Error wrong subject/subject key (2)');
     echo json_encode($response);
     return;

   }
   
 $translator=array(
		     'country_code'=>'Address Country Code'
		     ,'country_d1'=>'Address Country Primary Division'
		     ,'country_d2'=>'Address Country Secondary Division'
		     ,'town'=>'Address Town'
		     ,'town_d1'=>'Address Town Primary Division'
		     ,'town_d2'=>'Address Town Secondary Division'
		     ,'postal_code'=>'Address Postal Code'
		     ,'street'=>'Street Data'
		     ,'internal'=>'Address Internal'
		     ,'building'=>'Address Building'
		     ,'type'=>'Address Type'
		     ,'function'=>'Address Function'
		     ,'description'=>'Address Description'
		     
		   );
 
 
   $data=array('editor'=>$editor);
   foreach($raw_data as $key=>$value){
     if (array_key_exists($key, $translator)) {
       $data[$translator[$key]]=$value;
     }
   }
   

   
   $subject_object->add_address($data);
   if($subject_object->added_address_key){
     $contact=new Contact('create anonymous');
     $contact->add_address(array(
				 'Address Key'=>$subject_object->added_address_key
				 ,'Address Type'=>$data['Address Type']
				 ,'Address Function'=>$data['Address Function']
			       ));
     
     $address=new Address($subject_object->added_address_key);
     
     
     $address->set_scope($subject,$subject_key);
     
     $updated_address_data=array(
				 'country'=>$address->data['Address Country Name']
				 ,'country_code'=>$address->data['Address Country Code']
				 ,'country_d1'=> $address->data['Address Country Primary Division']
				 ,'country_d2'=> $address->data['Address Country Secondary Division']
				 ,'town'=> $address->data['Address Town']
				 ,'postal_code'=> $address->data['Address Postal Code']
				 ,'town_d1'=> $address->data['Address Town Primary Division']
				 ,'town_d2'=> $address->data['Address Town Secondary Division']
				 ,'fuzzy'=> $address->data['Address Fuzzy']
				 ,'street'=> $address->display('street')
				 ,'building'=>  $address->data['Address Building']
				 ,'internal'=> $address->data['Address Internal']
				 ,'type'=>$address->data['Address Type']
				 ,'function'=>$address->data['Address Function']
				 ,'description'=>$address->data['Address Description']
				   );
       
     $response=array(
		     'state'=>200
		     ,'action'=>'created'
		     ,'msg'=>$subject_object->msg_updated
		     ,'updated_data'=>$updated_address_data
		     ,'xhtml_address'=>$address->display('xhtml')
		     ,'address_key'=>$address->id
		     );
  echo json_encode($response);
     return;
   
   }else{
     $response=array('state'=>200,'action'=>'nochange','msg'=>_('Address already in company'));
     echo json_encode($response);
     return;
   }

  break;
 //---------------------------------------------------------------------------------------
case('edit_address_type'):
   if( !isset($_REQUEST['value']) ){
    $response=array('state'=>400,'msg'=>'Error no value');
    echo json_encode($response);
    return;
   }
   
   $tmp=preg_replace('/\\\"/','"',$_REQUEST['value']);
   $tmp=preg_replace('/\\\\\"/','"',$tmp);
   //$tmp=$_REQUEST['value'];
   $raw_data=json_decode($tmp, true);
   //   print "$tmp";
   // print_r($raw_data);

   if(!is_array($raw_data)){
     $response=array('state'=>400,'msg'=>'Wrong value');
     echo json_encode($response);
     return;
   }
   if( !isset($_REQUEST['id'])  or !is_numeric($_REQUEST['id']) or $_REQUEST['id']<=0  ){
     $response=array('state'=>400,'msg'=>'Error wrong id');
     echo json_encode($response);
    return;
   }



   if( !isset($_REQUEST['subject'])  
       or !is_numeric($_REQUEST['subject_key'])
       or $_REQUEST['subject_key']<=0
       or !preg_match('/^company|contact$/i',$_REQUEST['subject'])
       
       ){
     $response=array('state'=>400,'msg'=>'Error wrong subject/subject key');
      echo json_encode($response);
    return;
   }
   
   $subject=$_REQUEST['subject'];
   $subject_key=$_REQUEST['subject_key'];


   $address=new Address('id',$_REQUEST['id']);

   if(!$address->id){
      $response=array('state'=>400,'msg'=>'Address not found');
      echo json_encode($response);
      return;
   }
   $address->set_editor($editor);
   $address->set_scope($subject,$subject_key);
   $address->update_metadata(
			     array('Type'=>$raw_data)
			     );


   $updated_data=array();
   foreach($address->get('Type') as $type)
     $updated_data[]=$type;
    
   if($address->updated){
     $response=array(
		     'state'=>200
		     ,'action'=>'updated'
		     ,'msg'=>$address->msg_updated
		     ,'key'=>''
		     ,'updated_data'=>$updated_data
		     );
   }else{
     if($address->error_updated)
       $response=array('state'=>200,'action'=>'error','msg'=>$company->msg_updated,'key'=>'');
     else
       $response=array('state'=>200,'action'=>'nochange','msg'=>$address->msg_updated,'key'=>'');
     
   }

    
   echo json_encode($response);
    

  break;


 //---------------------------------------------------------------------------------------
case('edit_address'):
   if( !isset($_REQUEST['value']) ){
    $response=array('state'=>400,'msg'=>'Error no value');
    echo json_encode($response);
    return;
   }
   
   $tmp=preg_replace('/\\\"/','"',$_REQUEST['value']);
   $tmp=preg_replace('/\\\\\"/','"',$tmp);
   //$tmp=$_REQUEST['value'];
   $raw_data=json_decode($tmp, true);
   //   print "$tmp";
   // print_r($raw_data);

   if(!is_array($raw_data)){
     $response=array('state'=>400,'msg'=>'Wrong value');
     echo json_encode($response);
     return;
   }
   if( !isset($_REQUEST['id'])  or !is_numeric($_REQUEST['id']) or $_REQUEST['id']<=0  ){
     $response=array('state'=>400,'msg'=>'Error wrong id');
     echo json_encode($response);
    return;
   }



   if( !isset($_REQUEST['subject'])  
       or !is_numeric($_REQUEST['subject_key'])
       or $_REQUEST['subject_key']<=0
       or !preg_match('/^company|contact$/i',$_REQUEST['subject'])
       
       ){
     $response=array('state'=>400,'msg'=>'Error wrong subject/subject key');
      echo json_encode($response);
    return;
   }
   $subject=$_REQUEST['subject'];
   $subject_key=$_REQUEST['subject_key'];


   $address=new Address('id',$_REQUEST['id']);

   if(!$address->id){
      $response=array('state'=>400,'msg'=>'Address not found');
      echo json_encode($response);
      return;
   }
   $address->set_editor($editor);
  


   $translator=array(
		     'country_code'=>'Address Country Code'
		     ,'country_d1'=>'Address Country Primary Division'
		     ,'country_d2'=>'Address Country Secondary Division'
		     ,'town'=>'Address Town'
		     ,'town_d1'=>'Address Town Primary Division'
		     ,'town_d2'=>'Address Town Secondary Division'
		     ,'postal_code'=>'Address Postal Code'
		     ,'street'=>'Street Data'
		     ,'internal'=>'Address Internal'
		     ,'building'=>'Address Building');
   

   $update_data=array('editor'=>$editor);
   foreach($raw_data as $key=>$value){
     if (array_key_exists($key, $translator)) {
       $update_data[$translator[$key]]=$value;
     }
   }
   
   $address->find("in $subject $subject_key");
   if($address->found_in){
     $msg=_('Address already associated with contact');
     $response=array('state'=>200,'action'=>'error','msg'=>$msg,'key'=>'');
     echo json_encode($response);
     return;
   }
   $address->update($update_data,'cascade');
  
    
   if($address->updated){
     $updated_address_data=array(
				 'country'=>$address->data['Address Country Name']
				 ,'country_code'=>$address->data['Address Country Code']
				 ,'country_d1'=> $address->data['Address Country Primary Division']
				 ,'country_d2'=> $address->data['Address Country Secondary Division']
				 ,'town'=> $address->data['Address Town']
				 ,'postal_code'=> $address->data['Address Postal Code']
				 ,'town_d1'=> $address->data['Address Town Primary Division']
				 ,'town_d2'=> $address->data['Address Town Secondary Division']
				 ,'fuzzy'=> $address->data['Address Fuzzy']
				 ,'street'=> $address->display('street')
				 ,'building'=>  $address->data['Address Building']
				 ,'internal'=> $address->data['Address Internal']
				 ,'description'=>$address->data['Address Description']
				 
				 );
     $response=array('state'=>200,'action'=>'updated','msg'=>$address->msg_updated,'key'=>'','updated_data'=>$updated_address_data,'xhtml_address'=>$address->display('xhtml'));
   }else{
     if($address->error_updated)
       $response=array('state'=>200,'action'=>'error','msg'=>$company->msg_updated,'key'=>$translator[$_REQUEST['key']]);
     else
       $response=array('state'=>200,'action'=>'nochange','msg'=>$address->msg_updated,'key'=>'');
     
   }

    
   echo json_encode($response);
    

  break;
  //---------------------------------------------------------------------------------------
case('edit_company'):
  if(!isset($_REQUEST['key']) ){
    $response=array('state'=>400,'msg'=>'Error no key');
  }
 if( !isset($_REQUEST['value']) ){
   $response=array('state'=>400,'msg'=>'Error no value');
 }
 if( !isset($_REQUEST['id']) or !is_numeric($_REQUEST['id'])  ){
   $company_key=$_SESSION['state']['company']['id'];
 }else
   $company_key=$_REQUEST['id'];

 $company=new Company($company_key);

 if(!$company->id){
   $response=array('state'=>400,'msg'=>_('Company not found'));
 }
  
 $translator=array(
		   'name'=>'Company Name'
		   ,'fiscal_name'=>'Company Fiscal Name'
		   ,'tax_number'=>'Company Tax Number'
		   ,'registration_number'=>'Company Registration Number'
		   

		   );
  if (array_key_exists($_REQUEST['key'], $translator)) {
    $update_data=array(
		       'editor'=>$editor
		       ,$translator[$_REQUEST['key']]=>$_REQUEST['value']
		       );
    $company->update($update_data);
    
    if($company->error_updated){
      $response=array('state'=>200,'action'=>'error','msg'=>$company->msg_updated,'key'=>$translator[$_REQUEST['key']]);
    }else{
    
      if($company->updated){
	$response=array('state'=>200,'action'=>'updated','msg'=>$company->msg_updated,'key'=>$translator[$_REQUEST['key']]);
      }else{
	$response=array('state'=>200,'action'=>'nochange','msg'=>$company->msg_updated,'key'=>$translator[$_REQUEST['key']]);

      }

    }


  }else{
    $response=array('state'=>400,'msg'=>_('Key not in Company'));
  }
  echo json_encode($response);
 
  break;

    //---------------------------------------------------------------------------------------
case('edit_contact'):
 edit_company();
 
 break;
 //---------------------------------------------------------------------------------------
case('edit_email'):
   if( !isset($_REQUEST['value']) ){
    $response=array('state'=>400,'msg'=>'Error no value');
    echo json_encode($response);
    return;
   }
   
   $tmp=preg_replace('/\\\"/','"',$_REQUEST['value']);
   $tmp=preg_replace('/\\\\\"/','"',$tmp);
   $raw_data=json_decode($tmp, true);


   if(!is_array($raw_data)){
     $response=array('state'=>400,'msg'=>'Wrong value');
     echo json_encode($response);
     return;
   }
   if( !isset($_REQUEST['id'])  or !is_numeric($_REQUEST['id']) or $_REQUEST['id']<=0  ){
     $response=array('state'=>400,'msg'=>'Error wrong id');
     echo json_encode($response);
    return;
   }



   if( !isset($_REQUEST['subject'])  
       or !is_numeric($_REQUEST['subject_key'])
       or $_REQUEST['subject_key']<=0       or !preg_match('/^company|contact$/i',$_REQUEST['subject'])
       
       ){
     $response=array('state'=>400,'msg'=>'Error wrong subject/subject key');
      echo json_encode($response);
    return;
   }
   $subject_type=$_REQUEST['subject'];
   $subject_key=$_REQUEST['subject_key'];

   if(preg_match('/^company$/i',$subject_type))
     $subject=new Company($subject_key);
   else{
     $subject=new Contact($subject_key);
   }

   
if(!$subject->id){
       $response=array('state'=>400,'msg'=>'Subject not found');
       echo json_encode($response);
       return;
     }
   

   if(!isset($raw_data['Email'])){
     $response=array('state'=>400,'msg'=>'No email value');
     echo json_encode($response);
     return;
     
   }

   $editing=false;
   $creating=false;

   $msg=_('No changes');
  
   if(is_numeric($raw_data['Email Key']) and $raw_data['Email Key']>0){
     $action='updated';
     $email=new Email('id',$raw_data['Email Key']);
     if(!$email->id){
       $response=array('state'=>400,'msg'=>'Email not found');
       echo json_encode($response);
       return;
     }
     $email->set_editor($editor);

     $email->update(array('Email'=>$raw_data['Email']));
     // $email->update(array('Email'=>'scsaca@gmail.com'));
    
    
     if($email->error_updated){
      $response=array('state'=>200,'action'=>'error','msg'=>$email->msg_updated);
       echo json_encode($response);
       return;
     }

     if($email->updated)
       $msg=_('Email updated');

     $update_data=array(
			'Email Key'=>$raw_data['Email Key']
			,'Email Description'=>$raw_data['Email Description']
			,'Email Is Main'=>$raw_data['Email Is Main']
			,'Email Contact Name'=>$raw_data['Email Contact Name']
			
			);
    
     
     $subject->add_email($update_data);
     if($subject->updated)
       $msg=_('Email updated');
     $email->set_scope($subject_type,$subject_key);

   }else{
     $action='created';
       $update_data=array(
			'Email'=>$raw_data['Email']
			,'Email Description'=>$raw_data['Email Description']
			,'Email Is Main'=>$raw_data['Email Is Main']
			,'Email Contact Name'=>$raw_data['Email Contact Name']
			);


      

       $subject->add_email($update_data,'if found error');
       
       if($subject->error){
	 $response=array('state'=>200,'action'=>'error','msg'=>$subject->msg_updated);
	 echo json_encode($response);
	 return;
	 
       }
       
	 
       if($subject->inserted_email){
	 $email=new Email ($subject->inserted_email);
	 $email->set_scope($subject_type,$subject_key);
	 $msg=_("Email created");
       }else{
	 $response=array('state'=>200,'action'=>'nochange','msg'=>$subject->msg_updated);
	 echo json_encode($response);
	 return;

	 
       }
       
   }


  $updated_email_data=array(
			    'Email'=>$email->data['Email']
			    ,'Email_Description'=>$email->data['Email Description']
			    ,'Email_Contact_Name'=> $email->data['Email Contact Name']
			    ,'Email_Is_Main'=> $email->data['Email Is Main']
			    );
  
  $subject->reread();
  
  $response=array('state'=>200,'action'=>$action,'msg'=>$msg,'email_key'=>$email->id,'updated_data'=>$updated_email_data,'xhtml_subject'=>$subject->display('card'),'main_email_key'=>$subject->get_main_email_key());
     
    
   echo json_encode($response);
    

  break;
//---------------------------------------------------------------------------------------
case('remove_email'):
case('delete_email'):
  if( !isset($_REQUEST['value']) or !is_numeric($_REQUEST['value']) ){
    $response=array('state'=>400,'msg'=>'Error no value');
    echo json_encode($response);
    return;
   }
   
 
   if( !isset($_REQUEST['id'])  or !is_numeric($_REQUEST['id']) or $_REQUEST['id']<=0  ){
     $response=array('state'=>400,'msg'=>'Error wrong id');
     echo json_encode($response);
    return;
   }



   if( !isset($_REQUEST['subject'])  
       or !is_numeric($_REQUEST['subject_key'])
       or $_REQUEST['subject_key']<=0       or !preg_match('/^company|contact$/i',$_REQUEST['subject'])
       
       ){
     $response=array('state'=>400,'msg'=>'Error wrong subject/subject key');
      echo json_encode($response);
    return;
   }
   $subject_type=$_REQUEST['subject'];
   $subject_key=$_REQUEST['subject_key'];

   if(preg_match('/^company$/i',$subject_type))
     $subject=new Company($subject_key);
   else{
     $subject=new Contact($subject_key);
   }
   
   
   if(!$subject->id){
     $response=array('state'=>400,'msg'=>'Subject not found');
     echo json_encode($response);
     return;
   }
   
   
   
   $email_key=$_REQUEST['value'];

  

   $subject->remove_email($email_key);
   
   if($subject->updated){
     $action='deleted';
     $msg=_('Email deleted');
     $subject->reread();
   }else{
     $action='nochage';
     $msg=_('Email could not be deleted');
   }
  
   

   $response=array('state'=>200,'action'=>$action,'msg'=>$msg,'email_key'=>$email_key,'xhtml_subject'=>$subject->display('card'),'main_email_key'=>$subject->get_main_email_key());
     
    
   echo json_encode($response);
    

  break;
}



function edit_company(){
global $editor;
 if(!isset($_REQUEST['key']) ){
    $response=array('state'=>400,'msg'=>'Error no key');
  }
 if( !isset($_REQUEST['value']) ){
   $response=array('state'=>400,'msg'=>'Error no value');
 }

   $tmp=preg_replace('/\\\"/','"',$_REQUEST['value']);
   $tmp=preg_replace('/\\\\\"/','"',$tmp);
   
   $raw_data=json_decode($tmp, true);

   if(!is_array($raw_data)){
     $response=array('state'=>400,'msg'=>'Wrong value');
     echo json_encode($response);
     return;
   }
 





 if( !isset($_REQUEST['id']) or !is_numeric($_REQUEST['id'])  ){
   $contact_key=$_SESSION['state']['contact']['id'];
 }else
   $contact_key=$_REQUEST['id'];

 $contact=new Contact($contact_key);

 if(!$contact->id){
   $response=array('state'=>400,'msg'=>_('Contact not found'));
 }
  
 $translator=array(
		   'Contact_Name_Components'=>'Contact Name Components'
		   ,'Contact_Gender'=>'Contact Gender'
		   ,'Contact_Title'=>'Contact Title'
		   ,'Contact_Profession'=>'Contact Profession'
		   );
 $components_translator=array(
				   'Contact_First_Name'=>'Contact First Name'
				   ,'Contact_Surname'=>'Contact Surname'
				   ,'Contact_Suffix'=>'Contact Suffix'
				   ,'Contact_Salutation'=>'Contact Salutation'
				   
		   );
 
 
 foreach($raw_data as $key=>$value){
   if (array_key_exists($key, $translator)) {
     
     if($key=='Contact_Name_Components'){
       $components=array();
       foreach($value as $component_key => $component_value){
	 if (array_key_exists($component_key, $components_translator)) 
	   $components[$components_translator[$component_key]]=$component_value;

       }
       $data[$translator[$key]]=$components;
       
     }else
       $data[$translator[$key]]=$value;

   }

 }

 
 $data['editor']=$editor;
 $contact->update($data);
 
 $contact->reread();
 if($contact->error_updated){
   $response=array('state'=>200,'action'=>'error','msg'=>$contact->msg_updated);
 }else{
   
   if($contact->updated){

     $updated_data_name_components=array(
			 'Contact_First_Name'=>$contact->data['Contact First Name']
			 ,'Contact_Surname'=>$contact->data['Contact Surname']
			 ,'Contact_Suffix'=>$contact->data['Contact Suffix']
			 ,'Contact_Salutation'=>$contact->data['Contact Salutation']
		
			 );

     $updated_data=array(
			 'Contact_Name'=>$contact->data['Contact Name']
			 ,'Name_Data'=>$updated_data_name_components
			 ,'Contact_Gender'=>$contact->data['Contact Gender']
			 ,'Contact_Title'=>$contact->data['Contact Title']
			 ,'Contact_Profession'=>$contact->data['Contact Profession']
			 );
     


     $response=array('state'=>200,'action'=>'updated','msg'=>$contact->msg_updated,'xhtml_subject'=>$contact->display('card'),'updated_data'=>$updated_data,'updated_data'=>$updated_data_name_components);
   }else{
     $response=array('state'=>200,'action'=>'nochange','msg'=>$contact->msg_updated);
     
   }
   
 }
 
 echo json_encode($response);

}


?>