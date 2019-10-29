<?php
/*
 /*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 September 2017 at 17:38:20 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


include_once('class.DB_Table.php');

class TimeseriesRecord extends DB_Table {
    /**
     * @var \PDO
     */
    public $db;

    function __construct($a1, $a2 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Timeseries Record';
        $this->ignore_fields = array('Timeseries Record Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } else {
            if (($a1 == 'new' or $a1 == 'create') and is_array($a2)) {
                $this->find($a2, 'create');

            } elseif (preg_match('/find/i', $a1)) {
                $this->find($a2, $a1);
            } else {
                $this->get_data($a1, $a2);
            }
        }

    }

    function get_data($tipo, $tag) {



        $sql = sprintf(
            "SELECT * FROM `Timeseries Record Dimension` LEFT JOIN `Timeseries Dimension` ON (`Timeseries Key`=`Timeseries Record Timeseries Key`)  WHERE `Timeseries Record Key`=%d", $tag
        );


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Timeseries Record Key'];
        }


    }


    function get($key = '',$arg='') {


        switch ($key) {

            case 'Code':
                $code = '';
                switch ($this->data['Timeseries Frequency']) {
                    case 'Daily':
                        $code = _('Daily');
                        break;
                    case 'Weekly':
                        $code = _('Weekly');
                        break;
                    case 'Monthly':
                        $code = sprintf(_('month %s'), strftime('%b %Y', strtotime($this->data['Timeseries Record Date'])));
                        break;
                    case 'Quarterly':
                        $code = _('Quarterly');
                        break;
                    case 'Yearly':
                        $code = _('Yearly');
                        break;

                }

                return $code;
                break;

            case 'Deliveries':
                return number($this->data['Timeseries Record Integer B']);
                break;
            case 'Dispatched':
                return number($this->data['Timeseries Record Integer A']);
                break;
            case 'Sales':
                return money($this->data['Timeseries Record Float A'],$arg->get('Currency Code'));
                break;
            case 'Purchased Amount':
                return money($this->data['Timeseries Record Float B'],$arg->get('Currency Code'));
                break;
            case 'Supplier Deliveries':
                return number($this->data['Timeseries Record Integer C']);
                break;
            case 'Timeseries Record Deliveries':
                return $this->data['Timeseries Record Integer B'];
                break;
            case 'Timeseries Record Dispatched':
                return $this->data['Timeseries Record Integer A'];
                break;
            case 'Timeseries Record Sales':
                return $this->data['Timeseries Record Float A'];
                break;
            case 'Timeseries Record Purchased Amount':
                return $this->data['Timeseries Record Float B'];
                break;
            case 'Timeseries Record Supplier Deliveries':
                return $this->data['Timeseries Record Integer C'];
                break;


                break;
            default:
                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Timeseries Record '.$key, $this->data)) {
                    return $this->data[$this->table_name.' '.$key];
                }


                return false;
        }


    }


}