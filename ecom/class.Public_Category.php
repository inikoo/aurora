<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 November 2016 at 17:49:06 GMT+8, Plane (Hangzhou - Kuala Lumpur)
 Copyright (c) 2016, Inikoo

 Version 3

*/


class Public_Category {

    function __construct($arg1 = false, $arg2 = false, $arg3 = false) {

        global $db;
        $this->db = $db;
        $this->id       = false;

        $this->webpage = false;


        $this->table_name = 'Category';

        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }
        $this->get_data($arg1, $arg2, $arg3);


    }


    function get_data($tipo, $tag, $tag2 = false) {



        switch ($tipo) {
            case 'root_key_code':
                $sql = sprintf(
                    "SELECT * FROM `Category Dimension` WHERE `Category Root Key`=%d AND `Category Code`=%s ", $tag, prepare_mysql($tag2)
                );
                break;
            case 'subject_code':
                $sql = sprintf(
                    "SELECT * FROM `Category Dimension` WHERE `Category Subject`=%s AND `Category Code`=%s ", prepare_mysql($tag), prepare_mysql($tag2)
                );
                break;
            default:
                $sql = sprintf(
                    "SELECT * FROM `Category Dimension` WHERE `Category Key`=%d", $tag
                );

                break;
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Category Key'];


            $sql = sprintf("SELECT * FROM `Product Category Dimension` WHERE `Product Category Key`=%d", $this->id);

            if ($result2 = $this->db->query($sql)) {
                if ($row = $result2->fetch()) {
                    $this->data = array_merge($this->data, $row);
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }


        }


    }

    function load_webpage(){

        include_once 'class.Public_Webpage.php';


        $this->webpage=new Public_Webpage('scope',($this->get('Category Subject')=='Category'?'Category Categories':'Category Products'),$this->id);
    }

    function old_load_webpage() {

        $page_key = 0;
        include_once 'class.Public_Page.php';


        $category_key = $this->id;

        include_once 'class.Store.php';
        $store = new Store($this->data['Category Store Key']);

        // Migration
        if ($this->data['Category Root Key'] == $store->get('Store Family Category Key')) {


            $sql = sprintf(
                "SELECT * FROM `Product Family Dimension` WHERE `Product Family Store Key`=%d AND `Product Family Code`=%s", $this->data['Category Store Key'],
                prepare_mysql($this->data['Category Code'])
            );


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {

                    $category_key = $row['Product Family Key'];
                }
            }


        }


        $sql = sprintf('SELECT `Page Key` FROM `Page Store Dimension` WHERE `Page Store Section Type`="Family"  AND  `Page Parent Key`=%d ', $category_key);

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $page_key = $row['Page Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        $this->webpage         = new Page($page_key );

        return $this->webpage;

    }

    function get($key) {

        switch ($key) {

            case 'Product Category Status':
            case 'Category Subject':
                return $this->data[$key];
                break;
            case 'Subject':
            case 'Code':
            case 'Scope':
            case 'Label':
                return $this->data['Category '.$key];
                break;
            case 'Description':
                return $this->data['Product Category '.$key];
                break;
            case 'Image':




                $image_key = $this->data['Category Main Image Key'];

                if ($image_key) {
                    $img = '/image_root.php?size=small&id='.$image_key;
                } else {
                    $img = '/art/nopic.png';

                }

                return $img;


        }

    }



    function get_object_name() {
        return $this->table_name;

    }





    function get_deal_components($scope = 'keys',$options='Active') {

        switch($options) {
        case 'Active':
            $where='AND `Deal Component Status`=\'Active\'';
            break;
        default:
            $where='';
            break;
        }


        $deal_components = array();


        $sql = sprintf(
            "SELECT `Deal Component Key` FROM `Deal Component Dimension` WHERE `Deal Component Allowance Target`='Category' AND `Deal Component Allowance Target Key`=%d $where",
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($scope == 'objects') {
                    $deal_components[$row['Deal Component Key']] = get_object('DealComponent',$row['Deal Component Key']);
                } else {
                    $deal_components[$row['Deal Component Key']] = $row['Deal Component Key'];
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }




        return $deal_components;


    }



}


?>