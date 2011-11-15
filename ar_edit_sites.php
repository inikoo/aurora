<?php
/*


 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/

require_once 'common.php';
require_once 'class.Site.php';
require_once 'ar_edit_common.php';

if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case('add_see_also_page'):
    $data=prepare_values($_REQUEST,array(
                             'id'=>array('type'=>'key'),
                             'see_also_key'=>array('type'=>'key')

                         ));

    add_see_also_page($data);


    break;
case('edit_site'):

    $data=prepare_values($_REQUEST,array(
                             'site_key'=>array('type'=>'key'),
                             'values'=>array('type'=>'json array')

                         ));

    edit_site($data);
    break;
case('edit_checkout_method'):
    $data=prepare_values($_REQUEST,array(
                             'site_key'=>array('type'=>'key'),
                             'store_key'=>array('type'=>'key'),
							 'site_checkout_method'=>array('type'=>'string'),

                         ));

    edit_checkout_method($data);


    break;

case('edit_registration_method'):
    $data=prepare_values($_REQUEST,array(
                             'site_key'=>array('type'=>'key'),
                             'store_key'=>array('type'=>'key'),
							 'site_registration_method'=>array('type'=>'string'),

                         ));

    edit_registration_method($data);


    break;
case('delete_see_also_page'):
    $data=prepare_values($_REQUEST,array(
                             'id'=>array('type'=>'key'),
                             'see_also_key'=>array('type'=>'key')

                         ));

    delete_see_also_page($data);


    break;
case('add_found_in_page'):
    $data=prepare_values($_REQUEST,array(
                             'id'=>array('type'=>'key'),
                             'found_in_key'=>array('type'=>'key')

                         ));

    add_found_in_page($data);


    break;
case('delete_found_in_page'):
    $data=prepare_values($_REQUEST,array(
                             'id'=>array('type'=>'key'),
                             'found_in_key'=>array('type'=>'key')

                         ));

    delete_found_in_page($data);


    break;
case('delete_page_store'):

    $data=prepare_values($_REQUEST,array(
                             'id'=>array('type'=>'key'),

                         ));

    delete_page_store($data);
    break;
case('new_department_page'):
    $data=prepare_values($_REQUEST,array(
                             'site_key'=>array('type'=>'key'),
                             'department_key'=>array('type'=>'key')
                         ));

    new_department_page($data);
    break;


case('new_family_page'):
    $data=prepare_values($_REQUEST,array(
                             'site_key'=>array('type'=>'key'),
                             'family_key'=>array('type'=>'key')
                         ));

    new_family_page($data);
    break;
case('edit_page_layout'):
    edit_page_layout();
    break;
case('edit_page_html_head'):
case('edit_page_header'):
case('edit_page_content'):
case('edit_page_properties'):
    require_once 'class.Family.php';


    $data=prepare_values($_REQUEST,array(
                             'newvalue'=>array('type'=>'string'),
                             'key'=>array('type'=>'string'),
                             'id'=>array('type'=>'key')
                         ));

    edit_page($data);
    break;

case('edit_family_page_html_head'):
case('edit_family_page_header'):
case('edit_family_page_content'):
case('edit_family_page_properties'):
    require_once 'class.Family.php';


    $data=prepare_values($_REQUEST,array(
                             'newvalue'=>array('type'=>'string'),
                             'key'=>array('type'=>'string'),
                             'id'=>array('type'=>'key')
                         ));

    edit_page($data);
    break;
    
    case('edit_store_page_html_head'):
case('edit_store_page_header'):
case('edit_store_page_content'):
case('edit_store_page_properties'):
    require_once 'class.Store.php';


    $data=prepare_values($_REQUEST,array(
                             'newvalue'=>array('type'=>'string'),
                             'key'=>array('type'=>'string'),
                             'id'=>array('type'=>'key')
                         ));

    edit_page($data);
    break;
    
case('edit_department_page_html_head'):
case('edit_department_page_header'):
case('edit_department_page_content'):
case('edit_department_page_properties'):
    require_once 'class.Department.php';


    $data=prepare_values($_REQUEST,array(
                             'newvalue'=>array('type'=>'string'),
                             'key'=>array('type'=>'string'),
                             'id'=>array('type'=>'key')
                         ));

    edit_page($data);
    break;
    break;
case('family_page_list'):
case('department_page_list'):
case('store_pages'):
case('pages'):
    list_pages_for_edition();
    break;

default:

    $response=array('state'=>404,'msg'=>_('Operation not found'));
    echo json_encode($response);

}



function  edit_page($data) {

    global $editor;




    $page=new Page($data['id']);
    $page->editor=$editor;

    $page->update_field_switcher($data['key'],stripslashes(urldecode($data['newvalue'])));


    if ($page->updated) {
        $response= array('state'=>200,'newvalue'=>$page->new_value,'key'=>$data['key']);

    } else {
        $response= array('state'=>400,'msg'=>$page->msg,'key'=>$data['key']);
    }
    echo json_encode($response);

}

function edit_page_layout() {
    $page_key=$_REQUEST['page_key'];
    $layout=$_REQUEST['layout'];
    $value=$_REQUEST['newvalue'];

    $page=new Page($page_key);
    $page->update_show_layout($layout,$value);

    if ($page->updated) {
        $response= array('state'=>200,'newvalue'=>$page->new_value);

    } else {
        $response= array('state'=>400,'msg'=>$page->msg);
    }
    echo json_encode($response);


}


function new_department_page($data) {
    include_once('class.Department.php');
    $site=new Site($data['site_key']);
    $department=new Department($data['department_key']);
    $page_data=array();
    $page_data['Page Parent Key']=$department->id;
    $page_data['Page Store Slogan']='';
    $page_data['Page Store Resume']='';
    $page_data['Page Store Section']='Department Catalogue';
    $page_data['Showcases Layout']='Splited';
    $page_data['Page URL']='www.ancientwisdom.biz/'.strtolower($department->data['Product Department Code']);
    $site->add_department_page($page_data);

    if ($site->new_page) {
        $response= array('state'=>200,'action'=>'created');

    } else {
        $response= array('state'=>400,'msg'=>$site->msg);

    }
    echo json_encode($response);
}

function new_family_page($data) {
    include_once('class.Family.php');
    $site=new Site($data['site_key']);
    $family=new Family($data['family_key']);
    $page_data=array();
    $page_data['Page Parent Key']=$family->id;
    $page_data['Page Store Slogan']='';
    $page_data['Page Store Resume']='';
    $page_data['Page Store Section']='Family Catalogue';
    $page_data['Showcases Layout']='Splited';
    $page_data['Page URL']='www.ancientwisdom.biz/forms/'.strtolower($family->data['Product Family Code']);
    $site->add_family_page($page_data);

    if ($site->new_page) {
        $response= array('state'=>200,'action'=>'created');

    } else {
        $response= array('state'=>400,'msg'=>$site->msg);

    }
    echo json_encode($response);
}


function list_pages_for_edition() {
    if (isset( $_REQUEST['site_key'])) {
        $site_key=$_REQUEST['site_key'];

    } else
        $site_key=$conf['site_key'];


    $parent='site';
    $parent_key=$site_key;


    if ( isset($_REQUEST['parent']))
        $parent= $_REQUEST['parent'];

    if ($parent=='store')
        $parent_key=$_REQUEST['parent_key'];
    elseif ($parent=='family')
    $parent_key=$_REQUEST['parent_key'];
    elseif ($parent=='department')
    $parent_key=$_REQUEST['parent_key'];





    $conf=$_SESSION['state'][$parent]['edit_pages'];




    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];


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



    $_SESSION['state'][$parent]['edit_pages']['order']=$order;
    $_SESSION['state'][$parent]['edit_pages']['order_dir']=$order_direction;
    $_SESSION['state'][$parent]['edit_pages']['nr']=$number_results;
    $_SESSION['state'][$parent]['edit_pages']['sf']=$start_from;
    $_SESSION['state'][$parent]['edit_pages']['f_field']=$f_field;
    $_SESSION['state'][$parent]['edit_pages']['f_value']=$f_value;
    $_SESSION['state'][$parent]['edit_pages']['parent_key']=$parent_key;
    $_SESSION['state'][$parent]['edit_pages']['site_key']=$site_key;





    // print_r($_SESSION['tables']['families_list']);

    //  print_r($_SESSION['tables']['families_list']);

    $where=sprintf(' where `Page Type`="Store" and `Page Site Key`=%d ',$site_key);
    if ($parent=='store')
        $where.=sprintf("and `Page Store Section`  not in ('Department Catalogue','Product Description','Family Catalogue') and `Page Store Key`=%d ",$parent_key);
    elseif ($parent=='family')
    $where.=sprintf("and `Page Store Section`='Family Catalogue'   and `Page Parent Key`=%d ",$parent_key);
    elseif ($parent=='department')
    $where.=sprintf("and `Page Store Section`='Department Catalogue'   and `Page Parent Key`=%d ",$parent_key);

    $filter_msg='';
    $wheref='';
    if ($f_field=='description' and $f_value!='')
        $wheref.=" and  CONCAT(`Charge Description`,' ',`Charge Terms Description`) like '".addslashes($f_value)."%'";
    elseif($f_field=='name' and $f_value!='')
    $wheref.=" and  `Charge Name` like '".addslashes($f_value)."%'";








    $sql="select count(*) as total from `Page Dimension` P left join `Page Store Dimension` PS on (P.`Page Key`=PS.`Page Key`)  $where $wheref";
//print $sql;
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($result);
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total `Page Dimension` P left join `Page Store Dimension` PS on (P.`Page Key`=PS.`Page Key`)   $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($result);

    }


    $rtext=$total_records." ".ngettext('page','pages',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any charge with this name ")." <b>".$f_value."*</b> ";
            break;
        case('description'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any charge with description like ")." <b>".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('charges with name like')." <b>".$f_value."*</b>";
            break;
        case('description'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('charges with description like')." <b>".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';

    $_dir=$order_direction;
    $_order=$order;


    if ($order=='title')
        $order='`Page Title`';
    else
        $order='`Page Section`';


    $adata=array();
    $sql="select *  from `Page Dimension`  P left join `Page Store Dimension` PS on (P.`Page Key`=PS.`Page Key`) $where    order by $order $order_direction limit $start_from,$number_results    ";

    $res = mysql_query($sql);

    $total=mysql_num_rows($res);

    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {







        $adata[]=array(
                     'id'=>$row['Page Key'],
                     'section'=>$row['Page Section'],
                     'code'=>$row['Page Code'],
                     'store_title'=>$row['Page Store Title'],
                     'link_title'=>$row['Page Short Title'],
                     'url'=>$row['Page URL'],
                     'page_title'=>$row['Page Title'],
                     'page_keywords'=>$row['Page Keywords'],


                     'go'=>sprintf("<a href='edit_page.php?id=%d&referral=%s&referral_key=%s'><img src='art/icons/page_go.png' alt='go'></a>",$row['Page Key'],$parent,$parent_key),

                     'delete'=>"<img src='art/icons/cross.png'  alt='"._('Delete')."'  title='"._('Delete')."' />"

                 );
    }
    mysql_free_result($res);



    // if($total<$number_results)
    //  $rtext=$total.' '.ngettext('store','stores',$total);
    //else
    //  $rtext='';

//   $total_records=ceil($total_records/$number_results)+$total_records;

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

function  delete_page_store($data) {

    $page=new Page($data['id']);
    $page->delete();

    if ($page->deleted) {
        $response= array('state'=>200,'action'=>'deleted');

    } else {
        $response= array('state'=>400,'msg'=>$site->msg);

    }
    echo json_encode($response);

}

function add_found_in_page($data) {

    $page_key=$data['id'];
    $found_in_key=$data['found_in_key'];
    $sql=sprintf("insert into `Page Store Found In Bridge` values (%d,%d)  ",
                 $page_key,
                 $found_in_key);

    mysql_query($sql);
    $response= array('state'=>200,'action'=>'created','page_key'=>$page_key);
    echo json_encode($response);

}


function delete_found_in_page($data) {

    $page_key=$data['id'];
    $found_in_key=$data['found_in_key'];
    $sql=sprintf("delete from  `Page Store Found In Bridge` where `Page Store Key`=%d and `Page Store Found In Key`=%d   ",
                 $page_key,
                 $found_in_key);
    mysql_query($sql);
    $response= array('state'=>200,'action'=>'deleted','page_key'=>$page_key);
    echo json_encode($response);

}

function add_see_also_page($data) {

    $page_key=$data['id'];
    $see_also_key=$data['see_also_key'];
    $sql=sprintf("insert into `Page Store See Also Bridge` values (%d,%d,'Manual',null)  ",
                 $page_key,
                 $see_also_key);

    mysql_query($sql);
    $response= array('state'=>200,'action'=>'created','page_key'=>$page_key);
    echo json_encode($response);

}


function delete_see_also_page($data) {

    $page_key=$data['id'];
    $see_also_key=$data['see_also_key'];
    $sql=sprintf("delete from  `Page Store See Also Bridge` where `Page Store Key`=%d and `Page Store See Also Key`=%d   ",
                 $page_key,
                 $see_also_key);
    mysql_query($sql);
    $response= array('state'=>200,'action'=>'deleted','page_key'=>$page_key);
    echo json_encode($response);

}

function edit_checkout_method($data){
//print_r($data);
	$site = new Site($data['site_key']);
	if(!$site){
		$response= array('state'=>400,'msg'=>'Site not found','key'=>$data['site_key']);
		echo json_encode($response);

		exit;
	}
//print_r($site);	
	switch($data['site_checkout_method']){
		case 'inikoo':
		case 'Inikoo':
			$method='Inikoo';
			break;
		default:
			$method='Ecommerce';
	}
//print $method;
	$response=$site->update(array('Site Checkout Method'=>$method));
	if($site->updated){
		$response= array('state'=>200,'action'=>'updated','msg'=>$site->msg, 'new_value'=>strtolower($site->new_value));
	}
	else
		$response= array('state'=>400,'msg'=>$site->msg);
		
	echo json_encode($response);
}

function edit_registration_method($data){
	$site = new Site($data['site_key']);
	if(!$site){
		$response= array('state'=>400,'msg'=>'Site not found','key'=>$data['site_key']);
		echo json_encode($response);

		exit;
	}
//print_r($site);	
	switch($data['site_registration_method']){
		case 'sidebar':
		case 'Sidebar':
			$method='SideBar';
			break;
		default:
			$method='MainPage';
	}
//print $method;
	$response=$site->update(array('Site Registration Method'=>$method));
	if($site->updated){
		$response= array('state'=>200,'action'=>'updated','msg'=>$site->msg, 'new_value'=>strtolower($site->new_value));
	}
	else
		$response= array('state'=>400,'msg'=>$site->msg);
		
	echo json_encode($response);
}

function edit_site($data) {
    $site=new Site($data['site_key']);
    if (!$site->id) {
        $response= array('state'=>400,'msg'=>'Site not found','key'=>$data['key']);
        echo json_encode($response);

        exit;
    }
    $values=array();
    foreach($data['values'] as $value_key=>$value_data) {
        if ($value_data['value']=='') {
            $values[$value_key]=$value_data;
            unset($data['values'][$value_key]);
        }
    }

    foreach($data['values'] as $value_key=>$value_data) {

        $values[$value_key]=$value_data;

    }

//print_r($values);

    $responses=array();
    foreach($values as $key=>$values_data) {
		//print_r($values_data);
        $responses[]=edit_site_field($site->id,$key,$values_data);
    }

    echo json_encode($responses);


}

function edit_site_field($site_key,$key,$value_data) {

    //print $value_data;
	//print "$customer_key,$key,$value_data ***";
    $site=new site($site_key);

    global $editor;
    $site->editor=$editor;

    $key_dic=array(
                 'slogan'=>'Site Slogan'
				 ,'name'=>'Site Name'
				 ,'url'=>'Site URL'
				 ,'ftp'=>'Site FTP Credentials'
             );

    if (array_key_exists($key,$key_dic))
        $key=$key_dic[$key];

    $the_new_value=_trim($value_data['value']);
	//print "$key: $the_new_value";

    $site->update(array($key=>$the_new_value));
    
	if($site->updated){
		$response= array('state'=>200,'action'=>'updated','msg'=>$site->msg, 'newvalue'=>strtolower($site->new_value),'key'=>$value_data['okey']);
	}
	else
		$response= array('state'=>400,'msg'=>$site->msg,'key'=>$value_data['okey']);


	//$response=array();
    return $response;

}
