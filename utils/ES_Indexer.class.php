<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  01 December 2019  17:34::02  +0100, Mijas Costa, Spain

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

use Elasticsearch\ClientBuilder;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

require_once 'vendor/autoload.php';


class ES_indexer {
    /**
     * @var \PDO
     */
    public $db;
    /**
     * @var  ClientBuilder
     */
    var $client;
    /**
     * @var  \Customer
     */
    var $object;
    /**
     * @var  string
     */
    var $account_code;
    /**
     * @var  float
     */
    var $weight;
    /**
     * @var  string
     */
    var $status;
    /**
     * @var  array
     */
    var $real_time;
    /**
     * @var string
     */
    private $url;
    /**
     * @var string
     */
    private $module;
    /**
     * @var array
     */
    private $scopes;
    /**
     * @var string
     */
    private $icon_classes;
    /**
     * @var string
     */
    private $label_1;
    /**
     * @var string
     */
    private $label_3;
    /**
     * @var string
     */
    private $label_2;
    /**
     * @var string
     */
    private $label_4;
    /**
     * @var array
     */
    private $primary;
    /**
     * @var array
     */
    private $secondary;
    /**
     * @var array
     */
    private $alias;
    /**
     * @var string
     */
    private $code;
    /**
     * @var string
     */
    private $operation;
    /**
     * @var string
     */
    private $prefix;
    /**
     * @var string
     */
    private $store_key;
    /**
     * @var array
     */
    private $indices;

    function __construct($hosts, $account_code, $object, $db, $indices) {
        $this->client       = ClientBuilder::create()->setHosts($hosts)->build();
        $this->indices      = $indices;
        $this->object       = $object;
        $this->db           = $db;
        $this->account_code = strtolower($account_code);
        $this->primary      = array();
        $this->secondary    = array();
        $this->alias        = array();
        $this->weight       = 50;
        $this->status       = '';
        $this->real_time    = array();
        $this->module       = '';
        $this->scopes       = array();
        $this->icon_classes = '';
        $this->label_1      = '';
        $this->label_2      = '';
        $this->label_3      = '';
        $this->label_4      = '';

        $this->code = '';

        $this->prefix    = '';
        $this->store_key = '';

        $this->skip_add_index = false;

    }


    public function prepare_object() {

        switch ($this->object->get_object_name()) {
            case 'Customer':
                $this->prefix            = 'c';
                $this->object_index_name = 'customers';
                $this->prepare_customer();
                break;
            case 'Prospect':
                $this->prefix = 'cp';
                $this->prepare_prospect();
                break;
            case 'Order':
                $this->prefix = 'o';
                $this->prepare_order();
                break;
            case 'Part':
                $this->prefix = 'sko';
                $this->prepare_part();
                break;
            case 'Location':
                $this->prefix = 'loc';
                $this->prepare_location();
                break;
            case 'Product':
                $this->prefix = 'p';
                $this->prepare_product();
                break;
            case 'Category':
                switch ($this->object->get('Category Scope')) {
                    case 'Product':

                        if ($this->object->get('Category Branch Type') == 'Head') {
                            $this->prefix = 'cat_p';
                            $this->prepare_product_category();
                        } else {

                            $this->skip_add_index = true;
                        }
                        break;
                    case 'Part':

                        if ($this->object->get('Category Branch Type') == 'Head') {
                            $this->prefix = 'cat_sko';
                            $this->prepare_part_category();
                        } else {
                            $this->skip_add_index = true;
                        }
                        break;
                    default:
                        $this->skip_add_index = true;
                        break;
                }

                break;

            case 'Page':
                $this->prefix = 'wp';
                $this->prepare_webpage();
                break;
            case 'Supplier':
                $this->prefix = 'sup';
                $this->prepare_supplier();
                break;
            case 'Agent':
                $this->prefix = 'ag';
                $this->prepare_agent();
                break;
            case 'Supplier Part':
                $this->prefix = 'sp';
                $this->prepare_supplier_part();
                break;
            case 'Staff':
                $this->prefix = 's';
                $this->prepare_staff();
                break;
            case 'User':
                $this->prefix = 'u';
                $this->prepare_user();
                break;
            case 'Invoice':
                $this->prefix = 'i';
                $this->prepare_invoice();
                break;
            case 'Delivery Note':
                $this->prefix = 'dn';
                $this->prepare_delivery_note();
                break;
            case 'Payment':
                $this->prefix = 'pay';
                $this->prepare_payment();
                break;
            case 'List':
                $this->prefix = 'li';
                $this->prepare_list();
                break;
            case 'Deal':
                $this->prefix = 'd';
                $this->prepare_deal();
                break;
            case 'Deal Component':
                $this->prefix = 'dc';
                $this->prepare_deal_component();
                break;
            case 'Deal Campaign':
                $this->prefix = 'dcc';
                $this->prepare_deal_campaign();
                break;
            case 'Email Campaign':
                $this->prefix = 'm';
                $this->prepare_mailshot();
                break;
        }
    }

    private function prepare_customer() {

        $this->module    = 'customers';
        $this->store_key = $this->object->get('Store Key');

        $this->scopes = array(
            'customers' => 100
        );

        //$this->label = $this->object->get_formatted_id().' '.$this->object->get('Name').' '.$this->object->get('Location');
        $this->url = sprintf('customers/%d/%d', $this->object->get('Customer Store Key'), $this->object->id);

        $this->real_time[] = $this->object->id;
        $this->real_time[] = $this->object->get('Name');
        $this->real_time[] = $this->object->get('Customer Tax Number');
        $this->real_time[] = $this->object->get('Customer Main Plain Email');
        $this->real_time[] = $this->object->get('Customer Main Contact Name');

        $this->label_1 = $this->object->get_formatted_id('', 6);
        $this->label_2 = $this->object->get('Name');
        $this->label_3 = $this->object->get('Customer Main Plain Email');
        $this->label_4 = $this->object->get('Location');


        if (in_array(
            $this->object->get('Customer Contact Address Country 2 Alpha Code'), array(
                                                                                   'GB',
                                                                                   'IM',
                                                                                   'JE',
                                                                                   'GG',
                                                                                   'GI',
                                                                                   'CA'
                                                                               )
        )) {
            $this->real_time[] = $this->object->get('Customer Contact Address Postal Code');
            $this->real_time[] = preg_replace('/\s/','',$this->object->get('Customer Contact Address Postal Code'));

            $this->label_4     .= ' '.$this->object->get('Customer Contact Address Postal Code');
        }


        $this->status = $this->object->get('Customer Type by Activity');


        //'Rejected','ToApprove','Active','Losing','Lost'
        switch ($this->object->get('Customer Type by Activity')) {
            case 'Rejected':
                $this->icon_classes = 'far fa-fw fa-user-times error';

                $this->weight = 10;
                break;
            case 'ToApprove':
                $this->icon_classes = 'far fa-fw fa-user | fa fa-fw fa-circle-notch purple opacity_50';

                $this->weight = 70;
                break;
            case 'Active':
                $this->icon_classes = 'far fa-fw fa-user| fa fa-fw fa-circle green opacity_50';
                $this->weight       = 70;
                break;
            case 'Losing':
                $this->icon_classes = 'far fa-fw fa-user | fa fa-fw fa-circle yellow opacity_50';
                $this->weight       = 40;
                break;
            case 'Lost':
                $this->icon_classes = 'far fa-fw fa-user | fa fa-fw fa-circle red opacity_50';
                $this->weight       = 30;
                break;
        }

        if (in_array('favourites', $this->indices)) {
            $this->object_data['favourites'] = [];

            $sql  = "select P.`Product Code` from `Customer Favourite Product Fact` B left join `Product Dimension` P on (P.`Product ID`=`Customer Favourite Product Product ID`) where `Customer Favourite Product Customer Key`=?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(
                array(
                    $this->object->id
                )
            );
            while ($row = $stmt->fetch()) {
                $this->object_data['favourites'][] = $row['Product Code'];
            }
        }

