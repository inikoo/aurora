<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Site.php');
include_once('../../class.Image.php');

include_once('../../class.Page.php');
include_once('../../class.Store.php');
error_reporting(E_ALL);

date_default_timezone_set('UTC');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
    print "Error can not connect with database server\n";
    exit;
}
//$dns_db='dw_avant2';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
    print "Error can not access the database\n";
    exit;
}


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';

global $myconf;


$store_code='ES';
$store_key=1;


//$site=new Site(2);






$sql=sprintf("select P.`Page Key` from `Page Dimension` P  left join `Page Store Dimension` PS on (P.`Page Key`=PS.`Page Key`)  where `Page Type`='Store'  and `Page Site Key`=2 ");
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {



    $sql=sprintf("delete from `Page Dimension` where `Page Key`=%d",$row['Page Key']);
    // print "$sql\n";
    mysql_query($sql);
    $sql=sprintf("delete from `Page Store Dimension` where `Page Key`=%d",$row['Page Key']);
    mysql_query($sql);



    //print "$sql\n";

}
exit;


$sql=sprintf("select * from `Product Department Dimension` left join  `Store Dimension` on (`Product Department Store Key`=`Store Key`)  where `Product Department Sales Type`='Public Sale'  ");

$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {

    $store=new Store($row['Product Department Store Key']);
    $site_keys=$store->get_active_sites_keys();

    foreach($site_keys as $site_key) {
        $site=new Site($site_key);
        $data=array();
        $department=new Department($row['Product Department Key']);
        
        if($department->data['Product Department Code']=='ND' or $department->data['Product Department Code']=='Promo')
        	continue;
        
        $data=array();
        $data['Page Parent Key']=$department->id;
        $data['Page Store Slogan']=(isset($department_data[$row['Store Code'].'_'.$row['Product Department Code']]['Slogan'])?$department_data[$row['Store Code'].'_'.$row['Product Department Code']]['Slogan']:'');
        $data['Page Store Resume']=(isset($department_data[$row['Store Code'].'_'.$row['Product Department Code']]['Resume'])?$department_data[$row['Store Code'].'_'.$row['Product Department Code']]['Resume']:'');
        $data['Page Store Section']='Department Catalogue';
        $data['Showcases Layout']='Splited';

        $site->add_department_page($department->id,$data);
      
    }
}


$sql=sprintf("select * from `Product Family Dimension` left join  `Store Dimension` on (`Product Family Store Key`=`Store Key`)  where `Product Family Sales Type`='Public Sale'  ");

$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {

    $store=new Store($row['Product Family Store Key']);
    $site_keys=$store->get_active_sites_keys();
    foreach($site_keys as $site_key) {
        $site=new Site($site_key);

        $family=new Family($row['Product Family Key']);
        $data=array();
        $data['Page Parent Key']=$family->id;
        $data['Page Store Slogan']=(isset($family_data[$row['Store Code'].'_'.$row['Product Family Code']]['Slogan'])?$family_data[$row['Store Code'].'_'.$row['Product Family Code']]['Slogan']:'');
        $data['Page Store Resume']=(isset($family_data[$row['Store Code'].'_'.$row['Product Family Code']]['Resume'])?$family_data[$row['Store Code'].'_'.$row['Product Family Code']]['Resume']:'');
        $data['Page Store Section']='Family Catalogue';
        $data['Showcases Layout']='Splited';
         $data['Page URL']='www.ancientwisdom.biz/forms/'.strtolower($row['Product Family Code']);
      
      print "cretate page\n";
      //    $data['Page Code']=strtolower($row['Product Family Code']);
        $site->add_family_page($family->id,$data);
    }
}




exit;



include_once('gb_create_main_pages.php');
//include_once('de_create_main_pages.php');
//include_once('fr_create_main_pages.php');
//include_once('pl_create_main_pages.php');





chdir('../../');

