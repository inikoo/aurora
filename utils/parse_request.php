<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 August 2015 23:49:27 GMT+8 Singapore
 Moved: 3 October 2015 at 08:57:36 BST Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/


function parse_request($_data, $db, $modules, $account = '', $user = '', $is_setup = false) {


    $request = $_data['request'];


    $request = preg_replace('/\/+/', '/', $request);
    if ($request == '/' or $request == '') {
        $request = 'dashboard';
    }


    $original_request = preg_replace('/^\//', '', $request);

    $view_path = preg_split('/\//', $original_request);


    $view_path = array_filter($view_path);

    $module     = 'utils';
    $section    = 'not_found';
    $tab        = 'not_found';
    $tab_parent = '';
    $subtab     = '';
    $parent     = 'account';
    $parent_key = 1;
    $object     = '';
    $key        = '';
    $extra      = '';
    $extra_tab  = '';
    $title      = '';

    $count_view_path = count($view_path);
    $shortcut        = false;
    $is_main_section = false;

    reset($modules);


    if ($count_view_path > 0) {
        $root            = array_shift($view_path);
        $count_view_path = count($view_path);
        switch ($root) {
            case 'index.php':
            case 'dashboard':
                $module  = 'dashboard';
                $section = 'dashboard';
                break;
            case 'stores':
                if (!$user->can_view('stores')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module  = 'products_server';
                $section = 'stores';
                $object  = 'account';
                $key     = 1;


                break;
            case 'store':

                if (!$user->can_view('stores')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }


                $module  = 'products';
                $section = 'store';
                $object  = 'store';

                if (isset($view_path[0])) {

                    if (is_numeric($view_path[0])) {
                        $key = $view_path[0];
                        if (isset($view_path[1])) {
                            if ($view_path[1] == 'dashboard') {
                                $section = 'dashboard';
                            } elseif ($view_path[1] == 'settings') {
                                $section = 'settings';
                            } /*
                            elseif ($view_path[1] == 'website') {


                                if (!$user->can_view('sites')) {
                                    $module  = 'utils';
                                    $section = 'forbidden';
                                    break;
                                }

                                $module  = 'products';
                                $section = 'website';


                                $parent     = 'store';
                                $parent_key = $key;

                                $object = 'website';
                                $key    = '';


                                if (isset($view_path[2])) {
                                    if ($view_path[1] == 'page') {
                                        $section    = 'page';
                                        $object     = 'page';
                                        $parent     = 'website';
                                        $parent_key = $key;

                                        if (is_numeric($view_path[3])) {
                                            $key = $view_path[3];
                                        }


                                    } elseif ($view_path[2] == 'user') {
                                        $section    = 'website.user';
                                        $object     = 'user';
                                        $parent     = 'website';
                                        $parent_key = $key;

                                        if (is_numeric($view_path[3])) {
                                            $key = $view_path[3];
                                        }


                                    } elseif ($view_path[2] == 'new') {
                                        $section = 'website.new';
                                        $object  = 'website';


                                    }

                                }


                            }
                            */ elseif ($view_path[1] == 'charge') {
                                $section = 'charge';
                                $object  = 'charge';

                                $parent     = 'store';
                                $parent_key = $key;
                                if (is_numeric($view_path[2])) {
                                    $key = $view_path[2];
                                } elseif ($view_path[2] == 'new') {
                                    $section = 'charge.new';
                                    $key     = 0;
                                }
                            } elseif ($view_path[1] == 'shipping_zone_schema') {


                                $section = 'shipping_zone_schema';
                                $object  = 'shipping_zone_schema';

                                $parent     = 'store';
                                $parent_key = $key;


                                if (is_numeric($view_path[2])) {
                                    $key = $view_path[2];
                                } elseif ($view_path[2] == 'new') {

                                    $section = 'shipping_zone_schema.new';
                                }

                            } elseif ($view_path[1] == 'shipping_zone') {


                                $section = 'shipping_zone';
                                $object  = 'shipping_zone';

                                $parent     = 'store';
                                $parent_key = $key;


                                if (is_numeric($view_path[2])) {
                                    $key = $view_path[2];
                                } elseif ($view_path[2] == 'new') {

                                    $section = 'shipping_zone.new';
                                }

                            } elseif ($view_path[1] == 'notifications') {


                                if (isset($view_path[2])) {

                                    $section    = 'user_notifications';
                                    $parent     = 'store';
                                    $parent_key = $key;
                                    if (is_numeric($view_path[2])) {
                                        $section = 'email_campaign_type';
                                        $object  = 'email_campaign_type';
                                        $key     = $view_path[2];

                                        if (isset($view_path[3])) {
                                            if ($view_path[3] == 'tracking') {


                                                $section = 'email_tracking';

                                                $parent     = 'email_campaign_type';
                                                $parent_key = $key;

                                                if (is_numeric($view_path[4])) {
                                                    $section = 'email_tracking';
                                                    $object  = 'email_tracking';
                                                    $key     = $view_path[4];


                                                }

                                            }
                                        }


                                    }
                                }

                            }

                        }

                    } elseif ($view_path[0] == 'new') {
                        $module  = 'products_server';
                        $section = 'store.new';
                        $object  = '';


                    } elseif ($view_path[0] == 'product') {
                        $module     = 'products';
                        $section    = 'product';
                        $object     = 'product';
                        $parent     = 'store';
                        $parent_key = $key;

                        if (is_numeric($view_path[1])) {
                            $key = $view_path[1];
                        }


                    }


                } else {
                    if ($user->data['User Hooked Store Key'] and in_array(
                            $user->data['User Hooked Store Key'], $user->stores
                        )) {
                        $key = $user->data['User Hooked Store Key'];
                    } else {
                        $_tmp = $user->stores;
                        $key  = array_shift($_tmp);
                    }
                }

                break;

            case 'charge':
                if (!$user->can_view('stores')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module  = 'products';
                $section = 'store';

                if (isset($view_path[0])) {
                    if (is_numeric($view_path[0])) {


                        $section    = 'charge';
                        $object     = 'charge';
                        $key        = $view_path[0];
                        $parent     = 'store';
                        $parent_key = '';

                        if (isset($view_path[1])) {

                            if ($view_path[1] == 'order') {
                                $object  = 'order';
                                $section = 'order';


                                $parent     = 'charge';
                                $parent_key = $key;
                                if (isset($view_path[2])) {
                                    $key = $view_path[2];

                                }


                            }


                        }
                    }


                }
                break;
            case 'shipping_zone':
                if (!$user->can_view('stores')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module  = 'products';
                $section = 'store';

                if (isset($view_path[0])) {
                    if (is_numeric($view_path[0])) {


                        $section    = 'shipping_zone';
                        $object     = 'shipping_zone';
                        $key        = $view_path[0];
                        $parent     = 'store';
                        $parent_key = '';

                        if (isset($view_path[1])) {

                            if ($view_path[1] == 'order') {
                                $object  = 'order';
                                $section = 'order';


                                $parent     = 'shipping_zone';
                                $parent_key = $key;
                                if (isset($view_path[2])) {
                                    $key = $view_path[2];

                                }


                            }


                        }

                    } elseif ($view_path[0] == 'new') {
                        $section = 'shipping_zone.new';
                    }


                }
                break;

            case 'product':
                if (!$user->can_view('stores')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module  = 'products';
                $section = 'products';

                if (isset($view_path[0])) {
                    if (is_numeric($view_path[0])) {


                        $section    = 'product';
                        $object     = 'product';
                        $key        = $view_path[0];
                        $parent     = 'store';
                        $parent_key = '';


                        if (isset($view_path[1])) {

                            if ($view_path[1] == 'new') {
                                $object     = 'product';
                                $key        = 0;
                                $section    = 'product.new';
                                $parent     = 'store';
                                $parent_key = $view_path[0];


                            } elseif ($view_path[1] == 'categories') {
                                $object     = 'store';
                                $key        = $view_path[0];
                                $section    = 'categories';
                                $parent     = 'store';
                                $parent_key = $view_path[0];
                            } else {
                                if ($view_path[1] == 'category') {
                                    $section = 'category';
                                    $object  = 'category';

                                    if (isset($view_path[2])) {

                                        $view_path[2] = preg_replace(
                                            '/\>$/', '', $view_path[2]
                                        );
                                        if (preg_match(
                                            '/^(\d+\>)+(\d+)$/', $view_path[2]
                                        )) {

                                            $parent_categories = preg_split(
                                                '/\>/', $view_path[2]
                                            );
                                            $metadata          = $parent_categories;
                                            $key               = array_pop(
                                                $parent_categories
                                            );

                                            $parent = 'category';


                                            $parent_key = array_pop(
                                                $parent_categories
                                            );


                                            if (isset($view_path[3])) {

                                                if ($view_path[3] == 'product') {

                                                    $parent_key = $key;

                                                    $section = 'product';
                                                    $object  = 'product';
                                                    if (isset($view_path[4]) and is_numeric(
                                                            $view_path[4]
                                                        )) {

                                                        $key = $view_path[4];

                                                    }

                                                }


                                            }

                                        } elseif (is_numeric($view_path[2])) {
                                            $key = $view_path[2];
                                            include_once 'class.Category.php';
                                            $category = new Category($key);
                                            if ($category->get(
                                                    'Category Branch Type'
                                                ) == 'Root') {
                                                $parent     = 'store';
                                                $parent_key = $category->get(
                                                    'Category Store Key'
                                                );
                                            } else {
                                                $parent     = 'category';
                                                $parent_key = $category->get(
                                                    'Category Parent Key'
                                                );

                                            }


                                            if (isset($view_path[3])) {


                                                if (is_numeric($view_path[3])) {
                                                    $section    = 'product';
                                                    $parent     = 'category';
                                                    $parent_key = $category->id;
                                                    $object     = 'product';
                                                    $key        = $view_path[3];
                                                } elseif ($view_path[3] == 'product') {
                                                    $section = 'product';
                                                    $object  = 'product';
                                                    if (isset($view_path[4]) and is_numeric(
                                                            $view_path[4]
                                                        )) {

                                                        $key = $view_path[4];

                                                    }

                                                } elseif ($view_path[3] == 'upload') {
                                                    //$module='account';
                                                    $section    = 'upload';
                                                    $parent     = 'category';
                                                    $parent_key = $key;
                                                    $object     = 'upload';
                                                    if (isset($view_path[4])) {
                                                        if (is_numeric(
                                                            $view_path[4]
                                                        )) {

                                                            $key = $view_path[4];
                                                        }
                                                    }

                                                }

                                            }


                                        } elseif ($view_path[2] == 'new') {

                                            $section = 'main_category.new';

                                        }
                                    } else {
                                        //error
                                    }

                                } else {

                                    if ($view_path[1] == 'order') {

                                        if (isset($view_path[2])) {

                                            if (is_numeric($view_path[2])) {
                                                $section    = 'order';
                                                $object     = 'order';
                                                $parent_key = $key;
                                                $key        = $view_path[2];
                                                $parent     = 'product';

                                            }

                                        }

                                    }

                                }
                            }


                        }
                    }


                }
                break;


            case 'products':
                if (!$user->can_view('stores')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module  = 'products';
                $section = 'products';

                if (isset($view_path[0])) {
                    if (is_numeric($view_path[0])) {
                        $parent     = 'store';
                        $parent_key = $view_path[0];
                        if (isset($view_path[1])) {

                            if ($view_path[1] == 'new') {
                                $object     = 'product';
                                $key        = 0;
                                $section    = 'product.new';
                                $parent     = 'store';
                                $parent_key = $view_path[0];


                            } elseif ($view_path[1] == 'categories') {
                                $object     = 'store';
                                $key        = $view_path[0];
                                $section    = 'categories';
                                $parent     = 'store';
                                $parent_key = $view_path[0];
                            } else {
                                if ($view_path[1] == 'category') {
                                    $section = 'category';
                                    $object  = 'category';

                                    if (isset($view_path[2])) {

                                        $view_path[2] = preg_replace(
                                            '/\>$/', '', $view_path[2]
                                        );
                                        if (preg_match(
                                            '/^(\d+\>)+(\d+)$/', $view_path[2]
                                        )) {

                                            $parent_categories = preg_split(
                                                '/\>/', $view_path[2]
                                            );
                                            $metadata          = $parent_categories;
                                            $key               = array_pop(
                                                $parent_categories
                                            );

                                            $parent = 'category';


                                            $parent_key = array_pop(
                                                $parent_categories
                                            );


                                            if (isset($view_path[3])) {

                                                if ($view_path[3] == 'product') {

                                                    $parent_key = $key;

                                                    $section = 'product';
                                                    $object  = 'product';
                                                    if (isset($view_path[4]) and is_numeric(
                                                            $view_path[4]
                                                        )) {

                                                        $key = $view_path[4];

                                                    }

                                                }


                                            }

                                        } elseif (is_numeric($view_path[2])) {
                                            $key = $view_path[2];
                                            include_once 'class.Category.php';
                                            $category = new Category($key);
                                            if ($category->get(
                                                    'Category Branch Type'
                                                ) == 'Root') {
                                                $parent     = 'store';
                                                $parent_key = $category->get(
                                                    'Category Store Key'
                                                );
                                            } else {
                                                $parent     = 'category';
                                                $parent_key = $category->get(
                                                    'Category Parent Key'
                                                );

                                            }


                                            if (isset($view_path[3])) {


                                                if (is_numeric($view_path[3])) {
                                                    $section    = 'product';
                                                    $parent     = 'category';
                                                    $parent_key = $category->id;
                                                    $object     = 'product';
                                                    $key        = $view_path[3];
                                                } elseif ($view_path[3] == 'product') {
                                                    $section = 'product';
                                                    $object  = 'product';
                                                    if (isset($view_path[4]) and is_numeric(
                                                            $view_path[4]
                                                        )) {

                                                        $key = $view_path[4];

                                                    }


                                                } elseif ($view_path[3] == 'deal_component') {


                                                    $section    = 'deal_component';
                                                    $object     = 'deal_component';
                                                    $parent     = 'category';
                                                    $parent_key = $category->id;


                                                    if (isset($view_path[4])) {
                                                        if (is_numeric($view_path[4])) {
                                                            $key = $view_path[4];
                                                        } elseif ($view_path[4] == 'new') {


                                                            $key     = 0;
                                                            $section = 'deal_component.new';

                                                        }

                                                    }


                                                } elseif ($view_path[3] == 'upload') {
                                                    //$module='account';
                                                    $section    = 'upload';
                                                    $parent     = 'category';
                                                    $parent_key = $key;
                                                    $object     = 'upload';
                                                    if (isset($view_path[4])) {
                                                        if (is_numeric(
                                                            $view_path[4]
                                                        )) {

                                                            $key = $view_path[4];
                                                        }
                                                    }

                                                }

                                            }


                                        } elseif ($view_path[2] == 'new') {

                                            $section = 'main_category.new';

                                        }
                                    } else {
                                        //error
                                    }

                                } else {
                                    if (is_numeric($view_path[1])) {
                                        $section    = 'product';
                                        $object     = 'product';
                                        $key        = $view_path[1];
                                        $parent     = 'store';
                                        $parent_key = $view_path[0];


                                    }
                                }
                            }


                        }
                    } elseif ($view_path[0] == 'all') {
                        $module  = 'products_server';
                        $section = 'products';


                    }


                }
                break;


            case 'services':
                if (!$user->can_view('stores')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module  = 'products';
                $section = 'services';

                if (isset($view_path[0])) {
                    if (is_numeric($view_path[0])) {
                        $parent     = 'store';
                        $parent_key = $view_path[0];
                        if (isset($view_path[1])) {

                            if ($view_path[1] == 'new') {
                                $object     = 'service';
                                $key        = 0;
                                $section    = 'service.new';
                                $parent     = 'store';
                                $parent_key = $view_path[0];


                            } elseif ($view_path[1] == 'categories') {
                                $object     = 'store';
                                $key        = $view_path[0];
                                $section    = 'categories';
                                $parent     = 'store';
                                $parent_key = $view_path[0];
                            } else {
                                if ($view_path[1] == 'category') {
                                    $section = 'category';
                                    $object  = 'category';

                                    if (isset($view_path[2])) {

                                        $view_path[2] = preg_replace(
                                            '/\>$/', '', $view_path[2]
                                        );
                                        if (preg_match(
                                            '/^(\d+\>)+(\d+)$/', $view_path[2]
                                        )) {

                                            $parent_categories = preg_split(
                                                '/\>/', $view_path[2]
                                            );
                                            $metadata          = $parent_categories;
                                            $key               = array_pop(
                                                $parent_categories
                                            );

                                            $parent = 'category';


                                            $parent_key = array_pop(
                                                $parent_categories
                                            );


                                            if (isset($view_path[3])) {

                                                if ($view_path[3] == 'service') {

                                                    $parent_key = $key;

                                                    $section = 'service';
                                                    $object  = 'service';
                                                    if (isset($view_path[4]) and is_numeric(
                                                            $view_path[4]
                                                        )) {

                                                        $key = $view_path[4];

                                                    }

                                                }


                                            }

                                        } elseif (is_numeric($view_path[2])) {
                                            $key = $view_path[2];
                                            include_once 'class.Category.php';
                                            $category = new Category($key);
                                            if ($category->get(
                                                    'Category Branch Type'
                                                ) == 'Root') {
                                                $parent     = 'store';
                                                $parent_key = $category->get(
                                                    'Category Store Key'
                                                );
                                            } else {
                                                $parent     = 'category';
                                                $parent_key = $category->get(
                                                    'Category Parent Key'
                                                );

                                            }


                                            if (isset($view_path[3])) {


                                                if (is_numeric($view_path[3])) {
                                                    $section    = 'service';
                                                    $parent     = 'category';
                                                    $parent_key = $category->id;
                                                    $object     = 'service';
                                                    $key        = $view_path[3];
                                                } elseif ($view_path[3] == 'service') {
                                                    $section = 'service';
                                                    $object  = 'service';
                                                    if (isset($view_path[4]) and is_numeric(
                                                            $view_path[4]
                                                        )) {

                                                        $key = $view_path[4];

                                                    }

                                                } elseif ($view_path[3] == 'upload') {
                                                    //$module='account';
                                                    $section    = 'upload';
                                                    $parent     = 'category';
                                                    $parent_key = $key;
                                                    $object     = 'upload';
                                                    if (isset($view_path[4])) {
                                                        if (is_numeric(
                                                            $view_path[4]
                                                        )) {

                                                            $key = $view_path[4];
                                                        }
                                                    }

                                                }

                                            }


                                        } elseif ($view_path[2] == 'new') {

                                            $section = 'main_category.new';

                                        }
                                    } else {
                                        //error
                                    }

                                } else {
                                    if (is_numeric($view_path[1])) {
                                        $section    = 'service';
                                        $object     = 'service';
                                        $key        = $view_path[1];
                                        $parent     = 'store';
                                        $parent_key = $view_path[0];
                                    }
                                }
                            }


                        }
                    } elseif ($view_path[0] == 'all') {
                        $module  = 'services_server';
                        $section = 'services';


                    }


                }
                break;

            case 'category':
                $object = 'category';

                if (isset($view_path[0]) and is_numeric($view_path[0])) {
                    $key = $view_path[0];
                    include_once 'class.Category.php';
                    $category = new Category($key);

                    $parent     = 'category';
                    $parent_key = $category->get('Category Parent Key');


                    switch ($category->get('Category Scope')) {
                        case 'Customer':
                            $module  = 'customers';
                            $section = 'category';

                            if ($category->get('Category Branch Type') == 'Root') {
                                $parent     = 'store';
                                $parent_key = $category->get(
                                    'Category Store Key'
                                );
                            }
                        case 'Product':
                            $module  = 'products';
                            $section = 'category';

                            if ($category->get('Category Branch Type') == 'Root') {
                                $parent     = 'store';
                                $parent_key = $category->get(
                                    'Category Store Key'
                                );
                            } else {
                                $parent     = 'category';
                                $parent_key = $category->get(
                                    'Category Parent Key'
                                );

                            }
                            break;
                        case 'Invoice':
                            $module  = 'orders_server';
                            $section = 'category';

                            if ($category->get('Category Branch Type') == 'Root') {
                                $parent     = 'account';
                                $parent_key = 1;
                            }
                            break;
                        case 'Part':
                            $module  = 'inventory';
                            $section = 'category';

                            if ($category->get('Category Branch Type') == 'Root') {
                                $parent     = 'account';
                                $parent_key = 1;
                            }

                            if (isset($view_path[1])) {
                                if ($view_path[1] == 'part') {
                                    $section    = 'part';
                                    $object     = 'part';
                                    $parent     = 'category';
                                    $parent_key = $view_path[0];
                                    if (isset($view_path[2])) {
                                        if (is_numeric($view_path[2])) {
                                            $key = $view_path[2];
                                        }
                                    }
                                } elseif ($view_path[1] == 'upload') {
                                    //$module='account';
                                    $section    = 'upload';
                                    $parent     = 'category';
                                    $parent_key = $key;
                                    $object     = 'upload';
                                    if (isset($view_path[2])) {
                                        if (is_numeric($view_path[2])) {

                                            $key = $view_path[2];
                                        }
                                    }

                                }
                            }


                            break;
                        case 'Supplier':
                            $module  = 'suppliers';
                            $section = 'category';

                            if ($category->get('Category Branch Type') == 'Root') {
                                $parent     = 'account';
                                $parent_key = 1;
                            }
                            if (isset($view_path[1])) {
                                if ($view_path[1] == 'supplier') {
                                    $section    = 'supplier';
                                    $object     = 'supplier';
                                    $parent     = 'category';
                                    $parent_key = $view_path[0];
                                    if (isset($view_path[2])) {
                                        if (is_numeric($view_path[2])) {
                                            $key = $view_path[2];
                                        }
                                    }
                                }
                            }
                            break;
                        default:

                            print_r($category);

                            exit(
                                'error category '.$category->get(
                                    'Category Subject'
                                ).' not set up in parse_request.php'
                            );
                            break;
                    }

                } else {
                    //error
                }


                break;
            case 'websites':
                if (!$user->can_view('sites')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }
                $module  = 'websites_server';
                $section = 'websites';

                break;
            case 'page':
                if (!$user->can_view('sites')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module  = 'websites';
                $section = 'page';
                $object  = 'page';
                $parent  = 'website';
                $key     = $view_path[0];


                if (isset($view_path[0])) {
                    if (is_numeric($view_path[1])) {
                        $key = $view_path[0];
                    }

                    if (isset($view_path[1])) {
                        if ($view_path[1] == 'version') {

                            $section    = 'page_version';
                            $object     = 'page_version';
                            $parent     = 'page';
                            $parent_key = $key;
                            if (isset($view_path[2])) {
                                if (is_numeric($view_path[2])) {
                                    $key = $view_path[2];

                                }

                            }


                        }

                    }

                }


                break;


            case 'webpages':
                if (!$user->can_view('sites')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module  = 'websites';
                $section = 'webpages';
                $parent  = 'website';


                if (isset($view_path[0]) and is_numeric($view_path[0])) {
                    $parent_key = $view_path[0];

                }

                if (isset($view_path[1])) {

                    if ($view_path[1] == 'type') {

                        $section = 'webpage_type';
                        $object  = 'webpage_type';
                        if (isset($view_path[2]) and is_numeric($view_path[2])) {
                            $key = $view_path[2];

                            if (isset($view_path[3]) and in_array(
                                    $view_path[3], array(
                                                     'online',
                                                     'in_process',
                                                     'offline'
                                                 )
                                )) {

                                $parent     = 'webpage_type';
                                $parent_key = $key;

                                if (isset($view_path[4]) and is_numeric($view_path[4])) {

                                    $section = 'webpage';
                                    $object  = 'webpage';
                                    $key     = $view_path[4];


                                }


                            }

                        }


                    }

                }


                break;


            case 'website':
                if (!$user->can_view('sites')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module  = 'websites';
                $section = 'analytics';
                $object  = 'website';
                $key     = $view_path[0];

                if (isset($view_path[1])) {
                    if ($view_path[1] == 'webpage') {
                        $section    = 'webpage';
                        $object     = 'webpage';
                        $parent     = 'website';
                        $parent_key = $key;

                        if (is_numeric($view_path[2])) {
                            $key = $view_path[2];

                            if (isset($view_path[3])) {
                                if ($view_path[3] == 'asset') {
                                    $parent     = 'webpage';
                                    $parent_key = $view_path[2];
                                    if (isset($view_path[4])) {
                                        if (is_numeric($view_path[4])) {

                                            $key = $view_path[4];


                                        }
                                    }


                                }
                            }


                        } elseif ($view_path[2] == 'new') {
                            $section = 'webpage.new';
                        }


                    } elseif ($view_path[1] == 'settings') {
                        $section = 'settings';
                    } elseif ($view_path[1] == 'workshop') {
                        $section = 'workshop';
                    } elseif ($view_path[1] == 'users') {
                        $section = 'web_users';
                    } elseif ($view_path[1] == 'analytics') {
                        $section = 'analytics';
                    } elseif ($view_path[1] == 'online') {


                        if ($view_path[2] == 'webpage') {
                            $section    = 'webpage';
                            $object     = 'webpage';
                            $parent     = 'website';
                            $parent_key = $key;

                            if (is_numeric($view_path[3])) {
                                $key = $view_path[3];
                            }


                        }


                    } elseif ($view_path[1] == 'offline') {


                        if ($view_path[2] == 'webpage') {
                            $section    = 'webpage';
                            $object     = 'webpage';
                            $parent     = 'website';
                            $parent_key = $key;

                            if (is_numeric($view_path[3])) {
                                $key = $view_path[3];
                            }


                        }


                    } elseif ($view_path[1] == 'in_process') {


                        if ($view_path[2] == 'webpage') {
                            $section    = 'webpage';
                            $object     = 'webpage';
                            $parent     = 'website';
                            $parent_key = $key;

                            if (is_numeric($view_path[3])) {
                                $key = $view_path[3];
                            }


                        }


                    } elseif ($view_path[1] == 'ready') {


                        if ($view_path[2] == 'webpage') {
                            $section    = 'webpage';
                            $object     = 'webpage';
                            $parent     = 'website';
                            $parent_key = $key;

                            if (is_numeric($view_path[3])) {
                                $key = $view_path[3];
                            }


                        }


                    } elseif ($view_path[1] == 'user') {
                        $section    = 'website.user';
                        $object     = 'user';
                        $parent     = 'website';
                        $parent_key = $key;

                        if (is_numeric($view_path[2])) {
                            $key = $view_path[2];
                        }


                    } elseif ($view_path[1] == 'node') {
                        $section    = 'website.node';
                        $object     = 'node';
                        $parent     = 'website';
                        $parent_key = $key;

                        if (is_numeric($view_path[2])) {
                            $key = $view_path[2];
                        }


                    }

                }


                break;

            case 'webpage':
                if (!$user->can_view('sites')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module  = 'websites';
                $section = 'webpage';
                $object  = 'webpage';
                $parent  = 'website';


                $parent_key = '';


                if (isset($view_path[0])) {

                    $key        = $view_path[0];
                    $webpage    = get_object('Webpage', $key);
                    $parent_key = $webpage->get('Webpage Website Key');

                }

                break;

            case 'customer':
                if (!$user->can_view('customers')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module     = 'customers';
                $section    = 'customer';
                $object     = 'customer';
                $parent     = 'store';
                $parent_key = '';


                if (isset($view_path[0])) {

                    $key = $view_path[0];

                    if (isset($view_path[1])) {
                        if ($view_path[1] == 'order') {


                            $module     = 'orders';
                            $section    = 'order';
                            $parent     = 'customer';
                            $parent_key = $key;
                            $object     = 'order';
                            if (isset($view_path[2])) {
                                if (is_numeric($view_path[2])) {
                                    $key = $view_path[2];
                                }
                            }
                        }


                    }


                }


                break;
            case 'supplier':
                if (!($user->can_view('suppliers') or $user->get('User Type') == 'Agent')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }
                include_once 'utils/parse_request.supplier.inc.php';
                break;
            case 'agent':


                if (!($user->can_view('suppliers') or $user->get('User Type') == 'Agent')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module     = 'suppliers';
                $section    = 'agent';
                $parent     = 'account';
                $parent_key = 1;

                $object = 'agent';

                if (isset($view_path[0])) {
                    if (is_numeric($view_path[0])) {
                        $key = $view_path[0];

                        if ($user->get('User Type') == 'Agent' and $user->get('User Parent Key') != $key) {
                            $module  = 'utils';
                            $section = 'forbidden';
                            break;
                        }


                        if (isset($view_path[1])) {

                            if ($view_path[1] == 'part') {
                                $section    = 'agent_part';
                                $parent     = 'agent';
                                $parent_key = $key;
                                $object     = 'agent_part';
                                if (isset($view_path[2])) {
                                    if (is_numeric($view_path[2])) {

                                        $key = $view_path[2];
                                    } elseif ($view_path[2] == 'new') {
                                        $key     = 0;
                                        $section = 'agent_part.new';
                                    }
                                }


                            } elseif ($view_path[1] == 'supplier') {
                                $section    = 'supplier';
                                $parent     = 'agent';
                                $parent_key = $key;
                                $object     = 'supplier';
                                if (isset($view_path[2])) {
                                    if (is_numeric($view_path[2])) {

                                        $key = $view_path[2];
                                    } elseif ($view_path[2] == 'new') {
                                        $key     = 0;
                                        $section = 'supplier.new';
                                    }
                                }


                            } elseif ($view_path[1] == 'order') {
                                $section = 'order';

                                $parent     = 'agent';
                                $parent_key = $view_path[0];
                                $object     = 'purchase_order';

                                if (isset($view_path[2])) {
                                    if (is_numeric($view_path[2])) {
                                        $key = $view_path[2];
                                    }

                                    if (isset($view_path[3])) {
                                        if ($view_path[3] == 'item') {

                                            if (isset($view_path[4])) {
                                                if (is_numeric($view_path[4])) {

                                                    $parent     = 'PurchaseOrder';
                                                    $parent_key = $view_path[2];
                                                    $object     = 'PurchaseOrderItem';
                                                    $key        = $view_path[4];
                                                    $section    = 'agent.order.item';


                                                }
                                            }


                                        }
                                    }


                                }


                            } elseif ($view_path[1] == 'delivery') {
                                $section = 'delivery';

                                $parent     = 'Agent';
                                $parent_key = $view_path[0];
                                $object     = 'supplier_delivery';

                                if (isset($view_path[2])) {
                                    if (is_numeric($view_path[2])) {
                                        $key = $view_path[2];


                                    }


                                }

                            } else {
                                if ($view_path[1] == 'user') {


                                    $parent     = 'agent';
                                    $parent_key = $key;


                                    if (isset($view_path[2])) {
                                        if (is_numeric($view_path[2])) {

                                            $section = 'agent.user';
                                            $object  = 'user';
                                            $key     = $view_path[2];
                                        } elseif ($view_path[2] == 'new') {

                                            $section = 'agent.user.new';
                                            $object  = 'user';
                                        }
                                    }


                                }
                            }
                        }

                    }

                }


                break;

            case 'email_campaign_type':
                if (!$user->can_view('customers')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module = 'customers';


                if ($count_view_path == 0) {
                    $section = 'email_campaign_type';
                    $parent  = 'store';
                    if ($user->data['User Hooked Store Key'] and in_array(
                            $user->data['User Hooked Store Key'], $user->stores
                        )) {
                        $parent_key = $user->data['User Hooked Store Key'];
                    } else {
                        $_tmp       = $user->stores;
                        $parent_key = array_shift($_tmp);
                    }

                }
                $arg1 = array_shift($view_path);
                if (is_numeric($arg1)) {
                    $section    = 'email_campaign_type';
                    $parent     = 'store';
                    $parent_key = $arg1;


                    if (isset($view_path[0])) {

                        if (is_numeric($view_path[0])) {
                            $section = 'email_campaign_type';

                            $parent     = 'store';
                            $parent_key = $arg1;
                            $object     = 'email_campaign_type';
                            $key        = $view_path[0];


                            if (isset($view_path[1])) {


                                if ($view_path[1] == 'tracking') {

                                    $store_key = $arg1;

                                    $section = 'email_tracking';

                                    $parent     = 'email_campaign_type';
                                    $parent_key = $key;

                                    if (is_numeric($view_path[2])) {
                                        $section = 'email_tracking';
                                        $object  = 'email_tracking';
                                        $key     = $view_path[2];


                                    }

                                } elseif ($view_path[1] == 'mailshot') {

                                    $store_key = $arg1;

                                    $section = 'mailshot';

                                    $parent     = 'email_campaign_type';
                                    $parent_key = $key;

                                    if (is_numeric($view_path[2])) {
                                        $section = 'mailshot';
                                        $object  = 'mailshot';
                                        $key     = $view_path[2];
                                        if (isset($view_path[3])) {


                                            if ($view_path[3] == 'tracking') {


                                                $section = 'email_tracking';

                                                $parent     = 'mailshot';
                                                $parent_key = $key;

                                                if (is_numeric($view_path[4])) {
                                                    $section = 'email_tracking';
                                                    $object  = 'email_tracking';
                                                    $key     = $view_path[4];


                                                }

                                            }
                                        }


                                    } elseif ($view_path[2] == 'new') {
                                        $section = 'mailshot.new';
                                        $object  = 'mailshot';
                                        $key     = 0;
                                    }

                                }


                            }


                        } elseif ($view_path[0] == 'new') {
                            $section = 'prospect.new';
                            $object  = '';
                        } elseif ($view_path[0] == 'template') {


                            if (is_numeric($view_path[1])) {
                                $section = 'prospects.email_template';
                                $object  = 'email_template';
                                $key     = $view_path[1];


                            } elseif ($view_path[1] == 'new') {
                                $object  = 'email_template';
                                $key     = 0;
                                $section = 'prospects.template.new';

                            }


                        }

                    }

                }


                break;


            case 'prospects':
                if (!$user->can_view('customers')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module = 'customers';


                if ($count_view_path == 0) {
                    $section = 'prospects';
                    $parent  = 'store';
                    if ($user->data['User Hooked Store Key'] and in_array(
                            $user->data['User Hooked Store Key'], $user->stores
                        )) {
                        $parent_key = $user->data['User Hooked Store Key'];
                    } else {
                        $_tmp       = $user->stores;
                        $parent_key = array_shift($_tmp);
                    }

                }
                $arg1 = array_shift($view_path);
                if (is_numeric($arg1)) {
                    $section    = 'prospects';
                    $parent     = 'store';
                    $parent_key = $arg1;


                    if (isset($view_path[0])) {

                        if (is_numeric($view_path[0])) {
                            $section = 'prospect';

                            $parent     = 'store';
                            $parent_key = $arg1;
                            $object     = 'prospect';
                            $key        = $view_path[0];


                            if (isset($view_path[1])) {


                                if ($view_path[1] == 'email') {

                                    $store_key = $arg1;

                                    $section = 'email_tracking';

                                    $parent     = 'prospect';
                                    $parent_key = $key;

                                    if (is_numeric($view_path[2])) {
                                        $section = 'email_tracking';
                                        $object  = 'email_tracking';
                                        $key     = $view_path[2];


                                    }

                                } elseif ($view_path[1] == 'compose') {


                                    if (isset($view_path[2]) and is_numeric($view_path[2])) {

                                        $parent     = 'prospect';
                                        $parent_key = $key;
                                        $object     = 'email_template';
                                        $key        = $view_path[2];

                                        $section = 'prospect.compose_email';

                                    }


                                }


                            }


                        } elseif ($view_path[0] == 'new') {
                            $section = 'prospect.new';
                            $object  = '';
                        } elseif ($view_path[0] == 'template') {


                            if (is_numeric($view_path[1])) {
                                $section = 'prospects.email_template';
                                $object  = 'email_template';
                                $key     = $view_path[1];


                            } elseif ($view_path[1] == 'new') {
                                $object  = 'email_template';
                                $key     = 0;
                                $section = 'prospects.template.new';

                            }


                        }

                    }

                }


                break;
            case 'customers':
                if (!$user->can_view('customers')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module = 'customers';

                if ($count_view_path == 0) {
                    $section = 'customers';
                    $parent  = 'store';
                    if ($user->data['User Hooked Store Key'] and in_array(
                            $user->data['User Hooked Store Key'], $user->stores
                        )) {
                        $parent_key = $user->data['User Hooked Store Key'];
                    } else {
                        $_tmp       = $user->stores;
                        $parent_key = array_shift($_tmp);
                    }

                }
                $arg1 = array_shift($view_path);


                if ($arg1 == 'all') {
                    $module  = 'customers_server';
                    $section = 'customers';
                    if (isset($view_path[0])) {
                        if ($view_path[0] == 'email_communications') {
                            $section = 'email_communications';
                        }
                    }

                } elseif ($arg1 == 'list') {
                    $section = 'list';
                    $object  = 'list';


                    if (isset($view_path[0]) and is_numeric($view_path[0])) {
                        $key = $view_path[0];
                        include_once 'class.List.php';
                        $list       = new SubjectList($key);
                        $parent     = 'store';
                        $parent_key = $list->get('List Parent Key');


                        if (isset($view_path[1]) and is_numeric(
                                $view_path[1]
                            )) {
                            $section    = 'customer';
                            $parent     = 'list';
                            $parent_key = $list->id;
                            $object     = 'customer';
                            $key        = $view_path[1];

                        }


                    } else {
                        //error
                    }

                } elseif ($arg1 == 'category') {
                    $section = 'category';
                    $object  = 'category';

                    if (isset($view_path[0]) and is_numeric($view_path[0])) {
                        $key = $view_path[0];
                        include_once 'class.Category.php';
                        $category   = new Category($key);
                        $parent     = 'store';
                        $parent_key = $category->get('Category Store Key');


                        if (isset($view_path[1]) and is_numeric(
                                $view_path[1]
                            )) {
                            $section    = 'customer';
                            $parent     = 'category';
                            $parent_key = $category->id;
                            $object     = 'customer';
                            $key        = $view_path[1];

                        }


                    } else {
                        //error
                    }

                } elseif (is_numeric($arg1)) {
                    $section    = 'customers';
                    $parent     = 'store';
                    $parent_key = $arg1;
                    if (isset($view_path[0])) {

                        if (is_numeric($view_path[0])) {
                            $section = 'customer';

                            $parent     = 'store';
                            $parent_key = $arg1;
                            $object     = 'customer';
                            $key        = $view_path[0];
                            if (isset($view_path[1])) {

                                if ($view_path[1] == 'email') {


                                    $module     = 'customers';
                                    $section    = 'email_tracking';
                                    $parent     = 'customer';
                                    $parent_key = $key;
                                    $object     = 'email_tracking';
                                    if (isset($view_path[2])) {
                                        if (is_numeric($view_path[2])) {
                                            $key = $view_path[2];
                                        }
                                    }
                                } elseif ($view_path[1] == 'product') {


                                    $module     = 'customers';
                                    $section    = 'product';
                                    $parent     = 'customer';
                                    $parent_key = $key;
                                    $object     = 'product';
                                    if (isset($view_path[2])) {
                                        if (is_numeric($view_path[2])) {
                                            $key = $view_path[2];


                                        }
                                    }
                                }
                            }

                        } elseif ($view_path[0] == 'lists') {
                            $section = 'lists';


                            if (!empty($view_path[1])) {
                                if ($view_path[1] == 'new') {
                                    $section = 'list.new';
                                    $object  = 'list';

                                }
                            }

                        } elseif ($view_path[0] == 'categories') {
                            $section = 'categories';

                        } elseif ($view_path[0] == 'notifications') {
                            $section = 'customer_notifications';

                            if (isset($view_path[1])) {
                                if (is_numeric($view_path[1])) {
                                    $section = 'email_campaign_type';
                                    $object  = 'email_campaign_type';
                                    $key     = $view_path[1];

                                    if (isset($view_path[2])) {
                                        if ($view_path[2] == 'tracking') {


                                            $section = 'email_tracking';

                                            $parent     = 'email_campaign_type';
                                            $parent_key = $key;

                                            if (is_numeric($view_path[3])) {
                                                $section = 'email_tracking';
                                                $object  = 'email_tracking';
                                                $key     = $view_path[3];


                                            }

                                        } elseif ($view_path[2] == 'mailshot') {


                                            $section = 'mailshot';

                                            $parent     = 'email_campaign_type';
                                            $parent_key = $key;

                                            if (is_numeric($view_path[3])) {
                                                $section = 'mailshot';
                                                $object  = 'mailshot';
                                                $key     = $view_path[3];


                                            }


                                            if (isset($view_path[4])) {
                                                if ($view_path[4] == 'tracking') {


                                                    $section = 'email_tracking';

                                                    $parent     = 'mailshot';
                                                    $parent_key = $key;

                                                    if (is_numeric($view_path[5])) {
                                                        $section = 'email_tracking';
                                                        $object  = 'email_tracking';
                                                        $key     = $view_path[5];


                                                    }
                                                }
                                            }

                                            //===


                                        }
                                    }


                                }
                            }

                        } elseif ($view_path[0] == 'email_campaign_type') {


                            $section = 'email_campaign_type';
                            $object  = 'email_campaign_type';
                            if (isset($view_path[1])) {


                                if (is_numeric($view_path[1])) {

                                    $key = $view_path[1];
                                }


                            }


                        } elseif ($view_path[0] == 'insights') {
                            $section = 'insights';


                        } elseif ($view_path[0] == 'poll_query') {
                            $section = 'insights';


                            if (isset($view_path[1])) {
                                $object = 'Customer_Poll_Query';

                                if (is_numeric($view_path[1])) {
                                    $section = 'poll_query';
                                    $key     = $view_path[1];


                                    if (isset($view_path[2]) and $view_path[2] == 'option' and isset($view_path[3])) {
                                        $object = 'Customer_Poll_Query_Option';

                                        $parent     = 'customer_poll_query';
                                        $parent_key = $key;

                                        if (is_numeric($view_path[3])) {
                                            $section = 'poll_query_option';
                                            $key     = $view_path[3];


                                        } elseif ($view_path[3] == 'new') {
                                            $section = 'poll_query_option.new';
                                            $key     = 0;
                                        }


                                    }


                                } elseif ($view_path[1] == 'new') {
                                    $section = 'poll_query.new';
                                    $key     = 0;
                                }


                            }


                        } elseif ($view_path[0] == 'category') {

                            $section = 'category';
                            $object  = 'category';

                            if (isset($view_path[1]) and is_numeric($view_path[1])) {

                                include_once 'class.Category.php';
                                $key = $view_path[1];

                                $category = new Category($key);


                                $parent     = 'category';
                                $parent_key = $category->get(
                                    'Category Parent Key'
                                );

                                //if ($category->get('Category Branch Type')=='Root') {
                                //}
                            }
                        } elseif ($view_path[0] == 'new') {
                            $section = 'customer.new';
                            $object  = '';
                        }
                    }

                }

                break;
            case 'newsletters':
                $module  = 'customers';
                $section = 'newsletter';
                $parent  = 'store';

                if (isset($view_path[0])) {
                    $parent_key = $view_path[0];

                    if (isset($view_path[1])) {

                        $object = 'mailshot';
                        $key    = $view_path[1];

                    }


                }


                break;
            case 'receipts':
                $module  = 'account';
                $section = 'orders_index';
                break;
            case 'orders':
                if ($user->get('User Type') == 'Staff' or $user->get('User Type') == 'Contractor') {


                    if (!$user->can_view('orders')) {
                        $module  = 'utils';
                        $section = 'forbidden';
                        break;
                    }

                    $module = 'orders';
                    if ($count_view_path == 0) {
                        $section = 'orders';

                        $parent = 'store';
                        if ($user->data['User Hooked Store Key'] and in_array($user->data['User Hooked Store Key'], $user->stores)) {
                            $parent_key = $user->data['User Hooked Store Key'];
                        } else {
                            $_tmp       = $user->stores;
                            $parent_key = array_shift($_tmp);
                        }

                    }
                    $arg1 = array_shift($view_path);

                    if ($arg1 == 'all') {
                        $module     = 'orders_server';
                        $section    = 'orders';
                        $parent     = 'account';
                        $parent_key = 1;

                        if (isset($view_path[0])) {

                            if (is_numeric($view_path[0])) {
                                $module     = 'orders';
                                $section    = 'order';
                                $object     = 'order';
                                $parent     = 'account';
                                $parent_key = 1;
                                $key        = $view_path[0];


                            } elseif ($view_path[0] == 'dashboard') {
                                $section = 'dashboard';

                                if (isset($view_path[1])) {
                                    $extra = $view_path[1];

                                    if (isset($view_path[2])) {

                                        if (is_numeric($view_path[2])) {
                                            $section = 'order';
                                            $object  = 'order';
                                            $key     = $view_path[2];
                                        }

                                    }

                                }
                                if (isset($view_path[2])) {
                                    $extra_tab = $view_path[2];


                                    if (isset($view_path[3])) {


                                        if (is_numeric($view_path[3])) {


                                            $section = 'mailshot';
                                            $object  = 'mailshot';
                                            $key     = $view_path[3];


                                        }


                                    }

                                }


                            } elseif ($view_path[0] == 'by_store') {
                                $section = 'group_by_store';
                            }


                        }


                    } elseif (is_numeric($arg1)) {


                        $section    = 'orders';
                        $parent     = 'store';
                        $parent_key = $arg1;

                        if (isset($view_path[0])) {


                            if ($view_path[0] == 'dashboard') {
                                $section = 'dashboard';
                                if (isset($view_path[1])) {

                                    $extra = $view_path[1];


                                    if (isset($view_path[2])) {

                                        if (is_numeric($view_path[2])) {
                                            $section = 'order';
                                            $object  = 'order';
                                            $key     = $view_path[2];
                                        }

                                    }

                                }

                                if (isset($view_path[2])) {

                                    if ($view_path[2] == 'mailshots') {
                                        $extra_tab = $view_path[2];

                                        if (isset($view_path[3])) {


                                            if (is_numeric($view_path[3])) {

                                                $section = 'mailshot';
                                                $object  = 'mailshot';
                                                $key     = $view_path[3];


                                            }


                                        }
                                    } elseif ($view_path[2] == 'purges') {
                                        $extra_tab = $view_path[2];

                                        if (isset($view_path[3])) {


                                            if (is_numeric($view_path[3])) {

                                                $section = 'purge';
                                                $object  = 'purge';
                                                $key     = $view_path[3];


                                            }


                                        }
                                    }


                                }


                            } elseif (is_numeric($view_path[0])) {
                                $section = 'order';
                                $object  = 'order';

                                $parent     = 'store';
                                $parent_key = $arg1;
                                $key        = $view_path[0];


                                if (isset($view_path[1])) {


                                    if ($view_path[1] == 'invoice') {

                                        $section = 'invoice';
                                        $object  = 'invoice';

                                        $parent     = 'order';
                                        $parent_key = $key;
                                        if (isset($view_path[2])) {

                                            if (is_numeric($view_path[2])) {
                                                $key = $view_path[2];
                                            }


                                        }


                                    } elseif ($view_path[1] == 'refund') {

                                        $section = 'refund';
                                        $object  = 'refund';

                                        $parent     = 'order';
                                        $parent_key = $key;
                                        if (isset($view_path[2])) {

                                            if (is_numeric($view_path[2])) {
                                                $key = $view_path[2];
                                            } elseif ($view_path[2] == 'new') {
                                                $object     = 'order';
                                                $key        = $parent_key;
                                                $parent     = 'store';
                                                $parent_key = $arg1;
                                                $section    = 'refund.new';

                                            }


                                        }

                                    } elseif ($view_path[1] == 'replacement') {

                                        $section = 'replacement';
                                        $object  = 'replacement';

                                        $parent     = 'order';
                                        $parent_key = $key;
                                        if (isset($view_path[2])) {

                                            if (is_numeric($view_path[2])) {
                                                $key = $view_path[2];
                                            } elseif ($view_path[2] == 'new') {
                                                $object     = 'order';
                                                $key        = $parent_key;
                                                $parent     = 'store';
                                                $parent_key = $arg1;
                                                $section    = 'replacement.new';
                                            }


                                        }

                                    } elseif ($view_path[1] == 'return') {

                                        $section = 'return';
                                        $object  = 'return';

                                        $parent     = 'order';
                                        $parent_key = $key;
                                        if (isset($view_path[2])) {

                                            if (is_numeric($view_path[2])) {
                                                $key = $view_path[2];
                                            } elseif ($view_path[2] == 'new') {
                                                $object     = 'order';
                                                $key        = $parent_key;
                                                $parent     = 'store';
                                                $parent_key = $arg1;
                                                $section    = 'return.new';
                                            }


                                        }

                                    } elseif ($view_path[1] == 'email') {


                                        $section    = 'email_tracking';
                                        $parent     = 'order';
                                        $parent_key = $key;
                                        $object     = 'email_tracking';
                                        if (isset($view_path[2])) {
                                            if (is_numeric($view_path[2])) {
                                                $key = $view_path[2];
                                            }
                                        }
                                    }


                                }
                            }

                        }


                    }
                } elseif ($user->get('User Type') == 'Agent') {
                    $module     = 'agent_client_orders';
                    $section    = 'orders';
                    $parent     = 'agent';
                    $parent_key = $user->get('User Parent Key');
                }
                break;
            case 'invoices':
                if (!$user->can_view('orders')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module     = 'accounting_server';
                $parent     = 'account';
                $parent_key = 1;

                if (isset($view_path[0])) {

                    if (is_numeric($view_path[0])) {
                        $module  = 'accounting';
                        $section = 'invoices';

                        $parent     = 'store';
                        $parent_key = $view_path[0];

                        if (isset($view_path[1])) {
                            if (is_numeric($view_path[1])) {
                                $section    = 'invoice';
                                $object     = 'invoice';
                                $parent     = 'store';
                                $parent_key = $view_path[0];
                                $key        = $view_path[1];
                            }

                        }

                    } elseif ($view_path[0] == 'all') {
                        $section      = 'invoices';
                        $_data['tab'] = 'invoices_server';
                    } elseif ($view_path[0] == 'per_store') {
                        $section      = 'invoices';
                        $_data['tab'] = 'invoices_per_store';
                    } elseif ($view_path[0] == 'deleted') {

                        if (isset($view_path[1])) {
                            if ($view_path[1] == 'all') {
                                $section      = 'invoices';
                                $_data['tab'] = 'deleted_invoices_server';


                                if (isset($view_path[2])) {
                                    if (is_numeric($view_path[2])) {
                                        $section = 'deleted_invoice';
                                        $object  = 'invoice';
                                        $key     = $view_path[2];


                                    }

                                }


                            } elseif (is_numeric($view_path[1])) {
                                $section    = 'deleted_invoices';
                                $module     = 'accounting';
                                $parent     = 'store';
                                $parent_key = $view_path[1];


                                if (isset($view_path[2])) {
                                    if (is_numeric($view_path[2])) {
                                        $section = 'deleted_invoice';
                                        $module  = 'accounting';
                                        $object  = 'invoice';
                                        $key     = $view_path[2];


                                    }

                                }


                            }

                        }


                    } elseif ($view_path[0] == 'category') {

                        if (isset($view_path[1])) {
                            if ($view_path[1] == 'all') {
                                $section = 'invoices';

                                $_data['tab'] = 'invoices.categories';

                            } elseif (is_numeric($view_path[1])) {
                                $section = 'category';


                                $object = 'category';
                                $key    = $view_path[1];

                                // $_data['tab'] = 'category.invoices';


                            }

                        }


                    }
                }
                /*

                                if ($count_view_path == 0) {
                                    $section = 'invoices';
                                    $parent  = 'store';


                                } else {
                                    $arg1 = array_shift($view_path);
                                    if ($arg1 == 'all') {
                                        $module     = 'accounting_server';
                                        $section    = 'invoices';
                                        $parent     = 'account';
                                        $parent_key = 1;

                                        if (isset($view_path[0])) {


                                            if ($view_path[0] == 'categories') {


                                                $section = 'category';

                                                $object = 'category';

                                                $sql = sprintf('select `Category Key` from `Category Dimension` where  `Category Scope`="Invoice" and `Category Branch Type`="Root"  ');

                                                if ($result = $db->query($sql)) {
                                                    if ($row = $result->fetch()) {
                                                        $key = $row['Category Key'];

                                                    }
                                                }


                                            } elseif ($view_path[0] == 'category') {

                                                $section = 'category';

                                                $object = 'category';

                                                if (isset($view_path[1]) and is_numeric(
                                                        $view_path[1]
                                                    )) {
                                                    $key = $view_path[1];

                                                    include_once 'class.Category.php';
                                                    $category = new Category($key);

                                                    $parent     = 'category';
                                                    $parent_key = $category->get('Category Parent Key');

                                                    if ($category->get('Category Branch Type') == 'Root') {

                                                    }
                                                }
                                            }

                                        }

                                    } elseif (is_numeric($arg1)) {
                                        $section    = 'invoices';
                                        $parent     = 'store';
                                        $parent_key = $arg1;

                                        if (isset($view_path[0])) {

                                            if ($view_path[0] == 'categories') {
                                                $section = 'categories';

                                            } elseif (is_numeric($view_path[0])) {
                                                $section    = 'invoice';
                                                $object     = 'invoice';
                                                $parent     = 'store';
                                                $parent_key = $arg1;
                                                $key        = $view_path[0];

                                            }

                                        }

                                    }
                                }

                */

                break;

            case 'invoice':
                if (!$user->can_view('orders')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module     = 'accounting_server';
                $parent     = 'account';
                $parent_key = 1;

                if (isset($view_path[0])) {

                    if (is_numeric($view_path[0])) {
                        $section = 'invoice';
                        $object  = 'invoice';

                        $key = $view_path[0];

                    }
                }
                /*


                                /*

                                $module = 'invoices';
                                if (isset($view_path[0])) {

                                    if (is_numeric($view_path[0])) {
                                        $section = 'invoice';
                                        $object  = 'invoice';

                                        $parent     = '';
                                        $parent_key = '';
                                        $key        = $view_path[0];

                                        if (isset($view_path[1])) {
                                            if ($view_path[1] == 'item') {
                                                $module     = 'products';
                                                $section    = 'product';
                                                $object     = 'product';
                                                $parent     = 'invoice';
                                                $parent_key = $key;

                                                if (is_numeric($view_path[2])) {
                                                    $otf = $view_path[2];
                                                }

                                                $sql = sprintf(
                                                    "SELECT `Product ID` AS `key` FROM `Order Transaction Fact` WHERE `Order Transaction Fact Key`=%d", $otf
                                                );
                                                if ($row = $db->query($sql)->fetch()) {
                                                    $key          = $row['key'];
                                                    $_data['otf'] = $otf;
                                                }


                                            } elseif ($view_path[1] == 'order') {
                                                $section    = 'order';
                                                $object     = 'order';
                                                $parent     = 'invoice';
                                                $parent_key = $key;

                                                if (is_numeric($view_path[2])) {
                                                    $key = $view_path[2];
                                                }


                                            } elseif ($view_path[1] == 'delivery_note') {
                                                $section    = 'delivery_note';
                                                $object     = 'delivery_note';
                                                $parent     = 'invoice';
                                                $parent_key = $key;

                                                if (is_numeric($view_path[2])) {
                                                    $key = $view_path[2];
                                                }


                                            }
                                        }


                                    }


                                }
                */
                break;
                /*
                            case 'returns':

                                $module = 'warehouses';

                                $arg1 = array_shift($view_path);

                                if ($arg1 == 'all') {
                                    $module     = 'warehouses_server';
                                    $section    = 'returns';
                                    $parent     = 'account';
                                    $parent_key = 1;


                                } elseif (is_numeric($arg1)) {
                                    $section    = 'returns';
                                    $parent     = 'warehouse';
                                    $parent_key = $arg1;

                                    if (isset($view_path[0]) and is_numeric($view_path[0])) {
                                        $section    = 'return';
                                        $object     = 'return';
                                        $parent     = 'warehouse';


                                    }

                                }

                */
                break;
            case 'delivery_notes':
                if (!$user->can_view('orders')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module = 'delivery_notes';
                if ($count_view_path == 0) {
                    $section = 'delivery_notes';
                    $parent  = 'store';
                    if ($user->data['User Hooked Store Key'] and in_array(
                            $user->data['User Hooked Store Key'], $user->stores
                        )) {
                        $parent_key = $user->data['User Hooked Store Key'];
                    } else {
                        $_tmp       = $user->stores;
                        $parent_key = array_shift($_tmp);
                    }

                }
                $arg1 = array_shift($view_path);

                if ($arg1 == 'all') {
                    $module     = 'delivery_notes_server';
                    $section    = 'delivery_notes';
                    $parent     = 'account';
                    $parent_key = 1;


                    if (isset($view_path[0]) and $view_path[0] == 'by_store') {
                        $module     = 'delivery_notes_server';
                        $section    = 'group_by_store';
                        $parent     = 'account';
                        $parent_key = 1;
                    }


                } elseif (is_numeric($arg1)) {
                    $section    = 'delivery_notes';
                    $parent     = 'store';
                    $parent_key = $arg1;

                    if (isset($view_path[0]) and is_numeric($view_path[0])) {
                        $section    = 'delivery_note';
                        $object     = 'delivery_note';
                        $parent     = 'store';
                        $parent_key = $arg1;
                        $key        = $view_path[0];

                    }

                }
                break;


            case 'order':


                if (!$user->can_view('orders')) {


                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }


                if (isset($view_path[0])) {

                    $module = 'orders';
                    if (is_numeric($view_path[0])) {
                        $section = 'order';
                        $object  = 'order';

                        $parent     = '';
                        $parent_key = '';
                        $key        = $view_path[0];

                        if (isset($view_path[1])) {
                            if ($view_path[1] == 'item') {
                                $module     = 'products';
                                $section    = 'product';
                                $object     = 'product';
                                $parent     = 'order';
                                $parent_key = $key;

                                if (is_numeric($view_path[2])) {
                                    $otf = $view_path[2];
                                }

                                $sql = sprintf(
                                    "SELECT `Product ID` AS `key` FROM `Order Transaction Fact` WHERE `Order Transaction Fact Key`=%d", $otf
                                );
                                if ($row = $db->query($sql)->fetch()) {
                                    $key          = $row['key'];
                                    $_data['otf'] = $otf;
                                }


                            } elseif ($view_path[1] == 'delivery_note') {
                                $section    = 'delivery_note';
                                $object     = 'delivery_note';
                                $parent     = 'order';
                                $parent_key = $key;

                                if (is_numeric($view_path[2])) {
                                    $key = $view_path[2];
                                }


                            } elseif ($view_path[1] == 'pick_aid') {
                                $section    = 'pick_aid';
                                $object     = 'pick_aid';
                                $parent     = 'order';
                                $parent_key = $key;

                                if (is_numeric($view_path[2])) {
                                    $key = $view_path[2];
                                }


                            } elseif ($view_path[1] == 'invoice') {
                                $section    = 'invoice';
                                $object     = 'invoice';
                                $parent     = 'order';
                                $parent_key = $key;

                                if (is_numeric($view_path[2])) {
                                    $key = $view_path[2];
                                }


                            } elseif ($view_path[1] == 'payment') {
                                $section    = 'payment';
                                $object     = 'payment';
                                $parent     = 'order';
                                $parent_key = $key;

                                if (is_numeric($view_path[2])) {
                                    $key = $view_path[2];
                                }


                            }


                        }


                    }


                }

                break;

            case 'delivery_note':
                if (!$user->can_view('orders')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module = 'delivery_notes';
                if (isset($view_path[0])) {

                    if (is_numeric($view_path[0])) {
                        $section = 'delivery_note';
                        $object  = 'delivery_note';

                        $parent     = '';
                        $parent_key = '';
                        $key        = $view_path[0];

                        if (isset($view_path[1])) {
                            if ($view_path[1] == 'item') {
                                $module     = 'parts';
                                $section    = 'part';
                                $object     = 'part';
                                $parent     = 'delivery_note';
                                $parent_key = $key;

                                if (is_numeric($view_path[2])) {
                                    $otf = $view_path[2];
                                }

                                $sql = sprintf(
                                    "SELECT `Part SKU` AS `key` FROM `Inventory Transaction Fact` WHERE `Inventory Transaction Fact Key`=%d", $otf
                                );
                                if ($row = $db->query($sql)->fetch()) {
                                    $key          = $row['key'];
                                    $_data['otf'] = $otf;
                                }


                            } elseif ($view_path[1] == 'order') {
                                $section    = 'order';
                                $object     = 'order';
                                $parent     = 'delivery_note';
                                $parent_key = $key;

                                if (is_numeric($view_path[2])) {
                                    $key = $view_path[2];
                                }


                            } elseif ($view_path[1] == 'pick_aid') {
                                $section    = 'pick_aid';
                                $object     = 'pick_aid';
                                $parent     = 'delivery_note';
                                $parent_key = $key;

                                if (is_numeric($view_path[2])) {
                                    $key = $view_path[2];
                                }


                            } elseif ($view_path[1] == 'pack_aid') {
                                $section    = 'pack_aid';
                                $object     = 'pack_aid';
                                $parent     = 'delivery_note';
                                $parent_key = $key;

                                if (is_numeric($view_path[2])) {
                                    $key = $view_path[2];
                                }


                            } elseif ($view_path[1] == 'invoice') {
                                $section    = 'invoice';
                                $object     = 'invoice';
                                $parent     = 'delivery_note';
                                $parent_key = $key;

                                if (is_numeric($view_path[2])) {
                                    $key = $view_path[2];
                                }


                            }
                        }


                    }


                }

                break;

            case 'offers':
                if (!$user->can_view('marketing')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module = 'products';


                if (isset($view_path[0])) {
                    if (is_numeric($view_path[0])) {

                        $parent     = 'store';
                        $parent_key = $view_path[0];

                        $section = 'offers';

                        if (isset($view_path[1])) {
                            if ($view_path[1] == 'vo') {

                                $section = 'vouchers';

                                $object = 'campaign';
                                $key    = $view_path[1];


                                if (isset($view_path[2])) {
                                    if ($view_path[2] == 'new') {

                                        $parent     = 'campaign';
                                        $parent_key = $view_path[1];
                                        $extra      = $view_path[0];
                                        $object     = 'deal';
                                        $key        = 0;
                                        $section    = 'deal.new';


                                    } elseif (is_numeric($view_path[2])) {

                                        $parent     = 'campaign';
                                        $parent_key = $view_path[1];
                                        $extra      = $view_path[0];
                                        $object     = 'deal';
                                        $key        = $view_path[2];
                                        $section    = 'deal';

                                    }

                                }


                            } elseif ($view_path[1] == 'or') {

                                $section = 'campaign_order_recursion';

                                $object = 'campaign';
                                $key    = $view_path[1];

                                if (isset($view_path[2])) {
                                    if ($view_path[2] == 'deal_component') {

                                        $parent     = 'campaign';
                                        $parent_key = $view_path[1];
                                        $object     = 'deal_component';
                                        $section    = 'deal_component';
                                        $extra      = $view_path[0];
                                        $key        = 0;
                                        if (isset($view_path[3])) {
                                            if (is_numeric($view_path[3])) {

                                                $key = $view_path[3];


                                            }

                                        }

                                    }

                                }

                            } elseif ($view_path[1] == 'vl' or $view_path[1] == 'so' or $view_path[1] == 'fo' or $view_path[1] == 'ca' or $view_path[1] == 'cu') {

                                $section = 'campaign';

                                $object = 'campaign';
                                $key    = $view_path[1];

                                if (isset($view_path[2])) {


                                    if (is_numeric($view_path[2])) {

                                        $parent     = 'campaign';
                                        $parent_key = $view_path[1];
                                        $extra      = $view_path[0];
                                        $object     = 'deal';
                                        $key        = $view_path[2];
                                        $section    = 'deal';

                                    } elseif ($view_path[2] == 'new') {

                                        $parent     = 'campaign';
                                        $parent_key = $view_path[1];
                                        $extra      = $view_path[0];
                                        $object     = 'deal';
                                        $key        = 0;
                                        $section    = 'deal.new';


                                    }


                                }

                            }


                        }


                    } elseif ($view_path[0] == 'all') {
                        $module  = 'offers_server';
                        $section = 'offers';
                    }

                }


                break;
            case 'marketing':


                if (!$user->can_view('marketing')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }


                if (isset($view_path[0])) {
                    if (is_numeric($view_path[0])) {


                        if (isset($view_path[1])) {

                            $parent     = 'store';
                            $parent_key = $view_path[0];


                            if ($view_path[1] == 'emails') {
                                $module  = 'products';
                                $section = 'marketing';
                                if (isset($view_path[2])) {
                                    if (is_numeric($view_path[2])) {

                                        $section = 'email_campaign_type';
                                        $object  = 'email_campaign_type';

                                        $key = $view_path[2];


                                        if (isset($view_path[3])) {
                                            if ($view_path[3] == 'mailshot') {


                                                $section = 'mailshot';

                                                $parent     = 'email_campaign_type';
                                                $parent_key = $key;


                                                if (isset($view_path[4])) {
                                                    if (is_numeric($view_path[4])) {

                                                        $section = 'mailshot';
                                                        $object  = 'mailshot';
                                                        $key     = $view_path[4];
                                                        if (isset($view_path[5])) {


                                                            if ($view_path[5] == 'tracking') {


                                                                $section = 'email_tracking';

                                                                $parent     = 'mailshot';
                                                                $parent_key = $key;

                                                                if (is_numeric($view_path[6])) {
                                                                    $section = 'email_tracking';
                                                                    $object  = 'email_tracking';
                                                                    $key     = $view_path[6];


                                                                }

                                                            }
                                                        }


                                                    } elseif ($view_path[4] == 'new') {
                                                        $object  = 'mailshot';
                                                        $section = 'mailshot.new';
                                                        $key     = 0;
                                                    }

                                                }
                                            }


                                        }


                                    }

                                }
                            }


                        }


                    }

                }


                break;

            case 'deals':
                $module = 'products';


                if (isset($view_path[0])) {
                    $section = 'deal';


                    $parent     = 'store';
                    $parent_key = $view_path[0];

                    if (isset($view_path[1])) {
                        $object     = 'deal';
                        $parent     = 'store';
                        $parent_key = $view_path[0];
                        if (is_numeric($view_path[1])) {
                            $key = $view_path[1];


                        } elseif ($view_path[1] == 'new') {
                            $section = 'deal.new';
                        }

                    }
                }


                break;

            case 'deal':
                $module = 'products';


                if (isset($view_path[0])) {
                    $section = 'deals';


                    $parent = 'campaign';
                    $key    = $view_path[0];


                    if (isset($view_path[1])) {


                        if ($view_path[1] == 'order') {
                            $object     = 'order';
                            $section    = 'order';
                            $parent     = 'deal';
                            $parent_key = $key;
                            if (isset($view_path[2])) {
                                $key = $view_path[2];
                            }

                        } elseif ($view_path[1] == 'customer') {
                            $object     = 'customer';
                            $section    = 'customer';
                            $parent     = 'deal';
                            $parent_key = $key;
                            if (isset($view_path[2])) {
                                $key = $view_path[2];
                            }

                        }


                    }

                }
                break;

            case 'warehouses':

                if (!$user->can_view('locations')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module  = 'warehouses_server';
                $section = 'warehouses';


                break;

            case 'warehouse':

                if (!$user->can_view('locations')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module  = 'warehouses';
                $section = 'warehouse';


                if (isset($view_path[0])) {

                    if (is_numeric($view_path[0])) {
                        $object = 'warehouse';
                        $key    = $view_path[0];
                        if (isset($view_path[1])) {

                            if ($view_path[1] == 'dashboard') {

                                $section = 'dashboard';


                            } elseif ($view_path[1] == 'feedback') {

                                $section = 'feedback';


                            } elseif ($view_path[1] == 'delivery_notes') {
                                //===

                                if (!$user->can_view('orders')) {
                                    $module  = 'utils';
                                    $section = 'forbidden';
                                    break;
                                }

                                $section = 'delivery_notes';
                                $object  = '';

                                $parent     = 'warehouse';
                                $parent_key = $key;
                            } elseif ($view_path[1] == 'shippers') {


                                $parent     = 'warehouse';
                                $parent_key = $key;

                                $section = 'shippers';
                                if (isset($view_path[2])) {
                                    if (is_numeric($view_path[2])) {
                                        $section = 'shipper';
                                        $object  = 'shipper';
                                        $key     = $view_path[2];
                                    } elseif ($view_path[2] == 'new') {
                                        $section = 'shipper.new';
                                        $object  = 'shipper';
                                        $key     = 0;
                                    }
                                }


                            } elseif ($view_path[1] == 'returns') {

                                $section = 'returns';

                                $parent     = 'warehouse';
                                $parent_key = $key;

                                if (isset($view_path[2])) {
                                    $section = 'return';
                                    $object  = 'supplierdelivery';

                                    if (is_numeric($view_path[2])) {
                                        if (is_numeric($view_path[2])) {
                                            $key = $view_path[2];
                                        }

                                    }


                                }


                            } elseif ($view_path[1] == 'leakages') {


                                $section = 'leakages';
                                $object  = '';

                                $parent     = 'warehouse';
                                $parent_key = $key;


                                if (isset($view_path[2])) {
                                    if (is_numeric($view_path[2])) {
                                        $key = $view_path[2];

                                        if (isset($view_path[3])) {
                                            if (is_numeric($view_path[3])) {
                                                $section    = 'timeseries_record';
                                                $parent     = 'timeseries';
                                                $parent_key = $view_path[2];
                                                $object     = 'timeseries_record';

                                                $key = $view_path[3];


                                            }

                                        }


                                    }

                                }


                            } elseif ($view_path[1] == 'locations') {
                                $section = 'locations';
                                $object  = '';

                                $parent     = 'warehouse';
                                $parent_key = $key;


                                if (isset($view_path[2])) {
                                    if ($view_path[2] == 'upload') {

                                        $section = 'upload';
                                        $object  = 'upload';


                                        if (isset($view_path[3])) {

                                            if (is_numeric($view_path[3])) {
                                                $key = $view_path[3];
                                            }

                                        }

                                    }

                                } else {
                                    $_data['tab'] = 'warehouse.locations';
                                }


                            } elseif ($view_path[1] == 'areas') {
                                $section = 'locations';


                                $object = '';

                                $parent     = 'warehouse';
                                $parent_key = $key;

                                if (isset($view_path[2])) {
                                    $object = 'warehouse_area';
                                    if ($view_path[2] == 'new') {

                                        $section = 'warehouse_area.new';
                                        $key     = 0;


                                    } elseif (is_numeric($view_path[2])) {
                                        $key     = $view_path[2];
                                        $section = 'warehouse_area';

                                        if (isset($view_path[3])) {

                                            if ($view_path[3] == 'location') {


                                                if (isset($view_path[4])) {

                                                    $parent     = 'warehouse_area';
                                                    $parent_key = $key;
                                                    $object     = 'location';
                                                    if ($view_path[4] == 'new') {


                                                        $section = 'location.new';
                                                        $key     = 0;


                                                    } elseif (is_numeric($view_path[4])) {

                                                        $section = 'location';
                                                        $key     = $view_path[4];
                                                    }

                                                }

                                            } elseif ($view_path[3] == 'upload') {


                                                $parent     = 'warehouse_area';
                                                $parent_key = $key;


                                                $section = 'upload';

                                                $object = 'upload';
                                                if (isset($view_path[4])) {
                                                    if (is_numeric($view_path[4])) {

                                                        $key = $view_path[4];
                                                    }
                                                }

                                            }


                                        }


                                    } elseif ($view_path[2] == 'all') {
                                        $object       = '';
                                        $_data['tab'] = 'warehouse.areas';

                                    } elseif ($view_path[2] == 'upload') {
                                        $section = 'upload';

                                        $object = 'upload';
                                        if (isset($view_path[3])) {
                                            if (is_numeric($view_path[3])) {

                                                $key = $view_path[3];
                                            }
                                        }

                                    }


                                } else {
                                    $_data['tab'] = 'warehouse.areas';
                                }


                            } elseif ($view_path[1] == 'area') {
                                $section = 'warehouse_area';
                                $object  = '';

                                $parent     = 'warehouse';
                                $parent_key = $key;

                                if (isset($view_path[2])) {
                                    $object = 'warehouse_area';
                                    if ($view_path[2] == 'new') {

                                        $section = 'warehouse_area.new';
                                        $key     = 0;


                                    } elseif (is_numeric($view_path[2])) {
                                        $key     = $view_path[2];
                                        $section = 'warehouse_area';

                                    }


                                }


                            } elseif ($view_path[1] == 'categories') {
                                $object     = 'warehouse';
                                $key        = $view_path[0];
                                $section    = 'categories';
                                $parent     = 'warehouse';
                                $parent_key = $view_path[0];
                            } else {
                                if ($view_path[1] == 'category') {
                                    $section = 'category';
                                    $object  = 'category';

                                    if (isset($view_path[2])) {

                                        $view_path[2] = preg_replace(
                                            '/\>$/', '', $view_path[2]
                                        );
                                        if (preg_match(
                                            '/^(\d+\>)+(\d+)$/', $view_path[2]
                                        )) {

                                            $parent_categories = preg_split(
                                                '/\>/', $view_path[2]
                                            );
                                            $metadata          = $parent_categories;
                                            $key               = array_pop(
                                                $parent_categories
                                            );

                                            $parent = 'category';


                                            $parent_key = array_pop(
                                                $parent_categories
                                            );


                                            if (isset($view_path[3])) {

                                                if ($view_path[3] == 'location') {

                                                    $parent_key = $key;

                                                    $section = 'location';
                                                    $object  = 'location';
                                                    if (isset($view_path[4]) and is_numeric(
                                                            $view_path[4]
                                                        )) {

                                                        $key = $view_path[4];

                                                    }

                                                }


                                            }

                                        } elseif (is_numeric($view_path[2])) {
                                            $key = $view_path[2];
                                            include_once 'class.Category.php';
                                            $category = new Category($key);
                                            if ($category->get(
                                                    'Category Branch Type'
                                                ) == 'Root') {
                                                $parent     = 'warehouse';
                                                $parent_key = $category->get(
                                                    'Category Store Key'
                                                );
                                            } else {
                                                $parent     = 'category';
                                                $parent_key = $category->get(
                                                    'Category Parent Key'
                                                );

                                            }


                                            if (isset($view_path[3])) {


                                                if (is_numeric($view_path[3])) {
                                                    $section    = 'location';
                                                    $parent     = 'category';
                                                    $parent_key = $category->id;
                                                    $object     = 'location';
                                                    $key        = $view_path[3];
                                                } elseif ($view_path[3] == 'location') {
                                                    $section = 'location';
                                                    $object  = 'location';
                                                    if (isset($view_path[4]) and is_numeric(
                                                            $view_path[4]
                                                        )) {

                                                        $key = $view_path[4];

                                                    }

                                                } elseif ($view_path[3] == 'upload') {
                                                    //$module='account';
                                                    $section    = 'upload';
                                                    $parent     = 'category';
                                                    $parent_key = $key;
                                                    $object     = 'upload';
                                                    if (isset($view_path[4])) {
                                                        if (is_numeric(
                                                            $view_path[4]
                                                        )) {

                                                            $key = $view_path[4];
                                                        }
                                                    }

                                                }

                                            }


                                        } elseif ($view_path[2] == 'new') {

                                            $section = 'main_category.new';

                                            $parent     = 'warehouse';
                                            $parent_key = $view_path[0];
                                            $key        = 0;
                                        }
                                    } else {
                                        //error
                                    }

                                }
                            }


                        }
                    } elseif ($view_path[0] == 'new') {
                        $object  = 'warehouse';
                        $section = 'warehouse.new';


                    }


                } else {
                    if ($user->data['User Hooked Warehouse Key'] and in_array(
                            $user->data['User Hooked Warehouse Key'], $user->stores
                        )) {
                        $key = $user->data['User Hooked Warehouse Key'];
                    } else {
                        $_tmp = $user->warehouses;
                        $key  = array_shift($_tmp);
                    }
                }


                break;
            case 'inventory':

                if (!$user->can_view('parts')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }


                $module     = 'inventory';
                $section    = 'inventory';
                $parent     = 'account';
                $parent_key = 1;


                if (isset($view_path[0])) {

                    if ($view_path[0] == 'transactions') {
                        $section = 'transactions';

                    } elseif ($view_path[0] == 'dashboard') {

                        $section = 'dashboard';

                        if (isset($view_path[1])) {
                            $extra = $view_path[1];
                        }
                        if (isset($view_path[2])) {
                            $extra_tab = $view_path[2];
                        }


                    } elseif ($view_path[0] == 'stock_history') {
                        $section = 'stock_history';

                        if (isset($view_path[1])) {
                            if ($view_path[1] == 'day') {
                                $section = 'stock_history.day';
                                if (isset($view_path[2])) {
                                    $key = $view_path[2];
                                }
                            }
                        }

                    } elseif ($view_path[0] == 'categories') {
                        $section = 'categories';
                    } else {
                        if ($view_path[0] == 'category') {
                            $section = 'category';
                            $object  = 'category';
                            if (isset($view_path[1])) {


                                $view_path[1] = preg_replace(
                                    '/\>$/', '', $view_path[1]
                                );
                                if (preg_match(
                                    '/^(\d+\>)+(\d+)$/', $view_path[1]
                                )) {

                                    $parent_categories = preg_split(
                                        '/\>/', $view_path[1]
                                    );
                                    $metadata          = $parent_categories;
                                    $key               = array_pop(
                                        $parent_categories
                                    );

                                    $parent     = 'category';
                                    $parent_key = array_pop($parent_categories);

                                    if (isset($view_path[2])) {
                                        if ($view_path[2] == 'part') {
                                            $section = 'part';

                                            if (isset($view_path[2]) and is_numeric($view_path[2])) {

                                                $key = $view_path[2];

                                            }

                                        } elseif ($view_path[2] == 'upload') {
                                            //$module='account';
                                            $section    = 'upload';
                                            $parent     = 'category';
                                            $parent_key = $key;
                                            $object     = 'upload';
                                            if (isset($view_path[3])) {
                                                if (is_numeric($view_path[3])) {

                                                    $key = $view_path[3];
                                                }
                                            }

                                        }


                                    }

                                } elseif (is_numeric($view_path[1])) {

                                    $key = $view_path[1];
                                    include_once 'class.Category.php';
                                    $category = new Category($key);
                                    if ($category->get('Category Branch Type') == 'Root') {
                                        $parent     = 'account';
                                        $parent_key = 1;
                                    } else {
                                        $parent     = 'category';
                                        $parent_key = $category->get(
                                            'Category Parent Key'
                                        );

                                    }


                                    if (isset($view_path[2])) {
                                        if ($view_path[2] == 'part') {

                                            if (isset($view_path[3]) and is_numeric($view_path[3])) {

                                                $section    = 'part';
                                                $parent     = 'category';
                                                $parent_key = $category->id;
                                                $object     = 'part';
                                                $key        = $view_path[3];
                                            }
                                        } elseif ($view_path[2] == 'upload') {
                                            //$module='account';
                                            $section    = 'upload';
                                            $parent     = 'category';
                                            $parent_key = $key;
                                            $object     = 'upload';
                                            if (isset($view_path[3])) {
                                                if (is_numeric($view_path[3])) {

                                                    $key = $view_path[3];
                                                }
                                            }

                                        }


                                    }
                                } elseif ($view_path[1] == 'new') {

                                    $section = 'main_category.new';

                                }
                            } else {
                                //error
                            }

                        } elseif ($view_path[0] == 'barcodes') {
                            $section = 'barcodes';
                        } elseif ($view_path[0] == 'feedback') {
                            $section = 'feedback';
                        } elseif ($view_path[0] == 'barcode') {
                            $section = 'barcode';
                            $object  = 'barcode';
                            if (isset($view_path[1])) {
                                if (is_numeric($view_path[1])) {
                                    $key = $view_path[1];
                                }
                            }

                        } elseif ($view_path[0] == 'upload') {
                            $module     = 'account';
                            $section    = 'upload';
                            $parent     = 'inventory';
                            $parent_key = 1;
                            $object     = 'upload';
                            if (isset($view_path[1])) {
                                if (is_numeric($view_path[1])) {
                                    $key = $view_path[1];
                                }
                            }


                        }
                    }

                }


                break;

            case 'part':
                if (!$user->can_view('parts')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module = 'inventory';

                $section = 'part';
                $object  = 'part';

                if (is_numeric($view_path[0])) {
                    $key = $view_path[0];

                    if (isset($view_path[1])) {
                        if ($view_path[1] == 'product') {

                            if (is_numeric($view_path[2])) {
                                //$module='products';
                                $section    = 'product';
                                $object     = 'product';
                                $parent_key = $key;
                                $key        = $view_path[2];
                                $parent     = 'part';


                            } elseif ($view_path[2] == 'new') {
                                $module     = 'products';
                                $section    = 'product.new';
                                $object     = 'product';
                                $parent_key = $key;
                                $parent     = 'part';
                            }

                        } elseif ($view_path[1] == 'image') {

                            if (is_numeric($view_path[2])) {
                                $section    = 'part.image';
                                $object     = 'image.subject';
                                $parent_key = $key;
                                $key        = $view_path[2];
                                $parent     = 'part';

                            }

                        } elseif ($view_path[1] == 'attachment') {
                            $section    = 'part.attachment';
                            $object     = 'attachment';
                            $parent     = 'part';
                            $parent_key = $key;
                            if (isset($view_path[2])) {
                                if (is_numeric($view_path[2])) {
                                    $key = $view_path[2];
                                } elseif ($view_path[2] == 'new') {
                                    $section = 'part.attachment.new';

                                    $key = 0;
                                }
                            }


                        }
                        if ($view_path[1] == 'supplier_part') {

                            if (is_numeric($view_path[2])) {
                                //$module='products';
                                $section    = 'supplier_part';
                                $object     = 'supplier_part';
                                $parent_key = $key;
                                $key        = $view_path[2];
                                $parent     = 'part';


                            } elseif ($view_path[2] == 'new') {
                                $section = 'supplier_part.new';

                                $parent_key = $key;
                                $parent     = 'part';
                            }

                        }
                    }

                } elseif ($view_path[0] == 'new') {
                    $section = 'part.new';
                }


                break;
            case 'locations':
                if (!$user->can_view('locations')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module = 'warehouses';


                if (isset($view_path[0])) {
                    $parent     = 'warehouse';
                    $parent_key = $view_path[0];
                    if (isset($view_path[1])) {
                        if (is_numeric($view_path[1])) {
                            $section = 'location';
                            $object  = 'location';
                            $key     = $view_path[1];
                        } elseif ($view_path[1] == 'new') {
                            $key     = 0;
                            $object  = 'location';
                            $section = 'location.new';
                        }
                    }

                }

                break;

            case 'production':


                $module     = 'production';
                $parent     = 'account';
                $parent_key = 1;


                if (isset($view_path[0])) {


                    if (is_numeric($view_path[0])) {


                        $section = 'dashboard';
                        $object  = 'supplier_production';
                        $key     = $view_path[0];


                        if (isset($view_path[1])) {
                            if ($view_path[1] == 'manufacture_tasks') {
                                $section = 'manufacture_tasks';

                            } elseif ($view_path[1] == 'operatives') {
                                $section = 'operatives';

                            } elseif ($view_path[1] == 'orders') {
                                $section = 'production_supplier_orders';

                            } elseif ($view_path[1] == 'deliveries') {
                                $section = 'production_supplier_deliveries';

                            } elseif ($view_path[1] == 'batches') {
                                $section = 'batches';

                            } elseif ($view_path[1] == 'materials') {
                                $section = 'materials';

                            } elseif ($view_path[1] == 'parts') {
                                $section = 'production_parts';


                                if (isset($view_path[2])) {
                                    if ($view_path[2] == 'upload') {


                                        $section = 'upload';
                                        $object  = 'upload';


                                        if (isset($view_path[3])) {

                                            if (is_numeric($view_path[3])) {
                                                $key = $view_path[3];
                                            }

                                        }


                                    }
                                }

                            } elseif ($view_path[1] == 'delivery') {

                                $parent     = 'supplier_production';
                                $parent_key = $key;

                                $section = 'delivery';
                                if (isset($view_path[2])) {
                                    if (is_numeric($view_path[2])) {
                                        $object = 'supplier_delivery';
                                        $key    = $view_path[2];
                                    }
                                }
                            } elseif ($view_path[1] == 'order') {

                                $parent     = 'supplier_production';
                                $parent_key = $key;

                                $section = 'order';
                                if (isset($view_path[2])) {
                                    if (is_numeric($view_path[2])) {
                                        $object = 'purchase_order';
                                        $key    = $view_path[2];
                                    }
                                }
                            } elseif ($view_path[1] == 'part') {

                                $section    = 'production_part';
                                $parent     = 'supplier_production';
                                $parent_key = $key;
                                $object     = 'production_part';

                                if (isset($view_path[2])) {
                                    if (is_numeric($view_path[2])) {

                                        $key = $view_path[2];


                                        if (isset($view_path[3])) {


                                            if ($view_path[3] == 'order') {
                                                $section = 'order';

                                                $parent     = 'production_part';
                                                $parent_key = $key;
                                                $object     = 'purchase_order';

                                                if (isset($view_path[4])) {
                                                    if (is_numeric(
                                                        $view_path[4]
                                                    )) {
                                                        $key = $view_path[4];


                                                        if (isset($view_path[5])) {
                                                            if ($view_path[5] == 'item') {

                                                                if (isset($view_path[6])) {
                                                                    if (is_numeric(
                                                                        $view_path[6]
                                                                    )) {

                                                                        $parent     = 'PurchaseOrder';
                                                                        $parent_key = $view_path[4];
                                                                        $object     = 'PurchaseOrderItem';
                                                                        $key        = $view_path[6];
                                                                        $section    = 'supplier.order.item';


                                                                    }
                                                                }


                                                            }
                                                        }

                                                    }

                                                }


                                            }

                                        }


                                    } elseif ($view_path[2] == 'new') {
                                        $key     = 0;
                                        $section = 'production_part.new';
                                    } elseif ($view_path[2] == 'hk') {
                                        $object  = 'production_part_historic';
                                        $section = 'production_part.historic';
                                        if (isset($view_path[3])) {
                                            if (is_numeric($view_path[3])) {
                                                $key = $view_path[3];
                                            }
                                        }


                                    }
                                }


                            } elseif ($view_path[1] == 'material') {
                                $section = 'materials';

                            } elseif ($view_path[1] == 'operative') {
                                $section = 'operative';
                                $object  = 'operative';
                                if (isset($view_path[1])) {
                                    if (is_numeric($view_path[1])) {
                                        $key = $view_path[1];
                                    } elseif ($view_path[1] == 'add') {
                                        $section = 'operative.add';
                                    }
                                }

                            } elseif ($view_path[1] == 'manufacture_task') {
                                $section = 'manufacture_task';
                                $object  = 'manufacture_task';
                                if (isset($view_path[1])) {
                                    if (is_numeric($view_path[1])) {
                                        $key = $view_path[1];
                                    } elseif ($view_path[1] == 'new') {
                                        $section = 'manufacture_task.new';

                                    }
                                }

                            } elseif ($view_path[1] == 'settings') {
                                $section = 'settings';


                            }
                        }
                    } elseif ($view_path[0] == 'all') {
                        $module  = 'production_server';
                        $section = 'production.suppliers';
                        break;
                    }
                } else {
                    $module  = 'production_server';
                    $section = 'production.suppliers';
                    break;
                }


                break;

            case 'manufacture_task':
                if (!$user->can_view('suppliers')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module     = 'production';
                $section    = 'manufacture_task';
                $parent     = 'account';
                $parent_key = 1;
                $object     = 'manufacture_task';
                if (isset($view_path[0])) {
                    if (is_numeric($view_path[0])) {
                        $key = $view_path[0];
                    } elseif ($view_path[0] == 'new') {
                        $section = 'manufacture_task.new';
                    }
                }
                break;

            case 'agents':
                if (!$user->can_view('suppliers')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module  = 'suppliers';
                $section = 'agents';


                if (isset($view_path[0])) {


                    if ($view_path[0] == 'new') {
                        $section = 'agent.new';
                        $object  = 'agent';
                    }
                }
                break;

            case 'suppliers':

                if ($user->get('User Type') == 'Staff' or $user->get('User Type') == 'Contractor') {
                    if (!$user->can_view('suppliers')) {
                        $module  = 'utils';
                        $section = 'forbidden';
                        break;
                    }

                    $module  = 'suppliers';
                    $section = 'suppliers';


                    if (isset($view_path[0])) {

                        if ($view_path[0] == 'categories') {
                            $object  = 'category';
                            $key     = '';
                            $section = 'categories';

                        } elseif ($view_path[0] == 'dashboard') {
                            $object  = 'account';
                            $key     = 1;
                            $section = 'dashboard';

                        } elseif ($view_path[0] == 'settings') {
                            $object  = 'account';
                            $key     = 1;
                            $section = 'settings';

                        } elseif ($view_path[0] == 'orders') {

                            $section = 'orders';

                        } elseif ($view_path[0] == 'order') {

                            $section = 'order';
                            $object  = 'purchase_order';

                            if (isset($view_path[1])) {
                                if (is_numeric($view_path[1])) {
                                    $key = $view_path[1];
                                }

                            }


                        } else {
                            if ($view_path[0] == 'category') {
                                $section = 'category';
                                $object  = 'category';
                                if (isset($view_path[1])) {


                                    $view_path[1] = preg_replace(
                                        '/\>$/', '', $view_path[1]
                                    );
                                    if (preg_match(
                                        '/^(\d+\>)+(\d+)$/', $view_path[1]
                                    )) {

                                        $parent_categories = preg_split(
                                            '/\>/', $view_path[1]
                                        );
                                        $metadata          = $parent_categories;
                                        $key               = array_pop(
                                            $parent_categories
                                        );

                                        $parent     = 'category';
                                        $parent_key = array_pop(
                                            $parent_categories
                                        );

                                        if (isset($view_path[2])) {
                                            if ($view_path[2] == 'part') {
                                                $section = 'part';

                                                if (isset($view_path[2]) and is_numeric(
                                                        $view_path[2]
                                                    )) {

                                                    $key = $view_path[2];

                                                }

                                            }


                                        }

                                    } elseif (is_numeric($view_path[1])) {
                                        $key = $view_path[1];
                                        include_once 'class.Category.php';
                                        $category = new Category($key);
                                        if ($category->get(
                                                'Category Branch Type'
                                            ) == 'Root') {
                                            $parent     = 'account';
                                            $parent_key = 1;
                                        } else {
                                            $parent     = 'category';
                                            $parent_key = $category->get(
                                                'Category Parent Key'
                                            );

                                        }


                                        if (isset($view_path[2])) {
                                            if ($view_path[2] == 'supplier') {

                                                if (isset($view_path[3]) and is_numeric(
                                                        $view_path[3]
                                                    )) {

                                                    $section    = 'supplier';
                                                    $parent     = 'category';
                                                    $parent_key = $category->id;
                                                    $object     = 'supplier';
                                                    $key        = $view_path[3];
                                                }
                                            }
                                        }
                                    } elseif ($view_path[1] == 'new') {

                                        $section = 'main_category.new';

                                    }
                                } else {
                                    //error
                                }

                            } elseif ($view_path[0] == 'new') {
                                $section = 'supplier.new';
                                $object  = 'supplier';
                            }
                        }
                    }
                } elseif ($user->get('User Type') == 'Agent') {


                    $module  = 'agent_suppliers';
                    $section = 'suppliers';
                    $object  = 'agent';
                    $key     = $user->get('User Parent Key');


                }


                break;
            case 'agent_deliveries':
                if ($user->get('User Type') != 'Agent') {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module  = 'agent_client_deliveries';
                $section = 'deliveries';
                break;
            case 'agent_delivery':
                if ($user->get('User Type') != 'Agent') {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $parent     = 'Agent';
                $parent_key = $user->get('User Parent Key');

                $module  = 'agent_client_deliveries';
                $section = 'agent_delivery';
                $object  = 'supplier_delivery';

                if (isset($view_path[0])) {
                    if (is_numeric($view_path[0])) {
                        $key = $view_path[0];
                    }

                }

                break;
            case 'deliveries':
                if (!$user->can_view('suppliers')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module  = 'suppliers';
                $section = 'deliveries';
                break;

            case 'delivery':
                if (!$user->can_view('suppliers')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module  = 'suppliers';
                $section = 'delivery';
                $object  = 'supplierdelivery';

                if (isset($view_path[0])) {
                    if (is_numeric($view_path[0])) {
                        $key = $view_path[0];
                    }
                }
                break;

            case 'client_order':


                $module     = 'agent_client_orders';
                $section    = 'client_order';
                $object     = 'purchase_order';
                $parent     = 'Agent';
                $parent_key = $user->get('User Parent Key');

                if (isset($view_path[0])) {
                    if (is_numeric($view_path[0])) {
                        $key = $view_path[0];
                    }

                }

                if (isset($view_path[1])) {


                    if (is_numeric($view_path[1])) {
                        $section    = 'agent_supplier_order';
                        $object     = 'agent_supplier_order';
                        $parent     = 'client_order';
                        $parent_key = $key;
                        $key        = $view_path[1];


                    }

                }


                break;
            case 'agent_delivery':
                if ($user->get('User Type') != 'Agent') {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module = 'agent_client_deliveries';


                $section = 'agent_delivery';
                $object  = 'supplierdelivery';

                if (isset($view_path[0])) {
                    if (is_numeric($view_path[0])) {
                        $key = $view_path[0];
                    }

                }


                break;


            case 'agent_parts':
                if ($user->get('User Type') != 'Agent') {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module = 'agent_parts';


                $section = 'parts';


                break;
            case 'agent_part':
                if ($user->get('User Type') != 'Agent') {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module = 'agent_parts';


                $section = 'part';
                $object  = 'supplier_part';

                if (isset($view_path[0])) {
                    if (is_numeric($view_path[0])) {
                        $key = $view_path[0];
                    }

                }


                break;

            case 'hr':
                if (!$user->can_view('staff')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module     = 'hr';
                $section    = 'employees';
                $parent     = 'account';
                $parent_key = 1;
                if (isset($view_path[0])) {
                    if ($view_path[0] == 'new_timesheet_record') {

                        $section = 'new_timesheet_record';
                    } elseif ($view_path[0] == 'contractors') {

                        $section = 'contractors';

                        if (!isset($_data['tab'])) {
                            $_data['tab'] = 'contractors';
                        }

                    } elseif ($view_path[0] == 'salesmen') {

                        $section = 'salesmen';


                    } elseif ($view_path[0] == 'deleted_employees') {
                        $section = 'employees';
                        if (!isset($_data['tab'])) {
                            $_data['tab'] = 'deleted.employees';
                        }

                    } elseif ($view_path[0] == 'deleted_contractors') {
                        $section = 'contractors';
                        if (!isset($_data['tab'])) {
                            $_data['tab'] = 'deleted.contractors';
                        }

                    } elseif ($view_path[0] == 'overtimes') {


                        $section = 'overtimes';


                        if (isset($view_path[0])) {
                            if (is_numeric($view_path[0])) {

                                $section    = 'overtime';
                                $object     = 'overtime';
                                $parent     = 'account';
                                $parent_key = 1;

                                $key = $view_path[0];


                            } elseif ($view_path[0] == 'new') {
                                $section = 'overtime.new';
                                $object  = 'overtime';

                            }
                        }


                    } elseif ($view_path[0] == 'organization') {

                        $section = 'organization';
                    } elseif ($view_path[0] == 'history') {
                        $section = 'hr.history';


                    } elseif ($view_path[0] == 'position') {
                        $section    = 'position';
                        $object     = 'position';
                        $parent     = 'account';
                        $parent_key = 1;
                        if (isset($view_path[1])) {

                            $key = $view_path[1];

                        }


                    } elseif ($view_path[0] == 'timesheet') {
                        $section    = 'timesheet';
                        $object     = 'timesheet';
                        $parent     = 'account';
                        $parent_key = 1;
                        if (isset($view_path[1])) {
                            if (is_numeric($view_path[1])) {
                                $key = $view_path[1];
                            }
                        }


                    } elseif ($view_path[0] == 'upload') {

                        $section    = 'upload';
                        $object     = 'upload';
                        $parent     = 'account';
                        $parent_key = 1;

                        if (isset($view_path[1])) {

                            if (is_numeric($view_path[1])) {
                                $key = $view_path[1];
                            }

                        }

                    } elseif ($view_path[0] == 'uploads') {

                        $section    = 'upload';
                        $object     = 'upload';
                        $parent     = 'employees';
                        $parent_key = 1;

                        if (isset($view_path[1])) {

                            if (is_numeric($view_path[1])) {
                                $key = $view_path[1];
                            }

                        }

                    }

                } else {


                    if (!isset($_data['tab'])) {
                        $_data['tab'] = 'employees';
                    }
                }
                break;

            case 'timesheet':
                if (!$user->can_view('staff')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module     = 'hr';
                $section    = 'timesheet';
                $object     = 'timesheet';
                $parent     = 'account';
                $parent_key = 1;

                if (isset($view_path[0])) {
                    if (is_numeric($view_path[0])) {
                        $key = $view_path[0];
                    }
                }

                break;

            case 'timesheets':
                if (!$user->can_view('staff')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module  = 'hr';
                $section = 'timesheets';
                $object  = '';

                $parent     = 'account';
                $parent_key = 1;

                if (isset($view_path[0])) {

                    if ($view_path[0] == 'year') {
                        $parent = $view_path[0];

                    } elseif ($view_path[0] == 'month') {
                        $parent = $view_path[0];
                    } elseif ($view_path[0] == 'week') {
                        $parent = $view_path[0];
                    } elseif ($view_path[0] == 'day') {
                        $parent = $view_path[0];
                    }

                }

                if (isset($view_path[1])) {
                    $parent_key = $view_path[1];
                }

                break;

            case 'employee':
                if (!$user->can_view('staff')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module     = 'hr';
                $section    = 'employee';
                $object     = 'employee';
                $parent     = 'account';
                $parent_key = 1;

                if (isset($view_path[0])) {
                    if (is_numeric($view_path[0])) {
                        $key = $view_path[0];

                        if (isset($view_path[1])) {

                            if ($view_path[1] == 'timesheet') {
                                $section    = 'timesheet';
                                $object     = 'timesheet';
                                $parent     = 'employee';
                                $parent_key = $key;
                                if (isset($view_path[2])) {
                                    if (is_numeric($view_path[2])) {
                                        $key = $view_path[2];
                                    }
                                }


                            } elseif ($view_path[1] == 'attachment') {
                                $section    = 'employee.attachment';
                                $object     = 'attachment';
                                $parent     = 'employee';
                                $parent_key = $key;
                                if (isset($view_path[2])) {
                                    if (is_numeric($view_path[2])) {
                                        $key = $view_path[2];
                                    } elseif ($view_path[2] == 'new') {
                                        $section = 'employee.attachment.new';

                                        $key = 0;
                                    }
                                }


                            } elseif ($view_path[1] == 'user') {
                                $section    = 'employee.user';
                                $object     = 'user';
                                $parent     = 'employee';
                                $parent_key = $key;
                                if (isset($view_path[2])) {
                                    if (is_numeric($view_path[2])) {
                                        $key = $view_path[2];
                                    } elseif ($view_path[2] == 'new') {
                                        $section = 'employee.user.new';

                                        $key = 0;
                                    }
                                }


                            }


                        }


                    } elseif ($view_path[0] == 'new') {
                        $section = 'employee.new';
                        $object  = '';


                    }
                }

                break;

            case 'overtime':
                if (!$user->can_view('staff')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module     = 'hr';
                $section    = 'overtime';
                $object     = 'overtime';
                $parent     = 'account';
                $parent_key = 1;

                if (isset($view_path[0])) {
                    if (is_numeric($view_path[0])) {
                        $key = $view_path[0];


                    } elseif ($view_path[0] == 'new') {
                        $section = 'overtime.new';
                        $object  = 'overtime';

                    }
                }

                break;

            case 'contractor':
                if (!$user->can_view('staff')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module     = 'hr';
                $section    = 'contractor';
                $object     = 'contractor';
                $parent     = 'account';
                $parent_key = 1;

                if (isset($view_path[0])) {
                    if (is_numeric($view_path[0])) {
                        $key = $view_path[0];
                        if (isset($view_path[1])) {


                            if ($view_path[1] == 'user') {


                                $parent     = 'staff';
                                $parent_key = $key;


                                if (isset($view_path[2])) {
                                    if (is_numeric($view_path[2])) {

                                        $section = 'contractor.user';
                                        $object  = 'user';
                                        $key     = $view_path[2];
                                    } elseif ($view_path[2] == 'new') {

                                        $section = 'contractor.user.new';
                                        $object  = 'user';
                                    }
                                }


                            }
                        }

                    } elseif ($view_path[0] == 'new') {
                        $section = 'contractor.new';
                        $object  = '';

                    }


                }

                break;
            case 'reports':


                $module  = 'reports';
                $section = 'reports';

                break;


            case 'report':


                $module = 'reports';

                if (isset($view_path[0])) {
                    if ($view_path[0] == 'billingregion_taxcategory') {
                        $section = 'billingregion_taxcategory';

                        if (isset($view_path[1]) and isset($view_path[2]) and isset($view_path[3])) {

                            if ($view_path[1] == 'invoices') {
                                $section = 'billingregion_taxcategory.invoices';
                                //$parent='billingregion_taxcategory.invoices';
                            } elseif ($view_path[1] == 'refunds') {
                                $section = 'billingregion_taxcategory.refunds';
                                //  $parent='billingregion_taxcategory.refunds';

                            }


                            $parent_key = $view_path[2].'_'.$view_path[3];


                        }


                    } elseif ($view_path[0] == 'ec_sales_list') {
                        $section = 'ec_sales_list';


                    } elseif ($view_path[0] == 'sales') {
                        $section = 'sales';


                    } elseif ($view_path[0] == 'lost_stock') {
                        $section = 'lost_stock';


                    } elseif ($view_path[0] == 'stock_given_free') {
                        $section = 'stock_given_free';


                    } elseif ($view_path[0] == 'report_orders') {
                        $section = 'report_orders';


                    } elseif ($view_path[0] == 'report_orders_components') {
                        $section = 'report_orders_components';


                    } elseif ($view_path[0] == 'report_delivery_notes') {
                        $section = 'report_delivery_notes';


                    } elseif ($view_path[0] == 'pickers') {
                        $section = 'pickers';


                    } elseif ($view_path[0] == 'packers') {
                        $section = 'packers';


                    } elseif ($view_path[0] == 'sales_representatives') {
                        $section = 'sales_representatives';

                        if (isset($view_path[1])) {
                            if (is_numeric($view_path[1])) {

                                $section = 'sales_representative';
                                $object  = 'sales_representative';
                                $key     = $view_path[1];
                            }


                        }
                    } elseif ($view_path[0] == 'prospect_agents') {
                        $section = 'prospect_agents';

                        if (isset($view_path[1])) {
                            if (is_numeric($view_path[1])) {

                                $section = 'prospect_agent';
                                $object  = 'sales_representative';
                                $key     = $view_path[1];


                                if (isset($view_path[2])) {


                                    if ($view_path[2] == 'email') {

                                        //$store_key = $arg1;

                                        $section = 'prospect_agent_email_tracking';

                                        $parent     = 'prospect_agent';
                                        $parent_key = $key;

                                        if (is_numeric($view_path[3])) {
                                            $section = 'prospect_agent_email_tracking';
                                            $object  = 'email_tracking';
                                            $key     = $view_path[3];


                                        }

                                    }
                                }


                            }


                        }
                    } elseif ($view_path[0] == 'intrastat') {


                        if (!$user->can_view('sales_reports')) {
                            $module  = 'utils';
                            $section = 'forbidden';
                            break;
                        }


                        $section = 'intrastat';

                        if (isset($view_path[1])) {
                            if ($view_path[1] == 'orders') {

                                if (isset($view_path[2]) and isset($view_path[3])) {

                                    $section = 'intrastat_orders';


                                    $extra = 'country_tariff_code|'.$view_path[2].'_'.$view_path[3];

                                }


                            } elseif ($view_path[1] == 'products') {
                                if (isset($view_path[2]) and isset($view_path[3])) {
                                    $section = 'intrastat_products';
                                    $extra   = 'country_tariff_code|'.$view_path[2].'_'.$view_path[3];
                                }
                            }

                        }

                    } elseif ($view_path[0] == 'intrastat_imports') {


                        if (!$user->can_view('sales_reports')) {
                            $module  = 'utils';
                            $section = 'forbidden';
                            break;
                        }

                        $section = 'intrastat_imports';

                        if (isset($view_path[1])) {
                            if ($view_path[1] == 'deliveries') {

                                if (isset($view_path[2]) and isset($view_path[3])) {

                                    $section = 'intrastat_deliveries';


                                    $extra = 'country_tariff_code|'.$view_path[2].'_'.$view_path[3];

                                }


                            } elseif ($view_path[1] == 'parts') {
                                if (isset($view_path[2]) and isset($view_path[3])) {
                                    $section = 'intrastat_parts';
                                    $extra   = 'country_tariff_code|'.$view_path[2].'_'.$view_path[3];
                                }
                            }

                        }

                    }
                }

                break;


            case 'profile':


                if ($user->get('User Type') == 'Staff' or $user->get('User Type') == 'Contractor') {
                    $module  = 'profile';
                    $section = 'profile';

                    $object = 'user';
                    $key    = $user->id;
                } elseif ($user->get('User Type') == 'Administrator') {
                    $module  = 'profile';
                    $section = 'profile_admin';

                    $object = 'user';
                    $key    = $user->id;
                } else {
                    if ($user->get('User Type') == 'Agent') {
                        $module  = 'agent_profile';
                        $section = 'profile';
                        $object  = 'agent';
                        $key     = $user->get('User Parent Key');

                    }
                }


                //   print_r($view_path);

                if (isset($view_path[0])) {

                    if ($view_path[0] == 'new') {

                        if (isset($view_path[1])) {

                            if ($view_path[1] == 'api_key') {


                                $parent     = 'user';
                                $parent_key = $key;
                                $section    = 'profile.api_key.new';
                                $object     = 'api_key';
                                $key        = 0;

                            }

                        }
                    } elseif ($view_path[0] == 'api_key') {

                        if (isset($view_path[1])) {

                            if (is_numeric($view_path[1])) {
                                $section = 'profile.api_key';

                                $parent     = 'user';
                                $parent_key = $key;
                                //   $section    = 'user.api_key';
                                $object = 'api_key';

                                $key = $view_path[1];
                            }

                        }
                    }

                }


                break;

            case 'material':
                $module = 'account';

                $section = 'materials';
                if (isset($view_path[0])) {
                    if (is_numeric($view_path[0])) {
                        $section = 'material';
                        $object  = 'material';
                        $key     = $view_path[0];
                    }
                }

                break;
            case 'users':
                if (!$is_setup) {

                    if (!$user->can_view('account')) {
                        $module  = 'utils';
                        $section = 'forbidden';
                        break;
                    }
                }

                $module  = 'users';
                $section = 'users';

                if (isset($view_path[0])) {

                    if ($view_path[0] == 'staff') {
                        $section = 'staff';

                    } elseif ($view_path[0] == 'groups') {
                        $section = 'groups';

                    } elseif ($view_path[0] == 'contractors') {
                        $section = 'contractors';
                    } elseif ($view_path[0] == 'warehouse') {
                        $section = 'warehouse';
                    } elseif ($view_path[0] == 'root') {
                        $section = 'root';
                    } elseif ($view_path[0] == 'suppliers') {
                        $section = 'suppliers';
                    } elseif ($view_path[0] == 'agents') {
                        $section = 'agents';
                    } elseif ($view_path[0] == 'deleted_users') {
                        if (!isset($_data['tab'])) {
                            $_data['tab'] = 'deleted.users';
                        }
                    } elseif (is_numeric($view_path[0])) {


                        $section = 'user';
                        $object  = 'user';
                        $key     = $view_path[0];

                        if (isset($view_path[1])) {

                            if ($view_path[1] == 'api_key') {

                                $parent     = 'user';
                                $parent_key = $key;
                                $section    = 'user.api_key';
                                $object     = 'api_key';


                                if (isset($view_path[2])) {

                                    if ($view_path[2] == 'new') {

                                        $section = 'user.api_key.new';

                                    } elseif (is_numeric($view_path[2])) {
                                        $key = $view_path[2];

                                    }
                                }

                            }

                        }


                    }
                }


                break;
            case 'account':


                if (!$is_setup) {

                    if (!$user->can_view('account')) {
                        $module  = 'utils';
                        $section = 'forbidden';
                        break;
                    }
                }

                $module  = 'account';
                $section = 'account';
                $object  = 'account';
                $key     = 1;

                if (isset($view_path[0])) {


                    $object = '';

                    if ($view_path[0] == '1') {

                        if (isset($view_path[1])) {


                        }


                    } elseif ($view_path[0] == 'settings') {
                        $section = 'settings';


                    } elseif ($view_path[0] == 'data_sets') {
                        $section = 'data_sets';
                        if (isset($view_path[1])) {
                            if ($view_path[1] == 'timeseries') {
                                $section = 'timeseries';
                            } elseif ($view_path[1] == 'images') {
                                $section = 'images';
                            } elseif ($view_path[1] == 'attachments') {
                                $section = 'attachments';
                            } elseif ($view_path[1] == 'osf') {
                                $section = 'osf';
                            } elseif ($view_path[1] == 'isf') {
                                $section = 'isf';
                            } elseif ($view_path[1] == 'uploads') {
                                $section = 'uploads';
                            } elseif ($view_path[1] == 'materials') {
                                $section = 'materials';
                                if (isset($view_path[2])) {
                                    if (is_numeric($view_path[2])) {
                                        $section = 'material';
                                        $object  = 'material';
                                        $key     = $view_path[2];
                                    }
                                }

                            }
                        }


                    } elseif ($view_path[0] == 'user') {

                        if (isset($view_path[1])) {

                            $parent     = 'account';
                            $parent_key = 1;
                            $section    = 'user';
                            $object     = 'user';
                            $key        = $view_path[1];


                            if (isset($view_path[2])) {

                                if ($view_path[2] == 'new') {

                                    if (isset($view_path[3])) {

                                        if ($view_path[3] == 'api_key') {

                                            $parent     = 'user';
                                            $parent_key = $key;
                                            $section    = 'user.api_key.new';
                                            $object     = 'api_key';

                                        }

                                    }
                                } elseif ($view_path[2] == 'api_key') {

                                    if (isset($view_path[3])) {

                                        if (is_numeric($view_path[3])) {

                                            $parent     = 'user';
                                            $parent_key = $key;
                                            $section    = 'user.api_key';
                                            $object     = 'api_key';

                                            $key = $view_path[3];
                                        }

                                    }
                                }

                            }


                        }


                    } elseif ($view_path[0] == 'setup') {
                        $section = 'setup';
                        $object  = 'account';
                        if (isset($view_path[1])) {
                            if ($view_path[1] == 'error') {
                                $section = 'setup_error';
                                $key     = $view_path[2];
                            } elseif ($view_path[1] == 'root_user') {
                                $section = 'setup_root_user';
                                $object  = 'user_root';

                            } elseif ($view_path[1] == 'setup_account') {
                                $section = 'setup_account';

                                $object = 'account';
                                $key    = 1;


                            } elseif ($view_path[1] == 'add_employees') {
                                $section = 'setup_add_employees';
                                $object  = 'account';
                                $key     = 1;
                            } elseif ($view_path[1] == 'add_employee') {
                                $section    = 'setup_add_employee';
                                $parent     = 'account';
                                $parent_key = 1;
                                $object     = 'employee';
                                $key        = 1;

                            } elseif ($view_path[1] == 'add_warehouse') {


                                $section    = 'setup_add_warehouse';
                                $parent     = 'account';
                                $parent_key = 1;
                                $object     = 'warehouse';
                                $key        = 1;

                            } elseif ($view_path[1] == 'add_store') {
                                $section    = 'setup_add_store';
                                $parent     = 'account';
                                $parent_key = 1;
                                $object     = 'store';
                                $key        = 1;

                            }
                        }

                    }

                }


                break;
            case 'upload':
                if (!$user->can_view('account')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module  = 'account';
                $section = 'upload';
                $object  = 'upload';

                $parent     = 'account';
                $parent_key = 1;
                if (isset($view_path[0])) {
                    if (is_numeric($view_path[0])) {
                        $key = $view_path[0];
                    }

                }
                break;
            case 'accounting':

                $module     = 'accounting_server';
                $parent     = 'account';
                $parent_key = 1;
                $section    = 'dashboard';

                if (isset($view_path[0])) {
                    if ($view_path[0] == 'dashboard') {
                        $section = 'dashboard';

                    }

                }

                break;


            case 'payment_service_providers':
                if (!$user->can_view('orders')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module     = 'accounting_server';
                $section    = 'payments';
                $parent     = 'account';
                $parent_key = 1;
                if (isset($view_path[0])) {

                    if (is_numeric($view_path[0])) {
                        $object = 'payment_service_provider';
                        $key    = $view_path[0];

                        if (isset($view_path[1])) {

                            if ($view_path[1] == 'payment_account') {
                                $section    = 'payment_account';
                                $object     = 'payment_account';
                                $parent     = 'payment_service_provider';
                                $parent_key = $key;
                                if (isset($view_path[2])) {
                                    $key = $view_path[2];
                                }

                            } elseif ($view_path[1] == 'payment') {
                                $section    = 'payment';
                                $object     = 'payment';
                                $parent     = 'payment_service_provider';
                                $parent_key = $key;
                                if (isset($view_path[2])) {
                                    $key = $view_path[2];
                                }

                            }

                        }


                    } elseif ($view_path[0] == 'all') {
                        $_data['tab'] = 'payment_service_providers';
                    }


                }
                break;


            case 'payment_service_provider':
                if (!$user->can_view('orders')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module     = 'accounting_server';
                $section    = 'payment_service_provider';
                $parent     = 'account';
                $parent_key = 1;
                if (isset($view_path[0])) {

                    if (is_numeric($view_path[0])) {
                        $object = 'payment_service_provider';
                        $key    = $view_path[0];

                        if (isset($view_path[1])) {

                            if ($view_path[1] == 'payment_account') {
                                $section    = 'payment_account';
                                $object     = 'payment_account';
                                $parent     = 'payment_service_provider';
                                $parent_key = $key;
                                if (isset($view_path[2])) {
                                    $key = $view_path[2];
                                }

                            } elseif ($view_path[1] == 'payment') {
                                $section    = 'payment';
                                $object     = 'payment';
                                $parent     = 'payment_service_provider';
                                $parent_key = $key;
                                if (isset($view_path[2])) {
                                    $key = $view_path[2];
                                }

                            }

                        }


                    }


                }
                break;
            case 'payment_account':

                // todo improve this permissions
                /*
                if (!$user->can_view('payments')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }
                */

                $module  = 'accounting_server';
                $section = 'payment_account';
                $parent  = 'account';
                if (isset($view_path[0])) {

                    if (is_numeric($view_path[0])) {


                        $object = 'payment_account';
                        $key    = $view_path[0];

                        if (isset($view_path[1])) {

                            if (is_numeric($view_path[1])) {
                                $module     = 'accounting';
                                $object     = 'payment_account';
                                $key        = $view_path[1];
                                $parent     = 'store';
                                $parent_key = $view_path[0];
                            } elseif ($view_path[1] == 'payment') {
                                $section    = 'payment';
                                $object     = 'payment';
                                $parent     = 'payment_account';
                                $parent_key = $view_path[0];

                                if (isset($view_path[2])) {
                                    if (is_numeric($view_path[2])) {
                                        $key = $view_path[2];
                                    }


                                }

                            }

                        }
                    }


                }


                break;
            case 'payment':


                /*
                if (!$user->can_view('orders')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }
                */

                $module     = 'accounting_server';
                $section    = 'payment';
                $object     = 'payment';
                $parent     = 'account';
                $parent_key = 1;
                if (isset($view_path[0])) {
                    if (is_numeric($view_path[0]) and isset($view_path[1]) and is_numeric($view_path[1])) {
                        $module = 'payments';

                        $key        = $view_path[1];
                        $parent     = 'store';
                        $parent_key = $view_path[0];
                    } elseif (is_numeric($view_path[0])) {
                        $key = $view_path[0];

                    }

                }
                break;
            case 'payment_accounts':
                if (!$user->can_view('orders')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module     = 'accounting_server';
                $section    = 'payment_accounts';
                $parent     = 'account';
                $parent_key = 1;

                if (isset($view_path[0])) {
                    if (is_numeric($view_path[0]) and isset($view_path[1]) and is_numeric($view_path[1])) {
                        $section    = 'payment_account';
                        $module     = 'accounting';
                        $object     = 'payment_account';
                        $key        = $view_path[1];
                        $parent     = 'store';
                        $parent_key = $view_path[0];


                        if (isset($view_path[2]) and $view_path[2] == 'payment' and isset($view_path[3]) and is_numeric($view_path[3])) {
                            $section    = 'payment';
                            $object     = 'payment';
                            $key        = $view_path[3];
                            $parent     = 'store_payment_account';
                            $parent_key = $parent_key.'_'.$view_path[1];
                        }


                    } elseif (is_numeric($view_path[0])) {

                        $section      = 'payments';
                        $_data['tab'] = 'store.payment_accounts';

                        $module     = 'accounting';
                        $parent     = 'store';
                        $parent_key = $view_path[0];


                    } elseif ($view_path[0] == 'all') {

                        $section      = 'payments';
                        $_data['tab'] = 'account.payment_accounts';
                    }

                } else {
                    $module = 'payments';
                    $parent = 'store';
                    if ($user->data['User Hooked Store Key'] and in_array(
                            $user->data['User Hooked Store Key'], $user->stores
                        )) {
                        $parent_key = $user->data['User Hooked Store Key'];
                    } else {
                        $_tmp       = $user->stores;
                        $parent_key = array_shift($_tmp);
                    }

                }
                break;

            case 'payments':
                if (!$user->can_view('orders')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module     = 'accounting_server';
                $section    = 'payments';
                $parent     = 'account';
                $parent_key = 1;


                if (isset($view_path[0])) {
                    if (is_numeric($view_path[0]) and isset($view_path[1]) and is_numeric($view_path[1])) {
                        $module  = 'accounting';
                        $section = 'payment';

                        $object     = 'payment';
                        $key        = $view_path[1];
                        $parent     = 'store';
                        $parent_key = $view_path[0];


                    } elseif (is_numeric($view_path[0])) {
                        $module       = 'accounting';
                        $parent       = 'store';
                        $parent_key   = $view_path[0];
                        $_data['tab'] = 'store.payments';

                    } elseif ($view_path[0] == 'all') {
                        $_data['tab'] = 'account.payments';
                    } elseif ($view_path[0] == 'per_store') {
                        $_data['tab'] = 'payments_group_by_store';
                    }

                } else {

                    $module = 'accounting';

                    $parent = 'store';
                    if ($user->data['User Hooked Store Key'] and in_array($user->data['User Hooked Store Key'], $user->stores)) {
                        $parent_key = $user->data['User Hooked Store Key'];
                    } else {
                        $_tmp       = $user->stores;
                        $parent_key = array_shift($_tmp);
                    }

                }
                break;
            case 'credits':
                if (!$user->can_view('orders')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module     = 'accounting_server';
                $section    = 'credits';
                $parent     = 'account';
                $parent_key = 1;


                if (isset($view_path[0])) {

                    if (is_numeric($view_path[0])) {
                        $module     = 'accounting';
                        $parent     = 'store';
                        $parent_key = $view_path[0];

                    }

                } else {

                    $module = 'payments';

                    $parent = 'store';
                    if ($user->data['User Hooked Store Key'] and in_array($user->data['User Hooked Store Key'], $user->stores)) {
                        $parent_key = $user->data['User Hooked Store Key'];
                    } else {
                        $_tmp       = $user->stores;
                        $parent_key = array_shift($_tmp);
                    }

                }
                break;
            case 'timeseries':
                if (!$user->can_view('account')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module  = 'account';
                $section = 'timeserie';
                if (isset($view_path[0])) {
                    if (is_numeric($view_path[0])) {


                        $object = 'timeseries';
                        $key    = $view_path[0];

                    }
                }
                break;

            case 'pending_delivery_notes':


                $module  = 'delivery_notes_server';
                $section = 'pending_delivery_notes';

                break;

            case 'fire':
                $module  = 'utils';
                $section = 'fire';

            default:

                break;
        }

    }


    if ($section == 'not_found') {
        $module = 'utils';
    }


    // print_r($_data);


    list($tab, $subtab) = parse_tabs($module, $section, $_data, $modules);


    $state = array(
        'request'    => $request,
        'module'     => $module,
        'section'    => $section,
        'tab'        => $tab,
        'subtab'     => $subtab,
        'parent'     => $parent,
        'parent_key' => $parent_key,
        'object'     => $object,
        'key'        => $key,
        'extra'      => $extra,
        'extra_tab'  => $extra_tab,
        'title'      => parse_title($module, $section, $modules)
    );


    if (isset($_data['otf'])) {
        $state['otf'] = $_data['otf'];
    }

    if (isset($metadata)) {
        $state['metadata'] = $metadata;
    }

    if (isset($store_key)) {
        $state['store_key'] = $store_key;
    }


    return $state;

}


function parse_title($module, $section, $modules) {


    if (isset($modules[$module]['sections'][$section]['title'])) {
        return $modules[$module]['sections'][$section]['title'];
    } else {

        if (isset($modules[$module]['sections'][$section]['label'])) {
            return $modules[$module]['sections'][$section]['label'];
        } else {


            return '';
        }

    }


}


function parse_tabs($module, $section, $_data, $modules) {

    global $session;


    if (isset($_data['subtab'])) {

        // print_r($_data);

        $subtab = $_data['subtab'];
        $tab    = $modules[$module]['sections'][$section]['subtabs_parent'][$subtab];

    } elseif (isset($_data['tab'])) {

        $tab    = $_data['tab'];
        $subtab = parse_subtab($module, $section, $tab, $modules);
    } else {


        $tmp = $session->get('state');


        if (!empty($tmp[$module][$section]['tab'])) {


            $tab = $tmp[$module][$section]['tab'];

        } else {


            if (!isset($modules[$module]['sections'][$section]['tabs']) or !is_array($modules[$module]['sections'][$section]['tabs']) or count($modules[$module]['sections'][$section]['tabs']) == 0) {
                print "problem with M: $module S: >$section<";
            }


            $tab = array_keys($modules[$module]['sections'][$section]['tabs'])[0];
        }


        $subtab = parse_subtab($module, $section, $tab, $modules);
    }

    return array(
        $tab,
        $subtab
    );

}


function parse_subtab($module, $section, $tab, $modules) {

    global $session;

    if (isset($modules[$module]['sections'][$section]['tabs'][$tab]['subtabs'])) {


        $_session = $session->get('tab_state');

        if (isset ($_session[$tab])) {
            $subtab = $_session[$tab];
        } else {

            $subtab = array_keys($modules[$module]['sections'][$section]['tabs'][$tab]['subtabs'])[0];

        }
    } else {
        $subtab = '';
    }

    return $subtab;
}


?>
