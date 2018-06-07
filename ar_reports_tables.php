<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 October 2015 at 12:27:49 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 405,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}

$tipo = $_REQUEST['tipo'];

switch ($tipo) {
    case 'reports':
        reports(get_table_parameters(), $db, $user);
        break;
    case 'lost_stock':
        lost_stock(get_table_parameters(), $db, $user, $account);
        break;
    case 'stock_given_free':
        stock_given_free(get_table_parameters(), $db, $user, $account);
        break;
    case 'ec_sales_list':
        ec_sales_list(get_table_parameters(), $db, $user, $account);
        break;
    case 'pickers':
        pickers(get_table_parameters(), $db, $user, $account);
        break;
    case 'packers':
        packers(get_table_parameters(), $db, $user, $account);
        break;
    case 'sales':
        sales(get_table_parameters(), $db, $user, $account);
        break;
    case 'orders':
        dispatched_orders(get_table_parameters(), $db, $user, $account);
        break;
    case 'delivery_notes':
        dispatched_delivery_notes(get_table_parameters(), $db, $user, $account);
        break;

    case 'orders_components':
        dispatched_orders_components(get_table_parameters(), $db, $user, $account);
        break;


    case 'intrastat':
        intrastat(get_table_parameters(), $db, $user, $account);
        break;
    case 'intrastat_totals':
        intrastat_totals($db, $user, $account);
        break;
    case 'intrastat_orders':
        intrastat_orders(get_table_parameters(), $db, $user, $account);
        break;
    case 'intrastat_orders_totals':
        intrastat_orders_totals($db, $user, $account);
        break;

    case 'ec_sales_list_totals':

        ec_sales_list_totals($db, $user, $account);

        break;
    case 'intrastat_products_totals':
        intrastat_products_totals($db, $user, $account);
        break;
    case 'intrastat_products':
        intrastat_products(get_table_parameters(), $db, $user, $account);
        break;
    case 'billingregion_taxcategory':
        billingregion_taxcategory(get_table_parameters(), $db, $user, $account);
        break;
    case 'billingregion_taxcategory.invoices':
    case 'billingregion_taxcategory.refunds':
        invoices_billingregion_taxcategory(
            get_table_parameters(), $db, $user, $account
        );
        break;

    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
        break;
}


function reports($_data, $db, $user) {
    global $db;
    $rtext_label = 'report';
    //include_once 'prepare_table/init.php';
    include_once 'utils/available_reports.php';


    $adata = array();


    foreach ($available_reports as $key => $data) {

        $adata[] = array(
            'name'    => sprintf('<span class="link" onclick="change_view(\'/report/%s\')">%s</span>', $key, $data['Label']),
            'section' => sprintf('<span class="link" onclick="change_view(\'/reports/%s\')">%s</span>', $data['Group'], $data['GroupLabel'])

        );

    }

    $_order = (isset($_data['o']) ? $_data['o'] : 'id');
    $_dir   = ((isset($_data['od']) and preg_match('/desc/i', $_data['od'])) ? 'desc' : '');


    $rtext = get_rtext('report', count($available_reports));

    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => count($available_reports)

        )
    );
    echo json_encode($response);
}


