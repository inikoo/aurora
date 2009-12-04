<?php
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Campaign.php');
include_once('../../class.Charge.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.Warehouse.php');
include_once('../../class.Node.php');
include_once('../../class.Shipping.php');
include_once('../../class.SupplierProduct.php');

error_reporting(E_ALL);
date_default_timezone_set('Europe/London');
include_once('../../set_locales.php');
require('../../locale.php');
$_SESSION['locale_info'] = localeconv();

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           
date_default_timezone_set('Europe/London');

$_department_code='';
$software='Get_Products.php';
$version='V 1.0';

$Data_Audit_ETL_Software="$software $version";

$file_name='AWorder2002-spain.xls';
$csv_file='es_tmp.csv';
exec('/usr/local/bin/xls2csv    -s cp1252   -d 8859-1   '.$file_name.' > '.$csv_file);

$handle_csv = fopen($csv_file, "r");
$column=0;
$products=false;
$count=0;

$store_key=1;
//----------------------------------OK

$nodes=new Nodes('`Category Dimension`');
$data=array('`Category Name`'=>'Uso');
$nodes->add_new(0 , $data);



$data=array('`Category Name`'=>'Material');
$nodes->add_new(0 , $data);
$data=array('`Category Name`'=>'Tema');
$nodes->add_new(0 , $data);

$data=array('`Category Name`'=>'Otro','`Category Default`'=>'Yes');
$nodes->add_new(1 , $data);
$data=array('`Category Name`'=>'Velas');
$nodes->add_new(1 , $data);
$data=array('`Category Name`'=>'Jabón');
$nodes->add_new(1 , $data);
$data=array('`Category Name`'=>'Incenso');
$nodes->add_new(1 , $data);
$data=array('`Category Name`'=>'Terapias Holisticas');
$nodes->add_new(1 , $data);
$data=array('`Category Name`'=>'Productos de baño');
$nodes->add_new(1 , $data);
$data=array('`Category Name`'=>'Decoración');
$nodes->add_new(1 , $data);

$data=array('`Category Name`'=>'Otro','`Category Default`'=>'Yes');
$nodes->add_new(2 , $data);
$data=array('`Category Name`'=>'Madera');
$nodes->add_new(2 , $data);
$data=array('`Category Name`'=>'Metal');
$nodes->add_new(2 , $data);
$data=array('`Category Name`'=>'Vidrio');
$nodes->add_new(2 , $data);
$data=array('`Category Name`'=>'Resina');
$nodes->add_new(2 , $data);
$data=array('`Category Name`'=>'Ceramica');
$nodes->add_new(2 , $data);
$data=array('`Category Name`'=>'Mineral');
$nodes->add_new(2 , $data);

$data=array('`Category Name`'=>'Ninguna','`Category Default`'=>'Yes');
$nodes->add_new(3 , $data);
$data=array('`Category Name`'=>'Navidad');
$nodes->add_new(3 , $data);
$data=array('`Category Name`'=>'Halloween');
$nodes->add_new(3 , $data);
$data=array('`Category Name`'=>'Amor');
$nodes->add_new(3 , $data);
$data=array('`Category Name`'=>'Animales');
$nodes->add_new(3 , $data);
$data=array('`Category Name`'=>'Esoterico');
$nodes->add_new(3 , $data);
$data=array('`Category Name`'=>'Fantasia');
$nodes->add_new(3 , $data);

$last_department_name='';


$store_data=array('Store Code'=>'AWR',
		  'Store Name'=>'AW Regalos',
		  'Store Locale'=>'es_ES',
		  'Store Home Country Code 2 Alpha'=>'ES',
		  'Store Currency Code'=>'EUR',
		  'Store Home Country Name'=>'Spain', 
		  'Store Home Country Short Name'=>'ES', 
		  );
$store=new Store('find',$store_data,'create');



$warehouse=new Warehouse('find',array('Warehouse Code'=>'A','Warehouse Name'=>'Málaga'),'create');;

$unk_location=new Location('find',array('Location Code'=>'UNK','Location Name'=>'Locación Desconocida'),'create');;

$unk_supplier=new Supplier('find',array('Supplier Code'=>'UNK','Supplier Name'=>'Provedor Desconocido'),'create');;

$charge_data=array(
		     'Charge Description'=>'€5.00 small order'
		      ,'Store Key'=>$store_key
		     ,'Charge Trigger'=>'Order'
		     ,'Charge Type'=>'Amount'
		     ,'Charge Name'=>'Small Order Charge'
		     ,'Charge Terms Type'=>'Order Items Gross Amount'
		     ,'Charge Terms Description'=>'when Order Items Gross Amount is less than €75.00'
		     ,'Charge Begin Date'=>''
		     ,'Charge Expiration Date'=>''
		     );
