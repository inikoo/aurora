<?


function taxable_sales_in_interval($from,$to){
  

}


function sales_in_interval($from,$to,$valid_tax_rates_data=false){
   $db =& MDB2::singleton();
   global $myconf;
  
   $valid_tax_rates=false;
   if($valid_tax_rates_data){
   $_from=date('U',strtotime($from));
   $_to=date('U',strtotime($to));
   $valid_tax_rates=array();
   foreach($valid_tax_rates_data as $key=>$data){
     $_date_inicio=date('U',strtotime($data['date']));
     if(!isset($valid_tax_rates_data[$key+1]))
       $_date_fin=$_to+1;
     else
       $_date_fin=date('U',strtotime($valid_tax_rates_data[$key+1]['date']));
     
     if($_from>=$_date_inicio and $_to<$_date_fin)
       $valid_tax_rates[]=$data['rate'];
   }
   }     
   

   $int=prepare_mysql_dates($from,$to,'date_done','only_dates');


   


  
   



  // Get refunds first
  
  // Refund partner
  $refund_net_p=0;
  $refund_tax_p=0;
  $sql=sprintf("select sum(debit.value_net) as net,sum(if(debit.tax_code='S',value_net*0.175,0)+value_tax) as tax  from debit  left join orden on (order_affected_id=orden.id) where  partner=1 and debit.tipo=4 %s ",$int[0]);

  $res = $db->query($sql);
  if($row=$res->fetchRow()) {
    $refund_net_p=($row['net']==''?0:$row['net']);
    $refund_tax_p=($row['tax']==''?0:$row['tax']);
  }
  $refund_net_p_home=0;
  $refund_tax_p_home=0;
  $sql=sprintf("select sum(debit.value_net) as net,sum(if(debit.tax_code='S',value_net*0.175,0)+value_tax) as tax  from debit  left join orden on (order_affected_id=orden.id) where del_country_id=%d and  partner=1 and debit.tipo=4 %s ",$myconf['country_id'],$int[0]);
  $res = $db->query($sql);
  if($row=$res->fetchRow()) {
    $refund_net_p_home=($row['net']==''?0:$row['net']);
    $refund_tax_p_home=($row['tax']==''?0:$row['tax']);
  }
  
  $refund_net_p_nohome=$refund_net_p-$refund_net_p_home;
  $refund_tax_p_nohome=$refund_tax_p-$refund_tax_p_home;

   
  $refund_net=0;
  $refund_tax=0;
  $refund_net_home=0;
  $refund_tax_home=0;
  $refund_net_extended_home=0;
  $refund_tax_extended_home=0;
  $refund_net_region=0;
  $refund_tax_region=0;
  $refund_net_region2=0;
  $refund_tax_region2=0;
  $refund_net_org=0;
  $refund_tax_org=0;

  $sql=sprintf("select sum(debit.value_net) as net,sum(if(debit.tax_code='S',value_net*0.175,0)+value_tax) as tax  from debit  left join orden on (order_affected_id=orden.id) where  partner=0 and debit.tipo=4 %s ",$int[0]);
  $res = $db->query($sql);
  if($row=$res->fetchRow()) {
    $refund_net=($row['net']==''?0:$row['net']);
    $refund_tax=($row['tax']==''?0:$row['tax']);

    // get other refunds per geographical thing
    
    $sql=sprintf("select sum(value_net) as net,sum(if(debit.tax_code='S',value_net*0.175,0)+value_tax) as tax  from debit left join orden on (order_affected_id=orden.id) where partner=0 and del_country_id=%d and debit.tipo=4 %s ",$myconf['country_id'],$int[0]);
    //  print "$sql";
    $res = $db->query($sql);
    if($row=$res->fetchRow()) {
      $refund_net_home=($row['net']==''?0:$row['net']);
      $refund_tax_home=($row['tax']==''?0:$row['tax']);
    }
    $countries ='(';
    foreach($myconf['extended_home_id'] as $county_id){
      $countries.='del_country_id='.$county_id.' or ';
    }
    $countries=preg_replace('/or $/',')',$countries);
 $sql=sprintf("select sum(value_net) as net,sum(if(debit.tax_code='S',value_net*0.175,0)+value_tax) as tax  from debit left join orden on (order_affected_id=orden.id) where %s  and partner=0   and debit.tipo=4 %s ",$countries,$int[0]);
 // print "$sql";
    $res = $db->query($sql);
    if($row=$res->fetchRow()) {
      $refund_net_extended_home=($row['net']==''?0:$row['net']);
      $refund_tax_extended_home=($row['tax']==''?0:$row['tax']);
    }
    $countries ='(';
    foreach($myconf['region_id'] as $county_id){
      $countries.='del_country_id='.$county_id.' or ';
    }
    $countries=preg_replace('/or $/',')',$countries);
 $sql=sprintf("select sum(value_net) as net,sum(if(debit.tax_code='S',value_net*0.175,0)+value_tax) as tax  from debit left join orden on (order_affected_id=orden.id) where %s  and partner=0 and debit.tipo=4 %s ",$countries,$int[0]);
 // print "$sql";
    $res = $db->query($sql);
    if($row=$res->fetchRow()) {
      $refund_net_region=($row['net']==''?0:$row['net']);
      $refund_tax_region=($row['tax']==''?0:$row['tax']);
    }
 $countries ='(';
    foreach($myconf['continent_id'] as $county_id){
      $countries.='del_country_id='.$county_id.' or ';
    }
    $countries=preg_replace('/or $/',')',$countries);
 $sql=sprintf("select sum(value_net) as net,sum(if(debit.tax_code='S',value_net*0.175,0)+value_tax) as tax  from debit left join orden on (order_affected_id=orden.id) where %s and partner=0 and debit.tipo=4 %s ",$countries,$int[0]);
 // print "$sql";
    $res = $db->query($sql);
    if($row=$res->fetchRow()) {
      $refund_net_region2=($row['net']==''?0:$row['net']);
      $refund_tax_region2=($row['tax']==''?0:$row['tax']);
    }



  }

  $countries ='(';
    foreach($myconf['org_id'] as $county_id){
      $countries.='del_country_id='.$county_id.' or ';
    }
    $countries=preg_replace('/or $/',')',$countries);
 $sql=sprintf("select sum(value_net) as net,sum(if(debit.tax_code='S',value_net*0.175,0)+value_tax) as tax  from debit left join orden on (order_affected_id=orden.id) where %s   and debit.tipo=4 %s ",$countries,$int[0]);
 // print "$sql";
    $res = $db->query($sql);
    if($row=$res->fetchRow()) {
      $refund_net_org=($row['net']==''?0:$row['net']);
      $refund_tax_org=($row['tax']==''?0:$row['tax']);
    }


  $refund_net_nohome=$refund_net-$refund_net_home;
  $refund_tax_nohome=$refund_tax-$refund_tax_home;
  $refund_net_extended_home_nohome=$refund_net_extended_home-$refund_net_home;
  $refund_tax_extended_home_nohome=$refund_tax_extended_home-$refund_tax_home;
  $refund_net_region_nohome=$refund_net_region-$refund_net_home;
  $refund_tax_region_nohome=$refund_tax_region-$refund_tax_home;
  $refund_net_region2_nohome=$refund_net_region2-$refund_net_home;
  $refund_tax_region2_nohome=$refund_tax_region2-$refund_tax_home;
  $refund_net_org_nohome=$refund_net_org-$refund_net_home;
  $refund_tax_org_nohome=$refund_tax_org-$refund_tax_home;



 $int=prepare_mysql_dates($from,$to,'date_index','only_dates');
  $sql=sprintf("select sum(net) as net,sum(tax) as tax , count(*) as invoices from orden where  tipo=2 and partner=1 %s ",$int[0]);

  $res = $db->query($sql);
  if($row=$res->fetchRow()) {
    $net_p=$row['net']+$refund_net_p;
    $tax_p=$row['tax']+$refund_tax_p;
    $invoices_p=$row['invoices'];
  }



  // get sales and invoices

  // get data for parnerts

  $net_p=0;
  $tax_p=0;
  $invoices_p=0;
  $net_p_home=0;
  $tax_p_home=0;
  $invoices_p_home=0;

  $int=prepare_mysql_dates($from,$to,'date_index','only_dates');
  $sql=sprintf("select sum(net) as net,sum(tax) as tax , count(*) as invoices from orden where  tipo=2 and partner=1 %s ",$int[0]);

  $res = $db->query($sql);
  if($row=$res->fetchRow()) {
    $net_p=$row['net']+$refund_net_p;
    $tax_p=$row['tax']+$refund_tax_p;
    $invoices_p=$row['invoices'];
  }
  $sql=sprintf("select sum(net) as net,sum(tax) as tax , count(*) as invoices from orden where  tipo=2 and  del_country_id=%d   and partner=1 %s ",$myconf['country_id'],$int[0]);
  $res = $db->query($sql);
  if($row=$res->fetchRow()) {
    $net_p_home=$row['net']+$refund_net_p_home;
    $tax_p_home=$row['tax']+$refund_tax_p_home;
    $invoices_p_home=$row['invoices'];
  }
  $net_p_nohome=$net_p-$net_p_home;
  $tax_p_nohome=$tax_p-$tax_p_home;
  $invoices_p_nohome=$invoices_p-$invoices_p_home;

  // No partners
  $net=0;
  $tax=0;
  $invoices=0;
  $net_home=0;
  $tax_home=0;
  $invoices_home=0;
  $net_extended_home=0;
  $tax_extended_home=0;
  $invoices_extended_home=0;
  $net_region=0;
  $tax_region=0;
  $invoices_region=0;
  $net_region2=0;
  $tax_region2=0;
  $invoices_region2=0;
  $net_org=0;
  $tax_org=0;
  $invoices_org=0;

  $net_taxable=0;
  $tax_taxable=0;
  $invoices_taxable=0;
  $net_taxable_all=0;
  $tax_taxable_all=0;
  $invoices_taxable_all=0;

  $taxable=array();
  $notaxable=array();

  $taxable_error=array();
  $notaxable_error=array();
  $novalue_invoices=0;
  
 
  if(is_array($valid_tax_rates)){

   $sql=sprintf("select ROUND(100*ifnull(tax,0)/net,1) as vat_rate,  sum(net) as net,sum(tax) as tax , count(*) as invoices from orden   where  orden.tipo=2 and vateable=1 %s group by vat_rate",$int[0]);
   //  print $sql;
    $res = $db->query($sql);
    while($row=$res->fetchRow()) {
	$net_taxable_all+=$row['net'];
	$tax_taxable_all+=$row['tax'];
	$invoices_taxable_all+=$row['invoices'];
      
	if($row['vat_rate']==''){
	  $novalue_invoices=$row['invoices'];

	}else if(in_array($row['vat_rate'],$valid_tax_rates)){
	  $index=number($closest_rate)."%";
	  if(!isset($taxable[$index]))
	    $taxable[$index]=array(
				   'sales'=>$row['net'],
				   'tax'=>$row['tax'],
				   'invoices'=>$row['invoices']
				   );
	  else
	    $taxable[$index]=array(
				   'sales'=>$row['net']+$taxable[$index]['sales'],
				   'tax'=>$row['tax']+$taxable[$index]['tax'],
				   'invoices'=>$row['invoices']+$taxable[$index]['invoices']
				   );
	  
	  

	}else{
	  
	  // chech each case
	  $_net_taxable=0;
	  $_tax_taxable=0;
	  $_invoices_taxable=0;
	  $errors=false;
	  $sql=sprintf("select public_id,net ,tax from orden   where  orden.tipo=2 and vateable=1 and ROUND(100*ifnull(tax,0)/net,1)=%.1f   %s  ",$row['vat_rate'],$int[0]);
	  //  print $sql." | ".$row['vat_rate']."   <br>";
	  $res2 = $db->query($sql);
	  while($row2=$res2->fetchRow()) {
	    // print abs($avg_rate-$row['vat_rate'])." ".abs(1/$row2['net'])."<br>";
	    $min_diff=0;
	    $closest_rate=false;
	    foreach($valid_tax_rates as $rate){
	      $_min_diff=abs($rate-$row['vat_rate']);
	      if(!$closest_rate or $_min_diff<$min_diff){
		$closest_rate=$rate;
		$min_diff=$_min_diff;
	      }
	    }

	    if($min_diff<abs(1/$row2['net']) ){
	      if(!isset($taxable[number($closest_rate)."%"]))
		$taxable[number($closest_rate)."%"]=array(
							 'sales'=>$row2['net'],
							 'tax'=>$row2['tax'],
							 'invoices'=>1
							 );
	      else
		$taxable[number($closest_rate)."%"]=array(
							 'sales'=>$row2['net']+$taxable[number($closest_rate)."%"]['sales'],
							 'tax'=>$row2['tax']+$taxable[number($closest_rate)."%"]['tax'],
							 'invoices'=>1+$taxable[number($closest_rate)."%"]['invoices']
							 );
	      

	    }else{
	      $errors=true;
	      $_net_taxable+=$row2['net'];
	      $_tax_taxable+=$row2['tax'];
	      $_invoices_taxable+=1;
	    }
	    


	  }
	  if($errors){
	    //print "AQYUUUUUUUUUU" .$row['vat_rate']."<br>";
	    $taxable_error[number($row['vat_rate'])."%"]=array(
						   'sales'=>money($_net_taxable),
						   'tax'=>money($_tax_taxable),
						   'invoices'=>number($_invoices_taxable)
						 );
	  }

      }
    }
    
  }
  
  
 $sql=sprintf("select sum(net) as net,sum(tax) as tax , count(*) as invoices  from orden   where  orden.tipo=2 and vateable=0 and tax!=0 %s ",$int[0]);
 // print $sql;
 $res = $db->query($sql);
 if($row=$res->fetchRow()) {
   $notaxable_error[]=array(
			 'sales'=>$row['net'],
			 'tax'=>$row['tax'],
			 'invoices'=>$row['invoices']
			    );
      
 }
 
 $sql=sprintf("select sum(net) as net,sum(tax) as tax , count(*) as invoices  from orden   where  orden.tipo=2 and vateable=0 and tax=0 %s ",$int[0]);
  $res = $db->query($sql);
  if($row=$res->fetchRow()) {
    $notaxable[]=array(
		       'sales'=>$row['net'],
		       'tax'=>$row['tax'],
			 'invoices'=>$row['invoices']
		       );
  }

  // exit;
  //  print_r($taxable);
  $sql=sprintf("select sum(net) as net,sum(tax) as tax , count(*) as invoices,avg(datediff(date_index,date_creation)) as dispatch_days from orden   where  orden.tipo=2 and partner=0  %s ",$int[0]);


  $res = $db->query($sql);
  if($row=$res->fetchRow()) {
    $dispatch_days=$row['dispatch_days'];
    $net=$row['net']+$refund_net;
    $tax=$row['tax']+$refund_tax;
    $invoices=$row['invoices'];
  }



  $sql=sprintf("select sum(net) as net,sum(tax) as tax , count(*) as invoices,avg(datediff(date_index,date_creation)) as dispatch_days from orden   where  orden.tipo=2 and partner=0 and del_country_id=%d %s ",$myconf['country_id'],$int[0]);


  $res = $db->query($sql);
  if($row=$res->fetchRow()) {
    $dispatch_days_home=$row['dispatch_days'];
    $net_home=$row['net']+$refund_net_home;
    $tax_home=$row['tax']+$refund_tax_home;;
    $invoices_home=$row['invoices'];
  }


 $sql=sprintf("select sum(net) as net,sum(tax) as tax , count(*) as invoices,avg(datediff(date_index,date_creation)) as dispatch_days from orden   where  orden.tipo=2 and partner=0 and del_country_id!=%d %s ",$myconf['country_id'],$int[0]);


  $res = $db->query($sql);
  if($row=$res->fetchRow()) {
    $dispatch_days_nohome=$row['dispatch_days'];
    $net_nohome=$row['net']+$refund_net_nohome;
    $tax_nohome=$row['tax']+$refund_tax_nohome;;
    $invoices_nohome=$row['invoices'];
  }

  //  print "$net $net_home $net_nohome ".($net-$net_home-$net_nohome)."\n";
  //print "$invoices $invoices_home $invoices_nohome ".($invoices-$invoices_home-$invoices_nohome)."\n";
  //exit;
  $countries='(';
  foreach($myconf['extended_home_id'] as $county_id){
    $countries.='del_country_id='.$county_id.' or ';
  }
  $countries=preg_replace('/or $/',')',$countries);
 $sql=sprintf("select sum(net) as net,sum(tax) as tax , count(*) as invoices from orden     where  orden.tipo=2 and partner=0 and %s %s ",$countries,$int[0]);
  $res = $db->query($sql);
  if($row=$res->fetchRow()) {
    $net_extended_home=$row['net']+$refund_net_extended_home;
    $tax_extended_home=$row['tax']+$refund_tax_extended_home;
    $invoices_extended_home=$row['invoices'];
  }
  $countries='(';
  foreach($myconf['region_id'] as $county_id){
    $countries.='del_country_id='.$county_id.' or ';
  }
  $countries=preg_replace('/or $/',')',$countries);
 $sql=sprintf("select sum(net) as net,sum(tax) as tax , count(*) as invoices from orden     where  orden.tipo=2 and partner=0 and %s %s ",$countries,$int[0]);
  $res = $db->query($sql);
  if($row=$res->fetchRow()) {
    $net_region=$row['net']+$refund_net_region;
    $tax_region=$row['tax']+$refund_tax_region;
    $invoices_region=$row['invoices'];
  }
 $countries='(';
  foreach($myconf['continent_id'] as $county_id){
    $countries.='del_country_id='.$county_id.' or ';
  }
  $countries=preg_replace('/or $/',')',$countries);
 $sql=sprintf("select sum(net) as net,sum(tax) as tax , count(*) as invoices from orden     where  orden.tipo=2 and partner=0 and %s %s ",$countries,$int[0]);
  $res = $db->query($sql);
  if($row=$res->fetchRow()) {
    $net_region2=$row['net']+$refund_net_region;
    $tax_region2=$row['tax']+$refund_tax_region;
    $invoices_region2=$row['invoices'];
  }
$countries='(';
  foreach($myconf['org_id'] as $county_id){
    $countries.='del_country_id='.$county_id.' or ';
  }
  $countries=preg_replace('/or $/',')',$countries);
 $sql=sprintf("select sum(net) as net,sum(tax) as tax , count(*) as invoices from orden     where  orden.tipo=2  and %s %s ",$countries,$int[0]);
  $res = $db->query($sql);
  if($row=$res->fetchRow()) {
    $net_org=$row['net']+$refund_net_org;
    $tax_org=$row['tax']+$refund_tax_org;
    $invoices_org=$row['invoices'];
  }

  
  //  $net_nohome=$net-$net_home;
  //$tax_nohome=$tax-$tax_home;
  //$invoices_nohome=$invoices-$invoices_home;

  $net_extended_home_nohome=$net_extended_home-$net_home;
  $tax_extended_home_nohome=$tax_extended_home-$tax_home;
  $invoices_extended_home_nohome=$invoices_extended_home-$invoices_home;

  $net_region_nohome=$net_region-$net_home;
  $tax_region_nohome=$tax_region-$tax_home;
  $invoices_region_nohome=$invoices_region-$invoices_home;

  $net_region2_nohome=$net_region2-$net_home;
  $tax_region2_nohome=$tax_region2-$tax_home;
  $invoices_region2_nohome=$invoices_region2-$invoices_home;
  $net_org_nohome=$net_org-$net_home;
  $tax_org_nohome=$tax_org-$tax_home;
  $invoices_org_nohome=$invoices_org-$invoices_home;


  $sql=sprintf("select sum(net) as net , count(*) as orders from orden where  tipo=1 %s ",$int[0]);
 //  print "$sql";
    $res = $db->query($sql);
   if($row=$res->fetchRow()) {
     $net_tobedone=$row['net'];
     $tobedone=$row['orders'];
   }





   // export destinations
   $num_countries=0;
   $sql=sprintf("select count(*) as num from orden left join list_country as country on (del_country_id=country.id) where del_country_id!=30 and partner=0  %s group by del_country_id ",$int[0]);
   $res = $db->query($sql);
   while($row=$res->fetchRow()) {
     $num_countries=$row['num'];
   }

   $top3=array();
   $sql=sprintf("select country.name, sum(net) as net ,sum(tax) as tax from orden left join list_country as country on (del_country_id=country.id) where del_country_id!=30 and partner=0  %s group by del_country_id order by net desc limit 3",$int[0]);
   //   print "$sql";
   $res = $db->query($sql);
   while($row=$res->fetchRow()) {
     $top3[]=array('country'=>$row['name'],'net'=>$row['net'],'tax'=>$row['tax']);
   }
   $countries=array();
   $sql=sprintf("select country.id,country.code2,country.name, sum(net) as net ,sum(tax) as tax,count(*) as orders from orden left join list_country as country on (del_country_id=country.id) where del_country_id!=30 and partner=0  and tipo=2 %s group by del_country_id order by net desc ",$int[0]);
   //print $sql;
   $res = $db->query($sql);
   while($row=$res->fetchRow()) {

     // todo change to a info inside list_cumties
     
     if( in_array($row['id'],$myconf['org_id']))
       $eu=1;
     else
       $eu=0;
     $countries[]=array('country'=>'<img src="art/flags/'.strtolower($row['code2']).'.gif">'.' '.$row['name'],'net'=>money($row['net']),'tax'=>money($row['tax']),'orders'=>$row['orders'],'share'=>percentage($row['net'],$net_nohome,2),'eu'=>$eu,'id'=>$row['id'],'name'=>$row['name']);
   }

   $exports=array('num_countries'=>$num_countries,'top3'=>$top3,'countries'=>$countries);
   $orders_total=0;
 $orders_net=0;
 $orders_cancelled=0;
 $orders_invoices=0;
 $orders_follows=0;
 $orders_todo=0;
 $orders_todo_net=0;
 $orders_cancelled_net=0;
 $orders_invoices_net=0;
 $orders_follows_net=0;
 $orders_todo_net=0;
 $orders_donations=0;
 $orders_donations_net=0;
 $orders_others=0;
 $orders_others_net=0;
$sql=sprintf("select count(*) as num ,sum(net) as net  from orden where true %s ",$int[0]);
 $res = $db->query($sql);
 if($row=$res->fetchRow()){
    $orders_total_net=$row['net'];
     $orders_total=$row['num'];
 }
 $_int=preg_replace('/date_index/','date_creation',$int[0]);
 $sql=sprintf("select tipo,count(*) as num ,sum(net) as net  from orden where true %s group by tipo ",$_int);
 $res = $db->query($sql);
 while($row=$res->fetchRow()) {
   $tipo=$row['tipo'];
   switch($tipo){
   case 1:
     $orders_todo_net=$row['net'];
     $orders_todo=$row['num'];
     break;
   case 2:
     $orders_invoices_net=$row['net'];
     $orders_invoices=$row['num'];
     break;
   case 3:
     $orders_cancelled_net=$row['net'];
     $orders_cancelled=$row['num'];
     break;
   case 6:
   case 7:
     $orders_follows_net+=$row['net'];
     $orders_follows+=$row['num'];
     break;
  case 5:
     $orders_donations_net=$row['net'];
     $orders_donations=$row['num'];
     break;
   default:
     $orders_others_net=$row['net'];
     $orders_others=$row['num'];
   }
 }


  $refunds=array(
		 'refund_net_p'=>$refund_net_p,
		 'refund_tax_p'=>$refund_tax_p,
		 'refund_net_p_home'=>$refund_net_p_home,
		 'refund_tax_p_home'=>$refund_tax_p_home,
		 'refund_net_p_nohome'=>$refund_net_p_nohome,
		 'refund_tax_p_nohome'=>$refund_tax_p_nohome,
		 'refund_net'=>$refund_net,
		 'refund_tax'=>$refund_tax,
		 'refund_net_home'=>$refund_net_home,
		 'refund_tax_home'=>$refund_tax_home,
		 'refund_net_nohome'=>$refund_net_nohome,
		 'refund_tax_nohome'=>$refund_tax_nohome,
		 'refund_net_extended_home'=>$refund_net_extended_home,
		 'refund_tax_extended_home'=>$refund_tax_extended_home,
		 'refund_net_region'=>$refund_net_region,
		 'refund_tax_region'=>$refund_tax_region,
		 'refund_net_region2'=>$refund_net_region2,
		 'refund_tax_region2'=>$refund_tax_region2,
		 'refund_net_org'=>$refund_net_org,
		 'refund_tax_org'=>$refund_tax_org,
		 'refund_net_extended_home_nohome'=>$refund_net_extended_home_nohome,
		 'refund_tax_extended_home_nohome'=>$refund_tax_extended_home_nohome,
		 'refund_net_region_nohome'=>$refund_net_region_nohome,
		 'refund_tax_region_nohome'=>$refund_tax_region_nohome,
		 'refund_net_region2_nohome'=>$refund_net_region2_nohome,
		 'refund_tax_region2_nohome'=>$refund_tax_region2_nohome,
		 'refund_net_org_nohome'=>$refund_net_org_nohome,
		 'refund_tax_org_nohome'=>$refund_tax_org_nohome,
		 'refund'=>$refund_net+$refund_tax,
		 'refund_p'=>$refund_net_p+$refund_tax_p,
		 'refund_home'=>$refund_net_home+$refund_tax_home,
		 'refund_nohome'=>$refund_net_nohome+$refund_tax_nohome


		 );
  $other_data=array(
		    'dispatch_days'=>$dispatch_days,
		    'dispatch_days_home'=>$dispatch_days_home,
		    'dispatch_days_nohome'=>$dispatch_days_nohome,
		    );
  

  $sales=array(
	       'net_taxable_all'=>$net_taxable_all,
	       'tax_taxable_all'=>$tax_taxable_all,
	        'net_taxable'=>$net_taxable,
	       'tax_axable'=>$tax_taxable,
	       'total_net'=>$net_p+$net,
	       'total_tax'=>$tax_p+$tax,
	       'total_net_nohome'=>$net_p_nohome+$net_nohome,
	       'total_tax_nohome'=>$tax_p_nohome+$tax_nohome,
	       'net_p'=>$net_p,
	       'tax_p'=>$tax_p,
	       'net_p_home'=>$net_p_home,
	       'tax_p_home'=>$tax_p_home,
	       'net_p_nohome'=>$net_p_nohome,
	       'tax_p_nohome'=>$tax_p_nohome,
	       'net'=>$net,
	       'tax'=>$tax,
	       'net_home'=>$net_home,
	       'tax_home'=>$tax_home,
	       'net_nohome'=>$net_nohome,
	       'tax_nohome'=>$tax_nohome,
	       'net_extended_home'=>$net_extended_home,
	       'tax_extended_home'=>$tax_extended_home,
	       'net_region'=>$net_region,
	       'tax_region'=>$tax_region,
	       'net_region2'=>$net_region2,
	       'tax_region2'=>$tax_region2,
	       'net_outside'=>$net-$net_region2,
	       'tax_outside'=>$tax-$tax_region2,
	       'net_org'=>$net_org,
	       'tax_org'=>$tax_org,
	       'net_extended_home_nohome'=>$net_extended_home_nohome,
	       'tax_extended_home_nohome'=>$tax_extended_home_nohome,
	       'net_region_nohome'=>$net_region_nohome,
	       'tax_region_nohome'=>$tax_region_nohome,
	       'net_region2_nohome'=>$net_region2_nohome,
	       'tax_region2_nohome'=>$tax_region2_nohome,
	       'net_org_nohome'=>$net_org_nohome,
	       'tax_org_nohome'=>$tax_org_nohome
	       
	       
		 );
  $invoices=array(
		  'invoices_taxeable_all'=>$invoices_taxable_all,
		  'invoices_taxeable'=>$invoices_taxable,
		  'invoices'=>$invoices,
		 'invoices_home'=>$invoices_home,
		 'invoices_nohome'=>$invoices_nohome,
		 'invoices_extended_home'=>$invoices_extended_home,
		 'invoices_region'=>$invoices_region,
		 'invoices_region2'=>$invoices_region2,
		 'invoices_outside'=>$invoices-$invoices_region2,
		 'invoices_org'=>$invoices_org,
		 'invoices_extended_home_nohome'=>$invoices_extended_home_nohome,
		 'invoices_region_nohome'=>$invoices_region_nohome,
		 'invoices_region2_nohome'=>$invoices_region2_nohome,
		 'invoices_org_nohome'=>$invoices_org_nohome,
		 'invoices_p'=>$invoices_p,
		 'invoices_p_home'=>$invoices_p_home,
		 'invoices_p_nohome'=>$invoices_p_nohome,
		 'total_invoices'=>$invoices+$invoices_p,
		 'total_invoices_nohome'=>$invoices_nohome+$invoices_p_nohome
		 );
  $orders=array(
		'orders_total'=>$orders_total,
		'orders_cancelled'=>$orders_cancelled,
		'orders_invoices'=>$orders_invoices,
		'orders_follows'=>$orders_follows,
		'orders_donations'=>$orders_donations,
		'orders_others'=>$orders_others,
		'orders_todo'=>$orders_todo,
		'orders_todo_net'=>$orders_todo_net,
		'orders_total_net'=>$orders_total_net,
		'orders_cancelled_net'=>$orders_cancelled_net,
		'orders_invoices_net'=>$orders_invoices_net,
		'orders_follows_net'=>$orders_follows_net,
		'orders_donations_net'=>$orders_donations_net,
		'orders_others_net'=>$orders_others_net
		);


  $errors=array(
		'taxable'=>$taxable_error,
		'notaxable'=>$notaxable_error,
		'novalue_invoices'=>$novalue_invoices
		);
  return array('invoices'=>$invoices,'sales'=>$sales,'refunds'=>$refunds,'orders'=>$orders,'exports'=>$exports,'other_data'=>$other_data,'errors'=>$errors,'taxable'=>$taxable,'notaxable'=>$notaxable);

}


?>