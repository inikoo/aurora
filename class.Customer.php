<?php
/*
 File: Customer.php 

 This file contains the Customer Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0


 The customer dimension is the  critical element for a CRM, a customer can be a Company or a Contact.

*/
include_once('class.DB_Table.php');
include_once('class.Contact.php');
include_once('class.Order.php');
include_once('class.Address.php');

class Customer extends DB_Table{


 

  var $contact_data=false;
  var $ship_to=array();

  function __construct($arg1=false,$arg2=false) {

    $this->table_name='Customer';
    $this->ignore_fields=array(
			       'Customer Key'
			       ,'Customer Has More Orders Than'
			       ,'Customer Has More  Invoices Than'
			       ,'Customer Has Better Balance Than'
			       ,'Customer Is More Profiteable Than'
			       ,'Customer Order More Frecuently Than'
			       ,'Customer Older Than'
			       ,'Customer Orders Position'
			       ,'Customer Invoices Position'
			       ,'Customer Balance Position'
			       ,'Customer Profit Position'
			       ,'Customer Order Interval'
			       ,'Customer Order Interval STD'
			       ,'Customer Orders Top Percentage'
			       ,'Customer Invoices Top Percentage'
			       ,'Customer Balance Top Percentage'
			       ,'Customer Profits Top Percentage'
			       ,'Customer First Order Date'
			       ,'Customer Last Order Date'
			       ,'Customer Last Ship To Key'
			       );


    $this->status_names=array(0=>'new');
    
    if(is_numeric($arg1) and !$arg2){
      $this->get_data('id',$arg1);
       return;
    }
    
    if($arg1=='new'){
      $this->find($arg2,'create');
       return;
    }elseif(preg_match('/^find staff/',$arg1)){
	$this->find_staff($arg2,$arg1);
	return;
    }elseif(preg_match('/^find/',$arg1)){
	$this->find($arg2,$arg1);
	return;
    }

    $this->get_data($arg1,$arg2);
    
    
  }

/*
    Method: find_staff
    Find Staff Customer 
*/

 function find_staff($staff,$options=''){
   
   $sql=sprintf("select * from `Customer Dimension` where `Customer Staff`='Yes' and `Customer Staff Key`=%d",$staff->id);
   //print $sql;exit;
   $result=mysql_query($sql);
   if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   ){
     
     $this->id=$this->data['Customer Key'];
   }

   if(!$this->id and preg_match('/create|new/',$options)){
     $raw_data['Customer Type']='Person';
     
     $raw_data['Customer Staff']='Yes';
     if($staff->id){



       $contact=new Contact($staff->data['Staff Contact Key']);
       $_raw_data=$contact->data;
       foreach($_raw_data as $key=>$value){
	 $raw_data[preg_replace('/Contact/','Customer',$key)]=$value;
       }

       $raw_data['Customer Staff Key']=$staff->id;
       $raw_data['Customer Main Contact Key']=$staff->data['Staff Contact Key'];
       $raw_data['Customer Name']=$staff->data['Staff Name'];
     }else{
       $contact=new Contact('create anonymous');
  $_raw_data=$contact->data;
       foreach($raw_data as $key=>$value){
	 $raw_data[preg_replace('/Contact/','Customer',$key)]=$value;
       }
       $raw_data['Customer Staff Key']=0;
       $raw_data['Customer Main Contact Key']=$contact->id;
       $raw_data['Customer Name']=_('Unknown Staff');
     }

     
     $this->create($raw_data);
   }


}
  /*

    Method: find
    Find Customer with similar data
   
   
   */  
  function find($raw_data,$options=''){

    //print "===================================\n";

    $this->found_child=false;
    $this->found_child_key=0;
    $this->found=false;
    $this->found_key=0;


    if(isset($raw_data['editor'])){
      foreach($raw_data['editor'] as $key=>$value){

	if(array_key_exists($key,$this->editor))
	  $this->editor[$key]=$value;
		    
      }
    }


   $create='';
   $update='';
   if(preg_match('/create/i',$options)){
     $create='create';
   }
    if(preg_match('/update/i',$options)){
      $update='update';
    }

    if(
       !isset($raw_data['Customer Store Key']) or 
       !preg_match('/^\d+$/i',$raw_data['Customer Store Key']) ){
      $raw_data['Customer Store Key']=1;
      
    }
    
    //  print_r($raw_data);
    if(!isset($raw_data['Customer Type']) or !preg_match('/^(Company|Person)$/i',$raw_data['Customer Type']) ){
      

      // Try to detect if is a company or a person
      if(
	 (isset($raw_data['Customer Company Name']) and  $raw_data['Customer Company Name']!='' )
	 or (isset($raw_data['Customer Company Key']) and  $raw_data['Customer Company Key'] )
	 )$raw_data['Customer Type']='Company';
      else
	$raw_data['Customer Type']='Person';
      
	    
    }
    $raw_data['Customer Type']=ucwords($raw_data['Customer Type']);
    //print $raw_data['Customer Type']."\n";
    if($raw_data['Customer Type']=='Person'){
      $child=new Contact ('find in customer use old_id',$raw_data);
    }else{
      $child=new Company ('find in customer use old_id',$raw_data);
    }

    if($child->found){
      
      //print_r($child);
      $this->found_child=true;
      $this->found_child_key=$child->found_key;
      $customer_found_keys=$child->get_customers_key();
      if(count($customer_found_keys)>0){
	foreach($customer_found_keys as $customer_found_key){
	  $tmp_customer=new Customer($customer_found_key);
	  if($tmp_customer->data['Customer Store Key']==$raw_data['Customer Store Key']){
	    $this->found=true;
	    $this->found_key=$customer_found_key;
	  }
	}
      }
	

    }else{
      $this->candidate=$child->candidate;

    }
    
    
    // print "$options";
    if($this->found){
      $this->get_data('id',$this->found_key);
      //  print "customer Found: ".$this->found_key."  \n";
    }
    
    if($create){
    
      if($this->found){
	
	if($raw_data['Customer Type']=='Person'){
	  $child=new Contact ('find in customer create',$raw_data);
	}else{
	  $child=new Company ('find in customer create',$raw_data);
	}

	//	$child->editor=$this->editor;
	$this->update($raw_data);

      }else{

	if($this->found_child){
	  //	    print "----------------------------------******************\n";
	  //print_r($raw_data);
	  //print_r( $child->translate_data($raw_data,'from customer')  );
	  //print "-----------------------------------------------\n";

	  if($raw_data['Customer Type']=='Person'){

	    $contact=new contact('find in customer create',$raw_data);
	    $raw_data['Customer Main Contact Key']=$contact->id;
	    
	  }else{
	    $company=new company('find in customer create',$raw_data);
	    $raw_data['Customer Company Key']=$company->id;
	  }	  
	  
	  
	}
	$this->create($raw_data);

      }

    }
    

    
 }


  function get_data($tag,$id){
    if($tag=='id')
      $sql=sprintf("select * from `Customer Dimension` where `Customer Key`=%s",prepare_mysql($id));
    elseif($tag=='email')
      $sql=sprintf("select * from `Customer Dimension` where `Customer Email`=%s",prepare_mysql($id));
    elseif($tag='all'){
      $this->find($id);
      return true;
    }else
       return false;
    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   ){
      $this->id=$this->data['Customer Key'];
    }
  }
  
/*   function compley_get_data($data){ */
/*     $weight=array( */
/* 		   'Same Other ID'=>100 */
/* 		   ,'Same Email'=>100 */
/* 		   ,'Similar Email'=>20 */

/* 		   ); */

      
/*       if($data['Customer Email']!=''){ */
/* 	$has_email=true; */
/* 	$sql=sprintf("select `Email Key` from `Email Dimension` where `Email`=%s",prepare_mysql($data['Customer Email'])); */
/* 	$result=mysql_query($sql); */
/* 	if($row=mysql_fetch_array($result, MYSQL_ASSOC)){ */
/* 	  $email_key=$row['Email Key']; */
/* 	  $sql=sprintf("select `Subject Key` from `Email Bridge` where `Email Key`=%s and `Subject Type`='Customer'",prepare_mysql($email_key)); */
/* 	  $result2=mysql_query($sql); */
/* 	  if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)){ */
/* 	    // Email found assuming this is th customer */
	    
/* 	    return $row2['Subject Key']; */
/* 	  } */
/* 	} */
/*       }else */
/* 	$has_email=false; */

/*      $telephone=Telephone::display(Telephone::parse_telecom(array('Telecom Original Number'=>$data['Telephone']),$data['Country Key'])); */
/*     // Email not found check if we have a mantch in other id */
/*      if($data['Customer Other ID']!=''){ */
/*        $no_other_id=false; */
/* 	$sql=sprintf("select `Customer Key`,`Customer Name`,`Customer Main Telephone` from `Customer Dimension` where `Customer Other ID`=%s",prepare_mysql($data['Customer Other ID'])); */
/* 	$result=mysql_query($sql); */
/* 	$num_rows = mysql_num_rows($result); */
/* 	if($num_rows==1){ */
/* 	  $row=mysql_fetch_array($result, MYSQL_ASSOC); */
/* 	  return $row['Customer Key']; */
/* 	}elseif($num_rows>1){ */
/* 	  // Get the candidates */
	  
