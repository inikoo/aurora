<?
require_once 'dns.php';         // DB connecton configuration file
require_once 'external_libs/PEAR/MDB2.php';            // PEAR Database Abstraction Layer
$db =& MDB2::singleton($dsn);
if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}
$default_DB_link=mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$default_DB_link){print "Error can not connect with database server\n";exit;}
$db_selected=mysql_select_db($dns_db, $default_DB_link);
if (!$db_selected){print "Error can not access the database\n";exit;}
?>
