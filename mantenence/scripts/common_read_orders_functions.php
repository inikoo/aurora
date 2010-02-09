<?php 
function get_dates($filedate,$header_data,$tipo_order,$new_file=true){

  $datetime_updated=date("Y-m-d H:i:s",$filedate);
  $time_updated_menos30min=date("H:i:s",$filedate-1800);

  list($date_updated,$time_updated)=preg_split('/\s/',$datetime_updated);
  if($new_file){
    if($tipo_order==2  or $tipo_order==6 or $tipo_order==7 or $tipo_order==9  or $tipo_order==8   ){
      
      //print_r($header_data);
      if($header_data['date_inv']=='' or $header_data['date_inv']=='1970-01-01')
	$header_data['date_inv']=$header_data['date_order'];
      
      if($date_updated ==$header_data['date_inv']){
	
	$date_charged=$date_updated." ".$time_updated;

	$date_processed=$header_data['date_order']." 09:30:00";
	if(strtotime($date_processed)>strtotime($date_charged))
	  $date_processed=$header_data['date_order']." ".$time_updated_menos30min;

      }else{
	$date_charged=$header_data['date_inv']." 16:30:00";
	$date_processed=$header_data['date_order']." 09:30:00";
      }
      $date_index=$date_charged;
    }else{


      $date_charged="NULL";



      if($date_updated ==$header_data['date_order']){
	//print $header_data['date_order']." xssssssssssssxx";
	$date_processed=$date_updated." ".$time_updated;
	// print "$date_processed  xssssssssssssxx\n";

      }
      else
	$date_processed=$header_data['date_order']." 08:30:00";
      $date_index=$date_processed;

      // print $date_index." xxx\n";

    }
  }
  //  print "$date_index,$date_processed,$date_charged\n";
  return array($date_index,$date_processed,$date_charged);

}

function act_transformations($act_data){

  // $act_data['contact']=str_replace("\"",'',$act_data['contact']);
  //$act_data['name']=str_replace("\"",'',$act_data['name']);

  //act_data['contact']=preg_replace("/(\\\"|\\\')/",$act_data['contact'],'x');
  $act_data['name']=preg_replace("/\"/",' ',$act_data['name']);
  $act_data['contact']=preg_replace("/\"/",' ',$act_data['contact']);
  $act_data['name']=preg_replace("/\'\'/",' ',$act_data['name']);
  $act_data['contact']=preg_replace("/\'\'/",' ',$act_data['contact']);
  $act_data['contact']=_trim($act_data['contact']);
  $act_data['name']=_trim($act_data['name']);


if($act_data['name']=='Cleaners (Mrs Mop)' and $act_data['contact']=='' ){
$act_data['name']='Cleaners';
$act_data['contact']='Mrs Mop';
}
if($act_data['name']=='Michelle A(aromatherapy,indian Head Massage Therap'){
    $act_data['name']='Michelle Angus';
  }

 if($act_data['name']=="'magpies'"){
    $act_data['name']='magpies';
  }

  if(preg_match('/Stinkers.*duglas laver/i',$act_data['name'])){
    $act_data['name']='Stinkers';
    if($act_data['contact']=='')
      $act_data['contact']='Duglas Laver';
  }

  // print_r($act_data);

  if(preg_match('/J.t Tools.*Mr.*a.*Hammans/i',$act_data['name'])){
    //   print "yyy\n";
    $act_data['name']='J&t Tools';
    if($act_data['contact']=='')
      $act_data['contact']='Anthiny Hammans';


  }
  
  if($act_data['country']=='Norway'  and 
     (
      $act_data['a1']=='Postboks 407'  
      or $act_data['a2']=='Postboks 407' 
      or $act_data['a3']=='Postboks 407') 
     ){
    $act_data['town']='Straume';
    $act_data['postcode']='5343';
 $skip_del_address=true;
  }


 if($act_data['country']=='Norway'  and 
     (
      $act_data['a1']=='Straumsfjellsvegen 9'  
      or $act_data['a3']=='Straumsfjellsvegen 9' 
      or $act_data['a2']=='Straumsfjellsvegen 9') 
     ){
    $skip_del_address=true;
    $act_data['town']='Straume';
    $act_data['postcode']='5343';

  }
 
 if(
    (
     preg_match('/^Via Bssa$|Via Bssa.*11/i',$act_data['a1'])
     or  preg_match('/^Via Bssa$|Via Bssa.*11/i',$act_data['a2'])
     or  preg_match('/^Via Bssa$|Via Bssa.*11/i',$act_data['a3'])
     )

) { 
      
      $act_data['town']='Mestre';
      $act_data['postcode']='30173';
      $act_data['country_d1']=''; 
      $act_data['country_d2']=''; 
      $act_data['a1']='Via Bssa, 11'; 
      $act_data['a2']='';
      $act_data['a3']='';
   }



  if($act_data['town']=='Korea South' and $act_data['country']=='' ){
    $act_data['country']='Korea South'; 
    $act_data['town']='';

    if($act_data['a3']=='Seoul'){
      $act_data['town']='Seoul';
      $act_data['a3']='';}
  }

  if($act_data['postcode']=='Korea South' and ($act_data['country']=='' or $act_data['country']=='Korea South' ) ){
    $act_data['country']='Korea South'; 
    $act_data['postcode']='';

    if($act_data['a3']=='Seoul'){
      $act_data['town']='Seoul';
      $act_data['a3']='';}
  }


  if(preg_match('/^eire$/i',$act_data['postcode'])){
    $act_data['country']='Ireland'; 
    $act_data['postcode']='';

  }

if(preg_match('/^524 95 Ljung$/i',$act_data['town']) and $act_data['postcode']=''){
    $act_data['town']='Ljung$'; 
    $act_data['postcode']='52495';

  }



  if($act_data['name']=='Incensed ! / Sarah Ismaeel'){
    $act_data['name']='Incensed';
  }
  if($act_data['name']=="Wax N Wicca" or $act_data['name']=="Wax 'N' Wicca"){
    $act_data['name']="Wax 'n' Wicca";
    $act_data['act']='32279';
  }

  if($act_data['name']=="Attah-Hicks" or $act_data['act']=="32437"){
    $act_data['act']='29980';
  }

  if($act_data['name']=="Wax 'n' Wicca" and $act_data['contact']='P Lewis'){
    $act_data['contact']="Pam Lewis";
    $act_data['first_name']="Pam";
  }

  if(preg_match('/\(.+\)/i',$act_data['name'],$match)){
    $_contact=preg_replace('/^\(|\)$/i','',$match[0]);
    // print "$_contact\n";
    if(strtolower($_contact)==strtolower($act_data['contact'])){
      $act_data['name']=preg_replace('/\(.+\)/i','',$act_data['name']);
    }
  }
  if(preg_match('/^M/i',$act_data['postcode']) and $act_data['town']=='Manchester'){
    $act_data['country']='UK';
  }

 if($act_data['a1']=='Sharn Brook' and $act_data['town']==''){
    $act_data['a1']='NULL';
    $act_data['town']='Sharnbrook';
  }


  if($act_data['a2']=='Dhahran' and $act_data['town']=='East Province'){
    $act_data['a2']='';
    $act_data['town']='Dhahran';



  }

  if( preg_match('/^belfast\s*,/i',$act_data['town'])){
    $act_data['town']='Belfast';
  }
  if( preg_match('/^via cork$/i',$act_data['town'])){
    $act_data['town']='';
  }


  if( preg_match('/^co\.? (Westmeath|Meath)$/i',$act_data['town'])){
    $act_data['town']='';
  }


 //  print "xxxxxxx $different_delivery_address xxxxxxxxxxxxxx";
  //exit;
  // Ok in some cases the country is in the post code so try to get it

  if($act_data['country']==''){


    if(preg_match('/spain\s*.\s*ibiza/i',$act_data['postcode'])){
      $act_data['country']='Spain';
      $act_data['postcode']='';
      $act_data['country_d1']='Balearic Islands';
      $act_data['country_d2']='Balearic Islands';
    }
      
    

    $tmp_array=preg_split('/\s+/',$act_data['postcode']) ;

    if(count($tmp_array)==2){
      $sql=sprintf("select `Country Name` as name from kbase.`Country Dimension` left join kbase.`Country Alias Dimension` on  (`Country Alias Code`=`Country Code`) where `Country Alias`=%s or `Country Name`=%s ",prepare_mysql($tmp_array[0]),prepare_mysql($tmp_array[0]));


      $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
      if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$act_data['country']=$row['name'];
	$act_data['postcode']=$tmp_array[1];
      }

       $sql=sprintf("select `Country Name` as name from kbase.`Country Dimension` left join kbase.`Country Alias Dimension` on  (`Country Alias Code`=`Country Code`) where `Country Alias`=%s or `Country Name`=%s ",prepare_mysql($tmp_array[1]),prepare_mysql($tmp_array[1]));
      

      $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
      if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$act_data['country']=$row['name'];
	$act_data['postcode']=$tmp_array[0];
      }
    }elseif(count($tmp_array)==1){
      $sql=sprintf("select `Country Name` as name from kbase.`Country Dimension` left join kbase.`Country Alias Dimension` on  (`Country Alias Code`=`Country Code`) where `Country Alias`=%s or `Country Name`=%s",prepare_mysql($tmp_array[0]),prepare_mysql($tmp_array[0]));


      $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
      if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$act_data['country']=$row['name'];
	$act_data['postcode']='';
      }
	

    }
  }else{
    //    print_r($act_data);
    if(strtolower(_trim($act_data['country']))==strtolower(_trim($act_data['postcode'])))
      $act_data['postcode']='';
  }
  
  if($act_data['postcode']=='3742' and $act_data['town']=='SW Baarn'){
    $act_data['town']='Baarn';
    $act_data['postcode']='3742 SW';
  }


    
    
  if($act_data['postcode']=="" and preg_match('/\s*\d{4,6}\s*/i',$act_data['town'],$match))
    {
  
      if($act_data['country']!="Netherlands"){
	$act_data['postcode']=_trim($match[0]);
	$act_data['town']=preg_replace('/\s*\d{4,6}\s*/','',$act_data['town']);
      }
    }


  if($act_data['a2']=='Ascheffel'){
    $act_data['town']='Ascheffel';
    $act_data['a2']='';
  }
 if(preg_match('/South Afrika$|South Africa$/i',$act_data['postcode'])){
    $act_data['country']='South Africa';
    $act_data['postcode']=_trim(preg_replace('/South Afrika$|South Africa$/i','',$act_data['postcode']));
  }

 if(preg_match('/Via Chiesanuova, 71/i',$act_data['a1']) and preg_match('/C.O .c.c. Futura2./i',$act_data['a2'])  ){
   $act_data['a1']='C/O CC Futura';
  $act_data['a2']='Via Chiesanuova, 71';
  }

 if(preg_match('/United States$/i',$act_data['postcode'])){
    $act_data['country']='USA';
    $act_data['postcode']=_trim(preg_replace('/United States$/i','',$act_data['postcode']));
  }


  if(preg_match('/Lewiston - Ny/i',$act_data['town'])){
    $act_data['country']='USA';
    $act_data['town']='Lewiston';
    $act_data['country_d1']='NY';
 
  }
  //print_r($act_data);
 if(preg_match('/^101 Reykjavik$/i',$act_data['postcode'])){
   $act_data['town']='Reykjavik';
    $act_data['country']='Iceland';
    $act_data['postcode']='101';
    // print_r($act_data);
  }