/* 	  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){ */
/* 	    $candidate[$row['Customer Key']]['field']=array('Customer Other ID'); */
/* 	    $candidate[$row['Customer Key']]['points']=$weight['Same Other ID']; */
/* 	    // from this candoateed of one has the same name we wouls assume that this is the one */
/* 	    if($data['Customer Name']!='' and $data['Customer Name']==$row['Customer Name']) */
/* 	      return $row2['Customer Key']; */
/* 	    if($telephone!='' and $telephone==$row['Customer Main Telephone']) */
/* 	      return $row2['Customer Key']; */

	    
/* 	  } */
	  



/* 	} */
/*      }else */
/*        $no_other_id=true; */
    



/*      //If customer has the same name ond same address */
/*      //$addres_finger_print=preg_replace('/[^\d]/','',$data['Full Address']).$data['Address Town'].$data['Postal Code']; */


/*      //if thas the same name,telephone and address get it */
    




/*      if($has_email){ */
/*      //Get similar candidates from email */
       
/*        $sql=sprintf("select levenshtein(UPPER(%s),UPPER(`Email`)) as dist1,levenshtein(UPPER(SOUNDEX(%s)),UPPER(SOUNDEX(`Email`))) as dist2, `Subject Key`  from `Email Dimension` left join `Email Bridge` on (`Email Bridge`.`Email Key`=`Email Dimension`.`Email Key`)  where dist1<=2 and  `Subject Type`='Customer'   order by dist1,dist2 limit 20" */
/* 		    ,prepare_mysql($data['Customer Email']) */
/* 		    ,prepare_mysql($data['Customer Email']) */
/* 		    ); */
/*        $result=mysql_query($sql); */
/*        while($row=mysql_fetch_array($result, MYSQL_ASSOC)){ */
/* 	  $candidate[$row['Subject Key']]['field'][]='Customer Other ID'; */
/* 	  $dist=0.5*$row['dist1']+$row['dist2']; */
/* 	  if($dist==0) */
/* 	    $candidate[$row['Subject Key']]['points']+=$weight['Same Other ID']; */
/* 	  else */
/* 	    $candidate[$row['Subject Key']]['points']=$weight['Similar Email']/$dist; */
       
/*        } */
/*      } */
 

/*      //Get similar candidates from emailby name */
/*      if($data['Customer Name']!=''){ */
/*      $sql=sprintf("select levenshtein(UPPER(%s),UPPER(`Customer Name`)) as dist1,levenshtein(UPPER(SOUNDEX(%s)),UPPER(SOUNDEX(`Customer Name`))) as dist2, `Customer Key`  from `Customer Dimension`   where dist1<=3 and  `Subject Type`='Customer'   order by dist1,dist2 limit 20" */
/* 		  ,prepare_mysql($data['Customer Name']) */
/* 		  ,prepare_mysql($data['Customer Name']) */
/* 		  ); */
/*      $result=mysql_query($sql); */
/*      while($row=mysql_fetch_array($result, MYSQL_ASSOC)){ */
/*        $candidate[$row['Subject Key']]['field'][]='Customer Name'; */
/*        $dist=0.5*$row['dist1']+$row['dist2']; */
/*        if($dist==0) */
/* 	 $candidate[$row['Subject Key']]['points']+=$weight['Same Customer Name']; */
/*        else */
/* 	 $candidate[$row['Subject Key']]['points']=$weight['Similar Customer Name']/$dist; */
       
/*      } */
/*      } */
/*      // Address finger print */
     



