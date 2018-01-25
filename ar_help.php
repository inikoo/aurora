<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 March 2016 at 15:38:13 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


require_once 'common.php';
require_once 'utils/ar_common.php';


$tipo = $_REQUEST['tipo'];


switch ($tipo) {

    case 'help':
        $data = prepare_values($_REQUEST, array('state' => array('type' => 'json array'),));
        get_help($data, $modules, $db, $account, $user, $smarty);
        break;
    case 'whiteboard':
        $data = prepare_values($_REQUEST, array('state' => array('type' => 'json array'),));
        get_whiteboard($data, $modules, $db, $account, $user, $smarty);
        break;
    case 'save_whiteboard':
        $data = prepare_values(
            $_REQUEST, array(
            'state'   => array('type' => 'json array'),
            'block'   => array('type' => 'string'),
            'content' => array('type' => 'string'),
        )
        );
        save_whiteboard($data, $modules, $db, $account, $user, $editor);
        break;
    case 'side_block':
        $data                   = prepare_values($_REQUEST, array('value' => array('type' => 'string')));
        $_SESSION['side_block'] = $data['value'];
        break;
    default:
        $response = array(
            'state' => 404,
            'resp'  => 'Operation not found 2'
        );
        echo json_encode($response);

}

function save_whiteboard($data, $modules, $db, $account, $user, $editor) {



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
        VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%d,%d) ON DUPLICATE KEY UPDATE `Whiteboard Text`=%s,`Whiteboard Updated`=%s,`Whiteboard Last Updated User Key`=%d ,`Whiteboard Last Updated Staff Key`=%d ', prepare_mysql($hash), prepare_mysql($type), prepare_mysql($module), prepare_mysql($section), prepare_mysql($tab),
        prepare_mysql($data['content']), prepare_mysql($date), prepare_mysql($date), $user->id, $user->get_staff_key(), prepare_mysql($data['content']), prepare_mysql($date),  $user->id, $user->get_staff_key()

    );

  //  print $sql;

    $db->exec($sql);


}


function get_help($data, $modules, $db, $account, $user, $smarty) {

    //print_r($data['state']);


    $title   = get_title($data['state'], $account, $user);
    $content = get_content($data['state'], $smarty, $account, $user);

    $response = array(
        'title'   => $title,
        'content' => $content
    );

    echo json_encode($response);


}


function get_title($state, $account, $user) {

    if ($state['tab'] == 'supplier.supplier_parts') {
        return _("Supplier's part list & adding supplier parts");
    } elseif ($state['tab'] == 'employees') {
        if ($user->can_create('staff')) {
            return _('Employees list & adding employees');
        } else {

            return _('Employees list');
        }
    } elseif ($state['tab'] == 'employee.new') {
        return _('Adding an employee');

    } elseif ($state['tab'] == 'warehouse.locations') {
        return _("Locations list");

    }

    return '';
}


function get_content($state, $smarty, $account, $user) {

    $smarty->assign('user', $user);
    $smarty->assign('object', $state['object']);
    $smarty->assign('key', $state['key']);

    $smarty->assign('account', $account);


    $template = 'help/'.$state['module'].'.'.$state['tab'].'.quick.tpl';
    if ($smarty->templateExists($template)) {
        return $smarty->fetch($template);
    }

    return _('There is not help for this section').' '.$state['module'].'.'.$state['tab'];
}


function get_whiteboard($data, $modules, $db, $account, $user, $smarty) {


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


    $hash_page = hash('crc32', $module.$section.'=P=', false);

    $hash_tab = hash('crc32', $module.$section.$tab, false);



    $page_title=sprintf(_('%s (page)'),(isset($modules[$module]['sections'][$section]['label'])?$modules[$module]['sections'][$section]['label']:$section));


    $empty_tab = true;
    $text_tab  = '';
    $tab_title='';
    if (count($modules[$module]['sections'][$section]['tabs']) == 1) {
        $has_tab = false;
    } else {
        $has_tab = true;

        if($data['state']['subtab'] == ''){


            $tab_title=sprintf(_('%s (tab)'),(isset($modules[$module]['sections'][$section]['tabs'][$tab]['label'])?$modules[$module]['sections'][$section]['tabs'][$tab]['label']:$tab));

        }else{


            $tab_title=sprintf(_('%s (tab)'),(
                isset($modules[$module]['sections'][$section]['tabs'] [$data['state']['tab']] ['subtabs'][$data['state']['subtab']]['label'])
                    ?
                    $modules[$module]['sections'][$section]['tabs'][$data['state']['tab']]['subtabs'][$data['state']['subtab']]['label']
                    :$tab
            ));

        }



        $sql = sprintf('SELECT `Whiteboard Text` FROM `Whiteboard Dimension` WHERE `Whiteboard Hash`=%s ', prepare_mysql($hash_tab));


        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {
                $text_tab  = $row['Whiteboard Text'];
                $empty_tab = false;
            } else {
                $text_tab  = '<em class="very_discreet">'._('Fell free to type something about this tab').' ('.$tab.') </em>';
                $empty_tab = true;
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


    }


    $sql = sprintf('SELECT `Whiteboard Text` FROM `Whiteboard Dimension` WHERE `Whiteboard Hash`=%s ', prepare_mysql($hash_page));
    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $text  = $row['Whiteboard Text'];
            $empty = false;
        } else {
            $text  = '<em class="very_discreet">'._('Fell free to type something about this page').' ('.$data['state']['section'].') </em>';
            $empty = true;
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    $response = array(
        'status'      => 200,
        'empty'       => $empty,
        'content'     => $text,
        'has_tab'     => $has_tab,
        'empty_tab'   => $empty_tab,
        'content_tab' => $text_tab,
        'page_title'=>$page_title,
        'tab_title'=>$tab_title,

    );

    echo json_encode($response);

}


?>
