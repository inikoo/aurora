<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:2 July 2017 at 15:49:19 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

use ReallySimpleJWT\Token;


include_once 'ar_web_common_logged_out.php';

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
                         'handle'      => array('type' => 'string'),
                         'pwd'         => array('type' => 'string'),
                         'keep_logged' => array('type' => 'string'),
                         'cf-turnstile-response' => array(
                             'type'     => 'string',
                             'optional' => true

                         )
                     )
        );
        login($db, $data, $website);
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

function login($db, $data, $website) {

    // do not use turnstile
    if (false || $website->settings('fu_secret') != ''  ) {

        if (empty($data['cf-turnstile-response'])) {
            echo json_encode(
                array(
                    'state' => 400,
                    'msg'   => (!empty($labels['_captcha_missing']) ? $labels['_captcha_missing'] : _('Please check on the reCAPTCHA box'))

                )
            );
            exit;
        }

        $ip = '';
        if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }



        $turnstile_secret   = $website->settings('fu_secret');
        $turnstile_response = $data['cf-turnstile-response'];
        $url                = "https://challenges.cloudflare.com/turnstile/v0/siteverify";
        $post_fields        = "secret=$turnstile_secret&response=$turnstile_response&remoteip=$ip";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        $response = curl_exec($ch);
        curl_close($ch);

        $response_data = json_decode($response, true);


        if (!$response_data['success']) {
            echo json_encode(
                array(
                    'state' => 400,
                    'msg'   => (!empty($labels['_captcha_fail']) ? $labels['_captcha_fail'] : _('Captcha verification failed, please try again')),
                    'resp'  => $response_data['error-codes']
                )
            );
            exit;
        }


    }

    include_once 'class.WebAuth.php';
    $auth = new WebAuth($db);
    list($logged_in, $result, $customer_key, $website_user_key, $website_user_log_key) = $auth->authenticate_from_login($data['handle'], $data['pwd'], $website, $data['keep_logged']);


    if ($logged_in) {

        $_SESSION['logged_in']        = true;
        $_SESSION['customer_key']     = $customer_key;
        $_SESSION['website_user_key'] = $website_user_key;
        $_SESSION['UTK']              = [
            'C'   => $customer_key,
            'WU'  => $website_user_key,
            'WUL' => $website_user_log_key,
            'CUR'=>$website->get('Currency Code'),
            'LOC'=>$website->get('Website Locale')
        ];


        $token = Token::customPayload($_SESSION['UTK'], JWT_KEY);
        setcookie('UTK', $token, time() + 157680000,'/');
        setcookie('AUK', strtolower(DNS_ACCOUNT_CODE).'.'.$_SESSION['customer_key'], time() + 157680000,'/');


        echo json_encode(
            array(
                'state' => 200,
                'msg'   => 'L1'
            )
        );
        exit;

    } else {

        switch ($result) {
            case 'handle':
                $msg = _('Email not registered');
                break;
            case 'handle_active':
                $msg = _('This account is banned');
                break;
            case 'password':
                $msg = _('Incorrect password');
                break;
            case 'approved':
                $msg = _('Account waiting for approval');
                break;
            default:
                $msg = _('Invalid login credentials');
        }


        echo json_encode(
            array(
                'state' => 400,
                'msg'   => $msg
            )
        );
        exit;
    }


}



