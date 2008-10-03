<?



require_once 'MDB2.php';
$dsn = 'mysql://root:ajolote1@localhost/aw';
$db =& MDB2::connect($dsn);
$db->setFetchMode(MDB2_FETCHMODE_ASSOC);



$sql="select Code2 as code2,Code  as id from Country";
$result=$db->query($sql);

while($row=$result->fetchRow() ){
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
    $db->query($sql);
  }
 }





?>