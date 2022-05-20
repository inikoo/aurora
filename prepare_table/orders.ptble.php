<?php



$group_by = '';
$wheref   = '';

$currency = '';


$where = 'where true" ';
$table = '`Order Dimension` O left join `Payment Account Dimension` P on (P.`Payment Account Key`=O.`Order Payment Account Key`)';


if (isset($parameters['awhere']) and $parameters['awhere']) {
    $tmp = preg_replace('/\\\"/', '"', $awhere);
    $tmp = preg_replace('/\\\\\"/', '"', $tmp);
    $tmp = preg_replace('/\'/', "\'", $tmp);

    $raw_data              = json_decode($tmp, true);
    $raw_data['store_key'] = $parameters['parent_key'];
    //print_r( $raw_data);exit;
    list($where, $table) = orders_awhere($raw_data);

    $where_interval = '';
} elseif ($parameters['parent'] == 'list') {
    $where_interval = '';

    $sql = sprintf(
        "SELECT * FROM `List Dimension` WHERE `List Key`=%d", $parameters['parent_key']
    );
    //print $sql;
    $res = mysql_query($sql);
    if ($list_data = mysql_fetch_assoc($res)) {
        $awhere = false;
        if ($list_data['List Type'] == 'Static') {
            $table
                   = '`List Order Bridge` OB left join `Order Dimension` O  on (OB.`Order Key`=O.`Order Key`)';
            $where = sprintf(
                ' where `List Key`=%d ', $parameters['parent_key']
            );

        } else {// Dynamic by DEFAULT


            $tmp = preg_replace('/\\\"/', '"', $list_data['List Metadata']);
            $tmp = preg_replace('/\\\\\"/', '"', $tmp);
            $tmp = preg_replace('/\'/', "\'", $tmp);

            $raw_data = json_decode($tmp, true);

            $raw_data['store_key'] = $parameters['parent_key'];
            list($where, $table) = orders_awhere($raw_data);


        }

    } else {
        exit("error parent not found: ".$parameters['parent']);
    }
} elseif ($parameters['parent'] == 'store') {
    if (is_numeric($parameters['parent_key'])  and ($user->can_view('stores') or  $user->can_view('accounting'))  ) {

        $where = sprintf(
            ' where  `Order Store Key`=%d ', $parameters['parent_key']
        );
        if (!isset($store)) {
            include_once 'class.Store.php';
            $store = new Store($parameters['parent_key']);
        }
        $currency = $store->data['Store Currency Code'];
    } else {
        $where .= sprintf(' and  false');
    }


} elseif ($parameters['parent'] == 'account') {

        if (count($user->stores) == 0) {
            $where = ' where false';
        } else {

            if($user->stores==''){
                $where = 'where false';
            }else{
                $where = sprintf('where  `Order Store Key` in (%s)  ', join(',', $user->stores));

            }


        }





} elseif ($parameters['parent'] == 'customer') {


    include_once 'class.Customer.php';
    $customer = new Customer($parameters['parent_key']);

    if (!$customer->id) {
        $where = ' where false';
    } else {

        if (in_array($customer->get('Customer Store Key'), $user->stores)) {
            $where = sprintf(
                'where  `Order Customer Key`=%d  ', $parameters['parent_key']
            );
        } else {
            $where = ' where false';
        }

    }
}elseif ($parameters['parent'] == 'customer_client') {


    $customer_client = get_object('Customer_Client',$parameters['parent_key']);

    if (!$customer_client->id) {
        $where = ' where false';
    } else {

        if (in_array($customer_client->get('Customer Client Store Key'), $user->stores)) {
            $where = sprintf(
                'where  `Order Customer Client Key`=%d  ', $parameters['parent_key']
            );
        } else {
            $where = ' where false';
        }

    }
} elseif ($parameters['parent'] == 'campaign') {
    $table
           = '`Order Dimension` O left join `Payment Account Dimension` P on (P.`Payment Account Key`=O.`Order Payment Account Key`) left join `Order Deal Bridge` DB on (DB.`Order Key`=O.`Order Key`)';
    $where = sprintf(
        'where `Deal Campaign Key`=%d  ', $parameters['parent_key']
    );
} elseif ($parameters['parent'] == 'deal') {
    $table = '`Order Dimension` O left join `Payment Account Dimension` P on (P.`Payment Account Key`=O.`Order Payment Account Key`) left join `Order Deal Bridge` DB on (DB.`Order Key`=O.`Order Key`)';
    $where = sprintf('where `Deal Key`=%d  ', $parameters['parent_key']);
} elseif ($parameters['parent'] == 'product') {


    $table
        = '`Order Transaction Fact` OTF  left join     `Order Dimension` O   on (OTF.`Order Key`=O.`Order Key`)   left join `Payment Account Dimension` P on (P.`Payment Account Key`=O.`Order Payment Account Key`)';

    $where = sprintf(' where  `Product ID`=%d ', $parameters['parent_key']);

    $group_by = ' group by OTF.`Order Key` ';


} elseif ($parameters['parent'] == 'customer_product') {

    $parent_keys=preg_split('/\_/',$parameters['parent_key']);

    $table
        = '`Order Transaction Fact` OTF  left join     `Order Dimension` O   on (OTF.`Order Key`=O.`Order Key`)   left join `Payment Account Dimension` P on (P.`Payment Account Key`=O.`Order Payment Account Key`)';

    $where = sprintf(' where   `Customer Key`=%d  and `Product ID`=%d ', $parent_keys[0],$parent_keys[1]);

    //print $where;

    $group_by = ' group by OTF.`Order Key` ';


} elseif ($parameters['parent'] == 'delivery_note') {


    $table
        = '`Order Delivery Note Bridge` B left join   `Order Dimension` O  on (O.`Order Key`=B.`Order Key`)     left join `Payment Account Dimension`   P on (P.`Payment Account Key`=O.`Order Payment Account Key`)';

    $where = sprintf(
        ' where  `Delivery Note Key`=%d ', $parameters['parent_key']
    );


} elseif ($parameters['parent'] == 'invoice') {


    $table
        = '`Order Invoice Bridge` B left join   `Order Dimension` O  on (O.`Order Key`=B.`Order Key`)     left join `Payment Account Dimension`   P on (P.`Payment Account Key`=O.`Order Payment Account Key`)';

    $where = sprintf(' where  `Invoice Key`=%d ', $parameters['parent_key']);


} elseif ($parameters['parent'] == 'charge') {



    $table
        = '`Order No Product Transaction Fact` OTF  left join     `Order Dimension` O   on (OTF.`Order Key`=O.`Order Key`)   left join `Payment Account Dimension` P on (P.`Payment Account Key`=O.`Order Payment Account Key`)';

    $where = sprintf(' where  `Transaction Type` in ("Charges","Premium") and `Transaction Type Key`=%d   and `Order State`!="InBasket" ', $parameters['parent_key']);

    $group_by = ' group by OTF.`Order Key` ';


} else {
    exit("unknown parent\n");
}



