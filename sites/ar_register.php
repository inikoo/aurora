<?php


include('common.php');
require_once 'class.Customer.php';
require_once 'class.User.php';
require_once 'class.EmailSend.php';

require_once 'ar_edit_common.php';
if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>400);
    echo json_encode($response);
    exit;
}

switch ($_REQUEST['tipo']) {
case('change_password'):
    $password=$_REQUEST['password'];
    change_password($password);
    break;
case('send_lost_password_email'):
    $email=$_REQUEST['email'];
    send_lost_password_email($email);
    break;
case('register_customer'):
    $data=prepare_values($_REQUEST,array(
                             'values'=>array('type'=>'json array')

                         ));
    register_customer($data);
    break;
case('check_email'):
    if (!isset($_REQUEST['email'] )) {
        $response=array('state'=>401);
        echo json_encode($response);
        exit;
    }
    $email=$_REQUEST['email'];

    $store_key=$_REQUEST['store_key'];
    $found=check_email_users($email,$store_key);

    if ($found) {
        $response=array('state'=>200,'result'=>'found','email'=>$found);
        echo json_encode($response);
        exit;
    } else {
        $response=array('state'=>200,'result'=>'new');
        echo json_encode($response);
        exit;

    }




    break;
default:
    $response=array('state'=>402);
    echo json_encode($response);
    exit;

}

function register_customer($data) {
    global $store_key;

    if ($data['values']['customer_is_company']) {
        $data['values']['customer_is_company']='Company';
        $data['values']['customer_name']=$data['values']['company_name'];

    } else {
        $data['values']['customer_is_company']='Person';
        $data['values']['customer_name']=$data['values']['contact_name'];
    }

    if ($data['values']['emarketing'])
        $data['values']['emarketing']='Yes';
    else
        $data['values']['emarketing']='No';
    if ($data['values']['newsletter'])
        $data['values']['newsletter']='Yes';
    else
        $data['values']['newsletter']='No';


   


    $translate=array(
                   'email'=>'Customer Main Plain Email',
                   'customer_type'=>'Customer Category',
                   'customer_type_other'=>'Customer Category Data',
                   'customer_is_company'=>'Customer Type',
                   'tax_number'=>'Customer Tax Number',
                   'company_name'=>'Customer Company Name',
                   'contact_name'=>'Customer Main Contact Name',
                   'customer_name'=>'Customer Name',
                   'internal'=>'Customer Address Line 1',
                   'building'=>'Customer Address Line 2',
                   'street'=>'Customer Address Line 3',
                   'town'=>'Customer Address Town',
                   'postal_code'=>'Customer Address Postal Code',
                   'country_d2'=>'Customer Address Country Second Division',
                   'country_d1'=>'Customer Address Country First Division',
                   'country'=>'Customer Address Country Name',
                   'newsletter'=>'Customer Send Newsletter',
                   'emarketing'=>'Customer Send Email Marketing'
               );

    $customer_data=array();
    foreach($data['values'] as $key=>$value) {
        if (array_key_exists($key,$translate))
            $customer_data[$translate[$key]]=$value;
    }
    $customer_data['Customer Store Key']=$store_key;



    $customer=new Customer('find create',$customer_data);
    if ($customer->id) {
        $data=array(
                  'User Handle'=>$customer->data['Customer Main Plain Email'],
                                'User Type'=>'Customer',
                                'User Site Key'=>$customer->data['Customer Store Key'],
                                             'User Password'=>$data['values']['password'],
                                                              'User Active'=>'Yes',
                                                                             'User Alias'=>$customer->data['Customer Name'],
                                                                                           'User Parent Key'=>$customer->id
              );
        
        $user=new user('new',$data);

        // print_r($user);
        // exit;

        $_SESSION['logged_in']=true;
        $_SESSION['logged_in_page']=$customer->data['Customer Store Key'];
        $_SESSION['user_key']=$user->id;
         $_SESSION['customer_key']=$customer->id;
         
         $auth=new Auth(IKEY,SKEY);
         $auth->set_user_key($user->id);
         $auth->create_user_log();
         
        //print_r($user);
        $email_send=0;
        if ($customer->new or $user->new) {
            $email_to_send=new EmailSend();
            $email_to_send->compose_registration_email($user->id,array('Template'=>'emails/html_email_basic_template.html'));
            if (!$email_to_send->error)
                //print_r($email_to_send);
                $email_send=$email_to_send->send();
        }

        if ($user->new)
            $response= array('state'=>200,'action'=>'created','user_key'=>$user->id,'customer_key'=>$customer->id,'email_send'=>$email_send);
        else
            $response= array('state'=>200,'action'=>'found','user_key'=>$user->id,'customer_key'=>$customer->id,'email_send'=>$email_send);



    } else {
        $response= array('state'=>400,'action'=>'error','msg'=>'Customer can not be created');

    }



    echo json_encode($response);
}


function change_password($password) {
    global $user;
    $user->change_password($password);
    if ($user->updated) {
        $response=array('state'=>200,'result'=>'ok',);
        echo json_encode($response);
        exit;
    } else {
        $response=array('state'=>200,'result'=>'error','msg'=>$user->msg);
        echo json_encode($response);
        exit;

    }
}

function send_lost_password_email($email) {
    global $secret_key,$store_key;

    $user_key=check_email_users($email,$store_key);
    if (!$user_key) {
        $customer_key=check_email_customers($email,$store_key);
        if ($customer_key) {
            $customer = new Customer('id',$customer_key);
            $customer->create_user();
            if ($customer->user_key) {
                $user_key=$customer->user_key;
            } else {
                $response=array('state'=>200,'result'=>'error','msg'=>$customer->msg);
                echo json_encode($response);
                exit;
            }

        }

    }




    if ($user_key) {
        $email_to_send=new EmailSend();
        $email_to_send->compose_lost_password_email($user_key,array(
                    'Template'=>'emails/html_email_basic_template.html',
                    'secret_key'=>$secret_key.$store_key)

                                                   );
        if ($email_to_send->error) {
            $response=array('state'=>200,'result'=>'error');


        } else {

            $email_send=$email_to_send->send();
            if ($email_to_send->error) {
                $response=array('state'=>200,'result'=>'error');
            } else {
                $response=array('state'=>200,'result'=>'send');

            }

        }

        echo json_encode($response);
        exit;

    } else {

        $response=array('state'=>200,'result'=>'new');
        echo json_encode($response);
        exit;

    }





}

function check_email_customers($email,$store_key) {


    $sql=sprintf('select `Customer Key` from `Customer Dimension`  where  `Customer Store Key`=%d  and `Customer Main Plain Email`=%s',$store_key,prepare_mysql($email));
    //print $sql;
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res)) {


        return $row['Customer Key'];



    } else {

        return false;


    }
}



function check_email_users($email,$store_key) {
    $sql=sprintf('select `User Key`,`User Parent Key`, `User Handle` from `User Dimension`  where  `User Type`="Customer_%d"  and `User Handle`=%s',$store_key,prepare_mysql($email));

    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res)) {
        return $row['User Key'];


    } else {
        return 0;
    }
}


?>