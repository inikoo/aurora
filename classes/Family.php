<?
include_once('Product.php');

class family{
var $db;
 var $products=false;
 var $id=false;

 function __construct($a1=false,$a2=false) {
    $this->db =MDB2::singleton();
    
    if(is_numeric($a1) and !$a2  )
      $this->getdata('id',$a1);
    else if(preg_match('/new|create/',$a1) ){
      $this->msg=$this->create($a2);
    }elseif($a2!='')
       $this->getdata($a1,$a2);
    
 }

 function create($data){
   
   

   $sql=sprintf("insert into `Product Family Dimension` (`Product Family Code`,`Product Family Name`) values (%s,%s)"
		,prepare_mysql($data['code'])
		,prepare_mysql($data['name'])
		);
   
   // print_r($data);
   //print "$sql\n";
   $affected=& $this->db->exec($sql);
   
   if (PEAR::isError($affected)) {
     if(preg_match('/^MDB2 Error: constraint violation$/',$affected->getMessage()))
       return array('ok'=>false,'msg'=> _('Error: Another product family has the same name/description').'.');
     else
       return array('ok'=>false,'msg'=>_('Unknown Error').'.');
     return false;
   }
   $this->id=$this->db->lastInsertID();
   $this->getdata('id',$this->id);
   
   
   // print "***********************".$this->id;
   return array('ok'=>true);
   
 }
 
 function getdata($tipo,$tag){

   switch($tipo){
   case('id'):
     $sql=sprintf("select *  from `Product Family Dimension` where `Product Family Key`=%d ",$tag);
     break;
   case('code'):
     $sql=sprintf("select *  from `Product Family Dimension` where `Product Family Code`=%s and `Product Family Most Recent`='Yes'",prepare_mysql($tag));
     break;
   }
   //   print "S:$tipo $sql\n";
   $result = $this->db->query($sql);
   if($this->data=$result->fetchRow())
     $this->id=$this->data['product family key'];

 }




 function load($tipo,$args=false){
   switch($tipo){
   case('products_data'):
   case('products_info'):
     
  $sql=sprintf("select sum(if(`Product Sales State`='Unknown',1,0)) as sale_unknown, sum(if(`Product Sales State`='Discontinued',1,0)) as discontinued,sum(if(`Product Sales State`='Not for sale',1,0)) as not_for_sale,sum(if(`Product Sales State`='For sale',1,0)) as for_sale,sum(if(`Product Sales State`='In Process',1,0)) as in_process,sum(if(`Product Availability State`='Unknown',1,0)) as availability_unknown,sum(if(`Product Availability State`='Optimal',1,0)) as availability_optimal,sum(if(`Product Availability State`='Low',1,0)) as availability_low,sum(if(`Product Availability State`='Critical',1,0)) as availability_critical,sum(if(`Product Availability State`='Out Of Stock',1,0)) as availability_outofstock from `Product Dimension` where `Product Family Key`=%d",$this->id);
     //  print $sql;
     $res = $this->db->query($sql);
     if($row = $res->fetchrow()) {

       $sql=sprintf("update `Product Family Dimension` set `Product Family For Sale Products`=%d ,`Product Family Discontinued Products`=%d ,`Product Family Not For Sale Products`=%d ,`Product Family Unknown Sales State Products`=%d, `Product Family Optimal Availability Products`=%d , `Product Family Low Availability Products`=%d ,`Product Family Critical Availability Products`=%d ,`Product Family Out Of Stock Products`=%d,`Product Family Unknown Stock Products`=%d ,`Product Family Surplus Availability Products`=%d where `Product Family Key`=%d  ",
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
     $sql=sprintf("select * from `Product Dimension` where `Product Family Key`=%d ",$this->id);
     //    print $sql;
     $res = $this->db->query($sql);
     $this->products=array();
     while($row = $res->fetchrow()) {
       $this->products[$row['product key']]=$row;
     }
     break;
   case('first_date'):
     $first_date=date('U');
     $changed=false;
     $this->load('products');
     foreach($this->products as $product_id=>$product_data){
       $product=new Product($product_id);
       $_date=$product->data['first_date'];
       //   print "$_date\n";
       if(is_numeric($_date)){
	 // print "hola $product_id   $_date   $first_date  \n";
	 if($_date < $first_date){
	   $first_date=$_date;
	   $changed=true;
	 }
       }
     }
     //  print "$first_dat\n";
     if($changed){
       $this->data['first_date']=$first_date;
       if(preg_match('/save/i',$args))
	 $this->save($tipo);
     }

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
     
     $sql=sprintf("update product_group set first_date=%s where id=%d",
		  prepare_mysql(
				date("Y-m-d H:i:s",strtotime('@'.$this->data['first_date'])))
		  ,$this->id);
     //     print "$sql;";
     mysql_query($sql);

     break;
   case('sales'):
     $sql=sprintf("select id from sales where tipo='fam' and tipo_id=%d",$this->id);
      $res = $this->db->query($sql); 
      if ($row=$res->fetchRow()) {
	$sales_id=$row['id'];
      }else{
	$sql=sprintf("insert into sales (tipo,tipo_id) values ('fam',%d)",$this->id);
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
   case('products'):
     if(!$this->products)
       $this->load('products');
     return $this->products;
     
     break;
   case('weeks'):
      if(is_numeric($this->data['first_date'])){
     	$date1=date('d-m-Y',strtotime('@'.$this->data['first_date']));
	$day1=date('N')-1;
	$date2=date('d-m-Y');
	$days=datediff('d',$date1,$date2);
	$weeks=number_weeks($days,$day1);
      }else
	$weeks=0;
      return $weeks;
   }

 }
 
 function add_product($product_id,$args=false){
   
   $product=New Product($product_id);
   if($product->id){
     $sql=sprintf("update  `Product Dimension` set `Product Family Key`=%d ,`Product Family Code`=%s,`Product Family Name`=%s where `Product Key`=%s    "
		  ,$this->id                
		  ,prepare_mysql($this->get('product family code'))
		  ,prepare_mysql($this->get('product family name'))
		  ,$product->id);
     $this->db->exec($sql);
     $this->load('products_info');
     // print "$sql\n";
   }
 }

}

?>