<?php
/*
 File: Part.php

 This file contains the Part Class

 About:
 Author: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

include_once 'class.Asset.php';

class Part extends Asset {


    public $sku = false;
    public $warehouse_key = 1;
    public $locale = 'en_GB';

    function __construct($arg1, $arg2 = false, $arg3 = false, $_db = false) {


        if (!$_db) {
            global $db;
            $this->db = $db;
        } else {
            $this->db = $_db;
        }

        $this->table_name    = 'Part';
        $this->ignore_fields = array('Part Key');

        if (is_numeric($arg1) and !$arg2) {
            $this->get_data('id', $arg1);

            return;
        }


        if (preg_match('/^find/i', $arg1)) {

            $this->find($arg2, $arg3);

            return;
        }

        if (preg_match('/^create/i', $arg1)) {
            $this->create($arg2);

            return;
        }


        $this->get_data($arg1, $arg2);

    }


    function get_data($tipo, $tag) {
        if ($tipo == 'id' or $tipo == 'sku') {
            $sql = sprintf(
                "SELECT * FROM `Part Dimension` WHERE `Part SKU`=%d ", $tag
            );
        } elseif ($tipo == 'barcode') {
            $sql = sprintf(
                "SELECT * FROM `Part Dimension` WHERE `Part SKO Barcode`=%s ", prepare_mysql($tag)
            );
        } else {
            if ($tipo == 'code' or $tipo == 'reference') {
                $sql = sprintf(
                    "SELECT * FROM `Part Dimension` WHERE `Part Reference`=%s ", prepare_mysql($tag)
                );
            } else {
                return;
            }
        }

        // print $sql;

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id  = $this->data['Part SKU'];
            $this->sku = $this->data['Part SKU'];
        }


    }

    function find($raw_data, $options) {


        if (isset($raw_data['editor'])) {
            foreach ($raw_data['editor'] as $key => $value) {

                if (array_key_exists($key, $this->editor)) {
                    $this->editor[$key] = $value;
                }

            }
        }


        $create = '';
        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }


        $data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $data[$key] = _trim($value);
            }
        }


        $sql = sprintf(
            "SELECT `Part SKU` FROM `Part Dimension` WHERE `Part Reference`=%s", prepare_mysql($data['Part Reference'])
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->found     = true;
                $this->found_key = $row['Part SKU'];
                $this->get_data('id', $this->found_key);
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        if ($create and !$this->found) {


            $this->create($raw_data);

        }


    }

    function create($data) {

        include_once 'class.Account.php';
        include_once 'class.Category.php';


        $account = new Account($this->db);

        if (array_key_exists('Part Family Category Code', $data) and $data['Part Family Category Code'] != '') {

            $root_category = new Category(
                $account->get('Account Part Family Category Key')
            );
            if ($root_category->id) {


                $root_category->editor = $this->editor;
                $family                = $root_category->create_category(array('Category Code' => $data['Part Family Category Code']));
                if ($family->id) {
                    $data['Part Family Category Key'] = $family->id;
                }
            }
        }

        if (!isset($data['Part Valid From']) or $data['Part Valid From'] == '') {
            $data['Part Valid From'] = gmdate('Y-m-d H:i:s');
        }


        if (!isset($data['Part Production Supply']) or $data['Part Production Supply'] != 'Yes') {
            $data['Part Production Supply'] = 'No';
        }

        $base_data = $this->base_data();
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $base_data)) {
                $base_data[$key] = _trim($value);
            }
        }


        //   $base_data['Part Available']='No';

        //  if ($base_data['Part XHTML Description']=='') {
        //   $base_data['Part XHTML Description']=strip_tags($base_data['Part XHTML Description']);
        //  }

        //print_r($base_data);

        $keys   = '(';
        $values = 'values(';
        foreach ($base_data as $key => $value) {
            $keys .= "`$key`,";

            if (in_array(
                $key, array(
                        'Part XHTML Next Supplier Shipment',
                        'Part XHTML Picking Location'
                    )
            )) {
                $values .= prepare_mysql($value, false).",";

            } else {

                $values .= prepare_mysql($value).",";
            }
        }
        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);

        //print_r($base_data);

        $sql = sprintf("INSERT INTO `Part Dimension` %s %s", $keys, $values);


        if ($this->db->exec($sql)) {
            $this->id  = $this->db->lastInsertId();
            $this->sku = $this->id;
            $this->new = true;

            $sql = "INSERT INTO `Part Data` (`Part SKU`) VALUES(".$this->id.");";
            $this->db->exec($sql);


            $this->get_data('id', $this->id);


            if (!empty($data['Part Barcode'])) {
                $this->update(array('Part Barcode' => $data['Part Barcode']), 'no_history');
            }
            $this->update_next_deliveries_data();

            $this->calculate_forecast_data();

            $this->update_products_web_status();

            $history_data = array(
                'Action'           => 'created',
                'History Abstract' => _('Part created'),
                'History Details'  => ''
            );
            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );


            //  print 'x'.$this->get('Part Family Category Key')."\n";

            if ($this->get('Part Family Category Key')) {
                $family         = new Category(
                    $this->get('Part Family Category Key')
                );
                $family->editor = $this->editor;


                if ($family->id) {
                    $family->associate_subject($this->id);
                }
            }


            $this->validate_barcode();

        } else {
            print "Error Part can not be created $sql\n";
            $this->msg = 'Error Part can not be created';
            exit;
        }

    }

    function update_next_deliveries_data() {

        $data = $this->get_next_deliveries_data();


        $this->fast_update(
            array(
                'Part Next Deliveries Data'     => json_encode($data['deliveries']),
                'Part Next Shipment Date'       => $data['next_delivery_time'],
                'Part Number Active Deliveries' => $data['number_non_draft_POs'],
                'Part Number Draft Deliveries'  => $data['number_draft_POs']
            )
        );


        foreach ($this->get_products('objects') as $product) {
            $product->editor = $this->editor;
            $product->update_next_shipment();
        }

    }

    function get_next_deliveries_data() {


        $next_delivery_time   = 0;
        $next_deliveries_data = array();

        $number_draft_POs     = 0;
        $number_non_draft_POs = 0;

        $supplier_parts = $this->get_supplier_parts();
        if (count($supplier_parts) > 0) {


            $sql = sprintf(
                'SELECT  `Supplier Part Packages Per Carton`,`Purchase Order Key`,`Supplier Delivery Transaction State`,`Supplier Delivery Parent`,`Supplier Delivery Parent Key`,`Part Units Per Package`,
                `Supplier Delivery Units`, `Supplier Delivery Checked Units`,
                ifnull(`Supplier Delivery Placed Units`,0) AS placed,POTF.`Supplier Delivery Key`,`Supplier Delivery Public ID` FROM 
                `Purchase Order Transaction Fact` POTF LEFT JOIN 
                `Supplier Delivery Dimension` PO  ON (PO.`Supplier Delivery Key`=POTF.`Supplier Delivery Key`)  left join  
                `Supplier Part Dimension` SP on (POTF.`Supplier Part Key`=SP.`Supplier Part Key`) left join 
                `Part Dimension` Pa on (SP.`Supplier Part Part SKU`=Pa.`Part SKU`)
                WHERE POTF.`Supplier Part Key` IN (%s)  AND  POTF.`Supplier Delivery Key` IS NOT NULL AND `Supplier Delivery Transaction State` in ("InProcess","Dispatched","Received","Checked")
                

                
                 ', join($supplier_parts, ',')
            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {


                    // print_r($row);


                    if ($row['Supplier Delivery Checked Units'] > 0 or $row['Supplier Delivery Checked Units'] == '') {


                        if ($row['Supplier Delivery Checked Units'] == '') {
                            $raw_units_qty = $row['Supplier Delivery Units'];
                        } else {


                            $raw_units_qty = $row['Supplier Delivery Checked Units'] - $row['placed'];;
                        }


                        if ($raw_units_qty > 0) {

                            $_next_delivery_time = strtotime('tomorrow');
                            $raw_skos_qty        = $raw_units_qty / $row['Part Units Per Package'];


                            switch ($row['Supplier Delivery Transaction State']) {
                                case 'InProcess':
                                    $state = sprintf('%s', _('In Process'));
                                    break;
                                case 'Consolidated':
                                    $state = sprintf('%s', _('Consolidated'));
                                    break;
                                case 'Dispatched':
                                    $state = sprintf('%s', _('Dispatched'));
                                    break;
                                case 'Received':
                                    $state = sprintf('%s', _('Received'));
                                    break;
                                case 'Checked':
                                    $state = sprintf('%s', _('Checked'));
                                    break;


                                default:
                                    $state = $row['Supplier Delivery State'];
                                    break;
                            }

                            $number_non_draft_POs++;

                            $next_deliveries_data[] = array(
                                'type'            => 'delivery',
                                'qty'             => '+'.number($raw_skos_qty),
                                'raw_sko_qty'     => $raw_skos_qty,
                                'raw_units_qty'   => $raw_units_qty,
                                'date'            => '',
                                'formatted_link'  => sprintf(
                                    '<i class="fal fa-truck fa-fw" ></i> <i style="visibility: hidden" class="fal fa-truck fa-fw" ></i> <span class="link" onclick="change_view(\'%s/%d/delivery/%d\')"> %s</span>', strtolower($row['Supplier Delivery Parent']),
                                    $row['Supplier Delivery Parent Key'], $row['Supplier Delivery Key'], $row['Supplier Delivery Public ID']
                                ),
                                'link'            => sprintf('%s/%d/delivery/%d', strtolower($row['Supplier Delivery Parent']), $row['Supplier Delivery Parent Key'], $row['Supplier Delivery Key']),
                                'order_id'        => $row['Supplier Delivery Public ID'],
                                'formatted_state' => '<span class=" italic">'.$row['Supplier Delivery Transaction State'].'</span>',
                                'state'           => $state,

                                'po_key' => $row['Purchase Order Key']
                            );


                            if ($_next_delivery_time > $next_delivery_time) {
                                $next_delivery_time = $_next_delivery_time;
                            }
                        }
                    }
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            $sql = sprintf(
                'SELECT `Supplier Part Packages Per Carton`,POTF.`Purchase Order Transaction State`,`Purchase Order Submitted Units`,`Supplier Delivery Key` ,`Purchase Order Estimated Receiving Date`,`Purchase Order Public ID`,POTF.`Purchase Order Key` ,
                `Part Units Per Package`,`Purchase Order Ordering Units`,`Purchase Order Submitted Units`
        FROM `Purchase Order Transaction Fact` POTF LEFT JOIN `Purchase Order Dimension` PO  ON (PO.`Purchase Order Key`=POTF.`Purchase Order Key`)  
          left join  `Supplier Part Dimension` SP on (POTF.`Supplier Part Key`=SP.`Supplier Part Key`) left join 
                `Part Dimension` Pa on (SP.`Supplier Part Part SKU`=Pa.`Part SKU`)
        
        WHERE POTF.`Supplier Part Key`IN (%s) AND  POTF.`Supplier Delivery Key` IS NULL AND POTF.`Purchase Order Transaction State` NOT IN ("Placed","Cancelled") ', join($supplier_parts, ',')
            );

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {


                    if ($row['Purchase Order Transaction State'] == 'InProcess') {
                        $number_draft_POs++;

                        $raw_units_qty = $row['Purchase Order Ordering Units'];
                        $raw_skos_qty  = $raw_units_qty / $row['Part Units Per Package'];

                        $_next_delivery_time = 0;
                        $date                = '';
                        $formatted_state     = '<span class="very_discreet italic">'._('Draft').'</span>';
                        $link                = sprintf(
                            '<i class="fal fa-fw  fa-clipboard" ></i> <i class="fal fa-fw  fa-seedling" title="%s" ></i> <span class="link discreet" onclick="change_view(\'suppliers/order/%d\')"> %s</span>', _('In process'), $row['Purchase Order Key'],
                            $row['Purchase Order Public ID']
                        );
                        $qty                 = '<span class="very_discreet italic">+'.number($raw_skos_qty).'</span>';

                    } else {
                        $number_non_draft_POs++;
                        $raw_units_qty = $row['Purchase Order Submitted Units'];
                        $raw_skos_qty  = $raw_units_qty / $row['Part Units Per Package'];

                        $_next_delivery_time = strtotime($row['Purchase Order Estimated Receiving Date'].' +0:00');
                        $date                = strftime("%e %b %y", strtotime($row['Purchase Order Estimated Receiving Date'].' +0:00'));

                        $formatted_state = strftime("%e %b %y", strtotime($row['Purchase Order Estimated Receiving Date'].' +0:00'));
                        $link            = sprintf(
                            '<i class="fal fa-fw  fa-clipboard" ></i> <i class="fal fa-fw  fa-paper-plane" title="%s" ></i> <span class="link" onclick="change_view(\'suppliers/order/%d\')">  %s</span>', _('Submitted'), $row['Purchase Order Key'],
                            $row['Purchase Order Public ID']
                        );
                        $qty             = '+'.number($raw_skos_qty);
                    }


                    $next_deliveries_data[] = array(
                        'type'          => 'po',
                        'qty'           => $qty,
                        'raw_sko_qty'   => $raw_skos_qty,
                        'raw_units_qty' => $raw_units_qty,

                        'date'            => $date,
                        'formatted_state' => $formatted_state,

                        'formatted_link' => $link,
                        'link'           => sprintf('suppliers/order/%d', $row['Purchase Order Key']),
                        'order_id'       => $row['Purchase Order Public ID'],
                        'state'          => $row['Purchase Order Transaction State'],
                        'po_key'         => $row['Purchase Order Key']
                    );


                    if ($_next_delivery_time > $next_delivery_time) {
                        $next_delivery_time = $_next_delivery_time;
                    }

                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }

        }


        return array(
            'deliveries'           => $next_deliveries_data,
            'next_delivery_time'   => (!$next_delivery_time ? '' : $next_delivery_time),
            'number_non_draft_POs' => $number_non_draft_POs,
            'number_draft_POs'     => $number_draft_POs

        );

    }

    function get_supplier_parts($scope = 'keys') {


        if ($scope == 'objects') {
            include_once 'class.SupplierPart.php';
        }

        $sql = sprintf(
            'SELECT `Supplier Part Key` FROM `Supplier Part Dimension` WHERE `Supplier Part Part SKU`=%d ', $this->id
        );

        $supplier_parts = array();

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($scope == 'objects') {
                    $object = new SupplierPart('id', $row['Supplier Part Key'], false, $this->db);
                    $object->load_supplier();
                    $supplier_parts[$row['Supplier Part Key']] = $object;
                } else {
                    $supplier_parts[$row['Supplier Part Key']] = $row['Supplier Part Key'];
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        return $supplier_parts;
    }

    function get_products($scope = 'keys') {


        if ($scope == 'data' or $scope == 'products_data' ) {
            $fields = '*';
        } else {
            $fields = '`Product Part Product ID`';
        }

        $sql = sprintf(
            'SELECT %s FROM `Product Part Bridge` WHERE `Product Part Part SKU`=%d ', $fields, $this->id
        );

        $products = array();

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($scope == 'objects') {
                    $products[$row['Product Part Product ID']] = get_object('Product', $row['Product Part Product ID']);
                } elseif ($scope == 'data') {
                    $products[$row['Product Part Product ID']] = $row;
                } elseif ($scope == 'products_data') {
                    $product= get_object('Product', $row['Product Part Product ID']);
                    $store= get_object('Store', $product->get('Product Store Key'));



                    $products[$row['Product Part Product ID']] = array(
                        'store_key'=>$store->id,
                        'store_code'=>$store->get('Code'),
                        'store_key'=>$store->get('Name'),
                        'product_id'=>$product->id,
                        'units_per_case'=>$product->get('Product Units Per Case'),
                        'name'=>$product->get('Product Name'),
                        'status'=>$product->get('Product Status'),
                        'web_status'=>$product->get('Product Web State'),
                        'parts_per_product'=>$row['Product Part Ratio']

                    );
                } else {
                    $products[$row['Product Part Product ID']] = $row['Product Part Product ID'];
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        return $products;
    }

    function calculate_forecast_data() {

        $required_last_quarter = 0;

        $sql = sprintf('SELECT sum(`Required`) AS required FROM `Inventory Transaction Fact` WHERE `Part SKU`=%d AND `Date`>= ( NOW() - INTERVAL 1 QUARTER ) ', $this->id);

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $required_last_quarter = $row['required'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $forecast_metadata = json_encode(
            array(
                'method'                => 'Simple',
                'Required last quarter' => $required_last_quarter
            )
        );


        $this->update_field('Part Forecast Metadata', $forecast_metadata, 'no_history');

    }

    function update_products_web_status() {

        $products            = 0;
        $products_web_status = '';
        //'Offline','No Products','Online','Out of Stock'

        foreach ($this->get_products('objects') as $product) {
            if (!($product->get('Product Status') == 'Discontinued' or $product->get('Product Web State') == 'Discontinued')) {

                //'For Sale','Out of Stock','Discontinued','Offline'

                if ($product->get('Product Web State') == 'For Sale') {
                    $products_web_status = 'Online';
                    break;
                } elseif ($product->get('Product Web State') == 'Out of Stock') {
                    $products_web_status = 'Out of Stock';
                } elseif ($product->get('Product Web State') == 'Offline') {

                    if ($products_web_status == '') {
                        $products_web_status = 'Offline';
                    }

                }


                $products++;
            }
        }

        if ($products_web_status == '') {
            $products_web_status = 'No Products';
        }

        //print $this->get('Reference').' '.$products_web_status."\n";

        $this->update(
            array(
                'Part Products Web Status' => $products_web_status
            ), 'no_history'
        );


    }

    function get($key = '', $args = false) {

        $account = new Account($this->db);

        list($got, $result) = $this->get_asset_common($key, $args);
        if ($got) {
            return $result;
        }

        if (!$this->id) {
            return;
        }


        switch ($key) {

            case 'Unknown Location Stock':

                if ($this->data['Part Unknown Location Stock'] > 0) {
                    return '<span class="error">'.number(-$this->data['Part Unknown Location Stock']).'</span>';
                } elseif ($this->data['Part Unknown Location Stock'] < 0) {
                    return '<span class="success">+'.number(-$this->data['Part Unknown Location Stock']).'</span>';

                } else {
                    return '<span class="discreet">'.number($this->data['Part Unknown Location Stock']).'</span>';

                }


                break;

            case 'SKOs per Carton':


                $suppliers = '';

                foreach ($this->get_supplier_parts('objects') as $supplier_part) {
                    $supplier_part->load_supplier();
                    $suppliers .= $supplier_part->supplier->get('Code').' '.$supplier_part->get('Supplier Part Packages Per Carton').' '._("Supplier's part ordering SKOs per carton").', ';

                }
                $suppliers = preg_replace('/\, $/', '', $suppliers);

                if ($this->data['Part SKOs per Carton'] == '') {
                    return '<span class="error">'._('Not set')."</span> <span class='italic very_discreet'>(".$suppliers.')</span>';

                } else {
                    return number($this->data['Part SKOs per Carton'])." <span class='italic very_discreet'>(".$suppliers.')</span>';

                }


                break;
            case 'Products Numbers':

                return number($this->data['Part Number Active Products']).",<span class=' very_discreet'>".number($this->data['Part Number No Active Products']).'</span>';

                break;

            case 'Stock Status Icon':

                if ($this->data['Part Status'] == 'In Process') {
                    return '';
                }

                switch ($this->data[$this->table_name.' Stock Status']) {
                    case 'Surplus':
                        $stock_status = '<i class="fa  fa-plus-circle fa-fw"  title="'._('Surplus stock').'"></i>';
                        break;
                    case 'Optimal':
                        $stock_status = '<i class="fa fa-check-circle fa-fw"  title="'._('Optimal stock').'"></i>';
                        break;
                    case 'Low':
                        $stock_status = '<i class="fa fa-minus-circle fa-fw"  title="'._('Low stock').'"></i>';
                        break;
                    case 'Critical':
                        $stock_status = '<i class="fa error fa-minus-circle fa-fw"   title="'._('Critical stock').'"></i>';
                        break;
                    case 'Out_Of_Stock':
                    case 'Out_of_Stock':
                        $stock_status = '<i class="fa error fa-ban fa-fw"   title="'._('Out of stock').'"></i>';
                        break;
                    case 'Error':
                        $stock_status = '<i class="fa fa-question-circle fa-fw"   title="'._('Error').'"></i>';
                        break;
                    default:
                        $stock_status = $this->data[$this->table_name.' Stock Status'];
                        break;
                }

                return $stock_status;
                break;
            case 'Part Family Category Code':

                if ($this->data['Part Family Category Key'] == '') {
                    return '';
                }

                include_once 'class.Category.php';

                $category = new Category(
                    $this->data['Part Family Category Key']
                );

                if ($category->id) {
                    return $category->get('Code');
                } else {
                    return '';
                }


                break;
            case 'Products Web Status':

                if ($this->data['Part Status'] == 'Not In Use') {

                    if ($this->data['Part Products Web Status'] == 'Online') {
                        return '<i class="fa fa-exclamation-circle error" ></i> '._('Online');
                    } elseif ($this->data['Part Products Web Status'] == 'Out of Stock') {
                        return '<i class="fa fa-exclamation-circle warning" ></i> '._('Out of stock');
                    }


                } else {


                    if ($this->data['Part Products Web Status'] == 'Offline') {
                        return '<span class="warning"><i class="fa fa-exclamation-circle" ></i> '._('Offline').'</span>';
                    } elseif ($this->data['Part Products Web Status'] == 'No Products') {
                        return _('No products associated');
                    } elseif ($this->data['Part Products Web Status'] == 'Online') {

                        if ($this->data['Part Stock Status'] == 'Out_Of_Stock' or $this->data['Part Stock Status'] == 'Error') {
                            return '<span class="error"><i class="fa fa-exclamation-circle" ></i> '._('Online').'</span>';

                        } else {

                            return _('Online');
                        }
                    } elseif ($this->data['Part Products Web Status'] == 'Out of Stock') {

                        if ($this->data['Part Status'] == 'In Process') {
                            return '';
                        } else {
                            return _('Out of stock');
                        }


                    } else {
                        return $this->data['Part Products Web Status'];
                    }
                }

                break;
            case 'Status':
                if ($this->data['Part Status'] == 'In Use') {
                    return _('Active');
                } elseif ($this->data['Part Status'] == 'Discontinuing') {
                    return _('Discontinuing');
                } elseif ($this->data['Part Status'] == 'Not In Use') {
                    return _('Discontinued');
                } elseif ($this->data['Part Status'] == 'In Process') {
                    return _('In process');
                } else {
                    return $this->data['Part Status'];
                }
                break;

            case 'Cost in Warehouse only':


                if ($this->data['Part Cost in Warehouse'] == '') {
                    $sko_cost = _('SKO stock value no set up yet');
                } else {
                    $sko_cost = sprintf('<span title="%s">%s/SKO</span>', _('SKO stock value'), money($this->data['Part Cost in Warehouse'], $account->get('Account Currency')));

                }


                return $sko_cost;
                break;

            case 'Cost in Warehouse':


                if ($this->data['Part Cost in Warehouse'] == '') {
                    $sko_cost = _('SKO stock value no set up yet');
                } else {
                    $sko_cost = sprintf('<span title="%s">%s/SKO</span>', _('SKO stock value'), money($this->data['Part Cost in Warehouse'], $account->get('Account Currency')));

                }


                $total_value = $this->data['Part Cost in Warehouse'] * $this->get('Part Current On Hand Stock');

                if ($total_value > 0) {
                    $total_value = sprintf('<span class="hide_in_history" >%s %s</span>', money($total_value, $account->get('Account Currency')), _('total stock value'));
                } else {
                    $total_value = '';
                }


                return $sko_cost.' <span class="discreet" style="margin-left:10px">'.$total_value.'</span>';
                break;
            case 'SKO Cost in Warehouse - Price':


                if ($this->data['Part Cost in Warehouse'] == '') {
                    $sko_cost = '<i class="fa fa-exclamation-circle error" ></i> '._('SKO stock value no set up yet');
                } else {
                    $sko_cost = sprintf(
                        _('SKO stock value %s'), money($this->data['Part Cost in Warehouse'], $account->get('Account Currency'))

                    );
                }


                $sko_recomended_price = sprintf(
                    _('recommended SKO commercial value: %s'),
                    ($this->data['Part Unit Price'] > 0 ? money($this->data['Part Unit Price'] * $this->data['Part Units Per Package'], $account->get('Account Currency')) : '<span class="italic discreet">'._('not set').'</span>')

                );


                if ($this->data['Part Units Per Package'] != 0 and is_numeric($this->data['Part Units Per Package']) and $this->data['Part Cost in Warehouse'] != '') {

                    $unit_margin = $this->data['Part Unit Price'] - ($this->data['Part Cost in Warehouse'] / $this->data['Part Units Per Package']);

                    $sko_recomended_price .= sprintf(
                        ' (<span class="'.($unit_margin < 0 ? 'error' : '').'">%s '._('margin').'</span>)', percentage($unit_margin, $this->data['Part Unit Price'])
                    );
                }


                return $sko_cost.' <span class="discreet" style="margin-left:10px">'.$sko_recomended_price.'</span>';
                break;


            case 'Unit Price':
                if ($this->data['Part Unit Price'] == '') {
                    return '';
                }
                include_once 'utils/natural_language.php';
                $unit_price = money(
                    $this->data['Part Unit Price'], $account->get('Account Currency')
                );

                if ($this->data['Part Cost in Warehouse'] != '') {

                    $cost = $this->data['Part Cost in Warehouse'];
                    $formatted_cost=sprintf(_('Current stock value per unit in warehouse %s'),money($cost/ $this->data['Part Units Per Package'],$account->get('Currency Code')));

                } else {
                    $cost = $this->data['Part Cost'];
                    $formatted_cost=sprintf(_('Supplier unit cost %s'),money($cost/ $this->data['Part Units Per Package'],$account->get('Currency Code')));
                }


                $price_other_info = '';
                if ($this->data['Part Units Per Package'] != 1 and is_numeric(
                        $this->data['Part Units Per Package']
                    )) {
                    $price_other_info = '('.money(
                            $this->data['Part Unit Price'] * $this->data['Part Units Per Package'], $account->get('Account Currency')
                        ).' '._('per SKO').'), ';
                }


                if ($this->data['Part Units Per Package'] != 0 and is_numeric($this->data['Part Units Per Package'])) {

                    $unit_margin = $this->data['Part Unit Price'] - ($cost / $this->data['Part Units Per Package']);

                    $price_other_info .= sprintf(
                        '<span title="%s" class="'.($unit_margin < 0 ? 'error' : '').'">'._('margin %s').'</span>',$formatted_cost,percentage($unit_margin, $this->data['Part Unit Price'])
                    );
                }

                $price_other_info = preg_replace(
                    '/^, /', '', $price_other_info
                );
                if ($price_other_info != '') {
                    $unit_price .= ' <span class="discreet">'.$price_other_info.'</span>';
                }

                return $unit_price;
                break;
            case 'Unit RRP':
                if ($this->data['Part Unit RRP'] == '') {
                    return '';
                }

                include_once 'utils/natural_language.php';
                $rrp = money(
                    $this->data['Part Unit RRP'], $account->get('Account Currency')
                );


                $unit_margin    = $this->data['Part Unit RRP'] - $this->data['Part Unit Price'];
                $rrp_other_info = sprintf(
                    _('margin %s'), percentage($unit_margin, $this->data['Part Unit RRP'])
                );


                $rrp_other_info = preg_replace('/^, /', '', $rrp_other_info);
                if ($rrp_other_info != '') {
                    $rrp .= ' <span class="'.($unit_margin < 0 ? 'error' : '').'  discreet">'.$rrp_other_info.'</span>';
                }

                return $rrp;
                break;
            case 'Barcode':

                if ($this->get('Part Barcode Number') == '') {
                    return '';
                }


                return '<i '.($this->get('Part Barcode Key') ? 'class="fa fa-barcode button" onClick="change_view(\'inventory/barcode/'.$this->get('Part Barcode Key').'\')"' : 'class="fa fa-barcode"').' ></i><span class="Part_Barcode_Number ">'.$this->get(
                        'Part Barcode Number'
                    ).'</span>';

                break;

            case 'Available Forecast':


                if ($this->data['Part Stock Status'] == 'Out_Of_Stock' or $this->data['Part Stock Status'] == 'Error') {
                    return '';
                }

                if (in_array(
                    $this->data['Part Products Web Status'], array(
                                                               'No Products',
                                                               'Offline',
                                                               'Out of Stock'
                                                           )
                )) {
                    return '';
                }


                include_once 'utils/natural_language.php';

                if ($this->data['Part On Demand'] == 'Yes') {

                    $available_forecast = '<span >'.sprintf(
                            _('%s in stock'),
                            '<span  title="'.sprintf("%s %s", number($this->data['Part Days Available Forecast'], 1), ngettext("day", "days", intval($this->data['Part Days Available Forecast']))).'">'.seconds_to_until($this->data['Part Days Available Forecast'] * 86400)
                            .'</span>'
                        ).'</span>';

                    if ($this->data['Part Fresh'] == 'No') {
                        $available_forecast .= ' <i class="fa fa-fighter-jet padding_left_5"  title="'._('On demand').'"></i>';
                    } else {
                        $available_forecast = ' <i class="far fa-lemon padding_left_5"  title="'._('On demand').'"></i>';
                    }
                } else {
                    $available_forecast = '<span >'.sprintf(
                            _('%s availability'), '<span  title="'.sprintf(
                                                    "%s %s", number($this->data['Part Days Available Forecast'], 1), ngettext(
                                                               "day", "days", intval($this->data['Part Days Available Forecast'])
                                                           )
                                                ).'">'.seconds_to_until($this->data['Part Days Available Forecast'] * 86400).'</span>'
                        ).'</span>';


                }


                return $available_forecast;
                break;

            case 'Origin Country Code':
                if ($this->data['Part Origin Country Code']) {
                    include_once 'class.Country.php';
                    $country = new Country(
                        'code', $this->data['Part Origin Country Code']
                    );

                    return '<img src="/art/flags/'.strtolower(
                            $country->get('Country 2 Alpha Code')
                        ).'.gif" title="'.$country->get('Country Code').'"> '._(
                            $country->get('Country Name')
                        );
                } else {
                    return '';
                }

                break;
            case 'Origin Country':
                if ($this->data['Part Origin Country Code']) {
                    include_once 'class.Country.php';
                    $country = new Country(
                        'code', $this->data['Part Origin Country Code']
                    );

                    return $country->get('Country Name');
                } else {
                    return '';
                }

                break;


            case 'Next Shipment':
                if ($this->data['Part Next Shipment Date'] == '') {
                    return '';
                } else {

                    $date = strftime("%a, %e %b %y", strtotime($this->data['Part Next Shipment Date'].' +0:00'));

                    if ($this->data['Part Next Shipment Object Key']) {

                        if ($this->data['Part Next Shipment Object'] == 'PurchaseOrder') {
                            $date = sprintf('<span class="button" onclick="change_view(\'suppliers/order/%d\')">%s</span>', $this->data['Part Next Shipment Object Key'], $date);
                        } elseif ($this->data['Part Next Shipment Object'] == 'SupplierDelivery') {
                            $date = sprintf('<span class="button" onclick="change_view(\'suppliers/delivery/%d\')">%s</span>', $this->data['Part Next Shipment Object Key'], $date);

                        }

                    } else {

                    }


                    return $date;


                }
                break;

            case('Current Stock Available'):

                return number(
                    $this->data['Part Current On Hand Stock'] - $this->data['Part Current Stock In Process'] - $this->data['Part Current Stock Ordered Paid'], 6
                );


                break;

            case('Current On Hand Stock'):
            case('Current Stock'):
            case ('Current Stock Picked'):
            case ('Current Stock In Process'):
            case ('Current Stock Ordered Paid'):
                return number($this->data['Part '.$key], 6);


                break;


            case('Valid From'):
            case('Valid From Datetime'):

                return strftime(
                    "%a %e %b %Y %H:%M %Z", strtotime($this->data['Part Valid From'].' +0:00')
                );
                break;
            case('Valid To'):
                return strftime(
                    "%a %e %b %Y %H:%M %Z", strtotime($this->data['Part Valid To'].' +0:00')
                );
                break;
            case 'Package Description Image':


                if (!$this->data['Part SKO Image Key']) {
                    $image = '/art/nopic.png';

                } else {
                    $image = sprintf('<img src="/image_root.php?id=%d&size=thumbnail"> ', $this->data['Part SKO Image Key']);

                }


                return $image;

                break;

            case 'Acc To Day Updated':
            case 'Acc Ongoing Intervals Updated':
            case 'Acc Previous Intervals Updated':

                if ($this->data['Part '.$key] == '') {
                    $value = '';
                } else {

                    $value = strftime("%a %e %b %Y %H:%M:%S %Z", strtotime($this->data['Part '.$key].' +0:00'));

                }

                return $value;
                break;
            case 'Commercial Value':
                return money($this->data['Part '.$key], $account->get('Currency Code'));
                break;
            case 'Margin':
                return percentage($this->data['Part '.$key], 1);
                break;
            case 'Cost':
                return money($this->data['Part '.$key], $account->get('Currency Code'));
                break;


            case 'Barcode Number Error':

                if ($this->data['Part Barcode Number Error'] == '') {
                    return '';
                }


                switch ($this->data['Part Barcode Number Error']) {

                    case 'Duplicated':
                        $error = '<span class="barcode_number_error error">'._('Duplicated').'</span>';
                        break;
                    case 'Size':
                        $error = '<span class="barcode_number_error error">'._('Barcode should be 13 digits').'</span>';
                        break;
                    case 'Short_Duplicated':
                        $error = '<span class="barcode_number_error error">'._('Check digit missing, will duplicate').'</span>';
                        break;
                    case 'Checksum_missing':
                        $error = '<span class="barcode_number_error error">'._('Check digit missing').'</span>';
                        break;
                    case 'Checksum':
                        $error = '<span class="barcode_number_error error">'._('Invalid check digit').'</span>';
                        break;
                    default:
                        $error = '<span class="barcode_number_error error">'.$this->data['Part Barcode Number Error'].'</span>';
                }


                return '<i class="fa fa-exclamation-circle error" ></i> '.$error;

                break;

            case 'Barcode Number Error with Duplicates Links':

                $error = $this->get('Barcode Number Error');
                if ($error == '') {
                    return '';
                }


                $duplicates = '';


                if (strlen($this->data['Part Barcode Number']) >= 12 and strlen($this->data['Part Barcode Number']) < 14) {


                    $sql = sprintf(
                        'SELECT `Part SKU`,`Part Reference` FROM `Part Dimension` WHERE `Part Barcode Number` LIKE "%s%%" AND `Part SKU`!=%d', addslashes($this->data['Part Barcode Number']), $this->id
                    );

                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {
                            $duplicates .= sprintf('<span class=" " style="cursor: pointer" onclick="change_view(\'part/%d\')">%s</span>, ', $row['Part SKU'], $row['Part Reference']);
                        }

                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        print "$sql\n";
                        exit;
                    }
                    if ($duplicates != '') {
                        $duplicates = ' ('.preg_replace('/\, $/', ')', $duplicates);
                    }


                }

                return $error.$duplicates;
                break;
            case 'Next Deliveries Data':

                if ($this->data['Part Next Deliveries Data'] == '') {
                    return array();
                } else {
                    return json_decode($this->data['Part Next Deliveries Data'], true);
                }
                break;
            default:

                if (preg_match('/No Supplied$/', $key)) {

                    $_key = preg_replace('/ No Supplied$/', '', $key);
                    if (preg_match('/^Part /', $key)) {
                        return $this->data["$_key Required"] - $this->data["$_key Provided"];

                    } else {
                        return number(
                            $this->data["Part $_key Required"] - $this->data["Part $_key Provided"]
                        );
                    }

                }


                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit)$/', $key
                )) {

                    $amount = 'Part '.$key;

                    return money(
                        $this->data[$amount], $account->get('Account Currency')
                    );
                }
                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Minify$/', $key
                )) {

                    $field = 'Part '.preg_replace('/ Minify$/', '', $key);

                    $suffix          = '';
                    $fraction_digits = 'NO_FRACTION_DIGITS';
                    if ($this->data[$field] >= 10000) {
                        $suffix  = 'K';
                        $_amount = $this->data[$field] / 1000;
                    } elseif ($this->data[$field] > 100) {
                        $fraction_digits = 'SINGLE_FRACTION_DIGITS';
                        $suffix          = 'K';
                        $_amount         = $this->data[$field] / 1000;
                    } else {
                        $_amount = $this->data[$field];
                    }

                    $amount = money(
                            $_amount, $account->get('Account Currency'), $locale = false, $fraction_digits
                        ).$suffix;

                    return $amount;
                }
                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Soft Minify$/', $key
                )) {

                    $field = 'Part '.preg_replace('/ Soft Minify$/', '', $key);


                    $suffix          = '';
                    $fraction_digits = 'NO_FRACTION_DIGITS';
                    $_amount         = $this->data[$field];

                    $amount = money(
                            $_amount, $account->get('Account Currency'), $locale = false, $fraction_digits
                        ).$suffix;

                    return $amount;
                }

                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|Year To|Quarter To|Month To|Today|Week To).*(Margin|GMROI)$/', $key
                )) {

                    $amount = 'Part '.$key;

                    return percentage($this->data[$amount], 1);
                }
                if (preg_match(
                        '/^(Last|Yesterday|Total|1|10|6|3|Year To|Quarter To|Month To|Today|Week To).*(Given|Lost|Required|Sold|Dispatched|Broken|Acquired)$/', $key
                    ) or $key == 'Current Stock') {

                    $amount = 'Part '.$key;

                    return number($this->data[$amount]);
                }
                if (preg_match(
                        '/^(Last|Yesterday|Total|1|10|6|3|2|4|Year To|Quarter To|Month To|Today|Week To).*(Given|Lost|Required|Sold|Dispatched|Broken|Acquired) Minify$/', $key
                    ) or $key == 'Current Stock') {

                    $field = 'Part '.preg_replace('/ Minify$/', '', $key);

                    $suffix          = '';
                    $fraction_digits = 0;
                    if ($this->data[$field] >= 10000) {
                        $suffix  = 'K';
                        $_number = $this->data[$field] / 1000;
                    } elseif ($this->data[$field] > 100) {
                        $fraction_digits = 1;
                        $suffix          = 'K';
                        $_number         = $this->data[$field] / 1000;
                    } else {
                        $_number = $this->data[$field];
                    }

                    return number($_number, $fraction_digits).$suffix;
                }
                if (preg_match(
                        '/^(Last|Yesterday|Total|1|10|6|3|2|4|Year To|Quarter To|Month To|Today|Week To).*(Given|Lost|Required|Sold|Dispatched|Broken|Acquired) Soft Minify$/', $key
                    ) or $key == 'Current Stock') {

                    $field = 'Part '.preg_replace('/ Soft Minify$/', '', $key);

                    $suffix          = '';
                    $fraction_digits = 0;
                    $_number         = $this->data[$field];

                    return number($_number, $fraction_digits).$suffix;
                }


                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Part '.$key, $this->data)) {
                    return $this->data['Part '.$key];
                }

        }

        return false;
    }

    function validate_barcode() {

        $barcode = $this->data['Part Barcode Number'];

        $error = '';


        if ($barcode != '') {
            if (strlen($barcode) != (12 + 1)) {
                $error = 'Size';
                if (strlen($barcode) == 12) {
                    $error = 'Checksum_missing';
                    $sql   = sprintf(
                        'SELECT `Part SKU` FROM `Part Dimension` WHERE `Part Barcode Number` LIKE "%s%%" AND `Part SKU`!=%d', addslashes($barcode), $this->id
                    );

                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {

                            $error = 'Short_Duplicated';
                        }

                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        print "$sql\n";
                        exit;
                    }

                }


            } else {
                $digits = substr($barcode, 0, 12);

                $digits         = (string)$digits;
                $even_sum       = $digits{1} + $digits{3} + $digits{5} + $digits{7} + $digits{9} + $digits{11};
                $even_sum_three = $even_sum * 3;
                $odd_sum        = $digits{0} + $digits{2} + $digits{4} + $digits{6} + $digits{8} + $digits{10};
                $total_sum      = $even_sum_three + $odd_sum;
                $next_ten       = (ceil($total_sum / 10)) * 10;
                $check_digit    = $next_ten - $total_sum;

                if ($check_digit != substr($barcode, -1)) {
                    $error = 'Checksum';
                } else {
                    $sql = sprintf(
                        'SELECT `Part SKU` FROM `Part Dimension` WHERE `Part Barcode Number`=%s AND `Part SKU`!=%d', prepare_mysql($barcode), $this->id
                    );

                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {
                            $part = get_object('Part', $row['Part SKU']);
                            $part->fast_update(array('Part Barcode Number Error' => 'Duplicated'));
                            $error = 'Duplicated';
                        }

                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        print "$sql\n";
                        exit;
                    }


                }


            }

        }
        $this->fast_update(array('Part Barcode Number Error' => $error));

        global $account;
        $account->update_parts_data();

    }

    function update_next_shipment() {


        return;

        include_once 'class.PurchaseOrder.php';

        $next_delivery_time   = 0;
        $next_delivery_po_key = 0;

        $sql = sprintf(
            'SELECT `Purchase Order Key` FROM  `Supplier Part Dimension` SPD  LEFT JOIN   `Purchase Order Transaction Fact` POTF  ON (SPD.`Supplier Part Key`=POTF.`Supplier Part Key`)  WHERE `Purchase Order Transaction State` IN ("Submitted","Inputted","Dispatched") AND `Supplier Part Part SKU`=%d ',
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $purchase_order        = new PurchaseOrder($row['Purchase Order Key']);
                $_next_delivery_time   = $purchase_order->get('Estimated Receiving Datetime');
                $_next_delivery_po_key = $purchase_order->id;


                if ($_next_delivery_time != '' and strtotime($_next_delivery_time) > gmdate('U')) {


                    if (!$next_delivery_time or strtotime($_next_delivery_time.' +0:00') < $next_delivery_time) {
                        $next_delivery_time   = strtotime($_next_delivery_time.' +0:00');
                        $next_delivery_po_key = $_next_delivery_po_key;

                    }
                }

            }


            $this->update_field_switcher('Part Next Shipment Date', (!$next_delivery_time ? '' : gmdate('Y-m-d H:i:s', $next_delivery_time)), 'no_history');
            $this->update_field_switcher('Part Next Shipment Object', 'PurchaseOrder', 'no_history');
            $this->update_field_switcher('Part Next Shipment Object Key', (!$next_delivery_po_key ? '' : $next_delivery_po_key), 'no_history');


            foreach ($this->get_products('objects') as $product) {
                $product->editor = $this->editor;
                $product->update_next_shipment();
            }


        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {

        global $account;

        if ($this->update_asset_field_switcher(
            $field, $value, $options, $metadata
        )) {
            return;
        }

        switch ($field) {


            case 'Part Cost':

                $this->update_cost();


                break;


            case 'Part Cost in Warehouse':

                //$old_value = $this->get('Part Cost in Warehouse');
                //print 'xxxx';
                $this->update_field($field, $value, $options);
                //  print 'yyyy';

                $this->update_margin();


                foreach ($this->get_locations('part_location_object') as $part_location) {
                    $part_location->update_stock_value();
                }

                foreach ($this->get_products('objects') as $product) {
                    $product->editor = $this->editor;
                    $product->update_cost();
                }


                $hide = array();
                $show = array();

                if ($value == '') {
                    $hide[] = 'Part_Cost_in_Warehouse_info_set_up';
                    $show[] = 'Part_Cost_in_Warehouse_info_not_set_up';
                } else {
                    $show[] = 'Part_Cost_in_Warehouse_info_set_up';
                    $hide[] = 'Part_Cost_in_Warehouse_info_not_set_up';
                }

                $this->update_metadata = array(
                    'class_html' => array(
                        'Webpage_State_Icon' => $this->get('State Icon'),


                    ),
                    'hide'       => $hide,
                    'show'       => $show


                );


                break;

            case 'Part Barcode Number':

                $old_value = $this->data['Part Barcode Number'];


                $this->update_field($field, $value, $options);
                $this->validate_barcode();

                $updated = $this->updated;


                $sql = sprintf(
                    'SELECT `Part SKU` FROM `Part Dimension` WHERE `Part Barcode Number`=%s AND `Part SKU`!=%d', prepare_mysql($old_value), $this->id
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {


                        $part = get_object('Part', $row['Part SKU']);
                        $part->validate_barcode();


                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                foreach ($this->get_products('objects') as $product) {

                    if (count($product->get_parts()) == 1) {
                        $product->editor = $this->editor;
                        $product->update(
                            array('Product Barcode Number' => $value), $options.' from_part'
                        );
                    }

                }


                $this->updated = $updated;


                if (file_exists('widgets/inventory_alerts.wget.php')) {
                    include_once('widgets/inventory_alerts.wget.php');
                    global $smarty;

                    if (is_object($smarty)) {


                        $_data = get_widget_data(

                            $account->get('Account Parts with Barcode Number Error'), $account->get('Account Parts with Barcode Number'), 0, 0
                        );


                        $smarty->assign('data', $_data);


                        $this->update_metadata = array('parts_with_barcode_errors' => $smarty->fetch('dashboard/inventory.parts_with_barcode_errors.dbard.tpl'));
                    }
                }


                break;
            case 'Part Barcode Key':


                $this->update_field($field, $value, $options);


                $updated = $this->updated;


                foreach ($this->get_products('objects') as $product) {

                    if (count($product->get_parts()) == 1) {
                        $product->editor = $this->editor;
                        $product->update(
                            array('Product Barcode Key' => $value), $options.' from_part'
                        );
                    }

                }


                $this->updated = $updated;


                break;


            case 'Part Unit Label':


                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _('Unit label missing');

                    return;
                }

                $this->update_field($field, $value, $options);

                break;


            case 'Part Package Description':
                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _('Outers (SKO) description');

                    return;
                }

                $this->update_field($field, $value, $options);

                break;

            case 'Part Reference':

                if ($value == '') {
                    $this->error = true;
                    $this->msg   = sprintf(_('Reference missing'));

                    return;
                }

                $sql = sprintf(
                    'SELECT count(*) AS num FROM `Part Dimension` WHERE `Part Reference`=%s AND `Part SKU`!=%d ', prepare_mysql($value), $this->id
                );


                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        if ($row['num'] > 0) {
                            $this->error = true;
                            $this->msg   = sprintf(
                                _('Duplicated reference (%s)'), $value
                            );

                            return;
                        }
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }
                $this->update_field($field, $value, $options);


                foreach ($this->get_products('objects') as $product) {
                    $product->update(array('Product Parts Data' => json_encode($product->get_parts_data())), 'no_history');

                }


                break;
            case 'Part Unit Price':

                /*
			if ($value==''   ) {
				$this->error=true;
				$this->msg=_('Unit recommended price missing');
				return;
			}
*/
                if ($value != '' and (!is_numeric($value) or $value < 0)) {
                    $this->error = true;
                    $this->msg   = sprintf(
                        _('Invalid unit recommended price (%s)'), $value
                    );

                    return;
                }
                $this->update_field('Part Unit Price', $value, $options);
                if ($this->data['Part Commercial Value'] == '') {
                    $this->update_margin();

                }


                $this->other_fields_updated = array(

                    'Part_Unit_RRP' => array(
                        'field'           => 'Part_Unit_RRP',
                        'render'          => true,
                        'value'           => $this->get('Part Unit RRP'),
                        'formatted_value' => $this->get('Unit RRP'),
                    ),

                );

                break;


            case 'Part Unit RRP':

                /*
			if ($value==''   ) {
				$this->error=true;
				$this->msg=_('Unit recommended price missing');
				return;
			}
*/
                if ($value != '' and (!is_numeric($value) or $value < 0)) {
                    $this->error = true;
                    $this->msg   = sprintf(
                        _('Invalid unit recommended RRP (%s)'), $value
                    );

                    return;
                }


                $this->update_field('Part Unit RRP', $value, $options);


                break;

            case 'Part Units Per Package':
                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _('Units per SKO missing');

                    return;
                }

                if (!is_numeric($value) or $value < 0) {
                    $this->error = true;
                    $this->msg   = sprintf(
                        _('Invalid units per SKO (%s)'), $value
                    );

                    return;
                }

                $this->update_field('Part Units Per Package', $value, $options);

                if (!preg_match('/skip_update_historic_object/', $options)) {
                    foreach (
                        $this->get_supplier_parts('objects') as $supplier_part
                    ) {
                        $supplier_part->update_historic_object();
                    }
                }

                if ($this->data['Part Commercial Value'] == '') {
                    $this->update_margin();

                }


                $this->other_fields_updated = array(
                    'Part_Unit_Price' => array(
                        'field'           => 'Part_Unit_Price',
                        'render'          => true,
                        'value'           => $this->get('Part Unit Price'),
                        'formatted_value' => $this->get('Unit Price'),
                    ),
                    'Part_Unit_RRP'   => array(
                        'field'           => 'Part_Unit_RRP',
                        'render'          => true,
                        'value'           => $this->get('Part Unit RRP'),
                        'formatted_value' => $this->get('Unit RRP'),
                    ),

                );

                break;

            case 'Part Family Code':
            case 'Part Family Category Code':
                $account = new Account($this->db);
                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _("Family's code missing");

                    return;
                }

                include_once 'class.Category.php';


                $root_category = new Category(
                    $account->get('Account Part Family Category Key')
                );
                if ($root_category->id) {
                    $root_category->editor = $this->editor;
                    $family                = $root_category->create_category(
                        array('Category Code' => $value)
                    );
                    if ($family->id) {

                        $this->update_field_switcher(
                            'Part Family Category Key', $family->id, $options
                        );


                    } else {
                        $this->error = true;
                        $this->msg   = _("Can't create family");

                        return;
                    }
                } else {
                    $this->error = true;
                    $this->msg   = _("Part's families not configured");

                    return;
                }


                break;
            case 'Part Family Category Key';

                $account = new Account($this->db);
                include_once 'class.Category.php';


                if ($value != '') {


                    $category = new Category($value);
                    if ($category->id and $category->get('Category Root Key') == $account->get('Account Part Family Category Key')) {
                        $category->associate_subject($this->id);
                    } else {
                        $this->error = true;
                        $this->msg   = 'wrong category';

                        return;

                    }

                } else {

                    if ($this->data['Part Family Category Key'] != '') {


                        $category = new Category(
                            $this->data['Part Family Category Key']
                        );

                        if ($category->id) {
                            $category->disassociate_subject($this->id);
                        }

                    }


                }
                $this->update_field(
                    'Part Family Category Key', $value, 'no_history'
                );


                $categories = '';
                foreach ($this->get_category_data() as $item) {
                    $categories .= sprintf(
                        '<li><span class="button" onclick="change_view(\'category/%d\')" title="%s">%s</span></li>', $item['category_key'], $item['label'], $item['code']

                    );

                }
                $this->update_metadata = array(
                    'class_html' => array(
                        'Categories' => $categories,

                    )
                );


                break;
            case 'Part Materials':
                include_once 'utils/parse_materials.php';


                $materials_to_update = array();
                $sql                 = sprintf(
                    'SELECT `Material Key` FROM `Part Material Bridge` WHERE `Part SKU`=%d', $this->id
                );
                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $materials_to_update[$row['Material Key']] = true;
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }


                if ($value == '') {
                    $materials = '';


                    $sql = sprintf(
                        "DELETE FROM `Part Material Bridge` WHERE `Part SKU`=%d ", $this->sku
                    );
                    $this->db->exec($sql);

                } else {

                    $materials_data = parse_materials($value, $this->editor);

                    // print_r($materials_data);

                    $sql = sprintf(
                        "DELETE FROM `Part Material Bridge` WHERE `Part SKU`=%d ", $this->sku
                    );

                    $this->db->exec($sql);

                    foreach ($materials_data as $material_data) {

                        if ($material_data['id'] > 0) {
                            $sql = sprintf(
                                "INSERT INTO `Part Material Bridge` (`Part SKU`, `Material Key`, `Ratio`, `May Contain`) VALUES (%d, %d, %s, %s) ", $this->sku, $material_data['id'], prepare_mysql($material_data['ratio']), prepare_mysql($material_data['may_contain'])

                            );
                            $this->db->exec($sql);

                            if (isset($materials_to_update[$material_data['id']])) {
                                $materials_to_update[$material_data['id']] = false;
                            } else {
                                $materials_to_update[$material_data['id']] = true;
                            }

                        }


                    }


                    $materials = json_encode($materials_data);
                }


                foreach ($materials_to_update as $material_key => $update) {
                    if ($update) {
                        $material = new Material($material_key);
                        $material->update_stats();

                    }
                }


                $this->update_field('Part Materials', $materials, $options);
                $updated = $this->updated;


                foreach ($this->get_products('objects') as $product) {

                    if (count($product->get_parts()) == 1) {
                        $product->editor = $this->editor;
                        $product->update(
                            array('Product Materials' => $value), $options.' from_part'
                        );
                    }

                }


                $this->updated = $updated;
                break;

            /*
                        case '':


                            foreach ($this->get_products('objects') as $product) {

                                if (count($product->get_parts()) == 1) {
                                    $product->editor = $this->editor;
                                    $product->update(
                                        array('Product Materials' => $value), $options.' from_part'
                                    );
                                }

                            }


                            break;
            */
            case 'Part Package Dimensions':
            case 'Part Unit Dimensions':


                include_once 'utils/parse_natural_language.php';

                $tag = preg_replace('/ Dimensions$/', '', $field);

                if ($value == '') {
                    $dim = '';
                    $vol = '';
                } else {
                    $dim = parse_dimensions($value);
                    if ($dim == '') {
                        $this->error = true;
                        $this->msg   = sprintf(
                            _("Dimensions can't be parsed (%s)"), $value
                        );

                        return;
                    }
                    $_tmp = json_decode($dim, true);
                    $vol  = $_tmp['vol'];
                }

                $this->update_field($tag.' Dimensions', $dim, $options);
                $updated = $this->updated;
                $this->update_field($tag.' Volume', $vol, 'no_history');
                //$this->update_linked_products($field, $value, $options, $metadata);

                if ($field == 'Part Unit Dimensions') {
                    foreach ($this->get_products('objects') as $product) {

                        if (count($product->get_parts()) == 1) {
                            $product->editor = $this->editor;
                            $product->update(
                                array('Product Unit Dimensions' => $value), $options.' from_part'
                            );
                        }

                    }
                }
                $this->updated = $updated;

                break;
            case 'Part Package Weight':
            case 'Part Unit Weight':

                if ($value != '' and (!is_numeric($value) or $value < 0)) {
                    $this->error = true;
                    $this->msg   = sprintf(_('Invalid weight (%s)'), $value);

                    return;
                }


                $tag  = preg_replace('/ Weight$/', '', $field);
                $tag2 = preg_replace('/^Part /', '', $tag);
                $tag3 = preg_replace('/ /', '_', $tag);

                $this->update_field($field, $value, $options);
                $updated                    = $this->updated;
                $this->other_fields_updated = array(
                    $tag3.'_Dimensions' => array(
                        'field'           => $tag3.'_Dimensions',
                        'render'          => true,
                        'value'           => $this->get($tag.' Dimensions'),
                        'formatted_value' => $this->get($tag2.' Dimensions'),


                    )
                );
                //$this->update_linked_products($field, $value, $options, $metadata);


                if ($field == 'Part Package Weight') {


                    $purchase_order_keys = array();
                    $sql                 = sprintf(
                        "SELECT `Purchase Order Transaction Fact Key`,`Purchase Order Key`,`Purchase Order Ordering Units`,`Supplier Part Packages Per Carton` FROM `Purchase Order Transaction Fact`POTF 
                            LEFT JOIN `Supplier Part Dimension` S ON (POTF.`Supplier Part Key`=S.`Supplier Part Key`)  
                            LEFT JOIN `Part Dimension` P ON (S.`Supplier Part Part SKU`=P.`Part SKU`)  

                            WHERE `Supplier Part Part SKU`=%d  AND `Purchase Order Weight` IS NULL AND `Purchase Order Transaction State` IN ('InProcess','Submitted')  ", $this->id
                    );
                    //print $sql;
                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {

                            //todo review if this is really necessary

                            $purchase_order_keys[$row['Purchase Order Key']] = $row['Purchase Order Key'];

                            if ($value != '') {
                                $sql = sprintf(
                                    'UPDATE `Purchase Order Transaction Fact` SET  `Purchase Order Weight`=%f WHERE `Purchase Order Transaction Fact Key`=%d',
                                    $this->get('Part Package Weight') * $row['Purchase Order Ordering Units'] / $row['Supplier Part Packages Per Carton'], $row['Purchase Order Transaction Fact Key']
                                );
                            } else {
                                $sql = sprintf(
                                    'UPDATE `Purchase Order Transaction Fact` SET  `Purchase Order Weight`=NULL WHERE `Purchase Order Transaction Fact Key`=%d', $row['Purchase Order Transaction Fact Key']
                                );
                            }


                            $this->db->exec($sql);
                        }

                        foreach ($purchase_order_keys as $purchase_order_key) {
                            $purchase_order = get_object('PurchaseOrder', $purchase_order_key);
                            $purchase_order->update_totals();
                        }

                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        exit;
                    }


                }

                if ($field == 'Part Unit Weight') {

                    foreach ($this->get_products('objects') as $product) {

                        if (count($product->get_parts()) == 1) {
                            $product->editor = $this->editor;
                            $product->update(
                                array(
                                    'Product Unit Weight' => $this->get(
                                        'Part Unit Weight'
                                    )
                                ), $options.' from_part'
                            );
                        }

                    }
                }

                $this->updated = $updated;
                break;
            case('Part Tariff Code'):

                if ($value == '') {
                    $tariff_code_valid = '';
                } else {
                    //include_once 'utils/validate_tariff_code.php';
                    //$tariff_code_valid = validate_tariff_code($value, $this->db);
                }


                $this->update_field($field, $value, $options);
                $updated = $this->updated;
                //$this->update_field('Part Tariff Code Valid', $tariff_code_valid, 'no_history');


                foreach ($this->get_products('objects') as $product) {

                    if (count($product->get_parts()) == 1) {
                        $product->editor = $this->editor;
                        $product->update(
                            array('Product Tariff Code' => $this->get('Part Tariff Code')), $options.' from_part'
                        );
                    }

                }

                //$this->update_linked_products($field, $value, $options, $metadata);
                $this->updated = $updated;

                break;
            case 'Part SKO Barcode':


                $sql = sprintf(
                    'SELECT count(*) AS num FROM `Part Dimension` WHERE `Part SKO Barcode`=%s AND `Part SKU`!=%d ', prepare_mysql($value), $this->id
                );

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        if ($row['num'] > 0) {
                            $this->error = true;
                            $this->msg   = sprintf(
                                _('Duplicated SKO barcode (%s)'), $value
                            );

                            return;
                        }
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }


                $this->update_field($field, $value, $options);

                $account->update_parts_data();

                if (file_exists('widgets/inventory_alerts.wget.php')) {
                    include_once('widgets/inventory_alerts.wget.php');
                    global $smarty;

                    if (is_object($smarty)) {


                        $all_active = $account->get('Account Active Parts Number') + $account->get('Account In Process Parts Number') + $account->get('Account Discontinuing Parts Number');

                        $data = get_widget_data(

                            $all_active - $account->get('Account Active Parts with SKO Barcode Number'), $all_active, 0, 0

                        );


                        $smarty->assign('data', $data);


                        $this->update_metadata = array('parts_with_no_sko_barcode' => $smarty->fetch('dashboard/inventory.parts_with_no_sko_barcode.dbard.tpl'));
                    }
                }
                break;


            case 'Part UN Number':
            case 'Part UN Class':
            case 'Part Packing Group':
            case 'Part Proper Shipping Name':
            case 'Part Hazard Indentification Number':
            case('Part CPNP Number'):
            case('Part Duty Rate'):


                if ($field == 'Part Duty Rate' and is_numeric($value)) {
                    $value = percentage($value, 1);
                }


                $this->update_field($field, $value, $options);
                $updated = $this->updated;
                //$this->update_linked_products($field, $value, $options, $metadata);


                foreach ($this->get_products('objects') as $product) {

                    if (count($product->get_parts()) == 1) {
                        $product->editor = $this->editor;

                        $product_field = preg_replace(
                            '/^Part /', 'Product ', $field
                        );

                        $product->update(
                            array($product_field => $this->get($field)), $options.' from_part'
                        );
                    }

                }


                $this->updated = $updated;
                break;

            case 'Part Origin Country Code':


                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _("Country of origin missing");

                    return;
                }

                include_once 'class.Country.php';
                $country = new Country('find', $value);
                if ($country->get('Country Code') == 'UNK') {
                    $this->error = true;
                    $this->msg   = sprintf(_("Country not found (%s)"), $value);

                    return;

                }


                $this->update_field(
                    $field, $country->get('Country Code'), $options
                );
                $updated = $this->updated;

                foreach ($this->get_products('objects') as $product) {

                    if (count($product->get_parts()) == 1) {
                        $product->editor = $this->editor;

                        $product_field = preg_replace(
                            '/^Part /', 'Product ', $field
                        );

                        $product->update(
                            array($product_field => $this->get($field)), $options.' from_part'
                        );
                    }

                }


                $this->updated = $updated;
                break;

            case('Part Status'):


                if (!in_array(
                    $value, array(
                              'In Use',
                              'Not In Use',
                              'Discontinuing',
                              'In Process'
                          )
                )) {
                    $this->error = true;
                    $this->msg   = _('Invalid part status').' ('.$value.')';

                    return;
                }

                /*
                                if ($this->get('Part Status') == 'In Process' and $value = 'In Use' and !($this->get('Part Current On Hand Stock') > 0)
                                ) {

                                    $this->error = true;
                                    $this->msg   = _("Part status can't be set to active until stock is set up");

                                    return;

                                }
                */

                if ($value == 'Not In Use') {
                    if ($this->get('Part Current On Hand Stock') > 0) {
                        $value = 'Discontinuing';
                    } elseif ($this->get('Part Current On Hand Stock') < 0) {

                    }
                }


                $this->update_status($value, $options);
                break;
            case('Part Available for Products Configuration'):
                $this->update_availability_for_products_configuration(
                    $value, $options
                );
                break;


            case 'Part Next Set Supplier Shipment':
                $this->update_set_next_supplier_shipment($value, $options);
                break;
            case 'Part SKOs per Carton skip update supplier part':
                $this->update_field('Part SKOs per Carton', $value, $options);


            case 'Part SKOs per Carton':
                $this->update_field($field, $value, $options);

                //foreach ($this->get_supplier_parts('objects') as $supplier_part) {
                //    $supplier_part->editor = $this->editor;
                //    $supplier_part->update(array('Supplier Part Packages Per Carton skip update part' => $value), $options);
                //}

                break;

            default:
                $base_data = $this->base_data();


                if (array_key_exists($field, $base_data)) {
                    //print "$field $value  ".$this->data[$field]." \n";
                    if ($value != $this->data[$field]) {

                        if ($field == 'Part General Description' or $field == 'Part Health And Safety') {
                            $options .= ' nohistory';
                        }
                        $this->update_field($field, $value, $options);


                    }
                } elseif (array_key_exists($field, $this->base_data('Part Data'))) {
                    $this->update_table_field($field, $value, $options, 'Part Data', 'Part Data', $this->id);
                } elseif (preg_match('/^custom_field_part/i', $field)) {
                    $this->update_field($field, $value, $options);
                }

        }


    }

    function update_cost() {

        $account = new Account($this->db);

        $supplier_parts = $this->get_supplier_parts('objects');

        $cost_available    = array();
        $cost_no_available = array();
        $cost_discontinued = array();


        foreach ($supplier_parts as $supplier_part) {


            if ($supplier_part->get('Supplier Part Currency Code') != $account->get('Account Currency')) {
                include_once 'utils/currency_functions.php';
                $exchange = currency_conversion(
                    $this->db, $account->get('Account Currency'), $supplier_part->get('Supplier Part Currency Code'), '- 1 hour'
                );

            } else {
                $exchange = 1;
            }

            $_cost = ($supplier_part->get('Supplier Part Unit Cost') + $supplier_part->get('Supplier Part Unit Extra Cost')) / $exchange;


            if ($supplier_part->get('Supplier Part Status') == 'Available') {
                $cost_available[] = $_cost;

            } elseif ($supplier_part->get('Supplier Part Status') == 'NoAvailable') {
                $cost_no_available[] = $_cost;

            } elseif ($supplier_part->get('Supplier Part Status') == 'Discontinued') {
                $cost_discontinued[] = $_cost;

            }


        }


        $cost     = 0;
        $cost_set = false;


        if (count($cost_available) > 0) {

            $cost     = array_sum($cost_available) / count($cost_available);
            $cost_set = true;
        }

        if (!$cost_set and count($cost_no_available) > 0) {
            $cost     = array_sum($cost_no_available) / count(
                    $cost_no_available
                );
            $cost_set = true;
        }

        if (!$cost_set and count($cost_discontinued) > 0) {
            $cost     = array_sum($cost_discontinued) / count(
                    $cost_discontinued
                );
            $cost_set = true;
        }


        if ($cost_set) {
            $cost = $cost * $this->data['Part Units Per Package'];
        }
        $this->update_field('Part Number Supplier Parts', count($supplier_parts), 'no_history');


        $this->update_field('Part Cost', $cost, 'no_history');


        foreach ($this->get_products('objects') as $product) {
            $product->editor = $this->editor;
            $product->update_cost();
        }


        $this->update_margin();


    }

    function update_margin() {


        if ($this->data['Part Cost in Warehouse'] == '') {
            $cost = $this->data['Part Cost'];
        } else {
            $cost = $this->data['Part Cost in Warehouse'];
        }


        if ($this->data['Part Commercial Value'] == '') {

            $selling_price = $this->data['Part Unit Price'] * $this->data['Part Units Per Package'];


        } else {
            $selling_price = $this->data['Part Commercial Value'];
        }

        if ($selling_price == 0) {
            $margin = 0;
        } else {
            $margin = ($selling_price - $cost) / $selling_price;
        }

        $this->update_field_switcher('Part Margin', $margin, 'no_history');


    }

    function get_locations($scope = 'keys') {


        if ($scope == 'objects') {
            include_once 'class.Location.php';
        } elseif ($scope == 'part_location_object') {
            include_once 'class.PartLocation.php';
        }

        $sql = sprintf(
            "SELECT PL.`Location Key`,`Location Code`,`Quantity On Hand`,`Part Location Note`,`Location Warehouse Key`,`Part SKU`,`Minimum Quantity`,`Maximum Quantity`,`Moving Quantity`,`Can Pick`, datediff(CURDATE(), `Part Location Last Audit`) AS days_last_audit,`Part Location Last Audit` FROM `Part Location Dimension` PL LEFT JOIN `Location Dimension` L ON (L.`Location Key`=PL.`Location Key`)  WHERE `Part SKU`=%d 
        ORDER BY `Can Pick`,`Location File As` 

", $this->sku
        );


        $part_locations = array();


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($scope == 'keys') {
                    $part_locations[$row['Location Key']] = $row['Location Key'];
                } elseif ($scope == 'objects') {
                    $part_locations[$row['Location Key']] = new Location(
                        $row['Location Key']
                    );
                } elseif ($scope == 'part_location_object') {
                    $part_locations[$row['Location Key']] = new  PartLocation($this->sku.'_'.$row['Location Key']);
                } else {

                    /*
                                        switch ($row['Location Mainly Used For']) {
                                            case 'Picking':
                                                $used_for = sprintf(
                                                    '<i class="fa fa-fw fa-shopping-basket"  title="%s" ></i>', _('Picking')
                                                );
                                                break;
                                            case 'Storing':
                                                $used_for = sprintf(
                                                    '<i class="fa fa-fw  fa-hdd"  title="%s"></i>', _('Storing')
                                                );
                                                break;
                                            default:
                                                $used_for = sprintf(
                                                    '<i class="fa fa-fw  fa-map-maker"  title="%s"></i>', $row['Location Mainly Used For']
                                                );
                                        }
                    */


                    $picking_location_icon = sprintf(
                        '<i onclick="set_as_picking_location(%d,%d)" class="fa fa-fw fa-shopping-basket %s"  title="%s" ></i></span>', $this->id, $row['Location Key'], ($row['Can Pick'] == 'Yes' ? '' : 'super_discreet_on_hover button'),
                        ($row['Can Pick'] == 'Yes' ? _('Picking location') : _('Set as picking location'))

                    );


                    $part_locations[] = array(
                        'formatted_stock' => number($row['Quantity On Hand'], 3),
                        'stock'           => $row['Quantity On Hand'],
                        'warehouse_key'   => $row['Location Warehouse Key'],

                        'location_key' => $row['Location Key'],
                        'part_sku'     => $row['Part SKU'],

                        'location_code' => $row['Location Code'],
                        'note'          => $row['Part Location Note'],


                        'picking_location_icon' => $picking_location_icon,
                        //'location_used_for'      => $row['Location Mainly Used For'],
                        'formatted_min_qty'     => ($row['Minimum Quantity'] != '' ? $row['Minimum Quantity'] : '?'),
                        'formatted_max_qty'     => ($row['Maximum Quantity'] != '' ? $row['Maximum Quantity'] : '?'),
                        'formatted_move_qty'    => ($row['Moving Quantity'] != '' ? $row['Moving Quantity'] : '?'),
                        'min_qty'               => $row['Minimum Quantity'],
                        'max_qty'               => $row['Maximum Quantity'],
                        'move_qty'              => $row['Moving Quantity'],

                        'can_pick'        => $row['Can Pick'],
                        'label'           => ($row['Can Pick'] == 'Yes' ? _('Picking location') : _('Set as picking location')),
                        'days_last_audit' => ($row['days_last_audit'] == ''
                            ? '<span title="'._('Never been audited').'">-</span> <i class="far fa-clock padding_right_10" ></i> '
                            : sprintf(
                                '<span title="%s">%s</span>', sprintf(_('Last audit %s'), strftime("%a %e %b %Y %H:%M %Z", strtotime($row['Part Location Last Audit'].' +0:00')), $row['Part Location Last Audit']),
                                ($row['days_last_audit'] > 999 ? '<span class="error">+999</span>' : number($row['days_last_audit']))
                            ).' <i class="far fa-clock padding_right_10" ></i>')


                    );

                }

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $part_locations;
    }

    function get_category_data() {


        $type = 'Part';

        $sql = sprintf(
            "SELECT B.`Category Key`,`Category Root Key`,`Other Note`,`Category Label`,`Category Code`,`Is Category Field Other` FROM `Category Bridge` B LEFT JOIN `Category Dimension` C ON (C.`Category Key`=B.`Category Key`) WHERE  `Category Branch Type`='Head'  AND B.`Subject Key`=%d AND B.`Subject`=%s",
            $this->id, prepare_mysql($type)
        );

        $category_data = array();


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $sql = sprintf(
                    "SELECT `Category Label`,`Category Code` FROM `Category Dimension` WHERE `Category Key`=%d", $row['Category Root Key']
                );


                if ($result2 = $this->db->query($sql)) {
                    if ($row2 = $result2->fetch()) {
                        $root_label = $row2['Category Label'];
                        $root_code  = $row2['Category Code'];
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }


                if ($row['Is Category Field Other'] == 'Yes' and $row['Other Note'] != '') {
                    $value = $row['Other Note'];
                } else {
                    $value = $row['Category Label'];
                }
                $category_data[] = array(
                    'root_label'   => $root_label,
                    'root_code'    => $root_code,
                    'root_key'     => $row['Category Root Key'],
                    'label'        => $row['Category Label'],
                    'code'         => $row['Category Code'],
                    'value'        => $value,
                    'category_key' => $row['Category Key']
                );

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $category_data;
    }

    function update_status($value, $options = '', $force = false) {


        $old_value = $this->get('Part Status');

        if ($value == 'Not In Use' and ($this->data['Part Current On Hand Stock'] - $this->data['Part Current Stock In Process']) > 0) {
            $value = 'Discontinuing';
        }


        if ($value == $this->get('Part Status') and !$force) {
            return;
        }

        $this->update_field('Part Status', $value, $options);


        if ($old_value != $value) {

            if ($value == 'Discontinuing') {
                $this->discontinue_trigger();

            } elseif ($value == 'Not In Use') {


                foreach ($this->get_locations('part_location_object') as $part_location) {
                    $part_location->disassociate();
                }

                $this->update_stock();


                $this->update(
                    array('Part Valid To' => gmdate("Y-m-d H:i:s")), 'no_history'
                );


                $this->get_data('sku', $this->sku);


            }


            include_once 'utils/new_fork.php';
            $account = get_object('Account', 1);


            new_housekeeping_fork(
                'au_housekeeping', array(
                'type'     => 'update_part_status',
                'part_sku' => $this->id,
                'editor'   => $this->editor
            ), $account->get('Account Code')
            );
        }


    }

    function discontinue_trigger() {

        if ($this->get('Part Status') == 'Discontinuing' and ($this->data['Part Current On Hand Stock'] <= 0 and $this->data['Part Current Stock In Process'] == 0)) {
            $this->update_status('Not In Use');

            return;
        }
        if ($this->get('Part Status') == 'Not In Use' and ($this->data['Part Current On Hand Stock'] > 0 or $this->data['Part Current Stock In Process'] > 0)) {
            $this->update_status('Discontinuing');

            return;
        }
        if ($this->get('Part Status') == 'Not In Use' and ($this->data['Part Current On Hand Stock'] < 0)) {


            $this->update_status('Not In Use', '', true);


        }

    }

    function update_stock() {


        $old_value             = $this->data['Part Current Value'];
        $old_stock_in_progress = $this->data['Part Current Stock In Process'];
        $old_stock_picked      = $this->data['Part Current Stock Picked'];
        $old_stock_on_hand     = $this->data['Part Current On Hand Stock'];

        $picked   = 0;
        $required = 0;


        $sql = sprintf(
            "SELECT sum(`Picked`) AS picked, sum(`Required`) AS required FROM `Inventory Transaction Fact` WHERE `Part SKU`=%d AND `Inventory Transaction Type`='Order In Process'", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $picked   = round($row['picked'], 3);
                $required = round($row['required'], 3);
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        //$required+=$this->data['Part Current Stock Ordered Paid'];

        list($stock, $value, $in_process) = $this->get_current_stock();
        //print $stock;

        $this->fast_update(
            array(
                'Part Current Value'            => $value,
                'Part Current Stock In Process' => $required - $picked,
                'Part Current Stock Picked'     => $picked,
                'Part Current On Hand Stock'    => $stock,

            )
        );
        /*
                print "Stock $stock Picked  $picked\n";
                print "b* $old_value   ** ".$this->data['Part Current Value']."  \n"   ;
                print "b* $old_stock_in_progress   ** ".$this->data['Part Current Stock In Process']."  \n"   ;
                print "b* $old_stock_picked   ** ".$this->data['Part Current Stock Picked']."  \n"   ;
                print "b* $old_stock_on_hand   ** ".$this->data['Part Current On Hand Stock']."  \n"   ;
        */

        if ( $old_value != $this->data['Part Current Value'] or $old_stock_in_progress != $this->data['Part Current Stock In Process'] or $old_stock_picked != $this->data['Part Current Stock Picked']
            or $old_stock_on_hand != $this->data['Part Current On Hand Stock']

        ) {

            // print "XXXXXXX\n";

            $this->activate();
            $this->discontinue_trigger();
            $this->update_stock_status();

            //todo find a way do it more efficient
            $account = get_object('Account', 1);
            if ($account->get('Account Add Stock Value Type') == 'Blockchain') {
                $this->update_stock_run();
            }


            include_once 'utils/new_fork.php';
            $account = get_object('Account', 1);


            new_housekeeping_fork(
                'au_housekeeping', array(
                'type'     => 'update_part_products_availability',
                'part_sku' => $this->id
            ), $account->get('Account Code')
            );


            //print "Y $msg  YYYYYYYYYYY\n";
        }


    }

    function get_current_stock() {
        $stock      = 0;
        $value      = 0;
        $in_process = 0;


        $sql = sprintf(
            "SELECT sum(`Quantity On Hand`) AS stock , sum(`Quantity In Process`) AS in_process , sum(`Stock Value`) AS value FROM `Part Location Dimension` WHERE `Part SKU`=%d ", $this->id
        );

        //print $sql;
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                // print_r($row);

                $stock      = round($row['stock'], 3);
                $in_process = round($row['in_process'], 3);
                $value      = $row['value'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return array(
            $stock,
            $value,
            $in_process
        );

    }

    function activate() {

        if ($this->get('Part Status') == 'In Process') {


            if ($this->data['Part Number Active Products'] > 0 and $this->get('Part Current On Hand Stock') > 0) {
                $this->update(
                    array(
                        'Part Status'      => 'In Use',
                        'Part Active From' => gmdate('Y-m-d H:i:s')
                    ), 'no_history'
                );
            }


        }


    }

    function update_stock_status() {


        //print 'Delivery days '.$this->data['Part Delivery Days']."\n";
        //print 'Part Days Available Forecast '.$this->data['Part Days Available Forecast']."\n";

        $old_value = $this->get('Part Stock Status');

        if ($this->data['Part Current On Hand Stock'] < 0) {
            $stock_state = 'Error';
        } elseif ($this->data['Part Current On Hand Stock'] == 0) {
            if ($this->data['Part Fresh'] == 'Yes') {
                $stock_state = 'Optimal';
            } else {
                $stock_state = 'Out_of_Stock';
            }
        } elseif ($this->data['Part Days Available Forecast'] <= $this->data['Part Delivery Days']) {

            if ($this->data['Part Fresh'] == 'Yes') {
                $stock_state = 'Surplus';
            } else {
                $stock_state = 'Critical';
            }

        } elseif ($this->data['Part Days Available Forecast'] <= $this->data['Part Delivery Days'] + 7) {

            if ($this->data['Part Fresh'] == 'Yes') {
                $stock_state = 'Surplus';
            } else {
                $stock_state = 'Low';
            }


        } elseif ($this->data['Part Days Available Forecast'] >= $this->data['Part Excess Availability Days Limit']) {

            if ($this->data['Part Fresh'] == 'Yes') {
                $stock_state = 'Surplus';
            } else {
                $stock_state = 'Surplus';
            }


        } else {


            if ($this->data['Part Fresh'] == 'Yes') {
                $stock_state = 'Surplus';
            } else {
                $stock_state = 'Optimal';
            }

        }

        //print $stock_state;


        $this->fast_update(
            array(
                'Part Stock Status' => $stock_state
            )
        );


        if ($stock_state != $old_value) {
            $account = get_object('Account', 1);


            $account->update_parts_data();
            $account->update_active_parts_stock_data();
        }


    }

    function update_stock_run() {

        // todo experimental stuff
        $account = get_object('Account', 1);

        if ($account->get('Account Add Stock Value Type') == 'Blockchain') {

            $running_stock        = 0;
            $running_stock_value  = 0;
            $running_cost_per_sko = '';

            $sql = sprintf(
                'SELECT `Date`,`Note`,`Running Stock`,`Inventory Transaction Key`, `Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Inventory Transaction Type`,`Location Key`,`Inventory Transaction Section`,`Running Cost per SKO`,`Running Stock Value`,`Running Cost per SKO` FROM `Inventory Transaction Fact` WHERE `Part SKU`=%d   and  `Inventory Transaction Type`  ORDER BY `Date`   ',
                $this->id
            );

            //  print "$sql\n";

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {

                    //print "=========\n";
                    if (//!(
                        // ($row['Inventory Transaction Type']=='Adjust' )
                        // or
                        // ($row['Inventory Transaction Section']=='In' and $row['Inventory Transaction Quantity']>0)
                        // )

                        !($row['Inventory Transaction Section'] == 'In' and $row['Inventory Transaction Quantity'] > 0) and $running_cost_per_sko != '') {

                        // print $running_cost_per_sko."\n";
                        // print_r($row);

                        $sql = sprintf(
                            'UPDATE `Inventory Transaction Fact` SET `Inventory Transaction Amount`=%f  WHERE `Inventory Transaction Key`=%d ', $row['Inventory Transaction Quantity'] * $running_cost_per_sko,

                            $row['Inventory Transaction Key']
                        );
                        $this->db->exec($sql);
                        //  print "$sql\n";

                        $row['Inventory Transaction Amount'] = $row['Inventory Transaction Quantity'] * $running_cost_per_sko;


                        // print $running_cost_per_sko."\n";


                    }


                    // print_r($row);


                    $running_stock       = $running_stock + $row['Inventory Transaction Quantity'];
                    $running_stock_value = $running_stock_value + $row['Inventory Transaction Amount'];
                    if ($running_stock == 0) {
                        //$running_cost_per_sko='';
                    } else {
                        $running_cost_per_sko = $running_stock_value / $running_stock;
                    }


                    $sql = sprintf(
                        'UPDATE `Inventory Transaction Fact` SET `Running Stock`=%f,`Running Stock Value`=%f,`Running Cost per SKO`=%s  WHERE `Inventory Transaction Key`=%d ', $running_stock, $running_stock_value, prepare_mysql($running_cost_per_sko),
                        $row['Inventory Transaction Key']
                    );
                    $this->db->exec($sql);
                    // print "$sql\n";


                    //print "RR: $running_cost_per_sko \n-------\n";

                }


            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }
            $this->update_field_switcher('Part Cost in Warehouse', $running_cost_per_sko, 'no_history');

        } else {

            $running_stock        = 0;
            $running_stock_value  = 0;
            $running_cost_per_sko = '';

            $sql = sprintf(
                'SELECT `Date`,`Note`,`Running Stock`,`Inventory Transaction Key`, `Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Inventory Transaction Type`,`Location Key`,`Inventory Transaction Section`,`Running Cost per SKO`,`Running Stock Value`,`Running Cost per SKO` FROM `Inventory Transaction Fact` WHERE `Part SKU`=%d   and  `Inventory Transaction Type`  ORDER BY `Date`   ',
                $this->id
            );

            //  print "$sql\n";

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {

                    //print "=========\n";
                    if (//!(
                        // ($row['Inventory Transaction Type']=='Adjust' )
                        // or
                        // ($row['Inventory Transaction Section']=='In' and $row['Inventory Transaction Quantity']>0)
                        // )

                        !($row['Inventory Transaction Section'] == 'In' and $row['Inventory Transaction Quantity'] > 0) and $running_cost_per_sko != '') {

                        // print $running_cost_per_sko."\n";
                        // print_r($row);
                        /*
                                                $sql           = sprintf(
                                                    'UPDATE `Inventory Transaction Fact` SET `Inventory Transaction Amount`=%f  WHERE `Inventory Transaction Key`=%d ',
                                                    $row['Inventory Transaction Quantity']*$running_cost_per_sko,

                                                    $row['Inventory Transaction Key']
                                                );
                                                $this->db->exec($sql);
                                                //  print "$sql\n";
                        */
                        $row['Inventory Transaction Amount'] = $row['Inventory Transaction Quantity'] * $running_cost_per_sko;


                        // print $running_cost_per_sko."\n";


                    }


                    // print_r($row);


                    $running_stock       = $running_stock + $row['Inventory Transaction Quantity'];
                    $running_stock_value = $running_stock_value + $row['Inventory Transaction Amount'];
                    if ($running_stock == 0) {
                        //$running_cost_per_sko='';
                    } else {
                        $running_cost_per_sko = $running_stock_value / $running_stock;
                    }


                    $sql = sprintf(
                        'UPDATE `Inventory Transaction Fact` SET `Running Stock`=%f  WHERE `Inventory Transaction Key`=%d ', $running_stock,

                        $row['Inventory Transaction Key']
                    );
                    $this->db->exec($sql);
                    // print "$sql\n";


                    //print "RR: $running_cost_per_sko \n-------\n";

                }


            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            /*


            $sql=sprintf('select `Date`,(`Inventory Transaction Amount`/`Inventory Transaction Quantity`) as value_per_sko from  `Inventory Transaction Fact` ITF  where  `Inventory Transaction Amount`>0 and `Inventory Transaction Quantity`>0 and  ( `Inventory Transaction Section`=\'In\' or ( `Inventory Transaction Type`=\'Adjust\' and `Inventory Transaction Quantity`>0 and `Location Key`>1 )  )  and ITF.`Part SKU`=%d  order by `Date` desc, FIELD(`Inventory Transaction Type`, \'In\',\'Adjust\')  limit 1 ',$this->id);

           // print $sql;

            if ($result=$this->db->query($sql)) {
                foreach ($result as $row) {

                    //  print_r($row);

                    $this->update_field_switcher('Part Cost in Warehouse',$row['value_per_sko'],'no_history');
                }
            }else {
                print_r($error_info=$this->db->errorInfo());
                print "$sql\n";
                exit;
            }

            */
        }


    }

    function update_availability_for_products_configuration($value, $options) {

        $this->update_field(
            'Part Available for Products Configuration', $value, $options
        );
        $new_value = $this->new_value;
        $updated   = $this->updated;

        if (preg_match('/dont_update_pages/', $options)) {
            $update_products = false;
        } else {
            $update_products = true;
        }

        $this->update_availability_for_products($update_products);
        $this->new_value = $new_value;
        $this->updated   = $updated;

    }

    function update_availability_for_products($update_pages = true) {

        switch ($this->data['Part Available for Products Configuration']) {
            case 'Yes':
            case 'No':
                $this->update_field(
                    'Part Available for Products', $this->data['Part Available for Products Configuration']
                );
                break;
            case 'Automatic':
                if ($this->data['Part Current On Hand Stock'] > 0 and $this->data['Part Status'] == 'In Use') {
                    $this->update_field('Part Available for Products', 'Yes');
                } else {
                    $this->update_field('Part Available for Products', 'No');
                }

        }


        if ($this->updated) {


            if (isset($this->editor['User Key']) and is_numeric(
                    $this->editor['User Key']
                )) {
                $user_key = $this->editor['User Key'];
            } else {
                $user_key = 0;
            }

            $sql = sprintf(
                "SELECT UNIX_TIMESTAMP(`Date`) AS date,`Part Availability for Products Key` FROM `Part Availability for Products Timeline` WHERE `Part SKU`=%d AND `Warehouse Key`=%d  ORDER BY `Date` DESC ,`Part Availability for Products Key` DESC LIMIT 1", $this->sku,
                $this->warehouse_key
            );

            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $last_record_key  = $row['Part Availability for Products Key'];
                    $last_record_date = $row['date'];
                } else {
                    $last_record_key  = false;
                    $last_record_date = false;
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            $new_date_formatted = gmdate('Y-m-d H:i:s');
            $new_date           = gmdate('U');

            $sql = sprintf(
                "INSERT INTO `Part Availability for Products Timeline`  (`Part SKU`,`User Key`,`Warehouse Key`,`Date`,`Availability for Products`) VALUES (%d,%d,%d,%s,%s) ", $this->sku, $user_key, $this->warehouse_key, prepare_mysql($new_date_formatted),
                prepare_mysql($this->data['Part Available for Products'])

            );
            $this->db->exec($sql);

            if ($last_record_key) {
                $sql = sprintf(
                    "UPDATE `Part Availability for Products Timeline` SET `Duration`=%d WHERE `Part Availability for Products Key`=%d", $new_date - $last_record_date, $last_record_key

                );
                $this->db->exec($sql);

            }


            foreach ($this->get_products('objects') as $product) {
                $product->editor = $this->editor;
                //$product->update_web_state($update_pages);

            }

        }

    }

    function update_available_forecast() {

        $this->load_acc_data();


        if ($this->data['Part Current On Hand Stock'] == '' or $this->data['Part Current On Hand Stock'] < 0) {
            $this->data['Part Days Available Forecast']      = 0;
            $this->data['Part XHTML Available For Forecast'] = '?';
        } elseif ($this->data['Part Current On Hand Stock'] == 0) {
            $this->data['Part Days Available Forecast']      = 0;
            $this->data['Part XHTML Available For Forecast'] = 0;
        } else {


            //print $this->data['Part 1 Quarter Acc Dispatched'];

            //   print $this->data['Part 1 Quarter Acc Dispatched']/(52/4)/7;


            if ($this->data['Part 1 Quarter Acc Required'] > 0) {

                $days_on_sale = 91.25;

                $from_since = (date('U') - strtotime($this->data['Part Valid From'])) / 86400;
                if ($from_since < 1) {
                    $from_since = 1;
                }


                if ($days_on_sale > $from_since) {
                    $days_on_sale = $from_since;
                }


                $this->data['Part Days Available Forecast']      = $this->data['Part Current On Hand Stock'] / ($this->data['Part 1 Quarter Acc Required'] / $days_on_sale);
                $this->data['Part XHTML Available For Forecast'] = number($this->data['Part Days Available Forecast'], 0).' '._('d');

            } else {


                $from_since = (date('U') - strtotime($this->data['Part Valid From'])) / 86400;


                // print $from_since;

                if ($from_since < ($this->data['Part Excess Availability Days Limit'] / 2)) {
                    $forecast = $this->data['Part Excess Availability Days Limit'] - 1;
                } else {
                    $forecast = $this->data['Part Excess Availability Days Limit'] + $from_since;
                }


                $this->data['Part Days Available Forecast']      = $forecast;
                $this->data['Part XHTML Available For Forecast'] = number($this->data['Part Days Available Forecast'], 0).' '._('d');


            }


        }


        $this->fast_update(
            array(
                'Part Days Available Forecast'      => $this->data['Part Days Available Forecast'],
                'Part XHTML Available for Forecast' => $this->data['Part XHTML Available For Forecast']
            )
        );


        $this->update_stock_status();


    }

    function load_acc_data() {
        $sql = sprintf(
            "SELECT * FROM `Part Data` WHERE `Part SKU`=%d", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                foreach ($row as $key => $value) {
                    $this->data[$key] = $value;
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }

    function update_delivery_days($options = '') {


        $sum_delivery_days     = 0;
        $number_supplier_parts = 0;
        foreach ($this->get_supplier_parts('objects') as $supplier_part) {
            if ($supplier_part->get('Supplier Part Status') == 'Available') {
                $number_supplier_parts++;
                $sum_delivery_days += $supplier_part->get('Supplier Part Average Delivery Days');
            }

        }

        if ($number_supplier_parts == 0) {
            $average_delivery_days = 30;
        } else {
            $average_delivery_days = $sum_delivery_days / $number_supplier_parts;
        }

        $this->update(
            array(
                'Part Delivery Days' => $average_delivery_days
            ), $options
        );

        if ($this->updated) {
            $this->update_stock_status();
        }

    }

    function get_categories($scope = 'keys') {

        if ($scope == 'objects') {
            include_once 'class.Category.php';
        }


        $categories = array();


        $sql = sprintf(
            "SELECT B.`Category Key` FROM `Category Dimension` C LEFT JOIN `Category Bridge` B ON (B.`Category Key`=C.`Category Key`) WHERE `Subject`='Part' AND `Subject Key`=%d AND `Category Branch Type`!='Root'", $this->sku
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($scope == 'objects') {
                    $categories[$row['Category Key']] = new Category(
                        $row['Category Key']
                    );
                } else {
                    $categories[$row['Category Key']] = $row['Category Key'];
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        return $categories;


    }

    function update_sko_image_key() {


        $image_key = '';

        $sql = sprintf(
            'SELECT `Image Subject Image Key`  FROM `Image Subject Bridge` WHERE `Image Subject Object` = "Part" AND `Image Subject Object Key` =%d AND `Image Subject Object Image Scope`="SKO"  ', $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $image_key = $row['Image Subject Image Key'];
                break;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $this->update(array('Part SKO Image Key' => $image_key), 'no_history');

    }

    function get_production_suppliers($scope = 'keys') {


        if ($scope == 'objects') {
            include_once 'class.Supplier_Production.php';
        }

        $sql = sprintf(
            'SELECT `Supplier Part Supplier Key` FROM `Supplier Part Dimension`LEFT JOIN `Supplier Production Dimension` ON (`Supplier Part Supplier Key`=`Supplier Production Supplier Key`) WHERE `Supplier Production Supplier Key` IS NOT NULL AND `Supplier Part Part SKU`=%d ',
            $this->id
        );

        $suppliers = array();

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($scope == 'objects') {
                    $suppliers[$row['Supplier Part Supplier Key']] = new Supplier_Production(
                        $row['Supplier Part Supplier Key']
                    );
                } else {
                    $suppliers[$row['Supplier Part Supplier Key']] = $row['Supplier Part Supplier Key'];
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        return $suppliers;
    }

    function update_custom_fields($id, $value) {
        $this->update(array($id => $value));
    }

    function update_leakages($type = 'all') {


        if ($type == 'all' or $type == 'Lost') {
            $skos   = 0;
            $amount = 0;

            $sql = sprintf(
                "SELECT sum(`Inventory Transaction Quantity`) AS qty, sum(`Inventory Transaction Amount`) AS amount FROM `Inventory Transaction Fact` WHERE `Part SKU`=%d AND `Inventory Transaction Type`='Lost'", $this->id
            );


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $skos   = round($row['qty'], 1);
                    $amount = round($row['amount'], 2);
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }

            $this->fast_update(
                array(
                    'Part Stock Lost SKOs'   => -$skos,
                    'Part Stock Lost Amount' => -$amount


                )
            );


        }

        if ($type == 'all' or $type == 'Damaged') {
            $skos   = 0;
            $amount = 0;

            $sql = sprintf(
                "SELECT sum(`Inventory Transaction Quantity`) AS qty, sum(`Inventory Transaction Amount`) AS amount FROM `Inventory Transaction Fact` WHERE `Part SKU`=%d AND `Inventory Transaction Type`='Broken'", $this->id
            );


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $skos   = round($row['qty'], 1);
                    $amount = round($row['amount'], 2);
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }

            $this->fast_update(
                array(
                    'Part Stock Damaged SKOs'   => -$skos,
                    'Part Stock Damaged Amount' => -$amount


                )
            );


        }

        if ($type == 'all' or $type == 'Errors') {
            $skos   = 0;
            $amount = 0;

            $sql = sprintf(
                "SELECT sum(`Inventory Transaction Quantity`) AS qty, sum(`Inventory Transaction Amount`) AS amount FROM `Inventory Transaction Fact` WHERE `Part SKU`=%d AND `Inventory Transaction Type`='Other Out'  AND  `Inventory Transaction Quantity`<0  ", $this->id
            );


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $skos   = round($row['qty'], 1);
                    $amount = round($row['amount'], 2);
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }

            $this->fast_update(
                array(
                    'Part Stock Errors SKOs'   => -$skos,
                    'Part Stock Errors Amount' => -$amount


                )
            );


        }

        if ($type == 'all' or $type == 'Found') {
            $skos   = 0;
            $amount = 0;

            $sql = sprintf(
                "SELECT sum(`Inventory Transaction Quantity`) AS qty, sum(`Inventory Transaction Amount`) AS amount FROM `Inventory Transaction Fact` WHERE `Part SKU`=%d  AND `Inventory Transaction Type`='Other Out'  AND  `Inventory Transaction Quantity`>0 ", $this->id
            );


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $skos   = round($row['qty'], 1);
                    $amount = round($row['amount'], 2);
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }

            $this->fast_update(
                array(
                    'Part Stock Found SKOs'   => $skos,
                    'Part Stock Found Amount' => $amount


                )
            );


        }


    }

    function get_stock_supplier_data() {

        // todo create a way to know what is the supplier based in stock

        $supplier_key               = 0;
        $supplier_part_key          = 0;
        $supplier_part_historic_key = 0;


        foreach ($this->get_supplier_parts('objects') as $supplier_part) {
            $supplier_key               = $supplier_part->get('Supplier Part Supplier Key');
            $supplier_part_key          = $supplier_part->id;
            $supplier_part_historic_key = $supplier_part->get('Supplier Part Historic Key');
            break;
        }


        return array(
            $supplier_key,
            $supplier_part_key,
            $supplier_part_historic_key
        );
    }

    function update_on_demand() {

        $on_demand_available = 'No';
        foreach ($this->get_supplier_parts('objects') as $supplier_part) {
            if ($supplier_part->get('Supplier Part On Demand') == 'Yes' and $supplier_part->get('Supplier Part Status') == 'Available') {
                $on_demand_available = 'Yes';
                break;
            }
        }
        $this->update_field(
            'Part On Demand', $on_demand_available, 'no_history'
        );


        foreach ($this->get_products('objects') as $product) {
            $product->editor = $this->editor;
            $product->update_availability();
        }
    }

    function update_fresh() {

        $fresh_available = 'No';
        foreach ($this->get_supplier_parts('objects') as $supplier_part) {
            if ($supplier_part->get('Supplier Part Fresh') == 'Yes' and $supplier_part->get('Supplier Part On Demand') == 'Yes' and $supplier_part->get('Supplier Part Status') == 'Available') {
                $fresh_available = 'Yes';
                break;
            }
        }
        $this->update_field('Part Fresh', $fresh_available, 'no_history');

        $this->update_stock_status();


        foreach ($this->get_suppliers('objects') as $supplier) {
            $supplier->update_supplier_parts();
        }

    }

    function get_suppliers($scope = 'keys') {


        if ($scope == 'objects') {
            include_once 'class.Supplier.php';
        }

        $sql = sprintf(
            'SELECT `Supplier Part Supplier Key` FROM `Supplier Part Dimension` WHERE `Supplier Part Part SKU`=%d ', $this->id
        );

        $suppliers = array();

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($scope == 'objects') {


                    $suppliers[$row['Supplier Part Supplier Key']] = new Supplier('id', $row['Supplier Part Supplier Key'], false, $this->db);
                } else {
                    $suppliers[$row['Supplier Part Supplier Key']] = $row['Supplier Part Supplier Key'];
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        return $suppliers;
    }

    function update_weight_dimensions_data($field, $value, $type) {

        include_once 'utils/units_functions.php';

        //print "$field $value |";

        $this->update_field($field, $value);
        $_new_value = $this->new_value;
        $_updated   = $this->updated;

        $this->updated   = true;
        $this->new_value = $value;
        if ($this->updated) {

            if (preg_match('/Package/i', $field)) {
                $tag = 'Package';
            } else {
                $tag = 'Unit';
            }
            if ($field != 'Part '.$tag.' '.$type.' Display Units') {
                $value_in_standard_units = convert_units(
                    $value, $this->data['Part '.$tag.' '.$type.' Display Units'], ($type == 'Dimensions' ? 'm' : 'Kg')
                );


                $this->update_field(
                    preg_replace('/\sDisplay$/', '', $field), $value_in_standard_units, 'nohistory'
                );
            } elseif ($field == 'Part '.$tag.' Dimensions Display Units') {

                $width_in_standard_units    = convert_units(
                    $this->data['Part '.$tag.' Dimensions Width Display'], $value, 'm'
                );
                $depth_in_standard_units    = convert_units(
                    $this->data['Part '.$tag.' Dimensions Depth Display'], $value, 'm'
                );
                $length_in_standard_units   = convert_units(
                    $this->data['Part '.$tag.' Dimensions Length Display'], $value, 'm'
                );
                $diameter_in_standard_units = convert_units(
                    $this->data['Part '.$tag.' Dimensions Diameter Display'], $value, 'm'
                );


                $this->update_field(
                    'Part '.$tag.' Dimensions Width', $width_in_standard_units, 'nohistory'
                );
                $this->update_field(
                    'Part '.$tag.' Dimensions Depth', $depth_in_standard_units, 'nohistory'
                );
                $this->update_field(
                    'Part '.$tag.' Dimensions Length', $length_in_standard_units, 'nohistory'
                );
                $this->update_field(
                    'Part '.$tag.' Dimensions Diameter', $diameter_in_standard_units, 'nohistory'
                );


            }

            //print "x".$this->updated."<<";


            //print "x".$this->updated."< $type <";
            if ($type == 'Dimensions') {
                include_once 'utils/geometry_functions.php';
                $volume = get_volume(
                    $this->data["Part $tag Dimensions Type"], $this->data["Part $tag Dimensions Width"], $this->data["Part $tag Dimensions Depth"], $this->data["Part $tag Dimensions Length"], $this->data["Part $tag Dimensions Diameter"]
                );

                //print "*** $volume $volume";
                if (is_numeric($volume) and $volume > 0) {

                    $this->update_field(
                        'Part '.$tag.' Dimensions Volume', $volume, 'nohistory'
                    );
                }
                $this->update_field(
                    'Part '.$tag.' XHTML Dimensions', $this->get_xhtml_dimensions($tag), 'nohistory'
                );

            } else {
                $this->update_field(
                    'Part '.$tag.' Weight', convert_units(
                    $this->data['Part '.$tag.' Weight Display'], $this->data['Part '.$tag.' '.$type.' Display Units'], 'Kg'
                ), 'nohistory'
                );

            }


            $this->updated   = $_updated;
            $this->new_value = $_new_value;
        }
    }

    function get_period($period, $key) {
        return $this->get($period.' '.$key);
    }

    function get_unit($number) {
        //'10','25','100','200','bag','ball','box','doz','dwt','ea','foot','gram','gross','hank','kilo','ib','m','oz','ozt','pair','pkg','set','skein','spool','strand','ten','tube','vial','yd'
        switch ($this->data['Part Unit']) {
            case 'bag':
                $unit = ngettext('bag', 'bags', $number);
                break;
            case 'box':
                $unit = ngettext('box', 'boxes', $number);

                break;
            case 'doz':
                $unit = ngettext('dozen', 'dozens', $number);

                break;
            case 'ea':
                $unit = ngettext('unit', 'units', $number);

                break;
            default:
                $unit = $this->data['Part Unit'];
                break;
        }

        return $unit;
    }

    function get_stock($date) {
        $stock = 0;
        $value = 0;
        $sql   = sprintf(
            "SELECT ifnull(sum(`Quantity On Hand`), 0) AS stock, ifnull(sum(`Value At Cost`), 0) AS value FROM `Inventory Spanshot Fact` WHERE `Part SKU`=%d AND `Date`=%s", $this->id, prepare_mysql($date)
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $stock = $row['stock'];
                $value = $row['value'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return array(
            $stock,
            $value
        );
    }

    function update_stock_in_paid_orders() {


        $old_value = $this->get('Part Current Stock Ordered Paid');

        $stock_in_paid_orders = 0;


        $sql = sprintf(
            'SELECT sum((`Order Quantity`+`Order Bonus Quantity`)*`Product Part Ratio`) AS required FROM `Order Transaction Fact` OTF LEFT JOIN `Product Part Bridge` PPB ON (OTF.`Product ID`=PPB.`Product Part Product ID`) WHERE OTF.`Current Dispatching State` IN ("Submitted by Customer","In Process") AND  `Current Payment State` IN ("Paid","No Applicable") AND `Product Part Part SKU`=%d    ',

            $this->id
        );
        //print $sql;
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                //  print_r($row);
                $stock_in_paid_orders = $row['required'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        // print "$sql\n";
        $this->fast_update(
            array(
                'Part Current Stock Ordered Paid' => $stock_in_paid_orders

            )
        );

        if ($old_value != $stock_in_paid_orders) {
            $this->update_stock();
        }
    }

    function get_barcode_data() {

        switch ($this->data['Part Barcode Data Source']) {
            case 'SKU':
                return $this->sku;
            case 'Reference':
                return $this->data['Part Reference'];
            default:
                return $this->data['Part Barcode Data'];


        }

    }

    function get_current_formatted_value_at_cost() {
        //return number($this->data['Part Current Value'],2);
        return money($this->data['Part Current Value']);
    }

    /*
    function get_products_data($with_objects = false) {

        include_once 'class.Product.php';

        $sql           = sprintf(
            "SELECT `Linked Fields`,`Store Product Key`,`Parts Per Product`,`Note` FROM `Store Product Part Bridge` WHERE `Part SKU`=%d ", $this->id
        );


        $products_data = array();
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $product_data = $row;
                if ($product_data['Linked Fields'] == '') {
                    $product_data['Linked Fields']        = array();
                    $product_data['Number Linked Fields'] = 0;
                } else {
                    $product_data['Linked Fields']        = json_decode(
                        $row['Linked Fields'], true
                    );
                    $product_data['Number Linked Fields'] = count(
                        $product_data['Linked Fields']
                    );
                }
                if ($with_objects) {
                    $product_data['Product'] = new Product(
                        'id', $row['Store Product Key']
                    );
                }
                $products_data[] = $product_data;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        return $products_data;
    }
*/

    function get_current_formatted_value_at_current_cost() {

        $a = floatval(3.000 * 3.575);
        $a = round(3.575 + 3.575 + 3.575, 3);

        return money(
            $this->data['Part Current On Hand Stock'] * $this->data['Part Cost']
        );
    }

    function update_stock_history() {


        $sql = sprintf(
            "SELECT `Location Key`  FROM `Inventory Transaction Fact` WHERE `Part SKU`=%d GROUP BY `Location Key`", $this->sku
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $part_location = new PartLocation(
                    $this->sku.'_'.$row['Location Key']
                );
                $part_location->update_stock_history();
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }

    function update_stock_in_transactions() {

        $locations_data = array();
        $stock          = 0;
        $sql            = sprintf(
            "SELECT `Inventory Transaction Quantity` ,`Inventory Transaction Key`,`Location Key` FROM `Inventory Transaction Fact` WHERE `Part SKU`=%d ORDER BY `Date`,`Event Order`", $this->sku
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if (array_key_exists($row['Location Key'], $locations_data)) {
                    $locations_data[$row['Location Key']] += $row['Inventory Transaction Quantity'];
                } else {
                    $locations_data[$row['Location Key']] = $row['Inventory Transaction Quantity'];
                }

                $stock += $row['Inventory Transaction Quantity'];
                $sql   = sprintf(
                    "UPDATE `Inventory Transaction Fact` SET `Part Stock`=%f,`Part Location Stock`=%f WHERE `Inventory Transaction Key`=%d", $stock, $locations_data[$row['Location Key']], $row['Inventory Transaction Key']
                );
                $this->db->exec($sql);
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


    }

    function update_sales_from_invoices($interval, $this_year = true, $last_year = true) {

        include_once 'utils/date_functions.php';
        list(
            $db_interval, $from_date, $to_date, $from_date_1yb, $to_date_1yb
            ) = calculate_interval_dates($this->db, $interval);


        if ($this_year) {

            $sales_data = $this->get_sales_data($from_date, $to_date);


            $data_to_update = array(
                "Part $db_interval Acc Customers"        => $sales_data['customers'],
                "Part $db_interval Acc Repeat Customers" => $sales_data['repeat_customers'],
                "Part $db_interval Acc Deliveries"       => $sales_data['deliveries'],
                "Part $db_interval Acc Profit"           => $sales_data['profit'],
                "Part $db_interval Acc Invoiced Amount"  => $sales_data['invoiced_amount'],
                "Part $db_interval Acc Required"         => $sales_data['required'],
                "Part $db_interval Acc Dispatched"       => $sales_data['dispatched'],
                "Part $db_interval Acc Keeping Days"     => $sales_data['keep_days'],
                "Part $db_interval Acc With Stock Days"  => $sales_data['with_stock_days'],
            );


            $this->fast_update($data_to_update, 'Part Data');
        }
        if ($from_date_1yb and $last_year) {


            $sales_data = $this->get_sales_data($from_date_1yb, $to_date_1yb);


            $data_to_update = array(

                "Part $db_interval Acc 1YB Customers"        => $sales_data['customers'],
                "Part $db_interval Acc 1YB Repeat Customers" => $sales_data['repeat_customers'],
                "Part $db_interval Acc 1YB Deliveries"       => $sales_data['deliveries'],
                "Part $db_interval Acc 1YB Profit"           => $sales_data['profit'],
                "Part $db_interval Acc 1YB Invoiced Amount"  => $sales_data['invoiced_amount'],
                "Part $db_interval Acc 1YB Required"         => $sales_data['required'],
                "Part $db_interval Acc 1YB Dispatched"       => $sales_data['dispatched'],
                "Part $db_interval Acc 1YB Keeping Day"      => $sales_data['keep_days'],
                "Part $db_interval Acc 1YB With Stock Days"  => $sales_data['with_stock_days'],

            );
            $this->fast_update($data_to_update, 'Part Data');


        }

        if (in_array(
            $db_interval, [
                            'Total',
                            'Year To Date',
                            'Quarter To Date',
                            'Week To Date',
                            'Month To Date',
                            'Today'
                        ]
        )) {

            $this->fast_update(['Part Acc To Day Updated' => gmdate('Y-m-d H:i:s')]);

        } elseif (in_array(
            $db_interval, [
                            '1 Year',
                            '1 Month',
                            '1 Week',
                            '1 Quarter'
                        ]
        )) {

            $this->fast_update(['Part Acc Ongoing Intervals Updated' => gmdate('Y-m-d H:i:s')]);
        } elseif (in_array(
            $db_interval, [
                            'Last Month',
                            'Last Week',
                            'Yesterday',
                            'Last Year'
                        ]
        )) {

            $this->fast_update(['Part Acc Previous Intervals Updated' => gmdate('Y-m-d H:i:s')]);
        }


    }

    function get_sales_data($from_date, $to_date) {

        $sales_data = array(
            'invoiced_amount'  => 0,
            'profit'           => 0,
            'required'         => 0,
            'dispatched'       => 0,
            'deliveries'       => 0,
            'customers'        => 0,
            'repeat_customers' => 0,
            'keep_days'        => 0,
            'with_stock_days'  => 0,

        );


        if ($from_date == '' and $to_date == '') {
            $sales_data['repeat_customers'] = $this->get_customers_total_data();
        }


        $sql = sprintf(
            "SELECT count(DISTINCT `Delivery Note Customer Key`) AS customers, count( DISTINCT ITF.`Delivery Note Key`) AS deliveries, round(ifnull(sum(`Amount In`),0),2) AS invoiced_amount,round(ifnull(sum(`Amount In`+`Inventory Transaction Amount`),0),2) AS profit,round(ifnull(sum(`Inventory Transaction Quantity`),0),1) AS dispatched,round(ifnull(sum(`Required`),0),1) AS required 
              FROM `Inventory Transaction Fact` ITF  LEFT JOIN `Delivery Note Dimension` DN ON (DN.`Delivery Note Key`=ITF.`Delivery Note Key`) 
              WHERE `Inventory Transaction Type` LIKE 'Sale' AND `Part SKU`=%d %s %s", $this->id, ($from_date ? sprintf('and  `Date`>=%s', prepare_mysql($from_date)) : ''), ($to_date ? sprintf('and `Date`<%s', prepare_mysql($to_date)) : '')
        );

        //print "$sql\n";

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $sales_data['customers']       = $row['customers'];
                $sales_data['invoiced_amount'] = $row['invoiced_amount'];
                $sales_data['profit']          = $row['profit'];
                $sales_data['dispatched']      = -1.0 * $row['dispatched'];
                $sales_data['required']        = $row['required'];
                $sales_data['deliveries']      = $row['deliveries'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $sales_data;

    }

    function get_customers_total_data() {

        $repeat_customers = 0;


        $sql = sprintf(
            'SELECT count(`Customer Part Customer Key`) AS num  FROM `Customer Part Bridge` WHERE `Customer Part Delivery Notes`>1 AND `Customer Part Part SKU`=%d    ', $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $repeat_customers = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $repeat_customers;

    }

    function update_previous_years_data() {

        foreach (range(1, 5) as $i) {
            $data_iy_ago    = $this->get_sales_data(
                date('Y-01-01 00:00:00', strtotime('-'.$i.' year')), date('Y-01-01 00:00:00', strtotime('-'.($i - 1).' year'))
            );
            $data_to_update = array(
                "Part $i Year Ago Customers"        => $data_iy_ago['customers'],
                "Part $i Year Ago Repeat Customers" => $data_iy_ago['repeat_customers'],
                "Part $i Year Ago Deliveries"       => $data_iy_ago['deliveries'],
                "Part $i Year Ago Profit"           => $data_iy_ago['profit'],
                "Part $i Year Ago Invoiced Amount"  => $data_iy_ago['invoiced_amount'],
                "Part $i Year Ago Required"         => $data_iy_ago['required'],
                "Part $i Year Ago Dispatched"       => $data_iy_ago['dispatched'],
                "Part $i Year Ago Keeping Day"      => $data_iy_ago['keep_days'],
                "Part $i Year Ago With Stock Days"  => $data_iy_ago['with_stock_days'],
            );

            $this->update($data_to_update, 'no_history');
        }

    }

    function update_previous_quarters_data() {


        include_once 'utils/date_functions.php';

        foreach (range(1, 4) as $i) {
            $dates     = get_previous_quarters_dates($i);
            $dates_1yb = get_previous_quarters_dates($i + 4);


            $sales_data     = $this->get_sales_data(
                $dates['start'], $dates['end']
            );
            $sales_data_1yb = $this->get_sales_data(
                $dates_1yb['start'], $dates_1yb['end']
            );

            $data_to_update = array(
                "Part $i Quarter Ago Customers"        => $sales_data['customers'],
                "Part $i Quarter Ago Repeat Customers" => $sales_data['repeat_customers'],
                "Part $i Quarter Ago Deliveries"       => $sales_data['deliveries'],
                "Part $i Quarter Ago Profit"           => $sales_data['profit'],
                "Part $i Quarter Ago Invoiced Amount"  => $sales_data['invoiced_amount'],
                "Part $i Quarter Ago Required"         => $sales_data['required'],
                "Part $i Quarter Ago Dispatched"       => $sales_data['dispatched'],
                "Part $i Quarter Ago Keeping Day"      => $sales_data['keep_days'],
                "Part $i Quarter Ago With Stock Days"  => $sales_data['with_stock_days'],

                "Part $i Quarter Ago 1YB Customers"        => $sales_data_1yb['customers'],
                "Part $i Quarter Ago 1YB Repeat Customers" => $sales_data_1yb['repeat_customers'],
                "Part $i Quarter Ago 1YB Deliveries"       => $sales_data_1yb['deliveries'],
                "Part $i Quarter Ago 1YB Profit"           => $sales_data_1yb['profit'],
                "Part $i Quarter Ago 1YB Invoiced Amount"  => $sales_data_1yb['invoiced_amount'],
                "Part $i Quarter Ago 1YB Required"         => $sales_data_1yb['required'],
                "Part $i Quarter Ago 1YB Dispatched"       => $sales_data_1yb['dispatched'],
                "Part $i Quarter Ago 1YB Keeping Day"      => $sales_data_1yb['keep_days'],
                "Part $i Quarter Ago 1YB With Stock Days"  => $sales_data_1yb['with_stock_days'],
            );
            $this->update($data_to_update, 'no_history');
        }

    }

    function delete($metadata = false) {


        $sql = sprintf(
            'INSERT INTO `Part Deleted Dimension`  (`Part Deleted Key`,`Part Deleted Reference`,`Part Deleted Date`,`Part Deleted Metadata`) VALUES (%d,%s,%s,%s) ', $this->id, prepare_mysql($this->get('Part Reference')), prepare_mysql(gmdate('Y-m-d H:i:s')),
            prepare_mysql(gzcompress(json_encode($this->data), 9))

        );
        $this->db->exec($sql);


        $sql = sprintf(
            'DELETE FROM `Part Dimension`  WHERE `Part SKU`=%d ', $this->id
        );
        $this->db->exec($sql);


        $history_data = array(
            'History Abstract' => sprintf(
                _("Part record %s deleted"), $this->data['Part Reference']
            ),
            'History Details'  => '',
            'Action'           => 'deleted'
        );

        $this->add_subject_history(
            $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
        );


        $this->deleted = true;


        $sql = sprintf(
            'SELECT `Supplier Part Key` FROM `Supplier Part Dimension` WHERE `Supplier Part Part SKU`=%d  ', $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $supplier_part = get_object(
                    'Supplier Part', $row['Supplier Part Key']
                );
                $supplier_part->delete();
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }

    function get_field_label($field) {
        global $account;

        switch ($field) {

            case 'Part SKU':
                $label = _('SKU');
                break;
            case 'Part Status':
                $label = _('Status');
                break;
            case 'Part Reference':
                $label = _('reference');
                break;
            case 'Part Unit Label':
                $label = _('unit label');
                break;
            case 'Part Package Description':
                $label = _('SKO description');
                break;
            case 'Part Package Description Note':
                $label = _('SKO description note');
                break;
            case 'Part Package Image':
                $label = _('SKO image');
                break;
            case 'Part Unit Price':
                $label = _('unit recommended price');
                break;
            case 'Part Unit RRP':
                $label = _('unit recommended RRP');
                break;

            case 'Part Package Weight':
                $label = _('SKO weight');
                break;
            case 'Part Package Dimensions':
                $label = _('SKO dimensions');
                break;
            case 'Part Unit Weight':
                $label = _('unit weight');
                break;
            case 'Part Unit Dimensions':
                $label = _('unit dimensions');
                break;
            case 'Part Tariff Code':
                $label = _('tariff code');
                break;
            case 'Part Duty Rate':
                $label = _('duty rate');
                break;
            case 'Part UN Number':
                $label = _('UN number');
                break;
            case 'Part UN Class':
                $label = _('UN class');
                break;
            case 'Part Packing Group':
                $label = _('packing group');
                break;
            case 'Part Proper Shipping Name':
                $label = _('proper shipping name');
                break;
            case 'Part Hazard Indentification Number':
                $label = _('hazard identification number');
                break;
            case 'Part Materials':
                $label = _('Materials/Ingredients');
                break;
            case 'Part Origin Country Code':
                $label = _('country of origin');
                break;
            case 'Part Units Per Package':
                $label = _('units per SKO');
                break;
            case 'Part Barcode Number':
                $label = _('barcode');
                break;
            case 'Part CPNP Number':
                $label = _('CPNP number');
                break;
            case 'Part Cost in Warehouse':
                $label = _('Stock value (per SKO)');
                break;
            case 'Part Recommended Packages Per Selling Outer':
                $label = _('Recommended SKOs per selling outer');
                break;
            case 'Part SKOs per Carton':
                $label = _('SKOs per selling carton');
                break;
            case 'Part Recommended Product Unit Name':
                $label = _('Unit recommended description');
                break;
            case 'Part Production Supply':
                $label = _('used in production');
                break;
            case 'Part Carton Barcode':
                $label = _('carton barcode');
                break;

            case 'Part Delivery Day':
                $label = _('average delivery days');
                break;

            default:
                $label = $field;

        }

        return $label;

    }

    function create_supplier_part_record($data) {


        include_once 'class.Supplier.php';

        $data['editor'] = $this->editor;


        $supplier = new Supplier($data['Supplier Part Supplier Key']);
        if (!$supplier->id) {
            $this->error      = true;
            $this->error_code = 'supplier_not_found';
            $this->msg        = _('Supplier not found');
        }

        if ($data['Supplier Part Minimum Carton Order'] == '') {
            $data['Supplier Part Minimum Carton Order'] = 1;
        } else {
            $data['Supplier Part Minimum Carton Order'] = ceil(
                $data['Supplier Part Minimum Carton Order']
            );
        }


        $data['Supplier Part Currency Code'] = $supplier->get('Supplier Default Currency Code');


        $supplier_part = new SupplierPart('find', $data, 'create');


        if ($supplier_part->id) {
            $this->new_object_msg = $supplier_part->msg;

            if ($supplier_part->new) {
                $this->new_object = true;


                $supplier_part->update(array('Supplier Part Part SKU' => $this->sku));
                $supplier_part->get_data('id', $supplier_part->id);

                $supplier->update_supplier_parts();

                $this->update_cost();
                $supplier_part->update_historic_object();


            } else {

                $this->error = true;
                if ($supplier_part->found) {

                    $this->error_code     = 'duplicated_field';
                    $this->error_metadata = json_encode(
                        array($supplier_part->duplicated_field)
                    );

                    if ($supplier_part->duplicated_field == 'Supplier Part Reference') {
                        $this->msg = _("Duplicated supplier's part reference");
                    } else {
                        $this->msg = 'Duplicated '.$supplier_part->duplicated_field;
                    }


                } else {
                    $this->msg = $supplier_part->msg;
                }
            }

            return $supplier_part;
        } else {
            $this->error = true;

            if ($supplier_part->found) {
                $this->error_code     = 'duplicated_field';
                $this->error_metadata = json_encode(
                    array($supplier_part->duplicated_field)
                );

                if ($supplier_part->duplicated_field == 'Part Reference') {
                    $this->msg = _("Duplicated part reference");
                } else {
                    $this->msg = 'Duplicated '.$supplier_part->duplicated_field;
                }

            } else {


                $this->msg = $supplier_part->msg;
            }
        }

    }

    function updated_linked_products() {
        include_once 'class.Image.php';
        foreach ($this->get_products('objects') as $product) {

            if (count($product->get_parts()) == 1) {
                $product->editor = $this->editor;

                $product->update(
                    array(
                        'Product Tariff Code' => $this->get(
                            'Part Tariff Code'
                        )
                    ), 'no_history from_part'
                );
                $product->update(
                    array('Product Duty Rate' => $this->get('Part Duty Rate')), 'no_history from_part'
                );
                $product->update(
                    array(
                        'Product Origin Country Code' => $this->get(
                            'Part Origin Country Code'
                        )
                    ), 'no_history from_part'
                );


                $product->update(
                    array('Product UN Number' => $this->get('Part UN Number')), 'no_history from_part'
                );
                $product->update(
                    array('Product UN Class' => $this->get('Part UN Class')), 'no_history from_part'
                );
                $product->update(
                    array(
                        'Product Packing Group' => $this->get(
                            'Part Packing Group'
                        )
                    ), 'no_history from_part'
                );
                $product->update(
                    array(
                        'Product Proper Shipping Name' => $this->get(
                            'Part Proper Shipping Name'
                        )
                    ), 'no_history from_part'
                );
                $product->update(
                    array(
                        'Product Hazard Indentification Number' => $this->get(
                            'Part Hazard Indentification Number'
                        )
                    ), 'no_history from_part'
                );


                $product->update(
                    array(
                        'Product Unit Weight' => $this->get(
                            'Part Unit Weight'
                        )
                    ), 'no_history from_part'
                );


                $product->update(
                    array(
                        'Product Unit Dimensions' => $this->get(
                            'Part Unit Dimensions'
                        )
                    ), 'no_history from_part'
                );
                $product->update(
                    array(
                        'Product Materials' => strip_tags(
                            $this->get('Materials')
                        )
                    ), 'no_history from_part'
                );

                $sql = sprintf(
                    'SELECT `Image Subject Image Key` FROM `Image Subject Bridge` WHERE `Image Subject Object`="Part" AND `Image Subject Object Key`=%d ORDER BY `Image Subject Order` ', $this->id
                );

                //   print "$sql\n";

                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        //print_r($row);
                        $product->link_image($row['Image Subject Image Key']);
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }


            }

        }


    }

    function get_picking_location_key() {

        $sql = sprintf("SELECT `Location Key` FROM `Part Location Dimension` WHERE `Part SKU`=%d AND `Can Pick`='Yes' ;", $this->sku);

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                return $row['Location Key'];
            } else {
                return false;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

    }

    function update_products_data() {


        //'InProcess','Active','Suspended',,'Discontinued'

        $active_products    = 0;
        $no_active_products = 0;


        $sql = sprintf(
            "SELECT count(*) AS num FROM `Product Part Bridge`  LEFT JOIN `Product Dimension` P ON (P.`Product ID`=`Product Part Product ID`)  WHERE `Product Part Part SKU`=%d  AND `Product Status` IN ('InProcess','Active','Discontinuing') ", $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $active_products = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $sql = sprintf(
            "SELECT count(*) AS num FROM `Product Part Bridge`  LEFT JOIN `Product Dimension` P ON (P.`Product ID`=`Product Part Product ID`)  WHERE `Product Part Part SKU`=%d  AND `Product Status` IN ('Suspended','Discontinued') ", $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $no_active_products = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $this->fast_update(
            array(
                'Part Number Active Products'    => $active_products,
                'Part Number No Active Products' => $no_active_products,

            )

        );
        $this->activate();

    }


    function update_commercial_value() {

        include_once 'utils/currency_functions.php';
        include_once 'utils/date_functions.php';

        $num_all_products        = 0;
        $num_products            = 0;
        $num_products_with_sales = 0;
        $sum_sales               = 0;
        $data                    = array();

        $account = get_object('Account', 1);

        foreach ($this->get_products('data') as $product_data) {

            $num_all_products++;

            $product = get_object('product', $product_data['Product Part Product ID']);


            if (count($product->get_parts()) == 1) {
                $product->load_acc_data();
                $num_products++;

                if ($product->get('Product Status') == 'Discontinued') {


                    list($db_interval, $from_date, $to_date, $from_date_1yb, $to_1yb) = calculate_interval_dates($this->db, '1 Year');



                    $invoiced=0;



                    // todo quick hack before migration is done
                    global $account;
                    if ($account->get('Code') == 'AW') {
                        $sql = sprintf(
                            "SELECT round(ifnull(sum(`Invoice Quantity`),0),1) AS invoiced FROM `Order Transaction Fact` USE INDEX (`Product ID`,`Invoice Date`) WHERE `Invoice Key` IS NOT NULL AND  `Product ID`=%d %s %s ", $product->id, ($from_date ? sprintf(
                            'and `Invoice Date`>=%s', prepare_mysql($from_date)
                        ) : ''), ($to_date ? sprintf(
                            'and `Invoice Date`<%s', prepare_mysql($to_date)
                        ) : '')

                        );
                    } else {
                        $sql = sprintf(
                            "SELECT round(ifnull(sum(`Delivery Note Quantity`),0),1) AS invoiced FROM `Order Transaction Fact` USE INDEX (`Product ID`,`Invoice Date`) WHERE `Invoice Key` >0 AND  `Product ID`=%d %s %s ", $product->id, ($from_date ? sprintf(
                            'and `Invoice Date`>=%s', prepare_mysql($from_date)
                        ) : ''), ($to_date ? sprintf(
                            'and `Invoice Date`<%s', prepare_mysql($to_date)
                        ) : '')

                        );
                    }

                   // print "$sql\n";


                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {


                            $invoiced  = $row['invoiced'];



                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        exit;
                    }








                    $sales = $invoiced * $product_data['Product Part Ratio'];

                } else {
                    $sales = $product->get('Product 1 Year Acc Quantity Invoiced') * $product_data['Product Part Ratio'];

                }


                // print $product->get('Product 1 Year Acc Quantity Invoiced');

                $exchange = 1 / currency_conversion($this->db, $account->get('Account Currency'), $product->get('Product Currency'));

                if ($sales > 0) {
                    $num_products_with_sales++;
                    $sum_sales += $sales;


                    $data[] = array(
                        'store'         => $product->get('Store Key'),
                        'sales'         => $sales,
                        'price'         => $product->get('Product Price'),
                        'selling_price' => $exchange * $product->get('Product Price') / $product_data['Product Part Ratio'],
                        'exchange'      => $exchange,
                        'currency'      => $product->get('Product Currency')
                    );
                } else {
                    $data[] = array(
                        'store'         => $product->get('Store Key'),
                        'sales'         => 0,
                        'price'         => $product->get('Product Price'),
                        'selling_price' => $exchange * $product->get('Product Price') / $product_data['Product Part Ratio'],
                        'exchange'      => $exchange,
                        'currency'      => $product->get('Product Currency')
                    );

                }


            }


        }

        // print_r($data);

        $commercial_value = '';

        if ($num_products_with_sales > 0) {
            $commercial_value = 0;

            foreach ($data as $item) {
                $commercial_value += $item['selling_price'] * $item['sales'] / $sum_sales;
                //print $item['selling_price'].' '.$item['sales']/$sum_sales."\n";
            }

        } elseif ($num_products > 0) {


            $commercial_value = 0;

            foreach ($data as $item) {
                $commercial_value += $item['selling_price'];
            }


            $commercial_value = $commercial_value / $num_products;
        }


        $this->fast_update(array('Part Commercial Value' => $commercial_value));
        $this->update_margin();


    }

    function update_unknown_location() {

        $stock = 0;
        $value = 0;
        $sql   = sprintf("SELECT `Quantity On Hand`,`Stock Value` FROM `Part Location Dimension` WHERE `Location Key`=1 AND `Part SKU`=%d ", $this->id);
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $stock = $row['Quantity On Hand'];
                $value = $row['Stock Value'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $this->fast_update(
            array(
                'Part Unknown Location Stock'       => $stock,
                'Part Unknown Location Stock Value' => $value,

            )
        );

    }

    function redo_inventory_snapshot_fact($from = '') {

        include_once "class.PartLocation.php";


        if ($from == '') {
            $from = $this->get('Part Valid From');
        }

        $to = ($this->get('Part Status') == 'Not In Use' ? $this->get('Part Valid To') : gmdate('Y-m-d H:i:s'));


        $sql = sprintf(
            "DELETE FROM `Inventory Spanshot Fact` WHERE `Part SKU`=%d  AND (`Date`<%s  OR `Date`>%s  )", $this->sku, prepare_mysql($from), prepare_mysql($to)
        );
        $this->db->exec($sql);


        //$from='2016-03-18';
        //$to='2016-03-18';
        $sql = sprintf(
            "SELECT `Date` FROM kbase.`Date Dimension` WHERE `Date`>=date(%s) AND `Date`<=DATE(%s) ORDER BY `Date` DESC", prepare_mysql($from), prepare_mysql($to)
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $sql = sprintf(
                    "SELECT `Location Key`  FROM `Inventory Transaction Fact` WHERE  `Inventory Transaction Type` LIKE 'Associate' AND  `Part SKU`=%d AND `Date`<=%s GROUP BY `Location Key`", $this->id, prepare_mysql($row['Date'].' 23:59:59')
                );


                if ($result3 = $this->db->query($sql)) {
                    foreach ($result3 as $row3) {
                        // print $row['Date'].' '.$this->id.'_'.$row3['Location Key']."\r";

                        $part_location = new PartLocation(
                            $this->id.'_'.$row3['Location Key']
                        );
                        $part_location->update_stock_history_date($row['Date']);


                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }


}



