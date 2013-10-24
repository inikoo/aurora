<?php
//@author Raul Perusquia <raul@inikoo.com>
//Copyright (c) 2013 LW
include_once '../../app_files/db/dns.php';
include_once '../../class.User.php';
include_once '../../class.Account.php';
include_once '../../class.CompanyArea.php';
include_once '../../class.CompanyPosition.php';
include_once '../../class.Warehouse.php';


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


//******** Data
$admin_password='kaktus1';
$account_data=array(
	'Company Name'=>'Ancient Wisdom',
	'Company Fiscal Name'=>'Led Hero Ltd',
	'Company Tax Number'=>'60853314',
	'Company Registration Number'=>'08457701',
	'Company Main Plain Email'=>'sales@ledhero.co.uk',
	'Company Address Line 1'=>'2 Ebor Court',
	'Company Address Line 2'=>'Trinity Park',
	'Company Address Line 3'=>'',

	'Company Address Town'=>'Retford',

	'Company Address Postal Code'=>'DN22 7WF',
	'Company Address Country Name'=>'United Kingdom',
	'Company Address Country First Division'=>'',
	'Company Address Country Second Division'=>'Nottinhamshire',
	'Account Code'=>'Hero',
	'Account Name'=>'Ledhero',
	'Account Menu Label'=>'Hero',
	'Inikoo Public URL'=>'hero.inikoo.com',
	'Account Currency'=>'GBP',
	'Inikoo Version'=>'1.2.2'

);

$store_data=array(
	'Store Code'=>'Hero',
	'Store Name'=>'Ledhero',
	'Store Locale'=>'en_GB',
	'Store Home Country Code 2 Alpha'=>'GB',
	'Store Currency Code'=>'GBP',
	'Store Home Country Name'=>'United Kingdom',
	'Store Home Country Code 2 Alpha'=>'UK',
	'Store URL'=>'ledhero.co.uk',
	'Store Email'=>'sales@ledhero.co.uk',
	'Store Telephone'=>' +44 (0) 1777 703 215 ',
	'Store FAX'=>'+44 (0) 1777 703 215',
	'Store Slogan'=>'Changing bulbs, brightening lives, saving money',
	'Store Collection Address Key'=>'',
	'Store Valid From'=>'2013-10-01 09:00:00'
);

$warehouse_data=array('Warehouse Code'=>'W','Warehouse Name'=>'Randall');

//************************




$sql = sprintf("select * from `User Dimension` where `User Type`='Administrator'  ");
$result = mysql_query($sql);
if ($row=mysql_fetch_array($result)) {
	exit("administration account already there\n");
}

$sql = sprintf("select * from `Account Dimension`  ");
$result = mysql_query($sql);
if ($row=mysql_fetch_array($result)) {
	exit("account already there\n");
}

$account=new Account('create',$account_data);
$user_data=array(
	'User Handle'=>'root',
	'User Type'=>'Administrator',

);

$admin_user=new User('new',$user_data);
$admin_user->change_password(hash('sha256',$admin_password));
$admin_user->update_active(true);
$admin_user->add_group(array(1,2,3,4,5,6,7,8,910));

create_hr_structure($account);

$store=create_store($store_data);
$admin_user->add_store(array($store->id));


$warehouse=create_warehouse($warehouse_data);
$admin_user->add_warehouse(array($warehouse->id));





function create_store($store_data) {

	$data=array(
		'Tax Category Code'=>'S2',
		'Tax Category Name'=>'VAT 20%',
		'Tax Category Rate'=>0.2
	);
	$cat_tax=new TaxCategory('find',$data,'create');
	$data=array(
		'Tax Category Code'=>'EX',
		'Tax Category Name'=>'No vat',
		'Tax Category Rate'=>0
	);
	$cat_tax=new TaxCategory('find',$data,'create');


	$store_data['Store Tax Category Code']='S2';

	$store=new Store('find',$store_data,'create');

	return $store;
}

function create_warehouse($warehouse_data) {
	$warehouse=new Warehouse('find',array('Warehouse Code'=>'W','Warehouse Name'=>'Parkwood'),'create');
	$unk_supplier=new Supplier('find',array('Supplier Code'=>'UNK','Supplier Name'=>'Unknown'),'create');


	$flags=array(
		'Blue'=>_('Blue'),
		'Green'=>_('Green'),
		'Orange'=>_('Orange'),
		'Pink'=>_('Pink'),
		'Purple'=>_('Purple'),
		'Red'=>_('Red'),
		'Yellow'=>_('Yellow')
		);

	foreach ($flags as $flag=>$flag_label) {
		$sql=sprintf(" INSERT INTO `Warehouse Flag Dimension` (`Warehouse Key` ,`Warehouse Flag Color` ,`Warehouse Flag Label` ) VALUES (%s,%s,%s)",
			$warehouse->id,
			prepare_mysql($flag),
			prepare_mysql($flag_label)
		);
		mysql_query($sql);
	}

	$sql="INSERT INTO `Location Dimension` (`Location Key` ,`Location Warehouse Key` ,`Location Warehouse Area Key` ,`Location Code` ,`Location Mainly Used For` ,`Location Max Weight` ,`Location Max Volume` ,`Location Max Slots` ,`Location Distinct Parts` ,`Location Has Stock` ,`Location Stock Value`)VALUES ('1', ".$warehouse->id.", '1','Unknown', 'Picking', NULL , NULL , NULL , '0', 'Unknown', '0.00');";
	$loc= new Location(1);
	if (!$loc->id)
		mysql_query($sql);
	$sql2=
		"INSERT INTO `Location Dimension` (`Location Key` ,`Location Warehouse Key` ,`Location Warehouse Area Key` ,`Location Code` ,`Location Mainly Used For` ,`Location Max Weight` ,`Location Max Volume` ,`Location Max Slots` ,`Location Distinct Parts` ,`Location Has Stock` ,`Location Stock Value`)VALUES    ('2', ".$warehouse->id.", '1','LoadBay', 'Loading', NULL , NULL , NULL , '0', 'Unknown', '0.00');";
	$loc= new Location(2);
	if (!$loc->id)
		mysql_query($sql2);

	$wa_data=array( 'Warehouse Area Name'=>'Unknown',
		'Warehouse Area Code'=>'Unk',
		'Warehouse Key'=>$warehouse->id
	);

	$wa=new WarehouseArea('find',$wa_data,'create');
	$unk_supplier=new Supplier('find',array('Supplier Code'=>'UNK','Supplier Name'=>'Unknown'),'create');


	return $warehouse;
}


