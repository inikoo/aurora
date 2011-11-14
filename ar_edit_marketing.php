<?php
require_once 'common.php';
require_once 'ar_edit_common.php';
require_once 'class.EmailCampaign.php';
require_once 'class.Page.php';
require_once 'common_natural_language.php';



if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {

case('update_objetive'):

 $data=prepare_values($_REQUEST,array(
                             'objetive_key'=>array('type'=>'key'),
                             'objetive_term'=>array('type'=>'string'),
                             'objetive_time_limit_in_seconds'=>array('type'=>'numeric'),
                             
                         ));

    update_objetive($data);
    break;

case('upload_postcard'):
    upload_postcard();
    break;
case('upload_template_header_image'):

    upload_template_header_image();
    break;
case('upload_postcard'):
    upload_postcard();
    break;
case('email_template_postcards'):
    email_template_postcards();
    break;
case('email_template_header_images'):
    email_template_header_images();
    break;
case('color_schemes'):
    color_schemes();
    break;
case('delete_template_postcard'):
    $data=prepare_values($_REQUEST,
                         array(
                             'id'=>array('type'=>'key')
                         )
                        );

    delete_postcard($data);

    break;
case('delete_template_header_image'):
    $data=prepare_values($_REQUEST,
                         array(
                             'id'=>array('type'=>'key')
                         )
                        );

    delete_template_header_image($data);

    break;
case('delete_color_scheme'):
    $data=prepare_values($_REQUEST,
                         array(
                             'id'=>array('type'=>'key')
                         )
                        );

    delete_color_scheme($data);

    break;
case('new_color_scheme'):
    $data=prepare_values($_REQUEST,array(
                             'store_key'=>array('type'=>'key'),
                             'kbase_color_scheme_key'=>array('type'=>'numeric'),
                         ));
    new_color_scheme($data);

    break;
case('reset_color_scheme'):
    $data=prepare_values($_REQUEST,array(
                             'color_scheme_key'=>array('type'=>'key'),

                         ));
    reset_color_scheme($data);

    break;
case('edit_color_scheme'):

    $data=prepare_values($_REQUEST,array(
                             'color_scheme_key'=>array('type'=>'key'),
                             'color_element'=>array('type'=>'string'),
                             'color'=>array('type'=>'string'),
                         ));
    edit_color_scheme($data);

    break;
case('edit_email_content'):
    $data=prepare_values($_REQUEST,array(
                             'email_campaign_key'=>array('type'=>'key'),
                             'email_content_key'=>array('type'=>'key'),

                             'key'=>array('type'=>'string'),
                             'value'=>array('type'=>'string'),
                         ));
    edit_email_content($data);

    break;
case('delete_email_campaign_objetive'):
    $data=prepare_values($_REQUEST,array(
                             'id'=>array('type'=>'key'),

                         ));
    delete_email_campaign_objetive($data);
    break;

    break;

case('add_email_campaign_objective'):
    $data=prepare_values($_REQUEST,array(
                             'email_campaign_key'=>array('type'=>'key'),
                             'parent'=>array('type'=>'string'),
                             'parent_key'=>array('type'=>'key'),
                         ));
    add_email_campaign_objective($data);
    break;


case('email_campaign_objetives'):
    email_campaign_objetives();
    break;
case('delete_email_campaign_recipient'):
    $data=prepare_values($_REQUEST,array(
                             'id'=>array('type'=>'key'),

                         ));
    delete_email_campaign_recipient($data);
    break;

case('set_email_campaign_as_ready'):
    $data=prepare_values($_REQUEST,array(
                             'email_campaign_key'=>array('type'=>'key'),
                             'start_sending_in'=>array('type'=>'numeric')
                         ));
    set_email_campaign_as_ready($data);
    break;
case('mailing_list'):

    mailing_list();

    break;
case('add_email_template_header'):
    $data=prepare_values($_REQUEST,array(
                             'files_data'=>array('type'=>'json array'),
                             'scope'=>array('type'=>'string'),
                             'scope_key'=>array('type'=>'key'),
                             'caption'=>array('type'=>'string')

                         ));
    add_email_template_header($data);
    break;
case('delete_email_paragraph'):
    $data=prepare_values($_REQUEST,array(
                             'values'=>array('type'=>'json array')
                         ));
    delete_email_paragraph($data);
    break;
case('move_email_paragraph'):
    $data=prepare_values($_REQUEST,array(
                             'values'=>array('type'=>'json array')
                         ));
    move_email_paragraph($data);
    break;
case('edit_email_paragraph'):
    $data=prepare_values($_REQUEST,array(
                             'values'=>array('type'=>'json with html array')
                         ));
    edit_email_paragraph($data);
    break;
case('edit_email_campaign'):
case('edit_email_content_text'):
case('edit_email_content_html'):

    $data=prepare_values($_REQUEST,array(
                             'email_campaign_key'=>array('type'=>'key'),
                             'okey'=>array('type'=>'string'),
                             'key'=>array('type'=>'string'),
                             'newvalue'=>array('type'=>'string'),
                             'email_content_key'=>array('type'=>'string')

                         ));
    edit_email_campaign($data);
    break;
case('select_html_email_from_template_campaign'):
    $data=prepare_values($_REQUEST,array(
                             'email_campaign_key'=>array('type'=>'key'),
                             'email_content_key'=>array('type'=>'key')

                         ));
    $data['newvalue']='HTML Template';
    $data['key']='Email Campaign Content Type';
    $data['okey']='email_campaign_content_type';
    edit_email_campaign($data);
    break;

    break;
case('select_html_email_campaign'):
    $data=prepare_values($_REQUEST,array(
                             'email_campaign_key'=>array('type'=>'key'),
                             'email_content_key'=>array('type'=>'key')
                         ));
    $data['newvalue']='HTML';
    $data['key']='Email Campaign Content Type';
    $data['okey']='email_campaign_content_type';
    edit_email_campaign($data);
    break;
case('select_plain_email_campaign'):
    $data=prepare_values($_REQUEST,array(
                             'email_campaign_key'=>array('type'=>'key'),
                             'email_content_key'=>array('type'=>'key')

                         ));
    $data['newvalue']='Plain';
    $data['key']='Email Campaign Content Type';
    $data['okey']='email_campaign_content_type';
    edit_email_campaign($data);
    break;
case('delete_email_campaign'):
    $data=prepare_values($_REQUEST,array(
                             'email_campaign_key'=>array('type'=>'key'),
                         ));
    delete_email_campaign($data);
    break;

case('add_emails_from_list'):
    $data=prepare_values($_REQUEST,array(
                             'email_campaign_key'=>array('type'=>'key'),
                             'list_key'=>array('type'=>'key')
                         ));
    add_emails_to_email_campaign_from_list($data);
    break;
    break;
case('create_add_email_address_manually'):
    $data=prepare_values($_REQUEST,array(
                             'parent_key'=>array('type'=>'key'),
                             'values'=>array('type'=>'json array')
                         ));
    add_email_address_manually($data);
    break;
case('create_email_campaign'):
    $data=prepare_values($_REQUEST,array(
                          
                             'values'=>array('type'=>'json array')

                         ));
    create_email_campaign($data);
    break;
default:
    $response=array('state'=>404,'msg'=>_('Operation not found'));
    echo json_encode($response);
}

