<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 May 2016 at 19:36:52 CEST, Mijas Costa, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/


include_once 'class.DB_Table.php';

class Website extends DB_Table {

    var $areas = false;
    var $locations = false;

    function Website($a1, $a2 = false, $a3 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Website';
        $this->ignore_fields = array('Website Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } elseif ($a1 == 'find') {
            $this->find($a2, $a3);

        } else {
            $this->get_data($a1, $a2);
        }
    }


    function get_data($key, $tag) {


        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Website Dimension` WHERE `Website Key`=%d", $tag
            );
        } else {
            if ($key == 'code') {
                $sql = sprintf(
                    "SELECT  * FROM `Website Dimension` WHERE `Website Code`=%s ", prepare_mysql($tag)
                );
            } else {
                return;
            }
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id   = $this->data['Website Key'];
            $this->code = $this->data['Website Code'];
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

        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }


        $data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $data[$key] = _trim($value);
            } elseif ($key == 'Website Key') {
                $data[$key] = _trim($value);
            }
        }


        if ($data['Website Code'] == '') {
            $this->error = true;
            $this->msg   = 'Website code empty';

            return;
        }

        if ($data['Website Name'] == '') {
            $data['Website Name'] = $data['Website Code'];
        }


        $sql = sprintf(
            "SELECT `Website Key` FROM `Website Dimension` WHERE `Website Code`=%s  ", prepare_mysql($data['Website Code'])
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $this->found     = true;
                $this->found_key = $row['Website Key'];
                $this->get_data('id', $this->found_key);
                $this->duplicated_field = 'Website Code';

                return;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }
        $sql = sprintf(
            "SELECT `Website Key` FROM `Website Dimension` WHERE `Website Name`=%s  ", prepare_mysql($data['Website Name'])
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $this->found     = true;
                $this->found_key = $row['Website Key'];
                $this->get_data('id', $this->found_key);
                $this->duplicated_field = 'Website Name';

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
        $base_data = $this->base_data();

        foreach ($data as $key => $value) {
            if (array_key_exists($key, $base_data)) {
                $base_data[$key] = _trim($value);
            } elseif ($key == 'Website Key') {
                $base_data[$key] = _trim($value);
            }
        }

        $keys   = '(';
        $values = 'values(';
        foreach ($base_data as $key => $value) {
            $keys .= "`$key`,";
            //   if (preg_match('/^()$/i', $key))
            //    $values.=prepare_mysql($value, false).",";
            //   else
            $values .= prepare_mysql($value).",";
        }
        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);
        $sql    = sprintf(
            "INSERT INTO `Website Dimension` %s %s", $keys, $values
        );


        if ($this->db->exec($sql)) {
            $this->id  = $this->db->lastInsertId();
            $this->msg = _("Website added");
            $this->get_data('id', $this->id);
            $this->new = true;


            $sql = "INSERT INTO `Website Data` (`Website Key`) VALUES(".$this->id.");";
            $this->db->exec($sql);


            require_once 'conf/footer_data.php';
            require_once 'conf/header_data.php';


            $footer_data = array(
                'Website Footer Code' => 'default',
                'Website Footer Data' => json_encode(get_default_footer_data(1)),
                'editor'              => $this->editor

            );
            $this->create_footer($footer_data);


            $logo_image_key = $this->add_image(
                array(
                    'Image Filename'                   => 'website.logo.png',
                    'Upload Data'                      => array(
                        'tmp_name' => 'conf/website.logo.png',
                        'type'     => 'png'
                    ),
                    'Image Subject Object Image Scope' => json_encode(
                        array(
                            'scope'     => 'website_logo',
                            'scope_key' => $this->id

                        )
                    )

                )
            );


            $_header_data                   = get_default_header_data(1);
            $_header_data['logo_image_key'] = $logo_image_key;
            $header_data                    = array(
                'Website Header Code' => 'default',
                'Website Header Data' => json_encode($_header_data),
                'editor'              => $this->editor


            );
            $this->create_header($header_data);


            $this->setup_templates();


            include 'conf/webpage_types.php';
            foreach ($webpage_types as $webpage_type) {
                $sql = sprintf(
                    'INSERT INTO `Webpage Type Dimension` (`Webpage Type Website Key`,`Webpage Type Code`) VALUES (%d,%s) ', $this->id, prepare_mysql($webpage_type['code'])
                );
                $this->db->exec($sql);
            }

            include_once 'conf/website_system_webpages.php';
            foreach (website_system_webpages_config($this->get('Website Type')) as $website_system_webpages) {
                $this->create_system_webpage($website_system_webpages);
            }


            if (is_numeric($this->editor['User Key']) and $this->editor['User Key'] > 1) {

                $sql = sprintf("INSERT INTO `User Right Scope Bridge` VALUES(%d,'Website',%d)", $this->editor['User Key'], $this->id);
                $this->db->exec($sql);

            }


            include_once 'class.Store.php';
            $store = new Store($this->get('Website Store Key'));

            $account         = new Account($this->db);
            $account->editor = $this->editor;

            $families_category_data = array(
                'Category Code'      => 'Web.Fam.'.$store->get('Store Code'),
                'Category Label'     => 'Web families',
                'Category Scope'     => 'Product',
                'Category Subject'   => 'Product',
                'Category Store Key' => $this->get('Website Store Key')


            );


            $families = $account->create_category($families_category_data);


            $departments_category_data = array(
                'Category Code'      => 'Web.Dept.'.$store->get('Store Code'),
                'Category Label'     => 'Web departments',
                'Category Scope'     => 'Product',
                'Category Subject'   => 'Category',
                'Category Store Key' => $this->get('Website Store Key')


            );


            $departments = $account->create_category($departments_category_data);

            $this->update(
                array(

                    'Website Alt Family Category Key'     => $families->id,
                    'Website Alt Department Category Key' => $departments->id,
                ), 'no_history'
            );


            return;
        } else {
            $this->msg = "Error can not create website";
            print $sql;
            exit;
        }
    }

    function create_footer($data) {

        include_once 'class.WebsiteFooter.php';

        if (!isset($data['Website Footer Code'])) {
            $this->error = true;
            $this->msg   = 'no footer code';

            return;
        }

        if ($data['Website Footer Code'] == '') {
            $this->error = true;
            $this->msg   = 'footer code empty';

            return;
        }

        $data['Website Footer Code'] = $this->get_unique_code($data['Website Footer Code'], 'Footer');

        $data['Website Footer Website Key'] = $this->id;


        $footer = new WebsiteFooter('find', $data, 'create');
        if (!$footer->id) {
            $this->error = true;
            $this->msg   = $footer->msg;

            return;
        }

        if (!$this->get('Website Footer Key')) {

            $this->update(
                array('Website Footer Key' => $footer->id), 'no_history'

            );

        }

    }

    function get_unique_code($code, $type) {


        for ($i = 1; $i <= 200; $i++) {

            if ($i == 1) {
                $suffix = '';
            } elseif ($i <= 100) {
                $suffix = $i;
            } else {
                $suffix = uniqid('', true);
            }

            if ($type == 'Webpage') {
                $sql = sprintf("SELECT `Page Key` FROM `Page Store Dimension`  WHERE `Webpage Website Key`=%d AND `Page Code`=%s  ", $this->id, prepare_mysql($code.$suffix));
            } elseif ($type == 'Footer') {
                $sql = sprintf(
                    "SELECT `Website Footer Key` FROM `Website Footer Dimension`  WHERE `Website Footer Website Key`=%d AND `Website Footer Code`=%s  ", $this->id, prepare_mysql($code.$suffix)
                );
            } elseif ($type == 'Header') {
                $sql = sprintf(
                    "SELECT `Website Header Key` FROM `Website Header Dimension`  WHERE `Website Header Website Key`=%d AND `Website Header Code`=%s  ", $this->id, prepare_mysql($code.$suffix)
                );
            } else {
                exit('error unknown type in get_unique_code ');
            }


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {

                } else {
                    return $code.$suffix;
                }
            }


        }

        return $suffix;
    }

    function get($key, $data = false) {

        if (!$this->id) {
            return '';
        }


        switch ($key) {
            case 'Palette':

                return '<img style="width:150px;height:150px;" src="/'.$this->data['Website Palette'].'"/>';

                break;

            case 'Localised Labels':

                if ($this->data['Website '.$key] == '') {
                    $labels = array();
                } else {
                    $labels = json_decode($this->data['Website '.$key], true);
                }

                return $labels;
                break;
            case 'Localised Labels':
            case 'Data':

                if ($this->data['Website '.$key] == '') {
                    $content_data = false;
                } else {
                    $content_data = json_decode($this->data['Website '.$key], true);
                }

                return $content_data;
                break;


            case 'Footer Data':
            case 'Footer Published Data':

                $sql = sprintf('SELECT `Website %s` AS data FROM `Website Footer Dimension` WHERE `Website Footer Key`=%d  ', $key, $this->get('Website Footer Key'));
                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        return json_decode($row['data'], true);
                    } else {
                        return false;
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }
                break;
            case 'Header Data':
            case 'Header Published Data':

                $sql = sprintf('SELECT `Website %s` AS data FROM `Website Header Dimension` WHERE `Website Header Key`=%d  ', $key, $this->get('Website Header Key'));
                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        return json_decode($row['data'], true);
                    } else {
                        return false;
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }
                break;


            default:


                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Website '.$key, $this->data)) {
                    return $this->data['Website '.$key];
                }


        }

        return '';
    }

    function add_image($raw_data, $options = false) {

        include_once 'class.Image.php';

        include_once 'utils/units_functions.php';


        $data = array(
            'Image Width'         => 0,
            'Image Height'        => 0,
            'Image File Size'     => 0,
            'Image File Checksum' => '',
            'Image Filename'      => $raw_data['Image Filename'],
            'Image File Format'   => '',
            'Image Data'          => '',

            'upload_data' => $raw_data['Upload Data'],
            'editor'      => $this->editor
        );

        if ($options) {
            $options = json_decode($options, true);
        }

        // print_r($data);
        // print_r($raw_data);
        // print_r($options);


        $scope_data = json_decode($raw_data['Image Subject Object Image Scope'], true);

        $image    = new Image('find', $data);
        $tmp_file = $data['upload_data']['tmp_name'];

        $image_format = $image->guess_file_format($tmp_file);
        $im           = $image->get_image_from_file($image_format, $tmp_file);

        $width  = imagesx($im);
        $height = imagesy($im);

        if (isset($options['max_width']) and is_numeric($options['max_width']) and $width > $options['max_width']) {


            $new_width  = $options['max_width'];
            $new_height = $height * $options['max_width'] / $width;

            $source = $im;
            $im     = imagecreatetruecolor($new_width, $new_height);


            imagecopyresized($im, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        }

        //  print_r($im);

        $sql = sprintf(
            "INSERT INTO `Website Image Dimension`  (`Website Image Website Key`,`Website Image Scope`,`Website Image Scope Key`,`Website Image Data`,`Website Image Date`,`Website Image Format`) VALUES (%d,%s,%s,%s,%s,%s) ",
            $this->id, prepare_mysql($scope_data['scope']), prepare_mysql($scope_data['scope_key'], true), "'".addslashes($image->get_image_blob($im, $image_format))."'",
            prepare_mysql(gmdate('Y-m-d H;i:s')), prepare_mysql($image_format)

        );
        $this->db->exec($sql);
        $image_key = $this->db->lastInsertId();


        return $image_key;

    }

    function create_header($data) {

        include_once 'class.WebsiteHeader.php';

        if (!isset($data['Website Header Code'])) {
            $this->error = true;
            $this->msg   = 'no header code';

            return;
        }

        if ($data['Website Header Code'] == '') {
            $this->error = true;
            $this->msg   = 'header code empty';

            return;
        }

        $data['Website Header Code'] = $this->get_unique_code($data['Website Header Code'], 'Header');

        $data['Website Header Website Key'] = $this->id;


        $header = new WebsiteHeader('find', $data, 'create');
        if (!$header->id) {
            $this->error = true;
            $this->msg   = $header->msg;

            return;
        }

        if (!$this->get('Website Header Key')) {

            $this->update(
                array('Website Header Key' => $header->id), 'no_history'

            );

        }

    }

    function setup_templates() {

        include_once('class.TemplateScope.php');
        include_once('class.Template.php');
        include_once('conf/website_templates.php');

        $templates = website_templates_config($this->get('Website Type'));

        //print_r($templates);

        foreach ($templates['templates'] as $template_code => $_template_data) {
            // print_r($_template_data);

            $template_scope_data = array(
                'Template Scope Website Key' => $this->id,
                'Template Scope Code'        => $_template_data['scope'],

                'editor' => $this->editor

            );

            $template_scope = new TemplateScope('find', $template_scope_data, 'create');


            $template_data = array(
                'Template Code'   => $template_code,
                'Template Base'   => 'Yes',
                'Template Device' => (isset($_template_data['device']) ? $_template_data['device'] : 'desktop'),
                'editor'          => $this->editor

            );

            $template_scope->create_template($template_data);

            // $template=new Template('find',$template_data,'create');

        }


    }

    function create_system_webpage($data) {

        include_once 'class.Webpage_Type.php';
        include_once 'class.Page.php';





        if(empty($data['Webpage Code'])){
            $this->error=true;
            $this->msg='Webpage code empty';
            return;
        }

        if(empty($data['Webpage Name'])){
            $this->error=true;
            $this->msg='Webpage name empty';
            return;
        }

        if(empty($data['Webpage Browser Title'])){
            $this->error=true;
            $this->msg='Webpage Browser Title empty';
            return;
        }

        if(empty($data['Webpage Meta Description'])){
            $data['Webpage Meta Description']='';
        }


        $webpage_type = new Webpage_Type('website_code', $this->id, $data['Webpage Type']);

        unset($data['Webpage Type']);


        $page_data = array(

            'Page Code'                            => $data['Webpage Code'],
            'Page URL'                             => $this->data['Website URL'].'/'.strtolower($data['Webpage Code']),
            'Page Site Key'                        => $this->id,
            'Page Type'                            => 'Store',
            'Page Store Key'                       => $this->get('Website Store Key'),
            'Page Store Creation Date'             => gmdate('Y-m-d H:i:s'),
            'Number See Also Links'                => 0,
            'Page Store Content Display Type'      =>'Template',
            'Page Store Content Template Filename' =>  $data['Webpage Template Filename'],
            'Page Title'                           => $data['Webpage Name'],
            'Page Short Title'                     => $data['Webpage Browser Title'],
            'Page Parent Key'                      => 0,
            'Page State'                           => 'Online',
            'Page Store Description'               => $data['Webpage Meta Description'],


            'Webpage Scope'                 => $data['Webpage Scope'],
            'Webpage Scope Key'             => '',
            'Webpage Website Key'           => $this->id,
            'Webpage Store Key'             => $this->get('Website Store Key'),
            'Webpage Type Key'              => $webpage_type->id,
            'Webpage Code'                  => $data['Webpage Code'],
            'Webpage Template Filename'     => $data['Webpage Template Filename'],
            'Webpage Number See Also Links' => 0,
            'Webpage Creation Date'         => gmdate('Y-m-d H:i:s'),
            'Webpage URL'                   => $this->data['Website URL'].'/'.strtolower($data['Webpage Code']),
            'Webpage Name'                  => $data['Webpage Name'],
            'Webpage Browser Title'         => $data['Webpage Browser Title'],
            'Webpage State'                 => ($data['Webpage Scope'] == 'HomepageToLaunch' ? 'Online' : 'InProcess'),
            'Webpage Meta Description'      => $data['Webpage Meta Description'],
            'Page Store Content Data'       => (isset($data['Page Store Content Data']) ? $data['Page Store Content Data'] : ''),


            'editor' => $this->editor,
        );


        $page = new Page('find', $page_data, 'create');


        $webpage_type->update_number_webpages();

        if ($data['Webpage Scope'] == 'HomepageToLaunch') {
            $page->publish();
        }
        $page->update_version();


        $this->new_page     = $page->new;
        $this->new_page_key = $page->id;
        $this->msg          = $page->msg;
        $this->error        = $page->error;


        return $page;

    }



    function update_labels_in_localised_labels($labels, $operation = 'append') {

        $localised_labels = $this->get('Localised Labels');
        switch ($operation) {
            case 'append':
                $localised_labels = array_merge($localised_labels, $labels);


        }


        $this->update(array('Website Localised Labels' => json_encode($localised_labels)), 'no_history');


    }
    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        if ($this->deleted) {
            return;
        }

        switch ($field) {
            default:
                $base_data = $this->base_data();
                if (array_key_exists($field, $base_data)) {
                    if ($value != $this->data[$field]) {
                        $this->update_field($field, $value, $options);
                    }
                }


        }
    }

    function update_webpages() {
        $sql             = sprintf(
            'SELECT count(*) AS number FROM `Webpage Dimension` WHERE `Webpage Website Key`=%d', $this->id
        );
        $number_webpages = 0;


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_webpages = $row['number'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $this->update(
            array('Website Number Pages' => $number_webpages), 'no_history'
        );


    }

    function get_field_label($field) {

        switch ($field) {

            case 'Website Code':
                $label = _('code');
                break;
            case 'Website Name':
                $label = _('name');
                break;
            case 'Website Address':
                $label = _('address');
                break;

            default:


                $label = $field;

        }

        return $label;

    }

    function get_webpage($code) {

        if ($code == '') {
            $code = 'p.home';
        }

        $webpage = new Webpage('website_code', $this->id, $code);

        return $webpage;


    }

    function get_default_template_key($scope, $device = 'Desktop') {

        $template_key = false;

        $sql = sprintf(
            'SELECT `Template Key` FROM `Template Dimension` WHERE `Template Website Key`=%d AND `Template Scope`=%s AND `Template Device`=%s ', $this->id, prepare_mysql($scope),
            prepare_mysql($device)

        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $template_key = $row['Template Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        if (!$template_key) {


            $sql = sprintf(
                'SELECT `Template Key` FROM `Template Dimension` WHERE `Template Website Key`=%d AND `Template Scope`=%s AND `Template Device`="Desktop" ', $this->id, prepare_mysql($scope)

            );
            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $template_key = $row['Template Key'];
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }

        }

        if (!$template_key) {


            $sql = sprintf(
                'SELECT `Template Key` FROM `Template Dimension` WHERE `Template Website Key`=%d AND `Template Scope`="Blank" AND `Template Device`=%s ', $this->id, prepare_mysql($scope)

            );
            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $template_key = $row['Template Key'];
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }

        }

        // print $template_key;


        return $template_key;

    }

    function create_category_webpage($category_key) {

        include_once 'class.Webpage_Type.php';
        include_once 'class.Page.php';
        include_once 'class.Category.php';

        $category = new Category($category_key);

        $sql = sprintf(
            "SELECT `Page Key` FROM `Page Store Dimension` WHERE `Webpage Scope`=%s AND `Webpage Scope Key`=%d  AND `Webpage Website Key`=%d ",
            prepare_mysql(($category->get('Category Subject') == 'Product' ? 'Category Products' : 'Category Categories')), $category_key, $this->id
        );

        //print "$sql\n";

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                return $row['Page Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        //-- to delete

        $sql = sprintf('SELECT `Site Default Header Key`,`Site Default Footer Key` FROM `Site Dimension` WHERE `Site Key`=%d', $this->id);
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $header_key = $row['Site Default Header Key'];
                $footer_key = $row['Site Default Footer Key'];
            } else {
                $header_key = 0;
                $footer_key = 0;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }
        //


        $page_code = $this->get_unique_code($category->get('Code'), 'Webpage');


        $webpage_type = new Webpage_Type('website_code', $this->id, ($category->get('Category Subject') == 'Product' ? 'Prods' : 'Cats'));


        if ($category->get('Category Subject') == 'Product') {

            $template = 'products_showcase';

        } else {
            $template = 'categories_showcase';

        }


        $page_data = array(
            'Page Code'                            => $page_code,
            'Page URL'                             => $this->data['Website URL'].'/'.strtolower($page_code),
            'Page Site Key'                        => $this->id,
            'Page Type'                            => 'Store',
            'Page Store Key'                       => $category->get('Category Store Key'),
            'Page Store Creation Date'             => gmdate('Y-m-d H:i:s'),
            'Number See Also Links'                => ($category->get('Category Subject') == 'Product' ? 5 : 0),
            'Page Store Content Display Type'      => 'Template',
            'Page Store Content Template Filename' => $template,


            'Webpage Scope'                 => ($category->get('Category Subject') == 'Product' ? 'Category Products' : 'Category Categories'),
            'Webpage Scope Key'             => $category->id,
            'Webpage Website Key'           => $this->id,
            'Webpage Store Key'             => $category->get('Category Store Key'),
            'Webpage Type Key'              => $webpage_type->id,
            'Webpage Code'                  => $page_code,
            'Webpage Template Filename'     => $template,
            'Webpage Number See Also Links' => ($category->get('Category Subject') == 'Product' ? 5 : 0),
            'Webpage Creation Date'         => gmdate('Y-m-d H:i:s'),
            'Webpage Name'                  => $category->get('Label'),
            'Webpage Browser Title'         => $category->get('Label'),


            'Page Parent Key'                        => $category->id,
            'Page Parent Code'                       => $category->get('Code'),
            'Page Store Section Type'                => 'Department',
            'Page Store Section'                     => 'Department Catalogue',
            'Page Store Last Update Date'            => gmdate('Y-m-d H:i:s'),
            'Page Store Last Structural Change Date' => gmdate('Y-m-d H:i:s'),
            'Page Locale'                            => $this->data['Website Locale'],
            'Page Source Template'                   => '',
            'Page Description'                       => '',
            'Page Title'                             => $category->get('Label'),
            'Page Short Title'                       => $category->get('Label'),
            'Page Store Title'                       => $category->get('Label'),
            'Page Header Key'                        => $header_key,
            'Page Footer Key'                        => $footer_key,
            //-------------------
            'editor'                                 => $this->editor,

        );


        //print_r($page_data);
        $page = new Page('find', $page_data, 'create');

        $category->update(array('Product Category Webpage Key' => $page->id), 'no_history');

        if ($page->new) {
            $page->reset_object();
        }


        //  print_r($page->data);

        $webpage_type->update_number_webpages();

        $page->update_version();


        if ($page->new) {
            $page->update_see_also();
        }

        $this->new_page     = $page->new;
        $this->new_page_key = $page->id;
        $this->msg          = $page->msg;
        $this->error        = $page->error;




            if ($category->get('Category Subject') == 'Product') {


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
                $page->update(array('Page Store Content Data' => json_encode($content_data)), 'no_history');


                $category->create_stack_index(true);
            }


        //$this->update_product_totals();
        //$this->update_page_totals();

        return $page->id;

    }

    function create_product_webpage($product_id) {

        include_once 'class.Webpage_Type.php';
        include_once 'class.Page.php';


        //include_once 'class.Site.php';
        // $site = new Site($this->id);


        $sql = sprintf(
            "SELECT `Page Key` FROM `Page Store Dimension` WHERE `Webpage Scope`='Product' AND `Webpage Scope Key`=%d  AND `Webpage Website Key`=%d ", $product_id, $this->id
        );

        //print $sql;

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                return $row['Page Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        include_once 'class.Product.php';
        $product = new Product($product_id);

        $page_code = $this->get_unique_code($product->get('Code'), 'Webpage');


        //-- to delete

        $sql = sprintf('SELECT `Site Default Header Key`,`Site Default Footer Key` FROM `Site Dimension` WHERE `Site Key`=%d', $this->id);
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $header_key = $row['Site Default Header Key'];
                $footer_key = $row['Site Default Footer Key'];
            } else {
                $header_key = 0;
                $footer_key = 0;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }
        //


        $webpage_type = new Webpage_Type('website_code', $this->id, 'Prod');

        $template = 'product';


        $page_data = array(
            'Page Code'                            => $page_code,
            'Page URL'                             => $this->data['Website URL'].'/'.strtolower($page_code),
            'Page Site Key'                        => $this->id,
            'Page Type'                            => 'Store',
            'Page Store Key'                       => $product->get('Product Store Key'),
            'Page Store Creation Date'             => gmdate('Y-m-d H:i:s'),
            'Number See Also Links'                => 5,
            'Page Store Content Display Type'      => 'Template',
            'Page Store Content Template Filename' => $template,


            'Webpage Scope'                          => 'Product',
            'Webpage Scope Key'                      => $product->id,
            'Webpage Website Key'                    => $this->id,
            'Webpage Store Key'                      => $product->get('Product Store Key'),
            'Webpage Type Key'                       => $webpage_type->id,
            'Webpage Code'                           => $page_code,
            'Webpage Template Filename'              => $template,
            'Webpage Number See Also Links'          => 5,
            'Webpage Creation Date'                  => gmdate('Y-m-d H:i:s'),
            'Webpage Name'                           => $product->get('Name'),
            'Webpage Browser Title'                  => $product->get('Name'),

            //--------   to remove ??
            'Page Parent Key'                        => $product->id,
            'Page Parent Code'                       => $product->get('Code'),
            'Page Store Section Type'                => 'Product',
            'Page Store Section'                     => 'Product Description',
            'Page Store Last Update Date'            => gmdate('Y-m-d H:i:s'),
            'Page Store Last Structural Change Date' => gmdate('Y-m-d H:i:s'),
            'Page Locale'                            => $this->data['Website Locale'],
            'Page Source Template'                   => '',
            'Page Description'                       => '',
            'Page Title'                             => $product->get('Name'),
            'Page Short Title'                       => $product->get('Name'),
            'Page Store Title'                       => $product->get('Name'),
            'Page Header Key'                        => $header_key,
            'Page Footer Key'                        => $footer_key,
            //-------------------

            'editor' => $this->editor

        );


        $page = new Page('find', $page_data, 'create');


        print $page->id;

        $product->update(array('Product Webpage Key' => $page->id), 'no_history');

        $webpage_type->update_number_webpages();

        $page->update_version();


        if ($page->new) {
            $page->update_see_also();
        }

        $this->new_page     = $page->new;
        $this->new_page_key = $page->id;
        $this->msg          = $page->msg;
        $this->error        = $page->error;


        $content_data = array(
            'description_block' => array(
                'class' => '',

                'content' => sprintf('<div class="description">%s</div>', $product->get('Description'))


            ),
            'tabs'              => array()

        );

        $page->update(array('Page Store Content Data' => json_encode($content_data)), 'no_history');


        return $page->id;

    }

    function reset_element($type) {

        if ($type == 'website_footer') {




            $footer         = get_object('footer',$this->get('Website Footer Key'));
            $footer->editor = $this->editor;
            $footer->reset();

        } elseif ($type == 'website_header') {



            $header         = get_object('header',$this->get('Website Header Key'));
            $header->editor = $this->editor;
            $header->reset();



        }
    }

    function set_footer_template($template) {


        include_once 'conf/footer_data.php';

        $footer_data = $this->get('Footer Data');
        if (!$footer_data) {

            $footer_data = array(
                'template' => $template,
                'data'     => get_default_footer_data($this, $template)


            );


        } else {
            $footer_data['template'] = $template;
            if (isset($footer_data['legacy'][$template])) {
                $footer_data['data'] = $footer_data['legacy'][$template]['data'];

            } else {
                $footer_data['data'] = get_default_footer_data($this, $template);
            }
        }


        $this->update(array('Website Footer Data' => json_encode($footer_data)), 'no_history');


    }

    function get_system_webpage_key($code) {

        $sql = sprintf(
            'SELECT `Page Key` FROM `Page Store Dimension` WHERE `Webpage Code`=%s AND `Webpage Website Key`=%d  ', prepare_mysql($code), $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                return $row['Page Key'];
            } else {
                return 0;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

    }


    function launch() {

        include_once 'class.Page.php';



        $this->update(array('Website Status' => 'Active'));



        $sql = sprintf(
            "SELECT `Page Key` FROM `Page Store Dimension`  P LEFT JOIN `Webpage Type Dimension` WTD ON (WTD.`Webpage Type Key`=P.`Webpage Type Key`)  WHERE `Webpage Website Key`=%d AND `Webpage Type Code` IN ('Info','Home','Ordering','Customer','Portfolio','Sys')   ",
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                $webpage         = new Page($row['Page Key']);
                $webpage->editor = $this->editor;

                if ($webpage->get('Webpage Code') == 'launching.sys') {
                    $webpage->update(array('Webpage State' => 'Offline'));
                } else {
                    $webpage->update(array('Webpage State' => 'Online'));
                    $webpage->update(array('Webpage Launch Date' => gmdate('Y-m-d H:i:s')), 'no_history');
                }


            }
        }

        include_once 'utils/new_fork.php';
        global $account;
        new_housekeeping_fork(
            'au_housekeeping', array(
            'type'     => 'website_launched',
            'website_key' => $this->id,
            'editor'=>$this->editor,

        ), $account->get('Account Code')
        );


    }

    function create_user($data) {

        include_once 'class.Website_User.php';

        $this->new = false;

        $data['editor']             = $this->editor;
        $data['Website User Website Key'] = $this->id;
        $data['Website User Active'] = 'Yes';


        $website_user = new Website_User('new', $data);


        if ($website_user->id) {

            if ($website_user->new) {







            } else {
                $this->error = true;
                $this->msg   = $website_user->msg;


            }

            return $website_user;
        } else {
            $this->error = true;
            $this->msg   = $website_user->msg;
        }
    }

    function update_users_data(){
        // todo collect user/customers stats here, call when a user is created

    }


}


?>