if (isset($parameters['period'])) {
    include_once 'utils/date_functions.php';
    list($db_interval, $from, $to, $from_date_1yb, $to_1yb)
        = calculate_interval_dates(
        $db, $parameters['period'], $parameters['from'], $parameters['to']
    );

    $where_interval = prepare_mysql_dates($from, $to, 'O.`Order Date`');
    $where .= $where_interval['mysql'];
}

if (isset($parameters['elements_type'])) {



    switch ($parameters['elements_type']) {


        case('state'):
            $_elements            = '';
            $num_elements_checked = 0;
            foreach (
                $parameters['elements']['state']['items'] as $_key => $_value
            ) {
                $_value = $_value['selected'];
                if ($_value) {
                    $num_elements_checked++;

                    if($_key=='PackedDone'){
                        $_elements .= ", 'PackedDone','Packed'";

                    }else{
                        $_elements .= ", '$_key'";

                    }

                }
            }

            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($num_elements_checked == 7) {

            } else {
                $_elements = preg_replace('/^,/', '', $_elements);
                $where .= ' and `Order State` in ('.$_elements.')';
            }
            break;
        case('source'):
            $_elements            = '';
            $num_elements_checked = 0;
            foreach (
                $parameters['elements']['source']['items'] as $_key => $_value
            ) {
                $_value = $_value['selected'];
                if ($_value) {
                    $num_elements_checked++;

                    $_elements .= ", '$_key'";
                }
            }

            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($num_elements_checked == 6) {

            } else {
                $_elements = preg_replace('/^,/', '', $_elements);
                $where .= ' and `Order Main Source Type` in ('.$_elements.')';
            }
            break;
        case('type'):
            $_elements            = '';
            $num_elements_checked = 0;
            foreach (
                $parameters['elements']['type']['items'] as $_key => $_value
            ) {
                $_value = $_value['selected'];
                if ($_value) {
                    $num_elements_checked++;

                    $_elements .= ", '$_key'";
                }
            }

            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($num_elements_checked == 6) {

            } else {
                $_elements = preg_replace('/^,/', '', $_elements);
                $where .= ' and `Order Type` in ('.$_elements.')';
            }
            break;
        case('payment'):
            $_elements            = '';
            $num_elements_checked = 0;

            //'Waiting Payment','Paid','Partially Paid','Unknown','No Applicable'

            foreach (
                $parameters['elements']['payment']['items'] as $_key => $_value
            ) {
                $_value = $_value['selected'];
                if ($_value) {
                    $num_elements_checked++;



                    $_elements .= ", '$_key'";
                }
            }

            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($num_elements_checked == 4) {

            } else {
                $_elements = preg_replace('/^,/', '', $_elements);
                $where .= ' and `Order Payment State` in ('.$_elements.')';
            }
            break;
    }
}



