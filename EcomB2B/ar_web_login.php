<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:2 July 2017 at 15:49:19 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/get_addressing.php';


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 405,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}


$tipo = $_REQUEST['tipo'];

switch ($tipo) {
    case 'login':
        $data = prepare_values(
            $_REQUEST, array(
                         'website_key'   => array('type' => 'key'),
                         'handle'      => array('type' => 'string'),
                         'pwd'         => array('type' => 'string'),
                         'keep_logged' => array('type' => 'string'),

                     )
        );
        login($db, $data, $editor);
        break;


    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
        break;
}

function login($db, $data, $editor) {

    include_once 'class.WebAuth.php';

    $auth = new WebAuth();


    list($logged_in,$result,$customer_key,$website_user_key,$website_user_log_key)=$auth->authenticate_from_login($data['handle'],$data['pwd'],$data['website_key'],$data['keep_logged']);


    if($logged_in){

        $_SESSION['logged_in']=true;
        $_SESSION['customer_key']=$customer_key;
        $_SESSION['website_user_key']=$website_user_key;
        $_SESSION['website_user_log_key']=$website_user_log_key;

        require_once "external_libs/random/lib/random.php";
        $selector = base64_encode(random_bytes(9));
        $authenticator = random_bytes(33);

        setcookie(
            'rmb',
            $selector.':'.base64_encode($authenticator),
            time() + 864000,
            '/'
            //,'',
            //true, // TLS-only
            //true  // http-only
        );


        $sql=sprintf('insert into `Website Auth Token Dimension` (`Website Auth Token Website Key`,`Website Auth Token Selector`,`Website Auth Token Hash`,`Website Auth Token Website User Key`,`Website Auth Token Customer Key`,`Website Auth Token Website User Log Key`,`Website Auth Token Expire`) 
            values (%d,%s,%s,%d,%d,%d,%s)',
                     $data['website_key'],
                     prepare_mysql($selector),
                     prepare_mysql(hash('sha256', $authenticator)),
                     $website_user_key,
                     $customer_key,
                     $website_user_log_key,
                     prepare_mysql(date('Y-m-d H:i:s', time() + 864000))

            );

       // print $sql;

        $db->exec($sql);

        echo json_encode(
            array(
                'state' => 200
            )
        );
        exit;

    }else{

        switch ($result){
            case 'handle':
                $msg=_('Email not registered');
                break;
            case 'handle_active':
                $msg=_('This account is banned');
                break;
            case 'password':
                $msg=_('Incorrect password');
                break;
            default:
                $msg=_('Invalid login credentials');
        }


        echo json_encode(
            array(
                'state' => 400,
                'msg'   =>$msg
            )
        );
        exit;
    }




}


?>
