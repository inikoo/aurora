<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 28 Jul 2021 21:44:50 Malaysia Time, Kuala Lumpur, Malaysia
 *  Original: 22 January 2018 at 22:43:49 GMT+8, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

include_once 'utils/prepare_table.php';


class prepare_table_packers extends prepare_table {

    function __construct($db, $accounts, $user) {
        parent::__construct(...func_get_args());
        $this->record_label = [
            [
                '%s packer',
                '%s packers'
            ],
            [
                '%s packer of %s',
                '%s packers of %s'
            ],

        ];

        $this->navigation_sql = [
            'name' => '`Staff Name`',
            'key'  => '`Staff Key`'
        ];
    }


    function prepare_table() {


        $where_interval_working_hours = '';


        $this->where = " where `Inventory Transaction Type` = 'Sale'   ";

        $where_interval_feedback = '';
        if (isset($this->parameters['period'])) {

            include_once 'utils/date_functions.php';
            $tmp = calculate_interval_dates($this->db, $this->parameters['period'], $this->parameters['from'], $this->parameters['to']);

            $from                         = $tmp[1];
            $to                           = $tmp[2];
            $where_interval               = prepare_mysql_dates($from, $to, '`Date`');
            $this->where                  .= $where_interval['mysql'];
            $where_interval_working_hours = prepare_mysql_dates($from, $to, '`Timesheet Date`', 'only dates')['mysql'];
            $where_interval_feedback      = prepare_mysql_dates($from, $to, 'ITF2.`Date packed`')['mysql'];
        }


        $this->wheref = '';
        if ($this->parameters['f_field'] == 'name' and $this->f_value != '') {
            $this->wheref = sprintf(
                ' and `Staff Name` REGEXP "\\\\b%s" ', addslashes($this->f_value)
            );
        }


        $issues_percentage_field = '1';


        $this->order_direction = $this->sort_direction;


        if ($this->sort_key == 'name') {
            $this->order = '`Staff Name`';
        } elseif ($this->sort_key == 'packed') {
            $this->order = 'packed';
        } elseif ($this->sort_key == 'deliveries') {
            $this->order = 'deliveries';
        } elseif ($this->sort_key == 'dp' or $this->sort_key == 'dp_percentage') {
            $this->order = 'dp';
        } elseif ($this->sort_key == 'hrs') {
            $this->order = 'hrs';
        } elseif ($this->sort_key == 'dp_per_hour') {
            $this->order = 'dp_per_hour';
        } elseif ($this->sort_key == 'issues') {
            $this->order = "(select count(distinct `Feedback Key`) from `Feedback ITF Bridge` FIB  left join `Feedback Dimension` FD on (FD.`Feedback Key`=FIB.`Feedback ITF Feedback Key`)
             left join `Inventory Transaction Fact` ITF2 on   (ITF2.`Inventory Transaction Key`=`Feedback ITF Original Key`)  where   `Feedback Packer`='Yes' and  ITF2.`Packer Key`=ITF.`Packer Key` $where_interval_feedback ) ";
        } elseif ($this->sort_key == 'bonus') {
            $this->order = 'bonus';
        } elseif ($this->sort_key == 'issues_percentage') {
            $this->order             = '3';
            $issues_percentage_field = "(select count(distinct `Feedback Key`) from `Feedback ITF Bridge` FIB  left join `Feedback Dimension` FD on (FD.`Feedback Key`=FIB.`Feedback ITF Feedback Key`)
             left join `Inventory Transaction Fact` ITF2 on   (`Inventory Transaction Key`=`Feedback ITF Original Key`)  where   `Feedback Packer`='Yes' and  ITF2.`Packer Key`=ITF.`Packer Key` $where_interval_feedback ) /
  count(distinct ITF.`Delivery Note Key`,`Part SKU`)";

        } else {

            $this->order = '`Packer Key`';
        }


        $this->group_by = 'group by `Packer Key`';




        $this->table = ' `Inventory Transaction Fact` ITF  left join 

      
        `Staff Dimension` S on (S.`Staff Key`=ITF.`Packer Key`) ';

        $this->fields = "`Packer Key`,ITF.`Inventory Transaction Key`,
 (select count(distinct `Feedback Key`) from `Feedback ITF Bridge` FIB  left join `Feedback Dimension` FD on (FD.`Feedback Key`=FIB.`Feedback ITF Feedback Key`)
             left join `Inventory Transaction Fact` ITF2 on   (ITF2.`Inventory Transaction Key`=`Feedback ITF Original Key`)  where   `Feedback Packer`='Yes' and  ITF2.`Packer Key`=ITF.`Packer Key` $where_interval_feedback ) as issues,
  count(distinct ITF.`Delivery Note Key`,`Part SKU`) as dp ,

 $issues_percentage_field as issues_percentage ,


`Staff Name`,`Staff Key`, count(distinct ITF.`Delivery Note Key`) as deliveries , sum(`packed`) as packed,
 (select sum(`Timesheet Clocked Time`)/3600 from `Timesheet Dimension` where `Timesheet Staff Key`=`Packer Key` $where_interval_working_hours ) as hrs,
  count(distinct ITF.`Delivery Note Key`,`Part SKU`)/ (select sum(`Timesheet Clocked Time`)/3600 from `Timesheet Dimension` where `Timesheet Staff Key`=`Packer Key` $where_interval_working_hours )  as dp_per_hour";


        $this->sql_totals = "select"." count(Distinct `Packer Key` )  as num from $this->table  $this->where  ";


    }

    function get_data() {


        $total_dp = 0;
        $sql      = "select sum(`Inventory Transaction Weight`) as weight,count(distinct `Delivery Note Key`) as delivery_notes,count(distinct `Delivery Note Key`,`Part SKU`) as units  from  `Inventory Transaction Fact` $this->where group by `Packer Key` ";


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $total_dp += ($row['units']);

            }
        }

        if ($total_dp == 0) {
            $total_dp = 1;
        }



        $sql = "select $this->fields from $this->table $this->where $this->wheref $this->group_by order by $this->order $this->order_direction limit $this->start_from,$this->number_results";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        while ($data = $stmt->fetch()) {

            $hrs       = $data['hrs'] == '' ? 0 : $data['hrs'];

            $this->table_data[] = array(
                'id'                => $data['Staff Key'],
                'name'              => sprintf('<span class="link" onclick="change_view(\'report/packers/%d\', {parameters:{period:\'%s\'}})">%s</span>', $data['Staff Key'], $this->parameters['period'], $data['Staff Name']),
                'deliveries'        => number($data['deliveries']),
                'issues'            => sprintf('<span class="link" onclick="change_view(\'report/packers/%d\', {tab:\'packer.feedback\'  ,parameters:{period:\'%s\'}})">%s</span>', $data['Staff Key'], $this->parameters['period'], number($data['issues'])),
                'issues_percentage' => sprintf(
                    '<span class="link" onclick="change_view(\'report/packers/%d\', {tab:\'packer.feedback\'  ,parameters:{period:\'%s\'}})">%s</span>', $data['Staff Key'], $this->parameters['period'], percentage($data['issues'], $data['dp'])
                ),
                'packed'            => number($data['packed'], 0),
                'dp'                => number($data['dp']),
                'dp_percentage'     => percentage($data['dp'], $total_dp),
                'hrs'               => number($hrs, 1, true),
                'dp_per_hour'       => ($data['dp_per_hour'] == '' ? '' : number($data['dp_per_hour'], 1, true)),

            );


        }

    }
}