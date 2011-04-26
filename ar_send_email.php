<?php
require_once 'common.php';
require_once 'ar_edit_common.php';



if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case 'report_issue':
    $data=prepare_values($_REQUEST,array(
                             'values'=>array('type'=>'json array')

                         ));
    report_issue($data);
    break;

}


function report_issue($data) {

if($data['values']['summary']==''){
$response=array('state'=>400,'msg'=>_('You must specify a summary of the issue.'));
echo json_encode($response);
exit;
}


    require("external_libs/mail/email_message.php");

    $from_name='Inikoo User';
    $from_address='anon.user@aw-inikoo.com';
    $reply_name=$from_name;
    $reply_address=$from_address;
    $reply_address=$from_address;
    $error_delivery_name=$from_name;
    $error_delivery_address=$from_address;
    $to_name="Inikoo Jira";
    $to_address=$data['values']['email'];
    $subject=$data['values']['summary'];
    $message=$data['values']['description']."\n\n".$data['values']['metadata'];
    $email_message=new email_message_class;
    $email_message->SetEncodedEmailHeader("To",$to_address,$to_name);
    $email_message->SetEncodedEmailHeader("From",$from_address,$from_name);
    $email_message->SetEncodedEmailHeader("Reply-To",$reply_address,$reply_name);
    $email_message->SetEncodedEmailHeader("Errors-To",$error_delivery_address,$error_delivery_name);
    $email_message->SetEncodedHeader("Subject",$subject);
    $email_message->AddQuotedPrintableTextPart($email_message->WrapText($message));
    $error=$email_message->Send();
    if (strcmp($error,"")) {
        $response=array('state'=>400,'msg'=>$error);
    } else {
        $response=array('state'=>200,'msg'=>'ok');
    }
    echo json_encode($response);

}