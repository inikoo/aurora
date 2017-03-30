<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 November 2015 at 14:22:52 GMT, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/


function get_object($object_name, $key, $load_other_data = false) {

    if ($object_name == '') {
        return false;
    }


    global $account, $db;

    switch (strtolower($object_name)) {
        case 'account':
            include_once 'class.Account.php';
            $object = new Account();
            break;
            break;
        case 'customer':
            include_once 'class.Customer.php';
            $object = new Customer('id', $key);
            break;
        case 'store':
            include_once 'class.Store.php';
            $object = new Store($key);
            $object->load_acc_data();
            break;
        case 'product':
        case 'service':
            include_once 'class.Product.php';
            $object = new Product('id', $key);
            $object->get_webpage();
            break;
        case 'order':
            include_once 'class.Order.php';
            $object = new Order($key);
            break;
        case 'invoice':
            include_once 'class.Invoice.php';
            $object = new Invoice($key);
            break;
        case 'delivery_note':
        case 'deliverynote':
        case 'pick_aid':
        case 'pack_aid':
            include_once 'class.DeliveryNote.php';
            $object = new DeliveryNote($key);
            break;
        case 'website':
            include_once 'class.Website.php';

            $object = new Website($key);
            break;
        case 'old_page':
            include_once 'class.Page.php';

            $object = new Page($key);

            break;

        case 'page':
        case 'webpage':
            include_once 'class.Page.php';
        $object = new Page($key);
           // $object = new Webpage($key);

            break;
        case 'page_version':
        case 'webpage_version':
            case 'webpage version':
            include_once 'class.WebpageVersion.php';

            $object = new WebpageVersion($key);

            break;
        case 'warehouse':
            include_once 'class.Warehouse.php';
            $object = new Warehouse($key);
            break;
        case 'part':
            include_once 'class.Part.php';
            $object = new Part($key);
            break;
        case 'supplier':
            include_once 'class.Supplier.php';
            $object = new Supplier($key);
            $object->load_acc_data();
            break;
        case 'employee':
        case 'contractor':
        case 'staff':
            include_once 'class.Staff.php';
            $object = new Staff($key);
            break;
        case 'user':
        case 'User':
            include_once 'class.User.php';
            $object = new User($key);
            break;
        case 'user_root':
            include_once 'class.User.php';
            $object = new User('Administrator');
            break;
        case 'list':
            include_once 'class.List.php';
            $object = new SubjectList($key);
            break;
        case 'payment_service_provider':
            require_once "class.Payment_Service_Provider.php";
            $object = new Payment_Service_Provider($key);
            break;
        case 'payment_account':
            require_once "class.Payment_Account.php";
            $object = new Payment_Account($key);
            break;
        case 'payment':
            require_once "class.Payment.php";
            $object = new Payment($key);
            break;
        case 'api_key':
            require_once "class.API_Key.php";
            $object = new API_Key($key);
            break;
        case 'timesheet':
            require_once "class.Timesheet.php";
            $object = new Timesheet($key);
            break;
        case 'timesheet_record':
            require_once "class.Timesheet_Record.php";
            $object = new Timesheet_Record($key);
            break;
        case 'attachment':
            require_once "class.Attachment.php";
            $object = new Attachment('bridge_key', $key);
            break;
        case 'overtime':
            require_once "class.Overtime.php";
            $object = new Overtime($key);
            break;
        case 'category':
            require_once "class.Category.php";
            $object = new Category($key);
            break;
        case 'manufacture_task':
            require_once "class.Manufacture_Task.php";
            $object = new Manufacture_Task($key);
            break;
        case 'timeserie':
        case 'timeseries':
            require_once "class.Timeserie.php";
            $object = new Timeseries($key);
            break;

        case 'image.subject':
            require_once "class.Image.php";
            $object = new Image('image_bridge_key', $key);
            break;
        case 'upload':
            require_once "class.Upload.php";
            $object = new Upload($key);
            break;
        case 'supplier_part':
        case 'supplier part':
            require_once "class.SupplierPart.php";
            $object = new SupplierPart($key);
            break;
        case 'barcode':
            require_once "class.Barcode.php";
            $object = new Barcode($key);
            break;
        case 'agent':
            require_once "class.Agent.php";
            $object = new Agent($key);
            break;
        case 'location':
            require_once "class.Location.php";
            $object = new Location($key);
            break;
        case 'part_location':
        case 'partlocation':
            require_once "class.PartLocation.php";
            $object = new PartLocation($key);
            break;
        case 'campaign':
        case 'deal campaign':
            require_once "class.DealCampaign.php";
            $object = new DealCampaign($key);
            break;
        case 'deal':
            require_once "class.Deal.php";
            $object = new Deal($key);
            break;
        case 'purchase_order':
        case 'purchase order':
        case 'purchaseorder':
            require_once "class.PurchaseOrder.php";
            $object = new PurchaseOrder($key);
            break;
        case 'upload':
            require_once "class.Upload.php";
            $object = new Upload($key);
            break;
        case 'node':
        case 'website node':
        case 'websitenode':
            require_once "class.WebsiteNode.php";
            $object = new WebsiteNode($key);
            break;
        case 'purchaseorderitem':

            $sql = sprintf(
                "SELECT `Supplier Part Key` FROM `Purchase Order Transaction Fact` WHERE `Purchase Order Transaction Fact Key`=%d ", $key
            );
            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    require_once "class.SupplierPart.php";
                    $object = new SupplierPart($row['Supplier Part Key']);
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }
            break;
        case 'supplierdelivery':
        case 'supplier delivery':
            require_once "class.SupplierDelivery.php";
            $object = new SupplierDelivery($key);
            $object->get_order_data();
            break;
        case 'material':
            require_once "class.Material.php";
            $object = new Material($key);
            break;

        case 'supplier_production':
            require_once "class.Supplier_Production.php";
            $object = new Supplier_Production($key);
            break;
        case 'position':
            require_once "class.Job_Position.php";
            $object = new Job_Position($key);
            break;

        case 'webpage_type':
            require_once "class.Webpage_Type.php";
            $object = new Webpage_Type($key);
            break;
        default:
            exit('need to complete E1: >'.strtolower($object_name)."<\n");
            break;
    }


    return $object;


}


?>