if(preg_match('/^101 Reykjavik$/i',$act_data['town'])){
   $act_data['town']='Reykjavik';
    $act_data['country']='Iceland';
    $act_data['postcode']='101';
    // print_r($act_data);
  }


 if(preg_match('/Fi\-\d{4,5}/i',$act_data['postcode'])  and $act_data['country_d1']==''){
   
    $act_data['country']='Finland';
    // print_r($act_data);
  }


  if(preg_match('/Drogheda.*Co Louth/i',$act_data['town'])){

    $act_data['town']='Drogheda';
    $act_data['country_d2']='Co Louth';
 
  }

  if(preg_match('/Tampa\s*.\s*Florida/i',$act_data['town'])){

    $act_data['town']='Tampa';
    $act_data['country_d1']='Florida';
 
  }

  if($act_data['country']=='USA' and   preg_match('/\-\s*ny/i',$act_data['town'])){

    $act_data['town']=preg_replace('/\-\s*ny/i','',$act_data['town']);
    $act_data['country_d1']='New York';
 
  }



  if(preg_match('/alberta/i',$act_data['town'])  and  preg_match('/Onoway/i',$act_data['a3']) ){
    $act_data['a3']='';
    $act_data['town']='Onoway';
    $act_data['country_d1']='Alberta';
 
  }




  if(preg_match('/alicante/i',$act_data['country_d2']) and $act_data['country']==''  ){
    $act_data['country']='Spain';
    $act_data['country_d1']='Valencia';
  }

  if(preg_match('/Alfaz del Pi - Alicante/i',$act_data['town'])  ){
    $act_data['town']='Alfaz del Pi';
    $act_data['country_d2']='Alicante';
    $act_data['country_d1']='Valencia';
  }


  if(preg_match('/Viterbo/i',$act_data['town'])  and preg_match('/Soriano Nel Cimino/i',$act_data['a2']) ){
    $act_data['country']='Italy';
    $act_data['town']='Soriano Nel Cimino';
    $act_data['country_d1']='Lazio';
    $act_data['country_d2']='Viterbo';
    $act_data['a2']='';
    $act_data['postcode']='01028';
  }



  // print_r($header_data);
  //exit;

  //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  // Her we fix the distinct speciffic errors in the input fiels
  $act_data['town']=_trim($act_data['town']);

  if($act_data['country']=='Cyprus'){
    if($act_data['a3']=='1065 Nicosia'){
      $act_data['postcode']='1065';
      $act_data['town']='Nicosia';
      $act_data['a3']='';
    }
  }
    
  if($act_data['postcode']=='Cyprus')
    {
      $act_data['country']='Cyprus';
      $act_data['postcode']='';
    }
 
 


  if($act_data['postcode']=='' and preg_match('/\s*no\-\d{4}\s*/',$act_data['town'],$match) ){
    $act_data['postcode']=_trim($match[0]);
    $act_data['town']=preg_replace('/\s*no\-\d{4}\s*/','',$act_data['town']);

  }

  // print_r($act_data);

  if($act_data['country']=='Korea South' or $act_data['country']=='South Korea'){
    if($act_data['a3']=='Seoul' ){
      $act_data['town']='Seoul';$act_data['a3']='';
    }    
    if(preg_match('/^Kangseo.Gu$/i',_trim($act_data['a2']))){
      $act_data['town_d1']='Gangseo-gu';
      $act_data['a2']='';
    }
    if($act_data['a1']=='105-207 Whagok-Dong'){
      $act_data['town_d2']='Hwagok-dong';
      $act_data['a1']='105-207';
    }

  }

  // print_r($act_data);

  if($act_data['a2']=='Yokneam Ilit' ){
    $act_data['a2']='';
    $act_data['town']='Yokneam Ilit';
  }
  if(preg_match('/Pyrgos.*Limassol/',$act_data['town'])){

    $act_data['town']='Pyrgos';
  }


  if($act_data['a1']=='Sharn Brook' and $act_data['town']=='Bedfordshire'){
    $act_data['a1']='NULL';
    $act_data['town']='Sharnbrook';
  }

  if($act_data['a2']=='Upper Marlboro' and $act_data['town']=='Md'){
    $act_data['a2']='';
    $act_data['town']='Upper Marlboro';
    $act_data['country_d1']='MD';
  }



  if($act_data['a2']=='55299 Nackenheim' and $act_data['town']=='Germany'){
    $act_data['a2']='';
    $act_data['country_d1']='';
    $act_data['postcode']='55299';
    $act_data['town']='Nackenheim';
  }

  if($act_data['town']=='Siverstone - Oregon'){
    $act_data['town']='Siverstone';
    $act_data['country_d2']='Oregon';
    $act_data['country']='USA';
  }

  if($act_data['town']=='5227 Nesttun'){
    $act_data['town']='Nesttun';
    $act_data['postcode']='5227';
  }
  if($act_data['town']=='3960 Stathelle'){
    $act_data['town']='Stathelle';
    $act_data['postcode']='3960';
  }
  if($act_data['town']=='45700 Kuusankoski'){
    $act_data['town']='Kuusankoski';
    $act_data['postcode']='45700';
  }
  if($act_data['town']=='06880 Kärrby'){
    $act_data['town']='Kärrby';
    $act_data['postcode']='06880';
  }
  if($act_data['town']=='2500 Valby'){
    $act_data['town']='Valby';
    $act_data['postcode']='2500';
  }

  if($act_data['town']=='21442 Malmö'){
    $act_data['town']='Malmö';
    $act_data['postcode']='21442';
  }
  if($act_data['town']=='11522 Stockholm'){
    $act_data['town']='Stockholm';
    $act_data['postcode']='11522';
  }

  if($act_data['town']=='1191 Jm Ouderkerk A/d Amstel'){
    $act_data['town']='Ouderkerk aan de Amstel';
    $act_data['postcode']='1191JM';
  }
  if($act_data['town']=='7823 Pm Emmen'){
    $act_data['town']='Emmen';
    $act_data['postcode']='7823PM';
  }
  if($act_data['town']=='1092 Budapest'){
    $act_data['town']='Budapest';
    $act_data['postcode']='1092';
  }


  if($act_data['town']=='Lanzarote, las Palmas' ){
    $act_data['town']='';
   
    $act_data['country_d1']='Canary Islands';
   
    $act_data['country_d2']='Las Palmas';
    $act_data['town']='';

    if( $act_data['a2']=='Costa Teguise'){
      $act_data['a2']='';
      $act_data['town']='Costa Teguise';
    }


  }


  if($act_data['town']=='Zugena - Provincia Almeria'){
    $act_data['town']='Zurgena';
    $act_data['country_d2']='Almeria';
    $act_data['country_d1']='Adalucia';
    
  }

