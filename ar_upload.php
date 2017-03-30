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
                         'parent'     => array('type' => 'string'),
                         'parent_key' => array('type' => 'numeric'),
                         'objects'    => array('type' => 'string'),

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
                         'options' => array(
                             'type'     => 'string',
                             'optional' => true
                         ),
                         'response_type'              => array('type' => 'string', 'optional' => true),
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
        && strtolower($_SERVER['REQUEST_METHOD']) == 'post'
    ) { //catch file overload error...
        $postMax  = ini_get('post_max_size'); //grab the size limits...
        $msg      =
            "File can not be attached, please note files larger than {$postMax} will result in this error!, let's us know, an we will increase the size limits"; // echo out error and solutions...
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


    if(isset($_FILES['file'])){
        $_FILES['files']['name'][0]=$_FILES['file']['name'];
        $_FILES['files']['size'][0]=$_FILES['file']['size'];
        $_FILES['files']['tmp_name'][0]=$_FILES['file']['tmp_name'];
        $_FILES['files']['type'][0]=$_FILES['file']['type'];
        $_FILES['files']['error'][0]=$_FILES['file']['error'];



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


        $data['fields_data']['Filename']                      = $tmp_name;
        $data['fields_data']['Attachment File Original Name'] = $name;

        $image_data = array(
            'Upload Data'                      => array(
                'tmp_name' => $tmp_name,
                'type'     => $type
            ),
            'Image Filename'                   => $name,
            'Image Subject Object Image Scope' => $parent_object_scope

        );



        $image = $parent->add_image($image_data,$options);


        if ($parent->error) {


            $errors++;

            $error_msg[] = $parent->msg;

        } else {
            $uploads++;


        }


    }


    if(isset($data['response_type']) and  $data['response_type']=='froala'){
        echo json_encode(array('link'=>sprintf('/image_root.php?id=%d', $image->id)));
    }elseif(isset($data['response_type']) and  $data['response_type']=='website'){
        echo json_encode(
            array(
                'state'=>200,
                'web_image_key'=>$image,
                'image_src'=>sprintf('/web_image.php?id=%d', $image
                )
            ));
    }else{
        if ($uploads > 0) {
            $msg = '<i class="fa fa-check"></i> '._('Success');
        } else {
            $msg = '<i class="fa fa-exclamation-circle"></i>';
        }

        $response = array(
            'state'          => 200,
            'tipo'           => 'upload_images',
            'msg'            => $msg,
            'errors'         => $errors,
            'error_msg'      => $error_msg,
            'uploads'        => $uploads,
            'number_images'  => $parent->get_number_images(),
            'main_image_key' => $parent->get_main_image_key(),
            'image_src'      => sprintf('/image_root.php?id=%d', $image->id),
            'thumbnail'      => sprintf('<img src="/image_root.php?id=%d&size=thumbnail">', $image->id)


        );

        // todo remove parent->get_object_name()=='Page' when new class is used
        if($parent->get_object_name()=='Page' or  $parent->get_object_name()=='Webpage'){
            $response['publish'] =$parent->get('Publish');
        }

        echo json_encode($response);

    }



}


function upload_objects_to_deete($account, $db, $user, $editor, $data, $smarty) {
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
        && strtolower($_SERVER['REQUEST_METHOD']) == 'post'
    ) { //catch file overload error...
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
            'Upload Object'     => $data['object'],
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

            if ($file_index == 0) {

                $upload_file_key = create_upload_file(
                    $db, $upload->id, $upload_file_data
                );

                //print_r($upload_file_data);

                $inputFileType = PHPExcel_IOFactory::identify(
                    $upload_file_data['Upload File Filename']
                );
                $objReader     = PHPExcel_IOFactory::createReader(
                    $inputFileType
                );
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


                for ($row = 2; $row <= $highestRow; ++$row) {
                    $row_data = array();
                    $empty    = true;
                    for ($col = 0; $col <= $highestColumnIndex; ++$col) {
                        $value          = $objWorksheet->getCellByColumnAndRow(
                            $col, $row
                        )->getCalculatedValue();
                        $row_data[$col] = $value;
                        if ($value != '') {
                            $empty = false;
                        }
                    }


                    if (!$empty) {
                        $sql = sprintf(
                            'INSERT INTO `Upload Record Dimension` (`Upload Record Upload Key`,`Upload Record Data`,`Upload Record Upload File Key`,`Upload Record Row Index`) VALUE (%d,COMPRESS(%s),%d,%d)',
                            $upload->id, prepare_mysql(json_encode($row_data)), $upload_file_key, ($row)
                        );

                        $db->exec($sql);
                        $number_records++;
                    }

                }


                $file_index++;
            }
            unlink($upload_file_data['Upload File Filename']);

        }

        $upload_data = array(
            'upload_key' => $upload->id,
            'user_key'   => $user->id
        );

        $upload->update(
            array('Upload Records' => $number_records), 'no_history'
        );

        $upload_key = $upload->id;
        list($fork_key, $msg) = new_fork(
            'au_upload', $upload_data, $account->get('Account Code'), $db
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


function parse_upload_file_error_msg($file_data_error) {

    if ($file_data_error === UPLOAD_ERR_INI_SIZE) {
        $msg = sprintf(
            _('file exceeds the upload max filesize (%s)'), ini_get('upload_max_filesize')
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
        $keys .= "`$key`,";
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
        && strtolower($_SERVER['REQUEST_METHOD']) == 'post'
    ) { //catch file overload error...
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
                    $value          = $objWorksheet->getCellByColumnAndRow(
                        $col, $row
                    )->getCalculatedValue();
                    $row_data[$col] = $value;
                    if ($value != '') {
                        $empty = false;
                    }
                }


                if (!$empty) {
                    $sql = sprintf(
                        'INSERT INTO `Upload Record Dimension` (`Upload Record Upload Key`,`Upload Record Data`,`Upload Record Upload File Key`,`Upload Record Row Index`) VALUE (%d,COMPRESS(%s),%d,%d)',
                        $upload->id, prepare_mysql(json_encode($row_data)), $upload_file_key, ($row)
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


?>
