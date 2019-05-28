<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 July 2017 at 16:16:40 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

class WebAuth {


    function __construct() {

        global $db;
        $this->db = $db;


    }


    function authenticate_from_register($website_user_key, $customer_key, $website_key) {


        $website_user_log_key = $this->log_login('Register', $website_key, $website_user_key, $customer_key);


        return array(
            true,
            $website_user_log_key
        );


    }

    function log_login($authentication_type, $website_key, $web_user_key, $customer_key) {


        $ip   = $this->ip();
        $date = gmdate('Y-m-d H:i:s');
        $sql  = sprintf(
            "INSERT INTO `Website User Log Dimension` (`Website User Log User Key`,`Website User Log Session ID`, `Website User Log IP`, `Website User Log Start Date`,`Website User Log Last Visit Date`, `Website User Log Logout Date`,`Website User Log Website Key`) VALUES (%d, %s, %s, %s,%s,%s,%d)",
            $web_user_key, prepare_mysql(session_id()), prepare_mysql($ip), prepare_mysql($date), prepare_mysql($date), 'NULL', $website_key
        );

        $this->db->exec($sql);

        $website_user_log_key = $this->db->lastInsertId();


        if ($authentication_type == 'Login' or $authentication_type == 'Reset_Password') {

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
                "UPDATE `Website User Data` SET `Website User Login Count`=%d, `Website User Last Login IP`=%s,`Website User Last Login`=%s WHERE `Website User Key`=%d", $number_logs, prepare_mysql($ip), prepare_mysql($date), $web_user_key
            );

            $this->db->exec($sql);


        } else {
            $sql = sprintf(
                "UPDATE `Website User Data` SET  `Website User Last Login IP`=%s,`Website User Last Login`=%s WHERE `Website User Key`=%d", prepare_mysql($ip), prepare_mysql($date), $web_user_key
            );


            $this->db->exec($sql);
        }




