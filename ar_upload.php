<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 March 2016 at 12:20:56 GMT+8 Kuala Lumpur Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;

require_once 'utils/ar_common.php';
require_once 'utils/object_functions.php';
require_once 'utils/natural_language.php';
require_once 'utils/parse_natural_language.php';
require_once 'utils/new_fork.php';


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 405,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}


$tipo = $_REQUEST['tipo'];

switch ($tipo) {
    case 'get_data':

        $data = prepare_values(
            $_REQUEST, array(
                         'object' => array('type' => 'string'),
                         'key'    => array('type' => 'numeric')

                     )
        );

        get_data($account, $db, $user, $data, $smarty);
        break;

    case 'edit_objects':

        $data = prepare_values(
            $_REQUEST, array(
                         'parent'                         => array('type' => 'string'),
                         'parent_key'                     => array('type' => 'numeric'),
                         'objects'                        => array('type' => 'string'),
                         'upload_type'                    => array(
                             'type'     => 'string',
                             'optional' => true
                         ),
                         'allow_duplicate_part_reference' => array(
                             'type'     => 'string',
                             'optional' => true
                         ),

                     )
        );

        edit_objects($account, $db, $user, $editor, $data);
        break;

    case 'upload_attachment':

        $data = prepare_values(
            $_REQUEST, array(
                         'object'      => array('type' => 'string'),
                         'parent'      => array('type' => 'string'),
                         'parent_key'  => array('type' => 'key'),
                         'fields_data' => array('type' => 'json array'),

                     )
        );

        upload_attachment($account, $db, $user, $editor, $data, $smarty);
        break;

    case 'upload_images':


        $data = prepare_values(
            $_REQUEST, array(
                         'parent'              => array('type' => 'string'),
                         'parent_key'          => array('type' => 'numeric'),
                         'parent_object_scope' => array(
                             'type'     => 'string',
                             'optional' => true
                         ),
                         'options'             => array(
                             'type'     => 'string',
                             'optional' => true
                         ),
                         'metadata'            => array(
                             'type'     => 'string',
                             'optional' => true
                         ),

                         'response_type' => array(
                             'type'     => 'string',
                             'optional' => true
                         ),
                     )
        );

        upload_images($account, $db, $user, $editor, $data, $smarty);
        break;
    case 'add_items_to_order':
        $data = prepare_values(
            $_REQUEST, array(
                         'parent'     => array('type' => 'string'),
                         'parent_key' => array('type' => 'numeric'),
                         'field'      => array('type' => 'string'),


                     )
        );

        add_items_to_order($account, $db, $user, $editor, $data, $smarty);
        break;
    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
        break;
}


function upload_attachment($account, $db, $user, $editor, $data, $smarty) {

    include_once 'class.Attachment.php';


    $parent         = get_object($data['parent'], $data['parent_key']);
    $parent->editor = $editor;


    if (!$parent->id) {
        $msg      = 'object key not found';
        $response = array(
            'state' => 400,
            'title' => _('Error'),
            'msg'   => $msg
        );
        echo json_encode($response);
        exit;
    }

    // print_r($data);


    if (empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
        $postMax  = ini_get('post_max_size');
        $msg      = "File can not be attached, please note files larger than {$postMax} will result in this error!, let's us know, an we will increase the size limits";
        $response = array(
            'state' => 400,
            'title' => _('Error'),
            'msg'   => _('Files could not be attached').".<br>".$msg,
            'key'   => 'attach'
        );
        echo json_encode($response);
        exit;

    }

    foreach ($_FILES as $file_data) {


        if ($file_data['error']) {

            if ($file_data['error'] === UPLOAD_ERR_INI_SIZE) {
                $msg = sprintf(
                    _('file exceeds the upload max file size (%s)'), ini_get('upload_max_filesize')
                );

            } elseif ($file_data['error'] === UPLOAD_ERR_FORM_SIZE) {
                $msg = _(
                    'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form'
                );

            } elseif ($file_data['error'] === UPLOAD_ERR_PARTIAL) {
                $msg = _('The uploaded file was only partially uploaded');

            } elseif ($file_data['error'] === UPLOAD_ERR_NO_FILE) {
                $msg = _('No file was uploaded');

            } else {
                $msg = sprintf(
                    _('File could not be attached, error code %s'), $file_data['error']
                );


            }


            $response = array(
                'state' => 400,
                'title' => _('Error'),
                'msg'   => $msg,
                'key'   => 'attach'
            );
            echo json_encode($response);
            exit;
        }

        if ($file_data['size'] == 0) {
            $msg = _("This file seems that is empty, have a look and try again").'.';


            $response = array(
                'state' => 400,
                'title' => _('Error'),
                'msg'   => $msg,
                'key'   => 'attach'
            );
            echo json_encode($response);
            exit;

        }

        if ($file_data['error']) {
            $msg = $file_data['error'];
            if ($file_data['error'] == 4) {
                $msg = ' '._('please choose a file, and try again');

            }
            $response = array(
                'state' => 400,
                'title' => _('Error'),
                'msg'   => _('Files could not be attached')." $msg",
                'key'   => 'attach'
            );
            echo json_encode($response);
            exit;
        }


        $data['fields_data']['Filename']                      = $file_data['tmp_name'];
        $data['fields_data']['Attachment File Original Name'] = $file_data['name'];
        //$data['fields_data']['Subject']=$parent->get_object_name();

        switch ($data['object']) {
            case 'Attachment':

                $object = $parent->add_attachment($data['fields_data']);

                switch ($parent->get_object_name()) {
                    case 'Staff':
                        $parent_reference = 'employee';
                        break;
                    default:
                        $parent_reference = strtolower(
                            $parent->get_object_name()
                        );
                        break;
                }


                $smarty->assign('account', $account);
                $smarty->assign('object', $object);
                $smarty->assign('parent', $parent_reference);
                $smarty->assign('parent_key', $parent->id);

                $pcard        = $smarty->fetch('presentation_cards/attachment.pcard.tpl');
                $updated_data = array();
                break;
            case 'Image':

                break;
            default:
                $response = array(
                    'state' => 400,
                    'title' => _('Error'),
                    'msg'   => 'object process not found'

                );

                echo json_encode($response);
                exit;
                break;
        }
        if ($parent->error) {
            $response = array(
                'state' => 400,
                'title' => _('Error'),
                'msg'   => '<i class="fa fa-exclamation-circle"></i> '.$parent->msg,

            );

        } else {

            $response = array(
                'state'        => 200,
                'msg'          => '<i class="fa fa-check"></i> '._('Success'),
                'pcard'        => $pcard,
                'new_id'       => $object->id,
                'updated_data' => $updated_data
            );


        }
        echo json_encode($response);


        exit;

    }


}


