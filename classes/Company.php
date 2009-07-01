<?
/*
 File: Company.php 

 This file contains the Company Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0*/
include_once('DB_Table.php');
include_once('Contact.php');
include_once('Telecom.php');
include_once('Email.php');
include_once('Address.php');
include_once('Name.php');
/* class: Company
 Class to manage the *Company Dimension* table
*/
class Company extends DB_Table {




  /*
    Constructor: Company
    
    Initializes the class, Search/Load or Create for the data set 
    
    Parameters:
    arg1 -    (optional) Could be the tag for the Search Options or the Company Key for a simple object key search
    arg2 -    (optional) Data used to search or create the object
    
       Returns:
       void
       
        Example:
       (start example)
       // Load data from `Company Dimension` table where  `Company Key`=3
       $key=3;
       $company = New Company($key); 

        // Insert row to `Company Dimension` table
       $data=array();
       $company = New Company('new',$data); 
       

       (end example)

     */
  function Company($arg1=false,$arg2=false) {
 
    $this->table_name='Company';
    $this->ignore_fields=array('Company Key');

     if(is_numeric($arg1)){
       $this->get_data('id',$arg1);
       return ;
     }
     if(preg_match('/^(create|new)/i',$arg1)){
       $this->find($arg2,'create');
       return;
     } if(preg_match('/find/i',$arg1)){
       $this->find($arg2,$arg1);
       return;
     }       
      $this->get_data($arg1,$arg2);
       return ;

 }


  /*
    Method: find
    Find Company with similar data
   
    Returns:
    Key of the Compnay found, if create is found in the options string  returns the new key
   */  
  function find($raw_data,$options){
    
    
    if(isset($raw_data['editor'])){
      foreach($raw_data['editor'] as $key=>$value){

	if(array_key_exists($key,$this->editor))
	  $this->editor[$key]=$value;
		    
      }
    }


  $this->candidate=array();
  $this->found=false;

    $create='';
    $update='';
    if(preg_match('/create/i',$options)){
      $create='create';
    }
    if(preg_match('/update/i',$options)){
      $update='update';
    }

    $address_data=array('Company Address Line 1'=>'','Company Address Town'=>'','Company Address Line 2'=>'','Company Address Line 3'=>'','Company Address Postal Code'=>'','Company Address Country Name'=>'','Company Address Country Primary Division'=>'','Company Address Country Secondary Division'=>'');
    


    if(preg_match('/(from|on|in|at) supplier/',$options)){
      foreach($raw_data as $key=>$val){
	$_key=preg_replace('/Supplier /','Company ',$key);
	$raw_data[$_key]=$val;
      }
      $parent='supplier';
    }elseif(preg_match('/(from|on|in|at) customer/',$options)){
      foreach($raw_data as $key=>$val){
	$_key=preg_replace('/Customer /','Company ',$key);
	$raw_data[$_key]=$val;
      }
      $parent='customer';
    }else{

      $parent='none';
    }


    

    foreach($raw_data as $key=>$value){

      if(array_key_exists($key,$address_data))
	$address_data[$key]=$value; 
    }

    if($raw_data['Company Name']==''){
      $raw_data['Company Name']=_('Unknown Name');
    }


    $contact=new Contact("find in company",$raw_data);
    foreach($contact->candidate as $key=>$val){
      if(isset($this->candidate[$key]))
	$this->candidate[$key]+=$val;
      else
	$this->candidate[$key]=$val;
    }

    

/*     $email=new Email("find in company",$data['Company Main Plain Email']); */
/*     foreach($email->candidate as $key=>$val){ */
/*       if(isset($this->candidate[$key])) */
/* 	$this->candidate[$key]+=$val; */
/*       else */
/* 	$this->candidate[$key]=$val; */
/*     } */
    
/*     $address=new Address("find in company ",$address_data); */
    
/*     foreach($address->candidate as $key=>$val){ */
/*       if(isset($this->candidate[$key])) */
/* 	$this->candidate[$key]+=$val; */
/*       else */
/* 	$this->candidate[$key]=$val; */
/*     } */

    
/*     $telephone=new Telecom("find in company",$data['Company Main Telephone']); */
/*     foreach($telephone->candidate as $key=>$val){ */
/*       if(isset($this->candidate[$key])) */
/* 	$this->candidate[$key]+=$val; */
/*       else */
/* 	$this->candidate[$key]=$val; */
/*     } */




    //addnow we have a list of  candidates, from this list make another list of companies
    $candidate_companies=array();
    //     print "Contact Candidates:";
    //  print_r($this->candidate);
   

    foreach($this->candidate as $contact_key=>$score){
      $_contact=new Contact($contact_key);
      $company_key=$_contact->data['Contact Company Key'];
      if($company_key){
      // print "---- $company_key\n";
      if(isset($candidate_companies[$company_key]))
	$candidate_companies[$company_key]+=$score;
      else
	$candidate_companies[$company_key]=$score;
      }
    }

    //    print "Company Candidates:";
    //print_r($candidate_companies);
    if(!empty($candidate_companies)){
      arsort($candidate_companies);
      foreach($candidate_companies as $key=>$val){
	//print "*$key $val\n";
	if($val>=200){
	  $this->found=true;
	  $this->found_key=$key;
	  break;
	}
      }
      
    }
/*     if(count($this->candidate)>0){ */
/*       //  print "Contact candidates\n"; */
/*       // print_r($this->candidate); */
/*     } */
/*     if(count($candidate_companies)>0){ */
/*       //print "Company candidates\n"; */
/*       //print_r($candidate_companies); */
/*     } */
    

    if($this->found )
      $this->get_data('id',$this->found_key);

    if($create){

      

      //      print "Company Found:".$this->found." ".$this->found_key."   \nContact Found:".$contact->found." ".$contact->found_key."  \n";
      if(!$contact->found and $this->found){
	// try to find again the contact now that we now the company
	$contact=new Contact("find in company ".$this->found_key,$raw_data);
	
	$this->candidate=array();
	foreach($contact->candidate as $key=>$val){
	  if(isset($this->candidate[$key]))
	    $this->candidate[$key]+=$val;
	  else
	    $this->candidate[$key]=$val;
	}
	

      }

      // if($this->found)
      //	print "Company founded ".$this->found_key."  \n";


    // there are 4 cases
    if(!$contact->found and !$this->found){

      $this->new_contact=true;
      $this->create($raw_data,$address_data);

    }elseif(!$contact->found and $this->found){


      $this->get_data('id',$this->found_key);
      //print_r($this->card());
      // Create contact
      $contact=new Contact("find in company create",$raw_data);
      $this->data['Company Main Contact Name']=$contact->display('name');
      $this->data['Company Main Contact Key']=$contact->id;
      $contact->add_company(array(
				  'Company Key'=>$this->id
				  ));
      

      $this->update_address($address_data);
      $this->update($raw_data);

    }elseif($contact->found and !$this->found){

      if($contact->data['Contact Company Key']){
	 $this->get_data('id',$contact->data['Contact Company Key']);
	 // print_r($this->card());
	 $this->update_address($address_data);
	 $this->update($raw_data);
      }else{
	
	$this->create($raw_data,$address_data,'use contact '.$contact->id);
	
      }
       

    }else{
      // update 
      //print "Updatinf company and contact\n";
      
      
      $this->get_data('id',$this->found_key);
      //print_r($this->card());
      $this->update_address($address_data);
      $this->update($raw_data);

    }
   

    }

    

    

  }