function ec_sales_list($_data, $db, $user, $account) {


    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    //print $sql;

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            switch ($data['Invoice Tax Number Valid']) {
                case 'Yes':
                    $tax_number_valid = '<i class="fa fa-check-circle success padding_right_5" aria-hidden="true"></i> ';
                    break;
                case 'No':
                    $tax_number_valid = '<i class="fa fa-exclamation-circle error padding_right_5" aria-hidden="true"></i> ';
                    break;
                case 'Unknown':
                    $tax_number_valid = '<i class="fa fa-question-circle very_discreet padding_right_5" aria-hidden="true"></i> ';
                    break;
                default:
                    $tax_number_valid = '';
                    break;
            }

            if ($data['Invoice Tax Number'] == '') {
                $tax_number_valid = '';
            }

            $tax_number          = $data['Invoice Tax Number'];
            $country_2alpha_code = $data['Invoice Address Country 2 Alpha Code'];
            $tax_number          = preg_replace(
                '/^'.$country_2alpha_code.'/i', '', $tax_number
            );
            $tax_number          = preg_replace(
                '/[^a-z^0-9]/i', '', $tax_number
            );

            if (preg_match('/^gr$/i', $country_2alpha_code)) {
                $country_2alpha_code = 'EL';
            }

            $tax_number = preg_replace(
                '/^'.$country_2alpha_code.'/i', '', $tax_number
            );
            $tax_number = preg_replace('/[^a-z^0-9]/i', '', $tax_number);

            $adata[] = array(

                // 'tax_code'=> sprintf('<span title="%s">%s</span>', ($data['Invoice Tax Code']=='UNK'?_('Unknown tax code'):$data['Tax Category Name']), $data['Invoice Tax Code']),
                // 'request'=>$data['Invoice Billing Region'].'/'.$data['Invoice Tax Code'],
                'country_code' => $data['Invoice Address Country 2 Alpha Code'],
                'invoices'     => number($data['invoices']),
                'refunds'      => number($data['refunds']),
                'customer'     => sprintf(
                    '<span class="link" onClick="change_view(\'customer/%d\')"   title="%s">%06d</span>', $data['Invoice Customer Key'], $data['Invoice Customer Name'], $data['Invoice Customer Key']
                ),
                'tax_number'   => $tax_number_valid.$tax_number,
                'tax'          => money(
                    $data['tax'], $account->get('Account Currency')
                ),
                'net'          => money(
                    $data['net'], $account->get('Account Currency')
                ),
                'total'        => money(
                    $data['total'], $account->get('Account Currency')
                ),


            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $sql  = "select $fields from $table $where $wheref $group_by";
    $stmt = $db->prepare($sql);
    $stmt->execute();

    $total_records = $stmt->rowCount();

    $rtext = sprintf(
            ngettext('%s Customer/Tax number/Country', '%s Customers/Tax number/Country', $total_records), number($total_records)
        ).' <span class="discreet">'.$rtext.'</span>';


    //$rtext=preg_replace('/\(|\)/', '', $rtext);


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function billingregion_taxcategory($_data, $db, $user, $account) {

    $rtext_label = 'record';
    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";

    $adata = array();


    //print $sql;

    $sum_invoices = 0;
    $sum_refunds  = 0;
    $sum_net      = 0;
    $sum_tax      = 0;
    $sum_total    = 0;

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            switch ($data['Invoice Billing Region']) {
                case 'EU':
                    $billing_region = _('European Union');
                    break;
                case 'Unknown':
                    $billing_region = _('Unknown');
                    break;
                case 'NOEU':
                    $billing_region = _('Outside European Union');
                    break;
                case 'GBIM':
                    $billing_region = 'GB+IM';
                    break;
                default:
                    $billing_region = $data['Invoice Billing Region'];
                    break;
            }


            $adata[] = array(

                'billing_region' => $billing_region,
                'tax_code'       => sprintf('<span title="%s">%s</span>', ($data['Invoice Tax Code'] == 'UNK' ? _('Unknown tax code') : $data['Tax Category Name']), $data['Invoice Tax Code']),
                'request'        => $data['Invoice Billing Region'].'/'.$data['Invoice Tax Code'],
                'invoices'       => sprintf('<span class="link" onClick="change_view(\'report/billingregion_taxcategory/invoices/%s/%s\')" >%s</span>', $data['Invoice Billing Region'], $data['Invoice Tax Code'], number($data['invoices'])),

                'refunds' => sprintf('<span class="link" onClick="change_view(\'report/billingregion_taxcategory/refunds/%s/%s\')" >%s</span>', $data['Invoice Billing Region'], $data['Invoice Tax Code'], number($data['refunds'])),

                'customers' => number($data['customers']),
                'tax'       => money($data['tax'], $account->get('Account Currency')),
                'net'       => money($data['net'], $account->get('Account Currency')),
                'total'     => money($data['total'], $account->get('Account Currency')),


            );

            $sum_invoices += $data['invoices'];
            $sum_refunds  += $data['refunds'];
            $sum_net      += $data['net'];
            $sum_tax      += $data['tax'];
            $sum_total    += $data['total'];

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $adata[] = array(

        'billing_region' => _('Total'),
        'tax_code'       => '',
        'request'        => '',
        'invoices'       => number($sum_invoices),
        'refunds'        => number($sum_refunds),

        'customers' => '',
        'tax'       => money($sum_tax, $account->get('Account Currency')),
        'net'       => money($sum_net, $account->get('Account Currency')),
        'total'     => money($sum_total, $account->get('Account Currency')),


    );


    $rtext = preg_replace('/\(|\)/', '', $rtext);


    if (is_array($parameters['excluded_stores']) and count($parameters['excluded_stores']) > 0) {
        $excluded_stores = '';
        $sql             = sprintf(
            'SELECT `Store Key`,`Store Code`,`Store Name` FROM `Store Dimension` WHERE `Store Key` IN (%s)', join($parameters['excluded_stores'], ',')
        );

        if ($result = $db->query($sql)) {

            foreach ($result as $data) {
                $excluded_stores .= $data['Store Code'].', ';

            }

        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }

        $excluded_stores = preg_replace('/, $/', '', $excluded_stores);


        $rtext .= ' ('._('Excluding').': '.$excluded_stores.')';
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function invoices_billingregion_taxcategory($_data, $db, $user) {



    if($_data['parameters']['tab']=='billingregion_taxcategory.refunds'){
        $rtext_label = 'refund';
    }else{
        $rtext_label = 'invoice';
    }



    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    $adata = array();

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            if ($data['Invoice Paid'] == 'Yes') {
                $state = _('Paid');
            } elseif ($data['Invoice Paid'] == 'Partially') {
                $state = _('Partially Paid');
            } else {
                $state = _('No Paid');
            }


            if ($data['Invoice Type'] == 'Invoice') {
                $type = _('Invoice');
            } elseif ($data['Invoice Type'] == 'CreditNote') {
                $type = _('Credit Note');
            } else {
                $type = _('Refund');
            }

            switch ($data['Invoice Main Payment Method']) {
                default:
                    $method = $data['Invoice Main Payment Method'];
            }

            $adata[] = array(
                'id' => (integer)$data['Invoice Key'],

                'number'               => sprintf('<span class="link" onclick="change_view(\'invoices/%d/%d\')">%s</span>', $data['Invoice Store Key'], $data['Invoice Key'], $data['Invoice Public ID']),
                'customer'             => $data['Invoice Customer Name'],
                'store_code'           => sprintf('<span title="%s">%s</span>', $data['Store Name'], $data['Store Code']),
                'date'                 => strftime("%e %b %Y", strtotime($data['Invoice Date'].' +0:00')),
                'total_amount'         => money(
                    $data['Invoice Total Amount'], $data['Invoice Currency']
                ),
                'net'                  => money(
                    $data['Invoice Total Net Amount'], $data['Invoice Currency']
                ),
                'tax'                  => money(
                    $data['Invoice Total Tax Amount'], $data['Invoice Currency']
                ),
                'shipping'             => money(
                    $data['Invoice Shipping Net Amount'], $data['Invoice Currency']
                ),
                'items'                => money(
                    $data['Invoice Items Net Amount'], $data['Invoice Currency']
                ),
                'type'                 => $type,
                'payment_method'       => $method,
                'state'                => $state,
                'billing_country'      => $data['Invoice Address Country 2 Alpha Code'],
                'billing_country_flag' => sprintf(
                    '<img title="%s" src="/art/flags/%s.gif">', $data['Country Name'], strtolower($data['Invoice Address Country 2 Alpha Code'])
                )

            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

    if (is_array($parameters['excluded_stores']) and count(
            $parameters['excluded_stores']
        ) > 0) {
        $excluded_stores = '';
        $sql             = sprintf(
            'SELECT `Store Key`,`Store Code`,`Store Name` FROM `Store Dimension` WHERE `Store Key` IN (%s)', join($parameters['excluded_stores'], ',')
        );

        if ($result = $db->query($sql)) {
            foreach ($result as $data) {
                $excluded_stores .= $data['Store Code'].', ';
            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }

        $excluded_stores = preg_replace('/, $/', '', $excluded_stores);
        $rtext           .= ' ('._('Excluding').': '.$excluded_stores.')';
    }

    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function ec_sales_list_totals($db, $user, $account) {

    $sum_net   = 0;
    $sum_tax   = 0;
    $sum_total   = 0;
    $sum_invoices = 0;
    $sum_refunds = 0;
    $sum_customers = 0;

    $parameters = $_SESSION['table_state']['ec_sales_list'];


    include_once('class.Country.php');

    $account_country=new Country('code',$account->get('Account Country Code'));



    $european_union_2alpha=array('NL', 'BE', 'GB', 'BG', 'ES', 'IE', 'IT', 'AT', 'GR', 'CY', 'LV', 'LT', 'LU', 'MT', 'PT', 'PL', 'FR', 'RO', 'SE', 'DE', 'SK', 'SI', 'FI', 'DK', 'CZ', 'HU', 'EE');




    $european_union_2alpha= "'" . implode("','", $european_union_2alpha) . "'";


    $european_union_2alpha=preg_replace('/,?\''.$account_country->get('Country 2 Alpha Code').'\'/','',$european_union_2alpha);

    $european_union_2alpha=preg_replace('/^,/','',$european_union_2alpha);



    $where = ' where `Invoice Address Country 2 Alpha Code` in ('.$european_union_2alpha.')';

    if (isset($parameters['period'])) {


        include_once 'utils/date_functions.php';


        list($db_interval, $from, $to, $from_date_1yb, $to_1yb)
            = calculate_interval_dates(
            $db, $parameters['period'], $parameters['from'], $parameters['to']
        );


        $where_interval = prepare_mysql_dates($from, $to, '`Invoice Date`');


        $where .= $where_interval['mysql'];


    }




    if (isset($parameters['elements'])) {
        $elements = $parameters['elements'];


        switch ($parameters['elements_type']) {

            case('tax_status'):
                //print_r($parameters['elements']);

                $number_elements = 0;


                $with_tax_number    = false;
                $with_no_tax_number = false;

                $valid_tax_number   = false;
                $invalid_tax_number = false;

                foreach (
                    $parameters['elements'][$parameters['elements_type']]['items'] as $_element => $element_data
                ) {

                    if ($element_data['selected']) {
                        //print $_element;
                        if ($_element == 'Missing') {
                            $with_no_tax_number = true;
                        } else {

                            if ($_element == 'Yes') {
                                $valid_tax_number = true;
                            } else {
                                if ($_element == 'No') {
                                    $invalid_tax_number = true;

                                }
                            }

                            $with_tax_number = true;
                        }

                        $number_elements++;
                    }

                }
                if ($number_elements == 0) {
                    $where .= ' and false';
                } elseif ($number_elements < 3) {

                    if ($with_no_tax_number and !$with_tax_number) {
                        $where .= " and ( `Invoice Tax Number` is NULL or `Invoice Tax Number`='' ) ";
                    } elseif ($with_tax_number and !$with_no_tax_number) {
                        $where .= " and `Invoice Tax Number`!='' ";

                        if ($valid_tax_number and !$invalid_tax_number) {
                            $where .= " and `Invoice Tax Number Valid`='Yes' ";
                        }
                        if ($invalid_tax_number and !$valid_tax_number) {
                            $where .= " and `Invoice Tax Number Valid`!='Yes' ";
                        }


                    } elseif ($with_tax_number and $with_no_tax_number) {


                        if ($valid_tax_number and !$invalid_tax_number) {
                            $where .= " and  ( `Invoice Tax Number Valid`='Yes'  or  ( `Invoice Tax Number` is NULL or `Invoice Tax Number`='' )    )  ";
                        }
                        if ($invalid_tax_number and !$valid_tax_number) {
                            $where .= " and ( `Invoice Tax Number Valid`!='Yes' or  ( `Invoice Tax Number` is NULL or `Invoice Tax Number`='' )  ) ";
                        }


                    }

                }


                break;
        }

    }



    $sql = "select 
sum(if(`Invoice Type`='Invoice',1,0)) as invoices,
 sum(if(`Invoice Type`!='Invoice',1,0)) as refunds,
count(distinct `Invoice Customer Key` ) as customers,
  sum(`Invoice Total Amount`*`Invoice Currency Exchange`) as total ,
  sum( `Invoice Total Net Amount`*`Invoice Currency Exchange`) as net,
  sum( `Invoice Total Tax Amount`*`Invoice Currency Exchange`) as tax
  	
from `Invoice Dimension` 
 
   $where
  ";

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $sum_customers   = $row['customers'];
            $sum_net   = $row['net'];
            $sum_tax   = $row['tax'];
            $sum_total   =$row['total'];
            $sum_invoices = $row['invoices'];
            $sum_refunds =$row['refunds'];

        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }

    $totals   = array(
        'total_amount_net'   => money($sum_net, $account->get('Account Currency')),
        'total_amount_tax'   => money($sum_tax, $account->get('Account Currency')),
        'total_amount_total'   => money($sum_total, $account->get('Account Currency')),
        'total_customers'   => number($sum_customers),

        'total_invoices'   => number($sum_invoices),
        'total_refunds'   => number($sum_refunds),

    );
    $response = array(
        'state'  => 200,
        'totals' => $totals
    );

    echo json_encode($response);

}

function intrastat_totals($db, $user, $account) {

    // print_r($_SESSION['table_state']['intrastat']);

    $sum_amount   = 0;
    $sum_weight   = 0;
    $sum_orders   = 0;
    $sum_products = 0;

    $parameters = $_SESSION['table_state']['intrastat'];

    include_once('class.Country.php');
    $account_country     = new Country('code', $account->get('Account Country Code'));
    $intrastat_countries = array(
        'NL',
        'BE',
        'GB',
        'BG',
        'ES',
        'IE',
        'IT',
        'AT',
        'GR',
        'CY',
        'LV',
        'LT',
        'LU',
        'MT',
        'PT',
        'PL',
        'FR',
        'RO',
        'SE',
        'DE',
        'SK',
        'SI',
        'FI',
        'DK',
        'CZ',
        'HU',
        'EE'
    );
    $intrastat_countries = "'".implode("','", $intrastat_countries)."'";
    $intrastat_countries = preg_replace('/,?\''.$account_country->get('Country 2 Alpha Code').'\'/', '', $intrastat_countries);
    $intrastat_countries = preg_replace('/^,/', '', $intrastat_countries);

    $where = ' where `Delivery Note Address Country 2 Alpha Code` in ('.$intrastat_countries.')  and DN.`Delivery Note Key` is not null and `Delivery Note State`="Dispatched" ';


    if (isset($_SESSION['table_state']['intrastat']['period'])) {


        include_once 'utils/date_functions.php';


        list(
            $db_interval, $from, $to, $from_date_1yb, $to_1yb
            ) = calculate_interval_dates(
            $db, $_SESSION['table_state']['intrastat']['period'], $_SESSION['table_state']['intrastat']['from'], $_SESSION['table_state']['intrastat']['to']
        );


        $where_interval_invoice = prepare_mysql_dates($from, $to, 'I.`Invoice Date`');
        $where_interval_dn      = prepare_mysql_dates($from, $to, '`Delivery Note Date`');

        $where .= $where_interval_dn['mysql'];

      //  $where .= " and ( (  I.`Invoice Key`>0  ".$where_interval_invoice['mysql']." ) or ( I.`Invoice Key` is NULL  ".$where_interval_dn['mysql']." ))  ";


    }


    $parameters['invoices_vat']=(int)$parameters['invoices_vat'];
    $parameters['invoices_no_vat']=(int)$parameters['invoices_no_vat'];
    $parameters['invoices_null']=(int)$parameters['invoices_null'];



    if ($parameters['invoices_vat'] == 1 and $parameters['invoices_no_vat'] == 1 and $parameters['invoices_null'] == 1) {

    } elseif ($parameters['invoices_vat'] == 1 and $parameters['invoices_no_vat'] == 1 and $parameters['invoices_null'] == 0) {
        $where .= " and  I.`Invoice Key`>0  ";

    } elseif ($parameters['invoices_vat'] == 1 and $parameters['invoices_no_vat'] == 0 and $parameters['invoices_null'] == 0) {
        $where .= " and  I.`Invoice Key`>0  and I.`Invoice Tax Code` not in ('EX','OUT') ";

    } elseif ($parameters['invoices_vat'] == 0 and $parameters['invoices_no_vat'] == 1 and $parameters['invoices_null'] == 0) {
        $where .= " and  I.`Invoice Key`>0  and I.`Invoice Tax Code` in ('EX','OUT') ";

    } elseif ($parameters['invoices_vat'] == 0 and $parameters['invoices_no_vat'] == 0 and $parameters['invoices_null'] == 1) {
        $where .= " and  I.`Invoice Key` is null  ";

    } elseif ($parameters['invoices_vat'] == 1 and $parameters['invoices_no_vat'] == 0 and $parameters['invoices_null'] == 1) {
        $where .= " and   (  I.`Invoice Key` is null  or   ( I.`Invoice Key`>0    and I.`Invoice Tax Code` not in ('EX','OUT') )  ) ";

    } elseif ($parameters['invoices_vat'] == 0 and $parameters['invoices_no_vat'] == 1 and $parameters['invoices_null'] == 1) {
        $where .= " and   (  I.`Invoice Key` is null   or  I.`Invoice Tax Code` in ('EX','OUT')    ) ";

    } elseif ($parameters['invoices_vat'] == 0 and $parameters['invoices_no_vat'] == 0 and $parameters['invoices_null'] == 0) {
        $where .= " and false ";

    }

    if($account->get('Account Code')=='AWEU'){
        $sql = "select 
count(distinct OTF.`Product ID`) as products,
count(distinct OTF.`Order Key`) as orders,

sum(`Order Transaction Amount`*`Invoice Currency Exchange Rate`) as amount,
	sum(`Delivery Note Quantity`*`Product Unit Weight`*`Product Units Per Case`) as weight 
	
 from  `Order Transaction Fact` OTF left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`) left join `Delivery Note Dimension` DN  on (OTF.`Delivery Note Key`=DN.`Delivery Note Key`)  left join `Invoice Dimension` I  on (OTF.`Invoice Key`=I.`Invoice Key`) 
 
 
   $where
  ";

    }else{
        $sql = "select 
count(distinct OTF.`Product ID`) as products,
count(distinct OTF.`Order Key`) as orders,

	sum(`Invoice Currency Exchange Rate`*(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`+`Invoice Transaction Shipping Amount`+`Invoice Transaction Charges Amount`+`Invoice Transaction Insurance Amount`)) as amount, 
	sum(`Delivery Note Quantity`*`Product Unit Weight`*`Product Units Per Case`) as weight 
	
 from  `Order Transaction Fact` OTF left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`) left join `Delivery Note Dimension` DN  on (OTF.`Delivery Note Key`=DN.`Delivery Note Key`)  left join `Invoice Dimension` I  on (OTF.`Invoice Key`=I.`Invoice Key`) 
 
 
   $where
  ";

    }


    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $sum_amount   = $row['amount'];
            $sum_weight   = $row['weight'];
            $sum_orders   = $row['orders'];
            $sum_products = $row['products'];

        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }

    $totals   = array(
        'total_amount'   => money($sum_amount, $account->get('Account Currency')),
        'total_weight'   => weight($sum_weight, 'Kg', 0, false, true),
        'total_orders'   => number($sum_orders),
        'total_products' => number($sum_products),

    );
    $response = array(
        'state'  => 200,
        'totals' => $totals
    );

    echo json_encode($response);


}


function intrastat_orders_totals($db, $user, $account) {

    // print_r($_SESSION['table_state']['intrastat']);

    $sum_amount   = 0;
    $sum_weight   = 0;
    $sum_products = 0;

    $parameters = $_SESSION['table_state']['intrastat_orders'];


    if ($parameters['tariff_code'] == 'missing') {
        $where = sprintf(' where `Delivery Note Address Country 2 Alpha Code`=%s and (`Product Tariff Code` is null or `Product Tariff Code`="")  and DN.`Delivery Note Key` is not null and `Delivery Note State`="Dispatched" ', prepare_mysql($parameters['country_code']));

    } else {
        $where = sprintf(' where `Delivery Note Address Country 2 Alpha Code`=%s and `Product Tariff Code` like "%s%%"  and DN.`Delivery Note Key` is not null and `Delivery Note State`="Dispatched" ', prepare_mysql($parameters['country_code']), addslashes($parameters['tariff_code']));

    }


    if (isset($parameters['parent_period'])) {


        include_once 'utils/date_functions.php';


        list(
            $db_interval, $from, $to, $from_date_1yb, $to_1yb
            ) = calculate_interval_dates(
            $db, $parameters['parent_period'], $parameters['parent_from'], $parameters['parent_to']
        );


        $where_interval_invoice = prepare_mysql_dates($from, $to, 'I.`Invoice Date`');
        $where_interval_dn      = prepare_mysql_dates($from, $to, '`Delivery Note Date`');
        $where .= $where_interval_dn['mysql'];


      //  $where .= " and ( (  I.`Invoice Key`>0  ".$where_interval_invoice['mysql']." ) or ( I.`Invoice Key` is NULL  ".$where_interval_dn['mysql']." ))  ";


    }


    if ($parameters['invoices_vat'] == 1 and $parameters['invoices_no_vat'] == 1 and $parameters['invoices_null'] == 1) {

    } elseif ($parameters['invoices_vat'] == 1 and $parameters['invoices_no_vat'] == 1 and $parameters['invoices_null'] == 0) {
        $where .= " and  I.`Invoice Key`>0  ";

    } elseif ($parameters['invoices_vat'] == 1 and $parameters['invoices_no_vat'] == 0 and $parameters['invoices_null'] == 0) {
        $where .= " and  I.`Invoice Key`>0  and I.`Invoice Tax Code` not in ('EX','OUT') ";

    } elseif ($parameters['invoices_vat'] == 0 and $parameters['invoices_no_vat'] == 1 and $parameters['invoices_null'] == 0) {
        $where .= " and  I.`Invoice Key`>0  and I.`Invoice Tax Code` in ('EX','OUT') ";

    } elseif ($parameters['invoices_vat'] == 0 and $parameters['invoices_no_vat'] == 0 and $parameters['invoices_null'] == 1) {
        $where .= " and  I.`Invoice Key` is null  ";

    } elseif ($parameters['invoices_vat'] == 1 and $parameters['invoices_no_vat'] == 0 and $parameters['invoices_null'] == 1) {
        $where .= " and   (  I.`Invoice Key` is null  or   ( I.`Invoice Key`>0    and I.`Invoice Tax Code` not in ('EX','OUT') )  ) ";

    } elseif ($parameters['invoices_vat'] == 0 and $parameters['invoices_no_vat'] == 1 and $parameters['invoices_null'] == 1) {
        $where .= " and   (  I.`Invoice Key` is null   or  I.`Invoice Tax Code` in ('EX','OUT')    ) ";

    } elseif ($parameters['invoices_vat'] == 0 and $parameters['invoices_no_vat'] == 0 and $parameters['invoices_null'] == 0) {
        $where .= " and false ";

    }


    $sql = "select 
count(distinct OTF.`Product ID`) as products,

sum(`Order Transaction Amount`*`Invoice Currency Exchange Rate`) as amount,
	sum(`Delivery Note Quantity`*`Product Unit Weight`*`Product Units Per Case`) as weight 
	
 from  `Order Transaction Fact` OTF left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`) left join `Delivery Note Dimension` DN  on (OTF.`Delivery Note Key`=DN.`Delivery Note Key`)  left join `Invoice Dimension` I  on (OTF.`Invoice Key`=I.`Invoice Key`) 
 
 
   $where
  ";

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $sum_amount   = $row['amount'];
            $sum_weight   = $row['weight'];
            $sum_products = $row['products'];

        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }

    $totals   = array(
        'intrastat_orders_total_amount'   => money($sum_amount, $account->get('Account Currency')),
        'intrastat_orders_total_weight'   => weight($sum_weight, 'Kg', 0, false, true),
        'intrastat_orders_total_products' => number($sum_products),

    );
    $response = array(
        'state'  => 200,
        'totals' => $totals
    );

    echo json_encode($response);


}



function intrastat_products_totals($db, $user, $account) {

    // print_r($_SESSION['table_state']['intrastat']);

    $sum_amount   = 0;
    $sum_weight   = 0;
    $sum_orders = 0;

    $parameters = $_SESSION['table_state']['intrastat_products'];




    if($parameters['tariff_code']=='missing'){
        $where = sprintf(' where `Delivery Note Address Country 2 Alpha Code`=%s and (`Product Tariff Code` is null or `Product Tariff Code`="")  and DN.`Delivery Note Key` is not null and `Delivery Note State`="Dispatched" ',prepare_mysql($parameters['country_code']));

    }else{
        $where = sprintf(' where `Delivery Note Address Country 2 Alpha Code`=%s and `Product Tariff Code` like "%s%%"  and DN.`Delivery Note Key` is not null and `Delivery Note State`="Dispatched" ',prepare_mysql($parameters['country_code']),addslashes($parameters['tariff_code']));

    }




    if (isset($parameters['parent_period'])) {


        include_once 'utils/date_functions.php';


        list(
            $db_interval, $from, $to, $from_date_1yb, $to_1yb
            ) = calculate_interval_dates(
            $db, $parameters['parent_period'], $parameters['parent_from'], $parameters['parent_to']
        );


        $where_interval_invoice = prepare_mysql_dates($from, $to, 'I.`Invoice Date`');
        $where_interval_dn      = prepare_mysql_dates($from, $to, '`Delivery Note Date`');

        $where .= $where_interval_dn['mysql'];

      //  $where .= " and ( (  I.`Invoice Key`>0  ".$where_interval_invoice['mysql']." ) or ( I.`Invoice Key` is NULL  ".$where_interval_dn['mysql']." ))  ";


    }


    if ($parameters['invoices_vat'] == 1 and $parameters['invoices_no_vat'] == 1 and $parameters['invoices_null'] == 1) {

    } elseif ($parameters['invoices_vat'] == 1 and $parameters['invoices_no_vat'] == 1 and $parameters['invoices_null'] == 0) {
        $where .= " and  I.`Invoice Key`>0  ";

    } elseif ($parameters['invoices_vat'] == 1 and $parameters['invoices_no_vat'] == 0 and $parameters['invoices_null'] == 0) {
        $where .= " and  I.`Invoice Key`>0  and I.`Invoice Tax Code` not in ('EX','OUT') ";

    } elseif ($parameters['invoices_vat'] == 0 and $parameters['invoices_no_vat'] == 1 and $parameters['invoices_null'] == 0) {
        $where .= " and  I.`Invoice Key`>0  and I.`Invoice Tax Code` in ('EX','OUT') ";

    } elseif ($parameters['invoices_vat'] == 0 and $parameters['invoices_no_vat'] == 0 and $parameters['invoices_null'] == 1) {
        $where .= " and  I.`Invoice Key` is null  ";

    } elseif ($parameters['invoices_vat'] == 1 and $parameters['invoices_no_vat'] == 0 and $parameters['invoices_null'] == 1) {
        $where .= " and   (  I.`Invoice Key` is null  or   ( I.`Invoice Key`>0    and I.`Invoice Tax Code` not in ('EX','OUT') )  ) ";

    } elseif ($parameters['invoices_vat'] == 0 and $parameters['invoices_no_vat'] == 1 and $parameters['invoices_null'] == 1) {
        $where .= " and   (  I.`Invoice Key` is null   or  I.`Invoice Tax Code` in ('EX','OUT')    ) ";

    } elseif ($parameters['invoices_vat'] == 0 and $parameters['invoices_no_vat'] == 0 and $parameters['invoices_null'] == 0) {
        $where .= " and false ";

    }


    $sql = "select 
count(distinct OTF.`Order Key`) as orders,

sum(`Order Transaction Amount`*`Invoice Currency Exchange Rate`) as amount,
	sum(`Delivery Note Quantity`*`Product Unit Weight`*`Product Units Per Case`) as weight 
	
 from  `Order Transaction Fact` OTF left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`) left join `Delivery Note Dimension` DN  on (OTF.`Delivery Note Key`=DN.`Delivery Note Key`)  left join `Invoice Dimension` I  on (OTF.`Invoice Key`=I.`Invoice Key`) 
 
 
   $where
  ";

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $sum_amount   = $row['amount'];
            $sum_weight   = $row['weight'];
            $sum_orders = $row['orders'];

        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }

    $totals   = array(
        'intrastat_products_total_amount'   => money($sum_amount, $account->get('Account Currency')),
        'intrastat_products_total_weight'   => weight($sum_weight, 'Kg', 0, false, true),
        'intrastat_products_total_orders' => number($sum_orders),

    );
    $response = array(
        'state'  => 200,
        'totals' => $totals
    );

    echo json_encode($response);


}


function intrastat($_data, $db, $user, $account) {

    $rtext_label = 'record';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    //print $sql;

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            $adata[] = array(

                'country_code' => $data['Delivery Note Address Country 2 Alpha Code'],
                // 'invoices'     => number($data['invoices']),

                'period'      => $data['monthyear'],
                'tariff_code' => $data['tariff_code'],
                'value'       => money($data['value'], $account->get('Account Currency')),
                'items'       => $data['items'],
                'products'    => sprintf(
                    '<span class="link" onClick="change_view(\'report/intrastat/products/%s/%s\')" >%s</span>', $data['Delivery Note Address Country 2 Alpha Code'], ($data['tariff_code'] == '' ? 'missing' : $data['tariff_code']), number($data['products'])
                ),
                'orders'      => sprintf(
                    '<span class="link" onClick="change_view(\'report/intrastat/orders/%s/%s\')" >%s</span>', $data['Delivery Note Address Country 2 Alpha Code'], ($data['tariff_code'] == '' ? 'missing' : $data['tariff_code']), number($data['orders'])
                ),

                'weight' => weight($data['weight'], 'Kg', 2, false, true),


            );

        }
    } else {
        print "$sql\n";

        print_r($error_info = $db->errorInfo());
        exit;
    }


    $sql  = "select $fields from $table $where $wheref $group_by";
    $stmt = $db->prepare($sql);
    $stmt->execute();

    $total_records = $stmt->rowCount();

    $rtext = sprintf(
            ngettext('%s record', '%s records', $total_records), number($total_records)
        ).' <span class="discreet">'.$rtext.'</span>';



    //$rtext=preg_replace('/\(|\)/', '', $rtext);


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function intrastat_orders($_data, $db, $user, $account) {

    $rtext_label = 'order';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    // print $sql;

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            $adata[] = array(


                'number'   => sprintf('<span class="link" onClick="change_view(\'orders/%s/%s\')" >%s</span>', $data['Order Store Key'], $data['Order Key'], $data['Order Public ID']),
                'customer' => sprintf('<span class="link" onClick="change_view(\'customers/%s/%s\')" >%s</span>', $data['Order Store Key'], $data['Order Customer Key'], $data['Order Customer Name']),
                'date'     => strftime("%e %b %Y", strtotime($data['Delivery Note Date'].' +0:00')),
                'amount'   => money($data['amount'], $data['Order Currency Code']),
                'weight'   => weight($data['weight'], 'Kg', 2, false, true),
                'products' => $data['products']

            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $sql  = "select $fields from $table $where $wheref $group_by";
    $stmt = $db->prepare($sql);
    $stmt->execute();

    $total_records = $stmt->rowCount();

    $rtext = sprintf(
            ngettext('%s order', '%s orders', $total_records), number($total_records)
        ).' <span class="discreet">'.$rtext.'</span>';


    //$rtext=preg_replace('/\(|\)/', '', $rtext);


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function intrastat_products($_data, $db, $user, $account) {

    $rtext_label = 'product';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    // print $sql;

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            $adata[] = array(

                'store' => sprintf('<span class="link" onClick="change_view(\'products/%s\')" title="%s">%s</span>', $data['Product Store Key'], $data['Store Name'], $data['Store Code']),

                'code'       => sprintf('<span class="link" onClick="change_view(\'products/%s/%s\')" >%s</span>', $data['Product Store Key'], $data['Product ID'], $data['Product Code']),
                'name'       => $data['Product Name'],
                'units'      => number($data['Product Units Per Case']),
                'price'      => money($data['Product Price'] / $data['Product Units Per Case'], $data['Order Currency Code']),
                'weight'     => weight($data['Product Unit Weight'], 'Kg', 3, false, true),
                'units_send' => number($data['units_send']),

            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $sql  = "select $fields from $table $where $wheref $group_by";
    $stmt = $db->prepare($sql);
    $stmt->execute();

    $total_records = $stmt->rowCount();

    $rtext = sprintf(
            ngettext('%s product', '%s products', $total_records), number($total_records)
        ).' <span class="discreet">'.$rtext.'</span>';


    //$rtext=preg_replace('/\(|\)/', '', $rtext);


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function pickers($_data, $db, $user, $account) {

    $rtext_label = 'picker';


    foreach ($_data['parameters'] as $parameter => $parameter_value) {
        $_SESSION['table_state']['packers'][$parameter] = $parameter_value;

    }


    include_once 'prepare_table/init.php';


    $total_dp = 0;
    $sql      = sprintf("select sum(`Inventory Transaction Weight`) as weight,count(distinct `Delivery Note Key`) as delivery_notes,count(distinct `Delivery Note Key`,`Part SKU`) as units  from  `Inventory Transaction Fact` $where group by `Picker Key` ");


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $total_dp += ($row['units']);

        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    if ($total_dp == 0) {
        $total_dp = 1;
    }


    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            $adata[] = array(

                'name'                              => $data['Staff Name'],
                'deliveries'                        => number($data['deliveries']),
                'deliveries_with_errors'            => number($data['deliveries_with_errors']),
                'deliveries_with_errors_percentage' => percentage($data['deliveries_with_errors'], $data['deliveries']),
                'picks_with_errors'                 => number($data['picks_with_errors']),
                'picks_with_errors_percentage'      => percentage($data['picks_with_errors_percentage'], 1),
                'picked'                            => number($data['picked'], 0),
                'dp'                                => number($data['dp']),
                'dp_percentage'                     => percentage($data['dp'], $total_dp),
                'hrs'                               => number($data['hrs'], 1, true),
                'dp_per_hour'                       => ($data['dp_per_hour'] == '' ? '' : number($data['dp_per_hour'], 1, true)),

            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}

function packers($_data, $db, $user, $account) {

    $rtext_label = 'packer';


    foreach ($_data['parameters'] as $parameter => $parameter_value) {
        $_SESSION['table_state']['pickers'][$parameter] = $parameter_value;

    }

    include_once 'prepare_table/init.php';


    $total_dp = 0;
    $sql      = sprintf("select sum(`Inventory Transaction Weight`) as weight,count(distinct `Delivery Note Key`) as delivery_notes,count(distinct `Delivery Note Key`,`Part SKU`) as units  from  `Inventory Transaction Fact` $where group by `Packer Key` ");


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $total_dp += ($row['units']);

        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    if ($total_dp == 0) {
        $total_dp = 1;
    }


    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    //print $sql;

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            $adata[] = array(

                'name'                              => $data['Staff Name'],
                'deliveries'                        => number($data['deliveries']),
                'packed'                            => number($data['packed'], 0),
                'dp'                                => number($data['dp']),
                'dp_percentage'                     => percentage($data['dp'], $total_dp),
                'hrs'                               => number($data['hrs'], 1, true),
                'dp_per_hour'                       => ($data['dp_per_hour'] == '' ? '' : number($data['dp_per_hour'], 1, true)),
                'deliveries_with_errors'            => number($data['deliveries_with_errors']),
                'deliveries_with_errors_percentage' => percentage($data['deliveries_with_errors'], $data['deliveries']),
                'picks_with_errors'                 => number($data['picks_with_errors']),
                'picks_with_errors_percentage'      => percentage($data['picks_with_errors_percentage'], 1),


            );

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function sales($_data, $db, $user, $account) {

    $rtext_label = 'store';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    $totals = array(
        'store'         => _('Total'),
        'invoices'      => 0,
        'refunds'       => 0,
        'replacements'  => 0,
        'customers'     => 0,
        'refund_amount' => 0,
        'revenue'       => 0,
        'profit'        => 0,
        'margin'        => 0,
    );

    // print $sql;

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            $refund_amount = money($data['refunds_amount_oc'], $account->get('Account Currency Code'));
            $revenue       = money($data['revenue_oc'], $account->get('Account Currency Code'));
            $profit        = money($data['profit_oc'], $account->get('Account Currency Code'));

            $adata[] = array(

                'store'         => $data['Store Code'],
                'invoices'      => number($data['invoices']),
                'refunds'       => number($data['refunds']),
                'customers'     => number($data['customers']),
                'refund_amount' => $refund_amount,
                'revenue'       => $revenue,
                'profit'        => $profit
            );

            $totals['customers']     += $data['customers'];
            $totals['invoices']      += $data['invoices'];
            $totals['refunds']       += $data['refunds'];
            $totals['refund_amount'] += $data['refunds_amount_oc'];
            $totals['revenue']       += $data['revenue_oc'];
            $totals['profit']        += $data['profit_oc'];


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

    $totals['invoices']  = number($totals['invoices']);
    $totals['refunds']   = number($totals['refunds']);
    $totals['customers'] = number($totals['customers']);


    $totals['margin']        = percentage($totals['profit'], $totals['revenue']);
    $totals['refund_amount'] = money($totals['refund_amount'], $account->get('Account Currency Code'));
    $totals['revenue']       = money($totals['revenue'], $account->get('Account Currency Code'));
    $totals['profit']        = money($totals['profit'], $account->get('Account Currency Code'));

    $adata[] = $totals;

    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function dispatched_orders($_data, $db, $user, $account) {

    $rtext_label = 'store';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    // print $sql;


    $totals = array(
        'store'         => _('Total'),
        'orders'        => 0,
        'refunds'       => 0,
        'replacements'  => 0,
        'customers'     => 0,
        'refund_amount' => 0,
        'revenue'       => 0,
        'profit'        => 0,
        'margin'        => 0,
    );

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            $refund_amount = money($data['refunds_amount_oc'], $account->get('Account Currency Code'));
            $revenue       = money($data['revenue_oc'], $account->get('Account Currency Code'));
            $profit        = money($data['profit_oc'], $account->get('Account Currency Code'));

            $adata[] = array(

                'store'         => $data['Store Code'],
                'orders'        => number($data['orders']),
                'refunds'       => number($data['refunds']),
                'replacements'  => number($data['replacements']),
                'customers'     => number($data['customers']),
                'refund_amount' => $refund_amount,
                'revenue'       => $revenue,
                'profit'        => $profit,
                'margin'        => percentage($data['profit_oc'], $data['revenue_oc'])
            );


            $totals['customers']     += $data['customers'];
            $totals['orders']        += $data['orders'];
            $totals['refunds']       += $data['refunds'];
            $totals['replacements']  += $data['replacements'];
            $totals['refund_amount'] += $data['refunds_amount_oc'];
            $totals['revenue']       += $data['revenue_oc'];
            $totals['profit']        += $data['profit_oc'];

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

    $totals['margin'] = percentage($totals['profit'], $totals['revenue']);

    $totals['refund_amount'] = money($totals['refund_amount'], $account->get('Account Currency Code'));
    $totals['revenue']       = money($totals['revenue'], $account->get('Account Currency Code'));

    $totals['profit'] = money($totals['profit'], $account->get('Account Currency Code'));

    $adata[] = $totals;

    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function dispatched_delivery_notes($_data, $db, $user, $account) {

    $rtext_label = 'store';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    // print $sql;


    $totals = array(
        'store'          => _('Total'),
        'shipments'      => 0,
        'delivery_notes' => 0,
        'replacements'   => 0,
        'customers'      => 0,
    );


    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            // $refund_amount=money($data['refunds_amount_oc'],$account->get('Account Currency Code'));
            // $revenue=money($data['revenue_oc'],$account->get('Account Currency Code'));
            //  $profit=money($data['profit_oc'],$account->get('Account Currency Code'));

            $adata[] = array(

                'store'     => $data['Store Code'],
                'shipments' => number($data['shipments']),

                'delivery_notes' => number($data['shipments'] - $data['replacements']),
                //'refunds' =>number($data['refunds']),
                'replacements'   => number($data['replacements']),
                'customers'      => number($data['customers']),
                //'refund_amount' =>$refund_amount,
                //'revenue' =>$revenue,
                //'profit' =>$profit,
                //'margin' =>percentage($data['profit_oc'],$data['revenue_oc'])
            );


            $totals['customers']      += $data['customers'];
            $totals['shipments']      += $data['shipments'];
            $totals['delivery_notes'] += $data['shipments'] - $data['replacements'];
            $totals['replacements']   += $data['replacements'];
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

    $adata[] = $totals;


    $sql  = "select $fields from $table $where $wheref $group_by";
    $stmt = $db->prepare($sql);
    $stmt->execute();

    $total_records = $stmt->rowCount();

    $rtext = sprintf(
            ngettext('%s record', '%s records', $total_records), number($total_records)
        ).' <span class="discreet">'.$rtext.'</span>';


    //$rtext=preg_replace('/\(|\)/', '', $rtext);


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function dispatched_orders_components($_data, $db, $user, $account) {

    $rtext_label = 'store';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();

    // print $sql;


    $totals = array(
        'store'            => _('Total'),
        'items_cost'       => 0,
        'shipping_cost'    => 0,
        'replacement_cost' => 0,
        'items_net'        => 0,
        'shipping_net'     => 0,
        'charges_net'      => 0,
        'total_net'        => 0,
        'tax'              => 0,
        'refund_amount'    => 0,
        'revenue'          => 0,
        'profit'           => 0,
        'margin'           => 0,
    );

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            $adata[] = array(

                'store'            => $data['Store Code'],
                'items_cost'       => money($data['items_cost'], $account->get('Account Currency Code')),
                'shipping_cost'    => money($data['shipping_cost'], $account->get('Account Currency Code')),
                'replacement_cost' => money($data['replacement_cost'], $account->get('Account Currency Code')),

                'items_net'    => money($data['items_net'], $account->get('Account Currency Code')),
                'shipping_net' => money($data['shipping_net'], $account->get('Account Currency Code')),
                'charges_net'  => money($data['charges_net'], $account->get('Account Currency Code')),
                'total_net'    => money($data['total_net'], $account->get('Account Currency Code')),

                'tax' => money($data['tax'], $account->get('Account Currency Code')),

                'refund_amount' => money($data['refund_amount'], $account->get('Account Currency Code')),
                'revenue'       => money($data['revenue'], $account->get('Account Currency Code')),
                'profit'        => money($data['profit'], $account->get('Account Currency Code')),
                'margin'        => percentage($data['profit'], $data['revenue'])
            );

            $totals['items_cost']       += $data['items_cost'];
            $totals['shipping_cost']    += $data['shipping_cost'];
            $totals['replacement_cost'] += $data['replacement_cost'];
            $totals['items_net']        += $data['items_net'];
            $totals['shipping_net']     += $data['shipping_net'];
            $totals['charges_net']      += $data['charges_net'];
            $totals['total_net']        += $data['total_net'];
            $totals['tax']              += $data['tax'];

            $totals['refund_amount'] += $data['refund_amount'];
            $totals['revenue']       += $data['revenue'];
            $totals['profit']        += $data['profit'];

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

    $totals['margin']        = percentage($totals['profit'], $totals['revenue']);
    $totals['refund_amount'] = money($totals['refund_amount'], $account->get('Account Currency Code'));
    $totals['revenue']       = money($totals['revenue'], $account->get('Account Currency Code'));
    $totals['profit']        = money($totals['profit'], $account->get('Account Currency Code'));


    $totals['items_cost']       = money($totals['items_cost'], $account->get('Account Currency Code'));
    $totals['shipping_cost']    = money($totals['shipping_cost'], $account->get('Account Currency Code'));
    $totals['replacement_cost'] = money($totals['replacement_cost'], $account->get('Account Currency Code'));
    $totals['items_net']        = money($totals['items_net'], $account->get('Account Currency Code'));
    $totals['shipping_net']     = money($totals['shipping_net'], $account->get('Account Currency Code'));
    $totals['charges_net']      = money($totals['charges_net'], $account->get('Account Currency Code'));
    $totals['total_net']        = money($totals['total_net'], $account->get('Account Currency Code'));
    $totals['tax']              = money($totals['tax'], $account->get('Account Currency Code'));


    $adata[] = $totals;

    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function lost_stock($_data, $db, $user, $account) {

    $rtext_label = 'incidents';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            switch ($data['type']) {
                case 'Other Out':
                    $type = _('Error');
                    break;
                case 'Broken':
                    $type = _('Damaged');
                    break;
                case 'Lost':
                    $type = _('Lost');
                    break;

                default:
                    $type = $data['type'];
            }


            $note = preg_replace('/\-?\d+ SKO (.+) /', '', $data['Note']);

            $staff = $data['User Alias'];

            $adata[] = array(
                'id'          => $data['Inventory Transaction Key'],
                'reference'   => sprintf('<span class="link" title="%s" onclick="change_view(\'part/%d\')">%s</span>', $data['Part Package Description'], $data['Part SKU'], $data['Part Reference']),
                'description' => $data['Part Package Description'],
                'stock'       => number($data['stock']),
                'type'        => $type,
                'value'       => money($data['value'], $account->get('Account Currency Code')),
                'date'        => strftime("%e %b %Y %k:%M", strtotime($data['date'].' +0:00')),
                'note'        => $note,
                'staff'       => $staff

            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function stock_given_free($_data, $db, $user, $account) {

    $rtext_label = 'transaction';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            switch ($data['type']) {
                case 'Replacement':
                    $type = _('Replacement');
                    break;
                case 'Order':
                    $type = _('Offer');
                    break;


                default:
                    $type = $data['type'];
            }


            $note = preg_replace('/\-?\d+ SKO (.+) /', '', $data['Note']);


            $adata[] = array(
                'id'            => $data['Inventory Transaction Key'],
                'reference'     => sprintf('<span class="link" title="%s" onclick="change_view(\'part/%d\')">%s</span>', $data['Part Package Description'], $data['Part SKU'], $data['Part Reference']),
                'description'   => $data['Part Package Description'],
                'stock'         => number($data['stock']),
                'type'          => $type,
                'value'         => money($data['value'], $account->get('Account Currency Code')),
                'date'          => strftime("%e %b %Y %k:%M", strtotime($data['date'].' +0:00')),
                'note'          => $note,
                'delivery_note' => sprintf('<span class="link" onclick="change_view(\'delivery_notes/%d/%d\')">%s</span>', $data['Delivery Note Store Key'], $data['Delivery Note Key'], $data['Delivery Note ID']),

            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


?>
