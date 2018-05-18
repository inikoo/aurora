<?php
/*

 About:
Refurbished: 8 August 2017 at 15:16:40 CEST, Tranava , Slovakia
 Author: Raul Perusquia <rulovico@gmail.com>


 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';
include_once 'class.Deal.php';

class DealComponent extends DB_Table {


    function DealComponent($a1, $a2 = false) {


        global $db;
        $this->db = $db;

        $this->table_name    = 'Deal Component';
        $this->ignore_fields = array('Deal Component Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } else {
            if (($a1 == 'new' or $a1 == 'create') and is_array($a2)) {
                $this->find($a2, 'create');

            } elseif (preg_match('/find/i', $a1)) {
                $this->find($a2, $a1);
            } else {
                $this->get_data($a1, $a2);
            }
        }

    }

    function get_data($tipo, $tag) {


        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Deal Component Dimension` WHERE `Deal Component Key`=%d", $tag
            );
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->calculate_deal = create_function('$transaction_data,$customer_id,$date', $this->get('Deal Component'));
            $this->id             = $this->data['Deal Component Key'];
        }


    }

    function get($key = '', $arg = false) {


        switch ($key) {

            case 'Allowance':

                return $this->get_formatted_allowances();
                break;

            case('Description'):
            case('Deal Description'):
                return $this->get_formatted_terms().' <i class="far fa-arrow-right"></i> '.$this->get_formatted_allowances();
                break;
        }

        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        if (isset($this->data['Deal Component '.$key])) {
            return $this->data['Deal Component '.$key];
        }


        return false;
    }

    function get_formatted_allowances() {


        switch ($this->data['Deal Component Allowance Type']) {
            case 'Percentage Off':
                $allowance = sprintf(_('%s off'), percentage($this->data['Deal Component Allowance'], 1, 0));

                break;

            case 'Get Cheapest Free':

                if ($this->data['Deal Component Allowance'] == 1) {
                    $allowance = sprintf(_('cheapest free'));

                } else {
                    $allowance = sprintf(_('cheapest %d free'), $this->data['Deal Component Allowance']);

                }


                break;

            default:
                $allowance = $this->data['Deal Component Allowance'];

        };

        return $allowance;


    }

    function get_formatted_terms() {

        $terms = '';


        switch ($this->data['Deal Component Terms Type']) {

            case 'Order Interval':
                $terms = sprintf('last order within %d days', $this->get('Deal Component Terms'));


                break;
            case 'Category Quantity Ordered':


                $terms = sprintf('order %d or more %s', $this->data['Deal Component Terms'], $this->get('Deal Component Allowance Target Label'));


                break;
            case 'Category For Every Quantity Ordered':


                $terms = sprintf('%s, buy %d', $this->get('Deal Component Allowance Target Label'), $this->data['Deal Component Terms']);


                break;
            case 'Category For Every Quantity Any Product Ordered':


                $terms = sprintf('%s (Mix & match), buy %d ', $this->get('Deal Component Allowance Target Label'), $this->data['Deal Component Terms']);


                break;

        }


        return $terms;
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
            "SELECT `Deal Component Key` FROM `Deal Component Dimension` WHERE `Deal Component Deal Key`=%d AND  `Deal Component Trigger`=%s AND `Deal Component Trigger Key`=%d AND `Deal Component Terms Type`=%s AND `Deal Component Allowance Type`=%s AND `Deal Component Allowance Target`=%s AND `Deal Component Allowance Target Key`=%d ",
            $data['Deal Component Deal Key'], prepare_mysql($data['Deal Component Trigger']), $data['Deal Component Trigger Key'], prepare_mysql($data['Deal Component Terms Type']), prepare_mysql($data['Deal Component Allowance Type']),
            prepare_mysql($data['Deal Component Allowance Target']), $data['Deal Component Allowance Target Key']

        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->found     = true;
                $this->found_key = $row['Deal Component Key'];
                $this->get_data('id', $row['Deal Component Key']);
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        if ($create and !$this->found) {
            $this->create($data);

        }


    }

    function create($data) {


        if ($data['Deal Component Trigger Key'] == '') {
            $data['Deal Component Trigger Key'] = 0;
        }
        if ($data['Deal Component Allowance Target Key'] == '') {
            $data['Deal Component Allowance Target Key'] = 0;
        }


        $keys   = '(';
        $values = 'values(';
        foreach ($data as $key => $value) {
            $keys .= "`$key`,";

            $values .= prepare_mysql($value).",";

        }
        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);
        $sql    = sprintf(
            "INSERT INTO `Deal Component Dimension` %s %s", $keys, $values
        );
        // print "$sql\n";


        if ($this->db->exec($sql)) {
            $this->id = $this->db->lastInsertId();
            $this->get_data('id', $this->id);

            $this->new = true;
        } else {
            print "Error can not create deal component\n $sql\n";
            exit;

        }
    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {

        switch ($field) {


            case 'Deal Component Status':

                $this->update_status($value, $options);
                break;

            case 'Deal Component Name Label':

                if ($this->data['Deal Component Campaign Key']) {
                    $campaign         = get_object('DealCampaign', $this->data['Deal Component Campaign Key']);
                    $campaign->editor = $this->editor;
                    $campaign->update(array('Deal Campaign Name' => $value));
                } else {
                    $deal         = get_object('Deal', $this->data['Deal Component Deal Key']);
                    $deal->editor = $this->editor;
                    $deal->update(array('Deal Name Label' => $value));
                    $this->update_field('Deal Component Name Label', $value, $options);

                }


                break;

            case 'Deal Component Term Label':
                $deal         = get_object('Deal', $this->data['Deal Component Deal Key']);
                $deal->editor = $this->editor;
                $deal->update(array('Deal Term Label' => $value));
                break;
            case 'Deal Terms':
                $deal         = get_object('Deal', $this->data['Deal Component Deal Key']);
                $deal->editor = $this->editor;


                $deal->update(array('Deal Terms' => $value));


                break;


            case 'Deal Component Allowance Label':
                $this->update_field($field, $value, $options);


                $this->update_websites();

                $deal         = get_object('Deal', $this->data['Deal Component Deal Key']);
                $deal->editor = $this->editor;

                $deal->update_allowance_label();

                break;
            case 'Deal Component Expiration Date':
                $this->update_expiration_date($value, $options);
                break;


            case 'Deal Component Allowance':
                $this->update_field($field, $value, $options);
                $deal         = get_object('Deal', $this->data['Deal Component Deal Key']);
                $deal->editor = $this->editor;
                $deal->update_term_allowances();

                break;

            default:
                $base_data = $this->base_data();

                if (array_key_exists($field, $base_data)) {
                    $this->update_field($field, $value, $options);
                }
        }
    }

    function update_status($value, $options = '') {

        if ($value == 'Suspended') {

            $old_value = $this->data['Deal Component Status'];
            $this->update_field('Deal Component Status', $value, $options);


            if ($old_value != $value) {

                $account = get_object('Account', 1);
                require_once 'utils/new_fork.php';
                new_housekeeping_fork(
                    'au_housekeeping', array(
                    'type'     => 'deal_updated',
                    'deal_key' => $this->get('Deal Component Deal Key')
                ), $account->get('Account Code'), $this->db
                );


            }


        } else {
            $this->update_status_from_dates($force = true);
        }


    }

    function update_status_from_dates($force = false) {


        $old_value = $this->data['Deal Component Status'];

        if ($this->data['Deal Component Expiration Date'] != '' and strtotime(
                $this->data['Deal Component Expiration Date'].' +0:00'
            ) <= strtotime('now +0:00')) {


            $this->update_field(
                'Deal Component Status', 'Finish', 'no_history'
            );

            $value = 'Finish';

            if ($old_value != $value) {

                $account = get_object('Account', 1);
                require_once 'utils/new_fork.php';
                new_housekeeping_fork(
                    'au_housekeeping', array(
                    'type'     => 'deal_updated',
                    'deal_key' => $this->get('Deal Component Deal Key')
                ), $account->get('Account Code'), $this->db
                );


            }

            return;
        }


        if (!$force and $this->data['Deal Component Status'] == 'Suspended') {
            return;
        }

        if (strtotime($this->data['Deal Component Begin Date'].' +0:00') >= strtotime('now +0:00')) {
            $this->update_field(
                'Deal Component Status', 'Waiting', 'no_history'
            );
            $value = 'Waiting';
        } elseif (strtotime($this->data['Deal Component Begin Date'].' +0:00') <= strtotime('now +0:00')) {
            $this->update_field(
                'Deal Component Status', 'Active', 'no_history'
            );
            $value = 'Active';
        }


        if ($old_value != $value) {

            $account = get_object('Account', 1);
            require_once 'utils/new_fork.php';
            new_housekeeping_fork(
                'au_housekeeping', array(
                'type'     => 'deal_updated',
                'deal_key' => $this->get('Deal Component Deal Key')
            ), $account->get('Account Code'), $this->db
            );


        }


    }

    function update_websites() {

        $webpage_keys = array();

        $families    = array();
        $departments = array();
        $sql         = sprintf(
            'select `Deal Component Trigger Key`,`Category Scope` from  `Deal Component Dimension`  left join `Category Dimension` on (`Deal Component Trigger Key`=`Category Key`)   where `Deal Component Key`=%d  and `Deal Component Trigger`="Category"  ', $this->id
        );
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($row['Category Scope'] == 'Product') {
                    $families[$row['Deal Component Trigger Key']] = $row['Deal Component Trigger Key'];
                } else {
                    $departments[$row['Deal Component Trigger Key']] = $row['Deal Component Trigger Key'];
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $sql = sprintf(
            'select `Deal Component Allowance Target Key`,`Category Scope` from  `Deal Component Dimension`  left join `Category Dimension` on (`Deal Component Allowance Target Key`=`Category Key`)    where `Deal Component Key`=%d  and `Deal Component Allowance Target`="Category"   ',
            $this->id
        );
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($row['Category Scope'] == 'Product') {
                    $families[$row['Deal Component Allowance Target Key']] = $row['Deal Component Allowance Target Key'];
                } else {
                    $departments[$row['Deal Component Allowance Target Key']] = $row['Deal Component Allowance Target Key'];
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        if (count($families) > 0) {
            $sql = sprintf('select group_concat(`Subject Key`) as products from `Category Bridge` where `Category Key` in (%s) ', join($families, ','));

            //  print $sql;
            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $products = preg_split('/,/', $row['products']);
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }

        }


        foreach ($products as $product_id) {
            $sql = sprintf('select `Page Key` from `Page Store Dimension` where `Webpage Scope`="Product" and `Webpage Scope Key`=%d ', $product_id);

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $webpage_keys[$row['Page Key']] = $row['Page Key'];
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }

        }

        foreach ($families as $family_key) {
            $sql = sprintf('select `Page Key`,`Webpage Website Key` from `Page Store Dimension` where `Webpage Scope`="Category Products" and `Webpage Scope Key`=%d ', $family_key);

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $webpage_keys[$row['Page Key']] = array(
                        $row['Webpage Website Key'],
                        $row['Page Key']
                    );
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }

        }

        require_once 'external_libs/Smarty/Smarty.class.php';
        $smarty_web               = new Smarty();
        $smarty_web->template_dir = 'EcomB2B/templates';
        $smarty_web->compile_dir  = 'EcomB2B/server_files/smarty/templates_c';
        $smarty_web->cache_dir    = 'EcomB2B/server_files/smarty/cache';
        $smarty_web->config_dir   = 'EcomB2B/server_files/smarty/configs';
        $smarty_web->setCaching(Smarty::CACHING_LIFETIME_CURRENT);


        foreach ($webpage_keys as $data) {

            $cache_id = $data[0].'|'.$data[1];
            $smarty_web->clearCache(null, $cache_id);
        }


        //print_r($webpage_keys);
        //  print_r($products);


    }

    function update_expiration_date($value, $options) {

        if ($this->data['Deal Component Status'] == 'Finish') {
            $this->error = true;
            $this->msg   = 'Deal component already finished';
        } else {
            $this->update_field(
                'Deal Component Expiration Date', $value, $options
            );
            $this->updated = true;


        }


        $this->update_status_from_dates();


    }

    function update_target_bridge() {

        if ($this->data['Deal Component Status'] == 'Finish') {
            $sql = sprintf(
                "DELETE FROM `Deal Target Bridge` WHERE `Deal Component Key`=%d ", $this->id
            );
            $this->db->exec($sql);
        } else {


            $sql = sprintf(
                "INSERT INTO `Deal Target Bridge` VALUES (%d,%s,%s,%d) ", $this->data['Deal Component Deal Key'], $this->id, prepare_mysql($this->data['Deal Component Allowance Target']), $this->data['Deal Component Allowance Target Key']

            );
            $this->db->exec($sql);

            if ($this->data['Deal Component Allowance Target'] == 'Category') {


                $sql = sprintf(
                    "SELECT `Subject Key` FROM `Category Bridge` WHERE `Category Key`=%d ", $this->data['Deal Component Allowance Target Key']
                );

                if ($result2 = $this->db->query($sql)) {
                    foreach ($result2 as $row2) {
                        $sql = sprintf(
                            "INSERT INTO `Deal Target Bridge` VALUES (%d,%d,%s,%d) ", $this->data['Deal Component Deal Key'], $this->id, prepare_mysql('Product'), $row2['Subject Key']

                        );
                        $this->db->exec($sql);
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


            }


        }
    }

    function update_usage() {


        $sql = sprintf(
            "SELECT count( DISTINCT O.`Order Key`) AS orders,count( DISTINCT `Order Customer Key`) AS customers FROM `Order Deal Bridge` B LEFT  JOIN `Order Dimension` O ON (O.`Order Key`=B.`Order Key`) WHERE B.`Deal Component Key`=%d AND `Applied`='Yes' AND `Order State`!='Cancelled' ",
            $this->id

        );

        $orders    = 0;
        $customers = 0;

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $orders    = $row['orders'];
                $customers = $row['customers'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $this->update(
            array(
                'Deal Component Total Acc Applied Orders'    => $orders,
                'Deal Component Total Acc Applied Customers' => $customers,

            ), 'no_history'
        );


        $sql       = sprintf(
            "SELECT count( DISTINCT O.`Order Key`) AS orders,count( DISTINCT `Order Customer Key`) AS customers FROM `Order Deal Bridge` B LEFT  JOIN `Order Dimension` O ON (O.`Order Key`=B.`Order Key`) WHERE B.`Deal Component Key`=%d AND `Used`='Yes' AND `Order State`!='Cancelled' ",
            $this->id

        );
        $orders    = 0;
        $customers = 0;


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $orders    = $row['orders'];
                $customers = $row['customers'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $this->update(
            array(
                'Deal Component Total Acc Used Orders'    => $orders,
                'Deal Component Total Acc Used Customers' => $customers,

            ), 'no_history'
        );


    }

}

?>
