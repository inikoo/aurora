<?php


$website_key = $_REQUEST['website_key'];

include_once('EcomB2B/class.WebAuth.php');
$auth = new WebAuth($db);

$website = get_object('Website', $website_key);


list($unsubscribe_subject_type, $unsubscribe_subject_key) = $auth->get_customer_from_unsubscribe_link(($_REQUEST['s'] ?? ''), ($_REQUEST['a'] ?? ''));

if ($unsubscribe_subject_type == 'Customer') {

} elseif ($unsubscribe_subject_type == 'Prospect') {
    if ($unsubscribe_subject_key != '') {
        $sql  = "select `Prospect Key` from `Prospect Dimension` where `Prospect Key`=?";
        $stmt = $db->prepare($sql);
        $stmt->execute(
            array(
                $unsubscribe_subject_key
            )
        );
        if ($row = $stmt->fetch()) {
            include_once __DIR__.'/utils/new_fork.php';
            $email_tracking = get_object('Email_Tracking', ($_REQUEST['s'] ?? ''));
            $email_tracking->fast_update(
                array(
                    'Email Tracking Unsubscribed' => 'Yes'
                )
            );

            new_housekeeping_fork(
                'au_housekeeping',
                array(
                    'type'                    => 'update_sent_emails_data',
                    'email_template_key'      => $email_tracking->get('Email Tracking Email Template Key'),
                    'email_template_type_key' => $email_tracking->get('Email Tracking Email Template Type Key'),
                    'email_mailshot_key'      => $email_tracking->get('Email Tracking Email Mailshot Key'),

                ),
                DNS_ACCOUNT_CODE
            );


            new_housekeeping_fork(
                'au_housekeeping',
                array(
                    'type'         => 'unsubscribe_prospect',
                    'prospect_key' => $row['Prospect Key'],
                    'date'         => gmdate('Y-m-d H:i:s')

                ),
                DNS_ACCOUNT_CODE
            );
        }
    }
}

$response = array(
    'unsubscribe_subject_type' => $unsubscribe_subject_type,
    'unsubscribe_subject_key'  => $unsubscribe_subject_key,
    'state'                    => 'Unsubscribed',
    'msg'                      => 'test',
    'debug1'                   => DNS_ACCOUNT_CODE
);
echo json_encode($response);
exit;