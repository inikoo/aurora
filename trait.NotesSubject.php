<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 February 2016 at 20:36:41 GMT+8, Kuala Lumpur, Maysia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/


trait NotesSubject {


    function add_note($note, $details = '', $date = false, $deletable = 'No', $customer_history_type = 'Notes', $author = false, $subject = false, $subject_key = false) {


        list($ok, $note, $details) = $this->prepare_note($note, $details);
        if (!$ok) {
            return;
        }
        $history_data = array(
            'History Abstract'    => $note,
            'History Details'     => $details,
            'Action'              => 'created',
            'Direct Object'       => 'Note',
            'Prepostion'          => 'on',
            'Indirect Object'     => $this->table_name,
            'Indirect Object Key' => (($this->table_name == 'Product' or $this->table_name == 'Supplier Product') ? $this->pid : $this->id)
        );
        if ($author) {
            $history_data['Author Name'] = $author;
        }
        if ($subject) {
            $history_data['Subject']     = $subject;
            $history_data['Subject Key'] = $subject_key;
        }

        if ($date != '') {
            $history_data['Date'] = $date;
        }


        $history_key = $this->add_subject_history(
            $history_data, $force_save = true, $deletable, $customer_history_type, $this->table_name, $this->id
        );

        $this->updated   = true;
        $this->new_value = $history_key;
    }

    function prepare_note($note, $details) {
        $note = _trim($note);
        if ($note == '') {
            $this->msg = _('Empty note');

            return array(
                0,
                0,
                0
            );
        }


        if ($details == '') {


            $details = '';
            if (strlen($note) > 1000) {
                $words   = preg_split('/\s/', $note);
                $len     = 0;
                $note    = '';
                $details = '';
                foreach ($words as $word) {
                    $len += strlen($word);
                    if ($note == '') {
                        $note = $word;
                    } else {
                        if ($len < 1000) {
                            $note .= ' '.$word;
                        } else {
                            $details .= ' '.$word;
                        }

                    }
                }


            }

        }

        return array(
            1,
            $note,
            $details
        );

    }

    function edit_note_strikethrough($note_key, $value) {

        $sql = sprintf(
            "UPDATE `%s History Bridge` SET  `Strikethrough`=%s    WHERE `History Key`=%d AND `%s Key`=%d", addslashes($this->table_name), prepare_mysql($value), $note_key,
            addslashes($this->table_name), $this->id
        );

        $this->db->exec($sql);
        $this->updated = true;

    }

    function edit_note($note_key, $note, $details = '', $change_date = false) {

        if ($note == '') {

            $old_value = $this->get_note($note_key);

            $sql = sprintf(
                "DELETE FROM `%s History Bridge` WHERE `History Key`=%d AND `Deletable`='Yes'", $this->table_name, $note_key
            );

            $prep = $this->db->prepare($sql);
            $prep->execute();
            if ($prep->rowCount()) {

                $this->deleted = true;

                $sql = sprintf(
                    "DELETE FROM `History Dimension` WHERE `History Key`=%d", $note_key
                );
                $this->db->exec($sql);
                $this->deleted_value = $old_value;
                //$this->add_changelog_record($this->table_name.' Other Email', $old_value, '', $options, $this->table_name, $this->id, 'removed');

            } else {

            }

        } else {


            list($ok, $note, $details) = $this->prepare_note($note, $details);
            if (!$ok) {
                $this->error = true;

                return;
            }
            $sql = sprintf(
                "UPDATE `History Dimension` SET `History Abstract`=%s ,`History Details`=%s WHERE `History Key`=%d AND `Indirect Object`=%s AND `Indirect Object Key`=%s ", prepare_mysql($note),
                prepare_mysql($details),

                $note_key, prepare_mysql($this->table_name), $this->id
            );

            $prep = $this->db->prepare($sql);
            $prep->execute();
            if ($prep->rowCount()) {
                if ($change_date == 'update_date') {
                    $sql = sprintf(
                        "UPDATE `History Dimension` SET `History Date`=%s WHERE `History Key`=%d  ", prepare_mysql(gmdate("Y-m-d H:i:s")), $note_key
                    );
                    $this->db->exec($sql);
                }

                $this->updated   = true;
                $this->new_value = $note;
            }
        }

    }

    function get_note($note_key) {
        $note = '';
        $sql  = sprintf(
            'SELECT `History Abstract` FROM  `History Dimension`  WHERE `History Key`=%d AND `Indirect Object`=%s AND `Indirect Object Key`=%s', $note_key, prepare_mysql($this->table_name), $this->id
        );
        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $note = $row['History Abstract'];
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }

        return $note;
    }


}


?>
