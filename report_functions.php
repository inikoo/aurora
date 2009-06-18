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
   

   $int=prepare_mysql_dates($from,$to,'`Invoice Date`','only_dates');


   


  
   



  // Get refunds first
  
  // Refund partner
  $refund_net_p=0;
  $refund_tax_p=0;
  $refund_invoices_p=0;
  
  $sql=sprintf("select sum(`Invoice Refund Net Amount`) as net,sum(`Invoice Refund Tax Amount`) as tax,count(*) as orders from `Invoice Dimension` where  `Invoice For Partner`='Yes' and `Invoice Title`='Refund' %s ",$int[0]);
  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){

    $refund_net_p=($row['net']==''?0:$row['net']);
    $refund_tax_p=($row['tax']==''?0:$row['tax']);
    $refund_invoices_p=$row['orders'];
  }
  $refund_net_p_home=0;
  $refund_tax_p_home=0;
  $sql=sprintf("select sum(`Invoice Refund Net Amount`) as net,sum(`Invoice Refund Tax Amount`) as tax,count(*) as orders from `Invoice Dimension` where  `Invoice For Partner`='Yes' and `Invoice Title`='Refund' and `Invoice Billing Country 2 Alpha Code`=%s  %s ",prepare_mysql($myconf['country_2acode']),$int[0]);
  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $refund_net_p_home=($row['net']==''?0:$row['net']);
    $refund_tax_p_home=($row['tax']==''?0:$row['tax']);
    $refund_invoices_p_home=$row['orders'];
  }

 $refund_net_p_unk=0;
  $refund_tax_p_unk=0;
  $sql=sprintf("select sum(`Invoice Refund Net Amount`) as net,sum(`Invoice Refund Tax Amount`) as tax,count(*) as orders from `Invoice Dimension` where  `Invoice For Partner`='Yes' and `Invoice Title`='Refund' and `Invoice Billing Country 2 Alpha Code`=%s  %s ",prepare_mysql('XX'),$int[0]);
  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $refund_net_p_unk=($row['net']==''?0:$row['net']);
    $refund_tax_p_unk=($row['tax']==''?0:$row['tax']);
    $refund_invoices_p_unk=$row['orders'];
  }

  
  $refund_net_p_nohome=$refund_net_p-$refund_net_p_home-$refund_invoices_p_unk;
  $refund_tax_p_nohome=$refund_tax_p-$refund_tax_p_home-$refund_tax_p_unk;
  $refund_invoices_p_nohome=$refund_invoices_p-$refund_invoices_p_home-$refund_invoices_p_unk
;
   
  $refund_net=0;
  $refund_tax=0;
  $refund_net_home=0;
  $refund_tax_home=0;

  $refund_net_unk=0;
  $refund_tax_unk=0;
  $refund_invoices_unk=0;

  $refund_net_extended_home=0;
  $refund_tax_extended_home=0;
  $refund_net_region=0;
  $refund_tax_region=0;
  $refund_net_region2=0;
  $refund_tax_region2=0;
  $refund_net_org=0;
  $refund_tax_org=0;

  $refund_invoices=0;
  $refund_invoices_home=0;
  $refund_invoices_nohome=0;


  $sql=sprintf("select sum(`Invoice Refund Net Amount`) as net,sum(`Invoice Refund Tax Amount`) as tax,count(*) as orders from `Invoice Dimension` where  `Invoice For Partner`='No' and `Invoice Title`='Refund' %s ",$int[0]);
  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $refund_net=($row['net']==''?0:$row['net']);
    $refund_tax=($row['tax']==''?0:$row['tax']);
    $refund_invoices=$row['orders'];
    // get other refunds per geographical thing
    
    $sql=sprintf("select sum(`Invoice Refund Net Amount`) as net,sum(`Invoice Refund Tax Amount`) as tax,count(*) as orders from `Invoice Dimension` where  `Invoice For Partner`='No' and `Invoice Title`='Refund' and `Invoice Billing Country 2 Alpha Code`=%s  %s ",prepare_mysql($myconf['country_2acode']),$int[0]);
    //  print "$sql";
     $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $refund_net_home=($row['net']==''?0:$row['net']);
      $refund_tax_home=($row['tax']==''?0:$row['tax']);
      $refund_invoices_home=$row['orders'];
    }

  $sql=sprintf("select sum(`Invoice Refund Net Amount`) as net,sum(`Invoice Refund Tax Amount`) as tax,count(*) as orders from `Invoice Dimension` where  `Invoice For Partner`='No' and `Invoice Title`='Refund' and `Invoice Billing Country 2 Alpha Code`=%s  %s ",prepare_mysql('XX'),$int[0]);
    //  print "$sql";
     $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $refund_net_unk=($row['net']==''?0:$row['net']);
      $refund_tax_unk=($row['tax']==''?0:$row['tax']);
      $refund_invoices_unk=$row['orders'];
    }

  if(count($myconf['extended_home_2acode'])>1){
    $countries ='and `Invoice Billing Country 2 Alpha Code` in (';
    foreach($myconf['extended_home_2acode'] as $county_code){
      $countries.="'".$county_code."',";
    }
    $countries=preg_replace('/,$/',')',$countries);
 $sql=sprintf("select sum(`Invoice Refund Net Amount`) as net,sum(`Invoice Refund Tax Amount`) as tax,count(*) as orders from `Invoice Dimension` where  `Invoice For Partner`='No' and `Invoice Title`='Refund' %s %s  ",$countries,$int[0]);

    $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $refund_net_extended_home=($row['net']==''?0:$row['net']);
      $refund_tax_extended_home=($row['tax']==''?0:$row['tax']);
    }
  }
    $countries ='and `Invoice Billing Country 2 Alpha Code` in (';
    foreach($myconf['region_2acode'] as $county_id){
      $countries.="'".$county_id."',";
    }
    $countries=preg_replace('/,$/',')',$countries);
 $sql=sprintf("select sum(`Invoice Refund Net Amount`) as net,sum(`Invoice Refund Tax Amount`) as tax,count(*) as orders from `Invoice Dimension` where  `Invoice For Partner`='No' and `Invoice Title`='Refund' %s %s  ",$countries,$int[0]);
 // print "$sql";
    $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $refund_net_region=($row['net']==''?0:$row['net']);
      $refund_tax_region=($row['tax']==''?0:$row['tax']);
    }

   $countries ='and `Invoice Billing Country 2 Alpha Code` in (';
    foreach($myconf['continent_2acode'] as $county_code){
      $countries.="'".$county_code."',";
    }
    $countries=preg_replace('/,$/',')',$countries);
 $sql=sprintf("select sum(`Invoice Refund Net Amount`) as net,sum(`Invoice Refund Tax Amount`) as tax,count(*) as orders from `Invoice Dimension` where  `Invoice For Partner`='No' and `Invoice Title`='Refund' %s %s  ",$countries,$int[0]);
 // print "$sql";
    $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $refund_net_region2=($row['net']==''?0:$row['net']);
      $refund_tax_region2=($row['tax']==''?0:$row['tax']);
    }



  }


   $countries ='and `Invoice Billing Country 2 Alpha Code` in (';
    foreach($myconf['org_2acode'] as $county_code){
      $countries.="'".$county_code."',";
    }
    $countries=preg_replace('/,$/',')',$countries);
    $sql=sprintf("select sum(`Invoice Refund Net Amount`) as net,sum(`Invoice Refund Tax Amount`) as tax,count(*) as orders from `Invoice Dimension` where  `Invoice For Partner`='No' and `Invoice Title`='Refund' %s %s  ",$countries,$int[0]);
    // print "$sql";
    $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $refund_net_org=($row['net']==''?0:$row['net']);
      $refund_tax_org=($row['tax']==''?0:$row['tax']);
    }
  
  $refund_invoices_nohome=$refund_invoices-$refund_invoices_home-$refund_invoices_unk;
  $refund_net_nohome=$refund_net-$refund_net_home-$refund_net_unk;
  $refund_tax_nohome=$refund_tax-$refund_tax_home-$refund_tax_unk;
  $refund_net_extended_home_nohome=$refund_net_extended_home-$refund_net_home;
  $refund_tax_extended_home_nohome=$refund_tax_extended_home-$refund_tax_home;
  $refund_net_region_nohome=$refund_net_region-$refund_net_home;
  $refund_tax_region_nohome=$refund_tax_region-$refund_tax_home;
  $refund_net_region2_nohome=$refund_net_region2-$refund_net_home;
  $refund_tax_region2_nohome=$refund_tax_region2-$refund_tax_home;
  $refund_net_org_nohome=$refund_net_org-$refund_net_home;
  $refund_tax_org_nohome=$refund_tax_org-$refund_tax_home;







  // get sales and invoices

  // get data for parnerts

  $net_p=0;
  $tax_p=0;
  $invoices_p=0;
  $net_p_home=0;
  $tax_p_home=0;
  $invoices_p_home=0;

  $int=prepare_mysql_dates($from,$to,'`Invoice Date`','only_dates');
  $sql=sprintf("select sum(`Invoice Total Net Amount`) as net,sum(`Invoice Total Tax Amount`) as tax , count(*) as invoices from `Invoice Dimension` where `Invoice For Partner`='Yes' and `Invoice Title`='Invoice' %s ",$int[0]);

 $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $net_p=$row['net']+$refund_net_p;
    $tax_p=$row['tax']+$refund_tax_p;
    $invoices_p=$row['invoices'];
  }

  $sql=sprintf("select sum(`Invoice Total Net Amount`) as net,sum(`Invoice Total Tax Amount`) as tax , count(*) as invoices from `Invoice Dimension` where `Invoice For Partner`='Yes' and `Invoice Title`='Invoice' and `Invoice Billing Country 2 Alpha Code`=%s %s ",prepare_mysql($myconf['country_2acode']),$int[0]);
  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
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
  $net_notaxable_all=0; 
  $tax_notaxable_all=0; 
  $invoices_notaxable_all=0; 
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
  


 $sql=sprintf("select `Invoice Tax Code`,sum(`Invoice Total Net Amount`) as net,sum(`Invoice Total Tax Amount`) as tax , count(*) as invoices from `Invoice Dimension` where   `Invoice Taxable`='Yes'  %s group by `Invoice Tax Code` ",$int[0]);
  $result=mysql_query($sql);
  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $taxable[$row['Invoice Tax Code']]=array(
					      'sales'=>$row['net'],
					      'tax'=>$row['tax'], 
					      'invoices'=>$row['invoices']
					      ); 
    $net_taxable_all+=$row['net'];
    $tax_taxable_all+=$row['tax'];
    $invoices_taxable_all+=$row['invoices'];
  }
  
