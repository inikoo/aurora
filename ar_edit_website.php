<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 December 2016 at 12:58:51 CET, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

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
    case 'save_deal_component_labels':
        $data = prepare_values(
            $_REQUEST, array(
                         'key'   => array('type' => 'key'),
                         'label' => array('type' => 'string'),
                         'value' => array('type' => 'string')


                     )
        );
        save_deal_component_labels($data, $editor);
        break;

    case 'save_webpage_content':
        $data = prepare_values(
            $_REQUEST, array(
                         'key'            => array('type' => 'key'),
                         'content_data'   => array('type' => 'string'),
                         'labels'         => array(
                             'type'     => 'string',
                             'optional' => true
                         ),
                         'poll_labels'    => array(
                             'type'     => 'string',
                             'optional' => true
                         ),
                         'poll_position'  => array(
                             'type'     => 'string',
                             'optional' => true
                         ),
                         'discounts_data' => array(
                             'type'     => 'string',
                             'optional' => true
                         )


                     )
        );


        save_webpage_content($data, $editor, $account, $db);
        break;
    case 'update_website_styles':
        $data = prepare_values(
            $_REQUEST, array(
                         'key'           => array('type' => 'key'),
                         'styles'        => array(
                             'type'     => 'string',
                             'optional' => true
                         ),
                         'mobile_styles' => array(
                             'type'     => 'string',
                             'optional' => true
                         ),
                         'labels'        => array(
                             'type'     => 'string',
                             'optional' => true
                         ),
                         'settings'      => array(
                             'type'     => 'string',
                             'optional' => true
                         )


                     )
        );
        update_website_styles($data, $editor);
        break;

    case 'save_footer':
        $data = prepare_values(
            $_REQUEST, array(
                         'footer_key'  => array('type' => 'key'),
                         'footer_data' => array('type' => 'string')


                     )
        );
        save_footer($data, $editor, $smarty, $db);
        break;
    case 'save_header':
        $data = prepare_values(
            $_REQUEST, array(
                         'header_key' => array('type' => 'key'),
                         'menu'       => array('type' => 'string')


                     )
        );
        save_header($data, $editor, $smarty, $db);
        break;

    case 'create_webpage':

        $data = prepare_values(
            $_REQUEST, array(
                         'parent'     => array('type' => 'string'),
                         'parent_key' => array('type' => 'key'),


                     )
        );
        create_webpage($data, $editor, $smarty, $db);
        break;


    case 'update_object_public':
        $data = prepare_values(
            $_REQUEST, array(
                         'webpage_key' => array('type' => 'key'),
                         'section_key' => array('type' => 'numeric'),
                         'object'      => array('type' => 'string'),
                         'object_key'  => array('type' => 'key'),
                         'value'       => array('type' => 'string'),
                     )
        );
        update_object_public($data, $editor, $smarty, $db);
        break;


    case 'publish_webpage':

        $data = prepare_values(
            $_REQUEST, array(
                         'parent_key' => array('type' => 'key'),
                     )
        );
        publish_webpage($data, $editor, $db);
        break;
    case 'unpublish_webpage':

        $data = prepare_values(
            $_REQUEST, array(
                         'parent_key' => array('type' => 'key'),
                     )
        );
        unpublish_webpage($data, $editor, $db);
        break;

    case 'set_webpage_as_ready':

        $data = prepare_values(
            $_REQUEST, array(
                         'parent_key' => array('type' => 'key'),
                     )
        );
        set_webpage_as_ready($data, $editor, $db);
        break;
    case 'set_webpage_as_not_ready':

        $data = prepare_values(
            $_REQUEST, array(
                         'parent_key' => array('type' => 'key'),
                     )
        );
        set_webpage_as_not_ready($data, $editor, $db);
        break;

    case 'launch_website':
        $data = prepare_values(
            $_REQUEST, array(
                         'key' => array('type' => 'key')
                     )
        );
        launch_website($account, $db, $user, $editor, $data, $smarty);

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