  function get($key,$arg1=false){
    //  print $key."xxxxxxxx";
    
    if(array_key_exists($key,$this->data))
      return $this->data[$key];

    switch($key){
    case('departments'):
      if(!isset($this->departments))
	$this->load('departments');
      return $this->departments;
      break;
    case('department'):
      if(!isset($this->departments))
	$this->load('departments');
      if(is_numeric($arg1)){
	if(isset($this->departments[$arg1]))
	  return $this->departments[$arg1];
	else
	  return false;
      }
      if(is_string($arg1)){
	foreach($this->departments as $department){
	  if($department['company department code']==$arg1)
	    return $department;
	}
	return false;
      }
      
      
    }
   
     $_key=ucfirst($key);
    if(isset($this->data[$_key]))
      return $this->data[$_key];
    print "Error $key not found in get from address\n";

    return false;

  }


  function get_data($tipo,$id){
    $sql=sprintf("select * from `Company Dimension` where `Company Key`=%d",$id);
    // print $sql;
    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->id=$this->data['Company Key'];
    }
  }
  
  
  function create($raw_data,$raw_address_data=array(),$options=''){
    

    
    $this->data=$this->base_data();
    foreach($raw_data as $key=>$value){
      if(array_key_exists($key,$this->data)){
	$this->data[$key]=_trim($value);
      }
    }
    
    
    $this->data['Company File As']=$this->file_as($this->data['Company Name']);
    $this->data['Company ID']=$this->get_id();
    
    $use_contact=0;
    if(preg_match('/use contact \d+/',$options)){
      $use_contact=preg_replace('/use contact /','',$options);
    }
      
    if($use_contact){
      $contact=new contact($use_contact);
      $contact->update(array('Contact Name'=>$this->data['Company Main Contact Name']));
    }else{
      $contact=new Contact("find in company create",$raw_data);
     
    }

      $this->data['Company Main Contact Name']=$contact->display('name');
    $this->data['Company Main Contact Key']=$contact->id;
   

    if(email::wrong_email($this->data['Company Main Plain Email']))
      $this->data['Company Main Plain Email']='';
    
    
    if($this->data['Company Main Plain Email']!=''){
       
       $email_data['Email']=$this->data['Company Main Plain Email'];
       $email_data['Email Contact Name']=$this->data['Company Main Contact Name'];
       $email=new Email("find in company create",$email_data);
       if(!$email->error){
	 $this->data['Company Main Plain Email']=$email->display('plain');
	 $this->data['Company Main XHTML Email']=$email->display('xhtml');
	 $this->data['Company Main Email Key']=$email->id;
       }else{
	 $this->data['Company Main Plain Email']='';
	 $this->data['Company Main XHTML Email']='';
	 $this->data['Company Main Email Key']='';
       }
    }else{
      $this->data['Company Main Plain Email']='';
      $this->data['Company Main XHTML Email']='';
      $this->data['Company Main Email Key']='';
      
    }

        $address_data=array('Company Address Line 1'=>'','Company Address Town'=>'','Company Address Line 2'=>'','Company Address Line 3'=>'','Company Address Postal Code'=>'','Company Address Country Name'=>'','Company Address Country Primary Division'=>'','Company Address Country Secondary Division'=>'');
     foreach($raw_address_data as $key=>$value){
       if(array_key_exists($key,$address_data))
	 $address_data[$key]=$value; 
     }
     
         



     $address=new Address("find in company create",$address_data);
     if($address->error){
       exit("find_company: address found");
     }

    $this->data['Company Main Address Key']=$address->id;
    $this->data['Company Main XHTML Address']=$address->display('xhtml');
    $this->data['Company Main Plain Address']=$address->display('plain');
    $this->data['Company Main Country Key']=$address->data['Address Country Key'];
    $this->data['Company Main Country']=$address->data['Address Country Name'];
    $this->data['Company Main Location']=$address->display('location');


    if($this->data['Company Main Telephone']!=''){
      $telephone=new Telecom("find in company create country code ".$address->data['Address Country Code'],$this->data['Company Main Telephone']);

      if(!$telephone->error){
	//Collect data about telecom found
	

	$this->data['Company Main Plain Telephone']=$telephone->display('plain');
	$this->data['Company Main Telephone']=$telephone->display('number');
	$this->data['Company Main Telephone Key']=$telephone->id; 
      }else{
	$this->data['Company Main Plain Telephone']='';
	$this->data['Company Main Telephone']='';
	$this->data['Company Main Telephone Key']=''; 
      }
    }else{
      	$this->data['Company Main Plain Telephone']='';
	$this->data['Company Main Telephone']='';
	$this->data['Company Main Telephone Key']=''; 

    }

    if($this->data['Company Main FAX']!=''){
      $telephone=new Telecom("find in company create country code ".$address->data['Address Country Code'],$this->data['Company Main FAX']);
      
      if(!$telephone->error){
	$this->data['Company Main Plain FAX']=$telephone->display('plain');
	$this->data['Company Main FAX']=$telephone->display('number');
	$this->data['Company Main FAX Key']=$telephone->id; 
      }else{
	$this->data['Company Main Plain FAX']='';
	$this->data['Company Main FAX']='';
	$this->data['Company Main FAX Key']=''; 
      }
	//  print_r($this->data);
      // print_r($telephone);exit;
    }else{
      	$this->data['Company Main Plain FAX']='';
	$this->data['Company Main FAX']='';
	$this->data['Company Main FAX Key']=''; 
      
    }

    $keys='';
    $values='';
    foreach($this->data as $key=>$value){
      $keys.=",`".$key."`";
      $values.=','.prepare_mysql($value,false);
    }
    $values=preg_replace('/^,/','',$values);
    $keys=preg_replace('/^,/','',$keys);

    $sql="insert into `Company Dimension` ($keys) values ($values)";
    // print "$sql\n";
    
    if(mysql_query($sql)){
      $this->id = mysql_insert_id();
      $this->get_data('id',$this->id);


      
      $note=_('Company Created');
      $details=_('Company Created');
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
	      ,prepare_mysql('created')
	      ,prepare_mysql($this->table_name)
	      ,prepare_mysql($this->id)
	      ,"''"
	      ,"''"
	      ,0
	      ,prepare_mysql($note)
	      ,prepare_mysql($details)
	      ,prepare_mysql($author)
	      ,prepare_mysql($this->editor['Author Key'])
		  );
 // print $sql;
 // exit;
   mysql_query($sql);
      
      
      $contact->add_company(array(
				  'Company Key'=>$this->id
				  ));
      
           
      $contact->add_email(array(
				'Email Key'=>$this->data['Company Main Email Key']
				,'Email Type'=>'Work'
				));


      //  print_r($this->data);

      $contact->add_tel(array(
			      'Telecom Key'=>$this->data['Company Main Telephone Key']
			      ,'Telecom Type'=>'Work Telephone'
			      ));
                 
      $contact->add_tel(array(
			      'Telecom Key'=>$this->data['Company Main FAX Key']
			      ,'Telecom Type'=>'Office Fax'
			      ));




      $contact->add_address(array(
				  'Address Key'=>$this->data['Company Main Address Key']
				  ,'Address Type'=>'Work'
				  ,'Address Function'=>'Contact'
				  ,'Address Description'=>'Work Contact Address'
				  ));

     

      //create the DB bridges
      $this->add_email($this->data['Company Main Email Key']);
      $this->add_tel(array(
			   'Telecom Key'=>$this->data['Company Main Telephone Key']
			   ,'Telecom Type'=>'Office Telephone'
			   ));
      
      $this->add_tel(array(
			   'Telecom Key'=>$this->data['Company Main FAX Key']
			   ,'Telecom Type'=>'Office Fax'
			   ));
      $this->add_address(array(
			       'Address Key'=>$this->data['Company Main Address Key']
			       ,'Address Type'=>'Office'
			       ,'Address Function'=>'Contact'
			       ,'Address Description'=>'Company Address'
			       ));

/*       if($this->data['Company Main Telephone Key']){ */
/* 	$sql=sprintf("insert into `Telecom Bridge` (`Telecom Key`,`Subject Type`,`Subject Key`,`Telecom Description`,`Is Main`,`Is Active`,`Telecom Type`) values (%d,'Company',%d,%s,'Yes','Yes')" */
/* 		     ,$this->data['Company Main Telephone Key'] */
/* 		     ,$this->id */
/* 		     ,prepare_mysql('Company Telephone') */
/* 		     ,prepare_mysql('Office') */
/* 		     ); */
/* 	mysql_query($sql); */
/*       } */
      

      $this->get_data('id',$this->id);
     }else{
       print "Error, company can not be created";exit;
     }

  }




  function get_id(){
    
    $sql="select max(`Company ID`)  as company_id from `Company Dimension`";
    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      if(!preg_match('/\d*/',_trim($row['company_id']),$match))
	$match[0]=1;
      $right_side=$match[0];
      $number=(double) $right_side;
      $number++;
      $id=$number;
    }else{
      $id=1;
    }  
    return $id;
  }

  function load($key=''){
    switch($key){
   
    case('contacts'):
    case('contact'):
      $this->contact=new Contact($this->data['contact_id']);
      if($this->contact->id){
	$this->contact->load('telecoms');
	$this->contact->load('contacts');
      }

    }
    
  }




