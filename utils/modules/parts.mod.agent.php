<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  12:10::03  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_agent_parts_module() {
  return array(
      'sections' => array(
          'parts' => array(
              'type'      => 'navigation',
              'label'     => _("Products"),
              'icon'      => 'box',
              'reference' => 'agent_parts',
              'tabs'      => array(
                  'agent.parts' => array()
              )
          ),


          'agent_part' => array(
              'type' => 'object',


              'tabs' => array(


                  'supplier_part.details' => array(
                      'label' => _('Data'),
                      'icon'  => 'database'
                  ),

                  'supplier_part.images'  => array(
                      'label' => '',
                      'title' => _('Images'),
                      'icon'  => 'camera-retro',
                      'class' => 'right icon_only'
                  ),
                  'supplier_part.history' => array(
                      'label' => '',
                      'title' => _('History/Notes'),
                      'icon'  => 'road',
                      'class' => 'right icon_only'
                  ),


              )
          ),


      )
  );
}