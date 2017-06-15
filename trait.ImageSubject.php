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


    function add_image($raw_data, $options = '') {


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


        if (isset($raw_data['Image Subject Object Image Scope']) and $raw_data['Image Subject Object Image Scope']!='' ) {

            if ($this->table_name == 'Page') {

                json_decode($raw_data['Image Subject Object Image Scope']);
                if(json_last_error() == JSON_ERROR_NONE){
                    $scope_data         = json_decode($raw_data['Image Subject Object Image Scope'], true);
                    $object_image_scope = $scope_data['scope'];
                }else{
                    $scope_data=array('scope'=>'');
                    $object_image_scope=$raw_data['Image Subject Object Image Scope'];
                }




            } else{
                $object_image_scope = $raw_data['Image Subject Object Image Scope'];
            }


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
            elseif ($this->table_name == 'Page') {


                // print_r($scope_data);


                if ($scope_data['scope'] == 'content') {

                    $content_data = $this->get('Content Data');
                    $image_src    = '/image_root.php?size=small&id='.$image->id;

                    if ($scope_data['section'] == 'mute') {
                        return $image;
                    } elseif ($scope_data['section'] == 'items') {

                        foreach ($content_data['sections'] as $section_index => $section) {


                            foreach ($section['items'] as $item_index => $item) {


                                if ($item['type'] == 'category' and $item['category_key'] == $scope_data['item_key']) {


                                    $sql = sprintf(
                                        'SELECT `Category Webpage Index Key` ,`Category Webpage Index Content Data` FROM `Category Webpage Index` WHERE `Category Webpage Index Key`=%d  ',
                                        $content_data['sections'][$section_index]['items'][$item_index]['index_key']


                                    );

                                    if ($result = $this->db->query($sql)) {
                                        if ($row = $result->fetch()) {
                                            $item_content_data = json_decode($row['Category Webpage Index Content Data'], true);

                                            $item_content_data['image_src'] = $image_src;


                                            $sql = sprintf(
                                                'UPDATE `Category Webpage Index` SET `Category Webpage Index Content Data`=%s WHERE `Category Webpage Index Key`=%d ',
                                                prepare_mysql(json_encode($item_content_data)), $row['Category Webpage Index Key']
                                            );

                                            $this->db->exec($sql);
                                        }
                                    } else {
                                        print_r($error_info = $this->db->errorInfo());
                                        print "$sql\n";
                                        exit;
                                    }


                                    //    print_r(  $content_data['sections'][$section_index]['items']);

                                    break 2;
                                }
                            }

                        }

                        include_once('utils/website_functions.php');
                        $content_data['sections'][$section_index]['items'] = get_website_section_items($this->db, $content_data['sections'][$section_index]);
                        // print_r( $content_data['sections'][$section_index]['items']);

                    } elseif ($scope_data['section'] == 'panels_in_section') {


                        foreach ($content_data['sections'] as $section_index => $section) {


                            foreach ($section['panels'] as $panel_index => $panel) {
                                if ($panel['id'] == $scope_data['block']) {


                                    $content_data['sections'][$section_index]['panels'][$panel_index]['image_src'] = $image_src;
                                    break 2;
                                }
                            }

                        }

                        include_once('utils/website_functions.php');
                        $content_data['sections'][$section_index]['items'] = get_website_section_items($this->db, $content_data['sections'][$section_index]);


                        //  print_r( $content_data);


                    } elseif ($scope_data['section'] == 'panels') {
                        foreach ($content_data['panels'] as $panel_key => $panel) {
                            if ($panel['id'] == $scope_data['block']) {
                                $content_data['panels'][$panel_key]['image_src'] = $image_src;
                            }
                        }

                    } else {
                        if (isset($content_data[$scope_data['section']]['blocks'][$scope_data['block']])) {
                            $content_data[$scope_data['section']]['blocks'][$scope_data['block']]['image_src'] = $image_src;
                        } else {
                            $content_data[$data['section']]['blocks'][$data['block']] = array(
                                'image_src' => $image_src,
                                'type'      => 'image'
                            );
                        }
                    }


                    $this->update(array('Page Store Content Data' => json_encode($content_data)), 'no_history');

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


        // ALTER TABLE `Image Subject Bridge` CHANGE `Image Subject Object` `Image Subject Object` ENUM('Webpage','Store Product','Site Favicon','Product','Family','Department','Store','Part','Supplier Product','Store Logo','Store Email Template Header','Store Email Postcard','Email Image','Page','Page Header','Page Footer','Page Header Preview','Page Footer Preview','Page Preview','Site Menu','Site Search','User Profile','Attachment Thumbnail','Category','Staff') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

        $image = new Image($image_key);

        if ($image->id) {
            $subject_key = $this->id;
            $subject     = $this->table_name;

            if ($this->table_name == 'Page') {
                $subject = 'Webpage';
            }


            $sql = sprintf(
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
                            'Part',
                            'Webpage'
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

            $this->update_images_data();

            $image_subject_key = $this->db->lastInsertId();


            //print $sql;

            $this->reindex_order();


            $sql = sprintf(
                "SELECT `Image Subject Key`,`Image Subject Is Principal`,`Image Key`,`Image Subject Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` FROM `Image Subject Bridge` B LEFT JOIN `Image Dimension` ID ON (`Image Key`=`Image Subject Image Key`) WHERE `Image Subject Object`=%s AND `Image Subject Object Key`=%d AND  `Image Key`=%d",
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
                        'name'              => $row['Image Filename'],
                        'small_url'         => 'image.php?id='.$row['Image Key'].'&size=small',
                        'thumbnail_url'     => 'image.php?id='.$row['Image Key'].'&size=thumbnail',
                        'filename'          => $row['Image Filename'],
                        'ratio'             => $ratio,
                        'caption'           => $row['Image Subject Image Caption'],
                        'is_principal'      => $row['Image Subject Is Principal'],
                        'id'                => $row['Image Key'],
                        'size'              => file_size($row['Image File Size']),
                        'width'             => $row['Image Width'],
                        'height'            => $row['Image Height'],
                        'image_subject_key' => $image_subject_key

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


    function update_images_data() {

        $number_images = 0;

        $subject = $this->table_name;

        $sql = sprintf(
            "SELECT count(*) AS num FROM `Image Subject Bridge` WHERE `Image Subject Object`=%s AND `Image Subject Object Key`=%d ", prepare_mysql($subject), $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number_images = $row['num'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql\n";
            exit;
        }


        $this->update(
            array(($this->get_object_name() == 'Category' ? $this->subject_table_name : $this->get_object_name()).' Number Images' => $number_images), 'no_history'
        );


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
            if ($this->get('Category Root Key') == $store->get('Store Family Category Key')) {


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


        $image_subject_type = $this->table_name;


        $sql = sprintf(
            "SELECT `Image Subject Key`,`Image Subject Is Principal`,`Image Key`,`Image Subject Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` FROM `Image Subject Bridge` B LEFT JOIN `Image Dimension` I ON (`Image Subject Image Key`=`Image Key`) WHERE `Image Subject Object`=%s AND   `Image Subject Object Key`=%d ORDER BY `Image Subject Order`,`Image Subject Is Principal`,`Image Subject Date`,`Image Subject Key`",
            prepare_mysql($image_subject_type), $this->id
        );
        //print $sql;
        $images_slideshow = array();
        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {

                if ($row['Image Height'] != 0) {
                    $ratio           = sprintf('%.5f', $row['Image Width'] / $row['Image Height']);
                    $formatted_ratio = sprintf('%.2f', $row['Image Width'] / $row['Image Height']);
                } else {
                    $ratio           = 1;
                    $formatted_ratio = '-';
                }
                // print_r($row);
                $images_slideshow[] = array(
                    'name'            => $row['Image Filename'],
                    'small_url'       => 'image_root.php?id='.$row['Image Key'].'&size=small',
                    'thumbnail_url'   => 'image_root.php?id='.$row['Image Key'].'&size=thumbnail',
                    'normal_url'      => 'image_root.php?id='.$row['Image Key'],
                    'filename'        => $row['Image Filename'],
                    'ratio'           => $ratio,
                    'formatted_ratio' => $formatted_ratio,

                    'caption'           => $row['Image Subject Image Caption'],
                    'is_principal'      => $row['Image Subject Is Principal'],
                    'id'                => $row['Image Key'],
                    'size'              => file_size($row['Image File Size']),
                    'width'             => $row['Image Width'],
                    'height'            => $row['Image Height'],
                    'image_subject_key' => $row['Image Subject Key']

                );

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql";
            exit;
        }

        //1474527 ,1576664

        return $images_slideshow;
    }

    function delete_image($image_bridge_key) {

        $sql = sprintf(
            'SELECT `Image Subject Key`,`Image Subject Image Key` FROM `Image Subject Bridge` WHERE `Image Subject Key`=%d ', $image_bridge_key
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {


                $sql = sprintf('DELETE FROM `Image Subject Bridge` WHERE `Image Subject Key`=%d ', $image_bridge_key);
                $this->db->exec($sql);

                $this->update_images_data();


                $image         = new Image($row['Image Subject Image Key']);
                $image->editor = $this->editor;

                $image->delete();


                $order_index = $this->reindex_order();


                /*

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
                                    $product->delete_image($row2['Image Subject Key']);
                                }
                            } else {
                                print_r($error_info = $this->db->errorInfo());
                                exit;
                            }

                        }

                    }

                }
*/

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

    function set_as_principal($key, $look_up = 'image_bridge_key') {


        if ($look_up == 'image_key') {
            $sql = sprintf(
                'UPDATE  `Image Subject Bridge` SET `Image Subject Order`=0  WHERE   `Image Subject Object`=%s AND `Image Subject Object Key`=%d AND  `Image Subject Image Key`=%d ',
                prepare_mysql($this->table_name), $this->id, $key
            );
        } else {
            $sql = sprintf(
                'UPDATE  `Image Subject Bridge` SET `Image Subject Order`=0  WHERE `Image Subject Key`=%d ', $key
            );
        }


        //  print "$sql\n";


        $this->db->exec($sql);

        $this->reindex_order();

    }


}


?>
