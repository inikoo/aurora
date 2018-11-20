<?php
/*
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 1 August 2018 at 20:03:28 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2014, Inikoo

 Version 2.0
*/

require_once 'utils/get_addressing.php';


function fork_migration($job) {


    if (!$_data = get_fork_metadata($job)) {
        return true;
    }


    list($account, $db, $data, $editor, $session) = $_data;

    print_r($data);

    //return true;


    switch ($data['type']) {

        case 'update_other_telephones':

            if ($data['customer_key']) {
                $customer         = get_object('Customer', $data['customer_key']);
                $customer->editor = $data['editor'];

                add_other_telephone(get_other_telecoms_data($db, 'Telephone', $customer), $customer);
                add_other_telephone(get_other_telecoms_data($db, 'Mobile', $customer), $customer);
                add_other_telephone(get_other_telecoms_data($db, 'FAX', $customer), $customer);


                $customer->update_full_search();
                $customer->update_location_type();
            }

            break;


        case 'customer_updated_migration':
            if ($data['customer_key']) {


                migrate_customer_data($data['customer_key'], $db);

                $customer = get_object('Customer', $data['customer_key']);


                $customer->update_full_search();
                $customer->update_location_type();

            }
            break;
        case 'customer_created_migration':

            if ($data['customer_key']) {
                migrate_customer_data($data['customer_key'], $db);


                $customer         = get_object('Customer', $data['customer_key']);
                $store            = get_object('Store', $customer->get('Customer Store Key'));
                $customer->editor = $data['editor'];
                $store->editor    = $data['editor'];


                $customer->update_full_search();
                $customer->update_location_type();
                $store->update_customers_data();


                $sql = sprintf(
                    'select `Prospect Key` from `Prospect Dimension`  where `Prospect Store Key`=%d and `Prospect Main Plain Email`=%s and `Prospect Customer Key` is  NULL ', $customer->get('Store Key'), prepare_mysql($customer->get('Customer Main Plain Email'))

                );
                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {

                        $prospect         = get_object('Prospect', $row['Prospect Key']);
                        $prospect->editor = $data['editor'];
                        if ($prospect->id) {
                            $sql = sprintf('select `History Key`,`Type`,`Deletable`,`Strikethrough` from `Prospect History Bridge` where `Prospect Key`=%d ', $prospect->id);
                            if ($result2 = $db->query($sql)) {
                                foreach ($result2 as $row2) {
                                    $sql = sprintf(
                                        "INSERT INTO `Customer History Bridge` VALUES (%d,%d,%s,%s,%s)", $customer->id, $row2['History Key'], prepare_mysql($row2['Deletable']), prepare_mysql($row2['Strikethrough']), prepare_mysql($row2['Type'])
                                    );
                                    //print "$sql\n";
                                    $db->exec($sql);
                                }
                            }


                            $prospect->update_status('Registered', $customer);
                        }
                    }
                }
            }
            break;

        case 'migrate_invoice':

            include_once 'class.Billing_To.php';
            include_once 'class.Store.php';


            $invoice                  = get_object('Invoice', $data['invoice_key']);
            $data_to_update           = array();
            $address_fields_to_update = array();

            $order=false;

            if ($invoice->get('Invoice Order Key') > 0) {
                $order = get_object('Order', $invoice->get('Invoice Order Key'));
            } else {
                $sql = sprintf("select `Order Key` from `Order Transaction Fact` where `Invoice Key`=%d and  `Order Key`>0", $invoice->id);
                if ($result2 = $db->query($sql)) {
                    if ($row2 = $result2->fetch()) {


                        $order = get_object('Order', $row2['Order Key']);
                    }
                }
            }


            if ($order and is_object($order) and $order->id) {
                $data_to_update['Invoice Order Key'] = $order->id;

                $recipient    = $invoice->get('Invoice Customer Contact Name');
                $organization = $invoice->get('Invoice Customer Name');


                if ($organization == $recipient) {
                    $organization = '';
                }
                $store           = new Store($invoice->get('Store Key'));
                $default_country = $store->get('Store Home Country Code 2 Alpha');

                $address_fields_to_update = parse_old_invoice_address_fields($store, $order->get('Order Billing To Key To Bill'), $recipient, $organization, $default_country);


                $sql = sprintf('select * from `Invoice Payment Bridge`  where `Invoice Key`=%d', $invoice->id);
                if ($result2 = $db->query($sql)) {
                    foreach ($result2 as $row2) {


                        $sql = sprintf(
                            'update `Order Payment Bridge` set `Invoice Key`=%d where `Order Key`=%d and `Payment Key`=%d ', $invoice->id, $order->id, $row2['Payment Key']


                        );
                        //print "$sql\n";
                        $db->exec($sql);

                        $sql = sprintf(
                            'Insert into `Order Payment Bridge` values  (%d,%d,%d,%d,%d,%.2f,%s) ', $order->id, $invoice->id, $row2['Payment Key'], $row2['Payment Account Key'], $row2['Payment Service Provider Key'], $row2['Amount'], prepare_mysql('No')


                        );
                        // print "$sql\n";

                        $db->exec($sql);

                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    print "$sql\n";
                    exit;
                }


            }


            $invoice->fast_update($data_to_update);
            $invoice->fast_update($address_fields_to_update);


            break;
        case 'migrate_dn':

            require_once 'class.Ship_To.php';
            include_once 'class.Store.php';
            include_once 'class.DeliveryNote.php';

            $dn                       = new DeliveryNote($data['dn_key']);
            $data_to_update           = array();
            $address_fields_to_update = array();


            $sql = sprintf("select `Order Key` from `Order Transaction Fact` where `Delivery Note Key`=%d and  `Order Key`>0", $dn->id);

            if ($result2 = $db->query($sql)) {
                if ($row2 = $result2->fetch()) {

                    //$order = get_object('Order', $row2['Order Key']);


                    $data_to_update['Delivery Note Order Key'] = $row2['Order Key'];


                    $ship_to = new Ship_To($dn->get('Delivery Note Ship To Key'));


                    if ($ship_to->id) {
                        $recipient    = $ship_to->get('Ship To Contact Name');
                        $organization = $ship_to->get('Ship To Company Name');


                        if ($organization == $recipient) {
                            $organization = '';
                        }
                        $store           = new Store($dn->get('Store Key'));
                        $default_country = $store->get('Store Home Country Code 2 Alpha');

                        $address_fields_to_update = parse_old_dn_address_fields($store, $ship_to, $recipient, $organization, $default_country);
                    }


                }
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
            }
            //print_r($data_to_update);
            //print_r($address_fields_to_update);
            $dn->fast_update($data_to_update);
            $dn->fast_update($address_fields_to_update);

            break;
        default:
            break;

    }


    return false;
}


