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

    function __construct($hosts,$account_code, $object, $db) {
        $this->client       = ClientBuilder::create()->setHosts($hosts)->build();
        $this->object       = $object;
        $this->db           = $db;
        $this->account_code = $account_code;
        $this->primary   = array();
        $this->secondary = array();
        $this->alias     = array();
    }

    private function add_order(){


        $amount="(".$this->object->get('Total Amount');
        if($this->object->get('Order Currency Exchange')!=1){
            $amount.=', '.$this->object->get('DC Total Amount');
        }
        $amount.=') ';

        $this->label=$this->object->get('Public ID')." $amount".$this->object->get('State');
        $this->url=sprintf('orders/%d/%d', $this->object->get('Order Store Key'), $this->object->id);

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
        $this->add_index('o', 'Order',$this->object->get('Order Store Key'));

    }    
    
    private function add_customer() {

        $this->label=$this->object->get_formatted_id().' '.$this->object->get('Name').' '.$this->object->get('Location');
        $this->url=sprintf('customers/%d/%d', $this->object->get('Customer Store Key'), $this->object->id);


        $this->primary[] = 'C'.$this->object->get_formatted_id();

        $this->primary[] = $this->object->get_formatted_id();
        $this->primary[] = $this->object->id;

        $this->primary[] = $this->object->get('Name');
        $this->primary[] = $this->object->get('Main Contact Name');
        $this->primary[] = $this->object->get('Customer Main Plain Email');

        foreach($this->object->get_other_emails_data() as $other_mail_data){
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
        $this->alias     = array_merge($this->alias, $address_aux);

        list($address_tokens, $address_aux) = $this->tokenize_address('Delivery');
        $this->primary = array_merge($this->primary, $address_tokens);
        $this->alias     = array_merge($this->alias, $address_aux);

        list($tel_tokens, $tel_aux) = $this->tokenize_telephone_number($this->object->get('Main XHTML Mobile'), $this->object->get('Customer Contact Address Country 2 Alpha Code'));
        $this->primary = array_merge($this->primary, $tel_tokens);
        $this->alias   = array_merge($this->alias, $tel_aux);

        list($tel_tokens, $tel_aux) = $this->tokenize_telephone_number($this->object->get('Main XHTML Telephone'), $this->object->get('Customer Contact Address Country 2 Alpha Code'));
        $this->primary = array_merge($this->primary, $tel_tokens);
        $this->alias   = array_merge($this->alias, $tel_aux);
        $this->primary[] = $this->object->get('Sticky Note');
        $this->secondary   = array_diff($this->secondary, $this->primary);

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
        $this->add_index('c', 'Customer',$this->object->get('Customer Store Key'));
       
    }


    function remove_duplicated_tokens(){
        $this->secondary   = array_diff($this->secondary, $this->primary);
        $this->alias = array_diff($this->alias, $this->primary);
        $this->alias = array_diff($this->alias, $this->secondary);
    }

    public function add_object(){
        switch ($this->object->get_object_name()){
            case 'Customer':
                $this->add_customer();
                break;
            case 'Order':
                $this->add_order();
                break;
        }
    }


    private function add_index($prefix,$object,$store_key=''){
         $params = [
            'index' => strtolower('au_'.$this->account_code),
            'id'    => $prefix.$this->object->id,
            'body'  => array(
                'url'          => $this->url,
                'object'       => $object,
                'result_label' => $this->label,
                'primary'      => $this->flatten($this->primary),
                'secondary'    => $this->flatten($this->secondary),
                'alias'        => $this->flatten($this->alias),
                'store_key'    => $store_key
            )
        ];

         print_r($params);

        $this->client->index($params);
    }
    
    
    private function tokenize_address($type) {

        include_once 'class.Country.php';


        $tokens = array();
        $this->alias  = array();

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
            $this->alias[] = $county_alias;
            if (strlen($county_alias) < 5) {
                $this->alias[] = preg_replace('/\s+/', '', $county_alias);
            }

        }

        return array(
            $tokens,
            $this->alias
        );
    }

    private function tokenize_telephone_number($number, $country) {

        $tokens = array();
        $this->alias  = array();
        if ($number == '') {
            return array(
                $tokens,
                $this->alias
            );
        }

        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $_number = $phoneUtil->parse($number, $country);

            $tokens[] = preg_replace('/[^0-9]/', '', $phoneUtil->format($_number, PhoneNumberFormat::E164));
            $tokens[] = preg_replace('/[^0-9]/', '', $phoneUtil->format($_number, PhoneNumberFormat::NATIONAL));


            $this->alias[] = $phoneUtil->format($_number, PhoneNumberFormat::NATIONAL);
            $this->alias[] = $phoneUtil->format($_number, PhoneNumberFormat::INTERNATIONAL);

            $this->alias[] = $phoneUtil->format($_number, PhoneNumberFormat::E164);

            $this->alias[] = preg_replace('/\s+/', '', $phoneUtil->format($_number, PhoneNumberFormat::E164));
            $this->alias[] = preg_replace('/\s+/', '', $phoneUtil->format($_number, PhoneNumberFormat::NATIONAL));
            $this->alias[] = preg_replace('/\s+/', '', $phoneUtil->format($_number, PhoneNumberFormat::INTERNATIONAL));

        } catch (NumberParseException $e) {
            return array(
                $tokens,
                $this->alias
            );
        }

        return array(
            $tokens,
            $this->alias
        );

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


}

