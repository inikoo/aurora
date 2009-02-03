<?
include_once('Family.php');

class department{
var $db;
 function __construct($a1=false,$a2=false) {
    $this->db =MDB2::singleton();
    
    if(is_numeric($a1) and !$a2  )
      $this->getdata('id',$a1);
    else if( preg_match('/new|create/i',$a1)){
      $this->create($a2);
    }elseif($a2!='')
      $this->getdata($a1,$a2);
    
 }

 function create($data){

   

   if($data['code']=='' ){
     
     return array('ok'=>false,'msg'=>_("Wrong department code"));
   }
   $sql=sprintf("insert into `Product Department Dimension` (`Product Department Code`,`Product Department Name`) values (%s,%s)"
		,prepare_mysql($data['code'])
		,prepare_mysql($data['name'])
		);
   // print "$sql\n";
     $affected=& $this->db->exec($sql);

   if (PEAR::isError($affected)) {
     if(preg_match('/^MDB2 Error: constraint violation$/',$affected->getMessage()))
       return array('ok'=>false,'msg'=> _('Error: Another product department has the same code').'.');
     else
       return array('ok'=>false,'msg'=>_('Unknown Error').'.');
     return false;
   }
   $this->id = $this->db->lastInsertID();  
   $this->getdata('id',$this->id);

   return array('ok'=>true);
 }
 
 function getdata($tipo,$tag){

   switch($tipo){
   case('id'):
     $sql=sprintf("select *,`Product Department Total Acc Invoiced Gross Amount`+`Product Department Total Acc Invoiced Discount Amount` as `product department total acc invoiced amount` ,`Product Department 1 Month Acc Invoiced Gross Amount`+`Product Department 1 Month Acc Invoiced Discount Amount` as `product department 1 month acc invoiced amount` from `Product Department Dimension` where `Product Department Key`=%d ",$tag);
     break;
   case('code'):
     $sql=sprintf("select *,`Product Department Total Acc Invoiced Gross Amount`+`Product Department Total Acc Invoiced Discount Amount` as `product department total acc invoiced amount` ,`Product Department 1 Month Acc Invoiced Gross Amount`+`Product Department 1 Month Acc Invoiced Discount Amount` as `product department 1 month acc invoiced amount` from `Product Department Dimension` where `Product Department Code`=%s and `Product Department Most Recent`='Yes'",prepare_mysql($tag));
     break;
  //  default:
//      print "error wring tipo $tipo\n";
//      return;
   }
   //  print "$sql\n";
   if($result =& $this->db->query($sql)){
     $this->data=$result->fetchRow();
     $this->id=$this->data['product department key'];
   }
   
 }




