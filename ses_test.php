<?php
require_once('ses.php');

$access_key='AKIAJGTHT6POHWCQQNRQ';
$secret_key='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';
$ses = new SimpleEmailService($access_key, $secret_key);


/*Verify an emaail */
//print_r($ses->verifyEmailAddress('bugs@inikoo.com'));exit;
//print_r($ses->verifyEmailAddress('migara64@gmail.com'));

/*Remove an email from the list */
//$ses->deleteVerifiedEmailAddress('migara@inikoo.com');

/*Print the verified list */
print_r($ses->listVerifiedEmailAddresses());exit;



$m = new SimpleEmailServiceMessage();
$m->addTo('migara64@gmail.com');
$m->setFrom('migara@inikoo.com');
$m->setSubject('Hello, world!');
$m->setMessageFromString('This is the message body.');

/*Set character set*/
/*Default is set to UTF-8 */
//$m->setSubjectCharset('ISO-8859-1');
//$m->setMessageCharset('ISO-8859-1');


/* Add multiple addresses */
//$m->addTo(array('aaaa@example.com', 'bbbb@example.com'));

/*Set message content from a file or url */

//$m->setMessageFromFile('/path/to/some/file.txt');
// or from a URL, if allow_url_fopen is enabled:
//$m->setMessageFromURL('http://example.com/somefile.txt');

/*Set return path */
//$m->setReturnPath('noreply@example.com');


$m->addReplyTo('mig@anemanda.com');

$text="This is text";
$html="<b>This is bold</b>";
//Set both text and html message body
$m->setMessageFromString($text, $html);

print_r($ses->sendEmail($m));

print_r($ses->getSendQuota());
print_r($ses->getSendStatistics());



?>