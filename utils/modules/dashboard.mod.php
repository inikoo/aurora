<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:   27 December 2019  10:45::53  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_dashboard_module() {
  return array(

      'section' => 'dashboard',

      'parent'      => 'none',
      'parent_type' => 'none',
      'sections'    => array(
          'dashboard' => array(
              'type'  => 'widgets',
              'label' => _('Home'),
              'title' => _('Dashboard'),
              'icon'  => 'home',
              'tabs'  => array(
                  'dashboard' => array(
                      'label' => _('Dashboard')
                  ),

              )

          ),
      )

  );
}