        $interval = array();
        if (in_array('assets', $this->indices)) {
            $interval[] = array(
                'suffix'    => '',
                'sql_where' => ''
            );
        }
        if (in_array('assets_interval', $this->indices)) {
            $interval[] = array(
                'suffix'    => '_1y',
                'sql_where' => ' and `Invoice Date`>DATE_SUB(NOW(),INTERVAL 1 YEAR) '
            );
            $interval[] = array(
                'suffix'    => '_1q',
                'sql_where' => ' and `Invoice Date`>DATE_SUB(NOW(),INTERVAL 1 Quarter)'
            );
        }

        foreach ($interval as $period_data) {

            $products    = [];
            $families    = [];
            $departments = [];
            $sql         = "select P.`Product Code`,F.`Category Code` as fam ,D.`Category Code` as dept from `Order Transaction Fact` OTD 
                        left join `Product Dimension` P on (OTD.`Product ID`=P.`Product ID`) 
                        left join `Category Dimension` F on (OTD.`OTF Category Family Key`=F.`Category Key`) 
                        left join `Category Dimension` D on (OTD.`OTF Category Department Key`=D.`Category Key`) 
                        where `Customer Key`=? and  `Invoice Key` >0 ".$period_data['sql_where'];
            $stmt        = $this->db->prepare($sql);
            $stmt->execute([$this->object->id]);
            while ($row = $stmt->fetch()) {
                $products[] = $row['Product Code'];
                if ($row['fam'] != '') {
                    $families[] = $row['fam'];
                }
                if ($row['dept'] != '') {
                    $departments[] = $row['dept'];
                }

            }


            $this->object_data['products_bought'.$period_data['sql_where']] = array_keys(array_flip($products));

            $this->object_data['families_bought'.$period_data['sql_where']] = array_keys(array_flip($families));

            $this->object_data['departments_bought'.$period_data['sql_where']] = array_keys(array_flip($departments));


            unset($products);
            unset($families);
            unset($departments);
        }


