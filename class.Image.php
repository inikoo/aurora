<?php

/*
  File: class.Image.php

  This file contains the Image Class

  About:
  Author: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2009, Inikoo

  Version 2.0
*/


class Image extends DB_Table {

    var $id = false;
    var $im = "";
    var $resized_im = "";
    var $im_x = 0;
    var $im_y = 0;
    var $jpgCompression = 90;
    var $msg = '';
    var $new = false;
    var $deleted = false;
    var $found_key = 0;
    public $fork = false;


    public $editor = array(
        'Author Name'  => false,
        'Author Alias' => false,
        'Author Key'   => 0,
        'User Key'     => 0,
        'Date'         => false
    );


    function __construct($a1, $a2 = false, $a3 = false, $_db = false) {

        if (!$_db) {
            global $db;
            $this->db = $db;
        } else {
            $this->db = $_db;
        }

        $this->table_name    = 'Image';
        $this->ignore_fields = array('Image Key');


        $this->tmp_path       = 'server_files/tmp/';
        $this->found          = false;
        $this->error          = false;
        $this->thumbnail_size = array(
            25,
            20
        );
        $this->small_size     = array(
            320,
            280
        );
        $this->large_size     = array(
            800,
            600
        );
        if (is_numeric($a1) and !$a2) {

            $this->get_data('id', $a1);
        } else {
            if (($a1 == 'new' or $a1 == 'create') and is_string($a2)) {
                $this->find($a2, 'create');
            } elseif ($a1 == 'find') {
                $this->find($a2, $a3);

            } else {
                $this->get_data($a1, $a2);
            }
        }
    }


    function get_data($tipo = 'id', $id) {
        if ($tipo == 'id') {


            $sql = sprintf(
                "SELECT * FROM `Image Dimension` WHERE `Image Key`=%d ", $id
            );

            if ($this->data = $this->db->query($sql)->fetch()) {
                $this->id = $this->data['Image Key'];

            }
        } elseif ($tipo == 'image_bridge_key') {
            $sql = sprintf(
                "SELECT * FROM `Image Subject Bridge` WHERE `Image Subject Key`=%d ", $id
            );

            if ($this->data = $this->db->query($sql)->fetch()) {
                $this->id = $this->data['Image Subject Image Key'];
                if ($this->id) {
                    $sql = sprintf(
                        "SELECT * FROM `Image Dimension` WHERE `Image Key`=%d ", $this->id
                    );

                    if ($row = $this->db->query($sql)->fetch()) {

                        foreach ($row as $key => $value) {
                            $this->data[$key] = $value;
                        }
                    } else {

                        $this->id = 0;
                    }


                }


            }


        }


    }


    function find($raw_data, $options) {


        if (isset($raw_data['editor'])) {
            foreach ($raw_data['editor'] as $key => $value) {

                if (array_key_exists($key, $this->editor)) {
                    $this->editor[$key] = $value;
                }

            }
            unset($raw_data['editor']);
        }


        if (preg_match('/\.\.\//', $raw_data['upload_data']['tmp_name'])) {
            $this->error = true;
            $this->msg   = 'Invalid filename, return paths forbidden';

            return;
        }


        $create = '';

        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }


        if (!is_file($raw_data['upload_data']['tmp_name'])) {
            $this->error = true;
            $this->msg   = _('No image file').' ('.$raw_data['upload_data']['tmp_name'].')';

            return;
        }


        $raw_data['Image File Checksum'] = md5_file($raw_data['upload_data']['tmp_name']);


        $sql = sprintf(
            "SELECT `Image Key` FROM `Image Dimension` WHERE `Image File Checksum`=%s", prepare_mysql($raw_data['Image File Checksum'])

        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $this->found     = true;
                $this->found_key = $row['Image Key'];
                $this->get_data('id', $this->found_key);
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql";
            exit;
        }


