<?php 

include_once('common.php');
include_once('ar_common.php');

header("Content-Type:text/xml");



$output = "<?xml version=\"1.0\" ?>\n"; 
$output .= "<schema>"; 

// iterate over each table and return the fields for each table

$result_fld=mysql_query("select * from `Product Dimension` where true");
   while( $row1 = mysql_fetch_row($result_fld) ) {
      $output .= "<field name=\"$row1[0]\" type=\"$row1[1]\"";
      //$output .= ($row1[3] == "PRI") ? " primary_key=\"yes\" />" : " />";
   } 

  


$output .= "</schema>"; 

// tell the browser what kind of file is come in
header("Content-type: text/xml"); 
// print out XML that describes the schema
echo $output; 




/*

$_data=array();

foreach($fields as $key=>$options){

$_data[]=$row[$options['db_name']];
}
$data[]=$_data;
}
//print_r($data);exit;

//return $data;


$xml = new SimpleXMLElement('<root/>');
$_csv='';
	foreach($data as $key=>$value){
//$_csv.="\t".$value;

array_walk_recursive($value, array ($xml, 'addChild'));

}
print $xml->asXML();/*

/*
$_xml='';
	foreach($data as $key=>$value){
$_xml.="\t".$value;
}

$xml.=preg_replace('/^\t/','',$_xml)."\n";
		  // fputcsv($out, $data);
  	  
*/
//mysql_free_result($res);






// ------------------------------------
   ?>
