<?php
/*

 This file contains the Campaign Class

 About:
Refurbished: 8 August 2017 at 15:16:40 CEST, Tranava , Slovakia

 Author: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2013, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';

class DealCampaign extends DB_Table {

    /**
     * @var \PDO
     */
    public $db;

    function __construct($a1, $a2 = false, $a3 = false, $_db = false) {

        if (!$_db) {
            global $db;
            $this->db = $db;
        } else {
            $this->db = $_db;
        }


        $this->table_name         = 'Deal Campaign';
        $this->ignore_fields      = array('Deal Campaign Key');
        $this->new_deal_component = false;
        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } else {
            if (($a1 == 'new' or $a1 == 'create') and is_array($a2)) {
                $this->find($a2, 'create');

            } elseif (preg_match('/find/i', $a1)) {
                $this->find($a2, $a1);
            } else {
                $this->get_data($a1, $a2, $a3);
            }
        }

    }


    function get_data($tipo, $tag, $tag2 = false) {

        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Deal Campaign Dimension` WHERE `Deal Campaign Key`=%d", $tag
            );
        } elseif ($tipo == 'code_store') {
            $sql = sprintf(
                "SELECT * FROM `Deal Campaign Dimension` WHERE `Deal Campaign Code`=%s AND `Deal Campaign Store Key`=%d", prepare_mysql($tag), $tag2
            );
        } elseif ($tipo == 'name_store') {
            $sql = sprintf(
                "SELECT * FROM `Deal Campaign Dimension` WHERE `Deal Campaign Name`=%s AND `Deal Campaign Store Key`=%d", prepare_mysql($tag), $tag2
            );
        } else {
            $sql = sprintf("SELECT * FROM `Deal Campaign Dimension` WHERE FALSE");
        }


        if ($this->data = $this->db->query($sql)->fetch()) {

            $this->id = $this->data['Deal Campaign Key'];
        }

    }


    function find($raw_data, $options) {

        if (isset($raw_data['editor']) and is_array($raw_data['editor'])) {
            foreach ($raw_data['editor'] as $key => $value) {
                if (array_key_exists($key, $this->editor)) {
                    $this->editor[$key] = $value;
                }
            }
        }

        $this->candidate = array();
        $this->found     = false;
        $this->found_key = 0;
        $create          = '';

        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }


        $data = $this->base_data();


        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $data[$key] = $value;
            }
        }

        $sql = sprintf(
            "SELECT `Deal Campaign Key` FROM `Deal Campaign Dimension` WHERE  `Deal Campaign Name`=%s AND `Deal Campaign Store Key`=%d ", prepare_mysql($data['Deal Campaign Name']), $data['Deal Campaign Store Key']
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $this->found     = true;
                $this->found_key = $row['Deal Campaign Key'];
                $this->get_data('id', $this->found_key);
                $this->duplicated_field = 'Deal Campaign Name';

                return;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        if ($this->found) {
            $this->get_data('id', $this->found_key);
        }


        if ($create and !$this->found) {
            $this->create($data);

        }


    }


    function create($data) {

        $keys = "";

        $values = "";
        foreach ($data as $key => $value) {
            $keys .= "`$key`,";
            if ($key == 'Deal Campaign Description') {
                $values .= prepare_mysql($value, false).",";
            } else {
                $values .= prepare_mysql($value).",";
            }
        }
        $keys   = preg_replace('/,$/', '', $keys);
        $values = preg_replace('/,$/', '', $values);


        // print_r($data);
        $sql = sprintf(
            "INSERT INTO `Deal Campaign Dimension` (%s) VALUES(%s)", $keys, $values
        );


        if ($this->db->exec($sql)) {
            $this->id  = $this->db->lastInsertId();
            $this->msg = "Campaign created";
            $this->get_data('id', $this->id);


            $this->new = true;

            $history_data = array(
                'Action'           => 'created',
                'History Abstract' => _("Campaign created"),
                'History Details'  => ''
            );
            $this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id);

            $this->fork_index_elastic_search();

            return $this;
        } else {
            $this->msg = "Error can not create campaign";
            print $sql;
            exit;
        }


    }


    /*
    function update_status_from_dates() {


        if ($this->data['Deal Campaign Status'] == 'Waiting' and strtotime($this->data['Deal Campaign Valid From'].' +0:00') < strtotime('now +0:00')) {
            $this->update_field_switcher(
                'Deal Campaign Status', 'Active', 'no_history'
            );
        }


        if ($this->data['Deal Campaign Valid To'] != '' and strtotime($this->data['Deal Campaign Valid To'].' +0:00') < strtotime('now +0:00')) {

            $this->update_field_switcher('Deal Campaign Status', 'Finish', 'no_history');

        }


    }

    */

    function update_field_switcher($field, $value, $options = '', $metadata = '') {

        switch ($field) {

            case 'Deal Campaign Store Send Order Recursion Emails':


                $store         = get_object('store', $this->data['Deal Campaign Store Key']);
                $store->editor = $this->editor;
                $store->update(array('Store Send Order Recursion Emails' => $value), $options);


                break;

            case 'Deal Campaign Send Order Recursion Reminder':


                $store         = get_object('store', $this->data['Deal Campaign Store Key']);
                $store->editor = $this->editor;
                $store->update(array('Deal Terms' => $value.' day'), $options);


                break;


            case 'Deal Campaign Order Recursion Days':


                if (!is_numeric($value)) {
                    $this->error = true;
                    $this->msg   = 'value must be numeric';
                }

                if ($value <= 0) {
                    $this->error = true;
                    $this->msg   = 'value must be more than zero';
                }


                $deals = $this->get_deals();
                $deal  = array_pop($deals);


                $deal->editor = $this->editor;
                $deal->update(array('Deal Terms' => $value.' day'), $options);


                break;

            case 'Deal Campaign Deal Term Label':
                $deals = $this->get_deals();
                $deal  = array_pop($deals);


                $deal->editor = $this->editor;
                $deal->update(array('Deal Term Label' => $value), $options);


                break;

            case 'Deal Campaign Icon':

                $this->update_field($field, $value, $options);

                $sql = sprintf('UPDATE `Deal Dimension` SET `Deal Icon`=%s WHERE `Deal Campaign Key`=%d  ', prepare_mysql($value), $this->id);
                $this->db->exec($sql);
                $sql = sprintf('UPDATE `Deal Component Dimension` SET `Deal Component Icon`=%s WHERE `Deal Component Campaign Key`=%d  ', prepare_mysql($value), $this->id);
                $this->db->exec($sql);
                $this->fork_index_elastic_search();

                break;

            case 'Deal Campaign Name':


                $this->update_field($field, $value, $options);


                require_once 'utils/new_fork.php';
                new_housekeeping_fork(
                    'au_housekeeping', array(
                    'type'     => 'deal_campaign_changed',
                    'field'=>$field,
                    'deal_campaign_key' => $this->id,
                    'editor'      => $this->editor
                ), DNS_ACCOUNT_CODE, $this->db
                );



                $this->fork_index_elastic_search();
                break;
            case 'Deal Campaign Code':
                $this->update_field($field, $value, $options);
                $this->fork_index_elastic_search();
                break;
            default:


                if (array_key_exists($field, $this->base_data())) {

                    if ($value != $this->data[$field]) {
                        $this->update_field($field, $value, $options);

                    }
                }


        }


    }

    function get_deals($type = 'objects') {
        $deals = array();
        $sql   = sprintf(
            "SELECT `Deal Key` FROM `Deal Dimension` WHERE `Deal Campaign Key`=%d ORDER BY `Deal Key`", $this->id
        );
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($type == 'objects') {
                    $deals[] = get_object('deal', $row['Deal Key']);
                } else {
                    $deals[] = $row['Deal Key'];
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $deals;

    }



    function create_deal($data, $component_data = '') {


        include_once 'class.Deal.php';

        if (!array_key_exists('Deal Name', $data) or $data['Deal Name'] == '') {
            $this->error = true;
            $this->msg   = 'error, no deal name';

            return;
        }

        if (!array_key_exists('Deal Begin Date', $data)) {
            $this->error = true;
            $this->msg   = 'error, no deal start date';

            return;
        }

        $data['Deal Campaign Key'] = $this->id;
        $data['Deal Store Key']    = $this->data['Deal Campaign Store Key'];
        $data['Deal Icon']         = $this->get('Deal Campaign Icon');


        $data['editor'] = $this->editor;


        $deal = new Deal('find create', $data);


        //print_r($data);

        if ($deal->id) {
            $this->new_object_msg = $deal->msg;

            if ($deal->new) {
                $this->new_object = true;

                if ($component_data != '') {
                    $deal_component           = $deal->add_component($component_data);
                    $this->new_deal_component = $deal_component;
                }

                if (!empty($data['Voucher'])) {
                    include_once 'class.Voucher.php';


                    $voucher_data = array(
                        'Voucher Store Key'                => $this->data['Deal Campaign Store Key'],
                        'Voucher Deal Key'                 => $deal->id,
                        'Voucher Code'                     => ($data['Voucher Data']['Voucher Auto Code'] ? '' : $data['Voucher Data']['Voucher Code']),
                        'Voucher Usage Limit per Customer' => 1

                    );

                    $voucher_ok = false;


                    if (!$data['Voucher Data']['Voucher Auto Code'] and $data['Voucher Data']['Voucher Code'] != '') {
                        $voucher_data['Voucher Code'] = $data['Voucher Data']['Voucher Code'];
                        $voucher                      = new Voucher('find create', $voucher_data);

                        if (!$voucher->error) {
                            $voucher_ok = true;
                        }


                    }

                    if (!$voucher_ok) {


                        for ($j = 0; $j < 1000; $j++) {

                            $voucher_length = 6;

                            if ($j > 50) {
                                $voucher_length = 7;
                            } elseif ($j > 100) {
                                $voucher_length = 8;
                            } elseif ($j > 200) {
                                $voucher_length = 9;
                            } elseif ($j > 300) {
                                $voucher_length = 10;
                            } elseif ($j > 500) {
                                $voucher_length = 12;
                            }

                            $chars        = "0123456789ABCDEFGHJKLMNPQRSTUVWXYZABCDEFGHJKLMNPQRSTUVWXYZ";
                            $voucher_code = "";
                            for ($i = 0; $i < $voucher_length; $i++) {
                                $voucher_code .= $chars[mt_rand(0, strlen($chars) - 1)];
                            }
                            $voucher_data['Voucher Code'] = $voucher_code;


                            $voucher = new Voucher('find create', $voucher_data);

                            if (!$voucher->error) {


                                break;
                            }


                        }


                    }


                    $deal->fast_update(
                        array(
                            'Deal Voucher Key' => $voucher->id,
                            'Deal Terms'       => json_encode(
                                array(
                                    'voucher' => $voucher->get('Voucher Code'),
                                    'amount'  => $deal->get('Deal Terms')

                                )
                            )
                        )
                    );


                }

                if (!empty($data['Voucher'])) {
                    $history_abstract = sprintf(_('Offer %s, voucher: %s (%s) created'), $deal->get('Deal Name'), $voucher->get('Voucher Code'), $deal->get('Deal Term Allowances Label'));

                } else {
                    $history_abstract = sprintf(_('Offer %s (%s) created'), $deal->get('Deal Name'), $deal->get('Deal Term Allowances Label'));

                }


                $history_data = array(
                    'History Abstract' => $history_abstract,
                    'History Details'  => '',
                    'Action'           => 'created'
                );

                $deal->add_subject_history(
                    $history_data, true, 'No', 'Changes', $deal->get_object_name(), $deal->id
                );

                $deal->update_deal_term_allowances();


                $account = get_object('Account', 1);
                require_once 'utils/new_fork.php';
                new_housekeeping_fork(
                    'au_housekeeping', array(
                    'type'     => 'deal_created',
                    'deal_key' => $deal->id
                ), $account->get('Account Code'), $this->db
                );


            } else {
                $this->error = true;
                if ($deal->found) {

                    $this->error_code     = 'duplicated_field';
                    $this->error_metadata = json_encode(array($deal->duplicated_field));

                    if ($deal->duplicated_field == 'Deal Name') {
                        $this->msg = sprintf(_('Duplicated name %s'), $data['Deal Name']);
                    }


                } else {
                    $this->msg = $deal->msg;
                }
            }


            return $deal;
        } else {
            $this->error = true;
            $this->msg   = $deal->msg;
        }


        //        $deal->update_status_from_dates();


        //$this->update_number_of_deals();


        return $deal;
    }
    function get($key = '') {

        if (!$this->id) {
            return;
        }

        switch ($key) {

            case 'Valid From':
                if ($this->data['Deal Campaign Valid From'] == '') {
                    return '';
                } else {

                    return strftime("%e %b %Y", strtotime($this->data['Deal Campaign Valid From'].' +0:00'));
                }

                break;
            case 'Valid To':
                if ($this->data['Deal Campaign Valid To'] == '') {
                    return '';
                } else {
                    return strftime("%e %b %Y", strtotime($this->data['Deal Campaign Valid To'].' +0:00'));
                }
                break;
            case 'Status':
                switch ($this->data['Deal Campaign Status']) {
                    case 'Waiting':
                        return _('Waiting');
                        break;
                    case 'Suspended':
                        return _('Suspended');
                        break;
                    case 'Active':
                        return _('Active');
                        break;
                    case 'Finish':
                        return _('Finished');
                        break;
                    case 'Waiting':
                        return _('Waiting');
                        break;
                    default:
                        return $this->data['Deal Campaign Status'];
                }

                break;


            case 'Number Deals':
            case 'Number Active Deals':
            case 'Number Deal Components':
            case 'Number Active Deal Components':
                return number($this->data['Deal Campaign '.$key]);

                break;

            case 'Used Orders':
            case 'Used Customers':
            case 'Applied Orders':
            case 'Applied Customers':


                return number($this->data['Deal Campaign Total Acc '.$key]);

                break;

            case 'Number History Records':
            case 'Number Deals':
            case 'Number Current Deals':
                return number($this->data['Deal Campaign '.$key]);
                break;
            case 'Deals Numbers':

                if ($this->data['Deal Campaign Number Current Deals'] != $this->data['Deal Campaign Number Deals']) {
                    return number($this->data['Deal Campaign Number Current Deals']).','.number($this->data['Deal Campaign Number Deals']);
                } else {
                    return number($this->data['Deal Campaign Number Current Deals']);
                }


            case 'Interval':
            case 'Duration':
                if (!$this->data['Deal Campaign Valid To']) {
                    $duration = _('Permanent');
                } else {
                    if ($this->data['Deal Campaign Valid From']) {
                        $duration = strftime("%a %e %b %Y", strtotime($this->data['Deal Campaign Valid From']." +00:00")).' - ';
                    } else {
                        $duration = '? -';
                    }
                    $duration .= strftime(
                        "%a %e %b %Y", strtotime($this->data['Deal Campaign Valid To']." +00:00")
                    );
                }

                return $duration;
            case 'Deal Campaign Deal Term Label':
            case 'Deal Term Label':

                $deals = $this->get_deals();
                $deal  = array_pop($deals);


                return $deal->get('Deal Term Label');
                break;
            case 'Deal Campaign Order Recursion Days':
            case 'Order Recursion Days':

                $deals = $this->get_deals();
                $deal  = array_pop($deals);


                return $deal->get('Deal Terms Days');
                break;


                break;
            default:
                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Deal Campaign '.$key, $this->data)) {
                    return $this->data['Deal Campaign '.$key];
                }

        }

        return false;
    }

    function update_number_of_deals() {
        $number_deals           = 0;
        $number_current_deals   = 0;
        $number_deal_components = 0;

        $number_active_deal_components    = 0;
        $number_suspended_deal_components = 0;
        $number_waiting_deal_components   = 0;
        $number_finish_deal_components    = 0;

        $sql = sprintf("SELECT count(*) AS num FROM `Deal Dimension` WHERE `Deal Campaign Key`=%d  AND `Deal Status`='Active'  ", $this->id);

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_current_deals = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $sql = sprintf("SELECT count(*) AS num FROM `Deal Dimension` WHERE `Deal Campaign Key`=%d ", $this->id);

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_deals = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = "SELECT count(*) AS num ,`Deal Component Status` FROM `Deal Component Dimension` WHERE `Deal Component Campaign Key`=?  group by `Deal Component Status`  ";


        $stmt = $this->db->prepare($sql);
        if ($stmt->execute(
            array(
                $this->id
            )
        )) {
            while ($row = $stmt->fetch()) {
                switch ($row['Deal Component Status']) {
                    case 'Active':
                        $number_active_deal_components = $row['num'];
                        break;
                    case 'Suspended':
                        $number_suspended_deal_components = $row['num'];
                        break;
                    case 'Waiting':
                        $number_waiting_deal_components = $row['num'];
                        break;
                    case 'Finish':
                        $number_finish_deal_components = $row['num'];
                        break;
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit();
        }

        $sql = sprintf("SELECT count(*) AS num FROM `Deal Component Dimension` WHERE `Deal Component Campaign Key`=%d ", $this->id);

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_deal_components = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $this->fast_update(
            array(
                'Deal Campaign Number Deals'                     => $number_deals,
                'Deal Campaign Number Current Deals'             => $number_current_deals,
                'Deal Campaign Number Deal Components'           => $number_deal_components,
                'Deal Campaign Number Active Deal Components'    => $number_active_deal_components,
                'Deal Campaign Number Suspended Deal Components' => $number_suspended_deal_components,
                'Deal Campaign Number Waiting Deal Components'   => $number_waiting_deal_components,
                'Deal Campaign Number Finish Deal Components'    => $number_finish_deal_components

            )
        );

    }

    function update_usage() {

        $applied_orders    = 0;
        $applied_customers = 0;
        $used_orders       = 0;
        $used_customers    = 0;

        $sql = sprintf(
            "SELECT count( DISTINCT O.`Order Key`) AS orders,count( DISTINCT `Order Customer Key`) AS customers FROM `Order Deal Bridge` B LEFT  JOIN `Order Dimension` O ON (O.`Order Key`=B.`Order Key`) WHERE B.`Deal Campaign Key`=%d AND `Applied`='Yes' AND `Order State`!='Cancelled' ",
            $this->id

        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $applied_orders    = $row['orders'];
                $applied_customers = $row['customers'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf(
            "SELECT count( DISTINCT O.`Order Key`) AS orders,count( DISTINCT `Order Customer Key`) AS customers FROM `Order Deal Bridge` B LEFT  JOIN `Order Dimension` O ON (O.`Order Key`=B.`Order Key`) WHERE B.`Deal Campaign Key`=%d AND `Used`='Yes' AND `Order State`!='Cancelled' ",
            $this->id

        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $used_orders    = $row['orders'];
                $used_customers = $row['customers'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $this->fast_update(
            array(
                'Deal Campaign Total Acc Applied Orders'    => $applied_orders,
                'Deal Campaign Total Acc Applied Customers' => $applied_customers,
                'Deal Campaign Total Acc Used Orders'       => $used_orders,
                'Deal Campaign Total Acc Used Customers'    => $used_customers


            ), 'no_history'
        );


        //$store = new Store($this->get('Deal Campaign Store Key'));
        //$store->update_campaings_data();
        //$store->update_deals_data();


    }

    function delete() {

        if ($this->get('Deal Campaign Number Current Deals') > 0 and $this->data['Deal Campaign Status'] != 'Waiting') {
            $this->msg = "can't be delete";

            return;
        }


        $sql = sprintf(
            "DELETE FROM `Deal Campaign Dimension` WHERE `Deal Campaign Key`=%d", $this->id
        );
        $this->db->exec($sql);

        $sql = sprintf(
            "DELETE FROM `Deal Dimension` WHERE `Deal Campaign Key`=%d", $this->id
        );
        $this->db->exec($sql);

        $sql = sprintf(
            "DELETE FROM `Deal Component Dimension` WHERE `Deal Component Campaign Key`=%d", $this->id
        );
        $this->db->exec($sql);
        $this->fork_index_elastic_search('delete_elastic_index_object');

    }

    function get_field_label($field) {


        switch ($field) {

            case 'Deal Campaign Name':
                $label = _('name');
                break;

            case 'Deal Campaign Valid From':
                $label = _('start date');
                break;

            case 'Deal Campaign Valid To':
                $label = _('end date');
                break;

            case 'Deal Campaign Description':
                $label = _('description');
                break;


            default:
                $label = $field;

        }

        return $label;

    }

}


?>