if($act_data['town']=='Alhama de Almeria, Almeria'){
    $act_data['town']='Alhama de Almeria';
    $act_data['country_d2']='Almeria';
    $act_data['country_d1']='Adalucia';
    
  }
  
 if($act_data['town']=='Coulby Newham - Middlesbrough'){
   $act_data['country_d2']='Middlesbrough';
   $act_data['town']='Coulby Newham';
 }

if($act_data['town']=='Lerwick - Shetland Isles'){
   $act_data['country_d2']='Shetland Islands';
   $act_data['town']='Lerwick';
 }
if($act_data['town']=='Ollaberry - Shetland Islands'){
   $act_data['country_d2']='Shetland Islands';
   $act_data['town']='Ollaberry';
 }
if($act_data['town']=='Shetland - Shetland Islands' and $act_data['a1']=='Brae' ){
   $act_data['country_d2']='Shetland Islands';
   $act_data['town']='Brae';
   $act_data['a1']='NULL';
   
 }

 if(preg_match('/$MK40.*1hs/i',$act_data['postcode']) ){
   $act_data['country']='United Kingdom';
   
 }

 if(preg_match('/DH5.*9RS/i',$act_data['postcode'])  and $act_data['a1']=='Linden House' ){
   $act_data['a1']='Linden House';
   $act_data['a2']='2 Heather Drive';
   $act_data['a3']='';
   $act_data['town']='Houghton Le Spring';
  }

  if($act_data['town']=='Malaga' and $act_data['a2']=='Coin'){
    $act_data['town']='Coin';
    $act_data['country_d1']='Andalusia';
    $act_data['country_d2']='Malaga';
    $act_data['a2']='';
  }

 if($act_data['town']=='Villasor Pr. Cagliari'){
   $act_data['town']='Villasor';
   $act_data['country_d2']='Cagliari';
}

if($act_data['town']=='Leebotwood (nr Church Stretton'){
   $act_data['town']='Leebotwood Nr. Church Stretton';
}
if($act_data['town']=='Nea Moudhania - Chalkidiki'){
   $act_data['town']='Nea Moudhania';
   $act_data['country_d2']='Chalkidiki';
}

if($act_data['town']=='Cradley Heath, West Midlands'){
   $act_data['town']='Cradley Heath';
   $act_data['country_d2']='';
}

if($act_data['town']=='Garswood, Ashton In Makerf'){
   $act_data['town']='Ashton-in-Makerfield';
   $act_data['town_d2']='Garswood';
}
if($act_data['town']=='Boulogne Billancourt Cedex'){
   $act_data['town']='Boulogne Billancourt';
}

if($act_data['town']=='Furzton - Milton Keynes'){
   $act_data['town']='Milton Keynes';
$act_data['town_d2']='Furzton';
}

if($act_data['town']=='Glenfield - Leicester'){
   $act_data['town']='Leicester';
$act_data['town_d2']='Glenfield';
}
if($act_data['town']=='Edinburgh - Midlothian'){
   $act_data['town']='Edinburgh';
}

if($act_data['town']=='Killorglin - Co Kerry'){
   $act_data['town']='Killorglin';
}
if($act_data['town']=='Castledawson - Co Derry'){
   $act_data['town']='Castledawson';
}
if($act_data['town']=='Douglas, Isle of Man'){
   $act_data['town']='Douglas';
   $act_data['country']='Isle of Man';
}

if($act_data['town']=='Aberdeen, Aberdeenshire'){
   $act_data['town']='Aberdeen';
}

if($act_data['town']=='Elephant & Castle, London'){
   $act_data['town']='London';
$act_data['town_d2']='Elephant & Castle';
}
if($act_data['town']=='Muswell Hill, London'){
   $act_data['town']='London';
$act_data['town_d2']='Muswell Hill';
}

if($act_data['town']=='South Norwood, London'){
   $act_data['town']='London';
$act_data['town_d2']='South Norwood';
}

 if(preg_match('/Isle of Wight/i',$act_data['town']))
   $act_data['town']='';

if($act_data['town']=='Walkinstown - Dublin'){
   $act_data['town']='Dublin';
$act_data['town_d2']='Walkinstown';
}
if($act_data['town']=='Yarmouth - Isle of Wight'){
   $act_data['town']='Yarmouth';
   $act_data['country_d2']='Isle of Wight';
 }
if($act_data['town']=='New Port - Isle of Wight'){
   $act_data['town']='New Port';
   $act_data['country_d2']='Isle of Wight';
 }





if($act_data['town']=='Kingston-Upon Thames'){
   $act_data['town']='Kingston-Upon-Thames';
}

if($act_data['town']=='Bradford - On - Avon'){
   $act_data['town']='Bradford-On-Avon';
}

if($act_data['town']=='Tongham - Nr Farnham'){
   $act_data['town']='Tongham Nr. Farnham';
}

