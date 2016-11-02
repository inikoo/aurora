<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 30 May 2016 at 18:27:54 CEST, Mijas Costa, Spain

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

require_once 'pcommon.php';
require_once 'utils/ar_common.php';


$tipo = $_REQUEST['tipo'];


switch ($tipo) {
    case 'content':

        $data = prepare_values(
            $_REQUEST, array(
                'request'  => array('type' => 'string'),
                'metadata' => array(
                    'type'     => 'json array',
                    'optional' => true
                ),
                'old_view' => array(
                    'type'     => 'json array',
                    'optional' => true
                ),
            )
        );


        $response = get_content($db, $smarty, $website, $user, $account, $data);

        break;
    case 'marginals':

        $data = prepare_values(
            $_REQUEST, array(
                'request'  => array('type' => 'string'),
                'metadata' => array('type' => 'json array')
            )
        );


        $response = get_marginals(
            $db, $smarty, $website, $user, $account, $data
        );

        break;

    default:
        $response = array(
            'state' => 404,
            'resp'  => 'Operation not found 2'
        );
        echo json_encode($response);

}

function get_marginals($db, $smarty, $website, $user, $account, $data) {

    //require_once 'utils/parse_request.php';


    $header = $smarty->fetch('ecom/header.tpl');
    $footer = $smarty->fetch('ecom/footer.tpl');

    $response = array(
        'state'  => 200,
        'header' => $header,
        'footer' => $footer
    );


    echo json_encode($response);

}


function get_breadcrumbs($smarty, $db, $webpage) {

    include_once('class.WebsiteNode.php');

    $breadcrumbs = array();
    //$breadcrumbs[]=array('label'=>_('home'), 'icon'=>'home', 'reference'=>'home');

    $level       = 0;
    $breadcrumbs = array();


    $node = new WebsiteNode($webpage->get('Webpage Website Node Key'));

    if ($node->get('Website Node Parent Key') != $node->id) {
        $breadcrumbs = create_breadcrumbs(
            $db, $node->get('Website Node Parent Key'), $breadcrumbs
        );
    }


    $breadcrumbs[] = array(
        'label'     => $webpage->get('Name'),
        'icon'      => $node->get('Icon'),
        'reference' => preg_replace(
            '/\./', '/', $webpage->get('Code')
        )
    );


    $smarty->assign('breadcrumbs', $breadcrumbs);

    return $smarty->fetch('ecom/breadcrumbs.tpl');

}


function create_breadcrumbs($db, $node_key, $branch) {

    $sql = sprintf(
        'SELECT `Website Node Parent Key`,`Webpage Code`,`Webpage Name`,`Website Node Icon` FROM `Website Node Dimension` LEFT JOIN `Webpage Dimension` ON (`Webpage Key`=`Website Node Webpage Key`) WHERE `Website Node Key`=%d',
        $node_key
    );

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {


            array_unshift(
                $branch, array(
                    'label'     => $row['Webpage Name'],
                    'icon'      => $row['Website Node Icon'],
                    'reference' => preg_replace(
                        '/\./', '/', $row['Webpage Code']
                    )
                )
            );
            if ($row['Website Node Parent Key'] == $node_key) {
                return $branch;
            } else {

                $branch = create_breadcrumbs(
                    $db, $row['Website Node Parent Key'], $branch
                );

                return $branch;
            }
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

}


function get_content($db, $smarty, $website, $user, $account, $data) {

    require_once 'utils/pparse_request.php';


    if (isset($data['metadata']['help']) and $data['metadata']['help']) {
        get_help($data, $modules, $db);

        return;
    }


    if (isset($data['metadata']['reload']) and $data['metadata']['reload']) {
        $reload = true;
    } else {
        $reload = false;
    }

    list($webpage, $request) = parse_request(
        $data, $db, $website, $account, $user
    );


    /*

    $sql=sprintf('insert into `User System View Fact`  (`User Key`,`Date`,`Module`,`Section`,`Tab`,`Parent`,`Parent Key`,`Object`,`Object Key`)  values (%d,%s,%s,%s,%s,%s,%s,%s,%s)',
        $user->id,
        prepare_mysql(gmdate('Y-m-d H:i:s')),
        prepare_mysql($state['module']),
        prepare_mysql($state['section']),
        prepare_mysql(($state['subtab']!=''?$state['subtab']:$state['tab'])),
        prepare_mysql($state['parent']),
        prepare_mysql($state['parent_key']),
        prepare_mysql($state['object']),
        prepare_mysql($state['key'])

    );
    $db->exec($sql);

*/


    $view = array(
        'webpage_key' => $webpage->id,
        'request'     => $request
    );

    $breadcrumbs = get_breadcrumbs($smarty, $db, $webpage);


    $response = array(
        'state'        => 200,
        'content'      => $webpage->get_content($smarty),
        'view'         => $view,
        'body_classes' => $webpage->version->get_metadata(
            'body_classes'
        ),
        'breadcrumbs'  => $breadcrumbs
    );


    echo json_encode($response);

}


?>