function add_emails_to_email_campaign_from_list($data) {
    global $user;


    $email_campaign=new EmailCampaign($data['email_campaign_key']);

    $sql=sprintf("select * from `List Dimension` where `List Key`=%d",$data['list_key']);

    $res=mysql_query($sql);
    if (!$customer_list_data=mysql_fetch_assoc($res)) {
        $response= array('state'=>400,'msg'=>'List not found');
        echo json_encode($response);
        return;

    }






    if (!in_array($customer_list_data['List Store Key'],$user->stores)) {
        $response= array('state'=>400,'msg'=>_('Operation forbidden'));
        return;

    }



    $email_campaign->add_emails_from_list($data['list_key']);

    if ($email_campaign->updated) {

        $response= array('state'=>200,
                         'action'=>'created',
                         'number_recipients'=>$email_campaign->data['Number of Emails'],
                         'recipients_preview'=>$email_campaign->data['Email Campaign Recipients Preview'],
                         'msg'=>$email_campaign->msg,
                         'ready_to_send'=>$email_campaign->ready_to_send()
                        );
    } else {
        $response= array('state'=>400,'msg'=>$email_campaign->msg);

    }
    echo json_encode($response);
}



function delete_email_campaign($data) {


    $email_campaign=new EmailCampaign($data['email_campaign_key']);
    $email_campaign->delete();
    if ($email_campaign->updated) {

        $response= array('state'=>200,'action'=>'deleted'
                        );
    } else {
        $response= array('state'=>400,'msg'=>$email_campaign->msg);

    }
    echo json_encode($response);
}


function delete_email_campaign_objetive($data) {

    $sql=sprintf('delete from `Email Campaign Objetive Dimension` where `Email Campaign Objetive Key`=%d  ',
                 $data['id']);

    mysql_query($sql);
    $response= array('state'=>200,'action'=>'deleted');



    echo json_encode($response);
}

function delete_email_campaign_recipient($data) {
    $mailing_list_key=$data['id'];

    $sql=sprintf("select `Email Campaign Key` from `Email Campaign Mailing List` where `Email Campaign Mailing List Key`=%d",
                 $mailing_list_key
                );
    $res=mysql_query($sql);


    if ($row=mysql_fetch_assoc($res)) {

        $email_campaign=new EmailCampaign($row['Email Campaign Key']);
        $email_campaign->delete_email_address($mailing_list_key);

        if ($email_campaign->updated) {

            $response= array(
                           'state'=>200,
                           'action'=>'deleted'
                       );
        } else {



            $response= array(
                           'state'=>400,
                           'action'=>'no change',
                           'msg'=>$email_campaign->msg
                       );
        }

    } else {
        $response= array(
                       'state'=>400,
                       'action'=>'error',
                       'msg'=>'email recipient not exist'
                   );
    }



    echo json_encode($response);
}



function add_email_address_manually($data) {


    $email_campaign=new EmailCampaign($data['parent_key']);
    $email_campaign->add_email_address_manually($data['values']);

    if ($email_campaign->updated) {

        $response= array('state'=>200,
                         'action'=>'created',
                         'number_recipients'=>$email_campaign->data['Number of Emails'],
                         'recipients_preview'=>$email_campaign->data['Email Campaign Recipients Preview'],
                         'ready_to_send'=>$email_campaign->ready_to_send()
                        );
    } else {
        $response= array('state'=>400,'msg'=>$email_campaign->msg);

    }
    echo json_encode($response);
}

function create_email_campaign($data) {




    $email_campaign_data=array(
                             'Email Campaign Store Key'=>$data['values']['store_key'],
                             'Email Campaign Name'=>$data['values']['email_campaign_name'],
                            'Email Content Type'=>$data['values']['email_campaign_content_type'],
                             'Email Campaign Type'=>$data['values']['email_campaign_type'],
                            
                         );
//print_r($email_campaign_data);


if($email_campaign_data['Email Campaign Name']==''){
      $response= array(
                       'state'=>400,
                       'action'=>'error',
                       'msg'=>_('Please give us the email campaign name')

                   );
                    echo json_encode($response);
                    return;
}

    $email_campaign=new EmailCampaign('find',$email_campaign_data,'create');
    if ($email_campaign->new) {

        $response= array(
                       'state'=>200,
                       'action'=>'created',
                       'email_campaign_key'=>$email_campaign->id
                   );
    }
    elseif($email_campaign->found) {
        $response= array(
                       'state'=>200,
                       'action'=>'found',
                       'email_campaign_key'=>$email_campaign->id,
                       'msg'=>_('Another email campaign has that name')

                   );
    }
    else {
        $response= array('state'=>400,'msg'=>$email_campaign->msg);

    }
    echo json_encode($response);

}




function edit_email_campaign($data) {


    $email_campaign=new EmailCampaign($data['email_campaign_key']);
    if (!$email_campaign->id) {
        $response= array('state'=>400,'msg'=>'Invalid Email Campaign Key','key'=>$data['okey']);
        echo json_encode($response);
        exit;
    }


    if ($data['key']=='Email Campaign Subject') {
        $email_campaign->update_subject($data['newvalue'],$data['email_content_key']);
    }
    elseif($data['key']=='Email Campaign Content Text') {
        $email_campaign->update_content_text($data['newvalue'],$data['email_content_key']);
    }
    elseif($data['key']=='Email Campaign Content HTML') {
        $email_campaign->update_content_html($data['newvalue'],$data['email_content_key']);
    }
    else {

        $email_campaign->update(array($data['key']=>$data['newvalue']));
    }
    if ($email_campaign->updated) {

        $response= array(
                       'state'=>200,
                       'action'=>'changed',
                       'email_campaign_key'=>$email_campaign->id,
                       'key'=>$data['okey'],
                       'newvalue'=>$email_campaign->new_value
                   );
    }
    elseif($email_campaign->error) {
        $response= array(
                       'state'=>400,

                       'msg'=>$email_campaign->msg,
                       'key'=>$data['okey']

                   );
    }
    else {
        $response= array('state'=>400,'msg'=>$email_campaign->msg);
        $response= array(
                       'state'=>200,
                       'action'=>'nochange',
                       'email_campaign_key'=>$email_campaign->id,
                       'msg'=>$email_campaign->msg,
                       'key'=>$data['okey']

                   );
    }
    echo json_encode($response);


}

