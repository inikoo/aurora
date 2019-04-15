<?php
/**
 * Created by PhpStorm.
 * User: sasi
 * Date: 14/04/2019
 * Time: 11:43
 */


include_once __DIR__ . '/api.include.php';

$connect = mysql_connect($apiInclude->dbServername, $apiInclude->dbUsername, $apiInclude->dbPassword) or die('Database Not Connected. Please Fix the Issue! ' . mysql_error());
mysql_select_db($apiInclude->dbName, $connect);


$query = "SELECT `Google API Call Details` FROM `Google API Call Dimension`"; $res = mysql_query($query,$connect) or die("Query Not Executed " . mysql_error($connect));

$data_array = array(); while($rows =mysql_fetch_assoc($res)) { $data_array[] = $rows; }

$connect = mysql_connect("localhost","root","") or die('Database Not Connected. Please Fix the Issue! ' . mysql_error());
mysql_select_db("jsondb", $connect);
//Step No. 2: Extracting data from database $query = "SELECT * FROM stdtable";
$res = mysql_query($query,$connect) or die("Query Not Executed " . mysql_error($connect));
//Step No. 3: Putting the fetched data in Arrays $data_array = array();
while($rows =mysql_fetch_assoc($res)) { $data_array[] = $rows;
} //Step No. 4 and 5: Encoding Array into JSON + Writing data to JSON file $fp = fopen('studRecords.json', 'w');
//fwrite($fp, json_encode($data_array));
if(!fwrite($fp, json_encode($data_array))) { die('Error : File Not Opened. ' . mysql_error());
} else{ echo "Data Retrieved Successully!!!";
} fclose($fp);
mysql_close($connect);
