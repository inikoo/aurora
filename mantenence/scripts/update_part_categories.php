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


$fmap_category=new Category('subject_code','Part','FMap');

if(!$fmap_category->id){
	exit("no Fmap cat \n");
}

$sql="UPDATE `Warehouse Dimension` SET `Warehouse Family Category Key` = '".$fmap_category->id."' WHERE `Warehouse Dimension`.`Warehouse Key` =1;";
mysql_query($sql);

$sql=sprintf("select * from `Part Dimension` order by `Part SKU`");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$part=new Part($row['Part SKU']);
	//$part->update_used_in();
	//print "===============SKU ".$part->sku."\n";
	$used_in=preg_split('/\s+/',$part->data['Part Currently Used In']);
//	if(count($used_in)>1)
	//print_r($used_in);
	foreach ($used_in as $code) {
		if (preg_match('/^([a-z0-9]+)\-/',$code,$match)) {
			$fam_code=$match[1];
			if(count($used_in)>1){
			if ($fam_code=='cartsg')
				$fam_code='SG';
			if ($fam_code=='bgp')
				$fam_code='gp';
			}	
				
			//print "$code $fam_code\n";


			$family=new Family('code_store',$fam_code,1);
			if ($family->id) {

				$category=new Category('rootkey_code',$fmap_category->id,$family->data['Product Family Code']);
				if ($category->id) {
					$category->update(array('Category Label'=>$family->data['Product Family Name']));

					$category->associate_subject($part->sku);
				}else {
					$data=array(
						'Category Parent Key'=>$fmap_category->id,
						'Category Code'=>$family->data['Product Family Code'],
						'Category Label'=>$family->data['Product Family Name'],
						'Category Show Subject User Interface'=>'No',
						'Category Show Public New Subject'=>'No'
					);


					$category=$fmap_category->create_children($data);


					$category->associate_subject($part->sku);

				}




				break;
			}
		}
	}
}


/*
			$fam_code=$match[1];
			if($fam_code=='CartSG')
				$fam_code='SG';

			print "$code $fam_code\n";


			$family=new Family('code_store',$fam_code,1);
			if ($family->id) {

				print $family->data['Product Family Code']."\n";
				$category=new Category('rootkey_code',$fmap_category->id,$family->data['Product Family Code']);
				if($category->id){
					$category->update(array('Category Label'=>$family->data['Product Family Name']));

				$category->associate_subject($part->sku);
				}else{
				$data=array(
					'Category Parent Key'=>$fmap_category->id,
					'Category Code'=>$family->data['Product Family Code'],
					'Category Label'=>$family->data['Product Family Name'],
					'Category Show Subject User Interface'=>'No',
					'Category Show Public New Subject'=>'No'
					);


				$category=$fmap_category->create_children($data);


				$category->associate_subject($part->sku);

				}

				break;




			}

		}

	}

}
*/
?>
