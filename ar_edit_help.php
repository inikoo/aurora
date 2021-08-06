<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  14 January 2019 at 22:22:42 MYT+0800, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3.1

*/

require_once 'utils/ar_common.php';
include_once 'utils/help.functions.php';


$tipo = $_REQUEST['tipo'];


switch ($tipo) {

    case 'save_whiteboard':
        $data = prepare_values(
            $_REQUEST, array(
                         'state'   => array('type' => 'json array'),
                         'block'   => array('type' => 'string'),
                         'content' => array('type' => 'string'),
                     )
        );
        save_whiteboard($data, $db, $user, $smarty, $account, $modules);
        break;
    case  'side_block':
        $data = prepare_values(
            $_REQUEST, array(
                         'value'   => array('type' => 'string'),
                     )
        );



        $_SESSION['side_block']=$data['value'];
        break;


    default:
        $response = array(
            'state' => 404,
            'resp'  => 'Operation not found 2'
        );
        echo json_encode($response);

}




function save_whiteboard($data, $db, $user, $smarty, $account, $modules) {


    if (!isset($data['state']['module']) or !isset($data['state']['section'])) {
        $response = array(
            'status' => 400
        );


        echo json_encode($response);
        exit;
    }

    $module  = $data['state']['module'];
    $section = $data['state']['section'];

    $tab = ($data['state']['subtab'] == '' ? $data['state']['tab'] : $data['state']['subtab']);


    if ($data['block'] == 'tab') {

        $hash = hash('crc32', $module.$section.$tab, false);
        $type = 'Tab';
    } else {
        $hash = hash('crc32', $module.$section.'=P=', false);
        $type = 'Page';
    }


    $date = gmdate('Y-m-d H:i:s');

    $sql = sprintf(
        'INSERT INTO `Whiteboard Dimension` (`Whiteboard Hash`,`Whiteboard Type`,`Whiteboard Module`,`Whiteboard Section`,`Whiteboard Tab`,`Whiteboard Text`,`Whiteboard Created`,`Whiteboard Updated`,`Whiteboard Last Updated User Key`,`Whiteboard Last Updated Staff Key`) 
        VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%d,%d) ON DUPLICATE KEY UPDATE `Whiteboard Text`=%s,`Whiteboard Updated`=%s,`Whiteboard Last Updated User Key`=%d ,`Whiteboard Last Updated Staff Key`=%d ', prepare_mysql($hash), prepare_mysql($type), prepare_mysql($module),
        prepare_mysql($section), prepare_mysql($tab), prepare_mysql($data['content'], false), prepare_mysql($date), prepare_mysql($date), $user->id, $user->get_staff_key(), prepare_mysql($data['content'], false), prepare_mysql($date), $user->id, $user->get_staff_key()

    );


    $db->exec($sql);

    if (empty($state['tab'])) {
        $state['tab'] = '';
    }

    if (empty($state['subtab'])) {
        $state['subtab'] = '';
    }

    if (empty($state['section'])) {
        $state['section'] = '';
    }

    if (empty($state['module'])) {
        $state['module'] = '';
    }


    $state          = $data['state'];
    $help_cache_key = 'au_help|'.hash('crc32', $state['module'].'|'.$state['section']).'|'.hash('crc32', $state['tab'].$state['subtab']);


    $response = array(
        'help'       => array(
            'title'   => get_help_title($state, $user),
            'content' => get_help_content($state, $smarty, $account, $user)
        ),
        'whiteboard' => get_whiteboard($state['module'], $state['section'], $state['tab'], $state['subtab'], $modules, $db)
    );


    $redis = new Redis();
    $redis->connect(REDIS_HOST, REDIS_PORT);


    $redis->set($help_cache_key, json_encode($response));

    $response = array(
        'status' => 200
    );
    echo json_encode($response);

}

