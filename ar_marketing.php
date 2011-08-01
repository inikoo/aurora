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
case('preview_email_campaign'):
    $data=prepare_values($_REQUEST,array(
                             'email_campaign_key'=>array('type'=>'key'),
                             'index'=>array('type'=>'number')
                         ));
    preview_email_campaign($data);
    break;
    
case('is_email_campaign_name'):
    $data=prepare_values($_REQUEST,array(
                             'store_key'=>array('type'=>'key'),
                             'query'=>array('type'=>'string')
                         ));
    is_email_campaign_name($data);
    break;
case('email_campaigns'):
  
    email_campaigns();


    break;
 default:
    $response=array('state'=>404,'msg'=>_('Operation not found'));
    echo json_encode($response);   
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


    if (isset( $_REQUEST['store_id'])    ) {
        $store=$_REQUEST['store_id'];
        $_SESSION['state']['marketing']['store']=$store;
    } else
        $store=$_SESSION['state']['marketing']['store'];


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
    elseif($total_records>0)
      $rtext_rpp='('._("Showing all").')';
    else
        $rtext_rpp='';


  
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
function is_email_campaign_name($data) {
    if (!isset($data['query']) or !isset($data['store_key'])) {
        $response= array(
                       'state'=>400,
                       'msg'=>'Error'
                   );
        echo json_encode($response);
        return;
    } else
        $query=$data['query'];
    if ($query=='') {
        $response= array(
                       'state'=>200,
                       'found'=>0
                   );
        echo json_encode($response);
        return;
    }

    $store_key=$data['store_key'];

    $sql=sprintf("select `Email Campaign Key`,`Email Campaign Objective`,`Email Campaign Name` from `Email Campaign Dimension` where  `Email Campaign Store Key`=%d and  `Email Campaign Name`=%s  "
                 ,$store_key
                 ,prepare_mysql($query)
                );
    $res=mysql_query($sql);

    if ($data=mysql_fetch_array($res)) {
        $msg=sprintf('Another Campaign (<a href="email_campaign.php?id=%d">%s</a>) already has this name'
                     ,$data['Email Campaign Key']
                     ,$data['Email Campaign Name']
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

function preview_email_campaign($data){
include_once('class.EmailCampaign.php');
$email_campaign= new EmailCampaign($data['email_campaign_key']);
  if (!$email_campaign->id) {
        $response= array('state'=>400,'msg'=>'Invalid Email Campaign Key','key'=>$data['okey']);
        echo json_encode($response);
        exit;
    }
    $index=$data['index'];
        if($index>$email_campaign->data['Number of Emails'])
        $index=1;
    elseif($index<1)
        $index=$email_campaign->data['Number of Emails'];
    $email_mailing_list_key=$email_campaign->get_email_mailing_list_key_from_index($index);
    
    if(!$email_mailing_list_key){
     $response= array('state'=>400,'msg'=>'Invalid Email List Index','key'=>$data['okey']);
        echo json_encode($response);
        exit;
    
    }
    $message_data=$email_campaign->get_message_data($email_mailing_list_key);     

    
    if($message_data['type']=='Plain'){
            $body=$message_data['plain'];
    }else{
     $body=$message_data['html'];
    }
    
       $response= array('state'=>200,
       'plain'=>$message_data['plain'],
       'html_src'=>'email_template.php?email_campaign_key='.$email_campaign->id.'&email_mailing_list_key='.$email_mailing_list_key,
       'subject'=>$message_data['subject'],
       'index'=>$index,
       'formated_index'=>number($index),
       'to'=>$message_data['to'],
       'type'=>$message_data['type']
       );
        echo json_encode($response);
     


}

?>
