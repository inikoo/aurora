<?php
/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created:  19 November 2019  12:39::13  +0100, Malaga, Spain

 Copyright (c) 2017, Inikoo

 Version 2.0
*/

function set_locate($website_locale){



    $locale = $website_locale.'.UTF-8';

    putenv('LC_MESSAGES='.$locale);

    if (defined('LC_MESSAGES')) {
        setlocale(LC_MESSAGES, $locale);
    } else {
        setlocale(LC_ALL, $locale);
    }
    bindtextdomain("inikoo", "./locale");
    textdomain("inikoo");
    bind_textdomain_codeset("inikoo", 'UTF-8');

    setlocale(LC_MONETARY, $locale);
    $current_locale =setlocale(LC_TIME, $locale);

    return $current_locale;

}
