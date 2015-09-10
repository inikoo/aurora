<?php 

include_once('common.php');
include_once('ar_common.php');


header("Content-type: text/xml"); 


$query = "SELECT * FROM `Product Dimension` ORDER BY `Product Code` ASC"; 
$resultID = mysql_query($query) or die("Data not found."); 

$xml_output = "<?xml version=\"1.0\"?>\n"; 
$xml_output .= "<products>\n"; 

for($x = 0 ; $x < mysql_num_rows($resultID) ; $x++){ 
    $row = mysql_fetch_assoc($resultID); 
    $xml_output .= "\t<product>\n"; 
	$row['Product Code'] = str_replace("&", "&amp;", $row['Product Code']);
     $xml_output .= "\t\t<code>" . $row['Product Code'] . "</code>";  
        $row['Product Short Description'] = str_replace("&", "&amp;", $row['Product Short Description']); 
    $xml_output .= "\t\t<name>" . $row['Product Short Description'] . "</name>"; 
    
       $xml_output .= "\t\t<status>" . $row['Product Sales Type'] . "</status>"; 
       $xml_output .= "\t\t<web>" . $row['Product Web Configuration'] . "</web>";
       $xml_output .= "\t\t<sales>" . $row['Product Total Invoiced Gross Amount'] . "</sales>";     
       $xml_output .= "\t\t<profit>" . $row['Product Total Profit'] . "</profit>";
 $xml_output .= "\t</product>\n"; 
} 

$xml_output .= "</products>"; 

echo $xml_output; 


 ?>
