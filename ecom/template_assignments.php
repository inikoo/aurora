<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 17 December 2014 21:12:19 GMT, Nottingham, UK

 Copyright (c) 2014, Inikoo

 Version 2.0
*/

$_site=array(
	'telephone'=>$site->data['Site Contact Telephone'],
	'address'=>$site->data['Site Contact Address'],
	'email'=>$site->data['Site Contact Email'],
	'company_name'=>$store->data['Store Company Name'],
	'company_tax_number'=>$store->data['Store VAT Number'],
	'company_number'=>$store->data['Store Company Number'],
	'id'=>$site->id,
	'store_id'=>$site->data['Site Store Key'],
	'locale'=>$site->data['Site Locale'],
);

$menu=$page->display_menu();

$_page=array(
	'found_in'=>$page->display_found_in(),
	'title'=>$page->display_title(),
	'search'=>$page->display_search(),
	'id'=>$page->id,
	'menu'=>$menu,
	'top_menu'=>$menu
);


if ($page->data['Page Store Section Type']=='Family') {
	$smarty->assign('_products', $page->get_products_data());

	$smarty->assign('_related_products', $page->get_related_products_data());


	$family=new Family($page->data['Page Parent Key']);
	$smarty->assign('family', $family);


	include_once 'class.Public_Category.php';
	include_once 'class.Public_Webpage.php';
	include_once 'class.Public_Product.php';
	include_once 'class.Public_Customer.php';
	include_once 'class.Public_Order.php';
	include_once 'class.Public_Website_User.php';

	$public_category=new Public_Category('root_key_code', $store->get('Store Family Category Key'), $family->get('Product Family Code'));


	$public_category->load_webpage();

	$public_customer=new Public_Customer($customer->id);
	$public_order=new Public_Order($order_in_process->id);


	if($user=='') {
        $public_user=new Public_Website_User(0);
    }else{
        $public_user=new Public_Website_User($user->id);
	}





	$products = array();

	$sql = sprintf(
		"SELECT  P.`Product ID` FROM `Category Bridge` B  LEFT JOIN `Product Dimension` P ON (`Subject Key`=P.`Product ID`)  LEFT JOIN `Product Category Index` S ON (`Subject Key`=S.`Product Category Index Product ID` AND S.`Product Category Index Category Key`=B.`Category Key`)  WHERE  `Category Key`=%d  AND `Product Web State` IN  ('For Sale','Out of Stock')   ORDER BY `Product Web State`, ifnull(`Product Category Index Published Stack`,99999999),`Product Code File As` ",
		$public_category->id
	);


	if ($result = $db->query($sql)) {
		foreach ($result as $row) {
			$products[] = new Public_Product($row['Product ID']);
		}
	} else {
		print_r($error_info = $db->errorInfo());
		print "$sql\n";
		exit;
	}


    $related_products = array();

    $sql = sprintf(
        "SELECT `Webpage Related Product Product ID`  FROM `Webpage Related Product Bridge` B  LEFT JOIN `Product Dimension` P ON (`Webpage Related Product Product ID`=P.`Product ID`)  WHERE  `Webpage Related Product Page Key`=%d  AND `Product Web State` IN  ('For Sale','Out of Stock')   ORDER BY `Webpage Related Product Order`",
        $public_category->webpage->id
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $related_products[] = new Public_Product($row['Webpage Related Product Product ID']);
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


	$smarty->assign('products', $products);
    $smarty->assign('related_products', $related_products);
    $smarty->assign('category', $public_category);
	$smarty->assign('customer', $public_customer);
	$smarty->assign('order', $public_order);
	$smarty->assign('user', $public_user);



}elseif ($page->data['Page Store Section Type']=='Department') {
	$smarty->assign('_families', $page->get_families_data());
	$department=new Department($page->data['Page Parent Key']);
	$smarty->assign('department', $department);
}elseif ($page->data['Page Store Section Type']=='Product') {
	$smarty->assign('product', $page->get_product_data());
}elseif ($page->data['Page Store Section']=='Front Page Store') {
	$smarty->assign('_departments', $page->get_departments_data());
}






$smarty->assign('_page', $_page);
$smarty->assign('_site', $_site);





?>
