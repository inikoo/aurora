<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 December 2017 at 14:22:40 CET, Mijas Costa, Spain

 Copyright (c) 2017, Inikoo

 Version 3.0
*/


$object->get_flags_data();




$object_fields = array(
    array(
        'label'      => _('Dates'),
        'show_title' => true,
        'fields'     => array(

            array(
                  'edit' => ($edit ? 'date' : ''),
                'time'            => '00:00:00',

                'id'                => 'Warehouse_Leakage_Timeseries_From',
                'value'             => $object->get('Warehouse Leakage Timeseries From'),
                'formatted_value'   => $object->get('Leakage Timeseries From'),
                'label'             => ucfirst(
                    $object->get_field_label('Warehouse Leakage Timeseries From')
                ),
                'invalid_msg'       => get_invalid_message('date'),
                'required'          => true,
                
                'type'              => 'value'
            ),
           

        )
    ),

  


);




?>
