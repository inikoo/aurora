<?
require_once 'common.php';
require_once '_order.php';

require_once '_contact.php';
require_once 'classes/Customer.php';


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

 case('update_customer'):
   $key=$_REQUEST['key'];
   
   $customer=new customer($_SESSION['state']['customer']['id']);
   if(!$customer->id){
     $response=array('state'=>400,'msg'=>_('Customer not found'));
     echo json_encode($response);
     return;
   }
   
   switch($key){
   case('add_note'):
   case('new_note'):
     $data=array(
		 'note'=>$_REQUEST['value'],
		 'user_id'=>$LU->getProperty('auth_user_id')

		 );
     $customer->save_history('new_note','','',$data);
     $response=array('state'=>200,'msg'=>$customer->msg);
     echo json_encode($response);
     return;
     break;
 
   }

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

   
  $conf=$_SESSION['state']['hr']['staff'];
  if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$conf['sf'];
   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$conf['nr'];
  if(isset( $_REQUEST['o']))
    $order=$_REQUEST['o'];
  else
    $order=$conf['order'];
  if(isset( $_REQUEST['od']))
    $order_dir=$_REQUEST['od'];
  else
    $order_dir=$conf['order_dir'];
    if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];
  if(isset( $_REQUEST['where']))
     $where=$_REQUEST['where'];
   else
     $where=$conf['where'];
  
  if(isset( $_REQUEST['view']))
    $view=$_REQUEST['view'];
  else
    $view=$_SESSION['state']['hr']['view'];




   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;

 $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   $_order=$order;
   $_dir=$order_direction;



  $_SESSION['state']['hr']['staff']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
  $_SESSION['state']['hr']['view']=$view;


   $wheref='';
   if($f_field=='name' and $f_value!=''  )
     $wheref.=" and  name like '%".addslashes($f_value)."%'    ";
   else if($f_field=='position_id' or $f_field=='area_id'   and is_numeric($f_value) )
     $wheref.=sprintf(" and  $f_field=%d ",$f_value);
  
  
  switch($view){
   case('all'):
     break;
   case('staff'):
     $where.=' and active=1  ';
     break;
   case('exstaff'):
     $where.=' and active=0 ';
     break;
  }

   $sql="select count(*) as total from staff left join contact on (contact_id=contact.id) $where $wheref";
   //   print "$sql";
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   if($row=$res->fetchRow()) {
     $total=$row['total'];
   }if($wheref!=''){
     $sql="select count(*) as total from staff   $where ";
     $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
     if($row=$res->fetchRow()) {
       $total_records=$row['total'];
       $filtered=$row['total']-$total;
     }

   }else{
     $filtered=0;
     $total_records=$total;
   }
   
   
   $rtext=$total_records." ".ngettext('record','records',$total_records);
   if($total_records>$number_results)
     $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));
   $filter_msg='';
   
    switch($f_field){
     case('name'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff with name")." <b>*".$f_value."*</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff with name')." <b>*".$f_value."*</b>) <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;
    case('area_id'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff on area")." <b>".$f_value."</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff on area')." <b>".$f_value."</b>) <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;
    case('position_id'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff with position")." <b>".$f_value."</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff with position')." <b>".$f_value."</b>) <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;

    }



   $sql="select staff.id , position_id as position ,staff.alias,name,area_id as area,department_id as department from staff left join contact on (contact_id=contact.id)  $where $wheref order by $order $order_direction limit $start_from,$number_results";

   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   $adata=array();
   while($data=$res->fetchRow()) {
     if(is_numeric($data['position']))
       $pos=$_position_tipo[$data['position']];
     else
       $pos='';

     $_id=$myconf['staff_prefix'].sprintf('%03d',$data['id']);
     $id=sprintf('<a href="staff.php?id=%d">%s</a>',$data['id'],$_id);
     $adata[]=array(
		    'id'=>$id,
		    'alias'=>$data['alias'],
		    'name'=>$data['name'],
		    'department'=>$_company_department_tipo[$data['department']],
		    'area'=>$_company_area_tipo[$data['area']],
		    'position'=>$pos
		    
		    );
  }
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$adata,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$res->numRows(),
			 'records_perpage'=>$number_results,
			 'rtext'=>$rtext,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
   break;
case('customers_advanced_search'):
 if(!$LU->checkRight(CUST_VIEW))
    exit;
 $conf=$_SESSION['state']['customers']['advanced_search'];
  if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$conf['sf'];
   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$conf['nr'];
  if(isset( $_REQUEST['o']))
    $order=$_REQUEST['o'];
  else
    $order=$conf['order'];
  if(isset( $_REQUEST['od']))
    $order_dir=$_REQUEST['od'];
  else
    $order_dir=$conf['order_dir'];
//     if(isset( $_REQUEST['f_field']))
//      $f_field=$_REQUEST['f_field'];
//    else
//      $f_field=$conf['f_field'];

//   if(isset( $_REQUEST['f_value']))
//      $f_value=$_REQUEST['f_value'];
//    else
//      $f_value=$conf['f_value'];
  if(isset( $_REQUEST['where']))
     $awhere=$_REQUEST['where'];
   else
     $awhere=$conf['where'];
  
  
   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;

   
   $filtered=0;
   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   $_order=$order;
   $_dir=$order_direction;

   //print_r($_SESSION['state']['customers']['advanced_search']);
   $_SESSION['state']['customers']['advanced_search']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from
							    ,'where'=>$awhere
							    //,'f_field'=>$f_field,'f_value'=>$f_value
							   );
   $filter_msg='';
   // $awhere='{"from1":"","from2":"","product_not_ordered1":"","product_not_ordered2":"","product_not_received1":"","product_not_received2":"","product_ordered1":"g(ob)","product_ordered2":"","to1":"","to2":""}';
   $awhere=preg_replace('/\\\"/','"',$awhere);
  //    print "$awhere";
   $awhere=json_decode($awhere,TRUE);
  // print_r($awhere);
   $where='where ';

  if($awhere['product_ordered1']!=''){
    if($awhere['product_ordered1']!='ANY'){
      $where_product_ordered1=extract_product_groups($awhere['product_ordered1']);
    }else
      $where_product_ordered1='true';
  }else
    $where_product_ordered1='false';
  
  if($awhere['product_not_ordered1']!=''){
    if($awhere['product_not_ordered1']!='ALL'){
      $where_product_not_ordered1=extract_product_groups($awhere['product_ordered1'],'product.code not like','transaction.product_id not like','product_group.name not like','product_group.id like');
    }else
      $where_product_not_ordered1='false';
  }else
    $where_product_not_ordered1='true';

 if($awhere['product_not_received1']!=''){
    if($awhere['product_not_received1']!='ANY'){
      $where_product_not_received1=extract_product_groups($awhere['product_ordered1'],'(ordered-dispached)>0 and    product.code  like','(ordered-dispached)>0 and  transaction.product_id not like','(ordered-dispached)>0 and  product_group.name not like','(ordered-dispached)>0 and  product_group.id like');
    }else
      $where_product_not_received1=' ((ordered-dispached)>0)  ';
  }else
    $where_product_not_received1='true';




  $date_interval1=prepare_mysql_dates($awhere['from1'],$awhere['to1'],'date_index','only_dates');


  $geo_base='';
  if($awhere['geo_base']=='home')
    $geo_base='and list_country.id='.$myconf['country_id'];
  elseif($awhere['geo_base']=='nohome')
    $geo_base='and list_country.id!='.$myconf['country_id'];
  $with_mail='';
  if($awhere['mail'])
    $with_mail=' and main_email is not null ';
  $with_tel='';
  if($awhere['tel'])
    $with_tel=' and main_tel is not null ';



  $where='where ('.$where_product_ordered1.' and '.$where_product_not_ordered1.' and '.$where_product_not_received1.$date_interval1['mysql'].")  $geo_base $with_mail $with_tel";
  
  


  




  
  $sql="select count(distinct customer_id) as total  from customer left join orden on (customer_id=customer.id) left join transaction on (order_id=orden.id) left join product on (product_id=product.id) left join product_group on (group_id=product_group.id) left join product_department on (product_group.department_id=product_department.id)    left join address on (main_bill_address=address.id) left join list_country on (country=list_country.name)   $where  ";
 //print $sql;

   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   if($row=$res->fetchRow()) {
     $total=$row['total'];
   }else
     $total=0;


   $rtext=$total." ".ngettext($total,'results found','result found');
   
  $sql=" select telecom.number,telecom.icode,telecom.ncode,telecom.ext, postcode,town,list_country.code as country_code,code2 as country_code2,list_country.name as country_name, email ,email.contact as email_contact, UNIX_TIMESTAMP(max(date_index)) as last_order ,count(distinct orden.id) as orders, customer.id,customer.name from customer left join orden on (customer_id=customer.id) left join transaction on (order_id=orden.id) left join product on (product_id=product.id)  left join product_group on (group_id=product_group.id)  left join product_department on (product_group.department_id=product_department.id)      left join email on (main_email=email.id) left join telecom on (main_tel=telecom.id) left join address on (main_bill_address=address.id) left join list_country on (country=list_country.name) $where  group by customer_id order by $order $order_direction limit $start_from,$number_results";
 // print $sql;
 $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  $adata=array();
  while($data=$res->fetchRow()) {
     $id="<a href='customer.php?id=".$data['id']."'>".$myconf['customer_id_prefix'].sprintf("%05d",$data['id']).'</a>';
     $location='<img title="'.$data['country_name'].'"  src="art/flags/'.strtolower($data['country_code2']).'.gif" alt="'.$data['country_code'].'"> '.$data['town'].' '.preg_replace('/\s/','',$data['postcode']);
     $email='';
     if($data['email']!='')
       $email='<a href="emailto:'.$data['email'].'"  >'.$data['email'].'</a>';
        $tel='';
     if($data['number']!='')
       $tel=($data['icode']!=''?'+'.$data['icode'].' ':'').$data['number'];


     $adata[]=array(
		   'id'=>$id,
		   'name'=>$data['name'],
		   'orders'=>$data['orders'],
		   'last_order'=>strftime("%e %b %Y", strtotime('@'.$data['last_order'])),
		   'location'=>$location,
		   'email'=>$email,
		   'tel'=>$tel,
		     );		   
      
  }
  
  $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$adata,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'rtext'=>$rtext,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$res->numRows(),
			 'records_perpage'=>$number_results,
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

  $conf=$_SESSION['state']['customers']['table'];
  if(isset( $_REQUEST['sf']))
     $start_from=$_REQUEST['sf'];
   else
     $start_from=$conf['sf'];
   if(isset( $_REQUEST['nr']))
     $number_results=$_REQUEST['nr'];
   else
     $number_results=$conf['nr'];
  if(isset( $_REQUEST['o']))
    $order=$_REQUEST['o'];
  else
    $order=$conf['order'];
  if(isset( $_REQUEST['od']))
    $order_dir=$_REQUEST['od'];
  else
    $order_dir=$conf['order_dir'];
    if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];
if(isset( $_REQUEST['where']))
     $where=$_REQUEST['where'];
   else
     $where=$conf['where'];

  
   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;
 $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
  $_SESSION['state']['customers']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
   $filter_msg='';
  $wheref='';

  if(($f_field=='name'     )  and $f_value!=''){
      $wheref="  and  cu.name like '".addslashes($f_value)."%'";
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






   $sql="select count(*) as total from `Customer Dimension`  $where $wheref";
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   if($row=$res->fetchRow()) {
     $total=$row['total'];
   }if($wheref!=''){
     $sql="select count(*) as total_without_filters from customer `Customer Dimension`  $where ";
     $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
     if($row=$res->fetchRow()) {
       $total_records=$row['total_without_filters'];
       $filtered=$row['total_without_filters']-$total;
     }

   }else{
     $filtered=0;
     $filter_total=0;
     $total_records=$total;
   }

   $rtext=$total_records." ".ngettext('identified customers','identified customers',$total_records);
   if($total_records>$number_results)
     $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));

   if($total==0 and $filtered>0){
     switch($f_field){
     case('name'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer starting with")." <b>$f_value</b> ";
       break;
     }
   }
   elseif($filtered>0){
     switch($f_field){
     case('name'):
       $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('customers with name')." <b>".$f_value."*</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;
     }
   }else
      $filter_msg='';
   




   $_order=$order;
   $_dir=$order_direction;
   // if($order=='location'){
//      if($order_direction=='desc')
//        $order='country_code desc ,town desc';
//      else
//        $order='country_code,town';
//      $order_direction='';
//    }

//     if($order=='total'){
//       $order='supertotal';
//    }
    

   if($order=='name')
     $order='customer file as';
   elseif($order=='id')
     $order='customer id';
   elseif($order=='location')
     $order='customer main location';
   elseif($order=='orders')
     $order='customer orders';
   elseif($order=='email')
     $order='customer email';
   elseif($order=='telephone')
     $order='customer main telehone';
    elseif($order=='last_order')
     $order='customer last order date';
     elseif($order=='total_payments')
     $order='customer total payments';

   $sql="select   * from `Customer Dimension`  $where $wheref  order by `$order` $order_direction limit $start_from,$number_results";
   //      print $sql;
   
  $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}

  $adata=array();
  
  while($data=$res->fetchRow()) {

    $id="<a href='customer.php?id=".$data['customer id']."'>".$myconf['customer_id_prefix'].sprintf("%05d",$data['customer id']).'</a>';
  
    
    
   //  if($data['factor_num_orders_nd']>.60)
//       $color='bbb';
//     elseif($data['factor_num_orders_nd']>.40)
//       $color='888';
//     elseif($data['factor_num_orders_nd']>.20)
//       $color='444';
//     else
//       $color='000';

//     if($data['factor_num_orders_nd']<.05)
//       $old_orders='';
//     else{
//       $orders_with_no_data=number($data['num_orders_nd']);
//       $old_orders='<span title="'.$orders_with_no_data.' '.ngettext('order','orders',$orders_with_no_data).' '._('with no data available.').'" style="cuersor:pointer;position: relative;top: -0.3em;color:#'.$color.';font-size: 0.8em;">*</span>';
//     }

//     if($data['num_invoices']==0){
//       $color='bbb';
//       $super_total='<i  style="color:#'.$color.'">'._('ND').'</i>';
//       $orders_with_no_data=number($data['num_orders_nd']);
//       $old_orders='<span title="'.$orders_with_no_data.' '.ngettext('order','orders',$orders_with_no_data).' '._('with no data available.').'" style="cuersor:pointer;position: relative;top: -0.3em;color:#'.$color.';font-size: 0.8em;">*</span>';
//     }else
//       $super_total='<i  style="color:#'.$color.'">'.money($data['super_total']).'</i>';
//     $orders=$old_orders.'<i  style="color:#'.$color.'">'.number($data['orders']).'</i>';
//     if($data['is_staff']>0)
//       $location='<span style="color:#999">('._('ex').')</span>'._('Staff');
//     else
//       $location='<img title="'.$data['country_name'].'"  src="art/flags/'.strtolower($data['country_code2']).'.gif" alt="'.$data['country_code'].'"> '.$data['town'].' '.preg_replace('/\s/','',$data['postcode']);

//      $email='';
//      if($data['email']!='')
//        $email='<a href="emailto:'.$data['email'].'"  >'.$data['email'].'</a>';
//      $tel='';
//      if($data['number']!='')
//        $tel=($data['icode']!=''?'+'.$data['icode'].' ':'').$data['number'];
    $adata[]=array(
		   'id'=>$id,
		   'name'=>$data['customer name'],
		   'location'=>$data['customer main location'],
		   'orders'=>$data['customer orders'],
		   'email'=>$data['customer main xhtml email'],
		   'telephone'=>$data['customer main telephone'],
		   'last_order'=>strftime("%e %b %Y", strtotime($data['customer last order date'])),
		   'total_payments'=>$data['customer total payments']
      
		   );
  }




  $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$adata,
			 'rtext'=>$rtext,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$res->numRows(),
			 'records_perpage'=>$number_results,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
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
    if(!$LU->checkRight(ORDER_VIEW))
    exit;

    $conf=$_SESSION['state']['customer']['table'];

    if(isset( $_REQUEST['id']))
      $customer_id=$_REQUEST['id'];
    else
      $customer_id=$_SESSION['state']['customer']['id'];
    

    if(isset( $_REQUEST['sf']))
      $start_from=$_REQUEST['sf'];
    else
      $start_from=$conf['sf'];
    
    if(isset( $_REQUEST['nr']))
      $number_results=$_REQUEST['nr'];
    else
      $number_results=$conf['nr'];
    if(isset( $_REQUEST['o']))
      $order=$_REQUEST['o'];
    else
      $order=$conf['order'];
    if(isset( $_REQUEST['od']))
    $order_dir=$_REQUEST['od'];
  else
    $order_dir=$conf['order_dir'];

    if(isset( $_REQUEST['details']))
      $details=$_REQUEST['details'];
    else
      $details=$conf['details'];
    

    if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];

