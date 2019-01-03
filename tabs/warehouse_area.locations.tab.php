<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 September 2015 15:18:07 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

if (!$user->can_view('locations') or !in_array($state['warehouse']->id, $user->warehouses)
) {
    $html = '';
} else {


    $warehouse = $state['warehouse'];


    $tab     = 'warehouse_area.locations';
    $ar_file = 'ar_warehouse_tables.php';
    $tipo    = 'locations';

    $default = $user->get_tab_defaults($tab);


    $table_views = array();

    $table_filters = array(
        'code' => array(
            'label' => _('Code'),
            'title' => _('Location code')
        ),

    );

    $parameters = array(
        'parent'     => $state['object'],
        'parent_key' => $state['key'],

    );

    $smarty->assign(
        'js_code', 'js/injections/warehouse_locations.'.(_DEVEL ? '' : 'min.').'js'
    );


    $table_buttons = array();


    include_once 'conf/export_edit_template_fields.php';


    $edit_table_dialog = array(
        'labels'           => array(
            'add_items'  => _("Add location(s)").":",
            'edit_items' => _("Edit location(s)").":"
        ),
        'new_item'         => array(
            'icon'      => 'plus',
            'reference' => "warehouse/".$warehouse->id."/areas/".$state['key']."/location/new"
        ),
        'upload_items'     => array(
            'icon'         => 'plus',
            'label'        => _("Upload locations"),
            'template_url' => '/upload_arrangement.php?object=location&parent=warehouse_area&parent_key='.$state['key'],

            'tipo'       => 'edit_objects',
            'parent'     => 'warehouse_area',
            'parent_key' => $state['key'],

            'object' => 'location',
        ),
        'inline_edit'      => array(),
        'spreadsheet_edit' => array(
            'tipo' => 'edit_objects',

            'parent'      => 'warehouse_area',
            'parent_key'  => $state['_object']->id,
            'object'      => 'location',
            'parent_code' => preg_replace("/[^A-Za-z0-9 ]/", '', $state['_object']->get('Code')),
        ),

    );
    $smarty->assign('edit_table_dialog', $edit_table_dialog);

    $objects = 'location';


    $edit_fields = $export_edit_template_fields[$objects];







    $smarty->assign('edit_fields', $edit_fields);


    $table_buttons = array();

    $table_buttons[] = array(
        'icon'  => 'edit_add',
        'title' => _("Edit locations"),
        'id'    => 'edit_dialog'
    );


    $table_buttons[] = array(
        'icon'              => 'link',
        'title'             => _('Associate location'),
        'id'                => 'new_record',
        'inline_new_object' => array(
            'field_id'                 => 'WarehouseArea_Location_Code',
            'field_label'              => _('Associate location').':',
            'field_edit'               => 'dropdown',
            'object'                   => 'WarehouseArea_Location',
            'parent'                   => $state['object'],
            'parent_key'               => $state['key'],
            'placeholder'              => _("Location's code"),
            'dropdown_select_metadata' => base64_encode(
                json_encode(
                    array(
                        'scope'      => 'locations',
                        'parent'     => 'warehouse',
                        'parent_key' => $state['_object']->get(
                            'Warehouse Key'
                        ),
                        'options'    => array()
                    )
                )
            )
        )

    );


    /*



        if ($state['warehouse']->get('Warehouse Number Locations') > 0) {
            $table_buttons[] = array(
                'icon'  => 'edit',
                'title' => _("Edit locations"),
                'id'    => 'edit_table'
            );
        }


        $table_buttons[] = array(
            'icon'      => 'plus',
            'title'     => _('New location'),
            'reference' => "locations/".$warehouse->id."/new"
        );

        $smarty->assign('table_buttons', $table_buttons);

        $smarty->assign(
            'upload_file', array(
                             'tipo'       => 'edit_objects',
                             'icon'       => 'fa-cloud-upload',
                             'parent'     => 'warehouse',
                             'parent_key' => $warehouse->id,
                             'object'     => 'location',
                             'label'      => _("Upload locations")

                         )
        );



    */


    $smarty->assign('table_buttons', $table_buttons);

    $flags = array();

    $sql = sprintf(
        'select `Warehouse Flag Key`,`Warehouse Flag Color`,`Warehouse Flag Label` FROM `Warehouse Flag Dimension` where `Warehouse Flag Warehouse Key`=%d ',
        $warehouse->id
    );

    $flags = array();
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $flags[$row['Warehouse Flag Key']] = array(
                'color' => strtolower($row['Warehouse Flag Color']),
                'key'   => $row['Warehouse Flag Key'],
                'label' => $row['Warehouse Flag Label']
            );
        }
    } else {
        print_r($error_info = $this->db->errorInfo());
        print "$sql\n";
        exit;
    }


    $smarty->assign('flags', $flags);


    $smarty->assign('aux_templates', array('edit_locations.tpl'));


    include 'utils/get_table_html.php';
}

?>