/*Function: update_field_switcher
  */

protected function update_field_switcher($field,$value,$options=''){


 

  switch($field){
  case('Company Main Contact Name'):
    $contact=new Contact($this->data['Company Main Contact Key']);
    $contact=update('Contact Name',$value);
    $this->data['Company Main Contact Name']=$contact->display('Name');
    $sql=sprintf("update `Company Main Contact Name`=%s where `Company Key`=%d",prepare_mysql($this->data['Company Main Contact Name']),$this->id);
    mysql_query($sql);
    break;


  case('Company Name'):
    $this->update_Company_Name($value,$options);
    break;
  case('Company Main Plain Email'):
    
    if($value==''){
      $contact=new Contact($this->data['Company Main Contact Key']);
      $contact->del_email('principal');

    }elseif(!email::wrong_email($value)){
      $contact=new Contact($this->data['Company Main Contact Key']);
      $email_data=array('Email'=>$value);
      $contact->add_email($email_data);// <- will update company
    }
    
    break;  
  case('Company Main Telephone'):
    // check if plain numbers are the same

    //print "Updation company telecom\n NEW value $value\n";
      
    $contact=new Contact($this->data['Company Main Contact Key']);
    $tel_data=Telecom::parse_number($value);
    //print "tel data\n";
    //print_r($tel_data);
    $plain_tel=Telecom::plain_number($tel_data);
   
    //    print "plain: $plain_tel\n";

    if($plain_tel!=$this->data['Company Main Plain Telephone']){
      
      if($plain_tel==''){
	// Remove main telephone
	$contact->del_tel('principal');
      }else{
	$tel_data=array(
			'Telecom Raw Number'=>$value
			,'Telecom Type'=>'Telephone'
			);
	$contact->add_tel($tel_data,$options.' principal');
      }

    }
    break;  
  case('Company Main FAX'):
    

    $contact=new Contact($this->data['Company Main Contact Key']);
    $tel_data=Telecom::parse_number($value);
    $plain_tel=Telecom::plain_number($tel_data);
    if($plain_tel!=$this->data['Company Main Plain FAX']){
    if($plain_tel==''){
	// Remove main telephone
	$contact->del_tel('principal fax');
      }else{

    $tel_data=array(
		    'Telecom Raw Number'=>$value
		    ,'Telecom Type'=>'Fax'
		    );
    $contact->add_tel($tel_data,$options.' principal');
    }
    }
    break;  
  

  default:
    $this->update_field($field,$value,$options);
  }
  
}

 /*
    Function: update_address
    Update/Create address
   */
  private function update_address($data,$type='Work'){



    $address_data=false;
    if(array_empty($data))
      return;
    
    //  print_r($data);
    foreach($data as $key=>$val){
      $_key=preg_replace('/Company/','Contact',$key);
      $_data[$_key]=$val;
    }


    $address=new address('find in contact '.$this->data['Company Main Contact Key'].' '.$type.' create',$_data);
    if($address->id){

      $address_data=array(
			  'Address Key'=>$address->id
			  ,'Address Type'=>'Work'
			  ,'Address Function'=>'Contact'
			  ,'Address Description'=>'Work Contact Address'
			  );
      $contact=new Contact($this->data['Company Main Contact Key']);
      $contact->add_address($address_data,"principal");
    }

  }


