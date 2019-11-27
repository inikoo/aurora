<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  15 January 2019 at 00:41:43 MYT+0800, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3.1

*/



function get_help_title($state, $user) {


    if (empty($state['tab'])) {
        return '';
    }

    if ($state['tab'] == 'supplier.supplier_parts') {
        return _("Supplier's product list & adding supplier's products");
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
function get_help_content($state, $smarty, $account, $user) {

    if (empty($state['module']) or empty($state['tab'])) {
        return '';
    }


    $smarty->assign('user', $user);

    $smarty->assign('account', $account);


    $template = 'help/'.$state['module'].'.'.$state['tab'].'.quick.tpl';
    if ($smarty->templateExists($template)) {
        return $smarty->fetch($template);
    } else {
        return _('There is not help for this section').' '.$state['module'].'.'.$state['tab'];

    }

}
function get_whiteboard($module, $section, $tab, $subtab, $modules, $db) {

    $_tab = ($subtab == '' ? $tab : $subtab);


    $hash_page = hash('crc32', $module.$section.'=P=', false);
    $hash_tab  = hash('crc32', $module.$section.$_tab, false);


    $page_title = sprintf(_('%s (page)'), (isset($modules[$module]['sections'][$section]['label']) ? $modules[$module]['sections'][$section]['label'] : $section));


    $empty_tab = true;
    $has_tab   = false;
    $text_tab  = '';
    $tab_title = '';
    if (isset($modules[$module]['sections'][$section]['tabs']) and count($modules[$module]['sections'][$section]['tabs']) > 1) {
        $has_tab = true;

        if ($subtab == '') {


            $tab_title = sprintf(_('%s (tab)'), (isset($modules[$module]['sections'][$section]['tabs'][$tab]['label']) ? $modules[$module]['sections'][$section]['tabs'][$tab]['label'] : $tab));

        } else {


            $tab_title = sprintf(
                _('%s (tab)'), (
            isset($modules[$module]['sections'][$section]['tabs'] [$tab] ['subtabs'][$subtab]['label'])
                ?
                $modules[$module]['sections'][$section]['tabs'][$tab]['subtabs'][$subtab]['label']
                : $tab
            )
            );

        }


        $sql = sprintf('SELECT `Whiteboard Text` FROM `Whiteboard Dimension` WHERE `Whiteboard Hash`=%s ', prepare_mysql($hash_tab));


        if ($result = $db->query($sql)) {
            if ($row = $result->fetch()) {

                if ($row['Whiteboard Text'] == '') {
                    $text_tab  = '<em class="very_discreet">'._('Feel free to type something about this tab').' ('.$_tab.') </em>';
                    $empty_tab = true;
                } else {

                    $text_tab  = $row['Whiteboard Text'];
                    $empty_tab = false;
                }


            } else {
                $text_tab  = '<em class="very_discreet">'._('Feel free to type something about this tab').' ('.$_tab.') </em>';
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
            $text  = '<em class="very_discreet">'._('Feel free to type something about this page').' ('.$section.') </em>';
            $empty = true;
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    return array(

        'empty'       => $empty,
        'content'     => $text,
        'has_tab'     => $has_tab,
        'empty_tab'   => $empty_tab,
        'content_tab' => $text_tab,
        'page_title'  => $page_title,
        'tab_title'   => $tab_title,

    );


}