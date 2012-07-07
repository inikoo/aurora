<?php

if(!isset($skip_common))include_once 'common.php';



if (!isset($page_key) and isset($_REQUEST['id'])) {
	$page_key=$_REQUEST['id'];
}

if (!isset($page_key)) {

	header('Location: index.php?no_page_key');
	exit;
}

$page=new Page($page_key);

if (!$page->id) {
	header('Location: index.php?no_page');
	exit;
}


if ($page->data['Page Site Key']!=$site->id) {
	header('Location: index.php?site_page_not_match');
	//    exit("No site/page not match");
	exit;
}



update_page_key_visit_log($page->id,$user_click_key);


$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	$yui_path.'button/assets/skins/sam/button.css',
	$yui_path.'editor/assets/skins/sam/editor.css',
	$yui_path.'assets/skins/sam/autocomplete.css',

);
$js_files=array(
	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable-min.js',
	$yui_path.'container/container-min.js',
	$yui_path.'editor/editor-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
	$yui_path.'uploader/uploader-min.js',

	'external_libs/ampie/ampie/swfobject.js',
	'js/common.js',
	//      'js/table_common.js',

	'js/edit_common.js',

	'js/page.js'
);

$template_suffix='';
if ($page->data['Page Code']=='login') {
	$Sk="skstart|".(date('U')+300000)."|".ip()."|".IKEY."|".sha1(mt_rand()).sha1(mt_rand());
	$St=AESEncryptCtr($Sk,SKEY, 256);
	$smarty->assign('St',$St);

	if (isset($_REQUEST['logged_out'])) {
		$smarty->assign('logged_out',1);

	}

	if (isset($_REQUEST['from']) and is_numeric($_REQUEST['from'])) {
		$referral=$_REQUEST['from'];
	} else {
		$referral='';
	}
	$smarty->assign('referral',$referral);

	$js_files[]='js/aes.js';
	$js_files[]='js/sha256.js';
	$css_files[]='css/inikoo.css';

}
else if ($page->data['Page Code']=='registration') {
		$welcome=false;
		if ($logged_in) {

			if (isset($_REQUEST['welcome'])) {
				$welcome=true;
			} else {

				header('location: profile.php');
				exit;
			}

		}


		$smarty->assign('welcome',$welcome);
		$smarty->assign('customer',$customer);

		$js_files[]='js/aes.js';
		$js_files[]='js/sha256.js';
		$css_files[]='css/inikoo.css';


		$categories=array();
		$sql=sprintf("select `Category Key` from `Category Dimension` where `Category Subject`='Customer' and `Category Deep`=1 and `Category Store Key`=%d and `Category Show New Subject`='Yes'",$store_key);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$tmp=new Category($row['Category Key']);



			$categories[$row['Category Key']]=$tmp;

		}
		$smarty->assign('categories',$categories);
	}
