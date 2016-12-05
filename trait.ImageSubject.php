<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 February 2016 at 20:36:41 GMT+8, Kuala Lumpur, Maysia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/

include_once 'utils/natural_language.php';

trait ImageSubject {


    function add_image($raw_data) {


        include_once 'utils/units_functions.php';


        $data = array(
            'Image Width'         => 0,
            'Image Height'        => 0,
            'Image File Size'     => 0,
            'Image File Checksum' => '',
            'Image Filename'      => $raw_data['Image Filename'],
            'Image File Format'   => '',
            'Image Data'          => '',

            'upload_data' => $raw_data['Upload Data'],
            'editor'      => $this->editor
        );


        if (isset($raw_data['Image Subject Object Image Scope'])) {
            $object_image_scope = $raw_data['Image Subject Object Image Scope'];
        } else {
            $object_image_scope = 'Default';
        }


        $image = new Image('find', $data, 'create');

        if ($image->id) {
            $this->link_image($image->id, $object_image_scope);

            if ($this->table_name == 'Part') {


                if ($object_image_scope != 'SKO') {

                    $this->activate();


                    foreach ($this->get_products('objects') as $product) {

                        if (count($product->get_parts()) == 1) {
                            $product->editor = $this->editor;
                            $product->link_image($image->id);
                        }

                    }
                }

            } elseif ($this->table_name == 'Category') {
                $account = new Account();
                if ($this->get('Category Scope') == 'Part' and $this->get('Category Root Key') == $account->get('Account Part Family Category Key')) {

                    $sql = sprintf(
                        'SELECT `Category Key` FROM `Category Dimension` WHERE `Category Scope`="Product" AND `Category Code`=%s  ', prepare_mysql($this->get('Code'))
                    );


                    if ($result = $this->db->query($sql)) {
                        foreach ($result as $row) {

                            $category         = new Category($row['Category Key']);
                            $category->editor = $this->editor;
                            $category->link_image($image->id, $object_image_scope);

                        }

                    } else {
                        print_r($error_info = $db->errorInfo());
                        print $sql;
                        exit;
                    }

                }

            }

            return $image;
        } else {
            $this->error = true;
            $this->msg   = "Can't create/found image, ".$image->msg;

            return false;
        }

    }


