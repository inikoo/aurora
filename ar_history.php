<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
require_once 'common.php';
//require_once '_order.php';

//require_once '_contact.php';
require_once 'class.Customer.php';

require_once 'class.Timer.php';

require_once 'ar_common.php';



if (!isset($_REQUEST['tipo']))  {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case('history'):
    list_history($_REQUEST['type']);
    break;
case('indirect_history'):
    $data=prepare_values($_REQUEST,array(
                             'parent'=>array('type'=>'string')
                                      ,'parent_key'=>array('type'=>'key')
                                                    ,'scope'=>array('type'=>'string')
                         ));
    list_indirect_history($data);
    break;
case('history_details'):
    history_details();
    break;
    break;
case('customer_history'):
    list_customer_history();
    break;
case('staff_history'):
    list_staff_history();
    break;

default:
    $response=array('state'=>404,'resp'=>_('Operation not found'));
    echo json_encode($response);

}


function history_details() {
    if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id'])) {
        $sql=sprintf("select `History Details` as details from `History Dimension` where `History Key`=%d",$_REQUEST['id']);
        $res = mysql_query($sql);
        if ($data=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $response=array('state'=>200,'details'=>$data['details']);
            echo json_encode($response);
            return;
        }
        mysql_free_result($res);
    }
    $response=array('state'=>400,'msg'=>_("Can not get history details"));
    echo json_encode($response);
    return;
}


