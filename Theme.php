<?php 
$user_key=$_SESSION['user_key'];
$themeSql="select * from  `User Dimension` inner join `Theme Dimension`  on (`User Dimension`.`User Themes`=`Theme Dimension`.`Theme Key`) where `User Key`=$user_key";

$themeResult=mysql_query($themeSql);
$background_status='';
$themeRow='';
$Theme_css='';
$bg='';
//print_r(mysql_fetch_array($themeResult));

if ($themeRow=mysql_fetch_array($themeResult)) 
{
$Theme_css=$themeRow['Theme Css'];
$background_status=$themeRow['User Theme Background Status'];
}
$bg=$user_key.".png";
if($themeRow)
{
if($background_status)
{
array_push($css_files, 'table.css'); 
array_push($css_files, 'css/index.css');
array_push($css_files, 'css/dropdown.css');
array_push($css_files, 'common.css'); 
array_push($css_files, 'css/'.$Theme_css.'?c='.$bg);   
}
else
{
//array_push($css_files, 'themes_css/'.$ThemeCommon);  
array_push($css_files, 'common.css');
array_push($css_files, 'table.css'); 
//array_push($css_files, 'themes_css/'.$ThemeTable);
array_push($css_files, 'css/index.css');
array_push($css_files, 'css/dropdown.css'); 
//array_push($css_files, 'themes_css/'.$ThemeIndex); 
//array_push($css_files, 'themes_css/'.$ThemeDropdown);
//array_push($css_files, 'css/black_theme.css.php');
array_push($css_files, 'css/'.$Theme_css);

}
}    
   

else{
array_push($css_files, 'common.css'); 
array_push($css_files, 'css/dropdown.css'); 
array_push($css_files, 'css/index.css');
array_push($css_files, 'table.css');
array_push($css_files, 'marketing_campaigns.css');
}
?>