function upload_images($account, $db, $user, $editor, $data, $smarty) {

    include_once 'class.Image.php';

    $images = array();


    if (isset($data['parent_object_scope'])) {
        $parent_object_scope = $data['parent_object_scope'];
    } else {
        $parent_object_scope = 'Default';
    }


    if (isset($data['options'])) {
        $options = $data['options'];
    } else {
        $options = '';
    }

    if (isset($data['metadata'])) {
        $metadata = json_decode($data['metadata'], true);
    } else {
        $metadata = '';
    }


    $_options = json_decode($options, true);

    if (!empty($_options['parent_object_scope'])) {
        $parent_object_scope = $_options['parent_object_scope'];
    }


    $parent         = get_object($data['parent'], $data['parent_key']);
    $parent->editor = $editor;


    if (!$parent->id) {
        $msg      = 'object key not found';
        $response = array(
            'state' => 400,
            'title' => _('Error'),
            'msg'   => $msg
        );
        echo json_encode($response);
        exit;
    }


    if (empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post') { //catch file overload error...
        $postMax  = ini_get('post_max_size'); //grab the size limits...
        $msg      = sprintf(
            _(
                "File can not be attached, please note files larger than %s will result in this error!, let's us know, an we will increase the size limits"
            ), $postMax
        );
        $response = array(
            'state' => 400,
            'title' => _('Error'),
            'msg'   => $msg,
            'key'   => 'attach'
        );
        echo json_encode($response);
        exit;

    }

    if (empty($_FILES)) {
        $msg      = '_FILES array empty';
        $response = array(
            'state' => 400,
            'title' => _('Error'),
            'msg'   => _("Image can't be uploaded").", ".$msg
        );
        echo json_encode($response);
        exit;

    }

    $errors    = 0;
    $error_msg = array();
    $uploads   = 0;


    if (isset($_FILES['file'])) {
        $_FILES['files']['name'][0]     = $_FILES['file']['name'];
        $_FILES['files']['size'][0]     = $_FILES['file']['size'];
        $_FILES['files']['tmp_name'][0] = $_FILES['file']['tmp_name'];
        $_FILES['files']['type'][0]     = $_FILES['file']['type'];
        $_FILES['files']['error'][0]    = $_FILES['file']['error'];


    }


    foreach ($_FILES['files']['name'] as $file_key => $name) {


        $error    = $_FILES['files']['error'][$file_key];
        $size     = $_FILES['files']['size'][$file_key];
        $tmp_name = $_FILES['files']['tmp_name'][$file_key];
        $type     = $_FILES['files']['type'][$file_key];

        if ($error) {
            $msg = parse_upload_file_error_msg($error);

            $response = array(
                'state' => 400,
                'title' => _('Error'),
                'msg'   => $msg,
                'key'   => 'attach'
            );
            echo json_encode($response);
            exit;
        }

        if ($size == 0) {
            $msg = _("This file seems that is empty, have a look and try again").'.';


            $response = array(
                'state' => 400,
                'title' => _('Error'),
                'msg'   => $msg,
                'key'   => 'attach'
            );
            echo json_encode($response);
            exit;

        }


        list($width, $height) = getimagesize($tmp_name);


        if (isset($_options['width']) and isset($_options['height'])) {
            if ($_options['width'] != $width or $_options['height'] != $height) {
                $msg      = sprintf(_('Image dimensions must to be %sx%s (px)'), $_options['width'], $_options['height']);
                $response = array(
                    'state' => 400,
                    'title' => _('Wrong image dimensions'),
                    'msg'   => $msg,
                    'key'   => 'attach'
                );
                echo json_encode($response);
                exit;
            }
        } elseif (isset($_options['width'])) {
            if ($_options['width'] != $width) {
                $msg      = sprintf(_('Image width must to be %s (px)'), $_options['width']);
                $response = array(
                    'state' => 400,
                    'title' => _('Wrong image width'),
                    'msg'   => $msg,
                    'key'   => 'attach'
                );
                echo json_encode($response);
                exit;
            }
        } elseif (isset($_options['height'])) {
            if ($_options['height'] != $height) {
                $msg      = sprintf(_('Image height must to be %s (px)'), $_options['height']);
                $response = array(
                    'state' => 400,
                    'title' => _('Wrong image height'),
                    'msg'   => $msg,
                    'key'   => 'attach'
                );
                echo json_encode($response);
                exit;
            }
        }

        if (isset($_options['max_width'])) {
            if ($_options['max_width'] < $width) {
                $msg      = sprintf(_('Image max width is %s (px)'), $_options['max_width']);
                $response = array(
                    'state' => 400,
                    'title' => _('Image too large'),
                    'msg'   => $msg,
                    'key'   => 'attach'
                );
                echo json_encode($response);
                exit;
            }
        }
        if (isset($_options['max_height'])) {
            if ($_options['max_height'] < $height) {
                $msg      = sprintf(_('Image max height is %s (px)'), $_options['max_height']);
                $response = array(
                    'state' => 400,
                    'title' => _('Image too large'),
                    'msg'   => $msg,
                    'key'   => 'attach'
                );
                echo json_encode($response);
                exit;
            }
        }

        if (isset($_options['min_width'])) {
            if ($_options['min_width'] > $width) {
                $msg      = sprintf(_('Image min width is %s (px)'), $_options['min_width']);
                $response = array(
                    'state' => 400,
                    'title' => _('Image too small'),
                    'msg'   => $msg,
                    'key'   => 'attach'
                );
                echo json_encode($response);
                exit;
            }
        }
        if (isset($_options['min_height'])) {
            if ($_options['min_height'] > $height) {
                $msg      = sprintf(_('Image min height is %s (px)'), $_options['min_height']);
                $response = array(
                    'state' => 400,
                    'title' => _('Image too small'),
                    'msg'   => $msg,
                    'key'   => 'attach'
                );
                echo json_encode($response);
                exit;
            }
        }

        if (!empty($_options['fit_to_canvas'])) {

            list($canvas_w, $canvas_h) = preg_split('/x/', $_options['fit_to_canvas']);

            $size = getimagesize($tmp_name);

            $w = $size[0];
            $h = $size[1];

            $format = guess_file_format($tmp_name);


            //  print "$format $new_width $new_height";


            if ($format == 'jpeg') {
                $im = imagecreatefromjpeg($tmp_name);
            } elseif ($format == 'png') {
                $im = imagecreatefrompng($tmp_name);
                imagealphablending($im, true);
                imagesavealpha($im, true);
            } elseif ($format == 'gif') {
                $im = imagecreatefromgif($tmp_name);
            } elseif ($format == 'wbmp') {
                $im = imagecreatefromwbmp($tmp_name);
            } elseif ($format == 'psd') {
                include_once 'class.PSD.php';
                $im = imagecreatefrompsd($tmp_name);
            } else {

                $response = array(
                    'state' => 400,
                    'title' => _('Error'),
                    'msg'   => _('File format not supported')." ($format)",
                    'key'   => 'image'
                );
                echo json_encode($response);
                exit;
            }

            if (!$im) {


                $response = array(
                    'state' => 400,
                    'title' => _('Error'),
                    'msg'   => _('Can not read image'),
                    'key'   => 'image'
                );
                echo json_encode($response);
                exit;
            }


            $r = $w / $h;

            $r_canvas = $canvas_w / $canvas_h;

            if ($r < $r_canvas) {
                $fit_h    = $canvas_h;
                $fit_w    = $w * ($fit_h / $h);
                $canvas_y = 0;
                $canvas_x = ($canvas_w - $fit_w) / 2;
            } elseif ($r > $r_canvas) {
                $fit_w = $canvas_w;
                $fit_h = $h * ($fit_w / $w);

                $canvas_x = 0;
                $canvas_y = ($canvas_h - $fit_h) / 2;
            } else {
                $fit_h    = $canvas_h;
                $fit_w    = $canvas_w;
                $canvas_x = 0;
                $canvas_y = 0;

            }

            $canvas = imagecreatetruecolor($canvas_w, $canvas_h);
            $white  = imagecolorallocate($canvas, 255, 255, 255);
            imagefill($canvas, 0, 0, $white);

            imagecopyresampled($canvas, $im, $canvas_x, $canvas_y, 0, 0, $fit_w, $fit_h, $w, $h);


            ob_start();
            if ($format == 'jpeg' or $format == 'psd') {
                imagejpeg($canvas, null);

            } elseif ($format == 'png' or $format == 'wbmp') {
                imagepng($canvas);
            } elseif ($format == 'gif') {
                imagegif($canvas);
            }

            $image_data = ob_get_contents();
            ob_end_clean();


            file_put_contents($tmp_name, $image_data);


        }

        if (!empty($_options['set_width']) and is_numeric($_options['set_width']) and $_options['set_width'] > 0) {


            $size  = getimagesize($tmp_name);
            $width = $size[0];

            $height     = $size[1];
            $new_width  = $_options['set_width'];
            $new_height = $height * ($new_width / $width);
            $format     = guess_file_format($tmp_name);


            //  print "$format $new_width $new_height";


            if ($format == 'jpeg') {
                $im = imagecreatefromjpeg($tmp_name);
            } elseif ($format == 'png') {
                $im = imagecreatefrompng($tmp_name);
                imagealphablending($im, true);
                imagesavealpha($im, true);
            } elseif ($format == 'gif') {
                $im = imagecreatefromgif($tmp_name);
            } elseif ($format == 'wbmp') {
                $im = imagecreatefromwbmp($tmp_name);
            } elseif ($format == 'psd') {
                include_once 'class.PSD.php';
                $im = imagecreatefrompsd($tmp_name);
            } else {

                $response = array(
                    'state' => 400,
                    'title' => _('Error'),
                    'msg'   => _('File format not supported')." ($format)",
                    'key'   => 'image'
                );
                echo json_encode($response);
                exit;
            }

            if (!$im) {


                $response = array(
                    'state' => 400,
                    'title' => _('Error'),
                    'msg'   => _('Can not read image'),
                    'key'   => 'image'
                );
                echo json_encode($response);
                exit;
            }


            $dst_img = imagecreatetruecolor($new_width, $new_height);
            imagecopyresampled(
                $dst_img, $im, 0, 0, 0, 0, $new_width + 1, $new_height + 1, $width, $height
            );


            ob_start();
            if ($format == 'jpeg' or $format == 'psd') {
                imagejpeg($dst_img, null);

            } elseif ($format == 'png' or $format == 'wbmp') {
                imagepng($dst_img);
            } elseif ($format == 'gif') {
                imagegif($dst_img);
            }

            $image_data = ob_get_contents();
            ob_end_clean();


            file_put_contents($tmp_name, $image_data);


            //   exit;
            //  exit;


        }


        $data['fields_data']['Filename']                      = $tmp_name;
        $data['fields_data']['Attachment File Original Name'] = $name;

        $image_data = array(
            'Upload Data'                      => array(
                'tmp_name' => $tmp_name,
                'type'     => $type
            ),
            'Image Filename'                   => $name,
            'Image Subject Object Image Scope' => $parent_object_scope,


        );


        $image = $parent->add_image($image_data, $metadata);

        if ($parent->error) {


            $errors++;
            $error_msg[] = $parent->msg;

        } else {


            if (is_object($image) and $image->id) {
                $uploads++;
                $images[$image->id] = $image;
            } else {
                $errors++;
                $error_msg[] = $image->msg;
            }


        }


    }


    if (count($images) > 0) {

        $image = array_pop($images);

        if (isset($data['response_type']) and $data['response_type'] == 'froala') {
            echo json_encode(array('link' => sprintf('/wi.php?id=%d', $image->id)));
        } else {

            if ($uploads > 0) {
                $msg = '<i class="fa fa-check"></i> '._('Success');
            } else {
                $msg = '<i class="fa fa-exclamation-circle"></i>';
            }


            //   print "xx $errors  $uploads";


            $response = array(
                'state'         => 200,
                'tipo'          => 'upload_images',
                'msg'           => $msg,
                'errors'        => $errors,
                'error_msg'     => $error_msg,
                'uploads'       => $uploads,
                'number_images' => $parent->get_number_images(),

                'main_image_key' => $parent->get_main_image_key(),
                'image_src'      => sprintf('/image.php?id=%d', $image->id),
                'thumbnail'      => sprintf('<img src="/image.php?id=%d&size=25x20">', $image->id),
                'small_image'    => sprintf('<img src="/image.php?id=%d&size=320x280">', $image->id),

                'img_key' => $image->id,
                'height'  => $image->get('Image Height'),
                'width'   => $image->get('Image Width'),
                'ratio'   => $image->get('Image Width') / $image->get('Image Height'),
            );


            if (isset($data['response_type']) and $data['response_type'] == 'upload_item_image') {
                $response['images'] = $parent->get_images_slideshow();

            }

            // todo remove parent->get_object_name()=='Page' when new class is used
            if ($parent->get_object_name() == 'Page' or $parent->get_object_name() == 'Webpage') {
                $response['publish'] = $parent->get('Publish');
            }


            //  print_r($response);

            echo json_encode($response);

        }
    } else {
        $msg      = _("There is a problem uploading your image");
        $response = array(
            'state' => 400,
            'title' => _('Error'),
            'msg'   => $msg,
            'key'   => 'attach'
        );
        echo json_encode($response);
        exit;
    }


}


