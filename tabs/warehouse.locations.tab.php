<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 September 2015 15:18:07 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

if (!$user->can_view('locations') or !in_array(
        $state['key'], $user->warehouses
    )
) {
    $html = '';
} else {


    $warehouse=$state['warehouse'];


    $tab     = 'warehouse.locations';
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
        'parent'     => $state['parent'],
        'parent_key' => $state['parent_key'],

    );

    $smarty->assign(
        'js_code', 'js/injections/warehouse_locations.'.(_DEVEL ? '' : 'min.').'js'
    );








    $table_buttons = array();

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






    $smarty->assign('table_buttons', $table_buttons);

    $flags=array();

    $sql=sprintf('select `Warehouse Flag Key`,`Warehouse Flag Color`,`Warehouse Flag Label` FROM `Warehouse Flag Dimension` where `Warehouse Flag Warehouse Key`=%d ',
                 $warehouse->id
    );

    $flags=array();
    if ($result=$db->query($sql)) {
        foreach ($result as $row) {
            $flags[$row['Warehouse Flag Key']]=array('color'=>strtolower($row['Warehouse Flag Color']),'key'=>$row['Warehouse Flag Key'],'label'=>$row['Warehouse Flag Label']);
        }
    }else {
        print_r($error_info=$this->db->errorInfo());
        print "$sql\n";
        exit;
    }


    $smarty->assign('flags', $flags);



    $smarty->assign('aux_templates', array('edit_locations.tpl'));


    include 'utils/get_table_html.php';
}

?>
