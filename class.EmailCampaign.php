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


        }


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
            $sql=sprintf("insert into `Email Campaign Content Bridge`  values (%d,%d)",$this->id,$email_content_key);
            mysql_query($sql);
            $email_content_key=mysql_insert_id();


        } else {
            $this->error=true;
            $this->msg="Can not insert Email Campaign Dimension";
            // exit("$sql\n");
        }


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

    function get_subjects_serialized_array() {
        $content_keys=$this->get_content_data_keys();
        $subjects_array=array();
        if (count($content_keys)==1) {
            $sql=sprintf("select `Email Content Key`,`Email Content Subject` from  `Email Content Dimension`  where `Email Content Key`=%d",array_pop($content_keys));
            $res=mysql_query($sql);
            if ($row=mysql_fetch_assoc($res)) {

                $subjects_array[$row['Email Content Key']]=$row['Email Content Subject'];

            }
        }
        if (count($subjects_array)==0) {
            $subjects='';
        } else {
            $subjects=serialize($subjects_array);
        }


        return $subjects;
    }

    function get_contents_serialized_array() {
        $content_keys=$this->get_content_data_keys();
        $email_contents_array=array();
        if (count($content_keys)==1) {
            $sql=sprintf("select `Email Content Type`,`Email Content Key`,`Email Content Text`,`Email Content HTML` from  `Email Content Dimension`  where `Email Content Key`=%d",array_pop($content_keys));
            $res=mysql_query($sql);
            if ($row=mysql_fetch_assoc($res)) {
                if($row['Email Content Type']=='Plain'){
                    $email_contents_array[$row['Email Content Key']]=$row['Email Content Text'];
                }else{
                    $email_contents_array[$row['Email Content Key']]=array('Plain'=>$row['Email Content Text'],'HTML Template'=>$row['Email Content HTML']);

                }

            }
        }
        if (count($email_contents_array)==0) {
            $email_contents='';
        } else {
            $email_contents=serialize($email_contents_array);
        }


        return $email_contents;
    }

    function get_subject($escape=false) {
        $content_keys=$this->get_content_data_keys();
        if (count($content_keys)==0) {
            return '';
        }
        elseif(count($content_keys)==1) {
            $sql=sprintf("select `Email Content Subject` from  `Email Content Dimension`  where `Email Content Key`=%d",array_pop($content_keys));
            $res=mysql_query($sql);
            if ($row=mysql_fetch_assoc($res)) {
                
                if($escape=='addslashes')
                                return addslashes($row['Email Content Subject']);

                else
                return $row['Email Content Subject'];
            }
        }
    }
    
        function get_content_text() {
        $content_keys=$this->get_content_data_keys();
        if (count($content_keys)==0) {
            return '';
        }
        elseif(count($content_keys)==1) {
            $sql=sprintf("select `Email Content Text` from  `Email Content Dimension`  where `Email Content Key`=%d",array_pop($content_keys));
            $res=mysql_query($sql);
            if ($row=mysql_fetch_assoc($res)) {

                return $row['Email Content Text'];
            }
        }
    }

    function update_subject($value) {

        $content_keys=$this->get_content_data_keys();
        if (count($content_keys)==1) {
            $sql=sprintf("update `Email Content Dimension` set `Email Content Subject`=%s where `Email Content Key`=%d",
                         prepare_mysql($value),
                         array_pop($content_keys)
                        );
            mysql_query($sql);
        }
       $old_value=$this->data['Email Campaign Subjects'];
        $this->data['Email Campaign Subjects']=$this->get_subjects_serialized_array();
        $sql=sprintf("update `Email Campaign Dimension` set `Email Campaign Subjects`=%s where `Email Campaign Key`=%d",
                     prepare_mysql($this->data['Email Campaign Subjects']),
                     $this->id
                    );
        mysql_query($sql);
        if (mysql_affected_rows()>0) {
            $this->updated=true;
            $this->new_value=$this->get_subject();
        }
    }

    function update_content_text($value) {

        $content_keys=$this->get_content_data_keys();
        if (count($content_keys)==1) {
            $sql=sprintf("update `Email Content Dimension` set `Email Content Text`=%s where `Email Content Key`=%d",
                         prepare_mysql($value),
                         array_pop($content_keys)
                        );
            mysql_query($sql);
        }
       $old_value=$this->data['Email Campaign Contents'];
        $this->data['Email Campaign Contents']=$this->get_contents_serialized_array();
        $sql=sprintf("update `Email Campaign Dimension` set `Email Campaign Contents`=%s where `Email Campaign Key`=%d",
                     prepare_mysql($this->data['Email Campaign Contents']),
                     $this->id
                    );
        mysql_query($sql);
        if (mysql_affected_rows()>0) {
            $this->updated=true;
            $this->new_value=$this->get_content_text();
        }
    }




    function update_content_type($value) {
    
        if (!($value=='Plain' or $value=='HTML Template')) {
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
        $sql=sprintf("select * from `Customer List Dimension` where `Customer List Key`=%d",$list_key);
        $res=mysql_query($sql);
        if (!$customer_list_data=mysql_fetch_assoc($res)) {
            $this->error=true;
            $this->msg='Customer List not found';
            return;
        }
        $emails_already_in_the_mailing_list=0;
        $emails_added=0;
        $customer_without_email_address=0;
        $customer_dont_want_to_receive_email=0;
        $sent_to_customer_dont_want_to_receive_email=0;

        if ($customer_list_data['Customer List Type']=='Static') {

            $sql=sprintf("select `Customer Main Contact Name`,C.`Customer Key`,`Customer Main Plain Email`,`Customer Main Email Key`,`Customer Send Email Marketing` from `Customer List Customer Bridge` B left join `Customer Dimension` C on (B.`Customer Key`=C.`Customer Key`) where `Customer List Key`=%d ",
                         $list_key
                        );




        } else {//dynamic

            $where='where true';
            $table='`Customer Dimension` C ';

            $tmp=preg_replace('/\\\"/','"',$customer_list_data['Customer List Metadata']);
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


    function insert_email_to_mailing_list($data) {

        if (!array_key_exists('Email Key',$data)) {
            $email=new Email('email',$data['Email Address']);
            if ($email->id)
                $data['Email Key']=$email->id;
            else
                $data['Email Key']=false;
        }
        $sql=sprintf("insert into `Email Campaign Mailing List` (`Email Campaign Key`,`Email Key`,`Email Address`,`Email Contact Name`,`Customer Key`)
                     values (%d,%s,%s,%s,%s)",
                     $this->id,
                     prepare_mysql($data['Email Key']),
                     prepare_mysql($data['Email Address']),
                     prepare_mysql($data['Email Contact Name'],false),
                     prepare_mysql($data['Customer Key'])

                    );
        mysql_query($sql);
        return mysql_affected_rows();

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

}
?>
