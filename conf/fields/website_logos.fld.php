<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 April 2018 at 09:43:41 BST, Sheffield, UK

 Copyright (c) 2018, Inikoo

 Version 3.0
*/


$settings=$object->settings;




$object_fields = array(
    array(
        'label'      => _('Favicon'),
        'show_title' => true,
        'fields'     => array(


            array(
                'id'              => 'Website_Favicon',
                'edit'            => 'no_icon',
                'value'           => $object->get('Website Favicon'),
                'value'           => '',
                'formatted_value' => '<input style="display:none" type="file" name="_Website_Favicon" id="_Website_Favicon" class="image_upload" data-options=\'{ }\'/><label style="cursor: pointer" for="_Website_Favicon"><img src="'.(empty($settings['mobile_logo'])?'http://via.placeholder.com/310x310':$settings['mobile_logo']).'" /></label>',
                'label'           => _('Favicon').'<br><span class="discreet italic">png (310x310)</span>',
                'required'        => true,
                'type'            => 'value'
            ),



        )
    ),


    array(
        'label'      => _('Mobile'),
        'show_title' => true,
        'fields'     => array(



            array(
                'id'              => 'Mobile_Logo',
                'edit'            => 'no_icon',
                'value'           => $object->get('Website Mobile Logo'),
                'value'           => '',
                'formatted_value' => '<img src="'.(empty($settings['mobile_logo'])?'http://via.placeholder.com/60x60':$settings['mobile_logo']).'" />',
                'label'           => _('Header logo').'<br><span class="discreet italic">png (60x60)</span>',
                'required'        => true,
                'type'            => 'value'
            ),
            array(
                'id'              => 'Mobile_Menu_Background',
                'edit'            => 'no_icon',
                'value'           => $object->get('Website Mobile Menu Background'),
                'value'           => '',
                'formatted_value' => '<img src="'.(empty($settings['mobile_logo'])?'http://via.placeholder.com/300x188':$settings['mobile_logo']).'" />',
                'label'           => _('Menu background').'<br><span class="discreet italic">png (300x188)</span>',
                'required'        => true,
                'type'            => 'value'
            ),


        )
    ),


);


?>
