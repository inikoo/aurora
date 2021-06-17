<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 June 2021 22:19 MYR , Kuala Lumpur Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/natural_language.php';

require_once 'utils/object_functions.php';

if (!$user->can_view('fulfilment')) {
    echo json_encode(
        array(
            'state' => 405,
            'resp'  => 'Forbidden'
        )
    );
    exit;
}


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

    case 'locations':
        locations(get_table_parameters(), $db, $user, $account);
        break;

    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;

}


function locations($_data, $db, $user, $account) {


    $rtext_label = 'location';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();



    $link = 'fulfilment/locations/'.$_data['parameters']['parent_key'].'/';

    foreach ($db->query($sql) as $data) {


        if ($data['Location Max Weight'] == '' or $data['Location Max Weight'] <= 0) {
            $max_weight = '<span class="super_discreet italic">'._('Unknown').'</span>';
        } else {
            $max_weight = number($data['Location Max Weight'])._('Kg');
        }
        if ($data['Location Max Volume'] == '' or $data['Location Max Volume'] <= 0) {
            $max_vol = '<span class="super_discreet italic">'._('Unknown').'</span>';
        } else {
            $max_vol = number($data['Location Max Volume']).'m³';
        }


        $code = sprintf('<span class="link" onclick="change_view(\'%s/%d\')">%s</span>', $link, $data['Location Key'], $data['Location Code']);
        //$area = sprintf('<span class="link" onclick="change_view(\'warehouse/%d/areas/%d\')">%s</span>', $data['Location Warehouse Key'], $data['Location Warehouse Area Key'], $data['Warehouse Area Code']);

        if ($data['Location Place'] == 'External') {
            $type = ' <i  title="'._('External warehouse').'" style="color:tomato" class="small padding_left_10  fal  fa-garage-car   "></i>';
        } else {
            $type = '';
        }


        $adata[] = array(
            'id'          => (integer)$data['Location Key'],
            'code'        => $code,
            //     'flag'        => ($data['Warehouse Flag Key'] ? sprintf(
            //         '<i id="flag_location_%d" class="fa fa-flag %s button" aria-hidden="true" onclick="show_edit_flag_dialog(this)" location_key="%d" title="%s"></i>', $data['Location Key'], strtolower($data['Warehouse Flag Color']), $data['Location Key'],
            //         $data['Warehouse Flag Label']
            //     ) : '<i id="flag_location_'.$data['Location Key'].'"  class="far fa-flag super_discreet button" aria-hidden="true" onclick="show_edit_flag_dialog(this)" key="" ></i>'),
            //    'flag_key'    => $data['Warehouse Flag Key'],
            //    'area'        => $area,
            'max_weight'  => $max_weight,
            'max_volume'  => $max_vol,
            'type'        => $type,
            'parts'       => number($data['Location Distinct Parts']),
            'stock_value' => money($data['Location Stock Value'], $account->get('Account Currency')),

            // 'used_for'           => $used_for
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
