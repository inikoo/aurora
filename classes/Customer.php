<?
/*
 File: Customer.php 

 This file contains the Customer Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('Contact.php');
include_once('Order.php');
include_once('Address.php');

class Customer{

  var $id=false;
  var $contact_data=false;
  var $ship_to=array();

  function __construct($arg1=false,$arg2=false) {

    $this->status_names=array(0=>'new');
    
    if(is_numeric($arg1) and !$arg2){
      $this->get_data('id',$arg1);
       return;
    }
    
    if($arg1=='new'){
       $this->create($arg2);
       return;
    }

    $this->get_data($arg1,$arg2);
    
    
  }




  function get_data($tag,$id){
    if($tag=='id')
      $sql=sprintf("select * from `Customer Dimension` where `Customer Key`=%s",prepare_mysql($id));
    elseif($tag=='email')
      $sql=sprintf("select * from `Customer Dimension` where `Customer Email`=%s",prepare_mysql($id));
    elseif($tag='all'){
      $this->complex_get_data($id);
      return true;
    }else
       return false;
    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   ){
      $this->id=$this->data['Customer Key'];
    }
  }
  
  function compley_get_data($data){
    $weight=array(
		   'Same Other ID'=>100
		   ,'Same Email'=>100
		   ,'Similar Email'=>20

		   );

      
      if($data['Customer Email']!=''){
	$has_email=true;
	$sql=sprintf("select `Email Key` from `Email Dimension` where `Email`=%s",prepare_mysql($data['Customer Email']));
	$result=mysql_query($sql);
	if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	  $email_key=$row['Email Key'];
	  $sql=sprintf("select `Subject Key` from `Email Bridge` where `Email Key`=%s and `Subject Type`='Customer'",prepare_mysql($email_key));
	  $result2=mysql_query($sql);
	  if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)){
	    // Email found assuming this is th customer
	    
	    return $row2['Subject Key'];
	  }
	}
      }else
	$has_email=false;

     $telephone=Telephone::display(Telephone::parse_telecom(array('Telecom Original Number'=>$data['Telephone']),$data['Country Key']));
    // Email not found check if we have a mantch in other id
     if($data['Customer Other ID']!=''){
       $no_other_id=false;
	$sql=sprintf("select `Customer Key`,`Customer Name`,`Customer Main Telephone` from `Customer Dimension` where `Customer Other ID`=%s",prepare_mysql($data['Customer Other ID']));
	$result=mysql_query($sql);
	$num_rows = mysql_num_rows($result);
	if($num_rows==1){
	  $row=mysql_fetch_array($result, MYSQL_ASSOC);
	  return $row['Customer Key'];
	}elseif($num_rows>1){
	  // Get the candidates
	  
	  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	    $candidate[$row['Customer Key']]['field']=array('Customer Other ID');
	    $candidate[$row['Customer Key']]['points']=$weight['Same Other ID'];
	    // from this candoateed of one has the same name we wouls assume that this is the one
	    if($data['Customer Name']!='' and $data['Customer Name']==$row['Customer Name'])
	      return $row2['Customer Key'];
	    if($telephone!='' and $telephone==$row['Customer Main Telephone'])
	      return $row2['Customer Key'];

	    
	  }
	  



	}
     }else
       $no_other_id=true;
    



     //If customer has the same name ond same address
     //$addres_finger_print=preg_replace('/[^\d]/','',$data['Full Address']).$data['Address Town'].$data['Postal Code'];


     //if thas the same name,telephone and address get it
    




     if($has_email){
     //Get similar candidates from email
       
       $sql=sprintf("select levenshtein(UPPER(%s),UPPER(`Email`)) as dist1,levenshtein(UPPER(SOUNDEX(%s)),UPPER(SOUNDEX(`Email`))) as dist2, `Subject Key`  from `Email Dimension` left join `Email Bridge` on (`Email Bridge`.`Email Key`=`Email Dimension`.`Email Key`)  where dist1<=2 and  `Subject Type`='Customer'   order by dist1,dist2 limit 20"
		    ,prepare_mysql($data['Customer Email'])
		    ,prepare_mysql($data['Customer Email'])
		    );
       $result=mysql_query($sql);
       while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	  $candidate[$row['Subject Key']]['field'][]='Customer Other ID';
	  $dist=0.5*$row['dist1']+$row['dist2'];
	  if($dist==0)
	    $candidate[$row['Subject Key']]['points']+=$weight['Same Other ID'];
	  else
	    $candidate[$row['Subject Key']]['points']=$weight['Similar Email']/$dist;
       
       }
     }
 

     //Get similar candidates from emailby name
     if($data['Customer Name']!=''){
     $sql=sprintf("select levenshtein(UPPER(%s),UPPER(`Customer Name`)) as dist1,levenshtein(UPPER(SOUNDEX(%s)),UPPER(SOUNDEX(`Customer Name`))) as dist2, `Customer Key`  from `Customer Dimension`   where dist1<=3 and  `Subject Type`='Customer'   order by dist1,dist2 limit 20"
		  ,prepare_mysql($data['Customer Name'])
		  ,prepare_mysql($data['Customer Name'])
		  );
     $result=mysql_query($sql);
     while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $candidate[$row['Subject Key']]['field'][]='Customer Name';
       $dist=0.5*$row['dist1']+$row['dist2'];
       if($dist==0)
	 $candidate[$row['Subject Key']]['points']+=$weight['Same Customer Name'];
       else
	 $candidate[$row['Subject Key']]['points']=$weight['Similar Customer Name']/$dist;
       
     }
     }
     // Address finger print
     



 }




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


 function create($data=false){
   global $myconf;
   $this->unknown_contact=$myconf['unknown_contact'];
   $this->unknown_company=$myconf['unknown_company'];
   $this->unknown_customer=$myconf['unknown_customer'];
   $contact_name=$this->unknown_contact;
   $company_name=$this->unknown_company;
   $unique_id=$this->get_id();
   
   $type='Unknown';

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
       $data_contact['email type']='Work';
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

    $sql=sprintf("insert into `Customer Dimension` (`Customer ID`,`Customer Main Contact Key`,`Customer Main Contact Name`,`Customer Name`,`Customer File As`,`Customer Type`,`Customer Company Key`,`Customer Main Address Key`,`Customer Main Location`,`Customer Main XHTML Address`,`Customer Main XHTML Email`,`Customer Email`,`Customer Main Email Key`,`Customer Main Telephone`,`Customer Main Telephone Key`,`Customer Main Address Header`,`Customer Main Address Town`,`Customer Main Address Postal Code`,`Customer Main Address Country Region`,`Customer Main Address Country`,`Customer Main Address Country Key`) values (%d,%d,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
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
      

      if(isset($data['has_shipping']) and $data['has_shipping'] and  isset($data['shipping_data']) and is_array($data['shipping_data'])){
	
	if($data['same_address'] and $data['same_contact'] and $data['same_company'] and $data['same_telephone']){
	  $this->add_ship_to('shipping_same_as_main','Yes');
      }else{
	  
	  $this->shipping['contact_key']='';
	  $this->shipping['contact']='';
	  $this->shipping['company_key']='';
	  $this->shipping['company']='';
	  $this->shipping['telephone']='';
	  $this->shipping['telephone_key']='';
	  $this->shipping['email']='';
	  $this->shipping['email_key']='';
	  $this->shipping['address']='';
	  $this->shipping['address_key']='';
	  $this->shipping['address_country_key']='';
	  


	if(!$data['same_address']){
	  $shipping_address=new Address('new',$data['shipping_data']);
	  $this->shipping['address_key']=$shipping_address->id;
	  $this->shipping['address']=$shipping_address->get('XHTML Address');
	  $this->shipping['address_country_key']=$shipping_address->get('Address Country Key');
	}else{
	  $this->shipping['address_key']=$this->get('Customer Main Address Key');
	  $this->shipping['address']=$this->get('Customer Main XHTML Address');
	  $this->shipping['address_country_key']=$this->get('Customer Main Address Country Key');
	}

	if(!$data['same_company']){
	  $this->shipping['company_key']=false;
	  $this->shipping['company']=$data['shipping_data']['company'];
	}else{
	  $this->shipping['company_key']=$this->get('Customer Company Key');
	  $this->shipping['company']=$this->get('Customer Company Name');

	}

	if(!$data['same_contact']){

	  if(!$this->shipping['company_key']){
	    $this->shipping['contact_key']=false;
	    $this->shipping['contact']=$data['shipping_data']['name'];
	  }else{
	    //add a new contact
	    $_data=array(
			 'name'=>$data['shipping_data']['name'],
			 'email'=>$data['shipping_data']['email'],
			 'telephone'=>$data['shipping_data']['telephone'],
			 'address_key'=>$this->shipping['address_key']

			 );
	    $shipping_contact=new Contact('new',$_data);
	    $this->shipping['contact_key']=$shipping_contact->id;
	    $this->shipping['contact']=$shipping_contact->get('Contact Name');
	  }


	}else{
	  $shipping['contact']=$this->get('Customer Main Contact Name');
	  $shipping['contact_key']=$this->get('Customer Main Contact Key');
	  
	}
	$shipping_contact_key=$shipping['contact_key'];


// 	if(!$data['same_email']){
// 	  $shipping_contact=new Contact($shipping_contact_key);
// 	  if($shipping_contact->id){
// 	    $shipping_contact->add_email(array('email'=>$data['shipping_data']['email']));
// 	    $this->shipping['email_key']=$shipping_contact->add_email;
// 	    if($this->shipping['email_key']){
// 	    $email=new Email($this->shipping['email_key']);
// 	    $this->shipping['email_key']=$email->display('html');
// 	    }else
// 	      $this->shipping['email_key']='';
// 	  }else{
// 	    $this->shipping['email_key']='';
// 	    $this->shipping['email']='<a href="mailto:'.$data['shipping_data']['email'].'">'.$data['shipping_data']['email'].'</a>';
// 	  }
// 	}else{
// 	   $this->shipping['email_key']=$this->get('Customer Main Email Key');
// 	   $this->shipping['email']=$this->get('Customer Main XHTML Email');
// 	}

	if(!$data['same_telephone']){
	  $shipping_contact=new Contact($shipping_contact_key);
	  if($shipping_contact->id){
	    $shipping_contact->add_tel(array(
					     'Telecom Original Number'=>$data['shipping_data']['telephone']
					     )
				       );
	    
	    if($shipping_contact->add_telecom){
	      //  print "hola\n";
	      $shipping_tel=new Telecom($shipping_contact->add_telecom);

	      $this->shipping['telephone_key']=$shipping_tel->id;
	      $this->shipping['telephone']=$shipping_tel->display('html');
	    }
	    else{
	      $this->shipping['telephone_key']='';
	      $this->shipping['telephone']=$data['shipping_data']['telephone'];
	    }
	  }else{
	    $this->shipping['telephone_key']='';
	    $this->shipping['telephone']=$data['shipping_data']['telephone'];
	  }
	}else{
	   $this->shipping['telephone_key']=$this->get('Customer Main Telephone Key');
	   $this->shipping['telephone']=$this->get('Customer Main Telephone');

	}
	
	//print_r($this->shipping);
	
	$this->add_ship_to('other','Yes');
      }

      }
      //      $this->data['Customer Last Ship To Key']=$this->data['Customer Main Ship To Key']
      
    }else{
      print "Error, customer con not be created\n";exit;
      
    }


 }

 function add_ship_to($tipo='shipping_same_as_main',$is_principal='No'){
   
   $is_active='Yes';
   


   if($tipo='shipping_same_as_main'){

     $contact_key=$this->get('Customer Main Contact Key');
     $contact_name=$this->get('Customer Main Contact Name');
     $company_key=$this->get('Customer Company Key');
     $company_name=$this->get('Customer Company Name');
     $tel=$this->get('Customer Main Telephone');
     $tel_key=$this->get('Customer Main Telephone Key');
     $email=$this->get('Customer Main XHTML Email');
     $email_key=$this->get('Customer Main Email Key');
     $address=$this->get('Customer Main XHTML Address');
     $address_key=$this->get('Customer Main Address Key');
     $country_key=$this->get('Customer Main Address Country Key');
     // print_r($this->data);
  }else{

     $contact_key=$this->shipping['contact_key'];
     $contact_name=$this->shipping['contact'];
     $company_key=$this->shipping['company_key'];
     $company_name=$this->shipping['company'];
     $tel=$this->shipping['telephone'];
     $tel_key=$this->shipping['telephone_key'];
     $email=$this->shipping['email'];
     $email_key=$this->shipping['email_key'];
     $address=$this->shipping['address'];
     $address_key=$this->shipping['address_key'];
     $country_key=$this->shipping['address_country_key'];
     //     print "_cacacaca";
     // print"$address xx\n";exit;
   }

   $sql=sprintf("insert into `Ship To Dimension` (`Ship To Customer Key`,`Ship To Contact Name`,`Ship To Company Name`,`Ship To XHTML Address`,`Ship To Telephone`,`Ship To XHTML Email`,`Ship To Contact Key`,`Ship To Address Key`,`Ship To Country Key`,`Ship To Company Key`,`Ship To Email Key`,`Ship To Telecom Key`,`Ship To Is Active`,`Ship To Is Principal`) values (%d,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
		,$this->id
		,prepare_mysql($contact_name)
		,prepare_mysql($company_name)
		,prepare_mysql($address)
		,prepare_mysql($tel)
		,prepare_mysql($email)
		,prepare_mysql($contact_key)
		,prepare_mysql($address_key)
		,prepare_mysql($country_key)
		,prepare_mysql($company_key)
		,prepare_mysql($email_key)
		,prepare_mysql($tel_key)
		,prepare_mysql($is_active)
		,prepare_mysql($is_principal)
		);
   
   
   if(mysql_query($sql)){

   $ship_to_key = mysql_insert_id();
   
   
   $sql=sprintf("select count(*) as total,sum(if(`Ship To Is Active`='Yes',1,0)) as active from `Ship To Dimension` where `Ship To Customer Key`=%d ",$this->id);
   // print $sql;
 $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $active=$row['active'];
     $total=$row['total'];
   }
   
   if($is_principal='Yes'){
     
     $sql=sprintf("update `Ship To Dimension` set `Ship To Is Principal`='No' where `Ship To Customer Key`=%d ",$this->id);
     mysql_query($sql);
     $address=new Address($address_key);

     $sql=sprintf("update `Customer Dimension` set `Customer Last Ship To Key`=%s,`Customer Main Ship To Key`=%s,`Customer Main Ship To Header`=%s,`Customer Main Ship To Town`=%s,`Customer Main Ship To Postal Code`=%s,`Customer Main Ship To Country Region`=%s,`Customer Main Ship To Country`=%s,`Customer Main Ship To Country Key`=%s,`Customer Active Ship To Records`=%d,`Customer Total Ship To Records`=%d where `Customer Key`=%d"
		   ,prepare_mysql($ship_to_key)
		  ,prepare_mysql($ship_to_key)
		  ,prepare_mysql($address->display('header'))
		  ,prepare_mysql($address->get('address town'))
		  ,prepare_mysql($address->get('postal code'))
		  ,prepare_mysql($address->get('country region'))
		  ,prepare_mysql($address->get('address country name'))
		  ,prepare_mysql($address->get('address country key'))
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
 }else{
   print "Error , can not create Ship to\n";exit;
 }


 }
  /*
   Function: get_base_data
   Returns an array with the default field values
   */
 function base_data(){
   $this->base_data=array();

   $ignore_fields=array('Customer Key');

   $result = mysql_query("SHOW COLUMNS FROM `Customer Dimension`");
   if (!$result) {
     echo 'Could not run query: ' . mysql_error();
     exit;
   }
   if (mysql_num_rows($result) > 0) {
     while ($row = mysql_fetch_assoc($result)) {
       if(!in_array($row['Field'],$ignore_fields))
	 $this->base_data[$row['Field']]=$row['Default'];
     }
   }
   

 }


 function update($key,$data=false,$args='false'){
   
   switch($key){
   case('multiple'):
     $this->base_data();
     break;
   case('no normal data'):
   case('no_normal_data'):
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
      $address_fuzzy=false;
      $email_fuzzy=false;
      $tel_fuzzy=false;
      $contact_fuzzy=false;


      $address=new Address($this->get('customer main address key'));
      if($address->get('Fuzzy Address'))
	$address_fuzzy=true;
      



     break;
   case('orders'):
   case('orders_data'):
     

     $sigma_factor=3.2906;//99.9% value assuming normal distribution

     $sql="select min(`Order Date`) as first_order_date ,max(`Order Date`) as last_order_date,count(*)as orders, sum(if(`Order Current Payment State` like '%Cancelled',1,0)) as cancelled,  sum( if(`Order Current Payment State` like '%Paid%'    ,1,0)) as invoiced,sum( if(`Order Current Payment State` like '%Refund%'    ,1,0)) as refunded,sum(if(`Order Current Dispatch State`='Unknown',1,0)) as unknown   from `Order Dimension` where `Order Customer Key`=".$this->id;

     $this->data['customer orders']=0;
     $this->data['customer orders cancelled']=0;
     $this->data['customer orders invoiced']=0;
     $this->data['customer first order date']='';
     $this->data['customer last order date']='';
     $this->data['customer order interval']='';
     $this->data['customer order interval std']='';
     $this->data['actual customer']='No';
     $this->data['new served customer']='No';
     $this->data['active customer']='Unkwnown';
     
     //print "$sql\n";     
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       
       $this->data['customer orders']=$row['orders'];
       $this->data['customer orders cancelled']=$row['cancelled'];
       $this->data['customer orders invoiced']=$row['invoiced'];
       
       
       if($this->data['customer orders']>0){
	 $this->data['customer first order date']=$row['first_order_date'];
	 $this->data['customer last order date']=$row['last_order_date'] ;
	 $this->data['actual customer']='Yes';
       }else{
	 $this->data['actual customer']='No';
	 $this->data['customer type by activity']='Prospect';
	 
       }
       
       if($this->data['customer orders']==1){
	 $sql="select avg((`Customer Order Interval`)+($sigma_factor*`Customer Order Interval STD`)) as a from `Customer Dimension`";
	 
	 $result2=mysql_query($sql);
	 if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)){
	   $average_max_interval=$row2['a'];
	   if(is_numeric($average_max_interval)){
	     if(   (strtotime('now')-strtotime($this->data['customer last order date']))/(3600*24)  <  $average_max_interval){
	       $this->data['active customer']='Maybe';
	       $this->data['customer type by activity']='New';
	       
	     }else{
	       $this->data['active customer']='No';
	       $this->data['customer type by activity']='Inactive';
	       
	     }
	   }else
	     $this->data['active customer']='Unknown';
	   $this->data['customer type by activity']='Unknown';
	   
	   
	 }	
	 
       }
       
       if($this->data['customer orders']>1){
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
	 
	 
	 $this->data['customer order interval']=average($intervals);
	 $this->data['customer order interval std']=deviation($intervals);
	 
	 //print  $this->data['customer order interval']." ".$this->data['customer order interval std']."\n";
	 
	 if((date('U')-$last_date)<($this->data['customer order interval']+($sigma_factor*$this->data['customer order interval std']))){
	   $this->data['active customer']='Yes';
	   $this->data['customer type by activity']='Active';
	 }else{
	   $this->data['active customer']='No';
	   $this->data['customer type by activity']='Inactive';

	 }
       }
       
      
       
       $sql=sprintf("update `Customer Dimension` set `Customer Orders`=%d,`Customer Orders Cancelled`=%d,`Customer Orders Invoiced`=%d,`Customer First Order Date`=%s,`Customer Last Order Date`=%s,`Customer Order Interval`=%s,`Customer Order Interval STD`=%s,`Active Customer`=%s,`Actual Customer`=%s,`Customer Type by Activity`=%s where `Customer Key`=%d",
		    $this->data['customer orders']
		    ,$this->data['customer orders cancelled']
		    ,$this->data['customer orders invoiced']
		    ,prepare_mysql($this->data['customer first order date'])
		    ,prepare_mysql($this->data['customer last order date'])
		    ,prepare_mysql($this->data['customer order interval'])
		    ,prepare_mysql($this->data['customer order interval std'])
		    ,prepare_mysql($this->data['active customer'])
		    ,prepare_mysql($this->data['actual customer'])
		    ,prepare_mysql($this->data['customer type by activity'])
		    ,$this->id
		    );
       // print "$sql\n";
       //exit;
       mysql_query($sql);
     }


      //      $sql=sprintf("select `Customer Orders` from `Customer Dimension` order by `Customer Order`");



      break;
   }

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
   case('Total Balance'):
     return money($this->data['Customer Total Balance']);
     break;
   case('Total Net Per Order'):
     if($this->data['Customer Orders Invoiced']>0)
       return money($this->data['Customer Total Balance']/$this->data['Customer Orders Invoiced']);
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
    
      print "Warning no ship to key $ship_to_key un customer*** .php\n";
     
       exit;
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

   print "Error ->$key not found in get,* from Customer\n";
   exit;
   return false;

 }


  function get_id(){
    
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


 

 }
?>