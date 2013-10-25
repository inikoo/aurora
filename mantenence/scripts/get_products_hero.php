<?php
//@author Raul Perusquia <raul@inikoo.com>
//Copyright (c) 2013 Inikoo
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Deal.php';
include_once '../../class.DealCampaign.php';

include_once '../../class.Charge.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Warehouse.php';
include_once '../../class.Node.php';
include_once '../../class.Shipping.php';
include_once '../../class.SupplierProduct.php';


error_reporting(E_ALL);
date_default_timezone_set('UTC');
include_once '../../set_locales.php';
require '../../locale.php';
$_SESSION['locale_info'] = localeconv();

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
	print "Error can not access the database\n";
	exit;
}
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');

$csv_file='hero.csv';
$handle_csv = fopen($csv_file, "r");

$date=gmdate("Y-m-d H:i:s");
$editor=array(
	'Date'=>$date,
	'Author Name'=>'',
	'Author Alias'=>'',
	'Author Type'=>'',
	'Author Key'=>0,
	'User Key'=>0,
);
$store=new Store('code','Hero');

$unk_department=new Department('code_store','ND_Hero',$store->id);



$categories=create_categories($store);



while (($_cols = fgetcsv($handle_csv))!== false) {
	//print_r($_cols);
	//continue;
	//exit;
	if ($_cols[2]!=1 )
		continue;

//if ($_cols['1']=='HEROG1-63W')
//		exit;



	$department_code=$_cols[7];
	$department_name=$_cols[8];
	$current_fam_code=$_cols[9];
	$current_fam_name=$_cols[10];
	$fam_special_char=$_cols[11];

	$code=$_cols[1];
	$price=$_cols[3];
	$special_char=$_cols[14];
	$description=$_cols[12];

	$cat_fitting_12v=strtolower($_cols[34]);
	$cat_fitting_gu10=strtolower($_cols[35]);
	$cat_fitting_e27=strtolower($_cols[36]);
	$cat_fitting_e14=strtolower($_cols[37]);
	$cat_fitting_e40=strtolower($_cols[38]);
	$cat_fitting_t8=strtolower($_cols[39]);
	$cat_fitting_mr16=strtolower($_cols[40]);
	$cat_fitting_b22=strtolower($_cols[41]);
	$cat_fitting_tubes=strtolower($_cols[42]);
	$cat_fitting_downlights=strtolower($_cols[43]);
	$cat_fitting_floodlights=strtolower($_cols[44]);

	$cat_household_bathroom=strtolower($_cols[21]);
	$cat_household_kitchen=strtolower($_cols[22]);
	$cat_household_livingroom=strtolower($_cols[23]);
	$cat_household_exterior=strtolower($_cols[24]);
	$cat_household_bedroom=strtolower($_cols[25]);
	$cat_household_dimmeable=strtolower($_cols[26]);

	$cat_commercial_warehouse=strtolower($_cols[27]);
	$cat_commercial_exterior=strtolower($_cols[28]);
	$cat_commercial_office=strtolower($_cols[29]);
	$cat_commercial_retail=strtolower($_cols[30]);
	$cat_commercial_downlights=strtolower($_cols[31]);
	$cat_commercial_floodlights=strtolower($_cols[32]);
	$cat_commercial_party=strtolower($_cols[33]);

	$cat_misc_adaptors=strtolower($_cols[45]);
	$cat_misc_transformers=strtolower($_cols[46]);
	$cat_misc_accesories=strtolower($_cols[47]);
	$cat_misc_stripleds=strtolower($_cols[48]);

	$cat_xmas=strtolower($_cols[49]);
	$cat_megaman=strtolower($_cols[50]);





	$dep_data=array(
		'Product Department Code'=>$department_code,
		'Product Department Name'=>$department_name,
		'Product Department Store Key'=>$store->id,
	);
	$department=new Department('find',$dep_data,'create');
	if (!$department->id) {
		$department=$unk_department;
	}

	$fam_data=array(
		'editor'=>$editor,
		'Product Family Code'=>$current_fam_code,
		'Product Family Name'=>$current_fam_name,
		'Product Family Main Department Key'=>$department->id,
		'Product Family Store Key'=>$store->id,
		'Product Family Special Characteristic'=>$fam_special_char
	);


	$family=new Family('find',$fam_data,'create');



	$data=array(
		'editor'=>$editor,
		'product stage'=>'Normal',
		'product sales type'=>'Public Sale',
		'product type'=>'Normal',
		'Product stage'=>'Normal',
		'product record type'=>'Normal',
		'Product Web Configuration'=>'Online Auto',
		'product store key'=>$store->id,
		'product currency'=>'GBP',
		'product locale'=>'en_GB',
		'product code'=>$code,
		'product price'=>sprintf("%.2f",$price),
		'product rrp'=>'',
		'product units per case'=>1,
		'product name'=>$description,
		'product family key'=>$family->id,
		//'product main department key'=>$department->id,
		'product special characteristic'=>$special_char,

		'product valid from'=>$editor['Date'],
		'product valid to'=>$editor['Date'],
		
	);
	$product=new Product('find',$data,'create');


	if ($cat_household_kitchen=='ok')$categories['sub_household']['Kitchen']->associate_subject($family->id);
	if ($cat_household_livingroom=='ok')$categories['sub_household']['LivingRoom']->associate_subject($family->id);
	if ($cat_household_bathroom=='ok')$categories['sub_household']['Bathroom']->associate_subject($family->id);
	if ($cat_household_bedroom=='ok')$categories['sub_household']['Bedroom']->associate_subject($family->id);
	if ($cat_household_exterior=='ok')$categories['sub_household']['Exterior']->associate_subject($family->id);
	if ($cat_household_dimmeable=='ok')$categories['sub_household']['Dimmable']->associate_subject($family->id);

	if ($cat_commercial_warehouse=='ok') $categories['sub_commercial']['Warehouse']->associate_subject($family->id);
	if ($cat_commercial_office=='ok') $categories['sub_commercial']['Office']->associate_subject($family->id);
	if ($cat_commercial_exterior=='ok') $categories['sub_commercial']['Com.Exterior']->associate_subject($family->id);
	if ($cat_commercial_retail=='ok') $categories['sub_commercial']['Retail']->associate_subject($family->id);
	if ($cat_commercial_downlights=='ok') $categories['sub_commercial']['Com.Downlights']->associate_subject($family->id);
	if ($cat_commercial_floodlights=='ok') $categories['sub_commercial']['Com.Floodlights']->associate_subject($family->id);
	if ($cat_commercial_party=='ok') $categories['sub_commercial']['Party']->associate_subject($family->id);

	if ($cat_fitting_gu10=='ok'){
	//print $product->code." ->  ".$family->data['Product Family Code']."   $cat_fitting_gu10 \n";

	$categories['sub_fitting']['GU10']->associate_subject($family->id);


}
	if ($cat_fitting_12v=='ok')$categories['sub_fitting']['12V']->associate_subject($family->id);
	if ($cat_fitting_e27=='ok')$categories['sub_fitting']['E27']->associate_subject($family->id);
	if ($cat_fitting_e14=='ok')$categories['sub_fitting']['E14']->associate_subject($family->id);
	if ($cat_fitting_mr16=='ok')$categories['sub_fitting']['MR16']->associate_subject($family->id);
	if ($cat_fitting_b22=='ok')$categories['sub_fitting']['B22']->associate_subject($family->id);
	if ($cat_fitting_tubes=='ok')$categories['sub_fitting']['Tubes']->associate_subject($family->id);
	if ($cat_fitting_downlights=='ok')$categories['sub_fitting']['Downlights']->associate_subject($family->id);
	if ($cat_fitting_floodlights=='ok')$categories['sub_fitting']['Floodlights']->associate_subject($family->id);
	if ($cat_fitting_e40=='ok')$categories['sub_fitting']['E40']->associate_subject($family->id);
	if ($cat_fitting_t8=='ok')$categories['sub_fitting']['T8']->associate_subject($family->id);

	if ($cat_misc_adaptors=='ok')$categories['sub_miscellaneous']['Adaptors']->associate_subject($family->id);
	if ($cat_misc_transformers=='ok')$categories['sub_miscellaneous']['Transformers']->associate_subject($family->id);
	if ($cat_misc_accesories=='ok')$categories['sub_miscellaneous']['Accessories']->associate_subject($family->id);
	if ($cat_misc_stripleds=='ok')$categories['sub_miscellaneous']['Strips']->associate_subject($family->id);

	if ($cat_xmas=='ok')$categories['sub_other']['Xmas']->associate_subject($family->id);
	if ($cat_megaman=='ok')$categories['sub_other']['Megaman']->associate_subject($family->id);


}

