<?

class Page{
  var $db;
  var $data=array();
  var $id=false;

  
  function __construct($arg1=false,$arg2=false) {
     $this->db =MDB2::singleton();
     
     if(!$arg1 and !$arg2)
       return;
     if(is_numeric($arg1)){
       $this->get_data('id',$arg1);
       return;
     }
      if(is_array($arg2) and $arg1='new'){
       $this->create($arg2);
       return;
     }
      $this->get_data($arg1,$arg2);

  }


  function get_data($tipo,$tag){
    if($tipo=='id')
      $sql=sprintf("select * from `Page Dimension` where  `Page Key`=%d",$tag);
    elseif($tipo=='url')
      $sql=sprintf("select * from `Page Dimension` where  `Page URL`=%s",prepare_mysql($tag));
    
   $result =& $this->db->query($sql);
    if($this->data=$result->fetchRow()){
      $this->id=$this->data['page key'];
      return true;
    }
    return false;
}

  function create($data,$args=''){

   if($data['page url']==''){
     $this->new=false;
     $this->msg=_('No page URL provided');
     return false;
   }
   if($this->is_valid($data['page url']))
     $data['page validated']=1;
   $sql=sprintf("insert into `Page Dimension`  (`Page URL`,`Page Type`,`Page Description`,`Page Validated`,`Page Verified`) values (%s,%s,%s,%d,%d)"
		,prepare_mysql($data['page url'])
		,prepare_mysql($data['page type'])
		,prepare_mysql($data['page description'])
		,$data['page validated']
		,$data['page verified']
		);
   print "$sql\n";
   $affected=& $this->db->exec($sql);
   if (PEAR::isError($affected)){
      $this->new=false;
      $this->msg=_('Unknown error');
      return false;
   }else{
      $this->id=$this->db->lastInsertID();
      //      if(is_array($history_data))
      //	$this->save_history('new',$history_data);
      $this->get_data('id',$this->id);
      $this->new=true;

      $this->msg=_('New Page');
      return true;
   }
     
     
 }

 function get($key){
 $key=strtolower($key);
    if(isset($this->data[$key]))
      return $this->data[$key];

    switch($key){
    case('link'):
      return $this->display();
      break;
    default:
      if(isset($this->data[$key]))
	return $this->data[$key];
    }
    return false;
 }

 function set($key,$value){
    switch($key){
    default:
      $this->data[$key]=$value;
    }

 }
 
 function update($data,$args=false,$history_data=false){
   $key=key($data);
   $value=$data['value'];
   switch($key){
   case('page'):
     if($value==''){
       $this->update_msg=_('The new page is empty');
       return false;
     }
     if(!$this->is_valid($value)){
       $this->update_msg=_('The new page is not valid');
       return false;
     }
     if($value==$this->get($key)){
       $this->update_msg=_('The new page is the same as the old one');
       return false;
     }
     $this->update_msg=_('Page changed to')." ".$value;

     break;     
   case('contact'):
     if($value==$this->get($key)){
       $this->update_msg=_('The new page contact  is the same as the old one');
       return false;
     }

     $this->update_msg=_('Page contact changed to')." ".$value;
     break;    
   case('tipo'):
     if($value==$this->get($key)){
       $this->update_msg=_('The new page type is the same as the old one');
       return false;
     }

     $this->old_value2=$this->get('tipo_page');
     switch($value){
     case(0):
       $this->set('tipo_page','work');
       break;
     case 1:
       $this->set('tipo_page','personal');
       break;
     case 2:
       $this->set('tipo_page','company');
       break;
     default:
       $this->update_msg=_('Wrong page type');
       return false;
     }

     $this->update_msg=_('Page type changed to')." ".$this->get('tipo_page');
     break; 
 case('tipo_page'):
    if($value==$this->get($key)){
       $this->update_msg=_('The new page type is the same as the old one');
       return false;
     }

     $this->old_value2=$this->get('tipo');
     switch($value){
     case('work'):
       $this->set('tipo',0);
       break;
     case ('personal'):
       $this->set('tipo_page',1);
       break;
     case ('company'):
       $this->set('tipo_page',2);
       break;
     default:
       $this->update_msg=_('Wrong page type');
       return false;
     }

     $this->update_msg=_('Page type changed to')." ".$this->get('tipo_page');
     break; 
   case('contact_id'):
     if($value==$this->get($key)){
       $this->update_msg=_('The new page contact the same as the old one');
       return false;
     }
     $contact=new Contact($value);
     if(!$contact->id){
       $this->update_msg=_('Contact do not exist');
       return false;
     }
     if($contact->get('has_page_id',$id)){
       $this->update_msg=_('Contact already has this page');
       return false;
     }
     
     break;

   default:
      $this->update_msg=_('Wrong update key');
      return false;
   }
   $this->old_value=$this->get($key);
   $this->set($key,$value);
   
   if(preg_match('/save/',$args)){
     $this->save($key,$history_data);
     if($key=='tipo'){
       $this->old_value=$this->old_value2;
       $this->save('tipo_page',$history_data);
     }elseif($key=='tipo_page'){
       $this->old_value=$this->old_value2;
       $this->save('tipo',$history_data);
     }

   }
 }



