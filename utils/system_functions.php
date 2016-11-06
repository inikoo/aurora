<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 6 March 2016 at 22:13:28 GMT+8, Yiwu, China
 Copyright (c) 2015, Inikoo

 Version 3

*/

function setTimezone($default) {

    if ($default == '') {
        $default = 'Europe/London';
    }

    $timezone = "";

    // On many systems (Mac, for instance) "/etc/localtime" is a symlink
    // to the file with the timezone info
    if (is_link("/etc/localtime")) {

        // If it is, that file's name is actually the "Olsen" format timezone
        $filename = readlink("/etc/localtime");

        $pos = strpos($filename, "zoneinfo");
        if ($pos) {
            // When it is, it's in the "/usr/share/zoneinfo/" folder
            $timezone = substr($filename, $pos + strlen("zoneinfo/"));
        } else {
            // If not, bail
            $timezone = $default;
        }
    } else {
        // On other systems, like Ubuntu, there's file with the Olsen time
        // right inside it.
        $timezone = file_get_contents("/etc/timezone");
        if (!strlen($timezone)) {
            $timezone = $default;
        }
    }

    date_default_timezone_set($timezone);
}


?>