    function link_image($image_key, $object_image_scope = 'Default') {


        $image = new Image($image_key);

        if ($image->id) {
            $subject_key = $this->id;
            $subject     = $this->table_name;
            $sql         = sprintf(
                "SELECT `Image Subject Image Key`,`Image Subject Is Principal` FROM `Image Subject Bridge` WHERE `Image Subject Object`=%s AND `Image Subject Object Key`=%d  AND `Image Subject Image Key`=%d",
                prepare_mysql($subject), $subject_key, $image->id
            );

            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {
                    $this->nochange = true;
                    $this->msg      = _('Image already uploaded');

                    return;
                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql";
                exit;
            }

            $number_images = $this->get_number_images();
            if ($number_images == 0) {
                $principal = 'Yes';
            } else {
                $principal = 'No';
            }


            if (in_array(
                $subject, array(
                            'Product',
                            'Part'
                        )
            )) {
                $is_public = 'Yes';
            } else {
                $is_public = 'No';
            }

            $sql = sprintf(
                "INSERT INTO `Image Subject Bridge` (`Image Subject Object Image Scope`,`Image Subject Object`,`Image Subject Object Key`,`Image Subject Image Key`,`Image Subject Is Principal`,`Image Subject Image Caption`,`Image Subject Date`,`Image Subject Order`,`Image Subject Is Public`) VALUES (%s,%s,%d,%d,%s,'',%s,%d,%s)",
                prepare_mysql($object_image_scope), prepare_mysql($subject), $subject_key, $image->id, prepare_mysql($principal), prepare_mysql(gmdate('Y-m-d H:i:s')), ($number_images + 1),
                prepare_mysql($is_public)

            );
            $this->db->exec($sql);


            $this->reindex_order();


            $sql = sprintf(
                "SELECT `Image Subject Is Principal`,`Image Key`,`Image Subject Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` FROM `Image Subject Bridge` B LEFT JOIN `Image Dimension` ID ON (`Image Key`=`Image Subject Image Key`) WHERE `Image Subject Object`=%s AND `Image Subject Object Key`=%d AND  `Image Key`=%d",
                prepare_mysql($subject), $subject_key, $image->id
            );


            if ($result = $this->db->query($sql)) {
                if ($row = $result->fetch()) {

                    if ($row['Image Height'] != 0) {
                        $ratio = $row['Image Width'] / $row['Image Height'];
                    } else {
                        $ratio = 1;
                    }
                    include_once 'utils/units_functions.php';

                    $this->new_value = array(
                        'name'          => $row['Image Filename'],
                        'small_url'     => 'image.php?id='.$row['Image Key'].'&size=small',
                        'thumbnail_url' => 'image.php?id='.$row['Image Key'].'&size=thumbnail',
                        'filename'      => $row['Image Filename'],
                        'ratio'         => $ratio,
                        'caption'       => $row['Image Subject Image Caption'],
                        'is_principal'  => $row['Image Subject Is Principal'],
                        'id'            => $row['Image Key'],
                        'size'          => file_size($row['Image File Size']),
                        'width'         => $row['Image Width'],
                        'height'        => $row['Image Height']

                    );


                }
            } else {
                print_r($error_info = $this->db->errorInfo());
                print "$sql";
                exit;
            }


            $this->updated = true;
            $this->msg     = _("Image added");

            return $image;
        } else {
            $this->error = true;
            $this->msg   = "Can't create/found image, ".$image->msg;

            return false;
        }

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

    function reindex_order() {

        $order_index = array();

        $subject = $this->table_name;
        $sql     = sprintf(
            "SELECT `Image Subject Key` FROM `Image Subject Bridge` WHERE `Image Subject Object`=%s AND   `Image Subject Object Key`=%d ORDER BY `Image Subject Order`,`Image Subject Date`,`Image Subject Key`",
            prepare_mysql($subject), $this->id
        );
        //print $sql;
        $order = 1;
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                $sql           = sprintf(
                    "UPDATE `Image Subject Bridge` SET `Image Subject Order`=%d WHERE `Image Subject Key`=%d ", $order, $row['Image Subject Key']
                );
                $order_index[] = $row['Image Subject Key'];
                $this->db->exec($sql);
                $order++;
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql";
            exit;
        }

        $this->update_main_image();


        return $order_index;
    }

    function update_main_image() {


        $image_key = $this->get_main_image_key();


        if ($image_key) {


            $main_image_src = 'image_root.php?id='.$image_key.'&size=small';
            $main_image_key = $image_key;

        } else {
            $main_image_src = '/art/nopic.png';
            $main_image_key = 0;
        }

        //$this->data['Product Main Image']=$main_image_src;
        //$this->data['Product Main Image Key']=$main_image_key;


        $this->update(
            array(
                $this->table_name.' Main Image'     => $main_image_src,
                $this->table_name.' Main Image Key' => $main_image_key

            ), 'no_history'
        );


        //--- Migration -----

        if ($this->table_name == 'Category') {


            if ($main_image_src == '/art/nopic.png') {
                $main_image_src = 'art/nopic.png';
            }

            $main_image_src = preg_replace(
                '/image_root/', 'image', $main_image_src
            );

            include_once 'class.Store.php';
            $store = new Store($this->get('Category Store Key'));
            if ($this->get('Category Root Key') == $store->get(
                    'Store Family Category Key'
                )
            ) {


                $sql = sprintf(
                    'UPDATE `Product Family Dimension` SET `Product Family Main Image`=%s,`Product Family Main Image Key`=%d WHERE `Product Family Store Key`=%d AND `Product Family Code`=%s',

                    prepare_mysql($main_image_src), $main_image_key, $this->get('Category Store Key'), prepare_mysql($this->get('Category Code'))
                );
                $this->db->exec($sql);


            }


        } elseif ($this->table_name == 'Part') {
            $this->activate();
        }

        // -------------

        /*
        $page_keys=$this->get_pages_keys();
        foreach ($page_keys as $page_key) {
            $page=new Page($page_key);
            $page->update_image_key();
        }
        */
        $this->updated = true;

    }

