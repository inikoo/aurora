<?


function find_firstsaleday_all(){
 $db =& MDB2::singleton();
  $sql="select  id from product";
  $res=mysql_query($sql);
  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $id=$row['id'];
    find_firstsaleday($id);
  }

}




function find_firstsaleday($id){

  $db =& MDB2::singleton();
 $sql=sprintf("select  date_index from transaction left join orden on (order_id=orden.id) where product_id=%d  and date_index >'2000-01-01'    order by date_index limit 1  ",$id);

  $res=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $date=$row['date_index'];
    $sql=sprintf("update product set first_date='%s' where first_date>'%s'  and id=%d",$date,$date,$id);
    //        print "$sql";
    mysql_query($sql);
  }
}

function fix_todotransaction_all(){
 $db =& MDB2::singleton();
  $sql="select  id from product";
  $res=mysql_query($sql);
  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $id=$row['id'];
    fix_todotransaction($id);
  }

}


function fix_todotransaction($product_id){
   $db =& MDB2::singleton();
 $sql=sprintf("select  code from product where id=%d    ",$product_id);
    $res=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $code=$row['code'];
      
      $sql=sprintf("select * from todo_transaction where code like '%s' ",addslashes($code));
      //  print "$sql";
      $res2 = mysql_query($sql); 
      while ($row2=$res2->fetchRow()) {
	$code=$row['code'];
	
	$ordered=$row2['ordered'];
	$dispached=$row2['ordered']-$row2['reorder']+$row2['bonus'];
	$discount=$row2['discount'];
	$promotion_id=0;
	$charge=number_format($row2['price']*(1-$discount)*($row2['ordered']-$row2['reorder']),2,'.','');
	$order_id=$row2['order_id'];
	
	$sql=sprintf("insert into  transaction  (order_id,product_id,ordered,dispached,discount,promotion_id,charge) values   (%d,%d,'%s','%s','%s','%s','%s')",$order_id,$product_id,$ordered,$dispached,$discount,$promotion_id,$charge);
	//	print "$sql\n";
	$affected=& mysql_query($sql);
	if (!PEAR::isError($affected)) {
	  $sql=sprintf("delete from todo_transaction where id=%d",$row2['id']);
	  //print "$sql\n";
	  mysql_query($sql);
	}





      }



      
    }


}



function set_sales_all(){
 $db =& MDB2::singleton();
  $sql="select  id from product";
  $res=mysql_query($sql);
  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $id=$row['id'];
    set_sales($id);
  }

}

function set_sales($product_id,$update_fam=false){
  $db =& MDB2::singleton();
  if(is_numeric($product_id)){
    $total_sales=0;
    $y_sales=0;
    $q_sales=0;
    $m_sales=0;
    $w_sales=0;



    $sql=sprintf("select  sum(charge) as sales  ,sum(dispached) as outers from  transaction as t left join orden as o on (order_id=o.id) where product_id=%d and o.tipo=2    ",$product_id);
    $res=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $total_sales=number_format($row['sales'],2,'.','');
      $total_outers=number_format($row['outers'],2,'.','');

    }
    
    $sql=sprintf("select  sum(charge) as sales ,sum(dispached) as outers  from  transaction as t left join orden as o on (order_id=o.id) where product_id=%d and o.tipo=2 and DATE_SUB(CURDATE(),INTERVAL 1 YEAR) <= date_index   ",$product_id);
    $res=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $y_sales=number_format($row['sales'],2,'.','');
      $y_outers=number_format($row['outers'],2,'.','');

    }
    
    $sql=sprintf("select  sum(charge) as sales ,sum(dispached) as outers  from  transaction as t left join orden as o on (order_id=o.id) where product_id=%d and o.tipo=2 and DATE_SUB(CURDATE(),INTERVAL 3 MONTH) <= date_index   ",$product_id);
    $res=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $q_sales=number_format($row['sales'],2,'.','');
      $q_outers=number_format($row['outers'],2,'.','');

    }
    
      $sql=sprintf("select  sum(charge) as sales ,sum(dispached) as outers  from  transaction as t left join orden as o on (order_id=o.id) where product_id=%d and o.tipo=2 and DATE_SUB(CURDATE(),INTERVAL 1 MONTH) <= date_index   ",$product_id);
    $res=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $m_sales=number_format($row['sales'],2,'.','');
      $m_outers=number_format($row['outers'],2,'.','');

    }
    
      $sql=sprintf("select  sum(charge) as sales  ,sum(dispached) as outers from  transaction as t left join orden as o on (order_id=o.id) where product_id=%d and o.tipo=2 and DATE_SUB(CURDATE(),INTERVAL 1 WEEK) <= date_index   ",$product_id);
    $res=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $w_sales=number_format($row['sales'],2,'.','');
      $w_outers=number_format($row['outers'],2,'.','');
      

    }
    

    $awsall=0;
    $awtsall=0;

    $sql=sprintf("select   (TO_DAYS(NOW())-TO_DAYS(first_date))  as days from product     where product.id=%d    ",$product_id);
    $res=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      
      $days=$row['days'];
      
      if($days>0){
	$awsall=7*$total_outers/$days;
	$awtsall=7*$total_sales/$days;
	//	print "$awtsall $days ";
      }


    }
    
    
    $awsq=number_format(($q_outers/13.00),2,'.','');
    $awtsq=number_format(($q_sales/13.00),2,'.','');


    $awsall=number_format($awsall,2,'.','');
    $awtsall=number_format($awtsall,2,'.','');


    $sql=sprintf("update product set awoutq=%s , awoutall=%s, outall=%s ,outq=%s ,outm=%s ,outw=%s ,outy=%s, awtsq=%s , awtsall=%s, tsall=%s ,tsq=%s ,tsm=%s ,tsw=%s ,tsy=%s where id=%d",
		 $awsq,$awsall,$total_outers,$q_outers,$m_outers,$w_outers,$y_outers,
		 $awtsq,$awtsall,$total_sales,$q_sales,$m_sales,$w_sales,$y_sales,$product_id);
    //print "$sql\n";
    mysql_query($sql); 
    
    if($update_fam){
    $sql=sprintf("select  group_id from product where id=%d   ",$product_id);

    $res=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      //print "update ".$row['group_id']."\n";
      update_family($row['group_id'],true);
    }
    }

    //    print "$sql";
  }






}


