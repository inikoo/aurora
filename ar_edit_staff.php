<?php
require_once 'class.Timer.php';

require_once 'common.php';
require_once 'class.Company.php';
require_once 'class.Supplier.php';
require_once 'ar_edit_common.php';
include_once('class.CompanyPosition.php');
include_once('class.CompanyArea.php');
if(!isset($_REQUEST['tipo']))
  {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
  }

$editor=array(
	      'User Key'=>$user->id
	      );



$tipo=$_REQUEST['tipo'];
switch($tipo){
case('edit_staff_pin'):
case('edit_staff_description'):
edit_staff_description();
break;
 case('staff'):
edit_staff();
   break;
   case('positions'):
edit_positions();
   break;
case('edit_company_staff'):
  edit_company_staff();
  break;
case('edit_company_area'):
  edit_company_area();
break;
case('edit_company_position'):
  edit_company_position();
  break;
case('list_members_of_staff_to_edit'):
    list_members_of_staff_to_edit();
    break;
case('list_department_staff'):
    list_department_staff();
    break;

case('edit_ind_staff'):
       $data=prepare_values($_REQUEST,array(
                             'key'=>array('type'=>'string'),
                             'newvalue'=>array('type'=>'string'),
                             'oldvalue'=>array('type'=>'string'),
                             'staff_key'=>array('type'=>'key')
                         ));
    edit_ind_staff($data);
    break;
case('edit_ind_positions'):
       $data=prepare_values($_REQUEST,array(
                             'key'=>array('type'=>'string'),
                             'newvalue'=>array('type'=>'string'),
                             'company_position_key'=>array('type'=>'key')
                         ));
    edit_ind_positions($data);
    break;
case('delete_ind_positions'):
    $data=prepare_values($_REQUEST,array(
                             'company_position_key'=>array('type'=>'key')
                                  ,'delete_type'=>array('type'=>'string')
                         ));
    delete_ind_positions($data);
    break;
case('edit_ind_department'):
    $data=prepare_values($_REQUEST,array('id'=>array('type'=>'key'),'newvalue' =>array('type'=>'string'),'key' =>array('type'=>'string_value')));
    edit_ind_department($data);
    break;
case('delete_ind_department'):
    $data=prepare_values($_REQUEST,array(
                             'id'=>array('type'=>'key')
                                  ,'delete_type'=>array('type'=>'string')
                         ));
    delete_ind_department($data);
    break;
case('create_staff'):
	$data=prepare_values($_REQUEST,array(
			'values'=>array('type'=>'json array')
		));
	create_staff($data);
	break;

 default:

   $response=array('state'=>404,'resp'=>_('Operation not found'));
   echo json_encode($response);
}

function create_staff($data){
	
$values=$data['values'];
//print_r($values);

    $position=new CompanyPosition('id',$values['Position Key']);
    if(!$position->id){
      print "$position_code\n";
      //print_r($position);
    exit;
    }

      $staff=$position->add_staff($values);

if($staff->new){
	$response=array('state'=>200, 'staff_id'=>$staff->id, 'action'=>'created_');
}
else{
	$response=array('state'=>400, 'msg'=>'Error');
}

  echo json_encode($response);
}

