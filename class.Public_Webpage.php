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



            $sql = sprintf(
                "SELECT * FROM `Page Store Dimension` PS LEFT JOIN `Page Dimension` P  ON (P.`Page Key`=PS.`Page Key`) WHERE `Webpage Scope`=%s AND `Webpage Scope Key`=%d ", prepare_mysql($tag), $tag2
            );

        } elseif ($tipo == 'store_page_code') {
            $sql = sprintf(
                "SELECT * FROM `Page Store Dimension` PS LEFT JOIN `Page Dimension` P  ON (P.`Page Key`=PS.`Page Key`) WHERE `Webpage Code`=%s AND `Page Store Key`=%d ", prepare_mysql($tag2), $tag
            );
        } elseif ($tipo == 'website_code') {
            $sql = sprintf(
                "SELECT * FROM `Page Store Dimension` PS LEFT JOIN `Page Dimension` P  ON (P.`Page Key`=PS.`Page Key`) WHERE `Webpage Code`=%s AND PS.`Webpage Website Key`=%d ", prepare_mysql($tag2), $tag
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

            case 'Browser Title':
            case 'Meta Description':
                return $this->data['Webpage '.$key];

                break;

            case 'Webpage Website Key':
            case 'Webpage Browser Title':
            case 'Webpage Meta Description':
            case 'Webpage Redirection Code':
            case 'Webpage State':
            case 'Webpage Code':
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
            case 'Navigation Data':
                if ($this->data['Webpage '.$key] == '') {
                    $navigation_data = array(
                        'show'=>false,
                        'breadcrumbs' => array(),
                        'next'        => false,
                        'prev'    => false,

                    );
                } else {
                    $navigation_data = json_decode($this->data['Webpage '.$key], true);
                }





               // print_r(json_decode($this->data['Webpage '.$key], true));

               // exit('caca');

                return $navigation_data;
                break;



            case 'Discounts':


                switch ($this->data['Webpage Scope']){
                    case 'Category Products':
                        $deals=array();
                        $sql = sprintf(
                            "SELECT  `Deal Name Label`,`Deal Term Label`, `Deal Component Expiration Date`,`Deal Component Key`,`Deal Component Icon`,`Deal Component Allowance Label` 
                            FROM `Deal Component Dimension` left join `Deal Dimension` on (`Deal Key`=`Deal Component Deal Key`)
                            
                            WHERE 
                            `Deal Component Allowance Target`='Category' AND `Deal Component Allowance Target Key`=%d AND `Deal Component Status`='Active'",
                            $this->data['Webpage Scope Key']
                        );

                        if ($result = $this->db->query($sql)) {
                            foreach ($result as $row) {

                                $deals[]=array(
                                    'key'=>$row['Deal Component Key'],
                                    'icon'=>$row['Deal Component Icon'],
                                    'name'=>$row['Deal Name Label'],
                                    'until'=>$row['Deal Component Expiration Date'],
                                    'until_formatted'=>  strftime("%a %e %b %Y", strtotime($row['Deal Component Expiration Date'].' ')),
                                    'term'=>$row['Deal Term Label'],
                                    'allowance'=>$row['Deal Component Allowance Label']
                                );


                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            exit;
                        }

                        return array(
                            'show'=>(count($deals)==0?false:true),
                            'deals'=>$deals
                        );

                        break;

                    case 'Product':
                        $deals=array();

                        $categories=array();

                        $sql=sprintf('select `Category Key` from `Category Bridge` where   `Subject`="Product"   and `Subject Key`=%d ',$this->data['Webpage Scope Key']);
                        if ($result=$this->db->query($sql)) {
                        		foreach ($result as $row) {
                                    $categories[$row['Category Key']]=$row['Category Key'];
                        		}
                        }else {
                        		print_r($error_info=$this->db->errorInfo());
                        		print "$sql\n";
                        		exit;
                        }




                        if(count($categories)>0){
                            $sql = sprintf(
                                "SELECT `Deal Component Expiration Date`,`Deal Component Key`,`Deal Component Icon`,`Deal Name Label`,`Deal Term Label`,`Deal Component Allowance Label` 
                                FROM `Deal Component Dimension`  left join `Deal Dimension` on (`Deal Key`=`Deal Component Deal Key`) WHERE `Deal Component Allowance Target`='Category' AND `Deal Component Allowance Target Key` in (%s) AND `Deal Component Status`='Active'",
                                join($categories,',')
                            );

                            if ($result = $this->db->query($sql)) {
                                foreach ($result as $row) {

                                    $deals[]=array(
                                        'key'=>$row['Deal Component Key'],
                                        'icon'=>$row['Deal Component Icon'],
                                        'name'=>$row['Deal Name Label'],
                                        'until'=>$row['Deal Component Expiration Date'],
                                        'until_formatted'=>  strftime("%a %e %b %Y", strtotime($row['Deal Component Expiration Date'].' +0:00')),
                                        'term'=>$row['Deal Term Label'],
                                        'allowance'=>$row['Deal Component Allowance Label']
                                    );


                                }
                            } else {
                                print_r($error_info = $this->db->errorInfo());
                                exit;
                            }

                        }


                        return array(
                            'show'=>(count($deals)==0?false:true),
                            'deals'=>$deals
                        );

                        break;
                    default:
                        return array(
                            'show'=>false,
                            'deals'=>array()
                        );
                }


                break;

            case 'Image':
                if (!$this->scope_load) {
                    $this->load_scope();
                }

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