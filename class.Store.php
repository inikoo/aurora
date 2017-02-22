<?php
/*
  File: Company.php

  This file contains the Company Class

  About:
  Author: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2009, Inikoo

  Version 2.0
*/

/* class: Store
   Class to manage the *Company Dimension* table
*/

include_once 'class.DB_Table.php';

class Store extends DB_Table {


    function Store($a1, $a2 = false, $a3 = false) {

        global $db;

        $this->db = $db;

        $this->table_name    = 'Store';
        $this->ignore_fields = array('Store Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } elseif ($a1 == 'find') {
            $this->find($a2, $a3);

        } else {
            $this->get_data($a1, $a2);
        }

    }


    function get_data($tipo, $tag) {

        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Store Dimension` WHERE `Store Key`=%d", $tag
            );
        } elseif ($tipo == 'code') {
            $sql = sprintf(
                "SELECT * FROM `Store Dimension` WHERE `Store Code`=%s", prepare_mysql($tag)
            );
        } else {
            return;
        }

        if ($this->data = $this->db->query($sql)->fetch()) {

            $this->id   = $this->data['Store Key'];
            $this->code = $this->data['Store Code'];
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

        $this->found     = false;
        $this->found_key = false;

        $create = '';
        $update = '';
        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }
        if (preg_match('/update/i', $options)) {
            $update = 'update';
        }

        $data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $data[$key] = _trim($value);
            }
        }

        //    print_r($raw_data);

        if ($data['Store Code'] == '') {
            $this->error = true;
            $this->msg   = 'Store code empty';

            return;
        }

        if ($data['Store Name'] == '') {
            $data['Store Name'] = $data['Store Code'];
        }


        $sql = sprintf(
            "SELECT `Store Key` FROM `Store Dimension` WHERE `Store Code`=%s  ", prepare_mysql($data['Store Code'])
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $this->found     = true;
                $this->found_key = $row['Store Key'];
                $this->get_data('id', $this->found_key);
                $this->duplicated_field = 'Store Code';

                return;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }
        $sql = sprintf(
            "SELECT `Store Key` FROM `Store Dimension` WHERE `Store Name`=%s  ", prepare_mysql($data['Store Name'])
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $this->found     = true;
                $this->found_key = $row['Store Key'];
                $this->get_data('id', $this->found_key);
                $this->duplicated_field = 'Store Name';

                return;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        if ($create and !$this->found) {
            $this->create($data);

            return;
        }


    }

    function create($data) {


        $this->new = false;
        $basedata  = $this->base_data();

        foreach ($data as $key => $value) {
            if (array_key_exists($key, $basedata)) {
                $basedata[$key] = _trim($value);
            }
        }

        $keys   = '(';
        $values = 'values (';
        foreach ($basedata as $key => $value) {
            $keys .= "`$key`,";
            if (preg_match(
                '/Store Email|Store Telephone|Store Telephone|Slogan|URL|Fax|Sticky Note|Store VAT Number/i', $key
            )) {
                $values .= prepare_mysql($value, false).",";
            } else {
                $values .= prepare_mysql($value).",";
            }
        }
        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);
        $sql    = sprintf(
            "INSERT INTO `Store Dimension` %s %s", $keys, $values
        );

        $sql = "insert into `Store Dimension` $keys  $values";

        if ($this->db->exec($sql)) {
            $this->id = $this->db->lastInsertId();


            $this->msg = _("Store Added");
            $this->get_data('id', $this->id);
            $this->new = true;

            if (is_numeric($this->editor['User Key']) and $this->editor['User Key'] > 1) {

                $sql = sprintf(
                    "INSERT INTO `User Right Scope Bridge` VALUES(%d,'Store',%d)", $this->editor['User Key'], $this->id
                );
                $this->db->exec($sql);

            }

            $sql = "INSERT INTO `Store Default Currency` (`Store Key`) VALUES(".$this->id.");";
            $this->db->exec($sql);

            $sql = "INSERT INTO `Store Data Currency` (`Store Key`) VALUES(".$this->id.");";
            $this->db->exec($sql);


            $sql = sprintf(
                "INSERT INTO `Store Data` (`Store Key`) VALUES (%d)", $this->id
            );

            $db->exec($sql);
            $sql = sprintf(
                "INSERT INTO `Store DC Data` (`Store Key`) VALUES (%d)", $this->id

            );
            $db->exec($sql);


            /*

			$dept_data=array(
				'Product Department Code'=>'ND_'.$this->data['Store Code'],
				'Product Department Name'=>_('Products without department'),
				'Product Department Store Key'=>$this->id,
				'Product Department Sales Type'=>'Not for Sale',
				'editor'=>$this->editor
			);

			$dept_no_dept=new Department('find', $dept_data, 'create');
			$this->data['Store No Products Department Key']=$dept_no_dept->id;



			$fam_data=array(
				'Product Family Code'=>'PND_'.$this->data['Store Code'],
				'Product Family Name'=>_('Products without family'),
				'Product Family Main Department Key'=>$dept_no_dept->id,
				'Product Family Store Key'=>$this->id,
				'Product Family Special Characteristic'=>'None',
				'Product Family Sales Type'=>'Not for Sale',
				'Product Family Availability'=>'No Applicable',
				'editor'=>$this->editor
			);

			$fam_no_fam=new Family('find', $fam_data, 'create');
			$this->data['Store No Products Family Key']=$fam_no_fam->id;



			$sql=sprintf("update `Store Dimension` set `Store No Products Department Key`=%d ,`Store No Products Family Key`=%d where `Store Key`=%d",
				$dept_no_dept->id,
				$fam_no_fam->id,
				$this->id

			);

			mysql_query($sql);
*/

            /*

			$sql=sprintf("select `SR Category Key` from `Account Dimension` ");
			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$parent_category_key=$row['SR Category Key'];

			}

			if ($parent_category_key) {
				$this->create_sr_category($parent_category_key);

			}
*/


            $history_data = array(
                'History Abstract' => sprintf(
                    _('Store %s (%s) created'), $this->data['Store Name'], $this->data['Store Code']
                ),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $history_key = $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->get_main_id()
            );


            include_once 'class.Account.php';
            $account = new Account();
            $account->add_account_history($history_key);

            return;
        } else {
            print $sql;
            exit;
            $this->msg = _("Error can not create store");

        }

    }

    function load_acc_data() {

        $sql = sprintf("SELECT * FROM `Store Data`  WHERE `Store Key`=%d", $this->id);

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

        $sql = sprintf("SELECT * FROM `Store DC Data`  WHERE `Store Key`=%d", $this->id);

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

    function delete() {
        $this->deleted = false;
        $this->update_product_data();

        if ($this->data['Store Contacts'] == 0) {

            $sql = sprintf(
                "SELECT `Category Key` FROM `Category Dimension where `Category Store KEY`=%d", $this->id
            );

