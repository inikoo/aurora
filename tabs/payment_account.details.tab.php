<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 3 November 2015 at 23:34:26 CET Tessera Italy
 Copyright (c) 2015, Inikoo

 Version 3

*/

$payment_account = $state['_object'];

$object_fields = array(
    array(
        'label'      => _('Id'),
        'show_title' => true,
        'fields'     => array(


            array(
                'class' => 'string',
                'id'    => 'Payment_Account_Key',
                'value' => $payment_account->get('Payment Account Key'),
                'label' => _('Id')
            ),
            array(
                'class' => 'string',
                'id'    => 'Payment_Account_Code',
                'value' => $payment_account->get('Payment Account Code'),
                'label' => _('Code')
            ),
            array(
                'class' => 'string',
                'id'    => 'Payment_Account_Name',
                'value' => $payment_account->get('Payment Account Name'),
                'label' => _('Name')
            ),

        )
    ),


);
$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);

$html = $smarty->fetch('edit_object.tpl');

?>