$sql=sprintf("select `Invoice Tax Code`,sum(`Invoice Total Net Amount`) as net,sum(`Invoice Total Tax Amount`) as tax , count(*) as invoices from `Invoice Dimension` where    `Invoice Taxable`='No'  %s group by `Invoice Tax Code` ",$int[0]);
  $result=mysql_query($sql);

if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $notaxable[$row['Invoice Tax Code']]=array(
					      'sales'=>$row['net'],
					      'tax'=>$row['tax'], 
					      'invoices'=>$row['invoices']
					      ); 
    $tax_notaxable_all+=$row['tax'];
    $net_notaxable_all+=$row['net'];
    $invoices_notaxable_all+=$row['invoices'];
  }


 
/*   if(is_array($valid_tax_rates)){ */

/*    $sql=sprintf("select ROUND(100*ifnull(`Invoice Total Tax Amount`,0)/`Invoice Total Net Amount`,1) as vat_rate,  sum(`Invoice Total Net Amount`) as net,sum(`Invoice Total Tax Amount`) as tax , count(*) as invoices from `Invoice Dimension` where  `Invoice Title`='Invoice' %s group by vat_rate",$int[0]); */
/*    //  print $sql; */
/*     $result=mysql_query($sql); */
/*   if($row=mysql_fetch_array($result, MYSQL_ASSOC)){ */
/* 	$net_taxable_all+=$row['net']; */
/* 	$tax_taxable_all+=$row['tax']; */
/* 	$invoices_taxable_all+=$row['invoices']; */
      
/* 	if($row['vat_rate']==''){ */
/* 	  $novalue_invoices=$row['invoices']; */

/* 	}else if(in_array($row['vat_rate'],$valid_tax_rates)){ */
/* 	  $index=number($closest_rate)."%"; */
/* 	  if(!isset($taxable[$index])) */
/* 	    $taxable[$index]=array( */
/* 				   'sales'=>$row['net'], */
/* 				   'tax'=>$row['tax'], */
/* 				   'invoices'=>$row['invoices'] */
/* 				   ); */
/* 	  else */
/* 	    $taxable[$index]=array( */
/* 				   'sales'=>$row['net']+$taxable[$index]['sales'], */
/* 				   'tax'=>$row['tax']+$taxable[$index]['tax'], */
/* 				   'invoices'=>$row['invoices']+$taxable[$index]['invoices'] */
/* 				   ); */
	  
	  

/* 	}else{ */
	  
/* 	  // chech each case */
/* 	  $_net_taxable=0; */
/* 	  $_tax_taxable=0; */
/* 	  $_invoices_taxable=0; */
/* 	  $errors=false; */
/* 	  $sql=sprintf("select public_id,net ,tax from orden   where  orden.tipo=2 and vateable=1 and ROUND(100*ifnull(tax,0)/net,1)=%.1f   %s  ",$row['vat_rate'],$int[0]); */
/* 	  //  print $sql." | ".$row['vat_rate']."   <br>"; */
/* 	  $res2 = $db->query($sql); */
/* 	  while($row2=$res2->fetchRow()) { */
/* 	    // print abs($avg_rate-$row['vat_rate'])." ".abs(1/$row2['net'])."<br>"; */
/* 	    $min_diff=0; */
/* 	    $closest_rate=false; */
/* 	    foreach($valid_tax_rates as $rate){ */
/* 	      $_min_diff=abs($rate-$row['vat_rate']); */
/* 	      if(!$closest_rate or $_min_diff<$min_diff){ */
/* 		$closest_rate=$rate; */
/* 		$min_diff=$_min_diff; */
/* 	      } */
/* 	    } */

