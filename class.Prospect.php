<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 324 May 2018 at 15:20:50 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

use Aws\Ses\SesClient;

include_once 'class.Subject.php';


class Prospect extends Subject {


    var $warning_messages = array();
    var $warning = false;

    function __construct($arg1 = false, $arg2 = false, $arg3 = false) {

        global $db;
        $this->db = $db;

        $this->label         = _('Prospect');
        $this->table_name    = 'Prospect';
        $this->ignore_fields = array(
            'Prospect Key',

        );


        $this->status_names = array(0 => 'new');

        if (is_numeric($arg1) and !$arg2) {
            $this->get_data('id', $arg1);

            return;
        }


        if ($arg1 == 'new') {
            $this->find($arg2, $arg3, 'create');

            return;
        }


        $this->get_data($arg1, $arg2, $arg3);


    }

    function get_data($tag, $id, $id2 = false) {
        if ($tag == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Prospect Dimension` WHERE `Prospect Key`=%s", prepare_mysql($id)
            );
        } elseif ($tag == 'email') {
            $sql = sprintf(
                "SELECT * FROM `Prospect Dimension` WHERE `Prospect Main Plain Email`=%s", prepare_mysql($id)
            );
        } else {
            return false;
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Prospect Key'];

            $this->user     = get_object('User', $this->data['Prospect User Key']);
            $this->customer = get_object('Customer', $this->data['Prospect Customer Key']);

        }


    }

    function find($raw_data, $address_raw_data, $options = '') {


        if (isset($raw_data['editor'])) {
            foreach ($raw_data['editor'] as $key => $value) {

                if (array_key_exists($key, $this->editor)) {
                    $this->editor[$key] = $value;
                }

            }
        }

        $create = '';

        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }


        if (!isset($raw_data['Prospect Store Key']) or !preg_match('/^\d+$/i', $raw_data['Prospect Store Key'])) {
            $this->error = true;
            $this->msg   = 'missing store key';

        }


        $sql = sprintf(
            'SELECT `Prospect Key` FROM `Prospect Dimension` WHERE `Prospect Store Key`=%d AND `Prospect Main Plain Email`=%s ', $raw_data['Prospect Store Key'], prepare_mysql($raw_data['Prospect Main Plain Email'])
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->error = true;
                $this->found = true;
                $this->msg   = _('Another prospect with same email has been found');

                return;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        if ($create) {

            $this->create($raw_data, $address_raw_data);
        }


    }

    function create($raw_data, $address_raw_data, $args = '') {


        $this->data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $this->data)) {
                $this->data[$key] = _trim($value);
            }
        }
        $this->editor = $raw_data['editor'];

        if ($this->data['Prospect Created Date'] == '') {
            $this->data['Prospect Created Date'] = gmdate('Y-m-d H:i:s');
        }


        $keys   = '';
        $values = '';
        foreach ($this->data as $key => $value) {
            $keys .= ",`".$key."`";
            if (in_array(
                $key, array(
                        'Prospect First Contacted Date',
                        'Prospect Lost Date',
                        'Prospect Registration Date',
                        'Prospect Customer Key'

                    )
            )) {
                $values .= ','.prepare_mysql($value, true);
            } else {
                $values .= ','.prepare_mysql($value, false);
            }
        }
        $values = preg_replace('/^,/', '', $values);
        $keys   = preg_replace('/^,/', '', $keys);

        $sql = "insert into `Prospect Dimension` ($keys) values ($values)";


        if ($this->db->exec($sql)) {
            $this->id = $this->db->lastInsertId();
            $this->get_data('id', $this->id);


            if ($this->data['Prospect Company Name'] != '') {
                $prospect_name = $this->data['Prospect Company Name'];
            } else {
                $prospect_name = $this->data['Prospect Main Contact Name'];
            }
            $this->update_field('Prospect Name', $prospect_name, 'no_history');


            $this->update_address('Contact', $address_raw_data, 'no_history');


            $this->update(
                array(
                    'Prospect Main Plain Mobile'    => $this->get('Prospect Main Plain Mobile'),
                    'Prospect Main Plain Telephone' => $this->get('Prospect Main Plain Telephone'),
                    'Prospect Main Plain FAX'       => $this->get('Prospect Main Plain FAX'),
                ), 'no_history'

            );


            $history_data = array(
                'History Abstract' => sprintf(_('%s prospect record created'), $this->get('Name')),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );

            $this->new = true;


            return $this;


        } else {
            $this->error = true;
            $this->msg   = 'Error inserting prospect record';
        }


        //$this->update_full_search();
        // $this->update_location_type();

    }

    function get($key, $arg1 = false) {


        if (!$this->id) {
            return false;
        }

        list($got, $result) = $this->get_subject_common($key, $arg1);
        if ($got) {
            return $result;
        }

        switch ($key) {

            case 'Status Label':

                switch ($this->data['Prospect Status']) {
                    case 'NoContacted':
                        $label = ' <span class=" padding_left_10 discreet"><i class="far fa-exclamation-circle"></i> '._('Not contacted yet').'</span>';
                        break;

                    case 'Contacted':
                        $label = ' <span class="padding_left_10 discreet"><i class="far fa-stopwatch"></i> '._('Contacted').'</span>';

                        break;
                    case 'NotInterested':
                        $label = ' <span class="error padding_left_10"><i class="far fa-frown"></i> '._('Not interested').'</span>';

                        break;
                    case 'Registered':
                        $label = ' <span class="success padding_left_10"><i class="far fa-smile"></i> '._('Registered').'</span> 
                                    <span class="button padding_left_10" onClick="change_view(\'customers/'.$this->customer->get('Store Key').'/'.$this->customer->id.'\')"><i class="fa fa-user "></i> '.$this->customer->get_formatted_id().'</span>';

                        break;
                }

                return $label;

                break;


            case('Lost Date'):
            case('Created Date'):
            case('Registration Date'):
            case('First Contacted Date'):

                if ($this->data['Prospect '.$key] == '') {
                    return '';
                }

                return '<span title="'.strftime(
                        "%a %e %b %Y %H:%M:%S %Z", strtotime($this->data['Prospect '.$key]." +00:00")
                    ).'">'.strftime(
                        "%a, %e %b %Y %R", strtotime($this->data['Prospect '.$key]." +00:00")
                    ).'</span>';
                break;

            case('Notes'):
                $sql   = sprintf(
                    "SELECT count(*) AS total FROM  `Prospect History Bridge`     WHERE `Prospect Key`=%d AND `Type`='Notes'  ", $this->id
                );
                $notes = 0;

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $notes = $row['total'];
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                return number($notes);
                break;


            case("Sticky Note"):
                return nl2br($this->data['Prospect Sticky Note']);
                break;


            default:


                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Prospect '.$key, $this->data)) {
                    return $this->data[$this->table_name.' '.$key];
                }


        }


        return '';

    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        if (is_string($value)) {
            $value = _trim($value);
        }


        if ($this->update_subject_field_switcher($field, $value, $options, $metadata)) {
            return;
        }


        switch ($field) {
            case 'Send Invitation':
                $this->send_invitation($value);
                break;
            case 'Prospect Status':

                $this->update_status($value);

                break;
            case 'Log Email':

                $history_data = array(
                    'History Abstract' => '<i class="fa fa-envelope fa-fw"></i> '._('Invitation email send using external application'),
                    'History Details'  => $value,
                    'Action'           => 'edited'
                );


                $this->add_subject_history(
                    $history_data, true, 'No', 'Emails', $this->get_object_name(), $this->id
                );


                if ($this->data['Prospect Status'] == 'NoContacted') {

                    $this->fast_update(
                        array(
                            'Prospect Status'               => 'Contacted',
                            'Prospect First Contacted Date' => gmdate('Y-m-d H:i:s'),
                            'Prospect User Key'             => $this->editor['User Key']

                        )
                    );
                }


                $this->update_metadata = array(
                    'class_html' => array(
                        'Status_Label'   => $this->get('Status Label'),
                        'Contacted_Date' => $this->get('First Contacted Date')

                    ),
                    'hide'       => array(),
                    'show'       => array(
                        'contacted_date_tr',
                        'not_interested_button'
                    )
                );


                break;
            case 'Log Call':

                $history_data = array(
                    'History Abstract' => '<i class="fa fa-phone fa-fw"></i> '._('Invitation call'),
                    'History Details'  => $value,
                    'Action'           => 'edited'
                );


                $this->add_subject_history(
                    $history_data, true, 'No', 'Emails', $this->get_object_name(), $this->id
                );


                if ($this->data['Prospect Status'] == 'NoContacted') {

                    $this->fast_update(
                        array(
                            'Prospect Status'               => 'Contacted',
                            'Prospect First Contacted Date' => gmdate('Y-m-d H:i:s')
                        )
                    );
                }

                $this->update_metadata = array(
                    'class_html' => array(
                        'Status_Label'      => $this->get('Status Label'),
                        'Contacted_Date'    => $this->get('First Contacted Date'),
                        'Prospect User Key' => $this->editor['User Key']

                    ),
                    'hide'       => array(),
                    'show'       => array(
                        'contacted_date_tr',
                        'not_interested_button'
                    )
                );

                break;

            case 'Log Post':

                $history_data = array(
                    'History Abstract' => '<i class="fa fa-person-carry fa-fw"></i> '._('Invitation send by post'),
                    'History Details'  => $value,
                    'Action'           => 'edited'
                );


                $this->add_subject_history(
                    $history_data, true, 'No', 'Emails', $this->get_object_name(), $this->id
                );


                if ($this->data['Prospect Status'] == 'NoContacted') {

                    $this->fast_update(
                        array(
                            'Prospect Status'               => 'Contacted',
                            'Prospect First Contacted Date' => gmdate('Y-m-d H:i:s'),
                            'Prospect User Key'             => $this->editor['User Key']
                        )
                    );
                }

                $this->update_metadata = array(
                    'class_html' => array(
                        'Status_Label'   => $this->get('Status Label'),
                        'Contacted_Date' => $this->get('First Contacted Date')

                    ),
                    'hide'       => array(),
                    'show'       => array(
                        'contacted_date_tr',
                        'not_interested_button'
                    )
                );

                break;

            case 'Prospect Contact Address':


                $this->update_address('Contact', json_decode($value, true), $options);

                break;


            case('Prospect Sticky Note'):
                $this->update_field_switcher('Sticky Note', $value);
                break;
            case('Sticky Note'):
                $this->update_field('Prospect '.$field, $value, 'no_null');
                $this->new_value = html_entity_decode($this->new_value);
                break;
            case('Note'):
                $this->add_note($value);
                break;
            case('Attach'):
                $this->add_attach($value);
                break;


            default:


                $base_data = $this->base_data();
                if (array_key_exists($field, $base_data)) {
                    if ($value != $this->data[$field]) {
                        $this->update_field($field, $value, $options);
                    }
                }
        }
    }

    function send_invitation($email_template_key) {


        include_once 'class.EmailCampaignType.php';
        $email_campaign_type = new EmailCampaignType('code_store', 'Invite Mailshot', $this->get('Store Key'));


        if (!$email_campaign_type->id) {
            $this->error = true;
            $this->msg   = 'EmailCampaignType Invite for this store not found';

            return;
        }


        $email_template = get_object('Email_Template', $email_template_key);
        if (!$email_template->id) {
            $this->error = true;
            $this->msg   = 'Email_Template not found';

            return;
        }
        $published_email_template = get_object('published_email_template', $email_template->get('Email Template Published Email Key'));

        $this->send_email($published_email_template, $email_campaign_type->id);


    }

    function send_email($published_email_template, $email_campaign_type_key) {


        require 'external_libs/aws.phar';


        $account = get_object('Account', 1);

        if ($published_email_template->get('Published Email Template Subject') == '') {
            $this->error = true;
            $this->msg   = _('Empty email subject');

            return;
        }

        $store = get_object('Store', $this->get('Store Key'));

        $sender_email_address = $store->get('Store Email');

        if ($sender_email_address == '') {
            $this->error = true;
            $this->msg   = 'Store sender email address not configured';

            return;
        }


        $client = SesClient::factory(
            array(
                'version'     => 'latest',
                'region'      => 'eu-west-1',
                'credentials' => [
                    'key'    => AWS_ACCESS_KEY_ID,
                    'secret' => AWS_SECRET_ACCESS_KEY,
                ],
            )
        );


        $placeholders = array(
            '[Greetings]'     => $this->get_greetings(),
            '[Prospect Name]' => $this->get('Name'),
            '[Customer Name]' => $this->get('Name'),
            '[Name]'          => $this->get('Main Contact Name'),
            '[Name,Company]'  => preg_replace(
                '/^, /', '', $this->get('Main Contact Name').($this->get('Company Name') == '' ? '' : ', '.$this->get('Company Name'))
            ),
            '[Signature]'     => $store->get('Signature'),
        );


        $request                                    = array();
        $request['Source']                          = $sender_email_address;
        $request['Destination']['ToAddresses']      = array($this->get('Main Plain Email'));
        $request['Message']['Subject']['Data']      = $published_email_template->get('Published Email Template Subject');
        $request['Message']['Body']['Text']['Data'] = strtr($published_email_template->get('Published Email Template Text'), $placeholders);
        $request['ConfigurationSetName']            = $account->get('Account Code');


        if ($published_email_template->get('Published Email Template HTML') != '') {

            $request['Message']['Body']['Html']['Data'] = strtr($published_email_template->get('Published Email Template HTML'), $placeholders);

        }


        $sql = sprintf(
            'insert into `Email Tracking Dimension` (
              `Email Tracking Scope`,`Email Tracking Scope Key`,
              `Email Tracking Email Template Type Key`,`Email Tracking Email Template Key`,`Email Tracking Published Email Template Key`,
              `Email Tracking Recipient`,`Email Tracking Recipient Key`,`Email Tracking Created Date`) values (
                    %s,%d,
                    %d,%d,%d,
                    %s,%s,%s)', prepare_mysql('Invitation'), $store->id, $email_campaign_type_key, $published_email_template->get('Published Email Template Email Template Key'), $published_email_template->id, prepare_mysql('Prospect'), $this->id,
            prepare_mysql(gmdate('Y-m-d H:i:s'))


        );


        $this->db->exec($sql);
        $email_tracking_key = $this->db->lastInsertId();

        try {
            $result = $client->sendEmail($request);


            $messageId = $result->get('MessageId');


            $sql = sprintf(
                'update `Email Tracking Dimension` set `Email Tracking State`="Sent to SES" , `Email Tracking SES Id`=%s   where `Email Tracking Key`=%d ', prepare_mysql($messageId), $email_tracking_key
            );
            $this->db->exec($sql);


            $history_data = array(
                'History Abstract' => '<i class="fal fa-paper-plane fa-fw"></i> '._('Invitation email sent').' '.sprintf(
                        '<span class="link" onclick="change_view(\'prospects/%d/%d/email/%d\')" >%s</span>', $this->get('Store Key'), $this->id, $email_tracking_key, $published_email_template->get('Published Email Template Subject')


                    ),
                'History Details'  => '',
                'Action'           => 'edited'
            );


            $this->add_subject_history(
                $history_data, true, 'No', 'Emails', $this->get_object_name(), $this->id
            );


            if ($this->data['Prospect Status'] == 'NoContacted') {

                $this->fast_update(
                    array(
                        'Prospect Status'               => 'Contacted',
                        'Prospect First Contacted Date' => gmdate('Y-m-d H:i:s'),
                        'Prospect User Key'             => $this->editor['User Key']

                    )
                );
            }


            $this->update_metadata = array(
                'class_html' => array(
                    'Status_Label'   => $this->get('Status Label'),
                    'Contacted_Date' => $this->get('First Contacted Date')

                ),
                'hide'       => array(),
                'show'       => array(
                    'contacted_date_tr',
                    'not_interested_button'
                )
            );


        } catch (Exception $e) {
            // echo("The email was not sent. Error message: ");
            // echo($e->getMessage()."\n");


            $sql = sprintf(
                'insert into `Email Tracking Event Dimension` (
              `Email Tracking Event Tracking Key`,`Email Tracking Event Type`,
              `Email Tracking Event Date`,`Email Tracking Event Data`) values (
                    %d,%s,%s,%s)', $email_tracking_key, prepare_mysql('Send to SES Error'), prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql(json_encode(array('error' => $e->getMessage())))


            );
            $this->db->exec($sql);

            $this->error = true;
            $this->msg   = _('Error, email not send').' '.$e->getMessage();


        }

    }

    function update_status($value, $extra_args = false) {

        switch ($value) {
            case 'Registered':

                $customer = $extra_args;

                $this->fast_update(
                    array(
                        'Prospect Status'            => 'Registered',
                        'Prospect Registration Date' => gmdate('Y-m-d H:i:s'),
                        'Prospect Customer Key'      => $customer->id

                    )
                );


                $history_data = array(
                    'History Abstract' => sprintf(
                        _('Prospect registered as a customer %s'),
                        '<span class="button padding_left_5" onClick="change_view(\'customers/'.$this->customer->get('Store Key').'/'.$this->customer->id.'\')"><i class="fa fa-user "></i> <span class="link">'.$this->customer->get('Name').'</span> (<span class="link">'
                        .$this->customer->get_formatted_id().'</span>)</span>'
                    ),


                    'History Details' => '',
                    'Action'          => 'edited'
                );


                $this->add_subject_history(
                    $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
                );


                break;
            case 'NotInterested':


                if ($this->data['Prospect Status'] == 'Contacted' or $this->data['Prospect Status'] == 'NoContacted') {

                    $this->fast_update(
                        array(
                            'Prospect Status'    => 'NotInterested',
                            'Prospect Lost Date' => gmdate('Y-m-d H:i:s')
                        )
                    );


                    $history_data = array(
                        'History Abstract' => _('Not interested'),
                        'History Details'  => '',
                        'Action'           => 'edited'
                    );

                    $this->add_subject_history(
                        $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
                    );


                    $this->update_metadata = array(
                        'class_html' => array(
                            'Status_Label'   => $this->get('Status Label'),
                            'Contacted_Date' => $this->get('First Contacted Date'),
                            'Lost_Date'      => $this->get('Lost Date'),

                        ),
                        'hide'       => array('not_interested_button'),
                        'show'       => array(
                            'contacted_date_tr',
                            'fail_date_tr'
                        )
                    );

                    if ($this->get('Prospect Spam') == 'No') {
                        $this->update_metadata['show'][] = 'activate_prospect_field_operation_tr';
                    } else {
                        $this->update_metadata['hide'][] = 'activate_prospect_field_operation_tr';

                    }

                }
                break;
            case 'Contacted':
            case 'NoContacted':

                if ($this->data['Prospect Status'] == 'NotInterested') {

                    $this->fast_update(
                        array(
                            'Prospect Status'    => $value,
                            'Prospect Lost Date' => ''
                        )
                    );


                    $history_data = array(
                        'History Abstract' => _('Not interested status removed'),
                        'History Details'  => '',
                        'Action'           => 'edited'
                    );

                    $this->add_subject_history(
                        $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
                    );


                    $this->update_metadata = array(
                        'class_html' => array(
                            'Status_Label'   => $this->get('Status Label'),
                            'Contacted_Date' => $this->get('First Contacted Date'),
                            'Lost_Date'      => $this->get('Lost Date'),

                        ),
                        'show'       => array('not_interested_button'),
                        'hide'       => array(
                            'contacted_date_tr',
                            'fail_date_tr',
                            'activate_prospect_field_operation_tr'
                        )
                    );
                }


                break;


        }

    }

    function get_field_label($field) {


        switch ($field) {


            case 'Prospect Registration Number':
                $label = _('registration number');
                break;
            case 'Prospect Tax Number':
                $label = _('tax number');
                break;
            case 'Prospect Tax Number Valid':
                $label = _('tax number validity');
                break;
            case 'Prospect Company Name':
                $label = _('company name');
                break;
            case 'Prospect Main Contact Name':
                $label = _('contact name');
                break;
            case 'Prospect Main Plain Email':
                $label = _('email');
                break;
            case 'Prospect Main Email':
                $label = _('main email');
                break;
            case 'Prospect Other Email':
                $label = _('other email');
                break;
            case 'Prospect Main Plain Telephone':
            case 'Prospect Main XHTML Telephone':
                $label = _('telephone');
                break;
            case 'Prospect Main Plain Mobile':
            case 'Prospect Main XHTML Mobile':
                $label = _('mobile');
                break;
            case 'Prospect Main Plain FAX':
            case 'Prospect Main XHTML Fax':
                $label = _('fax');
                break;
            case 'Prospect Other Telephone':
                $label = _('other telephone');
                break;
            case 'Prospect Preferred Contact Number':
                $label = _('main contact number');
                break;
            case 'Prospect Fiscal Name':
                $label = _('fiscal name');
                break;

            case 'Prospect Contact Address':
                $label = _('contact address');
                break;

            case 'Prospect Invoice Address':
                $label = _('invoice address');
                break;
            case 'Prospect Delivery Address':
                $label = _('delivery address');
                break;
            case 'Prospect Other Delivery Address':
                $label = _('other delivery address');
                break;

            case 'Prospect Website':
                $label = _('website');
                break;
            default:
                $label = $field;

        }

        return $label;

    }


    function add_prospect_history($history_data, $force_save = true, $deleteable = 'No', $type = 'Changes') {

        return $this->add_subject_history(
            $history_data, $force_save, $deleteable, $type
        );
    }


    function delete() {


        $this->deleted = false;


        if ($this->data['Prospect Status'] == 'Registered') {

            $this->error = true;

            return;
        }


        $history_data = array(
            'History Abstract' => _('Prospect Deleted'),
            'History Details'  => '',
            'Action'           => 'deleted'
        );

        $this->add_history($history_data, $force_save = true);


        $sql = sprintf(
            "DELETE FROM `Prospect Dimension` WHERE `Prospect Key`=%d", $this->id
        );
        $this->db->exec($sql);

        $sql = sprintf(
            "DELETE FROM `Prospect History Bridge` WHERE `Prospect Key`=%d", $this->id
        );
        $this->db->exec($sql);


        $this->deleted = true;
    }

    function activate() {

        if ($this->get('Prospect Status') == 'NotInterested' and $this->get('Prospect Spam') == 'No') {
            $value = 'NoContacted';
            $sql   = sprintf(
                "select count(*) as num from `Prospect History Bridge` where `Prospect Key`=%d and `Type` in ('Emails','Calls','Posts') ", $this->id
            );
            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    if ($row['num'] > 0) {
                        $value = 'Contacted';
                    }
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }

            $this->update_status($value);

        }
    }


    function get_templates($scope = 'keys', $options = '') {

        $templates = array();

        include_once 'class.EmailCampaignType.php';
        $email_campaign_type = new EmailCampaignType('code_store', 'Invite Mailshot', $this->get('Store Key'));

        if ($options == 'Active') {
            $where = ' and `Email Template State`="Active" ';
        } else {
            $where = '';
        }

        $sql = sprintf('select `Email Template Key` from `Email Template Dimension` where  `Email Template Scope`="EmailCampaignType" and `Email Template Scope Key`=%d %s order by `Email Template Name`', $email_campaign_type->id, $where);

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                switch ($scope) {
                    case 'keys':
                        $templates[] = $row['Email Template Key'];
                        break;
                    case 'objects':
                        $templates[] = get_object('EmailTemplate', $row['Email Template Key']);
                        break;

                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        return $templates;
    }

}


?>
