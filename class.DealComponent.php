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


    function __construct($a1, $a2 = false) {


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
        } else {
            return;
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Deal Component Key'];
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
            $this->update_deal_component_term_allowances();

            if ($this->data['Deal Component Status'] == 'Active') {
                $this->update_deal_component_assets();
            }


            $this->new = true;
        } else {
            print "Error can not create deal component\n $sql\n";
            exit;

        }
    }

    function update_deal_component_assets() {
        $account = get_object('Account', 1);
        require_once 'utils/new_fork.php';
        new_housekeeping_fork(
            'au_housekeeping', array(
            'type'     => 'deal_updated',
            'deal_key' => $this->get('Deal Component Deal Key')
        ), $account->get('Account Code'), $this->db
        );




        switch ($this->get('Deal Component Allowance Target')) {


            case 'Category':


                $category = get_object('Category', $this->get('Deal Component Allowance Target Key'));

                $webpage = $category->get_webpage();

                if ($webpage->id) {


                    $cache_id = $webpage->get('Webpage Website Key').'|'.$webpage->id;

                    new_housekeeping_fork(
                        'au_housekeeping', array(
                        'type'     => 'clear_smarty_web_cache',
                        'cache_id' => $cache_id
                    ), DNS_ACCOUNT_CODE, $this->db
                    );



                }

                $sql = sprintf(
                    "SELECT `Product Webpage Key`  FROM `Category Bridge` B  LEFT JOIN `Product Dimension` P ON (`Subject Key`=P.`Product ID`)  
                    WHERE  `Category Key`=%d  AND `Product Web State` IN  ('For Sale','Out of Stock')   ", $category->id
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {

                        $webpage = get_object('Webpage', $row['Product Webpage Key']);
                        if ($webpage->id) {
                            $cache_id = $webpage->get('Webpage Website Key').'|'.$webpage->id;

                            new_housekeeping_fork(
                                'au_housekeeping', array(
                                'type'     => 'clear_smarty_web_cache',
                                'cache_id' => $cache_id
                            ), DNS_ACCOUNT_CODE, $this->db
                            );


                        }

                    }
                }


                break;
            default:
                break;
        }


    }

    function get($key = '') {


        switch ($key) {

            case 'Campaign Name':
                $deal_campaign = get_object('DealCampaign', $this->data['Deal Component Campaign Key']);

                return $deal_campaign->get('Name');
            case 'Deal Campaign Name':
                $deal_campaign = get_object('DealCampaign', $this->data['Deal Component Campaign Key']);

                return $deal_campaign->get('Deal Campaign Name');
            case 'Deal Name Label':
            case 'Name Label':
            case 'Deal Term Label':
            case 'Term Label':

                if (!isset($this->deal)) {
                    $this->deal = get_object('Deal', $this->get('Deal Key'));
                }


                return $this->deal->get($key);



            case 'Deal Component Allowance Percentage':
            case 'Allowance Percentage':


                if (is_numeric(($this->get('Deal Component Allowance')))) {
                    return percentage($this->get('Deal Component Allowance'), 1, 0);

                } else {
                    return '';
                }




            case 'Status Icon':
                switch ($this->data['Deal Component Status']) {
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


            case 'Status':
                switch ($this->data['Deal Component Status']) {
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
                        return $this->data['Deal Component Status'];
                }

                break;

            case 'Allowance':

                return $this->get_formatted_allowances();


            case('Description'):
            case('Deal Description'):
                return $this->get_formatted_terms().' <i class="far fa-arrow-right"></i> '.$this->get_formatted_allowances();


            case 'Used Orders':
            case 'Used Customers':
            case 'Applied Orders':
            case 'Applied Customers':


                return number($this->data['Deal Component Total Acc '.$key]);

            case 'Number History Records':
            case 'Number Active Components':
                return number($this->data['Deal Component '.$key]);

            case 'Begin Date':
            case 'Expiration Date':

                if ($this->data['Deal Component '.$key] == '') {
                    return '';
                } else {
                    return strftime("%a, %e %h %Y", strtotime($this->data['Deal Component '.$key]." +00:00"));
                }


            case 'Duration':
                $duration = '';
                if ($this->data['Deal Component Expiration Date'] == '' and $this->data['Deal Component Begin Date'] == '') {
                    $duration = _('permanent');
                } else {

                    if ($this->data['Deal Component Begin Date'] != '') {
                        $duration = strftime(
                            "%a, %e %h %Y", strtotime($this->data['Deal Component Begin Date']." +00:00")
                        );

                    }
                    $duration .= ' - ';
                    if ($this->data['Deal Component Expiration Date'] != '') {
                        $duration .= strftime(
                            "%a, %e %h %Y", strtotime(
                                              $this->data['Deal Component Expiration Date']." +00:00"
                                          )
                        );

                    } else {
                        $duration .= _('permanent');
                    }

                }

                return $duration;
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


    function update_deal_component_term_allowances() {


        $this->fast_update(

            array(
                'Deal Component Term Allowances Label' => '<span class="term">'.$this->get_formatted_terms().'</span> <i class="fa fa-arrow-right"></i> <span class="allowance">'.$this->get_formatted_allowances().'</span>'
            )


        );
    }

    function get_formatted_allowances() {

        $allowance = '';
        switch ($this->data['Deal Component Allowance Type']) {
            case 'Percentage Off':


                if ($this->data['Deal Component Allowance Target'] == 'Category' and $this->data['Deal Component Terms Type'] != 'Category Quantity Ordered') {
                    $allowance = sprintf(_('%s %s off'), $this->data['Deal Component Allowance Target Label'], percentage($this->data['Deal Component Allowance'], 1, 0));
                } else {
                    $allowance = sprintf(_('%s off'), percentage($this->data['Deal Component Allowance'], 1, 0));
                }


                break;
            case 'Shipping Off':
                $allowance = '<i class="fal fa-badge-percent"></i> '._('Shipping');
                break;
            case 'Get Cheapest Free':

                if ($this->data['Deal Component Allowance'] == 1) {
                    $allowance = sprintf(_('cheapest free'));

                } else {
                    $allowance = sprintf(_('cheapest %d free'), $this->data['Deal Component Allowance']);

                }


                break;
            case 'Get Free':


                $allowance_data = json_decode($this->get('Deal Component Allowance'), true);


                switch ($allowance_data['object']) {

                    case 'Choose Product in Basket':

                        $allowance = _('Choose').': (';

                        foreach ($allowance_data['objects'] as $object_data) {
                            $object    = get_object($object_data['object'], $object_data['key']);
                            $allowance .= $object->get('Code').' | ';


                        }

                        $allowance = preg_replace('/\|\s*$/', ' )', $allowance);

                        break;

                    case 'Product':
                        $object = get_object($allowance_data['object'], $allowance_data['key']);
                        if ($allowance_data['qty'] == 1) {
                            $allowance = sprintf(_('Get one %s free'), $object->get('Code'));

                        } else {
                            $allowance = sprintf(_('Get %d %s free'), $allowance_data['qty'], $object->get('Code'));
                        }

                        break;
                    case 'Category':
                        $object = get_object($allowance_data['object'], $allowance_data['key']);
                        if ($allowance_data['qty'] == 1) {
                            $allowance = ', '.sprintf(
                                    _('Get one %s product free'), sprintf(
                                                                    '<span class="link" onclick="change_view(\'store/%d/%d\')">%s</span>', $object->get('Store Key'), $object->id, $object->get('Code')
                                                                )
                                );

                        } else {
                            $allowance = ', '.sprintf(
                                    _('Get %d %s product free'), $allowance_data['qty'], sprintf(
                                                                   '<span class="link" onclick="change_view(\'store/%d/%d\')">%s</span>', $object->get('Store Key'), $object->id, $object->get('Code')
                                                               )
                                );
                        }

                        break;


                    case 'Charge':


                        $object = get_object($allowance_data['object'], $allowance_data['key']);


                        $allowance = sprintf(_('Free %s'), $object->get('Name'));
                        break;
                }


                break;


            case 'Get Free No Ordered Product':


                $allowance_data = json_decode($this->get('Deal Component Allowance'), true);


                switch ($allowance_data['object']) {

                    case 'Choose Product in Basket':

                        $allowance = _('Choose').': (';

                        foreach ($allowance_data['objects'] as $object_data) {
                            $object    = get_object($object_data['object'], $object_data['key']);
                            $allowance .= $object->get('Code').' | ';


                        }

                        $allowance = preg_replace('/\|\s*$/', ' )', $allowance);

                        break;

                    case 'Product':
                        $object = get_object($allowance_data['object'], $allowance_data['key']);
                        if ($allowance_data['qty'] == 1) {
                            $allowance = sprintf(_('Get one %s free'), $object->get('Code'));

                        } else {
                            $allowance = sprintf(_('Get %d %s free'), $allowance_data['qty'], $object->get('Code'));
                        }

                        break;

                }


                break;
            case 'Amount Off':
                $store     = get_object('Store', $this->get('Store Key'));
                $allowance = sprintf(_('%s off'), money($this->data['Deal Component Allowance'], $store->get('Store Currency Code')));


                break;
            default:
                $allowance = '?';

        }

        //  print $allowance."\n";

        return $allowance;


    }

    function get_formatted_terms() {

        $terms = '';


        switch ($this->data['Deal Component Terms Type']) {

            case 'Order Interval':
                $terms = sprintf('last order within %d days', $this->get('Deal Component Terms'));


                break;
            case 'Category Quantity Ordered':


                if ($this->data['Deal Component Terms'] == 1) {
                    $terms = $this->get('Deal Component Allowance Target Label');

                } else {
                    $terms = sprintf('order %d or more %s', $this->data['Deal Component Terms'], $this->get('Deal Component Allowance Target Label'));

                }


                break;
            case 'Category For Every Quantity Ordered':
                $terms = sprintf('%s, buy %d', $this->get('Deal Component Allowance Target Label'), $this->data['Deal Component Terms']);

                break;
            case 'Category For Every Quantity Any Product Ordered':

                if ($this->data['Deal Component Terms'] == 1) {
                    $terms = sprintf('%s (Mix & match)', $this->get('Deal Component Allowance Target Label'));

                } else {
                    $terms = sprintf('%s (Mix & match), for every %d ', $this->get('Deal Component Allowance Target Label'), $this->data['Deal Component Terms']);
                }

                break;
            case 'Amount AND Order Number':
                $store = get_object('Store', $this->data['Deal Component Store Key']);

                $deal_terms_data = preg_split('/;/', $this->get('Deal Component Terms'));

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

                $store = get_object('Store', $this->data['Deal Component Store Key']);


                $_terms = json_decode($this->get('Deal Component Terms'), true);

                if (!$_terms) {
                    $tmp    = preg_split('/\;/', $this->get('Deal Terms'));


                    if(count($tmp)!=3){

                        $_terms = array(
                            'voucher' =>'',
                            'amount'  =>';0;'
                        );
                    }else{

                        $_terms = array(
                            'voucher' => $tmp[0],
                            'amount'  => ';'.$tmp[1].';'.$tmp[2],
                        );
                    }


                }



                $amount_data = preg_split('/;/', $_terms['amount']);

                if(is_array($amount_data)){

                    if(count($amount_data)>1){
                        $amount=$amount_data[1];
                    }elseif(count($amount_data)==1 ){
                        if(is_numeric($amount_data[0]) ){
                            $amount=$amount_data[0];

                        }else{
                            $amount=0;
                        }


                    }else{
                        $amount=0;

                    }


                }elseif(is_numeric($amount_data)){
                    $amount=$amount_data;

                }else{
                    $amount=0;
                }



                $terms = '<span style="border:1px solid ;padding: 1px 10px">'.$_terms['voucher'].'</span> <span style="opacity: .8">'.money($amount, $store->get('Store Currency Code')).'</span>';


                break;

        }


        return $terms;
    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {

        switch ($field) {

            case 'Deal Campaign Name':
                $deal_campaign         = get_object('DealCampaign', $this->data['Deal Component Campaign Key']);
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
                        'Deal_Campaign_Name'=>$value,
                        'Deal_Component_Name_Label'=>$value
                    )
                );

                break;

            case 'Deal Name Label':
            case 'Deal Term Label':

                if (!isset($this->deal)) {
                    $this->deal = get_object('Deal', $this->get('Deal Key'));
                }

                $this->deal->editor = $this->editor;

                $this->deal->update_field_switcher($field, $value, $options);

                break;


            case 'Deal Component Allowance Percentage':

                //used for bulk discounts campaign


                $value = floatval($value) / 100;

                $this->update(array('Deal Component Allowance' => $value), $options);
                break;
            case 'Deal Component Status':

                $this->update_status($value, $options);
                break;

            case 'Deal Terms':
                $deal         = get_object('Deal', $this->data['Deal Component Deal Key']);
                $deal->editor = $this->editor;


                $deal->update(array('Deal Terms' => $value));


                break;


            case 'Deal Component Allowance Label':
                $this->update_field($field, $value, $options);


                $this->update_websites();

                /**
                 * @var $deal \Deal
                 */
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
                $deal->update_deal_term_allowances();

                break;

            default:
                $base_data = $this->base_data();

                if (array_key_exists($field, $base_data)) {
                    $this->update_field($field, $value, $options);
                }
        }
    }

    function update_status($value = '', $options = '') {


        $old_value = $this->data['Deal Component Status'];

        if ($value == 'Suspended') {

            $this->update_field('Deal Component Status', $value, $options);


        } else {
            $this->update_status_from_dates($force = true);
        }


        if ($old_value != $this->data['Deal Component Status']) {

            $this->update_deal_component_assets();


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

                if (!preg_match('/bali|sasi|sakoi|geko/', gethostname())) {
                    $this->update_deal_component_assets();
                    $this->update_deal_component_orders_in_basket_after_status_change($old_value, $value);
                }


            }

            return;
        }


        if (!$force and $this->data['Deal Component Status'] == 'Suspended') {
            return;
        }

        if (strtotime($this->data['Deal Component Begin Date'].' +0:00') > strtotime('now +0:00')) {
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
            if (!preg_match('/bali|sasi|sakoi|geko/', gethostname())) {

                $this->update_deal_component_assets();
                $this->update_deal_component_orders_in_basket_after_status_change($old_value, $value);
            }

        }


    }

    function update_deal_component_orders_in_basket_after_status_change($old_value, $value) {

        if ($old_value == $value) {
            return;
        }
        $date = gmdate('Y-m-d H:i:s');

        $sql = $this->get_eligible_basket_orders_sql();


        $counter = 0;
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                if ($counter < 100) {
                    $operation = 'update_order_in_basket';
                } else {
                    $operation = 'update_order_in_basket_low_priority';

                }

                $sql = sprintf(
                    'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (%s,%s,%s,%d) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=%s ,`Stack Counter`=`Stack Counter`+1 ', prepare_mysql($date), prepare_mysql($date), prepare_mysql($operation), $row['Order Key'], prepare_mysql($date)

                );
                $this->db->exec($sql);
                $counter++;

            }
        }


    }

    function get_eligible_basket_orders_sql() {

        switch ($this->data['Deal Component Trigger']) {
            // todo get only the orders affected by the deal
            default:
                $sql = sprintf(
                    "SELECT `Order Key` FROM `Order Dimension`  left join `Store Dimension` on (`Store Key`=`Order Store Key`) where `Order State`='InBasket'and `Store Key`=%d order by `Order Last Updated Date` desc ", $this->data['Deal Component Store Key']
                );

        }

        return $sql;

    }

    function update_websites() {

        $webpage_keys = array();

        $families    = array();
        $departments = array();
        $products    = array();


        $sql = sprintf(
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

        $smarty_web               = new Smarty();
        $smarty_web->template_dir = 'EcomB2B/templates';
        $smarty_web->compile_dir  = 'EcomB2B/server_files/smarty/templates_c';
        $smarty_web->cache_dir    = 'EcomB2B/server_files/smarty/cache';
        $smarty_web->config_dir   = 'EcomB2B/server_files/smarty/configs';
        $smarty_web->addPluginsDir('./smarty_plugins');
        $smarty_web->setCaching(Smarty::CACHING_LIFETIME_CURRENT);


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

    function get_field_label($field) {

        switch ($field) {

            case 'Deal Name Label':
                $label = _('name');
                break;


            default:
                $label = $field;

        }

        return $label;

    }

    function suspend() {
        $this->update_status('Suspended');


    }


    function activate() {
        $this->update_status();
    }


    function suspend_parent() {

        $deal = get_object('Deal', $this->get('Deal Component Deal Key'));
        $deal->suspend();


    }


    function activate_parent() {
        $deal = get_object('Deal', $this->get('Deal Component Deal Key'));


        $deal->activate();
    }

}


?>
