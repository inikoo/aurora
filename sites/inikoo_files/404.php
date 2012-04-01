<?php
include_once('common.php');
$not_found_current_page=$_REQUEST['original_url'];
$page_key=$site->get_not_found_page_key();
include_once('page.php');
?>