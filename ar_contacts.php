<?
require_once 'common.php';
require_once '_order.php';
require_once 'string.php';
require_once '_contact.php';


if (!$LU or !$LU->isLoggedIn()) {
  $response=array('state'=>402,'resp'=>_('Forbidden'));
  echo json_encode($response);
  exit;
 }


if(!isset($_REQUEST['tipo']))  {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
  }

$tipo=$_REQUEST['tipo'];
switch($tipo){

 case('update_contact'):
   $key=$_REQUEST['key'];


   switch($key){
   case('del_mob'):
   case('del_fax'):
   case('del_tel'):
     $telecom_id=$_REQUEST['id'];
     $sql=sprintf('delete from telecom where id=%d',$telecom_id);
     $db->exec($sql);
     $response=array('state'=>200,'element_id'=>$telecom_id);echo json_encode($response);return;
     break;
   case('del_email'):
     $email_id=$_REQUEST['id'];
     $sql=sprintf('delete from email where id=%d',$email_id);
     $db->exec($sql);
     $response=array('state'=>200,'element_id'=>$email_id);echo json_encode($response);return;
     break;
   case('del_www'):
     $www_id=$_REQUEST['id'];
     $sql=sprintf('delete from www where id=%d',$www_id);
     $db->exec($sql);
     $response=array('state'=>200,'element_id'=>$www_id);echo json_encode($response);return;
     break;
   case('del_a'):
     $address_id=$_REQUEST['id'];
     $sql=sprintf('delete from address where id=%d',$address_id);
     $db->exec($sql);
     $response=array('state'=>200,'element_id'=>$address_id);echo json_encode($response);return;
     break;
     
   case('telecom'):
     $tipo=$_REQUEST['telecom_tipo'];
     $new=0;
     $code_ok=false;
     $number_ok=false;
     $ext_ok=false;
     
     $name=$_REQUEST['name'];
     $code=$_REQUEST['code'];
     $number=$_REQUEST['number'];
     $ext=$_REQUEST['ext'];
     // check code
     $number=preg_replace('/\s\-/','',$number);
     $code=preg_replace('/\s\-/','',$code);
     $ext=preg_replace('/\s\-/','',$ext);

     if(preg_match('/^\d*$/',$code))
       $code_ok=true;
     if(preg_match('/^\d*$/',$number) and strlen($number)>5)
       $number_ok=true;
     if(preg_match('/^\d*$/',$number))
       $ext_ok=true;

     if($code_ok and $number_ok and $ext_ok){
       $sql_code=($code==''?'NULL':"'".$code."'");
       $sql_ext=($ext==''?'NULL':"'".$ext."'");
       $sql_name=($name==''?'NULL':"'".addslashes($name)."'");
       
       if(isset($_REQUEST['telecom_id']) and is_numeric($_REQUEST['telecom_id'])     ){
	 $telecom_id=$_REQUEST['telecom_id'];
	 $sql=sprintf("update telecom set name=%s,code=%s,number=%s,ext=%s where id=%d",$sql_name,$sql_code,$number,$sql_ext,$telecom_id);
	 $db->exec($sql);
       }elseif(isset($_REQUEST['contact_id']) and is_numeric($_REQUEST['contact_id'])){
	 $contact_id=$_REQUEST['contact_id'];
	 $sql=sprintf("insert into telecom (name,code,number,ext,tipo,contact_id) values  (%s,%s,'%s',%s,%d,%d)",$sql_name,$sql_code,$number,$sql_ext,$tipo,$contact_id);
	 $db->exec($sql);
	 $telecom_id = $db->lastInsertID();
	 $new=1;
       }else{
	  $response=array('state'=>400,'resp'=>_('Fatal Error'));echo json_encode($response);return;
       }
       
       $tel=($name!=''?'('.$name.') ':'').($code!=''?'+'.$code.' ':'').$number.($ext!=''?' '._('Ext').' '.$ext:'');
       $response=array('state'=>200,'tel'=>$tel,'name'=>$name,'ext'=>$ext,'code'=>$code,'number'=>$number,'ext_ok'=>$ext_ok,'number_ok'=>$number_ok,'code_ok'=>$code_ok,'telecom_id'=>$telecom_id,'new'=>$new);echo json_encode($response);return;

     }
     else{
       $response=array('state'=>300,'ext_ok'=>$ext_ok,'number_ok'=>$number_ok,'code_ok'=>$code_ok);echo json_encode($response);return;
     }
     break;
   case('email'):

     $new=0;
     $contact=$_REQUEST['name'];
     $address=$_REQUEST['address'];

     
     $address_ok=true;

     if($address=='')
       $address_ok=false;
     
     if($address_ok){
       $sql_contact=($contact==''?'NULL':"'".addslashes($contact)."'");
       $sql_address=addslashes($address);

       if(isset($_REQUEST['element_id']) and is_numeric($_REQUEST['element_id'])     ){
	 $email_id=$_REQUEST['element_id'];
	 $sql=sprintf("update email set contact=%s,email='%s' where id=%d",$sql_contact,$sql_address,$email_id);
	 $db->exec($sql);
       }elseif(isset($_REQUEST['contact_id']) and is_numeric($_REQUEST['contact_id'])){
	 $contact_id=$_REQUEST['contact_id'];
	 $sql=sprintf("insert into email (contact,email,contact_id) values  (%s,'%s',%d)",$sql_contact,$sql_address,$contact_id);
	 $db->exec($sql);
	 $email_id = $db->lastInsertID();
	 $new=1;
       }else{
	  $response=array('state'=>400,'resp'=>_('Fatal Error'));echo json_encode($response);return;
       }
       
       $email=($contact!=''?'('.$contact.') ':'').'<a href="'.$address.'">'.$address.'</a>';
       $response=array('state'=>200,'link_address'=>$email,'contact'=>$contact,'address'=>$address,'address_ok'=>$address_ok,'address_id'=>$email_id,'new'=>$new);echo json_encode($response);return;
     }
     else{
       $response=array('state'=>300,'email_ok'=>$email_ok);echo json_encode($response);return;
     }
     break;
     
 case('www'):

     $new=0;
     $title=$_REQUEST['name'];
     $address=$_REQUEST['address'];

     
     $address_ok=true;

     if($address=='')
       $address_ok=false;
     
     if($address_ok){
       $sql_title=($title==''?'NULL':"'".addslashes($title)."'");
       $sql_address=addslashes($address);
       
       if(isset($_REQUEST['element_id']) and is_numeric($_REQUEST['element_id'])     ){
	 $address_id=$_REQUEST['element_id'];
	 $sql=sprintf("update www set title=%s,www='%s' where id=%d",$sql_title,$sql_address,$address_id);
	 $db->exec($sql);
       }elseif(isset($_REQUEST['contact_id']) and is_numeric($_REQUEST['contact_id'])){
	 $contact_id=$_REQUEST['contact_id'];
	 $sql=sprintf("insert into www (title,www,contact_id) values  (%s,'%s',%d)",$sql_title,$sql_address,$contact_id);

	 $db->exec($sql);
	 $address_id = $db->lastInsertID();
	 $new=1;
       }else{
	  $response=array('state'=>400,'resp'=>_('Fatal Error'));echo json_encode($response);return;
       }
       
       $www=($title!=''?'('.$title.') ':'').'<a href="mailto:'.$address.'">'.$address.'</a>';
       $response=array('state'=>200,'link_address'=>$www,'title'=>$title,'address'=>$address,'address_ok'=>$address_ok,'address_id'=>$address_id,'new'=>$new);echo json_encode($response);return;
     }
     else{
       $response=array('state'=>300,'www_ok'=>$www_ok);echo json_encode($response);return;
     }
     
     break;

case('address'):

     $new=0;
     $description=$_REQUEST['description'];
     $a1=$_REQUEST['a1'];
     $a2=$_REQUEST['a2'];
     $a3=$_REQUEST['a3'];
     $d1=$_REQUEST['d1'];
     $d2=$_REQUEST['d2'];
     $d3=$_REQUEST['d3'];
     $fixed=$_REQUEST['fixed'];
     $princial=$_REQUEST['principal'];
     $town=$_REQUEST['town'];
     $postcode=$_REQUEST['postcode'];
     $country_id=$_REQUEST['country_id'];
	  
     
     
     $country_ok=true;
     $address_ok=true;
     $town_ok=true;
     $postcode_ok=true;
     

     
     if(!is_numeric($country_id))
       $country_ok=false;
     if($a1=='' and $a2=='' and $a3=='')
       $address_ok=false;
     if($town=='')
       $town_ok=false;
     if($postcode=='')
       $postcode_ok=false;

     
     if($address_ok and ($town_ok or $postcode_ok)  and $country_ok ){
       $sql_description=($description==''?'NULL':"'".addslashes($description)."'");
       $sql_a1=($a1==''?'NULL':"'".addslashes($a1)."'");
       $sql_a2=($a2==''?'NULL':"'".addslashes($a2)."'");
       $sql_a3=($a3==''?'NULL':"'".addslashes($a3)."'");
       $sql_d1=($d1==''?'NULL':"'".addslashes($d1)."'");
       $sql_d2=($d2==''?'NULL':"'".addslashes($d2)."'");
       $sql_d3=($d3==''?'NULL':"'".addslashes($d3)."'");
       $sql_town=($town==''?'NULL':"'".addslashes($town)."'");
       $sql_postcode=($postcode==''?'NULL':"'".addslashes($postcode)."'");
       $country=$_country[$country_id];

       
       $full_address=($description!=''?'('.$description.")\n":'').($a1!=''?$a1."\n":'').($a2!=''?$a2."\n":'').($a3!=''?$a3."\n":'').($d1!=''?$d1."\n":'').($town!=''?$town."\n":'').($d2!=''?$d2."\n":'').($d3!=''?$d3."\n":'').($postcode!=''?$postcode."\n":'').$country;

       if(isset($_REQUEST['address_id']) and is_numerisssc($_REQUEST['address_id'])     ){
	 $address_id=$_REQUEST['address_id'];
	 $sql=sprintf("update address set full_address='%s',description=%s, a1=%s,a2=%s,a3=%s,d1=%s,d2=%s,d3=%s,town=%s,postcode=%s,fixed=%d,principal=%d,country='%s' where id=%d"
		      ,$full_address
		      ,$sql_description
		      ,$sql_a1,$sql_a2,$sql_a3
		      ,$sql_d1,$sql_d2,$sql_d3
		      ,$sql_town
		      ,$postcode
		      ,$fixed
		      ,$principal
		      ,addslashes($country)
		      ,$address_id);
	 $db->exec($sql);
       }elseif(isset($_REQUEST['contact_id']) and is_numeric($_REQUEST['contact_id'])){
	 $contact_id=$_REQUEST['contact_id'];
	 $sql=sprintf("insert into telecom (full_address,description,a1,a2,a3,d1,d2,d3,town,postcode,fixed,principal,country,contact_id) values  ('%s',%s,%s,%s,%s,%s,%s,%s,%s,%s,%d,%d,'%s',%d)"
		      ,$full_address
		      ,$sql_a1,$sql_a2,$sql_a3
		      ,$sql_d1,$sql_d2,$sql_d3
		      ,$sql_town
		      ,$postcode
		      ,$fixed
		      ,$principal
		      ,addslashes($country)
		      ,$contact_id);
	 $address_id = $db->lastInsertID();
	 $db->exec($sql);
	 $address_id = $db->lastInsertID();
	 $new=1;
       }else{
	  $response=array('state'=>400,'resp'=>_('Fatal Error'));echo json_encode($response);return;
       }
       
       $address=preg_replace('/\n/','<br/>',$full_address); 
       $response=array('state'=>200
		       ,'address'=>$address
		       ,'a1'=>$a1,'a2'=>$a2,'a3'=>$a3
		       ,'d1'=>$d1,'d2'=>$d2,'d3'=>$d3
		       ,'town'=>$town
		       ,'postcode'=>$postcode
		       ,'country_id'=>$country_id
		       ,'address_ok'=>$address_ok
		       ,'town_ok'=>$town_ok
		       ,'postcode_ok'=>$postcode_ok
		       ,'country_ok'=>$country_ok
		       ,'address_id'=>$address_id
		       ,'new'=>$new);
       echo json_encode($response);return;
     }
     else{
       $response=array('state'=>300
		       ,'address_ok'=>$address_ok
		       ,'town_ok'=>$town_ok
		       ,'postcode_ok'=>$postcode_ok
		       ,'country_ok'=>$country_ok
		       );echo json_encode($response);return;
     }
     
     break;



   }
   
   $response=array('state'=>400,'resp'=>'No sub-operation');echo json_encode($response);return;



   break;


 case('contacts'):

  if(isset( $_REQUEST['sf']))
    $start_from=$_REQUEST['sf'];
  else
    $start_from=$_SESSION['tables']['contacts_list'][3];
  if(isset( $_REQUEST['nr']))
    $number_results=$_REQUEST['nr'];
  else
    $number_results=$_SESSION['tables']['contacts_list'][2];
  if(isset( $_REQUEST['o']))
    $order=$_REQUEST['o'];
  else
    $order=$_SESSION['tables']['contacts_list'][0];
  if(isset( $_REQUEST['od']))
    $order_dir=$_REQUEST['od'];
  else
    $order_dir=$_SESSION['tables']['contacts_list'][1];
  $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

  //  print_r($_SESSION['tables']['contacts_list']);
  $where='where true ';
  $wheref='';
   if(isset($_REQUEST['f_field']) and isset($_REQUEST['f_value'])){
     if($_REQUEST['f_field']=='cu.name' ){
       if($_REQUEST['f_value']!='')
	 $wheref="  and  ".$_REQUEST['f_field']." like '".addslashes($_REQUEST['f_value'])."%'";
     }
   }
   
   $_SESSION['tables']['contacts_list']=array($order,$order_direction,$number_results,$start_from);


   $sql="select count(*) as total from contact  $where $wheref";
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   if($row=$res->fetchRow()) {
     $total=$row['total'];
   }if($wheref!=''){
     $sql="select count(*) as total from contact  $where ";
     $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
     if($row=$res->fetchRow()) {
       $filtered=$row['total']-$total;
     }

   }else
     $filtered=0;


   if($total==0)
     $rtext=_('No contacts registered yet').'.';
   else if($total<$number_results)
    $rtext=$total.' '.ngettext('contact','contacts',$total);
  else
     $rtext='';

  $sql="select tipo,id,name as fname, order_name as name ,
(select email from email where contact_id=contact.id limit 1) as email ,
(select number from telecom where contact_id=contact.id limit 1) as tel,  
(select name  from contact_relations left join contact as c on (parent_id=c.id)  where child_id=contact.id limit 1) as company,  
(select parent_id from contact_relations where child_id=contact.id limit 1) as company_id
from contact     $where $wheref order by $order $order_direction limit $start_from,$number_results    ";
  //         print "$sql";
  $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}

  $adata=array();
  
  while($data=$res->fetchRow()) {

    $adata[]=array(
		   'id'=>$data['id'],
		   'name'=>$data['fname'],
		   'email'=>$data['email'],
		   'tipo'=>$data['tipo'],
		   'tel'=>$data['tel'],
		   'company'=>$data['company'],
		   'company_id'=>$data['company_id']

		   
		   );
  }
  $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$adata,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$res->numRows(),
			 'records_perpage'=>$number_results,
			 'records_text'=>$rtext,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
   break;
   // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 case('staff'):

  if(isset( $_REQUEST['sf']))
    $start_from=$_REQUEST['sf'];
  else
    $start_from=$_SESSION['tables']['staff_list'][3];
  if(isset( $_REQUEST['nr']))
    $number_results=$_REQUEST['nr'];
  else
    $number_results=$_SESSION['tables']['staff_list'][2];
  if(isset( $_REQUEST['o']))
    $order=$_REQUEST['o'];
  else
    $order=$_SESSION['tables']['staff_list'][0];
  if(isset( $_REQUEST['od']))
    $order_dir=$_REQUEST['od'];
  else
    $order_dir=$_SESSION['tables']['staff_list'][1];
  $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


   if(isset( $_REQUEST['where']))
     $where=addslashes($_REQUEST['where']);
   else
     $where=$_SESSION['tables']['staff_list'][4];

    
   if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$_SESSION['tables']['staff_list'][5];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$_SESSION['tables']['staff_list'][6];




  //  print_r($_SESSION['tables']['staff_list']);

  $wheref='';

  if(($f_field=='cu.name'  or  $f_field=='id' or  $f_field=='id2'  or  $f_field=='id3'  )  and $f_value!=''){
      $wheref="  and  ".$f_field." like '".addslashes($f_value)."%'";
  }
  else if($f_field=='maxdesde' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(last_order))<=".$f_value."    ";
  else if($f_field=='mindesde' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(last_order))>=".$f_value."    ";
  else if($f_field=='max' and is_numeric($f_value) )
    $wheref.=" and  orders<=".$f_value."    ";
  else if($f_field=='min' and is_numeric($f_value) )
    $wheref.=" and  orders>=".$f_value."    ";
  else if($f_field=='maxvalue' and is_numeric($f_value) )
    $wheref.=" and  total<=".$f_value."    ";
  else if($f_field=='minvalue' and is_numeric($f_value) )
    $wheref.=" and  total>=".$f_value."    ";



   $_SESSION['tables']['staff_list']=array($order,$order_direction,$number_results,$start_from,$where,$f_field,$f_value);


   $sql="select count(*) as total from staff  $where $wheref";
   //   print "$sql";
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   if($row=$res->fetchRow()) {
     $total=$row['total'];
   }if($wheref!=''){
     $sql="select count(*) as total from staff  as cu $where ";
     $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
     if($row=$res->fetchRow()) {
       $filtered=$row['total']-$total;
     }

   }else
     $filtered=0;


   if($total==0)
     $rtext=_('No staff member register yet').'.';
   else if($total<$number_results)
    $rtext=$total.' '.ngettext('staff member','staff members',$total);
  else
     $rtext='';

   $sql="select staff.id , staff.alias from staff   $where $wheref order by $order $order_direction limit $start_from,$number_results";
   //   print "$sql";
  $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}

  $adata=array();
  
  while($data=$res->fetchRow()) {
    $adata[]=array(
		   'id'=>$data['id'],
		   'alias'=>$data['alias']

      
		   );
  }
  $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$adata,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$res->numRows(),
			 'records_perpage'=>$number_results,
			 'records_text'=>$rtext,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
   break;
