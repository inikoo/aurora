<?
require_once 'common.php';

if (!$LU or !$LU->isLoggedIn()) {
  $response=array('state'=>402,'resp'=>_('Forbidden'));
  exit;
 }

$filename='empty';
$adata=array();

if(!isset($_REQUEST['tipo']))
  exit;
switch($_REQUEST['tipo']){
 case('cas'):
   //customer_advanced_search
   if(!$LU->checkRight(CUST_VIEW))
     exit;
   $conf=$_SESSION['state']['customers']['advanced_search'];
   $awhere=$conf['where'];

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


 if(($awhere['product_ordered2']=='' and $awhere['product_not_ordered2']=='' and $awhere['product_not_received2']=='') or ($awhere['from2']==0 and $awhere['to2'])){
   $second_fields='';
 }else{

 if($awhere['product_ordered2']!=''){
    if($awhere['product_ordered2']!='ANY'){
      $where_product_ordered2=extract_product_groups($awhere['product_ordered2']);
    }else
      $where_product_ordered2='true';
  }else
    $where_product_ordered2='false';
  
  if($awhere['product_not_ordered2']!=''){
    if($awhere['product_not_ordered2']!='ALL'){
      $where_product_not_ordered2=extract_product_groups($awhere['product_ordered2'],'product.code not like','transaction.product_id not like','product_group.name not like','product_group.id like');
    }else
      $where_product_not_ordered2=false;
  }else
    $here_product_not_ordered2=true;

 if($awhere['product_not_received2']!=''){
    if($awhere['product_not_received2']!='ANY'){
      $where_product_not_received2=extract_product_groups($awhere['product_ordered2'],'(ordered-dispached)>0 and    product.code  like','(ordered-dispached)>0 and  transaction.product_id not like','(ordered-dispached)>0 and  product_group.name not like','(ordered-dispached)>0 and  product_group.id like');
    }else
      $where_product_not_received2=' ((ordered-dispached)>0)  ';
  }else
    $where_product_not_received2='true';
 
 $second_fields="and (".$where_product_ordered2.' and '.$where_product_not_ordered2.' and '.$where_product_not_received2.$date_interval2['mysql'].")";
 }

  $date_interval1=prepare_mysql_dates($awhere['from1'],$awhere['to1'],'date_index','only_dates');
  $date_interval2=prepare_mysql_dates($awhere['from2'],$awhere['to2'],'date_index','only_dates');

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



  $where='where ('.$where_product_ordered1.' and '.$where_product_not_ordered1.' and '.$where_product_not_received1.$date_interval1['mysql'].") $second_fields $geo_base $with_mail $with_tel";
  
  
  


    $sql=" select telecom.number,telecom.icode,telecom.ncode,telecom.ext, postcode,town,list_country.code as country_code,code2 as country_code2,list_country.name as country_name, email ,email.contact as email_contact, UNIX_TIMESTAMP(max(date_index)) as last_order ,count(distinct orden.id) as orders, customer.id,customer.name from customer left join orden on (customer_id=customer.id) left join transaction on (order_id=orden.id) left join product on (product_id=product.id)  left join product_group on (group_id=product_group.id) left join email on (main_email=email.id) left join telecom on (main_tel=telecom.id) left join address on (main_bill_address=address.id) left join list_country on (country=list_country.name)   $where  group by customer_id order by customer.name";
    //  print $sql;
 $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
 $adata=array();
 $adata[]=array(_('Id'),_('Name'),_('Location'),_('Email'),_('Email contact'),_('Telephone'),_('Orders'),_('Last order'));
  while($data=$res->fetchRow()) {
     $id=$myconf['customer_id_prefix'].sprintf("%05d",$data['id']);
     $location=$data['country_name'].($data['town']!=''?', '.$data['town']:'').($data['postcode']!=''?', '.$data['postcode']:'');
     
        $tel='';
     if($data['number']!='')
       $tel=($data['icode']!=''?'+'.$data['icode'].' ':'').$data['number'];


     $adata[]=array(
		   $id,
		   $data['name'],
		   $location,
		   $data['email'],
		   $data['email_contact'],
		   $tel,
		   $data['orders'],
		   strftime("%e %b %Y", strtotime('@'.$data['last_order']))
		     );		   
      
  }
   $filename='cas';
   

   break;
 }

//print_r($adata);

header('Content-type: text/csv');
$filename='hola';
header('Content-Disposition: attachment; filename="'.$filename.'.csv"');
$out = fopen('php://output', 'w');
foreach($adata as $data)
fputcsv($out, $data);
fclose($out);




?>