        /*
        $this->primary[] = $this->object->id;

        $this->primary[] = $this->object->get('Name');
        $this->primary[] = $this->object->get('Main Contact Name');
        $this->primary[] = $this->object->get('Customer Main Plain Email');

        foreach ($this->object->get_other_emails_data() as $other_mail_data) {
            $this->primary[] = $other_mail_data['email'];
        }


        $this->primary[] = $this->object->get('Customer Tax Number');
        $this->primary[] = $this->object->get('Customer Registration Number');
        $this->primary[] = $this->object->get('Customer Website');


        list($address_tokens, $address_aux) = $this->tokenize_address('Contact');
        $this->primary = array_merge($this->primary, $address_tokens);
        $this->alias   = array_merge($this->alias, $address_aux);

        list($address_tokens, $address_aux) = $this->tokenize_address('Invoice');
        $this->primary = array_merge($this->primary, $address_tokens);
        $this->alias   = array_merge($this->alias, $address_aux);

        list($address_tokens, $address_aux) = $this->tokenize_address('Delivery');
        $this->primary = array_merge($this->primary, $address_tokens);
        $this->alias   = array_merge($this->alias, $address_aux);

        list($tel_tokens, $tel_aux) = $this->tokenize_telephone_number($this->object->get('Main XHTML Mobile'), $this->object->get('Customer Contact Address Country 2 Alpha Code'));
        $this->primary = array_merge($this->primary, $tel_tokens);
        $this->alias   = array_merge($this->alias, $tel_aux);

        list($tel_tokens, $tel_aux) = $this->tokenize_telephone_number($this->object->get('Main XHTML Telephone'), $this->object->get('Customer Contact Address Country 2 Alpha Code'));
        $this->primary   = array_merge($this->primary, $tel_tokens);
        $this->alias     = array_merge($this->alias, $tel_aux);
        $this->primary[] = $this->object->get('Sticky Note');
        $this->secondary = array_diff($this->secondary, $this->primary);

        $sql  = "select `History Details`,`History Abstract` from `Customer History Bridge` B left join `History Dimension` H  on (B.`History Key`=H.`History Key`) where    B.`Customer Key`=? and `Type`='Notes'  ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->object->id
            )
        );

        while ($row = $stmt->fetch()) {
            $this->secondary[] = $row['History Details'];
            $this->secondary[] = $row['History Abstract'];
        }

        $this->remove_duplicated_tokens();
        */


    }

    private function prepare_prospect() {

        $this->module    = 'customers';
        $this->store_key = $this->object->get('Store Key');

        $this->scopes = array(
            'prospects' => 100,
            'customers' => 10
        );

        // $this->label = $this->object->get('Name').' '.$this->object->get('Location');
        $this->url = sprintf('prospects/%d/%d', $this->object->get('Prospect Store Key'), $this->object->id);

        $this->real_time[] = $this->object->get('Name');
        $this->real_time[] = $this->object->get('Prospect Tax Number');
        $this->real_time[] = $this->object->get('Prospect Main Plain Email');
        $this->real_time[] = $this->object->get('Prospect Main Contact Name');

        $this->label_1 = '';
        $this->label_2 = $this->object->get('Name');
        $this->label_3 = $this->object->get('Prospect Main Plain Email');
        $this->label_4 = $this->object->get('Location');


        if (in_array(
            $this->object->get('Prospect Contact Address Country 2 Alpha Code'), array(
                                                                                   'GB',
                                                                                   'IM',
                                                                                   'JE',
                                                                                   'GG',
                                                                                   'GI',
                                                                                   'CA'
                                                                               )
        )) {
            $this->real_time[] = $this->object->get('Prospect Contact Address Postal Code');
            $this->label_4     .= ' '.$this->object->get('Customer Contact Address Postal Code');
        }

        $this->status = $this->object->get('Prospect Status');


        //''NoContacted','Contacted','NotInterested','Registered','Invoiced','Bounced'
        switch ($this->object->get('Prospect Status')) {
            case 'NoContacted':
                $this->icon_classes = 'fal fa-fw fa-user-alien discreet |  fa fa-fw fa-circle-notch purple opacity_50';

                $this->weight = 25;
                break;
            case 'Contacted':
                $this->icon_classes = 'fal fa-fw fa-user-alien discreet|  fa fa-fw fa-circle purple opacity_50';

                $this->weight = 20;
                break;
            case 'NotInterested':
                $this->icon_classes = 'fal fa-fw fa-user-alien discreet|fa  fa-fw fa-circle red opacity_50 ';
                $this->weight       = 20;
                break;
            case 'Registered':
                $this->icon_classes = 'fal fa-fw fa-user-alien discreet| fa  fa-fw fa-circle green opacity_50';
                $this->weight       = 10;
                break;
            case 'Invoiced':
                $this->icon_classes = 'fal fa-fw fa-user-alien discreet | fa  fa-fw fa-circle green opacity_50';
                $this->weight       = 10;
                break;
            case 'Bounced':
                $this->icon_classes = 'fal fa-fw fa-user-alien discreet| fa  fa-fw fa-exclamation-circle red opacity_50';
                $this->weight       = 15;
                break;
        }

        /*
                $this->primary[] = $this->object->id;

                $this->primary[] = $this->object->get('Name');
                $this->primary[] = $this->object->get('Main Contact Name');
                $this->primary[] = $this->object->get('Prospect Main Plain Email');


                list($address_tokens, $address_aux) = $this->tokenize_address('Contact');
                $this->primary = array_merge($this->primary, $address_tokens);
                $this->alias   = array_merge($this->alias, $address_aux);


                list($tel_tokens, $tel_aux) = $this->tokenize_telephone_number($this->object->get('Main XHTML Mobile'), $this->object->get('Prospect Contact Address Country 2 Alpha Code'));
                $this->primary = array_merge($this->primary, $tel_tokens);
                $this->alias   = array_merge($this->alias, $tel_aux);

                list($tel_tokens, $tel_aux) = $this->tokenize_telephone_number($this->object->get('Main XHTML Telephone'), $this->object->get('Prospect Contact Address Country 2 Alpha Code'));
                $this->primary   = array_merge($this->primary, $tel_tokens);
                $this->alias     = array_merge($this->alias, $tel_aux);
                $this->primary[] = $this->object->get('Sticky Note');
                $this->secondary = array_diff($this->secondary, $this->primary);

                $sql  = "select `History Details`,`History Abstract` from `Prospect History Bridge` B left join `History Dimension` H  on (B.`History Key`=H.`History Key`) where    B.`Prospect Key`=? and `Type`='Notes'  ";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(
                    array(
                        $this->object->id
                    )
                );

                while ($row = $stmt->fetch()) {
                    $this->secondary[] = $row['History Details'];
                    $this->secondary[] = $row['History Abstract'];
                }

                $this->remove_duplicated_tokens();
        */

    }

    private function prepare_order() {


        $this->module    = 'orders';
        $this->store_key = $this->object->get('Store Key');

        $this->code = $this->object->get('Order Public ID');

        $this->real_time[] = $this->object->get('Order Public ID');
        $number_only_id    = trim(preg_replace('/[^0-9]/', ' ', $this->object->get('Order Public ID')));
        $this->real_time[] = $number_only_id;
        $this->real_time[] = (int)$number_only_id;


        $this->real_time[] = $this->object->get('Order Customer Purchase Order ID');

        //'Ready to be Picked','Picker Assigned','Picking','Picked','Packing','Packed','Packed Done','Approved','Dispatched','Cancelled','Cancelled to Restock'

        $sql  = "select `Delivery Note ID` ,`Delivery Note Key`,`Delivery Note Type`,`Delivery Note State` from `Delivery Note Dimension` where `Delivery Note Order Key`=? and `Delivery Note State`!='Cancelled' ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->object->id
            )
        );

        $delivery_notes = '';
        while ($row = $stmt->fetch()) {

            if ($row['Delivery Note ID'] == $this->object->get('Public ID')) {
                continue;
            }


            if ($row['Delivery Note State'] == 'Dispatched') {
                $icon = 'fal fa-fw fa-truck';
            } else {
                $icon = 'fal fa-fw fa-clipboard-list-check';
            }


            $this->real_time[] = $row['Delivery Note ID'];

            $number_only_id    = trim(preg_replace('/[^0-9]/', ' ', $row['Delivery Note ID']));
            $this->real_time[] = $number_only_id;
            $this->real_time[] = (int)$number_only_id;

            $delivery_notes .= ', <span class="'.(in_array(
                    $row['Delivery Note Type'], array(
                                                  'Replacement & Shortages',
                                                  'Replacement',
                                                  'Shortages'
                                              )
                ) ? 'error' : '').'"><i class="'.$icon.'"></i> '.$row['Delivery Note ID'].'</span>';

        }
        $delivery_notes = preg_replace('/^, /', '', $delivery_notes);


        $invoices = '';
        $sql      = "select `Invoice Public ID`,`Invoice Key`,`Invoice Type`  from `Invoice Dimension` where `Invoice Order Key`=?";
        $stmt     = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->object->id
            )
        );
        while ($row = $stmt->fetch()) {

            if ($row['Invoice Public ID'] == $this->object->get('Public ID')) {
                continue;
            }

            $this->real_time[] = $row['Invoice Public ID'];
            $number_only_id    = trim(preg_replace('/[^0-9]/', ' ', $row['Invoice Public ID']));
            $this->real_time[] = $number_only_id;
            $this->real_time[] = (int)$number_only_id;

            $invoices .= ', <span class="'.($row['Invoice Type'] == 'Refund' ? 'error' : '').'"><i class="fal fa-fw fa-file-invoice"></i> '.$row['Invoice Public ID'].'</span>';

        }
        $invoices = preg_replace('/^, /', '', $invoices);

        if ($invoices != '' and $delivery_notes != '') {
            $invoices = '<span class="padding_right_10 ">'.$invoices.'</span>';
        }

        $this->icon_classes = $this->object->get('Icon');
        $this->label_1      = $this->object->get('Public ID');
        $this->label_2      = $this->object->get('Order Customer Name');
        $this->label_3      = $this->object->get('Total Amount');
        $this->label_4      = trim($invoices.' '.$delivery_notes);


        switch ($this->object->get('Order State')) {
            case 'InBasket':
                $this->weight = 25;
                break;

            case 'PackedDone':
            case 'InProcess':
                $this->weight = 80;
                break;

            case 'InWarehouse':
                $this->weight = 40;

                break;

            case 'Approved':
                $this->weight = 100;
                break;

            case 'Dispatched':
                $this->weight = 50;
                break;

            case 'Cancelled':
                $this->weight = 10;
                break;

            default:
                $this->weight = 50;
        }


        $this->url = sprintf('orders/%d/%d', $this->object->get('Order Store Key'), $this->object->id);


        /*

           $amount = "(".$this->object->get('Total Amount');
        if ($this->object->get('Order Currency Exchange') != 1) {
            $amount .= ', '.$this->object->get('DC Total Amount');
        }
        $amount .= ') ';
        $this->status = $this->object->get('Order State');

        $this->label = $this->object->get('Public ID')." $amount".$this->object->get('State');

        $this->primary[] = $this->object->get('Public ID');

        $this->secondary[] = $this->object->get('Order Sticky Note');
        $this->secondary[] = $this->object->get('Order Cancel Note');
        $this->secondary[] = $this->object->get('Order Customer Sevices Note');


        list($address_tokens, $address_aux) = $this->tokenize_address('Invoice');
        $this->secondary = array_merge($this->secondary, $address_tokens);
        $this->alias     = array_merge($this->alias, $address_aux);

        list($address_tokens, $address_aux) = $this->tokenize_address('Delivery');
        $this->secondary = array_merge($this->secondary, $address_tokens);
        $this->alias     = array_merge($this->alias, $address_aux);

        $this->remove_duplicated_tokens();
        */


    }

    private function prepare_part() {

        $this->module = 'inventory';
        $this->url    = sprintf('part/%d', $this->object->id);

        $this->code = $this->object->get('Part Reference');

        $this->real_time[] = $this->object->get('Part Reference');
        $this->real_time[] = preg_replace('/-/', '_', $this->object->get('Part Reference'));


        //  $number_only_id    = trim(preg_replace('/[^0-9]/', ' ', $this->object->get('Part Reference')));
        //  $this->real_time[] = $number_only_id;
        //  $this->real_time[] = (int)$number_only_id;


        $this->real_time[] = $this->object->get('Part Package Description');
        $this->real_time[] = $this->object->get('Part SKO Barcode');
        $this->real_time[] = strip_tags($this->object->get('Materials'));


        $this->label_1 = $this->object->get('Reference');
        $this->label_2 = $this->object->get('Part Package Description');
        $this->label_3 = $this->object->get('Package Weight');


        switch ($this->object->get('Part Status')) {
            case 'Discontinuing':
                $this->weight       = 50;
                $this->icon_classes = 'fal fa-fw fa-box warning';
                break;
            case 'Not In Use':
                $this->weight       = 1;
                $this->icon_classes = 'fal fa-fw fa-box very_discreet red';
                break;
            case 'In Use':
                $this->weight       = 70;
                $this->icon_classes = 'fal fa-fw fa-box';
                break;
            case 'In Process':
                $this->weight       = 40;
                $this->icon_classes = 'fal fa-fw fa-box discreet';
                break;
            default:
                $this->icon_classes = 'fal fa-fw fa-question-circle';


        }


        /*
        $this->label  = $this->object->get('Part Reference').', '.$this->object->get('Part Package Description');
        $this->status = $this->object->get('Part Status');
        $this->primary[] = $this->object->get('Part Reference');
        $this->primary[] = $this->object->get('Part Package Description');
        $this->primary[] = $this->object->get('Part SKO Barcode');
        $this->secondary[] = strip_tags($this->object->get('Materials'));
        $this->remove_duplicated_tokens();
        */


    }

    private function prepare_location() {

        $this->module = 'warehouse';

        $this->scopes = array(
            'locations' => 100
        );

        $this->code = $this->object->get('Code');

        $this->real_time[] = $this->object->get('Code');
        //$number_only_id    = trim(preg_replace('/[^0-9]/', ' ', $this->object->get('Category Code')));
        //$this->real_time[] = $number_only_id;
        //$this->real_time[] = (int)$number_only_id;

        $this->label_1 = $this->object->get('Code');
        $this->label_2 = $this->object->get('Warehouse Area Code');

        $this->icon_classes = 'fal  fa-fw fa-pallet';
        $this->weight       = 60;
        $this->url          = sprintf('locations/%d/%d', $this->object->get('Location Warehouse Key'), $this->object->id);


    }

    private function prepare_product() {


        $this->module    = 'products';
        $this->store_key = $this->object->get('Store Key');

        $this->scopes = array(
            'products' => 100
        );

        $this->real_time[] = $this->object->get('Product Code');
        $this->real_time[] = preg_replace('/-/', '_', $this->object->get('Product Code'));
        //$number_only_id    = trim(preg_replace('/[^0-9]/', ' ', $this->object->get('Product Code')));
        //$this->real_time[] = $number_only_id;
        //$this->real_time[] = (int)$number_only_id;
        $this->real_time[] = $this->object->get('Product Name');

        $this->code = $this->object->get('Product Code');

        $this->label_1 = $this->object->get('Code');
        $this->label_2 = ($this->object->get('Units Per Case') != 1 ? $this->object->get('Units Per Case').'x ' : '').$this->object->get('Name');
        $this->label_3 = $this->object->get('Price');

        //'InProcess','Active','Suspended','Discontinuing','Discontinued'
        switch ($this->object->get('Product Status')) {
            case 'Discontinuing':
                $this->weight       = 50;
                $this->icon_classes = 'fa fa-fw fa-cube warning';
                break;
            case 'Discontinued':
                $this->weight       = 1;
                $this->icon_classes = 'fa fa-fw fa-cube very_discreet';
                break;
            case 'Suspended':
                $this->weight       = 40;
                $this->icon_classes = 'fa fa-fw fa-cube error';
                break;
            default:
                $this->weight       = 70;
                $this->icon_classes = 'fa fa-fw fa-cube';
                break;
        }

        //'Online Force Out of Stock','Online Auto','Offline','Online Force For Sale'
        switch ($this->object->get('Product Web Configuration')) {
            case 'Online Force Out of Stock':
                $this->icon_classes .= '|fa fa-fw fa-stop red';
                break;

            case 'Online Force For Sale':
                $this->icon_classes .= '|fa fa-fw fa-stop';

                switch ($this->object->get('Product Availability State')) {
                    case 'OnDemand':
                    case 'Normal':
                    case 'Excess':
                        $this->icon_classes .= ' green';
                        break;
                    case 'VeryLow':
                    case 'Error':
                    case 'OutofStock':
                    case 'Low':
                        $this->icon_classes .= ' yellow';
                        break;
                    default:
                        break;
                }

                break;
            case 'Online Auto':
                $this->icon_classes .= '|fa fa-fw fa-circle';

                switch ($this->object->get('Product Availability State')) {
                    case 'OnDemand':
                    case 'Normal':
                    case 'Excess':
                        $this->icon_classes .= ' green';
                        break;
                    case 'VeryLow':
                    case 'Low':
                        $this->icon_classes .= ' yellow';
                        break;
                    case 'Error':
                    case 'OutofStock':
                        $this->icon_classes .= ' red';
                        break;
                    default:
                        break;
                }

                break;

        }


        $this->url = sprintf('products/%d/%d', $this->object->get('Product Store Key'), $this->object->id);


    }

    private function prepare_product_category() {
        $this->module = 'products';

        $store           = get_object('Store', $this->object->get('Store Key'));
        $this->module    = 'products';
        $this->store_key = $store->id;

        $this->code = $this->object->get('Category Code');


        $this->real_time[] = $this->object->get('Category Code');
        //$number_only_id    = trim(preg_replace('/[^0-9]/', ' ', $this->object->get('Product Code')));
        //$this->real_time[] = $number_only_id;
        //$this->real_time[] = (int)$number_only_id;
        $this->real_time[] = $this->object->get('Category Label');
        $this->label_1     = $this->object->get('Code');
        $this->label_2     = $this->object->get('Label');

        if ($this->object->get('Category Root Key') == $store->get('Store Family Category Key') or $this->object->get('Category Root Key') == $store->get('Store Department Category Key')) {
            $this->icon_classes = 'fa yellow_main ';
        } else {
            $this->icon_classes = 'far discreet ';
        }

        if ($this->object->get('Category Subject') == 'Product') {
            $this->icon_classes .= 'fa-fw fa-folder-open';
        } else {
            $this->icon_classes .= 'fa-fw fa-folder-tree';
        }

        switch ($this->object->get('Product Category Status')) {
            case 'In Process':
                $this->weight = 80;
                break;

            case 'Active':
                $this->weight = 85;
                break;
            case 'Suspended':
                $this->weight       = 30;
                $this->icon_classes .= ' very_discreet red';

                break;
            case 'Discontinued':
                $this->weight       = 10;
                $this->icon_classes .= ' very_discreet warning';
                break;
            case 'Discontinuing':
                $this->weight       = 60;
                $this->icon_classes .= ' warning';
                break;

        }


        $this->url = sprintf('products/%d/category/%d', $this->object->get('Store Key'), $this->object->id);


    }

    private function prepare_part_category() {

        $this->module = 'inventory';

        $this->code = $this->object->get('Category Code');

        $this->real_time[] = $this->object->get('Category Code');

        //$number_only_id    = trim(preg_replace('/[^0-9]/', ' ', $this->object->get('Category Code')));
        //$this->real_time[] = $number_only_id;
        //$this->real_time[] = (int)$number_only_id;

        $this->real_time[] = $this->object->get('Category Label');
        $this->label_1     = $this->object->get('Code');
        if ($this->object->get('Code') != $this->object->get('Label')) {
            $this->label_2 = $this->object->get('Label');
        }


        $this->icon_classes = 'fal  fa-fw fa-boxes';


        switch ($this->object->get('Part Category Status')) {
            case 'InProcess':
                $this->weight       = 80;
                $this->icon_classes .= '| fal fa-fw fa-seeding very_discreet ';

                break;

            case 'InUse':
                $this->weight = 85;
                break;

            case 'NotInUse':
                $this->weight       = 10;
                $this->icon_classes .= ' very_discreet error';
                break;
            case 'Discontinuing':
                $this->weight       = 60;
                $this->icon_classes .= ' warning';
                break;

        }


        $this->url = sprintf('category/%d', $this->object->id);


    }

    private function prepare_webpage() {

        $this->module = 'websites';

        $this->scopes    = array(
            'webpages' => 100
        );
        $this->store_key = $this->object->get('Store Key');


        $this->url  = sprintf('website/%d/webpage/%d', $this->object->get('Webpage Website Key'), $this->object->id);
        $this->code = $this->object->get('Webpage Code');


        $this->real_time[] = $this->object->get('Webpage Code');
        $this->real_time[] = preg_replace('/-/', '_', $this->object->get('Webpage Code'));
        //$number_only_id    = trim(preg_replace('/[^0-9]/', ' ', $this->object->get('Webpage Code')));
        //$this->real_time[] = $number_only_id;
        //$this->real_time[] = (int)$number_only_id;

        $this->label_1 = strtolower($this->object->get('Code'));
        $this->label_2 = $this->object->get('Webpage Name');


        switch ($this->object->get('Webpage State')) {
            case 'InProcess':
                $this->weight       = 30;
                $this->icon_classes = 'discreet ';
                break;
            case 'Online':
                $this->weight       = 60;
                $this->icon_classes = 'success ';
                break;
            case 'Offline':
                $this->weight       = 10;
                $this->icon_classes = 'error ';
                break;
            case 'Ready':
                $this->weight       = 50;
                $this->icon_classes = 'discreet ';
                break;
        }

        switch ($this->object->get('Webpage Scope')) {
            case 'Category Categories':
                $category          = get_object('Category', $this->object->get('Webpage Scope Key'));
                $this->real_time[] = $category->get('Code');
                //$number_only_id     = trim(preg_replace('/[^0-9]/', ' ', $category->get('Code')));
                //$this->real_time[]  = $number_only_id;
                //$this->real_time[]  = (int)$number_only_id;
                $this->real_time[]  = $category->get('Label');
                $this->icon_classes .= 'far fa-fw fa-browser| fal fa-fw fa-folder-tree';
                break;
            case 'Category Products':
                $category          = get_object('Category', $this->object->get('Webpage Scope Key'));
                $this->real_time[] = $category->get('Code');
                //$number_only_id     = trim(preg_replace('/[^0-9]/', ' ', $category->get('Code')));
                //$this->real_time[]  = $number_only_id;
                //$this->real_time[]  = (int)$number_only_id;
                $this->real_time[]  = $category->get('Label');
                $this->icon_classes .= 'far fa-fw fa-browser| fal fa-fw fa-folder-open';
                break;
            case 'Product':
                $product           = get_object('Product', $this->object->get('Webpage Scope Key'));
                $this->real_time[] = $product->get('Code');
                //$number_only_id     = trim(preg_replace('/[^0-9]/', ' ', $product->get('Code')));
                //$this->real_time[]  = $number_only_id;
                //$this->real_time[]  = (int)$number_only_id;
                $this->real_time[]  = $product->get('Name');
                $this->icon_classes .= ' far fa-fw fa-browser| fal fa-fw fa-cube';
                break;

            default:
                $this->icon_classes .= ' far fa-fw fa-browser';
                break;
        }
        switch ($this->object->get('Webpage State')) {
            case 'InProcess':
                $this->weight = 30;
                break;
            case 'Online':
                $this->weight = 60;
                break;
            case 'Offline':
                $this->weight = 10;
                break;
            case 'Ready':
                $this->weight = 50;
                break;
        }

        /*
        $this->label  = $this->object->get('Webpage Code').', '.$this->object->get('URL');
        $this->status = $this->object->get('Webpage State');
        $this->primary[] = $this->object->get('Webpage Code');


        list($tokens, $aux) = $this->tokenize_url($this->object->get('URL'));
        $this->alias = array_merge($this->alias, $tokens);
        $this->alias = array_merge($this->alias, $aux);


        $content_data = $this->object->get('Content Data');
        if (isset($content_data['blocks'])) {
            foreach ($content_data['blocks'] as $block_key => $block) {
                switch ($block['type']) {
                    case 'text':
                        foreach ($block['text_blocks'] as $text_block) {
                            $this->secondary[] = html_entity_decode(strip_tags($text_block['text']));
                        }

                        break;
                    case 'product':
                        $this->secondary[] = html_entity_decode(strip_tags($block['text']));
                        break;
                    case 'blackboard':
                        foreach ($block['texts'] as $text_block) {
                            $this->secondary[] = html_entity_decode(strip_tags($text_block['text']));
                        }
                        foreach ($block['images'] as $images_block) {
                            $this->secondary[] = strip_tags($images_block['title']);
                        }
                        break;
                }


            }
        }

        $this->remove_duplicated_tokens();
        */

    }

    private function prepare_supplier() {

        if ($this->object->get('Supplier Production') == 'Yes') {
            $this->module = 'production';
        } else {
            $this->module = 'suppliers';
            $this->scopes = array(
                'suppliers' => 100
            );
        }
        $this->code = $this->object->get('Supplier Code');

        $this->url = sprintf('supplier/%d', $this->object->id);

        $this->real_time[] = $this->object->get('Supplier Code');
        $this->real_time[] = $this->object->get('Supplier Name');
        $this->real_time[] = $this->object->get('Supplier Nickname');
        $this->real_time[] = $this->object->get('Supplier Main Contact Name');
        $this->real_time[] = $this->object->get('Supplier Main Plain Email');
        $this->real_time[] = $this->object->get('Supplier Website');

        $this->label_1 = $this->object->get('Supplier Code');
        $this->label_2 = $this->object->get('Name');
        $this->label_3 = $this->object->get('Location');


        switch ($this->object->get('Supplier Type')) {
            case 'Free':
                $this->icon_classes = 'far fa-fw fa-hand-holding-box ';
                $this->weight       = 50;
                break;
            case 'Agent':
                $this->icon_classes = 'far fa-fw fa-hand-holding-box| fal fa-fw fa-user-agent small  discreet ';
                $this->weight       = 50;
                break;
            case 'Archived':
                $this->icon_classes = 'far fa-fw fa-hand-holding-box error super_discreet ';
                $this->weight       = 20;
                break;
        }


    }

    private function prepare_agent() {

        $this->module = 'suppliers';

        $this->scopes = array(
            'agents' => 100
        );

        $this->code = $this->object->get('Agent Code');
        $this->url  = sprintf('agent/%d', $this->object->id);

        $this->real_time[] = $this->object->get('Agent Code');
        $this->real_time[] = $this->object->get('Agent Name');
        $this->real_time[] = $this->object->get('Agent Nickname');
        $this->real_time[] = $this->object->get('Agent Main Contact Name');
        $this->real_time[] = $this->object->get('Agent Main Plain Email');
        $this->real_time[] = $this->object->get('Agent Website');

        $this->label_1      = $this->object->get('Agent Code');
        $this->label_2      = $this->object->get('Name');
        $this->label_3      = $this->object->get('Location');
        $this->icon_classes = 'far fa-fw fa-user-secret';
    }

    private function prepare_supplier_part() {

        if ($this->object->get('Supplier Part Production') == 'Yes') {
            $this->module = 'production';
            $this->scopes = array(
                'parts' => 100
            );
        } else {
            $this->module = 'suppliers';
            $this->scopes = array(
                'supplier_parts' => 100
            );
        }


        $this->url = sprintf('supplier/%d/part/%d', $this->object->get('Supplier Part Supplier Key'), $this->object->id);

        $this->real_time[] = $this->object->get('Supplier Part Reference');
        //$number_only_id    = trim(preg_replace('/[^0-9]/', ' ', $this->object->get('Supplier Part Reference')));
        //$this->real_time[] = $number_only_id;
        //$this->real_time[] = (int)$number_only_id;
        $this->real_time[] = $this->object->get('Supplier Part Description');

        $this->real_time[] = $this->object->part->get('Part Reference');
        //$number_only_id    = trim(preg_replace('/[^0-9]/', ' ', $this->object->part->get('Part Reference')));
        //$this->real_time[] = $number_only_id;
        //$this->real_time[] = (int)$number_only_id;
        $this->real_time[] = $this->object->part->get('Part Package Description');
        $this->real_time[] = $this->object->part->get('Part SKO Barcode');
        $this->real_time[] = strip_tags($this->object->part->get('Materials'));


        $this->label_1 = '<span class="padding_right_5">'.$this->object->get('Reference').'</span>';


        $this->label_2 = $this->object->get('Supplier Part Description');

        $supplier      = get_object('Supplier', $this->object->get('Supplier Part Supplier Key'));
        $this->label_3 = $supplier->get('Supplier Code');
        if ($supplier->get('Supplier Type') == 'Agent') {
            $this->label_3 .= ' <i class="fal small discreet fa-fw fa-user-secret"></i>';
        }


        switch ($this->object->get('Supplier Part Status')) {
            case 'Available':
                $this->weight       = 50;
                $this->icon_classes = 'fa fa-fw fa-hand-receiving success';
                break;
            case 'NoAvailable':
                $this->weight       = 30;
                $this->icon_classes = 'fa fa-fw fa-hand-receiving warning';
                break;
            case 'Discontinued':
                $this->weight       = 5;
                $this->icon_classes = 'fal fa-fw fa-hand-receiving error';
                break;
        }

        $this->label_1 .= '<span class="small">';

        switch ($this->object->part->get('Part Status')) {
            case 'Discontinuing':
                $this->weight  = $this->weight - 5;
                $this->label_1 .= ' <i class="small fal fa-fw fa-box warning"></i>';
                break;
            case 'Not In Use':
                $this->weight = $this->weight - 10;

                $this->label_1 .= ' <i class="small fal fa-fw fa-box very_discreet red"></i>';
                break;
            case 'In Use':
                $this->weight  = 40;
                $this->label_1 .= '<i class="small fal fa-fw fa-box\"></i>';
                break;
            case 'In Process':
                $this->weight  = 40;
                $this->label_1 .= ' <i class="small fal fa-fw fa-box discreet"></i>';
                break;
            default:
                $this->label_1 .= ' <i class="small fal fa-fw fa-question-circle"></i>';


        }

        if ($this->object->get('Reference') != $this->object->part->get('Part Reference')) {
            $this->label_1 .= ' '.$this->object->part->get('Part Reference');

        }
        $this->label_1 .= '</span>';


    }

    private function prepare_staff() {

        $this->module = 'hr';


        $this->real_time[] = $this->object->get('Staff ID');
        $this->real_time[] = $this->object->get('Staff Alias');
        $this->real_time[] = $this->object->get('Staff Name');
        $this->real_time[] = $this->object->get('Staff Official ID');
        $this->real_time[] = $this->object->get('Staff Email');


        //'Employee','Volunteer','Contractor','TemporalWorker','WorkExperience'

        if ($this->object->get('Staff Type') == 'Contractor') {
            $this->scopes       = array(
                'contractor' => 100
            );
            $this->icon_classes = 'far fa-fw fa-hand-spock';
            $this->url          = sprintf('/contractor//%d', $this->object->id);

        } else {
            $this->scopes       = array(
                'staff' => 100
            );
            $this->icon_classes = 'far fa-fw fa-hand-rock';
            $this->url          = sprintf('/employee//%d', $this->object->id);

        }


        if ($this->object->get('Staff Currently Working') == 'Yes') {
            $this->label_1 = $this->object->get('Staff Alias');

            $this->label_2 = $this->object->get('Name');

            $this->weight = 60;
        } else {
            $this->label_1 = '<span class="strikethrough discreet">'.$this->object->get('Alias').'</span>';

            $this->label_2 = '<span class="strikethrough discreet">'.$this->object->get('Name').'</span>';

            $this->weight       = 10;
            $this->icon_classes .= ' very_discreet';
        }

    }

    private function prepare_user() {

        $this->module = 'users';


        $this->real_time[] = $this->object->get('User Handle');

        $this->code = $this->object->get('User Handle');

        $staff = get_object('Staff', $this->object->get_staff_key());

        $this->real_time[] = $staff->get('Staff ID');
        $this->real_time[] = $staff->get('Staff Alias');
        $this->real_time[] = $staff->get('Staff Name');
        $this->real_time[] = $staff->get('Staff Email');


        //'Employee','Volunteer','Contractor','TemporalWorker','WorkExperience'


        $this->icon_classes = 'fal fa-fw fa-id-badge';
        $this->url          = sprintf('/users//%d', $this->object->id);


        switch ($this->object->get('User Type')) {
            case 'Staff':
                $this->label_2 = '<i class="fal fa-fw fa-user-headset padding_right_10"></i>';
                break;
            case 'Supplier':
                $this->label_2 = '<i class="fal  fa-fw fa-hand-holding-box padding_right_10"></i>';
                break;
            case 'Administrator':
                $this->label_2 = '<i class="fal fa-fw fa-user-cog padding_right_10"></i>';
                break;
            case 'Warehouse':
                $this->label_2 = '<i class="fal fa-fw fa-warehouse padding_right_10"></i>';
                break;
            case 'Contractor':
                $this->label_2 = '<i class="fal fa-fw fa-user-hard-hat padding_right_10"></i>';
                break;
            case 'Agent':
                $this->label_2 = '<i class="fal fa-fw fa-user-secret padding_right_10"></i>';
                break;
        }

        if ($this->object->get('User Active') == 'Yes') {
            $this->label_1 = $this->object->get('User Handle');

            $this->label_2 .= $this->object->get('User Alias');

            $this->weight = 60;
        } else {
            $this->label_1 = '<span class="strikethrough discreet">'.$this->object->get('User Handle').'</span>';

            $this->label_2 .= '<span class="strikethrough discreet">'.$this->object->get('User Alias').'</span>';

            $this->weight       = 10;
            $this->icon_classes .= ' very_discreet';
        }


    }

    private function prepare_invoice() {


        $this->module    = 'accounting';
        $this->store_key = $this->object->get('Store Key');


        $this->code = $this->object->get('Public ID');

        $this->real_time[] = $this->object->get('Public ID');
        $number_only_id    = trim(preg_replace('/[^0-9]/', ' ', $this->object->get('Public ID')));
        $this->real_time[] = $number_only_id;
        $this->real_time[] = (int)$number_only_id;


        $order             = get_object('Order', $this->object->get('Invoice Order Key'));
        $this->real_time[] = $order->get('Order Public ID');
        $number_only_id    = trim(preg_replace('/[^0-9]/', ' ', $order->get('Order Public ID')));
        $this->real_time[] = $number_only_id;
        $this->real_time[] = (int)$number_only_id;
        $this->real_time[] = $order->get('Order Customer Purchase Order ID');


        if ($this->object->deleted) {
            $this->scopes       = array(
                'deleted_invoices' => 100
            );
            $this->icon_classes = $this->object->get('Icon').'| fa fa-fw fa-trash-bin';
            $this->weight       = 10;
            $this->label_1      = '<span class="strikethrough discreet">'.$this->object->get('Public ID').'</span>';
            $this->label_2      = '<span class="strikethrough discreet">'.$this->object->get('Customer Name').'</span>';
            $this->label_3      = '<span class="strikethrough discreet">'.$this->object->get('Total Amount').'</span>';

        } else {
            $this->icon_classes = $this->object->get('Icon');
            $this->weight       = 60;
            $this->scopes       = array(
                'invoices' => 100
            );
            $this->label_1      = $this->object->get('Public ID');
            $this->label_2      = $this->object->get('Customer Name');
            $this->label_3      = $this->object->get('Total Amount');
        }


        if ($order->get('Order ID') != $this->object->get('Public ID')) {
            $this->label_4 = '<i class="fal fa-fw fa-shopping-cart padding_right_5"></i>'.$order->get('Public ID');
        }

        $this->url = sprintf('invoices/%d/%d', $this->object->get('Invoice Store Key'), $this->object->id);

    }

    private function prepare_delivery_note() {


        $this->module    = 'delivering';
        $this->store_key = $this->object->get('Store Key');

        $this->code = $this->object->get('ID');

        $this->real_time[] = $this->object->get('ID');
        $number_only_id    = trim(preg_replace('/[^0-9]/', ' ', $this->object->get('ID')));
        $this->real_time[] = $number_only_id;
        $this->real_time[] = (int)$number_only_id;


        $order             = get_object('Order', $this->object->get('Order Key'));
        $this->real_time[] = $order->get('Order Public ID');
        $number_only_id    = trim(preg_replace('/[^0-9]/', ' ', $order->get('Order Public ID')));
        $this->real_time[] = $number_only_id;
        $this->real_time[] = (int)$number_only_id;
        $this->real_time[] = $order->get('Order Customer Purchase Order ID');


        $this->icon_classes = $this->object->get('Icon');
        $this->weight       = 60;
        $this->scopes       = array(
            'delivery_notes' => 100
        );
        $this->label_1      = $this->object->get('ID');
        $this->label_2      = $this->object->get('Customer Name');
        $this->label_3      = $this->object->get('Weight');


        if ($order->get('Order ID') != $this->object->get('ID')) {
            $this->label_4 = '<i class="fal fa-fw fa-shopping-cart padding_right_5"></i>'.$order->get('Public ID');
        }

        $this->url = sprintf('delivery_notes/%d/%d', $this->object->get('Store Key'), $this->object->id);

    }

    private function prepare_payment() {


        $this->module    = 'accounting';
        $this->store_key = $this->object->get('Store Key');

        $this->scopes = array(
            'payments' => 100
        );

        $this->code = $this->object->get('Payment Transaction ID');


        $this->real_time[]  = $this->object->get('Payment Transaction ID');
        $this->icon_classes = $this->object->get('Icon');
        $this->label_1      = $this->object->get('Payment Transaction ID');
        $this->label_3      = $this->object->get('Transaction Amount');

        $this->label_4    = '';
        $orders_public_id = array();
        foreach ($this->object->get_orders('objects') as $order) {

            $this->label_4      .= ', <span><i class="padding_right_5 '.$order->get('Icon').'"></i> '.$order->get('Public ID').'</span>';
            $orders_public_id[] = $order->get('Public ID');


            foreach ($this->object->get_invoices('objects') as $invoice) {

                if (!in_array($invoice->get('Public ID'), $orders_public_id)) {
                    $this->label_4 .= ', <span><i class="padding_right_5 '.$invoice->get('Icon').'"></i> '.$invoice->get('Public ID').'</span>';

                }
            }

        }
        $this->label_4 = preg_replace('/^, /', '', $this->label_4);


        $this->url = sprintf('payments/%d/%d', $this->object->get('Payment Store Key'), $this->object->id);

    }

    private function prepare_list() {

        $this->module    = 'customers';
        $this->store_key = $this->object->get('Store Key');

        $this->scopes = array(
            'lists' => 100
        );

        $this->real_time[] = $this->object->get('List Name');

        $this->label_1      = $this->object->get('Name');
        $this->icon_classes = 'fal  fa-fw fa-list|'.$this->object->get('Icon');
        $this->weight       = 20;
        $this->url          = sprintf('customers/list/%d', $this->object->id);


    }

    private function prepare_deal() {


        $this->module    = 'offers';
        $this->store_key = $this->object->get('Store Key');

        $this->scopes = array(
            'deal' => 100
        );


        if ($this->object->get('Deal Number Components') == 1) {
            $this->real_time[] = $this->object->get('Deal Name');
            $this->real_time[] = $this->object->get('Deal Name Label');
            $this->real_time[] = $this->object->get('Deal Term Label');
            $this->real_time[] = $this->object->get('Deal Allowance Label');


            $deal_campaign = get_object('Deal Campaign', $this->object->get('Deal Campaign Key'));


            if (preg_match('/class=\"fa.?\s(.*)\"/', $deal_campaign->get('Icon'), $icon_match)) {
                $this->icon_classes = 'small discreet fal '.$icon_match[1].'|';

            } else {
                $this->icon_classes = 'small discreet fal tags invisible|';
            }
            $this->icon_classes .= 'fal fa-fw fa-tag';


            if (preg_match('/class=\"(.*)\"/', $this->object->get('Status Icon'), $icon_match)) {
                $this->icon_classes .= '|  '.$icon_match[1];

            }

            switch ($this->object->get('Deal Status')) {
                case 'Active':
                    $this->weight = 60;
                    break;
                case 'Suspended':
                    $this->weight = 50;
                    break;
                case 'Finish':
                    $this->weight = 5;
                    break;
                case 'Waiting':
                    $this->weight = 60;
                    break;
                default:
                    break;
            }


            $this->label_1 =
                '<span class="offer_text_banner no_border small"><span class="name">'.$this->object->get('Name').'</span> <span class="term">'.$this->object->get('Deal Term Label').'</span> <span class="allowance">'.$this->object->get('Deal Allowance Label').'</span>';
            $this->label_2 = $this->object->get('Deal Term Allowances Label');
            $this->label_3 = $deal_campaign->get('Icon');
            $this->url     = sprintf('deals/%d/%d', $this->object->get('Store Key'), $this->object->id);

        } else {
            $this->skip_add_index = true;
        }


    }

    private function prepare_deal_component() {


        $this->module    = 'offers';
        $this->store_key = $this->object->get('Store Key');

        $this->scopes = array(
            'deal' => 100
        );

        $deal = get_object('Deal', $this->object->get('Deal Component Deal Key'));

        $deal_campaign = get_object('Deal Campaign', $this->object->get('Deal Component Campaign Key'));


        $this->real_time[] = $deal->get('Deal Name');
        $this->real_time[] = $deal->get('Deal Name Label');
        $this->real_time[] = $deal->get('Deal Term Label');
        $this->real_time[] = $deal->get('Deal Allowance Label');
        $this->real_time[] = $deal->get('Deal Allowance Label');
        $this->real_time[] = strip_tags($this->object->get('Deal Component Term Allowances Label'));
        $this->real_time[] = $this->object->get('Deal Component Allowance Target Label');


        if (preg_match('/class=\"fa.?\s(.*)\"/', $deal_campaign->get('Icon'), $icon_match)) {
            $this->icon_classes = 'small discreet fal '.$icon_match[1].'|';
        } else {
            $this->icon_classes = 'small discreet fal tags invisible|';
        }
        $this->icon_classes .= 'fal fa-fw fa-tags';


        if (preg_match('/class=\"(.*)\"/', $this->object->get('Status Icon'), $icon_match)) {
            $this->icon_classes .= '|  '.$icon_match[1];

        }

        switch ($this->object->get('Deal Component Status')) {
            case 'Active':
                $this->weight = 60;
                break;
            case 'Suspended':
                $this->weight = 50;
                break;
            case 'Finish':
                $this->weight = 5;
                break;
            case 'Waiting':
                $this->weight = 60;
                break;
            default:
                break;
        }


        $this->label_1 =
            '<span class="offer_text_banner no_border small"><span class="name">'.$this->object->get('Name').'</span> <span class="term">'.$this->object->get('Deal Term Label').'</span> <span class="allowance">'.$this->object->get('Deal Component Allowance Label')
            .'</span>';
        $this->label_2 = $this->object->get('Deal Component Term Allowances Label');
        $this->label_3 = $deal_campaign->get('Icon');
        $this->url     = sprintf('offers/%d/%s/%d', $this->object->get('Store Key'), strtolower($deal_campaign->get('Code')), $this->object->id);


    }

    private function prepare_deal_campaign() {


        $this->module    = 'offers';
        $this->store_key = $this->object->get('Store Key');

        $this->scopes = array(
            'deal_campaign' => 100
        );


        $this->real_time[] = $this->object->get('Deal Campaign Code');
        $this->real_time[] = $this->object->get('Deal Campaign Name');


        if (preg_match('/class=\"fa.?\s(.*)\"/', $this->object->get('Icon'), $icon_match)) {
            $this->icon_classes = ' fal '.$icon_match[1];

        }
        $this->weight  = 50;
        $this->label_1 = $this->object->get('Deal Campaign Name');
        $this->url     = sprintf('offers/%d/%s/', $this->object->get('Store Key'), strtolower($this->object->get('Code')), $this->object->id);


    }

    private function prepare_mailshot() {


        if (in_array(
            $this->object->get('Email Campaign Type'), [
                                                         'Newsletter',
                                                         'Marketing'
                                                     ]
        )) {


            $this->module    = 'mailroom';
            $this->store_key = $this->object->get('Store Key');

            $this->scopes = array(
                'mailshot' => 100
            );

            $email_template = get_object('Email Template', $this->object->get('Email Campaign Email Template Key'));

            $this->real_time[] = $email_template->get('Email Template Subject');
            $this->real_time[] = $this->object->get('Email Campaign Name');


            switch ($this->object->get('Email Campaign Type')) {
                case 'Newsletter':
                    $this->icon_classes = 'fal fa-fw fa-newspaper|';
                    break;
                case 'Marketing':
                    $this->icon_classes = 'fal fa-fw fa-bullhorn |';
                    break;
            }
            $this->icon_classes .= $this->object->get('State Icon');

            $this->weight = 50;
            if ($this->object->get('Email Campaign Name') != $email_template->get('Email Template Subject')) {
                $this->label_1 = $this->object->get('Email Campaign Name');

            }
            $this->label_2 = $email_template->get('Email Template Subject');
            if ($this->object->get('State Index') <= 40) {
                $this->label_3 = date('Y-m-d', strtotime($this->object->get('Email Campaign Creation Date')));

            } else {
                $this->label_3 = date('Y-m-d', strtotime($this->object->get('Start Send Date')));

            }

            $this->url = sprintf('mailroom/%d/marketing/%d/mailshot/%d', $this->object->get('Store Key'), $email_template->id, $this->object->id);
        } else {
            $this->skip_add_index = true;
        }

    }

    public function delete_index() {


        $this->client->delete(
            [
                'index' => strtolower('au_q_search_'.$this->account_code),
                'id'    => $this->prefix.$this->object->id,
            ]
        );
        switch ($this->object->get_object_name()) {
            case 'Customer':
                $this->client->delete(
                    [
                        'index' => strtolower('au_customers_'.$this->account_code),
                        'id'    => $this->object->id,
                    ]
                );
                break;
        }


    }

    public function add_index() {

        $params = ['body' => []];


        if ($body = $this->get_index_body()) {

            foreach ($this->get_index_header() as $key => $index_header) {


                $params['body'][] = [
                    'index' => [
                        '_index' => $index_header['index'],
                        '_id'    => $index_header['id']
                    ]
                ];

                $params['body'][] = $body[$key];

            }

            if (count($params['body']) > 0) {
                $this->client->bulk($params);
            }
        }

    }

    public function get_index_body() {


        if ($this->skip_add_index) {
            return false;
        }

        $index_body = [];

        foreach ($this->indices as $index_type) {


            switch ($index_type) {
                case 'quick':

                    $body = array(
                        'rt'           => $this->flatten($this->real_time),
                        'url'          => $this->url,
                        'module'       => $this->module,
                        'weight'       => $this->weight,
                        'store_key'    => $this->store_key,
                        'store_label'  => $this->get_store_code($this->store_key),
                        'icon_classes' => $this->icon_classes,
                        'label_1'      => $this->label_1,
                        'label_2'      => $this->label_2,
                        'label_3'      => $this->label_3,
                        'label_4'      => $this->label_4,
                    );

                    if ($this->code != '') {
                        $body['code']    = $this->code;
                        $body['rt_code'] = $this->code;

                    }

                    if (count($this->scopes) > 0) {
                        $body['scopes'] = $this->scopes;
                    }
                    $body['tenant'] = $this->account_code;
                    $index_body[]   = $body;

                    switch ($this->object->get_object_name()) {
                        case 'Customer':
                            $body = array(
                                'url'         => $this->url,
                                'module'      => $this->module,
                                'weight'      => $this->weight,
                                'store_key'   => $this->store_key,
                                'store_label' => $this->get_store_code($this->store_key),

                            );

                            if ($this->code != '') {
                                $body['code'] = $this->code;
                            }

                            if (count($this->scopes) > 0) {
                                $body['scopes'] = $this->scopes;
                            }
                            $body['tenant'] = $this->account_code;
                            $index_body[]   = $body;
                            break;
                    }
                    break;
                case 'favourites':
                    $index_body[] = array(
                        'tenant'     => $this->account_code,
                        'favourites' => $this->get_object_data('favourites')
                    );
                    break;
                case 'assets':
                    $body[]                     = array(
                        'tenant' => $this->account_code,
                    );
                    $body['products_bought']    = $this->get_object_data('products_bought');
                    $body['families_bought']    = $this->get_object_data('families_bought');
                    $body['departments_bought'] = $this->get_object_data('departments_bought');

                    $index_body[] = $body;
                    break;
                case 'assets_interval':
                    $body[]                        = array(
                        'tenant' => $this->account_code,
                    );
                    $body['products_bought_1y']    = $this->get_object_data('products_bought_1y');
                    $body['products_bought_1q']    = $this->get_object_data('products_bought_1q');
                    $body['families_bought_1y']    = $this->get_object_data('families_bought_1y');
                    $body['families_bought_1q']    = $this->get_object_data('families_bought_1q');
                    $body['departments_bought_1y'] = $this->get_object_data('departments_bought_1y');
                    $body['departments_bought_1q'] = $this->get_object_data('departments_bought_1q');
                    $index_body[]                  = $body;
                    break;


                default:
                    break;
            }


        }

        return $index_body;


    }

    private function flatten($array) {
        if (count($array) == 0) {
            return '';
        }

        $string = join(' ', array_unique($array));

        $string = preg_replace('/\s\s+/', ' ', $string);
        $string = preg_replace('/\n/', ' ', $string);

        return $string;

    }

    private function get_store_code($store_key) {

        if (!is_numeric($store_key)) {
            return '';
        }

        $sql  = "select `Store Code` from `Store Dimension` where `Store Key`=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $store_key
            )
        );
        if ($row = $stmt->fetch()) {
            return $row['Store Code'];
        } else {
            return '';
        }

    }

    function get_object_data($key) {


        if (isset($this->object_data[$key])) {
            return $this->object_data[$key];
        } else {
            return '';
        }
    }

    public function get_index_header() {

        if ($this->skip_add_index) {
            return false;
        }

        $index_header = [];

        foreach ($this->indices as $index_type) {

            switch ($index_type) {
                case 'quick':
                    $index_header[] = [
                        'index' => strtolower('au_q_search_'.$this->account_code),
                        'id'    => $this->prefix.$this->object->id,
                    ];
                    switch ($this->object->get_object_name()) {
                        case 'Customer':
                            $index_header[] = [
                                'index' => strtolower('au_customers_'.$this->account_code),
                                'id'    => $this->object->id,
                            ];
                            break;
                    }
                    break;
                case 'favourites':
                case 'assets':
                case 'assets_interval':
                    $index_header[] = [
                        'index' => strtolower('au_customers_'.$this->account_code),
                        'id'    => $this->object->id,
                    ];
                    break;
                default:
                    break;
            }


        }

        return $index_header;
    }

    private function tokenize_address($type) {

        include_once 'class.Country.php';


        $tokens = array();
        $aux    = array();

        $tokens[] = $this->object->get($type.' Address Recipient');
        $tokens[] = $this->object->get($type.' Address Organization');
        $tokens[] = $this->object->get($type.' Address Line 1');
        $tokens[] = $this->object->get($type.' Address Line 2');
        $tokens[] = $this->object->get($type.' Address Sorting Code');
        $tokens[] = $this->object->get($type.' Address Postal Code');
        $tokens[] = preg_replace('/\s+/', '', $this->object->get($type.' Address Sorting Code'));
        $tokens[] = preg_replace('/\s+/', '', $this->object->get($type.' Address Postal Code'));

        $tokens[] = $this->object->get($type.' Address Recipient');
        $tokens[] = $this->object->get($type.' Address Dependent Locality');
        $tokens[] = $this->object->get($type.' Address Locality');
        $tokens[] = $this->object->get($type.' Address Administrative Area');

        $country  = new Country('2alpha', $this->object->get($type.' Address Country 2 Alpha Code'), $this->db);
        $tokens[] = $country->get('Country Name');
        $tokens[] = $country->get('Country Local Name');
        $tokens[] = $country->get('Country Official Name');

        foreach ($country->get_alias() as $county_alias) {
            $aux[] = $county_alias;
            if (strlen($county_alias) < 5) {
                $aux[] = preg_replace('/\s+/', '', $county_alias);
            }

        }

        return array(
            $tokens,
            $aux
        );
    }

    private function tokenize_telephone_number($number, $country) {

        $tokens = array();
        $aux    = array();
        if ($number == '') {
            return array(
                $tokens,
                $aux
            );
        }

        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $_number = $phoneUtil->parse($number, $country);

            $tokens[] = preg_replace('/[^0-9]/', '', $phoneUtil->format($_number, PhoneNumberFormat::E164));
            $tokens[] = preg_replace('/[^0-9]/', '', $phoneUtil->format($_number, PhoneNumberFormat::NATIONAL));


            $aux[] = $phoneUtil->format($_number, PhoneNumberFormat::NATIONAL);
            $aux[] = $phoneUtil->format($_number, PhoneNumberFormat::INTERNATIONAL);

            $aux[] = $phoneUtil->format($_number, PhoneNumberFormat::E164);

            $aux[] = preg_replace('/\s+/', '', $phoneUtil->format($_number, PhoneNumberFormat::E164));
            $aux[] = preg_replace('/\s+/', '', $phoneUtil->format($_number, PhoneNumberFormat::NATIONAL));
            $aux[] = preg_replace('/\s+/', '', $phoneUtil->format($_number, PhoneNumberFormat::INTERNATIONAL));

        } catch (NumberParseException $e) {
            return array(
                $tokens,
                $aux
            );
        }

        return array(
            $tokens,
            $aux
        );

    }

    private function remove_duplicated_tokens() {
        $this->secondary = array_diff($this->secondary, $this->primary);
        $this->alias     = array_diff($this->alias, $this->primary);
        $this->alias     = array_diff($this->alias, $this->secondary);

    }

    private function tokenize_url($url) {

        $tokens = array();
        $aux    = array();

        if ($url == '') {
            return array(
                $tokens,
                $aux
            );
        }


        $tokens[] = $url;
        $url      = preg_replace('/^https?:\/\//', '', $url);

        $aux[] = $url;
        $url   = preg_replace('/^www\./', '', $url);
        $aux[] = $url;

        return array(
            $tokens,
            $aux
        );
    }

}

