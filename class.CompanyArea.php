<?php
/*
 File: CompanyArea.php

 This file contains the Company Area Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once('class.DB_Table.php');
include_once('class.Company.php');
include_once('class.CompanyDepartment.php');


class CompanyArea extends DB_Table {


    var $departments=false;



    function CompanyArea($arg1=false,$arg2=false,$arg3=false) {

        $this->table_name='Company Area';
        $this->ignore_fields=array('Company Area Key');

        if (preg_match('/^(new|create)$/i',$arg1) and is_array($arg2)) {
            $this->create($arg2);
            return;
        }

        if (preg_match('/find/i',$arg1)) {
            $this->find($arg2,$arg3);
            return;
        }
        if (is_numeric($arg1)) {
            $this->get_data('id',$arg1);
            return;
        }
        $this->get_data($arg1,$arg2);
    }

    /*
     Method: find
     Find W Area with similar data
    */

    function find($raw_data,$options) {

        if (isset($raw_data['editor'])) {
            foreach($raw_data['editor'] as $key=>$value) {

                if (array_key_exists($key,$this->editor))
                    $this->editor[$key]=$value;

            }
        }


        $this->found=false;
        $create='';
        $update='';
        if (preg_match('/create/i',$options)) {
            $create='create';
        }
        if (preg_match('/update/i',$options)) {
            $update='update';
        }

        $data=$this->base_data();
        foreach($raw_data as $key=>$val) {
            $_key=$key;
            $data[$_key]=$val;
        }


        //look for areas with the same code in the same Company
        $sql=sprintf("select `Company Area Key` from `Company Area Dimension` where `Company Key`=%d and `Company Area Code`=%s"
                     ,$data['Company Key']
                     ,prepare_mysql($data['Company Area Code']));


        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            $this->found=true;
            $this->found_key=$row['Company Area Key'];
        }

        //what to do if found
        if ($this->found) {
            $this->get_data('id',$this->found_key);
        }


        if ($create) {
            if ($this->found) {
                $this->update($raw_data,$options);
            } else {

                $this->create($data,$options);

            }


        }
    }


    function create ($data,$options='') {

        $this->data=$this->base_data();
        foreach($data as $key=>$value) {
            if (array_key_exists($key,$this->data))
                $this->data[$key]=_trim($value);
        }

        if ($this->data['Company Area Code']=='') {
            $this->msg=('Wrong Company area name');
            $this->new=false;
            $this->error=true;
            return;
        }
        $Company=new Company('id',$this->data['Company Key']);
        if (!$Company->id) {
            $this->msg=('Wrong Company key');
            $this->new=false;
            $this->error=true;
            return;

        }
        if ($this->data['Company Area Name']=='') {
            $this->data['Company Area Name']=$this->data['Company Area Code'];
        }

        $keys='(';
        $values='values(';
        foreach($this->data as $key=>$value) {

            $keys.="`$key`,";
            $_mode=true;
            if ($key=='Company Area Description')
                $_mode=false;
            $values.=prepare_mysql($value,$_mode).",";
        }

        $keys=preg_replace('/,$/',')',$keys);
        $values=preg_replace('/,$/',')',$values);

        $sql=sprintf("insert into `Company Area Dimension` %s %s",$keys,$values);
        //print "$sql\n";
        // exit;
        if (mysql_query($sql)) {
            $this->id= mysql_insert_id();
            $this->get_data('id',$this->id);
            $note=_('Company Area Created');
            $details=_('Company Area')." ".$this->data['Company Area Code']." "._('created in')." ".$Company->data['Company Name'];


            $history_data=array(
				'History Abstract'=>$note
				,'History Details'=>$details
				,'Action'=>'associated'
				,'Preposition'=>'to'
				,'Indirect Object'=>'Company'
				,'Indirect Object Key'=>$Company->id
				
                          );
            $this->add_history($history_data);
            $this->new=true;





        } else {
            exit($sql);
        }

    }

    function get_data($key,$tag) {

        if ($key=='id')
            $sql=sprintf("select * from `Company Area Dimension` where `Company Area Key`=%d",$tag);
        else if ($key=='code')
            $sql=sprintf("select  *  from `Company Area Dimension` where `Company Area Code`=%s ",prepare_mysql($tag));
        else
            return;

        $result=mysql_query($sql);
        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->id=$this->data['Company Area Key'];
        }




    }





    function load($key='') {
        switch ($key) {
        case('departments'):

            break;

        }


    }


    function get($key,$data=false) {
        switch ($key) {
        case('num_departments'):
        case('number_departments'):
            if (!$this->areas)
                $this->load('areas');
            return count($this->areas);
            break;
        case('departments'):
            if (!$this->departments)
                $this->load('departments');
            return $this->departments;
            break;
        case('area'):
            if (!$this->departments)
                $this->load('departments');
            if (isset($this->departments[$data['id']]))
                return $this->departments[$data['id']];
            break;
        default:
            if (isset($this->data[$key]))
                return $this->data[$key];
            else
                return '';
        }
        return '';
    }


