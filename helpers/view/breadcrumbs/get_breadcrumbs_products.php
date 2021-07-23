<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 23 Jul 2021 14:23:32 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */


function get_breadcrumbs_products( $state, $user): array {


    $branch = [];

    if ($user->get_number_stores() > 1) {
        $branch[] = array(
            'label'     => _('Stores'),
            'icon'      => '',
            'reference' => 'stores'
        );

    }


    if ($state['section'] == 'store') {

        $branch[] = array(
            'label'     => _('Store').' <span class="Store_Code id">'.$state['_object']->get('Store Code').'</span>',
            'icon'      => 'shopping-basket',
            'reference' => 'store/'.$state['_object']->id
        );


    } elseif ($state['section'] == 'settings') {
        $branch[] = array(
            'label'     => _('Settings store').' <span class="Store_Code id">'.$state['_object']->get('Store Code').'</span>',
            'icon'      => 'sliders-h',
            'reference' => 'store/'.$state['_object']->id.'/settings'
        );


    }  elseif ($state['section'] == 'picking_pipeline') {
        $branch[] = array(
            'label'     => _('Picking pipeline').' <span class="Store_Code id">'.$state['_object']->get('Store Code').'</span>',
            'icon'      => 'project-diagram ',
            'reference' => ''
        );


    } elseif ($state['section'] == 'store.new') {
        $branch[] = array(
            'label'     => _('New store'),
            'icon'      => 'shopping-basket',
            'reference' => ''
        );


    } elseif ($state['section'] == 'dashboard') {
        $branch[] = array(
            'label'     => _('Stores'),
            'icon'      => '',
            'reference' => 'stores'
        );

        $branch[]               = array(
            'label'     => _("Store's dashboard").' <span class="id">'.$state['_object']->get('Store Code').'</span>',
            'icon'      => '',
            'reference' => 'store/'.$state['_object']->id
        );


    } elseif ($state['section'] == 'product') {

        if ($state['parent'] == 'store') {
            $branch[] = array(
                'label'     => _('Products').' <span class="id">'.$state['store']->get('Code').'</span>',
                'icon'      => '',
                'reference' => 'products/'.$state['_parent']->id
            );

        } elseif ($state['parent'] == 'category') {
            $category = $state['_parent'];

            list ($branch,$parent_category_keys)=get_breadcrumbs_products_deals_top($branch,$category,$state);


            foreach ($parent_category_keys as $category_key) {
                if (!is_numeric($category_key)) {
                    continue;
                }
                // if ($category_key==$state['parent_key']) {
                // $branch[]=array('label'=>'<span class="Category_Code">'.$category->get('Code').'</span> <span class="italic hide Category_Label">'.$category->get('Label').'</span>', 'icon'=>'', 'reference'=>'');
                // break;
                //}else {

                $parent_category = new Category($category_key);
                if ($parent_category->id) {

                    $branch[] = array(
                        'label'     => $parent_category->get(
                            'Code'
                        ),
                        'icon'      => '',
                        'reference' => 'products/'.$category->get('Store Key').'/category/'.$parent_category->id
                    );

                }
                //}
            }


        } elseif ($state['parent'] == 'order') {
            $order  = new Order($state['parent_key']);
            $store  = new Store($order->get('Order Store Key'));
            $branch = array(
                array(
                    'label'     => _('Home'),
                    'icon'      => 'home',
                    'reference' => ''
                )
            );

            if ($user->get_number_stores() > 1) {
                $branch[] = array(
                    'label'     => _('Orders').' ('._('All stores').')',
                    'icon'      => 'indent',
                    'reference' => 'orders/all'
                );
            }

            $branch[] = array(
                'label'     => _('Orders').' '.$store->data['Store Code'],
                'icon'      => '',
                'reference' => 'orders/'.$store->id
            );

            $branch[] = array(
                'label'     => _('Order').' '.$order->get(
                        'Order Public ID'
                    ),
                'icon'      => 'shopping-cart',
                'reference' => 'orders/'.$store->id.'/'.$state['parent_key']
            );


        }
        $state['current_store'] = $state['store']->id;
        $_ref                   = $state['parent'].'/'.$state['parent_key'].'/product/'.$state['_object']->id;
        if (isset($state['otf'])) {
            $_ref = $state['parent'].'/'.$state['parent_key'].'/item/'.$state['otf'];
        }

        $branch[] = array(
            'label'     => '<span class="id Product_Code">'.$state['_object']->get('Code').'</span>',
            'icon'      => 'cube',
            'reference' => $_ref
        );

    } elseif ($state['section'] == 'products') {


        $branch[] = array(
            'label'     => _('Products').' <span class="id">'.$state['store']->get('Code').'</span>',
            'icon'      => 'cube',
            'reference' => ''
        );


    } elseif ($state['section'] == 'service') {

        if ($state['parent'] == 'store') {
            $branch[] = array(
                'label'     => _('Services').' <span class="id">'.$state['store']->get('Code').'</span>',
                'icon'      => '',
                'reference' => 'services/'.$state['_parent']->id
            );

        } elseif ($state['parent'] == 'category') {

            $category = $state['_parent'];
            $branch[] = array(
                'label'     => _("Services's categories").' <span class="id">'.$state['store']->get('Code').'</span>',
                'icon'      => 'sitemap',
                'reference' => 'services/'.$category->get(
                        'Store Key'
                    ).'/categories'
            );


            if (isset($state['metadata'])) {
                $parent_category_keys = $state['metadata'];
            } else {

                $parent_category_keys = preg_split(
                    '/>/', $category->get('Category Position')
                );
            }


            foreach ($parent_category_keys as $category_key) {
                if (!is_numeric($category_key)) {
                    continue;
                }
                // if ($category_key==$state['parent_key']) {
                // $branch[]=array('label'=>'<span class="Category_Code">'.$category->get('Code').'</span> <span class="italic hide Category_Label">'.$category->get('Label').'</span>', 'icon'=>'', 'reference'=>'');
                // break;
                //}else {

                $parent_category = new Category($category_key);
                if ($parent_category->id) {

                    $branch[] = array(
                        'label'     => $parent_category->get(
                            'Code'
                        ),
                        'icon'      => '',
                        'reference' => 'services/'.$category->get('Store Key').'/category/'.$parent_category->id
                    );

                }
                //}
            }


        } elseif ($state['parent'] == 'order') {
            $order  = new Order($state['parent_key']);
            $store  = new Store($order->get('Order Store Key'));
            $branch = array(
                array(
                    'label'     => _('Home'),
                    'icon'      => 'home',
                    'reference' => ''
                )
            );

            if ($user->get_number_stores() > 1) {
                $branch[] = array(
                    'label'     => _('Orders').' ('._('All stores').')',
                    'icon'      => 'indent',
                    'reference' => 'orders/all'
                );
            }

            $branch[] = array(
                'label'     => _('Orders').' '.$store->data['Store Code'],
                'icon'      => '',
                'reference' => 'orders/'.$store->id
            );

            $branch[] = array(
                'label'     => _('Order').' '.$order->get(
                        'Order Public ID'
                    ),
                'icon'      => 'shopping-cart',
                'reference' => 'orders/'.$store->id.'/'.$state['parent_key']
            );


        }


        $state['current_store'] = $state['store']->id;
        $_ref                   = $state['parent'].'/'.$state['parent_key'].'/service/'.$state['_object']->id;
        if (isset($state['otf'])) {
            $_ref = $state['parent'].'/'.$state['parent_key'].'/item/'.$state['otf'];
        }

        $branch[] = array(
            'label'     => '<span class="id Service_Code">'.$state['_object']->get('Code').'</span>',
            'icon'      => 'cube',
            'reference' => $_ref
        );

    } elseif ($state['section'] == 'services') {


        $branch[] = array(
            'label'     => _(
                    'Services'
                ).' <span class="id">'.$state['store']->get('Code').'</span>',
            'icon'      => 'cube',
            'reference' => ''
        );


    } elseif ($state['section'] == 'categories') {
        $branch[] = array(
            'label'     => _("Product's categories").' <span class="id">'.$state['store']->get('Code').'</span>',
            'icon'      => 'sitemap',
            'reference' => ''
        );
    } elseif ($state['section'] == 'category') {
        $category = $state['_object'];
        list ($branch,$parent_category_keys)=get_breadcrumbs_products_deals_top($branch,$category,$state);



        foreach ($parent_category_keys as $category_key) {
            if (!is_numeric($category_key)) {
                continue;
            }
            if ($category_key == $state['key']) {
                $branch[] = array(
                    'label'     => '<span class="Category_Code">'.$category->get('Code').'</span> <span class="italic hide Category_Label">'.$category->get('Label').'</span>',
                    'icon'      => '',
                    'reference' => ''
                );
                break;
            } else {

                $parent_category = new Category($category_key);
                if ($parent_category->id) {

                    $branch[] = array(
                        'label'     => $parent_category->get(
                            'Code'
                        ),
                        'icon'      => '',
                        'reference' => 'products/'.$category->get('Store Key').'/category/'.$parent_category->id
                    );

                }
            }
        }
    } elseif ($state['section'] == 'main_category.new') {
        $branch[] = array(
            'label'     => _("Product's categories"),
            'icon'      => 'sitemap',
            'reference' => 'products/'.$state['parent_key'].'/categories'
        );
        $branch[] = array(
            'label'     => _('New main category'),
            'icon'      => '',
            'reference' => ''
        );


    } elseif ($state['section'] == 'refund.new') {


        $branch[] = array(
            'label'     => '<span class="id ">'.$state['_object']->get('Order Public ID').'</span>',
            'icon'      => 'shopping-cart',
            'reference' => ''
        );

    } elseif ($state['section'] == 'customer') {


        if ($state['parent'] == 'campaign') {
            $branch[] = array(
                'label'     => _('Marketing').' <span class="Store_Code">'.$state['store']->get('Code').'</span>',
                'icon'      => 'bullhorn',
                'reference' => 'marketing/'.$state['store']->id
            );

            $branch[] = array(
                'label'     => '<span class="Deal_Campaign_Name">'.$state['_parent']->get('Name').'</span>',
                'icon'      => 'tags',
                'reference' => 'campaigns/'.$state['store']->id.'/'.$state['_parent']->id
            );

            $branch[] = array(
                'label'     => '<span class="id ">'.$state['_object']->get_formatted_id().'</span>',
                'icon'      => 'user',
                'reference' => ''
            );
        } elseif ($state['parent'] == 'deal') {

            $branch[] = array(
                'label'     => _('Marketing').' <span class="Store_Code">'.$state['store']->get('Code').'</span>',
                'icon'      => 'bullhorn',
                'reference' => 'marketing/'.$state['store']->id
            );

            $campaign = get_object('Campaign', $state['_parent']->get('Deal Campaign Key'));

            $branch[] = array(
                'label'     => '<span class="Deal_Campaign_Name">'.$campaign->get('Name').'</span>',
                'icon'      => 'tags',
                'reference' => 'campaigns/'.$state['store']->id.'/'.$state['_parent']->id
            );

            $branch[] = array(
                'label'     => '<span class="Deal_Name">'.$state['_parent']->get('Name').'</span>',
                'icon'      => 'tags',
                'reference' => 'campaigns/'.$state['store']->id.'/'.$state['_parent']->get('Deal Campaign Key').'/deal/'.$state['_parent']->id
            );

            $branch[] = array(
                'label'     => '<span class="id ">'.$state['_object']->get_formatted_id().'</span>',
                'icon'      => 'user',
                'reference' => ''
            );
        }

    } elseif ($state['section'] == 'website') {

        $branch[]               = array(
            'label'     => _('Store').' <span class="Store_Code id">'.$state['store']->get('Code').'</span>',
            'icon'      => 'shopping-basket',
            'reference' => 'store/'.$state['store']->id
        );
        $state['current_store'] = $state['store']->id;

        $branch[] = array(
            'label'     => '<span class="id Website_Code">'.$state['website']->get('Code').'</span>',
            'icon'      => 'globe',
            'reference' => ''
        );
    } elseif ($state['section'] == 'webpage') {

        $branch[]               = array(
            'label'     => _('Store').' <span class="Store_Code id">'.$state['store']->get('Code').'</span>',
            'icon'      => 'shopping-basket',
            'reference' => 'store/'.$state['store']->id
        );
        $state['current_store'] = $state['store']->id;

        $branch[] = array(
            'label'     => '<span class=" Website_Code">'.$state['website']->get('Code').'</span>',
            'icon'      => 'globe',
            'reference' => 'store/'.$state['store']->id.'/website'
        );


        $branch[] = array(
            'label'     => '<span class="id Webpage_Code">'.$state['_object']->get('Code').'</span>',
            'icon'      => 'browser',
            'reference' => ''
        );


    } elseif ($state['section'] == 'deleted.webpage') {

        $branch[]               = array(
            'label'     => _('Store').' <span class="Store_Code id">'.$state['store']->get('Code').'</span>',
            'icon'      => 'shopping-basket',
            'reference' => 'store/'.$state['store']->id
        );
        $state['current_store'] = $state['store']->id;

        $branch[] = array(
            'label'     => '<span class=" Website_Code">'.$state['website']->get('Code').'</span>',
            'icon'      => 'globe',
            'reference' => 'store/'.$state['store']->id.'/website'
        );


        $branch[] = array(
            'label'     => '<span class=" Webpage_Code error"> ('._('Deleted').')  '.$state['_object']->get('Page Title').'</span>',
            'icon'      => 'browser error',
            'reference' => ''
        );


    } elseif ($state['section'] == 'webpage.new') {


        $branch[]               = array(
            'label'     => _('Store').' <span class="Store_Code id">'.$state['store']->get('Code').'</span>',
            'icon'      => 'shopping-basket',
            'reference' => 'store/'.$state['store']->id
        );
        $state['current_store'] = $state['store']->id;


        $branch[] = array(
            'label'     => '<span class=" Website_Code">'.$state['website']->get('Code').'</span>',
            'icon'      => 'globe',
            'reference' => 'store/'.$state['store']->id.'/website'
        );

        $branch[] = array(
            'label'     => _('New webpage'),
            'icon'      => '',
            'reference' => '',
        );
    } elseif ($state['section'] == 'website.new') {


        $branch[]               = array(
            'label'     => _('Store').' <span class="Store_Code id">'.$state['store']->get('Code').'</span>',
            'icon'      => 'shopping-basket',
            'reference' => 'store/'.$state['store']->id
        );



        $branch[] = array(
            'label'     => _('New website'),
            'icon'      => '',
            'reference' => '',
        );
    } elseif ($state['section'] == 'deal') {

        $branch=get_breadcrumbs_products_deals($branch,$state);



        $branch[] = array(
            'label'     => '<span class="Deal_Name">'.$state['_object']->get('Name').'</span>',
            'icon'      => 'tag',
            'reference' => ''
        );
    } elseif ($state['section'] == 'charge') {

        $branch[] = array(
            'label'     => _('Store').' <span class="Store_Code id">'.$state['store']->get('Store Code').'</span>',
            'icon'      => 'shopping-basket',
            'reference' => 'store/'.$state['store']->id
        );
        $branch[] = array(
            'label'     => _('Charge').': <span class="Charge_Name id">'.$state['_object']->get('Charge Name').'</span>',
            'icon'      => 'money',
            'reference' => ''
        );


    } elseif ($state['section'] == 'deal_component') {

        $branch=get_breadcrumbs_products_deals($branch,$state);



    }


    return $branch;

}

