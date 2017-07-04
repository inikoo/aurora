<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 July 2017 at 12:19:22 GMT+8, Cyberjaya, Malaysia
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
    case 'recover_password':
        $data = prepare_values(
            $_REQUEST, array(
                         'website_key'   => array('type' => 'key'),
                         'recovery_email'      => array('type' => 'string')
                        

                     )
        );
        recover_password($db, $data, $editor);
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

function recover_password($db, $data, $editor) {



    $sql = sprintf(
        "SELECT `Website User Key`,`Website User Customer Key` from `Website User Dimension` WHERE  `Website User Handle`=%s AND `Website User Website Key`=%d",
        prepare_mysql($data['recovery_email']), $data['website_key']

    );


    if ($result=$db->query($sql)) {
        if ($row = $result->fetch()) {



            require_once "external_libs/random/lib/random.php";
            $selector = base64_encode(random_bytes(9));
            $authenticator = random_bytes(33);



            $sql=sprintf('insert into `Website Recover Token Dimension` (`Website Recover Token Website Key`,`Website Recover Token Selector`,`Website Recover Token Hash`,`Website Recover Token Website User Key`,`Website Recover Token Customer Key`,`Website Recover Token Expire`) 
            values (%d,%s,%s,%d,%d,%s)',
                         $data['website_key'],
                         prepare_mysql($selector),
                         prepare_mysql(hash('sha256', $authenticator)),
                         $row['Website User Key'],
                         $row['Website User Customer Key'],
                         prepare_mysql(date('Y-m-d H:i:s', time() + 1200))

            );



            $db->exec($sql);


            // send emiail




        }else{
            $response = array(
                'state' => 400,
                'error_code'  => 'email_not_register'
            );
            echo json_encode($response);
            exit;
        }
    }else {
        $response = array(
            'state' => 400,
            'error_code'  => 'unknown'
        );
        echo json_encode($response);
        exit;
    }




}


?>