function create_hr_structure($account) {


	$company=new Company($account->data['Account Company Key']);

	$areas=array(
		array(
			'Company Key'=>$company->id,
			'Company Area Code'=>'OFC',
			'Company Area Name'=>'Office',
			'Company Area Description'=>'House of the administrative and creative Departments',
		)
		,array(
			'Company Key'=>$company->id,
			'Company Area Code'=>'WAH',
			'Company Area Name'=>'Warehouse',
			'Company Area Description'=>'House of Picking,Packing and Stock Departments',

		)

	);

	foreach ($areas as $areas_data) {
		$area=new CompanyArea('find',$areas_data,'create');
	}



	$departments=array(
		'OFC'=>array(
			array(
				'Company Department Code'=>'DIR',
				'Company Department Name'=>'Direction',
				'Company Department Description'=>'Director Office')
			,

			array(
				'Company Department Code'=>'CUS',
				'Company Department Name'=>'Customer Services',
				'Company Department Description'=>'Customer Services')

			,array(
				'Company Department Code'=>'MRK',
				'Company Department Name'=>'Marketing',
				'Company Department Description'=>'Marketing Department')
			,array(
				'Company Department Code'=>'ACC',
				'Company Department Name'=>'Accounting',
				'Company Department Description'=>'Accounting Department')
			,array(
				'Company Department Code'=>'SMA',
				'Company Department Name'=>'Store Product Management',
				'Company Department Description'=>'Department where we order stock and put it on the webpage for selling')

		),'WAH'=>array(
			array(
				'Company Department Code'=>'OHA',
				'Company Department Name'=>'Order Handing',
				'Company Department Description'=>'Picking & Packing Department')
			,array(
				'Company Department Code'=>'STK',
				'Company Department Name'=>'Stock Keeping',
				'Company Department Description'=>'Dealing with Deliveries and stock movements')

		)

	);


	foreach ($departments as $area_code=>$departments_data) {
		$area=new CompanyArea('code',$area_code);


		if ($area_code=='WAH')
			$warehouse_area_key=$area->id;
		if ($area_code=='OFC')
			$office_area_key=$area->id;


		foreach ($departments_data as $data) {
			$area->add_department($data);
		}
	}


	$positions=array(
		'MRK'=>array(
			array(
				'Company Position Code'=>'MRK.O',
				'Company Position Title'=>'Marketing',
				'Company Position Description'=>'Marketing'
			)
			,array(
				'Company Position Code'=>'WEB',
				'Company Position Title'=>'Web Designer',
				'Company Position Description'=>'Web Designer'
			)

		)
		,'DIR'=>array(
			array(
				'Company Position Code'=>'DIR',
				'Company Position Title'=>'Director',
				'Company Position Description'=>'General Director'
			)
		)

		,'ACC'=>array(
			array(
				'Company Position Code'=>'ACC',
				'Company Position Title'=>'Accounts',
				'Company Position Description'=>'General Accounts '
			)
		)





		,'STK'=>array(

			array(
				'Company Position Code'=>'WAH.SK',
				'Company Position Title'=>'Warehouse Stock Keeper',
				'Company Position Description'=>'Stock Receaving & Handing'
			),array(
				'Company Position Code'=>'OFC.SK',
				'Company Position Title'=>'Stock Controller',
				'Company Position Description'=>'Stock Control'
			)



		)
		,'SMA'=>array(

			array(
				'Company Position Code'=>'BUY',
				'Company Position Title'=>'Buyer',
				'Company Position Description'=>'Buyer'
			)



		)
		,'OHA'=>
		array(
			array(
				'Company Position Code'=>'PICK',
				'Company Position Title'=>'Picker',
				'Company Position Description'=>'Warehouse Parts Picker'
			),
			array(
				'Company Position Code'=>'PACK',
				'Company Position Title'=>'Packer',
				'Company Position Description'=>'Orders Packer'
			),

			array(
				'Company Position Code'=>'OHA.DM',
				'Company Position Title'=>'Dispatch Supervisor',
				'Company Position Description'=>'Dispatch Supervisor'
			),
			array(
				'Company Position Code'=>'OHA.M',
				'Company Position Title'=>'Warehouse Manager',
				'Company Position Description'=>'Warehouse Supervisor'
			)


		)

		,'CUS'=>array(
			array(
				'Company Position Code'=>'CUS',
				'Company Position Title'=>'Customer Service',
				'Company Position Description'=>'Customer Service'
			)
		)

	);
	$departments_keys=array();
	foreach ($positions as $department_codes=>$positions_data) {
		foreach (preg_split('/,/',$department_codes) as $key =>$department_code ) {

			$department=new CompanyDepartment('code',$department_code);
			$departments_keys[$department_code]=$department->id;
			if (!$department->id) {

				print_r($department_code);
				exit('error creating departments');
			}
			foreach ($positions_data as $data) {
				$department->add_position($data);
			}
		}

	}



}


?>
