<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 March 2016 at 12:20:56 GMT+8 Kuala Lumpur Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
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
    case 'upload_objects_to_delete':

        $data = prepare_values(
            $_REQUEST, array(
                         'parent'     => array('type' => 'string'),
                         'parent_key' => array('type' => 'numeric'),
                         'object'     => array('type' => 'string'),

                     )
        );

        upload_objects($account, $db, $user, $editor, $data, $smarty);
        break;
    case 'edit_objects':

        $data = prepare_values(
            $_REQUEST, array(
                         'parent'                         => array('type' => 'string'),
                         'parent_key'                     => array('type' => 'numeric'),
                         'objects'                        => array('type' => 'string'),
                         'upload_type'                        => array('type' => 'string','optional'=>true),
                         'allow_duplicate_part_reference' => array(
                             'type'     => 'string',
                             'optional' => true
                         ),

                     )
        );

        edit_objects($account, $db, $user, $editor, $data, $smarty);
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
                         'metadata'             => array(
                             'type'     => 'string',
                             'optional' => true
                         ),

                         'response_type'       => array(
                             'type'     => 'string',
                             'optional' => true
                         ),
                     )
        );

        upload_images($account, $db, $user, $editor, $data, $smarty);
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
            'msg'   => $msg
        );
        echo json_encode($response);
        exit;
    }

    // print_r($data);


    if (empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD'])
        && strtolower($_SERVER['REQUEST_METHOD']) == 'post') { //catch file overload error...
        $postMax  = ini_get('post_max_size'); //grab the size limits...
        $msg      = "File can not be attached, please note files larger than {$postMax} will result in this error!, let's us know, an we will increase the size limits"; // echo out error and solutions...
        $response = array(
            'state' => 400,
            'msg'   => _('Files could not be attached').".<br>".$msg,
            'key'   => 'attach'
        );
        echo json_encode($response);
        exit;

    }

    foreach ($_FILES as $file_data) {


        if ($file_data['error']) {
            $msg = $file_data['error'];

            if ($file_data['error'] === UPLOAD_ERR_INI_SIZE) {
                $msg = sprintf(
                    _('file exceeds the upload max filesize (%s)'), ini_get('upload_max_filesize')
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
                'msg'   => _(
                        'Files could not be attached'
                    )." ".$msg,
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

                $pcard        = $smarty->fetch(
                    'presentation_cards/attachment.pcard.tpl'
                );
                $updated_data = array();
                break;
            case 'Image':

                break;
            default:
                $response = array(
                    'state' => 400,
                    'msg'   => 'object process not found'

                );

                echo json_encode($response);
                exit;
                break;
        }
        if ($parent->error) {
            $response = array(
                'state' => 400,
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
        $options =$data['options'];
    } else {
        $options = '';
    }

    if (isset($data['metadata'])) {
        $metadata =json_decode($data['metadata'],true);
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
            echo json_encode(array('link' => sprintf('/image.php?id=%d', $image->id)));
        } else {

            if ($uploads > 0) {
                $msg = '<i class="fa fa-check"></i> '._('Success');
            } else {
                $msg = '<i class="fa fa-exclamation-circle"></i>';
            }


            //   print "xx $errors  $uploads";


            $response = array(
                'state'          => 200,
                'tipo'           => 'upload_images',
                'msg'            => $msg,
                'errors'         => $errors,
                'error_msg'      => $error_msg,
                'uploads'        => $uploads,
                'number_images'  => $parent->get_number_images(),

                'main_image_key' => $parent->get_main_image_key(),
                'image_src'      => sprintf('/image.php?id=%d', $image->id),
                'thumbnail'      => sprintf('<img src="/image.php?id=%d&size=25x20">', $image->id),
                'small_image'    => sprintf('<img src="/image.php?id=%d&size=320x280">', $image->id),
                'img_key'        => $image->id,
                'height'         => $image->get('Image Height'),
                'width'          => $image->get('Image Width'),
                'ratio'          => $image->get('Image Width') / $image->get('Image Height'),
            );


            if (isset($data['response_type']) and $data['response_type'] == 'upload_item_image') {
                $response['images'] = $parent->get_images_slidesshow();

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


function edit_objects($account, $db, $user, $editor, $data, $smarty) {
    require_once 'class.Upload.php';

    require_once 'external_libs/PHPExcel/Classes/PHPExcel.php';
    require_once 'external_libs/PHPExcel/Classes/PHPExcel/IOFactory.php';

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


    if (empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD'])
        && strtolower($_SERVER['REQUEST_METHOD']) == 'post') { //catch file overload error...
        $postMax  = ini_get('post_max_size'); //grab the size limits...
        $msg      = sprintf(
            _(
                "File can not be attached, please note files larger than %s will result in this error!, let's us know, an we will increase the size limits"
            ), $postMax
        );
        $response = array(
            'state' => 400,
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
            'msg'   => _("File can't be uploaded").", ".$msg
        );
        echo json_encode($response);
        exit;

    }

    $upload_files_data = array();
    $files_with_errors = array();

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
            $msg                 = _(
                    "This file seems that is empty, have a look and try again"
                ).'.';
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
                'filename' => $name
            );
            continue;

        }

        $tmp_name = 'up_'.microtime(true).'_'.$user->id.'_'.md5_file(
                $original_tmp_name
            ).'.'.pathinfo($name, PATHINFO_EXTENSION);
        $tmp_path = 'server_files/uploads/';

        // rename($original_tmp_name, $tmp_path.$tmp_name);


        $upload_files_data[] = array(
            'editor'               => $editor,
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

    $number_files_uploaded = count($upload_files_data);
    $fork_key              = false;
    $upload_key            = false;

    if ($number_files_uploaded) {
        $upload_data = array(
            'editor'            => $editor,
            'Upload Type'       => 'EditObjects',
            'Upload Type'       => (!empty($data['upload_type']) ?$data['upload_type']:  'EditObjects'),


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

            //print_r($upload_file_data);

            $inputFileType = PHPExcel_IOFactory::identify(
                $upload_file_data['Upload File Filename']
            );
            $objReader     = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);

            $objPHPExcel = @$objReader->load(
                $upload_file_data['Upload File Filename']
            );


            $objWorksheet = $objPHPExcel->getActiveSheet();

            $highestRow         = $objWorksheet->getHighestRow();
            $highestColumn      = $objWorksheet->getHighestColumn();
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString(
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

        $msg   = '<i class="fa fa-spinner fa-spin"></i> '.sprintf(
                _('Processing %s files'), $number_files_uploaded
            );
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
                $msg   = '<i class="fa fa-exclamation-circle"></i> '._(
                        'No files uploaded'
                    );
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

?>