function list_customer_history() {

    $conf=$_SESSION['state']['customer']['table'];

    if (isset( $_REQUEST['customer_key'])) {
        $customer_id=$_REQUEST['customer_key'];
        $_SESSION['state']['customer']['id']=$customer_id;
    } else
        $customer_id=$_SESSION['state']['customer']['id'];

    if (!$customer_id)return;


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

    if (isset( $_REQUEST['details']))
        $details=$_REQUEST['details'];
    else
        $details=$conf['details'];


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

    if (isset( $_REQUEST['from']))
        $from=$_REQUEST['from'];
    else
        $from=$conf['from'];
    if (isset( $_REQUEST['to']))
        $to=$_REQUEST['to'];
    else
        $to=$conf['to'];
//ids=['elements_changes','elements_orders','elements_notes'];

    $elements=$conf['elements'];
    if (isset( $_REQUEST['elements_changes'])) {
        $elements['Changes']=$_REQUEST['elements_changes'];

    }
    if (isset( $_REQUEST['elements_orders'])) {
        $elements['Orders']=$_REQUEST['elements_orders'];
    }
    if (isset( $_REQUEST['elements_notes'])) {
        $elements['Notes']=$_REQUEST['elements_notes'];
    }
    if (isset( $_REQUEST['elements_attachments'])) {
        $elements['Attachments']=$_REQUEST['elements_attachments'];
    }
    if (isset( $_REQUEST['elements_emails'])) {
        $elements['Emails']=$_REQUEST['elements_emails'];
    }
    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;




    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    $_SESSION['state']['customer']['id']=$customer_id;

    $_SESSION['state']['customer']['table']['details']=$details;
    $_SESSION['state']['customer']['table']['elements']=$elements;
    $_SESSION['state']['customer']['table']['order']=$order;
    $_SESSION['state']['customer']['table']['order_dir']=$order_direction;
    $_SESSION['state']['customer']['table']['nr']=$number_results;
    $_SESSION['state']['customer']['table']['sf']=$start_from;
    $_SESSION['state']['customer']['table']['where']=$where;
    $_SESSION['state']['customer']['table']['f_field']=$f_field;
    $_SESSION['state']['customer']['table']['f_value']=$f_value;
    $_SESSION['state']['customer']['table']['elements']=$elements;


    $date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
    if ($date_interval['error']) {
        $date_interval=prepare_mysql_dates($_SESSION['state']['customer']['table']['from'],$_SESSION['state']['customer']['table']['to']);
    } else {
        $_SESSION['state']['customer']['table']['from']=$date_interval['from'];
        $_SESSION['state']['customer']['table']['to']=$date_interval['to'];
    }




    //  $where.=' and `Deep`=1 ';

    $where.=sprintf(' and  B.`Customer Key`=%d   ',$customer_id);
//   if(!$details)
//    $where.=" and display!='details'";
//  foreach($elements as $element=>$value){
//    if(!$value ){
//      $where.=sprintf(" and objeto!=%s ",prepare_mysql($element));
//    }
//  }

    $where.=$date_interval['mysql'];
    $_elements='';
    foreach($elements as $_key=>$_value) {
        if ($_value)
            $_elements.=','.prepare_mysql($_key);
    }
    $_elements=preg_replace('/^\,/','',$_elements);
    if ($_elements=='') {
        $where.=' and false' ;
    } else {
        $where.=' and Type in ('.$_elements.')' ;
    }


    $wheref='';



    if ( $f_field=='notes' and $f_value!='' )
        $wheref.=" and   `History Abstract` like '%".addslashes($f_value)."%'   ";
    else if ($f_field=='upto' and is_numeric($f_value) )
        $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`History Date`))<=".$f_value."    ";
    else if ($f_field=='older' and is_numeric($f_value))
        $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`History Date`))>=".$f_value."    ";
    elseif($f_field=='author' and $f_value!='') {
        $wheref.=" and   `Author Name` like '".addslashes($f_value)."%'   ";

    }






    //$sql="select count(*) as total from `Customer History Bridge` CHB  left join  `History Dimension` H on (H.`History Key`=CHB.`History Key`)   $where $wheref ";
    $sql="select count(*) as total from  `Customer History Bridge` B  left join  `History Dimension` H   on (B.`History Key`=H.`History Key`)    $where $wheref  ";
    // print $sql;
    // exit;
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($where=='') {
        $filtered=0;
        $filter_total=0;
        $total_records=$total;
    } else {

        // $sql="select count(*) as total from `Customer History Bridge` CHB  left join  `History Dimension` H on (H.`History Key`=CHB.`History Key`)   $where";
        $sql="select count(*) as total from   `Customer History Bridge` B  left join  `History Dimension` H   on (B.`History Key`=H.`History Key`)  $where ";
        // print $sql;
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $filtered=$row['total']-$total;
            $total_records=$row['total'];
        }

    }
    mysql_free_result($result);


    $rtext=$total_records." ".ngettext('record','records',$total_records);

    if ($total==0)
        $rtext_rpp='';
    elseif($total_records>$number_results)
    $rtext_rpp=sprintf('(%d%s)',$number_results,_('rpp'));
    else
        $rtext_rpp=_('Showing all');


    //print "$f_value $filtered  $total_records t: $total";
    $filter_msg='';
    if ($filtered>0) {
        switch ($f_field) {
        case('notes'):
            if ($total==0 and $filtered>0)
                $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any record matching")." <b>$f_value</b> ";
            elseif($filtered>0)
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total ".ngettext('record matching','records matching',$total)." <b>$f_value</b>";
            break;
        case('older'):
            if ($total==0 and $filtered>0)
                $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any record older than")." <b>$f_value</b> ".ngettext('day','days',$f_value);
            elseif($filtered>0)
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total ".ngettext('record older than','records older than',$total)." <b>$f_value</b> ".ngettext('day','days',$f_value);
            break;
        case('upto'):
            if ($total==0 and $filtered>0)
                $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any record in the last")." <b>$f_value</b> ".ngettext('day','days',$f_value);
            elseif($filtered>0)
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total ".ngettext('record in the last','records inthe last',$total)." <b>$f_value</b> ".ngettext('day','days',$f_value)."<span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
            break;


        }
    }
    $_order=$order;
    $_dir=$order_direction;
    if ($order=='date') {
        $order="`History Date` $order_direction , `History Key` $order_direction ";


    }
    if ($order=='note')
        $order="`History Abstract` $order_direction";
    if ($order=='objeto')
        $order="`Direct Object` $order_direction";
    if ($order=='handle')
        $order="`Author Name` $order_direction";

