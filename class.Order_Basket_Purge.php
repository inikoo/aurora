<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 27 September 2018 at 11:41:48 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 3.0


*/
include_once 'class.DB_Table.php';

class Order_Basket_Purge extends DB_Table {

    var $new = false;
    var $updated_data = array();

    function __construct($arg1 = false, $arg2 = false) {


        global $db;
        $this->db = $db;

        $this->table_name    = 'Order Basket Purge';
        $this->ignore_fields = array(
            'Order Basket Purge Key',
        );

        if (!$arg1 and !$arg2) {
            $this->error = true;
            $this->msg   = 'No arguments';
        }
        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }


        if (is_array($arg2) and $arg1 = 'create') {
            $this->create($arg2);

            return;
        }

        $this->get_data($arg1, $arg2);

    }

    function get_data($tipo, $tag) {


        $sql = sprintf(
            "SELECT * FROM `Order Basket Purge Dimension` WHERE  `Order Basket Purge Key`=%d", $tag
        );


        if ($this->data = $this->db->query($sql)->fetch()) {

            $this->id = $this->data['Order Basket Purge Key'];
        }


    }


    function create($raw_data) {

        $this->editor = $raw_data['editor'];
        unset($raw_data['editor']);

        $data = $this->base_data();


        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {

                $data[$key] = _trim($value);

            }
        }


        $data['Order Basket Purge Date']     = gmdate('Y-m-d H:i:s');
        $data['Order Basket Purge User Key'] = $this->editor['User Key'];


        $keys   = '(';
        $values = 'values(';
        foreach ($data as $key => $value) {
            $keys .= "`$key`,";
            if ($key = '') {
                $values .= prepare_mysql($value, false).",";
            } else {
                $values .= prepare_mysql($value).",";
            }
        }


        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);


        $sql = "insert into `Order Basket Purge Dimension` $keys  $values";

        // print $sql;

        if ($this->db->exec($sql)) {
            $this->id = $this->db->lastInsertId();

            $this->get_data('id', $this->id);


            $this->new = true;

            $store = get_object('Store', $this->data['Order Basket Purge Store Key']);
            $store->update_email_campaign_data();


            switch ($this->get('Order Basket Purge Type')) {
                case 'Scheduled':
                    $history_abstract = _("Scheduled order's in basket purge created");

                    break;
                default:

                    $history_abstract = sprintf(_("Order's in basket purge created by %s"), $this->editor['Author Alias']);
                    break;
            }

            $this->update_estimated_orders_to_be_purged();

            $history_data = array(
                'History Abstract' => $history_abstract,
                'History Details'  => '',
                'Action'           => 'created'
            );

            $history_key = $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );


        } else {
            $this->error = true;
            $this->msg   = "Can not insert Order Basket Purge Dimension";
            exit("$sql\n");
        }


    }

    function get($key) {

        if (!$this->id) {
            return false;
        }

        switch ($key) {


            case ('State Index'):

                switch ($this->data['Order Basket Purge State']) {
                    case 'In Process':
                        return 10;
                        break;
                    case 'Purging':
                        return 20;
                        break;
                    case 'Cancelled':
                        return 70;
                        break;
                    case 'Finished':
                        return 100;
                        break;
                    default:
                        return 0;
                        break;
                }

                break;
            case 'State':
                //'In Process','ComposingEmail','Ready','Sending','Complete'
                switch ($this->data['Order Basket Purge State']) {
                    case 'In Process':
                        return _('Setting up purge');
                        break;
                    case 'Purging':
                        return _('Purging');
                        break;
                    case 'Cancelled':
                        return _('Cancelled');
                        break;

                    case 'Finished':
                        return _('Purge completed');
                        break;


                    default:
                        return $this->data['Order Basket Purge State'];
                        break;
                }


                break;

            case 'Inactive Days':

                return number($this->data['Order Basket Purge Inactive Days']);
                break;

            case 'Estimated Purges':
                return number($this->data['Order Basket Purge Estimated Orders']);
                break;
            case 'Estimated Amount':
            case 'Purged Amount':
                $store = get_object('Store', $this->data['Order Basket Purge Store Key']);

                return money($this->data['Order Basket Purge '.$key], $store->get('Store Currency Code'));
                break;
            case  'Purged Orders Info':



                if (!$this->data['Order Basket Purge Orders'] > 0) {


                    if ($this->data['Order Basket Purge State'] == 'Purging') {
                        return '<span class="very_discreet"><i class="fa fa-spin fa-spinner"></i> '._('Initializing purge').'</span>';
                    } else {
                        return '';
                    }

                }

                $orders_done=$this->data['Order Basket Purge Purged Orders']+$this->data['Order Basket Purge Exculpated']+$this->data['Order Basket Purge Errors'];

                $purged_orders_info = sprintf(_('Purged %s of %s'), '<b>'.number($orders_done).'</b>', '<b>'.number($this->data['Order Basket Purge Orders'])).'</b> ';


                if ($this->data['Order Basket Purge Orders'] > 0) {
                    $purged_orders_info .= ' <span class="discreet">('.percentage($orders_done, $this->data['Order Basket Purge Orders']).')</span>';


                    if ($this->data['Order Basket Purge State'] == 'Purging') {

                        if (isset($this->start_send)) {
                            $start_datetime = $this->start_send;
                        } else {
                            $start_datetime = $this->data['Order Basket Purge Start Purge Date'];
                        }


                        $offset = (isset($this->sent) ? $this->sent : 0);


                        if ($orders_done - $offset > 5) {
                            $purged_orders_info .= ' <span class="discreet padding_left_5">'.eta($orders_done - $offset, $this->data['Order Basket Purge Orders'] - $offset, $start_datetime).'</span>';

                        }

                    }
                }


                return $purged_orders_info;
                break;


            case 'Date':

            case 'Start Purge Date':
            case 'End Purge Date':
                if ($this->data['Order Basket Purge '.$key] != '') {
                    return strftime('%e %b %y %k:%M', strtotime($this->data['Order Basket Purge '.$key]));
                } else {
                    return '';
                }


                break;


            default:
                if (isset($this->data[$key])) {
                    return $this->data[$key];
                }

                if (array_key_exists('Order Basket Purge '.$key, $this->data)) {
                    return $this->data[$this->table_name.' '.$key];
                }
        }

        return false;
    }

    function update_estimated_orders_to_be_purged() {


        if ($this->get('State Index') < 20) {


            $estimated_orders       = 0;
            $estimated_transactions = 0;
            $estimated_amount       = 0;

            $sql = sprintf(
                'SELECT count(DISTINCT O.`Order Key`) AS orders,sum(`Order Number Items`) AS transactions,sum(`Order Total Net Amount`) AS amount FROM `Order Dimension` O  WHERE `Order State`="InBasket" AND `Order Store Key`=%d AND `Order Last Updated Date`<= CURRENT_DATE - INTERVAL %d DAY',
                $this->data['Order Basket Purge Store Key'], $this->data['Order Basket Purge Inactive Days']
            );

            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {

                    $estimated_orders       = $row['orders'];
                    $estimated_transactions = $row['transactions'];
                    $estimated_amount       = $row['amount'];
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }


            $this->fast_update(
                array(
                    'Order Basket Purge Estimated Orders'       => $estimated_orders,
                    'Order Basket Purge Estimated Transactions' => $estimated_transactions,
                    'Order Basket Purge Estimated Amount'       => $estimated_amount

                )
            );

        }

    }

    function delete() {


        if (in_array(
            $this->data['Order Basket Purge State'], array(
                                                       'In Process',

                                                   )
        )) {


            $store = get_object('Store', $this->data['Order Basket Purge Store Key']);

            $sql = sprintf('SELECT `History Key` FROM `Order Basket Purge Order Fact` WHERE `Order Basket Purge Key`=%d ', $this->id);

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $sql = sprintf("DELETE FROM `History Dimension` WHERE  `History Key`=%d", $row['History Key']);
                    $this->db->exec($sql);
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql\n";
                exit;
            }

            $sql = sprintf("DELETE FROM `Order Basket Purge Order Fact` WHERE `Order Basket Purge Key`=%d ", $this->id);
            $this->db->exec($sql);


            $sql = sprintf(
                "DELETE FROM `Order Basket Purge Dimension` WHERE `Order Basket Purge Key`=%d", $this->id
            );

            $this->db->exec($sql);

            $store->update_purges_data(); // todo make stats for purges

            $this->updated = true;
            $this->deleted = true;


            return sprintf('orders/%d/dashboard/website/purges', $store->id);


        } else {
            $this->error = true;
            $this->msg   = 'Order Basket Purge can not be deleted';
        }

    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        switch ($field) {

            case 'Order Basket Purge State':

                $this->update_state($value);
                break;


            case 'Order Basket Purge Inactive Days':

                if (!is_numeric($value)) {
                    $this->error = true;
                    $this->msg   = 'numeric value required';

                    return;
                }

                if ($value < 1) {
                    $this->error = true;
                    $this->msg   = 'minimum value is 1';

                    return;
                }
                $this->fast_update(array('Order Basket Purge Inactive Days' => $value));
                $this->update_estimated_orders_to_be_purged();


                $this->update_metadata = array(
                    'class_html' => array(
                        'Estimated_Amount' => $this->get('Estimated Amount'),
                        'Estimated_Orders' => $this->get('Estimated Orders'),

                    ),

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

    function update_state($value) {

        $operations = array();


        switch ($value) {


            case 'Purging':


                if ($this->data['Order Basket Purge State'] == 'Purging') {
                    $this->error = true;
                    $this->msg   = _('Already purging');

                    return;
                }
                if ($this->data['Order Basket Purge State'] == 'Finished') {
                    $this->error = true;
                    $this->msg   = _('Purge already finished');

                    return;
                }
                if ($this->data['Order Basket Purge State'] == 'Cancelled') {
                    $this->error = true;
                    $this->msg   = _('Purge cancelled');

                    return;
                }


                $this->fast_update(
                    array(
                        'Order Basket Purge State' => $value,
                    )
                );

                $this->fast_update(
                    array(
                        'Order Basket Purge Start Purge Date' => gmdate('Y-m-d H:i:s'),
                    )
                );


                $sql = sprintf(
                    'select `Order Key`,`Order Last Updated Date`  from `Order Dimension` O   where `Order State`="InBasket"  and `Order Store Key`=%d  and `Order Last Updated Date`<= CURRENT_DATE - INTERVAL %d DAY ', $this->data['Order Basket Purge Store Key'],
                    $this->data['Order Basket Purge Inactive Days']
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {

                        $sql = sprintf(
                            'insert into `Order Basket Purge Order Fact` (`Order Basket Purge Order Basket Purge Key`,`Order Basket Purge Order Order Key`,`Order Basket Purge Order Last Updated Date`) values (%d,%d,%s) ', $this->id, $row['Order Key'],
                            prepare_mysql($row['Order Last Updated Date'])
                        );
                        $this->db->exec($sql);

                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "Error: $sql\n";
                    exit;
                }


                $sql = sprintf('select count(*) as num from `Order Basket Purge Order Fact` where `Order Basket Purge Order Basket Purge Key`=%d  ', $this->id);

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $this->fast_update(array('Order Basket Purge Orders' => $row['num']));

                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

                $account = get_object('Account', 1);
                require_once 'utils/new_fork.php';
                new_housekeeping_fork(
                    'au_housekeeping', array(
                    'type'      => 'start_purge',
                    'purge_key' => $this->id,
                    'editor'    => $this->editor
                ), $account->get('Account Code'), $this->db
                );


                $operations = array(
                    'stop_operations',

                );

                switch ($this->get('Order Basket Purge Type')) {
                    case 'Manual':
                        $history_abstract = _("Purge started");


                        $history_data = array(
                            'History Abstract' => $history_abstract,
                            'History Details'  => '',
                            'Action'           => 'edited'
                        );

                        $this->add_subject_history(
                            $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
                        );

                        break;

                }


                break;


            case 'Finished':


                $this->fast_update(
                    array(
                        'Order Basket Purge State'          => $value,
                        'Order Basket Purge End Purge Date' => gmdate('Y-m-d H:i:s'),
                    )
                );

                $operations = array();

                break;

            case 'Cancelled':


                $this->fast_update(
                    array(
                        'Order Basket Purge State'          => $value,
                        'Order Basket Purge End Purge Date' => gmdate('Y-m-d H:i:s'),
                    )
                );


                $sql = sprintf(
                    'update `Order Basket Purge Order Fact` set `Order Basket Purge Order Status`="Cancelled" where `Order Basket Purge Order Basket Purge Key`=%d and `Order Basket Purge Order Status`="In Process" ', $this->id
                );
                $this->db->exec($sql);

                $this->update_purged_orders_data();

                $operations = array();


                $history_abstract = _("Purge cancelled");


                $history_data = array(
                    'History Abstract' => $history_abstract,
                    'History Details'  => '',
                    'Action'           => 'edited'
                );

                $this->add_subject_history(
                    $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
                );


                break;
        }


        $this->update_metadata = array(
            'class_html'  => array(
                'Purge_State' => $this->get('State'),


            ),
            'operations'  => $operations,
            'state_index' => $this->get('State Index'),
            'state'       => $this->data['Order Basket Purge State']
        );


        switch ($this->data['Order Basket Purge State']) {
            case 'Purging':
                $this->update_metadata['hide'] = array('estimated_orders_pre_sent');
                $this->update_metadata['show'] = array('estimated_orders_post_sent');

                $this->update_metadata['show']                           = array('purged_data');
                $this->update_metadata['class_html']['Sent_Emails_Info'] = $this->get('Purged Orders Info');


                break;
            // case 'Sent':
            //   $this->update_metadata['add_class'] = array('sent_node' => 'complete');
            // break;

        }


    }

    function update_purged_orders_data() {


        $purged_orders       = 0;
        $purged_transactions = 0;
        $purged_amount       = 0;
        $exculpated          = 0;
        $cancelled           = 0;
        $errors              = 0;

        $sql = sprintf(
            'SELECT count(DISTINCT O.`Order Key`) AS orders,sum(`Order Number Items`) AS transactions,sum(`Order Total Net Amount`) AS amount 
                FROM  `Order Basket Purge Order Fact` left join  `Order Dimension` O  on (`Order Key`=`Order Basket Purge Order Order Key`)
                WHERE `Order Basket Purge Order Basket Purge Key`=%d AND `Order Basket Purge Order Status`="Purged"', $this->id
        );
        //print $sql;
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $purged_orders       = $row['orders'];
                $purged_transactions = $row['transactions'];
                $purged_amount       = $row['amount'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }
        $sql = sprintf(
            'SELECT count(*) AS orders  FROM  `Order Basket Purge Order Fact`
                WHERE `Order Basket Purge Order Basket Purge Key`=%d AND `Order Basket Purge Order Status`="Exculpated"', $this->id
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $exculpated = $row['orders'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }
        $sql = sprintf(
            'SELECT count(*)  AS orders  FROM  `Order Basket Purge Order Fact`
                WHERE `Order Basket Purge Order Basket Purge Key`=%d AND `Order Basket Purge Order Status`="Error"', $this->id
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $errors = $row['orders'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        $sql = sprintf(
            'SELECT count(*)  AS orders  FROM  `Order Basket Purge Order Fact`
                WHERE `Order Basket Purge Order Basket Purge Key`=%d AND `Order Basket Purge Order Status`="Cancelled"', $this->id
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $cancelled = $row['orders'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $this->fast_update(
            array(
                'Order Basket Purge Purged Orders'       => $purged_orders,
                'Order Basket Purge Purged Transactions' => $purged_transactions,
                'Order Basket Purge Purged Amount'       => $purged_amount,
                'Order Basket Purge Exculpated'          => $exculpated,
                'Order Basket Purge Cancelled'           => $cancelled,
                'Order Basket Purge Errors'              => $errors
            )
        );

    }

    function get_field_label($field) {

        switch ($field) {


            case 'Order Basket Purge Inactive Days':
                $label = _('Inactive days in basket purge criteria');
                break;

            default:
                $label = $field;

        }

        return $label;

    }

    function purge() {


        // $store = get_object('Store', $this->data['Order Basket Purge Store Key']);

        //  if (isset($this->socket)) {
        //      $published_email_template->socket = $this->socket;
        //  }


        $account = get_object('Account', 1);

        $sql = sprintf('select `Order Basket Purge Order Order Key` from `Order Basket Purge Order Fact` where `Order Basket Purge Order Basket Purge Key`=%d and `Order Basket Purge Order Status`="In Process" ', $this->id);

        // print $sql;


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $sql = sprintf('select `Order Basket Purge State` from `Order Basket Purge Dimension` where `Order Basket Purge Key`=%d ', $this->id);
                if ($result2 = $this->db->query($sql)) {
                    if ($row2 = $result2->fetch()) {
                        if ($row2['Order Basket Purge State'] == 'Cancelled') {


                            $this->update_metadata = array(
                                'class_html' => array(
                                    'Purge_State' => $this->get('State'),


                                ),
                                'hide'       => array(
                                    'estimated_orders_post_sent',
                                    'purge_operation'
                                ),

                            );


                            $this->socket->send(
                                json_encode(
                                    array(
                                        'channel' => 'real_time.'.strtolower($account->get('Account Code')),
                                        'objects' => array(
                                            array(
                                                'object' => 'purge',
                                                'key'    => $this->id,

                                                'update_metadata' => $this->get_update_metadata()

                                            )

                                        ),


                                    )
                                )
                            );

                            return;
                        }
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

                $order=get_object('Order',$row['Order Basket Purge Order Order Key']);
                $order->editor = $this->editor;
                if ($order->get('Order State') != 'InBasket') {

                    $operation_status = 'Exculpated';

                    $sql = sprintf(
                        'update `Order Basket Purge Order Fact`  set `Order Basket Purge Order Status`="Exculpated" , `Order Basket Purge Purged Date`=%s where `Order Basket Purge Order Basket Purge Key`=%d and `Order Basket Purge Order Order Key`=%d',
                        prepare_mysql(gmdate('Y-m-d H:i:s')), $this->id, $row['Order Basket Purge Order Order Key']
                    );

                    $this->db->exec($sql);


                } else {
                    if ($this->get('Order Basket Purge Type') == 'Scheduled') {
                        $note = _("cancelled due inactivity in the basket (Scheduled purge)");
                    } else {
                        $_user = get_object('User', $this->get('Order Basket Purge User Key'));
                        $note  = sprintf(_("cancelled due inactivity in the basket (Purged by %s)"), $_user->get('Alias'));

                    }

                    if ($order->cancel($note, $fork = false)) {

                        $operation_status = 'Purged';

                        $sql = sprintf(
                            'update `Order Basket Purge Order Fact`  set `Order Basket Purge Order Status`="Purged" , `Order Basket Purge Purged Date`=%s where `Order Basket Purge Order Basket Purge Key`=%d and `Order Basket Purge Order Order Key`=%d',
                            prepare_mysql(gmdate('Y-m-d H:i:s')), $this->id, $row['Order Basket Purge Order Order Key']
                        );

                    } else {

                        $operation_status = 'Error';

                        $sql = sprintf(
                            'update `Order Basket Purge Order Fact`  set `Order Basket Purge Order Status`="Error" , `Order Basket Purge Purged Date`=%s,`Order Basket Purge Note`=%s where `Order Basket Purge Order Basket Purge Key`=%d and `Order Basket Purge Order Order Key`=%d',
                            prepare_mysql(gmdate('Y-m-d H:i:s')), prepare_mysql($order->msg), $this->id, $row['Order Basket Purge Order Order Key']
                        );
                    }
                    $this->db->exec($sql);
                }


                $this->update_purged_orders_data();

                if (isset($this->socket)) {

                    switch ($operation_status) {
                        case('In Process'):
                            $purge_status = _('In process');
                            break;
                        case('Purged'):
                            $purge_status = _('Purged');
                            break;
                        case('Exculpated'):
                            $purge_status = _('Exculpated');
                            break;
                        case('Cancelled'):
                            $purge_status = _('Purge cancelled');
                            break;
                        default:
                            $purge_status = $operation_status;

                    }


                    $this->socket->send(
                        json_encode(
                            array(
                                'channel' => 'real_time.'.strtolower($account->get('Account Code')),
                                'objects' => array(
                                    array(
                                        'object' => 'purge',
                                        'key'    => $this->id,


                                        'update_metadata' => array(
                                            'class_html' => array(
                                                'Purged_Orders_Info'        => $this->get('Purged Orders Info'),
                                                'Purged_Orders'             => $this->get('Purged Orders'),
                                                'Purged_Transactions'       => $this->get('Purged Transactions'),
                                                'Purged_Amount'             => $this->get('Purged Amount'),
                                                'purged_status_'.$order->id => $purge_status,
                                                'purged_date_'.$order->id   => ($operation_status == 'Purged' ? strftime("%a %e %b %Y %H:%M %Z") : '')


                                            ),

                                            'show' => array(
                                                'estimated_orders_post_sent',
                                            ),
                                            'hide' => array(
                                                'estimated_orders_pre_sent',
                                            ),
                                        )

                                    )

                                ),


                            )
                        )
                    );
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        // exit('xxx');
        $this->update_state('Finished');

        if (isset($this->socket)) {



            $this->update_metadata = array(
                'class_html' => array(
                    'Purge_State' => $this->get('State'),


                ),
                'hide'       => array(
                    'estimated_orders_post_sent',
                    'purge_operation'
                ),

            );


            $this->socket->send(
                json_encode(
                    array(
                        'channel' => 'real_time.'.strtolower($account->get('Account Code')),
                        'objects' => array(
                            array(
                                'object' => 'purge',
                                'key'    => $this->id,

                                'update_metadata' => $this->get_update_metadata()

                            )

                        ),


                    )
                )
            );
        }


    }


}


?>