function add_department($data) {
        $this->new_area=false;
        $data['Company Key']=$this->data['Company Key'];
        //$data['Company Area Key']=$this->id;
        $department= new CompanyDepartment('find',$data,'create');
        if($department->id){
	  $this->new_department_msg=$department->msg;
	  
	  if ($department->new){
            $this->new_department=true;
	    
	  }else {
            if ($department->found)
	      $this->new_department_msg=_('department Code already in the Company');
	  }
	  $this->associate_department($department->id);
        }
        
    }

function get_department_keys(){
    $department_keys=array();
    $sql=sprintf("select `Department Key` from `Company Area Department Bridge` where `Area Key`=%d",$this->id);
    //print $sql;
    $res=mysql_query($sql);
    while($row=mysql_fetch_array($res)){
        $department_keys[$row['Department Key']]=$row['Department Key'];
    }
    return $department_keys;
}
function associate_department($department_key){
    if(!array_key_exists($department_key,$this->get_department_keys())){
        $sql=sprintf("insert into `Company Area Department Bridge` values (%d,%d) ",$this->id,$department_key);
        mysql_query($sql);
        
        $company=new Company($this->data['Company Key']);
                $department=new CompanyDepartment($department_key);
		
		$note=_('Company Department Created');
		$details=_('Company Department')." ".$department->data['Company Department Code']." "._('created in')." ".$company->data['Company Name'];

		
		$history_data=array(
				    'History Abstract'=>$note
				,'History Details'=>$details
				    ,'Action'=>'associated'
				    ,'Preposition'=>'to'
				,'Direct Object'=>'Company Department'
				    ,'Direct Object Key'=>$department_key
				    
				    ,'Indirect Object'=>'Company'
				,'Indirect Object Key'=>$company->id

				);
            $this->add_history($history_data);
        
        
    }

}
function load_children() {



    }
function update_children() {

        $sql=sprintf('select count(*) as number from `Company Department Dimension` where `Company Area Key`=%d',$this->id);
        $res-mysql_query($sql);
        $number_departments=0;
        if ($row=mysql_fetch_array($res)) {
            $number_departments=$row['number'];
        }
        $sql=sprintf('select count(*) as number from `Company Position Dimension` where `Company Area Key`=%d',$this->id);
        $res-mysql_query($sql);
        $number_positions=0;
        if ($row=mysql_fetch_array($res)) {
            $number_positions=$row['number'];
        }        
        $sql=sprintf('select count(*) as number from `Staff Dimension` where `Company Area Key`=%d',$this->id);
        $res-mysql_query($sql);
        $number_employees=0;
        if ($row=mysql_fetch_array($res)) {
            $number_employees=$row['number'];
        }

        $sql=sprintf('update `Company Area Dimension` set `Company Area Number Departments`=%d,`Company Area Number Positions`=%d,`Company Area Number Employees`=%d where `Company Area Key`=%d'
        ,$number_departments
        ,$number_positions
        ,$number_employees
        ,$this->id
        );
        mysql_query($sql);
        $this->get_data('id',$this->id);
    }
