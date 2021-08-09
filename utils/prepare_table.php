<?php /** @noinspection DuplicatedCode */
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sat, 10 Jul 2021 20:09:51 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */
include_once 'utils/date_functions.php';

class prepare_table {
    public string $rtext_label;
    public array $parameters;

    public int $number_results;
    /**
     * @var float|int
     */
    public $start_from;
    /**
     * @var mixed|string
     */
    public $order;

    public string $order_direction;
    /**
     * @var mixed
     */
    public $f_value;

    public string $table;

    public string $fields;

    public string $sql_totals;

    public string $sql_data;

    public string $where;

    public string $wheref;

    public string $rtext;

    public string $rtext_suffix;

    public int $total;

    public int $filtered;

    public array $record_label;

    public PDO $db;

    public int $total_records;

    public Account $account;

    public array $table_data;
    /**
     * @var mixed|string
     */
    public $sort_key;

    public string $sort_direction;

    public User $user;

    public string $group_by;

    public array $navigation_sql;
    /**
     * @var mixed
     */
    public $object;


    function __construct(PDO $db, Account $account, User $user) {

        $this->db      = $db;
        $this->account = $account;
        $this->user    = $user;

        $this->object = false;

        $this->group_by   = '';
        $this->where      = '';
        $this->wheref     = '';
        $this->table      = '';
        $this->fields     = '';
        $this->sql_totals = '';
        $this->sql_data   = '';
        $this->order_direction='';

        $this->record_label = [
            [
                '%s item',
                '%s items'
            ],
            [
                '%s item of %s',
                '%s items of %s'
            ],

        ];

        $this->rtext    = '';
        $this->rtext_suffix='';
        $this->total    = 0;
        $this->filtered = 0;

        $this->table_data = [];


    }


    function initialize($args) {
        $this->parameters     = $args['parameters'];
        $this->number_results = $args['nr'];
        $this->start_from     = ($args['page'] - 1) * $this->number_results;
        $this->sort_key       = ($args['o'] ?? 'id');
        $this->sort_direction = ((isset($args['od']) and preg_match('/desc/i', $args['od'])) ? 'desc' : '');



        if (isset($args['f_value']) and $args['f_value'] != '') {
            $this->f_value = $args['f_value'];
        } else {
            $this->f_value = '';
        }

        if (isset($args['f_field']) and $args['f_field'] != '') {
            $this->parameters['f_field'] = $args['f_field'];
        }
    }


    function fetch($args) {
        $this->initialize($args);
        $this->update_session();
        $this->prepare_table();
        $this->calculate_table_totals();
        $this->totals_formatted_text();
        $this->get_data();

        $response = array(
            'resultset' => array(
                'state'         => 200,
                'data'          => $this->table_data,
                'rtext'         => $this->rtext.$this->rtext_suffix,
                'sort_key'      => $this->sort_key,
                'sort_dir'      => $this->sort_direction,
                'total_records' => $this->total

            )
        );

        return json_encode($response);
    }


    function prepare_table() {

    }

    function get_data() {

    }

    function initialize_from_session($tab) {



        $this->parameters = $_SESSION['table_state'][$tab];
        $this->f_value    = $_SESSION['table_state'][$tab]['f_value'];

        $this->number_results = $_SESSION['table_state'][$tab]['nr']??1000;
        $this->start_from     = 0;
        $this->sort_key       =$_SESSION['table_state'][$tab]['nr']??'id';
        $this->sort_direction = ( ($_SESSION['table_state'][$tab]['od']??-1)?'desc':'');

    }

    function update_session() {


        foreach ($this->parameters as $parameter => $parameter_value) {
            $_SESSION['table_state'][$this->parameters['tab']][$parameter] = $parameter_value;
        }


        $_SESSION['table_state'][$this->parameters['tab']]['o']       = $this->sort_key;
        $_SESSION['table_state'][$this->parameters['tab']]['od']      = ($this->sort_direction == '' ? -1 : 1);
        $_SESSION['table_state'][$this->parameters['tab']]['nr']      = $this->number_results;
        $_SESSION['table_state'][$this->parameters['tab']]['f_value'] = $this->f_value;


        if (isset($this->parameters['invoices_vat'])) {
            $_SESSION['table_state'][$this->parameters['tab']]['invoices_vat'] = $this->parameters['invoices_vat'];
        }
        if (isset($this->parameters['invoices_no_vat'])) {
            $_SESSION['table_state'][$this->parameters['tab']]['invoices_no_vat'] = $this->parameters['invoices_no_vat'];
        }
        if (isset($this->parameters['invoices_null'])) {
            $_SESSION['table_state'][$this->parameters['tab']]['invoices_null'] = $this->parameters['invoices_null'];
        }

    }


    function calculate_table_totals($metadata = '') {

        $this->filtered      = 0;
        $this->total_records = 0;
        $this->total         = 0;
        if ($this->sql_totals) {
            $this->total = 0;

            $sql         = trim($this->sql_totals." $this->wheref");
            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $this->total = $row['num'];
                }
            }

