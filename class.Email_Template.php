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


    function __construct($a1, $a2 = false, $a3 = false) {

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


        $base_data['Email Template Created'] = gmdate('Y-m-d H:i:s');
        //$base_data['Email Template Last Edited']=gmdate('Y-m-d H:i:s');


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


            $checksum = md5(($this->get('Email Template Type') == 'Text' ? '' : $this->get('Email Template Editing JSON')).'|'.$this->get('Email Template Text').'|'.$this->get('Email Template Subject'));


            $this->update(
                array(
                    'Email Template Editing Checksum' => $checksum,
                ), 'no_history'
            );


            $history_data = array(
                'History Abstract' => sprintf(_('%s email template created'), '<b>'.$this->get('Name').'</b>'),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );


            return $this;
        } else {
            $this->msg = "Error can not create Email Template";

            print_r($this->db->errorInfo());
            // print $sql;
            exit;
        }
    }

    function get($key, $data = false) {

        if (!$this->id) {
            return '';
        }


        switch ($key) {


            case 'Published Info':
                $data = array(
                    'scope'       => $this->data['Email Template Scope'],
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


        if ($data['Email Template Role'] == 'Invite Mailshot') {
            $sql = sprintf(
                "SELECT `Email Template Key` FROM `Email Template Dimension` WHERE `Email Template Name`=%s AND  `Email Template Scope`=%s AND  `Email Template Scope Key`=%d ", prepare_mysql($data['Email Template Name']), prepare_mysql($data['Email Template Scope']),
                $data['Email Template Scope Key']

            );

        } else {
            $sql = sprintf(
                "SELECT `Email Template Key` FROM `Email Template Dimension` WHERE `Email Template Role`=%s AND  `Email Template Scope`=%s AND  `Email Template Scope Key`=%d ", prepare_mysql($data['Email Template Role']), prepare_mysql($data['Email Template Scope']),
                $data['Email Template Scope Key']

            );
        }

        //print $sql;


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

        $data['Email Blueprint Email Campaign Type Key'] = $this->get('Email Template Email Campaign Type Key');
        $data['Email Blueprint Email Template Key']      = $this->id;


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
                    "SELECT `Email Blueprint Key` FROM `Email Blueprint Dimension`  WHERE `Email Blueprint Role`=%s AND  `Email Blueprint Scope`=%s AND   `Email Blueprint Scope Key`=%s AND `Email Blueprint Name`=%s  ", prepare_mysql($this->get('Email Template Role')),
                    prepare_mysql($this->get('Email Template Scope')), $this->get('Email Template Scope Key'),

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




    function publish() {

        include_once 'class.Published_Email_Template.php';



        $data = array(
            'Published Email Template JSON'               => $this->data['Email Template Editing JSON'],
            'Published Email Template HTML'               => $this->data['Email Template HTML'],
            'Published Email Template Subject'            => $this->data['Email Template Subject'],
            'Published Email Template Text'               => $this->data['Email Template Text'],
            'Published Email Template Email Template Key' => $this->id
        );

        $data['editor'] = $this->editor;


        if ($this->get('Email Template Type') == 'Text') {
            $data['Published Email Template JSON'] = '';
            $data['Published Email Template HTML'] = '';


        }


        $current_published_template = get_object('Published_Email_Template', $this->get('Email Template Published Email Key'));

        if ($current_published_template->id) {

            $checksum = md5($data['Published Email Template JSON'].'|'.$this->get('Email Template Text').'|'.$this->get('Email Template Subject'));


            if ($checksum == $current_published_template->get('Published Email Template Checksum')) {
                $published_template = $current_published_template;
            }
        } else {
            $checksum = md5($data['Published Email Template JSON'].'|'.$this->get('Email Template Text').'|'.$this->get('Email Template Subject'));

        }


        $data['Published Email Template Checksum'] = $checksum;


        // print_r($data);

        if (!isset($published_template)) {
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


        $this->fast_update(
            array(
                'Email Template Editing JSON'        => $data['Published Email Template JSON'],
                'Email Template Editing Checksum'    => $checksum,
                'Email Template Published Checksum'  => $checksum,
                'Email Template Published Email Key' => $published_template->id
            )
        );


        return $published_template;


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

    function delete() {


        $this->deleted = false;


        if ($this->data['Email Template Sent'] > 0) {

            $this->error = true;

            return;
        }


        $sql = sprintf(
            "DELETE FROM `Email Template Dimension` WHERE `Email Template Key`=%d", $this->id
        );
        $this->db->exec($sql);

        $sql = sprintf(
            "DELETE FROM `Email Template History Bridge` WHERE `Email Template Key`=%d", $this->id
        );
        $this->db->exec($sql);


        $this->deleted = true;
    }

    function suspend() {

        $this->update_field_switcher('Email Template State', 'Suspended');

    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        if ($this->deleted) {
            return;
        }

        switch ($field) {
            case 'Email Template Subject':

                $this->update_field($field, $value, $options);

                if ($this->updated) {
                    $checksum = md5(
                        ($this->get('Email Template Type') == 'Text' ? '' : $this->get('Email Template Editing JSON')).'|'.$this->get('Email Template Text').'|'.$value
                    );


                    $update_data = array(
                        'Email Template Last Edited'      => gmdate('Y-m-d H:i:s'),
                        'Email Template Editing Checksum' => $checksum,
                        'Email Template Last Edited By'   => $this->editor['Author Key']

                    );

                    $this->fast_update($update_data);


                    if ($this->data['Email Template Role'] == 'Invite Mailshot') {


                        $published_template = get_object('published_email_template', $this->data['Email Template Published Email Key']);
                        if ($published_template->id) {
                            $published_template->editor = $this->editor;
                            $published_template->fast_update(array('Published Email Template Subject' => $value));
                        }

                    }


                }


                break;

            default:
                $base_data = $this->base_data();
                if (array_key_exists($field, $base_data)) {
                    if ($value != $this->data[$field]) {
                        $this->update_field($field, $value, $options);
                    }
                }


        }
    }

    function activate() {

        $this->update_field_switcher('Email Template State', 'Active');

    }

    function update_sent_emails_totals() {
        return;
        $unsubscribed = 0;
        $open         = 0;
        $sent         = 0;
        $clicked      = 0;
        $errors       = 0;
        $delivered    = 0;
        $hard_bounces = 0;
        $soft_bounces = 0;
        $spam         = 0;


        $sql = sprintf('select count(*) as num ,`Email Tracking State` from `Email Tracking Dimension` where `Email Tracking Email Template Key`=%d group by `Email Tracking State` ', $this->id);
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if (in_array(
                    $row['Email Tracking State'], array(
                                                    'Sent',
                                                    'Sent to SES',
                                                    'Soft Bounce',
                                                    'Hard Bounce',
                                                    'Delivered',
                                                    'Spam',
                                                    'Opened',
                                                    'Clicked'
                                                )
                )) {
                    $sent += $row['num'];

                }

                if (in_array(
                    $row['Email Tracking State'], array(
                                                    'Delivered',
                                                    'Spam',
                                                    'Opened',
                                                    'Clicked'
                                                )
                )) {
                    $delivered += $row['num'];
                }
                if (in_array(
                    $row['Email Tracking State'], array(
                                                    'Opened',
                                                    'Clicked'
                                                )
                )) {
                    $open += $row['num'];
                }
                if ($row['Email Tracking State'] == 'Clicked') {
                    $clicked = $row['num'];
                }
                if ($row['Email Tracking State'] == 'Rejected by SES') {
                    $errors = $row['num'];
                }
                if ($row['Email Tracking State'] == 'Hard Bounce') {
                    $hard_bounces = $row['num'];
                }
                if ($row['Email Tracking State'] == 'Soft Bounce') {
                    $soft_bounces = $row['num'];
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf('select count(*) as num  from `Email Tracking Dimension` where `Email Tracking Email Template Key`=%d and `Email Tracking Spam`="Yes" ', $this->id);
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $spam = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf('select count(*) as num  from `Email Tracking Dimension` where `Email Tracking Email Template Key`=%d and `Email Tracking Unsubscribed`="Yes" ', $this->id);
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $unsubscribed = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $this->fast_update(
            array(
                'Email Template Sent'         => $sent,
                'Email Template Delivered'    => $delivered,
                'Email Template Open'         => $open,
                'Email Template Clicked'      => $clicked,
                'Email Template Errors'       => $errors,
                'Email Template Hard Bounces' => $hard_bounces,
                'Email Template Soft Bounces' => $soft_bounces,
                'Email Template Spams'        => $spam,
                'Email Template Unsubscribed' => $unsubscribed,


            )

        );


    }

}


