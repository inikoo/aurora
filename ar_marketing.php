<?php
require_once 'common.php';
require_once 'ar_common.php';


if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case('email_campaigns'):
  
    email_campaigns();


    break;
}


function email_campaigns() {
 global $user;
    $conf=$_SESSION['state']['marketing']['email_campaigns'];
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

    

    $filter_msg='';
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    $_order=$order;
    $_dir=$order_direction;

   
  
    //$_SESSION['state']['marketing']['email_campaigns']['view']=$product_view;
    //$_SESSION['state']['marketing']['email_campaigns']['percentage']=$product_percentage;
    //$_SESSION['state']['marketing']['email_campaigns']['period']=$product_period;
    $_SESSION['state']['marketing']['email_campaigns']['order']=$order;
    $_SESSION['state']['marketing']['email_campaigns']['order_dir']=$order_dir;
    $_SESSION['state']['marketing']['email_campaigns']['nr']=$number_results;
    $_SESSION['state']['marketing']['email_campaigns']['sf']=$start_from;
    $_SESSION['state']['marketing']['email_campaigns']['where']=$where;
    $_SESSION['state']['marketing']['email_campaigns']['f_field']=$f_field;
    $_SESSION['state']['marketing']['email_campaigns']['f_value']=$f_value;





    //$_SESSION['state']['supplier']['id']=$supplier_id;


    //if($parent=='none')
    //$where.='';
    //else
   
    $store_keys=join(',',$user->stores);
    if(!$store_keys){
            $where=$where.' and false';

    }else{
    $where=$where.' and `Email Campaign Store Key` in ('.$store_keys.')   ';
    }


    $wheref='';

//print "$f_field -> $f_value";


    if (($f_field=='name' ) and $f_value!='')
        $wheref.=" and  `Email Campaign Name ` like '%".addslashes($f_value)."%'";
 







    $sql="select count(*) as total from `Email Campaign Dimension`  $where $wheref ";

//print $sql;
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {

        $sql="select count(*) as total from `Email Campaign Dimension`  $where  ";
      // print $sql;
       $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$row['total']-$total;
        }

    }



 $rtext=$total_records." ".ngettext('email campaign','email campaigns',$total_records);
    if($total_records>$number_results)
      $rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
    else
      $rtext_rpp=sprintf("Showing all campaigns");



  
    $filter_msg='';

    switch ($f_field) {
    case('name'):
        if ($total==0 and $filtered>0)
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any campaign with name")." <b>".$f_value."*</b> ";
        elseif($filtered>0)
        $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('campaigns with name')." <b>".$f_value."*</b>)";
        break;
    

    }
    
    //print "$order --\n";
    
    if ($order=='date'){
      $order='`Email Campaign Last Updated Date`';
  } elseif ($order=='store'){
      $order='`Store Code`';
  }else{
      $order='`Email Campaign Last Updated Date`';
      $order_direction=' desc';
    }
    $sql="select `Email Campaign Key`,`Email Campaign Name`,`Email Campaign Last Updated Date`,`Store Code` ,`Store Key` from `Email Campaign Dimension` left join `Store Dimension` S on (`Store Key`=`Email Campaign Store Key`) $where $wheref  order by $order $order_direction limit $start_from,$number_results ";

 //  print $sql;
    $data=array();

    $result=mysql_query($sql);
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


$name=sprintf('<a href="email_campaign.php?id=%d">%s</a>',$row['Email Campaign Key'],$row['Email Campaign Name']);
$store=sprintf('<a href="store.php?id=%d">%s</a>',$row['Store Key'],$row['Store Code']);

        $data[]=array(
		   
		      'name'=>$name,
		      'store'=>$store,
		      'date'=>strftime("%a %e %b %y %H:%M", strtotime($row['Email Campaign Last Updated Date']." +00:00")),
            
		      
		      );
    }


    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$data,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'rtext'=>$rtext,
				      'rtext_rpp'=>$rtext_rpp,
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
