<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  01 December 2019  17:34::02  +0100, Mijas Costa, Spain

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

require '../keyring/dns.php';

use Elasticsearch\ClientBuilder;

require '../vendor/autoload.php';

$client = ClientBuilder::create()->setHosts(get_ES_hosts())->build();

//curl -X DELETE 'http://localhost:9200/au_q_analytics_es';
//curl -X DELETE 'http://localhost:9200/au_q_analytics_aw';
//curl -X DELETE 'http://localhost:9200/au_q_analytics_aweu';




$params = [
    'index' => strtolower('au_q_analytics_'.DNS_ACCOUNT_CODE),
    'body'  => array(
        'settings'=>array(
            'analysis'=>array(
                'analyzer'=>'icu_analyzer'
            )
        ),
        'mappings' => array(

            'properties' => array(
                'date' => array(
                    'type'=> 'date',
                    "format"=> "yyyy-MM-dd'T'HH:mm:ss.SSSSSS"

                ),

                'search_index' => array(
                    'type'  => 'keyword',

                ),

                'account' => array(
                    'type'  => 'keyword',

                ),

                'stores' => array(
                    'type'  => 'keyword',

                ),
                'query' => array(
                    'type'=> 'text'
                ),

                'modules'          => array(
                    'type'  => 'keyword',

                ),

                'section'       => array(
                    'type'  => 'keyword',
                ),
                'user'       => array(
                    'type'  => 'keyword',
                ),


                'action' => array(
                    'type'  => 'keyword',

                ),

                'click_url' => array(
                    'type'  => 'keyword',

                ),
                'click_pos' => array(
                    'type'  => 'byte',

                ),
                'delta_time' => array(
                    'type'  => 'half_float',

                ),


            )
        )

    ),

];

$response = $client->indices()->create($params);


