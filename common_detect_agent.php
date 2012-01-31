<?php

function ip() {
    global $REMOTE_ADDR;
    global $HTTP_X_FORWARDED_FOR, $HTTP_X_FORWARDED, $HTTP_FORWARDED_FOR, $HTTP_FORWARDED;
    global $HTTP_VIA, $HTTP_X_COMING_FROM, $HTTP_COMING_FROM;
    // Get some server/environment variables values
    
    $direct_ip='';
    if (empty($REMOTE_ADDR)) {
        if (!empty($_SERVER) && isset($_SERVER['REMOTE_ADDR'])) {
            $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
        } else if (!empty($_ENV) && isset($_ENV['REMOTE_ADDR'])) {
            $REMOTE_ADDR = $_ENV['REMOTE_ADDR'];
        } else if (@getenv('REMOTE_ADDR')) {
            $REMOTE_ADDR = getenv('REMOTE_ADDR');
        }
    } // end if
    if (empty($HTTP_X_FORWARDED_FOR)) {
        if (!empty($_SERVER) && isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $HTTP_X_FORWARDED_FOR = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (!empty($_ENV) && isset($_ENV['HTTP_X_FORWARDED_FOR'])) {
            $HTTP_X_FORWARDED_FOR = $_ENV['HTTP_X_FORWARDED_FOR'];
        } else if (@getenv('HTTP_X_FORWARDED_FOR')) {
            $HTTP_X_FORWARDED_FOR = getenv('HTTP_X_FORWARDED_FOR');
        }
    } // end if
    if (empty($HTTP_X_FORWARDED)) {
        if (!empty($_SERVER) && isset($_SERVER['HTTP_X_FORWARDED'])) {
            $HTTP_X_FORWARDED = $_SERVER['HTTP_X_FORWARDED'];
        } else if (!empty($_ENV) && isset($_ENV['HTTP_X_FORWARDED'])) {
            $HTTP_X_FORWARDED = $_ENV['HTTP_X_FORWARDED'];
        } else if (@getenv('HTTP_X_FORWARDED')) {
            $HTTP_X_FORWARDED = getenv('HTTP_X_FORWARDED');
        }
    } // end if
    if (empty($HTTP_FORWARDED_FOR)) {
        if (!empty($_SERVER) && isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $HTTP_FORWARDED_FOR = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if (!empty($_ENV) && isset($_ENV['HTTP_FORWARDED_FOR'])) {
            $HTTP_FORWARDED_FOR = $_ENV['HTTP_FORWARDED_FOR'];
        } else if (@getenv('HTTP_FORWARDED_FOR')) {
            $HTTP_FORWARDED_FOR = getenv('HTTP_FORWARDED_FOR');
        }
    } // end if
    if (empty($HTTP_FORWARDED)) {
        if (!empty($_SERVER) && isset($_SERVER['HTTP_FORWARDED'])) {
            $HTTP_FORWARDED = $_SERVER['HTTP_FORWARDED'];
        } else if (!empty($_ENV) && isset($_ENV['HTTP_FORWARDED'])) {
            $HTTP_FORWARDED = $_ENV['HTTP_FORWARDED'];
        } else if (@getenv('HTTP_FORWARDED')) {
            $HTTP_FORWARDED = getenv('HTTP_FORWARDED');
        }
    } // end if
    if (empty($HTTP_VIA)) {
        if (!empty($_SERVER) && isset($_SERVER['HTTP_VIA'])) {
            $HTTP_VIA = $_SERVER['HTTP_VIA'];
        } else if (!empty($_ENV) && isset($_ENV['HTTP_VIA'])) {
            $HTTP_VIA = $_ENV['HTTP_VIA'];
        } else if (@getenv('HTTP_VIA')) {
            $HTTP_VIA = getenv('HTTP_VIA');
        }
    } // end if
    if (empty($HTTP_X_COMING_FROM)) {
        if (!empty($_SERVER) && isset($_SERVER['HTTP_X_COMING_FROM'])) {
            $HTTP_X_COMING_FROM = $_SERVER['HTTP_X_COMING_FROM'];
        } else if (!empty($_ENV) && isset($_ENV['HTTP_X_COMING_FROM'])) {
            $HTTP_X_COMING_FROM = $_ENV['HTTP_X_COMING_FROM'];
        } else if (@getenv('HTTP_X_COMING_FROM')) {
            $HTTP_X_COMING_FROM = getenv('HTTP_X_COMING_FROM');
        }
    } // end if
    if (empty($HTTP_COMING_FROM)) {
        if (!empty($_SERVER) && isset($_SERVER['HTTP_COMING_FROM'])) {
            $HTTP_COMING_FROM = $_SERVER['HTTP_COMING_FROM'];
        } else if (!empty($_ENV) && isset($_ENV['HTTP_COMING_FROM'])) {
            $HTTP_COMING_FROM = $_ENV['HTTP_COMING_FROM'];
        } else if (@getenv('HTTP_COMING_FROM')) {
            $HTTP_COMING_FROM = getenv('HTTP_COMING_FROM');
        }
    } // end if

    // Gets the default ip sent by the user
    if (!empty($REMOTE_ADDR)) {
        $direct_ip = $REMOTE_ADDR;
    }

    // Gets the proxy ip sent by the user
    $proxy_ip	 = '';
    if (!empty($HTTP_X_FORWARDED_FOR)) {
        $proxy_ip = $HTTP_X_FORWARDED_FOR;
    } else if (!empty($HTTP_X_FORWARDED)) {
        $proxy_ip = $HTTP_X_FORWARDED;
    } else if (!empty($HTTP_FORWARDED_FOR)) {
        $proxy_ip = $HTTP_FORWARDED_FOR;
    } else if (!empty($HTTP_FORWARDED)) {
        $proxy_ip = $HTTP_FORWARDED;
    } else if (!empty($HTTP_VIA)) {
        $proxy_ip = $HTTP_VIA;
    } else if (!empty($HTTP_X_COMING_FROM)) {
        $proxy_ip = $HTTP_X_COMING_FROM;
    } else if (!empty($HTTP_COMING_FROM)) {
        $proxy_ip = $HTTP_COMING_FROM;
    } // end if... else if...

    // Returns the true IP if it has been found, else FALSE
    if (empty($proxy_ip)) {
        // True IP without proxy

        return $direct_ip;
    } else {
        $is_ip = preg_match('|^([0-9]{1,3}\.){3,3}[0-9]{1,3}|', $proxy_ip, $regs);
        if ($is_ip && (count($regs) > 0)) {
            // True IP behind a proxy
            return $regs[0];
        } else {
            // Can't define IP: there is a proxy but we don't have
            // information about the true IP
            return FALSE;
        }
    } // end if... else...
}



function get_user_browser($useragent) {

if (strpos($useragent,"MSIE") !== false && strpos($useragent,"Opera") === false && strpos($useragent,"Netscape") === false) {
    //deal with IE
    $found = preg_match("/MSIE ([0-9]{1}\.[0-9]{1,2})/",$useragent, $mathes);
    if ($found) {
        return "Internet Explorer " . $mathes[1];
    }
}
elseif(strpos($useragent,"Gecko")) {
    //deal with Gecko based

    //if firefox
    $found = preg_match("/Firefox\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/",$useragent,$mathes);
    if ($found) {
        return "Mozilla Firefox " . $mathes[1];
    }

    //if Netscape (based on gecko)
    $found = preg_match("/Netscape\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/",$useragent,$mathes);
    if ($found) {
        return "Netscape " . $mathes[1];
    }

    //check chrome before safari because chrome agent contains both
    $found = preg_match("/Chrome\/([^\s]+)/",$useragent, $mathes);
    if ($found) {
        return "Google Chrome " . $mathes[1];
    }

    //if Safari (based on gecko)
    $found = preg_match("/Safari\/([0-9]{2,3}(\.[0-9])?)/",$useragent, $mathes);
    if ($found) {
        return "Safari " . $mathes[1];
    }

    //if Galeon (based on gecko)
    $found = preg_match("/Galeon\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/",$useragent,$mathes);
    if ($found) {
        return "Galeon " . $mathes[1];
    }

    //if Konqueror (based on gecko)
    $found = preg_match("/Konqueror\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/",$useragent,$mathes);
    if ($found) {
        return "Konqueror " . $mathes[1];
    }

    //no specific Gecko found
    //return generic Gecko
    return "Gecko based";
}

elseif(strpos($useragent,"Opera") !== false) {
    //deal with Opera
    $found = preg_match("/Opera[\/ ]([0-9]{1}\.[0-9]{1}([0-9])?)/",$useragent,$mathes);
    if ($found) {
        return "Opera " . $mathes[1];
    }
}
elseif (strpos($useragent,"Lynx") !== false) {
    //deal with Lynx
    $found = preg_match("/Lynx\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/",$useragent,$mathes);
    if ($found) {
        return "Lynx " . $mathes[1];
    }

}
elseif (strpos($useragent,"Netscape") !== false) {
    //NN8 with IE string
    $found = preg_match("/Netscape\/([0-9]{1}\.[0-9]{1}(\.[0-9])?)/",$useragent,$mathes);
    if ($found) {
        return "Netscape " . $mathes[1];
    }
}
else {
    //unrecognized, this should be less than 1% of browsers (not counting bots like google etc)!
    return false;
}
}
function get_user_os($useragent){        
        $useragent = strtolower($useragent);
        
        //check for (aaargh) most popular first        
        //winxp
        if(strpos("$useragent","windows nt 5.1") !== false)
        {
            return "Windows XP";            
        }
        elseif (strpos("$useragent","windows nt 6.0") !== false)
        {
            return "Windows Vista";
        }
        elseif (strpos("$useragent","windows 98") !== false)
        {
            return "Windows 98";
        }
        elseif (strpos("$useragent","windows nt 5.0") !== false)
        {
            return "Windows 2000";
        }
        elseif (strpos("$useragent","windows nt 5.2") !== false)
        {
            return "Windows 2003 server";
        }
        elseif (strpos("$useragent","windows nt 6.0") !== false)
        {
            return "Windows Vista";
        }
        elseif (strpos("$useragent","windows nt") !== false)
        {
            return "Windows NT";
        }
        elseif (strpos("$useragent","win 9x 4.90") !== false && strpos("$useragent","win me"))
        {
            return "Windows ME";
        }
        elseif (strpos("$useragent","win ce") !== false)
        {
            return "Windows CE";
        }
        elseif (strpos("$useragent","win 9x 4.90") !== false)
        {
            return "Windows ME";
        }
        elseif (strpos("$useragent","iphone") !== false)
        {
            return "iPhone";
        }
        elseif (strpos("$useragent","mac os x") !== false)
        {
            return "Mac OS X";
        }
        elseif (strpos("$useragent","macintosh") !== false)
        {
            return "Macintosh";
        }
        elseif (strpos("$useragent","linux") !== false)
        {
            return "Linux";
        }
        elseif (strpos("$useragent","freebsd") !== false)
        {
            return "Free BSD";
        }
        elseif (strpos("$useragent","symbian") !== false)
        {
            return "Symbian";
        }
        else 
        {
            return false;
        }
    }



?>