/* 	    if($min_diff<abs(1/$row2['net']) ){ */
/* 	      if(!isset($taxable[number($closest_rate)."%"])) */
/* 		$taxable[number($closest_rate)."%"]=array( */
/* 							 'sales'=>$row2['net'], */
/* 							 'tax'=>$row2['tax'], */
/* 							 'invoices'=>1 */
/* 							 ); */
/* 	      else */
/* 		$taxable[number($closest_rate)."%"]=array( */
/* 							 'sales'=>$row2['net']+$taxable[number($closest_rate)."%"]['sales'], */
/* 							 'tax'=>$row2['tax']+$taxable[number($closest_rate)."%"]['tax'], */
/* 							 'invoices'=>1+$taxable[number($closest_rate)."%"]['invoices'] */
/* 							 ); */
	      

/* 	    }else{ */
/* 	      $errors=true; */
/* 	      $_net_taxable+=$row2['net']; */
/* 	      $_tax_taxable+=$row2['tax']; */
/* 	      $_invoices_taxable+=1; */
/* 	    } */
	    


/* 	  } */
/* 	  if($errors){ */
/* 	    //print "AQYUUUUUUUUUU" .$row['vat_rate']."<br>"; */
/* 	    $taxable_error[number($row['vat_rate'])."%"]=array( */
/* 						   'sales'=>money($_net_taxable), */
/* 						   'tax'=>money($_tax_taxable), */
/* 						   'invoices'=>number($_invoices_taxable) */
/* 						 ); */
/* 	  } */

/*       } */
/*     } */
    
/*   } */
  

/*  $sql=sprintf("select sum(net) as net,sum(tax) as tax , count(*) as invoices  from orden   where  orden.tipo=2 and vateable=0 and tax!=0 %s ",$int[0]); */
/*  // print $sql; */
/*  $res = $db->query($sql); */
/*  if($row=$res->fetchRow()) { */
   
/*    $net_notaxable_all+=$row['net'];  */
/*    $tax_notaxable_all+=$row['tax'];  */
/*    $invoices_notaxable_all+=$row['invoices'];  */

/*    $notaxable_error[]=array( */
/* 			    'sales'=>money($row['net']), */
/* 			    'tax'=>money($row['tax']), */
/* 			    'invoices'=>number($row['invoices']) */
/* 			    ); */
      
/*  } */
 
/*  $sql=sprintf("select sum(net) as net,sum(tax) as tax , count(*) as invoices  from orden   where  orden.tipo=2 and vateable=0 and tax=0 %s ",$int[0]); */
/*  $res = $db->query($sql); */
/*  if($row=$res->fetchRow()) { */
/*      $net_notaxable_all+=$row['net'];  */
/*    $tax_notaxable_all+=$row['tax'];  */
/*    $invoices_notaxable_all+=$row['invoices'];  */

/*     $notaxable[]=array( */
/* 		       'sales'=>$row['net'], */
/* 		       'tax'=>$row['tax'], */
/* 		       'invoices'=>$row['invoices'] */
/* 		       ); */
/*   } */

 // exit;
 //  print_r($taxable);
  $sql=sprintf("select sum(`Invoice Total Net Amount`) as net,sum(`Invoice Total Tax Amount`) as tax , count(*) as invoices,avg(`Invoice Dispatching Lag`) as dispatch_days from `Invoice Dimension` where `Invoice Title`='Invoice' and `Invoice For Partner`='No'   %s ",$int[0]);

  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
 
    $dispatch_days=$row['dispatch_days'];
    $net=$row['net']+$refund_net;
    $tax=$row['tax']+$refund_tax;
    $invoices=$row['invoices'];
  }



  $sql=sprintf("select sum(`Invoice Total Net Amount`) as net,sum(`Invoice Total Tax Amount`) as tax , count(*) as invoices,avg(`Invoice Dispatching Lag`) as dispatch_days from `Invoice Dimension`   where  `Invoice Title`='Invoice' and `Invoice For Partner`='No' and `Invoice Billing Country 2 Alpha Code`=%s %s ",prepare_mysql($myconf['country_2acode']),$int[0]);
  

    $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $dispatch_days_home=$row['dispatch_days'];
    $net_home=$row['net']+$refund_net_home;
    $tax_home=$row['tax']+$refund_tax_home;;
    $invoices_home=$row['invoices'];
  }


  $sql=sprintf("select sum(`Invoice Total Net Amount`) as net,sum(`Invoice Total Tax Amount`) as tax , count(*) as invoices,avg(`Invoice Dispatching Lag`) as dispatch_days from `Invoice Dimension`   where  `Invoice Title`='Invoice' and `Invoice For Partner`='No'  and `Invoice Billing Country 2 Alpha Code`!=%s   and `Invoice Billing Country 2 Alpha Code`!='XX'   %s ",prepare_mysql($myconf['country_2acode']),$int[0]);

  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
 
    $dispatch_days_nohome=$row['dispatch_days'];
    $net_nohome=$row['net']+$refund_net_nohome;
    $tax_nohome=$row['tax']+$refund_tax_nohome;;
    $invoices_nohome=$row['invoices'];
  }

$sql=sprintf("select sum(`Invoice Total Net Amount`) as net,sum(`Invoice Total Tax Amount`) as tax , count(*) as invoices,avg(`Invoice Dispatching Lag`) as dispatch_days from `Invoice Dimension`   where  `Invoice Title`='Invoice' and `Invoice For Partner`='No'  and `Invoice Billing Country 2 Alpha Code`='XX'   %s ",$int[0]);
//print $sql;
  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
 
    $dispatch_days_unk=$row['dispatch_days'];
    $net_unk=$row['net']+$refund_net_unk;
    $tax_unk=$row['tax']+$refund_tax_unk;;
    $invoices_unk=$row['invoices'];
  }



  //  print "$net $net_home $net_nohome ".($net-$net_home-$net_nohome)."\n";
  //print "$invoices $invoices_home $invoices_nohome ".($invoices-$invoices_home-$invoices_nohome)."\n";
  //exit;
  if(count($myconf['extended_home_2acode'])>1){

  $countries='and `Invoice Billing Country 2 Alpha Code` in (';
  foreach($myconf['extended_home_2acode'] as $county_id){
    $countries.="'".$county_id."',";
  }
  $countries=preg_replace('/\,$/',')',$countries);
 $sql=sprintf("select sum(`Invoice Total Net Amount`) as net,sum(`Invoice Total Tax Amount`) as tax , count(*) as invoices from `Invoice Dimension`     where `Invoice For Partner`='No' and `Invoice Title`='Invoice'   %s %s ",$countries,$int[0]);
 
  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){

    $net_extended_home=$row['net']+$refund_net_extended_home;
    $tax_extended_home=$row['tax']+$refund_tax_extended_home;
    $invoices_extended_home=$row['invoices'];
  }
  }
  $countries='and `Invoice Billing Country 2 Alpha Code` in (';
  foreach($myconf['region_2acode'] as $county_id){
    $countries.="'".$county_id."',";
  }
  $countries=preg_replace('/\,$/',')',$countries);
 $sql=sprintf("select sum(`Invoice Total Net Amount`) as net,sum(`Invoice Total Tax Amount`) as tax , count(*) as invoices from `Invoice Dimension` where  `Invoice For Partner`='No' and `Invoice Title`='Invoice'  %s %s ",$countries,$int[0]);
    $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $net_region=$row['net']+$refund_net_region;
    $tax_region=$row['tax']+$refund_tax_region;
    $invoices_region=$row['invoices'];
  }
 $countries='and `Invoice Billing Country 2 Alpha Code` in(';
  foreach($myconf['continent_2acode'] as $county_id){
    $countries.="'".$county_id."',";
  }
  $countries=preg_replace('/\,$/',')',$countries);
 $sql=sprintf("select sum(`Invoice Total Net Amount`) as net,sum(`Invoice Total Tax Amount`) as tax , count(*) as invoices from  `Invoice Dimension` where  `Invoice For Partner`='No' and `Invoice Title`='Invoice' %s %s ",$countries,$int[0]);
  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $net_region2=$row['net']+$refund_net_region;
    $tax_region2=$row['tax']+$refund_tax_region;
    $invoices_region2=$row['invoices'];
  }
