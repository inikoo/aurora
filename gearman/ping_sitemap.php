<?php
//@author Raul Perusquia <raul@inikoo.com>
//Copyright (c) 2013 Inikoo

function fork_ping_sitemap($job) {

	$site_key=$job->workload();
	if (!$site_key or !is_numeric($site_key))
		return;
	require_once 'class.Site.php';
	$site=new Site($site_key);
	sleep(10);
	$site->ping_sitemap();
	return false;

}

?>