if(isset( $_REQUEST['where']))
     $where=$_REQUEST['where'];
   else
     $where=$conf['where'];
  
 if(isset( $_REQUEST['from']))
    $from=$_REQUEST['from'];
  else
    $from=$conf['from'];
  if(isset( $_REQUEST['to']))
    $to=$_REQUEST['to'];
  else
    $to=$conf['to'];

  $elements=$conf['elements'];
  if(isset( $_REQUEST['element_orden']))
    $elements['orden']=$_REQUEST['e_orden'];
  if(isset( $_REQUEST['element_h_cust']))
    $elements['h_cust']=$_REQUEST['e_orden'];
  if(isset( $_REQUEST['element_h_cont']))
    $elements['h_cont']=$_REQUEST['e_orden'];
  if(isset( $_REQUEST['element_note']))
    $elements['note']=$_REQUEST['e_orden'];
  

   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;




   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   $_SESSION['state']['customer']['id']=$customer_id;
   $_SESSION['state']['customer']['table']=array('details'=>$details,'elements'=>$elements,'order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
   $date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
   if($date_interval['error']){
      $date_interval=prepare_mysql_dates($_SESSION['state']['customer']['table']['from'],$_SESSION['state']['customer']['table']['to']);
   }else{
     $_SESSION['state']['customer']['table']['from']=$date_interval['from'];
     $_SESSION['state']['customer']['table']['to']=$date_interval['to'];
   }

   $where.=sprintf(' and  sujeto="CUST" and  sujeto_id=%d',$customer_id);
   if(!$details)
     $where.=" and display!='details'";
   foreach($elements as $element=>$value){
     if(!$value ){
       $where.=sprintf(" and objeto!=%s ",prepare_mysql($element));
     }
   }
   
   $where.=$date_interval['mysql'];
   
   $wheref='';



   if( $f_field=='notes' and $f_value!='' )
     $wheref.=" and   note like '%".addslashes($f_value)."%'   ";
   if($f_field=='upto' and is_numeric($f_value) )
     $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date))<=".$f_value."    ";
   else if($f_field=='older' and is_numeric($f_value))
     $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date))>=".$f_value."    ";
   elseif($f_field=='author' and $f_value!=''){
       if(is_numeric($f_value))
	 $wheref.=" and   staff_id=$f_value   ";
       else{
	 $wheref.=" and  handle like='".addslashes($f_value)."%'   ";
       }
     }
	  
   

   
   
       

   


   
   $sql="select count(*) as total from  history   left join liveuser_users on (staff_id=authuserid) $where $wheref ";
  // print $sql;
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   if($row=$res->fetchRow()) {
     $total=$row['total'];
   }
   if($where==''){
     $filtered=0;
     $filter_total=0;
     $total_records=$total;
   }else{
     
     $sql="select count(*) as total from history   $where";
    // print $sql;
     $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
     if($row=$res->fetchRow()) {
	$filtered=$row['total']-$total;
	$total_records=$row['total'];
     }
     
   }
   
   
   $rtext=$total_records." ".ngettext('record','records',$total_records);
   
   if($total==0)
     $rtext_rpp='';
   elseif($total_records>$number_results)
     $rtext_rpp=sprintf('(%d%s)',$number_results,_('rpp'));
   else
     $rtext_rpp=_('Showing all');