if($act_data['town']=='Hornbæk - Sjælland'){
   $act_data['town']='Hornbæk';
   $act_data['country_d2']='Sjælland';
}



  if($act_data['town']=='7779de Overijssel'){
    $act_data['town']='Overijssel';
    $act_data['postcode']='7779DE';
  }
  if($act_data['town']=='3015 Br Rotterdam'){
    $act_data['town']='Rotterdam';
    $act_data['postcode']='3015BR';
  }

  $act_data['postcode']=_trim(preg_replace('/the Netherlands/i','',$act_data['postcode']));

  if( preg_match('/boggon/i',$act_data['name'])  and preg_match('/35617|48051/i',$act_data['act'])    ){
    $act_data['name']='Temenos Academy';
  }
  if( preg_match('/dudden/i',$act_data['name'])  and preg_match('/25124/i',$act_data['act'])    ){
    $act_data['name']='Mr Jeff C Dudden';
  }
    

  if( preg_match('/Spain.*Canary Island/i',$act_data['country'])  and preg_match('/Arguineguin/i',$act_data['a2'])    ){
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Arguinegín';
    $act_data['country_d2']='Las Palmas';
    $act_data['country_d1']='Canary Islands';
    $act_data['country']='Spain';

  }


  if( preg_match('/Spain.*Canary Island/i',$act_data['country'])      ){
    $act_data['country_d1']='Canary Islands';
    $act_data['country']='Spain';
  }
  if( preg_match('/^Tenerife.*Canary Island/i',$act_data['town'])      ){
    $act_data['country_d1']='Canary Islands';
    $act_data['country']='Spain';
    $act_data['town']='Tenerife';

    if($act_data['a2']=='Playa de las Americas' and $act_data['a3']=='Adeje'){
      $act_data['a2']='';
      $act_data['a3']='';
      $act_data['town']='Playa de las Americas';
    }

  }

 if(preg_match('/Northern Ireland/i',$act_data['town'])  ){
    $act_data['town']=_trim(preg_replace('/\,?\-?\s*Northern Ireland/i','',$act_data['town']));
  }

  if( preg_match('/^bfpo\s*\d/i',$act_data['town'])  and $act_data['postcode']=='' ){
    $act_data['postcode']=strtoupper($act_data['town']);
    $act_data['town']='';
  }

 if( preg_match('/^je\d/i',$act_data['postcode'])  and $act_data['country']=='' ){
    $act_data['country']='Jersey';
    if($act_data['town']=='Jersey')
      $act_data['town']='';
  }
  if( preg_match('/^im\d/i',$act_data['postcode'])  and $act_data['country']=='' ){
    $act_data['country']='Isle of Man';

    if($act_data['a2']=='Ramsey'){
      $act_data['town']='Ramsey';
      $act_data['a2']='';
    }
     
    if(preg_match('/Isle of Man/i',$act_data['town'])  ){
      $act_data['town']=_trim(preg_replace('/\,?\-?\s*Isle of Man/i','',$act_data['town']));

    }
  }

  if(preg_match('/^Norfolk$|^West Midlands$/i',$act_data['town']))
    $act_data['town']='';


  if($act_data['town']=='St.pauls Bay')
    $act_data['town']='St Pauls Bay';

  if($act_data['town']=='Outside the Royal Festival Hall'){
  $act_data['town']='London';
    $act_data['country_d2']='';
    $act_data['country_d1']='';
  }

  if($act_data['town']=='Ashton Under Lyne, Tameside')
    $act_data['town']='Ashton Under Lyne';


  if(preg_match('/Las Palmas de Gran Canaria/i',$act_data['a2'])  ){
    $act_data['a2']='';
    $act_data['country_d2']='Las Palmas';
    $act_data['country_d1']='Canary Islands';
    $act_data['country']='Spain';

  }

  if($act_data['postcode']=='5260Demnark')
    $act_data['postcode']='DK-5260';
 

  if(preg_match('/ch6\s*5dz/i', $act_data['country'] )){
    
    $act_data['country']='';
    $act_data['postcode']='ch6 5dz';
  }
    
  if($act_data['country']=='Scotish Island' or $act_data['country']=='West Sussex' ){
    $act_data['country']='';
  }





  if( preg_match('/Mark Postage To France/i',$act_data['a1'])){
    $act_data['a1']='';
  }

  if( preg_match('/Spain.*Baleares/i',$act_data['country']) ){
    $act_data['country_d1']='Balearic Islands';
    $act_data['country']='Spain';
  }

  if($act_data['town']=='7182 Calvia - Mallorca'){
    $act_data['postcode']='07182';
    $act_data['town']='Calvia';
    $act_data['country_d1']='Balearic Islands';
    $act_data['country_d2']='Balearic Islands';
  }

  if($act_data['town']=='Lefkosia (nicosia)')
    $act_data['town']='Nicosia';


  if($act_data['town']=='07820 San Antonio - Ibiza')
    $act_data['postcode']='07820';

  if($act_data['postcode']=='Co Cork, Ireland')
    $act_data['postcode']='';
  if($act_data['town']=='Alicante - Spain')
    $act_data['town']='Alicante';
  if( preg_match('/San Antonio.*Ibiza/i',$act_data['town']) ){
    $act_data['town']='Sant Antoni de Portmany';
      
  }

  if( preg_match('/Perth.*Western Autralia/i',$act_data['town']) ){
    $act_data['town']='Perth';
    $act_data['country_d2']='Western Autralia';

  }
  if($act_data['a1']=='Kerem Maharal,' ){
    $act_data['a1']='NULL';
    $act_data['town']='Kerem Maharal';
  }

  if( preg_match('/Bs37 7rb|S2 3eh/i',$act_data['town'])){
    $act_data['town']='';
    $act_data['postcode']=strtoupper($act_data['town']);
  }

  if( preg_match('/castle market/i',$act_data['a3'])  and $act_data['postcode']=='' and $act_data['town']=='Sheffield' ){
    $act_data['postcode']='S1 2AD';
  }
  if($act_data['town']=='Albox, Almeria'){
    $act_data['town']='Albox';
    $act_data['country_d1']='Andalucía';
    $act_data['country_d2']='Almería';
  }

  if( preg_match('/^bfpo\s+\d+/i',$act_data['town'])){
    $act_data['country']='United Kingdom';
    $act_data['town']='';
    $act_data['postcode']=strtoupper($act_data['town']);
  }

  if($act_data['postcode']=='50004 Zaragoza'){
    $act_data['town']='Zaragoza';
    $act_data['postcode']='50004';
  }
  if($act_data['postcode']=='08530 Barcelona'){
    $act_data['town']='Barcelona';
    $act_data['postcode']='08530';
  }
  if($act_data['postcode']=='28300 Madrid'){
    $act_data['town']='Madrid';
    $act_data['postcode']='28300';
  }
  if($act_data['postcode']=='28013 Madrid'){
    $act_data['town']='Madrid';
    $act_data['postcode']='28013';
  }

  if($act_data['act']=='27821'){
    $act_data['act']='21179';
    $act_data['name']='Soap & Soak';
  }
  

  if(strtolower($act_data['town'])=='la romana (alicante)'){
    $act_data['town']='La Romana';
    $act_data['country_d2']='Alicante';
    $act_data['country_d1']='Valencia';
  }

  if($act_data['town']=='Sax (alicante)'){
    $act_data['town']='Sax';
    $act_data['country_d2']='Alicante';
    $act_data['country_d1']='Valencia';
  }


  if($act_data['postcode']=='30383 Cartagena'){
    $act_data['town']='Cartagena';
    $act_data['postcode']='30383';
  }
  if($act_data['postcode']=='07760 Ciutadella'){
    $act_data['town']='Ciutadella';
    $act_data['postcode']='07760';
  }
    

  if($act_data['town']=='Tucson Az'){
    $act_data['town']='Tucson';
    $act_data['country_d2']='Arizona';
  }

  if($act_data['country']=='Ireland' and $act_data['a3']=='Castleblaney' ){
    $act_data['town']='Castleblaney';
    $act_data['country_d2']='Monaghan';
    $act_data['a3']='';
  }


  if($act_data['town']=='Port Angeles (wa)'){
    $act_data['town']='Port Angeles';
    $act_data['country_d2']='WA';
  }
  if($act_data['town']=='Beverly Hills (ca)'){
    $act_data['town']='Beverly Hills';
    $act_data['country_d2']='California';
  }
  if($act_data['town']=='Milwaukee, Wi'){
    $act_data['town']='Milwaukee';
    $act_data['country_d2']='Wi';
  }

  if($act_data['town']=='Kingston, Ma'){
    $act_data['town']='Kingston';
    $act_data['country_d2']='Ma';
  } 
  if($act_data['town']=='Mcdonough, Ga'){
    $act_data['town']='Mcdonough';
    $act_data['country_d2']='Ga';
  }
  if($act_data['town']=='Bridgewater, Nj'){
    $act_data['town']='Bridgewater';
    $act_data['country_d2']='NJ';
  }
  if($act_data['town']=='Marietta, Ga'){
    $act_data['town']='Marietta';
    $act_data['country_d2']='Ga';
  }
  if($act_data['town']=='Duluth - Ga'){
    $act_data['town']='Duluth';
    $act_data['country_d2']='Ga';
  } 


  if($act_data['town']=='Hoffman Estates - Il.'){
    $act_data['town']='Hoffman Estates';
    $act_data['country_d2']='Il';
  } 
  if($act_data['town']=='Shelton Ct'){
    $act_data['town']='Shelton';
    $act_data['country_d2']='Ct';
  }
  if($act_data['town']=='Raton - Nm.'){
    $act_data['town']='Raton';
    $act_data['country_d2']='NM';
  }
  if($act_data['town']=='Monett, Mo'){
    $act_data['town']='Monett';
    $act_data['country_d2']='MO';
  }
  if($act_data['town']=='Alton, Il'){
    $act_data['town']='Alton';
    $act_data['country_d2']='Il';
  } 
  if($act_data['town']=='Zanesville, Ohio'){
    $act_data['town']='Zanesville';
    $act_data['country_d2']='Ohio';
  }
  if($act_data['town']=='Pinola, Ms'){
    $act_data['town']='Pinola';
    $act_data['country_d2']='MS';
  }
  if($act_data['town']=='Port Jefferson Station - Ny'){
    $act_data['town']='Port Jefferson Station';
    $act_data['country_d2']='NY';
  } 
  if($act_data['town']=='Houston - Texas'){
    $act_data['town']='Houston';
    $act_data['country_d2']='Texas';
  }
  if($act_data['town']=='Cambell Hall - Ny'){
    $act_data['town']='Cambell Hall';
    $act_data['country_d2']='NY';
  } 
  if($act_data['postcode']=='04400 Almeria - SPAIN'){
    $act_data['postcode']='04400';
    $act_data['country_d1']='Andalucía';
    $act_data['country_d2']='Almería';
  }
    
  if( preg_match('/Whaley Bridge, Derbyshire Sk23 7jg/i',$act_data['town'])){
    $act_data['country']='United Kingdom';
    $act_data['town']='Whaley Bridge';
    $act_data['postcode']='SK23 7JG';
  }

  if( preg_match('/Beirut\s*.\s*Lebanon/i',$act_data['country'])){
    $act_data['country']='Lebanon';
    $act_data['town']='Beirut';
  }

  if( preg_match('/01902 850 006|north ayrshire|stoke.on trent|Suffolk|Norfolk/i',$act_data['country']))
    $act_data['country']='';

  if( preg_match('/Channel Islands/i',$act_data['country']) ){
      
    if(preg_match('/^(jersey\s+)?\s*je/i',$act_data['postcode'])){
	
      $act_data['postcode']=preg_replace('/\s*jersey\s*/i','',$act_data['postcode']);
      $act_data['country']='Jersey';



    }
  }


  if( preg_match('/ireland/i',$act_data['country']) and preg_match('/^bt/i',$act_data['postcode']) )
    $act_data['country']='United Kingdom';
    
  if( preg_match('/Co Kerry, Ireland/i',$act_data['country'])  )
    $act_data['country']='Ireland';


  if($act_data['act']=='21808'){
    $act_data['name']='Luss Glass Studio';
    $act_data['contact']='Janine Smith';
  }

  if($act_data['act']=='33387'){
    $act_data['act']='9050';
  }


  if($act_data['name']=='Crocodile Antiques (1)')
    $act_data['name']='Crocodile Antiques';

  // print $act_data['mob'].'-'.$act_data['act']."-\n";
  if($act_data['mob']=='01723 376447' and $act_data['act']=='26456'){
    $act_data['mob']='';
  }

  if($act_data['contact']=='Thandi' and $act_data['act']=='21217'){
    $act_data['contact']='Thandi Viljoen';
  }

    
    
  if(preg_match('/G12 8aa/i',$act_data['country'])){
    $act_data['country']='';
    $act_data['postcode']='G12 8AA';
  }
    
    
  $split_town=preg_split('/\s*,\s*/i',$act_data['town']);
  if(count($split_town)==2){
    if(preg_match('/jersey/i',$split_town[1])){
      $act_data['town']=$split_town[0];
      $act_data['country']='Jersey';
    }
	
  }
