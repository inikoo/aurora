<?php
include_once 'common.php';
include_once 'search_common.php';

if (!isset($_REQUEST['q'])) {
	$q='';
}else {
	$q=$_REQUEST['q'];

	

}

$result=process_search($q,$site->id);



if($q!=''){
if ($user) {
		$user_key=$user->id;
		
		if($customer->id){
		
		
		$note=_('Search for').' '.$q;
		$details=_('Search for').' '.$q.', '.number($result['number_results']).' '.ngettext('result found','results found',$result['number_results']).'. '.$result['did_you_mean'];
		$history_data=array(
				'Date'=>gmdate("Y-m-d H:i:s"),
				'Site Key'=>$site->id,
				'Note'=>$note,
				'Details'=>$details,
				'Action'=>'search',
				'Indirect Object'=>'',
				'User Key'=>$user_key
			);

			$customer->add_history_login($history_data);
		}
		

	} else {
		$user_key=0;

	}


	$sql=sprintf("insert into `Page Store Search Query Dimension` values (%d,%d,%d,%s,%s,%d)",
		$user_click_key,
		$site->id,
		$user_key,
		prepare_mysql(gmdate("Y-m-d H:i:s")),
		prepare_mysql($q),
		$result['number_results']

	);

	mysql_query($sql);

}


$smarty->assign('results',$result['results']);
$smarty->assign('number_results',$result['number_results']);
$smarty->assign('did_you_mean',$result['did_you_mean']);

if($result['number_results']==0)
$formated_number_results=_("Sorry, we didn't find any result").'.';
else 
$formated_number_results=ngettext('result found','results found',$result['number_results']).'.';


$smarty->assign('formated_number_results',$formated_number_results);




$smarty->assign('query',$q);

$page_key=$site->get_search_page_key();



include_once 'page.php';
?>