function publish_webpage($data, $editor, $db) {

    $webpage         = get_object('Webpage', $data['parent_key']);
    $webpage->editor = $editor;


    $webpage->publish();


    $response = array(
        'state'           => 200,
        'other_fields'    => $webpage->get_other_fields_update_info(),
        'new_fields'      => $webpage->get_new_fields_info(),
        'deleted_fields'  => $webpage->get_deleted_fields_info(),
        'update_metadata' => $webpage->get_update_metadata()

    );
    echo json_encode($response);


}


function unpublish_webpage($data, $editor, $db) {

    $webpage = get_object('Webpage', $data['parent_key']);

    $webpage->editor = $editor;

    $webpage->unpublish();


    $response = array(
        'state'           => 200,
        'other_fields'    => $webpage->get_other_fields_update_info(),
        'new_fields'      => $webpage->get_new_fields_info(),
        'deleted_fields'  => $webpage->get_deleted_fields_info(),
        'update_metadata' => $webpage->get_update_metadata()

    );
    echo json_encode($response);


}


function set_webpage_as_ready($data, $editor, $db) {

    $webpage         = get_object('Webpage', $data['parent_key']);
    $webpage->editor = $editor;


    $website = get_object('Website', $webpage->get('Webpage Website Key'));

    if ($website->get('Website Status') == 'InProcess') {
        $webpage->update(array('Webpage State' => 'Ready'));
    }

    $response = array(
        'state'           => 200,
        'other_fields'    => $webpage->get_other_fields_update_info(),
        'new_fields'      => $webpage->get_new_fields_info(),
        'deleted_fields'  => $webpage->get_deleted_fields_info(),
        'update_metadata' => $webpage->get_update_metadata()

    );
    echo json_encode($response);


}


function set_webpage_as_not_ready($data, $editor, $db) {

    $webpage = get_object('Webpage', $data['parent_key']);

    $webpage->editor = $editor;

    $website = get_object('Website', $webpage->get('Webpage Website Key'));

    if ($website->get('Website Status') == 'InProcess') {
        $webpage->update(array('Webpage State' => 'InProcess'));
    }


    $response = array(
        'state'           => 200,
        'other_fields'    => $webpage->get_other_fields_update_info(),
        'new_fields'      => $webpage->get_new_fields_info(),
        'deleted_fields'  => $webpage->get_deleted_fields_info(),
        'update_metadata' => $webpage->get_update_metadata()

    );
    echo json_encode($response);


}





function update_object_public($data, $editor, $smarty, $db) {


    include_once('class.Page.php');


    $object = get_object($data['object'], $data['object_key']);


    if ($object->get_object_name() == 'Category') {
        $object->update(array('Product Category Public' => $data['value']));

    } elseif ($object->get_object_name() == 'Product') {

        $object->update(array('Product Public' => $data['value']));
    }


    $webpage             = new Page($data['webpage_key']);
    $content_data        = $webpage->get('Content Data');
    $overview_items_html = array();
    $items_html          = array();


    foreach ($content_data['sections'] as $section_stack_index => $section_data) {
        if ($section_data['key'] == $data['section_key']) {


            $smarty->assign('categories', $content_data['sections'][$section_stack_index]['items']);
            $overview_items_html[$data['section_key']] = $smarty->fetch('webpage.preview.categories_showcase.overview_section.items.tpl');
            $items_html[$data['section_key']]          = $smarty->fetch('webpage.preview.categories_showcase.section.items.tpl');


            break;
        }
    }


    $response = array(
        'state'               => 200,
        'items_html'          => $items_html,
        'overview_items_html' => $overview_items_html,
        'publish'             => $webpage->get('Publish')


    );
    echo json_encode($response);


}



function create_webpage($data, $editor, $smarty, $db) {

    include_once('class.Store.php');

    $parent = get_object($data['parent'], $data['parent_key']);

    $store = new Store($parent->get('Store Key'));

    foreach ($store->get_websites('objects') as $website) {
        if ($parent->get_object_name() == 'Product') {
            $webpage_key = $website->create_product_webpage($parent->id);
        } elseif ($parent->get_object_name() == 'Category') {
            $webpage_key = $website->create_category_webpage($parent->id);
        }
    }

    if ($webpage_key) {

        $response = array(
            'state'       => 200,
            'webpage_key' => $webpage_key,
            'request' =>'website/'.$website->id.'/webpage/'.$webpage_key

        );
    } else {

        $response = array(
            'state' => 400,
            'msg'   => $webpage_key


        );
    }

    echo json_encode($response);
}


