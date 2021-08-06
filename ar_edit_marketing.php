<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 December 2017 at 17:47:32 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once 'utils/ar_common.php';
require_once 'utils/object_functions.php';
require_once 'utils/natural_language.php';
require_once 'utils/parse_natural_language.php';


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
                         'terms'      => array(
                             'type'     => 'numeric',
                             'optional' => true
                         ),

                     )
        );
        add_target_to_campaign($account, $db, $user, $editor, $data, $smarty);
        break;
    case 'edit_campaign_order_recursion_data':
        $data = prepare_values(
            $_REQUEST, array(
                         'deal_component_key' => array('type' => 'key'),
                         'allowance'          => array('type' => 'string'),
                         'description'        => array('type' => 'string'),
                         'name'               => array('type' => 'string')


                     )
        );
        edit_campaign_order_recursion_data($account, $db, $user, $editor, $data, $smarty);
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
    case 'edit_campaign_components_status':
        $data = prepare_values(
            $_REQUEST, array(
                         'deal_key' => array('type' => 'key'),
                         'status'   => array('type' => 'string'),


                     )
        );
        edit_campaign_components_status($account, $db, $user, $editor, $data, $smarty);
        break;
    case 'edit_bulk_deal_data':
        $data = prepare_values(
            $_REQUEST, array(
                         'deal_component_key'     => array('type' => 'key'),
                         'allowance'              => array('type' => 'string'),
                         'terms'                  => array('type' => 'string'),
                         'description_terms'      => array('type' => 'string'),
                         'description_allowances' => array('type' => 'string')


                     )
        );
        edit_bulk_deal_data($account, $db, $user, $editor, $data, $smarty);
        break;
    case 'set_mailing_list':
        $data = prepare_values(
            $_REQUEST, array(
                         'object'      => array('type' => 'string'),
                         'key'         => array('type' => 'string'),
                         'fields_data' => array('type' => 'json array')
                     )
        );

        set_mailing_list($account, $db, $user, $editor, $data, $smarty);
        break;

    case 'set_second_wave':
        $data = prepare_values(
            $_REQUEST, array(
                         'key'             => array('type' => 'key'),
                         'second_wave' => array('type' => 'string'),


                     )
        );
        set_second_wave($account, $db, $user, $editor, $data);
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

function set_second_wave($account, $db, $user, $editor, $data) {


    $mailshot = get_object('Mailshot', $data['key']);


    $second_wave = 'No';
    if ($data['second_wave']=='Yes') {
        $second_wave = 'Yes';
    }


    if ($mailshot->can_create_second_wave() ) {


        $mailshot->fast_update(
            ['Email Campaign Wave Type'=> $second_wave]);


        if($mailshot->get('Email Campaign State')=='Sent'){
            $mailshot->fast_update(
                [
                    'Email Campaign Second Wave Date' => $mailshot->get_second_wave_date()
                ]
            );
        }



        $response = array(
            'state'       => 200,
            'second_wave' => $second_wave,
            'second_wave_date'=>$mailshot->get('Second Wave Formatted Date')
        );
        echo json_encode($response);
        exit;

    }

    $response = array(
        'state' => 400,
        'msg'   => 'Error'
    );
    echo json_encode($response);
    exit;

}



function edit_campaign_order_recursion_data($account, $db, $user, $editor, $data, $smarty) {


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
                '<i class="far fa-clock discreet fa-fw" aria-hidden="true" title="%s" ></i>', _('Waiting')
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


    $category = get_object('category', $data['target_key']);


    if ($campaign->get('Deal Campaign Code') == 'OR') {


        $deal = $campaign->get_deals()[0];


        $allowance = $data['allowance'] / 100;


        $component_data = array(
            'Deal Component Trigger'               => 'Category',
            'Deal Component Allowance Type'        => 'Percentage Off',
            'Deal Component Allowance Target'      => 'Category',
            'Deal Component Allowance Target Key'  => $category->id,
            'Deal Component Allowance Label'       => sprintf(_('%s off'), percentage($allowance, 1)),
            'Deal Component Allowance'             => $allowance,
            'Deal Component Allowance Target Type' => 'Items',
        );


        $component_data['Deal Component Allowance Target Label'] = $category->get('Code');
        $deal->add_component($component_data);


    } elseif ($campaign->get('Deal Campaign Code') == 'VL') {

        $allowance = $data['allowance'] / 100;


        $qty       = $data['terms'];
        $off       = sprintf(_('%s off'), percentage($allowance, 1, 0));
        $off_ratio = $allowance;

        $deal_new_data = array(
            'Deal Name'        => sprintf(_('Bulk discount %s'), $category->get('Code')),
            'Deal Name Label'  => _('Volume discount'),
            'Deal Term Label'  => "order $qty or more",
            'Deal Trigger'     => 'Category',
            'Deal Icon'        => '',
            'Deal Trigger Key' => $category->id,
            'Deal Terms Type'  => 'Category Quantity Ordered',
            'Deal Terms'       => $qty,
            'Deal Begin Date'  => gmdate('Y-m-d H:i:s')


        );


        $new_component_data = array(

            'Deal Component Allowance Type'         => 'Percentage Off',
            'Deal Component Allowance Target'       => 'Category',
            'Deal Component Allowance Target Key'   => $category->id,
            'Deal Component Allowance'              => $off_ratio,
            'Deal Component Allowance Label'        => $off,
            'Deal Component Allowance Target Label' => $category->get('Code'),
            'Deal Component Allowance Target Type'  => 'Items',

        );

        //print_r($deal_new_data);
        //print_r($new_component_data);

        $object = $campaign->create_deal($deal_new_data, $new_component_data);

        //  print_r($campaign);


    } else {

        $response = array(
            'state' => 400,
            'msg'   => 'no_valid_campaign'

        );
        echo json_encode($response);
        exit;
    }


    $response = array(
        'state' => 200,

    );
    echo json_encode($response);

}


