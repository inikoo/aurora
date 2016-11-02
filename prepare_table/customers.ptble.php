<?php

$currency = '';
$where    = 'where true';
$table    = '`Customer Dimension` C ';
$group_by = '';


$fields
    = ' *,`Customer Net Refunds`+`Customer Tax Refunds` as `Customer Total Refunds`';

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
        "SELECT * FROM `List Dimension` WHERE `List Key`=%d", $parameters['parent_key']
    );

    $res = mysql_query($sql);
    if ($customer_list_data = mysql_fetch_assoc($res)) {
        $parameters['awhere'] = false;
        if ($customer_list_data['List Type'] == 'Static') {
            $table
                   = '`List Customer Bridge` CB left join `Customer Dimension` C  on (CB.`Customer Key`=C.`Customer Key`)';
            $where = sprintf(
                ' where `List Key`=%d ', $parameters['parent_key']
            );

        } else {

            $tmp = preg_replace(
                '/\\\"/', '"', $customer_list_data['List Metadata']
            );
            $tmp = preg_replace('/\\\\\"/', '"', $tmp);
            $tmp = preg_replace('/\'/', "\'", $tmp);

            $raw_data = json_decode($tmp, true);

            $raw_data['store_key'] = $customer_list_data['List Parent Key'];
            include_once 'utils/list_functions_customer.php';

            list($where, $table, $group_by) = customers_awhere($raw_data);


        }

    } else {
        return;
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
    $table
           = ' `Category Bridge` left join  `Customer Dimension` C on (`Subject Key`=`Customer Key`) ';

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
    $where .= $where_stores;
} elseif ($parameters['parent'] == 'campaign') {
    $table
        = '`Order Dimension` O  left join `Order Deal Bridge` DB on (DB.`Order Key`=O.`Order Key`) left join `Customer Dimension` C on (`Order Customer Key`=C.`Customer Key`) ';

    $where = sprintf(
        ' where  `Deal Campaign Key`=%d ', $parameters['parent_key']
    );
    include_once('class.DealCampaign.php');
    include_once('class.Store.php');

    $campaign = new DealCampaign($parameters['parent_key']);
    $store    = new Store($campaign->get('Deal Campaign Store Key'));
    $currency = $store->get('Store Currency Code');

} elseif ($parameters['parent'] == 'deal') {
    $table
        = '`Order Dimension` O  left join `Order Deal Bridge` DB on (DB.`Order Key`=O.`Order Key`) left join `Customer Dimension` C on (`Order Customer Key`=C.`Customer Key`) ';

    $where = sprintf(' where  `Deal Key`=%d ', $parameters['parent_key']);
    include_once('class.Deal.php');
    include_once('class.Store.php');

    $deal     = new Deal($parameters['parent_key']);
    $store    = new Store($deal->get('Deal Store Key'));
    $currency = $store->get('Store Currency Code');

} elseif ($parameters['parent'] == 'favourites') {

    $table
        = '`Customer Favorite Product Bridge` F  left join `Customer Dimension` C   on (C.`Customer Key`=F.`Customer Key`)  ';

    if (in_array($parameters['parent_key'], $user->websites)) {
        $where .= sprintf(' and  `Site Key`=%d ', $parameters['parent_key']);
    } else {
        $where .= ' and false';
    }

    $group_by = 'group by F.`Customer Key`';

    $fields
        = ' *,`Customer Net Refunds`+`Customer Tax Refunds` as `Customer Total Refunds`';

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
        } elseif ($count_elements < 3) {
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
        ' and `Customer Name` REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );


} elseif (($parameters['f_field'] == 'postcode') and $f_value != '') {
    $wheref = "  and  `Customer Main Postal Code` like '%".addslashes($f_value)."%'";
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
} elseif ($parameters['f_field'] == 'country' and $f_value != '') {
    if ($f_value == 'UNK') {
        $wheref .= " and  `Customer Main Country Code`='".$f_value."'    ";
        $find_data = ' '._('a unknown country');
    } else {

        $f_value = Address::parse_country($f_value);
        if ($f_value != 'UNK') {
            $wheref .= " and  `Customer Main Country Code`='".$f_value."'    ";
            $country   = new Country('code', $f_value);
            $find_data = ' '.$country->data['Country Name'].' <img src="art/flags/'.$country->data['Country 2 Alpha Code'].'.png" alt="'.$country->data['Country Code'].'"/>';
        }

    }
}

