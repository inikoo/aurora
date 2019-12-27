<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:   27 December 2019  10:45::53  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_service_section() {
  return array(
      'type'           => 'object',
      'subtabs_parent' => array(
          'service.sales.plot'           => 'service.sales',
          'service.sales.history'        => 'service.sales',
          'service.sales.calendar'       => 'service.sales',
          'service.customers.customers'  => 'service.customers',
          'service.customers.favourites' => 'service.customers',
          'service.website.webpage'      => 'service.website',
      ),

      'tabs' => array(
          'service.details'   => array(
              'label' => _('Data'),
              'icon'  => 'database',
              'title' => _('Details')
          ),
          'service.history'   => array(
              'label' => _('History, notes'),
              'icon'  => 'sticky-note'
          ),
          'service.sales'     => array(
              'label'   => _('Sales'),
              'title'   => _('Sales'),
              'icon'    => 'money-bill-alt',
              'subtabs' => array(
                  'service.sales.plot'     => array(
                      'label' => _('Plot')
                  ),
                  'service.sales.history'  => array(
                      'label' => _('Sales history')
                  ),
                  'service.sales.calendar' => array(
                      'label' => _('Calendar')
                  ),

              )
          ),
          'service.orders'    => array(
              'label' => _('Orders'),

          ),
          'service.customers' => array(
              'label'   => _('Customers'),
              'subtabs' => array(
                  'service.customers.customers'  => array(
                      'label' => _('Customers'),
                      'title' => _('Customers')
                  ),
                  'service.customers.favourites' => array(
                      'label' => _('Customers who favored'),
                  ),

              )
          ),
          'service.offers'    => array(
              'label' => _('Offers'),
              'title' => _('Offers')
          ),

          'service.website' => array(
              'label'   => _('Website'),
              'title'   => _('Website'),
              'subtabs' => array(
                  'service.website.webpage' => array(
                      'label' => _(
                          'Webpage'
                      ),
                      'title' => _(
                          'service webpage'
                      )
                  ),
                  'service.sales.pages'     => array(
                      'label' => _(
                          'Webpages'
                      ),
                      'title' => _(
                          'Webpages where this service is on sale'
                      )
                  ),

              )
          ),
          'service.history' => array(
              'label' => _('History'),
              'icon'  => 'road',
              'class' => 'right icon_only'
          ),
          'category.images' => array(
              'label' => _('Images'),
              'icon'  => 'camera-retro',
              'class' => 'right icon_only'
          ),

      )
  );
}