function edit_bulk_deal_data($account, $db, $user, $editor, $data, $smarty) {


    $deal_component         = get_object('DealComponent', $data['deal_component_key']);
    $deal_component->editor = $editor;


    $allowance = floatval($data['allowance']) / 100;
    $terms     = floatval($data['terms']);


    $deal_component->update(
        array(
            'Deal Component Allowance'       => $allowance,
            'Deal Component Allowance Label' => $data['description_allowances'],
            'Deal Terms'                     => $terms,

        )

    );

    $deal = get_object('Deal', $deal_component->get('Deal Component Deal Key'));

    $deal->editor = $editor;
    $deal->update(
        array(
            'Deal Term Label' => $data['description_terms'],

        )

    );


    if (strlen(strip_tags($deal->get('Deal Term Allowances Label'))) > 75) {
        $description_class = 'super_small';
    } elseif (strlen(strip_tags($deal->get('Deal Term Allowances Label'))) > 60) {
        $description_class = 'very_small';
    } elseif (strlen(strip_tags($deal->get('Deal Term Allowances Label'))) > 50) {
        $description_class = 'small';
    } else {
        $description_class = '';
    }


    $description = sprintf(
        '<span  class="%s button"  key="%d" target="%s" terms="%d"  allowance="%s" description_terms="%s" description_allowances="%s"  onclick="edit_volume_deal(this)"  title="%s">%s</span>', $description_class, $deal_component->id,
        $deal_component->get('Deal Component Allowance Target Label'), $deal->get('Deal Terms'), percentage($deal_component->get('Deal Component Allowance'), 1), $deal->get('Deal Term Label'), $deal_component->get('Deal Component Allowance Label'),
        strip_tags($deal->get('Deal Term Label').' '.$deal_component->get('Deal Component Allowance Label')), $deal->get('Deal Term Allowances Label')
    );


    $response = array(
        'state'              => 200,
        'description'        => $description,
        'deal_component_key' => $deal_component->id
    );
    echo json_encode($response);

}


function edit_campaign_components_status($account, $db, $user, $editor, $data, $smarty) {


    $deal         = get_object('Deal', $data['deal_key']);
    $deal->editor = $editor;


    if ($data['status'] == 'Active') {
        $deal_components = $deal->get_deal_components('objects', 'Suspended');
    } elseif ($data['status'] == 'Suspended') {
        $deal_components = $deal->get_deal_components('objects', 'Active');
    } else {
        $response = array(
            'state' => 400,
            'msg'   => 'wrong status '.$data['status']
        );
        echo json_encode($response);
        exit;
    }

    foreach ($deal_components as $deal_component) {
        $deal_component->update(
            array(
                'Deal Component Status' => $data['status']
            )

        );

    }


    $response = array(
        'state' => 200,

    );
    echo json_encode($response);

}


function set_mailing_list($account, $db, $user, $editor, $data, $smarty) {


    include_once 'utils/parse_customer_list.php';


    $mailshot = get_object('Mailshot', $data['key']);

    $data['fields_data']['store_key'] = $mailshot->get('Store Key');


    if (isset($data['fields_data']['Email Campaign Name'])) {
        $mailshot->fast_update(
            array(
                'Email Campaign Name' => $data['fields_data']['Email Campaign Name']
            )
        );
        unset($data['fields_data']['Email Campaign Name']);
    }


    $metadata = array(
        'type'   => 'awhere',
        'fields' => $data['fields_data']
    );

    $mailshot->fast_update(
        array(
            'Email Campaign Metadata' => json_encode($metadata)
        )
    );
    $mailshot->update_estimated_recipients();

    $mailshot->update_state('ComposingEmail');


    $response = array(
        'state'           => 200,
        'update_metadata' => $mailshot->get_update_metadata(),

    );

    echo json_encode($response);

}


?>
