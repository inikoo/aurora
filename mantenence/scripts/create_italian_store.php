<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Page.php');
include_once('../../class.Store.php');
include_once('../../class.CompanyArea.php');
include_once('../../class.CompanyDepartment.php');
include_once('../../class.CompanyPosition.php');
include_once('../../class.TaxCategory.php');
include_once('../../class.Charge.php');
include_once('../../class.Staff.php');
include_once('../../class.Deal.php');
include_once('../../class.Shipping.php');

error_reporting(E_ALL);

date_default_timezone_set('UTC');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
    print "Error can not connect with database server\n";
    exit;
}

//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
    print "Error can not access the database\n";
    exit;
}


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';

global $myconf;




$store_data=array('Store Code'=>'IT',
		  'Store Name'=>'AW-Regali',
		  'Store Locale'=>'it_IT',
		  'Store Home Country Code 2 Alpha'=>'IT',
		  'Store Currency Code'=>'EUR',
		  'Store Home Country Name'=>'Italy', 
		  'Store Home Country Code 2 Alpha'=>'IT', 
		  'Store URL'=>'aw-regali.com',
		  'Store Email'=>'mauro@aw-regali.com',
		  'Store Telephone'=>'+39 02 40047064',
		  'Store FAX'=>'',
		  'Store Slogan'=>'Grossista articoli da regalo',
'Store Tax Category Code'=>'S1',
		 'Store Collection Address Key'=>1

		  );
$store=new Store('find',$store_data,'create');

// Germany
$store=new Store("code","IT");
$store_key=$store->id;



//exit($store_key);
$dept_data=array(
		 'Product Department Code'=>'ND_IT',
		 'Product Department Name'=>'Products Without Department',
		 'Product Department Store Key'=>$store_key
		 );

$dept_no_dept=new Department('find',$dept_data,'create');
$dept_no_dept_key=$dept_no_dept->id;

$dept_data=array(
		 'Product Department Code'=>'Promo_IT',
		 'Product Department Name'=>'Promotional Items',
		 'Product Department Store Key'=>$store_key
		 );
$dept_promo=new Department('find',$dept_data,'create');
$dept_promo_key=$dept_promo->id;

$fam_data=array(
		'Product Family Code'=>'PND_IT',
		'Product Family Name'=>'Products Without Family',
		'Product Family Main Department Key'=>$dept_no_dept_key,
		'Product Family Store Key'=>$store_key,
		'Product Family Special Characteristic'=>'None'
		);

$fam_no_fam=new Family('find',$fam_data,'create');
$fam_no_fam_key=$fam_no_fam->id;

//print_r($fam_no_fam);

$fam_data=array(
		'Product Family Code'=>'Promo_IT',
		'Product Family Name'=>'Promotional Items',
		'Product Family Main Department Key'=>$dept_promo_key,
		'Product Family Store Key'=>$store_key,
		'Product Family Special Characteristic'=>'None'
		);



$fam_promo=new Family('find',$fam_data,'create');



$fam_no_fam_key=$fam_no_fam->id;
$fam_promo_key=$fam_promo->id;

$campaign=array(
		'Deal Name'=>'Premio fedeltà'
		 ,'Deal Code'=>'IT.GR'
		,'Deal Description'=>'Small order charge waive & discounts on seleted items if last order within 1 calendar month'
		,'Deal Begin Date'=>''
		,'Deal Expiration Date'=>''
		,'Campaign Deal Component Terms Type'=>'Order Interval'
		,'Campaign Deal Component Terms Description'=>'last order within 1 month'
		,'Campaign Deal Component Terms Lock'=>'Yes'
        ,'Store Key'=>$store_key
		);
$gold_camp=new Deal('find create',$campaign);
//print_r($gold_camp);
//exit;

$data=array(
	    'Deal Component Name'=>'[Product Family Code] Premio fedeltà'
	    ,'Deal Component Trigger'=>'Family'
	    ,'Deal Component Allowance Type'=>'Percentage Off'
	    ,'Deal Component Allowance Description'=>'[Percentage Off] off'
	    ,'Deal Component Allowance Target'=>'Family'
	    ,'Deal Component Allowance Lock'=>'No'
	    );
$gold_camp->add_deal_schema($data);



//$data=array('Deal Component Allowance Target Key'=>$small_order_charge->id);
//$gold_camp->create_deal('Free [Charge Name]',$data);

$gold_reward_cam_id=$gold_camp->id;

$campaign=array(
		'Deal Name'=>'Volumen Discount'
	 ,'Deal Code'=>'IT.Vol'
		,'Campaign Trigger'=>'Family'
		,'Deal Description'=>'Percentage off when order more than some quantity of products in the same family'
		,'Deal Begin Date'=>''
		,'Deal Expiration Date'=>''
		,'Campaign Deal Component Terms Type'=>'Family Quantity Ordered'
		,'Campaign Deal Component Terms Description'=>'order [Quantity] or more same family'
		,'Campaign Deal Component Terms Lock'=>'No'
		,'Store Key'=>$store_key
		);
$vol_camp=new Deal('find create',$campaign);