            if ($this->wheref != '') {
                $sql = $this->sql_totals;
                if ($row = $this->db->query($sql)->fetch()) {
                    $this->total_records = $row['num'];
                    $this->filtered      = $row['num'] - $this->total;
                }

            } else {
                $this->filtered      = 0;
                $this->total_records = $this->total;
            }

        } elseif ($metadata) {

            if (is_array($metadata)) {

                $this->filtered      = $metadata['filtered'];
                $this->total_records = $metadata['total_records'];
                $this->total         = $metadata['total'];
            }


        }


    }


    function totals_formatted_text() {

        if ($this->filtered == 0) {
            $this->rtext = sprintf(ngettext($this->record_label[0][0], $this->record_label[0][1], $this->total_records), number($this->total_records));


        } else {
            $this->rtext = '<i class="fa fa-filter fa-fw"></i> '.sprintf(
                    ngettext(
                        $this->record_label[1][0], $this->record_label[1][1], $this->total
                    ), number($this->total), number($this->total_records)
                );


        }


        if (isset($this->parameters['period']) and $this->parameters['period'] != 'all') {
            include_once 'utils/date_functions.php';


            $tmp = calculate_interval_dates(
                $this->db, $this->parameters['period'], $this->parameters['from'], $this->parameters['to']
            );


            $_from = strftime('%d %b %Y', strtotime($tmp[1]));
            $_to   = strftime('%d %b %Y', strtotime($tmp[2]));
            if ($_from != $_to) {
                $this->rtext .= " ($_from-$_to)";
            } else {
                $this->rtext .= " ($_from)";
            }


        }


        if (isset($this->parameters['parent_period']) and $this->parameters['parent_period'] != 'all') {
            include_once 'utils/date_functions.php';


            $tmp = calculate_interval_dates(
                $this->db, $this->parameters['parent_period'], $this->parameters['parent_from'], $this->parameters['parent_to']
            );


            $_from = strftime('%d %b %Y', strtotime($tmp[1]));
            $_to   = strftime('%d %b %Y', strtotime($tmp[2]));


            if ($_from != $_to) {
                $this->rtext .= " ($_from-$_to)";
            } else {
                $this->rtext .= " ($_from)";
            }


        }
    }

    function get_navigation($object, $tab, $data): array {

        $args = [
            'nr'   => 0,
            'page' => 0,
        ];


        if (isset($_SESSION['table_state'][$tab])) {
            $args['o']       = $_SESSION['table_state'][$tab]['o'];
            $args['od']      = ($_SESSION['table_state'][$tab]['od'] == 1 ? 'desc' : '');
            $args['f_value'] = $_SESSION['table_state'][$tab]['f_value'];
            $parameters      = $_SESSION['table_state'][$tab];
        } else {
            $default         = $this->user->get_tab_defaults($tab);
            $args['o']       = $default['sort_key'];
            $args['od']      = ($default['sort_order'] == 1 ? 'desc' : '');
            $args['f_value'] = '';
            $parameters      = $default;

        }
        $parameters['parent']     = $data['parent'];
        $parameters['parent_key'] = $data['parent_key'];
        $args['parameters']       = $parameters;


        $this->initialize($args);

        $this->prepare_table();

        return $this->process_navigation($object);

    }


    private function process_navigation($object): array {
        $order_by_key = false;


        if ($this->order == $this->navigation_sql['key']) {
            $order_by_key = true;
        }


        $order = preg_replace('/^.*\.`/', '', $this->order);
        $order = preg_replace('/^`/', '', $order);
        $order = preg_replace('/`$/', '', $order);

        $_order_field_value = $object->get($order);


        $prev_title = '';
        $next_title = '';
        $prev_key   = 0;
        $next_key   = 0;


        if($order_by_key){
            $sql = " {$this->navigation_sql['name']} as object_name, {$this->navigation_sql['key']} as object_key 
            from $this->table   $this->where $this->wheref and  $this->order < ?
            order by $this->order  desc limit 1";

            $args=[
                $_order_field_value
            ];

        }else{
            $sql = " {$this->navigation_sql['name']} as object_name, {$this->navigation_sql['key']} as object_key 
            from $this->table   $this->where $this->wheref 
            and ( $this->order < ? OR ($this->order = ? AND {$this->navigation_sql['key']} < ? ))  
            order by $this->order desc , {$this->navigation_sql['key']} desc limit 1";

            $args=[
                $_order_field_value,
                $_order_field_value,
                $object->id
            ];
        }




        $stmt = $this->db->prepare('select '.$sql);
        $stmt->execute($args);
        while ($row = $stmt->fetch()) {
            $prev_key   = $row['object_key'];
            $prev_title = $row['object_name'];
        }

        if($order_by_key){
            $sql = " {$this->navigation_sql['name']} as object_name, {$this->navigation_sql['key']} as object_key 
            from $this->table   $this->where $this->wheref and  $this->order > ?
            order by $this->order   limit 1";
        }else{
            $sql = " {$this->navigation_sql['name']} as object_name, {$this->navigation_sql['key']} as object_key 
            from $this->table  $this->where $this->wheref 
            and ( $this->order > ? OR ($this->order = ? AND {$this->navigation_sql['key']} > ? ))  
            order by $this->order  , {$this->navigation_sql['key']}  limit 1";
        }



        $stmt = $this->db->prepare('select '.$sql);
        $stmt->execute($args);
        while ($row = $stmt->fetch()) {
            //print_r($row);
            $next_key   = $row['object_key'];
            $next_title = $row['object_name'];
        }

        if ($this->sort_direction == 'desc' and !$order_by_key ) {
            $_tmp1      = $prev_key;
            $_tmp2      = $prev_title;
            $prev_key   = $next_key;
            $prev_title = $next_title;
            $next_key   = $_tmp1;
            $next_title = $_tmp2;
        }


        return [
            $prev_key,
            $prev_title,
            $next_key,
            $next_title,

        ];


    }

}