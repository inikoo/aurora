<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 December 2015 at 16:34:36 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


if ($state['module'] == 'inventory') {
    $tab     = 'category.part_categories';
    $ar_file = 'ar_inventory_tables.php';
    $tipo    = 'part_categories';


    $default = $user->get_tab_defaults($tab);


    $table_views = array(
        'overview'    => array('label' => _('Overview')),
        'performance' => array('label' => _('Performance')),

        'stock'        => array('label' => _('Stock')),
        'sales'        => array('label' => _('Sales')),
        'dispatched_q' => array('label' => _('Dispatched (Qs)')),
        'dispatched_y' => array('label' => _('Dispatched (Yrs)')),
        'revenue_q'    => array('label' => _('Revenue (Qs)')),
        'revenue_y'    => array('label' => _('Revenue (Yrs)')),

    );


    if ($state['key'] == $account->get('Account Part Family Category Key')) {
        $title         = _('New family');
        $field_label   = _('Add family').':';
        $placeholder   = _("Family code");
        $table_filters = array(
            'label' => array('label' => _('Label')),
            'code'  => array('label' => _('Name')),

        );
    } else {
        $title         = _('New category');
        $field_label   = _('Add category').':';
        $placeholder   = _("Category code");
        $table_filters = array(
            'label' => array('label' => _('Label')),
            'code'  => array('label' => _('Code')),

        );
    }


    $parameters = array(
        'parent'     => $state['object'],
        'parent_key' => $state['key'],

    );


    $table_buttons[] = array(
        'icon'              => 'plus',
        'title'             => $title,
        'id'                => 'new_record',
        'inline_new_object' => array(
            'field_id'    => 'Category_Code',
            'field_label' => $field_label,
            'field_edit'  => 'string',
            'object'      => 'Category',
            'parent'      => $state['object'],
            'parent_key'  => $state['key'],
            'placeholder' => _("Family's code")
        )

    );

    $smarty->assign('table_buttons', $table_buttons);

} elseif ($state['module'] == 'products') {


    if($state['_object']->get('Category Subject')=='Product'){




        $tab     = 'category.product_categories.categories';
        $ar_file = 'ar_products_tables.php';
        $tipo    = 'product_categories_categories';

        $default = $user->get_tab_defaults($tab);


        $table_views = array(
            'overview' => array('label' => _('Overview')),
            'status'   => array('label' => _("Product's Status")),
            'stock'    => array('label' => _('Stock')),
            'sales'    => array('label' => _('Sales')),
            'sales_y'  => array('label' => _('Invoiced amount (Yrs)')),
            'sales_q'  => array('label' => _('Invoiced amount (Qs)')),

        );


        if ($state['key'] == $state['store']->get('Store Family Category Key')) {
            $title         = _('New family');
            $field_label   = _('Add family').':';
            $placeholder   = _("Family code");
            $table_filters = array(
                'label' => array('label' => _('Label')),
                'code'  => array('label' => _('Name')),

            );
        } else {
            $title         = _('New category');
            $field_label   = _('Add category').':';
            $placeholder   = _("Category code");
            $table_filters = array(
                'label' => array('label' => _('Label')),
                'code'  => array('label' => _('Code')),

            );
        }


        $parameters = array(
            'parent'     => $state['object'],
            'parent_key' => $state['key'],

        );


        $table_buttons[] = array(
            'icon'              => 'plus',
            'title'             => $title,
            'id'                => 'new_record',
            'inline_new_object' => array(
                'field_id'    => 'Category_Code',
                'field_label' => $field_label,
                'field_edit'  => 'string',
                'object'      => 'Category',
                'parent'      => $state['object'],
                'parent_key'  => $state['key'],
                'placeholder' => _("Family's code")
            )

        );

    }elseif($state['_object']->get('Category Subject')=='Category'){

        $tab     = 'category.product_categories.categories';
        $ar_file = 'ar_products_tables.php';
        $tipo    = 'product_categories_categories';

        $default = $user->get_tab_defaults($tab);


        $table_views = array(
            'overview' => array('label' => _('Overview')),
            'status'   => array('label' => _("Product's Status")),
            'stock'    => array('label' => _('Stock')),
            'sales'    => array('label' => _('Sales')),
            'sales_y'  => array('label' => _('Invoiced amount (Yrs)')),
            'sales_q'  => array('label' => _('Invoiced amount (Qs)')),

        );


        if ($state['key'] == $state['store']->get('Store Department Category Key')) {
            $title         = _('New department');
            $field_label   = _('Add department').':';
            $placeholder   = _("Department code");
            $table_filters = array(
                'label' => array('label' => _('Label')),
                'code'  => array('label' => _('Name')),

            );
        } else {
            $title         = _('New category');
            $field_label   = _('Add category').':';
            $placeholder   = _("Category code");
            $table_filters = array(
                'label' => array('label' => _('Label')),
                'code'  => array('label' => _('Code')),

            );
        }


        $parameters = array(
            'parent'     => $state['object'],
            'parent_key' => $state['key'],

        );


        $table_buttons[] = array(
            'icon'              => 'plus',
            'title'             => $title,
            'id'                => 'new_record',
            'inline_new_object' => array(
                'field_id'    => 'Category_Code',
                'field_label' => $field_label,
                'field_edit'  => 'string',
                'object'      => 'Category',
                'parent'      => $state['object'],
                'parent_key'  => $state['key'],
                'placeholder' => _("Family's code")
            )

        );

    }



    $smarty->assign('table_buttons', $table_buttons);

} elseif ($state['module'] == 'suppliers') {
    $tab     = 'category.supplier_categories';
    $ar_file = 'ar_suppliers_tables.php';
    $tipo    = 'supplier_categories';


    $default = $user->get_tab_defaults($tab);


    $table_views = array(
        'overview' => array(
            'label' => _('Overview'),
            'title' => _('Overview')
        ),
        'parts'    => array('label' => _("Parts's stock")),
        'sales'    => array('label' => _("Parts's sales")),
        'sales_q'  => array('label' => _("Parts's sales (Qs)")),
        'sales_y'  => array('label' => _("Parts's sales (Yrs)")),
    );


    $title         = _('New category');
    $field_label   = _('Add category').':';
    $placeholder   = _("Categoy code");
    $table_filters = array(
        'label' => array('label' => _('Label')),
        'code'  => array('label' => _('Code')),

    );


    $parameters = array(
        'parent'     => $state['object'],
        'parent_key' => $state['key'],

    );


    $table_buttons[] = array(
        'icon'              => 'plus',
        'title'             => _('New category'),
        'id'                => 'new_record',
        'inline_new_object' => array(
            'field_id'    => 'Category_Code',
            'field_label' => _('Add category').':',
            'field_edit'  => 'string',
            'object'      => 'Category',
            'parent'      => $state['object'],
            'parent_key'  => $state['key'],
            'placeholder' => _("Category's code")
        )

    );

    $smarty->assign('table_buttons', $table_buttons);

} else {

    $tab     = 'category.categories';
    $ar_file = 'ar_categories_tables.php';
    $tipo    = 'categories';


    $default = $user->get_tab_defaults($tab);


    $table_views = array();

    $table_filters = array(
        'label' => array(
            'label' => _('Label'),
            'title' => _('Category label')
        ),
        'code'  => array(
            'label' => _('Code'),
            'title' => _('Category code')
        ),

    );

    $parameters = array(
        'parent'     => $state['object'],
        'parent_key' => $state['key'],

    );


    $table_buttons[] = array(
        'icon'              => 'plus',
        'title'             => _('New category'),
        'id'                => 'new_record',
        'inline_new_object' => array(
            'field_id'    => 'Category_Code',
            'field_label' => _('Add category').':',
            'field_edit'  => 'string',
            'object'      => 'Category',
            'parent'      => $state['object'],
            'parent_key'  => $state['key'],
            'placeholder' => _("Category's code")
        )

    );

    $smarty->assign('table_buttons', $table_buttons);
}

include 'utils/get_table_html.php';


?>
