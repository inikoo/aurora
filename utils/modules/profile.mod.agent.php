<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  12:08::17  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_agent_profile_module() {
  return  array(
      'sections' => array(
          'profile' => array(
              'type'  => 'object',
              'label' => _('Profile'),
              'icon'  => 'user_secret',
              'id'    => '',
              'tabs'  => array(
                  'agent.profile' => array(
                      'label' => _('Settings'),
                      'icon'  => 'sliders-h'
                  ),
                  'agent.details' => array(
                      'label' => _('Agent details'),
                      'icon'  => 'database',
                      'title' => _('Agent details'),
                  ),

                  'agent.details' => array(
                      'label' => _('Agent details'),
                      'icon'  => 'database',
                      'title' => _('Agent details'),
                  ),


                  'agent.history' => array(
                      'label' => _('History/Notes'),
                      'icon'  => 'road',
                      'class' => 'right icon_only'
                  ),

              )
          )
      ),
  );
}