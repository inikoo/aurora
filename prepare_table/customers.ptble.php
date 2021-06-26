<?php

$currency = '';
$where    = 'where true';
$table    = '`Customer Dimension` C ';
$group_by = '';


$fields = ' *,`Customer Net Refunds`+`Customer Tax Refunds` as `Customer Total Refunds`';


//print_r($parameters);

if (isset($parameters['awhere']) and $parameters['awhere']) {

    $tmp = preg_replace('/\\\"/', '"', $parameters['awhere']);
    $tmp = preg_replace('/\\\\\"/', '"', $tmp);
    $tmp = preg_replace('/\'/', "\'", $tmp);

    $raw_data              = json_decode($tmp, true);
    $raw_data['store_key'] = $parameters['parent_key'];
    include_once 'list_functions_customer.php';
    list($where, $table, $group_by) = customers_awhere($raw_data);


} elseif ($parameters['parent'] == 'list') {


    $sql = sprintf(
        "SELECT `List Type`,`List Metadata`,`List Parent Key` FROM `List Dimension` WHERE `List Key`=%d", $parameters['parent_key']
    );

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            if ($row['List Type'] == 'Static') {
                $table = '`List Customer Bridge` CB left join `Customer Dimension` C  on (CB.`Customer Key`=C.`Customer Key`)';
                $where = sprintf(
                    ' where `List Key`=%d ', $parameters['parent_key']
                );

            } else {


                include_once 'utils/parse_customer_list.php';

                $_data=json_decode($row['List Metadata'], true);
                $_data['store_key']=$row['List Parent Key'];

                list($table, $where,$group_by) = parse_customer_list($_data,$db);

                $where=sprintf(' where `Customer Store Key`=%d ',$row['List Parent Key']).$where;

            }
        }
    }


} elseif ($parameters['parent'] == 'product_category') {

    include_once 'class.Category.php';
    $category = new Category($parameters['parent_key']);

    if (!in_array($category->data['Category Store Key'], $user->stores)) {
        return;
    }

    $store=get_object('store',$category->get('Store Key'));

    if($store->get('Store Family Category Key')==$category->get('Category Root Key')){



        $table = '`Order Transaction Fact` OTF  left join `Customer Dimension` C on (OTF.`Customer Key`=C.`Customer Key`)  left join `Product Dimension` P on (OTF.`Product ID`=P.`Product ID`)  ';

        $where = sprintf(' where  P.`Product Family Category Key`=%d ', $parameters['parent_key']);



    }elseif($store->get('Store Department Category Key')==$category->get('Category Root Key')){

    }else{

    }



} elseif ($parameters['parent'] == 'category') {

    include_once 'class.Category.php';
    $category = new Category($parameters['parent_key']);

    if (!in_array($category->data['Category Store Key'], $user->stores)) {
        return;
    }

    $where = sprintf(
        " where `Subject`='Customer' and  `Category Key`=%d", $parameters['parent_key']
    );
    $table = ' `Category Bridge` left join  `Customer Dimension` C on (`Subject Key`=`Customer Key`) ';




} elseif ($parameters['parent'] == 'store') {
    include_once('class.Store.php');

    if (in_array($parameters['parent_key'], $user->stores)) {
        $where_stores = sprintf(
            ' and  `Customer Store Key`=%d ', $parameters['parent_key']
        );
    } else {
        $where_stores = ' and false';
    }



    $store    = new Store($parameters['parent_key']);
    $currency = $store->data['Store Currency Code'];
    $where    .= $where_stores;
} elseif ($parameters['parent'] == 'campaign') {
    $table = '`Order Dimension` O  left join `Order Deal Bridge` DB on (DB.`Order Key`=O.`Order Key`) left join `Customer Dimension` C on (`Order Customer Key`=C.`Customer Key`) ';

    $where = sprintf(
        ' where  `Deal Campaign Key`=%d ', $parameters['parent_key']
    );
    include_once('class.DealCampaign.php');
    include_once('class.Store.php');

    $campaign = new DealCampaign($parameters['parent_key']);
    $store    = new Store($campaign->get('Deal Campaign Store Key'));
    $currency = $store->get('Store Currency Code');

} elseif ($parameters['parent'] == 'deal') {
    $table = '`Order Dimension` O  left join `Order Deal Bridge` DB on (DB.`Order Key`=O.`Order Key`) left join `Customer Dimension` C on (`Order Customer Key`=C.`Customer Key`) ';

    $where = sprintf(' where  `Deal Key`=%d ', $parameters['parent_key']);
    include_once('class.Deal.php');
    include_once('class.Store.php');

    $deal     = new Deal($parameters['parent_key']);
    $store    = new Store($deal->get('Deal Store Key'));
    $currency = $store->get('Store Currency Code');

} elseif ($parameters['parent'] == 'product') {
    $table = '`Order Transaction Fact` OTF  left join `Customer Dimension` C on (OTF.`Customer Key`=C.`Customer Key`) ';

    $where = sprintf(' where  `Product ID`=%d ', $parameters['parent_key']);


} elseif ($parameters['parent'] == 'sales_representative') {

    $where = sprintf(' where  `Customer Sales Representative Key`=%d ', $parameters['parent_key']);


} elseif ($parameters['parent'] == 'charge') {
    $table = '`Order No Product Transaction Fact` OTF left join     `Order Dimension` O   on (OTF.`Order Key`=O.`Order Key`)  left join `Customer Dimension` C on (`Order Customer Key`=C.`Customer Key`)  ';

    $where = sprintf(' where  `Transaction Type` in ("Charges","Premium") and `Transaction Type Key`=%d   and `Order State`!="InBasket" ', $parameters['parent_key']);


    $group_by = ' group by `Order Customer Key` ';


} elseif ($parameters['parent'] == 'favourites') {

    $table = ' `Customer Favourite Product Fact` F  left join `Customer Dimension` C   on (C.`Customer Key`=F.`Customer Favourite Product Customer Key`)  ';


    $where .= sprintf(' and  F.`Customer Favourite Product Product ID`=%d ', $parameters['parent_key']);



    $group_by = 'group by F.`Customer Favourite Product Customer Key`';

    $fields = ' *,`Customer Net Refunds`+`Customer Tax Refunds` as `Customer Total Refunds`';

} elseif ($parameters['parent'] == 'customer_poll_query_option') {
    $table = '`Customer Poll Fact` CPF  left join `Customer Dimension` C on (CPF.`Customer Poll Customer Key`=C.`Customer Key`) ';

    $where = sprintf(' where  `Customer Poll Query Option Key`=%d ', $parameters['parent_key']);


} else {

    if (count($user->stores) == 0) {
        $where_stores = sprintf(' and  false');
    } else {
        $where_stores = sprintf(
            ' and `Customer Store Key` in (%s)  ', join(',', $user->stores)
        );
    }
    $where .= $where_stores;
}


