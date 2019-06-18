<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW


require_once 'common.php';
include 'class.Customer.php';
require_once 'class.Country.php';
require_once 'utils/get_addressing.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'Script (reading date from Magento)'
);


$store = get_object('Store', 9);



$sql = sprintf("SELECT * FROM `drop`.`sales_flat_order` where updated_at>'%s' ", date('Y-m-d H:i:s', strtotime('-2 month')));

$sql = "SELECT * FROM `drop`.`customer_entity` ";




//$sql= "SELECT * FROM `drop`.`customer_entity` where entity_id=488 ";


if ($result = $db->query($sql)) {
    foreach ($result as $row) {



        $store_code    = $store->data['Store Code'];
        $order_data_id = $row['entity_id'];

        $sql = sprintf(
            "select * from `Customer Import Metadata` where `Metadata`=%s and `Import Date`>=%s", prepare_mysql($store_code.$order_data_id), prepare_mysql($row['updated_at'])

        );

        if ($resxx = $db->query($sql)) {
            if ($rowxx = $resxx->fetch()) {
                 continue;
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }

        //print $row['entity_id']."\n";

        $email      = $row['email'];
        $name       = '';
        $tel        = '';
        $fax        = '';
        $mob        = '';
        $company    = '';
        $www        = '';
        $tax_number = '';
        $mobile     = '';


        $address1    = '';
        $address2    = '';
        $town        = '';
        $postcode    = '';
        $country_div = '';
        $country     = 'GBR';

        $sql = sprintf("SELECT * FROM `drop`.`customer_entity_varchar` WHERE `attribute_id` = %d AND `entity_id` =%d", getMagentoAttNumber('company_name', 1), $row['entity_id']);
        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch()) {
                $company = $row2['value'];
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf("SELECT * FROM `drop`.`customer_entity_varchar` WHERE `attribute_id` = %d AND `entity_id` =%d", getMagentoAttNumber('taxvat', 1), $row['entity_id']);
        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch()) {
                $tax_number = $row2['value'];
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf("SELECT * FROM `drop`.`customer_entity_varchar` WHERE `attribute_id` = %d AND `entity_id` =%d", getMagentoAttNumber('prefix', 1), $row['entity_id']);

        if ($result3 = $db->query($sql)) {
            foreach ($result3 as $row3) {
                if ($row3['value'] != '') {
                    $name .= ' '.$row3['value'];
                }
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf("SELECT * FROM `drop`.`customer_entity_varchar` WHERE `attribute_id` = %d AND `entity_id` =%d", getMagentoAttNumber('middlename', 1), $row['entity_id']);

        if ($result3 = $db->query($sql)) {
            foreach ($result3 as $row3) {
                if ($row3['value'] != '') {
                    $name .= ' '.$row3['value'];
                }
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }
        $sql = sprintf("SELECT * FROM `drop`.`customer_entity_varchar` WHERE `attribute_id` = %d AND `entity_id` =%d", getMagentoAttNumber('lastname', 1), $row['entity_id']);

        if ($result3 = $db->query($sql)) {
            foreach ($result3 as $row3) {
                if ($row3['value'] != '') {
                    $name .= ' '.$row3['value'];
                }
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }
        $sql = sprintf("SELECT * FROM `drop`.`customer_entity_varchar` WHERE `attribute_id` = %d AND `entity_id` =%d", getMagentoAttNumber('suffix', 1), $row['entity_id']);

        if ($result3 = $db->query($sql)) {
            foreach ($result3 as $row3) {
                if ($row3['value'] != '') {
                    $name .= ' '.$row3['value'];
                }
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


        $name = _trim($name);


        $sql = sprintf(
            "SELECT * FROM `drop`.`customer_entity_varchar` WHERE `attribute_id` = %d AND `entity_id` =%d", getMagentoAttNumber('telephone', 12), $row['entity_id']
        );    // telephone type ID 1 is missing from new DB ??? this might not be right phone number and other one no longer in the database....

        if ($result3 = $db->query($sql)) {
            foreach ($result3 as $row3) {
                if ($row3['value'] != '') {
                    $tel = $row3['value'];
                }
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }

        $sql = sprintf("SELECT * FROM `drop`.`customer_entity_varchar` WHERE `attribute_id` = %d AND `entity_id` =%d", getMagentoAttNumber('mobile', 1), $row['entity_id']);


        if ($result3 = $db->query($sql)) {
            foreach ($result3 as $row3) {
                if ($row3['value'] != '') {
                    $mob = $row3['value'];
                }
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf("SELECT * FROM `drop`.`customer_entity_varchar` WHERE `attribute_id` = %d AND `entity_id` =%d", getMagentoAttNumber('website_url', 1), $row['entity_id']);

        if ($result3 = $db->query($sql)) {
            foreach ($result3 as $row3) {
                if ($row3['value'] != '') {
                    $www = $row3['value'];
                }
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }

        $sql = sprintf("SELECT * FROM `drop`.`customer_address_entity` WHERE  `parent_id` =%d", $row['entity_id']);
        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch()) {
                list($address1, $address2, $town, $postcode, $country_div, $country) = get_address($row2['entity_id']);

            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


        $country = new Country('find', $country);


        list($tipo_customer, $company_name, $contact_name) = parse_company_person($company, $name);

        $customer_data = array(
            "Customer Type"                            => $tipo_customer,
            'Customer First Contacted Date'            => $row['created_at'],
            "Customer Store Key"                       => $store->id,
            "Customer Old ID"                          => $row['entity_id'],
            "Customer Name"                            => $company_name,
            "Customer Main Contact Name"               => $contact_name,
            "Customer Tax Number"                      => $tax_number,
            "Customer Website"                         => $www,
            "Customer Main Plain Email"                => $email,
            "Customer Main Plain Telephone"            => $tel,
            "Customer Main Plain FAX"                  => $fax,
            "Customer Main Plain Mobile"               => $mob,
            "Customer Address Line 1"                  => $address1,
            "Customer Address Line 2"                  => $address2,
            "Customer Address Line 3"                  => '',
            "Customer Address Town"                    => $town,
            "Customer Address Postal Code"             => $postcode,
            "Customer Address Country Name"            => $country->data['Country Name'],
            "Customer Address Country Code"            => $country->data['Country Code'],
            "Customer Address Country 2 Alpha Code"    => $country->data['Country 2 Alpha Code'],
            "Customer Address Town Second Division"    => '',
            "Customer Address Town First Division"     => $country_div,
            "Customer Address Country First Division"  => '',
            "Customer Address Country Second Division" => '',
            "Customer Address Country Third Division"  => '',
            "Customer Address Country Forth Division"  => '',
            "Customer Address Country Fifth Division"  => ''

        );


        $editor['Date'] = $row['created_at'];
        if ($customer_data['Customer Address Country Code'] == '') {
            $customer_data['Customer Address Country Code']         = 'GBR';
            $customer_data['Customer Address Country 2 Alpha Code'] = 'GB';
        }

        $customer_data['editor'] = $editor;
        //$customer_data['Customer Main Plain Telephone']='0114 321 8600';
        //print_r($customer_data);
        //continue;

        $_customer_key = 0;
        $sql           = sprintf("select `Customer Key` from `Customer Dimension` where `Customer Old ID`=%s and `Customer Store Key`=%d", $row['entity_id'], $store->id);

        if ($result__ = $db->query($sql)) {
            if ($row__ = $result__->fetch()) {
                $_customer_key = $row__['Customer Key'];
            }
        } else {
            print_r($error_info = $store->db->errorInfo());
            exit;
        }

        $customer = get_object('Customer', $_customer_key);


        if ($customer->id) {

            print 'Updating '.$customer->id."  ".$customer->get('Name')." \n";


            $update_address_data = array();

            $update_address_data['subject']     = 'Customer';
            $update_address_data['subject_key'] = $customer->id;

            //  $customer_data['Customer Address Line 3']='sss';

            $update_address_data['value'] = array(
                'country_code'        => $customer_data['Customer Address Country Code'],
                'country_2alpha_code' => $customer_data['Customer Address Country 2 Alpha Code'],

                'country_d1'  => $customer_data['Customer Address Country First Division'],
                'country_d2'  => $customer_data['Customer Address Country Second Division'],
                'town'        => $customer_data['Customer Address Town'],
                'town_d1'     => $customer_data['Customer Address Town First Division'],
                'town_d2'     => $customer_data['Customer Address Town Second Division'],
                'postal_code' => $customer_data['Customer Address Postal Code'],
                'street'      => $customer_data['Customer Address Line 3'],
                'internal'    => $customer_data['Customer Address Line 2'],
                'building'    => $customer_data['Customer Address Line 1'],
                'contact'     => '',
                //$customer_data['Customer Main Contact Name'],
                'use_contact' => '',
                'telephone'   => $customer_data['Customer Main Plain Telephone'],
                'use_tel'     => '',
            );


            $recipient    = $customer_data['Customer Main Contact Name'];
            $organization = $customer_data['Customer Name'];


            $address_fields = address_fields($update_address_data['value'], $recipient, $organization, 'GB');
            $customer->update_address('Contact', $address_fields, 'no_history');


            //print_r($customer_data);

            //      print_r($address_fields);


            $customer->update_field_switcher('Customer Name', $customer_data['Customer Name']);
            $customer->update_field_switcher('Customer Main Contact Name', $customer_data['Customer Main Contact Name']);
            $customer->update_field_switcher('Customer Main Plain Email', $customer_data['Customer Main Plain Email']);
            $customer->update_field_switcher('Customer Website', $customer_data['Customer Website']);
            $customer->update_field_switcher('Customer Tax Number', $customer_data['Customer Tax Number']);

            $customer->update_field_switcher('Customer Main Plain Telephone', $customer_data['Customer Main Plain Telephone']);
            $customer->update_field_switcher('Customer Main Plain Mobile', $customer_data['Customer Main Plain Mobile']);
            $customer->update_field_switcher('Customer Main Plain FAX', $customer_data['Customer Main Plain FAX']);


        } else {

            //print_r($customer_data);

            $data                       = array();
            $data['editor']             = $editor;
            $data['Customer Store Key'] = $store->id;


            $data['Customer Billing Address Link']  = 'Contact';
            $data['Customer Delivery Address Link'] = 'Billing';

            $data['Customer Tax Number'] = $customer_data['Customer Tax Number'];
            $data['Customer Website']    = $customer_data['Customer Website'];


            $data['Customer Main Plain Email']     = $customer_data['Customer Main Plain Email'];
            $data['Customer Main Plain Telephone'] = $customer_data['Customer Main Plain Telephone'];
            $data['Customer Main Plain FAX']       = $customer_data['Customer Main Plain FAX'];
            $data['Customer Main Plain Mobile']    = $customer_data['Customer Main Plain Mobile'];

            $data['Customer First Contacted Date'] = $customer_data['Customer First Contacted Date'];
            $data['Customer Type']                 = $customer_data['Customer Type'];
            $data['Customer First Contacted Date'] = $customer_data['Customer First Contacted Date'];
            $data['Customer Name']                 = $customer_data['Customer Name'];
            $data['Customer Main Contact Name']    = $customer_data['Customer Main Contact Name'];
            $data['Customer Old ID']               = $customer_data['Customer Old ID'];


            $update_address_data['value'] = array(
                'country_code'        => $customer_data['Customer Address Country Code'],
                'country_2alpha_code' => $customer_data['Customer Address Country 2 Alpha Code'],

                'country_d1'  => $customer_data['Customer Address Country First Division'],
                'country_d2'  => $customer_data['Customer Address Country Second Division'],
                'town'        => $customer_data['Customer Address Town'],
                'town_d1'     => $customer_data['Customer Address Town First Division'],
                'town_d2'     => $customer_data['Customer Address Town Second Division'],
                'postal_code' => $customer_data['Customer Address Postal Code'],
                'street'      => $customer_data['Customer Address Line 3'],
                'internal'    => $customer_data['Customer Address Line 2'],
                'building'    => $customer_data['Customer Address Line 1'],
                'contact'     => '',
                'use_contact' => '',
                'telephone'   => $customer_data['Customer Main Plain Telephone'],
                'use_tel'     => '',
            );


            $recipient    = $customer_data['Customer Main Contact Name'];
            $organization = $customer_data['Customer Name'];


            $address_fields = address_fields($update_address_data['value'], $recipient, $organization, 'GB');


            $customer = new Customer('new', $data, $address_fields);

            print 'New customer '.$customer->id."  ".$customer->get('Name')." \n";



        }


        $sql = sprintf(
            "INSERT INTO `Customer Import Metadata` ( `Metadata`, `Import Date`) VALUES (%s,%s) ON DUPLICATE KEY UPDATE
		`Import Date`=%s", prepare_mysql($store_code.$order_data_id), prepare_mysql($row['updated_at']), prepare_mysql($row['updated_at'])
        );

        $db->exec($sql);

    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


function address_fields($address_data, $recipient, $organization, $default_country) {


    //print_r($address_data);

    $country_2a = (($address_data['country_2alpha_code'] == 'XX' or $address_data['country_2alpha_code'] == '') ? $default_country : $address_data['country_2alpha_code']);

    $country_divs = preg_replace('/\, $|^\, /', '', $address_data['country_d1'].', '.$address_data['country_d2']);
    $town_divs    = preg_replace('/\, $|^\, /', '', $address_data['town_d1'].', '.$address_data['town_d2']);

    $address_format = get_address_format($country_2a);


    $_tmp = preg_replace('/,/', '', $address_format->getFormat());

    $used_fields = preg_split('/\s+/', preg_replace('/%/', '', $_tmp));


    $lines = array(
        1 => preg_replace('/\, $|^\, /', '', $address_data['internal'].', '.$address_data['building']),
        2 => $address_data['street']
    );

    $address_fields = array(
        'Address Recipient'            => $recipient,
        'Address Organization'         => $organization,
        'Address Line 1'               => $lines[1],
        'Address Line 2'               => $lines[2],
        'Address Sorting Code'         => '',
        'Address Postal Code'          => $address_data['postal_code'],
        'Address Dependent Locality'   => $town_divs,
        'Address Locality'             => $address_data['town'],
        'Address Administrative Area'  => $country_divs,
        'Address Country 2 Alpha Code' => $country_2a

    );

    //if (!in_array('recipient', $used_fields) or !in_array('organization', $used_fields) or !in_array('addressLine1', $used_fields)) {
    ////    print_r($used_fields);
    //    print_r($address->data);
    //    exit('no recipient or organization');
    // }

    if (!in_array('addressLine2', $used_fields)) {

        if ($address_fields['Address Line 2'] != '') {
            $address_fields['Address Line 1'] .= ', '.$address_fields['Address Line 2'];
        }
        $address_fields['Address Line 2'] = '';
    }

    if (!in_array('dependentLocality', $used_fields)) {

        if ($address_fields['Address Line 2'] == '') {
            $address_fields['Address Line 2'] = $address_fields['Address Dependent Locality'];
        } else {
            $address_fields['Address Line 2'] .= ', '.$address_fields['Address Dependent Locality'];
        }

        $address_fields['Address Dependent Locality'] = '';
    }

    if (!in_array('administrativeArea', $used_fields) and $country_divs != '') {
        $address_fields['Address Administrative Area'] = '';
        //print_r($address->data);
        //print_r($address_fields);

        //print $address->display();


        //exit;

        //print_r($used_fields);
        //print_r($address->data);
        //exit('administrativeArea problem');

    }

    if (!in_array('postalCode', $used_fields) and $address_data['postal_code'] != '') {

        if (in_array('sortingCode', $used_fields)) {
            $address_fields['Address Sorting Code'] = $address_fields['Address Postal Code'];
            $address_fields['Address Postal Code']  = '';

        } else {
            if (in_array('addressLine2', $used_fields)) {
                $address_fields['Address Line 2']      .= trim(
                    ' '.$address_fields['Address Postal Code']
                );
                $address_fields['Address Postal Code'] = '';
            }


            /*
            print_r($used_fields);
            print_r($address->data);
            print_r($address_fields);

            print $address->display();


            exit("\nError2\n");
            */
        }

    }

    if (!in_array('locality', $used_fields) and ($address_data['town'] != '' or $town_divs != '')) {


        //$address_fields['Address Locality']='';
        //$address_fields['Address Dependent Locality']='';

        if (in_array('addressLine2', $used_fields)) {

            if ($address_fields['Address Line 1'] == '' and $address_fields['Address Line 2'] == '') {
                $address_fields['Address Line 1'] .= $address_fields['Address Dependent Locality'];
                $address_fields['Address Line 2'] .= $address_fields['Address Locality'];

            } elseif ($address_fields['Address Line 1'] != '' and $address_fields['Address Line 2'] == '') {
                $address_fields['Address Line 2'] = preg_replace(
                    '/^, /', '', $address_fields['Address Dependent Locality'].', '.$address_fields['Address Locality']
                );

            } else {
                $address_fields['Address Line 2'] = preg_replace(
                    '/^, /', '', $address_fields['Address Dependent Locality'].', '.$address_fields['Address Locality']
                );

            }
        } else {

            print_r($used_fields);
            print_r($address_data);
            print_r($address_fields);


            exit("Error3\n");

        }


    }


    array_walk($address_fields, 'trim_value');
    //print "\n".$customer->id."\n";
    //print_r($address_fields);

    return $address_fields;
}


function parse_company_person($posible_company_name, $posible_contact_name) {
    $company_name          = $posible_company_name;
    $contact_name          = $posible_contact_name;
    $person_person_factor  = 0;
    $person_company_factor = 0;
    if ($posible_company_name != '' and $posible_contact_name != '') {
        $tipo_customer = 'Company';
        if ($posible_company_name == $posible_contact_name) {
            $person_factor  = is_person($posible_company_name);
            $company_factor = is_company($posible_company_name);
            if ($company_factor > $person_factor) {
                $tipo_customer = 'Company';
                $contact_name  = '';


            } else {
                $tipo_customer = 'Person';
                $company_name  = '';
            }

        } else {
            $company_person_factor  = is_person($posible_company_name) + 0.00001;
            $company_company_factor = is_company($posible_company_name) + 0.00001;
            $person_company_factor  = is_company($posible_contact_name) + 0.00001;
            $person_person_factor   = is_person($posible_contact_name) + 0.00001;


            $company_ratio = $company_company_factor / $company_person_factor;
            $person_ratio  = $person_person_factor / $person_company_factor;

            $ratio = ($company_ratio + $person_ratio) / 2;

            //print "** $company_ratio $person_ratio\n";

            if ($ratio < 0.4) {
                $swap = true;
            } else {
                $swap = false;
            }


            if ($swap) {
                $_name        = $posible_company_name;
                $company_name = $posible_contact_name;
                $contact_name = $_name;
            }


        }


    } elseif ($posible_company_name != '') {
        $tipo_customer          = 'Company';
        $company_person_factor  = is_person($posible_company_name);
        $company_company_factor = is_company($posible_company_name);

        if ($company_person_factor > $company_company_factor) {
            $tipo_customer = 'Person';
            $_name         = $posible_company_name;
            $company_name  = $posible_contact_name;
            $contact_name  = $_name;
        }


    } elseif ($posible_contact_name != '') {
        $tipo_customer         = 'Person';
        $person_company_factor = is_company($posible_contact_name);
        $person_person_factor  = is_person($posible_contact_name);

        if ($person_company_factor > $person_person_factor) {
            $tipo_customer = 'Company';
            $_name         = $posible_company_name;
            $company_name  = $posible_contact_name;
            $contact_name  = $_name;
        }


    } else {
        $tipo_customer = 'Person';

    }

    /*
    printf("Name: %s  ; Company: %s  \n is company a person %f is company a company %f\n is paerson a comapny %f  is person a person%f  \n$tipo_customer,\nName: $contact_name\nCompany:$company_name\n",
        $posible_contact_name,
            $posible_company_name,

     $company_person_factor,
                $company_company_factor,
                $person_company_factor,
                $person_person_factor



    );
    */

    return array(
        $tipo_customer,
        $company_name,
        $contact_name
    );


}

function is_person($name) {
    $company_suffix = "L\.?T\.?D\.?";
    $company_prefix = "The";
    $company_words  = array(
        'Gifts',
        'Chemist',
        'Pharmacy',
        'Company',
        'Business',
        'Associates',
        'Enterprises',
        'hotel',
        'shop',
        'aromatheraphy'
    );
    $name           = _trim($name);
    $probability    = 1;
    if (preg_match('/\d/', $name)) {
        $probability *= 0.00001;
    }
    if (preg_match("/\s+".$company_suffix."$/", $name)) {
        $probability *= 0.001;
    }
    if (preg_match("/\s+".$company_prefix."$/", $name)) {
        $probability *= 0.001;
    }
    // print_r($company_words);
    foreach ($company_words as $word) {
        if (preg_match("/\b".$word."\b/i", $name)) {
            $probability *= 0.01;
        }
    }


    if ($probability > 1) {
        $probability = 1;
    }

    return $probability;

}


function is_company($name, $locale = 'en_GB') {

    global $db;


    $name = _trim($name);
    //global $person_prefix;
    $probability = 1;


    if ($locale = 'en_GB') {
        $person_prefixes         = array(
            "Mr",
            "Miss",
            "Ms"
        );
        $common_company_suffixes = array("L\.?t\.?d\.?");
        $common_company_prefixes = array("the");

        $common_company_compoments = array("Limited");
    } else {
        $person_prefixes         = array();
        $common_company_suffixes = array();
        $common_company_prefixes = array();

        $common_company_compoments = array();

    }

    foreach ($common_company_prefixes as $company_prefix) {
        if (preg_match("/^".$company_prefix."\s+/i", $name)) {
            $probability *= 10;
            break;
        }
    }

    foreach ($common_company_suffixes as $company_suffix) {
        if (preg_match("/\s+".$company_suffix."$/i", $name)) {
            $probability *= 10;
            break;
        }
    }


    foreach ($person_prefixes as $person_prefix) {
        if (preg_match("/^".$person_prefix."\s+/i", $name)) {
            $probability *= 0.01;
        }
    }

    $components = preg_split('/\s/', $name);


    if (count($components) > 1) {
        $has_sal    = false;
        $saludation = preg_replace('/\./', '', $components[0]);
        $sql        = sprintf('select `Salutation Key` from kbase.`Salutation Dimension` where `Salutation`=%s  ', prepare_mysql($saludation));
        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $probability *= 0.9;
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


    }


    if (count($components) == 2) {
        $name_ok    = false;
        $surname_ok = false;
        $sql        = sprintf('select `First Name Key` from kbase.`First Name Dimension` where `First Name`=%s  ', prepare_mysql($components[0]));

        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $name_ok = true;
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }

        $sql = sprintf('select `Surname Key` from kbase.`Surname Dimension` where `Surname`=%s  ', prepare_mysql($components[1]));

        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $surname_ok = true;
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


        if ($surname_ok and $name_ok) {
            $probability *= 0.75;
        }
        if ($name_ok) {
            $probability *= 0.95;
        }
        if ($surname_ok) {
            $probability *= 0.95;
        }

        if (strlen($components[0]) == 1) {
            $probability *= 0.95;
        }


    } elseif (count($components) == 3) {

        $name_ok    = false;
        $surname_ok = false;
        $sql        = sprintf('select `First Name Key` from kbase.`First Name Dimension` where `First Name`=%s  ', prepare_mysql($components[0]));

        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $name_ok = true;

            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf('select `Surname Key` from kbase.`Surname Dimension` where `Surname`=%s  ', prepare_mysql($components[2]));


        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $surname_ok = true;
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


        if ($surname_ok and $name_ok) {
            $probability *= 0.75;
        }
        if ($name_ok) {
            $probability *= 0.95;
        }
        if ($surname_ok) {
            $probability *= 0.95;
        }

        if (strlen($components[1]) == 1) {
            $probability *= 0.95;
        }

        if (strlen($components[1]) == 1 and strlen($components[0]) == 1) {
            $probability *= 0.99;
        }

    }

    if ($probability > 1) {
        $probability = 1;
    }

    return $probability;
}


function trim_value(&$value) {
    $value = trim(preg_replace('/\s+/', ' ', $value));
}


function getMagentoAttNumber($attribute_code, $entity_type_id) {

    global $db;

    $sql = "SELECT `attribute_id` FROM `drop`.`eav_attribute` WHERE `attribute_code` LIKE '".$attribute_code."' AND `entity_type_id` =".$entity_type_id."  ";

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $Att_Got = $row['attribute_id'];
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    return $Att_Got;

}





function get_address($address_id) {

    global $db;

    $address1='';
    $address2='';
    $town='';
    $postcode='';
    $country_div='';
    $country='';

    $sql=sprintf("SELECT * FROM `drop`.`sales_flat_order_address` WHERE `entity_id` =%d",$address_id);

    if ($result=$db->query($sql)) {
        if ($row3 = $result->fetch()) {
            $town=$row3['city'];
            $postcode=$row3['postcode'];

            $array = preg_split('/$\R?^/m', $row3['street']);

            if (count($array)==2) {
                $address1=$array[0];
                $address2=$array[1];

            }else {
                $address1=$row3['street'];

            }

            $country=$row3['country_id'];

            if($country=='GB'){
                $country='United Kingdom';
            }

            $country_div=$row3['region'];
    	}
    }else {
    	print_r($error_info=$db->errorInfo());
    	print "$sql\n";
    	exit;
    }



    return array($address1,$address2,$town,$postcode,$country_div,$country);

}
