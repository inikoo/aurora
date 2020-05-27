<?php
set_time_limit(300);
chdir('../');
require_once 'vendor/autoload.php';
require_once 'utils/object_functions.php';
require_once 'utils/common.pick_aid.php';
require_once 'common.php';

$ids = [];
if (!empty($_REQUEST['id'])) {
    $ids[] = $_REQUEST['id'];
} elseif (!empty($_REQUEST['ids'])) {
    $ids = preg_split('/\,/', $_REQUEST['ids']);
} else {
    exit("no id");
}

if (isset($_REQUEST['with_labels'])) {
    $type = 'with_labels';
} else {
    $type = 'normal';
}

$mpdf = get_pick_aid_mpdf($type);
foreach ($ids as $_key => $id) {

    $delivery_note = get_object('DeliveryNote', $id);
    if (!$delivery_note->id) {
        continue;
    }

    $mpdf->SetTitle(_('Picking Aid').' '.$delivery_note->data['Delivery Note ID']);
    $mpdf->SetAuthor('Aurora systems');

    $html = get_picking_aid_html_for_pdf($type, $delivery_note, $db, $smarty, $account);
    if ($_key > 0) {
        $mpdf->AddPage('P', '', 1);
    }

    $mpdf->WriteHTML($html);
}

if (count($ids) > 1) {
    $mpdf->Output('pickings.pdf', 'I');
} else {
    $mpdf->Output($delivery_note->get('Delivery Note ID').'_picking.pdf', 'I');
}


