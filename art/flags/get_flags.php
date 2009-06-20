<?



require_once 'MDB2.php';
$dsn = 'mysql://root:ajolote1@localhost/aw';





$sql="select Code2 as code2,Code  as id from Country";
$result=mysql_query($sql);

while($row= mysql_fetch_array($result, MYSQL_ASSOC)){
  $code=trim(strtolower($row['code2']));
  $id=$row['id'];
  //exec ("wget http://style.dailymotion.com/images/flag/$code.gif");
  
  $filename=$code.".gif";

  if(file_exists($filename)){

    
    $tmpfile = fopen($filename,'rb');
    //fseek($tmpfile,0,null);
    //$user_file = addslashes(fgets($tmpfile, filesize($tmpname)));
    

    $instr = fopen($filename,"rb");
    $image = addslashes(fread($instr,filesize($filename)));

    print "$filename x".filesize($filename)."x  \n";
    $sql="update Country set Flag='".$image."' where Code='".$id."'";
    mysql_query($sql);
  }
 }





?>