    function get_main_image_key() {

        $image_key = false;

        $subject = $this->table_name;

        $sql = sprintf(
            "SELECT `Image Subject Image Key` FROM `Image Subject Bridge` WHERE `Image Subject Object`=%s AND `Image Subject Object Key`=%d ORDER BY `Image Subject Order` LIMIT 1",
            prepare_mysql($subject), $this->id

        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $image_key = $row['Image Subject Image Key'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql";
            exit;
        }


        return $image_key;

    }

    function get_images_slidesshow() {
        include_once 'utils/natural_language.php';

        if ($this->table_name == 'Store Product') {
            $image_subject_type = 'Product';
        } else {
            $image_subject_type = $this->table_name;
        }


        $sql = sprintf(
            "SELECT `Image Subject Is Principal`,`Image Key`,`Image Subject Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` FROM `Image Subject Bridge` B LEFT JOIN `Image Dimension` I ON (`Image Subject Image Key`=`Image Key`) WHERE `Image Subject Object`=%s AND   `Image Subject Object Key`=%d ORDER BY `Image Subject Is Principal`,`Image Subject Date`,`Image Subject Key`",
            prepare_mysql($image_subject_type), $this->id
        );
        //print $sql;
        $images_slideshow = array();
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($row['Image Height'] != 0) {
                    $ratio = $row['Image Width'] / $row['Image Height'];
                } else {
                    $ratio = 1;
                }
                // print_r($row);
                $images_slideshow[] = array(
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

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql";
            exit;
        }


        return $images_slideshow;
    }

    function delete_image($image_bridge_key) {

        $sql = sprintf(
            'SELECT `Image Subject Key`,`Image Subject Image Key` FROM `Image Subject Bridge` WHERE `Image Subject Key`=%d ', $image_bridge_key
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {


                $sql = sprintf(
                    'DELETE FROM `Image Subject Bridge` WHERE `Image Subject Key`=%d ', $image_bridge_key
                );
                $this->db->exec($sql);

                $image         = new Image($row['Image Subject Image Key']);
                $image->editor = $this->editor;

                $image->delete();
                $order_index = $this->reindex_order();


                if ($this->table_name == 'Part') {

                    foreach ($this->get_products('objects') as $product) {

                        if (count($product->get_parts()) == 1) {
                            $product->editor = $this->editor;


                            $sql = sprintf(
                                'SELECT `Image Subject Key` FROM `Image Subject Bridge` WHERE  `Image Subject Image Key`=%d  AND `Image Subject Object`="Product" AND   `Image Subject Object Key`=%d ',
                                $row['Image Subject Image Key'], $product->id
                            );


                            if ($result2 = $this->db->query($sql)) {
                                foreach ($result2 as $row2) {
                                    $product->delete_image(
                                        $row2['Image Subject Key']
                                    );
                                }
                            } else {
                                print_r($error_info = $this->db->errorInfo());
                                exit;
                            }

                        }

                    }

                }


            } else {
                $this->error;
                $this->msg = _('Image not found');
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql";
            exit;
        }


    }

    function set_as_principal($image_bridge_key) {

        $sql = sprintf(
            'UPDATE  `Image Subject Bridge` SET `Image Subject Order`=0  WHERE `Image Subject Key`=%d ', $image_bridge_key
        );
        $this->db->exec($sql);

        $this->reindex_order();

    }


}


?>
