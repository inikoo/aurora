<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:   27 December 2019  10:45::53  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_product_section() {
  return array(
      'type'           => 'object',
      'subtabs_parent' => array(
          'product.sales.plot'                                   => 'product.sales',
          'product.sales.history'                                => 'product.sales',
          'product.sales.info'                                   => 'product.sales',
          'product.customers'                                    => 'product.customers',
          'product.customers.favored'                            => 'product.customers',
          'product.back_to_stock_notification_request.customers' => 'product.customers',


          'product.sales_correlation'     => 'product.correlation',
          'product.sales_anticorrelation' => 'product.correlation'
      ),

      'tabs' => array(
          'product.details' => array(
              'label' => _('Data'),
              'icon'  => 'database',
              'title' => _('Details')
          ),
          'product.variants' => array(
              'label' => _('Variations'),
              'icon'  => 'stream',
              'title' => _('Variations')
          ),

          'product.webpages' => array(
              'label' => _('Webpages'),
              'icon'  => 'globe',
              'title' => _('Webpages')
          ),

          'product.history' => array(
              'label' => _('History, notes'),
              'icon'  => 'sticky-note'
          ),
          'product.sales'   => array(
              'label'   => _('Sales'),
              'title'   => _('Sales'),
              'icon'    => 'money-bill-alt',
              'subtabs' => array(
                  'product.sales.plot'     => array(
                      'label' => _(
                          'Plot'
                      )
                  ),
                  'product.sales.history'  => array(
                      'label' => _(
                          'Sales history'
                      )
                  ),

                  'product.sales.info'     => array(
                      'label'   => '',
                      'title'   => _('Sales data info'),
                      'icon_v2' => 'fal fa-fw fa-chess-clock',
                      'class'   => 'right icon_only'
                  ),

              )
          ),
          'product.orders'  => array(
              'label'         => _('Orders'),
              'icon'          => 'shopping-cart',
              'quantity_data' => array(
                  'object' => '_object',
                  'field'  => 'Number Orders'
              ),

          ),

          'product.customers' => array(
              'label'         => _('Customers'),
              'icon'          => 'users',
              'quantity_data' => array(
                  'object' => '_object',
                  'field'  => 'Customers Numbers'
              ),
              'subtabs'       => array(
                  'product.customers'                                    => array(
                      'label'         => _('Customers'),
                      'icon'          => 'user',
                      'quantity_data' => array(
                          'object' => '_object',
                          'field'  => 'Number Customers'
                      ),
                  ),
                  'product.customers.favored'                            => array(
                      'label'         => _('Customers who favored'),
                      'icon'          => 'heart',
                      'quantity_data' => array(
                          'object' => '_object',
                          'field'  => 'Number Customers Favored'
                      ),

                  ),
                  'product.back_to_stock_notification_request.customers' => array(
                      'label'         => _('Back to stock notification requests'),
                      'icon'          => 'dolly',
                      'quantity_data' => array(
                          'object' => '_object',
                          'field'  => 'Number Customers OOS Notification'
                      ),

                  ),

              )
          ),

          'product.mailshots'   => array(
              'label' => _('Mailshots'),
              'icon'  => 'bullhorn'
          ),
          'product.correlations' => array(
              'title'   => _('Sales correlations'),
              'label'   => _('Related products'),
              'icon'    => 'project-diagram',

          ),


          'product.history' => array(
              'title'         => _('History/Notes'),
              'label'         => '',
              'quantity_data' => array(
                  'object' => '_object',
                  'field'  => 'Number History Records'
              ),
              'icon'          => 'road',
              'class'         => 'right icon_only'
          ),
          'product.images'  => array(
              'title'         => _('Images'),
              'label'         => '',
              'quantity_data' => array(
                  'object' => '_object',
                  'field'  => 'Number Images'
              ),
              'icon'          => 'camera-retro',
              'class'         => 'right icon_only'
          ),
          'product.parts'   => array(
              'title'         => _('Parts'),
              'label'         => '',
              'quantity_data' => array(
                  'object' => '_object',
                  'field'  => 'Number of Parts'
              ),
              'icon'          => 'box',
              'class'         => 'right icon_only'
          ),

      )

  );
}