function parse_upload_file_error_msg($file_data_error) {

    if ($file_data_error === UPLOAD_ERR_INI_SIZE) {
        $msg = sprintf(
            _('file exceeds the upload max file size (%s)'), ini_get('upload_max_file size')
        );

    } elseif ($file_data_error === UPLOAD_ERR_FORM_SIZE) {
        $msg = _(
            'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form'
        );

    } elseif ($file_data_error === UPLOAD_ERR_PARTIAL) {
        $msg = _('The uploaded file was only partially uploaded');

    } elseif ($file_data_error === UPLOAD_ERR_NO_FILE) {
        $msg = _('No file was uploaded');

    } else {

        $msg = sprintf(
            _('File could not be attached, error code %s'), $file_data_error
        );


    }

    return $msg;

}


function create_upload_file($db, $upload_key, $upload_file_data) {

    unset($upload_file_data['editor']);
    unset($upload_file_data['Upload File Filename']);

    unset($upload_file_data['Upload File Type']);


    $upload_file_data['Upload File Upload Key'] = $upload_key;

    $keys   = '(';
    $values = 'values(';
    foreach ($upload_file_data as $key => $value) {
        $keys   .= "`$key`,";
        $values .= prepare_mysql($value).",";
    }
    $keys   = preg_replace('/,$/', ')', $keys);
    $values = preg_replace('/,$/', ')', $values);
    $sql    = sprintf(
        "INSERT INTO `Upload File Dimension` %s %s", $keys, $values
    );


    if ($db->exec($sql)) {
        $upload_file_key = $db->lastInsertId();


    } else {
        $upload_file_key = 0;
    }

    return $upload_file_key;

}