if(check_email_address($act_data['country'])){
    if($act_data['email']=='')
      $act_data['email']=$act_data['country'];
    $act_data['country']='';
  }
  if(preg_match('/Clwyd/i',$act_data['country']))$act_data['country']='';
    
  if($act_data['country']=='Harmelen (netherlands)'){
    $act_data['town']='Harmelen';
    $act_data['country']='netherlands';
  }

  if($act_data['postcode']=='USA'){
    $act_data['postcode']='';
    $act_data['country']='United States';
  }

  if($act_data['town']=='Fgura, Europe'){
    $act_data['town']='Fgura';
  }
  if($act_data['town']=='3800 Limburg'){
    $act_data['town']='Limburg';
    $act_data['postcode']='3800';
  }
  if($act_data['town']=='West Vlaanderen'){
    $act_data['town']='West Vlaanderen';
    $act_data['postcode']='8800';
  }



  if($act_data['town']=='Nordrheinwestfalen' and $act_data['a2']=='Bochum'){
    $act_data['town']='Bochum';
    $act_data['country_d1']='Nordrhein-Westfalen';
    $act_data['a2']='';
  }

  if($act_data['town']=='Schwaig, Bavaria'){
    $act_data['town']='Schwaig';
    $act_data['country_d1']='Bayern';
  }
  if($act_data['town']=='Central Milton Keynes'){
    $act_data['town']='Milton Keynes';
  }
  if($act_data['town']=='No-5353 Straume'){
    $act_data['town']='Straume';
    $act_data['postcode']='No-5353';
  }

  if($act_data['a2']=='Vibrac' and $act_data['a3']=='Charente'){
    $act_data['a2']='';
    $act_data['a3']='';
    $act_data['town']='Vibrac';
    $act_data['country_d1']='Poitou-Charentes';
    $act_data['country_d2']='Charente';

  }



  if($act_data['town']=='Tiefenau' and $act_data['postcode']=='1609'){
    $act_data['country_d1']='Sachsen';
    $act_data['postcode']='01609';
  }

  if($act_data['town']=='Abingdon Oxfordshire')
    $act_data['town']='Abingdon';

  if($act_data['town']=='Bromham, Chippenham')
    $act_data['town']='Bromham';
  if($act_data['town']=='Buckinghamshire')
    $act_data['town']='';

  // print_r($act_data);    
  if(preg_match('/\s*eire\*/i',$act_data['postcode'])){
    $act_data['postcode']='';
    $act_data['country']='Ireland';
  }
    
  if(preg_match('/MO 63136/i',$act_data['postcode']) and  $act_data['country']='USA'  ){
    $act_data['postcode']='63136';
    $act_data['country_d1']='MO';
  }
    


  if($act_data['town']=='Halle' and $act_data['postcode']=='33790'){
    $act_data['country_d1']='Nordrhein-Westfalen';
      
  }
    
    
  if(preg_match('/^\s*\d{4,6}\s*$/',$act_data['town'])){
    $act_data['postcode']=$act_data['town'];
    $act_data['town']='';
  }
  if($act_data['town']=='Bilbao - Vizcaya'){
    $act_data['town']='Bilbao';
    $act_data['country_d2']='Vizcaya';
  }

  if($act_data['country']=='Balearic Isles'){
    $act_data['country']='Spain';
    $act_data['country_d1']='Balearic Islands';

  }


  if($act_data['country']=='Guernsey, C.i')
    $act_data['country']='Guernsey';
    
  if($act_data['town']=='Guernsey, C.i'){
    $act_data['town']='Guernsey';
    $act_data['country']='Guernsey';
  }

  if($act_data['town']=='South yorkshire'){
    $act_data['town']='';
      
    if($act_data['a3']!=''){
      $act_data['town']=$act_data['a3'];
      $act_data['a3']='';
    }else if($act_data['a2']!=''){
      $act_data['town']=$act_data['a2'];
      $act_data['a2']='';
    }
  }
 //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  // fix contracts
  //  print_r($act_data);  
