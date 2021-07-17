<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 08 Jul 2021 20:43:39 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */


function get_breadcrumbs($db, $state, $user, $smarty, $account): array {

    $branch = array();

    switch ($state['module']) {
        case 'dashboard':


            $branch = array(
                array(
                    'label'     => '<span >'._('Dashboard').'</span>',
                    'icon'      => 'dashboard',
                    'reference' => '/dashboard'
                )
            );

            break;

        case 'products_server':
            if ($state['section'] == 'stores') {
                $branch[] = array(
                    'label'     => _('Stores'),
                    'icon'      => 'shopping-basket',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'products') {

                $branch[] = array(
                    'label'     => _('Products (All stores)'),
                    'icon'      => 'cube',
                    'reference' => ''
                );
            }


            break;

        case 'products':

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
                $state['current_store'] = $state['_object']->id;

            } elseif ($state['section'] == 'product') {

                if ($state['parent'] == 'store') {
                    $branch[] = array(
                        'label'     => _('Products').' <span class="id">'.$state['store']->get('Code').'</span>',
                        'icon'      => '',
                        'reference' => 'products/'.$state['_parent']->id
                    );

                } elseif ($state['parent'] == 'category') {

                    $category = $state['_parent'];
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
                $state['current_store'] = $state['store']->id;


                $branch[] = array(
                    'label'     => _('New website'),
                    'icon'      => '',
                    'reference' => '',
                );
            } elseif ($state['section'] == 'deal') {


                $category = $state['_parent'];
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


                $category = $state['_parent'];
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


            }

            break;
        case 'customers_server':

            if ($state['section'] == 'customers') {
                $branch[] = array(
                    'label'     => _('Customers (All stores)'),
                    'icon'      => '',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'email_communications') {
                $branch[] = array(
                    'label'     => _('Customer notifications (All stores)'),
                    'icon'      => '',
                    'reference' => ''
                );
            }

            break;


        case 'customers':


            $state['current_store'] = $state['store']->id;


            switch ($state['parent']) {
                case 'store':
                    $store                  = new Store($state['parent_key']);
                    $state['current_store'] = $store->id;

                    break;


            }

            if ($user->get_number_stores() > 1) {


                if (in_array(
                    $state['section'], array(
                                         'email_campaigns',
                                         'email_campaign_type',
                                         'email_tracking',
                                         'mailshot'
                                     )
                )) {
                    $branch[] = array(
                        'label'     => _('(All stores)'),
                        'icon'      => 'mail-bulk',
                        'reference' => 'customers/all/email_communications'
                    );
                } else {
                    $branch[] = array(
                        'label'     => _('(All stores)'),
                        'icon'      => 'window-restore',
                        'reference' => 'customers/all'
                    );
                }


            }



            switch ($state['section']) {
                case 'list':


                    $branch[] = array(
                        'label'     => _("Customer's lists").' '.$state['store']->data['Store Code'],
                        'icon'      => 'list',
                        'reference' => 'customers/'.$state['store']->id.'/lists'
                    );
                    $branch[] = array(
                        'label'     => '<span class="List_Name">'.$state['_object']->get('List Name').'</span>',
                        'icon'      => '',
                        'reference' => 'customers/'.$state['store']->id.'/lists/'.$state['_object']->id
                    );

                    break;

                case 'customer':
                    if ($state['parent'] == 'store') {
                        $branch[] = array(
                            'label'     => _('Customers').' '.$state['store']->get('Store Code'),
                            'icon'      => 'users',
                            'reference' => 'customers/'.$state['store']->id
                        );
                        $branch[] = array(
                            'label'     => $state['_object']->get_formatted_id(),
                            'icon'      => 'user',
                            'reference' => 'customer/'.$state['_object']->id
                        );

                    } elseif ($state['parent'] == 'list') {

                        $branch[] = array(
                            'label'     => _("Customer's lists").' '.$state['store']->data['Store Code'],
                            'icon'      => 'list',
                            'reference' => 'customers/'.$state['store']->id.'/lists'
                        );
                        $branch[] = array(
                            'label'     => '<span class="List_Name">'.$state['_parent']->get('List Name').'</span>',
                            'icon'      => '',
                            'reference' => 'customers/'.$state['store']->id.'/lists/'.$state['_parent']->id
                        );
                        $branch[] = array(
                            'label'     => _('Customer').' '.$state['_object']->get_formatted_id(),
                            'icon'      => 'user',
                            'reference' => 'customers/'.$state['store']->id.'/lists/'.$state['_parent']->id.'/'.$state['_object']->id
                        );
                    } elseif ($state['parent'] == 'category') {
                        $store = $state['store'];


                        $branch[] = array(
                            'label'     => _("Customer's categories").' '.$store->data['Store Code'],
                            'icon'      => 'sitemap',
                            'reference' => 'customers/'.$store->id.'/categories'
                        );

                        $grandparent = get_object('Category', $state['_parent']->get('Category Parent Key'));

                        $branch[] = array(
                            'label'     => $grandparent->get('Code'),
                            'icon'      => '',
                            'reference' => 'customers/'.$store->id.'/category/'.$state['_parent']->get('Category Parent Key')
                        );

                        $branch[] = array(
                            'label'     => $state['_parent']->get('Code'),
                            'icon'      => '',
                            'reference' => 'customers/'.$store->id.'/category/'.$state['_parent']->get('Category Parent Key').'/'.$state['_parent']->id
                        );


                        $branch[] = array(
                            'label'     => _('Customer').' '.$state['_object']->get_formatted_id(),
                            'icon'      => 'user',
                            'reference' => 'customers/'.$store->id.'/category/'.$state['_parent']->get('Category Parent Key').'/'.$state['_parent']->id.'/customer/'.$state['_object']->id
                        );
                    }
                    break;

                case 'deleted_customer':
                    $branch[] = array(
                        'label'     => _('Customers').' '.$state['store']->get('Store Code'),
                        'icon'      => 'users',
                        'reference' => 'customers/'.$state['store']->id
                    );
                    $branch[] = array(
                        'label'     => $state['_object']->get_formatted_id().' ('._('Deleted').')',
                        'icon'      => 'user',
                        'reference' => 'customer/'.$state['_object']->id
                    );


                    break;


                case 'customer_client':
                    $branch[] = array(
                        'label'     => _('Customers').' '.$state['store']->get('Store Code'),
                        'icon'      => 'users',
                        'reference' => 'customers/'.$state['store']->id
                    );
                    $branch[] = array(
                        'label'     => $state['_parent']->get_formatted_id(),
                        'icon'      => 'user',
                        'reference' => 'customers/'.$state['store']->id.'/'.$state['_parent']->id
                    );
                    $branch[] = array(
                        'label'     => '<span class="Customer_Client_Code">'.$state['_object']->get('Code').'</span>',
                        'icon'      => 'address-book',
                        'reference' => ''
                    );
                    break;
                case 'customer_client.new':
                    $branch[] = array(
                        'label'     => _('Customers').' '.$state['store']->get('Store Code'),
                        'icon'      => 'users',
                        'reference' => 'customers/'.$state['store']->id
                    );
                    $branch[] = array(
                        'label'     => $state['_parent']->get_formatted_id(),
                        'icon'      => 'user',
                        'reference' => 'customers/'.$state['store']->id.'/'.$state['_parent']->id
                    );
                    $branch[] = array(
                        'label'     => _("New customer's client"),
                        'icon'      => 'address-book',
                        'reference' => ''
                    );
                    break;
                case 'prospect':
                    $branch[] = array(
                        'label'     => _('Prospects').' '.$store->data['Store Code'],
                        'icon'      => 'user-friends',
                        'reference' => 'prospects/'.$store->id
                    );

                    $branch[] = array(
                        'label'     => '<span class="Prospect_Name id">'.$state['_object']->get('Name').'</span>',
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;
                case 'prospects.template.new':
                    $branch[] = array(
                        'label'     => _('Prospects').' '.$store->data['Store Code'],
                        'icon'      => 'user-friends',
                        'reference' => 'prospects/'.$store->id
                    );

                    $branch[] = array(
                        'label'     => _('New prospect invitation template'),
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;

                case 'email_tracking':


                    $store = get_object('Store', $state['_parent']->get('Store Key'));


                    if ($state['_parent']->get_object_name() == 'Prospect') {
                        $branch[] = array(
                            'label'     => _('Prospects').' '.$store->data['Store Code'],
                            'icon'      => 'user-friends',
                            'reference' => 'prospects/'.$store->id
                        );

                        $branch[] = array(
                            'label'     => '<span class="Prospect_Name id">'.$state['_parent']->get('Name').'</span>',
                            'icon'      => '',
                            'reference' => 'prospects/'.$store->id.'/'.$state['_parent']->id
                        );

                        $branch[] = array(
                            'label'     => _('Invitation email'),
                            'icon'      => 'paper-plane',
                            'reference' => ''
                        );

                    } elseif ($state['_parent']->get_object_name() == 'Customer') {
                        $branch[] = array(
                            'label'     => _('Customers').' '.$store->data['Store Code'],
                            'icon'      => 'users',
                            'reference' => 'customers/'.$store->id
                        );

                        $branch[] = array(
                            'label'     => '<span class="Customer_Name id">'.$state['_parent']->get('Name').'</span>',
                            'icon'      => 'user',
                            'reference' => 'customers/'.$store->id.'/'.$state['_parent']->id
                        );

                        $branch[] = array(
                            'label'     => _('Sent email'),
                            'icon'      => 'paper-plane',
                            'reference' => ''
                        );

                    }

                    break;


                case 'prospect.compose_email':


                    $store = get_object('Store', $state['_parent']->get('Store Key'));

                    $branch[] = array(
                        'label'     => _('Prospects').' '.$store->data['Store Code'],
                        'icon'      => 'user-friends',
                        'reference' => 'prospects/'.$store->id
                    );

                    $branch[] = array(
                        'label'     => '<span class="Prospect_Name id">'.$state['_parent']->get('Name').'</span>',
                        'icon'      => '',
                        'reference' => 'prospects/'.$store->id.'/'.$state['_parent']->id
                    );

                    $branch[] = array(
                        'label'     => _('Composing personalized invitation'),
                        'icon'      => 'envelope',
                        'reference' => ''
                    );
                    break;
                case 'dashboard':
                    $branch[] = array(
                        'label'     => _("Customer's dashboard").' '.$store->data['Store Code'],
                        'icon'      => 'dashboard',
                        'reference' => 'customers/dashboard/'.$store->id
                    );
                    break;
                case 'customers':
                    $branch[] = array(
                        'label'     => _('Customers').' '.$store->data['Store Code'],
                        'icon'      => 'users',
                        'reference' => 'customers/'.$store->id
                    );
                    break;

                case 'prospects':
                    $branch[] = array(
                        'label'     => _('Prospects').' '.$store->data['Store Code'],
                        'icon'      => 'user-friends',
                        'reference' => 'prospects/'.$store->id
                    );
                    break;

                case 'prospect.new':
                    $branch[] = array(
                        'label'     => _('Prospects').' '.$store->data['Store Code'],
                        'icon'      => 'user-friends',
                        'reference' => 'prospects/'.$store->id
                    );
                    $branch[] = array(
                        'label'     => _('New prospect'),
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;

                case 'prospects.email_template':
                    $branch[] = array(
                        'label'     => _('Prospects').' '.$store->data['Store Code'],
                        'icon'      => 'user-friends',
                        'reference' => 'prospects/'.$store->id.'&tab=prospects'
                    );

                    $branch[] = array(
                        'label'     => _('Invitation templates'),
                        'icon'      => 'chalkboard',
                        'reference' => 'prospects/'.$store->id.'&tab=prospects.email_templates'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Email_Template_Name">'.$state['_object']->get('Name').'</span>',
                        'icon'      => 'envelope',
                        'reference' => 'prospects/'.$store->id.'/template/'
                    );

                    break;


                case 'categories':
                    $branch[] = array(
                        'label'     => _("Customer's categories").' '.$store->data['Store Code'],
                        'icon'      => 'sitemap',
                        'reference' => 'customers/'.$store->id.'/categories'
                    );
                    break;
                case 'sub_category':

                    $store = $state['store'];

                    $branch[] = array(
                        'label'     => _("Customer's categories").' '.$store->data['Store Code'],
                        'icon'      => 'sitemap',
                        'reference' => 'customers/'.$store->id.'/categories'
                    );

                    $branch[] = array(
                        'label'     => $state['_object']->get('Code'),
                        'icon'      => '',
                        'reference' => 'customers/'.$store->id.'/category/'.$state['_object']->id
                    );
                    break;
                case 'category':

                    $store = $state['store'];

                    $branch[] = array(
                        'label'     => _("Customer's categories").' '.$store->data['Store Code'],
                        'icon'      => 'sitemap',
                        'reference' => 'customers/'.$store->id.'/categories'
                    );

                    $branch[] = array(
                        'label'     => $state['_parent']->get('Code'),
                        'icon'      => '',
                        'reference' => 'customers/'.$store->id.'/category/'.$state['_parent']->id
                    );

                    $branch[] = array(
                        'label'     => $state['_object']->get('Code'),
                        'icon'      => '',
                        'reference' => 'customers/'.$store->id.'/category/'.$state['_parent']->id.'/'.$state['_object']->id
                    );
                    break;


                case 'lists':
                    $branch[] = array(
                        'label'     => _(
                                "Customer's lists"
                            ).' '.$store->data['Store Code'],
                        'icon'      => 'list',
                        'reference' => 'customers/'.$store->id.'/lists'
                    );
                    break;
                case 'insights':
                    $branch[] = array(
                        'label'     => _("Customer's insights").' '.$store->data['Store Code'],
                        'icon'      => 'graduation-cap',
                        'reference' => 'customers/'.$store->id.'/insights'
                    );
                    break;


                case 'poll_query.new':
                    $branch[] = array(
                        'label'     => _("Customer's insights").' '.$store->data['Store Code'],
                        'icon'      => 'graduation-cap',
                        'reference' => 'customers/'.$store->id.'/insights'
                    );
                    $branch[] = array(
                        'label'     => _('New poll query'),
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;
                case 'poll_query':
                    $branch[] = array(
                        'label'     => _("Customer's insights").' '.$store->data['Store Code'],
                        'icon'      => 'graduation-cap',
                        'reference' => 'customers/'.$store->id.'/insights'
                    );
                    $branch[] = array(
                        'label'     => sprintf(_('Poll query %s'), $state['_object']->get('Name')),
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;

                case 'poll_query_option.new':


                    $store = get_object('Store', $state['_parent']->get('Store Key'));

                    $branch[] = array(
                        'label'     => _("Customer's insights").' '.$store->data['Store Code'],
                        'icon'      => 'graduation-cap',
                        'reference' => 'customers/'.$store->id.'/insights'
                    );
                    $branch[] = array(
                        'label'     => sprintf(_('Poll query %s'), $state['_parent']->get('Name')),
                        'icon'      => '',
                        'reference' => 'customers/'.$store->id.'/poll_query/'.$state['_parent']->id,
                    );
                    $branch[] = array(
                        'label'     => _('New option'),
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;
                case 'poll_query_option':
                    $store    = get_object('Store', $state['_object']->get('Store Key'));
                    $branch[] = array(
                        'label'     => _("Customer's insights").' '.$store->data['Store Code'],
                        'icon'      => 'graduation-cap',
                        'reference' => 'customers/'.$store->id.'/insights'
                    );
                    $branch[] = array(
                        'label'     => sprintf(_('Poll query %s'), $state['_parent']->get('Name')),
                        'icon'      => '',
                        'reference' => 'customers/'.$store->id.'/poll_query/'.$state['_parent']->id
                    );
                    $branch[] = array(
                        'label'     => sprintf(_('Option %s'), $state['_object']->get('Name')),
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;
                case 'deleted_customer_poll_query_option':

                    //print_r($state['_object']->data);

                    $store    = get_object('Store', $state['_parent']->get('Store Key'));
                    $branch[] = array(
                        'label'     => _("Customer's insights").' '.$store->data['Store Code'],
                        'icon'      => 'graduation-cap',
                        'reference' => 'customers/'.$store->id.'/insights'
                    );
                    $branch[] = array(
                        'label'     => sprintf(_('Poll query %s'), $state['_parent']->get('Name')),
                        'icon'      => '',
                        'reference' => 'customers/'.$store->id.'/poll_query/'.$state['_parent']->id
                    );
                    $branch[] = array(
                        'label'     => sprintf(_('Deleted option %s'), $state['_object']->get('Customer Poll Query Option Deleted Name')),
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;
                case 'pending_orders':
                    $branch[] = array(
                        'label'     => _("Pending orders").' '.$store->data['Store Code'],
                        'icon'      => 'clock',
                        'reference' => 'customers/pending_orders/'.$store->id
                    );
                    break;


                case 'product':


                    $branch[] = array(
                        'label'     => _('Customers').' '.$state['store']->get('Store Code'),
                        'icon'      => 'users',
                        'reference' => 'customers/'.$state['store']->id
                    );
                    $branch[] = array(
                        'label'     => $state['_parent']->get_formatted_id(),
                        'icon'      => 'user',
                        'reference' => 'customer/'.$state['_parent']->id
                    );
                    $branch[] = array(
                        'label'     => $state['_object']->get('Code'),
                        'icon'      => 'cube',
                        'reference' => 'customer/'.$state['_parent']->id.'/product/'.$state['_object']->id
                    );


                    break;
                case 'customer.attachment':


                    $branch[] = array(
                        'label'     => _('Customers').' '.$state['store']->get('Store Code'),
                        'icon'      => 'users',
                        'reference' => 'customers/'.$state['store']->id
                    );
                    $branch[] = array(
                        'label'     => $state['_parent']->get_formatted_id(),
                        'icon'      => 'user',
                        'reference' => 'customers/'.$state['_parent']->get('Store Key').'/'.$state['_parent']->id
                    );
                    $branch[] = array(
                        'label'     => $state['_object']->get('Attachment File Original Name'),
                        'icon'      => 'paperclip',
                        'reference' => 'customers/'.$state['_parent']->get('Store Key').'//'.$state['_parent']->id.'/attachment/'.$state['_object']->id
                    );


                    break;
                case 'customer.attachment.new':


                    $branch[] = array(
                        'label'     => _('Customers').' '.$state['store']->get('Store Code'),
                        'icon'      => 'users',
                        'reference' => 'customers/'.$state['store']->id
                    );
                    $branch[] = array(
                        'label'     => $state['_parent']->get_formatted_id(),
                        'icon'      => 'user',
                        'reference' => 'customers/'.$state['_parent']->get('Store Key').'/'.$state['_parent']->id
                    );
                    $branch[] = array(
                        'label'     => _('Upload attachment'),
                        'icon'      => 'paperclip',
                        'reference' => ''
                    );


                    break;

            }
            break;

        case 'mailroom_server':


            $branch[] = array(
                'label'     => _('Mailroom').' ('._('All stores').')',
                'icon'      => 'mail-bulk',
                'reference' => ''
            );


            break;
        case 'mailroom':

            $branch[] = array(
                'label'     => _('Mailroom').' ('._('All stores').')',
                'icon'      => 'mail-bulk',
                'reference' => ''
            );
            switch ($state['section']) {

                case 'marketing':
                    $branch[] = array(
                        'label'     => _('Marketing emails').' '.$state['store']->get('Code'),
                        'icon'      => 'bullhorn',
                        'reference' => 'mailroom/'.$state['store']->id.'/marketing'
                    );
                    break;
                case 'user_notifications':
                    $branch[] = array(
                        'label'     => _('Staff notifications').' '.$state['store']->get('Code'),
                        'icon'      => 'bell',
                        'reference' => 'mailroom/'.$state['store']->id.'/staff_notifications'
                    );
                    break;
                case 'customer_notifications':
                    $branch[] = array(
                        'label'     => _('Customers notifications').' '.$state['store']->get('Code'),
                        'icon'      => 'user',
                        'reference' => 'mailroom/'.$state['store']->id.'/notifications'
                    );
                    break;
                case 'email_campaign_type':

                    if ($state['_object']->get('Email Campaign Type Scope') == 'User Notification') {

                        $branch[] = array(
                            'label'     => _('Staff notifications').' '.$state['store']->get('Code'),
                            'icon'      => 'bell',
                            'reference' => 'mailroom/'.$state['store']->id.'/staff_notifications'
                        );

                    } elseif ($state['_object']->get('Email Campaign Type Scope') == 'Customer Notification') {

                        $branch[] = array(
                            'label'     => _('Customers notifications').' '.$state['store']->get('Code'),
                            'icon'      => 'user',
                            'reference' => 'mailroom/'.$state['store']->id.'/notifications'
                        );

                    } else {
                        $branch[] = array(
                            'label'     => _('Marketing emails').' '.$state['store']->get('Code'),
                            'icon'      => 'bullhorn',
                            'reference' => 'mailroom/'.$state['store']->id.'/marketing'
                        );

                    }
                    $branch[] = array(
                        'label'     => '<span class="id">'.$state['_object']->get('Label').'</span>',
                        'icon'      => $state['_object']->get('Icon'),
                        'reference' => ''
                    );
                    break;

                case 'mailshot':

                    if ($state['_parent']->get('Email Campaign Type Scope') == 'User Notification') {

                        $branch[] = array(
                            'label'     => _('Staff notifications').' '.$state['store']->get('Code'),
                            'icon'      => 'bell',
                            'reference' => 'mailroom/'.$state['store']->id.'/staff_notifications'
                        );
                        $_link    = 'staff_notifications';
                    } elseif ($state['_parent']->get('Email Campaign Type Scope') == 'Customer Notification') {

                        $branch[] = array(
                            'label'     => _('Customers notifications').' '.$state['store']->get('Code'),
                            'icon'      => 'user',
                            'reference' => 'mailroom/'.$state['store']->id.'/notifications'
                        );
                        $_link    = 'notifications';
                    } else {
                        $branch[] = array(
                            'label'     => _('Marketing emails').' '.$state['store']->get('Code'),
                            'icon'      => 'bullhorn',
                            'reference' => 'mailroom/'.$state['store']->id.'/marketing'
                        );
                        $_link    = 'marketing';
                    }
                    $branch[] = array(
                        'label'     => '<span class="id">'.$state['_parent']->get('Label').'</span>',
                        'icon'      => $state['_parent']->get('Icon'),
                        'reference' => 'mailroom/'.$state['store']->id.'/'.$_link.'/'.$state['_parent']->id

                    );

                    $branch[] = array(
                        'label'     => '<span class="Email_Campaign_Name id">'.$state['_object']->get('Name').'</span>',
                        'icon'      => 'container-storage',
                        'reference' => ''
                    );

                    break;
                case 'mailshot.new':

                    if ($state['_parent']->get('Email Campaign Type Scope') == 'User Notification') {

                        $branch[] = array(
                            'label'     => _('Staff notifications').' '.$state['store']->get('Code'),
                            'icon'      => 'bell',
                            'reference' => 'mailroom/'.$state['store']->id.'/staff_notifications'
                        );
                        $_link    = 'staff_notifications';
                    } elseif ($state['_parent']->get('Email Campaign Type Scope') == 'Customer Notification') {

                        $branch[] = array(
                            'label'     => _('Customers notifications').' '.$state['store']->get('Code'),
                            'icon'      => 'user',
                            'reference' => 'mailroom/'.$state['store']->id.'/notifications'
                        );
                        $_link    = 'notifications';
                    } else {
                        $branch[] = array(
                            'label'     => _('Marketing emails').' '.$state['store']->get('Code'),
                            'icon'      => 'bullhorn',
                            'reference' => 'mailroom/'.$state['store']->id.'/marketing'
                        );
                        $_link    = 'marketing';
                    }
                    $branch[] = array(
                        'label'     => '<span class="id">'.$state['_parent']->get('Label').'</span>',
                        'icon'      => $state['_parent']->get('Icon'),
                        'reference' => 'mailroom/'.$state['store']->id.'/'.$_link.'/'.$state['_parent']->id

                    );

                    $branch[] = array(
                        'label'     => _('New mailshot'),
                        'icon'      => 'container-storage',
                        'reference' => ''
                    );

                    break;
                case 'email_tracking':

                    $email_campaign_type = get_object('EmailCampaignType', $state['_parent']->get('Email Campaign Email Template Type Key'));

                    if ($email_campaign_type->get('Email Campaign Type Scope') == 'User Notification') {

                        $branch[] = array(
                            'label'     => _('Staff notifications').' '.$state['store']->get('Code'),
                            'icon'      => 'bell',
                            'reference' => 'mailroom/'.$state['store']->id.'/staff_notifications'
                        );
                        $_link    = 'staff_notifications';
                    } elseif ($email_campaign_type->get('Email Campaign Type Scope') == 'Customer Notification') {

                        $branch[] = array(
                            'label'     => _('Customers notifications').' '.$state['store']->get('Code'),
                            'icon'      => 'user',
                            'reference' => 'mailroom/'.$state['store']->id.'/notifications'
                        );
                        $_link    = 'notifications';
                    } else {
                        $branch[] = array(
                            'label'     => _('Marketing emails').' '.$state['store']->get('Code'),
                            'icon'      => 'bullhorn',
                            'reference' => 'mailroom/'.$state['store']->id.'/marketing'
                        );
                        $_link    = 'marketing';

                    }
                    $branch[] = array(
                        'label'     => '<span class="id">'.$email_campaign_type->get('Label').'</span>',
                        'icon'      => $email_campaign_type->get('Icon'),
                        'reference' => 'mailroom/'.$state['store']->id.'/'.$_link.'/'.$email_campaign_type->id
                    );


                    $branch[] = array(
                        'label'     => '<span class="Email_Campaign_Name id">'.$state['_parent']->get('Name').'</span>',
                        'icon'      => 'container-storage',
                        'reference' => 'mailroom/'.$state['store']->id.'/'.$_link.'/'.$email_campaign_type->id.'/mailshot/'.$state['_parent']->id

                    );
                    $branch[] = array(
                        'label'     => '<span class="id">'.$state['_object']->get('Email Tracking Email').'</span>',
                        'icon'      => 'paper-plane',
                        'reference' => ''
                    );
                    break;

            }


            break;

        case 'suppliers':
            if ($state['section'] == 'dashboard') {
                $branch[] = array(
                    'label'     => _("Supplier's dashboard"),
                    'icon'      => 'tachometer-alt',
                    'reference' => 'dashboard'
                );
            } elseif ($state['section'] == 'suppliers') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => 'hand-holding-box',
                    'reference' => 'suppliers'
                );
            } elseif ($state['section'] == 'settings') {
                $branch[] = array(
                    'label'     => _("Suppliers' settings"),
                    'icon'      => 'sliders',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'orders') {
                $branch[] = array(
                    'label'     => _('Purchase orders'),
                    'icon'      => 'clipboard',
                    'reference' => 'suppliers.orders'
                );
            } elseif ($state['section'] == 'deliveries') {
                $branch[] = array(
                    'label'     => _('Deliveries'),
                    'icon'      => 'truck',
                    'reference' => 'suppliers.deliveries'
                );
            } elseif ($state['section'] == 'agents') {
                $branch[] = array(
                    'label'     => _('Agents'),
                    'icon'      => 'user-secret',
                    'reference' => 'agents'
                );
            } elseif ($state['section'] == 'agent.new') {
                $branch[] = array(
                    'label'     => _('Agents'),
                    'icon'      => '',
                    'reference' => 'agents'
                );
                $branch[] = array(
                    'label'     => _('New agent'),
                    'icon'      => 'user-secret',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'supplier') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="id Supplier_Code">'.$state['_object']->get('Code').'</span>',
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['key']
                );

            } elseif ($state['section'] == 'supplier.new') {

                if ($state['parent'] == 'agent') {
                    $branch[] = array(
                        'label'     => _('Agents'),
                        'icon'      => '',
                        'reference' => 'agents'
                    );
                    $branch[] = array(
                        'label'     => '<span class="Agent_Code">'.$state['_parent']->get('Code').'</span>',
                        'icon'      => 'user-secret',
                        'reference' => 'agent/'.$state['parent_key']
                    );

                } else {
                    $branch[] = array(
                        'label'     => _('Suppliers'),
                        'icon'      => '',
                        'reference' => 'suppliers'
                    );

                }

                $branch[] = array(
                    'label'     => _('New supplier'),
                    'icon'      => 'hand-holding-box',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'supplier.attachment.new') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="id Supplier_Code">'.$state['_parent']->get('Code').'</span>',
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['parent_key']
                );
                $branch[] = array(
                    'label'     => _('Upload attachment'),
                    'icon'      => 'paperclip',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'supplier.attachment') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="id Supplier_Code">'.$state['_parent']->get('Code').'</span>',
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['parent_key']
                );
                $branch[] = array(
                    'label'     => '<span class="id Attachment_Caption">'.$state['_object']->get('Caption').'</span>',
                    'icon'      => 'paperclip',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'order' or $state['section'] == 'deleted_order') {

                if ($state['parent'] == 'account') {
                    $branch[] = array(
                        'label'     => _('Purchase orders'),
                        'icon'      => '',
                        'reference' => 'suppliers/orders'
                    );
                } elseif ($state['parent'] == 'supplier') {
                    $branch[] = array(
                        'label'     => _('Suppliers'),
                        'icon'      => '',
                        'reference' => 'suppliers'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Supplier_Code">'.$state['_parent']->get('Code'),
                        'icon'      => 'hand-holding-box',
                        'reference' => 'supplier/'.$state['parent_key']
                    );
                } elseif ($state['parent'] == 'agent') {
                    $branch[] = array(
                        'label'     => _('Agents'),
                        'icon'      => '',
                        'reference' => 'agents'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Agent_Code">'.$state['_parent']->get('Code'),
                        'icon'      => 'user-secret',
                        'reference' => 'agent/'.$state['parent_key']
                    );
                } elseif ($state['parent'] == 'supplier_part') {

                    $supplier = new Supplier(
                        $state['_parent']->get('Supplier Part Supplier Key')
                    );

                    $branch[] = array(
                        'label'     => _('Suppliers'),
                        'icon'      => '',
                        'reference' => 'suppliers'
                    );
                    $branch[] = array(
                        'label'     => '<span class="Supplier_Code">'.$supplier->get('Code').'</span>',
                        'icon'      => 'hand-holding-box',
                        'reference' => 'supplier/'.$supplier->id
                    );
                    $branch[] = array(
                        'label'     => '<span class="Supplier_Part_Reference">'.$state['_parent']->get('Reference').'</span>',
                        'icon'      => 'stop',
                        'reference' => 'supplier/'.$supplier->id.'/part/'.$state['_parent']->id
                    );

                }
                $branch[] = array(
                    'label'     => '<span class="Purchase_Order_Public_ID">'.$state['_object']->get('Public ID').'</span>',
                    'icon'      => 'clipboard',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'delivery') {

                if ($state['parent'] == 'account') {
                    $branch[] = array(
                        'label'     => _('Deliveries'),
                        'icon'      => '',
                        'reference' => 'suppliers/deliveries'
                    );
                } elseif ($state['parent'] == 'supplier') {
                    $branch[] = array(
                        'label'     => _('Suppliers'),
                        'icon'      => '',
                        'reference' => 'suppliers'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Supplier_Code">'.$state['_parent']->get('Code'),
                        'icon'      => 'hand-holding-box',
                        'reference' => 'supplier/'.$state['parent_key']
                    );
                } elseif ($state['parent'] == 'agent') {
                    $branch[] = array(
                        'label'     => _('Agents'),
                        'icon'      => '',
                        'reference' => 'agents'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Agent_Code">'.$state['_parent']->get('Code'),
                        'icon'      => 'user-secret',
                        'reference' => 'agent/'.$state['parent_key']
                    );
                }
                $branch[] = array(
                    'label'     => '<span class="Supplier_Delivery_Public_ID">'.$state['_object']->get('Public ID').'</span>',
                    'icon'      => 'truck',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'supplier.order.item') {

                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="id Supplier_Code">'.$state['_parent']->get('Parent Code'),
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['_parent']->get(
                            'Purchase Order Parent Key'
                        )
                );
                $branch[] = array(
                    'label'     => '<span class="Purchase_Order_Public_ID">'.$state['_parent']->get('Public ID').'</span>',
                    'icon'      => 'clipboard',
                    'reference' => 'supplier/'.$state['_parent']->get(
                            'Purchase Order Parent Key'
                        ).'/order/'.$state['parent_key']
                );


                $branch[] = array(
                    'label'     => '<span class="Supplier_Part_Reference">'.$state['_object']->get('Reference').'</span>',
                    'icon'      => 'bars',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'agent.order.item') {


                $branch[] = array(
                    'label'     => _('Agents'),
                    'icon'      => '',
                    'reference' => 'agents'
                );
                $branch[] = array(
                    'label'     => '<span class="id Agent_Code">'.$state['_parent']->get('Parent Code'),
                    'icon'      => 'user-secret',
                    'reference' => 'agent/'.$state['_parent']->get(
                            'Purchase Order Parent Key'
                        )
                );
                $branch[] = array(
                    'label'     => '<span class="Purchase_Order_Public_ID">'.$state['_parent']->get('Public ID').'</span>',
                    'icon'      => 'clipboard',
                    'reference' => 'supplier/'.$state['_parent']->get(
                            'Purchase Order Parent Key'
                        ).'/order/'.$state['parent_key']
                );


                $branch[] = array(
                    'label'     => '<span class="Supplier_Part_Reference">'.$state['_object']->get('Reference').'</span>',
                    'icon'      => 'bars',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'agent') {
                $branch[] = array(
                    'label'     => _('Agents'),
                    'icon'      => '',
                    'reference' => 'agents'
                );
                $branch[] = array(
                    'label'     => '<span class="Agent_Code">'.$state['_object']->get('Code').'</span> <span class="Agent_Name italic">'.$state['_object']->get('Name').'</span>',
                    'icon'      => 'user-secret',
                    'reference' => 'agent/'.$state['key']
                );

            } elseif ($state['section'] == 'agent.new') {
                $branch[] = array(
                    'label'     => _('Agents'),
                    'icon'      => '',
                    'reference' => 'agents'
                );
                $branch[] = array(
                    'label'     => _('New agent'),
                    'icon'      => 'user-secret',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'supplier_part') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="Supplier_Code">'.$state['_parent']->get('Code').'</span>',
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['_parent']->id
                );
                $branch[] = array(
                    'label'     => '<span class="Supplier_Part_Reference">'.$state['_object']->get('Reference').'</span>',
                    'icon'      => 'stop',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'supplier_part.new') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="Supplier_Code">'.$state['_parent']->get('Code').'</span>',
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['_parent']->id
                );
                $branch[] = array(
                    'label'     => _("New supplier's product"),
                    'icon'      => 'stop',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'categories') {
                $branch[] = array(
                    'label'     => _("Supplier's categories"),
                    'icon'      => 'sitemap',
                    'reference' => 'suppliers/categories/'
                );

            } elseif ($state['section'] == 'category') {


                $category = $state['_object'];
                $branch[] = array(
                    'label'     => _("Supplier's categories"),
                    'icon'      => 'sitemap',
                    'reference' => 'suppliers/categories/'
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
                    if ($category_key == $state['key']) {
                        $branch[] = array(
                            'label'     => '<span class="Category_Label">'.$category->get('Label').'</span>',
                            'icon'      => '',
                            'reference' => ''
                        );
                        break;
                    } else {

                        $parent_category = new Category($category_key);
                        if ($parent_category->id) {

                            $branch[] = array(
                                'label'     => $parent_category->get(
                                    'Label'
                                ),
                                'icon'      => '',
                                'reference' => 'suppliers/category/'.$parent_category->id
                            );

                        }
                    }
                }

                break;


            } elseif ($state['section'] == 'main_category.new') {
                $branch[] = array(
                    'label'     => _("Supplier's categories"),
                    'icon'      => 'sitemap',
                    'reference' => 'suppliers/categories/'
                );
                $branch[] = array(
                    'label'     => _("New main category"),
                    'icon'      => '',
                    'reference' => '/'
                );

            } elseif ($state['section'] == 'supplier.user.new') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="id Supplier_Code">'.$state['_parent']->get('Code').'</span> <span class="Supplier_Name italic">'.$state['_parent']->get('Name').'</span>',
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['parent_key']
                );
                $branch[] = array(
                    'label'     => _('New system user'),
                    'icon'      => 'terminal',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'agent.user.new') {
                $branch[] = array(
                    'label'     => _('Agents'),
                    'icon'      => '',
                    'reference' => 'agents'
                );
                $branch[] = array(
                    'label'     => '<span class="Agent_Code">'.$state['_parent']->get('Code').'</span> <span class="Agent_Name italic">'.$state['_parent']->get('Name').'</span>',
                    'icon'      => 'user-secret',
                    'reference' => 'agent/'.$state['parent_key']
                );
                $branch[] = array(
                    'label'     => _('New system user'),
                    'icon'      => 'terminal',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'timeseries_record') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="id Supplier_Code">'.$state['_parent']->get('Parent')->get('Code').'</span>',
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['_parent']->get('Timeseries Parent Key')
                );
                $branch[] = array(
                    'label'     => '<span class="id">'.$state['_object']->get('Code').'</span>',
                    'icon'      => 'table',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'supplier_delivery.attachment.new') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );


                $supplier = get_object('Supplier', $state['_parent']->get('Parent Key'));

                //  print_r($state['_parent']);

                $branch[] = array(
                    'label'     => '<span class="id Supplier_Code">'.$supplier->get('Code').'</span>',
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$supplier->id
                );

                $branch[] = array(
                    'label'     => '<span class="Supplier_Delivery_Public_ID">'.$state['_parent']->get('Public ID').'</span>',
                    'icon'      => 'truck',
                    'reference' => ''
                );


                $branch[] = array(
                    'label'     => _('Upload attachment'),
                    'icon'      => 'paperclip',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'supplier_delivery.attachment') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="id Supplier_Code">'.$state['_parent']->get('Code').'</span>',
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['parent_key']
                );
                $branch[] = array(
                    'label'     => '<span class="id Attachment_Caption">'.$state['_object']->get('Caption').'</span>',
                    'icon'      => 'paperclip',
                    'reference' => ''
                );

            }


            break;
        case 'orders_server':

            $state['current_store'] = 0;
            $branch[]               = array(
                'label'     => '',
                'icon'      => 'indent',
                'reference' => 'receipts'
            );


            if ($state['section'] == 'dashboard') {

                $branch[] = array(
                    'label'     => _("Orders control panel").' ('._('All stores').')',
                    'icon'      => '',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'orders') {

                $branch[] = array(
                    'label'     => _('Orders').' ('._('All stores').')',
                    'icon'      => '',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'mailshot') {


                $branch[] = array(
                    'label'     => _("Orders control panel").' ('._('All stores').')',
                    'icon'      => '',
                    'reference' => 'orders/all/dashboard/website/mailshots'
                );


                $branch[] = array(
                    'label'     => '<span class="Email_Campaign_Name">'.$state['_object']->get('Name').'</span>',
                    'icon'      => 'at',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'invoices') {

                $branch[] = array(
                    'label'     => _('Invoices').' ('._('All stores').')',
                    'icon'      => '',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'group_by_store') {

                $branch[] = array(
                    'label'     => _('Orders grouped by store'),
                    'icon'      => 'compress',
                    'reference' => ''
                );


            }


            break;

        case 'delivery_notes_server':
            $state['current_store'] = 0;
            $branch[]               = array(
                'label'     => '',
                'icon'      => 'indent',
                'reference' => 'receipts'
            );

            if ($state['section'] == 'delivery_notes') {


                if ($user->get_number_stores() > 1) {
                    $branch[] = array(
                        'label'     => _('Delivery Notes').' ('._('All stores').')',
                        'icon'      => '',
                        'reference' => 'delivery_notes/all'
                    );
                }


            } elseif ($state['section'] == 'group_by_store') {

                $branch[] = array(
                    'label'     => _('Delivery notes grouped by store'),
                    'icon'      => 'compress',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'pending_delivery_notes') {

                $branch[] = array(
                    'label'     => _('Pending delivery notes'),
                    'icon'      => 'stream',
                    'reference' => ''
                );


            }


            break;

        case 'orders':
            $state['current_store'] = $state['store']->id;
            $branch[]               = array(
                'label'     => '',
                'icon'      => 'indent',
                'reference' => 'receipts'
            );
            switch ($state['section']) {

                case 'dashboard':

                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => 'stream',
                            'reference' => 'orders/all/dashboard'
                        );
                    }

                    $branch[] = array(
                        'label'     => $state['store']->data['Store Code'],
                        'icon'      => 'stream',
                        'reference' => ''
                    );

                    break;


                case 'orders':

                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'orders/all'
                        );
                    }


                    $branch[] = array(
                        'label'     => _('Orders').' '.$state['store']->data['Store Code'],
                        'icon'      => 'shopping-cart',
                        'reference' => ''
                    );


                    break;

                case 'payments':
                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label' => _('Payments').' ('._('All stores').')',
                            'icon'  => 'indent',
                            'url'   => 'payments/all'
                        );
                    }
                    break;

                case 'payment':

                    if ($state['parent'] == 'order') {


                        $branch[] = array(
                            'label'     => _('Orders').' '.$state['store']->data['Store Code'],
                            'icon'      => '',
                            'reference' => 'orders/'.$state['store']->id
                        );

                        $branch[] = array(
                            'label'     => $state['_parent']->get('Order Public ID'),
                            'icon'      => 'shopping-cart',
                            'reference' => ''
                        );


                    }

                    $branch[] = array(
                        'label'     => $state['_object']->get('Payment Transaction ID'),
                        'icon'      => 'dollar-sign',
                        'reference' => ''
                    );

                    break;

                case 'order':

                    if ($state['parent'] == 'customer') {

                        $customer = new Customer($state['parent_key']);
                        if ($customer->id) {
                            if ($user->get_number_stores() > 1) {


                                $branch[] = array(
                                    'label'     => _(
                                        'Customers (All stores)'
                                    ),
                                    'icon'      => 'indent',
                                    'reference' => 'customers/all'
                                );

                            }

                            $store = new Store(
                                $customer->data['Customer Store Key']
                            );


                            $branch[] = array(
                                'label'     => _(
                                        'Customers'
                                    ).' '.$store->data['Store Code'],
                                'icon'      => 'users',
                                'reference' => 'customers/'.$store->id
                            );
                            $branch[] = array(
                                'label'     => _(
                                        'Customer'
                                    ).' '.$customer->get_formatted_id(),
                                'icon'      => 'user',
                                'reference' => 'customer/'.$customer->id
                            );
                        }


                    }
                    else {


                        if ($user->get_number_stores() > 1) {
                            $branch[] = array(
                                'label'     => '('._('All stores').')',
                                'icon'      => 'stream',
                                'reference' => 'orders/all/dashboard'
                            );
                        }


                        if (!empty($state['extra'])) {

                            switch ($state['extra']) {
                                case 'submitted_not_paid':
                                    $label = _('Submitted (not paid)');
                                    break;
                                case 'submitted':
                                    $label = _('Submitted (paid)');
                                    break;
                                case 'website':
                                    $label = _('In basket');
                                    break;
                                case 'in_warehouse':
                                    $label = _('In warehouse');

                                    break;
                                case 'in_warehouse_with_alerts':
                                    $label = _('In warehouse').' ('._('with alerts').')';

                                    break;

                                case 'packed_done':
                                    $label = _('Packed & closed');

                                    break;
                                case 'approved':
                                    $label = _('Invoiced');

                                    break;
                                case 'dispatched_today':
                                    $label = _('Dispatched today');

                                    break;
                                default:
                                    $label = '';
                            }

                            $branch[] = array(
                                'label'     => $label.' <span class="id">'.$state['store']->data['Store Code'].'</span>',
                                'icon'      => 'stream',
                                'reference' => 'orders/'.$state['store']->id.'/dashboard/'.$state['extra']
                            );

                        } else {
                            $branch[] = array(
                                'label'     => _('Orders').' '.$state['store']->data['Store Code'],
                                'icon'      => '',
                                'reference' => 'orders/'.$state['store']->id
                            );
                        }


                    }
                    $branch[] = array(
                        'label'     => $state['_object']->get('Order Public ID'),
                        'icon'      => 'shopping-cart',
                        'reference' => ''
                    );

                    break;

                case 'return.new':


                    $branch[] = array(
                        'label'     => _('Orders').' '.$state['store']->data['Store Code'],
                        'icon'      => '',
                        'reference' => 'orders/'.$state['store']->id
                    );


                    $branch[] = array(
                        'label'     => $state['_object']->get('Order Public ID'),
                        'icon'      => 'shopping-cart',
                        'reference' => sprintf('orders/%d/%d', $state['_object']->get('Order Store Key'), $state['_object']->id)
                    );


                    $branch[] = array(
                        'label'     => _('Creating return'),
                        'icon'      => 'backspace',
                        'reference' => ''
                    );
                    break;


                case 'refund.new':

                    if ($state['parent'] == 'customer') {

                        $customer = new Customer($state['parent_key']);
                        if ($customer->id) {
                            if ($user->get_number_stores() > 1) {


                                $branch[] = array(
                                    'label'     => _('Customers (All stores)'),
                                    'icon'      => 'indent',
                                    'reference' => 'customers/all'
                                );

                            }

                            $store = new Store(
                                $customer->data['Customer Store Key']
                            );


                            $branch[] = array(
                                'label'     => _(
                                        'Customers'
                                    ).' '.$store->data['Store Code'],
                                'icon'      => 'users',
                                'reference' => 'customers/'.$store->id
                            );
                            $branch[] = array(
                                'label'     => _(
                                        'Customer'
                                    ).' '.$customer->get_formatted_id(),
                                'icon'      => 'user',
                                'reference' => 'customer/'.$customer->id
                            );
                        }


                    } else {


                        $branch[] = array(
                            'label'     => _('Orders').' '.$state['store']->data['Store Code'],
                            'icon'      => '',
                            'reference' => 'orders/'.$state['store']->id
                        );


                    }
                    $branch[] = array(
                        'label'     => $state['_object']->get('Order Public ID'),
                        'icon'      => 'shopping-cart',
                        'reference' => ''
                    );

                    break;

                case 'delivery_note':

                    $store = new Store(
                        $state['_object']->data['Delivery Note Store Key']
                    );

                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'orders/all'
                        );
                    }
                    $branch[] = array(
                        'label'     => _('Orders').' '.$store->data['Store Code'],
                        'icon'      => '',
                        'reference' => 'orders/'.$store->id
                    );

                    $parent   = new Order($state['parent_key']);
                    $branch[] = array(
                        'label'     => $parent->get(
                            'Order Public ID'
                        ),
                        'icon'      => 'shopping-cart',
                        'reference' => 'orders/'.$store->id.'/'.$state['parent_key']
                    );


                    $branch[] = array(
                        'label'     => $state['_object']->get(
                            'Delivery Note ID'
                        ),
                        'icon'      => 'truck fa-flip-horizontal',
                        'reference' => ''
                    );
                    break;

                case 'invoices':
                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'invoices/all'
                        );
                    }
                    $store = new Store($state['parent_key']);

                    $branch[] = array(
                        'label'     => _('Invoices').' '.$store->data['Store Code'],
                        'icon'      => '',
                        'reference' => ''
                    );


                    break;

                case 'invoice':
                case 'refund':
                case 'deleted_invoice':
                case 'deleted_refund':
                    $store = new Store($state['_object']->data['Invoice Store Key']);

                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'orders/all'
                        );
                    }
                    $branch[] = array(
                        'label'     => _('Orders').' '.$store->data['Store Code'],
                        'icon'      => '',
                        'reference' => 'orders/'.$store->id
                    );

                    $parent   = new Order($state['parent_key']);
                    $branch[] = array(
                        'label'     => $parent->get('Order Public ID'),
                        'icon'      => 'shopping-cart',
                        'reference' => 'orders/'.$store->id.'/'.$state['parent_key']
                    );


                    if ($state['_object']->deleted) {
                        $branch[] = array(
                            'label'     => '<span class="strikethrough">'.$state['_object']->get('Invoice Public ID').'</span> ('._('Deleted').')',
                            'icon'      => 'file-invoice-dollar',
                            'reference' => ''
                        );
                    } else {
                        $branch[] = array(
                            'label'     => $state['_object']->get('Invoice Public ID'),
                            'icon'      => 'file-invoice-dollar',
                            'reference' => ''
                        );
                    }


                    break;
                case 'mailshot':


                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'orders/all'
                        );
                    }


                    $branch[] = array(
                        'label'     => _('Orders control panel').' '.$state['store']->get('Code'),
                        'icon'      => '',
                        'reference' => 'orders/'.$state['store']->id.'/dashboard/website/mailshots'
                    );


                    $branch[] = array(
                        'label'     => '<span class="Email_Campaign_Name">'.$state['_object']->get('Name').'</span>',
                        'icon'      => 'at',
                        'reference' => ''
                    );
                    break;

                case 'email_tracking':
                    //$store = get_object('Store', $state['_parent']->get('Store Key'));

                    $branch[] = array(
                        'label'     => _('Orders').' '.$state['store']->data['Store Code'],
                        'icon'      => '',
                        'reference' => 'orders/'.$state['store']->id
                    );


                    $branch[] = array(
                        'label'     => $state['_parent']->get('Order Public ID'),
                        'icon'      => 'shopping-cart',
                        'reference' => 'orders/'.$state['store']->id.'/'.$state['_parent']->id
                    );


                    $branch[] = array(
                        'label'     => _('Sent email').' <span class="id">'.$state['_object']->get('Sent Date').'</span>',
                        'icon'      => '',
                        'reference' => ''
                    );


                    break;
                case 'purge':
                    //$store = get_object('Store', $state['_parent']->get('Store Key'));

                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'orders/all/dashboard'
                        );
                    }

                    $branch[] = array(
                        'label'     => _('Orders control panel').' '.$state['store']->data['Store Code'],
                        'icon'      => '',
                        'reference' => ''
                    );


                    $branch[] = array(
                        'label'     => _('Purge').' <span class="id">'.$state['_object']->get('Date').'</span>',
                        'icon'      => '',
                        'reference' => ''
                    );


                    break;

                case 'order.attachment':
                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'orders/all'
                        );
                    }
                    $branch[] = array(
                        'label'     => _('Orders').' '.$state['store']->data['Store Code'],
                        'icon'      => '',
                        'reference' => 'orders/'.$state['store']->id
                    );


                    $branch[] = array(
                        'label'     => $state['_parent']->get('Order Public ID'),
                        'icon'      => 'shopping-cart',
                        'reference' => sprintf('orders/%d/%d', $state['_parent']->get('Order Store Key'), $state['_parent']->id)
                    );

                    $branch[] = array(
                        'label'     => $state['_object']->get('Attachment File Original Name'),
                        'icon'      => 'paperclip',
                        'reference' =>''
                    );


                    break;
                case 'order.attachment.new':
                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'orders/all'
                        );
                    }
                    $branch[] = array(
                        'label'     => _('Orders').' '.$state['store']->data['Store Code'],
                        'icon'      => '',
                        'reference' => 'orders/'.$state['store']->id
                    );


                    $branch[] = array(
                        'label'     => $state['_parent']->get('Order Public ID'),
                        'icon'      => 'shopping-cart',
                        'reference' => sprintf('orders/%d/%d', $state['_parent']->get('Order Store Key'), $state['_parent']->id)
                    );

                    $branch[] = array(
                        'label'     => _('Upload attachment'),
                        'icon'      => 'paperclip',
                        'reference' =>''
                    );

            }

            break;
        case 'delivery_notes':
            $state['current_store'] = $state['store']->id;
            $branch[]               = array(
                'label'     => '',
                'icon'      => 'indent',
                'reference' => 'receipts'
            );

            switch ($state['section']) {
                case 'delivery_notes':

                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'delivery_notes/all'
                        );
                    }
                    $store = new Store($state['parent_key']);

                    $branch[] = array(
                        'label'     => _(
                                'Delivery Notes'
                            ).' '.$store->data['Store Code'],
                        'icon'      => '',
                        'reference' => 'delivery_notes/'.$store->id
                    );


                    break;


                case 'delivery_note':

                    if ($state['parent'] == 'customer') {

                        $customer = new Customer($state['parent_key']);
                        if ($customer->id) {
                            if ($user->get_number_stores() > 1) {


                                $branch[] = array(
                                    'label'     => _(
                                        'Customers (All stores)'
                                    ),
                                    'icon'      => '',
                                    'reference' => 'customers/all'
                                );

                            }

                            $store = new Store(
                                $customer->data['Customer Store Key']
                            );


                            $branch[] = array(
                                'label'     => _('Customers').' '.$store->data['Store Code'],
                                'icon'      => 'users',
                                'reference' => 'customers/'.$store->id
                            );
                            $branch[] = array(
                                'label'     => _('Customer').' '.$customer->get_formatted_id(),
                                'icon'      => 'user',
                                'reference' => 'customer/'.$customer->id
                            );
                        }


                    } else {
                        $store = new Store(
                            $state['_object']->data['Delivery Note Store Key']
                        );

                        if ($user->get_number_stores() > 1) {
                            $branch[] = array(
                                'label'     => '('._('All stores').')',
                                'icon'      => '',
                                'reference' => 'delivery_notes/all'
                            );
                        }
                        $branch[] = array(
                            'label'     => _(
                                    'Delivery notes'
                                ).' '.$store->data['Store Code'],
                            'icon'      => '',
                            'reference' => 'delivery_notes/'.$store->id
                        );


                    }
                    $branch[] = array(
                        'label'     => $state['_object']->get(
                            'Delivery Note ID'
                        ),
                        'icon'      => 'truck fa-flip-horizontal',
                        'reference' => ''
                    );

                    break;

                case 'order':

                    $store = new Store(
                        $state['_object']->data['Order Store Key']
                    );

                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'delivery_notes/all'
                        );
                    }
                    $branch[] = array(
                        'label'     => _(
                                'Delivery notes'
                            ).' '.$store->data['Store Code'],
                        'icon'      => '',
                        'reference' => 'delivery_notes/'.$store->id
                    );

                    $parent   = new DeliveryNote($state['parent_key']);
                    $branch[] = array(
                        'label'     => $parent->get(
                            'Delivery Note ID'
                        ),
                        'icon'      => 'truck fa-flip-horizontal',
                        'reference' => 'delivery_notes/'.$store->id.'/'.$state['parent_key']
                    );


                    $branch[] = array(
                        'label'     => $state['_object']->get(
                            'Order Public ID'
                        ),
                        'icon'      => 'shopping-cart',
                        'reference' => ''
                    );
                    $branch[] = array(
                        'label'     => $state['_object']->get(
                            'Delivery Note ID'
                        ),
                        'icon'      => 'truck fa-flip-horizontal',
                        'reference' => ''
                    );

                    break;

                case 'invoice':

                    $store = new Store(
                        $state['_object']->data['Invoice Store Key']
                    );

                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'delivery_notes/all'
                        );
                    }
                    $branch[] = array(
                        'label'     => _(
                                'Delivery notes'
                            ).' '.$store->data['Store Code'],
                        'icon'      => '',
                        'reference' => 'delivery_notes/'.$store->id
                    );

                    $parent   = new DeliveryNote($state['parent_key']);
                    $branch[] = array(
                        'label'     => $parent->get(
                            'Delivery Note ID'
                        ),
                        'icon'      => 'truck fa-flip-horizontal',
                        'reference' => 'delivery_notes/'.$store->id.'/'.$state['parent_key']
                    );


                    $branch[] = array(
                        'label'     => $state['_object']->get('Invoice Public ID'),
                        'icon'      => 'file-invoice-dollar',
                        'reference' => ''
                    );
                    $branch[] = array(
                        'label'     => $state['_object']->get('Delivery Note ID'),
                        'icon'      => 'truck fa-flip-horizontal',
                        'reference' => ''
                    );

                    break;


            }

            break;


        case 'help':
            switch ($state['section']) {
                case 'help':
                    $branch[] = array(
                        'label'     => _('Help'),
                        'icon'      => '',
                        'reference' => 'help'
                    );
                    break;


            }
            break;
        case 'hr':
            switch ($state['section']) {
                case 'employees':
                    $branch[] = array(
                        'label'     => _('Employees'),
                        'icon'      => '',
                        'reference' => 'hr'
                    );
                    break;
                case 'contractors':
                    $branch[] = array(
                        'label'     => _('Contractors'),
                        'icon'      => 'hand-spock',
                        'reference' => 'hr/contractors'
                    );
                    break;
                case 'hr.history':
                    $branch[] = array(
                        'label'     => _('Staff history'),
                        'icon'      => '',
                        'reference' => 'hr/history'
                    );
                    break;

                case 'employee':
                    $branch[] = array(
                        'label'     => _('Employees'),
                        'icon'      => '',
                        'reference' => 'hr'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Staff_Alias">'.$state['_object']->get('Staff Alias').'</span>',
                        'icon'      => 'hand-rock',
                        'reference' => 'employee/'.$state['_object']->id
                    );
                    break;
                case 'deleted.employee':
                    $branch[] = array(
                        'label'     => _('Deleted employees'),
                        'icon'      => '',
                        'reference' => 'hr/deleted_employees'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Staff_Alias">'.$state['_object']->get('Staff Alias').'</span> <i class="far fa-trash-alt padding_left_5" aria-hidden="true"></i> ',
                        'icon'      => 'hand-rock',
                        'reference' => 'employee/'.$state['_object']->id
                    );
                    break;

                case 'employee.attachment.new':
                    $branch[] = array(
                        'label'     => _('Employees'),
                        'icon'      => '',
                        'reference' => 'hr'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Staff_Alias">'.$state['_parent']->get('Staff Alias').'</span>',
                        'icon'      => 'hand-rock',
                        'reference' => 'employee/'.$state['_parent']->id
                    );
                    $branch[] = array(
                        'label' => _('Upload attachment'),
                        'icon'  => 'paperclip'
                    );

                    break;


                case 'employee.new':
                    $branch[] = array(
                        'label'     => _('Employees'),
                        'icon'      => '',
                        'reference' => 'hr'
                    );
                    $branch[] = array(
                        'label' => _('New employee'),
                        'icon'  => ''
                    );
                    break;
                case 'contractor':
                    $branch[] = array(
                        'label'     => _('Contractors'),
                        'icon'      => '',
                        'reference' => 'hr/contractors'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Staff_Alias">'.$state['_object']->get('Staff Alias').'</span>',
                        'icon'      => 'hand-spock',
                        'reference' => 'employee/'.$state['_object']->id
                    );
                    break;
                case 'contractor.user.new':
                    $branch[] = array(
                        'label'     => _('Contractors'),
                        'icon'      => '',
                        'reference' => 'hr/contractors'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Staff_Alias">'.$state['_parent']->get('Staff Alias').'</span>',
                        'icon'      => 'hand-spock',
                        'reference' => 'contractor/'.$state['_parent']->id
                    );
                    $branch[] = array(
                        'label' => _('New system user'),
                        'icon'  => ''
                    );

                    break;
                case 'employee.user.new':
                    $branch[] = array(
                        'label'     => _('Employees'),
                        'icon'      => '',
                        'reference' => 'hr'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Staff_Alias">'.$state['_parent']->get('Staff Alias').'</span>',
                        'icon'      => 'hand-rock',
                        'reference' => 'employee/'.$state['_parent']->id
                    );
                    $branch[] = array(
                        'label' => _('New system user'),
                        'icon'  => ''
                    );

                    break;
                case 'deleted.contractor':
                    $branch[] = array(
                        'label'     => _('Deleted contractors'),
                        'icon'      => '',
                        'reference' => 'hr/deleted_contractors'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Staff_Alias">'.$state['_object']->get('Staff Alias').'</span> <i class="far fa-trash-alt padding_left_5" aria-hidden="true"></i> ',
                        'icon'      => 'hand-spock',
                        'reference' => 'employee/'.$state['_object']->id
                    );
                    break;
                case 'contractor.new':
                    $branch[] = array(
                        'label'     => _('Contractors'),
                        'icon'      => '',
                        'reference' => 'hr/contractors'
                    );
                    $branch[] = array(
                        'label' => _('New contractor'),
                        'icon'  => 'hand-spock'
                    );
                    break;

                case 'employee.attachment':
                    include_once 'class.Staff.php';
                    $employee = new Staff($state['parent_key']);
                    $branch[] = array(
                        'label'     => _('Employees'),
                        'icon'      => '',
                        'reference' => 'hr'
                    );

                    $branch[] = array(
                        'label'     => '<span class="id Staff_Alias">'.$employee->get('Staff Alias').'</span>',
                        'icon'      => 'hand-rock',
                        'reference' => 'employee/'.$employee->id
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Attachment_Caption">'.$state['_object']->get('Caption').'</span>',
                        'icon'      => 'paperclip',
                        'reference' => 'employee/'.$employee->id.'/attachment/'.$state['_object']->id
                    );
                    break;

                case 'organization':
                    $branch[] = array(
                        'label'     => _('Organization'),
                        'icon'      => '',
                        'reference' => 'hr/organization'
                    );
                    break;
                case 'position':
                    $branch[] = array(
                        'label'     => _('Job positions'),
                        'icon'      => '',
                        'reference' => 'hr/organization'
                    );
                    $branch[] = array(
                        'label'     => $state['_object']->get('title'),
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;

                case 'timesheets':
                    $branch[] = array(
                        'label'     => _("Employees' calendar"),
                        'icon'      => '',
                        'reference' => 'timesheets/day/'.date(
                                'Ymd'
                            )
                    );
                    if ($state['parent'] == 'year') {
                        $branch[] = array(
                            'label'     => $state['parent_key'],
                            'icon'      => '',
                            'reference' => 'timesheets/year/'.$state['parent_key']
                        );

                    } elseif ($state['parent'] == 'month') {
                        $year     = substr($state['parent_key'], 0, 4);
                        $month    = substr($state['parent_key'], 4, 2);
                        $branch[] = array(
                            'label'     => $year,
                            'icon'      => '',
                            'reference' => 'timesheets/year/'.$year
                        );

                        $date     = strtotime("$year-$month-01");
                        $branch[] = array(
                            'label'     => strftime('%B', $date),
                            'icon'      => '',
                            'reference' => 'timesheets/month/'.$state['parent_key']
                        );

                    } elseif ($state['parent'] == 'week') {
                        $year     = substr($state['parent_key'], 0, 4);
                        $week     = substr($state['parent_key'], 4, 2);
                        $branch[] = array(
                            'label'     => $year,
                            'icon'      => '',
                            'reference' => 'timesheets/year/'.$year
                        );

                        $date     = strtotime("$year".'W'.$week);
                        $branch[] = array(
                            'label'     => sprintf(
                                _('%s week (starting %s %s)'), get_ordinal_suffix($week), strftime('%a', $date), get_ordinal_suffix(strftime('%d', $date))
                            ),
                            'icon'      => '',
                            'reference' => 'timesheets/week/'.$year.$week
                        );

                    } elseif ($state['parent'] == 'day') {

                        $year  = substr($state['parent_key'], 0, 4);
                        $month = substr($state['parent_key'], 4, 2);
                        $day   = substr($state['parent_key'], 6, 2);

                        $date = strtotime("$year-$month-$day");

                        $branch[] = array(
                            'label'     => $year,
                            'icon'      => '',
                            'reference' => 'timesheets/year/'.$year
                        );

                        $branch[] = array(
                            'label'     => strftime('%B', $date),
                            'icon'      => '',
                            'reference' => 'timesheets/month/'.$year.$month
                        );
                        $branch[] = array(
                            'label'     => strftime('%a', $date).' '.get_ordinal_suffix(strftime('%d', $date)),
                            'icon'      => '',
                            'reference' => 'timesheets/month/'.$year.$month.$day
                        );

                    }
                    break;
                case 'clocking_machines':
                    $branch[] = array(
                        'label'     => _('Clocking-in machines'),
                        'icon'      => 'chess-clock',
                        'reference' => 'clocking_machines'
                    );
                    break;
                case 'clocking_machine.new':
                    $branch[] = array(
                        'label'     => _('Clocking-in machines'),
                        'icon'      => 'chess-clock',
                        'reference' => 'clocking_machines'
                    );

                    $branch[] = array(
                        'label' => _('Setting up new clocking-in machine'),
                        'icon'  => 'pager',
                    );
                    break;
            }
            break;


        case 'inventory':


            switch ($state['section']) {
                case 'inventory':
                    $branch[] = array(
                        'label'     => _('Inventory'),
                        'icon'      => 'th-large',
                        'reference' => ''
                    );
                    break;
                case 'part':


                    if ($state['parent'] == 'category') {
                        $category = $state['_parent'];
                        $branch[] = array(
                            'label'     => _(
                                "Part's families"
                            ),
                            'icon'      => 'sitemap',
                            'reference' => 'inventory/categories'
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
                            if ($category_key == $state['parent_key']) {
                                $branch[] = array(
                                    'label'     => '<span class="Category_Label">'.$category->get('Label').'</span>',
                                    'icon'      => '',
                                    'reference' => 'inventory/category/'.$category_key
                                );
                                break;
                            } else {

                                $parent_category = new Category($category_key);
                                if ($parent_category->id) {

                                    $branch[] = array(
                                        'label'     => $parent_category->get(
                                            'Label'
                                        ),
                                        'icon'      => '',
                                        'reference' => 'inventory/category/'.$parent_category->id
                                    );

                                }
                            }
                        }

                    } else {
                        $branch[] = array(
                            'label'     => _('Inventory'),
                            'icon'      => 'th-large',
                            'reference' => 'inventory'
                        );

                    }

                    $branch[] = array(
                        'label'     => '<span class="id Part_Reference">'.$state['_object']->get('Reference').'</span>',
                        'icon'      => 'box',
                        'reference' => ''
                    );

                    break;

                case 'part.image':

                    $branch[] = array(
                        'label'     => _('Inventory'),
                        'icon'      => 'th-large',
                        'reference' => 'inventory'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Part_Reference">'.$state['_parent']->get('Reference').'</span>',
                        'icon'      => 'box',
                        'reference' => 'part/'.$state['_parent']->sku
                    );
                    $branch[] = array(
                        'label'     => _('Image'),
                        'icon'      => 'camera-retro',
                        'reference' => ''
                    );

                    break;

                case 'part.new':

                    $branch[] = array(
                        'label'     => _('Inventory'),
                        'icon'      => 'th-large',
                        'reference' => 'inventory'
                    );
                    $branch[] = array(
                        'label'     => _('New part'),
                        'icon'      => 'box',
                        'reference' => ''
                    );

                    break;


                case 'supplier_part.new':


                    if ($state['parent'] == 'category') {
                        $category = $state['_parent'];
                        $branch[] = array(
                            'label'     => _(
                                "Part's families"
                            ),
                            'icon'      => 'sitemap',
                            'reference' => 'inventory/categories'
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
                            if ($category_key == $state['parent_key']) {
                                $branch[] = array(
                                    'label'     => '<span class="Category_Label">'.$category->get('Label').'</span>',
                                    'icon'      => '',
                                    'reference' => ''
                                );
                                break;
                            } else {

                                $parent_category = new Category($category_key);
                                if ($parent_category->id) {

                                    $branch[] = array(
                                        'label'     => $parent_category->get(
                                            'Label'
                                        ),
                                        'icon'      => '',
                                        'reference' => 'inventory/category/'.$parent_category->id
                                    );

                                }
                            }
                        }

                    } else {
                        $branch[] = array(
                            'label'     => _('Inventory'),
                            'icon'      => 'th-large',
                            'reference' => 'inventory'
                        );

                    }

                    $branch[] = array(
                        'label'     => '<span class="id Part_Reference">'.$state['_object']->get('Reference').'</span>',
                        'icon'      => 'box',
                        'reference' => 'part/'.$state['_object']->id
                    );
                    $branch[] = array(
                        'label'     => _("New supplier's product"),
                        'icon'      => '',
                        'reference' => ''
                    );

                    break;

                case 'product':
                    $branch[] = array(
                        'label'     => _('Inventory'),
                        'icon'      => 'th-large',
                        'reference' => 'inventory'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Part_Reference">'.$state['_parent']->get('Reference').'</span>',
                        'icon'      => 'box',
                        'reference' => 'part/'.$state['parent_key']
                    );


                    $branch[] = array(
                        'label'     => '<span class="id Product_Code">'.$state['_object']->get('Code').'</span>',
                        'icon'      => 'cube',
                        'reference' => 'products/'.$state['_object']->get('Product Store Key').'/'.$state['_object']->id
                    );

                    break;
                case 'feedback':
                    $branch[] = array(
                        'label'     => _('Issues'),
                        'icon'      => 'poop',
                        'reference' => ''
                    );
                    break;
                case 'barcodes':
                    $branch[] = array(
                        'label'     => _('Barcodes'),
                        'icon'      => 'barcode',
                        'reference' => ''
                    );
                    break;
                case 'barcode':
                    $branch[] = array(
                        'label'     => _('Barcodes'),
                        'icon'      => '',
                        'reference' => 'inventory/barcodes'
                    );
                    $branch[] = array(
                        'label'     => $state['_object']->get(
                            'Number'
                        ),
                        'icon'      => 'barcode',
                        'reference' => ''
                    );

                    break;
                case 'deleted_barcode':
                    $branch[] = array(
                        'label'     => _('Barcodes'),
                        'icon'      => '',
                        'reference' => 'inventory/barcodes'
                    );
                    $branch[] = array(
                        'label'     => $state['_object']->get(
                                'Deleted Number'
                            ).' <i class="fa fa-trash" aria-hidden="true"></i>',
                        'icon'      => 'barcode',
                        'reference' => ''
                    );

                    break;
                case 'categories':
                    $branch[] = array(
                        'label'     => _("Part's families"),
                        'icon'      => 'sitemap',
                        'reference' => ''
                    );
                    break;
                case 'category':
                    $category = $state['_object'];
                    $branch[] = array(
                        'label'     => _("Part's families"),
                        'icon'      => 'sitemap',
                        'reference' => 'inventory/categories'
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
                        if ($category_key == $state['key']) {
                            $branch[] = array(
                                'label'     => '<span class="Category_Label">'.$category->get('Label').'</span>',
                                'icon'      => '',
                                'reference' => ''
                            );
                            break;
                        } else {

                            $parent_category = new Category($category_key);
                            if ($parent_category->id) {

                                $branch[] = array(
                                    'label'     => $parent_category->get(
                                        'Label'
                                    ),
                                    'icon'      => '',
                                    'reference' => 'inventory/category/'.$parent_category->id
                                );

                            }
                        }
                    }

                    break;
                case 'main_category.new':

                    $branch[] = array(
                        'label'     => _("Part's families"),
                        'icon'      => 'sitemap',
                        'reference' => 'inventory/categories'
                    );
                    $branch[] = array(
                        'label'     => _('New main category'),
                        'icon'      => '',
                        'reference' => ''
                    );


                    break;
                case 'upload':

                    if ($state['parent'] == 'category') {
                        $category = $state['_parent'];
                        $branch[] = array(
                            'label'     => _(
                                "Part's families"
                            ),
                            'icon'      => 'sitemap',
                            'reference' => 'inventory/categories'
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
                            if ($category_key == $state['key']) {
                                $branch[] = array(
                                    'label'     => '<span class="Category_Label">'.$category->get('Label').'</span>',
                                    'icon'      => '',
                                    'reference' => ''
                                );
                                break;
                            } else {

                                $parent_category = new Category($category_key);
                                if ($parent_category->id) {

                                    $branch[] = array(
                                        'label'     => $parent_category->get(
                                            'Label'
                                        ),
                                        'icon'      => '',
                                        'reference' => 'inventory/category/'.$parent_category->id
                                    );

                                }
                            }
                        }

                    }

                    $branch[] = array(
                        'label'     => _('Upload').' '.sprintf(
                                '%04d', $state['_object']->get('Key')
                            ),
                        'icon'      => 'upload',
                        'reference' => ''
                    );

                    break;
                case 'stock_history':
                    $branch[] = array(
                        'label'     => _('Stock History'),
                        'icon'      => 'area-chart',
                        'reference' => ''
                    );
                    break;
                case 'stock_history.day':
                    $branch[] = array(
                        'label'     => _('Stock History'),
                        'icon'      => 'area-chart',
                        'reference' => 'inventory/stock_history'
                    );
                    $branch[] = array(
                        'label'     => strftime(
                            "%a %e %b %Y", strtotime($state['key'].' +0:00')
                        ),
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;
                case 'part.attachment.new':
                    $branch[] = array(
                        'label'     => _('Inventory'),
                        'icon'      => 'th-large',
                        'reference' => 'inventory'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Part_Reference">'.$state['_parent']->get('Reference').'</span>',
                        'icon'      => 'box',
                        'reference' => 'part/'.$state['_parent']->sku
                    );
                    $branch[] = array(
                        'label'     => _('Upload attachment'),
                        'icon'      => 'paperclip',
                        'reference' => ''
                    );
                    break;
                case 'part.attachment':
                    $branch[] = array(
                        'label'     => _('Inventory'),
                        'icon'      => 'th-large',
                        'reference' => 'inventory'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Part_Reference">'.$state['_parent']->get('Reference').'</span>',
                        'icon'      => 'box',
                        'reference' => 'part/'.$state['_parent']->sku
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Attachment_Caption">'.$state['_object']->get('Caption').'</span>',
                        'icon'      => 'paperclip',
                        'reference' => ''
                    );

                    break;
            }

            break;
        case 'warehouses_server':

            $branch[] = array(
                'label'     => '('._('All warehouses').')',
                'icon'      => 'warehouse-alt',
                'reference' => ''
            );


            break;
        case 'warehouses':


            if ($user->get_number_warehouses() > 1 or $user->can_create('warehouses')) {


                $branch[] = array(
                    'label'     => '('._('All warehouses').')',
                    'icon'      => '',
                    'reference' => 'warehouses'
                );
            }
            switch ($state['section']) {


                case 'dashboard':
                    $branch[] = array(
                        'label'     => _('Warehouse dashboard'),
                        'icon'      => 'tachometer',
                        'reference' => ''
                    );

                    break;


                case 'warehouse':


                    $branch[] = array(
                        'label'     => '<span class="id Warehouse_Code">'._('Warehouse').' '.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'warehouse-alt',
                        'reference' => ''
                    );
                    break;
                case 'feedback':

                    $branch[] = array(
                        'label'     => '<span class="id Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'warehouse-alt',
                        'reference' => 'warehouse/'.$state['parent_key']
                    );
                    $branch[] = array(
                        'label'     => _('Issues'),
                        'icon'      => 'poop',
                        'reference' => ''
                    );
                    break;
                case 'warehouse_areas':


                    $branch[] = array(
                        'label'     => '<span class="id Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'warehouse-alt',
                        'reference' => 'warehouse/'.$state['parent_key']
                    );
                    $branch[] = array(
                        'label'     => _('Warehouse areas'),
                        'icon'      => 'inventory',
                        'reference' => ''
                    );
                    break;
                case 'warehouse_area':


                    $branch[] = array(
                        'label'     => '<span class="id Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'warehouse-alt',
                        'reference' => 'warehouse/'.$state['warehouse']->id
                    );
                    $branch[] = array(
                        'label'     => _('Warehouse areas'),
                        'icon'      => 'inventory',
                        'reference' => 'warehouse/'.$state['warehouse']->id.'/areas'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Warehouse_Area_Code">'.$state['_object']->get('Code').'</span>',
                        'icon'      => 'inventory',
                        'reference' => ''
                    );
                    break;
                case 'locations':


                    $branch[] = array(
                        'label'     => '<span class="id Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'warehouse-alt',
                        'reference' => 'warehouse/'.$state['parent_key']
                    );
                    $branch[] = array(
                        'label'     => _('Locations'),
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;
                case 'delivery_notes':

                    $branch[] = array(
                        'label'     => '<span class="id Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'warehouse-alt',
                        'reference' => 'warehouse/'.$state['parent_key']
                    );
                    $branch[] = array(
                        'label'     => _('Pending delivery notes'),
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;

                case 'location':


                    switch ($state['_parent']->get_object_name()) {
                        case 'Warehouse Area':
                            $branch[] = array(
                                'label'     => '<span class="id Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                                'icon'      => 'warehouse-alt',
                                'reference' => 'warehouse/'.$state['warehouse']->id
                            );
                            $branch[] = array(
                                'label'     => _('Warehouse areas'),
                                'icon'      => 'inventory',
                                'reference' => 'warehouse/'.$state['warehouse']->id.'/areas'
                            );
                            $branch[] = array(
                                'label'     => '<span class="id Warehouse_Area_Code">'.$state['_parent']->get('Code').'</span>',
                                'icon'      => 'inventory',
                                'reference' => 'warehouse/'.$state['warehouse']->id.'/areas/'.$state['_parent']->id
                            );
                            break;
                        case 'Warehouse':

                            $branch[] = array(
                                'label'     => '<span class=" Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                                'icon'      => 'warehouse-alt',
                                'reference' => 'warehouse/'.$state['parent_key']
                            );
                            $branch[] = array(
                                'label'     => _('Locations'),
                                'icon'      => '',
                                'reference' => 'warehouse/'.$state['parent_key'].'/locations'
                            );

                            break;
                    }


                    $branch[] = array(
                        'label'     => '<span class="id Location_Code">'.$state['_object']->get('Code').'</span>',
                        'icon'      => 'inventory',
                        'reference' => ''
                    );

                    break;

                case 'location.new':


                    switch ($state['_parent']->get_object_name()) {
                        case 'Warehouse Area':
                            $branch[] = array(
                                'label'     => '<span class="id Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                                'icon'      => 'warehouse-alt',
                                'reference' => 'warehouse/'.$state['warehouse']->id
                            );
                            $branch[] = array(
                                'label'     => _('Warehouse areas'),
                                'icon'      => 'inventory',
                                'reference' => 'warehouse/'.$state['warehouse']->id.'/areas'
                            );
                            $branch[] = array(
                                'label'     => '<span class="id Warehouse_Area_Code">'.$state['_parent']->get('Code').'</span>',
                                'icon'      => 'inventory',
                                'reference' => 'warehouse/'.$state['warehouse']->id.'/areas/'.$state['_parent']->id
                            );
                            break;
                        case 'Warehouse':

                            break;
                    }


                    $branch[] = array(
                        'label'     => _('New location'),
                        'icon'      => '',
                        'reference' => ''
                    );


                    break;

                case 'categories':
                    $branch[] = array(
                        'label'     => _("Location's categories"),
                        'icon'      => 'sitemap',
                        'reference' => ''
                    );
                    break;
                case 'category':
                    $category = $state['_object'];
                    $branch[] = array(
                        'label'     => _("Location's categories"),
                        'icon'      => 'sitemap',
                        'reference' => 'inventory/categories'
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
                        if ($category_key == $state['key']) {
                            $branch[] = array(
                                'label'     => '<span class="Category_Label">'.$category->get('Label').'</span>',
                                'icon'      => '',
                                'reference' => ''
                            );
                            break;
                        } else {

                            $parent_category = new Category($category_key);
                            if ($parent_category->id) {

                                $branch[] = array(
                                    'label'     => $parent_category->get('Label'),
                                    'icon'      => '',
                                    'reference' => 'inventory/category/'.$parent_category->id
                                );

                            }
                        }
                    }

                    break;
                case 'warehouse.new':

                    $branch[] = array(
                        'label'     => _('New warehouse'),
                        'icon'      => 'warehouse-alt',
                        'reference' => ''
                    );

                    break;
                case 'warehouse_area.new':

                    $branch[] = array(
                        'label'     => _('New warehouse area'),
                        'icon'      => 'inventory',
                        'reference' => ''
                    );

                    break;
                case 'part':

                    $branch[] = array(
                        'label'     => _('Inventory').' <span class="id">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'th-large',
                        'reference' => 'inventory/'.$state['warehouse']->id
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Part_Reference">'.$state['_object']->get('Reference').'</span> (<span class="id">'.$state['_object']->get(
                                'SKU'
                            ).'</span>)',
                        'icon'      => 'box',
                        'reference' => ''
                    );

                    break;

                case 'leakages':


                    $branch[] = array(
                        'label'     => _('Warehouse dashboard'),
                        'icon'      => 'tachometer',
                        'reference' => 'warehouse/'.$state['warehouse']->id.'/dashboard'
                    );
                    $branch[] = array(
                        'label'     => _('Stock leakages'),
                        'icon'      => 'inbox',
                        'reference' => ''
                    );
                    break;
                case 'timeseries_record':


                    $branch[] = array(
                        'label'     => _('Warehouse dashboard'),
                        'icon'      => 'tachometer',
                        'reference' => 'warehouse/'.$state['warehouse']->id.'/dashboard'
                    );
                    $branch[] = array(
                        'label'     => _('Stock leakages'),
                        'icon'      => 'inbox',
                        'reference' => 'warehouse/'.$state['warehouse']->id.'/leakages'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id">'.$state['_object']->get('Code').'</span>',
                        'icon'      => 'table',
                        'reference' => ''
                    );
                    break;
                case 'production_deliveries':

                    $branch[] = array(
                        'label'     => '<span class="id Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'warehouse-alt',
                        'reference' => 'warehouse/'.$state['parent_key']
                    );

                    $branch[] = array(
                        'label'     => _('Production deliveries'),
                        'icon'      => 'industry',
                        'reference' => ''
                    );

                    break;
                case 'production_delivery':

                    $branch[] = array(
                        'label'     => '<span class="id Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'warehouse-alt',
                        'reference' => 'warehouse/'.$state['parent_key']
                    );

                    $branch[] = array(
                        'label'     => _('Production deliveries'),
                        'icon'      => 'industry',
                        'reference' => 'warehouse/'.$state['parent_key'].'/production_deliveries'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id">'.$state['_object']->get('Public ID').'</span>',
                        'icon'      => 'boxes',
                        'reference' => ''
                    );
                    break;
                case 'returns':


                    $branch[] = array(
                        'label'     => '<span class=" Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'warehouse-alt',
                        'reference' => 'warehouse/'.$state['parent_key']
                    );

                    $branch[] = array(
                        'label'     => _('Returns'),
                        'icon'      => 'backspace',
                        'reference' => ''
                    );
                    break;
                case 'return':


                    $branch[] = array(
                        'label'     => '<span class=" Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'warehouse-alt',
                        'reference' => 'warehouse/'.$state['parent_key']
                    );

                    $branch[] = array(
                        'label'     => _('Returns'),
                        'icon'      => 'backspace',
                        'reference' => 'warehouse/'.$state['parent_key'].'/returns'
                    );

                    $branch[] = array(
                        'label'     => '<span class="id">'.$state['_object']->get('Public ID').'</span>',
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;
                case 'shippers':


                    $branch[] = array(
                        'label'     => '<span class=" Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'warehouse-alt',
                        'reference' => 'warehouse/'.$state['parent_key']
                    );

                    $branch[] = array(
                        'label'     => _('Shipping companies'),
                        'icon'      => 'truck-loading',
                        'reference' => ''
                    );
                    break;
                case 'shipper.new':


                    $branch[] = array(
                        'label'     => '<span class=" Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'warehouse-alt',
                        'reference' => 'warehouse/'.$state['parent_key']
                    );

                    $branch[] = array(
                        'label'     => _('Shipping companies'),
                        'icon'      => 'truck-loading',
                        'reference' => 'warehouse/'.$state['parent_key'].'/shippers'
                    );

                    $branch[] = array(
                        'label'     => _('Add shipping company'),
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;


                case 'upload':

                    $branch[] = array(
                        'label'     => '<span class=" Warehouse_Code">'.$state['warehouse']->get('Code').'</span>',
                        'icon'      => 'warehouse-alt',
                        'reference' => 'warehouse/'.$state['parent_key']
                    );


                    if ($state['_object']->get('Upload Type') == 'EditObjects') {


                        switch ($state['_object']->get('Upload Object')) {
                            case 'warehouse_area':

                                $branch[] = array(
                                    'label'     => _('Warehouse areas'),
                                    'icon'      => '',
                                    'reference' => 'warehouse/'.$state['parent_key'].'/areas'
                                );


                                $branch[] = array(
                                    'label'     => _('Editing warehouse areas').' ('.$state['_object']->get('Filename').')',
                                    'icon'      => 'upload',
                                    'reference' => ''
                                );

                                break;
                            case 'location':


                                switch ($state['parent']) {
                                    case 'warehouse':


                                        $branch[] = array(
                                            'label'     => _('Locations'),
                                            'icon'      => '',
                                            'reference' => 'warehouse/'.$state['parent_key'].'/locations'
                                        );

                                        break;
                                    case 'warehouse_area':

                                        $branch[] = array(
                                            'label'     => _('Warehouse areas'),
                                            'icon'      => '',
                                            'reference' => 'warehouse/'.$state['parent_key'].'/areas'
                                        );

                                        $branch[] = array(
                                            'label'     => '<span class="id Warehouse_Area_Code">'.$state['_parent']->get('Code').'</span>',
                                            'icon'      => 'inventory',
                                            'reference' => 'warehouse/'.$state['warehouse']->id.'/areas/'.$state['_parent']->id
                                        );

                                        break;
                                }


                                $branch[] = array(
                                    'label'     => _('Editing locations').' ('.$state['_object']->get('Filename').')',
                                    'icon'      => 'upload',
                                    'reference' => ''
                                );


                                break;
                        }


                    } else {

                        switch ($state['_object']->get('Upload Object')) {
                            case 'warehouse_area':

                                $branch[] = array(
                                    'label'     => _('Warehouse areas'),
                                    'icon'      => '',
                                    'reference' => 'warehouse/'.$state['parent_key'].'/areas'
                                );


                                $branch[] = array(
                                    'label'     => _('Adding warehouse areas').' ('.$state['_object']->get('Filename').')',
                                    'icon'      => 'upload',
                                    'reference' => ''
                                );

                                break;
                            case 'location':


                                switch ($state['parent']) {
                                    case 'warehouse':


                                        $branch[] = array(
                                            'label'     => _('Locations'),
                                            'icon'      => '',
                                            'reference' => 'warehouse/'.$state['parent_key'].'/locations'
                                        );

                                        break;
                                    case 'warehouse_area':

                                        $branch[] = array(
                                            'label'     => _('Warehouse areas'),
                                            'icon'      => '',
                                            'reference' => 'warehouse/'.$state['parent_key'].'/areas'
                                        );

                                        $branch[] = array(
                                            'label'     => '<span class="id Warehouse_Area_Code">'.$state['_parent']->get('Code').'</span>',
                                            'icon'      => 'inventory',
                                            'reference' => 'warehouse/'.$state['warehouse']->id.'/areas/'.$state['_parent']->id
                                        );

                                        break;
                                }


                                $branch[] = array(
                                    'label'     => _('Adding locations').' ('.$state['_object']->get('Filename').')',
                                    'icon'      => 'upload',
                                    'reference' => ''
                                );


                                break;
                        }


                    }


                    /*

                    switch ($data['parent']) {


                    }

                    switch ($state['_object']->get('Upload Object')) {
                        case 'location':

                            $branch[] = array(
                                'label'     => _('Locations'),
                                'icon'      => '',
                                'reference' => 'warehouse/'.$state['parent_key'].'/locations'
                            );

                            $branch[] = array(
                                'label'     => _('Uploading new locations').' ('.$state['_object']->get('Filename').')',
                                'icon'      => 'upload',
                                'reference' => ''
                            );
                            break;
                        case 'warehouse_area':

                            $branch[] = array(
                                'label'     => _('Warehouse areas'),
                                'icon'      => '',
                                'reference' => 'warehouse/'.$state['parent_key'].'/areas'
                            );


                            $branch[] = array(
                                'label'     => '<span title="'.$state['_object']->get('Filename').'">'._('Uploading new warehouse areas').'</span>',
                                'icon'      => 'upload',
                                'reference' => ''
                            );


                            break;

                    }

*/
                    break;

            }


            break;

        case 'fulfilment':
            switch ($state['section']) {
                case 'dashboard':
                    $branch[] = array(
                        'label'     => _('Fulfilment dashboard'),
                        'icon'      => 'tachometer',
                        'reference' => ''
                    );

                    break;
                case 'customers':
                    $branch[] = array(
                        'label'     => _('Fulfilment'),
                        'icon'      => 'tachometer',
                        'reference' => 'fulfilment/'.$state['current_warehouse'].'/dashboard'
                    );
                    $branch[] = array(
                        'label'     => _('Customers'),
                        'icon'      => 'user',
                        'reference' => ''
                    );

                    break;
                case 'asset_keeping_customer':
                    $branch[] = array(
                        'label'     => _('Fulfilment'),
                        'icon'      => 'tachometer',
                        'reference' => 'fulfilment/'.$state['current_warehouse'].'/dashboard'
                    );
                    $branch[] = array(
                        'label'     => _('Customers').' ('._('Asset keeping').')',
                        'icon'      => 'user',
                        'reference' => 'fulfilment/'.$state['current_warehouse'].'/customers'
                    );
                    $branch[] = array(
                        'label'     => $state['_object']->get('Formatted ID'),
                        'icon'      => '',
                        'reference' => ''
                    );

                    break;
                case 'dropshipping_customer':
                    $branch[] = array(
                        'label'     => _('Fulfilment'),
                        'icon'      => 'tachometer',
                        'reference' => 'fulfilment/'.$state['current_warehouse'].'/dashboard'
                    );
                    $branch[] = array(
                        'label'     => _('Customers').' ('._('Dropshipping').')',
                        'icon'      => 'user',
                        'reference' => 'fulfilment/'.$state['current_warehouse'].'/customers'
                    );
                    $branch[] = array(
                        'label'     => $state['_object']->get('Formatted ID'),
                        'icon'      => '',
                        'reference' => ''
                    );

                    break;
                case 'delivery':

                    if ($state['parent'] == 'customer') {

                        $branch[] = array(
                            'label'     => _('Fulfilment'),
                            'icon'      => 'tachometer',
                            'reference' => 'fulfilment/'.$state['current_warehouse'].'/dashboard'
                        );
                        $branch[] = array(
                            'label'     => _('Customers').' ('.($state['_object']->get('Fulfilment Delivery Type') == 'Part' ? _('Dropshipping') : _('Asset keeping')).')',
                            'icon'      => '',
                            'reference' => 'fulfilment/'.$state['current_warehouse'].'/customers'
                        );
                        $branch[] = array(
                            'label'     => $state['_parent']->get('Name').' '.$state['_parent']->get('Formatted ID'),
                            'icon'      => 'user',
                            'reference' => 'fulfilment/'.$state['current_warehouse'].'/customers/'.($state['_object']->get('Fulfilment Delivery Type') == 'Part' ? 'dropshipping' : 'asset_keeping').'/'.$state['parent_key']
                        );
                        $branch[] = array(
                            'label'     => $state['_object']->get('Formatted ID'),
                            'icon'      => 'arrow-square-down',
                            'reference' => ''
                        );
                    }
                    break;
                case 'asset':

                    if ($state['parent'] == 'fulfilment_delivery') {


                        $branch[] = array(
                            'label'     => _('Fulfilment'),
                            'icon'      => 'tachometer',
                            'reference' => 'fulfilment/'.$state['current_warehouse'].'/dashboard'
                        );
                        $branch[] = array(
                            'label'     => _('Customers').' ('.($state['_parent']->get('Fulfilment Delivery Type') == 'Part' ? _('Dropshipping') : _('Asset keeping')).')',
                            'icon'      => '',
                            'reference' => 'fulfilment/'.$state['current_warehouse'].'/customers'
                        );
                        $branch[] = array(
                            'label'     => $state['_parent']->get('Customer Name').' '.$state['_parent']->get('Customer Key'),
                            'icon'      => 'user',
                            'reference' => 'fulfilment/'.$state['current_warehouse'].'/customers/'.($state['_object']->get('Fulfilment Delivery Type') == 'Part' ? 'dropshipping' : 'asset_keeping').'/'.$state['_parent']->get('Customer Key')
                        );
                        $branch[] = array(
                            'label'     => $state['_parent']->get('Formatted ID'),
                            'icon'      => 'arrow-square-down',
                            'reference' => 'fulfilment/'.$state['current_warehouse'].'/customers/'.($state['_object']->get('Fulfilment Delivery Type') == 'Part' ? 'dropshipping' : 'asset_keeping').'/'.$state['_parent']->get('Customer Key').'/delivery/'.$state['_parent']->id
                        );
                        $branch[] = array(
                            'label'     => $state['_object']->get('Formatted ID'),
                            'html_icon'      => $state['_object']->get('Type Icon'),
                            'reference' => ''
                        );
                    }
                    break;

            }
            break;

        case 'websites_server':


            $branch[] = array(
                'label'     => _('Websites'),
                'icon'      => '',
                'reference' => ''
            );


            break;
        case 'websites':
            $state['current_store'] = $state['store']->id;

            if ($user->get_number_websites() > 1) {

                $branch[] = array(
                    'label'     => '('._('All websites').')',
                    'icon'      => '',
                    'reference' => 'websites/all'
                );

            }
            switch ($state['section']) {
                case 'analytics':
                    $branch[] = array(
                        'label'     => '<span class="id Website_Code">'.$state['website']->get('Code'),
                        'icon'      => 'analytics',
                        'reference' => '',
                    );
                    break;
                case 'workshop':
                    $branch[] = array(
                        'label'     => '<span class="id Website_Code">'.$state['website']->get('Code').'</span> '._('workshop'),
                        'icon'      => 'drafting-compass',
                        'reference' => ''
                    );
                    break;
                case 'web_users':
                    $branch[] = array(
                        'label'     => '<span class="id Website_Code">'.$state['website']->get('Code').'</span> '._('Registered users'),
                        'icon'      => 'users-class',
                        'reference' => ''
                    );
                    break;
                case 'settings':
                    $branch[] = array(
                        'label'     => '<span class="id Website_Code">'.$state['website']->get('Code').'</span> '._('settings'),
                        'icon'      => 'sliders-h',
                        'reference' => ''
                    );
                    break;
                case 'website':


                    $branch[] = array(
                        'label'     => '<span class="id Website_Code">'.$state['website']->get('Code').'</span>',
                        'icon'      => '',
                        'reference' => 'website/'.$state['website']->id
                    );
                    break;
                case 'webpage_type':
                    $branch[] = array(
                        'label'     => '<span class="id Website_Code">'.$state['website']->get('Code').'</span>',
                        'icon'      => 'globe',
                        'reference' => 'webpages/'.$state['website']->id
                    );

                    $branch[] = array(
                        'label'     => _('Web page type').': <span class="id">'.$state['_object']->get('Label').'</span>',
                        'icon'      => '',
                        'reference' => ''
                    );
                    break;

                case 'webpages':


                    $branch[] = array(
                        'label'     => '<span class="id Website_Code">'.$state['website']->get('Code').'</span> <i class="fa fa-files" aria-hidden="true"></i>',
                        'icon'      => 'browser',
                        'reference' => 'website/'.$state['website']->id
                    );


                    break;

                case 'page':

                    $branch[] = array(
                        'label'     => $state['website']->get(
                            'Code'
                        ),
                        'icon'      => 'globe',
                        'reference' => 'website/'.$state['website']->id
                    );
                    $branch[] = array(
                        'label'     => $state['_object']->get('Code'),
                        'icon'      => 'file',
                        'reference' => ''
                    );


                    break;
                case 'page_version':

                    $branch[] = array(
                        'label'     => $state['website']->get(
                            'Code'
                        ),
                        'icon'      => 'globe',
                        'reference' => 'website/'.$state['website']->id
                    );
                    $branch[] = array(
                        'label'     => $state['_parent']->get('Code'),
                        'icon'      => 'file',
                        'reference' => 'website/'.$state['website']->id.'/page/'.$state['_parent']->id
                    );


                    switch ($state['_object']->get('Webpage Version Device')) {
                        case 'Desktop':
                            $device_icon = 'desktop';
                            break;
                        case 'Mobile':
                            $device_icon = 'mobile';
                            break;
                        case 'Tablet':
                            $device_icon = 'tablet';
                            break;
                        default:
                            $device_icon = '';
                    }

                    $branch[] = array(
                        'label'     => ' <i class="fa fa-code-fork" aria-hidden="true"></i>'.$state['_object']->get('Code'),
                        'icon'      => $device_icon,
                        'reference' => ''
                    );

                    break;
                case 'website.user':

                    if ($state['parent'] == 'website') {
                        $website = new Website($state['parent_key']);
                    } elseif ($state['parent'] == 'page') {
                        $page    = new Page($state['parent_key']);
                        $website = new Website($page->get('Webpage Website Key'));
                    }

                    $branch[] = array(
                        'label'     => _('Website').' '.$website->get('Code'),
                        'icon'      => 'globe',
                        'reference' => 'website/'.$website->id
                    );

                    if ($state['parent'] == 'page') {

                        $branch[] = array(
                            'label'     => _('Page').' '.$page->get('Code'),
                            'icon'      => 'file',
                            'reference' => 'website/'.$website->id.'/page/'.$page->id
                        );

                    }

                    $branch[] = array(
                        'label'     => _('User').' '.$state['_object']->data['User Handle'],
                        'icon'      => 'user',
                        'reference' => 'website/'.$website->id.'/user/'.$state['_object']->id
                    );

                    break;
            }

            break;

        case 'profile':
            $branch[] = array(
                'label'     => _('My profile').' <span class="id">'.$user->get('User Alias').'</span>',
                'icon'      => '',
                'reference' => 'profile'
            );


            break;
        case 'accounting_server':


            if ($state['section'] == 'dashboard') {

                $branch[] = array(
                    'label'     => _('Accounting'),
                    'icon'      => 'abacus',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'payment_account') {


                if ($state['parent'] == 'account') {
                    $branch[] = array(
                        'label'     => _('Payment accounts').' ('._('All stores').')',
                        'icon'      => '',
                        'reference' => 'payment_accounts/all'
                    );

                }

                $branch[] = array(
                    'label'     => '<span title="'._('Payment account').'" id="id">'.$state['_object']->get('Payment Account Code').'</span>',
                    'icon'      => 'money-check-alt',
                    'reference' => 'account/payment_service_provider/'.$state['_object']->id
                );


            } elseif ($state['section'] == 'payment_accounts') {
                if ($state['parent'] == 'account') {

                    $branch[] = array(
                        'label'     => _('Payment accounts').' ('._('All stores').')',
                        'icon'      => 'money-check-alt',
                        'reference' => 'payment_accounts/all'
                    );

                } elseif ($state['parent'] == 'payment_service_provider') {

                    /*
                     * @var $psp \Payment_Service_Provider
                     */
                    $psp = get_object('Payment_Service_Provider', $state['parent_key']);

                    $branch[] = array(
                        'label'     => _('Payment service provider').'  <span id="id">'.$psp->get('Payment Service Provider Code').'</span>',
                        'icon'      => '',
                        'reference' => 'payment_service_provider/'.$psp->id
                    );
                    $branch[] = array(
                        'label'     => _('Payment accounts'),
                        'icon'      => '',
                        'reference' => ''
                    );

                }


            } elseif ($state['section'] == 'payment_service_providers') {


                $branch[] = array(
                    'label'     => _('Payment service providers'),
                    'icon'      => 'cash-register',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'payment_service_provider') {

                $branch[] = array(
                    'label'     => _('Payment service providers'),
                    'icon'      => 'cash-register',
                    'reference' => 'payment_service_providers/all'
                );


                $branch[] = array(
                    'label'     => '<span id="id">'.$state['_object']->get('Code').'</span>',
                    'icon'      => '',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'payments') {

                if ($state['parent'] == 'account') {
                    $branch[] = array(
                        'label'     => _('Payments').' ('._(
                                'All stores'
                            ).')',
                        'icon'      => '',
                        'reference' => 'payments/all'
                    );

                } elseif ($state['parent'] == 'store') {
                    $store = new Store($state['parent_key']);


                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'payments/all'
                        );
                    }

                    $branch[] = array(
                        'label'     => _('Payments').'  <span id="id">('.$store->get('Code').')</span>',
                        'icon'      => '',
                        'reference' => 'payments/'.$store->id
                    );


                } elseif ($state['parent'] == 'payment_service_provider') {
                    $psp = get_object('Payment_Service_Provider', $state['parent_key']);

                    $branch[] = array(
                        'label'     => _(
                                'Payment service provider'
                            ).'  <span id="id">'.$psp->get('Payment Service Provider Code').'</span>',
                        'icon'      => '',
                        'reference' => 'account/payment_service_provider/'.$psp->id
                    );


                } elseif ($state['parent'] == 'payment_account') {
                    $payment_account = get_object('Payment_Account', $state['_object']->get('Payment Account Key'));


                    $branch[] = array(
                        'label'     => _('Payment account').'  <span id="id">'.$payment_account->get(
                                'Payment Account Code'
                            ).'</span>',
                        'icon'      => '',
                        'reference' => 'payment_service_provider/'.$payment_account->get('Payment Account Service Provider Key').'/payment_account/'.$payment_account->id
                    );


                }


            } elseif ($state['section'] == 'credits') {


                $branch[] = array(
                    'label'     => _('Credits').' ('._('All stores').')',
                    'icon'      => 'piggy-bank',
                    'reference' => 'credits/all'
                );


            } elseif ($state['section'] == 'invoices') {


                $branch[] = array(
                    'label'     => _('Invoices').' ('._('All stores').')',
                    'icon'      => 'file-invoice-dollar',
                    'reference' => 'invoices/all'
                );


            } elseif ($state['section'] == 'deleted_invoices_server') {


                $branch[] = array(
                    'label'     => _('Deleted invoices').' ('._('All stores').')',
                    'icon'      => 'ban',
                    'reference' => 'invoices/deleted/all'
                );


            } elseif ($state['section'] == 'invoice') {


                $branch[] = array(
                    'label'     => _('Invoices').' ('._('All stores').')',
                    'icon'      => '',
                    'reference' => 'invoices/all'
                );


                $branch[] = array(
                    'label'     => $state['_object']->get('Invoice Public ID'),
                    'icon'      => 'file-invoice-dollar',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'payments_by_store') {


                $branch[] = array(
                    'label'     => _('Payments by store'),
                    'icon'      => '',
                    'reference' => 'payments/by_store'
                );


            } elseif ($state['section'] == 'payment') {

                if ($state['parent'] == 'account') {


                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => _('Payments').' ('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'payments/all'
                        );
                    }


                    $branch[] = array(
                        'label'     => '<span id="id">'.$state['_object']->get('Transaction ID').'</span>',
                        'icon'      => 'credit-card',
                        'reference' => ''
                    );

                } elseif ($state['parent'] == 'payment_service_provider') {


                    $branch[] = array(
                        'label'     => _('Payment service providers'),
                        'icon'      => 'cash-register',
                        'reference' => 'payment_service_providers/all'
                    );
                    $branch[] = array(
                        'label'     => '<span id="id">'.$state['_parent']->get('Payment Service Provider Code').'</span>',
                        'icon'      => '',
                        'reference' => 'payment_service_provider/'.$state['_parent']->id
                    );

                    $branch[] = array(
                        'label'     => '<span id="id" title="'._('Payment').'">'.$state['_object']->get('Payment Transaction ID').'</span>',
                        'icon'      => 'credit-card',
                        'reference' => '',
                    );


                } elseif ($state['parent'] == 'payment_account') {

                    $branch[] = array(
                        'label'     => _('Payment accounts').' ('._('All stores').')',
                        'icon'      => '',
                        'reference' => 'payment_accounts/all'
                    );

                    $branch[] = array(
                        'label'     => '<span id="id" title="'._('Payment account').'">'.$state['_parent']->get('Payment Account Code').'</span>',
                        'icon'      => 'fa fa-money-check',
                        'reference' => 'payment_account/'.$state['_parent']->id
                    );

                    $branch[] = array(
                        'label'     => '<span id="id" title="'._('Payment').'">'.$state['_object']->get('Payment Transaction ID').'</span>',
                        'icon'      => 'credit-card',
                        'reference' => '',
                    );

                }


            }


            break;
        case 'accounting':

            if ($state['section'] == 'payment_account') {


                $branch[] = array(
                    'label'     => _('Payment accounts').' ('._('All stores').')',
                    'html_icon' => '',
                    'reference' => 'payment_accounts/all'
                );
                $branch[] = array(
                    'label'     => _('Payment accounts').' ('.$state['_parent']->get('Code').')',
                    'html_icon' => '',
                    'reference' => 'payment_accounts/'.$state['parent_key']
                );


                $branch[] = array(
                    'label'     => '<span id="id">'.$state['_object']->get('Payment Account Code').'</span>',
                    'html_icon' => '<i class="fal fa-money-check-alt"></i>',
                    'reference' => 'account/payment_service_provider/'.$state['_object']->id
                );


            } elseif ($state['section'] == 'payment_service_providers') {


                $branch[] = array(
                    'label'     => _('Payment service providers'),
                    'icon'      => 'bank',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'payment_service_provider') {

                $branch[] = array(
                    'label'     => _('Payment service providers'),
                    'icon'      => 'bank',
                    'reference' => ''
                );
                $psp      = new Payment_Service_Provider(
                    $state['_object']->get('Payment Service Provider Key')
                );

                $branch[] = array(
                    'label'     => _(
                            'Payment service provider'
                        ).'  <span id="id">'.$psp->get('Code').'</span>',
                    'icon'      => '',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'payments') {


                if ($state['tab'] == 'store.payment_accounts') {


                    $branch[] = array(
                        'label'     => '('._('All stores').')',
                        'icon'      => '',
                        'reference' => 'payments_accounts/all'
                    );

                    $branch[] = array(
                        'label'     => _('Payments accounts').'  <span id="id">('.$state['_parent']->get('Code').')</span>',
                        'icon'      => '',
                        'reference' => 'payments_accounts/'.$state['_parent']->id
                    );

                } else {
                    $branch[] = array(
                        'label'     => _('Payments per store'),
                        'html_icon' => '<i class="fal fa-layer-group"></i>',
                        'reference' => 'payments/per_store'
                    );

                    $branch[] = array(
                        'label'     => _('Payments').'  <span id="id">('.$state['_parent']->get('Code').')</span>',
                        'icon'      => '',
                        'reference' => 'payments/'.$state['_parent']->id
                    );
                }


                /*

                if ($state['parent'] == 'account') {
                    $branch[] = array(
                        'label'     => _('Payments').' ('._('All stores').')',
                        'icon'      => '',
                        'reference' => 'payments/all'
                    );

                } elseif ($state['parent'] == 'store') {
                    $store = new Store($state['parent_key']);


                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => '('._('All stores').')',
                            'icon'      => '',
                            'reference' => 'payments/all'
                        );
                    }

                    $branch[] = array(
                        'label'     => _('Payments').'  <span id="id">('.$store->get('Code').')</span>',
                        'icon'      => '',
                        'reference' => 'payments/'.$store->id
                    );


                } elseif ($state['parent'] == 'payment_service_provider') {
                    include_once 'class.Payment_Service_Provider.php';
                    $branch[] = array(
                        'label'     => _(
                                'Payment service provider'
                            ).'  <span id="id">'.$psp->get(
                                'Payment Service Provider Code'
                            ).'</span>',
                        'icon'      => '',
                        'reference' => 'account/payment_service_provider/'.$psp->id
                    );


                } elseif ($state['parent'] == 'payment_account') {
                    include_once 'class.Payment_Account.php';
                    $payment_account = new Payment_Account(
                        $state['_object']->get('Payment Account Key')
                    );
                    $branch[]        = array(
                        'label'     => _('Payment account').'  <span id="id">'.$payment_account->get(
                                'Payment Account Code'
                            ).'</span>',
                        'icon'      => '',
                        'reference' => 'payment_service_provider/'.$psp->id.'/payment_account/'.$payment_account->id
                    );


                }

                */


            } elseif ($state['section'] == 'payment') {


                if ($state['parent'] == 'store') {


                    if ($user->get_number_stores() > 1) {
                        $branch[] = array(
                            'label'     => _('Payments per store'),
                            'html_icon' => '<i class="fal fa-layer-group"></i>',
                            'reference' => 'payments/per_store'
                        );
                    }

                    $branch[] = array(
                        'label'     => _('Payments').'  <span id="id">('.$state['_parent']->get('Code').')</span>',
                        'icon'      => '',
                        'reference' => 'payments/'.$state['_parent']->id
                    );


                    $branch[] = array(
                        'label'     => '<span id="id" title="'._('Payment').'">'.$state['_object']->get('Payment Transaction ID').'</span>',
                        'icon'      => 'credit-card',
                        'reference' => ''
                    );

                } elseif ($state['parent'] == 'store_payment_account') {


                    $tmp = preg_split('/_/', $state['parent_key']);

                    $branch[] = array(
                        'label'     => _('Payment accounts').' ('._('All stores').')',
                        'html_icon' => '',
                        'reference' => 'payment_accounts/all'
                    );
                    $branch[] = array(
                        'label'     => _('Payment accounts').' ('.$state['store']->get('Code').')',
                        'html_icon' => '',
                        'reference' => 'payment_accounts/'.$tmp[0]
                    );


                    $branch[] = array(
                        'label'     => '<span id="id">'.$state['_object']->get('Payment Account Code').'</span>',
                        'html_icon' => '<i class="fal fa-money-check-alt"></i>',
                        'reference' => 'payment_accounts/'.$tmp[0].'/'.$tmp[1]
                    );

                    $branch[] = array(
                        'label'     => '<span id="id" title="'._('Payment').'">'.$state['_object']->get('Payment Transaction ID').'</span>',
                        'icon'      => 'credit-card',
                        'reference' => ''
                    );


                }


            } elseif ($state['section'] == 'invoice' or $state['section'] == 'refund') {


                if ($user->get_number_stores() > 1) {
                    $branch[] = array(
                        'label'     => _('Invoices per store'),
                        'html_icon' => '<i class="fal fa-layer-group"></i>',
                        'reference' => 'invoices/per_store'
                    );
                }


                $branch[] = array(
                    'label'     => _('Invoices').' '.$state['_parent']->get('Store Code'),
                    'icon'      => '',
                    'reference' => 'invoices/'.$state['_parent']->id
                );


                $branch[] = array(
                    'label'     => $state['_object']->get('Invoice Public ID'),
                    'icon'      => 'file-invoice-dollar',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'invoices') {

                if ($user->get_number_stores() > 1) {
                    $branch[] = array(
                        'label'     => _('Invoices per store'),
                        'html_icon' => '<i class="fal fa-layer-group"></i>',
                        'reference' => 'invoices/per_store'
                    );
                }
                $branch[] = array(
                    'label'     => _('Invoices').' '.$state['_parent']->get('Store Code'),
                    'icon'      => '',
                    'reference' => 'invoices/'.$state['_parent']->id
                );
            } elseif ($state['section'] == 'credits') {


                $branch[] = array(
                    'label'     => _('Credits').' ('._('All stores').')',
                    'icon'      => 'piggy-bank',
                    'reference' => 'credits/all'
                );


                $branch[] = array(
                    'label'     => _('Credits').' ('.$state['_parent']->get('Store Code').')',
                    'icon'      => '',
                    'reference' => ''
                );
            }


            break;
        case 'account':


            if ($state['section'] == 'orders_index') {
                $branch[] = array(
                    'label'     => _("Order's index"),
                    'icon'      => 'indent',
                    'reference' => ''
                );
                break;
            }

            $branch[] = array(
                'label'     => _('Account').' <span class="id">'.$account->get('Account Code').'</span>',
                'icon'      => '',
                'reference' => 'account'
            );


            if ($state['section'] == 'users') {
                $branch[] = array(
                    'label'     => _('Users'),
                    'icon'      => 'terminal',
                    'reference' => 'users'
                );

            } elseif ($state['section'] == 'staff') {
                $branch[] = array(
                    'label'     => _('Users'),
                    'icon'      => '',
                    'reference' => 'users'
                );
                $branch[] = array(
                    'label'     => _('Employees'),
                    'icon'      => 'terminal',
                    'reference' => 'users/staff'
                );
            } elseif ($state['section'] == 'contractors') {
                $branch[] = array(
                    'label'     => _('Users'),
                    'icon'      => '',
                    'reference' => 'users'
                );
                $branch[] = array(
                    'label'     => _('Contractors'),
                    'icon'      => 'terminal',
                    'reference' => 'users/contractors'
                );
            } elseif ($state['section'] == 'suppliers') {
                $branch[] = array(
                    'label'     => _('Users'),
                    'icon'      => '',
                    'reference' => 'users'
                );
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => 'terminal',
                    'reference' => 'users/suppliers'
                );
            } elseif ($state['section'] == 'agents') {
                $branch[] = array(
                    'label'     => _('Users'),
                    'icon'      => '',
                    'reference' => 'users'
                );
                $branch[] = array(
                    'label'     => _('Agents'),
                    'icon'      => 'terminal',
                    'reference' => 'users/agents'
                );
            } elseif ($state['section'] == 'user') {
                $branch[] = array(
                    'label'     => _('Users'),
                    'icon'      => '',
                    'reference' => 'users'
                );


                switch ($state['_object']->get('User Type')) {
                    case 'Staff':
                        $branch[] = array(
                            'label'     => _('Employees'),
                            'icon'      => '',
                            'reference' => 'users/staff'
                        );
                        break;
                    case 'Contractor':
                        $branch[] = array(
                            'label'     => _('Contractors'),
                            'icon'      => '',
                            'reference' => 'users/contractors'
                        );
                        break;
                    case 'Agent':
                        $branch[] = array(
                            'label'     => _('Agents'),
                            'icon'      => '',
                            'reference' => 'users/agents'
                        );
                        break;
                    case 'Suppliers':
                        $branch[] = array(
                            'label'     => _('Suppliers'),
                            'icon'      => '',
                            'reference' => 'users/suppliers'
                        );
                        break;
                    default:

                        break;
                }


                $branch[] = array(
                    'label'     => '<span id="id">'.$state['_object']->get('User Handle').'</span>',
                    'icon'      => 'terminal',
                    'reference' => 'users/'.$state['_object']->id
                );

            } elseif ($state['section'] == 'deleted.user') {
                $branch[] = array(
                    'label'     => _('Deleted users'),
                    'icon'      => '',
                    'reference' => 'account/deleted_users'
                );


                $branch[] = array(
                    'label'     => '<span id="id">'.$state['_object']->get('User Handle').'</span>  <i class="far fa-trash-alt padding_left_5" aria-hidden="true"></i> ',
                    'icon'      => 'terminal',
                    'reference' => 'users/'.$state['_object']->id
                );

            } elseif ($state['section'] == 'user.api_key') {

                $branch[] = array(
                    'label'     => _('Users'),
                    'icon'      => '',
                    'reference' => 'users'
                );

                switch ($state['_parent']->get('User Type')) {
                    case 'Staff':
                        $branch[] = array(
                            'label'     => _('Employees'),
                            'icon'      => '',
                            'reference' => 'users/staff'
                        );
                        break;
                    case 'Contractor':
                        $branch[] = array(
                            'label'     => _('Contractors'),
                            'icon'      => '',
                            'reference' => 'users/contractors'
                        );
                        break;
                    case 'Agent':
                        $branch[] = array(
                            'label'     => _('Agents'),
                            'icon'      => '',
                            'reference' => 'users/agents'
                        );
                        break;
                    case 'Suppliers':
                        $branch[] = array(
                            'label'     => _('Suppliers'),
                            'icon'      => '',
                            'reference' => 'users/suppliers'
                        );
                        break;
                    default:

                        break;
                }
                $branch[] = array(
                    'label'     => '<span >'.$state['_parent']->get('User Handle').'</span>',
                    'icon'      => 'terminal',
                    'reference' => 'users/'.$state['_parent']->id
                );

                $branch[] = array(
                    'label'     => _('API key').': <span class="id">'.$state['_object']->get('Scope').'</span> ('.$state['_object']->get('Code').')',
                    'icon'      => '',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'user.api_key.new') {

                $branch[] = array(
                    'label'     => _('Users'),
                    'icon'      => '',
                    'reference' => 'users'
                );

                switch ($state['_parent']->get('User Type')) {
                    case 'Staff':
                        $branch[] = array(
                            'label'     => _('Employees'),
                            'icon'      => '',
                            'reference' => 'users/staff'
                        );
                        break;
                    case 'Contractor':
                        $branch[] = array(
                            'label'     => _('Contractors'),
                            'icon'      => '',
                            'reference' => 'users/contractors'
                        );
                        break;
                    case 'Agent':
                        $branch[] = array(
                            'label'     => _('Agents'),
                            'icon'      => '',
                            'reference' => 'users/agents'
                        );
                        break;
                    case 'Suppliers':
                        $branch[] = array(
                            'label'     => _('Suppliers'),
                            'icon'      => '',
                            'reference' => 'users/suppliers'
                        );
                        break;
                    default:

                        break;
                }
                $branch[] = array(
                    'label'     => '<span >'.$state['_parent']->get('User Handle').'</span>',
                    'icon'      => 'terminal',
                    'reference' => 'users/'.$state['_parent']->id
                );

                $branch[] = array(
                    'label'     => _('New API key'),
                    'icon'      => '',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'deleted_api_key') {

                $branch[] = array(
                    'label'     => _('Users'),
                    'icon'      => '',
                    'reference' => 'users'
                );

                switch ($state['_parent']->get('User Type')) {
                    case 'Staff':
                        $branch[] = array(
                            'label'     => _('Employees'),
                            'icon'      => '',
                            'reference' => 'users/staff'
                        );
                        break;
                    case 'Contractor':
                        $branch[] = array(
                            'label'     => _('Contractors'),
                            'icon'      => '',
                            'reference' => 'users/contractors'
                        );
                        break;
                    case 'Agent':
                        $branch[] = array(
                            'label'     => _('Agents'),
                            'icon'      => '',
                            'reference' => 'users/agents'
                        );
                        break;
                    case 'Suppliers':
                        $branch[] = array(
                            'label'     => _('Suppliers'),
                            'icon'      => '',
                            'reference' => 'users/suppliers'
                        );
                        break;
                    default:

                        break;
                }
                $branch[] = array(
                    'label'     => '<span >'.$state['_parent']->get('User Handle').'</span>',
                    'icon'      => 'terminal',
                    'reference' => 'users/'.$state['_parent']->id
                );

                $branch[] = array(
                    'label'     => _('Deleted API key').': <span class="id">'.$state['_object']->get('Deleted Scope').'</span> ('.$state['_object']->get('Deleted Code').')',
                    'icon'      => '',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'settings') {
                $branch[] = array(
                    'label'     => _('Settings'),
                    'icon'      => 'cog',
                    'reference' => 'account/settings'
                );

            } elseif ($state['section'] == 'payment_service_provider') {
                $branch[] = array(
                    'label'     => _('Payment service provider').'  <span id="id">'.$state['_object']->get(
                            'Payment Service Provider Code'
                        ).'</span>',
                    'icon'      => '',
                    'reference' => 'account/payment_service_provider/'.$state['_object']->id
                );

            } elseif ($state['section'] == 'data_sets') {
                $branch[] = array(
                    'label'     => _('Data sets'),
                    'icon'      => 'align-left',
                    'reference' => 'account/data_sets'
                );

            } elseif ($state['section'] == 'timeseries') {
                $branch[] = array(
                    'label'     => _('Data sets'),
                    'icon'      => 'align-left',
                    'reference' => 'account/data_sets'
                );
                $branch[] = array(
                    'label'     => _('Time series'),
                    'icon'      => 'line-chart',
                    'reference' => 'account/data_sets/timeseries'
                );

            } elseif ($state['section'] == 'timeserie') {
                $branch[] = array(
                    'label'     => _('Data sets'),
                    'icon'      => 'align-left',
                    'reference' => 'account/data_sets'
                );
                $branch[] = array(
                    'label'     => _('Time series'),
                    'icon'      => 'line-chart',
                    'reference' => 'account/data_sets/timeseries'
                );
                $branch[] = array(
                    'label'     => $state['_object']->get('Name'),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'images') {
                $branch[] = array(
                    'label'     => _('Data sets'),
                    'icon'      => 'align-left',
                    'reference' => 'account/data_sets'
                );
                $branch[] = array(
                    'label'     => _('Images'),
                    'icon'      => 'image',
                    'reference' => 'account/data_sets/images'
                );

            } elseif ($state['section'] == 'attachments') {
                $branch[] = array(
                    'label'     => _('Data sets'),
                    'icon'      => 'align-left',
                    'reference' => 'account/data_sets'
                );
                $branch[] = array(
                    'label'     => _('Attachments'),
                    'icon'      => 'paperclip',
                    'reference' => 'account/data_sets/attachments'
                );

            } elseif ($state['section'] == 'uploads') {
                $branch[] = array(
                    'label'     => _('Data sets'),
                    'icon'      => 'align-left',
                    'reference' => 'account/data_sets'
                );
                $branch[] = array(
                    'label'     => _('Records uploads'),
                    'icon'      => 'upload',
                    'reference' => 'account/data_sets/uploads'
                );

            } elseif ($state['section'] == 'materials') {
                $branch[] = array(
                    'label'     => _('Data sets'),
                    'icon'      => 'align-left',
                    'reference' => 'account/data_sets'
                );
                $branch[] = array(
                    'label'     => _('Materials'),
                    'icon'      => 'puzzle-piece',
                    'reference' => 'account/data_sets/materials'
                );

            } elseif ($state['section'] == 'material') {
                $branch[] = array(
                    'label'     => _('Data sets'),
                    'icon'      => 'align-left',
                    'reference' => 'account/data_sets'
                );
                $branch[] = array(
                    'label'     => _('Materials'),
                    'icon'      => '',
                    'reference' => 'account/data_sets/materials'
                );
                $branch[] = array(
                    'label'     => '<span class="Material_Name">'.$state['_object']->get('Name').'</span>',
                    'icon'      => 'puzzle-piece',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'osf') {
                $branch[] = array(
                    'label'     => _('Data sets'),
                    'icon'      => 'align-left',
                    'reference' => 'account/data_sets'
                );
                $branch[] = array(
                    'label'     => _('Transactions timeseries'),
                    'icon'      => '',
                    'reference' => 'account/data_sets/osf'
                );

            } elseif ($state['section'] == 'isf') {
                $branch[] = array(
                    'label'     => _('Data sets'),
                    'icon'      => 'align-left',
                    'reference' => 'account/data_sets'
                );
                $branch[] = array(
                    'label'     => _('Inventory timeseries'),
                    'icon'      => '',
                    'reference' => 'account/data_sets/isf'
                );

            } elseif ($state['section'] == 'upload') {

                if ($state['parent'] == 'supplier') {
                    $branch   = array();
                    $branch[] = array(
                        'label'     => _('Suppliers'),
                        'icon'      => '',
                        'reference' => 'suppliers'
                    );
                    $branch[] = array(
                        'label'     => '<span class="Supplier_Code">'.$state['_parent']->get('Code').'</span>',
                        'icon'      => 'hand-holding-box',
                        'reference' => 'supplier/'.$state['parent_key']
                    );
                    $branch[] = array(
                        'label'     => _('Upload').' '.sprintf(
                                '%04d', $state['_object']->get('Key')
                            ),
                        'icon'      => 'upload',
                        'reference' => ''
                    );

                } elseif ($state['parent'] == 'inventory') {
                    $branch   = array();
                    $branch[] = array(
                        'label'     => _('Inventory'),
                        'icon'      => 'th-large',
                        'reference' => 'inventory'
                    );
                    $branch[] = array(
                        'label'     => _('Upload').' '.sprintf(
                                '%04d', $state['_object']->get('Key')
                            ),
                        'icon'      => 'upload',
                        'reference' => ''
                    );

                } else {

                    $branch[] = array(
                        'label'     => _('Data sets'),
                        'icon'      => 'align-left',
                        'reference' => 'account/data_sets'
                    );
                    $branch[] = array(
                        'label'     => _('Records uploads'),
                        'icon'      => '',
                        'reference' => 'account/data_sets/uploads'
                    );
                    $branch[] = array(
                        'label'     => _('Upload').' '.sprintf(
                                '%04d', $state['_object']->get('Key')
                            ),
                        'icon'      => 'upload',
                        'reference' => ''
                    );
                }
            }

            /*
		case ('data_sets'):
			return get_data_sets_navigation($data, $smarty, $user, $db,$account);
			break;
		case ('timeseries'):
			return get_timeseries_navigation($data, $smarty, $user, $db,$account);
			break;
		case ('images'):
			return get_images_navigation($data, $smarty, $user, $db,$account);
			break;
		case ('attachments'):
			return get_attachments_navigation($data, $smarty, $user, $db,$account);
			break;
		case ('osf'):
			return get_osf_navigation($data, $smarty, $user, $db,$account);
			break;
		case ('isf'):
			return get_isf_navigation($data, $smarty, $user, $db,$account);
			break;
*/


            break;

        case 'production_server':
            $branch[] = array(
                'label'     => _("Production (All manufactures)"),
                'icon'      => 'industry',
                'reference' => 'production/all'
            );


            if ($state['section'] == 'production.suppliers') {

                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => 'hand-holding-box',
                    'reference' => ''
                );

            }

            break;

        case 'production':


            /*
            $branch[] = array(
                'label'     => _("(All manufactures)"),
                'icon'      => '',
                'reference' => 'production/all'
            );
            */ $branch[] = array(
            'label'     => _("Production").' <span class="id Supplier_Code">'.$state['_object']->get('Code').'</span>',
            'icon'      => 'industry',
            'reference' => 'production'
        );


            if ($state['section'] == 'manufacture_tasks') {

                $branch[] = array(
                    'label'     => _("Manufacture Tasks"),
                    'icon'      => 'tasks',
                    'reference' => 'production/manufacture_tasks'
                );
            } elseif ($state['section'] == 'manufacture_task') {

                $branch[] = array(
                    'label'     => _("Manufacture Tasks"),
                    'icon'      => 'tasks',
                    'reference' => 'production/manufacture_tasks'
                );
                $branch[] = array(
                    'label'     => '<span class="Manufacture_Task_Code">'.$state['_object']->get('Code').'</span>',
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'settings') {

                $branch[] = array(
                    'label'     => _('Settings'),
                    'icon'      => 'sliders',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'dashboard') {

                $branch[] = array(
                    'label'     => _('Dashboard'),
                    'icon'      => 'tachometer',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'raw_materials') {

                $branch[] = array(
                    'label'     => _('Raw materials'),
                    'icon'      => 'puzzle-piece',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'raw_material') {

                $branch[] = array(
                    'label'     => _('Raw materials'),
                    'icon'      => 'puzzle-piece',
                    'reference' => 'production/'.$state['current_production'].'/raw_materials'
                );
                $branch[] = array(
                    'label'     => '<span class="Raw_Material_Code">'.$state['_object']->get('Code').'</span>',
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'production_parts') {

                $branch[] = array(
                    'label'     => _('Parts'),
                    'icon'      => 'hand-receiving',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'production_part') {

                $branch[] = array(
                    'label'     => _('Parts'),
                    'icon'      => 'hand-receiving',
                    'reference' => 'production/'.$state['current_production'].'/parts'
                );

                $branch[] = array(
                    'label'     => $state['_object']->get('Reference'),
                    'icon'      => 'box',
                    'reference' => ''
                );

            }

            break;
        case 'reports':
            $branch[] = array(
                'label'     => _('Reports'),
                'icon'      => '',
                'reference' => 'reports'
            );

            if ($state['section'] == 'billingregion_taxcategory') {
                $branch[] = array(
                    'label'     => _(
                        'Billing region & Tax code report'
                    ),
                    'icon'      => '',
                    'reference' => 'report/billingregion_taxcategory'
                );

            } elseif ($state['section'] == 'sales') {
                $branch[] = array(
                    'label'     => _('Sales report'),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'report_orders') {
                $branch[] = array(
                    'label'     => _("Dispatched order's sales"),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'report_orders_components') {
                $branch[] = array(
                    'label'     => _("Dispatched order's x-rays"),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'report_delivery_notes') {
                $branch[] = array(
                    'label'     => _('Delivery notes'),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'pickers') {
                $branch[] = array(
                    'label'     => _('Pickers productivity'),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'picker') {
                $branch[] = array(
                    'label'     => _('Pickers productivity'),
                    'icon'      => '',
                    'reference' => 'report/pickers',
                );
                $staff    = get_object('Staff', $state['key']);

                $branch[] = array(
                    'label'     => $staff->get('Name'),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'packers') {
                $branch[] = array(
                    'label'     => _('Packers productivity'),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'packer') {
                $branch[] = array(
                    'label'     => _('Packers productivity'),
                    'icon'      => '',
                    'reference' => 'report/packers',
                );

                $staff = get_object('Staff', $state['key']);

                $branch[] = array(
                    'label'     => $staff->get('Name'),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'sales_representatives') {
                $branch[] = array(
                    'label'     => _('Sales representatives productivity'),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'sales_representative') {
                $branch[] = array(
                    'label'     => _('Sales representatives productivity'),
                    'icon'      => '',
                    'reference' => 'report/sales_representatives'
                );
                $branch[] = array(
                    'label'     => $state['_object']->user->get('Alias'),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'prospect_agents') {
                $branch[] = array(
                    'label'     => _("Prospect's agents"),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'prospect_agent') {
                $branch[] = array(
                    'label'     => _("Prospect's agents"),
                    'icon'      => '',
                    'reference' => 'report/prospect_agents'
                );
                $branch[] = array(
                    'label'     => $state['_object']->user->get('Alias'),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'prospect_agent_email_tracking') {
                $branch[] = array(
                    'label'     => _("Prospect's agents"),
                    'icon'      => '',
                    'reference' => 'report/prospect_agents'
                );
                $branch[] = array(
                    'label'     => $state['_parent']->user->get('Alias'),
                    'icon'      => '',
                    'reference' => 'report/prospect_agents/'.$state['parent_key']
                );
                $branch[] = array(
                    'label'     => $state['_object']->get('Email Tracking Email'),
                    'icon'      => 'paper-plane',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'lost_stock') {
                $branch[] = array(
                    'label'     => _('Lost/Damaged stock'),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'stock_given_free') {
                $branch[] = array(
                    'label'     => _('Stock given for free'),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'intrastat') {
                $branch[] = array(
                    'label'     => _('Intrastat exports'),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'intrastat_imports') {
                $branch[] = array(
                    'label'     => _('Intrastat imports'),
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'intrastat_products') {
                $branch[] = array(
                    'label'     => _('Intrastat'),
                    'icon'      => '',
                    'reference' => 'report/intrastat'
                );


                $_data  = preg_split('/\|/', $state['extra']);
                $__data = preg_split('/_/', $_data[1]);

                $_parameters = array(

                    'period' => (!empty($_SESSION['table_state']['intrastat']['period']) ? $_SESSION['table_state']['intrastat']['period'] : 'last_m'),
                    'from'   => (!empty($_SESSION['table_state']['intrastat']['from']) ? $_SESSION['table_state']['intrastat']['from'] : ''),
                    'to'     => (!empty($_SESSION['table_state']['intrastat']['to']) ? $_SESSION['table_state']['intrastat']['to'] : '')


                );


                include_once 'utils/date_functions.php';

                $tmp  = calculate_interval_dates($db, $_parameters['period'], $_parameters['from'], $_parameters['to']);
                $from = $tmp[1];
                $to   = $tmp[2];


                $_from   = strftime('%d %b %Y', strtotime($from));
                $_to     = strftime('%d %b %Y', strtotime($to));
                $_period = '';
                if ($_from != $_to) {
                    $_period .= " ($_from-$_to)";
                } else {
                    $_period .= " ($_from)";
                }

                $_tariff_code = ($__data[1] == 'missing' ? _('commodity code missing') : $__data[1]);

                $branch[] = array(
                    'label'     => _('Products')."; $__data[0], $_tariff_code  $_period",
                    'icon'      => '',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'intrastat_orders') {
                $branch[] = array(
                    'label'     => _('Intrastat'),
                    'icon'      => '',
                    'reference' => 'report/intrastat'
                );


                $_data  = preg_split('/\|/', $state['extra']);
                $__data = preg_split('/_/', $_data[1]);

                $_parameters = array(

                    'period' => (!empty($_SESSION['table_state']['intrastat']['period']) ? $_SESSION['table_state']['intrastat']['period'] : 'last_m'),
                    'from'   => (!empty($_SESSION['table_state']['intrastat']['from']) ? $_SESSION['table_state']['intrastat']['from'] : ''),
                    'to'     => (!empty($_SESSION['table_state']['intrastat']['to']) ? $_SESSION['table_state']['intrastat']['to'] : '')


                );


                include_once 'utils/date_functions.php';

                $tmp  = calculate_interval_dates($db, $_parameters['period'], $_parameters['from'], $_parameters['to']);
                $from = $tmp[1];
                $to   = $tmp[2];


                $_from   = strftime('%d %b %Y', strtotime($from));
                $_to     = strftime('%d %b %Y', strtotime($to));
                $_period = '';
                if ($_from != $_to) {
                    $_period .= " ($_from-$_to)";
                } else {
                    $_period .= " ($_from)";
                }

                $_tariff_code = ($__data[1] == 'missing' ? _('commodity code missing') : $__data[1]);

                $branch[] = array(
                    'label'     => _('Orders')."; $__data[0], $_tariff_code  $_period",
                    'icon'      => '',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'intrastat_parts') {
                $branch[] = array(
                    'label'     => _('Intrastat imports'),
                    'icon'      => '',
                    'reference' => 'report/intrastat_imports'
                );


                $_data  = preg_split('/\|/', $state['extra']);
                $__data = preg_split('/_/', $_data[1]);

                $_parameters = array(

                    'period' => (!empty($_SESSION['table_state']['intrastat_imports']['period']) ? $_SESSION['table_state']['intrastat_imports']['period'] : 'last_m'),
                    'from'   => (!empty($_SESSION['table_state']['intrastat_imports']['from']) ? $_SESSION['table_state']['intrastat_imports']['from'] : ''),
                    'to'     => (!empty($_SESSION['table_state']['intrastat_imports']['to']) ? $_SESSION['table_state']['intrastat_imports']['to'] : '')


                );


                include_once 'utils/date_functions.php';


                $tmp  = calculate_interval_dates($db, $_parameters['period'], $_parameters['from'], $_parameters['to']);
                $from = $tmp[1];
                $to   = $tmp[2];


                $_from   = strftime('%d %b %Y', strtotime($from));
                $_to     = strftime('%d %b %Y', strtotime($to));
                $_period = '';
                if ($_from != $_to) {
                    $_period .= " ($_from-$_to)";
                } else {
                    $_period .= " ($_from)";
                }

                $_tariff_code = ($__data[1] == 'missing' ? _('commodity code missing') : $__data[1]);

                $branch[] = array(
                    'label'     => _('Parts')."; $__data[0], $_tariff_code  $_period",
                    'icon'      => '',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'intrastat_deliveries') {
                $branch[] = array(
                    'label'     => _('Intrastat imports'),
                    'icon'      => '',
                    'reference' => 'report/intrastat_imports'
                );


                $_data  = preg_split('/\|/', $state['extra']);
                $__data = preg_split('/_/', $_data[1]);

                $_parameters = array(

                    'period' => (!empty($_SESSION['table_state']['intrastat_imports']['period']) ? $_SESSION['table_state']['intrastat_imports']['period'] : 'last_m'),
                    'from'   => (!empty($_SESSION['table_state']['intrastat_imports']['from']) ? $_SESSION['table_state']['intrastat_imports']['from'] : ''),
                    'to'     => (!empty($_SESSION['table_state']['intrastat_imports']['to']) ? $_SESSION['table_state']['intrastat_imports']['to'] : '')


                );


                include_once 'utils/date_functions.php';

                $tmp  = calculate_interval_dates($db, $_parameters['period'], $_parameters['from'], $_parameters['to']);
                $from = $tmp[1];
                $to   = $tmp[2];

                $_from   = strftime('%d %b %Y', strtotime($from));
                $_to     = strftime('%d %b %Y', strtotime($to));
                $_period = '';
                if ($_from != $_to) {
                    $_period .= " ($_from-$_to)";
                } else {
                    $_period .= " ($_from)";
                }

                $_tariff_code = ($__data[1] == 'missing' ? _('commodity code missing') : $__data[1]);

                $branch[] = array(
                    'label'     => _('Deliveries')."; $__data[0], $_tariff_code  $_period",
                    'icon'      => '',
                    'reference' => ''
                );

            } else {
                if ($state['section'] == 'billingregion_taxcategory.invoices') {
                    $branch[] = array(
                        'label'     => _(
                            'Billing region & Tax code report'
                        ),
                        'icon'      => '',
                        'reference' => 'report/billingregion_taxcategory'
                    );


                    $parents = preg_split('/_/', $state['parent_key']);

                    switch ($parents[0]) {
                        case 'EU':
                            $billing_region = _('European Union');
                            break;
                        case 'NOEU':
                            $billing_region = _('Outside European Union');
                            break;
                        case 'GBIM':
                            $billing_region = 'GB+IM';
                            break;
                        case 'Unknown':
                            $billing_region = _('Unknown');
                            break;
                        default:
                            $billing_region = $parents[0];
                            break;
                    }

                    $label    = _('Invoices')." $billing_region & ".$parents[1];
                    $branch[] = array(
                        'label'     => $label,
                        'icon'      => '',
                        'reference' => ''
                    );

                } else {
                    if ($state['section'] == 'billingregion_taxcategory.refunds') {
                        $branch[] = array(
                            'label'     => _(
                                'Billing region & Tax code report'
                            ),
                            'icon'      => '',
                            'reference' => 'report/billingregion_taxcategory'
                        );
                        $parents  = preg_split('/_/', $state['parent_key']);

                        switch ($parents[0]) {
                            case 'EU':
                                $billing_region = _('European Union');
                                break;
                            case 'Unknown':
                                $billing_region = _('Unknown');
                                break;
                            case 'NOEU':
                                $billing_region = _('Outside European Union');
                                break;
                            case 'GBIM':
                                $billing_region = 'GB+IM';
                                break;
                            default:
                                $billing_region = $state[0];
                                break;
                        }

                        $label    = _('Refunds')." $billing_region & ".$parents[1];
                        $branch[] = array(
                            'label'     => $label,
                            'icon'      => '',
                            'reference' => ''
                        );
                    } elseif ($state['section'] == 'ec_sales_list') {
                        $branch[] = array(
                            'label'     => _('EC sales list'),
                            'icon'      => '',
                            'reference' => 'report/ec_sales_list'
                        );

                    }
                }
            }

            break;


        case 'offers_server':
            $branch[] = array(
                'label'     => _('Offers').' ('._('All stores).'),
                'icon'      => 'percentage',
                'reference' => ''
            );


            break;
        case 'offers':
            $state['current_store'] = $state['store']->id;
            $branch[]               = array(
                'label'     => _('(All stores)'),
                'icon'      => 'badge-percent',
                'reference' => 'offers/by_store'
            );


            if ($state['section'] == 'campaigns') {

                $branch[] = array(
                    'label'     => _("Offers categories").' '.$state['store']->get('Code'),
                    'icon'      => 'sitemap',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'offers') {

                $branch[] = array(
                    'label'     => _('Offers').' '.$state['store']->get('Code'),
                    'icon'      => 'tag',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'vouchers') {
                $branch[] = array(
                    'label'     => _("Offers categories").' '.$state['store']->get('Code'),
                    'icon'      => 'sitemap',
                    'reference' => 'offers/'.$state['store']->id.'/categories'
                );

                $branch[] = array(
                    'label'     => '<span class="Deal_Campaign_Name">'.$state['_object']->get('Name').'</span>',
                    'html_icon' => $state['_object']->get('Icon'),
                    'reference' => ''
                );


            } elseif ($state['section'] == 'campaign_order_recursion') {
                $branch[] = array(
                    'label'     => _("Offers categories").' '.$state['store']->get('Code'),
                    'icon'      => 'sitemap',
                    'reference' => 'offers/'.$state['store']->id.'/categories'
                );

                $branch[] = array(
                    'label'     => '<span class="Deal_Campaign_Name">'.$state['_object']->get('Name').'</span>',
                    'icon'      => 'tags',
                    'reference' => ''
                );


            } elseif ($state['section'] == 'campaign') {
                $branch[] = array(
                    'label'     => _("Offers categories").' '.$state['store']->get('Code'),
                    'icon'      => 'sitemap',
                    'reference' => 'offers/'.$state['store']->id.'/categories'
                );

                $branch[] = array(
                    'label'     => '<span class="Deal_Campaign_Name">'.$state['_object']->get('Name').'</span>',
                    'html_icon' => $state['_object']->get('Icon'),
                    'reference' => ''
                );


            } elseif ($state['section'] == 'campaign.new') {
                $branch[] = array(
                    'label'     => _("Offers categories").' '.$state['store']->get('Code'),
                    'icon'      => 'sitemap',
                    'reference' => 'offers/'.$state['store']->id.'/categories'
                );

                $branch[] = array(
                    'label'     => _('New campaign'),
                    'icon'      => '',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'deal.new') {


                if ($state['parent'] == 'campaign') {

                    include_once 'class.Store.php';
                    $state['store'] = new Store($state['_parent']->get('Store Key'));

                    $branch[] = array(
                        'label'     => _('Offers').' <span class="Store_Code">'.$state['store']->get('Code').'</span>',
                        'icon'      => 'tags',
                        'reference' => 'offers/'.$state['store']->id
                    );

                    $branch[] = array(
                        'label'     => '<span class="Deal_Campaign_Name">'.$state['_parent']->get('Name').'</span>',
                        'html_icon' => $state['_parent']->get('Icon'),
                        'reference' => 'offers/'.$state['store']->id.'/'.strtolower($state['_parent']->get('Code'))
                    );


                    switch ($state['_parent']->get('Code')) {
                        case 'VO':
                            $branch[] = array(
                                'label'     => _('New voucher'),
                                'icon'      => '',
                                'reference' => ''
                            );
                            break;
                        default:
                            $branch[] = array(
                                'label'     => _('New offer'),
                                'icon'      => '',
                                'reference' => ''
                            );

                    }


                } else {

                    $branch[] = array(
                        'label'     => _('New offer'),
                        'icon'      => '',
                        'reference' => ''
                    );

                }


            } elseif ($state['section'] == 'deal') {


                $branch[] = array(
                    'label'     => _('Offers').' <span class="Store_Code">'.$state['store']->get('Code').'</span>',
                    'icon'      => 'tags',
                    'reference' => 'offers/'.$state['store']->id
                );

                $branch[] = array(
                    'label'     => '<span class="Deal_Campaign_Name">'.$state['_parent']->get('Name').'</span>',
                    'icon'      => 'tags',
                    'reference' => 'offers/'.$state['store']->id.'/'.strtolower($state['_parent']->get('Code'))
                );


                $branch[] = array(
                    'label'     => '<span class="Deal_Name">'.$state['_object']->get('Name').'</span>',
                    'icon'      => 'tag',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'deal_component') {


                $branch[] = array(
                    'label'     => _("Offers categories").' '.$state['store']->get('Code'),
                    'icon'      => 'sitemap',
                    'reference' => 'offers/'.$state['store']->id.'/categories'
                );

                $branch[] = array(
                    'label'     => $state['_parent']->get('Name'),
                    'html_icon' => $state['_parent']->get('Icon'),
                    'reference' => 'offers/'.$state['store']->id.'/'.strtolower($state['_parent']->get('Code'))
                );


                if ($state['_parent']->get('Code') == 'OR') {
                    $branch[] = array(
                        'label'     => '<span class="Deal_Component_Name_Label">'.$state['_object']->get('Deal Component Allowance Target Label').'</span>',
                        'icon'      => 'tag',
                        'reference' => ''
                    );
                } else {
                    $branch[] = array(
                        'label'     => '<span class="Deal_Component_Name_Label">'.$state['_object']->get('Name Label').'</span>',
                        'icon'      => 'tag',
                        'reference' => ''
                    );
                }


            }

            break;


        case 'agent_suppliers':


            if ($state['section'] == 'suppliers') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => 'hand-holding-box',
                    'reference' => 'suppliers'
                );
            } elseif ($state['section'] == 'settings') {
                $branch[] = array(
                    'label'     => _("Suppliers' settings"),
                    'icon'      => 'sliders',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'orders') {
                $branch[] = array(
                    'label'     => _('Purchase orders'),
                    'icon'      => 'clipboard',
                    'reference' => 'suppliers.orders'
                );
            } elseif ($state['section'] == 'deliveries') {
                $branch[] = array(
                    'label'     => _('Deliveries'),
                    'icon'      => 'truck',
                    'reference' => 'suppliers.deliveries'
                );
            } elseif ($state['section'] == 'agents') {
                $branch[] = array(
                    'label'     => _('Agents'),
                    'icon'      => 'user-secret',
                    'reference' => 'agents'
                );
            } elseif ($state['section'] == 'supplier') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="id Supplier_Code">'.$state['_object']->get('Code').'</span> <span class="Supplier_Name italic">'.$state['_object']->get('Name').'</span>',
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['key']
                );

            } elseif ($state['section'] == 'supplier.attachment.new') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="id Supplier_Code">'.$state['_parent']->get('Code').'</span>',
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['parent_key']
                );
                $branch[] = array(
                    'label'     => _('Upload attachment'),
                    'icon'      => 'paperclip',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'supplier.attachment') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="id Supplier_Code">'.$state['_parent']->get('Code').'</span>',
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['parent_key']
                );
                $branch[] = array(
                    'label'     => '<span class="id Attachment_Caption">'.$state['_object']->get('Caption').'</span>',
                    'icon'      => 'paperclip',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'supplier.new') {

                if ($state['parent'] == 'agent') {
                    $branch[] = array(
                        'label'     => _('Agents'),
                        'icon'      => '',
                        'reference' => 'agents'
                    );
                    $branch[] = array(
                        'label'     => '<span class="Agent_Code">'.$state['_parent']->get('Code').'</span>',
                        'icon'      => 'user-secret',
                        'reference' => 'agent/'.$state['parent_key']
                    );

                } else {
                    $branch[] = array(
                        'label'     => _('Suppliers'),
                        'icon'      => '',
                        'reference' => 'suppliers'
                    );

                }

                $branch[] = array(
                    'label'     => _('New supplier'),
                    'icon'      => 'hand-holding-box',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'order' or $state['section'] == 'deleted_order') {

                if ($state['parent'] == 'account') {
                    $branch[] = array(
                        'label'     => _('Purchase orders'),
                        'icon'      => '',
                        'reference' => 'suppliers/orders'
                    );
                } elseif ($state['parent'] == 'supplier') {
                    $branch[] = array(
                        'label'     => _('Suppliers'),
                        'icon'      => '',
                        'reference' => 'suppliers'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Supplier_Code">'.$state['_parent']->get('Code'),
                        'icon'      => 'hand-holding-box',
                        'reference' => 'supplier/'.$state['parent_key']
                    );
                } elseif ($state['parent'] == 'agent') {
                    $branch[] = array(
                        'label'     => _('Agents'),
                        'icon'      => '',
                        'reference' => 'agents'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Agent_Code">'.$state['_parent']->get('Code'),
                        'icon'      => 'user-secret',
                        'reference' => 'agent/'.$state['parent_key']
                    );
                }
                $branch[] = array(
                    'label'     => '<span class="Purchase_Order_Public_ID">'.$state['_object']->get('Public ID').'</span>',
                    'icon'      => 'clipboard',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'delivery') {

                if ($state['parent'] == 'account') {
                    $branch[] = array(
                        'label'     => _('Deliveries'),
                        'icon'      => '',
                        'reference' => 'suppliers/deliveries'
                    );
                } elseif ($state['parent'] == 'supplier') {
                    $branch[] = array(
                        'label'     => _('Suppliers'),
                        'icon'      => '',
                        'reference' => 'suppliers'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Supplier_Code">'.$state['_parent']->get('Code'),
                        'icon'      => 'hand-holding-box',
                        'reference' => 'supplier/'.$state['parent_key']
                    );
                } elseif ($state['parent'] == 'agent') {
                    $branch[] = array(
                        'label'     => _('Agents'),
                        'icon'      => '',
                        'reference' => 'agents'
                    );
                    $branch[] = array(
                        'label'     => '<span class="id Agent_Code">'.$state['_parent']->get('Code'),
                        'icon'      => 'user-secret',
                        'reference' => 'agent/'.$state['parent_key']
                    );
                }
                $branch[] = array(
                    'label'     => '<span class="Supplier_Delivery_Public_ID">'.$state['_object']->get('Public ID').'</span>',
                    'icon'      => 'truck',
                    'reference' => ''
                );
            } elseif ($state['section'] == 'supplier.order.item') {

                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="id Supplier_Code">'.$state['_parent']->get('Parent Code'),
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['_parent']->get(
                            'Purchase Order Parent Key'
                        )
                );
                $branch[] = array(
                    'label'     => '<span class="Purchase_Order_Public_ID">'.$state['_parent']->get('Public ID').'</span>',
                    'icon'      => 'clipboard',
                    'reference' => 'supplier/'.$state['_parent']->get(
                            'Purchase Order Parent Key'
                        ).'/order/'.$state['parent_key']
                );


                $branch[] = array(
                    'label'     => '<span class="Supplier_Part_Reference">'.$state['_object']->get('Reference').'</span>',
                    'icon'      => 'bars',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'agent.order.item') {


                $branch[] = array(
                    'label'     => _('Agents'),
                    'icon'      => '',
                    'reference' => 'agents'
                );
                $branch[] = array(
                    'label'     => '<span class="id Agent_Code">'.$state['_parent']->get('Parent Code'),
                    'icon'      => 'user-secret',
                    'reference' => 'agent/'.$state['_parent']->get(
                            'Purchase Order Parent Key'
                        )
                );
                $branch[] = array(
                    'label'     => '<span class="Purchase_Order_Public_ID">'.$state['_parent']->get('Public ID').'</span>',
                    'icon'      => 'clipboard',
                    'reference' => 'supplier/'.$state['_parent']->get(
                            'Purchase Order Parent Key'
                        ).'/order/'.$state['parent_key']
                );


                $branch[] = array(
                    'label'     => '<span class="Supplier_Part_Reference">'.$state['_object']->get('Reference').'</span>',
                    'icon'      => 'bars',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'supplier_part') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="Supplier_Code">'.$state['_parent']->get('Code').'</span>',
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['_parent']->id
                );
                $branch[] = array(
                    'label'     => '<span class="Supplier_Part_Reference">'.$state['_object']->get('Reference').'</span>',
                    'icon'      => 'stop',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'supplier_part.new') {
                $branch[] = array(
                    'label'     => _('Suppliers'),
                    'icon'      => '',
                    'reference' => 'suppliers'
                );
                $branch[] = array(
                    'label'     => '<span class="Supplier_Code">'.$state['_parent']->get('Code').'</span>',
                    'icon'      => 'hand-holding-box',
                    'reference' => 'supplier/'.$state['_parent']->id
                );
                $branch[] = array(
                    'label'     => _("New supplier's product"),
                    'icon'      => 'stop',
                    'reference' => ''
                );

            } elseif ($state['section'] == 'agent.user.new') {
                $branch[] = array(
                    'label'     => _('Agents'),
                    'icon'      => '',
                    'reference' => 'agents'
                );
                $branch[] = array(
                    'label'     => '<span class="Agent_Code">'.$state['_parent']->get('Code').'</span> <span class="Agent_Name italic">'.$state['_parent']->get('Name').'</span>',
                    'icon'      => 'user-secret',
                    'reference' => 'agent/'.$state['parent_key']
                );
                $branch[] = array(
                    'label'     => _('New system user'),
                    'icon'      => 'terminal',
                    'reference' => ''
                );

            }


            break;

        case 'agent_client_orders':

            switch ($state['section']) {
                case 'orders':
                    $branch[] = array(
                        'label'     => _("Client's orders"),
                        'icon'      => 'clipboard',
                        'reference' => 'agents'
                    );
                    break;
                case 'client_order':
                    $branch[] = array(
                        'label'     => _("Client's orders"),
                        'icon'      => '',
                        'reference' => 'orders'
                    );
                    $branch[] = array(
                        'label'     => '<class ="Purchase_Order_Public_ID">'.$state['_object']->get('Public ID').'</span>',
                        'icon'      => 'clipboard',
                        'reference' => 'orders'
                    );
                    break;
                case 'agent_supplier_order':
                    $branch[] = array(
                        'label'     => _("Client's orders"),
                        'icon'      => '',
                        'reference' => 'orders'
                    );
                    $branch[] = array(
                        'label'     => '<class ="Purchase_Order_Public_ID">'.$state['_parent']->get('Public ID').'</span>',
                        'icon'      => 'clipboard',
                        'reference' => 'client_order/'.$state['_parent']->id
                    );
                    $branch[] = array(
                        'label'     => '<class ="Agent_Supplier_Purchase_Order_Public_ID">'.$state['_object']->get('Public ID').'</span>',
                        'icon'      => 'paste',
                        'reference' => ''
                    );

            }

            break;

        case 'agent_client_deliveries':


            switch ($state['section']) {
                case 'deliveries':
                    $branch[] = array(
                        'label'     => _("Deliveries"),
                        'icon'      => 'truck-container',
                        'reference' => 'agent_deliveries'
                    );
                    break;
                case 'agent_delivery':
                    $branch[] = array(
                        'label'     => _("Deliveries"),
                        'icon'      => '',
                        'reference' => 'agent_deliveries'
                    );
                    $branch[] = array(
                        'label'     => '<class ="Supplier_Delivery_Public_ID">'.$state['_object']->get('Public ID').'</span>',
                        'icon'      => 'truck-container',
                        'reference' => ''
                    );
                    break;


            }

            break;
        case 'agent_parts':

            switch ($state['section']) {
                case 'parts':
                    $branch[] = array(
                        'label'     => _("Products"),
                        'icon'      => 'stop',
                        'reference' => ''
                    );
                    break;


            }

            break;

        default:


    }

    $_content = array(
        'branch' => $branch,

    );
    $smarty->assign('_content', $_content);


    $html = $smarty->fetch('view_position.tpl');

    return array(
        $state,
        $html
    );


}