function update_supplier_datos_all(){
 $db =& MDB2::singleton();
  $sql="select  id from supplier";
  $res=mysql_query($sql);
  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $id=$row['id'];
    update_supplier_datos($id);
  }

}


function update_supplier_datos($supplier_id){
 $db =& MDB2::singleton();
  $active=0;
  $outofstock=0;
  $products=0;
  $lowstock=00;
  
  $sql=sprintf("select count(*) as num from product2supplier where supplier_id=%d",$supplier_id);
  $res=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $products=$row['num'];
  }
  
  $sql=sprintf("select count(*) as num   from product left join product2supplier on (product.id=product_id) where stock=0 and product2supplier.supplier_id=%d",$supplier_id);

  $res=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $outofstock=$row['num'];
  }
  
  $sql=sprintf("select count(*) as num   from product left join product2supplier on (product.id=product_id) where condicion=0 and product2supplier.supplier_id=%d",$supplier_id);
  $res=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $active=$row['num'];
  }




  $sql=sprintf("update supplier set products=$products, outofstock=$outofstock, lowstock=$lowstock, active=$active where id=%d",$supplier_id);
  mysql_query($sql); 


}


function set_stock_value_all(){
 $db =& MDB2::singleton();
  $sql="select  id from product";
  $res=mysql_query($sql);
  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $id=$row['id'];
    set_stockvalue($id);
  }

}





function set_stockvalue($product_id){
  $db =& MDB2::singleton();
  $value=stock_value($product_id);
  if(is_numeric($value)){
    $sql=sprintf("update product set stock_value='%s' where id=%d",number_format($value,2,'.',''),$product_id);
    //print "$sql";
    mysql_query($sql); 
  }

}


function stock_value($product_id,$date=false){
  $db =& MDB2::singleton();
  
  $sql=sprintf("select units from product where id=%d",$product_id);
  $res=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $units=$row['units'];
  }else
    return 'error';



  $sql=sprintf("select avg(price) as av from product2supplier where product_id=%d",$product_id);
  $res=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $price=$row['av'];
  }else
    $price='';
  


  $stock=get_stock($product_id,$date);

  if($stock==0)
    return 0;

  if(is_numeric($stock) and $stock>=0 and is_numeric($price))
    $value=$units*$price*$stock;
  else
    $value='error';
  


  return $value;

}