// $order="`History Date` desc,  `History Key` DESC  ";


//    $sql="select * from `Customer History Bridge` CHB  left join  `History Dimension` H on (H.`History Key`=CHB.`History Key`)   left join `User Dimension` U on (H.`User Key`=U.`User Key`)  $where $wheref  order by `$order` $order_direction limit $start_from,$number_results ";
    $sql="select `Type`,`Strikethrough`,`Deletable`,`Subject`,`Author Name`,`History Details`,`History Abstract`,H.`History Key`,`History Date` from  `Customer History Bridge` B left join `History Dimension` H  on (B.`History Key`=H.`History Key`)  left join          `Customer Dimension` CD on (B.`Customer Key`=CD.`Customer Key`)   $where $wheref  order by $order limit $start_from,$number_results ";


// print $sql;
    $result=mysql_query($sql);
    $data=array();
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        if ($row['History Details']=='')
            $note=$row['History Abstract'];
        else
            $note=$row['History Abstract'].' <img class="button" d="no" id="ch'.$row['History Key'].'" hid="'.$row['History Key'].'" onClick="showdetails(this)" src="art/icons/closed.png" alt="Show details" />';

        //$objeto=$row['Direct Object'];
        $objeto=$row['History Details'];

        if ($row['Subject']=='Customer')
            $author=_('Customer');
        else
            $author=$row['Author Name'];

        $data[]=array(
                    'key'=>$row['History Key'],
                    'date'=>strftime("%a %e %b %Y", strtotime($row['History Date']." +00:00")),
                    'time'=>strftime("%H:%M", strtotime($row['History Date']." +00:00")),
                    'objeto'=>$objeto,
                    'note'=>$note,
                    'handle'=>$author,
                    'delete'=>($row['Type']=='Notes'?($row['Deletable']=='Yes'?'<img alt="'._('delete').'" src="art/icons/cross.png" />':($row['Strikethrough']=='Yes'?'<img alt="'._('unstrikethrough').'" src="art/icons/text_unstrikethrough.png" />':'<img alt="'._('strikethrough').'" src="art/icons/text_strikethrough.png" />')):''),
                            'edit'=>(($row['Deletable']=='Yes' or $row['Type']=='Orders')?'<img style="cursor:pointer" alt="'._('edit').'" src="art/icons/edit.gif" />':''),
                            'can_delete'=>($row['Deletable']=='Yes'?1:0),
                            'delete_type'=>_('delete'),
                            'type'=>$row['Type'],
                            'strikethrough'=>$row['Strikethrough']
                        );
    }
    mysql_free_result($result);
    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$data,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'total_records'=>$total,
                                      'records_offset'=>$start_from,
                                      //	 'records_returned'=>$start_from+$res->numRows(),
                                      'records_perpage'=>$number_results,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'records_order'=>$order,
                                      'records_order_dir'=>$order_dir,
                                      'filtered'=>$filtered
                                     )
                   );
    echo json_encode($response);
}

