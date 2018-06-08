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

    if ($load_other_data != '') {
        $load_other_data = '-'.$load_other_data;

    }


    global $account, $db;


    switch (strtolower($object_name.$load_other_data)) {
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
        case 'public_product':
            include_once 'class.Public_Product.php';
            $object = new Public_Product($key);

            break;
        case 'public_store':
            include_once 'class.Public_Store.php';
            $object = new Public_Store($key);

            break;
        case 'product-historic_key':
            include_once 'class.Product.php';
            $object = new Product('historic_key', $key);
            break;

        case 'order':
            include_once 'class.Order.php';
            $object = new Order($key);
            break;

        case 'refund':
        case 'invoice':
            include_once 'class.Invoice.php';
            $object = new Invoice($key);
            break;
        case 'delivery_note':
        case 'delivery note':
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
        case 'public_website':
            include_once 'class.Public_Website.php';

            $object = new Public_Website($key);
            break;
        case 'websiteuser':
        case 'website_user':
            include_once 'class.Website_User.php';

            $object = new Website_User($key);
            break;
        case 'image':
            include_once 'class.Image.php';
            $object = new Image($key);
            break;

        case 'old_page':
        case 'page':
        case 'webpage':

            include_once 'class.Page.php';

            $object = new Page($key);

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
        case 'api key':
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
        case 'part_family': // needed for export edit products in part family

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
        case 'supplierpart':
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
        case 'dealcampaign':
        case 'deal campaign':
        case 'deal_campaign':
        case 'campaign_order_recursion':
            require_once "class.DealCampaign.php";
            $object = new DealCampaign($key);
            break;
        case 'deal':
            require_once "class.Deal.php";
            $object = new Deal($key);
            break;
        case 'deal component':
        case 'dealcomponent':
        case 'deal_component':
            require_once "class.DealComponent.php";
            $object = new DealComponent($key);
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
        case 'website_footer':
        case 'footer':
            require_once "class.WebsiteFooter.php";
            $object = new WebsiteFooter($key);
            break;
        case 'website_header':
        case 'header':
            require_once "class.WebsiteHeader.php";
            $object = new WebsiteHeader($key);
            break;
        case 'email_blueprint':
            require_once "class.Email_Blueprint.php";
            $object = new Email_Blueprint($key);
            break;
        case 'email_template':
            require_once "class.Email_Template.php";
            $object = new Email_Template($key);
            break;
        case 'published_email_template':
            require_once "class.Published_Email_Template.php";
            $object = new Published_Email_Template($key);
            break;
        case 'payment_account':
        case 'payment account':
            require_once "class.Payment_Account.php";
            $object = new Payment_Account($key);
            break;
        case 'payment_account-block':
        case 'payment account-block':
            require_once "class.Payment_Account.php";
            $object = new Payment_Account('block', $key);
            break;
        case 'tax_category':
            require_once "class.TaxCategory.php";
            $object = new TaxCategory($key);
            break;
        case 'tax_category-key':
            require_once "class.TaxCategory.php";
            $object = new TaxCategory('key', $key);
            break;
        case 'charge':
            require_once "class.Charge.php";
            $object = new Charge($key);
            break;
        case 'timeseries_record':
        case 'timeseriesrecord':
        require_once "class.TimeseriesRecord.php";
            $object = new TimeseriesRecord($key);
            break;

        case 'shipping_zone':
        case 'shippingzone':
            require_once "class.Shipping_Zone.php";
            $object = new Shipping_Zone($key);
            break;
        case 'shipping_option':
        case 'shippingoption':
            require_once "class.Shipping_Option.php";
            $object = new Shipping_Option($key);
            break;
        case 'email_campaign':
        case 'emailcampaign':
        case 'email campaign':
            require_once "class.EmailCampaign.php";
            $object = new EmailCampaign($key);
            break;
        case 'pagedeleted':
            require_once "class.PageDeleted.php";
            $object = new PageDeleted($key);
            break;
        case 'customer_poll_query':
        case 'customer poll query':
        case 'customerpollquery':
            require_once "class.Customer_Poll_Query.php";
            $object = new Customer_Poll_Query($key);
            break;
        case 'customer_poll_query_option':
        case 'customer poll query option':
        case 'customerpollqueryoption':
            require_once "class.Customer_Poll_Query_Option.php";
            $object = new Customer_Poll_Query_Option($key);
            break;
        case 'email_campaign_type':
        case 'emailcampaigntype':

            require_once "class.EmailCampaignType.php";
            $object = new EmailCampaignType($key);
            break;
        case 'prospect':
            include_once 'class.Prospect.php';
            $object = new Prospect('id', $key);
            break;
        case 'email_tracking':
            include_once 'class.Email_Tracking.php';
            $object = new Email_Tracking('id', $key);
            break;
        case 'email template':
        case 'email_template':
        case 'emailtemplate':

        include_once 'class.Email_Template.php';
            $object = new Email_Template('id', $key);
            break;
        default:
            exit('need to complete E1: x>'.strtolower($object_name).'<<++>>'.$load_other_data."<\n");
            break;
    }


    return $object;


}


?>
