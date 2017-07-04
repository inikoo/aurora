<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 December 2016 at 13:26:41 GMT+8, Kuta, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/


include_once 'class.DBW_Table.php';

class Public_Website_User extends DB_Table {


    function Public_Website_User($a1 = 'id', $a2 = false, $a3 = false) {
        global $db;
        $this->db = $db;

        $this->table_name    = 'Website User';



        if (($a1 == 'new') and is_array($a2)) {
            $this->create($a2);

            return;
        }



        if (is_numeric($a1) and !$a2) {
            $_data = $a1;
            $key   = 'id';
        } else {
            $_data = $a2;
            $key   = $a1;
        }

        $this->get_data($key, $_data, $a3);

        return;
    }


    function get_data($type, $key) {


        if ($type == 'handle') {
            $sql = sprintf(
                "SELECT * FROM  `Website User Dimension` WHERE `Website User Handle`=%s ", prepare_mysql($key)
            );
        }  else {
            $sql = sprintf(
                "SELECT * FROM `Website User Dimension` WHERE `Website User Key`=%d", $key
            );
        }



        if ($this->data = $this->db->query($sql)->fetch()) {


            $this->id                    = $this->data['Website User Key'];
            $this->data['Website User Password'] = '';



        }


    }



    function create($data) {



        $this->new = false;
        $this->msg = _('Unknown Error').' (0)';
        $base_data = $this->base_data();

        foreach ($data as $key => $value) {
            if (array_key_exists($key, $base_data)) {
                $base_data[$key] = _trim($value);
            }
        }

        $this->editor = $data['editor'];



        if ($base_data['Website User Handle'] == '') {
            $this->msg = "Login can't be empty";

            return;
        }






        $sql = sprintf(
            "SELECT count(*) AS num  FROM `Website User Dimension` WHERE `Website User Handle`=%s and `Website User Website Key`=%d ",
            prepare_mysql($base_data['Website User Handle']), $base_data['Website User Website Key']
        );



        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                if ($row['num'] > 0) {
                    $this->error = true;
                    $this->msg   = 'Duplicate user login';

                    return;
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        $base_data['Website User Created'] = gmdate("Y-m-d H:i:s");

        $base_data['Website User Password Hash'] =password_hash($base_data['Website User Password'], PASSWORD_DEFAULT, array('cost'=>12));




        $keys   = '(';
        $values = 'values(';
        foreach ($base_data as $key => $value) {
            $keys .= "`$key`,";
                $values .= prepare_mysql($value).",";

        }
        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);
        $sql    = sprintf("INSERT INTO `Website User Dimension` %s %s", $keys, $values);




        if ($this->db->exec($sql)) {

            $user_key = $this->db->lastInsertId();
            $this->get_data('id', $user_key);

            $sql    = sprintf("INSERT INTO `Website User Data` (`Website User Key`) values (%d)", $user_key);
            $this->db->exec($sql);

            $this->new = true;


            $history_data = array(
                'History Abstract' => sprintf(_('Website user %s created'), $this->get('Handle')),
                'History Details'  => '',
                'Action'           => 'created',
                'Subject'=>'Customer',
                'Subject Key'=>$this->data['Website User Customer Key'],
                'Author Name'=>_('Customer')

            );




            $this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id);

            $this->msg = 'User added successfully';





            return $this;
        } else {
            $this->error = true;
            $this->msg   = _('Unknown error').' (2)';

            return;
        }




    }

    function get($key) {


        if (!$this->id) {
            return;
        }


        switch ($key) {
            case 'Website User Customer Key':
                return $this->data[$key];
                break;

            default:


        }

    }




    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        if (is_string($value)) {
            $value = _trim($value);
        }

        switch ($field) {






            default:



        }

    }


    function forgot_password() {


        //global $secret_key,$public_url;

        $sql = sprintf(
            "SELECT `Site Secret Key`,`Site URL` FROM `Site Dimension` WHERE `Site Store Key`=%s", $this->data['User Site Key']
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $secret_key = $row['Site Secret Key'];
                $url        = $row['Site URL'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $user_key = $this->data['User Key'];


        if ($user_key) {


            $user     = new User($user_key);
            $customer = new Customer($this->data['User Parent Key']);


            $email_credential_key = 1;


            $signature_name    = '';
            $signature_company = '';

            $master_key = $user_key.'x'.generatePassword(6, 10);


            $sql = sprintf(
                "INSERT INTO `MasterKey Dimension` (`Key`,`User Key`,`Valid Until`,`IP`) VALUES (%s,%d,%s,%s) ", prepare_mysql($master_key), $user_key,
                prepare_mysql(date("Y-m-d H:i:s", strtotime("now +24 hours"))), prepare_mysql(ip())
            );

            $this->db->exec($sql);


            //json_encode(array('D'=>generatePassword(2,10).date('U') ,'C'=>$user_key ));
            //$encrypted_secret_data=base64_encode(AESEncryptCtr($secret_data,$secret_key.$store_key,256));


            $encrypted_secret_data = base64_encode(
                AESEncryptCtr($master_key, $secret_key, 256)
            );


            $plain_message = $customer->get('greetings')
                ."\n\n We received request to reset the password associated with this email account.\n\nIf you did not request to have your password reset, you can safely ignore this email. We assure that yor customer account is safe.\n\nCopy and paste the following link to your browser's address window.\n\n "
                .$url."?p=".$encrypted_secret_data."\n\n Once you hace returned our page you will be asked to choose a new password\n\nThank you \n\n".$signature_name."\n".$signature_company;


            $html_message = $customer->get('greetings')."<br/>We received request to reset the password associated with this email account.<br><br>
		If you did not request to have your password reset, you can safely ignore this email. We assure that yor customer account is safe.<br><br>
		<b>Click the link below to reset your password</b>
		<br><br>
		<a href=\"".$url."?p=".$encrypted_secret_data."\">".$url."?p=".$encrypted_secret_data."</a>
		<br></br>
		If clicking the link doesn't work you can copy and paste it into your browser's address window. Once you have returned to our website, you will be asked to choose a new password.
		<br><br>
		Thank you";


            //$to='rulovico@gmail.com';
            $to   = 'migara@inikoo.com';
            $data = array(
                'type'                  => 'HTML',
                'subject'               => 'Reset your password',
                'plain'                 => $plain_message,
                'email_credentials_key' => $email_credential_key,
                'to'                    => $to,
                'html'                  => $html_message,

            );


            $send_email = new SendEmail();

            $send_email->smtp('HTML', $data);

            $result = $send_email->send();

            if ($result['msg'] == 'ok') {
                $response = array(
                    'state'  => 200,
                    'result' => 'send'
                );
                echo json_encode($response);
                exit;

            } else {
                print_r($result);
                $response = array(
                    'state'  => 200,
                    'result' => 'error'
                );
                echo json_encode($response);
                exit;
            }


        } else {
            $response = array(
                'state'  => 200,
                'result' => 'handle_not_found'
            );
            echo json_encode($response);
            exit;
        }

    }




}


?>