$small_order_charge=new Charge('find create',$charge_data);

$dept_data=array(
		   'Product Department Code'=>'ND',
		   'Product Department Name'=>'Products sin Departamento',
		   'Product Department Store Key'=>$store_key
		   );

$dept_no_dept=new Department('find',$dept_data,'create');
$dept_no_dept_key=$dept_no_dept->id;

$dept_data=array(
		   'Product Department Code'=>'Promo',
		   'Product Department Name'=>'Articulos Promotionales',
		   'Product Department Store Key'=>$store_key
		   );
$dept_promo=new Department('find',$dept_data,'create');

$dept_promo_key=$dept_promo->id;

$fam_data=array(
		   'Product Family Code'=>'PND_ES',
		   'Product Family Name'=>'Productos sin Familia',
		   'Product Family Main Department Key'=>$dept_no_dept_key,
		   'Product Family Store Key'=>$store_key,
		   'Product Family Special Characteristic'=>'None'
		   );

$fam_no_fam=new Family('find',$fam_data,'create');
$fam_no_fam_key=$fam_no_fam->id;

//print_r($fam_no_fam);

$fam_data=array(
		   'Product Family Code'=>'Promo_ES',
		   'Product Family Name'=>'Promotional Items',
		   'Product Family Main Department Key'=>$dept_promo_key,
		   'Product Family Store Key'=>$store_key,
		   'Product Family Special Characteristic'=>'None'
		   );



$fam_promo=new Family('find',$fam_data,'create');



$fam_no_fam_key=$fam_no_fam->id;
$fam_promo_key=$fam_promo->id;


 $campaign=array(
		     'Campaign Name'=>'Club Oro'
		     ,'Campaign Description'=>'Small order charge waive & discounts on seleted items if last order within 1 calendar month'
		     ,'Campaign Begin Date'=>''
		     ,'Campaign Expiration Date'=>''
		     ,'Campaign Deal Terms Type'=>'Order Interval'
		     ,'Campaign Deal Terms Description'=>'last order within 1 month'
		     ,'Campaign Deal Terms Lock'=>'Yes'

		     );
$gold_camp=new Campaign('find create',$campaign);


$data=array(
	    'Deal Name'=>'[Product Family Code] Club Oro'
	    ,'Deal Trigger'=>'Family'
	    ,'Deal Allowance Type'=>'Percentage Off'
	    ,'Deal Allowance Description'=>'[Percentage Off] off'
	    ,'Deal Allowance Target'=>'Family'
	    ,'Deal Allowance Lock'=>'No'
		     );
$gold_camp->add_deal_schema($data);

$data=array(
	    'Deal Name'=>'Free [Charge Name]'
	    ,'Deal Trigger'=>'Order'
	    ,'Deal Allowance Type'=>'Percentage Off'
	    ,'Deal Allowance Description'=>'Free [Charge Name]'
	    ,'Deal Allowance Target'=>'Charge'
	    ,'Deal Allowance Key'=>$small_order_charge->id
        ,'Deal Allowance Lock'=>'Yes'

		   
		     );
$gold_camp->add_deal_schema($data);

$data=array('Deal Allowance Target Key'=>$small_order_charge->id);
$gold_camp->create_deal('Free [Charge Name]',$data);

$gold_reward_cam_id=$gold_camp->id;

$campaign=array(
		     'Campaign Name'=>'Volumen Discount'
		     ,'Campaign Trigger'=>'Family'
		     ,'Campaign Description'=>'Percentage off when order more than some quantity of products in the same family'
		     ,'Campaign Begin Date'=>''
		     ,'Campaign Expiration Date'=>''
		      ,'Campaign Deal Terms Type'=>'Family Quantity Ordered'
		     ,'Campaign Deal Terms Description'=>'order [Quantity] or more same family'
		     ,'Campaign Deal Terms Lock'=>'No'
		     );
$vol_camp=new Campaign('find create',$campaign);


$data=array(
		     'Deal Name'=>'[Product Family Code] Volume Discount'
		     ,'Deal Trigger'=>'Family'
		     ,'Deal Allowance Type'=>'Percentage Off'
		     ,'Deal Allowance Description'=>'[Percentage Off] off'
		     ,'Deal Allowance Target'=>'Family'
		   	 ,'Deal Allowance Lock'=>'No'

		     );
$vol_camp->add_deal_schema($data);

$volume_cam_id=$vol_camp->id;


