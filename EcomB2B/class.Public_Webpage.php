<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 November 2016 at 12:23:21 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


class Public_Webpage {

    /**
     * @var \PDO
     */
    public $db;

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
                "SELECT * FROM `Page Store Dimension` PS WHERE `Webpage Scope`=%s AND `Webpage Scope Key`=%d ", prepare_mysql($tag), $tag2
            );

        } elseif ($tipo == 'store_page_code') {
            $sql = sprintf(
                "SELECT * FROM `Page Store Dimension` PS WHERE `Webpage Code`=%s AND `Webpage Store Key`=%d ", prepare_mysql($tag2), $tag
            );
        } elseif ($tipo == 'website_code') {
            $sql = sprintf(
                "SELECT * FROM `Page Store Dimension` PS  WHERE `Webpage Code`=%s AND PS.`Webpage Website Key`=%d ", prepare_mysql($tag2), $tag
            );

        } else {
            $sql = sprintf("SELECT * FROM `Page Store Dimension` PS  WHERE  PS.`Page Key`=%d", $tag);
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Page Key'];


        }


    }


    function get($key) {

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

            case 'Webpage Browser Title':
            case 'Browser Title':

                $website      = get_object('Website', $this->get('Webpage Website Key'));
                $title_format = $website->get('Website Settings Browser Title Format');

                $placeholders = array(
                    '[Webpage]' => $this->data['Webpage Name'],
                    '[Website]' => $website->get('Webpage Name')
                );

                return strtr($title_format, $placeholders);

            case 'Meta Description':
                return $this->data['Webpage '.$key];


            case 'Webpage Website Key':
            case 'Webpage Meta Description':
            case 'Webpage Redirection Code':
            case 'Webpage State':
            case 'Webpage Code':
            case 'Webpage Scope':
            case 'Webpage Store Key':
            case 'Webpage Scope Key':
            case 'Webpage Website Key':
            case 'Website URL':
                return $this->data[$key];


            case 'Name':

                return $this->data['Webpage Name'];

            case 'URL':

                return $this->data['Webpage URL'];
                break;

            case 'CSS':
            case 'Published CSS':
                return $this->data['Page Store '.$key];

            case 'Content Data':
            case 'Content Published Data':
                if ($this->data['Page Store '.$key] == '') {
                    $content_data = false;
                } else {
                    $content_data = json_decode($this->data['Page Store '.$key], true);
                }

                return $content_data;

            case 'Navigation Data':


                if ($this->data['Webpage Code'] == 'client.sys') {
                    $website = get_object('Website', $this->get('Webpage Website Key'));

                    $navigation_data = array(
                        'show'        => true,
                        'breadcrumbs' => [
                            [
                                'link'  => 'https://'.$website->get('Website URL'),
                                'title' => _('Home'),
                                'label' => '<i class="fa fa-home"></i>'
                            ],
                            [
                                'link'  => 'https://'.$website->get('Website URL').'/clients.sys',
                                'title' => _('Customers'),
                                'label' => '<i class="fal fa-user"></i> <span class="hide_mobile">'._('Customers').'</span>'
                            ],
                            [
                                'link'  => '',
                                'title' => '',
                                'label' => '',
                                'class' => 'client_nav'
                            ]

                        ],
                        'next'        => false,
                        'prev'        => false,

                    );
                } elseif ($this->data['Webpage Code'] == 'clients.sys') {
                    $website = get_object('Website', $this->get('Webpage Website Key'));

                    $navigation_data = array(
                        'show'        => true,
                        'breadcrumbs' => [
                            [
                                'link'  => 'https://'.$website->get('Website URL'),
                                'title' => _('Home'),
                                'label' => '<i class="fa fa-home"></i>'
                            ],
                            [
                                'link'  => '',
                                'title' => _('Customers'),
                                'label' => '<i class="fal fa-user"></i> <span class="hide_mobile">'._('Customers').'</span>'
                            ]


                        ],
                        'next'        => false,
                        'prev'        => false,

                    );
                } elseif ($this->data['Webpage Code'] == 'profile.sys') {
                    $website = get_object('Website', $this->get('Webpage Website Key'));

                    $navigation_data = array(
                        'show'        => true,
                        'breadcrumbs' => [
                            [
                                'link'  => 'https://'.$website->get('Website URL'),
                                'title' => _('Home'),
                                'label' => '<i class="fa fa-home"></i>'
                            ],
                            [
                                'link'  => '',
                                'title' => _('Profile'),
                                'label' => '<i class="fal fa-cog"></i> '._('Profile')
                            ]


                        ],
                        'next'        => false,
                        'prev'        => false,

                    );
                }elseif ($this->data['Webpage Code'] == 'portfolio.sys') {
                    $website = get_object('Website', $this->get('Webpage Website Key'));

                    $navigation_data = array(
                        'show'        => true,
                        'breadcrumbs' => [
                            [
                                'link'  => 'https://'.$website->get('Website URL'),
                                'title' => _('Home'),
                                'label' => '<i class="fa fa-home"></i>'
                            ],
                            [
                                'link'  => '',
                                'title' => _('Portfolio'),
                                'label' => '<i class="fal fa-store"></i> '._('Portfolio')
                            ]


                        ],
                        'next'        => false,
                        'prev'        => false,

                    );
                } elseif ($this->data['Webpage Code'] == 'clients_orders.sys') {
                    $website = get_object('Website', $this->get('Webpage Website Key'));

                    $navigation_data = array(
                        'show'        => true,
                        'breadcrumbs' => [
                            [
                                'link'  => 'https://'.$website->get('Website URL'),
                                'title' => _('Home'),
                                'label' => '<i class="fa fa-home"></i>'
                            ],
                            [
                                'link'  => '',
                                'title' => _('Orders'),
                                'label' => '<i class="fal fa-shopping-cart"></i> '._('Orders')
                            ],


                        ],
                        'next'        => false,
                        'prev'        => false,

                    );
                } elseif ($this->data['Webpage Code'] == 'client_basket.sys' or $this->data['Webpage Code'] == 'client_order.sys') {
                    $website = get_object('Website', $this->get('Webpage Website Key'));

                    $navigation_data = array(
                        'show'        => true,
                        'breadcrumbs' => [
                            [
                                'link'  => 'https://'.$website->get('Website URL'),
                                'title' => _('Home'),
                                'label' => '<i class="fa fa-home"></i>'
                            ],
                            [
                                'link'  => 'https://'.$website->get('Website URL').'/clients.sys',
                                'title' => _('Customers'),
                                'label' => '<i class="fal fa-user"></i> <span class="hide_mobile">'._('Customers').'</span>'
                            ],
                            [
                                'link'  => '',
                                'title' => '',
                                'label' => '',
                                'class' => 'client_nav'
                            ],
                            [
                                'link'  => '',
                                'title' => '',
                                'label' => '',
                                'class' => 'order_nav'
                            ]

                        ],
                        'next'        => false,
                        'prev'        => false,

                    );
                } elseif ($this->data['Webpage '.$key] == '') {
                    $navigation_data = array(
                        'show'        => false,
                        'breadcrumbs' => array(),
                        'next'        => false,
                        'prev'        => false,

                    );
                } else {


                    $navigation_data = json_decode($this->data['Webpage '.$key], true);


                }

                return $navigation_data;


            case 'Discounts':


                switch ($this->data['Webpage Scope']) {
                    case 'Category Products':
                        $deals = array();
                        $sql   =
                            "SELECT `Deal Component Expiration Date`,`Deal Component Key`,`Deal Component Icon`,`Deal Name Label`,`Deal Term Label`,`Deal Component Allowance Label`  FROM `Deal Component Dimension`  left join  `Deal Dimension` on (`Deal Key`=`Deal Component Deal Key`)  left join  `Deal Campaign Dimension` Dcam on (`Deal Component Campaign Key`=Dcam.`Deal Campaign Key`)     WHERE `Deal Campaign Code`!='CU' and `Deal Component Allowance Target`='Category' AND `Deal Component Allowance Target Key`=? AND `Deal Component Status`='Active'";
                        $stmt  = $this->db->prepare($sql);
                        $stmt->execute(
                            array(
                                $this->data['Webpage Scope Key']
                            )
                        );
                        while ($row = $stmt->fetch()) {
                            $deals[] = array(
                                'key'             => $row['Deal Component Key'],
                                'icon'            => $row['Deal Component Icon'],
                                'name'            => $row['Deal Name Label'],
                                'until'           => $row['Deal Component Expiration Date'],
                                'until_formatted' => strftime("%a %e %b %Y", strtotime($row['Deal Component Expiration Date'].' ')),
                                'term'            => $row['Deal Term Label'],
                                'allowance'       => $row['Deal Component Allowance Label']
                            );
                        }


                        return array(
                            'show'  => (count($deals) == 0 ? false : true),
                            'deals' => $deals
                        );

                        break;

                    case 'Product':
                        $deals = array();

                        $categories = array();

                        $sql = "select `Category Key` from `Category Bridge` where   `Subject`='Product'   and `Subject Key`=? ";

                        $stmt = $this->db->prepare($sql);
                        $stmt->execute(
                            array($this->data['Webpage Scope Key'])
                        );
                        while ($row = $stmt->fetch()) {
                            $categories[$row['Category Key']] = $row['Category Key'];

                        }


                        if (count($categories) > 0) {
                            $sql = sprintf(
                                "SELECT `Deal Component Expiration Date`,`Deal Component Key`,`Deal Component Icon`,`Deal Name Label`,`Deal Term Label`,`Deal Component Allowance Label` 
FROM `Deal Component Dimension`   left join 
`Deal Dimension` on (`Deal Key`=`Deal Component Deal Key`)  left join `Deal Campaign Dimension` DCam on (`Deal Component Campaign Key`=DCam.`Deal Campaign Key`)  WHERE `Deal Campaign Code`!='CU' and  `Deal Component Allowance Target`='Category' AND `Deal Component Allowance Target Key` in (%s) AND `Deal Component Status`='Active'",
                                join($categories, ',')
                            );

                            if ($result = $this->db->query($sql)) {
                                foreach ($result as $row) {

                                    $deals[] = array(
                                        'key'             => $row['Deal Component Key'],
                                        'icon'            => $row['Deal Component Icon'],
                                        'name'            => $row['Deal Name Label'],
                                        'until'           => $row['Deal Component Expiration Date'],
                                        'until_formatted' => strftime("%a %e %b %Y", strtotime($row['Deal Component Expiration Date'].' +0:00')),
                                        'term'            => $row['Deal Term Label'],
                                        'allowance'       => $row['Deal Component Allowance Label']
                                    );


                                }
                            }

                        }


                        return array(
                            'show'  => (count($deals) == 0 ? false : true),
                            'deals' => $deals
                        );

                        break;
                    default:
                        return array(
                            'show'  => false,
                            'deals' => array()
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