if($act_data['name']=='Igneous Products' and $act_data['contact']=='Les'){
    $act_data['contact']='';
    
  }

    $extra_contact=false;
  if($act_data['contact']!=''){

    $_contact=$act_data['contact'];
    $split_names=preg_split('/\s+and\s+|\&|\/|\s+or\s+/i',$act_data['contact']);
    if(count($split_names)==2){
      $split_names1=preg_split('/\s+/i',$split_names[0]);
      $split_names2=preg_split('/\s+/i',$split_names[1]);
      if(count($split_names1)==1 and count($split_names2)==2 ){
	$name1=$split_names1[0].' '.$split_names2[1];
	$name2=$split_names[1];
      }else{
	$name1=$split_names[0];
	$name2=$split_names[1];
      }
      $act_data['contact']=$name1;
      $extra_contact=$name2;
      if($_contact==$act_data['name']){
	$act_data['name']=preg_replace('/\s+and\s+|\&|\/|\s+or\s+/i',' & ',$act_data['name']);
      }

    }
    $there_is_contact=true;
  }

  if($act_data['contact']==$act_data['name'] or  $act_data['name']=='' and $act_data['contact']!=''){
    // we dont hasve person name
    $there_is_contact=false;
    if(!preg_match('/C \& P Trading|Peter \& Paul Ltd|Health.*Beauty.*Salon|plant.*herb|Igneous Products/i',$act_data['contact'])){
      $act_data['name']=$act_data['contact'];
      $act_data['contact']='';
    }

  }
   if(preg_match('/^c\/o/i',$act_data['a1'])){
    $co=$act_data['a1'];
    $act_data['a1']='';
  }
  if(preg_match('/^c\/o/i',$act_data['a2'])){
    $co=$act_data['a2'];
    $act_data['a2']='';
  }
  if(preg_match('/^c\/o/i',$act_data['a3'])){
    $co=$act_data['a3'];
    $act_data['a3']='';
  }

 if(preg_match('/@/',$act_data['country']))
    $act_data['country']='';
 $act_data['tel']=preg_replace('/\[\d*\]/','',$act_data['tel']);
  $act_data['tel']=preg_replace('/\(/','',$act_data['tel']);
  $act_data['tel']=preg_replace('/\)/','',$act_data['tel']);
  $act_data['fax']=preg_replace('/\[\d*\]/','',$act_data['fax']);
  $act_data['fax']=preg_replace('/\(/','',$act_data['fax']);
  $act_data['fax']=preg_replace('/\)/','',$act_data['fax']);
  $act_data['mob']=preg_replace('/\[\d*\]/','',$act_data['mob']);
  $act_data['mob']=preg_replace('/\(/','',$act_data['mob']);
  $act_data['mob']=preg_replace('/\)/','',$act_data['mob']);
  return $act_data;

} 


function check_email_address($email) {
return Email::is_valid($email);
}
function guess_email($email,$contact='',$tipo=1){
  if(check_email_address($email) ){

    // if($contact=='')
    //  $contact=get_name($contact_id);
    $email_data=array ('email'=>$email,'contact'=>$contact,'tipo'=>$tipo);
    
    return $email_data;
    
  }

  else
    return false;
}

function get_address_raw(){
  
 $address1='';
  $address2='';
  $address3='';
  $town_d2='';
  $town_d1='';
  $town='';
  $country_d2='';
  $country_d1='';
  $postcode='';
  $country='';
  $town_d2_id=0;
  $town_d1_id=0;
  $town_id=0;
  $country_d2_id=0;
  $country_d1_id=0;
  $country_id=0;
  
   $address_data=array(
		      'address1'=>$address1,
		      'address2'=>$address2,
		      'address3'=>$address3,
		      'town_d2'=>$town_d2,
		      'town_d1'=>$town_d1,
		      'town'=>$town,
		      'country_d2'=>$country_d2,
		      'country_d1'=>$country_d1,
		      'postcode'=>$postcode,
		      'country'=>$country,
		      'town_d2_id'=>$town_d2_id,
		      'town_d1_id'=>$town_d1_id,
		      'town_id'=>$town_id,
		      'country_d2_id'=>$country_d2_id,
		      'country_d1_id'=>$country_d1_id,
		      'country'=>$country_id,

		       );

   return $address_data;
}


function ci_act_transformations($act_data){
 


 $act_data['name']=preg_replace('/\\\"/i',' ',$act_data['name']);
  $act_data['contact']=preg_replace('/\\\"/i',' ',$act_data['contact']);

 
  if($act_data['name']=='Eujopa.s.l'){
    $act_data['name']='Eujopa S.L.';
  }
if($act_data['name']=='S. coop. mad. Los Apisquillos'){
    $act_data['name']='S. Coop. Mad. Los Apisquillos';
  }

  //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
  // fix contracts
  if($act_data['name']=='Antonio Laborda - Jabón Jabón'){
    $act_data['name']='Jabón Jabón';
    $act_data['contact']='Antonio Laborda';
  }

 

  if($act_data['country']==''){


    if(preg_match('/spain\s*.\s*ibiza/i',$act_data['postcode'])){
      $act_data['country']='Spain';
      $act_data['postcode']='';
      $act_data['country_d1']='Balearic Islands';
      $act_data['country_d2']='Balearic Islands';
    }
      
    

    $tmp_array=preg_split('/\s+/',$act_data['postcode']) ;

    if(count($tmp_array)==2){
      $sql=sprintf("select `Country Name` as name from kbase.`Country Dimension` left join kbase.`Country Alias Dimension` on  (`Country Alias Code`=`Country Code`) where `Country Alias`=%s or `Country Name`=%s ",prepare_mysql($tmp_array[0]),prepare_mysql($tmp_array[0]));


      $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
      if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$act_data['country']=$row['name'];
	$act_data['postcode']=$tmp_array[1];
      }

       $sql=sprintf("select `Country Name` as name from kbase.`Country Dimension` left join kbase.`Country Alias Dimension` on  (`Country Alias Code`=`Country Code`) where `Country Alias`=%s or `Country Name`=%s ",prepare_mysql($tmp_array[1]),prepare_mysql($tmp_array[1]));
      

      $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
      if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$act_data['country']=$row['name'];
	$act_data['postcode']=$tmp_array[0];
      }
    }elseif(count($tmp_array)==1){
      $sql=sprintf("select `Country Name` as name from kbase.`Country Dimension` left join kbase.`Country Alias Dimension` on  (`Country Alias Code`=`Country Code`) where `Country Alias`=%s or `Country Name`=%s",prepare_mysql($tmp_array[0]),prepare_mysql($tmp_array[0]));


      $result = mysql_query($sql) or die('Query failed: ' . mysql_error());
      if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	$act_data['country']=$row['name'];
	$act_data['postcode']='';
      }
	

    }
  }else{
    //    print_r($act_data);
    if(strtolower(_trim($act_data['country']))==strtolower(_trim($act_data['postcode'])))
      $act_data['postcode']='';
  }
  


    
    
  if($act_data['postcode']=="" and preg_match('/\s*\d{4,6}\s*/i',$act_data['town'],$match))
    {
  
      if($act_data['country']!="Netherlands"){
	$act_data['postcode']=_trim($match[0]);
	$act_data['town']=preg_replace('/\s*\d{4,6}\s*/','',$act_data['town']);
      }
    }


  if($act_data['a2']=='Ascheffel'){
    $act_data['town']='Ascheffel';
    $act_data['a2']='';
  }



 if(preg_match('/alicante/i',$act_data['country_d2']) and $act_data['country']==''  ){
    $act_data['country']='Spain';
    $act_data['country_d1']='Valencia';
  }

  if(preg_match('/Alfaz del Pi - Alicante/i',$act_data['town'])  ){
    $act_data['town']='Alfaz del Pi';
    $act_data['country_d2']='Alicante';
    $act_data['country_d1']='Valencia';
  }


 


  if(preg_match('/Viterbo/i',$act_data['town'])  and preg_match('/Soriano Nel Cimino/i',$act_data['a2']) ){
    $act_data['country']='Italy';
    $act_data['town']='Soriano Nel Cimino';
    $act_data['country_d1']='Lazio';
    $act_data['country_d2']='Viterbo';
    $act_data['a2']='';
    $act_data['postcode']='01028';
  }



