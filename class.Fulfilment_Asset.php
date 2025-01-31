<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 12 Jul 2021 20:37:36 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

include_once('class.DB_Table.php');


class Fulfilment_Asset extends DB_Table
{

    /**
     * @var \PDO
     */
    public $db;


    function __construct($arg1 = false, $arg2 = false)
    {
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


    function create($data)
    {
        exit();
        $this->editor = $data['editor'];

        $this->data = $this->base_data();
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $this->data)) {
                $this->data[$key] = _trim($value);
            }
        }

        if ($this->data['Fulfilment Asset Type'] == '') {
            $this->msg        = _('Field required');
            $this->new        = false;
            $this->error      = true;
            $this->error_code = 'fulfilment_asset_type_missing';

            return;
        }

        if (!in_array($this->data['Fulfilment Asset Type'], ['Pallet', 'Box', 'Oversize'])) {
            $this->msg            = _('Invalid value');
            $this->new            = false;
            $this->error          = true;
            $this->error_code     = 'invalid_fulfilment_asset_type';
            $this->error_metadata = $this->data['Fulfilment Asset Type'];
            return;
        }


        $sql = sprintf(
            "INTO `Fulfilment Asset Dimension` (%s) values (%s)",
            '`'.join('`,`', array_keys($this->data)).'`',
            join(',', array_fill(0, count($this->data), '?'))
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
                $history_data,
                true,
                'No',
                'Changes',
                $this->get_object_name(),
                $this->id
            );
        } else {
            print_r($stmt->errorInfo());
            $this->error = true;
            $this->msg   = 'Error inserting fulfilment Asset record';
        }
    }

    function get_data($key, $tag): bool
    {
        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Fulfilment Asset Dimension` WHERE `Fulfilment Asset Key`=%d",
                $tag
            );
        } else {
            return false;
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id       = $this->data['Fulfilment Asset Key'];
            $this->metadata = json_decode($this->data['Fulfilment Asset Metadata'], true);
        }

        return true;
    }

    function metadata($key)
    {
        return ($this->metadata[$key] ?? '');
    }

    function get($key)
    {
        if (!$this->id) {
            return false;
        }


        switch ($key) {
            case 'Type Icon':
                if ($this->get('Fulfilment Asset Type') == 'Pallet') {
                    return '<i class="fal fa-fw fa-pallet-alt" title="'._('Pallet').'"></i>';
                } elseif ($this->get('Fulfilment Asset Type') == 'Oversize') {
                    return '<i class="fas fa-fw fa-pallet" title="'._('Oversize').'"></i>';
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
                } elseif ($this->data['Fulfilment Asset Type'] == 'Oversize') {
                    return _('Oversize');
                } else {
                    return _('Box');
                }
            case 'Formatted Location':
                if ($this->data['Fulfilment Asset Location Key'] != '') {
                    $location = get_object('Location', $this->data['Fulfilment Asset Location Key']);

                    return '<i class="fa fa-pallet"></i> '.$location->get('Code');
                } else {
                    return '';
                }
            case ('State'):
                switch ($this->data['Fulfilment Asset State']) {
                    case 'InProcess':
                        return _('In process');

                    case 'Received':
                        return _('Received');
                    case 'BookedIn':
                        return _('Booked in');
                    case 'BookedOut':
                        return _('Booked out');
                    case 'Invoiced':
                        return _('Booked out (invoiced)');

                    case 'Lost':
                        return _('Lost');
                    default:
                        break;
                }

                break;
            case 'State Index':


                switch ($this->data['Fulfilment Asset State']) {
                    case 'InProcess':
                        return 10;

                    case 'Received':
                        return 40;
                    case 'BookedIn':
                        return 60;
                    case 'BookedOut':
                        return 80;
                    case 'Invoiced':
                        return 100;
                    case 'Lost':
                        return -10;
                    default:
                        return 0;
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

            case 'From':
            case 'To':

                if ($this->data['Fulfilment Asset '.$key] == '') {
                    return '';
                }
                return strftime("%a %e %b %Y", strtotime($this->data['Fulfilment Asset '.$key].' +0:00'));

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


    function delete($metadata = ''): string
    {
        exit();
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
            $history_data,
            true,
            'No',
            'Changes',
            $fulfilment_delivery->get_object_name(),
            $fulfilment_delivery->id
        );

        return '/fulfilment/'.$fulfilment_delivery->get('Fulfilment Delivery Warehouse Key').'/customers/'.($fulfilment_delivery->get('Fulfilment Delivery Type') == 'Part' ? 'dropshipping' : 'asset_keeping').'/'.$fulfilment_delivery->get(
                'Fulfilment Delivery Customer Key'
            ).'/delivery/'.$fulfilment_delivery->id;
    }

    function get_field_label($field)
    {
        switch ($field) {
            case 'Fulfilment Asset Reference':
                $label = _('reference');
                break;
            case 'Fulfilment Asset Note':
                $label = _('note');
                break;
            case 'Fulfilment Asset Type':
                $label = _('type');
                break;
            case 'Fulfilment Asset From':
                $label = _('received date');
                break;
            case 'Fulfilment Asset To':
                $label = _('book out date');
                break;
            default:


                $label = $field;
        }

        return $label;
    }


    /**
     * @throws \Exception
     */
    function
    update_field_switcher(
        $field,
        $value,
        $options = '',
        $metadata = ''
    ) {
        exit();

        if (!$this->deleted and $this->id) {
            switch ($field) {
                case 'Fulfilment Asset From':

                    if ($this->data['Fulfilment Asset To'] != '') {
                        if (strtotime($this->data['Fulfilment Asset To']) < strtotime($value)) {
                            $this->error = true;
                            $this->msg   = _('Received date must has to be before the book out date');
                            return;
                        }
                    }

                    if (strtotime($value) > strtotime('tomorrow')) {
                        $this->error = true;
                        $this->msg   = _('Date can not be in the future');
                        return;
                    }

                    $this->update_field($field, $value, $options);
                    break;
                case 'Fulfilment Asset To':

                    if ($this->data['Fulfilment Asset From'] == '') {
                        $this->error = true;
                        $this->msg   = 'Booked in date is empty';
                        return;
                    }

                    if (strtotime($value) < strtotime($this->data['Fulfilment Asset From'])) {
                        $this->error = true;
                        $this->msg   = _('Booked out must has to be after received');
                        return;
                    }


                    if (strtotime($value) > strtotime('tomorrow')) {
                        $this->error = true;
                        $this->msg   = _('Date can not be in the future');
                        return;
                    }

                    $this->update_field($field, $value, $options);
                    break;

                case 'Fulfilment Asset State':
                    $this->update_field($field, $value, $options);
                    $show = [];
                    $hide = [];
                    if ($value == 'BookedOut') {
                        $this->fast_update(
                            ['Fulfilment Asset To' => gmdate('Y-m-d H:i:s')]

                        );
                        $this->update(
                            ['Fulfilment Asset Location Key' => ''],
                            'no_history'
                        );


                        $show[] = 'undo_booked_out_operation';
                        $hide[] = 'booked_out_operation';
                    } elseif ($value == 'BookedIn') {
                        $this->fast_update(['Fulfilment Asset To' => '']);


                        $hide[] = 'undo_booked_out_operation';
                        $show[] = 'booked_out_operation';
                    }

                    $this->update_metadata['hide']       = $hide;
                    $this->update_metadata['show']       = $show;
                    $this->update_metadata['class_html'] = array(
                        'Fulfilment_Asset_To'    => $this->get('To'),
                        'Fulfilment_Asset_State' => $this->get('State'),
                    );


                    break;
                case 'label box':
                case 'label oversize':
                case 'label pallet':
                    $this->fast_update_json_field('Fulfilment Asset Metadata', preg_replace('/ /', '_', $field), $value);
                    break;
                case 'Fulfilment Asset Type':
                    $this->update_field($field, $value, $options);
                    $this->update_metadata['class_html'] = array(
                        'Type_Icon' => $this->get('Type Icon'),
                    );
                    if ($value == 'Pallet' or $value == 'Oversize') {
                        $this->update_metadata['hide'] = array('pdf_label_container_box');
                        $this->update_metadata['show'] = array('pdf_label_container_pallet');
                    } else {
                        $this->update_metadata['hide'] = array('pdf_label_container_pallet');
                        $this->update_metadata['show'] = array('pdf_label_container_box');
                    }

                    break;
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
                        /**
                         * @var $old_location \Location
                         */
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

    function get_labels_data(): array
    {
        $labels_data = [];


        $label_box    = json_decode($this->metadata('label_box'), true);
        $label_pallet = json_decode($this->metadata('label_pallet'), true);

        if ($label_box == '' or $label_pallet == '') {
            $warehouse = get_object('Warehouse', $this->data['Fulfilment Asset Warehouse Key']);
            if ($label_box == '') {
                $label_box = json_decode($warehouse->settings('label_fulfilment_asset_box'), true);
                if ($label_box == '') {
                    $label_box = [
                        'size'   => 'EU30036',
                        'set_up' => 'single'
                    ];
                    $warehouse->fast_update_json_field('Warehouse Settings', 'label_fulfilment_asset_box', json_encode($label_box));
                }
            }


            if ($label_pallet == '') {
                $label_pallet = json_decode($warehouse->settings('label_fulfilment_asset_pallet'), true);
                if ($label_pallet == '') {
                    $label_pallet = [
                        'size'   => 'A4',
                        'set_up' => 'single'
                    ];
                    $warehouse->fast_update_json_field('Warehouse Settings', 'label_fulfilment_asset_pallet', json_encode($label_pallet));
                }
            }
        }


        $labels_data['box']    = $label_box;
        $labels_data['pallet'] = $label_pallet;

        return $labels_data;
    }

}

