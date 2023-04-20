<?php
/*


 About:
 Author: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'class.Node.php';
include_once 'trait.ImageSubject.php';
include_once 'trait.AttachmentSubject.php';
include_once 'trait.NotesSubject.php';

include_once 'trait.PartCategory.php';
include_once 'trait.SupplierCategory.php';
include_once 'trait.InvoiceCategory.php';
include_once 'trait.ProductCategory.php';
include_once 'trait.LocationCategory.php';


class Category extends DB_Table {
    use ImageSubject, NotesSubject, AttachmentSubject;
    use PartCategory, SupplierCategory, InvoiceCategory, ProductCategory, LocationCategory;


    function __construct($a1, $a2 = false, $a3 = false, $_db = false) {


        if (!$_db) {
            global $db;
            $this->db = $db;
        } else {
            $this->db = $_db;
        }

        $this->update_subjects_data = true;
        $this->table_name           = 'Category';
        $this->subject_table_name   = 'Category';
        $this->ignore_fields        = array(
            'Category Key',
            'Part Category Key'
        );
        $this->all_descendants_keys = array();
        $this->skip_update_sales    = false;
        $this->webpage              = false;

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id', $a1);
        } else {
            if (($a1 == 'new' or $a1 == 'create') and is_array($a2)) {
                $this->find($a2, 'create');

            } elseif (preg_match('/find/i', $a1)) {
                $this->find($a2, $a1);
            } else {
                $this->get_data($a1, $a2, $a3);
            }
        }

    }


    function get_data($tipo, $tag, $tag2 = false) {
        switch ($tipo) {
            case 'root_key_code':
                $sql = sprintf(
                    "SELECT * FROM `Category Dimension` WHERE `Category Root Key`=%d AND `Category Code`=%s ", $tag, prepare_mysql($tag2)
                );
                break;
            case 'subject_code':
                $sql = sprintf(
                    "SELECT * FROM `Category Dimension` WHERE `Category Subject`=%s AND `Category Code`=%s ", prepare_mysql($tag), prepare_mysql($tag2)
                );
                break;
            case 'root_key_key':
                $sql = sprintf(
                    "SELECT * FROM `Category Dimension` WHERE `Category Root Key`=%d AND `Category Key`=%d ", $tag, $tag2
                );
                break;

            default:
                $sql = sprintf(
                    "SELECT * FROM `Category Dimension` WHERE `Category Key`=%d", $tag
                );

                break;
        }


        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Category Key'];


            $this->properties = json_decode($this->data['Category Properties'], true);

            if ($this->data['Category Scope'] == 'Part') {


                $this->subject_table_name = 'Part Category';
                $sql                      = sprintf(
                    "SELECT * FROM `Part Category Dimension` WHERE `Part Category Key`=%d", $this->id
                );
                if ($result2 = $this->db->query($sql)) {
                    if ($row = $result2->fetch()) {
                        $this->data = array_merge($this->data, $row);
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }

                $sql = sprintf(
                    "SELECT * FROM `Part Category Data` WHERE `Part Category Key`=%d", $this->id
                );
                if ($result2 = $this->db->query($sql)) {
                    if ($row = $result2->fetch()) {
                        $this->data = array_merge($this->data, $row);
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }

                // print_r($sql);

            } elseif ($this->data['Category Scope'] == 'Product') {


                $this->subject_table_name = 'Product Category';
                $sql                      = sprintf(
                    "SELECT * FROM `Product Category Dimension` WHERE `Product Category Key`=%d", $this->id
                );
                //  print $sql;
                // exit;
                if ($result2 = $this->db->query($sql)) {
                    if ($row = $result2->fetch()) {
                        $this->data = array_merge($this->data, $row);
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }


            } elseif ($this->data['Category Scope'] == 'Supplier') {


                $this->subject_table_name = 'Supplier Category';

                $sql = sprintf(
                    "SELECT * FROM `Supplier Category Dimension` WHERE `Supplier Category Key`=%d", $this->id
                );
                if ($result2 = $this->db->query($sql)) {
                    if ($row = $result2->fetch()) {
                        $this->data = array_merge($this->data, $row);
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }

                $sql = sprintf(
                    "SELECT * FROM `Supplier Category Data` WHERE `Supplier Category Key`=%d", $this->id
                );
                if ($result2 = $this->db->query($sql)) {
                    if ($row = $result2->fetch()) {
                        $this->data = array_merge($this->data, $row);
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }


            } elseif ($this->data['Category Subject'] == 'Invoice') {
                $this->subject_table_name = 'Invoice Category';

                $sql = sprintf(
                    "SELECT * FROM `Invoice Category Dimension` WHERE `Invoice Category Key`=%d", $this->id
                );

                if ($result2 = $this->db->query($sql)) {
                    if ($row = $result2->fetch()) {
                        $this->data = array_merge($this->data, $row);
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }

            }


        }


    }

    function find($raw_data, $options) {

        if (isset($raw_data['editor']) and is_array($raw_data['editor'])) {
            foreach ($raw_data['editor'] as $key => $value) {
                if (array_key_exists($key, $this->editor)) {
                    $this->editor[$key] = $value;
                }

            }
        }

        $this->candidate = array();
        $this->found     = false;
        $this->found_key = 0;
        $create          = '';
        $update          = '';
        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }
        if (preg_match('/update/i', $options)) {
            $update = 'update';
        }

        $data = $this->base_data();
        foreach ($raw_data as $key => $value) {

            if (array_key_exists($key, $data)) {
                $data[$key] = $value;
            }

        }

        if (!$data['Category Store Key'] and $data['Category Parent Key']) {
            $parent_category            = get_object('Category', $data['Category Parent Key']);
            $data['Category Store Key'] = $parent_category->get('Category Store Key');
        }

        $fields = array();


        $sql = sprintf(
            "SELECT `Category Key` FROM `Category Dimension` WHERE  `Category Parent Key`=%d AND `Category Store Key`=%d AND `Category Code`=%s ", $data['Category Parent Key'], $data['Category Store Key'], prepare_mysql($data['Category Code'])

        );
        //print_r($fields);
        foreach ($fields as $field) {
            $sql .= sprintf(
                ' and `%s`=%s', $field, prepare_mysql($data[$field], false)
            );
        }

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {


                $this->found     = true;
                $this->found_key = $row['Category Key'];
                $this->get_data('id', $this->found_key);
                $this->duplicated_field = 'Category Code';

                return;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        if ($create) {
            $this->create($data);

        }


    }

    function create($data) {


        if ($data['Category Label'] == '') {
            $data['Category Label'] = $data['Category Code'];
        }


        //todo  Move stuff from class.Node.php to here
        $nodes = new Nodes('`Category Dimension`');
        $nodes->add_new($data['Category Parent Key'], $data);
        $node_id = $nodes->id;
        unset($nodes);


        if ($node_id) {

            $this->get_data('id', $node_id);


            /*

			if ($this->data['Category Parent Key']==0) {
				$abstract=_('Category')." (".$this->data['Category Subject'].")  ".$this->data['Category Code']." "._('Created');
				$details=_trim(_('New Category')." (".$this->data['Category Subject'].")  \"".$this->data['Category Code']."\"  "._('added'));
			} else {
				$abstract=_('Category')." (".$this->data['Category Subject'].")  ".$this->data['Category Code']." "._('Created');
				$details=_trim(_('New Category')." ".$this->data['Category Subject'].") \"".$this->data['Category Code']."\"  "._('added'));

			}


			$history_data=array(
				'History Abstract'=>$abstract,
				'History Details'=>$details,
				'Indirect Object Key'=>$this->data['Category Parent Key'],
				'Indirect Object'=>'Category '.$this->data['Category Subject'],
				'Direct Object Key'=>$this->id,
				'Direct Object'=>'Category '.$this->data['Category Subject'],
				'Action'=>'created'
			);
			$this->add_history($history_data);
		*/


            $this->new = true;

            $created_msg = _('Category created');


            if ($this->data['Category Scope'] == 'Supplier') {
                $sql = sprintf(
                    "INSERT INTO `Supplier Category Dimension` (`Supplier Category Key`,`Supplier Category Valid From`) VALUES (%d,Now())", $this->id
                );
                $this->db->exec($sql);

                $sql = sprintf(
                    "INSERT INTO `Supplier Category Data` (`Supplier Category Key`) VALUES (%d)", $this->id
                );
                $this->db->exec($sql);


            } elseif ($this->data['Category Scope'] == 'Part') {
                $created_msg = _("Part's category created");

                $sql = sprintf(
                    "INSERT INTO `Part Category Dimension` (`Part Category Key`,`Part Category Valid From`) VALUES (%d,%s)", $this->id, prepare_mysql(gmdate('Y-m-d H:i:s'))

                );
                $this->db->exec($sql);


                $sql = $sql = sprintf(
                    "INSERT INTO `Part Category Data` (`Part Category Key`) VALUES (%d)", $this->id
                );
                $this->db->exec($sql);

            } elseif ($this->data['Category Scope'] == 'Product') {
                $store = get_object('Store', $this->data['Category Store Key']);

                $sql = sprintf(
                    "INSERT INTO `Product Category Dimension` (`Product Category Key`,`Product Category Status`,`Product Category Store Key`,`Product Category Currency Code`,`Product Category Valid From`) VALUES (%d,%s,%d,%s,%s)", $this->id, prepare_mysql('Active'),
                    $store->id, prepare_mysql($store->get('Store Currency Code')), prepare_mysql(gmdate('Y-m-d H:i:s'))
                );
                $this->db->exec($sql);


                $sql = sprintf(
                    "INSERT INTO `Product Category Data` (`Product Category Key`) VALUES (%d)", $this->id

                );
                $this->db->exec($sql);


                $sql = sprintf(
                    "INSERT INTO `Product Category DC Data` (`Product Category Key`) VALUES (%d)", $this->id

                );


                $this->db->exec($sql);

            } elseif ($this->data['Category Scope'] == 'Invoice') {

                $store = get_object('Store', $this->data['Category Store Key']);

                $sql = sprintf(
                    "INSERT INTO `Invoice Category Dimension` (`Invoice Category Key`,`Invoice Category Store Key`,`Invoice Category Currency Code`,`Invoice Category Valid From`) VALUES (%d,%d,%s,%s)", $this->id, $store->id,
                    prepare_mysql($store->get('Store Currency Code')), prepare_mysql(gmdate('Y-m-d H:i:s'))
                );
                $this->db->exec($sql);

                $sql = sprintf(
                    "INSERT INTO `Invoice Category Data` (`Invoice Category Key`) VALUES (%d)", $this->id

                );
                $this->db->exec($sql);
                $sql = sprintf(
                    "INSERT INTO `Invoice Category DC Data` (`Invoice Category Key`) VALUES (%d)", $this->id

                );
                $this->db->exec($sql);

            }

            $this->fast_update(array('Category Properties' => '{}'));

            $history_data = array(
                'Action'           => 'created',
                'History Abstract' => $created_msg,
                'History Details'  => ''
            );
            $this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id);


            $this->update_number_of_subjects();
            /** @var $parent_category \Category */
            $parent_category = get_object('Category', $data['Category Parent Key']);
            if ($parent_category->id) {
                $parent_category->editor = $this->editor;
                $parent_category->update_children_data();
            }


            $this->fork_index_elastic_search();


        }


    }


    function update_number_of_subjects() {

        $num           = 0;
        $num_active    = 0;
        $num_no_active = 0;

        /*
                if ($this->data['Category Subject'] == 'Category') {

                    $sql = sprintf(
                        "SELECT sum(`Category Number Subjects`)  AS num FROM `Category Bridge` B LEFT JOIN `Category Dimension` C ON (B.`Subject Key`=C.`Category Key`) WHERE B.`Category Key`=%d  ", $this->id
                    );

                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $num = $row['num'];
                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        exit;
                    }
                    $num_active    = $num;
                    $num_no_active = 0;

                } else {
        */
        $sql = sprintf(
            "SELECT COUNT(DISTINCT `Subject Key`)  AS num FROM `Category Bridge`  WHERE `Category Key`=%d  ", $this->id
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $num = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        if ($this->get('Category Scope') == 'Part') {

            $sql = sprintf(
                "SELECT count(*) AS num, `Part Status` FROM `Category Bridge` B LEFT JOIN `Part Dimension` P ON (B.`Subject Key`=P.`Part SKU`) WHERE B.`Category Key`=%d GROUP BY `Part Status` ", $this->id
            );

            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {
                    if ($row['Part Status'] == 'Not In Use') {

                        $num_no_active = $row['num'];
                    } else {
                        $num_active = $row['num'];
                    }
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }


        } else {
            $num_active    = $num;
            $num_no_active = 0;
        }


        //   }


        $sql = sprintf(
            "UPDATE `Category Dimension` SET `Category Number Subjects`=%d ,`Category Number Active Subjects`=%d ,`Category Number No Active Subjects`=%d WHERE `Category Key`=%d ", $num, $num_active, $num_no_active, $this->id
        );

        //   print $sql;

        $this->db->exec($sql);
        $this->update_no_assigned_subjects();

    }

    function get($key = '') {

        global $account;

        if (!$this->id) {
            return false;
        }


        if ($key == 'Subjects Not Assigned' or $key == 'Number Subjects') {
            return number($this->data['Category '.$key]);
        }
        if ($key == 'Number Children') {
            return number($this->data['Category Children']);
        }

        switch ($key) {

            case 'Image':


                $image_key = $this->data['Category Main Image Key'];


                if ($image_key) {
                    $img = '/image.php?s=320x280&id='.$image_key;
                } else {
                    $img = '/art/nopic.png';

                }

                return $img;
                break;


            case 'Subjects Not Assigned':
            case 'Number Subjects':
            case 'Children':
                return number($this->data['Category '.$key]);
                break;

            case 'Number History Records':
                switch ($this->data['Category Scope']) {

                    case 'Product':
                        return number($this->data['Product Category '.$key]);
                        break;
                    case 'Part':
                        return number($this->data['Part Category '.$key]);
                        break;
                    default:
                        return number($this->data['Category '.$key]);
                        break;

                }
                break;

            default:
                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Category '.$key, $this->data)) {
                    return $this->data['Category '.$key];
                }
        }


        switch ($this->data['Category Scope']) {

            case 'Product':

                switch ($key) {


                    case 'Status':
                        switch ($this->data['Product Category Status']) {
                            case 'In Process':
                                return _('In process');
                                break;
                            case 'Active':
                                return _('Active');
                                break;
                            case 'Suspended':
                                return _('Suspended');
                                break;
                            case 'Discontinuing':
                                return _('Discontinuing');
                                break;
                            case 'Discontinued':
                                return _('Discontinued');
                                break;
                            default:
                                return $this->data['Product Category Status'];
                                break;
                        }

                        break;

                    case 'Department Category Key':

                        return $this->get('Product Category Department Category Key');
                        break;

                    case 'Parent Category Code':


                            $department = get_object('Category', $this->get('Category Parent Key'));
                            if ($department->id) {
                                return $department->get('Code');
                            }


                        return '';

                    case 'Department Category Code':


                        if ($this->get('Product Category Department Category Key') > 0) {
                            $department = get_object('Category', $this->get('Product Category Department Category Key'));
                            if ($department->id) {
                                return $department->get('Code');
                            }
                        }

                        return '';


                    case 'Category Webpage Meta Description':
                    case 'Webpage Meta Description':
                        return $this->webpage->get('Webpage Meta Description');

                        break;

                    case 'Category Webpage Browser Title':
                    case 'Webpage Browser Title':
                        return $this->webpage->get('Webpage Browser Title');

                        break;

                    case 'Category Webpage Name':
                    case 'Webpage Name':


                        return $this->webpage->get('Webpage Name');

                        break;


                    case 'Description':
                        return htmlentities(
                            $this->data['Product Category '.$key]
                        );
                        break;
                    case 'Public':
                        if ($this->data['Product Category '.$key] == 'Yes') {
                            return _('Yes');
                        } else {
                            return _('No');
                        }

                        break;

                    case 'Valid From':

                        return strftime(
                            "%a %e %b %Y", strtotime(
                                             $this->data['Product Category '.$key].' +0:00'
                                         )
                        );


                        break;
                    case 'Valid To':

                        if ($this->data['Product Category '.$key] == '') {
                            return '';
                        } else {

                            return strftime(
                                "%a %e %b %Y", strtotime(
                                                 $this->data['Product Category '.$key].' +0:00'
                                             )
                            );

                        }

                        break;
                    case 'Acc To Day Updated':
                    case 'Acc Ongoing Intervals Updated':
                    case 'Acc Previous Intervals Updated':

                        if ($this->data['Product Category '.$key] == '') {
                            return '';
                        } else {

                            return strftime("%a %e %b %Y %H:%M:%S %Z", strtotime($this->data['Product Category '.$key].' +0:00'));

                        }
                        break;
                    case 'Active Web For Sale including Out of Stock':
                        return number($this->data['Product Category Active Web For Sale'] + $this->data['Product Category Active Web Out of Stock']);
                    case 'Percentage Active Web Out of Stock':
                        return percentage(
                            $this->data['Product Category Active Web Out of Stock'], $this->data['Product Category Active Web For Sale'] + $this->data['Product Category Active Web Out of Stock'], 0
                        );
                    case 'Percentage Active Web Offline':
                        return percentage($this->data['Product Category Active Web Offline'], $this->data['Product Category Active Products'], 0);

                    case 'products':
                        return $this->data['Product Category Active Products'] + $this->data['Product Category Discontinuing Products'];
                    default:

                        if (preg_match(
                            '/^(Last|Yesterday|Total|1|10|6|3|2|4|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit)$/', $key
                        )) {

                            $amount = 'Product Category '.$key;


                            return money(
                                $this->data[$amount], $this->get('Product Category Currency Code')
                            );
                        }
                        if (preg_match('/^(Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Minify$/', $key)) {

                            $field = 'Product Category '.preg_replace(
                                    '/ Minify$/', '', $key
                                );

                            $suffix          = '';
                            $fraction_digits = 'NO_FRACTION_DIGITS';
                            if ($this->data[$field] >= 10000) {
                                $suffix  = 'K';
                                $_amount = $this->data[$field] / 1000;
                            } elseif ($this->data[$field] > 100) {
                                $fraction_digits = 'SINGLE_FRACTION_DIGITS';
                                $suffix          = 'K';
                                $_amount         = $this->data[$field] / 1000;
                            } else {
                                $_amount = $this->data[$field];
                            }

                            $amount = money(
                                    $_amount, $this->get(
                                    'Product Category Currency Code'
                                ), $locale = false, $fraction_digits
                                ).$suffix;

                            return $amount;
                        }
                        if (preg_match('/^(Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Soft Minify$/', $key)) {

                            $field = 'Product Category '.preg_replace(
                                    '/ Soft Minify$/', '', $key
                                );

                            $suffix          = '';
                            $fraction_digits = 'NO_FRACTION_DIGITS';
                            $_amount         = $this->data[$field];


                            $amount = money(
                                    $_amount, $this->get(
                                    'Product Category Currency Code'
                                ), $locale = false, $fraction_digits
                                ).$suffix;

                            return $amount;
                        }
                        if (preg_match(
                            '/^(Last|Yesterday|Total|1|10|6|3|2|4|5|Year To|Quarter To|Month To|Today|Week To).*(Margin|GMROI)$/', $key
                        )) {

                            $amount = 'Product Category '.$key;

                            return percentage($this->data[$amount], 1);
                        }
                        if (preg_match(
                            '/^(Last|Yesterday|Total|1|10|6|3|2|4|5|Year To|Quarter To|Month To|Today|Week To).*(Quantity Invoiced|Customers)$/', $key
                        )) {

                            $field = 'Product Category '.$key;

                            return number($this->data[$field]);
                        }


                        if (preg_match('/^(Last|Yesterday|Total|1|10|6|3|2|4|5|Year To|Quarter To|Month To|Today|Week To).*(Quantity Invoiced) Minify$/', $key)) {

                            $field = 'Product Category '.preg_replace('/ Minify$/', '', $key);

                            $suffix          = '';
                            $fraction_digits = 0;
                            if ($this->data[$field] >= 10000) {
                                $suffix  = 'K';
                                $_number = $this->data[$field] / 1000;
                            } elseif ($this->data[$field] > 100) {
                                $fraction_digits = 1;
                                $suffix          = 'K';
                                $_number         = $this->data[$field] / 1000;
                            } else {
                                $_number = $this->data[$field];
                            }

                            return number($_number, $fraction_digits).$suffix;
                        }
                        if (preg_match('/^(Last|Yesterday|Total|1|10|6|3|2|4|5|Year To|Quarter To|Month To|Today|Week To).*(Quantity Invoiced) Soft Minify$/', $key)) {
                            $field   = 'Product Category '.preg_replace('/ Soft Minify$/', '', $key);
                            $_number = $this->data[$field];

                            return number($_number, 0);
                        }

                        if (array_key_exists(
                            'Product Category '.$key, $this->data
                        )) {
                            return $this->data['Product Category '.$key];
                        }
                        break;
                }


                break;
            case 'Part':

                switch ($key) {


                    case 'Parts':

                        return number($this->data['Part Category In Process'] + $this->data['Part Category Active'] + $this->data['Part Category Discontinuing']);
                        break;

                    case 'In Process':
                    case 'Active':
                    case 'Discontinuing':
                    case 'Discontinued':
                    case 'Number Images':
                    case 'Number History Records':
                        return number($this->data['Part Category '.$key]);
                        break;

                    case 'Part Category Status Including Parts':

                        return $this->get('Part Category Status');

                        break;
                    case 'Status Including Parts':
                    case 'Status':

                        switch ($this->data['Part Category Status']) {
                            case 'InUse':
                                return _('Active');
                                break;
                            case 'InProcess':
                                return _('In process');
                                break;
                            case 'Discontinuing':
                                return _('Discontinuing');
                                break;
                            case 'NotInUse':
                                return _('Discontinued');
                                break;
                            default:
                                return $this->data['Part Category Status'];
                                break;
                        }

                        break;

                    case 'Valid From':

                        return strftime(
                            "%a %e %b %Y", strtotime(
                                             $this->data['Part Category '.$key].' +0:00'
                                         )
                        );


                        break;
                    case 'Valid To':

                        if ($this->data['Part Category '.$key] == '') {
                            return '';
                        } else {

                            return strftime("%a %e %b %Y", strtotime($this->data['Part Category '.$key].' +0:00'));

                        }

                        break;

                    case 'Acc To Day Updated':
                    case 'Acc Ongoing Intervals Updated':
                    case 'Acc Previous Intervals Updated':

                        if ($this->data['Part Category '.$key] == '') {
                            return '';
                        } else {

                            return strftime("%a %e %b %Y %H:%M:%S %Z", strtotime($this->data['Part Category '.$key].' +0:00'));

                        }
                        break;

                }

                include_once 'utils/natural_language.php';

                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit)$/', $key
                )) {

                    $field = 'Part Category '.$key;

                    return money(
                        $this->data[$field], $account->get('Account Currency')
                    );
                }
                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Minify$/', $key
                )) {

                    $field = 'Part Category '.preg_replace(
                            '/ Minify$/', '', $key
                        );

                    $suffix          = '';
                    $fraction_digits = 'NO_FRACTION_DIGITS';
                    if ($this->data[$field] >= 10000) {
                        $suffix  = 'K';
                        $_amount = $this->data[$field] / 1000;
                    } elseif ($this->data[$field] > 100) {
                        $fraction_digits = 'SINGLE_FRACTION_DIGITS';
                        $suffix          = 'K';
                        $_amount         = $this->data[$field] / 1000;
                    } else {
                        $_amount = $this->data[$field];
                    }

                    $amount = money(
                            $_amount, $account->get('Account Currency'), $locale = false, $fraction_digits
                        ).$suffix;

                    return $amount;
                }
                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Soft Minify$/', $key
                )) {

                    $field = 'Part Category '.preg_replace(
                            '/ Soft Minify$/', '', $key
                        );

                    $suffix          = '';
                    $fraction_digits = 'NO_FRACTION_DIGITS';
                    $_amount         = $this->data[$field];


                    $amount = money(
                            $_amount, $account->get('Account Currency'), $locale = false, $fraction_digits
                        ).$suffix;

                    return $amount;
                }
                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|Year To|Quarter To|Month To|Today|Week To).*(Margin|GMROI)$/', $key
                )) {

                    $amount = 'Part Category '.$key;

                    return percentage($this->data[$amount], 1);
                }
                if (preg_match(
                        '/^(Last|Yesterday|Total|1|10|6|3|Year To|Quarter To|Month To|Today|Week To).*(Given|Lost|Required|Sold|Dispatched|Broken|Customers)$/', $key
                    ) or $key == 'Current Stock') {

                    $amount = 'Part Category '.$key;

                    return number($this->data[$amount]);
                }
                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|2|4|Year To|Quarter To|Month To|Today|Week To).*(Given|Lost|Required|Sold|Dispatched|Broken|Acquired) Minify$/', $key
                )) {

                    $field = 'Part Category '.preg_replace(
                            '/ Minify$/', '', $key
                        );

                    $suffix          = '';
                    $fraction_digits = 0;
                    if ($this->data[$field] >= 10000) {
                        $suffix  = 'K';
                        $_number = $this->data[$field] / 1000;
                    } elseif ($this->data[$field] > 100) {
                        $fraction_digits = 1;
                        $suffix          = 'K';
                        $_number         = $this->data[$field] / 1000;
                    } else {
                        $_number = $this->data[$field];
                    }

                    return number($_number, $fraction_digits).$suffix;
                }
                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|2|4|Year To|Quarter To|Month To|Today|Week To).*(Given|Lost|Required|Sold|Dispatched|Broken|Acquired) Soft Minify$/', $key
                )) {
                    $field = 'Part Category '.preg_replace(
                            '/ Soft Minify$/', '', $key
                        );


                    $_number = $this->data[$field];

                    return number($_number, 0);
                }

                return '';
                break;

            case 'Supplier':

                switch ($key) {
                    case 'Valid From':

                        return strftime(
                            "%a %e %b %Y", strtotime(
                                             $this->data['Supplier Category '.$key].' +0:00'
                                         )
                        );


                        break;
                    case 'Valid To':

                        if ($this->data['Product Category '.$key] == '') {
                            return '';
                        } else {

                            return strftime(
                                "%a %e %b %Y", strtotime(
                                                 $this->data['Supplier Category '.$key].' +0:00'
                                             )
                            );

                        }
                        break;
                }

                include_once 'utils/natural_language.php';

                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit)$/', $key
                )) {

                    $field = 'Supplier Category '.$key;

                    return money(
                        $this->data[$field], $account->get('Account Currency')
                    );
                }
                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Minify$/', $key
                )) {

                    $field = 'Supplier Category '.preg_replace(
                            '/ Minify$/', '', $key
                        );

                    $suffix          = '';
                    $fraction_digits = 'NO_FRACTION_DIGITS';
                    if ($this->data[$field] >= 10000) {
                        $suffix  = 'K';
                        $_amount = $this->data[$field] / 1000;
                    } elseif ($this->data[$field] > 100) {
                        $fraction_digits = 'SINGLE_FRACTION_DIGITS';
                        $suffix          = 'K';
                        $_amount         = $this->data[$field] / 1000;
                    } else {
                        $_amount = $this->data[$field];
                    }

                    $amount = money(
                            $_amount, $account->get('Account Currency'), $locale = false, $fraction_digits
                        ).$suffix;

                    return $amount;
                }
                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Soft Minify$/', $key
                )) {

                    $field = 'Supplier Category '.preg_replace(
                            '/ Soft Minify$/', '', $key
                        );

                    $suffix          = '';
                    $fraction_digits = 'NO_FRACTION_DIGITS';
                    $_amount         = $this->data[$field];


                    $amount = money(
                            $_amount, $account->get('Account Currency'), $locale = false, $fraction_digits
                        ).$suffix;

                    return $amount;
                }
                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|Year To|Quarter To|Month To|Today|Week To).*(Margin|GMROI)$/', $key
                )) {

                    $amount = 'Supplier Category '.$key;

                    return percentage($this->data[$amount], 1);
                }
                if (preg_match(
                        '/^(Last|Yesterday|Total|1|10|6|3|Year To|Quarter To|Month To|Today|Week To).*(Given|Lost|Required|Sold|Dispatched|Broken|Customers)$/', $key
                    ) or $key == 'Current Stock') {

                    $amount = 'Supplier Category '.$key;

                    return number($this->data[$amount]);
                }
                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|2|4|Year To|Quarter To|Month To|Today|Week To).*(Given|Lost|Required|Sold|Dispatched|Broken|Acquired) Minify$/', $key
                )) {

                    $field = 'Supplier Category '.preg_replace(
                            '/ Minify$/', '', $key
                        );

                    $suffix          = '';
                    $fraction_digits = 0;
                    if ($this->data[$field] >= 10000) {
                        $suffix  = 'K';
                        $_number = $this->data[$field] / 1000;
                    } elseif ($this->data[$field] > 100) {
                        $fraction_digits = 1;
                        $suffix          = 'K';
                        $_number         = $this->data[$field] / 1000;
                    } else {
                        $_number = $this->data[$field];
                    }

                    return number($_number, $fraction_digits).$suffix;
                }
                if (preg_match(
                    '/^(Last|Yesterday|Total|1|10|6|3|2|4|Year To|Quarter To|Month To|Today|Week To).*(Given|Lost|Required|Sold|Dispatched|Broken|Acquired) Soft Minify$/', $key
                )) {
                    $field = 'Supplier Category '.preg_replace(
                            '/ Soft Minify$/', '', $key
                        );


                    $_number = $this->data[$field];

                    return number($_number, 0);
                }

                return '';
                break;
            case 'Invoice':

                include_once 'utils/natural_language.php';


                if (preg_match(
                    '/^(Yesterday|Today|Last|Week|Year|Month|Total|1|6|3).*(Invoices|Refunds)$/', $key
                )) {

                    $amount = 'Invoice Category '.$key;

                    return number($this->data[$amount]);
                }

                if (preg_match(
                    '/^(Yesterday|Today|Last|Week|Year|Month|Total|1|6|3).*(Amount|Profit|Paid|To Pay)$/', $key
                )) {

                    $amount = 'Invoice Category '.$key;

                    return money($this->data[$amount]);
                }

                return $key;
                break;

        }


        return false;
    }

    function update_no_assigned_subjects() {
        $no_assigned_subjects = 0;
        $assigned_subjects    = 0;

        $total_subjects = 0;

        switch ($this->data['Category Scope']) {
            case('Part'):

                $sql = sprintf("SELECT count(*) AS num FROM `Part Dimension`");
                break;

            case('Customer'):

                $sql = sprintf(
                    "SELECT count(*) AS num FROM `Customer Dimension` WHERE `Customer Store Key`=%d", $this->data['Category Store Key']
                );
                break;

            case('Supplier'):

                $sql = sprintf(
                    "SELECT count(*) AS num FROM `Supplier Dimension` "
                );
                break;
            case('Product'):

                $sql = sprintf("SELECT count(*) AS num FROM `Product Dimension` WHERE `Product Store Key`=%d ", $this->data['Category Store Key']);
                break;

            default:
                $table = $this->data['Category Subject'];
                $store = sprintf(
                    " where `%s Store Key`=%d", addslashes($this->data['Category Subject']), $this->data['Category Store Key']
                );
                $sql   = sprintf(
                    "SELECT count(*) AS num FROM `%s Dimension` %s", $table, $store
                );
                break;
        }


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $total_subjects = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print_r($this);
            print "$sql\n";
            exit;
        }


        $assigned_subjects = 0;
        $sql               = sprintf(
            "SELECT COUNT(DISTINCT `Subject Key`)  AS num FROM `Category Bridge`  WHERE `Category Key`=%d  ", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $assigned_subjects = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $no_assigned_subjects = $total_subjects - $assigned_subjects;


        $sql = sprintf(
            "UPDATE `Category Dimension` SET  `Category Subjects Not Assigned`=%d  WHERE `Category Root Key`=%d ", $no_assigned_subjects, $this->data['Category Root Key']
        );

        $this->data['Category Subjects Not Assigned'] = $no_assigned_subjects;


        $this->db->exec($sql);

    }

    function load_acc_data() {
        if ($this->data['Category Scope'] == 'Part') {

            $sql = sprintf(
                "SELECT * FROM `Part Category Data` WHERE `Part Category Key`=%d", $this->id
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

        } elseif ($this->data['Category Scope'] == 'Product') {


            $sql = sprintf(
                "SELECT * FROM `Product Category Data` WHERE `Product Category Key`=%d", $this->id
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
                "SELECT * FROM `Product Category DC Data` WHERE `Product Category Key`=%d", $this->id
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


        } elseif ($this->data['Category Scope'] == 'Invoice') {


            $sql = sprintf(
                "SELECT * FROM `Invoice Category Data` WHERE `Invoice Category Key`=%d", $this->id
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
                "SELECT * FROM `Invoice Category DC Data` WHERE `Invoice Category Key`=%d", $this->id
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


        }

    }

    function create_category($data) {


        unset($data['user']);

        $data['editor'] = $this->editor;

        if ($this->data['Category Deep'] > $this->data['Category Max Deep']) {

            $this->msg   = 'max deep';
            $this->error = true;

            return;
        }

        if (!isset($data['Category Label']) or $data['Category Label'] == '') {
            $data['Category Label'] = $data['Category Code'];
        }


        //  $branch_type = $this->data['Category Branch Type'];

        $data['Category Scope'] = $this->data['Category Scope'];

        $data['Category Subject'] = $this->data['Category Subject'];
        //  $data['Category Subject Key']=$this->data['Category Subject Key'];

        $data['Category Warehouse Key'] = $this->data['Category Warehouse Key'];


        if ($this->data['Category Store Key'] != 0 and array_key_exists(
                'Category Store Key', $data
            )) {
            $data['Category Store Key'] = $this->data['Category Store Key'];
        }

        if (empty($data['Category Branch Type'])) {
            $data['Category Branch Type'] = 'Head';
        }

        $data['Category Subject Multiplicity'] = $this->data['Category Subject Multiplicity'];

        $data['Category Root Key']   = $this->data['Category Root Key'];
        $data['Category Parent Key'] = $this->id;


        if (array_key_exists('Is Category Field Other', $data)) {
            if ($data['Is Category Field Other'] == 'Yes' and $this->data['Category Can Have Other'] == 'Yes' and $this->data['Category Children Other'] == 'No') {
                $data['Is Category Field Other'] = 'Yes';

            } else {
                $data['Is Category Field Other'] = 'No';
            }
        } else {
            $data['Is Category Field Other'] = 'No';
        }
        // $data['editor']

        $subcategory       = new Category('find create', $data);
        $subcategory->fork = $this->fork;

        //   print_r($subcategory);

        if ($subcategory->new) {


            /*

                    if ($data['Is Category Field Other'] == 'Yes') {
                        $this->data['Category Children Other'] = 'Yes';
                        $sql                                   = sprintf(
                            "UPDATE `Category Dimension` SET `Category Children Other`=%s WHERE `Category Key`=%d", prepare_mysql($this->data['Category Children Other']), $this->id
                        );
                        $this->db->exec($sql);
                    }
            */

            //  print_r($subcategory);

            if ($this->get('Category Scope') == 'Product') {


                $store = get_object('Store', $this->get('Category Store Key'));


                if ($this->get('Category Root Key') == $store->get('Store Family Category Key')) {


                    $account = get_object('Account', 1);

                    $sql = sprintf(
                        'SELECT `Image Subject Image Key` FROM `Image Subject Bridge` LEFT JOIN `Category Dimension` ON (`Image Subject Object Key`=`Category Key`)  WHERE `Category Subject`="Part" AND  `Image Subject Object Image Scope`="Marketing"  and   `Category Code`=%s  AND `Category Root Key`=%d AND `Image Subject Object`="Category" ',
                        prepare_mysql($subcategory->get('Category Code')), $account->get('Account Part Family Category Key')
                    );


                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {
                            //  print_r($row);
                            $subcategory->link_image($row['Image Subject Image Key'], 'Marketing');


                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        exit;


                    }


                }


                $subcategory->update_product_category_products_data();


            }


            return $subcategory;
        } else {
            if ($subcategory->found) {
                $this->error = true;

                if ($subcategory->duplicated_field == 'Category Code') {
                    $this->msg = _('Duplicated code');
                } else {
                    $this->msg = "Category already exists";
                }

                return $subcategory;
            } else {
                $this->error = true;

                return false;
            }
        }


    }


    function delete() {

        if (!$this->id) {
            return;
        }

        $this->deleted = false;

        $sql_new_deleted_category = sprintf(
            "INSERT INTO `Category Deleted Dimension` (`Category Deleted Key`, `Category Deleted Branch Type`, `Category Deleted Store Key`, `Category Deleted Warehouse Key`,
`Category Deleted Deep`, `Category Deleted Children`, `Category Deleted Code`, `Category Deleted Label`, `Category Deleted Subject`,  `Category Deleted Number Subjects`,`Category Deleted Date`)
VALUES (%d,%s, %d, %d, %d, %d, %s, %s, %s,%d,NOW())", $this->id,


            prepare_mysql($this->data['Category Branch Type']), $this->data['Category Store Key'], $this->data['Category Warehouse Key'], $this->data['Category Deep'], $this->data['Category Children'], prepare_mysql($this->data['Category Code']),
            prepare_mysql($this->data['Category Label']), prepare_mysql($this->data['Category Subject']), $this->data['Category Number Subjects']

        );

        $is_category_other = $this->data['Is Category Field Other'];

        $parent_keys = $this->get_parent_keys();

        foreach ($this->get_children_objects() as $children) {

            $children->delete();
        }


        $sql = sprintf(
            "SELECT `Subject Key` FROM `Category Bridge`  WHERE `Category Key`=%d  ", $this->id
        );

        $this->deleting_category = true;

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $this->disassociate_subject($row['Subject Key']);
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        if ($this->data['Category Scope'] == 'Invoice') {
            $sql = sprintf(
                'DELETE FROM `Invoice Category Dimension` WHERE `Invoice Category Key`=%d', $this->id
            );
            $this->db->exec($sql);


            $sql = sprintf(
                "DELETE FROM `Invoice Category Data` WHERE `Invoice Category Key`=%d ", $this->id

            );
            $this->db->exec($sql);
            $sql = sprintf(
                "DELETE FROM  `Invoice Category DC Data` WHERE `Invoice Category Key`=%d ", $this->id
            );
            $this->db->exec($sql);


        } elseif ($this->data['Category Scope'] == 'Supplier') {
            $sql = sprintf(
                'DELETE FROM `Supplier Category Dimension` WHERE `Supplier Category Key`=%d', $this->id
            );
            $this->db->exec($sql);
        } elseif ($this->data['Category Scope'] == 'Part') {
            $sql = sprintf(
                'DELETE FROM `Part Category Dimension`  WHERE `Part Category Key`=%d', $this->id
            );
            $this->db->exec($sql);
        } elseif ($this->data['Category Scope'] == 'Product') {
            $sql = sprintf(
                'DELETE FROM `Product Category Dimension`  WHERE `Product Category Key`=%d', $this->id
            );
            $this->db->exec($sql);
            $sql = sprintf(
                'DELETE FROM `Product Category Data`  WHERE `Product Category Key`=%d', $this->id
            );
            $this->db->exec($sql);
            $sql = sprintf(
                'DELETE FROM `Product Category DC Data`  WHERE `Product Category Key`=%d', $this->id
            );
            $this->db->exec($sql);
        }


        $this->db->exec($sql_new_deleted_category);


        $sql = sprintf(
            'DELETE FROM `Category Dimension` WHERE `Category Key`=%d', $this->id
        );
        $this->db->exec($sql);


        $sql = "DELETE FROM `Category Bridge` WHERE  `Subject`='Category'  and  `Subject Key`=?";

        $this->db->prepare($sql)->execute(
            array(
                $this->id
            )
        );


        $sql = sprintf(
            'DELETE FROM `Category Bridge` WHERE   and  `Category Key`=%d', $this->id
        );


        $this->db->exec($sql);


        foreach ($parent_keys as $parent_key) {
            /** @var $parent_category \Category */
            $parent_category         = get_object('Category', $parent_key);
            $parent_category->editor = $this->editor;
            if ($parent_category->id) {
                $parent_category->update_children_data();

                if ($is_category_other == 'Yes') {
                    $parent_category->data['Category Children Other'] = 'No';

                    $sql = sprintf(
                        "UPDATE `Category Dimension` SET `Category Children Other`=%s WHERE `Category Key`=%d", prepare_mysql($parent_category->data['Category Children Other']), $parent_category->id
                    );
                    $this->db->exec($sql);
                }


            }
        }

        $history_data = array(
            'Direct Object Key'   => $this->id,
            'Direct Object'       => 'Category '.$this->data['Category Subject'],
            'Indirect Object Key' => $this->data['Category Parent Key'],
            'Indirect Object'     => 'Category '.$this->data['Category Subject'],
            'History Abstract'    => _('Category deleted').' ('.$this->data['Category Code'].')',
            'History Details'     => _trim(
                _('Category')." ".$this->data['Category Code'].' ('.$this->data['Category Label'].') '._(
                    'has been deleted permanently'
                )
            ),
            'Action'              => 'deleted'
        );
        $this->add_history($history_data);
        $this->deleted = true;

        $this->fork_index_elastic_search('delete_elastic_index_object');

    }

    function get_parent_keys() {
        $category_tree_keys = preg_split(
            '/\>/', preg_replace('/\>$/', '', $this->data['Category Position'])
        );
        array_pop($category_tree_keys);

        return $category_tree_keys;
    }

    function get_children_objects() {
        $sql = sprintf(
            "SELECT `Category Key`   FROM `Category Dimension` WHERE `Category Parent Key`=%d ORDER BY `Category Code` ", $this->id
        );
        //  print $sql;

        $children_objects = array();
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $children_objects[$row['Category Key']] = new Category(
                    $row['Category Key']
                );
            }
        }

        return $children_objects;

    }

    function disassociate_subject($subject_key, $options = '') {


        if (!$this->is_subject_associated($subject_key)) {
            return true;
        }

        //print "Deleting  $subject_key   from  ".$this->id."  \n";
        if ($this->data['Category Branch Type'] != 'Head') {


            $sql = sprintf(
                "SELECT B.`Category Head Key` FROM `Category Bridge` B LEFT JOIN `Category Dimension` C ON (C.`Category Key`=B.`Category Key`) WHERE `Category Root Key`=%d AND `Subject`=%s AND `Subject Key`=%d AND `Category Branch Type`='Head' GROUP BY `Category Head Key` ",
                $this->data['Category Root Key'], prepare_mysql($this->data['Category Subject']), $subject_key
            );

            $return_value = false;


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {

                    $head_category         = get_object('Category', $row['Category Head Key']);
                    $head_category->editor = $this->editor;
                    if ($head_category->disassociate_subject($subject_key, $options)) {
                        $return_value = true;
                    }
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }


            $this->get_data('id', $this->id);

            return $return_value;

        }


        $sql = sprintf(
            "DELETE FROM `Category Bridge` WHERE `Category Key`=%d AND `Subject`=%s AND `Subject Key`=%d", $this->id, prepare_mysql($this->data['Category Subject']), $subject_key
        );


        $del = $this->db->prepare($sql);
        $del->execute();


        $deleted = $del->rowCount();

        if ($deleted) {

            $this->update_number_of_subjects();
            $this->update_subjects_data();


            switch ($this->data['Category Scope']) {
                case('Part'):
                    include_once 'class.Part.php';

                    $part     = new Part($subject_key);
                    $abstract = _('Part').': <a href="part.php?sku='.$part->id.'">SKU'.sprintf('%05d', $part->id).'</a> '._(
                            'disassociated with category'
                        ).sprintf(
                            ' <a href="part_category.php?id=%d">%s</a>', $this->id, $this->data['Category Code']
                        );
                    $details  = _('Part').': <a href="part.php?sku='.$part->id.'">SKU'.sprintf('%05d', $part->id).'</a> ('.$part->data['Part Package Description'].') '._(
                            'disassociated with category'
                        ).sprintf(
                            ' <a href="part_category.php?id=%d">%s</a>', $this->id, $this->data['Category Code']
                        ).' ('.$this->data['Category Label'].')';
                    break;
                case('Location'):
                    include_once 'class.Location.php';

                    $location = new Location($subject_key);
                    $abstract = _('Part').': <a href="part.php?sku='.$part->id.'">SKU'.sprintf('%05d', $part->id).'</a> '._(
                            'disassociated with category'
                        ).sprintf(
                            ' <a href="part_category.php?id=%d">%s</a>', $this->id, $this->data['Category Code']
                        );
                    $details  = _('Part').': <a href="part.php?sku='.$part->id.'">SKU'.sprintf('%05d', $part->id).'</a> ('.$part->data['Part Package Description'].') '._(
                            'disassociated with category'
                        ).sprintf(
                            ' <a href="part_category.php?id=%d">%s</a>', $this->id, $this->data['Category Code']
                        ).' ('.$this->data['Category Label'].')';
                    break;
                case('Supplier'):
                    include_once 'class.Supplier.php';

                    $supplier = new Supplier($subject_key);
                    $abstract = _('Supplier').': <a href="supplier.php?id='.$supplier->id.'">'.$supplier->data['Supplier Code'].'</a> '._('disassociated with category').sprintf(
                            ' <a href="part_category.php?id=%d">%s</a>', $this->id, $this->data['Category Code']
                        );
                    $details  = _('Supplier').': <a href="supplier.php?id='.$supplier->id.'">'.$supplier->data['Supplier Code'].'</a> ('.$supplier->data['Supplier Name'].') '._(
                            'disassociated with category'
                        ).sprintf(
                            ' <a href="part_category.php?id=%d">%s</a>', $this->id, $this->data['Category Code']
                        ).' ('.$this->data['Category Label'].')';
                    break;
                case('Customer'):
                    include_once 'class.Customer.php';

                    $customer = new Customer($subject_key);
                    $abstract = _('Customer').': <a href="customer.php?id='.$customer->id.'">'.$customer->data['Customer Name'].'</a> '._('disassociated with category').sprintf(
                            ' <a href="part_category.php?id=%d">%s</a>', $this->id, $this->data['Category Code']
                        );
                    $details  = _('Customer').': <a href="customer.php?id='.$customer->id.'">'.$customer->data['Customer Name'].'</a>  '._(
                            'associated with category'
                        ).sprintf(
                            ' <a href="part_category.php?id=%d">%s</a>', $this->id, $this->data['Category Code']
                        ).' ('.$this->data['Category Label'].')';
                    break;
                case('Product'):
                    include_once 'class.Product.php';

                    $product = new Product($subject_key);

                    $abstract = sprintf(_('Product %s disassociated from category %s'), $product->get('Code'), $this->get('Code'));
                    $details  = '';


                    $store = get_object('Store', $this->get('Category Store Key'));
                    if ($this->get('Category Root Key') == $store->get('Store Family Category Key')) {


                        if (!preg_match('/skip_direct_update/', $options)) {
                            $product->update(
                                array(
                                    'Product Family Category Key' => ''
                                ), 'no_history'
                            );
                        }


                    }


                    break;


                default:
                    $abstract = 'todo';
                    $details  = 'todo';
            }

            if (isset($this->deleting_category)) {
                $abstract .= ' ('._('Category Deleted').')';
            }

            $history_data = array(
                'Direct Object'       => $this->data['Category Subject'],
                'Direct Object Key'   => $subject_key,
                'Action'              => 'associated',
                'Preposition'         => 'to',
                'Indirect Object'     => 'Category '.$this->data['Category Subject'],
                'Indirect Object Key' => $this->id,
                'History Abstract'    => $abstract,
                'History Details'     => $details
            );


            $history_key = $this->add_history(
                $history_data, $force = false, $post_arg1 = 'Assign'
            );

            switch ($this->data['Category Subject']) {
                case('Part'):
                    break;
                case('Supplier'):
                    break;
                case('Customer'):
                    $sql = sprintf(
                        "INSERT INTO `Customer History Bridge` VALUES (%d,%d,'No','No','Changes')", $customer->id, $history_key
                    );
                    // print $sql;
                    $this->db->exec($sql);
                    break;
                case('Product'):
                    break;
                default:

            }


            foreach ($this->get_parent_keys() as $parent_key) {


                $sql = sprintf(
                    "DELETE FROM `Category Bridge` WHERE `Category Key`=%d AND `Subject`=%s AND `Subject Key`=%d", $parent_key, prepare_mysql($this->data['Category Subject']), $subject_key
                );
                $del = $this->db->prepare($sql);
                $del->execute();

                if ($del->rowCount()) {
                    $parent_category = new Category($parent_key);

                    $parent_category->update_number_of_subjects();
                    $parent_category->update_subjects_data();

                }
            }

            // NOTE: no tested
            if ($this->data['Category Subject Multiplicity'] == 'Yes') {
                $sql = sprintf(
                    "SELECT B.`Category Key` FROM `Category Bridge` B LEFT JOIN `Category Dimension` C ON (C.`Category Key`=B.`Category Key`) WHERE `Category Root Key`=%d AND `Subject`=%s AND `Subject Key`=%d AND `Category Branch Type`='Head'",
                    $this->data['Category Root Key'], prepare_mysql($this->data['Category Subject']), $subject_key
                );


                if ($result = $this->db->query($sql)) {
                    foreach ($result as $row) {


                        $category = new Category($row['Category Key']);
                        foreach ($category->get_parent_keys() as $parent_key) {
                            $sql = sprintf(
                                "INSERT INTO `Category Bridge` VALUES (%d,%s,%d, NULL,%d,1)", $parent_key, prepare_mysql(
                                $category->data['Category Subject']
                            ), $subject_key, $subject_key
                            );

                            $insert = $this->db->prepare($sql);
                            $insert->execute();


                            if ($insert->rowCount()) {
                                $parent_category = new Category($parent_key);
                                $parent_category->update_number_of_subjects();
                                $parent_category->update_subjects_data();

                            }
                        }


                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }


            }


        }


        if ($this->data['Category Scope'] == 'Product') {


            $webpage = get_object('Webpage', $this->get('Product Category Webpage Key'));

            if ($webpage->id) {

                $website = get_object('Website', $webpage->get('Webpage Website Key'));

                $webpage->reindex_items();


            }
        }

        return $deleted;

    }

    function is_subject_associated($subject_key) {
        $sql = sprintf(
            "SELECT `Subject Key` FROM `Category Bridge` WHERE `Category Key`=%d AND `Subject Key`=%d ", $this->id, $subject_key
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                return true;
            } else {
                return false;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }

    function update_subjects_data() {

        include_once 'utils/date_functions.php';


        if ($this->data['Category Branch Type'] == 'Root' or !$this->update_subjects_data) {
            return;

        }


        //print "updatiog cat ".$this->id."   \n";
        //$this->update_up_today();
        //$this->update_last_period();
        //$this->update_last_interval();
    }

    function update_children_data() {

        $number_of_children = 0;

        $sql = sprintf(
            "SELECT COUNT(*)  AS num  FROM `Category Dimension` WHERE `Category Parent Key`=%d AND `Category Subject`=%s ", $this->id, prepare_mysql($this->data['Category Subject'])
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_of_children = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $has_children_other = 'No';
        $sql                = sprintf(
            "SELECT COUNT(*)  AS num  FROM `Category Dimension` WHERE `Category Parent Key`=%d AND `Category Subject`=%s AND `Is Category Field Other`='Yes' ", $this->id, prepare_mysql($this->data['Category Subject'])
        );

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                if ($row['num'] > 0) {
                    $has_children_other = 'Yes';
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $max_deep = 0;
        if ($number_of_children) {

            $sql = sprintf(
                "SELECT `Category Position`  FROM `Category Dimension` WHERE `Category Position`	RLIKE '^%s[0-9]+>$' AND `Category Subject`=%s ", $this->data['Category Position'], prepare_mysql($this->data['Category Subject'])
            );


            $max_deep = 0;


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $deep = count(preg_split('/\>/', $row['Category Position'])) - 2;
                    if ($deep > $max_deep) {
                        $max_deep = $deep;
                    }
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }


        }

        $sql = sprintf(
            "UPDATE `Category Dimension` SET `Category Children`=%d ,`Category Children Deep`=%d , `Category Children Other`=%s WHERE `Category Key`=%d ", $number_of_children, $max_deep, prepare_mysql($has_children_other), $this->id
        );
        $this->db->exec($sql);


        if ($this->data['Category Branch Type'] != 'Root') {
            if ($number_of_children) {
                $sql = sprintf(
                    "UPDATE `Category Dimension` SET `Category Branch Type`='Node' WHERE `Category Key`=%d ", $this->id
                );
                $this->db->exec($sql);
            } else {

                $sql = sprintf(
                    "UPDATE `Category Dimension` SET `Category Branch Type`='Head' WHERE `Category Key`=%d ", $this->id
                );
                $this->db->exec($sql);
            }


        }


    }


    function update_field_switcher($field, $value, $options = '', $metadata = '') {



        if (array_key_exists($field, $this->base_data())) {


            if ($field == 'Category Code') {


                $this->update_field($field, $value, $options);

                $this->load_all_descendants_keys();


                $this->fork_index_elastic_search();

            } elseif ($field == 'Category Label') {


                $this->update_field($field, $value, $options);


                // print 'x'.$this->last_history_key.'xx>'.$this->data['Category Scope'].'<z';

                if (!empty($this->last_history_key)) {


                    $this->post_add_history($this->last_history_key);


                }
                $this->fork_index_elastic_search();

            } elseif ($value != $this->data[$field]) {

                $this->update_field($field, $value, $options);

            }
        }
        elseif (array_key_exists($field, $this->base_data('Product Category Dimension'))) {


            switch ($field) {



                case 'Product Category Department Category Key':

                    $hide = array();
                    $show = array();


                    if ($value) {


                        if ($this->data['Product Category Department Category Key'] != $value) {


                            $old_parent_category = get_object('Category', $this->data['Product Category Department Category Key']);
                            $new_parent_category = get_object('Category', $value);


                            $new_parent_category->associate_subject($this->id, false, '', 'skip_direct_update');


                            $new_parent_category->update_product_category_products_data();


                            if ($old_parent_category->id) {
                                $old_parent_category->update_product_category_products_data();

                                $webpage = $old_parent_category->get_webpage();
                                if ($webpage->id) {
                                    $webpage->reindex_items();
                                    if ($webpage->updated) {
                                        $webpage->publish();
                                    }
                                }
                            }


                            $webpage = $new_parent_category->get_webpage();

                            if ($webpage->id) {


                                $webpage->reindex_items();

                                if ($webpage->updated) {
                                    $webpage->publish();
                                }
                            }


                            //todo Urgent update navigation of products

                            $sql = "update `Product Dimension` set `Product Department Category Key`=?  where `Product Family Category Key`=?";

                            $this->db->prepare($sql)->execute(
                                array(
                                    $new_parent_category->id,
                                    $this->id
                                )
                            );


                            $this->db->exec($sql);

                            $this->fast_update(
                                [
                                    'Product Category Department Category Key' => $new_parent_category->id
                                ], 'Product Category Dimension'
                            );
                        }


                        $hide = array('no_department_warning');


                    }
                    else {
                        if ($this->data['Product Category Department Category Key'] != '') {


                            $category         = get_object('Category', $this->data['Product Category Department Category Key']);
                            $category->editor = $this->editor;
                            if ($category->id) {
                                $category->disassociate_subject($this->id);
                                $category->update_product_category_products_data();

                            }

                        }


                        $sql = "update `Product Dimension` set `Product Department Category Key`=NULL  where `Product Family Category Key`=?";

                        $this->db->prepare($sql)->execute(
                            array(
                                $this->id
                            )
                        );


                        $this->db->exec($sql);


                        $show = array('no_department_warning');


                        $this->fast_update(
                            [
                                'Product Category Department Category Key' => ''
                            ], 'Product Category Dimension'
                        );


                    }


                    $categories = '';
                    foreach ($this->get_category_data() as $item) {
                        $categories .= sprintf(
                            '<li><span class="button" onclick="change_view(\'category/%d\')" title="%s">%s</span></li>', $item['category_key'], $item['label'], $item['code']

                        );

                    }

                    $webpage = $this->get_webpage();
                    if ($webpage->id) {
                        $webpage->update_navigation();

                        $this->update_metadata = array(
                            'class_html' => array(
                                'Categories' => $categories,

                            ),
                            'hide'       => $hide,
                            'show'       => $show
                        );
                    }

                    break;


                case 'Product Category Description':


                    $value = html_entity_decode($value);
                    //$this->update_subject_field($field, $value, $options);


                    $this->update_table_field(
                        $field, $value, $options, 'Product Category', 'Product Category Dimension', $this->id
                    );


                    break;


                case 'Product Category Public':


                    $this->update_table_field($field, $value, $options, 'Product Category', 'Product Category Dimension', $this->id);


                    if ($this->updated) {
                        $webpage         = $this->get_webpage();
                        $webpage->editor = $this->editor;
                        if ($webpage->id) {
                            $webpage->reindex_items();
                            if ($webpage->updated) {

                                if ($value == 'Yes') {
                                    $webpage->publish();
                                } else {
                                    $webpage->unpublish();
                                }

                            }
                        }


                    }


                    $this->get_webpage();
                    if ($value == 'No') {


                    } elseif ($value == 'Yes') {


                        if ($this->webpage->id) {
                            $this->webpage->update(array('Webpage State' => 'Online'));


                        } else {
                            // todo: create webpage??
                        }


                    }


                    break;


                default:
                    $this->update_table_field(
                        $field, $value, $options, 'Product Category', 'Product Category Dimension', $this->id
                    );

                // $this->update_subject_field($field, $value, $options);
            }
        } elseif (array_key_exists($field, $this->base_data('Part Category Dimension'))) {


            $this->update_table_field($field, $value, $options, 'Part Category', 'Part Category Dimension', $this->id);


        } elseif (array_key_exists($field, $this->base_data('Part Category Data'))) {
            $this->update_table_field($field, $value, $options, 'Part Category', 'Part Category Data', $this->id);
        } elseif (array_key_exists($field, $this->base_data('Product Category Data'))) {
            $this->update_table_field($field, $value, $options, 'Product Category', 'Product Category Data', $this->id);
        } elseif (array_key_exists($field, $this->base_data('Product Category DC Data'))) {
            $this->update_table_field($field, $value, $options, 'Product Category DC', 'Product Category DC Data', $this->id);
        } elseif (array_key_exists($field, $this->base_data('Invoice Category Dimension'))) {
            $this->update_table_field($field, $value, $options, 'Invoice Category', 'Invoice Category Dimension', $this->id);
        } elseif (array_key_exists($field, $this->base_data('Invoice Category Data'))) {
            $this->update_table_field($field, $value, $options, 'Invoice Category', 'Invoice Category Data', $this->id);
        } elseif (array_key_exists($field, $this->base_data('Invoice Category DC Data'))) {
            $this->update_table_field($field, $value, $options, 'Invoice Category', 'Invoice Category DC Data', $this->id);
        } elseif (array_key_exists($field, $this->base_data('Supplier Category Data'))) {
            $this->update_table_field($field, $value, $options, 'Supplier Category', 'Supplier Category Data', $this->id);
        } elseif (array_key_exists($field, $this->base_data('Supplier Category Dimension'))) {
            $this->update_table_field($field, $value, $options, 'Supplier Category', 'Supplier Category Dimension', $this->id);
        } else {


            switch ($field) {
                case 'Subdepartment Parent Key':


                    if($value==$this->id){
                        $this->error=true;
                        $this->msg="🙄 imbecile";
                        return;
                    }


                    $old_parent_category = get_object('Category', $this->data['Category Parent Key']);
                    $new_parent_category = get_object('Category', $value);
                    $new_parent_category->associate_subject($this->id, false, '', 'skip_direct_update');

                    $this->fast_update(
                        [
                            'Category Parent Key'=>$value
                        ]
                    );

                    break;
                case 'History Note':


                    $this->add_note($value, '', '', $metadata['deletable']);
                    break;

                case 'Part Category Status Including Parts':
                    include_once 'class.Part.php';
                    $old_formatted_value = $this->get('Status');

                    if ($value == 'Discontinuing') {

                        $sql     = sprintf(
                            "SELECT P.`Part SKU` FROM  `Part Dimension` P LEFT JOIN `Category Bridge` B ON (P.`Part SKU`=B.`Subject Key`)  WHERE B.`Category Key`=%d AND `Subject`='Part' AND P.`Part Status` IN ('In Use','In Process') ", $this->id
                        );
                        $counter = 0;
                        if ($result = $this->db->query($sql)) {
                            foreach ($result as $row) {

                                $part         = get_object('Part', $row['Part SKU']);
                                $part->editor = $this->editor;
                                $part->update_status($value, $options);
                                $counter++;
                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            print "$sql\n";
                            exit;
                        }

                        if ($counter == 0) {
                            $this->update(
                                array(
                                    'Part Category Status' => 'NotInUse'
                                ), 'no_history'
                            );
                        }

                    } elseif ($value == 'In Use') {
                        $sql = sprintf(
                            "SELECT P.`Part SKU` FROM  `Part Dimension` P LEFT JOIN `Category Bridge` B ON (P.`Part SKU`=B.`Subject Key`)  WHERE B.`Category Key`=%d AND `Subject`='Part' AND P.`Part Status` IN ('Discontinuing') ", $this->id
                        );

                        if ($result = $this->db->query($sql)) {
                            foreach ($result as $row) {

                                $part         = get_object('Part', $row['Part SKU']);
                                $part->editor = $this->editor;
                                $part->update_status($value, $options);

                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            print "$sql\n";
                            exit;
                        }

                        if ($this->get('Part Category Active') == 0) {
                            $this->update(
                                array(
                                    'Part Category Status' => 'InProcess'
                                ), 'no_history'
                            );
                        }


                    }

                    $this->get_data('id', $this->id);


                    $this->update_metadata = array(
                        'class_html' => array(
                            'Part_Status'         => $this->get('Status'),
                            'In_Process_Parts'    => $this->get('In Process'),
                            'In_Use_Parts'        => $this->get('Active'),
                            'Discontinuing_Parts' => $this->get('Discontinuing'),
                            'Not_In_Use_Parts'    => $this->get('Discontinued'),
                            'Valid_To'            => $this->get('Valid To'),

                        )
                    );

                    if ($this->get('Part Category Status') == 'NotInUse') {
                        $this->update_metadata['show'] = array('Valid_To');
                    } else {
                        $this->update_metadata['hide'] = array('Valid_To');

                    }
                    $new_formatted_value = $this->get('Status');

                    if ($new_formatted_value != $old_formatted_value) {
                        $this->add_changelog_record($field, $old_formatted_value, $new_formatted_value, '', $this->get_object_name(), $this->id);
                    }


                    break;


                default:


                    break;


            }

        }


    }

    function load_all_descendants_keys($category_key = false) {

        if (!$category_key) {
            $category_key               = $this->id;
            $this->all_descendants_keys = array();
        }

        $sql = sprintf(
            "SELECT `Category Key`   FROM `Category Dimension` WHERE `Category Parent Key`=%d AND `Category Key`!=0 ", $category_key
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $this->all_descendants_keys[$row['Category Key']] = $row['Category Key'];
                $this->load_all_descendants_keys($row['Category Key']);
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


    }

    function post_add_history($history_key, $type = false) {

        if (!$type) {
            $type = 'Changes';
        }

        switch ($this->data['Category Scope']) {
            case('Part'):
                $sql = sprintf(
                    "INSERT INTO  `Part Category History Bridge` VALUES (%d,%d,%s)", $this->id, $history_key, prepare_mysql($type)
                );
                $this->db->exec($sql);
                break;

            case('Supplier'):
                $sql = sprintf(
                    "INSERT INTO  `Supplier Category History Bridge` VALUES (%d,%d,%s)", $this->id, $history_key, prepare_mysql($type)
                );
                $this->db->exec($sql);
                break;
            case('Customer'):
                $sql = sprintf(
                    "INSERT INTO  `Customer Category History Bridge` VALUES (%d,%d,%d,%s)", $this->data['Category Store Key'], $this->id, $history_key, prepare_mysql($type)
                );
                $this->db->exec($sql);
                break;
            case('Product'):


                $sql = sprintf(
                    'insert into `Product Category History Bridge` (`Store Key`,`Category Key`,`History Key`,`Type`)  values (%d,%d,%d,%s)', $this->get('Store Key'), $this->id, $history_key, prepare_mysql($type)
                );


                $this->db->exec($sql);
                $this->update_product_category_history_records_data();


                break;

        }
    }

    function associate_subject($subject_key, $force_associate = false, $other_value = '', $options = '') {


        if ($this->data['Category Branch Type'] == 'Root') {
            $this->msg = "Subject can't be associated with category (Node is Root)";

            return false;
        }

        if ($this->is_subject_associated($subject_key)) {

            return true;
        }

        if ($this->data['Category Subject Multiplicity'] == 'Yes' or $force_associate) {

            $sql = sprintf(
                "INSERT INTO `Category Bridge` (`Category Key`,`Subject`,`Subject Key`,`Other Note`,`Category Head Key`)  VALUES (%d,%s,%d,%s,%d)", $this->id, prepare_mysql($this->data['Category Subject']), $subject_key, prepare_mysql($other_value), $this->id
            );


            $update_op = $this->db->prepare($sql);
            $update_op->execute();
            $inserted = $update_op->rowCount();

            // print $inserted;

            if ($inserted) {
                $this->update_number_of_subjects();
                $this->update_subjects_data();


                switch ($this->data['Category Scope']) {
                    case('Part'):

                        $part     = get_object('Part', $subject_key);
                        $abstract = _('Part').': <a href="part.php?sku='.$part->id.'">SKU'.sprintf('05%d', $part->id).'</a> '._('associated with category').sprintf(
                                ' <a href="part_category.php?id=%d">%s</a>', $this->id, $this->data['Category Code']
                            );
                        $details  = _('Part').': <a href="part.php?sku='.$part->id.'">SKU'.sprintf('05%d', $part->id).'</a> ('.$part->data['Part Package Description'].') '._(
                                'associated with category'
                            ).sprintf(
                                ' <a href="part_category.php?id=%d">%s</a>', $this->id, $this->data['Category Code']
                            ).' ('.$this->data['Category Label'].')';

                        $this->update_part_category_status();
                        break;
                    case('Supplier'):

                        $supplier = get_object('Supplier', $subject_key);
                        $abstract = _('Supplier').': <a href="supplier.php?id='.$supplier->id.'">'.$supplier->data['Supplier Code'].'</a> '._('associated with category').sprintf(
                                ' <a href="part_category.php?id=%d">%s</a>', $this->id, $this->data['Category Code']
                            );
                        $details  = _('Supplier').': <a href="supplier.php?id='.$supplier->id.'">'.$supplier->data['Supplier Code'].'</a> ('.$supplier->data['Supplier Name'].') '._(
                                'associated with category'
                            ).sprintf(
                                ' <a href="part_category.php?id=%d">%s</a>', $this->id, $this->data['Category Code']
                            ).' ('.$this->data['Category Label'].')';
                        break;
                    case('Customer'):

                        $customer = get_object('Customer', $subject_key);
                        $abstract = _('Customer').': <a href="customer.php?id='.$customer->id.'">'.$customer->data['Customer Name'].'</a> '._('associated with category').sprintf(
                                ' <a href="part_category.php?id=%d">%s</a>', $this->id, $this->data['Category Code']
                            );
                        $details  = _('Customer').': <a href="customer.php?id='.$customer->id.'">'.$customer->data['Customer Name'].'</a>  '._(
                                'associated with category'
                            ).sprintf(
                                ' <a href="part_category.php?id=%d">%s</a>', $this->id, $this->data['Category Code']
                            ).' ('.$this->data['Category Label'].')';


                        break;
                    case('Product'):


                        $store = get_object('Store', $this->get('Category Store Key'));

                        if ($this->get('Category Subject') == 'Category') {

                            $_cat         = get_object('Category', $subject_key);
                            $_cat->editor = $this->editor;
                            $abstract     = sprintf(_('Category %s associated with category %s'), $_cat->get('Code'), $this->get('Code'));
                            $details      = '';
                            if (!preg_match('/skip_direct_update/', $options)) {

                                if ($this->get('Category Root Key') == $store->get('Store Department Category Key')) {

                                    $_cat->fast_update(
                                        [
                                            'Product Category Department Category Key' => $this->id,
                                        ], 'Product Category Dimension'
                                    );

                                    $sql = "update `Product Dimension` set `Product Department Category Key`=?  where `Product Family Category Key`=? ";

                                    $this->db->prepare($sql)->execute(
                                        array(
                                            $this->id,
                                            $_cat->id
                                        )
                                    );


                                }
                            }


                        } elseif ($this->get('Category Subject') == 'Product') {

                            $product         = get_object('Product', $subject_key);
                            $product->editor = $this->editor;
                            $abstract        = sprintf(_('Product %s associated with category %s'), $product->get('Code'), $this->get('Code'));
                            $details         = '';

                            if (!preg_match('/skip_direct_update/', $options)) {


                                if ($this->get('Category Root Key') == $store->get('Store Family Category Key')) {

                                    $product->fast_update(
                                        [
                                            'Product Family Category Key'     => $this->id,
                                            'Product Department Category Key' => $this->get('Product Category Department Category Key')
                                        ]
                                    );
                                }
                            }

                        }


                        $webpage = get_object('Webpage', $this->get('Product Category Webpage Key'));

                        //  print_r($webpage);

                        if ($webpage->id) {
                            $webpage->reindex_items();
                        }


                        $this->update_product_category_products_data();

                        break;
                    default:
                        $abstract = 'todo';
                        $details  = 'todo';
                }


                $history_data = array(
                    'Direct Object'       => $this->data['Category Subject'],
                    'Direct Object Key'   => $subject_key,
                    'Action'              => 'associated',
                    'Preposition'         => 'to',
                    'Indirect Object'     => 'Category '.$this->data['Category Subject'],
                    'Indirect Object Key' => $this->id,
                    'History Abstract'    => $abstract,
                    'History Details'     => $details
                );


                $history_key = $this->add_history(
                    $history_data, $force = false, $post_arg1 = 'Assign'
                );


                switch ($this->data['Category Subject']) {
                    case('Part'):
                        break;
                    case('Supplier'):
                        break;
                    case('Customer'):
                        $sql = sprintf(
                            "INSERT INTO `Customer History Bridge` VALUES (%d,%d,'No','No','Changes')", $customer->id, $history_key
                        );
                        // print $sql;
                        $this->db->exec($sql);
                        break;
                    case('Product'):
                        break;
                    default:

                }


                foreach ($this->get_parent_keys() as $parent_key) {
                    $sql       = sprintf(
                        "INSERT INTO `Category Bridge` (`Category Key`,`Subject`,`Subject Key`,`Other Note`,`Category Head Key`) VALUES (%d,%s,%d, NULL,%d)", $parent_key, prepare_mysql($this->data['Category Subject']), $subject_key, $this->id
                    );
                    $update_op = $this->db->prepare($sql);
                    $update_op->execute();
                    $inserted = $update_op->rowCount();

                    if ($inserted) {
                        $parent_category = new Category($parent_key);

                        $parent_category->update_number_of_subjects();

                        $parent_category->update_subjects_data();

                    }
                }

                return true;
            } else {
                $this->msg = _("Subject can't be associated with category");

                return false;
            }

        }
        else {


            $sql = sprintf(
                "SELECT B.`Category Key` FROM `Category Bridge` B LEFT JOIN `Category Dimension` C    ON  (C.`Category Key`=B.`Category Key`) 
                        WHERE  `Category Branch Type`='Head' AND `Category Root Key`=%d  AND B.`Category Key`!=%d AND `Subject`=%s AND `Subject Key`=%d",
                $this->data['Category Root Key'], $this->id, prepare_mysql($this->data['Category Subject']), $subject_key
            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {

                    $other_category         = get_object('Category', $row['Category Key']);
                    $other_category->editor = $this->editor;
                    $other_category->disassociate_subject($subject_key, $options);
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }


            return $this->associate_subject($subject_key, true, $other_value);


        }

    }


    function create_timeseries($data, $fork_key = false) {
        switch ($this->data['Category Scope']) {
            case('Part'):
                $this->create_part_timeseries($data, $fork_key);
                break;
            case('Product'):
                $this->create_product_timeseries($data, $fork_key);
                break;
            case('Invoice'):
                // todo
                $this->create_invoice_timeseries($data, $fork_key);
                break;
            case('Supplier'):
                // todo
                $this->create_supplier_timeseries($data, $fork_key);
                break;

        }

    }

    function update_sales_from_invoices($interval, $this_year = true, $last_year = true) {
        switch ($this->data['Category Scope']) {
            case('Part'):
                $this->update_part_category_sales($interval, $this_year, $last_year);
                break;
            case('Product'):
                $this->update_product_category_sales($interval, $this_year, $last_year);
                break;
            case('Invoice'):
                $this->update_invoice_category_sales($interval, $this_year, $last_year);
                break;
            case('Supplier'):
                $this->update_supplier_category_sales($interval, $this_year, $last_year);
                break;

        }

    }

    function update_previous_years_data() {
        switch ($this->data['Category Scope']) {
            case('Part'):
                $this->update_part_category_previous_years_data();
                break;
            case('Product'):
                $this->update_product_category_previous_years_data();
                break;
            case('Invoice'):
                $this->update_invoice_category_previous_years_data();
                break;
            case('Supplier'):
                $this->update_supplier_category_previous_years_data();
                break;

        }

    }

    function update_previous_quarters_data() {
        switch ($this->data['Category Scope']) {
            case('Part'):
                $this->update_part_category_previous_quarters_data();
                break;
            case('Product'):
                $this->update_product_category_previous_quarters_data();
                break;
            case('Invoice'):
                $this->update_invoice_category_previous_quarters_data();
                break;
            case('Supplier'):
                $this->update_supplier_category_previous_quarters_data();
                break;

        }

    }


    function get_field_label($field) {


        switch ($field) {


            case 'Category Code':
                $label = _('code');
                break;
            case 'Category Label':
                $label = _('label');
                break;
            case 'Product Category Description':
                $label = _('description');
                break;
            case 'Category Webpage Name':
                $label = _('webpage name');
                break;
            case 'Category Webpage Browser Title':
                $label = _('browser title');
                break;
            case 'Webpage Meta Description':
                $label = _('meta description');
                break;
            case 'Part Category Status':
            case 'Part Category Status Including Parts':
                $label = _('status');
                break;

            default:
                $label = $field;

        }

        return $label;

    }


    function update_webpages($change_type) {


        if ($this->data['Category Scope'] == 'Product') {

            /**
             * @var $webpage \Page
             */
            $webpage = get_object('Webpage', $this->get('Product Category Webpage Key'));
            if ($webpage->id) {
                $webpage->reindex();


                switch ($change_type) {
                    case 'main_image':
                        $account = get_object('Account', 1);
                        require_once 'utils/new_fork.php';
                        new_housekeeping_fork(
                            'au_reindex_webpages', array(
                            'type'          => 'reindex_webpages_items',
                            'webpages_keys' => $webpage->get_upstream_webpage_keys(),
                        ), $account->get('Account Code'), $this->db
                        );
                        break;
                    default:

                        $webpages_to_reindex = array_merge(
                            $webpage->get_upstream_webpage_keys(), $webpage->get_downstream_webpage_keys()
                        );

                        $date = gmdate('Y-m-d H:i:s');
                        foreach ($webpages_to_reindex as $webpage_to_reindex_key) {
                            if ($webpage_to_reindex_key > 0) {


                                $sql = sprintf(
                                    'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`,`Stack Metadata`) values (%s,%s,%s,%d,%s) 
                                ON DUPLICATE KEY UPDATE `Stack Last Update Date`=%s , `Stack Metadata`=%s, `Stack Counter`=`Stack Counter`+1 ', prepare_mysql($date), prepare_mysql($date), prepare_mysql('reindex_webpage'), $webpage_to_reindex_key,
                                    prepare_mysql('from_cat'.$change_type), prepare_mysql($date), prepare_mysql('from_cat'.$change_type)

                                );
                                $this->db->exec($sql);
                            }
                        }
                }
            }

        }


    }


    function properties($key) {
        return (isset($this->properties[$key]) ? $this->properties[$key] : '');
    }


    function get_children_with_subject($subject_key) {

        $children = [];
        $sql      = "select B.`Category Key`,`Category Code`,`Category Label` from `Category Bridge` B left join `Category Dimension` C on (B.`Category Key`=C.`Category Key`)  where `Category Parent Key`=? and `Subject Key`=? ";
        $stmt     = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id,
                $subject_key
            )
        );
        while ($row = $stmt->fetch()) {
            $children[$row['Category Key']] = [
                $row['Category Key'],
                $row['Category Code'],
                $row['Category Label']

            ];
        }

        return $children;

    }


}