function get_data($account, $db, $user, $data, $smarty) {

    $object = get_object($data['object'], $data['key']);

    if (!$object->id) {
        $response = array(
            'state' => 400,
            'resp'  => 'object not found'
        );
        echo json_encode($response);
        exit;

    }
    if ($object->get_object_name() == 'Upload') {

        $response = array(
            'state'  => 200,
            'upload' => array(
                'state'      => $object->get('Upload State'),
                'class_html' => array(
                    'Upload_State'   => $object->get('State'),
                    'Upload_Date'    => $object->get('Date'),
                    'Upload_Records' => $object->get('Records'),
                    'Upload_OK'      => $object->get('OK'),
                    'Upload_Errors'  => $object->get('Errors'),

                )
            )
        );

        echo json_encode($response);
        exit;

    }


}


function edit_objects($account, $db, $user, $editor, $data) {

    list(
        $upload_files_data, $files_with_errors, $error_info
        ) = process_files();


    if ($error_info != '') {
        echo json_encode($error_info);
        exit;
    }


    require_once 'class.Upload.php';


    $parent         = get_object($data['parent'], $data['parent_key']);
    $parent->editor = $editor;


    if (!$parent->id) {
        $msg      = 'parent key not found';
        $response = array(
            'state' => 400,
            'msg'   => $msg
        );
        echo json_encode($response);
        exit;
    }


    $number_files_uploaded = count($upload_files_data);
    $fork_key              = false;
    $upload_key            = false;

    if ($number_files_uploaded > 0) {

        foreach ($upload_files_data as $_key => $_value) {
            $upload_files_data[$_key]['editor'] = $editor;
        }


        $upload_data = array(
            'editor'      => $editor,
            'Upload Type' => 'EditObjects',
            'Upload Type' => (!empty($data['upload_type']) ? $data['upload_type'] : 'EditObjects'),


            'Upload Object'     => $data['objects'],
            'Upload Parent'     => $data['parent'],
            'Upload Parent Key' => $data['parent_key'],
            'Upload User Key'   => $user->id,
            'Upload Metadata'   => json_encode(
                array(
                    'uploaded_files'    => $number_files_uploaded,
                    'files_with_errors' => count($files_with_errors),
                    'files_data'        => $upload_files_data
                )
            )

        );

        $upload = new Upload('create', $upload_data);

        $file_index     = 0;
        $number_records = 0;
        foreach ($upload_files_data as $upload_file_data) {

            $upload_file_key = create_upload_file(
                $db, $upload->id, $upload_file_data
            );


            $inputFileType = IOFactory::identify($upload_file_data['Upload File Filename']);
            $objReader     = IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);

            $objPHPExcel = @$objReader->load($upload_file_data['Upload File Filename']);


            $objWorksheet = $objPHPExcel->getActiveSheet();

            $highestRow         = $objWorksheet->getHighestRow();
            $highestColumn      = $objWorksheet->getHighestColumn();
            $highestColumnIndex = Coordinate::columnIndexFromString(
                $highestColumn
            );
            $row_data           = array();
            for ($col = 0; $col <= $highestColumnIndex; ++$col) {

                $value          = $objWorksheet->getCellByColumnAndRow($col, 1)->getCalculatedValue();
                $row_data[$col] = $value;

            }

            $upload->update(
                array(
                    'Upload Metadata' => json_encode(
                        array(
                            'uploaded_files'    => $number_files_uploaded,
                            'files_with_errors' => count($files_with_errors),
                            'files_data'        => $upload_files_data,
                            'fields'            => $row_data
                        )
                    )
                ), 'no_history'
            );


            for ($row = 2; $row <= $highestRow; ++$row) {
                $row_data = array();
                $empty    = true;
                for ($col = 0; $col <= $highestColumnIndex; ++$col) {
                    $value          = $objWorksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
                    $row_data[$col] = $value;
                    if ($value != '') {
                        $empty = false;
                    }


                }


                if (!$empty) {
                    $sql = sprintf(
                        'INSERT INTO `Upload Record Dimension` (`Upload Record Upload Key`,`Upload Record Data`,`Upload Record Upload File Key`,`Upload Record Row Index`) VALUE (%d,COMPRESS(%s),%d,%d)', $upload->id, prepare_mysql(json_encode($row_data)),
                        $upload_file_key, ($row)
                    );

                    $db->exec($sql);
                    $number_records++;
                }

            }


            $file_index++;

            unlink($upload_file_data['Upload File Filename']);

        }

        $upload_data = array(
            'upload_key' => $upload->id,
            'user_key'   => $user->id
        );


        if (isset($data['allow_duplicate_part_reference'])) {
            $upload_data['allow_duplicate_part_reference'] = $data['allow_duplicate_part_reference'];
        }


        $upload->update(
            array('Upload Records' => $number_records), 'no_history'
        );

        $upload_key = $upload->id;
        list($fork_key, $msg) = new_fork(
            'au_upload_edit', $upload_data, $account->get('Account Code'), $db
        );

        $sql = sprintf(
            'UPDATE `Fork Dimension` SET `Fork Operations Total Operations`=%d WHERE `Fork Key`=%d ', $number_records, $fork_key
        );
        $db->exec($sql);


    }


    if ($number_files_uploaded == 1) {
        $msg   = '<i class="fa fa-spinner fa-spin"></i> '._('Processing');
        $state = 200;
    } elseif ($number_files_uploaded > 1) {

        $msg   = '<i class="fa fa-spinner fa-spin"></i> '.sprintf(_('Processing %s files'), $number_files_uploaded);
        $state = 200;
    } else {
        if (count($files_with_errors) == 1) {

            foreach ($files_with_errors as $file_with_errors) {
                $error_msg = $file_with_errors['msg'];
            }

            $msg   = '<i class="fa fa-exclamation-circle"></i> '.$error_msg;
            $state = 400;
        } else {
            if (count($files_with_errors) > 0) {
                $error_msg = '';
                foreach ($files_with_errors as $file_with_errors) {
                    $error_msg .= $file_with_errors['filename'].': '.$file_with_errors['msg'].', ';
                }
                $error_msg = preg_replace('/,$/', '', $error_msg);

                $msg   = '<i class="fa fa-exclamation-circle"></i> '.$error_msg;
                $state = 400;
            } else {
                $msg   = '<i class="fa fa-exclamation-circle"></i> '._('No files uploaded');
                $state = 400;
            }
        }
    }

    $response = array(
        'state'             => $state,
        'msg'               => $msg,
        'tipo'              => 'upload_objects',
        'files_with_errors' => $files_with_errors,
        'fork_key'          => $fork_key,
        'upload_key'        => $upload_key


    );

    echo json_encode($response);


}


