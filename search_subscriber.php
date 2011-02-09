<?php

include_once('common.php');
$value = trim($_REQUEST['id']);
$user_str = '';

if(trim($value) != ''){

    if(strlen($value) <= 2){

        $user_str = "<i>Too short keyword...</i>";
    }else{

    $sql = "SELECT `People List Key` , `People Email` , `People First Name` , `People Last Name` FROM `Email People Dimension` WHERE `People Email` LIKE '%$value%' OR
    `People First Name` LIKE '%$value%' OR `People Last Name` LIKE '%$value%'";

    $query = mysql_query($sql);

    $num = mysql_num_rows($query);
    if($num == 0){
        
        $user_str = "<i>No subscriber found...</i>";
        
    }else{
        $user_str = "<p>$num subscriber(s) found ...</p>";
        while($row=mysql_fetch_assoc($query)){

        $listname = getListnameById($row['People List Key']);
        $full_name = $row['People First Name'].' '.$row['People Last Name'];
        $email = $row['People Email'];

        $user_str .= "<p><b>$listname</b><br />"."<u>$email</u><br />"."$full_name<hr></p>";


        }
    }

    }
    

}
else{

    $user_str="<i>Please Enter a keyword.</i>";

}


 echo $user_str;

function getListnameById($id){

    $qry = mysql_query("SELECT `List Name` FROM `Email Campaign Mailing List` WHERE `Email Campaign Mailing List Key` = '$id'");
    $ro = mysql_fetch_assoc($qry);
    $listname = $ro['List Name'];
    return $listname;

}

?>
