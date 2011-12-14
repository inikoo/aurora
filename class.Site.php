<?php
/*
 File: Site.php

 This file contains the Site Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2010, Inikoo

 Version 2.0
*/
include_once('class.DB_Table.php');
include_once('class.Page.php');
include_once('class.PageStoreSection.php');

class Site extends DB_Table {

    var $new=false;

    function Site($arg1=false,$arg2=false) {
        $this->table_name='Site';
        $this->ignore_fields=array('Site Key');


        if (!$arg1 and !$arg2) {
            $this->error=true;
            $this->msg='No arguments';
        }
        if (is_numeric($arg1)) {
            $this->get_data('id',$arg1);
            return;
        }



        if (is_array($arg2) and preg_match('/create|new/i',$arg1)) {
            $this->find($arg2,'create');
            return;
        }


        $this->get_data($arg1,$arg2);

    }


    function get_data($tipo,$tag) {


        $sql=sprintf("select * from `Site Dimension` where  `Site Key`=%d",$tag);
        $result =mysql_query($sql);
        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->id=$this->data['Site Key'];

            if ($this->data['Site Logo Data']!='')
                $this->data['Site Logo Data']=unserialize($this->data['Site Logo Data']);
            if ($this->data['Site Header Data']!='')
                $this->data['Site Header Data']=unserialize($this->data['Site Header Data']);
            if ($this->data['Site Content Data']!='')
                $this->data['Site Content Data']=unserialize($this->data['Site Content Data']);
            if ($this->data['Site Footer Data']!='')
                $this->data['Site Footer Data']=unserialize($this->data['Site Footer Data']);
            if ($this->data['Site Layout Data']!='')
                $this->data['Site Layout Data']=unserialize($this->data['Site Layout Data']);
            if ($this->data['Site Checkout Mals Metadata']!='')
                $this->data['Site Checkout Mals Metadata']=unserialize($this->data['Site Checkout Mals Metadata']);
            else {
                $this->data['Site Checkout Mals Metadata']=array('id'=>'','url'=>'','url_multi'=>'');
            }




        }


    }



    function get_mals_data($item) {
        return $this->data['Site Checkout Mals Metadata'][$item];
    }

    function find($raw_data,$options) {






        if (isset($raw_data['editor'])) {
            foreach($raw_data['editor'] as $key=>$value) {
                if (array_key_exists($key,$this->editor))
                    $this->editor[$key]=$value;
            }
        }

        $this->found=false;
        $this->found_key=false;

        $create='';
        $update='';
        if (preg_match('/create/i',$options)) {
            $create='create';
        }
        if (preg_match('/update/i',$options)) {
            $update='update';
        }





        $sql=sprintf("select `Site Key` from `Site Dimension` where `Site Name`=%s and `Site Store Key`=%d ",

                     prepare_mysql($raw_data['Site Name']),
                     $raw_data['Site Store Key']

                    );

        $res=mysql_query($sql);
        if ($row=mysql_fetch_assoc($res)) {
            $this->found=true;
            $this->found_key=$row['Site Key'];
            $this->get_data('id',$this->found_key);
        }


        if ($create and !$this->found) {
            $this->create($raw_data);
        }

    }


    function create($raw_data) {

//print_r($raw_data);

        $data=$this->base_data();


        foreach($raw_data as $key=>$value) {
            if (array_key_exists($key,$data))



                if (is_array($value))
                    $data[$key]=serialize($value);
                else
                    $data[$key]=_trim($value);


        }



        $keys='(';
        $values='values(';
        foreach($data as $key=>$value) {
            $keys.="`$key`,";

            $values.=prepare_mysql($value).",";
        }
        $keys=preg_replace('/,$/',')',$keys);
        $values=preg_replace('/,$/',')',$values);
        $sql=sprintf("insert into `Site Dimension` %s %s",$keys,$values);


        if (mysql_query($sql)) {
            $this->id=mysql_insert_id();
            $this->get_data('id',$this->id);


            $this->create_site_page_sections();



        } else {
            $this->error=true;
            $this->msg='Can not insert Site Dimension';
            exit("$sql\n");
        }


    }



    function create_site_page_sections() {
        $sections=getEnumValues('Page Store Dimension', 'Page Store Section');

        foreach($sections as $section) {
            $sql=sprintf("INSERT INTO `Page Store Section Dimension` (
                         `Page Store Section Key` ,
                         `Site Key` ,
                         `Page Store Section Code` ,
                         `Page Store Section Logo Data` ,
                         `Page Store Section Header Data` ,
                         `Page Store Section Content Data` ,
                         `Page Store Section Footer Data` ,
                         `Page Store Section Layout Data`
                         )
                         VALUES (
                         NULL , %s, %s, NULL , NULL , NULL , NULL , NULL
                         );
                         ",$this->id,prepare_mysql($section));

            mysql_query($sql);

        }
    }




    function get($key) {



        switch ($key) {

        default:
            if (isset($this->data[$key]))
                return $this->data[$key];
        }
        return false;
    }





    function update_mals_data($field,$value) {
        $this->data['Site Checkout Mals Metadata'][$field]=$value;

        $sql=sprintf("update `Site Dimension` set `Site Checkout Mals Metadata`=%s where `Site Key`=%d",
                     prepare_mysql(serialize($this->data['Site Checkout Mals Metadata'])),
                     $this->id
                    );
        mysql_query($sql);
        $this->updated=true;
        $this->new_value=$value;
    }




    function update_field_switcher($field,$value,$options='') {

        switch ($field) {
        
        case('Site Menu HTML'):
        case('Site Menu CSS'):
        case('Site Menu Javascript'):
        case('Site Search HTML'):
        case('Site Search CSS'):
        case('Site Search Javascript'):      
     $this->update_field($field,$value,'no_history');
     break;
        case 'mals_id':
            $this->update_mals_data('id',$value);
            break;
        case 'mals_url':
            $this->update_mals_data('url',$value);
            break;
        case 'mals_url_multi':
            $this->update_mals_data('url_multi',$value);
            break;

        default:
            $base_data=$this->base_data();

            if (array_key_exists($field,$base_data)) {

                if ($value!=$this->data[$field]) {

                    $this->update_field($field,$value,$options);
                }
            }

        }



    }



    function add_page($page_data) {
        $page_data['Page Store Key']=$this->data['Site Store Key'];
        $page_data['Page Parent Key']=$this->data['Site Store Key'];
        $page_data['Page Site Key']=$this->id;

        $page_section=new PageStoreSection('code',$page_data['Page Store Section']);
        $page_data['Page Store Section Key']=$page_section->id;

        $page=new Page('find',$page_data,'create');

    }

    function add_index_page($data) {

        $store= new Store($this->data['Site Store Key']);

        if (array_key_exists('Showcases',$data))
            $showcases=$data['Showcases'];
        else
            $showcases['Presentation']=array('Display'=>true,'Type'=>'Template','Contents'=>$store->data['Store Name']);

        if (array_key_exists('Showcases',$data))
            $product_layouts=$data['Product Layouts'];
        else
            $product_layouts=array('List'=>array('Display'=>true,'Type'=>'Auto'));
        if (isset($data['Showcases Layout']))
            $showcases_layout=$data['Showcases Layout'];
        else
            $showcases_layout='';
        $page_data=array(
                       'Page Site Key'=>$this->id,
                       'Page Code'=>'index',
                       'Page Source Template'=>'pages/'.$store->data['Store Code'].'/catalogue',
                       'Page URL'=>'catalogue.php?code='.$store->data['Store Code'],
                       'Page Description'=>'Store Catalogue',
                       'Page Title'=>$store->data['Store Name'],
                       'Page Short Title'=>$store->data['Store Name'],
                       'Page Store Title'=>$store->data['Store Name'],
                       'Page Store Subtitle'=>'',
                       'Page Store Slogan'=>$data['Page Store Slogan'],
                       'Page Store Resume'=>$data['Page Store Resume'],
                       'Page Store Showcases'=>$showcases,
                       'Page Store Showcases Layout'=>$showcases_layout,
                       'Page Store Product Layouts'=>$product_layouts
                   );

        if (array_key_exists('Page Store Header Data',$data)) {
            $page_data['Page Store Header Data']=$data['Page Store Header Data'];
        }
        if (array_key_exists('Page Store Footer Data',$data)) {
            $page_data['Page Store Footer Data']=$data['Page Store Footer Data'];
        }
        if (array_key_exists('Page Store Content Data',$data)) {
            $page_data['Page Store Content Data']=$data['Page Store Content Data'];
        }
        if (array_key_exists('Page Store Layout Data',$data)) {
            $page_data['Page Store Layout Data']=$data['Page Store Layout Data'];
        }
        if (array_key_exists('Page Store Logo Data',$data)) {
            $page_data['Page Store Logo Data']=$data['Page Store Logo Data'];
        }
        $page_data['Page Store Section']='Front Page Store';
        $page_section=new PageStoreSection('code',$page_data['Page Store Section']);
        $page_data['Page Store Section Key']=$page_section->id;
        $page_data['Page Store Creation Date']=date('Y-m-d H:i:s');
        $page_data['Page Store Last Update Date']=date('Y-m-d H:i:s');
        $page_data['Page Store Last Structural Change Date']=date('Y-m-d H:i:s');
        $page_data['Page Type']='Store';
        $page_data['Page Section']='catalogue';
        $page_data['Page Store Source Type'] ='Dynamic';
        $page_data['Page Store Key']=$store->data['Store Key'];
        $page_data['Page Parent Key']=$store->data['Store Key'];



        $page=new Page('find',$page_data,'create');

        $sql=sprintf("update `Site Dimension` set `Site Index Page Key`=%d  where `Site Key`=%d",$page->id,$this->id);
        //  print $sql;
        mysql_query($sql);

    }

    function add_cataloge_page($data) {

        $store= new Store($this->data['Site Store Key']);

        if (array_key_exists('Showcases',$data))
            $showcases=$data['Showcases'];
        else
            $showcases['Presentation']=array('Display'=>true,'Type'=>'Template','Contents'=>$store->data['Store Name']);

        if (array_key_exists('Showcases',$data))
            $product_layouts=$data['Product Layouts'];
        else
            $product_layouts=array('List'=>array('Display'=>true,'Type'=>'Auto'));
        if (isset($data['Showcases Layout']))
            $showcases_layout=$data['Showcases Layout'];
        else
            $showcases_layout='';
        $page_data=array(
                       'Page Site Key'=>$this->id,
                       'Page Code'=>'SD_'.$store->data['Store Code'],
                       'Page Source Template'=>'pages/'.$store->data['Store Code'].'/catalogue',
                       'Page URL'=>'catalogue.php?code='.$store->data['Store Code'],
                       'Page Description'=>'Store Catalogue',
                       'Page Title'=>$store->data['Store Name'],
                       'Page Short Title'=>$store->data['Store Name'],
                       'Page Store Title'=>$store->data['Store Name'],
                       'Page Store Subtitle'=>'',
                       'Page Store Slogan'=>$data['Page Store Slogan'],
                       'Page Store Resume'=>$data['Page Store Resume'],
                       'Page Store Showcases'=>$showcases,
                       'Page Store Showcases Layout'=>$showcases_layout,
                       'Page Store Product Layouts'=>$product_layouts
                   );

        $page_data['Page Store Section']='Store Catalogue';
        $page_section=new PageStoreSection('code',$page_data['Page Store Section']);
        $page_data['Page Store Section Key']=$page_section->id;
        $page_data['Page Store Creation Date']=date('Y-m-d H:i:s');
        $page_data['Page Store Last Update Date']=date('Y-m-d H:i:s');
        $page_data['Page Store Last Structural Change Date']=date('Y-m-d H:i:s');
        $page_data['Page Type']='Store';
        $page_data['Page Section']='catalogue';
        $page_data['Page Store Source Type'] ='Dynamic';
        $page_data['Page Store Key']=$store->data['Store Key'];
        $page_data['Page Parent Key']=$store->data['Store Key'];
//print_r($page_data);
        $page=new Page('find',$page_data,'create');



    }


    function add_department_page($data) {

        $department=new Department($data['Page Parent Key']);
        $store=new Store($department->data['Product Department Store Key']);

        $index_page=$this->get_page_object('index');
//if(!is_object($page)){

//}

        //        print_r($page->data);
//exit;
        if (!array_key_exists('Showcases',$data)) {

            $showcases=array();

            if ($store_page_data['Display Presentation']='Yes'  ) {
                $showcases['Presentation']=array('Display'=>true,'Type'=>'Template','Contents'=>$department->data['Product Department Name']);
            }
            if ($store_page_data['Display Offers']='Yes' ) {
                $showcases['Offers']=array('Display'=>true,'Type'=>'Auto');
            }
            if ($store_page_data['Display New Products']='Yes' ) {
                $showcases['New']=array('Display'=>true,'Type'=>'Auto');
            }
        } else
            $showcases=$data['Showcases'];


        if (!array_key_exists('Product Layouts',$data)) {

            $product_layouts=array();

            if ($store_page_data['Product Thumbnails Layout']='Yes' ) {
                $product_layouts['Thumbnails']=array('Display'=>true,'Type'=>'Auto');
            }

            if ($store_page_data['List Layout ']='Yes' ) {
                $product_layouts['List']=array('Display'=>true,'Type'=>'Auto');
            }

            if ($store_page_data['Product Slideshow Layout']='Yes' ) {
                $product_layouts['Slideshow']=array('Display'=>true,'Type'=>'Auto');
            }
            if ($store_page_data['Product Manual Layout']='Yes' ) {
                $product_layouts['Manual']=array('Display'=>true,'Type'=>$index_page->data['Product Manual Layout Type'],'Data'=>$index_page->data['Product Manual Layout Data']);
            }

            if (count($product_layouts==0)) {
                $product_layouts['Thumbnails']=array('Display'=>true,'Type'=>'Auto');
            }
        } else
            $product_layouts=$data['Product Layouts'];

        if (!array_key_exists('Showcases Layout',$data))
            $showcases_layout=$store_page_data['Showcases Layout'];
        else
            $showcases_layout=$data['Showcases Layout'];




        if (!isset($data['Page Code']) or !$data['Page Code']) {
            $data['Page Code']=$this->get_unique_department_page_code($department);
        }





        $page_data=array(
                       'Page Code'=>$data['Page Code'],
                       'Page Site Key'=>$this->id,
                       'Page Source Template'=>'',
                       'Page URL'=>'department.php?code='.$department->data['Product Department Code'],
                       'Page Source Template'=>'pages/'.$store->data['Store Code'].'/department.tpl',
                       'Page Description'=>'Department Showcase Page',
                       'Page Title'=>$department->data['Product Department Name'],
                       'Page Short Title'=>$department->data['Product Department Name'],
                       'Page Store Title'=>$department->data['Product Department Name'],
                       'Page Store Subtitle'=>'',
                       'Page Store Slogan'=>$data['Page Store Slogan'],
                       'Page Store Abstract'=>$data['Page Store Resume'],
                       'Page Store Showcases'=>$showcases,
                       'Page Store Showcases Layout'=>$showcases_layout,
                       'Page Store Product Layouts'=>$product_layouts
                   );

        $page_data['Page Store Section']='Department Catalogue';
        $page_section=new PageStoreSection('code',$page_data['Page Store Section']);
        $page_data['Page Store Section Key']=$page_section->id;
        $page_data['Page Store Creation Date']=date('Y-m-d H:i:s');
        $page_data['Page Store Last Update Date']=date('Y-m-d H:i:s');
        $page_data['Page Store Last Structural Change Date']=date('Y-m-d H:i:s');
        $page_data['Page Section']='catalogue';
        $page_data['Page Type']='Store';
        $page_data['Page Store Source Type'] ='Dynamic';
        $page_data['Page Store Key']=$store->id;
        $page_data['Page Parent Key']=$department->id;

        $page=new Page('find',$page_data,'create');
        $this->new_page=$page->new;
        $this->msg=$page->msg;
        $this->error=$page->error;
//print_r($page);
//exit;
        //$sql=sprintf("update `Product Department Dimension` set `Product Department Page Key`=%d  where `Product Department Key`=%d",$page->id,$department->id);
        //mysql_query($sql);

    }


    function add_family_page($data) {

        $family=new Family($data['Page Parent Key']);
        $store=new Store($family->data['Product Family Store Key']);
        $index_page=$this->get_page_object('index');
        //      print_r($store_page_data);

        if (!array_key_exists('Showcases',$data)) {

            $showcases=array();

            if ($store_page_data['Display Presentation']='Yes'  ) {
                $showcases['Presentation']=array('Display'=>true,'Type'=>'Template','Contents'=>$family->data['Product Family Name']);
            }
            if ($store_page_data['Display Offers']='Yes' ) {
                $showcases['Offers']=array('Display'=>true,'Type'=>'Auto');
            }
            if ($store_page_data['Display New Products']='Yes' ) {
                $showcases['New']=array('Display'=>true,'Type'=>'Auto');
            }
        } else
            $showcases=$data['Showcases'];


        if (!array_key_exists('Product Layouts',$data)) {

            $product_layouts=array();

            if ($store_page_data['Product Thumbnails Layout']='Yes' ) {
                $product_layouts['Thumbnails']=array('Display'=>true,'Type'=>'Auto');
            }

            if ($store_page_data['List Layout ']='Yes' ) {
                $product_layouts['List']=array('Display'=>true,'Type'=>'Auto');
            }

            if ($store_page_data['Product Slideshow Layout']='Yes' ) {
                $product_layouts['Slideshow']=array('Display'=>true,'Type'=>'Auto');
            }
            if ($store_page_data['Product Manual Layout']='Yes' ) {
                $product_layouts['Manual']=array('Display'=>true,'Type'=>$index_page->data['Product Manual Layout Type'],'Data'=>$index_page->data['Product Manual Layout Data']);
            }

            if (count($product_layouts==0)) {
                $product_layouts['Thumbnails']=array('Display'=>true,'Type'=>'Auto');
            }
        } else
            $product_layouts=$data['Product Layouts'];

        if (!array_key_exists('Showcases Layout',$data))
            $showcases_layout=$store_page_data['Showcases Layout'];
        else
            $showcases_layout=$data['Showcases Layout'];


        if (!isset($data['Page Code']) or !$data['Page Code']) {
            $data['Page Code']=$this->get_unique_family_page_code($family);
        }



        $page_data=array(
                       'Page Code'=>$data['Page Code'],
                       'Page Source Template'=>'',
                       'Page Site Key'=>$this->id,
                       'Page URL'=>$data['Page URL'],
                       'Page Source Template'=>'pages/'.$store->data['Store Code'].'/family.tpl',
                       'Page Description'=>'Family Showcase Page',
                       'Page Title'=>$family->data['Product Family Name'],
                       'Page Short Title'=>$family->data['Product Family Name'],
                       'Page Store Title'=>$family->data['Product Family Name'],
                       'Page Store Subtitle'=>'',
                       'Page Store Slogan'=>$data['Page Store Slogan'],
                       'Page Store Abstract'=>$data['Page Store Resume'],
                       'Page Store Showcases'=>$showcases,
                       'Page Store Showcases Layout'=>$showcases_layout,
                       'Page Store Product Layouts'=>$product_layouts
                   );




        $page_data['Page Store Section']='Family Catalogue';
        $page_section=new PageStoreSection('code',$page_data['Page Store Section']);
        $page_data['Page Store Section Key']=$page_section->id;
        $page_data['Page Store Creation Date']=date('Y-m-d H:i:s');
        $page_data['Page Store Last Update Date']=date('Y-m-d H:i:s');
        $page_data['Page Store Last Structural Change Date']=date('Y-m-d H:i:s');
        $page_data['Page Section']='catalogue';
        $page_data['Page Type']='Store';
        $page_data['Page Store Source Type'] ='Dynamic';
        $page_data['Page Store Key']=$store->id;
        $page_data['Page Parent Key']=$family->id;


        $page=new Page('find',$page_data,'create');



        $this->new_page=$page->new;
        $this->msg=$page->msg;
        $this->error=$page->error;

//print_r($page);
//exit;


    }
    function base_data() {


        $data=array();
        $result = mysql_query("SHOW COLUMNS FROM `".$this->table_name." Dimension`");
        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit;
        }
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_assoc($result)) {
                if (!in_array($row['Field'],$this->ignore_fields)) {
                    $data[$row['Field']]=$row['Default'];
                    if (preg_match('/ Data$/',$row['Field'])) {
                        $data[$row['Field']]='a:0:{}';
                    }

                }
            }
        }

        return $data;
    }

    function get_page_object($tipo,$key=false) {
        $page=false;
        switch ($tipo) {
        case 'index':
            $page=New Page('id',$this->data['Site Index Page Key']);
            break;
        case 'department':
            $sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Store Section`='Department Catalogue' and `Page Parent Key`=%d ",
                         $key

                        );
            $res=mysql_query($sql);
            if ($row=mysql_fetch_assoc($res)) {
                $page=New Page('id',$row['Page Key']);
            }
            break;
        case 'family':
            $sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Store Section`='Family Catalogue' and `Page Parent Key`=%d ",
                         $key

                        );
            $res=mysql_query($sql);
            if ($row=mysql_fetch_assoc($res)) {
                $page=New Page('id',$row['Page Key']);
            }
            break;

        }
        return $page;
    }

    function get_data_for_smarty() {

//print_r($this->data);

        $data['logo']=$this->data['Site Logo Data']['Image Source'];


        $header_style='';
        if ($this->data['Site Header Data'] and array_key_exists('style',$this->data['Site Header Data']))
            foreach($this->data['Site Header Data']['style'] as $key=>$value) {
            $header_style.="$key:$value;";
        }
        $data['header_style']=$header_style;

        $footer_style='';
        if ($this->data['Site Footer Data'] and array_key_exists('style',$this->data['Site Footer Data']))
            foreach($this->data['Site Footer Data']['style'] as $key=>$value) {
            $footer_style.="$key:$value;";
        }
        $data['footer_style']=$footer_style;



        return $data;
    }

    function get_page_section_object($code) {
        $page_section=new PageStoreSection('code',$code,$this->id);
        return $page_section;
    }

    function get_unique_family_page_code($family) {

        if (!$this->is_page_store_code($family->data['Product Family Code']))
            return $family->data['Product Family Code'];

        for ($i = 2; $i <= 200; $i++) {

            if ($i<=100) {
                $suffix=$i;
            } else {
                $suffix=uniqid('', true);
            }

            if (!$this->is_page_store_code($family->data['Product Family Code'].$suffix))
                return $family->data['Product Family Code'].$suffix;
        }

        return $suffix;
    }


    function get_unique_department_page_code($department) {

        if (!$this->is_page_store_code($department->data['Product Department Code']))
            return $department->data['Product Department Code'];

        for ($i = 2; $i <= 200; $i++) {

            if ($i<=100) {
                $suffix=$i;
            } else {
                $suffix=uniqid('', true);
            }

            if (!$this->is_page_store_code($department->data['Product Department Code'].$suffix))
                return $department->data['Product Department Code'].$suffix;
        }

        return $suffix;
    }


    function is_page_store_code($query) {

        $sql=sprintf("select PS.`Page Code`,PS.`Page Key` from `Page Store Dimension`  PS where `Page Site Key`=%d and `Page Code`=%s  "
                     ,$this->id
                     ,prepare_mysql($query)
                    );

        $res=mysql_query($sql);

        if ($data=mysql_fetch_array($res)) {
            return true;


        } else {
            return false;
        }


    }

    function   update_headers($new_header_key=false) {

    }

    function set_default_header($new_header_key) {


        $old_header_key=$this->data['Site Default Header Key'];
        $this->update_field_switcher('Site Default Header Key',$new_header_key,'no history');


        if ($this->updated) {


            $sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Header Type`='SiteDefault' and `Page Site Key`=%d",$this->id);
            $res=mysql_query($sql);

            while ($row=mysql_fetch_array($res)) {
                $sql=sprintf("update  `Page Store Dimension` set `Page Header Key`=%d where `Page Key`=%d ",$new_header_key,$row['Page Key']);
                //  print "$sql<br>";
                mysql_query($sql);
            }
            $old_header=new PageHeader($old_header_key);
            $new_header=new PageHeader($new_header_key);
            $old_header->update_number_pages();
            $new_header->update_number_pages();

            $history_data=array(
                              'History Abstract'=>_('Site default header changed').' ('.$old_header->data['Page Header Name'].' &rarr; '.$new_header->data['Page Header Name'].')',
                              'History Details'=>'',

                              'Indirect Object'=>'Page Header',
                              'Indirect Object Key'=>$new_header_key
                          );
            $this->add_history($history_data);

        }

    }

    function   update_footers($new_footer_key=false) {

    }

    function set_default_footer($new_footer_key) {


        $old_footer_key=$this->data['Site Default Footer Key'];
        $this->update_field_switcher('Site Default Footer Key',$new_footer_key,'no history');


        if ($this->updated) {


            $sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Footer Type`='SiteDefault' and `Page Site Key`=%d",$this->id);
            $res=mysql_query($sql);

            while ($row=mysql_fetch_array($res)) {
                $sql=sprintf("update  `Page Store Dimension` set `Page Footer Key`=%d where `Page Key`=%d ",$new_footer_key,$row['Page Key']);
                //  print "$sql<br>";
                mysql_query($sql);
            }
            $old_footer=new PageFooter($old_footer_key);
            $new_footer=new PageFooter($new_footer_key);
            $old_footer->update_number_pages();
            $new_footer->update_number_pages();

            $history_data=array(
                              'History Abstract'=>_('Site default footer changed').' ('.$old_footer->data['Page Footer Name'].' &rarr; '.$new_footer->data['Page Footer Name'].')',
                              'History Details'=>'',

                              'Indirect Object'=>'Page Footer',
                              'Indirect Object Key'=>$new_footer_key
                          );
            $this->add_history($history_data);

        }

    }
    
    function display_search(){
    
 
    return $this->data['Site Search HTML'];
    }
    
    function display_menu(){
     return $this->data['Site Menu HTML'];
    }
    
    
}
?>
