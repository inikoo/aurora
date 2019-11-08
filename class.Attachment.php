<?php
/*
 File: Attachment.php

 This file contains the Attachment Class

 About:
 Author: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

include_once 'trait.ImageSubject.php';


include_once 'class.DB_Table.php';

/**
 * Class Attachment
 */
class Attachment extends DB_Table {
    use ImageSubject;

    /**
     * @var \PDO
     */
    public $db;


    function __construct($arg1 = false, $arg2 = false, $arg3 = false, $_db = false) {

        if (!$_db) {
            global $db;
            $this->db = $db;
        } else {
            $this->db = $_db;
        }


        $this->table_name    = 'Attachment';
        $this->ignore_fields = array('Attachment Key');

        if (preg_match('/^(new|create)$/i', $arg1) and is_array($arg2)) {
            $this->create($arg2);

            return;
        }

        if (preg_match('/find/i', $arg1)) {
            $this->find($arg2, $arg3);

            return;
        }
        if (is_numeric($arg1)) {
            $this->get_data('id', $arg1);

            return;
        }
        $this->get_data($arg1, $arg2);
    }

    function create($data) {

        $this->data = $this->base_data();
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $this->data)) {
                $this->data[$key] = _trim($value);
            }
        }


        $filename = $data['file'];


        $this->data['Attachment Data'] = file_get_contents($filename);


        $columns = '`'.join('`,`', array_keys($this->data)).'`';
        $values  = join(',', array_fill(0, count($this->data), '?'));


        $sql = "INSERT INTO `Attachment Dimension` ($columns) values ($values)";


        $stmt = $this->db->prepare($sql);


        $i = 1;
        foreach ($this->data as $key => $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {


            $this->id  = $this->db->lastInsertId();
            $this->new = true;
            $this->get_data('id', $this->id, true);


            if (preg_match('/^image/', $this->data['Attachment MIME Type'])) {
                $type = 'Image';
            } elseif (preg_match('/spreadsheet|excel/', $this->data['Attachment MIME Type'])) {
                $type = 'Spreadsheet';
            } elseif (preg_match('/msword/', $this->data['Attachment MIME Type'])) {
                $type = 'Word';
            } elseif (preg_match('/pdf/', $this->data['Attachment MIME Type'])) {
                $type = 'PDF';
            } elseif (preg_match('/(zip|rar)/', $this->data['Attachment MIME Type'])) {
                $type = 'Compressed';
            } elseif (preg_match('/(text)/', $this->data['Attachment MIME Type'])) {
                $type = 'Text';
            } else {
                $type = 'Other';
            }
            $this->fast_update(array('Attachment Type' => $type));


            $this->create_thumbnail();
        } else {


            $error = $this->db->errorInfo();
            if (preg_match('/max_allowed_packet/i', $error[2])) {
                $this->msg = "Got a packet bigger than 'max_allowed_packet' bytes ";
            } else {
                $this->msg = 'Unknown error';

            }
            $this->error = true;
        }

    }

    function get_data($key, $tag, $with_data = false) {

        if ($key == 'id') {
            $sql = sprintf(
                "SELECT * FROM `Attachment Dimension` WHERE `Attachment Key`=%d", $tag
            );

        } elseif ($key == 'bridge_key') {
            $sql = sprintf(
                "SELECT * FROM `Attachment Bridge` B LEFT JOIN  `Attachment Dimension` A ON (A.`Attachment Key`= B.`Attachment Key`) WHERE `Attachment Bridge Key`=%d", $tag
            );
        } else {
            return;
        }

        if ($this->data = $this->db->query($sql)->fetch()) {
            if (!$with_data) {
                unset($this->data['Attachment Data']);
            }
            $this->id = $this->data['Attachment Key'];
        }


    }


    function create_thumbnail() {


        try {

            if (preg_match('/application\/pdf/', $this->data['Attachment MIME Type'])) {
                $tmp_file = 'server_files/tmp/attachment'.date('U').$this->data['Attachment File Checksum'];


                $tmp_file_name = $tmp_file.'.pdf';
                file_put_contents($tmp_file_name, $this->data['Attachment Data']);

                $im = new imagick($tmp_file_name.'[0]');


            } elseif (preg_match('/image\/(png|jpg|gif|jpeg)/', $this->data['Attachment MIME Type'])) {

                $tmp_file      = 'server_files/tmp/attachment'.date('U').$this->data['Attachment File Checksum'];
                $tmp_file_name = $tmp_file;
                file_put_contents($tmp_file_name, $this->data['Attachment Data']);
                $im = new imagick($tmp_file_name);


            } else {
                return;
            }


            $im->setImageFormat('jpg');
            $im->thumbnailImage(500, 0);
            $im->writeImage($tmp_file.'.jpg');


            $image = $this->add_image(
                array(
                    'Image Filename' => 'attachment_thumbnail',
                    'Upload Data'    => array(
                        'tmp_name' => $tmp_file.'.jpg',
                        'type'     => 'jpg'
                    ),


                )
            );


            if (is_object($image) and $image->id) {
                $this->fast_update(
                    array(
                        'Attachment Thumbnail Image Key' => $image->id
                    )
                );
            }


            unlink($tmp_file_name);
            unlink($tmp_file.'.jpg');
        } catch (Exception $e) {
            // echo 'Caught exception: ',  $e->getMessage(), "\n";
        }


    }

    function find($raw_data, $options) {

        if (isset($raw_data['editor'])) {
            foreach ($raw_data['editor'] as $key => $value) {
                if (array_key_exists($key, $this->editor)) {
                    $this->editor[$key] = $value;
                }

            }
        }
        $this->found = false;
        $create      = '';

        if (preg_match('/create/i', $options)) {
            $create = 'create';
        }


        if (isset($raw_data['file']) and $raw_data['file'] != '') {
            $file     = $raw_data['file'];
            $checksum = md5_file($file);


            $file_info = finfo_open(FILEINFO_MIME_TYPE);
            $mime      = finfo_file($file_info, $file);
            finfo_close($file_info);
            if ($mime == 'unknown' and (isset($raw_data['Attachment MIME Type']) and $raw_data['Attachment MIME Type'] != '')) {
                $mime = "unknown (".$raw_data['Attachment MIME Type'].")";
            }

            $raw_data['Attachment MIME Type']     = $mime;
            $raw_data['Attachment File Checksum'] = $checksum;
            $raw_data['Attachment File Size']     = filesize($file);


        }


        $data = $this->base_data();
        foreach ($raw_data as $key => $val) {
            $_key        = $key;
            $data[$_key] = $val;
        }


        $sql = "SELECT `Attachment Key` FROM `Attachment Dimension` WHERE `Attachment File Checksum`=?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $data['Attachment File Checksum']
            )
        );
        if ($row = $stmt->fetch()) {
            $this->found     = true;
            $this->found_key = $row['Attachment Key'];
        }


        if ($this->found) {
            $this->get_data('id', $this->found_key);
            $this->found = true;

            return;
        }


        if ($create) {

            $this->create($data);

        }


    }

    function delete($force = false) {
        $subjects     = $this->get_subjects();
        $num_subjects = count($subjects);

        if ($num_subjects == 0 or $force) {
            $sql = "DELETE FROM `Attachment Dimension` WHERE `Attachment Key`=?";
            $this->db->prepare($sql)->execute(array($this->id));
            $sql = "DELETE FROM `Attachment Bridge` WHERE `Attachment Key`=?";
            $this->db->prepare($sql)->execute(array($this->id));
        }
    }

    function get_subjects() {
        $subjects = array();
        $sql      = sprintf(
            'SELECT * FROM `Attachment Bridge` WHERE `Attachment Key`=%d', $this->id
        );


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                $subjects[] = $row;
            }
        }


        return $subjects;
    }


    function get_field_label($field) {

        switch ($field) {
            case 'Attachment Subject Type':
                $label = _('Content type');
                break;
            case 'Attachment Caption':
                $label = _('Short description');
                break;

            case 'Attachment Public':
                if ($this->get('Subject') == 'Staff') {
                    $label = _('Employee can see file');
                } else {
                    $label = _('Public');
                }
                break;
            case 'Attachment File':
                $label = _('File');
                break;
            case 'Attachment File Original Name':
                $label = _('File name');
                break;
            case 'Attachment File Size':
                $label = _('File size');
                break;
            case 'Attachment Preview':
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
            return '';
        }

        switch ($key) {


            case 'Preview':

                return sprintf('/attachment_preview.php?id=%d', $this->get('Attachment Bridge Key'));

            case 'Public':
                if ($this->data['Attachment Public'] == 'Yes') {
                    return _('Yes');
                } else {
                    return _('No');
                }


            case 'Public Info':

                if ($this->get('Subject') == 'Staff') {
                    if ($this->data['Attachment Public'] == 'Yes') {
                        $visibility = sprintf(
                            '<i title="%s" class="fa fa-eye"></i> %s', _('Public'), _('Employee can see file')
                        );
                    } else {
                        $visibility = sprintf(
                            '<span class="error" > <i title="%s" class="fa fa-eye-slash"></i> %s</span>', _('Private'), _('Top secret file')
                        );
                    }
                } else {
                    if ($this->data['Attachment Public'] == 'Yes') {
                        $visibility = sprintf('<i title="%s" class="fa fa-eye"></i> %s', _('Public'), _('Public'));
                    } else {
                        $visibility = sprintf('<i title="%s" class="fa fa-eye-slash"></i> %s', _('Private'), _('Private'));
                    }

                }

                return $visibility;


            case 'Subject Type':

                if (array_key_exists('Attachment Subject Type', $this->data)) {


                    switch ($this->data['Attachment Subject Type']) {
                        case 'Contract':
                            $type = _('Employment contract');
                            break;
                        case 'CV':
                            $type = _('Curriculum vitae');
                            break;
                        case 'Other':
                            $type = _('Other');
                            break;
                        case 'Invoice':
                            $type = _('Invoice');
                            break;
                        case 'PurchaseOrder':
                            $type = _('Purchase order');
                            break;
                        case 'Contact Card':
                            $type = _('Contact card');
                            break;
                        case 'Catalogue':
                            $type = _('Catalogue');
                            break;
                        case 'Image':
                            $type = _('Image');
                            break;
                        case 'MSDS':
                            $type = _('Material Safety Data Sheet (MSDS)');
                            break;
                        default:
                            $type = $this->data['Attachment Subject Type'].'*';
                            break;
                    }

                    return $type;
                } else {
                    return '';
                }

            case 'File Size':
                include_once 'utils/natural_language.php';

                return file_size($this->data['Attachment File Size']);
            case 'Type':
                switch ($this->data['Attachment Type']) {
                    case 'PDF':
                        $file_type = sprintf(
                            '<i title="%s" class="fa fa-fw fa-file-pdf"></i> %s', $this->data['Attachment MIME Type'], 'PDF'
                        );

                        break;
                    case 'Image':
                        $file_type = sprintf(
                            '<i title="%s" class="fa fa-fw fa-image"></i> %s', $this->data['Attachment MIME Type'], _('Image')
                        );
                        break;
                    case 'Compressed':
                        $file_type = sprintf(
                            '<i title="%s" class="fa fa-fw fa-file-archive"></i> %s', $this->data['Attachment MIME Type'], _('Compressed')
                        );
                        break;
                    case 'Spreadsheet':
                        $file_type = sprintf(
                            '<i title="%s" class="fa fa-fw fa-table"></i> %s', $this->data['Attachment MIME Type'], _('Spreadsheet')
                        );
                        break;
                    case 'Text':
                        $file_type = sprintf(
                            '<i title="%s" class="fal fa-file-alt fa-fw"></i> %s', $this->data['Attachment MIME Type'], _('Text')
                        );
                        break;
                    case 'Word':
                        $file_type = sprintf(
                            '<i title="%s" class="fa fa-fw fa-file-word"></i> %s', $this->data['Attachment MIME Type'], 'Word'
                        );
                        break;
                    default:
                        $file_type = sprintf(
                            '<i title="%s" class="fa fa-fw fa-file"></i> %s', $this->data['Attachment MIME Type'], _('Other')
                        );
                        break;
                }

                return $file_type;

            default:
                if (array_key_exists($key, $this->data)) {
                    return $this->data[$key];
                }

                if (array_key_exists('Attachment '.$key, $this->data)) {
                    return $this->data['Attachment '.$key];
                }
        }


        return '';
    }

    function update_field_switcher($field, $value, $options = '', $metadata = '') {
        if (is_string($value)) {
            $value = _trim($value);
        }


        switch ($field) {
            case 'Attachment Caption':
            case 'Attachment Subject Type':
            case 'Attachment Public':
                $this->update_table_field(
                    $field, $value, $options, 'Attachment Bridge', 'Attachment Bridge', $this->get('Attachment Bridge Key')
                );

                if ($field == 'Attachment Public') {
                    $this->other_fields_updated = array(
                        'Public_Info' => array(
                            'field'           => 'Public_Info',
                            'render'          => true,
                            'value'           => $this->get('Public_Info'),
                            'formatted_value' => $this->get('Public Info'),


                        )
                    );

                }

                break;
            default:
                $base_data = $this->base_data();
                if (array_key_exists($field, $base_data)) {
                    $this->update_field($field, $value, $options);
                }
        }
        $bridge_key = $this->get('Attachment Bridge Key');
        $this->reread();

        $this->get_subject_data($bridge_key);

    }

    function get_subject_data($bridge_key) {

        $sql = sprintf(
            "SELECT * FROM `Attachment Bridge` WHERE `Attachment Bridge Key`=%d AND `Attachment Key`=%d", $bridge_key, $this->id
        );


        if ($row = $this->db->query($sql)->fetch()) {

            foreach ($row as $key => $value) {
                $this->data[$key] = $value;
            }
        }
    }


}



