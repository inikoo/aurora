<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 March 2016 at 21:20:09 GMT+8, Yiwu, China

 Copyright (c) 2016, Inikoo

 Version 3.0

*/

/**
 * Trait AttachmentSubject
 */
trait AttachmentSubject {

    /**
     * @param $raw_data
     *
     * @return \Attachment
     */

    /**
     * @var \PDO
     */
    public $db;


    function add_attachment($raw_data) {
        $data = array(
            'file' => $raw_data['Filename']
        );

        $attach = new Attachment('find', $data, 'create');


        if ($attach->id) {


            $sql = sprintf(
                "INSERT INTO `Attachment Bridge` (`Attachment Key`,`Subject`,`Subject Key`,`Attachment File Original Name`,`Attachment Caption`,`Attachment Subject Type`,`Attachment Public`) VALUES (%d,%s,%d,%s,%s,%s,%s)", $attach->id,
                prepare_mysql($this->get_object_name()), $this->id, prepare_mysql($raw_data['Attachment File Original Name']), prepare_mysql($raw_data['Attachment Caption'], false), prepare_mysql($raw_data['Attachment Subject Type']),
                prepare_mysql((isset($raw_data['Attachment Public']) ? $raw_data['Attachment Public'] : 'No'))


            );


            //print $sql;
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
            $this->update_attachments_data();

        } else {
            $this->error;
            $this->msg = $attach->msg;
        }


        return $attach;
    }

    function update_attachments_data() {

        $sql = sprintf(
            'SELECT count(*) AS num FROM `Attachment Bridge` WHERE `Subject`=%s AND `Subject Key`=%d ', prepare_mysql($this->get_object_name()), $this->id
        );

        $number = 0;

        if ($result = $this->db->query($sql)) {
            if ($row = $result->fetch()) {
                $number = $row['num'];
            }
        }

        $this->fast_update(
            array($this->get_object_name().' Number Attachments' => $number)
        );


    }

    function delete_attachment($attachment_bridge_key) {

        $sql = "SELECT `Subject Key`,`Attachment Bridge Key` FROM `Attachment Bridge` WHERE `Attachment Bridge Key`=? ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(
            array(
                $attachment_bridge_key
            )
        );
        if ($row = $stmt->fetch()) {

            $attachment         = new Attachment('bridge_key', $row['Attachment Bridge Key']);
            $attachment->editor = $this->editor;

            $sql = 'DELETE FROM `Attachment Bridge` WHERE `Attachment Bridge Key`=?';
            $this->db->prepare($sql)->execute(array($attachment_bridge_key));


            $attachment->delete();
        } else {
            $this->error;
            $this->msg = _('Attachment not found');
        }


        $this->update_attachments_data();

    }


}