else if ($page->data['Page Code']=='profile') {


		//$js_files[]='upload_common.js.php';

		if (!$logged_in) {
			header('location: login.php');
			exit;
		}


		if (isset($_REQUEST['view']) and
			in_array($_REQUEST['view'],array('contact','orders','address_book','change_password', 'add_address','products',
					'edit_address', 'invoices', 'delivery_notes'))) {
			$view=$_REQUEST['view'];
		} else {
			$view='contact';
		}

		$smarty->assign('user',$user);

		if (isset($_REQUEST['view']) && $_REQUEST['view']=='delivery_notes') {
			if (isset($_REQUEST['id']))
				$smarty->assign('id',$_REQUEST['id']);
			$dn=new DeliveryNote($_REQUEST['id']);
			$smarty->assign('dn',$dn);
			$smarty->assign('user',$user);
		}

		if (isset($_REQUEST['view']) && $_REQUEST['view']=='invoices') {
			if (isset($_REQUEST['id']))
				$smarty->assign('id',$_REQUEST['id']);
			$smarty->assign('user',$user);
			$invoice=new Invoice($_REQUEST['id']);
			//print_r($invoice);
			$smarty->assign('invoice',$invoice);

			$tax_data=array();
			$sql=sprintf("select `Tax Category Name`,`Tax Category Rate`,`Tax Amount` from  `Invoice Tax Bridge` B  left join `Tax Category Dimension` T on (T.`Tax Category Code`=B.`Tax Code`)  where B.`Invoice Key`=%d ",$invoice->id);

			$res=mysql_query($sql);
			while ($row=mysql_fetch_assoc($res)) {
				$tax_data[]=array('name'=>$row['Tax Category Name'],'amount'=>money($row['Tax Amount'],$invoice->data['Invoice Currency']));
			}

			$smarty->assign('tax_data',$tax_data);

		}



$custom_fields=array();
$sql=sprintf("select * from `Custom Field Dimension`  where `Custom Field Table`='Customer' and `Custom Field In Profile`='Yes'  ");



		
		$result=mysql_query($sql);
		mysql_fetch_assoc($result);
		while ($row=mysql_fetch_assoc($result)) {
			$sql=sprintf("select * from `Customer Custom Field Dimension` where `Customer Key`=%d", $customer->id);

			$res=mysql_query($sql);
			$r=mysql_fetch_assoc($res);
			$val=$r[$row['Field']];


			$sql=sprintf("select * from `Custom Field Dimension` where `Custom Field Key`=%d", $row['Field']);
			$res=mysql_query($sql);
			$r=mysql_fetch_assoc($res);

			$custom_fields[]=array('name'=>$r['Custom Field Name'], 'value'=>$val, 'type'=>$r['Custom Field Type']);
		}

		$smarty->assign('custom_fields',$custom_fields);

		$template_suffix='_'.$view;
		$order_template='dummy.tpl';
		if (isset($_REQUEST['order_id'])) {
			$order=new Order($_REQUEST['order_id']);

			$smarty->assign('customer',$customer);



			if (!$order->id) {
				header('Location: profile.php');
				exit;
			}
			$smarty->assign('order',$order);

			if ($order->get('Order XHTML Invoices') != '') {
				$invoice_number=explode("?id=", $order->get('Order XHTML Invoices'));
				$invoice_number=explode("\"", $invoice_number[1]);
				$smarty->assign('invoice_number',$invoice_number[0]);
			}
			if ($order->get('Order XHTML Invoices') != '') {
				$dn_number=explode("?id=", $order->get('Order XHTML Delivery Notes'));
				$dn_number=explode("\"", $dn_number[1]);
				$smarty->assign('dn_number',$dn_number[0]);
			}
			switch ($order->get('Order Current Dispatch State')) {

			case('In Process'):
			case('Ready to Pick'):
				$js_files[]='js/edit_common.js';


				$js_files[]='edit_address.js.php';
				$js_files[]='address_data.js.php?tipo=customer&id='.$customer->id;

				$js_files[]='edit_delivery_address_js/common.js';
				$js_files[]='order_in_process.js.php?order_key='.$order_id.'&customer_key='.$customer->id;

				$css_files[]='css/edit_address.css';


				$order_template='order_in_process.tpl';



				$_SESSION['state']['order']['store_key']=$order->data['Order Store Key'];

				if ($order->data['Order Number Items']) {
					$products_display_type='ordered_products';

				} else {
					$products_display_type='all_products';

				}

				$_SESSION['state']['order']['products']['display']=$products_display_type;

				$products_display_type=$_SESSION['state']['order']['products']['display'];

				$smarty->assign('products_display_type',$products_display_type);




				$tipo_filter=$_SESSION['state']['order']['products']['f_field'];


				$smarty->assign('filter',$tipo_filter);
				$smarty->assign('filter_value',$_SESSION['state']['order']['products']['f_value']);
				$filter_menu=array(
					'code'=>array('db_key'=>'code','menu_label'=>'Code starting with <i>x</i>','label'=>'Code'),
					'family'=>array('db_key'=>'family','menu_label'=>'Family starting with
<i>x</i>','label'=>'Code'),
					'name'=>array('db_key'=>'name','menu_label'=>'Name starting with
<i>x</i>','label'=>'Code')

				);
				$smarty->assign('filter_menu0',$filter_menu);
				$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);


				$paginator_menu=array(10,25,50,100);
				$smarty->assign('paginator_menu0',$paginator_menu);

				$smarty->assign('search_label',_('Products'));
				$smarty->assign('search_scope','products');


				$general_options_list[]=array('tipo'=>'url','url'=>'customers.php?store='.$store->id,'label'=>_(
						'Customers'));

				break;
			case('Dispatched'):



				$smarty->assign('search_label',_('Orders'));
				$smarty->assign('search_scope','orders_store');

				$js_files[]='js/order_dispatched.js';
				$order_template='order_dispatched.tpl';
				$template_suffix='_order_dispatched';

				break;
			case('Cancelled'):
			case('Suspended'):
				$smarty->assign('search_label',_('Orders'));
				$smarty->assign('search_scope','orders_store');

				$js_files[]='js/order_cancelled.js.php';
				$order_template='order_cancelled.tpl';
				break;

			case('Ready to Ship'):
				$js_files[]='js/order_ready_to_ship.js.php';
				$order_template='order_ready_to_ship.tpl';
				break;
			default:
				//exit('todo '.$order->get('Order Current Dispatch State'));
				$order_template='dummy.tpl';
				break;
			}

		}


		//$order_template='dummy.tpl';


		$smarty->assign('order_template',$order_template);




		$smarty->assign('view',$view);

		$smarty->assign('user',$user);
		$rnd='';
		//$js_files[]='address_data.js.php';

		if ($view=='contact')
			$js_files[]='profile_contact.js.php';

		if (isset($_REQUEST['type'])) {
			$smarty->assign('address_identifier',$_REQUEST['type']);
			if ($_REQUEST['type'] == 'delivery_')
				$smarty->assign('address_function','Delivery');
			else if ($_REQUEST['type'] == 'billing_')
					$smarty->assign('address_function','Billing');
				else if ($_REQUEST['type'] == 'contact_')
						$smarty->assign('address_function','Contact');

					if (isset($_REQUEST['index']))
						$smarty->assign('index',$_REQUEST['index']);

		}

		$country_list=array();
		$sql=sprintf("select * from kbase.`Country Dimension`");
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result)) {
			$country_list[]=array('country'=>$row['Country Name'], 'code'=>$row['Country Code']);
		}

		$smarty->assign('country_list',$country_list);


		$categories=array();
		$categories_value=array();
		$sql=sprintf("select `Category Key` from `Category Dimension` where `Category Subject`='Customer' and `Category Deep`=1 and `Category Store Key`=%d and `Category Show Public Edit`='Yes'",$customer->data['Customer Store Key']);

		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$tmp=new Category($row['Category Key']);
			$selected_array=$tmp->sub_category_selected_by_subject($customer->id);


			if (count($selected_array)==0) {
				$tmp_selected='';
			} else {
				$tmp_selected=array_pop($selected_array);
			}

			$categories[$row['Category Key']]=$tmp;
			$categories_value[$row['Category Key']]=$tmp_selected;

		}


		$smarty->assign('categories',$categories);
		$smarty->assign('categories_value',$categories_value);

		$enable_other=array();

		$other_value=array();
		foreach ($categories_value as $key=>$value) {
			$category=new Category($value);

			if ($category->data['Is Category Field Other'] == 'Yes') {

				$sql=sprintf("select * from `Category Bridge` where `Category Key`=%d and `Subject`='Customer' and `Subject Key`=%d", $category->id, $customer->id);
				$result=mysql_query($sql);
				$row=mysql_fetch_assoc($result);
				$enable_other[$category->data['Category Parent Key']]=true;
				$other_value[$category->data['Category Parent Key']]=$row['Customer Other Note'];

			}else {
				$enable_other[$category->data['Category Parent Key']]=false;
			}


		}

		//print_r($other_value);

		$smarty->assign('other_value',$other_value);
		$smarty->assign('enable_other',$enable_other);


		for ($i = 0; $i < 16; $i++) {
			$rnd .= substr("./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789",
				mt_rand(0, 63), 1);
		}

		$epwcp1=sprintf("%sinsecure_key%s",$user->id,$rnd);

		$smarty->assign('epwcp1',$epwcp1);
		$smarty->assign('rnd',$rnd);
		$js_files[]='js/aes.js';
		$js_files[]='js/sha256.js';
		$js_files[]='js/table_common.js';

		$css_files[]='css/container.css';
		$css_files[]='css/edit.css';
		$css_files[]='css/inikoo.css';
		$css_files[]='css/inikoo_table.css';

	}