function add_items_to_order($account, $db, $user, $editor, $data) {

    list(
        $upload_files_data, $files_with_errors, $error_info
        ) = process_files();


    if ($error_info != '') {
        echo json_encode($error_info);
        exit;
    }


    $object         = get_object($data['parent'], $data['parent_key']);
    $object->editor = $editor;


    if (!$object->id) {
        $msg      = 'parent key not found';
        $response = array(
            'state' => 400,
            'msg'   => $msg
        );
        echo json_encode($response);
        exit;
    }


    $rows_data = array();
    foreach ($upload_files_data as $upload_file_data) {


        $inputFileType = IOFactory::identify($upload_file_data['Upload File Filename']);
        $objReader     = IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly(true);

        $objPHPExcel = @$objReader->load($upload_file_data['Upload File Filename']);


        $objWorksheet = $objPHPExcel->getActiveSheet();

        $highestRow         = $objWorksheet->getHighestRow();
        $highestColumn      = $objWorksheet->getHighestColumn();
        $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);


        for ($row = 1; $row <= $highestRow; ++$row) {
            $row_data = array();
            for ($col = 1; $col <= $highestColumnIndex; ++$col) {
                $value          = $objWorksheet->getCellByColumnAndRow($col, $row)->getCalculatedValue();
                $row_data[$col] = $value;
            }
            $rows_data[] = $row_data;
        }
    }

    $discounts_data = array();


    foreach ($rows_data as $row_index => $row_data) {

        $qty = trim($row_data[2]);

        switch ($object->get_object_name()) {
            case 'Purchase Order':
                list($item_key, $item_historic_key, $result) = find_purchase_order_item($db, $object, $row_data[1]);
                break;
            case 'Order':
                list($item_key, $item_historic_key, $result) = find_order_item($db, $object, $row_data[1]);
                break;
        }

        if ($result != 'ok') {
            $feedback[$row_index] = array(
                'ignored',
                $result
            );
            continue;
        }

        if ($qty == '') {
            $qty = 0;
        }

        if (!is_numeric($qty)) {
            $feedback[$row_index] = array(
                'ignored',
                'qty_missing'
            );
            continue;
        } elseif ($qty < 0) {
            $feedback[$row_index] = array(
                'ignored',
                'qty_error'
            );
            continue;
        }


        switch ($object->get_object_name()) {
            case 'Purchase Order':
                $transaction_data = array(
                    'item_key'          => $item_key,
                    'item_historic_key' => $item_historic_key,
                    'qty'               => $qty,
                    'field'             => $data['field']
                );

                $object->update_item($transaction_data);
                break;
            case 'Order':
                /**
                 * @var $object \Order
                 */
                $object->skip_update_after_individual_transaction = false;

                if (in_array(
                    $object->data['Order State'], array(
                                                    'InWarehouse',
                                                    'PackedDone'
                                                )
                )) {
                    $dispatching_state = 'Ready to Pick';
                } else {

                    $dispatching_state = 'In Process';
                }

                $payment_state = 'Waiting Payment';

                $data['Current Dispatching State'] = $dispatching_state;
                $data['Current Payment State']     = $payment_state;
                $data['Metadata']                  = '';
                $transaction_data                  = array(
                    'item_key'                  => $item_key,
                    'item_historic_key'         => $item_historic_key,
                    'qty'                       => $qty,
                    'field'                     => $data['field'],
                    'Current Dispatching State' => $dispatching_state,
                    'Current Payment State'     => $payment_state,
                    'Metadata'                  => ''
                );


                $object->update_item($transaction_data);

                break;
        }


    }


    if ($object->get_object_name() == 'Order') {
        $sql = sprintf(
            'SELECT `Order Transaction Amount`,OTF.`Product ID`,OTF.`Product Key`,`Order Transaction Total Discount Amount`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Order Transaction Amount`,`Order Currency Code`,OTF.`Order Transaction Fact Key`, `Deal Info` FROM `Order Transaction Fact` OTF left join  `Order Transaction Deal Bridge` B on (OTF.`Order Transaction Fact Key`=B.`Order Transaction Fact Key`) WHERE OTF.`Order Key`=%s ',
            $object->id
        );

        if ($result = $db->query($sql)) {
            foreach ($result as $row) {


                if (in_array(
                    $object->get('Order State'), array(
                                                   'Cancelled',
                                                   'Approved',
                                                   'Dispatched',
                                               )
                )) {
                    $discounts_class = '';
                    $discounts_input = '';
                } else {
                    $discounts_class = 'button';
                    $discounts_input = sprintf(
                        '<span class="hide order_item_percentage_discount_form" data-settings=\'{ "field": "Percentage" ,"transaction_key":"%d","item_key":%d, "item_historic_key":%d ,"on":1 }\'   ><input class="order_item_percentage_discount_input" style="width: 70px" value="%s"> <i class="fa save fa-cloud" aria-hidden="true"></i></span>',
                        $row['Order Transaction Fact Key'], $row['Product ID'], $row['Product Key'], percentage($row['Order Transaction Total Discount Amount'], $row['Order Transaction Gross Amount'])
                    );
                }
                $discounts = $discounts_input.'<span class="order_item_percentage_discount   '.$discounts_class.' '.($row['Order Transaction Total Discount Amount'] == 0 ? 'super_discreet' : '').'"><span style="padding-right:5px">'.percentage(
                        $row['Order Transaction Total Discount Amount'], $row['Order Transaction Gross Amount']
                    ).'</span> <span class="'.($row['Order Transaction Total Discount Amount'] == 0 ? 'hide' : '').'">'.money($row['Order Transaction Total Discount Amount'], $row['Order Currency Code']).'</span></span>';


                if (isset($data['tab']) and $data['tab'] == 'order.all_products') {
                    $discounts_data[$row['Product ID']] = array(
                        'deal_info' => $row['Deal Info'],
                        'discounts' => $discounts,
                        'item_net'  => money($row['Order Transaction Amount'], $row['Order Currency Code'])
                    );
                } else {
                    $discounts_data[$row['Order Transaction Fact Key']] = array(
                        'deal_info' => $row['Deal Info'],
                        'discounts' => $discounts,
                        'item_net'  => money($row['Order Transaction Amount'], $row['Order Currency Code'])
                    );
                }


            }
        }

        $update_metadata                 = $object->get_update_metadata();
        $update_metadata['deleted_otfs'] = $object->deleted_otfs;
        $update_metadata['new_otfs']     = $object->new_otfs;


    } else {

        $update_metadata = $object->get_update_metadata();
    }



    $response = array(
        'state'          => 200,
        'tipo'           => 'add_items_to_order',
        'metadata'       => $update_metadata,
        'discounts_data' => $discounts_data
    );
    echo json_encode($response);

}


