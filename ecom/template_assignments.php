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


    $content_data = $public_category->webpage->get('Content Data');




    if (isset($content_data['panels'])) {
        $panels = $content_data['panels'];
    } else {
        $panels = array();
    }



	$products = array();

	$sql = sprintf(
		"SELECT  P.`Product ID`,`Product Category Index Content Published Data` FROM `Category Bridge` B  LEFT JOIN `Product Dimension` P ON (`Subject Key`=P.`Product ID`)  LEFT JOIN `Product Category Index` S ON (`Subject Key`=S.`Product Category Index Product ID` AND S.`Product Category Index Category Key`=B.`Category Key`)  WHERE  `Category Key`=%d  AND `Product Web State` IN  ('For Sale','Out of Stock')   ORDER BY `Product Web State`, ifnull(`Product Category Index Published Stack`,99999999),`Product Code File As` ",
		$public_category->id
	);

    $stack_index = 0;
	if ($result = $db->query($sql)) {
		foreach ($result as $row) {


		//	print $stack_index."\n";

            if (isset($panels[$stack_index])) {
                $products[] = array(
                    'type' => 'panel',
                    'data' => $panels[$stack_index]
                );

                $size=floatval($panels[$stack_index]['size']);




                unset($panels[$stack_index]);
                $stack_index+=$size;

                list($stack_index, $products) = get_next_panel($stack_index, $products, $panels);

            }



            if ($row['Product Category Index Content Published Data'] == '') {
                $product_content_data = array('header_text' => '');
            } else {
                $product_content_data =json_decode($row['Product Category Index Content Published Data'],true);

            }

            $products[] = array(
                'type'        => 'product',
                'object'      => new Public_Product($row['Product ID']),
                'header_text' => (isset($product_content_data['header_text'])?$product_content_data['header_text']:'')
            );
            $stack_index++;
		}
	} else {
		print_r($error_info = $db->errorInfo());
		print "$sql\n";
		exit;
	}


    $related_products = array();

    $sql = sprintf(
        "SELECT `Webpage Related Product Key`,`Webpage Related Product Product ID`,`Webpage Related Product Content Published Data`  FROM `Webpage Related Product Bridge` B  LEFT JOIN `Product Dimension` P ON (`Webpage Related Product Product ID`=P.`Product ID`)  WHERE  `Webpage Related Product Page Key`=%d  AND `Product Web State` IN  ('For Sale','Out of Stock')   ORDER BY `Webpage Related Product Published Order`",
        $public_category->webpage->id
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            if ($row['Webpage Related Product Content Published Data'] == '') {
                $product_content_data = array('header_text' => '');
            } else {
                $product_content_data =json_decode($row['Webpage Related Product Content Published Data'],true);

            }

            $related_products[] = array(
                'header_text' => (isset($product_content_data['header_text'])?$product_content_data['header_text']:''),
                'object'      =>  new Public_Product($row['Webpage Related Product Product ID']),
                'index_key'   => $row['Webpage Related Product Key'],


            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }

    //print_r($products);

	$smarty->assign('products', $products);
    $smarty->assign('related_products', $related_products);
    $smarty->assign('category', $public_category);
	$smarty->assign('customer', $public_customer);
	$smarty->assign('order', $public_order);
	$smarty->assign('user', $public_user);



}
elseif ($page->data['Page Store Section Type']=='Department') {


	$smarty->assign('_families', $page->get_families_data());
	$department=new Department($page->data['Page Parent Key']);
	$smarty->assign('department', $department);



    include_once 'class.Public_Category.php';
    include_once 'class.Public_Webpage.php';
    include_once 'class.Public_Product.php';
    include_once 'class.Public_Customer.php';
    include_once 'class.Public_Order.php';
    include_once 'class.Public_Website_User.php';

    $public_category=new Public_Category('root_key_code', $store->get('Store Department Category Key'), $department->get('Product Department Code'));


    $public_category->load_webpage();

    $public_customer=new Public_Customer($customer->id);
    $public_order=new Public_Order($order_in_process->id);


    if($user=='') {
        $public_user=new Public_Website_User(0);
    }else{
        $public_user=new Public_Website_User($user->id);
    }


    $content_data = $public_category->webpage->get('Content Data');

    $smarty->assign('content_data', $content_data);

    $smarty->assign('sections', $content_data['sections']);



    $smarty->assign('category', $public_category);
    $smarty->assign('customer', $public_customer);
    $smarty->assign('order', $public_order);
    $smarty->assign('user', $public_user);


}elseif ($page->data['Page Store Section Type']=='Product') {
	$smarty->assign('product', $page->get_product_data());
}elseif ($page->data['Page Store Section']=='Front Page Store') {
	$smarty->assign('_departments', $page->get_departments_data());
}






$smarty->assign('_page', $_page);
$smarty->assign('_site', $_site);



function get_next_panel($stack_index, $products, $panels) {

    if (isset($panels[$stack_index])) {
        $products[] = array(
            'type' => 'panel',
            'data' => $panels[$stack_index]
        );

        $size=floatval($panels[$stack_index]['size']);
        unset($panels[$stack_index]);
        $stack_index+=$size;
        list($stack_index, $products) = get_next_panel($stack_index, $products, $panels);
    }

    return array(
        $stack_index,
        $products
    );

}


?>
