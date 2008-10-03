<?

function get_staff_alias($staff_id){

  if(!is_numeric($staff_id) or $staff_id<1)
    return false;
$db =& MDB2::singleton();
 $sql=sprintf("select alias from staff where id=%d",$staff_id);
 $res = $db->query($sql);  
 if($row=$res->fetchRow()){
   return $row['alias'];
 }else
   return false;
}




function get_staff_data($staff_id){
$db =& MDB2::singleton();
 $sql=sprintf("select * from staff where id=%d",$staff_id);
 // print "$sql";
 $res = $db->query($sql);  
 if($row=$res->fetchRow()){
   $sql=sprintf("select id from customer  where contact_id=%d",$row['contact_id']);
   $res2 = $db->query($sql);  
   if($row2=$res2->fetchRow())
     $customer_id=$row2['id'];
   else
     $customer_id=false;

   $row['customer_id']=$customer_id;
   $row['name']= get_name($row['contact_id']);
   return $row;
 }else
   return false;
}

function is_staff_customer($staff_id){
$db =& MDB2::singleton();
 $sql=sprintf("select customer.id from customer  where id=%d",$staff_id);
 $res = $db->query($sql);  
 if($row=$res->fetchRow())
   return $row;
 else
   return false;
}


function get_user_id($oname,$order_id='',$tipo='',$record=true){

  //print "$record\n";

  $ids=array();
  if($oname=='' or is_numeric($oname))
    return array();


  $_names=array();
  
  $_names=preg_split('/\s*(\+|\&|,+|\/|\-)\s*/',strtolower($oname));
   foreach($_names as $_name){    
     $_name=_trim(strtolower($_name));
    if($_name=='')
      continue;
    $original_name=$_name;
    //    print $_name;
    $_name=preg_replace('/^\s*/','',$_name);
    $_name=preg_replace('/\s*$/','',$_name);
    if(preg_match('/michele|michell/i',$_name)   )
      $_name='michelle';
    else if( $_name=='salvka' or    preg_match('/^slavka/i',$_name) or $_name=='slavke' or $_name=='slavla' )
      $_name='slavka';
 else if(preg_match('/^malcom$/i',$_name)  )
      $_name='malcolm';

    else if(preg_match('/katerina/i',$_name) or $_name=='katka]' or   $_name=='katk'   )
      $_name='katka';
 else if(preg_match('/richard w/i',$_name) or $_name=='rich')
      $_name='richard';
    else if(preg_match('/david\s?(hardy)?/i',$_name))
      $_name='david';
    else if(preg_match('/philip|phil/i',$_name))
      $_name='philippe';
     else if(preg_match('/amanada|amand\s*$/i',$_name))
      $_name='amanda';
     else if(preg_match('/janette/i',$_name) or $_name=='jqnet' )
      $_name='janet';
  else if(preg_match('/pete/i',$_name))
      $_name='peter';
 else if(preg_match('/debra/i',$_name))
      $_name='debbie';
 else if(preg_match('/sam/i',$_name))
      $_name='samantha';
     else if($_name=='philip' or $_name=='ph' or $_name=='phi' )
       $_name='philippe';
     else if(  $_name=='aqb' or $_name=='kj' or  $_name=='act' or $_name=='tr'  or    $_name=='other' or $_name=='?' or $_name=='bb')
         return array();
     else if($_name=='thomas' or $_name=='tb' or preg_match('/^\s*tomas\s*$/i',$_name) or $_name=='tom' )
       $_name='tomas';
     else if($_name=='alam' or $_name=='aw' or   $_name=='al' or   $_name=='al.'  or  $_name=='ala' )
       $_name='alan';
 else if($_name=='carol')
       $_name='carole';

     else if($_name=='dushan' or $_name=='duscan' or $_name=='dus')
       $_name='dusan';
     else if($_name=='eli' or $_name=='eilska' or $_name=='eilsk' or $_name=='elsika' or $_name=='elishka')
       $_name='eliska';
     else if($_name=='jiom' or $_name=='tim'  or $_name=='jimbob'    or  $_name=='jikm')
       $_name='jim';
     else if($_name=='beverley' or $_name=='ber'  or $_name=='bav')
       $_name='bev';
     else if(   $_name=='albett' or  $_name=='alnert'  or    $_name=='alberft' or   $_name=='alberyt' or    $_name=='alabert'  or   $_name=='albet' or $_name=='albert ' or $_name=='albet ' or$_name=='alberto'  or $_name=='alb'  or $_name=='albery' or $_name=='alberty' or $_name=='ac'  or $_name=='albeert'  )
       $_name='albert';
     else if($_name=='ab' or $_name=='adr')
       $_name='adriana';
     else if($_name=='jamet' or $_name=='jante' or $_name=='jant' or $_name=='jnet' or $_name=='j' or $_name=='jenet'  or $_name=='jsnet'  )
       $_name='janet';
     else if($_name=='slvaka')
       $_name='slavka';
     else if($_name=='ct')
       $_name='craig';
     else if($_name=='k ' or $_name=='k' or $_name=='katerina2')
       $_name='katka';
     else if($_name=='daniella' or $_name=='daniella' )
       $_name='daniela';
     else if($_name=='cc' or $_name==' cc')
      $_name='chris';
    else if($_name=='bret')
      $_name='brett';
    else if($_name=='lucia' or $_name=='luc')
      $_name='lucie';
    else if($_name=='mat')
      $_name='matus';
    else if($_name=='ob' or $_name=='o.b.')
      $_name='olga';
    else if($_name=='stacy')
      $_name='stacey';
     else if($_name=='kkzoe' or $_name=='kzoe')
      $_name='zoe';
  else if($_name=='cph')
      $_name='caleb';
 else if($_name=='jenka')
      $_name='lenka';
 else if($_name=='jjanka')
      $_name='janka';
else if($_name=='jarina')
      $_name='jirina';

    $sql=sprintf("select id from staff where alias='$_name'");
    //print "$sql\n";
    $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
    if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      $id=$row['id'];
      $ids[]=$id;
    }else if($record){
      $sql=sprintf("insert into todo_users (name,order_id,tipo) values ('%s','%s','%s')",addslashes($original_name),$order_id,$tipo);
      //    print "$sql\n";
      mysql_query($sql);

    }
  }
  return $ids;
}


?>