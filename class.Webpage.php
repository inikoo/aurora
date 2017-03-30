<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 May 2016 at 19:43:38 CEST, Mijas Costa, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'class.WebpageVersion.php';

include_once 'class.DB_Table.php';

class Webpage extends DB_Table {

    var $areas = false;
    var $locations = false;

    function Webpage($a1, $a2 = false, $a3 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Webpage';
        $this->ignore_fields = array('Webpage Key');

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
            $sql = sprintf("SELECT * FROM `Webpage Dimension` WHERE `Webpage Key`=%d", $tag);
        } elseif ($key == 'website_code') {
            $sql = sprintf("SELECT  * FROM `Webpage Dimension` WHERE `Webpage Website Key`=%d AND `Webpage Code`=%s ", $tag, prepare_mysql($tag2));
        } elseif ($key == 'code') {
            $sql = sprintf("SELECT  * FROM `Webpage Dimension` WHERE `Webpage Code`=%s ", prepare_mysql($tag));
        } else {
            return;
        }
//print $sql;

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Webpage Key'];

            $this->version = $this->get_version();


        }


    }


    function get_version() {


        if ($this->get('Webpage Number Displayable Versions') == 0) {
            return false;
        }
        if ($this->get('Webpage Number Displayable Versions') == 1) {
            return new WebpageVersion($this->get('Webpage Version Key'));
        } else {

            $versions = $this->get_version_keys();
            if (count($versions) > 0) {
                // TODO !! with probabilities

                reset($versions);

                $this->version = key($versions);
            } else {
                $this->version = false;
            }

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

                if (array_key_exists('Webpage '.$key, $this->data)) {
                    return $this->data['Webpage '.$key];
                }


        }

        return '';
    }

    function get_version_keys($filter = 'all') {
        $versions = array();

        $sql = sprintf(
            'SELECT `Webpage Version Key`,`Webpage Version Display Probability` FROM `Webpage Version Dimension` WHERE `Webpage Version Webpage Key`=%d ORDER BY `Webpage Version Display Probability` DESC ',
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($filter == 'displayable') {
                    if ($row['Webpage Version Display Probability'] > 0) {
                        $versions[$row['Webpage Version Key']] = $row['Webpage Version Display Probability'];
                    }

                } elseif ($filter == 'displayable') {
                    if ($row['Webpage Version Display Probability'] == 0) {
                        $versions[$row['Webpage Version Key']] = $row['Webpage Version Display Probability'];
                    }

                } else {
                    $versions[$row['Webpage Version Key']] = $row['Webpage Version Display Probability'];

                }

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $versions;
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
            }
        }


        if ($data['Webpage Code'] == '') {
            $this->error = true;
            $this->msg   = 'Webpage code empty';

            return;
        }

        if ($data['Webpage Name'] == '') {
            $this->error = true;
            $this->msg   = 'Webpage name empty';

            return;
        }


        $sql = sprintf(
            "SELECT `Webpage Key` FROM `Webpage Dimension` WHERE `Webpage Website Key`=%d AND  `Webpage Code`=%s", $data['Webpage Website Key'], prepare_mysql($data['Webpage Code'])
        );

        // print "$sql\n";

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $this->found     = true;
                $this->found_key = $row['Webpage Key'];
                $this->get_data('id', $this->found_key);
                $this->duplicated_field = 'Webpage Code';
                $this->msg = sprintf(_('Another web page has same code %s'),$data['Webpage Code']);
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
            "INSERT INTO `Webpage Dimension` %s %s", $keys, $values
        );
        //print "=======  $sql\"";
        if ($this->db->exec($sql)) {
            $this->id  = $this->db->lastInsertId();
            $this->msg = _("Webpage created");
            $this->get_data('id', $this->id);
            $this->new = true;


            $this->version = $this->create_version();


            switch ($this->get('Webpage Scope')) {
                case 'Product':


                    break;
                default:

                    break;
            }


            return;
        } else {
            $this->msg = "Error can not create webpage";
            print $sql;
            exit;
        }
    }

    function create_version() {

        include_once('class.Website.php');
        $website = new Website($this->get('Webpage Website Key'));

        $this->new_object = false;

        $data['editor'] = $this->editor;


        // print $this->get('Webpage Scope')."\n";

        $data['Webpage Version Webpage Key']  = $this->id;
        $data['Webpage Version Valid From']   = gmdate('Y-m-d H:i:s');
        $data['Webpage Version Template Key'] = $website->get_default_template_key($this->get('Webpage Scope'));


        if (!array_key_exists('Webpage Version Code', $data) or $data['Webpage Version Code'] == '') {

            $number_web_pages = count($this->get_version_keys());

            if ($number_web_pages < 26) {
                $alphabet                     = range('A', 'Z');
                $data['Webpage Version Code'] = $alphabet[$number_web_pages];
            }


        }

        $version = new WebpageVersion('find', $data, 'create');

        if ($version->id) {
            $this->new_object_msg = $version->msg;

            if ($version->new) {
                $this->new_object = true;
                $this->update_versions_data();


                switch ($this->get('Webpage Scope')) {
                    case 'Hub':
                        $version->update(
                            array(
                                'Webpage Version Metadata' => json_encode(
                                    array('body_classes' => 'information-contact page-information-contact layout-fullwidth')
                                )
                            ), 'no_history'
                        );

                        $settings = array(
                            'title'   => array(
                                'edit'  => 'string',
                                'id'    => 'title',
                                'value' => _('Editable Title')
                            ),
                            'content' => array(
                                'edit'  => 'text',
                                'id'    => 'content',
                                'value' => _(
                                    'This is a CMS block edited from admin panel'
                                )
                            )
                        );

                        $version->append_block(
                            array(
                                'Webpage Version Block Template' => 'info.blank',
                                'Webpage Version Block Settings' => $settings
                            )
                        );

                        break;
                    case 'Info':
                        $version->update(
                            array(
                                'Webpage Version Metadata' => json_encode(
                                    array('body_classes' => 'information-contact page-information-contact layout-fullwidth')
                                )
                            ), 'no_history'
                        );

                        $settings = array(
                            'title'   => array(
                                'edit'  => 'string',
                                'id'    => 'title',
                                'value' => _('Editable Title')
                            ),
                            'content' => array(
                                'edit'  => 'text',
                                'id'    => 'content',
                                'value' => _(
                                    'This is a CMS block edited from admin panel'
                                )
                            )
                        );

                        $version->append_block(
                            array(
                                'Webpage Version Block Template' => 'info.blank',
                                'Webpage Version Block Settings' => $settings
                            )
                        );

                        break;

                    case 'Product':

                        $version->update(
                            array(
                                'Webpage Properties' => json_encode(
                                    array('body_classes' => 'page-product layout-fullwidth')
                                )
                            ), 'no_history'
                        );

                        $settings = array(
                            'title' => array(
                                'edit'  => 'string',
                                'id'    => 'title',
                                'value' => 'xxx'
                            ),


                            'summary' => array(
                                array(
                                    'class'             => '',
                                    'label'             => 'Code',
                                    'product_value_key' => 'Code',
                                    'ref'               => ''
                                ),
                                array(
                                    'class'             => '',
                                    'label'             => 'Availability',
                                    'product_value_key' => 'Fuzzy Availability',
                                    'ref'               => ''
                                )
                            )


                        );
                        $version->append_block(
                            array(
                                'Webpage Version Block Template' => 'product',
                                'Webpage Version Block Settings' => $settings
                            )
                        );


                        break;


                    default:

                        break;
                }


            } else {
                $this->error = true;
                if ($version->found) {

                    $this->error_code     = 'duplicated_field';
                    $this->error_metadata = json_encode(
                        array($version->duplicated_field)
                    );

                    if ($version->duplicated_field == 'Webpage Code') {
                        $this->msg = _('Duplicated webpage version code');
                    }


                } else {
                    $this->msg = $version->msg;
                }
            }

            return $version;
        } else {
            $this->error = true;
            $this->msg   = $version->msg;
        }


    }

    function update_versions_data() {

        $displayable_versions        = $this->get_version_keys('displayable');
        $number_displayable_versions = count($displayable_versions);
        $versions                    = $this->get_version_keys();
        $number_versions             = count($versions);

        if ($number_versions == 0) {
            $main_version = '';
        } else {

            reset($versions);
            $main_version = key($versions);

        }


        $this->update(
            array(
                'Webpage Number Displayable Versions' => $number_displayable_versions,
                'Webpage Version Key'                 => $main_version
            ), 'no_history'
        );
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

    function get_field_label($field) {

        switch ($field) {

            case 'Webpage Code':
                $label = _('code');
                break;
            case 'Webpage Name':
                $label = _('name');
                break;
            case 'Webpage Address':
                $label = _('address');
                break;

            default:


                $label = $field;

        }

        return $label;

    }

    function get_content($smarty, $version_key = false) {

        include_once 'utils/object_functions.php';

        if (!$version_key) {
            $version_key = $this->version->id;
        }

        $content = '';

        $object = get_object(
            $this->get('Webpage Object'), $this->get('Webpage Object Key')
        );


        $sql = sprintf(
            'SELECT `Webpage Version Block Template`,`Webpage Version Block Settings` FROM  `Webpage Version Block Bridge` WHERE `Webpage Version Block Webpage Version Key`=%d  ORDER BY `Webpage Version Block Position` DESC',
            $version_key
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $smarty->assign(
                    'data', json_decode($row['Webpage Version Block Settings'], true)
                );

                if ($row['Webpage Version Block Template'] == 'product') {
                    $smarty->assign('product', $object);

                }

                $content .= $smarty->fetch(
                    'ecom/'.$row['Webpage Version Block Template'].'.tpl'
                );
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $content;


    }

}


?>
