<?php include("phpHTMLParser.php");
$content = file_get_contents("http://en.wikipedia.org/wiki/List_of_countries_by_population");
$parser = new phpHTMLParser("$content");
$HTMLObject = $parser->parse_tags(array("td", ""));
$aTags = $HTMLObject->getTagsByName("td");
foreach ($aTags as $a) {
   
      //echo $a->href . "<br/>";
     echo $a->innerHTML . "<br/><br/>";
  
}
?>