$free_shipping_campaign_data=array(
		     'Campaign Name'=>'Free Shipping'
		     
		     ,'Campaign Description'=>'Free shipping to selected destinations when order more than some amount'
		     ,'Campaign Begin Date'=>''
		     ,'Campaign Expiration Date'=>''
		     ,'Campaign Deal Terms Type'=>'Order Items Net Amount AND Shipping Country'
		     ,'Campaign Deal Terms Description'=>'Orders shipped to {Country Name} and Order Items Net Amount more than {Order Items Net Amount}'
		     ,'Campaign Deal Terms Lock'=>'No'
		     );
$free_shipping_campaign=new Campaign('find create',$free_shipping_campaign_data);


$data=array(
		     'Deal Name'=>'[Country Name] Free Shipping'
		     ,'Deal Trigger'=>'Order'
		     ,'Deal Allowance Type'=>'Percentage Off'
		     ,'Deal Allowance Description'=>'Free Shipping'
		     ,'Deal Allowance Target'=>'Shipping'
		     ,'Deal Allowance Lock'=>'Yes'

		     );
$free_shipping_campaign->add_deal_schema($data);

$free_shipping_campaign_id=$free_shipping_campaign->id;

$shipping_uk=new Shipping('find',array('Country Code'=>'GBR'));
$terms_description=sprintf('Orders shipped to %s with Order Items Net Amount more than %s','GBR','£175');
$data=array(
	    'Deal Allowance Target Key'=>$shipping_uk->id
	    ,'Deal Terms Description'=>$terms_description
	    );
$free_shipping_campaign->create_deal('[Country Name] Free Shipping',$data);



$campaign=array(
		     'Campaign Name'=>'BOGOF'
		     ,'Campaign Description'=>'Buy one Get one Free'
		     ,'Campaign Begin Date'=>''
		     ,'Campaign Expiration Date'=>''
		       ,'Campaign Deal Terms Type'=>'Product Quantity Ordered'
		     ,'Campaign Deal Terms Description'=>'Buy 1'
		     ,'Campaign Deal Terms Lock'=>'Yes'
		     );
$bogof_camp=new Campaign('find create',$campaign);
$data=array(
		     'Deal Name'=>'[Product Family Code] BOGOF'
		     ,'Deal Trigger'=>'Family'
		     ,'Deal Allowance Type'=>'Get Free'
		     ,'Deal Allowance Description'=>'get 1 free'
		     ,'Deal Allowance Target'=>'Product'
		    ,'Deal Allowance Lock'=>'Yes'
		     );
$bogof_camp->add_deal_schema($data);

$data=array(
	    'Deal Name'=>'[Product Code] BOGOF'
		     ,'Deal Trigger'=>'Product'
		     ,'Deal Allowance Type'=>'Get Same Free'
		     ,'Deal Allowance Description'=>'get 1 free'
		     ,'Deal Allowance Target'=>'Product'
		     ,'Deal Allowance Lock'=>'Yes'

		     );
$bogof_camp->add_deal_schema($data);


$bogof_cam_id=$bogof_camp->id;
$campaign=array(
		     'Campaign Name'=>'First Order Bonus'
		     ,'Campaign Trigger'=>'Order'
		     ,'Campaign Description'=>'When you order over £100+vat for the first time we give you over a £100 of stock. (at retail value).'
		     ,'Campaign Begin Date'=>''
		     ,'Campaign Expiration Date'=>''
		     ,'Campaign Deal Terms Type'=>'Order Total Net Amount AND Order Number'
		     ,'Campaign Deal Terms Description'=>'order over £100+tax on the first order '
		     ,'Campaign Deal Terms Lock'=>'Yes'
		     );
$camp=new Campaign('find create',$campaign);


$data=array(
	    'Deal Name'=>'First Order Bonus [Counter]'
	    ,'Deal Trigger'=>'Order'
            ,'Deal Description'=>'When you order over £100+vat for the first time we give you over a £100 of stock. (at retail value).'
	    ,'Deal Allowance Type'=>'Get Free'
	    ,'Deal Allowance Description'=>'Free Bonus Stock ([Product Code])'
	    ,'Deal Allowance Target'=>'Product'
	    ,'Deal Allowance Lock'=>'No'
	    
	    );
$camp->add_deal_schema($data);




