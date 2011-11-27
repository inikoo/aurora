<?php
/*
 File: ar_users.php

 Ajax Server Anchor for the User Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
require_once 'common.php';
require_once 'class.User.php';
require_once 'class.Staff.php';


if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case('forgot_password'):
    forgot_password();
    break;
case('staff_users'):
    list_staff_users();
    break;

case('staff_user_login_history'):
    list_staff_user_login_history();
    break;
case('customer_user_login_history'):
    list_customer_user_login_history();
    break;
case('supplier_user_login_history'):
    list_supplier_user_login_history();
    break;
case('supplier_users'):
    list_supplier_users();
    break;
case('customer_users'):
    list_customer_users();
    break;

case('login_history'):
    list_login_history();
    break;
case('staff_login_history'):
    list_staff_login_history();
    break;

case('groups'):
    list_groups();
    break;
default:
    $response=array('state'=>404,'msg'=>_('Operation not found'));
    echo json_encode($response);

}






function list_login_history() {
    $conf=$_SESSION['state']['users']['login_history'];
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
    if (isset( $_REQUEST['where']))
        $where=$_REQUEST['where'];
    else
        $where=$conf['where'];

    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;


    if (isset( $_REQUEST['type']))
        $type=$_REQUEST['type'];
    else
        $type=$conf['type'];


    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';


    $_SESSION['state']['users']['login_history']=array(
                'type'=>$type
                       ,'order'=>$order
                                ,'order_dir'=>$order_direction
                                             ,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);



    $where=sprintf('where true ');


    $filter_msg='';
    $wheref='';
    if ($f_field=='user' and $f_value!='')
        $wheref.=" and  `User Handle` like '".addslashes($f_value)."%'";
    elseif($f_field=='ip' and $f_value!='')
    $wheref.=" and  `IP Address` like '%".addslashes($f_value)."%'";
    $sql="select count(*) as total from `User Log Dimension` UL left join `User Dimension` U on (U.`User Key`=UL.`User Key`)  $where $wheref   ";

    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($res);
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `User Log Dimension`  $where   ";
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($res);
    }


    $rtext=$total_records." ".ngettext('user','users',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=_('(Showing all)');


    $translations=array('handle'=>'`User Handle`');
    if (array_key_exists($order,$translations))
        $order=$translations[$order];


    switch ($order) {
    case 'login_date':
    default:
        $order='`Start Date`';

    }

    $adata=array();
    $sql="Select *  from `User Log Dimension` UL left join `User Dimension` U on (U.`User Key`=UL.`User Key`)  $where $wheref  order by $order $order_direction limit $start_from,$number_results;";
// print $sql;
    $res=mysql_query($sql);

    while ($row=mysql_fetch_array($res)) {
        if ($row['Logout Date']=="") {
            $adata[]=array(
                         'user'=>$row['User Handle'],
                         'ip'=>$row['IP'],
                         'login_date'=>strftime("%c",strtotime($row['Start Date'])),
                         'logout_date'=>"",
                     );
        } else {
            $adata[]=array(
                         'user'=>$row['User Handle'],
                         'ip'=>$row['IP'],
                         'login_date'=>strftime("%c",strtotime($row['Start Date'])),
                         'logout_date'=>strftime("%c",strtotime($row['Logout Date'])),
                     );
        }

    }
    mysql_free_result($res);

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'total_records'=>$total,
                                      'records_offset'=>$start_from,
                                      'records_returned'=>$total,
                                      'records_perpage'=>$number_results,
                                      'records_text'=>$rtext,
                                      'records_order'=>$order,
                                      'records_order_dir'=>$order_dir,
                                      'filtered'=>$filtered,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp
                                     )
                   );

    echo json_encode($response);
}

function list_staff_login_history() {
    $conf=$_SESSION['state']['users']['login_history'];
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
    if (isset( $_REQUEST['where']))
        $where=$_REQUEST['where'];
    else
        $where=$conf['where'];

    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;


    if (isset( $_REQUEST['type']))
        $type=$_REQUEST['type'];
    else
        $type=$conf['type'];


    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';


    $_SESSION['state']['users']['login_history']=array(
                'type'=>$type
                       ,'order'=>$order
                                ,'order_dir'=>$order_direction
                                             ,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);



    $where=sprintf('where true ');


    $filter_msg='';
    $wheref='';
    if ($f_field=='user' and $f_value!='')
        $wheref.=" and  `User Handle` like '".addslashes($f_value)."%'";
    elseif($f_field=='ip' and $f_value!='')
    $wheref.=" and  `IP Address` like '%".addslashes($f_value)."%'";
    $where.=" and `User Type`='Staff'";
    $sql="select count(*) as total from `User Log Dimension` UL left join `User Dimension` U on (U.`User Key`=UL.`User Key`)  $where $wheref   ";

    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($res);
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `User Log Dimension`  $where   ";
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($res);
    }


    $rtext=$total_records." ".ngettext('user','users',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=_('(Showing all)');


    $translations=array('handle'=>'`User Handle`');
    if (array_key_exists($order,$translations))
        $order=$translations[$order];


    switch ($order) {
    case 'login_date':
    default:
        $order='`Start Date`';

    }

    $adata=array();
    $sql="Select *  from `User Log Dimension` UL left join `User Dimension` U on (U.`User Key`=UL.`User Key`)  $where $wheref  order by $order $order_direction limit $start_from,$number_results;";
// print $sql;
    $res=mysql_query($sql);

    while ($row=mysql_fetch_array($res)) {
        if ($row['Logout Date']=="") {
            $adata[]=array(
                         'user'=>$row['User Handle'],
                         'ip'=>$row['IP'],
                         'login_date'=>strftime("%c",strtotime($row['Start Date'])),
                         'logout_date'=>"",
                     );
        } else {
            $adata[]=array(
                         'user'=>$row['User Handle'],
                         'ip'=>$row['IP'],
                         'login_date'=>strftime("%c",strtotime($row['Start Date'])),
                         'logout_date'=>strftime("%c",strtotime($row['Logout Date'])),
                     );
        }

    }
    mysql_free_result($res);

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'total_records'=>$total,
                                      'records_offset'=>$start_from,
                                      'records_returned'=>$total,
                                      'records_perpage'=>$number_results,
                                      'records_text'=>$rtext,
                                      'records_order'=>$order,
                                      'records_order_dir'=>$order_dir,
                                      'filtered'=>$filtered,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp
                                     )
                   );

    echo json_encode($response);
}
function list_customer_user_login_history() {
    $conf=$_SESSION['state']['staff_user']['login_history'];
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
    if (isset( $_REQUEST['where']))
        $where=$_REQUEST['where'];
    else
        $where=$conf['where'];

    //print $_REQUEST['user_key'];
    if (isset( $_REQUEST['user_key'])) {
        $id=$_REQUEST['user_key'];
        $_SESSION['state']['staff_user']['user_key']=$id;

    } else {
        $id=$_SESSION['state']['staff_user']['user_key'];
    }


//print $_REQUEST['tableid'];
//print $_REQUEST['user_key'];
    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;


    if (isset( $_REQUEST['type']))
        $type=$_REQUEST['type'];
    else
        $type=$conf['type'];


    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';


    $_SESSION['state']['staff_user']['login_history']['type']=$type;
    $_SESSION['state']['staff_user']['login_history']['order']=$order;
    $_SESSION['state']['usstaff_userers']['login_history']['order_dir']=$order_direction;
    $_SESSION['state']['staff_user']['login_history']['nr']=$number_results;

    $_SESSION['state']['staff_user']['login_history']['sf']=$start_from;
    $_SESSION['state']['staff_user']['login_history']['where']=$where;
    $_SESSION['state']['staff_user']['login_history']['f_field']=$f_field;
    $_SESSION['state']['staff_user']['login_history']['f_value']=$f_value;
    $_SESSION['state']['staff_user']['login_history']['f_value']=$f_value;

    $where=sprintf('where true ');
    $where.=" and `User Type`='Customer'";

    if (isset($_REQUEST['customer_user']))
        $where.=" and UL.`User Key`=".$id;
    else
        $where.=" and U.`User Parent Key`=".$id;

    $filter_msg='';
    $wheref='';
    if ($f_field=='user' and $f_value!='')
        $wheref.=" and  `User Handle` like '".addslashes($f_value)."%'";
    elseif($f_field=='ip' and $f_value!='')
    $wheref.=" and  `IP Address` like '%".addslashes($f_value)."%'";

    $sql="select count(*) as total from `User Log Dimension` UL left join `User Dimension` U on (U.`User Key`=UL.`User Key`)  $where $wheref   ";

    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($res);
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `User Log Dimension`  $where   ";
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($res);
    }


    $rtext=$total_records." ".ngettext('record','records',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=_('(Showing all)');


    $translations=array('handle'=>'`User Handle`');
    if (array_key_exists($order,$translations))
        $order=$translations[$order];


    switch ($order) {
    case 'login_date':
    default:
        $order='`Start Date`';
        $order_direction='DESC';
    }

    $adata=array();
    $sql="Select *  from `User Log Dimension` UL left join `User Dimension` U on (U.`User Key`=UL.`User Key`)  $where $wheref  order by $order $order_direction limit $start_from,$number_results;";
    //print $sql;
    $res=mysql_query($sql);

    while ($row=mysql_fetch_array($res)) {
        if ($row['Logout Date']=="") {
            $adata[]=array(
                         'user'=>$row['User Handle'],
                         'ip'=>$row['IP'],
                         'login_date'=>strftime("%c",strtotime($row['Start Date'])),
                         'logout_date'=>"",
                     );
        } else {
            $adata[]=array(
                         'user'=>$row['User Handle'],
                         'ip'=>$row['IP'],
                         'login_date'=>strftime("%c",strtotime($row['Start Date'])),
                         'logout_date'=>strftime("%c",strtotime($row['Logout Date'])),
                     );
        }

    }
    mysql_free_result($res);

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'total_records'=>$total,
                                      'records_offset'=>$start_from,
                                      'records_returned'=>$total,
                                      'records_perpage'=>$number_results,
                                      'records_text'=>$rtext,
                                      'records_order'=>$order,
                                      'records_order_dir'=>$order_dir,
                                      'filtered'=>$filtered,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp
                                     )
                   );

    echo json_encode($response);
}

function list_supplier_user_login_history() {
    $conf=$_SESSION['state']['staff_user']['login_history'];
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
    if (isset( $_REQUEST['where']))
        $where=$_REQUEST['where'];
    else
        $where=$conf['where'];

    //print $_REQUEST['user_key'];
    if (isset( $_REQUEST['user_key'])) {
        $id=$_REQUEST['user_key'];
        $_SESSION['state']['staff_user']['user_key']=$id;

    } else {
        $id=$_SESSION['state']['staff_user']['user_key'];
    }


//print $_REQUEST['tableid'];
//print $_REQUEST['user_key'];
    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;


    if (isset( $_REQUEST['type']))
        $type=$_REQUEST['type'];
    else
        $type=$conf['type'];


    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';


    $_SESSION['state']['staff_user']['login_history']['type']=$type;
    $_SESSION['state']['staff_user']['login_history']['order']=$order;
    $_SESSION['state']['usstaff_userers']['login_history']['order_dir']=$order_direction;
    $_SESSION['state']['staff_user']['login_history']['nr']=$number_results;

    $_SESSION['state']['staff_user']['login_history']['sf']=$start_from;
    $_SESSION['state']['staff_user']['login_history']['where']=$where;
    $_SESSION['state']['staff_user']['login_history']['f_field']=$f_field;
    $_SESSION['state']['staff_user']['login_history']['f_value']=$f_value;
    $_SESSION['state']['staff_user']['login_history']['f_value']=$f_value;

    $where=sprintf('where true ');
    $where.=" and `User Type`='Supplier'";


    $where.=" and UL.`User Key`=".$id;


    $filter_msg='';
    $wheref='';
    if ($f_field=='user' and $f_value!='')
        $wheref.=" and  `User Handle` like '".addslashes($f_value)."%'";
    elseif($f_field=='ip' and $f_value!='')
    $wheref.=" and  `IP Address` like '%".addslashes($f_value)."%'";

    $sql="select count(*) as total from `User Log Dimension` UL left join `User Dimension` U on (U.`User Key`=UL.`User Key`)  $where $wheref   ";

    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($res);
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `User Log Dimension`  $where   ";
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($res);
    }


    $rtext=$total_records." ".ngettext('record','records',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=_('(Showing all)');


    $translations=array('handle'=>'`User Handle`');
    if (array_key_exists($order,$translations))
        $order=$translations[$order];


    switch ($order) {
    case 'login_date':
    default:
        $order='`Start Date`';
        $order_direction='DESC';
    }

    $adata=array();
    $sql="Select *  from `User Log Dimension` UL left join `User Dimension` U on (U.`User Key`=UL.`User Key`)  $where $wheref  order by $order $order_direction limit $start_from,$number_results;";
    //print $sql;
    $res=mysql_query($sql);

    while ($row=mysql_fetch_array($res)) {

        if ($row['Logout Date']=="") {
            $adata[]=array(
                         'user'=>$row['User Handle'],
                         'ip'=>$row['IP'],
                         'login_date'=>strftime("%c",strtotime($row['Start Date'])),
                         'logout_date'=>"",
                     );
        } else {
            $adata[]=array(
                         'user'=>$row['User Handle'],
                         'ip'=>$row['IP'],
                         'login_date'=>strftime("%c",strtotime($row['Start Date'])),
                         'logout_date'=>strftime("%c",strtotime($row['Logout Date'])),
                     );
        }

    }
    mysql_free_result($res);

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'total_records'=>$total,
                                      'records_offset'=>$start_from,
                                      'records_returned'=>$total,
                                      'records_perpage'=>$number_results,
                                      'records_text'=>$rtext,
                                      'records_order'=>$order,
                                      'records_order_dir'=>$order_dir,
                                      'filtered'=>$filtered,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp
                                     )
                   );

    echo json_encode($response);
}

function list_staff_user_login_history() {
    $conf=$_SESSION['state']['staff_user']['login_history'];
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
    if (isset( $_REQUEST['where']))
        $where=$_REQUEST['where'];
    else
        $where=$conf['where'];

    //print $_REQUEST['user_key'];
    if (isset( $_REQUEST['user_key'])) {
        $id=$_REQUEST['user_key'];
        $_SESSION['state']['staff_user']['user_key']=$id;

    } else {
        $id=$_SESSION['state']['staff_user']['user_key'];
    }


//print $_REQUEST['tableid'];
//print $_REQUEST['user_key'];
    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;


    if (isset( $_REQUEST['type']))
        $type=$_REQUEST['type'];
    else
        $type=$conf['type'];


    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';


    $_SESSION['state']['staff_user']['login_history']['type']=$type;
    $_SESSION['state']['staff_user']['login_history']['order']=$order;
    $_SESSION['state']['usstaff_userers']['login_history']['order_dir']=$order_direction;
    $_SESSION['state']['staff_user']['login_history']['nr']=$number_results;

    $_SESSION['state']['staff_user']['login_history']['sf']=$start_from;
    $_SESSION['state']['staff_user']['login_history']['where']=$where;
    $_SESSION['state']['staff_user']['login_history']['f_field']=$f_field;
    $_SESSION['state']['staff_user']['login_history']['f_value']=$f_value;
    $_SESSION['state']['staff_user']['login_history']['f_value']=$f_value;

    $where=sprintf('where true ');
    $where.=" and `User Type`='Staff'";


    $where.=" and UL.`User Key`=".$id;


    $filter_msg='';
    $wheref='';
    if ($f_field=='user' and $f_value!='')
        $wheref.=" and  `User Handle` like '".addslashes($f_value)."%'";
    elseif($f_field=='ip' and $f_value!='')
    $wheref.=" and  `IP Address` like '%".addslashes($f_value)."%'";

    $sql="select count(*) as total from `User Log Dimension` UL left join `User Dimension` U on (U.`User Key`=UL.`User Key`)  $where $wheref   ";

    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($res);
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `User Log Dimension`  $where   ";
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($res);
    }


    $rtext=$total_records." ".ngettext('record','records',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=_('(Showing all)');


    $translations=array('handle'=>'`User Handle`');
    if (array_key_exists($order,$translations))
        $order=$translations[$order];


    switch ($order) {
    case 'login_date':
    default:
        $order='`Start Date`';
        $order_direction='DESC';
    }

    $adata=array();
    $sql="Select *  from `User Log Dimension` UL left join `User Dimension` U on (U.`User Key`=UL.`User Key`)  $where $wheref  order by $order $order_direction limit $start_from,$number_results;";
    //print $sql;
    $res=mysql_query($sql);

    while ($row=mysql_fetch_array($res)) {
        if ($row['Logout Date']=="") {
            $adata[]=array(
                         'user'=>$row['User Handle'],
                         'ip'=>$row['IP'],
                         'login_date'=>strftime("%c",strtotime($row['Start Date'])),
                         'logout_date'=>"",
                     );
        } else {
            $adata[]=array(
                         'user'=>$row['User Handle'],
                         'ip'=>$row['IP'],
                         'login_date'=>strftime("%c",strtotime($row['Start Date'])),
                         'logout_date'=>strftime("%c",strtotime($row['Logout Date'])),
                     );
        }

    }
    mysql_free_result($res);

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'total_records'=>$total,
                                      'records_offset'=>$start_from,
                                      'records_returned'=>$total,
                                      'records_perpage'=>$number_results,
                                      'records_text'=>$rtext,
                                      'records_order'=>$order,
                                      'records_order_dir'=>$order_dir,
                                      'filtered'=>$filtered,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp
                                     )
                   );

    echo json_encode($response);
}

function list_groups() {
    $conf=$_SESSION['state']['users']['groups'];
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
    if (isset( $_REQUEST['where']))
        $where=$_REQUEST['where'];
    else
        $where=$conf['where'];

    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;

    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';


    $_SESSION['state']['users']['groups']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);


    $filtered=0;



    $where='';
    $wheref='';

    $sql="select count(*) as total from `User Group Dimension`     ";


    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        $total=$row['total'];
    }
    if ($wheref!='') {
        $sql="select count(*) as total_without_filters from `User Group Dimension`  $where $wheref   ";

        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

            $total_records=$row['total_without_filters'];
            $filtered=$row['total_without_filters']-$total;
        }

    } else {
        $filtered=0;
        $filter_total=0;
        $total_records=$total;
    }
    mysql_free_result($res);


    $rtext=$total_records." ".ngettext('work group','work groups',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._("Showing All").')';




    $data=array();
    $_order=$order;
    if ($order=='name') {
        $_order='`User Group Name`';
    } else
        $_order='`User Group Name`';

    $sql="select *,(select GROUP_CONCAT(`User Alias`) from `User Dimension` U left join `User Group User Bridge` UGUB on (U.`User Key`=UGUB.`User Key`)
         where UGUB.`User Group Key`=UG.`User Group Key` ) as Users from `User Group Dimension` UG  $where order by $_order $order_direction limit $start_from,$number_results       ";
//print $sql;
    $res=mysql_query($sql);



    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        $data[]=array(
                    'name'=>$row['User Group Name'],
                    'id'=>$row['User Group Key'],
                    'users'=>$row['Users']
                );
    }
    mysql_free_result($res);
    $response=array('resultset'=>
                                array(
                                    'state'=>200,
                                    'data'=>$data,
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


function list_staff_users() {
    global $myconf;

    $conf=$_SESSION['state']['users']['staff'];
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




    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;



    if (isset( $_REQUEST['elements']))
        $elements=$_REQUEST['elements'];
    else
        $elements=$conf['elements'];



    if (isset( $_REQUEST['elements_InactiveNotWorking'])) {
        $elements['InactiveNotWorking']=$_REQUEST['elements_InactiveNotWorking'];
    }
    if (isset( $_REQUEST['elements_InactiveWorking'])) {
        $elements['InactiveWorking']=$_REQUEST['elements_InactiveWorking'];
    }
    if (isset( $_REQUEST['elements_ActiveNotWorking'])) {
        $elements['ActiveNotWorking']=$_REQUEST['elements_ActiveNotWorking'];
    }
    if (isset( $_REQUEST['elements_ActiveWorking'])) {
        $elements['ActiveWorking']=$_REQUEST['elements_ActiveWorking'];
    }






    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    $_order=$order;
    $_dir=$order_direction;




    $_SESSION['state']['users']['staff']['order']=$order;
    $_SESSION['state']['users']['staff']['order_dir']=$order_direction;
    $_SESSION['state']['users']['staff']['nr']=$number_results;
    $_SESSION['state']['users']['staff']['sf']=$start_from;
    $_SESSION['state']['users']['staff']['f_field']=$f_field;
    $_SESSION['state']['users']['staff']['f_value']=$f_value;

    $_SESSION['state']['users']['staff']['elements']=$elements;


  //  $where=" where `User Key` IS NOT NULL  ";
$where=" where  true";
    $_elements='';
    foreach($elements as $_key=>$_value) {
        if ($_value) {
            if ($_key=='InactiveNotWorking') {
                $_elements.=",'Inactive No tWorking'";
            }
            elseif($_key=='InactiveWorking') {
                $_elements.=",'Inactive Working'";
            }
            elseif($_key=='ActiveNotWorking') {
                $_elements.=",'Active Not Working'";
            }
              elseif($_key=='ActiveWorking') {
                $_elements.=",'Active Working'";
            }
        }
    }
    $_elements=preg_replace('/^\,/','',$_elements);
    if ($_elements=='') {
        $where.=' and false' ;
    } else {
        $where.=' and `User Staff Type` in ('.$_elements.')' ;
    }




    $wheref='';
    if ($f_field=='name' and $f_value!=''  )
        $wheref.=" and  name like '%".addslashes($f_value)."%'    ";
    else if ($f_field=='position_id' or $f_field=='area_id'   and is_numeric($f_value) )
        $wheref.=sprintf(" and  $f_field=%d ",$f_value);




    $sql="select count(*) as total from `Staff Dimension` SD  left join `User Dimension` on (`User Parent Key`=`Staff Key`) $where $wheref";


    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($wheref!='') {
        $sql="select count(*) as total from `Staff Dimension` SD  left join `User Dimension` on (`User Parent Key`=`Staff Key`)  $where ";
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



    $rtext=$total_records." ".ngettext('user','users',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=_('(Showing all)');



    switch ($f_field) {
    case('name'):
        if ($total==0 and $filtered>0)
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff with name")." <b>*".$f_value."*</b> ";
        elseif($filtered>0)
        $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff with name')." <b>*".$f_value."*</b>)";
        break;
    case('area_id'):
        if ($total==0 and $filtered>0)
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff on area")." <b>".$f_value."</b> ";
        elseif($filtered>0)
        $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff on area')." <b>".$f_value."</b>)";
        break;
    case('position_id'):
        if ($total==0 and $filtered>0)
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff with position")." <b>".$f_value."</b> ";
        elseif($filtered>0)
        $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff with position')." <b>".$f_value."</b>)";
        break;

    }

    $_order=$order;
    if ($order=='name')
        $order='`Staff Name`';
    elseif($order=='position')
    $order='position';
    else
        $order='`Staff Name`';
    $sql="select (select GROUP_CONCAT(distinct `Company Position Title`) from `Company Position Staff Bridge` PSB  left join `Company Position Dimension` P on (`Company Position Key`=`Position Key`) where PSB.`Staff Key`= SD.`Staff Key`) as position, `Staff Alias`,`Staff Key`,`Staff Name` from `Staff Dimension` SD  left join `User Dimension` on (`User Parent Key`=`Staff Key`) $where  $wheref and `User Type`='Staff' order by $order $order_direction limit $start_from,$number_results";

    $sql="select `User Failed Login Count`,`User Last Failed Login`,`User Last Login`,`User Login Count`,`User Alias`,(select GROUP_CONCAT(URSB.`Scope Key`) from `User Right Scope Bridge` URSB where URSB.`User Key`=U.`User Key` and `Scope`='Store'  ) as Stores,(select GROUP_CONCAT(URSB.`Scope Key`) from `User Right Scope Bridge` URSB where URSB.`User Key`=U.`User Key`and `Scope`='Warehouse'  ) as Warehouses ,(select GROUP_CONCAT(UGUD.`User Group Key`) from `User Group User Bridge` UGUD left join  `User Group Dimension` UGD on (UGUD.`User Group Key`=UGD.`User Group Key`)      where UGUD.`User Key`=U.`User Key` ) as Groups,`User Key`,`User Active`, `Staff Alias`,`Staff Key`,`Staff Name` from `Staff Dimension` SD  left join `User Dimension` U on (`User Parent Key`=`Staff Key`) $where  $wheref and (`User Type`='Staff' or `User Type` is null ) order by $order $order_direction limit $start_from,$number_results";
    // print $sql;
    $adata=array();
    $res=mysql_query($sql);
    while ($data=mysql_fetch_array($res)) {

        $groups=preg_split('/,/',$data['Groups']);
        $stores=preg_split('/,/',$data['Stores']);
        $warehouses=preg_split('/,/',$data['Warehouses']);

        //   $_id=$myconf['staff_prefix'].sprintf('%03d',$data['Staff Key']);
        //  $id=sprintf('<a href="staff.php?id=%d">%s</a>',$data['Staff Key'],$_id);
        $is_active='No';

        if ($data['User Active']=='Yes')
            $is_active='Yes';

        $password='';
        if ($data['User Key']) {
            $password='<img style="cursor:pointer" user_name="'.$data['User Alias'].'" user_id="'.$data['User Key'].'" onClick="change_passwd(this)" src="art/icons/key.png"/>';
        }

        $alias=sprintf('<a href="staff_user.php?id=%d">%s</a>',$data['User Key'],$data['Staff Alias']);
        $adata[]=array(
                     'id'=>$data['User Key'],
                     'staff_id'=>$data['Staff Key'],
                     'alias'=>$alias,
                     'name'=>$data['Staff Name'],
                     'password'=>$password,
                     'logins'=>number($data['User Login Count']),
                     'last_login'=>($data ['User Last Login']==''?'':strftime ( "%e %b %Y %H:%M %Z", strtotime ( $data ['User Last Login']." +00:00" ) )),
                     'fail_logins'=>number($data['User Failed Login Count']),
                     'fail_last_login'=>($data ['User Last Failed Login']==''?'':strftime ( "%e %b %Y %H:%M %Z", strtotime ( $data ['User Last Failed Login']." +00:00" ) )),

                     'groups'=>$groups,
                     'stores'=>$stores,
                     'warehouses'=>$warehouses,
                     'isactive'=>$is_active
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


function list_supplier_users() {
    global $myconf;
    $conf=$_SESSION['state']['users']['supplier'];
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
    if (isset( $_REQUEST['where']))
        $where=$_REQUEST['where'];
    else
        $where=$conf['where'];






    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;

    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    $_order=$order;
    $_dir=$order_direction;



    $_SESSION['state']['users']['supplier']=array(

                                                'order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);



    $wheref='';
    if ($f_field=='name' and $f_value!=''  )
        $wheref.=" and  name like '%".addslashes($f_value)."%'    ";
    else if ($f_field=='position_id' or $f_field=='area_id'   and is_numeric($f_value) )
        $wheref.=sprintf(" and  $f_field=%d ",$f_value);

    $where.=" and `User Key` IS NOT NULL and `User Active`='Yes' and `User Type`='Supplier' ";


    // $where.=" and `User Key` IS NOT NULL and `User Type`='Supplier' ";     //will use this $where when will insert any supplier info in 'user dimension'

    $sql="select count(*) as total from `Supplier Dimension` SD  left join `User Dimension` on (`User Parent Key`=`Supplier Key`) $where $wheref";


    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($wheref!='') {
        $sql="select count(*) as total from `Supplier Dimension` SD  left join `User Dimension` on (`User Parent Key`=`Supplier Key`)  $where ";
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
    $rtext=$total_records." ".ngettext('record','records',$total_records);
    if ($total_records>$number_results)
        $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));
    $filter_msg='';

    switch ($f_field) {
    case('name'):
        if ($total==0 and $filtered>0)
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff with name")." <b>*".$f_value."*</b> ";
        elseif($filtered>0)
        $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff with name')." <b>*".$f_value."*</b>)";
        break;
    case('area_id'):
        if ($total==0 and $filtered>0)
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff on area")." <b>".$f_value."</b> ";
        elseif($filtered>0)
        $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff on area')." <b>".$f_value."</b>)";
        break;
    case('position_id'):
        if ($total==0 and $filtered>0)
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff with position")." <b>".$f_value."</b> ";
        elseif($filtered>0)
        $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff with position')." <b>".$f_value."</b>)";
        break;

    }

    $_order=$order;
    if ($order=='name')
        $order='`Supplier Name`';
    elseif($order=='position')
    $order='position';
    else
        $order='`Supplier Name`';
    //$sql="select (select GROUP_CONCAT(distinct `Company Position Title`) from `Company Position Staff Bridge` PSB  left join `Company Position Dimension` P on (`Company Position Key`=`Position Key`) where PSB.`Staff Key`= SD.`Staff Key`) as position, `Staff Alias`,`Staff Key`,`Staff Name` from `Staff Dimension` SD  left join `User Dimension` on (`User Parent Key`=`Staff Key`) $where  $wheref and `User Type`='Staff' order by $order $order_direction limit $start_from,$number_results";

    //  $sql="select `User Alias`,(select GROUP_CONCAT(URSB.`Scope Key`) from `User Right Scope Bridge` URSB where URSB.`User Key`=U.`User Key` and `Scope`='Store'  ) as Stores,(select GROUP_CONCAT(URSB.`Scope Key`) from `User Right Scope Bridge` URSB where URSB.`User Key`=U.`User Key`and `Scope`='Warehouse'  ) as Warehouses ,(select GROUP_CONCAT(UGUD.`User Group Key`) from `User Group User Bridge` UGUD left join  `User Group Dimension` UGD on (UGUD.`User Group Key`=UGD.`User Group Key`)      where UGUD.`User Key`=U.`User Key` ) as Groups,`User Key`,`Supplier Active`, `Supplier Key`,`Supplier Name` from `Supplier Dimension` SD  left join `User Dimension` U on (`User Parent Key`=`Supplier Key`) $where  $wheref and (`User Type`='Supplier' or `User Type` is null ) order by $order $order_direction limit $start_from,$number_results";



    $sql="select *   from `Supplier Dimension` SD  left join `User Dimension` U on (`User Parent Key`=`Supplier Key`)  $where $wheref order by $order $order_direction limit $start_from,$number_results";
//print $sql;


    $adata=array();
    $res=mysql_query($sql);
    while ($data=mysql_fetch_array($res)) {

        //$groups=preg_split('/,/',$data['Groups']);
        //$stores=preg_split('/,/',$data['Stores']);
        // $warehouses=preg_split('/,/',$data['Warehouses']);

        //   $_id=$myconf['staff_prefix'].sprintf('%03d',$data['Staff Key']);
        //  $id=sprintf('<a href="staff.php?id=%d">%s</a>',$data['Staff Key'],$_id);
        $is_active='No';

        if ($data['User Active']=='Yes')
            $is_active='Yes';

        $password='';
        if ($data['User Key']) {
            $password='<img style="cursor:pointer" user_name="'.$data['User Alias'].'" user_id="'.$data['User Key'].'" onClick="change_passwd(this)" src="art/icons/key.png"/>';
        }

        $alias=sprintf('<a href="supplier_user.php?id=%d">%s</a>',$data['User Key'],$data['User Alias']);
        $adata[]=array(
                     'id'=>$data['User Key'],
                     'staff_id'=>$data['User Key'],
                     'alias'=>$alias,
                     'name'=>$data['User Handle'],
                     'password'=>$password,
                     'logins'=>number($data['User Login Count']),
                     'last_login'=>($data ['User Last Login']==''?'':strftime ( "%e %b %Y %H:%M %Z", strtotime ( $data ['User Last Login']." +00:00" ) )),
                     'fail_logins'=>number($data['User Failed Login Count']),
                     'fail_last_login'=>($data ['User Last Failed Login']==''?'':strftime ( "%e %b %Y %H:%M %Z", strtotime ( $data ['User Last Failed Login']." +00:00" ) ))

                                       //'groups'=>$groups,
                                       //  'stores'=>$stores,
                                       //  'warehouses'=>$warehouses,
                                       //  'isactive'=>$is_active
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
function list_customer_users() {
    global $myconf;
    $conf=$_SESSION['state']['users']['customer'];
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
    if (isset( $_REQUEST['where']))
        $where=$_REQUEST['where'];
    else
        $where=$conf['where'];






    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;

    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    $_order=$order;
    $_dir=$order_direction;



    $_SESSION['state']['users']['customer']=array(

                                                'order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);



    $wheref='';
    if ($f_field=='name' and $f_value!=''  )
        $wheref.=" and  name like '%".addslashes($f_value)."%'    ";
    else if ($f_field=='position_id' or $f_field=='area_id'   and is_numeric($f_value) )
        $wheref.=sprintf(" and  $f_field=%d ",$f_value);

    $where.=" and `User Key` IS NOT NULL  ";
    $where.=sprintf(" and `User Type`='Customer' and `User Site Key`=%d", $_REQUEST['store_key']);
    $sql="select count(*) as total from `Customer Dimension` SD  left join `User Dimension` on (`User Parent Key`=`Customer Key`) $where $wheref";


    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($wheref!='') {
        $sql="select count(*) as total from `Customer Dimension` SD  left join `User Dimension` on (`User Parent Key`=`Customer Key`)  $where ";
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
    $rtext=$total_records." ".ngettext('record','records',$total_records);
    if ($total_records>$number_results)
        $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));
    $filter_msg='';

    switch ($f_field) {
    case('name'):
        if ($total==0 and $filtered>0)
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no customer with name")." <b>*".$f_value."*</b> ";
        elseif($filtered>0)
        $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('customer with name')." <b>*".$f_value."*</b>)";
        break;
    case('area_id'):
        if ($total==0 and $filtered>0)
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no customer on area")." <b>".$f_value."</b> ";
        elseif($filtered>0)
        $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('customer on area')." <b>".$f_value."</b>)";
        break;
    case('position_id'):
        if ($total==0 and $filtered>0)
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no customer with position")." <b>".$f_value."</b> ";
        elseif($filtered>0)
        $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('customer with position')." <b>".$f_value."</b>)";
        break;

    }

    $_order=$order;
    if ($order=='name')
        $order='`Customer Main Contact Name`';
    elseif($order=='position')
    $order='position';
    else
        $order='`Customer Main Contact Name`';
    //$sql="select (select GROUP_CONCAT(distinct `Company Position Title`) from `Company Position Staff Bridge` PSB  left join `Company Position Dimension` P on (`Company Position Key`=`Position Key`) where PSB.`Staff Key`= SD.`Staff Key`) as position, `Staff Alias`,`Staff Key`,`Staff Name` from `Staff Dimension` SD  left join `User Dimension` on (`User Parent Key`=`Staff Key`) $where  $wheref and `User Type`='Staff' order by $order $order_direction limit $start_from,$number_results";

    //  $sql="select `User Alias`,(select GROUP_CONCAT(URSB.`Scope Key`) from `User Right Scope Bridge` URSB where URSB.`User Key`=U.`User Key` and `Scope`='Store'  ) as Stores,(select GROUP_CONCAT(URSB.`Scope Key`) from `User Right Scope Bridge` URSB where URSB.`User Key`=U.`User Key`and `Scope`='Warehouse'  ) as Warehouses ,(select GROUP_CONCAT(UGUD.`User Group Key`) from `User Group User Bridge` UGUD left join  `User Group Dimension` UGD on (UGUD.`User Group Key`=UGD.`User Group Key`)      where UGUD.`User Key`=U.`User Key` ) as Groups,`User Key`,`Supplier Active`, `Supplier Key`,`Supplier Name` from `Supplier Dimension` SD  left join `User Dimension` U on (`User Parent Key`=`Supplier Key`) $where  $wheref and (`User Type`='Supplier' or `User Type` is null ) order by $order $order_direction limit $start_from,$number_results";



    $sql="select *   from `Customer Dimension` SD  left join `User Dimension` U on (`User Parent Key`=`Customer Key`)  $where $wheref order by $order $order_direction limit $start_from,$number_results";

    //print $sql;
//print($sql);

    $adata=array();
    $res=mysql_query($sql);
    while ($data=mysql_fetch_array($res)) {

// $groups=preg_split('/,/',$data['Groups']);
//      $stores=preg_split('/,/',$data['Stores']);
//     $warehouses=preg_split('/,/',$data['Warehouses']);

        //   $_id=$myconf['staff_prefix'].sprintf('%03d',$data['Staff Key']);
        //  $id=sprintf('<a href="staff.php?id=%d">%s</a>',$data['Staff Key'],$_id);
        $is_active='No';

        if ($data['User Active']=='Yes')
            $is_active='Yes';

//$password='';
//  if ($data['User Key']){
//  $password='<img style="cursor:pointer" user_name="'.$data['User Alias'].'" user_id="'.$data['User Key'].'" onClick="change_passwd(this)" src="art/icons/key.png"/>';
// }
        $alias=sprintf('<a href="customer_user.php?id=%d&site_key=%d">%s</a>',$data['User Key'],$data['User Site Key'],$data['User Handle']);
        $customer=sprintf('%s (<a href="customer.php?id=%d">%5d</a>)',$data['Customer Name'],$data['User Parent Key'],$data['User Parent Key']);

        $adata[]=array(
                     'id'=>$data['User Key'],
                     'customer_id'=>$data['Customer Key'],
                     'alias'=>$data['User Handle'],
                     'name'=>$customer,
                     'login'=>$data['User Last Login'],
                     'count'=>$data['User Login Count'],
                     'isactive'=>$is_active
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

function forgot_password() {
    $user = new User(43);
    $user->forgot_password();
}

?>