function list_staff_history() {

    $conf=$_SESSION['state']['staff_history']['table'];

    if (isset( $_REQUEST['id']))
        $staff_id=$_REQUEST['id'];
    else
        $staff_id=$_SESSION['state']['staff_history']['id'];


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

    if (isset( $_REQUEST['details']))
        $details=$_REQUEST['details'];
    else
        $details=$conf['details'];


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

    /*  if (isset( $_REQUEST['from']))
          $from=$_REQUEST['from'];
      else
          $from=$conf['from'];
      if (isset( $_REQUEST['to']))
          $to=$_REQUEST['to'];
      else
          $to=$conf['to'];*/

    /*  $elements=$conf['elements'];
      if (isset( $_REQUEST['element_orden']))
          $elements['orden']=$_REQUEST['e_orden'];
      if (isset( $_REQUEST['element_h_cust']))
          $elements['h_cust']=$_REQUEST['e_orden'];
      if (isset( $_REQUEST['element_h_cont']))
          $elements['h_cont']=$_REQUEST['e_orden'];
      if (isset( $_REQUEST['element_note']))
          $elements['note']=$_REQUEST['e_orden'];*/


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;




    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    $_SESSION['state']['staff_history']['id']=$staff_id;
    $_SESSION['state']['staff_history']['table']=array('details'=>$details,'order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
    /*    $date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
        if ($date_interval['error']) {
            $date_interval=prepare_mysql_dates($_SESSION['state']['staff']['table']['from'],$_SESSION['state']['staff']['table']['to']);
        } else {
            $_SESSION['state']['staff']['table']['from']=$date_interval['from'];
            $_SESSION['state']['staff']['table']['to']=$date_interval['to'];
        }
    */





    $where.=sprintf(' and  SD.`Staff Key`=%d and `Subject`="Staff"  ',$staff_id);
//   if(!$details)
//    $where.=" and display!='details'";
//  foreach($elements as $element=>$value){
//    if(!$value ){
//      $where.=sprintf(" and objeto!=%s ",prepare_mysql($element));
//    }
//  }

    // $where.=$date_interval['mysql'];

    $wheref='';



    /* if ( $f_field=='notes' and $f_value!='' )
         $wheref.=" and   `History Abstract` like '%".addslashes($f_value)."%'   ";
     if ($f_field=='upto' and is_numeric($f_value) )
         $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date))<=".$f_value."    ";
     else if ($f_field=='older' and is_numeric($f_value))
         $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date))>=".$f_value."    ";
     else*/
    if ($f_field=='author' and $f_value!='') {
        if (is_numeric($f_value))
            $wheref.=" and   staff_id=$f_value   ";
        else {
            $wheref.=" and  handle like='".addslashes($f_value)."%'   ";
        }
    }


    $sql="select count(*) as total from `Staff Dimension` SD  left join  `History Dimension` H on (H.`Direct Object Key`=SD.`Staff Key`)   $where $wheref  ";
    // $sql="select count(*) as total from `Staff Event Dimension` SED  left join  `Staff Dimension` SD on (SED.`Staff Key`=SD.`Staff Key`)   $where $wheref ";
//print $sql;
    // exit;
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($where=='') {
        $filtered=0;
        $filter_total=0;
        $total_records=$total;
    } else {

        $sql="select count(*) as total from `Staff Dimension` SD  left join  `History Dimension` H on (H.`Direct Object Key`=SD.`Staff Key`)   $where $wheref  ";
        // $sql="select count(*) as total from `Staff Event Dimension` SED  left join  `Staff Dimension` SD on (SED.`Staff Key`=SD.`Staff Key`)   $where $wheref ";
        // print $sql;
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $filtered=$row['total']-$total;
            $total_records=$row['total'];
        }

    }
    mysql_free_result($result);


    $rtext=$total_records." ".ngettext('record','records',$total_records);

    if ($total==0)
        $rtext_rpp='';
    elseif($total_records>$number_results)
    $rtext_rpp=sprintf('(%d%s)',$number_results,_('rpp'));
    else
        $rtext_rpp=_('Showing all');


    //print "$f_value $filtered  $total_records t: $total";
    $filter_msg='';
    if ($filtered>0) {
        switch ($f_field) {
        case('notes'):
            if ($total==0 and $filtered>0)
                $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any record matching")." <b>$f_value</b> ";
            elseif($filtered>0)
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total ".ngettext('record matching','records matching',$total)." <b>$f_value</b>";
            break;
        case('older'):
            if ($total==0 and $filtered>0)
                $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any record older than")." <b>$f_value</b> ".ngettext('day','days',$f_value);
            elseif($filtered>0)
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total ".ngettext('record older than','records older than',$total)." <b>$f_value</b> ".ngettext($f_value,'day','days');
            break;
        case('upto'):
            if ($total==0 and $filtered>0)
                $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any record in the last")." <b>$f_value</b> ".ngettext('day','days',$f_value);
            elseif($filtered>0)
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total ".ngettext('record in the last','records inthe last',$total)." <b>$f_value</b> ".ngettext($f_value,'day','days')."<span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
            break;


        }
    }



    $_order=$order;
    $_dir=$order_direction;
    if ($order=='date')
        $order='History Date';
    if ($order=='staff_id')
        $order='Staff Key';
    if ($order=='subject')
        $order='Subject';
    if ($order=='name')
        $order='Staff Name';

    $sql="select * from `Staff Dimension` SD  left join  `History Dimension` H on (H.`Direct Object Key`=SD.`Staff Key`)   $where $wheref  order by `$order` $order_direction limit $start_from,$number_results ";
//  print $sql;
    $result=mysql_query($sql);
    $data=array();

    while ($row=mysql_fetch_array($result)) {

        if ($row['History Details']=='')
            $note=$row['History Abstract'];
        else
            $note=$row['History Abstract'].' <img class="button" d="no" id="ch'.$row['History Key'].'" hid="'.$row['History Key'].'" onClick="showdetails(this)" src="art/icons/closed.png" alt="Show details" />';

        // $objeto=$row['Direct Object'];

        $objeto=$row['History Details'];
        $data[]=array(
                    'id'=>$row['History Key'],
                    'date'=>strftime("%a %e %b %Y", strtotime($row['History Date']." +00:00")),
                    'time'=>strftime("%H:%M", strtotime($row['History Date']." +00:00")),
                    'objeto'=>$objeto,
                    'note'=>$note,
                    //'description'=>$row['History Details']
                );
    }
    mysql_free_result($result);
    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$data,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      // 'total_records'=>$total,
                                      'records_offset'=>$start_from,
                                      //	 'records_returned'=>$start_from+$res->numRows(),
                                      'records_perpage'=>$number_results,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'records_order'=>$order,
                                      'records_order_dir'=>$order_dir,
                                      'filtered'=>$filtered
                                     )
                   );
    echo json_encode($response);
}