switch ($parameters['elements_type']) {
    case 'orders':
        $_elements      = '';
        $count_elements = 0;
        foreach (
            $parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value
        ) {
            if ($_value['selected']) {
                $count_elements++;
                $_elements .= ','.prepare_mysql($_key);

            }
        }
        $_elements = preg_replace('/^\,/', '', $_elements);
        if ($_elements == '') {
            $where .= ' and false';
        } elseif ($count_elements == 1) {
            $where .= ' and `Customer With Orders`='.$_elements.'';
        }
        break;
    case 'activity':
        $_elements      = '';
        $count_elements = 0;
        foreach (
            $parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value
        ) {
            if ($_value['selected']) {
                $count_elements++;
                $_elements .= ','.prepare_mysql($_key);

            }
        }
        $_elements = preg_replace('/^\,/', '', $_elements);
        if ($_elements == '') {
            $where .= ' and false';
        } elseif ($count_elements < 5) {
            $where .= ' and `Customer Type by Activity` in ('.$_elements.')';
        }
        break;
    case 'type':
        $_elements      = '';
        $count_elements = 0;
        foreach (
            $parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value
        ) {
            if ($_value['selected']) {
                $count_elements++;
                $_elements .= ','.prepare_mysql($_key);

            }
        }
        $_elements = preg_replace('/^\,/', '', $_elements);
        if ($_elements == '') {
            $where .= ' and false';
        } elseif ($count_elements < 4) {
            $where .= ' and `Customer Level Type` in ('.$_elements.')';
        }
        break;
    case 'location':
        $_elements      = '';
        $count_elements = 0;
        foreach (
            $parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value
        ) {
            if ($_value['selected']) {
                $count_elements++;
                $_elements .= ','.prepare_mysql($_key);

            }
        }
        $_elements = preg_replace('/^\,/', '', $_elements);
        if ($_elements == '') {
            $where .= ' and false';
        } elseif ($count_elements < 2) {
            $where .= ' and `Customer Location Type` in ('.$_elements.')';
        }
        break;


}


