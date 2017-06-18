<?php

/*
  File: class.Image.php

  This file contains the Image Class

  About:
  Author: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2009, Inikoo

  Version 2.0
*/


class Image {

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
    var $delete_source_file = false;


    public $editor = array(
        'Author Name'  => false,
        'Author Alias' => false,
        'Author Key'   => 0,
        'User Key'     => 0,
        'Date'         => false
    );


    function Image($a1, $a2 = false, $a3 = false, $_db = false) {

        if (!$_db) {
            global $db;
            $this->db = $db;
        } else {
            $this->db = $_db;
        }

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
                "SELECT `Image Key`,`Image Data`,`Image Thumbnail Data`,`Image Small Data`,`Image Large Data`,`Image Filename`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Size`,`Image File Format` FROM `Image Dimension` WHERE `Image Key`=%d ",
                $id
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
                        "SELECT `Image Key`,`Image Data`,`Image Thumbnail Data`,`Image Small Data`,`Image Large Data`,`Image Filename`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Size`,`Image File Format` FROM `Image Dimension` WHERE `Image Key`=%d ",
                        $this->id
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


        $raw_data['Image File Checksum'] = md5_file(
            $raw_data['upload_data']['tmp_name']
        );


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

        //print_r($data);

        $tmp_file = $data['upload_data']['tmp_name'];
        unset($data['upload_data']);
        $data['Image File Size'] = filesize($tmp_file);

        $data['Image File Format'] = $this->guess_file_format($tmp_file);
        $im                        = $this->get_image_from_file($data['Image File Format'], $tmp_file);
        if (!$im) {
            return;
        }


        $data['Image Width']  = imagesx($im);
        $data['Image Height'] = imagesy($im);




        if ($data['Image File Format'] == 'gif' and $this->is_animated_gif($tmp_file)) {
            $data['Image Data'] = file_get_contents($tmp_file);
        } else {
            $data['Image Data'] = $this->get_image_blob($im, $data['Image File Format']);
        }


        $keys   = '(';
        $values = 'values(';
        foreach ($data as $key => $value) {
            $keys .= "`$key`,";
            if ($key == 'Image Data') {
                $values .= "'".addslashes($value)."',";
            } else {
                $values .= prepare_mysql($value).",";
            }
        }
        $keys   = preg_replace('/,$/', ')', $keys);
        $values = preg_replace('/,$/', ')', $values);
        $sql    = sprintf(
            "INSERT INTO `Image Dimension` %s %s", $keys, $values
        );

        if ($this->db->exec($sql)) {
            $this->id = $this->db->lastInsertId();
            $this->im = $im;

            $this->new = true;
            $this->get_data('id', $this->id);


        } else {
            $this->error = true;
            $this->msg   = 'Can not insert the image ';

            print_r($this->db->errorInfo());
            return;
        }


        if ($this->delete_source_file) {
            unlink($tmp_file);
        }


        $this->create_other_size_data();

        $sql = sprintf(
            "UPDATE `Image Dimension` SET `Last Modify Date`=NOW() WHERE `Image Key`=%d ", $this->id
        );
        $this->db->exec($sql);


    }

    function guess_file_format($filename) {

        $mimetype = 'Unknown';


        ob_start();
        system("uname");
        $system  = 'Unknown';
        $_system = ob_get_clean();

        // print "S:$system M:$mimetype\n";

        if (preg_match('/darwin/i', $_system)) {
            ob_start();
            $system = 'Mac';
            system('file -I "'.addslashes($filename).'"');
            $mimetype = ob_get_clean();
            $mimetype = preg_replace('/^.*\:/', '', $mimetype);

        } elseif (preg_match('/linux/i', $_system)) {
            ob_start();
            $system   = 'Linux';
            $mimetype = system('file -ib "'.addslashes($filename).'"');
            $mimetype = ob_get_clean();
        } else {
            $system = 'Other';

        }


        //print "** $filename **";

        if (preg_match('/png/i', $mimetype)) {
            $format = 'png';
        } else {
            if (preg_match('/jpeg/i', $mimetype)) {
                $format = 'jpeg';
            } else {
                if (preg_match('/image.psd/i', $mimetype)) {
                    $format = 'psd';
                } else {
                    if (preg_match('/gif/i', $mimetype)) {
                        $format = 'gif';
                    } else {
                        if (preg_match('/wbmp$/i', $mimetype)) {
                            $format = 'wbmp';
                        } else {
                            $format = 'other';
                        }
                    }
                }
            }
        }
        //  print "S:$system M:$mimetype\n";
        // return;

        return $format;

    }


