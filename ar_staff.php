<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
require_once 'common.php';
//require_once '_order.php';

//require_once '_contact.php';
require_once 'class.Customer.php';
require_once 'class.Timer.php';




if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case('company_departments'):

	list_company_departments();
	break;
case('company_positions'):
case('positions'):

	list_company_positions();
	break;
case('company_areas'):
	list_company_areas();
	break;
case('is_position_code'):
	is_position_code();
	break;
case('is_position_name'):
	is_position_name();
	break;
case('is_company_area_code'):
	is_company_area_code();
	break;
case('is_company_area_name'):
	is_company_area_name();
	break;

case('is_company_staff_code'):
	is_company_staff_id();
	break;
case('is_staff_id'):
	is_staff_id();
	break;
case('is_staff_alias'):
	is_staff_alias();
	break;
case('is_company_staff_name'):
	is_company_staff_name();
	break;
case('is_company_department_code'):
	is_company_department_code();
	break;
case('is_company_department_name'):
	is_company_department_name();
	break;
case('find_position'):
	require_once 'ar_edit_common.php';
	$data=prepare_values($_REQUEST,array(
			'parent_key'=>array('type'=>'number'),
			'grandparent_key'=>array('type'=>'number'),
			'query'=>array('type'=>'string')
		));
	find_company_area($data);
	break;
case('employees'):
	list_employees();
	break;
case('staff_working_hours'):
	list_staff_working_hours();
	break;
default:
	$response=array('state'=>404,'resp'=>_('Operation not found'));
	echo json_encode($response);

}



