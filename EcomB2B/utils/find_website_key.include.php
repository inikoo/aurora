<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 June 2017 at 20:17:49 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


  if ($_SERVER['SERVER_NAME'] == 'ecom.bali') {
        $_SESSION['website_key'] = 2;
    } else {

      include_once 'utils/general_functions.php';

      require_once 'keyring/dns.php';

        $db = new PDO(
            "mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
        );
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


        $server_name=$_SERVER['SERVER_NAME'];//preg_replace('/^www\./','',$_SERVER['SERVER_NAME']);

        $sql = sprintf(
            'SELECT `Website Key`  FROM `Website Dimension` WHERE `Website URL`=%s', prepare_mysql($server_name)
        );



        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $_SESSION['website_key'] = $row['Website Key'];
            } else {

                print 'E1 SERVER_NAME '.$_SERVER['SERVER_NAME'];
                exit;
            }
        } else {


            print 'E2 SERVER_NAME '.$_SERVER['SERVER_NAME'];
            exit;

        }


    }






?>