function load_positions(){
$this->positions=array();
$sql=sprintf('Select * from `Company Position Dimension` where `Company Area Key`=%d',$this->id);
$res=mysql_query($sql);
while($row=mysql_fetch_array($res,MYSQL_ASSOC)){
    $this->positions[$row['Company Position Key']]=$row;
}
}

function get_staff_with_position_code($position_code,$options=false){
$for_smarty=false;

$positions=array();
$sql=sprintf('Select * from `Staff Dimension` SD  left join `Company Position Staff Bridge` B on (B.`Staff Key`=SD.`Staff Key`) left join  `Company Position Dimension` CPD on (CPD.`Company Position Key`=B.`Position Key`) where `Staff Area Key`=%d and `Company Position Code`=%s' 
,$this->id
,prepare_mysql($position_code)
);
//print $sql;
$res=mysql_query($sql);
while($row=mysql_fetch_array($res,MYSQL_ASSOC)){


if($for_smarty){
foreach($row as $key=>$value){
$row_for_smarty[preg_replace('/\s/','',$key)]=$value;
}
    $positions[$row['Staff Key']]=$row_for_smarty;

}else{


    $positions[$row['Staff Key']]=$row;
}

}
return $positions;
}

function get_current_staff_with_position_code($position_code,$options=''){
$positions=array();
$sql=sprintf('Select * from `Staff Dimension` SD  left join `Company Position Staff Bridge` B on (B.`Staff Key`=SD.`Staff Key`) left join  `Company Position Dimension` CPD on (CPD.`Company Position Key`=B.`Position Key`) where `Staff Area Key`=%d and `Company Position Code`=%s and `Staff Currently Working`="Yes"' 
,$this->id
,prepare_mysql($position_code)
);
;
$smarty=false;
if(preg_match('/smarty/i',$options))
  $smarty=true;
$res=mysql_query($sql);
while($row=mysql_fetch_array($res,MYSQL_ASSOC)){
  if($smarty){
    $_row=array();
    foreach($row as $key=>$value){
      $_row[preg_replace('/\s/','',$key)]=$value;
    }
    
    $positions[$row['Staff Key']]=$_row;
  }else
    $positions[$row['Staff Key']]=$row;
}
return $positions;
}

function load_departments(){
$this->departments=array();
$sql=sprintf('Select * from `Company Department Dimension` where `Company Area Key`=%d',$this->id);
$res=mysql_query($sql);
while($row=mysql_fetch_array($res,MYSQL_ASSOC)){
    $this->departments[$row['Company Department Key']]=$row;
}
}



function delete(){
$this->deleted=false;
if($this->data['Company Area Number Employees']>0){
$this->msg=_('Company Area could not be deleted because').' '.gettext($this->data['Company Area Number Employees'],'employee','employees').' '.gettext($this->data['Company Area Number Employees'],'is','are').' '._('associated with it');
return;
}

$this->load_positions();
foreach($this->positions as $position_key=>$position){
    $position=new CompanyPosition($position_key);
    $position->editor=$this->editor;
    $position->delete();
}
$this->load_departments();
foreach($this->departments as $department_key=>$department){
    $department=new CompanyArea($department_key);
    $department->editor=$this->editor;
    $department->delete();
}



$sql=sprintf('delete from `Company Area Dimension` where `Company Area Key`=%d',$this->id);
mysql_query($sql);

$history_data=array(
                    'History Abstract'=>_('Company Area deleted').' ('.$this->data['Company Area Name'].')'
                    ,'History Details'=>_trim(_('Company Area')." ".$this->data['Company Area Name'].' ('.$this->data['Company Area Code'].') '._('has been permanently') )
		    ,'Action'=>'deleted'
		    );
 $this->add_history($history_data);
$this->deleted=true;

}





}

?>
