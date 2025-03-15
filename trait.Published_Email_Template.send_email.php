<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 June 2018 at 14:21:30 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/


use Aws\Ses\SesClient;
use Aws\Exception\AwsException;


trait Send_Email
{

    use Aiku;
    function send($recipient, $data, $smarty = false)
    {
        $this->sent = false;

        $this->error   = false;
        $this->bcc     = null;
        $this->account = get_object('Account', 1);

        if (empty($data['Email_Template'])) {
            $email_template = get_object('Email_Template', $this->data['Published Email Template Email Template Key']);
        } else {
            $email_template = $data['Email_Template'];
            unset($data['Email_Template']);
        }
        if (empty($data['Email_Template_Type'])) {
            $this->email_template_type = get_object('Email_Template_Type', $email_template->get('Email Template Email Campaign Type Key'));
        } else {
            $this->email_template_type = $data['Email_Template_Type'];
            unset($data['Email_Template_Type']);
        }


        if ($this->email_template_type->id) {
            if ($this->email_template_type->get('Email Campaign Type Scope') != 'Marketing' and $this->email_template_type->get('Email Campaign Type Status') != 'Active') {
                $this->error = true;
                $this->msg   = 'Email Campaign Type Status not active '.$this->email_template_type->id;

                return false;
            }
        }


        $this->store = get_object('Store', $this->email_template_type->get('Store Key'));

        if ($recipient->get_object_name() == 'Prospect' or $recipient->get_object_name() == 'Customer') {
            if($this->store->id!=$recipient->get('Store Key')){
                $this->error = true;
                $this->msg   = 'Recipient store key does not match email template store key';
                return false;
            }
        }
        

        $website          = get_object('Website', $this->store->get('Store Website Key'));
        $localised_labels = $website->get('Localised Labels');


        if (empty($data['Email_Tracking'])) {
            include_once 'class.Email_Tracking.php';

            $email_tracking_data = array(
                'Email Tracking Email' => $recipient->get('Main Plain Email'),

                'Email Tracking Email Template Type Key'      => $this->email_template_type->id,
                'Email Tracking Email Template Key'           => $email_template->id,
                'Email Tracking Published Email Template Key' => $this->id,
                'Email Tracking Recipient'                    => $recipient->get_object_name(),
                'Email Tracking Recipient Key'                => $recipient->id,

            );

            $email_tracking = new Email_Tracking('new', $email_tracking_data);
        } else {
            $email_tracking = $data['Email_Tracking'];
        }



        if ($this->email_template_type->get('Email Campaign Type Code') == 'OOS Notification') {
            $this->oos_notification_reminder_keys = array();


            $with_products = 0;

            $sql = sprintf(
                'select `Back in Stock Reminder Product ID`,`Back in Stock Reminder Key` from `Back in Stock Reminder Fact` where `Back in Stock Reminder Customer Key`=%d and `Back in Stock Reminder State`="Ready"  ',
                $recipient->id
            );

            $_event_data_error=[
                'products'=>[]
            ];


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $product = get_object('Product', $row['Back in Stock Reminder Product ID']);
                    $webpage = $product->get_webpage();


                    if ($product->id and $product->get('Product Web State') == 'For Sale' and $webpage->id and $webpage->get('Webpage State') == 'Online') {
                        $with_products++;
                        break;
                    }else{
                        $_event_data_error['products'][]=[
                            'id'=>$product->id,
                            'code'=>$product->get('Product Code'),
                            'web_state'=>$product->get('Product Web State'),
                            'webpage'=>$webpage->id,
                            'webpage_state'=>$webpage->get('Webpage State')
                        ];
                    }
                }
            }

            if ($with_products == 0) {
                $email_tracking->fast_update(
                    array(
                        'Email Tracking State' => "Error",


                    )
                );



                $sql = sprintf(
                    'insert into `Email Tracking Event Dimension` (
              `Email Tracking Event Tracking Key`,`Email Tracking Event Type`,
              `Email Tracking Event Date`,`Email Tracking Event Data`) values (
                    %d,%s,%s,%s)',
                    $email_tracking->id,
                    prepare_mysql('Error'),
                    prepare_mysql(gmdate('Y-m-d H:i:s')),
                    prepare_mysql(json_encode($_event_data_error))


                );
                $this->db->exec($sql);


                $this->error = true;
                $this->msg   = _('Error, email not send');
                //$this->process_aiku_fetch('DispatchedEmailWithFull',$email_tracking->id);

