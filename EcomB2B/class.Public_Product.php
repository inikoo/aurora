<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Based in 2009 class.Product.php
 Created: 28 November 2016 at 10:01:35 GMT+8, Yiwu, China
 Copyright (c) 2016, Inikoo

 Version 3

*/


class Public_Product {

    function __construct($arg1 = false, $arg2 = false, $arg3 = false) {

        global $db;
        $this->db = $db;
        $this->id = false;

        $this->webpage = false;

        $this->table_name = 'Product';

        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }

        $this->get_data($arg1, $arg2, $arg3);


    }


    function get_data($key, $id, $aux_id = false) {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Product Dimension` WHERE `Product ID`=%d", $id
            );
            if ($this->data = $this->db->query($sql)->fetch()) {
                $this->id          = $this->data['Product ID'];
                $this->historic_id = $this->data['Product Current Key'];
            }
        } elseif ($key == 'store_code') {
            $sql = sprintf(
                "SELECT * FROM `Product Dimension` WHERE `Product Store Key`=%s  AND `Product Code`=%s", $id, prepare_mysql($aux_id)
            );
            if ($this->data = $this->db->query($sql)->fetch()) {
                $this->id          = $this->data['Product ID'];
                $this->historic_id = $this->data['Product Current Key'];
            }
        } elseif ($key == 'historic_key') {
            $sql = sprintf(
                "SELECT * FROM `Product History Dimension` WHERE `Product Key`=%d", $id
            );


            if ($this->data = $this->db->query($sql)->fetch()) {
                $this->historic_id = $this->data['Product Key'];
                $this->id          = $this->data['Product ID'];


                $sql = sprintf("SELECT * FROM `Product Dimension` WHERE `Product ID`=%d", $this->data['Product ID']);
                if ($row = $this->db->query($sql)->fetch()) {

                    foreach ($row as $key => $value) {
                        $this->data[$key] = $value;
                    }
                }


            }
        } else {

            sdasdas();
            exit ("wrong id in class.product get_data :$key  \n");

            return;
        }

        $sql = sprintf(
            "SELECT * FROM `Product Data` WHERE `Product ID`=%d", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                foreach ($row as $key => $value) {
                    $this->data[$key] = $value;
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        $sql = sprintf(
            'SELECT * FROM `Store Dimension` WHERE `Store Key`=%d ', $this->data['Product Store Key']
        );
        if ($row = $this->db->query($sql)->fetch()) {

            foreach ($row as $key => $value) {
                $this->data[$key] = $value;
            }
        }

    }

    function get($key, $arg1 = '') {

        switch ($key) {

            case 'Status':
            case 'Barcode Number':
            case 'CPNP Number':
            case 'Code':
            case 'Web State':
            case 'Description':
            case 'Current Key':
                return $this->data['Product '.$key];
                break;


            case 'Product Current Key':
                return $this->data[$key];
                break;


            case 'Ordered Quantity':


                $sql = sprintf(
                    "SELECT `Order Quantity` FROM `Order Transaction Fact` WHERE `Order Key`=%d AND `Product ID`=%d", $arg1, $this->id
                );

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $ordered_quantity = $row['Order Quantity'];
                        if ($ordered_quantity == 0) {
                            $ordered_quantity = '';
                        }
                    } else {
                        $ordered_quantity = '';

                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

                return $ordered_quantity;
                break;


            case 'Reminder Key':


                $sql = sprintf(
                    "SELECT `Email Site Reminder Key` FROM `Email Site Reminder Dimension` WHERE `Trigger Scope`='Back in Stock' AND `Trigger Scope Key`=%d AND `User Key`=%d AND `Email Site Reminder In Process`='Yes' ",
                    $this->id, $arg1

                );

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $reminder_key = $row['Email Site Reminder Key'];
                    } else {
                        $reminder_key = 0;

                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                return $reminder_key;
                break;

            case 'Favourite Key':

                $sql = sprintf(
                    "SELECT `Customer Favorite Product Key`  FROM  `Customer Favorite Product Bridge` WHERE `Customer Key`=%d AND `Product ID`=%d ", $arg1, $this->id
                );

                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $favourite_key = $row['Customer Favorite Product Key'];
                    } else {
                        $favourite_key = 0;

                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

                return $favourite_key;
                break;


            case 'Name':

                if ($this->data['Product Units Per Case'] > 1) {
                    return $this->data['Product Units Per Case'].'x '.$this->data['Product Name'];
                } else {
                    return $this->data['Product Name'];
                }


                break;

            case 'Origin':
                if ($this->data['Product Origin Country Code']) {
                    include_once 'class.Country.php';
                    $country = new Country('code', $this->data['Product Origin Country Code']);

                    return '<img src="/art/flags/'.strtolower($country->get('Country 2 Alpha Code')).'.gif" title="'.$country->get('Country Code').'"> '._($country->get('Country Name'));
                } else {
                    return '';
                }

                break;

            case 'Image':


                $image_key = $this->data['Product Main Image Key'];

                if ($image_key) {
                    $img = '/image_root.php?size=small&id='.$image_key;
                } else {
                    $img = '/art/nopic.png';

                }

                return $img;

                break;

            case 'Webpage Related Products':
            case 'Related Products':

                $related_products_data = $this->webpage->get_related_products_data();
                $related_products      = '';


                foreach ($related_products_data['links'] as $link) {
                    $related_products .= $link['code'].', ';
                }

                $related_products = preg_replace(
                    '/, $/', '', $related_products
                );

                return $related_products;


                break;

            case 'Webpage Key':
                if (!is_object($this->webpage)) {
                    $this->load_webpage();
                }

                return $this->webpage->id;

                break;
            case 'Webpage See Also':
            case 'See Also':

                if (!is_object($this->webpage)) {
                    $this->load_webpage();
                }

                $see_also_data = $this->webpage->get_see_also_data();
                $see_also      = '';
                if ($see_also_data['type'] == 'Auto') {
                    $see_also = _('Automatic').': ';
                }

                if (count($see_also_data['links']) == 0) {
                    $see_also .= ', '._('none');
                } else {
                    foreach ($see_also_data['links'] as $link) {
                        $see_also .= $link['code'].', ';
                    }
                }
                $see_also = preg_replace('/, $/', '', $see_also);

                return $see_also;


                break;

            case 'Webpage Name':
                if (!is_object($this->webpage)) {
                    $this->load_webpage();
                }

                return $this->webpage->get('Webpage Name');

                break;
            case 'Website Node Parent Key':
                if (!is_object($this->webpage)) {
                    $this->load_webpage();
                }

                return $this->webpage->get('Found In Page Key');

                break;
            case 'Product Website Node Parent Key':
                if (!is_object($this->webpage)) {
                    $this->load_webpage();
                }

                return $this->webpage->get('Page Found In Page Key');

                break;

            case 'Price':

                $price = preg_replace('/PLN/', 'zł ', money($this->data['Product Price'], $this->data['Store Currency Code']));

                if ($this->data['Product Units Per Case'] != 1) {

                    $price .= ' ('.preg_replace('/PLN/', 'zł ', money($this->data['Product Price'] / $this->data['Product Units Per Case'], $this->data['Store Currency Code'])).'/'
                        .$this->data['Product Unit Label'].')';


                    //$price.=' ('.sprintf(_('%s per %s'), money($this->data['Product Price']/$this->data['Product Units Per Case'], $this->data['Store Currency Code']), $this->data['Product Unit Label']).')';
                }


                return $price;
                break;

            case 'Webpage RRP':
            case 'RRP':

                if ($this->data['Product RRP'] == '') {
                    return '';
                }

                $rrp = preg_replace('/PLN/', 'zł ', money($this->data['Product RRP'] / $this->data['Product Units Per Case'], $this->data['Store Currency Code']));
                if ($this->get('Product Units Per Case') != 1) {
                    $rrp .= '/'.$this->data['Product Unit Label'];
                }


                return $rrp;
                break;

            case 'Out of Stock Label':
                if ($this->data['Product Total Acc Quantity Ordered'] > 0) {


                    if ($this->data['Product Next Supplier Shipment'] != '') {
                        $title = _('Expected').': '.strftime("%a %e %b %Y", strtotime($this->data['Product Next Supplier Shipment'].' +0:00'));


                        $label = _('Out of stock').' <span style="font-size:80%" title="'.$title.'">('.$this->get('Next Supplier Shipment').')</span>';
                    } else {
                        $label = _('Out of stock');
                    }


                    return $label;
                } else {
                    return _('Launching soon');
                }
                break;

            case 'Out of Stock Class':
                if ($this->data['Product Total Acc Quantity Ordered'] > 0) {
                    return 'out_of_stock';
                } else {
                    return 'launching_soon';
                }
                break;


            case 'Unit Type':
                if ($this->data['Product Unit Type'] == '') {
                    return '';
                }

                return _($this->data['Product Unit Type']);


                break;


            case 'Availability':

                if ($this->data['Product Availability State'] == 'OnDemand') {
                    return _('On demand');
                } else {
                    return number($this->data['Product Availability']);
                }
                break;

            case 'Product Next Supplier Shipment':

                return ($this->data['Product Availability State'] == '0000-00-00 00:00:00' ? '' : $this->data['Product Availability State']);

                break;
            case 'Next Supplier Shipment':

                return strftime("%e %b %y", strtotime($this->data['Product Next Supplier Shipment'].' +0:00'));

                break;


            case 'Unit Weight':
                include_once 'utils/natural_language.php';


                return weight($this->data['Product Unit Weight']);
                break;

            case 'Unit Dimensions':

                include_once 'utils/natural_language.php';


                $dimensions = '';


                $tag = preg_replace('/ Dimensions$/', '', $key);

                if ($this->data[$this->table_name.' '.$key] != '') {
                    $data = json_decode(
                        $this->data[$this->table_name.' '.$key], true
                    );
                    include_once 'utils/units_functions.php';


                    switch ($data['type']) {
                        case 'Rectangular':

                            $dimensions = number(
                                    convert_units(
                                        $data['l'], 'm', $data['units']
                                    )
                                ).'x'.number(
                                    convert_units(
                                        $data['w'], 'm', $data['units']
                                    )
                                ).'x'.number(
                                    convert_units(
                                        $data['h'], 'm', $data['units']
                                    )
                                ).' ('.$data['units'].')';
                            $dimensions .= '<span class="discreet volume">, '.volume($data['vol']).'</span>';
                            if ($this->data[$this->table_name." $tag Weight"] > 0) {

                                $dimensions .= '<span class="discreet density">, '.number(
                                        $this->data[$this->table_name." $tag Weight"] / $data['vol'], 3
                                    ).'Kg/L</span>';
                            }

                            break;
                        case 'Sheet':
                            $dimensions = number(
                                    convert_units(
                                        $data['l'], 'm', $data['units']
                                    )
                                ).'x'.number(
                                    convert_units(
                                        $data['w'], 'm', $data['units']
                                    )
                                ).' ('.$data['units'].')';

                            break;

                        case 'Cilinder':
                            $dimensions = number(
                                    convert_units(
                                        $data['h'], 'm', $data['units']
                                    )
                                ).'x'.number(
                                    convert_units(
                                        $data['w'], 'm', $data['units']
                                    )
                                ).' ('.$data['units'].')';
                            $dimensions .= '<span class="discreet volume">, '.volume($data['vol']).'</span>';
                            if ($this->data[$this->table_name." $tag Weight"] > 0) {
                                $dimensions .= '<span class="discreet density">, '.number(
                                        $this->data[$this->table_name." $tag Weight"] / $data['vol']
                                    ).'Kg/L</span>';
                            }

                            break;
                            print_r($data);
                            exit;
                            if (!$part->data['Part '.$tag.' Dimensions Length Display'] or !$part->data['Part '.$tag.' Dimensions Diameter Display']) {
                                $dimensions = '';
                            } else {
                                $dimensions = 'L:'.number(
                                        $part->data['Part '.$tag.' Dimensions Length Display']
                                    ).' &#8709;:'.number(
                                        $part->data['Part '.$tag.' Dimensions Diameter Display']
                                    ).' ('.$part->data['Part '.$tag.' Dimensions Display Units'].')';
                            }
                            break;
                        case 'Sphere':


                            $dimensions = _('Diameter').' '.number(
                                    convert_units(
                                        $data['l'], 'm', $data['units']
                                    )
                                ).$data['units'];
                            $dimensions .= ', <span class="discreet">'.volume(
                                    $data['vol']
                                ).'</span>';
                            if ($this->data[$this->table_name." $tag Weight"] > 0) {
                                $dimensions .= '<span class="discreet">, '.number(
                                        $this->data[$this->table_name." $tag Weight"] / $data['vol']
                                    ).'Kg/L</span>';
                            }

                            break;
                            if (!$part->data['Part '.$tag.' Dimensions Diameter Display']) {
                                $dimensions = '';
                            } else {
                                $dimensions = '&#8709;:'.number(
                                        $part->data['Part '.$tag.' Dimensions Diameter Display']
                                    ).' ('.$part->data['Part '.$tag.' Dimensions Display Units'].')';
                            }
                            break;
                        case 'String':
                            $dimensions = number(
                                    convert_units(
                                        $data['l'], 'm', $data['units']
                                    )
                                ).$data['units'];
                            break;

                            if (!$part->data['Part '.$tag.' Dimensions Length Display']) {
                                $dimensions = '';
                            } else {
                                $dimensions = 'L:'.number(
                                        $part->data['Part '.$tag.' Dimensions Length Display']
                                    ).' ('.$part->data['Part '.$tag.' Dimensions Display Units'].')';
                            }
                            break;

                        default:
                            $dimensions = '';
                    }

                }


                return $dimensions;

                break;

            case 'Materials':

                if ($this->data[$this->table_name.' Materials'] != '') {
                    $materials_data  = json_decode(
                        $this->data[$this->table_name.' Materials'], true
                    );
                    $xhtml_materials = '';


                    foreach ($materials_data as $material_data) {
                        if (!array_key_exists('id', $material_data)) {
                            continue;
                        }

                        if ($material_data['may_contain'] == 'Yes') {
                            $may_contain_tag = '±';
                        } else {
                            $may_contain_tag = '';
                        }

                        if ($material_data['id'] > 0) {
                            $xhtml_materials .= sprintf(
                                ', %s<span >%s</span>', $may_contain_tag, $material_data['name']
                            );
                        } else {
                            $xhtml_materials .= sprintf(
                                ', %s%s', $may_contain_tag, $material_data['name']
                            );

                        }


                        if ($material_data['ratio'] > 0) {
                            $xhtml_materials .= sprintf(
                                ' (%s)', percentage($material_data['ratio'], 1)
                            );
                        }
                    }

                    $xhtml_materials = ucfirst(
                        preg_replace('/^\, /', '', $xhtml_materials)
                    );

                    return $xhtml_materials;


                } else {
                    return '';
                }
                break;


            default:


        }

    }

    function load_webpage() {

        include_once 'class.Public_Webpage.php';
        $this->webpage = new Public_Webpage('scope', 'Product', $this->id);
    }

    function get_attachments() {

        $attachments = array();


        $sql = sprintf(
            'SELECT `Attachment Subject Type`, `Attachment Bridge Key`,`Attachment Caption`  FROM `Product Part Bridge`  LEFT JOIN `Attachment Bridge` AB  ON (AB.`Subject Key`=`Product Part Part SKU`)    WHERE AB.`Subject`="Part" AND  `Product Part Product ID`=%d  AND `Attachment Public`="Yes" AND `Attachment Subject Type`="MSDS" ',
            $this->id
        );


        if ($result2 = $this->db->query($sql)) {
            foreach ($result2 as $row2) {

                if ($row2['Attachment Subject Type'] == 'MSDS') {
                    $label = '<span title="'._('Material safety data sheet').'">MSDS</span>';
                } else {
                    $label = _('Attachment');
                }


                $attachments[] = array(
                    'id'    => $row2['Attachment Bridge Key'],
                    'label' => $label,
                    'name'  => $row2['Attachment Caption']
                );
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $attachments;


    }


    function get_images_slidesshow() {
        include_once 'utils/natural_language.php';


        $image_subject_type = $this->table_name;


        $sql = sprintf(
            "SELECT `Image Subject Is Principal`,`Image Key`,`Image Subject Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` FROM `Image Subject Bridge` B LEFT JOIN `Image Dimension` I ON (`Image Subject Image Key`=`Image Key`) WHERE `Image Subject Object`=%s AND   `Image Subject Object Key`=%d ORDER BY `Image Subject Is Principal`,`Image Subject Date`,`Image Subject Key`",
            prepare_mysql($image_subject_type), $this->id
        );


        // print $sql;

        $subject_order = 0;

        //print $sql;
        $images_slideshow = array();
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($row['Image Key']) {

                    if ($row['Image Height'] != 0) {
                        $ratio = $row['Image Width'] / $row['Image Height'];
                    } else {
                        $ratio = 1;
                    }
                    // print_r($row);
                    $images_slideshow[] = array(
                        'subject_order' => $subject_order,
                        'name'          => $row['Image Filename'],
                        'small_url'     => 'image_root.php?id='.$row['Image Key'].'&size=small',
                        'thumbnail_url' => 'image_root.php?id='.$row['Image Key'].'&size=thumbnail',
                        'normal_url'    => 'image_root.php?id='.$row['Image Key'],
                        'filename'      => $row['Image Filename'],
                        'ratio'         => $ratio,
                        'caption'       => $row['Image Subject Image Caption'],
                        'is_principal'  => $row['Image Subject Is Principal'],
                        'id'            => $row['Image Key'],
                        'size'          => file_size($row['Image File Size']),
                        'width'         => $row['Image Width'],
                        'height'        => $row['Image Height']

                    );
                    $subject_order++;
                }

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql";
            exit;
        }

        //print_r($images_slideshow);
        //exit;

        return $images_slideshow;
    }

    function get_number_images() {

        $subject = $this->table_name;

        $number_of_images = 0;
        $sql              = sprintf(
            "SELECT count(*) AS num FROM `Image Subject Bridge` WHERE `Image Subject Object`=%s AND `Image Subject Object Key`=%d ", prepare_mysql($subject), $this->id
        );
        //print $sql;


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_of_images = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql";
            exit;
        }


        return $number_of_images;
    }

    function get_object_name() {
        return $this->table_name;

    }

    function get_field_label($field) {
        global $account;

        switch ($field) {

            case 'Product ID':
                $label = _('id');
                break;


            case 'Product Cost':
                $label = _('Outer cost');
                break;

            case 'Product Description':
                $label = _('Product description');
                break;
            case 'Product Webpage Name':
                $label = _('Webpage title');
                break;
            case 'Product Code':
                $label = _('code');
                break;
            case 'Product Outer Description':
                $label = _('description');
                break;
            case 'Product Unit Description':
                $label = _('unit description');
                break;
            case 'Product Price':
                $label = _('Outer price');
                break;
            case 'Product Outer Weight':
                $label = _('weight');
                break;
            case 'Product Outer Dimensions':
                $label = _('dimensions');
                break;
            case 'Product Units Per Outer':
                $label = _('retail units per outer');
                break;

            case 'Product Unit Type':
                $label = _('unit type');
                break;
            case 'Product Label in Family':
                $label = _('label in family');
                break;

            case 'Product Unit Weight':
                $label = _('unit weight');
                break;
            case 'Product Unit Dimensions':
                $label = _('unit dimensions');
                break;
            case 'Product Units Per Case':
                $label = _('units per outer');
                break;
            case 'Product Unit Label':
                $label = _('unit label');
                break;
            case 'Product Parts':
                $label = _('parts');
                break;
            case 'Product Name':
                $label = _('unit name');
                break;

            case 'Product Unit RRP':
                $label = _('unit RRP');
                break;

            case 'Product Tariff Code':
                $label = _('tariff code');
                break;

            case 'Product Duty Rate':
                $label = _('duty rate');
                break;

            case 'Product UN Number':
                $label = _('UN number');
                break;

            case 'Product UN Class':
                $label = _('UN class');
                break;
            case 'Product Packing Group':
                $label = _('packing group');
                break;
            case 'Product Proper Shipping Name':
                $label = _('proper shipping name');
                break;
            case 'Product Hazard Indentification Number':
                $label = _('hazard identification number');
                break;
            case 'Product Materials':
                $label = _('Materials/Ingredients');
                break;
            case 'Product Origin Country Code':
                $label = _('country of origin');
                break;
            case 'Product Units Per Package':
                $label = _('units per SKO');
                break;
            case 'Product Barcode Number':
                $label = _('barcode');
                break;
            case 'Product CPNP Number':
                $label = _('CPNP number');
                break;


            default:
                $label = $field;

        }

        return $label;

    }

    function get_prev_product($scope = 'data') {


        $prev_product = false;

        $sql = sprintf(
            "SELECT `Webpage Code`,`Product Name` FROM `Category Bridge` LEFT JOIN `Product Dimension` P ON (`Subject Key`=`Product ID`) 
              LEFT JOIN `Page Store Dimension` ON (`Page Key`=`Product Webpage Key`)
              WHERE P.`Product Type`='Product' AND`Subject`='Product' AND `Category Key`=%d AND `Webpage State`='Online' AND P.`Product Status` IN ('Active','Discontinuing') AND (`Product Code File As` < %s OR (`Product Code File As` = %s AND P.`Product ID` < %d)) ORDER BY `Product Code File As` DESC , P.`Product ID` DESC LIMIT 1;",
            $this->data['Product Family Category Key'], prepare_mysql($this->data['Product Code File As']), prepare_mysql($this->data['Product Code File As']), $this->id

        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {


                $prev_product = array(
                    'webpage_code' => $row['Webpage Code'],
                    'name'         => $row['Product Name']
                );
            } else {

                $sql = sprintf(
                    "SELECT `Webpage Code`,`Product Name` FROM `Category Bridge` LEFT JOIN `Product Dimension` P ON (`Subject Key`=`Product ID`) 
              LEFT JOIN `Page Store Dimension` ON (`Page Key`=`Product Webpage Key`)
              WHERE P.`Product Type`='Product' AND`Subject`='Product' AND `Category Key`=%d AND `Webpage State`='Online' AND P.`Product Status` IN ('Active','Discontinuing')  AND   P.`Product ID`!=%d  ORDER BY `Product Code File As` DESC , P.`Product ID` DESC LIMIT 1;",
                    $this->data['Product Family Category Key'], $this->id

                );


                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $prev_product = array(
                            'webpage_code' => $row['Webpage Code'],
                            'name'         => $row['Product Name']
                        );
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }


            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $prev_product;


    }


    function get_next_product($scope = 'data') {
        $next_product = false;


        $sql = sprintf(
            "SELECT `Webpage Code`,`Product Name` FROM `Category Bridge` LEFT JOIN `Product Dimension` P ON (`Subject Key`=`Product ID`) 
              LEFT JOIN `Page Store Dimension` ON (`Page Key`=`Product Webpage Key`)
              WHERE P.`Product Type`='Product' AND`Subject`='Product' AND `Category Key`=%d AND `Webpage State`='Online' AND P.`Product Status` IN ('Active','Discontinuing') 
              AND (`Product Code File As` > %s OR (`Product Code File As` = %s AND P.`Product ID` > %d)) ORDER BY `Product Code File As`  , P.`Product ID` DESC LIMIT 1;",
            $this->data['Product Family Category Key'], prepare_mysql($this->data['Product Code File As']), prepare_mysql($this->data['Product Code File As']), $this->id

        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {


                $next_product = array(
                    'webpage_code' => $row['Webpage Code'],
                    'name'         => $row['Product Name']
                );
            } else {

                $sql = sprintf(
                    "SELECT `Webpage Code`,`Product Name` FROM `Category Bridge` LEFT JOIN `Product Dimension` P ON (`Subject Key`=`Product ID`) 
              LEFT JOIN `Page Store Dimension` ON (`Page Key`=`Product Webpage Key`)
              WHERE P.`Product Type`='Product' AND`Subject`='Product' AND `Category Key`=%d AND `Webpage State`='Online' AND P.`Product Status` IN ('Active','Discontinuing') AND   P.`Product ID`!=%d  ORDER BY `Product Code File As`  , P.`Product ID` DESC LIMIT 1;",
                    $this->data['Product Family Category Key'], $this->id

                );


                if ($result = $this->db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $next_product = array(
                            'webpage_code' => $row['Webpage Code'],
                            'name'         => $row['Product Name']
                        );
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    print "$sql\n";
                    exit;
                }

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $next_product;

    }


    function get_deal_components($scope = 'keys', $options = 'Active') {

        switch ($options) {
            case 'Active':
                $where = 'AND `Deal Component Status`=\'Active\'';
                break;
            default:
                $where = '';
                break;
        }


        $deal_components = array();


        $parent_categories = $this->get_parent_categories();

        if (count($parent_categories) > 0) {

            $sql = sprintf(
                "SELECT `Deal Component Key` FROM `Deal Component Dimension` WHERE `Deal Component Allowance Target`='Category' AND `Deal Component Allowance Target Key` in (%s) $where",
                join(',', $parent_categories)

            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {

                    if ($scope == 'objects') {
                        $deal_components[$row['Deal Component Key']] = get_object('DealComponent', $row['Deal Component Key']);
                    } else {
                        $deal_components[$row['Deal Component Key']] = $row['Deal Component Key'];
                    }


                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }

        }


        return $deal_components;


    }

    function get_parent_categories($scope = 'keys') {


        $type              = 'Product';
        $parent_categories = array();


        $sql = sprintf(
            "SELECT `Webpage Code`,B.`Category Key`,`Category Root Key`,`Other Note`,`Category Label`,`Category Code`,`Is Category Field Other` 
        FROM `Category Bridge` B 
        LEFT JOIN `Category Dimension` C ON (C.`Category Key`=B.`Category Key`) 
        LEFT JOIN `Page Store Dimension` W ON (W.`Webpage Scope Key`=B.`Category Key` AND `Webpage Scope`=%s) 

          WHERE  `Category Branch Type`='Head'  AND B.`Subject Key`=%d AND B.`Subject`=%s",

            prepare_mysql('Category Products'),

            $this->id, prepare_mysql($type)
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($scope == 'keys') {
                    $parent_categories[$row['Category Key']] = $row['Category Key'];
                } elseif ($scope == 'objects') {
                    $parent_categories[$row['Category Key']] = get_object('Category', $row['Category Key']);
                } elseif ($scope == 'data') {


                    $value = $row['Category Label'];

                    $parent_categories[] = array(

                        'label'        => $row['Category Label'],
                        'code'         => $row['Category Code'],
                        'value'        => $value,
                        'category_key' => $row['Category Key'],
                        'webpage_code' => strtolower($row['Webpage Code'])

                    );
                }

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $parent_categories;
    }

    function get_parent_category($scope = 'keys') {


        $parent_category = false;
        if ($scope == 'keys') {
            $parent_category = $this->data['Product Family Category Key'];
        } elseif ($scope == 'objects') {
            $parent_category = get_object('Category', $this->data['Product Family Category Key']);
        } elseif ($scope == 'data') {

            $sql = sprintf(
                'SELECT `Webpage Code`,`Category Label`,`Category Code` FROM  `Category Dimension` C LEFT JOIN `Product Category Dimension`  ON (C.`Category Key`=`Product Category Key`) 
        LEFT JOIN `Page Store Dimension` ON (`Page Key`=`Product Category Webpage Key`)  WHERE C.`Category Key`=%d ', $this->data['Product Family Category Key']
            );

        }

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $parent_category = array(
                    'label'        => $row['Category Label'],
                    'code'         => $row['Category Code'],
                    'webpage_code' => strtolower($row['Webpage Code'])

                );
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        return $parent_category;
    }


}


?>
