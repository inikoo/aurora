<?php


$website_key = $_REQUEST['website_key'];


include_once('class.WebAuth.php');
$auth = new WebAuth($db);

$website = get_object('Website', $website_key);


list($unsubscribe_subject_type, $unsubscribe_subject_key) = $auth->get_customer_from_unsubscribe_link(($_REQUEST['s'] ?? ''), ($_REQUEST['a'] ?? ''));


$response = array(
    'unsubscribe_subject_type' => $unsubscribe_subject_type,
    'unsubscribe_subject_key'  => $unsubscribe_subject_key,
    'state'                    => 'Unsubscribed',
    'msg'                      => 'test',
);
echo json_encode($response);
exit;