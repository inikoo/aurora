<?php
/*
 File: PartLocation.php

 This file contains the PartLocation Class

 About:
 Author: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

use Elasticsearch\ClientBuilder;

include_once 'class.Part.php';
include_once 'class.Location.php';
include_once 'class.InventoryAudit.php';

class PartLocation extends DB_Table
{

    /**
     * @var \PDO
     */
    public $db;
    /**
     * @var \Part
     */
    public $part;
    /**
     * @var \Location
     */
    public $location;

    var $ok = false;

    function __construct($arg1 = false, $arg2 = false, $arg3 = false)
    {
        global $db;
        $this->db = $db;

        $this->table_name = 'Part Location';

        if (is_array($arg1)) {
            $data = $arg1;
            if (isset($data['LocationPart'])) {
                $tmp                = preg_split("/\_/", $data['LocationPart']);
                $this->location_key = $tmp[1];
                $this->part_sku     = $tmp[2];
            } else {
                //print "---- $data   --------\n";
                $this->location_key = $data['Location Key'];
                $this->part_sku     = $data['Part SKU'];
            }
            $this->date = gmdate("Y-m-d");
        } else {
            if ($arg1 == 'find') {
                $this->find($arg2, $arg3);

                return;
            } elseif (is_numeric($arg1) and is_numeric($arg2)) {
                $this->part_sku     = $arg1;
                $this->location_key = $arg2;
                $this->get_data();

                return;
            } else {
                $tmp = preg_split("/\_/", $arg1);
                if (count($tmp) == 2) {
                    $this->part_sku     = $tmp[0];
                    $this->location_key = $tmp[1];
                    $this->get_data();
                }

                return;
            }
        }
    }


    function find($raw_data, $options)
    {
        if (isset($raw_data['editor'])) {
            foreach ($raw_data['editor'] as $key => $value) {
                if (array_key_exists($key, $this->editor)) {
                    $this->editor[$key] = $value;
                }
            }
        }

        $this->found = false;
        $create      = '';
        $update      = '';
        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }
        if (preg_match('/update/i', $options)) {
            $update = 'update';
        }

        $data = $this->base_data();
        foreach ($raw_data as $key => $val) {
            $_key        = $key;
            $data[$_key] = $val;
        }

        $this->location = new Location($data['Location Key']);
        if (!$this->location->id) {
            $warehouse = get_object('Warehouse', $_SESSION['current_warehouse']);


            $this->location = get_object('Location', $warehouse->get('Warehouse Unknown Location Key'));
        }
        $this->location_key                  = $this->location->id;
        $data['Part Location Warehouse Key'] = $this->location->data['Location Warehouse Key'];

        $this->part = new Part($data['Part SKU']);
        if (!$this->part->id) {
            $this->error = true;
            $this->msg   = _('Part not found');
        } else {
            $this->part_sku = $this->part->id;
        }

        $sql = sprintf(
            "SELECT `Location Key`,`Part SKU` FROM `Part Location Dimension` WHERE `Part SKU`=%d AND `Location Key`=%d",
            $this->part_sku,
            $this->location_key
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->found = true;
                $this->get_data();
            }
        }


        if ($create and !$this->found) {
            $this->create($data, $options);
        }

        if ($update and $this->found) {
            $this->update($data, $options);
        }
    }


    function get_data()
    {
        $this->current = false;
        $sql           = sprintf(
            "SELECT * FROM `Part Location Dimension` WHERE `Part SKU`=%d AND `Location Key`=%d",
            $this->part_sku,
            $this->location_key
        );


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->ok      = true;
            $this->id      = true;
            $this->current = true;
        }

        $this->part     = get_object('Part', $this->part_sku);
        $this->location = get_object('Location', $this->location_key);
    }

    function create($data)
    {
        //print_r($data);

        $data['Part Location Metadata'] = '{}';

        $this->data = $this->base_data();
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $this->data)) {
                $this->data[$key] = _trim($value);
            }
        }


        // $location = new Location($this->data['Location Key']);


        $keys   = '(';
        $values = 'values(';
        foreach ($this->data as $key => $value) {
            $keys  .= "`$key`,";
            $_mode = true;
            if ($key == 'Last Updated') {
                $values .= 'NOW(),';
            } else {
                $values .= prepare_mysql($value, $_mode).",";
            }
        }
        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);
        $sql    = sprintf(
            "INSERT INTO `Part Location Dimension` %s %s",
            $keys,
            $values
        );

        if ($this->db->exec($sql)) {
            $this->id  = $this->db->lastInsertId();
            $this->new = true;

            $this->part_sku     = $this->data['Part SKU'];
            $this->location_key = $this->data['Location Key'];
            $this->get_data();

            if (array_key_exists('Date', $data)) {
                $date = $data['Date'];
            } elseif (!$this->editor['Date']) {
                $date = gmdate("Y-m-d H:i:s");
            } else {
                $date = $this->editor['Date'];
            }

            $associate_data = array('date' => $date);
            $this->associate($associate_data);

            if (!$this->part->get_picking_location_key()) {
                $this->update_field_switcher('Can Pick', 'Yes', 'no_history');
            }

            $this->process_aiku_fetch('StockLocations',$this->part_sku);

            $this->new = true;
        } else {
            exit($sql);
        }
    }

    function associate($data = false)
    {
        $base_data = array(
            'date'         => gmdate('Y-m-d H:i:s'),
            'note'         => sprintf(_('Part %s associated with %s'), $this->part->get('Reference'), $this->location->get('Code')),
            'metadata'     => '',
            'history_type' => 'Admin'
        );
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                $base_data[$key] = $val;
            }
        }


        $sql = sprintf(
            "INSERT INTO `Inventory Transaction Fact` (`Inventory Transaction Record Type`,`Inventory Transaction Section`,`Date`,`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Note`,`Metadata`,`History Type`)
		VALUES (%s,%s,%s,%d,%d,%s,0,0,%s,%s,%s)",
            "'Helper'",
            "'Other'",
            prepare_mysql($base_data['date']),
            $this->part_sku,
            $this->location_key,
            "'Associate'",
            prepare_mysql($base_data['note'], false),
            prepare_mysql($base_data['metadata'], false),
            prepare_mysql($base_data['history_type'], false)

        );
        //print_r($base_data);
        // print "$sql\n";
        // exit;


        $this->db->exec($sql);
        $associate_transaction_key = $this->db->lastInsertId();
        $this->process_aiku_fetch('OrgStockMovement',$associate_transaction_key);



        $audit_key = $this->audit(0, _('Part associated with location'), $base_data['date'], $include_current = false, $parent = 'associate');
        $sql       = sprintf("UPDATE `Inventory Transaction Fact` SET `Relations`=%d WHERE `Inventory Transaction Key`=%d", $associate_transaction_key, $audit_key);
        $this->db->exec($sql);
        $this->location->update_parts();
        $this->part->update_number_locations();

        if ($this->location->get('Location Pipeline') == 'Yes') {
            $this->location->update_pipelines_data();
        }


    }

    function audit($qty, $note = '', $date = false, $include_current = false, $parent = '')
    {
        if (!$date) {
            $date = gmdate('Y-m-d H:i:s');
        }

        if (!is_numeric($qty) or $qty < 0) {
            $this->error = true;
            $this->msg   = _('Quantity On Hand should be a number');
            return null;
        }


        $old_qty = 0;

        $sql = "SELECT sum(ifnull(`Inventory Transaction Quantity`,0)) AS stock  FROM `Inventory Transaction Fact` WHERE  `Inventory Transaction Record Type`='Movement' and  `Date`<".($include_current ? '=' : '')."? AND `Part SKU`=? AND `Location Key`=?";


        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $date,
                $this->part_sku,
                $this->location_key
            )
        );
        if ($row = $stmt->fetch()) {
            if ($row['stock'] != '') {
                $old_qty = $row['stock'];
                if (!is_numeric($old_qty) ) {
                    $old_qty=0;
                }
            }
        }


        $qty_change = $qty - $old_qty;


        $value_change = round($qty_change * $this->get_sko_cost(), 2);

        $this->updated = true;

        if ($note) {
            $note = '<i class="note_data padding_left_5 fa fa-sticky-note" aria-hidden="true"></i> <span class="note">'.$note.'</span>';
        } else {
            $note = '';
        }

        if ($this->location->id) {
            $location_link = sprintf(
                '<span class="link" onClick="change_view(\'locations/%d/%d\')">%s</span>',
                $this->location->get('Warehouse Key'),
                $this->location->id,
                $this->location->get('Code')
            );
        } else {
            $location_link = '<span style="font-style:italic">'._('deleted').'</span>';
        }


        if ($qty_change != 0 or $value_change != 0) {
            $audit_note = '';
        } else {
            $audit_note = $note;
        }


        $warehouse        = get_object('Warehouse', $_SESSION['current_warehouse']);
        $unknown_location = $warehouse->get('Warehouse Unknown Location Key');


        $details = '';
        if ($parent == 'associate') {
            if ($unknown_location != $this->location->id) {
                $details = sprintf(
                    '<span class="note_data"><span class="link" onClick="change_view(\'part/%d\')"><i class="fal fa-box"></i> %s</span> <i class="fa fa-link" aria-hidden="true"></i> %s</span>%s',
                    $this->part_sku,
                    $this->part->get('Reference'),
                    $location_link,
                    $audit_note
                );
            }

            $section = 'Other';
        } elseif ($parent == 'disassociate') {
            if ($unknown_location != $this->location->id) {
                $details = sprintf(
                    '<span class="note_data"><span class="link" onClick="change_view(\'part/%d\')"><i class="fal fa-box"></i> %s</span> <i class="fa fa-unlink" aria-hidden="true"></i> %s</span>%s',
                    $this->part_sku,
                    $this->part->get('Reference'),
                    $location_link,
                    $audit_note
                );
            }
            $section = 'Other';
        } else {
            $details = sprintf(
                '<span class="note_data"><i class="fa fa-dot-circle" aria-hidden="true"></i>: %s SKO <span class="link" onClick="change_view(\'part/%d\')"><i class="fal fa-box"></i> %s</span> @ %s</span>%s',
                number($qty),
                $this->part_sku,
                $this->part->get('Reference'),
                $location_link,
                $audit_note
            );

            $section = 'Audit';
        }


        $sql = sprintf(
            "INSERT INTO `Inventory Transaction Fact` (`Inventory Transaction Record Type`,`Inventory Transaction Section`,`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`,`Part Location Stock`) VALUES (%s,%s,%d,%d,%s,%f,%.2f,%s,%s,%s,%f)",
            "'Info'",
            prepare_mysql($section),
            $this->part_sku,
            $this->location_key,
            "'Audit'",
            0,
            0,
            $this->editor['User Key'],
            prepare_mysql($details, false),
            prepare_mysql($date),
            $qty

        );
        //print $sql;
        $this->db->exec($sql);
        $audit_key = $this->db->lastInsertId();


        //  print "changes $qty_change $value_change  \n";

        if ($qty_change != 0 or $value_change != 0) {
            $details = sprintf(
                '<span class="note_data"><i class="fa fa-dot-circle" aria-hidden="true"></i>: <i class="fa fa-fw fa-sliders" aria-hidden="true"></i> <b>%s</b> SKO <span class="link" onClick="change_view(\'part/%d\')"><i class="fal fa-box"></i> %s</span> @ <span class="link" onClick="change_view(\'locations/%d/%d\')">%s</span></span>',
                ($qty_change > 0 ? '+' : '').number($qty_change),
                $this->part_sku,
                $this->part->get('Reference'),
                $this->location->get('Warehouse Key'),
                $this->location->id,
                $this->location->get('Code')
            );


            // $details='Audit: <b>['.number($qty).']</b> <a href="part.php?sku='.$this->part_sku.'">'.$this->part->id.'</a>'.' '._('adjust quantity').' '.$location_link.': '.($qty_change>0?'+':'').number($qty_change).' ('.($value_change>0?'+':'').money($value_change).')';
            if ($note) {
                $details .= $note;
            }

            $warehouse = get_object('Warehouse', $_SESSION['current_warehouse']);


            $sql = sprintf(
                "INSERT INTO `Inventory Transaction Fact` (`Inventory Transaction Record Type`,`Inventory Transaction Section`,`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`,`Relations`) VALUES (%s,%s,%d,%d,%s,%f,%.3f,%s,%s,%s,%s)",
                "'Movement'",
                "'Leakage Detail'",
                $this->part_sku,
                $this->location_key,
                "'Adjust'",
                $qty_change,
                $value_change,
                $this->editor['User Key'],
                prepare_mysql($details, false),
                prepare_mysql($date),
                prepare_mysql($audit_key)
            );


            $this->db->exec($sql);
            $leakage_transaction_key = $this->db->lastInsertId();
            $this->process_aiku_fetch('OrgStockMovement',$leakage_transaction_key);


            if ($qty_change != 0 and $this->location_key != $warehouse->get('Warehouse Unknown Location Key')) {
                $part_location_data = array(
                    'Location Key' => $warehouse->get('Warehouse Unknown Location Key'),
                    'Part SKU'     => $this->part_sku,
                    'editor'       => $this->editor
                );


                $part_unk_location = new PartLocation('find', $part_location_data, 'create');


                if ($qty_change < 0) {
                    $_details = _('F&L stack updated to offset lost stock').' (+'.-$qty_change.')';
                } else {
                    $_details = _('F&L stack updated to offset found stock').' ('.-$qty_change.')';
                }

                $sql = sprintf(
                    "INSERT INTO `Inventory Transaction Fact` (`Inventory Transaction Record Type`,`Inventory Transaction Section`,`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`,`Relations`) VALUES (%s,%s,%d,%d,%s,%f,%.3f,%s,%s,%s,%s)",
                    "'Movement'",
                    "'Leakage Detail'",
                    $part_unk_location->part_sku,
                    $part_unk_location->location_key,
                    "'Adjust'",
                    -$qty_change,
                    -$value_change,
                    $this->editor['User Key'],
                    prepare_mysql($_details, false),
                    prepare_mysql($date),
                    prepare_mysql($audit_key)
                );
                $this->db->exec($sql);
                $leakage_detail_key = $this->db->lastInsertId();
                $this->process_aiku_fetch('OrgStockMovement',$leakage_detail_key);

                $part_unk_location->update_stock(true);
                $this->part->update_unknown_location();
            }
        }

        // print "---------------------\n";


        $this->update_stock();


        $sql = sprintf(
            "UPDATE `Part Location Dimension` SET  `Part Location Last Audit`=NOW() WHERE `Part SKU`=%d AND `Location Key`=%d ",
            $this->part_sku,
            $this->location_key
        );
        $this->db->exec($sql);

        $this->get_data();


        include_once 'utils/new_fork.php';

        $account = get_object('Account', 1);


        new_housekeeping_fork(
            'au_housekeeping',
            array(
                'type'          => 'update_warehouse_leakages',
                'warehouse_key' => $this->location->get('Location Warehouse Key'),
            ),
            $account->get('Account Code')
        );



        $this->process_aiku_fetch('StockLocations',$this->part->id);

        return $audit_key;
    }

    /*
    function get_value_change_deleted($qty_change, $old_qty, $old_value, $date) {
        $qty = $old_qty + $qty_change;
        if ($qty_change > 0) {

            list($qty_above_zero, $qty_below_zero) = $this->qty_analysis(
                $old_qty, $qty
            );
            $value_change = 0;
            if ($qty_below_zero) {
                $unit_cost    = $old_value / $old_qty;
                $value_change += $qty_below_zero * $unit_cost;
            }

            if ($qty_above_zero) {

                $unit_cost    = $this->part->data['Part Cost in Warehouse'];
                $value_change += $qty_above_zero * $unit_cost;
            }


        } elseif ($qty_change < 0) {

            list($qty_above_zero, $qty_below_zero) = $this->qty_analysis(
                $old_qty, $qty
            );

            $value_change = 0;
            if ($qty_below_zero) {
                $unit_cost    = $this->part->data['Part Cost in Warehouse'];
                $value_change += -$qty_below_zero * $unit_cost;

            }

            if ($qty_above_zero) {

                $unit_cost    = $old_value / $old_qty;
                $value_change += -$qty_above_zero * $unit_cost;

            }


        } else {

            $value_change = 0;
        }

        return $value_change;
    }
    */


    function get_sko_cost()
    {
        if ($this->part->get('Part Cost in Warehouse') == '') {
            $sko_cost = $this->part->get('Part Cost');
        } else {
            $sko_cost = $this->part->get('Part Cost in Warehouse');
        }

        return $sko_cost;
    }

    function update_stock($dont_update_part_stock = false)
    {
        $old_stock = $this->data['Quantity On Hand'];
        $old_value = $this->data['Stock Value'];

        $stock = $this->get_stock();


        $value = $stock * $this->get_sko_cost();

        $this->data['Quantity On Hand']    = $stock;
        $this->data['Stock Value']         = $value;
        $this->data['Quantity In Process'] = 0;

        $sql = sprintf(
            "UPDATE `Part Location Dimension` SET `Quantity On Hand`=%f ,`Quantity In Process`=%f,`Stock Value`=%f WHERE `Part SKU`=%d AND `Location Key`=%d",
            $stock,
            0,
            $value,
            $this->part_sku,
            $this->location_key
        );


        $this->db->exec($sql);


        if (!$dont_update_part_stock) {
            $this->part->update_stock();
        }
        $this->location->update_stock_value();


        $warehouse = get_object('Warehouse', $_SESSION['current_warehouse']);

        if ($this->location->id == $warehouse->get('Warehouse Unknown Location Key')) {
            $this->part->update_unknown_location();
        }

        foreach ($this->part->get_production_suppliers('objects') as $production) {
            $production->update_locations_with_errors();
        }

        $warehouse = get_object('Warehouse', $this->get('Part Location Warehouse Key'));
        $warehouse->update_stock_amount();


        if ($old_stock != $stock or $old_value != $value) {
            include_once 'utils/new_fork.php';

            $account = get_object('Account', 1);

            $this->process_aiku_fetch('StockLocations',$this->part->id);

            new_housekeeping_fork(
                'au_housekeeping',
                array(
                    'type'         => 'update_ISF',
                    'part_sku'     => $this->part->id,
                    'location_key' => $this->location->id,
                    'debug'        => "diff: $old_stock -> $stock , $old_value -> $value\n"
                ),
                $account->get('Account Code')
            );
        }
    }

    function get_stock($date = '')
    {
        if (!$date) {
            $date = gmdate('Y-m-d H:i:s');
        }


        $sql = sprintf(
            "SELECT sum(`Inventory Transaction Quantity`) AS stock  from `Inventory Transaction Fact` WHERE  `Inventory Transaction Record Type`='Movement' and `Date`<=%s AND `Part SKU`=%d AND `Location Key`=%d  ",
            prepare_mysql($date),
            $this->part_sku,
            $this->location_key
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $stock = round($row['stock'], 3);
            } else {
                $stock = 0;
            }
        }

        return $stock;
    }

    function get($key = '', $args = false)
    {
        if (!$this->ok) {
            return;
        }


        switch ($key) {
            case 'Part Location min max':
                return array(
                    $this->get('Minimum Quantity'),
                    $this->get('Maximum Quantity')
                );
            case 'min max':
                return array(
                    ($this->get('Minimum Quantity') != '' ? number(
                        $this->get('Minimum Quantity')
                    ) : '?'),
                    ($this->get('Maximum Quantity') != '' ? number(
                        $this->get('Maximum Quantity')
                    ) : '?')
                );
            case 'Part Location Moving Quantity':
                return $this->data['Moving Quantity'];
            case 'Moving Quantity':
                return $this->data['Moving Quantity'] != '' ? number(
                    $this->data['Moving Quantity']
                ) : '?';

            default:
                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }
        }

        return false;
    }

    function update_field_switcher($field, $value, $options = '', $metadata = '')
    {
        switch ($field) {
            case 'Part Location min max':

                $value = json_decode($value, true);
                $this->update_min($value['min'], $options);
                $this->update_max($value['max'], $options);
                $this->process_aiku_fetch('StockLocations',$this->part->id);
                break;
            case('Part Location Quantity On Hand'):
            case('Quantity On Hand'):
                $this->audit($value);
                break;
            case('Can Pick'):
                $this->update_can_pick($value);
                break;
            case('Part Location Minimum Quantity'):
                $this->update_min($value, $options);
                $this->process_aiku_fetch('StockLocations',$this->part->id);
                break;
            case('Part Location Maximum Quantity'):
                $this->update_max($value, $options);
                $this->process_aiku_fetch('StockLocations',$this->part->id);
                break;
            case('Part Location Moving Quantity'):
                $this->update_move_qty($value);
                $this->process_aiku_fetch('StockLocations',$this->part->id);
                break;
        }
    }

    function update_min($value, $check_errors = true)
    {
        if (!is_numeric($value) or $value < 0) {
            $value = '';
        }

        if ($value != '' and $check_errors) {
            $sql = sprintf(
                "SELECT `Maximum Quantity` FROM `Part Location Dimension` WHERE `Part SKU`=%d AND `Location Key`=%d ",
                $this->part_sku,
                $this->location_key
            );


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    if (is_numeric($row['Maximum Quantity'])) {
                        if ($row['Maximum Quantity'] < $value || $value < 0) {
                            $this->updated = false;
                            $this->error   = true;
                            $this->msg     = _(
                                'Minimum quantity has to be lower than the maximum quantity'
                            );

                            return;
                        }
                    }
                }
            }
        }

        $sql = sprintf(
            "UPDATE `Part Location Dimension` SET `Minimum Quantity`=%s WHERE `Part SKU`=%d AND `Location Key`=%d ",
            prepare_mysql($value),
            $this->part_sku,
            $this->location_key
        );

        $update = $this->db->prepare($sql);
        $update->execute();

        if ($update->rowCount()) {
            $this->updated                  = true;
            $this->data['Minimum Quantity'] = $value;
        }
    }

    function update_max($value, $check_errors = true)
    {
        if (!is_numeric($value) or $value < 0) {
            $value = '';
        }

        if ($value != '' and $check_errors) {
            $sql = sprintf(
                "SELECT `Minimum Quantity` FROM `Part Location Dimension` WHERE `Part SKU`=%d AND `Location Key`=%d ",
                $this->part_sku,
                $this->location_key
            );


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    if (is_numeric($row['Minimum Quantity'])) {
                        if ($row['Minimum Quantity'] > $value) {
                            $this->updated = false;
                            $this->error   = true;
                            $this->msg     = 'Maximum quantity has to be greater than minimum quantity';

                            return;
                        }
                    }
                }
            }
        }

        $sql = sprintf(
            "UPDATE `Part Location Dimension` SET `Maximum Quantity`=%s WHERE `Part SKU`=%d AND `Location Key`=%d ",
            prepare_mysql($value),
            $this->part_sku,
            $this->location_key
        );

        $update = $this->db->prepare($sql);
        $update->execute();

        if ($update->rowCount()) {
            $this->updated                  = true;
            $this->data['Maximum Quantity'] = $value;
        }
    }

    function update_can_pick($value)
    {
        if (preg_match('/^(yes|si)$/i', $value)) {
            $value = 'Yes';
        } else {
            $value = 'No';
        }
        $sql = sprintf(
            "UPDATE `Part Location Dimension` SET `Can Pick`=%s ,`Last Updated`=NOW() WHERE `Part SKU`=%d AND `Location Key`=%d ",
            prepare_mysql($value),
            $this->part_sku,
            $this->location_key
        );


        $update = $this->db->prepare($sql);
        $update->execute();

        if ($update->rowCount()) {
            $this->updated = true;


            if (isset($this->data)) {
                $this->data['Can Pick'] = $value;
            }
        }
    }

    function update_move_qty($value)
    {
        if (!is_numeric($value) or $value <= 0) {
            $value = '';
        }

        $sql = sprintf(
            "UPDATE `Part Location Dimension` SET `Moving Quantity`=%s WHERE `Part SKU`=%d AND `Location Key`=%d ",
            prepare_mysql($value),
            $this->part_sku,
            $this->location_key
        );

        $update = $this->db->prepare($sql);
        $update->execute();

        if ($update->rowCount()) {
            $this->updated                 = true;
            $this->data['Moving Quantity'] = $value;
        }
    }

    function update_note($value)
    {
        $sql = sprintf(
            "UPDATE `Part Location Dimension` SET `Part Location Note`=%s ,`Last Updated`=NOW() WHERE `Part SKU`=%d AND `Location Key`=%d ",
            prepare_mysql($value),
            $this->part_sku,
            $this->location_key
        );


        $update = $this->db->prepare($sql);
        $update->execute();

        if ($update->rowCount()) {
            $this->data['Part Location Note'] = $value;
            $this->updated                    = true;
        }

        $this->process_aiku_fetch('StockLocations',$this->part->id);
    }

    function qty_analysis($a, $b)
    {
        if ($b < $a) {
            $tmp = $a;
            $a   = $b;
            $b   = $tmp;
        }

        if ($a >= 0 and $b >= 0) {
            $above = $b - $a;
            $below = 0;
        } else {
            if ($a <= 0 and $b <= 0) {
                $above = 0;
                $below = $b - $a;
            } else {
                $above = $b;
                $below = -$a;
            }
        }

        return array(
            $above,
            $below
        );
    }


    function delete()
    {
        $this->disassociate();
    }

    function disassociate($data = false)
    {
        $date = gmdate("Y-m-d H:i:s");

        if (!$this->editor['Date']) {
            $date = $this->editor['Date'];
        }
        if (is_array($data) and array_key_exists('Date', $data)) {
            $date = $data['Date'];
        }

        if (!$date or $date == '' or $date = '0000-00-00 00:00:00') {
            $date = gmdate('Y-m-d H:i:s');
        }


        $this->deleted = false;


        $base_data = array(
            'Date'         => $date,
            'Note'         => '',
            'Metadata'     => '',
            'History Type' => 'Admin'
        );
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                if (array_key_exists($key, $base_data)) {
                    $base_data[$key] = $val;
                }
            }
        }


        $sql = sprintf(
            "DELETE FROM `Part Location Dimension` WHERE `Part SKU`=%d AND `Location Key`=%d",
            $this->part_sku,
            $this->location_key
        );

        $this->db->exec($sql);


        $sql = sprintf(
            "INSERT INTO `Inventory Transaction Fact` (`Inventory Transaction Record Type`,`Inventory Transaction Section`,`Date`,`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Note`,`Metadata`,`History Type`)

		VALUES (%s,%s,%s,%d,%d,%s,0,0,%s,%s,%s)",
            "'Helper'",
            "'Other'",
            prepare_mysql($date),
            $this->part_sku,
            $this->location_key,
            "'Disassociate'",
            prepare_mysql($base_data['Note'], false),
            prepare_mysql($base_data['Metadata'], false),
            prepare_mysql($base_data['History Type'], false)

        );


        $this->db->exec($sql);
        $disassociate_transaction_key = $this->db->lastInsertId();
        $this->process_aiku_fetch('OrgStockMovement',$disassociate_transaction_key);


        $this->deleted     = true;
        $this->deleted_msg = _('Part no longer associated with location');


        $audit_key = $this->audit(0, _('Part disassociate with location'), $date, true, 'disassociate');
        $sql       = sprintf(
            "UPDATE `Inventory Transaction Fact` SET `Relations`=%d WHERE `Inventory Transaction Key`=%d",
            $disassociate_transaction_key,
            $audit_key
        );
        $this->db->exec($sql);

        $this->location->update_parts();

        if ($this->location->get('Location Pipeline') == 'Yes') {
            $this->location->update_pipelines_data();
        }

        $this->part->update_number_locations();


        if (!$this->part->get_picking_location_key()) {
            foreach ($this->part->get_locations('part_location_object') as $_part_location) {
                $_part_location->update_field_switcher('Can Pick', 'Yes');

                break;
            }
        }


        $this->process_aiku_fetch('StockLocations',$this->part->id);
    }

    function add_stock($data)
    {
        $stock_transfer_data = array(
            'Quantity'         => $data['Quantity'],
            'Transaction Type' => $data['Transaction Type'],
            'Destination'      => $this->location_key,
            'Origin'           => $data['Origin']
        );

        if (isset($data['Amount'])) {
            $stock_transfer_data['Amount'] = $data['Amount'];
        }


        $transaction_id = $this->stock_transfer($stock_transfer_data);

        if (!$transaction_id) {
            $this->error = true;
            $this->msg   = 'Can not add stock';
        } else {
        }

        return $transaction_id;
    }

    function aiku_stock_transfer($data)
    {
        $account = get_object('Account', 1);

        if (!is_numeric($this->data['Quantity On Hand'])) {
            $this->error;
            $this->msg = 'Stock quantity not numeric';
        }


        $qty_change       = $data['Quantity'];
        $transaction_type = $data['Transaction Type'];


        if (isset($data['Amount'])) {
            $value_change = $data['Amount'];
        } else {
            $value_change = $this->get_sko_cost() * $qty_change;
        }


        // print "qyy $qty_change  value  $value_change ";

        $sql = sprintf(
            "UPDATE `Part Location Dimension` SET `Quantity On Hand`=%f ,`Stock Value`=%.3f, `Last Updated`=NOW()  WHERE `Part SKU`=%d AND `Location Key`=%d ",
            $this->data['Quantity On Hand'] + $qty_change,
            ($this->data['Quantity On Hand'] + $qty_change) * $this->get_sko_cost(),
            $this->part_sku,
            $this->location_key
        );


        $trigger_discontinued = $this->part->trigger_discontinued;

        $this->db->exec($sql);
        $this->get_data();

        $this->part->trigger_discontinued = $trigger_discontinued;


        $details = '';


        // print $transaction_type."\n";

        if ($qty_change > 0) {
            $record_type = 'Movement';
            $section     = 'In';
            $details     = sprintf(_('%s SKO %s due to cancelled picking'), $qty_change, '<b>'._('send back to location').'</b>').' ('.($value_change > 0 ? '+' : '').money($value_change, $account->get('Account Currency')).') '.$data['Note'];
        } else {
            $record_type = 'Movement';
            $section     = 'Out';
            $details     = sprintf(_('%s SKO pick in aiku'), -$qty_change).' ('.($value_change > 0 ? '+' : '').money($value_change, $account->get('Account Currency')).') '.$data['Note'];
        }




        $editor = $this->get_editor_data();


        $aiku_picking_key=(int) $data['aiku_picking_id'];



        $sql = sprintf(
            "INSERT INTO `Inventory Transaction Fact` (`aiku_picking_id`,`Inventory Transaction Record Type`,`Inventory Transaction Section`,`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`)
		VALUES (%d,%s,%s,%d,%d,%s,%f,%.3f,%s,%s,%s)",
                 $aiku_picking_key,
            prepare_mysql($record_type),
            prepare_mysql($section),
            $this->part_sku,
            $this->location_key,
            prepare_mysql($transaction_type),
            $qty_change,
            $value_change,
            $this->editor['User Key'],
            prepare_mysql($details, false),
            prepare_mysql($editor['Date'])

        );


        //print "$sql\n\n\n";

        $this->db->exec($sql);
        $transaction_id = $this->db->lastInsertId();
        $this->process_aiku_fetch('OrgStockMovement',$transaction_id);

        $this->part->update_stock();

        $this->part->update_unknown_location();

        $this->location->update_parts();
        $this->update_stock_value();

        $this->updated = true;


        switch ($transaction_type) {
            case('Lost'):
                $this->part->update_leakages('Lost');
                break;
            case('Broken'):
                $this->part->update_leakages('Damaged');
                break;
            case('Found'):
                $this->part->update_leakages('Found');
                break;
            case('Other Out'):

                $this->part->update_leakages('Errors');


                break;
        }


        $this->process_aiku_fetch('StockLocations',$this->part->id);

        return $transaction_id;
    }

    function stock_transfer($data)
    {
        $account = get_object('Account', 1);

        if (!is_numeric($this->data['Quantity On Hand'])) {
            $this->error;
            $this->msg = 'Stock quantity not numeric';
        }


        $qty_change       = $data['Quantity'];
        $transaction_type = $data['Transaction Type'];


        if (isset($data['Amount'])) {
            $value_change = $data['Amount'];
        } else {
            $value_change = $this->get_sko_cost() * $qty_change;
        }


        // print "qyy $qty_change  value  $value_change ";

        $sql = sprintf(
            "UPDATE `Part Location Dimension` SET `Quantity On Hand`=%f ,`Stock Value`=%.3f, `Last Updated`=NOW()  WHERE `Part SKU`=%d AND `Location Key`=%d ",
            $this->data['Quantity On Hand'] + $qty_change,
            ($this->data['Quantity On Hand'] + $qty_change) * $this->get_sko_cost(),
            $this->part_sku,
            $this->location_key
        );


        $trigger_discontinued = $this->part->trigger_discontinued;

        $this->db->exec($sql);
        $this->get_data();

        $this->part->trigger_discontinued = $trigger_discontinued;


        $details = '';


        // print $transaction_type."\n";

        switch ($transaction_type) {
            case('AikuPick'):

                if ($qty_change > 0) {
                    $record_type = 'Movement';
                    $section     = 'In';
                    $details     = sprintf(_('%s SKO %s due to cancelled picking'), $qty_change, '<b>'._('send back to location').'</b>').' ('.($value_change > 0 ? '+' : '').money($value_change, $account->get('Account Currency')).') '.$data['Note'];
                } else {
                    $record_type = 'Movement';
                    $section     = 'Out';
                    $details     = sprintf(_('%s SKO pick in aiku'), -$qty_change).' ('.($value_change > 0 ? '+' : '').money($value_change, $account->get('Account Currency')).') '.$data['Note'];
                }

                break;
            case('Production'):

                if ($qty_change > 0) {
                    $record_type = 'Movement';
                    $section     = 'In';
                    $details     = sprintf(_('%s SKO %s due to cancelled manufacturing'), $qty_change, '<b>'._('send back').'</b>').' ('.($value_change > 0 ? '+' : '').money($value_change, $account->get('Account Currency')).') '.$data['Note'];
                } else {
                    $record_type = 'Movement';
                    $section     = 'Out';
                    $details     = sprintf(_('%s SKO send to production'), -$qty_change).' ('.($value_change > 0 ? '+' : '').money($value_change, $account->get('Account Currency')).') '.$data['Note'];
                }

                break;
            case('Lost'):
                $record_type = 'Movement';
                $section     = 'Lost';
                $details     = sprintf(_('%s SKO lost'), -$qty_change).' ('.($value_change > 0 ? '+' : '').money($value_change, $account->get('Account Currency')).') '.$data['Note'];
                break;
            case('Broken'):
                $record_type = 'Movement';
                $section     = 'Lost';
                $details     = sprintf(_('%s SKO damaged'), -$qty_change).' ('.($value_change > 0 ? '+' : '').money($value_change, $account->get('Account Currency')).') '.$data['Note'];
                break;


            case('Other Out'):

                $record_type = 'Movement';

                if ($qty_change > 0) {
                    $details          = sprintf(_('%s SKO found'), ($qty_change > 0 ? '+' : '').$qty_change).' ('.($value_change > 0 ? '+' : '').money($value_change, $account->get('Account Currency')).') '.$data['Note'];
                    $section          = 'In';
                    $transaction_type = 'Found';
                } else {
                    $details = sprintf(_('%s SKO leaked'), ($qty_change > 0 ? '+' : '').$qty_change).' ('.($value_change > 0 ? '+' : '').money($value_change, $account->get('Account Currency')).') '.$data['Note'];
                    $section = 'Lost';
                }

                break;


            case('Move Out'):
                $record_type          = 'Movement';
                $section              = 'Move Detail';
                $destination_location = new Location(
                    'code', $data['Destination']
                );
                if ($destination_location->id) {
                    $destination_link = '<a href="location.php?id='.$destination_location->id.'">'.$destination_location->data['Location Code'].'</a>';
                } else {
                    $destination_link = $data['Destination'];
                }
                $details = number(-$qty_change).'x '.'<a href="part.php?sku='.$this->part_sku.'">'.$this->part->id.'</a>'.' '._(
                        'move out from'
                    ).' <a href="location.php?id='.$this->location->id.'">'.$this->location->data['Location Code'].'</a> '._('to').' '.$destination_link.': '.($qty_change > 0 ? '+' : '').number(
                        $qty_change
                    ).' ('.($value_change > 0 ? '+' : '').money($value_change).')';
                break;
            case('Move In'):
                $record_type = 'Movement';
                $section     = 'Move Detail';
                $details     = number($qty_change).'x '.'<a href="part.php?sku='.$this->part_sku.'">'.$this->part->id.'</a>'.' '._(
                        'move in to'
                    ).' <a href="location.php?id='.$this->location->id.'">'.$this->location->data['Location Code'].'</a> '._('from').' '.$data['Origin'].': '.($qty_change > 0 ? '+' : '').number(
                        $qty_change
                    ).' ('.($value_change > 0 ? '+' : '').money($value_change).')';

                break;
            case('In'):

                $record_type = 'Movement';
                $section     = 'In';
                $details     = sprintf(
                    _('%d SKO %s received in %s from %s'),
                    $qty_change,
                    sprintf(
                        '<span class="link" onClick="change_view(\'part/%d\')">%s</span>',
                        $this->part_sku,
                        $this->part->get('Reference')
                    ),
                    sprintf(
                        '<span class="link" onClick="change_view(\'location/%d/%d\')">%s</span>',
                        $this->location->get('Location Warehouse Key'),
                        $this->location->id,
                        $this->location->get('Code')
                    ),
                    $data['Origin']

                );
                break;
            case('Restock'):

                $record_type = 'Movement';
                $section     = 'In';
                $details     = sprintf(
                    _('%d SKO %s received in %s from %s'),
                    $qty_change,
                    sprintf(
                        '<span class="link" onClick="change_view(\'part/%d\')">%s</span>',
                        $this->part_sku,
                        $this->part->get('Reference')
                    ),
                    sprintf(
                        '<span class="link" onClick="change_view(\'location/%d/%d\')">%s</span>',
                        $this->location->get('Location Warehouse Key'),
                        $this->location->id,
                        $this->location->get('Code')
                    ),
                    $data['Origin']

                );
            //$details=number($qty_change).'x '.'<a href="part.php?sku='.$this->part_sku.'">'.$this->part->id.'</a>'.' '._('received in').' <a href="location.php?id='.$this->location->id.'">'.$this->location->data['Location Code'].'</a> '._('from').' '.$data['Origin'].': '.($qty_change>0?'+':'').number($qty_change).' ('.($value_change>0?'+':'').money($value_change).')';
        }


        $editor = $this->get_editor_data();


//        $aiku_picking_key=null;
//        if(isset($data['aiku_picking_id'])){
//            $aiku_picking_key=(int) $data['aiku_picking_id'];
//        }

        $sql = sprintf(
            "INSERT INTO `Inventory Transaction Fact` (`Inventory Transaction Record Type`,`Inventory Transaction Section`,`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`)
		VALUES (%s,%s,%d,%d,%s,%f,%.3f,%s,%s,%s)",
       //     $aiku_picking_key,
            prepare_mysql($record_type),
            prepare_mysql($section),
            $this->part_sku,
            $this->location_key,
            prepare_mysql($transaction_type),
            $qty_change,
            $value_change,
            $this->editor['User Key'],
            prepare_mysql($details, false),
            prepare_mysql($editor['Date'])

        );


        //print "$sql\n\n\n";

        $this->db->exec($sql);
        $transaction_id = $this->db->lastInsertId();
        $this->process_aiku_fetch('OrgStockMovement',$transaction_id);

        $this->part->update_stock();

        $this->part->update_unknown_location();

        $this->location->update_parts();
        $this->update_stock_value();

        $this->updated = true;


        switch ($transaction_type) {
            case('Lost'):
                $this->part->update_leakages('Lost');
                break;
            case('Broken'):
                $this->part->update_leakages('Damaged');
                break;
            case('Found'):
                $this->part->update_leakages('Found');
                break;
            case('Other Out'):

                $this->part->update_leakages('Errors');


                break;
        }

        $this->process_aiku_fetch('StockLocations',$this->part->id);

        return $transaction_id;
    }

    function update_stock_value()
    {
        $old_value = $this->data['Stock Value'];

        $value = $this->get('Quantity On Hand') * $this->get_sko_cost();

        $sql = sprintf(
            "UPDATE `Part Location Dimension` SET `Stock Value`=%.3f WHERE `Part SKU`=%d AND `Location Key`=%d ",
            $value,
            $this->part_sku,
            $this->location_key
        );

        $update = $this->db->prepare($sql);
        $update->execute();

        $this->location->update_stock_value();

        if (isset($this->data['Part Location Warehouse Key'])) {
            $warehouse = get_object('Warehouse', $this->data['Part Location Warehouse Key']);
            $warehouse->update_stock_amount();
        }


        if ($old_value != $value) {
            include_once 'utils/new_fork.php';

            $account = get_object('Account', 1);

            $this->process_aiku_fetch('StockLocations',$this->part->id);

            new_housekeeping_fork(
                'au_housekeeping',
                array(
                    'type'         => 'update_ISF',
                    'part_sku'     => $this->part->id,
                    'location_key' => $this->location->id,
                    'debug'        => "diff:  $old_value -> $value\n"
                ),
                $account->get('Account Code')
            );
        }
    }

    function move_stock($data)
    {
        $this->part->trigger_discontinued = false;


        if ($this->error) {
            $this->msg = _('Unknown error');

            return;
        }

        if ($data['Quantity To Move'] == 'all') {
            $data['Quantity To Move'] = $this->data['Quantity On Hand'];
        }


        if (!is_numeric($this->data['Quantity On Hand'])) {
            $this->error = true;
            $this->msg   = _('Unknown stock in this location');

            return;
        }
        if ($this->data['Quantity On Hand'] < $data['Quantity To Move']) {
            $this->error = true;
            $this->msg   = _('To Move Quantity greater than the stock on the location');

            return;
        }


        if ($data['Destination Key'] == $this->location_key) {
            $this->error = true;
            $this->msg   = _('Destination location is the same as this one');

            return;
        }

        $destination_data = array(
            'Location Key' => $data['Destination Key'],
            'Part SKU'     => $this->part_sku,
            'editor'       => $this->editor
        );


        $destination = new PartLocation('find', $destination_data, 'create');


        if (!$destination->ok) {
            $this->error = true;
            $this->msg   = 'to location_part not found';

            return;
        }


        if (!is_numeric($destination->data['Quantity On Hand'])) {
            $this->error = true;
            $this->msg   = _('Unknown stock in the destination location');

            return;
        }


        if ($data['Quantity To Move'] != 0) {
            $from_transaction_id = $this->stock_transfer(
                array(
                    'Quantity'         => -$data['Quantity To Move'],
                    'Transaction Type' => 'Move Out',
                    'Destination'      => $destination->location->data['Location Code']

                )
            );
            if ($this->error) {
                return;
            }

            $to_transaction_id = $destination->stock_transfer(
                array(
                    'Quantity'         => $data['Quantity To Move'],
                    'Transaction Type' => 'Move In',
                    'Origin'           => $this->location->data['Location Code'],
                    //  'Value'            => -1 * $this->value_change

                )
            );


            $details = sprintf(
                _('%s SKO Inter-warehouse transfer from %s to %s'),
                number($data['Quantity To Move']),
                sprintf(
                    '<span onClick="change_view(\'locations/%d/%d\')" class="button">%s</span>',
                    $this->location->get('Location Warehouse Key'),
                    $this->location->id,
                    $this->location->get('Code')
                ),
                sprintf(
                    '<span onClick="change_view(\'locations/%d/%d\')" class="button">%s</span>',
                    $destination->location->get('Location Warehouse Key'),
                    $destination->location->id,
                    $destination->location->get('Code')
                )

            );


            //   .' <b>['.number($data['Quantity To Move']).']</b>,  <a href="location.php?id='.$this->location->id.'">'.$this->location->data['Location Code'].'</a> &rarr; <a href="location.php?id='.$destination->location->id.'">'.$destination->location->data['Location Code'].'</a>';

            $sql = sprintf(
                "INSERT INTO `Inventory Transaction Fact` (`Inventory Transaction Record Type`,`Inventory Transaction Section`,`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`,`Relations`,`Metadata`) VALUES (%s,%s,%d,%d,%s,%f,%.2f,%s,%s,%s,%s,%d)",
                "'Info'",
                "'Move'",
                $this->part_sku,
                $data['Destination Key'],
                "'Move'",
                0,
                0,
                $this->editor['User Key'],
                prepare_mysql($details, false),
                prepare_mysql($this->editor['Date']),
                prepare_mysql($from_transaction_id.','.$to_transaction_id),
                $data['Quantity To Move']
            );

            $this->db->exec($sql);
            $move_transaction_id = $this->db->lastInsertId();


            $sql = sprintf(
                "UPDATE `Inventory Transaction Fact` SET `Relations`=%s WHERE `Inventory Transaction Key`=%d",
                prepare_mysql($move_transaction_id),
                $from_transaction_id
            );
            $this->db->exec($sql);
            $sql = sprintf(
                "UPDATE `Inventory Transaction Fact` SET `Relations`=%s WHERE `Inventory Transaction Key`=%d",
                prepare_mysql($move_transaction_id),
                $to_transaction_id
            );
            $this->db->exec($sql);
        }


        $this->location->update_parts();
        $destination->location->update_parts();
    }

    function get_history_datetime_intervals()
    {
        $sql = sprintf(
            "SELECT  `Inventory Transaction Type`,(`Date`) AS Date FROM `Inventory Transaction Fact` WHERE  `Part SKU`=%d AND  `Location Key`=%d AND `Inventory Transaction Type` IN ('Associate','Disassociate')  ORDER BY `Date` ,`Inventory Transaction Key` ",
            $this->part_sku,
            $this->location_key
        );
        // print "$sql\n";
        $dates = array();


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $dates[$row['Date']] = $row['Inventory Transaction Type'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $intervals = array();


        foreach ($dates as $date => $type) {
            if ($type == 'Associate') {
                $intervals[] = array(
                    'From' => date("Y-m-d H:i:s", strtotime($date)),
                    'To'   => false
                );
            }
            if ($type == 'Disassociate') {
                $intervals[count($intervals) - 1]['To'] = gmdate("Y-m-d H:i:s", strtotime($date));
            }
        }


        return $intervals;
    }

    function update_stock_history_date($date)
    {
        $client = ClientBuilder::create()->setHosts(get_elasticsearch_hosts())
        ->setApiKey(ES_KEY1,ES_KEY2)
        ->setSSLVerification(ES_SSL)
        ->build();


        if ($this->exist_on_date($date)) {
            //   print "Yeah\n";

            if ($date == gmdate('Y-m-d')) {
                $cost_per_sko = $this->get_sko_cost();
            } else {
                $cost_per_sko = $this->get_value_per_sko($date.' 23:59:59');
            }


            $stock = $this->get_stock($date.' 23:59:59');


            $value = $stock * $cost_per_sko;

            list($sold, $sales_value) = $this->get_sales($date);
            $in   = $this->get_in($date);
            $lost = $this->get_lost($date);

            list($amount_in_po, $amount_in_other, $amount_out_sales, $amount_out_other) = $this->get_amount_deltas($date);


            if ($this->part->get('Part Commercial Value') == '') {
                $commercial_value_unit_cost = $this->part->get('Part Unit Price');
            } else {
                $commercial_value_unit_cost = $this->part->get('Part Commercial Value');
            }

            $value_day_cost_unit_cost = $this->get_sko_cost();

            $value_day_cost   = $stock * $value_day_cost_unit_cost;
            $commercial_value = $stock * $commercial_value_unit_cost;


            $params = ['body' => []];

            $params['body'][] = [
                'index' => [
                    '_index' => 'au_part_location_isf_'.strtolower(DNS_ACCOUNT_CODE),
                    '_id'    => DNS_ACCOUNT_CODE.'.'.$this->part_sku.'_'.$this->location_key.'.'.$date,

                ]
            ];


            $data = [
                'tenant'          => strtolower(DNS_ACCOUNT_CODE),
                'date'            => $date,
                '1st_day_year'    => (preg_match('/\d{4}-01-01/ ', $date) ? true : false),
                '1st_day_month'   => (preg_match('/\d{4}-\d{2}-01/', $date) ? true : false),
                '1st_day_quarter' => (preg_match('/\d{4}-(01|04|07|10)-01/', $date) ? true : false),
                '1st_day_week'    => (gmdate('w', strtotime($date)) == 0 ? true : false),
                'sku'             => $this->part_sku,
                'location_key'    => $this->location_key,
                'warehouse'       => $this->location->get('Location Warehouse Key'),


                'stock_on_hand'           => $stock,
                'stock_cost'              => $value,
                'stock_value_at_day_cost' => $value_day_cost,
                'stock_commercial_value'  => $commercial_value,

                'sold_amount' => $sales_value,

                'book_in' => $in,
                'sold'    => $sold,
                'lost'    => $lost,

                'stock_value_in_purchase_order' => $amount_in_po,
                'stock_value_in_other'          => $amount_in_other,
                'stock_value_out_sales'         => $amount_out_sales,
                'stock_value_out_other'         => $amount_out_other,

                'part_reference' => $this->part->get('Reference'),
                'location_code'  => $this->location->get('Code'),

            ];


            $params['body'][] = $data;
            $client->bulk($params);

            return array(
                'action'       => 'updated',
                'data'         => $data,
                'cost_per_sko' => $cost_per_sko


            );
        } else {
            // print "Naa\n";
            $params = [
                'index' => 'au_part_location_isf_'.strtolower(DNS_ACCOUNT_CODE),
                'id'    => DNS_ACCOUNT_CODE.'.'.$this->part_sku.'_'.$this->location_key.'.'.$date,
            ];

            // print_r($params);

            try {
                $client->delete($params);
            } catch (Exception $e) {
            }


            return array(
                'action' => 'deleted',
                'data'   => [],


            );
        }
    }

    function exist_on_date($date)
    {
        $date = gmdate('U', strtotime($date));

        $intervals = $this->get_history_intervals();


        foreach ($intervals as $interval) {
            if ($interval['To']) {
                if (!isset($interval['From'])) {
                    //print_r($this);
                    //print_r($interval);
                    exit;
                }

                if ($date >= gmdate('U', strtotime($interval['From'])) and $date <= gmdate('U', strtotime($interval['To']))) {
                    return true;
                }
            } else {
                if ($date >= gmdate('U', strtotime($interval['From']))) {
                    return true;
                }
            }
        }

        return false;
    }

    function get_history_intervals()
    {
        $sql = sprintf(
            "SELECT  `Inventory Transaction Type`,(`Date`) AS Date FROM `Inventory Transaction Fact` WHERE  `Part SKU`=%d AND  `Location Key`=%d AND `Inventory Transaction Type` IN ('Associate','Disassociate')  ORDER BY `Date` ,`Inventory Transaction Key` ",
            $this->part_sku,
            $this->location_key
        );
        // print "$sql\n";
        $dates = array();


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                // print_r($row);

                $dates[$row['Date']] = $row['Inventory Transaction Type'];
            }
        }


        $intervals = array();


        $index = 0;

        $last_type = '';

        foreach ($dates as $date => $type) {
            if ($index == 0 and $type == 'Disassociate') {
                continue;
            }

            if ($type == 'Associate' and $last_type != 'Associate') {
                $intervals[] = array(
                    'From' => date("Y-m-d", strtotime($date)),
                    'To'   => false
                );
                $last_type   = $type;
            }
            if ($type == 'Disassociate' and $last_type != 'Disassociate') {
                $intervals[count($intervals) - 1]['To'] = gmdate("Y-m-d", strtotime($date));
                $last_type                              = $type;
            }

            $index++;
        }


        return $intervals;
    }

    function get_value_per_sko($date = '')
    {
        if ($date == '') {
            $date = gmdate('Y-m-d H:i:s');
        }

        $account = get_object('Account', 1);


        if ($account->get('Account Add Stock Value Type') == 'Blockchain') {
            $sql = sprintf(
                "SELECT `Running Cost per SKO` AS sko_value from `Inventory Transaction Fact` WHERE  `Date`<=%s AND `Part SKU`=%d AND `Location Key`=%d  and   `Running Cost per SKO`  is not null order by `Date` desc  ",
                prepare_mysql($date),
                $this->part_sku,
                $this->location_key
            );

            //            print $sql;

            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $value_per_sko = $row['sko_value'];
                    //$value_per_sko = $row['Running Cost per SKO'];
                } else {
                    $value_per_sko = $this->part->get('Part Cost');
                    // $value_per_sko = $this->part->get('Part Cost');

                }
            }

            return $value_per_sko;
        } else {
            $sql = sprintf(
                'select  (`Inventory Transaction Amount`/`Inventory Transaction Quantity`) as value_per_sko ,`ITF POTF Costing Done POTF Key` from    `ITF POTF Costing Done Bridge` B  left join     `Inventory Transaction Fact` ITF   on  (B.`ITF POTF Costing Done ITF Key`=`Inventory Transaction Key`)  
where  `Inventory Transaction Amount`>0 and `Inventory Transaction Quantity`>0    and  ITF.`Part SKU`=%d  and `Date`<=%s order by `Date` desc  limit 1 ',
                $this->part_sku,
                prepare_mysql($date)
            );


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    if ($row['ITF POTF Costing Done POTF Key'] > 0) {
                        $value_per_sko = $row['value_per_sko'];
                    } else {
                        $value_per_sko = $this->part->get('Part Cost');
                    }
                } else {
                    $value_per_sko = $this->part->get('Part Cost');
                }
            }

            return $value_per_sko;
        }
    }

    function get_sales($date)
    {
        $start = $date.' 00:00:00';
        $end   = $date.' 23:59:59';


        $sql = sprintf(
            "SELECT ifnull(sum(`Inventory Transaction Quantity`),0) AS stock ,ifnull(sum(`Amount In`),0) AS value FROM `Inventory Transaction Fact` WHERE  `Date`>=%s and `Date`<=%s  AND `Part SKU`=%d AND `Location Key`=%d AND `Inventory Transaction Type` = 'Sale'",
            prepare_mysql($start),
            prepare_mysql($end),
            $this->part_sku,
            $this->location_key
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $stock = -$row['stock'];
                $value = $row['value'];
            } else {
                $stock = 0;
                $value = 0;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return array(
            $stock,
            $value
        );
    }

    function get_in($date)
    {
        $start = $date.' 00:00:00';
        $end   = $date.' 23:59:59';

        /*
         *
         * This one was made becuse somethins mone in and move out dont match :S better address why that happen or just ignore it
        $sql = sprintf(
            "SELECT ifnull(sum(`Inventory Transaction Quantity`),0) AS stock  FROM `Inventory Transaction Fact` WHERE  `Date`>=%s and `Date`<=%s  AND `Part SKU`=%d AND `Location Key`=%d AND ( `Inventory Transaction Type` IN ('In','Move In','Move Out') OR  (`Inventory Transaction Type` LIKE 'Audit' AND `Inventory Transaction Quantity`>0 ) )   ",
            prepare_mysql($start), prepare_mysql($end), $this->part_sku, $this->location_key
        );
*/

        $sql = sprintf(
            "SELECT ifnull(sum(`Inventory Transaction Quantity`),0) AS stock  FROM `Inventory Transaction Fact` WHERE  `Date`>=%s and `Date`<=%s  AND `Part SKU`=%d AND `Location Key`=%d AND `Inventory Transaction Section`='In'   ",
            prepare_mysql($start),
            prepare_mysql($end),
            $this->part_sku,
            $this->location_key
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $stock = $row['stock'];
            } else {
                $stock = 0;
            }
        }


        return $stock;
    }

    function get_lost($date)
    {
        //to do

        return 0;

        $start = $date.' 00:00:00';
        $end   = $date.' 23:59:59';

        $sql = sprintf(
            "SELECT ifnull(sum(`Inventory Transaction Quantity`),0) AS stock FROM `Inventory Transaction Fact` WHERE  `Date`>=%s and `Date`<=%s   AND `Part SKU`=%d AND `Location Key`=%d AND  `Inventory Transaction Section`='Lost'   ",
            prepare_mysql($start),
            prepare_mysql($end),
            $this->part_sku,
            $this->location_key
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $stock = $row['stock'];
            } else {
                $stock = 0;
            }
        }

        return $stock;
    }

    function get_amount_deltas($date)
    {
        $start = $date.' 00:00:00';
        $end   = $date.' 23:59:59';

        $sql = sprintf(
            "SELECT ifnull(sum(`Inventory Transaction Amount`),0) AS value FROM `Inventory Transaction Fact` WHERE `Date`>=%s and `Date`<=%s  AND `Part SKU`=%d AND `Location Key`=%d AND `Inventory Transaction Type` ='In'",
            prepare_mysql($start),
            prepare_mysql($end),
            $this->part_sku,
            $this->location_key
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $amount_in_po = $row['value'];
            } else {
                $amount_in_po = 0;
            }
        }

        $sql = sprintf(
            "SELECT ifnull(sum(`Inventory Transaction Amount`),0) AS value FROM `Inventory Transaction Fact` WHERE `Date`>=%s and `Date`<=%s  AND `Part SKU`=%d AND `Location Key`=%d AND  `Inventory Transaction Type` in ('Found','Restock')   ",
            prepare_mysql($start),
            prepare_mysql($end),
            $this->part_sku,
            $this->location_key
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $amount_in_other = $row['value'];
            } else {
                $amount_in_other = 0;
            }
        }

        $sql = sprintf(
            "SELECT ifnull(sum(`Inventory Transaction Amount`),0) AS value FROM `Inventory Transaction Fact` WHERE `Date`>=%s and `Date`<=%s  AND `Part SKU`=%d AND `Location Key`=%d AND `Inventory Transaction Type` ='Sale'",
            prepare_mysql($start),
            prepare_mysql($end),
            $this->part_sku,
            $this->location_key
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $amount_out_sales = $row['value'];
            } else {
                $amount_out_sales = 0;
            }
        }

        $sql = sprintf(
            "SELECT ifnull(sum(`Inventory Transaction Amount`),0) AS value FROM `Inventory Transaction Fact` WHERE `Date`>=%s and `Date`<=%s  AND `Part SKU`=%d AND `Location Key`=%d AND `Inventory Transaction Section`='Out' ",
            prepare_mysql($start),
            prepare_mysql($end),
            $this->part_sku,
            $this->location_key
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $all_out = $row['value'];
            } else {
                $all_out = 0;
            }
        }

        $amount_out_other = $all_out - $amount_out_sales;

        return array(
            $amount_in_po,
            $amount_in_other,
            $amount_out_sales,
            $amount_out_other
        );
    }


}