function create_categories($store) {
	$cat=array();
	$data=array(
		'Category Store Key'=>$store->id,
		'Category Code'=>'Fittings',
		'Category Subject'=>'Family',
		'Category Branch Type'=>'Root',
		'Category Max Deep'=>2,
		'Category Subject Multiplicity'=>'Yes'
	);
	$cat['fitting']=new Category('find create',$data);
	$data=array(
		'Category Store Key'=>$store->id,
		'Category Code'=>'Household',
		'Category Subject'=>'Family',
		'Category Branch Type'=>'Root',
		'Category Max Deep'=>2,
		'Category Subject Multiplicity'=>'Yes'
	);
	$cat['household']=new Category('find create',$data);
	$data=array(
		'Category Store Key'=>$store->id,
		'Category Code'=>'Commercial',
		'Category Subject'=>'Family',
		'Category Branch Type'=>'Root',
		'Category Max Deep'=>2,
		'Category Subject Multiplicity'=>'Yes'
	);
	$cat['commercial']=new Category('find create',$data);
	$data=array(
		'Category Store Key'=>$store->id,
		'Category Code'=>'Miscellaneous',
		'Category Subject'=>'Family',
		'Category Branch Type'=>'Root',
		'Category Max Deep'=>2,
		'Category Subject Multiplicity'=>'Yes'
	);
	$cat['miscellaneous']=new Category('find create',$data);

	$data=array(
		'Category Store Key'=>$store->id,
		'Category Code'=>'Other',
		'Category Subject'=>'Family',
		'Category Branch Type'=>'Root',
		'Category Max Deep'=>2,
		'Category Subject Multiplicity'=>'Yes'
	);
	$cat['other']=new Category('find create',$data);



	$sub_cats=array('12V','GU10','E27','E14','E40','MR16','B22','T8','Tubes','Downlights','Floodlights');
	foreach ($sub_cats as $sub_cat) {
		$data=array(
			'Category Code'=>$sub_cat,
			'Category Label'=>$sub_cat,
			'Category Show Subject User Interface'=>'No',
			'Category Show Public New Subject'=>'No'
		);
		$cat['sub_fitting'][$sub_cat]=$cat['fitting']->create_children($data);
	}

	$sub_cats=array('Kitchen'=>'Kitchen','LivingRoom'=>'Living Room','Bathroom'=>'Bathroom','Bedroom'=>'Bedroom','Exterior'=>'Exterior','Dimmable'=>'Dimmable');
	foreach ($sub_cats as $sub_cat_key=>$sub_cat_value) {
		$data=array(
			'Category Code'=>$sub_cat_key,
			'Category Label'=>$sub_cat_value,
			'Category Show Subject User Interface'=>'No',
			'Category Show Public New Subject'=>'No'
		);
		$cat['sub_household'][$sub_cat_key]=$cat['household']->create_children($data);
	}
	$sub_cats=array('Warehouse'=>'Warehouse','Office'=>'Office','Com.Exterior'=>'Exterior','Retail'=>'Retail','Exterior'=>'Exterior','Com.Downlights'=>'Downlights','Com.Floodlights'=>'Floodlights','Party'=>'Party Lights');
	foreach ($sub_cats as $sub_cat_key=>$sub_cat_value) {
		$data=array(
			'Category Code'=>$sub_cat_key,
			'Category Label'=>$sub_cat_value,
			'Category Show Subject User Interface'=>'No',
			'Category Show Public New Subject'=>'No'
		);
		$cat['sub_commercial'][$sub_cat_key]=$cat['commercial']->create_children($data);
	}
	$sub_cats=array('Adaptors'=>'Adaptors','Transformers'=>'Transformers','Accessories'=>'Accessories','Strips'=>'Strip LED Lighting');
	foreach ($sub_cats as $sub_cat_key=>$sub_cat_value) {
		$data=array(
			'Category Code'=>$sub_cat_key,
			'Category Label'=>$sub_cat_value,
			'Category Show Subject User Interface'=>'No',
			'Category Show Public New Subject'=>'No'
		);
		$cat['sub_miscellaneous'][$sub_cat_key]=$cat['miscellaneous']->create_children($data);
	}


	$sub_cats=array('Xmas'=>'Xmas','Megaman'=>'Megaman');
	foreach ($sub_cats as $sub_cat_key=>$sub_cat_value) {
		$data=array(
			'Category Code'=>$sub_cat_key,
			'Category Label'=>$sub_cat_value,
			'Category Show Subject User Interface'=>'No',
			'Category Show Public New Subject'=>'No'
		);
		$cat['sub_other'][$sub_cat_key]=$cat['other']->create_children($data);
	}

	return $cat;
}

?>
