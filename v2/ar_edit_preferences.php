<?php
/*
 Copyright (c) 2011, Inikoo

 Version 2.0
*/

require_once 'common.php';
require_once 'class.User.php';
require_once 'ar_edit_common.php';


$editor=array(
            'User Key'=>$user->id
        );



if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>405,'msg'=>'Non acceptable request (t)');
    echo json_encode($response);
    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {

case('change_theme'):
    $data=prepare_values($_REQUEST,array(
                             'theme_key'=>array('type'=>'theme'),
                             'user_key'=>array('type'=>'theme'),
                         ));

    change_theme($data);
    break;
case('change_background_theme'):
    $data=prepare_values($_REQUEST,array(
                             'background_theme_key'=>array('type'=>'theme'),
                             'user_key'=>array('type'=>'theme'),
                         ));

    change_background_theme($data);
    break;



default:
    $response=array('state'=>404,'msg'=>_('Sub-operation not found'));
    echo json_encode($response);
}

function change_background_theme($data) {
    $_user=new User($data['user_key']);
    $_user->update('User Theme Background Key',array('value'=>$data['background_theme_key']));
    
    if($_user->updated){
       $response= array('state'=>200,'newvalue'=>$_user->data['User Theme Background Key'],'key'=>'change_background_theme');
    }else{
       $response= array('state'=>400,'key'=>'no_change');
    }
    
   echo json_encode($response);
}

function change_theme($data) {
    $_user=new User($data['user_key']);
    $_user->update('User Theme Key',array('value'=>$data['theme_key']));
    
    if($_user->updated){
       $response= array('state'=>200,'newvalue'=>$_user->data['User Theme Key'],'key'=>'change_theme');
    }else{
       $response= array('state'=>400,'key'=>'no_change');
    }
    
   echo json_encode($response);
}


?>
