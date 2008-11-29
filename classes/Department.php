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
    }
    
 }

 function create($data){

 }
 
 function getdata($tipo,$id){
   $sql=sprintf("select * from product_department where id=%d",$id);

   if($result =& $this->db->query($sql)){
     $this->data=$result->fetchRow();
     $this->id=$this->data['id'];
   }
   
 }




 function load($tipo,$args=false){
   switch($tipo){
   case('products'):
     $sql=sprintf("select id,code from product  left join product_group on  (product_group.id=group_id)  where department_id=%d",$this->id);
     //  print $sql;
     $res = $this->db->query($sql);
     $this->products=array();
     while($row = $res->fetchrow()) {
       $this->products[$row['id']]=array('code'=>$row['code']);
     }
     break;
   case('families'):
     $sql=sprintf("select id,name from product_group  department_id=%d",$this->id);
     //  print $sql;
     $res = $this->db->query($sql);
     $this->products=array();
     while($row = $res->fetchrow()) {
       $this->products[$row['id']]=array('code'=>$row['name']);
     }
     break;
   case('first_date'):
     $first_date=date('U');
     $changed=false;
     $this->load('families');
     foreach($this->families as $family_id=>$family_data){
       $family=new Fmaily($family_id);
       $_date=$family->data['first_date'];
       if(is_numeric($_date)){
	 if($_date < $first_date){
	   $first_date=$_date;
	   $changed=true;
	 }
       }
     }
     if($changed){
       $this->data['first_date']=$first_date;
       if(preg_match('/save/i',$args))
	 $this->save($tipo);
     }

     break;
   case('sales_metadata'):
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
       $tsall+=$product->data['tsall'];
       $tsoall+=$product->data['tsoall'];
       if(is_numeric($product->data['tsy']))
	 $tsy+=$product->data['tsy'];
       if(is_numeric($product->data['tsq']))
	 $tsq+=$product->data['tsq'];
       if(is_numeric($product->data['tsm']))
	 $tsm+=$product->data['tsm'];
       if(is_numeric($product->data['tsw']))
	 $tsw+=$product->data['tsw'];
       if(is_numeric($product->data['tsoy']))
	 $tsoy+=$product->data['tsoy'];
       if(is_numeric($product->data['tsoq']))
	 $tsoq+=$product->data['tsoq'];
       if(is_numeric($product->data['tsom']))
	 $tsom+=$product->data['tsom'];
       if(is_numeric($product->data['tsow']))
	 $tsow+=$product->data['tsow'];


     }
     // print $tsm."\n";

     $this->data['tsall']=$tsall;
     $this->data['tsoall']=$tsoall;
     
     $weeks=$this->get('weeks');
     $date_diff=$weeks*7;
     if($weeks>0){
       $this->data['awtsall']=$this->data['tsall']/$weeks;
       $this->data['awtsoall']=$this->data['tsall']/$weeks;
     }else{
       $this->data['awtsall']=0;
       $this->data['awtosall']=0;
     }
     if($date_diff>=365){
       $this->data['tsy']=$tsy;
       $this->data['tsoy']=$tsoy;
       $this->data['awtsy']=$tsy/52.17857142;
       $this->data['awtsoy']=$tsoy/52.17857142;
     }else{
       $this->data['tsy']='';
       $this->data['tsoy']='';
       $this->data['awtsy']='';
       $this->data['awtsoy']='';
     }     
       if($date_diff>=89){
       $this->data['tsq']=$tsq;
       $this->data['tsoq']=$tsoq;
       $this->data['awtsq']=$tsq/13.044642857;
       $this->data['awtsoq']=$tsoq/13.044642857;
     }else{
       $this->data['tsq']='';
       $this->data['tsoq']='';
       $this->data['awtsq']='';
       $this->data['awtsoq']='';
     }     
   if($date_diff>=31){
       $this->data['tsm']=$tsm;
       $this->data['tsom']=$tsom;
       $this->data['awtsm']=$tsm/4.348214286;
       $this->data['awtsom']=$tsom/4.348214286;
     }else{
       $this->data['tsm']='';
       $this->data['tsom']='';
       $this->data['awtsm']='';
       $this->data['awtsom']='';
     }     
 if($date_diff>5){
       $this->data['tsw']=$tsw;
       $this->data['tsow']=$tsow;
     }else{
       $this->data['tsw']='';
       $this->data['tsow']='';

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
     //print "$sql;";
     mysql_query($sql);

     break;
   case('sales_metadata'):

     $sql=sprintf("update product_group set  tsoall=0,tsall=0,tdall=0,awtsoall=NULL, awtsoy=NULL,  awtsoq=NULL, awtsom=NULL, awtsall=NULL, awtsy=NULL, awtsq=NULL,  awtsm=NULL, awtdall=NULL, awtdy=NULL, awtdq=NULL, awtdm=NULL, tsoy=NULL, tsoq=NULL,  tsom=NULL, tsoq=NULL, tsom=NULL, tsow=NULL, tsw=NULL, tsm=NULL, tsq=NULL, tsy=NULL, tdy=NULL, tdq=NULL, tdm=NULL, tdw=NULL  where id=%d",$this->id);

     $sql=sprintf("update product_group set tsall=%.2f,tsoall=%f  where id=%d"
		  ,$this->data['tsall']
		  ,$this->data['tsoall']
		  ,$this->id);
     mysql_query($sql);
     $weeks=$this->get('weeks');
     $date_diff=$weeks*7;

     if($weeks>0){
       $sql=sprintf("update product_group set awtsall=%.2f,awtsoall=%f where id=%d"
		    ,$this->data['awtsall']
		    ,$this->data['awtsoall']
		    ,$this->id);
       mysql_query($sql);
     }
     if($date_diff>=365){
       $sql=sprintf("update product_group set  tsy=%.2f,tsoy=%f,awtsy=%.2f,awtsoy=%f where id=%d"
		    ,$this->data['tsy']
		    ,$this->data['tsoy']
		    ,$this->data['awtsy']
		    ,$this->data['awtsoy']
		    ,$this->id);
       mysql_query($sql);
       //       print "$sql;";
     } 
      if($date_diff>=89){
       $sql=sprintf("update product_group set  tsq=%.2f,tsoq=%f,awtsq=%.2f,awtsoq=%f where id=%d"
		    ,$this->data['tsq']
		    ,$this->data['tsoq']
		    ,$this->data['awtsq']
		    ,$this->data['awtsoq']
		    ,$this->id);
       mysql_query($sql);
     } 
      if($date_diff>=31){
	$sql=sprintf("update product_group set  tsm=%.2f,tsom=%f,awtsm=%.2f,awtsom=%f where id=%d"
		    ,$this->data['tsm']
		    ,$this->data['tsom']
		    ,$this->data['awtsm']
		    ,$this->data['awtsom']
		    ,$this->id);
       mysql_query($sql);
       // print "$sql";
     } 
      
      if($date_diff>5){
	$sql=sprintf("update product_group set  tsw=%.2f,tsow=%f  where id=%d"
		    ,$this->data['tsw']
		    ,$this->data['tsow']
		    ,$this->id);
       mysql_query($sql);
     } 


     break;
   }
   
 }


 function get($tipo){
   switch($tipo){
   case('weeks'):
     $_diff_seconds=date('U')-$this->data['first_date'];
     $day_diff=$_diff_seconds/24/3600;
     $weeks=$day_diff/7;
     return $weeks;
   }

 }
 

}

?>