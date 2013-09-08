<?php
/*

 Autor: Raul Perusquia <raul@inikoo.com>
 Copyright (c) 2013, Inikoo

*/


function new_fork($type,$data,$account_code) {

	$fork_encrypt_key=md5('huls0fjhslsshskslgjbtqcwijnbxhl2391');

	$token=substr(str_shuffle(md5(time()).rand().str_shuffle('qwertyuiopasdfghjjklmnbvcxzQWERTYUIOPKJHGFDSAZXCVBNM1234567890') ),0,64);
	$sql=sprintf("insert into `Fork Dimension`  (`Fork Process Data`,`Fork Token`,`Fork Type`) values (%s,%s,%s)  ",
		prepare_mysql(json_encode($data)),
		prepare_mysql( $token),
		prepare_mysql($type)

	);

	$salt=md5(rand());

	mysql_query($sql);
	$fork_key=mysql_insert_id();

	$fork_metadata=base64_encode(AESEncryptCtr(json_encode(array('code'=>addslashes($account_code),'token'=>$token,'fork_key'=>$fork_key,'salt'=>$salt)),$fork_encrypt_key,256));

	$client= new GearmanClient();
	$client->addServer('127.0.0.1');
	$msg=$client->doBackground("export", $fork_metadata);

	return array($fork_key,$msg);

}

?>