/**
 * @param $db             \PDO
 * @param $purchase_order \PurchaseOrder
 * @param $code           string
 */
function find_purchase_order_item($db, $purchase_order, $code) {


    if ($code == '') {
        return array(
            0,
            0,
            'code_empty'
        );
    }


    if ($purchase_order->get('Purchase Order Parent') == 'Supplier') {

        $sql  = "select `Supplier Part Key`,`Supplier Part Historic Key`,`Supplier Part Status` from `Supplier Part Dimension` where `Supplier Part Supplier Key`=? and `Supplier Part Reference`=? ";
        $stmt = $db->prepare($sql);
        $stmt->execute(
            array(
                $purchase_order->get('Purchase Order Parent Key'),
                $code
            )
        );
        if ($row = $stmt->fetch()) {
            if ($row['Supplier Part Status'] == 'Available') {
                return array(
                    $row['Supplier Part Key'],
                    $row['Supplier Part Historic Key'],
                    'ok'
                );
            } else {
                return array(
                    $row['Supplier Part Key'],
                    $row['Supplier Part Historic Key'],
                    'supplier_part_not_available'
                );
            }
        }
        $sql  =
            "select `Supplier Part Key`,`Supplier Part Historic Key`,`Supplier Part Status`,`Part Status` from `Supplier Part Dimension` left join `Part Dimension` P on (`Part SKU`=`Supplier Part Part SKU`) where `Supplier Part Supplier Key`=? and `Part Reference`=? ";
        $stmt = $db->prepare($sql);
        $stmt->execute(
            array(
                $purchase_order->get('Purchase Order Parent Key'),
                $code
            )
        );
        if ($row = $stmt->fetch()) {
            if ($row['Supplier Part Status'] == 'Available') {


                if ($row['Supplier Status'] == 'In Process' or $row['Supplier Status'] == 'In Use') {

                    return array(
                        $row['Supplier Part Key'],
                        $row['Supplier Part Historic Key'],
                        'ok'
                    );
                } elseif ($row['Supplier Status'] == 'Discontinuing') {
                    return array(
                        $row['Supplier Part Key'],
                        $row['Supplier Part Historic Key'],
                        'part_discontinuing'
                    );
                } else {
                    return array(
                        $row['Supplier Part Key'],
                        $row['Supplier Part Historic Key'],
                        'part_not_in_use'
                    );
                }

            } else {
                return array(
                    $row['Supplier Part Key'],
                    $row['Supplier Part Historic Key'],
                    'supplier_part_not_available'
                );
            }
        } else {
            return array(
                0,
                0,
                'supplier_par_not_found'
            );
        }


    } elseif ($purchase_order->get('Purchase Order Parent') == 'Agent') {

        $sql  =
            "select `Supplier Part Key`,`Supplier Part Historic Key`,`Supplier Part Status` from `Supplier Part Dimension` LEFT JOIN `Agent Supplier Bridge` ON (`Supplier Part Supplier Key`=`Agent Supplier Supplier Key`)  WHERE `Agent Supplier Agent Key`=?  and `Supplier Part Reference`=? ";
        $stmt = $db->prepare($sql);
        $stmt->execute(
            array(
                $purchase_order->get('Purchase Order Parent Key'),
                $code
            )
        );
        if ($row = $stmt->fetch()) {
            if ($row['Supplier Part Status'] == 'Available') {
                return array(
                    $row['Supplier Part Key'],
                    $row['Supplier Part Historic Key'],
                    'ok'
                );
            } else {
                return array(
                    $row['Supplier Part Key'],
                    $row['Supplier Part Historic Key'],
                    'supplier_part_not_available'
                );
            }
        }
        $sql  =
            "select `Supplier Part Key`,`Supplier Part Historic Key`,`Supplier Part Status`,`Part Status` from `Supplier Part Dimension`  LEFT JOIN `Agent Supplier Bridge` ON (`Supplier Part Supplier Key`=`Agent Supplier Supplier Key`)  left join `Part Dimension` P on (`Part SKU`=`Supplier Part Part SKU`)  WHERE `Agent Supplier Agent Key`=?  and `Part Reference`=? ";
        $stmt = $db->prepare($sql);
        $stmt->execute(
            array(
                $purchase_order->get('Purchase Order Parent Key'),
                $code
            )
        );
        if ($row = $stmt->fetch()) {
            if ($row['Supplier Part Status'] == 'Available') {


                if ($row['Supplier Status'] == 'In Process' or $row['Supplier Status'] == 'In Use') {

                    return array(
                        $row['Supplier Part Key'],
                        $row['Supplier Part Historic Key'],
                        'ok'
                    );
                } elseif ($row['Supplier Status'] == 'Discontinuing') {
                    return array(
                        $row['Supplier Part Key'],
                        $row['Supplier Part Historic Key'],
                        'part_discontinuing'
                    );
                } else {
                    return array(
                        $row['Supplier Part Key'],
                        $row['Supplier Part Historic Key'],
                        'part_not_in_use'
                    );
                }

            } else {
                return array(
                    $row['Supplier Part Key'],
                    $row['Supplier Part Historic Key'],
                    'supplier_part_not_available'
                );
            }
        } else {
            return array(
                0,
                0,
                'supplier_par_not_found'
            );
        }


    }


}


