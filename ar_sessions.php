<?php
require_once 'common.php';
require_once 'ar_common.php';

if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}



$tipo=$_REQUEST['tipo'];

switch ($tipo) {

case ('change_period'):
	$data=prepare_values($_REQUEST,array(
			'period'=>array('type'=>'string'),
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'key','optional'=>true),
			'from'=>array('type'=>'string','optional'=>true),
			'to'=>array('type'=>'string','optional'=>true)
		));


	change_period($data);

	break;
case('update'):

	$keys=preg_split('/-/',$_REQUEST['keys']);
	switch (count($keys)) {
	case 1:

		$value=$_REQUEST['value'];
		$_SESSION['state'][$keys[0]]=$value;
		//print $keys[0];
		echo $keys[0]."=".$value;
		break;
	case 2:
		$value=$_REQUEST['value'];
		//print $_SESSION['state'][$keys[0]][$keys[1]]."\n";
		//print $keys[0];
		//print $keys[1];
		//print $value;
		$_SESSION['state'][$keys[0]][$keys[1]]=$value;
		print $_SESSION['state'][$keys[0]][$keys[1]]."\n";
		//  $data=$session->read(session_id( ));

		break;
	case 3:
		$value=$_REQUEST['value'];
		$_SESSION['state'][$keys[0]][$keys[1]][$keys[2]]=$value;
		echo $keys[0]."|".$keys[1]."|".$keys[2]."=".$value;
		break;
	case 4:
		$value=$_REQUEST['value'];
		$_SESSION['state'][$keys[0]][$keys[1]][$keys[2]][$keys[3]]=$value;
		print $_SESSION['state'][$keys[0]][$keys[1]][$keys[2]][$keys[3]];
		print_r($_SESSION['state'][$keys[0]]);
		break;
	case 5:
		$value=$_REQUEST['value'];
		$_SESSION['state'][$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]]=$value;
		print $_SESSION['state'][$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]];

		break;

	}
	break;
case('update_plot_product'):
	$value=$_REQUEST['value'];
	if (preg_match('/^product\_(week|month|quarter|year)\_(sales|outers)$/',$value)) {
		$_SESSION['state']['product']['product']=$value;
		if (preg_match('/week/',$value))
			$plot_interval='week';
		elseif (preg_match('/month/',$value))
			$plot_interval='month';
		elseif (preg_match('/quarter/',$value))
			$plot_interval='quarter';
		elseif (preg_match('/year/',$value))
			$plot_interval='year';
		$data=$_SESSION['state']['product']['plot_data'][$plot_interval];
		$data['state']=200;
		echo json_encode($data);
		exit;
	}
	break;
}


function change_period($data) {
	include_once 'common_date_functions.php';

	$from=(isset($data['from'])?$data['from']:'');
	$to=(isset($data['to'])?$data['to']:'');

	list($period_label,$from,$to)=get_period_data($data['period'],$from,$to);


//print $from;

	switch ($data['parent']) {
	
	case('spinter_out_of_stock'):
		$_SESSION['state']['home']['splinters']['out_of_stock']['period']=$data['period'];
	break;
	default:
		$_SESSION['state'][$data['parent']]['period']=$data['period'];
		if ($data['period']=='day' or $data['period']=='f') {
			$_SESSION['state'][$data['parent']]['from']=$from;
			$_SESSION['state'][$data['parent']]['to']=$to;

		}


		break;
	}


	$response= array('state'=>200,'period_label'=>$period_label,'period'=>$data['period'],'to'=>$to,'from'=>$from);
	echo json_encode($response);

}


?>