$_order = $order;
$_dir   = $order_direction;
if ($order == 'name') {
    $order = '`Customer File As`';
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
    $order = '`Customer Last Invoiced Order Date';
} elseif ($order == 'contact_name') {
    $order = '`Customer Main Contact Name`';
} elseif ($order == 'company_name') {
    $order = '`Customer Company Name`';
} elseif ($order == 'address') {
    $order = '`Customer Main Plain Address`';
} elseif ($order == 'town') {
    $order = '`Customer Main Town`';
} elseif ($order == 'postcode') {
    $order = '`Customer Main Postal Code`';
} elseif ($order == 'region') {
    $order = '`Customer Main Country First Division`';
} elseif ($order == 'country') {
    $order = '`Customer Main Country`';
}
//  elseif($order=='ship_address')
//  $order='`customer main ship to header`';
elseif ($order == 'ship_town') {
    $order = '`Customer Main Delivery Address Town`';
} elseif ($order == 'ship_postcode') {
    $order = '`Customer Main Delivery Address Postal Code`';
} elseif ($order == 'ship_region') {
    $order = '`Customer Main Delivery Address Country Region`';
} elseif ($order == 'ship_country') {
    $order = '`Customer Main Delivery Address Country`';
} elseif ($order == 'net_balance') {
    $order = '`Customer Net Balance`';
} elseif ($order == 'balance') {
    $order = '`Customer Outstanding Net Balance`';
} elseif ($order == 'total_profit') {
    $order = '`Customer Profit`';
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
    $order = '`Customer Orders Invoiced`';
} else {
    $order = '`Customer File As`';
}


$sql_totals
    = "select count(Distinct C.`Customer Key`) as num from $table  $where ";