function edit_email_paragraph($data) {



    $email_campaign=new EmailCampaign($data['values']['email_campaign_key']);
    $paragraph_data=array(
                        'title'=>_trim($data['values']['title']),
                        'subtitle'=>_trim($data['values']['subtitle']),
                        'content'=>_trim($data['values']['content']),
                        'type'=>$data['values']['type'],
                    );

//print_r($paragraph_data);

    if (!$data['values']['paragraph_key']) {

        if ($paragraph_data['title']=='' and $paragraph_data['subtitle']=='' and $paragraph_data['content']=='') {
            $response= array('state'=>400,'msg'=>_('All fields are empty!'));
            echo json_encode($response);
            return;
        }

        $email_campaign->add_paragraph($data['values']['email_content_key'],$paragraph_data);
    } else {

        if ($paragraph_data['title']=='' and $paragraph_data['subtitle']=='' and $paragraph_data['content']=='') {
            delete_email_paragraph($data);
            return;
        } else {

            $email_campaign->update_paragraph($data['values']['email_content_key'],$data['values']['paragraph_key'],$paragraph_data);
        }
    }
    if ($email_campaign->updated) {
        $response= array('state'=>200);

    } else {
        $response= array('state'=>400,'msg'=>$email_campaign->msg);

    }
    echo json_encode($response);
}


function move_email_paragraph($data) {
//print_r($data['values']);

    $email_campaign=new EmailCampaign($data['values']['email_campaign_key']);


    if (preg_match('/side/i',$data['values']['target'])) {
        $paragraph_type='Side';
    } else {
        $paragraph_type='Main';

    }

    if (preg_match('/\d+$/',$data['values']['paragraph_key'],$match)) {
        $paragraph_key=$match[0];
    } else {
        $response= array('state'=>400,'msg'=>'invalid paragraph');
        echo json_encode($response);
        return;
    }
    if (preg_match('/\d+$/',$data['values']['target'],$match)) {
        $target_paragraph_key=$match[0];
    } else {
        $response= array('state'=>400,'msg'=>'invalid target');
        echo json_encode($response);
        return;
    }

    $email_campaign->move_paragraph_before_target($data['values']['email_content_key'],$paragraph_key,$target_paragraph_key,$paragraph_type);

    $response= array('state'=>200);
    echo json_encode($response);
}

function edit_email_content($data) {

    $email_campaign=new EmailCampaign($data['email_campaign_key']);
    if (!$email_campaign->id) {
        $response= array('state'=>400,'msg'=>'invalid email campaign');
        echo json_encode($response);
        return;
    }

    if (!in_array($email_campaign->data['Email Campaign Store Key'],$data['user']->stores)) {
        $response= array('state'=>400,'msg'=>'forbidden');
        echo json_encode($response);
        return;
    }


    $email_campaign->update_content($data['email_content_key'],$data['key'],$data['value']);
    if ($email_campaign->updated) {


        $response= array('state'=>200,'key'=>$data['key'],'new_value'=>$email_campaign->new_value,'old_value'=>$email_campaign->old_value,'updated_data'=>$email_campaign->updated_data);

    } else {
        $response= array('state'=>400,'msg'=>'no change');
        echo json_encode($response);
        return;

    }
    echo json_encode($response);

}


function delete_email_paragraph($data) {


    $email_campaign=new EmailCampaign($data['values']['email_campaign_key']);
    if (!$email_campaign->id) {
        $response= array('state'=>400,'msg'=>'invalid email campaign');
        echo json_encode($response);
        return;
    }

    if (!in_array($email_campaign->data['Email Campaign Store Key'],$data['user']->stores)) {
        $response= array('state'=>400,'msg'=>'forbidden');
        echo json_encode($response);
        return;
    }


    $email_campaign->delete_paragraph($data['values']['email_content_key'],$data['values']['paragraph_key']);

    $response= array('state'=>200);
    echo json_encode($response);
}

function add_email_template_header($data) {


    include_once('class.Image.php');
    $create_image=false;
    foreach($data['files_data'] as $file_data) {

        $image_data=array(
                        'file'=>$file_data['filename_with_path'],
                        'source_path'=>'',
                        'name'=>$file_data['original_filename'],
                        'caption'=>''
                    );


        $image=new Image('find',$image_data,'create');
        if ($image->id) {
            $sql=sprintf("insert into  `Email Template Header Image Dimension` (`Email Template Header Image Name`,`Store Key`,`Image Key`) values (%s,%d,%d)",
                         prepare_mysql($file_data['original_filename']),
                         $data['scope_key'],
                         $image->id
                        );
            mysql_query($sql);
            $create_image=true;
        }
    }
    if ($create_image) {
        $response= array(
                       'state'=>200,
                       'action'=>'created'
                   );
    } else {

        $response= array('state'=>400,'msg'=>'can not add image');
    }

    echo json_encode($response);

}


function mailing_list() {
    $conf=$_SESSION['state']['email_campaign']['mailing_list'];
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

    if (isset( $_REQUEST['where']))
        $where=$_REQUEST['where'];
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

    if (isset( $_REQUEST['email_campaign_key'])) {
        $email_campaign_key=$_REQUEST['email_campaign_key'];
        $_SESSION['state']['email_campaign']['id']=$email_campaign_key;
    } else {
        $email_campaign_key=$_SESSION['state']['email_campaign']['id'];
    }

    $filter_msg='';



    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



    $_SESSION['state']['email_campaign']['mailing_list']['order']=$order;
    $_SESSION['state']['email_campaign']['mailing_list']['order_dir']=$order_dir;
    $_SESSION['state']['email_campaign']['mailing_list']['nr']=$number_results;
    $_SESSION['state']['email_campaign']['mailing_list']['sf']=$start_from;
    $_SESSION['state']['email_campaign']['mailing_list']['where']=$where;
    $_SESSION['state']['email_campaign']['mailing_list']['f_field']=$f_field;
    $_SESSION['state']['email_campaign']['mailing_list']['f_value']=$f_value;


    $where=sprintf(" where  `Email Campaign Key`=%d",$email_campaign_key);



    $filter_msg='';
    $wheref='';
    if ($f_field=='name' and $f_value!='')
        $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";

    $sql="select count(*) as total from `Email Campaign Mailing List`     $where $wheref";

    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($result);
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total  from `Email Campaign Mailing List`    $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $filtered=$row['total']-$total;
            $total_records=$row['total'];
        }
        mysql_free_result($result);
    }

    $rtext=sprintf(ngettext("%d recipient", "%d recipients", $total_records), $total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp='('._('Showing all').')';

    $_order=$order;
    $_dir=$order_direction;


    if ($order=='contact')
        $order='`Email Contact Name`';
    else
        $order='`Email Address`';

    $sql="select *  from `Email Campaign Mailing List` L left join `Email Send Dimension` S  on (S.`Email Send Key`=L.`Email Send Key`) $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
//print $sql;
    $res = mysql_query($sql);
    $adata=array();
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $status='';
        if (!$row['Email Send Key']) {
            $status='<span style="color:#aaa">'._('Ready to send').'</span>';

        }


        $adata[]=array(
                     'id'=>$row['Email Campaign Mailing List Key'],
                     'contact'=>$row['Email Contact Name'],
                     'email'=>$row['Email Address'],
                     'status'=>$status,
                     'delete'=>"<img src='art/icons/cross.png'  alt='"._('Delete')."'  title='"._('Delete')."' />"


                 );
    }
    mysql_free_result($res);
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


