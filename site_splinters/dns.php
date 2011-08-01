<?php
/*
Change:
PASSWORD
USER
HOST

to suit your database configuration
and save as to dns.php



*/

$dns_pwd='ajolote1';
$dns_db='dw';
$dns_user='root';
$dns_host='localhost';
$dsn = 'mysql://'.$dns_user.':'.$dns_pwd.'@'.$dns_host.'/'.$dns_db;



?>