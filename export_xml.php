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
        $row['Product Name'] = str_replace("&", "&amp;", $row['Product Name']); 
        
    $xml_output .= "\t\t<name>" . $row['Product Name'] . "</name>"; 
    $xml_output .= "\t</product>\n"; 
} 

$xml_output .= "</products>"; 

echo $xml_output; 

 ?>
