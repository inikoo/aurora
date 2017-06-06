<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 September 2015 13:54:37 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


function get_table_parameters() {

    return prepare_values(
        $_REQUEST, array(
                     'parameters' => array('type' => 'json array'),
                     'nr'         => array('type' => 'number'),
                     'page'       => array('type' => 'number'),
                     'o'          => array(
                         'type'     => 'string',
                         'optional' => true
                     ),
                     'od'         => array(
                         'type'     => 'string',
                         'optional' => true
                     ),
                     'f_value'    => array(
                         'type'     => 'string',
                         'optional' => true
                     ),
                     'f_field'    => array(
                         'type'     => 'string',
                         'optional' => true
                     ),

                 )
    );
}


function get_table_totals($db, $sql_totals, $wheref = '', $record_label = '', $metadata = '') {


    if ($sql_totals) {
        $sql = trim($sql_totals." $wheref");
        //print $sql;

        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $total = $row['num'];
            }
        } else {
            print_r($error_info = $db->errorInfo());
            exit;
        }

        if ($wheref != '') {
            $sql = $sql_totals;
            if ($row = $db->query($sql)->fetch()) {
                $total_records = $row['num'];
                $filtered      = $row['num'] - $total;
            }

        } else {
            $filtered      = 0;
            $filter_total  = 0;
            $total_records = $total;
        }

    } elseif ($metadata) {

        if (is_array($metadata)) {

            $filtered      = $metadata['filtered'];
            $filter_total  = $metadata['filter_total'];
            $total_records = $metadata['total_records'];
            $total         = $metadata['total'];
        }


    } else {
        return array(
            '',
            0,
            0
        );

    }


    if ($filtered == 0) {
        $rtext = get_rtext($record_label, $total_records);
    } else {
        $rtext = '<i class="fa fa-filter fa-fw"></i> '.get_rtext_with_filter(
                $record_label, $total, $total_records
            );
    }


    return array(
        $rtext,
        $total,
        $filtered
    );

}


