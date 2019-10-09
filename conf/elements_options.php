<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 December 2015 at 14:28:35 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$elements_options = array(


    'customers' => array(
        'activity' => array(
            'label' => _('Status'),
            'items' => array(
                'Rejected'  => array(
                    'label'    => _('Rejected'),
                    'selected' => true
                ),
                'ToApprove' => array(
                    'label'    => _('To be approved'),
                    'selected' => true
                ),
                'Active'    => array(
                    'label'    => _('Active'),
                    'selected' => true
                ),
                'Losing'    => array(
                    'label'    => _('Losing'),
                    'selected' => true
                ),
                'Lost'      => array(
                    'label'    => _('Lost'),
                    'selected' => true
                ),
            )


        ),
        'orders'   => array(
            'label' => _('Orders'),
            'items' => array(
                'Yes' => array(
                    'label'    => _('With orders'),
                    'selected' => true
                ),
                'No'  => array(
                    'label'    => _('Without orders'),
                    'selected' => true
                ),
            )


        ),

        'type'     => array(
            'label' => _('Type'),
            'items' => array(
                'Normal'  => array(
                    'label'    => _('Normal'),
                    'selected' => true
                ),
                'VIP'     => array(
                    'label'    => _('VIP'),
                    'selected' => true
                ),
                'Partner' => array(
                    'label'    => _('Partner'),
                    'selected' => true
                ),
                'Staff'   => array(
                    'label'    => _('Staff'),
                    'selected' => true
                ),
            )
        ),
        'location' => array(
            'label' => _('Location'),
            'items' => array(
                'Domestic' => array(
                    'label'    => _('Domestic'),
                    'selected' => true
                ),
                'Export'   => array(
                    'label'    => _('Export'),
                    'selected' => true
                ),

            )


        )
    ),
    'prospects' => array(
        'status' => array(
            'label' => _('Status'),
            'items' => array(
                'NoContacted'   => array(
                    'label'    => _('To be contacted'),
                    'selected' => true
                ),
                'Contacted'     => array(
                    'label'    => _('Contacted'),
                    'selected' => true
                ),
                'NotInterested' => array(
                    'label'    => _('Not interested'),
                    'selected' => false
                ),
                'Registered'    => array(
                    'label'    => _('Registered'),
                    'selected' => false
                ),
                'Invoiced'      => array(
                    'label'    => _('Invoiced'),
                    'selected' => false
                ),

            )


        )
    ),

    'prospect_agent_prospects' => array(
        'status' => array(
            'label' => _('Status'),
            'items' => array(
                'NoContacted'   => array(
                    'label'    => _('To be contacted'),
                    'selected' => true
                ),
                'Contacted'     => array(
                    'label'    => _('Contacted'),
                    'selected' => true
                ),
                'NotInterested' => array(
                    'label'    => _('Not interested'),
                    'selected' => true
                ),
                'Registered'    => array(
                    'label'    => _('Registered'),
                    'selected' => true
                ),
                'Invoiced'      => array(
                    'label'    => _('Invoiced'),
                    'selected' => true
                ),

            )


        )
    ),

    'product_category_customers' => array(
        'sales_status' => array(
            'label' => _('Status'),
            'items' => array(
                'basket'  => array(
                    'label'    => _('Just in basket'),
                    'selected' => true
                ),
                'ordered' => array(
                    'label'    => _('Ordered'),
                    'selected' => true
                )
            )


        )
    ),
    'customer_history'           => array(
        'type' => array(
            'label' => _('Type'),
            'items' => array(
                'Notes'       => array(
                    'label'    => _('Notes'),
                    'selected' => true
                ),
                'Orders'      => array(
                    'label'    => _('Orders'),
                    'selected' => true
                ),
                'Changes'     => array(
                    'label'    => _('Changes'),
                    'selected' => true
                ),
                'Attachments' => array(
                    'label'    => _('Attachments'),
                    'selected' => true
                ),
                'WebLog'      => array(
                    'label'    => _('WebLog'),
                    'selected' => true
                ),
                'Emails'      => array(
                    'label'    => _('Emails'),
                    'selected' => true
                )
            ),

        )
    ),
    'prospect_history'           => array(
        'type' => array(
            'label' => _('Type'),
            'items' => array(
                'Notes' => array(
                    'label'    => _('Notes'),
                    'selected' => true
                ),

                'Changes' => array(
                    'label'    => _('Changes'),
                    'selected' => true
                ),
                'Emails'  => array(
                    'label'    => _('Emails'),
                    'selected' => true
                ),
                'Calls'   => array(
                    'label'    => _('Calls'),
                    'selected' => true
                ),
                'Posts'   => array(
                    'label'    => _('Mail posts'),
                    'selected' => true
                )
            ),

        )
    ),


    'webpage_publishing_history' => array(
        'type' => array(
            'label' => _('Type'),
            'items' => array(
                'Notes' => array(
                    'label'    => _('Notes'),
                    'selected' => true
                ),

                'Deployment' => array(
                    'label'    => _('Deployments'),
                    'selected' => true
                ),

            ),

        )
    ),

    'supplier_history'       => array(
        'type' => array(
            'label' => _('Type'),
            'items' => array(
                'Notes'   => array(
                    'label'    => _('Notes'),
                    'selected' => true
                ),
                'Changes' => array(
                    'label'    => _('Changes'),
                    'selected' => true
                ),
                'WebLog'  => array(
                    'label'    => _('WebLog'),
                    'selected' => true
                ),
            ),

        )
    ),
    'supplier_order_history' => array(
        'type' => array(
            'label' => _('Type'),
            'items' => array(
                'Notes'   => array(
                    'label'    => _('Notes'),
                    'selected' => true
                ),
                'Changes' => array(
                    'label'    => _('Changes'),
                    'selected' => true
                ),
            ),

        )
    ),


    'supplier_part_history' => array(
        'type' => array(
            'label' => _('Type'),
            'items' => array(
                'Notes'   => array(
                    'label'    => _('Notes'),
                    'selected' => true
                ),
                'Changes' => array(
                    'label'    => _('Changes'),
                    'selected' => true
                ),
            ),

        )
    ),
    'agent_history'         => array(
        'type' => array(
            'label' => _('Type'),
            'items' => array(
                'Notes'   => array(
                    'label'    => _('Notes'),
                    'selected' => true
                ),
                'Changes' => array(
                    'label'    => _('Changes'),
                    'selected' => true
                ),
                'WebLog'  => array(
                    'label'    => _('WebLog'),
                    'selected' => true
                ),
            ),

        )
    ),


    'poll_query_history' => array(
        'type' => array(
            'label' => _('Type'),
            'items' => array(
                'Notes'   => array(
                    'label'    => _('Notes'),
                    'selected' => true
                ),
                'Changes' => array(
                    'label'    => _('Changes'),
                    'selected' => true
                ),
            ),

        )
    ),

    'poll_query_option_history' => array(
        'type' => array(
            'label' => _('Type'),
            'items' => array(
                'Notes'   => array(
                    'label'    => _('Notes'),
                    'selected' => true
                ),
                'Changes' => array(
                    'label'    => _('Changes'),
                    'selected' => true
                ),
            ),

        )
    ),


    'orders' => array(


        'state' => array(
            'label' => _('State'),
            'items' => array(
                'InBasket'    => array(
                    'label'    => _('In basket'),
                    'selected' => true
                ),
                'InProcess'   => array(
                    'label'    => _('Submitted'),
                    'selected' => true
                ),
                'InWarehouse' => array(
                    'label'    => _('In warehouse'),
                    'selected' => true
                ),
                'PackedDone'  => array(
                    'label'    => _('Packed'),
                    'selected' => true
                ),
                'Approved'    => array(
                    'label'    => _('Approved'),
                    'selected' => true
                ),
                'Dispatched'  => array(
                    'label'    => _('Dispatched'),
                    'selected' => true
                ),
                'Cancelled'   => array(
                    'label'    => _('Cancelled'),
                    'selected' => true
                )
            )
        ),

        'type' => array(
            'label' => _('Type'),
            'items' => array(
                'Order'    => array(
                    'label'    => _('Order'),
                    'selected' => true
                ),
                'Sample'   => array(
                    'label'    => _('Sample'),
                    'selected' => true
                ),
                'Donation' => array(
                    'label'    => _('Donation'),
                    'selected' => true
                ),
                'Other'    => array(
                    'label'    => _('Other'),
                    'selected' => true
                ),
            )
        ),

        'source' => array(
            'label' => _('Source'),
            'items' => array(
                'Internet' => array(
                    'label'    => _('Website'),
                    'selected' => true
                ),
                'Call'     => array(
                    'label'    => _('Telephone'),
                    'selected' => true
                ),
                'Store'    => array(
                    'label'    => _('Showroom'),
                    'selected' => true
                ),
                'Email'    => array(
                    'label'    => _('Email'),
                    'selected' => true
                ),
                'Fax'      => array(
                    'label'    => _('Fax'),
                    'selected' => true
                ),
                'Other'    => array(
                    'label'    => _('Other'),
                    'selected' => true
                )
            ),

        ),


    ),


    'orders_in_control_panel' => array(

        'location' => array(
            'label' => _('Location'),
            'items' => array(

                'Domestic' => array(
                    'label'    => _('Domestic'),
                    'selected' => true
                ),
                'Export'   => array(
                    'label'    => _('Export'),
                    'selected' => true
                ),


            )
        ),


    ),
    'invoices'                => array(
        'type'          => array(
            'label' => _('Type'),
            'items' => array(
                'Invoice' => array(
                    'label'    => _('Invoice'),
                    'selected' => true
                ),
                'Refund'  => array(
                    'label'    => _('Refund'),
                    'selected' => true
                ),
            )
        ),
        'payment_state' => array(
            'label' => _('Payment state'),
            'items' => array(
                'Yes'       => array(
                    'label'    => _(
                        'Paid'
                    ),
                    'selected' => true
                ),
                'Partially' => array(
                    'label'    => _(
                        'Partially paid'
                    ),
                    'selected' => true
                ),
                'No'        => array(
                    'label'    => _(
                        'Waiting payment'
                    ),
                    'selected' => true
                ),
            )
        )

    ),
    'delivery_notes'          => array(
        'dispatch' => array(
            'label' => _('Dispatch state'),
            'items' => array(
                'Ready'    => array(
                    'label'    => _('Ready'),
                    'selected' => true
                ),
                'Picking'  => array(
                    'label'    => _('Picking'),
                    'selected' => true
                ),
                'Packed'   => array(
                    'label'    => _('Packed'),
                    'selected' => true
                ),
                'Done'     => array(
                    'label'    => _('Closed'),
                    'selected' => true
                ),
                'Send'     => array(
                    'label'    => _('Send'),
                    'selected' => true
                ),
                'Returned' => array(
                    'label'    => _('Returned'),
                    'selected' => true
                ),
            )
        ),
        'type'     => array(
            'label' => _('Type'),
            'items' => array(
                'Order'        => array(
                    'label'    => _(
                        'Order'
                    ),
                    'selected' => true
                ),
                'Replacements' => array(
                    'label'    => _(
                        'Replacements'
                    ),
                    'selected' => true
                )

            )
        )

    ),

    'products' => array(
        'status' => array(
            'label' => _('Status'),
            'items' => array(
                'Active' => array(
                    'label'    => _('Active'),
                    'selected' => true
                ),

                'Discontinuing' => array(
                    'label'    => _('Discontinuing'),
                    'selected' => true
                ),

                'Suspended'    => array(
                    'label'    => _('Suspended'),
                    'selected' => false
                ),
                'Discontinued' => array(
                    'label'    => _('Discontinued'),
                    'selected' => false
                )
            )


        ),
    ),

    'services' => array(
        'status' => array(
            'label' => _('Status'),
            'items' => array(
                'Active'       => array(
                    'label'    => _('Active'),
                    'selected' => true
                ),
                'Suspended'    => array(
                    'label'    => _('Suspended'),
                    'selected' => false
                ),
                'Discontinued' => array(
                    'label'    => _('Discontinued'),
                    'selected' => false
                )
            )


        ),
    ),


    'product_categories' => array(
        'status' => array(
            'label' => _('Status'),
            'items' => array(
                'InProcess'     => array(
                    'label'    => _('In process'),
                    'selected' => true
                ),
                'Active'        => array(
                    'label'    => _('Active'),
                    'selected' => true
                ),
                'Suspended'     => array(
                    'label'    => _('Suspended'),
                    'selected' => false
                ),
                'Discontinuing' => array(
                    'label'    => _('Discontinuing'),
                    'selected' => true
                ),
                'Discontinued'  => array(
                    'label'    => _('Discontinued'),
                    'selected' => false
                )
            )


        ),
    ),


    'part_families' => array(
        'status' => array(
            'label' => _('Status'),
            'items' => array(
                'InProcess' => array(
                    'label'    => _('In process'),
                    'selected' => true
                ),
                'InUse'     => array(
                    'label'    => _('Active'),
                    'selected' => true
                ),

                'Discontinuing' => array(
                    'label'    => _('Discontinuing'),
                    'selected' => true
                ),
                'NotInUse'      => array(
                    'label'    => _('Discontinued'),
                    'selected' => false
                )
            )


        ),
    ),

    'parts' => array(
        'stock_status' => array(
            'label' => _('Stock status'),
            'items' => array(
                'Surplus'      => array(
                    'label'    => _('Surplus'),
                    'selected' => true
                ),
                'Optimal'      => array(
                    'label'    => _('Optimal'),
                    'selected' => true
                ),
                'Low'          => array(
                    'label'    => _('Low'),
                    'selected' => true
                ),
                'Critical'     => array(
                    'label'    => _('Critical'),
                    'selected' => true
                ),
                'Error'        => array(
                    'label'    => _('Error'),
                    'selected' => true
                ),
                'Out_Of_Stock' => array(
                    'label'    => _('Out of stock'),
                    'selected' => true
                )

            )


        ),
    ),

    'part_categories' => array(
        'status' => array(
            'label' => _('Category status'),
            'items' => array(
                'InProcess'     => array(
                    'label'    => _('In Process'),
                    'selected' => true
                ),
                'InUse'         => array(
                    'label'    => _('Active'),
                    'selected' => true
                ),
                'Discontinuing' => array(
                    'label'    => _('Discontinuing'),
                    'selected' => true
                ),
                'NotInUse'      => array(
                    'label'    => _('Discontinued'),
                    'selected' => true
                ),
            )
        )
    ),

    'suppliers'       => array(
        'type' => array(
            'label' => _('Type'),
            'items' => array(
                'Free'     => array(
                    'label'    => _('Free'),
                    'selected' => true
                ),
                'Agent'    => array(
                    'label'    => _("Through agent"),
                    'selected' => true
                ),
                'Archived' => array(
                    'label'    => _('Archived'),
                    'selected' => false
                ),

            ),

        )
    ),
    'agent_suppliers' => array(
        'type' => array(
            'label' => _('Type'),
            'items' => array(
                'Agent'    => array(
                    'label'    => _('Active'),
                    'selected' => true
                ),
                'Archived' => array(
                    'label'    => _('Archived'),
                    'selected' => false
                ),

            ),

        )
    ),

    'supplier_parts' => array(
        'status'      => array(
            'label' => _('Status'),
            'items' => array(
                'Available'    => array(
                    'label'    => _('Available'),
                    'selected' => true
                ),
                'NoAvailable'  => array(
                    'label'    => _('No available'),
                    'selected' => true
                ),
                'Discontinued' => array(
                    'label'    => _('Discontinued'),
                    'selected' => false
                )
            )


        ),
        'part_status' => array(
            'label' => _('Part status'),
            'items' => array(
                'InProcess'     => array(
                    'label'    => _('Part in process'),
                    'selected' => true
                ),
                'InUse'         => array(
                    'label'    => _('Part active'),
                    'selected' => true
                ),
                'Discontinuing' => array(
                    'label'    => _('Part discontinuing'),
                    'selected' => true
                ),
                'NotInUse'      => array(
                    'label'    => _('Part discontinued'),
                    'selected' => true
                ),
            )


        )
    ),


    'agent_parts' => array(


        'part_status' => array(
            'label' => _('Required by client'),
            'items' => array(
                'Required'    => array(
                    'label'    => _('Required by client'),
                    'selected' => true
                ),
                'NotRequired' => array(
                    'label'    => _('Not required'),
                    'selected' => true
                ),
            )


        ),
        'status'      => array(
            'label' => _('Availability'),
            'items' => array(
                'Available'    => array(
                    'label'    => _('Available'),
                    'selected' => true
                ),
                'NoAvailable'  => array(
                    'label'    => _('No available'),
                    'selected' => true
                ),
                'Discontinued' => array(
                    'label'    => _('Discontinued'),
                    'selected' => false
                )
            )


        ),

    ),


    'barcodes'                => array(
        'status' => array(
            'label' => _('Status'),
            'items' => array(
                'Available' => array(
                    'label'    => _('Available'),
                    'selected' => true
                ),
                'Used'      => array(
                    'label'    => _('Used'),
                    'selected' => true
                ),
                'Reserved'  => array(
                    'label'    => _('Reserved'),
                    'selected' => true
                ),
            )


        ),
    ),
    'part_stock_transactions' => array(
        'type' => array(
            'label' => _('Transaction type'),
            'items' => array(


                'In'   => array(
                    'label'    => _('In'),
                    'selected' => true
                ),
                'Out'  => array(
                    'label'    => _('Out'),
                    'selected' => true
                ),
                'Lost' => array(
                    'label'    => _('Lost'),
                    'selected' => true
                ),
                'Move' => array(
                    'label'    => _('Move'),
                    'selected' => false
                ),

                'NoDispatched' => array(
                    'label'    => _('No Dispatched'),
                    'selected' => true
                ),
                'OIP'          => array(
                    'label'    => 'OIP',
                    'selected' => true,
                    'title'    => _('Orders in Process')
                ),

                'Audit' => array(
                    'label'    => _('Audits'),
                    'selected' => false
                ),


            )


        ),
    ),
    'locations'               => array(
        'flags' => array(
            'label' => _('Flags'),
            'items' => array(
                'None'   => array(
                    'label'    => 'None',
                    'selected' => true
                ),
                'Blue'   => array(
                    'label'    => 'Blue',
                    'selected' => true
                ),
                'Green'  => array(
                    'label'    => 'Green',
                    'selected' => true
                ),
                'Orange' => array(
                    'label'    => 'Orange',
                    'selected' => true
                ),
                'Pink'   => array(
                    'label'    => 'Pink',
                    'selected' => true
                ),
                'Purple' => array(
                    'label'    => 'Purple',
                    'selected' => true
                ),
                'Red'    => array(
                    'label'    => 'Red',
                    'selected' => true
                ),
                'Yellow' => array(
                    'label'    => 'Yellow',
                    'selected' => true
                ),

            )


        ),
    ),
    'location_history'        => array(
        'type' => array(
            'label' => _('Type'),
            'items' => array(
                //    'Notes'=>array('label'=>_('Notes'), 'selected'=>true),
                'Changes' => array(
                    'label'    => _('Changes'),
                    'selected' => true
                ),
            ),

        )
    ),
    'campaigns'               => array(
        'status' => array(
            'label' => _('Status'),
            'items' => array(
                'Active'    => array(
                    'label'    => _('Active'),
                    'selected' => true
                ),
                'Waiting'   => array(
                    'label'    => _('Waiting'),
                    'selected' => true
                ),
                'Suspended' => array(
                    'label'    => _('Suspended'),
                    'selected' => true
                ),
                'Finish'    => array(
                    'label'    => _('Finish'),
                    'selected' => false
                ),

            ),

        )
    ),

    'fixed_deals' => array(
        'status' => array(
            'label' => _('Status'),
            'items' => array(
                'Active' => array(
                    'label'    => _('Active'),
                    'selected' => true
                ),

                'Suspended' => array(
                    'label'    => _('Suspended'),
                    'selected' => true
                ),


            ),

        )

    ),

    'deals' => array(
        'status'  => array(
            'label' => _('Status'),
            'items' => array(
                'Active'    => array(
                    'label'    => _('Active'),
                    'selected' => true
                ),
                'Waiting'   => array(
                    'label'    => _('Waiting'),
                    'selected' => true
                ),
                'Suspended' => array(
                    'label'    => _('Suspended'),
                    'selected' => true
                ),
                'Finish'    => array(
                    'label'    => _('Finish'),
                    'selected' => false
                ),

            ),

        ),
        'trigger' => array(
            'label' => _('Trigger'),
            'items' => array(

                'Order'             => array(
                    'label'    => _('Order'),
                    'selected' => true
                ),
                'Product_Category'  => array(
                    'label'    => _(
                        'Product category'
                    ),
                    'selected' => true
                ),
                'Product'           => array(
                    'label'    => _('Product'),
                    'selected' => false
                ),
                'Customer'          => array(
                    'label'    => _('Customer'),
                    'selected' => true
                ),
                'Customer_Category' => array(
                    'label'    => _('Customer category'),
                    'selected' => true
                ),
                'Customer_List'     => array(
                    'label'    => _('Customer list'),
                    'selected' => true
                ),
            ),


        )
    ),

    'deal_components' => array(
        'status' => array(
            'label' => _('Status'),
            'items' => array(
                'Active'    => array(
                    'label'    => _('Active'),
                    'selected' => true
                ),
                'Waiting'   => array(
                    'label'    => _('Waiting'),
                    'selected' => true
                ),
                'Suspended' => array(
                    'label'    => _('Suspended'),
                    'selected' => true
                ),
                'Finish'    => array(
                    'label'    => _('Finish'),
                    'selected' => false
                ),

            ),

        )

    ),

    'campaign_history' => array(
        'type' => array(
            'label' => _('Type'),
            'items' => array(
                'Notes'   => array(
                    'label'    => _('Notes'),
                    'selected' => true
                ),
                'Changes' => array(
                    'label'    => _('Changes'),
                    'selected' => true
                ),
            ),

        )
    ),

    'deal_history' => array(
        'type' => array(
            'label' => _('Type'),
            'items' => array(
                'Notes'   => array(
                    'label'    => _('Notes'),
                    'selected' => true
                ),
                'Changes' => array(
                    'label'    => _('Changes'),
                    'selected' => true
                ),
            ),

        )
    ),

    'list_history' => array(
        'type' => array(
            'label' => _('Type'),
            'items' => array(
                'Notes'   => array(
                    'label'    => _('Notes'),
                    'selected' => true
                ),
                'Changes' => array(
                    'label'    => _('Changes'),
                    'selected' => true
                ),
            ),

        )
    ),

    'supplier_orders' => array(
        'state' => array(
            'label' => _('State'),
            'items' => array(


                'InProcess'                   => array(
                    'label'    => _('In process'),
                    'selected' => true
                ),
                'Submitted' => array(
                    'label'    => _('Submitted'),
                    'selected' => true
                ),
                'InTransit' => array(
                    'label'    => _('In transit'),
                    'selected' => true
                ),
                'ReceivedChecked'             => array(
                    'label'    => _('In warehouse'),
                    'selected' => true
                ),
                'Placed'                      => array(
                    'label'    => _('Booked in'),
                    'selected' => true
                ),

                'InvoiceChecked' => array(
                    'label'    => _('Booked in').' ('._('Costing done').')',
                    'selected' => true
                ),
                'Cancelled'      => array(
                    'label'    => _('Cancelled'),
                    'selected' => false
                ),
            ),


        ),


    ),
    'agent_orders'    => array(
        'state' => array(
            'label' => _('State'),
            'items' => array(
                'InProcess' => array(
                    'label'    => _('In process'),
                    'selected' => true
                ),

                'Submitted'       => array(
                    'label'    => _('Submitted to agent'),
                    'selected' => true
                ),
                'InTransit'       => array(
                    'label'    => _('In transit'),
                    'selected' => true
                ),
                'ReceivedChecked' => array(
                    'label'    => _('In warehouse'),
                    'selected' => true
                ),
                'Placed'          => array(
                    'label'    => _('Booked in'),
                    'selected' => true
                ),
                'Cancelled'       => array(
                    'label'    => _('Cancelled'),
                    'selected' => false
                ),
            ),


        ),


    ),


    'supplier_deliveries' => array(
        'state' => array(
            'label' => _('State'),
            'items' => array(
                'InProcess' => array(
                    'label'    => _('In process'),
                    'selected' => true
                ),

                'Received'       => array(
                    'label'    => _('Received'),
                    'selected' => true
                ),
                'Checked'        => array(
                    'label'    => _('Checked'),
                    'selected' => true
                ),
                'Placed'         => array(
                    'label'    => _('Booked in'),
                    'selected' => true
                ),
                'InvoiceChecked' => array(
                    'label'    => _('Booked in').' ('._('Costing done').')',
                    'selected' => true
                ),
                'Cancelled'      => array(
                    'label'    => _('Cancelled'),
                    'selected' => false
                ),
            ),


        ),


    ),

    'production_orders' => array(
        'state' => array(
            'label' => _('State'),
            'items' => array(


                'InProcess'       => array(
                    'label'    => _('Planning'),
                    'selected' => true
                ),
                'Manufacturing'   => array(
                    'label'    => _('Manufacturing'),
                    'selected' => true
                ),
                'ReceivedChecked' => array(
                    'label'    => _('In quality control'),
                    'selected' => true
                ),
                'Placed'          => array(
                    'label'    => _('Booked in'),
                    'selected' => true
                ),

                'Cancelled' => array(
                    'label'    => _('Cancelled'),
                    'selected' => false
                ),
            ),


        ),


    ),

    'production_deliveries' => array(
        'state' => array(
            'label' => _('State'),
            'items' => array(
                'InProcess' => array(
                    'label'    => _('Items manufactured'),
                    'selected' => true
                ),


                'Checked' => array(
                    'label'    => _('Checked'),
                    'selected' => true
                ),
                'Placed'  => array(
                    'label'    => _('Booked in'),
                    'selected' => true
                ),

                'Cancelled' => array(
                    'label'    => _('Cancelled'),
                    'selected' => false
                ),
            ),


        ),


    ),

    'agent_client_orders'     => array(
        'state_agent' => array(
            'label' => _('State'),
            'items' => array(


                'Submitted' => array(
                    'label'    => _('To do'),
                    'selected' => true
                ),


                'Dispatched' => array(
                    'label'    => _('Dispatched'),
                    'selected' => true
                ),
                'Cancelled'  => array(
                    'label'    => _('Cancelled'),
                    'selected' => false
                ),
            ),

        ),


    ),
    'agent_client_deliveries' => array(
        'state_agent' => array(
            'label' => _('State'),
            'items' => array(
                'InProcess'  => array(
                    'label'    => _('In process'),
                    'selected' => true
                ),
                'Dispatched' => array(
                    'label'    => _('Dispatched'),
                    'selected' => true
                ),
                'Received'   => array(
                    'label'    => _('Received'),
                    'selected' => true
                ),
                'Checked'    => array(
                    'label'    => _('Checked'),
                    'selected' => true
                ),
                'Placed'     => array(
                    'label'    => _('Booked in'),
                    'selected' => true
                ),
                'Cancelled'  => array(
                    'label'    => _('Cancelled'),
                    'selected' => false
                ),
            ),


        ),


    ),


    'websites' => array(
        'status' => array(
            'label' => _('Status'),
            'items' => array(
                'Active'    => array(
                    'label'    => _('Live'),
                    'selected' => true
                ),
                'InProcess' => array(
                    'label'    => _('In construction'),
                    'selected' => false
                ),

                'Closed' => array(
                    'label'    => _('Closed'),
                    'selected' => false
                )


            )


        )
    ),

    'webpages' => array(
        'state' => array(
            'label' => _('State'),
            'items' => array(
                'Online'  => array(
                    'label'    => _('Online'),
                    'selected' => true
                ),
                'Offline' => array(
                    'label'    => _('Offline'),
                    'selected' => false
                ),

            )


        ),
        'type'  => array(
            'label' => _('Type'),
            'items' => array(

                'Cats'   => array(
                    'label'    => _('Departments'),
                    'selected' => true
                ),
                'Prods'  => array(
                    'label'    => _('Families'),
                    'selected' => true
                ),
                'Prod'   => array(
                    'label'    => _('Products'),
                    'selected' => true
                ),
                'Sys'    => array(
                    'label'    => _('System'),
                    'selected' => true
                ),
                'Others' => array(
                    'label'    => _('Other'),
                    'selected' => true
                ),

            )


        ),

    ),


    'online_webpages_in_webpage_type' => array(),

    'online_webpages' => array(
        'type' => array(
            'label' => _('Type'),
            'items' => array(

                'Cats'   => array(
                    'label'    => _('Departments'),
                    'selected' => true
                ),
                'Prods'  => array(
                    'label'    => _('Families'),
                    'selected' => true
                ),
                'Prod'   => array(
                    'label'    => _('Products'),
                    'selected' => true
                ),
                'Sys'    => array(
                    'label'    => _('System'),
                    'selected' => true
                ),
                'Others' => array(
                    'label'    => _('Info'),
                    'selected' => true
                ),

            )


        )
    ),

    'category_root_subjects' => array(
        'status' => array(
            'label' => _('Status'),
            'items' => array(
                'Assigned'   => array(
                    'label'    => _('Assigned'),
                    'selected' => true
                ),
                'NoAssigned' => array(
                    'label'    => _('No Assigned'),
                    'selected' => false
                ),

            )


        ),
    ),
    'ec_sales_list'          => array(
        'tax_status' => array(
            'label' => _('Tax number status'),
            'items' => array(
                'Yes'     => array(
                    'label'    => _('Tax number valid'),
                    'selected' => true
                ),
                'No'      => array(
                    'label'    => _('Tax number invalid'),
                    'selected' => true
                ),
                'Missing' => array(
                    'label'    => _('No tax number'),
                    'selected' => true
                ),

            ),

        )
    ),
    'leakages_transactions'  => array(
        'type' => array(
            'label' => _('Type'),
            'items' => array(
                'found' => array(
                    'label'    => _('Found'),
                    'selected' => true
                ),
                'lost'  => array(
                    'label'    => _('Lost'),
                    'selected' => true
                )

            ),

        )
    ),

    'lost_stock' => array(
        'type' => array(
            'label' => _('Type'),
            'items' => array(
                'Lost'   => array(
                    'label'    => _('Lost'),
                    'selected' => true
                ),
                'Broken' => array(
                    'label'    => _('Damaged'),
                    'selected' => true
                ),
                'Error'  => array(
                    'label'    => _('Error'),
                    'selected' => false
                )

            ),

        )
    ),

    'stock_given_free'    => array(
        'type' => array(
            'label' => _('Type'),
            'items' => array(
                'Order'       => array(
                    'label'    => _('Offer'),
                    'selected' => true
                ),
                'Replacement' => array(
                    'label'    => _('Replacement'),
                    'selected' => true
                )

            ),

        )
    ),
    'part_barcode_errors' => array(
        'type' => array(
            'label' => _('Type'),
            'items' => array(
                'Duplicated'       => array(
                    'label'    => _('Duplicated'),
                    'selected' => true
                ),
                'Size'             => array(
                    'label'    => _('Number digits'),
                    'selected' => true
                ),
                'Short_Duplicated' => array(
                    'label'    => _('No check digit (Will dup.)'),
                    'selected' => true
                ),
                'Checksum_missing' => array(
                    'label'    => _('No check digit'),
                    'selected' => true
                ),
                'Checksum'         => array(
                    'label'    => _('Invalid check digit'),
                    'selected' => true
                )

            ),

        )
    ),

    'parts_weight_errors' => array(
        'type' => array(
            'label' => _('Type'),
            'items' => array(
                'Missing'     => array(
                    'label'    => _('Missing'),
                    'selected' => true
                ),
                'Underweight' => array(
                    'label'    => _('Underweight'),
                    'selected' => true
                ),
                'Overweight'  => array(
                    'label'    => _('Overweight'),
                    'selected' => true
                )

            ),

        )
    ),

    'purged_orders'  => array(
        'state' => array(
            'label' => _('State'),
            'items' => array(
                'In_Process' => array(
                    'label'    => _('In Process'),
                    'selected' => true
                ),
                'Purged'     => array(
                    'label'    => _('Purged'),
                    'selected' => true
                ),
                'Exculpated' => array(
                    'label'    => _('Exculpated'),
                    'selected' => true
                ),
                'Cancelled'  => array(
                    'label'    => _('Purge cancelled'),
                    'selected' => true
                ),


            ),

        )
    ),
    'purges_history' => array(
        'type' => array(
            'label' => _('Type'),
            'items' => array(
                'Notes'   => array(
                    'label'    => _('Notes'),
                    'selected' => true
                ),
                'Changes' => array(
                    'label'    => _('Changes'),
                    'selected' => true
                ),
            ),

        )
    ),
    //'Ready','Sent to SES','Rejected by SES','Sent','Soft Bounce','Hard Bounce','Delivered','Spam','Opened','Clicked','Error'


    'sent_emails' => array(
        'state' => array(
            'label' => _('State'),
            'items' => array(
                'Sending'   => array(
                    'label'    => _('Sending'),
                    'selected' => true
                ),
                'Delivered' => array(
                    'label'    => _('Delivered'),
                    'selected' => true
                ),
                'Opened'    => array(
                    'label'    => _('Opened'),
                    'selected' => true
                ),
                'Clicked'   => array(
                    'label'    => _('Clicked'),
                    'selected' => true
                ),
                'Bounced'   => array(
                    'label'    => _('Bounced'),
                    'selected' => true
                ),
                'Spam'      => array(
                    'label'    => _('Spam'),
                    'selected' => true
                ),
                'Error'     => array(
                    'label'    => _('Errors'),
                    'selected' => true
                ),


            ),

        )
    ),
    'mailshots'   => array(
        'type' => array(
            'label' => _('State'),
            'items' => array(
                'Newsletter'      => array(
                    'label'    => _('Newsletters'),
                    'selected' => true
                ),
                'Marketing'       => array(
                    'label'    => _('Marketing'),
                    'selected' => true
                ),
                'AbandonedCart'   => array(
                    'label'    => _('Orders in basket'),
                    'selected' => false
                ),
                'GRReminder'      => array(
                    'label'    => _('Reorder reminder'),
                    'selected' => false
                ),
                'OOSNotification' => array(
                    'label'    => _('Back in stock'),
                    'selected' => false
                ),


            ),

        )
    ),
    'payments'    => array(
        'status' => array(
            'label' => _('Status'),
            'items' => array(
                'Pending'   => array(
                    'label'    => _('Pending'),
                    'selected' => true
                ),
                'Completed' => array(
                    'label'    => _('Completed'),
                    'selected' => true
                ),
                'Cancelled' => array(
                    'label'    => _('Cancelled'),
                    'selected' => true
                ),
                'Error'     => array(
                    'label'    => _('Error'),
                    'selected' => true
                ),
                'Declined'  => array(
                    'label'    => _('Declined'),
                    'selected' => true
                ),


            ),

        ),
        'type'   => array(
            'label' => _('Type'),
            'items' => array(
                'Payment' => array(
                    'label'    => _('Payment'),
                    'selected' => true
                ),
                'Refund'  => array(
                    'label'    => _('Refund'),
                    'selected' => true
                ),
                'Credit'  => array(
                    'label'    => _('Credit'),
                    'selected' => true
                )


            ),

        )
    ),

    'users'                => array(
        'active' => array(
            'label' => _('Active'),
            'items' => array(
                'Yes' => array(
                    'label'    => _('Active'),
                    'selected' => true
                ),
                'No'  => array(
                    'label'    => _('Suspended'),
                    'selected' => true
                ),
            ),

        )
    ),

    //'InProcess','Submitted','Inputted','Dispatched','Received','Checked','Placed','Costing','InvoiceChecked','Cancelled'
    'supplier_order_items' => array(
        'state' => array(
            'label' => _('Status'),
            'items' => array(
                'InProcess'   => array(
                    'label'    => _('In process'),
                    'selected' => true
                ),
                'Submitted' => array(
                    'label'    => _('Submitted'),
                    'selected' => true
                ),
                'InDelivery' => array(
                    'label'    => _('In transit'),
                    'selected' => true
                ),
                'Receiving'     => array(
                    'label'    => _('Booking-in in process'),
                    'selected' => true
                ),
                'Received'  => array(
                    'label'    => _('Booked in'),
                    'selected' => true
                ),
                'Cancelled'  => array(
                    'label'    => _('No received'),
                    'selected' => true
                ),

            ),

        ),
    ),
);