$countries='and `Invoice Billing Country 2 Alpha Code` in (';
  foreach($myconf['org_2acode'] as $county_id){
    $countries.="'".$county_id."',";
  }
  $countries=preg_replace('/\,$/',')',$countries);
 $sql=sprintf("select sum(`Invoice Total Net Amount`) as net,sum(`Invoice Total Tax Amount`) as tax , count(*) as invoices from `Invoice Dimension` where  `Invoice For Partner`='No' and `Invoice Title`='Invoice'     %s %s ",$countries,$int[0]);
    $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
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


 /*  $sql=sprintf("select sum(net) as net , count(*) as orders from orden where  tipo=1 %s ",$int[0]); */
/*  //  print "$sql"; */
/*       $result=mysql_query($sql); */
/*   if($row=mysql_fetch_array($result, MYSQL_ASSOC)){ */
/*      $net_tobedone=$row['net']; */
/*      $tobedone=$row['orders']; */
/*    } */





   // export destinations
   $num_countries=0;
   $sql=sprintf("select count(*) as num from `Invoice Dimension` where  `Invoice For Partner`='No' and `Invoice Title`='Invoice' %s  group by `Invoice Billing Country 2 Alpha Code`   ",$int[0]);

   $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){

     $num_countries=$row['num'];
   }

   $top3=array();
   $sql=sprintf("select `Country Name`  as name, sum(`Invoice Total Net Amount`) as net ,sum(`Invoice Total Tax Amount`) as tax   from `Invoice Dimension` left join `Country Dimension` on (`Invoice Billing Country 2 Alpha Code`=`Country 2 Alpha Code`) where  `Invoice Billing Country 2 Alpha Code`!=%s  and `Invoice For Partner`='No' and `Invoice Title`='Invoice' %s group by `Invoice Billing Country 2 Alpha Code`  order by net     desc limit 3",prepare_mysql($myconf['country_2acode']),$int[0]);

     $result=mysql_query($sql);
  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     $top3[]=array('country'=>$row['name'],'net'=>$row['net'],'tax'=>$row['tax']);
   }
   $countries=array();
   $sql=sprintf("select `Country Key` as id,`Invoice Billing Country 2 Alpha Code` as  code2,`Country Name` as name, sum(`Invoice Total Net Amount`) as net ,sum(`Invoice Total Tax Amount`) as tax,count(*) as orders from `Invoice Dimension` left join `Country Dimension` on (`Invoice Billing Country 2 Alpha Code`=`Country 2 Alpha Code`) where  `Invoice Billing Country 2 Alpha Code`!=%s  and `Invoice For Partner`='No' and `Invoice Title`='Invoice' %s group by `Invoice Billing Country 2 Alpha Code`  order by net desc ",prepare_mysql($myconf['country_2acode']),$int[0]);
   //print $sql;
     $result=mysql_query($sql);
  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){

     // todo change to a info inside list_cumties
     
     if( in_array($row['code2'],$myconf['org_2acode']))
       $eu=1;
     else
       $eu=0;
     $countries[]=array('country'=>'<img src="art/flags/'.strtolower($row['code2']).'.gif">'.' '.$row['name'],'net'=>money($row['net']),'tax'=>money($row['tax']),'orders'=>$row['orders'],'share'=>percentage($row['net'],$net_nohome,2),'eu'=>$eu,'id'=>$row['id'],'name'=>$row['name'],'2acode'=>$row['code2']);
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


/*  $_int=preg_replace('/Invoice Date/','Order Date',$int[0]); */
/*  $sql=sprintf("select tipo,count(*) as num ,sum(net) as net  from orden where true %s group by tipo ",$_int); */
/*    $result=mysql_query($sql); */
/*   if($row=mysql_fetch_array($result, MYSQL_ASSOC)){ */
/*    $tipo=$row['tipo']; */
/*    switch($tipo){ */
/*    case 1: */
/*      $orders_todo_net=$row['net']; */
/*      $orders_todo=$row['num']; */
/*      break; */
/*    case 2: */
/*      $orders_invoices_net=$row['net']; */
/*      $orders_invoices=$row['num']; */
/*      break; */
/*    case 3: */
/*      $orders_cancelled_net=$row['net']; */
/*      $orders_cancelled=$row['num']; */
/*      break; */
/*    case 6: */
/*    case 7: */
/*      $orders_follows_net+=$row['net']; */
/*      $orders_follows+=$row['num']; */
/*      break; */
/*   case 5: */
/*      $orders_donations_net=$row['net']; */
/*      $orders_donations=$row['num']; */
/*      break; */
/*    default: */
/*      $orders_others_net=$row['net']; */
/*      $orders_others=$row['num']; */
/*    } */
/*  } */


 $balance['total']['net']=0;
 $balance['total']['net_charged']=0;
 $balance['total']['tax_charged']=0;
 $balance['total']['shipping']=0;
 $balance['total']['products']=0;
 $balance['total']['charges']=0;
 $balance['total']['orders']=0;
 $balance['total']['total']=0;
 $balance['total']['credit_net']=0;
 $balance['total']['tax']=0;
 $balance['total']['credit_tax']=0;
 $balance['total']['unk']=0;


 $balance['invoices']['net']=0;
 $balance['invoices']['net_charged']=0;
 $balance['invoices']['tax_charged']=0;
 $balance['invoices']['shipping']=0;
 $balance['invoices']['products']=0;
 $balance['invoices']['charges']=0;
 $balance['invoices']['orders']=0;
 $balance['invoices']['total']=0;
 $balance['invoices']['credit_net']=0;
 $balance['invoices']['tax']=0;
 $balance['invoices']['credit_tax']=0;
 $balance['invoices']['unk']=0;

 $balance['invoices_zero']['net']=0;
 $balance['invoices_zero']['net_charged']=0;
 $balance['invoices_zero']['tax_charged']=0;
 $balance['invoices_zero']['shipping']=0;
 $balance['invoices_zero']['charges']=0;
 $balance['invoices_zero']['orders']=0;
 $balance['invoices_zero']['total']=0;
 $balance['invoices_zero']['credit_net']=0;
 $balance['invoices_zero']['tax']=0;
 $balance['invoices_zero']['credit_tax']=0;
 $balance['invoices_zero']['products']=0;
 $balance['invoices_zero']['unk']=0;

 
 $balance['invoices_negative']['net']=0;
 $balance['invoices_negative']['net_charged']=0;
 $balance['invoices_negative']['tax_charged']=0;
 $balance['invoices_negative']['shipping']=0;
 $balance['invoices_negative']['charges']=0;
 $balance['invoices_negative']['orders']=0;
 $balance['invoices_negative']['total']=0;
 $balance['invoices_negative']['credit_net']=0;
 $balance['invoices_negative']['tax']=0;
 $balance['invoices_negative']['credit_tax']=0;
 $balance['invoices_negative']['unk']=0;

$balance['invoices_negative']['products']=0;
 $balance['replacements']['net']=0;

 $balance['replacements']['net_charged']=0;
 $balance['replacements']['tax_charged']=0;
 $balance['replacements']['shipping']=0;
 $balance['replacements']['charges']=0;
 $balance['replacements']['orders']=0;
 $balance['replacements']['total']=0;
 $balance['replacements']['credit_net']=0;
 $balance['replacements']['tax']=0;
 $balance['replacements']['credit_tax']=0;
 $balance['replacements']['products']=0;
 $balance['replacements']['unk']=0;

 $balance['donation']['net']=0;
 $balance['donation']['net_charged']=0;
 $balance['donation']['tax_charged']=0;
 $balance['donation']['shipping']=0;
 $balance['donation']['charges']=0;
 $balance['donation']['orders']=0;
 $balance['donation']['total']=0;
 $balance['donation']['credit_net']=0;
 $balance['donation']['tax']=0;
 $balance['donation']['credit_tax']=0;
$balance['donation']['products']=0;
$balance['donation']['unk']=0;

 $balance['followup']['net']=0;
 $balance['followup']['net_charged']=0;
 $balance['followup']['tax_charged']=0;
 $balance['followup']['shipping']=0;
 $balance['followup']['charges']=0;
 $balance['followup']['orders']=0;
 $balance['followup']['total']=0;
 $balance['followup']['credit_net']=0;
 $balance['followup']['tax']=0;
 $balance['followup']['credit_tax']=0;
$balance['followup']['products']=0;
$balance['followup']['unk']=0;

$balance['shortage']['net']=0;
$balance['shortage']['net_charged']=0;
 $balance['shortage']['tax_charged']=0;
 $balance['shortage']['shipping']=0;
 $balance['shortage']['charges']=0;
 $balance['shortage']['orders']=0;
 $balance['shortage']['total']=0;
 $balance['shortage']['credit_net']=0;
 $balance['shortage']['tax']=0;
 $balance['shortage']['credit_tax']=0;
$balance['shortage']['products']=0;
$balance['shortage']['unk']=0;

$balance['samples']['net']=0;
$balance['samples']['net_charged']=0;
 $balance['samples']['tax_charged']=0;
 $balance['samples']['shipping']=0;
 $balance['samples']['charges']=0;
 $balance['samples']['orders']=0;
 $balance['samples']['total']=0;
 $balance['samples']['credit_net']=0;
 $balance['samples']['tax']=0;
 $balance['samples']['credit_tax']=0;
$balance['samples']['products']=0;
$balance['samples']['unk']=0;

$balance['refund']['credit_net']=0;
$balance['refund']['credit_tax']=0;
$balance['refund']['orders']=0;
$balance['refund']['total']=0;
$balance['refund']['net']=0;
$balance['refund']['tax']=0;
$balance['refund']['unk']=0;



$balance['refund_error']['total']=0;
$balance['refund_error']['credit_net']=0;
$balance['refund_error']['credit_tax']=0;
$balance['refund_error']['orders']=0;

$balance['credits']['products']=0;
$balance['credits']['shipping']=0;
$balance['credits']['charges']=0;
$balance['credits']['unk']=0;
$balance['credits']['orders']=0;
$balance['credits']['total']=0;
$balance['credits']['net']=0;
$balance['credits']['tax']=0;


/* $_int=preg_replace('/date_index/','date_done',$int[0]); */
/*  $sql=sprintf("select count(*) as orders,sum(value_net) as net, sum(value_net*ifnull(rate,0)) as tax  from debit left join tax_code on (debit.tax_code=tax_code.code)    where tipo=4 and order_affected_id is not null %s  ",$_int); */
/*  // print $sql; */
/*   $result=mysql_query($sql); */
/*   if($row=mysql_fetch_array($result, MYSQL_ASSOC)){ */
/*     $balance['refund']['credit_net']=$row['net']; */
/*     $balance['refund']['credit_tax']=$row['tax']; */
/*         $balance['refund']['total']=$row['tax']+$row['net']; */

/*     $balance['refund']['orders']=$row['orders']; */
/*  } */

/* $sql=sprintf("select count(*) as orders,sum(value_net) as net, sum(value_net*ifnull(rate,0)) as tax  from debit left join tax_code on (debit.tax_code=tax_code.code)    where tipo=4 and order_affected_id is null %s  ",$_int); */
/*  //  print $sql; */
/* $res = $db->query($sql); */
/*  if($row=$res->fetchRow()) { */
/*     $balance['refund_error']['credit_net']=$row['net']; */
/*     $balance['refund_error']['credit_tax']=$row['tax']; */
/*     $balance['refund_error']['total']=$row['tax']+$row['net']; */

/*     $balance['refund_error']['orders']=$row['orders']; */
/*  } */


  $sql=sprintf("select sum(`Invoice Refund Unknown Net Amount`) as net_refund_unk,sum(`Invoice Refund Items Net Amount`) as net_refund_items,sum(`Invoice Refund Shipping Net Amount`) as net_refund_shipping, sum(`Invoice Refund Charges Net Amount`) as net_refund_charges,`Invoice Title` as tipo,count(*) as orders, sum(`Invoice Shipping Net Amount`) as shipping,sum(`Invoice Charges Net Amount`) as charges,sum(`Invoice Total Amount`) as total,sum(`Invoice Refund Net Amount`) as credit_net,sum(`Invoice Total Net Amount`) as net,sum(`Invoice Total Tax Amount`) as tax, sum(`Invoice Refund Tax Amount`) as credit_tax,sum(`Invoice Items Net Amount`) as products from `Invoice Dimension` where true  %s group by `Invoice Title` ",$int[0]);
  //print $sql;
  $result=mysql_query($sql);
  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
   $tipo=$row['tipo'];

   switch($tipo){
   case('Refund'):
     $balance['refund']['orders']=$row['orders'];
     $balance['refund']['total']=$row['total'];
     $balance['refund']['credit_tax']=$row['tax'];
     $balance['refund']['credit_net']=$row['net'];
     $balance['refund']['products']=$row['net_refund_items'];
     $balance['refund']['shipping']=$row['net_refund_shipping'];
     $balance['refund']['charges']=$row['net_refund_charges'];
     $balance['refund']['unk']=$row['net_refund_unk'];
     $balance['refund']['net']=$row['net'];
     $balance['refund']['tax']=$row['tax'];

     

     break;
   case ('Invoice'):
     if($row['total']>0){
       
       if($row['credit_net']!=0 or $row['credit_tax']!=0){
	 $balance['credits']['orders']++;
	 
	 

       
       $balance['credits']['products']+=$row['net_refund_items'];
       $balance['credits']['shipping']+=$row['net_refund_shipping'];
       $balance['credits']['charges']+=$row['net_refund_charges'];
       $balance['credits']['unk']+=$row['net_refund_unk'];
       $balance['credits']['tax']+=$row['credit_tax'];
       $balance['credits']['net']+=$row['credit_net'];
       $balance['credits']['total']+=($row['credit_net']+$row['credit_tax']);
       }


       $balance['invoices']['net']+=$row['net']-$row['credit_net'];
       $balance['invoices']['net_charged']+=$row['net'];
       $balance['invoices']['tax_charged']+=$row['tax'];

       $balance['invoices']['tax']+=($row['tax']-$row['credit_tax']);
       $balance['invoices']['shipping']+=$row['shipping'];
       $balance['invoices']['charges']+=$row['charges'];

       $balance['invoices']['orders']+=$row['orders'];
       $balance['invoices']['total']+=$row['total'];
       $balance['invoices']['products']+=$row['products'];

       $balance['invoices']['credit_net']+=$row['credit_net'];
       $balance['invoices']['credit_tax']+=$row['credit_tax'];


     }elseif($row['total']==0){
       $balance['invoices_zero']['net']+=$row['net']-$row['credit_net'];
       $balance['invoices_zero']['net_charged']+=$row['net'];
       $balance['invoices_zero']['tax_charged']+=$row['tax'];

       $balance['invoices_zero']['tax']+=($row['tax']-$row['credit_tax']);
       $balance['invoices_zero']['shipping']+=$row['shipping'];
       $balance['invoices_zero']['charges']+=$row['charges'];

       $balance['invoices_zero']['orders']+=1;
       $balance['invoices_zero']['total']+=$row['total'];
       $balance['invoices_zero']['products']+=$row['products'];

       $balance['invoices_zero']['credit_net']+=$row['credit_net'];
       $balance['invoices_zero']['credit_tax']+=$row['credit_tax'];


     }else{
       $balance['invoices_negative']['net']+=$row['net']-$row['credit_net'];
       $balance['invoices_negative']['net_charged']+=$row['net'];
       $balance['invoices_negative']['tax_charged']+=$row['tax'];

       $balance['invoices_negative']['tax']+=($row['tax']-$row['credit_tax']);
       $balance['invoices_negative']['shipping']+=$row['shipping'];
       $balance['invoices_negative']['charges']+=$row['charges'];

       $balance['invoices_negative']['orders']+=1;
       $balance['invoices_negative']['total']+=$row['total'];
       $balance['invoices_negative']['products']+=$row['products'];

       $balance['invoices_negative']['credit_net']+=$row['credit_net'];
       $balance['invoices_negative']['credit_tax']+=$row['credit_tax'];

     }
   }
 }

  

  $_int=preg_replace('/Invoice Date/','Order Date',$int[0]); 
  
  
  
  $sql=sprintf("select count(*) as num ,sum(`Order Total Net Amount`) as net  from `Order Dimension` where true %s ",$_int);
  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $orders_total_net=$row['net'];
    $orders_total=$row['num'];
  }

  $orders_state=array();
  $sql=sprintf("Select count(*) as orders ,`Order Current Dispatch State` as state,sum(`Order Total Net Amount`) as net ,sum(`Order Total Net Potential Amount`) as net_potential,sum(`Order Net Balance Amount`) as net_balance from `Order Dimension` where true  %s group by state",$_int);
  //print $sql;
  $result=mysql_query($sql);
  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $orders_state[$row['state']]=array(
				       'orders'=>number($row['orders'])
				       ,'orders_percentage'=>percentage($row['orders'],$orders_total)
				       ,'net_chargable'=>money($row['net'])
				       ,'net_potential'=>money($row['net_potential'])
				       ,'net_paid'=>money($row['net']-$row['net_balance'])
				       ,'net_topay'=>money($row['net_balance'])
				       ,'net_lost'=>money($row['net']-$row['net_potential'])
				       
				       );
  };
  

  