/*  } */




   function load($key='',$arg1=false){
     switch($key){
    case('contact_data'):
    case('contact data'):
      $contact=new Contact($this->get('customer contact key'));
      if($contact->id)
	$this->contact_data=$contact->data;
      else
	$this->errors[]='Error geting contact data object. Contact key:'.$this->get('customer contact key');
      break;
    case('ship to'):
      
      $sql=sprintf('select * from `Ship To Dimension` where `Ship To Key`=%d ',$arg1);

      //  print $sql;
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	$this->ship_to[$row['Ship To Key']]=$row;
	

      }else
	$this->errors[]='Error loading ship to data. Ship to Key:'.$arg1;

      break; 
     }

  }


   function create($raw_data,$args=''){

     $main_telephone_key=false;
     $main_fax_key=false;


     //print_r($raw_data);
       //  exit;
     $this->data=$this->base_data();
     foreach($raw_data as $key=>$value){
       if(array_key_exists($key,$this->data)){
	 $this->data[$key]=_trim($value);
       }
    }


     $this->data['Customer ID']=$this->new_id();
     if($this->data['Customer Type']=='Company'){
       $this->data['Customer Main Email Key']=0;
       $this->data['Customer Main XHTML Email']='';
       $this->data['Customer Main Plain Email']='';
       $this->data['Customer Main Telephone Key']=0;
       $this->data['Customer Main Telephone']='';
       $this->data['Customer Main Plain Telephone']='';
       $this->data['Customer Main FAX Key']=0;
       $this->data['Customer Main FAX']='';
      $this->data['Customer Main Plain FAX']='';
      

  
      $company=new company('find in customer create',$raw_data);
   
      $this->data['Customer Company Key']=$company->id;
      $this->data['Customer Company Name']=$company->data['Company Name'];

      if($company->data['Company Main Email Key']){
	$this->data['Customer Main Email Key']=$company->data['Company Main Email Key'];
	$this->data['Customer Main XHTML Email']=$company->data['Company Main XHTML Email'];
	$this->data['Customer Main Plain Email']=$company->data['Company Main Plain Email'];
      }
      if($company->data['Company Main Telephone Key']){
	$main_telephone_key=$company->data['Company Main Telephone Key'];
      }
      if($company->data['Company Main FAX Key']){
	$main_fax_key=$company->data['Company Main FAX Key'];
      }
      $this->data['Customer Main Contact Key']=$company->data['Company Main Contact Key'];
      $this->data['Customer Main Contact Name']=$company->data['Company Main Contact Name'];

    
      
    }elseif($this->data['Customer Type']=='Person'){
      $this->data['Customer Main Email Key']=0;
      $this->data['Customer Main XHTML Email']='';
      $this->data['Customer Main Plain Email']='';
      $this->data['Customer Main Telephone Key']=0;
      $this->data['Customer Main Telephone']='';
      $this->data['Customer Main Plain Telephone']='';
      $this->data['Customer Main FAX Key']=0;
      $this->data['Customer Main FAX']='';
      $this->data['Customer Main Plain FAX']='';
      
      if(!$this->data['Customer Main Contact Key'])
	$contact=new contact('find in customer create',$raw_data);
      else
	$contact=new contact($this->data['Customer Main Contact Key']);

      $this->data['Customer Main Contact Key']=$contact->id;
      $this->data['Customer Main Contact Name']=$contact->data['Contact Name'];
      $this->data['Customer Name']=$contact->data['Contact Name'];

      //address!!!!!!!!!!!!!


      


      if($contact->data['Contact Main Email Key']){
	$this->data['Customer Main Email Key']=$contact->data['Contact Main Email Key'];
	$this->data['Customer Main XHTML Email']=$contact->data['Contact Main XHTML Email'];
	$this->data['Customer Main Plain Email']=$contact->data['Contact Main Plain Email'];
      }
      if($contact->data['Contact Main Telephone Key']){
	$main_telephone_key=$contact->data['Contact Main Telephone Key'];

      }
      if($contact->data['Contact Main FAX Key']){
	$main_fax_key=$contact->data['Contact Main FAX Key'];

      }
      $this->data['Customer Company Key']=0;


    }else{
      $this->error=true;
      $this->msg.=' Error, Wrong Customer Type ->'.$this->data['Customer Type'];
    }

    if($this->data['Customer First Contacted Date']==''){
      $this->data['Customer First Contacted Date']=date('Y-m-d H:i:s');
    }

    $this->data['Customer Active Ship To Records']=0;
    $this->data['Customer Total Ship To Records']=0;


    // Ok see if we have a billing address!!!

    if(isset($raw_data['Customer Billing Address'])){
      $billing_address=new address('find create',$raw_data['Customer Billing Address']);
      $this->data['Customer Main Address Key']=$billing_address->id;
      $this->data['Customer Main Address Country Code']=$billing_address->data['Address Country Code'];
      $this->data['Customer Main Address 2 Alpha Country Code']=$billing_address->data['Address Country 2 Alpha Code'];

      $this->data['Customer Main Location']=$billing_address->data['Address Location'];
      $this->data['Customer Main Address Town']=$billing_address->data['Address Town'];
      $this->data['Customer Main Address Postal Code']=$billing_address->data['Address'];
      $this->data['Customer Main Address Country Primary Division']=$billing_address->data['Address Country Primary Division'];
      $this->data['Customer Main XHTML Address']=$billing_address->display('html'); 
      $this->data['Customer Main Plain Address']=$billing_address->display('plain'); 


    }else{
     if($this->data['Customer Type']=='Company'){
       
       $billing_address_key=$company->data['Company Main Address Key'];
     }else{
       $billing_address_key=$contact->data['Contact Main Address Key'];
     }

     if($billing_address_key){
       $billing_address=new address($billing_address_key);
       $this->data['Customer Main Address Key']=$billing_address->id;
       $this->data['Customer Main Address Country Code']=$billing_address->data['Address Country Code'];
       $this->data['Customer Main Address Country 2 Alpha Code']=$billing_address->data['Address Country 2 Alpha Code'];
       $this->data['Customer Main Location']=$billing_address->data['Address Location'];
       $this->data['Customer Main Address Town']=$billing_address->data['Address Town'];
       $this->data['Customer Main Address Postal Code']=$billing_address->data['Address Postal Code'];
       $this->data['Customer Main Address Country Primary Division']=$billing_address->data['Address Country Primary Division'];
       $this->data['Customer Main XHTML Address']=$billing_address->display('html');
       $this->data['Customer Main Plain Address']=$billing_address->display('plain');

     }

    }
      

    


    // print_r($this->data);
    //print "xxxxxxxxxxxxxxxxxxxxxxxxxxxx\n";
    //exit;
    $keys='';
    $values='';
    foreach($this->data as $key=>$value){
      $keys.=",`".$key."`";

      if(preg_match('/Key$/',$key))
	$values.=','.prepare_mysql($value);
      else
	$values.=','.prepare_mysql($value,false);
    }
    $values=preg_replace('/^,/','',$values);
    $keys=preg_replace('/^,/','',$keys);

    $sql="insert into `Customer Dimension` ($keys) values ($values)";
  
    if(mysql_query($sql)){
      $this->new=true;
      $this->id=mysql_insert_id();
      $this->get_data('id',$this->id);
      
      $history_data=array(
			    'note'=>_('New Customer')
			  ,'details'=>_trim(_('New customer')." \"".$this->data['Customer Name']."\"  "._('added'))
			  ,'action'=>'created'
			  );
      $this->add_history($history_data);


      if($main_telephone_key){

	$this->add_tel(array(
			     'Telecom Key'=>$main_telephone_key
			     ,'Telecom Type'=>'Contact Telephone'
			     ));
	
      }
      if($main_fax_key)
	$this->add_tel(array(
			     'Telecom Key'=>$main_fax_key
			     ,'Telecom Type'=>'Contact Fax'
			     ));




    }else{
      // print "Error can not create supplier $sql\n";
    }






   }


   function create_old($data=false,$args){


     //print_r($data);

   global $myconf;
   //   $this->unknown_contact=$myconf['unknown_contact'];
   //$this->unknown_company=$myconf['unknown_company'];
   //$this->unknown_customer=$myconf['unknown_customer'];
   //$contact_name=$this->unknown_contact;
   //$company_name=$this->unknown_company;
   $unique_id=$this->get_id();
   
   //$type='Unknown';

   if(isset($data['type']) and ($data['type']=='Company' or $data['type']=='Person'))
     $type=$data['type'];
   
   if(isset($data['contact_name']) and $data['contact_name']!='')
     $contact_name=$data['contact_name'];
    if(isset($data['company_name']) and $data['company_name']!='')
     $company_name=$data['company_name'];
   
   $data_contact=array('name'=>$contact_name);
   if(isset($data['address_data']))
     $data_contact['address_data']=$data['address_data'];
   if(isset($data['email'])){
     $data_contact['email']=$data['email'];
     if($type=='Company')
       $data_contact['Email Description']='Work';
   }
   if(isset($data['telephone']))
     $data_contact['telephone']=$data['telephone'];
   if(isset($data['fax']))
     $data_contact['fax']=$data['fax'];
   
   $shipping=false;
   $shipping_cold_sale=false;
   $shipping_same=false;
   $shipping_same_contact=false;
   $shipping_same_address=false;
   
  //  if(isset($data['shipping']) and isset($data['shipping_data'])){
//       $shipping=true;
//       switch($data['shipping']){
//       case('same'):
// 	$shipping_same=true;
// 	break;
//       case('same_contact'):
// 	$shipping_same=false;
// 	$shipping_same_contact=false;
// 	$shipping_same_address=true;
// 	break;	
//       case('shipping_cold_sale'):
// 	$shipping_cold_sale=true;
// 	$shipping_same=false;
// 	$shipping_same_contact=false;
// 	$shipping_same_address=false;
// 	break;
//       case('same_address'):
// 	$shipping_same=false;
// 	$shipping_same_contact=true;
// 	$shipping_same_address=false;
// 	if($type!='Company'){
// 	  $type='Company';
// 	  $company_name=$contact_name;
// 	}

// 	break;
//       default:
// 	$shipping_same=false;
// 	$shipping_same_contact=true;
// 	$shipping_same_address=false;
// 	break;	
//       }

//    }


   $main_contact=new contact('new',$data_contact);
   
   $this->base_data();
 // print_r($main_contact->data);

   if($type=='Company'){
     $company=new company('new',
			  array('name'=>$company_name,'contact key'=>$main_contact->id)
			  );
     $customer_name=$company->get('Company Name');
     $customer_file_as=$company->get('Company File As');
     
     $company_key=$company->id;


   }else{

     $customer_name=$main_contact->get('Contact Name');
     $customer_file_as=$main_contact->get('Contact File As');
     $company_key='';
   }

   

   if($customer_name=='Unknown Contact' or $customer_name=='Unknown Company')
     $customer_name=$this->unknown_customer;
   
   $address=new Address($main_contact->get('Contact Main Address Key'));
   if(!$address->id){
     print_r($data_contact);
     print_r($main_contact->data);
     dsadasdas();
   }

    $sql=sprintf("insert into `Customer Dimension` (`Customer ID`,`Customer Main Contact Key`,`Customer Main Contact Name`,`Customer Name`,`Customer File As`,`Customer Type`,`Customer Company Key`,`Customer Main Address Key`,`Customer Main Location`,`Customer Main XHTML Address`,`Customer Main Plain Address`,`Customer Main XHTML Email`,`Customer Email`,`Customer Main Email Key`,`Customer Main Telephone`,`Customer Main Telephone Key`,`Customer Main Address Header`,`Customer Main Address Town`,`Customer Main Address Postal Code`,`Customer Main Address Country Region`,`Customer Main Address Country`,`Customer Main Address Country Key`) values (%d,%d,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
		 ,$unique_id
		 ,$main_contact->id
		 ,prepare_mysql($main_contact->get('Contact Name'))
		 ,prepare_mysql($customer_name)
		 ,prepare_mysql($customer_file_as)

		 ,prepare_mysql($type)
		 ,prepare_mysql($company_key)
		 ,prepare_mysql($main_contact->get('Contact Main Address Key'))
		 ,prepare_mysql($main_contact->get('Contact Main Location'))
		 ,prepare_mysql($main_contact->get('Contact Main XHTML Address'))
		 ,prepare_mysql($main_contact->get('Contact Main Plain Address'))

		 ,prepare_mysql($main_contact->get('Contact Main XHTML Email'))
		 ,prepare_mysql(strip_tags($main_contact->get('Contact Main XHTML Email')))
		 ,prepare_mysql($main_contact->get('Contact Main Email Key'))
		 ,prepare_mysql($main_contact->get('Contact Main Telephone'))
		 ,prepare_mysql($main_contact->get('Contact Main Telephone Key'))
		 ,prepare_mysql($address->display('header'))
		 ,prepare_mysql($address->get('Address Town'))
		 ,prepare_mysql($address->get('Postal Code'))
		 ,prepare_mysql($address->get('Address Country Primary Division'))
		 ,prepare_mysql($address->get('Address Country Name'))
		 ,prepare_mysql($address->get('Address Country Key'))
	       );
    //  print_r($main_contact->data);
    
    //print_r($data);
   //  print "$sql\n";
//     exit;

    if(mysql_query($sql)){
      
      $this->id =  mysql_insert_id();
      $this->get_data('id',$this->id);

      
      if(isset($data['other id']) and $data['other id']!=''){

	$sql=sprintf("update `Customer Dimension` set `Customer Other ID`=%s where `Customer Key`=%d",prepare_mysql($data['other id']),$this->id);

	if(!mysql_query($sql))
	  exit("can not update customer other id\n");
      }
      

/*       if(isset($data['has_shipping']) and $data['has_shipping'] and  isset($data['shipping_data']) and is_array($data['shipping_data'])){ */
	
/* 	if($data['same_address'] and $data['same_contact'] and $data['same_company'] and $data['same_telephone']){ */
/* 	  $this->add_ship_to('shipping_same_as_main','Yes'); */
/*       }else{ */
	  
/* 	  $this->shipping['contact_key']=''; */
/* 	  $this->shipping['contact']=''; */
/* 	  $this->shipping['company_key']=''; */
/* 	  $this->shipping['company']=''; */
/* 	  $this->shipping['telephone']=''; */
/* 	  $this->shipping['telephone_key']=''; */
/* 	  $this->shipping['email']=''; */
/* 	  $this->shipping['email_key']=''; */
/* 	  $this->shipping['address']=''; */
/* 	  $this->shipping['address_key']=''; */
/* 	  $this->shipping['address_country_key']=''; */
	  


/* 	if(!$data['same_address']){ */
/* 	  $shipping_address=new Address('new',$data['shipping_data']); */
/* 	  $this->shipping['address_key']=$shipping_address->id; */
/* 	  $this->shipping['address']=$shipping_address->get('XHTML Address'); */
/* 	  $this->shipping['address_country_key']=$shipping_address->get('Address Country Key'); */
/* 	}else{ */
/* 	  $this->shipping['address_key']=$this->get('Customer Main Address Key'); */
/* 	  $this->shipping['address']=$this->get('Customer Main XHTML Address'); */
/* 	  $this->shipping['address_country_key']=$this->get('Customer Main Address Country Key'); */
/* 	} */

/* 	if(!$data['same_company']){ */
/* 	  $this->shipping['company_key']=false; */
/* 	  $this->shipping['company']=$data['shipping_data']['company']; */
/* 	}else{ */
/* 	  $this->shipping['company_key']=$this->get('Customer Company Key'); */
/* 	  $this->shipping['company']=$this->get('Customer Company Name'); */

/* 	} */

/* 	if(!$data['same_contact']){ */

/* 	  if(!$this->shipping['company_key']){ */
/* 	    $this->shipping['contact_key']=false; */
/* 	    $this->shipping['contact']=$data['shipping_data']['name']; */
/* 	  }else{ */
/* 	    //add a new contact */
/* 	    $_data=array( */
/* 			 'name'=>$data['shipping_data']['name'], */
/* 			 'email'=>$data['shipping_data']['email'], */
/* 			 'telephone'=>$data['shipping_data']['telephone'], */
/* 			 'address_key'=>$this->shipping['address_key'] */

/* 			 ); */
/* 	    $shipping_contact=new Contact('new',$_data); */
/* 	    $this->shipping['contact_key']=$shipping_contact->id; */
/* 	    $this->shipping['contact']=$shipping_contact->get('Contact Name'); */
/* 	  } */


/* 	}else{ */
/* 	  $shipping['contact']=$this->get('Customer Main Contact Name'); */
/* 	  $shipping['contact_key']=$this->get('Customer Main Contact Key'); */
	  
/* 	} */
/* 	$shipping_contact_key=$shipping['contact_key']; */


/* // 	if(!$data['same_email']){ */
/* // 	  $shipping_contact=new Contact($shipping_contact_key); */
/* // 	  if($shipping_contact->id){ */
/* // 	    $shipping_contact->add_email(array('email'=>$data['shipping_data']['email'])); */
/* // 	    $this->shipping['email_key']=$shipping_contact->add_email; */
/* // 	    if($this->shipping['email_key']){ */
/* // 	    $email=new Email($this->shipping['email_key']); */
/* // 	    $this->shipping['email_key']=$email->display('html'); */
/* // 	    }else */
/* // 	      $this->shipping['email_key']=''; */
/* // 	  }else{ */
/* // 	    $this->shipping['email_key']=''; */
/* // 	    $this->shipping['email']='<a href="mailto:'.$data['shipping_data']['email'].'">'.$data['shipping_data']['email'].'</a>'; */
/* // 	  } */
/* // 	}else{ */
/* // 	   $this->shipping['email_key']=$this->get('Customer Main Email Key'); */
/* // 	   $this->shipping['email']=$this->get('Customer Main XHTML Email'); */
/* // 	} */

/* 	if(!$data['same_telephone']){ */
/* 	  $shipping_contact=new Contact($shipping_contact_key); */
/* 	  if($shipping_contact->id){ */
/* 	    $shipping_contact->add_tel(array( */
/* 					     'Telecom Original Number'=>$data['shipping_data']['telephone'] */
/* 					     ) */
/* 				       ); */
	    
/* 	    if($shipping_contact->add_telecom){ */
/* 	      //  print "hola\n"; */
/* 	      $shipping_tel=new Telecom($shipping_contact->add_telecom); */

/* 	      $this->shipping['telephone_key']=$shipping_tel->id; */
/* 	      $this->shipping['telephone']=$shipping_tel->display('html'); */
/* 	    } */
/* 	    else{ */
/* 	      $this->shipping['telephone_key']=''; */
/* 	      $this->shipping['telephone']=$data['shipping_data']['telephone']; */
/* 	    } */
/* 	  }else{ */
/* 	    $this->shipping['telephone_key']=''; */
/* 	    $this->shipping['telephone']=$data['shipping_data']['telephone']; */
/* 	  } */
/* 	}else{ */
/* 	   $this->shipping['telephone_key']=$this->get('Customer Main Telephone Key'); */
/* 	   $this->shipping['telephone']=$this->get('Customer Main Telephone'); */

/* 	} */
	
/* 	//print_r($this->shipping); */
	
/* 	$this->add_ship_to('other','Yes'); */
/*       } */

/*       } */
      //      $this->data['Customer Last Ship To Key']=$this->data['Customer Main Ship To Key']
      
    }else{
      print "Error, customer con not be created\n";exit;
      
    }


 }

 function add_ship_to($args='shipping_same_as_main',$is_principal='Yes'){
   
   $is_active='Yes';
   


   if($args=='shipping_same_as_main'){
     //TODO
     
   }elseif(is_numeric($args) and $args>0){
     $ship_to=new Ship_To('id',$args);
   }   

   if($is_principal!='Yes')
     $is_principal='No';
   
   
   $sql=sprintf("insert into (`Customer Ship To Bridge`) values (%d,%d,'%s','Yes',NOW()) on duplicate key update `Is Principal`='%s' ,`Is Active`='Yes'  ",$this->id,$ship_to->id,$is_principal,$is_principal);
   // print $sql;
   mysql_query($sql);
   
   
   
   
   
   $sql=sprintf("select count(*) as total,sum(if(`Is Active`='Yes',1,0)) as active from `Customer Ship To Bridge` where `Customer Key`=%d ",$this->id);
   // print $sql;
   $result=mysql_query($sql);
   if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $active=$row['active'];
     $total=$row['total'];
   }
   
   if($is_principal='Yes'){
     



     $sql=sprintf("update `Customer Dimension` set `Customer Main Ship To Key`=%s,`Customer Main Ship To Town`=%s,`Customer Main Ship To Postal Code`=%s,`Customer Main Ship To Country`=%s,`Customer Main Ship To Country Key`=%s,`Customer Main Ship To Country Code`=%s,`Customer Main Ship To Country 2 Alpha Code`=%s,`Customer Active Ship To Records`=%d,`Customer Total Ship To Records`=%d where `Customer Key`=%d"
		  ,prepare_mysql($ship_to->id)
		  ,prepare_mysql($ship_to->data['Ship To Town'])
		  ,prepare_mysql($ship_to->data['Ship To Postal Code'])
		  ,prepare_mysql($ship_to->data['Ship To Country'])
		  ,prepare_mysql($ship_to->data['Ship To Country Key'])
		  ,prepare_mysql($ship_to->data['Ship To Country Code'])
		  ,prepare_mysql($ship_to->data['Ship To Country 2 Alpha Code'])
		  ,$active
		  ,$total
		  ,$this->id
		  );
     mysql_query($sql);
     
   }else{
 $sql=sprintf("update `Customer Dimension` set `Customer Active Ship To Records`=%d,`Customer Total Ship To Records`=%d where `Customer Key`=%d"
		  ,$active
		  ,$total
		  ,$this->id
		  );
     mysql_query($sql);
   }
 }






   /*Function: update_field_switcher
   */
 function update_field_switcher($field,$value,$options=''){
   
   switch($field){
   case('Customer Main Telephone'):
   case('Customer Main Plain Telephone'):
   case('Customer Main Telephone Key'):
   case('Customer Main FAX'):
   case('Customer Main Plain FAX'):
   case('Customer Main FAX Key'):
   case('Customer Main XHTML Email'):
   case('Customer Main Email Key'):
   case('Customer Main Plain Email'):
     return;
     break;
   default:
     $this->update_field($field,$value,$options);
   }
 }

 
 /*
  function:update_main_contact_key
  */
 function update_main_contact_key($contact_key=false){

   if(!$contact_key)
     return;
   
    $contact=new Contact($contact_key);
   if(!$contact->id)
     return;

   if($this->data['Customer Type']=='Company'){
     $sql=sprintf("select `Is Active` from `Contact Bridge` where `Subject`='Company' and `Subjet Key`=%d and `Contact Key`=%d "
		  ,$this->data['Customer Comapany Key']
		  ,$contact->id
		  );
     $res=mysql_query($sql);
     $number=mysql_num_rows($res);
     if($number==0){
       $this->error=true;
       $msg=_('Contact not in company').".";
       $this->msg.=$msg;
       $this->msg_updated.=$msg;
       return;
     }


   }
   $old_key_value=$this->data['Customer Main Contact Key'];
   $old_value=$this->data['Customer Main Contact Name'];
   $old_contact=new Contact ($this->data['Customer Main Contact Key']);
   $sql=sprintf("update `Customer Dimension` set `Customer Main Contact Key`=%d ,`Customer Main Contact Name`=%s where `Customer Key`=%d"
		,$contact->id
		,prepare_mysql($contact->display('name'))
		,$this->id
		);
 
   mysql_query($sql);
   $this->data['Customer Main Contact Key']=$contact->id;
   $this->data['Customer Main Contact Name']=$contact->display('name');

   $updated=false;
   if($this->data['Customer Main Contact Key']==$old_key_value){
     if($this->data['Customer Main Contact Name']!=$old_value){
       $updated=true;
       $field='Customer Contact Name';
       $note=$field.' '._('Changed');
       $details=$field.' '._('changed from')." \"".$old_value."\" "._('to')." \"".$this->data['Customer Main Contact Name']."\"";
     }
       
   }else{// new contact
       $updated=true;
       $field='Customer Contact';
       $note=$field.' '._('Changed');
      
	 $details=$field.' '._('changed from')." \""
	 .$old_value."\"(".$old_contact->get("ID").") "
	 ._('to')." \"".$this->data['Customer Main Contact Name']."\" (".$contact->get("ID").")";

   }


   if($updated){
     $this->updated=true;
     $this->msg=$details;
     $this->msg_updated=$details;
       $history_data=array(
			   'indirect_object'=>$field
			   ,'details'=>$details
			   ,'note'=>$note
			   );
       $this->add_history($history_data);
   }

 }

 /*
  function:update_email
  */
 function update_email($email_key=false){
   if(!$email_key)
     return;
   $email=new Email($email_key);
   if(!$email->id)
     return;

   if($email->id!=$this->data['Customer Main Email Key']){
     $old_value=$this->data['Customer Main Email Key'];
     $this->data['Customer Main Email Key']=$email->id;
     $this->data['Customer Main Plain Email']=$email->display('plain');
     $this->data['Customer Main XHTML Email']=$email->display('xhtml');
     $sql=sprintf("update `Customer Dimension` set `Customer Main Email Key`=%d,`Customer Main Plain Email`=%s,`Customer Main XHTML Email`=%s where `Customer Key`=%d"

		  ,$this->data['Customer Main Email Key']
		  ,prepare_mysql($this->data['Customer Main Plain Email'])
		  ,prepare_mysql($this->data['Customer Main XHTML Email'])
		  ,$this->id
		  );
     if(mysql_query($sql)){
       
       $note=_('Email changed');
       if($old_value){
	 $old_email=new Email($old_value);
	 $details=_('Customer email changed from')." \"".$old_email->display('plain')."\" "._('to')." \"".$this->data['Customer Main Plain Email']."\"";
       }else{
	 $details=_('Customer email set to')." \"".$this->data['Customer Main Plain Email']."\"";
       }
       
       $history_data=array(
			   'indirect_object'=>'Email'
			   ,'details'=>$details
			   ,'note'=>$note
			   );
       $this->add_history($history_data);
       
       



     }else{
       $this->error=true;
       
     }
     

     
   }elseif($email->display('plain')!=$this->data['Customer Main Plain Email']){
     $old_value=$this->data['Customer Main Plain Email'];
     
     $this->data['Customer Main Plain Email']=$email->display('plain');
     $this->data['Customer Main XHTML Email']=$email->display('xhtml');
     $sql=sprintf("update `Customer Dimension` set `Customer Main Plain Email`=%s,`Customer Main XHTML Email`=%s where `Customer Key`=%d"
		  

		  ,prepare_mysql($this->data['Customer Main Plain Email'])
		  ,prepare_mysql($this->data['Customer Main XHTML Email'])
		  ,$this->id
		  );
     if(mysql_query($sql)){
       $field='Customer Email';
       $note=$field.' '._('Changed');
       $details=$field.' '._('Changed')." ".$old_value." -> ".$this->data['Customer Main Plain Email'];
       
       $history_data=array('indirect_object'=>'Email','details'=>$details,'note'=>$note);
       $this->add_history($history_data);
       



     }else{
       $this->error=true;
       
     }
     

   }



 }


