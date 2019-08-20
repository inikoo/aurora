<?php
/*
 File: Page.php

 This file contains the Page Class

 About:
 Author: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0 2018
*/
include_once 'class.DB_Table.php';

include_once 'class.Image.php';
include_once 'trait.ImageSubject.php';
include_once 'trait.NotesSubject.php';

include_once 'utils/website_functions.php';

class Page extends DB_Table {
    use ImageSubject, NotesSubject;

    var $new = false;
    var $logged = false;
    var $snapshots_taken = 0;
    var $set_title = false;
    var $set_currency = 'GBP';
    var $set_currency_exchange = 1;
    var $deleted = false;

    /** @var PDO  */
    var $db;

    function __construct($arg1 = false, $arg2 = false, $arg3 = false, $_db = false) {

        if (!$_db) {
            global $db;

            $this->db = $db;
        } else {
            $this->db = $_db;
        }

        $this->table_name    = 'Page';
        $this->ignore_fields = array('Page Key');
        $this->scope         = false;
        $this->scope_load    = false;

        $this->scope_found = '';


        if (!$arg1 and !$arg2) {
            $this->error = true;
            $this->msg   = 'No arguments';
        }
        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }
        if (is_string($arg1) and !$arg2) {
            $this->get_data('url', $arg1);

            return;
        }


        if (is_array($arg2) and preg_match('/create|new/i', $arg1)) {
            $this->find($arg2, $arg3.' create');

            return;
        }
        if (preg_match('/find/i', $arg1)) {
            $this->find($arg2, $arg3);

            return;
        }

