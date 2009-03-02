<?

include_once('Staff.php');
include_once('Supplier.php');
include_once('Customer.php');
include_once('Store.php');

class Order{
 var $data=array();
  var $items=array();
  var $status_names=array();
  var $id=false;
  var $tipo;
  var $staus='new';

  function __construct($arg1=false,$arg2=false) {

    $this->status_names=array(0=>'new');
     
    if(is_numeric($arg1)){
      $this->get_data('id',$arg1);
      return false;
    }
     
    if(preg_match('/new/i',$arg1)){
      $this->create_order($arg2);
    }
  }

  function create_order($data){
    if(!isset($data['type']))
      return;
    $type=$data['type'];
    switch($type){
      

    case('direct_data_injection'):
      $this->compare_addresses($data['cdata']);
      $data['cdata']['same_address']=$this->same_address;
      $data['cdata']['same_contact']=$this->same_contact;
      $data['cdata']['same_company']=$this->same_company;
      $data['cdata']['same_telephone']=$this->same_telephone;

      
      
      $customer_identification_method='other id';

      $customer_id=$this->find_customer($customer_identification_method,$data['cdata']);

      $customer=new Customer($customer_id);
      

      $this->data['Order Main Ship To Key']=$customer->get('Customer Last Ship To Key');
      $ship_to_key=$this->data['Order Main Ship To Key'];
      
      
      $ship_to=$customer->get('xhtml ship to',$ship_to_key);

      if(!isset($data['store_code']))
	$data['store_code']='Unknown';
      $store=new Store('code',$data['store_code']);
      if(!$store->id)
	$store=new Store('unknown');
      $this->data['Order Date']=$data['order date'];
      $this->data['Order Public ID']=$data['order id'];
      $this->data['Order File As']=$data['order id'];
      $this->data['Order Type']=$data['Order Type'];

      $this->data['Order Customer Key']=$customer->id;
      $this->data['Order Customer Name']=$customer->get('Customer Name');
      $this->data['Order Current Dispatch State']='In Process';
      $this->data['Order Current Payment State']='Waiting Invoice';
      $this->data['Order Current XHTML State']='In Process';
      $this->data['Order Customer Message']=_trim($data['order customer message']);
      $this->data['Order Original Data MIME Type']=$data['order original data mime type'];
      $this->data['Order Original Data']=$data['order original data'];
      $this->data['Order Original Data Source']=$data['order original data source'];


      $this->data['Order Original Metadata']=$data['order original metadata'];
      $this->data['Order Main Store Key']=$store->id;
      $this->data['Order Main Store Code']=$store->get('code');
      $this->data['Order Main Store Type']=$store->get('type');
      $this->data['Order Multiple Stores']=0;

      $this->data['Order XHTML Ship Tos']=$ship_to;
      //$this->data['Order Main Ship To Key']=$ship_to_key;
      $this->data['Order Multiple Ship Tos']=0;
      
      if(isset($data['metadata_id']))
	$this->data['Order Metadata']=$data['metadata_id'];
      else
	$this->data['Order Metadata']='';



      $this->create_order_header();

      $line_number=1;
      foreach($data['products'] as $product_data){

	$product_data['date']=$this->data['Order Date'];
	$product_data['line_number']=$line_number;
	$product_data['metadata']=$data['metadata_id'];
	$this->add_order_transaction($product_data);
	$line_number++;
      }
      $sql="select sum(`Order Transaction Gross Amount`) as gross,sum(`Order Transaction Total Discount Amount`) as discount from `Order Transaction Fact` where `Order Key`=".$this->data['Order Key'];
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	$sql=sprintf("update `Order Dimension` set `Order Gross Amount`=%.2f, `Order Discount Amount`=%.2f where  `Order Key`=%d ",$row['gross'],$row['discount'],$this->data['Order Key']);

	mysql_query($sql);
      }
      
      


      $customer->update('orders');       
      $customer->update('no normal data');

//       $this->cutomer_rankings();
      
      switch($_SESSION['lang']){
      default:
	  $abstract=sprintf('Order <a href="order.php?id=%d">%s</a>',$this->data['Order Key'],$this->data['Order Public ID']);
	  $note=sprintf('%s (<a href="customer.php?id=%d">%s) place an order at %s'
			,$customer->get('Customer Name')
			,$customer->id
			,$customer->get('Customer ID')
			,strftime("%e %b %Y %H:%M",strtotime($this->data['Order Date']))
			);
	}

	$sql=sprintf("insert into `History Dimension` (`History Date`,`Subject`,`Subject Key`,`Action`,`Direct Object`,`Direct Object Key`,`History Details`,`Author Key`,`Author Name`) values(%s,'Customer','%s','Placed','Order',%d,%s,0,%s)"
		     ,prepare_mysql($this->data['Order Date'])
		     ,$customer->id
		     ,$this->data['Order Key']
		     ,prepare_mysql($note)
		     ,prepare_mysql(_('System'))
		     );
	mysql_query($sql);
	$history_id= mysql_insert_id();
	$abstract.=' (<span class="like_a" onclick="showdetails(this)" d="0" id="ch'.$history_id.'"  hid="'.$history_id.'">'._('view details').'</span>)';
	$sql=sprintf("update `History Dimension` set `History Abstract`=%s where `History Key`=%d",prepare_mysql($abstract),$history_id);
	//	print "$sql\n";
	mysql_query($sql);
	

	
      break;
    case('imap_email_mals-e'):
      $ip = gethostbyname('imap.gmail.com');
      $ip ='imap.gmail.com';
      $mbox = imap_open("{".$ip.":993/imap/ssl/novalidate-cert}INBOX", $data['email'], $data['pwd'])or die("can't connect: " . imap_last_error());
      $imap_obj = imap_check($mbox);
      $imap_obj->Nmsgs;

      for($i=1;$i<=$imap_obj->Nmsgs;$i++){
	print "MENSSAGE NUMBER $i\n";
	$email= imap_body($mbox,$i);
	$email = mb_convert_encoding($email, "UTF-8", "UTF-8, ISO-8859-1");
	//	print $email;

// 	print "\n**********\n".mb_detect_encoding($email,'UTF-8, ISO-8859-1')."\n";
// 	exit;
	if(preg_match('/\nUsername\s*:\s*\d+/',$email,$match))
	  $edata['username']=preg_replace('/username\s*:\s*/i','',_trim($match[0]));
	if(preg_match('/\nDate\s*:\s*[a-zA-Z0-9\-\s]+:\d\d\s*/',$email,$match)){
	  $date=preg_replace('/date\s*:\s*/i','',_trim($match[0]));
	  $date=preg_replace('/\-/','',$date);

	  $edata['date']=date("Y-m-d H:i:s",strtotime($date));
	}
	if(preg_match('/\nShopper Id\s*:\s*\d+/',$email,$match))
	  $edata['shopper_id']=preg_replace('/shopper id\s*:\s*/i','',_trim($match[0]));
	if(preg_match('/\nIP number\s*:\s*\d+\.\d+\.\d+\.\d+/',$email,$match))
	  $edata['ip_number']=preg_replace('/ip number\s*:\s*/i','',_trim($match[0]));
	if(preg_match('/\nFor payment by\s*:\s*.+\n/',$email,$match))
	  $edata['for_payment_by']=preg_replace('/for payment by\s*:\s*/i','',_trim($match[0]));
      
	if( !preg_match('/\nTel\s*:\s*\n/',$email)  and  preg_match('/\nTel\s*:\s*.+\n/',$email,$match))
	  $edata['tel']=preg_replace('/tel\s*:\s*/i','',_trim($match[0]));

	if( !preg_match('/\nFax\s*:\s*\n/',$email)  and preg_match('/\nFax\s*:\s*.+\n/',$email,$match))
	  $edata['fax']=preg_replace('/fax\s*:\s*/i','',_trim($match[0]));

	if(!preg_match('/\nEmail\s*:\s*\n/',$email)  and    preg_match('/\nEmail\s*:\s*.+\n/',$email,$match))
	  $edata['email']=preg_replace('/email\s*:\s*/i','',_trim($match[0]));

	$edata['voucher']='0.00';
	if(preg_match('/\nVoucher\s*:\s*[0-9\.`-]+\s*\n/',$email,$match)){
	  $edata['voucher']=preg_replace('/voucher\s*:\s*/i','',_trim($match[0]));
	  if($edata['voucher']=='-0.00')
	    $edata['voucher']='0.00';
	}
	$edata['discount']='0.00';
	if(preg_match('/\nDiscount\s*:\s*[0-9\.`-]+\s*\n/',$email,$match)){
	  $edata['discount']=preg_replace('/discount\s*:\s*/i','',_trim($match[0]));
	  if($edata['discount']=='-0.00')
	    $edata['discount']='0.00';
	}

      	$edata['subtotal']='0.00';
	if(preg_match('/\nSubtotal\s*:\s*[0-9\.`-]+\s*\n/',$email,$match)){
	  $edata['subtotal']=preg_replace('/subtotal\s*:\s*/i','',_trim($match[0]));
	  if($edata['subtotal']=='-0.00')
	    $edata['subtotal']='0.00';
	}
	$edata['tax']='0.00';
	if(preg_match('/\nTax\s*:\s*[0-9\.\-]+\s*\n/',$email,$match)){
	  $edata['tax']=preg_replace('/tax\s*:\s*/i','',_trim($match[0]));
	  if($edata['tax']=='-0.00')
	    $edata['tax']='0.00';
	}


	$edata['total']='0.00';
	if(preg_match('/\nTOTAL\s*:\s*[0-9\.`-]+\s*\n/',$email,$match)){
	  $edata['total']=preg_replace('/total\s*:\s*/i','',_trim($match[0]));
	  if($edata['total']=='-0.00')
	    $edata['total']='0.00';
	}
	$edata['shipping']='0.00';
	if(preg_match('/\nShipping\s*:\s*[0-9\.`-]+\s*\n/',$email,$match)){
	  $edata['shipping']=preg_replace('/shipping\s*:\s*/i','',_trim($match[0]));
	  if($edata['shipping']=='-0.00')
	    $edata['shipping']='0.00';
	}
// 	print_r($edata);
// 	exit;
	//Delivery data

	$tags=array(' Inv Name',' Inv Company',' Inv Address',' Inv City',' Inv State',' Inv Pst Code',' Inv Country',' Ship Name',' Ship Company',' Ship Address',' Ship City',' Ship State',' Ship Pst Code',' Ship Country',' Ship Tel');
	foreach($tags as $tag){
	  if(preg_match('/\n'.$tag.'\s*:.*\n/',$email,$match))
	    $edata[strtolower(_trim($tag))]=preg_replace('/'._trim($tag).'\s*:\s*/i','',_trim($match[0]));
	}


	$lines=preg_split('/\n/',$email);
	$products=false;
	$_products=array();

	$preline='';
	foreach($lines as $line){

	  $line=_trim($line);
	  //   print "$products $line\n";
	  if(preg_match('/Product : Quantity : Price/',$line))
	    $products=true;
	  elseif(preg_match('/Voucher  :/',$line))
	    $products=false;
	  elseif($products and preg_match('/:\s*[0-9\.]+\s*:\s*[0-9\.]+$/',$line)){
	    $_products[]=_trim($preline.$line);
	    $preline='';
	  }elseif($products)
	     $preline.=$line.' ';
   


	}

      



	//	print "$email";
	//print_r($_products);
	

	global $myconf;
	$cdata['contact_name']=$edata['inv name'];
	$cdata['type']='Person';
	$__tel='';
	$__company='';
	$__name=='';
	if(isset($edata['tel']) and $edata['tel']!=''){
	  $cdata['telephone']=$edata['tel'];
	  $__tel=$edata['tel'];
	}
	if(isset($edata['fax']) and $edata['fax']!='')
	  $cdata['fax']=$edata['fax'];
	if(isset($edata['email']) and $edata['email']!='')
	  $cdata['email']=$edata['email'];
	if($edata['inv company']!=''){
	  $cdata['type']='Company';
	  $cdata['company_name']=$edata['inv company'];
	  $__company=$edata['inv company'];
	}
 
 	$cdata['address_data']=array(
				     'type'=>'3line'
				     ,'address1'=>$edata['inv address']
				     ,'address2'=>''
				     ,'address3'=>''
				     ,'town'=>$edata['inv city']
				     ,'country'=>$edata['inv country']
				     ,'country_d1'=>$edata['inv state']
				     ,'country_d2'=>''
				     ,'default_country_id'=>$myconf['country_id']
				     ,'postcode'=>$edata['inv pst code']
				     
				     );
 
	$__name=$edata['inv name'];
	$cdata['address_inv_data']=array(
				     'type'=>'3line'
				     ,'name'=>$__name
				     ,'company'=>$edata['inv company']
				     ,'telephone'=>$__tel
				     ,'address1'=>$edata['inv address']
				     ,'address2'=>''
				     ,'address3'=>''
				     ,'town'=>$edata['inv city']
				     ,'country'=>$edata['inv country']
				     ,'country_d1'=>$edata['inv state']
				     ,'country_d2'=>''
				     ,'default_country_id'=>$myconf['country_id']
				     ,'postcode'=>$edata['inv pst code']
				     
				     );
	$cdata['address_shipping_data']=array(
					      'type'=>'3line'
					      ,'name'=>$edata['ship name']
					      ,'company'=>$edata['ship company']
					      ,'telephone'=>$edata['ship tel']
					      ,'address1'=>$edata['ship address']
					      ,'address2'=>''
					      ,'address3'=>''
					      ,'town'=>$edata['ship city']
					      ,'country'=>$edata['ship country']
					      ,'country_d1'=>$edata['ship state']
					      ,'country_d2'=>''
					      ,'default_country_id'=>$myconf['country_id']
					      ,'postcode'=>$edata['ship pst code']
					      );
 
	//check if the addresses are the same:
	$diff_result = array_diff($cdata['address_inv_data'], $cdata['address_shipping_data']);

	if(count($diff_result)==0){

	  $same_address=true;
	  $same_contact=true;
	  $same_company=true;
	  $same_email=true;
	  $same_telaphone=true;


	}else{
	  
	  

	  $percentage=array('address1'=>1,'town'=>1,'country'=>1,'country_d1'=>1,'postcode'=>1);
	  $percentage_address=array();

	  foreach($diff_result as $key=>$value){
	    similar_text($cdata['address_shipping_data'][$key],$cdata['address_inv_data'][$key],$p);
	    $percentage[$key]=$p/100;
	    if(preg_match('/address1|town|^country$|postcode|country_d1/i',$key))
	      $percentage_address[$key]=$p/100;
	  }
	  $avg_percentage=average($percentage);
	  $avg_percentage_address=average($percentage_address);

	  //print "AVG DIFF $avg_percentage $avg_percentage_address \n";
	  
	  if($cdata['address_shipping_data']['name']=='' or !array_key_exists('name',$diff_result) )
	    $same_contact=true;
	  else{
	    $_max=1000000;
	    $irand=mt_rand(0,1000000);
	    $rand=$irand/$_max;
	    if($rand<$percentage['name'] and $percentage['name']>.90 ){
	      $same_contact=true;
	     
	    }else
	      $same_contact=false;
	  }
	  if($cdata['address_shipping_data']['company']=='' or !array_key_exists('company',$diff_result) )
	    $same_company=true;
	  else{
	    $_max=1000000;
	    $irand=mt_rand(0,1000000);
	    $rand=$irand/$_max;
	    //print "xxx ".$percentage['company']."\n";
	    if($rand<$percentage['company']and $percentage['company']>.90){
	      $same_company=true;
	    }else
	      $same_company=false;
	  }
	  
	  if(array_key_exists('telephone',$diff_result) )
	    $same_telephone=false;
	  else
	    $same_telephone=true;


	  if($avg_percentage_address==1)
	    $same_address=true;
	  else
	    $same_address=false;
	  
	  //print "C:$same_contact  CM:$same_company  T:$same_telephone E:$same_email  A:$same_address \n";
	  // exit;

	}
	$cdata['has_shipping']=true;
	$cdata['shipping_data']=$cdata['address_shipping_data'];


	$cdata['same_address']=$same_address;
	$cdata['same_contact']=$same_contact;
	$cdata['same_company']=$same_company;
	$cdata['same_email']=$same_email;
	$cdata['same_telephone']=$same_email;





    
	$customer_identification_method='email';
	$customer_id=$this->find_customer($customer_identification_method,$cdata);
	$customer=new Customer($customer_id);
	$ship_to_key=$customer->data['Customer Last Ship To Key'];
	$ship_to=$customer->get('xhtml ship to',$ship_to_key);

	$store=new Store('code','AW.web');
	if(!$store->id)
	  $store=new Store('unknown');
	
	$this->data['Order Date']=$edata['date'];
	$this->data['Order Public Id']=$edata['shopper_id'];
	$this->data['Order Customer Key']=$customer->id;
	$this->data['Order Customer Name']=$customer->data['customer name'];
	$this->data['Order Current Dispatch State']='In Process';
	$this->data['Order Current Payment State']='Waiting Invoice';
	$this->data['Order Current Xhtml State']='In Process';
	$this->data['Order Customer Message']=_trim($edata['message']);
	$this->data['Order Original Data Mime Type']='text/plain';
	$this->data['Order Original Data']=$email;
	$this->data['order main store key']=$store->id;
	$this->data['order main store code']=$store->get('code');
	$this->data['order main store type']=$store->get('type');
	$this->data['order gross amount']=$edata['subtotal'];
	$this->data['order shipping amount']=$edata['shipping'];

	$this->data['order discount ammont']=$edata['discount']+$edata['voucher'];
	$this->data['order total tax amount']=$edata['tax'];
	$this->data['order main xhtml ship to']=$ship_to;
	$this->data['order ship to addresses']=1;

	//	print "$email";
	//	print_r($edata);
	//exit;

	$this->create_order_header();

	$pdata=array();
	foreach($_products as $product_line){
	  $_data=preg_split('/:/',$product_line);
	  if(count($_data)>3 and count($_data)<6){
	    // print_r($_data);
	    $__code=='';
	    for($j=0;$j<count($_data)-2;$j++){
	      $__code.=$_data[$j].' ';
	    }
	    $__qty=$_data[count($_data)-2];
	    $__amount=$_data[count($_data)-1];
	    $_data=array($__code, $__qty,$__amount);
	    //	    print_r($_data);
	    $this->warnings[]=_('Warning: Delimiter found in product description. Line:').$product_line;
	    //exit;



	  }


	  if(count($_data)==3){
	    preg_match('/^[a-z0-9\-\&\/]+\s/i',$_data[0],$match_code);
	    $code=_trim($match_code[0]);
	    if(in_array($code,$data['product code exceptions']))
	      continue;
	    
	    if(array_key_exists(strtolower($code),$data['product code replacements'])){

	      foreach($data['product code replacements'][strtolower($code)] as $replacement_data){
		if(preg_match('/^'.$replacement_data['line'].'/i',$_data[0]))
		  $code=$replacement_data['replacement'];
	      }

	    }

	    // print_r($_data);
	    $product=new Product('code',$code,$this->data['Order Date']);
	    if(!$product->id){
	      $this->errors[]=_('Error(1): Undentified Product. Line:').$product_line;
	      print "Error(1), product undentified Line: $code $product_line\n";
	      exit;
	    }
	    else{
	      $qty=_trim($_data[1]);
	      // Get here the discounts
	      if(isset($pdata[$product->id]))
		$pdata[$product->id]['qty']=$pdata[$product->id]['qty']+$qty;
	      else
		$pdata[$product->id]=array('code'=>$product->get('product code'),'amount'=>$product->data['Product Price']*$qty,'case_price'=>$product->get['Product Price'],'product_id'=>$product->id,'qty'=>$qty,'family_id'=>$product->data['Product Family Key']);
		
	    }
	  }else{
	    //print_r($_data);
	    $this->errors[]=_('Error(2): Can not read product line. Line:').$product_line;
	    print "Error(2), product undentified Count:".count($_data)." Line:$product_line\n";
	    exit;
	  }
	}
	

	$pdata=$this->get_discounts($pdata,$customer->id,$this->data['Order Date']);
	$line_number=1;
	foreach($pdata as $product_data){
	  $product_data['date']=$this->data['Order Date'];
	  $product_data['line_number']=$line_number;
	  $this->add_order_transaction($product_data);
	  $line_number++;
	}
	// $this->finish_new_order();
      
	 $customer=new Customer($customer_id);
	$customer->update('orders');
	$customer->update('no normal data');
	
	//$this->cutomer_rankings();

	switch($_SESSION['lang']){
	default:
	  $abstract=sprintf('Internet Order <a href="order.php?id=%d">%s</a>',$this->get('order key'),$this->get('order public id'));
	  $note=sprintf('%s (<a href="customer.php?id=%d">%s) place an order by internet using IP:%d at %s'
			,$customer->get('customer name')
			,$customer->id
			,$customer->get('customer id')
			,$edata['ip_number']
			,strftime("%e %b %Y %H:%M",strtotime($this->data['order date']))
			);
	}

	$sql=sprintf("insert into `History Dimension` (`History Date`,`Subject`,`Subject Key`,`Action`,`Direct Object`,`Direct Object Key`,`History Details`,`Author Key`,`Author Name`) values(%s,'Customer','%s','Placed','Order',%d,%s,0,%s)"
		     ,prepare_mysql($this->data['order date'])
		     ,$customer->id
		     ,$this->data['order key']
		     ,prepare_mysql($note)
		     ,prepare_mysql(_('System'))
		     );
	if(mysql_query($sql)){
	$history_id=mysql_insert_id();
	$abstract.=' (<span class="like_a" onclick="showdetails(this)" d="0" id="ch'.$history_id.'"  hid="'.$history_id.'">'._('view details').'</span>)';
	$sql=sprintf("update `History Dimension` set `History Abstract`=%s where `History Key`=%d",prepare_mysql($abstract),$history_id);
	//	print "$sql\n";
	mysql_query($sql);
	}else
	  exit("error can not insert  history");
	
	//	print "$sql\n";
      }
   

    }
  }
  


  function find_customer($method,$data){

    switch($method){
    case('email'):
    case('email strict'):
	
   //    $email=$data['email'];

//       if($email!=''){
// 	$customer=new Customer('email',$email);
// 	if($customer->id)
// 	  return $customer->id;
//       }
       
      $customer=new Customer('new',$data);
      return $customer->id;
      break;

    case('other id'):
      
      if($data['other id']==''){
	$customer=new Customer('new',$data);
	return $customer->id;
      }
      
      $sql=sprintf("select `Customer Key` from `Customer Dimension`  where `Customer Other ID`=%s",prepare_mysql($data['other id']));
      $result =mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	$customer=new Customer($row['Customer Key']);
	return $customer->id;
      }
      $customer=new Customer('new',$data);
      return $customer->id;
      
      break;
 //    case('auto'):
      
//       //get list of posible customers (email);

//       $email=$data['email'];
//       if($email!=''){
// 	$sql=sprintf("select `Customer Key`,(length(`Customer Email`)-levenshtein(`Customer Email`,'%s'))/length(`Customer Email`) as similarity from `Customer Dimension`  where similarity>0  order by similarity limit 500",add_slashes($email));
//       $result =& $this->db->query($sql);
//       $p_customer_by_email=array();
//       while($row=$result->fetchRow()){
// 	$p_customer_by_email[$row['customer key']]=$row['similarity'];
// 	if(!isset($multiplicity[$row['similarity']]))
// 	  $multiplicity[$row['similarity']]=1;
// 	else
// 	  $multiplicity[$row['similarity']];
//       }
      
      


//       break;

    }

  }

  

  function add_order_transaction($data){

    $sql=sprintf("insert into `Order Transaction Fact` (`Estimated Weight`,`Order Date`,`Order Last Updated Date`,`Product Key`,`Current Dispatching State`,`Current Payment State`,`Customer Key`,`Order Key`,`Order Public ID`,`Order Line`,`Order Quantity`,`Ship To Key`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Metadata`) values (%s,%s,%s,%d,%s,%s,%s,%s,%s,%d,%s,%s,%.2f,%.2f,%s) "
		 ,prepare_mysql($data['Estimated Weight'])
		 ,prepare_mysql($data['date'])
		 ,prepare_mysql($data['date'])
		 ,$data['product_id']
		 ,prepare_mysql('Waiting authorization to be picked')
		 ,prepare_mysql('Waiting Payment')
		 ,prepare_mysql($this->get('Order Customer Key'))
		  ,prepare_mysql($this->get('Order Key'))
		 ,prepare_mysql($this->get('Order Public ID'))
		 ,$data['line_number']
		 ,prepare_mysql($data['qty'])
		 ,prepare_mysql($this->data['Order Main Ship To Key'])
		 ,$data['gross_amount']
		 ,$data['discount_amount']
		 ,prepare_mysql($data['metadata'])
		 );
    // print "$sql\n";
    if(!mysql_query($sql))
      exit("can not update order trwansiocion facrt after invoice 1223");

    //     print_r($data);
    //print "$sql\n";

  }


  function create_invoice_simple($invoice_data,$transacions_data){
    $this->data['Invoice Date']=$invoice_data['Invoice Date'];
    $this->data['Invoice Public ID']=$invoice_data['Invoice Public ID'];
    $this->data['Invoice File As']=$invoice_data['Invoice File As'];
    $this->data['Invoice Main Store Key']=$this->data['Order Main Store Key'];
    $this->data['Invoice Main Store Code']=$this->data['Order Main Store Code'];
    $this->data['Invoice Main Store Type']=$this->data['Order Main Store Type'];
    $this->data['Invoice Multiple Stores']=$this->data['Order Multiple Stores'];
    $this->data['Invoice Customer Key']=$this->data['Order Customer Key'];
    $this->data['Invoice Customer Name']=$this->data['Order Customer Name'];
    $this->data['Invoice XHTML Ship Tos']=$this->data['Order XHTML Ship Tos'];
    $this->data['Invoice Multiple Ship Tos']=$this->data['Order Multiple Ship Tos'];
    $this->data['Invoice Gross Shipping Amount']=$invoice_data['Invoice Gross Shipping Amount'];
    $this->data['Invoice Gross Charges Amount']=$invoice_data['Invoice Gross Charges Amount'];
    $this->data['Invoice Metadata']=$this->data['Order Original Metadata'];

    $this->create_invoice_header();
    
    $line_number=1;
    $amount=0;
    $discounts=0;
    foreach($transacions_data as $data){
      $sql=sprintf("update  `Order Transaction Fact`  set `Current Payment State`=%s,`Current Dispatching State`=%s,`Invoice Date`=%s,`Order Last Updated Date`=%s, `Invoice Public ID`=%s,`Invoice Line`=%d,`Current Payment State`=%s ,`Invoice Quantity`=%s ,`Ship To Key`=%s ,`Invoice Transaction Gross Amount`=%.2f,`Invoice Transaction Total Discount Amount`=%.2f ,`Consolidated`='Yes' where `Order Key`=%d and  `Order Line`=%d"
		   ,prepare_mysql('Paid')
		   ,prepare_mysql('Received by customer')
		   ,prepare_mysql($invoice_data['Invoice Date'])
		   ,prepare_mysql($invoice_data['Invoice Date'])
		   ,prepare_mysql($this->data['Invoice Public ID'])
		   ,$line_number
		   ,prepare_mysql($data['current payment state'])
		   ,prepare_mysql($data['invoice qty'])
		   ,prepare_mysql($this->data['Order Main Ship To Key'])
		   ,$data['gross amount']
		   ,$data['discount amount']
		   ,$this->data['Order Key']
		    ,$line_number
		   );
      $amount+=$data['gross amount'];
      $discounts+=$data['discount amount'];
      

      $line_number++;
      if(!mysql_query($sql))
	exit("can not update order trwansiocion facrt after invoice");
      
    }

    $sql=sprintf("update `Invoice Dimension` set `Invoice Gross Amount`=%.2f ,`Invoice Discount Amount`=%.2f where `Invoice Key`=%d",$amount,$discounts,$this->data['Invoice Key']);
    if(!mysql_query($sql))
      exit("can not update invoice dimension after inv\n");

    
    $invoice_txt="Invoice";

    $sql=sprintf("update `Order Dimension` set `Order Current Dispatch State`='%s' ,`Order Current Payment State`='%s',`Order Current XHTML State`='%s <a href=invoice.php?id=%d>%s</a>' ,`Order XHTML Invoices`='%s <a href=invoice.php?id=%d>%s</a>'   where `Order Key`=%d"
		 ,'Dispached'
		 ,'Paid'
		 ,$invoice_txt
		 ,$this->data['Invoice Key']
		 ,addslashes($this->data['Invoice Public ID'])
		 ,$invoice_txt
		 ,$this->data['Invoice Key']
		 ,addslashes($this->data['Invoice Public ID'])
		 ,$this->data['Order Key']
		 
		 
);
    if(!mysql_query($sql))
      exit("can not update order dimension after inv\n");


  $invoice_txt="Invoice";

    $sql=sprintf("update `Delivery Note Dimension` set `Delivery Note XHTML Invoices`='%s <a href=invoice.php?id=%d>%s</a>'   where `Delivery Note Key`=%d"
		 ,$invoice_txt
		 ,$this->data['Invoice Key']
		 ,addslashes($this->data['Invoice Public ID'])
		 ,$this->data['Delivery Note Key']
		 
		 
);
    if(!mysql_query($sql))
      exit("$sql\n can not update dn  dimension after inv\n");






  }

 function create_dn_simple($invoice_data,$transacions_data){
    $this->data['Delivery Note Date']=$invoice_data['Delivery Note Date'];
    $this->data['Delivery Note ID']=$invoice_data['Delivery Note ID'];
    $this->data['Delivery Note File As']=$invoice_data['Delivery Note File As'];
    $this->data['Delivery Note Customer Key']=$this->data['Order Customer Key'];
    $this->data['Delivery Note Customer Name']=$this->data['Order Customer Name'];
    $this->data['Delivery Note XHTML Ship To']=$this->data['Order XHTML Ship Tos'];
    $this->data['Delivery Note Ship To Key']=$this->data['Order Main Ship To Key'];
    $this->data['Delivery Note Metadata']=$this->data['Order Original Metadata'];
    $this->create_dn_header();
    
    


    $line_number=1;
    $amount=0;
    $discounts=0;
    foreach($transacions_data as $data){

      if($data['pick_method']=='historic'){
	$cost_supplier=0;
	 $cost_manu='';
      $cost_storing='';
      $cost_hand='';
      $cost_shipping='';
      $sql=sprintf("select `Parts Per Product`,`Product Part Key`,`Part SKU` from `Product Part List` where `Product Part ID`=%d and `Part SKU`=%d",$data['pick_method_data']['product part id'],$data['pick_method_data']['part sku']);
	$result=mysql_query($sql);
	$part_sku=array();$qty=array();
	if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	  $parts_per_product=$row['Parts Per Product'];
	  $part_sku=$row['Part SKU'];

	  $sql=sprintf(" select `Supplier Product Code`,`Supplier Product Valid From`,`Supplier Product Valid To`,`Supplier Product Key`,SPD.`Supplier Product ID`,`Supplier Product Units Per Part`,`Supplier Product Cost` from  `Supplier Product Dimension`   SPD left join `Supplier Product Part List` SPPL  on (SPD.`Supplier Product ID`=SPPL.`Supplier Product ID`) where `Part SKU`=%s  and `Supplier Product Valid From`<=%s and `Supplier Product Valid To`>=%s  and `Supplier Product Key`=%s",prepare_mysql($row['Part SKU'])
		     ,prepare_mysql($this->data['Delivery Note Date'])
		     ,prepare_mysql($this->data['Delivery Note Date'])
		       ,$data['pick_method_data']['supplier product key']
);
	  
	  $result2=mysql_query($sql);

	  $num_sp=mysql_num_rows($result2);
	  if($num_sp!=1)
	   exit("$sql\n error in order class 0we49qwqeqwe history 1\n");
	  
	  $row2=mysql_fetch_array($result2, MYSQL_ASSOC);
	  $supplier_product_id=$row2['Supplier Product ID'];
	  $sp_units_per_part=$row2['Supplier Product Units Per Part'];
	  $cost=$row2['Supplier Product Cost']*$sp_units_per_part*$parts_per_product*$data['Shipped Quantity'];

	  $cost_supplier+=$cost;
	  
	  $product=new product($data['product_id']);
	  $a=sprintf('<a href="product.php?id=%d">%s</a>',$product->id,$product->data['Product Code']);
	  unset($product);
	  $note=$a.', '.$this->data['Order Current XHTML State'];
	  
	   $sql=sprintf("insert into `Inventory Transaction Fact`  (`Date`,`Part SKU`,`Location Key`,`Inventory Transaction Quantity`,`Inventory Transaction Type`,`Inventory Transaction Amount`,`Required`,`Given`,`Amount In`,`Metadata`,`Note`) values (%s,%s,%d,%s,%s,%.2f,%f,%f,%f,%s,%s) "
		       ,prepare_mysql($this->data['Delivery Note Date'])
		       ,prepare_mysql($part_sku)
			,1
		       ,prepare_mysql(-$parts_per_product*$data['Shipped Quantity'])
			,"'Sale'"
			,-$cost
			,number($data['required']*$parts_per_product)
		       ,$data['given']*$parts_per_product
		       ,$data['amount in']
		       ,prepare_mysql($this->data['Delivery Note Metadata'])
			,prepare_mysql($note)
			);
	   //  print "$sql\n";
	  if(!mysql_query($sql))
	    exit("can not create Warehouse * 888 $sql   Inventory Transaction Fact\n");
	}else
	  exit("error no sku found order php l 792\n");
	  
	

      }
// if($data['pick_method']=='historic2'){

//       $cost_supplier=0;
//       $cost_manu='';
//       $cost_storing='';
//       $cost_hand='';
//       $cost_shipping='';

//       //       print "nre --------------\n";
      
      


//       $sql=sprintf("select `Parts Per Product`,`Product Part Key`,`Part SKU` from `Product Part List` where `Product ID`=%s ",prepare_mysql($data['Product ID']));
//       $result=mysql_query($sql);
//       $part_sku=array();$qty=array();
      
//       $index=0;
//       //  print "$sql\n";
//       //     $supplier_cost=0;
//       while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
// 	$part_sku=$row['Part SKU'];
// 	$parts_per_product=$row['Parts Per Product'];
// 	//get supplier product id
	

      


// 	$sql=sprintf(" select `Supplier Product Code`,`Supplier Product Valid From`,`Supplier Product Valid To`,`Supplier Product Key`,SPD.`Supplier Product ID`,`Supplier Product Units Per Part`,`Supplier Product Cost` from  `Supplier Product Dimension`   SPD left join `Supplier Product Part List` SPPL  on (SPD.`Supplier Product ID`=SPPL.`Supplier Product ID`) where `Part SKU`=%s  and `Supplier Product Valid From`<=%s and `Supplier Product Valid To`>=%s",prepare_mysql($row['Part SKU'])
// 		     ,prepare_mysql($this->data['Delivery Note Date'])
// 		     ,prepare_mysql($this->data['Delivery Note Date'])
// );

// 	 $result2=mysql_query($sql);

// 	 $num_sp=mysql_num_rows($result2);
// 	 if($num_sp==0){
// 	   exit("$sql\n error in order class 0we49qwqeqwe\n");
// 	 }if($num_sp==1){
// 	   //print "AQYIIIII";
	   
// 	   $row2=mysql_fetch_array($result2, MYSQL_ASSOC);
// 	   $supplier_product_id=$row2['Supplier Product ID'];
// 	   $sp_units_per_part=$row2['Supplier Product Units Per Part'];
// 	   $cost=$row2['Supplier Product Cost']*$sp_units_per_part*$parts_per_product*$data['Shipped Quantity'];
// 	   $cost_supplier+=$cost;
// 	   //print_r($row2);
// 	   // print "$cost_supplier * $cost * $sp_units_per_part  $parts_per_product  \n";
// 	   //if($cost=='')
// 	   //  print_r($data);
	   
// 	   $sql=sprintf("insert into `Inventory Transaction Fact`  (`Date`,`Part SKU`,`Supplier Product ID`,`Location Key`,`Inventory Transaction Quantity`,`Inventory Transaction Type`,`Inventory Transaction Amount`,`Required`,`Given`,`Amount In`,`Metadata`) values (%s,%s,%s,1,%s,'Sale',%.2f,%f,%f,%.2f,%s) "
// 			,prepare_mysql($this->data['Delivery Note Date'])
// 			,prepare_mysql($part_sku)
// 			,prepare_mysql($supplier_product_id)
// 			,prepare_mysql(-$parts_per_product*$data['Shipped Quantity'])
// 			,-$cost
// 			,$data['required']*$parts_per_product
// 			,$data['given']*$parts_per_product
// 			,$data['amount in']
// 			,prepare_mysql($this->data['Delivery Note Metadata'])
// 		     );
// 	   //   print "$sql\n";
// 	   if(!mysql_query($sql))
// 	     exit("can not create Warehouse * 888 $sql   Inventory Transaction Fact\n");
	   

	   
// 	 }else{// More than one suplier product providing this part get at random (approx)
	   
// 	   print "$sql\n";
// 	   print "more than 2 prod to choose\n";
// 	   //   exit;

// 	   $tmp=array();
// 	   while($row2=mysql_fetch_array($result2, MYSQL_ASSOC)){
	     
	     

	     
// 	     $tmp[$row2['Supplier Product Key']]=array(
// 						      'supplier product id'=>$row2['Supplier Product ID']
// 						      ,'supplier product units per part'=>$row2['Supplier Product Units Per Part']
// 						      ,'supplier product cost'=>$row2['Supplier Product Cost']
// 						      ,'taken'=>0
// 						      );
// 	   }
	   
// 	   //print_r($tmp);
// 	   $total_sps=$sp_units_per_part*$parts_per_product*$data['Shipped Quantity'];
// 	   //print "$total_sps -------------\n";
// 	   for($i=1;$i<=floor($total_sps);$i++){
// 	     $rand_keys = array_rand($tmp,1);
// 	     $tmp[$rand_keys]['taken']++;
// 	   }
	   
// 	   if(floor($total_sps)!=$total_sps ){
// 	     $rand_keys=array_rand($tmp,1);
// 	     $tmp[$rand_keys]['taken']+=$total_sps-floor($total_sps);
// 	   }
	   
	   
	 


// 	   //print_r($tmp);
// 	   foreach($tmp as $key=>$values){
	     
// 	     if($values['taken']>0){

// 	       $cost=$values['taken']*$values['supplier product cost'];
// 	       $cost_supplier+=$cost;
// 	       $sql=sprintf("insert into `Inventory Transaction Fact`  (`Date`,`Part SKU`,`Supplier Product ID`,`Location Key`,`Inventory Transaction Quantity`,`Inventory Transaction Type`,`Inventory Transaction Amount`,`Required`,`Given`,`Amount In`,`Metadata`)
//  values (%s,%s,%s,1,%f,'Sale',%f,%f,%f,%f,%s) "
// 			    ,prepare_mysql($this->data['Delivery Note Date'])
// 			    ,prepare_mysql($part_sku)
// 			    ,prepare_mysql($values['supplier product id'])
			    
// 			    ,-$parts_per_product*$values['taken']
			    
// 			    ,-$cost
			    
// 			    ,($data['required']*$parts_per_product)*($values['taken']/$total_sps)
//  			    ,$data['given']*$parts_per_product*($values['taken']/$total_sps)
// 			    ,$data['amount in']*($values['taken']/$total_sps)
			    
// 			    ,prepare_mysql($this->data['Delivery Note Metadata'])
// 			    );
// 	       // print "$sql\n\n\n\n";
		       
// 		       if(!mysql_query($sql))
// 			 exit("can not create Warehouse * 888 $sql   Inventory Transaction Fact\n");
// 	     }
// 	   }
// 	   exit;
	   
// 	 }
	 
//       }
      
//  //      $sql=sprintf("insert into `Inventory Transaction Fact`  (`Date`,`Part SKU`,`Supplier Product ID`,`Warehouse Key`,`Warehouse Location Key`,`Inventory Transaction Quantity`,`Inventory Transaction Type`,`Inventory Transaction Amount`,`Required`,`Given`,`Amount In`,`Metadata`)
// //  values (%s,%s,%s,1,1,%s,'Sale'     ,%.2f   ,%f,%f,%.2f,    %s

// // ) "
// // 			    ,prepare_mysql($this->data['Delivery Note Date'])
// // 			    ,prepare_mysql($part_sku)
// // 			    ,prepare_mysql($values['supplier product id'])
			    
// // 			    ,prepare_mysql(-$parts_per_product*$values['taken'])
			    
// // 			    -$cost
			    
// // 			    ,$data['required']*$parts_per_product
// // 			    ,$data['given']*$parts_per_product
// // 			    //,$data['amount in']
			    
// // 			    //,prepare_mysql($this->data['Delivery Note Metadata'])
// // 			    );



//  }



      
      $sql=sprintf("update  `Order Transaction Fact` set `Estimated Weight`=%s,`Actual Shipping Date`=%s,`Order Last Updated Date`=%s, `Delivery Note ID`=%s,`Delivery Note Line`=%d,`Current Autorized to Sell Quantity`=%s ,`Delivery Note Quantity`=%s ,`Shipped Quantity`=%s ,`No Shipped Due Out of Stock`=%s ,`No Shipped Due No Authorized`=%s ,`No Shipped Due Not Found`=%s ,`No Shipped Due Other`=%s ,`Cost Supplier`=%s,`Cost Manufacure`=%s,`Cost Storing`=%s,`Cost Handing`=%s,`Cost Shipping`=%s,`Picking Advance`=100 ,`Picking Advance`=100 where `Order Key`=%d and  `Order Line`=%d"
		   ,prepare_mysql($data['Estimated Weight'])
		   ,prepare_mysql($this->data['Delivery Note Date'])
		   ,prepare_mysql($this->data['Delivery Note Date'])
		   ,prepare_mysql($this->data['Delivery Note ID'])
		   ,$line_number
		   ,$data['Current Autorized to Sell Quantity']
		   ,$data['Delivery Note Quantity']
		   ,prepare_mysql($data['Shipped Quantity'])
		   ,prepare_mysql($data['No Shipped Due Out of Stock'])
		   ,prepare_mysql($data['No Shipped Due No Authorized'])
		   ,prepare_mysql($data['No Shipped Due Not Found'])
		   ,prepare_mysql($data['No Shipped Due Other'])
		   ,prepare_mysql($cost_supplier)
		   ,prepare_mysql($cost_manu)
		   ,prepare_mysql($cost_storing)
		   ,prepare_mysql($cost_hand)
		   ,prepare_mysql($cost_shipping)


		   ,$this->data['Order Key']
		   ,$line_number
		   );
   //    if($cost_supplier==''){
// 	print "$sql\n $cost_supplier\n";
// 	print 
// 	exit;
//       }
      
      // $prod=new Product($data['product_id']);

      //      print "\n ".$prod->data['Product Code']." $cost_supplier \n\n\n\n********************\n\n";

//       if($prod->data['Product Code']=='Joie-01')
// 	exit;
      $line_number++;
      if(!mysql_query($sql))
	exit("$sql\n can not update order transacrion aferter dn 313123");
      

    }
    $dn_txt="Delivery Note";
    $sql=sprintf("update `Order Dimension` set `Order Current Dispatch State`='%s' ,`Order Current XHTML State`='%s <a href=dn.php?id=%d>%s</a>'    where `Order Key`=%d"
		 ,'Ready to Pick'
		 ,'Paid'
		 ,$dn_txt
		 ,$this->data['Delivery Note Key']
		 ,addslashes($this->data['Delivery Note ID'])
		 ,$this->data['Order Key']
		 
		 
		 );
    if(!mysql_query($sql))
      exit("can not update order dimension after dn\n");
    $order_txt='Order';
    $orders=sprintf('%s <a href="order.php?id=%d">%s</a>',$order_txt,$this->id,$this->data['Order Public ID']);
    $sql=sprintf("update `Delivery Note Dimension` set `Delivery Note XHTML Orders`=%s     where `Delivery Note Key`=%d"
		  ,prepare_mysql($orders)
		 ,$this->data['Delivery Note Key']
		 );
    if(!mysql_query($sql))
      exit("$sql\n can not update order dimension after dn\n");


 }


  function get_discounts($data,$customer_id,$date){
     
    $family=array();
     foreach($data as $item){
       $nodeal[$item['product_id']]=_('No deal Available|');
       if(!isset($family[$item['family_id']]))
	 $family[$item['family_id']]=1;
       else
	 $family[$item['family_id']]++;
     }


     //print_r($data);
     //exit;
     foreach($data as $item){
       $sql=sprintf("select * from `Deal Dimension` where `Deal Allowance Type`='Percentage Off' and  `Deal Allowance Target`='Product' and `Deal Allowance Target Key`=%d and %s BETWEEN `Deal Begin Date` and  `Deal Expiration Date` ",$item['product_id'],prepare_mysql($date));

       $result =& $this->db->query($sql);
       while($row=$result->fetchRow()){

	
	 
	 $metadata=split(',',$row['deal allowance metadata']);
	 if($row['deal allowance type']=='Percentage Off'){
	   print "percentage off ";
	 if(preg_match('/Quantity Ordered$/i',$row['deal terms type'])){//Depending on the quantity ordered
	   // Family trigger -------------------------------------------------


	   if($row['deal trigger']=='Family' and $row['deal trigger key']==$item['family_id']){
	     print $family[$item['family_id']].'  '.$metadata[0]." family target\n"  ;
	     if($family[$item['family_id']]>=$metadata[0]){
	       $deal[$item['product_id']][]=array(
						  'description'=>$row['deal description'],
						  'awollance'=>$row['deal allowance type'],
						  'discount_amount'=>$metadata[1]*$item['amount'],
						  'target'=>$row['deal allowance target'],
						  'trigger'=>$row['deal trigger'],
						  'terms'=>$row['deal terms type'],
						  'add'=>0,
						  'use'=>1
						);
	     }else
	       $nodeal[$item['product_id']].='; '._('Not enought products ordered.')." ".$family[$item['family_id']]."/".$metadata[0];
	   }//_______________________________________________________________|
	   // Product selft trigger -------------------------------------------------
	   elseif($row['deal trigger']=='Product' and $row['deal trigger key']==$item['product_id']){
	     if($item['qty']>=$metadata[0]){
	       $deal[$item['product_id']][]=array(
						   'description'=>$row['deal description'],
						  'awollance'=>$row['deal allowance type'],
						  'discount_amount'=>$metadata[1]*$item['amount'],
						  'target'=>$row['deal allowance target'],
						  'trigger'=>$row['deal trigger'],
						  'terms'=>$row['deal terms type'],
						  'add'=>0,
						  'use'=>1
						);
	     }
	   }//________________________________________________________________|
	   // Other Product  trigger -------------------------------------------------
	   elseif($row['deal trigger']=='Product' and $row['deal trigger key']!=$item['product_id']){
	     
	     if(isset($data[$row['deal trigger key']]))
	       $qty=$data[$row['deal trigger key']]['qty'];
	     else
	       $qty=0;


	     if($qty>=$metadata[0]){
	       $deal[$item['product_id']][]=array(
						   'description'=>$row['deal description'],
						  'awollance'=>$row['deal allowance type'],
						  'discount_amount'=>$metadata[1]*$item['amount'],
						  'target'=>$row['deal allowance target'],
						  'trigger'=>$row['deal trigger'],
						  'terms'=>$row['deal terms type'],
						  'add'=>0,
						  'use'=>1
						  );
	     }
	   }//________________________________________________________________|
	   

	 }//end Depending quantity ordered
	 if(preg_match('/Order Interval$/i',$row['deal terms type'])){//Depending on the order interval

	   //get order interval;
	   $customer=new Customer($customer_id);
	   if($customer->get('order within',$metadata[0])){
	      $deal[$item['product_id']][]=array(
					       'description'=>$row['deal description'],
						'discount_amount'=>$metadata[1]*$item['amount']
						);
	   }else{
	     if($customer->get('customer orders')==0)
	       $nodeal[$item['product_id']].='; '._("No prevous orders");
	     else
	       $nodeal[$item['product_id']].='; '._("Last order not with in").' '.$metadata[0];
	   }


	 }//end Depending ordwer interval;

	 }//end Percentage Off
	 else if($row['deal allowance type']=='Get Free'){
	   
	   if($row['deal trigger']=='Product' and $row['deal trigger key']!=$item['product_id']){
	     $valid_orders=floor($item['qty']/$metadata[0]);
	     $free_qty=$valid_orders*$metadata[1];
	     $deal[$item['product_id']][]=array(
					      'target'=>$row['deal allowance target type'],
					      'trigger'=>$row['deal trigger'],
					      'terms'=>$row['deal terms type'],
					      'add'=>$free_qty,
					      'discount_amount'=>$free_qty*$item['case_price']
					      );
	   }

	 }//end Get Free

       }

     }
     
     foreach($nodeal as $key=>$value){
       if(preg_match('/\;/',$value))
	 $nodeal[$key]=_trim(preg_replace('/.*\|\;/','',$value));
       else
	 $nodeal[$key]=_trim(preg_replace('/\|/','',$value));
     }

     //print_r($deal);
  
     //strip duplicate deals perdentage off deals
     foreach($deal as $key=>$value){
       if($value['allowance']=='Percentage Off'){
	 if($data[$key]['discount']<$value['discount_amount'])
	   $data[$key]['discount']=$value['discount_amount'];

       }

     }
     foreach($deal as $key=>$value){
       if($value['allowance']=='Get Free'){
	 if($data[$key]['get_free']<$value['add'])
	   $data[$key]['get_free']=$value['add'];

       }

     }

     // print_r($data);


   //   print_r($nodeal);
     if(count($deal)>0)
       exit;

//       $sql=sprintf("select * from `Deal Dimension` where `Allowance Type`='Percentage Off' and  `Triger`='Product' and `Trigger Key`=%d ",$item['product_id']);
//       $result =& $this->db->query($sql);
//       while($row=$result->fetchRow()){
// 	$deal=new Deal($row['deal key']);
	
// 	$discount_function = create_function("$data,$customer_id,$date", $row['deal metadata']);
// 	$discount[$item['product_id']][$row['deal key']]['discount']=$discount_function($data,$customer,$date);
// 	$discount[$item['product_id']][$row['deal key']]['deal key']=$row['deal key'];
//       }
      
//     }
    return $data;
  }

  function create_order_header(){

    //calculate the order total
     $this->data['Order Gross Amount']=0;
     $this->data['Order Discount Amount']=0;
//     $sql="select sum(`Order Transaction Gross Amount`) as gross,sum(`Order Transaction Total Discount Amount`) from `Order Transaction Fact` where "


    $sql=sprintf("insert into `Order Dimension` (`Order File As`,`Order Date`,`Order Last Updated Date`,`Order Public ID`,`Order Main Store Key`,`Order Main Store Code`,`Order Main Store Type`,`Order Customer Key`,`Order Customer Name`,`Order Current Dispatch State`,`Order Current Payment State`,`Order Current XHTML State`,`Order Customer Message`,`Order Original Data MIME Type`,`Order Original Data`,`Order XHTML Ship Tos`,`Order Gross Amount`,`Order Discount Amount`) values (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%.2f,%.2f)"
		 ,prepare_mysql($this->data['Order File As'])
		 ,prepare_mysql($this->data['Order Date'])
		 ,prepare_mysql($this->data['Order Date'])
		 ,prepare_mysql($this->data['Order Public ID'])
		 ,prepare_mysql($this->data['Order Main Store Key'])
		 ,prepare_mysql($this->data['Order Main Store Code'])
		 ,prepare_mysql($this->data['Order Main Store Type'])
		 ,prepare_mysql($this->data['Order Customer Key'])
		 ,prepare_mysql($this->data['Order Customer Name'])
		 ,prepare_mysql($this->data['Order Current Dispatch State'])
		 ,prepare_mysql($this->data['Order Current Payment State'])
		 ,prepare_mysql($this->data['Order Current XHTML State'])
		 ,prepare_mysql($this->data['Order Customer Message'])
		 ,prepare_mysql($this->data['Order Original Data MIME Type'])
		 ,prepare_mysql($this->data['Order Original Data'])
		 ,prepare_mysql($this->data['Order XHTML Ship Tos'])
		 
		 ,$this->data['Order Gross Amount']
		 ,$this->data['Order Discount Amount']
		
		 );

     If(mysql_query($sql)){
       $this->id = mysql_insert_id();
       $this->data['Order Key']=$this->id ;
     }else{
       print "Error coan not create order header";exit;
     }

  }
    
  function create_invoice_header(){

    //calculate the order total
     $this->data['Invoice Gross Amount']=0;
     $this->data['Invoice Discount Amount']=0;
     $this->data['Invoice Total Tax Amount']=0;
     
  //    $this->data['Invoice Date']=
//        $this->data['Invoice File As']
//        $this->data['Invoice Main Store Key']
//        $this->data['Invoice Main Store Code']
//        $this->data['Invoice Main Store Type']
//        $this->data['Invoice Multiple Stores']
//        $this->data['Invoice Customer Key']
//        $this->data['Invoice Customer Name']
//        $this->data['Invoice XHTML Ship Tos']
//        $this->data['Invoice Multiple Ship Tos']
//     $sql="select sum(`Order Transaction Gross Amount`) as gross,sum(`Order Transaction Total Discount Amount`) from `Order Transaction Fact` where "


    $sql=sprintf("insert into `Invoice Dimension` (`Invoice Date`,`Invoice Public ID`,`Invoice File As`,`Invoice Main Store Key`,`Invoice Main Store Code`,`Invoice Main Store Type`,`Invoice Multiple Stores`,`Invoice Customer Key`,`Invoice Customer Name`,`Invoice XHTML Ship Tos`,`Invoice Multiple Ship Tos`,`Invoice Gross Amount`,`Invoice Discount Amount`,`Invoice Gross Shipping Amount`,`Invoice Gross Charges Amount`,`Invoice Metadata`) values (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%d,%.2f,%.2f,%.2f,%.2f,%s)"
		 ,prepare_mysql($this->data['Invoice Date'])
		 ,prepare_mysql($this->data['Invoice Public ID'])
		 ,prepare_mysql($this->data['Invoice File As'])
		 ,prepare_mysql($this->data['Invoice Main Store Key'])
		 ,prepare_mysql($this->data['Invoice Main Store Code'])
		 ,prepare_mysql($this->data['Invoice Main Store Type'])
		 ,prepare_mysql($this->data['Invoice Multiple Stores'])
		 ,prepare_mysql($this->data['Invoice Customer Key'])
		 ,prepare_mysql($this->data['Invoice Customer Name'])
		 ,prepare_mysql($this->data['Invoice XHTML Ship Tos'])
		 ,$this->data['Invoice Multiple Ship Tos']
		 ,$this->data['Invoice Gross Amount']
		 ,$this->data['Invoice Discount Amount']
		 ,$this->data['Invoice Gross Shipping Amount']
		 ,$this->data['Invoice Gross Charges Amount']
		 ,prepare_mysql($this->data['Invoice Metadata'])
		 );
    

    if(mysql_query($sql)){
       $this->id = mysql_insert_id();
       $this->data['Invoice Key']=$this->id ;
    }else{
      
      print "Error can not create order header";exit;
    }

  }


 function create_dn_header(){



   $sql=sprintf("insert into `Delivery Note Dimension` (`Delivery Note Date`,`Delivery Note ID`,`Delivery Note File As`,`Delivery Note Customer Key`,`Delivery Note Customer Name`,`Delivery Note XHTML Ship To`,`Delivery Note Ship To Key`,`Delivery Note Metadata`) values (%s,%s,%s,%s,%s,%s,%s,%s)"
		,prepare_mysql($this->data['Delivery Note Date'])
		,prepare_mysql($this->data['Delivery Note ID'])
		 ,prepare_mysql($this->data['Delivery Note File As'])
		,prepare_mysql($this->data['Delivery Note Customer Key'])
		,prepare_mysql($this->data['Delivery Note Customer Name'])
		,prepare_mysql($this->data['Delivery Note XHTML Ship To'])
		 ,prepare_mysql($this->data['Delivery Note Ship To Key'])
		,prepare_mysql($this->data['Delivery Note Metadata'])
		 );
   
  
    if(mysql_query($sql)){
      $this->id = mysql_insert_id();
      $this->data['Delivery Note Key']=$this->id ;
    }else{
       print "Error can not create dn header";exit;
    }
    
 }
 
 
 



  function get_data($key,$id){
    if($key=='id'){
      $sql=sprintf("select * from `Order Dimension` where `Order Key`=%d",$id);
      $result=mysql_query($sql);
      if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
	$this->id=$this->data['Order Key'];
      }


    }
  }


  function get($key=''){

    if(array_key_exists($key,$this->data))
      return $this->data[$key];
    
    

    switch($key){
    case('estimated_weight'):
      if($this->tipo=='order'){
	$w=0;
	$sql=sprintf("select sum(dispached*units*weight)as w from transaction left join product on (product.id=product_id) where order_id=%d ",$this->id);
	$result =& $this->db->query($sql);
	if($row=$result->fetchRow()){
	  $w=$row['w'];
	}
	return $w;
	 
      }
       
      break;
    case('pick_factor'):
      if($this->tipo=='order'){
	$factor=10;
	$sql=sprintf("select count(distinct group_id) as families,count(distinct product_id) as products from transaction left join product on (product.id=product_id) where order_id=%d ",$this->id);
	$result =& $this->db->query($sql);
	if($row=$result->fetchRow()){
	  $factor=10*$row['families']+2*($row['products']-$row['families']);
	}
	return $this->get('estimated_weight')/2+$factor;
	  
      }
       
      break;
    case('pack_factor'):
      if($this->tipo=='order'){
	$factor=10;
	$sql=sprintf("select sum(dispached) as dispached ,count(distinct product_id) as products from transaction left join product on (product.id=product_id) where order_id=%d ",$this->id);
	$result =& $this->db->query($sql);
	if($row=$result->fetchRow()){
	  if($row['products']==0)
	    $factor=0;
	  else
	    $factor=5*$row['products']+($row['dispached']/$row['products']);
	}
	return $this->get('estimated_weight')/2+$factor;
	 
      }
    }
    $_key=ucwords($key);
    if(array_key_exists($_key,$this->data))
      return $this->data[$_key];

    print_r($this->data);

    print "Error $key not found in get from Order\n";
    exit;
    return false;

    return false;
  }


  function load($key=''){
    switch($key){
    

    case('supplier'):
      $this->supplier=new supplier($this->data['supplier_id']);
      break;
    case('items'):
      switch($this->tipo){
      case('po'):
	$sql=sprintf("select * from porden_item where porden_id=%d",$this->id);
	$result =& $this->db->query($sql);
	$items=0;
	$expected_goods=0;
	$goods=0;
	while($row=$result->fetchRow()){
	  $items++;
	  $expected_goods+=($row['expected_price']);
	  $goods+=($row['price']*$row['qty']);
	}
	$this->data['items']=$items;
	$this->data['goods']=$expected_goods;
	$this->data['total']=$this->data['goods']+$this->data['vat']+$this->data['shipping']+$this->data['charges']+$this->data['diff'];
	$this->data['money']['goods']=money($this->data['goods']);
	$this->data['money']['total']=money($this->data['total']);


	

      }
      break;
    }
      

  }
  
  function get_date($key='',$tipo='dt'){
    if(isset($this->dates['ts_'.$key]) and is_numeric($this->dates['ts_'.$key]) ){

      switch($tipo){
      case('dt'):
      default:
	return strftime("%e %B %Y %H:%M", $porder['date_expected']);
      }
    }else
      return false;
  }

  
  //  function submit($data){
  //     if($this->data['status']<10){
  //       $this->data['tipo']=1;
  //       $this->data['status_id']=10;
  //       $datetime=prepare_mysql_datetime($data['sdate'].' '.$data['stime']);
  //       if($datetime['ok']){

  // 	$this->get_data();
  // 	//	return array('ok'=>false,'msg'=>$this->save_history('submit',array('date'=>'NOW','user_id'=>$data['user_id'])));
  // 	$this->save_history('submit',array('date'=>'NOW','user_id'=>$data['user_id']));

  // 	return array('ok'=>true);
  //       }else
  // 	return array('ok'=>false,'msg'=>_('wrong date').' '.$data['sdate'].' '.$data['stime']);
  //     }else{
  //       return array('ok'=>false,'msg'=>_('Order is already submited'));

  //     }

  //   }
  function add_item($data){

    switch($this->tipo){
    case('po'):
      
      //check if the product is related to the supplier
      $sql=sprintf("select supplier_id,product2supplier.price,units from product2supplier  left join product on (product_id=product.id) where product2supplier.id=%d",$data['product_id']);
      $res = $this->db->query($sql);  
      if ($row=$res->fetchRow()){
	if($row['supplier_id']!=$this->data['supplier_id'])
	  return array('ok'=>false,'msg'=>_('Product no related to this supplier'));
	$price=$row['price'];
	$units=$row['units'];
      }else
	return array('ok'=>false,'msg'=>_('Product not exist'));
      
      //check if already exist 
      $item_data=array('outers'=>'','est_price'=>'','id'=>$data['product_id'] );

      $sql=sprintf("select porden_item.id from porden_item  where porden_id=%d and p2s_id=%d",$this->id,$data['product_id']);

      $res = $this->db->query($sql);  
      if ($row=$res->fetchRow()){
	if($data['qty']!=0){
	  $sql=sprintf("update porden_item set expected_qty=%.3f,expected_price=%.3f where id=%d    ",$data['qty'],$price,$row['id']);
	  mysql_query($sql);
	  $item_data=array('outers'=>number($data['qty']/$units),'est_price'=>money($data['qty']*$price),'id'=>$data['product_id']  );
	}else{
	  $sql=sprintf("delete from porden_item  where id=%d    ",$row['id']);
	  //	  	return array('ok'=>false,'msg'=>$sql);
	  mysql_query($sql);
	}
      }else{
	if($data['qty']!=0){
	  $sql=sprintf("insert into porden_item (porden_id,p2s_id,expected_qty,expected_price) values (%d,%d,%.3f,%.3f)",$this->id,$data['product_id'],$data['qty'],$price*$data['qty']);
	  mysql_query($sql);
	  $item_data=array('outers'=>number($data['qty']/$units),'est_price'=>money($data['qty']*$price) ,'id'=>$data['product_id'] );
	}
	  
      }
      $this->load('items');
      $this->save('items');
      return array('ok'=>true,'item_data'=>$item_data);
    }
    

  }

  function item_checked($data){

    switch($this->tipo){
    case('po'):
      
      //check if the product is related to the supplier
      $sql=sprintf("select supplier_id,product2supplier.price,units from product2supplier  left join product on (product_id=product.id) where product2supplier.id=%d",$data['product_id']);
      $res = $this->db->query($sql);  
      if ($row=$res->fetchRow()){
	if($row['supplier_id']!=$this->data['supplier_id']){
	  // product not related
	  

	}
	$price=$row['price'];
	$units=$row['units'];
      }else
	return array('ok'=>false,'msg'=>_('Product not exist'));
      
      //check if already exist 
      $item_data=array('outers'=>'','est_price'=>'','id'=>$data['product_id'] );

      $sql=sprintf("select porden_item.id from porden_item  where porden_id=%d and p2s_id=%d",$this->id,$data['product_id']);

      $res = $this->db->query($sql);  
      if ($row=$res->fetchRow()){
	if($data['qty']!=0){
	  $sql=sprintf("update porden_item set expected_qty=%.3f,expected_price=%.3f where id=%d    ",$data['qty'],$price,$row['id']);
	  mysql_query($sql);
	  $item_data=array('outers'=>number($data['qty']/$units),'est_price'=>money($data['qty']*$price),'id'=>$data['product_id']  );
	}else{
	  $sql=sprintf("delete from porden_item  where id=%d    ",$row['id']);
	  //	  	return array('ok'=>false,'msg'=>$sql);
	  mysql_query($sql);
	}
      }else{
	if($data['qty']!=0){
	  $sql=sprintf("insert into porden_item (porden_id,p2s_id,expected_qty,expected_price) values (%d,%d,%.3f,%.3f)",$this->id,$data['product_id'],$data['qty'],$price*$data['qty']);
	  mysql_query($sql);
	  $item_data=array('outers'=>number($data['qty']/$units),'est_price'=>money($data['qty']*$price) ,'id'=>$data['product_id'] );
	}
	  
      }
      $this->load('items');
      $this->save('items');
      return array('ok'=>true,'item_data'=>$item_data);
    }
    

  }


  function set($tipo,$data){
    global $_order_status;
    switch($tipo){
    case('date_submited'):

      if($this->data['status']<10){

	$datetime=prepare_mysql_datetime($data['sdate'].' '.$data['stime']);
	if($datetime['ok']){
	  $this->data['tipo']=1;
	  $this->data['status_id']=10;
	  $this->data['status']=$_order_status[$this->data['status_id']];
	

	  $this->data['date_submited']=$datetime['ts'];
	  $this->data['dates']['submited']=strftime("%e %b %Y %H:%M",$datetime['ts']);
	  $this->save($tipo);
	  
	  $this->save_history('submit',array('date'=>'NOW','user_id'=>$data['user_id']));
	  

	  


	  return array('ok'=>true);
	}else
	  return array('ok'=>false,'msg'=>_('wrong date').' '.$data['sdate'].' '.$data['stime']);
      }else{
	return array('ok'=>false,'msg'=>_('Order is already submited'));

      }
      


      break;
    case('date_expected'):
      $datetime=prepare_mysql_datetime($data['date'].' 12:00:00','datetime');
      if($datetime['ok']){
	if($this->data['status_id']>=10 and  $this->data['status_id']<80 ){
	  
	  $old_value=$this->data['date_expected'];
	  $this->data['date_expected']=$datetime['ts'];
	  $this->data['dates']['expected']=strftime("%e %b %Y",$datetime['ts']);
	  $this->save('date_expected');

	  if(!isset($data['history']) or $data['history'])
	    $this->save_history('date_expected',array('date'=>'NOW()','user_id'=>$data['user_id'],'old_value'=>$old_value));
	  return array('ok'=>true,'date'=>$this->data['dates']['expected']);
	}else
	  return array('ok'=>false,'msg'=>_('Order not submited or already received')." ".$this->data['status_id']);
      }else
	return array('ok'=>false,'msg'=>_('Wrong date'));
      
      break;
    case('date_received'):
      $datetime=prepare_mysql_datetime($data['date']." ".$data['time'],'datetime');

      if($datetime['ok']){
	if($this->data['status']<20){
	  $this->data['date_received']=$datetime['ts'];
	  $this->data['dates']['received']=strftime("%e %B %Y %H:%M",$datetime['ts']);

	  //	  print "caca";
	  $done_by=$data['done_by'];
	  if(count($done_by)==0  or !is_array($done_by))
	    return array('ok'=>false,'msg'=>_('Error, indicate who receive the order'));
	  $this->data['received_by']=array();
	  $received_list='';
	  foreach($done_by as $id=>$value){
	    $staff=new staff($id);
	    if($staff->id){
	      $this->data['received_by'][$id]=$staff;
	      $received_list=', '.$staff->data['alias'];
	    }else
	      return array('ok'=>false,'msg'=>_('Error, staff id not found'));
	    
	    unset($staff);
	  }

	  
	  $this->data['received_by_list']=preg_replace('/^\,\s*/','',$received_list);
	  $this->data['status_id']=80;
	  $this->data['status']=$_order_status[$this->data['status_id']];
	


	  $this->save($tipo);
	  if(!isset($data['history']) or $data['history'])
	    $this->save_history($tipo,array('date'=>'NOW()','user_id'=>$data['user_id']));
	  return array('ok'=>true);
	}else
	  return array('ok'=>false,'msg'=>_('Already received'));
      }else
	return array('ok'=>false,'msg'=>_('Wrong date'));
    case('date_checked'):
      $datetime=prepare_mysql_datetime($data['date']." ".$data['time'],'datetime');
      if($datetime['ok']){
	if($this->data['status']<80 or $this->data['status']>=90 ){
	  $this->data['date_checked']=$datetime['ts'];
	  $this->data['dates']['checked']=strftime("%e %B %Y %H:%M",$datetime['ts']);
	  $this->data['status_id']=90;
	  $this->data['status']=$_order_status[$this->data['status_id']];
	  
	  
	  $done_by=$data['done_by'];

	  if(count($done_by)==0 or !is_array($done_by))
	    return array('ok'=>false,'msg'=>_('Error, indicate who checked the order'));
	  $this->data['checked_by']=array();
	  $received_list='';
	  foreach($done_by as $id=>$value){
	    $staff=new staff($id);
	    if($staff->id){
	      $this->data['checked_by'][$id]=$staff;
	      $received_list=', '.$staff->data['alias'];
	    }else
	      return array('ok'=>false,'msg'=>_('Error, staff id not found'));
	    
	    unset($staff);
	  }

	  
	  $this->data['checked_by_list']=preg_replace('/^\,\s*/','',$received_list);

	  
	  $this->save($tipo);
	  if(!isset($data['history']) or $data['history'])
	    $this->save_history($tipo,array('date'=>'NOW()','user_id'=>$data['user_id']));
	  return array('ok'=>true);
	}else
	  return array('ok'=>false,'msg'=>_('Already checked or not received yet'));
      }else
	return array('ok'=>false,'msg'=>_('Wrong date'));
      break;
    case('date_consolidated'):
      $datetime=prepare_mysql_datetime($data['date']." ".$data['time'],'datetime');
      if($this->data['status']<=90 and $this->data['status']<100 ){
	$this->save($tipo,$datetime);
	$this->get_data();
	if(!isset($data['history']) or $data['history'])
	  $this->save_history($tipo,array('date'=>'NOW()','user_id'=>$data['user_id'],'done_by'=>$data['done_by']));
	return array('ok'=>true);
      }else
	return array('ok'=>false,'msg'=>_('Error can not be consolidated'));
      break;
    case('date_cancelled'):
      $datetime=prepare_mysql_datetime($data['rdate']." ".$data['time'],'date');
      if($this->data['status']<80 ){
	$this->save($tipo,$datetime);
	$this->get_data();
	if(!isset($data['history']) or $data['history'])
	  $this->save_history($tipo,array('date'=>'NOW()','user_id'=>$data['user_id']));
	return array('ok'=>true);
      }else
	return array('ok'=>false,'msg'=>_('Error, order already received'));
      break;


    }
    return array('ok'=>false,'msg'=>_('Operation not found')." $tipo");
  }
  function save($key){
    switch($key){

    case('items'):
      if($this->tipo='po'){
	$sql=sprintf("update porden set items=%d,total=%.2f,goods=%.2f",$this->data['items'],$this->data['total'],$this->data['goods']);
	mysql_query($sql);

      }
      
      
      break;
    case('date_submited'):
      if($this->tipo='po'){
	$sql=sprintf("update porden set date_submited='%s' , tipo=%d, status_id=%d where id=%d",date("Y-m-d H:i:s",strtotime("@".$this->data['date_submited'])),$this->data['tipo'],$this->data['status_id'],$this->id);
      }
      mysql_query($sql);
      break;
    case('date_expected'):
      if($this->tipo='po'){
	$sql=sprintf("update porden set date_expected='%s' where id=%d",date("Y-m-d H:i:s",strtotime("@".$this->data['date_expected'])),$this->id);
	//	print $sql;
      }
      mysql_query($sql);
      break;  
    case('date_received'):
      if($this->tipo='po'){
	$sql=sprintf("update porden set date_received='%s',status_id=%d   where id=%d",date("Y-m-d H:i:s",strtotime("@".$this->data['date_received'])),$this->data['status_id'],$this->id);
	mysql_query($sql);
	$num_receivers=count($this->data['received_by']);
	if($num_receivers>0){
	  $share=1/$num_receivers;
	  foreach($this->data['received_by'] as $key=>$value){
	    $sql=sprintf("insert into porden_receiver (po_id,staff_id,share) values (%d,%d,%f)",$this->id,$key,$share);
	    //	    print "$sql ";
	    mysql_query($sql);
	  }
	}

      }
      // mysql_query($sql);
      break;  
    case('date_checked'):
      if($this->tipo='po'){
	$sql=sprintf("update porden set date_checked='%s' ,status_id=%d   where id=%d",date("Y-m-d H:i:s",strtotime("@".$this->data['date_checked'])),$this->data['status_id'],$this->id);
	mysql_query($sql);

	$num_checkers=count($this->data['checked_by']);
	if($num_checkers>0){
	  $share=1/$num_checkers;
	  foreach($this->data['checked_by'] as $key=>$value){
	    $sql=sprintf("insert into porden_checker (po_id,staff_id,share) values (%d,%d,%f)",$this->id,$key,$share);
	    //	    print "$sql ";
	    mysql_query($sql);
	  }
	}



      }
      
      break;  
    case('date_consolidated'):
      if($this->tipo='po'){
	$sql=sprintf("update porden set date_consolidated=%s , consolidated_by=%d ,status_id=%d   where id=%d",date("Y-m-d H:i:s",strtotime("@".$this->data['date_consolidated'])),$this->data['consolidated_by'],$this->data['status_id'],$this->id);
      }
      mysql_query($sql);
      break;  
    case('vateable'):
      $value=$this->get($key);
      $sql=sprintf("update %s set %s=%d where id=%d",$this->db_table,$key,$value,$this->id);
      //print $sql;
      mysql_query($sql);

    }
  }

  
  function save_history($key,$data){
    switch($key){
    case('date_submited'):
      if($this->tipo='po'){
	$note=_('submited')." ".$this->data['dates']['submited'];
	$sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,'PO',%d,'SDATE',NULL,'NEW',%d,NULL,'%d',%s)"
		     ,$data['date'],$this->id,$data['user_id'],$this->data['date_submited'],prepare_mysql($note)); 
      }
      mysql_query($sql);
      break;
    case('date_expected'):
      if($this->tipo='po'){
	$note=_('expected')." ".$this->data['dates']['expected'];
	$sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,'PO',%d,'EDATE',NULL,'CHG',%d,'%d','%d',%s)"
		     ,$data['date'],$this->id,$data['user_id'],$data['old_value'],$this->data['date_expected'],prepare_mysql($note)); 
      }
      mysql_query($sql);
      break;
      

    }
  }

  function update($values,$args=''){
    $res=array();

    foreach($values as $data){

      $key=$data['key'];
      $value=$data['value'];
      $res[$key]=array('ok'=>false,'msg'=>'');
      
      switch($key){
      case('vateable'):
	if($value)
	  $this->data[$key]=1;
	else
	  $this->data[$key]=0;
	break;
      default:
	$res[$key]=array('res'=>2,'new_value'=>'','desc'=>'Unkwown key');
      }
      if(preg_match('/save/',$args))
	$this->save($key);
      
    }
    return $res;
  }

  function cutomer_rankings(){
    $sql=sprintf("select `Customer Key` as id,`Customer Orders` as orders, (select count(*) from `Customer Dimension` as TC where TC.`Customer Orders`<C.`Customer Orders`) as better,(select count(DISTINCT `Customer ID` ) from `Customer Dimension`) total  from `Customer Dimension` as C order by `Customer Orders` desc ;");

    $orders=-99999;
    $position=0;
    
    $result=mysql_query($sql);
    while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      

      if($row['orders']!=$orders){
	    $position++;
	    $orders=$row['orders'];
      }
      $better_than=$row['better'];
      $total=$row['total'];
	  if($total>0)
	    $top=sprintf("%f",100*(1.0-($better_than/$total))) ;
	  else
	    $top='null';
	  $id=$row['id'];
	  $sql=sprintf("update `Customer Dimension` set `Customer Orders Top Percentage`=%s,`Customer Orders Position`=%d,`Customer Has More Orders Than`=%d where `Customer Key`=%d",$top,$position,$better_than,$id);
	  // print "$sql\n";
	  mysql_query($sql);
	}
  } 

  function compare_addresses($cdata){
    
    
    
    //print_r($cdata['address_data']);
    //print_r($cdata['shipping_data']);
    
    
    //check if the addresses are the same:
	$diff_result = array_diff($cdata['address_data'], $cdata['shipping_data']);
	
	if(count($diff_result)==0){
	  
	  $this->same_address=true;
	  $this->same_contact=true;
	  $this->same_company=true;
	  
	  $this->same_telephone=true;
	  

	}else{
	  


	  // print_r($diff_result);
	   //   exit;
	   $percentage=array('address1'=>1,'town'=>1,'country'=>1,'country_d1'=>1,'postcode'=>1);
	  $percentage_address=array();

	  foreach($diff_result as $key=>$value){
	    similar_text($cdata['shipping_data'][$key],$cdata['address_data'][$key],$p);
	    $percentage[$key]=$p/100;
	    if(preg_match('/address1|town|^country$|postcode|country_d1/i',$key))
	      $percentage_address[$key]=$p/100;
	  }
	  if(count($percentage)==0)
	    $avg_percentage=1;
	  else
	    $avg_percentage=average($percentage);
	  
	  if(count($percentage_address)==0)
	    $avg_percentage_address=1;
	  else
	    $avg_percentage_address=average($percentage_address);
	  
	  //	  print "AVG DIFF O:$avg_percentage A:$avg_percentage_address \n";
	  
	  if($cdata['shipping_data']['name']=='' or !array_key_exists('name',$diff_result) )
	    $this->same_contact=true;
	  else{
	    $_max=1000000;
	    $irand=mt_rand(0,1000000);
	    $rand=$irand/$_max;
	    if($rand<$percentage['name'] and $percentage['name']>.90 ){
	      $this->same_contact=true;
	     
	    }else
	      $this->same_contact=false;
	  }
	  if($cdata['shipping_data']['company']=='' or !array_key_exists('company',$diff_result) )
	    $this->same_company=true;
	  else{
	    $_max=1000000;
	    $irand=mt_rand(0,1000000);
	    $rand=$irand/$_max;
	    //print "xxx ".$percentage['company']."\n";
	    if($rand<$percentage['company']and $percentage['company']>.90){
	      $this->same_company=true;
	    }else
	      $this->same_company=false;
	  }
	  
	  if(array_key_exists('telephone',$diff_result) )
	    $this->same_telephone=false;
	  else
	    $this->same_telephone=true;


	  if($avg_percentage_address==1)
	    $this->same_address=true;
	  else
	    $this->same_address=false;
	  
	  //  print "C:$this->same_contact  CM:$this->same_company  T:$this->same_telephone   A:$this->same_address \n";
	  // exit;

	}



  }


}

?>