/*   /\* */
/*    Function: base_data */
/*    Initializes an array with the default field values */
/*    *\/ */
/*  function base_data(){ */
/*    $data=array(); */

/*    $ignore_fields=array('Customer Key'); */

/*    $result = mysql_query("SHOW COLUMNS FROM `Customer Dimension`"); */
/*    if (!$result) { */
/*      echo 'Could not run query: ' . mysql_error(); */
/*      exit; */
/*    } */
/*    if (mysql_num_rows($result) > 0) { */
/*      while ($row = mysql_fetch_assoc($result)) { */
/*        if(!in_array($row['Field'],$ignore_fields)) */
/* 	 $data[$row['Field']]=$row['Default']; */
/*      } */
/*    } */
/*    if(preg_match('/not? replace/i',$args)) */
/*      return $data; */
/*    if(preg_match('/replace/i',$args)) */
/*      $this->data=$data; */

/*  } */




 public function update_no_normal_data(){


  $sql="select min(`Order Date`) as date   from `Order Dimension` where `Order Customer Key`=".$this->id;
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)){

	$first_order_date=date('U',strtotime($row['date']));
	if($row['date']!='' 
	   and (
		$this->data['Customer First Contacted Date']=='' 
		or ( date('U',strtotime($this->data['Customer First Contacted Date']))>$first_order_date  )
		)
	   ){
	  $sql=sprintf("update `Customer Dimension` set `Customer First Contacted Date`=%d, where `Customer Key`=%d"
		       ,prepare_mysql($row['date'])
		       ,$this->id
		       );
	  mysql_query($sql);
	}	 
      }
      // $address_fuzzy=false;
      // $email_fuzzy=false;
      // $tel_fuzzy=false;
      // $contact_fuzzy=false;


      // $address=new Address($this->get('Customer Main Address Key'));
      // if($address->get('Fuzzy Address'))
      // 	$address_fuzzy=true;
      


 }


 public function update_activity($date=''){
   if($date=='')
     $date=date("Y-m-d H:i:s");
     $sigma_factor=3.2906;//99.9% value assuming normal distribution
     $this->data['Customer Lost Date']='';
     $this->data['Actual Customer']='Yes';
     $orders= $this->data['Customer Orders'];

     //print $this->id." $orders  \n";

     if($orders==0){
       $this->data['Active Customer']='No';
       $this->data['Customer Type by Activity']='Prospect';
       $this->data['Actual Customer']='No';
     }elseif($orders==1){
       $sql="select avg((`Customer Order Interval`)+($sigma_factor*`Customer Order Interval STD`)) as a from `Customer Dimension` where `Customer Orders`>1";
	 
	 $result2=mysql_query($sql);
	 if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)){
	   $average_max_interval=$row2['a'];
	   //print "$average_max_interval\n";
	   if(is_numeric($average_max_interval)){
	     //print "xxxxxxxxxxxxxx\n";
	     if(   (strtotime('now')-strtotime($this->data['Customer Last Order Date']))/(3600*24)  <  $average_max_interval){
	       // print "xxxxxxxxxxxxxx1\n";
		     
	       $this->data['Active Customer']='Maybe';
	       $this->data['Customer Type by Activity']='New';
	       
	     }else{
	       
	       
	       //print "xxxxxxxxxxxxxx2\n";

	       $this->data['Active Customer']='No';
	       $this->data['Customer Type by Activity']='Inactive';
	       //   print $this->data['Customer Last Order Date']." +$average_max_interval days\n"; 
	       $this->data['Customer Lost Date']=date("Y-m-d H:i:s",strtotime($this->data['Customer Last Order Date']." +".ceil($average_max_interval)." day" ));
	     }

	     
	     //print "+++++++++++++\n";
	   }else{
	     $this->data['Active Customer']='Unknown';
	     $this->data['Customer Type by Activity']='Unknown';
	   }
	   
	 }else{
	    $this->data['Active Customer']='Unknown';
	    $this->data['Customer Type by Activity']='Unknown';
	 }
	 //print "-----------\n";

     }else{
       //print $this->data['Customer Last Order Date']."\n";

       $last_date=date('U',strtotime($this->data['Customer Last Order Date']));
       //print ((date('U')-$last_date)/3600/24)."\n";
       // print_r($this->data);
       
       if($orders==2){
	  $sql="select avg(`Customer Order Interval`) as i, avg((`Customer Order Interval`)+($sigma_factor*`Customer Order Interval STD`)) as a from `Customer Dimension` where `Customer Orders`>2";
	 
	 $result2=mysql_query($sql);
	 if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)){
	   $a_inteval=$row2['a'];
	   $i_inteval=$row2['i'];
	 }
	 if($i_inteval==0)
	   $factor=3;	   
	 else
	   $factor=$a_inteval/$i_inteval;
	     
	 $interval=ceil($this->data['Customer Order Interval']*$factor);
	
       }else
	 $interval=ceil($this->data['Customer Order Interval']+($sigma_factor*$this->data['Customer Order Interval STD']));




       if( (date('U')-$last_date)/24/3600  <$interval){
	   $this->data['Active Customer']='Yes';
	   $this->data['Customer Type by Activity']='Active';
	 }else{
	   $this->data['Active Customer']='No';
	   $this->data['Customer Type by Activity']='Inactive';
	   $this->data['Customer Lost Date']=date("Y-m-d H:i:s",strtotime($this->data['Customer Last Order Date']." +".$interval." day" ));
	 }
     }
 
         $sql=sprintf("update `Customer Dimension` set `Actual Customer`=%s,`Active Customer`=%s,`Customer Type by Activity`=%s , `Customer Lost Date`=%s where `Customer Key`=%d"
		      ,prepare_mysql($this->data['Actual Customer'])
		      ,prepare_mysql($this->data['Active Customer'])
		      ,prepare_mysql($this->data['Customer Type by Activity'])
		      ,prepare_mysql($this->data['Customer Lost Date'])
		      ,$this->id
		    );

	 //	  print "$sql\n";
	 if(!mysql_query($sql))
	 exit("$sql error");
     
 }

 /*
   function: update_orders
   Update order stats
  */

 public function update_orders(){
    $sigma_factor=3.2906;//99.9% value assuming normal distribution

     $sql="select sum(`Order Profit Amount`) as profit,sum(`Order Net Refund Amount`+`Order Net Credited Amount`) as net_refunds,sum(`Order Outstanding Balance Net Amount`) as net_outstanding, sum(`Order Balance Net Amount`) as net_balance,sum(`Order Tax Refund Amount`+`Order Tax Credited Amount`) as tax_refunds,sum(`Order Outstanding Balance Tax Amount`) as tax_outstanding, sum(`Order Balance Tax Amount`) as tax_balance, min(`Order Date`) as first_order_date ,max(`Order Date`) as last_order_date,count(*)as orders, sum(if(`Order Current Payment State` like '%Cancelled',1,0)) as cancelled,  sum( if(`Order Current Payment State` like '%Paid%'    ,1,0)) as invoiced,sum( if(`Order Current Payment State` like '%Refund%'    ,1,0)) as refunded,sum(if(`Order Current Dispatch State`='Unknown',1,0)) as unknown   from `Order Dimension` where `Order Customer Key`=".$this->id;

     $this->data['Customer Orders']=0;
     $this->data['Customer Orders Cancelled']=0;
     $this->data['Customer Orders Invoiced']=0;
     $this->data['Customer First Order Date']='';
     $this->data['Customer Last Order Date']='';
     $this->data['Customer Order Interval']='';
     $this->data['Customer Order Interval STD']='';
     $this->data['Actual Customer']='No';
     $this->data['New Served Customer']='No';
     $this->data['Active Customer']='Unkwnown';
     $this->data['Customer Net Balance']=0;
     $this->data['Customer Net Refunds']=0;
     $this->data['Customer Net Payments']=0;
     $this->data['Customer Tax Balance']=0;
     $this->data['Customer Tax Refunds']=0;
     $this->data['Customer Tax Payments']=0;
     $this->data['Customer Profit']=0;

     //print $sql;exit;
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       
       $this->data['Customer Orders']=$row['orders'];
       $this->data['Customer Orders Cancelled']=$row['cancelled'];
       $this->data['Customer Orders Invoiced']=$row['invoiced'];
       
       $this->data['Customer Net Balance']=$row['net_balance'];
       $this->data['Customer Net Refunds']=$row['net_refunds'];
       $this->data['Customer Net Payments']=$row['net_balance']-$row['net_outstanding'];
       $this->data['Customer Outstanding Net Balance']=$row['net_outstanding'];

       $this->data['Customer Tax Balance']=$row['tax_balance'];
       $this->data['Customer Tax Refunds']=$row['tax_refunds'];
       $this->data['Customer Tax Payments']=$row['tax_balance']-$row['tax_outstanding'];
       $this->data['Customer Outstanding Tax Balance']=$row['tax_outstanding'];

       $this->data['Customer Profit']=$row['profit'];


       if($this->data['Customer Orders']>0){
	 $this->data['Customer First Order Date']=$row['first_order_date'];
	 $this->data['Customer Last Order Date']=$row['last_order_date'] ;
	 $this->data['Actual Customer']='Yes';
       }else{
	 $this->data['Actual Customer']='No';
	 $this->data['Customer Type By Activity']='Prospect';
	 
       }
       
       if($this->data['Customer Orders']==1){
	 $sql="select avg((`Customer Order Interval`)+($sigma_factor*`Customer Order Interval STD`)) as a from `Customer Dimension`";
	 
	 $result2=mysql_query($sql);
	 if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)){
	   $average_max_interval=$row2['a'];
	   if(is_numeric($average_max_interval)){
	     if(   (strtotime('now')-strtotime($this->data['Customer Last Order Date']))/(3600*24)  <  $average_max_interval){
	       $this->data['Active Customer']='Maybe';
	       $this->data['Customer Type by Activity']='New';
	       
	     }else{
	       $this->data['Active Customer']='No';
	       $this->data['Customer Type by Activity']='Inactive';
	       
	     }
	   }else
	     $this->data['Active Customer']='Unknown';
	   $this->data['Customer Type by Activity']='Unknown';
	   
	   
	 }	
	 
       }
       
       if($this->data['Customer Orders']>1){
	 $sql="select `Order Date` as date from `Order Dimension` where `Order Customer Key`=".$this->id." order by `Order Date`";
	 $last_order=false;
	 $intervals=array();
	 $result2=mysql_query($sql);
	 while($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ){
	   $this_date=date('U',strtotime($row2['date']));
	   if($last_order){
	     $intervals[]=($this_date-$last_date)/3600/24;
	   }
	   
	   $last_date=$this_date;
	   $last_order=true;
	   
	 }
	 //	 print $sql;
	 //print_r($intervals);
	 
	 
	 $this->data['Customer Order Interval']=average($intervals);
	 $this->data['Customer Order Interval STD']=deviation($intervals);
	 

	 

       }
       
      
       
       $sql=sprintf("update `Customer Dimension` set `Customer Net Balance`=%.2f,`Customer Orders`=%d,`Customer Orders Cancelled`=%d,`Customer Orders Invoiced`=%d,`Customer First Order Date`=%s,`Customer Last Order Date`=%s,`Customer Order Interval`=%s,`Customer Order Interval STD`=%s,`Customer Net Refunds`=%.2f,`Customer Net Payments`=%.2f,`Customer Outstanding Net Balance`=%.2f,`Customer Tax Balance`=%.2f,`Customer Tax Refunds`=%.2f,`Customer Tax Payments`=%.2f,`Customer Outstanding Tax Balance`=%.2f,`Customer Profit`=%.2f where `Customer Key`=%d",
		    $this->data['Customer Net Balance']
		    ,$this->data['Customer Orders']
		    ,$this->data['Customer Orders Cancelled']
		    ,$this->data['Customer Orders Invoiced']
		    ,prepare_mysql($this->data['Customer First Order Date'])
		    ,prepare_mysql($this->data['Customer Last Order Date'])
		    ,prepare_mysql($this->data['Customer Order Interval'])
		    ,prepare_mysql($this->data['Customer Order Interval STD'])
		    ,$this->data['Customer Net Refunds']
		    ,$this->data['Customer Net Payments']
		    ,$this->data['Customer Outstanding Net Balance']
		    
		    ,$this->data['Customer Tax Balance']
		    ,$this->data['Customer Tax Refunds']
		    ,$this->data['Customer Tax Payments']
		    ,$this->data['Customer Outstanding Tax Balance']
		    
		    ,$this->data['Customer Profit']



		    ,$this->id
		    );

       if(!mysql_query($sql))
	 exit("$sql error");
     }


      //      $sql=sprintf("select `Customer Orders` from `Customer Dimension` order by `Customer Order`");



 }





 function updatex($values,$args=''){
    $res=array();
    foreach($values as $data){
      
      $key=$data['key'];
      $value=$data['value'];
      $res[$key]=array('ok'=>false,'msg'=>'');
      
      switch($key){

      case('tax_number_valid'):
	if($value)
	  $this->data['tax_number_valid']=1;
	else
	  $this->data['tax_number_valid']=0;
	
	break;

      case('tax_number'):
	$this->data['tax_number']=$value;
	if($value=='')
	  $this->update(array(array('key'=>'tax_number_valid','value'=>0)),'save');
	break;
      case('main_email'):
	$main_email=new email($value);
	if(!$main_email->id){
	  $res[$key]['msg']=_('Email not found');
	  $res[$key]['ok']=false;
	  continue;
	}
	$this->old['main_email']=$this->data['main']['email'];
	$this->data['main_email']=$value;
	$this->data['main']['email']=$main_email->data['email'];
	$res[$key]['ok']=true;


      } 
      if(preg_match('/save/',$args)){
	$this->save($key);
      }

    }
    return $res;
 }


 function save($key,$history_data=false){
   switch($key){

   case('tax_number'):
   case('tax_number_valid'):
   case('main_email'):
     $sql=sprintf('update customer set %s=%s where id=%d',$key,prepare_mysql($this->data[$key]),$this->id);
     //print "$sql\n";
     mysql_query($sql);
     
	if(is_array($history_data)){
	  $this->save_history($key,$this->old[$key],$this->data['main']['email'],$history_data);
	}
       
	
	break;
    }

 }

 function save_history($key,$old,$new,$data){
     if(isset($data['user_id']))
       $user=$data['user_id'];
     else
       $user=0;
     
     if(isset($data['date']))
       $date=prepare_mysql($data['date']);
     else
       $date='NOW()';

   switch($key){
   case('new_note'):
   case('add_note'):
     if(preg_match('/^\s*$/',$data['note'])){
       $this->msg=_('Invalid value');
       return false;
     
     }

     $tipo='NOTE';
     $note=_trim($data['note']);
     $details='';


     $sql=sprintf("insert into `History Dimension` (`History Date`,`Subject`,`Subject Key`,`Action`,`Direct Object`,`Preposition`,`Indirect Object`,`Indirect Object Key`,`History Abstract`,`History Details`,`Author Name`,`Author Key`) values (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
		  ,$date
		  ,prepare_mysql('User')
		  ,prepare_mysql($data['user_id'])
		  ,prepare_mysql('wrote')
		  ,prepare_mysql('Note')
		  ,prepare_mysql('about')
		  ,prepare_mysql('Customer')
		  ,prepare_mysql($this->id)
		  ,prepare_mysql($note)
		  ,prepare_mysql($details)
		  ,prepare_mysql($data['author'])
		  ,prepare_mysql($data['author_key'])
		  );
     //   print $sql;
     mysql_query($sql);
     $this->msg=_('Note Added');
     return true;
     break;

       case('new_note'):
   case('order'):
     $tipo='ORDER';
     $order=new order('order',$data['order_id']);
     $action=$data['action'];

     if(isset($data['display']))
       $display=$data['display'];
     else
       $display='normal';

     switch($action){
     case('creation'):
       $_action='DATE_CR';
       $note=_('Customer place order').' <a href="order.php?id='.$order->id.'">'.$order->get('public_id').'</a>';
       break;
     case('processed'):
       $_action='DATE_PR';
       $note=_('Order').' <a href="order.php?id='.$order->id.'">'.$order->get('public_id').'</a> '._('processed');
       
       break;
     case('invoiced'):
       $_action='DATE_IN';
       $note=_('Order').' <a href="order.php?id='.$order->id.'">'.$order->get('public_id').'</a> '._('for').' '.money((float)$order->get('total'));
       break;
     case('cancelled'):
       $_action='DATE_CA';
       $note=_('Order').' <a href="order.php?id='.$order->id.'">'.$order->get('public_id').'</a> '._('has been cancelled');
       break;
   case('sample'):
       $_action='DATE_DI';
       $note=_('Sample send').' (<a href="order.php?id='.$order->id.'">'.$order->get('public_id').'</a>)';
       break;
   case('donation'):
       $_action='DATE_DI';
       $note=_('Donation').' (<a href="order.php?id='.$order->id.'">'.$order->get('public_id').'</a>)';
       break;
   case('replacement'):
       $_action='DATE_DI';
       $parent_order='';
       if($order->get('parent_id')){
	 $parent=new Order($order->get('parent_id'));
	 if($parent->id)
	   $parent_order=' '._('for order').' (<a href="order.php?id='.$parent->id.'">'.$parent->get('public_id').'</a>';
       }
       $note=_('Replacement').' (<a href="order.php?id='.$order->id.'">'.$order->get('public_id').'</a>)'.$parent_order;
       break;
   case('shortages'):
       $_action='DATE_DI';
       $parent_order='';
       if($order->get('parent_id')){
	 $parent=new Order($order->get('parent_id'));
	 if($parent->id)
	   $parent_order=' '._('for order').' (<a href="order.php?id='.$parent->id.'">'.$parent->get('public_id').'</a>';
       }
       $note=_('shortages').' (<a href="order.php?id='.$order->id.'">'.$order->get('public_id').'</a>)'.$parent_order;
       break;
   case('followup'):
       $_action='DATE_DI';
       $parent_order='';
       if($order->get('parent_id')){
	 $parent=new Order($order->get('parent_id'));
	 if($parent->id)
	   $parent_order=' '._('for order').' (<a href="order.php?id='.$parent->id.'">'.$parent->get('public_id').'</a>';
       }
       $note=_('Follow up').' (<a href="order.php?id='.$order->id.'">'.$order->get('public_id').'</a>)'.$parent_order;
       break;
     default:
       $this->msg=_('Unknown action');
       return false;
     }





     $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note,display) values (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
		  ,$date
		  ,prepare_mysql('CUST')
		  ,prepare_mysql($this->id)
		  ,prepare_mysql($tipo)
		  ,$order->id
		  ,prepare_mysql($_action)
		  ,prepare_mysql($user)
		  ,prepare_mysql($old)	 
		  ,prepare_mysql($new)	 
		  ,prepare_mysql($note)
		  ,prepare_mysql($display)
		  );
     // print "$sql\n";
     mysql_query($sql);
     $this->msg=_('Note Added');
     return true;

   }
 }


 function get($key,$arg1=false){

   

   if(array_key_exists($key,$this->data)){
     return $this->data[$key]; 
   }
   
   if(preg_match('/^contact /i',$key)){
     if(!$this->contact_data)
       $this->load('contact data');
     if(isset($this->contact_data[$key]))
       return $this->contact_data[$key]; 
   }
   



   if(preg_match('/^ship to /i',$key)){
     if(!$arg1)
       $ship_to_key=$this->data['Customer Main Ship To Key'];
     else
       $ship_to_key=$arg1;
      if(!$this->ship_to[$ship_to_key])
	$this->load('ship to',$ship_to_key);
      if(isset($this->ship_to[$ship_to_key])    and  array_key_exists($key,$this->ship_to[$ship_to_key]) )
	return $this->ship_to[$ship_to_key][$key]; 
   }
   


   switch($key){
   case("ID"):
   case("Formated ID"):
     return $this->get_formated_id();
   case('Net Balance'):
     return money($this->data['Customer Net Balance']);
     break;
   case('Total Net Per Order'):
     if($this->data['Customer Orders Invoiced']>0)
       return money($this->data['Customer Net Balance']/$this->data['Customer Orders Invoiced']);
     else
       return _('ND');
     break;
   case('Order Interval'):
     $order_interval=$this->get('Customer Order Interval');
     
     if($order_interval>10){
       $order_interval=round($order_interval/7);
       if( $order_interval==1)
	 $order_interval=_('week');
       else
	 $order_interval=$order_interval.' '._('weeks');
       
     }else if($order_interval=='')
  $order_interval='';
     else
       $order_interval=round($order_interval).' '._('days');
     return $order_interval;
     break;
   case('order within'):
     
     if(!$args)
       $args='1 MONTH';
     //get customer last invoice;
     $sql="select count(*)as num  from `Order Dimension` where `Order Type`='Order' and `Order Current Dispatch State`!='Cancelled' and `Order Customer Key`=".$this->id." and DATE_SUB(CURDATE(),INTERVAL $args) <=`Order Date`  ";
     // print $sql;
     
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       
       if($row['num']>0)
	 return true;
     }
     return false;
     break;
   case('xhtml ship to'):
   
     
     if(!$arg1)
       $ship_to_key=$this->data['Customer Main Ship To Key'];
     else
       $ship_to_key=$arg1;
     
     if(!$ship_to_key){
       print_r($this->data);
       print "\n*** Warning no ship to key un customer.php\n";
       sdsd();
       exit;
       return false;
       
     }

     if(!isset($this->ship_to[$ship_to_key]['ship to key']))
	 $this->load('ship to',$ship_to_key);
      

     //print_r($this->ship_to);

      if(isset($this->ship_to[$ship_to_key]['Ship To Key'])){
	$contact=$this->ship_to[$ship_to_key]['Ship To Contact Name'];
	$company=$this->ship_to[$ship_to_key]['Ship To Company Name'];
	$address=$this->ship_to[$ship_to_key]['Ship To XHTML Address'];
	$tel=$this->ship_to[$ship_to_key]['Ship To Telephone'];
	$ship_to='';
	if($contact!='')
	  $ship_to.='<b>'.$contact.'</b>';
	if($company!='')
	  $ship_to.='<br/>'.$company;
	if($address!='')
	  $ship_to.='<br/>'.$address;
	if($tel!='')
	  $ship_to.='<br/>'.$tel;
	return $ship_to;
      }
    
      return false;
     break;

     //   case('customer main address key')

     
 //   case('location'):
//      if(!isset($this->data['location']))
//        $this->load('location');
//      return $this->data['location']['country_code'].$this->data['location']['town'];
//      break;
//    case('super_total'):
//           return $this->data['total_nd']+$this->data['total'];
// 	  break;
//    case('orders'):
//      return $this->data['num_invoices']+$this->data['num_invoices_nd'];
//      break;
//    default:
//      if(isset($this->data[$key]))
//        return $this->data[$key];
//      else
//        return '';
   }
   
   $_key=ucwords($key);
   if(isset($this->data[$_key]))
     return $this->data[$_key];

   //print "Error ->$key not found in get,* from Customer\n";
   //exit;
   return false;

 }


  function new_id(){
    
    $sql="select max(`Customer ID`)  as customer_id from `Customer Dimension`";
    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      
      
      if(!preg_match('/\d*/',_trim($row['customer_id']),$match))
	$match[0]=1;
      $right_side=$match[0];
      // print "$right_side\n";
      $number=(double) $right_side;
      $number++;
      $id=$number;
    }else{
      $id=1;
    }  
    // print "$id\n";
    return $id;
  }





 function update_address_data($address_key=false){

   if(!$address_key)
     return;
   $address=new Address($address_key);
   if(!$address->id)
     return;

   if(
      $address->id!=$this->data['Customer Main Address Key']
      or $address->display('xhtml')!=$this->data['Customer Main XHTML Address']
      or $address->display('plain')!=$this->data['Customer Main Plain Address']
      or $address->display('location')!=$this->data['Customer Main Location']      ){
     $old_value=$this->data['Customer Main XHTML Address'];
     $this->data['Customer Main Address Key']=$address->id;
     $this->data['Customer Main XHTML Address']=$address->display('xhtml');
     $this->data['Customer Main Address Country Code']=$address->data['Address Country Code'];
     $this->data['Customer Main Address Country 2 Alpha Code']=$address->data['Address Country 2 Alpha Code'];
     


     $this->data['Customer Main Address Country']=$address->data['Address Country Name'];
     $this->data['Customer Main Location']=$address->display('location');
     $this->data['Customer Main Address Town']=$address->data['Address Town'];
     $this->data['Customer Main Address Postal Code']=$address->data['Address Postal Code'];
     $this->data['Customer Main Address Country Primary Division']=$address->data['Address Country Primary Division'];
     

     $sql=sprintf("update `Customer Dimension` set `Customer Main Address Key`=%d,`Customer Main Plain Address`=%s,`Customer Main XHTML Address`=%s,`Customer Main Address Country`=%s,`Customer Main Location`=%s,`Customer Main Address Country Code`=%s,`Customer Main Address Country 2 Alpha Code`=%s,`Customer Main Address Town`=%s,`Customer Main Address Postal Code`=%s ,`Customer Main Address Country Primary Division`=%s    where `Customer Key`=%d"
		  
		  ,$this->data['Customer Main Address Key']
		  ,prepare_mysql($this->data['Customer Main Plain Address'],false)
		  ,prepare_mysql($this->data['Customer Main XHTML Address'])
		  ,prepare_mysql($this->data['Customer Main Address Country'])
		  ,prepare_mysql($this->data['Customer Main Location'])
		  ,prepare_mysql($this->data['Customer Main Address Country Code'])
		  ,prepare_mysql($this->data['Customer Main Address Country 2 Alpha Code'])
		  ,prepare_mysql($this->data['Customer Main Address Town'])
		  ,prepare_mysql($this->data['Customer Main Address Postal Code'])
		  ,prepare_mysql($this->data['Customer Main Address Country Primary Division'])

		  
		  ,$this->id
		  );


     if(!mysql_query($sql))
       exit("\n\nerror $sql\n");
       
     if($old_value!=$this->data['Customer Main XHTML Address']){
     
     $note=_('Address Changed');
     if($old_value!=''){
       $details=_('Customer address changed from')." \"".$old_value."\" "._('to')." \"".$this->data['Customer Main XHTML Address']."\"";
     }else{
       $details=_('Customer address set to')." \"".$this->data['Customer Main XHTML Address']."\"";
     }
       
       $history_data=array(
			   'indirect_object'=>'Address'
			   ,'details'=>$details
			   ,'note'=>$note
			   );
       $this->add_history($history_data);
       
     }




   }

 }


   /*function:get_formated_id
     Returns formated id
    */
   function get_formated_id(){
     global $myconf;
     
     $sql="select count(*) as num from `Customer Dimension`";
     $res=mysql_query($sql);
     $min_number_zeros=4;
     if($row=mysql_fetch_array($res)){
       if(strlen($row['num'])-1>$min_number_zeros)
	 $min_number_zeros=strlen($row['num'])-01;
     }
     if(!is_numeric($min_number_zeros))
       $min_number_zeros=4;

     return sprintf("%s%0".$min_number_zeros."d",$myconf['customer_id_prefix'], $this->data['Customer ID']);

   }

