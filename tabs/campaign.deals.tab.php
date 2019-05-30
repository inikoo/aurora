<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 May 2016 at 14:50:27 GMT+8, Kual Lumput Malaydia
 Copyright (c) 2016, Inikoo

 Version 3

*/


switch ($state['_object']->get('Code')) {
    case 'VO':
        $tab     = 'vouchers';
        $ar_file = 'ar_marketing_tables.php';
        $tipo    = 'vouchers';

        $default = $user->get_tab_defaults($tab);


        $table_views = array(
            'overview' => array(
                'label' => _('Overview'),
                'title' => _('Overview')
            ),

        );

        $table_filters = array(

            'name' => array('label' => _('Name')),

        );

        $parameters = array(
            'parent'     => $state['object'],
            'parent_key' => $state['key'],

        );


        $table_buttons   = array();
        $table_buttons[] = array(
            'icon'      => 'plus',
            'title'     => _('New voucher'),
            'reference' => "offers/".$state['parent_key']."/".strtolower($state['_object']->get('Code'))."/new"
        );
        $smarty->assign('table_buttons', $table_buttons);
        break;
    case 'VL':


        $tab     = 'campaign_bulk_deals';
        $ar_file = 'ar_marketing_tables.php';
        $tipo    = 'campaign_bulk_deals';
        $default = $user->get_tab_defaults($tab);


        $table_views = array();

        $table_filters = array(

            'target' => array('label' => _('Target')),

        );

        $parameters = array(
            'parent'     => $state['object'],
            'parent_key' => $state['key'],

        );


        $table_buttons = array();

        $table_buttons[] = array(
            'icon'          => 'plus',
            'title'         => _('New offer'),
            'id'            => 'new_item',
            'class'         => 'items_operation',
            'add_bulk_deal' => array(

                'field_label' => _("Category").':',
                'metadata'    => base64_encode(
                    json_encode(
                        array(
                            'scope'      => 'targets',
                            'store_key'  => $state['_object']->get('Store Key'),
                            'parent'     => 'campaign',
                            'parent_key' => $state['_object']->id,
                            'options'    => array('bulk_deals')
                        )
                    )
                )

            )

        );

        $smarty->assign(
            'table_metadata',
            json_encode(
                array(
                    'parent'     => $state['object'],
                    'parent_key' => $state['key'],
                    'field'      => 'target'
                )
            )

        );


        $smarty->assign('table_buttons', $table_buttons);


        $smarty->assign('aux_templates', array('campaign_bulk_deals.edit.tpl'));
        break;
    default:
        $tab     = 'deals';
        $ar_file = 'ar_marketing_tables.php';
        $tipo    = 'deals';

        $default = $user->get_tab_defaults($tab);


        $table_views = array(
            'overview' => array(
                'label' => _('Overview'),
                'title' => _('Overview')
            ),

        );

        $table_filters = array(

            'name' => array('label' => _('Name')),

        );

        $parameters = array(
            'parent'     => $state['object'],
            'parent_key' => $state['key'],

        );


        $table_buttons   = array();
        $table_buttons[] = array(
            'icon'      => 'plus',
            'title'     => _('New offer'),
            'reference' => "campaigns/".$state['parent_key']."/".$state['key']."/deal/new"
        );
        $smarty->assign('table_buttons', $table_buttons);
}


include 'utils/get_table_html.php';


?>
