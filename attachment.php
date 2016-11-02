<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 December 2015 at 12:25:56 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


require_once 'common.php';
require_once 'utils/authorize_file_view.php';

if (!isset($_REQUEST['id'])) {
	$attachment_key=-1;
}else{
	$attachment_key=$_REQUEST['id'];
}


$sql=sprintf("select `Attachment Public`,`Subject`,`Subject Key`,`Attachment MIME Type`,`Attachment File Original Name`,`Attachment Data` from `Attachment Bridge` B left join  `Attachment Dimension` A on (A.`Attachment Key`= B.`Attachment Key`) where `Attachment Bridge Key`=%d", $attachment_key);



if ($result=$db->query($sql)) {

	if ($row = $result->fetch()) {


		if (authorize_file_view($db,$user, $row['Attachment Public'], $row['Subject'], $row['Subject Key'])) {

			header('Content-Type: '.$row['Attachment MIME Type']);

			header('Content-Disposition: inline; filename='.$row['Attachment File Original Name']);
			echo $row['Attachment Data'];
		}else {

			header('HTTP/1.0 403 Forbidden');
			echo _('Forbidden');
			exit;
		}

	}else {
		header("HTTP/1.0 404 Not Found");
		echo "Attachment not found";

		exit;
	}


}else {
	print_r($error_info=$db->errorInfo());
	exit;

}



?>
