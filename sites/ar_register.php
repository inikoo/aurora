<?php
include('common.php');
if(!isset($_REQUEST['tipo'])){
    $response=array('state'=>400);
    echo json_encode($response);
    exit;
}

switch($_REQUEST['tipo']){

case('check_mail'):
if(!isset($_REQUEST['email'] )){
    $response=array('state'=>401);
    echo json_encode($response);
    exit;
}
$email=$_REQUEST['email'];


$sql=sprintf('select `User Parent Key`, `User Handle`, from `User Dimension`  where `User Type`="Customer_%d"  and `User Handle`=%s',$store_key,prepare_mysql($email));
$res=mysql_query($sql);
if($row=mysql_query($res)){
 $response=array('state'=>200,'result'=>'found','email'=>$row['User Handle']);
    echo json_encode($response);
    exit;

}
$response=array('state'=>200,'result'=>'new');
    echo json_encode($response);
    exit;
break;

}
?>