else if ($page->data['Page Code']=='reset') {
	$css_files[]='css/inikoo.css';
}else{
$js_files[]='js/fill_basket.js';

}



$smarty->assign('logged',$logged_in);
$page->site=$site;
$page->user=$user;
$page->logged=$logged_in;
$page->currency=$store->data['Store Currency Code'];

if ($logged_in) {
	$page->customer=$customer;
	$page->order=$order;
}




$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from
`Page Header External File Bridge` where `Page Header Key`=%d",$page->data['Page Header Key']);
$res=mysql_query($sql);
//print $sql;
while ($row=mysql_fetch_assoc($res)) {
	if ($row['External File Type']=='CSS')
		$css_files[]='public_external_file.php?id='.$row['external_file_key'];
	else
		$js_files[]='public_external_file.php?id='.$row['external_file_key'];

}

$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Page Footer External File Bridge` where `Page Footer Key`=%d",$page->data['Page Footer Key']);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	if ($row['External File Type']=='CSS')
		$css_files[]='public_external_file.php?id='.$row['external_file_key'];
	else
		$js_files[]='public_external_file.php?id='.$row['external_file_key'];

}

$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from
`Site External File Bridge` where `Site Key`=%d",$site->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	if ($row['External File Type']=='CSS')
		$css_files[]='public_external_file.php?id='.$row['external_file_key'];
	else
		$js_files[]='public_external_file.php?id='.$row['external_file_key'];

}


$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from
`Page Store External File Bridge` where `Page Key`=%d",$page->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	if ($row['External File Type']=='CSS')
		$css_files[]='public_external_file.php?id='.$row['external_file_key'];
	else
		$js_files[]='public_external_file.php?id='.$row['external_file_key'];

}

if ($page->data['Page Store Content Display Type']=='Source') {
	$smarty->assign('type_content','string');
	$smarty->assign('template_string',$page->data['Page Store Source']);
}
else {
	$smarty->assign('type_content','file');


	$smarty->assign('template_string',$page->data['Page Store Content Template Filename'].$template_suffix.'.tpl');
	$css_files[]='css/'.$page->data['Page Store Content Template Filename'].$template_suffix.'.css';
	$js_files[]='js/'.$page->data['Page Store Content Template Filename'].$template_suffix.'.js';
}
//
//$customer=new Customer(73257);
$page->customer=$customer;
//print_r($order);
$smarty->assign('filter_name0','Order ID');
$smarty->assign('filter_value0', '');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('title',$page->data['Page Title']);
$smarty->assign('store',$store);
$smarty->assign('page',$page);
$smarty->assign('site',$site);


$smarty->display('page.tpl');

?>
