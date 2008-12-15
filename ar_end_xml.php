<?
include_once('aes.php');
include_once('external_libs/xml/minixml.inc.php');

$key ="mc49015kfkuto0lk,rbijr0gl*(&172225224961644tui0f9jf98d345hpyl09jpl9fhlptjip_[[gdo;dlfkglklyndfUIHNNUIOSO832&*^)*(^^&*^*32KJLSDJFSNXRJ";
$key2="dsoap968m0()*)mERG048m03495xm3[7eyf7ERG8awe8723mx7o0sjt6pvp[rp9uyt87JYRTdr6erwet6r7twe6rt71wert7FWEw6u1s7t6dv1t71ry7i6yv1i78r6ui78rvu";
$path="app_files/p_xmldb/";
$separator='-';
if(!isset($_REQUEST['data'])){
  error('No data');
 }

$edata=base64_decode($_REQUEST['data']);
$data=json_decode(AESDecryptCtr($edata,'123',256),true);
//print_r($data);
if(is_array($data) and $data['key']==$key2){
  $operation=$data['operation'];
  switch($operation){
  case('get_file'):
    if(!isset($data['file']) or $data['file']=='')
      error('No/Wrong data file');
    $file=$path.$data['file'];
    if(!file_exists($file))
      error('No/Wrong data file');
    if($file_content=file_get_contents($file)){
      $finger_print=AESEncryptCtr(
				  json_encode(array('key'=>$key2,'trash'=>trash())),
				  $key,256);
      $response=array('state'=>200,'msg'=>'file returned','file'=>$file,'file_contant'=>$file_content,'finget_print'=>$finger_print);
      echo json_encode($response);
      exit;
    }else
	error('Can not read data file');
    break;
  case('update'):
    $code=$data['code'];
    if($code=='' or $code==$separator)
      error('Wrong code');
    $file=get_file($code);
    if(!file_exists($file)){

      $handle = fopen($file, 'x+');
      fclose($handle);
    }
      
    $xml_data=file_get_contents($file);
    $new_xml_data=update_product($xml_data,$code,$data['product_data']);
    if (is_writable($file)) {

      if (!$handle = fopen($file, 'w'))
	error("Cannot open file");
      if (fwrite($handle, $new_xml_data) === FALSE) 
	error("Cannot write on file");

      fclose($handle);
      $response=array('state'=>200,'msg'=>'product_updated','finget_print'=>finger_print());
      echo json_encode($response);
    }else
      	error('Data file not writeable');
    


    break;
  case('read'):
    $code=$data['code'];
    $file=get_file($code);
    if(!file_exists($file)){
      error('Product File not fonud');
    }

    break; 
    
 }
  
  
  
 }else{
   error('No data');
 }

function update_product($xml_data,$code,$product_data){
  $code=strtolower($code);
  $xml = new MiniXMLDoc();
  $xml->fromString($xml_data);
  $data=$xml->toArray();
  $data[$code]=$product_data;
  $xmlDoc = new MiniXMLDoc();

  $xmlDoc->fromArray($data);
  return $xmlDoc->toString();
  

}

function get_file($code){
  global $path,$separator;
  $code=strtolower($code);
  $code_array=split($separator,$code);
  if(count($code_array)==1){
    return  $path."noseparator.xml";
  }
  $_code = array_shift($code_array);
  $join=join("",$code_array);
  if(preg_match('/^[^\d]/',$join))
    return $path.$_code."_alfa.xml";
  $numbers=preg_replace('/[^\d]/','',$join);
  if($numbers=='')
    return $path.$_code."_alfa.xml";
  $numbers=sprintf("_%04d",50*floor(intval($numbers)/50));
  return $path.$_code.$numbers.".xml";


}

function finger_print(){
  return "weoirwpoirpoifopdsfipsdf";
}

function error($msg='Error'){
  global $key,$key2;
  $response=array('state'=>400,'msg'=>$msg);
  echo json_encode($response);
  exit;

}

function trash(){
  return md5(date('U').'qwertyuiopahjkl');
}

?>