function addtosupplier($product_id,$supplier_id){
  $db =& MDB2::singleton();
  $sql=sprintf("select count(*) as x from product where id=%d",$product_id);
  $res=mysql_query($sql);
  $tmp1=$res->numRows();
  if($tmp1!=1)
    return;
  
  $sql=sprintf("select count(*) as x from supplier where id=%d",$supplier_id);
  $res=mysql_query($sql);
  $tmp1=$res->numRows();
  if($tmp1!=1)
    return 0;
  
  

  $sql="insert into product2supplier (supplier_id,product_id) values ($supplier_id,$product_id)";
 
  $affected=& mysql_query($sql);
  
  if (PEAR::isError($affected)) {
    return 0;
  }else{
    return  $db->lastInsertID();
  }
}

function addfamilytosupplier($family_id,$upplier_id){
  $sql=sprintf("select  id from product where group_id=%d",$family_id);
  $res=mysql_query($sql);
  $changed=0;
  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $id=$row['id'];
    $changed=$changed+addtosupplier($id,$upplier_id);
  }
  return $changed;
}


function update_department_all(){
  $db =& MDB2::singleton();
  $sql="select  id from product_department";
  $res=mysql_query($sql);
  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $id=$row['id'];
    update_department($id);
  }
}


function update_family_all(){
  $db =& MDB2::singleton();
  $sql="select  id from product_group";
  $res=mysql_query($sql);
  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $id=$row['id'];
    update_family($id);
  }
}


function update_department($department_id){
   $db =& MDB2::singleton();
  $sql=" select id ,
(select sum(product.tsall) from product left join product_group as g on (g.id=group_id)  where department_id=d.id    )   as tsall,
(select sum(product.tsy) from product left join product_group as g on (g.id=group_id)  where department_id=d.id    )   as tsy,
(select sum(product.tsq) from product left join product_group as g on (g.id=group_id)  where department_id=d.id    )   as tsq,
(select sum(product.tsm) from product left join product_group as g on (g.id=group_id)  where department_id=d.id    )   as tsm,



(select sum(product.stock_value) from product left join product_group as g on (g.id=group_id)  where department_id=d.id    )   as stock_value,(select count(*) from product_group where department_id=d.id    ) as families   ,(select count(*) from product left join product_group as g on (g.id=group_id)  where department_id=d.id    )   as products,(select count(*) from product left join product_group as g on (g.id=group_id)  where department_id=d.id and (condicion=0  or (condicion=1 and stock>0)  or (condicion=2 and stock>0)   )    )   as active  , (select count(*) from product left join product_group as g on (g.id=group_id)  where department_id=d.id and (condicion=0 and stock=0  ) )   as outofstock, (select count(*) from product left join product_group as g on (g.id=group_id)  where department_id=d.id and ( isnull(stock) or stock<0  ) )   as stockerror       from product_department  as d where d.id=$department_id";

  $res=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $products=$row['products'];
    $families=$row['families'];
    $outofstock=$row['outofstock'];
    $stockerror=$row['stockerror'];
    //    $total_sales=$row['total_sales'];
    $active=$row['active'];
     $stock_value=$row['stock_value'];
    if(!is_numeric($stock_value))
      $stock_value=0;
  
  $tsall=number_format($row['tsall'],2,'.','');
    $tsy=number_format($row['tsy'],2,'.','');
    $tsq=number_format($row['tsq'],2,'.','');
    $tsm=number_format($row['tsm'],2,'.','');


    $sql=sprintf("update product_department set  tsall=%s, tsy=%s,tsq=%s,tsm=%s    ,stock_value=%s ,families='%d',products='%d',outofstock='%d',stockerror='%d',active='%d' where id=%d  ",$tsall,$tsy,$tsq,$tsm,$stock_value,$families,$products,$outofstock,$stockerror,$active,$department_id); 
    // print "$sql\n";
    mysql_query($sql);
  }

}

