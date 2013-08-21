<?php
require_once 'common.php';

if(!isset($_REQUEST['tipo']))
  {
    $response=array('state'=>405,'resp'=>'Non acceptable request (t)');
    echo json_encode($response);
    exit;
  }

  

$tipo=$_REQUEST['tipo'];

switch($tipo){
 case('update'):

   $keys=preg_split('/-/',$_REQUEST['keys']);
   switch(count($keys)){
   case 1:
	 
     $value=$_REQUEST['value'];
     $_SESSION['state'][$keys[0]]=$value;
	 //print $keys[0];
     echo $keys[0]."=".$value;
     break;
   case 2:
     $value=$_REQUEST['value'];
     //print $_SESSION['state'][$keys[0]][$keys[1]]."\n";
	 //print $keys[0];
	 //print $keys[1];
	 //print $value;
     $_SESSION['state'][$keys[0]][$keys[1]]=$value;
     print $_SESSION['state'][$keys[0]][$keys[1]]."\n";
   //  $data=$session->read(session_id( ));

     break;
   case 3:
     $value=$_REQUEST['value'];
     $_SESSION['state'][$keys[0]][$keys[1]][$keys[2]]=$value;
     echo $keys[0]."|".$keys[1]."|".$keys[2]."=".$value;
     break;
   case 4:
     $value=$_REQUEST['value'];
     $_SESSION['state'][$keys[0]][$keys[1]][$keys[2]][$keys[3]]=$value;
     print $_SESSION['state'][$keys[0]][$keys[1]][$keys[2]][$keys[3]];
     print_r($_SESSION['state'][$keys[0]]);
     break;
   case 5:
     $value=$_REQUEST['value'];
     $_SESSION['state'][$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]]=$value;
     print $_SESSION['state'][$keys[0]][$keys[1]][$keys[2]][$keys[3]][$keys[4]];
     
     break;

   }
   break;
 case('update_plot_product'):
   $value=$_REQUEST['value'];
   if(preg_match('/^product\_(week|month|quarter|year)\_(sales|outers)$/',$value)){
     $_SESSION['state']['product']['product']=$value;
     if(preg_match('/week/',$value))
       $plot_interval='week';
     elseif(preg_match('/month/',$value))
       $plot_interval='month';
     elseif(preg_match('/quarter/',$value))
       $plot_interval='quarter';
     elseif(preg_match('/year/',$value))
       $plot_interval='year';
     $data=$_SESSION['state']['product']['plot_data'][$plot_interval];
     $data['state']=200;
     echo json_encode($data);
     exit;
   }
   break;
 }



?>
