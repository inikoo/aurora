<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 December 2017 at 17:47:32 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/object_functions.php';
require_once 'utils/natural_language.php';
require_once 'utils/parse_natural_language.php';
include_once('utils/website_functions.php');


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

    case 'add_target_to_campaign':
        $data = prepare_values(
            $_REQUEST, array(
                         'parent'     => array('type' => 'string'),
                         'parent_key' => array('type' => 'key'),
                         'target_key' => array('type' => 'key'),
                         'allowance'  => array('type' => 'numeric'),


                     )
        );
        add_target_to_campaign($account, $db, $user, $editor, $data, $smarty);
        break;
    case 'edit_campaign_component_allowance':
        $data = prepare_values(
            $_REQUEST, array(
                         'deal_component_key' => array('type' => 'key'),
                         'allowance'          => array('type' => 'string'),
                         'description'        => array('type' => 'string')


                     )
        );
        edit_campaign_component_allowance($account, $db, $user, $editor, $data, $smarty);
        break;
    case 'edit_campaign_component_status':
        $data = prepare_values(
            $_REQUEST, array(
                         'deal_component_key' => array('type' => 'key'),
                         'status'             => array('type' => 'string'),


                     )
        );
        edit_campaign_component_status($account, $db, $user, $editor, $data, $smarty);
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


function edit_campaign_component_allowance($account, $db, $user, $editor, $data, $smarty) {


    $deal_component         = get_object('DealComponent', $data['deal_component_key']);
    $deal_component->editor = $editor;


    $allowance = floatval($data['allowance']) / 100;


    $deal_component->update(
        array(
            'Deal Component Allowance'       => $allowance,
            'Deal Component Allowance Label' => $data['description']
        )

    );


    switch ($deal_component->get('Deal Component Allowance Type')) {
        case 'Percentage Off':
            $allowance = '<span class="button" key="'.$deal_component->id.'" target="'.$deal_component->get('Deal Component Allowance Label').'" allowance="'.percentage($deal_component->get('Deal Component Allowance'), 1).'" description="'.$deal_component->get(
                    'Deal Component Allowance Label'
                ).'"  onclick="edit_component_allowance(this)"   >'.percentage($deal_component->get('Deal Component Allowance'), 1).'</span>';
            break;

        default:
            $allowance = $deal_component->get('Deal Component Allowance Type').' '.$deal_component->get('Deal Component Allowance');
    }

    $response = array(
        'state'              => 200,
        'allowance'          => $allowance,
        'description'        => $deal_component->get('Deal Component Allowance Label'),
        'deal_component_key' => $deal_component->id
    );
    echo json_encode($response);

}


function edit_campaign_component_status($account, $db, $user, $editor, $data, $smarty) {


    $deal_component         = get_object('DealComponent', $data['deal_component_key']);
    $deal_component->editor = $editor;


    $deal_component->update(
        array(
            'Deal Component Status' => $data['status']
        )

    );

    switch ($deal_component->get('Deal Component Status')) {
        case 'Waiting':
            $status = sprintf(
                '<i class="fa fa-clock-o discreet fa-fw" aria-hidden="true" title="%s" ></i>', _('Waiting')
            );
            break;
        case 'Active':
            $status = sprintf(
                '<i class="fa fa-play success fa-fw" aria-hidden="true" title="%s" ></i>', _('Active')
            );
            break;
        case 'Suspended':
            $status = sprintf(
                '<i class="fa fa-pause error fa-fw" aria-hidden="true" title="%s" ></i>', _('Suspended')
            );
            break;
        case 'Finish':
            $status = sprintf(
                '<i class="fa fa-stop discreet fa-fw" aria-hidden="true" title="%s" ></i>', _('Finished')
            );
            break;
        default:
            $status = $deal_component->get('Deal Component Status');
    }

    $status = '<span status="'.$deal_component->get('Deal Component Status').'" target="'.$deal_component->get('Deal Component Allowance Target Label').'" key="'.$deal_component->get('Deal Component Key').'" class="button" onclick="edit_component_status(this)">'.$status
        .'</span>';


    $response = array(
        'state'  => 200,
        'status' => $status,

        'deal_component_key' => $deal_component->id
    );
    echo json_encode($response);

}


function add_target_to_campaign($account, $db, $user, $editor, $data, $smarty) {


    $campaign         = get_object('Campaign', $data['parent_key']);
    $campaign->editor = $editor;


    $deal = $campaign->get_deals()[0];

    print_r($data);


    $category = get_object('category', $data['target_key']);

    $allowance = $data['target_key'] / 100;

    $component_data = array(
        'Deal Component Terms Type'           => 'Category Quantity Ordered',
        'Deal Component Trigger'              => 'Category',
        'Deal Component Allowance Type'       => 'Percentage Off',
        'Deal Component Allowance Target'     => 'Category',
        'Deal Component Allowance Target Key' => $category->id,
        'Deal Component Allowance Label'      => sprintf(_('%s off'), percentage($allowance, 1)),
        'Deal Component Allowance'            => $allowance,
        'Deal Component Public'               => 'Yes'

    );


    $deal->add_component($component_data);


    $response = array(
        'state'  => 200,

    );
    echo json_encode($response);

}


?>
