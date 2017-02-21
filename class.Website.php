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
        $update = '';
        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }


        $data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {
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

            $this->setup_templates();


            include_once 'conf/webpage_types.php';

            foreach ($webpage_types as $webpage_type){


                $sql=sprintf('insert into `Webpage Type Dimension` (`Webpage Type Website Key`,`Webpage Type Code`) values (%d,%s) ',
                             $this->id,
                             prepare_mysql($webpage_type['code'])
                );

                $db->exec($sql);

            }



            if (is_numeric($this->editor['User Key']) and $this->editor['User Key'] > 1) {

                $sql = sprintf("INSERT INTO `User Right Scope Bridge` VALUES(%d,'Website',%d)", $this->editor['User Key'], $this->id);
                $this->db->exec($sql);

            }



            return;
        } else {
            $this->msg = "Error can not create website";
            print $sql;
            exit;
        }
    }


    function create_no_product_webnodes() {

        include_once 'class.WebsiteNode.php';
        include_once 'class.Website.php';

        $home_webnode = $this->create_webnode(
            array(
                'Webpage Code'        => 'p.Home',
                'Webpage Name'        => _('Home'),
                'Webpage Scope'       => 'Home',
                'Webpage Locked'      => 'Yes',
                'Website Node Locked' => 'Yes',
                'Website Node Type'   => 'Root',
                'Website Node Icon'   => 'home'


            )
        );
        $page         = new Webpage($home_webnode->get('Website Node Webpage Key'));
        $page->update(
            array(
                'Webpage Properties' => json_encode(
                    array('body_classes' => 'common-home page-common-home layout-fullwidth')
                )
            ), 'no_history'
        );


        $mya_webnode = $home_webnode->create_subnode(
            array(
                'Webpage Code'        => 'p.MyA',
                'Webpage Name'        => _('My account'),
                'Webpage Scope'       => 'Hub',
                'Webpage Locked'      => 'Yes',
                'Website Node Locked' => 'Yes',
                'Website Node Type'   => 'Root',
                'Website Node Icon'   => 'user',

            )
        );

        $home_webnode->create_subnode(
            array(
                'Webpage Code'        => 'p.Login',
                'Webpage Name'        => _('Login'),
                'Website Node Locked' => 'Yes',
                'Webpage Scope'       => 'Login',
                'Webpage Locked'      => 'Yes',
                'Website Node Type'   => 'Head'
            )
        );
        $home_webnode->create_subnode(
            array(
                'Webpage Code'        => 'p.Register',
                'Webpage Name'        => _('Register'),
                'Website Node Locked' => 'Yes',
                'Webpage Scope'       => 'Register',
                'Webpage Locked'      => 'Yes',
                'Website Node Type'   => 'Head'
            )
        );
        $home_webnode->create_subnode(
            array(
                'Webpage Code'        => 'p.Pwd',
                'Webpage Name'        => _('Forgotten password'),
                'Website Node Locked' => 'Yes',
                'Webpage Scope'       => 'ResetPwd',
                'Webpage Locked'      => 'Yes',
                'Website Node Type'   => 'Head'
            )
        );
        $mya_webnode->create_subnode(
            array(
                'Webpage Code'        => 'p.Profile',
                'Webpage Name'        => _('My account'),
                'Website Node Locked' => 'Yes',
                'Webpage Scope'       => 'Profile',
                'Webpage Locked'      => 'Yes',
                'Website Node Type'   => 'Head'
            )
        );
        $mya_webnode->create_subnode(
            array(
                'Webpage Code'        => 'p.Orders',
                'Webpage Name'        => _('My orders'),
                'Website Node Locked' => 'Yes',
                'Webpage Scope'       => 'Orders',
                'Webpage Locked'      => 'Yes',
                'Website Node Type'   => 'Head'
            )
        );


        $cs_webnode = $home_webnode->create_subnode(
            array(
                'Webpage Code'        => 'p.CS',
                'Webpage Name'        => _('Customer services'),
                'Website Node Locked' => 'Yes',
                'Website Node Type'   => 'Root',
                'Website Node Icon'   => 'thumbs-o-up',
                'Webpage Scope'       => 'Hub'
            )
        );


        $node = $cs_webnode->create_subnode(
            array(
                'Webpage Code'        => 'p.Contact',
                'Webpage Name'        => _('Contact us'),
                'Website Node Locked' => 'Yes',
                'Webpage Scope'       => 'Contact',
                'Website Node Type'   => 'Head'
            )
        );


        $node = $cs_webnode->create_subnode(
            array(
                'Webpage Code'        => 'p.Delivery',
                'Webpage Name'        => _('Delivery'),
                'Website Node Locked' => 'No',
                'Website Node Type'   => 'Head',
                'Webpage Scope'       => 'Blank',
                'Website Node Icon'   => 'truck fa-flip-horizontal'
            )
        );


        $node = $cs_webnode->create_subnode(
            array(
                'Webpage Code'        => 'p.GTC',
                'Webpage Name'        => _('Terms & Conditions'),
                'Website Node Locked' => 'Yes',
                'Webpage Locked'      => 'Yes',
                'Webpage Scope'       => 'Blank',
                'Website Node Type'   => 'Head'
            )
        );


        //$home_webnode->create_subnode(array('Webpage Code'=>'p.Insp', 'Webpage Name'=>_('Inspiration'), 'Website Node Locked'=>'No', 'Website Node Type'=>'Root'));


    }

    function create_webnode($data) {

        $this->new_object = false;

        $data['editor'] = $this->editor;

        $data['Website Node Store Key']   = $this->get('Store Key');
        $data['Website Node Website Key'] = $this->id;
        $data['Website Node Valid From']  = gmdate('Y-m-d H:i:s');


        $website_node = new WebsiteNode('find', $data, 'create');

        if ($website_node->id) {
            $this->new_object_msg = $website_node->msg;

            if ($website_node->new) {
                $this->new_object = true;
                $website_node->update(
                    array(
                        'Website Node Parent Key' => $website_node->id
                    ), 'no_history'
                );

                $this->update_website_nodes_data();
            } else {
                $this->error = true;
                if ($website_node->found) {

                    $this->error_code     = 'duplicated_field';
                    $this->error_metadata = json_encode(
                        array($website_node->duplicated_field)
                    );

                    //if ($website_node->duplicated_field=='Webpage Code') {
                    // $this->msg=_('Duplicated Webpage Code');
                    //}


                } else {
                    $this->msg = $website_node->msg;
                }
            }

            return $website_node;
        } else {
            $this->error = true;
            $this->msg   = $website_node->msg;
        }
    }

    function get($key, $data = false) {

        if (!$this->id) {
            return '';
        }


        switch ($key) {
            case('num_areas'):
            case('number_areas'):
                if (!$this->areas) {
                    $this->load('areas');
                }

                return count($this->areas);
                break;
            case('areas'):
                if (!$this->areas) {
                    $this->load('areas');
                }

                return $this->areas;
                break;
            case('area'):
                if (!$this->areas) {
                    $this->load('areas');
                }
                if (isset($this->areas[$data['id']])) {
                    return $this->areas[$data['id']];
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

    function update_website_nodes_data() {

    }

    function create_product_webnodes() {

        $homepage = new Webpage('website_code', $this->id, 'p.Home');


        $home_webnode = new WebsiteNode($homepage->get('Webpage Website Node Key'));

        $store = new Store($this->get('Website Store Key'));

        $catalogue = $home_webnode->create_subnode(
            array(
                'Webpage Code'        => 'p.Cat',
                'Webpage Name'        => _('Catalogue'),
                'Webpage Locked'      => 'Yes',
                'Website Node Locked' => 'Yes',
                'Website Node Type'   => 'Root',
                'Website Node Icon'   => 'th',
                'Webpage Scope'       => 'Categories',
                'Webpage Object'      => 'Category',
                'Webpage Object Key'  => $store->get('Store Department Category Key')
            )
        );

        /*
        $sql=sprintf('select `Category Label`,`Category Code`,`Category Key` from `Category Dimension` where `Category Root Key`=%d ',
                     $store->get('Store Department Category Key')
                     );
        print $sql;

        if ($result=$this->db->query($sql)) {
        		foreach ($result as $row) {



                    $department=$catalogue->create_subnode(
                        array(
                            'Webpage Code'        => 'd.'.$row['Category Code'],
                            'Webpage Name'        => $row['Category Label'],
                            'Webpage Locked'      => 'Yes',
                            'Website Node Locked' => 'Yes',
                            'Website Node Type'   => 'Branch',
                            'Website Node Icon'   => 'th',
                            'Webpage Scope'       => 'Categories',
                            'Webpage Object'      => 'Category',
                            'Webpage Object Key'  => $row['Category Key']
                        )
                    );

                    print "end create department ".$row['Category Label']."\n";
        		}
        }else {
        		print_r($error_info=$this->db->errorInfo());
        		print "$sql\n";
        		exit;
        }

        print "xxxend\n";
        */


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


    function get_default_template_key($scope,$device='Desktop'){

        $template_key=false;

        $sql=sprintf('select `Template Key` from `Template Dimension` where `Template Website Key`=%d and `Template Scope`=%s and `Template Device`=%s ',
                     $this->id,
                     prepare_mysql($scope),
                     prepare_mysql($device)

                     );
        if ($result=$this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $template_key=$row['Template Key'];
        	}
        }else {
        	print_r($error_info=$this->db->errorInfo());
        	print "$sql\n";
        	exit;
        }

        if(!$template_key){


            $sql=sprintf('select `Template Key` from `Template Dimension` where `Template Website Key`=%d and `Template Scope`=%s and `Template Device`="Desktop" ',
                         $this->id,
                         prepare_mysql($scope)

            );
            if ($result=$this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $template_key=$row['Template Key'];
                }
            }else {
                print_r($error_info=$this->db->errorInfo());
                print "$sql\n";
                exit;
            }

        }

        if(!$template_key){


            $sql=sprintf('select `Template Key` from `Template Dimension` where `Template Website Key`=%d and `Template Scope`="Blank" and `Template Device`=%s ',
                         $this->id,
                         prepare_mysql($scope)

            );
            if ($result=$this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $template_key=$row['Template Key'];
                }
            }else {
                print_r($error_info=$this->db->errorInfo());
                print "$sql\n";
                exit;
            }

        }

       // print $template_key;



        return $template_key;

    }

    function setup_templates(){

        include_once('class.TemplateScope.php');
        include_once('class.Template.php');
        include_once('conf/website_templates.php');

        $templates=website_templates_config($this->get('Website Type'));

        //print_r($templates);

        foreach($templates['templates'] as $template_code=>$_template_data){
           // print_r($_template_data);

            $template_scope_data=array(
                'Template Scope Website Key'=>$this->id,
                'Template Scope Code'=>$_template_data['scope'],

                'editor'=>$this->editor

            );

            $template_scope=new TemplateScope('find',$template_scope_data,'create');


            $template_data=array(
                'Template Code'=>$template_code,
                'Template Base'=>'Yes',
                'Template Device'=>(isset($_template_data['device'])?$_template_data['device']:'desktop'),
                'editor'=>$this->editor

            );

            $template_scope->create_template($template_data);

           // $template=new Template('find',$template_data,'create');

        }





    }

}


?>