    // scale the image constraining proportions (maxX and maxY)

    function get_image_from_file($format, $srcImage) {


        if ($format == 'jpeg') {
            $im = imagecreatefromjpeg($srcImage);
        } elseif ($format == 'png') {
            $im = imagecreatefrompng($srcImage);
            imagealphablending($im, true);
            imagesavealpha($im, true);
        } elseif ($format == 'gif') {
            $im = imagecreatefromgif($srcImage);
        } elseif ($format == 'wbmp') {
            $im = imagecreatefromwbmp($srcImage);
        } elseif ($format == 'psd') {
            include_once 'class.PSD.php';
            $im = imagecreatefrompsd($srcImage);
        } else {
            $this->error = true;
            $this->msg   = _('File format not supported')." ($format)";

            return false;
        }

        if (!$im) {
            $this->error = true;
            $this->msg   = _('Can not read image');;

            return false;
        }

        return $im;

    }

    function is_animated_gif($filename) {
        if (!($fh = @fopen($filename, 'rb'))) {
            return false;
        }
        $count = 0;
        //an animated gif contains multiple "frames", with each frame having a
        //header made up of:
        // * a static 4-byte sequence (\x00\x21\xF9\x04)
        // * 4 variable bytes
        // * a static 2-byte sequence (\x00\x2C) (some variants may use \x00\x21 ?)

        // We read through the file til we reach the end of the file, or we've found
        // at least 2 frame headers
        while (!feof($fh) && $count < 2) {
            $chunk = fread($fh, 1024 * 100); //read 100kb at a time
            $count += preg_match_all('#\x00\x21\xF9\x04.{4}\x00(\x2C|\x21)#s', $chunk, $matches);
        }

        fclose($fh);

        return $count > 1;
    }

    function get_image_blob($im, $format = '') {

        if (!$format) {
            $format = $this->data['Image File Format'];
        }

        ob_start();
        if ($format == 'jpeg' or $format == 'psd') {
            imagejpeg($im, null, $this->jpgCompression);

        } elseif ($format == 'png' or $format == 'wbmp') {
            imagepng($im);
        } elseif ($format == 'gif') {
            imagegif($im);
        }

        $image_data = ob_get_contents();
        ob_end_clean();

        return $image_data;

    }

    function create_other_size_data() {
        $this->create_thumbnail();
        $this->create_small();
        //$this->create_large();
    }

    function create_thumbnail() {

       // if ($this->data['Image Thumbnail Data'] != '') {
       //     return;
       // }

        $thumbnail_im = $this->transformToFit(
            $this->thumbnail_size[0], $this->thumbnail_size[1]
        );
        if ($this->error) {
            $this->msg = _('Can not resize image');

            return;
        }


        $image_blob = $this->get_image_blob($thumbnail_im);
        $sql        = sprintf(
            "UPDATE `Image Dimension` SET `Image Thumbnail Data`='%s' WHERE `Image Key`=%d ", addslashes($image_blob), $this->id
        );

        $this->db->exec($sql);
        $this->data['Image Thumbnail Data'] = $image_blob;
    }