function list_employees() {
	global $myconf;

	if (isset( $_REQUEST['parent'])) {
		$parent=$_REQUEST['parent'];
	} else {
		exit;
	}
	if (isset( $_REQUEST['parent_key'])) {
		$parent_key=$_REQUEST['parent_key'];
	} else {
		exit;
	}

	if ($parent=='area') {
		$conf_table='company_area';
		$conf=$_SESSION['state']['company_area']['staff'];
	}elseif ($parent=='department') {
		$conf_table='department';
		$conf=$_SESSION['state']['department']['staff'];
	}elseif ($parent=='company') {
		$conf_table='hr';
		$conf=$_SESSION['state']['hr']['staff'];
	}elseif ($parent=='position') {
		$conf_table='position';
		$conf=$_SESSION['state']['position']['staff'];
	}else {
		exit;
	}


	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];



	$elements=$conf['elements'];

	if (isset( $_REQUEST['elements_notworking'])) {
		$elements['NotWorking']=$_REQUEST['elements_notworking'];

	}
	if (isset( $_REQUEST['elements_working'])) {
		$elements['Working']=$_REQUEST['elements_working'];
	}

	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	$_order=$order;
	$_dir=$order_direction;



	foreach (array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'f_field'=>$f_field,'f_value'=>$f_value) as $key=>$item) {
		$_SESSION['state'][$conf_table]['staff'][$key]=$item;
	}
	$_SESSION['state'][$conf_table]['staff']['elements']=$elements;



	if($parent=='company'){
		$where=' where true';
	
	}elseif($parent=='department'){
		$where=' where true';
	
	}elseif($parent=='area'){
		$where=' where true';
	
	}elseif($parent=='position'){
		$where=' where true';
	
	}



	$wheref='';
	if ($f_field=='name' and $f_value!=''  )
		$wheref.=" and  name like '%".addslashes($f_value)."%'    ";
	elseif ($f_field=='position_id' or $f_field=='area_id'   and is_numeric($f_value) )
		$wheref.=sprintf(" and  $f_field=%d ",$f_value);




	$_elements='';
	foreach ($elements as $_key=>$_value) {
		if ($_value) {
			if ($_key=='NotWorking') {
				$_elements.=",'No'";
			}
			elseif ($_key=='Working') {
				$_elements.=",'Yes'";
			}

		}
	}
	$_elements=preg_replace('/^\,/','',$_elements);
	if ($_elements=='') {
		$where.=' and false' ;
	} else {
		$where.=' and `Staff Currently Working` in ('.$_elements.')' ;
	}

	$sql="select count(*) as total from `Staff Dimension` SD left join `Contact Dimension` CD on (`Contact Key`=`Staff Contact Key`) $where $wheref";

	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count(*) as total from `Staff Dimension` SD left join `Contact Dimension` CD on (`Contact Key`=`Staff Contact Key`)   $where ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$row['total']-$total;
		}

	} else {
		$filtered=0;
		$total_records=$total;
	}

	mysql_free_result($res);

	$filter_msg='';


	$rtext=number($total_records)." ".ngettext('record','records',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp='('._("Showing all").')';

	switch ($f_field) {
	case('name'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff with name")." <b>*".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff with name')." <b>*".$f_value."*</b>)";
		break;
	case('area_id'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff on area")." <b>".$f_value."</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff on area')." <b>".$f_value."</b>)";
		break;
	case('position_id'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff with position")." <b>".$f_value."</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff with position')." <b>".$f_value."</b>)";
		break;

	}


	if ($order=='name')
		$order='`Staff Name`';
	elseif ($order=='position')
		$order='position';
	elseif ($order=='id')
		$order='`Staff Key`';
	else
		$order='`Staff Name`';

	$sql="select (select GROUP_CONCAT(distinct `Company Position Title`) from `Company Position Staff Bridge` PSB  left join `Company Position Dimension` P on (`Company Position Key`=`Position Key`) where PSB.`Staff Key`= SD.`Staff Key`) as position, `Staff Alias`,`Staff Key`,`Staff Name` from `Staff Dimension` SD   $where $wheref order by $order $order_direction limit $start_from,$number_results";
	//print $sql;
	$adata=array();
	$res=mysql_query($sql);
	while ($data=mysql_fetch_array($res)) {


		$id=sprintf('<a href="staff.php?id=%d">%03d</a>',$data['Staff Key'],$data['Staff Key']);
		$alias=sprintf('<a href="staff.php?id=%d">%s</a>',$data['Staff Key'],$data['Staff Alias']);

		$department='';
		$area='';
		$position=$data['position'];
		$adata[]=array(
			'id'=>$id,
			'alias'=>$data['Staff Alias'],
			'name'=>$alias,
			'department'=>$department,
			'area'=>$area,
			'position'=>$position

		);
	}
	mysql_free_result($res);


	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,

			'records_perpage'=>$number_results,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);

	echo json_encode($response);
}

// ------------------------------------------ Staff Working Hours Table Starts Here ----------------------------------
function list_staff_working_hours() {
	$conf=$_SESSION['state']['staff']['working_hours'];
	if (isset( $_REQUEST['id']))
		$staff_id=$_REQUEST['id'];
	else
		exit;
	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];

	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];

	/*   if (isset( $_REQUEST['from']))
        $from=$_REQUEST['from'];
    else
        $from=$conf['from'];
    if (isset( $_REQUEST['to']))
        $to=$_REQUEST['to'];
    else
        $to=$conf['to'];*/



	/*  if (isset( $_REQUEST['type']))
        $type=$_REQUEST['type'];
    else
        $type=$conf['type'];*/

	if (isset( $_REQUEST['tid']))
		$tableid=$_REQUEST['tid'];
	else
		$tableid=0;


	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	//  $_SESSION['state']['staff_history']['working_hours']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'f_field'=>$f_field,'f_value'=>$f_value);

	$_SESSION['state']['staff']['working_hours']['order']=$order;
	$_SESSION['state']['staff']['working_hours']['order_dir']=$order_direction;
	$_SESSION['state']['staff']['working_hours']['nr']=$number_results;
	$_SESSION['state']['staff']['working_hours']['sf']=$start_from;
	$_SESSION['state']['staff']['working_hours']['f_field']=$f_field;
	$_SESSION['state']['staff']['working_hours']['f_value']=$f_value;

	/*$date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
    if ($date_interval['error']) {
        $date_interval=prepare_mysql_dates($_SESSION['state']['staff']['table']['from'],$_SESSION['state']['staff']['table']['to']);
    } else {
        $_SESSION['state']['staff']['working_hours']['from']=$date_interval['from'];
        $_SESSION['state']['staff']['working_hours']['to']=$date_interval['to'];
    }*/

	/*  switch ($type) {
    case('Family'):
        $group_by='Product Family Key';
        $subject='Product Family Code';
        $description='Product Family Name';
        $subject_label='family';
        $subject_label_plural='families';
        break;
    case('Department'):
        $group_by='Product Department Key';
                $description='Product Department Name';

        $subject='Product Department Code';
        $subject_label='department';
        $subject_label_plural='departments';
        break;
    default:
        $group_by='Product Code';
        $subject='Product Code';
                $description='Product XHTML Short Description';

        $subject_label='product';
        $subject_label_plural='products';
    }
*/


	$where=sprintf("    where `Staff Key`=%d  ",$staff_id);

	//print "$f_field $f_value  " ;

	$wheref='';
	/*   if ($f_field=='description' and $f_value!='')
        $wheref.=" and ( `Deal Component Terms Description` like '".addslashes($f_value)."%' or `Deal Component Allowance Description` like '".addslashes($f_value)."%'  )   ";
    elseif($f_field=='code' and $f_value!=''){
  switch ($type) {
        case('Family'):
            $wheref.=" and  `Product Family Code` like '".addslashes($f_value)."%'";
            break;
        case('Department'):
                    $wheref.=" and  `Product Department Code` like '".addslashes($f_value)."%'";

            break;
        default:
          $wheref.=" and  `Product Code` like '".addslashes($f_value)."%'";

        }



}*/

	$sql="select count(*) as total from `Staff Work Hours Dimension`  $where $wheref";


	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total=$row['total'];
	}if ($wheref!='') {
		$sql="select count(*) as total from `Staff Work Hours Dimension`  $where ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$row['total']-$total;
		}

	}else {
		$filtered=0;
		$total_records=$total;
	}

	mysql_free_result($res);


	$rtext=$total_records." ".ngettext('record','records',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' ('._("Showing All").')';




	$filter_msg='';

	switch ($f_field) {
	case('name'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff with name")." <b>*".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff with name')." <b>*".$f_value."*</b>)";
		break;
	case('area_id'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff on area")." <b>".$f_value."</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff on area')." <b>".$f_value."</b>)";
		break;
	case('position_id'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff with position")." <b>".$f_value."</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff with position')." <b>".$f_value."</b>)";
		break;

	}


	$_order=$order;
	$_dir=$order_direction;

	if ($order=='start_time')
		$order='`Start Time`';

	$sql="select * from `Staff Work Hours Dimension`  $where $wheref order by $order $order_direction limit $start_from,$number_results";
	//print $sql;
	$adata=array();
	$res=mysql_query($sql);
	while ($data=mysql_fetch_array($res)) {



		$adata[]=array(
			'id'=>$data['Staff Key'],
			'day'=>$data['Day'],
			'start_time'=>$data['Start Time'],
			'finish_time'=>$data['Finish Time'],
			'total_breaks_time'=>$data['Total Breaks Time'],
			'hours_worked'=>$data['Hours Worked']

		);
	}



	mysql_free_result($res);
	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total
		)
	);
	echo json_encode($response);
}








