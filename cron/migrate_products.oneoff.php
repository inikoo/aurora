<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 15 February 2016 at 10:45:44 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


$default_DB_link=@mysql_connect($dns_host, $dns_user, $dns_pwd );
if (!$default_DB_link) {
	print "Error can not connect with database server\n";
}
$db_selected=mysql_select_db($dns_db, $default_DB_link);
if (!$db_selected) {
	print "Error can not access the database\n";
	exit;
}
mysql_set_charset('utf8');
mysql_query("SET time_zone='+0:00'");



require_once 'utils/get_addressing.php';

require_once 'class.Customer.php';
require_once 'class.Store.php';
require_once 'class.Address.php';
require_once 'class.Product.php';

$editor=array(
	'Author Name'=>'',
	'Author Alias'=>'',
	'Author Type'=>'',
	'Author Key'=>'',
	'User Key'=>0,
	'Date'=>gmdate('Y-m-d H:i:s')
);

migrate_products($db, $editor);
create_categories($db,$editor);
fix_product_categories($db);


function fix_product_categories($db) {
	$sql=sprintf('select `Category Key` from `Category Dimension` where `Category Scope`="Product"');

	if ($result=$db->query($sql)) {
		foreach ($result as $row) {

			$category=new Category($row['Category Key']);

			$store=new Store($category->data['Category Store Key']);

			$sql=sprintf("insert into `Product Category Dimension` (`Product Category Key`,`Product Category Store Key`,`Product Category Currency Code`,`Product Category Valid From`) values (%d,%d,%s,%s)",
				$category->id,
				$store->id,
				prepare_mysql($store->get('Store Currency Code')),
				prepare_mysql(gmdate('Y-m-d H:i:s'))
			);
			$db->exec($sql);
		}

	}else {print_r($error_info=$db->errorInfo());exit;}

}


