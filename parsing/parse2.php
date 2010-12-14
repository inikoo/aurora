<?php
//$urlpage determines the site that is going to be pulled.
$urlpage = 'http://en.wikipedia.org/wiki/List_of_countries_by_population';
//We need to define this so it doesn't give us an error later.
$pagedata = "";

//Opens the file specified in $urlpage for reading only.
$filetext = fopen("$urlpage", "r");

//Starts a loop that stays open until the end of the file.
while (!feof($filetext))
{
//Makes a variable called $theline that pulls the file line by line from the page.
$theline = fgets($filetext, 2048);
//Adds the data in $theline to the variable $pagedata and sticks a <p> after it.
$pagedata .= "$theline<p>";
}
//We are now done with the file so it's time to close it so it's not sitting open.
fclose($filetext);

//Alright now to deal with all that CSS garbage.
//This removes everything between the <head></head> tags even the tags.
$pagedata = ereg_replace("<head>(.*)head>", "", $pagedata);
//Incase there is Javascript this removes everything between the <script> tags.
$pagedata = ereg_replace("<script>(.*)script>", "", $pagedata);

//The '<p>' means that it won't strip '<p>' tags from the data.
$pagedata = strip_tags($pagedata, '<p>');
//Echos our result to the browser so you can see what you did.
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

