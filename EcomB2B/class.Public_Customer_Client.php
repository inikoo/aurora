<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30-09-2019 15:17:40 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
*/

use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;

include_once 'class.DBW_Table.php';
include_once 'trait.Address.php';
include_once 'trait.CustomerClientAiku.php';

class Public_Customer_Client extends DBW_Table {
    use Address, CustomerClientAiku;

    function __construct($arg1 = false, $arg2 = false, $arg3 = false) {

        global $db;
        $this->db = $db;
        $this->id = false;


        $this->table_name = 'Customer Client';

        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }

        if ($arg1 == 'new') {
            $this->create($arg2, $arg3);

            return;
        }

        $this->get_data($arg1, $arg2);


    }


    function get_data($key, $id) {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Customer Client Dimension` WHERE `Customer Client Key`=%d", $id
            );

        } else {

            return;
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id       = $this->data['Customer Client Key'];
            $this->metadata = json_decode($this->data['Customer Client Metadata'], true);

        }


    }

    function create($raw_data, $address_raw_data) {


        $sql  = "select `Customer Client Key` from `Customer Client Dimension` where `Customer Client Code`=? and `Customer Client Customer Key`=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $raw_data['Customer Client Code'],
                $raw_data['Customer Client Customer Key']
            )
        );
        if ($row = $stmt->fetch()) {
            $this->error = true;
            $this->msg   = _('Other customer has same unique reference');

            return;
        }

        $this->data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $this->data)) {
                $this->data[$key] = _trim($value);
            }
        }
        $this->editor = $raw_data['editor'];

        $this->data['Customer Client Creation Date'] = gmdate('Y-m-d H:i:s');
        $this->data['Customer Client Metadata']      = '{}';


        $sql = sprintf(
            "INSERT INTO `Customer Client Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($this->data)).'`', join(',', array_fill(0, count($this->data), '?'))
        );

        $stmt = $this->db->prepare($sql);

        $i = 1;
        foreach ($this->data as $key => $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {


            $this->id = $this->db->lastInsertId();
            if (!$this->id) {
                throw new Exception('Error inserting '.$this->table_name);
            }
            $this->get_data('id', $this->id);


            if ($this->data['Customer Client Company Name'] != '') {
                $customer_name = $this->data['Customer Client Company Name'];
            } else {
                $customer_name = $this->data['Customer Client Main Contact Name'];
            }
            $this->fast_update(
                array(
                    'Customer Client Name' => $customer_name
                )
            );


            $this->update_address('Contact', $address_raw_data, 'no_history');


            $this->fast_update(
                array(
                    'Customer Client Main Plain Mobile'    => $this->get('Customer Client Main Plain Mobile'),
                    'Customer Client Main Plain Telephone' => $this->get('Customer Client Main Plain Telephone'),
                )

            );


            $history_data = array(
                'History Abstract' => sprintf(_("Customer's client created (%s)"), $this->get('Name')),
                'History Details'  => '',
                'Action'           => 'created',
                'Subject'          => 'Customer Client',
                'Subject Key'      => $this->id,
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );

            $this->new = true;
            $this->model_updated('new',$this->id);

        } else {
            $this->error = true;
            $this->msg   = 'Unknown error';
        }


    }

    function get($key, $arg1 = false) {

        if (!$this->id) {
            return '';
        }


        switch ($key) {
            case 'Formatted Client Code':
                return ($this->data['Customer Client Code'] == '' ? '<span class="italic">'.sprintf('%05d', $this->id).'</span>' : $this->data['Customer Client Code']);

            case 'Phone':

                $phone = $this->data['Customer Client Main XHTML Mobile'];

                if ($this->data['Customer Client Preferred Contact Number'] == 'Telephone' and $this->data['Customer Client Preferred Contact Number'] != '') {
                    $phone = $this->data['Customer Client Main XHTML Telephone'];
                }

                return $phone;
            case 'Name Truncated':
                return (strlen($this->get('Customer Client Name')) > 50 ? substrwords($this->get('Customer Client Name'), 55) : $this->get('Customer Client Name'));

            case('Creation Date'):
                if ($this->data['Customer Client '.$key] == '') {
                    return '';
                }

                return '<span title="'.strftime(
                        "%a %e %b %Y %H:%M:%S %Z", strtotime($this->data['Customer Client '.$key]." +00:00")
                    ).'">'.strftime(
                        "%a %e %b %Y", strtotime($this->data['Customer Client '.$key]." +00:00")
                    ).'</span>';

            case 'Customer Client Contact Address':


                $address_fields = array(

                    'Address Recipient'            => $this->get('Customer Client Contact Address Recipient'),
                    'Address Organization'         => $this->get('Customer Client Contact Address Organization'),
                    'Address Line 1'               => $this->get('Customer Client Contact Address Line 1'),
                    'Address Line 2'               => $this->get('Customer Client Contact Address Line 2'),
                    'Address Sorting Code'         => $this->get('Customer Client Contact Address Sorting Code'),
                    'Address Postal Code'          => $this->get('Customer Client Contact Address Postal Code'),
                    'Address Dependent Locality'   => $this->get('Customer Client Contact Address Dependent Locality'),
                    'Address Locality'             => $this->get('Customer Client Contact Address Locality'),
                    'Address Administrative Area'  => $this->get('Customer Client Contact Address Administrative Area'),
                    'Address Country 2 Alpha Code' => $this->get('Customer Client Contact Address Country 2 Alpha Code'),


                );

                return json_encode($address_fields);


            default:

                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Customer Client '.$key, $this->data)) {
                    return $this->data[$this->table_name.' '.$key];
                }

                return '';

        }

    }

    function metadata($key) {
        return (isset($this->metadata[$key]) ? $this->metadata[$key] : '');
    }

    function get_order_in_process_key() {

        $sql  = "SELECT `Order Key` FROM `Order Dimension` WHERE `Order Customer Client Key`=? AND `Order State`='InBasket' ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        if ($row = $stmt->fetch()) {
            $order_key = $row['Order Key'];
        } else {
            $order_key = false;
        }

        return $order_key;
    }


    function update_field_switcher($field, $value, $options = '', $metadata = array()) {

        if (is_string($value)) {
            $value = _trim($value);
        }


        switch ($field) {
            case 'Customer Client Contact Address':
                $this->update_address('Contact', json_decode($value, true), $options);
                break;
            case 'Customer Client Location':
            case 'Customer Client Code':

            case 'Customer Client Main Plain Email':

                $this->update_field($field, $value, $options);

                break;


            case 'Customer Client Main Plain Mobile':
            case 'Customer Client Main Plain Telephone':
                $value = preg_replace('/\s/', '', $value);
                if ($value == '+') {
                    $value = '';
                }
                if ($value != '') {

                    include_once 'utils/get_phoneUtil.php';
                    $phoneUtil = get_phoneUtil();
                    try {
                        if ($this->data['Customer Client Contact Address Country 2 Alpha Code'] == '' or $this->data['Customer Client Contact Address Country 2 Alpha Code'] == 'XX') {

                            if ($this->get('Store Key')) {
                                $store   = get_object('Store', $this->data['Customer Store Key']);
                                $country = $store->get('Home Country Code 2 Alpha');
                            } else {
                                $account = get_object('Account', 1);
                                $country = $account->get('Account Country 2 Alpha Code');
                            }

                        } else {
                            $country = $this->data['Customer Client Contact Address Country 2 Alpha Code'];
                        }
                        $proto_number    = $phoneUtil->parse($value, $country);
                        $formatted_value = $phoneUtil->format($proto_number, PhoneNumberFormat::INTERNATIONAL);

                        $value = $phoneUtil->format($proto_number, PhoneNumberFormat::E164);


                    } catch (NumberParseException $e) {
                        $this->error     = true;
                        $this->msg       = 'Error 1234';
                        $formatted_value = '';
                    }

                } else {
                    $formatted_value = '';
                }


                $this->update_field($field, $value, 'no_history');
                $this->update_field(preg_replace('/Plain/', 'XHTML', $field), $formatted_value, 'no_history');


                $this->update_field_switcher('Customer Client Preferred Contact Number', '');


                $this->fork_index_elastic_search();


                return true;
            case 'Customer Client Preferred Contact Number':


                if ($value == '') {
                    $value = $this->data['Customer Client Preferred Contact Number'];

                    if ($value == '') {
                        $value = 'Mobile';
                    }

                    if ($this->data['Customer Client Main Plain Mobile'] == '' and $this->data['Customer Client Main Plain Telephone'] != '') {
                        $value = 'Telephone';
                    } elseif ($this->data['Customer Client Main Plain Mobile'] != '' and $this->data['Customer Client Main Plain Telephone'] == '') {
                        $value = 'Mobile';
                    } elseif ($this->data['Customer Client Main Plain Mobile'] == '' and $this->data['Customer Client Main Plain Telephone'] == '') {
                        $value = 'Mobile';
                    }

                }


                $this->update_field($field, $value, $options);
                $this->update_field('Customer Client Preferred Contact Number Formatted Number', $this->get('Customer Client Main XHTML '.$value), $options);

                $this->fork_index_elastic_search();
                break;

            case 'Customer Client Company Name':

                $old_value = $this->get('Company Name');

                if ($value == '' and $this->data[$this->table_name.' Main Contact Name'] == '') {
                    $this->msg   = _("Company name can't be empty if the contact name is empty as well");
                    $this->error = true;

                    return true;
                }

                $this->update_field($field, $value, $options);
                if ($value == '') {

                    $this->fast_update(
                        ['Customer Client Name' => $this->data['Customer Client Main Contact Name']]
                    );

                } else {
                    $this->fast_update(
                        ['Customer Client Name' => $value]
                    );
                }

                if ($this->get('Contact Address Country 2 Alpha Code') != '' and $old_value == $this->get('Contact Address Organization')) {
                    $this->update_field(
                        $this->table_name.' Contact Address Organization', $value, 'no_history'
                    );
                    $this->update_address_formatted_fields('Contact');


                }


                $sql = sprintf("SELECT `Order Key` FROM `Order Dimension` WHERE  `Order State` ='InBasket'  AND `Order Customer Client Key`=%d ", $this->id);

                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $order         = get_object('Order', $row['Order Key']);
                        $order->editor = $this->editor;


                        $_value = $this->get('Customer Client Contact Address');


                        $order->update(array('Order Delivery Address' => $_value), 'no_history', array('no_propagate_customer' => true));

                    }
                }


                $this->update_metadata = array(
                    'class_html' => array(
                        'Name_Truncated'         => $this->get('Name Truncated'),
                        'Subject_Name'           => $this->get('Name'),
                        'Company_Name_Formatted' => $this->get('Company Name Formatted')


                    )
                );


                $this->fork_index_elastic_search();

                return true;

            case 'Customer Client Main Contact Name':


                $old_value = $this->get('Main Contact Name');

                if ($value == '' and $this->data[$this->table_name.' Company Name'] == '') {
                    $this->msg   = _("Contact name can't be empty if the company name is empty");
                    $this->error = true;

                    return;
                }

                $this->update_field($field, $value, $options);
                if ($this->data[$this->table_name.' Company Name'] == '') {
                    $this->update_field($this->table_name.' Name', $value, 'no_history');

                }


                if ($this->get('Contact Address Country 2 Alpha Code') != '' and $old_value == $this->get('Contact Address Recipient')) {
                    $this->update_field($this->table_name.' Contact Address Recipient', $value, 'no_history');
                    $this->update_address_formatted_fields('Contact');

                }


                $sql = sprintf("SELECT `Order Key` FROM `Order Dimension` WHERE  `Order State` = 'InBasket'   AND `Order Customer Client Key`=%d ", $this->id);
                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {
                        $order         = get_object('Order', $row['Order Key']);
                        $order->editor = $this->editor;;


                        $order->update(array('Order Delivery Address' => $this->get('Customer Client Contact Address')), 'no_history', array('no_propagate_customer' => true));

                    }
                }


                $this->update_metadata = array(
                    'class_html' => array(
                        'Name_Truncated'              => $this->get('Name Truncated'),
                        'Subject_Name'                => $this->get('Name'),
                        'Main_Contact_Name_Formatted' => $this->get('Main Contact Name Formatted')

                    )

                );


                $this->other_fields_updated = array(
                    $this->table_name.'_Name' => array(
                        'field'           => $this->table_name.'_Name',
                        'render'          => true,
                        'value'           => $this->get($this->table_name.' Name'),
                        'formatted_value' => $this->get('Name'),


                    )
                );


                $this->fork_index_elastic_search();

                return true;


            default:


        }
    }


    function get_telephone() {
        $telephone = $this->data['Customer Client Main Plain Mobile'];
        if ($telephone == '') {
            $telephone = $this->data['Customer Client Main Plain Telephone'];
        }

        if ($telephone == '') {
            $customer  = get_object('Customer', $this->data['Customer Client Customer Key']);
            $telephone = $customer->get_telephone();
        }

        return $telephone;
    }

    function create_order() {

        $account = get_object('Account', 1);


        $order_data = array(

            'Order Original Data MIME Type' => 'application/aurora',
            'Order Type'                    => 'Order',
            'editor'                        => $this->editor,


        );

        $customer = get_object('Customer', $this->data['Customer Client Customer Key']);

        $order_data['Order Customer Client Key']   = $this->id;
        $order_data['Order Customer Key']          = $customer->id;
        $order_data['Order Customer Name']         = $customer->data['Customer Name'];
        $order_data['Order Customer Contact Name'] = $customer->data['Customer Main Contact Name'];
        $order_data['Order Customer Level Type']   = $customer->data['Customer Level Type'];

        $order_data['Order Registration Number'] = $customer->data['Customer Registration Number'];

        $order_data['Order Source Key']   = 1;


        $order_data['Order Tax Number']                    = $customer->data['Customer Tax Number'];
        $order_data['Order Tax Number Valid']              = $customer->data['Customer Tax Number Valid'];
        $order_data['Order Tax Number Validation Date']    = $customer->data['Customer Tax Number Validation Date'];
        $order_data['Order Tax Number Validation Source']  = $customer->data['Customer Tax Number Validation Source'];
        $order_data['Order Tax Number Validation Message'] = $customer->data['Customer Tax Number Validation Message'];

        $order_data['Order Tax Number Details Match']      = $customer->data['Customer Tax Number Details Match'];
        $order_data['Order Tax Number Registered Name']    = $customer->data['Customer Tax Number Registered Name'];
        $order_data['Order Tax Number Registered Address'] = $customer->data['Customer Tax Number Registered Address'];

        $order_data['Order Available Credit Amount']  = $customer->data['Customer Account Balance'];
        $order_data['Order Sales Representative Key'] = $customer->data['Customer Sales Representative Key'];

        $order_data['Order Customer Fiscal Name'] = $customer->get('Fiscal Name');

        //$email = $this->data['Customer Client Main Plain Email'];
        //if ($email == '') {
        //    $email = $customer->data['Customer Main Plain Email'];
        //}
        //$order_data['Order Email'] = $email;
        //$order_data['Order Telephone'] = $this->get_telephone();

        $order_data['Order Email']     = $customer->data['Customer Main Plain Email'];
        $order_data['Order Telephone'] = $customer->get_telephone();


        $order_data['Order Invoice Address Recipient']            = $customer->data['Customer Invoice Address Recipient'];
        $order_data['Order Invoice Address Organization']         = $customer->data['Customer Invoice Address Organization'];
        $order_data['Order Invoice Address Line 1']               = $customer->data['Customer Invoice Address Line 1'];
        $order_data['Order Invoice Address Line 2']               = $customer->data['Customer Invoice Address Line 2'];
        $order_data['Order Invoice Address Sorting Code']         = $customer->data['Customer Invoice Address Sorting Code'];
        $order_data['Order Invoice Address Postal Code']          = $customer->data['Customer Invoice Address Postal Code'];
        $order_data['Order Invoice Address Dependent Locality']   = $customer->data['Customer Invoice Address Dependent Locality'];
        $order_data['Order Invoice Address Locality']             = $customer->data['Customer Invoice Address Locality'];
        $order_data['Order Invoice Address Administrative Area']  = $customer->data['Customer Invoice Address Administrative Area'];
        $order_data['Order Invoice Address Country 2 Alpha Code'] = $customer->data['Customer Invoice Address Country 2 Alpha Code'];
        $order_data['Order Invoice Address Checksum']             = $customer->data['Customer Invoice Address Checksum'];
        $order_data['Order Invoice Address Formatted']            = $customer->data['Customer Invoice Address Formatted'];
        $order_data['Order Invoice Address Postal Label']         = $customer->data['Customer Invoice Address Postal Label'];


        $order_data['Order Delivery Address Recipient']            = $this->data['Customer Client Contact Address Recipient'];
        $order_data['Order Delivery Address Organization']         = $this->data['Customer Client Contact Address Organization'];
        $order_data['Order Delivery Address Line 1']               = $this->data['Customer Client Contact Address Line 1'];
        $order_data['Order Delivery Address Line 2']               = $this->data['Customer Client Contact Address Line 2'];
        $order_data['Order Delivery Address Sorting Code']         = $this->data['Customer Client Contact Address Sorting Code'];
        $order_data['Order Delivery Address Postal Code']          = $this->data['Customer Client Contact Address Postal Code'];
        $order_data['Order Delivery Address Dependent Locality']   = $this->data['Customer Client Contact Address Dependent Locality'];
        $order_data['Order Delivery Address Locality']             = $this->data['Customer Client Contact Address Locality'];
        $order_data['Order Delivery Address Administrative Area']  = $this->data['Customer Client Contact Address Administrative Area'];
        $order_data['Order Delivery Address Country 2 Alpha Code'] = $this->data['Customer Client Contact Address Country 2 Alpha Code'];
        $order_data['Order Delivery Address Checksum']             = $this->data['Customer Client Contact Address Checksum'];
        $order_data['Order Delivery Address Formatted']            = $this->data['Customer Client Contact Address Formatted'];
        $order_data['Order Delivery Address Postal Label']         = $this->data['Customer Client Contact Address Postal Label'];


        $order_data['Order Sticky Note']          = $customer->data['Customer Order Sticky Note'];
        $order_data['Order Delivery Sticky Note'] = $customer->data['Customer Delivery Sticky Note'];


        $order_data['Order Customer Order Number'] = $customer->get_number_of_orders() + 1;

        $store = get_object('Store', $customer->get('Customer Store Key'));

        $order_data['Order Store Key']                = $store->id;
        $order_data['Order Currency']                 = $store->get('Store Currency Code');
        $order_data['Order Show in Warehouse Orders'] = $store->get('Store Show in Warehouse Orders');
        $order_data['public_id_format']               = $store->get('Store Order Public ID Format');


        $order_data['Recargo Equivalencia'] = $customer->get('Customer Recargo Equivalencia');


        include_once 'class.Public_Order.php';
        $order = new Public_Order('new', $order_data);

        if ($order->error) {
            $this->error = true;
            $this->msg   = $order->msg;

            return $order;
        }

        $order->fast_update_json_field('Order Metadata', 'cc_email', $this->data['Customer Client Main Plain Email']);
        $order->fast_update_json_field('Order Metadata', 'cc_telephone', $this->get('Phone'));


        require_once 'utils/new_fork.php';
        new_housekeeping_fork(
            'au_housekeeping', array(
            'type'        => 'order_created',
            'subject_key' => $order->id,
            'editor'      => $order->editor
        ), $account->get('Account Code'), $this->db
        );


        return $order;

    }

    function get_number_of_orders($type = '') {

        $sql = "SELECT count(*) AS number FROM `Order Dimension` WHERE `Order Customer Client Key`=? ";

        switch ($type) {
            case 'Basket':
                $sql .= " and `Order State`='InBasket'";
                break;
            case 'Cancelled':
                $sql .= " and `Order State`='Cancelled'";
                break;
            case 'All Submitted':
                $sql .= " and `Order State` not in ('InBasket','Cancelled')";
                break;
            case 'All Submitted including Cancelled':
                $sql .= " and `Order State` not in ('InBasket')";
                break;

        }


        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        if ($row = $stmt->fetch()) {
            $number = $row['number'];
        } else {
            $number = 0;
        }


        return $number;


    }

    /**
     * @return array
     */
    public function get_orders_data() {

        $orders_data = array();
        $sql         = sprintf('select `Order Invoice Key`,`Order Key`,`Order Public ID`,`Order Date`,`Order Total Amount`,`Order State`,`Order Currency` from `Order Dimension` where `Order Customer Client Key`=%d order by `Order Date` desc ', $this->id);

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                switch ($row['Order State']) {
                    default:
                        $state = $row['Order State'];
                }

                $orders_data[] = array(
                    'key'         => $row['Order Key'],
                    'invoice_key' => $row['Order Invoice Key'],
                    'number'      => $row['Order Public ID'],
                    'date'        => strftime("%e %b %Y", strtotime($row['Order Date'].' +0:00')),
                    'state'       => $state,
                    'total'       => money($row['Order Total Amount'], $row['Order Currency'])

                );
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }

        return $orders_data;

    }


    function get_field_label($field) {


        switch ($field) {

            case 'Customer Client Company Name':
                $label = _('company name');
                break;
            case 'Customer Client Main Contact Name':
                $label = _('contact name');
                break;
            case 'Customer Client Main Plain Email':
                $label = _('email');
                break;
            case 'Customer Client Main Email':
                $label = _('main email');
                break;
            case 'Customer Client Other Email':
                $label = _('other email');
                break;
            case 'Customer Client Main Plain Telephone':
            case 'Customer Client Main XHTML Telephone':
                $label = _('telephone');
                break;
            case 'Customer Client Main Plain Mobile':
            case 'Customer Client Main XHTML Mobile':
                $label = _('mobile');
                break;
            case 'Customer Client Main Plain FAX':
            case 'Customer Client Main XHTML Fax':
                $label = _('fax');
                break;
            case 'Customer Client Other Telephone':
                $label = _('other telephone');
                break;
            case 'Customer Client Preferred Contact Number':
                $label = _('main contact number');
                break;
            case 'Customer Client Fiscal Name':
                $label = _('fiscal name');
                break;

            case 'Customer Client Contact Address':
                $label = _('contact address');
                break;
            case 'Customer Client Code':
                $label = _('Reference');
                break;

            default:
                $label = $field;

        }

        return $label;

    }


    function delete() {


        $this->deleted = false;


        $sql  = "SELECT `Order Key`  FROM `Order Dimension` WHERE `Order State` in  ('InBasket') and  `Order Customer Client Key`=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        $orders_in_basket = [];
        while ($row = $stmt->fetch()) {
            $order = get_object('Order', $row['Order Key']);
            $order->editor;
            $order->cancel(_('Cancelled because client was deleted'));

            $order->fast_update_json_field('Order Metadata', 'cancel_reason', 'client_deleted');
            $orders_in_basket[] = $order;


        }

        $number_orders = 0;

        $sql  = "SELECT count(*) AS num  FROM `Order Dimension` WHERE `Order State`!='Cancelled' and  `Order Customer Client Key`=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        if ($row = $stmt->fetch()) {
            $number_orders = $row['num'];
        }


        if ($number_orders > 0) {
            $this->deactivate();
            $this->model_updated('deactivate',$this->id);

            return;
        }

        foreach ($orders_in_basket as $order) {
            $order->fast_update(['Order Customer Client Key' => null]);
        }

        $history_data = array(
            'History Abstract' => _('Customer client deleted'),
            'History Details'  => '',
            'Action'           => 'deleted'
        );

        $this->add_subject_history(
            $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
        );


        $sql = sprintf(
            "DELETE FROM `Customer Client Dimension` WHERE `Customer Client Key`=%d", $this->id
        );
        $this->db->exec($sql);

        require_once 'utils/new_fork.php';
        new_housekeeping_fork(
            'au_housekeeping', array(
            'type'                => 'customer_client_deleted',
            'store_key'           => $this->data['Customer Client Store Key'],
            'customer_key'        => $this->data['Customer Client Customer Key'],
            'customer_client_key' => $this->id,
            'editor'              => $this->editor
        ), DNS_ACCOUNT_CODE, $this->db
        );

        $this->fork_index_elastic_search('delete_elastic_index_object');
        $this->model_updated('deleted',$this->id);

        $this->deleted = true;
    }


    function deactivate() {


        $this->deleted = false;


        $sql  = "SELECT `Order Key`  FROM `Order Dimension` WHERE `Order State` in  ('InBasket') and  `Order Customer Key`=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        while ($row = $stmt->fetch()) {
            $order = get_object('Order', $row['Order Key']);
            $order->editor;
            $order->cancel(_('Cancelled because client was deleted'));
            $order->fast_update_json_field('Order Metadata','cancel_reason', 'client_deactivated');
        }


        $history_data = array(
            'History Abstract' => _('Customer client deactivated'),
            'History Details'  => '',
            'Action'           => 'edited'
        );
        $this->add_subject_history(
            $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
        );


        $this->fast_update_json_field('Customer Client Metadata', 'deactivated_date', gmdate('Y-m-d H:i:s'));
        //$this->fast_update_json_field('Order Metadata','client_reference', $this->get('Customer Client Code'));

        $this->fast_update(
            [
                'Customer Client Status' => 'Inactive',
                'Customer Client Code'   => ''
            ]
        );


        require_once 'utils/new_fork.php';
        new_housekeeping_fork(
            'au_housekeeping', array(
            'type'                => 'customer_client_deactivated',
            'store_key'           => $this->data['Customer Client Store Key'],
            'customer_key'        => $this->data['Customer Client Customer Key'],
            'customer_client_key' => $this->id,
            'editor'              => $this->editor
        ), DNS_ACCOUNT_CODE, $this->db
        );

        $this->fork_index_elastic_search('delete_elastic_index_object');

        $this->deleted = true;

        $this->model_updated('deactivate',$this->id);


    }

}