/* Function:update_Company_Name
   Updates the company name

 */
private function update_Company_Name($value,$options){
  if($value==''){
  $this->new=false;
    $this->msg.=" Company name should have a vbalue";
    $this->error=true;
    if(preg_match('/exit on errors/',$options))
      exit($this->msg);
    return false;
  }
  $this->data['Company Name']=$value;
  $this->data['Company File As']=$this->file_as($this->data['Company Name']);
  $sql=sprintf("update `Company Dimension` set `Company name`=%s,`Company File As`=%s where `Company Key`=%d "
	       ,prepare_mysql($this->data['Company Name'])
	       ,prepare_mysql($this->data['Company File As'])
	       ,$this->id);
  mysql_query($sql);
  $affected=mysql_affected_rows();
  
  if($affected==-1){
    $this->msg.=' '._('Company Name can not be updated')."\n";
    $this->error=true;
    return;
  }elseif($affected==0){
    //$this->msg.=' '._('Same value as the old record');
    
  }else{
    $this->msg.=' '._('Record updated')."\n";
    $this->updated=true;

    // update childen and parents

    $sql=sprintf("update `Contact Dimension` set `Contact Company Name`=%s where `Company Key`=%d  "
		 ,prepare_mysql($this->data['Company Name'])
		 ,$this->id);
    mysql_query($sql);
    
  }  
  
}





  function add_page($page_data,$args='principal'){
    $url=$data['page url'];
    if(isset($data['page_type']) and preg_match('/internal/i',$data['page_type']))
      $email_type='Internal';
    else
      $email_type='External';
    $url_data=array(
		     'page description'=>'',
		     'page url'=>$url,
		     'page type'=>$email_type,
		     'page validated'=>0,
		     'page verified'=>0,
		    );
    
    if(isset($data['page description']) and $data['page description']!='')
      $url_data['page description']=$data['page description'];
    $page=new page('new',$url_data);
   if($email->new){
     
     $sql=sprintf("insert into  `Company Web Site Bridge` (`Page Key`, `Company Key`) values (%d,%d)  ",$page->id,$this->id);
     mysql_query($sql);
     if(preg_match('/principal/i',$args)){
     $sql=sprintf("update `Company Dimension` set `Company Main XHTML Page`=%s where `Contact Key`=%d",prepare_mysql($page->display('html')),$this->id);
     // print "$sql\n";
     mysql_query($sql);
     }

     $this->add_page=true;
   }else{
     $this->add_page=false;
     
   }

  }

  function add_email($email_data,$args='principal'){
    
    if(is_numeric($email_data)){
      $tmp=$email_data;
      unset($email_data);
      $email_data['Email Key']=$tmp;
    }
      

    if(preg_match('/from main contact/',$args)){
       $contact=new contact($this->data['Company Main Contact Key']);
       $email=new Email($contact->data['Contact Main Email Key']);
    }elseif(isset($email_data['Email Key'])){
      $email=new Email($email_data['Email Key']);
    }elseif(is_array($email_data)){
      $email=new Email('find in company create',$email_data['Email Key']);
      
    }else
       return;
    
    if($email->id){
      
      	$sql=sprintf("insert into `Email Bridge` (`Email Key`,`Subject Type`,`Subject Key`,`Email Description`,`Is Main`,`Is Active`) values (%d,'Company',%d,%s,'Yes','Yes')  ON DUPLICATE KEY UPDATE `Email Description`=%s   "
		     ,$email->id
		     ,$this->id
		     ,prepare_mysql('Company Email')
		     ,prepare_mysql('Company Email')
		     );
	mysql_query($sql);


	if(preg_match('/principal/i',$args)){

	   $sql=sprintf("update `Email Bridge`  set `Is Main`='No' where `Subject Type`='Company' and  `Subject Key`=%d  and `Email Key`!=%d",
		  $this->id
		 ,$email->id
		  );
   mysql_query($sql);
     $sql=sprintf("update `Email Bridge`  set `Is Main`='Yes' where `Subject Type`='Company' and  `Subject Key`=%d  and `Email Key`=%d",
		  $this->id
		  ,$email->id
		  );
     mysql_query($sql);

	  $sql=sprintf("update `Company Dimension` set `Company Main XHTML Email`=%s ,`Company Main Plain Email`=%s,`Company Main Email Key`=%d where `Company Key`=%d"		       
		       ,prepare_mysql($email->display('html'))
		       ,prepare_mysql($email->display('plain'))
		       ,$email->id
		       ,$this->id
		       );
	  mysql_query($sql);
	}



    }
  
    
 
    
    
    
  }

