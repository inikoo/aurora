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
        $this->weight       = 1;
        $this->status       = '';

    }

    public function add_object() {

        switch ($this->object->get_object_name()) {
            case 'Customer':
                $this->add_customer();
                break;
            case 'Order':
                $this->add_order();
                break;
            case 'Part':
                $this->add_part();
            case 'Page':
                $this->add_webpage();
                break;
        }
    }

    private function add_customer() {

        $this->label = $this->object->get_formatted_id().' '.$this->object->get('Name').' '.$this->object->get('Location');
        $this->url   = sprintf('customers/%d/%d', $this->object->get('Customer Store Key'), $this->object->id);


        $this->status = $this->object->get('Customer Type by Activity');

        $this->primary[] = 'C'.$this->object->get_formatted_id();

        $this->primary[] = $this->object->get_formatted_id();
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

    private function add_index($prefix, $object, $store_key = '') {
        $params = [
            'index' => strtolower('au_'.$this->account_code),
            'id'    => $prefix.$this->object->id,
            'body'  => array(
                'url'          => $this->url,
                'object'       => $object,
                'status'       => $this->status,
                'weight'       => $this->weight,
                'result_label' => $this->label,
                'primary'      => $this->flatten($this->primary),
                'secondary'    => $this->flatten($this->secondary),
                'alias'        => $this->flatten($this->alias),
                'store_key'    => $store_key
            )
        ];


        $this->client->index($params);
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

    private function add_order() {

        $amount = "(".$this->object->get('Total Amount');
        if ($this->object->get('Order Currency Exchange') != 1) {
            $amount .= ', '.$this->object->get('DC Total Amount');
        }
        $amount .= ') ';

        $this->status = $this->object->get('Order State');

        $this->label = $this->object->get('Public ID')." $amount".$this->object->get('State');
        $this->url   = sprintf('orders/%d/%d', $this->object->get('Order Store Key'), $this->object->id);

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


}

