<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 6 November 2015 at 14:22:52 GMT, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/



function get_object($object_name, $key,$load_other_data=false) {

	global $account;

	switch ($object_name) {
	case 'account':

		$object=$account;
		break;
	case 'customer':
	case 'Customer':
		include_once 'class.Customer.php';
		$object=new Customer($key);
		break;
	case 'store':
	case 'Store':
		include_once 'class.Store.php';
		$object=new Store($key);
		break;
	case 'storeproduct':
	case 'product':
	case 'StoreProduct':
	case 'Store Product':
	case 'Product':
		include_once 'class.StoreProduct.php';
		$object=new StoreProduct($key);
		
		break;
	case 'order':
		include_once 'class.Order.php';
		$object=new Order($key);
		break;
	case 'invoice':
		include_once 'class.Invoice.php';
		$object=new Invoice($key);
		break;
	case 'delivery_note':
	case 'pick_aid':
	case 'pack_aid':
		include_once 'class.DeliveryNote.php';
		$object=new DeliveryNote($key);
		break;
	case 'website':
	case 'Site':
		include_once 'class.Site.php';

		$object=new Site($key);
		break;
	case 'page':
		$object=new Page($key);

		break;
	case 'warehouse':
	case 'Warehouse':
		include_once 'class.Warehouse.php';
		$object=new Warehouse($key);
		break;
	case 'part':
	case 'Part':
		include_once 'class.Part.php';
		$object=new Part($key);
		break;
	case 'supplier':
	case 'Supplier':
		include_once 'class.Supplier.php';
		$object=new Supplier($key);
		break;
	case 'employee':
	case 'contractor':
	case 'Staff':
		include_once 'class.Staff.php';
		$object=new Staff($key);
		break;
	case 'user':
	case 'User':
		include_once 'class.User.php';
		$object=new User($key);
		break;
	case 'list':
		include_once 'class.List.php';
		$object=new SubjectList($key);
		break;
	case 'payment_service_provider':
		require_once "class.Payment_Service_Provider.php";
		$object=new Payment_Service_Provider($key);
		break;
	case 'payment_account':
		require_once "class.Payment_Account.php";
		$object=new Payment_Account($key);
		break;
	case 'payment':
	case 'Payment':
		require_once "class.Payment.php";
		$object=new Payment($key);
		break;
	case 'api_key':
	case 'API Key':
		require_once "class.API_Key.php";
		$object=new API_Key($key);
		break;
	case 'timesheet':
		require_once "class.Timesheet.php";
		$object=new Timesheet($key);
		break;
	case 'timesheet_record':
		require_once "class.Timesheet_Record.php";
		$object=new Timesheet_Record($key);
		break;
	case 'attachment':
	case 'Attachment':
		require_once "class.Attachment.php";
		$object=new Attachment('bridge_key', $key);
		break;
	case 'overtime':
		require_once "class.Overtime.php";
		$object=new Overtime($key);
		break;
	case 'category':
	case 'Category':
		require_once "class.Category.php";
		$object=new Category($key);
		break;
	case 'manufacture_task':
	case 'Manufacture Task':
		require_once "class.Manufacture_Task.php";
		$object=new Manufacture_Task($key);
		break;
	case 'timeserie':
	case 'timeseries':
		require_once "class.Timeseries.php";
		$object=new Timeseries($key);
		break;
		
	case 'image.subject':
		require_once "class.Image.php";
		$object=new Image('image_bridge_key',$key);
		break;	
	default:
		exit('need to complete E1 '.$object_name);
		break;
	}


	return $object;




}


?>
