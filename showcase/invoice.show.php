<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 19 October 2015 at 14:17:09 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_invoice_showcase($data) {

    global $smarty, $user;

    if (!$data['_object']->id) {
        return "";
    }

    $smarty->assign('invoice', $data['_object']);
    $smarty->assign('user', $user);

    $invoice = $data['_object'];

    $tax_data = array();
    $sql      = sprintf(
        "SELECT `Tax Category Name`,`Tax Category Rate`,`Tax Amount` FROM  `Invoice Tax Bridge` B  LEFT JOIN `Tax Category Dimension` T ON (T.`Tax Category Code`=B.`Tax Code`)  WHERE B.`Invoice Key`=%d ",
        $invoice->id
    );

    $res = mysql_query($sql);
    while ($row = mysql_fetch_assoc($res)) {
        $tax_data[] = array(
            'name'   => $row['Tax Category Name'],
            'amount' => money(
                $row['Tax Amount'], $invoice->data['Invoice Currency']
            )
        );
    }

    $smarty->assign('tax_data', $tax_data);

    return $smarty->fetch('showcase/invoice.tpl');


}


?>
