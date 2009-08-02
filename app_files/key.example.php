<?php
/* Replace Set  the 3 random strings
 * and save as key.php 
 * 
 */


$random1=
$random2=
$random3=

define("IKEY",md5($random1));
define("SKEY",md5($random2).md5($random3));
unset($random1);
unset($random2);
unset($random3);
?>