function migrate_customer_data($customer_key, $db) {


    require_once 'utils/get_addressing.php';

    require_once 'class.Customer.php';
    require_once 'class.Store.php';
    require_once 'class.Address.php';


    $customer = new Customer($customer_key);


    $other_emails = get_other_emails_data($db, $customer);
    if (count($other_emails) > 0) {
        //print $customer->id."\n";

        foreach ($other_emails as $other_email) {
            $customer->update(array('new email' => $other_email['email']));
            //print_r($customer);
            //

        }

    }


    $store           = new Store($customer->get('Store Key'));
    $default_country = $store->get('Store Home Country Code 2 Alpha');

    $recipient    = $customer->get('Main Contact Name');
    $organization = $customer->get('Company Name');


    if ($organization == $recipient) {
        $organization = '';
    }

    if (!$customer->get('Customer Main Address Key')) {
        $address_fields = array(
            'Address Recipient'            => $recipient,
            'Address Organization'         => $organization,
            'Address Line 1'               => '',
            'Address Line 2'               => '',
            'Address Sorting Code'         => '',
            'Address Postal Code'          => '',
            'Address Dependent Locality'   => '',
            'Address Locality'             => '',
            'Address Administrative Area'  => '',
            'Address Country 2 Alpha Code' => $default_country,

        );
    } else {

        $address_fields = address_fields(
            $customer->get('Customer Main Address Key'), $recipient, $organization, $default_country
        );
    }


    $customer->update_address('Contact', $address_fields, 'no_history  ');

    $location = $customer->get('Contact Address Locality');
    if ($location == '') {
        $location = $customer->get('Contact Address Administrative Area');
    }
    if ($location == '') {
        $location = $customer->get('Customer Contact Address Postal Code');
    }


    $customer->update(
        array(
            'Customer Location' => trim(
                sprintf(
                    '<img src="/art/flags/%s.gif" title="%s"> %s', strtolower(
                    $customer->get(
                        'Contact Address Country 2 Alpha Code'
                    )
                ), $customer->get('Contact Address Country 2 Alpha Code'), $location
                )
            )
        ), 'no_history'
    );


    if (!$customer->get('Customer Main Delivery Address Key')) {


        $address_fields = array(
            'Address Recipient'            => $recipient,
            'Address Organization'         => $organization,
            'Address Line 1'               => '',
            'Address Line 2'               => '',
            'Address Sorting Code'         => '',
            'Address Postal Code'          => '',
            'Address Dependent Locality'   => '',
            'Address Locality'             => '',
            'Address Administrative Area'  => '',
            'Address Country 2 Alpha Code' => $default_country,

        );
    } else {
        $address = new _Address(
            $customer->get('Customer Main Delivery Address Key')
        );


        if ($address->data['Address Contact'] != '') {
            $address_contact = trim(
                preg_replace(
                    '/\s+/', ' ', $address->data['Address Contact']
                )
            );
            if (strtolower($address_contact) == strtolower($recipient)) {

            } elseif (strtolower($address_contact) == strtolower(
                    $organization
                )) {

            } else {
                print $customer->id." DEL ==================\n";
                print "->$recipient<-\n";
                print "->$organization<-\n";
                print "->$address_contact<-\n";
                $recipient    = $address_contact;
                $organization = '';

            }
        }
        $address_fields = address_fields(
            $customer->get('Customer Main Delivery Address Key'), $recipient, $organization, $default_country
        );
    }


    $customer->update_address('Delivery', $address_fields, 'no_history no_old_address');


    $fiscal_name  = get_fiscal_name($customer, $db);
    $organization = $fiscal_name;
    $recipient    = $customer->data['Customer Main Contact Name'];
    if ($fiscal_name == '') {
        $organization = $customer->get('Company Name');
    } else {
        $organization = $fiscal_name;
    }
    if ($organization == $recipient) {
        $organization = '';
    }

    $recipient    = trim(preg_replace('/\s+/', ' ', $recipient));
    $organization = trim(preg_replace('/\s+/', ' ', $organization));


    if (!$customer->get('Customer Billing Address Key')) {
        $address_fields = array(
            'Address Recipient'            => $recipient,
            'Address Organization'         => $organization,
            'Address Line 1'               => '',
            'Address Line 2'               => '',
            'Address Sorting Code'         => '',
            'Address Postal Code'          => '',
            'Address Dependent Locality'   => '',
            'Address Locality'             => '',
            'Address Administrative Area'  => '',
            'Address Country 2 Alpha Code' => $default_country,

        );
    } else {
        $address = new _Address(
            $customer->get('Customer Billing Address Key')
        );
        if ($address->data['Address Contact'] != '') {
            $address_contact = trim(preg_replace('/\s+/', ' ', $address->data['Address Contact']));
            if (strtolower($address_contact) == strtolower($recipient)) {

            } elseif (strtolower($address_contact) == strtolower($organization)) {

            } else {
                print $customer->id."==================\n";
                print "->$recipient<-\n";
                print "->$organization<-\n";
                print "->$address_contact<-\n";
                $recipient    = $address_contact;
                $organization = '';

            }
        }
        $address_fields = address_fields(
            $customer->get('Customer Billing Address Key'), $recipient, $organization, $default_country
        );
    }


    $customer->update_address('Invoice', $address_fields, 'no_history no_old_address');

    $other_delivery_address_keys = get_delivery_address_keys(
        $db, $customer->id, $customer->get('Customer Billing Address Key')
    );


    if (count($other_delivery_address_keys) > 0) {

        foreach (
            $other_delivery_address_keys as $other_delivery_address_key
        ) {
            $recipient    = $customer->get('Main Contact Name');
            $organization = $customer->get('Company Name');


            $address = new _Address($other_delivery_address_key);
            if ($address->data['Address Contact'] != '') {
                $address_contact = trim(
                    preg_replace(
                        '/\s+/', ' ', $address->data['Address Contact']
                    )
                );
                if (strtolower($address_contact) == strtolower(
                        $recipient
                    )) {

                } elseif (strtolower($address_contact) == strtolower(
                        $organization
                    )) {

                } else {
                    print $customer->id." Other DEL ==================\n";
                    print "->$recipient<-\n";
                    print "->$organization<-\n";
                    print "->$address_contact<-\n";
                    $recipient    = $address_contact;
                    $organization = '';

                }
            }
            $address_fields = address_fields(
                $other_delivery_address_key, $recipient, $organization, $default_country
            );
            $customer->add_other_delivery_address($address_fields);


        }


    }

    if ($customer->data['Customer Main Plain Telephone'] != '') {
        $customer->update(
            array('Customer Main Plain Telephone' => $customer->data['Customer Main Plain Telephone']), 'no_history'
        );
    }
    if ($customer->data['Customer Main Plain Mobile'] != '') {
        $customer->update(
            array('Customer Main Plain Mobile' => $customer->data['Customer Main Plain Mobile']), 'no_history'
        );
    }
    if ($customer->data['Customer Main Plain FAX'] != '') {
        $customer->update(
            array('Customer Main Plain FAX' => $customer->data['Customer Main Plain FAX']), 'no_history'
        );
    }


    if ($customer->get('Customer Billing Address Link') == 'Contact' and $customer->get('Customer Delivery Address Link') == 'Contact') {
        $customer->update(
            array('Customer Delivery Address Link' => 'Billing'), 'no_history'
        );

    }


    add_other_telephone(get_other_telecoms_data($db, 'Telephone', $customer), $customer);
    add_other_telephone(get_other_telecoms_data($db, 'Mobile', $customer), $customer);
    add_other_telephone(get_other_telecoms_data($db, 'FAX', $customer), $customer);

    if ($customer->get('Customer Tax Number') != '' and $customer->get('Customer Tax Number Validation Source') != 'Manual' and ($customer->get('Customer Tax Number Valid') == 'Unknown' or $customer->get('Customer Tax Number Validation Date')
            == '0000-00-00 00:00:00')) {
        $customer->update_tax_number_valid('Auto');

    }


}


