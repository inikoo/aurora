<?php
/*
 File: Auth.php

 Authenticatin Class
 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/


class Auth {
    private $user_parent_key=false;

    private $user_key=false;
    private $status=false;
    private $use_cookies=false;

    function Auth($ikey,$skey,$options='') {
        if (preg_match('/use( |\_)cookies?/i',$options))
            $this->use_cookies=true;
        $this->ikey=$ikey;
        $this->skey=$skey;

        $this->pass=array(
                        'handle'=>'No',
                        'handle_in_use'=>'No',
                        'password'=>'No',
                        'time'=>'No',
                        'ip'=>'No',
                        'ikey'=>'No'
                    );
    }


    function authenticate($handle=false,$sk=false,$page=false,$page_key=f0) {

        $this->log_page=$page;
        switch ($this->log_page) {
        case 'staff':
            $this->user_type="'Administrator','Staff'";
            $this->where_user_type=" and `User Type` in ('Administrator','Staff')"; 
            break;
        case 'customer':    
             $this->user_type="'Customer'";
            $this->where_user_type=sprintf(" and `User Type`='Customer' and `User Site Key`=%d ",$page_key); 
            break;
         case 'supplier':    
             $this->user_type="'Supplier'";
            $this->where_user_type=sprintf(" and `User Type`='Supplier'  "); 
            break;
        }

        if ($handle and $sk) {
            $this->handle=$handle;
            $this->sk=$sk;
            $this->authenticate_from_login();
        }
        elseif($this->use_cookies) {
            $this->authenticate_from_cookie();
        }

    }

    function is_authenticated() {
        return $this->status;

    }

    function authenticate_from_cookie() {

    }

    function authenticate_from_masterkey($data) {

        $data=preg_split('/h_Adkiseqto/',$data);

        if (count($data)==2) {
            $handle=$data[0];
            $key=$data[1];
        } else
            exit;


        $sql=sprintf("select `MasterKey Key`,`User Key` from `MasterKey Dimension` left join  (`User Dimension`) ON (`User Handle`=`Handle`)   where `Key`=%s and  `Valid Until`>=%s and `Handle`=%s   "
                     ,prepare_mysql($key)
                     ,prepare_mysql(date('Y-m-d H:i:s'))
                     ,prepare_mysql($handle)
                    );
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            $this->status=true;
            $this->user_key=$row['User Key'];

            $sql=sprintf("delete from  `MasterKey Dimension` where `MasterKey Key`=%d   "
                         ,$row['MasterKey Key']
                        );
            mysql_query($sql);


        }


    }


    function authenticate_from_login() {
        date_default_timezone_set('UTC');
        include_once('aes.php');
        $this->status=false;
        $pass_tests=false;
        $this->pass=array(
                        'handle'=>'No',
                        'handle_in_use'=>'No',
                        'handle_key'=>0,
                        'password'=>'Unknown',
                        'time'=>'Unknown',
                        'ip'=>'Unknown',
                        'ikey'=>'Unknown',
                        'main_reason'=>'handle'
                    );

        $sql=sprintf("select `User Key`,`User Password`,`User Parent Key` from `User Dimension` where `User Handle`=%s and `User Active`='Yes' %s  "
                     ,prepare_mysql($this->handle)
                     ,$this->where_user_type
                    );
//print $sql;
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            $this->pass['handle']='Yes';
            $this->pass['handle_in_use']='Yes';

            $st=AESDecryptCtr(AESDecryptCtr($this->sk,$row['User Password'],256),$this->skey,256);

            $this->pass['handle_key']=$row['User Key'];
            if (preg_match('/^skstart\|\d+\|[abcdef0-9\.\:]+\|.+\|/',$st)) {
                $this->pass['password']='Yes';
                $data=preg_split('/\|/',$st);
                $time=$data[1];
                $ip=$data[2];
                $ikey=$data[3];

                $pass_tests=true;
                if ($time<time(date('U'))  ) {
                    $pass_tests=false;
                    $this->pass['main_reason']='logging_timeout';
                    $this->pass['time']='No';
                } else {
                    $this->pass['time']='Yes';
                }
                if (ip()!=$ip) {
                    $pass_tests=false;
                    $this->pass['main_reason']='ip';
                    $this->pass['ip']='No';
                } else {
                    $this->pass['ip']='Yes';
                }
                if ($this->ikey!=$ikey) {
                    $pass_tests=false;
                    $this->pass['main_reason']='ikey';
                    $this->pass['ikey']='No';

                } else {
                    $this->pass['ikey']='Yes';
                }

            } else {
                $pass_tests=false;
                $this->pass['password']='No';
                $this->pass['main_reason']='password';
            }
        }

        if ($pass_tests ) {
            $this->status=true;
            $this->user_key=$row['User Key'];
            $this->user_parent_key=$row['User Parent Key'];
            $this->create_user_log();
        } else {
            $this->log_failed_login();
        }

        date_default_timezone_set(TIMEZONE) ;

    }


    function log_failed_login() {
        date_default_timezone_set('UTC');
        $date=date("Y-m-d H:i:s");
        $ip=ip();
        $sql=sprintf("insert into `User Failed Log Dimension` values (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)",
                     prepare_mysql($this->handle),
                     prepare_mysql($this->log_page),
                     prepare_mysql($this->pass['handle_key']),
                     prepare_mysql($date),
                     prepare_mysql($ip),
                     prepare_mysql($this->pass['main_reason']),
                     prepare_mysql($this->pass['handle']),
                     prepare_mysql($this->pass['password']),
                     prepare_mysql($this->pass['time']),
                     prepare_mysql($this->pass['ip']),
                     prepare_mysql($this->pass['ikey'])

                    );
        mysql_query($sql);
        if ($this->pass['handle_key']) {

            $sql=sprintf("select count(*) as num from `User Failed Log Dimension` where `User Key`=%d",$this->pass['handle_key']);
            $res=mysql_query($sql);
            $num_failed_logins=0;
            if ($row=mysql_fetch_assoc($res)) {
                $num_failed_logins=$row['num'];
            }
            $sql=sprintf("update `User Dimension` set `User Failed Login Count`=%d, `User Last Failed Login IP`=%s,`User Last Failed Login`=%s where `User Key`=%d",
                         $num_failed_logins,
                         prepare_mysql($ip),
                         prepare_mysql($date),
                         $this->pass['handle_key']
                        );
            mysql_query($sql);
        }
        date_default_timezone_set(TIMEZONE) ;
    }

    function create_user_log() {
        date_default_timezone_set('UTC');
        $ip=ip();
        $date=date('Y-m-d H:i:s');
        $sql=sprintf("INSERT INTO `User Log Dimension` (`User Key`,`Session ID`, `IP`, `Start Date`, `Logout Date`) VALUES (%d, %s, %s, %s, %s)",
                     $this->user_key,
                     prepare_mysql(session_id()),
                     prepare_mysql($ip),
                     prepare_mysql($date),
                     'NULL');

        mysql_query($sql);
        
         $sql=sprintf("select count(*) as num from `User Log Dimension` where `User Key`=%d",$this->user_key);
            $res=mysql_query($sql);
            $num_logins=0;
            if ($row=mysql_fetch_assoc($res)) {
                $num_logins=$row['num'];
            }
            $sql=sprintf("update `User Dimension` set `User Login Count`=%d, `User Last Login IP`=%s,`User Last Login`=%s where `User Key`=%d",
                         $num_logins,
                         prepare_mysql($ip),
                         prepare_mysql($date),
                         $this->user_key
                        );
            mysql_query($sql);
        
        date_default_timezone_set(TIMEZONE) ;
    }

    public function get_user_key() {
        return $this->user_key;
    }
    public function get_user_parent_key() {
        return $this->user_parent_key;
    }


public function set_user_key($user_key){
    $this->user_key=$user_key;
}
}
?>
