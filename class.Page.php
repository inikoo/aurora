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
include_once 'trait.WebpageAiku.php';

use Nesk\Puphpeteer\Puppeteer;
use Nesk\Rialto\Exceptions\Node;


class Page extends DB_Table
{
    use ImageSubject, NotesSubject, WebpageAiku;

    var $new = false;
    var $logged = false;

    var $deleted = false;

    /** @var PDO */
    var $db;

    function __construct($arg1 = false, $arg2 = false, $arg3 = false, $_db = false)
    {
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


    function get_data($tipo, $tag, $tag2 = false)
    {
        if (preg_match('/url|address|www/i', $tipo)) {
            $sql = sprintf(
                "SELECT * FROM `Page Store Dimension` PS WHERE `Webpage URL`=%s",
                prepare_mysql($tag)
            );
        } elseif ($tipo == 'store_page_code') {
            $sql = sprintf(
                "SELECT * FROM `Page Store Dimension` PS  WHERE `Webpage Code`=%s AND `Webpage Store Key`=%d ",
                prepare_mysql($tag2),
                $tag
            );
        } elseif ($tipo == 'website_code') {
            $sql = sprintf(
                "SELECT * FROM `Page Store Dimension` PS  WHERE `Webpage Code`=%s AND PS.`Webpage Website Key`=%d ",
                prepare_mysql($tag2),
                $tag
            );
        } elseif ($tipo == 'scope') {
            $sql = sprintf(
                "SELECT * FROM `Page Store Dimension` PS  WHERE `Webpage Scope`=%s AND `Webpage Scope Key`=%d ",
                prepare_mysql($tag),
                $tag2
            );
        } elseif ($tipo == 'deleted') {
            $this->get_deleted_data($tag);

            return;
        } else {
            $sql = sprintf(
                "SELECT * FROM `Page Store Dimension` PS WHERE `Page Key`=%d",
                $tag
            );
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Page Key'];


            $sql = sprintf("SELECT * FROM `Page Store Dimension` WHERE  `Page Key`=%d", $this->id);


            if ($result2 = $this->db->query($sql)) {
                if ($row2 = $result2->fetch()) {
                    foreach ($row2 as $key => $value) {
                        $this->data[$key] = $value;
                    }

                    $this->properties = json_decode($this->data['Webpage Properties'], true);
                }
            }
        }
    }

    function get_deleted_data($tag)
    {
        $this->deleted = true;
        $sql           = sprintf(
            "SELECT * FROM `Page Store Deleted Dimension` WHERE `Page Key`=%d",
            $tag
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

    function find($raw_data, $options)
    {
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
            "SELECT P.`Page Key` FROM `Page Store Dimension` P  WHERE `Webpage Code`=%s AND `Webpage Website Key`=%d ",
            prepare_mysql($raw_data['Webpage Code']),
            $raw_data['Webpage Website Key']
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->found     = true;
                $this->found_key = $row['Page Key'];
                $this->get_data('id', $this->found_key);
            }
        }


        if (!$this->found and $create) {
            $this->create($raw_data);
        }
    }


    function create($raw_data)
    {
        $this->new = false;
        if (!isset($raw_data['Webpage Code']) or $raw_data['Webpage Code'] == '') {
            $this->error = true;
            $this->msg   = 'No page code';
        }


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
        $raw_data['Webpage Sticky Note'] = '';


        $data['Webpage Properties'] = '{}';

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
            if (!$this->id) {
                throw new Exception('Error inserting '.$this->table_name);
            }

            $this->get_data('id', $this->id);
            $this->new = true;

            /*
            $sql = sprintf(
                "INSERT INTO `Webpage Analytics Data` (`Webpage Analytics Webpage Key`) VALUES (%d)", $this->id
            );
            $this->db->exec($sql);
            */

            $sql = sprintf(
                "INSERT INTO `Page Store Data Dimension` (`Page Key`) VALUES (%d)",
                $this->id
            );
            $this->db->exec($sql);


            $sql = sprintf(
                "INSERT INTO `Page State Timeline`  (`Page Key`,`Website Key`,`Store Key`,`Date`,`State`,`Operation`) VALUES (%d,%d,%d,%s,%s,'Created') ",
                $this->id,
                $this->data['Webpage Website Key'],
                $this->data['Webpage Store Key'],
                prepare_mysql(gmdate('Y-m-d H:i:s')),
                prepare_mysql($this->data['Webpage State'])

            );
            $this->db->exec($sql);
            $this->update_url();
            $this->refresh_cache();


            $account = get_object('Account', 1);
            require_once 'utils/new_fork.php';
            new_housekeeping_fork(
                'au_take_webpage_screenshot',
                array(
                    'webpage_key' => $this->id,
                ),
                $account->get('Account Code'),
                $this->db
            );

            $this->model_updated('new', $this->id);

            return $this;

            $this->fork_index_elastic_search();
        } else {
            $this->error = true;
            $this->msg   = 'Can not insert Page Store Dimension';
            print_r($stmt->errorInfo());
            //print "$sql\n";
            exit;
        }
    }

    function store_base_data()
    {
        $data = array();


        $sql = 'show columns from `Page Store Dimension`';
        foreach ($this->db->query($sql) as $row) {
            if (!in_array($row['Field'], $this->ignore_fields)) {
                $data[$row['Field']] = $row['Default'];
            }
        }


        return $data;
    }


    function update_url()
    {
        $website = get_object('website', $this->get('Webpage Website Key'));


        $url_code = $this->get('Code');
        if ($this->get('Canonical Code') != '') {
            $url_code = $this->get('Canonical Code');
        }


        $this->update(array('Webpage URL' => 'https://'.$website->get('Website URL').'/'.strtolower($url_code)), 'no_history');
    }