function add_other_telephone($other_telephones, $customer) {
    if (count($other_telephones) > 0) {


        foreach ($other_telephones as $other_telephone) {
            $customer->update(array('new telephone' => $other_telephone['number']), 'no_history');
            if ($customer->field_created_key and $other_telephone['label'] != '') {
                $update_data                                                                 = array();
                $update_data['Customer Other Telephone Label '.$customer->field_created_key] = $other_telephone['label'];
                $customer->update($update_data, 'no_history');

            }


        }

    }

}


function trim_value(&$value) {
    $value = trim(preg_replace('/\s+/', ' ', $value));
}


function address_fields($address_key, $recipient, $organization, $default_country) {

    $address = new _Address($address_key);

    if ($address->id > 0) {


        $address_format = get_address_format(
            ($address->data['Address Country 2 Alpha Code'] == 'XX' ? 'GB' : $address->data['Address Country 2 Alpha Code'])
        );


        $_tmp = preg_replace('/,/', '', $address_format->getFormat());

        $used_fields = preg_split('/\s+/', preg_replace('/%/', '', $_tmp));


        $lines = $address->display('2lines');

        $address_fields = array(
            'Address Recipient'            => $recipient,
            'Address Organization'         => $organization,
            'Address Line 1'               => $lines[1],
            'Address Line 2'               => $lines[2],
            'Address Sorting Code'         => '',
            'Address Postal Code'          => $address->get('Address Postal Code'),
            'Address Dependent Locality'   => $address->display('Town Divisions'),
            'Address Locality'             => $address->get('Address Town'),
            'Address Administrative Area'  => $address->display('Country Divisions'),
            'Address Country 2 Alpha Code' => ($address->data['Address Country 2 Alpha Code'] == 'XX' ? $default_country : $address->data['Address Country 2 Alpha Code']),

        );
        //print_r($used_fields);

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

        if (!in_array('administrativeArea', $used_fields) and $address->display(
                'Country Divisions'
            ) != '') {
            $address_fields['Address Administrative Area'] = '';
            //print_r($address->data);
            //print_r($address_fields);

            //print $address->display();


            //exit;

            //print_r($used_fields);
            //print_r($address->data);
            //exit('administrativeArea problem');

        }

        if (!in_array('postalCode', $used_fields) and $address->display(
                'Address Postal Code'
            ) != '') {

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

        if (!in_array('locality', $used_fields) and ($address->display(
                    'Address Locality'
                ) != '' or $address->display('Address Dependent Locality') != '')) {


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
                print_r($address->data);
                print_r($address_fields);

                print $address->display();


                exit("Error3\n");

            }


        }


    } else {


        $address_format = get_address_format($default_country);


        $address_fields = array(
            'Address Recipient'            => $recipient,
            'Address Organization'         => $organization,
            'Address Line 1'               => '',
            'Address Line 2'               => '',
            'Address Sorting Code'         => '',
            'Address Postal Code'          => '',
            'Address Dependent Locality'   => '',
            'Address Locality'             => '',
            'Address Administrative Area'  => '',
            'Address Country 2 Alpha Code' => $default_country,

        );

    }

    array_walk($address_fields, 'trim_value');
    //print "\n".$customer->id."\n";
    //print_r($address_fields);

    return $address_fields;
}


