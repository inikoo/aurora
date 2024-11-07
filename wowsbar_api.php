<?php
require_once 'vendor/autoload.php';
include_once 'keyring/dns.php';
include_once 'keyring/au_deploy_conf.php';


if(defined('SENTRY_DNS_API')){
    Sentry\init(['dsn' => SENTRY_DNS_API ]);
}

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token,HTTP_X_AUTH_KEY');

error_reporting(E_ALL);
date_default_timezone_set('UTC');
include_once 'utils/general_functions.php';
include_once 'utils/object_functions.php';

$db = new PDO("mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

if(!empty($_REQUEST['key']) and $_REQUEST['key']==WOWSBAR_KEY ){
    $website_url=$_REQUEST['website'];
    $website_url=preg_replace('/^http?s:\/\//','',$website_url);
    $website_url=preg_replace('/^www./','',$website_url,);
    $website_url='www.'.$website_url;

    $entityBody = file_get_contents('php://input');
    $data=json_decode($entityBody,true);
    if(isset($data['footer'])){

        $sql=sprintf("update `Website Dimension` set wowsbar_footer=?  where `Website URL`=?  ");

      $footer= $data['footer']==''?'':json_encode($data['footer']);

        $db->prepare($sql)->execute(
            array(
                $footer,
                $website_url
            )
        );

       print json_encode(
           [
               'website'=>$website_url,
               'footer'=>$data['footer']['data']
           ]
       );


    }
}else{
    echo '403';
}