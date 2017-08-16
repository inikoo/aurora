<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 October 2015 at 14:17:09 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_invoice_showcase($data,$smarty, $user,$db) {

    global $account;

    if (!$data['_object']->id) {
        return "";
    }

    $smarty->assign('invoice', $data['_object']);

    $smarty->assign('order',get_object('order', $data['_object']->get('Invoice Order Key')));


    $smarty->assign('user', $user);

    $invoice = $data['_object'];

    $tax_data = array();
    $sql      = sprintf(
        "SELECT `Tax Category Name`,`Tax Category Rate`,`Tax Amount`  FROM  `Invoice Tax Bridge` B  LEFT JOIN kbase.`Tax Category Dimension` T ON (T.`Tax Category Code`=B.`Tax Code`)  WHERE B.`Invoice Key`=%d  and `Tax Category Country Code`=%s  ",
        $invoice->id,
        prepare_mysql($account->get('Account Country Code'))
    );


    if ($result=$db->query($sql)) {
    		foreach ($result as $row) {
                $tax_data[] = array(
                    'name'   => $row['Tax Category Name'],
                    'amount' => money(
                        $row['Tax Amount'], $invoice->data['Invoice Currency']
                    )
                );
    		}
    }else {
    		print_r($error_info=$db->errorInfo());
    		print "$sql\n";
    		exit;
    }





    $smarty->assign('tax_data', $tax_data);

    return $smarty->fetch('showcase/invoice.tpl');


}


?>
