<?php
include_once('common.php');


$_SESSION['add_products']=array();

$target_path = "uploads/";
$target_path = $target_path . $_REQUEST["PHPSESSID"].date('U');
if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
    $response='OK';
    $handle_csv = fopen($target_path, "r");
    $col=0;
    $error=true;
    $_code=array();
    $_units=array();
    $_description=array();
    $_price=array();
    $_rrp=array();
    $_scode=array();
    $_supplier_id=array();
    $_supplier_cost=array();
    $_weight=array();
    $_tipounit=array();
    $_export_code=array();
    $_note=array();
    
    $col=0;
    $allocate_dept=false;
    $allocate_fam=false;
    $allocate_prod=false;
    $new_dept=array();
    $new_fam=array();
    $new_prod_req=array();
    $new_prod2=array();
    $contador=0;
    $contador2=0;
    $contado3=0;


    $new_prod_dim=array();
    $new_prod_sup=array();
    $new_prod_opt=array();

    $department_toallocate=0;
    $family_toallocate=0;

     while(($cols = fgetcsv($handle_csv,0,"\t"))!== false){

       if($col==1){
	   if( !( $cols[0]=='ADDPRODS' and $cols[1]=='KAKTUS' and  $cols[2]=='V1.0')){
	     $error=true;
	     break;
	   }else
	     $error=false;
	 }
	 
       
       
       
       
       
       if($cols[0]=='NEWDEPT'){
	 $new_dept[]=array(0,$cols[0],$cols[1],$cols[2]);
	 $department_toallocate++;
       }

       if($cols[0]=='NEWFAM'){
	 $new_fam[]=array(0,$department_toallocate-1,$cols[0],$cols[1],$cols[2]);
	 $family_toallocate++;
       }
       




       if(preg_match('/NEWPROD/i',$cols[0])){
	 $new_prod_req[]=array(
			       2,
			       $family_toallocate-1
			       ,$cols[0]
			       ,$cols[1]
			       ,$cols[2]
			       ,$cols[3]
			       ,$cols[4]
			       ,$cols[6]
			       ,2
			       ,2
			       ,2
			       ,2
			       ,2
			       );
	 $new_prod_opt[]=array(
			       2,
			       $family_toallocate-1
			       ,$cols[0]
			       ,$cols[5]
			       ,$cols[7]
			       ,$cols[8]
			       ,1
			       ,1
			       ,1
			       );
	 
	 $new_prod_dim[]=array(
			       2,
			       $family_toallocate-1
			       ,$cols[0]
			       ,$cols[9]
			       ,$cols[10]
			       ,$cols[11]
			       ,$cols[12]
			       ,$cols[13]
			       ,$cols[14]
			       ,1
			       ,1
			       ,1
			       ,1
			       ,1
			       ,1
			       );
	 
	 

	 $new_prod_sup[]=array(
			       2,
			       $family_toallocate-1
			       ,$cols[0]
			       ,$cols[15]
			       ,$cols[16]
			       ,$cols[17]
			       ,0
			       ,0
			       ,1
			       );

	
     
       }
     


	 $col++;
     }

     //Chack if is ok to add the new departments
     $i=0;
     foreach($new_dept as $dept){
       $sql=sprintf("select count(*) as num from product_department where code='%s' or name='%s'",addslashes($dept[2]),addslashes($dept[3]));
       $res=mysql_query($sql);
       if($data=mysql_fetch_array($res, MYSQL_ASSOC))
	 $num=$data['num'];
       else{
	 $error=true;
       }
       if($num==0)
	 $new_dept[$i][0]=2;
       $i++;
     }


        $i=0;
       foreach($new_fam as $fam ){
       $sql=sprintf("select count(*) as num from product_group where description='%s' and  name='%s'",addslashes($fam[3]),addslashes($fam[4]));
       $res=mysql_query($sql);
       if($data=mysql_fetch_array($res, MYSQL_ASSOC))
	 $num=$data['num'];
       else{
	 $error=true;
       }
       if($num==0 ){
	 $new_fam[$i][0]=2;
	 
       }
       //$new_fam[$i][1]=$new_dept[$new_fam[$i][0]][1];
       $i++;
       }



       $i=0;
       foreach($new_prod_opt as $prod ){
	 $malos=0;
	 if(!is_numeric($prod[4]) ){
	   $new_prod_opt[$i][7]=0;
	   $new_prod_opt[$i][0]=1;
	   $malos++;
	 }
	 if(!is_numeric($prod[6]) ){
	   $new_prod_opt[$i][9]=0;
	   $new_prod_opt[$i][0]=1;
	   $malos++;
	 }
	 if( $prod[5]==''  ){
	   $new_prod_opt[$i][8]=0;
	   $new_prod_opt[$i][0]=1;
	   $malos++;
	 }
	 if($malos==0)
	   $new_prod_opt[$i][0]=2;
       $i++;
       }

       $i=0;
       foreach($new_prod_dim as $prod ){
	 $malos=0;
	 if(!is_numeric($prod[4]) ){
	   $new_prod_dim[$i][7]=0;
	   $new_prod_dim[$i][0]=1;
	   $malos++;
	 }
	 if(!is_numeric($prod[5]) ){
	   $new_prod_dim[$i][8]=0;
	   $new_prod_dim[$i][0]=1;
	   $malos++;
	 }
	 if(!is_numeric($prod[6]) ){
	   $new_prod_dim[$i][9]=0;
	   $new_prod_dim[$i][0]=1;
	   $malos++;
	 }


	 if( $prod[7]==''  ){
	   $new_prod_dim[$i][10]=0;
	   $new_prod_dim[$i][0]=1;
	   $malos++;
	 }
	 if( $prod[8]==''  ){
	   $new_prod_dim[$i][11]=0;
	   $new_prod_dim[$i][0]=1;
	   $malos++;
	 }
	 if( $prod[9]==''  ){
	   $new_prod_dim[$i][12]=0;
	   $new_prod_dim[$i][0]=1;
	   $malos++;
	 }

	 if($malos==0)
	   $new_prod_dim[$i][0]=2;
       $i++;
       }



       $i=0;
       foreach($new_prod_sup as $prod ){
	 $sql=sprintf("select id,name from supplier where id='%s'",addslashes($prod[4]));
	 $res=mysql_query($sql);
	 if($data=mysql_fetch_array($res, MYSQL_ASSOC)){
	   $new_prod_sup[$i][0]=2;
	   $new_prod_sup[$i][7]=2;
	 }
	 else{
	   $new_prod_sup[$i][0]=0;
	   $new_prod_sup[$i][7]=0;
	 }
	 
	 if(!is_numeric($prod[7])){
	     $new_prod_sup[$i][0]=1;
	     $new_prod_sup[$i][9]=1;
	 }else{
	   $new_prod_sup[$i][10]=2;
	 }


	 if($prod[6]==''){
	   $new_prod_sup[$i][0]=0;
	   $new_prod_sup[$i][8]=0;
	 }else
	   $new_prod_sup[$i][8]=2;
	 

	 //       $new_prod_sup[$i][0]=$new_fam[$new_prod_sup[$i][0]][3];
       $i++;
       }
       
       
       $i=0;
       foreach($new_prod_req as $prod ){
       $sql=sprintf("select count(*) as num from product where code='%s'",addslashes($prod[3]));
       $res=mysql_query($sql);
       if($data=mysql_fetch_array($res, MYSQL_ASSOC))
	 $num=$data['num'];
       else{
	 $error=true;
       }
       if($num!=0 or $prod[3]==''  ){
	 $new_prod_req[$i][0]=0;
	 $new_prod_req[$i][8]=0;
	 
	 
       }else{
	 $new_prod_req[$i][0]=2;
	 $new_prod_req[$i][8]=2;
       }


       if($prod[4]==''){
	 $new_prod_req[$i][0]=0;
	 $new_prod_req[$i][9]=0;
       }
       if(!is_numeric($prod[5])){
	 $new_prod_req[$i][0]=0;
	 $new_prod_req[$i][10]=0;
       } 

       if(!is_numeric($prod[6])){
	 $new_prod_req[$i][0]=0;
	 $new_prod_req[$i][11]=0;
       } 
       if(!is_numeric($prod[7])){
	 $new_prod_req[$i][0]=0;
	 $new_prod_req[$i][12]=0;
       } 


       //       $new_prod_req[$i][0]=$new_fam[$new_prod_req[$i][0]][3];
       $i++;
       }
       

     
     
     // print the things to be inputed
     
     //print_r($new_dept);
       // print_r($new_fam);
       //print_r($new_prod);

       $_SESSION['add_products'][0]=$new_dept;
       $_SESSION['add_products'][1]=$new_fam;
       $_SESSION['add_products'][2]=$new_prod_req;
       $_SESSION['add_products'][3]=$new_prod_opt;
       $_SESSION['add_products'][4]=$new_prod_dim;
       $_SESSION['add_products'][5]=$new_prod_sup;







 }




print_r( $_SESSION['add_products']);

$smarty->assign('box_layout','yui-t0');


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'css/common.css',
		 'css/container.css',
		 'css/button.css',
		 'css/table.css'
		 );
$js_files=array(

		$yui_path.'utilities/utilities.js',
		$yui_path.'container/container.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'button/button.js',
		$yui_path.'autocomplete/autocomplete.js',
		$yui_path.'datasource/datasource-beta.js',
		$yui_path.'datatable/datatable-beta.js',
		$yui_path.'json/json-min.js',
		'js/common.js',
		'js/table_common.js'
		,'js/upload_assets.js'
		);






$smarty->assign('parent','products');
$smarty->assign('title', _('Uploading new products file'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$smarty->assign('dept',$new_dept);
$smarty->assign('fam',$new_fam);
$smarty->assign('prod',$new_prod_req);
$smarty->assign('prodopt',$new_prod_opt);

$smarty->assign('prodsup',$new_prod_sup);
$smarty->assign('proddim',$new_prod_dim);


$smarty->display('upload_assets.tpl');









?>