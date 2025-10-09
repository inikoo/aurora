<?php
/*


  About:
  Author: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2009, Inikoo

  Version 2.0
*/


include_once 'class.DB_Table.php';

/**
 * Class Store
 */
class Store extends DB_Table
{


    public Smarty $smarty;


    function __construct($a1, $a2 = false, $a3 = false, $_db = false)
    {
        if (!$_db) {
            global $db;
            $this->db = $db;
        } else {
            $this->db = $_db;
        }

        $this->table_name    = 'Store';
        $this->ignore_fields = array('Store Key');


        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } elseif ($a1 == 'find') {
            $this->find($a2, $a3);
        } else {
            $this->get_data($a1, $a2);
        }
    }

    function get_data($tipo, $tag)
    {
        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Store Dimension` WHERE `Store Key`=%d",
                $tag
            );
        } elseif ($tipo == 'code') {
            $sql = sprintf(
                "SELECT * FROM `Store Dimension` WHERE `Store Code`=%s",
                prepare_mysql($tag)
            );
        } else {
            return;
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id         = $this->data['Store Key'];
            $this->code       = $this->data['Store Code'];
            $this->properties = json_decode($this->data['Store Properties'], true);
            $this->settings   = json_decode($this->data['Store Settings'], true);
        }
    }

    function find($raw_data, $options)
    {
        if (isset($raw_data['editor'])) {
            foreach ($raw_data['editor'] as $key => $value) {
                if (array_key_exists($key, $this->editor)) {
                    $this->editor[$key] = $value;
                }
            }
        }

        $this->found     = false;
        $this->found_key = false;

        $create = '';

        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }


        $data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $data)) {
                $data[$key] = _trim($value);
            }
        }

        //    print_r($raw_data);

        if ($data['Store Code'] == '') {
            $this->error = true;
            $this->msg   = 'Store code empty';

            return;
        }

        if ($data['Store Name'] == '') {
            $data['Store Name'] = $data['Store Code'];
        }


        $sql = sprintf(
            "SELECT `Store Key` FROM `Store Dimension` WHERE `Store Code`=%s  ",
            prepare_mysql($data['Store Code'])
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->found     = true;
                $this->found_key = $row['Store Key'];
                $this->get_data('id', $this->found_key);
                $this->duplicated_field = 'Store Code';

                return;
            }
        }
        $sql = sprintf(
            "SELECT `Store Key` FROM `Store Dimension` WHERE `Store Name`=%s  ",
            prepare_mysql($data['Store Name'])
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->found     = true;
                $this->found_key = $row['Store Key'];
                $this->get_data('id', $this->found_key);
                $this->duplicated_field = 'Store Name';

                return;
            }
        }


        if ($create and !$this->found) {
            $this->create($data);
        }
    }

    function create($data)
    {
        $account = new Account($this->db);
        $account->load_properties();
        $account->editor = $this->editor;

        $this->new = false;
        $base_data = $this->base_data();


        $data['Store Properties'] = '{}';
        $data['Store Settings']   = '{}';

        foreach ($data as $key => $value) {
            if (array_key_exists($key, $base_data)) {
                $base_data[$key] = _trim($value);
            }
        }


        $sql = sprintf(
            "INTO `Store Dimension` (%s) values (%s)",
            '`'.join('`,`', array_keys($base_data)).'`',
            join(',', array_fill(0, count($base_data), '?'))
        );

        $stmt = $this->db->prepare('INSERT '.$sql);


        $i = 1;
        foreach ($base_data as $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {
            $this->id = $this->db->lastInsertId();


            $this->msg = _("Store added");
            $this->get_data('id', $this->id);
            $this->new = true;

            $this->fast_update_json_field('Store Settings', 'tax_authority', $account->properties('tax_authority'));
            $this->fast_update_json_field('Store Settings', 'tax_country_code', $account->properties('tax_country_code'));

            if (is_numeric($this->editor['User Key']) and $this->editor['User Key'] > 1) {
                $sql = sprintf(
                    "INSERT INTO `User Right Scope Bridge` VALUES(%d,'Store',%d)",
                    $this->editor['User Key'],
                    $this->id
                );
                $this->db->exec($sql);
            }

            $sql = "INSERT INTO `Store Default Currency` (`Store Key`) VALUES(".$this->id.");";
            $this->db->exec($sql);


            $sql = "INSERT INTO `Store Data` (`Store Key`) VALUES (?)";

            $this->db->prepare($sql)->execute(array(
                                                  $this->id
                                              ));


            $sql = sprintf(
                "INSERT INTO `Store DC Data` (`Store Key`) VALUES (%d)",
                $this->id
            );
            $this->db->exec($sql);


            $shipping_zone_schema = $this->create_shipping_zone_schema(array(
                                                                           'Shipping Zone Schema Label'         => sprintf(_('%s shipping zones'), $this->get('Code')),
                                                                           'Shipping Zone Schema Type'          => 'Current',
                                                                           'Shipping Zone Schema Default Price' => json_encode(array(
                                                                                                                                   'type' => 'TBC'
                                                                                                                               ))
                                                                       ));
            $this->fast_update_json_field('Store Properties', 'current_shipping_zone_schema', $shipping_zone_schema->id);


            require_once 'conf/timeseries.php';

            $timeseries      = get_time_series_config();
            $timeseries_data = $timeseries['Store'];

            include_once 'class.Timeserie.php';

            foreach ($timeseries_data as $time_series_data) {
                $time_series_data['editor'] = $this->editor;
                $this->create_timeseries($time_series_data);
            }


            include_once 'class.Payment_Service_Provider.php';
            $account_payments        = new Payment_Service_Provider('block', 'Accounts');
            $payment_account_credits = $account_payments->create_payment_account(array(
                                                                                     'Payment Account Block' => 'Accounts',
                                                                                     'Payment Account Code'  => 'IA_'.$this->get('Code'),
                                                                                     'Payment Account Name'  => 'Accounts '.$this->get('Code'),

                                                                                 ));

            $this->fast_update(array(
                                   'Store Customer Payment Account Key' => $payment_account_credits->id
                               ));


            $customer_root_category = $account->create_category([
                                                                    'Category Code'      => 'Cust_.'.$this->get('Store Code'),
                                                                    'Category Label'     => 'Cust_.'.$this->get('Store Code'),
                                                                    'Category Scope'     => 'Customer',
                                                                    'Category Subject'   => 'Customer',
                                                                    'Category Store Key' => $this->id
                                                                ]);
            $this->fast_update_json_field('Store Properties', 'customer_root_category_key', $customer_root_category->id);


            $families_category_data = array(
                'Category Code'      => 'Fam.'.$this->get('Store Code'),
                'Category Label'     => 'Families',
                'Category Scope'     => 'Product',
                'Category Subject'   => 'Product',
                'Category Store Key' => $this->id
            );


            $families = $account->create_category($families_category_data);


            $departments_category_data = array(
                'Category Code'      => 'Dept.'.$this->get('Store Code'),
                'Category Label'     => 'Departments',
                'Category Scope'     => 'Product',
                'Category Subject'   => 'Category',
                'Category Store Key' => $this->id


            );


            $departments = $account->create_category($departments_category_data);

            $this->update(
                array(

                    'Store Family Category Key'     => $families->id,
                    'Store Department Category Key' => $departments->id,
                ),
                'no_history'
            );


            $order_recursion_campaign_data = array(
                'Deal Campaign Name'       => 'Order recursion incentive',
                'Deal Campaign Icon'       => '<i class="far fa-repeat-1"></i>',
                'Deal Campaign Code'       => 'OR',
                'Deal Campaign Valid From' => gmdate('Y-m-d'),
                'Deal Campaign Valid To'   => '',


            );


            $order_recursion_campaign = $this->create_campaign($order_recursion_campaign_data);


            $deal_data = array(
                'Deal Name'                  => 'Order Recursion Campaign',
                'Deal Description'           => "",
                'Deal Term Allowances Label' => "",
                'Deal Trigger'               => 'Order',
                'Deal Trigger Key'           => '0',
                'Deal Trigger XHTML Label'   => '',
                'Deal Terms Type'            => 'Order Interval',
                'Deal Terms'                 => '30 day',
                'Deal Begin Date'            => gmdate('Y-m-d H:i:s')
            );


            $order_recursion_campaign->create_deal($deal_data);


            $bulk_discounts_campaign_data = array(
                'Deal Campaign Name'       => 'Bulk discount',
                'Deal Campaign Icon'       => '<i class="far fa-ball-pile"></i>',
                'Deal Campaign Code'       => 'VL',
                'Deal Campaign Valid From' => gmdate('Y-m-d'),
                'Deal Campaign Valid To'   => '',

            );

            $bulk_discounts_campaign = $this->create_campaign($bulk_discounts_campaign_data);

            $first_order_incentive_campaign_data = array(
                'Deal Campaign Code'       => 'FO',
                'Deal Campaign Icon'       => '<i class="far fa-trophy-alt"></i>',
                'Deal Campaign Name'       => 'First order incentive',
                'Deal Campaign Valid From' => gmdate('Y-m-d'),
                'Deal Campaign Valid To'   => '',


            );

            $first_order_incentive_campaign = $this->create_campaign($first_order_incentive_campaign_data);


            $this->update(
                array(

                    'Store Order Recursion Campaign Key' => $order_recursion_campaign->id,
                    'Store Bulk Discounts Campaign Key'  => $bulk_discounts_campaign->id,
                    'Store First Order Campaign Key'     => $first_order_incentive_campaign->id,

                ),
                'no_history'
            );


            $campaign_data = array(
                'Deal Campaign Code' => 'CU',
                'Deal Campaign Icon' => '<i class="far fa-user-crown"></i>',

                'Deal Campaign Name'       => 'Customers offers',
                'Deal Campaign Valid From' => gmdate('Y-m-d'),
                'Deal Campaign Valid To'   => '',
            );
            $this->create_campaign($campaign_data);

            $campaign_data = array(
                'Deal Campaign Code' => 'CA',
                'Deal Campaign Icon' => '<i class="far fa-bullseye-arrow"></i>',

                'Deal Campaign Name'       => 'Family offers',
                'Deal Campaign Valid From' => gmdate('Y-m-d'),
                'Deal Campaign Valid To'   => '',
            );
            $this->create_campaign($campaign_data);

            $campaign_data = array(
                'Deal Campaign Code'       => 'PO',
                'Deal Campaign Icon'       => '<i class="far fa-crosshairs"></i>',
                'Deal Campaign Name'       => 'Product offers',
                'Deal Campaign Valid From' => gmdate('Y-m-d'),
                'Deal Campaign Valid To'   => '',
            );
            $this->create_campaign($campaign_data);

            $campaign_data = array(
                'Deal Campaign Code' => 'SO',
                'Deal Campaign Name' => 'Store offers',
                'Deal Campaign Icon' => '<i class="far fa-badge-percent"></i>',

                'Deal Campaign Valid From' => gmdate('Y-m-d'),
                'Deal Campaign Valid To'   => '',
            );
            $this->create_campaign($campaign_data);

            $campaign_data = array(
                'Deal Campaign Code'       => 'VO',
                'Deal Campaign Icon'       => '<i class="far fa-money-bill-wave"></i>',
                'Deal Campaign Name'       => 'Vouchers',
                'Deal Campaign Valid From' => gmdate('Y-m-d'),
                'Deal Campaign Valid To'   => '',
            );
            $this->create_campaign($campaign_data);


            include_once 'utils/create_email_templates.php';

            create_email_templates($this->db, $this);


            $sql = "insert into `Store Emails Data`  (`Store Emails Store Key`)  values (?) ";
            $this->db->prepare($sql)->execute(
                [
                    $this->id
                ]
            );


            $history_data = array(
                'History Abstract' => sprintf(_('Store %s (%s) created'), $this->data['Store Name'], $this->data['Store Code']),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $history_key = $this->add_subject_history(
                $history_data,
                true,
                'No',
                'Changes',
                $this->get_object_name(),
                $this->id
            );


            include_once 'class.Account.php';
            $account = new Account();
            $account->add_account_history($history_key);
        } else {
            // print_r($stmt->errorInfo());


            $this->msg = _("Error can not create store");
        }
    }

    function create_shipping_zone_schema($data)
    {
        include_once 'class.Shipping_Zone_Schema.php';

        if (!array_key_exists('Shipping Zone Schema Label', $data) or $data['Shipping Zone Schema Label'] == '') {
            $this->error = true;
            $this->msg   = 'error, no label';

            return false;
        }


        $data['Shipping Zone Schema Store Key']     = $this->id;
        $data['Shipping Zone Schema Creation Date'] = gmdate('Y-m-d H:i:s');


        $shipping_zone_schema = new Shipping_Zone_Schema('find create', $data);

        // print_r($shipping_zone_schema);
        if ($shipping_zone_schema->id) {
            $this->new_object_msg = $shipping_zone_schema->msg;

            if ($shipping_zone_schema->new) {
                $this->new_object = true;

                $shipping_zone_schema->create_shipping_zone(array(
                                                                'Shipping Zone Type'        => 'Failover',
                                                                'Shipping Zone Code'        => 'Other',
                                                                'Shipping Zone Name'        => _('Rest of the world'),
                                                                'Shipping Zone Territories' => '{}',
                                                                'Shipping Zone Price'       => json_encode(array(
                                                                                                               'type' => 'TBC',

                                                                                                           )),

                                                            ));
            } else {
                $this->error = true;
                if ($shipping_zone_schema->found) {
                    $this->error_code     = 'duplicated_field';
                    $this->error_metadata = json_encode(array($shipping_zone_schema->duplicated_field));

                    if ($shipping_zone_schema->duplicated_field == 'Shipping Zone Schema Label') {
                        $this->msg = _('Duplicated label');
                    }
                } else {
                    $this->msg = $shipping_zone_schema->msg;
                }
            }

            return $shipping_zone_schema;
        } else {
            $this->error = true;
            $this->msg   = $shipping_zone_schema->msg;

            return false;
        }
    }

    function get($key = '', $args = '')
    {
        $account = get_object('Account', 1);

        if (!$this->id) {
            return '';
        }


        switch ($key) {
            case 'Send Email Address':
                return $this->data['Store Email'];
            case $this->table_name.' Collect Address':

                $type = 'Collect';

                $address_fields = array(

                    'Address Recipient'            => $this->get($type.' Address Recipient'),
                    'Address Organization'         => $this->get($type.' Address Organization'),
                    'Address Line 1'               => $this->get($type.' Address Line 1'),
                    'Address Line 2'               => $this->get($type.' Address Line 2'),
                    'Address Sorting Code'         => $this->get($type.' Address Sorting Code'),
                    'Address Postal Code'          => $this->get($type.' Address Postal Code'),
                    'Address Dependent Locality'   => $this->get($type.' Address Dependent Locality'),
                    'Address Locality'             => $this->get($type.' Address Locality'),
                    'Address Administrative Area'  => $this->get($type.' Address Administrative Area'),
                    'Address Country 2 Alpha Code' => $this->get(
                        $type.' Address Country 2 Alpha Code'
                    ),


                );

                return json_encode($address_fields);

            case 'Label Signature':
                return nl2br($this->data['Store Label Signature']);

            case 'Collect Address':


                return $this->get($this->table_name.' '.$key.' Formatted');


            case('Google Map URL'):


                return '<iframe src="'.$this->data['Store Google Map URL'].'" width="1000" height="300"  allowfullscreen></iframe>';


            case 'State':
            case 'Status':
                switch ($this->data['Store Status']) {
                    case 'Normal':
                        return _('Open');

                    case 'Closed':
                        return _('Closed');
                    case 'InProcess':
                        return _('In construction');
                    case 'ClosingDown':
                        return _('Recently closed');
                    default:
                        return $this->data['Store Status'];
                }

            case('Currency Code'):
                include_once 'utils/natural_language.php';

                return currency_label(
                    $this->data['Store Currency Code'],
                    $this->db
                );


            case('Currency Symbol'):
                include_once 'utils/natural_language.php';

                return currency_symbol($this->data['Store Currency Code']);

            case('Valid From'):
                return strftime("%a %e %b %Y", strtotime($this->data['Store Valid From'].' +0:00'));
            case('Valid To'):
                return strftime("%a %e %b %Y", strtotime($this->data['Store Valid To'].' +0:00'));

            case("Sticky Note"):
                return nl2br($this->data['Store Sticky Note']);

            case('Contacts'):
            case('Active Contacts'):
            case('New Contacts'):
            case('Lost Contacts'):
            case('Losing Contacts'):
            case('Contacts With Orders'):
            case('Active Contacts With Orders'):
            case('New Contacts With Orders'):
            case('Lost Contacts With Orders'):
            case('Losing Contacts With Orders'):
            case('Active Web For Sale'):
            case('Active Web Out of Stock'):
            case('Active Web Offline'):
            case 'Orders In Basket Number':
                return number($this->data['Store '.$key]);


            case 'Percentage Contacts With Orders':
            case 'Percentage Active Contacts':
                return percentage($this->data['Store '.preg_replace('/^Percentage /', '', $key)], $this->data['Store Contacts']);

            case 'Percentage New Contacts With Orders':
                return ($this->data['Store New Contacts'] == 0 ? '' : '('.percentage($this->data['Store '.preg_replace('/^Percentage /', '', $key)], $this->data['Store New Contacts'])).')';


            case 'Percentage Active Web Out of Stock':
                return percentage($this->data['Store Active Web Out of Stock'], $this->data['Store Active Products']);
            case 'Percentage Active Web Offline':
                return percentage($this->data['Store Active Web Offline'], $this->data['Store Active Products']);

            case('Potential Customers'):
                return number(
                    $this->data['Store Active Contacts'] - $this->data['Store Active Contacts With Orders']
                );
            case('Total Users'):
                return number($this->data['Store Total Users']);


            case('code'):
                return $this->data['Store Code'];

            case('type'):
                return $this->data['Store Type'];

            case('Total Products'):
                return $this->data['Store For Sale Products'] + $this->data['Store In Process Products'] + $this->data['Store Not For Sale Products'] + $this->data['Store Discontinued Products'] + $this->data['Store Unknown Sales State Products'];

            case('For Sale Products'):
                return number($this->data['Store For Sale Products']);

            case('For Public Sale Products'):
                return number($this->data['Store For Public Sale Products']);

            case('Families'):
                return number($this->data['Store Families']);
            case('Departments'):
                return number($this->data['Store Departments']);

            case('Percentage Active Contacts'):
                return percentage(
                    $this->data['Store Active Contacts'],
                    $this->data['Store Contacts']
                );
            case('Percentage Total With Orders'):
                return percentage($this->data['Store Contacts With Orders'], $this->data['Store Contacts']);
            case 'Delta Today Start Orders In Warehouse Number':

                $start = $this->data['Store Today Start Orders In Warehouse Number'];
                $end   = $this->data['Store Orders In Warehouse Number'] + $this->data['Store Orders Packed Number'] + $this->data['Store Orders Dispatch Approved Number'];

                $diff = $end - $start;

                return ($diff > 0 ? '+' : '').number($diff).delta_icon($end, $start, true);

            case 'Today Orders Dispatched':

                $number = 0;

                $sql = sprintf(
                    "SELECT count(*) AS num FROM `Order Dimension` WHERE `Order Store Key`=%d AND `Order State`='Dispatched' AND `Order Dispatched Date`>%s   AND  `Order Dispatched Date`<%s   ",
                    $this->id,
                    prepare_mysql(date('Y-m-d 00:00:00')),
                    prepare_mysql(date('Y-m-d 23:59:59'))
                );

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $number = $row['num'];
                    }
                }


                return number($number);

            case 'Show in Warehouse Orders':
            case 'Store Show in Warehouse Orders':
                return $this->data[$key];

            case 'Website URL':
                $website = get_object('Website', $this->data['Store Website Key']);

                return $website->get('Website URL');

            case 'Reviews Settings':


                if ($this->data['Store Reviews Settings'] == '') {
                    return false;
                } else {
                    return json_decode($this->data['Store Reviews Settings'], true);
                }


            case 'send invoice attachment in delivery confirmation':
            case 'send dn attachment in delivery confirmation':

                if ($this->settings(preg_replace('/\s/', '_', $key)) == 'Yes') {
                    return _('Yes');
                } else {
                    return _('No');
                }

            case 'data entry picking aid default picker':
            case 'data entry picking aid default packer':


                $staff_key = $this->settings(preg_replace('/\s/', '_', $key));

                if (is_numeric($staff_key) and $staff_key > 0) {
                    $staff = get_object('Staff', $staff_key);

                    return $staff->get('Name');
                } else {
                    return '';
                }


            case 'data entry picking aid default shipper':


                $shipper_key = $this->settings(preg_replace('/\s/', '_', $key));

                if (is_numeric($shipper_key) and $shipper_key > 0) {
                    $shipper = get_object('Shipper', $shipper_key);

                    return $shipper->get('Name');
                } else {
                    return '';
                }

            case 'data entry picking aid default number boxes':

                $default_number_of_boxed = $this->settings(preg_replace('/\s/', '_', $key));

                if ($default_number_of_boxed > 0) {
                    return number($default_number_of_boxed);
                } else {
                    return '';
                }


            case 'Store Notification New Order Recipients':
            case 'Store Notification New Customer Recipients':
            case 'Store Notification Invoice Deleted Recipients':
            case 'Store Notification Delivery Note Undispatched Recipients':
            case 'Store Notification Delivery Note Dispatched Recipients':
            case 'Store BCC Delivery Note Dispatched Recipients':

                $encoded_value = $this->settings(preg_replace('/\s/', '_', $key));

                if ($encoded_value != '') {
                    $mixed_recipients          = json_decode($encoded_value, true);
                    $mixed_recipients['users'] = array();
                    foreach ($mixed_recipients['user_keys'] as $user_key) {
                        $mixed_recipients['users'][] = get_object('User', $user_key);
                    }


                    return $mixed_recipients;
                } else {
                    return array(
                        'external_emails' => array(),
                        'user_keys'       => array(),
                        'users'           => array(),
                    );
                }


            case 'Notification New Order Recipients':
            case 'Notification New Customer Recipients':
            case 'Notification Invoice Deleted Recipients':
            case 'Notification Delivery Note Undispatched Recipients':
            case 'Notification Delivery Note Dispatched Recipients':
            case 'BCC Delivery Note Dispatched Recipients':

                $this->smarty->assign('mixed_recipients', $this->get('Store '.$key));
                $this->smarty->assign('mode', 'formatted_value');
                $this->smarty->assign('field_id', '');

                try {
                    return $this->smarty->fetch('mixed_recipients.edit.tpl');
                } catch (Exception $e) {
                    return '';
                }


            case 'Next Invoice Public ID Method':

                switch ($this->data['Store Next Invoice Public ID Method']) {
                    case 'Order ID':
                        return _('Same as order');

                    case 'Invoice Public ID':
                        return _('Own consecutive number');

                    case 'Account Wide Invoice Public ID':
                        return _('Own consecutive number (shared all stores)');
                }


                break;

            case 'Refund Public ID Method':

                switch ($this->data['Store Refund Public ID Method']) {
                    case 'Same Invoice ID'  :
                        return _('Same as invoice');

                    case 'Next Invoice ID'  :
                        return _('Next consecutive invoice number');

                    case 'Store Own Index':
                        return _('Own consecutive number');

                    case 'Account Wide Own Index':
                        return _('Own consecutive number (shared all stores)');
                }


                break;
            case 'dispatch_time_avg':
            case 'dispatch_time_samples':
            case 'sitting_time_avg':
            case 'sitting_time_samples':

                if ($args != '') {
                    $key .= '_'.strtolower(preg_replace('/\s/', '_', $args));
                }


                return $this->properties($key);
            case 'formatted_dispatch_time_avg':
            case 'formatted_sitting_time_avg':

                $_key = preg_replace('/formatted_/', '', $key);

                $_sample_key = preg_replace('/_avg/', '_samples', $_key);
                if ($args != '') {
                    $_sample_key .= '_'.strtolower(preg_replace('/\s/', '_', $args));
                }

                if ($this->properties($_sample_key) > 0) {
                    $dispatch_time_average = $this->get($_key, $args);

                    return seconds_to_natural_string($dispatch_time_average, false, 1);
                } else {
                    return '-';
                }


            case 'formatted_bis_dispatch_time_avg':
            case 'formatted_bis_sitting_time_avg':
                $_key = preg_replace('/formatted_bis_/', '', $key);

                $_sample_key = preg_replace('/_avg/', '_samples', $_key);
                if ($args != '') {
                    $_sample_key .= '_'.strtolower(preg_replace('/\s/', '_', $args));
                }

                if ($this->properties($_sample_key) > 0) {
                    $dispatch_time_average = $this->get($_key, $args);

                    return seconds_to_string($dispatch_time_average);
                } else {
                    return '-';
                }

            case 'dispatch_time_histogram':

                $key = preg_replace('/percentage_/', '', $key);

                if (is_array($args)) {
                    $key .= '_'.strtolower(preg_replace('/\s/', '_', $args[1]));
                }


                $histogram = $this->properties($key);


                if ($histogram != '') {
                    $histogram = json_decode($histogram, true);


                    if (isset($histogram[$args[0]])) {
                        return $histogram[$args[0]];
                    }
                }

                return 0;
            case 'Default Product Pricing Policy Key':
                if (!$this->data['Store Default Product Pricing Policy Key']) {
                    return _('No policy');
                }

                $label = '';
                $sql   = "select `Product Pricing Policy Label` from `Product Pricing Policy Dimension` where `Product Pricing Policy Key`=? ";
                $stmt  = $this->db->prepare($sql);
                $stmt->execute(
                    [
                        $this->data['Store Default Product Pricing Policy Key']
                    ]
                );
                if ($row = $stmt->fetch()) {
                    $label = $row['Product Pricing Policy Label'];
                }

                return $label;

            case 'percentage_dispatch_time_histogram':
                return percentage(
                    $this->get('dispatch_time_histogram', $args),
                    $this->get('dispatch_time_samples', $args[1])
                );
        }


        if (preg_match('/^(DC Orders).*(Amount) Soft Minify$/', $key)) {
            $field = 'Store '.preg_replace('/ Soft Minify$/', '', $key);

            $suffix          = '';
            $fraction_digits = 'NO_FRACTION_DIGITS';
            $_amount         = $this->data[$field];


            return money($_amount, $account->get('Account Currency'), false, $fraction_digits).$suffix;
        }


        if (preg_match('/^(DC Orders).*(Amount|Profit)$/', $key)) {
            $field = 'Store '.$key;

            return money($this->data[$field], $account->get('Account Currency'));
        }
        if (preg_match('/^(DC Orders).*(Amount|Profit) Minify$/', $key)) {
            $field = 'Store '.preg_replace('/ Minify$/', '', $key);

            $suffix          = '';
            $fraction_digits = 'NO_FRACTION_DIGITS';
            if ($this->data[$field] >= 1000000) {
                $suffix          = 'M';
                $fraction_digits = 'DOUBLE_FRACTION_DIGITS';
                $_amount         = $this->data[$field] / 1000000;
            } elseif ($this->data[$field] >= 10000) {
                $suffix  = 'K';
                $_amount = $this->data[$field] / 1000;
            } elseif ($this->data[$field] > 100) {
                $fraction_digits = 'SINGLE_FRACTION_DIGITS';
                $suffix          = 'K';
                $_amount         = $this->data[$field] / 1000;
            } else {
                $_amount = $this->data[$field];
            }

            return money($_amount, $account->get('Account Currency'), false, $fraction_digits).$suffix;
        }

        if (preg_match('/^(Orders|Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Minify$/', $key)) {
            $field = 'Store '.preg_replace('/ Minify$/', '', $key);

            $suffix          = '';
            $fraction_digits = 'NO_FRACTION_DIGITS';
            if ($this->data[$field] >= 1000000) {
                $suffix          = 'M';
                $fraction_digits = 'DOUBLE_FRACTION_DIGITS';
                $_amount         = $this->data[$field] / 1000000;
            } elseif ($this->data[$field] >= 10000) {
                $suffix  = 'K';
                $_amount = $this->data[$field] / 1000;
            } elseif ($this->data[$field] > 100) {
                $fraction_digits = 'SINGLE_FRACTION_DIGITS';
                $suffix          = 'K';
                $_amount         = $this->data[$field] / 1000;
            } else {
                $_amount = $this->data[$field];
            }

            return money($_amount, $this->get('Store Currency Code'), false, $fraction_digits).$suffix;
        }

        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }


        if (preg_match('/^(Orders|Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Soft Minify$/', $key)) {
            $field = 'Store '.preg_replace('/ Soft Minify$/', '', $key);

            $suffix          = '';
            $fraction_digits = 'NO_FRACTION_DIGITS';
            $_amount         = $this->data[$field];


            return money($_amount, $this->get('Store Currency Code'), false, $fraction_digits).$suffix;
        }
        if (preg_match('/^(Last|Yesterday|Total|1|10|6|3|2|4|5|Year To|Quarter To|Month To|Today|Week To).*(Quantity Invoiced|Invoices) Minify$/', $key)) {
            $field = 'Store '.preg_replace('/ Minify$/', '', $key);

            $suffix          = '';
            $fraction_digits = 0;
            if ($this->data[$field] >= 10000) {
                $suffix  = 'K';
                $_number = $this->data[$field] / 1000;
            } elseif ($this->data[$field] > 100) {
                $fraction_digits = 1;
                $suffix          = 'K';
                $_number         = $this->data[$field] / 1000;
            } else {
                $_number = $this->data[$field];
            }

            return number($_number, $fraction_digits).$suffix;
        }
        if (preg_match('/^(Last|Yesterday|Total|1|10|6|3|2|4|5|Year To|Quarter To|Month To|Today|Week To).*(Quantity Invoiced|Invoices) Soft Minify$/', $key)) {
            $field   = 'Store '.preg_replace('/ Soft Minify$/', '', $key);
            $_number = $this->data[$field];

            return number($_number, 0);
        }

        if (preg_match('/^(Orders|Total|1).*(Amount|Profit)$/', $key)) {
            $amount = 'Store '.$key;

            return money($this->data[$amount], $this->get('Store Currency Code'));
        }
        if (preg_match(
                '/^(Total|1).*(Quantity (Ordered|Invoiced|Delivered|)|Customers|Customers Contacts)$/',
                $key
            ) or preg_match('/^(Active Customers|Orders .* Number)$/', $key)) {
            $amount = 'Store '.$key;


            return number($this->data[$amount]);
        }
        if (preg_match(
            '/^Delivery Notes For (Orders|Replacements|Shortages|Samples|Donations)$/',
            $key
        )) {
            $amount = 'Store '.$key;

            return number($this->data[$amount]);
        }


        if (preg_match('/(Orders|Delivery Notes|Invoices) Acc$/', $key)) {
            $amount = 'Store '.$key;

            return number($this->data[$amount]);
        } elseif (preg_match(
            '/(Orders|Delivery Notes|Invoices|Refunds|Orders In Process|(Active|New|Suspended|Discontinuing|Discontinued) Products)$/',
            $key
        )) {
            $amount = 'Store '.$key;

            return number($this->data[$amount]);
        }

        if (array_key_exists('Store '.$key, $this->data)) {
            return $this->data['Store '.$key];
        }

        return '';
    }

    function settings($key)
    {
        return ($this->settings[$key] ?? '');
    }

    function properties($key)
    {
        return ($this->properties[$key] ?? '');
    }

    function create_timeseries($data, $fork_key = 0)
    {
        $data['Timeseries Parent']     = 'Store';
        $data['Timeseries Parent Key'] = $this->id;
        $data['editor']                = $this->editor;

        $timeseries = new Timeseries('find', $data, 'create');
        if ($timeseries->id) {
            require_once 'utils/date_functions.php';

            if ($this->data['Store Valid From'] != '') {
                $from = date('Y-m-d', strtotime($this->get('Valid From').' +0:00'));
            } else {
                $from = '';
            }

            if ($this->get('Store Status') == 'Closed' or $this->get('Store Status') == 'ClosingDown') {
                $to = $this->get('Valid To');
            } else {
                $to = date('Y-m-d');
            }


            $sql        = sprintf(
                'DELETE FROM `Timeseries Record Dimension` WHERE `Timeseries Record Timeseries Key`=%d AND `Timeseries Record Date`<%s ',
                $timeseries->id,
                prepare_mysql($from)
            );
            $update_sql = $this->db->prepare($sql);
            $update_sql->execute();
            if ($update_sql->rowCount()) {
                $timeseries->update(
                    array('Timeseries Updated' => gmdate('Y-m-d H:i:s')),
                    'no_history'
                );
            }

            $sql        = sprintf(
                'DELETE FROM `Timeseries Record Dimension` WHERE `Timeseries Record Timeseries Key`=%d AND `Timeseries Record Date`>%s ',
                $timeseries->id,
                prepare_mysql($to)
            );
            $update_sql = $this->db->prepare($sql);
            $update_sql->execute();
            if ($update_sql->rowCount()) {
                $timeseries->update(
                    array('Timeseries Updated' => gmdate('Y-m-d H:i:s')),
                    'no_history'
                );
            }

            if ($from and $to) {
                $this->update_timeseries_record($timeseries, $from, $to, $fork_key);
            }

            if ($timeseries->get('Timeseries Number Records') == 0) {
                $timeseries->update(
                    array('Timeseries Updated' => gmdate('Y-m-d H:i:s')),
                    'no_history'
                );
            }
        }
    }

    function update_timeseries_record($timeseries, $from, $to, $fork_key = false)
    {
        if ($timeseries->get('Type') == 'StoreSales') {
            $dates = date_frequency_range(
                $this->db,
                $timeseries->get('Timeseries Frequency'),
                $from,
                $to
            );


            if ($fork_key) {
                $sql = sprintf(
                    "UPDATE `Fork Dimension` SET `Fork State`='In Process' ,`Fork Operations Total Operations`=%d,`Fork Start Date`=NOW(),`Fork Result`=%d  WHERE `Fork Key`=%d ",
                    count($dates),
                    $timeseries->id,
                    $fork_key
                );

                $this->db->exec($sql);
            }
            $index = 0;
            foreach ($dates as $date_frequency_period) {
                $index++;
                $sales_data     = $this->get_sales_data($date_frequency_period['from'], $date_frequency_period['to']);
                $customers_data = $this->get_customers_data($date_frequency_period['from'], $date_frequency_period['to']);

                $_date = gmdate('Y-m-d', strtotime($date_frequency_period['from'].' +0:00'));


                if ($customers_data['new_customers'] > 0 or $sales_data['invoices'] > 0 or $sales_data['refunds'] > 0 or $sales_data['customers'] > 0 or $sales_data['amount'] != 0 or $sales_data['dc_amount'] != 0 or $sales_data['profit'] != 0 or $sales_data['dc_profit'] != 0) {
                    list($timeseries_record_key, $date) = $timeseries->create_record(array('Timeseries Record Date' => $_date));


                    $sql = "UPDATE `Timeseries Record Dimension` SET
                    `Timeseries Record Integer A`=? ,`Timeseries Record Integer B`=? ,`Timeseries Record Integer C`=? ,`Timeseries Record Integer D`=? ,`Timeseries Record Float A`=? ,  `Timeseries Record Float B`=? ,`Timeseries Record Float C`=? ,`Timeseries Record Float D`=? ,`Timeseries Record Type`=?
                    WHERE `Timeseries Record Key`=?
                    ";


                    $stmt = $this->db->prepare($sql);
                    $stmt->execute(array(
                                       $sales_data['invoices'],
                                       $sales_data['refunds'],
                                       $sales_data['customers'],
                                       $customers_data['new_customers'],
                                       $sales_data['amount'],
                                       $sales_data['dc_amount'],
                                       $sales_data['profit'],
                                       $sales_data['dc_profit'],
                                       'Data',
                                       $timeseries_record_key

                                   ));


                    if ($stmt->rowCount() or $date == gmdate('Y-m-d')) {
                        $timeseries->fast_update([
                                                     'Timeseries Updated' => gmdate('Y-m-d H:i:s')
                                                 ]);
                    }
                } else {
                    $sql = sprintf(
                        'DELETE FROM `Timeseries Record Dimension` WHERE `Timeseries Record Timeseries Key`=%d AND `Timeseries Record Date`=%s ',
                        $timeseries->id,
                        prepare_mysql($_date)
                    );

                    $update_sql = $this->db->prepare($sql);
                    $update_sql->execute();
                    if ($update_sql->rowCount()) {
                        $timeseries->update(
                            array(
                                'Timeseries Updated' => gmdate(
                                    'Y-m-d H:i:s'
                                )
                            ),
                            'no_history'
                        );
                    }
                }
                if ($fork_key) {
                    $skip_every = 1;
                    if ($index % $skip_every == 0) {
                        $sql = sprintf(
                            "UPDATE `Fork Dimension` SET `Fork Operations Done`=%d  WHERE `Fork Key`=%d ",
                            $index,
                            $fork_key
                        );
                        $this->db->exec($sql);
                    }
                }

                $date = gmdate('Y-m-d H:i:s');
                $sql  = 'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (?,?,?,?) ON DUPLICATE KEY UPDATE `Stack Last Update Date`=? ,`Stack Counter`=`Stack Counter`+1 ';
                $this->db->prepare($sql)->execute([
                                                      $date,
                                                      $date,
                                                      'timeseries_stats',
                                                      $timeseries->id,
                                                      $date,

                                                  ]);
            }


            if ($fork_key) {
                $sql = sprintf(
                    "UPDATE `Fork Dimension` SET `Fork State`='Finished' ,`Fork Finished Date`=NOW(),`Fork Operations Done`=%d,`Fork Result`=%d WHERE `Fork Key`=%d ",
                    $index,
                    $timeseries->id,
                    $fork_key
                );

                $this->db->exec($sql);
            }
        }
    }

    function get_sales_data($from_date, $to_date): array
    {
        $sales_data = array(
            'discount_amount'    => 0,
            'amount'             => 0,
            'invoices'           => 0,
            'refunds'            => 0,
            'replacements'       => 0,
            'deliveries'         => 0,
            'profit'             => 0,
            'dc_amount'          => 0,
            'dc_discount_amount' => 0,
            'dc_profit'          => 0,
            'customers'          => 0,
            'repeat_customers'   => 0,

        );


        $sql = sprintf(
            "SELECT count(DISTINCT `Invoice Customer Key`)  AS customers,sum(if(`Invoice Type`='Invoice',1,0))  AS invoices, sum(if(`Invoice Type`='Refund',1,0))  AS refunds,sum(`Invoice Items Discount Amount`) AS discounts,sum(`Invoice Total Net Amount`) net  ,sum(`Invoice Total Profit`) AS profit ,sum(`Invoice Items Discount Amount`*`Invoice Currency Exchange`) AS dc_discounts,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) dc_net  ,sum(`Invoice Total Profit`*`Invoice Currency Exchange`) AS dc_profit 
            FROM `Invoice Dimension` WHERE `Invoice Store Key`=%d %s %s",
            $this->id,
            ($from_date ? sprintf('and `Invoice Date`>%s', prepare_mysql($from_date)) : ''),
            ($to_date ? sprintf('and `Invoice Date`<%s', prepare_mysql($to_date)) : '')

        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $sales_data['discount_amount']    = $row['discounts'];
                $sales_data['amount']             = $row['net'];
                $sales_data['profit']             = $row['profit'];
                $sales_data['invoices']           = $row['invoices'];
                $sales_data['refunds']            = $row['refunds'];
                $sales_data['dc_discount_amount'] = $row['dc_discounts'];
                $sales_data['dc_amount']          = $row['dc_net'];
                $sales_data['dc_profit']          = $row['dc_profit'];
                $sales_data['customers']          = $row['customers'];
            }
        }


        $sql = sprintf(
            "SELECT count(*)  AS replacements FROM `Delivery Note Dimension` WHERE `Delivery Note Type` IN ('Replacement & Shortages','Replacement','Shortages') AND `Delivery Note Store Key`=%d %s %s",
            $this->id,
            ($from_date ? sprintf(
                'and `Delivery Note Date`>%s',
                prepare_mysql($from_date)
            ) : ''),
            ($to_date ? sprintf(
                'and `Delivery Note Date`<%s',
                prepare_mysql($to_date)
            ) : '')

        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $sales_data['replacements'] = $row['replacements'];
            }
        }

        $sql = sprintf(
            "SELECT count(*)  AS delivery_notes FROM `Delivery Note Dimension` WHERE `Delivery Note Type` IN ('Order') AND `Delivery Note Store Key`=%d %s %s",
            $this->id,
            ($from_date ? sprintf(
                'and `Delivery Note Date`>%s',
                prepare_mysql($from_date)
            ) : ''),
            ($to_date ? sprintf(
                'and `Delivery Note Date`<%s',
                prepare_mysql($to_date)
            ) : '')

        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $sales_data['deliveries'] = $row['delivery_notes'];
            }
        }


        $sql = sprintf(
            " SELECT COUNT(*) AS repeat_customers FROM( SELECT count(*) AS invoices ,`Invoice Customer Key` FROM `Invoice Dimension` WHERE `Invoice Store Key`=%d  %s %s GROUP BY `Invoice Customer Key` HAVING invoices>1) AS tmp",
            $this->id,
            ($from_date ? sprintf('and `Invoice Date`>%s', prepare_mysql($from_date)) : ''),
            ($to_date ? sprintf('and `Invoice Date`<%s', prepare_mysql($to_date)) : '')
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $sales_data['repeat_customers'] = $row['repeat_customers'];
            }
        }


        return $sales_data;
    }

    function get_customers_data($from_date, $to_date): array
    {
        $customers_data = array(
            'new_customers' => 0,
        );


        $parameters[] = $this->id;
        $sql          = "SELECT count(*)  AS new_customers from `Customer Dimension` where `Customer Store Key`=? ";
        if ($from_date) {
            $sql          .= " and `Customer First Contacted Date`>? ";
            $parameters[] = $from_date;
        }
        if ($to_date) {
            $sql          .= " and `Customer First Contacted Date`<? ";
            $parameters[] = $to_date;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute(

            $parameters

        );
        if ($row = $stmt->fetch()) {
            $customers_data['new_customers'] = $row['new_customers'];
        }


        return $customers_data;
    }

    function create_campaign($data)
    {
        include_once 'class.DealCampaign.php';

        if (!array_key_exists('Deal Campaign Name', $data) or $data['Deal Campaign Name'] == '') {
            $this->error = true;
            $this->msg   = 'error, no campaign name';

            return false;
        }

        if (!array_key_exists('Deal Campaign Valid From', $data)) {
            $this->error = true;
            $this->msg   = 'error, no campaign start date';

            return false;
        }

        if ($data['Deal Campaign Valid From'] == '') {
            $data['Deal Campaign Valid From'] = gmdate('Y-m-d');
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['Deal Campaign Valid From'])) {
            $data['Deal Campaign Valid From'] .= ' 00:00:00';
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['Deal Campaign Valid To'])) {
            $data['Deal Campaign Valid To'] .= ' 23:59:59';
        }

        $data['Deal Campaign Store Key'] = $this->id;


        $campaign = new DealCampaign('find create', $data);


        if ($campaign->id) {
            $this->new_object_msg = $campaign->msg;

            if ($campaign->new) {
                $this->new_object = true;
                $this->update_campaigns_data();
            } else {
                $this->error = true;
                if ($campaign->found) {
                    $this->error_code     = 'duplicated_field';
                    $this->error_metadata = json_encode(array($campaign->duplicated_field));

                    if ($campaign->duplicated_field == 'Deal Campaign Name') {
                        $this->msg = _('Duplicated campaign name');
                    }
                } else {
                    $this->msg = $campaign->msg;
                }
            }

            return $campaign;
        } else {
            $this->error = true;
            $this->msg   = $campaign->msg;

            return false;
        }
    }

    function update_campaigns_data()
    {
        $campaigns = 0;

        $sql = sprintf(
            "SELECT count(*) AS num FROM `Deal Campaign Dimension` WHERE `Deal Campaign Store Key`=%d AND `Deal Campaign Status`='Active' ",
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $campaigns = $row['num'];
            }
        }


        $this->update(array('Store Active Deal Campaigns' => $campaigns), 'no_history');
    }

    function get_bcc_recipients($type): array
    {
        $recipients      = array();
        $recipients_data = $this->get('Store BCC '.$type.' Recipients');

        foreach ($recipients_data['external_emails'] as $external_emails) {
            $recipients[$external_emails] = $external_emails;
        }


        foreach ($recipients_data['users'] as $user) {
            if ($user->get('User Active') == 'Yes' and $user->get('User Password Recovery Email') != '') {
                $recipients[$user->get('User Password Recovery Email')] = $user->get('User Password Recovery Email');
            }
        }

        return array_values($recipients);
    }

    function get_notification_recipients($type): array
    {
        $recipients      = array();
        $recipients_data = $this->get('Store Notification '.$type.' Recipients');

        foreach ($recipients_data['external_emails'] as $external_emails) {
            $recipients[$external_emails] = $external_emails;
        }


        foreach ($recipients_data['users'] as $user) {
            if ($user->get('User Active') == 'Yes' and $user->get('User Password Recovery Email') != '') {
                $recipients[$user->get('User Password Recovery Email')] = $user->get('User Password Recovery Email');
            }
        }

        return array_values($recipients);
    }

    function get_notification_recipients_objects($type): array
    {
        include_once 'utils/email_recipient.class.php';

        $recipients      = array();
        $recipients_data = $this->get('Store Notification '.$type.' Recipients');


        if (is_array($recipients_data)) {
            foreach ($recipients_data['external_emails'] as $external_email) {
                $recipients[$external_email] = new email_recipient(

                    0, array(
                         'object_name'      => 'external_email',
                         'Main Plain Email' => $external_email
                     )

                );
            }


            foreach ($recipients_data['users'] as $user) {
                if ($user->get('User Active') == 'Yes' and $user->get('User Password Recovery Email') != '') {
                    $recipients[$user->get('User Password Recovery Email')] = new email_recipient(

                        $user->id, array(
                                     'object_name'      => 'User',
                                     'Main Plain Email' => $user->get('User Password Recovery Email'),
                                     'Name'             => $user->get('Alias'),
                                 )

                    );
                }
            }

            return array_values($recipients);
        } else {
            return [];
        }
    }


    function create_customers_list($data)
    {
        $this->new_list = false;

        include_once 'class.List.php';

        if (empty($data['List Name'])) {
            $this->error = true;
            $this->msg   = 'error, no list name';

            return false;
        }


        $sql = "SELECT count(*) as num  FROM `List Dimension` WHERE `List Parent Key`=?  AND `List Name`=?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
                           $this->id,
                           $data['List Name']
                       ));
        if ($row = $stmt->fetch()) {
            if ($row['num'] > 0) {
                $this->error = true;
                $this->msg   = 'error, duplicated list name';

                return false;
            }
        }


        $list_data['List Creation Date'] = gmdate('Y-m-d H:i:s');


        $list_data['List Scope']    = 'Customer';
        $list_data['List Use Type'] = 'UserCreated';

        $list_data['List Parent Key'] = $this->id;
        $list_data['editor']          = $this->editor;
        $list_data['List Name']       = $data['List Name'];
        $list_data['List Type']       = $data['List Type'];

        unset($data['List Name']);
        unset($data['List Type']);


        $list_data['List Metadata'] = json_encode($data);

        $list = new SubjectList('new', $list_data);


        if ($list->id) {
            $this->new_object_msg = $list->msg;

            if ($list->new) {
                $this->new_list = true;
            } else {
                $this->error = true;
                if ($list->found) {
                    $this->error_code     = 'duplicated_field';
                    $this->error_metadata = json_encode(array($list->duplicated_field));

                    if ($list->duplicated_field == 'List Name') {
                        $this->msg = _('Duplicated list name');
                    }
                } else {
                    $this->msg = $list->msg;
                }
            }

            return $list;
        } else {
            $this->error = true;
            $this->msg   = $list->msg;

            return false;
        }
    }

    function delete()
    {
        $this->deleted = false;
        $this->update_customers_data();

        if ($this->data['Store Contacts'] == 0) {
            $sql = sprintf("SELECT `Category Key` FROM `Category Dimension` WHERE `Category Store Key`=%d", $this->id);

            include_once 'class.Category.php';
            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $category = new Category($row['Category Key']);
                    $category->delete();
                }
            }


            $sql = sprintf("DELETE FROM `Store Dimension` WHERE `Store Key`=%d", $this->id);
            $this->db->exec($sql);
            $this->deleted = true;
            $sql           = sprintf("DELETE FROM `User Right Scope Bridge` WHERE `Scope`='Store' AND `Scope Key`=%d ", $this->id);
            $this->db->exec($sql);

            $sql = sprintf("DELETE FROM `Store Data` WHERE `Store Key`=%d ", $this->id);
            $this->db->exec($sql);
            $sql = sprintf("DELETE FROM `Store DC Data` WHERE `Store Key`=%d ", $this->id);
            $this->db->exec($sql);


            $sql = sprintf("SELECT `Timeseries Key` FROM `Timeseries Dimension` WHERE `Timeseries Parent`='Store' AND `Timeseries Parent Key`=%d ", $this->id);

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    $sql = sprintf("DELETE  FROM `Timeseries Record Dimension` WHERE `Timeseries Record Timeseries Key`=%d", $row['Timeseries Key']);
                    $this->db->exec($sql);
                }
            }
            $sql = sprintf("DELETE FROM `Timeseries Dimension` WHERE `Timeseries Parent`='Store' AND `Timeseries Parent Key`=%d ", $this->id);
            $this->db->exec($sql);


            $history_key = $this->add_history(
                array(
                    'Action'           => 'deleted',
                    'History Abstract' => sprintf(_('Store %d deleted'), $this->data['Store Name']),
                    'History Details'  => ''
                ),
                true
            );

            include_once 'class.Account.php';

            $hq = new Account();
            $hq->add_account_history($history_key);


            $this->deleted = true;
        }
    }

    function update_customers_data_bis()
    {

        $this->data['Store Contacts']                     = 0;
        $this->data['Store New Contacts']                 = 0;
        $this->data['Store Contacts With Orders']         = 0;
        $this->data['Store Active Contacts']              = 0;
        $this->data['Store Losing Contacts']              = 0;
        $this->data['Store Lost Contacts']                = 0;
        $this->data['Store New Contacts With Orders']     = 0;
        $this->data['Store Active Contacts With Orders']  = 0;
        $this->data['Store Losing Contacts With Orders']  = 0;
        $this->data['Store Lost Contacts With Orders']    = 0;
        $this->data['Store Contacts Who Visit Website']   = 0;
        $this->data['Store Lost Contacts With No Orders'] = 0;


        $sql = sprintf(
            "SELECT count(*) AS num FROM  `Customer Dimension`    WHERE   `Customer Number Web Logins`>0  AND `Customer Store Key`=%d  ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->data['Store Contacts Who Visit Website'] = $row['num'];
            } else {
                $this->data['Store Contacts Who Visit Website'] = 0;
            }
        }


        $sql = sprintf(
            "SELECT count(*) AS num FROM  `Customer Dimension`    WHERE   `Customer First Contacted Date`>%s  AND `Customer Store Key`=%d  ",
            prepare_mysql(gmdate('Y-m-d H:i:s', strtotime('now -1 week'))),
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->data['Store New Contacts'] = $row['num'];
            }
        }


        $sql = sprintf(
            "SELECT count(*) AS num FROM  `Customer Dimension`    WHERE   `Customer First Contacted Date`>%s  AND `Customer Store Key`=%d  AND `Customer With Orders`='Yes' ",
            prepare_mysql(gmdate('Y-m-d H:i:s', strtotime('now -1 week'))),
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->data['Store New Contacts With Orders'] = $row['num'];
            }
        }


        $sql = sprintf(
            "SELECT count(*) AS num ,  sum(IF(`Customer Type by Activity`='Active'   ,1,0)) AS active, sum(IF(`Customer Type by Activity`='Losing',1,0)) AS losing, sum(IF(`Customer Type by Activity`='Lost',1,0)) AS lost  FROM   `Customer Dimension` WHERE `Customer Store Key`=%d ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->data['Store Contacts']        = $row['num'];
                $this->data['Store Active Contacts'] = $row['active'];
                $this->data['Store Losing Contacts'] = $row['losing'];
                $this->data['Store Lost Contacts']   = $row['lost'];
            }
        }

        $sql = sprintf(
            "SELECT count(*) AS num ,sum(IF(`Customer New`='Yes',1,0)) AS new,sum(IF(`Customer Type by Activity`='Active'   ,1,0)) AS active, sum(IF(`Customer Type by Activity`='Losing',1,0)) AS losing, sum(IF(`Customer Type by Activity`='Lost',1,0)) AS lost  FROM   `Customer Dimension` WHERE `Customer Store Key`=%d AND `Customer With Orders`='Yes'",
            $this->id
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->data['Store Contacts With Orders']        = $row['num'];
                $this->data['Store Active Contacts With Orders'] = $row['active'];
                $this->data['Store Losing Contacts With Orders'] = $row['losing'];
                $this->data['Store Lost Contacts With Orders']   = $row['lost'];
            }
        }


        $sql = sprintf(
            "SELECT count(*) AS num   FROM   `Customer Dimension` WHERE `Customer Store Key`=%d AND `Customer With Orders`='No'  and `Customer Type by Activity`='NeverOrder' ",
            $this->id
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->data['Store Lost Contacts With No Orders'] = $row['num'];
            }
        }


        $sql = sprintf(
            "UPDATE `Store Dimension` SET
                     `Store Contacts`=%d,
                     `Store New Contacts`=%d,
                     `Store Active Contacts`=%d ,
                     `Store Losing Contacts`=%d ,
                     `Store Lost Contacts`=%d ,

                     `Store Contacts With Orders`=%d,
                     `Store New Contacts With Orders`=%d,
                     `Store Active Contacts With Orders`=%d,
                     `Store Losing Contacts With Orders`=%d,
                     `Store Lost Contacts With Orders`=%d,
                     `Store Contacts Who Visit Website`=%d,
                       `Store Lost Contacts With No Orders`=%d
                     WHERE `Store Key`=%d  ",
            $this->data['Store Contacts'],
            $this->data['Store New Contacts'],
            $this->data['Store Active Contacts'],
            $this->data['Store Losing Contacts'],
            $this->data['Store Lost Contacts'],

            $this->data['Store Contacts With Orders'],
            $this->data['Store New Contacts With Orders'],
            $this->data['Store Active Contacts With Orders'],
            $this->data['Store Losing Contacts With Orders'],
            $this->data['Store Lost Contacts With Orders'],
            $this->data['Store Contacts Who Visit Website'],
            $this->data['Store Lost Contacts With No Orders'],
            $this->id
        );

        $this->db->exec($sql);


        $this->update_customers_email_marketing_data();


        $account = get_object('Account', 1);
        $account->update_customers_data();
    }

    function update_customers_data()
    {
        return;
        $this->data['Store Contacts']                     = 0;
        $this->data['Store New Contacts']                 = 0;
        $this->data['Store Contacts With Orders']         = 0;
        $this->data['Store Active Contacts']              = 0;
        $this->data['Store Losing Contacts']              = 0;
        $this->data['Store Lost Contacts']                = 0;
        $this->data['Store New Contacts With Orders']     = 0;
        $this->data['Store Active Contacts With Orders']  = 0;
        $this->data['Store Losing Contacts With Orders']  = 0;
        $this->data['Store Lost Contacts With Orders']    = 0;
        $this->data['Store Contacts Who Visit Website']   = 0;
        $this->data['Store Lost Contacts With No Orders'] = 0;


        $sql = sprintf(
            "SELECT count(*) AS num FROM  `Customer Dimension`    WHERE   `Customer Number Web Logins`>0  AND `Customer Store Key`=%d  ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->data['Store Contacts Who Visit Website'] = $row['num'];
            } else {
                $this->data['Store Contacts Who Visit Website'] = 0;
            }
        }


        $sql = sprintf(
            "SELECT count(*) AS num FROM  `Customer Dimension`    WHERE   `Customer First Contacted Date`>%s  AND `Customer Store Key`=%d  ",
            prepare_mysql(gmdate('Y-m-d H:i:s', strtotime('now -1 week'))),
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->data['Store New Contacts'] = $row['num'];
            }
        }


        $sql = sprintf(
            "SELECT count(*) AS num FROM  `Customer Dimension`    WHERE   `Customer First Contacted Date`>%s  AND `Customer Store Key`=%d  AND `Customer With Orders`='Yes' ",
            prepare_mysql(gmdate('Y-m-d H:i:s', strtotime('now -1 week'))),
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->data['Store New Contacts With Orders'] = $row['num'];
            }
        }


        $sql = sprintf(
            "SELECT count(*) AS num ,  sum(IF(`Customer Type by Activity`='Active'   ,1,0)) AS active, sum(IF(`Customer Type by Activity`='Losing',1,0)) AS losing, sum(IF(`Customer Type by Activity`='Lost',1,0)) AS lost  FROM   `Customer Dimension` WHERE `Customer Store Key`=%d ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->data['Store Contacts']        = $row['num'];
                $this->data['Store Active Contacts'] = $row['active'];
                $this->data['Store Losing Contacts'] = $row['losing'];
                $this->data['Store Lost Contacts']   = $row['lost'];
            }
        }

        $sql = sprintf(
            "SELECT count(*) AS num ,sum(IF(`Customer New`='Yes',1,0)) AS new,sum(IF(`Customer Type by Activity`='Active'   ,1,0)) AS active, sum(IF(`Customer Type by Activity`='Losing',1,0)) AS losing, sum(IF(`Customer Type by Activity`='Lost',1,0)) AS lost  FROM   `Customer Dimension` WHERE `Customer Store Key`=%d AND `Customer With Orders`='Yes'",
            $this->id
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->data['Store Contacts With Orders']        = $row['num'];
                $this->data['Store Active Contacts With Orders'] = $row['active'];
                $this->data['Store Losing Contacts With Orders'] = $row['losing'];
                $this->data['Store Lost Contacts With Orders']   = $row['lost'];
            }
        }


        $sql = sprintf(
            "SELECT count(*) AS num   FROM   `Customer Dimension` WHERE `Customer Store Key`=%d AND `Customer With Orders`='No'  and `Customer Type by Activity`='NeverOrder' ",
            $this->id
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->data['Store Lost Contacts With No Orders'] = $row['num'];
            }
        }


        $sql = sprintf(
            "UPDATE `Store Dimension` SET
                     `Store Contacts`=%d,
                     `Store New Contacts`=%d,
                     `Store Active Contacts`=%d ,
                     `Store Losing Contacts`=%d ,
                     `Store Lost Contacts`=%d ,

                     `Store Contacts With Orders`=%d,
                     `Store New Contacts With Orders`=%d,
                     `Store Active Contacts With Orders`=%d,
                     `Store Losing Contacts With Orders`=%d,
                     `Store Lost Contacts With Orders`=%d,
                     `Store Contacts Who Visit Website`=%d,
                       `Store Lost Contacts With No Orders`=%d
                     WHERE `Store Key`=%d  ",
            $this->data['Store Contacts'],
            $this->data['Store New Contacts'],
            $this->data['Store Active Contacts'],
            $this->data['Store Losing Contacts'],
            $this->data['Store Lost Contacts'],

            $this->data['Store Contacts With Orders'],
            $this->data['Store New Contacts With Orders'],
            $this->data['Store Active Contacts With Orders'],
            $this->data['Store Losing Contacts With Orders'],
            $this->data['Store Lost Contacts With Orders'],
            $this->data['Store Contacts Who Visit Website'],
            $this->data['Store Lost Contacts With No Orders'],
            $this->id
        );

        $this->db->exec($sql);


        $this->update_customers_email_marketing_data();


        $account = get_object('Account', 1);
        $account->update_customers_data();
    }

    function update_customers_email_marketing_data()
    {
        $email_marketing_customers   = 0;
        $newsletters_customers       = 0;
        $basket_engagement_customers = 0;


        $sql = "SELECT count(*) AS num FROM  `Customer Dimension` WHERE `Customer Store Key`=?  and `Customer Main Plain Email`!=''  and `Customer Send Basket Emails`='Yes' ";


        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($this->id));
        while ($row = $stmt->fetch()) {
            $basket_engagement_customers = $row['num'];
        }

        $this->fast_update_json_field('Store Properties', 'basket_engagement_customers', $basket_engagement_customers);


        $sql = "SELECT count(*) AS num FROM  `Customer Dimension` WHERE `Customer Store Key`=?  and `Customer Main Plain Email`!=''  and `Customer Send Email Marketing`='Yes' ";


        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($this->id));
        while ($row = $stmt->fetch()) {
            $email_marketing_customers = $row['num'];
        }

        $this->fast_update_json_field('Store Properties', 'email_marketing_customers', $email_marketing_customers);


        $sql = "SELECT count(*) AS num FROM  `Customer Dimension` WHERE `Customer Store Key`=?  and `Customer Main Plain Email`!=''  and `Customer Send Newsletter`='Yes' ";


        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($this->id));
        while ($row = $stmt->fetch()) {
            $newsletters_customers = $row['num'];
        }
        $this->fast_update_json_field('Store Properties', 'newsletters_customers', $newsletters_customers);
    }

    function update_customers_with_transactions()
    {
        $customers_with_transactions = 0;
        $sql                         = sprintf(
            "select count(distinct `Customer Key`) as num  from `Order Transaction Fact` OTF  where `Store Key`=%d  and `Order Transaction Type`='Order' ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $customers_with_transactions = $row['num'];
            }
        }

        $this->fast_update_json_field('Store Properties', 'customers_with_transactions', $customers_with_transactions);
    }

    function update_new_customers()
    {
        $this->data['Store New Contacts']             = 0;
        $this->data['Store New Contacts With Orders'] = 0;

        $sql = sprintf(
            "SELECT count(*) AS num FROM  `Customer Dimension`    WHERE   `Customer First Contacted Date`>%s  AND `Customer Store Key`=%d  ",
            prepare_mysql(gmdate('Y-m-d H:i:s'), strtotime('now - 1 week')),
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->data['Store New Contacts'] = $row['num'];
            }
        }


        $sql = sprintf(
            "SELECT count(*) AS num FROM  `Customer Dimension`    WHERE   `Customer First Contacted Date`>%s  AND `Customer Store Key`=%d  AND `Customer With Orders`='Yes' ",
            prepare_mysql(gmdate('Y-m-d H:i:s', strtotime('now -1 week'))),
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->data['Store New Contacts With Orders'] = $row['num'];
            }
        }


        $sql = sprintf(
            "UPDATE `Store Dimension` SET `Store New Contacts`=%d ,`Store New Contacts With Orders`=%d  WHERE `Store Key`=%d  ",
            $this->data['Store New Contacts'],
            $this->data['Store New Contacts With Orders'],
            $this->id
        );

        $this->db->exec($sql);


        $account_new_customers             = 0;
        $account_new_customers_with_orders = 0;
        $sql                               = "SELECT   sum(`Store New Contacts`) as new_contacts from  `Store Dimension` ";


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $account_new_customers             = $row['new_contacts'];
                $account_new_customers_with_orders = $row['new_contacts_with_orders'];
            }
        }


        $sql = sprintf(
            "UPDATE `Account Data` SET `Account New Contacts`=%d ,`Account New Contacts With Orders`=%d  WHERE `Account Key`=1  ",
            $account_new_customers,
            $account_new_customers_with_orders
        );

        $this->db->exec($sql);
    }


    function update_product_data()
    {
        $active_products        = 0;
        $suspended_products     = 0;
        $discontinuing_products = 0;
        $discontinued_products  = 0;

        $elements_active_web_status_numbers = array(
            'For Sale'     => 0,
            'Out of Stock' => 0,
            'Offline'      => 0

        );


        $sql = sprintf(
            'SELECT count(*) AS num, `Product Status` FROM `Product Dimension` WHERE `Product Store Key`=%d GROUP BY `Product Status`',

            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Product Status'] == 'Active') {
                    $active_products = $row['num'];
                } elseif ($row['Product Status'] == 'Discontinuing') {
                    $discontinuing_products = $row['num'];
                } elseif ($row['Product Status'] == 'Suspended') {
                    $suspended_products = $row['num'];
                } elseif ($row['Product Status'] == 'Discontinued') {
                    $discontinued_products = $row['num'];
                }
            }
        }


        $active_products = $active_products + $discontinuing_products;

        $sql = sprintf(
            "SELECT count(*) AS num ,`Product Web State` AS web_state FROM  `Product Dimension` P WHERE `Product Store Key`=%d AND `Product Status` IN ('Active','Discontinuing') GROUP BY  `Product Web State`   ",
            $this->id

        );

        // print "$sql\n";

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['web_state'] == 'Discontinued') {
                    $row['web_state'] = 'Offline';
                }
                $elements_active_web_status_numbers[$row['web_state']] += $row['num'];
            }
        }


        $this->update(
            array(
                'Store Active Products'         => $active_products,
                'Store Suspended Products'      => $suspended_products,
                'Store Discontinuing Products'  => $discontinuing_products,
                'Store Discontinued Products'   => $discontinued_products,
                'Store Active Web For Sale'     => $elements_active_web_status_numbers['For Sale'],
                'Store Active Web Out of Stock' => $elements_active_web_status_numbers['Out of Stock'],
                'Store Active Web Offline'      => $elements_active_web_status_numbers['Offline']

            ),
            'no_history'
        );
    }


    function update_invoices_bis()
    {
        $invoices = 0;
        $refunds  = 0;


        $sql = sprintf(
            "select sum(if(`Invoice Type`='Invoice',1,0)) as invoices,sum(if(`Invoice Type`='Refund',1,0)) as refunds from  `Invoice Dimension` where `Invoice Store Key`=%d  ",
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $invoices = $row['invoices'];
                $refunds  = $row['refunds'];
            }
        }

        $this->fast_update(
            array(
                'Store Invoices' => $invoices,
                'Store Refunds'  => $refunds,
            ),
            'Store Data'
        );
    }
    function update_invoices()
    {
        return;
        $invoices = 0;
        $refunds  = 0;


        $sql = sprintf(
            "select sum(if(`Invoice Type`='Invoice',1,0)) as invoices,sum(if(`Invoice Type`='Refund',1,0)) as refunds from  `Invoice Dimension` where `Invoice Store Key`=%d  ",
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $invoices = $row['invoices'];
                $refunds  = $row['refunds'];
            }
        }

        $this->fast_update(
            array(
                'Store Invoices' => $invoices,
                'Store Refunds'  => $refunds,
            ),
            'Store Data'
        );
    }

    function update_orders_bis()
    {

                $this->update_orders_in_basket_data();
                $this->update_orders_in_process_data();
                $this->update_orders_in_warehouse_data();
                $this->update_orders_packed_data();
                $this->update_orders_packed_done_data();
                $this->update_orders_approved_data();
                $this->update_orders_dispatched();
                $this->update_orders_dispatched_today();
                $this->update_orders_cancelled();

    }

    function update_orders()
    {

        $this->update_orders_in_basket_data();
        $this->update_orders_in_process_data();
        $this->update_orders_in_warehouse_data();
        $this->update_orders_packed_data();
        $this->update_orders_packed_done_data();
        $this->update_orders_approved_data();
        $this->update_orders_dispatched();
        $this->update_orders_dispatched_today();
        $this->update_orders_cancelled();

    }

    function update_orders_in_basket_data()
    {
        $this->load_acc_data();

        $data = array(
            'in_basket' => array(
                'number'    => 0,
                'amount'    => 0,
                'dc_amount' => 0
            ),
        );

        $sql = sprintf(
            "SELECT count(*) AS num,ifnull(sum(`Order Total Net Amount`),0) AS amount,ifnull(sum(`Order Total Net Amount`*`Order Currency Exchange`),0) AS dc_amount FROM `Order Dimension` USE INDEX (StoreState)  WHERE `Order Store Key`=%d AND  `Order State`='InBasket'  ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $data['in_basket']['number']    = $row['num'];
                $data['in_basket']['amount']    = $row['amount'];
                $data['in_basket']['dc_amount'] = $row['dc_amount'];
            }
        }


        $data_to_update = array(
            'Store Orders In Basket Number' => $data['in_basket']['number'],
            'Store Orders In Basket Amount' => round($data['in_basket']['amount'], 2)
        );


        $this->fast_update($data_to_update, 'Store Data');

        $data_to_update = array(
            'Store DC Orders In Basket Amount' => round($data['in_basket']['dc_amount'], 2),
        );
        $this->fast_update($data_to_update, 'Store DC Data');
        $this->load_acc_data();
    }

    function load_acc_data()
    {
        $sql = sprintf("SELECT * FROM `Store Data`  WHERE `Store Key`=%d", $this->id);


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                foreach ($row as $key => $value) {
                    $this->data[$key] = $value;
                }
            }
        }

        $sql = sprintf("SELECT * FROM `Store DC Data`  WHERE `Store Key`=%d", $this->id);

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                foreach ($row as $key => $value) {
                    $this->data[$key] = $value;
                }
            }
        }
    }

    function update_orders_in_process_data()
    {
        $this->load_acc_data();

        $data = array(

            'in_process_paid'     => array(
                'number'    => 0,
                'amount'    => 0,
                'dc_amount' => 0
            ),
            'in_process_not_paid' => array(
                'number'    => 0,
                'amount'    => 0,
                'dc_amount' => 0
            )
        );
        $sql  = "SELECT count(*) AS num,ifnull(sum(`Order Total Net Amount`),0) AS amount,ifnull(sum(`Order Total Net Amount`*`Order Currency Exchange`),0) AS dc_amount FROM `Order Dimension` WHERE `Order Store Key`=?  AND  `Order State` ='InProcess'  AND !`Order To Pay Amount`>0 ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
                           $this->id
                       ));
        while ($row = $stmt->fetch()) {
            $data['in_process_paid']['number']    += $row['num'];
            $data['in_process_paid']['amount']    += $row['amount'];
            $data['in_process_paid']['dc_amount'] += $row['dc_amount'];
        }


        $sql  = "SELECT count(*) AS num,ifnull(sum(`Order Total Net Amount`) ,0)AS amount,ifnull(sum(`Order Total Net Amount`*`Order Currency Exchange`),0) AS dc_amount FROM `Order Dimension` WHERE `Order Store Key`=?  AND `Order State`='InProcess'  AND `Order To Pay Amount`>0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
                           $this->id
                       ));
        while ($row = $stmt->fetch()) {
            $data['in_process_not_paid']['number']    += $row['num'];
            $data['in_process_not_paid']['amount']    += $row['amount'];
            $data['in_process_not_paid']['dc_amount'] += $row['dc_amount'];
        }


        $data_to_update = array(
            'Store Orders In Process Paid Number'     => $data['in_process_paid']['number'],
            'Store Orders In Process Paid Amount'     => round($data['in_process_paid']['amount'], 2),
            'Store Orders In Process Not Paid Number' => $data['in_process_not_paid']['number'],
            'Store Orders In Process Not Paid Amount' => round($data['in_process_not_paid']['amount'], 2),
            'Store Orders In Process Number'          => $data['in_process_paid']['number'] + $data['in_process_not_paid']['number'],


        );


        $this->fast_update($data_to_update, 'Store Data');

        $data_to_update = array(
            'Store DC Orders In Process Paid Amount'     => round($data['in_process_paid']['dc_amount'], 2),
            'Store DC Orders In Process Not Paid Amount' => round($data['in_process_not_paid']['dc_amount'], 2)

        );
        $this->fast_update($data_to_update, 'Store DC Data');
    }

    function update_orders_in_warehouse_data()
    {
        $data = array(
            'warehouse'             => array(
                'number'    => 0,
                'amount'    => 0,
                'dc_amount' => 0
            ),
            'warehouse_no_alerts'   => array(
                'number'    => 0,
                'amount'    => 0,
                'dc_amount' => 0
            ),
            'warehouse_with_alerts' => array(
                'number'    => 0,
                'amount'    => 0,
                'dc_amount' => 0
            ),
        );


        $sql = sprintf(
            "SELECT ifnull(sum(`Order Total Net Amount`),0) AS amount,ifnull(sum(`Order Total Net Amount`*`Order Currency Exchange`),0) AS dc_amount FROM `Order Dimension` WHERE `Order Store Key`=%d  AND  `Order State` ='InWarehouse' and `Order Delivery Note Alert`='No'",
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $data['warehouse_no_alerts']['amount']    = $row['amount'];
                $data['warehouse_no_alerts']['dc_amount'] = $row['dc_amount'];
            }
        }


        $sql = sprintf(
            "SELECT ifnull(sum(`Order Total Net Amount`),0) AS amount,ifnull(sum(`Order Total Net Amount`*`Order Currency Exchange`),0) AS dc_amount FROM `Order Dimension` WHERE `Order Store Key`=%d  AND  `Order State` ='InWarehouse' and `Order Delivery Note Alert`='Yes'",
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $data['warehouse_with_alerts']['amount']    = $row['amount'];
                $data['warehouse_with_alerts']['dc_amount'] = $row['dc_amount'];
            }
        }


        $sql = sprintf(
            "SELECT count(*) AS num FROM `Order Dimension` WHERE `Order Store Key`=%d  and (( `Order State` ='InWarehouse' and  `Order Delivery Note Alert`='No'  ) or `Order Replacements In Warehouse without Alerts`>0  ) ",
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $data['warehouse_no_alerts']['number'] = $row['num'];
            }
        }

        $sql = sprintf(
            "SELECT count(*) AS num FROM `Order Dimension` WHERE `Order Store Key`=%d  and (( `Order State` ='InWarehouse' and  `Order Delivery Note Alert`='Yes'  ) or `Order Replacements In Warehouse with Alerts`>0  ) ",
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $data['warehouse_with_alerts']['number'] = $row['num'];
            }
        }


        $data_to_update = array(
            'Store Orders In Warehouse Number' => $data['warehouse_with_alerts']['number'] + $data['warehouse_no_alerts']['number'],
            'Store Orders In Warehouse Amount' => round($data['warehouse_with_alerts']['amount'] + $data['warehouse_no_alerts']['amount'], 2),

            'Store Orders In Warehouse No Alerts Number' => $data['warehouse_no_alerts']['number'],
            'Store Orders In Warehouse No Alerts Amount' => round($data['warehouse_no_alerts']['amount'], 2),

            'Store Orders In Warehouse With Alerts Number' => $data['warehouse_with_alerts']['number'],
            'Store Orders In Warehouse With Alerts Amount' => round($data['warehouse_with_alerts']['amount'], 2)

        );


        $this->fast_update($data_to_update, 'Store Data');

        $data_to_update = array(

            'Store DC Orders In Warehouse Amount'             => round($data['warehouse_with_alerts']['dc_amount'] + $data['warehouse_no_alerts']['dc_amount'], 2),
            'Store DC Orders In Warehouse No Alerts Amount'   => round($data['warehouse_no_alerts']['dc_amount'], 2),
            'Store DC Orders In Warehouse With Alerts Amount' => round($data['warehouse_with_alerts']['dc_amount'], 2)

        );
        $this->fast_update($data_to_update, 'Store DC Data');
    }

    function update_orders_packed_data()
    {
        $data = array(
            'packed' => array(
                'number'    => 0,
                'amount'    => 0,
                'dc_amount' => 0
            ),
        );


        $sql = sprintf(
            "SELECT count(*) AS num,ifnull(sum(`Order Total Net Amount`),0) AS amount,ifnull(sum(`Order Total Net Amount`*`Order Currency Exchange`),0) AS dc_amount FROM `Order Dimension` WHERE `Order Store Key`=%d  AND `Order State` ='Packed'  ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $data['packed']['number']    = $row['num'];
                $data['packed']['amount']    = $row['amount'];
                $data['packed']['dc_amount'] = $row['dc_amount'];
            }
        }


        $data_to_update = array(
            'Store Orders Packed Number' => $data['packed']['number'],
            'Store Orders Packed Amount' => round($data['packed']['amount'], 2)


        );


        $this->fast_update($data_to_update, 'Store Data');

        $data_to_update = array(
            'Store DC Orders Packed Amount' => round($data['packed']['dc_amount'], 2)

        );
        $this->fast_update($data_to_update, 'Store DC Data');
    }

    function update_orders_packed_done_data()
    {
        $data = array(
            'packed' => array(
                'number'    => 0,
                'amount'    => 0,
                'dc_amount' => 0
            ),
        );


        $sql = sprintf(
            "SELECT count(*) AS num,ifnull(sum(`Order Total Net Amount`),0) AS amount,ifnull(sum(`Order Total Net Amount`*`Order Currency Exchange`),0) AS dc_amount FROM `Order Dimension` WHERE `Order Store Key`=%d  AND `Order State` ='PackedDone'  ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $data['packed']['number']    = $row['num'];
                $data['packed']['amount']    = $row['amount'];
                $data['packed']['dc_amount'] = $row['dc_amount'];
            }
        }


        $sql = sprintf(
            "SELECT count(*) AS num FROM `Order Dimension` WHERE  `Order Store Key`=%d  AND   `Order Replacements Packed Done`>0 ",
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $data['packed']['number'] += $row['num'];
            }
        }


        $data_to_update = array(
            'Store Orders Packed Done Number' => $data['packed']['number'],
            'Store Orders Packed Done Amount' => round($data['packed']['amount'], 2)


        );


        $this->fast_update($data_to_update, 'Store Data');

        $data_to_update = array(
            'Store DC Orders Packed Done Amount' => round($data['packed']['dc_amount'], 2)

        );
        $this->fast_update($data_to_update, 'Store DC Data');
    }

    function update_orders_approved_data()
    {
        $data = array(
            'approved' => array(
                'number'    => 0,
                'amount'    => 0,
                'dc_amount' => 0
            ),
        );


        $sql = sprintf(
            "SELECT count(*) AS num,ifnull(sum(`Order Total Net Amount`),0) AS amount,ifnull(sum(`Order Total Net Amount`*`Order Currency Exchange`),0) AS dc_amount FROM `Order Dimension` WHERE  `Order Store Key`=%d  AND   `Order State` ='Approved' ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $data['approved']['number']    = $row['num'];
                $data['approved']['amount']    = $row['amount'];
                $data['approved']['dc_amount'] = $row['dc_amount'];
            }
        }


        $sql = sprintf(
            "SELECT count(*) AS num FROM `Order Dimension` WHERE  `Order Store Key`=%d  AND   `Order Replacements Approved`>0 ",
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $data['approved']['number'] += $row['num'];
            }
        }


        $data_to_update = array(
            'Store Orders Dispatch Approved Number' => $data['approved']['number'],
            'Store Orders Dispatch Approved Amount' => round($data['approved']['amount'], 2)


        );


        $this->fast_update($data_to_update, 'Store Data');

        $data_to_update = array(
            'Store DC Orders Dispatch Approved Amount' => round($data['approved']['dc_amount'], 2)

        );
        $this->fast_update($data_to_update, 'Store DC Data');
        $this->fast_update($data_to_update, 'Store DC Data');
    }


    function update_orders_dispatched()
    {
        $data = array(
            'dispatched' => array(
                'number'    => 0,
                'amount'    => 0,
                'dc_amount' => 0
            ),

        );


        $sql = sprintf(
            "SELECT count(*) AS num,ifnull(sum(`Order Total Net Amount`),0) AS amount,ifnull(sum(`Order Total Net Amount`*`Order Currency Exchange`),0) AS dc_amount FROM `Order Dimension` USE INDEX (StoreState)   WHERE `Order Store Key`=%d  AND  `Order State` ='Dispatched' ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $data['dispatched']['number']    = $row['num'];
                $data['dispatched']['amount']    = $row['amount'];
                $data['dispatched']['dc_amount'] = $row['dc_amount'];
            }
        }


        $data_to_update = array(
            'Store Orders Dispatched Number' => $data['dispatched']['number'],
            'Store Orders Dispatched Amount' => round($data['dispatched']['amount'], 2)


        );
        $this->fast_update($data_to_update, 'Store Data');

        $data_to_update = array(
            'Store DC Orders Dispatched Amount' => round($data['dispatched']['dc_amount'], 2)

        );
        $this->fast_update($data_to_update, 'Store DC Data');
    }

    function update_orders_dispatched_today()
    {
        $data = array(
            'dispatched_today' => array(
                'number'    => 0,
                'amount'    => 0,
                'dc_amount' => 0
            ),


        );


        $sql = sprintf(
            "SELECT count(*) AS num,ifnull(sum(`Order Total Net Amount`),0) AS amount ,ifnull(sum(`Order Total Net Amount`*`Order Currency Exchange`),0) AS dc_amount FROM `Order Dimension` WHERE  `Order Store Key`=%d  AND   `Order State` ='Dispatched' AND `Order Dispatched Date`>=%s ",
            $this->id,
            prepare_mysql(gmdate('Y-m-d 00:00:00'))

        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $data['dispatched_today']['number']    = $row['num'];
                $data['dispatched_today']['amount']    = $row['amount'];
                $data['dispatched_today']['dc_amount'] = $row['dc_amount'];
            }
        }


        $sql = sprintf(
            "SELECT count(*) AS num FROM `Order Dimension` WHERE  `Order Store Key`=%d  AND   `Order Replacements Dispatched Today`>0 ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $data['dispatched_today']['number'] += $row['num'];
            }
        }


        $data_to_update = array(
            'Store Orders Dispatched Today Number' => $data['dispatched_today']['number'],
            'Store Orders Dispatched Today Amount' => round($data['dispatched_today']['amount'], 2)


        );


        $this->fast_update($data_to_update, 'Store Data');

        $data_to_update = array(
            'Store DC Orders Dispatched Today Amount' => round($data['dispatched_today']['dc_amount'], 2)

        );
        $this->fast_update($data_to_update, 'Store DC Data');
    }

    function update_orders_cancelled()
    {
        $data = array(

            'cancelled' => array(
                'number'    => 0,
                'amount'    => 0,
                'dc_amount' => 0
            ),
        );


        $sql = sprintf(
            "SELECT count(*) AS num,ifnull(sum(`Order Total Net Amount`),0) AS amount,ifnull(sum(`Order Total Net Amount`*`Order Currency Exchange`),0) AS dc_amount FROM `Order Dimension` USE INDEX (StoreState)  WHERE  `Order Store Key`=%d  AND   `Order State` ='Cancelled' ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $data['cancelled']['number']    = $row['num'];
                $data['cancelled']['amount']    = $row['amount'];
                $data['cancelled']['dc_amount'] = $row['dc_amount'];
            }
        }


        $data_to_update = array(

            'Store Orders Cancelled Number' => $data['cancelled']['number'],
            'Store Orders Cancelled Amount' => round($data['cancelled']['amount'], 2)

        );
        $this->fast_update($data_to_update, 'Store Data');


        $data_to_update = array(
            'Store DC Orders Cancelled Amount' => round($data['cancelled']['dc_amount'], 2)
        );
        $this->fast_update($data_to_update, 'Store DC Data');
    }

    function update_payments_bis()
    {


        $data = array(

            'payments' => array(
                'number'    => 0,
                'amount'    => 0,
                'dc_amount' => 0
            ),
            'credits'  => array(
                'number' => 0,
                'amount' => 0,
            ),
        );

        $sql = sprintf(
            "SELECT count(*) AS num,ifnull(sum(`Payment Transaction Amount`),0) AS amount,ifnull(sum(`Payment Transaction Amount`*`Payment Currency Exchange Rate`),0) AS dc_amount FROM `Payment Dimension`  WHERE  `Payment Store Key`=%d  AND   `Payment Transaction Status` ='Completed' ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $data['payments']['number']    = $row['num'];
                $data['payments']['amount']    = $row['amount'];
                $data['payments']['dc_amount'] = $row['dc_amount'];
            }
        }


        $sql = sprintf(
            "SELECT count(*) AS num,ifnull(sum(`Customer Account Balance`),0) AS amount FROM `Customer Dimension`  WHERE  `Customer Store Key`=%d  AND   `Customer Account Balance`!=0 ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $data['credits']['number'] = $row['num'];
                $data['credits']['amount'] = $row['amount'];
            }
        }


        $data_to_update = array(
            'Store Total Acc Payments'        => $data['payments']['number'],
            'Store Total Acc Payments Amount' => round($data['payments']['amount'], 2),
            'Store Total Acc Credits'         => $data['credits']['number'],
            'Store Total Acc Credits Amount'  => round($data['credits']['amount'], 2)

        );


        $this->fast_update($data_to_update, 'Store Data');


        $data_to_update = array(
            'Store DC Total Acc Payments Amount' => round($data['payments']['dc_amount'], 2)

        );
        $this->fast_update($data_to_update, 'Store DC Data');
    }
    function update_payments()
    {
return;

        $data = array(

            'payments' => array(
                'number'    => 0,
                'amount'    => 0,
                'dc_amount' => 0
            ),
            'credits'  => array(
                'number' => 0,
                'amount' => 0,
            ),
        );

        $sql = sprintf(
            "SELECT count(*) AS num,ifnull(sum(`Payment Transaction Amount`),0) AS amount,ifnull(sum(`Payment Transaction Amount`*`Payment Currency Exchange Rate`),0) AS dc_amount FROM `Payment Dimension`  WHERE  `Payment Store Key`=%d  AND   `Payment Transaction Status` ='Completed' ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $data['payments']['number']    = $row['num'];
                $data['payments']['amount']    = $row['amount'];
                $data['payments']['dc_amount'] = $row['dc_amount'];
            }
        }


        $sql = sprintf(
            "SELECT count(*) AS num,ifnull(sum(`Customer Account Balance`),0) AS amount FROM `Customer Dimension`  WHERE  `Customer Store Key`=%d  AND   `Customer Account Balance`!=0 ",
            $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $data['credits']['number'] = $row['num'];
                $data['credits']['amount'] = $row['amount'];
            }
        }


        $data_to_update = array(
            'Store Total Acc Payments'        => $data['payments']['number'],
            'Store Total Acc Payments Amount' => round($data['payments']['amount'], 2),
            'Store Total Acc Credits'         => $data['credits']['number'],
            'Store Total Acc Credits Amount'  => round($data['credits']['amount'], 2)

        );


        $this->fast_update($data_to_update, 'Store Data');


        $data_to_update = array(
            'Store DC Total Acc Payments Amount' => round($data['payments']['dc_amount'], 2)

        );
        $this->fast_update($data_to_update, 'Store DC Data');
    }

    function update_previous_years_data()
    {
        foreach (range(1, 5) as $i) {
            $data_iy_ago = $this->get_sales_data(
                date('Y-01-01 00:00:00', strtotime('-'.$i.' year')),
                date('Y-01-01 00:00:00', strtotime('-'.($i - 1).' year'))
            );


            $data_to_update = array(
                "Store $i Year Ago Invoiced Discount Amount" => round($data_iy_ago['discount_amount'], 2),
                "Store $i Year Ago Invoiced Amount"          => round($data_iy_ago['amount'], 2),
                "Store $i Year Ago Invoices"                 => $data_iy_ago['invoices'],
                "Store $i Year Ago Refunds"                  => $data_iy_ago['refunds'],
                "Store $i Year Ago Replacements"             => $data_iy_ago['replacements'],
                "Store $i Year Ago Delivery Notes"           => $data_iy_ago['deliveries'],
                "Store $i Year Ago Profit"                   => round($data_iy_ago['profit'], 2),

            );


            $this->fast_update($data_to_update, 'Store Data');

            $data_to_update = array(
                "Store DC $i Year Ago Invoiced Amount"          => round($data_iy_ago['dc_amount'], 2),
                "Store DC $i Year Ago Invoiced Discount Amount" => round($data_iy_ago['dc_discount_amount'], 2),
                "Store DC $i Year Ago Profit"                   => round($data_iy_ago['dc_profit'], 2)
            );
            $this->fast_update($data_to_update, 'Store DC Data');
        }

        $this->fast_update(['Store Acc Previous Intervals Updated' => gmdate('Y-m-d H:i:s')]);
    }

    function update_previous_quarters_data()
    {
        include_once 'utils/date_functions.php';

        foreach (range(1, 4) as $i) {
            $dates     = get_previous_quarters_dates($i);
            $dates_1yb = get_previous_quarters_dates($i + 4);


            $sales_data     = $this->get_sales_data(
                $dates['start'],
                $dates['end']
            );
            $sales_data_1yb = $this->get_sales_data(
                $dates_1yb['start'],
                $dates_1yb['end']
            );

            $data_to_update = array(
                "Store $i Quarter Ago Invoiced Discount Amount" => round($sales_data['discount_amount'], 2),
                "Store $i Quarter Ago Invoiced Amount"          => round($sales_data['amount'], 2),
                "Store $i Quarter Ago Invoices"                 => $sales_data['invoices'],
                "Store $i Quarter Ago Refunds"                  => $sales_data['refunds'],
                "Store $i Quarter Ago Replacements"             => $sales_data['replacements'],
                "Store $i Quarter Ago Delivery Notes"           => $sales_data['deliveries'],
                "Store $i Quarter Ago Profit"                   => round($sales_data['profit'], 2),


                "Store $i Quarter Ago 1YB Invoiced Discount Amount" => round($sales_data_1yb['discount_amount'], 2),
                "Store $i Quarter Ago 1YB Invoiced Amount"          => round($sales_data_1yb['amount'], 2),
                "Store $i Quarter Ago 1YB Invoices"                 => $sales_data_1yb['invoices'],
                "Store $i Quarter Ago 1YB Refunds"                  => $sales_data_1yb['refunds'],
                "Store $i Quarter Ago 1YB Replacements"             => $sales_data_1yb['replacements'],
                "Store $i Quarter Ago 1YB Delivery Notes"           => $sales_data_1yb['deliveries'],
                "Store $i Quarter Ago 1YB Profit"                   => round($sales_data_1yb['profit'], 2),

            );

            $this->fast_update($data_to_update, 'Store Data');

            $data_to_update = array(
                "Store DC $i Quarter Ago Invoiced Amount"              => round($sales_data['dc_amount'], 2),
                "Store DC $i Quarter Ago Invoiced Discount Amount"     => round($sales_data['dc_discount_amount'], 2),
                "Store DC $i Quarter Ago Profit"                       => round($sales_data['dc_profit'], 2),
                "Store DC $i Quarter Ago 1YB Invoiced Amount"          => round($sales_data_1yb['dc_amount'], 2),
                "Store DC $i Quarter Ago 1YB Invoiced Discount Amount" => round($sales_data_1yb['dc_discount_amount'], 2),
                "Store DC $i Quarter Ago 1YB Profit"                   => round($sales_data_1yb['dc_profit'], 2)
            );
            $this->fast_update($data_to_update, 'Store DC Data');
        }

        $this->fast_update(['Store Acc Previous Intervals Updated' => gmdate('Y-m-d H:i:s')]);
    }


    function get_emails_data($type, $from_date, $to_date)
    {
        $mailshots    = 0;
        $emails       = 0;
        $open         = 0;
        $unsubscribed = 0;
        $clicked      = 0;


        $args = [
            $type,
            $this->id
        ];

        $placeholder  = '?';
        $extra_fields = '';

        if ($type == 'All') {
            $args         = [
                'Newsletter',
                'Marketing',
                'AbandonedCart',
                $this->id
            ];
            $placeholder  = '?,?,?';
            $extra_fields = ' , sum(`Email Campaign Open`) as open, sum(`Email Campaign Unsubscribed`) as  unsubscribed , sum(`Email Campaign Clicked`) as clicked ';
        }


        //enum('InProcess','SetRecipients','ComposingEmail','Ready','Scheduled','Sending','Sent','Cancelled','Stopped')

        $sql  = sprintf(
            "select count(*) as num from `Email Campaign Dimension` where `Email Campaign Type` in ($placeholder)  and `Email Campaign Store Key`=?  and `Email Campaign State` in ('Sending','Sent','Stopped') %s %s ",
            ($from_date ? sprintf('and `Email Campaign Start Send Date`>%s', prepare_mysql($from_date)) : ''),
            ($to_date ? sprintf('and `Email Campaign Start Send Date`<%s', prepare_mysql($to_date)) : '')

        );
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            $args
        );
        while ($row = $stmt->fetch()) {
            $mailshots = $row['num'];
        }

        $sql = sprintf(
            "select sum(`Email Campaign Sent`) as emails $extra_fields from `Email Campaign Dimension` where `Email Campaign Type` in ($placeholder)  and `Email Campaign Store Key` in (?)  and `Email Campaign State` in ('Sending','Sent','Cancelled','Stopped')  %s %s ",
            ($from_date ? sprintf('and `Email Campaign Start Send Date`>%s', prepare_mysql($from_date)) : ''),
            ($to_date ? sprintf('and `Email Campaign Start Send Date`<%s', prepare_mysql($to_date)) : '')

        );

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            $args
        );
        while ($row = $stmt->fetch()) {
            $emails = $row['emails'];
            if ($type == 'All') {
                $open         = $row['open'];
                $unsubscribed = $row['unsubscribed'];
                $clicked      = $row['clicked'];
            }
        }

        //print $this->code." $mailshots $emails\n ";


        return array(
            'mailshots'    => $mailshots,
            'emails'       => $emails,
            'open'         => $open,
            'unsubscribed' => $unsubscribed,
            'clicked'      => $clicked
        );
    }


    function update_emails_data($type, $interval, $this_year = true, $last_year = true)
    {
        include_once 'utils/date_functions.php';
        list($db_interval, $from_date, $to_date, $from_date_1yb, $to_date_1yb) = calculate_interval_dates($this->db, $interval);


        //  print "$interval $db_interval, $from_date, $to_date, $from_date_1yb, $to_date_1yb \n";


        if ($this_year) {
            $emails_data = $this->get_emails_data($type, $from_date, $to_date);
            if ($type == 'All') {
                $data_to_update = array(
                    "Store $db_interval Acc Mailshots"           => $emails_data['mailshots'],
                    "Store $db_interval Acc Emails"              => $emails_data['emails'],
                    "Store $db_interval Acc Emails Open"         => $emails_data['open'],
                    "Store $db_interval Acc Emails Unsubscribed" => $emails_data['unsubscribed'],
                    "Store $db_interval Acc Emails Clicked"      => $emails_data['clicked'],
                );
            } else {
                $data_to_update = array(
                    "Store $db_interval Acc $type Mailshots" => $emails_data['mailshots'],
                    "Store $db_interval Acc $type Emails"    => $emails_data['emails'],
                );
            }
            $this->fast_update($data_to_update, 'Store Emails Data');
        }

        if ($from_date_1yb and $last_year) {
            $emails_data = $this->get_emails_data($type, $from_date, $to_date);
            if ($type == 'All') {
                $data_to_update = array(
                    "Store $db_interval Acc 1YB Mailshots"           => $emails_data['mailshots'],
                    "Store $db_interval Acc 1YB Emails"              => $emails_data['emails'],
                    "Store $db_interval Acc 1YB Emails Open"         => $emails_data['open'],
                    "Store $db_interval Acc 1YB Emails Unsubscribed" => $emails_data['unsubscribed'],
                    "Store $db_interval Acc 1YB Emails Clicked"      => $emails_data['clicked'],
                );
            } else {
                $data_to_update = array(
                    "Store $db_interval Acc 1YB $type Mailshots" => $emails_data['mailshots'],
                    "Store $db_interval Acc 1YB $type Emails"    => $emails_data['emails'],
                );
            }
            $this->fast_update($data_to_update, 'Store Emails Data');
        }
    }

    function update_sales_from_invoices($interval, $this_year = true, $last_year = true)
    {
        include_once 'utils/date_functions.php';


        list($db_interval, $from_date, $to_date, $from_date_1yb, $to_date_1yb) = calculate_interval_dates($this->db, $interval);

        //print "$db_interval, $from_date, $to_date, $from_date_1yb, $to_date_1yb \n";

        if ($this_year) {
            $sales_data = $this->get_sales_data($from_date, $to_date);


            $data_to_update = array(
                "Store $db_interval Acc Invoiced Discount Amount" => round($sales_data['discount_amount'], 2),
                "Store $db_interval Acc Invoiced Amount"          => round($sales_data['amount'], 2),
                "Store $db_interval Acc Invoices"                 => $sales_data['invoices'],
                "Store $db_interval Acc Refunds"                  => $sales_data['refunds'],
                "Store $db_interval Acc Replacements"             => $sales_data['replacements'],
                "Store $db_interval Acc Delivery Notes"           => $sales_data['deliveries'],
                "Store $db_interval Acc Profit"                   => round($sales_data['profit'], 2),
                "Store $db_interval Acc Customers"                => $sales_data['customers'],
                "Store $db_interval Acc Repeat Customers"         => $sales_data['repeat_customers'],


            );


            $this->fast_update($data_to_update, 'Store Data');

            $data_to_update = array(
                "Store DC $db_interval Acc Invoiced Amount"          => round($sales_data['dc_amount'], 2),
                "Store DC $db_interval Acc Invoiced Discount Amount" => round($sales_data['dc_discount_amount'], 2),
                "Store DC $db_interval Acc Profit"                   => round($sales_data['dc_profit'], 2)
            );
            $this->fast_update($data_to_update, 'Store DC Data');
        }

        if ($from_date_1yb and $last_year) {
            $sales_data = $this->get_sales_data($from_date_1yb, $to_date_1yb);

            $data_to_update = array(
                "Store $db_interval Acc 1YB Invoiced Discount Amount" => round($sales_data['discount_amount'], 2),
                "Store $db_interval Acc 1YB Invoiced Amount"          => round($sales_data['amount'], 2),
                "Store $db_interval Acc 1YB Invoices"                 => $sales_data['invoices'],
                "Store $db_interval Acc 1YB Refunds"                  => $sales_data['refunds'],
                "Store $db_interval Acc 1YB Replacements"             => $sales_data['replacements'],
                "Store $db_interval Acc 1YB Delivery Notes"           => $sales_data['deliveries'],
                "Store $db_interval Acc 1YB Profit"                   => round($sales_data['profit'], 2),
                "Store $db_interval Acc 1YB Customers"                => $sales_data['customers'],
                "Store $db_interval Acc 1YB Repeat Customers"         => $sales_data['repeat_customers'],

            );

            $this->fast_update($data_to_update, 'Store Data');

            $data_to_update = array(
                "Store DC $db_interval Acc 1YB Invoiced Amount"          => round($sales_data['dc_amount'], 2),
                "Store DC $db_interval Acc 1YB Invoiced Discount Amount" => round($sales_data['dc_discount_amount'], 2),
                "Store DC $db_interval Acc 1YB Profit"                   => round($sales_data['dc_profit'], 2)
            );
            $this->fast_update($data_to_update, 'Store DC Data');
        }


        if (in_array($db_interval, [
            'Total',
            'Year To Date',
            'Quarter To Date',
            'Week To Date',
            'Month To Date',
            'Today'
        ])) {
            $this->fast_update(['Store Acc To Day Updated' => gmdate('Y-m-d H:i:s')]);
        } elseif (in_array($db_interval, [
            '1 Year',
            '1 Month',
            '1 Week',
            '1 Quarter'
        ])) {
            $this->fast_update(['Store Acc Ongoing Intervals Updated' => gmdate('Y-m-d H:i:s')]);
        } elseif (in_array($db_interval, [
            'Last Month',
            'Last Week',
            'Yesterday',
            'Last Year'
        ])) {
            $this->fast_update(['Store Acc Previous Intervals Updated' => gmdate('Y-m-d H:i:s')]);
        }
    }

    function update_email_campaign_data()
    {
        $email_campaigns = 0;
        $sql             = sprintf("SELECT count(*) AS email_campaign FROM `Email Campaign Dimension` WHERE `Email Campaign Store Key`=%d  ", $this->id);

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $email_campaigns = $row['email_campaign'];
            }
        }


        $this->fast_update(array('Store Email Campaigns' => $email_campaigns));
    }


    function update_deals_data()
    {
        $deals = 0;

        $sql = sprintf("SELECT count(*) AS num FROM `Deal Dimension` WHERE `Deal Store Key`=%d AND `Deal Status`='Active' ", $this->id);

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $deals = $row['num'];
            }
        }


        $this->update(array('Store Active Deals' => $deals), 'no_history');
    }


    function post_add_history($history_key, $type = false)
    {
        if (!$type) {
            $type = 'Changes';
        }

        $sql = sprintf(
            "INSERT INTO  `Store History Bridge` (`Store Key`,`History Key`,`Type`) VALUES (%d,%d,%s)",
            $this->id,
            $history_key,
            prepare_mysql($type)
        );

        $this->db->exec($sql);
    }

    function get_payment_accounts($type = 'objects', $filter = ''): array
    {
        if ($filter == 'Active') {
            $where = ' and `Payment Account Store Status`="Active" ';
        } else {
            $where = '';
        }

        $payment_accounts = array();
        $sql              = sprintf(
            "SELECT PA.`Payment Account Key` FROM `Payment Account Dimension` PA LEFT JOIN `Payment Account Store Bridge` B ON (PA.`Payment Account Key`=B.`Payment Account Store Payment Account Key`)   WHERE `Payment Account Store Store Key`=%d  %s ",
            $this->id,
            $where
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($type == 'objects') {
                    $payment_accounts[] = get_object('Payment_Account', $row['Payment Account Key']);
                } else {
                    $payment_accounts[] = $row['Payment Account Key'];
                }
            }
        }


        return $payment_accounts;
    }

    function get_field_label($field)
    {
        switch ($field) {
            case 'Store Code':
                $label = _('code');
                break;
            case 'Store Name':
                $label = _('name');
                break;
            case 'Store Currency Code':
                $label = _('currency');
                break;
            case 'Store Locale':
                $label = _('language');
                break;
            case 'Store Timezone':
                $label = _('timezone');
                break;
            case 'Store Email':
                $label = _('email');
                break;
            case 'Store Telephone':
                $label = _('telephone');
                break;
            case 'Store Address':
                $label = _('address');
                break;
            case 'Store VAT Number':
                $label = _('VAT number');
                break;
            case 'Store Company Name':
                $label = _('company name');
                break;
            case 'Store Company Number':
                $label = _('company number');
                break;
            case 'Store URL':
                $label = _('website URL');
                break;

            case 'Store Email Template Signature':
                $label = _('Emails signature');
                break;
            case 'Store Invoice Message':
                $label = _('Invoice signature');
                break;
            case 'Store Proforma Message':
                $label = _('Proforma signature');
                break;
            case 'Store Collect Address':
                $label = _("Collection address");
                break;
            case 'Store Order Public ID Format':
                $label = _("order number format");
                break;
            case 'Store Order Last Order ID':
                $label = _("last incremental order number");
                break;
            case 'Store Next Invoice Public ID Method':
                $label = _("Invoice number");
                break;
            case 'Store Invoice Public ID Format':
                $label = _("invoice number format");
                break;
            case 'Store Invoice Last Invoice Public ID':
                $label = _("last incremental invoice number");
                break;
            case 'Store Invoice Last Refund Public ID':
                $label = _("last incremental refund number");
                break;
            case 'Store Next Refund Public ID Method':
                $label = _("Refund number");
                break;
            case 'Store Default Product Pricing Policy Key':
                $label = _('pricing policy');
                break;
            default:
                $label = $field;
        }

        return $label;
    }


    public function create_customer($data): array
    {
        $this->new_customer     = false;
        $this->new_website_user = false;

        $data['editor']             = $this->editor;
        $data['Customer Store Key'] = $this->id;


        $data['Customer Billing Address Link']  = 'Contact';
        $data['Customer Delivery Address Link'] = 'Billing';


        $address_fields = array(
            'Address Recipient'            => $data['Customer Main Contact Name'],
            'Address Organization'         => $data['Customer Company Name'],
            'Address Line 1'               => '',
            'Address Line 2'               => '',
            'Address Sorting Code'         => '',
            'Address Postal Code'          => '',
            'Address Dependent Locality'   => '',
            'Address Locality'             => '',
            'Address Administrative Area'  => '',
            'Address Country 2 Alpha Code' => $data['Customer Contact Address country'],

        );
        unset($data['Customer Contact Address country']);

        if (isset($data['Customer Contact Address addressLine1'])) {
            $address_fields['Address Line 1'] = $data['Customer Contact Address addressLine1'];
            unset($data['Customer Contact Address addressLine1']);
        }
        if (isset($data['Customer Contact Address addressLine2'])) {
            $address_fields['Address Line 2'] = $data['Customer Contact Address addressLine2'];
            unset($data['Customer Contact Address addressLine2']);
        }
        if (isset($data['Customer Contact Address sortingCode'])) {
            $address_fields['Address Sorting Code'] = $data['Customer Contact Address sortingCode'];
            unset($data['Customer Contact Address sortingCode']);
        }
        if (isset($data['Customer Contact Address postalCode'])) {
            $address_fields['Address Postal Code'] = $data['Customer Contact Address postalCode'];
            unset($data['Customer Contact Address postalCode']);
        }

        if (isset($data['Customer Contact Address dependentLocality'])) {
            $address_fields['Address Dependent Locality'] = $data['Customer Contact Address dependentLocality'];
            unset($data['Customer Contact Address dependentLocality']);
        }

        if (isset($data['Customer Contact Address locality'])) {
            $address_fields['Address Locality'] = $data['Customer Contact Address locality'];
            unset($data['Customer Contact Address locality']);
        }

        if (isset($data['Customer Contact Address administrativeArea'])) {
            $address_fields['Address Administrative Area'] = $data['Customer Contact Address administrativeArea'];
            unset($data['Customer Contact Address administrativeArea']);
        }

        if ($this->get('Store Type') == 'Fulfilment') {
            $data['Customer Fulfilment'] = 'Yes';
        }


        $customer         = new Customer('new', $data, $address_fields);
        $website_user     = false;
        $website_user_key = false;

        if ($customer->id) {
            $this->new_customer_msg = $customer->msg;

            if ($customer->new) {
                if ($this->get('Store Type') == 'Fulfilment') {
                    $account = get_object('Account', 1);
                    $account->load_acc_data();
                    $sql = "insert into `Customer Fulfilment Dimension` (`Customer Fulfilment Customer Key`,`Customer Fulfilment Metadata`,`Customer Fulfilment Warehouse Key`) values (?,'{}',?)";
                    $this->db->prepare($sql)->execute(array(
                                                          $customer->id,
                                                          $account->properties('fulfilment_warehouse_key')

                                                      ));
                }


                $customer->fast_update_json_field('Customer Metadata', 'cur', $this->get('Store Currency Code'));
                $this->new_customer = true;
                $this->new_customer_id = $customer->id;

                include_once 'utils/new_fork.php';


                if ($customer->get('Customer Main Plain Email') != '') {
                    $website = get_object('website', $this->get('Store Website Key'));

                    $user_data['Website User Handle']       = $customer->get('Customer Main Plain Email');
                    $user_data['Website User Customer Key'] = $customer->id;
                    $website_user                           = $website->create_user($user_data);


                    $this->new_customer = true;

                    $this->new_website_user = $website_user->new;
                    $website_user_key       = $website_user->id;

                    $customer->update(array('Customer Website User Key' => $website_user_key), 'no_history');
                }

                new_housekeeping_fork(
                    'au_housekeeping',
                    array(
                        'type'             => 'customer_created',
                        'customer_key'     => $customer->id,
                        'website_user_key' => $website_user_key,
                        'editor'           => $this->editor
                    ),
                    DNS_ACCOUNT_CODE
                );
            } else {
                $this->error = true;
                $this->msg   = $customer->msg;
            }

            return array(
                'Customer'     => $customer,
                'Website_User' => $website_user
            );
        } else {
            $this->error = true;
            $this->msg   = $customer->msg;

            return array(
                'Customer'     => false,
                'Website_User' => false
            );
        }
    }

    function create_prospect($data)
    {
        $this->new_prospect = false;
        $this->new_prospect_id=null;

        $data['editor']             = $this->editor;
        $data['Prospect Store Key'] = $this->id;


        $data['Prospect User Key'] = $this->editor['User Key'];


        include_once('class.Sales_Representative.php');
        $sales_representative = new Sales_Representative('find', array(
            'Sales Representative User Key' => $this->editor['User Key'],
            'editor'                        => $this->editor
        ));
        $sales_representative->fast_update(array('Sales Representative Prospect Agent' => 'Yes'));


        $data['Prospect Sales Representative Key'] = $sales_representative->id;

        if (!empty($data['Prospect Contact Address country'])) {
            $address_fields = array(
                'Address Recipient'            => $data['Prospect Main Contact Name'],
                'Address Organization'         => $data['Prospect Company Name'],
                'Address Line 1'               => '',
                'Address Line 2'               => '',
                'Address Sorting Code'         => '',
                'Address Postal Code'          => '',
                'Address Dependent Locality'   => '',
                'Address Locality'             => '',
                'Address Administrative Area'  => '',
                'Address Country 2 Alpha Code' => $data['Prospect Contact Address country'],

            );
            unset($data['Prospect Contact Address country']);

            if (isset($data['Prospect Contact Address addressLine1'])) {
                $address_fields['Address Line 1'] = $data['Prospect Contact Address addressLine1'];
                unset($data['Prospect Contact Address addressLine1']);
            }
            if (isset($data['Prospect Contact Address addressLine2'])) {
                $address_fields['Address Line 2'] = $data['Prospect Contact Address addressLine2'];
                unset($data['Prospect Contact Address addressLine2']);
            }
            if (isset($data['Prospect Contact Address sortingCode'])) {
                $address_fields['Address Sorting Code'] = $data['Prospect Contact Address sortingCode'];
                unset($data['Prospect Contact Address sortingCode']);
            }
            if (isset($data['Prospect Contact Address postalCode'])) {
                $address_fields['Address Postal Code'] = $data['Prospect Contact Address postalCode'];
                unset($data['Prospect Contact Address postalCode']);
            }

            if (isset($data['Prospect Contact Address dependentLocality'])) {
                $address_fields['Address Dependent Locality'] = $data['Prospect Contact Address dependentLocality'];
                unset($data['Prospect Contact Address dependentLocality']);
            }

            if (isset($data['Prospect Contact Address locality'])) {
                $address_fields['Address Locality'] = $data['Prospect Contact Address locality'];
                unset($data['Prospect Contact Address locality']);
            }

            if (isset($data['Prospect Contact Address administrativeArea'])) {
                $address_fields['Address Administrative Area'] = $data['Prospect Contact Address administrativeArea'];
                unset($data['Prospect Contact Address administrativeArea']);
            }
        } else {
            $address_fields = false;
        }


        $prospect = new Prospect('new', $data, $address_fields);


        if ($prospect->id) {
            $this->new_prospect_msg = $prospect->msg;

            if ($prospect->new) {
                $this->new_prospect = true;
                $this->new_prospect_id=$prospect->id;
            } else {
                $this->error      = true;
                $this->msg        = $prospect->msg;
                $this->error_code = $prospect->error_code;
            }

            return $prospect;
        } else {
            $this->error      = true;
            $this->msg        = $prospect->msg;
            $this->error_code = $prospect->error_code;

            return false;
        }
    }


    function create_product($data)
    {
        $this->new_product = false;

        $data['editor'] = $this->editor;


        if (!isset($data['Product Code']) or $data['Product Code'] == '') {
            $this->error      = true;
            $this->msg        = _("Code missing");
            $this->error_code = 'product_code_missing';
            $this->metadata   = '';

            return false;
        }

        $sql  = "SELECT count(*) AS num ,`Product ID` FROM `Product Dimension` WHERE `Product Code`=%s AND `Product Store Key`=%d AND `Product Status`!='Discontinued' ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
                           $data['Product Code'],
                           $this->id
                       ));
        if ($row = $stmt->fetch()) {
            if ($row['num'] > 0) {
                $this->error      = true;
                $this->msg        = sprintf(_('Duplicated code (%s)'), $data['Product Code']);
                $this->error_code = 'duplicate_product_code_reference';
                $this->metadata   = $data['Product Code'];

                return get_object('Product', $row['Product ID']);
            }
        }


        if (!isset($data['Product Unit Label']) or $data['Product Unit Label'] == '') {
            $this->error      = true;
            $this->msg        = _('Unit label missing');
            $this->error_code = 'product_unit_label_missing';

            return false;
        }

        if (!isset($data['Product Name']) or $data['Product Name'] == '') {
            $this->error      = true;
            $this->msg        = _('Product name missing');
            $this->error_code = 'product_name_missing';

            return false;
        }


        if (!isset($data['Product Units Per Case']) or $data['Product Units Per Case'] == '') {
            $this->error      = true;
            $this->msg        = _('Units per outer missing');
            $this->error_code = 'product_units_per_case_missing';

            return false;
        }

        if (!is_numeric($data['Product Units Per Case']) or $data['Product Units Per Case'] <= 0) {
            $this->error      = true;
            $this->msg        = sprintf(_('Invalid units per outer (%s)'), $data['Product Units Per Case']);
            $this->error_code = 'invalid_product_units_per_case';
            $this->metadata   = $data['Product Units Per Case'];

            return false;
        }


        if (!isset($data['Product Price']) or $data['Product Price'] == '') {
            $this->error      = true;
            $this->msg        = _('Cost missing');
            $this->error_code = 'product_price_missing';

            return false;
        }

        if (!is_numeric($data['Product Price']) or $data['Product Price'] < 0) {
            $this->error      = true;
            $this->msg        = sprintf(_('Invalid cost (%s)'), $data['Product Price']);
            $this->error_code = 'invalid_product_price';
            $this->metadata   = $data['Product Price'];

            return false;
        }


        if (isset($data['Product Unit RRP']) and $data['Product Unit RRP'] != '') {
            if (!is_numeric($data['Product Unit RRP']) or $data['Product Unit RRP'] < 0) {
                $this->error      = true;
                $this->msg        = sprintf(
                    _('Invalid unit recommended RRP (%s)'),
                    $data['Product Unit RRP']
                );
                $this->error_code = 'invalid_product_unit_rrp';
                $this->metadata   = $data['Product Unit RRP'];

                return false;
            }
        }
        if ($data['Product Unit RRP'] != '') {
            $data['Product RRP'] = $data['Product Unit RRP'] * $data['Product Units Per Case'];
        } else {
            $data['Product RRP'] = '';
        }

        $data['Product Store Key'] = $this->id;


        $data['Product Currency']           = $this->data['Store Currency Code'];
        $data['Product Locale']             = $this->data['Store Locale'];
        $data['Product Pricing Policy Key'] = $this->data['Store Default Product Pricing Policy Key'];


        if (array_key_exists('Family Category Code', $data)) {
            include_once 'class.Category.php';
            $root_category       = new Category('id', $this->get('Store Family Category Key'), false, $this->db);
            $root_category->fork = $this->fork;
            if ($root_category->id) {
                $root_category->editor = $this->editor;
                $family                = $root_category->create_category(array('Category Code' => $data['Family Category Code']));
                if ($family->id) {
                    $data['Product Family Category Key'] = $family->id;
                }
            }
            unset($data['Family Category Code']);
        }

        if (isset($data['Product Family Category Key'])) {
            $family_key = $data['Product Family Category Key'];
            unset($data['Product Family Category Key']);
        } else {
            $family_key = false;
        }


        if (!empty($data['Parts'])) {
            include_once 'class.Part.php';
            $product_parts = array();
            foreach (preg_split('/,/', $data['Parts']) as $part_data) {
                $part_data = _trim($part_data);

                if (preg_match('/([0-9]*\.?[0-9]+)x\s+/', $part_data, $matches)) {
                    $ratio = floatval($matches[1]);


                    if ($ratio <= 0) {
                        $this->error      = true;
                        $this->msg        = sprintf(_('Invalid parts format, use: %s'), '<i>'._('number').'</i>>x '._('reference').', <i>'._('number').'</i>>x '._('reference').', ...');
                        $this->error_code = 'invalid_parts';
                        $this->metadata   = $data['Parts'];

                        return false;
                    }
                    $part_data = preg_replace('/([0-9]*\.?[0-9]+)x\s+/', '', $part_data);
                } else {
                    $this->error      = true;
                    $this->msg        = sprintf(_('Invalid parts format, use: %s'), '<i>'._('number').'</i>>x '._('reference').', <i>'._('number').'</i>>x '._('reference').', ...');
                    $this->error_code = 'invalid_parts';
                    $this->metadata   = $data['Parts'];

                    return false;
                }

                $part = new Part(
                    'reference', _trim(
                                   $part_data
                               )
                );

                $product_parts[] = array(
                    'Ratio'    => $ratio,
                    'Part SKU' => $part->id,
                    'Note'     => ''
                );
            }

            $data['Product Parts'] = json_encode($product_parts);
        }


        if (isset($data['Product Parts'])) {
            $product_parts = json_decode($data['Product Parts'], true);


            if ($product_parts and is_array($product_parts)) {
                foreach ($product_parts as $product_part) {
                    if (!is_array($product_part)

                    ) {
                        $this->error      = true;
                        $this->msg        = "Can't parse product parts";
                        $this->error_code = 'can_not_parse_product_parts_no_array';
                        $this->metadata   = '';


                        return false;
                    }

                    if (!isset($product_part['Part SKU'])


                    ) {
                        $this->error      = true;
                        $this->msg        = "Can't parse product parts, missing part sku";
                        $this->error_code = 'can_not_parse_product_parts_missing_part_sku';
                        $this->metadata   = '';


                        return false;
                    }

                    if (!array_key_exists('Ratio', $product_part)

                    ) {
                        $this->error      = true;
                        $this->msg        = "Can't parse product parts, missing ratio";
                        $this->error_code = 'can_not_parse_product_parts_missing_ratio';
                        $this->metadata   = '';


                        return false;
                    }

                    if (!array_key_exists('Note', $product_part)

                    ) {
                        $this->error      = true;
                        $this->msg        = "Can't parse product parts, missing note";
                        $this->error_code = 'can_not_parse_product_parts_missing_note';
                        $this->metadata   = '';


                        return false;
                    }

                    if (is_null($product_part['Note'])) {
                        $product_part['Note'] = '';
                    }

                    if (

                        !is_numeric($product_part['Part SKU'])) {
                        $this->error      = true;
                        $this->msg        = "Can't parse product parts";
                        $this->error_code = 'can_not_parse_product_parts_wrong_part_sku';
                        $this->metadata   = '';


                        return false;
                    }

                    if (

                        !is_string($product_part['Note'])) {
                        $this->error      = true;
                        $this->msg        = "Can't parse product parts";
                        $this->error_code = 'can_not_parse_product_parts_note_is_not_string';
                        $this->metadata   = '';


                        return false;
                    }


                    $part = get_object('Part', $product_part['Part SKU']);

                    if (!$part->id) {
                        $this->error      = true;
                        $this->msg        = 'Part not found';
                        $this->error_code = 'part_not_found';
                        $this->metadata   = $product_part['Part SKU'];


                        return false;
                    }


                    if (!is_numeric($product_part['Ratio']) or $product_part['Ratio'] < 0) {
                        $this->error      = true;
                        $this->msg        = sprintf(_('Invalid parts per product (%s)'), $product_part['Ratio']);
                        $this->error_code = 'invalid_parts_per_product';
                        $this->metadata   = $product_part['Ratio'];

                        return false;
                    }
                }
            } else {
                $this->error      = true;
                $this->msg        = "Can't parse product parts";
                $this->error_code = 'can_not_parse_product_parts';
                $this->metadata   = '';

                return false;
            }


            $product_parts_data = $data['Product Parts'];
            unset($data['Product Parts']);
        } else {
            $product_parts_data = false;
        }


        $product       = new Product('find', $data, 'create');
        $product->fork = $this->fork;

        if ($product->id) {
            $this->new_object_msg = $product->msg;

            if ($product->new) {
                $this->new_object  = true;
                $this->new_product = true;

                set_time_limit(90);

                if ($product_parts_data) {
                    $product->update_part_list($product_parts_data, 'no_history');
                }

                $product->update_status_from_parts();
                if ($family_key) {
                    $product->update(array('Product Family Category Key' => $family_key), 'no_history');
                }

                //get data from parts
                $parts = $product->get_parts('objects');
                if (count($parts) == 1) {
                    foreach ($parts as $part) {
                        $product->fast_update(array(
                                                  'Product Tariff Code'                  => $part->get('Part Tariff Code'),
                                                  'Product HTSUS Code'                   => $part->get('Part HTSUS Code'),
                                                  'Product Duty Rate'                    => $part->get('Part Duty Rate'),
                                                  'Product Origin Country Code'          => $part->get('Part Origin Country Code'),
                                                  'Product UN Number'                    => $part->get('Part UN Number'),
                                                  'Product UN Class'                     => $part->get('Part UN Class'),
                                                  'Product Packing Group'                => $part->get('Part Packing Group'),
                                                  'Product Proper Shipping Name'         => $part->get('Part Proper Shipping Name'),
                                                  'Product Hazard Identification Number' => $part->get('Part Hazard Identification Number'),
                                                  'Product Unit Weight'                  => $part->get('Part Unit Weight'),
                                                  'Product Unit Dimensions'              => $part->get('Part Unit Dimensions'),
                                                  'Product Materials'                    => $part->data['Part Materials'],
                                                  'Product Barcode Number'               => $part->data['Part Barcode Number'],
                                                  'Product Barcode Key'                  => $part->data['Part Barcode Key'],
                                                  'Product CPNP Number'                  => $part->data['Part CPNP Number'],
                                                  'Product UFI'                          => $part->data['Part UFI'],

                                              ));

                        $product->update_updated_markers('Data');

                        $sql = "SELECT `Image Subject Image Key` FROM `Image Subject Bridge` WHERE `Image Subject Object`='Part' AND `Image Subject Object Key`=? and `Image Subject Object Image Scope`='Marketing' ORDER BY `Image Subject Order` ";

                        $stmt_img = $this->db->prepare($sql);
                        $stmt_img->execute(array($part->id));
                        while ($row_img = $stmt_img->fetch()) {
                            $product->link_image($row_img['Image Subject Image Key'], 'Marketing');
                        }
                    }
                }


                foreach ($this->get_websites('objects') as $website) {
                    if ($product->data['is_variant'] == 'No') {
                        $website->create_product_webpage($product->id);
                    }
                }


                $product->update_web_state();


                require_once 'utils/new_fork.php';
                new_housekeeping_fork(
                    'au_housekeeping',
                    array(
                        'type'       => 'product_created',
                        'product_id' => $product->id,
                        'editor'     => $product->editor
                    ),
                    DNS_ACCOUNT_CODE,
                    $this->db
                );
            } else {
                $this->error = true;
                if ($product->found) {
                    $this->error_code     = 'duplicated_field';
                    $this->error_metadata = json_encode(array($product->duplicated_field));

                    if ($product->duplicated_field == 'Product Code') {
                        $this->msg = _("Duplicated product code");
                    } else {
                        $this->msg = 'Duplicated '.$product->duplicated_field;
                    }
                } else {
                    $this->msg = $product->msg;
                }
            }


            return $product;
        } else {
            $this->error = true;

            $this->msg = 'Error '.$product->msg;


            $this->error      = true;
            $this->error_code = 'cant create product'.$product->msg;
            $this->metadata   = '';

            return false;
        }
    }

    function get_websites($scope = 'keys'): array
    {
        $websites = array();

        if ($scope == 'data') {
            $fields = '`Website Key`,`Website Code`,`Website Name`,`Website Type`';
        } else {
            $fields = '`Website Key`';
        }

        $stmt = $this->db->prepare("SELECT $fields FROM `Website Dimension` WHERE `Website Store Key`=?");
        if ($stmt->execute(array(
                               $this->id
                           ))) {
            while ($row = $stmt->fetch()) {
                if ($scope == 'objects') {
                    $websites[$row['Website Key']] = get_object('Website', $row['Website Key']);
                } elseif ($scope == 'data') {
                    $websites[$row['Website Key']] = $row;
                } else {
                    $websites[$row['Website Key']] = $row['Website Key'];
                }
            }
        }


        return $websites;
    }

    function get_shipping_zones_schemas($type = 'Current', $scope = 'keys'): array
    {
        $shipping_zones_schemas = array();


        switch ($type) {
            case 'Current':
                $where = 'and `Shipping Zone Schema Type`="Current" and `Shipping Zone Schema Store State`="Active"  ';
                break;
            case 'Deal':
                $where = 'and `Shipping Zone Schema Type`="Deal" and `Shipping Zone Schema Store State`="Active"  ';
                break;
            case 'InReserve':
                $where = 'and `Shipping Zone Schema Type`="InReserve" and `Shipping Zone Schema Store State`="Active"  ';
                break;
            case 'Active':
                $where = 'and `Shipping Zone Schema Store State`="Active"  ';
                break;
            case 'Inactive':
                $where = 'and `Shipping Zone Schema Store State`="Inactive"  ';
                break;
            default:
                $where = '';
        }

        $sql = sprintf(
            "SELECT  `Shipping Zone Schema Key` FROM `Shipping Zone Schema Dimension` WHERE `Shipping Zone Schema Store Key`=%d  %s ",
            $this->id,
            $where
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($scope == 'objects') {
                    $shipping_zones_schemas[$row['Shipping Zone Schema Key']] = get_object('Shipping Zone Schema', $row['Shipping Zone Schema Key']);
                } else {
                    $shipping_zones_schemas[$row['Shipping Zone Schema Key']] = $row['Shipping Zone Schema Key'];
                }
            }
        }


        return $shipping_zones_schemas;
    }

    function update_new_products()
    {
        $new = 0;
        $sql = sprintf(
            "SELECT count(*) AS num FROM `Product Dimension` WHERE  `Product Status` IN ('Active','Discontinuing') AND  `Product Store Key` =%d AND `Product Valid From` >= CURDATE() - INTERVAL 14 DAY",
            $this->id

        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $new = $row['num'];
            }
        }


        $this->update(array('Store New Products' => $new), 'no_history');
    }

    function create_poll_query($data)
    {
        $this->new_poll_query = false;

        $data['editor']                            = $this->editor;
        $data['Customer Poll Query Store Key']     = $this->id;
        $data['Customer Poll Query Creation Date'] = gmdate('Y-m-d H:i:s');


        $poll_query = new Customer_Poll_Query('new', $data);


        if ($poll_query->id) {
            $this->new_poll_query_msg = $poll_query->msg;

            if ($poll_query->new) {
                $this->new_poll_query = true;

                $history_data = array(
                    'History Abstract' => sprintf(
                        _('Customer poll query (%s) created'),
                        '<span class="link" onclick="change_view(\'customers/'.$this->id.'/poll_query/'.$poll_query->id.'\')">'.$poll_query->get('Name').'</span>'
                    ),
                    'History Details'  => '',
                    'Action'           => 'created'
                );

                $this->add_subject_history(
                    $history_data,
                    true,
                    'No',
                    'Changes',
                    $this->get_object_name(),
                    $this->id
                );
            }

            return $poll_query;
        } else {
            $this->error = true;
            $this->msg   = $poll_query->msg;

            return false;
        }
    }

    function create_purge($data)
    {
        $data['editor']                       = $this->editor;
        $data['Order Basket Purge Store Key'] = $this->id;
        $purge                                = new Order_Basket_Purge('create', $data);
        if ($purge->id) {
            $this->new_object_msg = $purge->msg;
            if ($purge->new) {
                $this->new_object = true;
            } else {
                $this->error = true;
                $this->msg   = $purge->msg;
            }
            return $purge;
        } else {
            $this->error = true;
            $this->msg   = $purge->msg;
            return false;
        }
    }

    function create_picking_pipeline($data)
    {
        $data['editor'] = $this->editor;

        $data['Picking Pipeline Store Key'] = $this->id;
        include_once 'class.Picking_Pipeline.php';
        $picking_pipeline = new Picking_Pipeline('create', $data);

        if ($picking_pipeline->id) {
            $this->new_object_msg = $picking_pipeline->msg;
            if ($picking_pipeline->new) {
                $this->new_object = true;
                $this->fast_update_json_field('Store Properties', 'picking_pipeline', $picking_pipeline->id);
            } else {
                $this->error = true;
                $this->msg   = $picking_pipeline->msg;
            }

            return $picking_pipeline;
        } else {
            $this->error = true;
            $this->msg   = $picking_pipeline->msg;
            return false;
        }
    }

    function create_mailshot($data)
    {
        /**
         * @var $email_template_type EmailCampaignType
         */
        $email_template_type         = get_object('Email_Template_Type', $data['Email Campaign Type'].'|'.$this->id, 'code_store');
        $email_template_type->editor = $this->editor;

        if ($email_template_type->id) {
            $mailshot = $email_template_type->create_mailshot();

            if (is_object($mailshot) and $mailshot->id) {
                return $mailshot;
            } else {
                $this->error = true;
                $this->msg   = $email_template_type->msg;
            }
        } else {
            $this->error = true;
            $this->msg   = 'invalid email campaign type';
        }

        return false;
    }

    function create_category($raw_data)
    {
        if (!isset($raw_data['Category Label']) or $raw_data['Category Label'] == '') {
            $raw_data['Category Label'] = $raw_data['Category Code'];
        }

        $data = array(
            'Category Code'           => $raw_data['Category Code'],
            'Category Label'          => $raw_data['Category Label'],
            'Category Scope'          => 'Product',
            'Category Subject'        => $raw_data['Category Subject'],
            'Category Store Key'      => $this->id,
            'Category Can Have Other' => 'No',
            'Category Locked'         => 'Yes',
            'Category Branch Type'    => 'Root',
            'editor'                  => $this->editor

        );

        $category = new Category('find create', $data);


        if ($category->id) {
            $this->new_category_msg = $category->msg;

            if ($category->new) {
                $this->new_category = true;
            } else {
                $this->error = true;
                $this->msg   = $category->msg;
            }

            return $category;
        } else {
            $this->error = true;
            $this->msg   = $category->msg;
        }

        return false;
    }

    function create_website($data)
    {
        $this->new_object = false;

        $data['editor'] = $this->editor;


        $data['Website Store Key'] = $this->id;
        $data['Website Locale']    = $this->get('Store Locale');


        if (!isset($data['Website Theme'])) {
            $data['Website Theme'] = 'theme_1';
        }


        $data['Website From'] = gmdate('Y-m-d H:i:s');


        $data['Website Primary Color']   = '#b22222';
        $data['Website Secondary Color'] = '#ffa500';
        $data['Website Accent Color']    = '#ff6600';
        $data['Website Text Background'] = '#333';


        switch ($this->get('Store Type')) {
            case 'B2BC':
            case 'B2B':
                $data['Website Type'] = 'EcomB2B';
                break;
            case 'Dropshipping':
                $data['Website Type'] = 'EcomDS';
                break;
            default:
                $data['Website Type'] = 'Ecom';
        }


        $website = new Website('find', $data, 'create');

        if ($website->id) {
            $this->new_object_msg = $website->msg;

            if ($website->new) {
                $this->new_object = true;


                $this->fast_update(array(
                                       'Store Website Key' => $website->id
                                   ));


                new_housekeeping_fork(
                    'au_housekeeping',
                    array(
                        'type'        => 'website_created',
                        'website_key' => $website->id,

                        'editor' => $this->editor
                    ),
                    DNS_ACCOUNT_CODE
                );
            } else {
                $this->error = true;
                if ($website->found) {
                    $this->error_code     = 'duplicated_field';
                    $this->error_metadata = json_encode(array($website->duplicated_field));

                    if ($website->duplicated_field == 'Website Code') {
                        $this->msg = _('Duplicated website code');
                    }
                    if ($website->duplicated_field == 'Website URL') {
                        $this->msg = _('Duplicated website URL');
                    } else {
                        $this->msg = _('Duplicated website name');
                    }
                } else {
                    $this->msg = $website->msg;
                }
            }

            return $website;
        } else {
            $this->error = true;
            $this->msg   = $website->msg;

            return false;
        }
    }

    function update_websites_data()
    {
        $number_sites = 0;
        $sql          = sprintf(
            "SELECT count(*) AS number_sites FROM `Website Dimension` WHERE `Website Store Key`=%d ",
            $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_sites = $row['number_sites'];
            }
        }

        $this->update(array('Store Websites' => $number_sites), 'no_history');
    }

    function update_field_switcher($field, $value, $options = '', $metadata = '')
    {
        switch ($field) {
            case 'Store Notification New Order Recipients':
            case 'Store Notification New Customer Recipients':
            case 'Store Notification Invoice Deleted Recipients':
            case 'Store Notification Delivery Note Undispatched Recipients':
            case 'Store Notification Delivery Note Dispatched Recipients':
            case 'Store BCC Delivery Note Dispatched Recipients':

                $this->update_notifications($field, $value, 'set');

                break;

            case 'data entry picking aid':
            case 'data entry picking aid state after save':
            case 'data entry picking aid default picker':
            case 'data entry picking aid default packer':
            case 'data entry picking aid default shipper':
            case 'data entry picking aid default number boxes':
            case 'invoice show rrp':
            case 'invoice show parts':
            case 'invoice show tariff codes':
            case 'invoice show barcode':
            case 'invoice show weight':
            case 'invoice show origin':
            case 'invoice show CPNP':
            case 'send invoice attachment in delivery confirmation':
            case 'send dn attachment in delivery confirmation':

                $this->fast_update_json_field('Store Settings', preg_replace('/\s/', '_', $field), $value);

                break;
            case 'Store Can Collect':

                $this->update_field($field, $value, $options);


                $this->other_fields_updated = array(
                    'Store_Collect_Address' => array(
                        'field'  => 'Store_Collect_Address',
                        'render' => $this->get('Store Can Collect') == 'Yes',


                    )
                );

                break;

            case 'Store Collect Address':


                $this->update_address('Collect', json_decode($value, true));

                $sql = sprintf("SELECT `Order Key` FROM `Order Dimension` WHERE  `Order State`='InBasket'  AND `Order For Collection`='Yes'  AND `Order Customer Key`=%d ", $this->id);
                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $order = get_object('Order', $row['Order Key']);
                        $order->update(array('Order Delivery Address' => $value), $options, array('no_propagate_customer' => true));
                    }
                }


                break;


            case('Store Google Map URL'):


                $doc = new DOMDocument();
                @$doc->loadHTML($value);

                $tags = $doc->getElementsByTagName('iframe');


                if ($tags->length == 1) {
                    foreach ($tags as $tag) {
                        $value = $tag->getAttribute('src');
                        break;
                    }
                }


                $this->update_field('Store Google Map URL', $value);
                break;

            case('Store Sticky Note'):
                $this->update_field_switcher('Sticky Note', $value);
                break;
            case('Sticky Note'):
                $this->update_field('Store '.$field, $value, 'no_null');
                $this->new_value = html_entity_decode($this->new_value);
                break;

            case('Store Code'):
                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _("Value can't be empty");
                }
                if (strlen($value) > 4) {
                    $this->error = true;
                    $this->msg   = _("The max length of the code is 4 characters");
                }
                $this->update_field($field, $value, $options);

                break;
            case('Store Name'):

                if ($value == '') {
                    $this->error = true;
                    $this->msg   = _("Value can't be empty");
                }
                $this->update_field($field, $value, $options);
                break;

            case 'Store Next Invoice Public ID Method':

                $this->update_field($field, $value);


                if ($value == 'Order ID') {
                    $this->update_field('Store Refund Public ID Method', 'Same Invoice ID');
                }


                $this->other_fields_updated = array(
                    'Store_Invoice_Public_ID_Format'       => array(
                        'field'  => 'Store_Invoice_Public_ID_Format',
                        'render' => $this->get('Store Next Invoice Public ID Method') == 'Invoice Public ID',

                    ),
                    'Store_Invoice_Last_Invoice_Public_ID' => array(
                        'field'  => 'Store_Invoice_Last_Invoice_Public_ID',
                        'render' => $this->get('Store Next Invoice Public ID Method') == 'Invoice Public ID',
                    ),


                    'Store_Refund_Public_ID_Format'       => array(
                        'field'  => 'Store_Refund_Public_ID_Format',
                        'render' => !($this->get('Store Next Invoice Public ID Method') == 'Same Invoice ID'),

                    ),
                    'Store_Invoice_Last_Refund_Public_ID' => array(
                        'field'  => 'Store_Invoice_Last_Refund_Public_ID',
                        'render' => !($this->get('Store Next Invoice Public ID Method') == 'Same Invoice ID'),
                    ),

                );


                break;

            case 'Store Refund Public ID Method':

                $this->update_field($field, $value);


                $this->other_fields_updated = array(


                    'Store_Refund_Public_ID_Format'       => array(
                        'field'  => 'Store_Refund_Public_ID_Format',
                        'render' => !(($this->get('Store Next Invoice Public ID Method') == 'Same Invoice ID' or $this->get('Store Refund Public ID Method') != 'Store Own Index')),

                    ),
                    'Store_Invoice_Last_Refund_Public_ID' => array(
                        'field'  => 'Store_Invoice_Last_Refund_Public_ID',
                        'render' => !(($this->get('Store Next Invoice Public ID Method') == 'Same Invoice ID' or $this->get('Store Refund Public ID Method') != 'Store Own Index')),
                    ),

                );


                break;
            case 'Store Timezone':
                $value = preg_replace('/_/', '/', $value);
                $this->update_field($field, $value);

                break;

            default:
                $base_data = $this->base_data();
                if (array_key_exists($field, $base_data)) {
                    if ($value != $this->data[$field]) {
                        $this->update_field($field, $value, $options);
                    }
                } elseif (array_key_exists($field, $this->base_data('Store Data'))) {
                    $this->update_table_field($field, $value, $options, 'Store', 'Store Data', $this->id);
                } elseif (array_key_exists(
                    $field,
                    $this->base_data('Store DC Data')
                )) {
                    $this->update_table_field(
                        $field,
                        $value,
                        $options,
                        'Store',
                        'Store DC Data',
                        $this->id
                    );
                }
        }
    }

    function update_notifications($field, $values, $operation)
    {
        $values    = json_decode($values, true);
        $old_value = $this->get($field);

        if ($operation == 'set') {
            $external_emails = array();
            $user_keys       = array();
            foreach ($values['external_emails'] as $external_email) {
                if (filter_var($external_email, FILTER_VALIDATE_EMAIL)) {
                    $external_emails[$external_email] = $external_email;
                } else {
                    $this->error = true;
                    $this->msg   = _('Invalid email');

                    return;
                }
            }

            foreach ($values['user_keys'] as $user_key) {
                $_user = get_object('User', $user_key);
                if ($_user->id and in_array($_user->get('User Type'), array(
                        'Staff',
                        'Contractor'
                    ))) {
                    $user_keys[$user_key] = $user_key;
                } else {
                    $this->error = true;
                    $this->msg   = _('Invalid user');

                    return;
                }
            }


            $recipients_data = array(
                'external_emails' => array_values($external_emails),
                'user_keys'       => array_values($user_keys),
            );
        } else {
            $recipients_data = $old_value;
        }


        $this->fast_update_json_field('Store Settings', preg_replace('/\s/', '_', $field), json_encode($recipients_data));


        if (preg_match('/Store Notification (.+) Recipients/', $field, $matches)) {
            /**
             * @var $email_template_type EmailCampaignType
             */
            $email_template_type = get_object('Email_Template_Type', $matches[1].'|'.$this->id, 'code_store');
            $email_template_type->update_number_subscribers();
        }
        //  print_r($recipients_data);

    }

    function update_address($type, $fields, $options = '')
    {
        $old_value = $this->get("$type Address");
        //$old_checksum = $this->get("$type Address Checksum");


        $updated_fields_number = 0;

        if (preg_match('/gb|im|jy|gg/i', $fields['Address Country 2 Alpha Code'])) {
            include_once 'utils/geography_functions.php';
            $fields['Address Postal Code'] = gbr_pretty_format_post_code($fields['Address Postal Code']);
        }

        foreach ($fields as $field => $value) {
            $this->update_field(
                $this->table_name.' '.$type.' '.$field,
                $value,
                'no_history'
            );
            if ($this->updated) {
                $updated_fields_number++;
            }
        }


        if ($updated_fields_number > 0) {
            $this->updated = true;
        }


        if ($this->updated) {
            $this->update_address_formatted_fields($type);


            if (!preg_match('/no_history/i', $options)) {
                $this->add_changelog_record(
                    $this->table_name." $type Address",
                    $old_value,
                    $this->get("$type Address"),
                    '',
                    $this->table_name,
                    $this->id
                );
            }
        }
    }

    function update_address_formatted_fields($type)
    {
        include_once 'utils/get_addressing.php';


        $new_checksum = md5(
            json_encode(array(
                            'Address Recipient'            => $this->get($type.' Address Recipient'),
                            'Address Organization'         => $this->get($type.' Address Organization'),
                            'Address Line 1'               => $this->get($type.' Address Line 1'),
                            'Address Line 2'               => $this->get($type.' Address Line 2'),
                            'Address Sorting Code'         => $this->get($type.' Address Sorting Code'),
                            'Address Postal Code'          => $this->get($type.' Address Postal Code'),
                            'Address Dependent Locality'   => $this->get($type.' Address Dependent Locality'),
                            'Address Locality'             => $this->get($type.' Address Locality'),
                            'Address Administrative Area'  => $this->get($type.' Address Administrative Area'),
                            'Address Country 2 Alpha Code' => $this->get($type.' Address Country 2 Alpha Code'),
                        ))
        );


        $this->update_field(
            $this->table_name.' '.$type.' Address Checksum',
            $new_checksum,
            'no_history'
        );


        $country = $this->get('Store Home Country Code 2 Alpha');
        $locale  = $this->get('Store Locale');

        list($address, $formatter, $postal_label_formatter) = get_address_formatter($country, $locale);


        $address = $address->withFamilyName($this->get($type.' Address Recipient'))->withOrganization($this->get($type.' Address Organization'))->withAddressLine1($this->get($type.' Address Line 1'))->withAddressLine2(
            $this->get($type.' Address Line 2')
        )->withSortingCode(
            $this->get($type.' Address Sorting Code')
        )->withPostalCode($this->get($type.' Address Postal Code'))->withDependentLocality($this->get($type.' Address Dependent Locality'))->withLocality($this->get($type.' Address Locality'))->withAdministrativeArea(
            $this->get($type.' Address Administrative Area')
        )->withCountryCode($this->get($type.' Address Country 2 Alpha Code'));


        $xhtml_address = $formatter->format($address);


        if ($this->get($type.' Address Recipient') == $this->get('Main Contact Name')) {
            $xhtml_address = preg_replace('/(class="recipient">.+<\/span>)<br>/', '$1', $xhtml_address);
        }

        if ($this->get($type.' Address Organization') == $this->get('Company Name')) {
            $xhtml_address = preg_replace('/(class="organization">.+<\/span>)<br>/', '$1', $xhtml_address);
        }

        $xhtml_address = preg_replace(
            '/class="recipient"/',
            'class="recipient fn '.($this->get($type.' Address Recipient') == $this->get('Main Contact Name') ? 'hide' : '').'"',
            $xhtml_address
        );


        $xhtml_address = preg_replace('/class="organization"/', 'class="organization org '.($this->get($type.' Address Organization') == $this->get('Company Name') ? 'hide' : '').'"', $xhtml_address);
        $xhtml_address = preg_replace('/class="address-line1"/', 'class="address-line1 street-address"', $xhtml_address);
        $xhtml_address = preg_replace('/class="address-line2"/', 'class="address-line2 extended-address"', $xhtml_address);
        $xhtml_address = preg_replace('/class="sort-code"/', 'class="sort-code postal-code"', $xhtml_address);
        $xhtml_address = preg_replace('/class="country"/', 'class="country country-name"', $xhtml_address);


        $xhtml_address = preg_replace('/(class="address-line1 street-address"><\/span>)<br>/', '$1', $xhtml_address);


        //print $xhtml_address;
        $this->update_field($this->table_name.' '.$type.' Address Formatted', $xhtml_address, 'no_history');
        $this->update_field($this->table_name.' '.$type.' Address Postal Label', $postal_label_formatter->format($address), 'no_history');
    }

    function update_purges_data()
    {
        // todo: make stats for purges
    }

    function cache_object($redis, $account_code)
    {
        $redis_key     = 'Au_Cached_obj'.$account_code.'.Store.'.$this->id;
        $data_to_cache = json_encode([
                                         'code'       => $this->data['Store Code'],
                                         'name'       => $this->data['Store Name'],
                                         'state'      => $this->data['Store Status'],
                                         'type'       => $this->data['Store Type'],
                                         'locale'     => $this->data['Store Locale'],
                                         'timezone'   => $this->data['Store Timezone'],
                                         'currency'   => $this->data['Store Currency Code'],
                                         'properties' => $this->properties,
                                         'settings'   => $this->settings
                                     ]);
        $redis->set($redis_key, $data_to_cache);

        return $data_to_cache;
    }

    function update_sitting_time_in_warehouse()
    {
        $sql = "SELECT count(*) as num  ,avg(TIMESTAMPDIFF(SECOND,`Delivery Note Date Created`,NOW()) )as diff   FROM `Delivery Note Dimension` WHERE `Delivery Note State`  not in ('Dispatched','Cancelled','Cancelled to Restock')  and `Delivery Note Store Key`=? ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
                           $this->id
                       ));
        while ($row = $stmt->fetch()) {
            $this->fast_update_json_field('Store Properties', 'sitting_time_samples', $row['num']);
            $this->fast_update_json_field('Store Properties', 'sitting_time_avg', $row['diff']);
        }
    }

    function update_dispatching_time_data($interval)
    {
        include_once 'utils/date_functions.php';

        $interval_data = calculate_interval_dates($this->db, $interval);


        $sql = "select  count(*) as num  ,avg(TIMESTAMPDIFF(SECOND,`Order Submitted by Customer Date`,`Delivery Note Date Dispatched`) )as diff from   `Delivery Note Dimension` left join `Order Dimension` on (`Delivery Note Order Key`=`Order Key`) where `Delivery Note State`='Dispatched' and `Delivery Note Type`='Order' and `Delivery Note Date Dispatched`>=? and `Delivery Note Store Key`=?  ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
                           $interval_data[1],
                           $this->id
                       ));
        while ($row = $stmt->fetch()) {
            $this->fast_update_json_field('Store Properties', 'dispatch_time_samples_'.strtolower(preg_replace('/\s/', '_', $interval_data[0])), $row['num']);
            $this->fast_update_json_field('Store Properties', 'dispatch_time_avg_'.strtolower(preg_replace('/\s/', '_', $interval_data[0])), $row['diff']);
        }

        $sql = "select count(*) as num ,floor(TIMESTAMPDIFF(SECOND,`Order Submitted by Customer Date`,`Delivery Note Date Dispatched`)/3600/24)  days from   `Delivery Note Dimension` left join `Order Dimension` on (`Delivery Note Order Key`=`Order Key`) where `Delivery Note State`='Dispatched' 
                       and `Delivery Note Type`='Order' and `Delivery Note Date Dispatched`>=?  and `Delivery Note Store Key`=?  group by floor(TIMESTAMPDIFF(SECOND,`Order Submitted by Customer Date`,`Delivery Note Date Dispatched`)/3600/24) ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array(
                           $interval_data[1],
                           $this->id
                       ));
        $waiting_time_histogram = array();
        while ($row = $stmt->fetch()) {
            $waiting_time_histogram[$row['days']] = $row['num'];
        }
        $this->fast_update_json_field('Store Properties', 'dispatch_time_histogram_'.strtolower(preg_replace('/\s/', '_', $interval_data[0])), json_encode($waiting_time_histogram));
    }

    function get_order_sources()
    {
        $sources = [];
        $sql     = "select * from `Order Source Dimension` where  `Order Source Type` !='website' and  `Order Source Store Key` is null or `Order Source Store Key`=? ";
        $stmt    = $this->db->prepare($sql);
        $stmt->execute(
            [
                $this->id
            ]
        );
        while ($row = $stmt->fetch()) {
            $sources[$row['Order Source Key']] = $row;
        }

        return $sources;
    }

    function get_aiku_params($field, $value = '')
    {
        $url = AIKU_URL.'stores/'.$this->id;


        switch ($field) {
            case 'Object':
                $url                 = AIKU_URL.'stores/';
                $params              = $this->get_aiku_params('Store Status')[1];
                $params['legacy_id'] = $this->id;
                $params['name']      = $this->data['Store Name'];
                $params['code']      = $this->data['Store Code'];

                $data = [
                    'url'      => $this->data['Store URL'],
                    'email'    => $this->data['Store Email'],
                    'currency' => $this->data['Store Currency Code'],
                    'locale'   => $this->data['Store Locale'],
                    'timezone' => $this->data['Store Timezone']
                ];

                $settings = [
                    'can_collect' => $this->data['Store Can Collect'] == 'Yes',

                ];


                $params['data'] = json_encode(
                    array_filter(
                        $data
                    )
                );

                $params['settings'] = json_encode(
                    array_filter(
                        $settings
                    )
                );

                break;
            case 'Store Status':

                $legacy_status_to_state = [
                    'InProcess'   => 'creating',
                    'Normal'      => 'live',
                    'ClosingDown' => 'closed',
                    'Closed'      => 'closed'
                ];

                $params['state'] = $legacy_status_to_state[$this->data['Store Status']];


                break;
            case 'Store Name':
                $params['name'] = $value;
                break;
            case 'Store Code':
                $params['code'] = $value;
                break;
            case 'Store URL':
                $params['data'] = json_encode(['url' => $value]);
                break;
            case 'Store Email':
                $params['data'] = json_encode(['email' => $value]);
                break;
            case 'Store Currency Code':
                $params['data'] = json_encode(['currency' => $value]);
                break;
            case 'Store Locale':
                $params['data'] = json_encode(['locale' => $value]);
                break;
            case 'Store Timezone':
                $params['data'] = json_encode(['timezone' => $value]);
                break;
            case 'Store Can Collect':
                $params['settings'] = json_encode(['can_collect' => $value == 'Yes']);
                break;
            default:
                return [
                    false,
                    false
                ];
        }


        return [
            $url,
            $params
        ];
    }

    function set_policy($data)
    {
        $policy = $data['policy'];
        if ($policy == 'pricing') {
            $sql = "update `Product Dimension` set `Product Pricing Policy Key`=? where `Product Store Key`=?  and `Product Status`='Discontinued'   ";
            $this->db->prepare($sql)->execute(
                [
                    $this->id,
                    $this->data['Store Default Product Pricing Policy Key'] ?: null
                ]
            );

            include_once 'utils/new_fork.php';

            new_housekeeping_fork(
                'au_housekeeping',
                array(
                    'type'      => 'set_store_pricing_policy',
                    'store_key' => $this->id,
                    'editor'    => $this->editor

                ),
                DNS_ACCOUNT_CODE
            );
        }
    }

}


