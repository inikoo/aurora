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

    function __construct($account_code, $object, $db) {
        $this->client       = ClientBuilder::create()->build();
        $this->object       = $object;
        $this->db           = $db;
        $this->account_code = $account_code;
    }


    public function add_customer() {


        $primary   = array();
        $secondary = array();
        $alias     = array();

        $primary[] = 'C'.$this->object->get_formatted_id();

        $primary[] = $this->object->get_formatted_id();
        $primary[] = $this->object->id;

        $primary[] = $this->object->get('Name');
        $primary[] = $this->object->get('Main Contact Name');
        $primary[] = $this->object->get('Customer Main Plain Email');

        foreach($this->object->get_other_emails_data() as $other_mail_data){
            $primary[] = $other_mail_data['email'];
        }


        $primary[] = $this->object->get('Customer Tax Number');
        $primary[] = $this->object->get('Customer Registration Number');
        $primary[] = $this->object->get('Customer Website');


        list($address_tokens, $address_aux) = $this->tokenize_address('Contact');
        $primary = array_merge($primary, $address_tokens);
        $alias   = array_merge($alias, $address_aux);

        list($address_tokens, $address_aux) = $this->tokenize_address('Invoice');
        $primary = array_merge($primary, $address_tokens);
        $alias     = array_merge($alias, $address_aux);

        list($address_tokens, $address_aux) = $this->tokenize_address('Delivery');
        $primary = array_merge($primary, $address_tokens);
        $alias     = array_merge($alias, $address_aux);

        list($tel_tokens, $tel_aux) = $this->tokenize_telephone_number($this->object->get('Main XHTML Mobile'), $this->object->get('Customer Contact Address Country 2 Alpha Code'));
        $primary = array_merge($primary, $tel_tokens);
        $alias   = array_merge($alias, $tel_aux);

        list($tel_tokens, $tel_aux) = $this->tokenize_telephone_number($this->object->get('Main XHTML Telephone'), $this->object->get('Customer Contact Address Country 2 Alpha Code'));
        $primary = array_merge($primary, $tel_tokens);
        $alias   = array_merge($alias, $tel_aux);

        $primary[] = $this->object->get('Sticky Note');


        $secondary   = array_diff($secondary, $primary);

        $sql  = "select `History Details`,`History Abstract` from `Customer History Bridge` B left join `History Dimension` H  on (B.`History Key`=H.`History Key`) where    B.`Customer Key`=? and `Type`='Notes'  ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->object->id
            )
        );
        while ($row = $stmt->fetch()) {
            $secondary[] = $row['History Details'];
            $secondary[] = $row['History Abstract'];
        }


        $alias = array_diff($alias, $primary);
        $alias = array_diff($alias, $secondary);


        $params = [
            'index' => strtolower('au_'.$this->account_code),
            'id'    => 'c'.$this->object->id,
            'body'  => array(
                'url'          => sprintf('customers/%d/%d', $this->object->get('Customer Store Key'), $this->object->id),
                'object'       => 'Customer',
                'result_label' => $this->object->get_formatted_id().' '.$this->object->get('Name').' '.$this->object->get('Location'),
                'primary'      => $this->flatten($primary),
                'secondary'    => $this->flatten($secondary),
                'alias'        => $this->flatten($alias),
                'store_key'    => $this->object->get('Customer Store Key')
            )
        ];

        //print_r($params);

        $this->client->index($params);
    }

    private function tokenize_address($type) {

        include_once 'class.Country.php';


        $tokens = array();
        $alias  = array();

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
            $alias[] = $county_alias;
            if (strlen($county_alias) < 5) {
                $alias[] = preg_replace('/\s+/', '', $county_alias);
            }

        }

        return array(
            $tokens,
            $alias
        );
    }

    private function tokenize_telephone_number($number, $country) {

        $tokens = array();
        $alias  = array();
        if ($number == '') {
            return array(
                $tokens,
                $alias
            );
        }

        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $_number = $phoneUtil->parse($number, $country);

            $tokens[] = preg_replace('/[^0-9]/', '', $phoneUtil->format($_number, PhoneNumberFormat::E164));
            $tokens[] = preg_replace('/[^0-9]/', '', $phoneUtil->format($_number, PhoneNumberFormat::NATIONAL));


            $alias[] = $phoneUtil->format($_number, PhoneNumberFormat::NATIONAL);
            $alias[] = $phoneUtil->format($_number, PhoneNumberFormat::INTERNATIONAL);

            $alias[] = $phoneUtil->format($_number, PhoneNumberFormat::E164);

            $alias[] = preg_replace('/\s+/', '', $phoneUtil->format($_number, PhoneNumberFormat::E164));
            $alias[] = preg_replace('/\s+/', '', $phoneUtil->format($_number, PhoneNumberFormat::NATIONAL));
            $alias[] = preg_replace('/\s+/', '', $phoneUtil->format($_number, PhoneNumberFormat::INTERNATIONAL));

        } catch (NumberParseException $e) {
            return array(
                $tokens,
                $alias
            );
        }

        return array(
            $tokens,
            $alias
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