function save_footer($data, $editor) {

    include_once('class.WebsiteFooter.php');


    $footer_data = json_decode($data['footer_data'], true);


    $footer         = new WebsiteFooter($data['footer_key']);
    $footer->editor = $editor;
    $footer->update(
        array(
            'Website Footer Data' => $footer_data
        )
    );


    if (!$footer->error) {
        $website = get_object('Website', $footer->get('Website Footer Website Key'));
        $website->clean_cache();
        $response = array(
            'state' => 200


        );
    } else {

        $response = array(
            'state' => 400,
            'msg'   => $footer->msg


        );
    }

    echo json_encode($response);


}


function save_header($data, $editor) {

    include_once('class.WebsiteHeader.php');


    $header = new WebsiteHeader($data['header_key']);


    $header_data = json_decode($header->get('Website Header Data'), true);




    $header_data['menu']['columns'] = json_decode($data['menu'], true);


    $header->editor = $editor;
    $header->update(
        array(
            'Website Header Data' => $header_data
        )
    );


    if (!$header->error) {
        $website = get_object('Website', $header->get('Website Header Website Key'));
        $website->clean_cache();
        $response = array(
            'state' => 200


        );
    } else {

        $response = array(
            'state' => 400,
            'msg'   => $header->msg


        );
    }

    echo json_encode($response);


}


function save_webpage_content($data, $editor, $account, $db) {


    $webpage         = get_object('Webpage',$data['key']);
    $webpage->editor = $editor;

    $website         = get_object('Website', $webpage->get('Webpage Website Key'));
    $website->editor = $editor;

    if (isset($data['labels'])) {

        $website->update_labels_in_localised_labels(json_decode($data['labels'], true));

        //print_r($data['labels']);
    }


    if (isset($data['discounts_data']) and false) { //todo its takes ages to do dis has to be done in fork maybe
        $discounts_data = json_decode($data['discounts_data'], true);

        foreach ($discounts_data as $deal_component_key => $deal_component_data) {


            $deal_component         = get_object('Deal_Component', $deal_component_key);
            $deal_component->editor = $editor;

            $deal_component->update(
                array(

                    'Deal Component Allowance Label' => $deal_component_data['allowance']
                )
            );


            $deal         = get_object('Deal', $deal_component->get('Deal Key'));
            $deal->editor = $editor;

            $deal->update(
                array(
                    'Deal Name Label' => $deal_component_data['name'],
                    'Deal Term Label' => $deal_component_data['term'],
                )
            );
            //  print_r($deal_component_data);

            //$poll_query->update($_data);

        }


    }


    if (isset($data['poll_labels'])) {
        $poll_labels_data = json_decode($data['poll_labels'], true);

        foreach ($poll_labels_data as $poll_key => $label) {
            $label = base64_decode($label);

            $_data = array('Customer Poll Query Label' => $label);

            $poll_query         = get_object('Customer_Poll_Query', $poll_key);
            $poll_query->editor = $editor;
            $poll_query->update($_data);

        }


    }

    if (isset($data['poll_position'])) {
        $poll_position_data = json_decode($data['poll_position'], true);

        $position = 1;
        foreach ($poll_position_data as $key) {

            $sql = sprintf(
                'update `Customer Poll Query Dimension` set `Customer Poll Query Position`=%d where `Customer Poll Query Key`=%d ', $position, $key
            );

            $db->exec($sql);
            $position++;

        }


    }



    $old_content_data = $webpage->get('Content Data');





    $webpage->update(array('Page Store Content Data' => $data['content_data']), 'no_history');


    if (isset($old_content_data['backup'])) {
        $webpage->update_content_data('backup', $old_content_data['backup']);
    }


    $webpage->reindex_items();


    $webpage->publish();

    $response = array(
        'state'   => 200,
        'content' => (isset($data['value']) ? $data['value'] : ''),
        'publish' => $webpage->get('Publish'),


    );

    if (isset($products_html)) {
        $response['products'] = $products_html;
    }
    if (isset($items_html)) {
        $response['items_html'] = $items_html;
    }
    if (isset($overview_items_html)) {
        $response['overview_items_html'] = $overview_items_html;
    }


    require_once 'utils/new_fork.php';
    new_housekeeping_fork(
        'au_take_webpage_screenshot', array(
        'webpage_key' => $webpage->id,
    ), $account->get('Account Code'), $db
    );



    echo json_encode($response);


}