        return $website_user_log_key;


    }

    function ip() {
        global $REMOTE_ADDR;
        global $HTTP_X_FORWARDED_FOR, $HTTP_X_FORWARDED, $HTTP_FORWARDED_FOR, $HTTP_FORWARDED;
        global $HTTP_VIA, $HTTP_X_COMING_FROM, $HTTP_COMING_FROM;
        // Get some server/environment variables values

        $direct_ip = '';
        if (empty($REMOTE_ADDR)) {
            if (!empty($_SERVER) && isset($_SERVER['REMOTE_ADDR'])) {
                $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
            } else {
                if (!empty($_ENV) && isset($_ENV['REMOTE_ADDR'])) {
                    $REMOTE_ADDR = $_ENV['REMOTE_ADDR'];
                } else {
                    if (@getenv('REMOTE_ADDR')) {
                        $REMOTE_ADDR = getenv('REMOTE_ADDR');
                    }
                }
            }
        } // end if
        if (empty($HTTP_X_FORWARDED_FOR)) {
            if (!empty($_SERVER) && isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $HTTP_X_FORWARDED_FOR = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                if (!empty($_ENV) && isset($_ENV['HTTP_X_FORWARDED_FOR'])) {
                    $HTTP_X_FORWARDED_FOR = $_ENV['HTTP_X_FORWARDED_FOR'];
                } else {
                    if (@getenv('HTTP_X_FORWARDED_FOR')) {
                        $HTTP_X_FORWARDED_FOR = getenv('HTTP_X_FORWARDED_FOR');
                    }
                }
            }
        } // end if
        if (empty($HTTP_X_FORWARDED)) {
            if (!empty($_SERVER) && isset($_SERVER['HTTP_X_FORWARDED'])) {
                $HTTP_X_FORWARDED = $_SERVER['HTTP_X_FORWARDED'];
            } else {
                if (!empty($_ENV) && isset($_ENV['HTTP_X_FORWARDED'])) {
                    $HTTP_X_FORWARDED = $_ENV['HTTP_X_FORWARDED'];
                } else {
                    if (@getenv('HTTP_X_FORWARDED')) {
                        $HTTP_X_FORWARDED = getenv('HTTP_X_FORWARDED');
                    }
                }
            }
        } // end if
        if (empty($HTTP_FORWARDED_FOR)) {
            if (!empty($_SERVER) && isset($_SERVER['HTTP_FORWARDED_FOR'])) {
                $HTTP_FORWARDED_FOR = $_SERVER['HTTP_FORWARDED_FOR'];
            } else {
                if (!empty($_ENV) && isset($_ENV['HTTP_FORWARDED_FOR'])) {
                    $HTTP_FORWARDED_FOR = $_ENV['HTTP_FORWARDED_FOR'];
                } else {
                    if (@getenv('HTTP_FORWARDED_FOR')) {
                        $HTTP_FORWARDED_FOR = getenv('HTTP_FORWARDED_FOR');
                    }
                }
            }
        } // end if
        if (empty($HTTP_FORWARDED)) {
            if (!empty($_SERVER) && isset($_SERVER['HTTP_FORWARDED'])) {
                $HTTP_FORWARDED = $_SERVER['HTTP_FORWARDED'];
            } else {
                if (!empty($_ENV) && isset($_ENV['HTTP_FORWARDED'])) {
                    $HTTP_FORWARDED = $_ENV['HTTP_FORWARDED'];
                } else {
                    if (@getenv('HTTP_FORWARDED')) {
                        $HTTP_FORWARDED = getenv('HTTP_FORWARDED');
                    }
                }
            }
        } // end if
        if (empty($HTTP_VIA)) {
            if (!empty($_SERVER) && isset($_SERVER['HTTP_VIA'])) {
                $HTTP_VIA = $_SERVER['HTTP_VIA'];
            } else {
                if (!empty($_ENV) && isset($_ENV['HTTP_VIA'])) {
                    $HTTP_VIA = $_ENV['HTTP_VIA'];
                } else {
                    if (@getenv('HTTP_VIA')) {
                        $HTTP_VIA = getenv('HTTP_VIA');
                    }
                }
            }
        } // end if
        if (empty($HTTP_X_COMING_FROM)) {
            if (!empty($_SERVER) && isset($_SERVER['HTTP_X_COMING_FROM'])) {
                $HTTP_X_COMING_FROM = $_SERVER['HTTP_X_COMING_FROM'];
            } else {
                if (!empty($_ENV) && isset($_ENV['HTTP_X_COMING_FROM'])) {
                    $HTTP_X_COMING_FROM = $_ENV['HTTP_X_COMING_FROM'];
                } else {
                    if (@getenv('HTTP_X_COMING_FROM')) {
                        $HTTP_X_COMING_FROM = getenv('HTTP_X_COMING_FROM');
                    }
                }
            }
        } // end if
        if (empty($HTTP_COMING_FROM)) {
            if (!empty($_SERVER) && isset($_SERVER['HTTP_COMING_FROM'])) {
                $HTTP_COMING_FROM = $_SERVER['HTTP_COMING_FROM'];
            } else {
                if (!empty($_ENV) && isset($_ENV['HTTP_COMING_FROM'])) {
                    $HTTP_COMING_FROM = $_ENV['HTTP_COMING_FROM'];
                } else {
                    if (@getenv('HTTP_COMING_FROM')) {
                        $HTTP_COMING_FROM = getenv('HTTP_COMING_FROM');
                    }
                }
            }
        } // end if

        // Gets the default ip sent by the user
        if (!empty($REMOTE_ADDR)) {
            $direct_ip = $REMOTE_ADDR;
        }

        // Gets the proxy ip sent by the user
        $proxy_ip = '';
        if (!empty($HTTP_X_FORWARDED_FOR)) {
            $proxy_ip = $HTTP_X_FORWARDED_FOR;
        } else {
            if (!empty($HTTP_X_FORWARDED)) {
                $proxy_ip = $HTTP_X_FORWARDED;
            } else {
                if (!empty($HTTP_FORWARDED_FOR)) {
                    $proxy_ip = $HTTP_FORWARDED_FOR;
                } else {
                    if (!empty($HTTP_FORWARDED)) {
                        $proxy_ip = $HTTP_FORWARDED;
                    } else {
                        if (!empty($HTTP_VIA)) {
                            $proxy_ip = $HTTP_VIA;
                        } else {
                            if (!empty($HTTP_X_COMING_FROM)) {
                                $proxy_ip = $HTTP_X_COMING_FROM;
                            } else {
                                if (!empty($HTTP_COMING_FROM)) {
                                    $proxy_ip = $HTTP_COMING_FROM;
                                }
                            }
                        }
                    }
                }
            }
        } // end if... else if...

        // Returns the true IP if it has been found, else FALSE
        if (empty($proxy_ip)) {
            // True IP without proxy
            if ($direct_ip == '::1') {
                $direct_ip = 'localhost';
            }

            return $direct_ip;
        } else {
            $is_ip = preg_match(
                '|^([0-9]{1,3}\.){3,3}[0-9]{1,3}|', $proxy_ip, $regs
            );
            if ($is_ip && (count($regs) > 0)) {
                // True IP behind a proxy
                return $regs[0];
            } else {
                // Can't define IP: there is a proxy but we don't have
                // information about the true IP
                return 'unknown';
            }
        } // end if... else...
    }

    function authenticate_from_login($handle, $password, $website) {

        $website_key = $website->id;


        $pass_tests = true;
        $tests      = array(
            'handle'        => false,
            'handle_active' => false,
            'password'      => false,
            'approved'      => false

        );

        $website_user_key = '';
        $customer_key     = '';

        $sql = sprintf(
            "SELECT `Customer Type by Activity`,`Website User Key`,`Website User Customer Key`,`Website User Password`,`Website User Active` FROM `Website User Dimension`  left join `Customer Dimension` on (`Customer Key`=`Website User Customer Key`)  WHERE `Website User Handle`=%s  AND `Website User Website Key`=%d ",
            prepare_mysql($handle), $website_key
        );





        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {


                //print_r($row);

                $tests['handle']        = true;
                $tests['handle_active'] = ($row['Website User Active'] == 'Yes' ? true : false);
                $tests['approved']      = (($row['Customer Type by Activity'] == 'ToApprove' or $row['Customer Type by Activity'] == 'Rejected') ? false : true);


                $website_user_key = $row['Website User Key'];
                $customer_key     = $row['Website User Customer Key'];


              //  print $password;

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


            $website_user_log_key = $this->log_login('Login', $website_key, $website_user_key, $customer_key);

            return array(
                true,
                'success',
                $customer_key,
                $website_user_key,
                $website_user_log_key
            );

        } else {
            $this->log_failed_login('Login', $handle, $website_user_key, $fail_test, $customer_key, $website_key);

            return array(
                false,
                $fail_test,
                '',
                '',
                ''
            );
        }


    }

    function log_failed_login($type, $handle, $website_user_key, $main_reason, $customer_key, $website_key) {
        $date = gmdate("Y-m-d H:i:s");
        $ip   = $this->ip();
        $sql  = sprintf(
            "INSERT INTO `Website Failed Log Dimension` 
(
              `Website Failed Log Type`,`Website Failed Log Handle`,`Website Failed Log User Key`,`Website Failed Log Date`,`Website Failed Log IP`,
              `Website Failed Log Fail Reason`)  
            VALUES (%s,%s,%s,%s,%s, %s)", prepare_mysql($type), prepare_mysql($handle), prepare_mysql($website_user_key), prepare_mysql($date), prepare_mysql($ip), prepare_mysql($main_reason)

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
                "UPDATE `Website User Data` SET `Website User Failed Login Count`=%d, `Website User Last Failed Login IP`=%s,`Website User Last Failed Login`=%s WHERE `Website User Key`=%d", $num_failed_logs, prepare_mysql($ip), prepare_mysql($date), $website_user_key
            );
            $this->db->exec($sql);




        }
    }

    function authenticate_from_remember($selector, $authenticator, $website_key) {


        $pass_tests = false;
        $fail_test  = 'cookie_error';

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
                        'DELETE FROM `Website Auth Token Dimension` WHERE `Website Auth Token Key`=%d ', $row['Website Auth Token Key']

                    );

                    $this->db->exec($sql);

                    $sql = sprintf(
                        'INSERT INTO `Website Auth Token Dimension` (`Website Auth Token Website Key`,`Website Auth Token Selector`,`Website Auth Token Hash`,`Website Auth Token Website User Key`,`Website Auth Token Customer Key`,`Website Auth Token Website User Log Key`,`Website Auth Token Expire`) 
                      VALUES (%d,%s,%s,%d,%d,%d,%s)',

                        $website_key, prepare_mysql($selector), prepare_mysql(hash('sha256', $authenticator)), $website_user_key, $customer_key, $website_user_log_key, prepare_mysql(gmdate('Y-m-d H:i:s', time() + 864000))

                    );

                    $this->db->exec($sql);


                } else {


                    setcookie(
                        'rmb', 'x:x', time() - 864000, '/'
                    //,'',
                    //true, // TLS-only
                    //true  // http-only
                    );


                }

            } else {


                setcookie(
                    'rmb', 'x:x', time() - 864000, '/'
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
            $this->log_failed_login('Cookie', '', $website_user_key, $fail_test, $customer_key, $website_key);

            return array(
                false,
                $fail_test,
                '',
                '',
                ''
            );
        }


    }

    function extend_log($website_user_log_key, $website_user_key, $customer_key) {


    }

    function authenticate_from_reset_password($selector, $authenticator, $website_key) {

        $pass_tests = false;
        $fail_test  = 'cookie_error';

        $website_user_key = '';
        $customer_key     = '';


        $sql = sprintf(
            "SELECT `Website Recover Token Key`, `Website Recover Token Hash`,`Website Recover Token Website Key`,`Website Recover Token Website User Key`,`Website Recover Token Customer Key`,`Website Recover Token Expire`  FROM `Website Recover Token Dimension` WHERE `Website Recover Token Selector`=%s  ",
            prepare_mysql($selector)
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $website_user_key = $row['Website Recover Token Website User Key'];


                if (hash_equals(
                        $row['Website Recover Token Hash'], hash(
                                                              'sha256', $authenticator

                                                          )
                    ) and $website_key == $row['Website Recover Token Website Key']) {


                    if ($row['Website Recover Token Expire'] < gmdate('Y-m-d H:i:s')) {

                        $fail_test = 'selector_expired';

                    } else {

                        $pass_tests       = true;
                        $website_user_key = $row['Website Recover Token Website User Key'];
                        $customer_key     = $row['Website Recover Token Customer Key'];


                        $sql = sprintf(
                            'DELETE FROM `Website Recover Token Dimension` WHERE `Website Recover Token Key`=%d ', $row['Website Recover Token Key']

                        );

                        // print $sql;
                        $this->db->exec($sql);


                    }


                } else {

                    $fail_test = 'wrong_hash';

                }

            } else {
                $fail_test = 'selector_not_found';


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        if ($pass_tests) {
            $website_user_log_key = $this->log_login('Reset_Password', $website_key, $website_user_key, $customer_key);


            return array(
                true,
                'success',
                $customer_key,
                $website_user_key,
                $website_user_log_key
            );


        } else {
            $this->log_failed_login('Reset_Password', '', $website_user_key, $fail_test, $customer_key, $website_key);

            return array(
                false,
                $fail_test,
                '',
                '',
                ''
            );
        }


    }

    function get_customer_from_unsubscribe_link($selector, $authenticator) {


        include_once 'keyring/key.php';

        $customer_key = '';


        $sql = sprintf('select `Email Tracking Recipient`,`Email Tracking Recipient Key` from `Email Tracking Dimension` where `Email Tracking Key`=%d ', $selector);

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                if (hash_equals(hash('sha256', IKEY.$row['Email Tracking Recipient Key'].$selector), $authenticator)) {
                    $customer_key = $row['Email Tracking Recipient Key'];
                }


            } else {

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $customer_key;


    }


}

?>
