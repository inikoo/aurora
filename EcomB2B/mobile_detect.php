<?php
    require_once 'external_libs/mobile_detect/Mobile_Detect.php';


$detect = new Mobile_Detect;
print $detect->getUserAgent()."\n";

if( $detect->isTablet() ){
 print ' tablet';
}

?>
