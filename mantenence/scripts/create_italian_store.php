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
include_once('../../class.Campaign.php');
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
		  'Store Home Country Short Name'=>'IT', 
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
		'Campaign Name'=>'Premio fedeltà'
		 ,'Campaign Code'=>'IT.GR'
		,'Campaign Description'=>'Small order charge waive & discounts on seleted items if last order within 1 calendar month'
		,'Campaign Begin Date'=>''
		,'Campaign Expiration Date'=>''
		,'Campaign Deal Terms Type'=>'Order Interval'
		,'Campaign Deal Terms Description'=>'last order within 1 month'
		,'Campaign Deal Terms Lock'=>'Yes'
        ,'Store Key'=>$store_key
		);
$gold_camp=new Campaign('find create',$campaign);
//print_r($gold_camp);
//exit;

$data=array(
	    'Deal Name'=>'[Product Family Code] Premio fedeltà'
	    ,'Deal Trigger'=>'Family'
	    ,'Deal Allowance Type'=>'Percentage Off'
	    ,'Deal Allowance Description'=>'[Percentage Off] off'
	    ,'Deal Allowance Target'=>'Family'
	    ,'Deal Allowance Lock'=>'No'
	    );
$gold_camp->add_deal_schema($data);



//$data=array('Deal Allowance Target Key'=>$small_order_charge->id);
//$gold_camp->create_deal('Free [Charge Name]',$data);

$gold_reward_cam_id=$gold_camp->id;

$campaign=array(
		'Campaign Name'=>'Volumen Discount'
	 ,'Campaign Code'=>'IT.Vol'
		,'Campaign Trigger'=>'Family'
		,'Campaign Description'=>'Percentage off when order more than some quantity of products in the same family'
		,'Campaign Begin Date'=>''
		,'Campaign Expiration Date'=>''
		,'Campaign Deal Terms Type'=>'Family Quantity Ordered'
		,'Campaign Deal Terms Description'=>'order [Quantity] or more same family'
		,'Campaign Deal Terms Lock'=>'No'
		,'Store Key'=>$store_key
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
		     	 ,'Campaign Code'=>'IT.FShip'
				   ,'Campaign Description'=>'Free shipping to selected destinations when order more than some amount'
				   ,'Campaign Begin Date'=>''
				   ,'Campaign Expiration Date'=>''
				   ,'Campaign Deal Terms Type'=>'Order Items Net Amount AND Shipping Country'
				   ,'Campaign Deal Terms Description'=>'Orders shipped to {Country Name} and Order Items Net Amount more than {Order Items Net Amount}'
				   ,'Campaign Deal Terms Lock'=>'No'
				   ,'Store Key'=>$store_key
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


$shipping_uk=new Shipping('find',array('Country Code'=>'ITA'));
$terms_description=sprintf('Orders shipped to %s with Order Items Net Amount more than %s','ITA','€495');
$data=array(
	    'Deal Allowance Target Key'=>$shipping_uk->id
	    ,'Deal Terms Description'=>$terms_description
	    );
$free_shipping_campaign->create_deal('[Country Name] Free Shipping',$data);


$campaign=array(
		'Campaign Name'=>'BOGOF'
		,'Campaign Code'=>'IT.BOGOF'
		,'Campaign Description'=>'Buy one Get one Free'
		,'Campaign Begin Date'=>''
		,'Campaign Expiration Date'=>''
		,'Campaign Deal Terms Type'=>'Product Quantity Ordered'
		,'Campaign Deal Terms Description'=>'Buy 1'
		,'Campaign Deal Terms Lock'=>'Yes'
		,'Store Key'=>$store_key
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
		,'Campaign Code'=>'IT.FOB'
		,'Campaign Trigger'=>'Order'
		,'Campaign Description'=>'When you order over €100+vat for the first time we give you over a €100 of stock. (at retail value).'
		,'Campaign Begin Date'=>''
		,'Campaign Expiration Date'=>''
		,'Campaign Deal Terms Type'=>'Order Total Net Amount AND Order Number'
		,'Campaign Deal Terms Description'=>'order over £100+tax on the first order '
		,'Campaign Deal Terms Lock'=>'Yes'
		,'Store Key'=>$store_key
		);
$camp=new Campaign('find create',$campaign);


$data=array(
	    'Deal Name'=>'First Order Bonus [Counter]'
	    ,'Deal Trigger'=>'Order'
            ,'Deal Description'=>'When you order over €100+vat for the first time we give you over a €100 of stock. (at retail value).'
	    ,'Deal Allowance Type'=>'Get Free'
	    ,'Deal Allowance Description'=>'Free Bonus Stock ([Product Code])'
	    ,'Deal Allowance Target'=>'Product'
	    ,'Deal Allowance Lock'=>'No'
	    
	    );
$camp->add_deal_schema($data);


?>