case('customers'):
  
  if(!$LU->checkRight(CUST_VIEW))
    exit;

  if(isset( $_REQUEST['sf']))
    $start_from=$_REQUEST['sf'];
  else
    $start_from=$_SESSION['tables']['customers_list'][3];
  if(isset( $_REQUEST['nr']))
    $number_results=$_REQUEST['nr'];
  else
    $number_results=$_SESSION['tables']['customers_list'][2];
  if(isset( $_REQUEST['o']))
    $order=$_REQUEST['o'];
  else
    $order=$_SESSION['tables']['customers_list'][0];
  if(isset( $_REQUEST['od']))
    $order_dir=$_REQUEST['od'];
  else
    $order_dir=$_SESSION['tables']['customers_list'][1];
  $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


  if(isset( $_REQUEST['where']))
    $where=addslashes($_REQUEST['where']);
   else
     $where=$_SESSION['tables']['customers_list'][4];

    
   if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$_SESSION['tables']['customers_list'][5];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$_SESSION['tables']['customers_list'][6];


  if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;
  //  print_r($_SESSION['tables']['customers_list']);

  $wheref='';

  if(($f_field=='cu.name'     )  and $f_value!=''){
      $wheref="  and  ".$f_field." like '".addslashes($f_value)."%'";
  }else if($f_field=='id3'  )
    $wheref.=" and  extra_id2 like '".addslashes(preg_replace('/\s*|\,|\./','',$f_value))."%' ";
  else if($f_field=='id2'  )
    $wheref.=" and  extra_id1 like '".addslashes(preg_replace('/\s*|\,|\./','',$f_value))."%' ";
