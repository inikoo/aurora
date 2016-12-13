<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Based in 2009 class.Product.php
 Created: 28 November 2016 at 10:01:35 GMT+8, Yiwu, China
 Copyright (c) 2016, Inikoo

 Version 3

*/


class Public_Product {

    function __construct($arg1 = false, $arg2 = false, $arg3 = false) {

        global $db;
        $this->db = $db;
        $this->id = false;

        $this->webpage = false;

        $this->table_name = 'Product';

        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }

        $this->get_data($arg1, $arg2, $arg3);


    }


    function get_data($key, $id, $aux_id = false) {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Product Dimension` WHERE `Product ID`=%d", $id
            );
            if ($this->data = $this->db->query($sql)->fetch()) {
                $this->id          = $this->data['Product ID'];
                $this->historic_id = $this->data['Product Current Key'];
            }
        } elseif ($key == 'store_code') {
            $sql = sprintf(
                "SELECT * FROM `Product Dimension` WHERE `Product Store Key`=%s  AND `Product Code`=%s", $id, prepare_mysql($aux_id)
            );
            if ($this->data = $this->db->query($sql)->fetch()) {
                $this->id          = $this->data['Product ID'];
                $this->historic_id = $this->data['Product Current Key'];
            }
        } elseif ($key == 'historic_key') {
            $sql = sprintf(
                "SELECT * FROM `Product History Dimension` WHERE `Product Key`=%s", $id
            );
            if ($this->data = $this->db->query($sql)->fetch()) {
                $this->historic_id = $this->data['Product Key'];
                $this->id          = $this->data['Product ID'];


                $sql = sprintf("SELECT * FROM `Product Dimension` WHERE `Product ID`=%d", $this->data['Product ID']);
                if ($row = $this->db->query($sql)->fetch()) {

                    foreach ($row as $key => $value) {
                        $this->data[$key] = $value;
                    }
                }


            }
        } else {

            sdasdas();
            exit ("wrong id in class.product get_data :$key  \n");

            return;
        }

        $sql = sprintf(
            "SELECT * FROM `Product Data` WHERE `Product ID`=%d", $this->id
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

        $sql = sprintf(
            'SELECT * FROM `Store Dimension` WHERE `Store Key`=%d ', $this->data['Product Store Key']
        );
        if ($row = $this->db->query($sql)->fetch()) {

            foreach ($row as $key => $value) {
                $this->data[$key] = $value;
            }
        }

    }

    function get($key, $arg1 = '') {

        switch ($key) {
            case 'Ordered Quantity':


                $sql = sprintf(
                    "SELECT `Order Quantity` FROM `Order Transaction Fact` WHERE `Order Key`=%d AND `Product ID`=%d", $arg1, $this->id
                );

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $ordered_quantity = $row['Order Quantity'];
                        if( $ordered_quantity ==0)  $ordered_quantity = '';
                    } else {
                        $ordered_quantity = '';

                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

                return $ordered_quantity;
                break;


            case 'Reminder Key':


            $sql=sprintf("select `Email Site Reminder Key` from `Email Site Reminder Dimension` where `Trigger Scope`='Back in Stock' and `Trigger Scope Key`=%d and `User Key`=%d and `Email Site Reminder In Process`='Yes' ",
                             $this->id,
                             $arg1

                );

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $reminder_key = $row['Email Site Reminder Key'];
                    } else {
                        $reminder_key = 0;

                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }




                return $reminder_key;
                break;

            case 'Favourite Key':

                $sql = sprintf(
                    "SELECT `Customer Favorite Product Key`  FROM  `Customer Favorite Product Bridge` WHERE `Customer Key`=%d AND `Product ID`=%d ", $arg1, $this->id
                );

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $favourite_key = $row['Customer Favorite Product Key'];
                    } else {
                        $favourite_key = 0;

                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

                return $favourite_key;
                break;


            case 'Name':

                if($this->data['Product Units Per Case']>1){
                    return $this->data['Product Units Per Case'].'x '.$this->data['Product Name'];
                }else{
                    return $this->data['Product Name'];
                }


                break;

            case 'Code':
            case 'Web State':
                return $this->data['Product '.$key];
                break;


            case 'Image':


                $image_key = $this->data['Product Main Image Key'];

                if ($image_key) {
                    $img = '/image_root.php?size=small&id='.$image_key;
                } else {
                    $img = '/art/nopic.png';

                }

                return $img;

                break;

            case 'Webpage Related Products':
            case 'Related Products':

                $related_products_data = $this->webpage->get_related_products_data();
                $related_products      = '';


                foreach ($related_products_data['links'] as $link) {
                    $related_products .= $link['code'].', ';
                }

                $related_products = preg_replace(
                    '/, $/', '', $related_products
                );

                return $related_products;


                break;

            case 'Webpage Key':
                if (!is_object($this->webpage)) {
                    $this->load_webpage();
                }

                return $this->webpage->id;

                break;
            case 'Webpage See Also':
            case 'See Also':

                if (!is_object($this->webpage)) {
                    $this->load_webpage();
                }

                $see_also_data = $this->webpage->get_see_also_data();
                $see_also      = '';
                if ($see_also_data['type'] == 'Auto') {
                    $see_also = _('Automatic').': ';
                }

                if (count($see_also_data['links']) == 0) {
                    $see_also .= ', '._('none');
                } else {
                    foreach ($see_also_data['links'] as $link) {
                        $see_also .= $link['code'].', ';
                    }
                }
                $see_also = preg_replace('/, $/', '', $see_also);

                return $see_also;


                break;

            case 'Webpage Name':
                if (!is_object($this->webpage)) {
                    $this->load_webpage();
                }

                return $this->webpage->get('Page Store Title');

                break;
            case 'Website Node Parent Key':
                if (!is_object($this->webpage)) {
                    $this->load_webpage();
                }

                return $this->webpage->get('Found In Page Key');

                break;
            case 'Product Website Node Parent Key':
                if (!is_object($this->webpage)) {
                    $this->load_webpage();
                }

                return $this->webpage->get('Page Found In Page Key');

                break;

            case 'Price':

                $price = preg_replace('/PLN/','zł ',money($this->data['Product Price'], $this->data['Store Currency Code']));

                if ($this->data['Product Units Per Case'] != 1) {

                    $price .= ' ('.preg_replace('/PLN/','zł ',money($this->data['Product Price'] / $this->data['Product Units Per Case'], $this->data['Store Currency Code'])).'/'.$this->data['Product Unit Label'].')';


                    //$price.=' ('.sprintf(_('%s per %s'), money($this->data['Product Price']/$this->data['Product Units Per Case'], $this->data['Store Currency Code']), $this->data['Product Unit Label']).')';
                }


                return $price;
                break;

            case 'Webpage RRP':
            case 'RRP':

                if ($this->data['Product RRP'] == '') {
                    return '';
                }

                $rrp = preg_replace('/PLN/','zł ',money($this->data['Product RRP'] / $this->data['Product Units Per Case'], $this->data['Store Currency Code']));
                if ($this->get('Product Units Per Case') != 1) {
                    $rrp .= '/'.$this->data['Product Unit Label'];
                }


                return $rrp;
                break;

            case 'Out of Stock Label':
                if ($this->data['Product Total Acc Quantity Ordered'] > 0) {




                    if($this->data['Product Next Supplier Shipment']!=''){
                        $title=_('Expected').': '.strftime("%a %e %b %Y",strtotime($this->data['Product Next Supplier Shipment'].' +0:00'));




                        $label=_('Out of stock').' <span style="font-size:80%" title="'.$title.'">('.$this->get('Next Supplier Shipment').')</span>';
                    }else{
                        $label=_('Out of stock');
                    }


                    return  $label;
                } else {
                    return _('Launching soon');
                }
                break;

            case 'Out of Stock Class':
                if ($this->data['Product Total Acc Quantity Ordered'] > 0) {
                    return 'out_of_stock';
                } else {
                    return 'launching_soon';
                }
                break;


            case 'Unit Type':
                if ($this->data['Product Unit Type'] == '') {
                    return '';
                }

                return _($this->data['Product Unit Type']);


                break;


            case 'Availability':

                if ($this->data['Product Availability State'] == 'OnDemand') {
                    return _('On demand');
                } else {
                    return number($this->data['Product Availability']);
                }
                break;

            case 'Product Next Supplier Shipment':

                return ($this->data['Product Availability State']=='0000-00-00 00:00:00'?'':$this->data['Product Availability State']);

                break;
            case 'Next Supplier Shipment':

                return strftime("%e %b %y",strtotime($this->data['Product Next Supplier Shipment'].' +0:00'));

                break;
            default:


        }

    }

    function load_webpage() {

        include_once 'class.Public_Webpage.php';
        $this->webpage = new Public_Webpage('scope', 'Product', $this->id);
    }


}


?>
