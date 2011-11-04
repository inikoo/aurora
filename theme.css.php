<?php 
    include_once('common.php');
 header("Content-type: text/css"); 
$theme_key=$user->data['User Theme Key'];
$theme_background_key=$user->data['User Theme Key'];

$sql=sprintf("select `Theme CSS Buttons`,`Theme CSS Header`,`Theme CSS Tables` from `Theme Dimension` where `Theme Key`=%d",$theme_key);
$res=mysql_query($sql);

if($row=mysql_fetch_assoc($res)){
    print $row['Theme CSS Buttons']; print $row['Theme CSS Header']; print $row['Theme CSS Tables'];
}

$sql=sprintf("select `Header CSS`,`Background CSS`,`Footer CSS` from `Theme Background Dimension` where `Theme Key`=%d",$theme_key);
$res=mysql_query($sql);

if($row=mysql_fetch_assoc($res)){
    print $row['Theme CSS Buttons']; print $row['Theme CSS Header']; print $row['Theme CSS Tables'];
}

   
?>
