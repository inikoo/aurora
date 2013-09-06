<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2011 Inikoo
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Category.php';
include_once '../../class.Node.php';




error_reporting(E_ALL);

date_default_timezone_set('UTC');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}
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


// DONT USE THIS
exit;

$sql=sprintf("delete from `Category Dimension` where `Category Subject`='Part';");
mysql_query($sql);

$sql=sprintf("delete from `Category Bridge` where `Subject`='Part';");
mysql_query($sql);


$data=array(
			'Category Warehouse Key'=>1,
			'Category Code'=>'Family Map',
			'Category Subject'=>'Part',
			'Category Branch Type'=>'Root',
			'Category Max Deep'=>1,
			'Category Warehouse Key'=>1,
			'Category Subject Multiplicity'=>'No'
			);
$main_cat=new Category('find create',$data);
$main_cat->skip_update_sales=true;


$sql=sprintf("select * from `Part Dimension`");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$part=new Part($row['Part SKU']);
	//$part->update_used_in();
	$used_in=preg_split('/\s+/',$part->data['Part Currently Used In']);
	foreach ($used_in as $code) {
		if (preg_match('/^([a-z0-9]+)\-/',$code,$match)) {
			$fam_code=$match[1];
			$family=new Family('code_store',$fam_code,1);
			if ($family->id) {
				// print "xxx->".$family->data['Product Family Code']."\n";
				$data=array(
					'Category Parent Key'=>$main_cat->id,
					'Category Code'=>$family->data['Product Family Code'],
					'Category Label'=>$family->data['Product Family Name'],
					'Category Show Subject User Interface'=>'No',
					'Category Show Public New Subject'=>'No'
					);
				
				print $part->sku."\r";
				
				$cat=$main_cat->create_children($data);
$cat->skip_update_sales=true;


				$cat->associate_subject($part->sku);


			
 
			}

		}
	}

}
//$main_cat->update_number_of_subjects();
// $main_cat->update_children_data();\
$sql="UPDATE `Warehouse Dimension` SET `Warehouse Family Category Key` = '".$main_cat->id."' WHERE `Warehouse Dimension`.`Warehouse Key` =1;";
mysql_query($sql);

?>
