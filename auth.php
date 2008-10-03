<?
include_once "dns/dns.php";

$link = mysql_connect('localhost',$dns_user, $dns_pwd);

mysql_select_db($dns_db) ;

if(!isset($_REQUEST['h']))
  exit;
$td = mcrypt_module_open('rijndael-128', '', 'cfb', '');
$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_DEV_RANDOM);
$ks = mcrypt_enc_get_key_size($td);


/* Intialize encryption */




$sql="select passwd from liveuser_users  where handle='". mysql_real_escape_string($_REQUEST['h'])."'";

$result = mysql_query($sql) ;

if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
  $pwd=$row['passwd'];
  

   $key = substr(md5('very secret key'), 0, $ks/2);

   $key= hex2ascii('ca978112ca1bbdcafac231b39a23dc4d');
   $iv=  hex2ascii('4b3cc691f540dd535bf5da4bb788c9f3');

  mcrypt_generic_init($td,$key,$iv);
  $st1 = mcrypt_generic($td,  'xx');
 
  mcrypt_generic_init($td,$key,$iv);
  $st2 = mdecrypt_generic($td, hex2ascii('c1f958aa') );
 
  print  $ks.'    '.($st1).'->'.$st2.'<br>';

  print strlen(ascii2hex($iv)).' '. ascii2hex($iv).'<br>'.strlen(ascii2hex($key)).' '. ascii2hex($key);


}
 

function hex2ascii($str)
{
    $p = '';
    for ($i=0; $i < strlen($str); $i=$i+2)
    {
        $p .= chr(hexdec(substr($str, $i, 2)));
    }
    return $p;
}

function ascii2hex($ascii) {
$hex = '';
for ($i = 0; $i < strlen($ascii); $i++) {
$byte = strtoupper(dechex(ord($ascii{$i})));
$byte = str_repeat('0', 2 - strlen($byte)).$byte;
$hex.=$byte;
}
return $hex;
}


?>