        $this->get_data($arg1, $arg2, $arg3);

    }


    function get_data($tipo, $tag, $tag2 = false) {

        if (preg_match('/url|address|www/i', $tipo)) {
            $sql = sprintf(
                "SELECT * FROM `Page Dimension` WHERE  `Page URL`=%s", prepare_mysql($tag)
            );
        } elseif ($tipo == 'store_page_code') {
            $sql = sprintf(
                "SELECT * FROM `Page Store Dimension` PS LEFT JOIN `Page Dimension` P  ON (P.`Page Key`=PS.`Page Key`) WHERE `Page Code`=%s AND `Page Store Key`=%d ", prepare_mysql($tag2), $tag
            );
        } elseif ($tipo == 'website_code') {
            $sql = sprintf(
                "SELECT * FROM `Page Store Dimension`  PS LEFT JOIN `Page Dimension` P  ON (P.`Page Key`=PS.`Page Key`) WHERE `Webpage Code`=%s AND PS.`Webpage Website Key`=%d ", prepare_mysql($tag2), $tag
            );

        } elseif ($tipo == 'scope') {
            $sql = sprintf(
                "SELECT * FROM `Page Store Dimension` PS LEFT JOIN `Page Dimension` P  ON (P.`Page Key`=PS.`Page Key`) WHERE `Webpage Scope`=%s AND `Webpage Scope Key`=%d ", prepare_mysql($tag), $tag2

            );

        } elseif ($tipo == 'deleted') {
            $this->get_deleted_data($tag);

            return;
        } else {
            $sql = sprintf(
                "SELECT * FROM `Page Dimension` WHERE  `Page Key`=%d", $tag
            );
        }



       // print $sql;


        if ($this->data = $this->db->query($sql)->fetch()) {

            $this->id = $this->data['Page Key'];


            $this->type = $this->data['Page Type'];

            if ($this->type == 'Store') {
                $sql = sprintf("SELECT * FROM `Page Store Dimension` WHERE  `Page Key`=%d", $this->id);


                if ($result2 = $this->db->query($sql)) {
                    if ($row2 = $result2->fetch()) {
                        foreach ($row2 as $key => $value) {
                            $this->data[$key] = $value;
                        }
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


            } elseif ($this->type == 'Internal') {
                $sql = sprintf("SELECT * FROM `Page Internal Dimension` WHERE  `Page Key`=%d", $this->id);


                if ($result2 = $this->db->query($sql)) {
                    if ($row2 = $result2->fetch()) {
                        foreach ($row2 as $key => $value) {
                            $this->data[$key] = $value;
                        }
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


            }
        }


    }

    function get_deleted_data($tag) {

        $this->deleted = true;
        $sql           = sprintf(
            "SELECT * FROM `Page Store Deleted Dimension` WHERE `Page Key`=%d", $tag
        );

        // print $sql;


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Page Store Deleted Key'];


            if ($this->data['Page Store Deleted Metadata'] != '') {
                $deleted_data = json_decode(gzuncompress($this->data['Page Store Deleted Metadata']), true);
                foreach ($deleted_data['data'] as $key => $value) {
                    $this->data[$key] = $value;
                }
            }
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


        $sql = sprintf(
            "SELECT P.`Page Key` FROM `Page Store Dimension` P  WHERE `Webpage Code`=%s AND `Webpage Website Key`=%d ", prepare_mysql($raw_data['Webpage Code']), $raw_data['Webpage Website Key']
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->found     = true;
                $this->found_key = $row['Page Key'];
                $this->get_data('id', $this->found_key);
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        if (!$this->found and $create) {
            $this->create($raw_data);

        }


    }

    function create($raw_data, $migration_hack = false) {


        $this->new = false;
        if (!isset($raw_data['Page Code']) or $raw_data['Page Code'] == '') {
            $this->error = true;
            $this->msg   = 'No page code';

        }

        if (!isset($raw_data['Page URL']) or $raw_data['Page URL'] == '') {

            $raw_data['Page URL'] = "info.php?page=".$raw_data['Page Code'];
        }

        if (!isset($raw_data['Page Short Title']) or $raw_data['Page Short Title'] == '') {

            $raw_data['Page Short Title'] = $raw_data['Page Title'];
        }


        $data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $data[$key] = _trim($value);
            }
        }


        $sql = sprintf(
            "INSERT INTO `Page Dimension` (%s) values (%s)",
            '`'.join('`,`', array_keys($data)).'`',
            join(',', array_fill(0, count($data), '?'))
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


            if ($this->data['Page Type'] == 'Store') {
                $this->create_store_page($raw_data);

            }

            $sql = sprintf(
                "INSERT INTO `Page State Timeline`  (`Page Key`,`Website Key`,`Store Key`,`Date`,`State`,`Operation`) VALUES (%d,%d,%d,%s,%s,'Created') ", $this->id, $this->data['Webpage Website Key'], $this->data['Webpage Store Key'], prepare_mysql(gmdate('Y-m-d H:i:s')),
                prepare_mysql($this->data['Page State'])

            );
            $this->db->exec($sql);


        } else {
            $this->error = true;
            $this->msg   = 'Can not insert Page Dimension';
            print_r($stmt->errorInfo());
            exit();
        }


    }


    function create_store_page($raw_data) {

        $data = $this->store_base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $data[$key] = $value;
                if (is_string($value)) {
                    $data[$key] = _trim($value);
                } elseif (is_array($value)) {
                    $data[$key] = serialize($value);
                }
            }
        }


        $data['Page Key'] = $this->id;

        $sql = sprintf(
            "INSERT INTO `Page Store Dimension` (%s) values (%s)",
            '`'.join('`,`', array_keys($data)).'`',
            join(',', array_fill(0, count($data), '?'))
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



            $sql = sprintf(
                "INSERT INTO `Webpage Analytics Data` (`Webpage Analytics Webpage Key`) VALUES (%d)", $this->id
            );
            $this->db->exec($sql);

            $sql = sprintf(
                "INSERT INTO `Page Store Data Dimension` (`Page Key`) VALUES (%d)", $this->id
            );
            $this->db->exec($sql);





            $this->update_url();

            $this->update_see_also();

            $this->update_image_key();
            $this->refresh_cache();

            return $this;


        } else {
            $this->error = true;
            $this->msg   = 'Can not insert Page Store Dimension';
            print_r($stmt->errorInfo());
            //print "$sql\n";
            exit;
        }

    }

    function store_base_data() {
        $data = array();


        $sql = 'show columns from `Page Store Dimension`';
        foreach ($this->db->query($sql) as $row) {
            if (!in_array($row['Field'], $this->ignore_fields)) {
                $data[$row['Field']] = $row['Default'];
            }
        }


        return $data;
    }



    function update_url() {

        $website = get_object('website', $this->get('Webpage Website Key'));


        $this->update(array('Webpage URL' => 'https://'.$website->get('Website URL').'/'.strtolower($this->get('Code'))), 'no_history');


    }

    function get($key) {
        switch ($key) {


            case 'Website Registration Type':
            case 'Registration Type':

                $website = get_object('website', $this->get('Webpage Website Key'));

                return $website->get($key);


                break;
            case 'See Also':

                $see_also_data = $this->get_see_also_data();
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


            case 'Browser Title':

                return $this->data['Webpage '.$key];
                break;
            case 'State Icon':

                switch ($this->data['Webpage State']) {
                    case 'InProcess':
                        return '<i class="fa fa-fw fa-child" aria-hidden="true"></i>';
                    case 'Ready':
                        return '<i class="fa fa-fw  fa-check-circle" aria-hidden="true"></i>';
                    case 'Online':
                        return '<i class="fa fa-fw fa-rocket" aria-hidden="true"></i>';
                    case 'Offline':
                        return '<i class="fa fa-fw fa-rocket discreet fa-flip-vertical" aria-hidden="true"></i>';

                    default:
                        return $this->data['Webpage State'];
                }

                break;


            case 'State':

                switch ($this->data['Webpage State']) {
                    case 'InProcess':
                        return $this->get('State Icon').' '._('In process');
                    case 'Ready':
                        return $this->get('State Icon').' '._('Ready');
                    case 'Online':
                        return $this->get('State Icon').' '._('Online');
                    case 'Offline':
                        return '<span class="very_discreet">'.$this->get('State Icon').' '._('Offline').'</span>';

                    default:
                        return $this->data['Webpage State'];
                }

                break;

            case 'Send Email Address':
                $store = get_object('Store',$this->data['Webpage Store Key']);

                return $store->get('Store Email');

                break;
            case 'Send Email Signature':
                $store = get_object('Store',$this->data['Webpage Store Key']);

                return $store->get('Store Email Template Signature');

                break;

            case 'Email':
            case 'Company Name':
            case 'VAT Number':
            case 'Company Number':
            case 'Telephone':
            case 'Address':
            case 'Google Map URL':
                include_once('class.Store.php');
                $store = new Store($this->data['Webpage Store Key']);

                return $store->get($key);

                break;
            case 'Store Email':
            case 'Store Company Name':
            case 'Store VAT Number':
            case 'Store Company Number':
            case 'Store Telephone':
            case 'Store Address':
            case 'Store Google Map URL':
                include_once('class.Store.php');
                $store = new Store($this->data['Webpage Store Key']);

                return $store->get($key);

                break;

            case 'Template Filename':

                switch ($this->data['Webpage Template Filename']) {
                    case 'blank':
                        $template_label = _('Old template').' '._('unsupported');
                        break;
                    case 'categories_classic_showcase':
                        $template_label = _('Classic grid');
                        break;
                    case 'categories_showcase':
                        $template_label = _('Rigid grid');
                        break;
                    default:
                        $template_label = $this->data['Webpage Template Filename'];
                }

                return $template_label;
                break;
            case 'Publish':


                if ($this->data['Page Store Content Data'] != $this->data['Page Store Content Published Data']) {


                    return true;
                }

                if ($this->data['Page Store CSS'] != $this->data['Page Store Published CSS']) {


                    return true;
                }

                $this->load_scope();

                if ($this->scope_found == 'Category') {

                    $sql = sprintf(
                        'SELECT `Product Category Index Stack`,`Product Category Index Published Stack`,`Product Category Index Content Data`,`Product Category Index Content Published Data`  FROM  `Product Category Index`  WHERE `Product Category Index Category Key`=%d  ',
                        $this->scope->id
                    );

                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {
                            if ($row['Product Category Index Stack'] != $row['Product Category Index Published Stack']) {
                                return true;
                            }
                            if ($row['Product Category Index Content Data'] != $row['Product Category Index Content Published Data']) {
                                return true;
                            }
                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        print "$sql\n";
                        exit;
                    }


                }


                $sql = sprintf(
                    'SELECT `Webpage Related Product Order`,`Webpage Related Product Published Order`,`Webpage Related Product Content Data`,`Webpage Related Product Content Published Data`  FROM  `Webpage Related Product Bridge`  WHERE `Webpage Related Product Page Key`=%d  ',
                    $this->id
                );

                // print $sql;

                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        if ($row['Webpage Related Product Order'] != $row['Webpage Related Product Published Order']) {
                            return true;
                        }
                        if ($row['Webpage Related Product Content Data'] != $row['Webpage Related Product Content Published Data']) {
                            return true;
                        }
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                return false;

                break;
            case 'Navigation Data':
                if ($this->data['Webpage '.$key] == '') {
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
                break;
            case 'Content Data':
                if ($this->data['Page Store '.$key] == '') {
                    $content_data = false;
                } else {
                    $content_data = json_decode($this->data['Page Store '.$key], true);
                }

                return $content_data;
                break;


            case 'Scope Metadata':

                if ($this->data['Webpage '.$key] == '') {
                    $content_data = false;
                } else {
                    $content_data = json_decode($this->data['Webpage '.$key], true);
                }

                return $content_data;
                break;

            case 'Webpage Launching Date':
                $content_data = $this->get('Content Data');
                if (isset($content_data['_launch_date'])) {
                    return $content_data['_launch_date'];
                } else {
                    return '';
                }
            case 'Launching Date':
                $content_data = $this->get('Content Data');
                if (isset($content_data['_launch_date']) and $content_data['_launch_date'] != '') {
                    return strftime("%A, %x", strtotime($content_data['_launch_date'].' +0:00'));
                } else {
                    return '';
                }


            case 'Code':
                return $this->data['Webpage Code'];
                break;

            case  'Page Found In Page Key':

                $found_in_page_key = '';

                $sql = sprintf(
                    "SELECT `Page Store Found In Key` FROM  `Page Store Found In Bridge` WHERE `Page Store Key`=%d", $this->id
                );

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $found_in_page_key = $row['Page Store Found In Key'];
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }

                return $found_in_page_key;
                break;
            case  'Found In Page Key':

                $found_in_page = '';

                $sql = sprintf(
                    "SELECT `Page Code` FROM  `Page Store Found In Bridge` B  LEFT JOIN `Page Store Dimension` ON (`Page Key`=`Page Store Found In Key`)  WHERE B.`Page Store Key`=%d", $this->id
                );

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $found_in_page = $row['Page Code'];
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }

                return $found_in_page;
                break;

            case('link'):
                return $this->display();
                break;

            default:
                if (isset($this->data[$key])) {
                    return $this->data[$key];
                }

                if (isset($this->data['Webpage '.$key])) {
                    return $this->data['Webpage '.$key];
                }
        }

        if (preg_match('/ Acc /', $key)) {

            $amount = 'Page Store '.$key;

            return number($this->data[$amount]);
        }

        return false;
    }

    function get_see_also_data() {

        $see_also = array();
        $sql      = sprintf(
            "SELECT `Page Store See Also Key`,`Correlation Type`,`Correlation Value` FROM  `Page Store See Also Bridge` WHERE `Page Store Key`=%d ORDER BY `Webpage See Also Order` ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                $see_also_page = new Page($row['Page Store See Also Key']);
                if ($see_also_page->id) {

                    if ($this->data['Page Store See Also Type'] == 'Manual') {
                        $formatted_correlation_type  = _('Manual');
                        $formatted_correlation_value = '';
                    } else {

                        switch ($row['Correlation Type']) {
                            case 'Manual':
                                $formatted_correlation_type  = _('Manual');
                                $formatted_correlation_value = '';
                                break;
                            case 'Sales':
                                $formatted_correlation_type  = _('Sales');
                                $formatted_correlation_value = percentage(
                                    $row['Correlation Value'], 1
                                );
                                break;
                            case 'Semantic':
                                $formatted_correlation_type  = _('Semantic');
                                $formatted_correlation_value = number(
                                    $row['Correlation Value']
                                );
                                break;
                            case 'New':
                                $formatted_correlation_type  = _('New');
                                $formatted_correlation_value = number(
                                    $row['Correlation Value']
                                );
                                break;
                            default:
                                $formatted_correlation_type  = $row['Correlation Type'];
                                $formatted_correlation_value = number(
                                    $row['Correlation Value']
                                );
                                break;
                        }
                    }
                    //if ($site_url)
                    //$link='<a href="http://'.$site_url.'/'.$see_also_page->data['Page URL'].'">'.$see_also_page->data['Page Short Title'].'</a>';

                    //else
                    $link = '<a href="https://'.$see_also_page->data['Page URL'].'">'.$see_also_page->data['Page Short Title'].'</a>';

                    $see_also[] = array(
                        'link'                        => $link,
                        'label'                       => $see_also_page->data['Page Short Title'],
                        'url'                         => $see_also_page->data['Page URL'],
                        'key'                         => $see_also_page->id,
                        'code'                        => $see_also_page->data['Page Code'],
                        'correlation_type'            => $row['Correlation Type'],
                        'correlation_formatted'       => $formatted_correlation_type,
                        'correlation_value'           => $row['Correlation Value'],
                        'correlation_formatted_value' => $formatted_correlation_value,
                        'image_key'                   => $see_also_page->data['Page Store Image Key']

                    );
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        if ($this->data['Page See Also Last Updated'] == '') {
            $last_updated = '';
        } else {
            $last_updated = strftime(
                "%a %e %b %Y %H:%M:%S %Z", strtotime($this->data['Page See Also Last Updated'].' +0:00')
            );
        }


        $data = array(
            'website_key'  => $this->get('Webpage Website Key'),
            'webpage_key'  => $this->id,
            'type'         => $this->get('Page Store See Also Type'),
            'number_links' => $this->get('Number See Also Links'),
            'last_updated' => $last_updated,
            'links'        => $see_also
        );

        return $data;
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

    function display($tipo = 'link') {

        switch ($tipo) {
            case('html'):
            case('xhtml'):
            case('link'):
            default:
                return '<a href="'.$this->data['Webpage URL'].'">'.$this->data['Page Title'].'</a>';

        }


    }

    function update_see_also() {


        if ($this->data['Page Type'] != 'Store' or $this->data['Page Store See Also Type'] == 'Manual') {
            return;
        }


        if (!isset($this->data['Number See Also Links'])) {
            //print_r($this);
            exit('error in update see also');

        }

        $max_links = $this->data['Number See Also Links'] * 2;


        $max_sales_links = ceil($max_links * .6);


        $min_sales_correlation_samples = 5;
        $correlation_upper_limit       = .5 / ($min_sales_correlation_samples);
        $see_also                      = array();
        $number_links                  = 0;


        switch ($this->data['Webpage Scope']) {
            case 'Department Catalogue':
                break;

            case 'Category Products':

                $category = get_object('Category', $this->data['Webpage Scope Key']);


                $sql = sprintf(
                    "SELECT `Category B Key`,`Correlation` FROM `Product Category Sales Correlation` WHERE `Category A Key`=%d ORDER BY `Correlation` DESC ", $this->data['Webpage Scope Key']
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $_family  = get_object('Category', $row['Category B Key']);
                        $_webpage = $_family->get_webpage();
                        if ($_webpage->id and $_webpage->data['Page State'] == 'Online') {
                            $see_also[$_webpage->id] = array(
                                'type'     => 'Sales',
                                'value'    => $row['Correlation'],
                                'page_key' => $_webpage->id
                            );
                            $number_links            = count($see_also);
                            if ($number_links >= $max_sales_links) {
                                break;
                            }
                        }
                    }
                }


                if ($number_links < $max_links) {
                    $sql = sprintf(
                        "SELECT * FROM `Product Family Semantic Correlation` WHERE `Family A Key`=%d ORDER BY `Weight` DESC LIMIT %d", $this->data['Webpage Scope Key'], ($max_links - $number_links) * 2
                    );


                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {

                            if (!array_key_exists($row['Family B Key'], $see_also)) {


                                $_family  = get_object('Category', $row['Family B Key']);
                                $_webpage = $_family->get_webpage();
                                // and $_webpage->data['Page Stealth Mode'] == 'No'
                                if ($_webpage->id and $_webpage->data['Page State'] == 'Online') {
                                    $see_also[$_webpage->id] = array(
                                        'type'     => 'Semantic',
                                        'value'    => $row['Weight'],
                                        'page_key' => $_webpage->id
                                    );
                                    $number_links            = count($see_also);
                                    if ($number_links >= $max_links) {
                                        break;
                                    }
                                }


                            }

                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        exit;
                    }


                }


                if ($number_links < $max_links) {


                    $sql = sprintf(
                        "SELECT `Category Key` FROM `Category Dimension` LEFT JOIN   `Product Category Dimension` ON (`Category Key`=`Product Category Key`)   LEFT JOIN `Page Store Dimension` ON (`Product Category Webpage Key`=`Page Key`) WHERE `Category Parent Key`=%d  AND `Webpage State`='Online'  AND `Category Key`!=%d  ORDER BY RAND()  LIMIT %d",
                        $category->get('Category Parent Key'), $category->id, ($max_links - $number_links) * 2
                    );


                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {

                            if (!array_key_exists($row['Category Key'], $see_also)) {


                                $_family  = get_object('Category', $row['Category Key']);
                                $_webpage = $_family->get_webpage();
                                // and $_webpage->data['Page Stealth Mode'] == 'No'
                                if ($_webpage->id and $_webpage->data['Page State'] == 'Online') {
                                    $see_also[$_webpage->id] = array(
                                        'type'     => 'SameParent',
                                        'value'    => .2,
                                        'page_key' => $_webpage->id
                                    );
                                    $number_links            = count($see_also);
                                    if ($number_links >= $max_links) {
                                        break;
                                    }
                                }


                            }

                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        exit;
                    }


                }


                if ($number_links < $max_links) {


                    $sql = sprintf(
                        "SELECT `Category Key` FROM `Category Dimension` LEFT JOIN   `Product Category Dimension` ON (`Category Key`=`Product Category Key`)   LEFT JOIN `Page Store Dimension` ON (`Product Category Webpage Key`=`Page Key`) WHERE  `Webpage State`='Online'  AND `Category Key`!=%d  AND `Category Store Key`=%d ORDER BY RAND()  LIMIT %d",
                        $this->data['Webpage Scope Key'], $category->get('Category Store Key'), ($max_links - $number_links) * 2
                    );


                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {

                            if (!array_key_exists($row['Category Key'], $see_also)) {


                                $_family  = get_object('Category', $row['Category Key']);
                                $_webpage = $_family->get_webpage();
                                // and $_webpage->data['Page Stealth Mode'] == 'No'
                                if ($_webpage->id and $_webpage->data['Page State'] == 'Online') {
                                    $see_also[$_webpage->id] = array(
                                        'type'     => 'Other',
                                        'value'    => .1,
                                        'page_key' => $_webpage->id
                                    );
                                    $number_links            = count($see_also);
                                    if ($number_links >= $max_links) {
                                        break;
                                    }
                                }


                            }

                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        exit;
                    }


                }


                break;


            case 'Product':

                $product = get_object('Product', $this->data['Webpage Scope Key']);
                $sql     = sprintf(
                    "SELECT `Product Webpage Key`,`Product B ID`,`Correlation` FROM `Product Sales Correlation`  LEFT JOIN `Product Dimension` ON (`Product ID`=`Product B ID`)    LEFT JOIN `Page Store Dimension` ON (`Page Key`=`Product Webpage Key`)  WHERE `Product A ID`=%d AND `Webpage State`='Online' AND `Product Web State`='For Sale'  ORDER BY `Correlation` DESC",
                    $product->id
                );
                //  $see_also_page->data['Page Stealth Mode'] == 'No')

                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        if (!array_key_exists($row['Product B ID'], $see_also) and $row['Product Webpage Key']) {

                            $see_also[$row['Product Webpage Key']] = array(
                                'type'     => 'Sales',
                                'value'    => $row['Correlation'],
                                'page_key' => $row['Product Webpage Key']
                            );
                            $number_links                          = count($see_also);
                            if ($number_links >= $max_links) {
                                break;
                            }

                        }
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                if ($number_links >= $max_links) {
                    break;
                }


                $max_customers = 0;


                if ($product->get('Product Family Category Key') > 0) {
                    $sql = sprintf(
                        "SELECT P.`Product ID`,P.`Product Code`,`Product Web State`,`Product Webpage Key`,`Product Total Acc Customers` FROM `Product Dimension` P LEFT JOIN `Product Data Dimension` D ON (P.`Product ID`=D.`Product ID`)    LEFT JOIN `Page Store Dimension` ON (`Page Key`=`Product Webpage Key`)  
                      WHERE  `Product Web State`='For Sale' AND `Webpage State`='Online' AND P.`Product ID`!=%d  AND `Product Family Category Key`=%d ORDER BY `Product Total Acc Customers` DESC  ", $product->id, $product->get('Product Family Category Key')

                    );

                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {


                            if (!array_key_exists($row['Product ID'], $see_also) and $row['Product Webpage Key']) {


                                if ($max_customers == 0) {
                                    $max_customers = $row['Product Total Acc Customers'];
                                }


                                $rnd = mt_rand() / mt_getrandmax();

                                $see_also[$row['Product Webpage Key']] = array(
                                    'type'     => 'Same Family',
                                    'value'    => .25 * $rnd * ($row['Product Total Acc Customers'] == 0 ? 1 : $row['Product Total Acc Customers']) / ($max_customers == 0 ? 1 : $max_customers),
                                    'page_key' => $row['Product Webpage Key']
                                );
                                $number_links                          = count($see_also);
                                if ($number_links >= $max_links) {
                                    break;
                                }
                            }

                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        print "$sql\n";
                        exit;
                    }

                }


                if ($number_links >= $max_links) {
                    break;
                }
                $max_customers = 0;


                if ($product->get('Product Store Key') > 0) {

                    $sql = sprintf(
                        "SELECT P.`Product ID`,P.`Product Code`,`Product Web State`,`Product Webpage Key`,`Product Total Acc Customers` FROM `Product Dimension` P LEFT JOIN `Product Data Dimension` D ON (P.`Product ID`=D.`Product ID`)    LEFT JOIN `Page Store Dimension` ON (`Page Key`=`Product Webpage Key`)  
                      WHERE  `Product Web State`='For Sale' AND `Webpage State`='Online' AND P.`Product ID`!=%d  AND `Product Store Key`=%d ORDER BY `Product Total Acc Customers` DESC  ", $product->id, $product->get('Product Store Key')

                    );

                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {


                            if (!array_key_exists($row['Product ID'], $see_also) and $row['Product Webpage Key']) {

                                if ($max_customers == 0) {
                                    $max_customers = $row['Product Total Acc Customers'];
                                }


                                $rnd = mt_rand() / mt_getrandmax();

                                $see_also[$row['Product Webpage Key']] = array(
                                    'type'     => 'Other',
                                    'value'    => .1 * $rnd * ($row['Product Total Acc Customers'] == 0 ? 1 : $row['Product Total Acc Customers']) / ($max_customers == 0 ? 1 : $max_customers),
                                    'page_key' => $row['Product Webpage Key']
                                );
                                $number_links                          = count($see_also);
                                if ($number_links >= $max_links) {
                                    break;
                                }
                            }

                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        print "$sql\n";
                        exit;
                    }
                }

                break;
            default:

                break;
        }


        $sql = sprintf(
            "DELETE FROM `Page Store See Also Bridge`WHERE `Page Store Key`=%d ", $this->id
        );
        $this->db->exec($sql);


        $count = 0;

        $order_value = 1;


        if (count($see_also) > 0) {


            foreach ($see_also as $key => $row) {
                $correlation[$key] = $row['value'];
            }

            //print_r($correlation);

            array_multisort($correlation, SORT_DESC, $see_also);
            // print_r($see_also);


            foreach ($see_also as $see_also_page_key => $see_also_data) {

                if ($count >= $this->data['Number See Also Links']) {
                    break;
                }

                $sql = sprintf(
                    "INSERT  INTO `Page Store See Also Bridge` (`Page Store Key`,`Page Store See Also Key`,`Correlation Type`,`Correlation Value`,`Webpage See Also Order`)  VALUES (%d,%d,%s,%f,%d) ", $this->id, $see_also_data['page_key'],
                    prepare_mysql($see_also_data['type']), $see_also_data['value'], $order_value
                );
                $this->db->exec($sql);
                $count++;
                $order_value++;
                //print "$sql\n";
            }

        }
        $this->update(
            array('Page See Also Last Updated' => gmdate('Y-m-d H:i:s')), 'no_history'
        );

    }


    function update_image_key() {


        if ($this->data['Page Type'] != 'Store') {
            return;
        }


        $page_image_source = 'art/nopic.png';
        $image_key         = '';


        switch ($this->data['Webpage Scope']) {
            case 'Category Categories':
            case 'Category Products':
                $category = get_object('Category', $this->data['Page Parent Key']);
                if ($category->id and $category->get('Category Main Image Key')) {
                    $_page_image = get_object('Image',$category->get('Category Main Image Key'));
                    if ($_page_image->id) {
                        $page_image_source = sprintf("images/%07d.%s", $_page_image->data['Image Key'], $_page_image->data['Image File Format']);
                        $image_key         = $_page_image->id;
                    }
                }
            case 'Product':
                include_once 'class.Product.php';
                $product = new Product('id', $this->data['Page Parent Key']);
                if ($product->id and $product->get('Product Main Image Key')) {
                    $_page_image = new Image($product->get('Product Main Image Key'));
                    if ($_page_image->id) {
                        $page_image_source = sprintf("images/%07d.%s", $_page_image->data['Image Key'], $_page_image->data['Image File Format']);
                        $image_key         = $_page_image->id;
                    }
                }

            default:

                break;
        }


        $sql = sprintf(
            "UPDATE `Page Store Dimension` SET `Page Store Image Key`=%s ,`Page Store Image URL`=%s   WHERE `Page Key`=%d ", prepare_mysql($image_key), prepare_mysql($page_image_source), $this->id
        );
        $this->db->exec($sql);

        $this->data['Page Store Image Key'] = $image_key;
        $this->data['Page Store Image URL'] = $page_image_source;


    }

    function refresh_cache() {
        global $memcache_ip;


        $account      = new Account($this->db);
        $account_code = $account->get('Account Code');


        $template_response = '';



        $smarty_web = new Smarty();

        if (empty($this->fork)) {
            $base = '';
        } else {
            $account = get_object('Account', 1);
            $base    = 'base_dirs/_home.'.strtoupper($account->get('Account Code')).'/';
        }

        $smarty_web->template_dir = $base.'EcomB2B/templates';
        $smarty_web->compile_dir  = $base.'EcomB2B/server_files/smarty/templates_c';
        $smarty_web->cache_dir    = $base.'EcomB2B/server_files/smarty/cache';
        $smarty_web->config_dir   = $base.'EcomB2B/server_files/smarty/configs';
        $smarty_web->addPluginsDir('./smarty_plugins');


        $smarty_web->setCaching(Smarty::CACHING_LIFETIME_CURRENT);


        $cache_id = $this->get('Webpage Website Key').'|'.$this->id;
        $smarty_web->clearCache(null, $cache_id);




        $redis = new Redis();
        if ($redis->connect('127.0.0.1', 6379)) {


            $url_cache_key = 'pwc2|'.DNS_ACCOUNT_CODE.'|'.$this->get('Webpage Website Key').'_'.$this->get('Webpage Code');
            $redis->set($url_cache_key, $this->id);
            $url_cache_key = 'pwc2|'.DNS_ACCOUNT_CODE.'|'.$this->get('Webpage Website Key').'_'.strtoupper($this->get('Webpage Code'));
            $redis->set($url_cache_key, $this->id);
            $url_cache_key = 'pwc2|'.DNS_ACCOUNT_CODE.'|'.$this->get('Webpage Website Key').'_'.strtolower($this->get('Webpage Code'));
            $redis->set($url_cache_key, $this->id);

        }


        return $template_response;

    }




    function get_options() {

        if (array_key_exists('Page Options', $this->data)) {

            return unserialize($this->data['Page Options']);
        } else {
            return false;
        }

    }


    function get_items() {


        $items = array();


        if ($this->get('Webpage Scope') == 'Category Products') {

            $sql = sprintf(
                "SELECT `Product Category Index Content Data` FROM `Product Category Index` 
                 WHERE  `Product Category Index Website Key`=%d   ORDER BY  ifnull(`Product Category Index Stack`,99999999)", $this->id


            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $items[] = json_decode($row['Product Category Index Content Data'], true);
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


        } else {
            if ($this->get('Webpage Scope') == 'Category Categories') {

                $sql = sprintf(
                    "SELECT `Category Webpage Index Content Data` FROM `Category Webpage Index` CWI
                 WHERE  `Category Webpage Index Webpage Key`=%d   ORDER BY  ifnull(`Category Webpage Index Stack`,99999999)", $this->id


                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $items[] = json_decode($row['Category Webpage Index Content Data'], true);
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

            }
        }


        return $items;

    }



    function update_store_search() {


        //todo redo this using elastic search



    }


    function get_related_products_data() {

        $related_products = array();
        $sql              = sprintf(
            "SELECT `Webpage Related Product Product ID`,`Webpage Related Product Product Page Key`  FROM  `Webpage Related Product Bridge` WHERE `Webpage Related Product Page Key`=%d ORDER BY `Webpage Related Product Order` ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                $related_products_page = new Page(
                    $row['Webpage Related Product Product Page Key']
                );
                if ($related_products_page->id) {


                    $link = '<a href="https://'.$related_products_page->data['Page URL'].'">'.$related_products_page->data['Page Short Title'].'</a>';

                    $related_products[] = array(
                        'link'       => $link,
                        'label'      => $related_products_page->data['Page Short Title'],
                        'url'        => $related_products_page->data['Page URL'],
                        'key'        => $related_products_page->id,
                        'product_id' => $row['Webpage Related Product Product ID'],
                        'code'       => $related_products_page->data['Page Code'],

                        'image_key' => $related_products_page->data['Page Store Image Key']

                    );
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $data = array(
            'website_key' => $this->get('Webpage Website Key'),
            'webpage_key' => $this->id,
            'links'       => $related_products
        );

        return $data;
    }

    function unpublish() {

        $this->update_state('Offline');


        if ($this->get('Webpage State') == 'Online') {
            $icon = 'fa-rocket';
        } elseif ($this->get('Webpage State') == 'Offline') {
            $icon = ' fa-rocket discreet fa-flip-vertical';
        } elseif ($this->get('Webpage State') == 'Ready') {
            $icon = 'fa-check-circle';

        } elseif ($this->get('Webpage State') == 'InProcess') {
            $icon = 'fa-child';


        }


        $smarty_web = new Smarty();


        if (empty($this->fork)) {
            $base = '';
        } else {
            $account = get_object('Account', 1);
            $base    = 'base_dirs/_home.'.strtoupper($account->get('Account Code')).'/';
        }

        $smarty_web->template_dir = $base.'EcomB2B/templates';
        $smarty_web->compile_dir  = $base.'EcomB2B/server_files/smarty/templates_c';
        $smarty_web->cache_dir    = $base.'EcomB2B/server_files/smarty/cache';
        $smarty_web->config_dir   = $base.'EcomB2B/server_files/smarty/configs';
        $smarty_web->addPluginsDir('./smarty_plugins');

        $smarty_web->setCaching(Smarty::CACHING_LIFETIME_CURRENT);

        $theme        = 'theme_1';
        $website_type = 'EcomB2B';


        $cache_id = $this->get('Webpage Website Key').'|'.$this->id;
        $smarty_web->clearCache(null, $cache_id);


        $this->update_metadata = array(
            'class_html'      => array(
                'Webpage_State_Icon'    => $this->get('State Icon'),
                'Webpage_State'         => $this->get('State'),
                'preview_publish_label' => _('Republish')

            ),
            'hide_by_id'      => array(
                'unpublish_webpage_field',
                'launch_webpage_field'
            ),
            'show_by_id'      => array('republish_webpage_field'),
            'invisible_by_id' => array('link_to_live_webpage'),


        );


    }

    function update_state($value, $options = '') {


        if (!$this->id) {
            return;
        }

        $old_state = $this->data['Webpage State'];


        $this->update_field('Page State', $value, 'no_history');
        $this->update_field('Webpage State', $value, $options);



        if ($old_state != $this->data['Webpage State']) {


            if ($this->data['Webpage State'] == 'Offline') {

                $this->update_field('Webpage Take Down Date', gmdate('Y-m-d H:i:s'), 'no_history');

            }

            $sql = sprintf(
                "INSERT INTO `Page State Timeline`  (`Page Key`,`Website Key`,`Store Key`,`Date`,`State`,`Operation`) VALUES (%d,%d,%d,%s,%s,'Change') ", $this->id, $this->data['Webpage Website Key'], $this->data['Webpage Store Key'], prepare_mysql(gmdate('Y-m-d H:i:s')),
                prepare_mysql($this->data['Webpage State'])

            );

            $this->db->exec($sql);




            $sql = sprintf(
                "SELECT `Page Store Key`  FROM  `Page Store See Also Bridge` WHERE `Page Store See Also Key`=%d ", $this->id
            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $_page = new Page ($row['Page Store Key']);
                    $_page->update_see_also();
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            $this->reindex_items();

            if ($this->updated and $this->data['Webpage State'] == 'Online') {
                $this->publish();
            }

            $sql = sprintf(
                'SELECT `Category Webpage Index Webpage Key` FROM `Category Webpage Index` WHERE `Category Webpage Index Category Webpage Key`=%d  GROUP BY `Category Webpage Index Webpage Key` ', $this->id
            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $webpage = new Page($row['Category Webpage Index Webpage Key']);
                    $webpage->reindex_items();
                    if ($webpage->updated) {
                        $webpage->publish();
                    }
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            $this->updated = true;

        }


        $show = array();
        $hide = array();


        if ($this->get('Webpage State') == 'Ready') {

            $show = array('set_as_not_ready_webpage_field');
            $hide = array('set_as_ready_webpage_field');
        } elseif ($this->get('Webpage State') == 'InProcess') {


            $show = array('set_as_ready_webpage_field');
            $hide = array('set_as_not_ready_webpage_field');
        }


        $this->update_metadata = array(
            'class_html' => array(
                'Webpage_State_Icon' => $this->get('State Icon'),
                'Webpage_State'      => $this->get('State'),


            ),
            'hide_by_id' => $hide,
            'show_by_id' => $show


        );


    }

    function reindex_items() {

        $this->updated = false;

        $website = get_object('Website', $this->get('Webpage Website Key'));

        if ($website->get('Website Theme') == 'theme_1') {

            $content_data = $this->get('Content Data');
            if (isset($content_data['blocks'])) {
                foreach ($content_data['blocks'] as $block_key => $block) {

                    //   print $block['type']."\n";

                    switch ($block['type']) {
                        case 'category_products':
                            $this->reindex_category_products();
                            break;
                        case 'category_categories':
                            $this->reindex_category_categories();
                            break;
                        case 'products':
                            $this->reindex_products();
                            break;
                        case 'product':
                            $this->reindex_product();
                            break;
                        case 'see_also':
                            $this->reindex_see_also();
                            break;
                    }


                }
            }
            $this->updated = true;


            $smarty_web = new Smarty();

            if (empty($this->fork)) {
                $base = '';
            } else {
                $account = get_object('Account', 1);
                $base    = 'base_dirs/_home.'.strtoupper($account->get('Account Code')).'/';
            }

            $smarty_web->template_dir = $base.'EcomB2B/templates';
            $smarty_web->compile_dir  = $base.'EcomB2B/server_files/smarty/templates_c';
            $smarty_web->cache_dir    = $base.'EcomB2B/server_files/smarty/cache';
            $smarty_web->config_dir   = $base.'EcomB2B/server_files/smarty/configs';
            $smarty_web->addPluginsDir('./smarty_plugins');

            $smarty_web->setCaching(Smarty::CACHING_LIFETIME_CURRENT);

            $theme        = 'theme_1';
            $website_type = 'EcomB2B';


            $cache_id = $this->get('Webpage Website Key').'|'.$this->id;
            $smarty_web->clearCache(null, $cache_id);
            $account = get_object('Account', 1);


            $redis = new Redis();
            if ($redis->connect('127.0.0.1', 6379)) {

                $cache_id_prefix='pwc2|'.$account->get('Code').'|'.$this->get('Webpage Website Key').'_';

                $redis->delete($cache_id_prefix.$this->data['Page Code']);
                $redis->delete($cache_id_prefix.strtolower($this->data['Page Code']));
                $redis->delete($cache_id_prefix.strtoupper($this->data['Page Code']));
                $redis->delete($cache_id_prefix.ucfirst($this->data['Page Code']));




            }


        }
        else {
            if ($this->get('Webpage Scope') == 'Category Categories') {

                if ($this->get('Webpage Version') == 2) {


                    $this->updated = true;


                    $subjects = array();
                    $sql      = sprintf(
                        'SELECT `Webpage Scope Key` FROM `Category Bridge` LEFT JOIN `Page Store Dimension` ON (`Webpage Scope Key`=`Subject Key`   )  WHERE  ( `Webpage Scope`="Category Categories" OR  `Webpage Scope`="Category Products" ) AND   `Subject`="Category" AND `Category Key`=%d  ORDER BY `Webpage Scope Key` ',
                        $this->get('Webpage Scope Key')
                    );
                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {
                            if ($row['Webpage Scope Key']) {
                                $subjects[] = $row['Webpage Scope Key'];
                            }
                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        print "$sql\n";
                        exit;
                    }


                    foreach ($subjects as $item_key) {
                        $sql = sprintf(
                            'UPDATE `Category Webpage Index` SET `Category Webpage Index Subject Type`="Subject" WHERE `Category Webpage Index Webpage Key`=%d  AND `Category Webpage Index Category Key`=%d   ', $this->id, $item_key
                        );
                        $this->db->exec($sql);

                    }

                    // print_r($subjects);


                    $content_data = $this->get('Content Data');


                    //     print count($subjects)."sss\n";


                    if ($content_data != '') {


                        foreach ($content_data['sections'] as $section_stack_index => $section_data) {


                            $content_data['sections'][$section_stack_index]['items'] = get_website_section_items($this->db, $section_data);


                        }
                    }
                    $this->update(array('Page Store Content Data' => json_encode($content_data)), 'no_history');

                    $_subjects_in_webpage = array();

                    $sql = sprintf(
                        "SELECT `Category Webpage Index Category Key`  ,`Category Webpage Index Section Key`          FROM `Category Webpage Index` CWI  WHERE  `Category Webpage Index Webpage Key`=%d AND `Category Webpage Index Subject Type`='Subject'  ORDER BY `Category Webpage Index Category Key` ",
                        $this->id


                    );


                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {


                            $_subjects_in_webpage[] = $row['Category Webpage Index Category Key'];

                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        print "$sql\n";
                        exit;
                    }
                    //print_r($subjects);
                    //print_r($_subjects_in_webpage);

                    //print count($_subjects_in_webpage)."\n";


                    $to_add    = array_diff($subjects, $_subjects_in_webpage);
                    $to_remove = array_diff($_subjects_in_webpage, $subjects);


                    //print_r($to_add);
                    //print_r($to_remove);


                    foreach ($to_add as $item_key) {
                        $this->add_section_item($item_key);


                    }


                    // print_r($_to_remove);

                    foreach ($to_remove as $item_key) {
                        $this->remove_section_item($item_key);

                    }


                }
            }
        }

        /*
                if ($this->get('Webpage Template Filename') == 'category_categories') {

                    $this->reindex_category_categories();
                    $this->updated = true;

                    return;
                }
                if ($this->get('Webpage Template Filename') == 'category_products') {

                    $this->reindex_category_products();
                    $this->updated = true;

                    return;
                }
        */

    }

    function reindex_category_products() {
        $content_data = $this->get('Content Data');


        //print_r($content_data);

        $block_found = false;
        foreach ($content_data['blocks'] as $_block_key => $_block) {
            if ($_block['type'] == 'category_products') {
                $block       = $_block;
                $block_key   = $_block_key;
                $block_found = true;
                break;
            }
        }

        if (!$block_found) {
            return;
        }

        $sql = sprintf(
            "SELECT P.`Product ID` 
                  FROM `Category Bridge` B  LEFT JOIN `Product Dimension` P ON (`Subject Key`=P.`Product ID`)  
                WHERE  `Category Key`=%d  AND `Product Web State` IN  ('For Sale','Out of Stock')   ORDER BY `Product Web State`", $this->data['Webpage Scope Key']
        );

        //print $sql;

        $items                  = array();
        $items_product_id_index = array();

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $items[$row['Product ID']]                  = $row;
                $items_product_id_index[$row['Product ID']] = $row['Product ID'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $offline_items_product_id_index[$row['Product ID']] = $row['Product ID'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        if(isset($block['items']) and is_array($block['items'])) {
            foreach ($block['items'] as $item_key => $item) {
                if ($item['type'] == 'product') {
                    if (in_array($item['product_id'], $items_product_id_index)) {


                        $product = get_object('Public_Product', $item['product_id']);

                        if ($product->id) {
                            $product->load_webpage();

                            // print_r($content_data['blocks'][$block_key]['items'][$item_key]);


                            if ($product->get('Image') != $content_data['blocks'][$block_key]['items'][$item_key]['image_src']) {
                                $content_data['blocks'][$block_key]['items'][$item_key]['image_src'] = $product->get('Image');
                                $content_data['blocks'][$block_key]['items'][$item_key]['image_mobile_website'] = '';
                                $content_data['blocks'][$block_key]['items'][$item_key]['image_website'] = '';


                            }


                            $content_data['blocks'][$block_key]['items'][$item_key]['web_state'] = $product->get('Web State');
                            $content_data['blocks'][$block_key]['items'][$item_key]['price'] = $product->get('Price');


                            $content_data['blocks'][$block_key]['items'][$item_key]['price_unit'] = $product->get('Price Per Unit');

                            $content_data['blocks'][$block_key]['items'][$item_key]['rrp'] = $product->get('RRP');
                            $content_data['blocks'][$block_key]['items'][$item_key]['code'] = $product->get('Code');
                            $content_data['blocks'][$block_key]['items'][$item_key]['name'] = $product->get('Name');
                            $content_data['blocks'][$block_key]['items'][$item_key]['link'] = $product->webpage->get('URL');
                            $content_data['blocks'][$block_key]['items'][$item_key]['webpage_code'] = $product->webpage->get('Webpage Code');
                            $content_data['blocks'][$block_key]['items'][$item_key]['webpage_key'] = $product->webpage->id;


                            $content_data['blocks'][$block_key]['items'][$item_key]['out_of_stock_class'] = $product->get('Out of Stock Class');
                            $content_data['blocks'][$block_key]['items'][$item_key]['out_of_stock_label'] = $product->get('Out of Stock Label');
                            $content_data['blocks'][$block_key]['items'][$item_key]['sort_code'] = $product->get('Code File As');
                            $content_data['blocks'][$block_key]['items'][$item_key]['sort_name'] = $product->get('Product Name');
                            $content_data['blocks'][$block_key]['items'][$item_key]['next_shipment_timestamp'] = $product->get('Next Supplier Shipment Timestamp');

                            $content_data['blocks'][$block_key]['items'][$item_key]['category'] = $product->get('Family Code');
                            $content_data['blocks'][$block_key]['items'][$item_key]['raw_price'] = $product->get('Product Price');


                            unset($items_product_id_index[$item['product_id']]);
                        } else {
                            unset($content_data['blocks'][$block_key]['items'][$item_key]);
                            unset($items_product_id_index[$item['product_id']]);
                        }


                    } else {
                        unset($content_data['blocks'][$block_key]['items'][$item_key]);

                    }

                }
            }
        }
        foreach ($items_product_id_index as $product_id) {

            $product = get_object('Public_Product', $product_id);

            if ($product->id) {

                $product->load_webpage();


                $item = array(
                    'type'                    => 'product',
                    'product_id'              => $product_id,
                    'web_state'               => $product->get('Web State'),
                    'price'                   => $product->get('Price'),
                    'rrp'                     => $product->get('RRP'),
                    'header_text'             => '',
                    'code'                    => $product->get('Code'),
                    'name'                    => $product->get('Name'),
                    'link'                    => $product->webpage->get('URL'),
                    'webpage_code'            => $product->webpage->get('Webpage Code'),
                    'webpage_key'             => $product->webpage->id,
                    'image_src'               => $product->get('Image'),
                    'image_mobile_website'    => '',
                    'image_website'           => '',
                    'out_of_stock_class'      => $product->get('Out of Stock Class'),
                    'out_of_stock_label'      => $product->get('Out of Stock Label'),
                    'sort_code'               => $product->get('Code File As'),
                    'sort_name'               => $product->get('Product Name'),
                    'next_shipment_timestamp' => $product->get('Next Supplier Shipment Timestamp'),
                    'category'                => $product->get('Family Code'),
                    'raw_price'               => $product->get('Product Price'),


                );


                array_unshift($content_data['blocks'][$block_key]['items'], $item);
            }

        }


        $this->update_field_switcher('Page Store Content Data', json_encode($content_data), 'no_history');

        $sql = sprintf('DELETE FROM `Website Webpage Scope Map` WHERE `Website Webpage Scope Webpage Key`=%d AND `Website Webpage Scope Type`="Category_Products_Item" ', $this->id);
        $this->db->exec($sql);

        $index = 0;
        foreach ($content_data['blocks'][$block_key]['items'] as $item) {
            if ($item['type'] == 'product') {
                $sql = sprintf(
                    'INSERT INTO `Website Webpage Scope Map` (`Website Webpage Scope Website Key`,`Website Webpage Scope Webpage Key`,`Website Webpage Scope Scope`,`Website Webpage Scope Scope Key`,`Website Webpage Scope Type`,`Website Webpage Scope Index`) VALUES (%d,%d,%s,%d,%s,%d) ',
                    $this->get('Webpage Website Key'), $this->id, prepare_mysql('Product'), $item['product_id'], prepare_mysql('Category_Products_Item'), $index

                );


                $this->db->exec($sql);
                $index++;

            }


        }


    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        switch ($field) {

            case 'Webpage See Also':

                $this->update(
                    array(
                        'See Also' => $value
                    ), $options
                );


                break;

            case('Webpage Code'):
                $sql = sprintf('UPDATE `Page Dimension` SET `Page Code`=%s WHERE `Page Key`=%d ', prepare_mysql($value), $this->id);
                $this->db->exec($sql);
                $this->update_field($field, $value, $options);
                $this->update_url();


                $this->update_metadata = array(
                    'class_html' => array(
                        'Webpage_URL' => $this->get('Webpage URL'),

                    ),

                );


                break;


            case 'Webpage Browser Title':

                $sql = sprintf('UPDATE `Page Dimension` SET `Page Title`=%s WHERE `Page Key`=%d ', prepare_mysql($value), $this->id);
                $this->db->exec($sql);
                $this->update_field($field, $value, $options);
                break;
            case 'Webpage Name':


                $sql = sprintf('UPDATE `Page Dimension` SET `Page Short Title`=%s WHERE `Page Key`=%d ', prepare_mysql($value), $this->id);
                $this->db->exec($sql);
                $sql = sprintf('UPDATE `Page Store Dimension` SET `Page Store Title`=%s WHERE `Page Key`=%d ', prepare_mysql($value), $this->id);
                $this->db->exec($sql);
                $this->update_field($field, $value, $options);
                break;

            case 'Webpage Meta Description':


                $sql = sprintf('UPDATE `Page Store Dimension` SET `Page Store Description`=%s WHERE `Page Key`=%d ', prepare_mysql($value), $this->id);
                $this->db->exec($sql);
                $this->update_field($field, $value, $options);
                break;


            case 'Store Email':
            case 'Store Company Name':
            case 'Store VAT Number':
            case 'Store Company Number':
            case 'Store Telephone':
            case 'Store Address':
            case 'Store Google Map URL':
                include_once('class.Store.php');
                $store         = new Store($this->data['Webpage Store Key']);
                $store->editor = $this->editor;

                $store->update_field_switcher($field, $value, $options);
                $this->updated = $store->updated;
                $this->error   = $store->error;
                $this->msg     = $store->msg;

                break;


            case 'History Note':
                $this->add_note($value, '', '', $metadata['deletable'], 'Notes', false, false, false, 'Webpage', false, 'Webpage Publishing', false);

                break;


            case 'Scope Metadata':

                $this->update_field('Webpage '.$field, $value, $options);
                break;

            case('Webpage Scope'):
            case('Webpage Scope Key'):
            case('Webpage Scope Metadata'):
            case('Webpage Website Key'):
            case('Webpage Store Key'):
            case ('Webpage Redirection Code'):

            case('Webpage Type Key'):
            case 'Webpage Version':
            case 'Webpage Launch Date':
            case 'Webpage Name':
            case 'Webpage Browser Title':
            case 'Webpage Meta Description':
            case 'Webpage URL':

                $this->update_field($field, $value, $options);
                break;


            case 'Webpage Template Filename':


                if ($value == 'blank') {


                    $sql = sprintf('UPDATE `Page Store Dimension` SET `Page Store Content Display Type`="Source" WHERE `Page Key`=%d ', $this->id);
                    $this->db->exec($sql);

                } else {


                    $sql = sprintf('UPDATE `Page Store Dimension` SET `Page Store Content Display Type`="Template" WHERE `Page Key`=%d ', $this->id);
                    $this->db->exec($sql);

                    $sql = sprintf('UPDATE `Page Store Dimension` SET `Page Store Content Template Filename`=%s WHERE `Page Key`=%d ', prepare_mysql($value), $this->id);
                    $this->db->exec($sql);


                }


                $this->update_field($field, $value, $options);

                $this->update_version();
                $this->publish();

                break;


            case('Webpage Launching Date'):


                if ($value == '00:00:00') {
                    $value = '';
                }

                $this->update_content_data('_launch_date', $value, $options);

                if ($value == '') {
                    $this->update_content_data('show_countdown', false, 'no_history');

                } else {
                    $this->update_content_data('show_countdown', true, 'no_history');

                }


                break;


            case 'Related Products':

                $value = json_decode($value, true);
                //print_r($value);

                $product_page_keys = array();
                foreach ($value as $product_id) {


                    $sql = sprintf(
                        'SELECT `Page Key` FROM `Page Store Dimension` WHERE `Page Store Section Type`="Product"  AND  `Page Parent Key`=%d ', $product_id
                    );

                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $product_page_keys[$product_id] = $row['Page Key'];
                        } else {
                            $this->error = true;
                            $this->msg   = 'Product and/or page no found';
                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        exit;
                    }


                }


                $sql = sprintf(
                    'DELETE FROM `Webpage Related Product Bridge` WHERE `Webpage Related Product Page Key`=%d %s', $this->id, (count($value) > 0 ? sprintf(
                    'and `Webpage Related Product Product ID` not in (%s)', join(',', $value)
                ) : '')
                );
                //print $sql;
                $this->db->exec($sql);

                $order_value = 1;
                foreach ($value as $product_id) {

                    if (isset($product_page_keys[$product_id])) {


                        $sql = sprintf(
                            'INSERT INTO  `Webpage Related Product Bridge` (`Webpage Related Product Page Key`,`Webpage Related Product Product ID`,`Webpage Related Product Product Page Key`,`Webpage Related Product Published Order`,`Webpage Related Product Order`) VALUES (%d,%d,%d,%d,%d)  ON DUPLICATE KEY UPDATE `Webpage Related Product Order`=%d,`Webpage Related Product Published Order`=%d ',
                            $this->id, $product_id, $product_page_keys[$product_id], $order_value, $order_value, $order_value, $order_value


                        );


                        $this->db->exec($sql);
                        $order_value++;

                    }
                }


                $this->refresh_cache();


                break;

            case 'See Also':

                $value = json_decode($value, true);
                // print_r($value);

                $this->update_field(
                    'Page Store See Also Type', $value['type'], $options
                );
                $updated = $this->updated;
                if ($value['type'] == 'Auto') {
                    $this->update_field(
                        'Number See Also Links', $value['number_links'], $options
                    );
                    if ($this->updated) {
                        $updated = $this->updated;
                    }

                    //if ($updated) {
                    $this->update_see_also();
                    //}

                    $this->updated = $updated;
                } else {

                    //print_r($value);


                    $sql = sprintf(
                        'DELETE FROM `Page Store See Also Bridge` WHERE `Page Store Key`=%d %s', $this->id, (count($value['manual_links']) > 0 ? sprintf(
                        'and `Page Store See Also Key` not in (%s)', join(',', $value['manual_links'])
                    ) : '')
                    );

                    $this->db->exec($sql);

                    $order_value = 1;
                    foreach ($value['manual_links'] as $link_key) {
                        $sql = sprintf(
                            'INSERT INTO  `Page Store See Also Bridge` (`Page Store Key`,`Page Store See Also Key`,`Correlation Type`,`Correlation Value`,`Webpage See Also Order`) VALUES (%d,%d,"Manual",NULL,%d)  ON DUPLICATE KEY UPDATE `Correlation Type`="Manual",`Webpage See Also Order`=%d ',
                            $this->id, $link_key, $order_value, $order_value


                        );
                        $this->db->exec($sql);
                        //print "$sql\n";
                        $order_value++;
                    }


                    $this->update_field(
                        'Number See Also Links', count($value['manual_links']), $options
                    );


                }
                $this->refresh_cache();
                break;

            case 'Found In':

                $this->update_found_in($value);
                $this->refresh_cache();
                break;
            case('Page Store See Also Type'):
                $this->update_field(
                    'Page Store See Also Type', $value, $options
                );
                if ($value == 'Auto') {
                    $this->update_see_also();
                }
                break;


            case('Page See Also Last Updated'):

                $this->update_field($field, $value, $options);


                break;


            case('Webpage State'):


                $this->update_state($value, $options);
                // $this->refresh_cache();
                break;


            case 'Page Store Content Data':

                // post edit content data
                include_once('utils/image_functions.php');

                $content_data = json_decode($value, true);


                if (isset($content_data['blocks'])) {
                    foreach ($content_data['blocks'] as $block_key => $block) {

                        if ($block['type'] == 'blackboard') {

                            $items = array();
                            $index = 0;

                            $max_images = count($block['texts']);
                            if ($max_images == 0) {
                                $max_images = 1;
                            }

                            $counter = 0;
                            foreach ($block['images'] as $key_item => $item) {
                                $index        = $index + 10;
                                $item['type'] = 'image';


                                if (empty($item['image_website']) and $item['width'] > 0 and $item['height'] > 0) {
                                    $image_website = $item['src'];
                                    if (preg_match('/id=(\d+)/', $item['src'], $matches)) {
                                        $image_key = $matches[1];

                                        $width  = $item['width'] * 2;
                                        $height = $item['height'] * 2;


                                        $image_website = create_cached_image($image_key, $width, $height, 'do_not_enlarge');

                                    }


                                    $content_data['blocks'][$block_key]['images'][$key_item]['image_website'] = $image_website;
                                    $item['image_website']                                                    = $image_website;

                                }

                                $items[$index] = $item;
                                $counter++;

                            }
                            $index = 5;
                            foreach ($block['texts'] as $item) {
                                $index         = $index + 10;
                                $item['type']  = 'text';
                                $items[$index] = $item;
                            }

                            ksort($items);


                            $image_counter = 0;
                            $mobile_html   = '';
                            $tablet_html   = '';


                            foreach ($items as $item) {
                                if ($item['type'] == 'text') {
                                    $tablet_html .= '<p>'.$item['text'].'</p>';
                                }
                                if ($item['type'] == 'image') {

                                    if ($image_counter >= $max_images) {
                                        break;
                                    }

                                    if ($image_counter % 2 == 0) {
                                        $tablet_html .= '<img src="'.$item['image_website'].'" style="width:45%;float:left;margin-right:20px;" alt="'.$item['title'].'">';

                                    } else {
                                        $tablet_html .= '<img src="'.$item['image_website'].'" style="width:40%;float:right;margin-left:20px;" alt="'.$item['title'].'">';

                                    }


                                    $image_counter++;

                                }

                            }
                            $image_counter = 0;

                            foreach ($items as $key_item => $item) {

                                if ($item['type'] == 'image') {

                                    if ($item['height'] == 0 or $item['width'] == 0) {
                                        unset($items[$key_item]);
                                    } else {
                                        $ratio = $item['width'] / $item['height'];
                                        //print "$ratio\n";

                                        if ($ratio > 7.5) {
                                            $mobile_html .= '<img src="'.$item['image_website'].'" style="width:100%;" alt="'.$item['title'].'">';
                                            unset($items[$key_item]);
                                            break;
                                        }
                                    }

                                }


                            }


                            foreach ($items as $item) {
                                if ($item['type'] == 'text') {
                                    $mobile_html .= '<p>'.$item['text'].'</p>';
                                }
                                if ($item['type'] == 'image') {


                                    if ($image_counter % 2 == 0) {
                                        $mobile_html .= '<img src="'.$item['image_website'].'" style="width:40%;padding-top:15px;float:left;margin-right:15px;" alt="'.$item['title'].'">';

                                    } else {
                                        $mobile_html .= '<img src="'.$item['image_website'].'" style="width:40%;padding-top:15px;float:right;margin-left:15px;" alt="'.$item['title'].'">';

                                    }


                                    $image_counter++;

                                }

                            }


                            $mobile_html = preg_replace('/\<p\>\<br\>\<\/p\>/', '', $mobile_html);
                            $mobile_html = preg_replace('/\<p style\=\"text-align: left;\"\><br\>\<\/p\>/', '', $mobile_html);
                            $mobile_html = preg_replace('/\<p style\=\"\"\>\<br\>\<\/p\>/', '', $mobile_html);


                            $tablet_html = preg_replace('/\<p\>\<br\>\<\/p\>/', '', $tablet_html);
                            $tablet_html = preg_replace('/\<p style\=\"text-align: left;\"\><br\>\<\/p\>/', '', $tablet_html);
                            $tablet_html = preg_replace('/\<p style\=\"\"\>\<br\>\<\/p\>/', '', $tablet_html);

                            // print_r($mobile_html);
                            $content_data['blocks'][$block_key]['mobile_html'] = $mobile_html;
                            $content_data['blocks'][$block_key]['tablet_html'] = $tablet_html;

                        } elseif ($block['type'] == 'category_products') {
                            foreach ($block['items'] as $item_key => $item) {
                                if ($item['type'] == 'product') {
                                    if (empty($item['image_mobile_website'])) {
                                        $image_mobile_website = $item['image_src'];
                                        if (preg_match('/id=(\d+)/', $item['image_src'], $matches)) {
                                            $image_key = $matches[1];

                                            $image_mobile_website = create_cached_image($image_key, 340, 214);

                                        }


                                        $content_data['blocks'][$block_key]['items'][$item_key]['image_mobile_website'] = $image_mobile_website;


                                    }

                                    if (empty($item['image_website'])) {
                                        $image_website = $item['image_src'];
                                        if (preg_match('/id=(\d+)/', $item['image_src'], $matches)) {
                                            $image_key     = $matches[1];
                                            $image_website = create_cached_image($image_key, 432, 330, 'fit_highest');
                                        }


                                        $content_data['blocks'][$block_key]['items'][$item_key]['image_website'] = $image_website;


                                    }
                                } elseif ($item['type'] == 'image') {

                                    if (empty($item['image_website'])) {
                                        $image_website = $item['image_src'];
                                        if (preg_match('/id=(\d+)/', $item['image_src'], $matches)) {
                                            $image_key = $matches[1];

                                            if ($content_data['blocks'][$block_key]['item_headers']) {
                                                $height = 330;
                                            } else {
                                                $height = 290;
                                            }


                                            switch ($item['size_class']) {
                                                case 'panel_1':
                                                    $width = 226;

                                                    break;
                                                case 'panel_2':
                                                    $width = 470;
                                                    break;
                                                case 'panel_3':
                                                    $width = 714;
                                                    break;
                                                case 'panel_4':
                                                    $width = 958;
                                                    break;
                                                case 'panel_5':
                                                    $width = 1202;
                                                    break;

                                            }

                                            $image_website = create_cached_image($image_key, $width, $height);
                                        }


                                        $content_data['blocks'][$block_key]['items'][$item_key]['image_website'] = $image_website;


                                    }


                                }

                            }

                        } elseif ($block['type'] == 'products') {
                            foreach ($block['items'] as $item_key => $item) {

                                if (empty($item['image_mobile_website'])) {
                                    $image_mobile_website = $item['image_src'];
                                    if (preg_match('/id=(\d+)/', $item['image_src'], $matches)) {
                                        $image_key = $matches[1];

                                        $image_mobile_website = create_cached_image($image_key, 340, 214);

                                    }


                                    $content_data['blocks'][$block_key]['items'][$item_key]['image_mobile_website'] = $image_mobile_website;


                                }

                                if (empty($item['image_website'])) {
                                    $image_website = $item['image_src'];
                                    if (preg_match('/id=(\d+)/', $item['image_src'], $matches)) {
                                        $image_key     = $matches[1];
                                        $image_website = create_cached_image($image_key, 432, 330, 'fit_highest');
                                    }


                                    $content_data['blocks'][$block_key]['items'][$item_key]['image_website'] = $image_website;


                                }
                            }

                        } elseif ($block['type'] == 'see_also') {
                            foreach ($block['items'] as $item_key => $item) {

                                if (empty($item['image_mobile_website'])) {
                                    $image_mobile_website = $item['image_src'];
                                    if (preg_match('/id=(\d+)/', $item['image_src'], $matches)) {
                                        $image_key = $matches[1];

                                        $image_mobile_website = create_cached_image($image_key, 320, 200);

                                    }


                                    $content_data['blocks'][$block_key]['items'][$item_key]['image_mobile_website'] = $image_mobile_website;


                                }

                                if (empty($item['image_website'])) {
                                    $image_website = $item['image_src'];
                                    if (preg_match('/id=(\d+)/', $item['image_src'], $matches)) {
                                        $image_key     = $matches[1];
                                        $image_website = create_cached_image($image_key, 432, 330, 'fit_highest');
                                    }


                                    $content_data['blocks'][$block_key]['items'][$item_key]['image_website'] = $image_website;


                                }
                            }

                        } elseif ($block['type'] == 'category_categories') {


                            foreach ($block['sections'] as $section_key => $section) {

                                if(isset($section['items']) and is_array($section['items']) ){


                                    foreach ($section['items'] as $item_key => $item) {

                                        if ($item['type'] == 'category') {
                                            if (empty($item['image_mobile_website'])) {
                                                $image_mobile_website = $item['image_src'];
                                                if (preg_match('/id=(\d+)/', $item['image_src'], $matches)) {
                                                    $image_key = $matches[1];

                                                    $image_mobile_website = create_cached_image($image_key, 320, 200);

                                                }


                                                $content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['image_mobile_website'] = $image_mobile_website;


                                            }

                                            if (empty($item['image_website'])) {
                                                $image_website = $item['image_src'];
                                                if (preg_match('/id=(\d+)/', $item['image_src'], $matches)) {
                                                    $image_key     = $matches[1];
                                                    $image_website = create_cached_image($image_key, 432, 330, 'fit_highest');
                                                }


                                                $content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['image_website'] = $image_website;


                                            }
                                        } elseif ($item['type'] == 'image') {

                                            if (empty($item['image_website'])) {
                                                $image_website = $item['image_src'];
                                                if (preg_match('/id=(\d+)/', $item['image_src'], $matches)) {
                                                    $image_key = $matches[1];
                                                    $height    = 220;
                                                    switch ($item['size_class']) {
                                                        case 'panel_1':
                                                            $width = 226;

                                                            break;
                                                        case 'panel_2':
                                                            $width = 470;
                                                            break;
                                                        case 'panel_3':
                                                            $width = 714;
                                                            break;
                                                        case 'panel_4':
                                                            $width = 958;
                                                            break;
                                                        case 'panel_5':
                                                            $width = 1202;
                                                            break;

                                                    }

                                                    $image_website = create_cached_image($image_key, $width, $height);
                                                }


                                                $content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['image_website'] = $image_website;


                                            }


                                        }
                                    }
                                }




                            }
                        }


                    }

                }


                $value = json_encode($content_data);

                $this->update_field('Page Store Content Data', $value, $options);
                $this->update_store_search();

                if ($this->get('Webpage Scope') == 'Category Categories' and $this->get('Webpage Template Filename') != 'category_categories') {


                        $this->update_category_webpage_index();

                }


                break;

            case 'Website Registration Type':


                $old_content_data = $this->get('Content Data');
                if (empty($old_content_data['backup'])) {
                    $backup = array(
                        'Open'         => '',
                        'Closed'       => '',
                        'ApprovedOnly' => ''
                    );
                } else {
                    $backup = $old_content_data['backup'];
                }
                unset($old_content_data['backup']);


                $website = get_object('website', $this->get('Webpage Website Key'));

                $old_type = $website->get('Website Registration Type');

                $website->editor = $this->editor;
                $website->update_field_switcher($field, $value, $options);
                if ($website->updated) {
                    $this->updated;


                    //print_r($backup);
                    //print_r($old_type);

                    $backup[$old_type] = $old_content_data;

                    if (isset($backup[$value])) {
                        $this->update(array('Page Store Content Data' => json_encode($backup[$value])), 'no_history');
                    } else {
                        $this->reset_object();
                    }
                    $this->update_content_data('backup', $backup);


                }


                break;


            default:

                $base_data = $this->base_data();
                if (array_key_exists($field, $base_data)) {

                    if ($value != $this->data[$field]) {


                        $this->update_field($field, $value, $options);
                    }
                } else {
                    $this->error = true;
                    $this->msg   = "field not found ($field)";

                }

        }


    }

    function update_version() {

        if (in_array(
                $this->get('Page Store Content Template Filename'), array(
                                                                      'products_showcase',
                                                                      'categories_showcase'
                                                                  )
            ) and $this->get('Page Store Content Display Type') == 'Template') {
            $version = 2;
        } elseif ($this->get('Webpage Scope') == 'Product') {
            $version = 2;

        } else {
            $version = 1;

        }


        $this->update(array('Webpage Version' => $version), 'no_history');

    }

    function publish($note = '') {


        $website = get_object('Website', $this->get('Webpage Website Key'));


        if ($website->get('Website Status') != 'Active') {
            $this->error = true;
            $this->msg   = 'Website not active';

            return;
        }


        if ($this->get('Webpage State') == 'Offline' or $this->get('Webpage State') == 'InProcess' or $this->get('Webpage State') == 'Ready') {

            if ($this->data['Webpage Scope'] == 'Category Products' or $this->data['Webpage Scope'] == 'Category Categories') {
                $scope = get_object('Category', $this->data['Webpage Scope Key']);
                if ($scope->get('Product Category Public') == 'Yes') {
                    $this->update_state('Online');
                }
            } elseif ($this->data['Webpage Scope'] == 'Product') {
                $scope = get_object('Product', $this->data['Webpage Scope Key']);
                if ($scope->get('Product Public') == 'Yes' and in_array(
                        $scope->get('Product Web State'), array(
                                                            'For Sale',
                                                            'Out of Stock'
                                                        )
                    )) {
                    $this->update_state('Online');
                }
            } else {
                $this->update_state('Online');
            }


        }



        if ($this->get('Webpage Launch Date') == '') {
            $this->update(array('Webpage Launch Date' => gmdate('Y-m-d H:i:s')), 'no_history');
            $msg = _('Webpage launched');
            $publish_products=true;
        } else {
            $msg = _('Webpage published');
            $publish_products=false;
        }


        $content_data = $this->get('Content Data');


        $sql = sprintf(
            'UPDATE `Page Store Dimension` SET  `Page Store Content Published Data`=`Page Store Content Data`,`Page Store Published CSS`=`Page Store CSS` WHERE `Page Key`=%d ', $this->id
        );

        $this->db->exec($sql);


        $history_data = array(
            'Date'              => gmdate('Y-m-d H:i:s'),
            'Direct Object'     => 'Webpage',
            'Direct Object Key' => $this->id,
            'History Details'   => '',
            'History Abstract'  => $msg.($note != '' ? ', '.$note : ''),
        );

        $history_key = $this->add_history($history_data, $force_save = true);
        $sql         = sprintf(
            "INSERT INTO `Webpage Publishing History Bridge` VALUES (%d,%d,'No','No','Deployment')", $this->id, $history_key
        );


        $this->db->exec($sql);


        if ($this->get('Webpage Scope') == 'Category Products' and $publish_products) {


            include_once 'class.Page.php';

            $sql = sprintf(
                'UPDATE  `Product Category Index` SET  `Product Category Index Published Stack`=`Product Category Index Stack`,`Product Category Index Content Published Data`=`Product Category Index Content Data` WHERE `Product Category Index Category Key`=%d ',
                $this->get('Webpage Scope Key')
            );
            $this->db->exec($sql);


            $sql = sprintf('SELECT `Product Category Index Product ID` FROM `Product Category Index`    WHERE `Product Category Index Website Key`=%d', $this->id);


            //print "$sql\n";

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {

                    $webpage = new Page('scope', 'Product', $row['Product Category Index Product ID']);

                    // print_r($webpage);
                    //exit;

                    if ($webpage->id) {


                        // if ($webpage->get('Webpage Launch Date') == '') {


                        //  print $webpage->get('Webpage Code')."\n";

                        $webpage->publish();
                        //  }
                    }

                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


        } elseif ($this->get('Webpage Scope') == 'Product') {


            if (isset($content_data['description_block']['content'])) {
                $web_text = $content_data['description_block']['content'];
            } else {
                $web_text = '';
            }


            $product = get_object('Product', $this->get('Webpage Scope Key'));
            $product->fast_update(array('Product Published Webpage Description' => $web_text));

        }

        $sql = sprintf(
            'UPDATE  `Webpage Related Product Bridge` SET  `Webpage Related Product Content Published Data`=`Webpage Related Product Content Data`,`Webpage Related Product Published Order`=`Webpage Related Product Order` WHERE `Webpage Related Product Page Key`=%d ',
            $this->id
        );
        $this->db->exec($sql);

        $this->get_data('id', $this->id);


        if (isset($content_data['sections'])) {
            $sections = array();


            foreach ($content_data['sections'] as $section_stack_index => $section_data) {

                $categories                     = get_website_section_items($this->db, $section_data);
                $sections[$section_data['key']] = array(
                    'data'       => $section_data,
                    'categories' => $categories
                );
            }

        }


        $account = get_object('Account', 1);
        require_once 'utils/new_fork.php';
        new_housekeeping_fork(
            'au_housekeeping', array(
            'type'        => 'clean_webpage_cache',
            'webpage_key' => $this->id,
        ), $account->get('Account Code'), $this->db
        );


        $this->update_metadata = array(
            'class_html'    => array(
                'Webpage_State_Icon'    => $this->get('State Icon'),
                'Webpage_State'         => $this->get('State'),
                'preview_publish_label' => _('Publish')

            ),
            'hide_by_id'    => array(
                'republish_webpage_field',
                'launch_webpage_field'
            ),
            'show_by_id'    => array('unpublish_webpage_field'),
            'visible_by_id' => array('link_to_live_webpage'),
        );


    }

    function update_content_data($field, $value, $options = '') {

        $content_data = $this->get('Content Data');

        $content_data[$field] = $value;

        $this->update_field('Page Store Content Data', json_encode($content_data), $this->no_history);


    }

    function update_found_in($parent_keys) {


        $parent_keys = array_unique($parent_keys);

        $sql = sprintf(
            "SELECT `Page Store Found In Key` FROM  `Page Store Found In Bridge` WHERE `Page Store Key`=%d", $this->id
        );

        $keys_to_delete = array();
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if (!in_array($row['Page Store Found In Key'], $parent_keys)) {
                    $sql = sprintf(
                        "DELETE FROM  `Page Store Found In Bridge` WHERE `Page Store Key`=%d AND `Page Store Found In Key`=%d   ", $this->id, $row['Page Store Found In Key']
                    );

                    $this->db->exec($sql);
                }

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        foreach ($parent_keys as $parent_key) {

            if ($this->id != $parent_key and is_numeric($parent_key) and $parent_key > 0) {

                $sql = sprintf(
                    "INSERT INTO `Page Store Found In Bridge`  (`Page Store Key`,`Page Store Found In Key`)  VALUES (%d,%d)  ", $this->id, $parent_key
                );
                $this->db->exec($sql);


            }

        }


        $number_found_in_links = 0;

        $sql = sprintf(
            "SELECT count(*) AS num FROM  `Page Store Found In Bridge` WHERE `Page Store Key`=%d", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_found_in_links = $row['num'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $this->update(
            array('Number Found In Links' => $number_found_in_links), 'no_history'
        );


    }

    function update_category_webpage_index() {


        if ($this->get('Webpage Scope') == 'Category Categories') {

            include_once 'class.Website.php';
            $website = new Website($this->get('Webpage Website Key'));

            if ($website->get('Website Theme') == 'theme_1' and false) {


                $sql = sprintf('DELETE FROM  `Category Webpage Index` WHERE `Category Webpage Index Webpage Key`=%d  ', $this->id);
                $this->db->exec($sql);


                $content_data = $this->get('Content Data');

                $stack              = 0;
                $anchor_section_key = 0;

                foreach ($content_data['catalogue']['items'] as $item) {


                    $sql = sprintf(
                        'INSERT INTO `Category Webpage Index` (`Category Webpage Index Section Key`,`Category Webpage Index Content Data`,
                          `Category Webpage Index Parent Category Key`,`Category Webpage Index Category Key`,`Category Webpage Index Webpage Key`,`Category Webpage Index Category Webpage Key`,`Category Webpage Index Stack`) VALUES (%d,%s,%d,%d,%d,%d,%d) ',
                        $anchor_section_key, prepare_mysql(json_encode($item)), $this->get('Webpage Scope Key'), $item['category_key'], $this->id, $item['webpage_key'], $stack
                    );


                    $this->db->exec($sql);
                    $stack++;


                }


            }


        }


    }

    function reset_object() {


        $website = get_object('Website', $this->get('Webpage Website Key'));

        if ($this->get('Webpage Scope') == 'Category Products') {

            include_once 'class.Category.php';

            $category = new Category($this->get('Webpage Scope Key'));

            if ($website->get('Website Theme') == 'theme_1') {


                $items = array();


                $sql = sprintf(
                    "SELECT P.`Product ID`  FROM `Category Bridge` B  LEFT JOIN `Product Dimension` P ON (`Subject Key`=P.`Product ID`)  
                    WHERE  `Category Key`=%d  AND `Product Web State` IN  ('For Sale','Out of Stock')   ORDER BY `Product Web State` ", $category->id
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {

                        $product = get_object('Public_Product', $row['Product ID']);
                        $product->load_webpage();


                        $items[] = array(
                            'type'                    => 'product',
                            'product_id'              => $product->id,
                            'web_state'               => $product->get('Web State'),
                            'price'                   => $product->get('Price'),
                            'rrp'                     => $product->get('RRP'),
                            'header_text'             => '',
                            'code'                    => $product->get('Code'),
                            'name'                    => $product->get('Name'),
                            'link'                    => $product->webpage->get('URL'),
                            'webpage_code'            => $product->webpage->get('Webpage Code'),
                            'webpage_key'             => $product->webpage->id,
                            'image_src'               => $product->get('Image'),
                            'image_mobile_website'    => '',
                            'image_website'           => '',
                            'out_of_stock_class'      => $product->get('Out of Stock Class'),
                            'out_of_stock_label'      => $product->get('Out of Stock Label'),
                            'sort_code'               => $product->get('Code File As'),
                            'sort_name'               => $product->get('Product Name'),
                            'next_shipment_timestamp' => $product->get('Next Supplier Shipment Timestamp'),
                            'category'                => $product->get('Family Code'),
                            'raw_price'               => $product->get('Product Price'),
                        );


                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                $content_data = array(
                    'blocks' => array(

                        array(
                            'type'              => 'category_products',
                            'label'             => _('Family'),
                            'icon'              => 'fa-cubes',
                            'show'              => 1,
                            'top_margin'        => 20,
                            'bottom_margin'     => 20,
                            'item_headers'      => false,
                            'items'             => $items,
                            'sort'              => 'Manual',
                            'new_first'         => true,
                            'out_of_stock_last' => true,
                        )
                    )

                );


                $this->update(array('Page Store Content Data' => json_encode($content_data)), 'no_history');
                $this->reindex_items();
                $this->update_navigation();

            } else {
                include_once 'class.Public_Product.php';


                $title = $category->get('Label');
                if ($title == '') {
                    $title = $category->get('Code');
                }
                if ($title == '') {
                    $title = _('Title');
                }

                $description = $category->get('Product Category Description');
                if ($description == '') {
                    $description = $category->get('Label');
                }
                if ($description == '') {
                    $description = $category->get('Code');
                }
                if ($description == '') {
                    $description = _('Description');
                }


                $image_src = $category->get('Image');

                $content_data = array(
                    'description_block' => array(
                        'class' => '',

                        'blocks' => array(

                            'webpage_content_header_image' => array(
                                'type'      => 'image',
                                'image_src' => $image_src,
                                'caption'   => '',
                                'class'     => ''

                            ),

                            'webpage_content_header_text' => array(
                                'class'   => '',
                                'type'    => 'text',
                                'content' => sprintf('<h1 class="description_title">%s</h1><div class="description">%s</div>', $title, $description)

                            )

                        )
                    )

                );

                //print_r($content_data);
                $this->update(array('Page Store Content Data' => json_encode($content_data)), 'no_history');

                $content_data = $this->get('Content Data');


            }


        }
        elseif ($this->get('Webpage Scope') == 'Category Categories') {


            //  $category=get_object('Category',$this->get('Webpage Scope Key'));

            if ($website->get('Website Theme') == 'theme_1') {


                $items = array();

                $sql = sprintf(
                    "SELECT  `Webpage URL`,`Category Main Image Key`,`Category Main Image`,`Category Label`,`Category Main Image Key`,`Webpage State`,`Product Category Public`,`Webpage State`,`Page Key`,`Webpage Code`,`Product Category Active Products`,`Category Code`,B.`Category Key` ,`Product Category Key`
                  FROM    `Category Bridge` B  LEFT JOIN     `Product Category Dimension` P   ON (`Subject Key`=`Product Category Key` AND `Subject`='Category' )    LEFT JOIN `Category Dimension` Cat ON (Cat.`Category Key`=P.`Product Category Key`) LEFT JOIN `Page Store Dimension` CatWeb ON (CatWeb.`Page Key`=`Product Category Webpage Key`)  WHERE  B.`Category Key`=%d  AND `Product Category Public`='Yes'  AND `Webpage State` IN ('Online','Ready')    ORDER BY  `Category Label`  ",
                    $this->get('Webpage Scope Key')


                );

                //   print $sql;

                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $items[] = array(
                            'type'                 => 'category',
                            'category_key'         => $row['Product Category Key'],
                            'header_text'          => trim(strip_tags($row['Category Label'])),
                            'image_src'            => ($row['Category Main Image Key'] ? 'image_root.php?id='.$row['Category Main Image Key'] : '/art/nopic.png'),
                            'image_mobile_website' => '',
                            'image_website'        => '',
                            'webpage_key'          => $row['Page Key'],
                            'webpage_code'         => strtolower($row['Webpage Code']),
                            'item_type'            => 'Subject',
                            'category_code'        => $row['Category Code'],
                            'number_products'      => $row['Product Category Active Products'],
                            'link'                 => $row['Webpage URL']
                        );
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                $sections = array(

                    array(
                        'type'     => 'anchor',
                        'title'    => '',
                        'subtitle' => '',
                        'items'    => $items
                    )

                );


                $content_data = array(
                    'blocks' => array(
                        array(
                            'type'          => 'category_categories',
                            'label'         => _('Department'),
                            'icon'          => 'fa-th',
                            'show'          => 1,
                            'top_margin'    => 0,
                            'bottom_margin' => 30,
                            'sections'      => $sections
                        )
                    )

                );


                $this->update(array('Page Store Content Data' => json_encode($content_data)), 'no_history');


            } else {


                include_once 'class.Category.php';

                $category = new Category($this->get('Webpage Scope Key'));

                $sql = sprintf(
                    'DELETE FROM  `Webpage Section Dimension` WHERE `Webpage Section Webpage Key`=%d  ', $this->id

                );

                $this->db->exec($sql);

                $sql = sprintf(
                    'DELETE FROM  `Category Webpage Index` WHERE `Category Webpage Index Webpage Key`=%d  ', $this->id

                );

                $this->db->exec($sql);
                $sql = sprintf(
                    'DELETE FROM  `Category Webpage Index` WHERE `Category Webpage Index Parent Category Key`=%d  ', $this->get('Webpage Scope Key')

                );


                //  print "$sql\n";

                $this->db->exec($sql);


                $title = $category->get('Label');
                if ($title == '') {
                    $title = $category->get('Code');
                }
                if ($title == '') {
                    $title = _('Title');
                }

                $description = $category->get('Product Category Description');
                if ($description == '') {
                    $description = $category->get('Label');
                }
                if ($description == '') {
                    $description = $category->get('Code');
                }
                if ($description == '') {
                    $description = _('Description');
                }


                $image_src = $category->get('Image');

                $content_data = array(
                    'description_block' => array(
                        'class' => '',

                        'blocks' => array(

                            'webpage_content_header_image' => array(
                                'type'      => 'image',
                                'image_src' => $image_src,
                                'caption'   => '',
                                'class'     => ''

                            ),

                            'webpage_content_header_text' => array(
                                'class'   => '',
                                'type'    => 'text',
                                'content' => sprintf('<h1 class="description_title">%s</h1><div class="description">%s</div>', $title, $description)

                            )

                        )
                    ),
                    'sections'          => array()

                );

                $section = array(
                    'type'     => 'anchor',
                    'title'    => '',
                    'subtitle' => '',
                    'panels'   => array()
                );


                $sql = sprintf(
                    'INSERT INTO `Webpage Section Dimension` (`Webpage Section Webpage Key`,`Webpage Section Webpage Stack Index`,`Webpage Section Data`) VALUES (%d,%d,%s) ', $this->id, 0, prepare_mysql(json_encode($section))

                );

                //  print $sql;

                $this->db->exec($sql);

                $section['key'] = $this->db->lastInsertId();

                $content_data['sections'][] = $section;
                $this->update(array('Page Store Content Data' => json_encode($content_data)), 'no_history');

                $category->create_stack_index(true);

                // new list of

            }


        }
        elseif ($this->get('Webpage Scope') == 'Product') {


            //  $category=get_object('Category',$this->get('Webpage Scope Key'));
            if ($website->get('Website Theme') == 'theme_1') {

                $website = get_object('Website', $this->get('Webpage Website Key'));


                switch ($website->get('Website Locale')) {
                    case 'sk_SK':
                        $title = 'Pozrite si tiež';

                        break;
                    case 'fr_FR':
                        $title = 'Voir aussi';

                        break;
                    case 'it_IT':
                        $title = 'Guarda anche';

                        break;
                    case 'pl_PL':
                        $title = 'Zobacz także';

                        break;
                    case 'cs_CZ':
                        $title = 'Viz též';

                        break;
                    case 'hu_HU':
                        $title = 'Lásd még';

                        break;
                    case 'de_DE':

                        $title = 'Siehe auch';

                        break;
                    default:
                        $title = 'See also';
                }


                $product    = get_object('Public_Product', $this->get('Webpage Scope Key'));
                $image_data = $product->get('Image Data');


                $image_gallery = array();
                foreach ($product->get_image_gallery() as $image_item) {
                    if ($image_item['key'] != $image_data['key']) {
                        $image_gallery[] = $image_item;
                    }
                }

                $content_data = array(
                    'blocks' => array(
                        array(
                            'type'            => 'product',
                            'label'           => _('Product'),
                            'icon'            => 'fa-cube',
                            'show'            => 1,
                            'top_margin'      => 20,
                            'bottom_margin'   => 30,
                            'text'            => '',
                            'show_properties' => true,

                            'image'        => array(
                                'key'           => $image_data['key'],
                                'src'           => $image_data['src'],
                                'caption'       => $image_data['caption'],
                                'width'         => $image_data['width'],
                                'height'        => $image_data['height'],
                                'image_website' => $image_data['image_website']

                            ),
                            'other_images' => $image_gallery


                        ),
                        array(
                            'type'              => 'see_also',
                            'auto'              => true,
                            'auto_scope'        => 'webpage',
                            'auto_items'        => 5,
                            'auto_last_updated' => '',
                            'label'             => _('See also'),
                            'icon'              => 'fa-link',
                            'show'              => 1,
                            'top_margin'        => 0,
                            'bottom_margin'     => 40,
                            'item_headers'      => false,
                            'items'             => array(),
                            'sort'              => 'Manual',
                            'title'             => $title,
                            'show_title'        => true
                        )
                    )

                );


                $this->update(array('Page Store Content Data' => json_encode($content_data)), 'no_history');

                $this->reindex_items();
                $this->refill_see_also();
                $this->update_navigation();


            } else {


            }


        } else {


            include_once 'conf/website_system_webpages.php';

            $website = get_object('Website',$this->get('Webpage Website Key'));

            $website_system_webpages = website_system_webpages_config($website->get('Website Type'));


            if (isset($website_system_webpages[$this->get('Webpage Code')]['Page Store Content Data'])) {




                $this->update(array('Page Store Content Data' => $website_system_webpages[$this->get('Webpage Code')]['Page Store Content Data']), 'no_history');
            }




        }


    }

    function update_navigation() {

        $navigation_data = array(
            'show'        => false,
            'breadcrumbs' => array(),
            'prev'        => false,
            'next'        => false,
        );


        switch ($this->get('Webpage Scope')) {
            case 'Category Products':

                $website = get_object('Website', $this->data['Webpage Website Key']);

                $category = get_object('Category', $this->data['Webpage Scope Key']);


                $parent_webpage_key = 0;

                if ($category->get('Product Category Department Category Key')) {
                    $parent         = get_object('Category', $category->get('Product Category Department Category Key'));
                    $parent_webpage = get_object('Webpage', $parent->get('Product Category Webpage Key'));
                    if ($parent_webpage->get('Webpage State') == 'Offline') {
                        $parent_webpage_key = 0;
                    } else {
                        $parent_webpage_key = $parent_webpage->id;
                    }

                }


                // print_r($parent_webpage);

                $navigation_data['breadcrumbs'][] = array(
                    'link'        => 'https://'.$website->get('Website URL'),
                    'label'       => '<i class="fa fa-home"></i>',
                    'label_short' => '<i class="fa fa-home"></i>',
                    'title'       => _('Home')
                );


                if ($parent_webpage_key) {
                    $navigation_data['breadcrumbs'][] = array(
                        'link'        => $parent_webpage->get('Webpage URL'),
                        'label'       => $parent_webpage->get('Name'),
                        'label_short' => $parent_webpage->get('Webpage Code'),
                        'title'       => $parent_webpage->get('Webpage Browser Title'),
                    );
                }


                //print_r($parent_webpage);

                $prev = false;
                $next = false;

                $next_key = 0;
                $prev_key = 0;

                $sql = sprintf(
                    'SELECT `Website Webpage Scope Index` FROM `Website Webpage Scope Map` WHERE `Website Webpage Scope Webpage Key`=%d  AND `Website Webpage Scope Scope`="Category" AND `Website Webpage Scope Scope Key`=%d ', $parent_webpage_key, $category->id

                );
                // print $sql;

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {

                        $sql = sprintf(
                            'SELECT `Website Webpage Scope Scope Key` FROM `Website Webpage Scope Map` WHERE `Website Webpage Scope Webpage Key`=%d  AND  `Website Webpage Scope Type`="Subject" AND  `Website Webpage Scope Scope`="Category" AND `Website Webpage Scope Index`<%d ORDER BY `Website Webpage Scope Index` DESC',
                            $parent_webpage_key, $row['Website Webpage Scope Index']
                        );

                        //print $sql;

                        if ($result2 = $this->db->query($sql)) {
                            if ($row2 = $result2->fetch()) {
                                $prev_key = $row2['Website Webpage Scope Scope Key'];

                            } else {

                                $sql = sprintf(
                                    'SELECT `Website Webpage Scope Scope Key` FROM `Website Webpage Scope Map`  WHERE `Website Webpage Scope Webpage Key`=%d  AND  `Website Webpage Scope Type`="Subject" AND `Website Webpage Scope Scope`="Category"  ORDER BY `Website Webpage Scope Index` DESC ',
                                    $parent_webpage_key
                                );
                                //print $sql;
                                if ($result3 = $this->db->query($sql)) {
                                    if ($row3 = $result3->fetch()) {
                                        $prev_key = $row3['Website Webpage Scope Scope Key'];
                                    }
                                } else {
                                    print_r($error_info = $this->db->errorInfo());
                                    print "$sql\n";
                                    exit;
                                }

                            }


                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            print "$sql\n";
                            exit;
                        }


                        $sql = sprintf(
                            'SELECT `Website Webpage Scope Scope Key` FROM `Website Webpage Scope Map`  WHERE `Website Webpage Scope Webpage Key`=%d  AND  `Website Webpage Scope Type`="Subject" AND `Website Webpage Scope Scope`="Category" AND `Website Webpage Scope Index`>%d ORDER BY `Website Webpage Scope Index` ',
                            $parent_webpage_key, $row['Website Webpage Scope Index']
                        );

                        if ($result2 = $this->db->query($sql)) {
                            if ($row2 = $result2->fetch()) {
                                $next_key = $row2['Website Webpage Scope Scope Key'];
                            } else {

                                $sql = sprintf(
                                    'SELECT `Website Webpage Scope Scope Key` FROM `Website Webpage Scope Map`  WHERE `Website Webpage Scope Webpage Key`=%d  AND  `Website Webpage Scope Type`="Subject" AND `Website Webpage Scope Scope`="Category"  ORDER BY `Website Webpage Scope Index` ',
                                    $parent_webpage_key
                                );

                                if ($result3 = $this->db->query($sql)) {
                                    if ($row3 = $result3->fetch()) {
                                        $next_key = $row3['Website Webpage Scope Scope Key'];
                                    }
                                } else {
                                    print_r($error_info = $this->db->errorInfo());
                                    print "$sql\n";
                                    exit;
                                }

                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            print "$sql\n";
                            exit;
                        }


                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

                if ($prev_key) {

                    $prev_category         = get_object('Category', $prev_key);
                    $prev_category_webpage = get_object('Webpage', $prev_category->get('Product Category Webpage Key'));
                    $prev                  = array(
                        'link'        => $prev_category_webpage->get('Webpage URL'),
                        'label'       => $prev_category_webpage->get('Name'),
                        'label_short' => $prev_category_webpage->get('Webpage Code'),
                        'title'       => $prev_category_webpage->get('Webpage Browser Title'),
                    );
                }


                if ($next_key) {

                    $next_category         = get_object('Category', $next_key);
                    $next_category_webpage = get_object('Webpage', $next_category->get('Product Category Webpage Key'));
                    $next                  = array(
                        'link'        => $next_category_webpage->get('Webpage URL'),
                        'label'       => $next_category_webpage->get('Name'),
                        'label_short' => $next_category_webpage->get('Webpage Code'),
                        'title'       => $next_category_webpage->get('Webpage Browser Title'),
                    );
                }


                if ($next_key or $prev_key or $parent_webpage_key) {
                    $navigation_data['show'] = 1;
                }

                //  print $prev_key;

                // print $next_key;


                $navigation_data['prev'] = $prev;
                $navigation_data['next'] = $next;
                // print_r($navigation_data);


                $this->update_field('Webpage Navigation Data', json_encode($navigation_data), 'no_history');


                //print_r($this);
                break;

            case 'Product':


                $website = get_object('Website', $this->data['Webpage Website Key']);

                $product = get_object('Product', $this->data['Webpage Scope Key']);


                $parent_webpage_key = 0;


                if ($product->get('Product Family Category Key')) {
                    $parent         = get_object('Category', $product->get('Product Family Category Key'));
                    $parent_webpage = get_object('Webpage', $parent->get('Product Category Webpage Key'));
                    if ($parent_webpage->get('Webpage State') == 'Offline') {
                        $parent_webpage_key = 0;
                    } else {
                        $parent_webpage_key = $parent_webpage->id;
                    }

                }


                $navigation_data['breadcrumbs'][] = array(
                    'link'        => 'https://'.$website->get('Website URL'),
                    'label'       => '<i class="fa fa-home"></i>',
                    'label_short' => '<i class="fa fa-home"></i>',
                    'title'       => _('Home')
                );


                if ($parent_webpage_key) {


                    $grandparent_webpage_key = 0;

                    if ($parent->get('Product Category Department Category Key')) {
                        $grandparent         = get_object('Category', $parent->get('Product Category Department Category Key'));
                        $grandparent_webpage = get_object('Webpage', $grandparent->get('Product Category Webpage Key'));
                        if ($grandparent_webpage->get('Webpage State') == 'Offline') {
                            $grandparent_webpage_key = 0;
                        } else {
                            $grandparent_webpage_key = $grandparent_webpage->id;
                        }

                    }

                    if ($grandparent_webpage_key) {
                        $navigation_data['breadcrumbs'][] = array(
                            'link'        => $grandparent_webpage->get('Webpage URL'),
                            'label'       => $grandparent_webpage->get('Name'),
                            'label_short' => $grandparent_webpage->get('Webpage Code'),
                            'title'       => $grandparent_webpage->get('Webpage Browser Title'),
                        );
                    }


                    $navigation_data['breadcrumbs'][] = array(
                        'link'        => $parent_webpage->get('Webpage URL'),
                        'label'       => $parent_webpage->get('Name'),
                        'label_short' => $parent_webpage->get('Webpage Code'),
                        'title'       => $parent_webpage->get('Webpage Browser Title'),
                    );
                }

                //print_r($parent_webpage);

                $prev = false;
                $next = false;

                $next_key = 0;
                $prev_key = 0;

                $sql = sprintf(
                    'SELECT `Website Webpage Scope Index` FROM `Website Webpage Scope Map` WHERE `Website Webpage Scope Webpage Key`=%d  AND `Website Webpage Scope Scope`="Product" AND `Website Webpage Scope Scope Key`=%d ', $parent_webpage_key, $product->id

                );


                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {

                        $sql = sprintf(
                            'SELECT `Website Webpage Scope Scope Key` FROM `Website Webpage Scope Map` WHERE `Website Webpage Scope Webpage Key`=%d  AND  `Website Webpage Scope Type`="Category_Products_Item" AND  `Website Webpage Scope Scope`="Product" AND `Website Webpage Scope Index`<%d ORDER BY `Website Webpage Scope Index` DESC',
                            $parent_webpage_key, $row['Website Webpage Scope Index']
                        );


                        if ($result2 = $this->db->query($sql)) {
                            if ($row2 = $result2->fetch()) {
                                $prev_key = $row2['Website Webpage Scope Scope Key'];

                            } else {

                                $sql = sprintf(
                                    'SELECT `Website Webpage Scope Scope Key` FROM `Website Webpage Scope Map`  WHERE `Website Webpage Scope Webpage Key`=%d  AND  `Website Webpage Scope Type`="Category_Products_Item" AND `Website Webpage Scope Scope`="Product"  ORDER BY `Website Webpage Scope Index` DESC ',
                                    $parent_webpage_key
                                );
                                //print $sql;
                                if ($result3 = $this->db->query($sql)) {
                                    if ($row3 = $result3->fetch()) {
                                        $prev_key = $row3['Website Webpage Scope Scope Key'];
                                    }
                                } else {
                                    print_r($error_info = $this->db->errorInfo());
                                    print "$sql\n";
                                    exit;
                                }

                            }


                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            print "$sql\n";
                            exit;
                        }


                        $sql = sprintf(
                            'SELECT `Website Webpage Scope Scope Key` FROM `Website Webpage Scope Map`  WHERE `Website Webpage Scope Webpage Key`=%d  AND  `Website Webpage Scope Type`="Category_Products_Item" AND `Website Webpage Scope Scope`="Product" AND `Website Webpage Scope Index`>%d ORDER BY `Website Webpage Scope Index` ',
                            $parent_webpage_key, $row['Website Webpage Scope Index']
                        );

                        // print $sql;


                        if ($result2 = $this->db->query($sql)) {
                            if ($row2 = $result2->fetch()) {
                                $next_key = $row2['Website Webpage Scope Scope Key'];
                            } else {

                                $sql = sprintf(
                                    'SELECT `Website Webpage Scope Scope Key` FROM `Website Webpage Scope Map`  WHERE `Website Webpage Scope Webpage Key`=%d  AND  `Website Webpage Scope Type`="Category_Products_Item" AND `Website Webpage Scope Scope`="Product"  ORDER BY `Website Webpage Scope Index` ',
                                    $parent_webpage_key
                                );

                                if ($result3 = $this->db->query($sql)) {
                                    if ($row3 = $result3->fetch()) {
                                        $next_key = $row3['Website Webpage Scope Scope Key'];
                                    }
                                } else {
                                    print_r($error_info = $this->db->errorInfo());
                                    print "$sql\n";
                                    exit;
                                }

                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            print "$sql\n";
                            exit;
                        }


                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

                if ($prev_key) {

                    $prev_product         = get_object('Product', $prev_key);
                    $prev_product_webpage = get_object('Webpage', $prev_product->get('Product Webpage Key'));
                    $prev                 = array(
                        'link'        => $prev_product_webpage->get('Webpage URL'),
                        'label'       => $prev_product_webpage->get('Name'),
                        'label_short' => $prev_product_webpage->get('Webpage Code'),
                        'title'       => $prev_product_webpage->get('Webpage Browser Title'),
                    );
                }


                if ($next_key) {

                    $next_product         = get_object('Product', $next_key);
                    $next_product_webpage = get_object('Webpage', $next_product->get('Product Webpage Key'));
                    $next                 = array(
                        'link'        => $next_product_webpage->get('Webpage URL'),
                        'label'       => $next_product_webpage->get('Name'),
                        'label_short' => $next_product_webpage->get('Webpage Code'),
                        'title'       => $next_product_webpage->get('Webpage Browser Title'),
                    );
                }


                if ($next_key or $prev_key or $parent_webpage_key) {
                    $navigation_data['show'] = 1;
                }

                //  print $prev_key;

                // print $next_key;


                $navigation_data['prev'] = $prev;
                $navigation_data['next'] = $next;
                //  print_r($navigation_data);

                $this->update_field('Webpage Navigation Data', json_encode($navigation_data), 'no_history');


                break;

            default:


        }


    }

    function refill_see_also($convert_to_auto = false, $number_items = false) {

        $content_data = $this->get('Content Data');
        $block_found  = false;


        if ($content_data == '') {
            return;
        }
        if (!isset($content_data['blocks'])) {
            return;
        }

        foreach ($content_data['blocks'] as $_block_key => $_block) {
            if ($_block['type'] == 'see_also') {
                $block_key   = $_block_key;
                $block_found = true;
                break;
            }
        }

        if (!$block_found) {
            return;
        }

        if ($convert_to_auto) {
            $content_data['blocks'][$block_key]['auto'] = true;
        }
        if (is_numeric($number_items) and $number_items > 0) {
            $content_data['blocks'][$block_key]['auto_items'] = $number_items;
        }


        $items = array();


        if ($content_data['blocks'][$block_key]['auto']) {


            foreach ($this->get_related_webpages_key($content_data['blocks'][$block_key]['auto_items']) as $webpage_key) {
                $see_also_page = get_object('Webpage', $webpage_key);

                switch ($see_also_page->get('Webpage Scope')) {
                    case'Category Products' :
                    case'Category Categories' :
                        $category = get_object('Category', $see_also_page->get('Webpage Scope Key'));
                        $items[]  = array(
                            'type' => 'category',

                            'header_text'          => $category->get('Category Label'),
                            'image_src'            => $category->get('Image'),
                            'image_mobile_website' => '',
                            'image_website'        => '',

                            'webpage_key'  => $see_also_page->id,
                            'webpage_code' => $see_also_page->get('Webpage Code'),

                            'category_key'    => $category->id,
                            'category_code'   => $category->get('Category Code'),
                            'number_products' => $category->get('Product Category Active Products'),
                            'link'            => $see_also_page->get('Webpage URL'),


                        );
                        break;
                    case 'Product':

                        $product = get_object('Public_Product', $see_also_page->get('Webpage Scope Key'));


                        $items[] = array(
                            'type' => 'product',

                            'header_text'          => $product->get('Name'),
                            'image_src'            => $product->get('Image'),
                            'image_mobile_website' => '',
                            'image_website'        => '',

                            'webpage_key'  => $see_also_page->id,
                            'webpage_code' => $see_also_page->get('Webpage Code'),

                            'product_id'        => $product->id,
                            'product_code'      => $product->get('Code'),
                            'product_web_state' => $product->get('Web State'),
                            'link'              => $see_also_page->get('Webpage URL'),

                        );
                        break;
                }
            }


            $content_data['blocks'][$block_key]['items'] = $items;
            $this->update_field_switcher('Page Store Content Data', json_encode($content_data), 'no_history');

        }

        $this->reindex_see_also();


    }

    function get_related_webpages_key($number_items) {

        $max_links = $number_items * 2;


        $max_sales_links = ceil($max_links * .6);


        // $min_sales_correlation_samples = 5;
        // $correlation_upper_limit       = .5 / ($min_sales_correlation_samples);
        $see_also     = array();
        $number_links = 0;
        $items        = array();

        switch ($this->data['Webpage Scope']) {


            case 'Category Products':


                $sql = sprintf(
                    "SELECT `Category B Key`,`Correlation` FROM `Product Category Sales Correlation` WHERE `Category A Key`=%d ORDER BY `Correlation` DESC ", $this->data['Webpage Scope Key']
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $_family  = get_object('Category', $row['Category B Key']);
                        $_webpage = $_family->get_webpage();
                        // and $_webpage->data['Page Stealth Mode'] == 'No'
                        if ($_webpage->id and $_webpage->data['Page State'] == 'Online') {
                            $see_also[$_webpage->id] = array(
                                'type'     => 'Sales',
                                'value'    => $row['Correlation'],
                                'page_key' => $_webpage->id
                            );
                            $number_links            = count($see_also);
                            if ($number_links >= $max_sales_links) {
                                break;
                            }
                        }
                    }
                }


                if ($number_links < $max_links) {
                    $sql = sprintf(
                        "SELECT * FROM `Product Family Semantic Correlation` WHERE `Family A Key`=%d ORDER BY `Weight` DESC LIMIT %d", $this->data['Webpage Scope Key'], ($max_links - $number_links) * 2
                    );


                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {

                            if (!array_key_exists($row['Family B Key'], $see_also)) {


                                $_family  = get_object('Category', $row['Family B Key']);
                                $_webpage = $_family->get_webpage();
                                // and $_webpage->data['Page Stealth Mode'] == 'No'
                                if ($_webpage->id and $_webpage->data['Page State'] == 'Online') {
                                    $see_also[$_webpage->id] = array(
                                        'type'     => 'Semantic',
                                        'value'    => $row['Weight'],
                                        'page_key' => $_webpage->id
                                    );
                                    $number_links            = count($see_also);
                                    if ($number_links >= $max_links) {
                                        break;
                                    }
                                }


                            }

                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        exit;
                    }


                }


                if ($number_links < $max_links) {

                    $category = get_object('Category', $this->data['Webpage Scope Key']);

                    $sql = sprintf(
                        "SELECT `Category Key` FROM `Category Dimension` LEFT JOIN   `Product Category Dimension` ON (`Category Key`=`Product Category Key`)   LEFT JOIN `Page Store Dimension` ON (`Product Category Webpage Key`=`Page Key`) WHERE `Category Parent Key`=%d  AND `Webpage State`='Online'  AND `Category Key`!=%d  ORDER BY RAND()  LIMIT %d",
                        $category->get('Category Parent Key'), $category->id, ($max_links - $number_links) * 2
                    );


                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {

                            if (!array_key_exists($row['Category Key'], $see_also)) {


                                $_family  = get_object('Category', $row['Category Key']);
                                $_webpage = $_family->get_webpage();
                                // and $_webpage->data['Page Stealth Mode'] == 'No'
                                if ($_webpage->id and $_webpage->data['Page State'] == 'Online') {
                                    $see_also[$_webpage->id] = array(
                                        'type'     => 'SameParent',
                                        'value'    => .2,
                                        'page_key' => $_webpage->id
                                    );
                                    $number_links            = count($see_also);
                                    if ($number_links >= $max_links) {
                                        break;
                                    }
                                }


                            }

                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        exit;
                    }


                }


                if ($number_links < $max_links) {


                    $sql = sprintf(
                        "SELECT `Category Key` FROM `Category Dimension` LEFT JOIN   `Product Category Dimension` ON (`Category Key`=`Product Category Key`)   LEFT JOIN `Page Store Dimension` ON (`Product Category Webpage Key`=`Page Key`) WHERE  `Webpage State`='Online'  AND `Category Key`!=%d  AND `Category Store Key`=%d ORDER BY RAND()  LIMIT %d",
                        $this->data['Webpage Scope Key'], $category->get('Category Store Key'), ($max_links - $number_links) * 2
                    );


                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {

                            if (!array_key_exists($row['Category Key'], $see_also)) {


                                $_family  = get_object('Category', $row['Category Key']);
                                $_webpage = $_family->get_webpage();
                                // and $_webpage->data['Page Stealth Mode'] == 'No'
                                if ($_webpage->id and $_webpage->data['Page State'] == 'Online') {
                                    $see_also[$_webpage->id] = array(
                                        'type'     => 'Other',
                                        'value'    => .1,
                                        'page_key' => $_webpage->id
                                    );
                                    $number_links            = count($see_also);
                                    if ($number_links >= $max_links) {
                                        break;
                                    }
                                }


                            }

                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        exit;
                    }


                }


                break;


            case 'Product':

                $product = get_object('Product', $this->data['Webpage Scope Key']);
                $sql     = sprintf(
                    "SELECT `Product Webpage Key`,`Product B ID`,`Correlation` FROM `Product Sales Correlation`  LEFT JOIN `Product Dimension` ON (`Product ID`=`Product B ID`)    LEFT JOIN `Page Store Dimension` ON (`Page Key`=`Product Webpage Key`)  WHERE `Product A ID`=%d AND `Webpage State`='Online' AND `Product Web State`='For Sale'  ORDER BY `Correlation` DESC",
                    $product->id
                );


                //  $see_also_page->data['Page Stealth Mode'] == 'No')

                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        if (!array_key_exists($row['Product B ID'], $see_also) and $row['Product Webpage Key']) {

                            $see_also[$row['Product Webpage Key']] = array(
                                'type'     => 'Sales',
                                'value'    => $row['Correlation'],
                                'page_key' => $row['Product Webpage Key']
                            );
                            $number_links                          = count($see_also);
                            if ($number_links >= $max_links) {
                                break;
                            }

                        }
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                if ($number_links >= $max_links) {
                    break;
                }


                $max_customers = 0;

                $sql = sprintf(
                    "SELECT P.`Product ID`,P.`Product Code`,`Product Web State`,`Product Webpage Key`,`Product Total Acc Customers` FROM `Product Dimension` P LEFT JOIN `Product Data Dimension` D ON (P.`Product ID`=D.`Product ID`)    LEFT JOIN `Page Store Dimension` ON (`Page Key`=`Product Webpage Key`)  WHERE  `Product Web State`='For Sale' AND `Webpage State`='Online' AND P.`Product ID`!=%d  AND `Product Family Category Key`=%d ORDER BY `Product Total Acc Customers` DESC  ",
                    $product->id, $product->get('Product Family Category Key')

                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {


                        if (!array_key_exists($row['Product ID'], $see_also) and $row['Product Webpage Key']) {


                            if ($max_customers == 0) {
                                $max_customers = $row['Product Total Acc Customers'];
                            }


                            $rnd = mt_rand() / mt_getrandmax();

                            $see_also[$row['Product Webpage Key']] = array(
                                'type'     => 'Same Family',
                                'value'    => .25 * $rnd * ($row['Product Total Acc Customers'] == 0 ? 1 : $row['Product Total Acc Customers']) / ($max_customers == 0 ? 1 : $max_customers),
                                'page_key' => $row['Product Webpage Key']
                            );
                            $number_links                          = count($see_also);
                            if ($number_links >= $max_links) {
                                break;
                            }
                        }

                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

                if ($number_links >= $max_links) {
                    break;
                }
                $max_customers = 0;
                $sql           = sprintf(
                    "SELECT P.`Product ID`,P.`Product Code`,`Product Web State`,`Product Webpage Key`,`Product Total Acc Customers` FROM `Product Dimension` P LEFT JOIN `Product Data Dimension` D ON (P.`Product ID`=D.`Product ID`)    LEFT JOIN `Page Store Dimension` ON (`Page Key`=`Product Webpage Key`)  WHERE  `Product Web State`='For Sale' AND `Webpage State`='Online' AND P.`Product ID`!=%d  AND `Product Store Key`=%d ORDER BY `Product Total Acc Customers` DESC  ",
                    $product->id, $product->get('Product Store Key')

                );

                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {


                        if (!array_key_exists($row['Product ID'], $see_also) and $row['Product Webpage Key']) {

                            if ($max_customers == 0) {
                                $max_customers = $row['Product Total Acc Customers'];
                            }


                            $rnd = mt_rand() / mt_getrandmax();

                            $see_also[$row['Product Webpage Key']] = array(
                                'type'     => 'Other',
                                'value'    => .1 * $rnd * ($row['Product Total Acc Customers'] == 0 ? 1 : $row['Product Total Acc Customers']) / ($max_customers == 0 ? 1 : $max_customers),
                                'page_key' => $row['Product Webpage Key']
                            );
                            $number_links                          = count($see_also);
                            if ($number_links >= $max_links) {
                                break;
                            }
                        }

                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                break;
        }

        $count = 0;

        $order_value = 1;


        if (count($see_also) > 0) {


            foreach ($see_also as $key => $row) {
                $correlation[$key] = $row['value'];
            }

            //print_r($correlation);

            array_multisort($correlation, SORT_DESC, $see_also);
            // print_r($see_also);


            foreach ($see_also as $see_also_page_key => $see_also_data) {


                if ($count >= $number_items) {
                    break;
                }
                $items[] = $see_also_data['page_key'];

                $count++;
                $order_value++;
                //print "$sql\n";
            }

        }

        return $items;


    }

    function reindex_see_also() {

        $content_data = $this->get('Content Data');
        $block_found  = false;
        foreach ($content_data['blocks'] as $_block_key => $_block) {
            if ($_block['type'] == 'see_also') {
                $block       = $_block;
                $block_key   = $_block_key;
                $block_found = true;
                break;
            }
        }

        if (!$block_found) {
            return;
        }


        //    print_r($block['items']);

        foreach ($block['items'] as $item_key => $item) {


            if ($item['type'] == 'category') {

                $sql = sprintf(
                    "SELECT `Category Label`,`Webpage URL`,`Webpage Code`,`Page Key`,`Category Code`,`Product Category Active Products`,`Category Main Image Key`
                   `Product Category Dimension` P      LEFT JOIN 
                   `Category Dimension` Cat ON (Cat.`Category Key`=P.`Product Category Key`) 
                   LEFT JOIN `Page Store Dimension` CatWeb ON (CatWeb.`Page Key`=`Product Category Webpage Key`)  
                WHERE  `Product Category Key`=%d  AND `Product Category Public`='Yes'  AND `Webpage State` IN ('Online','Ready')  ", $item['category_key']


                );


                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {


                        if ($block['auto'] == true) {
                            $content_data['blocks'][$block_key]['items'][$item_key]['header_text'] = $row['Category Label'];


                            $image_key = $this->data['Category Main Image Key'];


                            if ($image_key) {
                                $image_src = '/image_root.php?id='.$image_key;
                            } else {
                                $image_src = '/art/nopic.png';

                            }

                            if ($image_src != $content_data['blocks'][$block_key]['items'][$item_key]['image_src']) {

                                $content_data['blocks'][$block_key]['items'][$item_key]['image_src'] = $image_src;

                                $content_data['blocks'][$block_key]['items'][$item_key]['image_mobile_website'] = '';
                                $content_data['blocks'][$block_key]['items'][$item_key]['image_website']        = '';
                            }


                        }


                        $content_data['blocks'][$block_key]['items'][$item_key]['category_code']   = $row['Webpage URL'];
                        $content_data['blocks'][$block_key]['items'][$item_key]['number_products'] = $row['Product Category Active Products'];


                        $content_data['blocks'][$block_key]['items'][$item_key]['link']         = $row['Webpage URL'];
                        $content_data['blocks'][$block_key]['items'][$item_key]['webpage_code'] = $row['Webpage Code'];
                        $content_data['blocks'][$block_key]['items'][$item_key]['webpage_key']  = $row['`Page Key'];


                    } else {
                        unset($content_data['blocks'][$block_key]['items'][$item_key]);
                    }
                }


            } elseif ($item['type'] == 'product') {


                $sql = sprintf('SELECT `Product Web State` FROM `Product Dimension` WHERE `Product ID`=%d', $item['product_id']);
                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        if ($row['Product Web State'] == 'For Sale' or $row['Product Web State'] == 'Out of Stock') {

                            $product = get_object('Public_Product', $item['product_id']);
                            $product->load_webpage();


                            if ($block['auto'] == true) {
                                $content_data['blocks'][$block_key]['items'][$item_key]['header_text'] = $product->get('Name');

                                $image_src = $product->get('Image');
                                if ($image_src != $content_data['blocks'][$block_key]['items'][$item_key]['image_src']) {

                                    $content_data['blocks'][$block_key]['items'][$item_key]['image_src'] = $image_src;

                                    $content_data['blocks'][$block_key]['items'][$item_key]['image_mobile_website'] = '';
                                    $content_data['blocks'][$block_key]['items'][$item_key]['image_website']        = '';
                                }

                            }


                            $content_data['blocks'][$block_key]['items'][$item_key]['link']         = $product->webpage->get('URL');
                            $content_data['blocks'][$block_key]['items'][$item_key]['webpage_code'] = $product->webpage->get('Webpage Code');
                            $content_data['blocks'][$block_key]['items'][$item_key]['webpage_key']  = $product->webpage->id;


                        } else {
                            unset($content_data['blocks'][$block_key]['items'][$item_key]);

                        }

                    } else {
                        unset($content_data['blocks'][$block_key]['items'][$item_key]);
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

            }


        }


        if ($block['auto'] and count($block['items']) < $block['auto_items']) {

            foreach ($this->get_related_webpages_key($block['auto_items']) as $webpage_key) {
                $found = false;
                foreach ($block['items'] as $item_key => $item) {
                    if ($webpage_key == $item['webpage_key']) {
                        $found = true;
                        break;
                    }
                }

                if (!$found) {
                    $see_also_page = get_object('Webpage', $webpage_key);

                    if ($see_also_page->get('Webpage Scope') == 'Category Products' or $see_also_page->get('Webpage Scope') == 'Category Categories') {
                        $category = get_object('Category', $see_also_page->get('Webpage Scope Key'));


                        $content_data['blocks'][$block_key]['items'][] = array(
                            'type' => 'category',

                            'header_text'          => $category->get('Category Label'),
                            'image_src'            => $category->get('Image'),
                            'image_mobile_website' => '',
                            'image_website'        => '',

                            'webpage_key'  => $see_also_page->id,
                            'webpage_code' => $see_also_page->get('Webpage Code'),

                            'category_key'    => $category->id,
                            'category_code'   => $category->get('Category Code'),
                            'number_products' => $category->get('Product Category Active Products'),
                            'link'            => $see_also_page->get('Webpage URL'),


                        );
                    } elseif ($see_also_page->get('Webpage Scope') == 'Product') {

                        $product = get_object('Public_Product', $see_also_page->get('Webpage Scope Key'));


                        $content_data['blocks'][$block_key]['items'][] = array(
                            'type' => 'product',

                            'header_text'          => $product->get('Name'),
                            'image_src'            => $product->get('Image'),
                            'image_mobile_website' => '',
                            'image_website'        => '',

                            'webpage_key'  => $see_also_page->id,
                            'webpage_code' => $see_also_page->get('Webpage Code'),

                            'product_id'        => $product->id,
                            'product_code'      => $product->get('Code'),
                            'product_web_state' => $product->get('Web State'),
                            'link'              => $see_also_page->get('Webpage URL'),


                        );
                    }


                }


            }

        }


        $this->update_field_switcher('Page Store Content Data', json_encode($content_data), 'no_history');
        $sql = sprintf('DELETE FROM `Website Webpage Scope Map` WHERE `Website Webpage Scope Webpage Key`=%d  AND `Website Webpage Scope Type` IN  ("See_Also_Category_Manual","See_Also_Category_Auto","See_Also_Product_Manual","See_Also_Product_Auto") ', $this->id);
        $this->db->exec($sql);

        $index = 0;
        foreach ($content_data['blocks'][$block_key]['items'] as $item) {

            //  print_r($item);


            $sql = sprintf(
                'INSERT INTO `Website Webpage Scope Map` (`Website Webpage Scope Website Key`,`Website Webpage Scope Webpage Key`,`Website Webpage Scope Scope`,`Website Webpage Scope Scope Key`,`Website Webpage Scope Type`,`Website Webpage Scope Index`) VALUES (%d,%d,%s,%d,%s,%d) ',
                $this->get('Webpage Website Key'), $this->id, prepare_mysql(capitalize($item['type'])), ($item['type'] == 'category' ? $item['category_key'] : $item['product_id']),
                prepare_mysql('See_Also_'.capitalize($item['type']).'_'.($block['auto'] ? 'Auto' : 'Manual')), $index

            );
            //print "$sql\n";

            $this->db->exec($sql);
            $index++;


        }


    }

    function reindex_category_categories() {

        include_once('utils/image_functions.php');


        $content_data = $this->get('Content Data');


        $block_found = false;

        if (!isset($content_data['blocks'])) {
            return;
        }

        foreach ($content_data['blocks'] as $_block_key => $_block) {
            if ($_block['type'] == 'category_categories') {
                $block       = $_block;
                $block_key   = $_block_key;
                $block_found = true;
                break;
            }
        }

        if (!$block_found) {
            return;
        }

      //  print_r($content_data);


        $sql = sprintf(
            "SELECT  `Webpage URL`,`Category Main Image Key`,`Category Main Image`,`Category Label`,`Category Main Image Key`,`Webpage State`,`Product Category Public`,`Webpage State`,`Page Key`,`Webpage Code`,`Product Category Active Products`,`Category Code`,Cat.`Category Key` 
                FROM    `Category Bridge` B  LEFT JOIN     `Product Category Dimension` P   ON (`Subject Key`=`Product Category Key` AND `Subject`='Category' )    LEFT JOIN `Category Dimension` Cat ON (Cat.`Category Key`=P.`Product Category Key`) LEFT JOIN `Page Store Dimension` CatWeb ON (CatWeb.`Page Key`=`Product Category Webpage Key`)  WHERE  B.`Category Key`=%d  AND `Product Category Public`='Yes'  AND `Webpage State` IN ('Online','Ready')  ORDER BY  `Category Label` DESC   ",
            $this->get('Webpage Scope Key')


        );

        // print $sql;

        $items                    = array();
        $items_category_key_index = array();

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {



                $items[$row['Category Key']]                    = $row;
                $items_category_key_index[$row['Category Key']] = $row['Category Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $offline_items_category_key_index = array();
        $sql                              = sprintf(
            "SELECT  B.`Category Key` FROM    `Category Bridge` B  LEFT JOIN     `Product Category Dimension` P   ON (`Subject Key`=`Product Category Key` AND `Subject`='Category' )    LEFT JOIN `Category Dimension` Cat ON (Cat.`Category Key`=P.`Product Category Key`) LEFT JOIN `Page Store Dimension` CatWeb ON (CatWeb.`Page Key`=`Product Category Webpage Key`)  
            WHERE  B.`Category Key`=%d  AND  (`Product Category Public`='No'  OR `Webpage State` NOT IN ('Online','Ready')  )  ", $this->get('Webpage Scope Key')


        );
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                $offline_items_category_key_index[$row['Category Key']] = $row['Category Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $anchor_section_key = 0;

        foreach ($block['sections'] as $section_key => $section) {

            if (isset($section['type']) and $section['type'] == 'anchor') {
                $anchor_section_key = $section_key;
            }

            if(isset($section['items']) and is_array($section['items']) ){
                foreach ($section['items'] as $item_key => $item) {
                    if ($item['type'] == 'category') {


                        //print $item['category_key'];
                        //print_r($items_category_key_index);
                        //exit;




                        if (in_array($item['category_key'], $items_category_key_index)) {

                            $item_data = $items[$item['category_key']];





/*
                            print_r($content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]);


                            print_r($item_data);



                            if (preg_match('/id=(\d+)/', $item_data['Category Main Image'], $matches)) {

                                $image_mobile_website=create_cached_image($matches[1], 320, 200);
                                $image_website = create_cached_image($matches[1], 432, 330, 'fit_highest');

                            }else{
                                $image_mobile_website= 'art/nopic_mobile.png';
                                $image_website= $item_data['Category Main Image'];

                            }






                          //  print $item_data['Category Main Image']."\n";



                            $content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['image_src']=$item_data['Category Main Image'];
                            $content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['image_website']= $image_website;
                            $content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['image_mobile_website']= $image_mobile_website;

*/

                            $content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['item_type']       = 'Subject';
                            $content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['webpage_key']     = $item_data['Page Key'];
                            $content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['webpage_code']    = $item_data['Webpage Code'];
                            $content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['category_code']   = $item_data['Category Code'];
                            $content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['number_products'] = $item_data['Product Category Active Products'];
                            $content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['link']            = $item_data['Webpage URL'];



//print_r($content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]);


                            unset($items_category_key_index[$item['category_key']]);
                        } else {

                            if (in_array($item['category_key'], $offline_items_category_key_index)) {
                                unset($content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]);

                            } else {

                                $sql = sprintf(
                                    "SELECT  `Webpage URL`,`Category Main Image Key`,`Category Main Image`,`Category Label`,`Category Main Image Key`,`Webpage State`,`Product Category Public`,`Webpage State`,`Page Key`,`Webpage Code`,`Product Category Active Products`,`Category Code`,Cat.`Category Key` 
                                  FROM   `Product Category Dimension` P     LEFT JOIN `Category Dimension` Cat ON (Cat.`Category Key`=P.`Product Category Key`) LEFT JOIN `Page Store Dimension` CatWeb ON (CatWeb.`Page Key`=`Product Category Webpage Key`)  
                                  WHERE  `Product Category Key`=%d  AND `Product Category Public`='Yes'  AND `Webpage State` IN ('Online','Ready')    ", $item['category_key']


                                );

                                if ($result = $this->db->query($sql)) {
                                    if ($row = $result->fetch()) {
                                        $content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['item_type']       = 'Guest';
                                        $content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['webpage_key']     = $row['Page Key'];
                                        $content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['webpage_code']    = $row['Webpage Code'];
                                        $content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['category_code']   = $row['Category Code'];
                                        $content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['number_products'] = $row['Product Category Active Products'];
                                        $content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['link']            = $row['Webpage URL'];


                                    } else {
                                        unset($content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]);
                                    }
                                } else {
                                    print_r($error_info = $this->db->errorInfo());
                                    print "$sql\n";
                                    exit;
                                }


                            }


                        }

                    }


                }
            }



        }



        foreach ($items_category_key_index as $index) {
            $item_data = $items[$index];
            $item      = array(
                'type'                 => 'category',
                'category_key'         => $item_data['Category Key'],
                'header_text'          => trim(strip_tags($item_data['Category Label'])),
                'image_src'            => ($item_data['Category Main Image Key'] ? 'image_root.php?id='.$item_data['Category Main Image Key'] : '/art/nopic.png'),
                'image_mobile_website' => '',
                'image_website'        => '',
                'webpage_key'          => $item_data['Page Key'],
                'webpage_code'         => strtolower($item_data['Webpage Code']),
                'item_type'            => 'Subject',
                'category_code'        => $item_data['Category Code'],
                'number_products'      => $item_data['Product Category Active Products'],
                'link'                 => $item_data['Webpage URL'],


            );

            array_unshift($content_data['blocks'][$block_key]['sections'][$anchor_section_key]['items'], $item);
        }



        // print_r($content_data['blocks'][$block_key]['sections']);

        $this->update_field_switcher('Page Store Content Data', json_encode($content_data), 'no_history');

        $sql = sprintf('DELETE FROM `Website Webpage Scope Map` WHERE `Website Webpage Scope Webpage Key`=%d AND `Website Webpage Scope Type` IN ("Subject","Guest")  ', $this->id);
        $this->db->exec($sql);

        $index = 0;
        foreach ($content_data['blocks'][$block_key]['sections'] as $section_key => $section) {

            if (isset($section['items'])) {
                foreach ($section['items'] as $item_key => $item) {
                    if ($item['type'] == 'category') {
                        $sql = sprintf(
                            'INSERT INTO `Website Webpage Scope Map` (`Website Webpage Scope Website Key`,`Website Webpage Scope Webpage Key`,`Website Webpage Scope Scope`,`Website Webpage Scope Scope Key`,`Website Webpage Scope Type`,`Website Webpage Scope Index`) VALUES (%d,%d,%s,%d,%s,%d) ',
                            $this->get('Webpage Website Key'), $this->id, prepare_mysql('Category'), $item['category_key'], prepare_mysql($item['item_type']), $index

                        );
                        //print "$sql\n";

                        $this->db->exec($sql);
                        $index++;

                    }
                }
            }


        }


    }

    function reindex_products() {
        $content_data = $this->get('Content Data');
        $block_found  = false;
        foreach ($content_data['blocks'] as $_block_key => $_block) {
            if ($_block['type'] == 'products') {
                $block       = $_block;
                $block_key   = $_block_key;
                $block_found = true;
                break;
            }
        }

        if (!$block_found) {
            return;
        }

        foreach ($block['items'] as $item_key => $item) {

            $sql = sprintf('SELECT `Product Web State` FROM `Product Dimension` WHERE `Product ID`=%d', $item['product_id']);
            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    if ($row['Product Web State'] == 'For Sale' or $row['Product Web State'] == 'Out of Stock') {

                        $product = get_object('Public_Product', $item['product_id']);
                        $product->load_webpage();


                        $content_data['blocks'][$block_key]['items'][$item_key]['web_state']               = $product->get('Web State');
                        $content_data['blocks'][$block_key]['items'][$item_key]['price']                   = $product->get('Price');
                        $content_data['blocks'][$block_key]['items'][$item_key]['price_unit']              = $product->get('Price Per Unit');
                        $content_data['blocks'][$block_key]['items'][$item_key]['rrp']                     = $product->get('RRP');
                        $content_data['blocks'][$block_key]['items'][$item_key]['code']                    = $product->get('Code');
                        $content_data['blocks'][$block_key]['items'][$item_key]['name']                    = $product->get('Name');
                        $content_data['blocks'][$block_key]['items'][$item_key]['link']                    = $product->webpage->get('URL');
                        $content_data['blocks'][$block_key]['items'][$item_key]['webpage_code']            = $product->webpage->get('Webpage Code');
                        $content_data['blocks'][$block_key]['items'][$item_key]['webpage_key']             = $product->webpage->id;
                        $content_data['blocks'][$block_key]['items'][$item_key]['out_of_stock_class']      = $product->get('Out of Stock Class');
                        $content_data['blocks'][$block_key]['items'][$item_key]['out_of_stock_label']      = $product->get('Out of Stock Label');
                        $content_data['blocks'][$block_key]['items'][$item_key]['sort_code']               = $product->get('Code File As');
                        $content_data['blocks'][$block_key]['items'][$item_key]['sort_name']               = $product->get('Product Name');
                        $content_data['blocks'][$block_key]['items'][$item_key]['next_shipment_timestamp'] = $product->get('Next Supplier Shipment Timestamp');
                        $content_data['blocks'][$block_key]['items'][$item_key]['category']                = $product->get('Family Code');
                        $content_data['blocks'][$block_key]['items'][$item_key]['raw_price']               = $product->get('Product Price');


                    } else {
                        unset($content_data['blocks'][$block_key]['items'][$item_key]);

                    }

                } else {
                    unset($content_data['blocks'][$block_key]['items'][$item_key]);
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


        }

        $this->update_field_switcher('Page Store Content Data', json_encode($content_data), 'no_history');
        $sql = sprintf('DELETE FROM `Website Webpage Scope Map` WHERE `Website Webpage Scope Webpage Key`=%d  AND `Website Webpage Scope Type`="Products_Item" ', $this->id);
        $this->db->exec($sql);

        $index = 0;
        foreach ($content_data['blocks'][$block_key]['items'] as $item) {

            $sql = sprintf(
                'INSERT INTO `Website Webpage Scope Map` (`Website Webpage Scope Website Key`,`Website Webpage Scope Webpage Key`,`Website Webpage Scope Scope`,`Website Webpage Scope Scope Key`,`Website Webpage Scope Type`,`Website Webpage Scope Index`) VALUES (%d,%d,%s,%d,%s,%d) ',
                $this->get('Webpage Website Key'), $this->id, prepare_mysql('Product'), $item['product_id'], prepare_mysql('Products_Item'), $index

            );
            // print "$sql\n";

            $this->db->exec($sql);
            $index++;


        }


    }

    function reindex_product() {
        $content_data = $this->get('Content Data');
        $block_found  = false;
        foreach ($content_data['blocks'] as $_block_key => $_block) {
            if ($_block['type'] == 'product') {
                $block       = $_block;
                $block_key   = $_block_key;
                $block_found = true;
                break;
            }
        }

        if (!$block_found) {
            return;
        }

        $product    = get_object('Public_Product', $this->get('Webpage Scope Key'));
        $image_data = $product->get('Image Data');

        $image_gallery = array();
        foreach ($product->get_image_gallery() as $image_item) {
            if ($image_item['key'] != $image_data['key']) {


                if ($image_item['image_website'] == '') {
                    foreach ($content_data['blocks'][$block_key]['other_images'] as $_img_data) {
                        if ($_img_data['key'] == $image_item['key']) {
                            $image_item['image_website'] = $_img_data['image_website'];
                            break;
                        }
                    }

                }
                $image_gallery[] = $image_item;
            }
        }


        //$old_image_website=$content_data['blocks'][$block_key]['image']['image_website'];


        if ($image_data['image_website'] == '') {
            if ($image_data['key'] == $content_data['blocks'][$block_key]['image']['key']) {
                $image_data['image_website'] = $content_data['blocks'][$block_key]['image']['image_website'];
            }
        }


        $content_data['blocks'][$block_key]['image'] = array(

            'key'           => $image_data['key'],
            'src'           => $image_data['src'],
            'caption'       => $image_data['caption'],
            'width'         => $image_data['width'],
            'height'        => $image_data['height'],
            'image_website' => $image_data['image_website']
        );


        $content_data['blocks'][$block_key]['other_images'] = $image_gallery;

        //print $product->get('Code');
        //print_r($content_data);

        $this->update_field_switcher('Page Store Content Data', json_encode($content_data), 'no_history');


    }

    function add_section_item($item_key, $section_key = false) {


        include_once('class.Public_Webpage.php');
        include_once('class.Category.php');

        $updated_metadata = array('section_keys' => array());

        $content_data = $this->get('Content Data');

        // print_r($content_data['sections']);


        if (!$section_key) {

            foreach ($content_data['sections'] as $_key => $_data) {


                if ($_data['type'] == 'anchor') {
                    $section_key = $_data['key'];

                    break;
                }

            }

        }


        $found_section = false;
        foreach ($content_data['sections'] as $section_data) {
            if ($section_data['key'] == $section_key) {
                $found_section = true;
                break;

            }
        }


        if (!$found_section) {

            $this->msg   = 'Web page section not found in website';
            $this->error = true;

            return $updated_metadata;
        }

        $parent_category  = new Category($this->get('Webpage Scope Key'));
        $subject_category = new Category($item_key);


        //print_r($subject_category);

        $subject_webpage = new Public_Webpage('scope', ($subject_category->get('Category Subject') == 'Category' ? 'Category Categories' : 'Category Products'), $subject_category->id);


        //  print_r($subject_category);
        // print_r($subject_webpage);

        if ($subject_webpage->id) {

            $sql = sprintf(
                'SELECT max(`Category Webpage Index Stack`) AS stack FROM  `Category Webpage Index` WHERE `Category Webpage Index Webpage Key`=%d AND `Category Webpage Index Section Key`=%d ', $this->id, $section_key


            );


            //  print $sql;
            $stack = 0;
            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $stack = $row['stack'];

                }
            }


            $stack++;

            if ($parent_category->is_subject_associated($item_key)) {
                $subject_type = 'Subject';
            } else {
                $subject_type = 'Guest';

            }


            $subject_data = array(
                'header_text' => $subject_category->get('Label'),
                'image_src'   => $subject_category->get('Image'),
                'footer_text' => $subject_category->get('Code'),
            );


            $sql = sprintf(
                'INSERT INTO `Category Webpage Index` (`Category Webpage Index Parent Category Key`,`Category Webpage Index Category Key`,`Category Webpage Index Webpage Key`,`Category Webpage Index Category Webpage Key`,`Category Webpage Index Section Key`,`Category Webpage Index Content Data`,`Category Webpage Index Subject Type`,`Category Webpage Index Stack`) VALUES (%d,%d,%d,%d,%d,%s,%s,%d) ',
                $this->get('Webpage Scope Key'), $item_key, $this->id, $subject_webpage->id, $section_key, prepare_mysql(json_encode($subject_data)),

                prepare_mysql($subject_type), $stack
            );

            //  print "$sql\n\n";
            $this->db->exec($sql);


            $updated_metadata['section_keys'][] = $section_key;


        } else {
            $this->msg   = "Item don't have website";
            $this->error = true;

            return $updated_metadata;
        }

        $result = array();

        foreach ($updated_metadata['section_keys'] as $section_key) {
            foreach ($content_data['sections'] as $section_stack_index => $section_data) {
                if ($section_data['key'] == $section_key) {
                    $content_data['sections'][$section_stack_index]['items'] = get_website_section_items($this->db, $section_data);
                    $result[$section_key]                                    = $content_data['sections'][$section_stack_index]['items'];
                    break;
                }
            }
        }


        $this->update(array('Page Store Content Data' => json_encode($content_data)), 'no_history');


        return $result;


    }

    function remove_section_item($item_key) {

        $updated_metadata = array('section_keys' => array());
        $content_data     = $this->get('Content Data');

        $sql = sprintf(
            'SELECT `Category Webpage Index Key`,`Category Webpage Index Section Key`,`Category Webpage Index Stack` FROM  `Category Webpage Index` WHERE `Category Webpage Index Webpage Key`=%d AND `Category Webpage Index Category Key`=%d ', $this->id, $item_key


        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {


                $updated_metadata['section_keys'][] = $row['Category Webpage Index Section Key'];

                $sql = sprintf(
                    'DELETE FROM `Category Webpage Index` WHERE `Category Webpage Index Key`=%d  ',

                    $row['Category Webpage Index Key']
                );
                $this->db->exec($sql);

            } else {
                $this->msg   = 'Item not found in website';
                $this->error = true;

                return $updated_metadata;



            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $result = array();

        foreach ($updated_metadata['section_keys'] as $section_key) {
            foreach ($content_data['sections'] as $section_stack_index => $section_data) {
                if ($section_data['key'] == $section_key) {
                    $content_data['sections'][$section_stack_index]['items'] = get_website_section_items($this->db, $section_data);
                    $result[$section_key]                                    = $content_data['sections'][$section_stack_index]['items'];
                    break;
                }
            }
        }

        $this->update(array('Page Store Content Data' => json_encode($content_data)), 'no_history');


        return $result;

    }


    function delete($create_deleted_page_record = true) {


        $sql = sprintf('delete `Product Category Index` where `Product Category Index Website Key`=%d  ', $this->id);
        $this->db->exec($sql);


        $sql = sprintf('delete `Category Webpage Index` where `Category Webpage Index Webpage Key`=%d  ', $this->id);
        $this->db->exec($sql);


        $sql = sprintf('delete `Webpage Section Dimension` where `Webpage Section Webpage Key`=%d  ', $this->id);
        $this->db->exec($sql);


        $this->deleted = false;
        $sql           = sprintf(
            "DELETE FROM `Page Dimension` WHERE `Page Key`=%d", $this->id
        );


        $this->db->exec($sql);

        $sql = sprintf(
            "DELETE FROM `Page Store Dimension` WHERE `Page Key`=%d", $this->id
        );
        $this->db->exec($sql);
        $sql = sprintf(
            "DELETE FROM `Page Redirection Dimension` WHERE `Page Target Key`=%d", $this->id
        );
        $this->db->exec($sql);


        $sql = sprintf(
            "DELETE FROM `Page Store Found In Bridge` WHERE `Page Store Key`=%d", $this->id
        );
        $this->db->exec($sql);
        $sql = sprintf(
            "DELETE FROM `Page Store Found In Bridge` WHERE `Page Store Found In Key`=%d", $this->id
        );

        $this->db->exec($sql);

        $sql = sprintf(
            "DELETE FROM  `Page Store See Also Bridge` WHERE `Page Store Key`=%d", $this->id
        );
        $this->db->exec($sql);


        $sql = sprintf(
            "INSERT INTO `Page State Timeline`  (`Page Key`,`Website Key`,`Store Key`,`Date`,`State`,`Operation`) VALUES (%d,%d,%d,%s,'Offline','Deleted') ",
            $this->id, $this->data['Webpage Website Key'], $this->data['Webpage Store Key'], prepare_mysql(gmdate('Y-m-d H:i:s'))

        );
        $this->db->exec($sql);



        $images = array();
        $sql    = sprintf(
            "SELECT `Image Key` FROM `Image Bridge` WHERE `Subject Type`='Page' AND `Subject Key`=%d", $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $images[] = $row['Image Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf(
            "DELETE FROM  `Image Bridge` WHERE `Subject Type`='Page' AND `Subject Key`=%d", $this->id
        );

        $this->db->exec($sql);

        foreach ($images as $image_key) {
            $image = get_object('Image',$image_key);
            $image->delete();
            //if (!$image->deleted) {
            //    $image->update_other_size_data();
            // }


        }

        $sql = sprintf(
            "SELECT `Page Store Key`  FROM  `Page Store See Also Bridge` WHERE `Page Store See Also Key`=%d ", $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $_page = new Page ($row['Page Store Key']);
                $_page->update_see_also();
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $this->deleted = true;


        if (array_key_exists('Webpage Website Key', $this->data)) {
            $website = get_object('website', $this->data['Webpage Website Key']);
            $website->update_webpages();
        }


        if ($create_deleted_page_record) {


            $deleted_metadata = gzcompress(json_encode($this->data), 9);


            include_once 'class.PageDeleted.php';
            $data = array(
                'Page Code'                   => $this->data['Page Code'],
                'Page Key'                    => $this->id,
                'Website Key'                    => $this->data['Webpage Website Key'],
                'Store Key'                   => $this->data['Page Store Key'],
                'Page Store Section'          => $this->data['Page Store Section'],
                'Page Parent Key'             => $this->data['Page Parent Key'],
                'Page Parent Code'            => $this->data['Page Parent Code'],
                'Page Title'                  => $this->data['Webpage Browser Title'],
                'Page Short Title'            => $this->data['Webpage Name'],
                'Page Description'            => $this->data['Webpage Meta Description'],
                'Page URL'                    => $this->data['Webpage URL'],
                'Page Valid To'               => gmdate('Y-m-d H:i:s'),
                'Page Store Deleted Metadata' => $deleted_metadata


            );

            $deleted_page = new PageDeleted();
            $deleted_page->create($data);


            $abstract = sprintf(
                _('Webpage %s deleted'), sprintf(
                                           '<span class="button" onClick="change_view(\'webpage/%d\')">%s</span>', $this->id, $this->data['Page Code']
                                       )
            );


            $history_data = array(
                'History Abstract' => $abstract,
                'History Details'  => '',
                'Action'           => 'deleted'
            );
            $this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id);


            require_once 'class.Webpage_Type.php';

            $webpage_type = new Webpage_Type($this->get('Webpage Type Key'));
            $webpage_type->update_number_webpages();


            $this->new_value = $deleted_page->id;
        }
        $this->deleted = true;

        $account = get_object('Account', 1);

        $redis = new Redis();
        if ($redis->connect('127.0.0.1', 6379)) {

            $cache_id_prefix='pwc2|'.$account->get('Code').'|'.$this->get('Webpage Website Key').'_';

            $redis->delete($cache_id_prefix.$this->data['Page Code']);
            $redis->delete($cache_id_prefix.strtolower($this->data['Page Code']));
            $redis->delete($cache_id_prefix.strtoupper($this->data['Page Code']));
            $redis->delete($cache_id_prefix.ucfirst($this->data['Page Code']));

            include_once 'utils/string_functions.php';
            foreach(permutation_letter_case($this->data['Page Code']) as $permutation){
                $redis->delete($cache_id_prefix.$permutation);

            }



        }


    }







    function update_items_order($item_key, $target_key, $target_section_key) {


        $content_data = $this->get('Content Data');

        $updated_metadata = array('section_keys' => array());


        switch ($this->scope->get_object_name()) {


            case 'Category':
                include_once('class.Category.php');
                $category = new Category($this->scope->id);
                //print'x'.$category->get('Category Subject').'x';


                if ($category->get('Category Subject') == 'Category') {

                    $item_found   = false;
                    $target_found = false;

                    $sql = sprintf(
                        'SELECT `Category Webpage Index Key`,`Category Webpage Index Section Key`,`Category Webpage Index Stack` FROM  `Category Webpage Index` WHERE `Category Webpage Index Webpage Key`=%d AND `Category Webpage Index Category Key`=%d ', $this->id,
                        $item_key


                    );

                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {

                            $item_section_key = $row['Category Webpage Index Section Key'];
                            $item_found       = true;
                            $item_stack       = $row['Category Webpage Index Stack'];
                            $item_index_key   = $row['Category Webpage Index Key'];
                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        print "$sql\n";
                        exit;
                    }


                    if (!$item_found) {
                        $this->msg   = 'Item not found in website';
                        $this->error = true;

                        return array();

                    }


                    if ($target_key) {


                        $sql = sprintf(
                            'SELECT `Category Webpage Index Section Key`,`Category Webpage Index Stack` FROM  `Category Webpage Index` WHERE `Category Webpage Index Webpage Key`=%d AND `Category Webpage Index Category Key`=%d ', $this->id, $target_key


                        );

                        if ($result = $this->db->query($sql)) {
                            if ($row = $result->fetch()) {
                                $target_section_key = $row['Category Webpage Index Section Key'];
                                $target_found       = true;
                                $target_stack       = $row['Category Webpage Index Stack'];
                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            print "$sql\n";
                            exit;
                        }


                        if (!$target_found) {
                            $this->msg   = 'Target not found in website';
                            $this->error = true;

                            return array();

                        }

                        if ($item_section_key == $target_section_key) {


                            $updated_metadata['section_keys'][] = $item_section_key;

                            $subjects = array();

                            $sql = sprintf(
                                "SELECT `Category Webpage Index Stack`,`Category Webpage Index Key`,`Category Webpage Index Category Key` AS subject_key FROM `Category Webpage Index`    WHERE  `Category Webpage Index Webpage Key`=%d ", $this->id
                            );


                            if ($result = $this->db->query($sql)) {
                                foreach ($result as $row) {


                                    if ($row['subject_key'] == $item_key) {

                                        $row['Category Webpage Index Stack'] = (string)($target_stack < $item_stack ? $target_stack - 0.5 : $target_stack + .5);


                                    }


                                    //print_r($row);

                                    $subjects[$row['Category Webpage Index Stack']] = $row['Category Webpage Index Key'];
                                }
                            } else {
                                print_r($error_info = $this->db->errorInfo());
                                print "$sql\n";
                                exit;
                            }


                            ksort($subjects);
                            //print_r($subjects);

                            $stack_index = 0;
                            foreach ($subjects as $tmp => $category_webpage_stack_key) {
                                $stack_index++;

                                $sql = sprintf(
                                    'UPDATE `Category Webpage Index` SET `Category Webpage Index Stack`=%d WHERE `Category Webpage Index Key`=%d ', $stack_index, $category_webpage_stack_key
                                );

                                //print "$sql\n";

                                $this->db->exec($sql);

                            }


                        } else {


                            $updated_metadata['section_keys'][] = $item_section_key;
                            $updated_metadata['section_keys'][] = $target_section_key;


                            $sql = sprintf(
                                'UPDATE `Category Webpage Index` SET `Category Webpage Index Section Key`=%d , `Category Webpage Index Stack`=%d WHERE `Category Webpage Index Key`=%d', $target_section_key, 0, $item_index_key


                            );
                            $this->db->exec($sql);


                            $subjects = array();
                            $sql      = sprintf(
                                "SELECT `Category Webpage Index Stack`,`Category Webpage Index Key`,`Category Webpage Index Category Key` AS subject_key FROM `Category Webpage Index`    WHERE  `Category Webpage Index Webpage Key`=%d AND  `Category Webpage Index Section Key`=%d ORDER BY `Category Webpage Index Stack` ",
                                $this->id, $target_section_key

                            );
                            if ($result = $this->db->query($sql)) {
                                foreach ($result as $row) {


                                    // print "aaaa_> $item_index_key\n";
                                    // print_r($row);

                                    if ($item_index_key == $row['Category Webpage Index Key']) {
                                        $tmp = (string)$target_stack - .5;

                                        //  print "x  $tmp x\n";

                                        $row['Category Webpage Index Stack'] = (string)($target_stack - .5);
                                    }
                                    $subjects[$row['Category Webpage Index Stack']] = $row['Category Webpage Index Key'];
                                }
                            }
                            // print_r($subjects);
                            ksort($subjects);

                            //  print_r($subjects);

                            $stack_index = 0;
                            foreach ($subjects as $tmp => $category_webpage_stack_key) {
                                $stack_index++;
                                $sql = sprintf(
                                    'UPDATE `Category Webpage Index` SET `Category Webpage Index Stack`=%d WHERE `Category Webpage Index Key`=%d ', $stack_index, $category_webpage_stack_key
                                );
                                $this->db->exec($sql);

                            }


                            $subjects = array();
                            $sql      = sprintf(
                                "SELECT `Category Webpage Index Stack`,`Category Webpage Index Key`,`Category Webpage Index Category Key` AS subject_key FROM `Category Webpage Index`    WHERE  `Category Webpage Index Webpage Key`=%d AND  `Category Webpage Index Section Key`=%d ORDER BY `Category Webpage Index Stack` ",
                                $this->id, $item_section_key

                            );
                            if ($result = $this->db->query($sql)) {
                                foreach ($result as $row) {
                                    $subjects[$row['Category Webpage Index Stack']] = $row['Category Webpage Index Key'];
                                }
                            }

                            $stack_index = 0;
                            foreach ($subjects as $tmp => $category_webpage_stack_key) {
                                $stack_index++;
                                $sql = sprintf(
                                    'UPDATE `Category Webpage Index` SET `Category Webpage Index Stack`=%d WHERE `Category Webpage Index Key`=%d ', $stack_index, $category_webpage_stack_key
                                );
                                $this->db->exec($sql);

                            }


                        }


                    } else {
                        // move last square

                        $sql = sprintf(
                            'SELECT max(`Category Webpage Index Stack`) AS stack FROM  `Category Webpage Index` WHERE `Category Webpage Index Webpage Key`=%d AND `Category Webpage Index Section Key`=%d ', $this->id, $target_section_key


                        );


                        //  print $sql;

                        if ($result = $this->db->query($sql)) {
                            if ($row = $result->fetch()) {
                                $stack = $row['stack'];
                                if ($target_section_key == $item_section_key) {

                                    $updated_metadata['section_keys'][] = $item_section_key;

                                    $subjects = array();
                                    $sql      = sprintf(
                                        "SELECT `Category Webpage Index Stack`,`Category Webpage Index Key`,`Category Webpage Index Category Key` AS subject_key FROM `Category Webpage Index`    WHERE  `Category Webpage Index Webpage Key`=%d ", $this->id
                                    );
                                    if ($result = $this->db->query($sql)) {
                                        foreach ($result as $row) {
                                            if ($row['subject_key'] == $item_key) {
                                                $row['Category Webpage Index Stack'] = $stack + 1;
                                            }
                                            $subjects[$row['Category Webpage Index Stack']] = $row['Category Webpage Index Key'];
                                        }
                                    }

                                    ksort($subjects);
                                    $stack_index = 0;
                                    foreach ($subjects as $tmp => $category_webpage_stack_key) {
                                        $stack_index++;
                                        $sql = sprintf(
                                            'UPDATE `Category Webpage Index` SET `Category Webpage Index Stack`=%d WHERE `Category Webpage Index Key`=%d ', $stack_index, $category_webpage_stack_key
                                        );
                                        $this->db->exec($sql);

                                    }


                                } else {
                                    $updated_metadata['section_keys'][] = $item_section_key;
                                    $updated_metadata['section_keys'][] = $target_section_key;

                                    $sql = sprintf(
                                        'UPDATE `Category Webpage Index` SET `Category Webpage Index Section Key`=%d , `Category Webpage Index Stack`=%d WHERE `Category Webpage Index Key`=%d', $target_section_key, $stack + 1, $item_index_key


                                    );
                                    $this->db->exec($sql);


                                    $subjects = array();
                                    $sql      = sprintf(
                                        "SELECT `Category Webpage Index Stack`,`Category Webpage Index Key`,`Category Webpage Index Category Key` AS subject_key FROM `Category Webpage Index`    WHERE  `Category Webpage Index Webpage Key`=%d AND  `Category Webpage Index Section Key`=%d ORDER BY `Category Webpage Index Stack` ",
                                        $this->id, $item_section_key

                                    );
                                    if ($result = $this->db->query($sql)) {
                                        foreach ($result as $row) {
                                            $subjects[$row['Category Webpage Index Stack']] = $row['Category Webpage Index Key'];
                                        }
                                    }

                                    $stack_index = 0;
                                    foreach ($subjects as $tmp => $category_webpage_stack_key) {
                                        $stack_index++;
                                        $sql = sprintf(
                                            'UPDATE `Category Webpage Index` SET `Category Webpage Index Stack`=%d WHERE `Category Webpage Index Key`=%d ', $stack_index, $category_webpage_stack_key
                                        );
                                        $this->db->exec($sql);

                                    }


                                }


                            } else {
                                $this->msg   = 'Section not found in website';
                                $this->error = true;

                                return array();
                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            print "$sql\n";
                            exit;
                        }

                    }


                    $result = array();

                    foreach ($updated_metadata['section_keys'] as $section_key) {
                        foreach ($content_data['sections'] as $section_stack_index => $section_data) {
                            if ($section_data['key'] == $section_key) {
                                $content_data['sections'][$section_stack_index]['items'] = get_website_section_items($this->db, $section_data);
                                $result[$section_key]                                    = $content_data['sections'][$section_stack_index]['items'];
                                break;
                            }
                        }
                    }

                    $this->update(array('Page Store Content Data' => json_encode($content_data)), 'no_history');


                    return $result;

                }
                if ($category->get('Category Subject') == 'Product') {

                    $item_found   = false;
                    $target_found = false;

                    $sql = sprintf(
                        'SELECT `Product Category Index Key`,`Product Category Index Stack` FROM  `Product Category Index` WHERE `Product Category Index Website Key`=%d AND `Product Category Index Product ID`=%d ', $this->id, $item_key


                    );
                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {

                            $item_section_key = 0;
                            $item_found       = true;
                            $item_stack       = $row['Product Category Index Stack'];
                            $item_index_key   = $row['Product Category Index Key'];
                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        print "xx $sql\n";
                        exit;
                    }


                    if (!$item_found) {
                        $this->msg   = 'Item not found in website';
                        $this->error = true;

                        return array();

                    }


                    if ($target_key) {


                        $sql = sprintf(
                            'SELECT `Product Category Index Key`,`Product Category Index Stack` FROM  `Product Category Index` WHERE `Product Category Index Website Key`=%d AND `Product Category Index Product ID`=%d ', $this->id, $target_key


                        );

                        if ($result = $this->db->query($sql)) {
                            if ($row = $result->fetch()) {
                                $target_section_key = 0;
                                $target_found       = true;
                                $target_stack       = $row['Product Category Index Stack'];
                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            print "$sql\n";
                            exit;
                        }


                        if (!$target_found) {
                            $this->msg   = 'Target not found in website';
                            $this->error = true;

                            return array();

                        }

                        if ($item_section_key == $target_section_key) {


                            $updated_metadata['section_keys'][] = $item_section_key;

                            $subjects = array();

                            $sql = sprintf(
                                "SELECT `Product Category Index Stack`,`Product Category Index Key`,`Product Category Index Product ID` AS subject_key FROM `Product Category Index` WHERE  `Product Category Index Website Key`=%d ", $this->id
                            );


                            if ($result = $this->db->query($sql)) {
                                foreach ($result as $row) {


                                    if ($row['subject_key'] == $item_key) {
                                        $row['Product Category Index Stack'] = (string)($target_stack < $item_stack ? $target_stack - 0.5 : $target_stack + .5);
                                    }
                                    $subjects[$row['Product Category Index Stack']] = $row['Product Category Index Key'];
                                }
                            } else {
                                print_r($error_info = $this->db->errorInfo());
                                print "$sql\n";
                                exit;
                            }


                            ksort($subjects);
                            //print_r($subjects);

                            $stack_index = 0;
                            foreach ($subjects as $tmp => $category_webpage_stack_key) {
                                $stack_index++;

                                $sql = sprintf(
                                    'UPDATE `Product Category Index` SET `Product Category Index Stack`=%d WHERE `Product Category Index Key`=%d ', $stack_index, $category_webpage_stack_key
                                );

                                //print "$sql\n";

                                $this->db->exec($sql);

                            }


                        }


                    }




                    return array();
                }


                break;
            default:
                return array();
                break;
        }


    }

    function delete_section($section_key) {

        $content_data = $this->get('Content Data');


        foreach ($content_data['sections'] as $_key => $_data) {


            if ($_data['type'] == 'anchor') {
                $anchor_section_key = $_data['key'];

                break;
            }

        }
        $sql = sprintf(
            'SELECT max(`Category Webpage Index Stack`) AS stack FROM  `Category Webpage Index` WHERE `Category Webpage Index Webpage Key`=%d 
              AND `Category Webpage Index Section Key`=%d ', $this->id, $anchor_section_key
        );


        //  print $sql;

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $stack = $row['stack'];


                $sql = sprintf(
                    'UPDATE `Category Webpage Index` SET `Category Webpage Index Section Key`=%d , `Category Webpage Index Stack`=`Category Webpage Index Stack`+%d WHERE `Category Webpage Index Section Key`=%d', $anchor_section_key, $stack, $section_key


                );


                // print $sql;

                $this->db->exec($sql);


                $subjects = array();
                $sql      = sprintf(
                    "SELECT `Category Webpage Index Stack`,`Category Webpage Index Key`,`Category Webpage Index Category Key` AS subject_key FROM `Category Webpage Index`    WHERE  `Category Webpage Index Webpage Key`=%d AND  `Category Webpage Index Section Key`=%d ORDER BY `Category Webpage Index Stack` ",
                    $this->id, $anchor_section_key

                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $subjects[$row['Category Webpage Index Stack']] = $row['Category Webpage Index Key'];
                    }
                }

                ksort($subjects);

                $stack_index = 0;
                foreach ($subjects as $tmp => $category_webpage_stack_key) {
                    $stack_index++;
                    $sql = sprintf(
                        'UPDATE `Category Webpage Index` SET `Category Webpage Index Stack`=%d WHERE `Category Webpage Index Key`=%d ', $stack_index, $category_webpage_stack_key
                    );
                    $this->db->exec($sql);

                }


            } else {
                $this->msg   = 'Section not found in website';
                $this->error = true;

                return;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        foreach ($content_data['sections'] as $_key => $_data) {
            if ($_data['key'] == $section_key) {
                unset($content_data['sections'][$_key]);
                break;
            }

        }


        $result = array();


        foreach ($content_data['sections'] as $section_stack_index => $section_data) {
            if ($section_data['key'] == $anchor_section_key) {
                $content_data['sections'][$section_stack_index]['items'] = get_website_section_items($this->db, $section_data);
                $result[$anchor_section_key]                             = $content_data['sections'][$section_stack_index]['items'];
                break;
            }
        }

        $this->update(array('Page Store Content Data' => json_encode($content_data)), 'no_history');


        return $result;

    }

    function add_section() {

        $content_data = $this->get('Content Data');


        $section = array(
            'title'    => 'Bla bla',
            'subtitle' => 'bla bla',
            'type'     => 'page_break',
            'panels'   => array(),

            'items' => array()

        );

        if (isset($content_data['sections'])) {
            $section_stack_index = count($content_data['sections']) + 1;
        } else {
            $section_stack_index = 1;
        }


        $sql = sprintf(
            'INSERT INTO `Webpage Section Dimension` (`Webpage Section Webpage Key`,`Webpage Section Webpage Stack Index`,`Webpage Section Data`) VALUES (%d,%d,%s) ', $this->id, $section_stack_index, prepare_mysql(json_encode($section))

        );


        $this->db->exec($sql);
        $section['key'] = $this->db->lastInsertId();


        $content_data['sections'][] = $section;

        $this->update(array('Page Store Content Data' => json_encode($content_data)), 'no_history');


        $updated_metadata['new_section'] = $section;

        return $updated_metadata;

    }

    function update_webpage_section_order($section_key, $target_key) {


        $content_data = $this->get('Content Data');

        $section_index = 0;
        $target_index  = 0;

        $_sections = $content_data['sections'];


        foreach ($_sections as $index => $section_data) {


            if ($section_data['key'] == $section_key) {
                $section_index  = $index;
                $moving_section = $section_data;
                unset($_sections[$index]);
            }
            if ($section_data['key'] == $target_key) {
                $target_index = $index;
            }
        }


        if (!$section_index or !$target_index) {

            $this->error = true;
            $this->msg   = "Section index or target not found";

            return;
        }

        if ($section_index == $target_index) {

            $this->error = true;
            $this->msg   = "Same section index and target ";

            return;
        }


        $sections = array();


        if ($target_index > $section_index) {

            foreach ($_sections as $index => $section_data) {


                $sections[] = $section_data;
                if ($index == $target_index) {
                    $sections[] = $moving_section;
                }


            }

        } else {

            foreach ($_sections as $index => $section_data) {


                if ($index == $target_index) {
                    $sections[] = $moving_section;
                }
                $sections[] = $section_data;

            }

        }


        $content_data['sections'] = $sections;
        $this->update(array('Page Store Content Data' => json_encode($content_data)), 'no_history');


    }

    function add_panel($section_key, $panel_data) {

        $content_data = $this->get('Content Data');
        //   print_r($content_data['sections']);

        foreach ($content_data['sections'] as $_key => $section_data) {
            //print_r($section_data);

            //   print "$_key\n";
            if ($section_data['key'] == $section_key) {


                $section_index = $_key;
                $panels        = $section_data['panels'];

                //
                //  print "xx $section_index\n";
                // break;

                // print_r($_key);
            }

        }


        //print "yy $section_index\n";
        //print_r($content_data);
        //$panels=$content_data[$section_key]

        $size_tag = $panel_data['size'].'x';

        $panel = array(
            'id'   => $panel_data['id'],
            'type' => $panel_data['type'],
            'size' => $size_tag

        );

        if ($panel_data['type'] == 'image') {


            $panel['image_src'] = '/art/panel_'.$size_tag.'_1.png';
            $panel['link']      = '';
            $panel['caption']   = '';
            $panel['image_key'] = '';
        } elseif ($panel_data['type'] == 'text') {

            $panel['content'] = 'bla bla bla';
            $panel['class']   = 'text_panel_default';

        } elseif ($panel_data['type'] == 'code') {

            $panel['content'] = '';
            $panel['class']   = 'code_panel_default';


        } elseif ($panel_data['type'] == 'page_break') {

            $panel['title']    = 'Bla bla';
            $panel['subtitle'] = 'bla bla';


        }

        $sql = sprintf(
            'INSERT INTO `Webpage Panel Dimension` (`Webpage Panel Section Key`,`Webpage Panel Id`,`Webpage Panel Webpage Key`,`Webpage Panel Type`,`Webpage Panel Data`,`Webpage Panel Metadata`) VALUES (%d,%s,%d,%s,%s,%s) ', $section_key,
            prepare_mysql($panel_data['id']), $this->id, prepare_mysql($panel_data['type']), ($panel_data['type'] == 'code' ? prepare_mysql($panel['content']) : prepare_mysql('')), prepare_mysql(json_encode($panel))

        );


        // print $sql;

        $this->db->exec($sql);
        $panel['key'] = $this->db->lastInsertId();


        $panels[$panel_data['stack_index']] = $panel;

        ksort($panels);


        //  print_r($panels);


        // print "xxa $section_index";


        $content_data['sections'][$section_index]['panels'] = $panels;

        $content_data['sections'][$section_index]['items'] = get_website_section_items($this->db, $content_data['sections'][$section_index]);

        //print_r($content_data);
        $this->update(array('Page Store Content Data' => json_encode($content_data)), 'no_history');

        $result               = array();
        $result[$section_key] = $content_data['sections'][$section_index]['items'];


        return $result;


    }

    function sort_items($type) {

        $content_data = $this->get('Content Data');

        $updated_metadata = array('section_keys' => array());


        switch ($this->scope->get_object_name()) {


            case 'Category':
                include_once('class.Category.php');
                $category = new Category($this->scope->id);
                //print'x'.$category->get('Category Subject').'x';


                if ($category->get('Category Subject') == 'Product') {

                    $item_section_key                   = 0;
                    $updated_metadata['section_keys'][] = $item_section_key;

                    $subjects = array();


                    switch ($type) {
                        case 'code_asc':
                            $_order = 'order by `Product Code File As`';
                            break;
                        case 'code_desc':
                            $_order = 'order by `Product Code File As` desc';
                            break;
                        case 'name_asc':
                            $_order = 'order by `Product Name`';
                            break;
                        case 'name_desc':
                            $_order = 'order by `Product Name` desc';
                            break;
                        case 'sales_asc':
                            $_order = 'order by `Product 1 Year Acc Invoiced Amount`';
                            break;
                        case 'sales_desc':
                            $_order = 'order by Product 1 Year Acc Invoiced Amount` desc';
                            break;
                        case 'date_asc':
                            $_order = 'order by date(`Product Valid From`),`Product Code File As` ';
                            break;
                        case 'date_desc':
                            $_order = 'order by date(`Product Valid From`) desc, `Product Code File As` ';
                            break;
                        default:
                            $_order = 'order by `Product Code File As`';

                    }


                    $sql = sprintf(
                        "SELECT `Product Category Index Stack`,`Product Category Index Key`,`Product Category Index Product ID` AS subject_key FROM `Product Category Index`  left join `Product Dimension` P on ( P.`Product ID`=`Product Category Index Product ID`) left join `Product Data` D on ( D.`Product ID`=`Product Category Index Product ID`)  WHERE  `Product Category Index Website Key`=%d  $_order",
                        $this->id
                    );

                    $count = 0;
                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {


                            $subjects[$count++] = $row['Product Category Index Key'];
                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        print "$sql\n";
                        exit;
                    }


                    ksort($subjects);
                    // print $sql;
                    //print_r($subjects);

                    $stack_index = 0;
                    foreach ($subjects as $tmp => $category_webpage_stack_key) {
                        $stack_index++;

                        $sql = sprintf(
                            'UPDATE `Product Category Index` SET `Product Category Index Stack`=%d WHERE `Product Category Index Key`=%d ', $stack_index, $category_webpage_stack_key
                        );

                        //print "$sql\n";

                        $this->db->exec($sql);

                    }

                }

        }


        $result = array();


        return $result;

    }

    function get_field_label($field) {


        switch ($field) {

            case 'Webpage Code':
                $label = _('code');
                break;
            case 'Webpage Name':
                $label = _('name');
                break;

            case 'Webpage Locale':
                $label = _('language');
                break;
            case 'Webpage Timezone':
                $label = _('timezone');
                break;
            case 'Webpage Email':
                $label = _('email');
                break;

            case 'Webpage Browser Title':
                $label = _('browser title');
                break;
            case 'Webpage Meta Description':
                $label = _('meta description');
                break;
            case 'Webpage Redirection Code':
                $label = _('Permanent redirection');
                break;
            default:
                $label = $field;

        }

        return $label;

    }

    function reindex() {

        $this->reindex_items();
        $this->update_navigation();

        switch ($this->get('Webpage Scope')) {
            case 'Category Products':

                $sql = sprintf(
                    'SELECT `Product Webpage Key` FROM `Website Webpage Scope Map` left join `Product Dimension` on (`Product ID`=`Website Webpage Scope Scope Key`) WHERE `Website Webpage Scope Webpage Key`=%d and `Website Webpage Scope Scope`="Product" ', $this->id

                );

                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {

                        $webpage = get_object('Webpage', $row['Product Webpage Key']);
                        if ($webpage->id) {
                            $webpage->reindex_items();
                            $webpage->update_navigation();
                        }


                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


        }

    }


    function get_analytics_data($from,$to){


        include_once('utils/google_api.php');


        list($url_base,$url_suffix)=preg_split('/\//',$this->get('URL'));

        try {
            $analytics_data=get_analytics_data($url_base,$url_suffix,$from,$to);
        } catch (Exception $e) {
           // echo 'Caught exception: ',  $e->getMessage(), "\n";
            $analytics_data=array(
                'pageviews'=>0,
                'pageviews_registered_users'=>0,
                'page_value'=>0,
                'users'=>0,
                'registered_users'=>0,
                'sessions'=>0,
                'sessions_registered_users'=>0,
                'impressions'=>0,
                'clicks'=>0,
                'ctr'=>0,
                'position'=>0,



            );

        }




        return $analytics_data;

    }

    function update_analytics($interval,$this_period=true,$last_period=true){

        include_once 'utils/date_functions.php';
        list($db_interval, $from_date, $to_date, $from_date_1yb, $to_date_1yb,$from_prev_period,$to_prev_period) = calculate_interval_dates($this->db, $interval);



        if ($this_period) {

            $analytics_data = $this->get_analytics_data($from_date, $to_date);


            $data_to_update = array(
                "Webpage Analytics $db_interval Acc Pageviews" =>$analytics_data['pageviews'],
                "Webpage Analytics $db_interval Acc RUsers Pageviews" =>$analytics_data['pageviews_registered_users'],
                "Webpage Analytics $db_interval Acc Page Value"          => round($analytics_data['page_value'], 2),
                "Webpage Analytics $db_interval Acc Users" =>$analytics_data['users'],
                "Webpage Analytics $db_interval Acc RUsers" =>$analytics_data['registered_users'],
                "Webpage Analytics $db_interval Acc Sessions" =>$analytics_data['sessions'],
                "Webpage Analytics $db_interval Acc RUsers Sessions" =>$analytics_data['sessions_registered_users'],
                "Webpage Analytics $db_interval Acc SC Impressions" =>$analytics_data['impressions'],
                "Webpage Analytics $db_interval Acc SC Clicks" =>$analytics_data['clicks'],
                "Webpage Analytics $db_interval Acc SC CTR" =>$analytics_data['ctr'],
                "Webpage Analytics $db_interval Acc SC Position" =>$analytics_data['position'],



            );


            $this->fast_update($data_to_update, 'Webpage Analytics Data');



        }

        if ($from_prev_period and $last_period) {


            $analytics_data = $this->get_analytics_data($from_prev_period, $to_prev_period);

            $data_to_update = array(
                "Webpage Analytics $db_interval Acc Pageviews" =>$analytics_data['pageviews'],
                "Webpage Analytics $db_interval Acc RUsers Pageviews" =>$analytics_data['pageviews_registered_users'],
                "Webpage Analytics $db_interval Acc Page Value"          => round($analytics_data['page_value'], 2),
                "Webpage Analytics $db_interval Acc Users" =>$analytics_data['users'],
                "Webpage Analytics $db_interval Acc RUsers" =>$analytics_data['registered_users'],
                "Webpage Analytics $db_interval Acc Sessions" =>$analytics_data['sessions'],
                "Webpage Analytics $db_interval Acc RUsers Sessions" =>$analytics_data['sessions_registered_users'],
                "Webpage Analytics $db_interval Acc SC Impressions" =>$analytics_data['impressions'],
                "Webpage Analytics $db_interval Acc SC Clicks" =>$analytics_data['clicks'],
                "Webpage Analytics $db_interval Acc SC CTR" =>$analytics_data['ctr'],
                "Webpage Analytics $db_interval Acc SC Position" =>$analytics_data['position'],



            );


            $this->fast_update($data_to_update, 'Webpage Analytics Data');


        }

        /*

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

            $this->fast_update(['Store Acc To Day Updated' => gmdate('Y-m-d H:i:s')]);

        } elseif (in_array(
            $db_interval, [
                '1 Year',
                '1 Month',
                '1 Week',
                '1 Quarter'
            ]
        )) {

            $this->fast_update(['Store Acc Ongoing Intervals Updated' => gmdate('Y-m-d H:i:s')]);
        } elseif (in_array(
            $db_interval, [
                'Last Month',
                'Last Week',
                'Yesterday',
                'Last Year'
            ]
        )) {

            $this->fast_update(['Store Acc Previous Intervals Updated' => gmdate('Y-m-d H:i:s')]);
        }
*/

    }


}


