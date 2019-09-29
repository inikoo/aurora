<?php
/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 31 May 2018 at 18:26:15 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';

class Email_Tracking extends DB_Table {


    function __construct($arg1 = false, $arg2 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Email Tracking';
        $this->ignore_fields = array(
            'Email Tracking Key',
        );
        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }
        if ($arg1 == 'new') {
            $this->create($arg2);

            return;
        }

        $this->get_data($arg1, $arg2);


    }


    function get_data($key, $tag) {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT *  FROM `Email Tracking Dimension` WHERE `Email Tracking Key`=%d", $tag
            );
        } else {
            return;
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Email Tracking Key'];
        }
    }


    function create($data) {


        $data['Email Tracking Created Date'] = gmdate('Y-m-d H:i:s');


        $keys   = '';
        $values = '';
        foreach ($data as $key => $value) {
            $keys .= ",`".$key."`";
            if (in_array(
                $key, array()
            )) {
                $values .= ','.prepare_mysql($value, true);
            } else {
                $values .= ','.prepare_mysql($value, false);
            }
        }
        $values = preg_replace('/^,/', '', $values);
        $keys   = preg_replace('/^,/', '', $keys);

        $sql = "insert into `Email Tracking Dimension` ($keys) values ($values)";


        if ($this->db->exec($sql)) {
            $this->id = $this->db->lastInsertId();
            $this->get_data('id', $this->id);

            return $this;

        } else {
            $this->error = true;
            $this->msg   = 'Error inserting email tracking record';
        }

    }


    function update_state($event) {

        // Events: 'Rejected by SES','Send','Read','Hard Bounce','Soft Bounce','Spam','Delivered','Opened','Clicked','Send to SES Error'
        // 'Ready','Send to SES','Rejected by SES','Send','Read','Hard Bounce','Soft Bounce','Spam','Delivered','Opened','Clicked','Error'
        switch ($event) {

            case 'Sent':

                if (in_array(
                    $this->data['Email Tracking State'], array(
                                                           'Ready',
                                                           'Sent to SES'
                                                       )
                )) {
                    $this->fast_update(
                        array(
                            'Email Tracking State'=>'Sent',
                            'Email Tracking Sent Date'=>gmdate('Y-m-d H:i:s')
                        )
                    );



                }

                break;

            case 'Delivered':

                if (in_array(
                    $this->data['Email Tracking State'], array(
                                                           'Ready',
                                                           'Sent to SES',
                                                           'Rejected by SES',
                                                           'Sent'
                                                       )
                )) {

                    $this->fast_update(
                        array(
                            'Email Tracking State'=>'Delivered',
                        )
                    );


                }

                break;
            case 'Opened':


                if (in_array(
                    $this->data['Email Tracking State'], array(
                                                           'Ready',
                                                           'Sent to SES',
                                                           'Rejected by SES',
                                                           'Sent',
                                                           'Delivered',
                                                           'Soft Bounce',
                                                           'Hard Bounce',
                                                           'Spam'
                                                       )
                )) {

                    if ($this->data['Email Tracking First Read Date'] == '') {

                        $this->fast_update(
                            array(
                                'Email Tracking First Read Date'=>gmdate('Y-m-d H:i:s'),
                            )
                        );


                    }


                    $this->fast_update(
                        array(
                            'Email Tracking State'          => 'Opened',
                            'Email Tracking Last Read Date' => gmdate('Y-m-d H:i:s')
                        )
                    );


                    $sql = sprintf('select count(*) as num from  `Email Tracking Event Dimension` where `Email Tracking Event Tracking Key`=%d and `Email Tracking Event Type`="Opened" ', $this->id);

                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $this->fast_update(array('Email Tracking Number Reads' => $row['num']));

                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        print "$sql\n";
                        exit;
                    }

                }


                break;
            case 'Clicked':

                if (in_array(
                    $this->data['Email Tracking State'], array(
                                                           'Ready',
                                                           'Sent to SES',
                                                           'Rejected by SES',
                                                           'Sent',
                                                           'Delivered',
                                                           'Soft Bounce',
                                                           'Hard Bounce',
                                                           'Spam',
                                                           'Opened',
                                                           'Clicked'
                                                       )
                )) {

                    if ($this->data['Email Tracking First Clicked Date'] == '') {
                        $this->fast_update(
                            array(
                                'Email Tracking First Clicked Date' => gmdate('Y-m-d H:i:s')
                            )
                        );

                    }


                    $this->fast_update(
                        array(
                            'Email Tracking State'             => 'Clicked',
                            'Email Tracking Last Clicked Date' => gmdate('Y-m-d H:i:s')
                        )
                    );

                    $sql = sprintf('select count(*) as num from  `Email Tracking Event Dimension` where `Email Tracking Event Tracking Key`=%d and `Email Tracking Event Type`="Clicked" ', $this->id);

                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {

                            $this->fast_update(array('Email Tracking Number Clicks' => $row['num']));


                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        print "$sql\n";
                        exit;
                    }


                }

                break;

            case 'Hard Bounce':


                $this->update_field('Email Tracking State', 'Hard Bounce', 'no_history');

                break;
            case 'Soft Bounce':


                $this->update_field('Email Tracking State', 'Soft Bounce', 'no_history');

                break;

            case 'Spam':


                $this->update_field('Email Tracking State', 'Spam', 'no_history');

                break;

        }


    }

    function get($key) {
        switch ($key) {

            case 'State Label':

                switch ($this->data['Email Tracking State']) {
                    case 'NoContacted':
                        $label = ' <span class=" padding_left_10 discreet"><i class="far fa-exclamation-circle"></i> '._('Not contacted yet').'</span>';
                        break;

                    case 'Contacted':
                        $label = ' <span class="padding_left_10 discreet"><i class="far fa-stopwatch"></i> '._('Contacted').'</span>';

                        break;
                    case 'NotInterested':
                        $label = ' <span class="error padding_left_10"><i class="far fa-frown"></i> '._('Not interested').'</span>';

                        break;
                    case 'Registered':
                        $label = ' <span class="success padding_left_10"><i class="far fa-smile"></i> '._('Registered').'</span> 
                                    <span class="button padding_left_10" onClick="change_view(\'customers/'.$this->customer->get('Store Key').'/'.$this->customer->id.'\')"><i class="fa fa-user "></i> '.$this->customer->get_formatted_id().'</span>';

                        break;
                    default:
                        $label = $this->data['Email Tracking State'];
                }

                return $label;

                break;

            case 'Created Date':
            case 'Sent Date':
            case 'First Read Date':
            case 'Last Read Date':
            case 'First Clicked Date':
            case 'Last Clicked Date':

                if ($this->data['Email Tracking '.$key] == '') {
                    return '';
                }

                return '<span title="'.strftime(
                        "%a %e %b %Y %H:%M:%S %Z", strtotime($this->data['Email Tracking '.$key].' +0:00')).'">'.strftime("%a, %e %b %Y %R:%S", strtotime($this->data['Email Tracking '.$key].' +0:00')).'</span>';
                break;

            default:
                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                } else {
                    return '';
                }
        }

    }


}



