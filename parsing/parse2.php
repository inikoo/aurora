<?php

$urlpage = 'http://en.wikipedia.org/wiki/List_of_countries_by_population';

$pagedata = "";


$filetext = fopen("$urlpage", "r");


while (!feof($filetext))
{

$theline = fgets($filetext, 2048);

$pagedata .= "$theline<p>";
}

fclose($filetext);


$pagedata = ereg_replace("<head>(.*)head>", "", $pagedata);

$pagedata = ereg_replace("<script>(.*)script>", "", $pagedata);


$pagedata = strip_tags($pagedata, '<p>');

echo "$pagedata";

?>













<?php
/*
$urlpage = 'http://en.wikipedia.org/wiki/List_of_countries_by_population';
$filetext = fopen("$urlpage", "r");
while (!feof($filetext))
{
$theline = fgetss($filetext, 161);
echo "$theline<br>";
}
fclose($filetext);
*/
?>
<?php
/*
$urlpage = 'http://en.wikipedia.org/wiki/List_of_countries_by_population';
$pagedata = "";
$filetext = fopen("$urlpage", "r");
while (!feof($filetext))
{
$theline = fgets($filetext, 161);
$pagedata .= "$theline<br>";
}
fclose($filetext);

$pagedata = ereg_replace("<head>(.*)head>", "", $pagedata);
$pagedata = ereg_replace("<script>(.*)script>", "", $pagedata);
$pagedata = strip_tags($pagedata, '<br>');
echo "$pagedata";*/
?>

