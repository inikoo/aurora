<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 August 2015 23:49:27 GMT+8 Singapore
 Moved: 3 October 2015 at 08:57:36 BST Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/


function parse_request($_data, $db, $modules, $account = '', $user = '') {


    $request = $_data['request'];

    $request = preg_replace('/\/+/', '/', $request);
    if ($request == '/') {
        $request = 'dashboard';
    }

    $original_request = preg_replace('/^\//', '', $request);
    $view_path        = preg_split('/\//', $original_request);

    $module     = 'utils';
    $section    = 'not_found';
    $tab        = 'not_found';
    $tab_parent = '';
    $subtab     = '';
    $parent     = 'account';
    $parent_key = 1;
    $object     = '';
    $key        = '';


    $count_view_path = count($view_path);
    $shorcut         = false;
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
                $key  = 1;


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


                            }


                        }

                    } elseif ($view_path[0] == 'new') {
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
                        )
                    ) {
                        $key = $user->data['User Hooked Store Key'];
                    } else {
                        $_tmp = $user->stores;
                        $key  = array_shift($_tmp);
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
                                            $metadata
                                                               = $parent_categories;
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
                                                        )
                                                    ) {

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
                                                ) == 'Root'
                                            ) {
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
                                                        )
                                                    ) {

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

                                                            $key
                                                                = $view_path[4];
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
                                    if ($view_path[1] == 'image') {

                                        if (isset($view_path[2])) {

                                            if (is_numeric($view_path[2])) {
                                                $section    = 'product.image';
                                                $object     = 'image.subject';
                                                $parent_key = $key;
                                                $key        = $view_path[2];
                                                $parent     = 'product';

                                            }

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
                                            $metadata
                                                               = $parent_categories;
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
                                                        )
                                                    ) {

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
                                                ) == 'Root'
                                            ) {
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
                                                        )
                                                    ) {

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

                                                            $key
                                                                = $view_path[4];
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


                                        if (isset($view_path[2])) {

                                            if ($view_path[2] == 'image') {

                                                if (isset($view_path[3])) {

                                                    if (is_numeric(
                                                        $view_path[3]
                                                    )) {
                                                        $section
                                                                    = 'product.image';
                                                        $object
                                                                    = 'image.subject';
                                                        $parent_key = $key;
                                                        $key
                                                                    = $view_path[3];
                                                        $parent     = 'product';

                                                    }

                                                }

                                            }


                                        }


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
                                            $metadata
                                                               = $parent_categories;
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
                                                        )
                                                    ) {

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
                                                ) == 'Root'
                                            ) {
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
                                                        )
                                                    ) {

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

                                                            $key
                                                                = $view_path[4];
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
                            $module  = 'invoices';
                            $section = 'category';

                            if ($category->get('Category Branch Type') == 'Root') {
                                $parent     = 'store';
                                $parent_key = $category->get(
                                    'Category Store Key'
                                );
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

                            exit('error category '.$category->get(
                                    'Category Subject'
                                ).' not set up in parse_request.php');
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

                $module  = 'websites';
                $section = 'websites';

                break;
            case 'website':
                if (!$user->can_view('sites')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module  = 'websites';
                $section = 'website';
                $object  = 'website';
                $key     = $view_path[0];


                if (isset($view_path[1])) {
                    if ($view_path[1] == 'page') {
                        $section    = 'page';
                        $object     = 'page';
                        $parent     = 'website';
                        $parent_key = $key;

                        if (is_numeric($view_path[2])) {
                            $key = $view_path[2];
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
            case 'node':
                if (!$user->can_view('sites')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module  = 'websites';
                $section = 'website.node';
                $object  = 'node';
                $key     = $view_path[0];
                if (isset($view_path[1])) {
                    if ($view_path[1] == 'version') {
                        $section    = 'page';
                        $object     = 'page';
                        $parent     = 'node';
                        $parent_key = $key;

                        if (is_numeric($view_path[2])) {
                            $key = $view_path[2];
                        }


                    } elseif ($view_path[1] == 'user') {
                        $section    = 'website.user';
                        $object     = 'user';
                        $parent     = 'page';
                        $parent_key = $key;

                        if (is_numeric($view_path[2])) {
                            $key = $view_path[2];
                        }


                    } elseif ($view_path[1] == 'node') {
                        $section    = 'website.node';
                        $object     = 'node';
                        $parent     = 'node';
                        $parent_key = $key;

                        if (is_numeric($view_path[2])) {
                            $key = $view_path[2];
                        }


                    }


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

                if ($user->get('User Type') == 'Agent') {
                    $module     = 'agent_suppliers';
                    $parent     = 'agent';
                    $parent_key = $user->get('User Parent Key');
                } else {
                    $module     = 'suppliers';
                    $parent     = 'account';
                    $parent_key = 1;
                }

                $section = 'supplier';


                $object = 'supplier';

                if (isset($view_path[0])) {
                    if (is_numeric($view_path[0])) {
                        $key = $view_path[0];


                        if (isset($view_path[1])) {

                            if ($view_path[1] == 'part') {
                                $section    = 'supplier_part';
                                $parent     = 'supplier';
                                $parent_key = $key;
                                $object     = 'supplier_part';
                                if (isset($view_path[2])) {
                                    if (is_numeric($view_path[2])) {

                                        $key = $view_path[2];


                                        if (isset($view_path[3])) {


                                            if ($view_path[3] == 'order') {
                                                $section = 'order';

                                                $parent     = 'supplier_part';
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

                                                                        $parent
                                                                            = 'PurchaseOrder';
                                                                        $parent_key
                                                                            = $view_path[4];
                                                                        $object
                                                                            = 'PurchaseOrderItem';
                                                                        $key
                                                                            = $view_path[6];
                                                                        $section
                                                                            = 'supplier.order.item';


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
                                        $section = 'supplier_part.new';
                                    } elseif ($view_path[2] == 'hk') {
                                        $object  = 'supplier_part_historic';
                                        $section = 'supplier_part.historic';
                                        if (isset($view_path[3])) {
                                            if (is_numeric($view_path[3])) {
                                                $key = $view_path[3];
                                            }
                                        }


                                    }
                                }


                            } elseif ($view_path[1] == 'order') {
                                $section = 'order';

                                $parent     = 'supplier';
                                $parent_key = $view_path[0];
                                $object     = 'purchase_order';

                                if (isset($view_path[2])) {
                                    if (is_numeric($view_path[2])) {
                                        $key = $view_path[2];


                                        if (isset($view_path[3])) {
                                            if ($view_path[3] == 'item') {

                                                if (isset($view_path[4])) {
                                                    if (is_numeric(
                                                        $view_path[4]
                                                    )) {

                                                        $parent
                                                            = 'PurchaseOrder';
                                                        $parent_key
                                                            = $view_path[2];
                                                        $object
                                                            = 'PurchaseOrderItem';
                                                        $key
                                                            = $view_path[4];
                                                        $section
                                                            = 'supplier.order.item';


                                                    }
                                                }


                                            }
                                        }

                                    }

                                }


                            } elseif ($view_path[1] == 'delivery') {
                                $section = 'delivery';

                                $parent     = 'supplier';
                                $parent_key = $view_path[0];
                                $object     = 'supplierdelivery';

                                if (isset($view_path[2])) {
                                    if (is_numeric($view_path[2])) {
                                        $key = $view_path[2];


                                    }

                                }


                            } elseif ($view_path[1] == 'upload') {
                                $module     = 'account';
                                $section    = 'upload';
                                $parent     = 'supplier';
                                $parent_key = $key;
                                $object     = 'upload';
                                if (isset($view_path[2])) {
                                    if (is_numeric($view_path[2])) {

                                        $key = $view_path[2];
                                    }
                                }


                            } else {
                                if ($view_path[1] == 'user') {


                                    $parent     = 'supplier';
                                    $parent_key = $key;


                                    if (isset($view_path[2])) {
                                        if (is_numeric($view_path[2])) {

                                            $section = 'supplier.user';
                                            $object  = 'user';
                                            $key     = $view_path[2];
                                        } elseif ($view_path[2] == 'new') {

                                            $section = 'supplier.user.new';
                                            $object  = 'user';
                                        }
                                    }


                                } elseif ($view_path[1] == 'attachment') {
                                    $section    = 'supplier.attachment';
                                    $object     = 'attachment';
                                    $parent     = 'supplier';
                                    $parent_key = $key;
                                    if (isset($view_path[2])) {
                                        if (is_numeric($view_path[2])) {
                                            $key = $view_path[2];
                                        } elseif ($view_path[2] == 'new') {
                                            $section
                                                = 'supplier.attachment.new';

                                            $key = 0;
                                        }
                                    }


                                }
                            }


                        }

                    }

                }


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

                        if ($user->get('User Type') == 'Agent' and $user->get(
                                'User Parent Key'
                            ) != $key
                        ) {
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
                                                    $object
                                                                = 'PurchaseOrderItem';
                                                    $key        = $view_path[4];
                                                    $section
                                                                = 'agent.order.item';


                                                }
                                            }


                                        }
                                    }


                                }


                            } elseif ($view_path[1] == 'delivery') {
                                $section = 'delivery';

                                $parent     = 'agent';
                                $parent_key = $view_path[0];
                                $object     = 'supplierdelivery';

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
                        )
                    ) {
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
                            )
                        ) {
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
                            )
                        ) {
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


                        } elseif ($view_path[0] == 'lists') {
                            $section = 'lists';
                        } elseif ($view_path[0] == 'categories') {
                            $section = 'categories';

                        } elseif ($view_path[0] == 'category') {

                            $section = 'category';

                            $object = 'category';

                            if (isset($view_path[1]) and is_numeric(
                                    $view_path[1]
                                )
                            ) {

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

            case 'receipts':
                $module  = 'account';
                $section = 'orders_index';
                break;
            case 'orders':

                if ($user->get('User Type') == 'Staff' or $user->get(
                        'User Type'
                    ) == 'Contractor'
                ) {


                    if (!$user->can_view('orders')) {
                        $module  = 'utils';
                        $section = 'forbidden';
                        break;
                    }

                    $module = 'orders';
                    if ($count_view_path == 0) {
                        $section = 'orders';

                        $parent = 'store';
                        if ($user->data['User Hooked Store Key'] and in_array(
                                $user->data['User Hooked Store Key'], $user->stores
                            )
                        ) {
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


                    } elseif (is_numeric($arg1)) {
                        $section    = 'orders';
                        $parent     = 'store';
                        $parent_key = $arg1;

                        if (isset($view_path[0]) and is_numeric(
                                $view_path[0]
                            )
                        ) {
                            $section = 'order';
                            $object  = 'order';

                            $parent     = 'store';
                            $parent_key = $arg1;
                            $key        = $view_path[0];

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

                $module = 'invoices';
                if ($count_view_path == 0) {
                    $section = 'invoices';
                    $parent  = 'store';
                    if ($user->data['User Hooked Store Key'] and in_array(
                            $user->data['User Hooked Store Key'], $user->stores
                        )
                    ) {
                        $parent_key = $user->data['User Hooked Store Key'];
                    } else {
                        $_tmp       = $user->stores;
                        $parent_key = array_shift($_tmp);
                    }


                } else {
                    $arg1 = array_shift($view_path);
                    if ($arg1 == 'all') {
                        $module     = 'invoices_server';
                        $section    = 'invoices';
                        $parent     = 'account';
                        $parent_key = 1;

                        if (isset($view_path[0])) {

                            if ($view_path[0] == 'categories') {
                                $section = 'categories';

                            } elseif ($view_path[0] == 'category') {

                                $section = 'category';

                                $object = 'category';

                                if (isset($view_path[1]) and is_numeric(
                                        $view_path[1]
                                    )
                                ) {
                                    $key = $view_path[1];

                                    include_once 'class.Category.php';
                                    $category = new Category($key);

                                    $parent     = 'category';
                                    $parent_key = $category->get(
                                        'Category Parent Key'
                                    );

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
                        )
                    ) {
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

                $module = 'orders';
                if (isset($view_path[0])) {

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
            case 'invoice':
                if (!$user->can_view('orders')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

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

                break;
            case 'marketing':
                if (!$user->can_view('marketing')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module = 'marketing';
                if ($count_view_path == 0) {
                    $section = 'deals';
                    $parent  = 'store';
                    if ($user->data['User Hooked Store Key'] and in_array(
                            $user->data['User Hooked Store Key'], $user->stores
                        )
                    ) {
                        $parent_key = $user->data['User Hooked Store Key'];
                    } else {
                        $_tmp       = $user->stores;
                        $parent_key = array_shift($_tmp);
                    }

                }
                $arg1 = array_shift($view_path);
                if ($arg1 == 'all') {
                    $module  = 'marketing_server';
                    $section = 'marketing';


                } elseif (is_numeric($arg1)) {

                    $parent     = 'store';
                    $parent_key = $arg1;

                    if (isset($view_path[0])) {


                    } else {

                        $section = 'deals';
                    }

                }
                break;
            case 'campaigns':
                $module = 'marketing';

                if (isset($view_path[0])) {
                    $section = 'campaigns';


                    $parent     = 'store';
                    $parent_key = $view_path[0];


                    if (isset($view_path[1])) {

                        if (is_numeric($view_path[1])) {
                            $section = 'campaign';
                            $key     = $view_path[1];
                            $object  = 'campaign';

                            if (isset($view_path[2])) {
                                if ($view_path[2] == 'deal') {

                                    $section    = 'deal';
                                    $object     = 'deal';
                                    $parent     = 'campaign';
                                    $parent_key = $view_path[1];
                                    if (isset($view_path[3])) {
                                        if (is_numeric($view_path[3])) {
                                            $key = $view_path[3];
                                        } elseif ($view_path[3] == 'new') {


                                        }

                                    }
                                }


                            }


                        } elseif ($view_path[1] == 'new') {
                            $section = 'campaign.new';
                        }


                    }

                }
                break;
            case 'deals':
                $module = 'marketing';

                if (isset($view_path[0])) {
                    $section = 'deal';
                    $object  = 'deal';
                    $key     = $view_path[0];
                    $parent  = 'store';


                    if (isset($view_path[1])) {

                        $parent     = 'store';
                        $parent_key = $view_path[0];
                        if (is_numeric($view_path[1])) {
                            $key = $view_path[1];

                        } elseif ($view_path[1] == 'new') {
                            $section = 'campaign.new';
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
                            if ($view_path[1] == 'locations') {
                                $section = 'locations';
                                $object  = '';

                                $parent     = 'warehouse';
                                $parent_key = $key;

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
                                            $metadata
                                                               = $parent_categories;
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
                                                        )
                                                    ) {

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
                                                ) == 'Root'
                                            ) {
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
                                                        )
                                                    ) {

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

                                                            $key
                                                                = $view_path[4];
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
                        )
                    ) {
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
                if (!$user->can_view('suppliers')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

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

                            } elseif ($view_path[1] == 'batches') {
                                $section = 'batches';

                            } elseif ($view_path[1] == 'materials') {
                                $section = 'materials';

                            } elseif ($view_path[1] == 'parts') {
                                $section = 'supplier_parts';

                            } elseif ($view_path[1] == 'part') {

                                $section    = 'supplier_part';
                                $parent     = 'supplier_production';
                                $parent_key = $key;
                                $object     = 'supplier_part';
                                if (isset($view_path[2])) {
                                    if (is_numeric($view_path[2])) {

                                        $key = $view_path[2];


                                        if (isset($view_path[3])) {


                                            if ($view_path[3] == 'order') {
                                                $section = 'order';

                                                $parent     = 'supplier_part';
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

                                                                        $parent
                                                                            = 'PurchaseOrder';
                                                                        $parent_key
                                                                            = $view_path[4];
                                                                        $object
                                                                            = 'PurchaseOrderItem';
                                                                        $key
                                                                            = $view_path[6];
                                                                        $section
                                                                            = 'supplier.order.item';


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
                                        $section = 'supplier_part.new';
                                    } elseif ($view_path[2] == 'hk') {
                                        $object  = 'supplier_part_historic';
                                        $section = 'supplier_part.historic';
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

                if ($user->get('User Type') == 'Staff' or $user->get(
                        'User Type'
                    ) == 'Contractor'
                ) {
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
                                                    )
                                                ) {

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
                                            ) == 'Root'
                                        ) {
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
                                                    )
                                                ) {

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

                $module = 'suppliers';


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
                $parent     = 'agent';
                $parent_key = $user->get('User Parent Key');

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
                if (!$user->can_view('reports')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module  = 'reports';
                $section = 'reports';

                break;


            case 'report':
                if (!$user->can_view('reports')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

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


                    }

                }
                break;


            case 'profile':
                if ($user->get('User Type') == 'Staff' or $user->get(
                        'User Type'
                    ) == 'Contractor'
                ) {
                    $module  = 'profile';
                    $section = 'profile';

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
            case 'account':
                if (!$user->can_view('account')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module  = 'account';
                $section = 'account';
                $object  = 'account';
                $key     = 1;

                if (isset($view_path[0])) {


                    $object = '';
                    if ($view_path[0] == 'users') {
                        $section = 'users';

                        if (isset($view_path[1])) {

                            if ($view_path[1] == 'staff') {
                                $section = 'staff';

                            } elseif ($view_path[1] == 'contractors') {
                                $section = 'contractors';
                            } elseif ($view_path[1] == 'warehouse') {
                                $section = 'warehouse';
                            } elseif ($view_path[1] == 'root') {
                                $section = 'root';
                            } elseif ($view_path[1] == 'suppliers') {
                                $section = 'suppliers';
                            } elseif ($view_path[1] == 'agents') {
                                $section = 'agents';

                            }
                        }

                    } elseif ($view_path[0] == 'deleted_users') {
                        $section = 'users';
                        if (!isset($_data['tab'])) {
                            $_data['tab'] = 'account.deleted.users';
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

            case 'payment_service_providers':
                if (!$user->can_view('orders')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module     = 'payments';
                $section    = 'payment_service_providers';
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
            case 'payment_service_provider':
                if (!$user->can_view('orders')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module     = 'payments';
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
                if (!$user->can_view('orders')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module  = 'payments';
                $section = 'payment_account';
                $parent  = 'account';
                if (isset($view_path[0])) {


                    if (is_numeric($view_path[0]) and isset($view_path[1]) and is_numeric($view_path[1])) {
                        $object     = 'payment_account';
                        $key        = $view_path[1];
                        $parent     = 'store';
                        $parent_key = $view_path[0];
                    } elseif (is_numeric($view_path[0])) {
                        $object = 'payment_account';
                        $key    = $view_path[0];

                    }

                }


                /*

				if ( is_numeric($view_path[0])) {
					$object='payment_account';
					$key=$view_path[0];

					if (isset($view_path[1])) {

						if ($view_path[1]=='payment') {
							$section='payment';
							$object='payment';
							$parent='payment_account';
							$parent_key=$key;
							if (isset($view_path[2])) {
								$key=$view_path[2];
							}

						}

					}



				}

*/


                break;
            case 'payment':
                if (!$user->can_view('orders')) {
                    $module  = 'utils';
                    $section = 'forbidden';
                    break;
                }

                $module     = 'payments';
                $section    = 'payment';
                $object     = 'payment';
                $parent     = 'account';
                $parent_key = 1;
                if (isset($view_path[0])) {
                    if (is_numeric($view_path[0]) and isset($view_path[1]) and is_numeric($view_path[1])) {

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

                $module     = 'payments';
                $section    = 'payment_accounts';
                $parent     = 'account';
                $parent_key = 1;

                if (isset($view_path[0])) {
                    if (is_numeric($view_path[0]) and isset($view_path[1]) and is_numeric($view_path[1])) {
                        $object     = 'payment_account';
                        $key        = $view_path[1];
                        $parent     = 'store';
                        $parent_key = $view_path[0];
                    } elseif (is_numeric($view_path[0])) {
                        $parent     = 'store';
                        $parent_key = $view_path[0];

                    }

                } else {

                    $parent = 'store';
                    if ($user->data['User Hooked Store Key'] and in_array(
                            $user->data['User Hooked Store Key'], $user->stores
                        )
                    ) {
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

                $module     = 'payments';
                $section    = 'payments';
                $parent     = 'account';
                $parent_key = 1;

                if (isset($view_path[0])) {
                    if (is_numeric($view_path[0]) and isset($view_path[1]) and is_numeric($view_path[1])) {
                        $object     = 'payment';
                        $key        = $view_path[1];
                        $parent     = 'store';
                        $parent_key = $view_path[0];
                    } elseif (is_numeric($view_path[0])) {
                        $parent     = 'store';
                        $parent_key = $view_path[0];

                    }

                } else {

                    $parent = 'store';
                    if ($user->data['User Hooked Store Key'] and in_array(
                            $user->data['User Hooked Store Key'], $user->stores
                        )
                    ) {
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

            case 'fire':
                $module  = 'utils';
                $section = 'fire';

            default:

                break;
        }

    }

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
    );


    if (isset($_data['otf'])) {
        $state['otf'] = $_data['otf'];
    }

    if (isset($metadata)) {
        $state['metadata'] = $metadata;
    }


    //print_r($state);
    return $state;

}


function parse_tabs($module, $section, $_data, $modules) {


    $subtab = '';
    if (isset($_data['subtab'])) {
        $subtab = $_data['subtab'];
        $tab
                = $modules[$module]['sections'][$section]['subtabs_parent'][$subtab];
    } elseif (isset($_data['tab'])) {

        $tab    = $_data['tab'];
        $subtab = parse_subtab($module, $section, $tab, $modules);
    } else {

        if (isset ($_SESSION['state'][$module][$section]['tab'])) {
            $tab = $_SESSION['state'][$module][$section]['tab'];
            //Special default tabs

            if ($module == 'suppliers') {
                if ($section == 'order') {
                    if ($tab == 'supplier.order.all_supplier_parts') {
                        $tab = 'supplier.order.items';
                    }
                }
            }

        } else {


            if (!isset($modules[$module]['sections'][$section]['tabs']) or !is_array($modules[$module]['sections'][$section]['tabs']) or count($modules[$module]['sections'][$section]['tabs']) == 0) {
                print "problem with M: $module S: $section";
            }
            $tab = each($modules[$module]['sections'][$section]['tabs'])['key'];
        }
        $subtab = parse_subtab($module, $section, $tab, $modules);
    }

    return array(
        $tab,
        $subtab
    );

}


function parse_subtab($module, $section, $tab, $modules) {

    if (isset($modules[$module]['sections'][$section]['tabs'][$tab]['subtabs'])) {
        if (isset ($_SESSION['tab_state'][$tab])) {
            $subtab = $_SESSION['tab_state'][$tab];
        } else {
            $subtab = each(
                          $modules[$module]['sections'][$section]['tabs'][$tab]['subtabs']
                      )['key'];

        }
    } else {
        $subtab = '';
    }

    return $subtab;
}


?>
