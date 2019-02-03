<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  1 February 2019 at 22:03:48 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3.1

*/
use PHPMailer\PHPMailer\PHPMailer;

class email_notification {

    public $mail;


    function __construct() {

        $this->mail = new PHPMailer;
        $this->mail->isSMTP();

        $this->mail->SMTPDebug = 0;
        $this->mail->Host = 'smtp.gmail.com';

        $this->mail->Port = 587;
        $this->mail->SMTPSecure = 'tls';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = "bot@inikoo.com";
        $this->mail->Password = GMAIL_SMTP_PWD;

        $this->mail->CharSet = 'UTF-8';
        $this->mail->Encoding = 'base64';


        $this->mail->setFrom('bot@aurora.systems', 'Aurora notifications');





    }



    function send(){

        if (!$this->mail->send()) {
            echo "Mailer Error: " . $this->mail->ErrorInfo;
        } else {

            //Section 2: IMAP
            //Uncomment these to save your message in the 'Sent Mail' folder.
            #if (save_mail($mail)) {
            #    echo "Message saved!";
            #}
        }


    }




}