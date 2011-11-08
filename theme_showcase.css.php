<?php 
    include_once('common.php');

 header("Content-type: text/css"); 

$sql=sprintf("select * from `Theme Dimension` ");
$res=mysql_query($sql);

while($row=mysql_fetch_assoc($res)){
$header=$row['Theme CSS Header'];
    $header=preg_replace('/\#hd\{/','div.theme_'.$row['Theme Key'].'{',$header);
    $header=preg_replace('/header/','theme_'.$row['Theme Key'],$header);
    print $header;
}

$sql=sprintf("select * from `Theme Background Dimension` ");
$res=mysql_query($sql);

while($row=mysql_fetch_assoc($res)){
$header=$row['Background CSS'];
    $header=preg_replace('/html\{/','div.theme_background_'.$row['Theme Background Key'].'{',$header);
    print $header;
}

   
?>