/**
 * @param $db             \PDO
 * @param $purchase_order \Order
 * @param $code           string
 */
function find_order_item($db, $purchase_order, $code) {


    if ($code == '') {
        return array(
            0,
            0,
            'code_empty'
        );
    }


    $sql  = "select `Product ID`,`Product Current Key`,`Product Status` from `Product Dimension` where `Product Store Key`=? and `Product Code`=? ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $purchase_order->get('Order Store Key'),
            $code
        )
    );
    if ($row = $stmt->fetch()) {
        if ($row['Product Status'] == 'Active' or $row['Product Status'] == 'Discontinuing') {
            return array(
                $row['Product ID'],
                $row['Product Current Key'],
                'ok'
            );
        } elseif ($row['Product Status'] == 'InProcess') {
            return array(
                $row['Product ID'],
                $row['Product Current Key'],
                'product_in_process'
            );
        } else {
            return array(
                $row['Product ID'],
                $row['Product Current Key'],
                'product_not_available'
            );
        }
    } else {
        return array(
            0,
            0,
            'product_not_found'
        );
    }


}


function process_files() {


    $upload_files_data = array();
    $files_with_errors = array();
    $error_info        = '';


    $valid_extensions = array(
        'xls',
        'xlt',
        'xlm',
        'xlsx',
        'xlsm',
        'xltx',
        'xltm',
        'xlsb',
        'ods',
        'slk',
        'gnumeric',
        'tsv',
        'tab',
        'csv'
    );


    if (empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD'])
        && strtolower($_SERVER['REQUEST_METHOD']) == 'post') { //catch file overload error...
        $postMax    = ini_get('post_max_size'); //grab the size limits...
        $msg        = sprintf(
            _(
                "File can not be attached, please note files larger than %s will result in this error!, let's us know, an we will increase the size limits"
            ), $postMax
        );
        $error_info = array(
            'state' => 400,
            'title' => _('Error'),
            'msg'   => $msg,
            'key'   => 'attach'
        );


    }

    if (empty($_FILES)) {
        $msg        = '_FILES array empty';
        $error_info = array(
            'state' => 400,
            'title' => _('Error'),
            'msg'   => _("File can't be uploaded").", ".$msg
        );


    }


    if ($error_info == '') {


        foreach ($_FILES['files']['name'] as $file_key => $name) {


            $error             = $_FILES['files']['error'][$file_key];
            $size              = $_FILES['files']['size'][$file_key];
            $original_tmp_name = $_FILES['files']['tmp_name'][$file_key];
            $type              = $_FILES['files']['type'][$file_key];
            $extension         = strtolower(pathinfo($name, PATHINFO_EXTENSION));


            if ($error) {
                $msg = parse_upload_file_error_msg($error);

                $files_with_errors[] = array(
                    'msg'      => $msg,
                    'filename' => $name
                );
                continue;

            }

            if ($size == 0) {
                $msg                 = _("This file seems that is empty, have a look and try again").'.';
                $files_with_errors[] = array(
                    'msg'      => $msg,
                    'filename' => $name
                );
                continue;


            }

            if (!in_array($extension, $valid_extensions)) {
                $msg = _('Invalid file type').' <b>'.$extension.'</b> <i>('.$type.')</i>';

                $files_with_errors[] = array(
                    'msg'      => $msg,
                    'title'    => _('Error'),
                    'filename' => $name
                );
                continue;

            }

            $tmp_name = 'up_'.microtime(true).'_'.md5_file($original_tmp_name).'.'.pathinfo($name, PATHINFO_EXTENSION);

            $upload_files_data[] = array(
                'Upload File Checksum' => md5_file($original_tmp_name),
                'Upload File Name'     => $name,
                'Upload File Size'     => filesize($original_tmp_name),
                'Upload File Filename' => $original_tmp_name,
                'Upload File Type'     => $type,
                'Upload File Metadata' => json_encode(
                    array(
                        'extension' => $extension,
                        'type'      => $type,
                        'tmp_name'  => $tmp_name
                    )
                )

            );


        }
    }


    return array(
        $upload_files_data,
        $files_with_errors,
        $error_info
    );


}


function guess_file_format($filename) {

    $mime_type = mime_content_type($filename);


    if (preg_match('/png/i', $mime_type)) {
        $format = 'png';
    } else {
        if (preg_match('/jpeg/i', $mime_type)) {
            $format = 'jpeg';
        } else {
            if (preg_match('/image.psd/i', $mime_type)) {
                $format = 'psd';
            } else {
                if (preg_match('/gif/i', $mime_type)) {
                    $format = 'gif';
                } else {
                    if (preg_match('/wbmp$/i', $mime_type)) {
                        $format = 'wbmp';
                    } else {
                        $format = 'other';
                    }
                }
            }
        }
    }

    return $format;

}


