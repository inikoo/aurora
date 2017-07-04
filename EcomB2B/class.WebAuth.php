<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 July 2017 at 16:16:40 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

class WebAuth {


    function WebAuth() {

        global $db;
        $this->db = $db;


    }


    function authenticate_from_register($website_user_key, $customer_key, $website_key) {


        $website_user_log_key=$this->log_login('Register', $website_key, $website_user_key, $customer_key, $remember_me = false);


        return array(true,$website_user_log_key);


    }

    function log_login($authentication_type, $website_key, $web_user_key, $customer_key, $remember_me) {


        $ip   = ip();
        $date = gmdate('Y-m-d H:i:s');
        $sql  = sprintf(
            "INSERT INTO `Website User Log Dimension` (`Website User Log User Key`,`Website User Log Session ID`, `Website User Log IP`, `Website User Log Start Date`,`Website User Log Last Visit Date`, `Website User Log Logout Date`,`Website User Log Remember Cookie`,`Website User Log Website Key`) VALUES (%d, %s, %s, %s,%s, %s,%s,%d)",
            $web_user_key, prepare_mysql(session_id()), prepare_mysql($ip), prepare_mysql($date), prepare_mysql($date), 'NULL', prepare_mysql(($remember_me ? 'Yes' : 'No')), $website_key
        );


        $this->db->exec($sql);

        $website_user_log_key = $this->db->lastInsertId();


        if ($authentication_type == 'Login') {

            $sql = sprintf("SELECT `Website User Login Count` FROM `Website User Data`  WHERE `Website User Key`=%d", $web_user_key);

            $number_logs = 0;


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $number_logs = $row['Website User Login Count'];
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }

            $number_logs++;


            $sql = sprintf("UPDATE `Website User Dimension` SET `Website User Has Login`='Yes'  WHERE `Website User Key`=%d", $web_user_key);

            $this->db->exec($sql);

            $sql = sprintf(
                "UPDATE `Website User Data` SET `Website User Login Count`=%d, `Website User Last Login IP`=%s,`Website User Last Login`=%s WHERE `Website User Key`=%d", $number_logs,
                prepare_mysql($ip), prepare_mysql($date), $web_user_key
            );

            $this->db->exec($sql);


        } else {
            $sql = sprintf(
                "UPDATE `Website User Data` SET  `Website User Last Login IP`=%s,`Website User Last Login`=%s WHERE `Website User Key`=%d", prepare_mysql($ip), prepare_mysql($date), $web_user_key
            );


            $this->db->exec($sql);
        }


        /*

        $customer = new Customer($this->user_parent_key);
        $details  = '
			   <div class="table">
				<div class="field tr"><div>'._('Time').':</div><div>'.strftime(
                "%c %Z", strtotime($date.' +00:00')
            ).'</div></div>
				<div class="field tr"><div>'._('IP Address').':</div><div>'.$ip.'</div></div>
				<div class="field tr"><div>'._('User Agent').':</div><div>'.(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '').'</div></div>
				</div>';

        switch ($authentication_type) {
            case('master_key'):
                $note = _('Logged in from reset password email');
                break;
            default:
                $note = _('Logged in');
        }

        if ($remember_me) {
            $note .= ', '._('remember me cookie set');
        }

        $history_data = array(
            'Date'            => $date,
            'Site Key'        => $website_key,
            'Note'            => $note,
            'Details'         => $details,
            'Action'          => 'login',
            'Indirect Object' => '',
            'User Key'        => $web_user_key
        );

        $customer->add_history_login($history_data);
        $customer->update_web_data();

        */

