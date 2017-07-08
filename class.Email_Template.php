<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 July 2017 at 23:04:19 GMT+8, Cyberjaya. Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


include_once 'class.DB_Table.php';

class Email_Template extends DB_Table {


    function Email_Template($a1, $a2 = false, $a3 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Email Template';
        $this->ignore_fields = array('Email Template Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } elseif ($a1 == 'new') {
            $this->create($a2);

        } elseif ($a1 == 'find') {
            $this->find($a2, $a3);

        } else {
            $this->get_data($a1, $a2);
        }
    }


    function get_data($key, $tag) {



            $sql = sprintf(
                "SELECT * FROM `Email Template Dimension` WHERE `Email Template Key`=%d", $tag
            );



        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Email Template Key'];
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
            $keys   .= "`$key`,";
            $values .= prepare_mysql($value).",";
        }
        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);
        $sql    = sprintf(
            "INSERT INTO `Email Template Dimension` %s %s", $keys, $values
        );


        // print $sql;


        if ($this->db->exec($sql)) {
            $this->id  = $this->db->lastInsertId();
            $this->msg = "Email Template added";
            $this->get_data('id', $this->id);
            $this->new = true;


            $checksum = md5(($this->get('Email Template Type') == 'Text'  ? '' : $this->get('Email Template Editing JSON')).'|'.$this->get('Email Template Text').'|'.$this->get('Email Template Subject'));


            $this->update(
                array(
                    'Email Template Editing Checksum' => $checksum,
                ), 'no_history'
            );


            return $this;
        } else {
            $this->msg = "Error can not create Email Template";

            print_r($this->db->errorInfo());
            // print $sql;
            exit;
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


        $sql = sprintf(
            "SELECT `Email Template Key` FROM `Email Template Dimension` WHERE `Email Template Role`=%s AND  `Email Template Scope`=%s AND  `Email Template Scope Key`=%d ",
            prepare_mysql($data['Email Template Role']), prepare_mysql($data['Email Template Scope']), $data['Email Template Scope Key']

        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $this->found     = true;
                $this->found_key = $row['Email Template Key'];
                $this->get_data('id', $this->found_key);
                $this->duplicated_field = 'Email Template Code';

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

    function create_blueprint($data) {

        include_once 'class.Email_Blueprint.php';

        if (!isset($data['Email Blueprint Name'])) {
            $this->error = true;
            $this->msg   = 'blueprint name';

            return;
        }

        if ($data['Email Blueprint Name'] == '') {
            $this->error = true;
            $this->msg   = 'blueprint name empty';

            return;
        }


        $data['Email Blueprint Role']      = $this->get('Email Template Role');
        $data['Email Blueprint Scope']     = $this->get('Email Template Scope');
        $data['Email Blueprint Scope Key'] = $this->get('Email Template Scope Key');

        $data['Email Blueprint Name'] = $this->get_unique_name($data['Email Blueprint Name'], 'Blueprint');
        $data['editor']               = $this->editor;


        $blueprint = new Email_Blueprint('new', $data);
        if (!$blueprint->id) {
            $this->error = true;
            $this->msg   = $header->msg;

            return;
        }

        return $blueprint;

    }



    function get($key, $data = false) {

        if (!$this->id) {
            return '';
        }


        switch ($key) {




            case 'Published Info':
                $data = array(
                    'editing'     => ($this->data['Email Template Editing Checksum'] == $this->data['Email Template Published Checksum'] ? false : true),
                    'published'   => ($this->data['Email Template Published Email Key'] ? true : false),
                    'edited_date' => ($this->data['Email Template Last Edited'] == '' ? '' : strftime("%a %e %b %Y %H:%M:%S %Z", strtotime($this->data['Email Template Last Edited'].' +0:00')))

                );

                if ($data['published']) {
                    include_once 'class.Published_Email_Template.php';
                    $published_email_template = new Published_Email_Template($this->data['Email Template Published Email Key']);
                    $data['published_date']   = ($published_email_template->data['Published Email Template From'] == ''
                        ? ''
                        : strftime(
                            "%a %e %b %Y %H:%M:%S %Z", strtotime($published_email_template->data['Published Email Template From'].' +0:00')
                        ));
                }

                return $data;
                break;
            default:


                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Email Template '.$key, $this->data)) {
                    return $this->data['Email Template '.$key];
                }


        }

        return '';
    }

    function get_unique_name($name, $type) {


        for ($i = 1; $i <= 200; $i++) {

            if ($i == 1) {
                $suffix = '';
            } elseif ($i <= 100) {
                $suffix = $i;
            } else {
                $suffix = uniqid('', true);
            }

            if ($type == 'Blueprint') {
                $sql = sprintf(
                    "SELECT `Email Blueprint Key` FROM `Email Blueprint Dimension`  WHERE `Email Blueprint Role`=%s AND  `Email Blueprint Scope`=%s AND   `Email Blueprint Scope Key`=%s AND `Email Blueprint Name`=%s  ",
                    prepare_mysql($this->get('Email Template Role')), prepare_mysql($this->get('Email Template Scope')), $this->get('Email Template Scope Key'),

                    prepare_mysql($name.$suffix)
                );
            } else {
                exit('error unknown type in get_unique_name ');
            }


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {

                } else {
                    return $name.$suffix;
                }
            }


        }

        return $suffix;
    }

    function publish($data) {

        include_once 'class.Published_Email_Template.php';


        $data['editor'] = $this->editor;




        if($this->get('Email Template Type')=='Text'){
            $data['Published Email Template JSON']='';
            $data['Published Email Template HTML']='';



        }


        $current_published_template = new Published_Email_Template($this->get('Email Template Published Email Key'));

        if ($current_published_template->id) {

            $checksum = md5($data['Published Email Template JSON'].'|'.$this->get('Email Template Text').'|'.$this->get('Email Template Subject'));




            if ($checksum == $current_published_template->get('Published Email Template Checksum')) {
                $published_template=$current_published_template;
            }
        } else {
            $checksum = md5($data['Published Email Template JSON'].'|'.$this->get('Email Template Text').'|'.$this->get('Email Template Subject'));

        }

        $text     = $this->get('Email Template Text');
        $subject  = $this->get('Email Template Subject');


        $data['Published Email Template Checksum']           = $checksum;
        $data['Published Email Template Text']               = $text;
        $data['Published Email Template Subject']            = $subject;
        $data['Published Email Template Email Template Key'] = $this->id;

        $data['editor'] = $this->editor;


        // print_r($data);

        if(!isset($published_template)){
            $published_template = new Published_Email_Template('new', $data);
        }




        if (!$published_template->id) {
            $this->error = true;
            $this->msg   = $published_template->msg;

            return;
        }


        if ($current_published_template->id and $published_template->new) {
            $current_published_template->update(
                array(
                    'Published Email Template To' => $published_template->get('Published Email Template From')
                ), 'no_history'
            );
        }


        $this->update(
            array(
                'Email Template Editing JSON'            => $data['Published Email Template JSON'],
                'Email Template Editing Checksum'   => $checksum,
                'Email Template Published Checksum' => $checksum,
                'Email Template Published Email Key'     => $published_template->id
            ), 'no_history'
        );


        return $published_template;


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


            case 'Email Template Name':
                $label = _('name');
                break;


            default:


                $label = $field;

        }

        return $label;

    }


}


?>
