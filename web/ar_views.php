<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 September 2016 at 11:42:13 GMT+8, Cyberyaja, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/parse_request.php';
require_once 'class.WebsiteNode.php';
require_once 'class.Webpage.php';
require_once 'class.WebpageVersion.php';


$tipo = $_REQUEST['tipo'];


switch ($tipo) {
    case 'views':

        $request_data = prepare_values(
            $_REQUEST, array(
                'request'   => array('type' => 'string'),
                'old_state' => array('type' => 'json array'),
                'tab'       => array(
                    'type'     => 'string',
                    'optional' => true
                ),
                'subtab'    => array(
                    'type'     => 'string',
                    'optional' => true
                ),
                'otf'       => array(
                    'type'     => 'string',
                    'optional' => true
                ),
                'metadata'  => array(
                    'type'     => 'json array',
                    'optional' => true
                ),

            )
        );

        view(
            [
                'db'   => $db,
                'user' => $user,
                'smarty' => $smarty,
                'request_data' => $request_data
            ]
        );

        break;


    default:
        $response = array(
            'state' => 404,
            'resp'  => 'Operation not found 2'
        );
        echo json_encode($response);

}


/**
 * @param $data
 */
function view($data) {

    $db   = $data['db'];
    $user = $data['user'];
    $smarty = $data['smarty'];

    $request_data = $data['request_data'];

    $state = parse_request($request_data, $db, $user);


    $website_node = new WebsiteNode('code', $state['code']);

    if(!$website_node->id) {
        $website_node=new WebsiteNode('code','not_found');

    }



    $_SESSION['request'] = $state['request'];


    $state['metadata'] = (isset($data['metadata']) ? $data['metadata'] : array());

    $response = array(
        'state'   => $state,
        'crumbs'  => $website_node->get('Crumbs'),
        'content' => $website_node->get_content($smarty)
    );


    echo json_encode($response);

}


