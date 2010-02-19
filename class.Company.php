<?php
/*
  File: Company.php

  This file contains the Company Class

  About:
  Autor: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2009, Kaktus

  Version 2.0*/
include_once('class.DB_Table.php');
include_once('class.Contact.php');
include_once('class.Telecom.php');
include_once('class.Email.php');
include_once('class.Address.php');
//include_once('Name.php');
/* class: Company
   Class to manage the *Company Dimension* table
*/
class Company extends DB_Table {


  var $candidate_companies=array();
  var $number_candidate_companies=0;
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
    $this->ignore_fields=array(
			       'Company Key'
			       ,'Company Total Parts Profit'
			       ,'Company Total Parts Profit After Storing'
			       ,'Company Total Cost'
			       ,'Company Total Parts Sold Amount'
			       ,'Company 1 Year Acc Parts Profit'

			       );

    if (is_numeric($arg1)) {
      $this->get_data('id',$arg1);
      return ;
    }
    if (preg_match('/^(create|new)/i',$arg1)) {
      $this->find($arg2,'create');
      return;
    }
    if (preg_match('/find/i',$arg1)) {
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
  function find($raw_data,$options) {
    $find_fuzzy=false;

    if (preg_match('/fuzzy/i',$options)) {
      $find_fuzzy='fuzzy';
    }

    //Timer::timing_milestone('start find');

    if (isset($raw_data['editor'])) {
      foreach($raw_data['editor'] as $key=>$value) {

	if (array_key_exists($key,$this->editor))
	  $this->editor[$key]=$value;

      }
    }


    $this->candidate=array();
    $this->found=false;

    $create='';
    $update='';
    if (preg_match('/create/i',$options)) {
      $create='create';
    }
    if (preg_match('/update/i',$options)) {
      $update='update';
    }

    $address_data=array('Company Address Line 1'=>'','Company Address Town'=>'','Company Address Line 2'=>'','Company Address Line 3'=>'','Company Address Postal Code'=>'','Company Address Country Name'=>'','Company Address Country Code'=>'','Company Address Country Primary Division'=>'','Company Address Country Secondary Division'=>'');



    if (preg_match('/(from|on|in|at) supplier/',$options)) {
      foreach($raw_data as $key=>$val) {
	$_key=preg_replace('/Supplier /','Company ',$key);
	$raw_data[$_key]=$val;
      }
      $parent='supplier';
    }

    elseif(preg_match('/(from|on|in|at) customer/',$options)) {
      foreach($raw_data as $key=>$val) {
	if ($key!='Customer Type') {
	  $_key=preg_replace('/Customer /','Company ',$key);
	  $raw_data[$_key]=$val;
	}
      }
      $parent='customer';
    }
    else {

      $parent='none';
    }





    foreach($raw_data as $key=>$value) {

      if (array_key_exists($key,$address_data))
	$address_data[$key]=$value;
    }

    //print_r($address_data);

    if (!isset($raw_data['Company Name']) or $raw_data['Company Name']=='') {
      $raw_data['Company Name']='';
    }
    if (!isset($raw_data['Company Main Contact Name'])) {
      $raw_data['Company Main Contact Name']='';
    }


    //Timer::timing_milestone('begin  find  contact');
    $contact=new Contact("find in company $find_fuzzy ",$raw_data);
    //Timer::timing_milestone('end find contact');
    foreach($contact->candidate as $key=>$val) {
      if (isset($this->candidate[$key]))
	$this->candidate[$key]+=$val;
      else
	$this->candidate[$key]=$val;
    }





    //addnow we have a list of  candidates, from this list make another list of companies
    $this->candidate_companies=array();
    $this->number_candidate_companies=0;
    foreach($this->candidate as $contact_key=>$score) {
      $_contact=new Contact($contact_key);

      $company_key=$_contact->data['Contact Company Key'];
      if ($company_key) {
	// print "---- $company_key\n";
	if (isset($this->candidate_companies[$company_key]))
	  $this->candidate_companies[$company_key]+=$score;
	else
	  $this->candidate_companies[$company_key]=$score;
      }
    }

    /*     $sql=sprintf("select `Company Key` from `Company Dimension` where `Company Name`=%s",prepare_mysql($raw_data['Company Name'])); */
    /*     $res=mysql_query($sql); */
    /*     while($row=mysql_fetch_array($res)){ */
    /*       $score=80; */
    /*       $company_key=$row['Company Key']; */
    /*       if(isset($this->candidate_companies[$company_key])) */
    /* 	$this->candidate_companies[$company_key]+=$score; */
    /*       else */
    /* 	$this->candidate_companies[$company_key]=$score; */
    /*     } */

    //  print "Company candidates berfore name\n";
    //  print_r($this->candidate_companies);


    if ($raw_data['Company Name']!='') {

      $max_score=80;
      $score_plus_for_match=40;


      if($find_fuzzy){

      
	$sql=sprintf("select `Company Key`,damlevlim256(UPPER(%s),UPPER(`Company Name`),8)/LENGTH(`Company Name`) as dist1 from `Company Dimension`   order by dist1  limit 10"
		     ,prepare_mysql($raw_data['Company Name'])
		     
		     );
	$result=mysql_query($sql);
	//   print "$sql\n";
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	  if ($row['dist1']>=1)
	    break;
	  $score=$max_score*pow(1-  $row['dist1'] ,3  );
	  $extra_score=0;
	  $company_key=$row['Company Key'];
	  
	  foreach($this->candidate as $candidate_key=>$candidate_score) {
	    $sql=sprintf("select count(*) matched from `Contact Bridge` where `Contact Key`=%d and `Subject Key`=%d  and `Subject Type`='Company' and `Is Active`='Yes'  "
			 ,$candidate_key
			 ,$company_key
			 );
	    $res=mysql_query($sql);
	    //  print "$sql\n";
	  $match_data=mysql_fetch_array($res);
	  if ($match_data['matched']>0) {
	    //		      print "matched $score $score_plus_for_match \n";
	    $this->candidate[$candidate_key]+=$score_plus_for_match;
	    $extra_score=$score_plus_for_match;
	  }
	  
	  }
	  
		
	  if (isset($this->candidate_companies[$company_key]))
	    $this->candidate_companies[$company_key]+=$score+$extra_score;
	  else
	    $this->candidate_companies[$company_key]=$score+$extra_score;
	}
      }else{
	  
	$sql=sprintf("select `Company Key` from `Company Dimension` where `Company Name`=%s   limit 10"
		     ,prepare_mysql($raw_data['Company Name'])
		     );

	$result=mysql_query($sql);
	//   print "$sql\n";
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

	  $score=$max_score;
	  $extra_score=0;
	  $company_key=$row['Company Key'];
	  
	  foreach($this->candidate as $candidate_key=>$candidate_score) {
	    $sql=sprintf("select count(*) matched from `Contact Bridge` where `Contact Key`=%d and `Subject Key`=%d  and `Subject Type`='Company' and `Is Active`='Yes'  "
			 ,$candidate_key
			 ,$company_key
			 );
	    $res=mysql_query($sql);
	    //  print "$sql\n";
	  $match_data=mysql_fetch_array($res);
	  if ($match_data['matched']>0) {
	    //		      print "matched $score $score_plus_for_match \n";
	    $this->candidate[$candidate_key]+=$score_plus_for_match;
	    $extra_score=$score_plus_for_match;
	  }
	  
	  }
	  
		
	  if (isset($this->candidate_companies[$company_key]))
	    $this->candidate_companies[$company_key]+=$score+$extra_score;
	  else
	    $this->candidate_companies[$company_key]=$score+$extra_score;
	}
	
	
      }

    }

	

    if (!empty($this->candidate_companies)) {
      arsort($this->candidate_companies);
      foreach($this->candidate_companies as $key=>$val) {
	//print "*$key $val\n";
	if ($val>=200) {
	  $this->found=true;
	  $this->found_key=$key;
	  break;
	}
      }

    }
	
    $this->number_candidate_companies=count($this->candidate_companies);

  /*   	if(count($this->candidate)>0){ */
/*     	  print "Contact candidates\n"; */
/*     	  print_r($this->candidate); */
/*     	} */
/*     	if(count($this->candidate_companies)>0){ */
/*     	  print "Company candidates\n"; */
/*     	  print_r($this->candidate_companies); */
/*     	} */
	

    if ($this->found )
      $this->get_data('id',$this->found_key);




