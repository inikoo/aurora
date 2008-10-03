<?
include_once('common.php');
$js_code='';

if(!isset($_REQUEST['id']) and is_numeric($_REQUEST['id']))
  $supplier_id=1;
else
  $supplier_id=$_REQUEST['id'];


$_SESSION['tables']['product_withsupplier'][4]=$supplier_id;

$sql=sprintf("select s.id as id,code as code, s.name as name,s.outofstock,s.lowstock,s.contact_id ,c.name as company


from supplier as s left join contact as c on (contact_id=c.id)
where s.id=%d ",$supplier_id);
//print "$sql";
$result =& $db->query($sql);
if(!$supplier=$result->fetchRow())
  exit;

$contact_id=$supplier['contact_id'];


$smarty->assign('company','<a href="contact.php?id='.$contact_id.'">'.$supplier['company'].'</a>');
$smarty->assign('v_company',$supplier['company']);


$tels=array();
$sql=sprintf("select telecom.id,tipo,description as name,icode as code,number,ext from telecom left join telecom2contact on (telecom.id=telecom_id) where ( tipo=0) and contact_id=".$contact_id);

$resultt = $db->query($sql);
$js_code='var tel_ids=[';$i=0;
while($row=$resultt->fetchRow()){
  $tels[$row['id']]=array('tel'=>($row['name']!=''?$row['name'].' ':'').($row['code']!=''?'+'.$row['code'].' ':'').$row['number'].($row['ext']!=''?' '._('Ext').'. '.$row['ext']:''),'name'=>$row['name'],'code'=>$row['code'],'number'=>$row['number'],'ext'=>$row['ext']);
  $js_code.=($i++>0?',':'')."'".$row['id']."'";

 }
$js_code.='];';
$num_tels=count($tels);
$smarty->assign('tels',$tels);
$smarty->assign('num_tels',$num_tels);

$faxes=array();
$sql=sprintf("select telecom.id,tipo,description as name,icode as code,number,ext from telecom left join telecom2contact on (telecom.id=telecom_id) where ( tipo=4) and contact_id=".$contact_id);
$resultt = $db->query($sql);
$js_code.='var fax_ids=[';$i=0;
while($row=$resultt->fetchRow()){
  $faxes[$row['id']]=array('fax'=>($row['name']!=''?$row['name'].' ':'').($row['code']!=''?'+'.$row['code'].' ':'').$row['number'],'name'=>$row['name'],'code'=>$row['code'],'number'=>$row['number']);
   $js_code.=($i++>0?',':'')."'".$row['id']."'";
 }
$js_code.='];';
$num_faxes=count($faxes);
$smarty->assign('faxes',$faxes);
$smarty->assign('num_faxes',$num_faxes);

$addresses=array();
$sql=sprintf("select id,description,address1,address2,address3,town,postcode,country_id,full_address,principal  from address where contact_id=".$contact_id);
$resultt = $db->query($sql);
$js_code.='var address_ids=[';$i=0;
while($row=$resultt->fetchRow()){
  $addresses[$row['id']]=array(
		  'address'=>($row['description']!=''?'('.$row['description'].')<br/>':'').$row['full_address']
		  ,'description'=>$row['description']
		  ,'a1'=>$row['address1']
		  ,'a2'=>$row['address2']
		  ,'a3'=>$row['address3']
		  ,'town'=>$row['town']
		  ,'pc'=>$row['postcode']
		  ,'country_id'=>$row['country_id']
		  ,'principal'=>$row['principal']
		     );
   $js_code.=($i++>0?',':'')."'".$row['id']."'";
 }
$js_code.='];';
$num_addresses=count($addresses);
$smarty->assign('addresses',$addresses);
$smarty->assign('num_addresses',$num_addresses);
$smarty->assign('default_country_id',$myconf['country_id']);

$emails=array();
$sql=sprintf("select id,contact,email from email where contact_id=".$contact_id);

$resultt = $db->query($sql);
$js_code.='var email_ids=[';$i=0;
while($row=$resultt->fetchRow()){
  $emails[$row['id']]=array(
		  'email'=>($row['contact']!=''?'('.$row['contact'].') ':'').'<a href="mailto:'.$row['email'].'">'.$row['email'].'</a>'
		  ,'contact'=>$row['contact']
		  ,'address'=>$row['email']);
   $js_code.=($i++>0?',':'')."'".$row['id']."'";
 }
$js_code.='];';
$num_emails=count($emails);

$smarty->assign('emails',$emails);
$smarty->assign('num_emails',$num_emails);



$wwws=array();
$sql=sprintf("select id,title,www from www where contact_id=".$contact_id);
$resultt = $db->query($sql);
$js_code.='var www_ids=[';$i=0;
while($row=$resultt->fetchRow()){
  $wwws[$row['id']]=array(
		  'www'=>($row['title']!=''?'('.$row['title'].') ':'').'<a href="'.$row['www'].'">'.$row['www'].'</a>'
		  ,'title'=>$row['title']
		  ,'address'=>$row['www']);
   $js_code.=($i++>0?',':'')."'".$row['id']."'";
 }
$js_code.='];';
$num_wwws=count($wwws);
$smarty->assign('wwws',$wwws);
$smarty->assign('num_wwws',$num_wwws);



$sql=sprintf("select count(*) as number from porden where tipo=0 and supplier_id=".$supplier_id);
$result = $db->query($sql);
if($tmp=$result->fetchRow())
  $supplier['po_todo']=$tmp['number'];
$sql=sprintf("select count(*) as number from porden where tipo=1 and supplier_id=".$supplier_id);
$result = $db->query($sql);
if($tmp=$result->fetchRow())
  $supplier['po_submited']=$tmp['number'];
$sql=sprintf("select count(*) as number from porden where tipo=2 and supplier_id=".$supplier_id);
$result =& $db->query($sql);
if($tmp=$result->fetchRow())
  $supplier['po_received']=$tmp['number'];
$sql=sprintf("select count(*) as number from porden where tipo=3 and supplier_id=".$supplier_id);
$result =& $db->query($sql);
if($tmp=$result->fetchRow())
  $supplier['po_cancelled']=$tmp['number'];
$supplier['pos']=$supplier['po_todo']+$supplier['po_submited']+$supplier['po_received']+$supplier['po_cancelled'];

$sql=sprintf("select count(*) as number from product2supplier where supplier_id=".$supplier_id);
$result =& $db->query($sql);
if($tmp=$result->fetchRow())
  $supplier['products']=$tmp['number'];






$_SESSION['tables']['products_withsupplier'][4]=$supplier_id;
$_SESSION['tables']['dn_list'][4]=$supplier_id;
$_SESSION['tables']['po_list'][4]=$supplier_id;

//$_SESSION['tables']['purchase_orders'][4]=$supplier_id;



$smarty->assign('box_layout','yui-t0');


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.cs',
		 'common.css',
		 'button.css',
		 'container.css',
		 'table.css'
		 );



