<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 7 March 2016 at 12:28:04 GMT+8, Yiwu, China

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

error_reporting(E_ALL);
require_once 'utils/general_functions.php';
require_once 'utils/object_functions.php';
require_once 'utils/system_functions.php';
require_once 'utils/table_functions.php';
include_once 'utils/i18n.php';


date_default_timezone_set('UTC');


include_once 'keyring/dns.php';
include_once 'class.Account.php';
include_once 'class.User.php';

include_once 'external_libs/Smarty/Smarty.class.php';
$smarty = new Smarty();
$smarty->template_dir = 'templates';
$smarty->compile_dir = 'server_files/smarty/templates_c';
$smarty->cache_dir = 'server_files/smarty/cache';
$smarty->config_dir = 'server_files/smarty/configs';

$db = new PDO("mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd , array(\PDO::MYSQL_ATTR_INIT_COMMAND =>"SET time_zone = '+0:00';"));
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


session_save_path('server_files/tmp');
ini_set('session.gc_maxlifetime', 57600); // 16 hours
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);
session_start();


require_once 'utils/modules.php';


require_once 'utils/ar_common.php';



$tipo=$_REQUEST['tipo'];


switch ($tipo) {
case 'views':






	$data=prepare_values($_REQUEST, array(
			'request'=>array('type'=>'string'),
			'old_state'=>array('type'=>'json array'),
			'tab'=>array('type'=>'string', 'optional'=>true),
			'subtab'=>array('type'=>'string', 'optional'=>true),
			'otf'=>array('type'=>'string', 'optional'=>true),
			'metadata'=>array('type'=>'json array', 'optional'=>true),

		));

	get_view($data, $db, $modules, $smarty);



	break;
case 'tab':
	$data=prepare_values($_REQUEST, array(
			'tab'=>array('type'=>'string'),
			'subtab'=>array('type'=>'string'),
			'state'=>array('type'=>'json array'),
		));


	$response=array(
		'tab'=>get_tab($db, $smarty, $user, $account, $data['tab'], $data['subtab'], $data['state'])
	);




	echo json_encode($response);
	break;

case 'setup':
	$data=prepare_values($_REQUEST, array(
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'key'),
			'object'=>array('type'=>'string'),
			'key'=>array('type'=>'numeric'),
			'step'=>array('type'=>'string'),

			'fields_data'=>array('type'=>'json array'),
		));

	if ($data['step']=='root_user') {
		setup_root_user($data);
	}elseif ($data['step']=='add_employee') {
		new_object( $db,  $editor, $data, $smarty);
	}

	break;
case 'employees':
	employees(get_table_parameters(), $db, $user, 'current');
	break;
case 'check_for_duplicates':

	$data=prepare_values($_REQUEST, array(
			'object'=>array('type'=>'string'),
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'string'),
			'key'=>array('type'=>'string'),
			'field'=>array('type'=>'string'),
			'value'=>array('type'=>'string'),

		));

	check_for_duplicates($data, $db);
	break;
case 'skip':
	$data=prepare_values($_REQUEST, array(
			'step'=>array('type'=>'string'),


		));

	skip_step( $data);
	break;
case 'help':
	$data=prepare_values($_REQUEST, array(

			'state'=>array('type'=>'json array'),
		));


	$account=new Account();
	get_help($data, $modules, $db, $account, $user, $smarty);
	break;
default:
	$response=array('state'=>404, 'resp'=>'Operation not found 2');
	echo json_encode($response);

}

function setup_root_user($data) {

	include_once 'class.User.php';

	$root_user=new User('Administrator');


	$root_user->update($data['fields_data']);

	if (!$root_user->error) {

		$account=new Account();
		$setup_data=$account->get('Setup Metadata');
		$setup_data['steps']['root_user']['setup']=true;
		$account->update(array('Account Setup Metadata'=>json_encode($setup_data)), 'no_history');

		$done=true;
		$redirect='account/setup/state';
		foreach ($setup_data['steps'] as $step_code=>$step_data) {
			if (!$step_data['setup']) {
				$done=false;
				$redirect= 'account/setup/'.$step_code;
				break;
			}
		}
		if ($done) {
			$account->update(array('Account State'=>'Active'));
		}


		$response=array(
			'state'=>200,
			'redirect'=>$redirect


		);
	}else {
		$response=array(
			'state'=>400,
			'msg'=>$object->msg,

		);

	}

	echo json_encode($response);

}


