<?php

if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}

$tipo=$_REQUEST['tipo'];
switch($tipo){
	case 'user_profile':
	    $data=prepare_values($_REQUEST,array(
                             'supplier_key'=>array('type'=>'key'),
                             'query'=>array('type'=>'string')
                         ));
		show_user_profile($data);
		break;
		
	default:
		break;
}



function show_user_profile($data){
	
}



?>

