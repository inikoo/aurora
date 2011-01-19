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
case('create_email_marketing'):
    $data=prepare_values($_REQUEST,array(
      'store_key'=>array('type'=>'key'),
                             'name'=>array('type'=>'string'),
                             'objective'=>array('type'=>'string'),
                         ));
    new_email_marketing($data);


    break;
}


function new_email_marketing($data) {
require_once 'class.EmailCampaign.php';

$email_campaign_data=array(
    'Email Campaign Store Key'=>$data['store_key'],
    'Email Campaign Name'=>$data['name'],
    'Email Campaign Objective'=>$data['objective']

);
//print_r($email_campaign_data);
$email_campaign=new EmailCampaign('create',$email_campaign_data);
if($email_campaign->id){

 $response= array('state'=>200,'action'=>'created','email_campaign_key'=>$email_campaign->id);
}else{
 $response= array('state'=>400,'msg'=>$email_campaign->msg);

}
    echo json_encode($response);

}


?>