function set_email_campaign_as_ready($data) {

    $email_campaign=new EmailCampaign($data['email_campaign_key']);
    $email_campaign->set_as_ready($data['start_sending_in']);
    if (!$email_campaign->error) {
        $response= array(
                       'state'=>200,
                       'action'=>'ready'
                   );
    } else {

        $response= array('state'=>400,'msg'=>'can not add image');
    }
    echo json_encode($response);
}



function email_campaign_objetives() {
    $conf=$_SESSION['state']['email_campaign']['objetives'];
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

    if (isset( $_REQUEST['email_campaign_key'])) {
        $email_campaign_key=$_REQUEST['email_campaign_key'];
        $_SESSION['state']['email_campaign']['id']=$email_campaign_key;
    } else {
        $email_campaign_key=$_SESSION['state']['email_campaign']['id'];
    }

    $filter_msg='';



    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



    $_SESSION['state']['email_campaign']['objetives']['order']=$order;
    $_SESSION['state']['email_campaign']['objetives']['order_dir']=$order_dir;
    $_SESSION['state']['email_campaign']['objetives']['nr']=$number_results;
    $_SESSION['state']['email_campaign']['objetives']['sf']=$start_from;
    $_SESSION['state']['email_campaign']['objetives']['f_field']=$f_field;
    $_SESSION['state']['email_campaign']['objetives']['f_value']=$f_value;


    $where=sprintf(" where  `Email Campaign Key`=%d",$email_campaign_key);



    $filter_msg='';
    $wheref='';
    if ($f_field=='name' and $f_value!='')
        $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";

    $sql="select count(*) as total from `Email Campaign Objetive Dimension`     $where $wheref";

    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($result);
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total  from `Email Campaign Objetive Dimension`    $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $filtered=$row['total']-$total;
            $total_records=$row['total'];
        }
        mysql_free_result($result);
    }

    $rtext=sprintf(ngettext("%d objetive", "%d objetives", $total_records), $total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp='('._('Showing all').')';

    $_order=$order;
    $_dir=$order_direction;



    $order='`Email Campaign Objetive Name`';

    $sql="select *  from `Email Campaign Objetive Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
//print $sql;
    $res = mysql_query($sql);
    $adata=array();
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        switch ($row['Email Campaign Objetive Type']) {
        case 'Context':
            $type='<img src="art/icons/text_dropcaps.png"   style="height:14px" title="'.('Context').'"   alt="'.('Context').'"  />';
            $delete="<img src='art/icons/cross.png'  alt='"._('Delete')."'  title='"._('Delete')."' />";
            break;
        case 'Link':
            $type='<img src="art/icons/link.png"   style="height:14px" title="'.('Link').'"   alt="'.('Link').'"  />';
            $delete="";
            break;

        default:
            $type='';
            $delete="";
            break;
        }

        switch ($row['Email Campaign Objetive Parent']) {
        case 'Department':
            $parent=_('Department');
            $valid_terms=array('Order','Buy');
            break;
        case 'Family':
            $parent=_('Family');
            $valid_terms=array('Order','Buy');
            break;
        case 'Product':
            $parent=_('Product');
            $valid_terms=array('Order','Buy');
            break;
        case 'Store':
            $parent=_('Store');
            $valid_terms=array('Order','Buy');
            break;
        case 'Campaign':
            $parent=_('Campaign');
            $valid_terms=array('Use');
            break;
        case 'Deal':
            $parent=_('Deal');
            $valid_terms=array('Use');
        case 'Store Page':
            $parent=_('Store Page');
            $valid_terms=array('Visit');
            break;
        case 'External Link':
            $parent=_('External Link');
            $valid_terms=array('Visit');
            break;
        default:
            $parent=$row['Email Campaign Objetive Parent'];
            $valid_terms=array('Use');
            break;
        }

        $link='';



        switch ($row['Email Campaign Objetive Term']) {
        case 'Order':

            $metadata=preg_split('/;/',$row['Email Campaign Objetive Term Metadata']);
            $formated_time=seconds_to_string($metadata[2]);
            $time=$metadata[2];
            $objetive=_('Order').' ('.$formated_time.')';

            break;
        case 'Buy':

            $metadata=preg_split('/;/',$row['Email Campaign Objetive Term Metadata']);
            $formated_time=seconds_to_string($metadata[2]);
            $time=$metadata[2];
            $objetive=_('Buy').' ('.$formated_time.')';

            break;
        case 'Use':

            $metadata=preg_split('/;/',$row['Email Campaign Objetive Term Metadata']);
            $formated_time=seconds_to_string($metadata[0]);
            $time=$metadata[0];
            $objetive=_('Use').' ('.$formated_time.')';

            break;

        case 'Visit':

            $metadata=preg_split('/;/',$row['Email Campaign Objetive Term Metadata']);
            $formated_time=seconds_to_string($metadata[0]);
            $time=$metadata[0];
            $objetive=_('Visit').' ('.$formated_time.')';

            break;

        default:
            $objetive='';
            $formated_time='';
        }


        $adata[]=array(
                     'id'=>$row['Email Campaign Objetive Key'],

                     'name'=>$row['Email Campaign Objetive Name'],
                     'objetive'=>$objetive,
                     'term'=>$row['Email Campaign Objetive Term'],
                     'valid_terms'=>$valid_terms,
                     'metadata'=>preg_split('/;/',$row['Email Campaign Objetive Term Metadata']),
                     'temporal_formated_metadata'=>$formated_time,
                      'temporal_metadata'=>$time,
                     'parent'=>$parent,
                     'link'=>$link,
                     'type'=>$type,

                     'delete'=>$delete
                 );
    }
    mysql_free_result($res);
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