 function load($tipo,$args=false){
   switch($tipo){
   case('products_info'):
      $sql=sprintf("select sum(if(`Product Sales State`='Unknown',1,0)) as sale_unknown, sum(if(`Product Sales State`='Discontinued',1,0)) as discontinued,sum(if(`Product Sales State`='Not for sale',1,0)) as not_for_sale,sum(if(`Product Sales State`='For sale',1,0)) as for_sale,sum(if(`Product Sales State`='In Process',1,0)) as in_process,sum(if(`Product Availability State`='Unknown',1,0)) as availability_unknown,sum(if(`Product Availability State`='Optimal',1,0)) as availability_optimal,sum(if(`Product Availability State`='Low',1,0)) as availability_low,sum(if(`Product Availability State`='Critical',1,0)) as availability_critical,sum(if(`Product Availability State`='Out Of Stock',1,0)) as availability_outofstock from `Product Dimension` P left join  `Product Department Bridge` B on (P.`Product Key`=B.`Product Key`) where `Product Department Key`=%d",$this->id);
     //  print $sql;
     $res = $this->db->query($sql);
     if($row = $res->fetchrow()) {

       $sql=sprintf("update `Product Department Dimension` set `Product Department For Sale Products`=%d ,`Product Department Discontinued Products`=%d ,`Product Department Not For Sale Products`=%d ,`Product Department Unknown Sales State Products`=%d, `Product Department Optimal Availability Products`=%d , `Product Department Low Availability Products`=%d ,`Product Department Critical Availability Products`=%d ,`Product Department Out Of Stock Products`=%d,`Product Department Unknown Stock Products`=%d ,`Product Department Surplus Availability Products`=%d where `Product Department Key`=%d  ",
		    $row['for_sale'],
		    $row['discontinued'],
		    $row['not_for_sale'],
		    $row['sale_unknown'],
		    $row['availability_optimal'],
		    $row['availability_low'],
		    $row['availability_critical'],
		    $row['availability_outofstock'],
		    $row['availability_unknown'],
		    $row['availability_surplus'],
		    $this->id
	    );
       //  print "$sql\n";exit;
       $this->db->exec($sql);

    
     }

     $this->getdata('id',$this->id);
     break;
   case('products'):
     $sql=sprintf("select * from `Product Dimension` where `Product Department Key`=%d",$this->id);
     // print $sql;
     $res = $this->db->query($sql);
     $this->products=array();
     while($row = $res->fetchrow()) {
       $this->products[$row['product key']]=$row;
     }
     break;
   case('families'):
     $sql=sprintf("select * from `Product Family Dimension`  where  `Product Family Department Key`=%d",$this->id);
     //  print $sql;
     $res = $this->db->query($sql);
     $this->families=array();
     while($row = $res->fetchrow()) {
       $this->families[$row['family key']]=$row;
     }
     break;
   case('first_date'):

     $first_date=date('U');
     $changed=false;
     $this->load('families');
     foreach($this->families as $family_id=>$family_data){
       $family=new Family($family_id);
       $_date=$family->data['first_date'];
       //   print "$family_id $_date\n";
       if(is_numeric($_date)){
	 if($_date < $first_date){
	   $first_date=$_date;
	   $changed=true;
	 }
       }
     }
     if($changed){
       $this->data['first_date']=$first_date;
     }else
       $this->data['first_date']='';

     // if(preg_match('/save/i',$args))
	 $this->save('first_date');
     
     break;
   case('sales'):
     $this->load('products');

     
     $tsall=0;
     $tsoall=0;
     $tsy=0;
     $tsq=0;
     $tsm=0;
     $tsw=0;
     $tsoy=0;
     $tsoq=0;
     $tsom=0;
     $tsow=0;

     foreach($this->products as $product_id=>$product_data){
       
       $product=new Product($product_id);
       $product->get('sales');
       $tsall+=$product->data['sales']['tsall'];
       $tsy+=$product->data['sales']['tsy'];
       $tsq+=$product->data['sales']['tsq'];
       $tsm+=$product->data['sales']['tsm'];
       $tsw+=$product->data['sales']['tsw'];
       $tsoall+=$product->data['sales']['tsoall'];
       $tsoy+=$product->data['sales']['tsoy'];
       $tsoq+=$product->data['sales']['tsoq'];
       $tsom+=$product->data['sales']['tsom'];
       $tsow+=$product->data['sales']['tsow'];
     }


     $this->data['sales']['tsall']=$tsall;
     $this->data['sales']['tsy']=$tsy;
     $this->data['sales']['tsq']=$tsq;
     $this->data['sales']['tsm']=$tsm;
     $this->data['sales']['tsw']=$tsw;
     $this->data['sales']['tsoall']=$tsoall;
     $this->data['sales']['tsoy']=$tsoy;
     $this->data['sales']['tsoq']=$tsoq;
     $this->data['sales']['tsom']=$tsom;
     $this->data['sales']['tsow']=$tsow;


     $weeks=$this->get('weeks');
     if($weeks>0){
       $this->data['sales']['awtsall']=$this->data['sales']['tsall']/$weeks;
       $this->data['sales']['awtsoall']=$this->data['sales']['tsall']/$weeks;
       
       $date1=date("d-m-Y",strtotime("now -1 year"));$day1=date('N')-1;$date2=date('d-m-Y');$days=datediff('d',$date1,$date2);
       $weeks=number_weeks($days,$day1);
       $this->data['sales']['awtsy']=$tsy/$weeks;
       $this->data['sales']['awtsoy']=$tsoy/$weeks;
       $date1=date("d-m-Y",strtotime("now -3 month"));$day1=date('N')-1;$date2=date('d-m-Y');$days=datediff('d',$date1,$date2);
       $weeks=number_weeks($days,$day1);
       $this->data['sales']['awtsq']=$tsq/$weeks;
       $this->data['sales']['awtsoq']=$tsoq/$weeks;
       $date1=date("d-m-Y",strtotime("now -1 month"));$day1=date('N')-1;$date2=date('d-m-Y');$days=datediff('d',$date1,$date2);
       $weeks=number_weeks($days,$day1);
       $this->data['sales']['awtsm']=$tsm/$weeks;
       $this->data['sales']['awtsom']=$tsom/$weeks;


     }else{
       $this->data['sales']['awtsall']='';
       $this->data['sales']['awtosall']='';
       $this->data['sales']['awtsy']='';
       $this->data['sales']['awtsoy']='';
       $this->data['sales']['awtsq']='';
       $this->data['sales']['awtsoq']='';
       $this->data['sales']['awtsm']='';
       $this->data['sales']['awtsom']='';
     }

     if(preg_match('/save/',$args))
        $this->save($tipo);
     break;
   

   }
   
 }