        if (!$this->found and $create) {
            $this->create($raw_data);

        }


    }

    function create($data) {

        //ALTER TABLE `Image Dimension` ADD `Image MIME Type` ENUM('image/jpeg', 'image/png','image/gif','image/x-icon') NULL DEFAULT NULL AFTER `Image Key`, ADD INDEX (`Image MIME Type`);


        $tmp_file = $data['upload_data']['tmp_name'];

        if(!empty($data['fork'])){
            $this->fork=true;
            unset($data['fork']);
        }else{
            $this->fork=false;
        }


        $data['Image File Size'] = filesize($tmp_file);

        $finfo = new finfo(FILEINFO_MIME_TYPE);

        $whitelist_type = array(
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/x-icon'
        );

        if (!in_array($file_mime = $finfo->file($tmp_file), $whitelist_type)) {
            $this->error = true;
            $this->msg   = _("Uploaded file is not an valid image format").' '.$file_mime;

            return;
        }

        $data['Image MIME Type'] = $file_mime;

        $size_data = getimagesize($tmp_file);

        if (!$size_data) {
            $this->error = true;
            $this->msg   = _("Error opening the image").', '._('please contact support');

            return;
        }


        $data['Image Width']  = $size_data[0];
        $data['Image Height'] = $size_data[1];


        if ($data['Image Width'] == 0 or $data['Image Height'] == 0) {
            $this->error = true;
            $this->msg   = _("Image is not supported").', '._('please contact support');

            return;
        }


        switch ($data['Image MIME Type']) {
            case 'image/x-icon':
                $file_extension = 'ico';
                break;
            default:
                $file_extension = preg_replace('/image\//', '', $data['Image MIME Type']);
        }

        //print 'img/db/'.$data['Image File Checksum'][0].'/'.$data['Image File Checksum'][1].'/'.$data['Image File Checksum'].'.'.$file_extension;


        $data['Image Path'] = 'img/db/'.$data['Image File Checksum'][0].'/'.$data['Image File Checksum'][1].'/'.$data['Image File Checksum'].'.'.$file_extension;

        if($this->fork){
            $account=get_object('Account',1);
            $destination_path= preg_replace('/^img/','img_'.$account->get('Code'),$data['Image Path']);

        }else{
            $destination_path= $data['Image Path'];
        }


        copy($tmp_file,$destination_path);
        //chmod($destination_path,0664);
        unlink($tmp_file);




        $data['Image Creation Date'] = gmdate('Y-n-d H:i:s');


        unset($data['upload_data']);

        $sql = sprintf(
            "INSERT INTO `Image Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($data)).'`', join(',', array_fill(0, count($data), '?'))
        );

        $stmt = $this->db->prepare($sql);

        $i = 1;
        foreach ($data as $key => $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {
            $this->id = $this->db->lastInsertId();


            $this->new = true;
            $this->get_data('id', $this->id);


        } else {
            $this->error = true;
            $this->msg   = 'Can not insert the image ';

            print_r($stmt->errorInfo());

            return;
        }


    }


    function get_object_name() {

        return 'Image';
    }


    function delete($force = false) {
        $subjects     = $this->get_subjects();
        $num_subjects = count($subjects);

        if ($num_subjects == 0 or $force) {
            $sql = sprintf(
                "DELETE FROM `Image Dimension` WHERE `Image Key`=%d", $this->id
            );


            $this->db->exec($sql);
            $sql = sprintf(
                "DELETE FROM `Image Subject Bridge` WHERE `Image Subject Image Key`=%d", $this->id
            );
            $this->db->exec($sql);
            $this->deleted = true;
        }
    }

    function get_subjects() {
        $subjects = array();
        $sql      = sprintf(
            'SELECT `Image Subject Object`,`Image Subject Is Principal`,`Image Subject Object Key` FROM `Image Subject Bridge` WHERE `Image Subject Image Key`=%d', $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $subjects[] = array(
                    'Subject Type' => $row['Image Subject Object'],
                    'Subject Key'  => $row['Image Subject Object Key'],
                    'Is Principal' => $row['Image Subject Is Principal']
                );

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql";
            exit;
        }


        return $subjects;
    }


    function get_field_label($field) {

        switch ($field) {

            case 'Image Caption':
                $label = _('caption');
                break;

            case 'Image Public':
                if ($this->get('Subject') == 'Staff') {
                    $label = _('Employee can see file');
                } elseif ($this->get('Subject') == 'Product') {
                    $label = _('Customers can see');
                } else {
                    $label = _('Public');
                }
                break;
            case 'Image File':
                $label = _('File');
                break;
            case 'Image File Original Name':
                $label = _('File name');
                break;
            case 'Image File Size':
                $label = _('File size');
                break;
            case 'Image Preview':
                $label = _('Preview');
                break;


            default:
                $label = $field;
                break;
        }

        return $label;
    }

    function get($key) {


        if (!$this->id) {
            return;
        }

        switch ($key) {


            default:
                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Image '.$key, $this->data)) {
                    return $this->data['Image '.$key];
                }

        }


    }


    function update_public_db() {


        $checksum   = $this->get('Image File Checksum');
        $image_path = $this->get('Image Path');

        $current_cwd=getcwd();

        if($this->fork){
            $account=get_object('Account',1);
            chdir('img_'.$account->get('Code'));
            chdir('../');
        }


        $path_root='img';

        if (!preg_match('/^[a-f0-9]{32}$/i', $checksum)) {
            exit('wrong checksum');
        }


        $sql = sprintf(
            "select `Image Subject Key` from `Image Subject Bridge`  where  `Image Subject Is Public`='Yes'  
             and `Image Subject Image Key`=%d 
             limit 1", $this->id
        );


        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {



                if (!is_dir($path_root.'/public_db/'.$checksum[0])) {
                    mkdir($path_root.'/public_db/'.$checksum[0]);
                }


                if (!is_dir($path_root.'/public_db/'.$checksum[0].'/'.$checksum[1])) {
                    mkdir($path_root.'/public_db/'.$checksum[0].'/'.$checksum[1]);
                }





                chdir($path_root.'/public_db/'.$checksum[0].'/'.$checksum[1]);




                $_tmp = preg_replace('/.*\//', '', $image_path);

                if (!file_exists($_tmp)) {

                    //print '1>'.preg_replace('/'.$path_root.'\/db/', '../../../db', $image_path)."\n";
                    //print "2>$_tmp\n";

                    if (!symlink(
                        preg_replace('/'.$path_root.'\/db/', '../../../db', $image_path), $_tmp


                    )) {
                        print getcwd()."\n";
                        print preg_replace('/'.$path_root.'\/db/', '../../../db', $image_path)."\n";
                        print "$_tmp\n";
                        print ('can not create symlink');
                    }
                }


                chdir($current_cwd);

            } else {

                $public_db_path = preg_replace('/'.$path_root.'\/db/', $path_root.'/public_db', $image_path);
                if (file_exists($public_db_path)) {
                    unlink($public_db_path);
                }


                $mask = $path_root.'/public_cache/'.$checksum[0].'/'.$checksum[1]."/".$checksum."_*";
                array_map("unlink", glob($mask));


            }
        }


    }

}