/*      break; */
/*    case(6): */
     
/*      $balance['replacements']['net']+=$row['net']-$row['credit_net']; */
/*       $balance['replacements']['net_charged']+=$row['net']; */
/*       $balance['replacements']['tax_charged']+=$row['tax']; */
/*       $balance['replacements']['shipping']+=$row['shipping']; */
/*       $balance['replacements']['charges']+=$row['charges']; */
/*       $balance['replacements']['orders']+=1; */
/*       $balance['replacements']['total']+=$row['total']; */
/*       $balance['replacements']['products']+=$row['products']; */
/*       break; */
/*   case(7): */
/*     $balance['shortage']['net']+=$row['net']-$row['credit_net']; */
/*     $balance['shortage']['net_charged']+=$row['net']; */
/*     $balance['shortage']['tax_charged']+=$row['tax']; */
/*     $balance['shortage']['shipping']+=$row['shipping']; */
/*       $balance['shortage']['charges']+=$row['charges']; */
/*       $balance['shortage']['orders']+=1; */
/*       $balance['shortage']['total']+=$row['total']; */
/*       $balance['shortage']['products']+=$row['products']; */
/*       break; */
/*  case(8): */
       
/*     $balance['followup']['net']+=$row['net']-$row['credit_net']; */

/*    $balance['followup']['net_charged']+=$row['net']; */
/*       $balance['followup']['tax_charged']+=$row['tax']; */
/*       $balance['followup']['shipping']+=$row['shipping']; */
/*       $balance['followup']['charges']+=$row['charges']; */
/*       $balance['followup']['orders']+=1; */
/*       $balance['followup']['total']+=$row['total']; */
/*       $balance['followup']['products']+=$row['products']; */
/*       break; */
/*  case(5): */
/*        $balance['donation']['net']+=$row['net']-$row['credit_net']; */