                return false;
            }
        }

        if ($this->store->get('Send Email Address') == '') {
            $this->error = true;
            $this->msg   = 'Sender email address not configured';
            //$this->process_aiku_fetch('DispatchedEmailWithFull',$email_tracking->id);
            return false;
        }


        $this->get_placeholders($recipient, $data);


        $from_name            = base64_encode($this->store->get('Name'));
        $sender_email_address = $this->store->get('Send Email Address');
        $_source              = "=?utf-8?B?$from_name?= <$sender_email_address>";


        if (ENVIRONMENT == 'DEVEL') {
            $to_address = DEVEL_EMAIL;
        } else {
            $to_address = $recipient->get('Main Plain Email');
        }

        if ($this->email_template_type->get('Email Campaign Type Code') == 'Delivery Confirmation'

            and (($this->store->settings('send_invoice_attachment_in_delivery_confirmation') == 'Yes' and !empty($this->invoice_pdf)) or ($this->store->settings('send_dn_attachment_in_delivery_confirmation') == 'Yes' and !empty($this->dn_pdf)))

        ) {
            $send_raw = true;
        } else {
            $send_raw = false;
        }

        if (empty($data['Subject'])) {
            $subject = $this->get_email_subject();
        } else {
            $subject = $data['Subject'];
        }


        $subject = strtr($subject, $this->placeholders);

        $html_part = $this->get_email_html($email_tracking, $recipient, $data, $smarty, $localised_labels);
        $text_part = $this->get_email_plain_text();



        if ($send_raw) {
            $message = "To: ".$to_address."\n";
            $message .= "From: ".$_source."\n";





            $separator_multipart = md5($this->id.time());


            $message .= "Subject: ".'=?utf-8?B?'.base64_encode($subject).'?='."\n";
            $message .= "MIME-Version: 1.0\n";

            $message .= 'Content-Type: multipart/mixed; boundary="'.$separator_multipart.'"';

            $message .= "\n\n";
            $message .= "--$separator_multipart\n";

            if ($text_part != '') {
                $message .= 'Content-Type: multipart/alternative; boundary="sub_'.$separator_multipart."\"\n\n";

                $message .= '--sub_'.$separator_multipart."\n";
                $message .= 'Content-Type: text/plain; charset=utf-8'."\n";
                $message .= 'Content-Transfer-Encoding: quoted-printable'."\n";
                $message .= "\n".$text_part."\n\n";

                $message .= '--sub_'.$separator_multipart."\n";
                $message .= 'Content-Type: text/html; charset=utf-8'."\n";
                $message .= 'Content-Transfer-Encoding: quoted-printable'."\n";
                $message .= "\n".$html_part."\n";

                //$message .= "\n".'<p>hello</p>'."\n\n";

                $message .= '--sub_'.$separator_multipart.'--'."\n\n";
            } else {
                $message .= 'Content-Type: text/html; charset=utf-8'."\n";
                //  $message .= "Content-Transfer-Encoding: 7bit\n";
                //$message .= "Content-Type-Encoding: base64\n";

                $message .= "Content-Disposition: inline\n";
                $message .= "\n";
                $message .= $html_part;
                //$message .= '<p>hello</p>';
                $message .= "\n\n";
            }


            if (isset($this->invoice_pdf)) {
                $filename = _('Invoice').'_';

                $filename .= $this->placeholders['[Invoice Number]'];

                $filename .= '.pdf';
                $message  .= "--$separator_multipart\n";
                $message  .= 'Content-Type: application/pdf; name="'.$filename.'"';
                $message  .= "\n";
                $message  .= 'Content-Disposition: attachment; filename="'.$filename.'"'."\n";
                $message  .= "Content-Transfer-Encoding: base64\n";

                $message .= "\n";
                $message .= chunk_split(base64_encode($this->invoice_pdf));
                //$message .= base64_encode('hello');
                $message .= "\n\n";
            }
            if (isset($this->dn_pdf)) {
                $filename = $this->placeholders['[Delivery Note Number]'].'_delivery.pdf';


                $message .= "--$separator_multipart\n";
                $message .= 'Content-Type: application/pdf; name="'.$filename.'"';
                $message .= "\n";
                $message .= 'Content-Disposition: attachment; filename="'.$filename.'"'."\n";
                $message .= "Content-Transfer-Encoding: base64\n";

                $message .= "\n";
                $message .= chunk_split(base64_encode($this->dn_pdf));
                //$message .= base64_encode('hello');
                $message .= "\n\n";
            }
            $message .= "--$separator_multipart--\n";


            $request = [
                'Source'               => $_source,
                //  'Destinations'         => array($to_address),
                'To'                   => array($to_address),
                'ConfigurationSetName' => $this->account->get('Account Code'),
                'RawMessage'           => [
                    'Data' => $message
                ]
            ];

            if (!is_null($this->bcc)) {
                $request['Bcc'] = $this->bcc;

             //   Sentry\captureMessage("To -> ".json_encode(array($to_address)));


             //   Sentry\captureMessage("BCC -> ".json_encode($this->bcc));


            }
        }
        else {
            $request                               = array();
            $request['Source']                     = $_source;
            $request['Destination']['ToAddresses'] = array($to_address);

            if (!is_null($this->bcc)) {
                $request['Destination']['BccAddresses'] = $this->bcc;
            }

            $request['ConfigurationSetName'] = $this->account->get('Account Code');


            $request['Message']['Subject']['Data'] = '=?utf-8?B?'.base64_encode($subject).'?=';
            if ($request['Message']['Subject']['Data'] == '') {
                $this->error = true;
                $this->msg   = _('Empty email subject');
                //$this->process_aiku_fetch('DispatchedEmailWithFull',$email_tracking->id);

                return false;
            }
            $request['Message']['Body']['Text']['Data'] = $text_part;
            $request['Message']['Body']['Html']['Data'] = $html_part;
        }


        if (!isset($this->ses_clients)) {
            $this->ses_clients = array();

            $this->ses_clients[] = SesClient::factory(
                array(
                    'version'     => 'latest',
                    'region'      => 'us-east-1',
                    'credentials' => [
                        'key'    => AWS_ACCESS_KEY_ID,
                        'secret' => AWS_SECRET_ACCESS_KEY,
                    ],
                )
            );
            $this->ses_clients[] = SesClient::factory(
                array(
                    'version'     => 'latest',
                    'region'      => 'us-east-1',
                    'credentials' => [
                        'key'    => AWS_ACCESS_KEY_ID,
                        'secret' => AWS_SECRET_ACCESS_KEY,
                    ],
                )
            );

            /*
            $this->ses_clients[] = SesClient::factory(
                array(
                    'version'     => 'latest',
                    'region'      => 'eu-west-1',
                    'credentials' => [
                        'key'    => AWS_ACCESS_KEY_ID,
                        'secret' => AWS_SECRET_ACCESS_KEY,
                    ],
                )
            );
            */
        }


        try {
            $ses_client = $this->ses_clients[(rand(0, 1) == 0 ? 0 : 1)];


            if ($send_raw) {
                $result = $ses_client->sendRawEmail($request);
            } else {
                $result = $ses_client->sendEmail($request);
            }


            //print_r($result);

            $email_tracking->fast_update(
                array(
                    "Email Tracking SES Id" => $result->get('MessageId'),


                )
            );


            /*
                                    $email_tracking->fast_update(
                                        array(
                                            'Email Tracking State'  => "Sent to SES",
                                            "Email Tracking SES Id" => 'xxxx'.date('U'),


                                        )
                                    );
            */ //usleep(100000);

            //    sleep(1);


            $sql = sprintf(
                'insert into `Email Tracking Event Dimension`  (`Email Tracking Event Tracking Key`,`Email Tracking Event Type`,`Email Tracking Event Date`)
                  values (%d,%s,%s)',
                $email_tracking->id,
                prepare_mysql('Sent'),
                prepare_mysql(gmdate('Y-m-d H:i:s'))
            );
            $this->db->exec($sql);

            $email_tracking->update_state('Sent');


            if (in_array(
                $this->email_template_type->get('Email Campaign Type Code'), array(
                                                                               'Order Confirmation',
                                                                               'Delivery Confirmation',
                                                                               'OOS Notification',
                                                                               'Invite',
                                                                               'Invite Mailshot',
                                                                               'GR Reminder',
                                                                               'Registration',
                                                                           )
            )) {
                $sql  = "insert into `Email Tracking Email Copy` (`Email Tracking Email Copy Key`,`Email Tracking Email Copy Subject`,`Email Tracking Email Copy Compressed Body`) values (?,?,?) ";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(
                    array(

                        $email_tracking->id,
                        $subject,
                        gzcompress((isset($html_part) ? $html_part : $text_part))

                    )
                );
            }

            $this->sent = true;


            $this->email_tracking = $email_tracking;
        } catch (AwsException $e) {

         //   \Sentry\captureException($e);
         //   Sentry\captureMessage("Request -> ".json_encode($request));

            //print_r($request);

            //echo $e->getAwsRequestId()."\n";
            //echo $e->getAwsErrorType()."\n";
            //echo $e->getAwsErrorCode()."\n";
            //echo $e->getAwsErrorMessage();
            if ($e->getAwsErrorCode() == 'Throttling') {
                usleep(47620);
            } else {
                $email_tracking->fast_update(
                    array(
                        'Email Tracking State' => "Rejected by SES",


                    )
                );

                $sql = sprintf(
                    'insert into `Email Tracking Event Dimension` (
              `Email Tracking Event Tracking Key`,`Email Tracking Event Type`,
              `Email Tracking Event Date`,`Email Tracking Event Data`) values (
                    %d,%s,%s,%s)',
                    $email_tracking->id,
                    prepare_mysql('Send to SES Error'),
                    prepare_mysql(gmdate('Y-m-d H:i:s')),
                    prepare_mysql(json_encode(array('error' => $e->getAwsErrorMessage())))


                );
                $this->db->exec($sql);


                $this->error = true;
                $this->msg   = _('Error, email not send').' '.$e->getAwsErrorMessage();
            }
        }


        if (isset($this->oos_notification_reminder_keys)) {
            foreach ($this->oos_notification_reminder_keys as $oos_notification_reminder_key) {
                $sql = sprintf(
                    'delete from `Back in Stock Reminder Fact` where `Back in Stock Reminder Key`=%d  ',
                    $oos_notification_reminder_key
                );

                $this->db->exec($sql);
            }
        }

        if (!$email_tracking->get('Email Tracking Email Mailshot Key') > 0) {
            include_once 'utils/new_fork.php';
            new_housekeeping_fork(
                'au_housekeeping',
                array(
                    'type'                    => 'update_sent_emails_data',
                    'email_template_key'      => $email_tracking->get('Email Tracking Email Template Key'),
                    'email_template_type_key' => $email_tracking->get('Email Tracking Email Template Type Key'),


                ),
                $this->account->get('Account Code')
            );
        } else {
            if ($recipient->get_object_name() == 'Prospect') {
                /**
                 * @var $recipient \Prospect
                 */
                $recipient->mailshot_sent($email_tracking->id, $subject);
            }
        }


       // $this->process_aiku_fetch('DispatchedEmailWithFull',$email_tracking->id);

        return $email_tracking;
    }

    function get_placeholders($recipient, $data)
    {
        $this->placeholders = array(
            '[Greetings]'     => $recipient->get_greetings(),
            '[Customer Name]' => $recipient->get('Name'),
            '[Name]'          => $recipient->get('Main Contact Name'),
            '[Name,Company]'  => preg_replace('/^, /', '', $recipient->get('Main Contact Name').($recipient->get('Company Name') == '' ? '' : ', '.$recipient->get('Company Name'))),
            '[Signature]'     => $this->store->get('Signature'),
        );


        switch ($this->email_template_type->get('Email Campaign Type Code')) {
            case 'Invite':
            case 'Invite Mailshot':
            case 'Invite Full Mailshot':
                $this->placeholders['[Prospect Name]'] = $recipient->get('Name');

                break;
            case 'OOS Notification':


                $products = '';

                $sql = sprintf(
                    "select `Back in Stock Reminder Product ID`,`Back in Stock Reminder Key` from `Back in Stock Reminder Fact` where `Back in Stock Reminder Customer Key`=%d and `Back in Stock Reminder State`='Ready'  ",
                    $recipient->id
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $product = get_object('Product', $row['Back in Stock Reminder Product ID']);
                        $webpage = $product->get_webpage();


                        if ($product->id and $product->get('Product Web State') == 'For Sale' and $webpage->id and $webpage->get('Webpage State') == 'Online') {
                            $this->oos_notification_reminder_keys[] = $row['Back in Stock Reminder Key'];
                            $products                               .= sprintf(
                                '<a ses:tags="scope:product;scope_key:%d;webpage_key:%d;" href="%s"><b>%s</b> %s</a>, ',
                                $product->id,
                                $webpage->id,
                                $webpage->get('Webpage URL'),
                                $product->get('Code'),
                                $product->get('Name')

                            );
                        }
                    }
                }

                $products                         = preg_replace('/\, $/', '', $products);
                $this->placeholders['[Products]'] = $products;

                break;
            case 'Password Reminder':


                $this->placeholders['[Reset_Password_URL]'] = $data['Reset_Password_URL'];

                break;
            case 'GR Reminder':

                $this->order = get_object('Order', $data['Order Key']);


                $this->placeholders['[Order Number]']          = $this->order->get('Public ID');
                $this->placeholders['[Order Amount]']          = $this->order->get('Total');
                $this->placeholders['[Order Date]']            = strftime("%a, %e %b %Y", strtotime($this->order->get('Order Dispatched Date').' +0:00'));
                $this->placeholders['[Order Date + n days]']   = strftime("%a, %e %b %Y", strtotime($this->order->get('Order Dispatched Date').' +30 days  +0:00'));
                $this->placeholders['[Order Date + n weeks]']  = strftime("%a, %e %b %Y", strtotime($this->order->get('Order Dispatched Date').' +1 week  +0:00'));
                $this->placeholders['[Order Date + n months]'] = strftime("%a, %e %b %Y", strtotime($this->order->get('Order Dispatched Date').' +1 month  +0:00'));

                break;

            case 'Order Confirmation':

                $this->order = $data['Order'];

                $this->placeholders['[Order Number]'] = $this->order->get('Public ID');
                $this->placeholders['[Order Amount]'] = $this->order->get('Total');
                $this->placeholders['[Order Date]']   = $this->order->get('Date');

                if ($this->order->get('Order For Collection') == 'Yes') {
                    $this->placeholders['[Delivery Address]'] = _('For collection');
                } else {
                    $this->placeholders['[Delivery Address]'] = $this->order->get('Order Delivery Address Formatted');
                }

                $this->placeholders['[Invoice Address]'] = $this->order->get('Order Invoice Address Formatted');

                if (trim($this->order->get('Order Customer Message')) == '') {
                    $this->placeholders['[Customer Note]'] = '';
                } else {
                    $this->placeholders['[Customer Note]'] = '<span>'._('Note').':</span><br/><div>'.$this->order->get('Order Customer Message').'</div>';
                }


                $this->placeholders['[Pay Info]'] = $data['Pay Info'];
                $this->placeholders['[Order]']    = $data['Order Info'];
                break;
            case 'Delivery Confirmation':

                $this->order   = $data['Order'];
                $delivery_note = $data['Delivery_Note'];

                $this->placeholders['[Order Number]'] = $this->order->get('Public ID');
                $this->placeholders['[Order Amount]'] = $this->order->get('Total');
                $this->placeholders['[Order Date]']   = $this->order->get('Dispatched Date');

                $this->placeholders['[Tracking Number]'] = $delivery_note->get('Delivery Note Shipper Tracking');


                $shipper = $delivery_note->get('Shipper');


                if (is_object($shipper) and $shipper->id) {
                    $this->placeholders['[Tracking URL]'] = $shipper->get('Shipper Tracking URL');
                } else {
                    $this->placeholders['[Tracking URL]'] = '';
                }

                $bcc=$this->store->get_bcc_recipients('Delivery Note Dispatched');

                if (count($bcc)>0) {


                   // $_bbc=[];
                   // foreach($bcc as $key){
                   //     $_bbc[]="'". $key."'";
                   // }


                    $this->bcc = $bcc;
                }


                $aurora_url = $this->account->get('Account System Public URL');
                //$aurora_url='http://au.geko';

                if ($this->order->get('Order Invoice Key')) {
                    $invoice = get_object('Invoice', $this->order->get('Order Invoice Key'));
                    if ($invoice->id) {
                        $this->placeholders['[Invoice Number]'] = $invoice->get('Invoice Public ID');


                        $website=get_object('Website',$this->store->get('Store Website Key'));
                        $this->placeholders['[InvoiceLink]'] = 'https://'.$website->get('Website URL').'/ar_web_invoice.pdf.php?id='.$invoice->id;

                        $auth_data = json_encode(
                            array(
                                'auth_token' => array(
                                    'logged_in'      => true,
                                    'user_key'       => 0,
                                    'logged_in_page' => 0
                                )
                            )
                        );

                        $sak = safeEncrypt($auth_data, md5('82$je&4WN1g2B^{|bRbcEdx!Nz$OAZDI3ZkNs[cm9Q1)8buaLN'.SKEY));

                        $invoice_settings = '';
                        if ($this->store->settings('invoice_show_rrp') == 'Yes') {
                            $invoice_settings .= '&rrp=1';
                        }
                        if ($this->store->settings('invoice_show_parts') == 'Yes') {
                            $invoice_settings .= '&parts=1';
                        }
                        if ($this->store->settings('invoice_show_tariff_codes') == 'Yes') {
                            $invoice_settings .= '&commodity=1';
                        }
                        if ($this->store->settings('invoice_show_barcode') == 'Yes') {
                            $invoice_settings .= '&barcode=1';
                        }
                        if ($this->store->settings('invoice_show_weight') == 'Yes') {
                            $invoice_settings .= '&weight=1';
                        }
                        if ($this->store->settings('invoice_show_origin') == 'Yes') {
                            $invoice_settings .= '&origin=1';
                        }
                        if ($this->store->settings('invoice_show_CPNP') == 'Yes') {
                            $invoice_settings .= '&CPNP=1';
                        }
                        if (ENVIRONMENT != 'DEVEL') {
                            try {
                                $this->invoice_pdf = file_get_contents($aurora_url.'/pdf/invoice.pdf.php?id='.$this->order->get('Order Invoice Key').$invoice_settings.'&sak='.$sak);
                            } catch (\Throwable $exception) {
                                \Sentry\captureException($exception);
                            }
                        }
                    }
                }

                if ($delivery_note->id) {
                    $this->placeholders['[Delivery Note Number]'] = $delivery_note->get('Delivery Note ID');

                    $auth_data = json_encode(
                        array(
                            'auth_token' => array(
                                'logged_in'      => true,
                                'user_key'       => 0,
                                'logged_in_page' => 0
                            )
                        )
                    );
                    $sak       = safeEncrypt($auth_data, md5('82$je&4WN1g2B^{|bRbcEdx!Nz$OAZDI3ZkNs[cm9Q1)8buaLN'.SKEY));

                    if (ENVIRONMENT != 'DEVEL') {
                        try {
                            $this->dn_pdf = file_get_contents($aurora_url.'/pdf/dn.pdf.php?id='.$delivery_note->id.'&sak='.$sak);
                        } catch (\Throwable $exception) {
                            \Sentry\captureException($exception);
                        }
                    }
                }


                break;
            case 'New Customer':

                $this->new_customer = get_object('Customer', $data['customer_key']);


                break;
            case 'New Order':

                $this->order                       = get_object('Order', $data['order_key']);
                $this->notification_trigger_author = get_object('Customer', $data['customer_key']);


                break;
            case 'Invoice Deleted':

                $this->invoice                     = get_object('Invoice_deleted', $data['invoice_key']);
                $this->notification_trigger_author = get_object('User', $data['user_key']);


                break;
            case 'Delivery Note Undispatched':

                $this->delivery_note               = get_object('delivery_note', $data['delivery_note_key']);
                $this->notification_trigger_author = get_object('User', $data['user_key']);
                $this->note                        = $data['note'];

                break;
            case 'Delivery Note Dispatched':

                $this->delivery_note               = get_object('delivery_note', $data['delivery_note_key']);

                break;
            default:
        }
    }

    function get_email_subject()
    {
        switch ($this->email_template_type->get('Email Campaign Type Code')) {
            case 'New Customer':
                $subject = _('New customer registration').' '.$this->store->get('Name');
                break;
            case 'New Order':
                $subject = _('New order').' '.$this->store->get('Name');
                break;
            case 'Invoice Deleted':
                if ($this->invoice->get('Invoice Type') == 'Invoice') {
                    $subject = _('Invoice deleted').' '.$this->store->get('Name');
                } else {
                    $subject = _('Refund deleted').' '.$this->store->get('Name');
                }

                break;
            case 'Delivery Note Undispatched':
                if ($this->delivery_note->get('Delivery Note Type') == 'Replacement') {
                    $subject = _('Replacement undispatched').' '.$this->store->get('Name');
                } else {
                    $subject = _('Delivery note undispatched').' '.$this->store->get('Name');
                }
                break;
            case 'Delivery Note Dispatched':
                $subject = _('Dispatched order').' '.$this->store->get('Name');
                break;

            case 'Newsletter':
            case 'Marketing':


                $subject = $this->get('Published Email Template Subject');


                break;
            default:
                $subject = $this->get('Published Email Template Subject');
        }

        if ($subject == '') {
            $subject = 'hello';
        }


        return $subject;
    }

    function get_email_plain_text()
    {
        switch ($this->email_template_type->get('Email Campaign Type Code')) {
            case 'New Customer':
                $text = sprintf(
                    _('%s (%s) has registered'),
                    $this->new_customer->get('Name'),
                    $this->new_customer->get('Customer Main Plain Email')

                );
                break;
            case 'New Order':

                $text = sprintf(
                    _('New order %s (%s) has been placed by %s'),
                    $this->order->get('Public ID'),
                    $this->order->get('Total Amount'),
                    $this->notification_trigger_author->get('Name')

                );
                break;
            case 'Invoice Deleted':
                if ($this->invoice->get('Invoice Type') == 'Invoice') {
                    $text = sprintf(
                        _('Invoice %s (%s, %s) has been deleted by %s.'),
                        $this->invoice->get('Public ID'),
                        $this->invoice->get('Total Amount'),
                        $this->invoice->get_date('Invoice Date'),
                        $this->notification_trigger_author->get('Alias')

                    );
                } else {
                    $text = sprintf(
                        _('Refund %s (%s, %s) has been deleted by %s.'),
                        $this->invoice->get('Public ID'),
                        $this->invoice->get('Total Amount'),
                        $this->invoice->get_date('Invoice Date'),
                        $this->notification_trigger_author->get('Alias')

                    );
                }

                break;
            case 'Delivery Note Undispatched':
                if ($this->delivery_note->get('Delivery Note Type') == 'Replacement') {
                    $text = sprintf(
                        _('Replacement %s has been undispatched by %s.'),
                        $this->delivery_note->get('ID'),
                        $this->notification_trigger_author->get('Alias')

                    );
                } else {
                    $text = sprintf(
                        _('Delivery note %s has been undispatched by %s.'),
                        $this->delivery_note->get('ID'),
                        $this->notification_trigger_author->get('Alias')

                    );
                }
                break;
            case 'Delivery Note Dispatched':

                $text = sprintf(
                    _('New order %s has been dispatched to %s'),
                    $this->delivery_note->get('ID'),
                    $this->delivery_note->get('Delivery Note Customer Name'),

                );

                break;
            default:

                $text = strtr($this->get('Published Email Template Text'), $this->placeholders);

                if ($this->email_template_type->get('Email Campaign Type Code') == 'GR Reminder') {
                    $_date = date('Y-m-d', strtotime($this->order->get('Order Dispatched Date')));


                    if ($text != '') {
                        $text = preg_replace_callback(
                            '/\[Order Date \+\s*(\d+)\s*days\]/',
                            function ($match_data) use ($_date) {
                                return strftime("%a, %e %b %Y", strtotime($_date.' +'.$match_data[1].' days'));
                            },
                            $text
                        );


                        $text = preg_replace_callback(
                            '/\[Order Date \+\s*(\d+)\s*weeks\]/',
                            function ($match_data) use ($_date) {
                                return strftime("%a, %e %b %Y", strtotime($_date.' +'.$match_data[1].' weeks'));
                            },
                            $text
                        );
                    }
                }
        }


        return trim($text);
    }

    function get_email_html($email_tracking, $recipient, $data, $smarty, $localised_labels)
    {
        switch ($this->email_template_type->get('Email Campaign Type Code')) {
            case 'New Customer':

                $subject    = _('New customer registration').' '.$this->store->get('Name');
                $title      = '<b>'._('New customer registration').'</b> '.$this->store->get('Name');
                $link_label = _('Link to customer');


                $info = sprintf(
                    _('%s (%s) has registered'),
                    '<b>'.$this->new_customer->get('Name').'</b>',
                    '<a href="href="mailto:'.$this->new_customer->get('Customer Main Plain Email').'"">'.$this->new_customer->get('Customer Main Plain Email').'</a>'
                );


                $link = sprintf(
                    '%s/customers/%d/%d',
                    $this->account->get('Account System Public URL'),
                    $this->store->id,
                    $this->new_customer->id
                );

                $smarty->assign('type', 'Success');

                $smarty->assign('store', $this->store);
                $smarty->assign('account', $this->account);
                $smarty->assign('title', $title);
                $smarty->assign('subject', $subject);
                $smarty->assign('link_label', $link_label);
                $smarty->assign('link', $link);
                $smarty->assign('info', $info);

                $html = $smarty->fetch('notification_emails/alert.ntfy.tpl');
                break;
            case 'New Order':


                $subject    = _('New order').' '.$this->store->get('Name');
                $link_label = _('Link to order');

                $link = sprintf(
                    '%s/orders/%d/%d',
                    $this->account->get('Account System Public URL'),
                    $this->store->id,
                    $this->order->id
                );


                if ($this->account->get('Currency Code') == $this->store->get('Store Currency Code')) {
                    /*
                    $info = sprintf(
                        _('New order %s (%s) has been placed by %s'), '<a href="'.$link.'">'.$this->order->get('Public ID').'</a>', '<b>'.$this->order->get('Total Amount').'</b>', '<b>'.$this->notification_trigger_author->get('Name').'</b>'

                    );
                    */
                    $title = '<b>'._('New order').'</b> '.$this->order->get('Total Amount').' '.$this->store->get('Name');
                } else {
                    /*
                    $info = sprintf(
                        _('New order %s (%s) has been placed by %s'), '<a href="'.$link.'">'.$this->order->get('Public ID').'</a>',   '<span>'.$this->order->get('DC Total Amount').'</span> <b>'.$this->order->get('Total Amount').'</b>', '<b>'.$this->notification_trigger_author->get('Name').'</b>'

                    );
                    */
                    $title = '<b>'._('New order').'</b> <span style="font-style: italic">('.$this->order->get('DC Total Amount').')</span> '.$this->order->get('Total Amount').' '.$this->store->get('Name');
                }


                $smarty->assign('type', 'Success');

                $smarty->assign('store', $this->store);
                $smarty->assign('account', $this->account);
                $smarty->assign('title', $title);
                $smarty->assign('subject', $subject);
                $smarty->assign('link_label', $link_label);
                $smarty->assign('link', $link);
                //$smarty->assign('info', $info);

                $smarty->assign('customer', $this->notification_trigger_author);
                $smarty->assign('order', $this->order);

                $html = $smarty->fetch('notification_emails/new_order.ntfy.tpl');

                break;
            case 'Invoice Deleted':

                if ($this->invoice->get('Invoice Type') == 'Invoice') {
                    $subject    = _('Invoice deleted').' '.$this->store->get('Name');
                    $title      = '<b>'._('Invoice deleted').'</b> '.$this->store->get('Name');
                    $link_label = _('Link to deleted invoice');
                    $info       = sprintf(
                        _('Invoice %s (%s, %s) has been deleted by %s.'),
                        $this->invoice->get('Public ID'),
                        '<b>'.$this->invoice->get('Total Amount').'</b>',
                        $this->invoice->get_date('Invoice Date'),
                        $this->notification_trigger_author->get('Alias')

                    );
                } else {
                    $subject = _('Refund deleted').' '.$this->store->get('Name');
                    $title   = _('Refund deleted').' '.$this->store->get('Name');

                    $link_label = _('Link to deleted refund');
                    $info       = sprintf(
                        _('Refund %s (%s, %s) has been deleted by %s.'),
                        $this->invoice->get('Public ID'),
                        '<b>'.$this->invoice->get('Total Amount').'</b>',
                        $this->invoice->get_date('Invoice Date'),
                        $this->notification_trigger_author->get('Alias')

                    );
                }

                $note = $this->invoice->get('Invoice Deleted Note');
                if ($note == '') {
                    $note = _("User did not left a note");
                }


                $info .= sprintf('<p style="border:1px solid orange;width: 100%%;padding-top: 20px;padding-bottom: 20px"><span style="padding:0px 20px;color:#777">%s</span></p>', $note);
                $link = sprintf(
                    '%s/orders/%d/%d/invoice/%d',
                    $this->account->get('Account System Public URL'),

                    $this->store->id,
                    $this->invoice->get('Invoice Order Key'),
                    $this->invoice->id
                );

                $smarty->assign('type', 'Warning');

                $smarty->assign('store', $this->store);
                $smarty->assign('account', $this->account);
                $smarty->assign('title', $title);
                $smarty->assign('subject', $subject);
                $smarty->assign('link_label', $link_label);
                $smarty->assign('link', $link);
                $smarty->assign('info', $info);

                $html = $smarty->fetch('notification_emails/alert.ntfy.tpl');

                break;
            case 'Delivery Note Undispatched':

                if ($this->delivery_note->get('Delivery Note Type') == 'Replacement') {
                    $subject    = _('Replacement undispatched').' '.$this->store->get('Name');
                    $title      = '<b>'._('Replacement undispatched').'</b> '.$this->store->get('Name');
                    $link_label = _('Link to replacement');


                    $info = sprintf(
                        _('Replacement %s has been undispatched by %s.'),
                        '<b>'.$this->delivery_note->get('ID').'</b>',
                        $this->notification_trigger_author->get('Alias')

                    );
                } else {
                    $subject    = _('Delivery note undispatched').' '.$this->store->get('Name');
                    $title      = '<b>'._('Delivery note undispatched').'</b> '.$this->store->get('Name');
                    $link_label = _('Link to delivery note');


                    $info = sprintf(
                        _('Delivery note %s has been undispatched by %s.'),
                        '<b>'.$this->delivery_note->get('ID').'</b>',
                        $this->notification_trigger_author->get('Alias')

                    );
                }


                $note = $this->note;
                if ($note == '') {
                    $note = _("User did not left a note");
                }


                $info .= sprintf('<p style="border:1px solid orange;width: 100%%;padding-top: 20px;padding-bottom: 20px"><span style="padding:0px 20px;color:#777">%s</span></p>', $note);


                $link = sprintf(
                    '%s/delivery_notes/%d/%d',
                    $this->account->get('Account System Public URL'),
                    $this->store->id,
                    $this->delivery_note->id
                );


                $smarty->assign('type', 'Warning');

                $smarty->assign('store', $this->store);
                $smarty->assign('account', $this->account);
                $smarty->assign('title', $title);
                $smarty->assign('subject', $subject);
                $smarty->assign('link_label', $link_label);
                $smarty->assign('link', $link);
                $smarty->assign('info', $info);

                $html = $smarty->fetch('notification_emails/alert.ntfy.tpl');


                break;

            case 'Delivery Note Dispatched':

                $subject    = 'Delivery note dispatched'.' '.$this->store->get('Name');
                $title      = '<b>Delivery note dispatched to </b> '.$this->delivery_note->get('Delivery Note Customer Name');


                $info = sprintf(
                    'Delivery note %s has been undispatched to %s.',
                    '<b>'.$this->delivery_note->get('ID').'</b>',
                    $this->delivery_note->get('Delivery Note Customer Name')

                );

                $smarty->assign('type', 'Success');

                $smarty->assign('store', $this->store);
                $smarty->assign('account', $this->account);
                $smarty->assign('title', $title);
                $smarty->assign('subject', $subject);

                $smarty->assign('info', $info);
                $html = $smarty->fetch('notification_emails/order_dispatched.tpl');

                break;
            default:

                $html = strtr($this->get('Published Email Template HTML'), $this->placeholders);


                if ($this->email_template_type->get('Email Campaign Type Code') == 'GR Reminder') {
                    $_date = date('Y-m-d', strtotime($this->order->get('Order Dispatched Date')));


                    $html = preg_replace_callback(
                        '/\[Order Date \+\s*(\d+)\s*days\]/',
                        function ($match_data) use ($_date) {
                            return strftime("%a, %e %b %Y", strtotime($_date.' +'.$match_data[1].' days'));
                        },
                        $html
                    );

                    $html = preg_replace_callback(
                        '/\[Order Date \+\s*(\d+)\s*months\]/',
                        function ($match_data) use ($_date) {
                            return strftime("%a, %e %b %Y", strtotime($_date.' +'.$match_data[1].' months'));
                        },
                        $html
                    );
                } elseif ($this->email_template_type->get('Email Campaign Type Code') == 'Delivery Confirmation') {
                    if ($this->placeholders['[Tracking Number]'] != '' and $this->placeholders['[Tracking URL]'] != '') {
                        $html = preg_replace('/\[Not Tracking START\].*\[END\]/', '', $html);

                        if (preg_match('/\[Tracking START\](.*)\[END\]/', $html, $matches)) {
                            $html = preg_replace('/\[Tracking START\].*\[END\]/', $matches[1], $html);
                        }
                    } else {
                        $html = preg_replace('/\[Tracking START\].*\[END\]/', '', $html);

                        if (preg_match('/\[Not Tracking START\](.*)\[END\]/', $html, $matches)) {
                            $html = preg_replace('/\[Not Tracking START\].*\[END\]/', $matches[1], $html);
                        }
                    }
                }

                $html = preg_replace_callback(
                    '/\[Unsubscribe]/',
                    function () use ($email_tracking, $recipient, $data, $smarty, $localised_labels) {
                        if (isset($data['Unsubscribe URL'])) {
                            $smarty->assign('localised_labels', $localised_labels);
                            $smarty->assign('link', $data['Unsubscribe URL'].'?s='.$email_tracking->id.'&a='.hash('sha256', IKEY.$recipient->id.$email_tracking->id));

                            return $smarty->fetch('unsubscribe_marketing_email.placeholder.tpl');
                        }
                    },
                    $html
                );

                $html = preg_replace_callback(
                    '/\[Unsubscribe basket emails]/',
                    function () use ($email_tracking, $recipient, $data, $smarty, $localised_labels) {
                        if (isset($data['Unsubscribe URL'])) {
                            $smarty->assign('localised_labels', $localised_labels);
                            $smarty->assign('link', $data['Unsubscribe URL'].'?s='.$email_tracking->id.'&a='.hash('sha256', IKEY.$recipient->id.$email_tracking->id));

                            return $smarty->fetch('unsubscribe_marketing_email.placeholder.tpl');
                        }
                    },
                    $html
                );

                $html = preg_replace_callback(
                    '/\[Stop_Junk_Mail]/',
                    function () use ($email_tracking, $recipient, $data, $smarty, $localised_labels) {
                        if (isset($data['Unsubscribe URL'])) {
                            $smarty->assign('localised_labels', $localised_labels);
                            $smarty->assign('link', $data['Unsubscribe URL'].'?s='.$email_tracking->id.'&a='.hash('sha256', IKEY.$recipient->id.$email_tracking->id));

                            return $smarty->fetch('stop_junk_email.placeholder.tpl');
                        }
                    },
                    $html
                );
        }

        return $html;
    }


}
