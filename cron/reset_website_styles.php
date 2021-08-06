<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 April 2018 at 15:20:11 BST, Sheffield, UK
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
require_once 'conf/website_styles.php';


$sql = sprintf('select `Website Key` from `Website Dimension`   ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $website = get_object('Website', $row['Website Key']);

        $old_styles=$website->style;

        $website_styles=get_default_websites();

        foreach($website_styles as $key=>$style){

            foreach($old_styles as $old_style){
                if($old_style[0]==$style[0] and $old_style[1]==$style[1]){
                    $website_styles[$key][2]=$old_style[2];
                }
            }


        }

        $website->fast_update(
            array(
                'Website Style' => json_encode($website_styles)
            )
        );


    }
}
