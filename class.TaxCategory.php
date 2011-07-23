<?php
/*
 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/
include_once('class.DB_Table.php');


class TaxCategory extends DB_Table{


  
  function TaxCategory($a1,$a2=false,$a3=false) {

    $this->table_name='Tax Category';
    $this->ignore_fields=array();

    if($a1 and !$a2){
      $this->get_data('code',$a1);
    }elseif($a1=='find'){
      $this->find($a2,$a3);
      
    }else
       $this->get_data($a1,$a2);
  }


  function get_data($key,$tag){
    
   if($key=='code')
      $sql=sprintf("select *   from `Tax Category Dimension` where `Tax Category Code`=%s ",prepare_mysql($tag));
        elseif($key=='id')
      $sql=sprintf("select *   from `Tax Category Dimension` where `Tax Category Key`=%d ",$tag);
    else
      return;
     // print $sql;
    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->id=$this->data['Tax Category Key'];
      $this->code=$this->data['Tax Category Code'];

    }
      



  }
 
  function find($raw_data,$options){
  if(isset($raw_data['editor'])){
      foreach($raw_data['editor'] as $key=>$value){
	if(array_key_exists($key,$this->editor))
	  $this->editor[$key]=$value;
      }
    }
    
    $this->found=false;
    $this->found_key=false;

    $create='';
    $update='';
    if(preg_match('/create/i',$options)){
      
      $create=true;
    }
    if(preg_match('/update/i',$options)){
      $update=true;
    }

    $data=$this->base_data();
    foreach($raw_data as $key=>$value){
      if(array_key_exists($key,$data))
	$data[$key]=_trim($value);
    }
    

    //  print_r($raw_data);

    if($data['Tax Category Code']=='' ){
      $this->error=true;
      $this->msg='Tax Category code empty';
      return;
    }

    if($data['Tax Category Name']=='')
      $data['Tax Category Name']=$data['Tax Category Code'];
    
 if($data['Tax Category Type']=='')
      $data['Tax Category Type']=$data['Tax Category Code'];
    $sql=sprintf("select * from `Tax Category Dimension` where `Tax Category Code`=%s  "
		 ,prepare_mysql($data['Tax Category Code'])
		 ); 
    //   print $sql;
    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->found=true;
      $this->found_key=$row['Tax Category Code'];
    }
  
    if($create and !$this->found){
    $this->create($data);
      return;
    }
    if($this->found){
     $this->get_data('code',$this->found_key);
    }
    if($update and $this->found){

    }


  }

function create($data){
   $this->new=false;
   $base_data=$this->base_data();
  
    foreach($data as $key=>$value){
      if(array_key_exists($key,$base_data))
	$base_data[$key]=_trim($value);
    }

      $keys='(';$values='values(';
    foreach($base_data as $key=>$value){
      $keys.="`$key`,";
      $values.=prepare_mysql($value).",";
    }
    $keys=preg_replace('/,$/',')',$keys);
    $values=preg_replace('/,$/',')',$values);
    $sql=sprintf("insert into `Tax Category Dimension` %s %s",$keys,$values);
    if(mysql_query($sql)){
      $this->id = mysql_insert_id();
      $this->msg=_("Tax Category Added");
      $this->get_data('id',$this->id);
   $this->new=true;
   return;
 }else{
   $this->msg=_(" Error can not create tax category");
 }
}
 




  function get($key,$data=false){
    switch($key){
   
    default:
      if(isset($this->data[$key]))
	return $this->data[$key];
      else
	return '';
    }
    return '';
  } 
    

  function calculate_tax($amount){

    return $amount*$this->data['Tax Category Rate'];
    
  }
  
         function set_taxes($country) {

            switch ($country) {
            case('GBR'):
                if ($this->data['Order Ship To Country Code']=='GBR' or $this->data['Order Ship To Country Code']=='UNK') {
                    $tax_rate=0.175;
                    $tax_code='GBR.S';

                } else {
                    $sql=sprintf("select `European Union` from kbase.`Country Dimension` where `Country Code`=%s      "
                    ,prepare_mysql($country));
                    //print $sql;
                    $res=mysql_query($sql);
                    if ($row=mysql_fetch_array($res)) {
                        if ($row['European Union']=="Yes") {
                            $customer=new Customer($this->data['Order Customer Key']);

                            if ($customer->is_tax_number_valid()) {
                                $tax_rate=0;
                                $tax_code='GBR.EuroFree';
                            } else {
                                $tax_rate=0.175;
                                $tax_code='GBR.S';

                            }
                        } else {
                            $tax_rate=0;
                            $tax_code='GBR.OffEuroFree';

                        }


                    } else {
                        $tax_rate=0.175;
                        $tax_code='GBR.S';
                    }



                }

            }


            $this->data['Order Tax Rate']=$tax_rate;
            $this->data['Order Tax Code']=$tax_code;



        }
  
  
}

?>
