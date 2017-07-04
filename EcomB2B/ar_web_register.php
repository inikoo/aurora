<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 June 2017 at 20:37:24 GMT+8, Cyberjaya, Malaysia
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
    case 'register':
        $data = prepare_values(
            $_REQUEST, array(
                         'data'     => array('type' => 'json array'),
                         'store_key' => array('type' => 'key')
                     )
        );
        register($db, $data,$editor);
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

function register($db, $data,$editor) {

    include_once 'class.Public_Store.php';

    $store=new Public_Store($data['store_key']);


    $store->editor=$editor;
    $raw_data=$data['data'];



    if($store->id){
        $customer_data=array(
            'Customer Main Contact Name'=>$raw_data['contact_name'],
            'Customer Company Name'=>$raw_data['company'],
            'Customer Registration Number'=>$raw_data['registration_number'],
            'Customer Tax Number'=>$raw_data['tax_number'],
            'Customer Main Plain Email'=>$raw_data['email'],
            'Customer Main Plain Mobile'=>$raw_data['mobile'],
            'Customer Registration Number'=>$raw_data['registration_number'],

        );

        if(array_key_exists('locality',$raw_data)){$customer_data['Customer Contact Address locality']=$raw_data['locality'];}
        if(array_key_exists('postalCode',$raw_data)){$customer_data['Customer Contact Address postalCode']=$raw_data['postalCode'];}
        if(array_key_exists('addressLine1',$raw_data)){$customer_data['Customer Contact Address addressLine1']=$raw_data['addressLine1'];}
        if(array_key_exists('addressLine2',$raw_data)){$customer_data['Customer Contact Address addressLine2']=$raw_data['addressLine2'];}
        if(array_key_exists('administrativeArea',$raw_data)){$customer_data['Customer Contact Address administrativeArea']=$raw_data['administrativeArea'];}
        if(array_key_exists('dependentLocality',$raw_data)){$customer_data['Customer Contact Address dependentLocality']=$raw_data['dependentLocality'];}
        if(array_key_exists('sortingCode',$raw_data)){$customer_data['Customer Contact Address sortingCode']=$raw_data['sortingCode'];}
        if(array_key_exists('country',$raw_data)){$customer_data['Customer Contact Address country']=$raw_data['country'];}





        list($customer,$website_user)=$store->create_customer($customer_data,array('Website User Password'=>$raw_data['password']));

        if($store->new_customer and $store->new_website_user){

            include_once('class.WebAuth.php');
            $auth=new WebAuth();

           list($logged_in,$website_user_log_key)=$auth->authenticate_from_register($website_user->id, $customer->id, $store->get('Store Website Key'));

            if($logged_in){
                $_SESSION['logged_in']=true;
                $_SESSION['customer_key']=$customer->id;
                $_SESSION['website_user_key']=$website_user->id;
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
                             $store->get('Store Website Key'),
                             prepare_mysql($selector),
                             prepare_mysql(hash('sha256', $authenticator)),
                             $website_user->id,
                             $customer->id,
                             $_SESSION['website_user_log_key'],
                             prepare_mysql(date('Y-m-d H:i:s', time() + 864000))

                );

                $db->exec($sql);


            }else{

                echo json_encode(array('state' => 400, 'reason'  => $reason  ));
                exit;

            }




            echo json_encode(array('state' => 200));
            exit;

        }else{
            echo json_encode(array('state' => 400, 'msg'  => $store->msg  ));
            exit;
        }


    }else{
        echo json_encode(array('state' => 400, 'resp'  => 'Store not found '.$data['store_key']));
        exit;

    }










}


?>