function update_family($family_id,$update_depto=false){
  $db =& MDB2::singleton();
  $sql=" select id ,(select sum(product.tsq)  from product where group_id=g.id     ) as tsq , (select sum(product.tsm)  from product where group_id=g.id     ) as tsm ,  (select sum(product.tsy)  from product where group_id=g.id     ) as tsy , (select sum(product.tsall)  from product where group_id=g.id     ) as tsall , (select sum(product.stock_value)  from product where group_id=g.id     ) as stock_value , (select count(*) from product where group_id=g.id    ) as products  ,(select count(*) from product where group_id=g.id and (condicion=0  or (condicion=1 and stock>0)  or (condicion=2 and stock>0)   )    )   as active  ,(select count(*) from product where group_id=g.id and (condicion=0 and stock=0  ) )   as outofstock,(select count(*) from product where group_id=g.id and ( isnull(stock) or stock<0  ) )   as stockerror       from product_group  as g where g.id=$family_id";
 // print "$sql\n";
  $res=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $products=$row['products'];
    $outofstock=$row['outofstock'];
    $stockerror=$row['stockerror'];
    //    $total_sales=$row['total_sales'];
    $active=$row['active'];
    

    $tsall=number_format($row['tsall'],2,'.','');
    $tsy=number_format($row['tsy'],2,'.','');
    $tsq=number_format($row['tsq'],2,'.','');
    $tsm=number_format($row['tsm'],2,'.','');

    
    $stock_value=$row['stock_value'];
    if(!is_numeric($stock_value))
      $stock_value=0;


    $sql=sprintf("update product_group set  tsall=%s, tsy=%s,tsq=%s,tsm=%s    ,  stock_value=%s ,products='%d',outofstock='%d',stockerror='%d',active='%d' where id=%d  ",$tsall,$tsy,$tsq,$tsm,  $stock_value,$products,$outofstock,$stockerror,$active,$family_id); 


    


  //  print "$sql\n";
    //exit;
    mysql_query($sql);

    if($update_depto){
    $sql=sprintf("select  department_id from product_group where id=%d   ",$family_id);
    $res=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      update_department($row['department_id']);
    }
    }




  }

}









function update_stockhistory($product_id,$qty,$op_tipo,$op_id,$op_date,$value,$update_line=true,$umbral=true){
  $db =& MDB2::singleton();



  $sql=sprintf("select has_child from product where id=%d",$product_id);
  $res =  mysql_query($sql);
  if(!$data=$res->fetchRow())
    return;
  

  if($data['has_child']==1){

    $sql=sprintf("select count(*) as n from product_relations where product_id=%d",$product_id);
    $res2 =  mysql_query($sql);
    $data2=$res2->fetchRow();
    $num_parents=$data2['n'];
      


    $sql=sprintf("select bulk_id,relation,umbral from product_relations where product_id=%d",$product_id);
    $res2 =  mysql_query($sql);
    $parents=array();

    $parents_ok=array();
    $parents_no=array();

    while($data2=$res2->fetchRow()){
      
      $qty_parent=$qty*$data2['relation']/$num_parents;
      if($umbral){
	if($qty_parent>$data2['umbral'])
	  $parents_ok[]=array($data2['bulk_id'],$qty_parent,$qty*$data2['relation'] );
	else
	  $parents_no[]=array($data2['bulk_id'],$qty_parent,$qty*$data2['relation'] );
      }else
	$parents_ok[]=array($data2['bulk_id'],$qty_parent,$qty*$data2['relation'] );

    }
    
    if(count($parents_no)==0){
      foreach($parents_ok as $p){
	$value=unit_stock_value_estimate($p[0],$op_date,$p[1]);
	update_stockhistory($p[0],$p[1],$op_tipo,$op_id,$op_date,$value,$update_line,$umbral);
      }
    }else{
      // Si todos jodios le tomamos a todos ok
      if(count($parents_ok)==0){
	 foreach($parents_no as $p){
	   $value=unit_stock_value_estimate($p[0],$op_date,$p[1]);
	   update_stockhistory($p[0],$p[1],$op_tipo,$op_id,$op_date,$value,$update_line,$umbral);
	 }
      }elseif(count($parents_no)==0){// todos bien
	 foreach($parents_ok as $p){
	   $value=unit_stock_value_estimate($p[0],$op_date,$p[1]);
	   update_stockhistory($p[0],$p[1],$op_tipo,$op_id,$op_date,$value,$update_line,$umbral);
	 }

      }else{
	$ok_parents=count($parents_ok);
	  foreach($parents_ok as $p){
	    $p[1]=$p[2]/$ok_parents;
	    $value=unit_stock_value_estimate($p[0],$op_date,$p[1]);
	    update_stockhistory($p[0],$p[1],$op_tipo,$op_id,$op_date,$value,$update_line,$umbral);
	}
      }
    }
  
  
  }else{



  list($s,$a,$v)=stock_date($product_id,$op_date);
  if($s==''){
    // Assume that this is the first date, so crea te a new bagining
    $first_date=date("Y-m-d H:i:s",strtotime(str_replace("'",'',$op_date))-1);
    $sql="update stock_history set op_date='$first_date' where  product_id=$product_id and  op_tipo=100";
    mysql_query($sql);
    $sql="update product set first_date='$first_date' where id=$product_id";
    mysql_query($sql);
    $s=0;
    $a=0;
    $v=0;
  }
  
  //print "$s $a $v $qty $op_tipo\n";
  if($op_tipo==1)
    $qty=$qty-$s;

  
  if($op_tipo==5){
    $new_a=$a+$qty;
    $new_s=$s;
    $new_v=$v;
  }else{
    $new_s=$s+$qty;
    $new_v=$v+$value;
    $new_a=$a+$qty;
 }

  $sql="insert into stock_history (op_value,product_id,stock,available,value,op_qty, op_date,op_tipo,op_id) values ($value,$product_id,$new_s,$new_a,$new_v,$qty,'$op_date',$op_tipo,$op_id)";
  //print "$new_s $qty  ----\n ";
//  print "$sql\n";

   mysql_query($sql);
  // last update stok history;
   if($update_line)
     update_stockhistoryline($product_id);
   
   return 1;
  }

}