function add_email_campaign_objective($data) {

    include_once('class.Department.php');

    $email_campaign=new EmailCampaign($data['email_campaign_key']);

    $objetive_data=array(
                       'Email Campaign Objetive Parent'=>$data['parent'],
                       'Email Campaign Objetive Parent Key'=>$data['parent_key'],
                       'Email Campaign Objetive Type'=>'Context'


                   );

    $email_campaign->add_objetive($objetive_data);

    $response= array(
                   'state'=>200,
                   'action'=>'changed'
               );
    echo json_encode($response);


}


function edit_color_scheme($data) {

    $field=addslashes(preg_replace('/_/',' ',$data['color_element']));

    $sql=sprintf("update `Email Template Color Scheme Dimension` set `%s`=%s where `Email Template Color Scheme Key`=%d",
                 $field,
                 prepare_mysql($data['color']),
                 $data['color_scheme_key']

                );

    $sql=mysql_query($sql);

    if (mysql_affected_rows()) {

        $kbase_modified='No';
        $sql=sprintf("select * from `Email Template Color Scheme Dimension` where  `Email Template Color Scheme Key`=%d",
                     $data['color_scheme_key']
                    );
        $res=mysql_query($sql);
        if ($row=mysql_fetch_assoc($res)) {

            $sql=sprintf("select `Background Body`,`Background Header`,`Background Footer`,`Background Container`,`Text Header`,`Text Container`,`Text Footer`,`Link Header`,`Link Container`,`Link Footer`,`H1`,`H2` from  kbase.`Email Template Color Scheme Dimension` where `Email Template Color Scheme Key`=%d",
                         $row['Kbase Key']
                        );
            $res2=mysql_query($sql);
            //print $sql;
            if ($row2=mysql_fetch_assoc($res2)) {

                foreach($row2 as $element=>$kbase_value) {
                    if ($row[$element]!=$row2[$element]) {
                        $kbase_modified='Yes';
                        break;
                    }
                }


            }


        }


        $sql=sprintf("update `Email Template Color Scheme Dimension` set `Kbase Modifed`=%s where `Email Template Color Scheme Key`=%d",
                     prepare_mysql($kbase_modified),
                     $data['color_scheme_key']

                    );
        mysql_query($sql);

        $response= array(
                       'state'=>200,
                       'action'=>'changed',
                       'color'=>$data['color'],
                       'kbase_modified'=>$kbase_modified,
                       'element'=>$data['color_element'],
                       'color_scheme_key'=>$data['color_scheme_key']
                   );

    } else {
        $response= array(
                       'state'=>200,
                       'action'=>'nochange'
                   );

    }

    echo json_encode($response);
}


function reset_color_scheme($data) {


    $sql=sprintf("select * from `Email Template Color Scheme Dimension` where  `Email Template Color Scheme Key`=%d",
                 $data['color_scheme_key']
                );
    $res=mysql_query($sql);
    if ($row=mysql_fetch_assoc($res)) {

        $sql=sprintf("select `Background Body`,`Background Header`,`Background Footer`,`Background Container`,`Text Header`,`Text Container`,`Text Footer`,`Link Header`,`Link Container`,`Link Footer`,`H1`,`H2` from  kbase.`Email Template Color Scheme Dimension` where `Email Template Color Scheme Key`=%d",
                     $row['Kbase Key']
                    );
        $res2=mysql_query($sql);
        //print $sql;
        if ($row2=mysql_fetch_assoc($res2)) {
            $color_scheme_data=array();
            $sql_data='';
            foreach($row2 as $element=>$kbase_value) {
                $sql_data.=sprintf (',`%s`=%s',$element,prepare_mysql($kbase_value));
                $color_scheme_data[preg_replace('/ /','_',$element)]=$kbase_value;
            }
            $sql=sprintf("update `Email Template Color Scheme Dimension`  set  `Kbase Modifed`='No' %s where `Email Template Color Scheme Key`=%d ",
                         $sql_data,
                         $data['color_scheme_key']
                        );
            mysql_query($sql);

            $response= array(
                           'state'=>200,
                           'action'=>'changed',
                           'color_scheme_key'=>$data['color_scheme_key'],
                           'color_scheme_data'=>$color_scheme_data
                       );

        } else {
            $response= array(
                           'state'=>200,
                           'action'=>'nochange'
                       );

        }
    } else {
        $response= array(
                       'state'=>200,
                       'action'=>'nochange'
                   );

    }


    echo json_encode($response);


}


function new_color_scheme($data) {


    $sql=sprintf("insert into `Email Template Color Scheme Dimension`      (`Store Key`) values (%d) ",
                 $data['store_key']
                );
    mysql_query($sql);
    $id=mysql_insert_id();
    if ($id) {
        $name='CS'.$id;

        $sql=sprintf("update `Email Template Color Scheme Dimension` set `Email Template Color Scheme Name`=%s  where `Email Template Color Scheme Key`=%d",
                     prepare_mysql($name),
                     $id
                    );
        mysql_query($sql);



        $sql=sprintf("select * from `Email Template Color Scheme Dimension`  where `Email Template Color Scheme Key`=%d",
                     $id
                    );
        $res=mysql_query($sql);
        $scheme_data=array();
        if ($row=mysql_fetch_assoc($res)) {
            $scheme_data=$row['Kbase Modifed'].';'.$row['Background Body'].';'.$row['Background Header'].';'.$row['Background Container'].';'.$row['Background Footer'].';'.$row['Text Header'].';'.$row['Link Header'].';'.$row['Text Footer'].';'.$row['Link Footer'].';'.$row['Text Container'].';'.$row['Link Container'].';'.$row['H1'].';'.$row['H2'].';No';

        }


        $response= array(
                       'state'=>200,
                       'action'=>'created',
                       'color_scheme_key'=>$id,
                       'name'=>$name,
                       'data'=> $scheme_data


                   );

    } else {
        $response= array(
                       'state'=>400,
                       'msg'=>mysql_error()
                   );

    }

    echo json_encode($response);

}

function delete_color_scheme($data) {

    $sql=sprintf("select `Store Key` from `Email Template Color Scheme Dimension`  where `Email Template Color Scheme Key`=%d",
                 $data['id']
                );
    $res=mysql_query($sql);
    if ($row=mysql_fetch_assoc($res)) {
        $store_key=$row['Store Key'];
    } else {
        $response= array(
                       'state'=>400,
                       'msg'=>'Color Scheme not found'
                   );
        echo json_encode($response);
        return;
    }


    $other_color_scheme_key=0;
    $sql=sprintf("select `Email Template Color Scheme Key` from `Email Template Color Scheme Dimension`  where `Store Key`=%d and  `Email Template Color Scheme Key`!=%d  ",
                 $store_key,
                 $data['id']
                );
    $res=mysql_query($sql);
    if ($row=mysql_fetch_assoc($res)) {
        $other_color_scheme_key=$row['Email Template Color Scheme Key'];
    } else {


        $response= array(
                       'state'=>400,
                       'msg'=>_('Can not delete the last item left')
                   );
        echo json_encode($response);
        return;

    }

    $sql=sprintf("delete from `Email Template Color Scheme Dimension`  where `Email Template Color Scheme Key`=%d",

                 $data['id']
                );
    mysql_query($sql);





    if (mysql_affected_rows()) {
        $response= array(
                       'state'=>200,
                       'action'=>'deleted'
                   );

        $sql=sprintf("update `Email Content Dimension` set `Email Content Color Scheme Key`=%d   where  `Email Content Color Scheme Key`=%d ",
                     $other_color_scheme_key,
                     $data['id']);
        $res=mysql_query($sql);

    } else {
        $response= array(
                       'state'=>400,
                       'action'=>'nochange'
                   );
    }
    echo json_encode($response);


}


