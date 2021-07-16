<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Refurbished:11 January 2016 at 11:35:38 GMT+8, Kuala Lumpur , Malaysia
 *  Re-Refurbished: Sat, 17 Jul 2021 00:00:00 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

include_once 'utils/prepare_table.php';


class prepare_table_attachments extends prepare_table {

    function __construct($db, $accounts, $user) {
        parent::__construct(...func_get_args());
        $this->record_label = [
            [
                '%s attachment',
                '%s attachments'
            ],
            [
                '%s attachment of %s',
                '%s attachments of %s'
            ],

        ];

        $this->navigation_sql = [
            'name' => 'CONCAT(`Attachment File Original Name`," - ",`Attachment Caption`)',
            'key'  => '`Attachment Bridge Key`'
        ];


    }


    function prepare_table() {

        switch ($this->parameters['parent']) {
            case 'customer':
                $subject = 'Customer';
                break;
            case 'order':
                $subject = 'Order';
                break;
            case 'employee':
                $subject = 'Staff';
                break;
            case 'supplier':
                $subject = 'Supplier';
                break;
            case 'part':
                $subject = 'Part';
                break;
            case 'supplier_delivery':
            case 'supplierdelivery':
                $subject = 'Supplier Delivery';
                break;
            default:
                exit('error parent not set up '.$this->parameters['parent']);
        }

        $this->where = sprintf(
            " where `Subject`=%s and `Subject Key`=%d ", prepare_mysql($subject), $this->parameters['parent_key']
        );


        if (($this->parameters['f_field'] == 'reference') and $this->f_value != '') {

            $this->wheref = sprintf(
                '  and  Attachment Caption`  REGEXP "\\\\b%s" ', addslashes($this->f_value)
            );


        }


        if ($this->sort_key == 'handle') {
            $this->order = '`User Handle`';
        } elseif ($this->sort_key == 'size') {
            $this->order = '`Attachment File Size`';
        } elseif ($this->sort_key == 'file') {
            $this->order = '`Attachment File Original Name`';
        } elseif ($this->sort_key == 'visibility') {
            $this->order = '`Attachment Public`';
        } elseif ($this->sort_key == 'caption') {
            $this->order = '`Attachment Caption`';
        } elseif ($this->sort_key == 'type') {
            $this->order = '`Attachment Subject Type`';
        } elseif ($this->sort_key == 'file_type') {
            $this->order = '`Attachment Type`,`Attachment MIME Type`';
        } elseif ($this->sort_key == 'file') {
            $this->order = '`Attachment File Original Name`';
        } else {
            $this->order = '`Attachment Bridge Key`';
        }


        $this->table = ' `Attachment Bridge` B  left join `Attachment Dimension` A on (A.`Attachment Key`=B.`Attachment Key`) ';

        $this->sql_totals = 'select'." count(Distinct B.`Attachment Key`) as num from $this->table  $this->where  ";


        $this->fields = "`Attachment Bridge Key`,B.`Attachment Key`,`Attachment Subject Type`,`Attachment Caption`,`Attachment File Original Name`,`Attachment Public`,`Attachment MIME Type`,`Attachment Type`,`Attachment File Size`,`Attachment Thumbnail Image Key`";


    }