function list_history($asset_type) {

    $id_key='id';
    if ($asset_type=='product') {
        $asset='Product';
        $id_key='tag';
    }
    elseif($asset_type=='family') {
        $asset='Family';
    }
    elseif($asset_type=='part') {
        $asset='Part';
        //  $id_key='sku';
    }
    elseif($asset_type=='company_area') {
        $asset='Company Area';
    }
    elseif($asset_type=='edit_each_staff') {
        $asset='Company Staff';
    }
    elseif($asset_type=='company_position') {
        $asset='Company Position';
    }

    elseif($asset_type=='department') {
        $asset='Department';
    }
    elseif($asset_type=='store') {
        $asset='Store';
    }
    elseif($asset_type=='contact') {
        $asset='Contact';
    }
    elseif($asset_type=='company') {
        $asset='Company';
    }
   elseif($asset_type=='site') {
        $asset='Site';
    }
  elseif($asset_type=='page') {
        $asset='Page';
    }
    elseif($asset_type=='company_department') {
        $asset='Company Department';
    }

    elseif($asset_type=='position') {
        $asset='Position';
    }
    elseif($asset_type=='supplier') {
        $asset='Supplier';
    }
    elseif($asset_type=='supplier_product') {
        $asset='Supplier Product';
        $id_key='pid';
    }
    elseif($asset_type=='product_categories') {
        $asset='Category';
        $id_key='parent_key';
        $asset_type='categories';
    }
    elseif($asset_type=='customer_categories') {
        $asset='Category';
        $id_key='parent_key';
        $asset_type='categories';
    }
    elseif($asset_type=='supplier_categories') {
        $asset='Category';
        $id_key='parent_key';
        $asset_type='categories';
    }






    $conf=$_SESSION['state'][$asset_type]['history'];




    $asset_id=$_SESSION['state'][$asset_type][$id_key];

    //if (isset( $_REQUEST['elements']))
    //     $elements=$_REQUEST['elements'];
    //else
    //   $elements=$conf['elements'];

    if (isset( $_REQUEST['from']))
        $from=$_REQUEST['from'];
    else
        $from=$conf['from'];
    if (isset( $_REQUEST['to']))
        $to=$_REQUEST['to'];
    else
        $to=$conf['to'];
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


    list($date_interval,$error)=prepare_mysql_dates($from,$to);
    if ($error) {
        list($date_interval,$error)=prepare_mysql_dates($conf['from'],$conf['to']);
    } else {
        $_SESSION['state'][$asset_type]['history']['from']=$from;
        $_SESSION['state'][$asset_type]['history']['to']=$to;
    }

    $_SESSION['state'][$asset_type]['history']=
        array(
            'order'=>$order,
            'order_dir'=>$order_direction,
            'nr'=>$number_results,
            'sf'=>$start_from,
            'where'=>$where,
            'f_field'=>$f_field,
            'f_value'=>$f_value,
            'from'=>$from,
            'to'=>$to,
            //   'elements'=>$elements
        );


    //print_r($_SESSION['state'][$asset_type]['history']);

    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';


    $where='where true';

    $wheref='';

    $where=$where.sprintf(" and  ( (`Direct Object`='%s' and `Direct Object Key`=%d) or (`Indirect Object`='%s' and `Indirect Object Key`=%d)  )    "
                          ,$asset
                          ,$asset_id
                          ,$asset
                          ,$asset_id
                         );


    //  $where =$where.$view.sprintf(' and asset_id=%d  %s',$asset_id,$date_interval);



    $sql="select count(*) as total from `History Dimension`  $where $wheref";
//print($asset);print("**********");print($asset_id);print("*********");
//print($sql);print("*********");

    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        $total=$row['total'];
    }
    if ($wheref!='') {
        $sql="select count(*) as total_without_filters from `History Dimension`  $where ";
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


    $rtext=$total_records." ".ngettext('record','records',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' '._('(Showing all)');

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with code like ")." <b>".$f_value."*</b> ";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with name like ")." <b>".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with code like')." <b>".$f_value."*</b>";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with name like')." <b>".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';











    if ($order_direction=='')
        $rev_order_direction=' desc';
    else
        $rev_order_direction='';

    $order='`History Date` '.$order_direction.',`History Key`  '.$rev_order_direction;
    $_order='date';

    $sql=sprintf("select  * from `History Dimension` H left join `User Dimension` U on (U.`User Key`=H.`User Key`)   $where $wheref order by $order  limit $start_from,$number_results ");

    $result=mysql_query($sql);
    $adata=array();
    while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {



        $tipo=$data['Action'];
        $author=$data['User Alias'];


        if ($data['History Details']=='')
            $note=$data['History Abstract'];
        else
            $note=$data['History Abstract'].' <img class="button" d="no" id="ch'.$data['History Key'].'" hid="'.$data['History Key'].'" onClick="showdetails(this)" src="art/icons/closed.png" alt="Show details" />';



        $adata[]=array(

                     'author'=>$author
                              ,'tipo'=>$tipo
                                      ,'abstract'=>$note
                                                  ,'date'=>strftime("%a %e %b %Y %T", strtotime($data['History Date']." +00:00")),
                 );
    }





    $response=array('resultset'=>
                                array(
                                    'state'=>200,
                                    'data'=>$adata,
                                    'sort_key'=>$_order,
                                    'sort_dir'=>$_dir,
                                    'tableid'=>$tableid,
                                    'filter_msg'=>$filter_msg,
                                    'rtext'=>$rtext,
                                    'rtext_rpp'=>$rtext_rpp,
                                    'total_records'=>$total,
                                    'records_offset'=>$start_from,
                                    'records_perpage'=>$number_results,
                                )
                   );
    echo json_encode($response);

}


