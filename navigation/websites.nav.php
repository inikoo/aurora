<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 29 August 2015 13:51:15 GMT+8 Singapore

 Copyright (c) 2015, Inikoo

 Version 3.0
*/


function get_websites_navigation($data) {

	global $user, $smarty;
	require_once 'class.Store.php';


	$block_view=$data['section'];


	$sections_class='';
	$title=_('Websites');

	$left_buttons=array();



	$right_buttons=array();


	$sections=get_sections('websites');
	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;


	$_content=array(
		'sections_class'=>$sections_class,
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search websites'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


function get_website_navigation($data) {

	global $user, $smarty;
	require_once 'class.Site.php';



	$website=new Site($data['key']);

	$block_view=$data['section'];


	$sections_class='';
	$title=_('Website').' <span class="id">'.$website->get('Site Code').'</span>';

	$left_buttons=array();
	$right_buttons=array();


	if ($user->websites>1) {




		list($prev_key, $next_key)=get_prev_next($website->id, $user->websites);
		$sql=sprintf("select `Site Code` from `Site Dimension` where `Site Key`=%d", $prev_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$prev_title=_('Website').' '.$row['Site Code'];
		}else {$prev_title='';}
		$sql=sprintf("select `Site Code` from `Site Dimension` where `Site Key`=%d", $next_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$next_title=_('Website').' '.$row['Site Code'];
		}else {$next_title='';}


		$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'website/'.$prev_key );
		$left_buttons[]=array('icon'=>'arrow-up', 'title'=>_('Websites'), 'reference'=>'websites', 'parent'=>'');

		$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'website/'.$next_key );
	}


	$website=new Site($data['key']);
	$sections=get_sections('websites', $website->id);
	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;


	$_content=array(
		'sections_class'=>$sections_class,
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search website'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


function get_page_navigation($data) {

	global $user, $smarty;
	require_once 'class.Page.php';


	$object=$data['_object'];
	$object->load_data();

	$block_view=$data['section'];


	$sections_class='';
	$title=_('Page').' <span class="id">'.$object->get('Page Code').'</span>';

	$left_buttons=array();
	$right_buttons=array();





	if ($data['parent']) {

		switch ($data['parent']) {
		case 'website':
			$tab='website.pages';
			$_section='websites';
			break;

		}


		if (isset($_SESSION['table_state'][$tab])) {
			$number_results=$_SESSION['table_state'][$tab]['nr'];
			$start_from=0;
			$order=$_SESSION['table_state'][$tab]['o'];
			$order_direction=($_SESSION['table_state'][$tab]['od']==1 ?'desc':'');
			$f_value=$_SESSION['table_state'][$tab]['f_value'];
			$parameters=$_SESSION['table_state'][$tab];
		}else {

			$default=$user->get_tab_defaults($tab);
			$number_results=$default['rpp'];
			$start_from=0;
			$order=$default['sort_key'];
			$order_direction=($default['sort_order']==1 ?'desc':'');
			$f_value='';
			$parameters=$default;
			$parameters['parent']=$data['parent'];
			$parameters['parent_key']=$data['parent_key'];
		}

		include_once 'prepare_table/'.$tab.'.ptble.php';

		$_order_field=$order;
		$order=preg_replace('/^.*\.`/', '', $order);
		$order=preg_replace('/^`/', '', $order);
		$order=preg_replace('/`$/', '', $order);
		$_order_field_value=$object->get($order);


		$prev_title='';
		$next_title='';
		$prev_key=0;
		$next_key=0;
		$sql=trim($sql_totals." $wheref");

		$res2=mysql_query($sql);
		if ($row2=mysql_fetch_assoc($res2) and $row2['num']>1 ) {

			$sql=sprintf("select `Page Code` object_name,PS.`Page Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND PS.`Page Key` < %d))  order by $_order_field desc , PS.`Page Key` desc limit 1",

				prepare_mysql($_order_field_value),
				prepare_mysql($_order_field_value),
				$object->id
			);


			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$prev_key=$row['object_key'];
				$prev_title=_("Product").' '.$row['object_name'].' ('.$row['object_key'].')';

			}

			$sql=sprintf("select `Page Code` object_name,PS.`Page Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND PS.`Page Key` > %d))  order by $_order_field   , PS.`Page Key`  limit 1",
				prepare_mysql($_order_field_value),
				prepare_mysql($_order_field_value),
				$object->id
			);


			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$next_key=$row['object_key'];
				$next_title=_("Product").' '.$row['object_name'].' ('.$row['object_key'].')';

			}


			if ($order_direction=='desc') {
				$_tmp1=$prev_key;
				$_tmp2=$prev_title;
				$prev_key=$next_key;
				$prev_title=$next_title;
				$next_key=$_tmp1;
				$next_title=$_tmp2;
			}


			switch ($data['parent']) {
			case 'website':

				$website= new Site($object->get('Page Site Key'));

				$up_button=array('icon'=>'arrow-up', 'title'=>_("Website").' ('.$website->get('Site Code').')', 'reference'=>'website/'.$object->get('Page Site Key'));

				if ($prev_key) {
					$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'website/'.$data['parent_key'].'/page/'.$prev_key);

				}else {
					$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'');

				}
				$left_buttons[]=$up_button;


				if ($next_key) {
					$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'website/'.$data['parent_key'].'/page/'.$next_key);

				}else {
					$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

				}

				break;


			}


		}


	}
	else {
		$_section='products';

	}




	$sections=get_sections('websites', $object->get('Page Site Key'));
	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;


	$_content=array(
		'sections_class'=>$sections_class,
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search website'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


function get_user_navigation($data) {



	global $smarty;

	$object=$data['_object'];
	$left_buttons=array();
	$right_buttons=array();

	if ($data['parent']) {

		switch ($data['parent']) {
		case 'website':
			$tab='website.users';
			$_section='';
			break;
		case 'customer':
			$tab='customer.users';
			$_section='';
			break;
		case 'page':
			$tab='page.users';
			$_section='';
			break;

		}


		if (isset($_SESSION['table_state'][$tab])) {
			$number_results=$_SESSION['table_state'][$tab]['nr'];
			$start_from=0;
			$order=$_SESSION['table_state'][$tab]['o'];
			$order_direction=($_SESSION['table_state'][$tab]['od']==1 ?'desc':'');
			$f_value=$_SESSION['table_state'][$tab]['f_value'];
			$parameters=$_SESSION['table_state'][$tab];
		}else {

			$default=$user->get_tab_defaults($tab);
			$number_results=$default['rpp'];
			$start_from=0;
			$order=$default['sort_key'];
			$order_direction=($default['sort_order']==1 ?'desc':'');
			$f_value='';
			$parameters=$default;
			$parameters['parent']=$data['parent'];
			$parameters['parent_key']=$data['parent_key'];
		}

		include_once 'prepare_table/'.$tab.'.ptble.php';

		$_order_field=$order;
		$order=preg_replace('/^.*\.`/', '', $order);
		$order=preg_replace('/^`/', '', $order);
		$order=preg_replace('/`$/', '', $order);
		$_order_field_value=$object->get($order);


		$prev_title='';
		$next_title='';
		$prev_key=0;
		$next_key=0;
		$sql=trim($sql_totals." $wheref");

		$res2=mysql_query($sql);
		if ($row2=mysql_fetch_assoc($res2) and $row2['num']>1 ) {

			$sql=sprintf("select `User Handle` object_name,U.`User Key` as object_key from $table   $where $wheref
	                and ($_order_field < %s OR ($_order_field = %s AND U.`User Key` < %d))  order by $_order_field desc , U.`User Key` desc limit 1",

				prepare_mysql($_order_field_value),
				prepare_mysql($_order_field_value),
				$object->id
			);

			//print $sql;
			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$prev_key=$row['object_key'];
				$prev_title=_("User").' '.$row['object_name'].' ('.$row['object_key'].')';

			}

			$sql=sprintf("select `User Handle` object_name,U.`User Key` as object_key from $table   $where $wheref
	                and ($_order_field  > %s OR ($_order_field  = %s AND U.`User Key` > %d))  order by $_order_field   , U.`User Key`  limit 1",
				prepare_mysql($_order_field_value),
				prepare_mysql($_order_field_value),
				$object->id
			);


			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$next_key=$row['object_key'];
				$next_title=_("User").' '.$row['object_name'].' ('.$row['object_key'].')';

			}


			if ($order_direction=='desc') {
				$_tmp1=$prev_key;
				$_tmp2=$prev_title;
				$prev_key=$next_key;
				$prev_title=$next_title;
				$next_key=$_tmp1;
				$next_title=$_tmp2;
			}


		}

		if ($data['parent']=='website') {

			$up_button=array('icon'=>'arrow-up', 'title'=>_("Website"), 'reference'=>'website/'.$data['parent_key']);

			if ($prev_key) {
				$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'website/'.$data['parent_key'].'/user/'.$prev_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'', 'url'=>'');

			}
			$left_buttons[]=$up_button;


			if ($next_key) {
				$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'website/'.$data['parent_key'].'/user/'.$next_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

			}


			$sections=get_sections('websites', $data['parent_key']);



		}
		elseif ($data['parent']=='page') {

			$page=new Page($data['parent_key']);

			$website=new Site($page->get('Page Site Key'));

			$up_button=array('icon'=>'arrow-up', 'title'=>_("Page"), 'reference'=>'website/'.$page->get('Page Site Key').'/page/'.$data['parent_key']);

			if ($prev_key) {
				$left_buttons[]=array('icon'=>'arrow-left', 'title'=>$prev_title, 'reference'=>'page/'.$data['parent_key'].'/user/'.$prev_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-left disabled', 'title'=>'', 'url'=>'');

			}
			$left_buttons[]=$up_button;


			if ($next_key) {
				$left_buttons[]=array('icon'=>'arrow-right', 'title'=>$next_title, 'reference'=>'page/'.$data['parent_key'].'/user/'.$next_key);

			}else {
				$left_buttons[]=array('icon'=>'arrow-right disabled', 'title'=>'', 'url'=>'');

			}


			$sections=get_sections('websites', '');



		}
	}
	else {


	}




	if (isset($sections[$_section]) )$sections[$_section]['selected']=true;



	$title= _('User').' <span class="id">'.$object->get('User Handle').' ('.$object->get_formated_id().')</span>';


	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search websites'))

	);
	$smarty->assign('_content', $_content);


	$html=$smarty->fetch('navigation.tpl');

	return $html;



}


?>
