<?
require_once 'common.php';
if (!$LU or !$LU->isLoggedIn()) {
  $response=array('state'=>402,'resp'=>_('Forbidden'));
  echo json_encode($response);
  exit;
 }

require_once "Mail.php";
require_once('Mail/mime.php');




?>