    function fit_to_canvas($canvas_w,$canvas_h){

        $w = $this->data['Image Width'];
        $h = $this->data['Image Height'];

        $r = $w / $h;

        $r_canvas=$canvas_w/$canvas_h;

        if($r < $r_canvas) {
            $fit_h = $canvas_h;
            $fit_w = $w * ($fit_h / $h);
            $canvas_y = 0;
            $canvas_x = ($canvas_w - $fit_w) / 2;
        }elseif($r > $r_canvas) {
            $fit_w = $canvas_w;
            $fit_h = $h * ($fit_w / $w);

            $canvas_x = 0;
            $canvas_y = ($canvas_h - $fit_h) / 2;
        }else{
            $fit_h = $canvas_h;
            $fit_w = $canvas_w;
            $canvas_x = 0;
            $canvas_y = 0;

        }


       // print " $w $h  ---  $fit_h   $fit_w $canvas_x  $canvas_y   ";


        $canvas = imagecreatetruecolor($canvas_w, $canvas_h);
        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefill($canvas, 0, 0, $white);

        imagecopyresampled($canvas, imagecreatefromstring($this->data['Image Data']), $canvas_x, $canvas_y, 0, 0, $fit_w, $fit_h, $w, $h);

        return $canvas;

    }


    function transformToFit($newX, $newY) {

        $x = $this->data['Image Width'];
        $y = $this->data['Image Height'];
        if ($x == 0) {
            $this->error = true;
            $this->msg   = 'image width is zero';

            return;
        }

        $mlt = $newX / $x;
        $nx  = ceil($x * $mlt);
        $ny  = ceil($y * $mlt);

        if ($ny > $newY) {
            $mlt = $newY / $ny;
            $nx  = ceil($nx * $mlt);
            $ny  = ceil($ny * $mlt);
        }

        return $this->resizeImage($nx, $ny);
    }

    function resizeImage($width, $height) {
        $dst_img = imagecreatetruecolor($width, $height);
        imagecopyresampled(
            $dst_img, $this->im, 0, 0, 0, 0, $width + 1, $height + 1, $this->data['Image Width'], $this->data['Image Height']
        );

        return $dst_img;
    }


    // scale the image constraining proportions (maxX and maxY)

    function create_small() {

        //if ($this->data['Image Small Data'] != '') {
        //    return;
        //}

        if ($this->data['Image Width'] < 375 and $this->data['Image Height'] < 250) {
            $sql = sprintf(
                "UPDATE `Image Dimension` SET `Image Small Data`=NULL WHERE `Image Key`=%d", $this->id
            );
            $this->db->exec($sql);

            return;
        }

        $small_im = $this->transformToFit(375, 250);
        if ($this->error) {
            $this->msg = _('Can not resize image');

            return;
        }


        $image_blob = $this->get_image_blob($small_im);

        $sql = sprintf(
            "UPDATE `Image Dimension` SET `Image Small Data`='%s' WHERE `Image Key`=%d", addslashes($image_blob), $this->id
        );
        $this->db->exec($sql);
        $this->data['Image Small Data'] = $image_blob;

    }

    function get_object_name() {

        return 'Image';
    }


    // scale the image constraining proportions (maxX and maxY)

    function get_resized($tn_w, $tn_h, $quality = 100, $watermark = false) {

        $source = imagecreatefromstring($this->data['Image Data']);

        //Figure out the dimensions of the image and the dimensions of the desired thumbnail
        $src_w = imagesx($source);
        $src_h = imagesy($source);


        $mlt   = $tn_w / $src_w;
        $new_w = ceil($src_w * $mlt);
        $new_h = ceil($src_h * $mlt);


        $x_mid = $tn_w / 2;
        $y_mid = $tn_h / 2;

        if ($new_h > $tn_h) {
            $mlt = $tn_h / $new_h;


            $new_w = ceil($new_w * $mlt);
            $new_h = ceil($new_h * $mlt);


        } else {
            $y_mid = 10 + ($tn_h - $new_h) / 2;
        }


        $newpic = imagecreatetruecolor(round($new_w), round($new_h));
        imagecopyresampled(
            $newpic, $source, 0, 0, 0, 0, $new_w, $new_h, $src_w, $src_h
        );
        $final           = imagecreatetruecolor($tn_w, $tn_h);
        $backgroundColor = imagecolorallocate($final, 255, 255, 255);
        imagefill($newpic, 0, 0, $backgroundColor);
        imagefill($final, 0, 0, $backgroundColor);
        //imagecopyresampled($final, $newpic, 0, 0, ($x_mid - ($tn_w / 2)), ($y_mid - ($tn_h / 2)), $tn_w, $tn_h, $tn_w, $tn_h);
        imagecopy(
            $final, $newpic, (($tn_w - $new_w) / 2), (($tn_h - $new_h) / 2), 0, 0, $new_w, $new_h
        );

        //if we need to add a watermark
        if ($watermark) {

            $wm_w = imagesx($watermark);
            $wm_h = imagesy($watermark);
            imagecopy(
                $final, $watermark, $tn_w - $wm_w, $tn_h - $wm_h, 0, 0, $tn_w, $tn_h
            );

        }

        return $final;
    }