function get_tab($db, $smarty, $user, $account, $tab, $subtab, $state=false, $metadata=false) {


	$_tab=$tab;
	$_subtab=$subtab;

	$actual_tab=($subtab!=''?$subtab:$tab);
	$state['tab']=$actual_tab;

	$smarty->assign('data', $state);

	if (file_exists('tabs/'.$actual_tab . '.tab.php')) {
		include_once 'tabs/'.$actual_tab . '.tab.php';
	}else {
		$html='Tab Not found: >'.$actual_tab.'<';

	}


	if (is_array($state)) {


		$_SESSION['state'][$state['module']][$state['section']]['tab']=$_tab;
		if ($_subtab!='') {
			$_SESSION['tab_state'][$_tab]=$_subtab;
		}

	}
	return $html;

}



function get_navigation($user, $smarty, $data, $db, $account) {


	switch ($data['module']) {





	case ('utils'):
		require_once 'navigation/utils.nav.php';
		switch ($data['section']) {
		case ('forbidden'):
		case ('not_found'):
			return get_utils_navigation($data);
			break;
		case ('fire'):
			return get_fire_navigation($data);
			break;
		}

		break;

	case ('account'):

		require_once 'navigation/account.nav.php';

		switch ($data['section']) {
		case ('setup'):
		case ('setup_error'):
		case ('setup_root_user'):
		case ('setup_add_employees'):
			return get_account_setup_navigation($data, $smarty, $user, $db, $account);
			break;



		}



		break;

	default:
		return 'Module not found';
	}

}


function get_view($data, $db, $modules, $smarty) {

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



	$state=parse_request($data, $db, $modules, $account='', $user='');



	$_object=get_object($state['object'], $state['key']);

	$state['_object']=$_object;
	$state['key']=$_object->id;



	$_SESSION['request']=$state['request'];


	$response=array('state'=>array());

	list($state, $response['view_position'])=get_view_position($state, $smarty);


	//if ($data['old_state']['module']!=$state['module']  or $reload ) {
	$response['menu']=get_setup_menu($state, $user, $smarty);

	//}


	if (
		$data['old_state']['module']!=$state['module'] or
		$data['old_state']['section']!=$state['section'] or
		$data['old_state']['parent_key']!=$state['parent_key'] or
		$data['old_state']['key']!=$state['key'] or  $reload

	) {
		$response['navigation']=get_navigation($user, $smarty, $state, $db, $account);

	}
	if ($reload) {
		$response['logout_label']=_('Logout');
	}


	list($state, $response['tabs'])=get_tabs($state, $modules, $user, $smarty);// todo only calculate when is subtabs in the section



	$response['object_showcase']='';


	$response['tab']=get_tab($db, $smarty, $user, $account, $state['tab'], $state['subtab'], $state, $data['metadata']);

	unset($state['_object']);
	unset($state['_parent']);
	unset($state['store']);
	unset($state['website']);
	unset($state['warehouse']);
	$response['state']=$state;




	echo json_encode($response);

}


function get_tabs($data, $modules, $user, $smarty) {



	if (isset($modules[$data['module']]['sections'][$data['section']]['tabs'])) {
		$tabs=$modules[$data['module']]['sections'][$data['section']]['tabs'];
	}else {
		$tabs=array();
	}



	if (isset($modules[$data['module']]['sections'][$data['section']]['tabs'][$data['tab']] ['subtabs'])) {

		$subtabs=$modules[$data['module']]['sections'][$data['section']]['tabs'][$data['tab']]['subtabs'];
	}else {
		$subtabs=array();
	}


	if (isset($tabs[$data['tab']]) ) {
		$tabs[$data['tab']]['selected']=true;
	}


	if (isset($subtabs[$data['subtab']]) )$subtabs[$data['subtab']]['selected']=true;

	$_content=array(
		'tabs'=>$tabs,
		'subtabs'=>$subtabs



	);

	if ($data['section']=='category') {

		if ($data['_object']->get('Category Scope')=='Product') {
			if ($data['_object']->get('Category Subject')=='Product') {
				$_content['tabs']['category.subjects']['label']=_('Products');


				if ($data['_object']->get('Root Key')==$data['store']->get('Store Family Category Key')) {
					$_content['tabs']['category.categories']['label']=_('Families');
				}

			}else {


				if ($data['_object']->get('Root Key')==$data['store']->get('Store Department Category Key')) {
					$_content['tabs']['category.subjects']['label']=_('Families');
					$_content['tabs']['category.categories']['label']=_('Departments');
				}elseif ($data['_object']->get('Root Key')==$data['store']->get('Store Family Category Key')) {
					$_content['tabs']['category.categories']['label']=_('Families');
				}else {

					$_content['tabs']['category.subjects']['label']=_('Categories');
				}

			}
		}



		if ($data['_object']->get('Category Branch Type')=='Head') {
			unset($_content['tabs']['category.categories']);
			if ($data['tab']=='category.categories') {
				$_content['tabs']['category.subjects']['selected']=true;
				$data['tab']='category.subjects';
			}

		}else {
			unset($_content['tabs']['category.subjects']);
			if ($data['tab']=='category.subjects') {
				$_content['tabs']['category.categories']['selected']=true;
				$data['tab']='category.categories';
			}
		}

		//print_r($data['_object']);
		//print_r($_content);


	}

	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('tabs.tpl');

	return array($data, $html);
}


