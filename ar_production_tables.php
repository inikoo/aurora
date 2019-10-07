<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 December 2015 at 23:56:43 CET, Barcelona Airport, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';

/*
if (!$user->can_view('staff')) {
    echo json_encode(
        array(
            'state' => 405,
            'resp'  => 'Forbidden'
        )
    );
    exit;
}
*/

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
    case 'bill_of_materials':
        bill_of_materials(get_table_parameters(), $db, $user, $account);
        break;
    case 'suppliers':
        suppliers(get_table_parameters(), $db, $user, $account);
        break;
    case 'production_parts':
        production_parts(get_table_parameters(), $db, $user, $account);
        break;
    case 'materials':
        materials(get_table_parameters(), $db, $user, $account);
        break;
    case 'operatives':
        operatives(get_table_parameters(), $db, $user);
        break;
    case 'manufacture_tasks':
        manufacture_tasks(get_table_parameters(), $db, $user, $account);
        break;
    case 'production_deliveries':
        production_deliveries(get_table_parameters(), $db, $user, $account);
        break;
    case 'production_orders':
        production_orders(get_table_parameters(), $db, $user, $account);
        break;
    case 'deliveries_with_part':
        production_deliveries_with_part(get_table_parameters(), $db, $user, $account);
        break;
    case 'orders_with_part':
        production_orders_with_part(get_table_parameters(), $db, $user, $account);
        break;
    case 'replenishments':
        replenishments(get_table_parameters(), $db, $user, $account);
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


