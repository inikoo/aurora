<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 12 Jul 2021 20:37:36 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

include_once('class.DB_Table.php');


class Fulfilment_Asset extends DB_Table {

    /**
     * @var \PDO
     */
    public $db;


    function __construct($arg1 = false, $arg2 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Fulfilment Asset';
        $this->ignore_fields = array('Fulfilment Asset Key');

        if (preg_match('/^(new|create)$/i', $arg1) and is_array($arg2)) {
            $this->create($arg2);

            return;
        }

        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }
        $this->get_data($arg1, $arg2);
    }


    function create($data) {

        $this->editor=$data['editor'];

        $this->data = $this->base_data();
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $this->data)) {
                $this->data[$key] = _trim($value);
            }
        }

        if ($this->data['Fulfilment Asset Type'] == '') {
            $this->msg   = _('Field required');
            $this->new   = false;
            $this->error = true;

            return;
        }


        $sql = sprintf(
            "INTO `Fulfilment Asset Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($this->data)).'`', join(',', array_fill(0, count($this->data), '?'))
        );


        $stmt = $this->db->prepare("INSERT ".$sql);

        $i = 1;
        foreach ($this->data as $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {
            $this->id  = $this->db->lastInsertId();
            $this->new = true;
            $this->get_data('id', $this->id);

            if ($this->get('Fulfilment Asset Location Key')) {
                $this->fast_update(
                    ['Fulfilment Asset State' => 'Stored']
                );
            }


            $history_data = array(
                'History Abstract' => sprintf(_('Fulfilment asset %s created'), $this->get('Formatted ID Reference')),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );


        } else {
            print_r($stmt->errorInfo());
            $this->error = true;
            $this->msg   = 'Error inserting fulfilment Asset record';
        }

    }

    function get_data($key, $tag): bool {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Fulfilment Asset Dimension` WHERE `Fulfilment Asset Key`=%d", $tag
            );
        } else {
            return false;
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Fulfilment Asset Key'];

        }

        return true;

    }

    function get($key) {


        if (!$this->id) {
            return false;
        }


        switch ($key) {
            case 'Type Icon':
                if ($this->get('Fulfilment Asset Type') == 'Pallet') {
                    return '<i class="fal fa-fw fa-pallet-alt" title="'._('Pallet').'"></i>';
                } else {
                    return '<i class="fal fa-fw  fa-box-alt" title="'._('Box').'"></i>';

                }


            case 'Formatted ID':
                return sprintf('%05d', $this->id);
            case 'Formatted ID Reference':
                $tmp = '<span >'.$this->get('Formatted ID').'</span>';
                if ($this->get('Reference') != '') {
                    $tmp .= ' <span class=" small"><span  title="'._('Customer reference').'">'.$this->get('Reference').'</span></span>';
                }

                return $tmp;
            case 'Location Key':
                if ($this->data['Fulfilment Asset Location Key'] != '') {
                    $location = get_object('Location', $this->data['Fulfilment Asset Location Key']);

                    return $location->get('Code');
                } else {
                    return '';
                }
            case 'Type':
                if ($this->data['Fulfilment Asset Type'] == 'Pallet') {
                    return _('Pallet');
                } else {
                    return _('Box');
                }
            case 'Formatted Location':
                if ($this->data['Fulfilment Asset Location Key'] != '') {
                    return sprintf(
                        '<span class="link" onclick="change_view(\'locations/%d/%d\')">%s</span>', $this->data['Fulfilment Asset Warehouse Key'], $this->data['Fulfilment Asset Location Key'], $this->get('Location Key')
                    );
                } else {
                    return '';
                }
            case 'Location State Formatted':

                switch ($this->get('Fulfilment Asset State')) {
                    case 'InProcess':
                        if ($this->data['Fulfilment Asset Location Key'] != '') {
                            return $this->get('Formatted Location');
                        } else {
                            return _('In process');
                        }


                    default:
                        return '';
                }


            default:


                if (array_key_exists('Fulfilment Asset '.$key, $this->data)) {
                    return $this->data[$this->table_name.' '.$key];
                }

                if (isset($this->data[$key])) {
                    return $this->data[$key];
                } else {
                    return '';
                }
        }

    }


    function delete($metadata = ''): string {


        $fulfilment_delivery         = get_object('fulfilment_delivery', $this->get('Fulfilment Asset Fulfilment Delivery Key'));
        $fulfilment_delivery->editor = $this->editor;

        $this->deleted     = false;
        $this->deleted_msg = '';


        $sql  = "DELETE FROM `Fulfilment Asset Dimension` WHERE `Fulfilment Asset Key`=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $this->id);

        if ($stmt->execute()) {
            $this->deleted = true;

        } else {
            $this->deleted_msg = 'Error area can not be deleted';
        }

        if ($metadata == 'from_delivery') {
            $account = get_object('Account', 1);
            $user    = get_object('User', $this->editor['User Key']);

            include_once 'prepare_table/fulfilment.assets.ptc.php';
            $table = new prepare_table_fulfilment_assets($this->db, $account, $user);
            $table->initialize_from_session('fulfilment.delivery.assets');
            $table->prepare_table();
            $table->calculate_table_totals();
            $table->totals_formatted_text();
            $fulfilment_delivery->update_totals();
            $this->update_metadata = array(
                'class_html' => array(
                    'rtext_info'                       => $table->rtext,
                    'Fulfilment_Delivery_Number_Items' => $fulfilment_delivery->get('Number Items')
                ),

            );

        }


        $history_data = array(
            'History Abstract' => sprintf(_('Fulfilment asset %s deleted'), $this->get('Formatted ID Reference')),
            'History Details'  => '',
            'Action'           => 'deleted'
        );

        $fulfilment_delivery->add_subject_history(
            $history_data, true, 'No', 'Changes', $fulfilment_delivery->get_object_name(), $fulfilment_delivery->id
        );

        return '/fulfilment/'.$fulfilment_delivery->get('Fulfilment Delivery Warehouse Key').'/customers/'.($fulfilment_delivery->get('Fulfilment Delivery Type') == 'Part' ? 'dropshipping' : 'asset_keeping').'/'.$fulfilment_delivery->get(
                'Fulfilment Delivery Customer Key'
            ).'/delivery/'.$fulfilment_delivery->id;


    }

    function get_field_label($field) {

        switch ($field) {

            case 'Fulfilment Asset Reference':
                $label = _('reference');
                break;
            case 'Fulfilment Asset Note':
                $label = _('note');
                break;

            default:


                $label = $field;

        }

        return $label;

    }


    /**
     * @throws \Exception
     */
    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        if (!$this->deleted and $this->id) {

            switch ($field) {
                case 'Fulfilment Asset Location Key':
                    $old_value = $this->data['Fulfilment Asset Location Key'];
                    $this->update_field($field, $value, $options);
                    if ($value != '') {

                        $this->other_fields_updated = array(
                            'unlink_asset_location' => array(
                                'field'  => 'unlink_asset_location',
                                'render' => true,

                            )
                        );
                        $new_location               = get_object('Location', $value);
                        $new_location->fast_update(
                            [
                                'Location Fulfilment' => 'Yes'
                            ]
                        );
                    }

                    $this->update_metadata['class_html'] = array(
                        'Location_State_Formatted' => $this->get('Location State Formatted'),
                    );

                    if ($old_value) {
                        $old_location = get_object('Location', $old_value);
                        $old_location->update_fulfilment_status();
                    }

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
    }


}

