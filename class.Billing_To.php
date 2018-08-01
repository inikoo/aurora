<?php

/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 23 May 2014 15:51:16 CEST, Malaga , Spain

 Version 2.0
*/


class Billing_To extends DB_Table {


    function __construct($arg1 = false, $arg2 = false) {

        global $db;
        $this->db = $db;

        $this->table_name    = 'Billing To';
        $this->ignore_fields = array('Billing To Key');

        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }
        if (preg_match('/^(create|new)/i', $arg1)) {
            $this->find($arg2, 'create');

            return;
        }
        if (preg_match('/find/i', $arg1)) {
            $this->find($arg2, $arg1);

            return;
        }
        $this->get_data($arg1, $arg2);

        return;

    }


    function get_data($tipo, $tag) {

        if ($tipo == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Billing To Dimension` WHERE `Billing To Key`=%d", $tag
            );
        } else {
            return;
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Billing To Key'];
        }





    }



    function display($tipo) {

        include_once('utils/geography_functions.php');

        $separator = '\n';

        if ($tipo == 'xhtml') {
            $separator = '<br/>';
        }


        $address = '';


        if ($this->data['Billing To Contact Name'] != '') {
            if ($tipo == 'xhtml') {
                $address .= '<span style="text-decoration:underline">'.htmlspecialchars(
                        _trim($this->data['Billing To Contact Name'])
                    ).'</span>'.$separator;
            } else {
                $address .= _trim($this->data['Billing To Contact Name']).$separator;
            }
        }
        if ($this->data['Billing To Company Name'] != '') {
            $address .= htmlspecialchars(
                    _trim($this->data['Billing To Company Name'])
                ).$separator;
        }


        if ($address != '') {
            $address
                = '<div style="font-style:italic;color:#444;margin-bottom:5px">'.$address.'</div>';
        }


        if ($this->data['Billing To Line 1'] != '') {
            $address .= htmlspecialchars(
                    _trim($this->data['Billing To Line 1'])
                ).$separator;
        }
        if ($this->data['Billing To Line 2'] != '') {
            $address .= htmlspecialchars(
                    _trim($this->data['Billing To Line 2'])
                ).$separator;
        }

        if ($this->data['Billing To Line 3'] != '') {
            $address .= htmlspecialchars(
                    _trim($this->data['Billing To Line 3'])
                ).$separator;
        }
        $town_address = htmlspecialchars(_trim($this->data['Billing To Town']));
        if ($town_address != '') {
            $address .= $town_address.$separator;
        }

        if ($this->data['Billing To Line 4'] != '') {
            $address .= _trim($this->data['Billing To Line 3']).$separator;
        }
        $ps_address = _trim($this->data['Billing To Postal Code']);
        if ($ps_address != '') {
            $address .= $ps_address.$separator;
        }


        if ($tipo == 'xhtml') {
            $address .= '<b>'.translate_country_name(
                    $this->data['Billing To Country Name']
                ).'</b>';
        } else {
            $address .= translate_country_name(
                $this->data['Billing To Country Name']
            );
        }

        if ($tipo == 'xhtml') {
            if ($this->data['Billing To Telephone'] != '') {
                $address .= '<div style="font-style:italic;color:#444;margin-top:5px">'._('Tel').': '._trim($this->data['Billing To Telephone']).'</div>';
            }

        } else {

            if ($this->data['Billing To Telephone'] != '') {
                $address .= $separator.$separator;
            }

            if ($this->data['Billing To Telephone'] != '') {
                $address .= _('Tel').': '._trim(
                        $this->data['Billing To Telephone']
                    );
            }
        }


        return _trim($address);

    }

    function get($key = '') {


        switch ($key) {

            case 'Billing To First Name':
                if ($this->data['Billing To Contact Name'] != '') {
                    $name_data = Contact::parse_name(
                        $this->data['Billing To Contact Name']
                    );

                    return $name_data['Contact First Name'];
                } else {
                    return '';
                }
                break;
            case 'Billing To Surname':
                if ($this->data['Billing To Contact Name'] != '') {
                    $name_data = Contact::parse_name(
                        $this->data['Billing To Contact Name']
                    );

                    return $name_data['Contact Surname'];

                } else {
                    return '';
                }
                break;
            case '2line':
                $line2   = array();
                $line2[] = $this->data['Billing To Line 1'];
                $line2[] = $this->data['Billing To Line 2'];
                if ($line2[1] != '') {
                    $line2[1] .= ', ';
                }
                $line2[1] .= $this->data['Billing To Line 3'];

                return $line2;
                break;
            case 'World Region Code':

                if ($this->data['Billing To Country Code'] == '') {
                    return 'UNKN';
                }

                $sql    = sprintf(
                    "SELECT `World Region Code` FROM kbase.`Country Dimension` WHERE `Country Code`=%s", prepare_mysql($this->data['Billing To Country Code'])
                );
                $result = mysql_query($sql);
                if ($row = mysql_fetch_array($result)) {
                    return $row['World Region Code'] == '' ? 'UNKN' : $row['World Region Code'];
                } else {
                    return 'UNKN';
                }
                break;
            default:
                if (isset($this->data[$key])) {
                    return $this->data[$key];
                }


                $_key = ucfirst($key);
                if (isset($this->data[$_key])) {
                    return $this->data[$_key];
                }
                break;
        }


    }


}
