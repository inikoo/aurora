<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 September 2016 at 19:14:53 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/



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



$wheref = '';
if ($parameters['f_field'] == 'tax_number' and $f_value != '') {
    $wheref .= " and  `Invoice Tax Number` like '".addslashes($f_value)."%'    ";
} elseif ($parameters['f_field'] == 'customer' and $f_value != '') {
    $wheref = sprintf(
        '  and  `Customer Name`  REGEXP "\\\\b%s" ', addslashes($f_value)
    );
}

//print $where;
//exit;

$_order = $order;
$_dir   = $order_direction;


if ($order == 'tax_number') {
    $order = '`Invoice Tax Number`';


} else {
    if ($order == 'invoices') {
        $order = 'invoices';
    } else {
        if ($order == 'refunds') {
            $order = 'refunds';
        } else {
            if ($order == 'country_code') {
                $order
                    = '`Invoice Address Country 2 Alpha Code`,`Invoice Tax Number`';

                if ($order_direction != '') {
                    $order
                        = '`Invoice Address Country 2 Alpha Code` desc ,`Invoice Tax Number`';

                }

                $order_direction = '';

            } else {
                if ($order == 'net') {
                    $order = 'net';
                } else {
                    if ($order == 'tax') {
                        $order = 'tax';
                    } else {
                        if ($order == 'total') {
                            $order = 'total';
                        } else {
                            $order = '`Invoice Billing Region`';
                        }
                    }
                }
            }
        }
    }
}


$group_by
    = 'group by `Invoice Tax Number`,`Invoice Address Country 2 Alpha Code`,`Invoice Customer Key`';

$table = '  `Invoice Dimension` ';

$sql_totals = "";


$fields
    = "
`Invoice Tax Code`,`Invoice Customer Name`,`Invoice Customer Key`,`Invoice Tax Number`,`Invoice Tax Number Valid`,`Invoice Address Country 2 Alpha Code`,`Invoice Tax Number Valid`,
sum(if(`Invoice Type`='Invoice',1,0)) as invoices,
 sum(if(`Invoice Type`!='Invoice',1,0)) as refunds,

  sum(`Invoice Total Amount`*`Invoice Currency Exchange`) as total ,
  sum( `Invoice Total Net Amount`*`Invoice Currency Exchange`) as net,
  sum( `Invoice Total Tax Amount`*`Invoice Currency Exchange`) as tax



";

?>
