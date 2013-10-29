<?php
include_once('common.php');
header("Content-type: text/css");

$theme_key=$user->data['User Theme Key'];
$theme_background_key=$user->data['User Theme Background Key'];

$sql=sprintf("select `Theme CSS Buttons`,`Theme CSS Header`,`Theme CSS Tables` ,`Theme CSS Top Navigation`from `Theme Dimension` where `Theme Key`=%d",$theme_key);
$res=mysql_query($sql);

if ($row=mysql_fetch_assoc($res)) {
    print $row['Theme CSS Buttons'];
    print $row['Theme CSS Header'];
    print $row['Theme CSS Tables'];;
    print $row['Theme CSS Top Navigation'];
}

$sql=sprintf("select `Header CSS`,`Background CSS`,`Footer CSS` from `Theme Background Dimension` where `Theme Background Key`=%d",$theme_background_key);
$res=mysql_query($sql);
if ($row=mysql_fetch_assoc($res)) {
    print $row['Header CSS'];
    print $row['Background CSS'];
    print $row['Footer CSS'];
}


?>