function get_breadcrumbs_products_deals_top($branch,$category,$state): array {

    $branch[] = array(
        'label'     => _("Product's categories").' <span class="id">'.$state['store']->get('Code').'</span>',
        'icon'      => 'sitemap',
        'reference' => 'products/'.$category->get(
                'Store Key'
            ).'/categories'
    );


    if (isset($state['metadata'])) {
        $parent_category_keys = $state['metadata'];
    } else {

        $parent_category_keys = preg_split(
            '/>/', $category->get('Category Position')
        );
    }
    return [$branch,$parent_category_keys];
}


function get_breadcrumbs_products_deals($branch,$state){
    $category = $state['_parent'];

    list ($branch,$parent_category_keys)=get_breadcrumbs_products_deals_top($branch,$category,$state);




    foreach ($parent_category_keys as $category_key) {
        if (!is_numeric($category_key)) {
            continue;
        }
        if ($category_key == $state['key']) {
            $branch[] = array(
                'label'     => '<span class="Category_Code">'.$category->get('Code').'</span> <span class="italic hide Category_Label">'.$category->get('Label').'</span>',
                'icon'      => '',
                'reference' => ''
            );
            break;
        } else {

            $parent_category = new Category($category_key);
            if ($parent_category->id) {

                $branch[] = array(
                    'label'     => $parent_category->get(
                        'Code'
                    ),
                    'icon'      => '',
                    'reference' => 'products/'.$category->get('Store Key').'/category/'.$parent_category->id
                );

            }
        }
    }
    return $branch;
}