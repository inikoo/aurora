<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 22 Jul 2021 20:02:12 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */


function get_picking_pipeline_fields(Picking_Pipeline $picking_pipeline, Warehouse $warehouse, User $user): array {

    $new        = false;
    $edit       = false;
    $super_edit = false;
    if ($user->can_supervisor('stores')) {
        $super_edit = true;
    }
    if ($user->can_edit('stores')) {
        $edit = true;
    }

    if (!$picking_pipeline->id) {
        $new = true;

    }


    $picking_pipeline_fields = array(
        array(
            'label'      => _('Picking Pipeline'),
            'show_title' => true,
            'fields'     => array(
                array(
                    'id'                => 'Picking_Pipeline_Name',
                    'edit'              => ($edit ? 'string' : ''),
                    'value'             => htmlspecialchars($picking_pipeline->get('Picking Pipeline Name')),
                    'formatted_value'   => $picking_pipeline->get('Name'),
                    'label'             => ucfirst($picking_pipeline->get_field_label('Picking Pipeline Name')),
                    'required'          => true,
                    'server_validation' => json_encode(
                        array(
                            'tipo'       => 'check_for_duplicates',
                            'parent'     => 'warehouse',
                            'parent_key' => $warehouse->id,
                            'object'     => 'Picking Pipeline',
                            'key'        => $picking_pipeline->id
                        )
                    ),
                    'type'              => 'value'
                ),


            )
        ),

    );

    if (!$new) {
        $operations = array(
            'label'      => _('Operations'),
            'show_title' => true,
            'class'      => 'operations',
            'fields'     => array(


                array(
                    'id'    => 'delete_picking_pipeline',
                    'class' => 'operation',
                    'value' => '',

                    'label' => '<i class="fa fa-fw fa-'.($super_edit ? 'lock-alt' : 'lock').' button" onClick="'.($super_edit ? 'toggle_unlock_delete_object(this)' : 'not_authorised_toggle_unlock_delete_object(this,\'SS\')').'" 
                            style="margin-right:20px"></i> 
                            <span 
                                    data-labels=\'{ "no_message":"'._('A reason should be provided').'", "button_text":"'._('Delete').'",  "title":"'._('Deleting picking pipeline').'","text":"'._("This operation cannot be undone").'",  
                                                "placeholder":"'._('Write the reason for deleting this picking pipeline').'" }\' 
                                    data-data=\'{ "object": "'.$picking_pipeline->get_object_name().'", "key":"'.$picking_pipeline->id.'"}\' 
                                    onClick="delete_object_with_note(this)" class="delete_object disabled">'._('Delete picking pipeline').' <i class="far fa-trash-alt new_button link "></i>
                            </span>',

                    'reference' => '',
                    'type'      => 'operation'
                ),

            )

        );

        $picking_pipeline_fields[] = $operations;
    }

    return $picking_pipeline_fields;

}

