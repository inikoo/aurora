<?php
$files = glob("../forms2/*/*.php");

foreach($files as $filename) {
    print $filename."  ".filesize($filename)."  \n";
    if (filesize($filename)) {
        $fp = fopen($filename, 'r');
        $contents = fread($fp, filesize($filename));
        fclose($fp);

        if (preg_match('/<\?.*common.splinter.*\?>/msU',$contents,$match)) {
            $contents=removeBOM($contents);

            $script=$match[0];
            $contents=preg_replace('/<\?.*common.splinter.*\?>/msU','',$contents,1);
            $contents=$script.$contents;
            $fp = fopen($filename, 'w');
            rewind($fp);
            fwrite($fp,$contents);
            fclose($fp);

        }
       
    }
}

function removeBOM($str="") {
    if (substr($str, 0,3) == pack("CCC",0xef,0xbb,0xbf)) {
        $str=substr($str, 3);
    }
    return $str;
}

?>