function list_indirect_history($data) {

    $parent_key=$data['parent_key'];
    $scope=$data['scope'];



    if ($scope=='company_area') {
        $scope='Company Area';
        $scope_parent_key_column='Company Key';
        $scope_key_column='Company Area Key';

        $scope_table='Company Area Dimension';
    }
    if ($scope=='company_department') {
        $scope='Company Department';
        $scope_table='Company Department Dimension';
        $scope_key_column='Company Department Key';
        $scope_parent_key_column='Company Key';


    }


    $conf=$_SESSION['state'][$data['parent']]['history'];


    if (isset( $_REQUEST['elements']))
        $elements=$_REQUEST['elements'];
    else
        $elements=$conf['elements'];

    if (isset( $_REQUEST['from']))
        $from=$_REQUEST['from'];
    else
        $from=$conf['from'];
    if (isset( $_REQUEST['to']))
        $to=$_REQUEST['to'];
    else
        $to=$conf['to'];
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


    list($date_interval,$error)=prepare_mysql_dates($from,$to);
    if ($error) {
        list($date_interval,$error)=prepare_mysql_dates($conf['from'],$conf['to']);
    } else {
        $_SESSION['state'][$data['parent']]['history']['from']=$from;
        $_SESSION['state'][$data['parent']]['history']['to']=$to;
    }

    $_SESSION['state'][$data['parent']]['history']=
        array(
            'order'=>$order,
            'order_dir'=>$order_direction,
            'nr'=>$number_results,
            'sf'=>$start_from,
            'where'=>$where,
            'f_field'=>$f_field,
            'f_value'=>$f_value,
            'from'=>$from,
            'to'=>$to,
            'elements'=>$elements
        );


    //print_r($_SESSION['state'][$data['parent']]['history']);

    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';


    $where='where true';

    $wheref='';

    $table=sprintf(' `History Dimension`H  left join `%s` X on (H.`Direct Object Key`=X.`%s`)  ',$scope_table,$scope_key_column);
    $where=$where.sprintf(" and `Subject`='User'  and  `Direct Object`='%s' and X.`%s`='%d'     "
                          ,$scope
                          ,$scope_parent_key_column
                          ,$parent_key
                         );


    //   $where =$where.$view.sprintf(' and asset_id=%d  %s',$asset_id,$date_interval);

    $sql="select count(*) as total from  $table   $where $wheref";
    // print "$sql";
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($wheref=='')
        $filtered=0;
    else {
        $sql="select count(*) as total from  $table  $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $filtered=$row['total']-$total;
        }

    }


    if ($total==0)
        $rtext=_('No history records');
    else
        $rtext=$total.' '.ngettext('record','records',$total);

    if ($order_direction=='')
        $rev_order_direction=' desc';
    else
        $rev_order_direction='';

    $order='`History Date` '.$order_direction.',`History Key`  '.$rev_order_direction;


    $sql=sprintf("select  * from $table left join `User Dimension` U on (U.`User Key`=H.`Subject Key`)   $where $wheref order by $order  limit $start_from,$number_results ");
    // print $sql;
    $result=mysql_query($sql);
    $adata=array();
    while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {



        $tipo=$data['Action'];
        $author=$data['Author Name'];


        if ($data['History Details']=='')
            $note=$data['History Abstract'];
        else
            $note=$data['History Abstract'].' <img class="button" d="no" id="ch'.$data['History Key'].'" hid="'.$data['History Key'].'" onClick="showdetails(this)" src="art/icons/closed.png" alt="Show details" />';



        $adata[]=array(

                     'author'=>$author
                              ,'tipo'=>$tipo
                                      ,'abstract'=>$note
                                                  ,'date'=>strftime("%a %e %b %Y %T", strtotime($data['History Date']." +00:00")),
                 );
    }
    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'rtext'=>$rtext,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'total_records'=>$total,
                                      'records_offset'=>$start_from,
                                      'records_returned'=>$start_from+$total,
                                      'records_perpage'=>$number_results,
                                      'records_text'=>$rtext,
                                      'records_order'=>$order,
                                      'records_order_dir'=>$order_dir,
                                      'filtered'=>$filtered
                                     )
                   );
    echo json_encode($response);

}











?>