$__cols=array();
$inicio=false;
while(($_cols = fgetcsv($handle_csv))!== false){
  if(count($_cols)<=5)
    continue;

  //print_r($_cols);
  

  $code=$_cols[5];

 
  if($code=='FO-A1' and !$inicio){
    $inicio=true;
    $x=$__cols[count($__cols)-4];
    $z=$__cols[count($__cols)-3];
    $a=$__cols[count($__cols)-2];
    $b=$__cols[count($__cols)-1];
    $c=$_cols;
    $__cols=array();
    $__cols[]=$x;
    $__cols[]=$z;
    $__cols[]=$a;
    $__cols[]=$b;
    $__cols[]=$c;


  }elseif(isset($_cols[8]) and preg_match('/Regalo de bienvenida/i',$_cols[8])){

    break;
  }
  
  $__cols[]=$_cols;
}


//print_r($__cols);
//exit;

$fam_name='Productos sin Familia';
$fam_code='PND_ES';

$new_family=true;


$department_name='ND';
$department_code='Productos sin Departamento';


$department_name='';
$current_fam_name='';
$current_fam_code='';
$fam_position=-10000;
$promotion_position=100000;
$promotion='';


foreach($__cols as $cols){
  



  // print_r($cols);
  //exit;

  $is_product=true;
  
  $code=_trim($cols[3+2]);


  $price=$cols[7+2];
  $supplier_code=_trim($cols[23]);
  $part_code=_trim($cols[22]);
  $supplier_cost=$cols[26];
  

 
  // if(preg_match('/Reed-13/i',$code)){
  // print_r($cols);
  // exit;   }
  
  $code=_trim($code);
  if($code=='' or !preg_match('/\-/',$code) or preg_match('/total/i',$price)  or  preg_match('/^(pi\-|cxd\-|fw\-04)/i',$code))
    $is_product=false;
  if(preg_match('/^(ob\-108|ob\-156|ish\-94|rds\-47)/i',$code))
    $is_product=false;
  if(preg_match('/^staf-set/i',$code) and $price=='')
    $is_product=false;
  if(preg_match('/^hook-/i',$code) and $price=='')
    $is_product=false;
  if(preg_match('/^shop-fit-/i',$code) and $price=='')
    $is_product=false;
  if(preg_match('/^pack-01a|Pack-02a/i',$code) and $price=='')
    $is_product=false;
  if(preg_match('/^(DB-IS|EO-Sticker|ECBox-01|SHOP-Fit)$/i',$code) and $price=='')
    $is_product=false;
  
  
  if(preg_match('/^credit|Freight|^frc\-|^cxd\-|^wsl$|^postage$/i',$code) )
    $is_product=false;


  
  if($is_product){
    
    //    print " |$code\n";

     if($cols[8]=='' and $price=='')
    continue;

    
    $part_list=array();
    $rules=array();
    
    $current_fam_name=$fam_name;
    $current_fam_code=$fam_code;
    if($new_family){
      //    print "New family $column $promotion_position \n";
      if($promotion!='' and  ($column-$promotion_position)<4 ){
	$current_promotion=$promotion;
      }else
	$current_promotion='';
      $new_family=false;
    }
    $deals=array();
    if(preg_match('/off\s+\d+\s+or\s+more/i',_trim($current_promotion))){
      if(preg_match('/^\d+\% off/i',$current_promotion,$match))
	$allowance=$match[0];
      if(preg_match('/off.*more/i',$current_promotion,$match))
	$terms=preg_replace('/^off\s*/i','',$match[0]);
      else
	//	print "************".$current_promotion."\n";
	$deals[]=array(
		       'Deal Name'=>'Club Oro'
		       ,'Deal Trigger'=>'Order'
		       ,'Deal Description'=>$allowance.' if last order within 1 calendar month'
		       ,'Deal Terms Type'=>'Order Interval'
		       ,'Deal Terms Description'=>'last order within 1 calendar month'
		       ,'Deal Allowance Description'=>$allowance
		       ,'Deal Allowance Type'=>'Percentage Off'
		       ,'Deal Allowance Target'=>'Product'
		       ,'Deal Allowance Target Key'=>''
		       ,'Deal Begin Date'=>''
		       ,'Deal Expiration Date'=>''
		       );
      
      $deals[]=array(
		     'Deal Name'=>''
		     ,'Deal Trigger'=>'Family'
		     
		     ,'Deal Terms Type'=>'Family Quantity Ordered'
		     ,'Deal Terms Description'=>'order '.$terms
		     ,'Deal Allowance Description'=>$allowance
		     ,'Deal Allowance Type'=>'Percentage Off'
		     ,'Deal Allowance Target'=>'Product'
		     ,'Deal Allowance Target Key'=>''
		     ,'Deal Begin Date'=>''
		     ,'Deal Expiration Date'=>''
		     );	

      
      
    }elseif(preg_match('/\d+\s*or more\s*\d+\%$/i',_trim($current_promotion))){
      // print $current_promotion." *********\n";
      preg_match('/\d+\%$/i',$current_promotion,$match);
      $allowance=$match[0].' off';
      preg_match('/\d+\s*or more/i',$current_promotion,$match);
      $terms=_trim(strtolower($match[0]));

      $deals[]=array(
		     'Deal Name'=>'Club Oro'
		     ,'Deal Trigger'=>'Order'
		     ,'Deal Description'=>$allowance.' if last order within 1 calendar month'
		     ,'Deal Terms Type'=>'Order Interval'
		     ,'Deal Terms Description'=>'last order within 1 calendar month'
		     ,'Deal Allowance Description'=>$allowance
		     ,'Deal Allowance Type'=>'Percentage Off'
		     ,'Deal Allowance Target'=>'Product'
		     ,'Deal Allowance Target Key'=>''
		     ,'Deal Begin Date'=>''
		     ,'Deal Expiration Date'=>''
		     );

      $deals[]=array(
		     'Deal Name'=>''
		     ,'Deal Trigger'=>'Family'
		     ,'Deal Description'=>$allowance.' if '.$terms.' same family'
		     ,'Deal Terms Type'=>'Family Quantity Ordered'
		     ,'Deal Terms Description'=>'order '.$terms
		     ,'Deal Allowance Description'=>$allowance
		     ,'Deal Allowance Type'=>'Percentage Off'
		     ,'Deal Allowance Target'=>'Product'
		     ,'Deal Allowance Target Key'=>''
		     ,'Deal Begin Date'=>''
		     ,'Deal Expiration Date'=>''
		       
		     );	
	

    }elseif(preg_match('/^Oferta \d+\s?x\s?\d+$/i',_trim($current_promotion))){
      // print $current_promotion." *********\n";
      preg_match('/Ofertas \d+/i',$current_promotion,$match);
      $buy=_trim(preg_replace('/[^\d]/','',$match[0]));

      preg_match('/x\s?\d+/i',$current_promotion,$match);
      $get=_trim(preg_replace('/[^\d]/','',$match[0]));

      $deals[]=array(
		     'Deal Name'=>'Oferta n x m'
		     ,'Deal Trigger'=>'Product'
		     ,'Deal Description'=>'buy '.$buy.' get '.$get.' free'
		     ,'Deal Terms Type'=>'Product Quantity Ordered'
		     ,'Deal Terms Description'=>'foreach '.$buy
		     ,'Deal Allowance Description'=>$get.' free'
		     ,'Deal Allowance Type'=>'Get Free'
		     ,'Deal Allowance Target'=>'Product'
		     ,'Deal Allowance Target Key'=>''
		     ,'Deal Begin Date'=>''
		     ,'Deal Expiration Date'=>''
		     );	


    }else
       $deals=array();
    
    $units=$cols[7];
    if($units=='' OR $units<=0)
      $units=1;

    $description=_trim( mb_convert_encoding($cols[8], "UTF-8", "ISO-8859-1,UTF-8"));


    //    if(preg_match('/wsl-535/i',$code)){
    //       print_r($cols);
    //       exit;

    //     }

    $rrp=$cols[18];
    $supplier_code=_trim($cols[23]);

    $w=$cols[29];

    
    


    if($code=='EO-ST' or $code=='MOL-ST' or  $code=='JBB-st' or $code=='LWHEAT-ST' or  $code=='JBB-St' 
       or $code=='Scrub-St' or $code=='Eye-st' or $code=='Tbm-ST' or $code=='Tbc-ST' or $code=='Tbs-ST'
       or $code=='GemD-ST' or $code=='CryC-ST' or $code=='GP-ST'  or $code=='DC-ST'
       ){
      print "Skipping $code\n";
      
    }else{

      
      if(!is_numeric($price) or $price<=0){


	continue;
	print "Price Zero  $code \n";
	$price=0;
      }


      if($code=='Tib-20')
	$supplier_cost=0.2;

      if($code=='L&P-ST'){
	$supplier_cost=36.30;
	$price=86.40;
      }

      if(!is_numeric($supplier_cost)  or $supplier_cost<=0 ){
	//   print_r($cols);
	print "$code   assumind supplier cost of 40%  \n";
	$supplier_cost=0.4*$price/$units;
      
      }



    
	if($units=='')
	  $units=1;
      
	if(is_numeric($rrp))
	  $rrp=sprintf("%.2f",$rrp*$units);
	else
	  $rrp='';
      
      
	//   $_f=preg_replace('/s$/i','',$current_fam_name);
	//       //print "$_f\n";
	//       $special_char=preg_replace('/'.str_replace('/','\/',$_f).'$/i','',$description);
	//       $special_char=preg_replace('/'.str_replace('/','\/',$current_fam_name).'$/i','',$special_char);
	$fam_special_char=$current_fam_name;
	$special_char=$description;

	if(is_numeric($w)){
	  $w=$w*$units;
	  if($w<0.001 and $w>0)
	    $_w=0.001;
	  else
	    $_w=sprintf("%.3f",$w);
	}else
	  $_w='';
      
	$department_code='';
      
	//	print "$department_name\n ";
	if($department_name=='Ancient Wisdom Home Fragrance' )
	  $department_code='Home';
	if($department_name=='Bathroom Heaven' )
	  $department_code='Bath';
	if($department_name=='Departamento de Velas' )
	  $department_code='Velas';
	if($department_name=='Exotic Incense Dept Order' )
	  $department_code='Inc';
	if(preg_match('/Departamento Mundo Asi/i',$department_name) )
	  $department_code='Asia';
	if(preg_match('/Crystal Department/i',$department_name) )
	  $department_code='Crys';
	if(preg_match('/Retail Display Stands/i',$department_name) )
	  $department_code='RDS';
	if(preg_match('/Departamento de Oportunidades/i',$department_name) )
	  $department_code='Dop';
	if(preg_match('/Departamento de Perfume/i',$department_name) )
	  $department_code='Perf';
	if(preg_match('/Stoneware/i',$department_name) )
	  $department_code='Stone';
	if(preg_match('/Relaxing Music Collection/i',$department_name) )
	  $department_code='Relax';
	if(preg_match('/Jewellery Quarter/i',$department_name) )
	  $department_code='Joyas';
	if(preg_match('/Paradise Accesories/i',$department_name) )
	  $department_code='PA';
	if(preg_match('/Departamento de Bolsas/i',$department_name) )
	  $department_code='BET';
	if(preg_match('/Ancient Wisdom Aromatherapy Dept/i',$department_name) )
	  $department_code='Aterp';
      	if(preg_match('/Woodware Dept/i',$department_name) )
	  $department_code='Wood';
	if($department_code==''){
	
	  exit("Name: $department_name\n");
	
	}


 $dep_data=array(
		       'Product Department Code'=>$department_code,
		       'Product Department Name'=>$department_name,
		       'Product Department Store Key'=>$store_key
		       );
       $department=new Department('find',$dep_data,'create');	

    
	 
	 $fam_data=array(
			 'Product Family Code'=>$current_fam_code,
			 'Product Family Name'=>$current_fam_name,
			 'Product Family Main Department Key'=>$department->id,
			 'Product Family Store Key'=>$store_key,
			 'Product Family Special Characteristic'=>$fam_special_char
			 );
	 $family=new Family('find',$fam_data,'create');		 
	 }
   
   if(!$family->id){
    print_r($family);
    exit("Error en familia");
   
   }


 foreach($deals as $deal_data){
       print_r($deal_data);
   //exit;

      $deal_data['Store Key']=$store_key;

      if(preg_match('/Family Volume/i',$deal_data['Deal Name'])){
	//$deal_data['Deal Campaign Key']=$volume_cam_id;
	//$deal_data['Deal Name']=preg_replace('/Family/',$family->data['Product Family Code'],$deal_data['Deal Name']);
	//$deal_data['Deal Description']=preg_replace('/same family/',$family->data['Product Family Name'].' outers',$deal_data['Deal Description']);
   
	$data=array(
		    'Deal Allowance Target Key'=>$family->id,
		    'Deal Trigger Key'=>$family->id,

		    'Deal Allowance Description'=>$deal_data['Deal Allowance Description'],
		    'Deal Terms Description'=>$deal_data['Deal Terms Description']
		    
		    );

	$vol_camp->create_deal('[Product Family Code] Volume Discount',$data);


      }


      if(preg_match('/Oro/i',$deal_data['Deal Name'])){
	//$deal_data['Deal Campaign Key']=$gold_reward_cam_id;
	//$deal_data['Deal Name']=$family->data['Product Family Code'].' '.$deal_data['Deal Name'];

	$data=array(
		    'Deal Trigger Key'=>$family->id,
		    'Deal Allowance Target Key'=>$family->id,
		    'Deal Allowance Description'=>$deal_data['Deal Allowance Description']
		    );

	$gold_camp->create_deal('[Product Family Code] Club Oro',$data);

      }

      if(preg_match('/bogof/i',$deal_data['Deal Name'])){
		$data=array(
			    'Deal Trigger Key'=>$family->id,
			    'Deal Allowance Target Key'=>$family->id,
			    'Deal Allowance Description'=>$deal_data['Deal Allowance Description']
		    );

	$bogof_camp->create_deal('[Product Family Code] BOGOF',$data);


      }
	 
	

    }  


 if($family->id){
     $_special_char=$special_char;
     $fam_sp=$family->data['Product Family Special Characteristic'];
     $fam_sp=preg_replace('/[^a-z^0-9^\.^\-^"^\s]/i','',$fam_sp);
    
     
       //print "->$fam_sp ,  $special_char  ";
       $special_char=_trim(preg_replace("/$fam_sp/",'',$special_char));
       $fam_sp=preg_replace('/s$/i','',$fam_sp);
       $special_char=_trim(preg_replace("/$fam_sp/",'',$special_char));
       if($special_char=='')
	 $special_char=$_special_char;
       //print " ==> $special_char  \n";
     }




	$data=array(
		    'product store key'=>1,
		    'product currency'=>'EUR',
		    'product locale'=>'es_ES',
		  
		    'product sales state'=>'For Sale',
		    'product type'=>'Normal',
		    'product record type'=>'Normal',
		    'product web state'=>'Online Auto',

		    'product code'=>$code,
		    'product price'=>sprintf("%.2f",$price),
		    'product rrp'=>$rrp,
		    'product units per case'=>$units,
		    'product name'=>$description,
		  
		    'product family key'=>$family->id,
		    'product special characteristic'=>$special_char,
		    'product family special characteristic'=>$fam_special_char,
		    'product net weight'=>$_w,
		    'product gross weight'=>$_w,
		     'product valid from'=>date('Y-m-d H:i:s'),
		  'product valid to'=>date('Y-m-d H:i:s'),
		    'deals'=>$deals
		    
		    );
	//	print_r($cols);
	//print_r($data);
	//exit;

	

       	$product=new Product('find',$data,'create');
	if($product->new){
	 $product->update_for_sale_since(date("Y-m-d H:i:s",strtotime("now +1 seconds")));



	$scode=_trim($cols[22]);
	$supplier_code=$cols[23];
       
	if(preg_match('/^SG\-|^info\-/i',$code))
	  $supplier_code='AW';
	if($supplier_code=='AW')
	  $scode=$code;

	$the_supplier_data=array(
				 'Supplier Name'=>$supplier_code,
				 'Supplier Code'=>$supplier_code,
				 );

	if($scode=='SSK-452A' and $supplier_code=='Smen')
	  $scode='SSK-452A bis';




	if($supplier_code=='' or $supplier_code=='0' or preg_match('/^costa$/i',$supplier_code)){
	  $supplier_code='Unknown';
	  $the_supplier_data=array(
				   'Supplier Name'=>'Unknown Supplier'
				   ,'Supplier Code'=>$supplier_code
				   );
	}
	$supplier=new Supplier('code',$supplier_code);
	if(!$supplier->id){
	  //print "neew: $supplier_code";
	  $supplier=new Supplier('new',$the_supplier_data);
	}
	//print "$supplier_code";



	$scode=_trim($scode);
	$scode=preg_replace('/^\"\s*/','',$scode);
	$scode=preg_replace('/\s*\"$/','',$scode);
	if(preg_match('/\d+ or more|0.10000007|8.0600048828125|0.050000038|0.150000076|0.8000006103|1.100000610|1.16666666|1.650001220|1.80000122070/i',$scode))
	  $scode='';
	if(preg_match('/^(\?|new|\d|0.25|0.5|0.8|0.8000006103|01 Glass Jewellery Box|1|0.1|0.05|1.5625|10|\d{1,2}\s?\+\s?\d{1,2}\%)$/i',$scode))
	  $scode='';
	if($scode=='same')
	  $scode=$code;
	if($scode=='' or $scode=='0')
	  $scode='?'.$code;
	  
	  
	  
	  //$scode= preg_replace('/\?/','_unk',$scode);
	  
	$sp_data=array(
		       'Supplier Key'=>$supplier->id,
		       'Supplier Product Code'=>$scode,
		       'Supplier Product Cost'=>sprintf("%.4f",$supplier_cost),
		       'Supplier Product Name'=>$description,
		       'Supplier Product Description'=>$description,
		       'Supplier Product Valid From'=>date('Y-m-d H:i:s'),
		       'Supplier Product Valid To'=>date('Y-m-d H:i:s')
		       );
	//print_r($sp_data);
	$supplier_product=new SupplierProduct('find',$sp_data,'create');
	$part_data=array(
			 'Part Most Recent'=>'Yes',
			 'Part XHTML Currently Supplied By'=>sprintf('<a href="supplier.php?id=%d">%s</a>',$supplier->id,$supplier->get('Supplier Code')),
			 'Part XHTML Currently Used In'=>sprintf('<a href="product.php?id=%d">%s</a>',$product->id,$product->get('Product Code')),
			 'Part XHTML Description'=>preg_replace('/\(.*\)\s*$/i','',$product->get('Product XHTML Short Description')),
			 'part valid from'=>date('Y-m-d H:i:s'),
			 'part valid to'=>date('Y-m-d H:i:s'),
			 'Part Gross Weight'=>$w
			 );
	$part=new Part('new',$part_data);
	//	print_r($part->data);
	

	$rules[]=array('Part Sku'=>$part->data['Part SKU'],
		       'Supplier Product Units Per Part'=>$units
		       ,'supplier product part most recent'=>'Yes'
		       ,'supplier product part valid from'=>date('Y-m-d H:i:s')
		       ,'supplier product part valid to'=>date('Y-m-d H:i:s')
		       ,'factor supplier product'=>1
		       );

	

	$supplier_product->new_part_list('',$rules);

	
	$pl_header=array();
	$part_list[]=array(
			   'Product ID'=>$product->get('Product ID'),
			   'Part SKU'=>$part->get('Part SKU'),
			   'Product Part Id'=>1,
			   'requiered'=>'Yes',
			   'Parts Per Product'=>1,
			   'Product Part Type'=>'Simple Pick'
			   );
	$product->new_part_list($pl_header,$part_list);
	$supplier_product->load('used in');
	$product->load('parts');
	$part->load('used in');
	$part->load('supplied by');
    	$product->load('cost');
      }
    
    
  }else{


    

    $new_family=true;
    
    //   print "Col $column\n";
    //  print_r($cols);

    if($department_name=='Paradise Accesories'){
      if(preg_match('/Bolsos con Parejo/',$cols[8])){
	$fam_code='PBP';
	$fam_name=_trim( mb_convert_encoding($cols[8], "UTF-8", "ISO-8859-1,UTF-8"));
      }
      if(preg_match('/Bolsos/',$cols[8])){
	$fam_code='PB';
	$fam_name=_trim( mb_convert_encoding($cols[8], "UTF-8", "ISO-8859-1,UTF-8"));
      }
       if(preg_match('/Pulseras hechas a mano Paradise/i',$cols[8])){
	$fam_code='Ppul';
	$fam_name=_trim( mb_convert_encoding($cols[8], "UTF-8", "ISO-8859-1,UTF-8"));
      }
  if(preg_match('/Originales Collares hechos a mano Paradise/i',$cols[8])){
	$fam_code='Pcol';
	$fam_name=_trim( mb_convert_encoding($cols[8], "UTF-8", "ISO-8859-1,UTF-8"));
      }
  if(preg_match('/Pendientes Paradise/i',$cols[8])){
	$fam_code='Ppen';
	$fam_name=_trim( mb_convert_encoding($cols[8], "UTF-8", "ISO-8859-1,UTF-8"));
      }


    }


    if($cols[5]!='' and $cols[8]!=''){
      $fam_code=$cols[5];
      $fam_name=_trim( mb_convert_encoding($cols[8], "UTF-8", "ISO-8859-1,UTF-8"));
      $fam_position=$column;

      
    }
    
    if(preg_match('/off\s+\d+\s+or\s+more|\s*\d+\s*or more\s*\d+|buy \d+ get \d+ free|\d+ o m.as y obtendr.s \s+\% descuanto/i',_trim($cols[8]))){
      

      $promotion=$cols[8];

      $promotion=preg_replace('/^\s*order\s*/i','',$promotion);
      $promotion=preg_replace('/discount\s*$/i','',$promotion);
      $promotion=preg_replace('/\s*off\s*$/i','',$promotion);

      $promotion=_trim($promotion);
      $promotion_position=$column;
      // print "*********** Promotion $promotion $promotion_position \n";
    }
    if($cols[5]=='' and $cols[8]==''){
      $blank_position=$column;
    }


    

    if( ($cols[8]!='' and preg_match('/Sub Total/i',$cols[13])) or preg_match('/Bathroom Heaven/',$cols[8]) or $cols[8]=='Paradise Accesories' or preg_match('/Departamento de Bolsas/',$cols[8]) ){
      
     
      $department_name=$cols[8];
      $department_position=$column;
  
      //  print_r($cols);
      // if($department_name!='Ancient Wisdom Home Fragrance')
      

    }
    
    $posible_fam_code=$cols[5];
    $posible_fam_name=$cols[8];
  }
  

  
  $column++;
}






?>