<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 July 2017 at 12:19:22 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

//use Aws\Ses\SesClient;

include_once '../vendor/autoload.php';

require_once 'common.php';
require_once 'utils/ar_web_common.php';
require_once 'utils/public_object_functions.php';

require_once 'utils/get_addressing.php';


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 407,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}
$tipo = $_REQUEST['tipo'];

switch ($tipo) {
    case 'recover_password':
        $data = prepare_values(
            $_REQUEST, array(
                'website_key'           => array('type' => 'key'),
                'webpage_key'           => array('type' => 'key'),
                'recovery_email'        => array('type' => 'string'),
                'cf-turnstile-response' => array(
                    'type'     => 'string',
                    'optional' => true

                )


            )
        );
        recover_password($db, $data, $editor, $website, $account);
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

function recover_password($db, $data, $editor, $website, $account)
{
    if ($website->settings('fu_secret') != '' and false) {

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


    $sql = sprintf(
        "SELECT  `Customer Type by Activity`,`Website User Key`,`Website User Customer Key` FROM `Website User Dimension` left join `Customer Dimension` on (`Customer Key`=`Website User Customer Key`)   WHERE  `Website User Handle`=%s AND `Website User Website Key`=%d",
        prepare_mysql($data['recovery_email']),
        $data['website_key']

    );


    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            if ($row['Customer Type by Activity'] == 'ToApprove') {
                $response = array(
                    'state'      => 400,
                    'msg'        => _('Account waiting for approval'),
                    'error_code' => 'waiting_approval'
                );
                echo json_encode($response);
                exit;
            }

            $customer = get_object('Customer', $row['Website User Customer Key']);


            $selector      = base64_url_encode(random_bytes(9));
            $authenticator = base64_url_encode(random_bytes(33));


            $hash = hash('sha256', $authenticator);

            $sql = sprintf(
                'INSERT INTO `Website Recover Token Dimension` (`Website Recover Token Website Key`,`Website Recover Token Selector`,`Website Recover Token Hash`,`Website Recover Token Website User Key`,`Website Recover Token Customer Key`,`Website Recover Token Expire`) 
            VALUES (%d,%s,%s,%d,%d,%s)',
                $data['website_key'],
                prepare_mysql($selector),
                prepare_mysql($hash),
                $row['Website User Key'],
                $row['Website User Customer Key'],
                prepare_mysql(date('Y-m-d H:i:s', time() + 1200))

            );


            $db->exec($sql);


            $email_template_type      = get_object('Email_Template_Type', 'Password Reminder|'.$website->get('Website Store Key'), 'code_store');
            $email_template           = get_object('email_template', $email_template_type->get('Email Campaign Type Email Template Key'));
            $published_email_template = get_object('published_email_template', $email_template->get('Email Template Published Email Key'));


            $send_data = array(
                'Email_Template_Type' => $email_template_type,
                'Email_Template'      => $email_template,
                'Reset_Password_URL'  => 'https://'.$website->get('Website URL').'/reset.php?s='.$selector.'&a='.$authenticator

            );


            $published_email_template->send($customer, $send_data);

            if ($published_email_template->error == true) {
                $response = array(
                    'state'      => 400,
                    'msg'        => $published_email_template->msg,
                    'error_code' => 'unknown'
                );
                echo json_encode($response);
                exit;
            } else {
                $response = array(
                    'state' => 200


                );
            }


            echo json_encode($response);
            exit;
        } else {
            $response = array(
                'state'      => 400,
                'error_code' => 'email_not_register'
            );
            echo json_encode($response);
            exit;
        }
    } else {
        $response = array(
            'state'      => 400,
            'error_code' => 'unknown'
        );
        echo json_encode($response);
        exit;
    }
}


?>
