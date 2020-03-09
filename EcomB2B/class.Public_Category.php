<?php /** @noinspection DuplicatedCode */

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 November 2016 at 17:49:06 GMT+8, Plane (Hangzhou - Kuala Lumpur)
 Copyright (c) 2016, Inikoo

 Version 3

*/


class Public_Category {

    /**
     * @var \PDO
     */
    public $db;

    /**
     * @var string|bool
     */
    public $id = false;
    /**
     * @var array
     */
    public $data;

    /**
     * @var string|bool
     */
    public $webpage = false;
    /**
     * @var string
     */
    public $table_name;


    function __construct($arg1 = false, $arg2 = false, $arg3 = false) {

        global $db;
        $this->db = $db;
        $this->id = false;

        $this->webpage = false;


        $this->table_name = 'Category';

        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }
        $this->get_data($arg1, $arg2, $arg3);


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
            default:
                $sql = sprintf(
                    "SELECT * FROM `Category Dimension` WHERE `Category Key`=%d", $tag
                );

                break;
        }

        // print "$sql\n";

        if ($this->data = $this->db->query($sql)->fetch()) {
            $this->id = $this->data['Category Key'];


            $sql = sprintf("SELECT * FROM `Product Category Dimension` WHERE `Product Category Key`=%d", $this->id);

            if ($result2 = $this->db->query($sql)) {
                if ($row = $result2->fetch()) {


                    $this->data = array_merge($this->data, $row);
                }
            }



        }


    }

    function load_webpage() {

        include_once __DIR__.'/class.Public_Webpage.php';


        $this->webpage = new Public_Webpage('scope', ($this->get('Category Subject') == 'Category' ? 'Category Categories' : 'Category Products'), $this->id);
    }


    function get($key) {

        switch ($key) {

            case 'Product Category Status':
            case 'Product Category Webpage Key':
            case 'Category Subject':
            case 'Product Category Department Category Key':
                return $this->data[$key];
                break;
            case 'Subject':
            case 'Code':
            case 'Scope':
            case 'Label':
            case 'Store Key':
                return $this->data['Category '.$key];
                break;
            case 'Description':
                return $this->data['Product Category '.$key];
                break;
            case 'Image':


                $image_key = $this->data['Category Main Image Key'];

                if ($image_key) {
                    $img = '/image.php?s=320x280&id='.$image_key;
                } else {
                    $img = '/art/nopic.png';

                }

                return $img;


        }

        return false;
    }


    function get_object_name() {
        return $this->table_name;

    }


    function get_deal_components($scope = 'keys', $options = 'Active') {

        switch ($options) {
            case 'Active':
                $where = "AND `Deal Component Status`='Active'";
                break;
            default:
                $where = '';
                break;
        }


        $deal_components = array();


        $sql =
            "SELECT `Deal Component Key` FROM `Deal Component Dimension`  left join `Deal Campaign Dimension` on (`Deal Component Campaign Key`=`Deal Campaign Key`)   WHERE  `Deal Campaign Code`!='CU' and  `Deal Component Allowance Target`='Category' AND `Deal Component Allowance Target Key`=? $where";



        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $this->id
            )
        );
        while ($row = $stmt->fetch()) {
            if ($scope == 'objects') {
                $deal_components[$row['Deal Component Key']] = get_object('DealComponent', $row['Deal Component Key']);
            } else {
                $deal_components[$row['Deal Component Key']] = $row['Deal Component Key'];
            }
        }


        return $deal_components;


    }

    function get_images_slideshow() {

        include_once __DIR__.'/utils/natural_language.php';


        $image_subject_type = $this->table_name;

        $images_slideshow = array();

        $sql =
            "SELECT `Image Subject Key`,`Image Subject Is Principal`,`Image Key`,`Image Subject Image Caption`,`Image Filename`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` FROM `Image Subject Bridge` B LEFT JOIN `Image Dimension` I ON (`Image Subject Image Key`=`Image Key`) WHERE `Image Subject Object`=? AND   `Image Subject Object Key`=? ORDER BY `Image Subject Order`,`Image Subject Is Principal`,`Image Subject Date`,`Image Subject Key`";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $image_subject_type,
                $this->id
            )
        );
        while ($row = $stmt->fetch()) {
            if ($row['Image Height'] != 0) {
                $ratio           = sprintf('%.5f', $row['Image Width'] / $row['Image Height']);
                $formatted_ratio = sprintf('%.2f', $row['Image Width'] / $row['Image Height']);
            } else {
                $ratio           = 1;
                $formatted_ratio = '-';
            }
            $images_slideshow[] = array(
                'name'              => $row['Image Filename'],
                'small_url'         => 'image.php?id='.$row['Image Key'].'&s=320x280',
                'thumbnail_url'     => 'image.php?id='.$row['Image Key'].'&s=25x20',
                'normal_url'        => 'image.php?id='.$row['Image Key'],
                'filename'          => $row['Image Filename'],
                'ratio'             => $ratio,
                'formatted_ratio'   => $formatted_ratio,
                'caption'           => $row['Image Subject Image Caption'],
                'is_principal'      => $row['Image Subject Is Principal'],
                'id'                => $row['Image Key'],
                'size'              => file_size($row['Image File Size']),
                'width'             => $row['Image Width'],
                'height'            => $row['Image Height'],
                'image_subject_key' => $row['Image Subject Key']

            );
        }


        return $images_slideshow;
    }




}


