<?php

/*
 File: Auth.php

 Authenticatin Class
 About:
 Author: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

class Auth {


    var $user_parent_key = false;

    var $user_key = false;
    var $authentication_type = false;
    var $user_log_key = 0;
    var $handle = '';
    var $remember = false;
    private $status = false;
    private $use_cookies = false;

    function __construct($ikey = false, $skey = false, $options = '') {

        global $db;

        $this->db = $db;


        if (preg_match('/use( |\_)cookies?/i', $options)) {
            $this->use_cookies = true;
        }
        $this->ikey = $ikey;
        $this->skey = $skey;

        $this->pass = array(
            'handle'        => 'No',
            'handle_in_use' => 'No',
            'password'      => 'No',
            'time'          => 'No',
            'ip'            => 'No',
            'ikey'          => 'No'
        );
    }


    function authenticate($handle = false, $sk = false, $page = 'system', $page_key = 'f0') {


        $this->log_page = $page;

        switch ($this->log_page) {
            case 'system':
                $this->user_type       = "'Administrator','Staff','Warehouse','Contractor','Supplier','Agent'";
                $this->where_user_type = " and `User Type` in ('Administrator','Staff','Warehouse','Contractor','Supplier','Agent')";
                break;


        }

        if ($handle and $sk) {
            $this->handle = $handle;
            $this->sk     = $sk;
            $this->authenticate_from_login();
        } elseif ($this->use_cookies) {

            $this->handle = $_COOKIE['user_handle'];
            $this->sk     = $_COOKIE['sk'];
            $this->authenticate_from_cookie();
        }

    }

    function authenticate_from_login() {


        $this->authentication_type = 'login';
        $this->status              = false;
        $pass_tests                = false;
        $this->pass                = array(
            'handle'        => 'No',
            'handle_in_use' => 'No',
            'handle_key'    => 0,
            'password'      => 'Unknown',
            'time'          => 'Unknown',
            'ip'            => 'Unknown',
            'ikey'          => 'Unknown',
            'main_reason'   => 'handle',
            'customer_key'  => 0
        );

        $sql = sprintf(
            "SELECT `User Key`,`User Password`,`User Parent Key` FROM `User Dimension` WHERE `User Handle`=%s AND `User Active`='Yes' %s  ", prepare_mysql($this->handle), $this->where_user_type
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $this->pass['handle']          = 'Yes';
                $this->pass['handle_in_use']   = 'Yes';
                $this->pass['user_parent_key'] = $row['User Parent Key'];
                $st                            = AESDecryptCtr(AESDecryptCtr($this->sk, $row['User Password'], 256), $this->skey, 256);


                $this->pass['handle_key'] = $row['User Key'];


                if (preg_match(
                    '/^skstart\|\d+\|([abcdef0-9\.\:]+|localhost|unknown)\|.+\|/', $st
                )) {
                    $this->pass['password'] = 'Yes';
                    $data                   = preg_split('/\|/', $st);

                    //print_r($data);
                    $time = $data[1];
                    $ip   = $data[2];
                    $ikey = $data[3];

                    $pass_tests = true;


                    if ($time < gmdate('U')) {
                        $pass_tests                = false;
                        $this->pass['main_reason'] = 'logging_timeout';
                        $this->pass['time']        = 'No';
                    } else {
                        $this->pass['time'] = 'Yes';
                    }


                    $this->pass['ip'] = 'Yes';

                    if ($this->ikey != $ikey) {
                        $pass_tests                = false;
                        $this->pass['main_reason'] = 'ikey';
                        $this->pass['ikey']        = 'No';

                    } else {
                        $this->pass['ikey'] = 'Yes';
                    }

                } else {
                    $pass_tests                = false;
                    $this->pass['password']    = 'No';
                    $this->pass['main_reason'] = 'password';
                }

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        if ($pass_tests) {
            $this->status          = true;
            $this->user_key        = $row['User Key'];
            $this->user_parent_key = $row['User Parent Key'];
            $this->create_user_log();
        } else {
            $this->log_failed_login();
        }


    }

    function create_user_log() {


        $ip   = ip_from_cloudfare();
        $date = gmdate('Y-m-d H:i:s');
        $sql  = sprintf(
            "INSERT INTO `User Log Dimension` (`User Key`,`Session ID`, `IP`, `Start Date`,`Last Visit Date`, `Logout Date`) VALUES (%d, %s, %s, %s,%s, %s)", $this->user_key, prepare_mysql(session_id()), prepare_mysql($ip), prepare_mysql($date), prepare_mysql($date),
            'NULL'
        );


        $this->db->exec($sql);

        $this->user_log_key = $this->db->lastInsertId();

        $sql = sprintf("SELECT count(*) AS num FROM `User Log Dimension` WHERE `User Key`=%d", $this->user_key);

        $num_logins = 0;

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $num_logins = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        if ($num_logins > 0) {
            $this->data['User Has Login'] = 'Yes';
        } else {
            $this->data['User Has Login'] = 'No';
        }
        $sql = sprintf(
            "UPDATE `User Dimension` SET `User Has Login`=%d , `User Login Count`=%d, `User Last Login IP`=%s,`User Last Login`=%s WHERE `User Key`=%d", prepare_mysql($this->data['User Has Login']), $num_logins, prepare_mysql($ip), prepare_mysql($date), $this->user_key
        );
        $this->db->exec($sql);


    }

    function log_failed_login() {
        $date = gmdate("Y-m-d H:i:s");
        $ip   = ip_from_cloudfare();
        $sql  = "INSERT INTO `User Failed Log Dimension`  (`Handle`,`Login Page`,`User Key`, `Date`,`IP`,`Fail Main Reason`, `Handle OK`,`Password OK`,`Logging On Time OK`, `IP OK`,`IKey OK`)  VALUES (?,?,?,? ,?,?,?, ?,?,?, ?,?)";

        $this->db->prepare($sql)->execute(
            array(
                $this->handle,
                $this->log_page,
                $this->pass['handle_key'],

                $date,
                $ip,
                $this->pass['main_reason'],

                $this->pass['handle'],
                $this->pass['password'],
                $this->pass['time'],

                $this->pass['ip'],
                $this->pass['ikey']
            )
        );


        if ($this->pass['handle_key']) {

            $sql = sprintf(
                "SELECT count(*) AS num FROM `User Failed Log Dimension` WHERE `User Key`=%d", $this->pass['handle_key']
            );

            $num_failed_logins = 0;

            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $num_failed_logins = $row['num'];
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            $sql = sprintf(
                "UPDATE `User Dimension` SET `User Failed Login Count`=%d, `User Last Failed Login IP`=%s,`User Last Failed Login`=%s WHERE `User Key`=%d", $num_failed_logins, prepare_mysql($ip), prepare_mysql($date), $this->pass['handle_key']
            );
            $this->db->exec($sql);


        }
    }

    function authenticate_from_cookie() {


        $this->authentication_type = 'cookie';
        $this->status              = false;
        $pass_tests                = false;
        $this->pass                = array(
            'handle'        => 'No',
            'handle_in_use' => 'No',
            'handle_key'    => 0,
            'password'      => 'Unknown',
            'time'          => 'Unknown',
            'ip'            => 'Unknown',
            'ikey'          => 'Unknown',
            'main_reason'   => 'cookie_error'
        );

        $sql = sprintf(
            "SELECT `User Key`,`User Password`,`User Parent Key` FROM `User Dimension` WHERE `User Handle`=%s AND `User Active`='Yes' %s  ", prepare_mysql($this->handle), $this->where_user_type
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->pass['handle']        = 'Yes';
                $this->pass['handle_in_use'] = 'Yes';

                $st = AESDecryptCtr(
                    AESDecryptCtr($this->sk, $row['User Password'], 256), $this->skey, 256
                );
                //echo $st;
                $this->pass['handle_key'] = $row['User Key'];
                if (preg_match('/^skstart\|\d+\|[abcdef0-9\.\:]+\|.+\|/', $st)) {
                    $this->pass['password'] = 'Yes';
                    $data                   = preg_split('/\|/', $st);

                    //print_r($data);
                    $time = $data[1];
                    $ip   = $data[2];
                    $ikey = $data[3];

                    if (isset($_COOKIE['user_handle'])) {
                        $time = time(gmdate('U')) + 100;

                    }

                    $pass_tests = true;


                    if ($this->ikey != $ikey) {
                        $pass_tests                = false;
                        $this->pass['main_reason'] = 'cookie_error';
                        $this->pass['ikey']        = 'No';

                    } else {
                        $this->pass['ikey'] = 'Yes';
                    }

                } else {
                    $pass_tests                = false;
                    $this->pass['password']    = 'No';
                    $this->pass['main_reason'] = 'cookie_error';
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        if ($pass_tests) {

            $this->status          = true;
            $this->user_key        = $row['User Key'];
            $this->user_parent_key = $row['User Parent Key'];
            $this->get_last_log();

            //$this->create_user_log();
        } else {
            $this->log_failed_login();
        }
        //echo $this->status;

    }

    function get_last_log() {

        $sql = sprintf(
            "SELECT `User Log Key` FROM `User Log Dimension`  WHERE `Logout Date` IS NULL  AND `User Key`=%d ", $this->user_key
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $ip                 = ip_from_cloudfare();
                $date               = gmdate('Y-m-d H:i:s');
                $this->user_log_key = $row['User Log Key'];

                $sql = sprintf("UPDATE `User Dimension` SET `User Last Login IP`=%s,`User Last Login`=%s WHERE `User Key`=%d", prepare_mysql($ip), prepare_mysql($date), $this->user_key);
                $this->db->exec($sql);

            } else {
                $this->create_user_log();
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


    }

    function is_authenticated() {
        return $this->status;
        //return true;
    }

    function set_cookies($handle = false, $sk = false, $page = false, $page_key = false) {
        //setcookie('test2', 'yyyyyy', time()+60*60*24*365);
        //print_r($_COOKIE);
        //print "xxx";
        setcookie('user_handle', $handle, time() + 60 * 60 * 24 * 365, "/");
        setcookie('sk', $sk, time() + 60 * 60 * 24 * 365, "/");
        //setcookie('page', $page, time()+60*60*24*365, "/");
        setcookie('page_key', $page_key, time() + 60 * 60 * 24 * 365, "/");
    }

    function unset_cookies($handle = false, $sk = false, $page = false, $page_key = false) {
        $res = setcookie('user_handle', $handle, time() - 100000, "/");
        setcookie('sk', $sk, time() - 100000, "/");
        //setcookie('page', $page, time()-3600, "/");
        setcookie('page_key', $page_key, time() - 100000, "/");
        //print "xxxx $res X $handle xxxx";

    }

    function authenticate_from_inikoo_masterkey($data, $same_ip = false) {


        $this->authentication_type = 'masterkey';


        $sql = sprintf(
            "SELECT `MasterKey Internal Key`,U.`User Key`,`User Handle`,`User Parent Key` FROM `MasterKey Internal Dimension` M LEFT JOIN `User Dimension` U ON (U.`User Key`=M.`User Key`)    WHERE `Key`=%s AND  `Valid Until`>=%s  ", prepare_mysql($data),
            prepare_mysql(gmdate('Y-m-d H:i:s'))

        );


        if ($same_ip) {

            $sql .= sprintf(
                " and `IP`=%s", prepare_mysql(ip_from_cloudfare())
            );
        }


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->status          = true;
                $this->user_key        = $row['User Key'];
                $this->user_handle     = $row['User Handle'];
                $this->user_parent_key = $row['User Parent Key'];
                //$this->create_user_log();
                //todo  $this->create_inikoo_log <-- to log this shit!!!!


                $sql = sprintf("DELETE FROM  `MasterKey Internal Dimension` WHERE `MasterKey Internal Key`=%d   ", $row['MasterKey Internal Key']);
                $this->db->exec($sql);
            } else {
                // $this->log_failed_login();

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


    }

    function authenticate_from_masterkey($data, $same_ip = false) {


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
        $sql                       = sprintf("SELECT `User Key`,`Valid Until`,`MasterKey Key`,`Used`,`Fails Already Used`,`Fails Expired`  FROM `MasterKey Dimension` M  WHERE `Key`=%s  ", prepare_mysql($data));

        //  if ($same_ip) {$sql.=sprintf(" and `IP`=%s",prepare_mysql(ip_from_cloudfare()));}


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
                            $this->create_user_log();
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

    public function get_user_key() {
        return $this->user_key;
    }

    public function set_user_key($user_key) {
        $this->user_key = $user_key;
    }

    public function get_user_parent_key() {
        return $this->user_parent_key;
    }

    public function set_use_cookies() {
        $this->use_cookies = true;
    }
}

?>
