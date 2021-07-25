<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 22 Jul 2021 13:20:54 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

include_once('class.DB_Table.php');


class Picking_Pipeline extends DB_Table {

    /**
     * @var \PDO
     */
    public $db;


    function __construct($arg1 = false, $arg2 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Picking Pipeline';
        $this->ignore_fields = array('Picking Pipeline Key');

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

        $this->editor = $data['editor'];

        $this->data = $this->base_data();
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $this->data)) {
                $this->data[$key] = _trim($value);
            }
        }


        $sql = sprintf(
            "INTO `Picking Pipeline Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($this->data)).'`', join(',', array_fill(0, count($this->data), '?'))
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


            $history_data = array(
                'History Abstract' => sprintf(_('Picking pipeline %s created'), $this->get('Name')),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $history_key = $this->add_subject_history(
                $history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id
            );

            $warehouse         = get_object('Warehouse', $this->get('Warehouse Key'));
            $warehouse->editor = $this->editor;
            $store             = get_object('Store', $this->get('Store Key'));
            $store->editor     = $this->editor;

            $store->create_subject_history_bridge($history_key, 'No', 'Changes', $store->get_object_name(), $store->id);

            $history_data = array(
                'History Abstract' => sprintf(_('Picking pipeline %s for store %s created'), $this->get('Name'), $store->get('Name')),
                'History Details'  => '',
                'Action'           => 'created'
            );

            $warehouse->add_subject_history(
                $history_data, true, 'No', 'Changes', $warehouse->get_object_name(), $warehouse->id
            );

            $warehouse->update_warehouse_aggregations();


        } else {
            print_r($stmt->errorInfo());
            $this->error = true;
            $this->msg   = 'Error inserting picking pipeline record';
        }

    }

    function get_data($key, $tag): bool {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Picking Pipeline Dimension` WHERE `Picking Pipeline Key`=%d", $tag
            );
        } else {
            return false;
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id       = $this->data['Picking Pipeline Key'];
            $this->metadata = json_decode($this->data['Picking Pipeline Metadata'], true);

        }

        return true;

    }

    function metadata($key) {
        return ($this->metadata[$key] ?? '');
    }

    function get($key) {


        if (!$this->id) {
            return false;
        }


        switch ($key) {


            default:


                if (array_key_exists('Picking Pipeline '.$key, $this->data)) {
                    return $this->data[$this->table_name.' '.$key];
                }

                if (isset($this->data[$key])) {
                    return $this->data[$key];
                } else {
                    return '';
                }
        }

    }


    function delete(): string {

        $warehouse         = get_object('Warehouse', $this->get('Warehouse Key'));
        $warehouse->editor = $this->editor;
        $store             = get_object('Store', $this->get('Store Key'));
        $store->editor     = $this->editor;

        $this->deleted     = false;
        $this->deleted_msg = '';


        $sql  = "DELETE FROM `Picking Pipeline Dimension` WHERE `Picking Pipeline Key`=?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $this->id);

        if ($stmt->execute()) {
            $this->deleted = true;

        } else {
            $this->deleted_msg = 'Error picking pipeline can not be deleted';
        }

        $sql  = "Select `Location Picking Pipeline Key`,`Location Picking Pipeline Location Key`  from `Location Picking Pipeline Bridge` where `Location Picking Pipeline Picking Pipeline Key`=? ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        while ($row = $stmt->fetch()) {
            //Todo update store products availability

            $sql = "delete from  `Location Picking Pipeline Bridge` where  `Location Picking Pipeline Key`=?";
            $this->db->prepare($sql)->execute(
                array(
                    $row['Location Picking Pipeline Key']
                )
            );


        }


        $history_data = array(
            'History Abstract' => sprintf(_('Picking pipeline %s deleted'), $this->get('Name')),
            'History Details'  => '',
            'Action'           => 'deleted'
        );

        $history_key = $warehouse->add_subject_history(
            $history_data, true, 'No', 'Changes', $warehouse->get_object_name(), $warehouse->id
        );

        $store->create_subject_history_bridge($history_key, 'No', 'Changes', $store->get_object_name(), $store->id);

        $warehouse->update_warehouse_aggregations();

        return '/warehouse/'.$warehouse->id.'/pipelines';


    }

    function get_field_label($field) {

        switch ($field) {

            case 'Picking Pipeline Name':
                $label = _('name');
                break;


            default:


                $label = $field;

        }

        return $label;

    }


    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        if (!$this->deleted and $this->id) {
            switch ($field) {


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

    function add_location($location_key){

        $sql="insert into `Location Picking Pipeline Bridge` (`Location Picking Pipeline Picking Pipeline Key`,`Location Picking Pipeline Location Key`,`Location Picking Pipeline Creation Date`) values (?,?,?) ";
        $this->db->prepare($sql)->execute(
            array(
                $this->id,
                $location_key,
                gmdate('Y-m-s H:i:s')
            )
        );


    }
}

