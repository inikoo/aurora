<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 December 2018 at 11:23:20 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/



require_once __DIR__.'/cron_common.php';


$file='keyring/nano.key.php';

$keys=array();

$sql=sprintf('select `API Key Key`,`API Key Code` from `API Key Dimension` where `API Key Scope`="Nano Services" ');
$counter=0;
if ($result=$db->query($sql)) {
		foreach ($result as $row) {
            $counter++;
            $api_key=get_object('api_key',$row['API Key Key']);
            $api_key->refresh_key($cost=4);

            $keys[$row['API Key Code']]=$api_key->secret_key;
		}
}

if($counter==0 ){

    include_once 'class.API_Key.php';

    $data=array(
        'API Key Scope'=>'Nano Services'
    );


    $api_key = new API_Key('create', $data,4);



    $keys[$api_key->get('API Key Code')]=$api_key->secret_key;




}



$keys=json_encode($keys);

file_put_contents($file, "<?php\n\$nano_keys='$keys';\n?>");


?>
