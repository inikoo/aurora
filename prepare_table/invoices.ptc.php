<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 29 Jul 2021 23:09:29 Malaysia Time, Kuala Lumpur, Malaysia
 *  Original: 09 September 2013
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

include_once 'utils/prepare_table.php';


class prepare_table_invoices extends prepare_table {

    function __construct($db, $accounts, $user) {
        parent::__construct(...func_get_args());
        $this->record_label = [
            [
                '%s invoice',
                '%s invoices'
            ],
            [
                '%s invoice of %s',
                '%s invoices of %s'
            ],

        ];

        $this->navigation_sql = [
            'name' => '`Invoice Public ID`',
            'key'  => '`Invoice Key`'
        ];
    }


    function prepare_table() {


        if (isset($this->parameters['excluded_stores']) and is_array(
                $this->parameters['excluded_stores']
            ) and count($this->parameters['excluded_stores']) > 0) {
            $this->where = sprintf(
                ' where `Invoice Store Key` not in (%s)  ', join(',', $this->parameters['excluded_stores'])
            );
        } else {
            $this->where = ' where true';
        }
        $this->fields .= 'I.`Invoice Key`,`Invoice Paid`,`Invoice Type`,`Invoice Main Payment Method`,`Invoice Store Key`,`Invoice Customer Key`,I.`Invoice Public ID`,`Invoice Customer Name`,I.`Invoice Date`,`Invoice Total Amount`,`Invoice Currency`,
`Invoice Total Net Amount`,`Invoice Total Tax Amount`,`Invoice Shipping Net Amount`,`Invoice Items Net Amount`,`Invoice Total Net Amount`,`Invoice Shipping Net Amount`,
`Invoice Address Country 2 Alpha Code`';

        $this->table = '`Invoice Dimension` I left join `Payment Account Dimension` P on (P.`Payment Account Key`=I.`Invoice Payment Account Key`)  ';
        $where_type  = '';


        if ($this->parameters['parent'] == 'category') {
            include_once('class.Category.php');
            $category = new Category($this->parameters['parent_key']);


            $this->where = sprintf(
                " where `Subject`='Invoice' and  `Category Key`=%d", $this->parameters['parent_key']
            );
            $this->table = ' `Category Bridge` left join  `Invoice Dimension` I on (`Subject Key`=`Invoice Key`) ';
            $where_type  = '';

            $store_key = $category->data['Category Store Key'];

        } elseif ($this->parameters['parent'] == 'store') {
            if (is_numeric($this->parameters['parent_key']) and ($this->user->can_view('stores') or $this->user->can_view('accounting'))) {
                $this->where = sprintf(
                    ' where  `Invoice Store Key`=%d ', $this->parameters['parent_key']
                );
                include_once 'class.Store.php';
                $store    = new Store($this->parameters['parent_key']);
                $currency = $store->data['Store Currency Code'];
            } else {
                $this->where .= ' and  false';
            }


        } elseif ($this->parameters['parent'] == 'account') {

            if ($this->parameters['tab'] == 'billingregion_taxcategory.invoices') {

                $this->fields .= ',`Store Code`,`Store Name`,`Country Name`';
                $this->table  =
                    '`Invoice Dimension` I left join `Store Dimension` S on (S.`Store Key`=I.`Invoice Store Key`)  left join kbase.`Country Dimension` C on (I.`Invoice Address Country 2 Alpha Code`=C.`Country 2 Alpha Code`)  left join `Payment Account Dimension` P on (P.`Payment Account Key`=I.`Invoice Payment Account Key`)';

                $parents     = preg_split('/_/', $this->parameters['parent_key']);
                $this->where = sprintf(
                    'where  `Invoice Type`="Invoice" and  `Invoice Billing Region`=%s and `Invoice Tax Code`=%s', prepare_mysql($parents[0]), prepare_mysql($parents[1])
                );


            } elseif ($this->parameters['tab'] == 'billingregion_taxcategory.refunds') {

                $this->fields .= ',`Store Code`,`Store Name`,`Country Name`';
                $this->table  =
                    '`Invoice Dimension` I left join `Store Dimension` S on (S.`Store Key`=I.`Invoice Store Key`)  left join kbase.`Country Dimension` C on (I.`Invoice Address Country 2 Alpha Code`=C.`Country 2 Alpha Code`)  left join `Payment Account Dimension` P on (P.`Payment Account Key`=I.`Invoice Payment Account Key`)';

                $parents     = preg_split('/_/', $this->parameters['parent_key']);
                $this->where = sprintf(
                    'where  `Invoice Type`!="Invoice"  and  `Invoice Billing Region`=%s and `Invoice Tax Code`=%s  ', prepare_mysql($parents[0]), prepare_mysql($parents[1])
                );


            } else {


                if (is_numeric($this->parameters['parent_key']) and in_array(
                        $this->parameters['parent_key'], $this->user->stores
                    )) {

                    if (count($this->user->stores) == 0) {
                        $this->where = ' where false';
                    } else {

                        $this->where = sprintf(
                            'where  `Invoice Store Key` in (%s)  ', join(',', $this->user->stores)
                        );

                    }
                }
            }
        } elseif ($this->parameters['parent'] == 'order') {

            $this->table = '  `Invoice Dimension` I    left join `Payment Account Dimension` P on (P.`Payment Account Key`=I.`Invoice Payment Account Key`)';
            $this->where = sprintf('where  `Invoice Order Key`=%d  ', $this->parameters['parent_key']);

        } elseif ($this->parameters['parent'] == 'delivery_note') {

            $this->table = '`Invoice Delivery Note Bridge` B left join   `Invoice Dimension` I  on (I.`Invoice Key`=B.`Invoice Key`)     left join `Payment Account Dimension` P on (P.`Payment Account Key`=I.`Invoice Payment Account Key`)';
            $this->where = sprintf(
                'where  B.`Delivery Note Key`=%d  ', $this->parameters['parent_key']
            );

        } elseif ($this->parameters['parent'] == 'customer') {

            $this->where = sprintf(
                'where `Invoice Customer Key`=%d  ', $this->parameters['parent_key']
            );

        } elseif ($this->parameters['parent'] == 'sales_representative') {

            $this->where = sprintf(
                'where `Invoice Sales Representative Key`=%d  ', $this->parameters['parent_key']
            );

        } elseif ($this->parameters['parent'] == 'customer_product') {

            $parent_keys = preg_split('/_/', $this->parameters['parent_key']);

            $this->table = '`Order Transaction Fact` OTF  left join     `Invoice Dimension` I   on (OTF.`Invoice Key`=I.`Invoice Key`)  left join `Payment Account Dimension` P on (P.`Payment Account Key`=I.`Invoice Payment Account Key`) ';

            $this->where = sprintf(' where   `Customer Key`=%d  and `Product ID`=%d ', $parent_keys[0], $parent_keys[1]);

            //print $this->where;

            $group_by = ' group by OTF.`Invoice Key` ';


        } else {

            exit("unknown parent ".$this->parameters['parent']." \n");
        }


        if (isset($this->parameters['period'])) {
            include_once 'utils/date_functions.php';
            $tmp = calculate_interval_dates($this->db, $this->parameters['period'], $this->parameters['from'], $this->parameters['to']);

            $from           = $tmp[1];
            $to             = $tmp[2];
            $where_interval = prepare_mysql_dates($from, $to, 'I.`Invoice Date`');
            $this->where    .= $where_interval['mysql'];

        }


        if (isset($this->parameters['elements'])) {
            $elements = $this->parameters['elements'];


            switch ($this->parameters['elements_type']) {

                case('type'):
                    $_elements            = '';
                    $num_elements_checked = 0;
                    foreach ($elements['type']['items'] as $_key => $_value) {
                        if ($_value['selected']) {
                            $num_elements_checked++;

                            $_elements .= ", '$_key'";
                        }
                    }

                    if ($_elements == '') {
                        $this->where .= ' and false';

                    } elseif ($num_elements_checked < 2) {
                        $_elements   = preg_replace('/^,/', '', $_elements);
                        $this->where .= ' and `Invoice Type` in ('.$_elements.')';
                    }
                    break;
                case('payment'):
                    $_elements            = '';
                    $num_elements_checked = 0;

                    foreach ($elements['payment']['items'] as $_key => $_value) {
                        if ($_value['selected']) {
                            $num_elements_checked++;

                            $_elements .= ", '$_key'";
                        }
                    }
                    if ($_elements == '') {
                        $this->where .= ' and false';
                    } elseif ($num_elements_checked < 3) {
                        $_elements   = preg_replace('/^,/', '', $_elements);
                        $this->where .= ' and `Invoice Paid` in ('.$_elements.')';
                    }
                    break;
            }

        }


        if (($this->parameters['f_field'] == 'customer') and $this->f_value != '') {
            $this->wheref = sprintf(
                '  and  `Invoice Customer Name`  REGEXP "\\\\b%s" ', addslashes($this->f_value)
            );
        } elseif ($this->parameters['f_field'] == 'number' and $this->f_value != '') {
            $this->wheref .= " and  I.`Invoice Public ID` like '".addslashes(
                    preg_replace('/\s*|,|\./', '', $this->f_value)
                )."%' ";
        } elseif ($this->parameters['f_field'] == 'last_more' and is_numeric($this->f_value)) {
            $this->wheref .= " and  (TO_DAYS(NOW())-TO_DAYS(`Invoice Date`))>=".$this->f_value."    ";
        } elseif ($this->parameters['f_field'] == 'last_less' and is_numeric($this->f_value)) {
            $this->wheref .= " and  (TO_DAYS(NOW())-TO_DAYS(`Invoice Date`))<=".$this->f_value."    ";
        } elseif ($this->parameters['f_field'] == 'maxvalue' and is_numeric($this->f_value)) {
            $this->wheref .= " and  `Invoice Total Amount`<=".$this->f_value."    ";
        } elseif ($this->parameters['f_field'] == 'minvalue' and is_numeric($this->f_value)) {
            $this->wheref .= " and  `Invoice Total Amount`>=".$this->f_value."    ";
        }


        //=============================

        $this->order_direction = $this->sort_direction;

        if ($this->sort_key == 'date') {
            $this->order = '`Invoice Date`';
        } elseif ($this->sort_key == 'last_date') {
            $this->order = '`Invoice Last Updated Date`';
        } elseif ($this->sort_key == 'number') {
            $this->order = '`Invoice File As`';
        } elseif ($this->sort_key == 'total_amount') {
            $this->order = '`Invoice Total Amount`';
        } elseif ($this->sort_key == 'items') {
            $this->order = '`Invoice Items Net Amount`';
        } elseif ($this->sort_key == 'shipping') {
            $this->order = '`Invoice Shipping Net Amount`';
        } elseif ($this->sort_key == 'customer') {
            $this->order = '`Invoice Customer Name`';
        } elseif ($this->sort_key == 'payment_method') {
            $this->order = '`Invoice Main Payment Method`';
        } elseif ($this->sort_key == 'type') {
            $this->order = '`Invoice Type`';
        } elseif ($this->sort_key == 'state') {
            $this->order = '`Invoice Paid`';
        } elseif ($this->sort_key == 'net') {
            $this->order = '`Invoice Total Net Amount`';
        } elseif ($this->sort_key == 'tax') {
            $this->order = '`Invoice Total Tax Amount`';
        } elseif ($this->sort_key == 'store_code') {
            $this->order = '`Store Code`';
        } else {
            $this->order = 'I.`Invoice Key`';
        }


        $this->sql_totals = "select "."count(Distinct I.`Invoice Key`) as num from $this->table $this->where ";


    }

