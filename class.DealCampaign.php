<?php
/*

 This file contains the Campaign Class

 About:
 Author: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2013, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';

class DealCampaign extends DB_Table {


    function DealCampaign($a1, $a2 = false, $a3 = false, $_db = false) {

        if (!$_db) {
            global $db;
            $this->db = $db;
        } else {
            $this->db = $_db;
        }


        $this->table_name    = 'Deal Campaign';
        $this->ignore_fields = array('Deal Campaign Key');

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
            "SELECT `Deal Campaign Key` FROM `Deal Campaign Dimension` WHERE  `Deal Campaign Name`=%s AND `Deal Campaign Store Key`=%d ", prepare_mysql($data['Deal Campaign Name']),
            $data['Deal Campaign Store Key']
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
            $this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->get_main_id());
            $this->update_status_from_dates();


            return $this;
        } else {
            $this->msg = "Error can not create campaign";
            print $sql;
            exit;
        }


    }

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

    function add_deal($data) {

        $data['Deal Campaign Key'] = $this->id;
        $data['Deal Store Key']    = $this->data['Deal Campaign Store Key'];


        if (strtotime($this->data['Deal Campaign Valid From']) > strtotime('now')) {
            $data['Deal Begin Date'] = $this->data['Deal Campaign Valid From'];

        } else {
            $data['Deal Begin Date'] = gmdate('Y-m-d H:i:s');

        }

        $data['Deal Expiration Date'] = $this->data['Deal Campaign Valid To'];
        $data['Deal Status']          = $this->data['Deal Campaign Status'];


        $deal = new Deal('find create', $data);
        $deal->update_status_from_dates();




        $this->update_current_number_of_deals();

        return $deal;
    }


    function update_current_number_of_deals(){
        $number_deals = 0;
        $sql          = sprintf("SELECT count(*) AS num FROM `Deal Dimension` WHERE `Deal Campaign Key`=%d ", $this->id);

        if ($result=$this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_deals=$row['num'];
        	}
        }else {
        	print_r($error_info=$this->db->errorInfo());
        	print "$sql\n";
        	exit;
        }



        $this->update(array('Deal Campaign Number Current Deals'=>$number_deals),'no_history');

    }

    function get_deal_keys() {
        $deal_keys = array();
        $sql       = sprintf(
            "SELECT `Deal Key` FROM `Deal Dimension` WHERE `Deal Campaign Key`=%d ", $this->id
        );
        $res       = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            $deal_keys[] = $row['Deal Key'];
        }

        return $deal_keys;

    }

    function get_deal_component_keys() {
        $deal_component_keys = array();
        $sql                 = sprintf(
            "SELECT `Deal Component Key` FROM `Deal Component Dimension` WHERE `Deal Component Campaign Key`=%d ", $this->id
        );
        $res                 = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            $deal_component_keys[] = $row['Deal Component Key'];
        }

        return $deal_component_keys;

    }

    function update_usage() {

        $applied_orders    = 0;
        $applied_customers = 0;
        $used_orders       = 0;
        $used_customers    = 0;

        $sql = sprintf(
            "SELECT count( DISTINCT O.`Order Key`) AS orders,count( DISTINCT `Order Customer Key`) AS customers FROM `Order Deal Bridge` B LEFT  JOIN `Order Dimension` O ON (O.`Order Key`=B.`Order Key`) WHERE B.`Deal Campaign Key`=%d AND `Applied`='Yes' AND `Order Current Dispatch State`!='Cancelled' ",
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
            "SELECT count( DISTINCT O.`Order Key`) AS orders,count( DISTINCT `Order Customer Key`) AS customers FROM `Order Deal Bridge` B LEFT  JOIN `Order Dimension` O ON (O.`Order Key`=B.`Order Key`) WHERE B.`Deal Campaign Key`=%d AND `Used`='Yes' AND `Order Current Dispatch State`!='Cancelled' ",
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


        $this->update(
            array(
                'Deal Campaign Total Acc Applied Orders'    => $applied_orders,
                'Deal Campaign Total Acc Applied Customers' => $applied_customers,
                'Deal Campaign Total Acc Used Orders'       => $used_orders,
                'Deal Campaign Total Acc Used Customers'    => $used_customers


            ), 'no_history'
        );


        $store = new Store($this->get('Deal Campaign Store Key'));
        $store->update_campaings_data();
        $store->update_deals_data();


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
                    return gmdate('d-m-Y', strtotime($this->data['Deal Campaign Valid From'].' +0:00'));
                }

                break;
            case 'Valid To':
                if ($this->data['Deal Campaign Valid To'] == '') {
                    return '';
                } else {
                    return gmdate('d-m-Y', strtotime($this->data['Deal Campaign Valid To'].' +0:00'));
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

            case 'Used Orders':
            case 'Used Customers':
            case 'Applied Orders':
            case 'Applied Customers':


                return number($this->data['Deal Campaign Total Acc '.$key]);

                break;
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
            "DELETE FROM `Deal Compoment Dimension` WHERE `Deal Compoment Campaign Key`=%d", $this->id
        );
        $this->db->exec($sql);


    }

    function get_number_deals() {
        $number_deals = 0;
        $sql          = sprintf(
            "SELECT count(*) AS num FROM `Deal Dimension` WHERE `Deal Campaign Key`=%d ", $this->id
        );
        $res          = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            $number_deals = $row['num'];
        }

        return $number_deals;
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
