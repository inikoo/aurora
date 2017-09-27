<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 25 September 2017 at 14:19:21 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2010-2015, Inikoo

 Version 3.0


*/
include_once 'class.DB_Table.php';

class EmailCampaign extends DB_Table {

    var $new = false;
    var $updated_data = array();

    function EmailCampaign($arg1 = false, $arg2 = false, $arg3 = false) {
        $this->table_name    = 'Email Campaign';
        $this->ignore_fields = array(
            'Email Campaign Key',
        );

        if (!$arg1 and !$arg2) {
            $this->error = true;
            $this->msg   = 'No arguments';
        }
        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }


        if (is_array($arg2) and preg_match('/find/i', $arg1)) {
            $this->find($arg2, $arg3);

            return;
        }


        $this->get_data($arg1, $arg2);

    }

    function get_data($tipo, $tag) {


        $sql = sprintf(
            "SELECT * FROM `Email Campaign Dimension` WHERE  `Email Campaign Key`=%d", $tag
        );


        if ($this->data = $this->db->query($sql)->fetch()) {

            $this->id   = $this->data['Email Campaign Key'];
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


        $sql = sprintf(
            "SELECT `Email Campaign Key` FROM `Email Campaign Dimension` WHERE `Email Campaign Store Key`=%d AND `Email Campaign Name`=%s", $raw_data['Email Campaign Store Key'],
            prepare_mysql($raw_data['Email Campaign Name'])
        );


        if ($result=$this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->found_key = $row['Email Campaign Key'];
                $this->found     = true;
        	}
        }else {
        	print_r($error_info=$this->db->errorInfo());
        	print "$sql\n";
        	exit;
        }



        $create = '';
        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }



        if ($create and !$this->found) {
            $this->create($raw_data);
        }

    }

    function create($raw_data) {

        $data         = $this->base_data();

        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {

                    $data[$key] = _trim($value);

            }
        }



        if ($data['Email Campaign Name'] == '') {
            $this->error;
            $this->msg = 'no name';

            return;
        }

        $data['Email Campaign Creation Date'] = date("Y-m-d H:i:s");
        $data['Email Campaign Last Updated Date'] = $data['Email Campaign Creation Date'];
        $data['Email Campaign Status']        = 'Creating';


        $keys   = '(';
        $values = 'values(';
        foreach ($data as $key => $value) {
            $keys .= "`$key`,";
            if ($key = 'Email Campaign Recipients Preview' or $key = 'Email Campaign Scope') {
                $values .= prepare_mysql($value, false).",";
            } else {
                $values .= prepare_mysql($value).",";
            }
        }


        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);


        $sql = "insert into `Email Campaign Dimension` $keys  $values";

        if ($this->db->exec($sql)) {
            $this->id = $this->db->lastInsertId();


            $this->get_data('id', $this->id);
            $this->new = true;

            /*
            $store = new Store($this->data['Email Campaign Store Key']);
            switch ($this->data['Email Campaign Type']) {
                case 'Marketing':
                    //$store->update_email_campaign_data();
                    break;
                case 'Newsletter':
                    //update_newsletter_data();
                    break;
                case 'Reminder':
                    //update_newsletter_data();
                    break;
            }

*/



        } else {
            $this->error = true;
            $this->msg   = "Can not insert Email Campaign Dimension";
            // exit("$sql\n");
        }


    }

    function ready_to_send() {
        $ready_to_send = true;


        if (!$this->data['Number of Emails']) {

            return false;
        }
        if (!count($this->content_keys)) {

            return false;
        }

        foreach ($this->content_data as $content_data) {
            if ($content_data['subject'] == '') {

                $ready_to_send = false;
            }

            if ($content_data['type'] == 'Plain') {
                if ($content_data['plain'] == '') {
                    $ready_to_send = false;
                }
            } elseif ($content_data['type'] == 'HTML') {
                if ($content_data['html'] == '') {
                    $ready_to_send = false;
                }
            } else {
                if (!count($content_data['paragraphs'])) {
                    $ready_to_send = false;
                }
            }
        }


        return $ready_to_send;

    }

    function delete_email_address($email_address_key) {


        $sql = sprintf(
            "DELETE FROM  `Email Campaign Mailing List` WHERE `Email Campaign Mailing List Key`=%d AND `Email Campaign Key`=%d", $email_address_key, $this->id
        );
        $res = mysql_query($sql);

        if (mysql_affected_rows()) {
            $this->updated = true;
            $this->update_number_emails();
            $this->update_recipients_preview();
        } else {
            $this->msg = 'can not delete recipient';

        }

    }

    function update_number_emails() {
        $this->data['Number of Emails'] = 0;
        $sql                            = sprintf(
            "SELECT count(*) AS number FROM `Email Campaign Mailing List` WHERE `Email Campaign Key`=%d", $this->id
        );
        $res                            = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res)) {
            $this->data['Number of Emails'] = $row['number'];
        }
        $sql = sprintf(
            "UPDATE `Email Campaign Dimension` SET `Number of Emails`=%d WHERE `Email Campaign Key`=%d", $this->data['Number of Emails'], $this->id
        );
        mysql_query($sql);
    }

    function update_recipients_preview() {
        $this->data['Email Campaign Recipients Preview'] = '';
        $sql                                             = sprintf(
            "SELECT `Email Address` FROM `Email Campaign Mailing List` WHERE `Email Campaign Key`=%d", $this->id
        );
        $res                                             = mysql_query($sql);
        $num_previews_emails                             = 0;
        while ($row = mysql_fetch_assoc($res)) {
            $num_previews_emails++;
            $this->data['Email Campaign Recipients Preview'] .= ', '.$row['Email Address'];
            if (strlen($this->data['Email Campaign Recipients Preview']) > 250 and $this->data['Number of Emails'] - $num_previews_emails > 1) {
                break;
            }
        }
        $num_emails_not_previewed = $this->data['Number of Emails'] - $num_previews_emails;
        if ($num_emails_not_previewed > 0) {
            $this->data['Email Campaign Recipients Preview'] .= ", ... $num_emails_not_previewed "._('more');
        } else {
            $this->data['Email Campaign Recipients Preview'];

        }

        $this->data['Email Campaign Recipients Preview'] = preg_replace(
            '/^\,\s*/', '', $this->data['Email Campaign Recipients Preview']
        );
        $sql                                             = sprintf(
            "UPDATE `Email Campaign Dimension` SET `Email Campaign Recipients Preview`=%s WHERE `Email Campaign Key`=%d", prepare_mysql($this->data['Email Campaign Recipients Preview']), $this->id
        );
        mysql_query($sql);
    }

    function add_email_address_manually($data) {
        $data['Email Address'] = _trim($data['Email Address']);
        if ($data['Email Address'] == '') {
            $this->error = true;
            $this->msg   = _('Wrong Email Address');

            return;
        }

        $sql = sprintf(
            "SELECT `Email Campaign Mailing List Key` FROM `Email Campaign Mailing List` WHERE `Email Campaign Key`=%d AND `Email Address`=%s ", $this->id, prepare_mysql($data['Email Address'])
        );
        $res = mysql_query($sql);
        //  print $sql;
        if ($row = mysql_fetch_assoc($res)) {
            $this->error = true;
            $this->msg   = _('Email Address already in mailing list');

            return;

        }

        $data['Customer Key'] = false;

        if ($this->insert_email_to_mailing_list($data) > 0) {
            $this->updated = true;
            $this->update_number_emails();
            $this->update_recipients_preview();
        } else {
            $this->msg = _('Can not add email to mailing list');
        }

    }

    function insert_email_to_mailing_list($data) {

        if (!array_key_exists('Email Key', $data)) {
            $email = new Email('email', $data['Email Address']);
            if ($email->id) {
                $data['Email Key'] = $email->id;
            } else {
                $data['Email Key'] = false;
            }
        }

        $email_content_key = $this->assign_email_content_key();

        $sql = sprintf(
            "INSERT INTO `Email Campaign Mailing List` (`Email Campaign Key`,`Email Key`,`Email Address`,`Email Contact Name`,`Customer Key`,`Email Content Key`)
                     VALUES (%d,%s,%s,%s,%s,%d)", $this->id, prepare_mysql($data['Email Key']), prepare_mysql($data['Email Address']), prepare_mysql($data['Email Contact Name'], false),
            prepare_mysql($data['Customer Key']), $email_content_key

        );
        mysql_query($sql);

        //  print $sql;
        return mysql_affected_rows();

    }

    function assign_email_content_key() {

        return $this->get_first_content_key();
    }

    function get_first_content_key() {
        $tmp = $this->content_keys;

        return array_shift($tmp);
    }

    function add_emails_from_list($list_key, $force_send_to_customer_who_dont_want_to_receive_email = false) {
        $sql = sprintf(
            "SELECT * FROM `List Dimension` WHERE `List Key`=%d", $list_key
        );
        $res = mysql_query($sql);
        if (!$customer_list_data = mysql_fetch_assoc($res)) {
            $this->error = true;
            $this->msg   = 'List not found';

            return;
        }
        $emails_already_in_the_mailing_list          = 0;
        $emails_added                                = 0;
        $customer_without_email_address              = 0;
        $customer_dont_want_to_receive_email         = 0;
        $sent_to_customer_dont_want_to_receive_email = 0;
        $group                                       = '';
        if ($customer_list_data['List Type'] == 'Static') {

            $sql = sprintf(
                "SELECT `Customer Main Contact Name`,C.`Customer Key`,`Customer Main Plain Email`,`Customer Send Email Marketing` FROM `List Customer Bridge` B LEFT JOIN `Customer Dimension` C ON (B.`Customer Key`=C.`Customer Key`) WHERE `List Key`=%d ",
                $list_key
            );


        } else {//dynamic

            $where = 'where true';
            $table = '`Customer Dimension` C ';

            $tmp = preg_replace(
                '/\\\"/', '"', $customer_list_data['List Metadata']
            );
            $tmp = preg_replace('/\\\\\"/', '"', $tmp);
            $tmp = preg_replace('/\'/', "\'", $tmp);

            $raw_data = json_decode($tmp, true);
            include_once 'list_functions_customer.php';
            list($where, $table, $group) = customers_awhere($raw_data);

            $where .= sprintf(
                ' and `Customer Store Key`=%d ', $this->data['Email Campaign Store Key']
            );


            $sql = sprintf(
                "select `Customer Main Contact Name`,C.`Customer Key`,`Customer Main Plain Email`,`Customer Send Email Marketing` from $table $where $group "

            );

        }


        $res = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            if (!$row['Customer Main Email Key'] or $row['Customer Main Plain Email'] == '') {
                $customer_without_email_address++;
                continue;
            }
            if ($row['Customer Send Email Marketing'] == 'No') {
                $customer_dont_want_to_receive_email++;
                if (!$force_send_to_customer_who_dont_want_to_receive_email) {
                    continue;
                } else {
                    $sent_to_customer_dont_want_to_receive_email++;
                }
            }

            $data['Email Address']      = $row['Customer Main Plain Email'];
            $data['Email Key']          = $row['Customer Main Email Key'];
            $data['Email Contact Name'] = $row['Customer Main Contact Name'];

            $data['Customer Key'] = $row['Customer Key'];
            $result               = $this->insert_email_to_mailing_list($data);
            if ($result > 0) {
                $emails_added++;

            } else {
                $emails_already_in_the_mailing_list++;

            }

        }


        $msg = '<table>';
        $msg .= '<tr><td>'._('Email Address Added').':</td><td>'.number(
                $emails_added
            ).'</td></tr>';

        if ($customer_without_email_address) {
            $msg .= '<tr><td>'._('Customers without email').':</td><td>'.$customer_without_email_address.'</td></tr>';
        }
        if ($customer_dont_want_to_receive_email) {
            $msg .= '<tr><td>'._('Skipped (Customer preferences)').':</td><td>'.$customer_dont_want_to_receive_email.'</td></tr>';
        }
        if ($emails_already_in_the_mailing_list) {
            $msg .= '<tr><td>'._('Skipped (Email already added)').':</td><td>'.$emails_already_in_the_mailing_list.'</td></tr>';
        }
        $msg .= '</table>';
        $this->msg = $msg;


        $this->updated = true;
        $this->update_number_emails();
        $this->update_recipients_preview();


    }

    function delete() {
        if ($this->data['Email Campaign Status'] == 'Creating') {
            $content_keys = $this->get_content_data_keys();
            $sql          = sprintf(
                "DELETE FROM `Email Campaign Content Bridge` WHERE `Email Campaign Key`=%d", $this->id
            );
            mysql_query($sql);
            $sql = sprintf(
                "DELETE FROM `Email Campaign Dimension` WHERE `Email Campaign Key`=%d", $this->id
            );
            mysql_query($sql);
            $sql = sprintf(
                "DELETE FROM `Email Campaign Mailing List` WHERE `Email Campaign Key`=%d", $this->id
            );
            mysql_query($sql);
            $sql = sprintf(
                "DELETE FROM `Email Content Dimension` WHERE `Email Content Key` IN (%s)", join(',', $content_keys)
            );
            mysql_query($sql);
            $this->updated = true;

        } else {
            $this->error = true;
            $this->msg   = 'Email Campaign can not be deleted';
        }

    }



    function get($key) {


        switch ($key) {
            case('Email Campaign Content Type'):
                return $this->get_content_type();
                break;
            default:
                if (isset($this->data[$key])) {
                    return $this->data[$key];
                }
        }

        return false;
    }


    function get_recipient_email($email_mailing_list_key = false) {
        if (!$email_mailing_list_key) {
            $email_mailing_list_key = $this->get_first_mailing_list_key();
        }

        $sql = sprintf(
            "SELECT `Email Address` FROM `Email Campaign Mailing List` WHERE `Email Campaign Mailing List Key`=%d AND `Email Campaign Key`=%d", $email_mailing_list_key, $this->id
        );
        $res = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res)) {
            return $row['Email Address'];
        } else {
            return '';
        }

    }

    function get_first_mailing_list_key() {

        $sql = sprintf(
            "SELECT `Email Campaign Mailing List Key` FROM `Email Campaign Mailing List` WHERE  `Email Campaign Key`=%d LIMIT 1",

            $this->id
        );
        $res = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res)) {
            return $row['Email Campaign Mailing List Key'];

        } else {
            return 0;

        }


    }

    function get_email_mailing_list_key_from_index($index) {


        $sql = sprintf(
            "SELECT `Email Campaign Mailing List Key` FROM `Email Campaign Mailing List` WHERE `Email Campaign Key`=%d LIMIT %d, 1 ",

            $this->id, ($index - 1)
        );

        $res = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res)) {
            return $row['Email Campaign Mailing List Key'];

        } else {
            return 0;
        }


    }

    function consolidate() {


        foreach ($this->content_data as $content_data_key => $content_data) {

            // print_r($content_data);


            switch ($content_data['type']) {
                case 'HTML':
                    $html = $this->get_content_html($content_data_key);
                    break;
                case 'HTML Template':
                    $html = '';

                    foreach ($content_data['paragraphs'] as $paragraph_data) {
                        $html .= $paragraph_data['title'].' '.$paragraph_data['subtitle'].' '.$paragraph_data['content'];
                    }

                    break;
                default:
                    return;
                    break;
            }
            $links  = array();
            $regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>";
            if (preg_match_all(
                "/$regexp/siU", $html, $matches, PREG_SET_ORDER
            )) {
                foreach ($matches as $match) {

                    $url        = preg_replace(
                        "/^https?\:\/\//", '', $match[2]
                    );
                    $link_label = $match[3];

                    $links[$url] = $link_label;


                }
            }


            //print_r($links);
            //exit;


            if ($content_data['type'] == 'HTML Template') {

                if (!$content_data['header_image_key']) {
                    if ($content_data['template_type'] == 'Postcard') {
                        $header_src
                            = $content_data['color_scheme']['Header_Slim_Image_Source'];
                    } else {
                        $header_src
                            = $content_data['color_scheme']['Header_Image_Source'];
                    }

                    $data = array(
                        'file'        => $header_src,
                        'source_path' => '',
                        'name'        => basename($header_src),
                        'caption'     => ''
                    );

                    //print_r($data);
                    $image = new Image('find', $data, 'create');

                    if (!$image->id) {

                        print_r($image);
                        exit;

                    }


                    $sql = sprintf(
                        "SELECT `Email Template Header Image Key` FROM `Email Template Header Image Dimension` WHERE `Store Key`=%d AND `Image Key`=%d", $this->data['Email Campaign Store Key'],
                        $image->id
                    );


                    $res = mysql_query($sql);
                    if ($row = mysql_fetch_assoc($res)) {

                        $_header_image_key
                            = $row['Email Template Header Image Key'];
                    } else {


                        $sql = sprintf(
                            "INSERT INTO `Email Template Header Image Dimension` (`Email Template Header Image Name`,`Store Key`,`Image Key`) VALUES (%s,%d,%d) ", prepare_mysql(basename($header_src)),
                            $this->data['Email Campaign Store Key'], $image->id

                        );
                        mysql_query($sql);
                        $_header_image_key = mysql_insert_id();
                    }

                    $sql = sprintf(
                        "UPDATE `Email Content Dimension` SET `Email Template Header Image Key`=%d WHERE `Email Content Key`=%d", $_header_image_key, $content_data_key
                    );
                    mysql_query($sql);


                    //print $sql;


                }

                if ($content_data['template_type'] == 'Postcard' and !$content_data['postcard_image_key']) {

                    $postcard_src
                        = $content_data['color_scheme']['Postcard_Image_Source'];


                    $data = array(
                        'file'        => $postcard_src,
                        'source_path' => '',
                        'name'        => 'email_postcard_'.$this->id.'_'.$content_data_key,
                        'caption'     => ''
                    );

                    //print_r($data);
                    $image = new Image('find', $data, 'create');

                    if (!$image->id) {

                        print_r($image);
                        exit;

                    }

                    $sql = sprintf(
                        "UPDATE `Email Content Dimension` SET `Email Content Template Postcard Key`=%d WHERE `Email Content Key`=%d", $image->id, $content_data_key
                    );
                    mysql_query($sql);


                }


                $base_data = array();

                foreach ($content_data['color_scheme'] as $key => $value) {


                    if (!($key == 'Email_Template_Color_Scheme_Key' or $key == 'Email_Template_Color_Scheme_Name' or $key == 'Header_Image_Source' or $key == 'Store_Key')) {
                        $key             = preg_replace("/_/", " ", $key);
                        $base_data[$key] = $value;
                    }

                }

                $where           = '';
                $historic_keys   = '';
                $historic_values = '';
                foreach ($base_data as $_key => $_value) {
                    $where .= sprintf(
                        " and `%s`=%s", $_key, prepare_mysql($_value)
                    );
                    $historic_keys .= ",`$_key`";
                    $historic_values .= ",".prepare_mysql($_value);
                }
                $where           = preg_replace('/^ and/', '', $where);
                $historic_keys   = preg_replace('/^,/', '', $historic_keys);
                $historic_values = preg_replace('/^,/', '', $historic_values);
                $sql
                                 = "select `Email Template Historic Color Scheme Key` from `Email Template Historic Color Scheme Dimension` where $where";


                $res = mysql_query($sql);
                if ($row = mysql_fetch_assoc($res)) {

                    $historic_color_scheme
                        = $row['Email Template Historic Color Scheme Key'];
                } else {


                    $sql
                        = "insert into `Email Template Historic Color Scheme Dimension`($historic_keys) values ($historic_values) ";
                    mysql_query($sql);
                    $historic_color_scheme = mysql_insert_id();

                }
                $sql = sprintf(
                    "UPDATE `Email Content Dimension` SET `Email Content Color Scheme Historic Key`=%d WHERE `Email Content Key`=%d", $historic_color_scheme, $content_data_key
                );
                mysql_query($sql);

            }


        }


    }



    function get_message_data($email_mailing_list_key = false, $smarty = false, $inikoo_public_path = '') {

        $this->get_data('id', $this->id);

        if (!$email_mailing_list_key) {
            $email_mailing_list_key = $this->get_first_mailing_list_key();
        }
        include_once 'class.Customer.php';

        $sql   = sprintf(
            "SELECT * FROM `Email Campaign Mailing List` WHERE `Email Campaign Mailing List Key`=%d AND `Email Campaign Key`=%d", $email_mailing_list_key, $this->id
        );
        $res   = mysql_query($sql);
        $plain = '';
        $html  = '';
        $to    = '';
        if ($row = mysql_fetch_assoc($res)) {

            $to = $row['Email Address'];

            $email_content_key = $row['Email Content Key'];
            $customer          = new Customer($row['Customer Key']);
            if (!$customer->id) {
                $customer->data['Customer Main Contact Name']
                    = $row['Email Contact Name'];
                $customer->data['Customer Name']
                    = $row['Email Contact Name'];
                $customer->data['Customer Main Plain Email']
                    = $row['Email Address'];

                $customer->data['Customer Type'] = 'person';

            }

            switch ($type = $this->content_data[$email_content_key]['type']) {
                case 'Plain':
                    $plain = nl2br(
                        $this->content_data[$email_content_key]['plain']
                    );
                    $html  = '';
                    break;
                case 'HTML':
                    $plain = nl2br(
                        $this->content_data[$email_content_key]['plain']
                    );
                    $html  = nl2br(
                        $this->content_data[$email_content_key]['html']
                    );
                    break;
                case 'HTML Template':
                    $plain = nl2br(
                        $this->content_data[$email_content_key]['plain']
                    );


                    $html_data = array(
                        'smarty'             => $smarty,
                        'css_files'          => array(),
                        'js_files'           => array(),
                        'output_type'        => 'consolidated',
                        'inikoo_public_path' => $inikoo_public_path
                    );

                    $html = $this->get_templete_html(
                        $html_data, $email_mailing_list_key
                    );

                    break;
                default:

                    break;
            }

            if (preg_match_all('/\%[a-z]+\%/', $plain, $matches)) {
                foreach ($matches[0] as $match) {
                    $plain = preg_replace(
                        '/'.$match.'/', $customer->get(preg_replace('/\%/', '', $match)), $plain
                    );
                }
            }
            if (preg_match_all('/\%[a-z]+\%/', $html, $matches)) {
                foreach ($matches[0] as $match) {
                    $html = preg_replace(
                        '/'.$match.'/', $customer->get(preg_replace('/\%/', '', $match)), $html
                    );
                }
            }
            $subject = $this->get_subject($email_content_key);
            $ok      = true;
        } else {
            $plain   = 'Error recipient not associated with mailing list';
            $html    = 'Error recipient not associated with mailing list';
            $type    = false;
            $subject = '';
            $ok      = false;
        }


        return array(
            'ok'      => $ok,
            'subject' => $subject,
            'plain'   => $plain,
            'html'    => $html,
            'type'    => $type,
            'to'      => $to
        );
    }



    function update_send_emails() {
        $this->data['Number of Read Emails'] = 0;
        $sql                                 = sprintf(
            "SELECT count(*) AS number FROM `Email Send Dimension` WHERE `Email Send Date` IS NOT NULL  AND  `Email Send Type`='Marketing' AND `Email Send Type Parent Key`=%d", $this->id
        );
        //print $sql;
        $res = mysql_query($sql);
        if ($row = mysql_fetch_assoc($res)) {
            $this->data['Number of Read Emails'] = $row['number'];
        }
        $sql = sprintf(
            "UPDATE `Email Campaign Dimension` SET `Number of Read Emails`=%d WHERE `Email Campaign Key`=%d", $this->data['Number of Read Emails'], $this->id
        );
        mysql_query($sql);
    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        switch ($field) {
            case('Email Campaign Content Text'):
                $this->update_content_text($value);
                break;
            case('Email Campaign Subject'):
                $this->update_subject($value);
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



    function add_objective($scope_data) {

        $scope_data['Email Campaign Key'] = $this->id;

        switch ($scope_data['Email Campaign Objective Parent']) {
            case 'Department':
                $parent        = new Department(
                    $scope_data['Email Campaign Objective Parent Key']
                );
                $parent_key    = $parent->id;
                $parent_name   = $parent->data['Product Department Name'];
                $term          = 'Order';
                $term_metadata = '0;;432000';
                break;
            case 'Family':
                $parent        = new Family(
                    $scope_data['Email Campaign Objective Parent Key']
                );
                $parent_key    = $parent->id;
                $parent_name   = '<b>'.$parent->data['Product Family Code'].'</b>, '.$parent->data['Product Family Name'];
                $term          = 'Order';
                $term_metadata = '0;;432000';
                break;
            case 'Store':
                $parent        = new Store(
                    $scope_data['Email Campaign Objective Parent Key']
                );
                $parent_key    = $parent->id;
                $parent_name   = $parent->data['Product Store Name'];
                $term          = 'Order';
                $term_metadata = '0;;432000';
                break;
            case 'Product':
                $parent        = new Product(
                    'pid', $scope_data['Email Campaign Objective Parent Key']
                );
                $parent_key    = $parent->pid;
                $parent_name   = '<b>'.$parent->data['Product Code'].'</b>, '.$parent->data['Product Name'];
                $term          = 'Order';
                $term_metadata = '0;;432000';
                break;
            case 'Deal':
                $parent        = new Deal(
                    $scope_data['Email Campaign Objective Parent Key']
                );
                $parent_key    = $parent->pid;
                $parent_name   = $parent->data['Deal Name'];
                $term          = 'Use';
                $term_metadata = '432000';
                break;
            case 'External Link':
                $parent_key    = 0;
                $parent_name
                               = $scope_data['Email Campaign Objective Parent Name'];
                $term          = 'Visit';
                $term_metadata = '432000';
                break;

            default:
                return;
                break;
        }

        $found = false;

        if ($scope_data['Email Campaign Objective Parent'] != 'External Link') {


            $sql = sprintf(
                "SELECT `Email Campaign Objective Key` FROM `Email Campaign Objective Dimension` WHERE `Email Campaign Key`=%d  AND `Email Campaign Objective Parent`=%s  AND  `Email Campaign Objective Parent Key`=%d ",
                $this->id, prepare_mysql($scope_data['Email Campaign Objective Parent']), $parent_key
            );
            $res = mysql_query($sql);
            if ($row = mysql_fetch_assoc($res)) {
                $found = $row['Email Campaign Objective Key'];

            }

        }
        if ($found) {
            if ($scope_data['Email Campaign Objective Type'] == 'Link') {
                $sql = sprintf(
                    "UPDATE `Email Campaign Objective Dimension` SET `Email Campaign Objective Type`='Link'  WHERE `Email Campaign Key`=%d ", $found
                );

            }

        } else {
            $sql = sprintf(
                "INSERT INTO `Email Campaign Objective Dimension` (`Email Campaign Key`,`Email Campaign Objective Type`,`Email Campaign Objective Parent`,`Email Campaign Objective Parent Key`,`Email Campaign Objective Name`,`Email Campaign Objective Links`,`Email Campaign Objective Links Clicks`,`Email Campaign Objective Term`,`Email Campaign Objective Term Metadata`)  VALUES (%d,%s,%s,%d,%s,0,0,%s,%s)  ",
                $this->id, prepare_mysql($scope_data['Email Campaign Objective Type']), prepare_mysql($scope_data['Email Campaign Objective Parent']),

                $parent_key, prepare_mysql($parent_name), prepare_mysql($term), prepare_mysql($term_metadata)

            );
            mysql_query($sql);

        }


        //     print $sql;

    }


    function set_as_ready($lag_seconds) {

        if ($this->data['Email Campaign Status'] == 'Sending') {
            $this->error = true;
            $this->msg   = _('Campaign already sending emails');

            return;
        }
        if ($this->data['Email Campaign Status'] == 'Complete') {
            $this->error = true;
            $this->msg   = _('Campaign already send');

            return;
        }

        $this->data['Email Campaign Status']             = 'Ready';
        $this->data['Email Campaign Start Overdue Date'] = date(
            "Y-m-d H:i:s", strtotime(sprintf('now +%d seconds ', $lag_seconds))
        );
        $sql                                             = sprintf(
            "UPDATE `Email Campaign Dimension` SET `Email Campaign Status`='Ready'  , `Email Campaign Start Overdue Date`=%s ", prepare_mysql($this->data['Email Campaign Start Overdue Date'])

        );

        //print $sql;
        mysql_query($sql);

    }


}


?>