        return $website_user_log_key;


    }

    function authenticate_from_login($handle, $password, $website_key, $remember_me) {

        $pass_tests = true;
        $tests      = array(
            'handle'        => false,
            'handle_active' => false,
            'password'      => false

        );

        $website_user_key = '';
        $customer_key     = '';

        $sql = sprintf(
            "SELECT `Website User Key`,`Website User Customer Key`,`Website User Password`,`Website User Active` FROM `Website User Dimension` WHERE `Website User Handle`=%s  AND `Website User Website Key` ",
            prepare_mysql($handle), $website_key
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $tests['handle']        = true;
                $tests['handle_active'] = ($row['Website User Active'] == 'Yes' ? true : false);
                $website_user_key       = $row['Website User Key'];
                $customer_key           = $row['Website User Customer Key'];


                //if (password_verify($password, ($row['Website User Password Hash'])) {
                if ($row['Website User Password'] == $password) {
                    $tests['password'] = true;
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        foreach ($tests as $test => $value) {
            if (!$value) {
                $pass_tests = false;
                $fail_test  = $test;
                break;
            }


        }

        if ($pass_tests) {


            $website_user_log_key = $this->log_login('Login', $website_key, $website_user_key, $customer_key, $remember_me);

            return array(
                true,
                'success',
                $customer_key,
                $website_user_key,
                $website_user_log_key
            );

        } else {
            $this->log_failed_login($handle, $website_user_key, $fail_test, $tests, $customer_key, $website_key);

            return array(
                false,
                $fail_test,
                '',
                '',
                ''
            );
        }


    }

    function log_failed_login($handle, $website_user_key, $main_reason, $tests, $customer_key, $website_key) {
        $date = gmdate("Y-m-d H:i:s");
        $ip   = ip();
        $sql  = sprintf(
            "INSERT INTO `Website Failed Log Dimension` 
(
              `Website Failed Log Handle`,`Website Failed Log User Key`,`Website Failed Log Date`,`Website Failed Log IP`,
              `Website Failed Log Fail Reason`,`Website Failed Log Handle OK`,`Website Failed Log Handle Active OK`,`Website Failed Log Password OK`)  
            VALUES (%s,%s,%s,%s, %s,%s,%s,%s)", prepare_mysql($handle), prepare_mysql($website_user_key), prepare_mysql($date), prepare_mysql($ip), prepare_mysql($main_reason),
            prepare_mysql(($tests['handle'] ? 'Yes' : 'No')), prepare_mysql(($tests['handle_active'] ? 'Yes' : 'No')), prepare_mysql(($tests['password'] ? 'Yes' : 'No'))

        );

        //print $sql;

        $this->db->exec($sql);

        if ($website_user_key) {

            $sql = sprintf(
                "SELECT `Website User Failed Login Count` FROM `Website User Data` WHERE `Website User Key`=%d", $website_user_key
            );

            $num_failed_logs = 0;

            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $num_failed_logs = $row['Website User Failed Login Count'];
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }
            $num_failed_logs++;

            $sql = sprintf(
                "UPDATE `Website User Data` SET `Website User Failed Login Count`=%d, `Website User Last Failed Login IP`=%s,`Website User Last Failed Login`=%s WHERE `Website User Key`=%d",
                $num_failed_logs, prepare_mysql($ip), prepare_mysql($date), $website_user_key
            );
            $this->db->exec($sql);


            if ($customer_key) {


                /*

                $customer = new Customer($customer_key);
                switch ($main_reason) {
                    case('password'):
                        $formatted_reason = _('wrong password');
                        break;
                    case('masterkey_used'):
                        $formatted_reason = _('reset password link already used');
                        break;
                    case('masterkey_expired'):
                        $formatted_reason = _('reset password link expired');
                        break;
                    default:
                        $formatted_reason =$main_reason;
                }

                $details = '
				<div class="table">
				<tr><td style="width:120px">'._('Time').':</div><div>'.strftime("%c", strtotime($date.' +00:00')).'</div></div>
				<div class="field tr"><div>'._('Handle').':</div><div>'.$handle.'</div></div>
				<div class="field tr"><div>'._('IP Address').':</div><div>'.$ip.'</div></div>
			    <div class="field tr"><div>'._('User Agent').':</div><div>'.(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '').'</div></div>
				</div>';

                $history_data = array(
                    'Date'            => $date,
                    'Site Key'        =>$website_key,
                    'Note'            => _('Failed Login')." ($formatted_reason) ip:".$ip,
                    'Details'         => $details,
                    'Action'          => 'fail_login',
                    'Preposition'     => 'because',
                    'Indirect Object' => $main_reason,
                    'User Key'        => $website_user_key
                );

                $customer->add_history_login($history_data);
                $customer->update_web_data();

                */

            }


        }
    }


    function authenticate_from_remember($selector, $authenticator, $website_key) {


        $pass_tests = false;
        $fail_test = 'cookie_error';

        $website_user_key     = '';
        $customer_key         = '';
        $website_user_log_key = '';

        $tests = array(
            'handle'        => false,
            'handle_active' => false,
            'password'      => false

        );


        $sql = sprintf(
            "SELECT `Website Auth Token Key`, `Website Auth Token Hash`,`Website Auth Token Website Key`,`Website Auth Token Website User Key`,`Website Auth Token Customer Key`,`Website Auth Token Website User Log Key` FROM `Website Auth Token Dimension` WHERE `Website Auth Token Selector`=%s  ",
            prepare_mysql($selector)
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {


                if (hash_equals($row['Website Auth Token Hash'], hash('sha256', base64_decode($authenticator))) and $website_key == $row['Website Auth Token Website Key']) {

                    $pass_tests           = true;
                    $website_user_key     = $row['Website Auth Token Website User Key'];
                    $customer_key         = $row['Website Auth Token Customer Key'];
                    $website_user_log_key = $row['Website Auth Token Website User Log Key'];


                    require_once "external_libs/random/lib/random.php";
                    $selector      = base64_encode(random_bytes(9));
                    $authenticator = random_bytes(33);

                    setcookie(
                        'rmb', $selector.':'.base64_encode($authenticator), time() + 864000, '/'
                    //,'',
                    //true, // TLS-only
                    //true  // http-only
                    );

                    $sql = sprintf(
                        'delete from `Website Auth Token Dimension` where `Website Auth Token Key`=%d ',$row['Website Auth Token Key']

                    );

                    $this->db->exec($sql);

                    $sql = sprintf(
                        'INSERT INTO `Website Auth Token Dimension` (`Website Auth Token Website Key`,`Website Auth Token Selector`,`Website Auth Token Hash`,`Website Auth Token Website User Key`,`Website Auth Token Customer Key`,`Website Auth Token Website User Log Key`,`Website Auth Token Expire`) 
                      VALUES (%d,%s,%s,%d,%d,%d,%s)',

                        $website_key,prepare_mysql($selector), prepare_mysql(hash('sha256', $authenticator)), $website_user_key, $customer_key, $website_user_log_key,
                        prepare_mysql(date('Y-m-d H:i:s', time() + 864000))

                    );

                    $this->db->exec($sql);




                } else {



                    setcookie('rmb', 'x:x', time() - 864000, '/'
                    //,'',
                    //true, // TLS-only
                    //true  // http-only
                    );



                }

            } else {


                setcookie('rmb', 'x:x', time() - 864000, '/'
                //,'',
                //true, // TLS-only
                //true  // http-only
                );



            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        if ($pass_tests) {


            $this->extend_log($website_user_log_key, $website_user_key, $customer_key);

            return array(
                true,
                'success',
                $customer_key,
                $website_user_key,
                $website_user_log_key
            );

        } else {
            $this->log_failed_login('Cookie', $website_user_key, $fail_test, $tests, $customer_key, $website_key);

            return array(
                false,
                $fail_test,
                '',
                '',
                ''
            );
        }


    }

    function extend_log($website_user_log_key, $website_user_key, $customer_key){




    }



    function authenticate_from_master_key($data, $same_ip = false) {


        $pass_tests = false;
        $this->pass = array(
            'handle'        => 'No',
            'handle_in_use' => 'No',
            'handle_key'    => 0,
            'password'      => 'Unknown',
            'time'          => 'Unknown',
            'ip'            => 'Unknown',
            'ikey'          => 'Unknown',
            'main_reason'   => 'masterkey_not_found'
        );

        //'cookie_error','handle','password','logging_timeout','ip','ikey','masterkey_not_found','masterkey_used','masterkey_expired'

        $this->authentication_type = 'masterkey';
        $sql                       =
            sprintf("SELECT `User Key`,`Valid Until`,`MasterKey Key`,`Used`,`Fails Already Used`,`Fails Expired`  FROM `MasterKey Dimension` M  WHERE `Key`=%s  ", prepare_mysql($data));

        //  if ($same_ip) {$sql.=sprintf(" and `IP`=%s",prepare_mysql(ip()));}


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $user                          = new User($row['User Key']);
                $this->handle                  = $user->data['User Handle'];
                $this->pass['handle_key']      = $user->id;
                $this->pass['user_parent_key'] = $user->data['User Parent Key'];
                if ($row['Used'] == 'No') {


                    if (gmdate('U') < date('U', strtotime($row['Valid Until'].' +00:00'))) {

                        $sql = sprintf("UPDATE `MasterKey Dimension` SET `Used`='Yes' ,`Date Used`=%s WHERE  `MasterKey Key`=%d", prepare_mysql(gmdate('Y-m-d H:i:s')), $row['MasterKey Key']);


                        $this->db->exec($sql);

                        if ($user->id) {
                            $pass_tests            = true;
                            $this->status          = true;
                            $this->user_key        = $user->id;
                            $this->user_handle     = $user->data['User Handle'];
                            $this->user_parent_key = $user->data['User Parent Key'];
                            $this->log_login();
                        } else {
                            $this->pass['main_reason'] = 'handle';
                        }
                    } else {
                        $sql = sprintf("UPDATE `MasterKey Dimension` SET `Fails Expired`=%d WHERE  `MasterKey Key`=%d", $row['Fails Expired'] + 1, $row['MasterKey Key']);
                        $this->db->exec($sql);
                        $this->pass['main_reason'] = 'masterkey_expired';
                        $this->pass['time']        = 'No';
                        $this->pass['password']    = 'Yes';
                        $this->pass['handle']      = 'Yes';
                        $this->pass['ikey']        = 'Yes';

                    }
                } else {

                    $this->pass['password'] = 'Yes';
                    $this->pass['handle']   = 'Yes';
                    $this->pass['ikey']     = 'Yes';

                    $sql = sprintf("UPDATE `MasterKey Dimension` SET `Fails Already Used`=%d WHERE  `MasterKey Key`=%d", $row['Fails Already Used'] + 1, $row['MasterKey Key']);
                    $this->db->exec($sql);
                    $this->pass['main_reason'] = 'masterkey_used';


                }

            } else {
                // $this->log_failed_login();
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        if (!$pass_tests) {

            $this->log_failed_login();
        }


    }

}

?>