/* Method: add_tel
  Add/Update an telecom to the Customer
*/
   function add_tel($data,$args='principal'){
     
     $principal=false;
     if(preg_match('/not? principal/',$args) ){
       $principal=false;
     }elseif( preg_match('/principal/',$args)){
       $principal=true;
     }

   

   
      if(is_numeric($data)){
	$tmp=$data;
	unset($data);
	$data['Telecom Key']=$tmp;
      }
      
      if(isset($data['Telecom Key'])){
	$telecom=new Telecom('id',$data['Telecom Key']);
      }

      if(!isset($data['Telecom Type'])  or $data['Telecom Type']!='Contact Fax' )
	$data['Telecom Type']='Contact Telephone';



      if($data['Telecom Type']=='Contact Telephone'){
	$field='Customer Main Telephone';
	$field_key='Customer Main Plain Telephone';
	$field_plain='Customer Main Telephone Key';
	$old_principal_key=$this->data['Customer Main Telephone Key'];
	$old_value=$this->data['Customer Main Telephone']." (Id:".$this->data['Customer Main Telephone Key'].")";
      }else{
	$field='Customer Main FAX';
	$field_key='Customer Main Plain FAX';
	$field_plain='Customer Main Telephone FAX';
	$old_principal_key=$this->data['Customer Main FAX Key'];
	$old_value=$this->data['Customer Main FAX']." (Id:".$this->data['Customer Main FAX Key'].")";
      }

	
      
      if($telecom->id){
	
	//	print "$principal $old_principal_key ".$telecom->id."  \n";

	
	if($principal and $old_principal_key!=$telecom->id){
	  $sql=sprintf("update `Telecom Bridge`  set `Is Main`='No' where `Subject Type`='Customer' and  `Subject Key`=%d  ",
		       $this->id
		       ,$telecom->id
		       );
	  mysql_query($sql);
	  
	  $sql=sprintf("update `Customer Dimension` set `%s`=%s , `%s`=%d  , `%s`=%s  where `Customer Key`=%d"
		       ,$field
		       ,prepare_mysql($telecom->display('html'))
		       ,$field_key
		       ,$telecom->id
		       ,$field_plain
		       ,prepare_mysql($telecom->display('plain'))
		       ,$this->id
		      );
	  mysql_query($sql);
	  $history_data=array(
			      'note'=>$field." "._('Changed')
			      ,'details'=>$field." "._('changed')." "
			      .$old_value." -> ".$telecom->display('html')
			      ." (Id:"
			      .$telecom->id
			      .")"
			      ,'action'=>'created'
			      );
	  $this->add_history($history_data);
	 
	 
	}

	
	
	$sql=sprintf("insert into  `Telecom Bridge` (`Telecom Key`, `Subject Key`,`Subject Type`,`Telecom Type`,`Is Main`) values (%d,%d,'Customer',%s,%s)  ON DUPLICATE KEY UPDATE `Telecom Type`=%s ,`Is Main`=%s  "
		     ,$telecom->id
		     ,$this->id
		     ,prepare_mysql($data['Telecom Type'])
		     ,prepare_mysql($principal?'Yes':'No')
		     ,prepare_mysql($data['Telecom Type'])
		     ,prepare_mysql($principal?'Yes':'No')
		     );
	mysql_query($sql);
	


	


      }

      
      



    }




