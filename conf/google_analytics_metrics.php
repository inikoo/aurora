<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 09-07-2019 14:35:21 MYT Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/




function get_google_analytics_metrics_data(){

    $google_analytics_metrics_data=array(
        array('ga:pageviews','Pageviews'),
        array('ga:users','Users'),
        array('ga:sessions','Sessions'),
        array('ga:pageValue','Webpage Value'),

    );

    return $google_analytics_metrics_data;
}