            include_once 'class.Category.php';
            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $category = new Category($row['Category Key']);
                    $category->delete();
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }


            $sql = sprintf(
                "DELETE FROM `Store Dimension` WHERE `Store Key`=%d", $this->id
            );
            $this->db->exec($sql);
            $this->deleted = true;
            $sql           = sprintf(
                "DELETE FROM `User Right Scope Bridge` WHERE `Scope`='Store' AND `Scope Key`=%d ", $this->id
            );
            $this->db->exec($sql);

            $sql = sprintf(
                "DELETE FROM `Store Data` WHERE `Store Key`=%d ", $this->id
            );
            $this->db->exec($sql);
            $sql = sprintf(
                "DELETE FROM `Store DC Data` WHERE `Store Key`=%d ", $this->id
            );
            $this->db->exec($sql);
            $sql = sprintf(
                "DELETE FROM `Invoice Category Dimension` WHERE `Invoice Category Store Key`=%d ", $this->id
            );
            $this->db->exec($sql);
            $sql = sprintf(
                "DELETE FROM `Category Dimension` WHERE `Category Store Key`=%d ", $this->id
            );
            $this->db->exec($sql);


            $history_key = $this->add_history(
                array(
                    'Action'           => 'deleted',
                    'History Abstract' => sprintf(
                        _('Store %d deleted'), $this->data['Store Name']
                    ),
                    'History Details'  => ''
                ), true
            );

            include_once 'class.Account.php';

            $hq = new Account();
            $hq->add_account_history($history_key);


            $this->deleted = true;
        } else {
            $this->msg = _('Store can not be deleted because it has contacts');

        }
    }

    function update_product_data() {

        /*
        $availability            = 'No Applicable';
        $sales_type              = 'No Applicable';
        $in_process              = 0;
        $public_sale             = 0;
        $private_sale            = 0;
        $discontinued            = 0;
        $not_for_sale            = 0;
        $sale_unknown            = 0;
        $availability_optimal    = 0;
        $availability_low        = 0;
        $availability_critical   = 0;
        $availability_outofstock = 0;
        $availability_unknown    = 0;
        $availability_surplus    = 0;



        $sql = sprintf(
            "SELECT sum(if(`Product Stage`='In process',1,0)) AS in_process,sum(if(`Product Sales Type`='Unknown',1,0)) AS sale_unknown,
		sum(if(`Product Main Type`='Discontinued',1,0)) AS discontinued,sum(if(`Product Main Type`='NoSale',1,0)) AS not_for_sale,
		sum(if(`Product Main Type`='Sale',1,0)) AS public_sale,sum(if(`Product Main Type`='Private',1,0)) AS private_sale,
		sum(if(`Product Availability State`='Unknown',1,0)) AS availability_unknown,sum(if(`Product Availability State`='Optimal',1,0)) AS availability_optimal,sum(if(`Product Availability State`='Low',1,0)) AS availability_low,sum(if(`Product Availability State`='Surplus',1,0)) AS availability_surplus,sum(if(`Product Availability State`='Critical',1,0)) AS availability_critical,sum(if(`Product Availability State`='Out Of Stock',1,0)) AS availability_outofstock FROM `Product Dimension` WHERE `Product Store Key`=%d",
            $this->id
        );



       */

        $active_products       = 0;
        $suspended_products    = 0;
        $discontinued_products = 0;

        $elements_active_web_status_numbers = array(
            'For Sale'     => 0,
            'Out of Stock' => 0,
            'Offline'      => 0

        );


        //'InProcess','Active','Suspended','Discontinuing','Discontinued'

        $sql = sprintf(
            'SELECT count(*) AS num, `Product Status` FROM `Product Dimension` WHERE `Product Store Key`=%d GROUP BY `Product Status`',

            $this->id
        );

        //print $sql;

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Product Status'] == 'Active' or $row['Product Status'] == 'Discontinuing') {
                    $active_products += $row['num'];
                } elseif ($row['Product Status'] == 'Suspended') {
                    $suspended_products = $row['num'];
                } elseif ($row['Product Status'] == 'Discontinued') {
                    $discontinued_products = $row['num'];
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf(
            "SELECT count(*) AS num ,`Product Web State` AS web_state FROM  `Product Dimension` P WHERE `Product Store Key`=%d AND `Product Status` IN ('Active','Discontinuing') GROUP BY  `Product Web State`   ",
            $this->id

        );

        // print "$sql\n";

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['web_state'] == 'Discontinued') {
                    $row['web_state'] = 'Offline';
                }
                $elements_active_web_status_numbers[$row['web_state']] += $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        /*
        //print $sql;
        $result = mysql_query($sql);
        if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {


            $in_process              = $row['in_process'];
            $public_sale             = $row['public_sale'];
            $private_sale            = $row['private_sale'];
            $discontinued            = $row['discontinued'];
            $not_for_sale            = $row['not_for_sale'];
            $sale_unknown            = $row['sale_unknown'];
            $availability_optimal    = $row['availability_optimal'];
            $availability_low        = $row['availability_low'];
            $availability_critical   = $row['availability_critical'];
            $availability_outofstock = $row['availability_outofstock'];
            $availability_unknown    = $row['availability_unknown'];
            $availability_surplus    = $row['availability_surplus'];
        }

        */

        $this->update(
            array(
                'Store Active Products'         => $active_products,
                'Store Suspended Products'      => $suspended_products,
                'Store Discontinued Products'   => $discontinued_products,
                'Store Active Web For Sale'     => $elements_active_web_status_numbers['For Sale'],
                'Store Active Web Out of Stock' => $elements_active_web_status_numbers['Out of Stock'],
                'Store Active Web Offline'      => $elements_active_web_status_numbers['Offline']

            ), 'no_history'
        );


        /*
        $sql = sprintf(
            "UPDATE `Store Dimension` SET `Store In Process Products`=%d,`Store For Public Sale Products`=%d, `Store For Private Sale Products`=%d ,`Store Discontinued Products`=%d ,`Store Not For Sale Products`=%d ,`Store Unknown Sales State Products`=%d, `Store Optimal Availability Products`=%d , `Store Low Availability Products`=%d ,`Store Critical Availability Products`=%d ,`Store Out Of Stock Products`=%d,`Store Unknown Stock Products`=%d ,`Store Surplus Availability Products`=%d ,`Store New Products`=%d WHERE `Store Key`=%d  ",
            $in_process, $public_sale, $private_sale, $discontinued, $not_for_sale, $sale_unknown, $availability_optimal, $availability_low, $availability_critical, $availability_outofstock,
            $availability_unknown, $availability_surplus, $new, $this->id
        );
        // print "$sql\n";
        mysql_query($sql);
*/

    }

    function update_new_products() {

        $new = 0;
        $sql = sprintf(
            'SELECT count(*) AS num FROM `Product Dimension` WHERE `Product Store Key` =%d AND `Product Valid From` >= CURDATE() - INTERVAL 14 DAY', $this->id

        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $new = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $this->update(array('Store New Products' => $new), 'no_history');

    }

    function update_children_data() {
        $this->update_product_data();

    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        switch ($field) {
            case('Store Sticky Note'):
                $this->update_field_switcher('Sticky Note', $value);
                break;
            case('Sticky Note'):
                $this->update_field('Store '.$field, $value, 'no_null');
                $this->new_value = html_entity_decode($this->new_value);
                break;

            case('Store Code'):
            case('Store Name'):

                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _("Value can't be empty");
                }
                $this->update_field($field, $value, $options);
                break;


            default:
                $base_data = $this->base_data();
                if (array_key_exists($field, $base_data)) {
                    if ($value != $this->data[$field]) {
                        $this->update_field($field, $value, $options);
                    }
                } elseif (array_key_exists($field, $this->base_data('Store Data'))) {
                    $this->update_table_field($field, $value, $options, 'Store', 'Store Data', $this->id);
                } elseif (array_key_exists(
                    $field, $this->base_data('Store DC Data')
                )) {
                    $this->update_table_field(
                        $field, $value, $options, 'Store', 'Store DC Data', $this->id
                    );
                }

        }


    }

    function create_sr_category($parent_category_key, $suffix = '') {


        $parent_category = new Category($parent_category_key);
        if (!$parent_category->id) {
            return;
        }

        $data     = array(
            'Category Store Key' => $this->id,
            'Category Code'      => $this->data['Store Code'].($suffix != '' ? '.'.$suffix : ''),
            'Category Subject'   => 'Invoice',
            'Category Function'  => 'if(true)'
        );
        $category = $parent_category->create_children($data);
        if (!$category->new) {
            if ($suffix == '') {
                $this->sr_category_suffix = 2;
            } else {
                $this->sr_category_suffix++;
            }
            $this->create_sr_category(
                $parent_category_key, $this->sr_category_suffix
            );


        }


    }


    function update_orders_in_basket_data() {

        $data = array(
            'in_basket' => array(
                'number'    => 0,
                'amount'    => 0,
                'dc_amount' => 0
            ),
        );

        $sql = sprintf(
            "SELECT count(*) AS num,ifnull(sum(`Order Total Net Amount`),0) AS amount,ifnull(sum(`Order Total Net Amount`*`Order Currency Exchange`),0) AS dc_amount FROM `Order Dimension` WHERE `Order Store Key`=%d and `Order Number Items`>0 AND `Order Current Dispatch State`  IN ('In Process by Customer','In Process')  ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $data['in_basket']['number']    = $row['num'];
                $data['in_basket']['amount']    = $row['amount'];
                $data['in_basket']['dc_amount'] = $row['dc_amount'];


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }



        $data_to_update = array(
            'Store Orders In Basket Number'              => $data['in_basket']['number'],
            'Store Orders In Basket Amount'              => $data['in_basket']['amount'],
            'Store DC Orders In Basket Amount'           => $data['in_basket']['dc_amount'],



        );
        $this->update($data_to_update, 'no_history');
    }


    function update_orders_in_process_data() {

        $data = array(

            'in_process_paid'     => array(
                'number'    => 0,
                'amount'    => 0,
                'dc_amount' => 0
            ),
            'in_process_not_paid' => array(
                'number'    => 0,
                'amount'    => 0,
                'dc_amount' => 0
            )
        );
        $sql = sprintf(
            'SELECT `Order Current Dispatch State`,count(*) AS num,ifnull(sum(`Order Total Net Amount`),0) AS amount,ifnull(sum(`Order Total Net Amount`*`Order Currency Exchange`),0) AS dc_amount FROM `Order Dimension` WHERE `Order Store Key`=%d  AND `Order Current Dispatch State` ="Submitted by Customer"  AND `Order Current Payment State`="Paid" ',
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $data['in_process_paid']['number'] += $row['num'];
                $data['in_process_paid']['amount'] += $row['amount'];
                $data['in_process_paid']['dc_amount'] += $row['dc_amount'];


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $sql = sprintf(
            'SELECT `Order Current Dispatch State`,count(*) AS num,ifnull(sum(`Order Total Net Amount`) ,0)AS amount,ifnull(sum(`Order Total Net Amount`*`Order Currency Exchange`),0) AS dc_amount FROM `Order Dimension` WHERE `Order Store Key`=%d  AND `Order Current Dispatch State`="Submitted by Customer"  AND `Order Current Payment State`!="Paid" ',
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $data['in_process_not_paid']['number'] += $row['num'];
                $data['in_process_not_paid']['amount'] += $row['amount'];
                $data['in_process_not_paid']['dc_amount'] += $row['dc_amount'];


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $data_to_update = array(
            'Store Orders In Process Paid Number'        => $data['in_process_paid']['number'],
            'Store Orders In Process Paid Amount'        => $data['in_process_paid']['amount'],
            'Store DC Orders In Process Paid Amount'     => $data['in_process_paid']['dc_amount'],
            'Store Orders In Process Not Paid Number'    => $data['in_process_not_paid']['number'],
            'Store Orders In Process Not Paid Amount'    => $data['in_process_not_paid']['amount'],
            'Store DC Orders In Process Not Paid Amount' => $data['in_process_not_paid']['dc_amount'],


        );
        $this->update($data_to_update, 'no_history');
    }




    function update_orders_in_warehouse_data() {

        $data = array(
            'warehouse' => array(
                'number'    => 0,
                'amount'    => 0,
                'dc_amount' => 0
            ),
        );




        $sql = sprintf(
            "SELECT count(*) AS num,ifnull(sum(`Order Total Net Amount`),0) AS amount,ifnull(sum(`Order Total Net Amount`*`Order Currency Exchange`),0) AS dc_amount FROM `Order Dimension` WHERE `Order Store Key`=%d  AND `Order Current Dispatch State`  IN ( 'Ready to Pick', 'Picking & Packing', 'Ready to Ship', 'Packing')  ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $data['warehouse']['number']    = $row['num'];
                $data['warehouse']['amount']    = $row['amount'];
                $data['warehouse']['dc_amount'] = $row['dc_amount'];


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }



        $data_to_update = array(
            'Store Orders In Warehouse Number'              => $data['warehouse']['number'],
            'Store Orders In Warehouse Amount'              => $data['warehouse']['amount'],
            'Store DC Orders In Warehouse Amount'           => $data['warehouse']['dc_amount'],



        );
        $this->update($data_to_update, 'no_history');
    }


    function update_orders_packed_data() {

        $data = array(
            'packed' => array(
                'number'    => 0,
                'amount'    => 0,
                'dc_amount' => 0
            ),
        );




        $sql = sprintf(
            "SELECT count(*) AS num,ifnull(sum(`Order Total Net Amount`),0) AS amount,ifnull(sum(`Order Total Net Amount`*`Order Currency Exchange`),0) AS dc_amount FROM `Order Dimension` WHERE `Order Store Key`=%d  AND `Order Current Dispatch State` ='Packed'  ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $data['packed']['number']    = $row['num'];
                $data['packed']['amount']    = $row['amount'];
                $data['packed']['dc_amount'] = $row['dc_amount'];


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }



        $data_to_update = array(
            'Store Orders Packed Number'              => $data['packed']['number'],
            'Store Orders Packed Amount'              => $data['packed']['amount'],
            'Store DC Orders Packed Amount'           => $data['packed']['dc_amount'],



        );


        $this->update($data_to_update, 'no_history');
    }


    function update_orders_ready_to_ship_data() {

        $data = array(
            'ready_to_ship' => array(
                'number'    => 0,
                'amount'    => 0,
                'dc_amount' => 0
            ),
        );




        $sql = sprintf(
            "SELECT count(*) AS num,ifnull(sum(`Order Total Net Amount`),0) AS amount,ifnull(sum(`Order Total Net Amount`*`Order Currency Exchange`),0) AS dc_amount FROM `Order Dimension` WHERE `Order Store Key`=%d  AND `Order Current Dispatch State` ='Packed Done'  ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $data['ready_to_ship']['number']    = $row['num'];
                $data['ready_to_ship']['amount']    = $row['amount'];
                $data['ready_to_ship']['dc_amount'] = $row['dc_amount'];


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }



        $data_to_update = array(
            'Store Orders In Dispatch Area Number'              => $data['ready_to_ship']['number'],
            'Store Orders In Dispatch Area Amount'              => $data['ready_to_ship']['amount'],
            'Store DC Orders In Dispatch Area Amount'           => $data['ready_to_ship']['dc_amount'],



        );
        $this->update($data_to_update, 'no_history');
    }


    function update_orders() {

        $this->update_orders_in_basket_data();
        $this->update_orders_in_process_data();
        $this->update_orders_in_warehouse_data();
        $this->update_orders_packed_data();
        $this->update_orders_ready_to_ship_data();




        $this->data['Store Total Acc Orders']  = 0;
        $this->data['Store Dispatched Orders'] = 0;
        $this->data['Store Cancelled Orders']  = 0;
        $this->data['Store Orders In Process'] = 0;
        $this->data['Store Unknown Orders']    = 0;
        $this->data['Store Suspended Orders']  = 0;

        $this->data['Store Total Acc Invoices']      = 0;
        $this->data['Store Invoices']                = 0;
        $this->data['Store Refunds']                 = 0;
        $this->data['Store Paid Invoices']           = 0;
        $this->data['Store Paid Refunds']            = 0;
        $this->data['Store Partially Paid Invoices'] = 0;
        $this->data['Store Partially Paid Refunds']  = 0;

        $this->data['Store Total Acc Delivery Notes']         = 0;
        $this->data['Store Ready to Pick Delivery Notes']     = 0;
        $this->data['Store Picking Delivery Notes']           = 0;
        $this->data['Store Packing Delivery Notes']           = 0;
        $this->data['Store Ready to Dispatch Delivery Notes'] = 0;
        $this->data['Store Dispatched Delivery Notes']        = 0;
        $this->data['Store Cancelled Delivery Notes']         = 0;


        $this->data['Store Delivery Notes For Orders']       = 0;
        $this->data['Store Delivery Notes For Replacements'] = 0;
        $this->data['Store Delivery Notes For Samples']      = 0;
        $this->data['Store Delivery Notes For Donations']    = 0;
        $this->data['Store Delivery Notes For Shortages']    = 0;


        $sql =
            "SELECT count(*) AS `Store Total Acc Orders`,sum(IF(`Order Current Dispatch State`='Dispatched',1,0 )) AS `Store Dispatched Orders` ,sum(IF(`Order Current Dispatch State`='Suspended',1,0 )) AS `Store Suspended Orders`,sum(IF(`Order Current Dispatch State`='Cancelled',1,0 )) AS `Store Cancelled Orders`,sum(IF(`Order Current Dispatch State`='Unknown',1,0 )) AS `Store Unknown Orders` FROM `Order Dimension`   WHERE `Order Store Key`="
            .$this->id;

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->data['Store Total Acc Orders']  = $row['Store Total Acc Orders'];
                $this->data['Store Dispatched Orders'] = $row['Store Dispatched Orders'];
                $this->data['Store Cancelled Orders']  = $row['Store Cancelled Orders'];
                $this->data['Store Unknown Orders']    = $row['Store Unknown Orders'];
                $this->data['Store Suspended Orders']  = $row['Store Suspended Orders'];

                $this->data['Store Orders In Process'] =
                    $this->data['Store Total Acc Orders'] - $this->data['Store Dispatched Orders'] - $this->data['Store Cancelled Orders'] - $this->data['Store Unknown Orders']
                    - $this->data['Store Suspended Orders'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        $sql =
            "SELECT count(*) AS `Store Total Invoices`,sum(IF(`Invoice Type`='Invoice',1,0 )) AS `Store Invoices`,sum(IF(`Invoice Type`!='Invoice',1,0 )) AS `Store Refunds` ,sum(IF(`Invoice Paid`='Yes' AND `Invoice Type`='Invoice',1,0 )) AS `Store Paid Invoices`,sum(IF(`Invoice Paid`='Partially' AND `Invoice Type`='Invoice',1,0 )) AS `Store Partially Paid Invoices`,sum(IF(`Invoice Paid`='Yes' AND `Invoice Type`!='Invoice',1,0 )) AS `Store Paid Refunds`,sum(IF(`Invoice Paid`='Partially' AND `Invoice Type`!='Invoice',1,0 )) AS `Store Partially Paid Refunds` FROM `Invoice Dimension`   WHERE `Invoice Store Key`="
            .$this->id;


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->data['Store Total Acc Invoices']      = $row['Store Total Invoices'];
                $this->data['Store Invoices']                = $row['Store Invoices'];
                $this->data['Store Paid Invoices']           = $row['Store Paid Invoices'];
                $this->data['Store Partially Paid Invoices'] = $row['Store Partially Paid Invoices'];
                $this->data['Store Refunds']                 = $row['Store Refunds'];
                $this->data['Store Paid Refunds']            = $row['Store Paid Refunds'];
                $this->data['Store Partially Paid Refunds']  = $row['Store Partially Paid Refunds'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $sql = "SELECT count(*) AS `Store Total Delivery Notes`,
             sum(IF(`Delivery Note State`='Cancelled'  OR `Delivery Note State`='Cancelled to Restock' ,1,0 )) AS `Store Returned Delivery Notes`,
             sum(IF(`Delivery Note State`='Ready to be Picked' ,1,0 )) AS `Store Ready to Pick Delivery Notes`,
             sum(IF(`Delivery Note State`='Picking & Packing' OR `Delivery Note State`='Picking' OR `Delivery Note State`='Picker Assigned' OR `Delivery Note State`='' ,1,0 )) AS `Store Picking Delivery Notes`,
             sum(IF(`Delivery Note State`='Packing' OR `Delivery Note State`='Packer Assigned' OR `Delivery Note State`='Picked' ,1,0 )) AS `Store Packing Delivery Notes`,
             sum(IF(`Delivery Note State`='Approved' OR `Delivery Note State`='Packed' ,1,0 )) AS `Store Ready to Dispatch Delivery Notes`,
             sum(IF(`Delivery Note State`='Dispatched' ,1,0 )) AS `Store Dispatched Delivery Notes`,
             sum(IF(`Delivery Note Type`='Replacement & Shortages' OR `Delivery Note Type`='Replacement' ,1,0 )) AS `Store Delivery Notes For Replacements`,
             sum(IF(`Delivery Note Type`='Replacement & Shortages' OR `Delivery Note Type`='Shortages' ,1,0 )) AS `Store Delivery Notes For Shortages`,
             sum(IF(`Delivery Note Type`='Sample' ,1,0 )) AS `Store Delivery Notes For Samples`,
             sum(IF(`Delivery Note Type`='Donation' ,1,0 )) AS `Store Delivery Notes For Donations`,
             sum(IF(`Delivery Note Type`='Order' ,1,0 )) AS `Store Delivery Notes For Orders`
             FROM `Delivery Note Dimension`   WHERE `Delivery Note Store Key`=".$this->id;

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->data['Store Total Acc Delivery Notes']         = $row['Store Total Delivery Notes'];
                $this->data['Store Ready to Pick Delivery Notes']     = $row['Store Ready to Pick Delivery Notes'];
                $this->data['Store Picking Delivery Notes']           = $row['Store Picking Delivery Notes'];
                $this->data['Store Packing Delivery Notes']           = $row['Store Packing Delivery Notes'];
                $this->data['Store Ready to Dispatch Delivery Notes'] = $row['Store Ready to Dispatch Delivery Notes'];
                $this->data['Store Dispatched Delivery Notes']        = $row['Store Dispatched Delivery Notes'];
                $this->data['Store Returned Delivery Notes']          = $row['Store Returned Delivery Notes'];
                $this->data['Store Delivery Notes For Replacements']  = $row['Store Delivery Notes For Replacements'];
                $this->data['Store Delivery Notes For Shortages']     = $row['Store Delivery Notes For Shortages'];
                $this->data['Store Delivery Notes For Samples']       = $row['Store Delivery Notes For Samples'];
                $this->data['Store Delivery Notes For Donations']     = $row['Store Delivery Notes For Donations'];
                $this->data['Store Delivery Notes For Orders']        = $row['Store Delivery Notes For Orders'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "UPDATE `Store Dimension` SET `Store Suspended Orders`=%d,`Store Dispatched Orders`=%d,`Store Cancelled Orders`=%d,`Store Orders In Process`=%d,`Store Unknown Orders`=%d
                    ,`Store Invoices`=%d ,`Store Refunds`=%d ,`Store Paid Invoices`=%d ,`Store Paid Refunds`=%d ,`Store Partially Paid Invoices`=%d ,`Store Partially Paid Refunds`=%d
                     ,`Store Ready to Pick Delivery Notes`=%d,`Store Picking Delivery Notes`=%d,`Store Packing Delivery Notes`=%d,`Store Ready to Dispatch Delivery Notes`=%d,`Store Dispatched Delivery Notes`=%d,`Store Returned Delivery Notes`=%d
                     ,`Store Delivery Notes For Replacements`=%d,`Store Delivery Notes For Shortages`=%d,`Store Delivery Notes For Samples`=%d,`Store Delivery Notes For Donations`=%d,`Store Delivery Notes For Orders`=%d
                     WHERE `Store Key`=%d", $this->data['Store Suspended Orders'], $this->data['Store Dispatched Orders'], $this->data['Store Cancelled Orders'],
            $this->data['Store Orders In Process'], $this->data['Store Unknown Orders'], $this->data['Store Invoices'], $this->data['Store Refunds'], $this->data['Store Paid Invoices'],
            $this->data['Store Paid Refunds'], $this->data['Store Partially Paid Invoices'], $this->data['Store Partially Paid Refunds'], $this->data['Store Ready to Pick Delivery Notes'],
            $this->data['Store Picking Delivery Notes'], $this->data['Store Picking Delivery Notes'], $this->data['Store Ready to Dispatch Delivery Notes'],
            $this->data['Store Dispatched Delivery Notes'], $this->data['Store Returned Delivery Notes'], $this->data['Store Delivery Notes For Replacements'],
            $this->data['Store Delivery Notes For Shortages'], $this->data['Store Delivery Notes For Samples'], $this->data['Store Delivery Notes For Donations'],
            $this->data['Store Delivery Notes For Orders'], $this->id
        );
        $this->db->exec($sql);

        $sql = sprintf(
            "UPDATE `Store Data` SET `Store Total Acc Orders`=%d,`Store Total Acc Invoices`=%d ,`Store Total Acc Delivery Notes`=%d WHERE `Store Key`=%d", $this->data['Store Total Acc Orders'],
            $this->data['Store Total Acc Invoices'], $this->data['Store Total Acc Delivery Notes'], $this->id
        );
        $this->db->exec($sql);


    }


    function update_sales_from_invoices($interval, $this_year = true, $last_year = true) {


        include_once 'utils/date_functions.php';
        list($db_interval, $from_date, $to_date, $from_date_1yb, $to_date_1yb) = calculate_interval_dates($this->db, $interval);

        if ($this_year) {

            $sales_data = $this->get_sales_data($from_date, $to_date);


            $data_to_update = array(
                "Store $db_interval Acc Invoiced Discount Amount" => $sales_data['discount_amount'],
                "Store $db_interval Acc Invoiced Amount"          => $sales_data['amount'],
                "Store $db_interval Acc Invoices"                 => $sales_data['invoices'],
                "Store $db_interval Acc Refunds"                  => $sales_data['refunds'],
                "Store $db_interval Acc Replacements"             => $sales_data['replacements'],
                "Store $db_interval Acc Delivery Notes"           => $sales_data['deliveries'],
                "Store $db_interval Acc Profit"                   => $sales_data['profit'],
                "Store $db_interval Acc Customers"                => $sales_data['customers'],
                "Store $db_interval Acc Repeat Customers"         => $sales_data['repeat_customers'],

                "Store DC $db_interval Acc Invoiced Amount"          => $sales_data['dc_amount'],
                "Store DC $db_interval Acc Invoiced Discount Amount" => $sales_data['dc_discount_amount'],
                "Store DC $db_interval Acc Profit"                   => $sales_data['dc_profit']
            );


            $this->update($data_to_update, 'no_history');
        }

        if ($from_date_1yb and $last_year) {


            $sales_data = $this->get_sales_data($from_date_1yb, $to_date_1yb);

            $data_to_update = array(
                "Store $db_interval Acc 1YB Invoiced Discount Amount"    => $sales_data['discount_amount'],
                "Store $db_interval Acc 1YB Invoiced Amount"             => $sales_data['amount'],
                "Store $db_interval Acc 1YB Invoices"                    => $sales_data['invoices'],
                "Store $db_interval Acc 1YB Refunds"                     => $sales_data['refunds'],
                "Store $db_interval Acc 1YB Replacements"                => $sales_data['replacements'],
                "Store $db_interval Acc 1YB Delivery Notes"              => $sales_data['deliveries'],
                "Store $db_interval Acc 1YB Profit"                      => $sales_data['profit'],
                "Store $db_interval Acc 1YB Customers"                   => $sales_data['customers'],
                "Store $db_interval Acc 1YB Repeat Customers"            => $sales_data['repeat_customers'],
                "Store DC $db_interval Acc 1YB Invoiced Amount"          => $sales_data['dc_amount'],
                "Store DC $db_interval Acc 1YB Invoiced Discount Amount" => $sales_data['dc_discount_amount'],
                "Store DC $db_interval Acc 1YB Profit"                   => $sales_data['dc_profit']
            );

            $this->update($data_to_update, 'no_history');


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

            $this->update(['Store Acc To Day Updated' => gmdate('Y-m-d H:i:s')], 'no_history');

        } elseif (in_array(
            $db_interval, [
                            '1 Year',
                            '1 Month',
                            '1 Week',
                            '1 Quarter'
                        ]
        )) {

            $this->update(['Store Acc Ongoing Intervals Updated' => gmdate('Y-m-d H:i:s')], 'no_history');
        } elseif (in_array(
            $db_interval, [
                            'Last Month',
                            'Last Week',
                            'Yesterday',
                            'Last Year'
                        ]
        )) {

            $this->update(['Store Acc Previous Intervals Updated' => gmdate('Y-m-d H:i:s')], 'no_history');
        }

    }

    function get_sales_data($from_date, $to_date) {

        $sales_data = array(
            'discount_amount'    => 0,
            'amount'             => 0,
            'invoices'           => 0,
            'refunds'            => 0,
            'replacements'       => 0,
            'deliveries'         => 0,
            'profit'             => 0,
            'dc_amount'          => 0,
            'dc_discount_amount' => 0,
            'dc_profit'          => 0,
            'customers'          => 0,
            'repeat_customers'   => 0,

        );


        $sql = sprintf(
            "SELECT count(DISTINCT `Invoice Customer Key`)  AS customers,sum(if(`Invoice Type`='Invoice',1,0))  AS invoices, sum(if(`Invoice Type`='Refund',1,0))  AS refunds,sum(`Invoice Items Discount Amount`) AS discounts,sum(`Invoice Total Net Amount`) net  ,sum(`Invoice Total Profit`) AS profit ,sum(`Invoice Items Discount Amount`*`Invoice Currency Exchange`) AS dc_discounts,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) dc_net  ,sum(`Invoice Total Profit`*`Invoice Currency Exchange`) AS dc_profit FROM `Invoice Dimension` WHERE `Invoice Store Key`=%d %s %s",
            $this->id, ($from_date ? sprintf('and `Invoice Date`>%s', prepare_mysql($from_date)) : ''), ($to_date ? sprintf('and `Invoice Date`<%s', prepare_mysql($to_date)) : '')

        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $sales_data['discount_amount']    = $row['discounts'];
                $sales_data['amount']             = $row['net'];
                $sales_data['profit']             = $row['profit'];
                $sales_data['invoices']           = $row['invoices'];
                $sales_data['refunds']            = $row['refunds'];
                $sales_data['dc_discount_amount'] = $row['dc_discounts'];
                $sales_data['dc_amount']          = $row['dc_net'];
                $sales_data['dc_profit']          = $row['dc_profit'];
                $sales_data['customers']          = $row['customers'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "SELECT count(*)  AS replacements FROM `Delivery Note Dimension` WHERE `Delivery Note Type` IN ('Replacement & Shortages','Replacement','Shortages') AND `Delivery Note Store Key`=%d %s %s",
            $this->id, ($from_date ? sprintf(
            'and `Delivery Note Date`>%s', prepare_mysql($from_date)
        ) : ''), ($to_date ? sprintf(
            'and `Delivery Note Date`<%s', prepare_mysql($to_date)
        ) : '')

        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $sales_data['replacements'] = $row['replacements'];


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "SELECT count(*)  AS delivery_notes FROM `Delivery Note Dimension` WHERE `Delivery Note Type` IN ('Order') AND `Delivery Note Store Key`=%d %s %s", $this->id, ($from_date ? sprintf(
            'and `Delivery Note Date`>%s', prepare_mysql($from_date)
        ) : ''), ($to_date ? sprintf(
            'and `Delivery Note Date`<%s', prepare_mysql($to_date)
        ) : '')

        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $sales_data['deliveries'] = $row['delivery_notes'];


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $sql = sprintf(
            " SELECT COUNT(*) AS repeat_customers FROM( SELECT count(*) AS invoices ,`Invoice Customer Key` FROM `Invoice Dimension` WHERE `Invoice Store Key`=%d  %s %s GROUP BY `Invoice Customer Key` HAVING invoices>1) AS tmp",
            $this->id, ($from_date ? sprintf('and `Invoice Date`>%s', prepare_mysql($from_date)) : ''), ($to_date ? sprintf('and `Invoice Date`<%s', prepare_mysql($to_date)) : '')
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $sales_data['repeat_customers'] = $row['repeat_customers'];


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $sales_data;


    }


    function update_dispatch_times($interval) {


        return;

        $to_date = '';

        list($db_interval, $from_date, $to_date, $from_date_1yb, $to_1yb) = calculate_interval_dates($this->db, $interval);

        setlocale(LC_ALL, 'en_GB');

        //   print "$interval\t\t $from_date\t\t $to_date\t\t $from_date_1yb\t\t $to_1yb\n";

        $this->data["Store $db_interval Average Dispatch Time"] = '';


        $sql    = sprintf(
            "SELECT `Order Date`,`Order Dispatched Date`,`Order Current Dispatch State` FROM `Order Dimension` WHERE `Order Store Key`=%d AND `Order Date`>=%s %s", $this->id,
            prepare_mysql($from_date), ($to_date ? sprintf('and `Order Date`<%s', prepare_mysql($to_date)) : '')

        );
        $result = mysql_query($sql);
        //print "$interval \n$sql\n";
        $number_samples = 0;
        $sum_interval   = 0;
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

            $interval = 0;
            if ($row['Order Dispatched Date'] != '') {
                $interval = strtotime($row['Order Dispatched Date']) - strtotime($row['Order Date']);

                //'In Process by Customer','In Process','Submitted by Customer','Ready to Pick','Picking & Packing','Ready to Ship','Dispatched','Unknown','Packing','Cancelled','Suspended'
            }
            //else if (!in_array($row['Order Current Dispatch State'],array('In Process by Customer','Unknown','Packing','Cancelled','Suspended'))) {
            //  $interval=strtotime(gmdate('Y-m-d H:i:s'))-strtotime($row['Order Date']);
            // }

            if ($interval > 0) {
                $sum_interval += $interval;
                $number_samples++;
            }


        }

        if ($number_samples > 0) {
            $this->data["Store $db_interval Average Dispatch Time"] = $sum_interval / $number_samples;

        }
        $sql = sprintf(
            "update `Store Data` set `Store $db_interval Acc Average Dispatch Time`=%f where `Store Key`=%d ", $this->data["Store $db_interval Average Dispatch Time"]

            , $this->id
        );

        mysql_query($sql);


        //print "$sql\n\n";


    }

    function update_last_period_dispatch_times() {

        $this->update_dispatch_times('Yesterday');
        $this->update_dispatch_times('Last Week');
        $this->update_dispatch_times('Last Month');
    }

    function update_interval_dispatch_times() {
        $this->update_dispatch_times('3 Year');
        $this->update_dispatch_times('1 Year');
        $this->update_dispatch_times('6 Month');
        $this->update_dispatch_times('1 Quarter');
        $this->update_dispatch_times('1 Month');
        $this->update_dispatch_times('10 Day');
        $this->update_dispatch_times('1 Week');
    }

    function get_formatted_dispatch_time($interval) {


        $interval = addslashes($interval);

        return number(
            ($this->data["Store $interval Average Dispatch Time"] / 3600)
        );
    }

    function update_previous_years_data() {

        foreach (range(1, 5) as $i) {
            $data_iy_ago = $this->get_sales_data(
                date('Y-01-01 00:00:00', strtotime('-'.$i.' year')), date('Y-01-01 00:00:00', strtotime('-'.($i - 1).' year'))
            );


            $data_to_update = array(
                "Store $i Year Ago Invoiced Discount Amount"    => $data_iy_ago['discount_amount'],
                "Store $i Year Ago Invoiced Amount"             => $data_iy_ago['amount'],
                "Store $i Year Ago Invoices"                    => $data_iy_ago['invoices'],
                "Store $i Year Ago Refunds"                     => $data_iy_ago['refunds'],
                "Store $i Year Ago Replacements"                => $data_iy_ago['replacements'],
                "Store $i Year Ago Delivery Notes"              => $data_iy_ago['deliveries'],
                "Store $i Year Ago Profit"                      => $data_iy_ago['profit'],
                "Store DC $i Year Ago Invoiced Amount"          => $data_iy_ago['dc_amount'],
                "Store DC $i Year Ago Invoiced Discount Amount" => $data_iy_ago['dc_discount_amount'],
                "Store DC $i Year Ago Profit"                   => $data_iy_ago['dc_profit']
            );


            $this->update($data_to_update, 'no_history');
        }

        $this->update(['Store Acc Previous Intervals Updated' => gmdate('Y-m-d H:i:s')], 'no_history');


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
                "Store $i Quarter Ago Invoiced Discount Amount"    => $sales_data['discount_amount'],
                "Store $i Quarter Ago Invoiced Amount"             => $sales_data['amount'],
                "Store $i Quarter Ago Invoices"                    => $sales_data['invoices'],
                "Store $i Quarter Ago Refunds"                     => $sales_data['refunds'],
                "Store $i Quarter Ago Replacements"                => $sales_data['replacements'],
                "Store $i Quarter Ago Delivery Notes"              => $sales_data['deliveries'],
                "Store $i Quarter Ago Profit"                      => $sales_data['profit'],
                "Store DC $i Quarter Ago Invoiced Amount"          => $sales_data['dc_amount'],
                "Store DC $i Quarter Ago Invoiced Discount Amount" => $sales_data['dc_discount_amount'],
                "Store DC $i Quarter Ago Profit"                   => $sales_data['dc_profit'],

                "Store $i Quarter Ago 1YB Invoiced Discount Amount"    => $sales_data_1yb['discount_amount'],
                "Store $i Quarter Ago 1YB Invoiced Amount"             => $sales_data_1yb['amount'],
                "Store $i Quarter Ago 1YB Invoices"                    => $sales_data_1yb['invoices'],
                "Store $i Quarter Ago 1YB Refunds"                     => $sales_data_1yb['refunds'],
                "Store $i Quarter Ago 1YB Replacements"                => $sales_data_1yb['replacements'],
                "Store $i Quarter Ago 1YB Delivery Notes"              => $sales_data_1yb['deliveries'],
                "Store $i Quarter Ago 1YB Profit"                      => $sales_data_1yb['profit'],
                "Store DC $i Quarter Ago 1YB Invoiced Amount"          => $sales_data_1yb['dc_amount'],
                "Store DC $i Quarter Ago 1YB Invoiced Discount Amount" => $sales_data_1yb['dc_discount_amount'],
                "Store DC $i Quarter Ago 1YB Profit"                   => $sales_data_1yb['dc_profit']
            );
            $this->update($data_to_update, 'no_history');
        }

        $this->update(['Store Acc Previous Intervals Updated' => gmdate('Y-m-d H:i:s')], 'no_history');


    }

    function get_from_date($period) {
        return $this->update_sales_from_invoices($period);

    }

    function update_customer_activity_interval() {


        $losing_interval = false;


        $sigma_factor = 3.2906;//99.9% value assuming normal distribution
        $sql          = "select count(*) as num,avg((`Customer Order Interval`)+($sigma_factor*`Customer Order Interval STD`)) as a from `Customer Dimension` where `Customer Orders`>2";
        $result2      = mysql_query($sql);
        if ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
            if ($row2['num'] > 30) {
                $this->data['Store Losing Customer Interval'] = $row2['a'];
                $this->data['Store Lost Customer Interval']   = $this->data['Store Losing Customer Interval'] * 4.0 / 3.0;
            }
        }

        if (!$losing_interval) {
            $losing_interval = 5259487;
            $lost_interval   = 7889231;
        }
        $sql = sprintf(
            "UPDATE `Store Dimension` SET
                     `Store Losing Customer Interval`=%d,
                     `Store Lost Customer Interval`=%d
                     WHERE `Store Key`=%d ", $this->data["Store Losing Customer Interval"], $this->data["Store Lost Customer Interval"]

            , $this->id
        );
        //print "$sql\n";
        mysql_query($sql);

    }

    function update_email_campaign_data() {
        $sql = sprintf(
            "SELECT count(*) AS email_campaign FROM `Email Campaign Dimension` WHERE `Email Campaign Store Key`=%d  ", $this->id
        );

        $res   = mysql_query($sql);
        $sites = array();
        while ($row = mysql_fetch_assoc($res)) {
            $email_campaign = $row['email_campaign'];
        }

        $sql = sprintf(
            'UPDATE `Store Dimension` SET `Store Email Campaigns`=%d WHERE `Store Key`=%d', $email_campaign, $this->id
        );

    }

    function update_newsletter_data() {

    }

    function update_email_reminder_data() {

    }

    function update_deals_data() {

        $deals = 0;

        $sql   = sprintf(
            "SELECT count(*) AS num FROM `Deal Dimension` WHERE `Deal Store Key`=%d AND `Deal Status`='Active' ", $this->id
        );
        $res   = mysql_query($sql);
        $sites = array();
        if ($row = mysql_fetch_assoc($res)) {
            $deals = $row['num'];
        }

        $sql = sprintf(
            'UPDATE `Store Dimension` SET `Store Active Deals`=%d WHERE `Store Key`=%d', $deals, $this->id
        );
        mysql_query($sql);
        //print "$sql\n";
    }

    function update_campaings_data() {

        $campaings = 0;

        $sql   = sprintf(
            "SELECT count(*) AS num FROM `Deal Campaign Dimension` WHERE `Deal Campaign Store Key`=%d AND `Deal Campaign Status`='Active' ", $this->id
        );
        $res   = mysql_query($sql);
        $sites = array();
        if ($row = mysql_fetch_assoc($res)) {
            $campaings = $row['num'];
        }

        $sql = sprintf(
            'UPDATE `Store Dimension` SET `Store Active Deal Campaigns`=%d WHERE `Store Key`=%d', $campaings, $this->id
        );

        mysql_query($sql);
        //print "$sql\n";
    }

    function create_site($data) {


        $data['Site Store Key'] = $this->id;


        if ($data['Site Name'] == '') {
            $data['Site Name'] = $this->data['Store Name'];
        }

        if ($data['Site Code'] == '') {
            $data['Site Code'] = $this->data['Store Code'];
        }

        if (!array_key_exists('Site Contact Telephone', $data) or $data['Site Contact Telephone'] == '') {
            $data['Site Contact Telephone'] = $this->data['Store Telephone'];
        }
        if (!array_key_exists('Site Contact Address', $data) or $data['Site Contact Address'] == '') {
            $data['Site Contact Address'] = $this->data['Store Address'];
        }

        $data['editor'] = $this->editor;

        $site = new Site('new', $data);

        return $site;
    }

    function get_active_sites_keys() {
        $sql = sprintf(
            "SELECT `Site Key` FROM `Site Dimension` WHERE `Site Store Key`=%d AND `Site Active`='Yes' ", $this->id
        );

        $res   = mysql_query($sql);
        $sites = array();
        while ($row = mysql_fetch_assoc($res)) {
            $sites[$row['Site Key']] = $row['Site Key'];
        }
        //print "$sql\n";
        //print_r($sites);
        return $sites;
    }

    function get_sites_data($smarty = false) {
        $data = array();
        $sql  = sprintf(
            "SELECT  `Site Key`,`Site URL`,`Site Name` FROM `Site Dimension` WHERE `Site Store Key`=%d ", $this->id
        );
        $res  = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            if ($smarty) {
                $_row = array();
                foreach ($row as $key => $value) {
                    $_row[str_replace(' ', '', $key)] = $value;
                }

                $data[] = $_row;
            } else {
                $data[] = $row;
            }
        }

        return $data;
    }

    function get_site_keys() {

        $site_keys = array();


        $sql = sprintf(
            "SELECT  `Site Key` FROM `Site Dimension` WHERE `Site Store Key`=%d ", $this->id
        );
        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {

            $site_keys[$row['Site Key']] = $row['Site Key'];

        }


        return $site_keys;

    }

    function get_site_key() {

        $site_key = 0;


        $sql = sprintf(
            "SELECT  `Site Key` FROM `Site Dimension` WHERE `Site Store Key`=%d ", $this->id
        );
        $res = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res)) {

            $site_key = $row['Site Key'];

        }


        return $site_key;

    }

    function get_formatted_email_credentials($type) {

        $credentials = $this->get_email_credentials_data($type);

        $formatted_credentials = '';
        foreach ($credentials as $credential) {
            $formatted_credentials .= ','.$credential['Email Address'];
        }

        $formatted_credentials = preg_replace(
            '/^,/', '', $formatted_credentials
        );

        return $formatted_credentials;


    }

    function get_email_credentials_data($type = 'Newsletters') {
        $credentials = array();
        $sql         = sprintf(
            "SELECT * FROM `Email Credentials Dimension` C LEFT JOIN `Email Credentials Store Bridge` SB ON (SB.`Email Credentials Key`=C.`Email Credentials Key`) LEFT JOIN `Email Credentials Scope Bridge`  SCB  ON (SCB.`Email Credentials Key`=C.`Email Credentials Key`)    WHERE   `Scope`=%s AND `Store Key`=%d ",
            prepare_mysql($type), $this->id
        );
        //print $sql;
        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            $credentials[$row['Email Credentials Key']] = $row;
        }

        return $credentials;

    }

    function get_credential_type() {
        include_once 'class.EmailCredentials.php';
        $keys             = $this->get_email_credential_key();
        $email_credential = new EmailCredentials($keys);
        if ($email_credential->id) {
            return $email_credential->data['Email Provider'];
        } else {
            return false;
        }
    }

    function get_email_credential_key($type = 'Newsletters') {

        $sql = sprintf(
            "SELECT C.`Email Credentials Key` FROM `Email Credentials Dimension` C LEFT JOIN `Email Credentials Store Bridge` SB ON (SB.`Email Credentials Key`=C.`Email Credentials Key`) LEFT JOIN `Email Credentials Scope Bridge`  SCB  ON (SCB.`Email Credentials Key`=C.`Email Credentials Key`)    WHERE   `Scope`=%s AND `Store Key`=%d ",
            prepare_mysql($type), $this->id
        );

        $res = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res)) {
            return $row['Email Credentials Key'];
        } else {

            return false;
        }


    }

    function associate_email_credentials($email_credentials_key, $scope = 'Newsletters') {

        if (!$email_credentials_key) {
            $this->error = true;

            return;
        }

        $current_email_credentials_key = $this->get_email_credential_key();
        if ($email_credentials_key == $current_email_credentials_key) {
            return;
        }


        $sql = sprintf(
            "DELETE FROM `Email Credentials Store Bridge` WHERE `Store Key`=%d w ", $this->id
        );
        mysql_query($sql);
        $this->db->exec($sql);

        $sql = sprintf(
            "DELETE FROM `Email Credentials Scope Bridge` WHERE `Scope`='%s'", $scope
        );
        $this->db->exec($sql);

        include_once 'class.EmailCredentials.php';

        $old_email_credentials = new EmailCredentials(
            $current_email_credentials_key
        );
        $old_email_credentials->delete();

        $sql = sprintf(
            "INSERT INTO `Email Credentials Store Bridge` VALUES (%d,%d)", $email_credentials_key, $this->id
        );
        $this->db->exec($sql);

        $sql = sprintf(
            "INSERT INTO `Email Credentials Scope Bridge` VALUES (%d, '%s')", $email_credentials_key, $scope
        );
        $this->db->exec($sql);


        $this->updated  = true;
        $this->msg      = 'Updated';
        $this->newvalue = $email_credentials_key;


    }

    function post_add_history($history_key, $type = false) {

        if (!$type) {
            $type = 'Changes';
        }

        $sql = sprintf(
            "INSERT INTO  `Store History Bridge` (`Store Key`,`History Key`,`Type`) VALUES (%d,%d,%s)", $this->id, $history_key, prepare_mysql($type)
        );
        mysql_query($sql);

    }


    //$type='Newsletters'

    function add_campaign($data) {
        $data['Deal Campaign Store Key'] = $this->id;
        $campaign                        = new DealCampaign(
            'find create', $data
        );

        return $campaign;

    }

    function get_valid_to() {
        /*
	To do discintinued Store
	if($this->data['Store Record Type']=='Discontinued'){
			return $this->data['Store Valid To'];
		}else{
			return gmdate("Y-m-d H:i:s");
		}

		*/
        return gmdate("Y-m-d H:i:s");

    }

    function update_sales_averages() {

        include_once 'common_stat_functions.php';

        $sql = sprintf(
            "SELECT sum(`Sales`) AS sales,sum(`Availability`) AS availability  FROM `Order Spanshot Fact` WHERE `Store Key`=%d   GROUP BY `Date`;", $this->id
        );
        $res = mysql_query($sql);

        $counter_available = 0;
        $counter           = 0;
        $sum               = 0;
        while ($row = mysql_fetch_assoc($res)) {

            $sum += $row['sales'];
            $counter++;
            if ($row['sales'] == $row['availability']) {
                $counter_available++;
            }


        }


        if ($counter > 0) {
            $this->data['Store Number Days on Sale']   = $counter;
            $this->data['Store Avg Day Sales']         = $sum / $counter;
            $this->data['Store Number Days Available'] = $counter_available;

        } else {
            $this->data['Store Number Days on Sale']   = 0;
            $this->data['Store Avg Day Sales']         = 0;
            $this->data['Store Number Days Available'] = 0;


        }

        $sql        = sprintf(
            "SELECT sum(`Sales`) AS sales  FROM `Order Spanshot Fact` WHERE `Store Key`=%d AND sales>0  GROUP BY `Date`;", $this->id
        );
        $res        = mysql_query($sql);
        $data_sales = array();
        $max_value  = 0;
        $counter    = 0;
        $sum        = 0;
        while ($row = mysql_fetch_assoc($res)) {
            $data_sales[] = $row['sales'];
            $sum += $row['sales'];
            $counter++;
            if ($row['sales'] > $max_value) {
                $max_value = $row['sales'];
            }
        }


        if ($counter > 0) {


            $this->data['Store Number Days with Sales']  = $counter;
            $this->data['Store Avg with Sale Day Sales'] = $sum / $counter;
            $this->data['Store STD with Sale Day Sales'] = standard_deviation(
                $data_sales
            );
            $this->data['Store Max Day Sales']           = $max_value;
        } else {
            $this->data['Store Number Days with Sales']  = 0;
            $this->data['Store Avg with Sale Day Sales'] = 0;
            $this->data['Store STD with Sale Day Sales'] = 0;
            $this->data['Store Max Day Sales']           = 0;

        }

        $sql = sprintf(
            "UPDATE `Store Dimension` SET `Store Number Days on Sale`=%d,`Store Avg Day Sales`=%d,`Store Number Days Available`=%f,`Store Number Days with Sales`=%d,`Store Avg with Sale Day Sales`=%f,`Store STD with Sale Day Sales`=%f,`Store Max Day Sales`=%f WHERE `Store Key`=%d",
            $this->data['Store Number Days on Sale'], $this->data['Store Avg Day Sales'], $this->data['Store Number Days Available'], $this->data['Store Number Days with Sales'],
            $this->data['Store Avg with Sale Day Sales'], $this->data['Store STD with Sale Day Sales'], $this->data['Store Max Day Sales'], $this->id
        );
        mysql_query($sql);

    }

    function get_tax_rate() {
        $rate = 0;
        $sql  = sprintf(
            "SELECT `Tax Category Rate` FROM `Tax Category Dimension` WHERE `Tax Category Code`=%s", prepare_mysql($this->data['Store Tax Category Code'])
        );
        $res  = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res)) {
            $rate = $row['Tax Category Rate'];
        }

        return $rate;
    }

    function get_payment_account_key() {
        $payment_account_key = 0;
        $sql                 = sprintf(
            "SELECT PA.`Payment Account Key` FROM `Payment Account Dimension` PA LEFT JOIN `Payment Account Site Bridge` B ON (PA.`Payment Account Key`=B.`Payment Account Key`)  WHERE `Payment Type`='Account' AND `Store Key`=%d ",
            $this->id
        );
        // print $sql;
        $res = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res)) {
            $payment_account_key = $row['Payment Account Key'];
        }


        return $payment_account_key;

    }

    function get_payment_accounts_data() {
        $payment_accounts_data = array();
        $sql                   = sprintf(
            "SELECT *FROM `Payment Account Dimension` PA LEFT JOIN `Payment Account Site Bridge` B ON (PA.`Payment Account Key`=B.`Payment Account Key`) LEFT JOIN `Payment Service Provider Dimension` PSPD ON (PSPD.`Payment Service Provider Key`=PA.`Payment Service Provider Key`)  WHERE  `Status`='Active' AND `Store Key`=%d ",
            $this->id
        );
        // print $sql;
        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {


            if ($row['Payment Type'] == 'Account') {
                continue;
            }
            $payment_service_provider = new Payment_Service_Provider(
                $row['Payment Service Provider Key']
            );

            $payment_accounts_data[] = array(
                'key'                   => $row['Payment Account Key'],
                'code'                  => $row['Payment Account Code'],
                'type'                  => $row['Payment Type'],
                'service_provider_code' => $row['Payment Service Provider Code'],
                'service_provider_name' => $row['Payment Service Provider Name'],
                'valid_payment_methods' => join(
                    ',', preg_replace(
                           '/\s/', '', $payment_service_provider->get_valid_payment_methods()
                       )
                )

            );

        }


        return $payment_accounts_data;

    }

    function cancel_old_orders_in_basket() {
        include_once 'common_natural_language.php';

        if (!$this->data['Cancel Orders In Basket Older Than']) {
            return;
        }

        $date = gmdate(
            'Y-m-d H:i:s', strtotime(
                             sprintf(
                                 "now -%d seconds +0:00", $this->data['Cancel Orders In Basket Older Than']
                             )
                         )
        );

        $sql    = sprintf(
            "SELECT `Order Key` FROM `Order Dimension` WHERE  `Order Current Dispatch State`='In Process By Customer' AND `Order Store Key`=%d AND `Order Last Updated Date`<%s", $this->id,
            prepare_mysql($date)
        );
        $result = mysql_query($sql);
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $order         = new Order($row['Order Key']);
            $order->editor = $this->editor;
            $note          = sprintf(
                _(
                    'Order cancelled because has been untouched in the basket for more than %s'
                ), seconds_to_string(
                    $this->data['Cancel Orders In Basket Older Than']
                )
            );


            $order->cancel($note, false, true);

            //print $order->data['Order Date']." ".$order->data['Order Last Updated Date']." ".$order->data['Order Public ID']."  \n";
            // exit;
        }


    }

    function get_field_label($field) {

        switch ($field) {

            case 'Store Code':
                $label = _('Code');
                break;
            case 'Store Name':
                $label = _('Name');
                break;


            default:
                $label = $field;

        }

        return $label;

    }

    function create_timeseries($data, $fork_key = 0) {

        $data['Timeseries Parent']     = 'Store';
        $data['Timeseries Parent Key'] = $this->id;
        $data['editor']                = $this->editor;

        $timeseries = new Timeseries('find', $data, 'create');
        if ($timeseries->id) {
            require_once 'utils/date_functions.php';

            if ($this->data['Store Valid From'] != '') {
                $from = date('Y-m-d', strtotime($this->get('Valid From')));

            } else {
                $from = '';
            }

            if ($this->get('Store State') == 'Closed') {
                $to = $this->get('Valid To');
            } else {
                $to = date('Y-m-d');
            }


            $sql        = sprintf(
                'DELETE FROM `Timeseries Record Dimension` WHERE `Timeseries Record Timeseries Key`=%d AND `Timeseries Record Date`<%s ', $timeseries->id, prepare_mysql($from)
            );
            $update_sql = $this->db->prepare($sql);
            $update_sql->execute();
            if ($update_sql->rowCount()) {
                $timeseries->update(
                    array('Timeseries Updated' => gmdate('Y-m-d H:i:s')), 'no_history'
                );
            }

            $sql        = sprintf(
                'DELETE FROM `Timeseries Record Dimension` WHERE `Timeseries Record Timeseries Key`=%d AND `Timeseries Record Date`>%s ', $timeseries->id, prepare_mysql($to)
            );
            $update_sql = $this->db->prepare($sql);
            $update_sql->execute();
            if ($update_sql->rowCount()) {
                $timeseries->update(
                    array('Timeseries Updated' => gmdate('Y-m-d H:i:s')), 'no_history'
                );
            }

            if ($from and $to) {
                $this->update_timeseries_record($timeseries, $from, $to, $fork_key);
            }

            if ($timeseries->get('Timeseries Number Records') == 0) {
                $timeseries->update(
                    array('Timeseries Updated' => gmdate('Y-m-d H:i:s')), 'no_history'
                );
            }

        }

    }

    function update_timeseries_record($timeseries, $from, $to, $fork_key=false) {

        if ($timeseries->get('Type') == 'StoreSales') {

            $dates = date_frequency_range(
                $this->db, $timeseries->get('Timeseries Frequency'), $from, $to
            );

            if ($fork_key) {

                $sql = sprintf(
                    "UPDATE `Fork Dimension` SET `Fork State`='In Process' ,`Fork Operations Total Operations`=%d,`Fork Start Date`=NOW(),`Fork Result`=%d  WHERE `Fork Key`=%d ", count($dates),
                    $timeseries->id, $fork_key
                );

                $this->db->exec($sql);
            }
            $index = 0;
            foreach ($dates as $date_frequency_period) {
                $index++;
                $sales_data = $this->get_sales_data($date_frequency_period['from'], $date_frequency_period['to']);
                $_date      = gmdate('Y-m-d', strtotime($date_frequency_period['from'].' +0:00'));


                if ($sales_data['invoices'] > 0 or $sales_data['refunds'] > 0 or $sales_data['customers'] > 0 or $sales_data['amount'] != 0 or $sales_data['dc_amount'] != 0 or $sales_data['profit']
                    != 0 or $sales_data['dc_profit'] != 0
                ) {

                    list($timeseries_record_key, $date) = $timeseries->create_record(array('Timeseries Record Date' => $_date));

                    $sql = sprintf(
                        'UPDATE `Timeseries Record Dimension` SET `Timeseries Record Integer A`=%d ,`Timeseries Record Integer B`=%d ,`Timeseries Record Integer C`=%d ,`Timeseries Record Float A`=%.2f ,  `Timeseries Record Float B`=%f ,`Timeseries Record Float C`=%f ,`Timeseries Record Float D`=%f ,`Timeseries Record Type`=%s WHERE `Timeseries Record Key`=%d',
                        $sales_data['invoices'], $sales_data['refunds'], $sales_data['customers'], $sales_data['amount'], $sales_data['dc_amount'], $sales_data['profit'], $sales_data['dc_profit'],
                        prepare_mysql('Data'), $timeseries_record_key

                    );


                    //  print "$sql\n";

                    $update_sql = $this->db->prepare($sql);
                    $update_sql->execute();
                    if ($update_sql->rowCount() or $date == date('Y-m-d')) {
                        $timeseries->update(
                            array(
                                'Timeseries Updated' => gmdate(
                                    'Y-m-d H:i:s'
                                )
                            ), 'no_history'
                        );
                    }


                } else {
                    $sql = sprintf(
                        'DELETE FROM `Timeseries Record Dimension` WHERE `Timeseries Record Timeseries Key`=%d AND `Timeseries Record Date`=%s ', $timeseries->id, prepare_mysql($_date)
                    );

                    $update_sql = $this->db->prepare($sql);
                    $update_sql->execute();
                    if ($update_sql->rowCount()) {
                        $timeseries->update(
                            array(
                                'Timeseries Updated' => gmdate(
                                    'Y-m-d H:i:s'
                                )
                            ), 'no_history'
                        );

                    }

                }
                if ($fork_key) {
                    $skip_every = 1;
                    if ($index % $skip_every == 0) {
                        $sql = sprintf(
                            "UPDATE `Fork Dimension` SET `Fork Operations Done`=%d  WHERE `Fork Key`=%d ", $index, $fork_key
                        );
                        $this->db->exec($sql);

                    }

                }
                $timeseries->update_stats();

            }

        }


        if ($fork_key) {

            $sql = sprintf(
                "UPDATE `Fork Dimension` SET `Fork State`='Finished' ,`Fork Finished Date`=NOW(),`Fork Operations Done`=%d,`Fork Result`=%d WHERE `Fork Key`=%d ", $index, $timeseries->id, $fork_key
            );

            $this->db->exec($sql);

        }

    }


    function get($key = '') {

        global $account;

        if (!$this->id) {
            return '';
        }


        if (isset($this->data[$key])) {
            return $this->data[$key];
        }


        switch ($key) {

            case 'State':
                switch ($this->data['Store State']) {
                    case 'Normal':
                        return _('Open');
                        break;
                    case 'Closed':
                        return _('Closed');
                        break;
                    default:
                        break;
                }
                break;
            case('Currency Code'):
                include_once 'utils/natural_language.php';

                return currency_label(
                    $this->data['Store Currency Code'], $this->db
                );
                break;

            case('Currency Symbol'):
                include_once 'utils/natural_language.php';

                return currency_symbol($this->data['Store Currency Code']);
                break;
            case('Valid From'):

                return strftime(
                    "%a %e %b %Y", strtotime($this->data['Store Valid From'].' +0:00')
                );
                break;
            case('Valid To'):
                return strftime(
                    "%a %e %b %Y", strtotime($this->data['Store Valid To'].' +0:00')
                );
                break;
            case("Sticky Note"):
                return nl2br($this->data['Store Sticky Note']);
                break;
            case('Contacts'):
            case('Active Contacts'):
            case('New Contacts'):
            case('Lost Contacts'):
            case('Losing Contacts'):
            case('Contacts With Orders'):
            case('Active Contacts With Orders'):
            case('New Contacts With Orders'):
            case('Lost Contacts With Orders'):
            case('Losing Contacts With Orders'):
            case('Active Web For Sale'):
            case('Active Web Out of Stock'):
            case('Active Web Offline'):
                return number($this->data['Store '.$key]);
            case 'Percentage Active Web Out of Stock':
                return percentage($this->data['Store Active Web Out of Stock'], $this->data['Store Active Products']);
            case 'Percentage Active Web Offline':
                return percentage($this->data['Store Active Web Offline'], $this->data['Store Active Products']);

            case('Potential Customers'):
                return number(
                    $this->data['Store Active Contacts'] - $this->data['Store Active Contacts With Orders']
                );
            case('Total Users'):
                return number($this->data['Store Total Users']);
            case('All To Pay Invoices'):
                return $this->data['Store Total Acc Invoices'] - $this->data['Store Paid Invoices'] - $this->data['Store Paid Refunds'];
            case('All Paid Invoices'):
                return $this->data['Store Paid Invoices'] - $this->data['Store Paid Refunds'];
            case('code'):
                return $this->data['Store Code'];
                break;
            case('type'):
                return $this->data['Store Type'];
                break;
            case('Total Products'):
                return $this->data['Store For Sale Products'] + $this->data['Store In Process Products'] + $this->data['Store Not For Sale Products'] + $this->data['Store Discontinued Products']
                + $this->data['Store Unknown Sales State Products'];
                break;
            case('For Sale Products'):
                return number($this->data['Store For Sale Products']);
                break;
            case('For Public Sale Products'):
                return number($this->data['Store For Public Sale Products']);
                break;
            case('Families'):
                return number($this->data['Store Families']);
                break;
            case('Departments'):
                return number($this->data['Store Departments']);
                break;
            case('Percentage Active Contacts'):
                return percentage(
                    $this->data['Store Active Contacts'], $this->data['Store Contacts']
                );
            case('Percentage Total With Orders'):
                return percentage(
                    $this->data['Store Contacts With Orders'], $this->data['Store Contacts']
                );
            case 'Delta Today Start Orders In Warehouse Number':

                $start=$this->data['Store Today Start Orders In Warehouse Number'];
                $end=$this->data['Store Orders In Warehouse Number']+$this->data['Store Orders Packed Number']+$this->data['Store Orders In Dispatch Area Number'];

                $diff=$end-$start;

                $delta=($diff>0?'+':'').number($diff).delta_icon($end,$start,$inverse=true);


                return $delta;

            case 'Today Orders Dispatched':

                $number=0;

                $sql=sprintf('select count(*) as num from `Order Dimension` where `Order Store Key`=%d and `Order Current Dispatch State`="Dispatched" and `Order Dispatched Date`>%s   and  `Order Dispatched Date`<%s   ',
                             $this->id,
                             prepare_mysql(date('Y-m-d 00:00:00')),
                             prepare_mysql(date('Y-m-d 23:59:59'))
                             );

                if ($result=$this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $number=$row['num'];
                	}
                }else {
                	print_r($error_info=$this->db->errorInfo());
                	print "$sql\n";
                	exit;
                }


                return number($number);


        }


        if (preg_match('/^(DC Orders).*(Amount) Soft Minify$/', $key)) {

            $field = 'Store '.preg_replace('/ Soft Minify$/', '', $key);

            $suffix          = '';
            $fraction_digits = 'NO_FRACTION_DIGITS';
            $_amount         = $this->data[$field];


            $amount = money($_amount, $account->get('Account Currency'), $locale = false, $fraction_digits).$suffix;

            return $amount;
        }
        if (preg_match('/^(DC Orders).*(Amount|Profit)$/', $key)) {

            $field = 'Store '.$key;
            return money($this->data[$field],  $account->get('Account Currency'));

            return $amount;
        }
        if (preg_match('/^(DC Orders).*(Amount|Profit) Minify$/', $key)) {

            $field = 'Store '.preg_replace('/ Minify$/', '', $key);

            $suffix          = '';
            $fraction_digits = 'NO_FRACTION_DIGITS';
            if ($this->data[$field] >= 1000000) {
                $suffix          = 'M';
                $fraction_digits = 'DOUBLE_FRACTION_DIGITS';
                $_amount         = $this->data[$field] / 1000000;
            } elseif ($this->data[$field] >= 10000) {
                $suffix  = 'K';
                $_amount = $this->data[$field] / 1000;
            } elseif ($this->data[$field] > 100) {
                $fraction_digits = 'SINGLE_FRACTION_DIGITS';
                $suffix          = 'K';
                $_amount         = $this->data[$field] / 1000;
            } else {
                $_amount = $this->data[$field];
            }

            $amount = money($_amount, $account->get('Account Currency'), $locale = false, $fraction_digits).$suffix;

            return $amount;


        }

        if (preg_match('/^(Orders|Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Minify$/', $key)) {

            $field = 'Store '.preg_replace('/ Minify$/', '', $key);

            $suffix          = '';
            $fraction_digits = 'NO_FRACTION_DIGITS';
            if ($this->data[$field] >= 1000000) {
                $suffix          = 'M';
                $fraction_digits = 'DOUBLE_FRACTION_DIGITS';
                $_amount         = $this->data[$field] / 1000000;
            } elseif ($this->data[$field] >= 10000) {
                $suffix  = 'K';
                $_amount = $this->data[$field] / 1000;
            } elseif ($this->data[$field] > 100) {
                $fraction_digits = 'SINGLE_FRACTION_DIGITS';
                $suffix          = 'K';
                $_amount         = $this->data[$field] / 1000;
            } else {
                $_amount = $this->data[$field];
            }

            $amount = money($_amount, $this->get('Store Currency Code'), $locale = false, $fraction_digits).$suffix;

            return $amount;
        }



        if (preg_match('/^(Orders|Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Soft Minify$/', $key)) {

            $field = 'Store '.preg_replace('/ Soft Minify$/', '', $key);

            $suffix          = '';
            $fraction_digits = 'NO_FRACTION_DIGITS';
            $_amount         = $this->data[$field];


            $amount = money($_amount, $this->get('Store Currency Code'), $locale = false, $fraction_digits).$suffix;

            return $amount;
        }
        if (preg_match('/^(Last|Yesterday|Total|1|10|6|3|2|4|5|Year To|Quarter To|Month To|Today|Week To).*(Quantity Invoiced|Invoices) Minify$/', $key)) {

            $field = 'Store '.preg_replace('/ Minify$/', '', $key);

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
        if (preg_match('/^(Last|Yesterday|Total|1|10|6|3|2|4|5|Year To|Quarter To|Month To|Today|Week To).*(Quantity Invoiced|Invoices) Soft Minify$/', $key)) {
            $field   = 'Store '.preg_replace('/ Soft Minify$/', '', $key);
            $_number = $this->data[$field];

            return number($_number, 0);
        }

        if (preg_match('/^(Orders|Total|1).*(Amount|Profit)$/', $key)) {

            $amount = 'Store '.$key;

            return money($this->data[$amount], $this->get('Store Currency Code'));
        }
        if (preg_match(
                '/^(Total|1).*(Quantity (Ordered|Invoiced|Delivered|)|Customers|Customers Contacts)$/', $key
            ) or preg_match('/^(Active Customers|Orders .* Number)$/', $key)
        ) {

            $amount = 'Store '.$key;

            return number($this->data[$amount]);
        }
        if (preg_match(
            '/^Delivery Notes For (Orders|Replacements|Shortages|Samples|Donations)$/', $key
        )) {

            $amount = 'Store '.$key;

            return number($this->data[$amount]);
        }

        if (preg_match('/(Orders|Delivery Notes|Invoices) Acc$/', $key)) {

            $amount = 'Store '.$key;

            return number($this->data[$amount]);
        } elseif (preg_match(
            '/(Orders|Delivery Notes|Invoices|Refunds|Orders In Process|(Active|New|Suspended|Discontinuing|Discontinued) Products)$/', $key
        )) {

            $amount = 'Store '.$key;

            return number($this->data[$amount]);
        }


        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }

        if (array_key_exists('Store '.$key, $this->data)) {
            return $this->data['Store '.$key];
        }


    }

    function create_customer($data) {
        $this->new_customer = false;

        $data['editor']             = $this->editor;
        $data['Customer Store Key'] = $this->id;


        $address_fields = array(
            'Address Recipient'            => $data['Customer Main Contact Name'],
            'Address Organization'         => $data['Customer Company Name'],
            'Address Line 1'               => '',
            'Address Line 2'               => '',
            'Address Sorting Code'         => '',
            'Address Postal Code'          => '',
            'Address Dependent Locality'   => '',
            'Address Locality'             => '',
            'Address Administrative Area'  => '',
            'Address Country 2 Alpha Code' => $data['Customer Contact Address country'],

        );
        unset($data['Customer Contact Address country']);

        if (isset($data['Customer Contact Address addressLine1'])) {
            $address_fields['Address Line 1'] = $data['Customer Contact Address addressLine1'];
            unset($data['Customer Contact Address addressLine1']);
        }
        if (isset($data['Customer Contact Address addressLine2'])) {
            $address_fields['Address Line 2'] = $data['Customer Contact Address addressLine2'];
            unset($data['Customer Contact Address addressLine2']);
        }
        if (isset($data['Customer Contact Address sortingCode'])) {
            $address_fields['Address Sorting Code'] = $data['Customer Contact Address sortingCode'];
            unset($data['Customer Contact Address sortingCode']);
        }
        if (isset($data['Customer Contact Address postalCode'])) {
            $address_fields['Address Postal Code'] = $data['Customer Contact Address postalCode'];
            unset($data['Customer Contact Address postalCode']);
        }

        if (isset($data['Customer Contact Address dependentLocality'])) {
            $address_fields['Address Dependent Locality'] = $data['Customer Contact Address dependentLocality'];
            unset($data['Customer Contact Address dependentLocality']);
        }

        if (isset($data['Customer Contact Address locality'])) {
            $address_fields['Address Locality'] = $data['Customer Contact Address locality'];
            unset($data['Customer Contact Address locality']);
        }

        if (isset($data['Customer Contact Address administrativeArea'])) {
            $address_fields['Address Administrative Area'] = $data['Customer Contact Address administrativeArea'];
            unset($data['Customer Contact Address administrativeArea']);
        }

        //print_r($address_fields);
        // print_r($data);

        //exit;

        $customer = new Customer('new', $data, $address_fields);

        if ($customer->id) {
            $this->new_customer_msg = $customer->msg;

            if ($customer->new) {
                $this->new_customer = true;
                $this->update_customers_data();
            } else {
                $this->error = true;
                $this->msg   = $customer->msg;

            }

            return $customer;
        } else {
            $this->error = true;
            $this->msg   = $customer->msg;
        }
    }

    function update_customers_data() {

        $this->data['Store Contacts']                    = 0;
        $this->data['Store New Contacts']                = 0;
        $this->data['Store Contacts With Orders']        = 0;
        $this->data['Store Active Contacts']             = 0;
        $this->data['Store Losing Contacts']             = 0;
        $this->data['Store Lost Contacts']               = 0;
        $this->data['Store New Contacts With Orders']    = 0;
        $this->data['Store Active Contacts With Orders'] = 0;
        $this->data['Store Losing Contacts With Orders'] = 0;
        $this->data['Store Lost Contacts With Orders']   = 0;
        $this->data['Store Contacts Who Visit Website']  = 0;


        $sql = sprintf(
            "SELECT count(*) AS num FROM  `Customer Dimension`    WHERE   `Customer Number Web Logins`>0  AND `Customer Store Key`=%d  ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->data['Store Contacts Who Visit Website'] = $row['num'];

            } else {
                $this->data['Store Contacts Who Visit Website'] = 0;

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "SELECT count(*) AS num ,sum(IF(`Customer New`='Yes',1,0)) AS new,  sum(IF(`Customer Type by Activity`='Active'   ,1,0)) AS active, sum(IF(`Customer Type by Activity`='Losing',1,0)) AS losing, sum(IF(`Customer Type by Activity`='Lost',1,0)) AS lost  FROM   `Customer Dimension` WHERE `Customer Store Key`=%d ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->data['Store Contacts']        = $row['num'];
                $this->data['Store New Contacts']    = $row['new'];
                $this->data['Store Active Contacts'] = $row['active'];
                $this->data['Store Losing Contacts'] = $row['losing'];
                $this->data['Store Lost Contacts']   = $row['lost'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        $sql = sprintf(
            "SELECT count(*) AS num ,sum(IF(`Customer New`='Yes',1,0)) AS new,sum(IF(`Customer New`='Yes',1,0)) AS new,sum(IF(`Customer Type by Activity`='Active'   ,1,0)) AS active, sum(IF(`Customer Type by Activity`='Losing',1,0)) AS losing, sum(IF(`Customer Type by Activity`='Lost',1,0)) AS lost  FROM   `Customer Dimension` WHERE `Customer Store Key`=%d AND `Customer With Orders`='Yes'",
            $this->id
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->data['Store Contacts With Orders']        = $row['num'];
                $this->data['Store New Contacts With Orders']    = $row['new'];
                $this->data['Store Active Contacts With Orders'] = $row['active'];
                $this->data['Store Losing Contacts With Orders'] = $row['losing'];
                $this->data['Store Lost Contacts With Orders']   = $row['lost'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "UPDATE `Store Dimension` SET
                     `Store Contacts`=%d,
                     `Store New Contacts`=%d,
                     `Store Active Contacts`=%d ,
                     `Store Losing Contacts`=%d ,
                     `Store Lost Contacts`=%d ,

                     `Store Contacts With Orders`=%d,
                     `Store New Contacts With Orders`=%d,
                     `Store Active Contacts With Orders`=%d,
                     `Store Losing Contacts With Orders`=%d,
                     `Store Lost Contacts With Orders`=%d,
                     `Store Contacts Who Visit Website`=%d
                     WHERE `Store Key`=%d  ", $this->data['Store Contacts'], $this->data['Store New Contacts'], $this->data['Store Active Contacts'], $this->data['Store Losing Contacts'],
            $this->data['Store Lost Contacts'],

            $this->data['Store Contacts With Orders'], $this->data['Store New Contacts With Orders'], $this->data['Store Active Contacts With Orders'],
            $this->data['Store Losing Contacts With Orders'], $this->data['Store Lost Contacts With Orders'], $this->data['Store Contacts Who Visit Website'],

            $this->id
        );

        $this->db->exec($sql);

    }

    function create_product($data) {

        $this->new_product = false;

        $data['editor'] = $this->editor;


        //print_r($data);

        if (!isset($data['Product Code']) or $data['Product Code'] == '') {
            $this->error      = true;
            $this->msg        = _("Code missing");
            $this->error_code = 'product_code_missing';
            $this->metadata   = '';

            return;
        }

        $sql = sprintf(
            'SELECT count(*) AS num FROM `Product Dimension` WHERE `Product Code`=%s AND `Product Store Key`=%d AND `Product Status`!="Discontinued" ', prepare_mysql($data['Product Code']), $this->id

        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                if ($row['num'] > 0) {
                    $this->error      = true;
                    $this->msg        = sprintf(
                        _('Duplicated code (%s)'), $data['Product Code']
                    );
                    $this->error_code = 'duplicate_product_code_reference';
                    $this->metadata   = $data['Product Code'];

                    return;
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        if (!isset($data['Product Unit Label']) or $data['Product Unit Label'] == '') {


            $this->error      = true;
            $this->msg        = _('Unit label missing');
            $this->error_code = 'product_unit_label_missing';

            return;
        }

        if (!isset($data['Product Name']) or $data['Product Name'] == '') {


            $this->error      = true;
            $this->msg        = _('Product name missing');
            $this->error_code = 'product_name_missing';

            return;
        }


        if (!isset($data['Product Units Per Case']) or $data['Product Units Per Case'] == '') {
            $this->error      = true;
            $this->msg        = _('Units per outer missing');
            $this->error_code = 'sproduct_units_per_case_missing';

            return;
        }

        if (!is_numeric($data['Product Units Per Case']) or $data['Product Units Per Case'] < 0) {
            $this->error      = true;
            $this->msg        = sprintf(
                _('Invalid units per outer (%s)'), $data['Product Units Per Case']
            );
            $this->error_code = 'invalid_product_units_per_case';
            $this->metadata   = $data['Product Units Per Case'];

            return;
        }


        if (!isset($data['Product Price']) or $data['Product Price'] == '') {
            $this->error      = true;
            $this->msg        = _('Cost missing');
            $this->error_code = 'product_price_missing';

            return;
        }

        if (!is_numeric($data['Product Price']) or $data['Product Price'] < 0) {
            $this->error      = true;
            $this->msg        = sprintf(
                _('Invalid cost (%s)'), $data['Product Price']
            );
            $this->error_code = 'invalid_product_price';
            $this->metadata   = $data['Product Price'];

            return;
        }


        if (isset($data['Product Unit RRP']) and $data['Product Unit RRP'] != '') {
            if (!is_numeric($data['Product Unit RRP']) or $data['Product Unit RRP'] < 0) {
                $this->error      = true;
                $this->msg        = sprintf(
                    _('Invalid unit recommended RRP (%s)'), $data['Product Unit RRP']
                );
                $this->error_code = 'invalid_product_unit_rrp';
                $this->metadata   = $data['Product Unit RRP'];

                return;
            }
        }
        if ($data['Product Unit RRP'] != '') {
            $data['Product RRP'] = $data['Product Unit RRP'] * $data['Product Units Per Case'];
        } else {
            $data['Product RRP'] = '';
        }

        $data['Product Store Key'] = $this->id;


        $data['Product Currency'] = $this->data['Store Currency Code'];
        $data['Product Locale']   = $this->data['Store Locale'];


        if (array_key_exists('Family Category Code', $data)) {
            include_once 'class.Category.php';
            $root_category = new Category(
                $this->get('Store Family Category Key')
            );
            if ($root_category->id) {
                $root_category->editor = $this->editor;
                $family                = $root_category->create_category(
                    array('Category Code' => $data['Family Category Code'])
                );
                if ($family->id) {
                    $data['Product Family Category Key'] = $family->id;

                }
            }
            unset($data['Family Category Code']);
        }

        if (isset($data['Product Family Category Key'])) {
            $family_key = $data['Product Family Category Key'];
            unset($data['Product Family Category Key']);
        } else {
            $family_key = false;
        }


        if (isset($data['Parts']) and $data['Parts'] != '') {

            include_once 'class.Part.php';
            $product_parts = array();
            foreach (preg_split('/\,/', $data['Parts']) as $part_data) {
                $part_data = _trim($part_data);
                if (preg_match('/(\d+)x\s+/', $part_data, $matches)) {

                    $ratio     = $matches[1];
                    $part_data = preg_replace('/(\d+)x\s+/', '', $part_data);
                } else {
                    $ratio = 1;
                }

                $part = new Part(
                    'reference', _trim(
                                   $part_data
                               )
                );

                $product_parts[] = array(
                    'Ratio'    => $ratio,
                    'Part SKU' => $part->id,
                    'Note'     => ''
                );

            }

            $data['Product Parts'] = json_encode($product_parts);
        }


        if (isset($data['Product Parts'])) {

            include_once 'class.Part.php';
            $product_parts = json_decode($data['Product Parts'], true);

            if ($product_parts and is_array($product_parts)) {
                //print_r($product_parts);
                foreach ($product_parts as $product_part) {
                    if (!is_array($product_part) or !isset($product_part['Part SKU']) or !isset($product_part['Ratio']) or !isset($product_part['Note']) or !is_numeric(
                            $product_part['Part SKU']
                        ) or !is_string($product_part['Note'])
                    ) {

                        $this->error      = true;
                        $this->msg        = "Can't parse product parts";
                        $this->error_code = 'can_not_parse_product_parts';
                        $this->metadata   = '';

                        return;
                    }


                    $part = new Part($product_part['Part SKU']);

                    if (!$part->id) {

                        $this->error      = true;
                        $this->msg        = 'Part not found';
                        $this->error_code = 'part_not_found';
                        $this->metadata   = $product_part['Part SKU'];

                        return;

                    }


                    if (!is_numeric($product_part['Ratio']) or $product_part['Ratio'] < 0) {
                        $this->error      = true;
                        $this->msg        = sprintf(
                            _('Invalid parts per product (%s)'), $product_part['Ratio']
                        );
                        $this->error_code = 'invalid_parts_per_product';
                        $this->metadata   = array($product_part['Ratio']);

                        return;

                    }


                }


            } else {
                $this->error      = true;
                $this->msg        = "Can't parse product parts";
                $this->error_code = 'can_not_parse_product_parts';
                $this->metadata   = '';

                return;

            }


            $product_parts_data = $data['Product Parts'];
            unset($data['Product Parts']);

        } else {
            $product_parts_data = false;
        }


        $product = new Product('find', $data, 'create');


        if ($product->id) {
            $this->new_object_msg = $product->msg;

            if ($product->new) {
                $this->new_object  = true;
                $this->new_product = true;
                $this->update_product_data();

                if ($product_parts_data) {
                    $product->update_part_list(
                        $product_parts_data, 'no_history'
                    );


                }


                if ($product->get('Product Number of Parts') == 1) {
                    foreach ($product->get_parts('objects') as $part) {

                        // print_r($part);
                        $part->updated_linked_products();
                    }
                }


                if ($family_key) {
                    $product->update(
                        array('Product Family Category Key' => $family_key), 'no_history'
                    );
                }


                $page_data = array(
                    'Page Store Content Display Type'      => 'Template',
                    'Page Store Content Template Filename' => 'product',
                    'Page State'                           => 'Online'

                );

                foreach ($this->get_sites('objects') as $site) {

                    $product_page_key = $site->add_product_page(
                        $product->id, $page_data
                    );
                }


            } else {

                $this->error = true;
                if ($product->found) {

                    $this->error_code     = 'duplicated_field';
                    $this->error_metadata = json_encode(
                        array($product->duplicated_field)
                    );

                    if ($product->duplicated_field == 'Product Code') {
                        $this->msg = _("Duplicated product code");
                    } else {
                        $this->msg = 'Duplicated '.$product->duplicated_field;
                    }


                } else {
                    $this->msg = $product->msg;
                }
            }

            return $product;
        } else {
            $this->error = true;

            $this->msg = $product->msg;

        }

    }

    function get_sites($scope = 'keys') {


        if ($scope == 'objects') {
            include_once 'class.Site.php';
        }

        $sql = sprintf(
            "SELECT  `Site Key` FROM `Site Dimension` WHERE `Site Store Key`=%d ", $this->id
        );

        $sites = array();

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($scope == 'objects') {
                    $sites[$row['Site Key']] = new Site($row['Site Key']);
                } else {
                    $sites[$row['Site Key']] = $row['Site Key'];
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        return $sites;
    }

    function create_category($raw_data) {

        if (!isset($raw_data['Category Label']) or $raw_data['Category Label'] == '') {
            $raw_data['Category Label'] = $raw_data['Category Code'];
        }

        $data = array(
            'Category Code'           => $raw_data['Category Code'],
            'Category Label'          => $raw_data['Category Label'],
            'Category Scope'          => 'Product',
            'Category Subject'        => $raw_data['Category Subject'],
            'Category Store Key'      => $this->id,
            'Category Can Have Other' => 'No',
            'Category Locked'         => 'Yes',
            'Category Branch Type'    => 'Root',
            'editor'                  => $this->editor

        );

        $category = new Category('find create', $data);


        if ($category->id) {
            $this->new_category_msg = $category->msg;

            if ($category->new) {
                $this->new_category = true;

            } else {
                $this->error = true;
                $this->msg   = $category->msg;

            }

            return $category;
        } else {
            $this->error = true;
            $this->msg   = $category->msg;
        }

    }

    function create_website($data) {

        $this->new_object = false;

        $data['editor'] = $this->editor;


        $data['Website Store Key']  = $this->id;
        $data['Website Valid From'] = gmdate('Y-m-d H:i:s');

        $website = new Website('find', $data, 'create');

        if ($website->id) {
            $this->new_object_msg = $website->msg;

            if ($website->new) {
                $this->new_object = true;
                $this->update_websites_data();
            } else {
                $this->error = true;
                if ($website->found) {

                    $this->error_code     = 'duplicated_field';
                    $this->error_metadata = json_encode(
                        array($website->duplicated_field)
                    );

                    if ($website->duplicated_field == 'Website Code') {
                        $this->msg = _('Duplicated website code');
                    }
                    if ($website->duplicated_field == 'Website URL') {
                        $this->msg = _('Duplicated website URL');
                    } else {
                        $this->msg = _('Duplicated website name');
                    }


                } else {
                    $this->msg = $website->msg;
                }
            }

            return $website;
        } else {
            $this->error = true;
            $this->msg   = $website->msg;
        }
    }

    function update_websites_data() {


        $number_sites = 0;
        $sql          = sprintf(
            "SELECT count(*) AS number_sites FROM `Site Dimension` WHERE `Site Store Key`=%d ", $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_sites = $row['number_sites'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        $this->update(array('Store Websites' => $number_sites), 'no_history');


    }

    function get_sales_timeseries_sql() {

        $table = '`Order Spanshot Fact` TR ';
        $where = sprintf(' where `Store Key`=%d', $this->id);

        $order  = '`Date`';
        $fields = "`Sales`,`Sales DC`,`Availability`,`Customers`,`Invoices`";

        $sql = "select $fields from $table $where  order by $order ";

        return $sql;

    }


}


?>