/* Method: remove_email
  Delete the email from Customer
  
  Delete telecom record  this record to the Customer


  Parameter:
  $args -     string  options
 */
 function remove_email($email_key){

   
    if(!$email_key){
     $email_key=$this->data['Customer Main Email Key'];
   }
   
   
   $email=new email($email_key);
   if(!$email->id){
     $this->error=true;
     $this->msg='Wrong email key when trying to remove it';
     $this->msg_updated='Wrong email key when trying to remove it';
   }

   $email->set_scope('Customer',$this->id);
   if( $email->associated_with_scope){
     
     $sql=sprintf("delete `Email Bridge`  where `Subject Type`='Customer' and  `Subject Key`=%d  and `Email Key`=%d",
		  $this->id
		  
		  ,$this->data['Customer Main Email Key']
		  );
     mysql_query($sql);
     
     if($email->id==$this->data['Customer Main Email Key']){
       $sql=sprintf("update `Customer Dimension` set `Customer Main XHTML Email`='', `Customer Main Plain Email`='' , `Customer Main Email Key`=''  where `Customer Key`=%d"
		    ,$this->id
		    );
       
       mysql_query($sql);
     }
   }
   

       

 }

 function get_main_email_key(){
    return $this->data['Customer Main Email Key'];
  }


 }
?>