/* Method: del_email
  Delete the email from Company
  
  Delete telecom record  this record to the Contact


  Parameter:
  $args -     string  options
 */
 function del_email($args='principal'){

   if(preg_match('/principal/i',$args)){
       
     

       $sql=sprintf("delete `Email Bridge`  where `Subject Type`='Company' and  `Subject Key`=%d  and `Telecom Key`=%d",
		    $this->id
		    ,$this->data['Company Main Email Key']
		    );
       mysql_query($sql);
       $sql=sprintf("update `Company Dimension` set `Company Main XHTML Email`='' `Company Main Plain Email`='' , `Company Main Email Key`=''  where `Company Key`=%d"
		    ,$this->id
		    );

       mysql_query($sql);

       
	

   }

 }
/* Method: add_tel
  Add/Update an telecom to the Company
  
  Search for an telecom record maching the telecom data *$data* if not found create a ne telecom record then add this record to the Contact


  Parameter:
  $data  -    array   telecom data
  $args -     string  options
  Return: 
  integer telecom key of the added/updated telecom
 */


 function add_tel($data,$args='principal'){

   if(is_numeric($data)){
     $tmp=$data;
     unset($data);
     $data['Telecom Key']=$tmp;
   }
   
   if(isset($data['Telecom Key'])){
     $telecom=new Telecom('id',$data['Telecom Key']);
   }else{
     if(!isset($data['Telecom Original Country Key']) or !$data['Telecom Original Country Key'])
       $data['Telecom Original Country Key']=$this->data['Company Main Country Key'];
     $telecom=new telecom('find in company create',$data);
   }
   if($telecom->id){
     if($telecom->data['Telecom Technology Type']=='Mobile'){
	 $telecom_tipo='Company Main Telephone';
	 $telecom_tipo_plain='Company Main Plain Telephone';
       }else{
	 if(preg_match('/fax/i',$data['Telecom Type'])){
	 $telecom_tipo='Company Main FAX';
	 $telecom_tipo_plain='Company Main Plain FAX';
	 
	 }else{
	   $telecom_tipo='Company Main Telephone';
	   $telecom_tipo_plain='Company Main Plain Telephone';
	 }
       }

 if(!isset($data['Telecom Description']))
       $data['Telecom Description']=$telecom_tipo;

     // add bridge
  
     
     $sql=sprintf("insert into  `Telecom Bridge` (`Telecom Key`, `Subject Key`,`Subject Type`,`Telecom Type`,`Is Main`,`Telecom Description`) values (%d,%d,'Company',%s,%s,%s)  ON DUPLICATE KEY UPDATE `Telecom Type`=%s,`Telecom Description`=%s "
		  ,$telecom->id
		  ,$this->id
		  ,prepare_mysql($data['Telecom Type'])
		  ,prepare_mysql(preg_match('/principal/i',$args)?'Yes':'No')
		  ,prepare_mysql($data['Telecom Description'],false)
		  ,prepare_mysql($data['Telecom Type'])
		  ,prepare_mysql($data['Telecom Description'],false)

		  );
     mysql_query($sql);

     if(preg_match('/principal/i',$args)){
     
       
 $sql=sprintf("update `Telecom Bridge`  set `Is Main`='No' where `Subject Type`='Company' and  `Subject Key`=%d  and `Telecom Key`!=%d",
		  $this->id
		 ,$telecom->id
		  );
   mysql_query($sql);
     $sql=sprintf("update `Telecom Bridge`  set `Is Main`='Yes' where `Subject Type`='Company' and  `Subject Key`=%d  and `Telecom Key`=%d",
		  $this->id
		  ,$telecom->id
		  );
     mysql_query($sql);

       	 $sql=sprintf("update `Company Dimension` set `%s`=%s and `%s`=%s and   where `Company Key`=%d"
		      ,$telecom_tipo
		      ,prepare_mysql($telecom->display('html'))
		      ,$telecom_tipo_plain
		      ,$telecom->display('plain')
		      ,$this->id
		      );
	 mysql_query($sql);
       
     }
   }

 }
 /* Method: del_tel
  Delete an telecom  in Contact
  
  Delete telecom record  this record to the Contact


  Parameter:
  $args -     string  options
 */
 function del_tel($args='principal'){

   if(preg_match('/principal/i',$args)){
       
       if(preg_match('/fax/i',$args)){
	 $tel_key=$this->data['Company Main FAX Key'];
	 $telecom_tipo='Company Main FAX';
	 $telecom_tipo_key='Company Main FAX Key';
	 $telecom_tipo_plain='Company Main Plain FAX';
       }else{
	 $tel_key=$this->data['Company Main Telephone Key'];
	 $telecom_tipo='Company Main Telephone';
	 $telecom_tipo_key='Company Main Telephone Key';
	 $telecom_tipo_plain='Company Main Plain Telephone';
       }

       $sql=sprintf("delete `Telecom Bridge`  where `Subject Type`='Company' and  `Subject Key`=%d  and `Telecom Key`=%d",
		    $this->id
		    ,$tel_key
		    );
       mysql_query($sql);
       $sql=sprintf("update `Company Dimension` set `%s`='' `%s`='' , `%s`=''  where `Company Key`=%d"
		      ,$telecom_tipo
		      ,$telecom_tipo_plain
		      ,$telecom_tipo_key
		      ,$this->id
		      );
	 //print "$sql\n";
	 mysql_query($sql);

      




   }
 }
    
