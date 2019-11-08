<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Crated: 4 December 2015 at 21:26:14 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';


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
    case 'attachments':
        attachments(get_table_parameters(), $db, $user);
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

/**
 * @param $_data
 * @param $db \PDO
 * @param $user \User
 */
function attachments($_data, $db, $user) {


    include_once 'utils/natural_language.php';
    include_once 'utils/object_functions.php';

    $rtext_label = 'attachment';
    include_once 'prepare_table/init.php';

    if ($_data['parameters']['parent'] == 'supplier_delivery' or  $_data['parameters']['parent']=='supplierdelivery'  ) {

        $delivery = get_object('SupplierDelivery', $_data['parameters']['parent_key']);
    }elseif ($_data['parameters']['parent'] == 'employee') {

        if(!$user->can_edit('Staff')){
            exit;
        }

    }

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();



    foreach ($db->query($sql) as $data) {

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
                    <img  src="/image.php?id=%d&s=50x50"  style="max-width:100px;height:50px"  />
                 </a>', $data['Attachment Thumbnail Image Key'], $data['Attachment File Original Name'].' '.$data['Attachment Caption'], $data['Attachment Thumbnail Image Key']
            );
        } else {
            $preview = '';
        }


        if ($_data['parameters']['parent'] == 'supplier_delivery' or  $_data['parameters']['parent']=='supplierdelivery'  ) {
            $caption = sprintf(
                '<span class="link" onclick="change_view(\'%s/%d/delivery/%d/attachment/%d\')">%s</span>',
                strtolower($delivery->get('Supplier Delivery Parent')), $delivery->get('Supplier Delivery Parent Key'), $_data['parameters']['parent_key'],
                $data['Attachment Bridge Key'], $data['Attachment Caption']
            );
        } else {
            $caption = sprintf(
                '<span class="link" onclick="change_view(\'%s/%d/attachment/%d\')">%s</span>', $_data['parameters']['parent'], $_data['parameters']['parent_key'], $data['Attachment Bridge Key'], $data['Attachment Caption']
            );
        }


        $adata[] = array(
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

    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $adata,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