    if ($create or $update) {

      if ($raw_data['Company Main Contact Name']=='' and $this->found and !$contact->found) {
	//	print "Fuzzy cpontact try to get the name of the most likile cadidate";
	foreach($contact->candidate as $key=>$value) {
	  if ($value>100) {
	    //print "value $value ".$this->found_key."\n";
	    $contact=new Contact($key);
	    $contact->set_scope('Company',$this->found_key);
	    //print_r($contact);
	    if ($contact->associated_with_scope) {
	      //	print "--------------------------\n";
	      $raw_data['Company Main Contact Name']=$contact->display('name');
	      $contact->found=true;;
	      $contact->found_key=$contact->id;
	      break;
	    }
	  }

	}
      }



      //
      //    print "$create $update   Company Found:".$this->found." ".$this->found_key."   \nContact Found:".$contact->found." ".$contact->found_key."  \n";

      // exit;


      if (!$contact->found and $this->found) {

	// try to find again the contact now that we now the company
	$contact=new Contact("find in company ".$this->found_key,$raw_data);

	$this->candidate=array();
	foreach($contact->candidate as $key=>$val) {
	  if (isset($this->candidate[$key]))
	    $this->candidate[$key]+=$val;
	  else
	    $this->candidate[$key]=$val;
	}


      }

      // if($this->found)a
      //   print "Company founded ".$this->found_key."  \n";

      //      print "Company founded ".$this->found_key."  \n";

      if ($create and !$this->found) {

	if ($contact->found) {

	  if ($contact->data['Contact Company Key']) {
	    $this->get_data('id',$contact->data['Contact Company Key']);
	    // print_r($this->card());
	    $this->update_address($this->data['Company Main Address Key'],$address_data);
	    $this->update($raw_data);
	  } else {

	    $this->create($raw_data,$address_data,'use contact '.$contact->id);

	  }

	} else {
	  $this->new_contact=true;

	  $this->create($raw_data,$address_data);

	}

      }


      if ($update and $this->found) {

	     

	if ($contact->found) {
		   
	  $contact->set_scope('Company',$this->id);
	  // print_r($contact);

	  $update_data=array(
			     'Contact Name'=>$raw_data['Company Main Contact Name'],

			     'Contact Main Telephone'=>$raw_data['Company Main Telephone']
			     );
	  if (isset($raw_data['Company Main FAX']))
	    $update_data['Contact Main FAX']=$raw_data['Company Main FAX'];
	  if (isset($raw_data['Company Mobil']))
	    $update_data['Contact Main Mobile']=$raw_data['Company Mobile'];


	  $contact->update($update_data);
	  $this->add_contact($contact->id,'principal');

	  $this->get_data('id',$this->found_key);
	  //print_r($this->card());
	  //print_r($address_data);

	  //$this->update_address($this->data['Company Main Address Key'],$address_data);//Change in contact normal data shold be here
                   

	  $address_data['editor']=$this->editor;
	  $address=new Address("find in company ".$this->id." create",$address_data);
	  $contact->associate_address(array(
					    'Address Key'=>$address->id
					    ,'Address Type'=>array('Work')
					    ,'Address Function'=>array('Contact')
							
					    ),'principal');
	  $this->associate_address(array(
					 'Address Key'=>$address->id
					 ,'Address Type'=>array('Office')
					 ,'Address Function'=>array('Contact')

					 ),'principal');

		    
		

	  $this->update($raw_data);
	  //		    print "shoooooooooooolddd be updated\n\n\n\n\n";

	} else {
		 
	  $this->get_data('id',$this->found_key);
	  //print_r($this->card());
	  // Create contact
	  $contact=new Contact("find in company create",$raw_data);
	  $contact->editor=$this->editor;
	  $this->data['Company Main Contact Name']=$contact->display('name');
	  $this->data['Company Main Contact Key']=$contact->id;
	  $contact->add_company(array(
				      'Company Key'=>$this->id
				      ));


	  //$this->update_address($this->data['Company Main Address Key'],$address_data);
                    

	  $address_data['editor']=$this->editor;
	  $address=new Address("find in company ".$this->id." create",$address_data);
	  $contact->associate_address(array(
					    'Address Key'=>$address->id
					    ,'Address Type'=>array('Work')
					    ,'Address Function'=>array('Contact')
							
					    ),'principal');
	  $this->associate_address(array(
					 'Address Key'=>$address->id
					 ,'Address Type'=>array('Office')
					 ,'Address Function'=>array('Contact')

					 ),'principal');



	  $this->update($raw_data);

	}

      }




    }





  }

  function get($key,$arg1=false) {
    //  print $key."xxxxxxxx";

    if (array_key_exists($key,$this->data))
      return $this->data[$key];

    switch ($key) {
    case("Name"):
      if (preg_match('/addslashes/i',$arg1))
	return addslashes ($this->data['Company Name']);
      return     $this->data['Company Name'];
      break;
    case("ID"):
    case("Formated ID"):

      return $this->get_formated_id();



      break;

    case('departments'):
      if (!isset($this->departments))
	$this->load('departments');
      return $this->departments;
      break;
    case('department'):
      if (!isset($this->departments))
	$this->load('departments');
      if (is_numeric($arg1)) {
	if (isset($this->departments[$arg1]))
	  return $this->departments[$arg1];
	else
	  return false;
      }
      if (is_string($arg1)) {
	foreach($this->departments as $department) {
	  if ($department['company department code']==$arg1)
	    return $department;
	}
	return false;
      }


    }

    $_key=ucfirst($key);
    if (isset($this->data[$_key]))
      return $this->data[$_key];
    print "Error $key not found in get from address\n";

    return false;

  }


  function get_data($tipo,$id) {
    $sql=sprintf("select * from `Company Dimension` where `Company Key`=%d",$id);
    // print $sql;
    $result=mysql_query($sql);
    if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
      $this->id=$this->data['Company Key'];
    }
  }


  function create($raw_data,$raw_address_data=array(),$options='') {
    //print $options."\n";
    //print_r($raw_data);

    $this->data=$this->base_data();
    foreach($raw_data as $key=>$value) {
      if (array_key_exists($key,$this->data)) {
	$this->data[$key]=_trim($value);
      }
    }



    $extra_mobile_key=false;

    $this->data['Company File As']=$this->file_as($this->data['Company Name']);


    $use_contact=0;
    if (preg_match('/use contact \d+/',$options)) {
      $use_contact=preg_replace('/use contact /','',$options);
    }

    if ($use_contact) {
      $contact=new contact($use_contact);
      $contact->editor=$this->editor;
      $contact->update(array('Contact Name'=>$this->data['Company Main Contact Name']));
    } else {

      $contact=new Contact("find in company create",$raw_data);
      if (!$contact->new) {
	print_r($contact);
	print_r($contact->get_company_key());

	exit("can not add contant to company\n");
      }
    }

    $this->data['Company Main Contact Name']=$contact->display('name');
    $this->data['Company Main Contact Key']=$contact->id;
    //===delete down
    $address_data=array('Company Address Line 1'=>'','Company Address Town'=>'','Company Address Line 2'=>'','Company Address Line 3'=>'','Company Address Postal Code'=>'','Company Address Country Name'=>'','Company Address Country Code'=>'','Company Address Country Primary Division'=>'','Company Address Country Secondary Division'=>'');
    foreach($raw_address_data as $key=>$value) {
      if (array_key_exists($key,$address_data))
	$address_data[$key]=$value;
    }




    $address_data['editor']=$this->editor;
	
    $address=new Address("find in company create",$address_data);

    //== delete up

    if (email::wrong_email($this->data['Company Main Plain Email']))
      $this->data['Company Main Plain Email']='';


    if ($this->data['Company Main Plain Email']!='') {

      $email_data['Email']=$this->data['Company Main Plain Email'];
      $email_data['Email Contact Name']=$this->data['Company Main Contact Name'];
      $email_data['editor']=$this->editor;
      $email=new Email("find in company create",$email_data);
      //exit;
      if (!$email->error) {
	$this->data['Company Main Plain Email']=$email->display('plain');
	$this->data['Company Main XHTML Email']=$email->display('xhtml');
	$this->data['Company Main Email Key']=$email->id;
      } else {
	$this->data['Company Main Plain Email']='';
	$this->data['Company Main XHTML Email']='';
	$this->data['Company Main Email Key']='';
      }
    } else {
      $this->data['Company Main Plain Email']='';
      $this->data['Company Main XHTML Email']='';
      $this->data['Company Main Email Key']='';

    }

   
    if ($use_contact) {
      if ($address->found) {
	$contact->move_home_to_work_address($address->found_key);
      }
      //print_r($address);




    } else if (!$address->new) {
      //print_r($address);
      print ("Duplicate address: ".$address->display('plain')."\n");
            
	  

      $address_data['editor']=$this->editor;
      $address=new Address("find in company create force",$address_data);


    }
    // print_r($address->data);
    $this->data['Company Main Address Key']=$address->id;
    $this->data['Company Main XHTML Address']=$address->display('xhtml');
    $this->data['Company Main Plain Address']=$address->display('plain');
    $this->data['Company Main Country Key']=$address->data['Address Country Key'];
    $this->data['Company Main Country']=$address->data['Address Country Name'];
    $this->data['Company Main Country Code']=$address->data['Address Country Code'];
    $this->data['Company Main Location']=$address->display('location');





    $Company_Main_Telephone_Key=false;
    $Company_Main_FAX_Key=false;
    $main_telephone_is_mobile=false;
    $extra_mobile_key=false;



    if ($this->data['Company Main Telephone']!='' and $this->data['Company Main FAX']!='') {
      if ($this->data['Company Main Telephone']==$this->data['Company Main FAX']) {
	$this->data['Company Main FAX']='';
      } else {
	$_tel_data=Telecom::parse_number($this->data['Company Main Telephone']);
	$_fax_data=Telecom::parse_number($this->data['Company Main FAX']);
	if ($_tel_data['Telecom Plain Number']==$_fax_data['Telecom Plain Number'])
	  $this->data['Company Main FAX']='';
      }
    }




    if ($this->data['Company Main Telephone']!='') {
      $telephone_data=array();
      $telephone_data['editor']=$this->editor;
      $telephone_data['Telecom Raw Number']=$this->data['Company Main Telephone'];
      $telephone=new Telecom("find in company create country code ".$address->data['Address Country Code'],$telephone_data);
      if (!$telephone->error) {
	$Company_Main_Telephone_Key=$telephone->id;
	if ($telephone->is_mobile())
	  $main_telephone_is_mobile=true;
      }
    }


    if ($this->data['Company Main FAX']!='') {
      $telephone_data=array();
      $telephone_data['editor']=$this->editor;
      $telephone_data['Telecom Raw Number']=$this->data['Company Main FAX'];

      $telephone=new Telecom("find in company create country code ".$address->data['Address Country Code'],$telephone_data);

      if (!$telephone->error) {

	if ($telephone->is_mobile()) {
	  if ($Company_Main_Telephone_Key)
	    $extra_mobile_key=$telephone->id;
	  else {
	    $main_telephone_is_mobile=true;
	    $Company_Main_Telephone_Key=$telephone->id;
	  }
	} else {
	  $Company_Main_FAX_Key=$telephone->id;
	}

      }
    }

    $keys='';
    $values='';
    foreach($this->data as $key=>$value) {
      $keys.=",`".$key."`";
      if (preg_match('/plain|old id/i',$key))
	$print_null=false;
      else
	$print_null=true;
      $values.=','.prepare_mysql($value,$print_null);

    }
    $values=preg_replace('/^,/','',$values);
    $keys=preg_replace('/^,/','',$keys);

    $sql="insert into `Company Dimension` ($keys) values ($values)";
    // print "$sql\n";

    if (mysql_query($sql)) {
      $this->id = mysql_insert_id();
      $this->get_data('id',$this->id);

      //      print_r($this->data);
      $history_data=array(
			  'note'=>_('Company Created')
			  ,'details'=>_trim(_('Company')." \"".$this->data['Company Name']."\"  "._('created'))
			  ,'action'=>'created'
                          );
      $this->add_history($history_data);
      $this->new=true;


      if (_trim($this->data['Company Old ID'])) {
	$sql=sprintf("insert into `Company Old ID Bridge` values (%d,%s)",$this->id,prepare_mysql(_trim($this->data['Company Old ID'])));
	mysql_query($sql);
      }

      //   print "00000000000000000000000000000\n";
      //print_r($this->data);
      $contact->add_company(array(
				  'Company Key'=>$this->id
                                  ),'principal',true);



      $contact->associate_address(array(
					'Address Key'=>$this->data['Company Main Address Key']
					,'Address Type'=>array('Work')
					,'Address Function'=>array('Contact')

					));
      $this->associate_address(array(
				     'Address Key'=>$this->data['Company Main Address Key']
				     ,'Address Type'=>array('Office')
				     ,'Address Function'=>array('Contact')

				     ));






      if ($this->data['Company Main Email Key']) {
               

	$this->add_email($this->data['Company Main Email Key']);

      }




      if ($Company_Main_Telephone_Key) {




	if ($main_telephone_is_mobile) {
	  $contact_telecom_type='Work Mobile';
	  $company_telecom_type='Contact Mobile';
	} else {
	  $contact_telecom_type='Work Telephone';
	  $company_telecom_type='Office Telephone';



	  $sql=sprintf("insert into `Address Telecom Bridge` values (%d,%d)"
		       ,$this->data['Company Main Address Key']
		       ,$Company_Main_Telephone_Key
		       );
	  mysql_query($sql);

	}

	$contact->add_tel(array(
				'Telecom Key'=>$Company_Main_Telephone_Key
				,'Telecom Type'=>$contact_telecom_type
				,'Telecom Is Main'=>'Yes'
				));
	$this->add_tel(array(
			     'Telecom Key'=>$Company_Main_Telephone_Key
			     ,'Telecom Type'=>$company_telecom_type
			     ,'Telecom Is Main'=>'Yes'
			     ));




      }



      if ($extra_mobile_key) {

	$contact->add_tel(array(
				'Telecom Key'=>$extra_mobile_key
				,'Telecom Type'=>'Work Mobile'
				),'');

	$this->add_tel(array(
			     'Telecom Key'=>$extra_mobile_key
			     ,'Telecom Type'=>'Contact Mobile'
			     ),'');

      }



      if ($Company_Main_FAX_Key) {

	$sql=sprintf("insert into `Address Telecom Bridge` values (%d,%d)",$this->data['Company Main Address Key'],$Company_Main_FAX_Key);
	mysql_query($sql);

	$contact->add_tel(array(
				'Telecom Key'=>$Company_Main_FAX_Key
				,'Telecom Type'=>'Office Fax'
				));
	$this->add_tel(array(
			     'Telecom Key'=>$Company_Main_FAX_Key
			     ,'Telecom Type'=>'Office Fax'
			     ));
      }
















      $this->get_data('id',$this->id);
    } else {
      print "Error, company can not be created $sql";
      exit;
    }

  }






  function load($key='',$args) {
    switch ($key) {

    case('Contact List'):
      $sql=sprintf("select `Contact Key`  from `Contact Bridge` where `Subject Type`='Contact' and `Subject Key`=%d",$this->id);
      $res=mysql_query($sql);
      $this->contact_list=array();
      while ($row=mysql_fetch_array($res)) {
	$this->contact_list[$row['Contact Key']]=array('key'=>$row['Contact Key']);
      }

      break;
    case('Main Contact'):
      $this->contact=new Contact($this->data['Company Main Contact Key']);
      if ($this->contact->id) {
	$this->contact->load('telecoms');
	$this->contact->load('contacts');
      }

    }

  }




  /*Function: update_field_switcher
   */

  protected function update_field_switcher($field,$value,$options='') {


    //      print "$field -> $value\n";


    switch ($field) {
    case('Company Main Contact Key'):
      $this->update_main_contact_name($value);
      break;
    case('Company Main Contact Name'):

      break;


    case('Company Name'):
      $this->update_Company_Name($value,$options);
      break;
    case('Company Main Plain Email'):

      if ($value=='') {
	$contact=new Contact($this->data['Company Main Contact Key']);
	$contact->remove_email('principal');

      }
      elseif(!email::wrong_email($value)) {

	     
	$contact=new Contact($this->data['Company Main Contact Key']);
	$contact->editor=$this->editor;
	$email_data=array('Email'=>$value);
                
	$contact->add_email($email_data);// <- will update company
      }

      break;
    case('Company Main Telephone'):
      // check if plain numbers are the same

      // print "Updation company telecom\n NEW value $value\n";

      $contact=new Contact($this->data['Company Main Contact Key']);
      $contact->editor=$this->editor;
      $tel_data=Telecom::parse_number($value);
      $plain_tel=Telecom::plain_number($tel_data);

      //    print "plain: $plain_tel\n";

      if ($plain_tel!=$this->data['Company Main Plain Telephone']) {

	if ($plain_tel=='') {
	  // Remove main telephone
	  $contact->remove_tel('principal');
	} else {
	  $tel_data=array(
			  'Telecom Raw Number'=>$value
			  ,'Telecom Type'=>'Work Telephone'
			  );
	  //print_r($tel_data);
	  $contact->add_tel($tel_data,$options.' principal');
                
	  $this->add_tel(array(
			       'Telecom Key'=>$contact->data['Contact Main Telephone Key']
			       ,'Telecom Type'=>'Office Telephone'
			       ,'Telecom Is Main'=>'Yes'
			       ));


	}

      }
      break;
    case('Company Main FAX'):


      $contact=new Contact($this->data['Company Main Contact Key']);
      $contact->editor=$this->editor;
      $tel_data=Telecom::parse_number($value);
      $plain_tel=Telecom::plain_number($tel_data);
      if ($plain_tel!=$this->data['Company Main Plain FAX']) {
	if ($plain_tel=='') {
	  // Remove main telephone
	  $contact->remove_tel('principal fax');
	} else {

	  $tel_data=array(
			  'Telecom Raw Number'=>$value
			  ,'Telecom Type'=>'Work Fax'
			  );
	  $contact->add_tel($tel_data,$options.' principal');

	  $this->add_tel(array(
			       'Telecom Key'=>$contact->data['Contact Main FAX Key']
			       ,'Telecom Type'=>'Office Fax'
			       ,'Telecom Is Main'=>'Yes'
			       ));



	}
      }
      break;
    case('Company Old ID'):
      $this->update_Company_Old_ID($value,$options);
      break;
    default:
      $base_data=$this->base_data();
      if (array_key_exists($field,$base_data)) {
	$this->update_field($field,$value,$options);
      }



    }

  }

  /*
    Function: update_address
    Update address
  */
  private function update_address($address_key,$data) {

    $address=new Address($address_key);
    if(!$address->id){
      $this->error=true;
      $this->msg='Address to update not associated with Company';
      print $this->msg."\n";
      return;
    }
        
 



    $address_keys=$this->get_address_keys();
    if(!array_key_exists($address->id,$address_keys)){
      $this->error=true;
      $this->msg='Address not associated with company';
      return;
      //    $this->associate_address(array(
      //                              'Address Key'=>$address->id
      //                               ,'Address Type'=>array('Office')
      //                              ,'Address Function'=>array('Contact')

      //         ));
         
        
    }
        
    $address->editor=$this->editor;
    $address->update($data);// Address Object would update the address not normal data;
        

  }


  /* Function:update_Company_Name
     Updates the company name

  */
  private function update_Company_Name($value,$options) {




    if ($value=='') {
      $this->new=false;
      $this->msg.=" Company name should have a value";
      $this->error=true;
      if (preg_match('/exit on errors/',$options))
	exit($this->msg);
      return false;
    }
    $old_value=$this->data['Company Name'];
    $this->data['Company Name']=$value;
    $this->data['Company File As']=$this->file_as($this->data['Company Name']);
    $sql=sprintf("update `Company Dimension` set `Company Name`=%s,`Company File As`=%s where `Company Key`=%d "
		 ,prepare_mysql($this->data['Company Name'])
		 ,prepare_mysql($this->data['Company File As'])
		 ,$this->id);
    mysql_query($sql);
    $affected=mysql_affected_rows();

    if ($affected==-1) {
      $this->msg.=' '._('Company Name can not be updated')."\n";
      $this->error=true;
      return;
    }
    elseif($affected==0) {
      //$this->msg.=' '._('Same value as the old record');

    } else {
      $this->msg.=' '._('Company name updated')."\n";
      $this->msg_updated=_('Company name updated');
      $this->updated=true;
      $this->new_value=$this->data['Company Name'];


      $history_data=array(
			  'note'=>_('Company Name Changed')
			  ,'details'=>_trim(_('Company name chaged').": ".$old_value." -> ".$this->data['Company Name'])
			  ,'indirect_object'=>'Name'
			  );
	    
      $this->add_history($history_data);

      // update childen and parents

      $sql=sprintf("select `Contact Key` from `Contact Dimension` where `Contact Company Key`=%d  ",$this->id);
      $res=mysql_query($sql);
      while ($row=mysql_fetch_array($res)) {
	$contact=new Contact ($row['Contact Key']);
	$contact->editor=$this->editor;
	$contact->update(array('Contact Company Name'=>$this->data['Company Name']));
      }


      $sql=sprintf("select `Supplier Key` from `Supplier Dimension` where `Supplier Company Key`=%d  ",$this->id);
      $res=mysql_query($sql);
      while ($row=mysql_fetch_array($res)) {
	$supplier=new Supplier ($row['Supplier Key']);
	$supplier->editor=$this->editor;
               
	$supplier->update(array('Supplier Name'=>$this->data['Company Name']));
      }

      $sql=sprintf("select `Customer Key` from `Customer Dimension` where `Customer Company Key`=%d  ",$this->id);
      $res=mysql_query($sql);
      while ($row=mysql_fetch_array($res)) {
	$customer=new Customer ($row['Customer Key']);
	if($customer->data['Customer Type']=='Company'){
	  $customer->editor=$this->editor;
	  $customer->update_name($this->data['Company Name']);
	  $customer->update_file_as($this->data['Company File As']);

	}
      }
      mysql_free_result($res);     


    }

  }

  function get_company_old_id(){
    $old_ids=array();
    $sql=sprintf("select `Company Old ID` from `Company Old ID Bridge` where `Company Key`=%d",$this->id);
    $res=mysql_query($sql);
    while($row=mysql_fetch_array($res)){
      $old_ids[$row['Company Old ID']]=$row['Company Old ID'];
    }
    return $old_ids;
    
  }
 


  /* Function:update_Company_Old_ID
     Updates the company old id

  */
  private function update_Company_Old_ID($company_old_id,$options) {
    $company_old_id=_trim($company_old_id);
    if ($company_old_id=='') {
      $this->new=false;
      $this->msg.=" Company Old ID name should have a value";
      $this->error=true;
      if (preg_match('/exit on errors/',$options))
	exit($this->msg);
      return false;
    }

    $old_value=$this->data['Company Old ID'];
    $individual_ids=array();

    
    $individual_ids=$this->get_company_old_id();



    if (array_key_exists($company_old_id, $individual_ids)) {
      $this->msg.=' '._('Company Old ID already in record')."\n";
      $this->warning=true;
      return;
    }

    $sql=sprintf("insert into `Company Old ID Bridge` values (%d,%s)",$this->id,prepare_mysql(_trim($this->data['Company Old ID'])));
    mysql_query($sql);
    
    
    $affected=mysql_affected_rows();

    if ($affected==-1) {
      $this->msg.=' '._('Company Old ID  can not be updated')."\n";
      $this->error=true;
      return;
    }
    elseif($affected==0) {
      //$this->msg.=' '._('Same value as the old record');

    } else {
      $this->msg.=' '._('Record updated')."\n";
      $this->updated=true;



    }

  }



  function update_main_telecom($field,$telecom) {
    if ($field=='Company Main FAX') {
      $field_plain='Company Main Plain FAX';
      $field_key='Company Main FAX Key';
      $old_principal_key=$this->data['Company Main FAX Key'];
      $old_value=$this->data['Company Main FAX']." (Id:".$this->data['Company Main FAX Key'].")";
    } else {
      $field='Company Main Telephone';
      $field_plain='Company Main Plain Telephone';
      $field_key='Company Main Telephone Key';
      $old_principal_key=$this->data['Company Main Telephone Key'];
      $old_value=$this->data['Company Main Telephone']." (Id:".$this->data['Company Main Telephone Key'].")";
    }

    $sql=sprintf("update `Company Dimension` set `%s`=%s , `%s`=%d  , `%s`=%s  where `Company Key`=%d"
		 ,$field
		 ,prepare_mysql($telecom->display('html'))
		 ,$field_key
		 ,$telecom->id
		 ,$field_plain
		 ,prepare_mysql($telecom->display('plain'))
		 ,$this->id
		 );
    mysql_query($sql);

    // print $sql;

    $history_data=array(
			'note'=>$field." "._('Changed')
			,'details'=>$field." "._('changed')." "
			.$old_value." -> ".$telecom->display('html')
			." (Id:"
			.$telecom->id
			.")"
			,'action'=>''
			);
    $this->add_history($history_data);


  }

  function update_email($email_key=false) {

    if (!$email_key)
      return;
    $email=new Email($email_key);
    if (!$email->id)
      return;

    if ($email->id!=$this->data['Company Main Email Key']) {
      $old_value=$this->data['Company Main Email Key'];
      $this->data['Company Main Email Key']=$email->id;
      $this->data['Company Main Plain Email']=$email->display('plain');
      $this->data['Company Main XHTML Email']=$email->display('xhtml');
      $sql=sprintf("update `Company Dimension` set `Company Main Email Key`=%d,`Company Main Plain Email`=%s,`Company Main XHTML Email`=%s where `Company Key`=%d"

		   ,$this->data['Company Main Email Key']
		   ,prepare_mysql($this->data['Company Main Plain Email'])
		   ,prepare_mysql($this->data['Company Main XHTML Email'])
		   ,$this->id
		   );
      if (mysql_query($sql)) {

	$note=_('Email Changed');
	if ($old_value) {
	  $old_email=new Email($old_value);
	  $details=_('Company email changed from')." \"".$old_email->display('plain')."\" "._('to')." \"".$this->data['Company Main Plain Email']."\"";
	} else {
	  $details=_('Company email set to')." \"".$this->data['Company Main Plain Email']."\"";
	}

	$history_data=array(
			    'indirect_object'=>'Email'
			    ,'details'=>$details
			    ,'note'=>$note
			    );
	$this->add_history($history_data);





      } else {
	$this->error=true;

      }



    }
    elseif($email->display('plain')!=$this->data['Company Main Plain Email']) {
      $old_value=$this->data['Company Main Plain Email'];

      $this->data['Company Main Plain Email']=$email->display('plain');
      $this->data['Company Main XHTML Email']=$email->display('xhtml');
      $sql=sprintf("update `Company Dimension` set `Company Main Plain Email`=%s,`Company Main XHTML Email`=%s where `Company Key`=%d"


		   ,prepare_mysql($this->data['Company Main Plain Email'])
		   ,prepare_mysql($this->data['Company Main XHTML Email'])
		   ,$this->id
		   );
      if (mysql_query($sql)) {
	$field='Company Email';
	$note=$field.' '._('Changed');
	$details=$field.' '._('changed from')." \"".$old_value."\" "._('to')." \"".$this->data['Company Main Plain Email']."\"";

	$history_data=array(
			    'indirect_object'=>'Email'
			    ,'details'=>$details
			    ,'note'=>$note
			    );
	$this->add_history($history_data);




      } else {
	$this->error=true;

      }


    }

  }



  function update_address_data($address_key=false) {



    if (!$address_key)
      return;
    $address=new Address($address_key);
    if (!$address->id)
      return;

    if ($address->id!=$this->data['Company Main Address Key']) {
      $old_value=$this->data['Company Main Address Key'];
      $this->data['Company Main Address Key']=$address->id;
      $this->data['Company Main Plain Address']=$address->display('plain');
      $this->data['Company Main XHTML Address']=$address->display('xhtml');

      $this->data['Company Main Country Key']=$address->data['Address Country Key'];
      $this->data['Company Main Country']=$address->data['Address Country Name'];
      $this->data['Company Main Location']=$address->display('location');


      $sql=sprintf("update `Company Dimension` set `Company Main Address Key`=%d,`Company Main Plain Address`=%s,`Company Main XHTML Address`=%s,`Company Main Country`=%s,`Company Main Location`=%s,`Company Main Country Key`=%d where `Company Key`=%d"

		   ,$this->data['Company Main Address Key']
		   ,prepare_mysql($this->data['Company Main Plain Address'])
		   ,prepare_mysql($this->data['Company Main XHTML Address'])
		   ,prepare_mysql($this->data['Company Main Country'])
		   ,prepare_mysql($this->data['Company Main Location'])
		   ,$this->data['Company Main Country Key']
		   ,$this->id
		   );
      //print "XX $address_key $sql\n";
      if (mysql_query($sql)) {

	$note=_('Address Changed');
	if ($old_value) {
	  $old_address=new Address($old_value);
	  $details=_('Company address changed from')." \"".$old_address->display('xhtml')."\" "._('to')." \"".$this->data['Company Main XHTML Address']."\"";
	} else {
	  $details=_('Company address set to')." \"".$this->data['Company Main XHTML Address']."\"";
	}

	$history_data=array(
			    'indirect_object'=>'Address'
			    ,'details'=>$details
			    ,'note'=>$note
			    );
	$this->add_history($history_data);





      } else {
	$this->error=true;

      }



    }
    elseif($address->display('plain')!=$this->data['Company Main Plain Address']
	   or $address->display('location')!=$this->data['Company Main Location']
	   ) {
      $old_value=$this->data['Company Main XHTML Address'];

      // print_r($this->data);
      // print_r($address->data);
      //print $address->display('location');
      //  exit;

      $this->data['Company Main Plain Address']=$address->display('plain');
      $this->data['Company Main XHTML Address']=$address->display('xhtml');
      $this->data['Company Main Country Key']=$address->data['Address Country Key'];
      $this->data['Company Main Country']=$address->data['Address Country Name'];
      $this->data['Company Main Location']=$address->display('location');


      $sql=sprintf("update `Company Dimension` set `Company Main Plain Address`=%s,`Company Main XHTML Address`=%s,`Company Main Country`=%s,`Company Main Location`=%s,`Company Main Country Key`=%d where `Company Key`=%d"


		   ,prepare_mysql($this->data['Company Main Plain Address'])
		   ,prepare_mysql($this->data['Company Main XHTML Address'])
		   ,prepare_mysql($this->data['Company Main Country'])
		   ,prepare_mysql($this->data['Company Main Location'])
		   ,$this->data['Company Main Country Key']
		   ,$this->id
		   );
      if (mysql_query($sql)) {
	$field='Company Address';
	$note=$field.' '._('Changed');
	$details=$field.' '._('changed from')." \"".$old_value."\" "._('to')." \"".$this->data['Company Main XHTML Address']."\"";

	$history_data=array(
			    'indirect_object'=>'Address'
			    ,'details'=>$details
			    ,'note'=>$note
			    );
	$this->add_history($history_data);




      } else {
	$this->error=true;
	exit($sql);
      }


    }

  }





  function add_page($page_data,$args='principal') {
    $url=$data['page url'];
    if (isset($data['page_type']) and preg_match('/internal/i',$data['page_type']))
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

    if (isset($data['page description']) and $data['page description']!='')
      $url_data['page description']=$data['page description'];
    $page=new page('new',$url_data);
    if ($email->new) {

      $sql=sprintf("insert into  `Company Web Site Bridge` (`Page Key`, `Company Key`) values (%d,%d)  ",$page->id,$this->id);
      mysql_query($sql);
      if (preg_match('/principal/i',$args)) {
	$sql=sprintf("update `Company Dimension` set `Company Main XHTML Page`=%s where `Company Key`=%d",prepare_mysql($page->display('html')),$this->id);
	// print "$sql\n";
	mysql_query($sql);
      }

      $this->add_page=true;
    } else {
      $this->add_page=false;

    }

  }

  function add_email($email_data,$args='principal') {
    $this->updated=false;
    $this->new_email=0;
    if (is_numeric($email_data)) {
      $tmp=$email_data;
      unset($email_data);
      $email_data['Email Key']=$tmp;
    }


    if (preg_match('/from main contact/',$args)) {
      $contact=new contact($this->data['Company Main Contact Key']);
      $email=new Email($contact->data['Contact Main Email Key']);
    }
    elseif(isset($email_data['Email Key'])) {
      $email=new Email($email_data['Email Key']);
    }
    elseif(is_array($email_data)) {
      $email_data['Editor']=$this->editor;
      $email=new Email('find in company create',$email_data['Email Key']);
            
      // pass this email to the contact
            
            

    }
    else
      return 0;



    $this->new_email=$email->id;
    if ($email->id) {

      $contact=new Contact($this->data['Company Main Contact Key']);
      if($contact->id){
	$contact_email_data=array(
				  'Email Key'=>$email->id
				  ,'Email Description'=>'Work'
				  );
	//      print_r($email_data);
	$contact->add_email($contact_email_data);
      }

      $sql=sprintf("insert into `Email Bridge` (`Email Key`,`Subject Type`,`Subject Key`,`Email Description`,`Is Main`,`Is Active`) values (%d,'Company',%d,%s,'Yes','Yes')  ON DUPLICATE KEY UPDATE `Email Description`=%s   "
		   ,$email->id
		   ,$this->id
		   ,prepare_mysql('Company Email')
		   ,prepare_mysql('Company Email')
		   );
      mysql_query($sql);


      if (preg_match('/principal/i',$args)) {

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

	//  $sql=sprintf("update `Company Dimension` set `Company Main XHTML Email`=%s ,`Company Main Plain Email`=%s,`Company Main Email Key`=%d where `Company Key`=%d"
	//               ,prepare_mysql($email->display('html'))
	//               ,prepare_mysql($email->display('plain'))
	//               ,$email->id
	//               ,$this->id
	//              );
	//  mysql_query($sql);
                
                
                
                
                
                
      }



    }

    




  }

  /* Method: remove_address
     Delete the address from Company

     Delete telecom record  this record to the Comp[any


     Parameter:
     $args -     string  options
  */
  function remove_address($address_key) {


    if (!$address_key) {
      $address_key=$this->data['Company Main Address Key'];
    }


    $address=new address($address_key);
    if (!$address->id) {
      $this->error=true;
      $this->msg='Wrong address key when trying to remove it';
      $this->msg_updated='Wrong address key when trying to remove it';
    }

    $address->set_scope('Company',$this->id);

    if ( $address->associated_with_scope) {
      $this->updated=true;
      $sql=sprintf("delete from `Address Bridge`  where `Subject Type`='Company' and  `Subject Key`=%d  and `Address Key`=%d",
		   $this->id

		   ,$address->id
		   );
      mysql_query($sql);



      $this->update_main_address_key();




    }




  }


  /* Method: remove_email
     Delete the email from Company

     Delete telecom record  this record to the Comp[any


     Parameter:
     $args -     string  options
  */
  function remove_email($email_key) {

    if (!$email_key) {
      $email_key=$this->data['Company Main Email Key'];
    }


    $email=new email($email_key);
    if (!$email->id) {
      $this->error=true;
      $this->msg='Wrong email key when trying to remove it';
      $this->msg_updated='Wrong email key when trying to remove it';
    }

    $email->set_scope('Company',$this->id);
    //	print_r($email);
    if ( $email->associated_with_scope) {

      $sql=sprintf("delete from  `Email Bridge`  where `Subject Type`='Company' and  `Subject Key`=%d  and `Email Key`=%d",
		   $this->id

		   ,$this->data['Company Main Email Key']
		   );
      mysql_query($sql);
	    
      $customer_found_keys=$this->get_customer_keys();
      foreach($customer_found_keys as $customer_found_key) {
	$customer=new Customer($customer_found_key);
	$customer->editor=$this->editor;
	$customer->remove_email($email->id);
      }

	   
      if ($email->id==$this->data['Company Main Email Key']) {
	$sql=sprintf("update `Company Dimension` set `Company Main XHTML Email`='' , `Company Main Plain Email`='' , `Company Main Email Key`=''  where `Company Key`=%d"
		     ,$this->id
		     );

	mysql_query($sql);
      }
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


  function add_tel($data,$args='principal') {

    $principal=false;
    if (preg_match('/not? principal/i',$args) ) {
      $principal=false;

    }
    elseif( preg_match('/principal/i',$args)) {

      $principal=true;
    }

    if (isset($data['Telecom Is Main'])) {
      if ($data['Telecom Is Main']=='Yes')
	$principal=true;
      else
	$principal=false;

    }

    if (is_numeric($data)) {
      $tmp=$data;
      unset($data);
      $data['Telecom Key']=$tmp;
    }

    if (isset($data['Telecom Key'])) {
      $telecom=new Telecom('id',$data['Telecom Key']);
    } else {
      $contact=new Contact($this->data['Company Main Contact Key']);
      if (!$contact->id)
	return;
      $contact->add_tel($data,$args);
      return;

    }
    if ($telecom->id) {



      if (!isset($data['Telecom Type'])  )
	$data['Telecom Type']='Office Telephone';


      if (preg_match('/fax/i',$data['Telecom Type'])) {
	$field='Company Main FAX';
	$old_principal_key=$this->data['Company Main FAX Key'];
      } else {
	$field='Company Main Telephone';
	$old_principal_key=$this->data['Company Main Telephone Key'];
      }


      // print "addinf tel P:$principal O:$old_principal_key $old_value N:".$telecom->id."\n";

      if ($principal and $old_principal_key!=$telecom->id) {

	$sql=sprintf("update `Telecom Bridge`  set `Is Main`='No' where `Subject Type`='Company' and  `Subject Key`=%d and `Telecom Type`=%s ",
		     $this->id
		     ,$telecom->id
		     ,prepare_mysql($data['Telecom Type'])
		     );
	mysql_query($sql);

	$this->update_main_telecom($field,$telecom);

      }


      $sql=sprintf("insert into  `Telecom Bridge` (`Telecom Key`, `Subject Key`,`Subject Type`,`Telecom Type`,`Is Main`) values (%d,%d,'Company',%s,%s)  ON DUPLICATE KEY UPDATE `Telecom Type`=%s ,`Is Main`=%s "
		   ,$telecom->id
		   ,$this->id
		   ,prepare_mysql($data['Telecom Type'])
		   ,prepare_mysql($principal?'Yes':'No')
		   ,prepare_mysql($data['Telecom Type'])
		   ,prepare_mysql($principal?'Yes':'No')
		   );
      mysql_query($sql);
      //    print "$sql\n";

    }

  }
  /* Method: remove_tel
     Delete an telecom  in Contact

     Delete telecom record  this record to the Contact


     Parameter:
     $args -     string  options
  */
  function remove_tel($args='principal') {

    if (preg_match('/principal/i',$args)) {

      if (preg_match('/fax/i',$args)) {
	$tel_key=$this->data['Company Main FAX Key'];
	$telecom_tipo='Company Main FAX';
	$telecom_tipo_key='Company Main FAX Key';
	$telecom_tipo_plain='Company Main Plain FAX';
      } else {
	$tel_key=$this->data['Company Main Telephone Key'];
	$telecom_tipo='Company Main Telephone';
	$telecom_tipo_key='Company Main Telephone Key';
	$telecom_tipo_plain='Company Main Plain Telephone';
      }

      $sql=sprintf("delete from `Telecom Bridge`  where `Subject Type`='Company' and  `Subject Key`=%d  and `Telecom Key`=%d",
		   $this->id
		   ,$tel_key
		   );
      mysql_query($sql);
      $sql=sprintf("update `Company Dimension` set `%s`='' ,`%s`='' , `%s`=''  where `Company Key`=%d"
		   ,$telecom_tipo
		   ,$telecom_tipo_plain
		   ,$telecom_tipo_key
		   ,$this->id
		   );
      //print "$sql\n";
      mysql_query($sql);






    }
  }

  /* Method: associate_address
     Associate an address to the Company

    
  */

  function associate_address($data,$args='') {

    //      if (!$data)
    //         $address=new address('fuzzy all');
    //    elseif(is_numeric($data) )
    //     $address=new address('fuzzy country',$data);
    //      elseif(is_array($data)) {
    //          $data['editor']=$this->editor;
    //          if (isset($data['Address Key'])) {
    //
    //                $address=new address('id',$data['Address Key']);
    //            }    else {
    //
    //                $address=new address('find in company '.$this->id.' create',$data);
    //
    //            }
    //        }
    //       else
    //           $address=new address('fuzzy all');
    //
    //       if (!$address->id) {
    //
    //            return;
    //
    //        }



    //     if ($address->new)
    //         $this->added_address_key=$address->id;
    //     else
    //         $this->added_address_key=false;
    //
    //       if ($address->updated or $address->new)
    //           $this->updated=true;

    $principal=preg_match('/principal/i',$args);
    if(count($this->get_address_keys())==0)
      $principal=true;


    $address_key=$data['Address Key'];
    
    
    

    foreach($data['Address Type'] as $type) {
      foreach($data['Address Function'] as $function) {
	$sql=sprintf("insert into `Address Bridge` (`Subject Type`,`Subject Key`,`Address Key`,`Address Type`,`Address Function`) values ('Company',%d,%d,%s,%s)  ON DUPLICATE KEY UPDATE `Is Active`='Yes'",
		     $this->id,
		     $address_key
		     ,prepare_mysql($type)
		     ,prepare_mysql($function)
		     ,prepare_mysql($type)
		     ,prepare_mysql($function)
		     );

	//	      print $sql;

	if (!mysql_query($sql))
	  print("$sql\n error can no create company address bridge");

	if (mysql_affected_rows() )
	  $this->updated=true;

      }
    }


    if ($principal) {


      $sql=sprintf("update `Address Bridge`  set `Is Main`='No' where `Subject Type`='Company' and  `Subject Key`=%d  and `Address Key`!=%d",
		   $this->id
		   ,$address_key
		   );
      mysql_query($sql);
      $sql=sprintf("update `Address Bridge`  set `Is Main`='Yes' where `Subject Type`='Company' and  `Subject Key`=%d  and `Address Key`=%d",
		   $this->id
		   ,$address_key
		   );
      mysql_query($sql);

     
      $this->update_address_data($address_key);

    }

  }


  function add_contact($data,$args='principal') {
    //  print "addcontact contact  to ".$this->id."  ($args)\n";
    // print_r($data);

    if (is_numeric($data))
      $contact=new Contact('id',$data);
    else
      $contact=new Contact('find in company create',$data);

    if (!$contact->id) {
      $this->error=true;
      $this->msg="can not find/create contact";

    }

    $principal=false;
    if (preg_match('/not? principal|no_principal/',$args) ) {
      $principal=false;
    }
    elseif( preg_match('/principal/',$args)) {
      $principal=true;
    }



    $sql=sprintf("insert into  `Contact Bridge` (`Contact Key`, `Subject Type`,`Subject Key`,`Is Main`) values (%d,%s,%d,%s) on duplicate key update `Is Main`=%s "
		 ,$contact->id
		 ,prepare_mysql('Company')
		 ,$this->id
		 ,prepare_mysql($principal?'Yes':'No')
		 ,prepare_mysql($principal?'Yes':'No')
		 );
    mysql_query($sql);
    $affected=mysql_affected_rows();




    //	print "----------------> $principal <-----\n";


    if ($principal) {

      $sql=sprintf("update `Contact Bridge`  set `Is Main`='No' where `Subject Type`='Company' and  `Subject Key`=%d  and `Contact Key`!=%d",
		   $this->id
		   ,$contact->id
		   );
      mysql_query($sql);
      $sql=sprintf("update `Contact Bridge`  set `Is Main`='Yes' where `Subject Type`='Company' and  `Subject Key`=%d  and `Contact Key`=%d",
		   $this->id
		   ,$contact->id
		   );
      mysql_query($sql);


      $sql=sprintf("update `Contact Dimension` set  `Contact Company Name`=%s,`Contact Company Key`=%s where `Contact Key`=%d"
		   ,prepare_mysql($this->data['Company Name'])
		   ,$this->id
		   ,$contact->id
		   );
      if (!mysql_query($sql))
	exit("$sql\n");

      $sql=sprintf("update `Company Dimension` set  `Company Main Contact Name`=%s,`Company Main Contact Key`=%s where `Company Key`=%d"
		   ,prepare_mysql($contact->display('name'))
                         
		   ,$contact->id
		   ,$this->id
		   );
      if (!mysql_query($sql))
	exit("$sql\n");



    }


    $editor_data=$this->get_editor_data();
    if ($affected==1 and  !$this->new) {


      if ($principal) {

	$sql=sprintf("insert into `History Dimension`  (`Subject`,`Subject Key`,`Action`,`Direct Object`,`Direct Object Key`,`Preposition`,`Indirect Object`,`Indirect Object Key`,`History Abstract`,`History Details`,`History Date`,`Author Name`,`Author Key`) values (%s,%d,%s,%s,%d,%s,%s,%d,%s,%s,%s,%s,%s)   ",

		     prepare_mysql($editor_data['subject']),
		     $editor_data['subject_key'],
		     prepare_mysql('associated'),
		     prepare_mysql('Contact'),
		     $contact->id,
		     "'to'",
		     "'Company'",
		     $this->id,
		     prepare_mysql(_('Contact associated with Company as Main Contact').' ('.$contact->display('Short Name').'/'.$this->data['Company Name'].')'),
		     prepare_mysql(_('Contact associated with Company as Main Contact').' ('.$contact->display('Name').'/'.$this->data['Company Name'].')'),

		     prepare_mysql($editor_data['date']),
		     prepare_mysql($editor_data['author']),
		     $editor_data['author_key']
		     );
	mysql_query($sql);

      } else {


	$sql=sprintf("insert into `History Dimension`  (`Subject`,`Subject Key`,`Action`,`Direct Object`,`Direct Object Key`,`Preposition`,`Indirect Object`,`Indirect Object Key`,`History Abstract`,`History Details`,`History Date`,`Author Name`,`Author Key`) values (%s,%d,%s,%s,%d,%s,%s,%d,%s,%s,%s,%s,%s)   ",

		     prepare_mysql($editor_data['subject']),
		     $editor_data['subject_key'],
		     prepare_mysql('associated'),
		     prepare_mysql('Contact'),
		     $contact->id,
		     "'to'",
		     "'Company'",
		     $this->id,
		     prepare_mysql(_('Contact associated with Company').' ('.$contact->display('Short Name').'/'.$this->data['Company Name'].')'),
		     prepare_mysql(_('Contact associated with Company').' ('.$contact->display('Name').'/'.$this->data['Company Name'].')'),

		     prepare_mysql($editor_data['date']),
		     prepare_mysql($editor_data['author']),
		     $editor_data['author_key']
		     );
	mysql_query($sql);


      }
    }








  }


  function remove_contact($data,$args='') {
    // print "removing contact  to ".$this->id."  ($args)";
    //print_r($data);
    if (is_numeric($data))
      $contact=new Contact('id',$data);
    else
      $contact=new Contact('find in company create',$data);

    if (!$contact->id) {
      $this->error=true;
      $this->msg="can not find/create contact";

    }

    $contacts_keys=$this->get_contact_keys('only active');


    if (!in_array($contact->id,$contacts_keys)) {
      $this->msg_updated.=_('Can not remove contact because it in not associated with the company').".";
      return;
    }

    $contact->set_scope('Company',$this->id);

    // print "Main ".$contact->data['Contact Is Main']."\n";

    if ($contact->data['Contact Is Main']=='Yes') {
      if (count($contacts_keys)==1) {
	$fuzzy_contact=new Contact('create anonymous');
	$fuzzy_contact->editor=$this->editor;
	$fuzzy_contact->add_company(array('Company Key'=>$this->id));
	$fuzzy_contact->add_address(array(
					  'Address Key'=>$this->data['Company Main Address Key']
					  ,'Address Type'=>array('Work')
					  ,'Address Function'=>array('Contact')
					  ));
	if ($this->data['Company Main Telephone Key']) {
	  $fuzzy_contact->add_tel(array(
					'Telecom Key'=>$this->data['Company Main Telephone Key']
					,'Telecom Type'=>'Office Telephone'
					));
	}
	if ($this->data['Company Main FAX Key']) {
	  $fuzzy_contact->add_tel(array(
					'Telecom Key'=>$this->data['Company Main FAX Key']
					,'Telecom Type'=>'Office Fax'
					));
	}

	$customers_keys= get_customer_keys();
	foreach($customers_keys as $customer_key) {
	  $customer=new Customer($customer_key);
	  if ($customer->data['Customer Main Contact Key']==$contact->id) {
	    $customer->update_main_contact_key($fuzzy_contact->id);
	  }


	}


      } else {
	$this->error=true;
	$msg=_('can not remove main contact please set another contact as the main one first').".";
	$this->msg.=$msg;
	$this->msg_updated.=$msg;
      }


    }

    if (preg_match('/(remove|delete) from (db|database)/i',$args)) {

      $sql=sprintf("delete from  `Contact Bridge` where `Contact Key`=%s and  `Subject Type`='Company' and `Subject Key`=%d "
		   ,$contact->id
		   ,$this->id
		   );
      mysql_query($sql);

      $history_data=array(
			  'note'=>_('Company-Contact Relation deleted permanently')
			  ,'details'=>_trim(_('Company')." ".$this->data['Company Name'].' ('.$this->get_formated_id().') '._('relation with contact')." ".$contact->display('name')." (".$contact->get_formated_id().") "._('has been deleted permenentely') )
			  ,'action'=>'deleted'
                          );
      $this->add_history($history_data);


    } else {
      $sql=sprintf("update  `Contact Bridge` set `Is Active`='No' where `Contact Key`=%s and  `Subject Type`='Company' and `Subject Key`=%d "
		   ,$contact->id
		   ,$this->id
		   );

      mysql_query($sql);
      $history_data=array(
			  'note'=>_('Company-Contact Relation disassociated')
			  ,'details'=>_trim(_('Company')." ".$this->data['Company Name'].' ('.$this->get_formated_id().') '._('relation with contact')." ".$contact->display('name')." (".$contact->get_formated_id().") "._('has been disassociate') )
			  ,'action'=>'disassociate'
                          );
      $this->add_history($history_data);
    }



  }



  function create_code($name) {
    preg_replace('/[!a-z]/i','',$name);
    preg_replace('/^(the|el|la|les|los|a)\s+/i','',$name);
    preg_replace('/\s+(plc|inc|co|ltd)$/i','',$name);
    preg_split('/\s*/',$name);
    return $name;
  }

  function check_code($name) {
    return $name;
  }

  /*
    Function: file_as
    Parse company name to be order nicely


  */


  function file_as($name) {
    $articles_regex='/^(the|el|la|les|los|a)\s+/i';
    if (preg_match($articles_regex,$name,$match)) {
      $name=preg_replace($articles_regex,'',$name);
      $article=_trim($match[0]);
      $name.=' '.$article;
    }
    $no_standar_characters_regex='/^[^a-zA-Z0-9\!\?]*/i';
    $name=preg_replace($no_standar_characters_regex,'',$name);


    return $name;
  }

  /*
    function: card
    Returns an array with the contact details
  */
  function card() {


    $card=array(
		'Company Name'=>$this->data['Company Name']
		,'Contacts'=>array()
		);

    $sql=sprintf("select`Contact Key`,`Is Main`  from `Contact Bridge` DB where `Subject Type`='Contact' and `Subject Key`=%d order by `Is Main` desc",$this->id);
    $result=mysql_query($sql);
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
      $contact=new Contact($row['Contact Key']);
      $card['Contacts'][$row['Contact Key']]=$contact->card();
    }
    return $card;
  }

  /*
    function: get_customer_key
    Returns the Customer Key if the company is one

  */
  function get_customer_keys($args='') {
    $sql=sprintf("select `Customer Key` from `Customer Dimension` where  `Customer Type`='Company' and `Customer Company Key`=%d  ",$this->id);
    //	print_r($sql);

    $customer_keys=array();
    $result=mysql_query($sql);
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
      $customer_keys[$row['Customer Key']]= $row['Customer Key'];

    }
    return $customer_keys;
  }

  /*
    function: get_contact_keys
    Returns the Contact Key if the company is one
  */
  function get_contact_keys($args='active only') {
    $extra_args='';
    if (preg_match('/only active|active only/i',$args))
      $extra_args=" and `Is Active`='Yes'";
    if (preg_match('/only main|main only/i',$args))
      $extra_args=" and `Is Main`='Yes'";
    if (preg_match('/only not? active/i',$args))
      $extra_args=" and `Is Active`='No'";
    if (preg_match('/only not? main/i',$args))
      $extra_args=" and `Is Main`='No'";

    $sql=sprintf("select * from `Contact Bridge` where  `Subject Type`='Company' and `Subject Key`=%d %s order by `Is Main` desc  "
		 ,$this->id
		 ,$extra_args
		 );
    $contacts=array();
    $result=mysql_query($sql);
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
      $contacts[$row['Contact Key']]= $row['Contact Key'];
    }
    return $contacts;
  }


  function get_contacts($args='only active') {

    $extra_args='';
    if (preg_match('/only active|active only/i',$args))
      $extra_args=" and `Is Active`='Yes'";
    if (preg_match('/only main|main only/i',$args))
      $extra_args=" and `Is Main`='Yes'";
    if (preg_match('/only not? active/i',$args))
      $extra_args=" and `Is Active`='No'";
    if (preg_match('/only not? main/i',$args))
      $extra_args=" and `Is Main`='No'";





    $sql=sprintf("select CB.`Contact Key` from `Contact Bridge` CB left join `Contact Dimension` C on (CB.`Contact Key`=C.`Contact Key`)
                     where  `Subject Type`='Company' and `Subject Key`=%d %s order by `Is Main`, `Contact File As`  ",$this->id,$extra_args);


    $contacts=array();
    $result=mysql_query($sql);
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
      $contact=new Contact($row['Contact Key']);
      $contact->set_scope('Company',$this->id);
      $contacts[]=$contact;

    }
    return $contacts;
  }



  function get_address_keys() {


    $sql=sprintf("select * from `Address Bridge` CB where   `Subject Type`='Company' and `Subject Key`=%d  group by `Address Key` order by `Is Main` desc  ",$this->id);
    $address_keys=array();
    $result=mysql_query($sql);

    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
           
      $address_keys[$row['Address Key']]= $row['Address Key'];
    }
    return $address_keys;

  }

  function get_addresses() {


    $sql=sprintf("select * from `Address Bridge` CB where   `Subject Type`='Company' and `Subject Key`=%d  group by `Address Key` order by `Is Main` desc  ",$this->id);
    $addresses=array();
    $result=mysql_query($sql);

    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
      $address= new Address($row['Address Key']);
      $address->set_scope('Company',$this->id);
      $addresses[]= $address;
    }
    return $addresses;

  }

  /*function:get_formated_id_link
    Returns formated id_link
  */
  function get_formated_id_link() {

    return sprintf('<a href="company.php?id=%d">%s</a>',$this->id, $this->get_formated_id());

  }


  /*function:get_formated_id
    Returns formated id
  */
  function get_formated_id() {
    global $myconf;
    $sql="select count(*) as num from `Company Dimension`";
    $res=mysql_query($sql);
    $min_number_zeros=$myconf['company_min_number_zeros_id'];
    if ($row=mysql_fetch_array($res)) {
      if (strlen($row['num'])-1>$min_number_zeros)
	$min_number_zeros=strlen($row['num'])-01;
    }
    if (!is_numeric($min_number_zeros))
      $min_number_zeros=4;

    return sprintf("%s%0".$min_number_zeros."d",$myconf['company_id_prefix'], $this->id);

  }
  function get_main_email_key() {
    return $this->data['Company Main Email Key'];
  }
  function get_main_address_key() {
    return $this->data['Company Main Address Key'];
  }


  function update_main_contact_name($contact_key) {

    if (!$contact_key or !is_numeric($contact_key)) {
      $this->error=true;
      return;
    }

    $contact=new Contact($contact_key);
    if (!$contact->id) {
      $this->error=true;
      return;
    }
    // print_r($contact);
    $this->data['Company Main Contact Name']=$contact->display('Name');
    $sql=sprintf("update `Company Dimension` set `Company Main Contact Name`=%s where `Company Key`=%d",prepare_mysql($this->data['Company Main Contact Name']),$this->id);
    //print "$sql\n";
    mysql_query($sql);



  }

  function display($tipo='card') {

    global $myconf;

    switch ($tipo) {
    case('card'):


      $email_label="E:";
      $tel_label="T:";
      $fax_label="F:";
      $mobile_label="M:";
      $contact_label="C:";

      $email='';
      $tel='';
      $fax='';
      $mobile='';
      $contact='';
      $name=sprintf('<span class="name">%s</span>',$this->data['Company Name']);
      if ($this->data['Company Main Contact Name'])
	$contact=sprintf('<span class="name">%s %s</span><br/>',$contact_label,$this->data['Company Main Contact Name']);


      if ($this->data['Company Main XHTML Email'])
	$email=sprintf('<span class="email">%s</span><br/>',$this->data['Company Main XHTML Email']);
      if ($this->data['Company Main Telephone'])
	$tel=sprintf('<span class="tel">%s %s</span><br/>',$tel_label,$this->data['Company Main Telephone']);
      if ($this->data['Company Main FAX'])
	$fax=sprintf('<span class="fax">%s %s</span><br/>',$fax_label,$this->data['Company Main FAX']);


      $address=sprintf('<span class="mobile">%s</span>',$this->data['Company Main XHTML Address']);

      $card=sprintf('<div class="contact_card">%s <div  class="tels">%s %s %s %s</div><div  class="address">%s</div> </div>'
		    ,$name
		    ,$contact
		    ,$email
		    ,$tel
		    ,$fax

		    ,$address
		    );

      return $card;

    }

  }
  /*

   */

  function update_main_address_key() {
    $sql=sprintf("select `Address Key` from `Address Bridge` where `Subject Type`='Company' and `Subject Key`=%d and `Is Main`='Yes' ",$this->id);
    $res=mysql_query($sql);
    //  print $sql;
    if ($row=mysql_fetch_array($res)) {
      $address_key=$row['Address Key'];

      $address= new Address($address_key);
      if ($address->id) {
	$sql=sprintf("update `Company Dimension` set `Company Main XHTML Address`=%s , `Company Main Plain Address`=%s , `Company Main Address Key`=%d ,`Company Main Country Key`=%d ,`Company Main Country`=%s,`Company Main Location`=%s  where `Company Key`=%d"
		     ,prepare_mysql($address->display('xhtml'))
		     ,prepare_mysql($address->display('plain'))
		     ,$address->id
		     ,$address->data['Address Country Key']
		     ,prepare_mysql($address->data['Address Country Name'])
		     ,prepare_mysql($address->display('location'))
		     ,$this->id
		     );
	//	print $sql;
	mysql_query($sql);
	return;
      }

    }



    $sql=sprintf("select `Address Key` from `Address Bridge` where `Subject Type`='Company' and `Subject Key`=%d  ",$this->id);
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res)) {
      $address_key=$row['Address Key'];
      $address= new Address($address_key);
      if ($address->id) {
	$sql=sprintf("update `Company Dimension` set `Company Main XHTML Address`=%s , `Company Main Plain Address`=%s , `Company Main Address Key`=%d ,`Company Main Country Key`=%d ,`Company Main Country`=%s,`Company Main Location`=%s  where `Company Key`=%d"
		     ,prepare_mysql($address->display('xhtml'))
		     ,prepare_mysql($address->display('plain'))
		     ,$address->id
		     ,$address->data['Address Country Key']
		     ,prepare_mysql($address->data['Address Country Name'])
		     ,prepare_mysql($address->display('location'))
		     ,$this->id
		     );
	mysql_query($sql);
	return;
      }

    }




    $this->set_main_address_as_unknown();


  }


  function set_main_address_as_unknown() {
    $sql=sprintf("update `Company Dimension` set `Company Main XHTML Address`='' , `Company Main Plain Address`=''  ,`Company Main Country Key`='244' ,`Company Main Country`='Unknown',`Company Main Location`='' ,`Company Main Address Key`=0  where `Company Key`=%d"
		 ,$this->id
		 );

    mysql_query($sql);

  }



  function set_scope($raw_scope='',$scope_key=0) {
    $scope='Unknown';
    $raw_scope=_trim($raw_scope);
    if (preg_match('/^customers?$/i',$raw_scope)) {
      $scope='Customer';

    } else if (preg_match('/^(supplier)$/i',$raw_scope)) {
      $scope='Supplier';
    }

    $this->scope=$scope;
    $this->scope_key=$scope_key;
    $this->load_metadata();

  }
  function load_metadata() {





    $where_scope=sprintf(' and `Subject Type`=%s',prepare_mysql($this->scope));

    $where_scope_key='';
    if ($this->scope_key)
      $where_scope_key=sprintf(' and `Subject Key`=%d',$this->scope_key);




    $sql=sprintf("select * from `Company Bridge` where `Company Key`=%d %s  %s  order by `Is Main` desc"
		 ,$this->id
		 ,$where_scope
		 ,$where_scope_key
		 );
    $res=mysql_query($sql);



    $this->data['Company Is Main']='No';
    $this->data['Company Is Active']='No';

    $this->associated_with_scope=false;
    while ($row=mysql_fetch_array($res)) {
      $this->associated_with_scope=true;

      $this->data['Company Is Main']=$row['Is Main'];
      $this->data['Company Is Active']=$row['Is Active'];

    }


  }

  function add_area($data) {
    include_once('class.CompanyArea.php');
    $data['Company Key']=$this->id;
    $data['editor']=$this->editor;
    $area=new CompanyArea('find',$data,'create');
    if ($area->id) {
      $this->updated=true;

    }
  }


  function add_department($data) {
    include_once('class.CompanyDepartment.php');
    $data['Company Key']=$this->id;
    $data['editor']=$this->editor;
    $department=new CompanyDepartment('find',$data,'create');
    if ($department->id) {
      $this->updated=true;

    }
  }


  /*
    function:update_contact
  */
  function update_contact($contact_key=false) {


    $this->associated=false;
    if (!$contact_key)
      return;
    $contact=new contact($contact_key);
    if (!$contact->id) {
      $this->msg='contact not found';
      return;

    }


    $old_contact_key=$this->data['Company Main Contact Key'];

    if ($old_contact_key  and $old_contact_key!=$contact_key   ) {
      $this->remove_contact();
    }
    if ($old_contact_key!=$contact_key) {
      $sql=sprintf("insert into `Contact Bridge` values (%d,'Company',%d,'Yes','Yes')",
		   $contact->id,
		   $this->id
		   );
      mysql_query($sql);
      if (mysql_affected_rows()) {
	$this->associated=true;

      }

    }

    $old_name=$this->data['Company Main Contact Name'];
    if ($old_name!=$contact->display('name')) {



      $this->data['Company Main Contact Key']=$contact->id;
      $this->data['Company Main Contact Name']=$contact->display('name');
      $sql=sprintf("update `Company Dimension` set `Company Main Contact Key`=%d,`Company Main Contact Name`=%s where `Company Key`=%d"

		   ,$this->data['Company Main Contact Key']
		   ,prepare_mysql($this->data['Company Main Contact Name'])
		   ,$this->id
		   );
      mysql_query($sql);
      print $sql;


      $this->updated=true;






      $note=_('Company contact name changed');
      if ($old_contact_key) {
	$details=_('Company contact name changed from')." \"".$old_name."\" "._('to')." \"".$this->data['Company Main Contact Name']."\"";
      } else {
	$details=_('Company contact set to')." \"".$this->data['Company Main Contact Name']."\"";
      }

      $history_data=array(
			  'indirect_object'=>'Company Main Contact Name'

			  ,'details'=>$details
			  ,'note'=>$note
			  ,'action'=>'edited'
                          );
      $this->add_history($history_data);

    }


    if ($this->associated) {
      $note=_('Contact name changed');
      $details=_('Contact')." ".$contact->display('name')." (".$contact->get_formated_id_link().") "._('associated with Company:')." ".$this->data['Company Name']." (".$this->get_formated_id_link().")";
      $history_data=array(
			  'indirect_object'=>'Company Name'
			  ,'details'=>$details
			  ,'note'=>$note
			  ,'action'=>'edited',
			  'deep'=>2
                          );
      $this->add_history($history_data,true);
    }

  }

  function update_telephone($telecom_key) {

    $old_telecom_key=$this->data['Company Main Telephone Key'];

    $telecom=new Telecom($telecom_key);
    if (!$telecom->id) {
      $this->error=true;
      $this->msg='Telecom not found';
      $this->msg_updated.=',Telecom not found';
      return;
    }
    $old_value=$this->data['Company Main Telephone'];
    $sql=sprintf("update `Company Dimension` set `Company Main Telephone`=%s ,`Company Main Plain Telephone`=%s  ,`Company Main Telephone Key`=%d where `Company Key`=%d "
		 ,prepare_mysql($telecom->display('xhtml'))
		 ,prepare_mysql($telecom->display('plain'))
		 ,$telecom->id
		 ,$this->id
		 );
    mysql_query($sql);
    if (mysql_affected_rows()) {

      $this->updated;
      if ($old_value!=$telecom->display('xhtml'))
	$history_data=array(
			    'indirect_object'=>'Company Main Telephone'
			    ,'old_value'=>$old_value
			    ,'new_value'=>$telecom->display('xhtml')
			    );
      $this->add_history($history_data);
    }

  }

  function update_fax($telecom_key) {


    $old_telecom_key=$this->data['Company Main FAX Key'];

    $telecom=new Telecom($telecom_key);
    if (!$telecom->id) {
      $this->error=true;
      $this->msg='Telecom not found';
      $this->msg_updated.=',Telecom not found';
      return;
    }
    $old_value=$this->data['Company Main FAX'];
    $sql=sprintf("update `Company Dimension` set `Company Main FAX`=%s ,`Company Main Plain FAX`=%s  ,`Company Main Plain FAX`=%d where `Company Key`=%d "
		 ,prepare_mysql($telecom->display('xhtml'))
		 ,prepare_mysql($telecom->display('plain'))
		 ,$telecom->id
		 ,$this->id
		 );
    mysql_query($sql);
    if (mysql_affected_rows()) {
      $this->updated;
      if ($old_value!=$telecom->display('xhtml'))
	$history_data=array(
			    'indirect_object'=>'Company Main FAX'
			    ,'old_value'=>$old_value
			    ,'new_value'=>$telecom->display('xhtml')
			    );
      $this->add_history($history_data);
    }

  }



}

?>
