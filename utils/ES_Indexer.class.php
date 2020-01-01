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

    function __construct($hosts, $account_code, $object, $db) {
        $this->client       = ClientBuilder::create()->setHosts($hosts)->build();
        $this->object       = $object;
        $this->db           = $db;
        $this->account_code = $account_code;
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

    }

    public function add_object() {

        switch ($this->object->get_object_name()) {
            case 'Customer':
                $this->add_customer();
                break;
            case 'Prospect':
                $this->add_prospect();
                break;
            case 'Order':
                $this->add_order();
                break;
            case 'Part':
                $this->add_part();
                break;
            case 'Page':
                $this->add_webpage();
                break;
        }
    }

    private function add_customer() {

        $this->module = 'customers';
        $this->scopes=array(
            'customers'=>100
        );

        $this->label = $this->object->get_formatted_id().' '.$this->object->get('Name').' '.$this->object->get('Location');
        $this->url   = sprintf('customers/%d/%d', $this->object->get('Customer Store Key'), $this->object->id);

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
            $this->label_4     .= ' '.$this->object->get('Customer Contact Address Postal Code');
        }


        $this->status = $this->object->get('Customer Type by Activity');


        //'Rejected','ToApprove','Active','Losing','Lost'
        switch ($this->object->get('Customer Type by Activity')) {
            case 'Rejected':
                $this->icon_classes = 'far fa-user-times error';

                $this->weight = 10;
                break;
            case 'ToApprove':
                $this->icon_classes = 'far fa-user | fa fa-circle-notch purple opacity_50';

                $this->weight = 70;
                break;
            case 'Active':
                $this->icon_classes = 'far fa-user| fa fa-circle green opacity_50';
                $this->weight       = 70;
                break;
            case 'Losing':
                $this->icon_classes = 'far fa-user | fa fa-circle yellow opacity_50';
                $this->weight       = 40;
                break;
            case 'Lost':
                $this->icon_classes = 'far fa-user | fa fa-circle red opacity_50';
                $this->weight       = 30;
                break;
        }


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
        $this->add_index('c', 'Customer', $this->object->get('Customer Store Key'));

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


    private function flatten($array) {
        if (count($array) == 0) {
            return '';
        }

        $string = join(' ', array_unique($array));

        $string = preg_replace('/\s\s+/', ' ', $string);
        $string = preg_replace('/\n/', ' ', $string);

        return $string;

    }

    private function add_prospect() {

        $this->module = 'customers';
        $this->scopes=array(
            'prospects'=>100,
            'customers'=>10
        );

       // $this->label = $this->object->get('Name').' '.$this->object->get('Location');
        $this->url   = sprintf('prospects/%d/%d', $this->object->get('Prospect Store Key'), $this->object->id);

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
                $this->icon_classes = 'fal fa-user-alien discreet |  fa fa-circle-notch purple opacity_50';

                $this->weight = 25;
                break;
            case 'Contacted':
                $this->icon_classes = 'fal fa-user-alien discreet|  fa fa-circle purple opacity_50';

                $this->weight = 20;
                break;
            case 'NotInterested':
                $this->icon_classes = 'fal fa-user-alien discreet|fa  fa-circle red opacity_50 ';
                $this->weight       = 20;
                break;
            case 'Registered':
                $this->icon_classes = 'fal fa-user-alien discreet| fa  fa-circle green opacity_50';
                $this->weight       = 10;
                break;
            case 'Invoiced':
                $this->icon_classes = 'fal fa-user-alien discreet | fa  fa-circle green opacity_50';
                $this->weight       = 10;
                break;
            case 'Bounced':
                $this->icon_classes = 'fal fa-user-alien discreet| fa  fa-exclamation-circle red opacity_50';
                $this->weight       = 15;
                break;
        }


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
        $this->add_index('cp', 'Prospect', $this->object->get('Prospect Store Key'));

    }

    private function add_order() {



        $this->module = 'orders';


        $this->real_time[] = $this->object->get('Order Public ID');
        $number_only_id=trim(preg_replace('/[^0-9]/',' ',$this->object->get('Order Public ID')));
        $this->real_time[] =  $number_only_id;
        $this->real_time[] =  (int) $number_only_id;




        $this->real_time[] = $this->object->get('Order Customer Purchase Order ID');

//'Ready to be Picked','Picker Assigned','Picking','Picked','Packing','Packed','Packed Done','Approved','Dispatched','Cancelled','Cancelled to Restock'

        $sql =  "select `Delivery Note ID` ,`Delivery Note Key`,`Delivery Note Type`,`Delivery Note State` from `Delivery Note Dimension` where `Delivery Note Order Key`=? and `Delivery Note State`!='Cancelled' ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->object->id
            )
        );

        $delivery_notes='';
        while ($row = $stmt->fetch()) {

            if($row['Delivery Note ID']==$this->object->get('Public ID')){
                continue;
            }


            if($row['Delivery Note State']=='Dispatched'){
                $icon='fal fa-truck';
            }else{
                $icon='fal fa-clipboard-list-check';
            }


            $this->real_time[] = $row['Delivery Note ID'];

            $number_only_id=trim(preg_replace('/[^0-9]/',' ',$row['Delivery Note ID']));
            $this->real_time[] =  $number_only_id;
            $this->real_time[] =  (int) $number_only_id;

            $delivery_notes.=', <span class="'.( in_array($row['Delivery Note Type'],array('Replacement & Shortages','Replacement','Shortages'))?'error':'').'"><i class="'.$icon.'"></i> '.$row['Delivery Note ID'].'</span>';

        }
        $delivery_notes=preg_replace('/^\, /','',$delivery_notes);


        $invoices='';
        $sql =  "select `Invoice Public ID`,`Invoice Key`,`Invoice Type`  from `Invoice Dimension` where `Invoice Order Key`=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->object->id
            )
        );
        while ($row = $stmt->fetch()) {

            if($row['Invoice Public ID']==$this->object->get('Public ID')){
                continue;
            }

            $this->real_time[] = $row['Invoice Public ID'];
            $number_only_id=trim(preg_replace('/[^0-9]/',' ',$row['Invoice Public ID']));
            $this->real_time[] =  $number_only_id;
            $this->real_time[] =  (int) $number_only_id;

            $invoices.=', <span class="'.($row['Invoice Type']=='Refund'?'error':'').'"><i class="fal fa-file-invoice"></i> '.$row['Invoice Public ID'].'</span>';

        }
        $invoices=preg_replace('/^\, /','',$invoices);

        if($invoices!='' and $delivery_notes!=''){
                $invoices='<span class="padding_right_10 ">'.$invoices.'</span>';
        }

        $this->icon_classes = $this->object->get('Icon');
        $this->label_1 = $this->object->get('Public ID');
        $this->label_2 = $this->object->get('Order Customer Name');
        $this->label_3 = $this->object->get('Total Amount');
        $this->label_4 = trim($invoices.' '.$delivery_notes);


        switch ($this->object->get('Order State')) {
            case 'InBasket':
                $this->weight = 25;


            case 'InProcess':
                $this->weight = 80;

            case 'InWarehouse':
                $this->weight = 40;


            case 'PackedDone':
                $this->weight = 80;

            case 'Approved':
                $this->weight = 100;

            case 'Dispatched':
                $this->weight = 50;

            case 'Cancelled':
                $this->weight = 10;

            default:
                $this->weight = 50;
        }




        $this->url   = sprintf('orders/%d/%d', $this->object->get('Order Store Key'), $this->object->id);


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

        $this->add_index('o', 'Order', $this->object->get('Order Store Key'));

    }

    private function add_part() {


        $this->label  = $this->object->get('Part Reference').', '.$this->object->get('Part Package Description');
        $this->url    = sprintf('part/%d', $this->object->id);
        $this->status = $this->object->get('Part Status');

        $this->primary[] = $this->object->get('Part Reference');
        $this->primary[] = $this->object->get('Part Package Description');
        $this->primary[] = $this->object->get('Part SKO Barcode');

        $this->secondary[] = strip_tags($this->object->get('Materials'));


        $this->remove_duplicated_tokens();
        $this->add_index('sko', 'Part');

    }

    private function add_webpage() {


        $this->label  = $this->object->get('Webpage Code').', '.$this->object->get('URL');
        $this->url    = sprintf('webpages/%d/%d', $this->object->get('Webpage Website Key'), $this->object->id);
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
        $this->add_index('w', 'Webpage', $this->object->get('Webpage Store Key'));

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
        $url      = preg_replace('/^https?\:\/\//', '', $url);

        $aux[] = $url;
        $url   = preg_replace('/^www\./', '', $url);
        $aux[] = $url;

        return array(
            $tokens,
            $aux
        );
    }
    private function add_index($prefix, $object, $store_key = '') {





        $params = [
            'index' => strtolower('au_q_'.$this->account_code),
            'id'    => $prefix.$this->object->id,
            'body'  => array(
                'rt'           => $this->flatten($this->real_time),
                'url'          => $this->url,
                'module'       => $this->module,
                //'object'       => $object,
                //'status'       => $this->status,
                'weight'       => $this->weight,
                //'result_label' => $this->label,
                //'primary'      => $this->flatten($this->primary),
                //'secondary'    => $this->flatten($this->secondary),
                //'alias'        => $this->flatten($this->alias),

                'store_key'    => $store_key,
                'icon_classes' => $this->icon_classes,
                'label_1'      => $this->label_1,
                'label_2'      => $this->label_2,
                'label_3'      => $this->label_3,
                'label_4'      => $this->label_4,

            )
        ];

        if(count( $this->scopes)>0){
            $params['body']['scopes']=$this->scopes;
        }


        $this->client->index($params);
    }


}

