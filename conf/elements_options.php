<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 December 2015 at 14:28:35 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$elements_options = array(


    'customers'              => array(
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
        'activity' => array(
            'label' => _('Active/Lost'),
            'items' => array(
                'Active' => array(
                    'label'    => _('Active'),
                    'selected' => true
                ),
                'Losing' => array(
                    'label'    => _('Losing'),
                    'selected' => true
                ),
                'Lost'   => array(
                    'label'    => _('Lost'),
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
    'customer_history'       => array(
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
    'supplier_part_history'  => array(
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
    'agent_history'          => array(
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
    'orders'                 => array(
        'dispatch' => array(
            'label' => _('Dispatch state'),
            'items' => array(
                'InProcessCustomer' => array(
                    'label'    => _('Basket'),
                    'selected' => true
                ),
                'InProcess'         => array(
                    'label'    => _('In process'),
                    'selected' => true
                ),
                'Warehouse'         => array(
                    'label'    => _('Warehouse'),
                    'selected' => true
                ),
                'Dispatched'        => array(
                    'label'    => _('Dispatched'),
                    'selected' => true
                ),
                'Cancelled'         => array(
                    'label'    => _('Cancelled'),
                    'selected' => false
                ),
                'Suspended'         => array(
                    'label'    => _('Suspended'),
                    'selected' => false
                )
            ),
        ),
        'type'     => array(
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

        /*
        'source'   => array(
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
        */

        'payment'  => array(
            'label' => _('Payment'),
            'items' => array(
                'Paid'           => array(
                    'label'    => _('Paid'),
                    'selected' => true
                ),
                'PartiallyPaid'  => array(
                    'label'    => _('Partially Paid'),
                    'selected' => true
                ),
                'Unknown'        => array(
                    'label'    => _('Unknown'),
                    'selected' => true
                ),
                'WaitingPayment' => array(
                    'label'    => _('Waiting Payment'),
                    'selected' => true
                ),
                'NA'             => array(
                    'label'    => _('NA'),
                    'selected' => true
                ),
            )
        )

    ),

    'orders_pending'                 => array(

        'flow' => array(
            'label' => _('Process flow'),
            'items' => array(
                'Basket' => array(
                    'label'    => _('IPrcss'),
                    'title'    => _('In Process'),
                    'selected' => false
                ),
                'Submitted_Unpaid'         => array(
                    'label'    => _('S (UPaid)'),
                    'title'    => _('Submitted (Unpaid)'),
                    'selected' => true
                ),
                'Submitted_Paid'         => array(
                    'label'    => _('S (Paid)'),
                    'selected' => true
                ),
                'InWarehouse'        => array(
                    'label'    => _('Warhs'),
                    'title'    => _('In Warehouse'),
                    'selected' => true
                ),
                'Packed'         => array(
                    'label'    => _('Pkd'),
                    'title'    => _('Packed'),
                    'selected' => true
                ),
                'Dispatch_Ready'         => array(
                    'label'    => _('Disp Rdy'),
                    'title'    => _('Dispatch Ready'),
                    'selected' => true
                ),
                'Dispatched_Today'         => array(
                    'label'    => _('Disp Tdy'),
                    'title'    => _('Dispatched today'),
                    'selected' => false
                )
            ),
        ),


    ),
    'invoices'               => array(
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
    'delivery_notes'         => array(
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
                'Packing'  => array(
                    'label'    => _('Packing'),
                    'selected' => true
                ),
                'Done'     => array(
                    'label'    => _('Done'),
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
                'Sample'       => array(
                    'label'    => _(
                        'Sample'
                    ),
                    'selected' => true
                ),
                'Donation'     => array(
                    'label'    => _(
                        'Donation'
                    ),
                    'selected' => true
                ),
                'Replacements' => array(
                    'label'    => _(
                        'Replacements'
                    ),
                    'selected' => true
                ),
                'Shortages'    => array(
                    'label'    => _(
                        'Shortages'
                    ),
                    'selected' => true
                ),
            )
        )

    ),

    'products' => array(
        'status' => array(
            'label' => _('Status'),
            'items' => array(
                //'InProcess'=>array('label'=>_('In process'), 'selected'=>true),
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
                //'InProcess'=>array('label'=>_('In process'), 'selected'=>true),
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
                    'label'    => _('Empty'),
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
            'InProcess'    => array(
                'label'    => _('In Process'),
                'selected' => true
            ),
            'InUse'    => array(
                'label'    => _('Active'),
                'selected' => true
            ),
            'Discontinuing' => array(
                'label'    => _('Discontinuing'),
                'selected' => true
            ),
            'NotInUse' => array(
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
                    'selected' => false
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

    'supplier_parts'          => array(
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
                'InUse'    => array(
                    'label'    => _('Part active'),
                    'selected' => true
                ),
                'NotInUse' => array(
                    'label'    => _('Part discontinued'),
                    'selected' => true
                ),
            )


        )
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
                'Move'         => array(
                    'label'    => _('Movements'),
                    'selected' => true
                ),
                'NoDispatched' => array(
                    'label'    => _('No Dispatched'),
                    'selected' => true
                ),
                'Audit'        => array(
                    'label'    => _('Audits'),
                    'selected' => true
                ),
                'In'           => array(
                    'label'    => _('In'),
                    'selected' => true
                ),
                'Out'          => array(
                    'label'    => _('Out'),
                    'selected' => true
                ),
                'OIP'          => array(
                    'label'    => 'OIP',
                    'selected' => true,
                    'title'    => _('Orders in Process')
                )

            )


        ),
    ),
    'locations'               => array(
        'flags' => array(
            'label' => _('Flags'),
            'items' => array(
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

                'Order'              => array(
                    'label'    => _('Order'),
                    'selected' => true
                ),
                'Product_Category'   => array(
                    'label'    => _(
                        'Product category'
                    ),
                    'selected' => true
                ),
                'Product'            => array(
                    'label'    => _('Product'),
                    'selected' => false
                ),
                'Customer'           => array(
                    'label'    => _('Customer'),
                    'selected' => true
                ),
                'Customer_Cateogory' => array(
                    'label'    => _(
                        'Customer cateogry'
                    ),
                    'selected' => true
                ),
                'Customer_List'      => array(
                    'label'    => _('Customer list'),
                    'selected' => true
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

    'supplier_orders'     => array(
        'state' => array(
            'label' => _('State'),
            'items' => array(


                'InProcess'                   => array(
                    'label'    => _(
                        'In process'
                    ),
                    'selected' => true
                ),
                'SubmittedInputtedDispatched' => array(
                    'label'    => _(
                        'Submitted, in transit'
                    ),
                    'selected' => true
                ),
                'ReceivedChecked'             => array(
                    'label'    => _(
                        'In warehouse'
                    ),
                    'selected' => true
                ),
                'Placed'                      => array(
                    'label'    => _(
                        'Placed'
                    ),
                    'selected' => true
                ),
                'Cancelled'                   => array(
                    'label'    => _(
                        'Cancelled'
                    ),
                    'selected' => false
                ),
            ),


        ),


    ),
    'supplier_deliveries' => array(
        'state' => array(
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
                    'label'    => _('Placed'),
                    'selected' => true
                ),
                'Cancelled'  => array(
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


                'InProcessbyClient' => array(
                    'label'    => _(
                        'In process by client'
                    ),
                    'selected' => true
                ),
                'InProcess'         => array(
                    'label'    => _('In process'),
                    'selected' => true
                ),
                'Send'              => array(
                    'label'    => _('Dispatched'),
                    'selected' => true
                ),
                'Cancelled'         => array(
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
                    'label'    => _('Placed'),
                    'selected' => true
                ),
                'Cancelled'  => array(
                    'label'    => _('Cancelled'),
                    'selected' => false
                ),
            ),


        ),


    ),

    'webpages' => array(
        'status' => array(
            'label' => _('Status'),
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
);

?>