//$ids=urlencode(serialize(array('tel'=>$tel_ids,'faxes'=>$fax_ids,'emails'=>$email_ids,'wwws'=>$www_ids)));




$js_files=array(
		$yui_path.'yahoo-dom-event/yahoo-dom-event.js',
		$yui_path.'element/element-beta-min.js',
		$yui_path.'utilities/utilities.js',
		$yui_path.'container/container.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'button/button.js',
		$yui_path.'autocomplete/autocomplete.js',
		$yui_path.'datasource/datasource-beta.js',
		$yui_path.'datatable/datatable-beta.js',
		$yui_path.'json/json-min.js',
		'js/common.js.php',
		'js/table_common.js.php',
		'js/supplier.js.php?supplier_id='.$supplier_id.'&contact_id='.$contact_id.'&pos='.$supplier['pos'].'&prods='.$supplier['products']
		);




$smarty->assign('parent','suppliers.php');
$smarty->assign('title','Supplier: '.$supplier['code']);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$supplier['id_name']=sprintf($myconf['supplier_id_prefix']."%05d",$supplier['id']);

$supplier_home=_("Suppliers List");
$smarty->assign('home',$supplier_home);
$smarty->assign('supplier',$supplier);
$smarty->assign('table1_options',array(_('TD'),_('Submited'),_('Recived'),_('Canceled')));
$smarty->assign('table1_options_status',$_SESSION['views']['pos_table_options']);




$smarty->assign('date_now',date("d-m-Y"));
$smarty->assign('time_now',date("H:i"));

$smarty->assign('filter0',$_SESSION['tables']['po_list'][6]);
$smarty->assign('filter_value2',$_SESSION['tables']['po_list'][7]);
switch($_SESSION['tables']['po_list'][6]){
 case('id'):
   $filter_text=_('Id');
   break;
 default:
   $filter_text='?';
 }
$smarty->assign('filter_name0',$filter_text);
$smarty->assign('t_title0',_('Purchase Orders'));

$smarty->assign('filter1',$_SESSION['tables']['dn_list'][6]);
$smarty->assign('filter_value1',$_SESSION['tables']['dn_list'][7]);
switch($_SESSION['tables']['dn_list'][6]){
 case('public_id'):
   $filter_text=_('Inv Number');
   break;
 default:
   $filter_text='?';
 }
$smarty->assign('filter_name1',$filter_text);
$smarty->assign('t_title1',_('Purchase Orders'));


$smarty->assign('filter2',$_SESSION['tables']['product_withsupplier'][6]);
$smarty->assign('filter_value2',$_SESSION['tables']['product_withsupplier'][7]);
switch($_SESSION['tables']['product_withsupplier'][6]){
 case('p.code'):
   $filter_text=_('Our Code');
   break;
 case('sup_code'):
   $filter_text=_('Supplier Code');
   break;
 default:
   $filter_text='?';
 }
$smarty->assign('filter_name2',$filter_text);
$smarty->assign('t_title2',_('Products'));


$smarty->assign('view_block',$_SESSION['views']['supplier_blocks']);





$sql=sprintf("select id from country");
$result =& $db->query($sql);
while($tmp=$result->fetchRow())
  $country[$tmp['id']]=$_country[$tmp['id']];
$smarty->assign('countries',$country);
$_SESSION['tmp']=$js_code;


$smarty->display('supplier.tpl');
?>