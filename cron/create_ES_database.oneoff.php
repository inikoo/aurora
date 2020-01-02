<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  01 December 2019  17:34::02  +0100, Mijas Costa, Spain

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

require 'common.php';

use Elasticsearch\ClientBuilder;

require 'vendor/autoload.php';

$client = ClientBuilder::create()->setHosts(get_ES_hosts())->build();

//curl -X DELETE 'http://localhost:9200/_all';


$params = [
    'index' => strtolower('au_q_'.$account->get('Code')),
    'body'  => array(
        'settings'=>array(
            'analysis'=>array(
                'analyzer'=>'icu_analyzer'
            )
        ),
        'mappings' => array(

            'properties' => array(
                'rt' => array(
                    'type'=> 'search_as_you_type'
                ),

                'url'          => array(
                    'type'  => 'keyword',
                    'index' => false
                ),
                /*
                'object'       => array(
                    'type'  => 'keyword',
                    'index' => false
                ),

                   'status'       => array(
                    'type'  => 'keyword',
                    'index' => false

                ),

                'result_label' => array(
                    'type'  => 'text',
                    'index' => false
                ),
                  'primary'      => array(
                    'type' => 'text'
                ),
                'secondary'    => array(
                    'type' => 'text'
                ),
                'alias'        => array(
                    'type' => 'text'
                ),
                */
                'module'       => array(
                    'type'  => 'keyword',

                ),


                'icon_classes' => array(
                    'type'  => 'text',
                    'index' => false
                ),
                'label_1' => array(
                    'type'  => 'text',
                    'index' => false
                ),
                'label_2' => array(
                    'type'  => 'text',
                    'index' => false
                ),
                'label_3' => array(
                    'type'  => 'text',
                    'index' => false
                ),
                'label_4' => array(
                    'type'  => 'text',
                    'index' => false
                ),

                'store_label' => array(
                    'type'  => 'text',
                    'index' => false
                ),
                'store_key'    => array(
                    'type' => 'short',

                ),
                'weight'       => array(
                    'type'  => 'rank_feature',
                ),
                'scopes'       => array(
                    'type'  => 'rank_features',
                ),
            )
        )

    ),

];

$response = $client->indices()->create($params);



$params = [
    'index' => strtolower('au_aq_'.$account->get('Code')),
    'body'  => array(
        'settings'=>array(
            'analysis'=>array(
                'analyzer'=>'icu_analyzer'
            )
        ),
        'mappings' => array(

            'properties' => array(
                'date' => array(
                    'type'=> 'date'
                ),

                'query' => array(
                    'type'=> 'text'
                ),

                'module'          => array(
                    'type'  => 'keyword',

                ),

                'section'       => array(
                    'type'  => 'keyword',
                ),
                'user'       => array(
                    'type'  => 'keyword',
                ),


                'store' => array(
                    'type'  => 'keyword',

                ),


            )
        )

    ),

];

$response = $client->indices()->create($params);