function get_rtext($record_label, $total_records) {
    if ($record_label == 'customer') {
        return sprintf(
            ngettext('%s customer', '%s customers', $total_records), number($total_records)
        );
    } elseif ($record_label == 'order') {
        return sprintf(
            ngettext('%s order', '%s orders', $total_records), number($total_records)
        );
    } elseif ($record_label == 'store') {
        return sprintf(
            ngettext('%s store', '%s stores', $total_records), number($total_records)
        );
    } elseif ($record_label == 'department') {
        return sprintf(
            ngettext('%s department', '%s departments', $total_records), number($total_records)
        );
    } elseif ($record_label == 'family') {
        return sprintf(
            ngettext('%s family', '%s families', $total_records), number($total_records)
        );
    } elseif ($record_label == 'product') {
        return sprintf(
            ngettext('%s product', '%s products', $total_records), number($total_records)
        );
    } elseif ($record_label == 'category') {
        return sprintf(
            ngettext('%s category', '%s categories', $total_records), number($total_records)
        );
    } elseif ($record_label == 'order') {
        return sprintf(
            ngettext('%s order', '%s orders', $total_records), number($total_records)
        );
    } elseif ($record_label == 'item') {
        return sprintf(
            ngettext('%s item', '%s items', $total_records), number($total_records)
        );
    } elseif ($record_label == 'invoice') {
        return sprintf(
            ngettext('%s invoice', '%s invoices', $total_records), number($total_records)
        );
    } elseif ($record_label == 'delivery_note') {
        return sprintf(
            ngettext('%s delivery note', '%s delivery notes', $total_records), number($total_records)
        );
    } elseif ($record_label == 'part') {
        return sprintf(
            ngettext('%s part', '%s parts', $total_records), number($total_records)
        );
    } elseif ($record_label == 'discontinued part') {
        return sprintf(
            ngettext(
                '%s discontinued part', '%s discontinued parts', $total_records
            ), number($total_records)
        );
    } elseif ($record_label == 'supplier part') {
        return sprintf(
            ngettext(
                "%s supplier's part", "%s supplier's parts", $total_records
            ), number($total_records)
        );
    } elseif ($record_label == 'website') {
        return sprintf(
            ngettext('%s website', '%s websites', $total_records), number($total_records)
        );
    } elseif ($record_label == 'warehouse') {
        return sprintf(
            ngettext('%s warehouse', '%s warehouses', $total_records), number($total_records)
        );
    } elseif ($record_label == 'supplier') {
        return sprintf(
            ngettext('%s supplier', '%s suppliers', $total_records), number($total_records)
        );
    } elseif ($record_label == 'employee') {
        return sprintf(
            ngettext('%s employee', '%s employees', $total_records), number($total_records)
        );
    } elseif ($record_label == 'ex employee') {
        return sprintf(
            ngettext('%s ex employee', '%s ex employees', $total_records), number($total_records)
        );
    } elseif ($record_label == 'contractor') {
        return sprintf(
            ngettext('%s contractor', '%s contractors', $total_records), number($total_records)
        );
    } elseif ($record_label == 'user') {
        return sprintf(
            ngettext('%s user', '%s users', $total_records), number($total_records)
        );
    } elseif ($record_label == 'report') {
        return sprintf(
            ngettext('%s report', '%s reports', $total_records), number($total_records)
        );
    } elseif ($record_label == 'session') {
        return sprintf(
            ngettext('%s session', '%s sessions', $total_records), number($total_records)
        );
    } elseif ($record_label == 'list') {
        return sprintf(
            ngettext('%s list', '%s lists', $total_records), number($total_records)
        );
    } elseif ($record_label == 'customer who favored') {
        return sprintf(
            ngettext(
                '%s customer who favored', '%s customers who favored', $total_records
            ), number($total_records)
        );
    } elseif ($record_label == 'product favored') {
        return sprintf(
            ngettext(
                '%s product favored', '%s products favored', $total_records
            ), number($total_records)
        );
    } elseif ($record_label == 'query') {
        return sprintf(
            ngettext('%s query', '%s queries', $total_records), number($total_records)
        );
    } elseif ($record_label == 'search') {
        return sprintf(
            ngettext('%s search', '%s searches', $total_records), number($total_records)
        );
    } elseif ($record_label == 'transaction') {
        return sprintf(
            ngettext('%s transaction', '%s transactions', $total_records), number($total_records)
        );
    } elseif ($record_label == 'payment_account') {
        return sprintf(
            ngettext(
                '%s payment account', '%s payment accounts', $total_records
            ), number($total_records)
        );
    } elseif ($record_label == 'timesheet') {
        return sprintf(
            ngettext('%s timesheet', '%s timesheets', $total_records), number($total_records)
        );
    } elseif ($record_label == 'overtime') {
        return sprintf(
            ngettext('%s overtime', '%s overtimes', $total_records), number($total_records)
        );
    } elseif ($record_label == 'attachment') {
        return sprintf(
            ngettext('%s attachment', '%s attachments', $total_records), number($total_records)
        );
    } elseif ($record_label == 'year') {
        return sprintf(
            ngettext('%s year', '%s years', $total_records), number($total_records)
        );
    } elseif ($record_label == 'week') {
        return sprintf(
            ngettext('%s week', '%s weeks', $total_records), number($total_records)
        );
    } elseif ($record_label == 'month') {
        return sprintf(
            ngettext('%s month', '%s months', $total_records), number($total_records)
        );
    } elseif ($record_label == 'day') {
        return sprintf(
            ngettext('%s day', '%s days', $total_records), number($total_records)
        );
    } elseif ($record_label == 'operative') {
        return sprintf(
            ngettext('%s operative', '%s operatives', $total_records), number($total_records)
        );
    } elseif ($record_label == 'data set') {
        return sprintf(
            ngettext('%s data set', '%s data sets', $total_records), number($total_records)
        );
    } elseif ($record_label == 'time serie') {
        return sprintf(
            ngettext('%s time serie', '%s time series', $total_records), number($total_records)
        );
    } elseif ($record_label == 'image') {
        return sprintf(
            ngettext('%s image', '%s images', $total_records), number($total_records)
        );
    } elseif ($record_label == 'transaction') {
        return sprintf(
            ngettext('%s transaction', '%s transactions', $total_records), number($total_records)
        );
    } elseif ($record_label == 'campaign') {
        return sprintf(
            ngettext('%s campaign', '%s campaigns', $total_records), number($total_records)
        );
    } elseif ($record_label == 'offer') {
        return sprintf(
            ngettext('%s offer', '%s offers', $total_records), number($total_records)
        );
    } elseif ($record_label == 'purchase order') {
        return sprintf(
            ngettext('%s purchase order', '%s purchase orders', $total_records), number($total_records)
        );
    } elseif ($record_label == 'webpage block') {
        return sprintf(
            ngettext('%s webpage block', '%s webpage blocks', $total_records), number($total_records)
        );
    } elseif ($record_label == 'agent') {
        return sprintf(
            ngettext('%s agent', '%s agents', $total_records), number($total_records)
        );
    } elseif ($record_label == 'user category') {
        return sprintf(
            ngettext('%s user category', '%s user categories', $total_records), number($total_records)
        );
    } elseif ($record_label == 'delivery') {
        return sprintf(
            ngettext('%s delivery', '%s deliveries', $total_records), number($total_records)
        );
    } elseif ($record_label == 'deleted employee') {
        return sprintf(
            ngettext(
                '%s deleted employee', '%s deleted employees', $total_records
            ), number($total_records)
        );
    } elseif ($record_label == 'deleted contractor') {
        return sprintf(
            ngettext(
                '%s deleted contractor', '%s deleted contractors', $total_records
            ), number($total_records)
        );
    } elseif ($record_label == 'part location') {
        return sprintf(
            ngettext('%s part location', '%s part locations', $total_records), number($total_records)
        );
    } elseif ($record_label == 'part location with errors') {
        return sprintf(
            ngettext(
                '%s part location with errors', '%s part locations with errors', $total_records
            ), number($total_records)
        );
    } elseif ($record_label == 'production part') {
        return sprintf(
            ngettext(
                '%s production part', '%s production parts', $total_records
            ), number($total_records)
        );
    } elseif ($record_label == 'material') {
        return sprintf(
            ngettext('%s material', '%s materials', $total_records), number($total_records)
        );
    } elseif ($record_label == 'picking locations needed to replenish for ordered parts') {
        return sprintf(
            ngettext('%s picking location needed to replenish for ordered parts', '%s picking locations needed to replenish for ordered parts', $total_records), number($total_records)
        );
    } elseif ($record_label == 'webpage online') {
        return sprintf(
            ngettext('%s online web page', '%s online web pages', $total_records), number($total_records)
        );
    } elseif ($record_label == 'webpage offline') {
        return sprintf(
            ngettext('%s online web page', '%s offline web pages', $total_records), number($total_records)
        );
    } elseif ($record_label == 'replenishment') {
        return sprintf(
            ngettext('%s replenishment', '%s replenishments', $total_records), number($total_records)
        );
    } else {
        return sprintf(
            ngettext('%s record', '%s records', $total_records), number($total_records)
        );
    }
}


