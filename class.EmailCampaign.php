<?php
/*
 File: EmailCampaign.php

 This file contains the Email Campaign Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2010, Inikoo

 Version 2.0
*/
include_once('class.DB_Table.php');
include_once('class.Email.php');

class EmailCampaign extends DB_Table {

    var $new=false;
    function EmailCampaign($arg1=false,$arg2=false,$arg3=false) {
        $this->table_name='Email Campaign';
        $this->ignore_fields=array(
                                 'Email Campaign Key',
                                 'Email Campaign Maximum Emails',
                                 'Email Campaign Last Updated Date',
                                 'Email Campaign Creation Date',
                                 'Email Campaign Date'
                             );

        if (!$arg1 and !$arg2) {
            $this->error=true;
            $this->msg='No arguments';
        }
        if (is_numeric($arg1)) {
            $this->get_data('id',$arg1);
            return;
        }



        if (is_array($arg2) and preg_match('/find/i',$arg1)) {
            $this->find($arg2,$arg3);
            return;
        }


        $this->get_data($arg1,$arg2);

    }

    function get_data($tipo,$tag) {


        $sql=sprintf("select * from `Email Campaign Dimension` where  `Email Campaign Key`=%d",$tag);

        $result =mysql_query($sql);
        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->id=$this->data['Email Campaign Key'];
            $this->content_data=$this->get_contents_array();
            $this->content_keys=$this->get_content_data_keys();

        }


    }

  
    
    
    
    function ready_to_send(){
    $ready_to_send=true;
    
        
    
     if(!$this->data['Number of Emails']){
    
    return false;
    }
    
    if(!count($this->content_keys)){
    
    return false;
    }
    
    foreach($this->content_data as $content_data) {
        if($content_data['subject']==''){
             $ready_to_send=false;
        }
        
        if($content_data['type']=='Plain'){
          if($content_data['plain']==''){
             $ready_to_send=false;
            }    
        }elseif($content_data['type']=='HTML'){
        if($content_data['html']==''){
             $ready_to_send=false;
        }
        }else{
         if(!count($content_data['paragraphs'])){
         $ready_to_send=false;
         }
        }
        }
        
        
        
        
        return $ready_to_send;
        
        }
    
    function find($raw_data,$options) {




        if (isset($raw_data['editor'])) {
            foreach($raw_data['editor'] as $key=>$value) {
                if (array_key_exists($key,$this->editor))
                    $this->editor[$key]=$value;
            }
        }

        $this->found=false;
        $this->found_key=false;


        $sql=sprintf("select `Email Campaign Key` from `Email Campaign Dimension` where `Email Campaign Store Key`=%d and `Email Campaign Name`=%s",
                     $raw_data['Email Campaign Store Key'],
                     prepare_mysql($raw_data['Email Campaign Name'])
                    );
        // print $sql;
        $result =mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->found_key=$row['Email Campaign Key'];
            $this->found=true;

        }


        $create='';
        $update='';
        if (preg_match('/create/i',$options)) {
            $create='create';
        }
        if (preg_match('/update/i',$options)) {
            $update='update';
        }


        if ($create and !$this->found) {
            $this->create($raw_data);
        }

    }
    function create($raw_data) {

        $data=$this->base_data();


        foreach($raw_data as $key=>$value) {
            if (array_key_exists($key,$data))



                if (is_array($value))
                    $data[$key]=serialize($value);
                else
                    $data[$key]=_trim($value);


        }

        $data['Email Campaign Creation Date']=date("Y-m-d H:i:s");
        $data['Email Campaign Last Updated Date']=$data['Email Campaign Creation Date'];
        $data['Email Campaign Status']='Creating';


        $keys='(';
        $values='values(';
        foreach($data as $key=>$value) {
            $keys.="`$key`,";
            if ($key=='Email Campaign Objective' or $key='Email Campaign Recipients Preview' or $key='Email Campaign Scope')
                $values.=prepare_mysql($value,false).",";
            else
                $values.=prepare_mysql($value).",";
        }
        $keys=preg_replace('/,$/',')',$keys);
        $values=preg_replace('/,$/',')',$values);
        $sql=sprintf("insert into `Email Campaign Dimension` %s %s",$keys,$values);


        if (mysql_query($sql)) {
            $this->id=mysql_insert_id();
            $this->get_data('id',$this->id);
            $this->new=true;

            $sql=sprintf("insert into `Email Content Dimension` (`Email Content Subject`,`Email Content Text`,`Email Content HTML`) values ('','','')");
            mysql_query($sql);
            $email_content_key=mysql_insert_id();


            $paragraph_data=array(
                                array('title'=>'Donec eleifend nunc ut libero fringilla posuere','subtitle'=>'Duis mauris massa','content'=>'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque pretium sapien nec augue dictum tincidunt. Phasellus in vulputate nibh. Morbi ac odio lorem. Suspendisse ut nibh vel nibh malesuada ullamcorper vitae sed magna. Aliquam erat volutpat.'),
                                array('title'=>'Nullam interdum posuere ultricies','subtitle'=>'In sagittis augue tellus','content'=>'Morbi porttitor posuere venenatis. Aliquam tincidunt scelerisque porttitor. Vivamus vulputate tortor ut augue eleifend semper. Curabitur venenatis placerat porta. Aliquam semper magna vitae libero porttitor vulputate.'),
                                array('title'=>'Pellentesque sed sapien','subtitle'=>'Aliquam urna dui','content'=>'Quisque in purus eu purus malesuada porttitor. Proin sed arcu nisi. Ut in enim arcu. Cras consectetur commodo dolor, id tempus tortor imperdiet quis. Donec iaculis interdum congue. Nullam ultrices hendrerit lectus, vitae lobortis magna sagittis et.')

                            );

            foreach($paragraph_data as $_paragraph_data_key=>$_paragraph_data) {

                $sql=sprintf("insert into `Email Content Paragraph Dimension` (

                             `Email Content Key` ,
                             `Paragraph Order` ,
                             `Paragraph Type` ,
                             `Paragraph Title` ,
                             `Paragraph Subtitle` ,
                             `Paragraph Content`
                             ) values (%d,%d,%s,%s,%s,%s)",

                             $email_content_key,
                             $_paragraph_data_key,
                             prepare_mysql('Main'),
                             prepare_mysql($_paragraph_data['title']),
                             prepare_mysql($_paragraph_data['subtitle']),
                             prepare_mysql($_paragraph_data['content'])
                            );
            mysql_query($sql);
                

            }

            $sql=sprintf("insert into `Email Campaign Content Bridge`  values (%d,%d)",$this->id,$email_content_key);
            mysql_query($sql);
            $email_content_key=mysql_insert_id();
            $this->get_data('id',$this->id);
            $store=new Store($this->data['Email Campaign Store Key']);
            $store->update_email_campaign_data();

        } else {
            $this->error=true;
            $this->msg="Can not insert Email Campaign Dimension";
            // exit("$sql\n");
        }


    }
    
    function add_email_address_manually($data) {
        $data['Email Address']=_trim($data['Email Address']);
        if ($data['Email Address']=='') {
            $this->error=true;
            $this->msg=_('Wrong Email Address');
            return;
        }

        $sql=sprintf("select `Email Campaign Mailing List Key` from `Email Campaign Mailing List` where `Email Campaign Key`=%d and `Email Address`=%s ",
                     $this->id,
                     prepare_mysql($data['Email Address'])
                    );
        $res=mysql_query($sql);
      //  print $sql;
        if ($row=mysql_fetch_assoc($res)) {
            $this->error=true;
            $this->msg=_('Email Address already in mailing list');
            return;

        }

        $data['Customer Key']=false;

        if ($this->insert_email_to_mailing_list($data)>0) {
            $this->updated=true;
            $this->update_number_emails();
            $this->update_recipients_preview();
        } else {
            $this->msg=_('Can not add email to mailing list');
        }

    }
    function add_emails_from_list($list_key,$force_send_to_customer_who_dont_want_to_receive_email=false) {
        $sql=sprintf("select * from `List Dimension` where `List Key`=%d",$list_key);
        $res=mysql_query($sql);
        if (!$customer_list_data=mysql_fetch_assoc($res)) {
            $this->error=true;
            $this->msg='List not found';
            return;
        }
        $emails_already_in_the_mailing_list=0;
        $emails_added=0;
        $customer_without_email_address=0;
        $customer_dont_want_to_receive_email=0;
        $sent_to_customer_dont_want_to_receive_email=0;

        if ($customer_list_data['List Type']=='Static') {

            $sql=sprintf("select `Customer Main Contact Name`,C.`Customer Key`,`Customer Main Plain Email`,`Customer Main Email Key`,`Customer Send Email Marketing` from `List Customer Bridge` B left join `Customer Dimension` C on (B.`Customer Key`=C.`Customer Key`) where `List Key`=%d ",
                         $list_key
                        );




        } else {//dynamic

            $where='where true';
            $table='`Customer Dimension` C ';

            $tmp=preg_replace('/\\\"/','"',$customer_list_data['List Metadata']);
            $tmp=preg_replace('/\\\\\"/','"',$tmp);
            $tmp=preg_replace('/\'/',"\'",$tmp);

            $raw_data=json_decode($tmp, true);

            list($where,$table)=customers_awhere($raw_data);

            $where.=sprintf(' and `Customer Store Key`=%d ',$this->data['Email Campaign Store Key'] );



            $sql=sprintf("select `Customer Main Contact Name`,C.`Customer Key`,`Customer Main Plain Email`,`Customer Main Email Key`,`Customer Send Email Marketing` from $table $where  "

                        );

        }




        $res=mysql_query($sql);
        while ($row=mysql_fetch_assoc($res)) {
            if (!$row['Customer Main Email Key'] or $row['Customer Main Plain Email']=='') {
                $customer_without_email_address++;
                continue;
            }
            if ($row['Customer Send Email Marketing']=='No') {
                $customer_dont_want_to_receive_email++;
                if (!$force_send_to_customer_who_dont_want_to_receive_email)
                    continue;
                else
                    $sent_to_customer_dont_want_to_receive_email++;
            }

            $data['Email Address']=$row['Customer Main Plain Email'];
            $data['Email Key']=$row['Customer Main Email Key'];
            $data['Email Contact Name']=$row['Customer Main Contact Name'];

            $data['Customer Key']=$row['Customer Key'];
            $result=$this->insert_email_to_mailing_list($data);
            if ($result>0) {
                $emails_added++;

            } else {
                $emails_already_in_the_mailing_list++;

            }

        }


        $msg='<table>';
        $msg.='<tr><td>'._('Email Address Added').':</td><td>'.number($emails_added).'</td></tr>';

        if ($customer_without_email_address) {
            $msg.='<tr><td>'._('Customers without email').':</td><td>'.$customer_without_email_address.'</td></tr>';
        }
        if ($customer_dont_want_to_receive_email) {
            $msg.='<tr><td>'._('Skipped (Customer preferences)').':</td><td>'.$customer_dont_want_to_receive_email.'</td></tr>';
        }
        if ($emails_already_in_the_mailing_list) {
            $msg.='<tr><td>'._('Skipped (Email already added)').':</td><td>'.$emails_already_in_the_mailing_list.'</td></tr>';
        }
        $msg.='</table>';
        $this->msg=$msg;


        $this->updated=true;
        $this->update_number_emails();
        $this->update_recipients_preview();



    }
    function add_paragraph($email_content_key,$paragraph_data){
         
           $sql=sprintf("select `Email Paragraph Key`,`Paragraph Order` from `Email Content Paragraph Dimension` where `Email Content Key`=%d order by `Paragraph Order` desc limit 1",$email_content_key);
        $res=mysql_query($sql);
        $last_order_index=1;
        if ($row=mysql_fetch_assoc($res)) {
            $last_order_index=$row['Paragraph Order']+1;
            
        }
         
         $sql=sprintf("insert into `Email Content Paragraph Dimension` (

                             `Email Content Key` ,
                             `Paragraph Order` ,
                             `Paragraph Type` ,
                             `Paragraph Title` ,
                             `Paragraph Subtitle` ,
                             `Paragraph Content`
                             ) values (%d,%d,%s,%s,%s,%s)",

                             $email_content_key,
                             $last_order_index,
                             prepare_mysql($paragraph_data['type']),
                             prepare_mysql($paragraph_data['title']),
                             prepare_mysql($paragraph_data['subtitle']),
                             prepare_mysql($paragraph_data['content'])
                            );
            mysql_query($sql);
    
    }
    function assign_email_content_key(){
    
        return $this->get_first_content_key();
    }
    function delete() {
        if ($this->data['Email Campaign Status']=='Creating') {
            $content_keys=$this->get_content_data_keys();
            $sql=sprintf("delete from `Email Campaign Content Bridge` where `Email Campaign Key`=%d",$this->id);
            mysql_query($sql);
            $sql=sprintf("delete from `Email Campaign Dimension` where `Email Campaign Key`=%d",$this->id);
            mysql_query($sql);
            $sql=sprintf("delete from `Email Campaign Mailing List` where `Email Campaign Key`=%d",$this->id);
            mysql_query($sql);
            $sql=sprintf("delete from `Email Content Dimension` where `Email Content Key` in (%s)",join(',',$content_keys));
            mysql_query($sql);
            $this->updated=true;

        } else {
            $this->error=true;
            $this->msg='Email Campaign can not be deleted';
        }

    }
    function delete_paragraph($email_content_key,$paragraph_key){
        $sql=sprintf("delete from  `Email Content Paragraph Dimension` where `Email Paragraph Key`=%d ",$paragraph_key);
            mysql_query($sql);
           // print "$sql";
        if(mysql_affected_rows()){
            $this->updated=true;
        }
            
    }

    function get_first_content_key() {
        $tmp=$this->content_keys;
        return array_shift($tmp);
    }
    function get_content($content_key) {

        return $this->content_data[$content_key];


    }
    function get_content_data_keys() {
        $sql=sprintf("select `Email Content Key` from `Email Campaign Content Bridge` where `Email Campaign Key`=%d"
                     ,$this->id
                    );
        $content_keys=array();
        $result=mysql_query($sql);
        while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $content_keys[$row['Email Content Key']]= $row['Email Content Key'];
        }
        return $content_keys;

    }
    function get($key) {



        switch ($key) {
        case('Email Campaign Content Type'):
            return $this->get_content_type();
            break;
        default:
            if (isset($this->data[$key]))
                return $this->data[$key];
        }
        return false;
    }

    function get_content_type() {
        $content_keys=$this->get_content_data_keys();
        if (count($content_keys)==0) {
            return 'Unknown';
        }
        elseif(count($content_keys)==1) {
            $sql=sprintf("select `Email Content Type` from  `Email Content Dimension`  where `Email Content Key`=%d",array_pop($content_keys));
            $res=mysql_query($sql);
            if ($row=mysql_fetch_assoc($res)) {

                return $row['Email Content Type'];
            }
        }

    }
    function get_contents_array() {
        $content_keys=$this->get_content_data_keys();
        $email_contents_array=array();
        if (count($content_keys)==1) {
            $sql=sprintf("select `Email Content Type`,`Email Content Subject`,`Email Content Type`,`Email Content Key`,`Email Content Text`,`Email Content HTML`,`Email Content Header Image Source`,`Email Content Metadata` from  `Email Content Dimension`  where `Email Content Key`=%d",array_pop($content_keys));
            $res=mysql_query($sql);
            if ($row=mysql_fetch_assoc($res)) {
           

                    $sql2=sprintf("select * from `Email Content Paragraph Dimension` where `Email Content Key`=%d order by `Paragraph Order`",$row['Email Content Key']);
                    $res2=mysql_query($sql2);
                    $paragraph_data=array();
                    while ($row2=mysql_fetch_assoc($res2)) {
                        $paragraph_data[$row2['Email Paragraph Key']]=array(
                                    'order'=>$row2['Paragraph Order'],
                                    'type'=>$row2['Paragraph Type'],
                                    'title'=>$row2['Paragraph Title'],
                                    'subtitle'=>$row2['Paragraph Subtitle'],
                                    'content'=>$row2['Paragraph Content']
                                );
                    }

                    $email_contents_array[$row['Email Content Key']]=array(
                                'type'=>$row['Email Content Type'],
                                'subject'=>$row['Email Content Subject'],
                                'plain'=>$row['Email Content Text'],
                                'html'=>$row['Email Content HTML'],
                                'paragraphs'=>$paragraph_data,
                                'header_src'=>$row['Email Content Header Image Source']);

                

            }
        }


        return $email_contents_array;
    }
    function get_subject($email_content_key) {
       $subject='';
            $sql=sprintf("select `Email Content Subject` from  `Email Content Dimension`  where `Email Content Key`=%d",$email_content_key);
            $res=mysql_query($sql);
            if ($row=mysql_fetch_assoc($res)) {
                     $subject=$row['Email Content Subject'];
            }
    return $subject;
    }
    function get_first_mailing_list_key(){
    
     $sql=sprintf("select `Email Campaign Mailing List Key` from `Email Campaign Mailing List` where  `Email Campaign Key`=%d limit 1",

        $this->id
        );
        $res=mysql_query($sql);
        if($row=mysql_fetch_assoc($res)){
         return $row['Email Campaign Mailing List Key'];
        
        }else{
            return 0;
        
        }
        
        
    }
    function get_recipient_email($email_mailing_list_key=false){
     if(!$email_mailing_list_key)
            $email_mailing_list_key=$this->get_first_mailing_list_key();
    
    $sql=sprintf("select `Email Address` from `Email Campaign Mailing List` where `Email Campaign Mailing List Key`=%d and `Email Campaign Key`=%d",
        $email_mailing_list_key,
        $this->id
        );
        $res=mysql_query($sql);
        if($row=mysql_fetch_assoc($res)){
            return $row['Email Address'];
        }else{
            return '';
        }
    
    }
    function get_email_mailing_list_key_from_index($index){
    
   
    
     $sql=sprintf("select `Email Campaign Mailing List Key` from `Email Campaign Mailing List` where `Email Campaign Key`=%d limit %d, 1 ",
     
        $this->id,
        ($index-1)
        );
      
        $res=mysql_query($sql);
        if($row=mysql_fetch_assoc($res)){
            return $row['Email Campaign Mailing List Key'];
    
    }else{
        return 0;
    }
    
    
    }
    
    
    
    function get_html_template_body($email_content_key){
// require('external_libs/Smarty/Smarty.class.php');
$smarty = new Smarty();
$smarty->template_dir = 'templates';
$smarty->compile_dir = 'server_files/smarty/templates_c';
$smarty->cache_dir = 'server_files/smarty/cache';
$smarty->config_dir = 'server_files/smarty/configs';

$store=new Store($this->data['Email Campaign Store Key']);
$smarty->assign('paragraphs',$this->content_data[$email_content_key]['paragraphs']);


$smarty->assign('store',$store);
$output = $smarty->fetch('email_basic.tpl');
    
        return $output ;
    }
    
    
    function get_message_data($email_mailing_list_key=false){
        
        $this->get_data('id',$this->id);
        
        if(!$email_mailing_list_key)
            $email_mailing_list_key=$this->get_first_mailing_list_key();
        include_once('class.LightCustomer.php');
        
        $sql=sprintf("select * from `Email Campaign Mailing List` where `Email Campaign Mailing List Key`=%d and `Email Campaign Key`=%d",
        $email_mailing_list_key,
        $this->id
        );
        $res=mysql_query($sql);
            $plain='';
        $html='';
        $to='';
        if($row=mysql_fetch_assoc($res)){
        
    $to=$row['Email Address'];
        
          $email_content_key=$row['Email Content Key'];
            $customer=new LightCustomer($row['Customer Key']);
            if(!$customer->id){
                $customer->data['Customer Main Contact Name']=$row['Email Contact Name'];
                $customer->data['Customer Name']=$row['Email Contact Name'];
                 $customer->data['Customer Type']='person';
                 
            }
            
            switch ($type=$this->content_data[$email_content_key]['type']) {
                case 'Plain':
                    $plain=   nl2br($this->content_data[$email_content_key]['plain']);                     
                    
                  case 'HTML Template':
                    $plain=   nl2br($this->content_data[$email_content_key]['plain']); 
                    $html=  $this->get_html_template_body($email_content_key);

                    break;
                default:
                    
                    break;
            }
         
            if(preg_match_all('/\%[a-z]+\%/',$plain,$matches)){
                foreach($matches[0] as $match){
                    $plain=preg_replace('/'.$match.'/',$customer->get(preg_replace('/\%/','',$match)),$plain);
                }
            }
            if(preg_match_all('/\%[a-z]+\%/',$plain,$matches)){
                foreach($matches[0] as $match){
                    $html=preg_replace('/'.$match.'/',$customer->get(preg_replace('/\%/','',$match)),$html);
                }
            }
           $subject=$this->get_subject($email_content_key); 
        }else{
            $plain= 'Error recipient not associated with mailing list';
                        $html= 'Error recipient not associated with mailing list';
            $type='Plain';
            $subject='';
        }    
    
        
    
        return array('subject'=>$subject,'plain'=>$plain,'html'=>$html,'type'=>$type,'to'=>$to);
    }
    function get_content_text($email_content_key) {
        $content_text='';
        $sql=sprintf("select `Email Content Text` from  `Email Content Dimension`  where `Email Content Key`=%d",$email_content_key);
        $res=mysql_query($sql);
        if ($row=mysql_fetch_assoc($res)) {

            $content_text= $row['Email Content Text'];
        }
        return $content_text;
    }
    function insert_email_to_mailing_list($data) {

        if (!array_key_exists('Email Key',$data)) {
            $email=new Email('email',$data['Email Address']);
            if ($email->id)
                $data['Email Key']=$email->id;
            else
                $data['Email Key']=false;
        }
        
        $email_content_key=$this->assign_email_content_key();
        
        $sql=sprintf("insert into `Email Campaign Mailing List` (`Email Campaign Key`,`Email Key`,`Email Address`,`Email Contact Name`,`Customer Key`,`Email Content Key`)
                     values (%d,%s,%s,%s,%s,%d)",
                     $this->id,
                     prepare_mysql($data['Email Key']),
                     prepare_mysql($data['Email Address']),
                     prepare_mysql($data['Email Contact Name'],false),
                     prepare_mysql($data['Customer Key']),
                     $email_content_key

                    );
        mysql_query($sql);
      //  print $sql;
        return mysql_affected_rows();

    }
    function move_paragraph_to_the_end($email_content_key,$paragraph_key) {
        $sql=sprintf("select `Email Paragraph Key`,`Paragraph Order` from `Email Content Paragraph Dimension` where `Email Content Key`=%d order by `Paragraph Order` desc limit 1",$email_content_key);
        $res=mysql_query($sql);
        $last_order_index=1;
        if ($row=mysql_fetch_assoc($res)) {
            $last_order_index=$row['Paragraph Order']+1;
            $last_paragraph_key=$row['Email Paragraph Key'];
        }
        if ($last_paragraph_key!=$paragraph_key) {

            $sql=sprintf("update `Email Content Paragraph Dimension`  set `Paragraph Order`=%d where `Email Paragraph Key`=%d ",$last_order_index,$paragraph_key);

            mysql_query($sql);
            if (mysql_affected_rows()) {
                $this->updated;
            }

        }


    }
    function move_paragraph_before_target($email_content_key,$paragraph_key,$target_key) {


        if ($target_key==0) {

            return $this->move_paragraph_to_the_end($email_content_key,$paragraph_key);
        }

        $sql=sprintf("select `Email Paragraph Key`,`Paragraph Order` from `Email Content Paragraph Dimension` where `Email Content Key`=%d order by `Paragraph Order`",$email_content_key);
        $res=mysql_query($sql);
        $current_order=array();
        $i=1;
        $j=1;

        $new_order=array();
        while ($row=mysql_fetch_assoc($res)) {
            $current_order[$row['Email Paragraph Key']]=$j++;
            if ($row['Email Paragraph Key']==$paragraph_key) {
                continue;
            }
            if ($row['Email Paragraph Key']==$target_key) {
                $new_order[$paragraph_key]=$i++;
            }
            $new_order[$row['Email Paragraph Key']]=$i++;


        }
        foreach($new_order as $_paragraph_key=>$paragraph_order) {
            $sql=sprintf("update `Email Content Paragraph Dimension`  set `Paragraph Order`=%d where `Email Paragraph Key`=%d ",$paragraph_order,$_paragraph_key);
            mysql_query($sql);

        }



    }
    function update_subject($value,$email_content_key) {

   
            $sql=sprintf("update `Email Content Dimension` set `Email Content Subject`=%s where `Email Content Key`=%d",
                         prepare_mysql($value),
                        $email_content_key
                        );
            mysql_query($sql);
       
    
        if (mysql_affected_rows()>0) {
            $this->updated=true;
            $this->new_value=$value;
        }
    }
    function update_content_text($value,$email_content_key) {



        $sql=sprintf("update `Email Content Dimension` set `Email Content Text`=%s where `Email Content Key`=%d",
                     prepare_mysql($value),
                     $email_content_key
                    );
        mysql_query($sql);


        if (mysql_affected_rows()>0) {
            $this->updated=true;
            $this->new_value=$this->get_content_text($email_content_key);
        }
    }
    function update_content_type($value) {




        if (!($value=='Plain' or $value=='HTML Template' or $value=='HTML')) {
            $this->error;
            $this->msg='Wrong email content type '.$value;
            return;
        }
        $content_keys=$this->get_content_data_keys();
        $sql=sprintf("update `Email Content Dimension` set `Email Content Type`=%s where `Email Content Key` in (%s)",
                     prepare_mysql($value),
                     join(',',$content_keys)
                    );
        //print $sql;
        mysql_query($sql);
        if (mysql_affected_rows()>0) {
            $old_value=$this->data['Email Campaign Content Type'];
            $this->data['Email Campaign Content Type']=$this->get_content_type();
            $sql=sprintf("update `Email Campaign Dimension` set `Email Campaign Content Type`=%s where `Email Campaign Key`=%d",
                         prepare_mysql($this->data['Email Campaign Content Type']),
                         $this->id
                        );
            mysql_query($sql);
            if (mysql_affected_rows()>0) {
                $this->updated=true;
                $this->new_value=$this->data['Email Campaign Content Type'];
            }
        }





    }
    function update_number_emails() {
        $this->data['Number of Emails']=0;
        $sql=sprintf("select count(*) as number from `Email Campaign Mailing List` where `Email Campaign Key`=%d",$this->id);
        $res=mysql_query($sql);
        if ($row=mysql_fetch_assoc($res)) {
            $this->data['Number of Emails']=$row['number'];
        }
        $sql=sprintf("update `Email Campaign Dimension` set `Number of Emails`=%d where `Email Campaign Key`=%d",
                     $this->data['Number of Emails'],
                     $this->id);
        mysql_query($sql);
    }
    function update_recipients_preview() {
        $this->data['Email Campaign Recipients Preview']='';
        $sql=sprintf("select `Email Address` from `Email Campaign Mailing List` where `Email Campaign Key`=%d",$this->id);
        $res=mysql_query($sql);
        $num_previews_emails=0;
        while ($row=mysql_fetch_assoc($res)) {
            $num_previews_emails++;
            $this->data['Email Campaign Recipients Preview'].=', '.$row['Email Address'];
            if (strlen($this->data['Email Campaign Recipients Preview'])>250 and $this->data['Number of Emails']-$num_previews_emails>1)
                break;
        }
        $num_emails_not_previewed=$this->data['Number of Emails']-$num_previews_emails;
        if ($num_emails_not_previewed>0) {
            $this->data['Email Campaign Recipients Preview'].=", ... $num_emails_not_previewed "._('more').' (<a href="email_campaign_mailing_list.php?id='.$this->id.'">'._('View all').'</a>)';
        } else {
            $this->data['Email Campaign Recipients Preview'].=' (<a href="email_campaign_mailing_list.php?id='.$this->id.'">'._('Manage Recipients').'</a>)';

        }

        $this->data['Email Campaign Recipients Preview']=preg_replace('/^\,\s*/','',$this->data['Email Campaign Recipients Preview']);
        $sql=sprintf("update `Email Campaign Dimension` set `Email Campaign Recipients Preview`=%s where `Email Campaign Key`=%d",
                     prepare_mysql($this->data['Email Campaign Recipients Preview']),
                     $this->id);
        mysql_query($sql);
    }
    function update_field_switcher($field,$value,$options='') {


        switch ($field) {
        case('Email Campaign Content Text'):
            $this->update_content_text($value);
            break;
        case('Email Campaign Subject'):
            $this->update_subject($value);
            break;
        case('Email Campaign Content Type'):
            $this->update_content_type($value);
            break;
        default:
            $base_data=$this->base_data();
            if (array_key_exists($field,$base_data)) {
                if ($value!=$this->data[$field]) {

                    $this->update_field($field,$value,$options);
                }
            }

        }
    }
    function update_paragraph($email_content_key,$paragraph_key,$data) {



        $sql=sprintf("update `Email Content Paragraph Dimension` set `Paragraph Title`=%s,`Paragraph Subtitle`=%s,`Paragraph Content`=%s where `Email Paragraph Key`=%d ",
                     prepare_mysql($data['title']),
                     prepare_mysql($data['subtitle']),
                     prepare_mysql($data['content']),
                     $paragraph_key);
        mysql_query($sql);

        if (mysql_affected_rows()) {
            $this->updated=true;
        }



    }
  }
?>