function get_fiscal_name($customer, $db) {
    if ($customer->data['Customer Type'] == 'Person') {
        $customer->data['Customer Fiscal Name'] = $customer->data['Customer Name'];

        return $customer->data['Customer Fiscal Name'];
    } else {
        $subject     = 'Company';
        $subject_key = $customer->data['Customer Company Key'];
    }

    $sql = sprintf(
        "select `$subject Fiscal Name` as fiscal_name from `$subject Dimension` where `$subject Key`=%d ", $subject_key
    );


    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $customer->data['Customer Fiscal Name'] = $row['fiscal_name'];

            return $customer->data['Customer Fiscal Name'];
        } else {
            return '';
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


}


function get_delivery_address_keys($db, $customer_key, $main_address_key) {


    $sql          = sprintf(
        "SELECT * FROM `Address Bridge` CB WHERE  `Address Function` IN ('Shipping')  AND `Subject Type`='Customer' AND `Subject Key`=%d  GROUP BY `Address Key` ORDER BY `Address Key`   ", $customer_key
    );
    $address_keys = array();


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            if ($row['Address Key'] == $main_address_key) {
                continue;
            }

            $address_keys[$row['Address Key']] = $row['Address Key'];
        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    return $address_keys;


}


function get_other_emails_data($db, $customer) {


    $sql = sprintf(
        "SELECT B.`Email Key`,`Email`,`Email Description`,`User Key` FROM
        `Email Bridge` B  LEFT JOIN `Email Dimension` E ON (E.`Email Key`=B.`Email Key`)
        LEFT JOIN `User Dimension` U ON (`User Handle`=E.`Email` AND `User Type`='Customer' AND `User Parent Key`=%d )
        WHERE  `Subject Type`='Customer' AND `Subject Key`=%d ", $customer->id, $customer->id
    );

    $email_keys = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            if ($row['Email Key'] != $customer->data['Customer Main Email Key']) {
                $email_keys[$row['Email Key']] = array(
                    'email'    => $row['Email'],
                    'key'      => $row['Email Key'],
                    'xhtml'    => '<a href="mailto:'.$row['Email'].'">'.$row['Email'].'</a>',
                    'label'    => $row['Email Description'],
                    'user_key' => $row['User Key']
                );
            }

        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    return $email_keys;

}


