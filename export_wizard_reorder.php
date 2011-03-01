<?php
include_once('common.php');

$source=$_GET['seq1'];
$destination=$_GET['seq2'];
$Array = $_SESSION['list'];

if($source < 0){

	//Do nothing ...
	exit;

}
if($destination > count($Array)){

	//Do nothing ...
	exit;

}

$KeyArray = array();
$ValueArray = array();
$FinalArray = array();
//print_r($Array);

foreach ($Array as $key=>$value){
array_push($KeyArray , $key);
array_push($ValueArray , $value);
}

//print_r($KeyArray);
//print_r($ValueArray);

$temp1 = $ValueArray[$source];
$ValueArray[$source] = $ValueArray[$destination];
$ValueArray[$destination] = $temp1;

$temp2 = $KeyArray[$source];
$KeyArray[$source] = $KeyArray[$destination];
$KeyArray[$destination] = $temp2;

//print_r($KeyArray);
//print_r($ValueArray);

foreach($ValueArray as $key2=>$val2){

	$FinalKey = $KeyArray[$key2];
	$FinalArray[$FinalKey]=$val2;

}

//print_r($FinalArray);

$_SESSION['list'] = $FinalArray;
$op = '';
//$count = 0;
//$num=$count+1;
//$prv = $count - 1;

//foreach($FinalArray as $FinalKey=>$FinalValue){


//$html .= "<input type=\"hidden\" style=\"width:25px;\" name=\"seq$num\" id=\"txt$num\" value=\"$num\" readonly=\"readonly\"><a onClick=myfunc($count,$prv);>Up</a>&nbsp;<a onClick=myfunc($count,$prv);>Down</a></td><td>$FinalKey</td></tr><tr><td colspan=\"2\">";

//$count++;

//}

echo $op;


unset($KeyArray);
unset($ValueArray);
unset($FinalArray);
unset($Array);

?>
