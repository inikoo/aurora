<?php
/*

  About:
  Author: Raul Perusquia <raul@inikoo.com>
  created: 9 May 2017 at 11:40:13 GMT-5, CdMx Mexico  

  Copyright (c) 2017, Inikoo

  Version 2.0
*/

if (count(get_included_files()) == 1) {
    exit();
}



if($logged_in) {
    $form_error='logged_in';
}else{

    include_once 'class.WebAuth.php';

    $auth = new WebAuth();




    list($logged_in, $result, $customer_key, $website_user_key, $website_user_log_key) = $auth->authenticate_from_reset_password(
        $_REQUEST['s'], $_REQUEST['a'], $website->id
    );



    if($logged_in){


        $_SESSION['logged_in']            = true;
        $_SESSION['customer_key']         = $customer_key;
        $_SESSION['website_user_key']     = $website_user_key;
        $_SESSION['website_user_log_key'] = $website_user_log_key;


        if(!isset($website_user)){
            $website_user = new Public_Website_User($_SESSION['website_user_key']);

        }

        if(!isset($customer)){
            $customer = new Public_Customer($_SESSION['customer_key']);
        }


        $smarty->assign('website_user', $website_user);
        $smarty->assign('customer', $customer);




        $form_error=false;
    }else{
        $_SESSION['logged_in']=false;

        $form_error=$result;


    }
}




$smarty->assign('logged_in', $logged_in);

$smarty->assign('form_error', $form_error);



$labels_fallback = array(
    'validation_required'           => _('This field is required'),
    'validation_same_password'      => _("Enter the same password as above"),
    'validation_minlength_password' => _("Enter at least 8 characters"),
    'validation_password_missing'   => _("Please enter your password")
);

$smarty->assign('labels_fallback', $labels_fallback);




?>