<?php
/*
  This file contains the List Class

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Creates: 6 April 2015 14:31:11 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';

class SubjectList extends DB_Table {


    function __construct($a1, $a2 = false, $a3 = false) {


        global $db;
        $this->db = $db;

        $this->table_name    = 'List';
        $this->ignore_fields = array('List Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } elseif ($a1 == 'new') {
            $this->create($a2, $a3);

        } else {
            $this->get_data($a1, $a2);
        }
    }


    function get_data($key, $tag) {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `List Dimension` WHERE `List Key`=%d", $tag
            );
        } elseif ($key == 'name') {
            $sql = sprintf(
                "SELECT  *  FROM `List Dimension` WHERE `List Name`=%s ", prepare_mysql($tag)
            );
        } else {
            return;
        }
        if ($this->data = $this->db->query($sql)->fetch()) {

            $this->id = $this->data['List Key'];
        }


    }


    function create($data) {
        $this->new = false;
        $base_data = $this->base_data();

        $this->editor = $data['editor'];

        foreach ($data as $key => $value) {
            if (array_key_exists($key, $base_data)) {
                $base_data[$key] = _trim($value);
            }
        }

        $keys   = '(';
        $values = 'values(';
        foreach ($base_data as $key => $value) {
            $keys   .= "`$key`,";
            $values .= prepare_mysql($value).",";
        }
        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);
        $sql    = sprintf("INSERT INTO `List Dimension` %s %s", $keys, $values);

        if ($this->db->exec($sql)) {
            $this->id = $this->db->lastInsertId();


            $this->msg = 'List created';
            $this->get_data('id', $this->id);
            $this->new = true;


            if ($this->data['List Type'] == 'Static') {

                include_once 'utils/parse_customer_list.php';

                $_data              = json_decode($this->data['List Metadata'], true);
                $_data['store_key'] = $this->data['List Parent Key'];

                list($table, $where) = parse_customer_list($_data, $this->db);

                switch ($this->data['List Scope']) {
                    case 'Customer':
                        $where = sprintf(' where `Customer Store Key`=%d ', $this->data['List Parent Key']).$where;
                        $sql   = "select C.`Customer Key` from $table  $where ";


                        if ($result = $this->db->query($sql)) {
                            foreach ($result as $row) {

                                $sql = sprintf('INSERT INTO `List Customer Bridge` (`List Key`,`Customer Key`) VALUES (%d,%d) ', $this->id, $row['Customer Key']);

                                //  print "$sql\n";
                                $this->db->exec($sql);

                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            print "$sql\n";
                            exit;
                        }


                        break;

                    default:
                        exit('todo create static list bridge');
                }


            }

            $this->update_number_items();


            $history_data = array(
                'History Abstract' => sprintf(_('%s list created'), $this->get('Name')),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );


        } else {
            print $sql;
            exit;
            $this->msg = _("Error can not create list");

        }
    }

    function update_number_items() {

        $number_of_items = 0;




        if ($this->data['List Type'] == 'Static') {


            switch ($this->data['List Scope']) {
                case 'Customer':
                    $sql = sprintf('SELECT count(*) AS num FROM `List Customer Bridge` WHERE `List Key`=%d', $this->id);


                    break;

                default:
                    exit('todo list scope update number items');
            }


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $number_of_items = $row['num'];
                }
            }

        } else {
            include_once 'utils/parse_customer_list.php';

            $_data              = json_decode($this->data['List Metadata'], true);
            $_data['store_key'] = $this->data['List Parent Key'];

            list($table, $where) = parse_customer_list($_data, $this->db);

            switch ($this->data['List Scope']) {
                case 'Customer':
                    $where = sprintf(' where `Customer Store Key`=%d ', $this->data['List Parent Key']).$where;
                    $sql   = "select count(Distinct C.`Customer Key`) as num from $table  $where ";
                    break;

                default:
                    exit('todo list scope update number items');
            }

            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $number_of_items = $row['num'];
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


        }


        $this->fast_update(array('List Number Items' => $number_of_items));

    }

    function get($key, $data = false) {
        switch ($key) {

            case 'Type':

                if ($this->data['List Type'] == 'Static') {
                    return _('Static');
                } else {
                    return _('Dynamic');
                }


            case 'Icon':

                if ($this->data['List Type'] == 'Static') {
                    return 'fal fa-icicles';
                } else {
                    return 'fal fa-tornado';
                }


            case 'Creation Date':
                return strftime("%e %b %Y %H:%M:%S %Z", strtotime($this->data['List Creation Date'].' +0:00'));

            default:

                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('List '.$key, $this->data)) {
                    return $this->data[$this->table_name.' '.$key];
                }
        }

        return '';
    }

    function delete() {

        if ($this->data['List Type'] == 'Static') {
            $sql = sprintf('DELETE FROM `List Customer Bridge` WHERE `List Key`=%d', $this->id);
            $this->db->exec($sql);

        }
        $sql = sprintf('DELETE FROM `List Dimension` WHERE `List Key`=%d', $this->id);
        $this->db->exec($sql);

        switch ($this->data['List Scope']) {
            case 'Customer':

                return 'customers/'.$this->data['List Parent Key'].'/lists';
                break;
        }


    }

    function get_formatted_conditions() {


        $conditions = array();

        if($this->data['List Metadata']=='' or $this->data['List Metadata']=='{}'){
            return $conditions;
        }


        $data = json_decode($this->data['List Metadata'], true);



        if(empty($data['Customer Send Basket Emails'])){
            $data['Customer Send Basket Emails']='';
        }



        if ($data['Customer Status Active'] == 'No' or $data['Customer Status Loosing'] == 'No' or $data['Customer Status Lost'] == 'No') {

            //spacial cases

            if ($data['Customer Status Active'] == 'Yes' and $data['Customer Status Loosing'] == 'No' and $data['Customer Status Lost'] == 'No') {
                $conditions[] = _('Active customers');
            } elseif ($data['Customer Status Active'] == 'No' and $data['Customer Status Loosing'] == 'Yes' and $data['Customer Status Lost'] == 'No') {
                $conditions[] = _('Loosing customers');
            } elseif ($data['Customer Status Active'] == 'No' and $data['Customer Status Loosing'] == 'No' and $data['Customer Status Lost'] == 'Yes') {
                $conditions[] = _('Lost customers');
            } else {
                $tmp = '';
                if ($data['Customer Status Active'] == 'Yes') {
                    $tmp .= _('active customers').', ';
                }
                if ($data['Customer Status Loosing'] == 'Yes') {
                    $tmp .= _('loosing customers').', ';
                }
                if ($data['Customer Status Lost'] == 'Yes') {
                    $tmp .= _('lost customers').', ';
                }

                $tmp          = preg_replace('/\, $/', '', $tmp);
                $tmp          = ucfirst($tmp);
                $conditions[] = $tmp;

            }
        }


        if ($data['Register Date From'] != '' and $data['Register Date To'] != '') {
            $conditions[] = sprintf(
                _('Registered between %s and %s'), strftime("%e %b %Y", strtotime($data['Register Date From'].' +0:00')), strftime("%e %b %Y", strtotime($data['Register Date To'].' +0:00'))

            );
        }

        if ($data['Register Date From'] != '' and $data['Register Date To'] == '') {
            $conditions[] = sprintf(
                _('Registered from %s'), strftime("%e %b %Y", strtotime($data['Register Date From'].' +0:00'))

            );
        }
        if ($data['Register Date From'] == '' and $data['Register Date To'] != '') {
            $conditions[] = sprintf(
                _('Registered up to %s'), strftime("%e %b %Y", strtotime($data['Register Date To'].' +0:00'))

            );
        }


        if ($data['Location'] != '') {
            $tmp            = '';
            $countries_data = array();

            include_once 'class.Country.php';
            $locations = preg_split('/\,/i', $data['Location']);

            foreach ($locations as $location) {
                $location = trim($location);
                if ($location == '') {
                    continue;
                }

                $country = new Country('find', $location);

                //   print_r($country);
                //exit;
                if ($country->id and $country->get('Country Code') != 'UNK') {
                    $countries_data[$country->get('Country 2 Alpha Code')] = array(
                        'name' => $country->get('Country Name'),
                        'code' => $country->get('Country 2 Alpha Code')
                    );
                }


            }

            //   print_r($country_codes);


            foreach ($countries_data as $country_data) {
                $tmp .= sprintf('<img src="/art/flags/%s.png" title="%s" > %s, ', strtolower($country_data['code']), $country_data['code'], $country_data['name']);
            }

            $tmp = preg_replace('/\, $/', '', $tmp);


            if ($tmp != '') {
                $conditions[] = $tmp;
            }


        }


        $conditions[] = sprintf(
            '
<i title="%s" style="margin-right: 10px;position: relative;top:1px" class="%s fa fa-fw fa-newspaper" aria-hidden="true"></i> 
<i title="%s" style="margin-right: 10px"  class=" %s fa fa-fw fa-comment-alt-smile" aria-hidden="true"></i>
<i title="%s" style="margin-right: 10px"  class=" %s fa fa-fw fa-shopping-basket" aria-hidden="true"></i>    
<i title="%s" class="%s fal fa-fw fa-clipboard" aria-hidden="true"></i>',
            _('Newsletters'), ($data['Customer Send Newsletter'] == 'No' ? 'discreet error' : ''),
            _('Marketing by email'), ($data['Customer Send Email Marketing'] == 'No' ? 'discreet error' : ''),
            _('Basket engagement'), (   $data['Customer Send Basket Emails'] == 'No' ? 'discreet error' : ''),
            _('Marketing by post'), ($data['Customer Send Postal Marketing'] == 'No' ? 'discreet error' : '')
        );


        if ($data['With Email'] == 'Yes' or $data['With Telephone'] == 'Yes' or $data['With Mobile'] == 'Yes' or $data['With Tax Number'] == 'Yes') {

            $tmp = _('With').": ";
            if ($data['With Email'] == 'Yes') {
                $tmp .= sprintf('<i title="%s" style="margin-right: 10px"  class="  fa fa-fw fa-at" aria-hidden="true"></i> ', _('Email'));
            }
            if ($data['With Telephone'] == 'Yes') {
                $tmp .= sprintf('<i title="%s" style="margin-right: 10px"  class=" fa fa-fw fa-phone" aria-hidden="true"></i> ', _('Telephone'));
            }
            if ($data['With Mobile'] == 'Yes') {
                $tmp .= sprintf('<i title="%s" style="margin-right: 10px"  class=" fa fa-fw fa-mobile" aria-hidden="true"></i> ', _('Mobile'));
            }

            if ($data['With Tax Number'] == 'Yes') {
                $tmp .= sprintf('<i title="%s" style="margin-right: 10px"  class=" fab fa-fw fa-black-tie" aria-hidden="true"></i> ', _('Valid tax number'));
            }
            $conditions[] = $tmp;
        }


        if ($data['With Email'] == 'No' or $data['With Telephone'] == 'No' or $data['With Mobile'] == 'No' or $data['With Tax Number'] == 'No') {

            $tmp = _('Without').": ";
            if ($data['With Email'] == 'No') {
                $tmp .= sprintf('<i title="%s" style="margin-right: 10px"  class="  fa fa-fw fa-at discreet " aria-hidden="true"></i> ', _('Email'));
            }
            if ($data['With Telephone'] == 'No') {
                $tmp .= sprintf('<i title="%s" style="margin-right: 10px"  class=" fa fa-fw fa-phone discreet" aria-hidden="true"></i> ', _('Telephone'));
            }
            if ($data['With Mobile'] == 'No') {
                $tmp .= sprintf('<i title="%s" style="margin-right: 10px"  class="  fa fa-fw fa-mobile discreet" aria-hidden="true"></i> ', _('Mobile'));
            }

            if ($data['With Tax Number'] == 'No') {
                $tmp .= sprintf('<i title="%s" style="margin-right: 10px"  class=" fab fa-fw fa-black-tie discreet" aria-hidden="true"></i> ', _('Valid tax number'));
            }
            $conditions[] = $tmp;
        }

        $products_data   = array();
        $categories_data = array();
        if ($data['Assets'] != '') {


            $asset_codes = preg_split('/\,/i', $data['Assets']);

            foreach ($asset_codes as $asset_code) {
                $asset_code = trim($asset_code);
                if ($asset_code == '') {
                    continue;
                }


                $sql = sprintf(
                    'SELECT `Product ID`,`Product Code`,`Product Store Key`,`Product Name` FROM `Product Dimension` WHERE `Product Code`=%s AND `Product Store Key`=%d ', prepare_mysql($asset_code), $this->data['List Parent Key']
                );

                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $products_data[$row['Product ID']] = array(
                            'code'  => $row['Product Code'],
                            'id'    => $row['Product ID'],
                            'label' => $row['Product Name']
                        );
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

                $sql = sprintf(
                    'SELECT `Category Label`,`Category Code`,`Category Key` FROM `Category Dimension`  WHERE `Category Code`=%s AND `Category Store Key`=%d AND `Category Scope`="Product" ', prepare_mysql($asset_code), $this->data['List Parent Key']
                );

                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $categories_data[$row['Category Key']] = array(
                            'code'  => $row['Category Code'],
                            'id'    => $row['Category Key'],
                            'label' => $row['Category Label']
                        );
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


            }
            $tmp = '';
            foreach ($products_data as $product_data) {
                $tmp .= sprintf('<span  class="link"  onclick="change_view(\'/products/%d/%d\')" title="%s"> %s</span>, ', $this->data['List Parent Key'], $product_data['id'], $product_data['label'], $product_data['code']);
            }
            if ($tmp != '') {
                $tmp = preg_replace('/\, $/', '', $tmp);

                $conditions[] = '<i title="'._('Products').'" style="margin-right: 10px"  class="  fa fa-fw fa-cube  " aria-hidden="true"></i> '.$tmp;
            }
            $tmp = '';

            foreach ($categories_data as $category_data) {
                $tmp .= sprintf('<span class="link" onclick="change_view(\'/products/%d/category/%d\')" title="%s"> %s</span>, ', $this->data['List Parent Key'], $category_data['id'], $category_data['label'], $category_data['code']);
            }

            if ($tmp != '') {
                $tmp = preg_replace('/\, $/', '', $tmp);

                $conditions[] = '<i title="'._('Categories').'" style="margin-right: 10px"  class="  fa fa-fw fa-sitemap  " aria-hidden="true"></i> '.$tmp;
            }
        }


        //===


        if ($data['Ordered Date From'] != '' and $data['Ordered Date To'] != '' and (count($products_data) > 0 or count($categories_data) > 0)) {


            if ($data['Order State Basket'] == 'No' or $data['Order State Processing'] == 'No' or $data['Order State Dispatched'] == 'No' or $data['Order State Cancelled'] == 'No') {

                //spacial cases

                if ($data['Order State Basket'] == 'Yes' and $data['Order State Processing'] == 'No' and $data['Order State Dispatched'] == 'No' and $data['Order State Cancelled'] == 'No') {
                    $conditions[] = _('Products in basket');
                } elseif ($data['Order State Basket'] == 'No' and $data['Order State Processing'] == 'Yes' and $data['Order State Dispatched'] == 'No' and $data['Order State Cancelled'] == 'No') {
                    $conditions[] = _('Orders submitted & in warehouse');
                } elseif ($data['Order State Basket'] == 'No' and $data['Order State Processing'] == 'No' and $data['Order State Dispatched'] == 'Yes' and $data['Order State Cancelled'] == 'No') {
                    $conditions[] = _('Products dispatched');
                } elseif ($data['Order State Basket'] == 'No' and $data['Order State Processing'] == 'No' and $data['Order State Dispatched'] == 'No' and $data['Order State Cancelled'] == 'Yes') {
                    $conditions[] = _('In cancelled orders');
                } else {
                    $tmp = '';
                    if ($data['Order State Basket'] == 'Yes') {
                        $tmp .= _('in basket').', ';
                    }
                    if ($data['Order State Processing'] == 'Yes') {
                        $tmp .= _('submitted & in warehouse').', ';
                    }
                    if ($data['Order State Dispatched'] == 'Yes') {
                        $tmp .= _('dispatched').', ';
                    }
                    if ($data['Order State Cancelled'] == 'Yes') {
                        $tmp .= _('cancelled').', ';
                    }

                    $tmp          = preg_replace('/\, $/', '', $tmp);
                    $tmp          = ucfirst(_('orders').' '.$tmp);
                    $conditions[] = $tmp;

                }
            }
        }


        //===

        return $conditions;


    }

}



