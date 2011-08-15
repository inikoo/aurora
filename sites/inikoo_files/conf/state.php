<?php
$default_state=array(
                   'family'=>array(
                                'details'=>false,
                                'percentages'=>false,
                                'view'=>'general',

                                'edit_view'=>'view_name',
                                'id'=>1,
                                'period'=>'year',
                                'percentage'=>0,
                                'mode'=>'all',
                                'avg'=>'totals',
                                'edit'=>'details',
                                'editing'=>false,
                                'table_type'=>'list',
                                'plot'=>'family',
                                'plot_data'=>array('family'=>array(
                                                                'period'=>'m',
                                                                'category'=>'sales',
                                                                'page'=>'plot.php'
                                                            )
                                                            ,'top_products'=>array(
                                                                                'period'=>'m',
                                                                                'category'=>'sales',
                                                                                'page'=>'plot.php'
                                                                            )
                                                                            ,'pie'=>array(
                                                                                       'period'=>'m',
                                                                                       'category'=>'sales',
                                                                                       'page'=>'pie.php',
                                                                                       'forecast'=>'no',
                                                                                       'date'=>'today'
                                                                                   )
                                                  ),





                                'table'=>array(
                                            'show_only'=>'forsale',
                                            'order'=>'code',
                                            'order_dir'=>'',
                                            'sf'=>0,
                                            'nr'=>20,
                                            'where'=>'where true',
                                            'f_field'=>'id',
                                            'f_value'=>''

                                        ),
                                'history'=>array(
                                              'where'=>'where true',
                                              'f_field'=>'abstract',
                                              'f_value'=>'','f_show'=>false,
                                              'order'=>'date',
                                              'order_dir'=>'desc',
                                              'sf'=>0,
                                              'nr'=>25,
                                              'from'=>'',
                                              'to'=>'',
                                              'elements'=>''
                                          ),
                                'deals'=>array(
                                            'where'=>'where true',
                                            'f_field'=>'name',
                                            'f_value'=>'','f_show'=>false,
                                            'order'=>'name',
                                            'order_dir'=>'',
                                            'sf'=>0,
                                            'nr'=>25,
                                        ),
                            ),
                   'products'=>array(
                                  'details'=>false,
                                  'percentages'=>false,
                                  'view'=>'general',
                                  'from'=>'',
                                  'to'=>'',
                                  'period'=>'year',
                                  'percentage'=>0,
                                  'mode'=>'same_code',//same_code,same_id,all
                                  'parent'=>'none',//store,department,family,none
                                  'restrictions'=>'forsale',
                                  'avg'=>'totals',
                                  'table'=>array(
                                              'order'=>'code',
                                              'order_dir'=>'',
                                              'sf'=>0,
                                              'nr'=>25,
                                              'where'=>'where true',
                                              'f_field'=>'code',
                                              'f_value'=>'','f_show'=>false,
                                              'from'=>'',
                                              'to'=>'',
                                              'elements'=>array(),
                                              'mode'=>'same_code',//same_code,same_id,all
                                              'parent'=>'none',//store,department,family,none
                                              'restrictions'=>'forsale'
                                          )
                              ),
                   'families'=>array(
                                  'details'=>false,
                                  'view'=>'general',
                                  'percentages'=>false,
                                  'period'=>'year',
                                  'mode'=>'all',
                                  'avg'=>'totals',
                                  'mode'=>'same_code',//same_code,same_id,all
                                  'parent'=>'none',//store,department,family,none
                                  'restrictions'=>'forsale',
                                  'table'=>array(
                                              'order'=>'code',
                                              'order_dir'=>'',
                                              'sf'=>0,
                                              'nr'=>20,
                                              'where'=>'where true',
                                              'f_field'=>'code',
                                              'f_value'=>'','f_show'=>false,
                                              'mode'=>'same_code',//same_code,same_id,all
                                              'parent'=>'none',//store,department,family,none
                                              'restrictions'=>'forsale',
                                          )
                              ),



               );

?>