function create_categories($db, $editor) {

	// Delete categories if exists


	$sql=sprintf('select `Store Key` from `Store Dimension` ');

	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			$store=new Store($row['Store Key']);

			$category=new Category($store->get('Store Family Category Key'));

			$category->delete();

			$category=new Category($store->get('Store Department Category Key'));
			$category->delete();


			$store->update(array(
					'Store Family Category Key'=>'',
					'Store Department Category Key'=>'',
				), 'no_history');

		}
	}




	$sql=sprintf('select `Store Key` from `Store Dimension` ');

	if ($result=$db->query($sql)) {



		foreach ($result as $row) {
			$store=new Store($row['Store Key']);
			$category_fam_key=create_main_category($store, $editor, 'Families');
			$category_dept_key=create_main_category($store, $editor, 'Departments');




			$store->update(array(
					'Store Family Category Key'=>$category_fam_key,
					'Store Department Category Key'=>$category_dept_key,
				), 'no_history');


			$sql=sprintf('select * from `Product Department Dimension` where `Product Department Store Key`=%d and `Product Department Key`!=%d',
				$store->id,
				$store->get('Store No Products Department Key')
			);

			if ($result2=$db->query($sql)) {
				foreach ($result2 as $row2) {
					$departments=new Category($category_dept_key);
					$data=array(
						'Category Store Key'=>$store->id,
						'Category Subject'=>'Product',
						'Category Label'=>$row2['Product Department Name']
					);




					$department=create_subcategory($departments, $editor, $data, $row2['Product Department Code']);

					if (!$department) {
						$department=new Category('find', array(
								'Category Store Key'=>$store->id,
								'Category Parent Key'=>$departments->id,
								'Category Code'=>$row2['Product Department Code']

							));
					}

					$sql=sprintf('update `Product Category Dimension` set `Product Category Valid From`=%s,`Product Category Description`=%s where `Product Category Key`=%d ',
						prepare_mysql($row2['Product Department Valid From']),
						prepare_mysql($row2['Product Department Description']),
						$department->id

					);
					$db->exec($sql);


					$sql=sprintf("select * from `Image Bridge` where `Subject Type`='Department' and `Subject Key`=%d", $row2['Product Department Key']);
					if ($result3=$db->query($sql)) {
						foreach ($result3 as $row3) {
							$sql=sprintf("insert into `Image Bridge` (`Subject Type`,`Subject Key`,`Image Key`,`Is Principal`,`Image Caption`) values (%s,%d,%d,%s,%s)",
								prepare_mysql('Category'),
								$department->id,
								$row3['Image Key'],
								prepare_mysql( $row3['Is Principal']),
								prepare_mysql( $row3['Image Caption'], false)

							);
							$db->exec($sql);
						}
					}else {
						print_r($error_info=$db->errorInfo());
						exit;
					}



				}
			}else {
				print_r($error_info=$db->errorInfo());
				exit;
			}



			$sql=sprintf('select `Product Family Key`,`Product Family Name`,`Product Family Code`,`Product Family Main Department Code`,`Product Family Main Department Code`,`Product Family Valid From`,`Product Family Description` from `Product Family Dimension` where `Product Family Store Key`=%d and `Product Family Key`!=%d',
				$store->id,
				$store->get('Store No Products Family Key')
			);

			if ($result2=$db->query($sql)) {
				foreach ($result2 as $row2) {
					$families=new Category($category_fam_key);
					$data=array(
						'Category Store Key'=>$store->id,
						'Category Subject'=>'Product',
						'Category Label'=>$row2['Product Family Name']
					);
					$family=create_subcategory($families, $editor, $data, $row2['Product Family Code']);

					if (!$family) {
						$family=new Category('find', array(
								'Category Store Key'=>$store->id,
								'Category Parent Key'=>$departments->id,
								'Category Code'=>$row2['Product Family Code']

							));
					}


					$sql=sprintf("select * from `Image Bridge` where `Subject Type`='Family' and `Subject Key`=%d", $row2['Product Family Key']);
					if ($result3=$db->query($sql)) {
						foreach ($result3 as $row3) {
							$sql=sprintf("insert into `Image Bridge` (`Subject Type`,`Subject Key`,`Image Key`,`Is Principal`,`Image Caption`) values (%s,%d,%d,%s,%s)",
								prepare_mysql('Category'),
								$family->id,
								$row3['Image Key'],
								prepare_mysql( $row3['Is Principal']),
								prepare_mysql( $row3['Image Caption'], false)

							);
							//print "$sql\n";
							$db->exec($sql);
						}
					}else {
						print_r($error_info=$db->errorInfo());
						exit;
					}


					$sql=sprintf('update `Product Category Dimension` set `Product Category Valid From`=%s,`Product Category Description`=%s where `Product Category Key`=%d ',
						prepare_mysql($row2['Product Family Valid From']),
						prepare_mysql($row2['Product Family Description']),
						$family->id

					);
					$db->exec($sql);


					$department=new Category('find', array(
							'Category Store Key'=>$store->id,
							'Category Parent Key'=>$category_dept_key,
							'Category Code'=>$row2['Product Family Main Department Code']

						));

					if ($department->id) {
						$department->associate_subject($family->id);
					}

					$sql=sprintf('select * from `Product Dimension` where  `Product Store Key`=%d and `Product Family Key`=%d ',
						$store->id,
						$row2['Product Family Key']);

					if ($result3=$db->query($sql)) {
						foreach ($result3 as $row3) {
							$product=new Product($row3['Product ID']);

							if ($product->id) {

								$family->associate_subject($product->id);
							}



						}
					}else {
						print_r($error_info=$db->errorInfo());
						exit;
					}






				}
			}else {
				print_r($error_info=$db->errorInfo());
				exit;
			}



		}

	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}



}


