<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 14 Jul 2021 10:55:03 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */


function get_fulfilment_asset_fields(Fulfilment_Asset $fulfilment_asset, User $user): array {

    $edit = $user->can_edit('fulfilment');
    $super_edit =$user->can_supervisor('fulfilment');

    $fulfilment_asset_fields = array(
        array(
            'label'      => _('Identification'),
            'show_title' => true,
            'fields'     => array(
                array(
                    'id'                => 'Fulfilment_Asset_Reference',
                    'edit'              => ($edit ? 'string' : ''),
                    'value'             => htmlspecialchars($fulfilment_asset->get('Fulfilment Asset Reference')),
                    'formatted_value'   => $fulfilment_asset->get('Reference'),
                    'label'             => ucfirst($fulfilment_asset->get_field_label('Fulfilment Asset Reference')),
                    'required'          => true,
                    'server_validation' => json_encode(
                        array(
                            'tipo'       => 'check_for_duplicates',
                            'parent'     => 'customer',
                            'parent_key' => $fulfilment_asset->get('Fulfilment Asset Customer Key'),
                            'object'     => 'Fulfilment Asset',
                            'key'        => $fulfilment_asset->id
                        )
                    ),
                    'type'              => 'value'
                ),


            )
        ),
        array(
            'label'      => _('Storage'),
            'show_title' => true,
            'fields'     => array(
                array(
                    'id'                       => 'Fulfilment_Asset_Location_Key',
                    'edit'                     => 'dropdown_select',
                    'scope'                    => 'locations',
                    'parent'                   => 'warehouse',
                    'parent_key'               => $fulfilment_asset->get('Fulfilment Asset Warehouse Key'),
                    'value'                    => $fulfilment_asset->get('Fulfilment Asset Location Key'),
                    'formatted_value'          => $fulfilment_asset->get('Location Key'),
                    'stripped_formatted_value' => '',
                    'label'                    => _('Location'),
                    'required'                 => true,
                    'type'                     => 'value'


                ),


            )
        ),

    );

    $operations = array(
        'label'      => _('Operations'),
        'show_title' => true,
        'class'      => 'operations '.($fulfilment_asset->get('Fulfilment Asset State')=='InProcess'?'':'hide'),
        'fields'     => array(

            array(
                'id'     => 'unlink_asset_location',
                'class'  => 'operation',
                'render' => $fulfilment_asset->get('Fulfilment Asset Location Key'),

                'value'     => '',
                'label'     => '<span class="asset_location_container" data-asset_key="'.$fulfilment_asset->id.'" >
                                    <span onClick="edit_asset_location(\'delete_from_fld\',this)" class="button">'._("Unlink location").' <i class="fa fa-unlink valid"></i>
                                    </span>
                                </span>',
                'reference' => '',
                'type'      => 'operation'
            ),

            array(
                'id'        => 'delete_part',
                'class'     => 'operation',
                'value'     => '',
                'render'    => $fulfilment_asset->get('Fulfilment Asset State')=='InProcess',
                'label'     => '<i class="fa fa-fw fa-'.($super_edit ? 'lock-alt' : 'lock').'  button" 
                 data-labels=\'{ "text":"'._('Please ask an authorised user to delete this part').'","title":"'._('Restricted operation').'","footer":"'._('Authorised users').': "}\'  
                onClick="'.($super_edit ? 'toggle_unlock_delete_object(this)' : 'not_authorised_toggle_unlock_delete_object(this,\'BS\')').'"  
                style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$fulfilment_asset->get_object_name().'", "key":"'.$fulfilment_asset->id.'"}\' onClick="delete_object(this)" class="delete_object disabled">'._("Delete asset")
                    .' <i class="far fa-trash-alt new_button link"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),


        )

    );
    $fulfilment_asset_fields[] = $operations;


    return $fulfilment_asset_fields;

}





