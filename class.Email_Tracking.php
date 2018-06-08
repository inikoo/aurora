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


    function Email_Tracking($arg1 = false, $arg2 = false) {

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


    function update_state($event){

        // Events: 'Rejected by SES','Send','Read','Hard Bounce','Soft Bounce','Spam','Delivered','Opened','Clicked','Send to SES Error'
      // 'Ready','Send to SES','Rejected by SES','Send','Read','Hard Bounce','Soft Bounce','Spam','Delivered','Opened','Clicked','Error'
        switch ($event){

            case 'Sent':

                if(in_array($this->data['Email Tracking State'],array('Ready','Sent to SES'))){
                    $this->update_field('Email Tracking State','Sent','no_history');
                    $this->update_field('Email Tracking Sent Date',gmdate('Y-m-d H:i:s'),'no_history');

                }

                break;

            case 'Delivered':

                if(in_array($this->data['Email Tracking State'],array('Ready','Sent to SES','Rejected by SES','Sent'))){
                    $this->update_field('Email Tracking State','Delivered','no_history');

                }

                break;
            case 'Opened':




                if(in_array($this->data['Email Tracking State'],array('Ready','Sent to SES','Rejected by SES','Sent','Delivered','Soft Bounce','Hard Bounce','Spam'))){

                    if($this->data['Email Tracking First Read Date']==''){
                        $this->update_field('Email Tracking First Read Date',gmdate('Y-m-d H:i:s'),'no_history');

                    }

                    $this->update_field('Email Tracking State','Opened','no_history');

                    $this->update_field('Email Tracking Last Read Date',gmdate('Y-m-d H:i:s'),'no_history');
                    $sql=sprintf('select count(*) as num from  `Email Tracking Event Dimension` where `Email Tracking Event Tracking Key`=%d and `Email Tracking Event Type`="Opened" ',$this->id);

                    if ($result=$this->db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $this->update_field('Email Tracking Number Reads',$row['num'],'no_history');

                        }
                    }else {
                        print_r($error_info=$this->db->errorInfo());
                        print "$sql\n";
                        exit;
                    }

                }





                break;
            case 'Clicked':

                if(in_array($this->data['Email Tracking State'],array('Ready','Sent to SES','Rejected by SES','Sent','Delivered','Soft Bounce','Hard Bounce','Spam','Opened','Clicked'))){

                    if($this->data['Email Tracking First Clicked Date']==''){
                        $this->update_field('Email Tracking First Read Date',gmdate('Y-m-d H:i:s'),'no_history');

                    }
                    $this->update_field('Email Tracking State','Clicked','no_history');

                    $this->update_field('Email Tracking Last Clicked Date',gmdate('Y-m-d H:i:s'),'no_history');
                    $sql=sprintf('select count(*) as num from  `Email Tracking Event Dimension` where `Email Tracking Event Tracking Key`=%d and `Email Tracking Event Type`="Clicked" ',$this->id);

                    if ($result=$this->db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $this->update_field('Email Tracking Number Clicks
                            
                            
                            ',$row['num'],'no_history');

                        }
                    }else {
                        print_r($error_info=$this->db->errorInfo());
                        print "$sql\n";
                        exit;
                    }


                }

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
                        $label=$this->data['Email Tracking State'];
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
                        "%a %e %b %Y %H:%M:%S %Z", strtotime($this->data['Email Tracking '.$key]." +00:00")
                    ).'">'.strftime(
                        "%a, %e %b %Y %R:%S", strtotime($this->data['Email Tracking '.$key]." +00:00")
                    ).'</span>';
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


?>
