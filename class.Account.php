<?php
/*


 About:
 Author: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2010, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';

class Account extends DB_Table {

    function Account($db = false) {

        if (!$db) {
            global $db;
        }
        $this->db = $db;


        $this->table_name = 'Account';


        $this->get_data();
    }


    function get_data() {


        $sql = sprintf(
            "SELECT * FROM `Account Dimension` WHERE `Account Key`=1 "
        );


        if ($result = $this->db->query($sql)) {
            if ($this->data = $result->fetch()) {
                $this->id = 1;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }


    function load_acc_data() {

        $sql = sprintf("SELECT * FROM `Account Data`  WHERE `Account Key`=%d", $this->id);

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                foreach ($row as $key => $value) {
                    $this->data[$key] = $value;
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

     


    }


    function get($key, $data = false) {

        if (!$this->id) {
            return;
        }


        switch ($key) {

            case 'Productions':

                $number = 0;
                $sql    = sprintf(
                    "SELECT count(*) AS num FROM `Supplier Production Dimension`", $this->id
                );
                if ($row = $this->db->query($sql)->fetch()) {
                    $number = $row['num'];
                }

                return $number;
                break;

            case('Locale'):


                include 'utils/available_locales.php';

                if (array_key_exists(
                    $this->data['Account Locale'].'.UTF-8', $available_locales
                )) {
                    $locale = $available_locales[$this->data['Account Locale'].'.UTF-8'];

                    return $locale['Language Name'].($locale['Language Name'] != $locale['Language Original Name'] ? ' ('.$locale['Language Original Name'].')' : '');
                } else {

                    return $this->data['Account Locale'];
                }
                break;


            case 'Setup Metadata':
                return json_decode($this->data['Account Setup Metadata'], true);
                break;
            case 'National Employment Code Label':

                switch ($this->data['Account Country 2 Alpha Code']) {
                    case 'GB':
                        return _('National insurance number');
                        break;
                    case 'ES':
                        return _('DNI');
                        break;
                    default:
                        return '';
                        break;
                }

                break;

            default:


                if (preg_match('/^(Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Minify$/', $key)) {

                    $field = 'Account '.preg_replace('/ Minify$/', '', $key);

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

                    $amount = money($_amount, $this->get('Account Currency'), $locale = false, $fraction_digits).$suffix;

                    return $amount;
                }
                if (preg_match('/^(Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Soft Minify$/', $key)) {

                    $field = 'Account '.preg_replace('/ Soft Minify$/', '', $key);

                    $suffix          = '';
                    $fraction_digits = 'NO_FRACTION_DIGITS';
                    $_amount         = $this->data[$field];


                    $amount = money($_amount, $this->get('Account Currency'), $locale = false, $fraction_digits).$suffix;

                    return $amount;
                }
                if (preg_match('/^(Last|Yesterday|Total|1|10|6|3|2|4|5|Year To|Quarter To|Month To|Today|Week To).*(Quantity Invoiced|Invoices) Minify$/', $key)) {

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
                if (preg_match('/^(Last|Yesterday|Total|1|10|6|3|2|4|5|Year To|Quarter To|Month To|Today|Week To).*(Quantity Invoiced|Invoices) Soft Minify$/', $key)) {
                    $field   = 'Account '.preg_replace('/ Soft Minify$/', '', $key);
                    $_number = $this->data[$field];

                    return number($_number, 0);
                }

                if (preg_match('/^(Total|1).*(Amount|Profit)$/', $key)) {

                    $amount = 'Account '.$key;

                    return money($this->data[$amount]);
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

    function update_name($value) {


        $sql = sprintf(
            "UPDATE `Account Dimension` SET `Account Name`=%s", prepare_mysql($value)
        );
        $this->db->exec($sql);

        $this->updated   = true;
        $this->new_value = $value;
    }

    function add_account_history($history_key, $type = false) {
        $this->post_add_history($history_key, $type = false);
    }

    function post_add_history($history_key, $type = false) {

        if (!$type) {
            $type = 'Changes';
        }

        $sql = sprintf(
            "INSERT INTO  `Account History Bridge` (`History Key`,`Type`) VALUES (%d,%s)", $history_key, prepare_mysql($type)
        );
        $this->db->exec($sql);
        //print $sql;
    }

    function get_current_staff_with_position_code($position_code, $options = '') {


        if (preg_match('/smarty/i', $options)) {
            $smarty = true;
        } else {
            $smarty = false;
        }

        $positions = array();
        $sql       = sprintf(
            'SELECT * FROM `Staff Dimension` SD  LEFT JOIN `Company Position Staff Bridge` B ON (B.`Staff Key`=SD.`Staff Key`)  WHERE  `Position Code`=%s AND `Staff Currently Working`="Yes"',
            prepare_mysql($position_code)
        );


        if ($result = $db->this->query($sql)) {
            foreach ($result as $row) {

                if ($smarty) {
                    $_row = array();
                    foreach ($row as $key => $value) {
                        $_row[preg_replace('/\s/', '', $key)] = $value;
                    }

                    $positions[$row['Staff Key']] = $_row;
                } else {
                    $positions[$row['Staff Key']] = $row;
                }

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $positions;
    }

    function get_store_keys() {
        $store_keys = array();
        $sql        = sprintf('SELECT `Account Key` FROM `Account Dimension`');
        $res        = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            $store_keys[] = $row['Account Key'];
        }

        return $store_keys;
    }

    function create_staff($data) {
        $this->new_employee = false;

        $data['editor'] = $this->editor;
        $staff          = new Staff('find', $data, 'create');

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
        }
    }

    function update_employees_data() {
        $number_employees = 0;
        $sql              = sprintf(
            'SELECT count(*) AS num FROM `Staff Dimension` WHERE `Staff Currently Working`="Yes" '
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


        $data['Account Valid From']                = gmdate('Y-m-d H:i:s');
        $data['Account Timezone']                  = preg_replace('/_/', '/', $data['Account Timezone']);
        $data['Account Home Country Code 2 Alpha'] = substr($data['Account Locale'], -2);

        $country                         = new Country('2alpha', $data['Account Home Country Code 2 Alpha']);
        $data['Account Home Country Name'] = $country->get('Country Name');

        $store = new Account('find', $data, 'create');


        if ($store->id) {
            $this->new_object_msg = $store->msg;

            if ($store->new) {
                $this->new_object = true;
                $this->update_stores_data();
            } else {
                $this->error = true;
                if ($store->found) {

                    $this->error_code     = 'duplicated_field';
                    $this->error_metadata = json_encode(
                        array($store->duplicated_field)
                    );

                    if ($store->duplicated_field == 'Account Code') {
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
        }
    }

    function update_stores_data() {
        $number_stores = 0;
        $sql           = sprintf(
            'SELECT count(*) AS num FROM `Account Dimension` WHERE `Account State`="Normal"'
        );
        if ($row = $this->db->query($sql)->fetch()) {
            $number_stores = $row['num'];
        }

        $this->update(array('Account Accounts' => $number_stores), 'no_history');

    }

    function create_barcode($data) {

        $this->new_object = false;

        $data['editor'] = $this->editor;

        $this->errors = 0;
        $this->new    = 0;

        $range = preg_split('/-/', $data['Barcode Range']);
        unset($data['Barcode Range']);

        if (count($range) == 1) {
            $data['Barcode Number']    = $range[0];
            $data['Barcode Used From'] = gmdate('Y-m-d H:i:s');
            $data['editor']['Date']    = gmdate('Y-m-d H:i:s');

            $barcode = new Barcode('find', $data, 'create');
            if (!$barcode->id) {
                $this->error = true;
                $this->msg   = $barcode->msg;

                return;
            } else {
                if ($barcode->new) {
                    $this->new++;
                } else {
                    $this->error = true;
                    $this->msg   = $barcode->msg;

                    return;
                }
            }
        } elseif (count($range) == 2) {

            for ($i = $range[0]; $i <= $range[1]; $i++) {
                $data['Barcode Number']    = $i;
                $data['Barcode Used From'] = gmdate('Y-m-d H:i:s');
                $data['editor']['Date']    = gmdate('Y-m-d H:i:s');

                $barcode = new Barcode('find', $data, 'create');
                if (!$barcode->id) {
                    $this->errors++;
                } else {
                    if ($barcode->new) {
                        $this->new++;
                    } else {
                        $this->errors++;
                    }
                }
            }
        } else {
            $this->error = true;
            $this->msg   = _('None of the bar codes could be added');

            return;
        }


        if ($this->new == 0) {
            $this->error = true;
            $this->msg   = _('None of the bar codes could be added');

            return;
        }

        return $barcode;
    }


    function create_warehouse($data) {

        $this->new_object = false;

        $data['editor'] = $this->editor;

        $data['Warehouse State']      = 'Active';
        $data['Warehouse Valid From'] = gmdate('Y-m-d H:i:s');

        $warehouse = new Warehouse('find', $data, 'create');

        if ($warehouse->id) {
            $this->new_object_msg = $warehouse->msg;

            if ($warehouse->new) {
                $this->new_object = true;
                $this->update_warehouses_data();
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
        }
    }

    function update_warehouses_data() {
        $number_stores = 0;
        $sql           = sprintf(
            'SELECT count(*) AS num FROM `Warehouse Dimension` WHERE `Warehouse State`="Active"'
        );
        if ($row = $this->db->query($sql)->fetch()) {
            $number_stores = $row['num'];
        }

        $this->update(
            array('Account Warehouses' => $number_stores), 'no_history'
        );

    }

    function create_supplier($data) {
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
        unset($data['Supplier Contact Address country']);

        if (isset($data['Supplier Contact Address addressLine1'])) {
            $address_fields['Address Line 1']
                = $data['Supplier Contact Address addressLine1'];
            unset($data['Supplier Contact Address addressLine1']);
        }
        if (isset($data['Supplier Contact Address addressLine2'])) {
            $address_fields['Address Line 2']
                = $data['Supplier Contact Address addressLine2'];
            unset($data['Supplier Contact Address addressLine2']);
        }
        if (isset($data['Supplier Contact Address sortingCode'])) {
            $address_fields['Address Sorting Code']
                = $data['Supplier Contact Address sortingCode'];
            unset($data['Supplier Contact Address sortingCode']);
        }
        if (isset($data['Supplier Contact Address postalCode'])) {
            $address_fields['Address Postal Code']
                = $data['Supplier Contact Address postalCode'];
            unset($data['Supplier Contact Address postalCode']);
        }

        if (isset($data['Supplier Contact Address dependentLocality'])) {
            $address_fields['Address Dependent Locality']
                = $data['Supplier Contact Address dependentLocality'];
            unset($data['Supplier Contact Address dependentLocality']);
        }

        if (isset($data['Supplier Contact Address locality'])) {
            $address_fields['Address Locality']
                = $data['Supplier Contact Address locality'];
            unset($data['Supplier Contact Address locality']);
        }

        if (isset($data['Supplier Contact Address administrativeArea'])) {
            $address_fields['Address Administrative Area']
                = $data['Supplier Contact Address administrativeArea'];
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
        // TODO
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
            $address_fields['Address Line 1']
                = $data['Agent Contact Address addressLine1'];
            unset($data['Agent Contact Address addressLine1']);
        }
        if (isset($data['Agent Contact Address addressLine2'])) {
            $address_fields['Address Line 2']
                = $data['Agent Contact Address addressLine2'];
            unset($data['Agent Contact Address addressLine2']);
        }
        if (isset($data['Agent Contact Address sortingCode'])) {
            $address_fields['Address Sorting Code']
                = $data['Agent Contact Address sortingCode'];
            unset($data['Agent Contact Address sortingCode']);
        }
        if (isset($data['Agent Contact Address postalCode'])) {
            $address_fields['Address Postal Code']
                = $data['Agent Contact Address postalCode'];
            unset($data['Agent Contact Address postalCode']);
        }

        if (isset($data['Agent Contact Address dependentLocality'])) {
            $address_fields['Address Dependent Locality']
                = $data['Agent Contact Address dependentLocality'];
            unset($data['Agent Contact Address dependentLocality']);
        }

        if (isset($data['Agent Contact Address locality'])) {
            $address_fields['Address Locality']
                = $data['Agent Contact Address locality'];
            unset($data['Agent Contact Address locality']);
        }

        if (isset($data['Agent Contact Address administrativeArea'])) {
            $address_fields['Address Administrative Area']
                = $data['Agent Contact Address administrativeArea'];
            unset($data['Agent Contact Address administrativeArea']);
        }


        //exit;

        $agent = new Agent('new', $data, $address_fields);


        if ($agent->id) {
            $this->new_agent_msg = $agent->msg;

            if ($agent->new) {
                $this->new_agent = true;
                //$this->update_agents_data();
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
            case 'Account Accounts':
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

    protected function update_field_switcher($field, $value, $options = '', $metadata = '') {


        switch ($field) {
            case 'Company Name':


            case('Account Currency'):
                $this->update_currency($value);
                break;
            default:

                $base_data = $this->base_data();
                if (array_key_exists($field, $base_data)) {
                    $this->update_field($field, $value, $options);
                }elseif (array_key_exists($field, $this->base_data('Account Data'))) {
                    $this->update_table_field($field, $value, $options, 'Account', 'Account Data', $this->id);
                }


                break;
        }
    }

    function update_currency($value) {
        $value = strtoupper($value);
        $sql   = sprintf(
            "SELECT * FROM kbase.`Currency Dimension` WHERE `Currency Code`=%s", prepare_mysql($value)
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {

                $sql = sprintf(
                    "UPDATE `Account Dimension` SET `Account Currency`=%s", prepare_mysql($value)
                );
                $this->db->exec($sql);

                $this->updated   = true;
                $this->new_value = $value;

            } else {
                $this->error = true;
                $this->msg   = 'Currency Code '.$value.' not valid';

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }

    function update_sales_from_invoices($interval, $this_year = true, $last_year = true) {


        include_once 'utils/date_functions.php';
        list($db_interval, $from_date, $to_date, $from_date_1yb, $to_date_1yb) = calculate_interval_dates($this->db, $interval);

        if ($this_year) {

            $sales_data = $this->get_sales_data($from_date, $to_date);


            $data_to_update = array(
                "Account $db_interval Acc Invoiced Discount Amount" => $sales_data['discount_amount'],
                "Account $db_interval Acc Invoiced Amount"          => $sales_data['amount'],
                "Account $db_interval Acc Invoices"                 => $sales_data['invoices'],
                "Account $db_interval Acc Refunds"                  => $sales_data['refunds'],
                "Account $db_interval Acc Replacements"             => $sales_data['replacements'],
                "Account $db_interval Acc Delivery Notes"           => $sales_data['deliveries'],
                "Account $db_interval Acc Profit"                   => $sales_data['profit'],
                "Account $db_interval Acc Customers"                => $sales_data['customers'],
                "Account $db_interval Acc Repeat Customers"         => $sales_data['repeat_customers'],

                "Account DC $db_interval Acc Invoiced Amount"          => $sales_data['dc_amount'],
                "Account DC $db_interval Acc Invoiced Discount Amount" => $sales_data['dc_discount_amount'],
                "Account DC $db_interval Acc Profit"                   => $sales_data['dc_profit']
            );


            $this->update($data_to_update, 'no_history');
        }

        if ($from_date_1yb and $last_year) {


            $sales_data = $this->get_sales_data($from_date_1yb, $to_date_1yb);

            $data_to_update = array(
                "Account $db_interval Acc 1YB Invoiced Discount Amount"    => $sales_data['discount_amount'],
                "Account $db_interval Acc 1YB Invoiced Amount"             => $sales_data['amount'],
                "Account $db_interval Acc 1YB Invoices"                    => $sales_data['invoices'],
                "Account $db_interval Acc 1YB Refunds"                     => $sales_data['refunds'],
                "Account $db_interval Acc 1YB Replacements"                => $sales_data['replacements'],
                "Account $db_interval Acc 1YB Delivery Notes"              => $sales_data['deliveries'],
                "Account $db_interval Acc 1YB Profit"                      => $sales_data['profit'],
                "Account $db_interval Acc 1YB Customers"                   => $sales_data['customers'],
                "Account $db_interval Acc 1YB Repeat Customers"            => $sales_data['repeat_customers'],
                "Account DC $db_interval Acc 1YB Invoiced Amount"          => $sales_data['dc_amount'],
                "Account DC $db_interval Acc 1YB Invoiced Discount Amount" => $sales_data['dc_discount_amount'],
                "Account DC $db_interval Acc 1YB Profit"                   => $sales_data['dc_profit']
            );

            $this->update($data_to_update, 'no_history');


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


        $sql = sprintf(
            "SELECT count(DISTINCT `Invoice Customer Key`)  AS customers,sum(if(`Invoice Type`='Invoice',1,0))  AS invoices, sum(if(`Invoice Type`='Refund',1,0))  AS refunds  ,sum(`Invoice Items Discount Amount`*`Invoice Currency Exchange`) AS dc_discounts,sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`) dc_net  ,sum(`Invoice Total Profit`*`Invoice Currency Exchange`) AS dc_profit FROM `Invoice Dimension` WHERE true %s %s",
             ($from_date ? sprintf('and `Invoice Date`>%s', prepare_mysql($from_date)) : ''), ($to_date ? sprintf('and `Invoice Date`<%s', prepare_mysql($to_date)) : '')

        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $sales_data['discount_amount']    = $row['dc_discounts'];
                $sales_data['amount']             = $row['dc_net'];
                $sales_data['profit']             = $row['dc_profit'];
                $sales_data['invoices']           = $row['invoices'];
                $sales_data['refunds']            = $row['refunds'];
                $sales_data['customers']          = $row['customers'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print $sql;
            exit;
        }


        $sql = sprintf(
            "SELECT count(*)  AS replacements FROM `Delivery Note Dimension` WHERE `Delivery Note Type` IN ('Replacement & Shortages','Replacement','Shortages') AND true %s %s",
             ($from_date ? sprintf('and `Delivery Note Date`>%s', prepare_mysql($from_date)) : ''), ($to_date ? sprintf('and `Delivery Note Date`<%s', prepare_mysql($to_date)) : ''));


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
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $sql = sprintf(
            " SELECT COUNT(*) AS repeat_customers FROM( SELECT count(*) AS invoices ,`Invoice Customer Key` FROM `Invoice Dimension` WHERE true %s %s GROUP BY `Invoice Customer Key` HAVING invoices>1) AS tmp",
             ($from_date ? sprintf('and `Invoice Date`>%s', prepare_mysql($from_date)) : ''), ($to_date ? sprintf('and `Invoice Date`<%s', prepare_mysql($to_date)) : '')
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $sales_data['repeat_customers'] = $row['repeat_customers'];


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $sales_data;


    }


    function update_previous_years_data() {

        foreach (range(1, 5) as $i) {
            $data_iy_ago = $this->get_sales_data(
                date('Y-01-01 00:00:00', strtotime('-'.$i.' year')), date('Y-01-01 00:00:00', strtotime('-'.($i - 1).' year'))
            );


            $data_to_update = array(
                "Account $i Year Ago Invoiced Discount Amount"    => $data_iy_ago['discount_amount'],
                "Account $i Year Ago Invoiced Amount"             => $data_iy_ago['amount'],
                "Account $i Year Ago Invoices"                    => $data_iy_ago['invoices'],
                "Account $i Year Ago Refunds"                     => $data_iy_ago['refunds'],
                "Account $i Year Ago Replacements"                => $data_iy_ago['replacements'],
                "Account $i Year Ago Delivery Notes"              => $data_iy_ago['deliveries'],
                "Account $i Year Ago Profit"                      => $data_iy_ago['profit'],

            );


            $this->update($data_to_update, 'no_history');
        }

        $this->update(['Account Acc Previous Intervals Updated' => gmdate('Y-m-d H:i:s')], 'no_history');


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

            $data_to_update = array(
                "Account $i Quarter Ago Invoiced Discount Amount"    => $sales_data['discount_amount'],
                "Account $i Quarter Ago Invoiced Amount"             => $sales_data['amount'],
                "Account $i Quarter Ago Invoices"                    => $sales_data['invoices'],
                "Account $i Quarter Ago Refunds"                     => $sales_data['refunds'],
                "Account $i Quarter Ago Replacements"                => $sales_data['replacements'],
                "Account $i Quarter Ago Delivery Notes"              => $sales_data['deliveries'],
                "Account $i Quarter Ago Profit"                      => $sales_data['profit'],

                "Account $i Quarter Ago 1YB Invoiced Discount Amount"    => $sales_data_1yb['discount_amount'],
                "Account $i Quarter Ago 1YB Invoiced Amount"             => $sales_data_1yb['amount'],
                "Account $i Quarter Ago 1YB Invoices"                    => $sales_data_1yb['invoices'],
                "Account $i Quarter Ago 1YB Refunds"                     => $sales_data_1yb['refunds'],
                "Account $i Quarter Ago 1YB Replacements"                => $sales_data_1yb['replacements'],
                "Account $i Quarter Ago 1YB Delivery Notes"              => $sales_data_1yb['deliveries'],
                "Account $i Quarter Ago 1YB Profit"                      => $sales_data_1yb['profit'],
                );
            $this->update($data_to_update, 'no_history');
        }

        $this->update(['Account Acc Previous Intervals Updated' => gmdate('Y-m-d H:i:s')], 'no_history');


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


    function update_timeseries_record($timeseries, $from, $to, $fork_key) {

        if ($timeseries->get('Type') == 'AccountSales') {

            $dates = date_frequency_range(
                $this->db, $timeseries->get('Timeseries Frequency'), $from, $to
            );

            if ($fork_key) {

                $sql = sprintf(
                    "UPDATE `Fork Dimension` SET `Fork State`='In Process' ,`Fork Operations Total Operations`=%d,`Fork Start Date`=NOW(),`Fork Result`=%d  WHERE `Fork Key`=%d ", count($dates),
                    $timeseries->id, $fork_key
                );

                $this->db->exec($sql);
            }
            $index = 0;
            foreach ($dates as $date_frequency_period) {
                $index++;
                $sales_data = $this->get_sales_data($date_frequency_period['from'], $date_frequency_period['to']);
                $_date      = gmdate('Y-m-d', strtotime($date_frequency_period['from'].' +0:00'));


                if ($sales_data['invoices'] > 0 or $sales_data['refunds'] > 0 or $sales_data['customers'] > 0 or $sales_data['amount'] != 0 or $sales_data['dc_amount'] != 0 or $sales_data['profit']
                    != 0 or $sales_data['dc_profit'] != 0
                ) {

                    list($timeseries_record_key, $date) = $timeseries->create_record(array('Timeseries Record Date' => $_date));

                    $sql = sprintf(
                        'UPDATE `Timeseries Record Dimension` SET `Timeseries Record Integer A`=%d ,`Timeseries Record Integer B`=%d ,`Timeseries Record Integer C`=%d ,`Timeseries Record Float A`=%.2f ,  `Timeseries Record Float B`=%f ,`Timeseries Record Float C`=%f ,`Timeseries Record Float D`=%f ,`Timeseries Record Type`=%s WHERE `Timeseries Record Key`=%d',
                        $sales_data['invoices'], $sales_data['refunds'], $sales_data['customers'], $sales_data['amount'], $sales_data['dc_amount'], $sales_data['profit'], $sales_data['dc_profit'],
                        prepare_mysql('Data'), $timeseries_record_key

                    );


                    //  print "$sql\n";

                    $update_sql = $this->db->prepare($sql);
                    $update_sql->execute();
                    if ($update_sql->rowCount() or $date == date('Y-m-d')) {
                        $timeseries->update(
                            array(
                                'Timeseries Updated' => gmdate(
                                    'Y-m-d H:i:s'
                                )
                            ), 'no_history'
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
                $timeseries->update_stats();

            }

        }


        if ($fork_key) {

            $sql = sprintf(
                "UPDATE `Fork Dimension` SET `Fork State`='Finished' ,`Fork Finished Date`=NOW(),`Fork Operations Done`=%d,`Fork Result`=%d WHERE `Fork Key`=%d ", $index, $timeseries->id, $fork_key
            );

            $this->db->exec($sql);

        }

    }


    
    

}


?>
