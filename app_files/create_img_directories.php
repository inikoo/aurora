<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28-04-2019 15:37:11 MYT   Kuala Lumpur, Maysia

 Copyright (c) 2016, Inikoo

 Version 3.0

*/



chdir("../img/");

if (!file_exists('db')) {
    mkdir('db', 0770, true);
}


if (!file_exists('cache')) {
    mkdir('cache', 0770, true);
}



for($i = 0; $i < 16; $i++) {
    $tier_1=dechex($i);

    mkdir('cache/'.$tier_1, 0770, true);

    for($j = 0; $j < 16; $j++) {
        $tier_2=dechex($j);

        mkdir('cache/'.$tier_1.'/'.$tier_2, 0770, true);


    }

}


for($i = 0; $i < 16; $i++) {
    $tier_1=dechex($i);

    mkdir('db/'.$tier_1, 0770, true);

    for($j = 0; $j < 16; $j++) {
        $tier_2=dechex($j);

        mkdir('db/'.$tier_1.'/'.$tier_2, 0770, true);


    }

}