    function get_data() {


        $sql = "select $this->fields from $this->table $this->where $this->wheref order by $this->order $this->order_direction limit $this->start_from,$this->number_results";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        while ($data = $stmt->fetch()) {


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



            if ($this->parameters['parent'] == 'account') {
                $number = sprintf('<span class="link" onclick="change_view(\'invoice/%d\')">%s</span>', $data['Invoice Key'], $data['Invoice Public ID']);

            } else {
                $number = sprintf('<span class="link" onclick="change_view(\'invoices/%d/%d\')">%s</span>', $data['Invoice Store Key'], $data['Invoice Key'], $data['Invoice Public ID']);
            }

            $this->table_data[] = array(
                'id'                   => (integer)$data['Invoice Key'],
                'number'               => $number,
                'customer'             => sprintf('<span class="link" onclick="change_view(\'customers/%d/%d\')">%s</span>', $data['Invoice Store Key'], $data['Invoice Customer Key'], $data['Invoice Customer Name']),
                'date'                 => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Invoice Date'].' +0:00')),
                'total_amount'         => money($data['Invoice Total Amount'], $data['Invoice Currency']),
                'net'                  => money($data['Invoice Total Net Amount'], $data['Invoice Currency']),
                'tax'                  => money($data['Invoice Total Tax Amount'], $data['Invoice Currency']),
                'shipping'             => money($data['Invoice Shipping Net Amount'], $data['Invoice Currency']),
                'items'                => money($data['Invoice Items Net Amount'], $data['Invoice Currency']),
                'type'                 => $type,
                'state'                => $state,
                'billing_country'      => $data['Invoice Address Country 2 Alpha Code'],
                'billing_country_flag' => sprintf('<img alt="%s" title="%s" src="/art/flags/%s.png">', $data['Invoice Address Country 2 Alpha Code'],$data['Country Name']??'', strtolower($data['Invoice Address Country 2 Alpha Code'])),
                'store_code'           => sprintf('<span title="%s">%s</span>', $data['Store Name']??'', $data['Store Code']??''),

            );


        }

    }
}