<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 29 May 2016 at 11:00:08 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3

*/


include_once 'class.DB_Table.php';
include_once 'class.Webpage.php';

class WebsiteNode extends DB_Table {

    var $areas = false;
    var $locations = false;

    function WebsiteNode($a1, $a2 = false, $a3 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Website Node';
        $this->ignore_fields = array('Website Node Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } elseif ($a1 == 'find') {
            $this->find($a2, $a3);

        } else {
            $this->get_data($a1, $a2, $a3);
        }
    }


    function get_data($key, $tag, $tag2 = false) {


        if ($key == 'id') {
            $sql = sprintf("SELECT * FROM `Website Node Dimension` WHERE `Website Node Key`=%d", $tag);
        } else {
            return;
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Website Node Key'];

            $this->webpage = new Webpage($this->get('Website Node Webpage Key'));

        }


    }

    function get($key, $data = false) {

        if (!$this->id) {
            return '';
        }


        switch ($key) {

            default:


                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Website Node '.$key, $this->data)) {
                    return $this->data['Website Node '.$key];
                }


        }

        return '';
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

        if ($data['Website Node Website Key'] == '') {
            $this->error = true;
            $this->msg   = 'Website Node Website Key empty';

            return;
        }

        /*
        if ($data['Website Node Code']=='' ) {
            $this->error=true;
            $this->msg='Website Node Code empty';
            return;
        }

        if ($data['Website Node Name']=='')
            $data['Website Node Name']=$data['Website Node Code'];




        $sql=sprintf("select `Website Node Key` from `Website Node Dimension` where `Website Node Website Key`=%d and `Website Node Code`=%s  ",
            $data['Website Node Website Key'],
            prepare_mysql($data['Website Node Code'])
        );


        if ($result=$this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $this->found=true;
                $this->found_key=$row['Website Node Key'];
                $this->get_data('id', $this->found_key);
                $this->duplicated_field='Website Node Code';
                return;
            }
        }else {
            print_r($error_info=$this->db->errorInfo());
            exit;
        }

*/

