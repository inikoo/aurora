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


    function Category($a1, $a2 = false, $a3 = false, $_db = false) {


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
            case 'rootkey_code':
                $sql = sprintf(
                    "SELECT * FROM `Category Dimension` WHERE `Category Root Key`=%d AND `Category Code`=%s ", $tag, prepare_mysql($tag2)
                );
                break;
            case 'subject_code':
                $sql = sprintf(
                    "SELECT * FROM `Category Dimension` WHERE `Category Subject`=%s AND `Category Code`=%s ", prepare_mysql($tag), prepare_mysql($tag2)
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

            } elseif ($this->data['Category Scope'] == 'Location') {


                $this->subject_table_name = 'Location Category';
                $sql                      = sprintf(
                    "SELECT * FROM `Location Category Dimension` WHERE `Part Location Key`=%d", $this->id
                );
                if ($result2 = $this->db->query($sql)) {
                    if ($row = $result2->fetch()) {
                        $this->data = array_merge($this->data, $row);
                    }
                } else {
                    print_r($error_info = $this->db->errorInfo());
                    exit;
                }

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
            $parent_category            = new Category(
                $data['Category Parent Key']
            );
            $data['Category Store Key'] = $parent_category->data['Category Store Key'];
        }

        $fields = array();


        $sql = sprintf(
            "SELECT `Category Key` FROM `Category Dimension` WHERE  `Category Parent Key`=%d AND `Category Store Key`=%d AND `Category Code`=%s ", $data['Category Parent Key'],
            $data['Category Store Key'], prepare_mysql($data['Category Code'])

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

        //print_r($data);

        // $data=array('`Category Code`'=>$data['Category Code']);

        $nodes = new Nodes('`Category Dimension`');


        $nodes->add_new($data['Category Parent Key'], $data);


        $node_id = $nodes->id;

        unset($nodes);


        if ($node_id) {

            $this->get_data('id', $node_id);
            //print_r($this->data);


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


            if ($this->data['Category Scope'] == 'Invoice') {
                $sql = sprintf(
                    "INSERT INTO `Invoice Category Dimension` (`Invoice Category Key`,`Invoice Category Store Key`) VALUES (%d,%d)", $this->id, $this->data['Category Store Key']
                );
                $this->db->exec($sql);
            } elseif ($this->data['Category Scope'] == 'Supplier') {
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

            } elseif ($this->data['Category Scope'] == 'Location') {
                $created_msg = _("Location's category created");

                $sql = sprintf(
                    "INSERT INTO `Location Category Dimension` (`Location Category Key`,`Location Category Warehouse Key`) VALUES (%d,%d)", $this->id, $this->data['Category Warehouse Key']
                );
                $this->db->exec($sql);
            } elseif ($this->data['Category Scope'] == 'Product') {
                include_once 'class.Store.php';
                $store = new Store($this->data['Category Store Key']);

                $sql = sprintf(
                    "INSERT INTO `Product Category Dimension` (`Product Category Key`,`Product Category Store Key`,`Product Category Currency Code`,`Product Category Valid From`) VALUES (%d,%d,%s,%s)",
                    $this->id, $store->id, prepare_mysql($store->get('Store Currency Code')), prepare_mysql(gmdate('Y-m-d H:i:s'))
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
                include_once 'class.Store.php';
                $store = new Store($this->data['Category Store Key']);

                $sql = sprintf(
                    "INSERT INTO `Invoice Category Dimension` (`Invoice Category Key`,`Invoice Category Store Key`,`Invoice Category Currency Code`,`Invoice Category Valid From`) VALUES (%d,%d,%s,%s)",
                    $this->id, $store->id, prepare_mysql($store->get('Store Currency Code')), prepare_mysql(gmdate('Y-m-d H:i:s'))
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


            $history_data = array(
                'Action'           => 'created',
                'History Abstract' => $created_msg,
                'History Details'  => ''
            );
            $this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->id);


            $this->update_branch_tree();
            $this->update_number_of_subjects();
            $parent_category = new Category($data['Category Parent Key']);
            if ($parent_category->id) {
                $parent_category->update_children_data();
            }


        }

    }

    function update_branch_tree() {

        //'Product','Supplier','Customer','Family','Invoice','Part'

        switch ($this->data['Category Subject']) {
            case('Part'):
                $link = 'part_category.php';
                break;
            case('Customer'):
                $link = 'customer_category.php';
                break;
            case('Invoice'):
                $link = 'invoice_category.php';
                break;
            case('Supplier'):
                $link = 'supplier_category.php';
                break;
            case('Product'):
                $link = 'product_category.php';
                break;
            case('Family'):
                $link = 'family_category.php';
                break;
            default:
                $link = 'category.php';
        }

        $category_keys = preg_split(
            '/\>/', preg_replace('/\>$/', '', $this->data['Category Position'])
        );

        $sql = sprintf(
            "SELECT `Category Code`,`Category Key` FROM `Category Dimension` WHERE `Category Key` IN (%s)", join(',', $category_keys)
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $category_data[$row['Category Key']] = $row['Category Code'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        $xhtml_tree = '';
        $plain_tree = '';


        foreach ($category_keys as $key) {
            if (array_key_exists($key, $category_data)) {
                $xhtml_tree .= sprintf(
                    " <a href='%s?id=%d'>%s</a> &rarr;", $link, $key, $category_data[$key]
                );
                $plain_tree .= sprintf(" %s >", $category_data[$key]);
            }
        }
        $xhtml_tree = preg_replace('/\s*\&rarr\;$/', '', $xhtml_tree);
        $plain_tree = preg_replace('/\s*\>$/', '', $plain_tree);

        $this->data['Category XHTML Branch Tree'] = $xhtml_tree;
        $this->data['Category Plain Branch Tree'] = $plain_tree;

        $sql = sprintf(
            "UPDATE `Category Dimension` SET `Category XHTML Branch Tree`=%s ,`Category Plain Branch Tree`=%s WHERE `Category Key`=%d ", prepare_mysql($this->data['Category XHTML Branch Tree']),
            prepare_mysql($this->data['Category Plain Branch Tree']), $this->id
        );
        $this->db->exec($sql);


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
                "SELECT count(*) AS num, `Part Status` FROM `Category Bridge` B LEFT JOIN `Part Dimension` P ON (B.`Subject Key`=P.`Part SKU`) WHERE B.`Category Key`=%d GROUP BY `Part Status` ",
                $this->id
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
            "UPDATE `Category Dimension` SET `Category Number Subjects`=%d ,`Category Number Active Subjects`=%d ,`Category Number No Active Subjects`=%d WHERE `Category Key`=%d ", $num, $num_active,
            $num_no_active, $this->id
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
                    $img = '/image_root.php?size=small&id='.$image_key;
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
                    case 'Department Category Code':


                        include_once 'class.Category.php';
                        if ($this->get(
                                'Product Category Department Category Key'
                            ) > 0
                        ) {


                            $department = new Category(
                                $this->get(
                                    'Product Category Department Category Key'
                                )
                            );
                            if ($department->id) {
                                return $department->get('Code');
                            }
                        }

                        return '';

                        break;


                    case 'Category Webpage Template':
                        // todo migrate to new webpage and webpage version classes


                        if ($this->webpage->get('Page Store Content Display Type') == 'Source') {
                            return 'blank';
                        } else {
                            return $this->webpage->get('Page Store Content Template Filename');
                        }

                    case 'Webpage Template':
                        // todo migrate to new webpage and webpage version classes

                        if (!is_object($this->webpage)) {
                            $this->get_webpage();
                        }

                        if ($this->webpage->get('Page Store Content Display Type') == 'Source') {
                            return _('White canvas');
                        } else {
                            switch ($this->webpage->get('Page Store Content Template Filename')) {
                                case 'family_buttons':
                                    return '<span class="italic discreet" >Old products showcase (unsupported)</span>';
                                    break;
                                case 'products_showcase':
                                    return _("Products showcase");
                                    break;
                                case 'categories_showcase':
                                    return _("Categories showcase");
                                    break;
                                default:
                                    return $this->webpage->get('Page Store Content Template Filename');
                                    break;
                            }
                        }


                        break;
                    case 'Webpage Related Products':

                        $related_products_data = $this->webpage->get_related_products_data();
                        $related_products      = '';


                        foreach ($related_products_data['links'] as $link) {
                            $related_products .= $link['code'].', ';
                        }

                        $related_products = preg_replace('/, $/', '', $related_products);

                        return $related_products;


                        break;
                    case 'Webpage See Also':

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
                    case 'Website Node Parent Key':

                        return $this->webpage->get('Found In Page Key');

                        break;
                    case 'Category Website Node Parent Key':

                        return $this->webpage->get('Page Found In Page Key');

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
                    ) or $key == 'Current Stock'
                ) {

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
                    ) or $key == 'Current Stock'
                ) {

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
            case('Location'):

                $sql = sprintf(
                    "SELECT count(*) AS num FROM `Location Dimension` WHERE `Location Warehouse Key`=%d", $this->data['Category Warehouse Key']
                );
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

                $sql = sprintf("SELECT count(*) AS num FROM `Product Dimension` WHERE `Product Store Key`=%d AND `Product Record Type`='Normal'", $this->data['Category Store Key']);
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
            "SELECT COUNT(*)  AS num  FROM `Category Dimension` WHERE `Category Parent Key`=%d AND `Category Subject`=%s AND `Is Category Field Other`='Yes' ", $this->id,
            prepare_mysql($this->data['Category Subject'])
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
                "SELECT `Category Position`  FROM `Category Dimension` WHERE `Category Position`	RLIKE '^%s[0-9]+>$' AND `Category Subject`=%s ", $this->data['Category Position'],
                prepare_mysql($this->data['Category Subject'])
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
            "UPDATE `Category Dimension` SET `Category Children`=%d ,`Category Children Deep`=%d , `Category Children Other`=%s WHERE `Category Key`=%d ", $number_of_children, $max_deep,
            prepare_mysql($has_children_other), $this->id
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


        $branch_type = $this->data['Category Branch Type'];

        $data['Category Scope'] = $this->data['Category Scope'];

        $data['Category Subject'] = $this->data['Category Subject'];
        //  $data['Category Subject Key']=$this->data['Category Subject Key'];

        $data['Category Warehouse Key'] = $this->data['Category Warehouse Key'];


        if ($this->data['Category Store Key'] != 0 and array_key_exists(
                'Category Store Key', $data
            )
        ) {
            $data['Category Store Key'] = $this->data['Category Store Key'];
        }


        $data['Category Branch Type']          = 'Head';
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


        $subcategory = new Category('find create', $data);


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
            //-----Migration ---

            if ($this->get('Category Scope') == 'Product') {
                include_once 'class.Family.php';
                include_once 'class.Store.php';
                include_once 'class.Department.php';
                $store = new Store($this->get('Category Store Key'));


                if ($this->get('Category Root Key') == $store->get('Store Family Category Key')) {


                    $code = $subcategory->get('Category Code');


                    $sql = sprintf('SELECT `Product Department Key` FROM `Product Department Dimension` WHERE `Product Department Store Key`=%s ', $store->id);

                    $dept_key = 0;
                    if ($result = $this->db->query($sql)) {
                        if ($row = $result->fetch()) {
                            $dept_key = $row['Product Department Key'];
                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        exit;
                    }

                    include_once 'class.Account.php';
                    $account = new Account($this->db);

                    if (function_exists('mysql_query') and $account->get('Account Code') == 'AW') {

                        $fam_data = array(

                            'Product Family Code'                   => $code,
                            'Product Family Name'                   => $code,
                            'Product Family Main Department Key'    => $dept_key,
                            'Product Family Store Key'              => $store->id,
                            'Product Family Special Characteristic' => $code
                        );

                        //print_r($fam_data);


                        $family = new Family('find', $fam_data, 'create');

                    }

                    $account = new Account($this->db);

                    $sql = sprintf(
                        'SELECT `Image Subject Image Key` FROM `Image Subject Bridge` LEFT JOIN `Category Dimension` ON (`Image Subject Object Key`=`Category Key`)  WHERE `Category Subject`="Part" AND `Category Code`=%s  AND `Category Root Key`=%d AND `Image Subject Object`="Category" ',
                        prepare_mysql($subcategory->get('Category Code')), $account->get('Account Part Family Category Key')
                    );


                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {
                            //  print_r($row);
                            $subcategory->link_image($row['Image Subject Image Key']);


                        }
                    } else {
                        print_r($error_info = $this->db->errorInfo());
                        exit;


                    }


                }


                $subcategory->update_product_category_products_data();


                //    if ($subcategory->get('Product Category Public') == 'Yes') {
                //      foreach ($store->get_websites('objects') as $website) {
                //        $website->create_category_webpage($subcategory->id);
                //  }

                // }


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

    function get_period($period, $key) {

        return $this->get($period.' '.$key);
    }

    function delete() {

        if (!$this->id) {
            return;
        }

        $this->deleted = false;

        $sql_new_deleted_category = sprintf(
            "INSERT INTO `Category Deleted Dimension` (`Category Deleted Key`, `Category Deleted Branch Type`, `Category Deleted Store Key`, `Category Deleted Warehouse Key`, `Category Deleted XHTML Branch Tree`, `Category Deleted Plain Branch Tree`,
`Category Deleted Deep`, `Category Deleted Children`, `Category Deleted Code`, `Category Deleted Label`, `Category Deleted Subject`,  `Category Deleted Number Subjects`,`Category Deleted Date`)
VALUES (%d,%s, %d, %d, %s,%s, %d, %d, %s, %s, %s,%d,NOW())", $this->id,


            prepare_mysql($this->data['Category Branch Type']), $this->data['Category Store Key'], $this->data['Category Warehouse Key'], prepare_mysql($this->data['Category XHTML Branch Tree']),
            prepare_mysql($this->data['Category Plain Branch Tree']), $this->data['Category Deep'], $this->data['Category Children'], prepare_mysql($this->data['Category Code']),
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
                'DELETE FROM `Invoice Category Dimension` WHERE `Category Key`=%d', $this->id
            );
            $this->db->exec($sql);
        } elseif ($this->data['Category Scope'] == 'Supplier') {
            $sql = sprintf(
                'DELETE FROM `Supplier Category Dimension` WHERE `Category Key`=%d', $this->id
            );
            $this->db->exec($sql);
        } elseif ($this->data['Category Scope'] == 'Part') {
            $sql = sprintf(
                'DELETE FROM `Part Category Dimension`  WHERE `Category Key`=%d', $this->id
            );
            $this->db->exec($sql);
        } elseif ($this->data['Category Scope'] == 'Location') {
            $sql = sprintf(
                'DELETE FROM `Location Category Dimension`  WHERE `Category Key`=%d', $this->id
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
                'DELETE FROM `Product Category Dc Data`  WHERE `Product Category Key`=%d', $this->id
            );
            $this->db->exec($sql);
        }


        $this->db->exec($sql_new_deleted_category);


        $sql = sprintf(
            'DELETE FROM `Category Dimension` WHERE `Category Key`=%d', $this->id
        );
        $this->db->exec($sql);

        foreach ($parent_keys as $parent_key) {
            $parent_category = new Category($parent_key);
            if ($parent_category->id) {
                $parent_category->update_children_data();

                if ($is_category_other == 'Yes') {
                    $parent_category->data['Category Children Other'] = 'No';
                    $sql                                              = sprintf(
                        "UPDATE `Category Dimension` SET `Category Children Other`=%s WHERE `Category Key`=%d", prepare_mysql(
                        $parent_category->data['Category Children Other']
                    ), $parent_category->id
                    );
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

    }

    function get_parent_keys() {
        $parent_keys        = array();
        $category_tree_keys = preg_split(
            '/\>/', preg_replace('/\>$/', '', $this->data['Category Position'])
        );
        //print_r($this->data['Category Position']);
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
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
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

                    $head_category = new Category($row['Category Head Key']);
                    if ($head_category->disassociate_subject(
                        $subject_key, $options
                    )
                    ) {
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
                    $abstract = _('Part').': <a href="part.php?sku='.$part->sku.'">SKU'.sprintf('%05d', $part->sku).'</a> '._(
                            'disassociated with category'
                        ).sprintf(
                            ' <a href="part_category.php?id=%d">%s</a>', $this->id, $this->data['Category Code']
                        );
                    $details  = _('Part').': <a href="part.php?sku='.$part->sku.'">SKU'.sprintf('%05d', $part->sku).'</a> ('.$part->data['Part XHTML Description'].') '._(
                            'disassociated with category'
                        ).sprintf(
                            ' <a href="part_category.php?id=%d">%s</a>', $this->id, $this->data['Category Code']
                        ).' ('.$this->data['Category Label'].')';
                    break;
                case('Location'):
                    include_once 'class.Location.php';

                    $location = new Location($subject_key);
                    $abstract = _('Part').': <a href="part.php?sku='.$part->sku.'">SKU'.sprintf('%05d', $part->sku).'</a> '._(
                            'disassociated with category'
                        ).sprintf(
                            ' <a href="part_category.php?id=%d">%s</a>', $this->id, $this->data['Category Code']
                        );
                    $details  = _('Part').': <a href="part.php?sku='.$part->sku.'">SKU'.sprintf('%05d', $part->sku).'</a> ('.$part->data['Part XHTML Description'].') '._(
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
                    $details  = _('Customer').': <a href="customer.php?id='.$customer->id.'">'.$customer->data['Customer Name'].'</a> ('.$customer->data['Customer Main Location'].') '._(
                            'associated with category'
                        ).sprintf(
                            ' <a href="part_category.php?id=%d">%s</a>', $this->id, $this->data['Category Code']
                        ).' ('.$this->data['Category Label'].')';
                    break;
                case('Product'):
                    include_once 'class.Product.php';

                    $product = new Product($subject_key);

                    $abstract = sprintf(
                        _('Product %s disassociated from category %s'), $product->get('Code'), $this->get('Code')
                    );
                    $details  = '';
                    //$abstract=_('Product').': <a href="product.php?pid='.$product->pid.'">'.$product->data['Product Code'].'</a> '._('disassociated with category').sprintf(' <a href="part_category.php?id=%d">%s</a>', $this->id, $this->data['Category Code']);
                    //$details=_('Product').': <a href="product.php?pid='.$product->pid.'">'.$product->data['Product Code'].'</a> ('.$product->data['Product Name'].') '._('associated with category').sprintf(' <a href="part_category.php?id=%d">%s</a>', $this->id, $this->data['Category Code']).' ('.$this->data['Category Label'].')';


                    //  Migration  ----
                    include_once 'class.Store.php';
                    $store = new Store($this->get('Category Store Key'));
                    if ($this->get('Category Root Key') == $store->get('Store Family Category Key')) {


                        $sql = sprintf(
                            "SELECT * FROM `Product Family Dimension` WHERE `Product Family Store Key`=%d AND `Product Family Code`=%s", $this->get('Category Store Key'),
                            prepare_mysql($this->get('Category Code'))
                        );


                        if ($result = $this->db->query($sql)) {
                            if ($row = $result->fetch()) {

                                $sql = sprintf(
                                    "update `Product Dimension`set `Product Family Key`=0, `Product Family Code`='', `Product Family Name`='',`Product Main Department Key`=0,
                     `Product Main Department Code`='',
                     `Product Main Department Name`=''
                     where `Product ID`=%d",

                                    $subject_key
                                );


                                //print $sql;
                                $this->db->exec($sql);


                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            exit;
                        }


                        if (!preg_match('/skip_direct_update/', $options)) {
                            $product->update(
                                array(
                                    'Direct Product Family Category Key' => ''
                                ), 'no_history'
                            );
                        }


                    }

                    if ($this->get('Category Root Key') == $store->get(
                            'Store Department Category Key'
                        )
                    ) {

                        // DEpartment


                        $sql = sprintf(
                            "SELECT * FROM `Product Department Dimension` WHERE `Product Department Store Key`=%d AND `Product Department Code`=%s", $this->get('Category Store Key'),
                            prepare_mysql($this->get('Category Code'))
                        );


                        if ($result = $this->db->query($sql)) {
                            if ($department = $result->fetch()) {
                                $department_key = $department['Product Department Key'];
                            } else {
                                $department_key = false;
                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            exit;
                        }


                        $family = new Category($subject_key);


                        $sql = sprintf(
                            "SELECT * FROM `Product Family Dimension` WHERE `Product Family Store Key`=%d AND `Product Family Code`=%s", $family->get('Category Store Key'),
                            prepare_mysql($family->get('Category Code'))
                        );


                        if ($result = $this->db->query($sql)) {
                            if ($family = $result->fetch()) {
                                $family_key = $department['Product Department Key'];
                            } else {
                                $family_key = false;
                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            exit;
                        }


                        if ($family_key and $department_key) {


                            $sql = sprintf(
                                "UPDATE `Product Family Dimension` SET `Product Family Main Department Key`=0, `Product Family Main Department Code`='', `Product Family Main Department Name`='' WHERE `Product Family Key`=%d",

                                $family_key
                            );


                            $this->db->exec($sql);

                            $sql = sprintf(
                                "UPDATE `Product Dimension` SET `Product Main Department Key`=0, `Product Main Department Code`='', `Product Main Department Name`='' WHERE `Product Family Key`=%d",

                                $family_key
                            );
                            $this->db->exec($sql);

                        }


                    }


                    //--------


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

    function sub_category_selected_by_subject($subject_key) {
        $sub_category_keys_selected = array();
        $sql                        = sprintf(
            "SELECT C.`Category Key` FROM `Category Bridge` B LEFT JOIN `Category Dimension` C ON (C.`Category Key`=B.`Category Key`) WHERE `Category Subject`=%s AND `Subject Key`=%d AND `Category Parent Key`=%d",
            prepare_mysql($this->data['Category Subject']), $subject_key, $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $sub_category_keys_selected[$row['Category Key']] = $row['Category Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $sub_category_keys_selected;
    }

    function get_children_objects_new_subject() {
        $sql = sprintf(
            "SELECT `Category Key`   FROM `Category Dimension` WHERE `Category Parent Key`=%d AND `Category Show Subject User Interface`='Yes' ORDER BY `Category Code` ", $this->id
        );


        $children_keys = array();

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $children_keys[$row['Category Key']] = new Category(
                    $row['Category Key']
                );
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $children_keys;

    }

    function get_children_objects_public_edit() {
        $sql = sprintf(
            "SELECT `Category Key`   FROM `Category Dimension` WHERE `Category Parent Key`=%d AND `Category Show Public Edit`='Yes' ORDER BY `Category Code` ", $this->id
        );
        //  print $sql;

        $children_keys = array();


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $children_keys[$row['Category Key']] = new Category(
                    $row['Category Key']
                );
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $children_keys;

    }

    function get_children_objects_public_new_subject() {
        $sql = sprintf(
            "SELECT `Category Key`   FROM `Category Dimension` WHERE `Category Parent Key`=%d AND `Category Show Public New Subject`='Yes' ORDER BY `Category Code` ", $this->id
        );

        $children_keys = array();

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $children_keys[$row['Category Key']] = new Category(
                    $row['Category Key']
                );
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $children_keys;

    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {


        // print_r($this->base_data('Product Category Data'));
        // exit;




        if (array_key_exists($field, $this->base_data())) {


            if ($field == 'Category Code') {

                // Migration -----
                if ($field == 'Category Code') {

                    $sql = sprintf(
                        'UPDATE `Product Family Dimension` SET `Product Family Code`=%s WHERE `Product Family Store Key`=%d AND `Product Family Code`=%s', prepare_mysql($value),
                        $this->get('Category Store Key'), prepare_mysql($this->get('Category Code'))
                    );
                    $this->db->exec($sql);

                }
                //-----------


                $this->update_field($field, $value, $options);
                $this->update_branch_tree();

                $this->load_all_descendants_keys();

                foreach ($this->all_descendants_keys as $descendant_key) {
                    $descendant = new Category($descendant_key);
                    $descendant->update_branch_tree();
                }
            } elseif ($field == 'Category Label') {

                // Migration -----
                $this->update_field($field, $value, $options);

                $sql = sprintf(
                    'UPDATE `Product Family Dimension` SET `Product Family Name`=%s WHERE `Product Family Store Key`=%d AND `Product Family Code`=%s', prepare_mysql($value),
                    $this->get('Category Store Key'), prepare_mysql($this->get('Category Code'))
                );
                $this->db->exec($sql);
                //-------------


            } elseif ($value != $this->data[$field]) {

                $this->update_field($field, $value, $options);

            }
        }
        elseif (array_key_exists($field, $this->base_data('Product Category Dimension'))) {




            switch ($field) {

                case 'Product Category Department Category Key':


                    if ($value) {

                        include_once 'class.Category.php';

                        include_once 'class.Store.php';
                        $store = new Store($this->get('Category Store Key'));


                        if ($this->data['Product Category Department Category Key'] != $value) {

                            $old_parent_category = new Category($this->data['Product Category Department Category Key']);

                            $new_parent_category = new Category($value);


                            $new_parent_category->associate_subject($this->id, false, '', 'skip_direct_update');


                            $old_parent_category->update_product_category_products_data();
                            $new_parent_category->update_product_category_products_data();


                            $webpage = $old_parent_category->get_webpage();
                            if ($webpage->id) {
                                $webpage->reindex_items();
                                if ($webpage->updated) {
                                    $webpage->publish();
                                }
                            }
                            $sql = sprintf(
                                'SELECT `Category Webpage Index Webpage Key` FROM `Category Webpage Index` WHERE `Category Webpage Index Category Key`=%d  GROUP BY `Category Webpage Index Webpage Key` ',
                                $old_parent_category->id
                            );

                            if ($result = $this->db->query($sql)) {
                                foreach ($result as $row) {
                                    $webpage = new Page($row['Category Webpage Index Webpage Key']);
                                    $webpage->reindex_items();
                                    if ($webpage->updated) {
                                        $webpage->publish();
                                    }
                                }
                            } else {
                                print_r($error_info = $this->db->errorInfo());
                                print "$sql\n";
                                exit;
                            }


                            $webpage = $new_parent_category->get_webpage();

                            if ($webpage->id) {


                                $webpage->reindex_items();

                                if ($webpage->updated) {
                                    $webpage->publish();
                                }
                            }

                            $sql = sprintf(
                                'SELECT `Category Webpage Index Webpage Key` FROM `Category Webpage Index` WHERE `Category Webpage Index Category Key`=%d  GROUP BY `Category Webpage Index Webpage Key` ',
                                $new_parent_category->id
                            );


                            if ($result = $this->db->query($sql)) {
                                foreach ($result as $row) {
                                    $webpage = new Page($row['Category Webpage Index Webpage Key']);
                                    $webpage->reindex_items();
                                    if ($webpage->updated) {
                                        $webpage->publish();
                                    }
                                }
                            } else {
                                print_r($error_info = $this->db->errorInfo());
                                print "$sql\n";
                                exit;
                            }


                        }


                    } else {
                        if ($this->data['Product Category Department Category Key'] != '') {


                            $category = new Category($this->data['Product Category Department Category Key']);

                            if ($category->id) {
                                $category->disassociate_subject($this->id);
                                $category->update_product_category_products_data();

                            }

                        }

                    }


                    //print "$field, $value";

                    //$this->update_subject_field($field, $value, 'no_history');
                    $this->update_table_field($field, $value, 'no_history', 'Product Category', 'Product Category Dimension', $this->id);

                    $categories = '';
                    foreach ($this->get_category_data() as $item) {
                        $categories .= sprintf(
                            '<li><span class="button" onclick="change_view(\'category/%d\')" title="%s">%s</span></li>', $item['category_key'], $item['label'], $item['code']

                        );

                    }
                    $this->update_metadata = array(
                        'class_html' => array(
                            'Categories' => $categories,

                        )
                    );


                    break;


                case 'Product Category Description':


                    $value = html_entity_decode($value);
                    //$this->update_subject_field($field, $value, $options);


                    $this->update_table_field(
                        $field, $value, $options, 'Product Category', 'Product Category Dimension', $this->id
                    );


                    // Migration -----
                    if ($this->get('Category Subject') == 'Product') {

                        $sql = sprintf(
                            'UPDATE `Product Family Dimension` SET `Product Family Description`=%s WHERE `Product Family Store Key`=%d AND `Product Family Code`=%s', prepare_mysql($value),
                            $this->get('Category Store Key'), prepare_mysql($this->get('Category Code'))
                        );

                        //print $sql;
                        $this->db->exec($sql);

                    }


                    //-----------


                    break;


                case 'Product Category Public':
                    include_once 'class.Product.php';
                    include_once 'class.Page.php';


                    $this->update_table_field($field, $value, $options, 'Product Category', 'Product Category Dimension', $this->id);
                    if ($this->updated) {
                        $webpage = $this->get_webpage();
                        if ($webpage->id) {
                            $webpage->reindex_items();
                            if ($webpage->updated) {
                                $webpage->publish();
                            }
                        }
                        $sql = sprintf(
                            'SELECT `Category Webpage Index Webpage Key` FROM `Category Webpage Index` WHERE `Category Webpage Index Category Key`=%d  GROUP BY `Category Webpage Index Webpage Key` ',
                            $this->id
                        );


                        if ($result = $this->db->query($sql)) {
                            foreach ($result as $row) {
                                $webpage = new Page($row['Category Webpage Index Webpage Key']);
                                $webpage->reindex_items();
                                if ($webpage->updated) {
                                    $webpage->publish();
                                }
                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            print "$sql\n";
                            exit;
                        }


                    }


                    $this->get_webpage();
                    if ($value == 'No') {


                        if ($this->webpage->id) {
                            $this->webpage->update(array('Page State' => 'Offline'));
                        }

                        $sql = sprintf(
                            'SELECT `Category Webpage Index Webpage Key`,`Category Webpage Index Key` FROM `Category Webpage Index` WHERE `Category Webpage Index Category Key`=%d  ', $this->id
                        );


                        if ($result = $this->db->query($sql)) {
                            foreach ($result as $row) {
                                $webpage = new Page($row['Category Webpage Index Webpage Key']);

                                $sql = sprintf('DELETE FROM `Category Webpage Index` WHERE `Category Webpage Index Key`=%d', $row['Category Webpage Index Key']);
                                $this->db->exec($sql);

                                $webpage->reindex_items();

                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            print "$sql\n";
                            exit;
                        }


                        $sql = sprintf(
                            'SELECT `Category Webpage Index Webpage Key`,`Category Webpage Index Key` FROM `Category Webpage Index` WHERE `Category Webpage Index Parent Category Key`=%d   GROUP BY `Category Webpage Index Webpage Key`',
                            $this->id
                        );


                        if ($result = $this->db->query($sql)) {
                            foreach ($result as $row) {
                                $webpage = new Page($row['Category Webpage Index Webpage Key']);

                                $sql = sprintf('DELETE FROM `Category Webpage Index` WHERE `Category Webpage Index Webpage Key`=%d', $row['Category Webpage Index Webpage Key']);
                                $this->db->exec($sql);

                                $webpage->update(array('Page State' => 'Offline'));

                            }
                        } else {
                            print_r($error_info = $this->db->errorInfo());
                            print "$sql\n";
                            exit;
                        }


                        foreach ($this->get_children_keys() as $children_key) {
                            $subcategory = new Category($children_key);
                            $subcategory->update(array('Product Category Public' => $value), $options);
                        }


                        $sql = sprintf('SELECT `Subject Key`,`Subject` FROM `Category Bridge` WHERE `Category Key`=%d ', $this->id);


                        if ($result = $this->db->query($sql)) {
                            foreach ($result as $row) {


                                if ($row['Subject'] == 'Product') {
                                    $product = new Product($row['Subject Key']);
                                    //  print_r($row);
                                    $product->update(array('Product Web Configuration' => 'Offline'), $options);
                                } else {
                                    if ($row['Subject'] == 'Category') {
                                        $subcategory = new Category($row['Subject Key']);
                                        $subcategory->update(array('Product Category Public' => $value), $options);
                                    }
                                }


                            }
                        }


                    } elseif ($value == 'Yes') {


                        if ($this->webpage->id) {
                            $this->webpage->update(array('Page State' => 'Online'));


                            $sql = sprintf(
                                "SELECT `Category Key`  FROM `Category Bridge` WHERE `Subject Key`=%d AND `Subject`='Category' GROUP BY `Category Key` ", $this->id

                            );
                            if ($result = $this->db->query($sql)) {
                                foreach ($result as $row) {
                                    $parent = new Category($row['Category Key']);

                                    // print_r($parent->get('Code'));
                                    $parent->create_stack_index(true);

                                }
                            }


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


                case 'Part Category Status Including Parts':
                    include_once 'class.Part.php';
                    $old_formatted_value = $this->get('Status');

                    if ($value == 'Discontinuing') {

                        $sql     = sprintf(
                            "SELECT P.`Part SKU` FROM  `Part Dimension` P LEFT JOIN `Category Bridge` B ON (P.`Part SKU`=B.`Subject Key`)  WHERE B.`Category Key`=%d AND `Subject`='Part' AND P.`Part Status` IN ('In Use','In Process') ",
                            $this->id
                        );
                        $counter = 0;
                        if ($result = $this->db->query($sql)) {
                            foreach ($result as $row) {

                                $part = new Part($row['Part SKU']);
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
                            "SELECT P.`Part SKU` FROM  `Part Dimension` P LEFT JOIN `Category Bridge` B ON (P.`Part SKU`=B.`Subject Key`)  WHERE B.`Category Key`=%d AND `Subject`='Part' AND P.`Part Status` IN ('Discontinuing') ",
                            $this->id
                        );

                        if ($result = $this->db->query($sql)) {
                            foreach ($result as $row) {

                                $part = new Part($row['Part SKU']);
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


                case 'Webpage Template':

                    if (!is_object($this->webpage)) {
                        $this->get_webpage();
                    }


                    if ($value == 'blank') {
                        $this->webpage->update(array('Page Store Content Display Type' => 'Source'), $options);

                    } else {


                        $this->webpage->update(
                            array(
                                'Page Store Content Display Type'      => 'Template',
                                'Page Store Content Template Filename' => $value
                            ), $options
                        );


                    }
                    $this->webpage->update_version();
                    $this->webpage->publish();
                    $this->updated = $this->webpage->updated;

                    break;

                case 'Category Webpage Meta Description':
                case 'Webpage Meta Description':
                    if (!is_object($this->webpage)) {
                        $this->get_webpage();
                    }




                    $this->webpage->update(
                        array(
                            'Page Store Description' => $value
                        ), 'no_history'
                    );


                    $this->webpage->update(
                        array(
                            'Webpage Meta Description' => $value
                        ), $options
                    );


                    $this->updated = $this->webpage->updated;

                    break;

                case 'Webpage See Also':
                    if (!is_object($this->webpage)) {
                        $this->get_webpage();
                    }
                    $this->webpage->update(
                        array(
                            'See Also' => $value
                        ), $options
                    );

                    $this->updated = $this->webpage->updated;

                    break;
                case 'Webpage Related Products':
                    $this->get_webpage();
                    $this->webpage->update(
                        array(
                            'Related Products' => $value
                        ), $options
                    );

                    $this->updated = $this->webpage->updated;

                    break;
                case 'Category Webpage Name':
                    $this->get_webpage();
                    $this->webpage->update(
                        array(
                            'Page Store Title' => $value,
                            'Page Short Title' => $value,
                        ), 'no_history'
                    );

                    $this->webpage->update(
                        array(

                            'Webpage Name' => $value
                        ), $options
                    );

                    $this->updated = $this->webpage->updated;

                    break;

                case 'Category Webpage Browser Title':
                    if (!is_object($this->webpage)) {
                        $this->get_webpage();
                    }

                    $this->webpage->update(array('Page Title' => $value), 'no_history');
                    $this->webpage->update(array('Webpage Browser Title' => $value), $options);
                    $this->updated = $this->webpage->updated;

                    break;

                case 'Category Website Node Parent Key':
                    $this->get_webpage();
                    $this->get_webpage();
                    $this->webpage->update(
                        array('Found In' => array($value)), $options
                    );

                    $this->updated = true;

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

    function associate_subject($subject_key, $force_associate = false, $other_value = '', $options = '') {


        // print "caca";

        if ($this->data['Category Branch Type'] == 'Root') {
            $this->msg = _("Subject can't be associated with category").' (Node is Root)';

            return false;
        }

        if ($this->is_subject_associated($subject_key)) {

            return true;
        }


        //   print $this->data['Category Subject Multiplicity']."\n";

        if ($this->data['Category Subject Multiplicity'] == 'Yes' or $force_associate) {

            $sql = sprintf(
                "INSERT INTO `Category Bridge` (`Category Key`,`Subject`,`Subject Key`,`Other Note`,`Category Head Key`)  VALUES (%d,%s,%d,%s,%d)", $this->id,
                prepare_mysql($this->data['Category Subject']), $subject_key, prepare_mysql($other_value), $this->id
            );


            $update_op = $this->db->prepare($sql);
            $update_op->execute();
            $inserted = $update_op->rowCount();


            if ($inserted) {
                $this->update_number_of_subjects();
                $this->update_subjects_data();


                switch ($this->data['Category Scope']) {
                    case('Part'):
                        include_once 'class.Part.php';

                        $part     = new Part($subject_key);
                        $abstract = _('Part').': <a href="part.php?sku='.$part->sku.'">SKU'.sprintf('05%d', $part->sku).'</a> '._('associated with category').sprintf(
                                ' <a href="part_category.php?id=%d">%s</a>', $this->id, $this->data['Category Code']
                            );
                        $details  = _('Part').': <a href="part.php?sku='.$part->sku.'">SKU'.sprintf('05%d', $part->sku).'</a> ('.$part->data['Part XHTML Description'].') '._(
                                'associated with category'
                            ).sprintf(
                                ' <a href="part_category.php?id=%d">%s</a>', $this->id, $this->data['Category Code']
                            ).' ('.$this->data['Category Label'].')';

                        $this->update_part_category_status();
                        break;
                    case('Supplier'):
                        include_once 'class.Supplier.php';

                        $supplier = new Supplier($subject_key);
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
                        include_once 'class.Customer.php';

                        $customer = new Customer($subject_key);
                        $abstract = _('Customer').': <a href="customer.php?id='.$customer->id.'">'.$customer->data['Customer Name'].'</a> '._('associated with category').sprintf(
                                ' <a href="part_category.php?id=%d">%s</a>', $this->id, $this->data['Category Code']
                            );
                        $details  = _('Customer').': <a href="customer.php?id='.$customer->id.'">'.$customer->data['Customer Name'].'</a> ('.$customer->data['Customer Main Location'].') '._(
                                'associated with category'
                            ).sprintf(
                                ' <a href="part_category.php?id=%d">%s</a>', $this->id, $this->data['Category Code']
                            ).' ('.$this->data['Category Label'].')';


                        break;
                    case('Product'):
                        include_once 'class.Store.php';

                        include_once 'class.Product.php';
                        $product = new Product($subject_key);

                        $store = new Store($this->get('Category Store Key'));
                        if ($this->get('Category Root Key') == $store->get('Store Family Category Key')) {
                            $product->update(array('Product Family Category Key' => $this->id), 'no_history');

                        }

                        $abstract = sprintf(_('Product %s associated with category %s'), $product->get('Code'), $this->get('Code'));
                        $details  = '';


                        if ($this->get('Category Root Key') == $store->get('Store Family Category Key')) {

                            // Migration -----

                            $sql = sprintf(
                                "SELECT * FROM `Product Family Dimension` WHERE `Product Family Store Key`=%d AND `Product Family Code`=%s", $this->get('Category Store Key'),
                                prepare_mysql($this->get('Category Code'))
                            );


                            if ($result = $this->db->query($sql)) {
                                if ($row = $result->fetch()) {

                                    $sql = sprintf(
                                        "update `Product Dimension`set `Product Family Key`=%d, `Product Family Code`=%s, `Product Family Name`=%s,`Product Main Department Key`=%d,`Product Main Department Code`=%s, `Product Main Department Name`=%s   where `Product ID`=%d",
                                        $row['Product Family Key'], prepare_mysql(
                                            $row['Product Family Code']
                                        ), prepare_mysql(
                                            $row['Product Family Name']
                                        ), $row['Product Family Main Department Key'], prepare_mysql(
                                            $row['Product Family Main Department Code']
                                        ), prepare_mysql(
                                            $row['Product Family Main Department Name']
                                        ), $subject_key
                                    );

                                    $this->db->exec($sql);

                                }
                            } else {
                                print_r($error_info = $this->db->errorInfo());
                                print $sql;
                                exit;
                            }


                            //   if($product->)


                            // Migration -----

                            if (!preg_match('/skip_direct_update/', $options)) {
                                $product->update(
                                    array(
                                        'Direct Product Family Category Key' => $this->id
                                    ), 'no_history'
                                );
                            }

                        }


                        if ($this->get('Category Root Key') == $store->get('Store Department Category Key')) {


                            $sql = sprintf(
                                "SELECT * FROM `Product Department Dimension` WHERE `Product Department Store Key`=%d AND `Product Department Code`=%s", $this->get('Category Store Key'),
                                prepare_mysql($this->get('Category Code'))
                            );

                            //print $sql;
                            if ($result = $this->db->query($sql)) {
                                if ($department = $result->fetch()) {
                                    $department_key  = $department['Product Department Key'];
                                    $department_code = $department['Product Department Code'];
                                    $department_name = $department['Product Department Name'];
                                } else {
                                    $department_key = false;
                                }
                            } else {
                                print_r($error_info = $this->db->errorInfo());
                                exit;
                            }


                            $family = new Category($subject_key);


                            $sql = sprintf(
                                "SELECT * FROM `Product Family Dimension` WHERE `Product Family Store Key`=%d AND `Product Family Code`=%s", $family->get('Category Store Key'),
                                prepare_mysql($family->get('Category Code'))
                            );
                            //print $sql;


                            if ($result = $this->db->query($sql)) {
                                if ($family = $result->fetch()) {
                                    $family_key = $family['Product Family Key'];
                                } else {
                                    $family_key = false;
                                }
                            } else {
                                print_r($error_info = $this->db->errorInfo());
                                exit;
                            }

                            //print "** $family_key $department_key **";
                            if ($family_key and $department_key) {


                                $sql = sprintf(
                                    "UPDATE `Product Family Dimension` SET `Product Family Main Department Key`=%d, `Product Family Main Department Code`=%s, `Product Family Main Department Name`=%s WHERE `Product Family Key`=%d",
                                    $department_key, prepare_mysql($department_code), prepare_mysql($department_name), $family_key
                                );


                                $this->db->exec($sql);

                                $sql = sprintf(
                                    "UPDATE `Product Dimension` SET `Product Main Department Key`=%d, `Product Main Department Code`=%s, `Product Main Department Name`=%s WHERE `Product Family Key`=%d",
                                    $department_key, prepare_mysql($department_code), prepare_mysql($department_name), $family_key
                                );
                                $this->db->exec($sql);


                                if (!preg_match(
                                    '/skip_direct_update/', $options
                                )
                                ) {
                                    $product->update(
                                        array(
                                            'Direct Product Department Category Key' => $this->id
                                        ), 'no_history'
                                    );
                                }


                            }


                        }


                        if ($this->get('Category Subject') == 'Product') {
                            $this->create_stack_index(true);

                        }

                        $this->update_product_category_products_data();

                        //$abstract=_('Product').': <a href="product.php?pid='.$product->pid.'">'.$product->data['Product Code'].'</a> '._('associated with category').sprintf(' <a href="part_category.php?id=%d">%s</a>', $this->id, $this->data['Category Code']);
                        //$details=_('Product').': <a href="product.php?pid='.$product->pid.'">'.$product->data['Product Code'].'</a> ('.$product->data['Product Name'].') '._('associated with category').sprintf(' <a href="part_category.php?id=%d">%s</a>', $this->id, $this->data['Category Code']).' ('.$this->data['Category Label'].')';
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
                        "INSERT INTO `Category Bridge` (`Category Key`,`Subject`,`Subject Key`,`Other Note`,`Category Head Key`) VALUES (%d,%s,%d, NULL,%d)", $parent_key,
                        prepare_mysql($this->data['Category Subject']), $subject_key, $this->id
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

        } else {


            $parents_where = '';
            $parent_keys   = $this->get_parent_keys();


            $found = false;
            $sql   = sprintf(
                "SELECT B.`Category Key` FROM `Category Bridge` B LEFT JOIN `Category Dimension` C    ON  (C.`Category Key`=B.`Category Key`)   WHERE  `Category Branch Type`='Head' AND `Category Root Key`=%d  AND B.`Category Key`!=%d AND `Subject`=%s AND `Subject Key`=%d",
                $this->data['Category Root Key'], $this->id, prepare_mysql($this->data['Category Subject']), $subject_key
            );


            if ($result = $this->db->query($sql)) {
                foreach ($result as $row) {

                    $other_category         = new Category(
                        $row['Category Key']
                    );
                    $other_category->editor = $this->editor;
                    $other_category->disassociate_subject(
                        $subject_key, $options
                    );
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                exit;
            }


            return $this->associate_subject($subject_key, true, $other_value);


        }

    }

    function get_children_keys() {
        $sql = sprintf(
            "SELECT `Category Key`   FROM `Category Dimension` WHERE `Category Parent Key`=%d ", $this->id
        );


        $children_keys = array();
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $children_keys[$row['Category Key']] = $row['Category Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $children_keys;

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
                $this->create_invoice_timeseries($data, $fork_key);
                break;
            case('Supplier'):
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


    function get_other_categories() {

        $other_data = array();

        $sql = sprintf(
            "SELECT * FROM `Category Dimension` WHERE `Is Category Field Other`='Yes' AND `Category Subject`='Customer'"
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $other_data[$row['Category Parent Key']] = $row['Category Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $other_data;
    }

    function get_other_value($subject, $subject_key) {
        $other_values = '';
        $sql          = sprintf(
            "SELECT ifnull(group_concat(DISTINCT `Other Note`),'') AS other_value FROM `Category Bridge` B LEFT JOIN  `Category Dimension` C ON (C.`Category Key`=B.`Category Key`)  WHERE `Subject`=%s AND `Subject Key`=%d AND `Other Note`!='' AND  `Is Category Field Other`='Yes' AND `Category Parent Key`=%d",
            prepare_mysql($subject), $subject_key, $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $other_value = $row['other_value'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $other_value;
    }

    function update_other_value($subject_key, $other_value) {

        $sql = sprintf(
            "UPDATE `Category Bridge` SET `Other Note` =%s WHERE `Category Key`=%d AND `Subject`=%s AND `Subject Key`=%d  ", prepare_mysql($other_value), $this->id,
            prepare_mysql($this->data['Category Subject']), $subject_key

        );
        //print $sql;
        $this->db->exec($sql);

    }

    function number_of_children_with_other_value($subject, $subject_key) {

        $number_of_children = 0;

        $sql = sprintf(
            " SELECT  count(DISTINCT C.`Category Key`) AS number_of_children    FROM `Category Dimension` C LEFT JOIN  `Category Bridge` B ON (C.`Category Key`=B.`Category Key`)  WHERE  `Subject`=%s AND `Subject Key`=%d AND `Is Category Field Other`='Yes' AND `Category Parent Key`=%d",
            prepare_mysql($subject), $subject_key, $this->id
        );
        //print $sql;


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_of_children = $row['number_of_children'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $number_of_children;
    }

    function get_children_key_is_other_value() {
        $children_key_is_other_value = 0;

        $sql = sprintf(
            " SELECT `Category Key`    FROM `Category Dimension` C  WHERE   `Is Category Field Other`='Yes' AND `Category Parent Key`=%d",


            $this->id
        );
        //print $sql;

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $children_key_is_other_value = $row['Category Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $children_key_is_other_value;
    }

    function get_children_key_is_other_value_public_edit() {
        $children_key_is_other_value = 0;

        $sql = sprintf(
            " SELECT `Category Key`    FROM `Category Dimension` C  WHERE   `Is Category Field Other`='Yes' AND `Category Parent Key`=%d AND `Category Show Public Edit`='Yes'",


            $this->id
        );
        //print $sql;

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $children_key_is_other_value = $row['Category Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $children_key_is_other_value;
    }

    function get_user_view_icon() {


        if ($this->data['Category Show Subject User Interface'] == 'Yes') {
            if ($this->data['Category Show Public New Subject'] == 'Yes') {
                if ($this->data['Category Show Public Edit'] == 'Yes') {
                    $image_tag = 'yyy';
                } else {
                    $image_tag = 'yyn';
                }
            } else {
                if ($this->data['Category Show Public Edit'] == 'Yes') {
                    $image_tag = 'yny';
                } else {
                    $image_tag = 'ynn';
                }

            }


        } else {
            if ($this->data['Category Show Public New Subject'] == 'Yes') {

                if ($this->data['Category Show Public Edit'] == 'Yes') {
                    $image_tag = 'nyy';
                } else {
                    $image_tag = 'nyn';
                }


            } else {


                if ($this->data['Category Show Public Edit'] == 'Yes') {
                    $image_tag = 'nny';
                } else {
                    $image_tag = 'nnn';
                }


            }

        }

        $public_view_icon = '<img src="art/icons/category_user_view_'.$image_tag.'.png" title="'._('Category View').'" /> ';

        return $public_view_icon;

    }


    function get_icon() {
        $branch_type_icon = '';
        switch ($this->data['Category Branch Type']) {
            case('Root'):
                $branch_type_icon =
                    '<img src="art/icons/category_root'.($this->data['Category Can Have Other'] == 'Yes' ? ($this->data['Category Children Other'] == 'Yes' ? '_with_other' : '_can_other') : '')
                    .'.png" title="'._('Root Node').'" /> ';
                break;
            case('Node'):
                $branch_type_icon =
                    '<img src="art/icons/category_node'.($this->data['Category Can Have Other'] == 'Yes' ? ($this->data['Category Children Other'] == 'Yes' ? '_with_other' : '_can_other') : '')
                    .'.png" title="'._('Node').'" />';
                break;
            case('Head'):
                if ($this->data['Is Category Field Other'] == 'No') {
                    $branch_type_icon = '<img src="art/icons/category_head.png" title="'._(
                            'Head Node'
                        ).'" /> ';
                } else {
                    $branch_type_icon = '<img src="art/icons/category_head_other.png" title="'._('Head Node').' ('._('Other').')" /> ';
                }

        }

        return $branch_type_icon;
    }


    function post_add_history($history_key, $type = false) {

        if (!$type) {
            $type = 'Changes';
        }

        switch ($this->data['Category Subject']) {
            case('Part'):
                $sql = sprintf(
                    "INSERT INTO  `Part Category History Bridge` VALUES (%d,%d,%s)", $this->id, $history_key, prepare_mysql($type)
                );
                $this->db->exec($sql);
                break;
            case('Location'):
                $sql = sprintf(
                    "INSERT INTO  `Location Category History Bridge` VALUES (%d,%d,%d,%s)", $this->data['Category Warehouse Key'], $this->id, $history_key, prepare_mysql($type)
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
                    "INSERT INTO  `Product Category History Bridge` VALUES (%d,%d,%d,%s)", $this->data['Category Store Key'], $this->id, $history_key, prepare_mysql($type)
                );
                $this->db->exec($sql);
                break;
            case('Family'):
                $sql = sprintf(
                    "INSERT INTO  `Product Family Category History Bridge` VALUES (%d,%d,%d,%s)", $this->data['Category Store Key'], $this->id, $history_key, prepare_mysql($type)
                );
                $this->db->exec($sql);
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


}
