<?php
include_once('common.php');
session_destroy();
unset($_SESSION);

header('Location: index.php');
$_SESSION['prev_page_key']=$page_data['Page Key'];
exit();

?>