function is_company_staff_id() {
	if (!isset($_REQUEST['query']) or !isset($_REQUEST['company_key']) ) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	} else
		$query=$_REQUEST['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

	$company_key=$_REQUEST['company_key'];

	$sql=sprintf("select `Staff Key`,`Staff Name` from `Staff Dimension` where `Company Key`=%d  "
		,$company_key
	);
	$res=mysql_query($sql);

	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Company Staff <a href="edit_each_staff.php?id=%d">%s</a> already has this code (%s)'
			,$data['Staff Key']
			,$data['Staff Name']

		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	} else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

}


function is_staff_id() {
	if (!isset($_REQUEST['query']) or !isset($_REQUEST['staff_key']) ) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	} else
		$query=$_REQUEST['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

	$staff_key=$_REQUEST['staff_key'];

	$sql=sprintf("select `Staff Key`,`Staff ID`,`Staff Name` from `Staff Dimension` where `Staff Key`=%d  "
		,prepare_mysql($query)
	);
	$res=mysql_query($sql);

	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Company Staff <a href="edit_each_staff.php?id=%d">%s</a> already has this id (%d) '
			,$data['Staff Key'],$data['Staff Name']
			,$data['Staff ID']

		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	} else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

}

function is_staff_alias() {
	if (!isset($_REQUEST['query'] )) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	} else
		$query=$_REQUEST['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}


	$sql=sprintf("select `Staff Key`,`Staff Alias` from `Staff Dimension` where `Staff Alias`=%s  ",prepare_mysql($query)
	);

	//print $sql;
	$res=mysql_query($sql);

	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Another Staff <a href="staff.php?id=%d">(%s)</a> already has this alias'
			,$data['Staff Key']
			,$data['Staff Alias']
		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	} else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

}