function replenishments($_data, $db, $user, $account) {


    $rtext_label = 'replenishment';
    include_once 'prepare_table/init.php';

    $db->exec('SET SESSION group_concat_max_len = 1000000;');

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    foreach ($db->query($sql) as $data) {

        $locations_data = preg_split('/,/', $data['location_data']);


        $stock = '<div border=0 style="xwidth:150px">';

        foreach ($locations_data as $raw_location_data) {
            if ($raw_location_data != '') {
                $_locations_data = preg_split('/\:/', $raw_location_data);
                if ($_locations_data[0] != $data['Location Key']) {
                    $stock .= '<div style="clear:both">';
                    $stock .= '<div style="float:left;min-width:100px;">
<span class="link"  onClick="change_view(\'locations/'.$data['Location Warehouse Key'].'/'.$_locations_data[0].'\')" >'.$_locations_data[1].'</span>
</div><div style="float:left;min-width:100px;text-align:right">'.number($_locations_data[3]).'</div>';
                    $stock .= '</div>';
                }
            }
        }
        $stock .= '</div>';


        $adata[] = array(
            'id'                    => (integer)$data['Location Key'],
            'location'              => ($data['Warehouse Flag Key'] ? sprintf(
                    '<i class="fa fa-flag %s" aria-hidden="true" title="%s"></i>', strtolower($data['Warehouse Flag Color']), $data['Warehouse Flag Label']
                ) : '<i class="far fa-flag super_discreet" aria-hidden="true"></i>').' <span class="link" onClick="change_view(\'locations/'.$data['Location Warehouse Key'].'/'.$data['Location Key'].'\')">'.$data['Location Code'].'</span>',
            'part'                  => sprintf('<span class="link" onCLick="change_view(\'part/%d\')" >%s</span>', $data['Part SKU'], $data['Part Reference']),
            'other_locations_stock' => $stock,
            'description'           => $data['Part Package Description'],
            'quantity'              => number($data['Quantity On Hand']),
            'ordered_quantity'      => number($data['ordered_quantity']),
            'effective_stock'       => number($data['effective_stock']),
            'recommended_quantity'  => ' <span class="padding_left_5">(<span style="display: inline-block;min-width: 20px;text-align: center">'.number($data['Minimum Quantity']).'</span>,<span style="display: inline-block;min-width: 25px;text-align: center">'.number(
                    $data['Maximum Quantity']
                ).'</span>)</span>'

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


function operatives($_data, $db, $user) {

    $rtext_label = 'operative';
    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    $adata = array();

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            switch ($data['Staff Type']) {
                case 'Employee':
                    $type = _('Employee');
                    break;
                case 'Volunteer':
                    $type = _('Volunteer');
                    break;
                case 'TemporalWorker':
                    $type = _("Temporal worker");
                    break;
                case 'WorkExperience':
                    $type = _("Work experience");
                    break;
                default:
                    $type = $data['Staff Type'];
                    break;
            }

            $adata[] = array(
                'id'           => (integer)$data['Staff Key'],
                'formatted_id' => sprintf("%04d", $data['Staff Key']),
                'payroll_id'   => $data['Staff ID'],
                'name'         => $data['Staff Name'],
                'code'         => $data['Staff Alias'],
                'code_link'    => $data['Staff Alias'],
                'type'         => $type,
                'supervisors'  => $data['supervisors']
            );

        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
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

function manufacture_tasks($_data, $db, $user, $account) {

    $rtext_label = 'manufacture task';
    include_once 'prepare_table/init.php';

    $sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

    $adata = array();

    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            $adata[] = array(
                'id'        => (integer)$data['Manufacture Task Key'],
                'name'      => $data['Manufacture Task Name'],
                'work_cost' => ($data['Manufacture Task Work Cost'] != '' ? money(
                    $data['Manufacture Task Work Cost'], $account->get('Currency Code')
                ) : _('NA')),

            );

        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
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

function suppliers($_data, $db, $user, $account) {





        if (!$user->can_view('production')) {
            echo json_encode(
                array(
                    'state' => 405,
                    'resp'  => 'Forbidden'
                )
            );
            exit;
        }

    


    $rtext_label = 'supplier';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    if ($result = $db->query($sql)) {

        foreach ($result as $data) {


            if ($_data['parameters']['parent'] == 'agent') {
                $operations = sprintf(
                    '<i agent_key="%d" supplier_key="%d"  class="fa fa-unlink button" aria-hidden="true"  onClick="bridge_supplier(this)" ></i>', $_data['parameters']['parent_key'], $data['Supplier Key']
                );
            } else {
                $operations = '';
            }


            $associated = sprintf(
                '<i key="%d" class="fa fa-fw fa-link button" aria-hidden="true" onClick="edit_category_subject(this)" ></i>', $data['Supplier Key']
            );

            $adata[] = array(
                'id'         => (integer)$data['Supplier Key'],
                'operations' => $operations,
                'associated' => $associated,

                'code'                  => $data['Supplier Code'],
                'name'                  => $data['Supplier Name'],
                'supplier_parts'        => number(
                    $data['Supplier Number Parts']
                ),
                'active_supplier_parts' => number(
                    $data['Supplier Number Active Parts']
                ),

                'surplus'      => sprintf(
                    '<span class="%s" title="%s">%s</span>', (ratio(
                    $data['Supplier Number Surplus Parts'], $data['Supplier Number Parts']
                ) > .75
                    ? 'error'
                    : (ratio(
                        $data['Supplier Number Surplus Parts'], $data['Supplier Number Parts']
                    ) > .5 ? 'warning' : '')), percentage(
                        $data['Supplier Number Surplus Parts'], $data['Supplier Number Parts']
                    ), number($data['Supplier Number Surplus Parts'])
                ),
                'optimal'      => sprintf(
                    '<span  title="%s">%s</span>', percentage(
                    $data['Supplier Number Optimal Parts'], $data['Supplier Number Parts']
                ), number($data['Supplier Number Optimal Parts'])
                ),
                'low'          => sprintf(
                    '<span class="%s" title="%s">%s</span>', (ratio(
                    $data['Supplier Number Low Parts'], $data['Supplier Number Parts']
                ) > .5
                    ? 'error'
                    : (ratio(
                        $data['Supplier Number Low Parts'], $data['Supplier Number Parts']
                    ) > .25 ? 'warning' : '')), percentage(
                        $data['Supplier Number Low Parts'], $data['Supplier Number Parts']
                    ), number($data['Supplier Number Low Parts'])
                ),
                'critical'     => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Supplier Number Critical Parts'] == 0
                    ? ''
                    : (ratio(
                        $data['Supplier Number Critical Parts'], $data['Supplier Number Parts']
                    ) > .25 ? 'error' : 'warning')), percentage(
                        $data['Supplier Number Critical Parts'], $data['Supplier Number Parts']
                    ), number($data['Supplier Number Critical Parts'])
                ),
                'out_of_stock' => sprintf(
                    '<span class="%s" title="%s">%s</span>', ($data['Supplier Number Out Of Stock Parts'] == 0
                    ? ''
                    : (ratio(
                        $data['Supplier Number Out Of Stock Parts'], $data['Supplier Number Parts']
                    ) > .10 ? 'error' : 'warning')), percentage(
                        $data['Supplier Number Out Of Stock Parts'], $data['Supplier Number Parts']
                    ), number($data['Supplier Number Out Of Stock Parts'])
                ),


                'location'  => $data['Supplier Location'],
                'email'     => $data['Supplier Main Plain Email'],
                'telephone' => $data['Supplier Preferred Contact Number Formatted Number'],
                'contact'   => $data['Supplier Main Contact Name'],
                'company'   => $data['Supplier Company Name'],


            );


        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
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

function production_parts($_data, $db, $user, $account) {


    include_once 'utils/currency_functions.php';


    $rtext_label = 'production part';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    if ($result = $db->query($sql)) {


        foreach ($result as $data) {


            switch ($data['Part Stock Status']) {
                case 'Surplus':
                    $stock_status = '<i class="fa  fa-plus-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Optimal':
                    $stock_status = '<i class="fa fa-check-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Low':
                    $stock_status = '<i class="fa fa-minus-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Critical':
                    $stock_status = '<i class="fa error fa-minus-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Out_Of_Stock':
                    $stock_status = '<i class="fa error fa-ban fa-fw" aria-hidden="true"></i>';
                    if ($data['Supplier Part Status'] == 'Discontinued') {

                    }

                    break;
                case 'Error':
                    $stock_status = '<i class="fa fa-question-circle error fa-fw" aria-hidden="true"></i>';
                    break;
                default:
                    $stock_status = $data['Part Stock Status'];
                    break;
            }

            $stock = number(floor($data['Part Current On Hand Stock']))." $stock_status";


            switch ($data['Supplier Part Status']) {
                case 'Available':
                    $status = sprintf(
                        '<i class="fa fa-stop success" title="%s"></i>', _('Available')
                    );
                    break;
                case 'NoAvailable':
                    $status = sprintf(
                        '<i class="fa fa-stop warning" title="%s"></i>', _('No available')
                    );

                    break;
                case 'Discontinued':
                    $status = sprintf(
                        '<i class="fa fa-ban error" title="%s"></i>', _('Discontinued')
                    );

                    if ($data['Part Current On Hand Stock'] == 0) {
                        $stock = '';
                    } else {
                        $stock = '<span class="error">'.number(floor($data['Part Current On Hand Stock'])).'</span>';

                    }


                    break;
                default:
                    $status = $data['Supplier Part Status'];
                    break;
            }


            $description = $data['Part Package Description'].'<div class="italic very_discreet">('.sprintf(_('%s units per SKO'), $data['Part Units per Package']).')</div>';

            $adata[] = array(
                'id'        => (integer)$data['Supplier Part Key'],
                'reference' => sprintf('<span class="link" onclick="change_view(\'/production/%d/part/%d\')">%s</span>', $data['Supplier Part Supplier Key'], $data['Supplier Part Key'], $data['Supplier Part Reference']),


                'description' => $description,
                'status'      => $status,
                'cost'        => money(
                    $data['Supplier Part Unit Cost'], $data['Supplier Part Currency Code']
                ),

                'packing'    => '
				   <div style="float:right;min-width:30px;;text-align:right" title="'._('Units per part').'"> <span class="strong" >'.($data['Part Units Per Package'] * $data['Supplier Part Packages Per Carton'].'</span></div>
				   <div style="float:right;min-width:40px;text-align:center;"><i class="far fa-equals"></i></div>
				<div style="float:right;min-width:20px;text-align:right;" title="'._('Packages per part').'"><span>'.$data['Supplier Part Packages Per Carton'].'</span></div>
				<div style="float:right;min-width:40px;text-align:center;"><i class="far fa-times"></i></div>
				<div style="float:right;min-width:20px;text-align:right" title="'._('Packed in (Units per packages)').'"><span>'.$data['Part Units Per Package'].'</span></div>
				 '),
                'stock'      => $stock,
                'components' => number($data['Part Number Components']),
                'tasks'      => number($data['Part Number Production Tasks'])


            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        print $sql;
        exit;
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


function bill_of_materials($_data, $db, $user, $account) {


    include_once 'utils/currency_functions.php';


    $rtext_label = 'components';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    global $session;

    $production_key = $session->get('current_production');


    if ($result = $db->query($sql)) {


        foreach ($result as $data) {


            switch ($data['Part Stock Status']) {
                case 'Surplus':
                    $stock_status = '<i class="fa  fa-plus-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Optimal':
                    $stock_status = '<i class="fa fa-check-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Low':
                    $stock_status = '<i class="fa fa-minus-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Critical':
                    $stock_status = '<i class="fa error fa-minus-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Out_Of_Stock':
                    $stock_status = '<i class="fa error fa-ban fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Error':
                    $stock_status = '<i class="fa fa-question-circle error fa-fw" aria-hidden="true"></i>';
                    break;
                default:
                    $stock_status = $data['Part Stock Status'];
                    break;
            }


            $qty_units = $data['Bill of Materials Quantity'] * $data['Part Units per Package'];
            $qty_edit  = sprintf(
                '<span    data-settings=\'{"field": "Units", "item_key":%d  }\'   >
            <input class="bill_of_materials_item width_50" type="number" style="text-align: center" value="%s" ovalue="%s"> 
            <i onClick="save_bill_of_materials_item_change(this)" class="fa save  fa-cloud fa-fw button" ></i></span>', $data['Part SKU'], $qty_units, $qty_units
            );


            $adata[] = array(
                'id'        => (integer)$data['Part SKU'],
                'reference' => sprintf('<span class="link" onclick="change_view(\'/production/%d/materials/%d\')">%s</span>', $production_key, $data['Part SKU'], $data['Part Reference']),


                'description' => $data['Part Recommended Product Unit Name'].' <span class="italic very_discreet">('.sprintf(_('%s units per SKO'), $data['Part Units per Package']).')</span>',
                'cost_unit'   => money($data['Part Cost in Warehouse'] * $data['Bill of Materials Quantity'], $account->get('Account Currency')),
                'qty'         => number($qty_units),
                'qty_edit'    => $qty_edit,
                'qty_skos'    => number($data['Bill of Materials Quantity'], 4),

                'stock'                => number(floor($data['Part Current On Hand Stock'])),
                'stock_status'         => $stock_status,
                'available_to_make_up' => '<span class="item_available_to_make_up">'.number($data['Part Current On Hand Stock'] / $data['Bill of Materials Quantity'], 0).'</span>>'


            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        print $sql;
        exit;
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


function materials($_data, $db, $user, $account) {


    include_once 'utils/currency_functions.php';


    $rtext_label = 'components';
    include_once 'prepare_table/init.php';

    $sql   = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $adata = array();


    $production_key = $_data['parameters']['parent_key'];


    if ($result = $db->query($sql)) {


        foreach ($result as $data) {


            switch ($data['Part Stock Status']) {
                case 'Surplus':
                    $stock_status = '<i class="fa  fa-plus-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Optimal':
                    $stock_status = '<i class="fa fa-check-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Low':
                    $stock_status = '<i class="fa fa-minus-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Critical':
                    $stock_status = '<i class="fa error fa-minus-circle fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Out_Of_Stock':
                    $stock_status = '<i class="fa error fa-ban fa-fw" aria-hidden="true"></i>';
                    break;
                case 'Error':
                    $stock_status = '<i class="fa fa-question-circle error fa-fw" aria-hidden="true"></i>';
                    break;
                default:
                    $stock_status = $data['Part Stock Status'];
                    break;
            }


            $adata[] = array(
                'id'        => (integer)$data['Part SKU'],
                'reference' => sprintf('<span class="link" onclick="change_view(\'/part/%d\')">%s</span>', $data['Part SKU'], $data['Part Reference']),


                'description' => $data['Part Recommended Product Unit Name'].' <span class="italic very_discreet">('.sprintf(_('%s units per SKO'), $data['Part Units per Package']).')</span>',

                'stock_units'      => number(floor($data['Part Current On Hand Stock'] * $data['Part Units per Package'])),
                'stock_status'     => $stock_status,
                'production_links' => number($data['Part Number Production Links'])


            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        print $sql;
        exit;
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


function production_deliveries($_data, $db, $user) {
    $rtext_label = 'production sheet';


    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {

            switch ($data['Supplier Delivery State']) {
                case 'InProcess':
                case 'Consolidated':
                case 'Dispatched':
                case 'Received':
                    $state = sprintf('%s', _('Items manufactured'));
                    break;

                case 'Checked':
                    $state = sprintf('%s', _('Checked'));
                    break;
                case 'Placed':
                case 'InvoiceChecked':
                    $state = _('Booked in');
                    break;
                case 'Costing':
                    $state = _('Booked in').', '._('checking costing');
                    break;

                case 'Cancelled':
                    $state = sprintf('%s', _('Cancelled'));
                    break;

                default:
                    $state = $data['Supplier Delivery State'];
                    break;
            }

            $table_data[] = array(
                'id' => (integer)$data['Supplier Delivery Key'],

                'date'      => strftime("%e %b %Y", strtotime($data['Supplier Delivery Creation Date'].' +0:00')),
                'last_date' => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Supplier Delivery Last Updated Date'].' +0:00')),


                'public_id' => sprintf(
                    '<span class="link" onclick="change_view(\'/production/%d/delivery/%d\')" >%s</span>  ', $data['Supplier Delivery Parent Key'], $data['Supplier Delivery Key'], $data['Supplier Delivery Public ID']
                ),


                'state'        => $state,
                'total_amount' => money($data['Supplier Delivery Total Amount'], $data['Supplier Delivery Currency Code'])


            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $table_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function production_orders($_data, $db, $user, $account) {

  
    $rtext_label = 'job order';


    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            switch ($data['Purchase Order State']) {


                case 'InProcess':
                    $state = _('Planning');
                    break;
                case 'Submitted':
                    $state = _('Manufacturing');
                    break;
                case 'Inputted':
                case 'Dispatched':
                case 'Received':
                    $state = _('In quality control');
                    break;
                case 'Checked':
                    $state = _('In quality control').' ('._('Check done').')';
                    break;
                case 'Placed':
                case 'InvoiceChecked':

                $state = _('Booked in');
                    break;
                case 'Costing':

                $state = _('Booked in').' ('._('Review costing').')';
                    break;

                case 'Cancelled':
                    $state = _('Cancelled');
                    break;
                default:
                    $state = $data['Purchase Order State'];
                    break;
            }


            $table_data[] = array(
                'id'           => (integer)$data['Purchase Order Key'],
                'public_id'    => sprintf(
                    '<span class="link" onclick="change_view(\'production/%d/order/%d\')" >%s</span>  ', $data['Purchase Order Parent Key'], $data['Purchase Order Key'],
                    ($data['Purchase Order Public ID'] == '' ? '<i class="fa fa-exclamation-circle error"></i> <span class="very_discreet italic">'._('empty').'</span>' : $data['Purchase Order Public ID'])
                ),
                'date'         => strftime("%e %b %Y", strtotime($data['Purchase Order Creation Date'].' +0:00')),
                'last_date'    => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Purchase Order Last Updated Date'].' +0:00')),
                'state'        => $state,
                'total_amount' => money($data['Purchase Order Total Amount'], $data['Purchase Order Currency Code']),


            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $table_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function production_deliveries_with_part($_data, $db, $user) {
    $rtext_label = 'production sheet';


    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();

    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            switch ($data['Purchase Order Transaction State']) {


                case 'InProcess':
                    $state = _('Planning');
                    break;
                case 'Submitted':
                    $state = _('Manufacturing');
                    break;
                case 'Inputted':
                case 'Dispatched':
                case 'Received':
                    $state = _('In quality control');
                    break;
                case 'Checked':
                    $state = _('In quality control').' ('._('Check done').')';
                    break;
                case 'Placed':
                case 'InvoiceChecked':

                    $state = _('Booked in');
                    break;
                case 'Costing':

                    $state = _('Booked in').' ('._('Review costing').')';
                    break;

                case 'Cancelled':
                    $state = _('Cancelled');
                    break;
                default:
                    $state = $data['Purchase Order Transaction State'];
                    break;
            }



            $table_data[] = array(
                'id' => (integer)$data['Supplier Delivery Key'],

                'date'      => strftime("%e %b %Y", strtotime($data['Supplier Delivery Creation Date'].' +0:00')),
                'last_date' => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Supplier Delivery Last Updated Date'].' +0:00')),


                'public_id' => sprintf(
                    '<span class="link" onclick="change_view(\'/production/%d/delivery/%d\')" >%s</span>  ', $data['Supplier Delivery Parent Key'], $data['Supplier Delivery Key'], $data['Supplier Delivery Public ID']
                ),


                'state'        => $state,
                //'total_amount' => money($data['Supplier Delivery Total Amount'], $data['Supplier Delivery Currency Code'])


            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $table_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}


function production_orders_with_part($_data, $db, $user, $account) {

    if (!$user->can_view('production')) {
        echo json_encode(
            array(
                'state' => 405,
                'resp'  => 'Forbidden'
            )
        );
        exit;
    }


    $rtext_label = 'job order';


    include_once 'prepare_table/init.php';

    $sql        = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
    $table_data = array();


    if ($result = $db->query($sql)) {
        foreach ($result as $data) {


            switch ($data['Purchase Order Transaction State']) {


                case 'InProcess':
                    $state = _('Planning');
                    break;
                case 'Submitted':
                    $state = _('Manufacturing');
                    break;
                case 'Inputted':
                case 'Dispatched':
                case 'Received':
                    $state = _('In quality control');
                    break;
                case 'Checked':
                    $state = _('In quality control').' ('._('Check done').')';
                    break;
                case 'Placed':
                case 'InvoiceChecked':

                    $state = _('Booked in');
                    break;
                case 'Costing':

                    $state = _('Booked in').' ('._('Review costing').')';
                    break;

                case 'Cancelled':
                    $state = _('Cancelled');
                    break;
                default:
                    $state = $data['Purchase Order Transaction State'];
                    break;
            }


            if($data['Purchase Order State']=='InProccess'){
                $qty_units=number($data['Purchase Order Ordering Units']);
                $qty_skos=number($data['Purchase Order Ordering Units']/$data['Part Units Per Package']);

            }else{
                $qty_units=number($data['Purchase Order Submitted Units']);
                $qty_skos=number($data['Purchase Order Submitted Units']/$data['Purchase Order Submitted Units Per SKO']);

            }



            $table_data[] = array(
                'id'           => (integer)$data['Purchase Order Key'],
                'public_id'    => sprintf(
                    '<span class="link" onclick="change_view(\'production/%d/order/%d\')" >%s</span>  ', $data['Purchase Order Parent Key'], $data['Purchase Order Key'],
                    ($data['Purchase Order Public ID'] == '' ? '<i class="fa fa-exclamation-circle error"></i> <span class="very_discreet italic">'._('empty').'</span>' : $data['Purchase Order Public ID'])
                ),
                'date'         => strftime("%e %b %Y", strtotime($data['Purchase Order Creation Date'].' +0:00')),
                'last_date'    => strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Purchase Order Last Updated Date'].' +0:00')),
                'state'        => $state,
                'qty_units'=>$qty_units,
                'qty_skos'=>$qty_skos

                //'total_amount' => money($data['Purchase Order Total Amount'], $data['Purchase Order Currency Code']),


            );


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $response = array(
        'resultset' => array(
            'state'         => 200,
            'data'          => $table_data,
            'rtext'         => $rtext,
            'sort_key'      => $_order,
            'sort_dir'      => $_dir,
            'total_records' => $total

        )
    );
    echo json_encode($response);
}



