<?php
/* Replace Set  the 3 random strings
 * and save as key.php 
 * 
 */


$random1='dasdasdasdasgdfg';
$random2='y54g34f5td2456d254';
$random3='452rfwtywrywytetywty';

define("IKEY",md5($random1));
define("SKEY",md5($random2).md5($random3));
unset($random1);
unset($random2);
unset($random3);
?>