/* Method: add_address
  Add/Update an address to the Company
  
  Search for an address record maching the address data *$data* if not found create a ne address record then add this record to the Contact


  Parameter:
  $data  -    array   address data
  $args -     string  options
  Return: 
  integer address key of the added/updated address
 */

function add_address($data,$args='principal'){

  if(!$data)
    $address=new address('fuzzy all');
  elseif(is_numeric($data) )
    $address=new address('fuzzy country',$data);
  elseif(is_array($data)){

    if(isset($data['Address Key'])){

      $address=new address('id',$data['Address Key']);
    }    else
      $address=new address('find in company create',$data);

  }else
    $address=new address('fuzzy all');

  if(!$address->id){
    
    return;
    
  }
  
  $address_id=$address->id;
  $sql=sprintf("insert into `Address Bridge` (`Subject Type`,`Subject Key`,`Address Key`,`Address Type`,`Address Function`,`Address Description`) values ('Company',%d,%d,%s,%s,%s)  ON DUPLICATE KEY UPDATE `Address Type`=%s,`Address Function`=%s,`Address Description`=%s ",
	       $this->id,
	       $address_id
	       ,prepare_mysql($data['Address Type'])
	       ,prepare_mysql($data['Address Function'])
	       ,prepare_mysql($data['Address Description'])
	        ,prepare_mysql($data['Address Type'])
	       ,prepare_mysql($data['Address Function'])
	       ,prepare_mysql($data['Address Description'])
	       );
 
  if(!mysql_query($sql)){
    print("$sql\n error can no create company address bridge");
  }
 if(preg_match('/principal/i',$args)){


    $sql=sprintf("update `Address Bridge`  set `Is Main`='No' where `Subject Type`='Company' and  `Subject Key`=%d  and `Address Key`!=%d",
		  $this->id
		 ,$address->id
		  );
   mysql_query($sql);
     $sql=sprintf("update `Address Bridge`  set `Is Main`='Yes' where `Subject Type`='Company' and  `Subject Key`=%d  and `Address Key`=%d",
		  $this->id
		  ,$address->id
		  );
     mysql_query($sql);


 
   //    $plain_address=_trim($address->data['Street Number'].' '.$address->data['Street Name'].' '.$address->data['Address Town'].' '.$address->data['Postal Code'].' '.$address->data['Address Country Code']);
     $sql=sprintf("update `Company Dimension`  set `Company Main Plain Address`=%s,`Company Main Address Key`=%s ,`Company main Location`=%s ,`Company Main XHTML Address`=%s , `Company Main Country Key`=%d,`Company Main Country`=%s,`Company Main Country Code`=%s where `Company Key`=%d ",
		  prepare_mysql($address->display('plain')),
		  prepare_mysql($address_id),
		  prepare_mysql($address->data['Address Location']),
		  prepare_mysql($address->display('html')),
		  $address->data['Address Country Key'],
		  prepare_mysql($address->data['Address Country Name']),
		  prepare_mysql($address->data['Address Country Code']),
		  $this->id
		  );
 }
}


