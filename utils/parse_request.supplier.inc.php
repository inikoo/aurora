<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 August 2018 at 17:27:49 GMT+8, Kuala Lumpur, Malysia

 Copyright (c) 2018, Inikoo

 Version 3.0
*/




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


            }
            elseif ($view_path[1] == 'order') {
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

                                        $parent     = 'PurchaseOrder';
                                        $parent_key = $view_path[2];
                                        $object     = 'PurchaseOrderItem';
                                        $key        = $view_path[4];
                                        $section    = 'supplier.order.item';


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
                    if (isset($view_path[3])) {
                        if ($view_path[3] == 'attachment') {
                            $section    = 'supplier_delivery.attachment';
                            $object     = 'attachment';
                            $parent     = 'supplier_delivery';
                            $parent_key = $key;
                            if (isset($view_path[4])) {
                                if (is_numeric($view_path[4])) {
                                    $key = $view_path[4];
                                } elseif ($view_path[4] == 'new') {
                                    $section = 'supplier_delivery.attachment.new';

                                    $key = 0;
                                }
                            }


                        }
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


            } elseif ($view_path[1] == 'timeseries') {
                $section = 'timeseries_record';

                $parent     = 'supplier';
                $parent_key = $view_path[0];
                $object     = 'timeseries';

                if (isset($view_path[2])) {
                    if (is_numeric($view_path[2])) {
                        $key = $view_path[2];

                        if (isset($view_path[3])) {
                            if (is_numeric($view_path[3])) {

                                $parent     = 'timeseries';
                                $parent_key = $view_path[2];
                                $object     = 'timeseries_record';

                                $key = $view_path[3];


                            }

                        }


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
                            $section = 'supplier.attachment.new';

                            $key = 0;
                        }
                    }


                }
            }


        }

    }

}