function customers_awhere($awhere) {
    // $awhere=preg_replace('/\\\"/','"',$awhere);
    //    print "$awhere";

    include_once('utils/awhere_functions.php');


    $where_data = array(
        'product_ordered1'         => '∀',
        'geo_constraints'          => '',
        'product_not_ordered1'     => '',
        'product_not_received1'    => '',
        'ordered_from'             => '',
        'ordered_to'               => '',
        'customer_created_from'    => '',
        'customer_created_to'      => '',
        'dont_have'                => array(),
        'have'                     => array(),
        'allow'                    => array(),
        'dont_allow'               => array(),
        'customers_which'          => array(),
        'not_customers_which'      => array(),
        'categories'               => '',
        'lost_customer_from'       => '',
        'lost_customer_to'         => '',
        'invoice_option'           => array(),
        'number_of_invoices_upper' => '',
        'number_of_invoices_lower' => '',
        'sales_lower'              => '',
        'sales_upper'              => '',
        'sales_option'             => array(),
        'logins_lower'             => '',
        'logins_upper'             => '',
        'logins_option'            => array(),

        'failed_logins_lower'  => '',
        'failed_logins_upper'  => '',
        'failed_logins_option' => array(),

        'requests_lower'                          => '',
        'requests_upper'                          => '',
        'requests_option'                         => array(),
        'store_key'                               => 0,
        'order_option'                            => array(),
        'order_time_units_since_last_order_qty'   => false,
        'order_time_units_since_last_order_units' => false,
        'pending_orders'                          => '',
        'pending_orders_days_no_change'           => false,
        'pending_orders_days_no_change_type'      => 'more_than',


        'pending_order_payment_method' => array()
    );

    //  $awhere=json_decode($awhere,TRUE);


    foreach ($awhere as $key => $item) {
        $where_data[$key] = $item;
    }


    $where = sprintf(
        'where  `Customer Store Key`=%d ', $where_data['store_key']
    );
    $table = '`Customer Dimension` C ';
    $group = '';

    //print_r($where_data);
    $use_product    = false;
    $use_categories = false;
    $use_otf        = false;
    $use_order      = false;
    $where_orders   = '';
    if ($where_data['pending_orders'] == 'Yes') {
        $use_order = true;
        //$where_orders=" and `Order Current Dispatch State` in ('In Process by Customer','In Process','Submitted by Customer','Ready to Pick','Picking & Packing','Ready to Ship','Packing','Packed','Packed Done')";
        $where_orders
            = " and `Order Current Dispatch State` in ('In Process by Customer')";

        $tmp = '';
        foreach ($where_data['pending_order_payment_method'] as $payment_method) {
            if ($payment_method != '') {
                $payment_method = addslashes($payment_method);
                $tmp .= "'$payment_method',";
            }


        }
        $tmp = preg_replace('/\,$/', '', $tmp);
        if ($tmp != '') {
            $where_orders .= " and `Order Payment Method` in ($tmp)";
        }

        $where_data['pending_orders_days_no_change'] = floatval(
            $where_data['pending_orders_days_no_change']
        );
        if (is_numeric($where_data['pending_orders_days_no_change']) and $where_data['pending_orders_days_no_change'] > 0) {
            if ($where_data['pending_orders_days_no_change_type'] == 'less_than') {
                $_basket_date = gmdate(
                    'Y-m-d H:i:s', strtotime(
                        "now -".$where_data['pending_orders_days_no_change']." days"
                    )
                );

                $where_orders .= sprintf(
                    " and `Order Date`>%s", prepare_mysql($_basket_date)
                );

            } else {
                $_basket_date = gmdate(
                    'Y-m-d H:i:s', strtotime(
                        "now -".$where_data['pending_orders_days_no_change']." days"
                    )
                );

                $where_orders .= sprintf(
                    " and `Order Date`<%s", prepare_mysql($_basket_date)
                );

            }
        }
    }


    $where_categories = '';
    if ($where_data['categories'] != '') {

        $categories_keys       = preg_split('/,/', $where_data['categories']);
        $valid_categories_keys = array();
        foreach ($categories_keys as $item) {

            $valid_categories_keys[] = "'".addslashes($item)."'";
        }
        $categories_keys = join($valid_categories_keys, ',');
        if ($categories_keys) {
            $use_categories   = true;
            $where_categories = sprintf(
                " and `Subject`='Customer' and `Category Code` in (%s)", $categories_keys
            );
        }


    }

    $where_geo_constraints = '';
    if ($where_data['geo_constraints'] != '') {
        $where_geo_constraints = extract_geo_groups(
            $where_data['geo_constraints']
        );
    }


    if ($where_data['product_ordered1'] == '') {
        $where_data['product_ordered1'] = '∀';
    }


    if ($where_data['product_ordered1'] != '') {
        if ($where_data['product_ordered1'] != '∀') {
            $use_otf = true;
            list($where_product_ordered1, $use_product)
                = extract_product_groups(
                $where_data['product_ordered1'], $where_data['store_key']
            );
        } else {
            $where_product_ordered1 = 'true';
        }
    } else {
        $where_product_ordered1 = 'false';
    }

    /*
        if ($where_data['product_not_ordered1']!='') {
            if ($where_data['product_not_ordered1']!='ALL') {
                $use_otf=true;
                $where_product_not_ordered1=extract_product_groups($where_data['product_ordered1'],'O.`Product Code` not like','transaction.product_id not like','OTF.`Product Family Key` not in ','O.`Product Family Key` like');
            } else
                $where_product_not_ordered1='false';
        } else
            $where_product_not_ordered1='true';

        if ($where_data['product_not_received1']!='') {
            if ($where_data['product_not_received1']!='∀') {
                $use_otf=true;
                $where_product_not_received1=extract_product_groups($where_data['product_ordered1'],'(ordered-dispatched)>0 and    product.code  like','(ordered-dispatched)>0 and  transaction.product_id not like','(ordered-dispatched)>0 and  product_group.name not like','(ordered-dispatched)>0 and  product_group.id like');
            } else {
                $use_otf=true;
                $where_product_not_received1=' ((ordered-dispatched)>0)  ';
            }
        } else
            $where_product_not_received1='true';

    */


    $date_interval_when_ordered = prepare_mysql_dates(
        $where_data['ordered_from'], $where_data['ordered_to'], '`Order Date`', 'only_dates'
    );
    if ($date_interval_when_ordered['mysql']) {
        $use_otf = true;
    }


    $date_interval_when_customer_created = prepare_mysql_dates(
        $where_data['customer_created_from'], $where_data['customer_created_to'], '`Customer First Contacted Date`', 'only_dates'
    );
    if ($date_interval_when_customer_created['mysql']) {

    }

    $date_interval_lost_customer = prepare_mysql_dates(
        $where_data['lost_customer_from'], $where_data['lost_customer_to'], '`Customer Lost Date`', 'only_dates'
    );


    if ($use_otf) {
        $table
               = '`Customer Dimension` C  left join  `Order Transaction Fact` OTF  on (C.`Customer Key`=OTF.`Customer Key`)   ';
        $group = ' group by C.`Customer Key`';
    }
    if ($use_product) {
        $table
               = '`Customer Dimension` C  left join  `Order Transaction Fact` OTF  on (C.`Customer Key`=OTF.`Customer Key`) left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`)  ';
        $group = ' group by C.`Customer Key`';

    }

    if ($use_order) {
        $table
               = '`Customer Dimension` C  left join  `Order Dimension` O   on (C.`Customer Key`=O.`Order Customer Key`)  ';
        $group = ' group by C.`Customer Key`';


    }


    if ($use_categories) {

        $table .= '  left join   `Category Bridge` CatB on (C.`Customer Key`=CatB.`Subject Key`) left join `Category Dimension` Cat on (`Cat`.`Category Key`=CatB.`Category Key`)   ';
        $group = ' group by C.`Customer Key`';

    }


    $where .= ' and (  '.$where_product_ordered1.$date_interval_when_ordered['mysql'].$date_interval_when_customer_created['mysql'].$date_interval_lost_customer['mysql']
        .") $where_categories $where_geo_constraints $where_orders";

    foreach ($where_data['dont_have'] as $dont_have) {
        switch ($dont_have) {
            case 'tel':
                $where .= sprintf(
                    " and `Customer Main Telephone Key` IS NULL "
                );
                break;
            case 'email':
                $where .= sprintf(" and `Customer Main Email Key` IS NULL ");
                break;
            case 'fax':
                $where .= sprintf(" and `Customer Main Fax Key` IS NULL ");
                break;
            case 'address':
                $where .= sprintf(
                    " and `Customer Main Address Incomplete`='Yes' "
                );
                break;
        }
    }
    foreach ($where_data['have'] as $dont_have) {
        switch ($dont_have) {
            case 'tel':
                $where .= sprintf(
                    " and `Customer Main Telephone Key` IS NOT NULL "
                );
                break;
            case 'email':
                $where .= sprintf(
                    " and `Customer Main Email Key` IS NOT NULL "
                );
                break;
            case 'fax':
                $where .= sprintf(" and `Customer Main Fax Key` IS NOT NULL ");
                break;
            case 'address':
                $where .= sprintf(
                    " and `Customer Main Address Incomplete`='No' "
                );
                break;
        }
    }

    $allow_where = '';
    foreach ($where_data['allow'] as $allow) {
        switch ($allow) {
            case 'newsletter':
                $allow_where .= sprintf(
                    " or `Customer Send Newsletter`='Yes' "
                );
                break;
            case 'marketing_email':
                $allow_where .= sprintf(
                    " or `Customer Send Email Marketing`='Yes'  "
                );
                break;
            case 'marketing_post':
                $allow_where .= sprintf(
                    " or  `Customer Send Postal Marketing`='Yes'  "
                );
                break;

        }


    }
    $allow_where = preg_replace('/^\s*or/', '', $allow_where);
    if ($allow_where != '') {
        $where .= "and ($allow_where)";
    }

    $dont_allow_where = '';
    foreach ($where_data['dont_allow'] as $dont_allow) {
        switch ($dont_allow) {
            case 'newsletter':
                $dont_allow_where .= sprintf(
                    " or `Customer Send Newsletter`='No' "
                );
                break;
            case 'marketing_email':
                $dont_allow_where .= sprintf(
                    " or `Customer Send Email Marketing`='No'  "
                );
                break;
            case 'marketing_post':
                $dont_allow_where .= sprintf(
                    " or  `Customer Send Postal Marketing`='No'  "
                );
                break;
        }


    }
    $dont_allow_where = preg_replace('/^\s*or/', '', $dont_allow_where);
    if ($dont_allow_where != '') {
        $where .= "and ($dont_allow_where)";
    }


    $customers_which_where = '';
    foreach ($where_data['customers_which'] as $customers_which) {
        switch ($customers_which) {
            case 'active':
                $customers_which_where .= sprintf(
                    " or `Customer Active`='Yes' "
                );
                break;
            case 'losing':
                $customers_which_where .= sprintf(
                    " or `Customer Type by Activity`='Losing'  "
                );
                break;
            case 'lost':
                $customers_which_where .= sprintf(
                    " or `Customer Active`='No'  "
                );
                break;
        }
    }
    $customers_which_where = preg_replace(
        '/^\s*or/', '', $customers_which_where
    );
    if ($customers_which_where != '') {
        $where .= "and ($customers_which_where)";
    }

    //print_r($where_data);
    if ($where_data['order_time_units_since_last_order_qty'] > 0) {

        switch ($where_data['order_time_units_since_last_order_units']) {
            case 'days':

                $_date = date(
                    "Y-m-d 00:00:00", strtotime(
                        sprintf(
                            "today -%d %s", $where_data['order_time_units_since_last_order_qty'], $where_data['order_time_units_since_last_order_units']
                        )
                    )
                );

                $where .= sprintf(
                    ' and `Customer Orders`>0 and  `Customer Last Order Date`<=%s ', prepare_mysql($_date)
                );
                break;
            case 'exact_day':

                $_date = date(
                    "Y-m-d 00:00:00", strtotime(
                        sprintf(
                            "today -%d %s", $where_data['order_time_units_since_last_order_qty'], $where_data['order_time_units_since_last_order_units']
                        )
                    )
                );
                $_date = date(
                    "Y-m-d 23:59:59", strtotime(
                        sprintf(
                            "today -%d %s", $where_data['order_time_units_since_last_order_qty'], $where_data['order_time_units_since_last_order_units']
                        )
                    )
                );

                $where .= sprintf(
                    ' and `Customer Orders`>0 and  `Customer Last Order Date`>=%s and  `Customer Last Order Date`<=%s ', prepare_mysql($_date), prepare_mysql($_date2)

                );
                break;

            default:

                break;
        }

    }


    $invoice_option_where = '';
    foreach ($where_data['invoice_option'] as $invoice_option) {
        switch ($invoice_option) {
            case 'less':
                if (is_numeric($where_data['number_of_invoices_lower'])) {
                    $invoice_option_where .= sprintf(
                        " and `Customer Orders Invoiced`<'%d' ", $where_data['number_of_invoices_lower']
                    );
                }
                break;
            case 'equal':
                if (is_numeric($where_data['number_of_invoices_lower'])) {
                    $invoice_option_where .= sprintf(
                        " and `Customer Orders Invoiced`='%d'  ", $where_data['number_of_invoices_lower']
                    );
                }
                break;
            case 'more':
                if (is_numeric($where_data['number_of_invoices_lower'])) {
                    $invoice_option_where .= sprintf(
                        " and  `Customer Orders Invoiced`>'%d'  ", $where_data['number_of_invoices_lower']
                    );
                }
                break;

            case 'between':
                if (is_numeric($where_data['number_of_invoices_lower'])) {
                    $invoice_option_where .= sprintf(
                        " and `Customer Orders Invoiced`>='%d' ", $where_data['number_of_invoices_lower']
                    );
                }
                if (is_numeric($where_data['number_of_invoices_upper'])) {
                    $invoice_option_where .= sprintf(
                        " and `Customer Orders Invoiced`<='%d' ", $where_data['number_of_invoices_upper']
                    );
                }

                break;
        }
    }
    $invoice_option_where = preg_replace(
        '/^\s*and/', '', $invoice_option_where
    );

    if ($invoice_option_where != '') {
        $where .= "and ($invoice_option_where)";
    }

    $order_option_where = '';
    foreach ($where_data['order_option'] as $order_option) {
        switch ($order_option) {
            case 'less':
                if (is_numeric($where_data['number_of_orders_lower'])) {
                    $order_option_where .= sprintf(
                        " and `Customer Orders`<'%d' ", $where_data['number_of_orders_lower']
                    );
                }
                break;
            case 'equal':
                if (is_numeric($where_data['number_of_orders_lower'])) {
                    $order_option_where .= sprintf(
                        " and `Customer Orders`='%d'  ", $where_data['number_of_orders_lower']
                    );
                }
                break;
            case 'more':
                if (is_numeric($where_data['number_of_orders_lower'])) {
                    $order_option_where .= sprintf(
                        " and  `Customer Orders`>'%d'  ", $where_data['number_of_orders_lower']
                    );
                }
                break;

            case 'between':
                if (is_numeric($where_data['number_of_orders_lower'])) {
                    $order_option_where .= sprintf(
                        " and `Customer Orders`>='%d' ", $where_data['number_of_orders_lower']
                    );
                }
                if (is_numeric($where_data['number_of_orders_upper'])) {
                    $order_option_where .= sprintf(
                        " and `Customer Orders`<='%d' ", $where_data['number_of_orders_upper']
                    );
                }

                break;
        }
    }


    $order_option_where = preg_replace('/^\s*and/', '', $order_option_where);


    if ($order_option_where != '') {
        $where .= "and ($order_option_where)";
    }

    //print_r($where_data);
    $sales_option_where = '';
    foreach ($where_data['sales_option'] as $sales_option) {
        switch ($sales_option) {
            case 'sales_less':
                if (is_numeric($where_data['sales_lower'])) {
                    $sales_option_where .= sprintf(
                        " and `Customer Net Payments`<'%f' ", $where_data['sales_lower']
                    );
                }
                break;
            case 'sales_equal':
                if (is_numeric($where_data['sales_lower'])) {
                    $sales_option_where .= sprintf(
                        " and `Customer Net Payments`='%f'  ", $where_data['sales_lower']
                    );
                }
                break;
            case 'sales_more':
                if (is_numeric($where_data['sales_lower'])) {
                    $sales_option_where .= sprintf(
                        " and  `Customer Net Payments`>'%f'  ", $where_data['sales_lower']
                    );
                }
                break;

            case 'sales_between':
                if (is_numeric($where_data['sales_lower'])) {
                    $sales_option_where .= sprintf(
                        " and  `Customer Net Payments`>='%f'  ", $where_data['sales_lower']
                    );
                }
                if (is_numeric($where_data['sales_upper'])) {
                    $sales_option_where .= sprintf(
                        " and  `Customer Net Payments`<='%f'  ", $where_data['sales_upper']
                    );
                }
                break;
        }
    }
    $sales_option_where = preg_replace('/^\s*and/', '', $sales_option_where);
    if ($sales_option_where != '') {
        $where .= "and ($sales_option_where)";
    }


    $logins_option_where = '';
    foreach ($where_data['logins_option'] as $logins_option) {
        switch ($logins_option) {
            case 'logins_less':
                if (is_numeric($where_data['logins_lower'])) {
                    $logins_option_where .= sprintf(
                        " and `Customer Number Web Logins`<%d ", $where_data['logins_lower']
                    );
                }
                break;
            case 'logins_equal':
                if (is_numeric($where_data['logins_lower'])) {
                    $logins_option_where .= sprintf(
                        " and `Customer Number Web Logins`=%d  ", $where_data['logins_lower']
                    );
                }
                break;
            case 'logins_more':
                if (is_numeric($where_data['logins_lower'])) {
                    $logins_option_where .= sprintf(
                        " and  `Customer Number Web Logins`>%d  ", $where_data['logins_lower']
                    );
                }
                break;
            case 'logins_between':
                if (is_numeric($where_data['logins_lower'])) {
                    $logins_option_where .= sprintf(
                        " and  `Customer Number Web Logins`>=%d  ", $where_data['logins_lower']
                    );
                }
                if (is_numeric($where_data['logins_upper'])) {
                    $logins_option_where .= sprintf(
                        " and  `Customer Number Web Logins`<=%d  ", $where_data['logins_upper']
                    );
                }


                break;
        }
    }
    $logins_option_where = preg_replace('/^\s*and/', '', $logins_option_where);
    if ($logins_option_where != '') {
        $where .= "and ($logins_option_where)";
    }

    $failed_logins_option_where = '';
    foreach ($where_data['failed_logins_option'] as $failed_logins_option) {
        switch ($failed_logins_option) {
            case 'failed_logins_less':
                if (is_numeric($where_data['failed_logins_lower'])) {
                    $failed_logins_option_where .= sprintf(
                        " and `Customer Number Web Failed Logins`<%d ", $where_data['failed_logins_lower']
                    );
                }
                break;
            case 'failed_logins_equal':
                if (is_numeric($where_data['failed_logins_lower'])) {
                    $failed_logins_option_where .= sprintf(
                        " and `Customer Number Web Failed Logins`=%d  ", $where_data['failed_logins_lower']
                    );
                }
                break;
            case 'failed_logins_more':
                if (is_numeric($where_data['failed_logins_lower'])) {
                    $failed_logins_option_where .= sprintf(
                        " and  `Customer Number Web Failed Logins`>%d  ", $where_data['failed_logins_lower']
                    );
                }
                break;
            case 'failed_logins_between':
                if (is_numeric($where_data['failed_logins_lower'])) {
                    $failed_logins_option_where .= sprintf(
                        " and `Customer Number Web Failed Logins`>=%d ", $where_data['failed_logins_lower']
                    );
                }
                if (is_numeric($where_data['failed_logins_upper'])) {
                    $failed_logins_option_where .= sprintf(
                        " and `Customer Number Web Failed Logins`<=%d ", $where_data['failed_logins_upper']
                    );
                }


                break;
        }
    }
    $failed_logins_option_where = preg_replace(
        '/^\s*and/', '', $failed_logins_option_where
    );
    if ($failed_logins_option_where != '') {
        $where .= "and ($failed_logins_option_where)";
    }

    $requests_option_where = '';
    foreach ($where_data['requests_option'] as $requests_option) {
        switch ($requests_option) {
            case 'requests_less':
                if (is_numeric($where_data['requests_lower'])) {
                    $requests_option_where .= sprintf(
                        " and `Customer Number Web Requests`<%d ", $where_data['requests_lower']
                    );
                }
                break;
            case 'requests_equal':
                if (is_numeric($where_data['requests_lower'])) {
                    $requests_option_where .= sprintf(
                        " and `Customer Number Web Requests`=%d  ", $where_data['requests_lower']
                    );
                }
                break;
            case 'requests_more':
                if (is_numeric($where_data['requests_lower'])) {
                    $requests_option_where .= sprintf(
                        " and  `Customer Number Web Requests`>%d  ", $where_data['requests_lower']
                    );
                }
                break;
            case 'requests_between':
                if (is_numeric($where_data['requests_lower'])) {
                    $requests_option_where .= sprintf(
                        " and `Customer Number Web Requests`>-%d ", $where_data['requests_lower']
                    );
                }
                if (is_numeric($where_data['requests_upper'])) {
                    $requests_option_where .= sprintf(
                        " and `Customer Number Web Requests`<=%d ", $where_data['requests_upper']
                    );
                }

                break;
        }
    }
    $requests_option_where = preg_replace(
        '/^\s*and/', '', $requests_option_where
    );
    if ($requests_option_where != '') {
        $where .= "and ($requests_option_where)";
    }


    /*
    $not_customers_which_where='';
    foreach($where_data['not_customers_which'] as $not_customers_which) {
        switch ($not_customers_which) {
        case 'active':
            $not_customers_which_where.=sprintf(" or `Customer Active`='No' ");
            break;
        case 'losing':
            $not_customers_which_where.=sprintf(" or `Customer Type by Activity`='Active'  ");
            break;
        case 'lost':
            $not_customers_which_where.=sprintf(" or  `Customer Active`='Yes'  ");
            break;
        }
    }

    $not_customers_which_where=preg_replace('/^\s*or/','',$not_customers_which_where);
    if($not_customers_which_where!=''){
    $where.="and ($not_customers_which_where)";
    }
    *///print $table;print $where;
    // print "$where; <br/>$table "; exit;
    return array(
        $where,
        $table,
        $group
    );


}


?>