function get_view_position($state, $smarty) {



	$branch=array();


	$branch[]=array('label'=>_('Account Setup'), 'icon'=>'', 'reference'=>'');



	switch ($state['section']) {
	case 'setup_root_user':
		$branch[]=array('label'=>_('Set up root user'), 'icon'=>'', 'reference'=>'');
		break;
	case 'setup_add_employees':
		$branch[]=array('label'=>_('Add employees'), 'icon'=>'', 'reference'=>'');
		break;
	}


	$_content=array(
		'branch'=>$branch,

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('view_position.tpl');
	return array($state, $html);



}









function get_setup_menu($data, $user, $smarty) {

	include_once 'navigation/setup_menu.php';

	return $html;


}


function employees($_data, $db, $user, $type='') {


	if ($type=='current') {
		$extra_where=' and `Staff Currently Working`="Yes"';
		$rtext_label='employee';

	}elseif ($type=='ex') {
		$extra_where=' and `Staff Currently Working`="No"';
		$rtext_label='ex employee';

	}

	include_once 'prepare_table/init.php';

	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

	//print $sql;

	$adata=array();
	foreach ($db->query($sql) as $data) {


		switch ($data['User Active']) {
		case 'Yes':
			$user_active=_('Active');
			break;
		case 'No':
			$user_active=_('Suspended');
			break;
		case '':
			$user_active=_("Don't set up");
			break;
		default:
			$user_active=$data['User Active'];
			break;
		}

		switch ($data['Staff Type']) {
		case 'Employee':
			$type=_('Employee');
			break;
		case 'Volunteer':
			$type=_('Volunteer');
			break;
		case 'TemporalWorker':
			$type=_("Temporal worker");
			break;
		case 'WorkExperience':
			$type=_("Work experience");
			break;
		default:
			$type=$data['Staff Type'];
			break;
		}

		$adata[]=array(
			'id'=>(integer) $data['Staff Key'],
			'formatted_id'=>sprintf("%04d", $data['Staff Key']),
			'payroll_id'=>$data['Staff ID'],
			'name'=>$data['Staff Name'],
			'code'=>$data['Staff Alias'],
			'code_link'=>$data['Staff Alias'],


			'birthday'=>(($data['Staff Birthday']=='' or $data['Staff Birthday']=='0000-00-00 00:00:00' ) ?'': strftime("%e %b %Y", strtotime($data['Staff Birthday'].' +0:00'))),

			'official_id'=>$data['Staff Official ID' ],
			'email'=>$data['Staff Email'],
			'telephone'=>$data['Staff Telephone Formatted'],
			'next_of_kind'=>$data['Staff Next of Kind'],
			'from'=>(($data['Staff Valid From']=='' or $data['Staff Valid From']=='0000-00-00 00:00:00' ) ?'': strftime("%e %b %Y", strtotime($data['Staff Valid From'].' +0:00'))),

			'until'=>(($data['Staff Valid To']=='' or  $data['Staff Valid To']=='0000-00-00 00:00:00' ) ?'': strftime("%e %b %Y", strtotime($data['Staff Valid To'].' +0:00'))),
			'type'=>$type,


			'supervisors'=>$data['supervisors'],

			'job_title'=>$data['Staff Job Title'],
			'user_login'=>$data['User Handle'],
			'user_active'=>$user_active,
			'user_last_login'=>($data['User Last Login']=='' ?'': strftime("%a %e %b %Y %H:%M %Z", strtotime($data['User Last Login'].' +0:00'))),
			'user_number_logins'=>($data['User Active']=='' ?'': number($data['User Login Count']) ),



			'roles'=>$data['roles']
		);

	}

	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'total_records'=> $total

		)
	);
	echo json_encode($response);
}


