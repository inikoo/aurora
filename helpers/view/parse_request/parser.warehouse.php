<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 23 Jul 2021 21:29:20 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */
include_once 'helpers/view/parse_request/parser.class.php';

class parser_warehouse extends parser_request {

    function __construct($_data, $db) {
        $this->module  = 'warehouses';
        $this->section = 'warehouse';
        parent::__construct(...func_get_args());



    }

    function authorization() {

        if ($this->user->can_view('locations')) {
            $this->authorized = true;
        }

    }


    function parse($view_path) {


        if (isset($view_path[0])) {

            if (is_numeric($view_path[0])) {
                $this->object = 'warehouse';
                $this->key    = $view_path[0];
                if (isset($view_path[1])) {
                    if ($view_path[1] == 'dashboard') {

                        $this->section = 'dashboard';


                    } elseif ($view_path[1] == 'feedback') {

                        $this->section = 'feedback';


                    } elseif ($view_path[1] == 'delivery_notes') {


                        if (!$this->user->can_view('orders')) {
                            $this->authorized = false;
                        }

                        $this->section = 'delivery_notes';
                        $this->object  = '';

                        $this->parent     = 'warehouse';
                        $this->parent_key = $this->key;
                    } elseif ($view_path[1] == 'returns') {

                        $this->section = 'returns';

                        $this->parent     = 'warehouse';
                        $this->parent_key = $this->key;

                        if (isset($view_path[2])) {
                            $this->section = 'return';
                            $this->object  = 'supplierdelivery';

                            if (is_numeric($view_path[2])) {
                                if (is_numeric($view_path[2])) {
                                    $this->key = $view_path[2];
                                }

                            }


                        }


                    } elseif ($view_path[1] == 'leakages') {


                        $this->section = 'leakages';
                        $this->object  = '';

                        $this->parent     = 'warehouse';
                        $this->parent_key = $this->key;


                        if (isset($view_path[2])) {
                            if (is_numeric($view_path[2])) {
                                $this->key = $view_path[2];

                                if (isset($view_path[3])) {
                                    if (is_numeric($view_path[3])) {
                                        $this->section    = 'timeseries_record';
                                        $this->parent     = 'timeseries';
                                        $this->parent_key = $view_path[2];
                                        $this->object     = 'timeseries_record';

                                        $this->key = $view_path[3];


                                    }

                                }


                            }

                        }


                    } elseif ($view_path[1] == 'locations') {
                        $this->section = 'locations';
                        $this->object  = '';

                        $this->parent     = 'warehouse';
                        $this->parent_key = $this->key;


                        if (isset($view_path[2])) {
                            if ($view_path[2] == 'upload') {

                                $this->section = 'upload';
                                $this->object  = 'upload';


                                if (isset($view_path[3])) {

                                    if (is_numeric($view_path[3])) {
                                        $this->key = $view_path[3];
                                    }

                                }

                            }

                        } else {
                            $this->tab = 'warehouse.locations';
                        }


                    } elseif ($view_path[1] == 'deleted_locations') {
                        $this->section = 'locations';
                        $this->object  = '';

                        $this->parent     = 'warehouse';
                        $this->parent_key = $this->key;


                        $this->tab = 'warehouse.deleted_locations';


                    } elseif ($view_path[1] == 'areas') {
                        $this->section = 'locations';


                        $this->object = '';

                        $this->parent     = 'warehouse';
                        $this->parent_key = $this->key;

                        if (isset($view_path[2])) {
                            $this->object = 'warehouse_area';
                            if ($view_path[2] == 'new') {

                                $this->section = 'warehouse_area.new';
                                $this->key     = 0;


                            } elseif (is_numeric($view_path[2])) {
                                $this->key     = $view_path[2];
                                $this->section = 'warehouse_area';

                                if (isset($view_path[3])) {

                                    if ($view_path[3] == 'location') {


                                        if (isset($view_path[4])) {

                                            $this->parent     = 'warehouse_area';
                                            $this->parent_key = $this->key;
                                            $this->object     = 'location';
                                            if ($view_path[4] == 'new') {


                                                $this->section = 'location.new';
                                                $this->key     = 0;


                                            } elseif (is_numeric($view_path[4])) {

                                                $this->section = 'location';
                                                $this->key     = $view_path[4];
                                            }

                                        }

                                    } elseif ($view_path[3] == 'upload') {


                                        $this->parent     = 'warehouse_area';
                                        $this->parent_key = $this->key;


                                        $this->section = 'upload';

                                        $this->object = 'upload';
                                        if (isset($view_path[4])) {
                                            if (is_numeric($view_path[4])) {

                                                $this->key = $view_path[4];
                                            }
                                        }

                                    }


                                }


                            } elseif ($view_path[2] == 'all') {
                                $this->object = '';
                                $this->tab    = 'warehouse.areas';

                            } elseif ($view_path[2] == 'upload') {
                                $this->section = 'upload';

                                $this->object = 'upload';
                                if (isset($view_path[3])) {
                                    if (is_numeric($view_path[3])) {

                                        $this->key = $view_path[3];
                                    }
                                }

                            }


                        } else {
                            $this->tab = 'warehouse.areas';
                        }


                    } elseif ($view_path[1] == 'area') {
                        $this->section = 'warehouse_area';
                        $this->object  = '';

                        $this->parent     = 'warehouse';
                        $this->parent_key = $this->key;

                        if (isset($view_path[2])) {
                            $this->object = 'warehouse_area';
                            if ($view_path[2] == 'new') {

                                $this->section = 'warehouse_area.new';
                                $this->key     = 0;


                            } elseif (is_numeric($view_path[2])) {
                                $this->key     = $view_path[2];
                                $this->section = 'warehouse_area';

                            }


                        }


                    } elseif ($view_path[1] == 'categories') {
                        $this->object     = 'warehouse';
                        $this->key        = $view_path[0];
                        $this->section    = 'categories';
                        $this->parent     = 'warehouse';
                        $this->parent_key = $view_path[0];
                    } elseif ($view_path[1] == 'category') {
                        $this->section = 'category';
                        $this->object  = 'category';

                        if (isset($view_path[2])) {

                            $view_path[2] = preg_replace('/>$/', '', $view_path[2]);
                            if (preg_match('/^(\d+>)+(\d+)$/', $view_path[2])) {

                                $parent_categories = preg_split('/>/', $view_path[2]);
                                $this->metadata    = $parent_categories;
                                $this->key         = array_pop($parent_categories) ?? '';

                                $this->parent = 'category';


                                $this->parent_key = array_pop($parent_categories) ?? '';


                                if (isset($view_path[3])) {

                                    if ($view_path[3] == 'location') {

                                        $this->parent_key = $this->key;

                                        $this->section = 'location';
                                        $this->object  = 'location';
                                        if (isset($view_path[4]) and is_numeric(
                                                $view_path[4]
                                            )) {

                                            $this->key = $view_path[4];

                                        }

                                    }


                                }

                            } elseif (is_numeric($view_path[2])) {
                                $this->key = $view_path[2];
                                include_once 'class.Category.php';
                                $category = new Category($this->key);
                                if ($category->get(
                                        'Category Branch Type'
                                    ) == 'Root') {
                                    $this->parent     = 'warehouse';
                                    $this->parent_key = $category->get(
                                        'Category Store Key'
                                    );
                                } else {
                                    $this->parent     = 'category';
                                    $this->parent_key = $category->get(
                                        'Category Parent Key'
                                    );

                                }


                                if (isset($view_path[3])) {


                                    if (is_numeric($view_path[3])) {
                                        $this->section    = 'location';
                                        $this->parent     = 'category';
                                        $this->parent_key = $category->id;
                                        $this->object     = 'location';
                                        $this->key        = $view_path[3];
                                    } elseif ($view_path[3] == 'location') {
                                        $this->section = 'location';
                                        $this->object  = 'location';
                                        if (isset($view_path[4]) and is_numeric(
                                                $view_path[4]
                                            )) {

                                            $this->key = $view_path[4];

                                        }

                                    } elseif ($view_path[3] == 'upload') {
                                        //$module='account';
                                        $this->section    = 'upload';
                                        $this->parent     = 'category';
                                        $this->parent_key = $this->key;
                                        $this->object     = 'upload';
                                        if (isset($view_path[4])) {
                                            if (is_numeric(
                                                $view_path[4]
                                            )) {

                                                $this->key = $view_path[4];
                                            }
                                        }

                                    }

                                }


                            } elseif ($view_path[2] == 'new') {

                                $this->section = 'main_category.new';

                                $this->parent     = 'warehouse';
                                $this->parent_key = $view_path[0];
                                $this->key        = 0;
                            }
                        }

                    } elseif ($view_path[1] == 'production_deliveries') {


                        $this->section = 'production_deliveries';
                        $this->object  = '';

                        $this->parent     = 'warehouse';
                        $this->parent_key = $this->key;





                        if (isset($view_path[2]) and in_array(
                                $view_path[2], [
                                                 'todo',
                                                 'all',
                                                 'done',
                                                 'cancelled'
                                             ]
                            )) {

                            if (isset($view_path[3])) {
                                if (is_numeric($view_path[3])) {
                                    $this->key = $view_path[3];

                                    $this->extra   = $view_path[2];
                                    $this->section = 'production_delivery';
                                    $this->parent  = 'warehouse';
                                    $this->object  = 'supplier_delivery';


                                }

                            }
                        }

                    } elseif ($view_path[1] == 'kpis') {
                        $this->section = 'warehouse_kpis';
                        $this->object  = '';

                        $this->parent     = 'warehouse';
                        $this->parent_key = $this->key;

                        if (isset($view_path[2])) {
                            if (is_numeric($view_path[2])) {
                                $this->parent     = 'staff';
                                $this->parent_key = $view_path[2];
                                $this->section    = 'staff_warehouse_kpi';
                                $this->key        = $view_path[2];
                            }
                        }


                    }elseif ($view_path[1] == 'pipelines') {
                        $this->section = 'locations';
                        $this->object = '';
                        $this->parent     = 'warehouse';
                        $this->parent_key = $this->key;
                        $this->tab = 'warehouse.picking_pipelines';
                    }


                }
            } elseif ($view_path[0] == 'new') {
                $this->object  = 'warehouse';
                $this->section = 'warehouse.new';


            }


        } else {
            if ($this->user->data['User Hooked Warehouse Key'] and in_array($this->user->data['User Hooked Warehouse Key'], $this->user->stores)) {
                $this->key = $this->user->data['User Hooked Warehouse Key'];
            } else {
                $_tmp      = $this->user->warehouses;
                $this->key = array_shift($_tmp) ?? '';
            }
        }


    }

}