$data=array(
	    'Deal Component Name'=>'[Product Family Code] Volume Discount'
	    ,'Deal Component Trigger'=>'Family'
	    ,'Deal Component Allowance Type'=>'Percentage Off'
	    ,'Deal Component Allowance Description'=>'[Percentage Off] off'
	    ,'Deal Component Allowance Target'=>'Family'
	    ,'Deal Component Allowance Lock'=>'No'

	    );
$vol_camp->add_deal_schema($data);

$volume_cam_id=$vol_camp->id;


$free_shipping_campaign_data=array(
				   'Deal Name'=>'Free Shipping'
		     	 ,'Deal Code'=>'IT.FShip'
				   ,'Deal Description'=>'Free shipping to selected destinations when order more than some amount'
				   ,'Deal Begin Date'=>''
				   ,'Deal Expiration Date'=>''
				   ,'Campaign Deal Component Terms Type'=>'Order Items Net Amount AND Shipping Country'
				   ,'Campaign Deal Component Terms Description'=>'Orders shipped to {Country Name} and Order Items Net Amount more than {Order Items Net Amount}'
				   ,'Campaign Deal Component Terms Lock'=>'No'
				   ,'Store Key'=>$store_key
				   );
$free_shipping_campaign=new Deal('find create',$free_shipping_campaign_data);


$data=array(
	    'Deal Component Name'=>'[Country Name] Free Shipping'
	    ,'Deal Component Trigger'=>'Order'
	    ,'Deal Component Allowance Type'=>'Percentage Off'
	    ,'Deal Component Allowance Description'=>'Free Shipping'
	    ,'Deal Component Allowance Target'=>'Shipping'
	    ,'Deal Component Allowance Lock'=>'Yes'

	    );
$free_shipping_campaign->add_deal_schema($data);

$free_shipping_campaign_id=$free_shipping_campaign->id;


$shipping_uk=new Shipping('find',array('Country Code'=>'ITA'));
$terms_description=sprintf('Orders shipped to %s with Order Items Net Amount more than %s','ITA','€495');
$data=array(
	    'Deal Component Allowance Target Key'=>$shipping_uk->id
	    ,'Deal Component Terms Description'=>$terms_description
	    );
$free_shipping_campaign->create_deal('[Country Name] Free Shipping',$data);


$campaign=array(
		'Deal Name'=>'BOGOF'
		,'Deal Code'=>'IT.BOGOF'
		,'Deal Description'=>'Buy one Get one Free'
		,'Deal Begin Date'=>''
		,'Deal Expiration Date'=>''
		,'Campaign Deal Component Terms Type'=>'Product Quantity Ordered'
		,'Campaign Deal Component Terms Description'=>'Buy 1'
		,'Campaign Deal Component Terms Lock'=>'Yes'
		,'Store Key'=>$store_key
		);
$bogof_camp=new Deal('find create',$campaign);
$data=array(
	    'Deal Component Name'=>'[Product Family Code] BOGOF'
	    ,'Deal Component Trigger'=>'Family'
	    ,'Deal Component Allowance Type'=>'Get Free'
	    ,'Deal Component Allowance Description'=>'get 1 free'
	    ,'Deal Component Allowance Target'=>'Product'
	    ,'Deal Component Allowance Lock'=>'Yes'
	    );
$bogof_camp->add_deal_schema($data);

$data=array(
	    'Deal Component Name'=>'[Product Code] BOGOF'
	    ,'Deal Component Trigger'=>'Product'
	    ,'Deal Component Allowance Type'=>'Get Same Free'
	    ,'Deal Component Allowance Description'=>'get 1 free'
	    ,'Deal Component Allowance Target'=>'Product'
	    ,'Deal Component Allowance Lock'=>'Yes'

	    );
$bogof_camp->add_deal_schema($data);


$bogof_cam_id=$bogof_camp->id;
$campaign=array(
		'Deal Name'=>'First Order Bonus'
		,'Deal Code'=>'IT.FOB'
		,'Campaign Trigger'=>'Order'
		,'Deal Description'=>'When you order over €100+vat for the first time we give you over a €100 of stock. (at retail value).'
		,'Deal Begin Date'=>''
		,'Deal Expiration Date'=>''
		,'Campaign Deal Component Terms Type'=>'Order Total Net Amount AND Order Number'
		,'Campaign Deal Component Terms Description'=>'order over £100+tax on the first order '
		,'Campaign Deal Component Terms Lock'=>'Yes'
		,'Store Key'=>$store_key
		);
$camp=new Deal('find create',$campaign);


$data=array(
	    'Deal Component Name'=>'First Order Bonus [Counter]'
	    ,'Deal Component Trigger'=>'Order'
            ,'Deal Description'=>'When you order over €100+vat for the first time we give you over a €100 of stock. (at retail value).'
	    ,'Deal Component Allowance Type'=>'Get Free'
	    ,'Deal Component Allowance Description'=>'Free Bonus Stock ([Product Code])'
	    ,'Deal Component Allowance Target'=>'Product'
	    ,'Deal Component Allowance Lock'=>'No'
	    
	    );
$camp->add_deal_schema($data);


?>