function check_for_duplicates($data, $db  ) {

	$user=new User('Administrator');

	$account= new Account('');

	$field=preg_replace('/_/', ' ', $data['field']);


	$validation_sql_queries=array();

	switch ($data['object']) {
	case 'User':
		switch ($field) {
		case 'Staff User Handle':
			$invalid_msg=_('Another user is using this login');
			$sql=sprintf("select `User Key`as `key` ,`User Handle` as field from `User Dimension` where `User Type`='Staff' and `User Handle`=%s",
				prepare_mysql($data['value'])
			);

			$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);
			break;


		default:

			break;
		}
		break;

		break;
	case 'Contractor':

		switch ($field) {
		case 'Staff ID':
			$invalid_msg=_('Another contractor is using this payroll Id');
			$sql=sprintf("select `Staff Key` as `key` ,`Staff Alias` as field from `Staff Dimension` where `Staff ID`=%s",
				prepare_mysql($data['value'])
			);
			$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);

			break;
		case 'Staff Alias':
			$invalid_msg=_('Another contractor is using this code');
			$sql=sprintf("select `Staff Key` as `key` ,`Staff Alias` as field from `Staff Dimension` where `Staff Alias`=%s",
				prepare_mysql($data['value'])
			);
			$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);

			break;
		case 'Staff User Handle':
			$invalid_msg=_('Another user is using this login handle');
			$sql=sprintf("select `User Key`as `key` ,`User Handle` as field from `User Dimension` where `User Type`='Staff' and `User Handle`=%s",
				prepare_mysql($data['value'])
			);
			$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);
			break;
		default:

			break;
		}


		break;

	case 'Staff':
		switch ($field) {
		case 'Staff ID':
			$invalid_msg=_('Another employee is using this payroll Id');
			break;
		case 'Staff Alias':
			$invalid_msg=_('Another employee is using this code');
			break;
		case 'Staff User Handle':
			$invalid_msg=_('Another user is using this login handle');
			$sql=sprintf("select `User Key`as `key` ,`User Handle` as field from `User Dimension` where `User Type`='Staff' and `User Handle`=%s",
				prepare_mysql($data['value'])
			);
			$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);
			break;
		default:

			break;
		}


		break;
	case 'Store':

		switch ($field) {
		case 'Store Code':
			$invalid_msg=_('Another store is using this code');
			break;

		default:

			break;
		}


		break;

	case 'Category':

		switch ($field) {
		case 'Category Code':
			$invalid_msg=_('Another category is using this code');
			break;

		default:

			break;
		}


		break;

	case 'Customer':
		switch ($field) {
		case 'Customer Main Plain Email':
			$invalid_msg=_('Another customer have this email');
			$sql=sprintf("select `Customer Key` as `key` ,`Customer Main Plain Email` as field from `Customer Dimension` where `Customer Main Plain Email`=%s and `Customer Store Key`=%d ",
				prepare_mysql($data['value']),
				$data['parent_key']
			);
			$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);

			$invalid_msg=_('Another customer have this email');
			$sql=sprintf("select `Customer Other Email Customer Key` as `key` ,`Customer Other Email Email` as field from `Customer Other Email Dimension` where `Customer Other Email Email`=%s and `Customer Other Email Store Key`=%d  and `Customer Other Email Customer Key`!=%d ",
				prepare_mysql($data['value']),
				$data['parent_key'],
				$data['key']
			);
			$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);


			$invalid_msg=_('Email already set up in this customer');
			$sql=sprintf("select `Customer Other Email Customer Key` as `key` ,`Customer Other Email Email` as field from `Customer Other Email Dimension` where `Customer Other Email Email`=%s and  `Customer Other Email Customer Key`=%d ",
				prepare_mysql($data['value']),
				$data['key']
			);
			$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);


			break;
		case 'new email':
			$invalid_msg=_('Another customer have this email');
			$sql=sprintf("select `Customer Key` as `key` ,`Customer Main Plain Email` as field from `Customer Dimension` where `Customer Main Plain Email`=%s and `Customer Store Key`=%d ",
				prepare_mysql($data['value']),
				$data['parent_key']
			);
			$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);

			$invalid_msg=_('Another customer have this email');
			$sql=sprintf("select `Customer Other Email Customer Key` as `key` ,`Customer Other Email Email` as field from `Customer Other Email Dimension` where `Customer Other Email Email`=%s and `Customer Other Email Store Key`=%d  and `Customer Other Email Customer Key`!=%d ",
				prepare_mysql($data['value']),
				$data['parent_key'],
				$data['key']
			);
			$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);

			$invalid_msg=_('Email already set up in this customer');
			$sql=sprintf("select `Customer Other Email Customer Key` as `key` ,`Customer Other Email Email` as field from `Customer Other Email Dimension` where `Customer Other Email Email`=%s and  `Customer Other Email Customer Key`=%d ",
				prepare_mysql($data['value']),
				$data['key']
			);
			$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);


			$invalid_msg=_('Email already set up in this customer');
			$sql=sprintf("select `Customer Key` as `key` ,`Customer Main Plain Email` as field from `Customer Dimension` where `Customer Key`=%d ",
				prepare_mysql($data['value']),
				$data['key']
			);
			$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);



		default:

			if (preg_match('/^Customer Other Email (\d+)/i', $field, $matches)) {
				$customer_email_key=$matches[1];



				$invalid_msg=_('Email already set up in this customer');
				$sql=sprintf("select `Customer Key` as `key` ,`Customer Main Plain Email` as field from `Customer Dimension` where `Customer Main Plain Email`=%s  and `Customer Key`=%d ",
					prepare_mysql($data['value']),
					$data['key']
				);
				$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);


				$invalid_msg=_('Email already set up in this customer');
				$sql=sprintf("select `Customer Other Email Customer Key` as `key` ,`Customer Other Email Email` as field from `Customer Other Email Dimension` where `Customer Other Email Email`=%s and  `Customer Other Email Customer Key`=%d  and  `Customer Other Email Key`!=%d  ",
					prepare_mysql($data['value']),
					$data['key'],
					$customer_email_key
				);
				$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);



				$invalid_msg=_('Another customer have this email');
				$sql=sprintf("select `Customer Key` as `key` ,`Customer Main Plain Email` as field from `Customer Dimension` where `Customer Main Plain Email`=%s and `Customer Store Key`=%d ",
					prepare_mysql($data['value']),
					$data['parent_key']
				);
				$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);



				$invalid_msg=_('Another customer have this email');
				$sql=sprintf("select `Customer Other Email Customer Key` as `key` ,`Customer Other Email Email` as field from `Customer Other Email Dimension` where `Customer Other Email Email`=%s and `Customer Other Email Store Key`=%d  ",
					prepare_mysql($data['value']),
					$data['parent_key']
				);
				$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);






			}
		}


		break;

	case 'Supplier':
		switch ($field) {
		case 'Supplier Main Plain Email':
			$invalid_msg=_('Another supplier have this email');
			$sql=sprintf("select `Supplier Key` as `key` ,`Supplier Main Plain Email` as field from `Supplier Dimension` where `Supplier Main Plain Email`=%s  ",
				prepare_mysql($data['value'])

			);
			$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);

			$invalid_msg=_('Another supplier have this email');
			$sql=sprintf("select `Supplier Other Email Supplier Key` as `key` ,`Supplier Other Email Email` as field from `Supplier Other Email Dimension` where `Supplier Other Email Email`=%s   and `Supplier Other Email Supplier Key`!=%d ",
				prepare_mysql($data['value']),
				$data['key']
			);
			$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);


			$invalid_msg=_('Email already set up in this supplier');
			$sql=sprintf("select `Supplier Other Email Supplier Key` as `key` ,`Supplier Other Email Email` as field from `Supplier Other Email Dimension` where `Supplier Other Email Email`=%s and  `Supplier Other Email Supplier Key`=%d ",
				prepare_mysql($data['value']),
				$data['key']
			);
			$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);


			break;
		case 'new email':
			$invalid_msg=_('Another supplier have this email');
			$sql=sprintf("select `Supplier Key` as `key` ,`Supplier Main Plain Email` as field from `Supplier Dimension` where `Supplier Main Plain Email`=%s  ",
				prepare_mysql($data['value'])
			);
			$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);

			$invalid_msg=_('Another supplier have this email');
			$sql=sprintf("select `Supplier Other Email Supplier Key` as `key` ,`Supplier Other Email Email` as field from `Supplier Other Email Dimension` where `Supplier Other Email Email`=%s  and `Supplier Other Email Supplier Key`!=%d ",
				prepare_mysql($data['value']),
				$data['key']
			);
			$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);

			$invalid_msg=_('Email already set up in this supplier');
			$sql=sprintf("select `Supplier Other Email Supplier Key` as `key` ,`Supplier Other Email Email` as field from `Supplier Other Email Dimension` where `Supplier Other Email Email`=%s and  `Supplier Other Email Supplier Key`=%d ",
				prepare_mysql($data['value']),
				$data['key']
			);
			$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);


			$invalid_msg=_('Email already set up in this supplier');
			$sql=sprintf("select `Supplier Key` as `key` ,`Supplier Main Plain Email` as field from `Supplier Dimension` where `Supplier Key`=%d ",
				prepare_mysql($data['value']),
				$data['key']
			);
			$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);



		default:

			if (preg_match('/^Supplier Other Email (\d+)/i', $field, $matches)) {
				$supplier_email_key=$matches[1];



				$invalid_msg=_('Email already set up in this supplier');
				$sql=sprintf("select `Supplier Key` as `key` ,`Supplier Main Plain Email` as field from `Supplier Dimension` where `Supplier Main Plain Email`=%s  and `Supplier Key`=%d ",
					prepare_mysql($data['value']),
					$data['key']
				);
				$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);


				$invalid_msg=_('Email already set up in this supplier');
				$sql=sprintf("select `Supplier Other Email Supplier Key` as `key` ,`Supplier Other Email Email` as field from `Supplier Other Email Dimension` where `Supplier Other Email Email`=%s and  `Supplier Other Email Supplier Key`=%d  and  `Supplier Other Email Key`!=%d  ",
					prepare_mysql($data['value']),
					$data['key'],
					$supplier_email_key
				);
				$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);



				$invalid_msg=_('Another supplier have this email');
				$sql=sprintf("select `Supplier Key` as `key` ,`Supplier Main Plain Email` as field from `Supplier Dimension` where `Supplier Main Plain Email`=%s  ",
					prepare_mysql($data['value'])
				);
				$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);



				$invalid_msg=_('Another supplier have this email');
				$sql=sprintf("select `Supplier Other Email Supplier Key` as `key` ,`Supplier Other Email Email` as field from `Supplier Other Email Dimension` where `Supplier Other Email Email`=%s   ",
					prepare_mysql($data['value'])
				);
				$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);






			}
		}


		break;
	case 'Part':
		switch ($field) {
		case 'Part Reference':
			$invalid_msg=_('Part reference already used');
			$sql=sprintf("select P.`Part SKU` as `key` ,`Part Reference` as field from `Part Dimension` P left join `Part Warehouse Bridge` B on (B.`Part SKU`=P.`Part SKU`) where `Part Reference`=%s  and `Part Status`='In Use' and `Warehouse Key`=%d ",
				prepare_mysql($data['value']),
				$data['parent_key']
			);
			$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);

		}

		break;

	default:


		break;
	}





	if (count($validation_sql_queries)==0) {
		switch ($data['parent']) {
		case 'store':
			$parent_where=sprintf(' and `%s Store Key`=%d ', $data['object'], $data['parent_key']);
			break;
		case 'category':
			$parent_where=sprintf(' and `%s Parent Key`=%d ', $data['object'], $data['parent_key']);
			break;
		default:
			$parent_where='';
		}

		$sql=sprintf('select `%s Key` as `key` ,`%s` as field from `%s Dimension` where `%s`=%s %s ',
			addslashes(preg_replace('/_/', ' ', $data['object'])),
			addslashes($field),

			addslashes(preg_replace('/_/', ' ', $data['object'])),
			addslashes($field),
			prepare_mysql($data['value']),
			$parent_where

		);




		if (!isset($invalid_msg)) {
			$invalid_msg=_('There is another object with the same value');
		}
		$validation_sql_queries[]=array('sql'=>$sql, 'invalid_msg'=>$invalid_msg);
	}

	$validation='valid';
	$msg='';


	foreach ($validation_sql_queries as $validation_query) {
		$sql=$validation_query['sql'];
		$invalid_msg=$validation_query['invalid_msg'];

		//print "$sql\n";

		if ($result=$db->query($sql)) {
			if ($row = $result->fetch()) {
				$validation='invalid';
				$msg=$invalid_msg;
				break;
			}
		}else {
			print_r($error_info=$db->errorInfo());
			print "$sql";
			exit;
		}


	}

	$response=array(
		'state'=>200,
		'validation'=>$validation,
		'msg'=>$msg,
	);
	echo json_encode($response);



}


