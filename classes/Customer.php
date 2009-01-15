<?
include_once('Contact.php');
include_once('Order.php');
include_once('Address.php');

class Customer{
  var $db; 
  var $id=false;
  var $contact_data=false;


  function __construct($arg1=false,$arg2=false) {
    $this->db =MDB2::singleton();
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
    else
      return false;
   $result =& $this->db->query($sql);
   if($this->data=$result->fetchRow()){	     
      $this->id=$this->data['customer key'];
      
 //      $o_main_bill_address=new address($this->data['main_bill_address']);
//       if($o_main_bill_address->id){
// 	$this->data['main_bill_address_id']=$o_main_bill_address->id;
// 	$this->data['main_bill_address']=$o_main_bill_address->display('html');
	
//       }else{
// 	  $this->data['main_bill_address_id']=false;
// 	  $this->data['main_bill_address']='';
//       }
//       unset($o_main_bill_address);

//       $o_main_contact_name=new name($this->data['main_contact_name']);
//       if($o_main_contact_name->id){
// 	$this->data['main_contact_name_id']=$o_main_contact_name->id;
// 	$this->data['main_contact_name']=$o_main_contact_name->display('html');
//       }else{
// 	//try to auto fix it
// 	$this->data['main_contact_name_id']=false;
// 	$this->data['main_contact_name']='';
//       }
//       unset($o_main_contact_name);

//       $o_main_email=new email($this->data['main_email']);
//       if($o_main_email->id){
	
// 	$this->data['main_email_id']=$o_main_email->id;
// 	$this->data['main']['email']=$o_main_email->display();
// 	$this->data['main']['formated_email']=$o_main_email->display('link');
//       }else{
// 	//try to auto fix it
// 	$this->data['main_email_id']=false;
// 	$this->data['main']['email']='';
// 	$this->data['main']['formated_email']='';
//       }
//       unset($o_main_email);

//       $o_main_tel=new telecom($this->data['main_tel']);
//       if($o_main_tel->id){
// 	$this->data['main_tel_id']=$o_main_tel->id;
// 	$this->data['main_tel']=$o_main_tel->display('link');
//       }else{
// 	//try to auto fix it
// 	$this->data['main_tel_id']=false;
// 	$this->data['main_tel']='';
//       }
//       unset($o_main_tel);

     }


  }
 function load($key=''){
    switch($key){
    case('location'):
      global $myconf;
      if($this->data['main_bill_address_id']==''){
	$this->data['location']['country_code']=$myconf['country_code'];
	$this->data['location']['town']='';
      }else{
     
      $sql=sprintf('select code,town from  address  left join list_country on (country=list_country.name) where address.id=%d ',$this->data['main_bill_address_id']);
      //     print_r($this->data);
      // print $sql;
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){	     
	$this->data['location']['country_code']=$row['code'];
	$this->data['location']['town']=$row['town'];
      }

      }
      break;
    case('contact_data'):
    case('contact data'):
      $contact=new Contact($this->get('customer contact key'));
      if($contact->id)
	$this->contact_data=$contact->data;
      else
	$this->errors[]='Error geting contact data object. Contact key:'.$this->get('customer contact key');
      break;
    }

 }


 function create($data=false){
   // type:  Company|Person|Unknown
   // contact_name:
   // company_name:
   // address_data[]
   // email:
   // 'email tyoe': Work

   global $myconf;

   //  print_r($data);

   $this->unknown_contact=$myconf['unknown_contact'];
   $this->unknown_company=$myconf['unknown_company'];
   $this->unknown_customer=$myconf['unknown_customer'];
   //print_r($data);
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
   
   // print_r($data_contact);
   $main_contact=new contact('new',$data_contact);
   //   exit;
   //print_r($main_contact->data);
   if($type=='Company'){
     $company=new company('new',
			  array('name'=>$company_name,'contact key'=>$main_contact->id)
			  );
     // print_r($company->data);
     $customer_name=$company->get('company name');
     $customer_file_as=$company->get('company file as');

     $company_key=$company->id;


   }else{
     $customer_name=$main_contact->get('contact name');
     $customer_file_as=$main_contact->get('contact file as');
     $company_key='';
   }

   if($customer_name=='Unknown Contact' or $customer_name=='Unknown Company')
     $customer_name=$this->unknown_customer;
    $sql=sprintf("insert into `Customer Dimension` (`Customer ID`,`Customer Main Contact Key`,`Customer Main Contact Name`,`Customer Name`,`Customer File As`,`Customer Type`,`Customer Company Key`,`Customer Main Address Key`,`Customer Main Location`,`Customer Main XHTML Email`,`Customer Email`,`Customer Main Telephone`) values (%d,%d,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
		 ,$unique_id
		 ,$main_contact->id
		 ,prepare_mysql($main_contact->get('contact name'))
		 ,prepare_mysql($customer_name)
		 ,prepare_mysql($customer_file_as)

		 ,prepare_mysql($type)
		 ,prepare_mysql($company_key)
		 ,prepare_mysql($main_contact->get('contact main address key'))
		 ,prepare_mysql($main_contact->get('contact main location'))
		 ,prepare_mysql($main_contact->get('Contact Main XHTML Email'))
		 ,prepare_mysql(strip_tags($main_contact->get('Contact Main XHTML Email')))
		 ,prepare_mysql($main_contact->get('Contact Main Telephone'))
	       );
  //   print_r($main_contact->data);
//     print "$sql\n";
//     exit;
    $affected=& $this->db->exec($sql);
    if (PEAR::isError($affected)) {

      return; 
    }    

    $this->id = $this->db->lastInsertID();  
    $this->get_data('id',$this->id);

    //    print_r($this->data);
 }


 function update($key,$data=false,$args='false'){

   switch($key){
   case('no normal data'):
   case('no_normal_data'):
      $sql="select min(`Order Header Date`) as date   from `Order Header Dimension` where `Order Header Customer Key`=".$this->id;
      $result =& $this->db->query($sql);
      if($row=$result->fetchRow()){
	$first_order_date=date('U',strtotime($row['date']));
	if($row['date']!='' 
	   and (
		$this->data['customer first contacted date']=='' 
		or ( date('U',strtotime($this->data['customer first contacted date']))>$first_order_date  )
		)
	   ){
	  $sql=sprintf("update `Customer Dimension` set `Customer First Contacted Date`=%d, where `Customer Key`=%d"
		       ,prepare_mysql($row['date'])
		       ,$this->id
		       );
	  $this->db->exec($sql);
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

     $sql="select min(`Order Header Date`) as first_order_date ,max(`Order Header Date`) as last_order_date,count(*)as orders, sum(if(`Order Header Current State`='Cancelled',1,0)) as cancelled,  sum(if(`Order Header Current State`='Consolidated',1,0)) as invoiced,sum(if(`Order Header Current State`='Unknown',1,0)) as unknown   from `Order Header Dimension` where `Order Header Customer Key`=".$this->id;
     $result =& $this->db->query($sql);
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

      

      if($row=$result->fetchRow()){	     
	$this->data['customer orders']=$row['orders'];
	$this->data['customer orders cancelled']=$row['cancelled'];
	$this->data['customer orders invoiced']=$row['invoiced'];
      }
      
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
	$result2 =& $this->db->query($sql);
	if($row2=$result2->fetchRow()){
	  $average_max_interval=$row2['a'];
	  if(is_numeric($average_max_interval)){
	    if(date('U')-$last_date<$average_max_interval){
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
	 $sql="select `Order Header Date` as date from `Order Header Dimension` where `Order Header Customer Key`=".$this->id." order by `Order Header Date`";
	 $result =& $this->db->query($sql);
	 $last_order=false;
	 $intervals=array();
	 while($row=$result->fetchRow()){
	   $this_date=date('U',strtotime($row['date']));
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
      $this->db->exec($sql);
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
     $this->db->exec($sql);
     
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
     


     $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
		  ,$date
		  ,prepare_mysql('CUST')
		  ,prepare_mysql($this->id)
		  ,prepare_mysql($tipo)
		  ,'NULL'
		  ,prepare_mysql('NEW')
		  ,prepare_mysql($user)
		  ,prepare_mysql($old)	 
		  ,prepare_mysql($new)	 
		  ,prepare_mysql($note)
		  );
     //  print $sql;
     $this->db->exec($sql);
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
     $this->db->exec($sql);
     $this->msg=_('Note Added');
     return true;

   }
 }


 function get($key){

   $key=strtolower($key);
   if(isset($this->data[$key]))
     return $this->data[$key]; 
   
   if(preg_match('/^contact /i',$key)){
     if(!$this->contact_data)
       $this->load('contact data');
     if(isset($this->contact_data[$key]))
     return $this->contact_data[$key]; 
   }


   switch($key){
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
   
 }


  function get_id(){
    
    $sql="select max(`Customer ID`)  as customer_id from `Customer Dimension`";
    $result =& $this->db->query($sql);
    if( $row=$result->fetchRow()){
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