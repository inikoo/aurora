<?php
$not_found_current_page='x'.$_REQUEST['original_url'];

include_once('common.php');
$page_key=$site->get_not_found_page_key();
include_once('page.php');
?>