function stock_date($product_id,$date=''){
 $db =& MDB2::singleton();

 if($date=='')
   $date='NOW()';
 else
   $date="'".addslashes($date)."'";
 
//  // Checar if is a child product
//  $sql=sprintf("select child,relation from product left join product_relations on (product.id=product_id) where product.id=%d",$product_id);
//  $res=mysql_query($sql);
//  if(!$data=$res->fetchRow()) 
//    return false;
 
//  if($data['child']==1)
   
   
$sql=sprintf("select stock,available,value from stock_history where  product_id=%d and op_date<%s order by op_date desc limit 1",$product_id,$date);
//print $sql;
$res=mysql_query($sql);

 $s='';$a='';$v='';
if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
  $s=$row['stock'];
  $a=$row['available'];
  $v=$row['value'];
 }
return array($s,$a,$v);
}


function update_stockhistoryline($product_id,$update_values=true){
$db =& MDB2::singleton();
$sql=sprintf("select available,op_value,op_qty,id,op_tipo,op_date,op_id from stock_history where product_id=%d order by op_date",$product_id);
$res=mysql_query($sql);
$stock=0;
$available=0;
$value=0;
while($row=mysql_fetch_array($result, MYSQL_ASSOC)){

  
  $q=$row['op_qty'];
  $a=$row['available'];
  $date=$row['op_date'];
  if($update_values)
    $v=unit_stock_value_estimate($product_id,$date,$q);
  else
    $v=$row['op_value'];
  $tipo=$row['op_tipo'];
  
  if($tipo==0){
    $sql="select qty from inventory_item where id=".$row['op_id'];
    $res2 = mysql_query($sql); 
    if ($row2 = $res2->fetchRow() ) {
      $qty_inv=$row2['qty'];
      $q=$qty_inv-$stock;
      $sql=sprintf("update stock_history set  op_qty='%s' where id=%d",$q,$row['id']);
      mysql_query($sql);
      
    }
  }


 
  if($tipo!=4)
    $stock=$stock+$q;
  

  $available=$available+$q;
  $value=$value+$v;
    


  
  if($update_values)
    $sql=sprintf("update stock_history set  op_value='%s',stock='%s',value='%s',available='%s' where id=%d",$v,$stock,$value,$available,$row['id']);
  else
    $sql=sprintf("update stock_history set  stock='%s',value='%s',available='%s' where id=%d",$stock,$value,$available,$row['id']);
  //print "$sql\n";
  mysql_query($sql);
 }




 
$sql=sprintf("update product set stock='%s',available='%s'   where id=%d",$stock,$available,$product_id);
//print "$sql\n";
mysql_query($sql);
}






function unit_stock_value_estimate($product_id,$date,$qty_reference=1){
$db =& MDB2::singleton();
 
 $qty_reference=abs($qty_reference);
  $qty=0;
  $value=0;
  $sql=sprintf("select op_qty,op_value,op_date from stock_history where product_id=%d and op_date<'%s' and op_qty>0 order by op_date desc",$product_id,$date);
  //   print "$sql\n";
  $res=mysql_query($sql);
  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $qty=$qty+$row['op_qty'];
    $value=$value+$row['op_value'];

    
    if($qty>$qty_reference){
      $unit_price=$value/$qty;
      return $unit_price;
    }
	
    
  }
  
  if($qty>0){
    $unit_price=$value/$qty;
    return $unit_price;
  }else{
    $sql=sprintf("select avg(price) as price from product2supplier where product_id=%s",$product_id);
    $res=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $price=$row['price'];
      return $price;
    }else
      return 0;

  }




}


?>