function get_other_telecoms_data($db, $type, $customer) {

    $sql = sprintf(
        "SELECT B.`Telecom Key`,`Telecom Description`,`Telecom Plain Number` FROM `Telecom Bridge` B LEFT JOIN `Telecom Dimension` T ON (T.`Telecom Key`=B.`Telecom Key`) WHERE `Telecom Type`=%s  AND   `Subject Type`='Customer' AND `Subject Key`=%d ",
        prepare_mysql($type), $customer->id
    );
    //print $sql;
    $telecom_keys = array();


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            if ($row['Telecom Key'] != $customer->data["Customer Main $type Key"]) {


                $telecom_keys[$row['Telecom Key']] = array(
                    'number' => $row['Telecom Plain Number'],
                    'label'  => $row['Telecom Description']
                );

            }
        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    return $telecom_keys;

}


function parse_old_invoice_address_fields($store, $address_key, $recipient, $organization, $default_country) {


    $address = new Billing_To($address_key);
    if ($address->id > 0) {


        if ($address->data['Billing To Country 2 Alpha Code'] == 'XX' or $address->data['Billing To Country 2 Alpha Code'] == '') {
            $address->data['Billing To Country 2 Alpha Code'] = $default_country;
        }


        $address_format = get_address_format(
            (($address->data['Billing To Country 2 Alpha Code'] == 'XX' or $address->data['Billing To Country 2 Alpha Code'] == '') ? $default_country : $address->data['Billing To Country 2 Alpha Code'])
        );


        $_tmp = preg_replace('/,/', '', $address_format->getFormat());

        $used_fields = preg_split('/\s+/', preg_replace('/%/', '', $_tmp));


        $address_fields = array(
            'Address Recipient'            => $recipient,
            'Address Organization'         => $organization,
            'Address Line 1'               => $address->get('Billing To Line 1'),
            'Address Line 2'               => $address->get('Billing To Line 2'),
            'Address Sorting Code'         => '',
            'Address Postal Code'          => $address->get('Billing To Postal Code'),
            'Address Dependent Locality'   => $address->get('Billing To Line 3'),
            'Address Locality'             => $address->get('Billing To Town'),
            'Address Administrative Area'  => $address->get('Billing To Line 4'),
            'Address Country 2 Alpha Code' => ($address->data['Billing To Country 2 Alpha Code'] == 'XX' ? $default_country : $address->data['Billing To Country 2 Alpha Code']),

        );
        //print_r($used_fields);

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

        if (!in_array('administrativeArea', $used_fields) and $address->get('Billing To Line 4') != '') {
            $address_fields['Address Administrative Area'] = '';
            //print_r($address->data);
            //print_r($address_fields);

            //print $address->display();


            //exit;

            //print_r($used_fields);
            //print_r($address->data);
            //exit('administrativeArea problem');

        }

        if (!in_array('postalCode', $used_fields) and $address->display('Billing To Postal Code') != '') {

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


        if (!in_array('locality', $used_fields) and ($address->get(
                    'Billing To Town'
                ) != '' or $address->get('Billing To Line 4') != '')) {


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
                print_r($address->data);
                print_r($address_fields);

                print $address->display();


                exit("Error3\n");

            }


        }


    } else {


        $address_format = get_address_format($default_country);


        $address_fields = array(
            'Address Recipient'            => $recipient,
            'Address Organization'         => $organization,
            'Address Line 1'               => '',
            'Address Line 2'               => '',
            'Address Sorting Code'         => '',
            'Address Postal Code'          => '',
            'Address Dependent Locality'   => '',
            'Address Locality'             => '',
            'Address Administrative Area'  => '',
            'Address Country 2 Alpha Code' => $default_country,

        );

    }

    array_walk($address_fields, 'trim_value');

    $_address_fields = array();
    foreach ($address_fields as $key => $value) {
        $_address_fields['Invoice '.$key] = $value;
    }


    $new_checksum = md5(
        json_encode(
            array(
                'Address Recipient'            => $_address_fields['Invoice Address Recipient'],
                'Address Organization'         => $_address_fields['Invoice Address Organization'],
                'Address Line 1'               => $_address_fields['Invoice Address Line 1'],
                'Address Line 2'               => $_address_fields['Invoice Address Line 2'],
                'Address Sorting Code'         => $_address_fields['Invoice Address Sorting Code'],
                'Address Postal Code'          => $_address_fields['Invoice Address Postal Code'],
                'Address Dependent Locality'   => $_address_fields['Invoice Address Dependent Locality'],
                'Address Locality'             => $_address_fields['Invoice Address Locality'],
                'Address Administrative Area'  => $_address_fields['Invoice Address Administrative Area'],
                'Address Country 2 Alpha Code' => $_address_fields['Invoice Address Country 2 Alpha Code'],
            )
        )
    );

    $_address_fields['Invoice Address Checksum'] = $new_checksum;


    $account = get_object('Account', 1);
    $country = $account->get('Account Country 2 Alpha Code');
    $locale  = $store->get('Store Locale');


    list($address, $formatter, $postal_label_formatter) = get_address_formatter($country, $locale);


    $address = $address->withFamilyName($_address_fields['Invoice Address Recipient'])->withOrganization($_address_fields['Invoice Address Organization'])->withAddressLine1($_address_fields['Invoice Address Line 1'])->withAddressLine2(
        $_address_fields['Invoice Address Line 2']
    )->withSortingCode(
        $_address_fields['Invoice Address Sorting Code']
    )->withPostalCode($_address_fields['Invoice Address Postal Code'])->withDependentLocality(
        $_address_fields['Invoice Address Dependent Locality']
    )->withLocality($_address_fields['Invoice Address Locality'])->withAdministrativeArea(
        $_address_fields['Invoice Address Administrative Area']
    )->withCountryCode(
        $_address_fields['Invoice Address Country 2 Alpha Code']
    );


    $xhtml_address = $formatter->format($address);


    $xhtml_address = preg_replace('/class="address-line1"/', 'class="address-line1 street-address"', $xhtml_address);
    $xhtml_address = preg_replace('/class="address-line2"/', 'class="address-line2 extended-address"', $xhtml_address);
    $xhtml_address = preg_replace('/class="sort-code"/', 'class="sort-code postal-code"', $xhtml_address);
    $xhtml_address = preg_replace('/class="country"/', 'class="country country-name"', $xhtml_address);


    $xhtml_address = preg_replace('/(class="address-line1 street-address"><\/span>)<br>/', '$1', $xhtml_address);

    //  print $xhtml_address;

    $_address_fields['Invoice Address Formatted'] = $xhtml_address;
    /*
        $account=get_object('Account',1);
        $country = $account->get('Account Country 2 Alpha Code');
        $country = $store->get('Store Home Country Code 2 Alpha');

        $locale  = $store->get('Store Locale');



        list($address, $formatter, $postal_label_formatter) = get_address_formatter($country, $locale);
    */


    $_address_fields['Invoice Address Postal Label'] = $postal_label_formatter->format($address);


    //print "\n".$customer->id."\n";
    //print_r($address_fields);

    return $_address_fields;
}


