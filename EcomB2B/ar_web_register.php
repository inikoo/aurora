<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 June 2017 at 20:37:24 GMT+8, Cyberjaya, Malaysia
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
    case 'register':
        $data = prepare_values(
            $_REQUEST, array(
                'data'      => array('type' => 'json array'),
                'store_key' => array('type' => 'key')
            )
        );
        register($db, $website, $data, $editor);
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

/**
 * @param $db      \PDO
 * @param $website \Public_Website
 * @param $data
 * @param $editor
 *
 * @throws \Exception
 */
function register($db, $website, $data, $editor)
{
    include_once 'utils/new_fork.php';
    include_once 'class.Public_Store.php';

    $store = new Public_Store($data['store_key']);


    $labels = $website->get('Localised Labels');


    $store->editor = $editor;
    $raw_data      = $data['data'];


    if ($store->id) {
        if ($website->settings('fu_secret') != ''  ) {
            if (empty($raw_data['cf-turnstile-response'])) {
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


            $secretKey = $website->settings('fu_secret');

            $turnstile_secret   = $website->settings('fu_secret');
            $turnstile_response = $raw_data['cf-turnstile-response'];
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


        foreach ($raw_data as $_key => $value) {
            if ($_key === 'new-password') {
                continue;
            }
            $value=preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', strip_tags($value));
            $raw_data[$_key]=$value;
        }


        $customer_data = array(
            'Customer Main Contact Name'   => $raw_data['name'],
            'Customer Company Name'        => $raw_data['organization'],
            'Customer Registration Number' => $raw_data['registration_number'],
            'Customer Tax Number'          => $raw_data['tax_number'],
            'Customer Main Plain Email'    => $raw_data['email'],
            'Customer Main Plain Mobile'   => $raw_data['tel'],
            'Customer Type by Activity'    => ($website->get('Website Registration Type') == 'ApprovedOnly' ? 'ToApprove' : 'Active')

        );


        if (array_key_exists('locality', $raw_data)) {
            $customer_data['Customer Contact Address locality'] = $raw_data['locality'];
        }
        if (array_key_exists('postalCode', $raw_data)) {
            $customer_data['Customer Contact Address postalCode'] = $raw_data['postalCode'];
        }
        if (array_key_exists('addressLine1', $raw_data)) {
            $customer_data['Customer Contact Address addressLine1'] = $raw_data['addressLine1'];
        }
        if (array_key_exists('addressLine2', $raw_data)) {
            $customer_data['Customer Contact Address addressLine2'] = $raw_data['addressLine2'];
        }
        if (array_key_exists('administrativeArea', $raw_data)) {
            $customer_data['Customer Contact Address administrativeArea'] = $raw_data['administrativeArea'];
        }
        if (array_key_exists('dependentLocality', $raw_data)) {
            $customer_data['Customer Contact Address dependentLocality'] = $raw_data['dependentLocality'];
        }
        if (array_key_exists('sortingCode', $raw_data)) {
            $customer_data['Customer Contact Address sortingCode'] = $raw_data['sortingCode'];
        }
        if (array_key_exists('country', $raw_data)) {
            $customer_data['Customer Contact Address country'] = $raw_data['country'];
        }

        if (isset($raw_data['subscription']) and $raw_data['subscription'] == 'on') {
            $customer_data['Customer Send Newsletter']       = 'Yes';
            $customer_data['Customer Send Email Marketing']  = 'Yes';
            $customer_data['Customer Send Basket Emails']    = 'Yes';
            $customer_data['Customer Send Postal Marketing'] = 'Yes';
        } else {
            $customer_data['Customer Send Newsletter']       = 'No';
            $customer_data['Customer Send Email Marketing']  = 'No';
            $customer_data['Customer Send Basket Emails']    = 'No';
            $customer_data['Customer Send Postal Marketing'] = 'No';
        }


        list($customer, $website_user) = $store->create_customer($customer_data, array('Website User Password' => $raw_data['new-password']));

        if ($store->new_customer and $store->new_website_user) {
            foreach ($raw_data as $_key => $value) {
                if (preg_match('/^poll_(\d+)/i', $_key, $matches)) {
                    $poll_key = $matches[1];
                    $customer->update(array('Customer Poll Query '.$poll_key => $value), 'no_history');
                }
            }


            new_housekeeping_fork(
                'au_housekeeping',
                array(
                    'type'         => 'customer_registered',
                    'customer_key' => $customer->id,
                    'website_key'  => $website->id
                ),
                DNS_ACCOUNT_CODE
            );


            if ($website->get('Website Registration Type') != 'ApprovedOnly') {
                include_once('class.WebAuth.php');
                $auth = new WebAuth($db);

                list($logged_in, $website_user_log_key) = $auth->authenticate_from_register($website_user->id, $customer->id, $store->get('Store Website Key'));

                if ($logged_in) {
                    $_SESSION['logged_in']        = true;
                    $_SESSION['customer_key']     = $customer->id;
                    $_SESSION['website_user_key'] = $website_user->id;

                    $_SESSION['UTK'] = [
                        'C'   => $customer->id,
                        'WU'  => $website_user->id,
                        'WUL' => $website_user_log_key,
                        'CUR' => $website->get('Currency Code'),
                        'LOC' => $website->get('Website Locale')
                    ];

                    $token = Token::customPayload($_SESSION['UTK'], JWT_KEY);
                    setcookie('UTK', $token, time() + 157680000);
                    setcookie('AUK', strtolower(DNS_ACCOUNT_CODE).'.'.$_SESSION['customer_key'], time() + 157680000);
                } else {
                    echo json_encode(
                        array(
                            'state' => 400,
                        )
                    );
                    exit;
                }
            }


            if (array_key_exists('hokodo-company-id', $raw_data) and $raw_data['hokodo-company-id'] != '') {
                try {
                    create_hokodo_organization($customer, $raw_data['hokodo-company-id']);
                } catch (Exception $e) {
                    //
                }
            }


            echo json_encode(
                array(
                    'state' => 200,
                    'msg'   => 'reg'
                )
            );
            exit;
        } else {
            echo json_encode(
                array(
                    'state' => 400,
                    'msg'   => $store->msg
                )
            );
            exit;
        }
    } else {
        echo json_encode(
            array(
                'state' => 400,
                'resp'  => 'Store not found '.$data['store_key']
            )
        );
        exit;
    }
}


function create_hokodo_organization($customer, $hokodo_company_id)
{
    include_once 'hokodo/api_call.php';

    $website = get_object('Website', $_SESSION['website_key']);
    $api_key = $website->get_api_key('Hokodo');

    $account = get_object('Account', 1);

    $unique_id = strtolower($account->get('Account Code')).'-'.$customer->id;

    $email = $customer->get('Customer Main Plain Email');

    $raw_results  = api_post_call('organisations', array(
        "unique_id"  => $unique_id,
        "company"    => $hokodo_company_id,
        'registered' => date('c', strtotime($customer->get('Customer First Contacted Date')))
    ), $api_key);
    $raw_results0 = $raw_results;
    //print_r($raw_results0);

    if (!empty($raw_results['id'])) {
        $org_id = $raw_results['id'];

        $data = array(
            "name"          => $customer->get('Customer Main Contact Name'),
            "email"         => trim($email),
            "phone"         => trim($customer->get_telephone()),
            'registered'    => date('c', strtotime($customer->get('Customer First Contacted Date'))),
            'organisations' => []
        );
        //$user_id = false;

        $raw_results  = api_post_call('users', $data, $api_key);
        $raw_results1 = $raw_results;
        //print_r($raw_results1);
        $user_id     = $raw_results['id'];
        $data        = [
            'id'   => $org_id,
            'role' => 'member'
        ];
        $raw_results = api_post_call('users/'.$user_id.'/organisations', $data, $api_key);

        //print_r($raw_results);
        $customer->fast_update(
            [
                'hokodo_co_id'   => $hokodo_company_id,
                'hokodo_org_id'  => $org_id,
                'hokodo_user_id' => $user_id,
                'hokodo_data'    => json_encode(['name' => $customer->get('Customer Company Name')]),
                'hokodo_type'    => 'registered-company'

            ]
        );
    }
}