/*       $balance['donation']['net_charged']+=$row['net']; */
/*       $balance['donation']['tax_charged']+=$row['tax']; */
/*       $balance['donation']['shipping']+=$row['shipping']; */
/*       $balance['donation']['charges']+=$row['charges']; */
/*       $balance['donation']['orders']+=1; */
/*       $balance['donation']['total']+=$row['total']; */
/*       $balance['donation']['products']+=$row['products']; */
/*       break; */
/*  case(4): */
/*           $balance['samples']['net']+=$row['net']-$row['credit_net']; */

/*       $balance['samples']['net_charged']+=$row['net']; */
/*       $balance['samples']['tax_charged']+=$row['tax']; */
/*       $balance['samples']['shipping']+=$row['shipping']; */
/*       $balance['samples']['charges']+=$row['charges']; */
/*       $balance['samples']['orders']+=1; */
/*       $balance['samples']['total']+=$row['total']; */
/*       $balance['samples']['products']+=$row['products']; */
/*       break; */
/*    } */
/*  } */
 $tags=array('orders','total','credit_net','credit_tax');
 foreach($tags as $key){
   $balance['total'][$key]= 
     $balance['refund'][$key]+
     $balance['refund_error'][$key]+
     $balance['invoices'][$key]+
     $balance['invoices_zero'][$key]+
     $balance['invoices_negative'][$key]+
     $balance['donation'][$key]+
     $balance['samples'][$key]+
     $balance['followup'][$key]+
     $balance['replacements'][$key]+
     $balance['shortage'][$key];
   

 }
 $tags=array('net','shipping','products','charges','orders','unk');
 foreach($tags as $key){
   $balance['subtotal'][$key]=$balance['invoices'][$key]+
     $balance['invoices_zero'][$key]+
     $balance['invoices_negative'][$key]+
     $balance['donation'][$key]+
     $balance['samples'][$key]+
     $balance['followup'][$key]+
     $balance['replacements'][$key]+
     $balance['shortage'][$key];
 }
 $balance['total']['net_balance']=$balance['subtotal']['net']+$balance['credits']['net']+$balance['refund']['net'];
 $tags=array('net_charged','net','tax_charged','shipping','products','charges','tax');

