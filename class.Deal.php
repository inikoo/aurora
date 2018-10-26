<?php
/*


 About:
Refurbished: 8 August 2017 at 15:16:40 CEST, Tranava , Slovakia

 Author: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';

class Deal extends DB_Table {


    function Deal($a1, $a2 = false, $a3 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Deal';
        $this->ignore_fields = array('Deal Key');

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


    function get_data($tipo, $tag, $tag2 = '') {

        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Deal Dimension` WHERE `Deal Key`=%d", $tag
            );
        } elseif ($tipo == 'name') {
            $sql = sprintf(
                "SELECT * FROM `Deal Dimension` WHERE `Deal Name`=%s", prepare_mysql($tag)
            );
        } elseif ($tipo == 'store_name') {
            $sql = sprintf(
                "SELECT * FROM `Deal Dimension` WHERE `Deal Store Key`=%d AND `Deal Name`=%s", $tag, prepare_mysql($tag2)
            );
        }else{
            return;
        }


        if ($this->data = $this->db->query($sql)->fetch()) {

            $this->id = $this->data['Deal Key'];
        }


        if ($this->data['Deal Remainder Email Campaign Key'] > 0) {
            $this->remainder_email_campaign = get_object('EmailCampaign',$this->data['Deal Remainder Email Campaign Key']);

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
            "SELECT `Deal Key` FROM `Deal Dimension` WHERE  `Deal Name`=%s AND `Deal Store Key`=%d ", prepare_mysql($data['Deal Name']), $data['Deal Store Key']
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->found     = true;
                $this->found_key = $row['Deal Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
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

        $keys   = '';
        $values = '';


        foreach ($data as $key => $value) {
            $keys .= "`$key`,";

            $values .= prepare_mysql($value).",";

        }
        $keys   = preg_replace('/,$/', '', $keys);
        $values = preg_replace('/,$/', '', $values);


        // print_r($data);
        $sql = sprintf(
            "INSERT INTO `Deal Dimension` (%s) VALUES (%s)", $keys, $values
        );
        //print "$sql\n";
        if ($this->db->exec($sql)) {
            $this->id = $this->db->lastInsertId();
            $this->get_data('id', $this->id);
            $this->new = true;


            $this->update_status_from_dates();
            $this->update_term_allowances();


            $store = get_object('Store', $this->data['Deal Store Key']);
            $store->update_deals_data();

        } else {
            print "Error can not create deal  $sql\n";
            exit;

        }
    }

    function update_status_from_dates($force = false) {


        if ($this->data['Deal Expiration Date'] != '' and strtotime($this->data['Deal Expiration Date'].' +0:00') <= strtotime('now +0:00')) {
            $this->update_field_switcher('Deal Status', 'Finish', 'no_history');

            if ($this->data['Deal Voucher Key']) {
                $voucher = Voucher($this->data['Deal Voucher Key']);
                $voucher->update_field_switcher(
                    'Voucher Status', 'Finish', 'no_history'
                );

            }

            return;
        }


        if (!$force and $this->data['Deal Status'] == 'Suspended') {
            return;
        }

        if (strtotime($this->data['Deal Begin Date'].' +0:00') >= strtotime('now +0:00')) {
            $this->update_field_switcher('Deal Status', 'Waiting', 'no_history');
        }


        if (strtotime($this->data['Deal Begin Date'].' +0:00') <= strtotime(
                'now +0:00'
            )) {


            $this->update_field_switcher('Deal Status', 'Active', 'no_history');
        }


    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        switch ($field) {

            case 'Deal Component Allowance Label':
                //used for bulk discounts campaign
                $deal_components        = $this->get_deal_components('objects');
                $deal_component         = array_pop($deal_components);
                $deal_component->editor = $this->editor;
                $deal_component->update(array('Deal Component Allowance Label' => $value), $options);
                $this->get_data('id', $this->id);

                break;

            case 'Deal Component Allowance Percentage':

                //used for bulk discounts campaign


                $value = floatval($value) / 100;

                $deal_components        = $this->get_deal_components('objects');
                $deal_component         = array_pop($deal_components);
                $deal_component->editor = $this->editor;
                $deal_component->update(array('Deal Component Allowance' => $value), $options);
                $this->get_data('id', $this->id);
                break;
            case 'Deal Terms':

                $this->update_field($field, $value, $options);


                $this->update_term_allowances();

                $components = $this->get_deal_components('objects', 'All');

                foreach ($components as $component) {


                    $component->editor = $this->editor;
                    $component->update(array('Deal Component Terms' => $value), $options);

                }


                break;

            case 'Deal Term Label':
                $this->update_field($field, $value, $options);

                $sql = sprintf('UPDATE `Deal Component Dimension` SET `Deal Component Term Label`=%s WHERE `Deal Component Deal Key`=%d  ', prepare_mysql($value), $this->id);
                $this->db->exec($sql);

                $this->update_websites();


                break;
            case('Deal Begin Date'):
                $this->update_begin_date($value, $options);
                break;
            case('Deal Expiration Date'):
                $this->update_expiration_date($value, $options);
                break;
            default:
                $base_data = $this->base_data();

                if (array_key_exists($field, $base_data)) {
                    $this->update_field($field, $value, $options);
                }
        }
    }

    function get_deal_components($type = 'keys', $options = 'Active') {

        $deal_components = array();


        if ($options == 'Active') {

            $where = ' and `Deal Component Status`="Active"';

        }elseif ($options == 'Suspended') {

            $where = ' and `Deal Component Status`="Suspended"';

        } else {
            $where = '';
        }


        $sql = sprintf(
            "SELECT `Deal Component Key` FROM `Deal Component Dimension` WHERE `Deal Component Deal Key`=%d  $where", $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                if ($type == 'objects') {
                    $deal_components[$row['Deal Component Key']] = get_object('DealComponent', $row['Deal Component Key']);
                } else {
                    $deal_components[$row['Deal Component Key']] = $row['Deal Component Key'];
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $deal_components;
    }

    function update_term_allowances() {


        $this->update_field_switcher(
            'Deal Term Allowances Label', $this->get_formatted_terms().' &#8594; '.$this->get_formatted_allowances(), 'no_history'
        );
    }

    function get_formatted_terms() {

        $terms = '';


        switch ($this->data['Deal Terms Type']) {

            case 'Order Interval':
                $terms = sprintf('last order within %d days', $this->get('Deal Terms'));


                break;
            case 'Category Quantity Ordered':

                $component = $this->get_deal_components('objects');

                if (count($component) > 0) {
                    $component = array_pop($component);


                    if ($this->data['Deal Terms'] == 1) {
                        $terms = sprintf('order %d', $component->get('Deal Component Allowance Target Label'));

                    } else {
                        $terms = sprintf('order %d or more %s', $this->data['Deal Terms'], $component->get('Deal Component Allowance Target Label'));

                    }


                } else {
                    $terms = '';
                }


                break;
            case 'Category For Every Quantity Ordered':

                $component = $this->get_deal_components('objects');

                if (count($component) > 0) {
                    $component = array_pop($component);


                    $terms = sprintf('%s products, buy %d', $component->get('Deal Component Allowance Target Label'), $this->data['Deal Terms']);
                } else {
                    $terms = '';
                }


                break;
            case 'Category For Every Quantity Any Product Ordered':

                $component = $this->get_deal_components('objects');

                if (count($component) > 0) {
                    $component = array_pop($component);


                    $terms = sprintf('%s (Mix & match), buy %d ', $component->get('Deal Component Allowance Target Label'), $this->data['Deal Terms']);
                } else {
                    $terms = '';
                }

                break;

        }


        return $terms;
    }

    function get($key = '') {

        if (!$this->id) {
            return;
        }

        switch ($key) {
            case 'Status Icon':
                switch ($this->data['Deal Status']) {
                    case 'Waiting':
                        $status = sprintf(
                            '<i class="far fa-clock discreet fa-fw" aria-hidden="true" title="%s" ></i>', _('Waiting')
                        );
                        break;
                    case 'Active':
                        $status = sprintf(
                            '<i class="fa fa-play success fa-fw" aria-hidden="true" title="%s" ></i>', _('Active')
                        );
                        break;
                    case 'Suspended':
                        $status = sprintf(
                            '<i class="fa fa-pause error fa-fw" aria-hidden="true" title="%s" ></i>', _('Suspended')
                        );
                        break;
                    case 'Finish':
                        $status = sprintf(
                            '<i class="fa fa-stop discreet fa-fw" aria-hidden="true" title="%s" ></i>', _('Finished')
                        );
                        break;
                    default:
                        $status = '';
                }

                return $status;

                break;
            case 'Status':
                switch ($this->data['Deal Status']) {
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
                        return $this->data['Deal Status'];
                }

                break;
            case 'Deal Component Allowance Label':
            case 'Component Allowance Label':

                $deal_components = $this->get_deal_components('objects');
                if (count($deal_components) > 0) {
                    $deal_component = array_pop($deal_components);

                    return $deal_component->get('Deal Component Allowance Label');
                } else {
                    return '';
                }
                break;

            case 'Deal Component Allowance':

                $deal_components = $this->get_deal_components('objects');
                $deal_component  = array_pop($deal_components);

                return $deal_component->get('Deal Component Allowance');
                break;

            case 'Deal Component Allowance Percentage':
            case 'Component Allowance Percentage':

                $deal_components = $this->get_deal_components('objects');

                if (count($deal_components) > 0) {
                    $deal_component = array_pop($deal_components);

                    return percentage($deal_component->get('Deal Component Allowance'), 1, 0);
                } else {
                    return '';
                }

                break;

            case 'Component Allowance':

                $deal_components = $this->get_deal_components('objects');
                $deal_component  = array_pop($deal_components);

                return $deal_component->get('Allowance');
                break;


            case 'Used Orders':
            case 'Used Customers':
            case 'Applied Orders':
            case 'Applied Customers':


                return number($this->data['Deal Total Acc '.$key]);

            case 'Number History Records':
            case 'Number Active Components':
                return number($this->data['Deal '.$key]);
                break;
            case 'Begin Date':
            case 'Expiration Date':

                if ($this->data['Deal '.$key] == '') {
                    return '';
                } else {
                    return strftime("%a, %e %h %Y", strtotime($this->data['Deal '.$key]." +00:00"));
                }


            case 'Duration':
                $duration = '';
                if ($this->data['Deal Expiration Date'] == '' and $this->data['Deal Begin Date'] == '') {
                    $duration = _('permanent');
                } else {

                    if ($this->data['Deal Begin Date'] != '') {
                        $duration = strftime(
                            "%a, %e %h %Y", strtotime($this->data['Deal Begin Date']." +00:00")
                        );

                    }
                    $duration .= ' - ';
                    if ($this->data['Deal Expiration Date'] != '') {
                        $duration .= strftime(
                            "%a, %e %h %Y", strtotime(
                                              $this->data['Deal Expiration Date']." +00:00"
                                          )
                        );

                    } else {
                        $duration .= _('permanent');
                    }

                }

                return $duration;
                break;
            case 'Deal Terms Days':
            case 'Terms Days':
                return preg_replace("/[^0-9,.]/", "", $this->data['Deal Terms']);
                break;
            default:
                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Deal '.$key, $this->data)) {
                    return $this->data['Deal '.$key];
                }


        }


    }

    function get_formatted_allowances() {

        $allowances = '';
        $sql        = sprintf(
            "SELECT  `Deal Component Allowance Type`,`Deal Component Allowance` FROM `Deal Component Dimension` WHERE `Deal Component Deal Key`=%d ", $this->id
        );
        $count      = 0;

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $count++;
                if ($count <= 2) {


                    switch ($row['Deal Component Allowance Type']) {
                        case 'Percentage Off':
                            $allowances .= ', '.sprintf(_('%s off'), percentage($row['Deal Component Allowance'], 1, 0));

                            break;
                        case 'Get Cheapest Free':

                            if ($row['Deal Component Allowance'] == 1) {
                                $allowances .= ', '.sprintf(_('cheapest free'));

                            } else {
                                $allowances .= ', '.sprintf(_('cheapest %d free'), $row['Deal Component Allowance']);

                            }


                            break;
                    };


                } else {
                    $allowances .= ', ...';
                    break;
                }

            }
        }


        $allowances = preg_replace('/^\, /', '', $allowances);

        // print $allowances;

        return $allowances;


    }

    function update_websites() {

        $webpage_keys = array();

        $families    = array();
        $departments = array();
        $sql         = sprintf(
            'select `Deal Component Trigger Key`,`Category Scope` from  `Deal Component Dimension`  left join `Category Dimension` on (`Deal Component Trigger Key`=`Category Key`)   where `Deal Component Deal Key`=%d  and `Deal Component Trigger`="Category"  ',
            $this->id
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
            'select `Deal Component Allowance Target Key`,`Category Scope` from  `Deal Component Dimension`  left join `Category Dimension` on (`Deal Component Allowance Target Key`=`Category Key`)    where `Deal Component Deal Key`=%d  and `Deal Component Allowance Target`="Category"   ',
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

    function update_begin_date($value, $options) {
        $this->updated = false;

        if ($this->data['Deal Status'] == 'Waiting') {

            $this->update_field('Deal Begin Date', $value, $options);

            $sql = sprintf(
                'SELECT `Deal Component Key` FROM `Deal Component Dimension` WHERE `Deal Component Status`="Waiting" AND `Deal Component Deal Key`=%d', $this->id
            );

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $deal_component = new DealComponent($row['Deal Component Key']);
                    $deal_component->update(
                        array('Deal Component Begin Date' => $value)
                    );
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            $this->update_status_from_dates();
            $this->updated = true;


        } else {
            $this->error = true;
            $this->msg   = 'Deal already started';
        }


        $this->update_metadata = array(
            'class_html' => array(
                'Duration'          => $this->get('Duration'),
                'Status_Icon' => $this->get('Status Icon'),
                'Status' => $this->get('Status')


            )
        );


    }

    function update_expiration_date($value, $options) {

        /*
        if ($this->data['Deal Status'] == 'Finish') {
            $this->error = true;
            $this->msg   = 'Deal already finished';
        } else {
            $this->update_field('Deal Expiration Date', $value, $options);
            $this->updated = true;


        }
*/

        $this->update_field('Deal Expiration Date', $value, $options);
        $this->updated = true;

        $sql = sprintf(
            'SELECT `Deal Component Key` FROM `Deal Component Dimension` WHERE `Deal Component Status`!="Finish" AND `Deal Component Deal Key`=%d', $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $deal_component = get_object('DealComponent', $row['Deal Component Key']);
                $deal_component->update(
                    array('Deal Component Expiration Date' => $value)
                );
                $deal_component->update_status_from_dates();
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $this->update_status_from_dates();

        $this->update_metadata = array(
            'class_html' => array(
                'Duration'          => $this->get('Duration'),
                'Status_Icon' => $this->get('Status Icon'),
                'Status' => $this->get('Status')


            )
        );


    }


    function update_allowance_label() {


        $deal_component_keys = $this->get_deal_components('keys', 'Active');

        if (count($deal_component_keys) == 1) {
            $deal_component = get_object('DealComponent', array_pop($deal_component_keys));

            $this->update(array('Deal Allowance Label' => $deal_component->get('Deal Component Allowance Label')));

        }


    }

    function add_component($data) {


        $campaign = get_object('DealCampaign', $this->data['Deal Campaign Key']);

        include_once 'class.DealComponent.php';

        $data['Deal Component Deal Key']     = $this->id;
        $data['Deal Component Store Key']    = $this->data['Deal Store Key'];
        $data['Deal Component Campaign Key'] = $this->data['Deal Campaign Key'];
        $data['Deal Component Begin Date']   = gmdate('Y-m-d H:i:s');


        if ($campaign->id) {
            $data['Deal Component Icon']       = $campaign->get('Deal Campaign Icon');
            $data['Deal Component Name Label'] = $campaign->get('Deal Campaign Name');
        } else {
            $data['Deal Component Icon']       = '<i class="fa fa-tag "></i>';
            $data['Deal Component Name Label'] = $this->get('Deal Name');
        }


        $hereditary_fields = array(
            'Expiration Date',
            'Term Label',
            'Status',
            'Name',
            'Trigger',
            'Trigger Key',
            'Terms Type',
            'Terms',
            'Allowance Target Type'
        );
        foreach ($hereditary_fields as $hereditary_field) {
            if (!array_key_exists('Deal Component '.$hereditary_field, $data)) {
                $data['Deal Component '.$hereditary_field] = $this->data['Deal '.$hereditary_field];
            }
        }


        $deal_component = new DealComponent('find create', $data);
        //$deal_component->update_status($this->data['Deal Status']);
        $this->update_number_components();
        $this->update_term_allowances();

        $deal_component->update_target_bridge();


        return $deal_component;

    }

    function update_number_components() {
        $number        = 0;
        $active_number = 0;
        $sql           = sprintf(
            "SELECT count(*) AS number FROM `Deal Component Dimension` WHERE `Deal Component Deal Key`=%d AND `Deal Component Status`='Active' ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $active_number = $row['number'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $sql = sprintf(
            "SELECT count(*) AS number FROM `Deal Component Dimension` WHERE `Deal Component Deal Key`=%d ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number = $row['number'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $this->fast_update(
            array(
                'Deal Number Active Components' => $active_number,
                'Deal Number Components'        => $number,
            )
        );


    }


    function suspend() {
        $this->update_status('Suspended');
    }

    function update_status($value = '') {


        if ($value == 'Suspended') {


            $this->update_field('Deal Status', $value);


        } else {


            $this->update_status_from_dates($force = true);


        }


        foreach ($this->get_deal_components('objects', 'all') as $component) {


            $component->update(array('Deal Component Status' => $value), 'no_history');
        }

    }

    function activate() {
        $this->update_status();
    }


    function get_to_date() {
        if ($this->data['Deal Expiration Date'] == '') {
            return '';
        } else {
            return gmdate(
                'd-m-Y', strtotime($this->data['Deal Expiration Date'].' +0:00')
            );
        }
    }

    function is_voucher() {
        if (in_array(
            $this->data['Deal Terms Type'], array(
                                              'Voucher AND Order Interval',
                                              'Voucher AND Order Number',
                                              'Voucher AND Amount',
                                              'Voucher'
                                          )
        )) {
            return true;
        } else {
            return false;
        }
    }

    function delete() {

        $this->update_usage();

        if ($this->data['Deal Total Acc Applied Orders'] > 0) {
            $this->msg   = _(
                'Can not delete the offer, because it has been applied to an order'
            );
            $this->error = true;

            return;
        }


        $sql = sprintf("DELETE FROM `Voucher Dimension` WHERE `Voucher Key`=%d ", $this->data['Deal Voucher Key']);

        $this->db->exec($sql);

        $this->db->exec($sql);

        $sql = sprintf("DELETE FROM `Deal Component Dimension` WHERE `Deal Component Deal Key`=%d ", $this->id);
        $this->db->exec($sql);

        $sql = sprintf("DELETE FROM `Deal Dimension` WHERE `Deal Key`=%d ", $this->id);
        $this->db->exec($sql);

        $campaign         = new DealCampaign($this->data['Deal Campaign Key']);
        $campaign->editor = $this->editor;

        $campaign->update_number_of_deals();


    }

    function update_usage() {


        $sql               = sprintf(
            "SELECT count( DISTINCT O.`Order Key`) AS orders,count( DISTINCT `Order Customer Key`) AS customers FROM `Order Deal Bridge` B LEFT  JOIN `Order Dimension` O ON (O.`Order Key`=B.`Order Key`) WHERE B.`Deal Key`=%d AND `Applied`='Yes' AND `Order Current Dispatch State`!='Cancelled' ",
            $this->id

        );
        $applied_orders    = 0;
        $applied_customers = 0;

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

        $used_orders    = 0;
        $used_customers = 0;
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
                'Deal Total Acc Applied Orders'    => $applied_orders,
                'Deal Total Acc Applied Customers' => $applied_customers,
                'Deal Total Acc Used Orders'       => $used_orders,
                'Deal Total Acc Used Customers'    => $used_customers,
            )

        );


    }

    function get_field_label($field) {

        switch ($field) {

            case 'Deal Name':
                $label = _('name');
                break;
            case 'Deal Description':
                $label = _('description');
                break;

            default:
                $label = $field;

        }

        return $label;

    }


}


?>
