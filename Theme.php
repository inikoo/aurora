<?php 
$user_key=$_SESSION['user_key'];
$themeSql="select * from  `User Dimension` inner join `Theme Dimension`  on (`User Dimension`.`User Themes`=`Theme Dimension`.`Theme Key`) where `User Key`=$user_key";

$themeResult=mysql_query($themeSql);
//print_r(mysql_fetch_array($themeResult));
if ($themeRow=mysql_fetch_array($themeResult)) 
{
$ThemeCommon=$themeRow['Theme Common Css'];
$ThemeTable=$themeRow['Theme Table Css'];
$ThemeIndex=$themeRow['Theme Index Css'];
$ThemeDropdown=$themeRow['Theme Dropdown Css'];
$ThemeCampaign=$themeRow['Theme Campaign Css'];

}

?> 