foreach($tags as $key){
   $balance['total'][$key]= 
     $balance['invoices'][$key]+
     $balance['invoices_zero'][$key]+
     $balance['invoices_negative'][$key]+
     $balance['donation'][$key]+
     $balance['samples'][$key]+
     $balance['followup'][$key]+
     $balance['replacements'][$key]+
     $balance['shortage'][$key];
  }

 $balance['refund']['orders']=number($balance['refund']['orders']);
 $balance['refund']['total']=money($balance['refund']['total']);
 $balance['refund']['credit_net']=money( $balance['refund']['credit_net']);
 $balance['refund']['credit_tax']=money($balance['refund']['credit_tax']);


 $balance['refund_error']['orders']=number($balance['refund_error']['orders']);
 $balance['refund_error']['total']=money($balance['refund_error']['total']);
 $balance['refund_error']['credit_net']=money( $balance['refund_error']['credit_net']);
 $balance['refund_error']['credit_tax']=money($balance['refund_error']['credit_tax']);

 $balance['invoices']['net_charged']=money($balance['invoices']['net_charged']);
 $balance['invoices']['net']=money($balance['invoices']['net']);
 $balance['invoices']['tax_charged']= money($balance['invoices']['tax_charged']);
 $balance['invoices']['shipping']=money($balance['invoices']['shipping']);
 $balance['invoices']['products']=money($balance['invoices']['products']);
 $balance['invoices']['charges']=money($balance['invoices']['charges']);
 $balance['invoices']['orders']=number($balance['invoices']['orders']);
 $balance['invoices']['total']=money($balance['invoices']['total']);
 $balance['invoices']['credit_net']=money( $balance['invoices']['credit_net']);
 $balance['invoices']['tax']=money($balance['invoices']['tax']);
 $balance['invoices']['credit_tax']=money($balance['invoices']['credit_tax']);
 $balance['invoices']['unk']=money($balance['invoices']['unk']);

 $balance['credits']['net']=money($balance['credits']['net']);
 $balance['credits']['tax']=money($balance['credits']['tax']);
 $balance['credits']['total']=money($balance['credits']['total']);
 $balance['credits']['shipping']=money($balance['credits']['shipping']);
 $balance['credits']['charges']=money($balance['credits']['charges']);
 $balance['credits']['unk']=money($balance['credits']['unk']);
 $balance['credits']['products']=money($balance['credits']['products']);

 $balance['subtotal']['net']=money($balance['subtotal']['net']);


 $balance['subtotal']['shipping']=money($balance['subtotal']['shipping']);
 $balance['subtotal']['charges']=money($balance['subtotal']['charges']);
 $balance['subtotal']['unk']=money($balance['subtotal']['unk']);
 $balance['subtotal']['products']=money($balance['subtotal']['products']);
 $balance['subtotal']['orders']=number($balance['subtotal']['orders']);


 $balance['invoices_zero']['net_charged']=money($balance['invoices_zero']['net_charged']);
 $balance['invoices_zero']['net']=money($balance['invoices_zero']['net']);
 $balance['invoices_zero']['tax_charged']= money($balance['invoices_zero']['tax_charged']);
 $balance['invoices_zero']['shipping']=money($balance['invoices_zero']['shipping']);
 $balance['invoices_zero']['products']=money($balance['invoices_zero']['products']);
 $balance['invoices_zero']['charges']=money($balance['invoices_zero']['charges']);
 $balance['invoices_zero']['orders']=number($balance['invoices_zero']['orders']);
 $balance['invoices_zero']['total']=money($balance['invoices_zero']['total']);
 $balance['invoices_zero']['credit_net']=money( $balance['invoices_zero']['credit_net']);
 $balance['invoices_zero']['tax']=money($balance['invoices_zero']['tax']);
 $balance['invoices_zero']['credit_tax']=money($balance['invoices_zero']['credit_tax']);
 $balance['invoices_zero']['unk']=money($balance['invoices_zero']['unk']);

 $balance['invoices_negative']['net_charged']=money($balance['invoices_negative']['net_charged']);
 $balance['invoices_negative']['net']=money($balance['invoices_negative']['net']);
 $balance['invoices_negative']['tax_charged']= money($balance['invoices_negative']['tax_charged']);
 $balance['invoices_negative']['shipping']=money($balance['invoices_negative']['shipping']);
 $balance['invoices_negative']['products']=money($balance['invoices_negative']['products']);
 $balance['invoices_negative']['charges']=money($balance['invoices_negative']['charges']);
 $balance['invoices_negative']['orders']=number($balance['invoices_negative']['orders']);
 $balance['invoices_negative']['total']=money($balance['invoices_negative']['total']);
 $balance['invoices_negative']['credit_net']=money( $balance['invoices_negative']['credit_net']);
 $balance['invoices_negative']['tax']=money($balance['invoices_negative']['tax']);
 $balance['invoices_negative']['credit_tax']=money($balance['invoices_negative']['credit_tax']);
 $balance['invoices_negative']['unk']=money($balance['invoices_negative']['unk']);

