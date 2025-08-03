<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 21 November 2015 at 14:41:00 GMT GMT Sheffield UK

 Version 2.0
*/


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



$db = new PDO(
    "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


list($user_key, $api_key_key, $scope) = authenticate($db);





authorization($db, $user_key, $api_key_key, $scope);


function authorization($db, $user_key, $api_key_key, $scope) {




    $warehouse_key = '';
    $sql           = sprintf('SELECT `Warehouse Key` FROM `Warehouse Dimension` WHERE `Warehouse State`="Active" limit 1');

    if ($result2 = $db->query($sql)) {
        if ($row2 = $result2->fetch()) {
            $warehouse_key = $row2['Warehouse Key'];
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }
    $_SESSION['current_warehouse']= $warehouse_key;


    $method       = $_SERVER['REQUEST_METHOD'];
    $parsed_scope = parse_scope($_SERVER['REDIRECT_URL']);


    if (!$scope) {
        $response = log_api_key_access_failure(
            $db, $api_key_key, 'Fail_Access', 'Wrong URL'
        );
        echo json_encode($response);
        exit;
    }

    if ($parsed_scope != $scope) {
        $response = log_api_key_access_failure(
            $db, $api_key_key, 'Fail_Access', "Path and Scope don't match $parsed_scope $scope  "
        );
        echo json_encode($response);
        exit;
    }

    $user = check_permissions($db, $user_key, $scope, $api_key_key);


    $editor = array(
        'Author Name'  => $user->data['User Alias'],
        'Author Alias' => $user->data['User Alias'],
        'Author Type'  => $user->data['User Type'],
        'Author Key'   => $user->data['User Parent Key'],
        'User Key'     => $user->id,
        'Date'         => gmdate('Y-m-d H:i:s')
    );


    switch ($scope) {
        case 'Nano Services':
            include_once 'api_nano_services.php';
            break;
        case 'Timesheet':
            include_once 'api_timesheet.php';

            if ($method == 'POST') {
                post_timesheet($db, $editor, $api_key_key);

            } else {
                $response = log_api_key_access_failure(
                    $db, $api_key_key, 'Fail_Access', "Unauthorized request method"
                );
                echo json_encode($response);
                exit;

            }

        case 'Stock':
            include_once 'api_stock.php';
            break;
        case 'Picking':
            include_once 'api_picking.php';
            break;
        case 'Box':
            include_once 'api_box.php';
            break;
        default:
            $response = log_api_key_access_failure(
                $db, $api_key_key, 'Fail_Access', 'Unknown scope'
            );
            echo json_encode($response);
            exit;
            break;
    }


}


function check_permissions($db, $user_key, $scope) {
    include_once 'class.User.php';
    $user = new User($user_key);

    return $user;
}


function parse_scope($request) {

    if ($request == '/api/timesheet_record') {
        return 'Timesheet';
    } elseif ($request == '/api/nc') {
        return 'Nano Services';
    } elseif ($request == '/api/stock') {
        return 'Stock';
    } elseif ($request == '/api/picking') {
        return 'Picking';
    }elseif ($request == '/api/box') {
        return 'Box';
    }

    return false;

}


function _apache_request_headers() {
    $arh     = array();
    $rx_http = '/\AHTTP_/';
    foreach ($_SERVER as $key => $val) {
        if (preg_match($rx_http, $key)) {
            $arh_key    = preg_replace($rx_http, '', $key);
            $rx_matches = array();
            // do some nasty string manipulations to restore the original letter case
            // this should work in most cases
            $rx_matches = explode('_', $arh_key);
            if (count($rx_matches) > 0 and strlen($arh_key) > 2) {
                foreach ($rx_matches as $ak_key => $ak_val) {
                    $rx_matches[$ak_key] = ucfirst($ak_val);
                }
                $arh_key = implode('-', $rx_matches);
            }
            $arh[$arh_key] = $val;
        }
    }

    return ($arh);
}


function getAuthorizationHeader() {
    $headers = null;
    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER["Authorization"]);
    } else {
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
    }

    return $headers;
}


