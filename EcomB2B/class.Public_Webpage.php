<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 November 2016 at 12:23:21 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


class Public_Webpage {

    function __construct($arg1 = false, $arg2 = false, $arg3 = false) {

        global $db;
        $this->id         = false;
        $this->db         = $db;
        $this->scope      = false;
        $this->scope_load = false;

        $this->table_name = 'Webpage';

        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }

        $this->get_data($arg1, $arg2, $arg3);


    }

    function get_data($tipo, $tag, $tag2 = false) {

        if ($tipo == 'scope') {
            /*
                        // Todo (Migration)
                        if ($tag == 'Category') {


                            $sql = sprintf('SELECT `Category Root Key` ,`Category Scope`, `Category Store Key` ,`Category Code` FROM `Category Dimension` WHERE `Category Key`=%d ', $tag2);
                            if ($result = $this->db->query($sql)) {
                                if ($row = $result->fetch()) {
                                    include_once('class.Store.php');
                                    $store = new Store($row['Category Store Key']);


                                    if ($row['Category Root Key'] == $store->get('Store Family Category Key')) {


                                        $sql = sprintf(
                                            "SELECT `Product Family Key` FROM `Product Family Dimension` WHERE `Product Family Store Key`=%d AND `Product Family Code`=%s", $row['Category Store Key'],
                                            prepare_mysql($row['Category Code'])
                                        );


                                        if ($result2 = $this->db->query($sql)) {
                                            if ($row2 = $result2->fetch()) {
                                                $tag  = 'Family';
                                                $tag2 = $row2['Product Family Key'];
                                            }
                                        }


                                    } elseif ($row['Category Root Key'] == $store->get('Store Department Category Key')) {


                                        $sql = sprintf(
                                            "SELECT `Product Department Key` FROM `Product Department Dimension` WHERE `Product Department Store Key`=%d AND `Product Department Code`=%s",
                                            $row['Category Store Key'], prepare_mysql($row['Category Code'])
                                        );


                                        if ($result2 = $this->db->query($sql)) {
                                            if ($row2 = $result2->fetch()) {
                                                $tag  = 'Department';
                                                $tag2 = $row2['Product Department Key'];
                                            }
                                        }


                                    }


                                }
                            } else {
                                print_r($error_info = $this->db->errorInfo());
                                print "$sql\n";
                                exit;
                            }

                        }


                        $sql = sprintf(
                            'SELECT *FROM `Page Store Dimension`  PS LEFT JOIN `Page Dimension` P  ON (P.`Page Key`=PS.`Page Key`) WHERE `Page Store Section Type`=%s  AND  `Page Parent Key`=%d ',
                            prepare_mysql($tag), $tag2
                        );
            */


            $sql = sprintf(
                "SELECT * FROM `Page Store Dimension` PS LEFT JOIN `Page Dimension` P  ON (P.`Page Key`=PS.`Page Key`) WHERE `Webpage Scope`=%s AND `Webpage Scope Key`=%d ", prepare_mysql($tag), $tag2
            );

        } elseif ($tipo == 'store_page_code') {
            $sql = sprintf(
                "SELECT * FROM `Page Store Dimension` PS LEFT JOIN `Page Dimension` P  ON (P.`Page Key`=PS.`Page Key`) WHERE `Page Code`=%s AND `Page Store Key`=%d ", prepare_mysql($tag2), $tag
            );
        } elseif ($tipo == 'site_code') {
            $sql = sprintf(
                "SELECT * FROM `Page Store Dimension` PS LEFT JOIN `Page Dimension` P  ON (P.`Page Key`=PS.`Page Key`) WHERE `Page Code`=%s AND PS.`Page Site Key`=%d ", prepare_mysql($tag2), $tag
            );

        } else {
            $sql = sprintf("SELECT * FROM `Page Store Dimension` PS LEFT JOIN `Page Dimension` P  ON (P.`Page Key`=PS.`Page Key`) WHERE  PS.`Page Key`=%d", $tag);
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Page Key'];


        }


    }

    function get_see_also() {


        include_once('class.Public_Webpage.php');

        $see_also = array();
        $sql      = sprintf(
            "SELECT `Page Store See Also Key`,`Correlation Type`,`Correlation Value` FROM  `Page Store See Also Bridge` WHERE `Page Store Key`=%d ORDER BY `Correlation Value` DESC ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                $see_also_page = new Public_Webpage($row['Page Store See Also Key']);


                if ($see_also_page->id) {

                    $see_also[] = $see_also_page;


                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $see_also;

    }


    function get($key, $arg1 = '') {

        switch ($key) {

            case 'Signature':
                $store = get_object('Store', $this->data['Webpage Store Key']);

                return $store->get('Store Email Template Signature');

                break;
            case 'Send Email Address':

                $store = get_object('Store', $this->data['Webpage Store Key']);

                return $store->get('Store Email');

                break;
            case 'Scope Metadata':

                if ($this->data['Webpage '.$key] == '') {
                    $content_data = false;
                } else {
                    $content_data = json_decode($this->data['Webpage '.$key], true);
                }

                return $content_data;
                break;

            case 'Webpage Website Key':
            case 'Webpage State':
            case 'Webpage Scope':
            case 'Webpage Store Key':
            case 'Webpage Scope Key':
            case 'Webpage Website Key':
            case 'Webpage Template Filename':
            case 'Website URL':
                return $this->data[$key];
                break;


            case 'Name':

                return $this->data['Webpage Name'];
                break;
            case 'URL':

                return $this->data['Webpage URL'];
                break;

            case 'CSS':
            case 'Published CSS':
                return $this->data['Page Store '.$key];
                break;
            case 'Content Data':
            case 'Content Published Data':
                if ($this->data['Page Store '.$key] == '') {
                    $content_data = false;
                } else {
                    $content_data = json_decode($this->data['Page Store '.$key], true);
                }

                return $content_data;
                break;

            case 'Image':
                if (!$this->scope_load) {
                    $this->load_scope();
                }
                //sasdasd asdasdasd asdasd
                if (is_object($this->scope)) {


                    $img = $this->scope->get('Image');

                } else {
                    $img = '/art/nopic.png';

                }


                return $img;


        }

    }

    function load_scope() {

        $this->scope_load = true;


        if ($this->data['Webpage Scope'] == 'Product') {
            include_once('class.Public_Product.php');
            $this->scope       = new Public_Product($this->data['Webpage Scope Key']);
            $this->scope_found = 'Product';

        } elseif ($this->data['Webpage Scope'] == 'Category Categories' or $this->data['Webpage Scope'] == 'Category Products') {
            include_once('class.Public_Category.php');

            $this->scope       = new Public_Category($this->data['Webpage Scope Key']);
            $this->scope_found = 'Category';

        }


    }

}


?>