function get_rtext_with_filter($record_label, $total_with_filter, $total_no_filter) {
    if ($record_label == 'customer') {
        return sprintf(
            ngettext(
                '%s customer of %s', '%s customers of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'order') {
        return sprintf(
            ngettext('%s order of %s', '%s orders of %s', $total_with_filter), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'store') {
        return sprintf(
            ngettext('%s store of %s', '%s stores of %s', $total_with_filter), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'department') {
        return sprintf(
            ngettext(
                '%s department of %s', '%s departments of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'family') {
        return sprintf(
            ngettext(
                '%s family of %s', '%s families of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'product') {
        return sprintf(
            ngettext(
                '%s product of %s', '%s products of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'category') {
        return sprintf(
            ngettext(
                '%s category of %s', '%s categories of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'order') {
        return sprintf(
            ngettext('%s order of %s', '%s orders of %s', $total_with_filter), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'item') {
        return sprintf(
            ngettext('%s item of %s', '%s items of %s', $total_with_filter), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'invoice') {
        return sprintf(
            ngettext(
                '%s invoice of %s', '%s invoices of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'delivery_note') {
        return sprintf(
            ngettext(
                '%s delivery note of %s', '%s delivery notes of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'part') {
        return sprintf(
            ngettext('%s part of %s', '%s parts of %s', $total_with_filter), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'discontinued part') {
        return sprintf(
            ngettext(
                '%s discontinued part of %s', '%s discontinued parts of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'supplier part') {
        return sprintf(
            ngettext(
                "%s supplier's part of %s", "%s supplier's parts of %s", $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'website') {
        return sprintf(
            ngettext(
                '%s website of %s', '%s websites of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'warehouse') {
        return sprintf(
            ngettext(
                '%s warehouse of %s', '%s warehouses of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'supplier') {
        return sprintf(
            ngettext(
                '%s supplier of %s', '%s suppliers of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'employee') {
        return sprintf(
            ngettext(
                '%s employee of %s', '%s employees of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'ex employee') {
        return sprintf(
            ngettext(
                '%s ex employee of %s', '%s ex employees of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'contractor') {
        return sprintf(
            ngettext(
                '%s contractor of %s', '%s contractors of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'user') {
        return sprintf(
            ngettext('%s user of %s', '%s users of %s', $total_with_filter), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'user_group') {
        return sprintf(
            ngettext(
                '%s user group of %s', '%s user groups of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'report') {
        return sprintf(
            ngettext('%s report of %s', '%s reports of %s', $total_with_filter), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'session') {
        return sprintf(
            ngettext(
                '%s session of %s', '%s sessions of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'list') {
        return sprintf(
            ngettext('%s list of %s', '%s lists of %s', $total_with_filter), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'customer with favourites') {
        return sprintf(
            ngettext(
                '%s customer with favourites of %s', '%s customers with favourites of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'product favourited') {
        return sprintf(
            ngettext(
                '%s product favourited of %s', '%s products favourited of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'query') {
        return sprintf(
            ngettext('%s query of %s', '%s queries of %s', $total_with_filter), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'search') {
        return sprintf(
            ngettext(
                '%s search of %s', '%s searches of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'transaction') {
        return sprintf(
            ngettext(
                '%s transaction of %s', '%s transaction of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'payment_account') {
        return sprintf(
            ngettext(
                '%s payment account of %s', '%s payment accounts of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'timesheet') {
        return sprintf(
            ngettext(
                '%s timesheet of %s', '%s timesheets of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'overtime') {
        return sprintf(
            ngettext(
                '%s overtime of %s', '%s overtimes of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'attachment') {
        return sprintf(
            ngettext(
                '%s attachment of %s', '%s attachments of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'year') {
        return sprintf(
            ngettext('%s year of %s', '%s years of %s', $total_with_filter), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'month') {
        return sprintf(
            ngettext('%s month of %s', '%s months of %s', $total_with_filter), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'week') {
        return sprintf(
            ngettext('%s week of %s', '%s weeks of %s', $total_with_filter), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'day') {
        return sprintf(
            ngettext('%s day of %s', '%s days of %s', $total_with_filter), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'operative') {
        return sprintf(
            ngettext(
                '%s operative of %s', '%s operatives of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'time serie') {
        return sprintf(
            ngettext(
                '%s time serie of %s', '%s time series of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'data set') {
        return sprintf(
            ngettext(
                '%s data set of %s', '%s data sets of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'image') {
        return sprintf(
            ngettext('%s image of %s', '%s images of %s', $total_with_filter), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'transaction') {
        return sprintf(
            ngettext(
                '%s transaction of %s', '%s transactions of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'campaign') {
        return sprintf(
            ngettext(
                '%s campaign of %s', '%s campaigns of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'offer') {
        return sprintf(
            ngettext('%s offer of %s', '%s offers of %s', $total_with_filter), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'purchase order') {
        return sprintf(
            ngettext(
                '%s purchase order of %s', '%s purchase orders of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'webpage block') {
        return sprintf(
            ngettext(
                '%s webpage block of %s', '%s webpage blocks of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'agent') {
        return sprintf(
            ngettext('%s agent of %s', '%s agents of %s', $total_with_filter), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'user category') {
        return sprintf(
            ngettext(
                '%s user category of %s', '%s user categories of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'delivery') {
        return sprintf(
            ngettext(
                '%s delivery of %s', '%s deliveries of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'deleted employee') {
        return sprintf(
            ngettext(
                '%s deleted employee of %s', '%s deleted employees of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'deleted contractor') {
        return sprintf(
            ngettext(
                '%s deleted contractor of %s', '%s deleted contractors of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'part location') {
        return sprintf(
            ngettext(
                '%s part location of %s', '%s part locations of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'part location with errors') {
        return sprintf(
            ngettext(
                '%s part location with errors of %s', '%s part locations with errors of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'production part') {
        return sprintf(
            ngettext(
                '%s production part of %s', '%s production parts of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'material') {
        return sprintf(
            ngettext(
                '%s material of %s', '%s materials of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'picking locations needed to replenish for ordered parts') {
        return sprintf(
            ngettext(
                '%s picking location needed to replenish for ordered parts of %s', '%s picking locations needed to replenish for ordered parts of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'webpage online') {
        return sprintf(
            ngettext(
                '%s online web page of %s', '%s online web pages of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'webpage offline') {
        return sprintf(
            ngettext(
                '%s offline web page of %s', '%s offline web pages of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } elseif ($record_label == 'replenishment') {
        return sprintf(
            ngettext(
                '%s replenishment of %s', '%s replenishments of %s', $total_with_filter
            ), number($total_with_filter), number($total_no_filter)
        );
    } else {
        return sprintf(
            ngettext('%s record of %s', '%s records of %s', $total_with_filter), number($total_with_filter), number($total_no_filter)
        );
    }
}


?>
