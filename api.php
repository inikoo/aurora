<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 21 November 2015 at 14:41:00 GMT GMT Sheffield UK

 Version 2.0
*/

error_reporting(E_ALL);
date_default_timezone_set('UTC');
include_once 'utils/general_functions.php';
include_once 'utils/object_functions.php';

$db = get_db();

list($user_key, $api_key_key, $scope) = authenticate($db);


authorization($db, $user_key, $api_key_key, $scope);




function authorization($db, $user_key, $api_key_key, $scope) {
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

    $user = check_permisions($db, $user_key, $scope, $api_key_key);


    $editor = array(
        'Author Name'  => $user->data['User Alias'],
        'Author Alias' => $user->data['User Alias'],
        'Author Type'  => $user->data['User Type'],
        'Author Key'   => $user->data['User Parent Key'],
        'User Key'     => $user->id,
        'Date'         => gmdate('Y-m-d H:i:s')
    );


    switch ($scope) {
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
        default:
            $response = log_api_key_access_failure(
                $db, $api_key_key, 'Fail_Access', 'Unknown scope'
            );
            echo json_encode($response);
            exit;
            break;
    }


}


function check_permisions($db, $user_key, $scope) {
    include_once 'class.User.php';
    $user = new User($user_key);

    return $user;
}


function parse_scope($request) {

    if ($request == '/api/timesheet_record') {
        return 'Timesheet';
    }elseif ($request == '/api/stock') {
        return 'Stock';
    }elseif ($request == '/api/picking') {
        return 'Picking';
    }

    return false;

}


function authenticate($db) {

   // print_r($_SERVER);
   // print_r(apache_request_headers());

    $_headers=apache_request_headers();

        if(!isset($_SERVER['HTTP_X_AUTH_KEY']) and isset($_headers['HTTP_X_AUTH_KEY'])){
            $_SERVER['HTTP_X_AUTH_KEY']=$_headers['HTTP_X_AUTH_KEY'];
        }





    if (!isset($_SERVER['HTTP_X_AUTH_KEY'])) {

        $response = log_api_key_access_failure(
            $db, 0, 'Fail_Attempt', 'No API key header'
        );

        echo json_encode($response);
        exit;
    } else {
        $api_key = $_SERVER['HTTP_X_AUTH_KEY'];



        if (preg_match('/^([a-z0-9]{8})(.+)$/', $api_key, $matches)) {

            print_r($matches);

            $api_key_code   = $matches[1];
            $api_key_secret = base64_decode(preg_replace('/^\./','',$matches[2]));


            $sql = sprintf(
                'SELECT `API Key Scope`,`User Key`,`User Active`,`API Key Key`,`API Key Active`,`API Key User Key`,`API Key Hash` FROM `API Key Dimension` LEFT JOIN `User Dimension` ON (`API Key User Key`=`User Key`) WHERE `API Key Code`=%s',
                prepare_mysql($api_key_code)
            );



            if ($row = $db->query($sql)->fetch()) {


                print_r($row);


                if ($row['API Key Active'] != 'Yes') {
                    $response = log_api_key_access_failure(
                        $db, $row['API Key Key'], 'Fail_Access', 'API Key not active'
                    );
                    echo json_encode($response);
                    exit;
                }

                print $api_key_secret;


                if (!password_verify($api_key_secret, $row['API Key Hash'])) {


                    $response = log_api_key_access_failure(
                        $db, $row['API Key Key'], 'Fail_Access', 'API Key not valid'
                    );
                    echo json_encode($response);
                    exit;
                }


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


function get_db() {


    include_once 'keyring/dns.php';


    $db = new PDO(
        "mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
    );
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


    return $db;


}


function log_api_key_access_failure($db, $api_key_key, $fail_type, $fail_reason) {

    include_once 'utils/detect_agent.php';

    $fail_code = hash('crc32', $fail_reason, false);
    $method    = $_SERVER['REQUEST_METHOD'];
    $sql       = sprintf(
        'INSERT INTO `API Request Dimension` (`API Key Key`,`Date`,`Response`,`Response Code`,`IP`,`HTTP Method`) VALUES(%d,%s,%s,%s,%s,%s)', $api_key_key, prepare_mysql(gmdate('Y-m-d H:i:s')),
        prepare_mysql($fail_type), prepare_mysql($fail_code), prepare_mysql(ip()), prepare_mysql($method)
    );

    $db->exec($sql);

    return array(
        'state' => 'Error',
        'code'  => $fail_code,
        'msg'   => $fail_reason
    );

}


function log_api_key_access_success($db, $api_key_key, $success_reason, $debug = '') {

    include_once 'utils/detect_agent.php';

    $success_code = hash('crc32', $success_reason, false);

    $method = $_SERVER['REQUEST_METHOD'];

    $sql = sprintf(
        'INSERT INTO `API Request Dimension` (`API Key Key`,`Date`,`Response`,`Response Code`,`IP`,`HTTP Method`,`Debug`) VALUES(%d,%s,%s,%s,%s,%s,%s)', $api_key_key,
        prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql('OK'), prepare_mysql($success_code), prepare_mysql(ip()), prepare_mysql($method), prepare_mysql($debug)

    );

    $db->exec($sql);

    return array(
        'state' => 'Success',
        'code'  => $success_code,
        'msg'   => $success_reason
    );

}


?>