foreach($store_data as $store_code=>$xdata) {
    $store=new Store('code',$store_code);
    $site_data=$xdata['site_data'];
    $data=array();
    $data['Site Name']=$site_data['Site Name'];

/*
    if (isset($site_data['Site Logo Data']['image_filename'])) {
        $logo_file=$site_data['Site Logo Data']['image_filename'];
        $iamge_data=array(
                        'file'=>$site_data['Site Logo Data']['image_filename'],
                        'source_path'=>'mantenence/scripts/',
                        'path'=>'sites/app_files/pics/',
                        'name'=>'logo',
                        'caption'=>''
                    );
        $image=new Image('find',$iamge_data,'create');

        if ($image->id) {
            $site_data['Site Logo Data']['Image Key']=$image->id;
            $site_data['Site Logo Data']['Image Source']=preg_replace('/^sites./','',$image->get_url());
        }else{
             exit("image not found\n");
        }
    }
*/






    $data_tag='Site Header Data';
          if (isset($site_data[$data_tag]['style']['background-image'])) {
                $file=$site_data[$data_tag]['style']['background-image'];
                $image_data=array(
                                'file'=>$file,
                                'source_path'=>'mantenence/scripts/',
                                'path'=>'sites/app_files/pics/',
                                'name'=>'background_image',
                                'caption'=>''
                            );
                $image=new Image('find',$image_data,'create');
                if ($image->id) {
                    $image_src=preg_replace('/^sites./','',$image->get_url());
                    
                    
                   
                    $site_data[$data_tag]['style']['background-image']="url('$image_src')";
                } else {
                    print $image->msg."\n";
                    exit("bad image\n");
                }
            }
            
                    $data_tag='Site Footer Data';
          if (isset($site_data[$data_tag]['style']['background-image'])) {
                $file=$site_data[$data_tag]['style']['background-image'];
                $image_data=array(
                                'file'=>$file,
                                'source_path'=>'mantenence/scripts/',
                                'path'=>'sites/app_files/pics/',
                                'name'=>'background_image',
                                'caption'=>''
                            );
                $image=new Image('find',$image_data,'create');
                if ($image->id) {
                    $image_src=preg_replace('/^sites./','',$image->get_url());
                    
                    
                   
                    $site_data[$data_tag]['style']['background-image']="url('$image_src')";
                } else {
                    print $image->msg."\n";
                    exit("bad image\n");
                }
            }



print_r($site_data);
//print_r($data);
exit;


    $site=$store->create_site($site_data);
   // $site->create_site_page_sections();
    foreach($page_store_secton_data as $key=>$value){
            $page_section=$site->get_page_section_object($key);
            $page_section->update($value);
           // print_r($page_section);
    }

}



