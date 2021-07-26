<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  29 April 2020  23:33::43  +0800 Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


$account = get_object('Account', 1);
$account->load_properties();

$account->fast_update_json_field(
    'Account Properties', 'part_label_unit', json_encode(
    array(

        'with_image'             => false,
        'with_weight'            => false,
        'with_origin'            => true,
        'with_ingredients'       => false,
        'with_custom_text'       => false,
        'with_account_signature' => true,
        'size'                   => 'EU30161',
        'set_up'                 => 'single',
        'custom_text'            => '',
        'with_borders'           => true


    )

), 'Account Data'

);

$account->fast_update_json_field(
    'Account Properties', 'part_label_sko', json_encode(
    array(

        'with_image'             => false,
        'with_weight'            => false,
        'with_origin'            => true,
        'with_ingredients'       => false,
        'with_custom_text'       => false,
        'with_account_signature' => true,
        'size'                   => 'EU30161',
        'set_up'                 => 'single',
        'custom_text'            => '',
        'with_borders'           => true


    )

), 'Account Data'

);

$account->fast_update_json_field(
    'Account Properties', 'part_label_carton', json_encode(
    array(

        'with_image'             => false,
        'with_weight'            => false,
        'with_false'             => false,
        'with_ingredients'       => true,
        'with_custom_text'       => false,
        'with_account_signature' => false,
        'size'                   => 'EU30036',
        'set_up'                 => 'single',
        'custom_text'            => '',
        'with_borders'           => true


    )

), 'Account Data'

);