if (($parameters['f_field'] == 'customer') and $f_value != '') {
    $wheref = sprintf(
        '  and  `Order Customer Name`  REGEXP "\\\\b%s" ', addslashes($f_value)
    );
} elseif (($parameters['f_field'] == 'postcode') and $f_value != '') {
    $wheref = "  and  `Customer Main Plain Postal Code` like '%".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'number' and $f_value != '') {
    $wheref = " and  `Order Public ID`  like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'maxvalue' and is_numeric($f_value)) {
    $wheref = " and  `Order Invoiced Balance Total Amount`<=".$f_value."    ";
} elseif ($parameters['f_field'] == 'minvalue' and is_numeric($f_value)) {
    $wheref = " and  `Order Invoiced Balance Total Amount`>=".$f_value."    ";
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
    $order = 'O.`Order State`';
} elseif ($order == 'payment_state') {
    $order = 'O.`Order Payment State`';
} elseif ($order == 'state') {
    $order = 'O.`Order State`';
} elseif ($order == 'total_amount') {
    $order = 'O.`Order Total Amount`';
} elseif ($order == 'margin') {
    $order = 'O.`Order Margin`';
} else {
    $order = 'O.`Order Key`';
}



$fields
    = ' `Order Checkout Block Payment Account Key`,  `Order Customer Client Key`,"" as `Customer Client Code` ,`Order Profit Amount`,`Order Margin`,`Order State`,`Order Number Items`,`Order Store Key`,`Payment Account Name`,`Order Payment Method`,`Order Balance Total Amount`,`Order Payment State`,`Order State`,`Order Out of Stock Net Amount`,`Order Invoiced Total Net Adjust Amount`,`Order Invoiced Total Tax Adjust Amount`,FORMAT(`Order Invoiced Total Net Adjust Amount`+`Order Invoiced Total Tax Adjust Amount`,2) as `Order Adjust Amount`,`Order Out of Stock Net Amount`,`Order Out of Stock Tax Amount`,FORMAT(`Order Out of Stock Net Amount`+`Order Out of Stock Tax Amount`,2) as `Order Out of Stock Amount`,`Order Invoiced Balance Total Amount`,`Order Type`,`Order Currency Exchange`,`Order Currency`,O.`Order Key`,O.`Order Public ID`,`Order Customer Key`,`Order Customer Name`,O.`Order Last Updated Date`,O.`Order Date`,`Order Total Amount` ,`Order Current XHTML Payment State`';

$sql_totals = "select count(Distinct O.`Order Key`) as num from $table $where";
//$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
//print $sql;