    function get($key)
    {
        if (!$this->id) {
            return '';
        }

        switch ($key) {
            case 'Website Registration Type':
            case 'Registration Type':

                $website = get_object('website', $this->get('Webpage Website Key'));

                return $website->get($key);
            case 'Icon':

                switch ($this->data['Webpage Scope']) {
                    case 'Product':
                        return 'cube';

                    case 'Category Products':
                        return 'folder-open';
                    case 'Category Categories':
                        return 'folder-tree';
                    default:
                        return '';
                }

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


            case 'Send Email Address':
                $store = get_object('Store', $this->data['Webpage Store Key']);

                return $store->get('Store Email');


            case 'Send Email Signature':
                $store = get_object('Store', $this->data['Webpage Store Key']);

                return $store->get('Store Email Template Signature');


            case 'Email':
            case 'Company Name':
            case 'VAT Number':
            case 'Company Number':
            case 'Telephone':
            case 'Address':
            case 'Google Map URL':

            case 'Store Email':
            case 'Store Company Name':
            case 'Store VAT Number':
            case 'Store Company Number':
            case 'Store Telephone':
            case 'Store Address':
            case 'Store Google Map URL':
                $store = get_object('Store', $this->data['Webpage Store Key']);

                return $store->get($key);

                break;


            case 'Publish':


                if ($this->data['Page Store Content Data'] != $this->data['Page Store Content Published Data']) {
                    return true;
                }


                return false;


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

            case 'Content Data':
                if ($this->data['Page Store '.$key] == '') {
                    $content_data = false;
                } else {
                    $content_data = json_decode($this->data['Page Store '.$key], true);
                }

                return $content_data;


            case 'Scope Metadata':

                if ($this->data['Webpage '.$key] == '') {
                    $content_data = false;
                } else {
                    $content_data = json_decode($this->data['Webpage '.$key], true);
                }

                return $content_data;


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
            case 'Webpage Browser Title':
            case 'Browser Title':

                return $this->get_browser_title();


            case 'Webpage Children Browser Title Format':
            case 'Children Browser Title Format':
                return $this->properties('children_browser_title_format');

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


    function get_browser_title()
    {
        $title_format = '';
        switch ($this->data['Webpage Scope']) {
            case 'Category Products':


                $category = get_object('Category', $this->data['Webpage Scope Key']);


                if ($category->get('Product Category Department Category Key')) {
                    $parent         = get_object('Category', $category->get('Product Category Department Category Key'));
                    $parent_webpage = get_object('Webpage', $parent->get('Product Category Webpage Key'));
                    if ($parent_webpage->get('Webpage State') == 'Online') {
                        $title_format = $parent_webpage->get('Webpage Children Browser Title Format');
                    }
                }

                break;
            case 'Product':
                $product            = get_object('Product', $this->data['Webpage Scope Key']);
                $parent_webpage_key = '';
                if ($product->get('Product Family Category Key')) {
                    $parent         = get_object('Category', $product->get('Product Family Category Key'));
                    $parent_webpage = get_object('Webpage', $parent->get('Product Category Webpage Key'));
                    if ($parent_webpage->get('Webpage State') == 'Online') {
                        $title_format       = $parent_webpage->get('Webpage Children Browser Title Format');
                        $parent_webpage_key = $parent_webpage->id;
                    }
                }

                if ($title_format == '' and $parent_webpage_key) {
                    if ($parent->get('Product Category Department Category Key')) {
                        $grandparent         = get_object('Category', $parent->get('Product Category Department Category Key'));
                        $grandparent_webpage = get_object('Webpage', $grandparent->get('Product Category Webpage Key'));
                        if ($grandparent_webpage->get('Webpage State') == 'Online') {
                            $title_format = $grandparent_webpage->get('Webpage Children Browser Title Format');
                        }
                    }
                }


                break;
        }


        if ($title_format == '') {
            $website      = get_object('Website', $this->get('Webpage Website Key'));
            $title_format = $website->get('Website Settings Browser Title Format');
        }

        if ($title_format == '') {
            return $this->data['Webpage Name'];
        } else {
            if (!isset($website)) {
                $website = get_object('Website', $this->get('Webpage Website Key'));
            }

            $placeholders = array(
                '[Webpage]' => $this->data['Webpage Name'],
                '[webpage]' => $this->data['Webpage Name'],
                '[Child]'   => $this->data['Webpage Name'],
                '[child]'   => $this->data['Webpage Name'],
                '[]'        => $this->data['Webpage Name'],
                '[W]'       => $this->data['Webpage Name'],
                '[w]'       => $this->data['Webpage Name'],
                '[Website]' => $website->get('Webpage Name'),
                '[website]' => $website->get('Webpage Name')
            );

            return strtr($title_format, $placeholders);
        }
    }


    function properties($key)
    {
        return (isset($this->properties[$key]) ? $this->properties[$key] : '');
    }

    function refresh_cache()
    {
        $template_response = '';


        if (!$this->fork) {
            require_once 'utils/new_fork.php';
            new_housekeeping_fork(
                'au_housekeeping',
                array(
                    'type'        => 'clear_smarty_web_cache',
                    'webpage_key' => $this->id
                ),
                DNS_ACCOUNT_CODE,
                $this->db
            );
        } else {
            $this->clear_cache();
        }


        $redis = new Redis();
        if ($redis->connect(REDIS_HOST, REDIS_PORT)) {
            $url_cache_key = 'pwc3|'.DNS_ACCOUNT_CODE.'|'.$this->get('Webpage Website Key').'_'.$this->get('Webpage Code');
            $redis->set($url_cache_key, $this->id);
            $url_cache_key = 'pwc3|'.DNS_ACCOUNT_CODE.'|'.$this->get('Webpage Website Key').'_'.strtoupper($this->get('Webpage Code'));
            $redis->set($url_cache_key, $this->id);
            $url_cache_key = 'pwc3|'.DNS_ACCOUNT_CODE.'|'.$this->get('Webpage Website Key').'_'.strtolower($this->get('Webpage Code'));
            $redis->set($url_cache_key, $this->id);
        }


        return $template_response;
    }

    function clear_cache()
    {
        //        print $this->get('URL');

        foreach (VARNISH_URLS as $varnish_url) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $varnish_url);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "BAN");
            curl_setopt($curl, CURLOPT_PORT, VARNISH_PORT);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


            curl_setopt($curl, CURLOPT_HTTPHEADER, ['x-ban-wpk: '.DNS_ACCOUNT_CODE.'-'.$this->id]);

            curl_exec($curl);

            //print $server_output;
            curl_close($curl);
        }
        /*
        $cache_id = '_'.$this->id.'|'.$this->get('Webpage Website Key').'|'.DNS_ACCOUNT_CODE;


        $smarty_web               = new Smarty();
        $smarty_web->caching_type = 'redis';
        $smarty_web->setTemplateDir('EcomB2B/templates');
        $smarty_web->addPluginsDir('./smarty_plugins');
        $smarty_web->setCaching(Smarty::CACHING_LIFETIME_CURRENT);

        $theme        = 'theme_1';
        $website_type = 'EcomB2B';


        $smarty_web->clearCache($theme.'/webpage_blocks.'.$theme.'.'.$website_type.'.tpl', 'in'.$cache_id);
        $smarty_web->clearCache($theme.'/webpage_blocks.'.$theme.'.'.$website_type.'.tpl', 'out'.$cache_id);

        $smarty_web->clearCache($theme.'/webpage_blocks.'.$theme.'.'.$website_type.'.tablet.tpl', 'in'.$cache_id);
        $smarty_web->clearCache($theme.'/webpage_blocks.'.$theme.'.'.$website_type.'.tablet.tpl', 'out'.$cache_id);

        $smarty_web->clearCache($theme.'/webpage_blocks.'.$theme.'.'.$website_type.'.mobile,tpl', 'in'.$cache_id);
        $smarty_web->clearCache($theme.'/webpage_blocks.'.$theme.'.'.$website_type.'.mobile.tpl', 'out'.$cache_id);
        */
    }

    function unpublish()
    {
        $this->update_state('Offline');

        $webpage_type = get_object('Webpage_Type', $this->get('Webpage Type Key'));
        $webpage_type->update_number_webpages();

        require_once 'utils/new_fork.php';
        new_housekeeping_fork(
            'au_housekeeping',
            array(
                'type'        => 'clear_smarty_web_cache',
                'webpage_key' => $this->id
            ),
            DNS_ACCOUNT_CODE,
            $this->db
        );


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

    function update_state($value, $options = '')
    {
        if (!$this->id) {
            return;
        }

        $old_state = $this->data['Webpage State'];


        $this->update_field('Webpage State', $value, $options);


        if ($old_state != $this->data['Webpage State']) {
            if ($this->data['Webpage State'] == 'Offline') {
                $this->update_field('Webpage Take Down Date', gmdate('Y-m-d H:i:s'), 'no_history');
            }

            $sql = sprintf(
                "INSERT INTO `Page State Timeline`  (`Page Key`,`Website Key`,`Store Key`,`Date`,`State`,`Operation`) VALUES (%d,%d,%d,%s,%s,'Change') ",
                $this->id,
                $this->data['Webpage Website Key'],
                $this->data['Webpage Store Key'],
                prepare_mysql(gmdate('Y-m-d H:i:s')),
                prepare_mysql($this->data['Webpage State'])

            );

            $this->db->exec($sql);

            //todo Urder redo osee also


            $this->reindex_items();

            if ($this->updated and $this->data['Webpage State'] == 'Online') {
                $this->publish();
            }


            $website = get_object('Website', $this->data['Webpage Website Key']);
            $website->update_website_webpages_data();

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

    function reindex_items()
    {
        $this->updated = false;

        $block_index = 0;

        $content_data = $this->get('Content Data');

        $sql = "DELETE FROM `Website Webpage Scope Map` WHERE `Website Webpage Scope Webpage Key`=?";


        $this->db->prepare($sql)->execute(
            array(
                $this->id
            )
        );


        $this->db->exec($sql);

        if (isset($content_data['blocks'])) {
            foreach ($content_data['blocks'] as $block_key => $block) {
                $block_index++;


                switch ($block['type']) {
                    case 'category_products':
                        $this->reindex_category_products($block_index);
                        break;
                    case 'category_categories':
                        $this->reindex_category_categories($block_index);
                        break;
                    case 'products':
                        $this->reindex_products($block_key, $block_index);
                        break;
                    case 'product':
                        $this->reindex_product($block_index);
                        break;
                    case 'see_also':
                        $this->reindex_see_also($block_index);
                        break;
                }
            }
        }
        $this->reindex_webpage_scope_map();
        $this->updated = true;


        /*
         *
         * Only usefful is you want to reindex directjy in pweb
        $category_keys = [];
        $product_keys  = [];

        $sql  = "select `Website Webpage Scope Scope`,`Website Webpage Scope Scope Webpage Key` from `Website Webpage Scope Map` WHERE `Website Webpage Scope Webpage Key`=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        while ($row = $stmt->fetch()) {
            if ($row['Website Webpage Scope Scope'] == 'Category') {
                $category_keys[] = $row['Website Webpage Scope Scope Webpage Key'];
            } else {
                $product_keys[] = $row['Website Webpage Scope Scope Webpage Key'];
            }

        }

        $this->fast_update_json_field('Webpage Properties', 'category_keys', join($category_keys, " "));
        $this->fast_update_json_field('Webpage Properties', 'products_keys', join($product_keys, " "));
*/

        $this->clear_cache();

        /*
        require_once 'utils/new_fork.php';
        new_housekeeping_fork(
            'au_housekeeping', array(
            'type'        => 'clear_smarty_web_cache',
            'webpage_key' => $this->id
        ), DNS_ACCOUNT_CODE, $this->db
        );
        */

        $redis = new Redis();
        if ($redis->connect(REDIS_HOST, REDIS_PORT)) {
            $cache_id_prefix = 'pwc3|'.DNS_ACCOUNT_CODE.'|'.$this->get('Webpage Website Key').'_';

            $redis->del($cache_id_prefix.$this->data['Webpage Code']);
            $redis->del($cache_id_prefix.strtolower($this->data['Webpage Code']));
            $redis->del($cache_id_prefix.strtoupper($this->data['Webpage Code']));
            $redis->del($cache_id_prefix.ucfirst($this->data['Webpage Code']));
        }
    }

    function reindex_category_products($block_index)
    {
        $content_data = $this->get('Content Data');

        $block_found = false;
        $block_key   = 0;
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


        $items_product_id_index = array();
        $items_out_of_stock     = array();


        $sql =
            "SELECT P.`Product ID`  FROM `Category Bridge` B  LEFT JOIN `Product Dimension` P ON (`Subject Key`=P.`Product ID`)  WHERE  `Category Key`=?  AND `Product Web State` IN  ('For Sale','Out of Stock') and is_variant!='Yes'  and `Product Customer Key` is null  ORDER BY `Product Web State`,`Product Code File As`";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->data['Webpage Scope Key']
            )
        );
        while ($row = $stmt->fetch()) {
            $items_product_id_index[$row['Product ID']] = $row['Product ID'];
        }


        if (isset($block['items']) and is_array($block['items'])) {
            foreach ($block['items'] as $item_key => $item) {
                if ($item['type'] == 'product') {
                    if (in_array($item['product_id'], $items_product_id_index)) {
                        $product = get_object('Public_Product', $item['product_id']);

                        if ($product->id) {
                            if ($product->get('Product Web State') == 'For Sale') {
                                $product->load_webpage();


                                if ($product->get('Image') != $content_data['blocks'][$block_key]['items'][$item_key]['image_src']) {
                                    $content_data['blocks'][$block_key]['items'][$item_key]['image_src']            = $product->get('Image');
                                    $content_data['blocks'][$block_key]['items'][$item_key]['image_mobile_website'] = '';
                                    $content_data['blocks'][$block_key]['items'][$item_key]['image_website']        = '';
                                }


                                $content_data['blocks'][$block_key]['items'][$item_key]['web_state'] = $product->get('Web State');
                                $content_data['blocks'][$block_key]['items'][$item_key]['price']     = $product->get('Price');


                                $content_data['blocks'][$block_key]['items'][$item_key]['price_unit']     = $product->get('Price Per Unit');
                                $content_data['blocks'][$block_key]['items'][$item_key]['price_unit_bis'] = $product->get('Price Per Unit Bis');
                                $content_data['blocks'][$block_key]['items'][$item_key]['variants']       = $product->get_variants_data();


                                $content_data['blocks'][$block_key]['items'][$item_key]['rrp']          = $product->get('RRP');
                                $content_data['blocks'][$block_key]['items'][$item_key]['code']         = $product->get('Code');
                                $content_data['blocks'][$block_key]['items'][$item_key]['name']         = $product->get('Name');
                                $content_data['blocks'][$block_key]['items'][$item_key]['link']         = $product->webpage->get('URL');
                                $content_data['blocks'][$block_key]['items'][$item_key]['webpage_code'] = $product->webpage->get('Webpage Code');
                                $content_data['blocks'][$block_key]['items'][$item_key]['webpage_key']  = $product->webpage->id;


                                $content_data['blocks'][$block_key]['items'][$item_key]['out_of_stock_class']      = $product->get('Out of Stock Class');
                                $content_data['blocks'][$block_key]['items'][$item_key]['out_of_stock_label']      = $product->get('Out of Stock Label');
                                $content_data['blocks'][$block_key]['items'][$item_key]['sort_code']               = $product->get('Code File As');
                                $content_data['blocks'][$block_key]['items'][$item_key]['sort_name']               = $product->get('Product Name');
                                $content_data['blocks'][$block_key]['items'][$item_key]['next_shipment_timestamp'] = $product->get('Next Supplier Shipment Timestamp');

                                $content_data['blocks'][$block_key]['items'][$item_key]['category']                = $product->get('Family Code');
                                $content_data['blocks'][$block_key]['items'][$item_key]['raw_price']               = $product->get('Product Price');
                                $content_data['blocks'][$block_key]['items'][$item_key]['number_visible_variants'] = $product->get('number_visible_variants');
                                $content_data['blocks'][$block_key]['items'][$item_key]['family_key']              = $product->get('Product Family Category Key');
                                // print_r($content_data['blocks'][$block_key]['items'][$item_key]);

                            } else {
                                $items_out_of_stock[$product->id] = $content_data['blocks'][$block_key]['items'][$item_key];
                                unset($content_data['blocks'][$block_key]['items'][$item_key]);
                            }
                        } else {
                            unset($content_data['blocks'][$block_key]['items'][$item_key]);
                        }
                        unset($items_product_id_index[$item['product_id']]);
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
                    'number_visible_variants' => $product->get('number_visible_variants'),


                );


                array_unshift($content_data['blocks'][$block_key]['items'], $item);
            }
        }

        //$items_out_of_stock = array_reverse($items_out_of_stock);


        foreach ($items_out_of_stock as $product_id => $item_data) {
            $product = get_object('Public_Product', $product_id);

            if ($product->id) {
                $product->load_webpage();


                $item = array(
                    'type'                    => 'product',
                    'product_id'              => $product_id,
                    'web_state'               => $product->get('Web State'),
                    'price'                   => $product->get('Price'),
                    'price_unit'              => $product->get('Price Per Unit'),

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
                    'number_visible_variants' => $product->get('number_visible_variants'),
                    'variants'                => $product->get_variants_data()


                );

                if (!empty($item_data['header_text'])) {
                    $item['header_text'] = $item_data['header_text'];
                }

                array_push($content_data['blocks'][$block_key]['items'], $item);
            }
        }


        $this->update_field_switcher('Page Store Content Data', json_encode($content_data), 'no_history');

        $sql = sprintf("DELETE FROM `Website Webpage Scope Map` WHERE `Website Webpage Scope Webpage Key`=%d AND `Website Webpage Scope Type`='Category_Products_Item'", $this->id);
        $this->db->exec($sql);

        $index = 0;
        foreach ($content_data['blocks'][$block_key]['items'] as $item) {
            if ($item['type'] == 'product') {
                $sql = sprintf(
                    'INSERT INTO `Website Webpage Scope Map` (`Website Webpage Block Index`,`Website Webpage Scope Scope Webpage Key`,
                    `Website Webpage Scope Website Key`,`Website Webpage Scope Webpage Key`,`Website Webpage Scope Scope`,`Website Webpage Scope Scope Key`,`Website Webpage Scope Type`,`Website Webpage Scope Index`) 
                    VALUES (%d,%d,%d,%d,%s,%d,%s,%d) ',
                    $block_index,
                    $item['webpage_key'],
                    $this->get('Webpage Website Key'),
                    $this->id,
                    prepare_mysql('Product'),
                    $item['product_id'],
                    prepare_mysql('Category_Products_Item'),
                    $index

                );


                $this->db->exec($sql);
                $index++;
            }
        }
    }

    function update_field_switcher($field, $value, $options = '', $metadata = '')
    {
        switch ($field) {
            case 'desktop_screenshot':
            case 'mobile_screenshot':
            case 'tablet_screenshot':
            case 'full_webpage_screenshot':

                $this->fast_update_json_field('Webpage Properties', preg_replace('/\s/', '_', $field), $value);

                break;
            case 'Children Browser Title Format':
                $this->fast_update_json_field('Webpage Properties', 'children_browser_title_format', $value);


                $account = get_object('Account', 1);
                require_once 'utils/new_fork.php';
                new_housekeeping_fork(
                    'au_housekeeping',
                    array(
                        'type'        => 'refresh_cache_webpage_category_children',
                        'webpage_key' => $this->id,
                    ),
                    $account->get('Account Code'),
                    $this->db
                );


                break;
            case('Webpage Code'):
            case('Webpage Canonical Code'):

                $this->update_field($field, $value, $options);
                $this->update_url();


                $this->update_metadata = array(
                    'class_html' => array(
                        'Webpage_URL' => $this->get('Webpage URL'),

                    ),

                );


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

            case 'Webpage Name':
            case 'Webpage Meta Description':
            case('Webpage Scope'):
            case('Webpage Scope Key'):
            case('Webpage Scope Metadata'):
            case('Webpage Website Key'):
            case('Webpage Store Key'):
            case 'Webpage Redirection Code':

            case('Webpage Type Key'):
            case 'Webpage Launch Date':
            case 'Webpage URL':
            case 'Webpage Sticky Note';
            case 'Webpage Blog URL':
                $this->update_field($field, $value, $options);
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


            case('Webpage State'):


                $this->update_state($value, $options);

                break;

            case 'Page Store Content Data':


                $content_data = json_decode($value, true);


                if (isset($content_data['blocks'])) {
                    include_once('utils/webpage_blocks_digest_functions.php');

                    $content_data = digest_website_content_data_blocks($content_data);
                }


                $value = json_encode($content_data);

                $this->update_field('Page Store Content Data', $value, $options);


                break;


            case 'Website Registration Type':


                /*
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
*/

                /** @var  $website \Website */
                $website = get_object('website', $this->get('Webpage Website Key'));

                $old_type = $website->get('Website Registration Type');

                $website->editor = $this->editor;
                $website->update_field_switcher($field, $value, $options);
                if ($website->updated) {
                    $this->updated;


                    //print_r($backup);
                    //print_r($old_type);

                    // $backup[$old_type] = $old_content_data;

                    //if (isset($backup[$value])) {
                    //    $this->update(array('Page Store Content Data' => json_encode($backup[$value])), 'no_history');
                    //} else {
                    $this->reset_object();
                    // }
                    // $this->update_content_data('backup', $backup);


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

    function update_content_data($field, $value, $options = '')
    {
        $content_data = $this->get('Content Data');

        $content_data[$field] = $value;

        $this->update_field('Page Store Content Data', json_encode($content_data), $this->no_history);
    }

    function reset_object()
    {
        $website = get_object('Website', $this->get('Webpage Website Key'));


        if ($this->get('Webpage Scope') == 'Category Products') {
            $category = get_object('Category', $this->get('Webpage Scope Key'));


            $items = array();


            $sql = sprintf(
                "SELECT P.`Product ID`  FROM `Category Bridge` B  LEFT JOIN `Product Dimension` P ON (`Subject Key`=P.`Product ID`)  
                    WHERE  `Category Key`=%d  AND `Product Web State` IN  ('For Sale','Out of Stock')   ORDER BY `Product Web State` ",
                $category->id
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
                        'number_visible_variants' => $product->get('number_visible_variants')
                    );
                }
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
        } elseif ($this->get('Webpage Scope') == 'Category Categories') {
            $items = array();

            $sql = "SELECT  `Image File Format`,`Webpage URL`,`Category Main Image Key`,`Category Main Image`,`Category Label`,`Category Main Image Key`,`Webpage State`,`Product Category Public`,`Webpage State`,`Page Key`,`Webpage Code`,`Product Category Active Products`,`Category Code`,B.`Category Key` ,`Product Category Key`
                  FROM    `Category Bridge` B  LEFT JOIN  `Product Category Dimension` P   ON (`Subject Key`=`Product Category Key` AND `Subject`='Category' )    LEFT JOIN `Category Dimension` Cat ON (Cat.`Category Key`=P.`Product Category Key`) 
                      LEFT JOIN `Page Store Dimension` CatWeb ON (CatWeb.`Page Key`=`Product Category Webpage Key`) 
                      LEFT JOIN `Image Dimension` I ON (`Category Main Image Key`=I.`Image Key`) 

                    WHERE  B.`Category Key`=? AND `Product Category Public`='Yes' AND `Webpage State` IN ('Online','Ready') ORDER BY `Category Label`";

            $stmt = $this->db->prepare($sql);
            $stmt->execute(
                array(
                    $this->get('Webpage Scope Key')
                )
            );
            while ($row = $stmt->fetch()) {
                $items[] = array(
                    'type'                 => 'category',
                    'category_key'         => $row['Product Category Key'],
                    'header_text'          => trim(strip_tags($row['Category Label'])),
                    'image_caption'        => trim(strip_tags($row['Category Label'])),
                    'image_src'            => ($row['Category Main Image Key'] ? 'wi/'.$row['Category Main Image Key'].'.'.$row['Image File Format'] : '/art/nopic.png'),
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
        } elseif ($this->get('Webpage Scope') == 'Product') {
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
            include_once 'conf/website_system_webpages.php';


            $website_system_webpages = website_system_webpages_config($website->get('Website Type'));

            if (isset($website_system_webpages[$this->get('Webpage Code')]['Page Store Content Data'])) {
                $this->update(array('Page Store Content Data' => $website_system_webpages[$this->get('Webpage Code')]['Page Store Content Data']), 'no_history');
            }
            $this->reindex();
        }


        if ($this->get('Website Status') == 'Active') {
            $this->publish();
        }
    }

    function update_navigation()
    {
        $this->update_public_navigation();
        $this->update_internal_navigation();
    }



    function update_public_navigation()
    {
        $navigation_data = array(
            'show'        => false,
            'breadcrumbs' => array(),
            'prev'        => false,
            'next'        => false,
        );


        switch ($this->get('Webpage Scope')) {

            case 'Category Categories':
                $store = get_object('Store', $this->data['Webpage Store Key']);

                $website = get_object('Website', $this->data['Webpage Website Key']);

                $category = get_object('Category', $this->data['Webpage Scope Key']);
                $navigation_data['show'] = 1;
                $navigation_data['breadcrumbs'][] = array(
                    'link'        => 'https://'.$website->get('Website URL'),
                    'label'       => '<i class="fa fa-home"></i>',
                    'label_short' => '<i class="fa fa-home"></i>',
                    'title'       => _('Home'),
                    'type'        => 'home'

                );

                if($store->get('Store Department Category Key')!= $category->get('Category Root Key')){


                    $parent_webpage_key = 0;


                    if ($category->get('Category Parent Key')  and $category->get('Category Parent Key')!= $category->get('Category Root Key') ) {
                        $parent         = get_object('Category', $category->get('Category Parent Key'));
                        $parent_webpage = get_object('Webpage', $parent->get('Product Category Webpage Key'));
                        if ($parent_webpage->get('Webpage State') == 'Offline') {
                            $parent_webpage_key = 0;
                        } else {
                            $parent_webpage_key = $parent_webpage->id;
                        }
                    }





                    if ($parent_webpage_key) {
                        $navigation_data['breadcrumbs'][] = array(
                            'link'        => $parent_webpage->get('Webpage URL'),
                            'label'       => $parent_webpage->get('Name'),
                            'label_short' => $parent_webpage->get('Webpage Code'),
                            'title'       => $parent_webpage->get('Webpage Browser Title'),
                            'type'        => 'Category Categories',
                            'icon'        => 'folder-tree',
                            'key'         => $parent_webpage->id
                        );
                    }

                }



                $navigation_data['breadcrumbs'][] = array(
                    'link'        => $this->get('Webpage URL'),
                    'label'       => $this->get('Name'),
                    'label_short' => $this->get('Webpage Code'),
                    'title'       => $this->get('Webpage Browser Title'),
                    'type'        => 'Category Categories',
                    'icon'        => 'folder-tree',
                    'key'         => $this->id
                );


              //  print_r($navigation_data);

                $this->update_field('Webpage Navigation Data', json_encode($navigation_data), 'no_history');


                break;
            case 'Category Products':

                $store = get_object('Store', $this->data['Webpage Store Key']);
                $website = get_object('Website', $this->data['Webpage Website Key']);

                $category = get_object('Category', $this->data['Webpage Scope Key']);






                $navigation_data['breadcrumbs'][] = array(
                    'link'        => 'https://'.$website->get('Website URL'),
                    'label'       => '<i class="fa fa-home"></i>',
                    'label_short' => '<i class="fa fa-home"></i>',
                    'title'       => _('Home'),
                    'type'        => 'home'

                );
                //-------------------------

                if($store->get('Store Family Category Key')!= $category->get('Category Root Key')){
                    $parent_webpage_key=null;

                    $sql="select `Category Head Key` from `Category Bridge` where `Subject Key`=? and `Subject`='Category' and `Category Key`!=?  ";

                 //  print "$sql";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute(
                        array(
                        $category->id,$category->get('Category Root Key')
                        )
                    );
                    while ($row = $stmt->fetch()) {

                        $parent         = get_object('Category', $row['Category Head Key']);

                        if($parent->id!=$parent->get('Category Root Key')) {
                            $parent_webpage = get_object('Webpage', $parent->get('Product Category Webpage Key'));
                            if ($parent_webpage->id && $parent_webpage->get('Webpage State') == 'Online') {
                             //   print_r($parent);
                              //  print_r($parent_webpage);

                                $parent_webpage_key = $parent_webpage->id;
                            }
                        }

                    }

                    if ($parent_webpage_key) {


                        $navigation_data['breadcrumbs'][] = array(
                            'link'        => $parent_webpage->get('Webpage URL'),
                            'label'       => $parent_webpage->get('Name'),
                            'label_short' => $parent_webpage->get('Webpage Code'),
                            'title'       => $parent_webpage->get('Webpage Browser Title'),
                            'type'        => 'Category Categories',
                            'icon'        => 'folder-tree',
                            'key'         => $parent_webpage->id
                        );
                    }



                   // print_r($navigation_data);



                }else{


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





                               if ($parent_webpage_key) {
                                   $navigation_data['breadcrumbs'][] = array(
                                       'link'        => $parent_webpage->get('Webpage URL'),
                                       'label'       => $parent_webpage->get('Name'),
                                       'label_short' => $parent_webpage->get('Webpage Code'),
                                       'title'       => $parent_webpage->get('Webpage Browser Title'),
                                       'type'        => 'Category Categories',
                                       'icon'        => 'folder-tree',
                                       'key'         => $parent_webpage->id
                                   );
                               }

                }




                $navigation_data['breadcrumbs'][] = array(
                    'link'        => $this->get('Webpage URL'),
                    'label'       => $this->get('Name'),
                    'label_short' => $this->get('Webpage Code'),
                    'title'       => $this->get('Webpage Browser Title'),
                    'type'        => 'Category Categories',
                    'icon'        => 'folder-tree',
                    'key'         => $this->id
                );





                //======================



                //print_r($parent_webpage);

                $prev = false;
                $next = false;

                $next_key = 0;
                $prev_key = 0;

                $sql = sprintf(
                    "SELECT `Website Webpage Scope Index` FROM `Website Webpage Scope Map` WHERE `Website Webpage Scope Webpage Key`=%d  AND `Website Webpage Scope Scope`='Category' AND `Website Webpage Scope Scope Key`=%d ",
                    $parent_webpage_key,
                    $category->id

                );
                // print $sql;

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $sql = sprintf(
                            'SELECT `Website Webpage Scope Scope Key` FROM `Website Webpage Scope Map` WHERE `Website Webpage Scope Webpage Key`=%d  AND  `Website Webpage Scope Type`="Subject" AND  `Website Webpage Scope Scope`="Category" AND `Website Webpage Scope Index`<%d ORDER BY `Website Webpage Scope Index` DESC',
                            $parent_webpage_key,
                            $row['Website Webpage Scope Index']
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
                                }
                            }
                        }


                        $sql = sprintf(
                            'SELECT `Website Webpage Scope Scope Key` FROM `Website Webpage Scope Map`  WHERE `Website Webpage Scope Webpage Key`=%d  AND  `Website Webpage Scope Type`="Subject" AND `Website Webpage Scope Scope`="Category" AND `Website Webpage Scope Index`>%d ORDER BY `Website Webpage Scope Index` ',
                            $parent_webpage_key,
                            $row['Website Webpage Scope Index']
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
                                }
                            }
                        }
                    }
                }

                if ($prev_key) {
                    $prev_category         = get_object('Category', $prev_key);
                    $prev_category_webpage = get_object('Webpage', $prev_category->get('Product Category Webpage Key'));
                    $prev                  = array(
                        'link'        => $prev_category_webpage->get('Webpage URL'),
                        'label'       => $prev_category_webpage->get('Name'),
                        'label_short' => $prev_category_webpage->get('Webpage Code'),
                        'title'       => $prev_category_webpage->get('Webpage Browser Title'),
                        'type'        => 'Category Products',
                        'icon'        => 'folder-open',
                        'key'         => $prev_category_webpage->id
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
                        'type'        => 'Category Products',
                        'icon'        => 'folder-open',
                        'key'         => $prev_category_webpage->id
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
                    if ($parent_webpage->get('Webpage State') == 'Online') {
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
                            'type'        => 'Category Categories',
                            'icon'        => 'folder-tree',
                            'key'         => $grandparent_webpage->id

                        );
                    }


                    $navigation_data['breadcrumbs'][] = array(
                        'link'        => $parent_webpage->get('Webpage URL'),
                        'label'       => $parent_webpage->get('Name'),
                        'label_short' => $parent_webpage->get('Webpage Code'),
                        'title'       => $parent_webpage->get('Webpage Browser Title'),
                        'type'        => 'Category Products',
                        'icon'        => 'folder-open',
                        'key'         => $parent_webpage->id
                    );
                }

                //print_r($parent_webpage);

                $prev = false;
                $next = false;

                $next_key = 0;
                $prev_key = 0;

                $sql = sprintf(
                    'SELECT `Website Webpage Scope Index` FROM `Website Webpage Scope Map` WHERE `Website Webpage Scope Webpage Key`=%d  AND `Website Webpage Scope Scope`="Product" AND `Website Webpage Scope Scope Key`=%d ',
                    $parent_webpage_key,
                    $product->id

                );


                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $sql = sprintf(
                            'SELECT `Website Webpage Scope Scope Key` FROM `Website Webpage Scope Map` WHERE `Website Webpage Scope Webpage Key`=%d  AND  `Website Webpage Scope Type`="Category_Products_Item" AND  `Website Webpage Scope Scope`="Product" AND `Website Webpage Scope Index`<%d ORDER BY `Website Webpage Scope Index` DESC',
                            $parent_webpage_key,
                            $row['Website Webpage Scope Index']
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
                                }
                            }
                        }


                        $sql = sprintf(
                            'SELECT `Website Webpage Scope Scope Key` FROM `Website Webpage Scope Map`  WHERE `Website Webpage Scope Webpage Key`=%d  AND  `Website Webpage Scope Type`="Category_Products_Item" AND `Website Webpage Scope Scope`="Product" AND `Website Webpage Scope Index`>%d ORDER BY `Website Webpage Scope Index` ',
                            $parent_webpage_key,
                            $row['Website Webpage Scope Index']
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
                                }
                            }
                        }
                    }
                }

                if ($prev_key) {
                    $prev_product         = get_object('Product', $prev_key);
                    $prev_product_webpage = get_object('Webpage', $prev_product->get('Product Webpage Key'));
                    $prev                 = array(
                        'link'        => $prev_product_webpage->get('Webpage URL'),
                        'label'       => $prev_product_webpage->get('Name'),
                        'label_short' => $prev_product_webpage->get('Webpage Code'),
                        'title'       => $prev_product_webpage->get('Webpage Browser Title'),
                        'type'        => 'Product',
                        'icon'        => 'cube',
                        'key'         => $prev_product_webpage->id
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
                        'type'        => 'Product',
                        'icon'        => 'cube',
                        'key'         => $next_product_webpage->id
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

    function update_internal_navigation()
    {
        $navigation = $this->get('Navigation Data');
        array_shift($navigation['breadcrumbs']);

        switch ($this->get('Webpage Scope')) {
            case 'Category Categories':


                $sql  =
                    "select `Page Key` from `Page Store Dimension` WP left join `Webpage Type Dimension` WTD  on (WP.`Webpage Type Key`=`WTD`.`Webpage Type Key`)  where `Webpage Type Code`='Cats'  and `Webpage State`!='Offline' and `Page Key`!=?  and `Webpage Website Key`=? and `Webpage Code`>? order by `Webpage Code`,`Page Key` ";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(
                    array(
                        $this->id,
                        $this->data['Webpage Website Key'],

                        strtolower($this->data['Webpage Code'])
                    )
                );


                if ($row = $stmt->fetch()) {
                    $next_key = $row['Page Key'];
                } else {
                    $sql   =
                        "select `Page Key` from `Page Store Dimension` WP left join `Webpage Type Dimension` WTD  on (WP.`Webpage Type Key`=`WTD`.`Webpage Type Key`)  where `Webpage Type Code`='Cats'  and `Webpage State`!='Offline'  and `Page Key`!=?  and `Webpage Website Key`=?   order by `Webpage Code`,`Page Key` ";
                    $stmt2 = $this->db->prepare($sql);
                    $stmt2->execute(
                        array(
                            $this->id,
                            $this->data['Webpage Website Key']
                        )
                    );
                    if ($row2 = $stmt2->fetch()) {
                        $next_key = $row2['Page Key'];
                    } else {
                        $next_key = 0;
                    }
                }

                $sql  =
                    "select `Page Key`,`Webpage Code` from `Page Store Dimension` WP left join `Webpage Type Dimension` WTD  on (WP.`Webpage Type Key`=`WTD`.`Webpage Type Key`)  where `Webpage Type Code`='Cats'  and `Webpage State`!='Offline' and `Page Key`!=?  and `Webpage Website Key`=? and `Webpage Code`<? order by `Webpage Code` desc ,`Page Key` desc";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(
                    array(
                        $this->id,
                        $this->data['Webpage Website Key'],

                        strtolower($this->data['Webpage Code'])
                    )
                );

                $stmt->errorInfo();

                if ($row = $stmt->fetch()) {
                    $prev_key = $row['Page Key'];
                } else {
                    $sql =
                        "select `Page Key` from `Page Store Dimension` WP left join `Webpage Type Dimension` WTD  on (WP.`Webpage Type Key`=`WTD`.`Webpage Type Key`)  where `Webpage Type Code`='Cats'  and `Webpage State`!='Offline'  and `Page Key`!=?  and `Webpage Website Key`=?   order by `Webpage Code` desc,`Page Key` desc";


                    $stmt2 = $this->db->prepare($sql);
                    $stmt2->execute(
                        array(
                            $this->id,
                            $this->data['Webpage Website Key']
                        )
                    );
                    if ($row2 = $stmt2->fetch()) {
                        $prev_key = $row2['Page Key'];
                    } else {
                        $prev_key = 0;
                    }
                }


                if ($prev_key) {
                    $prev_category_webpage = get_object('Webpage', $prev_key);
                    $prev                  = array(
                        'link'        => $prev_category_webpage->get('Webpage URL'),
                        'label'       => $prev_category_webpage->get('Name'),
                        'label_short' => $prev_category_webpage->get('Webpage Code'),
                        'title'       => $prev_category_webpage->get('Webpage Browser Title'),
                        'type'        => 'Category Products',
                        'icon'        => 'folder-open',
                        'key'         => $prev_category_webpage->id
                    );
                    $navigation['prev']    = $prev;
                }


                if ($next_key) {
                    $next_category_webpage = get_object('Webpage', $next_key);
                    $next                  = array(
                        'link'        => $next_category_webpage->get('Webpage URL'),
                        'label'       => $next_category_webpage->get('Name'),
                        'label_short' => $next_category_webpage->get('Webpage Code'),
                        'title'       => $next_category_webpage->get('Webpage Browser Title'),
                        'type'        => 'Category Products',
                        'icon'        => 'folder-open',
                        'key'         => $next_category_webpage->id
                    );
                    $navigation['next']    = $next;
                }


                break;
        }


        $this->fast_update_json_field('Webpage Properties', 'navigation', json_encode($navigation));
    }

    function refill_see_also($convert_to_auto = false, $number_items = false)
    {
        $content_data = $this->get('Content Data');
        $block_found  = false;


        if ($content_data == '') {
            return;
        }
        if (!isset($content_data['blocks'])) {
            return;
        }
        $block_index = 0;
        foreach ($content_data['blocks'] as $_block_key => $_block) {
            $block_index++;
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
                            'image_caption'        => trim(strip_tags($category->get('Category Label'))),
                            'webpage_key'          => $see_also_page->id,
                            'webpage_code'         => $see_also_page->get('Webpage Code'),

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

        $this->reindex_see_also($block_index);
        $this->reindex_webpage_scope_map();
    }

    function get_related_webpages_key($number_items)
    {
        include_once 'elastic/assets_correlation.elastic.php';

        $max_links = $number_items * 2;


        $max_sales_links = ceil($max_links * .6);


        $see_also     = array();
        $number_links = 0;
        $items        = array();
        switch ($this->data['Webpage Scope']) {
            case 'Category Products':

                $result = get_elastic_sales_correlated_assets($this->data['Webpage Scope Key'], 'families_bought', '_1y', $max_sales_links * 2);

                foreach ($result['buckets'] as $row) {
                    $_family  = get_object('Category', $row['key']);
                    $_webpage = $_family->get_webpage();
                    if ($_webpage->id and $_webpage->data['Webpage State'] == 'Online') {
                        $see_also[$_webpage->id] = array(
                            'type'     => 'Sales',
                            'value'    => $row['score'],
                            'page_key' => $_webpage->id
                        );
                        $number_links            = count($see_also);
                        if ($number_links >= $max_sales_links) {
                            break;
                        }
                    }
                }


                if ($number_links < $max_links) {
                    $category = get_object('Category', $this->data['Webpage Scope Key']);

                    $sql = sprintf(
                        "SELECT `Category Key` FROM `Category Dimension` LEFT JOIN   `Product Category Dimension` ON (`Category Key`=`Product Category Key`)   LEFT JOIN `Page Store Dimension` ON (`Product Category Webpage Key`=`Page Key`) WHERE `Category Parent Key`=%d  AND `Webpage State`='Online'  AND `Category Key`!=%d  ORDER BY RAND()  LIMIT %d",
                        $category->get('Category Parent Key'),
                        $category->id,
                        ($max_links - $number_links) * 2
                    );


                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {
                            if (!array_key_exists($row['Category Key'], $see_also)) {
                                $_family  = get_object('Category', $row['Category Key']);
                                $_webpage = $_family->get_webpage();
                                if ($_webpage->id and $_webpage->data['Webpage State'] == 'Online') {
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
                    }
                }


                if ($number_links < $max_links) {
                    $sql = sprintf(
                        "SELECT `Category Key` FROM `Category Dimension` LEFT JOIN   `Product Category Dimension` ON (`Category Key`=`Product Category Key`)   LEFT JOIN `Page Store Dimension` ON (`Product Category Webpage Key`=`Page Key`) WHERE  `Webpage State`='Online'  AND `Category Key`!=%d  AND `Category Store Key`=%d ORDER BY RAND()  LIMIT %d",
                        $this->data['Webpage Scope Key'],
                        $category->get('Category Store Key'),
                        ($max_links - $number_links) * 2
                    );


                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {
                            if (!array_key_exists($row['Category Key'], $see_also)) {
                                $_family  = get_object('Category', $row['Category Key']);
                                $_webpage = $_family->get_webpage();
                                if ($_webpage->id and $_webpage->data['Webpage State'] == 'Online') {
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
                    }
                }


                break;


            case 'Product':

                $product = get_object('Product', $this->data['Webpage Scope Key']);


                $result = get_elastic_sales_correlated_assets($product->id, 'products_bought', '_1y', $max_sales_links * 2);


                foreach ($result['buckets'] as $row) {
                    $_product = get_object('Product', $row['key']);
                    $_webpage = $_product->get_webpage();
                    if ($_webpage->id and $_webpage->data['Webpage State'] == 'Online') {
                        $see_also[$_webpage->id] = array(
                            'type'     => 'Sales',
                            'value'    => $row['score'],
                            'page_key' => $_webpage->id
                        );
                        $number_links            = count($see_also);
                        if ($number_links >= $max_sales_links) {
                            break;
                        }
                    }
                }


                if ($number_links >= $max_links) {
                    break;
                }


                $max_customers = 0;

                $sql = sprintf(
                    "SELECT P.`Product ID`,P.`Product Code`,`Product Web State`,`Product Webpage Key`,`Product Total Acc Customers` FROM `Product Dimension` P LEFT JOIN `Product Data Dimension` D ON (P.`Product ID`=D.`Product ID`)    LEFT JOIN `Page Store Dimension` ON (`Page Key`=`Product Webpage Key`) 
                    WHERE  `Product Web State`='For Sale' AND `Webpage State`='Online' AND P.`Product ID`!=%d  and P.`Product Customer Key` is null  AND `Product Family Category Key`=%d ORDER BY `Product Total Acc Customers` DESC  ",
                    $product->id,
                    $product->get('Product Family Category Key')

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
                }

                if ($number_links >= $max_links) {
                    break;
                }
                $max_customers = 0;
                $sql           = sprintf(
                    "SELECT P.`Product ID`,P.`Product Code`,`Product Web State`,`Product Webpage Key`,`Product Total Acc Customers` FROM `Product Dimension` P LEFT JOIN `Product Data Dimension` D ON (P.`Product ID`=D.`Product ID`)    LEFT JOIN `Page Store Dimension` ON (`Page Key`=`Product Webpage Key`)  WHERE  `Product Web State`='For Sale' AND `Webpage State`='Online' AND P.`Product ID`!=%d  AND `Product Store Key`=%d ORDER BY `Product Total Acc Customers` DESC  ",
                    $product->id,
                    $product->get('Product Store Key')

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
                }


                break;
        }

        $count = 0;

        $order_value = 1;


        if (count($see_also) > 0) {
            foreach ($see_also as $key => $row) {
                $correlation[$key] = $row['value'];
            }


            array_multisort($correlation, SORT_DESC, $see_also);


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

    function reindex_see_also($block_index)
    {
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


        foreach ($block['items'] as $item_key => $item) {
            if ($item['type'] == 'category') {
                $sql = "SELECT `Category Key`,`Category Label`,`Webpage URL`,`Webpage Code`,`Page Key`,`Category Code`,`Product Category Active Products`,`Category Main Image Key`
                        from `Product Category Dimension` P LEFT JOIN `Category Dimension` Cat ON (Cat.`Category Key`=P.`Product Category Key`)  LEFT JOIN `Page Store Dimension` CatWeb ON (CatWeb.`Page Key`=`Product Category Webpage Key`)  
                        WHERE  `Product Category Key`=?  AND `Product Category Public`='Yes'  AND `Webpage State` IN ('Online','Ready')  ";


                $stmt = $this->db->prepare($sql);
                $stmt->execute(
                    array(
                        $item['category_key']
                    )
                );
                if ($row = $stmt->fetch()) {
                    $category = get_object('Category', $row['Category Key']);

                    $image_key = $category->data['Category Main Image Key'];


                    if ($block['auto'] == true) {
                        $content_data['blocks'][$block_key]['items'][$item_key]['header_text'] = $row['Category Label'];
                        if ($image_key) {
                            $image_src = '/wi.php?id='.$image_key;
                        } else {
                            $image_src = '/art/nopic.png';
                        }
                    } else {
                        if ($content_data['blocks'][$block_key]['items'][$item_key]['image_src'] == '/art/nopic.png') {
                            $image_src = '/wi.php?id='.$image_key;
                        } else {
                            $image_src = $content_data['blocks'][$block_key]['items'][$item_key]['image_src'];
                        }
                    }


                    if ($image_src != $content_data['blocks'][$block_key]['items'][$item_key]['image_src']) {
                        $content_data['blocks'][$block_key]['items'][$item_key]['image_src']            = $image_src;
                        $content_data['blocks'][$block_key]['items'][$item_key]['image_mobile_website'] = '';
                        $content_data['blocks'][$block_key]['items'][$item_key]['image_website']        = '';
                    }


                    $content_data['blocks'][$block_key]['items'][$item_key]['category_code']   = $row['Webpage URL'];
                    $content_data['blocks'][$block_key]['items'][$item_key]['number_products'] = $row['Product Category Active Products'];


                    $content_data['blocks'][$block_key]['items'][$item_key]['link']         = $row['Webpage URL'];
                    $content_data['blocks'][$block_key]['items'][$item_key]['webpage_code'] = $row['Webpage Code'];
                    $content_data['blocks'][$block_key]['items'][$item_key]['webpage_key']  = $row['Page Key'];
                } else {
                    unset($content_data['blocks'][$block_key]['items'][$item_key]);
                }
            } elseif ($item['type'] == 'product') {
                $sql = "SELECT `Product Web State`,`Product Customer Key` FROM `Product Dimension` WHERE `Product ID`=?";

                $stmt = $this->db->prepare($sql);
                $stmt->execute(
                    array(
                        $item['product_id']
                    )
                );
                if ($row = $stmt->fetch()) {
                    if ($row['Product Web State'] == 'For Sale' or $row['Product Web State'] == 'Out of Stock' and $row['Product Customer Key'] == '') {
                        $product = get_object('Public_Product', $item['product_id']);
                        $product->load_webpage();

                        $image_src = $product->get('Image');

                        if ($block['auto'] == true) {
                            $content_data['blocks'][$block_key]['items'][$item_key]['header_text'] = $product->get('Name');
                        } else {
                            if ($content_data['blocks'][$block_key]['items'][$item_key]['image_src'] != '/art/nopic.png') {
                                $image_src = $content_data['blocks'][$block_key]['items'][$item_key]['image_src'];
                            }
                        }

                        if ($image_src != $content_data['blocks'][$block_key]['items'][$item_key]['image_src']) {
                            $content_data['blocks'][$block_key]['items'][$item_key]['image_src'] = $image_src;

                            $content_data['blocks'][$block_key]['items'][$item_key]['image_mobile_website'] = '';
                            $content_data['blocks'][$block_key]['items'][$item_key]['image_website']        = '';
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

                            'header_text'   => $category->get('Category Label'),
                            'image_caption' => trim(strip_tags($category->get('Category Label'))),

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
        $sql = sprintf('DELETE FROM `Website Webpage Scope Map` WHERE `Website Webpage Scope Webpage Key`=%d  AND `Website Webpage Block Index`=%d ', $this->id, $block_index);
        $this->db->exec($sql);

        $index = 0;
        foreach ($content_data['blocks'][$block_key]['items'] as $item) {
            //  print_r($item);


            $sql = sprintf(
                'INSERT INTO `Website Webpage Scope Map` (`Website Webpage Block Index`,`Website Webpage Scope Scope Webpage Key`,`Website Webpage Scope Website Key`,`Website Webpage Scope Webpage Key`,`Website Webpage Scope Scope`,`Website Webpage Scope Scope Key`,`Website Webpage Scope Type`,`Website Webpage Scope Index`) VALUES (%d,%d,%d,%d,%s,%d,%s,%d) ',
                $block_index,
                $item['webpage_key'],
                $this->get('Webpage Website Key'),
                $this->id,
                prepare_mysql(capitalize($item['type'])),
                ($item['type'] == 'category' ? $item['category_key'] : $item['product_id']),
                prepare_mysql('See_Also_'.capitalize($item['type']).'_'.($block['auto'] ? 'Auto' : 'Manual')),
                $index

            );
            //print "$sql\n";

            $this->db->exec($sql);
            $index++;
        }
    }

    function reindex_webpage_scope_map()
    {
        $index = 0;
        $sql   = 'select `Website Webpage Scope Key` FROM `Website Webpage Scope Map` WHERE `Website Webpage Scope Webpage Key`=?  order by `Website Webpage Block Index`,`Website Webpage Scope Index`  ';
        $stmt  = $this->db->prepare($sql);
        $stmt->execute(
            array($this->id)
        );

        while ($row = $stmt->fetch()) {
            $index++;
            $sql = 'update `Website Webpage Scope Map` set `Website Webpage Scope Webpage Index`=? where `Website Webpage Scope Key`=? ';


            $this->db->prepare($sql)->execute(
                [
                    $index,
                    $row['Website Webpage Scope Key']
                ]
            );
        }
    }

    function reindex()
    {
        $this->reindex_items();
        $this->update_navigation();


        if ($this->get('Webpage Scope') == 'Top_Up') {
            $store = get_object('Store', $this->data['Webpage Store Key']);

            $content_data = $this->get('Content Data');


            foreach ($content_data['blocks'] as $block_key => $block) {
                if ($block['type'] == 'top_up') {
                    if (empty($content_data['blocks'][$block_key]['options'])) {
                        $content_data['blocks'][$block_key]['options'] = [
                            [
                                'value'           => 20,
                                'formatted_value' => money(20, $store->get('Store Currency Code'), false, 'NO_FRACTION_DIGITS')
                            ],
                            [
                                'value'           => 50,
                                'formatted_value' => money(50, $store->get('Store Currency Code'), false, 'NO_FRACTION_DIGITS')
                            ],
                            [
                                'value'           => 100,
                                'formatted_value' => money(100, $store->get('Store Currency Code'), false, 'NO_FRACTION_DIGITS')
                            ],
                            [
                                'value'           => 250,
                                'formatted_value' => money(250, $store->get('Store Currency Code'), false, 'NO_FRACTION_DIGITS')
                            ],
                        ];
                    } else {
                        foreach ($content_data['blocks'][$block_key]['options'] as $i => $option) {
                            if (isset($option['value'])) {
                                $content_data['blocks'][$block_key]['options'][$i]['formatted_value'] = money($option['value'], $store->get('Store Currency Code'), false, 'NO_FRACTION_DIGITS');
                            }
                        }
                    }
                }
            }


            $this->update_field_switcher('Page Store Content Data', json_encode($content_data), 'no_history');
        }
    }

    function publish($note = '')
    {
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


        $sql = sprintf(
            "UPDATE `Page Store Dimension` SET  `Webpage Last Launch Date`='%s'  WHERE `Page Key`=%d",
            gmdate('Y-m-d H:i:s'),
            $this->id
        );

        $this->db->exec($sql);


        // $this->fast_update(array('Webpage Last Launch Date' => gmdate('Y-m-d H:i:s')));


        if ($this->get('Webpage Launch Date') == '') {
            $this->fast_update(array('Webpage Launch Date' => gmdate('Y-m-d H:i:s')));
            $msg              = _('Webpage launched');
            $publish_products = true;
        } else {
            $msg              = _('Webpage published');
            $publish_products = false;
        }


        $content_data = $this->get('Content Data');


        $sql = sprintf(
            'UPDATE `Page Store Dimension` SET  `Page Store Content Published Data`=`Page Store Content Data`  WHERE `Page Key`=%d ',
            $this->id
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
            "INSERT INTO `Webpage Publishing History Bridge` VALUES (%d,%d,'No','No','Deployment')",
            $this->id,
            $history_key
        );


        $this->db->exec($sql);


        if ($this->get('Webpage Scope') == 'Category Products') {
            /** @var \Category $category */
            $category = get_object('Category', $this->get('Webpage Scope Key'));

            if ($category->get('Product Category Department Category Key')) {
                $parent = get_object('Category', $category->get('Product Category Department Category Key'));
                /** @var  $parent_webpage \Page */
                $parent_webpage = get_object('Webpage', $parent->get('Product Category Webpage Key'));
                $parent_webpage->reindex_items();
            }


            $web_text = '';

            if (isset($content_data['blocks']) and is_array($content_data['blocks'])) {
                foreach ($content_data['blocks'] as $block) {
                    if ($block['type'] == 'blackboard' and $block['show']) {
                        if (isset($block['texts'])) {
                            foreach ($block['texts'] as $text) {
                                $web_text .= $text['text'].' ';
                            }
                        }
                    }
                }
            }

            $category->fast_update(array('Product Category Published Webpage Description' => $web_text), 'Product Category Dimension');


            if ($publish_products) {
                include_once 'class.Page.php';
                $sql = sprintf('SELECT `Product Category Index Product ID` FROM `Product Category Index`    WHERE `Product Category Index Website Key`=%d', $this->id);


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $webpage = new Page('scope', 'Product', $row['Product Category Index Product ID']);

                        if ($webpage->id) {
                            $webpage->publish();
                        }
                    }
                }
            }
        } elseif ($this->get('Webpage Scope') == 'Product') {
            $web_text = '';

            if (isset($content_data['blocks']) and is_array($content_data['blocks'])) {
                foreach ($content_data['blocks'] as $block) {
                    if ($block['type'] == 'product') {
                        $web_text .= $block['text'];
                    }
                }
            }

            $product = get_object('Product', $this->get('Webpage Scope Key'));
            $product->fast_update(array('Product Published Webpage Description' => $web_text));

            if ($product->get('Product Family Category Key')) {
                $family         = get_object('Category', $product->get('Product Family Category Key'));
                $family_webpage = get_object('Webpage', $family->get('Product Category Webpage Key'));
                if ($family_webpage->id) {
                    $family_webpage->reindex_items();
                }
            }
        }


        $this->get_data('id', $this->id);

        require_once 'utils/new_fork.php';
        new_housekeeping_fork(
            'au_housekeeping',
            array(
                'type'        => 'webpage_published',
                'webpage_key' => $this->id,
            ),
            DNS_ACCOUNT_CODE,
            $this->db
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

        $this->model_published($this->id);

    }

    function reindex_category_categories($block_index)
    {
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
            "SELECT  `Image File Format`,`Webpage URL`,`Category Main Image Key`,`Category Main Image`,`Category Label`,`Webpage State`,`Product Category Public`,`Webpage State`,`Page Key`,`Webpage Code`,`Product Category Active Products`,`Category Code`,Cat.`Category Key` 
                FROM    `Category Bridge` B  LEFT JOIN     `Product Category Dimension` P   ON (`Subject Key`=`Product Category Key` AND `Subject`='Category' )    
                        LEFT JOIN `Category Dimension` Cat ON (Cat.`Category Key`=P.`Product Category Key`) 
                        LEFT JOIN `Page Store Dimension` CatWeb ON (CatWeb.`Page Key`=`Product Category Webpage Key`)  
                        LEFT JOIN `Image Dimension` I ON (`Category Main Image Key`=I.`Image Key`) 
                        WHERE  B.`Category Key`=%d  AND `Product Category Public`='Yes'  AND `Webpage State` IN ('Online','Ready')  ORDER BY  `Category Label` DESC   ",
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
        }

        $offline_items_category_key_index = array();
        $sql                              = sprintf(
            "SELECT  B.`Category Key` FROM `Category Bridge` B  LEFT JOIN `Product Category Dimension` P   ON (`Subject Key`=`Product Category Key` AND `Subject`='Category' )    LEFT JOIN `Category Dimension` Cat ON (Cat.`Category Key`=P.`Product Category Key`) LEFT JOIN `Page Store Dimension` CatWeb ON (CatWeb.`Page Key`=`Product Category Webpage Key`)  
            WHERE  B.`Category Key`=%d  AND  (`Product Category Public`='No'  OR `Webpage State` NOT IN ('Online','Ready')  )  ",
            $this->get('Webpage Scope Key')


        );
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $offline_items_category_key_index[$row['Category Key']] = $row['Category Key'];
            }
        }

        $anchor_section_key = 0;

        foreach ($block['sections'] as $section_key => $section) {
            if (isset($section['type']) and $section['type'] == 'anchor') {
                $anchor_section_key = $section_key;
            }

            if (isset($section['items']) and is_array($section['items'])) {
                foreach ($section['items'] as $item_key => $item) {
                    if ($item['type'] == 'category') {
                        if (in_array($item['category_key'], $items_category_key_index)) {
                            // print_r($content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]);


                            $item_data = $items[$item['category_key']];

                            //print_r($item_data);

                            $content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['item_type']       = 'Subject';
                            $content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['webpage_key']     = $item_data['Page Key'];
                            $content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['webpage_code']    = $item_data['Webpage Code'];
                            $content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['category_code']   = $item_data['Category Code'];
                            $content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['number_products'] = $item_data['Product Category Active Products'];
                            $content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['link']            = $item_data['Webpage URL'];

                            //$content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['image_src']            = 'wi/'.$item_data['Category Main Image Key'].'.'.$item_data['Image File Format'];
                            //$content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['image_website']        = 'wi/'.$item_data['Category Main Image Key'].'.'.$item_data['Image File Format'];
                            //$content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['image_mobile_website'] = 'wi/'.$item_data['Category Main Image Key'].'.'.$item_data['Image File Format'];


                            // $content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]['image_src']            = $item_data['Webpage URL'];


                            unset($items_category_key_index[$item['category_key']]);
                        } else {
                            if (in_array($item['category_key'], $offline_items_category_key_index)) {
                                unset($content_data['blocks'][$block_key]['sections'][$section_key]['items'][$item_key]);
                            } else {
                                $sql = sprintf(
                                    "SELECT  `Webpage URL`,`Category Main Image Key`,`Category Main Image`,`Category Label`,`Webpage State`,`Product Category Public`,`Webpage State`,`Page Key`,`Webpage Code`,`Product Category Active Products`,`Category Code`,Cat.`Category Key` 
                                        FROM   `Product Category Dimension` P     
                                      LEFT JOIN `Category Dimension` Cat ON (Cat.`Category Key`=P.`Product Category Key`) 
                                      LEFT JOIN `Page Store Dimension` CatWeb ON (CatWeb.`Page Key`=`Product Category Webpage Key`)  
                                  WHERE  `Product Category Key`=%d  AND `Product Category Public`='Yes'  AND `Webpage State` IN ('Online','Ready')    ",
                                    $item['category_key']


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
                                }
                            }
                        }
                    }
                }
            }
        }


        foreach ($items_category_key_index as $index) {
            $item_data = $items[$index];

            if ($item_data['Category Main Image Key'] > 0) {
                if ($item_data['Category Main Image Key'] != '') {
                    $img = 'wi/'.$item_data['Category Main Image Key'].'.'.$item_data['Image File Format'];
                } else {
                    $img = 'wi.php?id='.$item_data['Category Main Image Key'];
                }
            } else {
                $img = '/art/nopic.png';
            }

            $item = array(
                'type'                 => 'category',
                'category_key'         => $item_data['Category Key'],
                'header_text'          => trim(strip_tags($item_data['Category Label'])),
                'image_caption'        => trim(strip_tags($item_data['Category Label'])),
                'image_src'            => $img,
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

        // print_r($content_data);

        $this->update_field_switcher('Page Store Content Data', json_encode($content_data), 'no_history');

        $sql = sprintf("DELETE FROM `Website Webpage Scope Map` WHERE `Website Webpage Scope Webpage Key`=%d AND `Website Webpage Scope Type` IN ('Subject','Guest')  ", $this->id);
        $this->db->exec($sql);

        $index = 0;
        foreach ($content_data['blocks'][$block_key]['sections'] as $section_key => $section) {
            if (isset($section['items'])) {
                foreach ($section['items'] as $item_key => $item) {
                    if ($item['type'] == 'category') {
                        $sql = sprintf(
                            'INSERT INTO `Website Webpage Scope Map` (`Website Webpage Block Index`,`Website Webpage Scope Scope Webpage Key`,`Website Webpage Scope Website Key`,`Website Webpage Scope Webpage Key`,`Website Webpage Scope Scope`,`Website Webpage Scope Scope Key`,`Website Webpage Scope Type`,`Website Webpage Scope Index`) VALUES (%d,%d,%d,%d,%s,%d,%s,%d) ',
                            $block_index,
                            $item['webpage_key'],
                            $this->get('Webpage Website Key'),
                            $this->id,
                            prepare_mysql('Category'),
                            $item['category_key'],
                            prepare_mysql($item['item_type']),
                            $index

                        );


                        $this->db->exec($sql);
                        $index++;
                    }
                }
            }
        }
    }

    function reindex_products($block_key, $block_index)
    {
        $content_data = $this->get('Content Data');


        $block = $content_data['blocks'][$block_key];

        foreach ($block['items'] as $item_key => $item) {
            $sql = sprintf('SELECT `Product Web State` FROM `Product Dimension` WHERE `Product ID`=%d', $item['product_id']);
            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    if ($row['Product Web State'] == 'For Sale' or $row['Product Web State'] == 'Out of Stock') {
                        $product = get_object('Public_Product', $item['product_id']);
                        $product->load_webpage();


                        $content_data['blocks'][$block_key]['items'][$item_key]['web_state']      = $product->get('Web State');
                        $content_data['blocks'][$block_key]['items'][$item_key]['price']          = $product->get('Price');
                        $content_data['blocks'][$block_key]['items'][$item_key]['price_unit']     = $product->get('Price Per Unit');
                        $content_data['blocks'][$block_key]['items'][$item_key]['price_unit_bis'] = $product->get('Price Per Unit Bis');
                        $content_data['blocks'][$block_key]['items'][$item_key]['variants']       = $product->get_variants_data();

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
                        $content_data['blocks'][$block_key]['items'][$item_key]['number_visible_variants'] = $product->get('number_visible_variants');
                        $content_data['blocks'][$block_key]['items'][$item_key]['family_key']              = $product->get('Product Family Category Key');
                    } else {
                        unset($content_data['blocks'][$block_key]['items'][$item_key]);
                    }
                } else {
                    unset($content_data['blocks'][$block_key]['items'][$item_key]);
                }
            }
        }

        $this->update_field_switcher('Page Store Content Data', json_encode($content_data), 'no_history');
        $sql = sprintf('DELETE FROM `Website Webpage Scope Map` WHERE `Website Webpage Scope Webpage Key`=%d  AND `Website Webpage Scope Type`="Products_Item" ', $this->id);
        $this->db->exec($sql);

        $index = 0;
        foreach ($content_data['blocks'][$block_key]['items'] as $item) {
            $sql = sprintf(
                'INSERT INTO `Website Webpage Scope Map` (`Website Webpage Block Index`,`Website Webpage Scope Scope Webpage Key`,`Website Webpage Scope Website Key`,`Website Webpage Scope Webpage Key`,`Website Webpage Scope Scope`,`Website Webpage Scope Scope Key`,`Website Webpage Scope Type`,`Website Webpage Scope Index`) VALUES (%d,%d,%d,%d,%s,%d,%s,%d) ',
                $block_index,
                $item['webpage_key'],
                $this->get('Webpage Website Key'),
                $this->id,
                prepare_mysql('Product'),
                $item['product_id'],
                prepare_mysql('Products_Item'),
                $index

            );
            // print "$sql\n";

            $this->db->exec($sql);
            $index++;
        }
    }

    function reindex_product($block_index)
    {
        $content_data = $this->get('Content Data');
        $block_found  = false;
        foreach ($content_data['blocks'] as $_block_key => $_block) {
            if ($_block['type'] == 'product') {
                // $block       = $_block;
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

        // print_r($image_data);

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


        $sql = sprintf(
            'INSERT INTO `Website Webpage Scope Map` (`Website Webpage Block Index`,`Website Webpage Scope Scope Webpage Key`,`Website Webpage Scope Website Key`,`Website Webpage Scope Webpage Key`,`Website Webpage Scope Scope`,`Website Webpage Scope Scope Key`,`Website Webpage Scope Type`,`Website Webpage Scope Index`) VALUES (%d,%d,%d,%d,%s,%d,%s,%d) ',
            $block_index,
            $this->id,
            $this->get('Webpage Website Key'),
            $this->id,
            prepare_mysql('Product'),
            $product->id,
            prepare_mysql('Product_Main_Webpage'),
            0

        );
        // print "$sql\n";

        $this->db->exec($sql);

        //print $product->get('Code');
        //print_r($content_data);

        $this->update_field_switcher('Page Store Content Data', json_encode($content_data), 'no_history');
    }

    function get_category_children_webpage_keys()
    {
        $webpage_keys = array();

        switch ($this->get('Webpage Scope')) {
            case 'Category Products':


                $sql  = "select `Product Webpage Key` from `Category Bridge` left join `Product Dimension` on (`Subject Key`=`Product ID`) where `Category Key`=?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(
                    array(
                        $this->data['Webpage Scope Key']
                    )
                );
                while ($row = $stmt->fetch()) {
                    if ($row['Product Webpage Key'] > 0) {
                        $webpage_keys[$row['Product Webpage Key']] = $row['Product Webpage Key'];
                    }
                }
                break;

            case 'Category Categories':


                $sql  = "select `Product Category Webpage Key`,C.`Product Category Key` from `Category Bridge` B left join `Product Category Dimension` C on (`Subject Key`=C.`Product Category Key`) where B.`Category Key`=?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(
                    array(
                        $this->data['Webpage Scope Key']
                    )
                );
                while ($row = $stmt->fetch()) {
                    if ($row['Product Category Webpage Key'] > 0) {
                        $webpage_keys[$row['Product Category Webpage Key']] = $row['Product Category Webpage Key'];
                    }

                    $_webpage = get_object('Webpage', $row['Product Category Webpage Key']);

                    if ($_webpage->get('Webpage Scope') == 'Category Products') {
                        $webpage_keys = array_merge($webpage_keys, $_webpage->get_category_children_webpage_keys());
                    }
                }
                break;
        }

        return $webpage_keys;
    }

    function delete($create_deleted_page_record = true)
    {
        $sql = sprintf('delete `Product Category Index` where `Product Category Index Website Key`=%d  ', $this->id);
        $this->db->exec($sql);


        $sql = sprintf('delete `Webpage Section Dimension` where `Webpage Section Webpage Key`=%d  ', $this->id);
        $this->db->exec($sql);


        $this->deleted = false;


        $sql = sprintf(
            "DELETE FROM `Page Store Dimension` WHERE `Page Key`=%d",
            $this->id
        );
        $this->db->exec($sql);
        $sql = sprintf(
            "DELETE FROM `Page Redirection Dimension` WHERE `Page Target Key`=%d",
            $this->id
        );
        $this->db->exec($sql);


        $sql = sprintf(
            "INSERT INTO `Page State Timeline`  (`Page Key`,`Website Key`,`Store Key`,`Date`,`State`,`Operation`) VALUES (%d,%d,%d,%s,'Offline','Deleted') ",
            $this->id,
            $this->data['Webpage Website Key'],
            $this->data['Webpage Store Key'],
            prepare_mysql(gmdate('Y-m-d H:i:s'))

        );
        $this->db->exec($sql);


        $images = array();
        $sql    = sprintf(
            "SELECT `Image Subject Image Key` FROM  `Image Subject Bridge` WHERE `Image Subject Object`='Webpage' AND `Image Subject Object Key`=%d",
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $images[] = $row['Image Subject Image Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf(
            "DELETE FROM  `Image Subject Bridge` WHERE `Image Subject Object`='Webpage' AND `Image Subject Object Key`=%d",
            $this->id
        );

        $this->db->exec($sql);

        foreach ($images as $image_key) {
            $image = get_object('Image', $image_key);
            $image->delete();
        }

        //todo Urgent redo see also


        $this->deleted = true;


        if (array_key_exists('Webpage Website Key', $this->data)) {
            $website = get_object('website', $this->data['Webpage Website Key']);
            $website->update_website_webpages_data();
        }


        if ($create_deleted_page_record) {
            $deleted_metadata = gzcompress(json_encode($this->data), 9);


            include_once 'class.PageDeleted.php';
            $data = array(
                'Page Code'                   => $this->data['Webpage Code'],
                'Page Key'                    => $this->id,
                'Website Key'                 => $this->data['Webpage Website Key'],
                'Store Key'                   => $this->data['Webpage Store Key'],
                'Page Store Section'          => '',
                'Page Parent Key'             => $this->data['Webpage Scope Key'],
                'Page Parent Code'            => '',
                'Page Title'                  => $this->data['Webpage Name'],
                'Page Description'            => $this->data['Webpage Meta Description'],
                'Page URL'                    => $this->data['Webpage URL'],
                'Page Valid To'               => gmdate('Y-m-d H:i:s'),
                'Page Store Deleted Metadata' => $deleted_metadata


            );

            $deleted_page = new PageDeleted();
            $deleted_page->create($data);


            $abstract = sprintf(
                _('Webpage %s deleted'),
                sprintf(
                    '<span class="button" onClick="change_view(\'webpage/%d\')">%s</span>',
                    $this->id,
                    $this->data['Webpage Code']
                )
            );


            $history_data = array(
                'History Abstract' => $abstract,
                'History Details'  => '',
                'Action'           => 'deleted'
            );
            $this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id);


            $webpage_type = get_object('Webpage_Type', $this->get('Webpage Type Key'));
            $webpage_type->update_number_webpages();


            $this->new_value = $deleted_page->id;
        }
        $this->deleted = true;

        $account = get_object('Account', 1);

        $redis = new Redis();
        if ($redis->connect(REDIS_HOST, REDIS_PORT)) {
            $cache_id_prefix = 'pwc3|'.$account->get('Code').'|'.$this->get('Webpage Website Key').'_';

            $redis->del($cache_id_prefix.$this->data['Webpage Code']);
            $redis->del($cache_id_prefix.strtolower($this->data['Webpage Code']));
            $redis->del($cache_id_prefix.strtoupper($this->data['Webpage Code']));
            $redis->del($cache_id_prefix.ucfirst($this->data['Webpage Code']));

            include_once 'utils/string_functions.php';
            foreach (permutation_letter_case($this->data['Webpage Code']) as $permutation) {
                $redis->del($cache_id_prefix.$permutation);
            }
        }

        $this->fork_index_elastic_search('delete_elastic_index_object');
    }

    function get_field_label($field)
    {
        switch ($field) {
            case 'Webpage Code':
                $label = _('code');
                break;
            case 'Webpage Name':
                $label = _('title (name)');
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

            // case 'Webpage Browser Title':
            //     $label = _('browser title');
            //     break;
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

    function update_analytics($interval, $this_period = true, $last_period = true)
    {
        include_once 'utils/date_functions.php';
        list($db_interval, $from_date, $to_date, $from_date_1yb, $to_date_1yb, $from_prev_period, $to_prev_period) = calculate_interval_dates($this->db, $interval);


        if ($this_period) {
            $analytics_data = $this->get_analytics_data($from_date, $to_date);


            $data_to_update = array(
                "Webpage Analytics $db_interval Acc Pageviews"        => $analytics_data['pageviews'],
                "Webpage Analytics $db_interval Acc RUsers Pageviews" => $analytics_data['pageviews_registered_users'],
                "Webpage Analytics $db_interval Acc Page Value"       => round($analytics_data['page_value'], 2),
                "Webpage Analytics $db_interval Acc Users"            => $analytics_data['users'],
                "Webpage Analytics $db_interval Acc RUsers"           => $analytics_data['registered_users'],
                "Webpage Analytics $db_interval Acc Sessions"         => $analytics_data['sessions'],
                "Webpage Analytics $db_interval Acc RUsers Sessions"  => $analytics_data['sessions_registered_users'],
                "Webpage Analytics $db_interval Acc SC Impressions"   => $analytics_data['impressions'],
                "Webpage Analytics $db_interval Acc SC Clicks"        => $analytics_data['clicks'],
                "Webpage Analytics $db_interval Acc SC CTR"           => $analytics_data['ctr'],
                "Webpage Analytics $db_interval Acc SC Position"      => $analytics_data['position'],


            );


            $this->fast_update($data_to_update, 'Webpage Analytics Data');
        }

        if ($from_prev_period and $last_period) {
            $analytics_data = $this->get_analytics_data($from_prev_period, $to_prev_period);

            $data_to_update = array(
                "Webpage Analytics $db_interval Acc Pageviews"        => $analytics_data['pageviews'],
                "Webpage Analytics $db_interval Acc RUsers Pageviews" => $analytics_data['pageviews_registered_users'],
                "Webpage Analytics $db_interval Acc Page Value"       => round($analytics_data['page_value'], 2),
                "Webpage Analytics $db_interval Acc Users"            => $analytics_data['users'],
                "Webpage Analytics $db_interval Acc RUsers"           => $analytics_data['registered_users'],
                "Webpage Analytics $db_interval Acc Sessions"         => $analytics_data['sessions'],
                "Webpage Analytics $db_interval Acc RUsers Sessions"  => $analytics_data['sessions_registered_users'],
                "Webpage Analytics $db_interval Acc SC Impressions"   => $analytics_data['impressions'],
                "Webpage Analytics $db_interval Acc SC Clicks"        => $analytics_data['clicks'],
                "Webpage Analytics $db_interval Acc SC CTR"           => $analytics_data['ctr'],
                "Webpage Analytics $db_interval Acc SC Position"      => $analytics_data['position'],


            );


            $this->fast_update($data_to_update, 'Webpage Analytics Data');
        }
    }

    function get_analytics_data($from, $to)
    {
        include_once('utils/google_api_functions.php');
        list($url_base, $url_suffix) = preg_split('/\//', $this->get('URL'));

        try {
            $analytics_data = get_analytics_data($url_base, $url_suffix, $from, $to);
        } catch (Exception $e) {
            // echo 'Caught exception: ',  $e->getMessage(), "\n";
            $analytics_data = array(
                'pageviews'                  => 0,
                'pageviews_registered_users' => 0,
                'page_value'                 => 0,
                'users'                      => 0,
                'registered_users'           => 0,
                'sessions'                   => 0,
                'sessions_registered_users'  => 0,
                'impressions'                => 0,
                'clicks'                     => 0,
                'ctr'                        => 0,
                'position'                   => 0,


            );
        }


        return $analytics_data;
    }

    function update_screenshots($device = 'Desktop', $type = '')
    {
        if (in_array(
            $this->get('Website Code'), array(
                'reset_pwd.sys',
                'profile.sys',
                'basket.sys',
                'checkout.sys',
                'thanks.sys'
            )
        )) {
            return;
        }

        if ($this->get('Webpage URL') == '') {
            return;
        }


        $url = $this->get('Webpage URL').'?snapshot='.md5(VKEY.'||'.date('Ymd'));


        if (!($this->get('Website Code') == 'home_logout.sys' or $this->get('Website Code') == 'register.sys')) {
            $url .= '&logged_in=1';
        }


        include 'keyring/screenshots.dns.php';

        $curl  = curl_init();
        $route = '/'.$device;
        if ($type != '') {
            $route .= '/'.$type;
        }

        curl_setopt_array(
            $curl, array(
                CURLOPT_HEADER         => 1,
                CURLOPT_URL            => SCREENSHOTS_API_URL."/take$route?url=$url",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "GET",
                CURLOPT_HTTPHEADER     => array(
                    "Accept: */*",
                    "Accept-Encoding: gzip, deflate",
                    "Authorization: Bearer ".SCREENSHOTS_JWT,
                    "Cache-Control: no-cache",
                    "Connection: keep-alive",
                    "cache-control: no-cache"
                ),
            )
        );

        $response = curl_exec($curl);
        $err      = curl_error($curl);

        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $header      = substr($response, 0, $header_size);
        $body        = substr($response, $header_size);

        curl_close($curl);

        if (!$err) {
            if (preg_match('/Content-Type: image\/jpeg/', $header)) {
                $tmp_file = 'server_files/tmp/_screenshot_'.gmdate('U').'_'.md5($url).'.jpeg';

                if (file_put_contents($tmp_file, $body)) {
                    $scope = $device;
                    if ($type != '') {
                        $scope .= ' '.$type;
                    }
                    $scope .= ' Screenshot';

                    $image_data = array(
                        'Upload Data'                      => array(
                            'tmp_name' => $tmp_file,
                            'type'     => 'jpeg'
                        ),
                        'Image Filename'                   => $tmp_file,
                        'Image Subject Object Image Scope' => $scope


                    );

                    $image = $this->add_image($image_data, 'no_history');


                    $_scope = strtolower(preg_replace('/\s/', '_', $scope));

                    $old_screenshot_image_key = $this->properties($_scope);

                    $this->update(
                        array(
                            $_scope => $image->id,
                        ),
                        'no_history'
                    );


                    if ($old_screenshot_image_key != $image->id) {
                        $sql  = "select `Image Subject Key`  from `Image Subject Bridge` where `Image Subject Image Key`=? and `Image Subject Object`='Webpage' and `Image Subject Object Key`=? and `Image Subject Object Image Scope`=? ";
                        $stmt = $this->db->prepare($sql);
                        $stmt->execute(
                            array(
                                $old_screenshot_image_key,
                                $this->id,
                                $scope
                            )
                        );
                        if ($row = $stmt->fetch()) {
                            $this->delete_image($row['Image Subject Key']);
                        }
                    }


                    if (file_exists($tmp_file)) {
                        unlink($tmp_file);
                    }
                }
            }
        }
    }

    function get_upstream_webpage_keys()
    {
        $webpage_keys = array();

        $sql = sprintf(
            "select `Website Webpage Scope Webpage Key`  from `Website Webpage Scope Map` where  `Website Webpage Scope Scope Webpage Key`=%d ",
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Website Webpage Scope Webpage Key']) {
                    $webpage_keys[$row['Website Webpage Scope Webpage Key']] = $row['Website Webpage Scope Webpage Key'];
                }
            }
        }

        return $webpage_keys;
    }

    function get_downstream_webpage_keys()
    {
        $webpage_keys = array();

        $sql = sprintf(
            "select `Website Webpage Scope Scope Webpage Key`  from `Website Webpage Scope Map` where  `Website Webpage Scope Webpage Key`=%d ",
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Website Webpage Scope Webpage Key']) {
                    $webpage_keys[$row['Website Webpage Scope Scope Webpage Key']] = $row['Website Webpage Scope Scope Webpage Key'];
                }
            }
        }

        return $webpage_keys;
    }

}


