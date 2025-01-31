<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 14 Jul 2021 10:55:03 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */


function get_fulfilment_asset_fields(Fulfilment_Asset $fulfilment_asset, User $user): array
{
    $edit       = $user->can_edit('fulfilment');
    $super_edit = $user->can_supervisor('fulfilment');

    $options_Type = array(
        'Pallet' => _('Pallet'),
        'Box'    => _('Box')
    );

    $fulfilment_asset_fields = array(
        array(
            'label'      => _('Identification'),
            'show_title' => true,
            'class'      => ($fulfilment_asset->get('State Index') >= 60 ? 'hide' : ''),

            'fields' => array(
                array(
                    'id'     => 'Fulfilment_Asset_Reference',
                    'render' => $fulfilment_asset->get('State Index') < 60,

                    'edit'              => ($edit ? 'string' : ''),
                    'value'             => htmlspecialchars($fulfilment_asset->get('Fulfilment Asset Reference')),
                    'formatted_value'   => $fulfilment_asset->get('Reference'),
                    'label'             => ucfirst($fulfilment_asset->get_field_label('Fulfilment Asset Reference')),
                    'required'          => false,
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
                    'id'     => 'Fulfilment_Asset_Location_Key',
                    'render' => $fulfilment_asset->get('State Index') < 80,

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
                array(


                    'id'     => 'Fulfilment_Asset_From',
                    'edit'   => ($edit ? 'date' : ''),
                    'render' => $fulfilment_asset->get('State Index') >= 40 and $fulfilment_asset->get('State Index') <= 80,

                    'time'            => '09:00:00',
                    'value'           => $fulfilment_asset->get('Fulfilment Asset From'),
                    'formatted_value' => $fulfilment_asset->get('From'),
                    'label'           => ucfirst($fulfilment_asset->get_field_label('Fulfilment Asset From')),
                    'invalid_msg'     => get_invalid_message('date'),
                    'required'        => true,


                ),
                array(


                    'id'     => 'Fulfilment_Asset_To',
                    'edit'   => ($edit ? 'date' : ''),
                    'render' => $fulfilment_asset->get('State Index') == 80,

                    'time'            => '17:00:00',
                    'value'           => $fulfilment_asset->get('Fulfilment Asset To'),
                    'formatted_value' => $fulfilment_asset->get('To'),
                    'label'           => ucfirst($fulfilment_asset->get_field_label('Fulfilment Asset To')),
                    'invalid_msg'     => get_invalid_message('date'),
                    'required'        => true,


                ),

            )
        ),
        array(
            'label'      => _('Properties'),
            'class'      => ($fulfilment_asset->get('State Index') >= 60 ? 'hide' : ''),
            'show_title' => true,
            'fields'     => array(
                array(
                    'id'              => 'Fulfilment_Asset_Type',
                    'render'          => $fulfilment_asset->get('State Index') < 60,
                    'edit'            => ($edit ? 'option' : ''),
                    'value'           => htmlspecialchars($fulfilment_asset->get('Fulfilment Asset Type')),
                    'formatted_value' => $fulfilment_asset->get('Type'),
                    'label'           => ucfirst($fulfilment_asset->get_field_label('Fulfilment Asset Type')),
                    'options'         => $options_Type,

                    'required' => false,
                    'type'     => 'value'
                ),


            )
        ),

    );


    $operations                = array(
        'label'      => _('Operations'),
        'show_title' => true,
        'class'      => 'operations ',
        'class'      => ($fulfilment_asset->get('State Index') >= 60 ? 'hide' : ''),


        'fields' => array(

            array(
                'id'     => 'unlink_asset_location',
                'class'  => 'operation',
                'render' => ($fulfilment_asset->get('Fulfilment Asset Location Key') and $fulfilment_asset->get('State Index') <= 40),

                'value'     => '',
                'label'     => '<span class="asset_location_container" data-asset_key="'.$fulfilment_asset->id.'" >
                                    <span onClick="edit_asset_location(\'delete_from_fld\',this)" class="button">'._("Unlink location").' <i class="fa fa-unlink valid"></i>
                                    </span>
                                </span>',
                'reference' => '',
                'type'      => 'operation'
            ),

            array(
                'id'        => 'delete_asset',
                'class'     => 'operation',
                'value'     => '',
                'render'    => $fulfilment_asset->get('Fulfilment Asset State') == 'InProcess',
                'label'     => '<i class="fa fa-fw fa-'.($super_edit ? 'lock-alt' : 'lock').'  button" 
                 data-labels=\'{ "text":"'._('Please ask an authorised user to delete this asset').'","title":"'._('Restricted operation').'","footer":"'._('Authorised users').': "}\'  
                onClick="'.($super_edit ? 'toggle_unlock_delete_object(this)' : 'not_authorised_toggle_unlock_delete_object(this,\'BS\')').'"  
                style="margin-right:20px"></i> <span data-data=\'{ "object": "'.$fulfilment_asset->get_object_name().'", "key":"'.$fulfilment_asset->id.'"}\' onClick="delete_object(this)" class="delete_object disabled">'._("Delete asset")
                    .' <i class="far fa-trash-alt new_button link"></i></span>',
                'reference' => '',
                'type'      => 'operation'
            ),


        )

    );
    $fulfilment_asset_fields[] = $operations;

    $fulfilment_asset_fields=[];
    return $fulfilment_asset_fields;
}





