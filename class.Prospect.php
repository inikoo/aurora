<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 324 May 2018 at 15:20:50 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

include_once 'class.Subject.php';


class Prospect extends Subject {


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
            $this->error_code == 'missing store key';

        }


        $sql = sprintf(
            'SELECT `Prospect Key` FROM `Prospect Dimension` WHERE `Prospect Store Key`=%d AND `Prospect Main Plain Email`=%s ', $raw_data['Prospect Store Key'], prepare_mysql($raw_data['Prospect Main Plain Email'])
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->error      = true;
                $this->found      = true;
                $this->msg        = _('Another prospect with same email has been found');
                $this->error_code = 'duplicated email';

                return;
            }
        }


        if ($create) {

            $this->create($raw_data, $address_raw_data);
        }


    }

    function create($raw_data, $address_raw_data) {


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
                        'Prospect Last Contacted Date',
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

            if (is_array($address_raw_data)) {
                $this->update_address('Contact', $address_raw_data, 'no_history');
            }


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
                    case 'Invoiced':
                        $label = ' <span class="success padding_left_10"><i class="far fa-smile"></i> '._('Invoiced').'</span> 
                                    <span class="button padding_left_10" onClick="change_view(\'customers/'.$this->customer->get('Store Key').'/'.$this->customer->id.'\')"><i class="fa fa-user "></i> '.$this->customer->get_formatted_id().'</span>';

                        break;
                }

                return $label;

                break;


            case('Lost Date'):
            case('Created Date'):
            case('Registration Date'):
            case('Invoiced Date'):
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


                    include_once('class.Sales_Representative.php');
                    $sales_representative = new Sales_Representative(
                        'find', array(
                                  'Sales Representative User Key' => $this->editor['User Key'],
                                  'editor'                        => $this->editor
                              )
                    );
                    $sales_representative->fast_update(array('Sales Representative Prospect Agent' => 'Yes'));


                    $this->fast_update(
                        array(
                            'Prospect Status'                   => 'Contacted',
                            'Prospect First Contacted Date'     => gmdate('Y-m-d H:i:s'),
                            'Prospect Last Contacted Date'      => gmdate('Y-m-d H:i:s'),
                            'Prospect User Key'                 => $this->editor['User Key'],
                            'Prospect Sales Representative Key' => $sales_representative->id

                        )
                    );


                } else {
                    $this->fast_update(
                        array(

                            'Prospect Last Contacted Date' => gmdate('Y-m-d H:i:s'),


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
                            'Prospect First Contacted Date' => gmdate('Y-m-d H:i:s'),

                        )
                    );
                    if ($this->editor['User Key']) {


                        include_once('class.Sales_Representative.php');
                        $sales_representative = new Sales_Representative(
                            'find', array(
                                      'Sales Representative User Key' => $this->editor['User Key'],
                                      'editor'                        => $this->editor
                                  )
                        );
                        $sales_representative->fast_update(array('Sales Representative Prospect Agent' => 'Yes'));

                        $this->fast_update(
                            array(

                                'Prospect User Key'                 => $this->editor['User Key'],
                                'Prospect Sales Representative Key' => $sales_representative->id

                            )
                        );
                    }
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

                        )
                    );
                    if ($this->editor['User Key']) {


                        include_once('class.Sales_Representative.php');
                        $sales_representative = new Sales_Representative(
                            'find', array(
                                      'Sales Representative User Key' => $this->editor['User Key'],
                                      'editor'                        => $this->editor
                                  )
                        );
                        $sales_representative->fast_update(array('Sales Representative Prospect Agent' => 'Yes'));

                        $this->fast_update(
                            array(

                                'Prospect User Key'                 => $this->editor['User Key'],
                                'Prospect Sales Representative Key' => $sales_representative->id

                            )
                        );
                    }
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
            case 'Prospect Customer Key':

                $customer = get_object('Customer', $value);
                if (!$customer->id) {

                    $this->error = true;
                    $this->msg   = 'Customer not found';

                    return;
                }

                if ($customer->get('Store Key') != $this->get('Store Key')) {
                    $this->error = true;
                    $this->msg   = 'Customer different store';

                    return;

                }

                if (strtotime($customer->get('Customer First Contacted Date')) > strtotime($this->get('Prospect Created Date'))) {
                    $this->error = true;
                    $this->msg   = _("Can't link this customer because, he registered before the prospect was created");

                    return;

                }


                $sql = sprintf('select `History Key`,`Type`,`Deletable`,`Strikethrough` from `Prospect History Bridge` where `Prospect Key`=%d ', $this->id);
                if ($result2 = $this->db->query($sql)) {
                    foreach ($result2 as $row2) {
                        $sql = sprintf(
                            "INSERT INTO `Customer History Bridge` VALUES (%d,%d,%s,%s,%s)", $customer->id, $row2['History Key'], prepare_mysql($row2['Deletable']), prepare_mysql($row2['Strikethrough']), prepare_mysql($row2['Type'])
                        );
                        //print "$sql\n";
                        $this->db->exec($sql);
                    }
                }


                $this->fast_update(
                    array(
                        'Prospect Status'                        => 'Registered',
                        'Prospect Registration Date'             => gmdate('Y-m-d H:i:s'),
                        'Prospect Customer Key'                  => $customer->id,
                        'Prospect Customer Assigned by User Key' => $this->editor['User Key']

                    )
                );


                $history_data = array(
                    'History Abstract' => sprintf(
                        _('Prospect manually linked to customer %s'),
                        '<span class="button padding_left_5" onClick="change_view(\'customers/'.$customer->get('Store Key').'/'.$customer->id.'\')"><i class="fa fa-user "></i> <span class="link">'.$customer->get('Name').'</span> (<span class="link">'
                        .$customer->get_formatted_id().'</span>)</span>'
                    ),


                    'History Details' => '',
                    'Action'          => 'edited'
                );


                $this->add_subject_history(
                    $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
                );


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
        $email_template_type = get_object('Email_Template_Type', 'Invite Mailshot|'.$this->get('Prospect Store Key'), 'code_store');


        if (!$email_template_type->id) {
            $this->error = true;
            $this->msg   = 'Email Campaign Type Invite for this store not found';

            return;
        }


        $email_template = get_object('Email_Template', $email_template_key);
        if (!$email_template->id) {
            $this->error = true;
            $this->msg   = 'Email_Template not found';

            return;
        }
        $published_email_template = get_object('published_email_template', $email_template->get('Email Template Published Email Key'));


        $send_data = array(
            'Email_Template_Type' => $email_template_type,
            'Email_Template'      => $email_template,


        );


        $published_email_template->send($this, $send_data);


        if ($published_email_template->sent) {


            $history_data = array(
                'History Abstract' => '<i class="fal fa-paper-plane fa-fw"></i> '._('Invitation email sent').' '.sprintf(
                        '<span class="link" onclick="change_view(\'prospects/%d/%d/email/%d\')" >%s</span>', $this->get('Store Key'), $this->id, $published_email_template->email_tracking->id, $published_email_template->get('Published Email Template Subject')


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
                        'Prospect Last Contacted Date'  => gmdate('Y-m-d H:i:s'),

                    )
                );
                if ($this->editor['User Key']) {


                    include_once('class.Sales_Representative.php');
                    $sales_representative = new Sales_Representative(
                        'find', array(
                                  'Sales Representative User Key' => $this->editor['User Key'],
                                  'editor'                        => $this->editor
                              )
                    );
                    $sales_representative->fast_update(array('Sales Representative Prospect Agent' => 'Yes'));

                    $this->fast_update(
                        array(

                            'Prospect User Key'                 => $this->editor['User Key'],
                            'Prospect Sales Representative Key' => $sales_representative->id

                        )
                    );
                }

            } else {
                $this->fast_update(
                    array(
                        'Prospect Last Contacted Date' => gmdate('Y-m-d H:i:s'),
                    )
                );
            }

        } else {
            print $published_email_template->msg;
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


    }

    function update_status($value, $extra_args = false) {

        switch ($value) {
            case 'Invoiced':

                $invoice = $extra_args;


                if (($this->get('Prospect Emails Sent Number') > 0 or $this->get('Prospect Calls Number') > 0 or $this->get('Prospect First Contacted Date') != '') and $this->get('Prospect Status') == 'Registered') {
                    $this->fast_update(
                        array(
                            'Prospect Status' => 'Invoiced',
                        )
                    );


                    if ($this->get('Prospect Invoiced Date') == '') {


                        $this->fast_update(
                            array(
                                'Prospect Invoiced Date' => gmdate('Y-m-d H:i:s'),

                            )
                        );

                        $history_data = array(
                            'History Abstract' => sprintf(
                                _('Prospect has been invoiced, %s'),
                                '<span class="button padding_left_5" onClick="change_view(\'invoices/'.$invoice->get('Invoice Store Key').'/'.$invoice->id.'\')"><i class="fa fa-file-invoice-dollar "></i> <span class="link">'.$invoice->get('Invoice Public ID')
                                .'</span></span>'
                            ),


                            'History Details' => '',
                            'Action'          => 'edited'
                        );


                        $this->add_subject_history(
                            $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
                        );


                    }


                }


                break;

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


                if ($this->data['Prospect Status'] == 'Contacted' or $this->data['Prospect Status'] == 'NoContacted' or $this->data['Prospect Status'] == 'Bounced') {

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
            case 'Bounced':


                if ($this->data['Prospect Status'] == 'Contacted' or $this->data['Prospect Status'] == 'NoContacted') {

                    $this->fast_update(
                        array(
                            'Prospect Status'    => 'Bounced',
                            'Prospect Lost Date' => gmdate('Y-m-d H:i:s')
                        )
                    );


                    $history_data = array(
                        'History Abstract' => _('Email bounced'),
                        'History Details'  => '',
                        'Action'           => 'edited'
                    );

                    $this->add_subject_history(
                        $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
                    );


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
                } elseif ($this->data['Prospect Status'] == 'Bounced') {

                    $this->fast_update(
                        array(
                            'Prospect Status'    => $value,
                            'Prospect Lost Date' => ''
                        )
                    );


                    $history_data = array(
                        'History Abstract' => _('Bounced status removed'),
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
                } elseif ($this->data['Prospect Status'] == 'Registered' or $this->data['Prospect Status'] == 'Invoiced') {

                    $this->fast_update(
                        array(
                            'Prospect Status'                        => $value,
                            'Prospect Lost Date'                     => '',
                            'Prospect Lost Registered'               => '',
                            'Prospect Lost Invoiced'                 => '',
                            'Prospect Customer Assigned by User Key' => ''
                        )
                    );


                    $history_data = array(
                        'History Abstract' => sprintf(
                            _('Prospect unlinked from customer %s'),
                            '<span class="button padding_left_5" onClick="change_view(\'customers/'.$this->customer->get('Store Key').'/'.$this->customer->id.'\')"><i class="fa fa-user "></i> <span class="link">'.$this->customer->get('Name')
                            .'</span> (<span class="link">'.$this->customer->get_formatted_id().'</span>)</span>'
                        ),
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

    function mailshot_sent($tracking_key, $subject) {

        $history_data = array(
            'History Abstract' => '<i class="fal fa-paper-plane fa-fw"></i> '._('Invitation email sent').' '.sprintf(
                    '<span class="link" onclick="change_view(\'prospects/%d/%d/email/%d\')" >%s</span>', $this->get('Store Key'), $this->id, $tracking_key, $subject
                ),
            'History Details'  => '',
            'Action'           => 'edited'
        );


        $this->add_subject_history(
            $history_data, true, 'No', 'Emails', 'Prospect', $this->id
        );


        if ($this->data['Prospect Status'] == 'NoContacted') {
            $this->fast_update(
                array(
                    'Prospect Status'               => 'Contacted',
                    'Prospect First Contacted Date' => gmdate('Y-m-d H:i:s'),
                    'Prospect Last Contacted Date'  => gmdate('Y-m-d H:i:s'),

                )
            );


        } else {
            $this->fast_update(
                array(
                    'Prospect Last Contacted Date' => gmdate('Y-m-d H:i:s'),
                )
            );
        }


    }

    function opt_out($date) {


        if ($this->data['Prospect Status'] == 'Contacted' or $this->data['Prospect Status'] == 'NoContacted') {

            $this->fast_update(
                array(
                    'Prospect Status'    => 'NotInterested',
                    'Prospect Lost Date' => $date
                )
            );
            $history_data = array(
                'History Abstract' => _('Recipient opt out'),
                'History Details'  => '',
                'Action'           => 'edited'
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );
        } elseif ($this->data['Prospect Status'] == 'NotInterested') {
            $history_data = array(
                'History Abstract' => _('Recipient opt out again'),
                'History Details'  => '',
                'Action'           => 'edited'
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );
        } else {
            return;
        }


    }

    function unlink_customer() {


        if ($this->get('Prospect Status') == 'Registered' or $this->get('Prospect Status') == 'Invoiced') {

            if (($this->get('Prospect Emails Sent Number') > 0 or $this->get('Prospect Calls Number') > 0 or $this->get('Prospect First Contacted Date') != '')) {
                $status = 'Contacted';
            } else {
                $status = 'NoContacted';
            }
            $this->update_status($status);

        }

    }

    function send_personalized_invitation($published_email_template) {


        $email_template_type = get_object('Email_Template_Type', 'Invite|'.$this->get('Store Key'), 'code_store');


        if (!$email_template_type->id) {
            $this->error = true;
            $this->msg   = 'Email Campaign Type Invite for this store not found';

            return;
        }


        $send_data = array(
            'Email_Template_Type' => $email_template_type,

        );

        $published_email_template->send($this, $send_data);


        if ($published_email_template->sent) {


            $history_data = array(
                'History Abstract' => '<i class="fal fa-paper-plane fa-fw"></i> '._('Invitation email sent').' '.sprintf(
                        '<span class="link" onclick="change_view(\'prospects/%d/%d/email/%d\')" >%s</span>', $this->get('Store Key'), $this->id, $published_email_template->email_tracking->id, $published_email_template->get('Published Email Template Subject')
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
                        'Prospect Last Contacted Date'  => gmdate('Y-m-d H:i:s'),

                    )
                );
                if ($this->editor['User Key']) {


                    include_once('class.Sales_Representative.php');
                    $sales_representative = new Sales_Representative(
                        'find', array(
                                  'Sales Representative User Key' => $this->editor['User Key'],
                                  'editor'                        => $this->editor
                              )
                    );
                    $sales_representative->fast_update(array('Sales Representative Prospect Agent' => 'Yes'));

                    $this->fast_update(
                        array(

                            'Prospect User Key'                 => $this->editor['User Key'],
                            'Prospect Sales Representative Key' => $sales_representative->id

                        )
                    );
                }

            } else {
                $this->fast_update(
                    array(
                        'Prospect Last Contacted Date' => gmdate('Y-m-d H:i:s'),

                    )
                );
            }


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
        }

        return $templates;
    }


    function update_prospect_data() {

        $calls          = 0;
        $emails_sent    = 0;
        $emails_open    = 0;
        $emails_clicked = 0;


        $sql = sprintf(
            'select count(*) as emails_sent , sum(if(`Email Tracking Number Reads`>0,1,0))  open,  sum(if(`Email Tracking Number Clicks`>0,1,0))  clicked from `Email Tracking Dimension`  where `Email Tracking Recipient`="Prospect" and `Email Tracking Recipient Key`=%d   ',
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $emails_sent    = $row['emails_sent'];
                $emails_open    = $row['open'];
                $emails_clicked = $row['clicked'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $this->fast_update(
            array(
                'Prospect Calls Number'          => $calls,
                'Prospect Emails Sent Number'    => $emails_sent,
                'Prospect Emails Open Number'    => $emails_open,
                'Prospect Emails Clicked Number' => $emails_clicked


            )

        );


    }

    function has_telephone() {


        if (!empty($this->data[$this->table_name.' Main Plain Telephone']) or !empty($this->data[$this->table_name.' Main Plain Mobile'])) {
            return true;
        } else {
            return false;
        }

    }

    function has_address() {

        if (empty($this->data[$this->table_name.' Contact Address Line 1']) and empty($this->data[$this->table_name.' Contact Address Line 1']) and empty($this->data[$this->table_name.' Postal Code']) and empty($this->data[$this->table_name.' Address Sorting Code'])

        ) {
            return false;
        } else {
            return true;
        }

    }

}