foreach($page_data as $store_code=>$data) {
    $store=new Store('code',$store_code);

    $site_keys=$store->get_active_sites_keys();
    foreach($site_keys as $site_key) {

        $site=new Site($site_key);

        foreach($data as $page_data) {
            $page_data['Page Store Order Template']='No Applicable';

            $page_data['Page Store Creation Date']=date('Y-m-d H:i:s');
            $page_data['Page Store Last Update Date']=date('Y-m-d H:i:s');
            $page_data['Page Store Last Structural Change Date']=date('Y-m-d H:i:s');
            $page_data['Page Type']='Store';
            $page_data['Page Store Source Type'] ='Static';


            $data_tag='Page Store Header Data';
          if (isset($page_data[$data_tag]['style']['background-image'])) {
                $file=$page_data[$data_tag]['style']['background-image'];
                $image_data=array(
                                'file'=>$file,
                                'source_path'=>'mantenence/scripts/',
                                'path'=>'sites/app_files/pics/',
                                'name'=>'background_image',
                                'caption'=>''
                            );
                $image=new Image('find',$image_data,'create');
                if ($image->id) {
                    $image_src=preg_replace('/^sites./','',$image->get_url());
                    
                    
                   
                    $page_data[$data_tag]['style']['background-image']="url('$image_src')";
                } else {
                    print $image->msg."\n";
                    exit("bad image\n");
                }
            }
            
                    $data_tag='Page Store Footer Data';
          if (isset($page_data[$data_tag]['style']['background-image'])) {
                $file=$page_data[$data_tag]['style']['background-image'];
                $image_data=array(
                                'file'=>$file,
                                'source_path'=>'mantenence/scripts/',
                                'path'=>'sites/app_files/pics/',
                                'name'=>'background_image',
                                'caption'=>''
                            );
                $image=new Image('find',$image_data,'create');
                if ($image->id) {
                    $image_src=preg_replace('/^sites./','',$image->get_url());
                    
                    
                   
                    $page_data[$data_tag]['style']['background-image']="url('$image_src')";
                } else {
                    print $image->msg."\n";
                    exit("bad image\n");
                }
            }
            
            
            $data_tag='Page Store Content Data';
            if (isset($page_data[$data_tag]['style']['background-image'])) {
                $file=$page_data[$data_tag]['style']['background-image'];
                $image_data=array(
                                'file'=>$file,
                                'source_path'=>'mantenence/scripts/',
                                'path'=>'sites/app_files/pics/',
                                'name'=>'background_image',
                                'caption'=>''
                            );
                $image=new Image('find',$image_data,'create');
                if ($image->id) {
                    $image_src=preg_replace('/^sites./','',$image->get_url());
                   
                    
                   
                    $page_data[$data_tag]['style']['background-image']="url('$image_src')";
                } else {
                    print $image->msg."\n";
                    exit("bad image\n");
                }
            }

            if (isset($page_data['Page Store Content Data']['Showcases'])) {
                foreach($page_data['Page Store Content Data']['Showcases'] as $key=>$showcase) {
                    if ($showcase['type']=='banner') {
                        $file=$showcase['src'];
                        $image_data=array(
                                        'file'=>$file,
                                        'source_path'=>'mantenence/scripts/',
                                        'path'=>'sites/app_files/pics/',
                                        'name'=>'banner',
                                        'caption'=>''
                                    );
                        $image=new Image('find',$image_data,'create');
                        if ($image->id) {
                            $image_src=preg_replace('/^sites./','',$image->get_url());
                            $page_data['Page Store Content Data']['Showcases'][$key]['Image Key']=$image->id;
                            $page_data['Page Store Content Data']['Showcases'][$key]['src']=preg_replace('/^sites./','',$image->get_url());
                        } else {
                            print $image->msg."\n";
                            exit("\nCreate Site: bad image\n");
                        }
                    }elseif($showcase['type']=='div'){
            
                   if(isset($showcase['style']['background-image'])){
                    $file=$showcase['style']['background-image'];
                        $image_data=array(
                                        'file'=>$file,
                                        'source_path'=>'mantenence/scripts/',
                                        'path'=>'sites/app_files/pics/',
                                        'name'=>'div_bg_image',
                                        'caption'=>''
                                    );
                        $image=new Image('find',$image_data,'create');
                        if ($image->id) {
                            $image_src=preg_replace('/^sites./','',$image->get_url());
                            $page_data['Page Store Content Data']['Showcases'][$key]['Image Key']=$image->id;
                            $page_data['Page Store Content Data']['Showcases'][$key]['style']['background-image']="url('$image_src')";
                        } else {
                            print $image->msg."\n";
                            exit("\nCreate Site: bad image\n");
                        }
                    
                    }
                    
                    }
                }

            }

            if ($page_data['Page Code']=='home') {
              //  print_r($page_data);
              //exit;
              $site->add_index_page($page_data);
            } else {
                $site->add_store_page($page_data);
            }
           


        }


    }
}


