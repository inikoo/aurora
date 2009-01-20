<?
include_once('Family.php');

class department{
var $db;
 function __construct($a1=false,$a2=false) {
    $this->db =MDB2::singleton();
    
    if(is_numeric($a1) and !$a2  )
      $this->getdata('id',$a1);
    else if($a1=='new' and is_array($a2)){
      $this->create($a2);
    }elseif($a2!='')
      $this->getdata($a1,$a2);
    
 }

 function create($data){

   

   if($data['department code']=='' ){
     
     return array('ok'=>false,'msg'=>_("Wrong department code"));
   }
//    if($data['name']=='' )
//      return array('ok'=>false,'msg'=>_("Wrong department name"));

//    $sql=sprintf("select id from product_department where code=%s "
// 		,prepare_mysql($data['code'])
// 		);
//    print "$sql\n";
//    $res = $this->db->query($sql); 
//    if($tmp=$res->fetchRow()){
//      return array('ok'=>false,'msg'=>_('There is other product department  with the same code'));
//    }


   $sql=sprintf("insert into `Product Department Dimension` (`Product Department Code`,`Product Department Name`) values (%s,%s)"
		,prepare_mysql($data['department code'])
		,prepare_mysql($data['department name'])
		);
   if (PEAR::isError($affected)) {
     if(preg_match('/^MDB2 Error: constraint violation$/',$affected->getMessage()))
       return array('ok'=>false,'msg'=> _('Error: Another product department has the same code').'.');
     else
       return array('ok'=>false,'msg'=>_('Unknown Error').'.');
     return false;
   }
   $this->id = $this->db->lastInsertID();  
   $this->getdata($this->id);

   return array('ok'=>true);
 }
 
 function getdata($tipo,$tag){

   switch($tipo){
   case('id'):
     $sql=sprintf("select * from `Product Department` where `Product Deparment Key`=%d",$tag);
     break;
   case('code'):
     $sql=sprintf("select * from `Product Department` where `Product Deparment Code`=%s",prepare_mysql($tag));
     break;
  //  default:
//      print "error wring tipo $tipo\n";
//      return;
   }
   //   print "$sql\n";
   if($result =& $this->db->query($sql)){
     $this->data=$result->fetchRow();
     $this->id=$this->data['product department key'];
   }
   
 }




 function load($tipo,$args=false){
   switch($tipo){
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
     $sql=sprintf("select * from `Product Family`  where  `Product Family Department Key`=%d",$this->id);
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
$key=strtolower($key);
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
     if(preg_match('/principal/',$args)){
       $sql=speintf("update  `Product Dimension` set `Product Main Department Key`=%d ,`Product Main Department Code`=%s,`Product Main Department Name`=%s where `Product Key`=%s    "
		    ,$this->id
		    ,$this->get('product department code')
		    ,$this->get('product department name')
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

     foreach($family->get('products') as $key => $value){
       $this->add_product($key,$args);
     }


     if(preg_match('/principal/',$args)){
       $sql=speintf("update  `Product Family Dimension` set `Product Family Main Department Key`=%d ,`Product Family Main Department Code`=%s,`Product Family Main Department Name`=%s where `Product Family Key`=%s    "
		    ,$this->id
		    ,$this->get('product department code')
		    ,$this->get('product department name')
		    ,$family->id);
       $this->db->exec($sql);
     }
   }
 }


}

?>