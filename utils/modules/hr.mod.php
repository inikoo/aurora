<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  27 December 2019  11:37::15  +0800, Kuala Lumpur Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_hr_module() {
    return array(

        'sections' => array(
            'employees' => array(
                'type'      => 'navigation',
                'label'     => _('Employees'),
                'title'     => _("Employees"),
                'icon'      => 'user-headset',
                'reference' => 'hr',


                'subtabs_parent' => array(
                    'employees.uploads' => 'employees.history_uploads',
                    'employees.history' => 'employees.history_uploads',


                ),

                'tabs' => array(
                    'employees'         => array(
                        'label' => _('Employees')
                    ),
                    'deleted.employees' => array(
                        'label' => _('Deleted employees'),
                        'icon'  => 'trash',
                        'class' => 'right icon_only'
                    ),
                    'exemployees'       => array(
                        'label' => _('Ex employees'),
                        'title' => _('Ex Employees'),
                        'class' => ''
                    ),


                )

            ),

            'contractors'      => array(
                'type'      => 'navigation',
                'label'     => _('Contractors'),
                'icon'      => 'user-hard-hat',
                'reference' => 'hr/contractors',
                'tabs'      => array(
                    'contractors'         => array('label' => _('Contractors')),
                    'deleted.contractors' => array(
                        'label' => _('Deleted contractors'),
                        'icon'  => 'trash',
                        'class' => 'right icon_only'
                    ),

                )


            ),

            /*
              'salesmen'      => array(
                  'type'      => 'navigation',
                  'label'     => _('Account managers'),
                  'icon'      => 'handshake',
                  'reference' => 'hr/salesmen',
                  'tabs'      => array(
                      'salesmen'         => array('label' => _('Account managers')),


                  )


              ),

    */
            /*
            'overtimes'        => array(
                'type'      => 'navigation',
                'label'     => _('Overtimes'),
                'icon'      => 'clock',
                'reference' => 'hr/overtimes',
                'tabs'      => array(
                    'overtimes' => array('label' => _('Overtimes')),

                )


            ),

            'organization'     => array(
                'type'      => 'navigation',
                'label'     => _('Organization'),
                'title'     => _('Organization'),
                'icon'      => 'sitemap',
                'reference' => 'hr/organization',
                'tabs'      => array(
                    'organization.areas'       => array(
                        'label' => _(
                            'Working Areas'
                        ),
                        'class' => 'hide'
                    ),
                    'organization.departments' => array(
                        'label' => _(
                            'Company departments'
                        ),
                        'class' => 'hide'
                    ),
                    'organization.positions'   => array(
                        'label' => _(
                            'Job positions'
                        )
                    ),
                    'organization.organigram'  => array(
                        'label' => _(
                            'Organizational chart'
                        ),
                        'class' => 'hide'
                    ),


                )
            ),
             */
            'employee'         => array(
                'type' => 'object',


                'tabs' => array(
                    'employee.details'                 => array(
                        'label' => _('Data'),
                        'icon'  => 'database'
                    ),
                    'employee.today_timesheet.records' => array(
                        'label' => _('Today timesheet')
                    ),
                    'employee.timesheets'              => array(
                        'label' => _('Timesheets')
                    ),
                    'employee.history'                 => array(
                        'label' => _('History, notes'),
                        'icon'  => 'road',
                        'class' => 'right icon_only',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number History Records'
                        ),
                    ),
                    'employee.images'                  => array(
                        'label' => _('Images'),
                        'icon'  => 'camera-retro',
                        'class' => 'right icon_only'
                    ),
                    'employee.attachments'             => array(
                        'label'         => _('Attachments'),
                        'icon'          => 'paperclip',
                        'class'         => 'right icon_only',
                        'quantity_data' => array(
                            'object' => '_object',
                            'field'  => 'Number Attachments'
                        ),
                    ),


                )

            ),
            'deleted.employee' => array(
                'type' => 'object',


                'tabs' => array(
                    'deleted.employee.history' => array(
                        'label' => _(
                            'History'
                        ),
                        'icon'  => 'sticky-note'
                    ),


                )

            ),

            'employee.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'employee.new' => array(
                        'label' => _('new employee')
                    ),

                )

            ),

            'employee.attachment.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'employee.attachment.new' => array(
                        'label' => _(
                            'new attachment'
                        )
                    ),

                )

            ),
            'employee.user.new'       => array(
                'type' => 'new_object',
                'tabs' => array(
                    'employee.user.new' => array(
                        'label' => _(
                            'new system user'
                        )
                    ),

                )

            ),
            'contractor.user.new'     => array(
                'type' => 'new_object',
                'tabs' => array(
                    'contractor.user.new' => array(
                        'label' => _(
                            'new system user'
                        )
                    ),

                )

            ),

            'employee.attachment' => array(
                'type' => 'object',
                'tabs' => array(
                    'employee.attachment.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'employee.attachment.history' => array(
                        'label' => _(
                            'History'
                        ),
                        'icon'  => 'clock'
                    ),

                )

            ),

            'contractor'         => array(
                'type' => 'object',
                'tabs' => array(
                    'contractor.details' => array(
                        'label' => _(
                            'Data'
                        ),
                        'icon'  => 'database'
                    ),
                    'contractor.history' => array(
                        'label' => _(
                            'History, notes'
                        ),
                        'icon'  => 'sticky-note'
                    )

                )

            ),
            'deleted.contractor' => array(
                'type' => 'object',
                'tabs' => array(
                    'deleted.contractor.history' => array(
                        'label' => _(
                            'History'
                        ),
                        'icon'  => 'sticky-note'
                    ),


                )

            ),
            'contractor.new'     => array(
                'type' => 'new_object',
                'tabs' => array(
                    'contractor.new' => array(
                        'label' => _(
                            'new contractor'
                        )
                    ),

                )

            ),

            'timesheet'  => array(
                'type' => 'object',
                'tabs' => array(
                    'timesheet.records' => array(
                        'label' => _('Clockings')
                    ),

                )

            ),
            'timesheets' => array(
                'type'      => 'navigation',
                'icon'      => 'stopwatch',
                'label'     => _('Timesheets'),
                'reference' => 'timesheets/day/'.date('Ymd'),
                'tabs'      => array(
                    'timesheets.months'     => array(
                        'label' => _('Months')
                    ),
                    'timesheets.weeks'      => array(
                        'label' => _('Weeks')
                    ),
                    'timesheets.days'       => array(
                        'label' => _('Days')
                    ),
                    'timesheets.employees'  => array(
                        'label' => _("Employes'")
                    ),
                    'timesheets.timesheets' => array(
                        'label' => _('Timesheets')
                    ),
                )
            ),


            'clocking_machines'    => array(
                'type'           => 'navigation',
                'icon'           => 'chess-clock',
                'label'          => _('Clocking-in Machines'),
                'reference'      => 'clocking_machines',
                'subtabs_parent' => array(
                    'nfc_tags'         => 'clocking_machines.tags',
                    'pending_nfc_tags' => 'clocking_machines.tags',

                ),
                'tabs'           => array(
                    'clocking_machines'      => array(
                        'icon'  => 'chess-clock',
                        'label' => _('Clocking-in Machines')
                    ),
                    'clocking_machines.tags' => array(
                        'icon'    => 'id-card-alt',
                        'label'   => _('NFC Tags'),
                        'subtabs' => array(
                            'nfc_tags'         => array(
                                'icon'  => 'id-card-alt',
                                'label' => _('Registered nfc-tags')
                            ),
                            'pending_nfc_tags' => array(
                                'icon'  => 'head-side-medical',
                                'label' => _('Pending nfc-tags')
                            )
                        )
                    ),


                )

            ),
            'clocking_machine.new' => array(
                'type' => 'new_object',
                'tabs' => array(
                    'clocking_machine.new' => array(
                        'label' => _('new clocking-in machine')
                    ),

                )

            ),
            'clocking_machine'     => array(
                'type' => 'object',
                'tabs' => array(
                    'clocking_machine.details' => array(
                        'icon'  => 'sliders-h',
                        'label' => _('Settings')
                    ),

                )

            ),

            'new_timesheet_record' => array(
                'type'      => 'new',
                'label'     => _('New timesheet record'),
                'title'     => _('New timesheet record'),
                'icon'      => 'clock',
                'reference' => 'hr/new_timesheet_record',
                'tabs'      => array(
                    'timesheet_record.new'    => array(
                        'label' => _(
                            'New timesheet record'
                        ),
                        'title' => _(
                            'New timesheet record'
                        )
                    ),
                    'timesheet_record.import' => array(
                        'label' => _('Import'),
                        'title' => _(
                            'Import timesheet record'
                        )
                    ),
                    'timesheet_record.api'    => array(
                        'label' => _('API'),
                        'title' => _('API')
                    ),
                    'timesheet_record.cancel' => array(
                        'class' => 'right',
                        'label' => _('Cancel'),
                        'title' => _('Cancel'),
                        'icon'  => 'sign-out fa-flip-horizontal'
                    ),

                )

            ),


            'position' => array(
                'type' => 'object',


                'tabs' => array(


                    'position.employees' => array(
                        'label' => _(
                            'Employees'
                        )
                    ),


                )

            ),


            'uploads'    => array(
                'type' => '',
                'tabs' => array(
                    'uploads' => array(
                        'label' => _(
                            'Uploads'
                        )
                    ),

                )

            ),
            'upload'     => array(
                'type' => 'object',
                'tabs' => array(
                    'upload.employees' => array(
                        'label' => _(
                            'Upload Records'
                        )
                    ),

                )

            ),
            'hr.history' => array(
                'type'      => 'navigation',
                'label'     => '',
                'icon'      => 'road',
                'reference' => 'hr/history',
                'class'     => 'icon_only right',
                'tabs'      => array(
                    'hr.history' => array(
                        'label' => _('History'),
                        'icon'  => 'road',
                        'class' => ''
                    ),
                    'hr.uploads' => array(
                        'label' => _('Uploads'),
                        'icon'  => 'upload',
                        'class' => ''
                    ),


                )


            ),


            'sales_representative' => array(
                'type' => 'object',


                'sales_representative' => array(

                    'sales_representative.customers' => array(
                        'label' => _('Customers')
                    ),
                    'sales_representative.invoices'  => array(
                        'label' => _('Invoices')
                    ),
                    'sales_representative.prospects' => array(
                        'label' => _('Prospects')
                    ),


                )

            ),


        )
    );
}