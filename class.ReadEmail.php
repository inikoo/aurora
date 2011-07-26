<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/
include_once('class.DB_Table.php');
include_once('class.Email.php');
include_once('class.Customer.php');

class ReadEmail extends DB_Table {

    var $emails=array();

    function ReadEmail() {


    }

    function read_customer_communications($store_key) {
        $this->store_key=$store_key;
        $sql=sprintf("select `Email Address`,E.`Email Credentials Key`,`Login`,`Password`,`Incoming Mail Server`  from `Email Credentials Dimension` E left join `Email Credentials Scope Bridge` SB on (SB.`Email Credentials Key`=E.`Email Credentials Key`) left join `Email Credentials Store Bridge` SoB on (SoB.`Email Credentials Key`=E.`Email Credentials Key`) where `Scope`=%s and `Store Key`=%d  ",
                     "'Customer Communications'",
                     $store_key

                    );

        $res=mysql_query($sql);
        while ($row=mysql_fetch_assoc($res)) {
            $this->account_email_addresses[]=$row['Email Address'];
            $account_data[]=array('server'=>$row['Incoming Mail Server'],'login'=>$row['Login'],'password'=>$row['Password'],'email_credentials_key'=>$row['Email Credentials Key']);


        }

        foreach($account_data as $account) {
            $this->read_mailbox('customer_communication','',$account['server'],$account['login'],$account['password'],$account['email_credentials_key']);

        }


    }


    function getdecodevalue($message,$coding) {
        if ($coding == 0) {
            $message = imap_8bit($message);
        }
        elseif ($coding == 1) {
            $message = imap_8bit($message);
        }
        elseif ($coding == 2) {
            $message = imap_binary($message);
        }
        elseif ($coding == 3) {
            $message=imap_base64($message);
        }
        elseif ($coding == 4) {
            $message = imap_qprint($message);
        }
        elseif ($coding == 5) {
            $message = imap_base64($message);
        }
        return $message;
    }
    function transformHTML($str) {
        if ((strpos($str,"<HTML") < 0) || (strpos($str,"<html")    < 0)) {
            $makeHeader = "<html><head><meta http-equiv=\"Content-Type\"    content=\"text/html; charset=iso-8859-1\"></head>\n";
            if ((strpos($str,"<BODY") < 0) || (strpos($str,"<body")    < 0)) {
                $makeBody = "\n<body>\n";
                $str = $makeHeader . $makeBody . $str ."\n</body></html>";
            } else {
                $str = $makeHeader . $str ."\n</html>";
            }
        } else {
            $str = "<meta http-equiv=\"Content-Type\" content=\"text/html;    charset=iso-8859-1\">\n". $str;
        }
        return $str;
    }
    function get_mime_type(&$structure) {
        $primary_mime_type = array("TEXT", "MULTIPART","MESSAGE", "APPLICATION", "AUDIO","IMAGE", "VIDEO", "OTHER");
        if ($structure->subtype) {
            return $primary_mime_type[(int) $structure->type] . '/' .$structure->subtype;
        }
        return "TEXT/PLAIN";
    }
    function get_part($stream, $msg_number, $mime_type, $structure = false,$part_number    = false) {

        //  print "+++ $part_number \n";


        if (!$structure) {
            $structure = imap_fetchstructure($stream, $msg_number);
        }
        if ($structure) {
            if ($mime_type == $this->get_mime_type($structure)) {
                if (!$part_number) {
                    $part_number = "1";
                }
                $text = imap_fetchbody($stream, $msg_number, $part_number);

                return $this->getdecodevalue($text,$structure->encoding);


            } else {
                //  print "---++---  ".$this->get_mime_type($structure)."\n";
            }

            if ($structure->type == 1) { /* multipart */
                while (list($index, $sub_structure) = each($structure->parts)) {


                    if ($part_number) {
                        $prefix = $part_number . '.';
                    } else {
                        $prefix='';
                    }



                    $data = $this->get_part($stream, $msg_number, $mime_type, $sub_structure, $prefix.($index + 1));
                    if ($data) {
                        return $data;
                    }
                }
            }
        }
        return false;
    }



