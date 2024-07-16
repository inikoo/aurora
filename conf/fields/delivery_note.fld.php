<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 September 2017 at 23:01:41 GMT+8, Kuala Lumpur, Malaysia 

 Copyright (c) 2017, Inikoo

 Version 3.0
*/


include_once 'utils/static_data.php';

$new=false;


$options_parcel_type = array(
    'Box' => _('Box'),
    'Pallet' => _('Pallet'),
    'Envelope' => _('Envelope'),
);

$delivery_note=$object;


$object_fields = array(
    array(
        'label'      => _('Id'),
        'show_title' => true,
        'fields'     => array(

            array(
                'edit'  => ($edit ? 'string' : ''),
                'id'    => 'Delivery_Note_ID',
                'value' => $delivery_note->get('Delivery Note ID'),
                'server_validation' => json_encode(array('tipo' => 'check_for_duplicates')),
                'label' => _('Number').' <i class="fa fa-exclamation-triangle yellow" aria-hidden="true"  title="'._('Warning, editing delivery note details can cause problems').'" ></i>',
            ),


        )
    ),

    array(
        'label'      => _('Parcels/Weight'),
        'show_title' => true,
        'fields'     => array(

            array(
                'edit'  => ($edit ? 'string' : ''),
                'id'    => 'Delivery_Note_Number_Parcels',
                'value' => $delivery_note->get('Delivery Note Number Parcels'),
                'formatted_value' => $delivery_note->get('Number Parcels'),
                'label'             => ucfirst($object->get_field_label('Delivery Note Number Parcels')),
            ),
            array(
                'id'              => 'Delivery_Note_Parcel_Type',
                'edit'            => ($edit ? 'option' : ''),
                'options'         => $options_parcel_type,
                'value'           => $object->get('Delivery Note Parcel Type'),
                'formatted_value' => $object->get('Parcel Type'),
                'label'           => ucfirst($object->get_field_label('Delivery Note Parcel Type')),
                'type'            => 'value'
            ),
            array(
                'edit'  => ($edit ? 'string' : ''),
                'id'    => 'Delivery_Note_Weight',
                'value' => $delivery_note->get('Delivery Note Weight'),
                'formatted_value' => $delivery_note->get('Weight'),
                'label'             => ucfirst($object->get_field_label('Delivery Note Weight')),
            ),

        )
    ),


);







if($delivery_note->get('Delivery Note State')=='Dispatched' ) {

    $operations = array(
        'label'      => _('Operations'),
        'show_title' => true,
        'class'      => 'operations',
        'fields'     => array(


            array(
                'id'        => 'un_dispatch',
                'class'     => 'operation',
                'value'     => '',
                'label'     => '<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span data-labels=\'{ "no_message":"'._('A reason should be provided').'", "button_text":"'._('Un dispatch').'",  "title":"'._('Un dispatching delivery').'", "text":"'._("This operation cannot be undone").'",  "placeholder":"'._('Write the reason for un dispatching this delivery note').'" }\' data-data=\'{ "object": "'.$object->get_object_name().'", "key":"'.$object->id.'"}\' onClick="un_dispatch_delivery_note(this)" class="delete_object disabled">'._('Set delivery note as no dispatched').' 
<span class="fa-stack ">
  <i class="fa fa-paper-plane fa-stack-1x "></i>
  <i style="color:red" class="fa fa-ban fa-stack-1x text-danger"></i>
</span>
</span>',
                'reference' => '',
                'type'      => 'operation'
            ),


        )

    );

    $object_fields[] = $operations;

}

?>