function is_company_staff_name() {
	if (!isset($_REQUEST['query']) or !isset($_REQUEST['company_key']) ) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	} else
		$query=$_REQUEST['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

	$company_key=$_REQUEST['company_key'];

	$sql=sprintf("select `Staff Key`,`Staff Name` from `Staff Dimension`"
	);
	$res=mysql_query($sql);
	//print("******");print($sql);
	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Another Company Staff <a href="edit_each_staff.php?id=%d">(%s)</a> already has this name'
			,$data['Staff Key']
			,$data['Staff Name']
		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	} else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

}



function is_company_department_code() {
	if (!isset($_REQUEST['query']) or !isset($_REQUEST['company_key']) ) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	} else
		$query=$_REQUEST['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

	$company_key=$_REQUEST['company_key'];

	$sql=sprintf("select `Company Department Key`,`Company Department Code` from `Company Department Dimension` where `Company Key`=%d  "
		,$company_key
	);
	$res=mysql_query($sql);

	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Department Staff <a href="edit_each_department.php?id=%d">%s</a> already has this code (%s)'
			,$data['Company Department Key']
			,$data['Company Department Code']

		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	} else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

}
/*function is_company_staff_name() {
  if (!isset($_REQUEST['query']) or !isset($_REQUEST['company_key']) ) {
        $response= array(
                       'state'=>400,
                       'msg'=>'Error'
                   );
        echo json_encode($response);
        return;
    } else
        $query=$_REQUEST['query'];
    if ($query=='') {
        $response= array(
                       'state'=>200,
                       'found'=>0
                   );
        echo json_encode($response);
        return;
    }

    $company_key=$_REQUEST['company_key'];

   $sql=sprintf("select `Company Department Key`,`Company Department Name` from `Company Department Dimension`"
                );
    $res=mysql_query($sql);
//print("******");print($sql);
    if ($data=mysql_fetch_array($res)) {
        $msg=sprintf('Another Department Staff <a href="edit_each_department.php?id=%d">(%s)</a> already has this name'
                     ,$data['Company Department Key']
                     ,$data['Company Department Name']
                    );
        $response= array(
                       'state'=>200,
                       'found'=>1,
                       'msg'=>$msg
                   );
        echo json_encode($response);
        return;
    } else {
        $response= array(
                       'state'=>200,
                       'found'=>0
                   );
        echo json_encode($response);
        return;
    }

}

*/