 function save($tipo){
   switch($tipo){
   case('first_date'):

     if(is_numeric($this->data['first_date'])){
       $sql=sprintf("update product_department set first_date=%s where id=%d",
		    prepare_mysql(
				  date("Y-m-d H:i:s",strtotime('@'.$this->data['first_date'])))
		    ,$this->id);
     }else
       $sql=sprintf("update product_group set first_date=NULL where id=%d",$this->id);
     
     //     print "$sql;\n";
     mysql_query($sql);
     
     break;
   case('sales'):
       $sql=sprintf("select id from sales where tipo='dept' and tipo_id=%d",$this->id);
      $res = $this->db->query($sql); 
      if ($row=$res->fetchRow()) {
	$sales_id=$row['id'];
      }else{
	$sql=sprintf("insert into sales (tipo,tipo_id) values ('dept',%d)",$this->id);
	$this->db->exec($sql);
	$sales_id=$this->db->lastInsertID();
	
      }
      foreach($this->data['sales'] as $key=>$value){
	if(preg_match('/^aw/',$key)){
	  if(is_numeric($value))
	    $sql=sprintf("update sales set %s=%f where id=%d",$key,$value,$sales_id);
	  else
	    $sql=sprintf("update sales set %s=NULL where id=%d",$key,$sales_id);
	  $this->db->exec($sql);

	}
	if(preg_match('/^ts/',$key)){
	  $sql=sprintf("update sales set %s=%.2f where id=%d",$key,$value,$sales_id);
	  // print "$sql\n";
	  $this->db->exec($sql);
	}  
	
      }


     break;
   }
   
 }


