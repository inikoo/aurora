<?php
/*


 About:
 Author: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2010, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';

class Account extends DB_Table {
    /**
     * @var \PDO
     */
    public $db;
    private $properties;

    function __construct($_db = false) {

        if (!$_db) {
            global $db;
            $this->db = $db;
        } else {
            $this->db = $_db;
        }


        $this->table_name = 'Account';
        $this->properties = array();

        $this->get_data();
    }

    function get_data() {


        $sql = "SELECT * FROM `Account Dimension` WHERE `Account Key`=1 ";


        if ($result = $this->db->query($sql)) {
            if ($this->data = $result->fetch()) {
                $this->id = 1;
            }
        }


    }


    function load_acc_data() {

        $sql = "SELECT * FROM `Account Data`  WHERE `Account Key`=?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        if ($row = $stmt->fetch()) {

            foreach ($row as $key => $value) {
                if ($key == 'Account Properties') {

                    $this->properties = json_decode($value, true);
                    $this->settings   = [];
                } else {
                    $this->data[$key] = $value;
                }

            }
        }

    }

    function load_properties() {

        $sql = "SELECT `Account Properties` FROM `Account Data`  WHERE `Account Key`=?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        while ($row = $stmt->fetch()) {
            $this->properties = json_decode($row['Account Properties'], true);
        }


    }


    function settings($key) {
        return ($this->settings[$key] ?? '');
    }

    function add_account_history($history_key, $type = false) {
        $this->post_add_history($history_key, $type);
    }

    function post_add_history($history_key, $type = false) {

        if (!$type) {
            $type = 'Changes';
        }

        $sql = "INSERT INTO  `Account History Bridge` (`Account Key`,`History Key`,`Type`) VALUES (?,?,?)";
        $this->db->prepare($sql)->execute(
            array(
                $this->id,
                $history_key,
                $type
            )
        );


    }


    function create_staff($data) {
        $this->new_employee = false;

        $data['editor'] = $this->editor;


        $staff = new Staff('find', $data, 'create');
        if ($staff->id) {
            $this->new_employee_msg = $staff->msg;

            if ($staff->new) {
                $this->new_employee = true;
                $this->update_employees_data();
            } else {
                $this->error = true;
                if ($staff->found) {
                    $this->msg            = _('Duplicated employee code');
                    $this->error_code     = 'duplicated_field';
                    $this->error_metadata = json_encode(array('Staff Alias'));

                } else {
                    $this->msg = $staff->msg;
                }
            }

            return $staff;
        } else {

            $this->error = true;
            $this->msg   = $staff->msg;

            return false;
        }
    }

    function update_employees_data() {
        $number_employees = 0;
        $sql              = sprintf(
            "SELECT count(*) AS num FROM `Staff Dimension` WHERE `Staff Currently Working`='Yes' "
        );
        if ($row = $this->db->query($sql)->fetch()) {
            $number_employees = $row['num'];
        }

        $this->update(
            array('Account Employees' => $number_employees), 'no_history'
        );

    }

    function create_store($data) {
        include_once 'class.Country.php';

        $this->new_object = false;

        $data['editor'] = $this->editor;


        $data['Store Status'] = 'Normal';


        $data['Store Valid From'] = gmdate('Y-m-d H:i:s');


        if (!isset($data['Store Timezone']) or $data['Store Timezone'] == '') {
            $data['Store Timezone'] = $this->get('Account Timezone');

        } else {
            $data['Store Timezone'] = preg_replace('/_/', '/', $data['Store Timezone']);

        }

        if (!isset($data['Store Currency Code']) or $data['Store Currency Code'] == '') {
            $data['Store Currency Code'] = $this->get('Account Currency');

        }


        if (!isset($data['Store Type']) or $data['Store Type'] == '') {
            $data['Store Type'] = 'B2B';

        }

        $data['Store Tax Country Code'] = $this->get('Account Country Code');


        $data['Store Home Country Code 2 Alpha'] = substr($data['Store Locale'], -2);

        $country = new Country('2alpha', $data['Store Home Country Code 2 Alpha']);

        $data['Store Home Country Name'] = $country->get('Country Name');


        $store = new Store('find', $data, 'create');


        if ($store->id) {
            $this->new_object_msg = $store->msg;

            if ($store->new) {
                $this->new_object = true;
                $this->update_stores_data();
            } else {
                $this->error = true;
                if ($store->found) {

                    $this->error_code     = 'duplicated_field';
                    $this->error_metadata = json_encode(array($store->duplicated_field));

                    if ($store->duplicated_field == 'Store Code') {
                        $this->msg = _('Duplicated store code');
                    } else {
                        $this->msg = _('Duplicated store name');
                    }


                } else {
                    $this->msg = $store->msg;
                }
            }

            return $store;
        } else {
            $this->error = true;
            $this->msg   = $store->msg;

            return false;
        }
    }

    function get($key, $args = false) {


        if (!$this->id) {
            return false;
        }


        switch ($key) {

            case 'Currency Code':
            case 'Account Currency Code':
                return $this->data['Account Currency'];

            case ('Currency'):

                if ($this->data['Account Currency'] != '') {


                    $sql = sprintf(
                        "SELECT `Currency Code`,`Currency Name`,`Currency Symbol` FROM kbase.`Currency Dimension` WHERE `Currency Code`=%s", prepare_mysql($this->data['Account Currency'])
                    );


                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {
                            return sprintf("%s (%s)", $row['Currency Name'], $row['Currency Code']);
                        } else {
                            return '';
                        }
                    }
                } else {
                    return '';
                }



            case 'Country Code':
                if ($this->get('Account Country Code')) {
                    include_once 'class.Country.php';
                    $country = new Country('code', $this->data['Account Country Code']);

                    return _($country->get('Country Name')).' ('.$country->get('Country Code').')';
                } else {
                    return '';
                }


            case 'Productions':

                $number = 0;
                $sql    = "SELECT count(*) AS num FROM `Supplier Production Dimension`";
                $stmt   = $this->db->prepare($sql);
                $stmt->execute();
                if ($row = $stmt->fetch()) {
                    $number = $row['num'];
                }

                return $number;


            case('Locale'):


                include_once 'utils/available_locales.php';
                $available_locales = get_available_locales();


                if (array_key_exists(
                    $this->data['Account Locale'].'.UTF-8', $available_locales
                )) {
                    $locale = $available_locales[$this->data['Account Locale'].'.UTF-8'];

                    return $locale['Language Name'].($locale['Language Name'] != $locale['Language Original Name'] ? ' ('.$locale['Language Original Name'].')' : '');
                } else {

                    return $this->data['Account Locale'];
                }



            case 'Setup Metadata':
                return json_decode($this->data['Account Setup Metadata'], true);

            case 'National Employment Code Label':

                switch ($this->data['Account Country 2 Alpha Code']) {
                    case 'GB':
                        return _('National insurance number');

                    case 'ES':
                        return 'DNI';

                    default:
                        return '';

                }



            case 'Delta Today Start Orders In Warehouse Number':

                $start = $this->data['Account Today Start Orders In Warehouse Number'];
                $end   = $this->data['Account Orders In Warehouse Number'] + $this->data['Account Orders Packed Number'] + $this->data['Account Orders Dispatch Approved Number'];

                $diff = $end - $start;

                return ($diff > 0 ? '+' : '').number($diff).delta_icon($end, $start, $inverse = true);

            case 'Today Orders Dispatched':

                $number = 0;


                $sql  = "SELECT count(*) AS num FROM `Order Dimension` WHERE `Order State`='Dispatched' AND `Order Dispatched Date`>?   AND  `Order Dispatched Date`<? ";
                $stmt = $this->db->prepare($sql);
                $stmt->execute(
                    array(
                        date('Y-m-d 00:00:00'),
                        date('Y-m-d 23:59:59')
                    )
                );
                if ($row = $stmt->fetch()) {
                    $number = $row['num'];
                }


                return number($number);
            case 'Containers in Transit':

                $number = 0;


                $sql  = "SELECT count(*) AS num FROM `Supplier Delivery Dimension` WHERE `Supplier Delivery State`='Dispatched' and `Supplier Delivery Parent`!='Order'  and `Supplier Delivery Type`='Container' ";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                if ($row = $stmt->fetch()) {
                    $number = $row['num'];
                }


                return number($number);
            case 'Small Deliveries in Transit':

                $number = 0;


                $sql  = "SELECT count(*) AS num FROM `Supplier Delivery Dimension` WHERE `Supplier Delivery State`='Dispatched' and `Supplier Delivery Parent`!='Order' and  `Supplier Delivery Type`='Parcel'";
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                if ($row = $stmt->fetch()) {
                    $number = $row['num'];
                }


                return number($number);
            case 'Label Signature':
                return nl2br($this->data['Account Label Signature']);
                break;

            case 'Active Suppliers Parts Stock Surplus Number':
            case 'Active Suppliers Parts Stock OK Number':
            case 'Active Suppliers Parts Stock Low Number':
            case 'Active Suppliers Parts Stock Critical Number':
            case 'Active Suppliers Parts Stock Zero Number':

            case 'Active Suppliers Parts Stock Surplus Deliveries Number':
            case 'Active Suppliers Parts Stock OK Deliveries Number':
            case 'Active Suppliers Parts Stock Low Deliveries Number':
            case 'Active Suppliers Parts Stock Critical Deliveries Number':
            case 'Active Suppliers Parts Stock Zero Deliveries Number':

                return number($this->data['Account '.preg_replace('/Suppliers /', '', $key)] - $this->data['Account '.preg_replace('/Suppliers /', 'Production ', $key)]);


            case 'Active Suppliers Parts Stock Surplus Stock Value Minify':
            case 'Active Suppliers Parts Stock OK Stock Value Minify':
            case 'Active Suppliers Parts Stock Low Stock Value Minify':
            case 'Active Suppliers Parts Stock Critical Stock Value Minify':
            case 'Active Suppliers Parts Stock Error Stock Value Minify':
            case 'Active Suppliers Parts Stock Zero Stock Value Minify':


                $field = 'Account '.preg_replace('/ Minify$/', '', $key);

                $_value = ($this->data[preg_replace('/Suppliers /', '', $field)] - $this->data[preg_replace('/Suppliers /', 'Production ', $field)]);

                $suffix          = '';
                $fraction_digits = 'NO_FRACTION_DIGITS';
                if ($_value >= 1000000) {
                    $suffix          = 'M';
                    $fraction_digits = 'DOUBLE_FRACTION_DIGITS';
                    $_amount         = $_value / 1000000;
                } elseif ($_value >= 10000) {
                    $suffix  = 'K';
                    $_amount = $_value / 1000;
                } elseif ($_value > 100) {
                    $fraction_digits = 'SINGLE_FRACTION_DIGITS';
                    $suffix          = 'K';
                    $_amount         = $_value / 1000;
                } else {
                    $_amount = $_value;
                }

                return money($_amount, $this->get('Account Currency'), $locale = false, $fraction_digits).$suffix;


            case 'Contacts':
            case 'New Contacts':
            case 'Contacts With Orders':
            case 'New Contacts With Orders':
            case 'Active Contacts':
            case 'Losing Contacts':
            case 'Lost Contacts':
            case 'Active Parts Number':
            case 'In Process Parts Number':
            case 'Discontinuing Parts Number':
            case 'Discontinued Parts Number':

            case 'Active Parts Stock Surplus Number':
            case 'Active Parts Stock OK Number':
            case 'Active Parts Stock Low Number':
            case 'Active Parts Stock Critical Number':
            case 'Active Parts Stock Zero Number':

            case 'Active Parts Stock Surplus Deliveries Number':
            case 'Active Parts Stock OK Deliveries Number':
            case 'Active Parts Stock Low Deliveries Number':
            case 'Active Parts Stock Critical Deliveries Number':
            case 'Active Parts Stock Zero Deliveries Number':


            case 'Active Production Stock Surplus Number':
            case 'Active Production Stock OK Number':
            case 'Active Production Stock Low Number':
            case 'Active Production Stock Critical Number':
            case 'Active Production Stock Zero Number':
            case 'Active Production Stock Surplus Deliveries Number':
            case 'Active Production Stock OK Deliveries Number':
            case 'Active Production Stock Low Deliveries Number':
            case 'Active Production Stock Critical Deliveries Number':
            case 'Active Production Stock Zero Deliveries Number':

            case 'Parts No Products':
            case 'Parts Forced not for Sale':
                return number($this->data['Account '.$key]);

            case 'Active Parts Stock Surplus Number Excluding Production':
            case 'Active Parts Stock OK Number Excluding Production':
            case 'Active Parts Stock Low Number Excluding Production':
            case 'Active Parts Stock Critical Number Excluding Production':
            case 'Active Parts Stock Zero Number Excluding Production':

            case 'Active Parts Stock Surplus Deliveries Number Excluding Production':
            case 'Active Parts Stock OK Deliveries Number Excluding Production':
            case 'Active Parts Stock Low Deliveries Number Excluding Production':
            case 'Active Parts Stock Critical Deliveries Number Excluding Production':
            case 'Active Parts Stock Zero Deliveries Number Excluding Production':

                $_key            = 'Account '.preg_replace('/ Excluding Production/', '', $key);
                $_key_production = preg_replace('/Active/', 'Active Production', $_key);

                return number($this->data[$_key] - $this->data[$_key_production]);

            case 'Active Parts Number Excluding Production':
            case 'In Process Parts Number Excluding Production':
            case 'Discontinuing Parts Number Excluding Production':
            case 'Discontinued Parts Number Excluding Production':
            case 'Parts No Products Excluding Production':
            case 'Parts Forced not for Sale Excluding Production':

                $_key = preg_replace('/ Excluding Production/', '', $key);

                return number($this->data['Account '.$_key] - $this->data['Account Production '.$_key]);


            case 'Active Parts Stock Surplus Stock Value Minify Excluding Production':
            case 'Active Parts Stock OK Stock Value Minify Excluding Production':
            case 'Active Parts Stock Low Stock Value Minify Excluding Production':
            case 'Active Parts Stock Critical Stock Value Minify Excluding Production':
                $_key            = 'Account '.preg_replace('/ Minify Excluding Production/', '', $key);
                $_key_production = preg_replace('/Active/', 'Active Production', $_key);

                return money_minify($this->data[$_key] - $this->data[$_key_production], $this->get('Account Currency'));

            case 'Percentage Contacts With Orders':
            case 'Percentage Active Contacts':
                return percentage($this->data['Account '.preg_replace('/^Percentage /', '', $key)], $this->data['Account Contacts']);
                break;
            case 'Percentage New Contacts With Orders':
                return ($this->data['Account New Contacts'] == 0 ? '' : '('.percentage($this->data['Account '.preg_replace('/^Percentage /', '', $key)], $this->data['Account New Contacts'])).')';

                break;
            case 'Pretty Valid From':
                return strftime("%a %e %b %Y", strtotime($this->data['Account Valid From'].' +0:00'));

            case 'Timezone':
                include_once 'utils/timezones.php';

                return get_normalized_timezones_formatted_label($this->data['Account Timezone']);
            case 'dispatch_time_avg':
            case 'dispatch_time_samples':
            case 'sitting_time_avg':
            case 'sitting_time_samples':

                if ($args != '') {
                    $key .= '_'.strtolower(preg_replace('/\s/', '_', $args));
                }


                return $this->properties($key);
                break;
            case 'formatted_dispatch_time_avg':
            case 'formatted_sitting_time_avg':
                $dispatch_time_average = $this->get(preg_replace('/formatted_/', '', $key), $args);

                return seconds_to_natural_string($dispatch_time_average);
            case 'formatted_bis_dispatch_time_avg':
            case 'formatted_bis_sitting_time_avg':

                $dispatch_time_average = $this->get(preg_replace('/formatted_bis_/', '', $key), $args);

                return seconds_to_string($dispatch_time_average);
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


            case 'percentage_dispatch_time_histogram':
                return percentage(
                    $this->get('dispatch_time_histogram', $args), $this->get('dispatch_time_samples', $args[1])
                );


            default:


                if (preg_match('/^(DC Orders|Orders|Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Minify$/', $key) or in_array(
                        $key, array(
                                'Active Parts Stock Surplus Stock Value Minify',
                                'Active Parts Stock OK Stock Value Minify',
                                'Active Parts Stock Low Stock Value Minify',
                                'Active Parts Stock Critical Stock Value Minify',
                                'Active Parts Stock Error Stock Value Minify',
                                'Active Parts Stock Zero Stock Value Minify',
                                'Active Production Parts Stock Surplus Stock Value Minify',
                                'Active Production Parts Stock OK Stock Value Minify',
                                'Active Production Parts Stock Low Stock Value Minify',
                                'Active Production Parts Stock Critical Stock Value Minify',
                                'Active Production Parts Stock Error Stock Value Minify',
                                'Active Production Parts Stock Zero Stock Value Minify',


                            )
                    )

                ) {

                    $field = 'Account '.preg_replace('/ Minify$/', '', $key);
                    $field = preg_replace('/DC Orders/', 'Orders', $field);


                    return money_minify($this->data[$field], $this->get('Account Currency'));

                }


                if (preg_match('/^(DC Orders|Orders|Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Soft Minify$/', $key)) {

                    $field = 'Account '.preg_replace('/ Soft Minify$/', '', $key);


                    $field = preg_replace('/DC Orders/', 'Orders', $field);

                    $suffix          = '';
                    $fraction_digits = 'NO_FRACTION_DIGITS';
                    $_amount         = $this->data[$field];

                    return money($_amount, $this->get('Account Currency'), $locale = false, $fraction_digits).$suffix;
                }
                if (preg_match('/^(Orders|Last|Yesterday|Total|1|10|6|3|2|4|5|Year To|Quarter To|Month To|Today|Week To).*(Quantity Invoiced|Invoices|Distinct Parts Dispatched|Number)$/', $key)) {

                    $field = 'Account '.$key;


                    return number($this->data[$field]);
                }
                if (preg_match('/^(Orders|Last|Yesterday|Total|1|10|6|3|2|4|5|Year To|Quarter To|Month To|Today|Week To).*(Quantity Invoiced|Invoices|Distinct Parts Dispatched) Minify$/', $key)) {

                    $field = 'Account '.preg_replace('/ Minify$/', '', $key);

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
                if (preg_match('/^(Orders|Last|Yesterday|Total|1|10|6|3|2|4|5|Year To|Quarter To|Month To|Today|Week To).*(Quantity Invoiced|Invoices|Number) Soft Minify$/', $key)) {
                    $field   = 'Account '.preg_replace('/ Soft Minify$/', '', $key);
                    $_number = $this->data[$field];

                    return number($_number, 0);
                }

                if (preg_match('/^(DC Orders|Orders|Total|1).*(Amount|Profit)$/', $key)) {

                    $field = 'Account '.$key;
                    $field = preg_replace('/DC Orders/', 'Orders', $field);


                    return money($this->data[$field], $this->get('Account Currency'));
                }

                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Account '.$key, $this->data)) {
                    return $this->data['Account '.$key];
                }
        }

        return '';
    }

    public function properties($key) {
        return ($this->properties[$key] ?? '');
    }

    function update_stores_data() {
        $number_stores   = 0;
        $number_websites = 0;

        $sql = "SELECT count(*) AS num FROM `Store Dimension` ";
        if ($row = $this->db->query($sql)->fetch()) {
            $number_stores = $row['num'];
        }

        $sql = "SELECT count(*) AS num FROM `Website Dimension`";
        if ($row = $this->db->query($sql)->fetch()) {
            $number_websites = $row['num'];
        }

        $this->fast_update(
            [
                'Account Stores'   => $number_stores,
                'Account Websites' => $number_websites
            ]
        );


    }

    function create_barcode($data, $user) {

        $this->new_object = false;


        $data['editor'] = $this->editor;

        $this->errors = 0;
        $this->new    = 0;
        $barcode      = false;

        $barcodes_range = $data['Barcode Range'];
        unset($data['Barcode Range']);

        $range = preg_split('/-/', (string)$barcodes_range);


        if (count($range) == 1) {
            $data['Barcode Number']    = $range[0];
            $data['Barcode Used From'] = gmdate('Y-m-d H:i:s');
            $data['editor']['Date']    = gmdate('Y-m-d H:i:s');

            $barcode = new Barcode('find', $data, 'create');
            if (!$barcode->id) {
                $this->error = true;
                $this->msg   = $barcode->msg;

                return false;
            } else {
                if ($barcode->new) {
                    $this->new++;
                } else {
                    $this->error = true;


                    $this->msg = $barcode->msg;

                    return false;
                }
            }
        } elseif (count($range) == 2) {


            require_once 'utils/new_fork.php';
            new_housekeeping_fork(
                'au_housekeeping', array(
                'type'   => 'add_barcode_range',
                'ws_key' => 'real_time.'.strtolower(DNS_ACCOUNT_CODE).'.'.$user->id,
                'range'  => $range,
                'editor' => $this->editor
            ), DNS_ACCOUNT_CODE, $this->db
            );

            return 'fork';

        } else {
            $this->error = true;
            $this->msg   = _('None of the bar codes could be added');

            return false;
        }


        if ($this->new == 0) {
            $this->error = true;
            $this->msg   = _('None of the bar codes could be added');

            return false;
        }

        return $barcode;
    }

    function create_warehouse($data, $no_history = false) {

        include_once 'class.Warehouse.php';

        $this->new_object = false;

        $data['editor'] = $this->editor;

        $data['Warehouse State']          = 'Active';
        $data['Warehouse Valid From']     = gmdate('Y-m-d H:i:s');
        $data['Warehouse Address']        = '';
        $data['Warehouse Company Name']   = '';
        $data['Warehouse Company Number'] = '';
        $data['Warehouse VAT Number']     = '';
        $data['Warehouse Telephone']      = '';
        $data['Warehouse Email']          = '';

        $warehouse = new Warehouse('find', $data, 'create');


        if ($warehouse->id) {
            $this->new_object_msg = $warehouse->msg;


            if ($warehouse->new) {


                if (!$no_history) {

                    $history_data = array(
                        'History Abstract' => _('Warehouse created'),
                        'History Details'  => '',
                        'Action'           => 'created'
                    );

                    $this->add_subject_history(
                        $history_data, true, 'No', 'Changes', $warehouse->get_object_name(), $warehouse->id
                    );


                    $history_data = array(
                        'History Abstract' => sprintf(
                            _('Warehouse (%s) created'), $warehouse->get('Name')
                        ),
                        'History Details'  => '',
                        'Action'           => 'created'
                    );

                    $this->add_subject_history(
                        $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
                    );

                }

                $this->new_object = true;


                $this->update_warehouses_data();


                if ($this->get('Account Warehouses') == 1) {


                    $sql = sprintf("SELECT `User Key` FROM `User Dimension` WHERE `User Type` IN ('Staff','Warehouse','Contractor','Agent')  ");


                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {

                            $_user = get_object('User', $row['User Key']);

                            $_user->read_rights();
                            if ($_user->can_view('locations')) {

                                $_user->add_warehouse(array($warehouse->id));
                            }

                        }
                    }

                }

                if (is_numeric($this->editor['User Key']) and $this->editor['User Key'] > 1) {
                    $_user = get_object('User', $this->editor['User Key']);

                    if (in_array(
                        $_user->get('User Type'), array(
                                                    'Staff',
                                                    'Contractor'
                                                )
                    )) {
                        if ($_user->id) {
                            $_user->add_warehouse(array($warehouse->id));
                        }
                    }
                }


            } else {
                $this->error = true;
                if ($warehouse->found) {

                    $this->error_code     = 'duplicated_field';
                    $this->error_metadata = json_encode(
                        array($warehouse->duplicated_field)
                    );

                    if ($warehouse->duplicated_field == 'Warehouse Code') {
                        $this->msg = _('Duplicated warehouse code');
                    } else {
                        $this->msg = _('Duplicated warehouse name');
                    }


                } else {
                    $this->msg = $warehouse->msg;
                }
            }

            return $warehouse;
        } else {
            $this->error = true;
            $this->msg   = $warehouse->msg;

            return false;
        }
    }

    function update_warehouses_data() {
        $number_warehouses = 0;

        $sql = "SELECT count(*) AS num FROM `Warehouse Dimension` WHERE `Warehouse State`='Active'";
        if ($row = $this->db->query($sql)->fetch()) {
            $number_warehouses = $row['num'];
        }

        $this->fast_update(
            array('Account Warehouses' => $number_warehouses)
        );

    }

    function update_sitting_time_in_warehouse() {
        $sql = "SELECT count(*) as num  ,avg(TIMESTAMPDIFF(SECOND,`Delivery Note Date Created`,NOW()) )as diff   FROM `Delivery Note Dimension` WHERE `Delivery Note State`  not in ('Dispatched','Cancelled','Cancelled to Restock') ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            $this->fast_update_json_field('Account Properties', 'sitting_time_samples', $row['num'], 'Account Data');
            $this->fast_update_json_field('Account Properties', 'sitting_time_avg', $row['diff'], 'Account Data');
        }


    }

    function update_production_job_orders_stats() {


        $elements_numbers = array(

            'Planning'      => 0,
            'Queued'        => 0,
            'Manufacturing' => 0,
            'Manufactured'  => 0,
            'Delivered'     => 0,
            'QC_Pass'       => 0,
            'Placed'        => 0,
            'Cancelled'     => 0

        );


        $sql = "SELECT count(*) AS number,`Purchase Order State` AS element FROM `Purchase Order Dimension` O where `Purchase Order Type`='Production' GROUP BY `Purchase Order State` ";


        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            if ($row['element'] == 'InProcess') {
                $element = 'Planning';
            } elseif ($row['element'] == 'Submitted') {
                $element = 'Queued';
            } elseif ($row['element'] == 'Confirmed') {
                $element = 'Manufacturing';
            } elseif ($row['element'] == 'Manufactured') {
                $element = 'Manufactured';
            } elseif ($row['element'] == 'QC_Pass') {
                $element = 'QC_Pass';
            } elseif ($row['element'] == 'Received' or $row['element'] == 'Checked' or $row['element'] == 'Inputted' or $row['element'] == 'Dispatched') {
                $element = 'Delivered';
            } elseif ($row['element'] == 'Placed' or $row['element'] == 'Costing' or $row['element'] == 'InvoiceChecked') {
                $element = 'Placed';
            } elseif ($row['element'] == 'Cancelled' or $row['element'] == 'NoReceived') {
                $element = 'Cancelled';
            }


            if (isset($elements_numbers[$element])) {
                $elements_numbers[$element] += $row['number'];
            }
        }
        $this->fast_update_json_field('Account Properties', 'production_job_orders_elements', json_encode($elements_numbers), 'Account Data');


    }


    function update_dispatching_time_data($interval) {

        include_once 'utils/date_functions.php';

        $interval_data = calculate_interval_dates($this->db, $interval);


        $sql =
            "select  count(*) as num  ,avg(TIMESTAMPDIFF(SECOND,`Order Submitted by Customer Date`,`Delivery Note Date Dispatched`)) as diff from   `Delivery Note Dimension` left join `Order Dimension` on (`Delivery Note Order Key`=`Order Key`) where `Delivery Note State`='Dispatched' and `Delivery Note Type`='Order' and `Delivery Note Date Dispatched`>=?  ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $interval_data[1]
            )
        );
        while ($row = $stmt->fetch()) {
            $this->fast_update_json_field('Account Properties', 'dispatch_time_samples_'.strtolower(preg_replace('/\s/', '_', $interval_data[0])), $row['num'], 'Account Data');
            $this->fast_update_json_field('Account Properties', 'dispatch_time_avg_'.strtolower(preg_replace('/\s/', '_', $interval_data[0])), $row['diff'], 'Account Data');
        }

        $sql = "select count(*) as num ,floor(TIMESTAMPDIFF(SECOND,`Order Submitted by Customer Date`,`Delivery Note Date Dispatched`)/3600/24)  days from   `Delivery Note Dimension` left join `Order Dimension` on (`Delivery Note Order Key`=`Order Key`) where `Delivery Note State`='Dispatched' 
                       and `Delivery Note Type`='Order' and `Delivery Note Date Dispatched`>=?  group by floor(TIMESTAMPDIFF(SECOND,`Order Submitted by Customer Date`,`Delivery Note Date Dispatched`)/3600/24) ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $interval_data[1]
            )
        );
        $waiting_time_histogram = array();
        while ($row = $stmt->fetch()) {
            $waiting_time_histogram[$row['days']] = $row['num'];
        }
        $this->fast_update_json_field('Account Properties', 'dispatch_time_histogram_'.strtolower(preg_replace('/\s/', '_', $interval_data[0])), json_encode($waiting_time_histogram), 'Account Data');


    }

    function update_parts_data() {

        $number_in_process_parts          = 0;
        $number_active_parts              = 0;
        $number_discontinuing_parts       = 0;
        $number_discontinued_parts        = 0;
        $number_parts_no_sko_barcodes     = 0;
        $number_parts_barcode_error       = 0;
        $number_parts_with_barcode        = 0;
        $number_parts_with_invalid_weight = 0;


        $number_parts_with_no_products           = 0;
        $number_parts_forced_not_for_sale_online = 0;


        $sql = "SELECT count(*) AS num ,`Part Status`  FROM `Part Dimension`  GROUP BY `Part Status`";
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                switch ($row['Part Status']) {
                    case 'In Use':
                        $number_active_parts = $row['num'];
                        break;
                    case 'In Process':
                        $number_in_process_parts = $row['num'];
                        break;
                    case 'Not In Use':
                        $number_discontinued_parts = $row['num'];
                        break;
                    case 'Discontinuing':
                        $number_discontinuing_parts = $row['num'];
                        break;
                }

            }
        }


        $sql = "SELECT count(*) AS num FROM `Part Dimension` WHERE `Part Status`!='Not In Use' AND `Part SKO Barcode`!=''  ";
        if ($row = $this->db->query($sql)->fetch()) {
            $number_parts_no_sko_barcodes = $row['num'];
        }

        $sql = sprintf(
            'SELECT count(*) AS num FROM `Part Dimension` WHERE `Part Barcode Number Error` IS NOT NULL  '
        );
        if ($row = $this->db->query($sql)->fetch()) {
            $number_parts_barcode_error = $row['num'];
        }

        $sql = sprintf(
            'SELECT count(*) AS num FROM `Part Dimension` WHERE `Part Barcode Number` IS NOT NULL  '
        );
        if ($row = $this->db->query($sql)->fetch()) {
            $number_parts_with_barcode = $row['num'];
        }


        $sql = "SELECT count(*) AS num FROM `Part Dimension` WHERE `Part Status`!='Not In Use' and  `Part Package Weight Status`!='OK'";
        if ($row = $this->db->query($sql)->fetch()) {
            $number_parts_with_invalid_weight = $row['num'];
        }

        $sql  = "SELECT `Part Products Web Status`,count(*) AS num FROM `Part Dimension` WHERE `Part Status` in ('In Use','Discontinuing') group by `Part Products Web Status`  ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            if ($row['Part Products Web Status'] == 'Offline' or $row['Part Products Web Status'] == 'Out of Stock') {
                $number_parts_forced_not_for_sale_online += $row['num'];
            }

            if ($row['Part Products Web Status'] == 'No Products') {
                $number_parts_with_no_products = $row['num'];
            }
        }


        $this->fast_update(
            array(
                'Account Active Parts Number'                  => $number_active_parts,
                'Account In Process Parts Number'              => $number_in_process_parts,
                'Account Discontinued Parts Number'            => $number_discontinued_parts,
                'Account Discontinuing Parts Number'           => $number_discontinuing_parts,
                'Account Active Parts with SKO Barcode Number' => $number_parts_no_sko_barcodes,
                'Account Parts with Barcode Number Error'      => $number_parts_barcode_error,
                'Account Parts with Barcode Number'            => $number_parts_with_barcode,
                'Account Active Parts with SKO Invalid Weight' => $number_parts_with_invalid_weight,
                'Account Parts No Products'                    => $number_parts_with_no_products,
                'Account Parts Forced not for Sale'            => $number_parts_forced_not_for_sale_online
            ), 'Account Data'
        );

        include_once 'utils/send_zqm_message.class.php';
        try {
            send_zqm_message(
                json_encode(
                    array(
                        'channel'  => 'real_time.'.strtolower($this->get('Account Code')),
                        'sections' => array(
                            array(
                                'section' => 'dashboard',

                                'update_metadata' => array(
                                    'class_html' => array(
                                        'Active_Parts'        => $this->get('Active Parts Number'),
                                        'In_Process_Parts'    => $this->get('In Process Parts Number'),
                                        'Discontinuing_Parts' => $this->get('Discontinuing Parts Number'),

                                    )
                                )

                            )

                        ),


                    )
                )
            );
        } catch (ZMQSocketException $e) {

        }

        $this->update_production_parts_data();
    }

    function update_production_parts_data() {

        $number_in_process_parts    = 0;
        $number_active_parts        = 0;
        $number_discontinuing_parts = 0;
        $number_discontinued_parts  = 0;


        $number_parts_with_no_products           = 0;
        $number_parts_forced_not_for_sale_online = 0;


        $sql = "SELECT count(*) AS num ,`Part Status`  FROM `Part Dimension` where `Part Production`='Yes'  GROUP BY `Part Status`";
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                switch ($row['Part Status']) {
                    case 'In Use':
                        $number_active_parts = $row['num'];
                        break;
                    case 'In Process':
                        $number_in_process_parts = $row['num'];
                        break;
                    case 'Not In Use':
                        $number_discontinued_parts = $row['num'];
                        break;
                    case 'Discontinuing':
                        $number_discontinuing_parts = $row['num'];
                        break;
                }

            }
        }


        $sql  = "SELECT `Part Products Web Status`,count(*) AS num FROM `Part Dimension` WHERE `Part Production`='Yes' and `Part Status` in ('In Use','Discontinuing') group by `Part Products Web Status`  ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            if ($row['Part Products Web Status'] == 'Offline' or $row['Part Products Web Status'] == 'Out of Stock') {
                $number_parts_forced_not_for_sale_online += $row['num'];
            }

            if ($row['Part Products Web Status'] == 'No Products') {
                $number_parts_with_no_products = $row['num'];
            }
        }


        $this->fast_update(
            array(
                'Account Production Active Parts Number'        => $number_active_parts,
                'Account Production In Process Parts Number'    => $number_in_process_parts,
                'Account Production Discontinued Parts Number'  => $number_discontinued_parts,
                'Account Production Discontinuing Parts Number' => $number_discontinuing_parts,

                'Account Production Parts No Products'         => $number_parts_with_no_products,
                'Account Production Parts Forced not for Sale' => $number_parts_forced_not_for_sale_online
            ), 'Account Data'
        );

        include_once 'utils/send_zqm_message.class.php';
        try {
            send_zqm_message(
                json_encode(
                    array(
                        'channel'  => 'real_time.'.strtolower($this->get('Account Code')),
                        'sections' => array(
                            array(
                                'section' => 'dashboard',

                                'update_metadata' => array(
                                    'class_html' => array(
                                        'Active_Parts'        => $this->get('Active Parts Number'),
                                        'In_Process_Parts'    => $this->get('In Process Parts Number'),
                                        'Discontinuing_Parts' => $this->get('Discontinuing Parts Number'),

                                    )
                                )

                            )

                        ),


                    )
                )
            );
        } catch (ZMQSocketException $e) {

        }

    }

    function update_active_parts_stock_data() {


        $number_surplus_parts      = 0;
        $number_ok_parts           = 0;
        $number_low_parts          = 0;
        $number_critical_parts     = 0;
        $number_error_parts        = 0;
        $number_parts_zero_barcode = 0;

        $stock_value_surplus_parts      = 0;
        $stock_value_ok_parts           = 0;
        $stock_value_low_parts          = 0;
        $stock_value_critical_parts     = 0;
        $stock_value_error_parts        = 0;
        $stock_value_parts_zero_barcode = 0;


        $active_deliveries_surplus_parts      = 0;
        $active_deliveries_ok_parts           = 0;
        $active_deliveries_low_parts          = 0;
        $active_deliveries_critical_parts     = 0;
        $active_deliveries_error_parts        = 0;
        $active_deliveries_parts_zero_barcode = 0;


        $sql  =
            "SELECT count(*) AS num , sum(`Part Cost in Warehouse`*`Part Current On Hand Stock`) as stock_value ,sum(`Part Number Active Deliveries`) as next_deliveries , `Part Stock Status`  FROM `Part Dimension` WHERE `Part Status`='In Use'  GROUP BY `Part Stock Status`";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch()) {
            switch ($row['Part Stock Status']) {
                case 'Surplus':
                    $number_surplus_parts            = $row['num'];
                    $stock_value_surplus_parts       = $row['stock_value'];
                    $active_deliveries_surplus_parts = $row['next_deliveries'];

                    break;
                case 'Optimal':
                    $number_ok_parts            = $row['num'];
                    $stock_value_ok_parts       = $row['stock_value'];
                    $active_deliveries_ok_parts = $row['next_deliveries'];

                    break;
                case 'Low':
                    $number_low_parts            = $row['num'];
                    $stock_value_low_parts       = $row['stock_value'];
                    $active_deliveries_low_parts = $row['next_deliveries'];

                    break;
                case 'Critical':
                    $number_critical_parts            = $row['num'];
                    $stock_value_critical_parts       = $row['stock_value'];
                    $active_deliveries_critical_parts = $row['next_deliveries'];

                    break;
                case 'Out_Of_Stock':
                    $number_parts_zero_barcode            = $row['num'];
                    $stock_value_parts_zero_barcode       = $row['stock_value'];
                    $active_deliveries_parts_zero_barcode = $row['next_deliveries'];

                    break;
                case 'Error':
                    $number_error_parts            = $row['num'];
                    $stock_value_error_parts       = $row['stock_value'];
                    $active_deliveries_error_parts = $row['next_deliveries'];

                    break;
            }
        }

        $this->fast_update(
            array(


                'Account Active Parts Stock Surplus Number'  => $number_surplus_parts,
                'Account Active Parts Stock OK Number'       => $number_ok_parts,
                'Account Active Parts Stock Low Number'      => $number_low_parts,
                'Account Active Parts Stock Critical Number' => $number_critical_parts,
                'Account Active Parts Stock Zero Number'     => $number_parts_zero_barcode,
                'Account Active Parts Stock Error Number'    => $number_error_parts,

                'Account Active Parts Stock Surplus Stock Value'  => $stock_value_surplus_parts,
                'Account Active Parts Stock OK Stock Value'       => $stock_value_ok_parts,
                'Account Active Parts Stock Low Stock Value'      => $stock_value_low_parts,
                'Account Active Parts Stock Critical Stock Value' => $stock_value_critical_parts,
                'Account Active Parts Stock Error Stock Value'    => $stock_value_error_parts,
                'Account Active Parts Stock Zero Stock Value'     => $stock_value_parts_zero_barcode,


                'Account Active Parts Stock Surplus Deliveries Number'  => $active_deliveries_surplus_parts,
                'Account Active Parts Stock OK Deliveries Number'       => $active_deliveries_ok_parts,
                'Account Active Parts Stock Low Deliveries Number'      => $active_deliveries_low_parts,
                'Account Active Parts Stock Critical Deliveries Number' => $active_deliveries_critical_parts,
                'Account Active Parts Stock Error Deliveries Number'    => $active_deliveries_error_parts,
                'Account Active Parts Stock Zero Deliveries Number'     => $active_deliveries_parts_zero_barcode,


            ), 'Account Data'
        );

        $number_surplus_parts      = 0;
        $number_ok_parts           = 0;
        $number_low_parts          = 0;
        $number_critical_parts     = 0;
        $number_error_parts        = 0;
        $number_parts_zero_barcode = 0;

        $stock_value_surplus_parts      = 0;
        $stock_value_ok_parts           = 0;
        $stock_value_low_parts          = 0;
        $stock_value_critical_parts     = 0;
        $stock_value_error_parts        = 0;
        $stock_value_parts_zero_barcode = 0;


        $active_deliveries_surplus_parts      = 0;
        $active_deliveries_ok_parts           = 0;
        $active_deliveries_low_parts          = 0;
        $active_deliveries_critical_parts     = 0;
        $active_deliveries_error_parts        = 0;
        $active_deliveries_parts_zero_barcode = 0;


        $sql   = "select count(*) as num from `Supplier Production Dimension`";
        $stmt2 = $this->db->prepare($sql);
        $stmt2->execute();
        if ($row2 = $stmt2->fetch()) {
            if ($row2['num'] > 0) {
                $sql =
                    "SELECT count(*) AS num , sum(`Part Cost in Warehouse`*`Part Current On Hand Stock`) as stock_value ,sum(`Part Number Active Deliveries`) as next_deliveries , `Part Stock Status`  FROM `Part Dimension` WHERE `Part Status`='In Use' and  `Part Production`='Yes'  GROUP BY `Part Stock Status`";

                $stmt = $this->db->prepare($sql);
                $stmt->execute();
                while ($row = $stmt->fetch()) {
                    switch ($row['Part Stock Status']) {
                        case 'Surplus':
                            $number_surplus_parts            += $row['num'];
                            $stock_value_surplus_parts       += $row['stock_value'];
                            $active_deliveries_surplus_parts += $row['next_deliveries'];

                            break;
                        case 'Optimal':
                            $number_ok_parts            += $row['num'];
                            $stock_value_ok_parts       += $row['stock_value'];
                            $active_deliveries_ok_parts += $row['next_deliveries'];

                            break;
                        case 'Low':
                            $number_low_parts            += $row['num'];
                            $stock_value_low_parts       += $row['stock_value'];
                            $active_deliveries_low_parts += $row['next_deliveries'];

                            break;
                        case 'Critical':
                            $number_critical_parts            += $row['num'];
                            $stock_value_critical_parts       += $row['stock_value'];
                            $active_deliveries_critical_parts += $row['next_deliveries'];

                            break;
                        case 'Out_Of_Stock':
                            $number_parts_zero_barcode            += $row['num'];
                            $stock_value_parts_zero_barcode       += $row['stock_value'];
                            $active_deliveries_parts_zero_barcode += $row['next_deliveries'];

                            break;
                        case 'Error':
                            $number_error_parts            += $row['num'];
                            $stock_value_error_parts       += $row['stock_value'];
                            $active_deliveries_error_parts += $row['next_deliveries'];

                            break;
                    }
                }


            }
        }
        $this->fast_update(
            array(


                'Account Active Production Parts Stock Surplus Number'  => $number_surplus_parts,
                'Account Active Production Parts Stock OK Number'       => $number_ok_parts,
                'Account Active Production Parts Stock Low Number'      => $number_low_parts,
                'Account Active Production Parts Stock Critical Number' => $number_critical_parts,
                'Account Active Production Parts Stock Zero Number'     => $number_parts_zero_barcode,
                'Account Active Production Parts Stock Error Number'    => $number_error_parts,

                'Account Active Production Parts Stock Surplus Stock Value'  => $stock_value_surplus_parts,
                'Account Active Production Parts Stock OK Stock Value'       => $stock_value_ok_parts,
                'Account Active Production Parts Stock Low Stock Value'      => $stock_value_low_parts,
                'Account Active Production Parts Stock Critical Stock Value' => $stock_value_critical_parts,
                'Account Active Production Parts Stock Error Stock Value'    => $stock_value_error_parts,
                'Account Active Production Parts Stock Zero Stock Value'     => $stock_value_parts_zero_barcode,


                'Account Active Production Parts Stock Surplus Deliveries Number'  => $active_deliveries_surplus_parts,
                'Account Active Production Parts Stock OK Deliveries Number'       => $active_deliveries_ok_parts,
                'Account Active Production Parts Stock Low Deliveries Number'      => $active_deliveries_low_parts,
                'Account Active Production Parts Stock Critical Deliveries Number' => $active_deliveries_critical_parts,
                'Account Active Production Parts Stock Error Deliveries Number'    => $active_deliveries_error_parts,
                'Account Active Production Parts Stock Zero Deliveries Number'     => $active_deliveries_parts_zero_barcode,


            ), 'Account Data'
        );


        include_once 'utils/send_zqm_message.class.php';
        send_zqm_message(
            json_encode(
                array(
                    'channel'  => 'real_time.'.strtolower($this->get('Account Code')),
                    'sections' => array(
                        array(
                            'section' => 'dashboard',

                            'update_metadata' => array(
                                'class_html' => array(
                                    'Active_Parts_Stock_Surplus_Number'             => $this->get('Active Parts Stock Surplus Number'),
                                    'Active_Parts_Stock_Surplus_Stock_Value_Minify' => $this->get('Active Parts Stock Surplus Stock Value Minify'),
                                    'Active_Parts_Stock_Surplus_Deliveries_Number'  => $this->get('Active Parts Stock Surplus Deliveries Number'),

                                    'Active_Parts_Stock_OK_Number'             => $this->get('Active Parts Stock OK Number'),
                                    'Active_Parts_Stock_OK_Stock_Value_Minify' => $this->get('Active Parts Stock OK Stock Value Minify'),
                                    'Active_Parts_Stock_OK_Deliveries_Number'  => $this->get('Active Parts Stock OK Deliveries Number'),

                                    'Active_Parts_Stock_Low_Number'             => $this->get('Active Parts Stock Low Number'),
                                    'Active_Parts_Stock_Low_Stock_Value_Minify' => $this->get('Active Parts Stock Low Stock Value Minify'),
                                    'Active_Parts_Stock_Low_Deliveries_Number'  => $this->get('Active Parts Stock Low Deliveries Number'),

                                    'Active_Parts_Stock_Critical_Number'             => $this->get('Active Parts Stock Critical Number'),
                                    'Active_Parts_Stock_Critical_Stock_Value_Minify' => $this->get('Active Parts Stock Critical Stock Value Minify'),
                                    'Active_Parts_Stock_Critical_Deliveries_Number'  => $this->get('Active Parts Stock Critical Deliveries Number'),

                                    'Active_Parts_Stock_Zero_Number'             => $this->get('Active Parts Stock Zero Number'),
                                    'Active_Parts_Stock_Zero_Stock_Value_Minify' => $this->get('Active Parts Stock Zero Stock Value Minify'),
                                    'Active_Parts_Stock_Zero_Deliveries_Number'  => $this->get('Active Parts Stock Zero Deliveries Number'),


                                )
                            )

                        )

                    ),


                )
            )
        );


    }

    function create_supplier($data) {


        include_once 'class.Supplier.php';


        $this->new_employee = false;

        $data['editor'] = $this->editor;


        if (!array_key_exists('Supplier Code', $data) or $data['Supplier Code'] == '') {
            $this->error = true;
            $this->msg   = 'error, no supplier code';

            return;
        }


        $country_code = $data['Supplier Contact Address country'];
        if (strlen($country_code) == 3) {
            include_once 'class.Country.php';
            $country      = new Country('code', $country_code);
            $country_code = $country->get('Country 2 Alpha Code');

        }


        $address_fields = array(
            'Address Recipient'            => $data['Supplier Main Contact Name'],
            'Address Organization'         => $data['Supplier Company Name'],
            'Address Line 1'               => '',
            'Address Line 2'               => '',
            'Address Sorting Code'         => '',
            'Address Postal Code'          => '',
            'Address Dependent Locality'   => '',
            'Address Locality'             => '',
            'Address Administrative Area'  => '',
            'Address Country 2 Alpha Code' => $country_code,

        );
        unset($data['Supplier Contact Address Country']);

        if (isset($data['Supplier Contact Address addressLine1'])) {
            $address_fields['Address Line 1'] = $data['Supplier Contact Address addressLine1'];
            unset($data['Supplier Contact Address addressLine1']);
        }
        if (isset($data['Supplier Contact Address addressLine2'])) {
            $address_fields['Address Line 2'] = $data['Supplier Contact Address addressLine2'];
            unset($data['Supplier Contact Address addressLine2']);
        }
        if (isset($data['Supplier Contact Address sortingCode'])) {
            $address_fields['Address Sorting Code'] = $data['Supplier Contact Address sortingCode'];
            unset($data['Supplier Contact Address sortingCode']);
        }
        if (isset($data['Supplier Contact Address postalCode'])) {
            $address_fields['Address Postal Code'] = $data['Supplier Contact Address postalCode'];
            unset($data['Supplier Contact Address postalCode']);
        }

        if (isset($data['Supplier Contact Address dependentLocality'])) {
            $address_fields['Address Dependent Locality'] = $data['Supplier Contact Address dependentLocality'];
            unset($data['Supplier Contact Address dependentLocality']);
        }

        if (isset($data['Supplier Contact Address locality'])) {
            $address_fields['Address Locality'] = $data['Supplier Contact Address locality'];
            unset($data['Supplier Contact Address locality']);
        }

        if (isset($data['Supplier Contact Address administrativeArea'])) {
            $address_fields['Address Administrative Area'] = $data['Supplier Contact Address administrativeArea'];
            unset($data['Supplier Contact Address administrativeArea']);
        }

        //print_r($address_fields);
        // print_r($data);

        //exit;

        $supplier = new Supplier('new', $data, $address_fields);

        if ($supplier->id) {
            $this->new_supplier_msg = $supplier->msg;

            if ($supplier->new) {
                $this->new_supplier = true;
                $this->update_suppliers_data();
            } else {
                $this->error = true;
                $this->msg   = $supplier->msg;

            }

            return $supplier;
        } else {
            $this->error = true;
            $this->msg   = $supplier->msg;
        }
    }

    function update_suppliers_data() {
        $number_suppliers     = 0;
        $number_agents        = 0;

        $sql = "SELECT count(*) AS num FROM `Supplier Dimension` WHERE `Supplier Type`!='Archived'";
        if ($row = $this->db->query($sql)->fetch()) {
            $number_suppliers = $row['num'];
        }
        $sql = sprintf('SELECT count(*) AS num FROM `Agent Dimension` ');
        if ($row = $this->db->query($sql)->fetch()) {
            $number_agents = $row['num'];
        }

        $sql =
            'SELECT count(*) AS num,ANY_VALUE(`Supplier Production Supplier Key`) as production_supplier_key FROM `Supplier Production Dimension` LEFT JOIN `Supplier Dimension` ON (`Supplier Key`=`Supplier Production Supplier Key`) WHERE `Supplier Type`!="Archived" ';
        if ($row = $this->db->query($sql)->fetch()) {
            $number_manufacturers = $row['num'];
            if ($number_manufacturers == 1) {
                $this->fast_update_json_field('Account Properties', 'production_supplier_key', $row['production_supplier_key'], 'Account Data');

            }
        }


        $this->fast_update(
            array(
                'Account Suppliers' => $number_suppliers,
                'Account Agents'    => $number_agents,
                //    'Account Manufacturers' => $number_manufacturers
            )
        );


    }

    function create_agent($data) {


        $this->new_employee = false;

        $data['editor'] = $this->editor;


        if (!array_key_exists('Agent Code', $data) or $data['Agent Code'] == '') {
            $this->error = true;
            $this->msg   = 'error, no agent code';

            return;
        }


        $country_code = $data['Agent Contact Address country'];
        if (strlen($country_code) == 3) {
            include_once 'class.Country.php';
            $country      = new Country('code', $country_code);
            $country_code = $country->get('Country 2 Alpha Code');

        }


        $address_fields = array(
            'Address Recipient'            => $data['Agent Main Contact Name'],
            'Address Organization'         => $data['Agent Company Name'],
            'Address Line 1'               => '',
            'Address Line 2'               => '',
            'Address Sorting Code'         => '',
            'Address Postal Code'          => '',
            'Address Dependent Locality'   => '',
            'Address Locality'             => '',
            'Address Administrative Area'  => '',
            'Address Country 2 Alpha Code' => $country_code,

        );
        unset($data['Agent Contact Address country']);

        if (isset($data['Agent Contact Address addressLine1'])) {
            $address_fields['Address Line 1'] = $data['Agent Contact Address addressLine1'];
            unset($data['Agent Contact Address addressLine1']);
        }
        if (isset($data['Agent Contact Address addressLine2'])) {
            $address_fields['Address Line 2'] = $data['Agent Contact Address addressLine2'];
            unset($data['Agent Contact Address addressLine2']);
        }
        if (isset($data['Agent Contact Address sortingCode'])) {
            $address_fields['Address Sorting Code'] = $data['Agent Contact Address sortingCode'];
            unset($data['Agent Contact Address sortingCode']);
        }
        if (isset($data['Agent Contact Address postalCode'])) {
            $address_fields['Address Postal Code'] = $data['Agent Contact Address postalCode'];
            unset($data['Agent Contact Address postalCode']);
        }

        if (isset($data['Agent Contact Address dependentLocality'])) {
            $address_fields['Address Dependent Locality'] = $data['Agent Contact Address dependentLocality'];
            unset($data['Agent Contact Address dependentLocality']);
        }

        if (isset($data['Agent Contact Address locality'])) {
            $address_fields['Address Locality'] = $data['Agent Contact Address locality'];
            unset($data['Agent Contact Address locality']);
        }

        if (isset($data['Agent Contact Address administrativeArea'])) {
            $address_fields['Address Administrative Area'] = $data['Agent Contact Address administrativeArea'];
            unset($data['Agent Contact Address administrativeArea']);
        }


        //exit;

        $agent = new Agent('new', $data, $address_fields);


        if ($agent->id) {
            $this->new_agent_msg = $agent->msg;

            if ($agent->new) {
                $this->new_agent = true;
                $this->update_suppliers_data();
            } else {
                $this->error = true;
                $this->msg   = $agent->msg;

            }

            return $agent;
        } else {
            $this->error = true;
            $this->msg   = $agent->msg;
        }
    }

    function create_manufacture_task($data) {
        $this->new_manufacture_task = false;

        $data['editor'] = $this->editor;


        if (!isset($data['Manufacture Task From'])) {
            $data['Manufacture Task From'] = gmdate('Y-m-d H:i:s');
        }
        if (isset($data['Manufacture Task Lower Target Per Hour'])) {

            if ($data['Manufacture Task Lower Target Per Hour'] == 0) {
                $this->error = true;
                $this->msg   = _("Lower target per hour can't be zero");

                return false;
            } elseif ($data['Manufacture Task Lower Target Per Hour'] < 0) {
                $this->error = true;
                $this->msg   = _("Lower target per hour can't be negative");

                return false;
            }

            $data['Manufacture Task Lower Target'] = 3600 / $data['Manufacture Task Lower Target Per Hour'];
            unset($data['Manufacture Task Lower Target Per Hour']);
        }

        if (isset($data['Manufacture Task Upper Target Per Hour'])) {

            if ($data['Manufacture Task Upper Target Per Hour'] == 0) {
                $this->error = true;
                $this->msg   = _("Upper target per hour can't be zero");

                return false;
            } elseif ($data['Manufacture Task Upper Target Per Hour'] < 0) {
                $this->error = true;
                $this->msg   = _("Upper target per hour can't be negative");

                return false;
            }

            $data['Manufacture Task Upper Target'] = 3600 / $data['Manufacture Task Upper Target Per Hour'];
            unset($data['Manufacture Task Upper Target Per Hour']);
        }


        $manufacture_task = new Manufacture_Task('find', $data, 'create');

        if ($manufacture_task->id) {
            $this->new_manufacture_task_msg = $manufacture_task->msg;

            if ($manufacture_task->new) {
                $this->new_manufacture_task = true;

                return $manufacture_task;
            } else {
                $this->error = true;
                if ($manufacture_task->found) {
                    $this->msg = _('Duplicated manufacture task name');
                } else {
                    $this->msg = 'Error '.$manufacture_task->msg;
                }
            }

            return false;
        } else {
            $this->error = true;
            $this->msg   = 'Error '.$manufacture_task->msg;

            return false;
        }
    }

    function get_field_label($field) {

        switch ($field) {
            case 'Account Stores':
                $label = _('stores');
                break;
            case 'Account Websites':
                $label = _('websites');
                break;
            case 'Account Products':
                $label = _('products');
                break;
            case 'Account Customers':
                $label = _('customers');
                break;
            case 'Account Invoices':
                $label = _('invoices');
                break;
            case 'Account Order Transactions':
                $label = _("Order's Items");
                break;
            case 'Account Suppliers Terms and Conditions':
                $label = _('Purchases Terms & Conditions');
                break;
            case 'Account Currency':
                $label = _('currency');
                break;
            case 'Account Timezone':
                $label = _('timezone');
                break;
            case 'Account Locale':
                $label = _('language');
                break;
            case 'Account Name':
                $label = _('organization name');
                break;
            case 'Account Label Signature':
                $label = _('label signature');
                break;
            default:
                $label = $field;
        }

        return $label;

    }

    function create_data_sets($data) {

        $data_set = new Data_Sets('find', $data, 'create');

        return $data_set;
    }

    function create_category($raw_data) {

        include_once 'class.Category.php';


        if (!isset($raw_data['Category Label']) or $raw_data['Category Label'] == '') {
            $raw_data['Category Label'] = $raw_data['Category Code'];
        }

        if (!isset($raw_data['Category Subject']) or $raw_data['Category Subject'] == '') {
            $raw_data['Category Subject'] = $raw_data['Category Scope'];
        }


        $data = array(
            'Category Code'           => $raw_data['Category Code'],
            'Category Label'          => $raw_data['Category Label'],
            'Category Scope'          => $raw_data['Category Scope'],
            'Category Subject'        => $raw_data['Category Subject'],
            'Category Can Have Other' => 'No',
            'Category Locked'         => 'No',
            'Category Branch Type'    => 'Root',
            'editor'                  => $this->editor

        );

        if (isset($raw_data['Category Store Key'])) {
            $data['Category Store Key'] = $raw_data['Category Store Key'];
        }

        if (isset($raw_data['Category Warehouse Key'])) {
            $data['Category Warehouse Key'] = $raw_data['Category Warehouse Key'];
        }

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

    }

    function update_inventory_dispatched_data($interval, $this_year = true, $last_year = true) {


        include_once 'utils/date_functions.php';
        list($db_interval, $from_date, $to_date, $from_date_1yb, $to_date_1yb) = calculate_interval_dates($this->db, $interval);


        if ($this_year) {

            $inventory_dispatched_data = $this->get_inventory_dispatch_data($from_date, $to_date);


            $data_to_update = array(

                "Account $db_interval Acc Distinct Parts Dispatched" => $inventory_dispatched_data['distinct_parts_dispatched'],


            );


            $this->fast_update($data_to_update, 'Account Data');
            //  exit();
        }

        if ($from_date_1yb and $last_year) {


            $inventory_dispatched_data = $this->get_inventory_dispatch_data($from_date_1yb, $to_date_1yb);

            $data_to_update = array(
                "Account $db_interval Acc 1YB Distinct Parts Dispatched" => $inventory_dispatched_data['distinct_parts_dispatched'],


            );


            $this->fast_update($data_to_update, 'Account Data');


        }


    }

    function get_inventory_dispatch_data($from_date, $to_date) {

        $inventory_dispatch_data = array(

            'distinct_parts_dispatched' => 0

        );


        $sql = sprintf(
            " SELECT COUNT(distinct `Part SKU`) AS distinct_parts_dispatched  FROM `Inventory Transaction Fact` WHERE  `Inventory Transaction Type` = 'Sale'   %s %s ", ($from_date ? sprintf('and `Date`>%s', prepare_mysql($from_date)) : ''),
            ($to_date ? sprintf('and `Date`<%s', prepare_mysql($to_date)) : '')
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $inventory_dispatch_data['distinct_parts_dispatched'] = $row['distinct_parts_dispatched'];


            }
        }

        return $inventory_dispatch_data;


    }

    function update_sales_from_invoices($interval, $this_year = true, $last_year = true) {


        include_once 'utils/date_functions.php';
        list($db_interval, $from_date, $to_date, $from_date_1yb, $to_date_1yb) = calculate_interval_dates($this->db, $interval);


        // print "$db_interval  $from_date, $to_date, $from_date_1yb, $to_date_1yb\n";

        if ($this_year) {

            $sales_data = $this->get_sales_data($from_date, $to_date);


            $data_to_update = array(
                "Account $db_interval Acc Invoiced Discount Amount" => round($sales_data['discount_amount'], 2),
                "Account $db_interval Acc Invoiced Amount"          => round($sales_data['amount'], 2),
                "Account $db_interval Acc Invoices"                 => $sales_data['invoices'],
                "Account $db_interval Acc Refunds"                  => $sales_data['refunds'],
                "Account $db_interval Acc Replacements"             => $sales_data['replacements'],
                "Account $db_interval Acc Delivery Notes"           => $sales_data['deliveries'],
                "Account $db_interval Acc Profit"                   => round($sales_data['profit'], 2),
                "Account $db_interval Acc Customers"                => $sales_data['customers'],
                "Account $db_interval Acc Repeat Customers"         => $sales_data['repeat_customers'],


            );


            // $key = '_acc_Sales_'.$this->get('Account Code');
            // $this->redis->hSet(
            //     $key, preg_replace('/\s/','_',$interval), json_encode(
            //             $sales_data
            //         )
            // );

            $this->fast_update($data_to_update, 'Account Data');
            //  exit();
        }

        if ($from_date_1yb and $last_year) {


            $sales_data = $this->get_sales_data($from_date_1yb, $to_date_1yb);

            $data_to_update = array(
                "Account $db_interval Acc 1YB Invoiced Discount Amount" => round($sales_data['discount_amount'], 2),
                "Account $db_interval Acc 1YB Invoiced Amount"          => round($sales_data['amount'], 2),
                "Account $db_interval Acc 1YB Invoices"                 => $sales_data['invoices'],
                "Account $db_interval Acc 1YB Refunds"                  => $sales_data['refunds'],
                "Account $db_interval Acc 1YB Replacements"             => $sales_data['replacements'],
                "Account $db_interval Acc 1YB Delivery Notes"           => $sales_data['deliveries'],
                "Account $db_interval Acc 1YB Profit"                   => round($sales_data['profit'], 2),
                "Account $db_interval Acc 1YB Customers"                => $sales_data['customers'],
                "Account $db_interval Acc 1YB Repeat Customers"         => $sales_data['repeat_customers'],

            );


            $this->fast_update($data_to_update, 'Account Data');


        }

        if (in_array(
            $db_interval, [
                            'Total',
                            'Year To Date',
                            'Quarter To Date',
                            'Week To Date',
                            'Month To Date',
                            'Today'
                        ]
        )) {

            $this->update(['Account Acc To Day Updated' => gmdate('Y-m-d H:i:s')], 'no_history');

        } elseif (in_array(
            $db_interval, [
                            '1 Year',
                            '1 Month',
                            '1 Week',
                            '1 Quarter'
                        ]
        )) {

            $this->update(['Account Acc Ongoing Intervals Updated' => gmdate('Y-m-d H:i:s')], 'no_history');
        } elseif (in_array(
            $db_interval, [
                            'Last Month',
                            'Last Week',
                            'Yesterday',
                            'Last Year'
                        ]
        )) {

            $this->update(['Account Acc Previous Intervals Updated' => gmdate('Y-m-d H:i:s')], 'no_history');
        }

    }

    function update_account_overview($redis) {

        $account_data = [
            'name' => $this->data['Account Name']
        ];

        $key = '_acc_'.$this->get('Account Code');

        $redis->hSet($key, 'code', $this->data['Account Code']);
        $redis->hSet($key, 'name', $this->data['Account Name']);

        $redis->hSet(
            $key, 'ytd', json_encode(
                    [
                        'sales' => $this->get('Account Year To Day Acc Invoiced Amount')
                    ]
                )
        );


        print_r(
            [
                'sales' => $this->get('Account Year To Day Acc Invoiced Amount')
            ]
        );

    }


    function get_sales_data($from_date, $to_date) {

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
        // print_r($sales_data);


        $sql = sprintf(
            "SELECT count(DISTINCT `Invoice Customer Key`)  AS customers,sum(if(`Invoice Type`='Invoice',1,0))  AS invoices, sum(if(`Invoice Type`='Refund',1,0))  AS refunds  ,sum(`Invoice Items Discount Amount`*`Invoice Currency Exchange`) AS dc_discounts,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) dc_net  ,sum(`Invoice Total Profit`*`Invoice Currency Exchange`) AS dc_profit FROM `Invoice Dimension` WHERE TRUE %s %s",
            ($from_date ? sprintf('and `Invoice Date`>%s', prepare_mysql($from_date)) : ''), ($to_date ? sprintf('and `Invoice Date`<%s', prepare_mysql($to_date)) : '')

        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $sales_data['customers'] = $row['customers'];
                if ($row['customers'] > 0) {

                    $sales_data['discount_amount'] = $row['dc_discounts'];
                    $sales_data['amount']          = $row['dc_net'];
                    $sales_data['profit']          = $row['dc_profit'];
                    $sales_data['invoices']        = $row['invoices'];
                    $sales_data['refunds']         = $row['refunds'];
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print $sql;
            exit;
        }


        $sql = sprintf(
            "SELECT count(*)  AS replacements FROM `Delivery Note Dimension` WHERE `Delivery Note Type` IN ('Replacement & Shortages','Replacement','Shortages') AND TRUE %s %s", ($from_date ? sprintf('and `Delivery Note Date`>%s', prepare_mysql($from_date)) : ''),
            ($to_date ? sprintf('and `Delivery Note Date`<%s', prepare_mysql($to_date)) : '')
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $sales_data['replacements'] = $row['replacements'];


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "SELECT count(*)  AS delivery_notes FROM `Delivery Note Dimension` WHERE `Delivery Note Type` IN ('Order')  %s %s", ($from_date ? sprintf(
            'and `Delivery Note Date`>%s', prepare_mysql($from_date)
        ) : ''), ($to_date ? sprintf(
            'and `Delivery Note Date`<%s', prepare_mysql($to_date)
        ) : '')

        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $sales_data['deliveries'] = $row['delivery_notes'];


            }
        }


        $sql = sprintf(
            " SELECT COUNT(*) AS repeat_customers FROM( SELECT count(*) AS invoices ,`Invoice Customer Key` FROM `Invoice Dimension` WHERE TRUE %s %s GROUP BY `Invoice Customer Key` HAVING invoices>1) AS tmp",
            ($from_date ? sprintf('and `Invoice Date`>%s', prepare_mysql($from_date)) : ''), ($to_date ? sprintf('and `Invoice Date`<%s', prepare_mysql($to_date)) : '')
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $sales_data['repeat_customers'] = $row['repeat_customers'];


            }
        }

        /*

        $sql = sprintf(
            " SELECT COUNT(distinct `Part SKU`) AS distinct_parts_dispatched  FROM `Inventory Transaction Fact` WHERE  `Inventory Transaction Type` = 'Sale'   %s %s ", ($from_date ? sprintf('and `Date`>%s', prepare_mysql($from_date)) : ''),
            ($to_date ? sprintf('and `Date`<%s', prepare_mysql($to_date)) : '')
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $sales_data['distinct_parts_dispatched'] = $row['distinct_parts_dispatched'];


            }
        }
        */

        return $sales_data;

    }

    function get_customers_data($from_date, $to_date): array {

        $customers_data = array(
            'new_customers' => 0,
        );


        $parameters=[];
        $sql          = "SELECT count(*)  AS new_customers from `Customer Dimension` where true  ";
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

    function update_previous_years_data() {

        foreach (range(1, 5) as $i) {
            $data_iy_ago = $this->get_sales_data(
                date('Y-01-01 00:00:00', strtotime('-'.$i.' year')), date('Y-01-01 00:00:00', strtotime('-'.($i - 1).' year'))
            );


            $data_inventory_iy_ago = $this->get_inventory_dispatch_data(
                date('Y-01-01 00:00:00', strtotime('-'.$i.' year')), date('Y-01-01 00:00:00', strtotime('-'.($i - 1).' year'))
            );


            $data_to_update = array(
                "Account $i Year Ago Invoiced Discount Amount"  => $data_iy_ago['discount_amount'],
                "Account $i Year Ago Invoiced Amount"           => $data_iy_ago['amount'],
                "Account $i Year Ago Invoices"                  => $data_iy_ago['invoices'],
                "Account $i Year Ago Refunds"                   => $data_iy_ago['refunds'],
                "Account $i Year Ago Replacements"              => $data_iy_ago['replacements'],
                "Account $i Year Ago Delivery Notes"            => $data_iy_ago['deliveries'],
                "Account $i Year Ago Profit"                    => $data_iy_ago['profit'],
                "Account $i Year Ago Distinct Parts Dispatched" => $data_inventory_iy_ago['distinct_parts_dispatched'],

            );

            $this->fast_update($data_to_update, 'Account Data');
        }

        $this->fast_update(['Account Acc Previous Intervals Updated' => gmdate('Y-m-d H:i:s')]);


    }

    function update_previous_quarters_data() {


        include_once 'utils/date_functions.php';

        foreach (range(1, 4) as $i) {
            $dates     = get_previous_quarters_dates($i);
            $dates_1yb = get_previous_quarters_dates($i + 4);


            $sales_data     = $this->get_sales_data(
                $dates['start'], $dates['end']
            );
            $sales_data_1yb = $this->get_sales_data(
                $dates_1yb['start'], $dates_1yb['end']
            );

            $inventory_dispatch_data     = $this->get_inventory_dispatch_data(
                $dates['start'], $dates['end']
            );
            $inventory_dispatch_data_1yb = $this->get_inventory_dispatch_data(
                $dates_1yb['start'], $dates_1yb['end']
            );

            $data_to_update = array(
                "Account $i Quarter Ago Invoiced Discount Amount"  => $sales_data['discount_amount'],
                "Account $i Quarter Ago Invoiced Amount"           => $sales_data['amount'],
                "Account $i Quarter Ago Invoices"                  => $sales_data['invoices'],
                "Account $i Quarter Ago Refunds"                   => $sales_data['refunds'],
                "Account $i Quarter Ago Replacements"              => $sales_data['replacements'],
                "Account $i Quarter Ago Delivery Notes"            => $sales_data['deliveries'],
                "Account $i Quarter Ago Profit"                    => $sales_data['profit'],
                "Account $i Quarter Ago Distinct Parts Dispatched" => $inventory_dispatch_data['distinct_parts_dispatched'],


                "Account $i Quarter Ago 1YB Invoiced Discount Amount"  => $sales_data_1yb['discount_amount'],
                "Account $i Quarter Ago 1YB Invoiced Amount"           => $sales_data_1yb['amount'],
                "Account $i Quarter Ago 1YB Invoices"                  => $sales_data_1yb['invoices'],
                "Account $i Quarter Ago 1YB Refunds"                   => $sales_data_1yb['refunds'],
                "Account $i Quarter Ago 1YB Replacements"              => $sales_data_1yb['replacements'],
                "Account $i Quarter Ago 1YB Delivery Notes"            => $sales_data_1yb['deliveries'],
                "Account $i Quarter Ago 1YB Profit"                    => $sales_data_1yb['profit'],
                "Account $i Quarter Ago 1YB Distinct Parts Dispatched" => $inventory_dispatch_data_1yb['distinct_parts_dispatched'],

            );
            $this->fast_update($data_to_update, 'Account Data');
        }

        $this->fast_update(['Account Acc Previous Intervals Updated' => gmdate('Y-m-d H:i:s')]);


    }

    function create_timeseries($data, $fork_key = 0) {

        $data['Timeseries Parent']     = 'Account';
        $data['Timeseries Parent Key'] = $this->id;
        $data['editor']                = $this->editor;

        $timeseries = new Timeseries('find', $data, 'create');
        if ($timeseries->id) {
            require_once 'utils/date_functions.php';

            if ($this->data['Account Valid From'] != '') {
                $from = date('Y-m-d', strtotime($this->get('Valid From')));

            } else {
                $from = '';
            }
            $to = date('Y-m-d');


            $sql        = sprintf('DELETE FROM `Timeseries Record Dimension` WHERE `Timeseries Record Timeseries Key`=%d AND `Timeseries Record Date`<%s ', $timeseries->id, prepare_mysql($from));
            $update_sql = $this->db->prepare($sql);
            $update_sql->execute();
            if ($update_sql->rowCount()) {
                $timeseries->update(
                    array('Timeseries Updated' => gmdate('Y-m-d H:i:s')), 'no_history'
                );
            }

            $sql        = sprintf(
                'DELETE FROM `Timeseries Record Dimension` WHERE `Timeseries Record Timeseries Key`=%d AND `Timeseries Record Date`>%s ', $timeseries->id, prepare_mysql($to)
            );
            $update_sql = $this->db->prepare($sql);
            $update_sql->execute();
            if ($update_sql->rowCount()) {
                $timeseries->update(
                    array('Timeseries Updated' => gmdate('Y-m-d H:i:s')), 'no_history'
                );
            }

            if ($from and $to) {
                $this->update_timeseries_record($timeseries, $from, $to, $fork_key);
            }

            if ($timeseries->get('Timeseries Number Records') == 0) {
                $timeseries->update(
                    array('Timeseries Updated' => gmdate('Y-m-d H:i:s')), 'no_history'
                );
            }

        }

    }

    function update_timeseries_record($timeseries, $from, $to, $fork_key = false) {

        if ($timeseries->get('Type') == 'AccountSales') {

            $dates = date_frequency_range(
                $this->db, $timeseries->get('Timeseries Frequency'), $from, $to
            );

            if ($fork_key) {

                $sql = sprintf(
                    "UPDATE `Fork Dimension` SET `Fork State`='In Process' ,`Fork Operations Total Operations`=%d,`Fork Start Date`=NOW(),`Fork Result`=%d  WHERE `Fork Key`=%d ", count($dates), $timeseries->id, $fork_key
                );

                $this->db->exec($sql);
            }
            $index = 0;
            foreach ($dates as $date_frequency_period) {
                $index++;
                $sales_data = $this->get_sales_data($date_frequency_period['from'], $date_frequency_period['to']);
                $customers_data = $this->get_customers_data($date_frequency_period['from'], $date_frequency_period['to']);


                $_date      = gmdate('Y-m-d', strtotime($date_frequency_period['from'].' +0:00'));


                if ($customers_data['new_customers'] > 0  or $sales_data['invoices'] > 0 or $sales_data['refunds'] > 0 or $sales_data['customers'] > 0 or $sales_data['amount'] != 0 or $sales_data['dc_amount'] != 0 or $sales_data['profit'] != 0 or $sales_data['dc_profit'] != 0) {

                    list($timeseries_record_key, $date) = $timeseries->create_record(array('Timeseries Record Date' => $_date));

                    $sql = "UPDATE `Timeseries Record Dimension` SET
                    `Timeseries Record Integer A`=? ,`Timeseries Record Integer B`=? ,`Timeseries Record Integer C`=? ,`Timeseries Record Integer D`=? ,`Timeseries Record Float A`=? ,  `Timeseries Record Float B`=? ,`Timeseries Record Float C`=? ,`Timeseries Record Float D`=? ,`Timeseries Record Type`=?
                    WHERE `Timeseries Record Key`=?
                    ";



                    $stmt = $this->db->prepare($sql);
                    $stmt->execute(
                        array(
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

                        )
                    );


                    if ($stmt->rowCount() or $date == gmdate('Y-m-d')) {
                        $timeseries->fast_update(
                            [
                                'Timeseries Updated' => gmdate('Y-m-d H:i:s')
                            ]
                        );
                    }





                } else {
                    $sql = sprintf(
                        'DELETE FROM `Timeseries Record Dimension` WHERE `Timeseries Record Timeseries Key`=%d AND `Timeseries Record Date`=%s ', $timeseries->id, prepare_mysql($_date)
                    );

                    $update_sql = $this->db->prepare($sql);
                    $update_sql->execute();
                    if ($update_sql->rowCount()) {
                        $timeseries->update(
                            array(
                                'Timeseries Updated' => gmdate(
                                    'Y-m-d H:i:s'
                                )
                            ), 'no_history'
                        );

                    }

                }
                if ($fork_key) {
                    $skip_every = 1;
                    if ($index % $skip_every == 0) {
                        $sql = sprintf(
                            "UPDATE `Fork Dimension` SET `Fork Operations Done`=%d  WHERE `Fork Key`=%d ", $index, $fork_key
                        );
                        $this->db->exec($sql);

                    }

                }

                $date = gmdate('Y-m-d H:i:s');
                $sql  = 'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (?,?,?,?) ON DUPLICATE KEY UPDATE `Stack Last Update Date`=? ,`Stack Counter`=`Stack Counter`+1 ';
                $this->db->prepare($sql)->execute(
                    [
                        $date,
                        $date,
                        'timeseries_stats',
                        $timeseries->id,
                        $date,

                    ]
                );

            }

        }


        if ($fork_key) {

            $sql = sprintf(
                "UPDATE `Fork Dimension` SET `Fork State`='Finished' ,`Fork Finished Date`=NOW(),`Fork Operations Done`=%d,`Fork Result`=%d WHERE `Fork Key`=%d ", $index, $timeseries->id, $fork_key
            );

            $this->db->exec($sql);

        }

    }

    function update_orders() {


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

    function update_orders_in_basket_data() {

        $data = array(
            'in_basket' => array(
                'number'    => 0,
                'dc_amount' => 0
            ),
        );

        $sql = sprintf(
            "SELECT count(*) AS num ,ifnull(sum(`Order Total Net Amount`*`Order Currency Exchange`),0) AS dc_amount FROM `Order Dimension` WHERE  `Order State`='InBasket'  ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $data['in_basket']['number']    = $row['num'];
                $data['in_basket']['dc_amount'] = $row['dc_amount'];


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $data_to_update = array(
            'Account Orders In Basket Number' => $data['in_basket']['number'],
            'Account Orders In Basket Amount' => round($data['in_basket']['dc_amount'], 2)


        );


        $this->fast_update($data_to_update, 'Account Data');
    }

    function update_orders_in_process_data() {

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
            ),

        );
        $sql  = sprintf(
            'SELECT count(*) AS num, ifnull(sum(`Order Total Net Amount`*`Order Currency Exchange`),0) AS dc_amount FROM `Order Dimension` WHERE  `Order State` ="InProcess"  AND !`Order To Pay Amount`>0 '
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $data['in_process_paid']['number']    += $row['num'];
                $data['in_process_paid']['dc_amount'] += $row['dc_amount'];


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $sql = sprintf(
            'SELECT count(*) AS num,ifnull(sum(`Order Total Net Amount`*`Order Currency Exchange`),0) AS dc_amount FROM `Order Dimension` WHERE  `Order State`="InProcess"  AND `Order To Pay Amount`>0  '
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $data['in_process_not_paid']['number']    += $row['num'];
                $data['in_process_not_paid']['dc_amount'] += $row['dc_amount'];


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $data_to_update = array(
            'Account Orders In Process Paid Number'     => $data['in_process_paid']['number'],
            'Account Orders In Process Paid Amount'     => round($data['in_process_paid']['dc_amount'], 2),
            'Account Orders In Process Not Paid Number' => $data['in_process_not_paid']['number'],
            'Account Orders In Process Not Paid Amount' => round($data['in_process_not_paid']['dc_amount'], 2),
            'Account Orders In Process Number'          => $data['in_process_paid']['number'] + $data['in_process_not_paid']['number'],
            'Account Orders In Process Amount'          => round($data['in_process_paid']['dc_amount'] + $data['in_process_not_paid']['dc_amount'], 2)

        );


        $this->fast_update($data_to_update, 'Account Data');
    }

    function update_orders_in_warehouse_data() {

        $data = array(
            'warehouse'             => array(
                'number'    => 0,
                'dc_amount' => 0
            ),
            'warehouse_no_alerts'   => array(
                'number'    => 0,
                'dc_amount' => 0
            ),
            'warehouse_with_alerts' => array(
                'number'    => 0,
                'dc_amount' => 0
            ),
        );


        $sql = sprintf(
            "SELECT ifnull(sum(`Order Total Net Amount`*`Order Currency Exchange`),0) AS dc_amount FROM `Order Dimension` WHERE `Order State` ='InWarehouse' and `Order Delivery Note Alert`='No' "
        );
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $data['warehouse_no_alerts']['dc_amount'] = $row['dc_amount'];
            }
        }

        $sql = sprintf(
            "SELECT ifnull(sum(`Order Total Net Amount`*`Order Currency Exchange`),0) AS dc_amount FROM `Order Dimension` WHERE `Order State` ='InWarehouse' AND `Order Delivery Note Alert`='Yes'  "
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $data['warehouse_with_alerts']['dc_amount'] = $row['dc_amount'];
            }
        }


        $sql = sprintf(
            "SELECT count(*) AS num FROM `Order Dimension` WHERE  ( `Order State` ='InWarehouse'   and   `Order Delivery Note Alert`='No'  )   or `Order Replacements In Warehouse without Alerts`>0  "
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $data['warehouse_no_alerts']['number'] = $row['num'];
            }
        }

        $sql = sprintf(
            "SELECT count(*) AS num  FROM `Order Dimension` WHERE  ( `Order State` ='InWarehouse'   and   `Order Delivery Note Alert`='Yes'  )   or `Order Replacements In Warehouse with Alerts`>0  "
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $data['warehouse_with_alerts']['number'] = $row['num'];
            }
        }


        $data_to_update = array(
            'Account Orders In Warehouse Number'             => $data['warehouse_with_alerts']['number'] + $data['warehouse_no_alerts']['number'],
            'Account Orders In Warehouse Amount'             => round($data['warehouse_with_alerts']['dc_amount'] + $data['warehouse_no_alerts']['dc_amount'], 2),
            'Account Orders In Warehouse No Alerts Number'   => $data['warehouse_no_alerts']['number'],
            'Account Orders In Warehouse No Alerts Amount'   => round($data['warehouse_no_alerts']['dc_amount'], 2),
            'Account Orders In Warehouse With Alerts Number' => $data['warehouse_with_alerts']['number'],
            'Account Orders In Warehouse With Alerts Amount' => round($data['warehouse_with_alerts']['dc_amount'], 2)

        );


        $this->fast_update($data_to_update, 'Account Data');
    }

    function update_orders_packed_data() {

        $data = array(
            'packed' => array(
                'number'    => 0,
                'amount'    => 0,
                'dc_amount' => 0
            ),
        );


        $sql = sprintf(
            "SELECT count(*) AS num,ifnull(sum(`Order Total Net Amount`*`Order Currency Exchange`),0) AS dc_amount FROM `Order Dimension` WHERE   `Order State` ='Packed' "
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $data['packed']['number']    = $row['num'];
                $data['packed']['dc_amount'] = $row['dc_amount'];


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $data_to_update = array(
            'Account Orders Packed Number' => $data['packed']['number'],
            'Account Orders Packed Amount' => round($data['packed']['dc_amount'], 2)


        );
        $this->fast_update($data_to_update, 'Account Data');
    }


    function update_orders_packed_done_data() {

        $data = array(
            'packed' => array(
                'number'    => 0,
                'amount'    => 0,
                'dc_amount' => 0
            ),
        );


        $sql = sprintf(
            "SELECT count(*) AS num,ifnull(sum(`Order Total Net Amount`*`Order Currency Exchange`),0) AS dc_amount FROM `Order Dimension` WHERE   `Order State` ='PackedDone' "
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $data['packed']['number']    = $row['num'];
                $data['packed']['dc_amount'] = $row['dc_amount'];


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "SELECT count(*) AS num FROM `Order Dimension` WHERE `Order Replacement State` ='PackedDone'  "
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $data['packed']['number'] += $row['num'];


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $data_to_update = array(
            'Account Orders Packed Done Number' => $data['packed']['number'],
            'Account Orders Packed Done Amount' => round($data['packed']['dc_amount'], 2)


        );
        $this->fast_update($data_to_update, 'Account Data');
    }

    function update_orders_approved_data() {

        $data = array(
            'approved' => array(
                'number'    => 0,
                'dc_amount' => 0
            ),
        );


        $sql = sprintf(
            "SELECT count(*) AS num,ifnull(sum(`Order Total Net Amount`*`Order Currency Exchange`),0) AS dc_amount FROM `Order Dimension` WHERE   `Order State` ='Approved' "
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $data['approved']['number']    = $row['num'];
                $data['approved']['dc_amount'] = $row['dc_amount'];


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $data_to_update = array(
            'Account Orders Dispatch Approved Number' => $data['approved']['number'],
            'Account Orders Dispatch Approved Amount' => round($data['approved']['dc_amount'], 2)


        );
        $this->fast_update($data_to_update, 'Account Data');
    }

    function update_orders_dispatched() {

        $data = array(
            'dispatched' => array(
                'number'    => 0,
                'dc_amount' => 0
            ),


        );


        $sql = sprintf(
            "SELECT count(*) AS num,ifnull(sum(`Order Total Net Amount`*`Order Currency Exchange`),0) AS dc_amount FROM `Order Dimension` WHERE   `Order State` ='Dispatched' "
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $data['dispatched']['number']    = $row['num'];
                $data['dispatched']['dc_amount'] = $row['dc_amount'];


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $data_to_update = array(
            'Account Orders Dispatched Number' => $data['dispatched']['number'],
            'Account Orders Dispatched Amount' => round($data['dispatched']['dc_amount'], 2)


        );
        $this->fast_update($data_to_update, 'Account Data');
    }

    function update_orders_dispatched_today() {

        $data = array(
            'dispatched_today' => array(
                'number'    => 0,
                'amount'    => 0,
                'dc_amount' => 0
            ),


        );


        $sql = sprintf(
            "SELECT count(*) AS num,ifnull(sum(`Order Total Net Amount`*`Order Currency Exchange`),0) AS dc_amount FROM `Order Dimension` WHERE   `Order State` ='Dispatched' AND `Order Dispatched Date`>=%s ", prepare_mysql(gmdate('Y-m-d 00:00:00'))

        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $data['dispatched_today']['number']    = $row['num'];
                $data['dispatched_today']['dc_amount'] = $row['dc_amount'];


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        // todo do same as store


        $data_to_update = array(
            'Account Orders Dispatched Today Number' => $data['dispatched_today']['number'],
            'Account Orders Dispatched Today Amount' => round($data['dispatched_today']['dc_amount'], 2)


        );
        $this->fast_update($data_to_update, 'Account Data');
    }

    function update_orders_cancelled() {

        $data = array(

            'cancelled' => array(
                'number'    => 0,
                'dc_amount' => 0
            ),
        );


        $sql = sprintf(
            "SELECT count(*) AS num,ifnull(sum(`Order Total Net Amount`*`Order Currency Exchange`),0) AS dc_amount FROM `Order Dimension` WHERE   `Order State` ='Cancelled' "
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                $data['cancelled']['number']    = $row['num'];
                $data['cancelled']['dc_amount'] = $row['dc_amount'];


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $data_to_update = array(

            'Account Orders Cancelled Number' => $data['cancelled']['number'],
            'Account Orders Cancelled Amount' => round($data['cancelled']['dc_amount'], 2)

        );
        $this->fast_update($data_to_update, 'Account Data');
    }

    function update_customers_data() {

        $this->data['Account Contacts']                    = 0;
        $this->data['Account New Contacts']                = 0;
        $this->data['Account Contacts With Orders']        = 0;
        $this->data['Account Active Contacts']             = 0;
        $this->data['Account Losing Contacts']             = 0;
        $this->data['Account Lost Contacts']               = 0;
        $this->data['Account New Contacts With Orders']    = 0;
        $this->data['Account Active Contacts With Orders'] = 0;
        $this->data['Account Losing Contacts With Orders'] = 0;
        $this->data['Account Lost Contacts With Orders']   = 0;
        $this->data['Account Contacts Who Visit Website']  = 0;


        $sql = sprintf(
            "SELECT  sum(`Store Contacts`) as contacts, 
                sum(`Store New Contacts`) as new_contacts, 
                sum(`Store Contacts With Orders`) as contacts_with_orders, 
                sum(`Store Active Contacts`) as active, 
                sum(`Store Losing Contacts`) as losing, 
                sum(`Store Lost Contacts`) as lost, 
                sum(`Store New Contacts With Orders`) as new_contacts_with_orders, 
                sum(`Store Active Contacts With Orders`) as active_with_orders, 
                sum(`Store Losing Contacts With Orders`) as losing_with_orders, 
                sum(`Store Lost Contacts With Orders`) as lost_with_orders, 
                sum(`Store Contacts Who Visit Website`) as visitors

               
               from `Store Dimension`  "
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->data['Account Contacts']             = $row['contacts'];
                $this->data['Account New Contacts']         = $row['new_contacts'];
                $this->data['Account Contacts With Orders'] = $row['contacts_with_orders'];

                $this->data['Account Active Contacts'] = $row['active'];
                $this->data['Account Losing Contacts'] = $row['losing'];
                $this->data['Account Lost Contacts']   = $row['lost'];

                $this->data['Account New Contacts With Orders']    = $row['new_contacts_with_orders'];
                $this->data['Account Active Contacts With Orders'] = $row['active_with_orders'];
                $this->data['Account Losing Contacts With Orders'] = $row['losing_with_orders'];
                $this->data['Account Lost Contacts With Orders']   = $row['lost_with_orders'];
                $this->data['Account Contacts Who Visit Website']  = $row['visitors'];


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $sql = sprintf(
            "UPDATE `Account Data` SET
                     `Account Contacts`=%d,
                     `Account New Contacts`=%d,
                     `Account Active Contacts`=%d ,
                     `Account Losing Contacts`=%d ,
                     `Account Lost Contacts`=%d ,

                     `Account Contacts With Orders`=%d,
                     `Account New Contacts With Orders`=%d,
                     `Account Active Contacts With Orders`=%d,
                     `Account Losing Contacts With Orders`=%d,
                     `Account Lost Contacts With Orders`=%d,
                     `Account Contacts Who Visit Website`=%d
                     WHERE `Account Key`=%d  ", $this->data['Account Contacts'], $this->data['Account New Contacts'], $this->data['Account Active Contacts'], $this->data['Account Losing Contacts'], $this->data['Account Lost Contacts'],

            $this->data['Account Contacts With Orders'], $this->data['Account New Contacts With Orders'], $this->data['Account Active Contacts With Orders'], $this->data['Account Losing Contacts With Orders'], $this->data['Account Lost Contacts With Orders'],
            $this->data['Account Contacts Who Visit Website'],

            $this->id
        );

        //  print "$sql\n";


        $this->db->exec($sql);

    }

    /**
     * @param $data
     *
     * @return \Store
     */
    function set_up_clocking_machine($data) {


        include 'keyring/dns.php';
        include 'keyring/au_deploy_conf.php';

        $box_db = get_box_db();

        $this->new_clocking_machine = false;


        $sql = "SELECT `Box Key`,`Box Aurora Account Code`,`Box Model`  FROM box.`Box Dimension`  WHERE  `Box ID`=?    ";

        $stmt = $box_db->prepare($sql);
        $stmt->execute(
            array($data['Clocking Machine Serial Number'])
        );
        if ($row = $stmt->fetch()) {
            if ($row['Box Aurora Account Code'] == '' or true) {


                $sql = 'update  box.`Box Dimension` set `Box Aurora Account Code`=? where `Box Key`=?  ';

                $stmt = $box_db->prepare($sql);
                $stmt->execute(
                    array(
                        $this->get('Account Code'),
                        $row['Box Key']
                    )
                );


                $data['Clocking Machine Box Key'] = $row['Box Key'];
                $data['Clocking Machine Model']   = $row['Box Model'];


                if ($data['Clocking Machine WiFi Password'] != 'Box Key') {


                    $cipher_method           = 'aes-128-ctr';
                    $enc_key                 = openssl_digest(SHARED_KEY, 'SHA256', true);
                    $enc_iv                  = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher_method));
                    $wifi_encrypted_password = openssl_encrypt($data['Clocking Machine WiFi Password'], $cipher_method, $enc_key, 0, $enc_iv)."::".bin2hex($enc_iv);
                    unset($token, $cipher_method, $enc_key, $enc_iv);


                    /*
                                        list($wifi_encrypted_password, $enc_iv) = explode("::", $wifi_encrypted_password);
                                        $cipher_method = 'aes-128-ctr';
                                        $enc_key = openssl_digest(SHARED_KEY, 'SHA256', TRUE);
                                        $token = openssl_decrypt($wifi_encrypted_password, $cipher_method, $enc_key, 0, hex2bin($enc_iv));
                                        unset($wifi_encrypted_password, $cipher_method, $enc_key, $enc_iv);
                                        print $token."\n";
                    */

                } else {
                    $wifi_encrypted_password = '';
                }
                unset($data['Clocking Machine WiFi Password']);


                $settings = array(
                    'SSID'       => $data['Clocking Machine WiFi SSID'],
                    'wifi_token' => $wifi_encrypted_password,
                    'timezone'   => $data['Clocking Machine Timezone'],
                );
                unset($data['Clocking Machine Timezone']);

                $data['editor'] = $this->editor;


                include_once 'class.Clocking_Machine.php';
                $clocking_machine = new Clocking_Machine('new', $data, $settings);


                if ($clocking_machine->id) {
                    $this->new_object_msg = $clocking_machine->msg;

                    if ($clocking_machine->new) {
                        $this->new_clocking_machine = true;
                    } else {
                        $this->error = true;
                        if ($clocking_machine->found) {

                            $this->error_code     = 'duplicated_field';
                            $this->error_metadata = json_encode(array($clocking_machine->duplicated_field));

                            if ($clocking_machine->duplicated_field == 'Clocking Machine Code') {
                                $this->msg = _('Duplicated name');
                            }


                        } else {
                            $this->msg = $clocking_machine->msg;
                        }
                    }

                    return $clocking_machine;
                } else {


                    $this->error = true;
                    $this->msg   = $clocking_machine->msg;
                }


            } elseif ($row['Box Aurora Account Code'] == $this->get('Code')) {

                $this->error;
                $this->msg = _('Clocking-in machine already set up');

                return false;


            } else {
                $this->error;
                $this->msg = _('Clocking-in machine already set up');

                return false;
            }


        } else {
            $this->error;
            $this->msg = _('Serial number not found');

            return false;


        }


    }

    function cache_object($redis, $account_code) {

        $redis_key     = 'Au_Cached_obj'.$account_code.'.Account.'.$this->id;
        $data_to_cache = json_encode(
            [
                'code'         => $this->data['Account Code'],
                'name'         => $this->data['Account Name'],
                'currency'     => $this->data['Account Currency'],
                'part_cat_key' => $this->data['Account Part Family Category Key'],
                'locale'       => $this->data['Account Locale'],
                'timezone'     => $this->data['Account Timezone'],

            ]
        );
        $redis->set($redis_key, $data_to_cache);

        return $data_to_cache;

    }

    protected function update_field_switcher($field, $value, $options = '', $metadata = '') {


        switch ($field) {

            case 'Account Country Code':
                include_once 'class.Country.php';
                $country = new Country('code', $value);
                if ($country->id) {
                    $this->update_field('Account Country Code', $country->get('Country Code'), $options);
                    $this->update_field('Account Country 2 Alpha Code', $country->get('Country 2 Alpha Code'), 'no_history');


                } else {
                    $this->error = true;
                    $this->msg   = 'Country not found';
                }
                break;
            case('Account Currency'):

                $value = strtoupper($value);
                $sql   = sprintf(
                    "SELECT `Currency Symbol` FROM kbase.`Currency Dimension` WHERE `Currency Code`=%s", prepare_mysql($value)
                );

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {

                        $this->update_field('Account Currency', $value, $options);
                        $this->update_field('Account Currency Symbol', $row['Currency Symbol'], 'no_history');

                    } else {
                        $this->error = true;
                        $this->msg   = 'Currency Code '.$value.' not valid';

                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }

                break;
            case 'Account Timezone':
                $this->update_field($field, $value);

                break;
            default:

                $base_data = $this->base_data();
                if (array_key_exists($field, $base_data)) {
                    $this->update_field($field, $value, $options);
                } elseif (array_key_exists($field, $this->base_data('Account Data'))) {
                    $this->update_table_field($field, $value, $options, 'Account', 'Account Data', $this->id);
                }


                break;
        }
    }

}