//   print "$f_value $filtered  $total_records  $filter_total";
   $filter_msg='';
   if($filtered>0){
   switch($f_field){
     case('notes'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any record matching")." <b>$f_value</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total ".ngettext($total,'record matching','records matching')." <b>$f_value</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;
  case('older'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any record older than")." <b>$f_value</b> ".ngettext($f_value,'day','days');
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total ".ngettext($total,'record older than','records older than')." <b>$f_value</b> ".ngettext($f_value,'day','days')." <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;
     case('upto'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any record in the last")." <b>$f_value</b> ".ngettext($f_value,'day','days');
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total ".ngettext($total,'record in the last','records inthe last')." <b>$f_value</b> ".ngettext($f_value,'day','days')."<span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;  


   }
   }


   
   $_order=$order;
   $_dir=$order_direction;
   if($order=='op'){
     $order="op $order_direction, date_index desc ";
     $order_direction='';

   }


   $sql="select handle,objeto,staff_id,history.id,note,UNIX_TIMESTAMP(date) as udate from history  left join liveuser_users on (staff_id=authuserid)   $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
   //print $sql;
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   $data=array();
   while($row=$res->fetchRow()) {
     
     if($row['objeto']=='NOTE'){
       $tipo=_('Note');
     }elseif($row['objeto']=='ORDER'){
       $tipo=_('Order');
     }elseif($row['objeto']=='ATTACH'){
       $tipo=_('Attachment');
     }elseif(preg_match('/^CHG/',$row['objeto'])){
       $tipo=_('Change');
     }elseif(preg_match('/^NEW/',$row['objeto'])){
       $tipo=_('New');
     }else{
       $tipo=_('Other');
     }
     
     if($row['staff_id']>0)
       $author='<a href="user.php?id='.$row['staff_id'].'">'.$row['handle'].'</a>';
     else
       $author=_('System');
     $data[]=array(
		   'id'=>$row['id'],
		   'date'=>strftime("%a %e %b %Y", strtotime('@'.$row['udate'])),
		   'time'=>strftime("%H:%M", strtotime('@'.$row['udate'])),
		   'objeto'=>$tipo,
		   'note'=>$row['note'],
		   'handle'=>$author
		   );
   }
   
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
			 'total_records'=>$total,
			 'records_offset'=>$start_from,
			 'records_returned'=>$start_from+$res->numRows(),
			 'records_perpage'=>$number_results,
			 'rtext'=>$rtext,
			 'rtext_rpp'=>$rtext_rpp,
			 'records_order'=>$order,
			 'records_order_dir'=>$order_dir,
			 'filtered'=>$filtered
			 )
		   );
   echo json_encode($response);
   break;

case('customer_history_old'):
    if(!$LU->checkRight(ORDER_VIEW))
    exit;

    $conf=$_SESSION['state']['customer']['table'];

    if(isset( $_REQUEST['id']))
      $customer_id=$_REQUEST['id'];
    else
      $customer_id=$_SESSION['state']['customer']['id'];
    

    if(isset( $_REQUEST['sf']))
      $start_from=$_REQUEST['sf'];
    else
      $start_from=$conf['sf'];
    
    if(isset( $_REQUEST['nr']))
      $number_results=$_REQUEST['nr'];
    else
      $number_results=$conf['nr'];
    if(isset( $_REQUEST['o']))
      $order=$_REQUEST['o'];
    else
      $order=$conf['order'];
    if(isset( $_REQUEST['od']))
    $order_dir=$_REQUEST['od'];
  else
    $order_dir=$conf['order_dir'];
    if(isset( $_REQUEST['f_field']))
     $f_field=$_REQUEST['f_field'];
   else
     $f_field=$conf['f_field'];

  if(isset( $_REQUEST['f_value']))
     $f_value=$_REQUEST['f_value'];
   else
     $f_value=$conf['f_value'];
if(isset( $_REQUEST['where']))
     $where=$_REQUEST['where'];
   else
     $where=$conf['where'];
  
 if(isset( $_REQUEST['from']))
    $from=$_REQUEST['from'];
  else
    $from=$conf['from'];
  if(isset( $_REQUEST['to']))
    $to=$_REQUEST['to'];
  else
    $to=$conf['to'];

  $elements=$conf['elements'];
  if(isset( $_REQUEST['element_orden']))
    $elements['orden']=$_REQUEST['e_orden'];
  if(isset( $_REQUEST['element_h_cust']))
    $elements['h_cust']=$_REQUEST['e_orden'];
  if(isset( $_REQUEST['element_h_cont']))
    $elements['h_cont']=$_REQUEST['e_orden'];
  if(isset( $_REQUEST['element_note']))
    $elements['note']=$_REQUEST['e_orden'];
  

   if(isset( $_REQUEST['tableid']))
    $tableid=$_REQUEST['tableid'];
  else
    $tableid=0;




   $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
   $_SESSION['state']['customer']['id']=$customer_id;
   $_SESSION['state']['customer']['table']=array('elements'=>$elements,'order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
   $date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
   if($date_interval['error']){
      $date_interval=prepare_mysql_dates($_SESSION['state']['customer']['table']['from'],$_SESSION['state']['customer']['table']['to']);
   }else{
     $_SESSION['state']['customer']['table']['from']=$date_interval['from'];
     $_SESSION['state']['customer']['table']['to']=$date_interval['to'];
   }

   $where.=sprintf(' and customer_id=%d',$customer_id);

   foreach($elements as $element=>$value){
     if(!$value ){
       $where.=sprintf(" and op!=%s ",prepare_mysql($element));
     }
   }
   
   $where.=$date_interval['mysql'];
   
   $wheref='';

    // if( ($f_field=='public_id'   or  $f_field=='customer_name')  and $f_value=!'' )
   //   $wheref.=" and   $f_field like '".addslashes($f_value)."%'   ";
   if($f_field=='max' and is_numeric($f_value) )
     $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))<=".$f_value."    ";
   else if($f_field=='min' and is_numeric($f_value) )
     $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))>=".$f_value."    ";
   elseif(($f_field=='customer_name' or $f_field=='public_id') and $f_value!='')
     $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";
  else if($f_field=='maxvalue' and is_numeric($f_value) )
    $wheref.=" and  total<=".$f_value."    ";
  else if($f_field=='min' and is_numeric($f_value) )
    $wheref.=" and  total>=".$f_value."    ";
   


   


   
   $sql="select count(*) as total from customer_history   $where $wheref ";

   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  if($row=$res->fetchRow()) {
    $total=$row['total'];
  }
  if($where==''){
    $filtered=0;
  }else{
    
      $sql="select count(*) as total from customer_history  $where";
      $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
      if($row=$res->fetchRow()) {
	$filtered=$row['total']-$total;
      }
      
  }
  
  
 $filter_msg='';

     switch($f_field){
     case('public_id'):
       if($total==0 and $filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order starting with")." <b>$f_value</b> ";
       elseif($filtered>0)
	 $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('only orders starting with')." <b>$f_value</b> <span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
       break;
     }



   
   $_order=$order;
   $_dir=$order_direction;
   if($order=='op'){
     $order="op $order_direction, date_index desc ";
     $order_direction='';

   }


   $sql="select id,op,op_id,date_index,UNIX_TIMESTAMP(date_index) as udate, if(op='orden', (select concat_ws('|',id,tipo,public_id,net) from orden where id=op_id) , if(op='note',(select concat_ws('|',id,texto,author_id) from note where     note.id=customer_history.op_id    ), (select concat_ws('|',tipo,sujeto,sujeto_id,objeto,objeto_id,staff_id,old_value,new_value) from history   where history.id=op_id limit 1)   )   ) as description from customer_history  $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
     // print "$sql";
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   $data=array();

   $_tipo=array('note'=>_('Note'),'h_cust'=>_('Change'),'h_cont'=>_('Change'),'orden'=>'Order');
   while($row=$res->fetchRow()) {
     
     $description='';
     $_desc=preg_split('/\|/',$row['description']);
     switch($row['op']){
     case('note'):
       $description=$_desc[1];
       break;
     case('orden'):
       $description=$_order_tipo[$_desc[1]].' <b><a href="order.php?id='.$_desc[0].'">'.$_desc[2]."</a></b>";
       break;
     case('h_cust'):
       $tipo=$_desc[0];
       $objeto=$_desc[3];
         switch($tipo){
	 case('NEW'):
	   switch($objeto){
	   case('Delivery Address'):
	     $description.=' '._('New delivery address');
	   default:
	     $description=$row['description'];
	   }
	 }
     case('h_cont'):
       $tipo=$_desc[0];
       $objeto=$_desc[3]; 
       $description=$row['description'];
       switch($tipo){
       case('NEW'):
	  switch($objeto){
	  case('Work Email'):
	    $description=_('New email').': '.$_desc[5];
	    break;
	  case('Shop Address'):
	    $description=_('New shop address').':<br> '.$_desc[5];
	    break;
	  }
       }
     }

    

     
     $data[]=array(
		   'id'=>$row['id'],
		   'date_index'=>strftime("%a %e %b %Y", strtotime('@'.$row['udate'])),
		   'time'=>strftime("%H:%M", strtotime('@'.$row['udate'])),
		   'op'=>$_tipo[$row['op']],
		   'description'=>$description
		   );
   }
   if($total==0){
     $rtext=_('No order has been placed yet').'.';
   }elseif($total<$number_results)
     $rtext=$total.' '.ngettext('record returned','records returned',$total);
   else
     $rtext='';
   $response=array('resultset'=>
		   array('state'=>200,
			 'data'=>$data,
			 'sort_key'=>$_order,
			 'sort_dir'=>$_dir,
			 'tableid'=>$tableid,
			 'filter_msg'=>$filter_msg,
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

 case('customer_history_todelete'):

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