//print_r($store_data);
foreach($store_data as $store_code=>$xdata) {
    $store=new Store('code',$store_code);
    $site_keys=$store->get_active_sites_keys();
    foreach($site_keys as $site_key) {
        $site=new Site($site_key);
        $data=array();
        $data['Page Store Slogan']=$xdata['Slogan'];
        $data['Page Store Resume']=$xdata['Resume'];
        $data['Showcases Layout']='Splited';
        $data['Page Store Section']='Store Catalogue';


        $site->add_cataloge_page($data);
    }

}



$sql=sprintf("select * from `Product Department Dimension` left join  `Store Dimension` on (`Product Department Store Key`=`Store Key`)  where `Product Department Sales Type`='Public Sale'  ");

$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {

    $store=new Store($row['Product Department Store Key']);
    $site_keys=$store->get_active_sites_keys();

    foreach($site_keys as $site_key) {
        $site=new Site($site_key);
        $data=array();
        $department=new Department($row['Product Department Key']);
        $data=array();
        $data['Page Parent Key']=$department->id;
        $data['Page Store Slogan']=(isset($department_data[$row['Store Code'].'_'.$row['Product Department Code']]['Slogan'])?$department_data[$row['Store Code'].'_'.$row['Product Department Code']]['Slogan']:'');
        $data['Page Store Resume']=(isset($department_data[$row['Store Code'].'_'.$row['Product Department Code']]['Resume'])?$department_data[$row['Store Code'].'_'.$row['Product Department Code']]['Resume']:'');
        $data['Page Store Section']='Department Catalogue';
        $data['Showcases Layout']='Splited';

        $site->add_department_page($data);
    }
}


$sql=sprintf("select * from `Product Family Dimension` left join  `Store Dimension` on (`Product Family Store Key`=`Store Key`)  where `Product Family Sales Type`='Public Sale'  ");

$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {

    $store=new Store($row['Product Family Store Key']);
    $site_keys=$store->get_active_sites_keys();
    foreach($site_keys as $site_key) {
        $site=new Site($site_key);

        $family=new Family($row['Product Family Key']);
        $data=array();
        $data['Page Parent Key']=$family->id;
        $data['Page Store Slogan']=(isset($family_data[$row['Store Code'].'_'.$row['Product Family Code']]['Slogan'])?$family_data[$row['Store Code'].'_'.$row['Product Family Code']]['Slogan']:'');
        $data['Page Store Resume']=(isset($family_data[$row['Store Code'].'_'.$row['Product Family Code']]['Resume'])?$family_data[$row['Store Code'].'_'.$row['Product Family Code']]['Resume']:'');
        $data['Page Store Section']='Family Catalogue';
        $data['Showcases Layout']='Splited';
         $data['Page URL']='www.ancientwisdom.biz/forms/'.strtolower($row['Product Family Code']);
      
      print "cretate page\n";
      //    $data['Page Code']=strtolower($row['Product Family Code']);
        $site->add_family_page($data);
    }
}


function delete_old_sites(){
mysql_query('TRUNCATE TABLE `Site Dimension`');

$sql=sprintf("select `Store Key` from  `Store Dimension  ");
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {
    $sql=sprintf("delete from `Site Dimension` where `Store Key`=%d",$row['Store Key']);
    mysql_query($sql);

}

//$sql=sprintf("select P.`Page Key` from `Page Dimension` P  left join `Page Store Dimension` PS on (P.`Page Key`=PS.`Page Key`)  where `Page Type`='Store'  and `Page Store Section`='Information' ");
$sql=sprintf("select P.`Page Key` from `Page Dimension` P  left join `Page Store Dimension` PS on (P.`Page Key`=PS.`Page Key`)  where `Page Type`='Store'  ");
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {



    $sql=sprintf("delete from `Page Dimension` where `Page Key`=%d",$row['Page Key']);
    // print "$sql\n";
    mysql_query($sql);
    $sql=sprintf("delete from `Page Store Dimension` where `Page Key`=%d",$row['Page Key']);
    mysql_query($sql);



    //print "$sql\n";

}

}

?>