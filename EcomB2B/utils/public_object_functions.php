<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 July 2017 at 00:30:58 GMT+8, Cyebrjaya, Kuala Lumpur

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


    switch (strtolower($object_name.$load_other_data)) {
        case 'account':
            include_once 'class.Public_Account.php';
            $object = new Public_Account();
            break;
            break;
        case 'user':
        case 'website_user':
            include_once 'class.Public_Website_User.php';
            $object = new Public_Website_User('id', $key);
            break;
        case 'customer':
            include_once 'class.Public_Customer.php';
            $object = new Public_Customer('id', $key);
            break;
        case 'store':
            include_once 'class.Public_Store.php';
            $object = new Public_Store($key);
            break;
        case 'image':
            include_once 'class.Image.php';
            $object = new Image($key);
            break;
        case 'product':
        case 'service':
            include_once 'class.Public_Product.php';
            $object = new Public_Product('id', $key);
            break;


        case 'product-historic_key':
            include_once 'class.Public_Product.php';
            $object = new Public_Product('historic_key', $key);
            break;

        case 'order':
            include_once 'class.Public_Order.php';
            $object = new Public_Order($key);
            break;
        case 'invoice':
            include_once 'class.Public_Invoice.php';
            $object = new Public_Invoice($key);
            break;
        case 'delivery_note':
            include_once 'class.Public_Delivery_Note.php';
            $object = new Public_Delivery_Note($key);
            break;

        case 'website':
            include_once 'class.Public_Website.php';

            $object = new Public_Website($key);
            break;
        case 'webpage':
            include_once 'class.Public_Webpage.php';
            $object = new Public_Webpage($key);
            break;

        case 'email_template':

            require_once "class.Public_Email_Template.php";
            $object = new Public_Email_Template($key);


            break;
        case 'published_email_template':
            require_once "class.Public_Published_Email_Template.php";
            $object = new Public_Published_Email_Template($key);
            break;

        case 'payment_account':
            require_once "class.Public_Payment_Account.php";
            $object = new Public_Payment_Account($key);
            break;
        case 'payment':
            require_once "class.Public_Payment.php";
            $object = new Public_Payment($key);
            break;
        case 'tax_category':
            require_once "class.TaxCategory.php";
            $object = new TaxCategory($key);
            break;
        case 'tax_category-key':
            require_once "class.TaxCategory.php";
            $object = new TaxCategory('key', $key);
            break;
        case 'deal':
            require_once "class.Public_Deal.php";
            $object = new Public_Deal($key);
            break;
        case 'deal component':
        case 'dealcomponent':
            require_once "class.Public_DealComponent.php";
            $object = new Public_DealComponent($key);
            break;
        case 'customer_poll_query':
        case 'customer poll query':
        case 'customerpollquery':
            require_once "class.Public_Customer_Poll_Query.php";
            $object = new Customer_Poll_Query($key);
            break;
        case 'customer_poll_query_option':
        case 'customer poll query option':
        case 'customerpollqueryoption':
            require_once "class.Public_Customer_Poll_Query_Option.php";
            $object = new Customer_Poll_Query_Option($key);
            break;
        case 'email_template_type':
            require_once "class.EmailCampaignType.php";
            $object = new EmailCampaignType($key);
            break;
        case 'email_template_type-code_store':
            include_once 'class.EmailCampaignType.php';

            $keys = preg_split('/\|/', $key);

            $object = new EmailCampaignType('code_store', $keys[0], $keys[1]);
            break;
        case 'email_tracking':
            require_once "class.Email_Tracking.php";
            $object = new Email_Tracking($key);
            break;
        case 'charge':
            require_once "class.Public_Charge.php";
            $object = new Public_Charge($key);
            break;
        case 'public_webpage-scope_product':
            include_once 'class.Public_Webpage.php';
            $object = new  Public_Webpage('scope', 'Product', $key);
            break;
        default:
            exit('need to complete Pub  E1: >'.strtolower($object_name.' '.$load_other_data)."<\n");
            break;
    }


    return $object;


}