function launch_website($account, $db, $user, $editor, $data, $smarty) {


    $website         = get_object('website', $data['key']);
    $website->editor = $editor;

    $website->launch();


    $response = array(
        'state' => 200,

    );
    echo json_encode($response);

}

function save_deal_component_labels($data, $editor) {

    $deal_component         = get_object('Deal Component', $data['key']);
    $deal_component->editor = $editor;

    $deal = get_object('deal', $deal_component->get('Deal Key'));

    $deal->editor = $editor;

    //  $update_fields = array();

    switch ($data['label']) {
        case 'name':
            $update_fields = array('Deal Name Label' => $data['value']);
            $deal->update($update_fields);

            break;
        case 'term':
            $update_fields = array('Deal Term Label' => $data['value']);
            $deal->update($update_fields);

            break;
        case 'allowance':
            $update_fields = array('Deal Component Allowance Label' => $data['value']);
            $deal_component->update($update_fields);

            break;

    }


    $response = array(
        'state' => 200,

    );
    echo json_encode($response);


}


function update_website_styles($data, $editor) {

    include_once 'utils/image_functions.php';


    $website         = get_object('Website', $data['key']);
    $website->editor = $editor;


    if (isset($data['labels'])) {
        $website->update_labels_in_localised_labels(json_decode($data['labels'], true));
    }
    if (isset($data['settings'])) {





        $settings=json_decode($data['settings'], true);





        if (isset($settings['logo_website'])) {

            $tmp = array();
            foreach ($website->style as $style_data) {
                $tmp[trim($style_data[0]).'|'.trim($style_data[1])] = $style_data[2];

            }
            $style = array();
            foreach ($tmp as $_key => $_value) {
                $_tmp    = preg_split('/\|/', $_key);
                $style[] = array(
                    $_tmp[0],
                    $_tmp[1],
                    $_value
                );
            }

            $height = 60;
            $width  = 80;
            foreach ($style as $style_data) {
                if ($style_data[0] == '#header_logo' and $style_data[1] == 'flex-basis') {
                    $width = floatval($style_data[2]);
                }
                if ($style_data[0] == '#top_header' and $style_data[1] == 'height') {
                    $height = floatval($style_data[2]);
                }
            }



            $settings['logo_website'] = preg_replace('/image_root/', 'wi', $settings['logo_website']);
            $settings['logo_website'] = preg_replace('/image_/', 'wi', $settings['logo_website']);

            if (preg_match('/id=(\d+)/', $settings['logo_website'], $matches)) {



                $settings['logo_website_website'] = 'wi.php?id='.$matches[1];
            }

        }



        if (isset($settings['favicon'])) {
            $settings['favicon'] = preg_replace('/image_root/', 'wi', $settings['favicon']);
            if (preg_match('/id=(\d+)/', $settings['favicon'], $matches)) {
                $settings['favicon_website'] = 'wi.php?id='.$matches[1].'&s=32x32';
            }
        } 



        $website->update_settings($settings);
    }


    if (isset($data['styles'])) {
        $website->update_styles(json_decode($data['styles'], true));
    }
    if (isset($data['mobile_styles'])) {
        $website->update_mobile_styles(json_decode($data['mobile_styles'], true));
    }


    $website->clean_cache();

    $response = array(
        'state' => 200,


    );
    echo json_encode($response);

}


