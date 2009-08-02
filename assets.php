<?php
if(isset($_REQUEST['view']) and $_REQUEST['view']=='tree')
  header("Location: assets_tree.php");
 else
   header("Location: assets_index.php");


?>