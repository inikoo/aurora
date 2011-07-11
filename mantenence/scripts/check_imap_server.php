<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.Store.php');
include_once('../../class.Customer.php');

error_reporting(E_ALL);


date_default_timezone_set('UTC');

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
    print "Error can not connect with database server\n";
    exit;
}
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
    print "Error can not access the database\n";
    exit;
}


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';

$hostname = '{imap.gmail.com:993/imap/ssl/novalidate-cert}';
$username = '';
$password = '';


$mbox = imap_open($hostname,$username,$password) or die('Cannot connect to email server: ' . imap_last_error());

$MC = imap_check($mbox);

// Fetch an overview for all messages in INBOX
$result = imap_fetch_overview($mbox,"1:{$MC->Nmsgs}",0);
foreach ($result as $overview) {
$from=false;
    if (property_exists($overview, 'from')) {
        if (preg_match('/\<.+\@.+\>/',$overview->from,$match)) {
            $from=preg_replace('/\<|\>/','',$match[0]);
//print "$from\n";
        }
        elseif(preg_match('/^\s*[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})\s*$/i',$overview->from,$match)) {
            $from=$match[0];
        }
        else {
            print "error can not read ".$overview->from."\n";
        }
    }
//    echo "#{$overview->msgno} ({$overview->date}) - From: {$overview->from}
//    {$overview->subject}\n";

if($from){

$sql=sprintf("select `Subject Key` from `Email Bridge` B left join `Email Dimension` E on (E.`Email Key`=B.`Email Key`)  where `Email`=%s and `Subject Type`='Customer'",
prepare_mysql($from)
);
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){
$customer=new Customer($row['Subject Key']);
print "Customer {$customer->id} \n---------------------------\n";

//$editor=array('Date',$overview->date);
$date=date('Y-m-d H:i:s',strtotime($overview->date));
$details=$overview->msgno;
 $message = imap_fetchbody($mbox,$overview->msgno,2);
 if (property_exists($overview, 'subject')) 
$subject=$overview->subject;
else
$subject='';
$checksum=sha1($message);

$sql=sprintf("select `Customer Key` from `Customer History Bridge` B left join `Customer History Email Checksum` C on (C.`History Key`=B.`History Key`) left join `History Dimension` H on (H.`History Key`=B.`History Key`)  where `Customer Key`=%d and `Checksum`=%s ",
$customer->id,
prepare_mysql($checksum)
);
$res2=mysql_query($sql);
if($row=mysql_fetch_assoc($res2)){

}e
print "$date $subject $message\n";
$customer->add_note($note,$details,$date,'No','Emails');
$history_key=$customer->new_value;
$history_key=1;
if($customer->updated){
$sql=sprintf("insert into `Customer History Email Checksum` values (%d,%s)",$history_key,prepare_mysql($checksum));
mysql_query($sql);
print "$sql\n";
}


}



}

}
imap_close($mbox);

?>