function parse_old_dn_address_fields($store, $address, $recipient, $organization, $default_country) {


    if ($address->id > 0) {


        if ($address->data['Ship To Country 2 Alpha Code'] == 'XX' or $address->data['Ship To Country 2 Alpha Code'] == '') {
            $address->data['Ship To Country 2 Alpha Code'] = $default_country;
        }

        $address_format = get_address_format(
            (($address->data['Ship To Country 2 Alpha Code'] == 'XX' or $address->data['Ship To Country 2 Alpha Code'] == '') ? $default_country : $address->data['Ship To Country 2 Alpha Code'])
        );


        $_tmp = preg_replace('/,/', '', $address_format->getFormat());

        $used_fields = preg_split('/\s+/', preg_replace('/%/', '', $_tmp));


        $address_fields = array(
            'Address Recipient'            => $recipient,
            'Address Organization'         => $organization,
            'Address Line 1'               => $address->get('Ship To Line 1'),
            'Address Line 2'               => $address->get('Ship To Line 2'),
            'Address Sorting Code'         => '',
            'Address Postal Code'          => $address->get('Ship To Postal Code'),
            'Address Dependent Locality'   => $address->get('Ship To Line 3'),
            'Address Locality'             => $address->get('Ship To Town'),
            'Address Administrative Area'  => $address->get('Ship To Line 4'),
            'Address Country 2 Alpha Code' => ($address->data['Ship To Country 2 Alpha Code'] == 'XX' ? $default_country : $address->data['Ship To Country 2 Alpha Code']),

        );
        //print_r($used_fields);

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

        if (!in_array('administrativeArea', $used_fields) and $address->get(
                'Ship To Line 4'
            ) != '') {
            $address_fields['Address Administrative Area'] = '';
            //print_r($address->data);
            //print_r($address_fields);

            //print $address->display();


            //exit;

            //print_r($used_fields);
            //print_r($address->data);
            //exit('administrativeArea problem');

        }

        if (!in_array('postalCode', $used_fields) and $address->get(
                'Ship To Postal Code'
            ) != '') {

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

        if (!in_array('locality', $used_fields) and ($address->get(
                    'Ship To Town'
                ) != '' or $address->get('Ship To Line 4') != '')) {


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
                print_r($address->data);
                print_r($address_fields);

                print $address->display();


                exit("Error3\n");

            }


        }


    } else {


        $address_format = get_address_format($default_country);


        $address_fields = array(
            'Address Recipient'            => $recipient,
            'Address Organization'         => $organization,
            'Address Line 1'               => '',
            'Address Line 2'               => '',
            'Address Sorting Code'         => '',
            'Address Postal Code'          => '',
            'Address Dependent Locality'   => '',
            'Address Locality'             => '',
            'Address Administrative Area'  => '',
            'Address Country 2 Alpha Code' => $default_country,

        );

    }

    array_walk($address_fields, 'trim_value');

    $_address_fields = array();
    foreach ($address_fields as $key => $value) {
        $_address_fields['Delivery Note '.$key] = $value;
    }


    $new_checksum = md5(
        json_encode(
            array(
                'Address Recipient'            => $_address_fields['Delivery Note Address Recipient'],
                'Address Organization'         => $_address_fields['Delivery Note Address Organization'],
                'Address Line 1'               => $_address_fields['Delivery Note Address Line 1'],
                'Address Line 2'               => $_address_fields['Delivery Note Address Line 2'],
                'Address Sorting Code'         => $_address_fields['Delivery Note Address Sorting Code'],
                'Address Postal Code'          => $_address_fields['Delivery Note Address Postal Code'],
                'Address Dependent Locality'   => $_address_fields['Delivery Note Address Dependent Locality'],
                'Address Locality'             => $_address_fields['Delivery Note Address Locality'],
                'Address Administrative Area'  => $_address_fields['Delivery Note Address Administrative Area'],
                'Address Country 2 Alpha Code' => $_address_fields['Delivery Note Address Country 2 Alpha Code'],
            )
        )
    );

    $_address_fields['Delivery Note Address Checksum'] = $new_checksum;


    $account = get_object('Account', 1);
    $country = $account->get('Account Country 2 Alpha Code');
    $locale  = $store->get('Store Locale');


    list($address, $formatter, $postal_label_formatter) = get_address_formatter($country, $locale);


    $address =
        $address->withFamilyName($_address_fields['Delivery Note Address Recipient'])->withOrganization($_address_fields['Delivery Note Address Organization'])->withAddressLine1($_address_fields['Delivery Note Address Line 1'])->withAddressLine2(
            $_address_fields['Delivery Note Address Line 2']
        )->withSortingCode(
            $_address_fields['Delivery Note Address Sorting Code']
        )->withPostalCode($_address_fields['Delivery Note Address Postal Code'])->withDependentLocality(
            $_address_fields['Delivery Note Address Dependent Locality']
        )->withLocality($_address_fields['Delivery Note Address Locality'])->withAdministrativeArea(
            $_address_fields['Delivery Note Address Administrative Area']
        )->withCountryCode(
            $_address_fields['Delivery Note Address Country 2 Alpha Code']
        );


    $xhtml_address = $formatter->format($address);


    $xhtml_address = preg_replace('/class="address-line1"/', 'class="address-line1 street-address"', $xhtml_address);
    $xhtml_address = preg_replace('/class="address-line2"/', 'class="address-line2 extended-address"', $xhtml_address);
    $xhtml_address = preg_replace('/class="sort-code"/', 'class="sort-code postal-code"', $xhtml_address);
    $xhtml_address = preg_replace('/class="country"/', 'class="country country-name"', $xhtml_address);


    $xhtml_address = preg_replace('/(class="address-line1 street-address"><\/span>)<br>/', '$1', $xhtml_address);

    //  print $xhtml_address;

    $_address_fields['Delivery Note Address Postal Label'] = $postal_label_formatter->format($address);


    $_address_fields['Delivery Note Address Formatted'] = $xhtml_address;


    //print "\n".$customer->id."\n";
    //print_r($address_fields);

    return $_address_fields;
}


?>
