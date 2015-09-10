<?php
include_once('common.php');


if(!isset($_REQUEST['in']))
  exit;


$department_name='';
$department_code='';
$family_code='';
$family_description='';


switch($_REQUEST['in']){
 case('root'):
   $department='NEWDEPT';
   $family='NEWFAM';
      break;
 case('department'):
   
   if(!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id']))
     exit(_('Error, department id should be a number').'.');
   
   $department_id=$_REQUEST['id'];
   
   $sql=sprintf("select id,code,name from  product_department where id=%d",$department_id);
   $result=mysql_query($sql);
   if(!$data=mysql_fetch_array($result, MYSQL_ASSOC))
     exit(_('Error, family id not found').'.');

   $department=$department_id;
   $family='NEWFAM';
   $department_name=$data['name'];
   $department_code=$data['code'];
   
   break;
   
 case('family'):
   
   if(!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id']))
     exit(_('Error, family id should be a number').'.');
   
   
   $family_id=$_REQUEST['id'];
   $sql=sprintf("select department_id,d.name as department_name,d.code as department_code,g.name,description from product_group as g left join product_department as d on (department_id=d.id) where g.id=%d",$family_id);
   $result=mysql_query($sql);
   if(!$data=mysql_fetch_array($result, MYSQL_ASSOC))
     exit(_('Error, family id not found').'.');
   
   $department=$data['department_id'];
   $family=$family_id;
   $family_code=$data['name'];
   $family_description=$data['description'];
   $department_name=$data['department_name'];
   $department_code=$data['department_code'];
   
   break;
 default:
   $department='NEWDEPT';
   $family='NEWFAM';
  
 }




$cvs="\n\"ADDPRODS\"\t\"KAKTUS\"\t\"V1.0\"\n\"DEPT ID\"\t\"DESCRIPTION\"\t\"NAME\"\n\"$department\"\t\"$department_code\"\t\"$department_name\"\n\"FAM ID\"\t\"CODE\"\t\"DESCRIPTION\"\n\"$family\"\t\"$family_code\"\t\"$family_description\"\n\"PROD ID\"\t\"CODE\"\t\"DESCRIPTION\"\t\"TYPE UNITS ID\"\t\"UNITS PER OUTER\"\t\"UNITS PER CARTON\"\t\"PRICE OUTER\"\t\"RETAIL PRICE\"\t\"EXPORT CODE\"\t\"WEIGHT UNIT (KG)\"\t\"WEIGHT OUTER (KG)\" \t\"WEIGHT CARTON (KG)\"\t\"DIM UNITS HxWxD(CM)\"\t\"DIM OUTER HxWxD(CM)\"\t\"DIM CARTON HxWxD(CM)\"\t\"SUPPLIER ID\"\t\"SUPPLIER PROD CODE\"\t\"COST PER UNIT\"\n\"NEWPROD\" ";
header( "Content-type: application/vnd.ms-excel; charset=UTF-16LE" );
header("Content-Disposition: attachment; filename=\"addprods.csv\"");
print  chr(255).chr(254).mb_convert_encoding( $cvs, 'UTF-16LE', 'UTF-8');



?>