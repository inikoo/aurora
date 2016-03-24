<?php
/* Set 5 random strings
 * and save as key.php
 *
 */


$random1='write a random number';
$random2='write other random number';
$random3='write other random number';
$random4='write other random number';
$random5='write other random number';



define("IKEY", md5($random1));
define("SKEY", md5($random2).md5($random3));
define("CKEY", sha1($random4));
define("VKEY", sha1($random5));

unset($random1);
unset($random2);
unset($random3);
unset($random4);
unset($random5);
?>