function new_object($db, $editor, $data, $smarty) {


	$user=new User('Administrator');

	$account= new Account('');

	$parent=get_object($data['parent'], $data['parent_key']);
	$parent->editor=$editor;

	switch ($data['object']) {


	case 'Staff':
		include_once 'class.Staff.php';

		$object=$parent->create_staff($data['fields_data']);
		if (!$parent->error) {
			$smarty->assign('account', $account);
			$smarty->assign('object', $object);

			$pcard=$smarty->fetch('presentation_cards/employee.pcard.tpl');
			$updated_data=array();

			$account=new Account();
			$setup_data=$account->get('Setup Metadata');
			$setup_data['steps']['add_employees']['setup']=true;
			$account->update(array('Account Setup Metadata'=>json_encode($setup_data)), 'no_history');



			$done=true;
			foreach ($setup_data['steps'] as $step_code=>$step_data) {
				if (!$step_data['setup']) {
					$done=false;
					break;
				}
			}
			if ($done) {
				$account->update(array('Account State'=>'Active'));
			}


		}


		break;

	default:
		$response=array(
			'state'=>400,
			'msg'=>'object process not found'

		);

		echo json_encode($response);
		exit;
		break;
	}



	if ($parent->error) {
		$response=array(
			'state'=>400,
			'msg'=>'<i class="fa fa-exclamation-circle"></i> '.$parent->msg,

		);

	}else {

		$response=array(
			'state'=>200,
			'msg'=>'<i class="fa fa-check"></i> '._('Success'),
			'pcard'=>$pcard,
			'new_id'=>$object->id,
			'updated_data'=>$updated_data
		);


	}
	echo json_encode($response);

}