function edit_staff(){
  global $myconf;

$conf=$_SESSION['state']['hr']['staff'];
  if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$conf['sf'];
   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$conf['nr'];
  if(isset( $_REQUEST['o']))
    $order=$_REQUEST['o'];
  else
    $order=$conf['order'];
  if(isset( $_REQUEST['od']))
    $order_dir=$_REQUEST['od'];
  else
    $order_dir=$conf['order_dir'];
    if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];
  if(isset( $_REQUEST['where']))
     $where=$_REQUEST['where'];
   else
     $where=$conf['where'];
  
  if(isset( $_REQUEST['view']))
    $view=$_REQUEST['view'];
  else
    $view=$_SESSION['state']['hr']['view'];




   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;

 $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   $_order=$order;
   $_dir=$order_direction;



  $_SESSION['state']['hr']['staff']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
  $_SESSION['state']['hr']['view']=$view;


   $wheref='';
   if($f_field=='name' and $f_value!=''  )
     $wheref.=" and  name like '%".addslashes($f_value)."%'    ";
   else if($f_field=='position_id' or $f_field=='area_id'   and is_numeric($f_value) )
     $wheref.=sprintf(" and  $f_field=%d ",$f_value);
  
  
  switch($view){
   case('all'):
     break;
   case('staff'):
     $where.=" and `Staff Currently Working`='Yes'  ";
     break;
   case('exstaff'):
     $where.=" and `Staff Currently Working`='No' ";
     break;
  }

   $sql="select count(*) as total from `Staff Dimension` SD left join `Contact Dimension` CD on (`Contact Key`=`Staff Contact Key`) $where $wheref";
   

   $res=mysql_query($sql);
   if($row=mysql_fetch_array($res, MYSQL_ASSOC)){
     $total=$row['total'];
   }if($wheref!=''){
     $sql="select count(*) as total from `Staff Dimension` SD left join `Contact Dimension` CD on (`Contact Key`=`Staff Contact Key`)   $where ";
     $res=mysql_query($sql);
     if($row=mysql_fetch_array($res, MYSQL_ASSOC)){
       $total_records=$row['total'];
       $filtered=$row['total']-$total;
     }

   }else{
     $filtered=0;
     $total_records=$total;
   }
   
   mysql_free_result($res);
   $rtext=$total_records." ".ngettext('record','records',$total_records);
   if($total_records>$number_results)
     $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));
   $filter_msg='';
   
    switch($f_field){
     case('name'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff with name")." <b>*".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff with name')." <b>*".$f_value."*</b>)";
       break;
    case('area_id'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff on area")." <b>".$f_value."</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff on area')." <b>".$f_value."</b>)";
       break;
    case('position_id'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff with position")." <b>".$f_value."</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff with position')." <b>".$f_value."</b>)";
       break;

    }

if($order=='name')
  $order='`Staff Name`';

   $sql="select * from `Staff Dimension` SD left join `Contact Dimension` CD on (`Contact Key`=`Staff Contact Key`)  $where $wheref order by $order $order_direction limit $start_from,$number_results";

   $adata=array();
   $res=mysql_query($sql);
   while($data=mysql_fetch_array($res)){


     $_id=$myconf['staff_prefix'].sprintf('%03d',$data['Staff Key']);
     $id=sprintf('<a href="staff.php?id=%d">%s</a>',$data['Staff Key'],$_id);
     $adata[]=array(
		    'id'=>$id,
		    'alias'=>$data['Staff Alias'],
		    'name'=>$data['Staff Name'],
		    'department'=>$data['Staff Department Key'],
		    'area'=>$data['Staff Area Key'],
		    'position'=>$data['Staff Position Key']
		    
		    );
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
			 'records_returned'=>$start_from+$total,
			 'records_perpage'=>$number_results,
			 'rtext'=>$rtext,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}

// ------------------------------- edit_ind_staff function will be here -----------------------
function edit_ind_staff($data){
$staff=new Staff($data['staff_key']);


$translate_keys=array('id'=>'Staff ID','name'=>'Staff Name');

$staff->update(array($translate_keys[$data['key']]=>$data['newvalue']));
 if($staff->updated){
    $response=array('state'=>200,'action'=>'updated','key'=>$data['key'],'newvalue'=>$staff->new_value);
 }else{
     $response=array('state'=>200,'action'=>'nochange','key'=>$data['key'],'newvalue'=>$data['oldvalue']);
      }
 echo json_encode($response);
}
// --------------------------------------------------------------------------------------------

function edit_ind_positions($data){
$staff=new Staff($data['company_position_key']);
$staff->update(array($data['key']=>$data['newvalue']));
 if($staff->updated){
    $response=array('state'=>200,'action'=>'updated','key'=>$data['key'],'newvalue'=>$staff->new_value);
 }else{
     $response=array('state'=>200,'action'=>'nochange','key'=>$data['key'],'newvalue'=>$data['newvalue']);
      }
 echo json_encode($response);
}

function delete_ind_positions($data) {
    include_once('class.CompanyPosition.php');
    $companyposition=new CompanyPosition($data['company_position_key']);
    $companyposition->delete();
     if ($companyposition->deleted) {
        $action='deleted';
        $msg=_('Position deleted');

    } else {
        $action='nochage';
        $msg=_('Position could not be deleted');
    }
    $response=array('state'=>200,'action'=>$action);
    echo json_encode($response);
}



function edit_positions(){
  global $myconf;

$conf=$_SESSION['state']['hr']['positions'];
  if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$conf['sf'];
   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$conf['nr'];
  if(isset( $_REQUEST['o']))
    $order=$_REQUEST['o'];
  else
    $order=$conf['order'];
  if(isset( $_REQUEST['od']))
    $order_dir=$_REQUEST['od'];
  else
    $order_dir=$conf['order_dir'];
    if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];
  if(isset( $_REQUEST['where']))
     $where=$_REQUEST['where'];
   else
     $where=$conf['where'];
  
 



   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;

 $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   $_order=$order;
   $_dir=$order_direction;



  $_SESSION['state']['hr']['positions']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);


   $wheref='';
   if($f_field=='name' and $f_value!=''  )
     $wheref.=" and  name like '%".addslashes($f_value)."%'    ";
   else if($f_field=='position_id' or $f_field=='area_id'   and is_numeric($f_value) )
     $wheref.=sprintf(" and  $f_field=%d ",$f_value);
  
  
 

   $sql="select count(*) as total from `Company Position Dimension` $where $wheref";
   

   $res=mysql_query($sql);
   if($row=mysql_fetch_array($res, MYSQL_ASSOC)){
     $total=$row['total'];
   }if($wheref!=''){
     $sql="select count(*) as total from  `Company Position Dimension`  $where ";
     $res=mysql_query($sql);
     if($row=mysql_fetch_array($res, MYSQL_ASSOC)){
       $total_records=$row['total'];
       $filtered=$row['total']-$total;
     }

   }else{
     $filtered=0;
     $total_records=$total;
   }
   
   mysql_free_result($res);
   $rtext=$total_records." ".ngettext('record','records',$total_records);
   if($total_records>$number_results)
     $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));
   $filter_msg='';
   
    switch($f_field){
     case('name'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff with name")." <b>*".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff with name')." <b>*".$f_value."*</b>)";
       break;
    case('area_id'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff on area")." <b>".$f_value."</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff on area')." <b>".$f_value."</b>)";
       break;
    case('position_id'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff with position")." <b>".$f_value."</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff with position')." <b>".$f_value."</b>)";
       break;

    }
  $order='`Company Position Code`';

if($order=='name')
  $order='`Company Position Title`';

   $sql="select * from `Company Position Dimension`  $where $wheref order by $order $order_direction limit $start_from,$number_results";

   $adata=array();
   $res=mysql_query($sql);
   /*while($data=mysql_fetch_array($res)){
     $adata[]=array(
		    
		    'code'=>$data['Company Position Code'],
		    'name'=>$data['Company Position Title'],

		    );
  }*/
   while($data=mysql_fetch_array($res)){
 $delete='<img src="art/icons/delete.png"/>';

    
     $adata[]=array(
		    'company_position_key'=>$data['Company Position Key'],
		    'code'=>$data['Company Position Code'],
		    'name'=>$data['Company Position Title'],
		    'go'=>sprintf("<a href='edit_position.php?edit=1&id=%d'><img src='art/icons/page_go.png' alt='go'></a>",$data['Company Position Key'])
		    ,'delete'=>$delete
                    ,'delete_type'=>'delete'
		    
		    );
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
			 'records_returned'=>$start_from+$total,
			 'records_perpage'=>$number_results,
			 'rtext'=>$rtext,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
}
function list_members_of_staff_to_edit() {

    $conf=$_SESSION['state']['company_staff']['table'];
    if (isset( $_REQUEST['view']))
        $view=$_REQUEST['view'];
    else
        $view=$_SESSION['state']['company_staff']['view'];

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

    if (isset( $_REQUEST['parent']))
        $parent=$_REQUEST['parent'];
    else
        $parent=$conf['parent'];

    if (isset( $_REQUEST['mode']))
        $mode=$_REQUEST['mode'];
    else
        $mode=$conf['mode'];

    if (isset( $_REQUEST['restrictions']))
        $restrictions=$_REQUEST['restrictions'];
    else
        $restrictions=$conf['restrictions'];




    $_SESSION['state']['company_staff']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value,'mode'=>$mode,'restrictions'=>'','parent'=>$parent );



    if ($parent=='staff') {
        $where.=sprintf(' and `Staff Key`=%d',$_SESSION['state']['company_staff']['id']);
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
    if ($f_field=='staff name' and $f_value!='')
        $wheref.=" and  `Staff Name` like '%".addslashes($f_value)."%'";
    elseif($f_field=='email' and $f_value!='')
   // $wheref.=" and  `Company Main Plain Email` like '".addslashes($f_value)."%'";
    $wheref.="";
    $sql="select count(*) as total from `Staff Dimension`  $where $wheref   ";
//print $sql;
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `Staff Dimension`  $where   ";
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }

    }
    mysql_free_result($res);

   $rtext=$total_records." ".ngettext('company staff','company staff',$total_records);
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
    elseif($filtered>0) {
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
    $order='`Staff Name`';

    if ($order=='id')
        $order='`Staff Id`';
    elseif ($order=='name')
        $order='`Staff Name`';



    $sql="select  * from `Staff Dimension` P  $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";

    $res = mysql_query($sql);
    $adata=array();

    // print "$sql";
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $delete='<img src="art/icons/delete.png"/>';
       
         $adata[]=array(
               'staff_key'=>$row['Staff Key']
                          ,'go'=>sprintf("<a href='edit_each_staff.php?edit=1&id=%d'><img src='art/icons/page_go.png' alt='go'></a>",$row['Staff Key'])
                                ,'id'=>$row['Staff ID']
                                        ,'name'=>$row['Staff Name']
                                                ,'delete'=>$delete
                                                          ,'delete_type'=>'delete'
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

function list_department_staff() {

    $conf=$_SESSION['state']['company_departments']['table'];
    if (isset( $_REQUEST['parent'])) {
        $parent=$_REQUEST['parent'];
        $_SESSION['state']['company_departments']['parent']=$parent;
    } else
        $parent= $_SESSION['state']['company_departments']['parent'];

    if ($parent=='area') {
        $conf_table='company_area';

        $conf=$_SESSION['state']['hr']['departments'];

    } else {
        $conf_table='company_departments';
        $conf=$_SESSION['state'][$conf_table]['table'];
    }

    if (isset( $_REQUEST['view']))
        $view=$_REQUEST['view'];
    else
        $view=$_SESSION['state']['company_departments']['view'];

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
        $_SESSION['state']['hr']['departments']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value
                ,'restrictions'=>'','parent'=>$parent);
    } else {
        $_SESSION['state']['company_departments']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value
                ,'restrictions'=>'','parent'=>$parent
                                                                );
    }


    if ($parent=='area') {
        $where.=sprintf(' and `Company Area Key`=%d',$_SESSION['state']['company_area']['id']);
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
    elseif($f_field=='email' and $f_value!='')
    $wheref.=" and  `Company Main Plain Email` like '".addslashes($f_value)."%'";

    $sql="select count(*) as total from `Company Department Dimension`  $where $wheref   ";
//print $sql;
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `Company Department Dimension`  $where   ";
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }

    }
    mysql_free_result($res);

    $rtext=$total_records." ".ngettext('company department','company departments',$total_records);
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
    elseif($filtered>0) {
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



    $sql="select  * from `Company Department Dimension` P  $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";

    $res = mysql_query($sql);
    $adata=array();

    // print "$sql";
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


        if ($row['Company Department Number Employees']>0) {
            $delete='';
        } else {
            $delete='<img src="art/icons/delete.png"/>';
        }
        $adata[]=array(


                     'id'=>$row['Company Department Key']

                          ,'go'=>sprintf("<a href='edit_company_department.php?edit=1&id=%d'><img src='art/icons/page_go.png' alt='go'></a>",$row['Company Department Key'])

                                ,'code'=>$row['Company Department Code']
                                        ,'name'=>$row['Company Department Name']
                                              //  ,'area'=>$row['Company Area Key']
                                                        ,'delete'=>$delete
                                                                  ,'delete_type'=>'delete'


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

function edit_ind_department($data){
$staff=new Staff($data['id']);
$staff->update(array($data['key']=>$data['newvalue']));
 if($staff->updated){
    $response=array('state'=>200,'action'=>'updated','key'=>$data['key'],'newvalue'=>$staff->new_value);
 }else{
     $response=array('state'=>200,'action'=>'nochange','key'=>$data['key'],'newvalue'=>$data['newvalue']);
      }
 echo json_encode($response);
}
function delete_ind_department($data) {
    include_once('class.Staff.php');
    $staff=new Staff($data['id']);
    $staff->delete();
     if ($staff->deleted) {
        $action='deleted';
        $msg=_('Department deleted');

    } else {
        $action='nochage';
        $msg=_('Department could not be deleted');
    }
    $response=array('state'=>200,'action'=>$action);
    echo json_encode($response);
}


function edit_company_staff() {
  $key=$_REQUEST['key'];
 
  
  $staff=new Staff($_REQUEST['staff_key']);
  global $editor;
  $staff->editor=$editor;
  
  if($key=='Attach'){
    // print_r($_FILES);
    $note=stripslashes(urldecode($_REQUEST['newvalue']));
    $target_path = "uploads/".'attach_'.date('U');
    $original_name=$_FILES['testFile']['name'];
    $type=$_FILES['testFile']['type'];
    $data=array('Caption'=>$note,'Original Name'=>$original_name,'Type'=>$type);

    if(move_uploaded_file($_FILES['testFile']['tmp_name'],$target_path )) {
      $staff->add_attach($target_path,$data);
      
    }
  }else{
    

    
    $key_dic=array(
		   'name'=>'Staff Name'
		   ,'id'=>'Staff ID'
		   ,'alias'=>'Staff Alias'
		  // ,'type'=>'Staff Type'
		  
		   
    );
    if(array_key_exists($_REQUEST['key'],$key_dic))
       $key=$key_dic[$_REQUEST['key']];
    
    $update_data=array($key=>stripslashes(urldecode($_REQUEST['newvalue'])));
    $staff->update($update_data);
  }


    if ($staff->updated) {
        $response= array('state'=>200,'newvalue'=>$staff->new_value,'key'=>$_REQUEST['key']);

    } else {
        $response= array('state'=>400,'msg'=>$staff->msg,'key'=>$_REQUEST['key']);
    }
    echo json_encode($response);

}
function edit_company_position() {
  $key=$_REQUEST['key'];
 
  
  $company_position=new CompanyPosition($_REQUEST['position_key']);
  global $editor;
  $company_position->editor=$editor;
  
  if($key=='Attach'){
    // print_r($_FILES);
    $note=stripslashes(urldecode($_REQUEST['newvalue']));
    $target_path = "uploads/".'attach_'.date('U');
    $original_name=$_FILES['testFile']['name'];
    $type=$_FILES['testFile']['type'];
    $data=array('Caption'=>$note,'Original Name'=>$original_name,'Type'=>$type);

    if(move_uploaded_file($_FILES['testFile']['tmp_name'],$target_path )) {
      $company_position->add_attach($target_path,$data);
      
    }
  }else{
    

    
    $key_dic=array(
		   'name'=>'Company Position Title'
		   ,'code'=>'Company Position Code'
		   ,'description'=>'Company Position Description'
		  // ,'type'=>'Staff Type'
		  
		   
    );
    if(array_key_exists($_REQUEST['key'],$key_dic))
       $key=$key_dic[$_REQUEST['key']];
    
    $update_data=array($key=>stripslashes(urldecode($_REQUEST['newvalue'])));
    $company_position->update($update_data);
  }


    if ($company_position->updated) {
        $response= array('state'=>200,'newvalue'=>$company_position->new_value,'key'=>$_REQUEST['key']);

    } else {
        $response= array('state'=>400,'msg'=>$company_position->msg,'key'=>$_REQUEST['key']);
    }
    echo json_encode($response);

}

function edit_company_area() {
  $key=$_REQUEST['key'];
 
  
  $company_area=new CompanyArea($_REQUEST['company_key']);
  global $editor;
  $company_area->editor=$editor;
  
  if($key=='Attach'){
    // print_r($_FILES);
    $note=stripslashes(urldecode($_REQUEST['newvalue']));
    $target_path = "uploads/".'attach_'.date('U');
    $original_name=$_FILES['testFile']['name'];
    $type=$_FILES['testFile']['type'];
    $data=array('Caption'=>$note,'Original Name'=>$original_name,'Type'=>$type);

    if(move_uploaded_file($_FILES['testFile']['tmp_name'],$target_path )) {
      $company_area->add_attach($target_path,$data);
      
    }
  }else{
    

    
    $key_dic=array(
		   'name'=>'Company Area Name'
		   ,'code'=>'Company Area Code'
		   ,'description'=>'Company Area Description'
		  // ,'type'=>'Staff Type'
		  
		   
    );
    if(array_key_exists($_REQUEST['key'],$key_dic))
       $key=$key_dic[$_REQUEST['key']];
    
    $update_data=array($key=>stripslashes(urldecode($_REQUEST['newvalue'])));
    $company_area->update($update_data);
  }


    if ($company_area->updated) {
        $response= array('state'=>200,'newvalue'=>$company_area->new_value,'key'=>$_REQUEST['key']);

    } else {
        $response= array('state'=>400,'msg'=>$company_area->msg,'key'=>$_REQUEST['key']);
    }
    echo json_encode($response);

}


function edit_staff_description(){
  global $editor;

   if(!isset($_REQUEST['staff_key'])
     or !isset($_REQUEST['key'])
     or !isset($_REQUEST['newvalue'])
     ){
    $response=array('state'=>400,'action'=>'error','msg'=>'');
    echo json_encode($response);
     return;
  }
  //print_r($editor);

  $staff_key=$_REQUEST['staff_key'];

  $new_value=stripslashes(urldecode($_REQUEST['newvalue']));
  $traslator=array(
		   'alias'=>'Staff Alias',
		'pin'=>'Staff PIN',
		'pin_confirm'=>'Staff PIN',
		'position_key'=>'Staff Position',
		'name'=>'Staff Name',
		'Staff Currently Working'=>'Staff Currently Working',
		'Staff Is Supervisor'=>'Staff Is Supervisor',
		'Staff Type'=>'Staff Type'

		   );
  if(array_key_exists($_REQUEST['key'],$traslator)){
    $key=$traslator[$_REQUEST['key']];
  }else{
    $response=array('state'=>400,'action'=>'error','msg'=>'Unknown key '.$_REQUEST['key']);
    echo json_encode($response);
    return;
  }    
  


  $staff=new Staff($staff_key);

 $staff->editor=$editor;

  $data=array($key=>$new_value);
  $staff->editor=$editor;
  $staff->update($data);
  
  if($staff->updated){
    $response=array('state'=>200,'action'=>'updated','newvalue'=>$staff->data[$key], 'msg'=>$staff->msg, 'key'=>$_REQUEST['key']);
     echo json_encode($response);
     return;
  }else{
    $response=array('state'=>400,'action'=>'nochange','msg'=>$staff->msg);
    echo json_encode($response);
     return;

  }
  


}

?>
