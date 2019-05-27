<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 May 2018 at 14:43:24 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/natural_language.php';
require_once 'utils/order_functions.php';

require_once 'class.Category.php';




$sql = sprintf("SELECT `Deal Component Key` FROM `Deal Component Dimension` ");

if ($result = $db->query($sql)) {
    foreach ($result as $row) {


        $deal_component = get_object('DealComponent', $row['Deal Component Key']);

        if ($deal_component->get('Deal Component Allowance Type') == 'Get Free' and ($deal_component->get('Deal Component Allowance Target') == 'Charge' or is_numeric($deal_component->get('Deal Component Allowance')))) {


            if ($deal_component->get('Deal Component Allowance Target') == 'Charge') {


                $charge_key = 0;
                $sql        = sprintf('select `Charge Key` from `Charge Dimension` where `Charge Scope`="Hanging" and `Charge Store Key`=%d  ', $deal_component->get('Store Key'));


                //print "$sql\n";

                if ($result2 = $db->query($sql)) {
                    foreach ($result2 as $row2) {
                        $charge_key = $row2['Charge Key'];
                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                $allowances = json_encode(
                    array(
                        'object' => 'Charge',
                        'key'    => $charge_key,

                    )
                );


                if (!$charge_key) {

                    $sql = sprintf('delete from `Deal Component Dimension` where `Deal Component Key`=%d     ', $deal_component->id);
                    // print "$sql\n";
                    $db->exec($sql);


                } else {
                    print $allowances."\n";


                    $deal_component->fast_update(
                        array(
                            'Deal Component Allowance' => $allowances
                        )
                    );

                    $deal_component->update_deal_component_term_allowances();
                }


            } else {

                $allowances = json_encode(
                    array(
                        'qty'    => $deal_component->get('Deal Component Allowance'),
                        'object' => $deal_component->get('Deal Component Allowance Target'),
                        'key'    => $deal_component->get('Deal Component Allowance Target Key'),

                    )
                );


                print $allowances."\n";


                $deal_component->fast_update(
                    array(
                       'Deal Component Allowance' => $allowances
                   )
                );

                 $deal_component->update_deal_component_term_allowances();

            }


        }


    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}

