<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Thu 3 Oct 2019 17:59:10 +0800 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3
*/

include_once 'class.DB_Table.php';

class Clocking_Machine extends DB_Table {

    /**
     * @var \PDO
     */
    public $db;

    /**
     * @var integer
     */
    public $id;

    function __construct($arg1 = false, $arg2 = false, $arg3 = false) {

        global $db;
        $this->db = $db;
        $this->id = false;


        $this->table_name = 'Clocking Machine';

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
                "SELECT * FROM `Clocking Machine Dimension` WHERE `Clocking Machine Key`=%d", $id
            );

        } else {

            return;
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id       = $this->data['Clocking Machine Key'];
            $this->settings = json_decode($this->data['Clocking Machine Settings'], true);

        }


    }

    function create($raw_data, $settings) {

        include 'keyring/dns.php';
        include 'keyring/au_deploy_conf.php';

        $box_db = get_box_db();

        $account = get_object('Account', 1);

        if (empty($raw_data['Clocking Machine Code'])) {
            $this->error      = true;
            $this->msg        = _("Name missing");
            $this->error_code = 'clocking_machine_code_missing';
            $this->metadata   = '';

            return;

        }


        $sql  = "select `Clocking Machine Key` from `Clocking Machine Dimension`  where `Clocking Machine Code`=? ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array($raw_data['Clocking Machine Code'])
        );
        if ($row = $stmt->fetch()) {
            $this->error = true;

            $this->found            = true;
            $this->duplicated_field = 'Clocking Machine Code';
            $this->get_data('id', $row['Clocking Machine Key']);

            return;
        }


        $this->data = $this->base_data();
        foreach ($raw_data as $key => $value) {
            if (array_key_exists($key, $this->data)) {
                $this->data[$key] = _trim($value);
            }
        }
        $this->editor = $raw_data['editor'];

        $this->data['Clocking Machine Creation Date'] = gmdate('Y-m-d H:i:s');
        $this->data['Clocking Machine Settings']      = '{}';


        $sql = sprintf(
            "INSERT INTO `Clocking Machine Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($this->data)).'`', join(',', array_fill(0, count($this->data), '?'))
        );

        $stmt = $this->db->prepare($sql);

        $i = 1;
        foreach ($this->data as $key => $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {


            $this->id = $this->db->lastInsertId();

            foreach ($settings as $key => $value) {
                $this->fast_update_json_field('Clocking Machine Settings', $key, $value);
            }

            $api_key_data = array(
                'API Key Scope'                => 'Box',
                'API Key Clocking Machine Key' => $this->id
            );

            include_once 'class.API_Key.php';

            $api_key = new API_Key('create', $api_key_data);


            $this->fast_update(
                array(
                    'Clocking Machine API Key Key' => $api_key->id
                )
            );
            $this->get_data('id', $this->id);


            $cipher_method = 'aes-128-ctr';
            $enc_key       = openssl_digest(SHARED_KEY, 'SHA256', true);
            $enc_iv        = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher_method));
            $api_secret    = openssl_encrypt($api_key->secret_key, $cipher_method, $enc_key, 0, $enc_iv)."::".bin2hex($enc_iv);
            unset($cipher_method, $enc_key, $enc_iv);


            $box_data = array(
                'name'       => $this->data['Clocking Machine Code'],
                'box_key'    => $this->id,
                'timezone'   => $this->settings('timezone'),
                'SSID'       => $this->settings('SSID'),
                'wifi_token' => $this->settings('wifi_token'),
                'api_code'   => $api_key->get('API Key Code'),
                'api_secret' => $api_secret,
                'api_url'    => $account->get('Account System Public URL')

            );


            $sql = 'update  box.`Box Dimension` set `Box Set up Date`=?,`Box Aurora Account Data`=? where `Box Key`=?  ';

            $stmt = $box_db->prepare($sql);
            $stmt->execute(
                array(
                    gmdate('Y-m-d H:i:s'),
                    json_encode($box_data),
                    $this->data['Clocking Machine Box Key']
                )
            );

            $history_data = array(
                'History Abstract' => sprintf(_("Clocking-in machine created (%s)"), $this->get('Code')),
                'History Details'  => '',
                'Action'           => 'created',
                'Subject'          => 'Clocking Machine',
                'Subject Key'      => $this->id,
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );

            $this->new = true;


        } else {
            $this->error = true;
            $this->msg   = 'Error inserting Clocking Machine record';
        }


    }

    function settings($key) {
        return (isset($this->settings[$key]) ? $this->settings[$key] : '');
    }

    function get($key) {

        if (!$this->id) {
            return '';
        }


        switch ($key) {
            case('Creation Date'):
                if ($this->data['Clocking Machine '.$key] == '') {
                    return '';
                }

                return '<span title="'.strftime(
                        "%a %e %b %Y %H:%M:%S %Z", strtotime($this->data['Clocking Machine '.$key]." +00:00")
                    ).'">'.strftime(
                        "%a %e %b %Y", strtotime($this->data['Clocking Machine '.$key]." +00:00")
                    ).'</span>';
                break;


            default:

                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Clocking Machine '.$key, $this->data)) {
                    return $this->data[$this->table_name.' '.$key];
                }

                return '';

        }

    }

    function metadata($key) {
        return (isset($this->metadata[$key]) ? $this->metadata[$key] : '');
    }

    function update_field_switcher($field, $value, $options = '', $metadata = array()) {


        if (is_string($value)) {
            $value = _trim($value);
        }


        switch ($field) {


            case 'Clocking Machine Timezone':

                $this->update_field($field, $value, $options);

                break;

            default:


        }
    }


    function get_field_label($field) {


        switch ($field) {


            case 'Clocking Machine Code':
                $label = _('name');
                break;
            case 'Clocking Machine Serial Number':
                $label = _('serial number');
                break;
            case 'Clocking Machine Timezone':
                $label = _('timezone');
                break;
            default:
                $label = $field;

        }

        return $label;

    }

    function create_nfc_tag($data) {

        include_once 'class.Clocking_Machine_NFC_Tag.php';
        $this->new_nfc_tag = false;

        $data['editor']                                     = $this->editor;
        $data['Clocking Machine NFC Tag Last Scan Box Key'] = $this->id;

        $nfc_tag = new Clocking_Machine_NFC_Tag('find', $data, 'create');
        if ($nfc_tag->id) {
            $this->new_nfc_tag_msg = $nfc_tag->msg;

            if ($nfc_tag->new) {
                $this->new_nfc_tag = true;
            } else {
                $this->error = true;
                if ($nfc_tag->found) {
                    $this->msg            = _('Duplicated NFC tag if');
                    $this->error_code     = 'duplicated_field';
                    $this->error_metadata = json_encode(array('ID'));

                } else {
                    $this->msg = $nfc_tag->msg;
                }
            }

            return $nfc_tag;
        } else {
            $this->error = true;
            $this->msg   = $nfc_tag->msg;
        }
    }


}

