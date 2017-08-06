<?php





$group_by = '';
$wheref   = '';

$currency = '';


$where = 'where `Order Class`="InProcess" ';
$table = '`Order Dimension` O left join `Payment Account Dimension` P on (P.`Payment Account Key`=O.`Order Payment Account Key`)';


if ($parameters['parent'] == 'store') {
    if (is_numeric($parameters['parent_key']) and in_array(
            $parameters['parent_key'], $user->stores
        )
    ) {
        $where .= sprintf(' and  `Order Store Key`=%d ', $parameters['parent_key']);
        if (!isset($store)) {
            include_once 'class.Store.php';
            $store = new Store($parameters['parent_key']);
        }
        $currency = $store->data['Store Currency Code'];
    } else {
        $where .= sprintf(' and  false');
    }


} elseif ($parameters['parent'] == 'account') {
    if (is_numeric($parameters['parent_key']) and in_array(
            $parameters['parent_key'], $user->stores
        )
    ) {

        if (count($user->stores) == 0) {
            $where .= ' and false';
        } else {

            $where .= sprintf('and  `Order Store Key` in (%s)  ', join(',', $user->stores));
        }
    }
}


if (isset($parameters['elements_type'])) {


    switch ($parameters['elements_type']) {

        case ('flow'):

            $num_elements_checked = 0;


            $_where = '';

            foreach ($parameters['elements']['flow']['items'] as $_key => $_value) {
                $_value = $_value['selected'];
                if ($_value) {
                    $num_elements_checked++;

                    if ($_key == 'Basket') {
                        $_where .= " or (`Order Number Items`>0 AND `Order Current Dispatch State`  IN ('In Process') ) ";

                    }
                    if ($_key == 'Submitted_Unpaid') {
                        $_where .= " or (`Order Current Dispatch State`='Submitted by Customer'  AND `Order Current Payment State`!='Paid' ) ";

                    }
                    if ($_key == 'Submitted_Paid') {
                        $_where .= " or (`Order Current Dispatch State`='Submitted by Customer'  AND `Order Current Payment State`='Paid' ) ";

                    }

                    if ($_key == 'InWarehouse') {
                        $_where .= " or (`Order Current Dispatch State` in ('Ready to Pick', 'Picking & Packing', 'Ready to Ship', 'Packing')  ) ";

                    }

                    if ($_key == 'Packed') {
                        $_where .= " or (`Order Current Dispatch State` in ('Packed')  ) ";

                    }


                    if ($_key == 'Dispatch_Ready') {
                        $_where .= " or (`Order Current Dispatch State` in ('Packed Done')  ) ";

                    }

                    if ($_key == 'Dispatched_Today') {
                        $_where .= sprintf(
                            " or (  `Order Current Dispatch State`='Dispatched' and `Order Dispatched Date`>%s   and  `Order Dispatched Date`<%s   ) ", prepare_mysql(date('Y-m-d 00:00:00')),
                            prepare_mysql(date('Y-m-d 23:59:59'))
                        );

                    }


                }
            }


            if ($num_elements_checked == 0) {
                $where = ' where false';

            } else {

                $_where = preg_replace('/^ or/', '', $_where);

                $where .= 'and ( '.$_where.')';

            }

            // print $where;

            break;

    }
}


if (($parameters['f_field'] == 'customer') and $f_value != '') {
    $wheref = sprintf(
        '  and  `Order Customer Name`  REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
}  elseif ($parameters['f_field'] == 'number' and $f_value != '') {
    $wheref = " and  `Order Public ID`  like '".addslashes($f_value)."%'";
}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'public_id') {
    $order = '`Order File As`';
} elseif ($order == 'last_date' or $order == 'date') {
    $order = 'O.`Order Date`';
} elseif ($order == 'customer') {
    $order = 'O.`Order Customer Name`';
} elseif ($order == 'dispatch_state') {
    $order = 'O.`Order Current Dispatch State`';
} elseif ($order == 'payment_state') {
    $order = 'O.`Order Current Payment State`';
} elseif ($order == 'total_amount') {
    $order = 'O.`Order Total Amount`';
} else {
    $order = 'O.`Order Key`';
}

$fields
    = '`Order Invoiced`,`Order Number Items`,`Order Store Key`,`Payment Account Name`,`Order Payment Method`,`Order Balance Total Amount`,`Order Current Payment State`,`Order Current Dispatch State`,`Order Type`,`Order Currency Exchange`,`Order Currency`,O.`Order Key`,O.`Order Public ID`,`Order Customer Key`,`Order Customer Name`,O.`Order Last Updated Date`,O.`Order Date`,`Order Total Amount`,
     (select group_concat(`Delivery Note Key`) from `Delivery Note Dimension` where `Delivery Note Order Key`=O.`Order Key`   ) as delivery_notes
    
    
    ';

$sql_totals = "select count(Distinct O.`Order Key`) as num from $table $where";
//$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
//print $sql;


?>
