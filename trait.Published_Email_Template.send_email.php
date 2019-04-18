<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 June 2018 at 14:21:30 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/


use Aws\Ses\SesClient;


trait Send_Email {
    function send($recipient, $data, $smarty = false) {

        $this->sent = false;

        $this->error   = false;
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
            if ($this->email_template_type->get('Email Campaign Type Status') != 'Active') {
                $this->error = true;
                $this->msg   = 'Email Campaign Type Status not active';

                return false;
            }
        }


        $this->store = get_object('Store', $this->email_template_type->get('Store Key'));

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
                'select `Back in Stock Reminder Product ID`,`Back in Stock Reminder Key` from `Back in Stock Reminder Fact` where `Back in Stock Reminder Customer Key`=%d and `Back in Stock Reminder State`="Ready"  ', $recipient->id
            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $product = get_object('Product', $row['Back in Stock Reminder Product ID']);
                    $webpage = $product->get_webpage();


                    if ($product->id and $product->get('Product Web State') == 'For Sale' and $webpage->id and $webpage->get('Webpage State') == 'Online') {
                        $with_products++;
                        break;


                    }


                }


            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }

            if ($with_products == 0) {
                $email_tracking->fast_update(
                    array(
                        'Email Tracking State' => "Error",


                    )
                );


                $this->error = true;
                $this->msg   = _('Error, email not send');

                return false;

            }


        }

        if ($this->store->get('Send Email Address') == '') {

            $this->error = true;
            $this->msg   = 'Sender email address not configured';

            return false;


        }


        $this->get_placeholders($recipient, $data);


        $from_name            = base64_encode($this->store->get('Name'));
        $sender_email_address = $this->store->get('Send Email Address');
        $_source              = "=?utf-8?B?$from_name?= <$sender_email_address>";


        $to_address = $recipient->get('Main Plain Email');

        if (preg_match('/bali|sasi|sakoi/', gethostname())) {
            $to_address = 'raul@inikoo.com';
        }


        $request                               = array();
        $request['Source']                     = $_source;
        $request['Destination']['ToAddresses'] = array($to_address);
        $request['ConfigurationSetName']       = $this->account->get('Account Code');


        $request['Message']['Subject']['Data'] = $this->get_email_subject();


        if ($request['Message']['Subject']['Data'] == '') {

            $this->error = true;
            $this->msg   = _('Empty email subject');

            return false;

        }

        $request['Message']['Body']['Text']['Data'] = $this->get_email_plain_text();


        // if ($this->get('Published Email Template HTML') != '') {

        $request['Message']['Body']['Html']['Data'] = $this->get_email_html($email_tracking, $recipient, $data, $smarty, $localised_labels);


        if (!isset($this->ses_clients)) {
            $this->ses_clients=array();
            /*
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
            */
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

        }


        try {



            //$ses_client=$this->ses_clients[(rand(0,3)==0?0:1)];

            $ses_client=$this->ses_clients[0];

            $result = $ses_client->sendEmail($request);


            $email_tracking->fast_update(
                array(
                    'Email Tracking State'  => "Sent to SES",
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
            */


            //  sleep(1);


            if (in_array(
                $this->email_template_type->get('Email Campaign Type Code'), array(
                                                                               'Order Confirmation',
                                                                               'Delivery Confirmation',
                                                                               'OOS Notification',
                                                                               'Password Reminder',
                                                                               'Invite',
                                                                               'Invite Mailshot',
                                                                               'GR Reminder',
                                                                               'Registration',
                                                                               'AbandonedCart'
                                                                           )
            )) {

                $sql = sprintf(
                    'insert into `Email Tracking Email Copy` (`Email Tracking Email Copy Key`,`Email Tracking Email Copy Subject`,`Email Tracking Email Copy Body`) values (%d,%s,%s)  ', $email_tracking->id, prepare_mysql($request['Message']['Subject']['Data']),
                    (isset($request['Message']['Body']['Html']['Data']) ? prepare_mysql($request['Message']['Body']['Html']['Data']) : prepare_mysql($request['Message']['Body']['Text']['Data'])


                    )


                );
                $this->db->exec($sql);

            }

            $this->sent           = true;
            $this->email_tracking = $email_tracking;


        } catch (Exception $e) {


            $email_tracking->fast_update(
                array(
                    'Email Tracking State' => "Rejected by SES",


                )
            );

            $sql = sprintf(
                'insert into `Email Tracking Event Dimension` (
              `Email Tracking Event Tracking Key`,`Email Tracking Event Type`,
              `Email Tracking Event Date`,`Email Tracking Event Data`) values (
                    %d,%s,%s,%s)', $email_tracking->id, prepare_mysql('Send to SES Error'), prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql(json_encode(array('error' => $e->getMessage())))


            );
            $this->db->exec($sql);


            $this->error = true;
            $this->msg   = _('Error, email not send').' '.$e->getMessage();


        }


        if (isset($this->oos_notification_reminder_keys)) {
            foreach ($this->oos_notification_reminder_keys as $oos_notification_reminder_key) {
                $sql = sprintf(
                    'delete from `Back in Stock Reminder Fact` where `Back in Stock Reminder Key`=%d  ', $oos_notification_reminder_key
                );

                $this->db->exec($sql);
            }
        }


        if ($email_tracking->get('Email Tracking Email Mailshot Key') > 0) {

            $email_template->update_sent_emails_totals();

            $this->email_template_type->update_sent_emails_totals();

            $email_campaign = get_object('email_campaign', $email_tracking->get('Email Tracking Email Mailshot Key'));
            $email_campaign->update_sent_emails_totals();


            if (isset($this->socket)) {


                switch ($email_tracking->get('Email Tracking State')) {
                    case 'Ready':
                        $state = _('Ready to send');
                        break;
                    case 'Sent to SES':
                        $state = _('Sending');
                        break;
                        break;
                    case 'Delivered':
                        $state = _('Delivered');
                        break;
                    case 'Opened':
                        $state = _('Opened');
                        break;
                    case 'Clicked':
                        $state = _('Clicked');
                        break;
                    case 'Error':
                        $state = '<span class="warning">'._('Error').'</span>';
                        break;
                    case 'Hard Bounce':
                        $state = '<span class="error"><i class="fa fa-exclamation-circle"></i>  '._('Bounced').'</span>';
                        break;
                    case 'Soft Bounce':
                        $state = '<span class="warning"><i class="fa fa-exclamation-triangle"></i>  '._('Probable bounce').'</span>';
                        break;
                    case 'Spam':
                        $state = '<span class="error"><i class="fa fa-exclamation-circle"></i>  '._('Mark as spam').'</span>';
                        break;
                    default:
                        $state = $email_tracking->get('Email Tracking State');
                }


                $this->socket->send(
                    json_encode(
                        array(
                            'channel' => 'real_time.'.strtolower($this->account->get('Account Code')),
                            'objects' => array(
                                array(
                                    'object' => 'mailshot',
                                    'key'    => $email_campaign->id,

                                    'update_metadata' => array(
                                        'class_html' => array(
                                            'Sent_Emails_Info'    => $email_campaign->get('Sent Emails Info'),
                                            '_Email_Campaign_Sent' => $email_campaign->get('Sent'),
                                        )
                                    )

                                )

                            ),

                            'tabs' => array(
                                array(
                                    'tab'        => 'mailshot.sent_emails',
                                    'parent'     => 'email_campaign_type',
                                    'parent_key' => $this->email_template_type->id,
                                    'cell'       => array(
                                        'email_tracking_state_'.$email_tracking->id => $state
                                    )


                                ),
                                array(
                                    'tab'        => 'email_campaign_type.mailshots',
                                    'parent'     => 'store',
                                    'parent_key' => $this->email_template_type->get('Store Key'),
                                    'cell'       => array(
                                        'date_'.$email_campaign->id  => strftime("%a, %e %b %Y %R", strtotime($email_campaign->get('Email Campaign Last Updated Date')." +00:00")),
                                        'state_'.$email_campaign->id => $email_campaign->get('State'),
                                        'sent_'.$email_campaign->id  => $email_campaign->get('Sent')
                                    )


                                ),

                            ),


                        )
                    )
                );
            }


        } else {
            include_once 'utils/new_fork.php';
            new_housekeeping_fork(
                'au_housekeeping', array(
                'type'                    => 'update_sent_emails_data',
                'email_template_key'      => $email_tracking->get('Email Tracking Email Template Key'),
                'email_template_type_key' => $email_tracking->get('Email Tracking Email Template Type Key'),
                'email_mailshot_key'      => $email_tracking->get('Email Tracking Email Mailshot Key'),

            ), $this->account->get('Account Code')
            );
        }


        return $email_tracking;

    }

    function get_placeholders($recipient, $data) {


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

                $this->placeholders['[Prospect Name]'] = $recipient->get('Name');

                break;
            case 'OOS Notification':


                $products = '';

                $sql = sprintf(
                    'select `Back in Stock Reminder Product ID`,`Back in Stock Reminder Key` from `Back in Stock Reminder Fact` where `Back in Stock Reminder Customer Key`=%d and `Back in Stock Reminder State`="Ready"  ', $recipient->id
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $product = get_object('Product', $row['Back in Stock Reminder Product ID']);
                        $webpage = $product->get_webpage();


                        if ($product->id and $product->get('Product Web State') == 'For Sale' and $webpage->id and $webpage->get('Webpage State') == 'Online') {
                            $this->oos_notification_reminder_keys[] = $row['Back in Stock Reminder Key'];
                            $products                               .= sprintf(
                                '<a ses:tags="scope:product;scope_key:%d;webpage_key:%d;" href="%s"><b>%s</b> %s</a>, ', $product->id, $webpage->id, $webpage->get('Webpage URL'), $product->get('Code'), $product->get('Name')

                            );


                        }


                    }


                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                $products = preg_replace('/\, $/', '', $products);

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

                if($this->order->get('Order For Collection')=='Yes'){
                    $this->placeholders['[Delivery Address]']   = _('For collection');
                }else{
                    $this->placeholders['[Delivery Address]']   = $this->order->get('Order Delivery Address Formatted');

                }

                $this->placeholders['[Invoice Address]']   = $this->order->get('Order Invoice Address Formatted');

                if(trim($this->order->get('Order Customer Message'))==''){
                    $this->placeholders['[Customer Note]']   = '';
                }else{
                    $this->placeholders['[Customer Note]']   = '<span>'._('Note').':</span><br/><div>'.$this->order->get('Order Customer Message').'</div>';

                }


                $this->placeholders['[Pay Info]']     = $data['Pay Info'];
                $this->placeholders['[Order]']        = $data['Order Info'];
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
            default:


        }


    }

    function get_email_subject() {


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
            default:
                $subject = $this->get('Published Email Template Subject');

        }

        return $subject;
    }

    function get_email_plain_text() {


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
                        $this->invoice->get_date('Invoice Date'), $this->notification_trigger_author->get('Alias')

                    );
                } else {
                    $text = sprintf(
                        _('Refund %s (%s, %s) has been deleted by %s.'),
                        $this->invoice->get('Public ID'),
                        $this->invoice->get('Total Amount'),
                        $this->invoice->get_date('Invoice Date'), $this->notification_trigger_author->get('Alias')

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
            default:

                $text = strtr($this->get('Published Email Template Text'), $this->placeholders);

                if ($this->email_template_type->get('Email Campaign Type Code') == 'GR Reminder') {


                    $_date = date('Y-m-d', strtotime($this->order->get('Order Dispatched Date')));


                    if ($text != '') {
                        $text = preg_replace_callback(
                            '/\[Order Date \+\s*(\d+)\s*days\]/', function ($match_data) use ($_date) {
                            return strftime("%a, %e %b %Y", strtotime($_date.' +'.$match_data[1].' days'));
                        }, $text
                        );


                        $text = preg_replace_callback(
                            '/\[Order Date \+\s*(\d+)\s*weeks\]/', function ($match_data) use ($_date) {
                            return strftime("%a, %e %b %Y", strtotime($_date.' +'.$match_data[1].' weeks'));
                        }, $text
                        );


                    }


                }

        }


        return $text;

    }

    function get_email_html($email_tracking, $recipient, $data, $smarty, $localised_labels) {

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
                $title      = '<b>'._('New order').'</b> '.$this->order->get('Total Amount').' '.$this->store->get('Name');
                $link_label = _('Link to order');

                $link = sprintf(
                    '%s/orders/%d/%d',
                    $this->account->get('Account System Public URL'),
                    $this->store->id,
                    $this->order->id
                );


                $info = sprintf(
                    _('New order %s (%s) has been placed by %s'),
                    '<a href="'.$link.'">'.$this->order->get('Public ID').'</a>',
                    '<b>'.$this->order->get('Total Amount').'</b>',
                    '<b>'.$this->notification_trigger_author->get('Name').'</b>'

                );


                $smarty->assign('type', 'Success');

                $smarty->assign('store', $this->store);
                $smarty->assign('account', $this->account);
                $smarty->assign('title', $title);
                $smarty->assign('subject', $subject);
                $smarty->assign('link_label', $link_label);
                $smarty->assign('link', $link);
                $smarty->assign('info', $info);

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
                        $this->invoice->get_date('Invoice Date'), $this->notification_trigger_author->get('Alias')

                    );

                } else {
                    $subject = _('Refund deleted').' '.$this->store->get('Name');
                    $title   = _('Refund deleted').' '.$this->store->get('Name');

                    $link_label = _('Link to deleted refund');
                    $info       = sprintf(
                        _('Refund %s (%s, %s) has been deleted by %s.'),
                        $this->invoice->get('Public ID'),
                        '<b>'.$this->invoice->get('Total Amount').'</b>',
                        $this->invoice->get_date('Invoice Date'), $this->notification_trigger_author->get('Alias')

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
            default:

                $html = strtr($this->get('Published Email Template HTML'), $this->placeholders);


                if ($this->email_template_type->get('Email Campaign Type Code') == 'GR Reminder') {


                    $_date = date('Y-m-d', strtotime($this->order->get('Order Dispatched Date')));


                    $html = preg_replace_callback(
                        '/\[Order Date \+\s*(\d+)\s*days\]/', function ($match_data) use ($_date) {


                        return strftime("%a, %e %b %Y", strtotime($_date.' +'.$match_data[1].' days'));
                    }, $html
                    );

                    $html = preg_replace_callback(
                        '/\[Order Date \+\s*(\d+)\s*months\]/', function ($match_data) use ($_date) {
                        return strftime("%a, %e %b %Y", strtotime($_date.' +'.$match_data[1].' months'));
                    }, $html
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
                    '/\[Unsubscribe]/', function () use ($email_tracking, $recipient, $data, $smarty, $localised_labels) {

                    if (isset($data['Unsubscribe URL'])) {


                        $smarty->assign('localised_labels', $localised_labels);


                        $smarty->assign('link', $data['Unsubscribe URL'].'?s='.$email_tracking->id.'&a='.hash('sha256', IKEY.$recipient->id.$email_tracking->id));

                        // print $smarty->fetch('unsubscribe_marketing_email.placeholder.tpl');

                        return $smarty->fetch('unsubscribe_marketing_email.placeholder.tpl');;

                    }


                }, $html
                );
        }

        return $html;
    }


}


?>