$filter_msg = '';
$wheref     = '';


if (($parameters['f_field'] == 'name') and $f_value != '') {
    $wheref = sprintf(
        ' and `Customer Name` REGEXP "\\\\b%s" ', addslashes($f_value)
    );


} elseif (($parameters['f_field'] == 'postcode') and $f_value != '') {
    $wheref = "  and  `Customer Main Plain Postal Code` like '%".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'id') {
    $wheref .= " and  `Customer Key` like '".addslashes(
            preg_replace('/\s*|\,|\./', '', $f_value)
        )."%' ";
} elseif ($parameters['f_field'] == 'last_more' and is_numeric($f_value)) {
    $wheref .= " and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))>=".$f_value."    ";
} elseif ($parameters['f_field'] == 'last_less' and is_numeric($f_value)) {
    $wheref .= " and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))<=".$f_value."    ";
} elseif ($parameters['f_field'] == 'max' and is_numeric($f_value)) {
    $wheref .= " and  `Customer Orders`<=".$f_value."    ";
} elseif ($parameters['f_field'] == 'min' and is_numeric($f_value)) {
    $wheref .= " and  `Customer Orders`>=".$f_value."    ";
} elseif ($parameters['f_field'] == 'maxvalue' and is_numeric($f_value)) {
    $wheref .= " and  `Customer Net Balance`<=".$f_value."    ";
} elseif ($parameters['f_field'] == 'minvalue' and is_numeric($f_value)) {
    $wheref .= " and  `Customer Net Balance`>=".$f_value."    ";
}

$_order = $order;
$_dir   = $order_direction;
if ($order == 'name') {
    $order = '`Customer Name`';
} elseif ($order == 'formatted_id') {
    $order = 'C.`Customer Key`';
} elseif ($order == 'location') {
    $order = '`Customer Location`';
} elseif ($order == 'orders') {
    $order = '`Customer Orders`';
} elseif ($order == 'email') {
    $order = '`Customer Main Plain Email`';
} elseif ($order == 'telephone') {
    $order = '`Customer Main Plain Telephone`';
} elseif ($order == 'mobile') {
    $order = '`Customer Main Plain Mobile`';
} elseif ($order == 'last_order') {
    $order = '`Customer Last Order Date`';
} elseif ($order == 'last_invoice') {
    $order = '`Customer Last Invoiced Order Date`';
} elseif ($order == 'contact_name') {
    $order = '`Customer Main Contact Name`';
} elseif ($order == 'company_name') {
    $order = '`Customer Company Name`';
}elseif ($order == 'total_payments') {
    $order = '`Customer Payments Amount`';
} elseif ($order == 'total_invoiced_amount') {
    $order = '`Customer Invoiced Amount`';
} elseif ($order == 'total_invoiced_net_amount') {
    $order = '`Customer Invoiced Net Amount`';

} elseif ($order == 'customer_balance') {
    $order = '`Customer Account Balance`';
} elseif ($order == 'total_payments') {
    $order = '`Customer Net Payments`';
} elseif ($order == 'top_profits') {
    $order = '`Customer Profits Top Percentage`';
} elseif ($order == 'top_balance') {
    $order = '`Customer Balance Top Percentage`';
} elseif ($order == 'top_orders') {
    $order = '``Customer Orders Top Percentage`';
} elseif ($order == 'top_invoices') {
    $order = '``Customer Invoices Top Percentage`';
} elseif ($order == 'total_refunds') {
    $order = '`Customer Total Refunds`';
} elseif ($order == 'contact_since') {
    $order = '`Customer First Contacted Date`';
} elseif ($order == 'activity') {
    $order = '`Customer Type by Activity`';
} elseif ($order == 'logins') {
    $order = '`Customer Number Web Logins`';
} elseif ($order == 'failed_logins') {
    $order = '`Customer Number Web Failed Logins`';
} elseif ($order == 'requests') {
    $order = '`Customer Number Web Requests`';
} elseif ($order == 'invoices') {
    $order = '`Customer Number Invoices`';
} else {
    $order = '`Customer Name`';
}


$sql_totals = "select count(Distinct C.`Customer Key`) as num from $table  $where ";