if(preg_match('/^granada$/i',$act_data['town']) and $act_data['country']==''){
    $act_data['country']='Spain';
}




if($act_data['name']=='La Tasca de Oscar' and $act_data['contact']==''){
    $act_data['contact']='Rosa Amelia Fariña Rodriguez';

  }


if($act_data['name']=='Aventura2007s.c.p'){
  $act_data['name']='Aventura2007 S.C.P';
}
if($act_data['contact']=='Aventura2007s.c.p'){
  $act_data['contact']='Aventura2007 S.C.P';
}
    

if($act_data['name']=='0neida Beceira'){ $act_data['name']='Oneida Beceira';}
if($act_data['contact']=='0neida Beceira'){$act_data['contact']='Oneida Beceira';}


if($act_data['name']=='Kalamazad A'){ $act_data['name']='A Kalamazad';}
if($act_data['contact']=='Kalamazad A'){$act_data['contact']='A Kalamazad';}




if($act_data['name']=='0rganiza del principado S.L.'){
  $act_data['name']='Organiza del principado S.L.';
}





    if($act_data['name']=='Encarnación Jimenez Marquez' and $act_data['contact']=='0'){
    $act_data['name']='Encarnación Jimenez Marquez';
    $act_data['contact']='Encarnación Jimenez Marquez';
  }
  
    
  if(
  ($act_data['name']=='Virginia Cabrera Rivera' and $act_data['contact']=='David GTX')
  or ($act_data['name']=='Marisa Gómez' and $act_data['contact']=='Naturalmente')
  or ($act_data['name']=='Ignacio Galán Olaizola' and $act_data['contact']=='Mandala')
  or ($act_data['name']=='Sandra Romay Naixes' and $act_data['contact']=='Tribu´s')
  or ($act_data['name']=='Soledad Martin Santos' and $act_data['contact']=='Tu Luz')
  or ($act_data['name']=='Mari Carmen de La Muela Vega' and $act_data['contact']=='Joyeria Caro')
  or ($act_data['name']=='Aniceto de Leon Viñoly' and $act_data['contact']=='La Caja Roja')
  or ($act_data['name']=='Mº del Carmen López Carreira' and $act_data['contact']=='Eiroa-2')
  or ($act_data['name']=='Fermin Gutierrez' and $act_data['contact']=='Fermin')

  or ($act_data['name']=='Sonsoles Luque Delgado' and $act_data['contact']=='Isla Web')
  or ($act_data['name']=='Mercedes Manito Mantero' and $act_data['contact']=='Mercedes')

  or ($act_data['name']=='Adriana Ramos Ruiz' and $act_data['contact']=='Jaboneria')
  or ($act_data['name']=='Francisca Castillo Gil' and $act_data['contact']=='Bis a Bis')
  or ($act_data['name']=='Judit Plana Rodriguez' and $act_data['contact']=='Solluna')
 or ($act_data['name']=='Sylvie Felten' and $act_data['contact']=='Aparte')
 or ($act_data['name']=='Rosa Maria Moraleda Sanchez' and $act_data['contact']=='Miro 13')
 or ($act_data['name']=='Alberto Markuerkiaga Santiso' and $act_data['contact']=='Dra!')
 or ($act_data['name']=='Susana Rodriguez Lozano' and $act_data['contact']=='Mr Ayudas')
 or ($act_data['name']=='Juan Carlos Mirabal' and $act_data['contact']=='C')
 or ($act_data['name']=='Laudelina Saavedra Montesdeoca' and $act_data['contact']=='Herbolario Aguamar')
 or (preg_match('/Gina Younis Hevia/i',$act_data['name'])  and preg_match('/Gong Marbella/i',$act_data['contact']) )
 or (preg_match('/Maria Josefa Aparicio Arrebol/i',$act_data['name'])  and preg_match('/Duna/i',$act_data['contact']) )
 or (preg_match('/Marisa R/i',$act_data['name'])  and preg_match('/Ilusiones/i',$act_data['contact']) )
 or (preg_match('/Burgui/i',$act_data['name'])  and preg_match('/Burbuja/i',$act_data['contact']) )
  or (preg_match('/teteria|Herbolario|Perfumeria|Jauja|Herboristeria|El Rincon del Papi|Comercial Fermer.n|Ochun y Yemaya S.C.P.|Pompitas de |Artesan(í|i)a|Esoterico?|Craft Market|Artterapia|Centro De Estetica|Artesano Grabador de Vidrio|Psicolodia Logopedia Montserrat Baulenas|Centro Tiempo Crista|Mais Festa|Pompas de Jab.n|Q.guay\!/i',$act_data['contact']) )
  or (preg_match('/^Asociaci.n |^tienda |joyeria |Papeleria|^bazar|^restaurant|^el |^las |^los |^la /i',$act_data['contact']) )
  or (preg_match('/^(rayas|papel|Artesano|Gipp|La Mar de Cosas|Jabón Jabón|Angelus|Pompas|Jaboneria|Arfin|Samadhi|Zig Zag|Style|Salem|Videotarot|El duende|Sensual|Ariestética|Burbujitas|Chucotattoo|La Misma|D.e|Dunes|Dulce Pina|Naturshop|Amanatur S L|Lady Of the Stones|Splash|Fragancias|Lima Limon)$/i',$act_data['contact']) )
 or (preg_match('/^Mª /i',$act_data['name']) and  $act_data['contact']!='')
// or (preg_match('//i',$act_data['name'])  and preg_match('//i',$act_data['contact']) )
 //or ($act_data['name']=='' and $act_data['contact']=='')
  ){
   $_tmp=$act_data['name'];
   $act_data['name']=$act_data['contact'];
    $act_data['contact']=$_tmp;
  }







    
  $extra_contact=false;
  if($act_data['contact']!=''){

    $_contact=$act_data['contact'];
    $split_names=preg_split('/\s+and\s+|\&|\/|\s+or\s+/i',$act_data['contact']);
    if(count($split_names)==2){
      $split_names1=preg_split('/\s+/i',$split_names[0]);
      $split_names2=preg_split('/\s+/i',$split_names[1]);
      if(count($split_names1)==1 and count($split_names2)==2 ){
	$name1=$split_names1[0].' '.$split_names2[1];
	$name2=$split_names[1];
      }else{
	$name1=$split_names[0];
	$name2=$split_names[1];
      }
      $act_data['contact']=$name1;
      $extra_contact=$name2;
      if($_contact==$act_data['name']){
	$act_data['name']=preg_replace('/\s+and\s+|\&|\/|\s+or\s+/i',' & ',$act_data['name']);
      }

    }
    $there_is_contact=true;
  }else{
    $there_is_contact=false;
    if(!preg_match('/C \& P Trading|Peter \& Paul Ltd|Health.*Beauty.*Salon|plant.*herb|Amanatur S L/i',$act_data['name']))
      $act_data['contact']=$act_data['name'];
    if(!preg_match('/^(pompas)$/i',$act_data['name']))
      $act_data['contact']=$act_data['name'];

   

  }




 

  if($act_data['name']=='Jill Clare' and  $act_data['contact']=='Jill Clare'){
      $tipo_customer='Company';
      $act_data['contact']='';
    }
 


 

  $tmp_array=array('Burbujas Online S.L.','Sona Florida S.L.L.','Fisioglobal SCP','Naturshop','Amanatur S L');
  foreach($tmp_array as $__name){
    if($act_data['name']==$__name and $act_data['contact']==$__name  ){
      $tipo_customer='Company';
     $act_data['contact']='';
     
    }
  }

 
 
 


  $act_data['name']=preg_replace('/^m\.angeles /i','Mª Angeles ',$act_data['name']);

  $act_data['contact']=preg_replace('/^m\.angeles /i','Mª Angeles ',$act_data['contact']);
  

  $act_data['name']=preg_replace('/,? (S\s*L\.|S\.L\.|S\s*\.\s*L|SL)$/i',' S.L.',$act_data['name']);
  $act_data['name']=preg_replace('/\,? (Slu)$/i',' S.L.U.',$act_data['name']);