        if ($create and !$this->found) {
            $this->create($data, $raw_data);

            return;
        }


    }

    function create($data, $raw_data) {

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
            $values .= prepare_mysql($value).",";
        }
        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);
        $sql    = sprintf("INSERT INTO `Website Node Dimension` %s %s", $keys, $values);

        if ($this->db->exec($sql)) {
            $this->id  = $this->db->lastInsertId();
            $this->msg = _("Website node added");
            $this->get_data('id', $this->id);
            $this->new = true;


            $webpage = $this->create_webpage($raw_data);
            if (!$this->error) {


                $this->update(array('Website Node Webpage Key' => $webpage->id), 'no_history');
                $this->webpage = $webpage;

            } else {
                print_r($raw_data);
                print_r($this);
                exit("Error");

            }


            switch ($this->webpage->get('Webpage Class')) {
                case 'Categories':
                case 'Products':
                    $this->create_categories_nodes();
                    break;
                default:
                    // print "class not found ".$this->webpage->get('Webpage Class')."\n";
                    break;
            }


            return;
        } else {
            $this->msg = "Error can not create website node";
            print $sql;
            exit;
        }
    }

    function create_webpage($data) {

        $this->new_object = false;

        $data['editor'] = $this->editor;


        $data['Webpage Store Key']   = $this->get('Store Key');
        $data['Webpage Website Key'] = $this->get('Website Key');

        $data['Webpage Website Node Key'] = $this->id;
        $data['Webpage Valid From']       = gmdate('Y-m-d H:i:s');


        if (!array_key_exists('Webpage Code', $data) or $data['Webpage Code'] == '') {
            $this->error = true;
            $this->msg   = 'Missing webpage code';

            return;
        }


        if (!array_key_exists('Webpage Name', $data) or $data['Webpage Name'] == '') {
            $data['Webpage Name'] = $data['Webpage Code'];
        }

        $webpage = new Webpage('find', $data, 'create');

        if ($webpage->id) {
            $this->new_object_msg = $webpage->msg;

            if ($webpage->new) {
                $this->new_object = true;

            } else {
                $this->error = true;
                if ($webpage->found) {

                    $this->error_code     = 'duplicated_field';
                    $this->error_metadata = json_encode(array($webpage->duplicated_field));

                    if ($webpage->duplicated_field == 'Webpage Code') {
                        $this->msg = _('Duplicated webpage code');
                    }


                } else {
                    $this->msg = $webpage->msg;
                }
            }

            return $webpage;
        } else {
            $this->error = true;
            $this->msg   = $webpage->msg;
        }
    }

    function create_categories_nodes() {

        include_once 'class.Category.php';
        $category = new Category($this->webpage->get('Webpage Object Key'));
        //print_r($category->data);
        if ($category->get('Category Subject') == 'Category') {


            if ($category->get('Category Branch Type') == 'Head') {
                $sql = sprintf(
                    "SELECT C.`Category Key`,`Category Branch Type`,`Category Code`,`Category Label`,`Category Subject` FROM `Category Bridge` B LEFT JOIN  `Category Dimension` C ON (`Subject Key`=C.`Category Key`) LEFT JOIN `Product Category Dimension` PC ON (PC.`Product Category Key`=C.`Category Key`)   WHERE  B.`Category Key`=%d AND `Product Category Public`='Yes'",
                    $this->webpage->get('Webpage Object Key')
                );

            } else {
                $sql = sprintf(
                    "SELECT `Category Key`,`Category Branch Type`,`Category Code`,`Category Label`,`Category Subject` FROM   `Category Dimension` C LEFT JOIN `Product Category Dimension` PC ON (PC.`Product Category Key`=C.`Category Key`)  WHERE  `Category Parent Key`=%d AND `Product Category Public`='Yes'  ",
                    $this->webpage->get('Webpage Object Key')
                );

            }
            //print "$sql\n";

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {

                    if ($category->get('Category Branch Type') == 'Head') {
                        $branch_type = $row['Category Branch Type'];
                    } else {
                        $branch_type = 'Root';

                    }


                    $subnode = $this->create_subnode(
                        array(
                            'Webpage Code'  => ($branch_type == 'Head' ? 'f' : 'd').'.'.$row['Category Code'],
                            'Webpage Name'  => $row['Category Label'],
                            'Webpage Class' => ($branch_type == 'Head' ? 'Products' : 'Categories'),

                            'Website Node Type'  => 'Branch',
                            'Website Node Icon'  => ($branch_type == 'Head' ? 'pagelines' : 'tree'),
                            'Webpage Object'     => 'Category',
                            'Webpage Object Key' => $row['Category Key'],
                        )
                    );

                    print_r(
                        array(
                            'Webpage Code'        => ($branch_type == 'Head' ? 'f' : 'd').'.'.$row['Category Code'],
                            'Webpage Name'        => $row['Category Label'],
                            'Website Node Locked' => 'No',
                            'Website Node Type'   => 'Branch',
                            'Website Node Icon'   => ($branch_type == 'Head' ? 'pagelines' : 'tree'),
                            'Webpage Class'       => ($branch_type == 'Head' ? 'Products' : 'Categories'),
                            'Webpage Object'      => 'Category',
                            'Webpage Object Key'  => $row['Category Key'],
                        )
                    );

                }


            } else {
                print_r($error_info = $this->db->errorInfo());
                print $sql;
                exit;
            }


        } elseif ($category->get('Category Subject') == 'Product') {
            include_once 'class.Product.php';
            $sql = sprintf(
                "SELECT  `Subject Key` ,`Product Code`,`Product Name`,`Product Status` FROM `Category Bridge` B LEFT JOIN `Product Dimension` P ON (`Product ID`=`Subject Key`)  WHERE  B.`Category Key`=%d AND `Product Status`!='Discontinued'  AND `Product Public`='Yes'  ",
                $this->webpage->get('Webpage Object Key')
            );

            print "$sql\n";


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {

                    $product = new Product($row['Subject Key']);

                    $subnode = $this->create_subnode(
                        array(
                            'Webpage Code'        => 'a.'.$row['Product Code'],
                            'Webpage Name'        => $row['Product Name'],
                            'Webpage Status'      => ($row['Product Status'] == 'Active' ? 'Online' : 'Offline'),
                            'Website Node Locked' => 'No',
                            'Website Node Type'   => 'Head',
                            'Website Node Icon'   => 'leaf',
                            'Webpage Class'       => 'Product',
                            'Webpage Object'      => 'Product',
                            'Webpage Object Key'  => $row['Subject Key'],
                        )
                    );


                }


            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }


        }


    }

    function create_subnode($data) {

        $this->new_object = false;

        $data['editor'] = $this->editor;

        $data['Website Node Store Key']   = $this->get('Store Key');
        $data['Website Node Website Key'] = $this->get('Website Key');
        $data['Website Node Parent Key']  = $this->id;


        $data['Website Node Valid From'] = gmdate('Y-m-d H:i:s');


        $website_node = new WebsiteNode('find', $data, 'create');

        if ($website_node->id) {
            $this->new_object_msg = $website_node->msg;

            if ($website_node->new) {
                $this->new_object = true;
                $this->update_website_nodes_data();
            } else {
                print_r($data);
                exit();

                $this->error = true;
                if ($website_node->found) {

                    $this->error_code     = 'duplicated_field';
                    $this->error_metadata = json_encode(array($website_node->duplicated_field));

                    //if ($website_node->duplicated_field=='Website Node Code') {
                    // $this->msg=_('Duplicated website node code');
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

    function update_website_nodes_data() {

    }

    function get_webpage_key_delete() {

        $webpages = array();
        $sql      = sprintf('SELECT `Webpage Key`,`Webpage Display Probability` FROM `Webpage Dimension` WHERE `Webpage Website Node Key`=%d AND `Webpage Display Probability`>0', $this->id);

        $base = 0;

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $webpages[$row['Webpage Key']] = $base + $row['Webpage Display Probability'];
                $base += $base + $row['Webpage Display Probability'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        if (count($webpages) == 0) {
            return false;
        } elseif (count($webpages) == 1) {

            reset($webpages);

            return key($webpages);
        } else {
            exit("todo class.WebsiteNode.php line 226\n");
        }


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
        $sql             = sprintf('SELECT count(*) AS number FROM `Webpage Dimension` WHERE `Webpage Website Node Key`=%d', $this->id);
        $number_webpages = 0;


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_webpages = $row['number'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $this->update(array('Website Node Number Pages' => $number_webpages), 'no_history');


    }

    function get_number_webpages() {
        $number_webpages = 0;

        $sql = sprintf('SELECT count(*) AS num FROM `Webpage Dimension` WHERE `Webpage Website Node Key`=%d', $this->id);

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_webpages = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print $sql;
            exit;
        }

        return $number_webpages;
    }

    function get_field_label($field) {

        switch ($field) {

            case 'Website Node Code':
                $label = _('code');
                break;
            case 'Website Node Name':
                $label = _('name');
                break;


            default:


                $label = $field;

        }

        return $label;

    }


}


?>