else if($f_field=='id'  )
    $wheref.=" and  cu.id like '".addslashes(preg_replace('/\s*|\,|\./','',$f_value))."%' ";
  else if($f_field=='maxdesde' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(last_order))<=".$f_value."    ";
  else if($f_field=='mindesde' and is_numeric($f_value) )
    $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(last_order))>=".$f_value."    ";
  else if($f_field=='max' and is_numeric($f_value) )
    $wheref.=" and  orders<=".$f_value."    ";
  else if($f_field=='min' and is_numeric($f_value) )
    $wheref.=" and  orders>=".$f_value."    ";
  else if($f_field=='maxvalue' and is_numeric($f_value) )
    $wheref.=" and  total<=".$f_value."    ";
  else if($f_field=='minvalue' and is_numeric($f_value) )
    $wheref.=" and  total>=".$f_value."    ";



  $_SESSION['tables']['customers_list']=array($order,$order_direction,$number_results,$start_from,$where,$f_field,$f_value);


   $sql="select count(*) as total from customer as cu left join contact on (contact_id=contact.id)  $where $wheref";

   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   if($row=$res->fetchRow()) {
     $total=$row['total'];
   }if($wheref!=''){
     $sql="select count(*) as total_without_filters from customer  as cu left join contact on (contact_id=contact.id) $where ";

   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
     if($row=$res->fetchRow()) {
       $filtered=$row['total_without_filters']-$total;
     }

   }else{
     $filtered=0;
     $filter_total=0;
   }

   if($total==0 and $filtered>0){
     switch($f_field){
     case('cu.name'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer starting with")." <b>$f_value</b> ";
       break;
     }
   }
   elseif($filtered>0){
     switch($f_field){
     case('cu.name'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('only customers starting with')." <b>$f_value</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Remove Filter')."</span>";
       break;
     }
   }else
      $filter_msg='';
   
   $_order=$order;
   $_dir=$order_direction;
   if($order=='location'){
     if($order_direction=='desc')
       $order='country_code desc ,town desc';
     else
       $order='country_code,town';
     $order_direction='';
   }

    if($order=='total'){
      $order='supertotal';
   }

   $order=preg_replace('/name/','file_as',$order);

//   $sql="select id2,id3,ifnull(country_id,244) as country_id ,cu.id as id ,cu.name as name ,co.code2 as country_code2,co.code as country_code ,location , orders,UNIX_TIMESTAMP(last_order) date,cu.total as total  from customer as cu left join list_country as co on (co.id=country_id)     $where $wheref order by $order $order_direction limit $start_from,$number_results";
   $sql="select  (select count(*) from customer2group where group_id=9 and customer_id=cu.id) as is_staff, num_invoices,list_country.name as country_name, cu.file_as,(total_nd+total) as super_total,total_nd,total_nd/(total_nd+total) as  factor_num_orders_nd ,(cu.total/num_invoices) as total_avg,  postcode,cu.id as id ,cu.name as name , (num_invoices+num_invoices_nd) as orders,num_orders_nd,UNIX_TIMESTAMP(last_order) date,cu.total as total,town,code2 as country_code2, code as country_code from customer as cu left join contact on (contact_id=contact.id) left join address on (main_address=address.id) left join list_country on (country=list_country.name)   $where $wheref  order by $order $order_direction limit $start_from,$number_results";


  $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}

  $adata=array();
  
  while($data=$res->fetchRow()) {
    $last_order=$data['date'];
    $id="<a href='customer.php?id=".$data['id']."'>".$myconf['customer_id_prefix'].sprintf("%05d",$data['id']).'</a>';
  
    
    
    if($data['factor_num_orders_nd']>.60)
      $color='bbb';
    elseif($data['factor_num_orders_nd']>.40)
      $color='888';
    elseif($data['factor_num_orders_nd']>.20)
      $color='444';
    else
      $color='000';

    if($data['factor_num_orders_nd']<.05)
      $old_orders='';
    else{
      $orders_with_no_data=number($data['num_orders_nd']);
      $old_orders='<span title="'.$orders_with_no_data.' '.ngettext('order','orders',$orders_with_no_data).' '._('with no data available.').'" style="cuersor:pointer;position: relative;top: -0.3em;color:#'.$color.';font-size: 0.8em;">*</span>';
    }

    if($data['num_invoices']==0){
      $color='bbb';
      $super_total='<i  style="color:#'.$color.'">'._('ND').'</i>';
      $orders_with_no_data=number($data['num_orders_nd']);
      $old_orders='<span title="'.$orders_with_no_data.' '.ngettext('order','orders',$orders_with_no_data).' '._('with no data available.').'" style="cuersor:pointer;position: relative;top: -0.3em;color:#'.$color.';font-size: 0.8em;">*</span>';
    }else
      $super_total='<i  style="color:#'.$color.'">'.money($data['super_total']).'</i>';
    $orders=$old_orders.'<i  style="color:#'.$color.'">'.number($data['orders']).'</i>';
    if($data['is_staff']>0)
      $location='<span style="color:#999">('._('ex').')</span>'._('Staff');
    else
      $location='<img title="'.$data['country_name'].'"  src="art/flags/'.strtolower($data['country_code2']).'.gif" alt="'.$data['country_code'].'"> '.$data['town'].' '.preg_replace('/\s/','',$data['postcode']);

    $adata[]=array(
		   'id'=>$id,
		   'name'=>$data['name'],

	//	   'id2'=>$data['id2'],
	//	   'id3'=>$data['id3'],

		   //	   'location'=>$data['location'],
		   'location'=>$location,
		   'orders'=>$orders,
		   'last_order'=>$last_order,
		   'flast_order'=>strftime("%e %b %Y", strtotime('@'.$last_order)),
		   'super_total'=>$super_total
      
		   );
  }




  $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$adata,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$res->numRows(),
			 'records_perpage'=>$number_results,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
   break;
 case('newcompany'):
   
   $name=$_REQUEST['name'];

   $_SESSION['new_contact']['tipo']=1;
   $_SESSION['new_contact']['name']=array('','','','',$name,$name);
   $value='<img align="absbottom"  src="art/icons/building.png" /> '.$name;
   $response=array('state'=>200,'value'=>$value);
   echo json_encode($response);

   break;
 case('newcontact'):
   
   $pname=$_REQUEST['prefix'];
   $fname=$_REQUEST['firstname'];
   $lname=$_REQUEST['lastname'];
   $sname=$_REQUEST['suffix'];
   $order=(isset($_REQUEST['orden'])?$_REQUEST['orden']:'');
   $in_company=$_REQUEST['in_company'];


   if($pname==2 or $pname==3)
     $tipo=2;
   else
     $tipo=1;
   $aname=array($pname,$fname,$lname,$sname);
   
   $key='';
   if($tipo==2)
     $key='<img align="absbottom"  src="art/icons/user_female.png"/>';
   else
     $key='<img align="absbottom"  src="art/icons/user.png"/>';


   if($aname[0]==0)
     $aname[0]='';
   else
     $aname[0]=$_prefix[$aname[0]];
       
       

   $name=join (' ',$aname);
   $value=$key.' '.$name;
   
    $order=trim($lname.' '.$fname);
   
   if($in_company){
     
     $_SESSION['new_contact']['contact']=array($tipo,$pname,$fname,$lname,$sname,$name,$order);
     
   }else{

     $_SESSION['new_contact']['tipo']=$tipo;
     $_SESSION['new_contact']['name']=array($pname,$fname,$lname,$sname,$name,$order);
   }


   $response=array('state'=>200,'value'=>$value);
   echo json_encode($response);
   break;
 case('newaddress'):
   $add_tipo=(is_numeric($_REQUEST['add_tipo'])?$_REQUEST['add_tipo']:0);
   $a1=(isset($_REQUEST['a1'])?$_REQUEST['a1']:'');
   $a2=(isset($_REQUEST['a2'])?$_REQUEST['a2']:'');
   $a3=(isset($_REQUEST['a3'])?$_REQUEST['a3']:'');
   $town=$_REQUEST['town'];
   $state=(isset($_REQUEST['state'])?$_REQUEST['state']:'');
   $postcode=(isset($_REQUEST['postcode'])?$_REQUEST['postcode']:'');
   $country=$_REQUEST['country'];
   $country_id=$_REQUEST['country_id'];
   
   $a_address=array($add_tipo,$a1,$a2,$a3,$town,$state,$postcode,$country,$country_id);
   $_SESSION['new_contact']['address'][]=$a_address;
   
   
   $address='<br>'.($a1!=''?$a1."<br>":'').($a2!=''?$a2."<br>":'').($a3!=''?$a3."<br>":'').$town.'<br>'.($state!=''?$state."<br>":'').($postcode!=''?$postcode."<br>":'').$country;
   $key='';
   
   
   
   $response=array('state'=>200,'key'=>$key,'value'=>$address);
   echo json_encode($response);
   
   break;
 case('newemail'):
   $temail=$_REQUEST['temail'];
   $name=$_REQUEST['name'];
   $email=$_REQUEST['email'];
   $aemail=array($temail,$name,$email);
   $_SESSION['new_contact']['email'][]=$aemail;

   $value='<img src="art/icons/email.png" align="absbottom"> '.$email;
   $response=array('state'=>200,'contact'=>$name,'value'=>$value);
   echo json_encode($response);

   break;
 case('newwww'):

   $name=(isset($_REQUEST['name'])?$_REQUEST['name']:'');
   $www=$_REQUEST['www'];
   $awww=array($name,$www);
   $_SESSION['new_contact']['www'][]=$awww;

   $value='<img src="art/icons/page_world.png" align="absbottom"> '.$www;
   $response=array('state'=>200,'value'=>$value);
   echo json_encode($response);

   break;
 case('newtel'):
   
   $tipo=$_REQUEST['ttel'];
   $area=(isset($_REQUEST['area'])?$_REQUEST['area']:'');
   $code=$_REQUEST['code'];
   $number=$_REQUEST['number'];
   $ext=$_REQUEST['ext'];

   $atel=array($tipo,$area,$code,$number,$ext);
   $_SESSION['new_contact']['tel'][]=$atel;
   
   $atel[0]=$_tipo_tel[$atel[0]];
   if($atel[2]!='')
     $atel[2]='+'.$atel[2];
    if($atel[4]!='')
      $atel[4]=_('Ext.').' '.$atel[4];
   
   $tel=$atel[2].' '.$atel[3].' '.$atel[4];
   
   switch($tipo){
   case(0):
     $value='<img src="art/icons/telephone.png" alt="'.$atel[0].'"/>'.($area!=''?' ('.$area.')':'').' '.$tel;
     break;
   case(4):
     $value='<img src="art/icons/printer.png" alt="'.$atel[0].'"/>'.($area!=''?' ('.$area.')':'').' '.$tel;
     break;
   case(2):
     $value='<img src="art/icons/phone.png" alt="'.$atel[0].'"/>'.' '.$tel;
     break;
   case(1):
     $value='<img src="art/icons/telephone.png" alt="'.$atel[0].'"/> ('._('Work').') '.$tel;
     break;
  case(3):
     $value='<img src="art/icons/telephone.png" alt="'.$atel[0].'"/> ('._('Home').') '.$tel;
     break;    
   default:
     $value='';
     

   }



   $response=array('state'=>200,'value'=>$value);
   echo json_encode($response);

   break;
 case('savenew'):

   $contact_id=savecontact();


 //   //   if(isset($_SESSION['new_contact']['tipo'])  and is_numeric($_SESSION['new_contact']['tipo'])  ){
   
//    if(!isset($_SESSION['new_contact']['tipo']))
//      break;
     



//    print_r($_SESSION['new_contact']);
//    $tipo=$_SESSION['new_contact']['tipo'];
   
   
   


//    $name=addslashes($_SESSION['new_contact']['name'][4]);
//    $order=addslashes($_SESSION['new_contact']['name'][5]);

//    $sql=sprintf("insert into contact (name,order_name,tipo,date_creation,date_updated) values ('%s','%s',%d,NOW(),NOW())",$name,$order,$tipo);
//    $db->exec($sql);
//    $contact_id = $db->lastInsertID();
   
//    if(isset($_SESSION['new_contact']['contact'])){
//      $tipo=$_SESSION['new_contact']['contact'][0];
//      $name=addslashes($_SESSION['new_contact']['contact'][5]);
//      $order=addslashes($_SESSION['new_contact']['contact'][6]);
//      $sql=sprintf("insert into contact (name,order_name,tipo,date_creation,date_updated) values ('%s','%s',%d,NOW(),NOW())",$name,$order,$tipo);
//      $db->exec($sql);
//      $contactincompany_id = $db->lastInsertID();

//      $sql=sprintf("insert into contact_relations (child_id,parent_id) values (%d,%d)",$contactincompany_id,$contact_id);
//      $db->exec($sql);
     
//    }



//    if(isset($_SESSION['new_contact']['email']))
//      foreach($_SESSION['new_contact']['email'] as $aemail){
       
//        if($aemail[2]=='')
// 	 continue;
       
//        $tipo=$aemail[0];
//        $name=addslashes($aemail[1]);
//        $email=addslashes($aemail[2]);
//        $sql=sprintf("insert into email (contact,email,tipo,contact_id) values ('%s','%s',%d,%d)",$name,$email,$tipo,$contact_id);
// 	   $db->exec($sql);
//      }
//    if(isset($_SESSION['new_contact']['tel']))
//      foreach($_SESSION['new_contact']['tel'] as $atel){
//        if($atel[3]==''  )
// 	 continue;
       
       
//        $tipotel=$atel[0];
//        $name=($atel[1]!=''?'"'.addslashes($atel[1]).'"':'null');
//        $code=(is_numeric($atel[2])?$atel[2]:'null');
//        $number=(is_numeric($atel[3])?$atel[3]:'null');
//        $ext=(is_numeric($atel[4])?$atel[4]:'null');
       
//        $sql=sprintf("insert into telecom (name,code,number,ext,tipo,contact_id) values (%s,%s,%s,%s,%d,%d)",$name,$code,$number,$ext,$tipotel,$contact_id);
//        $db->exec($sql);
//        if($tipotel==1 and isset($contactincompany_id))
// 	 {
   
// 	   $sql=sprintf("insert into telecom (name,code,number,ext,tipo,contact_id) values (%s,%s,%s,%s,%d,%d)",$name,$code,$number,$ext,$tipotel,$contactincompany_id);
// 	   $db->exec($sql);
	   
// 	 }

//      }
//    if(isset($_SESSION['new_contact']['www']))
//        foreach($_SESSION['new_contact']['www'] as $awww){
	 

// 	 if($awww[1]=='')
// 	   continue;
	 
// 	 $title=($awww[0]!=''?"'".addslashes($awww[0]).'"':'null');
// 	 $www=addslashes($awww[1]);
	 
// 	 $sql=sprintf("insert into www (title,www,contact_id) values (%s,%s,%d)",$title,$www,$contact_id);
// 	 $db->exec($sql);
// 	 }
//    if(isset($_SESSION['new_contact']['address']))
//      foreach($_SESSION['new_contact']['address'] as $aadd){
       
       
//        $tipo=$aadd[0];
//        $address1=($aadd[1]!=''?'"'.addslashes($aadd[1]).'"':'null');
//        $address2=($aadd[2]!=''?'"'.addslashes($aadd[2]).'"':'null');
//        $address3=($aadd[3]!=''?'"'.addslashes($aadd[3]).'"':'null');
//        $town=($aadd[4]!=''?'"'.addslashes($aadd[4]).'"':'null');
//        $subdistrict=($aadd[5]!=''?'"'.addslashes($aadd[5]).'"':'null');
//        $postcode=($aadd[6]!=''?'"'.addslashes($aadd[6]).'"':'null');
//        $country=($aadd[7]!=''?'"'.addslashes($aadd[7]).'"':'null');
//        $country_id=$aadd[8];
       
//        $sql=sprintf("insert into address (tipo,address1,address2,address3,town,subdistrict,postcode,country,country_id,contact_id) values (%d,%s,%s,%s,%s,%s,%s,%s,%d,%d)",
// 		    $tipo,$address1,$address2,$address3,$town,$subdistrict,$postcode,$country,$country_id,$contact_id
// 		    );
//        print $sql;
//        $db->exec($sql);
	 




//        $response=array('state'=>200,'resp'=>$sql);
//        echo json_encode($response);
//        break;
//     }
     //}
  // $response=array('state'=>404,'resp'=>_('Error'));
  // echo json_encode($response);
   break;


 case('customer_history'):
 if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$_SESSION['tables']['order_withcust'][3];
   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$_SESSION['tables']['order_withcust'][2];
   if(isset( $_REQUEST['o']))
     $order=$_REQUEST['o'];
   else
     $order=$_SESSION['tables']['order_withcust'][0];
   if(isset( $_REQUEST['od']))
     $order_dir=$_REQUEST['od'];
   else
     $order_dir=$_SESSION['tables']['order_withcust'][1];
   

   if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
     $customer_id=$_REQUEST['id'];
   else
     $customer_id=$_SESSION['tables']['order_withcust'][4];


   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   

   $start_from=0;
   $number_results=10000;
   


   $_SESSION['tables']['order_withcust']=array($order,$order_direction,$number_results,$start_from,$customer_id);

   $where_orders=sprintf(" where customer_id=%d ",$customer_id);
   $wheref_orders="";
   if(isset($_REQUEST['f_field']) and isset($_REQUEST['f_value'])){
     if($_REQUEST['f_field']=='public_id' or $_REQUEST['f_field']=='customer'){
       if($_REQUEST['f_value']!='')
	 $wheref_orders==" and  ".$_REQUEST['f_field']." like '".addslashes($_REQUEST['f_value'])."%'";
     }
   }

  $where_note=sprintf(" where op='Customer' and op_id=%d ",$customer_id);
   $wheref_note="";
//    if(isset($_REQUEST['f_field']) and isset($_REQUEST['f_value'])){
//      if($_REQUEST['f_field']=='public_id' or $_REQUEST['f_field']=='customer'){
//        if($_REQUEST['f_value']!='')
// 	 $wheref_note==" and  ".$_REQUEST['f_field']." like '".addslashes($_REQUEST['f_value'])."%'";
//      }
//    }


   
  


   $sql="select count(*) as total from orden    $where_orders $wheref_orders";

   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   if($row=$res->fetchRow()) {
     $total=$row['total'];
   }
   if($wheref_orders==''){
     $filtered=0;
   }else{
     $sql="select count(*) as total from orden $where_orders      ";
     $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
     if($row=$res->fetchRow()) {
       $filtered=$row['total']-$total;
     }
     
   }
   
   $data=array();



   $sql=sprintf("select old_value,new_value, objeto_id,date,tipo,objeto,UNIX_TIMESTAMP(date) as date_index  from customer left join history  on (sujeto_id=contact_id) left join history_item on (history_id=history.id)   where sujeto='Contact' and customer.id=$customer_id  and (tipo='NEW' or tipo='UPD')  order by $order $order_direction  limit $start_from,$number_results");
//   print $sql;
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   
   while($row=$res->fetchRow()) {
     
     $description='';
     switch($row['tipo']){
     case('NEW'):
       //        $description= '<b>'.mb_ucwords(_('new')).'</b>';
       switch($row['objeto']){
       case('Contact'):
	 $description.=' '._('New contact').' '.get_name($row['objeto_id']);
	 break;
	case('Shop Address'):
	  
	  $address=preg_replace('/<br\/>.*$/i','',$row['new_value']);
	  $description.=' '._('New Address').": <b>$address</b>";
	 break; 
	case('Delivery Address'):
	  
	  $address=preg_replace('/<br\/>.*$/i','',$row['new_value']);
	  $description.=' '._('New Delivery Address').": <b>$address</b>";
	 break; 
	case('Work Email'):
	  $description.=' '._('New Email').": ".$row['new_value'];
	 break; 
       default:
	 //$description.=$row['objeto'];
	 //	 	 continue 3; 
	 
       }
       break;
     case('UPD'):
       
       switch($row['objeto']){
       case('Name'):
	 $description=_('Name Change').' ('.$row['old_value'].' &#8594; <b>'.$row['new_value'].'</b>)';;
	 break;
       case('Shop Address'):
	 continue 3; 
	 break;
       }

       
       break;
       
       
     default:
       //    continue 3; 
     }
     
     
     
     
     $tipo=mb_ucwords(_('history'));
     

     $datetime= strtotime('@'.$row['date_index']);
     $data[]=array(
		   'date_index'=>$row['date_index'],
		   'description'=> $description,
		   'date'=> strftime("%d-%m-%Y", $datetime),
		   'time'=> strftime("%H:%M", $datetime),
		   'day'=> strftime("(%a)",$datetime),
		   'tipo'=>$tipo,
		   'order'=>0
		   );
   }




    $sql=sprintf("select date,tipo,objeto,UNIX_TIMESTAMP(date) as date_index  from history  where sujeto='Customer' and sujeto_id=$customer_id and tipo='NEW'  order by $order $order_direction  limit $start_from,$number_results");
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   // print $sql;
   while($row=$res->fetchRow()) {

     $description='';
     switch($row['tipo']){
     case('NEW'):
       
       switch($row['objeto']){
       case('Delivery Address'):
	 $description.=' '._('New delivery address');
       default:
	 continue 2;
       }
       



     }
     
     
     $tipo=mb_ucwords(_('history'));
     

     $datetime= strtotime('@'.$row['date_index']);
     $data[]=array(
		   'date_index'=>$row['date_index'],
		   'description'=> $description,
		   'date'=> strftime("%d-%m-%Y", $datetime),
		   'time'=> strftime("%H:%M", $datetime),
		   'day'=> strftime("(%a)",$datetime),
		   'tipo'=>$tipo,
		   'order'=>0
		   );
   }




   $sql=sprintf("select net,parent_id,tipo,id,public_id ,UNIX_TIMESTAMP(date_index) as date_index from orden  $where_orders $wheref_orders order by $order $order_direction  limit $start_from,$number_results");
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}

   while($row=$res->fetchRow()) {


     if($row['tipo']==6 or $row['tipo']==7 ){
       $tipo='Follow up';
       
       if($parent=get_parent_order_public_id($row['id'])){
	 $from_parent=' ('._('from order').' <a href="order.php?id='.$row['parent_id'].'">'.$parent."</a>)";
       }else
	 $from_parent=' ('._('No original order data').')';

       $description='<b><a href="order.php?id='.$row['id'].'">'.$_order_tipo[$row['tipo']].'</a></b>'.$from_parent;
     }else if($row['tipo']==3 ){
        $description=$_order_tipo[$row['tipo']].' <b><a href="order.php?id='.$row['id'].'">'.$row['public_id']."</a></b> "._('Cancelled');
       
     }else{
       $tipo='Order';
       $description=$_order_tipo[$row['tipo']].' <b><a href="order.php?id='.$row['id'].'">'.$row['public_id']."</a></b> "._('for')." ".money($row['net']).' '._('plus tax');
     
     }
      $datetime= strtotime('@'.$row['date_index']);
     $data[]=array(
		   'date_index'=>$row['date_index'],
		   'description'=> $description,
		   'date'=> strftime("%d-%m-%Y", $datetime),
		   'time'=> strftime("%H:%M", $datetime),
		   'day'=> strftime("(%a)",$datetime),
		   'tipo'=>$tipo,
		   'order'=>2
		   );
   }


   $sql=sprintf("select texto ,UNIX_TIMESTAMP(date_index) as date_index from note  $where_note $wheref_note order by $order $order_direction  limit $start_from,$number_results");
  // print "$sql";
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
//&#160;

   while($row=$res->fetchRow()) {
     $description=$row['texto'];
     $tipo='Note';
     $datetime= strtotime('@'.$row['date_index']);
     $data[]=array(
		   'date_index'=>$row['date_index'],
		   'description'=> $description,
		   'date'=> strftime("%d-%m-%Y", $datetime),
		   'time'=> strftime("%H:%M", $datetime),
		   'day'=> strftime("(%a)",$datetime),

		   'tipo'=>$tipo,
		   'order'=>3
		   );
   }



   foreach ($data as $key => $row) {
     $dateindex[$key]  = $row['date_index'];
     $tipoindex[$key] = $row['order'];
   }
   array_multisort($dateindex, SORT_DESC, $tipoindex, SORT_DESC, $data);

   


   if($total<$number_results)
     $rtext=$total.' '.ngettext('record returned','records returned',$total);
   else
     $rtext='';
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$res->numRows(),
			 'records_perpage'=>$number_results,
			 'records_text'=>$rtext,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
   break;


case('plot_order_interval'):

  $now="'2008-04-18 08:30:00'";

  $sql="select count(*) as total from customer where order_interval>0    and  (order_interval*3)>DATEDIFF($now,last_order)   ";

  $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  if($row=$res->fetchRow()) {
    $total_sample=$row['total'];
  }
  $sql="select  CEIL(order_interval) as x ,count(*) as y from customer where order_interval>0 and order_interval<300    and  (order_interval*3)>DATEDIFF($now,last_order)     group by CEIL(order_interval)";
  //   print $sql;  
  $data=array();

  $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  while($row=$res->fetchRow()) {
  $data[]=array(
		'x'=>$row['x'],
		'y'=>$row['y']/$total_sample
		);
   }


 $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 )
		   );

   echo json_encode($response);
   break;


 default:
   $response=array('state'=>404,'resp'=>_('Operation not found'));
   echo json_encode($response);
   
 }


?>