 function save($key,$history_data=false){
    switch($key){


    default:
      $sql=sprintf("update page set %s=%s where id=%d",$key,$this->get($key),$this->id);
      $this->db->exec($sql);
      if(is_array($history_data)){
	$this->save_history($key,$history_data);
      }
    }

 }

 function save_history($key,$history_data){

   $old=$this->old_value;
   if($key=='new'){
     $old='';
     $new=$this->get('page');
     }else{
     $new=$this->get($key);
     $old=$this->old_value;
   }
   if(isset($history_data['msg'])){
     $note=$history_data['msg'];
   }else
     $note=$this->update_msg;

   if(
      isset($history_data['sujeto']) and 
      isset($history_data['sujeto_id'])and 
      isset($history_data['objeto']) and 
      isset($history_data['objeto_id'])
      ){
     
     $sujeto=$history_data['sujeto'];
     $sujeto_id=$history_data['sujeto_id'];
     $objeto=$history_data['objeto'];
     $objeto_id=$history_data['objeto_id'];
     if($key=='new')
       $tipo='NEW';
     else
       $tipo='CHGEML';


   }else{
     $sujeto='PAGE';
     $sujeto_id=$this->$id;
     $objeto=$key;
     $objeto_id='';
     if($key=='new')
       $tipo='NEW';
     else
       $tipo='CHG';
     switch($key){
     case('page'):
       $objeto='PAGE';
       break;
     case('contact'):
        $objeto='PAGEC';
	break;
     case('verified'):
       $objeto='PAGEV';
       break;
     case('tipo'):
       $objeto='PAGET';
       break;
     case('contact_id'):
       $objeto='PAGEC';
       break;
     case('new'):
       $objeto='';
       break;
     }

   }

    $sql=sprintf("insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)"
		 ,$date
		 ,prepare_mysql($sujeto)
		 ,prepare_mysql($sujeto_id)
		 ,prepare_mysql($objeto)
		 ,prepare_mysql($objeto_id)
		 ,prepare_mysql($action)
		 ,prepare_mysql($user_id)
		 ,prepare_mysql($old)	 
		 ,prepare_mysql($new)	 
		 ,prepare_mysql($note)); 
    // print $sql;
    $this->db->exec($sql);


 }
 


 function display($tipo='link'){

   switch($tipo){
   case('html'):
   case('xhtml'):
   case('link'):
   default:
     return '<a href="'.$this->data['page'].'">'.$this->data['page'].'</a>';
     
   }
   

 }



 
 function is_valid($url){
   if (preg_match("/^(http(s?):\\/\\/|ftp:\\/\\/{1})((\w+\.)+)\w{2,}(\/?)$/i", $url))
     return true;
   else
     return false;
   
 }

?>