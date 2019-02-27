<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  8 January 2019 at 16:08:59 WITA+0800, Kuta, Bali, Indonesia
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


$sql = sprintf('select `Customer Key` from `Customer Dimension` where `Customer Website User Key`=0  or `Customer Website User Key` is null  ');
if ($result2 = $db->query($sql)) {
    foreach ($result2 as $row2) {
        $customer = get_object('Customer', $row2['Customer Key']);


        if ($customer->get('Customer Main Plain Email') != '') {

            $sql = sprintf('select `Website User Key`,`Website User Customer Key` from `Website User Dimension` where `Website User Handle`=?  ');

            $stmt = $db->prepare($sql);
            if ($stmt->execute(
                array(
                    $customer->get('Customer Main Plain Email')
                )
            )) {
                if ($row = $stmt->fetch()) {

                } else {

                    print $customer->id.' '.$customer->get('Customer Main Plain Email')."\n";


                    $store = get_object('store', $customer->get('Customer Store Key'));


                    if ($store->get('Store Version') == 2) {
                        $website = get_object('website', $store->get('Store Website Key'));


                        $user_data['Website User Handle']       = $customer->get('Customer Main Plain Email');
                        $user_data['Website User Customer Key'] = $customer->id;
                        $user_data['Website User Password']     = sha1(date('U').'dadasda');
                        $user_data['Website User Website Key']  = $website->id;


                        $website_user = $website->create_user($user_data);

                        include_once 'utils/new_fork.php';

                        global $account;


                        $customer->update(array('Customer Website User Key' => $website_user->id), 'no_history');


                        new_housekeeping_fork(
                            'au_housekeeping', array(
                            'type'             => 'customer_created',
                            'customer_key'     => $customer->id,
                            'website_user_key' => $website_user->id,
                            'editor'           => $editor
                        ), $account->get('Account Code')
                        );
                    }


                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit();
            }

        }


    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


?>
