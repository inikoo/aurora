<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 Jun 2021 00:55 MYT Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';



$editor = array(
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'System (Script)',
    'Author Alias' => 'System (Script)',
    'v'            => 3


);


$sql="select `Store Key` from `Store Dimension` ";
/** @var TYPE_NAME $db */
$stmt = $db->prepare($sql);
$stmt->execute(
    [

    ]
);
while ($row = $stmt->fetch()) {

    $store=get_object('Store',$row['Store Key']);
    create_email_templates($db, $store);
}


function create_email_templates($db, $store) {

    include_once 'class.Email_Template.php';


    $email_campaign_types_data = array(

        array(
            'Email Campaign Type Status' => 'Active',
            'Email Campaign Type Scope'  => 'User Notification',
            'Email Campaign Type Code'   => 'Delivery Note Dispatched',
        ),
        array(
            'Email Campaign Type Status' => 'InProcess',
            'Email Campaign Type Scope'  => 'Customer Notification',
            'Email Campaign Type Code'   => 'Basket Low Stock',
            'Email Campaign Type Metadata'=>json_encode(
                [
                    'Cool Down Hours'=>'24',
                    'Include Critical'=>'No',
                ]
            )
        ),


    );

    foreach ($email_campaign_types_data as $email_campaign_type_data) {


        $email_campaign_type_data['Email Campaign Type Store Key'] = $store->id;


        $sql = sprintf(
            "INSERT INTO `Email Campaign Type Dimension` (%s) values (%s)", '`'.join('`,`', array_keys($email_campaign_type_data)).'`', join(',', array_fill(0, count($email_campaign_type_data), '?'))
        );


        print "$sql\n";
        print_r($email_campaign_type_data);

        $stmt = $db->prepare($sql);

        $i = 1;
        foreach ($email_campaign_type_data as $key => $value) {
            $stmt->bindValue($i, $value);
            $i++;
        }


        if ($stmt->execute()) {
            $email_campaign_type_key = $db->lastInsertId();
            $email_campaign_type     = get_object('email_campaign_type', $email_campaign_type_key);



            switch ($email_campaign_type->get('Email Campaign Type Code')) {
                case 'New Order':
                case 'New Customer':
                case 'Delivery Note Undispatched':
                case 'Delivery Note Dispatched':

                case 'Invoice Deleted':

                    $html = '';
                    $text = '';

                    switch ($email_campaign_type->get('Email Campaign Type Code')) {
                        case 'New Order':
                            $subject = _('New order').' '.$store->get('Name');
                            break;
                        case 'New Customer':
                            $subject = _('New customer registration').' '.$store->get('Name');
                            break;
                        case 'Delivery Note Undispatched':
                            $subject = _('Delivery note undispatched').' '.$store->get('Name');
                            break;
                        case 'Delivery Note Dispatched':
                            $subject = _('Delivery note dispatched').' '.$store->get('Name');
                            break;
                        case 'Invoice Deleted':
                            $subject = _('Invoice deleted').' '.$store->get('Name');
                            break;
                    }


                    $email_template_data = array(
                        'Email Template Name'                    => $email_campaign_type->get('Email Campaign Type Code'),
                        'Email Template Email Campaign Type Key' => $email_campaign_type->id,
                        'Email Template Role Type'               => 'Transactional',
                        'Email Template Role'                    => $email_campaign_type->get('Email Campaign Type Code'),
                        'Email Template Scope'                   => 'EmailCampaignType',
                        'Email Template Scope Key'               => $email_campaign_type->id,
                        'Email Template Subject'                 => $subject,
                        'Email Template HTML'                    => $html,
                        'Email Template Text'                    => $text,


                        'Email Template Created'      => gmdate('Y-m-d H:i:s'),
                        'Email Template Editing JSON' => ''
                    );

                    // print_r($email_template_data);

                    $email_template = new Email_Template('find', $email_template_data, 'create');

                    $email_campaign_type->fast_update(
                        array(
                            'Email Campaign Type Email Template Key' => $email_template->id
                        )
                    );


                    $email_template->publish();


                    break;





            }


        } else {
            print_r($stmt->errorInfo());
        }


    }

}