function add_contact($data,$args='principal'){
  
  if(is_numeric($data))
    $contact=new Contact('id',$data);
  else
    $contact=new Contact('new',$data);
  
  if(!$contact->id)
    exit("can not find contact");

    $sql=sprintf("insert into  `Contact Bridge` (`Contact Key`, `Subject Key`,`Is Main`) values (%d,%d,%s)  "
		   ,$contact->id
		   ,$this->id
		   ,prepare_mysql(preg_match('/principal/i',$args)?'Yes':'No')
		   );
      mysql_query($sql);
      
      if(preg_match('/principal/i',$args)){
	
	$sql=sprintf("update `Company Bridge`  set `Is Main`='No' where `Subject Type`='Company' and  `Subject Key`=%d  and `Contact Key`!=%d",
		     $this->id
		     ,$contact->id
		     );
	mysql_query($sql);
	$sql=sprintf("update `Company Bridge`  set `Is Main`='Yes' where `Subject Type`='Company' and  `Subject Key`=%d  and `Contact Key`=%d",
		     $this->id
		     ,$contact->id
		     );
	mysql_query($sql);





	$sql=sprintf("update `Contact Dimension` set  `Contact Company Name`=%s,`Contact Company Key`=%s,`Contact Main Address Key`=%d,`Contact Main XHTML Address`=%s,`Contact Main Plain Address`=%s,`Contact Main Country Key`=%d,`Contact Main Country`=%s ,`Contact Main Location`=%s,`Contact Main Telephone`=%s,`Contact Main Plain Telephone`=%,`Contact Main Telephone Key`=%d,`Contact Main FAX`=%s , `Contact Main Plain FAX`=%s,`Contact Main FAX Key`=%d  where `Contact Key`=%d"
		     ,prepare_mysql($this->data['Company Name'])

		     ,$this->id
		     ,$this->data['Company Main Address Key']
		     ,prepare_mysql($this->data['Company Main XHTML Address'])
		     ,prepare_mysql($this->data['Company Main Plain Address'])
		     ,$this->data['Company Main Country Key']
		     ,prepare_mysql($this->data['Company Main Country'])
		     ,prepare_mysql($this->data['Company Main Location'])
		     ,prepare_mysql($this->data['Company Main Telephone'])
		     ,prepare_mysql($this->data['Company Main Plain Telephone'])
		     ,$this->data['Company Main Telephone Key']
		     ,prepare_mysql($this->data['Company Main FAX'])
		     ,prepare_mysql($this->data['Company Main Plain FAX'])
		     ,$this->data['Company Main FAX Key']
		     ,$contact->id
		     );
	//	print $this->data['Company Main Address Key']." $sql\n\n";
	if(!mysql_query($sql))
	  exit("$sql\n");
	
      }
      


}

  function create_code($name){
    preg_replace('/[!a-z]/i','',$name);
    preg_replace('/^(the|el|la|les|los|a)\s+/i','',$name);
    preg_replace('/\s+(plc|inc|co|ltd)$/i','',$name);
    preg_split('/\s*/',$name);
    return $name;
  }

   function check_code($name){
    return $name;
  }
  
   /*
     Function: file_as
     Parse company name to be order nicely
     

    */


   function file_as($name){
     $articles_regex='/^(the|el|la|les|los|a)\s+/i';
     if(preg_match($articles_regex,$name,$match)){
       $name=preg_replace($articles_regex,'',$name);
       $article=_trim($match[0]);
       $name.=' '.$article;
     }
     $no_standar_characters_regex='/^[a-z0-9]*/';
     $name=preg_replace($no_standar_characters_regex,'',$name);
     
     
     return $name;
  }

   /*
     function: card
     Returns an array with the contact details
    */
   function card(){


     $card=array(
		 'Company Name'=>$this->data['Company Name']
		 ,'Contacts'=>array()
		 );
     
     $sql=sprintf("select`Contact Key`,`Is Main`  from `Contact Bridge` DB where `Subject Type`='Contact' and `Subject Key`=%d order by `Is Main` desc",$this->id);
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	$contact=new Contact($row['Contact Key']);
	$card['Contacts'][$row['Contact Key']]=$contact->card();
      }
      return $card;
   }

  /*
     function: get_customer_key
     Returns the Customer Key if the company is one
    */
   function get_customer_key(){
     $sql=sprintf("select `Customer Key` from `Customer Dimension` where `Customer Type`='Company' and `Customer Company Key`=%d  ",$this->id);
     //   print "$sql\n";
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       return $row['Customer Key'];
     }
     return false;
   }
}

?>