<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  28 February 2019 at 01:00:00 GMT+8 Kuala Lumpr malaysia
 Copyright (c) 2019, Inikoo

 Version 3.1

*/

require_once 'common.php';


require_once 'utils/get_addressing.php';
require_once 'utils/parse_natural_language.php';
require_once 'utils/object_functions.php';

$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

// and `Customer Key`=103576'


$store_key=7;

$store    = get_object('store', $store_key);

$website = get_object('website', $store->get('Store Website Key'));

$sql = sprintf('select `Customer Key`,`Customer Store Key`from `Customer Dimension` where `Customer Store Key`=%d ',$store->id);
if ($result2 = $db->query($sql)) {
    foreach ($result2 as $row2) {


        $customer = get_object('Customer', $row2['Customer Key']);


        if ($customer->get('Customer Main Plain Email') != '') {


            $sql = 'select `User Password` from `User Dimension` where `User Type`="Customer" and `User Parent Key`=? and `User Handle`=?  ';


            $stmt = $db->prepare($sql);
            if ($stmt->execute(
                array(
                    $customer->id,
                    $customer->get('Customer Main Plain Email')
                )
            )) {
                if ($row = $stmt->fetch()) {
                    $password = $row['User Password'];
                } else {
                    $password = sha1(date('U').mt_rand(1, 100000));

                    $customer->fast_update(
                        array(
                            'Customer Metadata' => 'NoWebUser'
                        )
                    );

                   // print "Error Customer ".$customer->id.' '.$website->get('Code')."\n";

                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit();
            }


            $user_data['Website User Handle']       = $customer->get('Customer Main Plain Email');
            $user_data['Website User Customer Key'] = $customer->id;
            $user_data['Website User Password']     = $password;
            $user_data['Website User Website Key']  = $website->id;


            // print_r($user_data);

            $website_user = $website->create_user($user_data);


        } else {

            print "Customer ".$customer->id.' '.$website->get('Code')." no email  \n";

        }

    }

}