function skip_step($data) {

	switch ($data['step']) {
	case 'setup_add_employees':

		$step='add_employees';
		break;
	default:
		$response=array(
			'state'=>400,
			'msg'=>'Step not found'

		);
		echo json_encode($response);
		exit;
	}

	$account=new Account();
	$setup_data=$account->get('Setup Metadata');
	$setup_data['steps'][$step]['setup']=true;



	$account->update(array('Account Setup Metadata'=>json_encode($setup_data)), 'no_history');

	$done=true;
	$redirect='account/setup/state';
	foreach ($setup_data['steps'] as $step_code=>$step_data) {
		if (!$step_data['setup']) {
			$done=false;
			$redirect= 'account/setup/'.$step_code;
			break;
		}
	}
	if ($done) {
		$account->update(array('Account State'=>'Active'));
	}

	$response=array(
		'state'=>200,
		'redirect'=>$redirect

	);
	echo json_encode($response);
}


function get_help($data, $modules, $db, $account, $user, $smarty) {

	//print_r($data['state']);

	$setup_data=$account->get('Setup Metadata');
	$step='finish';
	foreach ($setup_data['steps'] as $step_code=>$step_data) {
		if (!$step_data['setup']) {
			$step= 'account/setup/'.$step_code;
			break;
		}
	}

	$title=get_help_title($data['state'], $step, $account, $user);
	$content=get_help_content($data['state'], $step, $smarty, $account, $user);

	$response=array(
		'title'=>$title,
		'content'=>$content
	);

	echo json_encode($response);




}


function get_help_title($state, $step, $account, $user) {



	if ($state['tab']=='account.setup.root_user') {
		return _('Root user');
	}elseif ($state['tab']=='account.setup.add_employees') {
		return _('Add employees');
	}elseif ($state['tab']=='account.setup') {



		if ($step=='finish') {
			return _('Set up complete!');
		}

	}
	return '';
}


function get_help_content($state, $step, $smarty, $account, $user) {

	$smarty->assign('user', $user);
	$smarty->assign('account', $account);


	if ($state['tab']=='account.setup') {
		if ($step=='finish') {
			$template='help/account.setup.finish.quick.tpl';
		}else {
			$template='';
		}
	}else {
		$template='help/'.$state['tab'].'.quick.tpl';
	}
	if ($smarty->templateExists($template)) {
		return $smarty->fetch($template);
	}
	return _('There is not help for this section');
}



?>