function find_position($data) {
	$extra_where='';
	if ($data['parent_key']) {
		$extra_where.=sprintf(' and `Company Department Key`=%d',$data['parent_key']);
	}
	if ($data['grandparent_key']) {
		$extra_where.=sprintf(' and `Company Area Key`=%d',$data['grandsparent_key']);
	}



	$adata=array();
	$sql=sprintf("select `Position Key` ,`Position Code` ,`Position Name` from `Position Dimension`  where  (`Position Name` like '%%%s%%' or `Position Code` like '%s%%') %s limit 10"
		,addslashes($data['query'])
		,addslashes($data['query'])
		,$extra_where

	);
	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result)) {


		$adata[]=array(

			'key'=>$row['Position Key'],
			'code'=>$row['Position Code'],
			'name'=>$row['Position Name']
		);
	}
	$response=array('data'=>$adata);
	echo json_encode($response);


}
function is_position_code() {
	if (!isset($_REQUEST['query']) ) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	}else
		$query=$_REQUEST['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}


	$sql=sprintf("select `Position Key`,`Position Name`,`Position Code` from `Position Dimension` where  `Position Code`=%s  "
		,$company_key
		,prepare_mysql($query)
	);
	$res=mysql_query($sql);

	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Position <a href="position.php?id=%d">%s</a> already has this code (%s)'
			,$data['Position Key']
			,$data['Position Name']
			,$data['Position Code']
		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	}else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

}
function is_position_name() {
	if (!isset($_REQUEST['query'])  ) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	}else
		$query=$_REQUEST['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}



	$sql=sprintf("select `Position Key`,`Position Name`,`Position Code` from `Position Dimension` where `Position Name`=%s  "
		,$company_key
		,prepare_mysql($query)
	);
	$res=mysql_query($sql);

	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Another Position <a href="position.php?id=%d">(%s)</a> already has this name'
			,$data['Position Key']
			,$data['Position Code']
		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	}else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

}


function is_company_area_code() {
	if (!isset($_REQUEST['query']) or !isset($_REQUEST['company_key']) ) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	} else
		$query=$_REQUEST['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

	$company_key=$_REQUEST['company_key'];

	$sql=sprintf("select `Company Area Key`,`Company Area Name`,`Company Area Code` from `Company Area Dimension` where `Company Key`=%d and `Company Area Code`=%s  "
		,$company_key
		,prepare_mysql($query)
	);
	$res=mysql_query($sql);

	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Company Area <a href="company_area.php?id=%d">%s</a> already has this code (%s)'
			,$data['Company Area Key']
			,$data['Company Area Name']
			,$data['Company Area Code']
		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	} else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

}
function is_company_area_name() {
	if (!isset($_REQUEST['query']) or !isset($_REQUEST['company_key']) ) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	} else
		$query=$_REQUEST['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

	$company_key=$_REQUEST['company_key'];

	$sql=sprintf("select `Company Area Key`,`Company Area Name`,`Company Area Code` from `Company Area Dimension` where `Company Key`=%d and `Company Area Name`=%s  "
		,$company_key
		,prepare_mysql($query)
	);
	$res=mysql_query($sql);

	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Another Company Area <a href="company_area.php?id=%d">(%s)</a> already has this name'
			,$data['Company Area Key']
			,$data['Company Area Code']
		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	} else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

}