// $act_data['name']=preg_replace('/\,? (Slu)$/i',' S.L.U.',$act_data['name']);

  $act_data['name']=preg_replace('/ (S\s*C\.|S\.C\.|S\.C|SC)$/i',' S.C.',$act_data['name']);
  $act_data['name']=preg_replace('/ (s\.L\s*L|SLL|S\s*L\.L\.|S\.L\.L\.|S\.LL)$/i',' S.L.L.',$act_data['name']);
  $act_data['name']=preg_replace('/ (S\s*a\.|S\.a\.|S\.a|Sa|s\.a)$/i',' S.A.',$act_data['name']);
  $act_data['name']=preg_replace('/ (C\s*B\.|C\.B\.|C\.B|CB)$/i',' C.B.',$act_data['name']);
  $act_data['name']=preg_replace('/,\s*(C\s*B\.|C\.B\.|C\.B|CB)$/i',' C.B.',$act_data['name']);
  $act_data['name']=preg_replace('/ (-?\s*L\.da|LDA|l\.d\.a)$/i',' L.D.A.',$act_data['name']);
  $act_data['name']=preg_replace('/,\s*(-?\s*L\.da|LDA|l\.d\.a)$/i',' L.D.A.',$act_data['name']);
  $act_data['name']=preg_replace('/ (s\.?\s*c\.?\s*p)$/i',' S.C.P.',$act_data['name']);
$act_data['name']=preg_replace('/ S.l.n.e$/i',' S.L.N.E.',$act_data['name']);
  $act_data['name']=preg_replace('/ S\.?l\.?u\.?$/i',' S.L.U.',$act_data['name']);


if ($act_data['name']==$act_data['contact'] and $act_data['contact']!='') {
  if (preg_match('/^Bazar |^Alta Bisuteria | shop$|^Perfumer.a |Sociedad Cooperativa|souvenirs|^supermercados |^bisuteria | hoteles?$|^hotels? |^eventos |^terra |Avenue de |\d|^equilibrio |^la estrella |^verde |complementos |^joyeria |^regalos |bisiter.a|est.tica|peluquer.a|yoga |el zoco|jabones|S\.L\.$|Ldª$| SL$|Herboristeria|Asoc\. |^Asociaci.n |^Centro |^FPH C\.B\.$|Fisioglobal|^Amigos de | S\.A\.$|Associació Cultural|Associaci.n Cultural| C\.B$|^Asociación [a-z]+$| S\.A\.$| S\.C\.?$|Sucrolotes SLL - La Guinda| C\.B\.?$|lenilunio S\.c\.a$|^Laboratorios |Burbujas Castellón|^Rama SC$| S\.L\.?$| S\.l\.n\.e\.?$| s\.c\.a\.?$|Tecnologias|^Papeleria | S\.L\.U\.$| L\.D\.A\.$| C\.B\.$| S\.L\.L\.$/i',$act_data['name'])) {
    $act_data['contact']='';
    
  }elseif (preg_match('/^(centro)\s+|Publicidad/i',$act_data['name'])) {
    $act_data['contact']='';
  }elseif (preg_match('/^(Fantasía.S|Neurona.S|Carisma|Trin-Tran|Turquesa|Tulsa|Txibiritak|Tza|N Ude|Vakaloka|Valle-Villa|vimes|xl|xena|waza|Terranova|Tierra|Tigal|Timanfaya|Sun Time|Tinasty|Trabalú|Treal|Traum|Fgdf|Tramuntana|Damco Trading|poeme|Populi|Minerales Porto Pi|Servi Print|Prince|Prysma|Carros Publicidad|Objetivo Publicidad|Publiexpress|Puerimueble|Plata Punto Com|puri|Que Punto|Expo Regalo|Expo Regalo|Don Regalo|Scruples|Scruples|seducir|Si Tu Me Dices Ven |Sol y Sol|sp|spiral|Britt-Inger St|Sthmuck|star|Dream Store|Struch|stylo|Sueños|Sunmarine|Supercien|Mai Tai|tayhe|tagore|tamy|tanisa|tauros?|aries|capricornio|Tayhe|Modas Teis|Temporada|Tendencia|they|La Tienda de Merche|Artemaniashop|arrumaco|Bolsos Arpel|arrels|Electro Aroche|Aroa y Maria del Mar|Armonia|Arlequin|Tele Arcos|archi|arco|Arantxa Bisuteria, Regalos y Complementos|Antiquo|Alhambra|Albutt|Alanb|Elemento Agua|Aguamarina|Acuario|Africa|Acuario|Acuarela|Accessorize|Accessoris|Molts Accesoris|Aires De Mexico|Al Tuntun|Al Tun Tun|Laboladecristal|Gretel|Garcivera|S Espay|Ambar Diseño|Concha y Carlos|amina|Amica|America|Ameica|ambar|Amas de Casa Virgen del Carmen |Altieri|Alternativa|Alquimia|signa|Shiam|Singular|Sol y Luna - La Tienda de Mayca|Soyzoe|Splin|Spleen|Etetica Suvita|para ti|thot|tgoreti|el tintero|la tinaja|de todo|top|toke|etnia|a tope|topaz|toque|Un Toque de Estilo|tosca|tasca|toten|totem|touch|Abalorios Trini|La Traperia de Hellin|utop.a|venus|verdi|Art I vi|tigre volador|Walkiria|Waleska|Watermelon|Xarxa|Xaica|Xacris|Whatever|Waza|HM Woman|Interbisu Xxi|Yoryera|zeppo|yerba|yesi|zeida|zaguan|azahar|zaloa|zaleos|yuca|zurron|Fengzhu Zhu|Zidarra|De Zeta|)$/i',$act_data['name'])) {
	$act_data['contact']='';
 }elseif (preg_match('/^(la|el|los|las|spa|tele|Bisuter.a) /i',$act_data['name'])) {
	$act_data['contact']='';
 }elseif (preg_match('/^Bisuter/i',$act_data['name'])) {
	$act_data['contact']='';
  }else{
 

    $_tmp=preg_split('/\s*/',$act_data['name']);
    if(count($_tmp)==1){
      if(!Contact::is_surname($act_data['name']) and !Contact::is_givenname($act_data['name'])   )
	$act_data['contact']='';
    }
      
   

    }
 
}

    

 //  print_r($act_data);
  
  // print_r($header_data);

  //-----------------------------------------
  if(!isset($act_data['town_d1']))
    $act_data['town_d1']='';
  if(!isset($act_data['town_d2']))
    $act_data['town_d2']='';

  if(preg_match('/^c\/o/i',$act_data['a1'])){
    $co=$act_data['a1'];
    $act_data['a1']='';
  }
  if(preg_match('/^c\/o/i',$act_data['a2'])){
    $co=$act_data['a2'];
    $act_data['a2']='';
  }
  if(preg_match('/^c\/o/i',$act_data['a3'])){
    $co=$act_data['a3'];
    $act_data['a3']='';
  }

  

  return $act_data;
}


?>