<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 30 May 2016 at 18:27:54 CEST, Mijas Costa, Spain

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

require_once 'common.php';
require_once 'utils/ar_common.php';



$tipo=$_REQUEST['tipo'];


switch ($tipo) {
case 'content':

	$data=prepare_values($_REQUEST, array(
			'request'=>array('type'=>'string'),
			'metadata'=>array('type'=>'json array'),
			'old_view'=>array('type'=>'json array'),
		));


	$response=get_content($db, $smarty, $website, $user, $account, $data);
	
	break;
case 'marginals':

	$data=prepare_values($_REQUEST, array(
			'request'=>array('type'=>'string'),
			'metadata'=>array('type'=>'json array')
		));


	$response=get_marginals($db, $smarty, $website, $user, $account, $data);
	
	break;	

default:
	$response=array('state'=>404, 'resp'=>'Operation not found 2');
	echo json_encode($response);

}

function get_marginals($db, $smarty, $website, $user, $account, $data) {

	require_once 'utils/parse_request.php';


    $header=$smarty->fetch('header.tpl');
    $footer=$smarty->fetch('footer.tpl');

	$response=array('state'=>200, 'header'=>$header,'footer'=>$footer);



	echo json_encode($response);

}



function get_content($db, $smarty, $website, $user, $account, $data) {

	require_once 'utils/parse_request.php';



	if (isset($data['metadata']['help']) and $data['metadata']['help'] ) {
		get_help($data, $modules, $db);
		return;
	}


	if (isset($data['metadata']['reload']) and $data['metadata']['reload'] ) {
		$reload=true;
	}else {
		$reload=false;
	}

	list($webpage,$request)=parse_request($data, $db, $website, $account, $user);



	/*

	$sql=sprintf('insert into `User System View Fact`  (`User Key`,`Date`,`Module`,`Section`,`Tab`,`Parent`,`Parent Key`,`Object`,`Object Key`)  values (%d,%s,%s,%s,%s,%s,%s,%s,%s)',
		$user->id,
		prepare_mysql(gmdate('Y-m-d H:i:s')),
		prepare_mysql($state['module']),
		prepare_mysql($state['section']),
		prepare_mysql(($state['subtab']!=''?$state['subtab']:$state['tab'])),
		prepare_mysql($state['parent']),
		prepare_mysql($state['parent_key']),
		prepare_mysql($state['object']),
		prepare_mysql($state['key'])

	);
	$db->exec($sql);

*/

$view=array('webpage_key'=>$webpage->id,'request',$request);

	$response=array('state'=>200, 'content'=>$webpage->get_content(),'view'=>$view);



	echo json_encode($response);

}




?>