    function save_image_to_file($path, $filename = false,$im=false) {


        if (!$im) {
            $image_data = $this->data['Image Data'];
        }else{
            $image_data = $this->get_image_blob($im);
        }

        if (!$filename) {
            $filename = $this->id;
        }

        file_put_contents($path.'/'.$filename.'.'.$this->data['Image File Format'], $image_data);

        return $filename.'.'.$this->data['Image File Format'];

    }





    function setCompression($val = 70) {
        if ($val > 0 && $val < 10) {
            $val = 10 * $val;
        } elseif ($val > 100) {
            $val = 100;
        } elseif ($val < 0) {
            $val = 0;
        }
        $this->jpgCompression = $val;
    }

    function create_large() {

        if ($this->data['Image Large Data'] != '') {
            return;
        }


        if ($this->data['Image Width'] < 800 or $this->data['Image Height'] < 600) {
            $sql = sprintf(
                "UPDATE `Image Dimension` SET `Image Large Data`=NULL WHERE `Image Key`=%d", $this->id
            );
            $this->db->exec($sql);

            return;
        }

        $large_im = $this->transformToFit(800, 600);
        if ($this->error) {
            $this->msg = _('Can not resize image');

            return;
        }

        $image_blob = $this->get_image_blob($large_im);

        $sql = sprintf(
            "UPDATE `Image Dimension` SET `Image Large Data`='%s' WHERE `Image Key`=%d", addslashes($image_blob), $this->id
        );
        $this->db->exec($sql);
        $this->data['Image Large Data'] = $image_blob;

    }

    function strokeImage($strokeWidth, $strokeColor = "000000") {
        $code   = $this->colordecode($strokeColor);
        $width  = imagesx($this->im);
        $height = imagesy($this->im);
        $color  = imagecolorallocate($this->im, $code[r], $code[g], $code[b]);
        if ($strokeWidth > 1) {
            for ($i = 0; $i < $strokeWidth; $i++) {
                imagerectangle(
                    $this->im, $i, $i, $width - ($i + 1), $height - ($i + 1), $color
                );
            }
        } else {
            imagerectangle($this->im, 0, 0, $width - 1, $height - 1, $color);
        }
    }

    function colordecode($hex) {
        $code[r] = hexdec(substr($hex, 0, 2));
        $code[g] = hexdec(substr($hex, 2, 2));
        $code[b] = hexdec(substr($hex, 4, 2));

        return $code;
    }



    function get_subjects_types($result_type = 'array') {
        $subject_types = array();
        $sql           = sprintf(
            'SELECT `Image Subject Type` FROM `Image Subject Bridge` WHERE `Image Subject Key`=%d ', $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $subject_types[$row['Subject Type']] = $row['Image Subject Type'];

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql";
            exit;
        }


        if ($result_type == 'array') {
            return $subject_types;
        } else {
            return implode(",", $subject_types);
        }

    }

    function remove_other_sizes_data() {
        $sql = sprintf(
            "UPDATE `Image Dimension` SET `Image Small Data`=NULL,`Image Thumbnail Data`=NULL,`Image Large Data`=NULL WHERE `Image Key`=%d ", $this->id
        );
        $this->db->exec($sql);
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
                "DELETE FROM `Image Bridge` WHERE `Image Key`=%d", $this->id
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

    function get_url() {
        return "image.php?id=".$this->id;
    }

    function get_field_label($field) {

        switch ($field) {

            case 'Image Caption':
                $label = _('caption');
                break;

            case 'Image Public':
                if ($this->get('Subject') == 'Staff') {
                    $label = _('Employee can see file');
                }
                if ($this->get('Subject') == 'Product') {
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

}