$balance['donation']['net_charged']=money($balance['donation']['net_charged']);
 $balance['donation']['net']=money($balance['donation']['net']);
 $balance['donation']['tax_charged']= money($balance['donation']['tax_charged']);
 $balance['donation']['shipping']=money($balance['donation']['shipping']);
 $balance['donation']['products']=money($balance['donation']['products']);
 $balance['donation']['charges']=money($balance['donation']['charges']);
 $balance['donation']['orders']=number($balance['donation']['orders']);
 $balance['donation']['total']=money($balance['donation']['total']);
 $balance['donation']['credit_net']=money( $balance['donation']['credit_net']);
 $balance['donation']['tax']=money($balance['donation']['tax']);
 $balance['donation']['credit_tax']=money($balance['donation']['credit_tax']);
 $balance['donation']['unk']=money($balance['donation']['unk']);

 $balance['shortage']['net_charged']=money($balance['shortage']['net_charged']);
 $balance['shortage']['net']=money($balance['shortage']['net']);
 $balance['shortage']['tax_charged']= money($balance['shortage']['tax_charged']);
 $balance['shortage']['shipping']=money($balance['shortage']['shipping']);
 $balance['shortage']['products']=money($balance['shortage']['products']);
 $balance['shortage']['charges']=money($balance['shortage']['charges']);
 $balance['shortage']['orders']=number($balance['shortage']['orders']);
 $balance['shortage']['total']=money($balance['shortage']['total']);
 $balance['shortage']['credit_net']=money( $balance['shortage']['credit_net']);
 $balance['shortage']['tax']=money($balance['shortage']['tax']);
 $balance['shortage']['credit_tax']=money($balance['shortage']['credit_tax']);
 $balance['shortage']['unk']=money($balance['shortage']['unk']);


$balance['replacements']['net_charged']=money($balance['replacements']['net_charged']);
 $balance['replacements']['net']=money($balance['replacements']['net']);
 $balance['replacements']['tax_charged']= money($balance['replacements']['tax_charged']);
 $balance['replacements']['shipping']=money($balance['replacements']['shipping']);
 $balance['replacements']['products']=money($balance['replacements']['products']);
 $balance['replacements']['charges']=money($balance['replacements']['charges']);
 $balance['replacements']['orders']=number($balance['replacements']['orders']);
 $balance['replacements']['total']=money($balance['replacements']['total']);
 $balance['replacements']['credit_net']=money( $balance['replacements']['credit_net']);
 $balance['replacements']['tax']=money($balance['replacements']['tax']);
 $balance['replacements']['credit_tax']=money($balance['replacements']['credit_tax']);
 $balance['replacements']['unk']=money($balance['replacements']['unk']);


 $balance['followup']['net_charged']=money($balance['followup']['net_charged']);
 $balance['followup']['net']=money($balance['followup']['net']);
 $balance['followup']['tax_charged']= money($balance['followup']['tax_charged']);
 $balance['followup']['shipping']=money($balance['followup']['shipping']);
 $balance['followup']['products']=money($balance['followup']['products']);
 $balance['followup']['charges']=money($balance['followup']['charges']);
 $balance['followup']['orders']=number($balance['followup']['orders']);
 $balance['followup']['total']=money($balance['followup']['total']);
 $balance['followup']['credit_net']=money( $balance['followup']['credit_net']);
 $balance['followup']['tax']=money($balance['followup']['tax']);
 $balance['followup']['credit_tax']=money($balance['followup']['credit_tax']);
 $balance['followup']['unk']=money($balance['followup']['unk']);

$balance['total']['net_charged']=money($balance['total']['net_charged']);
 $balance['total']['net']=money($balance['total']['net']);
 $balance['total']['tax_charged']= money($balance['total']['tax_charged']);
 $balance['total']['shipping']=money($balance['total']['shipping']);
 $balance['total']['products']=money($balance['total']['products']);
 $balance['total']['charges']=money($balance['total']['charges']);
 $balance['total']['orders']=number($balance['total']['orders']);
 $balance['total']['total']=money($balance['total']['total']);
 $balance['total']['credit_net']=money( $balance['total']['credit_net']);
 $balance['total']['tax']=money($balance['total']['tax']);
 $balance['total']['credit_tax']=money($balance['total']['credit_tax']);
 $balance['total']['unk']=money($balance['total']['unk']);
 $balance['total']['net_balance']=money($balance['total']['net_balance']);

$balance['samples']['net_charged']=money($balance['samples']['net_charged']);
 $balance['samples']['net']=money($balance['samples']['net']);
 $balance['samples']['tax_charged']= money($balance['samples']['tax_charged']);
 $balance['samples']['shipping']=money($balance['samples']['shipping']);
 $balance['samples']['products']=money($balance['samples']['products']);
 $balance['samples']['charges']=money($balance['samples']['charges']);
 $balance['samples']['orders']=number($balance['samples']['orders']);
 $balance['samples']['total']=money($balance['samples']['total']);
 $balance['samples']['credit_net']=money( $balance['samples']['credit_net']);
 $balance['samples']['tax']=money($balance['samples']['tax']);
 $balance['samples']['credit_tax']=money($balance['samples']['credit_tax']);
 $balance['samples']['unk']=money($balance['samples']['unk']);



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
		 'refund_nohome'=>$refund_net_nohome+$refund_tax_nohome,
		 'refund_unk'=>$refund_net_unk+$refund_tax_unk

		 );
  $other_data=array(
		    'dispatch_days'=>$dispatch_days,
		    'dispatch_days_home'=>$dispatch_days_home,
		    'dispatch_days_nohome'=>$dispatch_days_nohome,
		    );
  

  $sales=array(
	       'net_taxable_all'=>$net_taxable_all,
	       'tax_taxable_all'=>$tax_taxable_all,
	       'net_notaxable_all'=>$net_notaxable_all,
	       'tax_notaxable_all'=>$tax_notaxable_all,
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
	       'net_unk'=>$net_unk,
	       'tax_unk'=>$tax_unk,
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
		  'invoices_notaxeable_all'=>$invoices_notaxable_all,
		  'invoices_taxeable'=>$invoices_taxable,
		  'invoices'=>$invoices,
		 'invoices_home'=>$invoices_home,
		  'invoices_unk'=>$invoices_unk,
		  
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
		  'total_invoices_nohome'=>$invoices_nohome+$invoices_p_nohome,
		  'refund_invoices'=>$refund_invoices,
		  'refund_invoices_home'=>$refund_invoices_home,
		  'refund_invoices_unk'=>$refund_invoices_unk,
		  'refund_invoices_p'=>$refund_invoices_p,
		  'refund_invoices_nohome'=>$refund_invoices_nohome

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
  return array('invoices'=>$invoices,'sales'=>$sales,'refunds'=>$refunds,'orders'=>$orders,'exports'=>$exports,'other_data'=>$other_data,'errors'=>$errors,'taxable'=>$taxable,'notaxable'=>$notaxable,'balance'=>$balance,'orders_state'=>$orders_state);


}


?>