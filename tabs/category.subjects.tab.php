<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  16 February 2016 at 11:20:41 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/
include_once 'utils/get_export_edit_template_fields.php';
/** @var User $user */
/** @var \PDO $db */
/** @var \Smarty $smarty */
/** @var array $state */


$category = $state['_object'];


if ($category->get('Category Scope') == 'Product') {

    if ($category->get('Category Subject') == 'Product') {

        $category->update_product_category_products_data();



        $tab     = 'category.products';
        $ar_file = 'ar_products_tables.php';
        $tipo    = 'products';

        $default = $user->get_tab_defaults($tab);

        $table_views = array(
            'overview'    => array('label' => _('Overview')),
            'price'    => array('label' => _('Price')),

            'performance' => array('label' => _('Performance')),
            'sales'       => array('label' => _('Sales')),
            'sales_y'     => array('label' => _('Invoiced amount (Yrs)')),
            'sales_q'     => array('label' => _('Invoiced amount (Qs)')),


        );

        $table_filters = array(
            'code' => array(
                'label' => _('Code'),
                'title' => _('Product code')
            ),
            'name' => array(
                'label' => _('Name'),
                'title' => _('Product name')
            ),

        );

        $parameters = array(
            'parent'     => $state['object'],
            'parent_key' => $state['key'],

        );




        $edit_table_dialog = array(


            'spreadsheet_edit' => array(
                'tipo'       => 'edit_objects',
                'parent'     => $state['object'],
                'parent_key' => $state['key'],
                'object'     => 'product',
                'parent_code' => preg_replace("/[^A-Za-z0-9 ]/", '', $state['_object']->get('Code')),
            ),

        );
        $smarty->assign('edit_table_dialog', $edit_table_dialog);

        $objects = 'product';
        $edit_fields = get_export_edit_template_fields($objects);



        $smarty->assign('edit_fields', $edit_fields);




        $table_buttons[] = array(
            'icon'  => 'edit',
            'title' => _("Edit products"),
            'id'    => 'edit_dialog'
        );

        $table_buttons[] = array(
            'icon'       => 'cube',
            'title'      => _('All products'),
            'change_tab' => 'category.all_subjects',
            'class'      => 'move_left'
        );


        $table_buttons[] = array(
            'icon'              => 'link',
            'title'             => _('Associate product'),
            'id'                => 'new_record',
            'inline_new_object' => array(
                'field_id'                 => 'Store_Product_Code',
                'field_label'              => _('Associate product').':',
                'field_edit'               => 'dropdown',
                'object'                   => 'Category_Product',
                'parent'                   => $state['object'],
                'parent_key'               => $state['key'],
                'placeholder'              => _("Product's code"),
                'dropdown_select_metadata' => base64_encode(
                    json_encode(
                        array(
                            'scope'      => 'products',
                            'parent'     => 'store',
                            'parent_key' => $state['_object']->get(
                                'Product Category Store Key'
                            ),
                            'options'    => array()
                        )
                    )
                )
            )

        );


    }
    else {

        $store=get_object('Store',$state['_object']->get('Store Key'));
        //print_r($store);


        if(
            $store->get('Store Department Category Key')==$state['_object']->get('Category Root Key') or
            $store->get('Store Family Category Key')==$state['_object']->get('Category Root Key')
        ){
            $smarty->assign('is_family', 'Yes');
        }






        $tab     = 'category.product_categories.products';
        $ar_file = 'ar_products_tables.php';
        $tipo    = 'product_categories_products';

        $default = $user->get_tab_defaults($tab);


        $table_views = array(
            'overview' => array('label' => _('Overview')),
            'webpages' => array('label' => _('Webpages')),
            'status'   => array('label' => _("Product's Status")),
            'stock'    => array('label' => _('Stock')),
            'sales'    => array('label' => _('Sales')),
            'sales_y'  => array('label' => _('Invoiced amount (Yrs)')),
            'sales_q'  => array('label' => _('Invoiced amount (Qs)')),

        );

        $table_filters = array(
            'code'  => array('label' => _('Code')),
            'label' => array('label' => _('Label')),

        );

        $parameters = array(
            'parent'     => $state['object'],
            'parent_key' => $state['key'],

        );
/*

        $table_buttons[] = array(
            'icon'       => 'sitemap',
            'title'      => _('All categories'),
            'change_tab' => 'category.all_subjects',
            'class'      => 'move_left'
        );
*/

        $table_buttons[] = array(
            'icon'              => 'link',
            'title'             => _('Associate family'),
            'id'                => 'new_record',
            'inline_new_object' => array(
                'field_id'                 => 'Store_Category_Code',
                'field_label'              => _('Associate family').':',
                'field_edit'               => 'dropdown',
                'object'                   => 'Category_Category',
                'parent'                   => $state['object'],
                'parent_key'               => $state['key'],
                'placeholder'              => _("Family's code"),
                'dropdown_select_metadata' => base64_encode(
                    json_encode(
                        array(
                            'scope'      => 'families',
                            'parent'     => 'root_key',
                            'parent_key' => $state['store']->get('Store Family Category Key'),
                            'options'    => array()
                        )
                    )
                )
            )

        );


    }
    $smarty->assign('table_buttons', $table_buttons);

}
elseif ($category->get('Category Scope') == 'Part') {


    $tab     = 'category.parts';
    $ar_file = 'ar_inventory_tables.php';
    $tipo    = 'parts';

    $default = $user->get_tab_defaults($tab);

    $table_views = array(
        'overview'     => array('label' => _('Overview')),
        'performance'  => array('label' => _('Performance')),
        'stock'        => array('label' => _('Stock')),
        'sales'        => array('label' => _('Revenue')),
        'dispatched_q' => array('label' => _('Dispatched (Qs)')),
        'dispatched_y' => array('label' => _('Dispatched (Yrs)')),
        'revenue_q'    => array('label' => _('Revenue (Qs)')),
        'revenue_y'    => array('label' => _('Revenue (Yrs)')),

    );


    $table_filters = array(
        'reference' => array(
            'label' => _('Reference'),
            'title' => _('Part reference')
        ),
        'name'      => array(
            'label' => _('Name'),
            'title' => _('Part name')
        ),

    );

    $parameters = array(
        'parent'     => $state['object'],
        'parent_key' => $state['key'],

    );






    $edit_table_dialog = array(


        'spreadsheet_edit' => array(
            'tipo'       => 'edit_objects',
            'parent'     => $state['object'],
            'parent_key' => $state['key'],
            'object'     => 'part',
            'parent_code' => preg_replace("/[^A-Za-z0-9 ]/", '', $state['_object']->get('Code')),
        ),

    );
    $smarty->assign('edit_table_dialog', $edit_table_dialog);

    $objects = 'part';
    $edit_fields = get_export_edit_template_fields($objects);



    

    $smarty->assign('edit_fields', $edit_fields);


    $table_buttons[] = array(
        'icon'  => 'edit',
        'title' => _("Edit parts"),
        'id'    => 'edit_dialog'
    );



    $table_buttons[] = array(
        'icon'              => 'link',
        'title'             => _('Associate part'),
        'id'                => 'new_record',
        'inline_new_object' => array(
            'field_id'                 => 'Part_Reference',
            'field_label'              => _('Associate part').':',
            'field_edit'               => 'dropdown',
            'object'                   => 'Category_Part',
            'parent'                   => $state['object'],
            'parent_key'               => $state['key'],
            'placeholder'              => _("Part's reference"),
            'dropdown_select_metadata' => base64_encode(
                json_encode(
                    array(
                        'scope'      => 'parts',
                        'parent'     => 'account',
                        'parent_key' => 1,
                        'options'    => array()
                    )
                )
            )

        )

    );



    $smarty->assign('table_buttons', $table_buttons);


}
elseif ($category->get('Category Scope') == 'Supplier') {


    $tab     = 'category.suppliers';
    $ar_file = 'ar_suppliers_tables.php';
    $tipo    = 'suppliers';

    $default = $user->get_tab_defaults($tab);

    $table_views = array(
        'overview' => array(
            'label' => _('Overview'),
            'title' => _('Overview')
        ),
        'contact'  => array(
            'label' => _('Contact'),
            'title' => _('Contact details')
        ),
        'parts'    => array('label' => _("Part's stock")),
        'sales'    => array('label' => _("Part's sales")),
        'sales_q'  => array('label' => _("Part's sales (Qs)")),
        'sales_y'  => array('label' => _("Part's sales (Yrs)")),
        'orders'   => array(
            'label' => _('Orders'),
            'title' => _(
                'Purchase orders, deliveries & invoices'
            )
        ),
    );

    $table_filters = array(
        'code' => array('label' => _('Code')),
        'name' => array('label' => _('Name')),

    );

    $parameters = array(
        'parent'     => $state['object'],
        'parent_key' => $state['key'],

    );


    $table_buttons[] = array(
        'icon'       => 'ship',
        'title'      => _('All suppliers'),
        'change_tab' => 'category.all_subjects'
    );


    $table_buttons[] = array(
        'icon'              => 'link',
        'title'             => _('Associate supplier'),
        'id'                => 'new_record',
        'inline_new_object' => array(
            'field_id'                 => 'Supplier_Code',
            'field_label'              => _('Associate supplier').':',
            'field_edit'               => 'dropdown',
            'object'                   => 'Category_Supplier',
            'parent'                   => $state['object'],
            'parent_key'               => $state['key'],
            'placeholder'              => _("Supplier's code"),
            'dropdown_select_metadata' => base64_encode(
                json_encode(
                    array(
                        'scope'      => 'suppliers',
                        'parent'     => 'account',
                        'parent_key' => 1,
                        'options'    => array()
                    )
                )
            )
        )

    );
    $smarty->assign('table_buttons', $table_buttons);


}
elseif ($category->get('Category Scope') == 'Invoice') {


    $tab     = 'category.invoices';
    $ar_file = 'ar_orders_tables.php';
    $tipo    = 'invoices';

    $default = $user->get_tab_defaults($tab);

    $table_views = array();


    $table_filters = array(
        'customer' => array(
            'label' => _('Customer'),
            'title' => _('Customer name')
        ),
        'number'   => array(
            'label' => _('Number'),
            'title' => _('Invoice number')
        ),

    );

    $parameters = array(
        'parent'     => $state['object'],
        'parent_key' => $state['key'],

    );


    $table_buttons   = array();

    $smarty->assign('table_buttons', $table_buttons);



}


include 'utils/get_table_html.php';



