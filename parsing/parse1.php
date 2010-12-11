<?php
// Get a file into an array.  In this example we'll go through HTTP to get
// the HTML source of a URL.
$lines = file('http://en.wikipedia.org/wiki/List_of_countries_by_population');
// Loop through our array, show HTML source as HTML source; and line numbers too.
$str='';
foreach ($lines as $line_num => $line) {
   if($line_num>=151 && $line_num<=1953)
           $str.=$line;
}
echo($str);
?>
