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
        $sql        = sprintf('SELECT `Store Key` FROM `Store Dimension`');
        $res        = mysql_query($sql);
        while ($row = mysql_fetch_assoc($res)) {
            $store_keys[] = $row['Store Key'];
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


        $data['Store Valid From']                = gmdate('Y-m-d H:i:s');
        $data['Store Timezone']                  = preg_replace('/_/', '/', $data['Store Timezone']);
        $data['Store Home Country Code 2 Alpha'] = substr($data['Store Locale'], -2);

        $country                         = new Country('2alpha', $data['Store Home Country Code 2 Alpha']);
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
                    $this->error_metadata = json_encode(
                        array($store->duplicated_field)
                    );

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
        }
    }

    function update_stores_data() {
        $number_stores = 0;
        $sql           = sprintf(
            'SELECT count(*) AS num FROM `Store Dimension` WHERE `Store State`="Normal"'
        );
        if ($row = $this->db->query($sql)->fetch()) {
            $number_stores = $row['num'];
        }

        $this->update(array('Account Stores' => $number_stores), 'no_history');

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


}


?>
