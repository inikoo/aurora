<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 March 2016 at 21:20:09 GMT+8, Yiwu, China

 Copyright (c) 2016, Inikoo

 Version 3.0

*/


trait AttachmentSubject {


    function add_attachment($raw_data) {
        $data = array(
            'file' => $raw_data['Filename']
        );

        $attach = new Attachment('find', $data, 'create');


        $subject_key = $this->id;


        $subject = $this->get_object_name();


        if ($attach->id) {


            $sql = sprintf(
                "INSERT INTO `Attachment Bridge` (`Attachment Key`,`Subject`,`Subject Key`,`Attachment File Original Name`,`Attachment Caption`,`Attachment Subject Type`) VALUES (%d,%s,%d,%s,%s,%s)",
                $attach->id, prepare_mysql($this->get_object_name()), $this->get_main_id(), prepare_mysql($raw_data['Attachment File Original Name']),
                prepare_mysql($raw_data['Attachment Caption'], false), prepare_mysql($raw_data['Attachment Subject Type'])


            );
            $this->db->exec($sql);

            $subject_bridge_key = $this->db->lastInsertId();

            if (!$subject_bridge_key) {

                $this->error = true;
                $this->msg   = _('File already attached');

                return $attach;
            }
            $attach->editor = $this->editor;
            $history_data   = array(
                'History Abstract' => _('File attached'),
                'History Details'  => '',
                'Action'           => 'created',
            );
            $attach->add_subject_history(
                $history_data, true, 'No', 'Changes', 'Attachment Bridge', $subject_bridge_key
            );


            $attach->get_subject_data($subject_bridge_key);


        } else {
            $this->error;
            $this->msg = $attach->msg;
        }


        return $attach;
    }


    function get_attachments_data() {

        include_once 'utils/units_functions.php';

        if ($this->table_name == 'Product' or $this->table_name == 'Supplier Product') {
            $subject_key = $this->pid;
        } else {
            $subject_key = $this->id;
        }

        if ($this->table_name == 'Product Family') {
            $subject = 'Family';
        } elseif ($this->table_name == 'Product Department') {
            $subject = 'Department';
        } else {

            $subject = $this->table_name;
        }


        $sql = sprintf(
            'SELECT A.`Attachment Key`,`Attachment MIME Type`,`Attachment Type`,`Attachment Caption`,`Attachment Public`,`Attachment File Original Name`,`Attachment Thumbnail Image Key`,`Attachment File Size` FROM `Attachment Bridge` B LEFT JOIN `Attachment Dimension` A ON  (A.`Attachment Key`=B.`Attachment Key`) WHERE `Subject`=%s AND `Subject Key`=%d',
            prepare_mysql($subject), $subject_key
        );


        $attachment_data = array();


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {


                if ($row['Attachment Type'] == 'Image') {
                    $icon
                        = '<img class="icon" src="art/icons/page_white_picture.png" alt="'.$row['Attachment MIME Type'].'" title="'.$row['Attachment MIME Type'].'" />';
                } elseif ($row['Attachment Type'] == 'Image') {
                    $icon
                        = '<img class="icon"  src="art/icons/page_white_excel.png" alt="'.$row['Attachment MIME Type'].'" title="'.$row['Attachment MIME Type'].'"/>';
                } elseif ($row['Attachment Type'] == 'Word') {
                    $icon
                        = '<img class="icon" src="art/icons/page_white_word.png" alt="'.$row['Attachment MIME Type'].'" title="'.$row['Attachment MIME Type'].'"/>';
                } elseif ($row['Attachment Type'] == 'PDF') {
                    $icon
                        = '<img class="icon" src="art/icons/page_white_acrobat.png" alt="'.$row['Attachment MIME Type'].'" title="'.$row['Attachment MIME Type'].'"/>';
                } elseif ($row['Attachment Type'] == 'Compressed') {
                    $icon
                        = '<img class="icon" src="art/icons/page_white_compressed.png" alt="'.$row['Attachment MIME Type'].'" title="'.$row['Attachment MIME Type'].'"/>';
                } elseif ($row['Attachment Type'] == 'Text') {
                    $icon
                        = '<img class="icon" src="art/icons/page_white_text.png" alt="'.$row['Attachment MIME Type'].'" title="'.$row['Attachment MIME Type'].'"/>';
                } else {
                    $icon = '<img class="icon" src="art/icons/attach.png" alt="'.$row['Attachment MIME Type'].'" title="'.$row['Attachment MIME Type'].'"/>';

                }

                $name = $row['Attachment File Original Name'];
                if (strlen($name) > 20) {

                    $exts = preg_split("/\./i", $name);
                    $n    = count($exts) - 1;

                    $_exts = $exts[$n];
                    unset($exts[$n]);
                    $name = join(',', $exts);


                    $name = substr($name, 0, 15)." <b>&hellip;</b> ".$_exts;
                }


                $attachment_data[] = array(
                    'key'       => $row['Attachment Key'],
                    'type'      => $row['Attachment Type'],
                    'caption'   => $row['Attachment Caption'],
                    'public'    => $row['Attachment Public'],
                    'name'      => $name,
                    'full_name' => $row['Attachment File Original Name'],
                    'size'      => file_size($row['Attachment File Size']),
                    'thumbnail' => $row['Attachment Thumbnail Image Key'],
                    'icon'      => $icon
                );

            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $attachment_data;
    }

    function delete_attachment($attachment_bridge_key) {

        $sql = sprintf(
            'SELECT `Subject Key`,`Attachment Bridge Key` FROM `Attachment Bridge` WHERE `Attachment Bridge Key`=%d ', $attachment_bridge_key
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {


                $sql = sprintf(
                    'DELETE FROM `Attachment Bridge` WHERE `Attachment Bridge Key`=%d ', $attachment_bridge_key
                );
                $this->db->exec($sql);

                $attachment         = new Attachment(
                    'bridge_key', $row['Attachment Bridge Key']
                );
                $attachment->editor = $this->editor;

                $attachment->delete();


            } else {
                $this->error;
                $this->msg = _('Attachment not found');
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            print "$sql";
            exit;
        }


    }


}

?>
