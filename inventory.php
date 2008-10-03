<?
include_once('common.php');


if(!isset($_REQUEST['tipo']))
  exit;


$department_name='';
$department_code='';
$family_code='';
$family_description='';


switch($_REQUEST['tipo']){
 case('last'):
   $inventory_id=1;
   $sql="select p.units,p.id,g.name as family,p.code,p.description,(select quantity from in_out where product_id=p.id and  inventory_id=$inventory_id and tipo=2 ) as qty,.6*(price/units) as price,(select date from in_out where product_id=p.id and  inventory_id=$inventory_id and tipo=2 ) as date from product as p left join product_group as g on (group_id=g.id)";
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   $cvs="\n\"Inventory\"\t\"$inventory_id\"\n\"id\"\t\"family\"\t\"code\"\t\"units\"\t\"description\"\t\"Outers\"\t\"price per unit\"\t\"date\"\n";
   while ($row = $res->fetchRow() ) {
     $code=$row['code'];
     $description=$row['description'];
     $family=$row['family'];
     $qty=$row['qty'];
     $date=$row['date'];
     $units=number($row['units']);
     $price=number_format($row['price'],3,'.','');
     $id=$row['id'];
     $cvs.="\"$id\"\t\"$family\"\t\"$code\"\t\"$units\"\t\"$description\"\t\"$qty\"\t\"$price\"\t\"$date\"\n";
   }

      break;
 case('new'):
     $sql="select p.units,p.id,g.name as family,p.code,p.description,.6*(price/units) as price from product as p left join product_group as g on (group_id=g.id)";
   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
   $cvs="\n\"Inventory\"\t\"$inventory_id\"\n\"id\"\t\"family\"\t\"code\"\t\"units\"\t\"description\"\t\"Outers\"\t\"price per unit\"\t\"date\"\n";
   while ($row = $res->fetchRow() ) {
     $code=$row['code'];
     $description=$row['description'];
     $family=$row['family'];

     $units=number($row['units']);
     $price=sprintf("%.3f",$row['price']);
     $id=$row['id'];
     $cvs.="\"$id\"\t\"$family\"\t\"$code\"\t\"$units\"\t\"$description\"\t\"\"\t\"$price\"\t\"\"\n";
   }




 }



//print "$cvs";

// $cvs="\n\"ADDPRODS\"\t\"KAKTUS\"\t\"V1.0\"\n\"DEPT ID\"\t\"DESCRIPTION\"\t\"NAME\"\n\"$department\"\t\"$department_code\"\t\"$department_name\"\n\"FAM ID\"\t\"CODE\"\t\"DESCRIPTION\"\n\"$family\"\t\"$family_code\"\t\"$family_description\"\n\"PROD ID\"\t\"CODE\"\t\"DESCRIPTION\"\t\"TYPE UNITS ID\"\t\"UNITS PER OUTER\"\t\"UNITS PER CARTON\"\t\"PRICE OUTER\"\t\"RETAIL PRICE\"\t\"EXPORT CODE\"\t\"WEIGHT UNIT (KG)\"\t\"WEIGHT OUTER (KG)\" \t\"WEIGHT CARTON (KG)\"\t\"DIM UNITS HxWxD(CM)\"\t\"DIM OUTER HxWxD(CM)\"\t\"DIM CARTON HxWxD(CM)\"\t\"SUPPLIER ID\"\t\"SUPPLIER PROD CODE\"\t\"COST PER UNIT\"\n\"NEWPROD\" ";




header( "Content-type: application/vnd.ms-excel; charset=UTF-16LE" );
header("Content-Disposition: attachment; filename=\"addprods.csv\"");
print  chr(255).chr(254).mb_convert_encoding( $cvs, 'UTF-16LE', 'UTF-8');



?>