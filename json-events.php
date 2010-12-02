<?php
require_once 'common.php';

	$year = date('Y');
	$month = date('m');

 $sql="select  * from `Calendar Event Dimension` where MONTH(`Date From`)=$month";

    $res = mysql_query($sql);
    $adata=array();

   
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
           
       
         $adata[]=array(
               'id'=>$row['Calendar Event Key']
                         
                                ,'title'=>$row['Event Title']
                                        ,'start'=>$row['Date From']
                                                ,'end'=>$row['Date To']
                                                          
                 );




    }echo json_encode($adata);
    mysql_free_result($res);





?>
