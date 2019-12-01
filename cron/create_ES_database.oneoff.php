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

$client = ClientBuilder::create()->build();

//curl -X DELETE 'http://localhost:9200/_all


$params = [
    'index' => strtolower('au_'.$account->get('Code')),
    'body'  => array(

        'mappings' => array(

                'properties' => array(
                    'url'       => array(
                        'type' => 'keyword',
                        'index' => false
                    ),
                    'object'       => array(
                        'type' => 'keyword',
                        'index' => false
                    ),
                    'result_label'       => array(
                        'type' => 'text',
                        'index' => false
                    ),
                    'primary' => array(
                        'type' => 'text'
                    ),
                    'secondary' => array(
                        'type' => 'text'
                    ),
                    'alias' => array(
                        'type' => 'text'
                    ),
                    'store_key'       => array(
                        'type' => 'short',

                    ),
                )
            )
        
    ),

];

$response = $client->indices()->create($params);
print_r($response);
