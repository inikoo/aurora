<?php
require_once 'common.php';

if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
	$sql=sprintf("select * from `Attachment Dimension` A left join `Attachment Bridge` B on (B.`Attachment Key`=A.`Attachment Key`) where A.`Attachment Key`=%d",$_REQUEST['id']);

	
}elseif (isset($_REQUEST['bid']) and is_numeric($_REQUEST['bid']) ) {
	$sql=sprintf("select * from `Attachment Bridge` A left join `Attachment Dimension` B on (B.`Attachment Key`=A.`Attachment Key`) where `Attachment Bridge Key`=%d",$_REQUEST['bid']);

}else {
not_found('Attachement','');
	exit;
}



$result = mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	header('Content-Type: '.$row['Attachment MIME Type']);
	header('Content-Disposition: inline; filename='.$row['Attachment File Original Name']);
	//readfile($row['Attachment Filename']);


	echo $row['Attachment Data'];


}else {
not_found('Attachement','');
	
}




?>