function color_schemes() {
    // $conf=$_SESSION['state']['email_campaign']['objetives'];

    $conf=array('sf'=>0,'nr'=>50,'order'=>'name','order_dir'=>'','f_field'=>'name','f_value'=>'');







    if (isset( $_REQUEST['email_content_key'])) {
        $email_content_key=$_REQUEST['email_content_key'];
    } else {
        $email_content_key=0;
    }
    if (isset( $_REQUEST['store_key'])) {
        $store_key=$_REQUEST['store_key'];
    } else {
        return;
    }


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





    $filter_msg='';



    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



    //$_SESSION['state']['email_campaign']['objetives']['order']=$order;
    //$_SESSION['state']['email_campaign']['objetives']['order_dir']=$order_dir;
    //$_SESSION['state']['email_campaign']['objetives']['nr']=$number_results;
    //$_SESSION['state']['email_campaign']['objetives']['sf']=$start_from;
    //$_SESSION['state']['email_campaign']['objetives']['f_field']=$f_field;
    //$_SESSION['state']['email_campaign']['objetives']['f_value']=$f_value;


    $where=sprintf(" where  `Store Key`=%d",$store_key);



    $filter_msg='';
    $wheref='';
    if ($f_field=='name' and $f_value!='')
        $wheref.=" and  `Email Template Color Scheme Name` like '".addslashes($f_value)."%'";

    $sql="select count(*) as total from `Email Template Color Scheme Dimension`     $where $wheref";

    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($result);
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total  from `Email Template Color Scheme Dimension`    $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $filtered=$row['total']-$total;
            $total_records=$row['total'];
        }
        mysql_free_result($result);
    }

    $rtext=sprintf(ngettext("%d scheme", "%d schemes", $total_records), $total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp='('._('Showing all').')';

    $_order=$order;
    $_dir=$order_direction;



    $order='`Email Template Color Scheme Name`';



    $current_color_scheme=0;

    if ($email_content_key) {
        $sql=sprintf("select `Email Content Color Scheme Key` from  `Email Content Dimension`  where `Email Content Key`=%d",$email_content_key);

        $res=mysql_query($sql);
        if ($row=mysql_fetch_assoc($res)) {

            $current_color_scheme= $row['Email Content Color Scheme Key'];
        }
    }

    $sql="select *  from `Email Template Color Scheme Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
//print $sql;
    $res = mysql_query($sql);
    $adata=array();
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


        $palette=sprintf("
                         <span  class='swatch' style='background-color:#%s;' alt='%s' title='%s'></span>
                         <span  class='swatch' style='background-color:#%s;' alt='%s' title='%s'></span>
                         <span  class='swatch' style='background-color:#%s;' alt='%s' title='%s'></span>
                         <span  class='swatch' style='background-color:#%s;' alt='%s' title='%s'></span>
                         <span  class='swatch' style='background-color:#%s;' alt='%s' title='%s'></span>
                         <span  class='swatch' style='background-color:#%s;' alt='%s' title='%s'></span>
                         <span  class='swatch' style='background-color:#%s;' alt='%s' title='%s'></span>
                         <span  class='swatch' style='background-color:#%s;' alt='%s' title='%s'></span>
                         ",
                         $row['Background Body'],
                         $row['Background Body'],
                         _('Background'),
                         $row['Background Container'],
                         $row['Background Container'],
                         _('Background Container'),
                         $row['H1'],
                         $row['H1'],
                         _('Titles'),
                         $row['H2'],
                         $row['H2'],
                         _('Subtitles'),
                         $row['Text Container'],
                         $row['Text Container'],
                         _('Text'),
                         $row['Link Container'],
                         $row['Link Container'],
                         _('Links'),
                         $row['Background Footer'],
                         $row['Background Footer'],
                         _('Footer'),
                         $row['Text Footer'],
                         $row['Text Footer'],
                         _('Text Footer')

                        );


        $delete="<img src='art/icons/cross.png'  alt='"._('Delete')."'  title='"._('Delete')."' />";

        if ($current_color_scheme==$row['Email Template Color Scheme Key']) {
            $used="<img   src='art/icons/accept.png'  alt='"._('Selected')."'  title='"._('Selected')."' />";
            $scheme_selected='Yes';
        } else {
            $used='<img style="cursor:pointer" onClick="save_select_color_scheme('.$row['Email Template Color Scheme Key'].')"  src="art/icons/accept_bw_hidden.png"  alt="Not in use"  title="'._('Click to select').'" />';
            $scheme_selected='No';

        }




        $scheme_data=$row['Kbase Modifed'].';'.$row['Background Body'].';'.$row['Background Header'].';'.$row['Background Container'].';'.$row['Background Footer'].';'.$row['Text Header'].';'.$row['Link Header'].';'.$row['Text Footer'].';'.$row['Link Footer'].';'.$row['Text Container'].';'.$row['Link Container'].';'.$row['H1'].';'.$row['H2'].';'.$scheme_selected;

        $adata[]=array(
                     'id'=>$row['Email Template Color Scheme Key'],

                     'name'=>'<img style="cursor:pointer" src="art/icons/layout_content.png" onClick="show_color_scheme_view_details('.$row['Email Template Color Scheme Key'].',\''.$scheme_data.'\',\''.htmlentities($row['Email Template Color Scheme Name']).'\')" alt="'._('Show Details').'"   title="'._('Show Details').'" > '.$row['Email Template Color Scheme Name'],
                     'palette'=>$palette,
                     'used'=>$used,

                     'delete'=>$delete
                 );
    }
    mysql_free_result($res);
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