    function get_data() {


        $this->object = get_object($this->parameters['parent'], $this->parameters['parent_key']);


        $sql = "select $this->fields from $this->table $this->where $this->wheref order by $this->order $this->order_direction limit $this->start_from,$this->number_results";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        while ($data = $stmt->fetch()) {

            if ($data['Attachment Public'] == 'Yes') {
                $visibility = sprintf(
                    '<i title="%s" class="fa fa-eye"></i>', _('Public')
                );
            } else {
                $visibility = sprintf(
                    '<i title="%s" class="fa fa-eye-slash"></i>', _('Private')
                );
            }


            switch ($data['Attachment Subject Type']) {
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
                    $type = $data['Attachment Subject Type'];
                    break;
            }

            switch ($data['Attachment Type']) {
                case 'PDF':
                    $file_type = sprintf(
                        '<i title="%s" class="fa fa-fw fa-file-pdf"></i> %s', $data['Attachment MIME Type'], 'PDF'
                    );

                    break;
                case 'Image':
                    $file_type = sprintf(
                        '<i title="%s" class="fa fa-fw fa-image"></i> %s', $data['Attachment MIME Type'], _('Image')
                    );
                    break;
                case 'Compressed':
                    $file_type = sprintf(
                        '<i title="%s" class="fa fa-fw fa-file-archive"></i> %s', $data['Attachment MIME Type'], _('Compressed')
                    );
                    break;
                case 'Spreadsheet':
                    $file_type = sprintf(
                        '<i title="%s" class="fa fa-fw fa-table"></i> %s', $data['Attachment MIME Type'], _('Spreadsheet')
                    );
                    break;
                case 'Text':
                    $file_type = sprintf(
                        '<i title="%s" class="fal fa-file-alt fa-fw"></i> %s', $data['Attachment MIME Type'], _('Text')
                    );
                    break;
                case 'Word':
                    $file_type = sprintf(
                        '<i title="%s" class="fa fa-fw fa-file-word"></i> %s', $data['Attachment MIME Type'], 'Word'
                    );
                    break;
                default:
                    $file_type = sprintf(
                        '<i title="%s" class="fa fa-fw fa-file"></i> %s', $data['Attachment MIME Type'], _('Other')
                    );
                    break;
            }


            if ($data['Attachment Thumbnail Image Key'] > 0) {
                $preview = sprintf(
                    '<a href="/image.php?id=%d" data-type="image"  data-fancybox="group" data-caption="%s">
                    <img alt="" src="/image.php?id=%d&s=50x50"  style="max-width:100px;height:50px"  />
                 </a>', $data['Attachment Thumbnail Image Key'], $data['Attachment File Original Name'].' '.$data['Attachment Caption'], $data['Attachment Thumbnail Image Key']
                );
            } else {
                $preview = '';
            }


            if ($this->parameters['parent'] == 'supplier_delivery' or $this->parameters['parent'] == 'supplierdelivery') {
                $caption = sprintf(
                    '<span class="link" onclick="change_view(\'%s/%d/delivery/%d/attachment/%d\')">%s</span>', strtolower($this->object->get('Supplier Delivery Parent')), $this->object->get('Supplier Delivery Parent Key'), $this->parameters['parent_key'],
                    $data['Attachment Bridge Key'], $data['Attachment Caption']
                );
            } elseif ($this->parameters['parent'] == 'customer') {
                $caption = sprintf(
                    '<span class="link" onclick="change_view(\'customers/%d/%d/attachment/%d\')">%s</span>', $this->object->get('Store Key'), $this->parameters['parent_key'], $data['Attachment Bridge Key'], $data['Attachment Caption']
                );
            } elseif ($this->parameters['parent'] == 'order') {
                $caption = sprintf(
                    '<span class="link" onclick="change_view(\'orders/%d/%d/attachment/%d\')">%s</span>', $this->object->get('Store Key'), $this->parameters['parent_key'], $data['Attachment Bridge Key'], $data['Attachment Caption']
                );
            } else {
                $caption = sprintf(
                    '<span class="link" onclick="change_view(\'%s/%d/attachment/%d\')">%s</span>', $this->parameters['parent'], $this->parameters['parent_key'], $data['Attachment Bridge Key'], $data['Attachment Caption']
                );
            }


            $this->table_data[] = array(
                'id'         => (integer)$data['Attachment Bridge Key'],
                'caption'    => $caption,
                'size'       => file_size($data['Attachment File Size']),
                'visibility' => $visibility,
                'type'       => $type,
                'file_type'  => $file_type,
                'preview'    => $preview,
                'file'       => sprintf(
                    '<a href="/attachment.php?id=%d" download><i class="fa fa-download"></i></a>  <a href="/attachment.php?id=%d" >%s</a>', $data['Attachment Bridge Key'], $data['Attachment Bridge Key'], $data['Attachment File Original Name']
                ),

                'download' => sprintf(
                    '<a href="/attachment.php?id=%d" download title="%s"><i class="fa fa-download"></i></a>', $data['Attachment Bridge Key'], $data['Attachment File Original Name']
                ),
            );


        }

    }
}