    function process_customer_communication($mbox,$message_number,$overview,$email_credentials_key) {


        // print_r($overview);

        $from=false;
        if (property_exists($overview, 'from')) {
            if (preg_match('/\<.+\@.+\>/',$overview->from,$match)) {
                $from=preg_replace('/\<|\>/','',$match[0]);
            }
            elseif(preg_match('/^\s*[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})\s*$/i',$overview->from,$match)) {
                $from=$match[0];
            }
            else {
                print "error can not read ".$overview->from."\n";
                exit();
            }
        }

        $to=false;
        if (property_exists($overview, 'to')) {
            if (preg_match('/\<.+\@.+\>/',$overview->to,$match)) {
                $to=preg_replace('/\<|\>/','',$match[0]);
            }
            elseif(preg_match('/^\s*[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})\s*$/i',$overview->to,$match)) {
                $to=$match[0];
            }
            else {
                print "error can not read ".$overview->to."\n";
                exit();
            }
        }


        $date=date("Y-m-d H:i:s",$overview->udate);

        // print "from: $from\nto: $to\ndate:$date";

        //exit;
        $done=false;

        if (!in_array($from,$this->account_email_addresses)) {

            $sql=sprintf("select `Customer Key` from    `Email Bridge` B left join `Email Dimension` E on (B.`Email Key`=E.`Email Key`) left join `Customer Dimension` C on (`Customer Key`=B.`Subject Key`) where `Subject Type`='Customer' and E.`Email`=%s and `Customer Store Key`=%d",

                         prepare_mysql($from),
                         $this->store_key

                        );

            $res=mysql_query($sql);
            while ($row=mysql_fetch_assoc($res)) {

                $customer=new Customer($row['Customer Key']);
                list($message,$has_attachements)=$this->get_email_message_body($mbox,$message_number);

                // print_r($overview);
                //print $message;

                if ($has_attachements)
                    $img_src='art/icons/email_attach.png';

                else
                    $img_src='art/icons/email.png';


                $subject='&rarr;<img src="'.$img_src.'"/> '.$overview->subject;


                $header="<table>";
                $header.="<tr><td><b>"._('Subject')."</b>:</td><td>".$overview->subject."</td></tr>";

                $header.="<tr style='border:none'><td ><b>"._('From')."</b>:</td><td>".$overview->from."</td></tr>";
                $header.="<tr style='border:none'><td><b>"._('To').":</b></td><td>".$overview->to."</td></tr>";
                $header.="<tr  style='border:none'><td><b>"._('Date')."</b>:</td><td>".$overview->date."</td></tr>";


                $header.= "</table><br/><div  style='clear:both;width:100%;border-bottom:1px solid #ccc'></div>";
                $customer->add_note($subject,$header.$message,$date,'No','Emails','Customer','Customer',$customer->id) ;

                $sql=sprintf("insert into `Email Read Dimension` (`Email Credentials Key`,`Email Uid`,`Customer Communications`,`Scope Key`) values (%d,%s,'Yes',%d)",
                             $email_credentials_key,
                             prepare_mysql($overview->message_id),
                             $customer->new_value
                            );
                mysql_query($sql);
                // print "$sql\n";
                //  exit;

            }

        }

        if (!$done   and !in_array($to,$this->account_email_addresses)  ) {
            $sql=sprintf("select `Customer Key` from    `Email Bridge` B left join `Email Dimension` E on (B.`Email Key`=E.`Email Key`) left join `Customer Dimension` C on (`Customer Key`=B.`Subject Key`) where `Subject Type`='Customer' and E.`Email`=%s ",
                         prepare_mysql($to));

            $res=mysql_query($sql);
            while ($row=mysql_fetch_assoc($res)) {

            }
        }



        // $message=$this->get_email_message_body($mbox,$message_number);





    }
    function get_email_message_body($mbox,$msgno) {

        $dataTxt = $this->get_part($mbox, $msgno, "TEXT/PLAIN");
        $dataHtml = $this->get_part($mbox, $msgno, "TEXT/HTML");


        if ($dataHtml != "") {
            $msgBody = $this->transformHTML($dataHtml);
        } else {
            $msgBody = nl2br($dataTxt);
            $msgBody = preg_replace("/([^\w\/])(www\.[a-z0-9\-]+\.[a-z0-9\-]+)/i","$1http://$2",    $msgBody);
            $msgBody = preg_replace("/([\w]+:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/i","<A    TARGET=\"_blank\" HREF=\"$1\">$1</A>", $msgBody);
            $msgBody = preg_replace("/([\w-?&;#~=\.\/]+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?))/i","<A    HREF=\"mailto:$1\">$1</A>",$msgBody);


        }



//print "$msgBody\n\n";

        $struct = imap_fetchstructure($mbox,$msgno);
        $attachment_data_array=array();





        if (isset($struct->parts)) {



            $contentParts = count($struct->parts);

            $message_mime_type=$this->get_mime_type($struct);




            if ($message_mime_type=='MULTIPART/ALTERNATIVE' and $contentParts==2) {

            } elseif ($contentParts >= 2) {
                for ($i=2; $i<=$contentParts; $i++) {
                    $att[$i-2] = imap_bodystruct($mbox,$msgno,$i);
                }
                for ($k=0; $k<sizeof($att); $k++) {
                    if ($att[$k]->parameters[0]->value == "us-ascii" || $att[$k]->parameters[0]->value    == "US-ASCII") {
                        if (    isset($att[$k]->parameters[1])    and   $att[$k]->parameters[1]->value != "") {
                            $attachment_data_array[$k] = array('name'=>$att[$k]->parameters[1]->value,'part'=>$att[$k]);


                        }
                    }
                    elseif ($att[$k]->parameters[0]->value != "iso-8859-1" &&    $att[$k]->parameters[0]->value != "ISO-8859-1") {
                        $attachment_data_array[$k] = array('name'=>$att[$k]->parameters[0]->value,'part'=>$att[$k]);
                    }
                }
            }

        }


        $attachment='';
        $has_attachements=false;

      

              

        

     



            if (count($attachment_data_array)>0) {
            
           // print_r($struct);
            
                $has_attachements=true;
                $_attachments='';
                foreach($attachment_data_array  as $attachment_key=>$attachment) {
                
                
                
                                    $data="";
                    $attachment_data = imap_fetchbody($mbox,$msgno,$attachment_key+2);  
                                    $filename="app_files/tmp/email_attachmen".date('U')."t$msgno".$attachment['name'];
                                    
                                    
                                    $fp=fopen($filename,'w');
                                    $data=$this->getdecodevalue($attachment_data,$attachment['part']->type);    
                                    fputs($fp,$data);
                                    fclose($fp);
                                    
                                    print_r($attachment);
                                   
                                    
                  $data=array(
                          'file'=>$filename,
                          'Attachment Caption'=>'',
                        
                          'Attachment File Original Name'=>$attachment['name']
                      );

                $attach=new Attachment('find',$data,'create');
                if ($attach->new) {
                  $_attachments.=', <a href="file.php?id='.$attach->id.'">'. $attachment['name'].'</a>';
                  
                  
                   if ($message_mime_type=='MULTIPART/RELATED'){
                
                        $_id=preg_replace('/^\<|\>$/','',$attachment['part']->id);
                $msgBody=preg_replace('/src\=\"cid\:'.$_id.'\"/','src="file.php?id='.$attach->id.'"',$msgBody);
                
                    }
                  
                  
                }else{
                    exit("Error no attach");
                }
                
                
                  
                  

                }
              
                $_attachments=preg_replace('/^,/','',$_attachments);
                $attachment="<div style='padding:10px;border:1px solid #ccc' >$_attachments</div>";
                //print "--> $attachment\n";
                //print $msgBody;
               // print_r($struct);
                
             
                
                //exit;

            }
        

        $msgBody=$attachment.$msgBody;

        return array($msgBody,$has_attachements);

    }



    function read_mailbox($process_type,$mail_box='',$ServerName, $UserName,$PassWord,$email_credentials_key) {
        $mbox = imap_open($ServerName.$mail_box, $UserName,$PassWord);


        $list = imap_list($mbox,$ServerName, "*");



        if ($hdr = imap_check($mbox)) {

            $msgCount = $hdr->Nmsgs;
        } else {
            return 0;
        }






        $MN=$msgCount;
        $overview=imap_fetch_overview($mbox,"1:$MN",0);
        $size=sizeof($overview);



        for ($i=$size-1; $i>=0; $i--) {



            switch ($process_type) {
            case 'customer_communication':
                $this->process_customer_communication($mbox,$i,$overview[$i],$email_credentials_key);
                break;
            default:
                break(2);

            }

        }

    }
}
?>