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

    /** @var PDO */
    var $db;

    function __construct($a1, $a2 = false, $a3 = false) {

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
        } else {
            return;
        }


        if ($this->data = $this->db->query($sql)->fetch()) {

            $this->id = $this->data['Deal Key'];
        }


        if ($this->data['Deal Remainder Email Campaign Key'] > 0) {
            $this->remainder_email_campaign = get_object('EmailCampaign', $this->data['Deal Remainder Email Campaign Key']);

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
                $this->found            = true;
                $this->found_key        = $row['Deal Key'];
                $this->duplicated_field = 'Deal Name';
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


        $sql = sprintf(
            "INSERT INTO `Deal Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($data)).'`', join(',', array_fill(0, count($data), '?'))
        );


        $stmt = $this->db->prepare($sql);

        $i = 1;
        foreach ($data as $key => $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {
            $this->id = $this->db->lastInsertId();


            $this->get_data('id', $this->id);
            $this->new = true;


            $this->update_status_from_dates();

            $this->update_deal_term_allowances();


            $store = get_object('Store', $this->data['Deal Store Key']);
            $store->update_deals_data();

            $this->fork_index_elastic_search();

        } else {
            print "Error can not create deal  $sql\n";
            exit;

        }
    }

    function update_status_from_dates($force = false) {


        if ($this->data['Deal Expiration Date'] != '' and strtotime($this->data['Deal Expiration Date'].' +0:00') <= strtotime('now +0:00')) {
            $this->fast_update(
                array(
                    'Deal Status' => 'Finish'
                )
            );

            if ($this->data['Deal Voucher Key']) {
                $voucher         = get_object('Voucher', $this->data['Deal Voucher Key']);
                $voucher->editor = $this->editor;
                $voucher->fast_update(
                    array(
                        'Voucher Status' => 'Finish'
                    )
                );


            }
            $this->fork_index_elastic_search();

            return;
        }


        if (!$force and $this->data['Deal Status'] == 'Suspended') {
            return;
        }

        if (strtotime($this->data['Deal Begin Date'].' +0:00') > strtotime('now +0:00')) {

            $this->fast_update(
                array(
                    'Deal Status' => 'Waiting'
                )
            );

        }


        if (strtotime($this->data['Deal Begin Date'].' +0:00') <= strtotime('now +0:00')) {
            $this->fast_update(
                array(
                    'Deal Status' => 'Active'
                )
            );

        }
        $this->fork_index_elastic_search();

    }

    function update_deal_term_allowances() {


        $this->fast_update(

            array(
                'Deal Term Allowances Label' => '<span class="term">'.$this->get_formatted_terms().'</span> <i class="fa fa-arrow-right"></i> <span class="allowance">'.$this->get_formatted_allowances().'</span>'
            )


        );
        $this->fork_index_elastic_search();
    }

    function get_formatted_terms() {

        $terms = '';

        switch ($this->data['Deal Terms Type']) {


            case 'Product Amount Ordered':


                $store = get_object('Store', $this->get('Store Key'));

                if ($this->data['Deal Trigger'] == 'Customer') {

                    $customer = $this->get('Trigger Object');

                    $terms = 'C'.$customer->get('Formatted ID');


                    $terms_data = json_decode($this->data['Deal Terms'], true);


                } else {

                    $terms = '';
                }


                $asset = get_object($terms_data['object'], $terms_data['key']);

                $terms .= ' '.$asset->get('Code');

                if ($terms_data['amount'] == 0) {

                } else {
                    $terms .= ' +'.money($terms_data['amount'], $store->get('Store Currency Code'));
                }


                break;

            case 'Category Amount Ordered':

                $store = get_object('Store', $this->get('Store Key'));

                if ($this->data['Deal Trigger'] == 'Customer') {

                    $customer = $this->get('Trigger Object');

                    $terms = 'C'.$customer->get('Formatted ID');


                } else {
                    $terms = '';

                }


                $terms_data = json_decode($this->data['Deal Terms'], true);


                $asset = get_object($terms_data['object'], $terms_data['key']);

                $terms .= ' '.$asset->get('Code');

                if ($terms_data['amount'] == 0) {

                } else {
                    $terms .= ' +'.money($terms_data['amount'], $store->get('Store Currency Code'));
                }

                break;

            case 'Amount':


                if ($this->data['Deal Trigger'] == 'Customers') {

                    $customer = $this->get('Trigger Object');

                    $terms = $customer->get('Formatted ID');


                } else {
                    $terms = '';
                }

                $deal_terms_data = preg_split('/\;/', $this->get('Deal Terms'));


                if (is_array($deal_terms_data) and count($deal_terms_data) == 2) {

                    $amount = $deal_terms_data[0];


                    if ($amount == 0) {
                        $terms .= ' <span title="'._('All orders').'">&#8704; <i class="fal fa-shopping-cart"></i></span>';
                    } else {

                        $store = get_object('Store', $this->data['Deal Store Key']);

                        $terms .= sprintf('<span><i class="fal fa-shopping-cart"></i> %s<i class="fal fa-arrow-from-bottom"></i> </span>', money($amount, $store->get('Store Currency Code')));
                    }
                } else {
                    $terms .= 'Error';
                }


                break;
            case 'Order Interval':
                $terms = sprintf('last order within %d days', $this->get('Deal Terms'));
                break;
            case 'Category Quantity Ordered':
                $category = get_object('Category', $this->data['Deal Trigger Key']);
                if ($this->data['Deal Terms'] == 1) {
                    $terms = $category->get('Code');
                } else {
                    $terms = sprintf('order %d or more %s', $this->data['Deal Terms'], $category->get('Code'));
                }
                break;
            case 'Category For Every Quantity Ordered':
                $category = get_object('Category', $this->data['Deal Trigger Key']);
                $terms    = sprintf('%s, buy %d', $category->get('Code'), $this->data['Deal Terms']);
                break;
            case 'Category For Every Quantity Any Product Ordered':
                $category = get_object('Category', $this->data['Deal Trigger Key']);


                if ($category->id) {
                    if ($this->data['Deal Terms'] == 1) {
                        $terms = sprintf('%s (Mix & match)', $category->get('Code'));

                    } else {
                        $terms = sprintf('%s (Mix & match), for every %d ', $category->get('Code'), $this->data['Deal Terms']);

                    }
                } else {

                    $terms = _('Error category not found');

                }


                break;


            case 'Amount AND Order Number':
                $store = get_object('Store', $this->data['Deal Store Key']);

                $deal_terms_data = preg_split('/\;/', $this->get('Deal Terms'));

                if (is_array($deal_terms_data) and count($deal_terms_data) == 3) {

                    $amount       = $deal_terms_data[1];
                    $order_number = $deal_terms_data[2];


                    $nf = new NumberFormatter('en_GB', NumberFormatter::ORDINAL);


                    if ($amount == 0) {
                        $terms = $nf->format($order_number).' order';
                    } else {
                        $terms = sprintf('%s order <span style="opacity: .8"> %s<i class="fal fa-arrow-from-bottom"></i></span>', $nf->format($order_number), money($amount, $store->get('Store Currency Code')));
                    }
                } else {
                    $terms = 'Error';
                }


                //print $this->get('Deal Terms');

                break;

            case 'Voucher AND Amount':

                $store = get_object('Store', $this->data['Deal Store Key']);

                $_terms = json_decode($this->get('Deal Terms'), true);

                if (!$_terms) {
                    $tmp = preg_split('/\;/', $this->get('Deal Terms'));


                    if (count($tmp) != 3) {

                        $_terms = array(
                            'voucher' => '',
                            'amount'  => ';0;'
                        );
                    } else {

                        $_terms = array(
                            'voucher' => $tmp[0],
                            'amount'  => ';'.$tmp[1].';'.$tmp[2],
                        );
                    }


                }


                $amount_data = preg_split('/\;/', $_terms['amount']);

                if (is_array($amount_data)) {

                    if (count($amount_data) > 1) {
                        $amount = $amount_data[1];
                    } elseif (count($amount_data) == 1) {
                        if (is_numeric($amount_data[0])) {
                            $amount = $amount_data[0];

                        } else {
                            $amount = 0;
                        }


                    } else {
                        print_r($amount_data);

                    }


                } elseif (is_numeric($amount_data)) {
                    $amount = $amount_data;

                } else {
                    $amount = 0;
                }


                $terms = '<span style="border:1px solid ;padding: 1px 10px">'.$_terms['voucher'].'</span> <span style="opacity: .8">'.money($amount, $store->get('Store Currency Code')).'</span>';


                break;


        }


        return $terms;
    }

    function get($key = '') {

        if (!$this->id) {
            return '';
        }

        switch ($key) {

            case 'Campaign Name':
                $deal_campaign = get_object('DealCampaign', $this->data['Deal Campaign Key']);

                return $deal_campaign->get('Name');
            case 'Deal Campaign Name':
                $deal_campaign = get_object('DealCampaign', $this->data['Deal Campaign Key']);

                return $deal_campaign->get('Deal Campaign Name');
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

            case 'Trigger Object':


                return get_object($this->data['Deal Trigger'], $this->data['Deal Trigger Key']);
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

    function get_deal_components($type = 'keys', $options = 'Active') {

        $deal_components = array();


        if ($options == 'Active') {

            $where = ' and `Deal Component Status`="Active"';

        } elseif ($options == 'Suspended') {

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
        }


        return $deal_components;
    }

    function get_formatted_allowances() {

        $allowances = '';
        $sql        = sprintf(
            "SELECT  `Deal Component Allowance Type`,`Deal Component Allowance` FROM `Deal Component Dimension` WHERE `Deal Component Deal Key`=%d ", $this->id
        );
        $count      = 0;

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                //print_r($row);

                $count++;
                if ($count <= 2) {


                    switch ($row['Deal Component Allowance Type']) {
                        case 'Amount Off':
                            $store      = get_object('Store', $this->get('Store Key'));
                            $allowances .= ', '.sprintf(_('%s off'), money($row['Deal Component Allowance'], $store->get('Store Currency Code')));

                            break;
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
                        case 'Shipping Off':
                            $allowances .= ', <i class="fal fa-badge-percent"></i> '._('Shipping');
                            break;

                        case 'Get Free':


                            $allowance_data = json_decode($row['Deal Component Allowance'], true);


                            switch ($allowance_data['object']) {
                                case 'Product':
                                    $object = get_object($allowance_data['object'], $allowance_data['key']);
                                    if ($allowance_data['qty'] == 1) {
                                        $allowances .= ', '.sprintf(
                                                _('Get one %s free'), sprintf(
                                                                        '<span class="link" onclick="change_view(\'store/%d/%d\')">%s</span>', $object->get('Store Key'), $object->id, $object->get('Code')
                                                                    )
                                            );

                                    } else {
                                        $allowances .= ', '.sprintf(
                                                _('Get %d %s free'), $allowance_data['qty'], sprintf(
                                                                       '<span class="link" onclick="change_view(\'store/%d/%d\')">%s</span>', $object->get('Store Key'), $object->id, $object->get('Code')
                                                                   )
                                            );
                                    }

                                    break;

                                case 'Category':
                                    $object = get_object($allowance_data['object'], $allowance_data['key']);
                                    if ($allowance_data['qty'] == 1) {
                                        $allowances .= ', '.sprintf(
                                                _('Get one %s product free'), sprintf(
                                                                                '<span class="link" onclick="change_view(\'store/%d/%d\')">%s</span>', $object->get('Store Key'), $object->id, $object->get('Code')
                                                                            )
                                            );

                                    } else {
                                        $allowances .= ', '.sprintf(
                                                _('Get %d %s product free'), $allowance_data['qty'], sprintf(
                                                                               '<span class="link" onclick="change_view(\'store/%d/%d\')">%s</span>', $object->get('Store Key'), $object->id, $object->get('Code')
                                                                           )
                                            );
                                    }

                                    break;

                                case 'Charge':
                                    $object = get_object($allowance_data['object'], $allowance_data['key']);

                                    $allowances .= ', '.sprintf(_('Free %s'), $object->get('Code'));

                            }


                            break;
                        case 'Get Free No Ordered Product':


                            $allowance_data = json_decode($row['Deal Component Allowance'], true);
                            switch ($allowance_data['object']) {
                                case 'Product':
                                    $object = get_object($allowance_data['object'], $allowance_data['key']);
                                    if ($allowance_data['qty'] == 1) {
                                        $allowances .= ', '.sprintf(
                                                _('Get one %s free'), sprintf(
                                                                        '<span class="link" onclick="change_view(\'store/%d/%d\')">%s</span>', $object->get('Store Key'), $object->id, $object->get('Code')
                                                                    )
                                            );

                                    } else {
                                        $allowances .= ', '.sprintf(
                                                _('Get %d %s free'), $allowance_data['qty'], sprintf(
                                                                       '<span class="link" onclick="change_view(\'store/%d/%d\')">%s</span>', $object->get('Store Key'), $object->id, $object->get('Code')
                                                                   )
                                            );
                                    }

                                    break;


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

    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        switch ($field) {
            case 'Deal Campaign Name':
                $deal_campaign         = get_object('DealCampaign', $this->data['Deal Campaign Key']);
                $deal_campaign->editor = $this->editor;
                $deal_campaign->update(
                    array(
                        'Deal Campaign Name' => $value
                    ), $options
                );


                $this->update_field('Deal Name Label', $value, $options);


                $this->update_metadata = array(
                    'class_html' => array(
                        'Deal_Name_Label' => $value,
                    )
                );


                break;

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


                $this->update_deal_term_allowances();

                $components = $this->get_deal_components('objects', 'All');

                foreach ($components as $component) {


                    $component->editor = $this->editor;
                    $component->update(array('Deal Component Terms' => $value), $options);

                }


                break;

            case 'Deal Term Label':
            case 'Deal Name Label':
                $this->update_field($field, $value, $options);


                $this->update_websites();
                $this->fork_index_elastic_search();

                break;
            case('Deal Begin Date'):
                $this->update_begin_date($value, $options);
                break;
            case('Deal Expiration Date'):
                $this->update_expiration_date($value, $options);
                break;
            case 'Deal Status':

                $this->update_status($value);
                break;
            case 'Deal Name':
                $this->update_field($field, $value, $options);
                $this->fork_index_elastic_search();
                break;
            default:
                $base_data = $this->base_data();

                if (array_key_exists($field, $base_data)) {
                    $this->update_field($field, $value, $options);
                }
        }
    }

    function update_websites() {

        $webpage_keys = array();
        $products     = array();
        $families     = array();
        $departments  = array();
        $sql          = sprintf(
            "select `Deal Component Trigger Key`,`Category Scope` from  `Deal Component Dimension`  left join `Category Dimension` on (`Deal Component Trigger Key`=`Category Key`)   where `Deal Component Deal Key`=%d  and `Deal Component Trigger`='Category'  ",
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
        }

        $sql = sprintf(
            "select `Deal Component Allowance Target Key`,`Category Scope` from  `Deal Component Dimension`  left join `Category Dimension` on (`Deal Component Allowance Target Key`=`Category Key`)    where `Deal Component Deal Key`=%d  and `Deal Component Allowance Target`='Category'   ",
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
        }


        if (count($families) > 0) {
            $sql = sprintf('select group_concat(`Subject Key`) as products from `Category Bridge` where `Category Key` in (%s) ', join($families, ','));

            //  print $sql;
            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $products = preg_split('/,/', $row['products']);
                }
            }

        }


        foreach ($products as $product_id) {
            $sql = sprintf("select `Page Key` from `Page Store Dimension` where `Webpage Scope`='Product' and `Webpage Scope Key`=%d ", $product_id);

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $webpage_keys[$row['Page Key']] = $row['Page Key'];
                }
            }

        }

        foreach ($families as $family_key) {
            $sql = sprintf("select `Page Key`,`Webpage Website Key` from `Page Store Dimension` where `Webpage Scope`='Category Products' and `Webpage Scope Key`=%d ", $family_key);

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $webpage_keys[$row['Page Key']] = array(
                        $row['Webpage Website Key'],
                        $row['Page Key']
                    );
                }
            }

        }


        require_once 'utils/new_fork.php';

        foreach ($webpage_keys as $data) {

            $cache_id = $data[0].'|'.$data[1];

            new_housekeeping_fork(
                'au_housekeeping', array(
                'type'     => 'clear_smarty_web_cache',
                'cache_id' => $cache_id
            ), DNS_ACCOUNT_CODE, $this->db
            );


        }


    }

    function update_begin_date($value, $options) {
        $this->updated = false;

        if ($this->data['Deal Status'] == 'Waiting') {

            $this->update_field('Deal Begin Date', $value, $options);

            $sql = sprintf(
                "SELECT `Deal Component Key` FROM `Deal Component Dimension` WHERE `Deal Component Status`='Waiting' AND `Deal Component Deal Key`=%d", $this->id
            );

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $deal_component = new DealComponent($row['Deal Component Key']);
                    $deal_component->update(
                        array('Deal Component Begin Date' => $value)
                    );
                }
            }


            $this->update_status_from_dates();
            $this->updated = true;


        } else {
            $this->error = true;
            $this->msg   = 'Deal already started';
        }


        $this->update_metadata = array(
            'class_html' => array(
                'Duration'    => $this->get('Duration'),
                'Status_Icon' => $this->get('Status Icon'),
                'Status'      => $this->get('Status')


            )
        );


    }

    function update_expiration_date($value, $options = '') {


        $this->update_field('Deal Expiration Date', $value, $options);
        $this->updated = true;

        $sql = sprintf(
            "SELECT `Deal Component Key` FROM `Deal Component Dimension` WHERE `Deal Component Status`!='Finish' AND `Deal Component Deal Key`=%d", $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $deal_component = get_object('DealComponent', $row['Deal Component Key']);
                $deal_component->update(array('Deal Component Expiration Date' => $value));
                $deal_component->update_status_from_dates();
            }
        }


        $this->update_status_from_dates();

        $this->update_metadata = array(
            'class_html' => array(
                'Duration'    => $this->get('Duration'),
                'Status_Icon' => $this->get('Status Icon'),
                'Status'      => $this->get('Status')


            )
        );


    }

    function update_status($value = '') {

        if ($value == 'Suspended') {
            $this->update_field('Deal Status', $value);
        } else {
            $this->update_status_from_dates($force = true);
        }
        $this->fork_index_elastic_search();

    }

    function finish() {

        if ($this->data['Deal Status'] == 'Finish') {
            $this->error = true;
            $this->msg   = 'Deal already finished';

            return;
        }

        $this->update_expiration_date(gmdate('Y-m-d H:i:s'));

    }

    public function update_allowance_label() {


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
        $data['Deal Component Icon']         = $campaign->get('Deal Campaign Icon');

        $data['Deal Component Status'] = $this->data['Deal Status'];


        $hereditary_fields = array(
            'Expiration Date',
            'Term Label',
            'Status',
            'Name',
            'Trigger',
            'Trigger Key',
            'Terms Type',
            'Terms',
        );
        foreach ($hereditary_fields as $hereditary_field) {
            if (!array_key_exists('Deal Component '.$hereditary_field, $data)) {
                $data['Deal Component '.$hereditary_field] = $this->data['Deal '.$hereditary_field];
            }
        }


        $deal_component = new DealComponent('find create', $data);
        //$deal_component->update_status($this->data['Deal Status']);
        $this->update_number_components();
        $this->update_deal_term_allowances();

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


        foreach ($this->get_deal_components('objects', 'all') as $component) {
            $component->update_status('Suspended');
        }

    }

    function activate() {
        $this->update_status();


        foreach ($this->get_deal_components('objects', 'all') as $component) {


            $component->update_status();
        }
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

        $campaign         = get_object('DealCampaign', $this->data['Deal Campaign Key']);
        $campaign->editor = $this->editor;

        $campaign->update_number_of_deals();
        $this->fork_index_elastic_search('delete_elastic_index_object');


    }

    function update_usage() {


        $sql               = sprintf(
            "SELECT count( DISTINCT O.`Order Key`) AS orders,count( DISTINCT `Order Customer Key`) AS customers FROM `Order Deal Bridge` B LEFT  JOIN `Order Dimension` O ON (O.`Order Key`=B.`Order Key`) WHERE B.`Deal Key`=%d AND `Applied`='Yes' AND `Order State`!='Cancelled' ",
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
                $label = _('code');
                break;
            case 'Deal Name Label':
                $label = _('public name');
                break;
            case 'Deal Term Label':
                $label = _('public terms info');
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
