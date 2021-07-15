<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 November 2015 at 14:22:52 GMT, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

/**
 * @param      $object_name
 * @param      $key
 * @param bool $load_other_data
 *
 * @return \Account|bool|\Customer|\Product|\Public_Category|\Public_Product|\Store|\PurchaseOrder|\Part|\Raw_Material|\Location|\Order|\DeliveryNote|\Website|\Attachment|Warehouse
 */
function get_object($object_name, $key, $load_other_data = false) {

    if ($object_name == '') {
        return false;
    }

    if ($load_other_data != '') {
        $load_other_data = '-'.$load_other_data;

    }


    global $db;


    switch (strtolower($object_name.$load_other_data)) {
        case 'account':
            include_once 'class.Account.php';
            $object = new Account();
            break;

        case 'customer':
            include_once 'class.Customer.php';
            /**
             * @var $object \Customer
             */
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
        case 'public_category':
            include_once 'class.Public_Category.php';
            $object = new Public_Category($key);

            break;
        case 'public_store':
            include_once 'class.Public_Store.php';
            $object = new Public_Store($key);

            break;
        case 'public_webpage':
            include_once 'class.Public_Webpage.php';
            $object = new Public_Webpage($key);

            break;
        case 'public_webpage-scope_product':
            include_once 'class.Public_Webpage.php';
            $object = new  Public_Webpage('scope', 'Product', $key);
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
        case 'invoice_deleted':
            include_once 'class.Invoice.php';
            $object = new Invoice('deleted', $key);
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
        case 'voucher':
            include_once 'class.Voucher.php';

            $object = new Voucher($key);
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

        case 'warehouse':
            include_once 'class.Warehouse.php';
            $object = new Warehouse($key);
            break;
        case 'warehouse_area':
        case 'warehousearea':
        case 'warehouse area':
            include_once 'class.WarehouseArea.php';
            /** @var WarehouseArea */
            $object = new WarehouseArea($key);
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
        case 'operative':
            include_once 'class.Operative.php';
            $object = new Operative($key);
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
        case 'payment service provider':
            require_once "class.Payment_Service_Provider.php";
            $object = new Payment_Service_Provider($key);
            break;
        case 'payment_account':
        case 'payment account':
            require_once "class.Payment_Account.php";
            $object = new Payment_Account($key);
            break;

        case 'store_payment_account':
            $tmp = preg_split('/\_/', $key);
            require_once "class.Payment_Account.php";
            $object = new Payment_Account($tmp[1]);
            break;

        case 'payment_account-block':
        case 'payment account-block':
            require_once "class.Payment_Account.php";
            $object = new Payment_Account('block', $key);
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
        case 'production_part':
        case 'production part':
            require_once "class.ProductionPart.php";
            $object = new ProductionPart($key);
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
        case 'campaign_code-store_key':
            require_once "class.DealCampaign.php";
            $keys = preg_split('/\|/', $key);
            $object = new DealCampaign('code_store', $keys[0], $keys[1]);
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
        case 'client_order':
            require_once "class.PurchaseOrder.php";
            $object = new PurchaseOrder($key);
            break;
        case 'agent_supplier_order':
        case 'agentsupplierpurchaseorder':
        case 'agent_supplier_purchase_order':
            require_once "class.Agent_Supplier_Purchase_Order.php";
            $object = new AgentSupplierPurchaseOrder($key);
            break;
        case 'upload':
            require_once "class.Upload.php";
            $object = new Upload($key);
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
        case 'supplier_delivery':
            require_once "class.SupplierDelivery.php";
            $object = new SupplierDelivery($key);
            $object->get_order_data();
            break;
        case 'material':
            require_once "class.Material.php";
            $object = new Material($key);
            break;

        case 'supplier_production':
        case 'supplierproduction':
        case 'supplier production':

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
        case 'emailblueprint':

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

        case 'tax_category':
        case 'tax category':
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
        case 'shipping zone':
            require_once "class.Shipping_Zone.php";
            $object = new Shipping_Zone($key);
            break;
        case 'shipping_zone_schema':
        case 'shippingzoneschema':
        case 'shipping zone schema':
            require_once "class.Shipping_Zone_Schema.php";
            $object = new Shipping_Zone_Schema($key);
            break;

        case 'shipping_option':
        case 'shippingoption':
            require_once "class.Shipping_Option.php";
            $object = new Shipping_Option($key);
            break;
        case 'email_campaign':
        case 'emailcampaign':
        case 'email campaign':
        case 'mailshot':
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
        case 'email_template_type':
        case 'email campaign type':
        case 'email template type':
        case 'emailtemplatetype':

            require_once "class.EmailCampaignType.php";
            $object = new EmailCampaignType($key);
            break;


        case 'email_template_type-code_store':
            include_once 'class.EmailCampaignType.php';

            $keys = preg_split('/\|/', $key);

            $object = new EmailCampaignType('code_store', $keys[0], $keys[1]);
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
        case 'shipper':
            include_once 'class.Shipper.php';
            $object = new Shipper('id', $key);
            break;
        case 'sales_representative':
        case 'salesrepresentative':
        case 'prospect_agent':
            include_once 'class.Sales_Representative.php';
            $object = new Sales_Representative('id', $key);
            break;
        case 'purge':
        case 'order_basket_purge':
        case 'order basket purge':
            include_once 'class.Order_Basket_Purge.php';
            $object = new Order_Basket_Purge('id', $key);
            break;
        case 'data_set':
        case 'data_sets':
            include_once('class.Data_Sets.php');
            $object = new Data_Sets('id', $key);
            break;
        case 'data_sets-code':
            include_once('class.Data_Sets.php');
            $object = new Data_Sets('code', $key);
            break;
        case 'customer_client':
        case 'customer client':
        case 'customerclient':
            include_once 'class.Customer_Client.php';
            $object = new Customer_Client('id', $key);
            break;
        case 'clocking_machine':
            include_once 'class.Clocking_Machine.php';
            $object = new Clocking_Machine('id', $key);
            break;
        case 'clocking_machine_nfc_tag':
            include_once 'class.Clocking_Machine_NFC_Tag.php';
            $object = new Clocking_Machine_NFC_Tag('id', $key);
            break;
        case 'raw material':
        case 'raw_material':
        case 'rawmaterial':

            include_once 'class.Raw_Material.php';
            $object = new Raw_Material('id', $key);
            break;
        case 'consignment':
            include_once 'class.Consignment.php';
            $object = new Consignment('id', $key);
            break;
        case 'external invoicer':
        case 'external_invoicer':
            include_once 'class.External_Invoicer.php';
            $object = new External_Invoicer('id', $key);
            break;
        case 'picking_band':
        case 'picking_band':
        case 'pickingband':
            include_once 'class.PickingBand.php';
            $object = new PickingBand('id', $key);
            break;
        case 'customer_fulfilment':
        case 'customer fulfilment':
            include_once 'class.Customer_Fulfilment.php';
            $object = new Customer_Fulfilment('id', $key);
            break;

        case 'fulfilment_delivery':
        case 'fulfilment delivery':
            include_once 'class.Fulfilment_Delivery.php';
            $object = new Fulfilment_Delivery('id', $key);
            break;
        case 'customer_part':
        case 'customer part':
            require_once "class.Customer_Part.php";
            $object = new Customer_Part($key);
            break;
        case 'fulfilment asset':
        case 'fulfilment_asset':
            require_once "class.Fulfilment_Asset.php";
            $object = new Fulfilment_Asset($key);
            break;
        default:
            exit('need to complete E1: x>>>>|'.strtolower($object_name).'|<<<<++>>'.$load_other_data."<\n");
    }


    return $object;


}


