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
        }  else {
            return false;
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Prospect Key'];
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

        if ($this->data['Prospect First Contacted Date'] == '') {
            $this->data['Prospect First Contacted Date'] = gmdate('Y-m-d H:i:s');
        }


        $keys   = '';
        $values = '';
        foreach ($this->data as $key => $value) {
            $keys .= ",`".$key."`";
            if (in_array(
                $key, array(
                        'Prospect First Contacted Date',
                        'Prospect Lost Date'

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


            case('Lost Date'):
            case('Last Order Date'):
            case('First Order Date'):
            case('First Contacted Date'):
            case('Last Order Date'):
            case('Tax Number Validation Date'):
                if ($this->data['Prospect '.$key] == '') {
                    return '';
                }

                return '<span title="'.strftime(
                        "%a %e %b %Y %H:%M:%S %Z", strtotime($this->data['Prospect '.$key]." +00:00")
                    ).'">'.strftime(
                        "%a %e %b %Y", strtotime($this->data['Prospect '.$key]." +00:00")
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


    function update_location_type() {

        $store = new Store($this->data['Prospect Store Key']);

        if ($this->data['Prospect Contact Address Country 2 Alpha Code'] == $store->data['Store Home Country Code 2 Alpha'] or $this->data['Prospect Contact Address Country 2 Alpha Code'] == 'XX') {
            $location_type = 'Domestic';
        } else {
            $location_type = 'Export';
        }

        $this->update(array('Prospect Location Type' => $location_type));


    }


    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        if (is_string($value)) {
            $value = _trim($value);
        }


        if ($this->update_subject_field_switcher($field, $value, $options, $metadata)) {
            return;
        }


        switch ($field) {



            case 'Prospect Contact Address':


                $this->update_address('Contact', json_decode($value, true), $options);
                /*

                                if(  empty($metadata['no_propagate_addresses'])  ) {


                                    if ($this->data['Prospect Billing Address Link'] == 'Contact') {

                                        $this->update_field_switcher('Prospect Invoice Address', $value, $options, array('no_propagate_addresses'=>true));

                                        if ($this->data['Prospect Delivery Address Link'] == 'Billing') {
                                            $this->update_field_switcher('Prospect Delivery Address', $value, $options, array('no_propagate_addresses'=>true));

                                        }


                                    }
                                    if ($this->data['Prospect Delivery Address Link'] == 'Contact') {

                                        $this->update_field_switcher('Prospect Delivery Address', $value, $options, array('no_propagate_addresses'=>true));
                                    }

                                }


                                $this->update_metadata = array(

                                    'class_html'  => array(
                                        'Contact_Address'      => $this->get('Contact Address')


                                    )
                                );
                */
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



    function delete($note = '') {

        global $account;


        $this->deleted = false;

        $has_orders = false;
        $sql        = "SELECT count(*) AS total  FROM `Order Dimension` WHERE `Order Prospect Key`=".$this->id;

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                if ($row['total'] > 0) {
                    $has_orders = true;
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        if ($has_orders) {
            $this->msg = _("Prospect can't be deleted");

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
            "DELETE FROM `Prospect Correlation` WHERE `Prospect A Key`=%d OR `Prospect B Key`=%s", $this->id, $this->id
        );
        $this->db->exec($sql);
        $sql = sprintf(
            "DELETE FROM `Prospect History Bridge` WHERE `Prospect Key`=%d", $this->id
        );
        $this->db->exec($sql);
        $sql = sprintf(
            "DELETE FROM `List Prospect Bridge` WHERE `Prospect Key`=%d", $this->id
        );
        $this->db->exec($sql);

        $sql = sprintf(
            "DELETE FROM `Prospect Send Post` WHERE `Prospect Key`=%d", $this->id
        );
        $this->db->exec($sql);
        $sql = sprintf(
            "DELETE FROM `Search Full Text Dimension` WHERE `Subject`='Prospect' AND `Subject Key`=%d", $this->id
        );
        $this->db->exec($sql);
        $sql = sprintf(
            "DELETE FROM `Category Bridge` WHERE `Subject`='Prospect' AND `Subject Key`=%d", $this->id
        );
        $this->db->exec($sql);

        $sql = sprintf(
            "DELETE FROM `Prospect Send Post` WHERE  `Prospect Key`=%d", $this->id
        );
        $this->db->exec($sql);


        $website_user = get_object('Website_User', $this->get('Prospect Website User Key'));
        $website_user->delete();


        // Delete if the email has not been send yet
        //Email Campaign Mailing List

        $sql = sprintf(
            "INSERT INTO `Prospect Deleted Dimension` (`Prospect Key`,`Prospect Store Key`,`Prospect Deleted Name`,`Prospect Deleted Contact Name`,`Prospect Deleted Email`,`Prospect Deleted Metadata`,`Prospect Deleted Date`,`Prospect Deleted Note`) VALUE (%d,%d,%s,%s,%s,%s,%s,%s) ",
            $this->id, $this->data['Prospect Store Key'], prepare_mysql($this->data['Prospect Name']), prepare_mysql($this->data['Prospect Main Contact Name']), prepare_mysql($this->data['Prospect Main Plain Email']),
            prepare_mysql(gzcompress(json_encode($this->data), 9)), prepare_mysql($this->editor['Date']), prepare_mysql($note, false)
        );


        $this->db->exec($sql);


        require_once 'utils/new_fork.php';
        new_housekeeping_fork(
            'au_housekeeping', array(
            'type'      => 'prospect_deleted',
            'store_key' => $this->data['Prospect Store Key'],
            'editor'    => $this->editor
        ), $account->get('Account Code'), $this->db
        );


        $this->deleted = true;
    }






}


?>