function migrate_products($db, $editor) {


	$sql=sprintf('update `Product Dimension` set  `Product Status`="Discontinued"  ');
	$db->exec($sql);

	$sql=sprintf('select * from `Product Dimension` where `Product ID`=2021');

	$sql=sprintf('select * from `Product Dimension` order by `Product ID` desc');

	if ($result=$db->query($sql)) {



		foreach ($result as $row) {
			$editor['Date']=gmdate('Y-m-d H:i:s');

			$status='Active';



			if ($row['Product Record Type']=='Historic' or $row['Product Main Type']=='Discontinued' or $row['Product Sales Type']=='Not for Sale') {
				$status='Discontinued';
			}



			if ($status=='Active') {
				$sql=sprintf('select count(*) as num from  `Product Dimension` where `Product Store Key`=%d and `Product Status`="Active" and `Product Code`=%s ',
					$row['Product Store Key'],
					prepare_mysql($row['Product Code'])

				);

				if ($result2=$db->query($sql)) {
					if ($row2 = $result2->fetch()) {
						if ($row2['num']>0) {
							$status='Discontinued';
						}
					}
				}else {
					print_r($error_info=$db->errorInfo());
					exit;
				}

			}


			$new_product=new Product( $row['Product ID']);





			$new_product->update(array(
					'Product Status'=>$status
				), 'no_history');





			continue;


			/*
		$outer_name=$row['Product Name'];
		if ($row['Product Units Per Case']>1) {
			$outer_name=$row['Product Units Per Case'].'x '.$outer_name;
		}


		$unit_type=array();
		$unit_type[$row['Product Unit Type']]=_($row['Product Unit Type']);
		$unit_type=json_encode($unit_type);
		$data=array(
			'editor'=>$editor,

			'Store Product Status'=>$status,
			'Store Product Store Key'=>$row['Product Store Key'],
			'Store Product Code'=>$row['Product Code'],

			'Store Product Label in Family'=>$row['Product Special Characteristic'],
			'Store Product Valid From'=>$row['Product Valid From'],
			'Store Product Valid To'=>$row['Product Valid To'],
			'Store Product Price'=>$row['Product Price'],
			'Store Product Outer Description'=>$outer_name,
			'Store Product Outer Tariff Code'=>$row['Product Tariff Code'],
			'Store Product Outer Duty Rate'=>$row['Product Duty Rate'],
			'Store Product Outer Weight'=>($row['Product Package Weight']==0?'':$row['Product Package Weight']),

			'Store Product Outer Dimensions'=>$row['Product Package XHTML Dimensions'],
			'Store Product Outer UN Number'=>$row['Product UN Number'],
			'Store Product Outer UN Class'=>$row['Product UN Class'],
			'Store Product Outer Packing Group'=>$row['Product Packing Group'],
			'Store Product Outer Proper Shipping Name'=>$row['Product Proper Shipping Name'],
			'Store Product Outer Hazard Indentification Number'=>$row['Product Hazard Indentification Number'],
			'Store Product Units Per Outer'=>$row['Product Units Per Case'],
			'Store Product Unit Description'=>$row['Product Name'],
			'Store Product Unit Type'=>$unit_type,
			'Store Product Unit Weight'=>($row['Product Unit Weight']==0?'':$row['Product Unit Weight']),
			'Store Product Unit Dimensions'=>$row['Product Unit XHTML Dimensions'],

		);
		//print_r($data);
		$product=new Product('find', $data, 'create');


		$sql=sprintf('update `Store Product Dimension` set  `Store Product Key`=%d  where  `Store Product Key`=%d',
			$row['Product ID'],
			$product->pid
		);
		$db->exec($sql);

		$product=new Product($row['Product ID']);
*/
			//$product=new Product('pid', $row['Product ID']);




		}

	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}


}








function create_subcategory($category, $editor, $data , $code, $suffix='') {



	$data['Category Code']=$code.($suffix!=''?'.'.$suffix:'');

	$subcategory=$category->create_category($data);
	if ($subcategory->new) {
		return $subcategory;

	}else {
		if ($suffix=='') {
			$suffix=2;
		}else {
			$suffix++;
		}


		create_subcategory($category, $editor, $data , $code, $suffix);


	}




}


function create_main_category($store, $editor, $type , $suffix='') {


	if ($type=='Families') {
		$prefix='Fam';
		$label=_('Families');
		$subject='Product';
		$scope='Product';
	}elseif ($type=='Departments') {
		$prefix='Dept';
		$label=_('Departments');
		$subject='Category';
		$scope='Product';

	}else {
		exit('wrong special product category type '.$type);
	}


	$data=array(
		'Category Code'=>$prefix.'.'.$store->get('Code').($suffix!=''?'.'.$suffix:''),
		'Category Label'=>$label,
		'Category Scope'=>$scope,
		'Category Subject'=>$subject,
		'Category Store Key'=>$store->id,
		'Category Can Have Other'=>'No',
		'Category Locked'=>'Yes',
		'Category Branch Type'=>'Root',
		'editor'=>$editor

	);


	//print_r($data);

	$category=new Category('find create', $data);

	if ($category->error or !$category->id) {
		print_r($category);
		exit;

	}

	if (!$category->new) {
		//print "dup\n";
		//print_r($data);

	}


	return $category->id;
	/*
	if ($category->new) {
		return $category->id;

	}else {



		print_r($category);



		if ($suffix=='') {
			$suffix=2;
		}else {
			$suffix++;
		}


		create_main_category($store, $editor, $type, $suffix);


	}
*/



}


?>