 function get($tipo){
   $key=strtolower($tipo);
    if(isset($this->data[$key]))
      return $this->data[$key];


   switch($tipo){

   case('weeks'):
     $_diff_seconds=date('U')-$this->data['first_date'];
     $day_diff=$_diff_seconds/24/3600;
     $weeks=$day_diff/7;
     return $weeks;
   }

 }
 
function add_product($product_id,$args=false){


   $product=New Product($product_id);
   if($product->id){
     $sql=sprintf("insert into `Product Department Bridge` (`Product Key`,`Product Department Key`) values (%d,%d)",$product->id,$this->id);
     $this->db->exec($sql);
     $this->load('products_info');

    //  $sql=sprintf("select sum(if(`Product Sales State`='Unknown',1,0)) as sale_unknown, sum(if(`Product Sales State`='Discontinued',1,0)) as discontinued,sum(if(`Product Sales State`='Not for sale',1,0)) as not_for_sale,sum(if(`Product Sales State`='For sale',1,0)) as for_sale,sum(if(`Product Sales State`='In Process',1,0)) as in_process,sum(if(`Product Availability State`='Unknown',1,0)) as availability_unknown,sum(if(`Product Availability State`='Optimal',1,0)) as availability_optimal,sum(if(`Product Availability State`='Low',1,0)) as availability_low,sum(if(`Product Availability State`='Critical',1,0)) as availability_critical,sum(if(`Product Availability State`='Out Of Stock',1,0)) as availability_outofstock from `Product Dimension` P left join  `Product Department Bridge` B on (P.`Product Key`=B.`Product Key`) where `Product Department Key`=%d",$this->id);
//      //  print $sql;
//      $res = $this->db->query($sql);
//      if($row = $res->fetchrow()) {

//        $sql=sprintf("update `Product Department Dimension` set `Product Department For Sale Products`=%d ,`Product Department Discontinued Products`=%d ,`Product Department Not For Sale Products`=%d ,`Product Department Unknown Sales State Products`=%d, `Product Department Optimal Availability Products`=%d , `Product Department Low Availability Products`=%d ,`Product Department Critical Availability Products`=%d ,`Product Department Out Of Stock Products`=%d,`Product Department Unknown Stock Products`=%d ,`Product Department Surplus Availability Products`=%d where `Product Department Key`=%d  ",
// 		    $row['for_sale'],
// 		    $row['discontinued'],
// 		    $row['not_for_sale'],
// 		    $row['sale_unknown'],
// 		    $row['availability_optimal'],
// 		    $row['availability_low'],
// 		    $row['availability_critical'],
// 		    $row['availability_outofstock'],
// 		    $row['availability_unknown'],
// 		    $row['availability_surplus'],
// 		    $this->id
// 	    );
//        //  print "$sql\n";exit;
//        $this->db->exec($sql);

    
//      }


     
     if(preg_match('/principal/',$args)){
       $sql=sprintf("update  `Product Dimension` set `Product Main Department Key`=%d ,`Product Main Department Code`=%s,`Product Main Department Name`=%s where `Product Key`=%s    "
		    ,$this->id
		    ,prepare_mysql($this->get('product department code'))
		    ,prepare_mysql($this->get('product department name'))
		    ,$product->id);

       $this->db->exec($sql);
     }
   }
 }

function add_family($family_id,$args=false){
   $family=New Family($family_id);
   if($family->id){
     $sql=sprintf("insert into `Product Family Department Bridge` (`Product Family Key`,`Product Department Key`) values (%d,%d)",$family->id,$this->id);
     $this->db->exec($sql);


     $sql=sprintf("select count(*) as num from `Product Family Department Bridge`  where `Product Department Key`=%d",$this->id);
     $res = $this->db->query($sql);
     if($row = $res->fetchrow()) {
       $sql=sprintf("update `Product Department Dimension` set `Product Department Families`=%d   where `Product Department Key`=%d  ",
		    $row['num'],
		    $this->id
		    );
       //  print "$sql\n";exit;
       $this->db->exec($sql);
     }
     if(!preg_match('/noproduct/i',$args) ){
       foreach($family->get('products') as $key => $value){
	 $this->add_product($key,$args);
       }
     }
   

     if(preg_match('/principal/',$args)){
       $sql=sprintf("update  `Product Family Dimension` set `Product Family Main Department Key`=%d ,`Product Family Main Department Code`=%s,`Product Family Main Department Name`=%s where `Product Family Key`=%s    "
		    ,$this->id
		    ,prepare_mysql($this->get('product department code'))
		    ,prepare_mysql($this->get('product department name'))
		    ,$family->id);
       $this->db->exec($sql);
     }
   }
 }


}

?>