function list_company_areas() {





	$conf=$_SESSION['state']['hr']['areas'];

	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (!is_numeric($start_from))
		$start_from=0;

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
	} else
		$number_results=$conf['nr'];


	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];

	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;







	$_SESSION['state']['hr']['areas']['order']=$order;
	$_SESSION['state']['hr']['areas']['order_dir']=$order_direction;
	$_SESSION['state']['hr']['areas']['nr']=$number_results;
	$_SESSION['state']['hr']['areas']['sf']=$start_from;
	$_SESSION['state']['hr']['areas']['f_field']=$f_field;
	$_SESSION['state']['hr']['areas']['f_value']=$f_value;



	$group='';





	$filter_msg='';

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	//  if(!is_numeric($start_from))
	//        $start_from=0;
	//      if(!is_numeric($number_results))
	//        $number_results=25;


	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';
	$wheref='';
	$where='';
	if ($f_field=='company name' and $f_value!='')
		$wheref.=" and  `Company Name` like '%".addslashes($f_value)."%'";
	elseif ($f_field=='email' and $f_value!='')
		$wheref.=" and  `Company Main Plain Email` like '".addslashes($f_value)."%'";

	$sql="select count(*) as total from `Company Area Dimension`  $where $wheref   ";
	//print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from `Company Area Dimension`  $where   ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}

	}
	mysql_free_result($res);

	$rtext=$total_records." ".ngettext('company area','company areas',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' '._('(Showing all)');

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with name like ")." <b>".$f_value."*</b> ";
			break;
		case('email'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with email like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('companies with name like')." <b>".$f_value."*</b>";
			break;
		case('email'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('companies with email like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_order=$order;
	$_order_dir=$order_dir;
	$order='`Company Area Name`';

	if ($order=='code')
		$order='`Company Area Code`';
	if ($order=='departments')
		$order='`Company Area Number Departments`';
	if ($order=='positions')
		$order='`Company Area Number Positions`';
	if ($order=='employees')
		$order='`Company Area Number Employees`';
	else
		$order='`Company Area Name`';



	$sql="select  * from `Company Area Dimension` P  $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);
	$adata=array();

	// print "$sql";
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {



		$adata[]=array(




			'code'=>sprintf('<a href="company_area.php?id=%d">%s</a>',$row['Company Area Key'],$row['Company Area Code']),
			'name'=>sprintf('<a href="company_area.php?id=%d">%s</a>',$row['Company Area Key'],$row['Company Area Name']),
			'departments'=>number($row['Company Area Number Departments']),
			'positions'=>number($row['Company Area Number Positions']),
			'employees'=>number($row['Company Area Number Employees']),

		);
	}
	mysql_free_result($res);


	// $total_records=ceil($total_records/$number_results)+$total_records;

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);




	echo json_encode($response);

}

function list_company_departments() {



	if (isset( $_REQUEST['parent'])) {
		$parent=$_REQUEST['parent'];
	} else {
		exit;
	}
	if (isset( $_REQUEST['parent_key'])) {
		$parent_key=$_REQUEST['parent_key'];
	} else {
		exit;
	}

	if ($parent=='area') {
		$conf_table='company_area';
		$conf=$_SESSION['state']['company_area']['departments'];
	}elseif ($parent=='company') {
		$conf_table='hr';
		$conf=$_SESSION['state']['hr']['departments'];
	}else {
		exit;
	}



	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (!is_numeric($start_from))
		$start_from=0;

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
	} else
		$number_results=$conf['nr'];


	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];

	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');




	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;






	$_SESSION['state'][$conf_table]['departments']=array(
		'order'=>$order,
		'order_dir'=>$order_direction,
		'nr'=>$number_results,
		'sf'=>$start_from,

		'f_field'=>$f_field,
		'f_value'=>$f_value

	);





	if ($parent=='area') {
		$table=' `Company Department Dimension`  D left join `Company Area Department Bridge` B on (D.`Company Department Key`=B.`Department Key`) left join `Company Area Dimension` A on (A.`Company Area Key`=B.`Area Key`) ';
		$where=sprintf(' where B.`Area Key`=%d',$parent_key);
	}
	if ($parent=='company') {
		$table=' `Company Department Dimension`  D left join `Company Area Department Bridge` B on (D.`Company Department Key`=B.`Department Key`) left join `Company Area Dimension` A on (A.`Company Area Key`=B.`Area Key`) ';
		$where=' where true';
	}

	$group='';





	$filter_msg='';

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';
	$wheref='';
	if ($f_field=='company name' and $f_value!='')
		$wheref.=" and  `Company Name` like '%".addslashes($f_value)."%'";
	elseif ($f_field=='email' and $f_value!='')
		$wheref.=" and  `Company Main Plain Email` like '".addslashes($f_value)."%'";

	$sql="select count(*) as total from $table  $where $wheref   ";
	//print "$sql";
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from $table  $where   ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}

	}
	mysql_free_result($res);

	$rtext=number($total_records)." ".ngettext('company department','company departments',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' '._('(Showing all)');

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with name like ")." <b>".$f_value."*</b> ";
			break;
		case('email'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with email like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('companies with name like')." <b>".$f_value."*</b>";
			break;
		case('email'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('companies with email like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_order=$order;
	$_order_dir=$order_dir;
	$order='`Company Department Name`';

	if ($order=='code')
		$order='`Company Department Code`';



	$sql="select  * from  $table   $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);
	$adata=array();

	// print "$sql";
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$adata[]=array(
			'area'=>sprintf('<a href="company_area.php?id=%d">%s</a>',$row['Company Area Key'],$row['Company Area Code'])
			,'code'=>sprintf('<a href="company_department.php?id=%d">%s</a>',$row['Company Department Key'],$row['Company Department Code'])
			,'name'=>sprintf('<a href="company_department.php?id=%d">%s</a>',$row['Company Department Key'],$row['Company Department Name'])

		);
	}
	mysql_free_result($res);


	// $total_records=ceil($total_records/$number_results)+$total_records;

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);




	echo json_encode($response);

}

function list_company_positions() {
	$conf=$_SESSION['state']['positions']['table'];


	if (isset( $_REQUEST['parent'])) {
		$parent=$_REQUEST['parent'];
		$_SESSION['state']['positions']['parent']=$parent;
	} else
		$parent= $_SESSION['state']['positions']['parent'];

	if ($parent=='area') {
		$conf_table='company_area';

		$conf=$_SESSION['state']['company_area']['positions'];

	} else {
		$conf_table='positions';
		$conf=$_SESSION['state'][$conf_table]['table'];

	}

	if (isset( $_REQUEST['view']))
		$view=$_REQUEST['view'];
	else
		$view=$_SESSION['state']['positions']['view'];

	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (!is_numeric($start_from))
		$start_from=0;

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
	} else
		$number_results=$conf['nr'];


	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];

	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	if (isset( $_REQUEST['where']))
		$where=addslashes($_REQUEST['where']);
	else
		$where=$conf['where'];


	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;






	if (isset( $_REQUEST['restrictions']))
		$restrictions=$_REQUEST['restrictions'];
	else
		$restrictions=$conf['restrictions'];


	if ($parent=='area') {
		$_SESSION['state']['company_area']['positions']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value
			,'restrictions'=>'','parent'=>$parent
		);
	} else {
		$_SESSION['state']['positions']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value
			,'restrictions'=>'','parent'=>$parent
		);
	}




	if ($parent=='area') {
		$where.=sprintf(' and A.`Company Area Key`=%d',$_SESSION['state']['company_area']['id']);
	}


	$group='';





	$filter_msg='';

	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	//  if(!is_numeric($start_from))
	//        $start_from=0;
	//      if(!is_numeric($number_results))
	//        $number_results=25;


	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';
	$wheref='';
	if ($f_field=='company name' and $f_value!='')
		$wheref.=" and  `Company Name` like '%".addslashes($f_value)."%'";
	elseif ($f_field=='email' and $f_value!='')
		$wheref.=" and  `Company Main Plain Email` like '".addslashes($f_value)."%'";

	$sql="select count(*) as total from `Company Position Dimension`  $where $wheref   ";
	//print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from `Company Position Dimension`  $where   ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}

	}
	mysql_free_result($res);

	$rtext=$total_records." ".ngettext('position','positions',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' '._('(Showing all)');

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with name like ")." <b>".$f_value."*</b> ";
			break;
		case('email'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with email like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('companies with name like')." <b>".$f_value."*</b>";
			break;
		case('email'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('companies with email like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_order=$order;
	$_order_dir=$order_dir;
	$order='`Company Position Title`';

	if ($order=='code')
		$order='`Company Position Code`';
	elseif ($order=='name')
		$order='`Company Position Title`';
	elseif ($order=='employees')
		$order='`Company Position Employees`';
	elseif ($order=='department')
		$order='`Company Department Name`';


	$sql="select  * from `Company Position Dimension` P left join `Company Department Position Bridge` on (`Position Key`=`Company Position Key`)     left join `Company Department Dimension` D on (`Department Key`=`Company Department Key`)  $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);
	$adata=array();

	// print "$sql";
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {



		$adata[]=array(



			'department'=>sprintf('<a href="company_department.php?id=%d">%s</a>',$row['Company Department Key'],$row['Company Department Name'])

			,'code'=>sprintf('<a href="position.php?id=%d">%s</a>',$row['Company Position Key'],$row['Company Position Code'])
			,'name'=>sprintf('<a href="position.php?id=%d">%s</a>',$row['Company Position Key'],$row['Company Position Title'])
			,'employees'=>number($row['Company Position Employees'])

		);
	}
	mysql_free_result($res);


	// $total_records=ceil($total_records/$number_results)+$total_records;

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);




	echo json_encode($response);

}


?>