function authenticate($db) {


    $_headers = _apache_request_headers();

    $token = false;


    //print_r($_headers);

    if (empty($_SERVER['HTTP_X_AUTH_KEY'])) {
        if (!empty($_headers['HTTP_X_AUTH_KEY'])) {
            $token = $_headers['HTTP_X_AUTH_KEY'];
        } elseif (!empty($_headers['http_x_auth_key'])) {
            $token = $_headers['http_x_auth_key'];
        } elseif (!empty($_headers['AUTHKEY'])) {
            $token = $_headers['AUTHKEY'];
        }elseif (!empty($_REQUEST['AUTH_KEY'])) {
            $token = $_REQUEST['AUTH_KEY'];
        }else{
            $auth_header= getAuthorizationHeader();
            if(preg_match('/^Bearer\s(.+)$/',$auth_header,$matches)){
                $token=$matches[1];
            }


        }


    }else{
        $token=$_SERVER['HTTP_X_AUTH_KEY'];
    }


    if (!$token  and isset($_REQUEST['AUTH_KEY'])) {
        $token = $_REQUEST['AUTH_KEY'];

    }

    if(!$token and isset($_headers['AKEY'])){
        $token=$_headers['AKEY'];
    }

    if(!$token and isset($_headers['SECRET'])){
        $token=$_headers['SECRET'];
    }



    if (!$token) {

        $response = log_api_key_access_failure(
            $db, 0, 'Fail_Attempt', 'No API key header'
        );

        echo json_encode($response);
        exit;
    } else {
        $api_key = $token;

       // print $api_key;
       // exit;

        if (preg_match('/^([a-z0-9]{8})(.+)$/', $api_key, $matches)) {

            // print_r($matches);

            $api_key_code = $matches[1];

            $_tmp = preg_replace('/^\./', '', $matches[2]);

            //print $_tmp;

            $api_key_secret = base64_decode($_tmp);
            // print $api_key_secret;
            // exit;
            $sql = sprintf(
                'SELECT `API Key Scope`,`User Key`,`User Active`,`API Key Key`,`API Key Active`,`API Key User Key`,`API Key Hash` FROM `API Key Dimension` LEFT JOIN `User Dimension` ON (`API Key User Key`=`User Key`) WHERE `API Key Code`=%s',
                prepare_mysql($api_key_code)
            );




            if ($row = $db->query($sql)->fetch()) {



                if ($row['API Key Active'] != 'Yes') {
                    $response = log_api_key_access_failure(
                        $db, $row['API Key Key'], 'Fail_Access', 'API Key not active'
                    );
                    echo json_encode($response);
                    exit;
                }



                if (!password_verify($api_key_secret, $row['API Key Hash'])) {


                    $response = log_api_key_access_failure(
                        $db, $row['API Key Key'], 'Fail_Access', 'API Key not valid'
                    );
                    echo json_encode($response);
                    exit;
                }


                if(!in_array($row['API Key Scope'],array('Nano Services','Box'))){

                    if ($row['User Active'] != 'Yes') {

                        if ($row['User Key'] == '') {
                            $response = log_api_key_access_failure(
                                $db, $row['API Key Key'], 'Fail_Access', 'User not found'
                            );
                            echo json_encode($response);
                            exit;
                        } else {
                            $response = log_api_key_access_failure(
                                $db, $row['API Key Key'], 'Fail_Access', 'User is not active'
                            );
                            echo json_encode($response);
                            exit;
                        }
                    }


                }




                return array(
                    $row['API Key User Key'],
                    $row['API Key Key'],
                    $row['API Key Scope']
                );
            } else {
                $response = log_api_key_access_failure(
                    $db, 0, 'Fail_Attempt', 'API Key not found'
                );

                echo json_encode($response);
                exit;
            }


        } else {

            $response = log_api_key_access_failure(
                $db, 0, 'Fail_Attempt', 'Invalid API key'
            );
            echo json_encode($response);
            exit;
        }


    }

}




function log_api_key_access_failure($db, $api_key_key, $fail_type, $fail_reason) {

    include_once 'utils/network_functions.php';

    $fail_code = hash('crc32', $fail_reason, false);
    $method    = $_SERVER['REQUEST_METHOD'];


    $debug=json_encode(array($fail_reason,$_SERVER,$_REQUEST));

    $sql       = sprintf(
        'INSERT INTO `API Request Dimension` (`API Key Key`,`Date`,`Response`,`Response Code`,`IP`,`HTTP Method`,`Debug`) VALUES(%d,%s,%s,%s,%s,%s,%s)', $api_key_key, prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql($fail_type), prepare_mysql($fail_code), prepare_mysql(ip()),
        prepare_mysql($method),prepare_mysql($debug)
    );

    $db->exec($sql);

    return array(
        'state' => 'Error',
        'code'  => $fail_code,
        'msg'   => $fail_reason
    );

}


function log_api_key_access_success($db, $api_key_key, $success_reason, $debug = '') {

    include_once 'utils/network_functions.php';

    $success_code = hash('crc32', $success_reason, false);

    $method = $_SERVER['REQUEST_METHOD'];


    $debug=json_encode(array($debug,$_SERVER,$_REQUEST));


    $sql = sprintf(
        'INSERT INTO `API Request Dimension` (`API Key Key`,`Date`,`Response`,`Response Code`,`IP`,`HTTP Method`,`Debug`) VALUES(%d,%s,%s,%s,%s,%s,%s)', $api_key_key, prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql('OK'), prepare_mysql($success_code),
        prepare_mysql(ip()), prepare_mysql($method), prepare_mysql($debug)

    );

    $db->exec($sql);

    return array(
        'state' => 'Success',
        'code'  => $success_code,
        'msg'   => $success_reason
    );

}



