<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 26 March 2016 at 00:37:22 GMT+8, Kuala Lumpur
 Copyright (c) 2015, Inikoo

 Version 3

*/
include_once 'class.DB_Table.php';


class Upload extends DB_Table {

    /**
     * @var \PDO
     */
    public $db;

    function __construct($a1, $a2 = false, $_db = false) {
        if (!$_db) {
            global $db;
            $this->db = $db;
        } else {
            $this->db = $_db;
        }

        $this->table_name    = 'Upload';
        $this->ignore_fields = array('Upload Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } elseif ($a1 == 'create') {
            $this->create($a2);

        } else {
            $this->get_data($a1, $a2);
        }
    }


    function get_data($key, $tag) {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Upload Dimension` WHERE `Upload Key`=%d", $tag
            );
            if ($this->data = $this->db->query($sql)->fetch()) {
                $this->id       = $this->data['Upload Key'];
                $this->metadata = $this->get('Metadata');
            }
        }


    }

    function get($key) {

        if (!$this->id) {
            return '';
        }

        switch ($key) {
            case  'State':
                switch ($this->data['Upload State']) {
                    case 'InProcess':
                    case 'Uploaded':
                        return _('In process');
                        break;
                    case 'Finished':
                        return _('Finished');


                        break;
                    case 'Cancelled':
                        return _('Cancelled');

                        break;

                    default:
                        return $this->data['Upload State'];
                        break;
                }

            case 'User Alias':


                $user = get_object('User', $this->data['Upload User Key']);


                return $user->get('Handle');


            case 'File Size':
                include_once 'utils/natural_language.php';

                return file_size($this->data['Upload File Size']);

            case 'Object':
                switch ($this->data['Upload Object']) {
                    case 'supplier_part':
                        $object = _("supplier's products");
                        break;
                    case 'supplier':
                        $object = _("suppliers");
                        break;
                    case 'part':
                        $object = _("parts");
                        break;
                    case 'location':
                        $object = _("locations");
                        break;
                    case 'warehouse_area':
                        $object = _("warehouse areas");
                        break;
                    default:
                        $object = $this->data['Upload Object'];
                }

                return $object;


            case 'Parent':

                $parent = get_object($this->data['Upload Parent'], $this->data['Upload Parent Key']);


                switch ($this->data['Upload Parent']) {
                    case 'supplier':
                        $parent = sprintf(_("supplier %s"), sprintf('<span  class="link"  onclick="change_view(\'supplier/%d\')"  >%s</span>', $this->data['Upload Parent Key'], $parent->get('Code')));
                        break;
                    case 'warehouse':
                        $parent = sprintf(_("warehouse %s"), sprintf('<span  class="link"  onclick="change_view(\'warehouse/%d\')"  >%s</span>', $this->data['Upload Parent Key'], $parent->get('Code')));
                        break;
                    case 'category':
                        if ($this->data['Upload Object'] == 'part') {
                            $parent = sprintf(_("part's category %s"), sprintf('<span  class="link"  onclick="change_view(\'category/%d\')"  >%s</span>', $this->data['Upload Parent Key'], $parent->get('Code')));


                        } else {
                            $parent = sprintf(_("category %s"), sprintf('<span  class="link"  onclick="change_view(\'category/%d\')"  >%s</span>', $this->data['Upload Parent Key'], $parent->get('Code')));

                        }
                        break;
                    default:
                        $parent = $this->data['Upload Parent'];
                }

                return $parent;


            case ('Created'):
            case ('Date'):
                $key = 'Created';

                return strftime(
                    "%a %e %b %Y %H:%M %Z", strtotime($this->data['Upload '.$key].' +0:00')
                );


            case ('Filesize'):
                include_once 'utils/units_functions.php';

                return file_size($this->data['Upload File Size']);


            case('Records'):
            case('OK'):
            case('Warnings'):
            case('Errors'):
                return number($this->data['Upload '.$key]);

            case 'Metadata':
                if ($this->data['Upload Metadata'] == '') {
                    return false;
                }

                return json_decode($this->data['Upload Metadata'], true);

            case 'Filename':
                if (isset($this->metadata['files_data'][0]['Upload File Name'])) {
                    return $this->metadata['files_data'][0]['Upload File Name'];
                }
                break;

            default:

                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Upload '.$key, $this->data)) {
                    return $this->data['Upload '.$key];
                }
        }

        return '';
    }

    function create($data) {

        $this->new = false;

        $data['Upload State']   = 'Uploaded';
        $data['Upload Created'] = gmdate('Y-m-d H:i:s');

        $base_data = $this->base_data();

        foreach ($data as $key => $value) {
            if (array_key_exists($key, $base_data)) {
                $base_data[$key] = _trim($value);
            }
        }


        $sql = sprintf(
            "INSERT INTO `Upload Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($base_data)).'`', join(',', array_fill(0, count($base_data), '?'))
        );

        $stmt = $this->db->prepare($sql);


        $i = 1;
        foreach ($base_data as $key => $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {

            $this->id = $this->db->lastInsertId();
            $this->get_data('id', $this->id);


            $this->new = true;


        } else {
            $this->error = true;
            $this->msg   = 'Error inserting upload record';
            print_r($stmt->errorInfo());
        }


    }

    function load_file_data() {
        $sql = "SELECT * FROM `Upload File Dimension` WHERE `Upload File Upload Key`=?";


        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        if ($row = $stmt->fetch()) {
            foreach ($row as $key => $value) {
                $this->data[$key] = $value;
            }
        }


    }

    function get_field_label($field) {

        switch ($field) {
            case 'Upload Object':
                $label = _('Objects');
                break;
            case 'Account Websites':
                $label = _('Websites');
                break;
            case 'Account Products':
                $label = _('Products');
                break;
            case 'Account Customers':
                $label = _('Customers');
                break;
            case 'Account Invoices':
                $label = _('Invoices');
                break;
            case 'Account Order Transactions':
                $label = _("Order's Items");
                break;

            default:
                $label = $field;
        }

        return $label;

    }


}