function email_template_header_images() {
    // $conf=$_SESSION['state']['email_campaign']['objetives'];

    $conf=array('sf'=>0,'nr'=>50,'order'=>'name','order_dir'=>'','f_field'=>'name','f_value'=>'');







    if (isset( $_REQUEST['email_content_key'])) {
        $email_content_key=$_REQUEST['email_content_key'];
    } else {
        $email_content_key=0;
    }
    if (isset( $_REQUEST['store_key'])) {
        $store_key=$_REQUEST['store_key'];
    } else {
        return;
    }


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





    $filter_msg='';



    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



    //$_SESSION['state']['email_campaign']['objetives']['order']=$order;
    //$_SESSION['state']['email_campaign']['objetives']['order_dir']=$order_dir;
    //$_SESSION['state']['email_campaign']['objetives']['nr']=$number_results;
    //$_SESSION['state']['email_campaign']['objetives']['sf']=$start_from;
    //$_SESSION['state']['email_campaign']['objetives']['f_field']=$f_field;
    //$_SESSION['state']['email_campaign']['objetives']['f_value']=$f_value;


    $where=sprintf(" where  `Store Key`=%d",$store_key);



    $filter_msg='';
    $wheref='';
    if ($f_field=='name' and $f_value!='')
        $wheref.=" and  `Email Template Color Scheme Name` like '".addslashes($f_value)."%'";

    $sql="select count(*) as total from `Email Template Header Image Dimension`     $where $wheref";

    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($result);
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total  from `Email Template Header Image Dimension`    $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $filtered=$row['total']-$total;
            $total_records=$row['total'];
        }
        mysql_free_result($result);
    }

    $rtext=sprintf(ngettext("%d scheme", "%d schemes", $total_records), $total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp='('._('Showing all').')';

    $_order=$order;
    $_dir=$order_direction;



    $order='`Email Template Header Image Name`';



    $current_header_image_key=0;

    if ($email_content_key) {
        $sql=sprintf("select `Email Content Template Header Image Key` from  `Email Content Dimension`  where `Email Content Key`=%d",$email_content_key);

        $res=mysql_query($sql);
        if ($row=mysql_fetch_assoc($res)) {

            $current_header_image_key= $row['Email Content Template Header Image Key'];
        }
    }
//   $sql="select *  from `Email Template Header Image Dimension`HI  left join `Image Dimension` I on (I.`Image Key`=HI.`Image Key`) $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";

    $sql="select *  from `Email Template Header Image Dimension` $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
//print $sql;
    $res = mysql_query($sql);
    $adata=array();
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {




        $delete="<img src='art/icons/cross.png'  alt='"._('Delete')."'  title='"._('Delete')."' />";

        if ($current_header_image_key==$row['Email Template Header Image Key']) {
            $used="<img   src='art/icons/accept.png'  alt='"._('Selected')."'  title='"._('Selected')."' />";
            $scheme_selected='Yes';
        } else {
            $used='<img style="cursor:pointer" onClick="save_select_header_image('.$row['Email Template Header Image Key'].')"  src="art/icons/accept_bw_hidden.png"  alt="Not in use"  title="'._('Click to select').'" />';
            $scheme_selected='No';

        }





        $adata[]=array(
                     'id'=>$row['Email Template Header Image Key'],

                     'name'=>$row['Email Template Header Image Name'],
                     'image'=>'<img src="image.php?id='.$row['Image Key'].'"  style="width: 600px;height : auto;"    />',
                     'used'=>$used,

                     'delete'=>$delete
                 );
    }
    mysql_free_result($res);
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


function email_template_postcards() {
    // $conf=$_SESSION['state']['email_campaign']['objetives'];

    $conf=array('sf'=>0,'nr'=>50,'order'=>'name','order_dir'=>'','f_field'=>'name','f_value'=>'');







    if (isset( $_REQUEST['email_content_key'])) {
        $email_content_key=$_REQUEST['email_content_key'];
    } else {
        $email_content_key=0;
    }
    if (isset( $_REQUEST['store_key'])) {
        $store_key=$_REQUEST['store_key'];
    } else {
        return;
    }


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





    $filter_msg='';



    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



    //$_SESSION['state']['email_campaign']['objetives']['order']=$order;
    //$_SESSION['state']['email_campaign']['objetives']['order_dir']=$order_dir;
    //$_SESSION['state']['email_campaign']['objetives']['nr']=$number_results;
    //$_SESSION['state']['email_campaign']['objetives']['sf']=$start_from;
    //$_SESSION['state']['email_campaign']['objetives']['f_field']=$f_field;
    //$_SESSION['state']['email_campaign']['objetives']['f_value']=$f_value;


    $where=sprintf(" where  `Store Key`=%d",$store_key);



    $filter_msg='';
    $wheref='';
    if ($f_field=='name' and $f_value!='')
        $wheref.=" and  `Email Template Postcard Name` like '".addslashes($f_value)."%'";

    $sql="select count(*) as total from `Email Template Postcard Dimension`     $where $wheref";

    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($result);
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total  from `Email Template Postcard Dimension`    $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $filtered=$row['total']-$total;
            $total_records=$row['total'];
        }
        mysql_free_result($result);
    }

    $rtext=sprintf(ngettext("%d scheme", "%d schemes", $total_records), $total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp='('._('Showing all').')';

    $_order=$order;
    $_dir=$order_direction;



    $order='`Email Template Postcard Name`';



    $current_postcard_key=0;

    if ($email_content_key) {
        $sql=sprintf("select `Email Content Template Postcard Key` from  `Email Content Dimension`  where `Email Content Key`=%d",$email_content_key);

        $res=mysql_query($sql);
        if ($row=mysql_fetch_assoc($res)) {

            $current_postcard_key= $row['Email Content Template Postcard Key'];
        }
    }
//   $sql="select *  from `Email Template Header Image Dimension`HI  left join `Image Dimension` I on (I.`Image Key`=HI.`Image Key`) $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";

    $sql="select *  from `Email Template Postcard Dimension` $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
//print $sql;
    $res = mysql_query($sql);
    $adata=array();
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {




        $delete="<img src='art/icons/cross.png'  alt='"._('Delete')."'  title='"._('Delete')."' />";

        if ($current_postcard_key==$row['Email Template Postcard Key']) {
            $used="<img   src='art/icons/accept.png'  alt='"._('Selected')."'  title='"._('Selected')."' />";
            $scheme_selected='Yes';
        } else {
            $used='<img style="cursor:pointer" onClick="save_select_postcard('.$row['Email Template Postcard Key'].')"  src="art/icons/accept_bw_hidden.png"  alt="Not in use"  title="'._('Click to select').'" />';
            $scheme_selected='No';

        }





        $adata[]=array(
                     'id'=>$row['Email Template Postcard Key'],

                     'name'=>$row['Email Template Postcard Name'],
                     'image'=>'<img src="image.php?id='.$row['Image Key'].'"  style="width: 300px;height : auto;"    />',
                     'used'=>$used,

                     'delete'=>$delete
                 );
    }
    mysql_free_result($res);
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

function upload_template_header_image() {

//print_r($_FILES);
    if (isset($_FILES['image']['tmp_name'])) {

        include_once('class.Image.php');
        $image_data=array(
                        'file'=>$_FILES['image']['tmp_name'],
                        'source_path'=>'',
                        'name'=>$_FILES['image']['name']
                    );


        $image=new Image('find',$image_data,'create');
        if (!$image->error) {


            $image->load_subjects();
            foreach($image->subjects as $image_subject) {
                if ($image_subject['Subject Type']=='Store Email Template Header' and $image_subject['Subject Key']==$_REQUEST['store_key']) {
                    $response= array('state'=>200,'image_key'=>$image->id);
                    echo json_encode($response);
                    return;
                }
            }




            if ($_REQUEST['name']=='') {
                $name=$image->data['Image Filename'];
            } else {
                $name=$_REQUEST['name'];
            }

            $sql=sprintf("insert into `Email Template Header Image Dimension` (`Email Template Header Image Name`,`Store Key`,`Image Key`) values (%s,%d,%d)",
                         prepare_mysql($name),
                         $_REQUEST['store_key'],
                         $image->id

                        );
            mysql_query($sql);
            $id=mysql_insert_id();

            $sql=sprintf("insert into `Image Bridge` values ('Store Email Template Header',%d,%d,'Yes',%s)",
                         $_REQUEST['store_key'],
                         $image->id,
                         prepare_mysql($_REQUEST['name'],false)
                        );
            mysql_query($sql);


            $response= array('state'=>200,'image_key'=>$image->id);
            echo json_encode($response);
            return;
        } else {
            $response= array('state'=>400,'msg'=>$image->msg);
            echo json_encode($response);
            return;
        }
    } else {
        $response= array('state'=>400,'msg'=>'no image');
        echo json_encode($response);
        return;
    }
}


function delete_postcard($data) {



    $sql=sprintf("select `Store Key`,`Image Key` from `Email Template Postcard Dimension`  where `Email Template Postcard Key`=%d",
                 $data['id']
                );
    $res=mysql_query($sql);
    if ($row=mysql_fetch_assoc($res)) {
        $store_key=$row['Store Key'];
        $image_key=$row['Image Key'];
    } else {
        $response= array(
                       'state'=>400,
                       'msg'=>'Postcard not found'
                   );
        echo json_encode($response);
        return;
    }




    $sql=sprintf("delete from `Email Template Postcard Dimension`  where `Email Template Postcard Key`=%d",

                 $data['id']
                );
    mysql_query($sql);
    $deleted=mysql_affected_rows();

    $sql=sprintf("delete from `Image Bridge`  where  `Subject Type`='Store Email Postcard'    and `Subject Key`=%d  and   `Image Key`=%d",
                 $store_key,
                 $image_key

                );
    mysql_query($sql);


    include_once('class.Image.php');
    $image=new Image($image_key);
    $image->delete();



    if ($deleted) {
        $response= array(
                       'state'=>200,
                       'action'=>'deleted'
                   );
        $sql=sprintf("update `Email Content Dimension` set `Email Content Template Postcard Key`=0   where  `Email Content Template Postcard Key`=%d ",
                     $data['id']);
        $res=mysql_query($sql);


    } else {
        $response= array(
                       'state'=>400,
                       'action'=>'nochange'
                   );
    }
    echo json_encode($response);


}

function upload_postcard() {

//print_r($_FILES);
    if (isset($_FILES['image']['tmp_name'])) {

        include_once('class.Image.php');
        $image_data=array(
                        'file'=>$_FILES['image']['tmp_name'],
                        'source_path'=>'',
                        'name'=>$_FILES['image']['name']
                    );


        $image=new Image('find',$image_data,'create');
        if (!$image->error) {


            $image->load_subjects();
            foreach($image->subjects as $image_subject) {
                if ($image_subject['Subject Type']=='Store Email Postcard' and $image_subject['Subject Key']==$_REQUEST['store_key']) {
                    $response= array('state'=>200,'image_key'=>$image->id);
                    echo json_encode($response);
                    return;
                }
            }




            if ($_REQUEST['name']=='') {
                $name=$image->data['Image Filename'];
            } else {
                $name=$_REQUEST['name'];
            }

            $sql=sprintf("insert into `Email Template Postcard Dimension` (`Email Template Postcard Name`,`Store Key`,`Image Key`) values (%s,%d,%d)",
                         prepare_mysql($name),
                         $_REQUEST['store_key'],
                         $image->id

                        );
            mysql_query($sql);
            $id=mysql_insert_id();

            $sql=sprintf("insert into `Image Bridge` values ('Store Email Postcard',%d,%d,'Yes',%s)",
                         $_REQUEST['store_key'],
                         $image->id,
                         prepare_mysql($_REQUEST['name'],false)
                        );
            mysql_query($sql);
//print $sql;

            $response= array('state'=>200,'image_key'=>$image->id);
            echo json_encode($response);
            return;
        } else {
            $response= array('state'=>400,'msg'=>$image->msg);
            echo json_encode($response);
            return;
        }
    } else {
        $response= array('state'=>400,'msg'=>'no image');
        echo json_encode($response);
        return;
    }
}


function delete_template_header_image($data) {



    $sql=sprintf("select `Store Key`,`Image Key` from `Email Template Header Image Dimension`  where `Email Template Header Image Key`=%d",
                 $data['id']
                );
    $res=mysql_query($sql);
    if ($row=mysql_fetch_assoc($res)) {
        $store_key=$row['Store Key'];
        $image_key=$row['Image Key'];
    } else {
        $response= array(
                       'state'=>400,
                       'msg'=>'Header Image not found'
                   );
        echo json_encode($response);
        return;
    }




    $sql=sprintf("delete from `Email Template Header Image Dimension`  where `Email Template Header Image Key`=%d",

                 $data['id']
                );
    mysql_query($sql);
    $deleted=mysql_affected_rows();

    $sql=sprintf("delete from `Image Bridge`  where  `Subject Type`='Store Email Template Header'    and `Subject Key`=%d  and   `Image Key`=%d",
                 $store_key,
                 $image_key

                );
    mysql_query($sql);


    include_once('class.Image.php');
    $image=new Image($image_key);
    $image->delete();



    if ($deleted) {
        $response= array(
                       'state'=>200,
                       'action'=>'deleted'
                   );
        $sql=sprintf("update `Email Content Dimension` set `Email Content Template Header Image Key`=0   where  `Email Content Template Header Image Key`=%d ",
                     $data['id']);
        $res=mysql_query($sql);


    } else {
        $response= array(
                       'state'=>400,
                       'action'=>'nochange'
                   );
    }
    echo json_encode($response);


}

function update_objetive($data){






}

?>
