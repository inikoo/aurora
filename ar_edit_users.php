<?php
/*
 File: ar_users.php

 Ajax Server Anchor for the User Class


 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
require_once 'common_detect_agent.php';
require_once 'common.php';
require_once 'class.User.php';
require_once 'class.Staff.php';
require_once 'ar_edit_common.php';
require_once 'class.User.php';
require_once 'class.Site.php';
require_once 'class.SendEmail.php';

$editor=array(
            'User Key'=>$user->id
        );



if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case('staff_users'):
    list_staff_users();
    break;
case('supplier_users'):
    list_supplier_users();
    break;
case('customer_users'):
    list_customer_users();
    break;

case('valid_handle'):

    if (!isset($_REQUEST['handle'])) {
        $response=array('state'=>400,'msg'=>'Error');
        echo json_encode($response);
        return;
    }

    if (strlen($_REQUEST['handle'])==0) {
        $response=array('state'=>400,'msg'=>'No handle set');
        echo json_encode($response);
        return;
    }


    if (strlen($_REQUEST['handle'])<4) {
        $response=array('state'=>400,'msg'=>'Handle should have at least 4 characters');
        echo json_encode($response);
        return;
    }


    $user=new User('handle',$_REQUEST['handle']);
    if ($user->id)
        $response=array('state'=>400,'msg'=>_('Handle already in use'));
    else
        $response=array('state'=>200,'exists'=>0);

    echo json_encode($response);



    break;
case('change_passwd'):
    change_user_passwd();



    break;
case('edit_staff_user'):


    edit_staff_user();



    break;

case('create_user'):
    $data=prepare_values($_REQUEST,array(
                             'values'=>array('type'=>'json array')
                         ));
	create_user($data);
	break;
case ('add_user'):

    $data=array(
              'handle'=>$_REQUEST['handle'],
              'passwd'=>$_REQUEST['passwd'],
              'tipo'=>$_REQUEST['tipo_user'],
          );
    // print_r($data);

    switch ($data['tipo']) {
    case 1:
        $data['id_in_table']=$_REQUEST['id_in_table'];
        $staff=new Staff($data['id_in_table']);
        $data['name']=$staff->get('First Name');
        $data['surname']=$staff->get('Surname');
        $data['isactive']=1;
        $data['email']=$staff->get('Email');
        break;
    case 4:
        $data['name']=$_REQUEST['name'];
        $data['surname']=$_REQUEST['surname'];
        $data['isactive']=$_REQUEST['isactive'];
        $data['email']=$_REQUEST['email'];
        $data['groups']=preg_replace('/,%/','',$_REQUEST['groups']);

        break;
    }


    $user=new User('new',$data);

    if ($user->new) {
        $response= array('state'=>200);
    } else
        $response=array('state'=>400,'msg'=>'ss'.$user->msg);

    echo json_encode($response);
    break;

case('updateone'):

    $key=$_REQUEST['key'];
    switch ($key) {
    case('active'):
        $sql=sprintf("update liveuser_users set isactive=%d where authuserid=%d",$_REQUEST['value'],$_REQUEST['id'] );
        mysql_query($sql);
        $response=array('state'=>200);
        echo json_encode($response);
        break;

    case('password'):
        $password= mysql_real_escape_string($_REQUEST['value']);
        $sql=sprintf("update liveuser_users set passwd='%s' where authuserid=%d",$password,$_REQUEST['id'] );
        mysql_query($sql);
        $response=array('state'=>200,'newvalue'=>'******');
        echo json_encode($response);
        break;
    case('groups'):
        $groups=split(",",$_REQUEST['value']);


        $sql=sprintf("select perm_user_id  from liveuser_perm_users where auth_user_id=%d",$_REQUEST['id']);
        $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
        if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $puser_id=$row['perm_user_id'];
        }

        $sql=sprintf("delete from  liveuser_groupusers where perm_user_id=%d",$puser_id);
        mysql_query($sql);

        foreach($groups as $group) {
            $group=preg_replace('/(.*?)id=/','',$group);
            $group=preg_replace('/>(.*?)$/','',$group);
            $group=str_replace('"','' ,$group);
            $group=str_replace('\\','',$group);
            $group=str_replace(' ','' ,$group);
            $group=str_replace('/','' ,$group);
            if (is_numeric($group)) {



                $sql=sprintf("insert into liveuser_groupusers (perm_user_id,group_id) values (%d,%d)",$puser_id,$group);
                mysql_query($sql);
            }

        }






        $sql="select g.group_id as id, g.name ,ifnull(group_concat(distinct handle order by handle separator ', '),'') as users from liveuser_groups as g left join liveuser_groupusers as gu on (g.group_id=gu.group_id) left join liveuser_perm_users as pu   on (gu.perm_user_id=pu.perm_user_id  ) left join liveuser_users as u on (u.authuserid=pu.auth_user_id)  group by g.group_id  order by id      ";

        $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
        $gdata=array();
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $gdata[]=array(
                         'name'=>$_group[$row['id']],
                         'id'=>$row['id'],
                         'users'=>$row['users']
                     );
        }
        $response=array('state'=>200,'gdata'=>$gdata);
        echo json_encode($response);

        break;
    case('email'):
        $email=mysql_real_escape_string($_REQUEST['value']);
        $sql=sprintf("update liveuser_users set email='%s' where authuserid=%d",$email,$_REQUEST['id']);
        mysql_query($sql);
        $response=array('state'=>200,'newvalue'=>$_REQUEST['value']);
        echo json_encode($response);
        break;
    case('name'):
        $name='NULL';
        $surname='NULL';

        // $names=str_replace("\'","'",$_REQUEST['value']);
        $names=mysql_real_escape_string($_REQUEST['value']);
        $names=split(" ",$names);
        if (count($names)==1)
            $name="'".$names[0]."'";

        else {
            $name="'".array_shift($names)."'";
            $surname="'".join(" ",$names)."'";
        }

        $sql=sprintf("update liveuser_users set name=%s , surname=%s  where authuserid=%d",$name,$surname,$_REQUEST['id']);

        mysql_query($sql);
        $response=array('state'=>200,'data'=>array('newvalue'=>$_REQUEST['value']));
        echo json_encode($response);
        break;
    case('lang'):
        $lang=$_REQUEST['value'];
        $lang=preg_replace('/(.*?)langid=/','',$lang);
        $lang=preg_replace('/>(.*?)$/','',$lang);
        $lang=str_replace('"','',$lang);
        $lang=str_replace('\\','',$lang);
        $lang=str_replace(' ','',$lang);
        $lang=str_replace('/','',$lang);


        if (is_numeric($lang)) {
            $sql=sprintf("update liveuser_users set lang_id=%d  where authuserid=%d",$lang,$_REQUEST['id']);
            mysql_query($sql);
            $response=array('state'=>200,'data'=>array('newvalue'=>$_REQUEST['value']));
        } else
            $response=array('state'=>$lang);

        echo  json_encode($response);
        break;
    case('delete'):

        if ($_REQUEST['value']==1) {
            $sql=sprintf("select perm_user_id  from liveuser_perm_users where auth_user_id=%d",$_REQUEST['id']);
            $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
            if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                $puser_id=$row['perm_user_id'];
            }


            $sql=sprintf("delete from liveuser_users where authuserid=%d",$_REQUEST['id']);
            mysql_query($sql);
            $sql=sprintf("delete from liveuser_perm_users where perm_user_id=%d",$puser_id);
            mysql_query($sql);
            $sql=sprintf("delete from liveuser_userrights where perm_user_id=%d",$puser_id);
            mysql_query($sql);
            $sql=sprintf("delete from liveuser_group_users where perm_user_id=%d",$puser_id);
            mysql_query($sql);



            $response=array('state'=>200);
            echo json_encode($response);
        }
        break;
    default:
        $response=array('state'=>404,'msg'=>_('Sub-operation not found'));
        echo json_encode($response);
    }

}

function list_users() {

    $conf=$_SESSION['state']['users']['user_list'];
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


    $_SESSION['state']['users']['user_list']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);



    $filter_msg='';
    $wheref='';
    if ($f_field=='handle' and $f_value!='')
        $wheref.=" and  `User Handle` like '".addslashes($f_value)."%'";
    elseif($f_field=='name' and $f_value!='')
    $wheref.=" and  `User Alias` like '%".addslashes($f_value)."%'";

    $sql="select count(*) as total from `User Dimension`  $where $wheref   ";

    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($res);
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `Product Dimension`  $where   ";
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




    $adata=array();
    $sql="Select *,(select GROUP_CONCAT(UGUD.`User Group Key`) from `User Group User Bridge` UGUD left join  `User Group Dimension` UGD on (UGUD.`User Group Key`=UGD.`User Group Key`)      where UGUD.`User Key`=U.`User Key`   ) as Groups  from `User Dimension` U  $where $wheref   order by $order $order_direction limit $start_from,$number_results;";
    //   print $sql;
    $res=mysql_query($sql);

    while ($row=mysql_fetch_array($res)) {



        $groups=preg_split('/,/',$row['Groups']);


        $adata[]=array(
                     'handle'=>$row['User Handle'],
                     'tipo'=>$row['User Type'],
                     'id'=>$row['User Key'],
                     'name'=>$row['User Alias'],
                     'email'=>$row['User Email'],
                     'lang'=>$row['User Language Code'],
                     'groups'=>$groups,
                     'password'=>'<img style="cursor:pointer" user_name="'.$row['User Alias'].'" user_id="'.$row['User Key'].'" onClick="change_passwd(this)" src="art/icons/key.png"/>'.($row['User Email']!=''?'<img src="art/icons/key_go.png"/>':''),
                     'passwordmail'=>($row['User Email']!=''?'<img src="art/icons/key_go.png"/>':''),
                     'isactive'=>$row['User Active'],
                     'delete'=>'<img src="art/icons/status_busy.png"/>'
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


function edit_staff_user() {
    global $editor;
    $user=new User($_REQUEST['user_id']);


    $user->editor=$editor;



    if ($user->id) {
        $data=array(
                  'value'=>$_REQUEST['newvalue'],
              );

//print $_REQUEST['key'].':::: '.print_r($data).'<-';
        $user->update($_REQUEST['key'],$data);




        if ($user->updated)
            $response=array('state'=>200,'data'=>$user->new_value,'new'=>false);
        else
            $response=array('state'=>400,'msg'=>$user->msg);
    } else {

        if ($_REQUEST['key']=='isactive' and $_REQUEST['newvalue']=='Yes') {
            $staff=new Staff($_REQUEST['staff_id']);

            $password=generatePassword(8,10);
            $user_data=array(
                           'User Handle'=>$staff->data['Staff Alias'],
                                                      'User Alias'=>$staff->data['Staff Name'],

                           'User Password'=>hash('sha256','inikoo1'),
                           'User Active'=>'Yes',
                           'User Type'=>'Staff',

                           'User Parent Key'=>$staff->id,

                       );
          //  print_r($user_data);
            $user= new User('find',$user_data,'create');
            if($user->id){
            $new_data=array(
            'user_id'=>$user->id,
            'handle'=>$user->data['User Handle'],
            'password'=>$password,
            'td_password'=>  $password='<img style="cursor:pointer" user_name="'.$user->data['User Alias'].'" user_id="'.$user->data['User Key'].'" onClick="change_passwd(this)" src="art/icons/key.png"/>'
    
            );
            $response=array('state'=>200,'data'=>'Yes','new'=>'Yes','new_data'=>$new_data);
             }else{
                         $response=array('state'=>400,'msg'=>$user->msg);

             }
     
     } else {


            $response=array('state'=>400,'msg'=>_("User don't exist"));
        }
    }
                echo json_encode($response);

    
}

function change_user_passwd() {


	$id=$_REQUEST['user_id'];

    $user=new User($id);
	
	if(isset($_REQUEST['value']))
		$value=$_REQUEST['value'];
	else{
		$_key=md5($user->id.'insecure_key'.$_REQUEST['ep2']);
		//$value=AESDecryptCtr($_REQUEST['ep1'], $_key ,256);
		$value=$_REQUEST['ep1'];
	}
    if ($user->id) {
        $user->change_password($value);
        if (!$user->error)
            $response=array('state'=>200);
        else
            $response=array('state'=>400,'msg'=>$user->msg);


    } else
        $response=array('state'=>400,'msg'=>_("User don't exist"));
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
    if (isset( $_REQUEST['where']))
        $where=$_REQUEST['where'];
    else
        $where=$conf['where'];

    if (isset( $_REQUEST['display']))
        $display=$_REQUEST['display'];
    else
        $display=$conf['display'];




    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;

    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    $_order=$order;
    $_dir=$order_direction;



$_SESSION['state']['users']['staff']['display']=$display;
$_SESSION['state']['users']['staff']['order']=$order;
    $_SESSION['state']['users']['staff']['order_dir']=$order_direction;
    $_SESSION['state']['users']['staff']['nr']=$number_results;
    $_SESSION['state']['users']['staff']['sf']=$start_from;
    $_SESSION['state']['users']['staff']['where']=$where;
    $_SESSION['state']['users']['staff']['f_field']=$f_field;
    $_SESSION['state']['users']['staff']['f_value']=$f_value;

    $wheref='';
    if ($f_field=='name' and $f_value!=''  )
        $wheref.=" and  name like '%".addslashes($f_value)."%'    ";
    else if ($f_field=='position_id' or $f_field=='area_id'   and is_numeric($f_value) )
        $wheref.=sprintf(" and  $f_field=%d ",$f_value);


    switch ($display) {
    case('all'):
        break;
    case('active'):
        $where.=" and `User Key` IS NOT NULL  ";
        break;
    case('inactive_current'):
        $where.=" and `Staff Currently Working`='Yes'   and `User Key` IS NULL ";
        break;
    case('inactive_ex'):
        $where.=" and `Staff Currently Working`='No'";
        break;

    }

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
   $rtext=$total_records." ".ngettext('user','users',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=_('(Showing all)');

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


    if ($order=='name')
        $order='`Staff Name`';
    elseif($order=='position')
    $order='position';
    else
        $order='`Staff Name`';
    $sql="select (select GROUP_CONCAT(distinct `Company Position Title`) from `Company Position Staff Bridge` PSB  left join `Company Position Dimension` P on (`Company Position Key`=`Position Key`) where PSB.`Staff Key`= SD.`Staff Key`) as position, `Staff Alias`,`Staff Key`,`Staff Name` from `Staff Dimension` SD  left join `User Dimension` on (`User Parent Key`=`Staff Key`) $where  $wheref and `User Type`='Staff' order by $order $order_direction limit $start_from,$number_results";

    $sql="select `User Alias`,(select GROUP_CONCAT(URSB.`Scope Key`) from `User Right Scope Bridge` URSB where URSB.`User Key`=U.`User Key` and `Scope`='Store'  ) as Stores,(select GROUP_CONCAT(URSB.`Scope Key`) from `User Right Scope Bridge` URSB where URSB.`User Key`=U.`User Key`and `Scope`='Warehouse'  ) as Warehouses ,(select GROUP_CONCAT(UGUD.`User Group Key`) from `User Group User Bridge` UGUD left join  `User Group Dimension` UGD on (UGUD.`User Group Key`=UGD.`User Group Key`)      where UGUD.`User Key`=U.`User Key` ) as Groups,`User Key`,`User Active`, `Staff Alias`,`Staff Key`,`Staff Name` from `Staff Dimension` SD  left join `User Dimension` U on (`User Parent Key`=`Staff Key`) $where  $wheref and (`User Type`='Staff' or `User Type` is null ) order by $order $order_direction limit $start_from,$number_results";
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
  if ($data['User Key']){
  $password='<img style="cursor:pointer" user_name="'.$data['User Alias'].'" user_id="'.$data['User Key'].'" onClick="change_passwd(this)" src="art/icons/key.png"/>';
  }

        $adata[]=array(
                     'id'=>$data['User Key'],
                     'staff_id'=>$data['Staff Key'],
                     'alias'=>$data['Staff Alias'],
                     'name'=>$data['Staff Name'],
                     		   'password'=>$password,

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

 /*   if (isset( $_REQUEST['display']))
        $display=$_REQUEST['display'];
    else
        $display=$conf['display'];
*/



    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;

    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    $_order=$order;
    $_dir=$order_direction;



    $_SESSION['state']['users']['supplier']=array(
                                           //  'display'=>$display,
                                             'order'=>$order,
                                             'order_dir'=>$order_direction,
                                             'nr'=>$number_results,
                                             'sf'=>$start_from,
                                             'where'=>$where,
                                             'f_field'=>$f_field,
                                             'f_value'=>$f_value
                                             );



    $wheref='';
    if ($f_field=='name' and $f_value!=''  )
        $wheref.=" and  name like '%".addslashes($f_value)."%'    ";
    else if ($f_field=='position_id' or $f_field=='area_id'   and is_numeric($f_value) )
        $wheref.=sprintf(" and  $f_field=%d ",$f_value);


 /*   switch ($display) {
    case('all'):
        break;
    case('active'):
        $where.=" and `User Key` IS NOT NULL  ";
        break;
    case('inactive_current'):
        $where.=" and `Staff Currently Working`='Yes'   and `User Key` IS NULL ";
        break;
    case('inactive_ex'):
        $where.=" and `Staff Currently Working`='No'";
        break;

    }
*/
    $sql="select count(*) as total from `Supplier Dimension` SD  left join `User Dimension` on (`User Parent Key`=`Supplier Key`) $where $wheref and  `User Type`='Supplier' ";
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


    if ($order=='name')
        $order='`Supplier Name`';
    elseif($order=='position')
    $order='position';
    else
        $order='`Supplier Name`';
 //   $sql="select (select GROUP_CONCAT(distinct `Company Position Title`) from `Company Position Staff Bridge` PSB  left join `Company Position Dimension` P on (`Company Position Key`=`Position Key`) where PSB.`Staff Key`= SD.`Staff Key`) as position, `Staff Alias`,`Staff Key`,`Staff Name` from `Staff Dimension` SD  left join `User Dimension` on (`User Parent Key`=`Staff Key`) $where  $wheref and `User Type`='Staff' order by $order $order_direction limit $start_from,$number_results";

    $sql="select `User Alias`,`User Active`,`User Key`,`User Handle`,`User Password`,`Supplier Key`,`Supplier Name` from `Supplier Dimension` SD  left join `User Dimension` U on (`User Parent Key`=`Supplier Key`) $where  $wheref and (`User Type`='Supplier') order by $order $order_direction limit $start_from,$number_results";
   //  print $sql;
    $adata=array();
    $res=mysql_query($sql);
    while ($data=mysql_fetch_array($res)) {

 //$groups=preg_split('/,/',$data['Groups']);
     // $stores=preg_split('/,/',$data['Stores']);
    // $warehouses=preg_split('/,/',$data['Warehouses']);

        //   $_id=$myconf['staff_prefix'].sprintf('%03d',$data['Staff Key']);
        //  $id=sprintf('<a href="staff.php?id=%d">%s</a>',$data['Staff Key'],$_id);
        $is_active='No';

        if ($data['User Active']=='Yes')
            $is_active='Yes';

$password='';
  if ($data['User Key']){
  $password='<img style="cursor:pointer" user_name="'.$data['User Alias'].'" user_id="'.$data['User Key'].'" onClick="change_passwd(this)" src="art/icons/key.png"/>';
  }

        $adata[]=array(
                     'id'=>$data['User Key'],
                     'supplier_id'=>$data['Supplier Key'],
                     'alias'=>$data['User Alias'],
                     'name'=>$data['Supplier Name'],
                     		   'password'=>$password,

		    //  'groups'=>$groups,
                    //  'stores'=>$stores,
		    // 'warehouses'=>$warehouses,
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

 /*   if (isset( $_REQUEST['display']))
        $display=$_REQUEST['display'];
    else
        $display=$conf['display'];
*/



    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;

    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    $_order=$order;
    $_dir=$order_direction;



    $_SESSION['state']['users']['customer']=array(
                                           //  'display'=>$display,
                                             'order'=>$order,
                                             'order_dir'=>$order_direction,
                                             'nr'=>$number_results,
                                             'sf'=>$start_from,
                                             'where'=>$where,
                                             'f_field'=>$f_field,
                                             'f_value'=>$f_value
                                             );



    $wheref='';
    if ($f_field=='name' and $f_value!=''  )
        $wheref.=" and  name like '%".addslashes($f_value)."%'    ";
    else if ($f_field=='position_id' or $f_field=='area_id'   and is_numeric($f_value) )
        $wheref.=sprintf(" and  $f_field=%d ",$f_value);


 /*   switch ($display) {
    case('all'):
        break;
    case('active'):
        $where.=" and `User Key` IS NOT NULL  ";
        break;
    case('inactive_current'):
        $where.=" and `Staff Currently Working`='Yes'   and `User Key` IS NULL ";
        break;
    case('inactive_ex'):
        $where.=" and `Staff Currently Working`='No'";
        break;

    }
*/
    $sql="select count(*) as total from `Customer Dimension` SD  left join `User Dimension` on (`User Parent Key`=`Customer Key`) $where $wheref and  `User Type`='Customer' ";
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


    if ($order=='name')
        $order='`Customer Name`';
    elseif($order=='position')
    $order='position';
    else
        $order='`Customer Name`';
 //   $sql="select (select GROUP_CONCAT(distinct `Company Position Title`) from `Company Position Staff Bridge` PSB  left join `Company Position Dimension` P on (`Company Position Key`=`Position Key`) where PSB.`Staff Key`= SD.`Staff Key`) as position, `Staff Alias`,`Staff Key`,`Staff Name` from `Staff Dimension` SD  left join `User Dimension` on (`User Parent Key`=`Staff Key`) $where  $wheref and `User Type`='Staff' order by $order $order_direction limit $start_from,$number_results";

    $sql="select `User Alias`,`User Active`,`User Key`,`User Handle`,`User Password`,`Customer Key`,`Customer Name` from `Customer Dimension` SD  left join `User Dimension` U on (`User Parent Key`=`Customer Key`) $where  $wheref and (`User Type`='Customer') order by $order $order_direction limit $start_from,$number_results";
   //  print $sql;
    $adata=array();
    $res=mysql_query($sql);
    while ($data=mysql_fetch_array($res)) {

 //$groups=preg_split('/,/',$data['Groups']);
     // $stores=preg_split('/,/',$data['Stores']);
    // $warehouses=preg_split('/,/',$data['Warehouses']);

        //   $_id=$myconf['staff_prefix'].sprintf('%03d',$data['Staff Key']);
        //  $id=sprintf('<a href="staff.php?id=%d">%s</a>',$data['Staff Key'],$_id);
        $is_active='No';

        if ($data['User Active']=='Yes')
            $is_active='Yes';

$password='';
  if ($data['User Key']){
  $password='<img style="cursor:pointer" user_name="'.$data['User Alias'].'" user_id="'.$data['User Key'].'" onClick="change_passwd(this)" src="art/icons/key.png"/>';
  }

        $adata[]=array(
                     'id'=>$data['User Key'],
                     'customer_id'=>$data['Customer Key'],
                     'alias'=>$data['User Alias'],
                     'name'=>$data['Customer Name'],
                     		   'password'=>$password,

		    //  'groups'=>$groups,
                    //  'stores'=>$stores,
		    // 'warehouses'=>$warehouses,
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

function create_user($data){


	$val=$data['values'];
	
    include_once('class.User.php');

	$customer_key=$val['customer_id'];
	$handle=$val['Email'];
	$site_key=$val['store_id'];
	$password=generate_password(10,10);
	$send_email=$val['send_email'];
	$url=$val['url'];
	
	$store=new store($site_key);
	$site=new site($site_key);
	
	
    $sql=sprintf("select `Customer Store Key`,`Customer Name` from `Customer Dimension` where `Customer Key`=%d",
                 $customer_key);
    $res=mysql_query($sql);
    if ($row=mysql_fetch_assoc($res)) {



        $data=array(
                  'User Handle'=>$handle,
                  'User Type'=>'Customer',
                  'User Password'=>$password,
                  'User Parent Key'=>$row['Customer Store Key'],
                  'User Site Key'=>$site_key,
                  'User Active'=>'Yes',
                  'User Alias'=>$row['Customer Name'],
                  'User Parent Key'=>$customer_key
              );

        $user=new User('new',$data);
        if (!$user->id) {
            $response=array(
							'state'=>400,
							'msg'=>'User not Created');
			
			echo json_encode($response);

        } else {


			$email_credential_key=$store->get_email_credential_key('Site Registration');

            $welcome_email_subject="Thank you for your registration with ".$site->data['Site Name'];
            $welcome_email_plain="Thank you for your registration with ".$site->data['Site Name']."\nYou will now be able to see our wholesale prices and order from our big range of products.\n";
            $welcome_email_html="Thank you for your registration with ".$site->data['Site Name']."<br/>You will now be able to see our wholesale prices and order from our big range of products<br/>";

			$email_mailing_list_key=0;//$row2['Email Campaign Mailing List Key'];
	//$message_data=$email_campaign->get_message_data($email_mailing_list_key);
   
        $message_data['method']='smtp';
		$message_data['type']='html';
		$message_data['to']=$handle;
		$message_data['subject']=$welcome_email_subject;
		$message_data['html']=$welcome_email_html;
        $message_data['email_credentials_key']=1;
        $message_data['email_matter']='Registration';
        $message_data['email_matter_key']=$email_mailing_list_key;
		$message_data['email_matter_parent_key']=$email_mailing_list_key;
        $message_data['recipient_type']='User';
        $message_data['recipient_key']=0;
        $message_data['email_key']=0;
		$message_data['plain']=$welcome_email_plain;
		if(isset($message_data['plain']) && $message_data['plain']){
			$message_data['plain']=$message_data['plain'];
		}
		else
			$message_data['plain']=null;

	 //print_r($message_data);
	$send_email=new SendEmail();

	$send_email->track=true;


	$send_result=$send_email->send($message_data);	
	print_r($send_result);exit;		
/*
			$data=array('email_type'=>'Registration',
				  'recipient_type'=>'User',
				  'recipient_key'=>$user->id);
			$send_email=new SendEmail($data);
			$welcome_email_html=$send_email->track_sent_email($welcome_email_html);
			
			$data=array(
                
                  'subject'=>$welcome_email_subject,
                  'plain'=>$welcome_email_plain,
                  'email_credentials_key'=>$email_credential_key,
                  'to'=>$handle,
                  'html'=>$welcome_email_html,

              );

			if($send_email){
			//$send_email=new SendEmail();
			$send_email->smtp('HTML', $data);
			$result=$send_email->send();
			}
*/
			$_val=array('customer_key'=>$customer_key,
				'store_key'=>$site_key,
				'url'=>$url,
				'site_key'=>$site->id,
				'store_key'=>$store->id,
				'email'=>$handle
				);
			$response=forgot_password($_val);
			/*
			$response=array(
				'state'=>200,
				'user_key'=>$user->id,
				'msg'=>'Email Sent');
				*/
			echo json_encode($response);
        }
	} else {
			$response=array(
				'state'=>400,
				'msg'=>'Customer not Found');
			echo json_encode($response);
	}
}


function forgot_password($data){
	//print_r($data);
	global $secret_key,$public_url;
	$key=$data['customer_key'];
	$store_key=$data['store_key'];
	$site_key=$data['site_key'];
	$url=$data['url'];
	$login_handle=$data['email'];
	
	$sql=sprintf("select `User Key`, `User Handle` from `User Dimension` where `User Handle` = '%s'", $login_handle);
	//print $sql;
	$result=mysql_query($sql);
	if($row=mysql_fetch_array($result));
	$user_key=$row['User Key'];
	//$login_handle=$row['User Handle'];
	//print $user_key;
	if ($user_key) {


        $user=new User($user_key);
        $customer=new Customer($user->data['User Parent Key']);

		$store=new Store($store_key);

        $email_credential_key=$store->get_email_credential_key('Site Registration');

		//print $email_credential_key=1;
//print_r($store);
        $signature_name='';
        $signature_company='';

        $master_key=$user_key.'x'.generatePassword(6,10);




        $sql=sprintf("insert into `MasterKey Dimension` (`Key`,`User Key`,`Valid Until`,`IP`) values (%s,%d,%s,%s) ",
                     prepare_mysql($master_key),
                     $user_key,
                     prepare_mysql(date("Y-m-d H:i:s",strtotime("now +24 hours"))),
                     prepare_mysql(ip())
                    );

        mysql_query($sql);





        $encrypted_secret_data=base64_encode(AESEncryptCtr($master_key,$secret_key,256));


        $plain_message=$customer->get_greetings()."\n\n We received request to reset the password associated with this email account.\n\nIf you did not request to have your password reset, you can safely ignore this email. We assure that yor customer account is safe.\n\nCopy and paste the following link to your browser's address window.\n\n ".$url."?p=".$encrypted_secret_data."\n\n Once you hace returned our page you will be asked to choose a new password\n\nThank you \n\n".$signature_name."\n".$signature_company;


        $html_message=$customer->get_greetings()."<br/>We received request to reset the password associated with this email account.<br><br>
                      If you did not request to have your password reset, you can safely ignore this email. We assure that yor customer account is safe.<br><br>
                      <b>Click the link below to reset your password</b>
                      <br><br>
                      <a href=\"".$url."?p=".$encrypted_secret_data."\">".$url."?p=".$encrypted_secret_data."</a>
                      <br></br>
                      If clicking the link doesn't work you can copy and paste it into your browser's address window. Once you have returned to our website, you will be asked to choose a new password.
                      <br><br>
                      Thank you";

$files=array();	

/*
		$data=array('email_type'=>'Password Reminder',
				  'recipient_type'=>'User',
				  'recipient_key'=>$user_key);
				  
		$send_email=new SendEmail($data);
		
		//$html_message=$send_email->track_sent_email($html_message);
		
        $to=$login_handle;
        $data=array(
				  'type'=>'HTML',
                  'subject'=>'Reset your password',
                  'plain'=>$plain_message,
                  'email_credentials_key'=>$email_credential_key,
                  'to'=>$to,
                  'html'=>$html_message,
					'attachement'=>$files
              );
		if(isset($data['plain']) && $data['plain']){
			$data['plain']=$data['plain'];
		}
		else
			$data['plain']=null;
			
        //$send_email=new SendEmail();
        $send_email->smtp('plain', $data);
        $result=$send_email->send();
*/

			$email_mailing_list_key=0;//$row2['Email Campaign Mailing List Key'];
	//$message_data=$email_campaign->get_message_data($email_mailing_list_key);
   
        $message_data['method']='smtp';
		$message_data['type']='html';
		$message_data['to']=$login_handle;
		$message_data['subject']='Reset your password';
		$message_data['html']=$html_message;
        $message_data['email_credentials_key']=$email_credential_key;
        $message_data['email_matter']='Forgot Password';
        $message_data['email_matter_key']=$email_mailing_list_key;
        $message_data['recipient_type']='User';
        $message_data['recipient_key']=0;
        $message_data['email_key']=0;
		$message_data['plain']=$plain_message;
		if(isset($message_data['plain']) && $message_data['plain']){
			$message_data['plain']=$message_data['plain'];
		}
		else
			$message_data['plain']=null;

	 //print_r($message_data);
	$send_email=new SendEmail();

	$send_email->track=true;


	$send_result=$send_email->send($message_data);	
		//print_r($result);
		
        if ($send_result['msg']=='ok') {
            $response=array('state'=>200,'result'=>'send');
            return $response;

        } else {
            print_r($send_result);
            $response=array('state'=>200,'result'=>'error '.join(' ',$result));
            return $response;
        }


    } else {
        $response=array('state'=>200,'result'=>'handle_not_found');
        return $response;
    }
}

function generate_password($length=9, $strength=0) {
    $vowels = 'aeuy'.md5(mt_rand());
    $consonants = 'bdghjmnpqrstvz'.md5(mt_rand());
    if ($strength & 1) {
        $consonants .= 'BDGHJLMNPQRSTVWXZlkjhgfduytrdqwertyuipasdfghjkzxcvbnm';
    }
    if ($strength & 2) {
        $vowels .= "AEUI";
    }
    if ($strength & 4) {
        $consonants .= '2345678906789$%^&*(';
    }
    if ($strength & 8) {
        $consonants .= '!=/[]{}~\<>$%^&*()_+@#.,)(*%%';
    }

    $password = '';
    $alt = time() % 2;
    for ($i = 0; $i < $length; $i++) {
        if ($alt == 1) {
            $password .= $consonants[(mt_rand() % strlen($consonants))];
            $alt = 0;
        } else {
            $password .= $vowels[(mt_rand() % strlen($vowels))];
            $alt = 1;
        }
    }
    return $password;
}
?>
