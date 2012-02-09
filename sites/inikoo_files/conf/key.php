<?php
/* Replace Set  the 3 random strings
 * and save as key.php 
 * 
 */






$random1='dasdasdasdasgdfg';
$random2='y54g34f5td2456d254';
$random3='452rfwtywrywytetywty';
$random4='lfdkfdjeoe84duskdkd didkd04em4 jdds ;lsd  ;sd slkjdlfid fj*jff93iKiofvifsfiwpkdu';



define("IKEY",md5($random1));
define("SKEY",md5($random2).md5($random3));
define("CKEY",sha1($random4));

unset($random1);
unset($random2);
unset($random3);
unset($random4);

?>