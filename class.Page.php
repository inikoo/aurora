<?php
/*
 File: Page.php

 This file contains the Page Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once('class.DB_Table.php');
include_once('class.PageStoreSection.php');
class Page extends DB_Table {

    var $new=false;

    function Page($arg1=false,$arg2=false,$arg3=false) {
        $this->table_name='Page';
        $this->ignore_fields=array('Page Key');


        if (!$arg1 and !$arg2) {
            $this->error=true;
            $this->msg='No arguments';
        }
        if (is_numeric($arg1)) {
            $this->get_data('id',$arg1);
            return;
        }
        if (is_string($arg1) and !$arg2) {
            $this->get_data('url',$arg1);
            return;
        }


        if (is_array($arg2) and preg_match('/create|new/i',$arg1)) {
            $this->find($arg2,$arg3.' create');
            return;
        }
        if (  preg_match('/find/i',$arg1)) {
            $this->find($arg2,$arg3);
            return;
        }

        $this->get_data($arg1,$arg2,$arg3);

    }


    function get_data($tipo,$tag,$tag2=false) {

        if (preg_match('/url|address|www/i',$tipo)) {
            $sql=sprintf("select * from `Page Dimension` where  `Page URL`=%s",prepare_mysql($tag));
        }
        elseif($tipo=='store_page_code') {
            $sql=sprintf("select * from `Page Store Dimension` PS left join `Page Dimension` P  on (P.`Page Key`=PS.`Page Key`) where `Page Code`=%s and `Page Store Key`=%d ",
                         prepare_mysql($tag2),
                         $tag
                        );


        }
        elseif($tipo=='site_code') {
            $sql=sprintf("select * from `Page Store Dimension` PS left join `Page Dimension` P  on (P.`Page Key`=PS.`Page Key`) where `Page Code`=%s and PS.`Page Site Key`=%d ",
                         prepare_mysql($tag2),
                         $tag
                        );

        }
        else {
            $sql=sprintf("select * from `Page Dimension` where  `Page Key`=%d",$tag);
        }
        $result =mysql_query($sql);
        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->id=$this->data['Page Key'];
            $this->type=$this->data['Page Type'];

            if ($this->type=='Store') {
                $sql=sprintf("select * from `Page Store Dimension` where  `Page Key`=%d",$this->id);
                $result2 =mysql_query($sql);
                if ($row=mysql_fetch_array($result2, MYSQL_ASSOC)) {
                    foreach($row as $key=>$value) {
                        $this->data[$key]=$value;


                    }

                    if ($this->data['Page Store Logo Data']!='')
                        $this->data['Page Store Logo Data']=unserialize($this->data['Page Store Logo Data']);
                    if ($this->data['Page Store Header Data']!='')
                        $this->data['Page Store Header Data']=unserialize($this->data['Page Store Header Data']);
                    if ($this->data['Page Store Content Data']!='')
                        $this->data['Page Store Content Data']=unserialize($this->data['Page Store Content Data']);
                    if ($this->data['Page Store Footer Data']!='')
                        $this->data['Page Store Footer Data']=unserialize($this->data['Page Store Footer Data']);
                    if ($this->data['Page Store Layout Data']!='')
                        $this->data['Page Store Layout Data']=unserialize($this->data['Page Store Layout Data']);

                }

            }
            elseif($this->type=='Internal') {
                $sql=sprintf("select * from `Page Internal Dimension` where  `Page Key`=%d",$this->id);
                $result2 =mysql_query($sql);
                if ($row=mysql_fetch_array($result2, MYSQL_ASSOC)) {
                    foreach($row as $key=>$value) {
                        $this->data[$key]=$value;
                    }

                }

            }


        }

    }


    function find($raw_data,$options) {

        if (isset($raw_data['editor'])) {
            foreach($raw_data['editor'] as $key=>$value) {

                if (array_key_exists($key,$this->editor))
                    $this->editor[$key]=$value;

            }
        }








        $create='';
        $update='';
        if (preg_match('/create/i',$options)) {
            $create='create';
        }
        if (preg_match('/update/i',$options)) {
            $update='update';
        }




        //   $sql=sprintf("select `Page Key` from `Page Dimension` P left join `Page Store Dimension` PS on (P.`Page Key`=PS.`Page Key`)   where `Page URL`=%s "
        //		 ,prepare_mysql($data['Page URL'])

        //		 );
        //$res=mysql_query($sql);
        //if($row=mysql_fetch_array($res)){
        //  $this->found=true;
        //  $this->found_key=$row['Page Key'];
        //  $this->get_data('id',$this->found_key);
        // }


        if (!$this->found and $create) {
            $this->create($raw_data);

        }


    }


    function get_options() {

        if (array_key_exists('Page Options',$this->data)) {

            return unserialize ( $this->data['Page Options'] );
        } else {
            return false;
        }

    }


    function create_internal($raw_data) {

        $data=$this->internal_base_data();
        foreach($raw_data as $key=>$value) {
            if (array_key_exists($key,$data))
                $data[$key]=_trim($value);

        }





        $keys='(';
        $values='values(';
        $data['Page Key']=$this->id;
        foreach($data as $key=>$value) {
            $keys.="`$key`,";


            $values.=prepare_mysql($value).",";
        }
        $keys=preg_replace('/,$/',')',$keys);
        $values=preg_replace('/,$/',')',$values);
        $sql=sprintf("insert into `Page Internal Dimension` %s %s",$keys,$values);
        //print $sql;
        if (mysql_query($sql)) {
            $this->id=mysql_insert_id();
            $this->get_data('id',$this->id);


        } else {
            $this->error=true;
            $this->msg='Can not insert Page Internal Dimension';
        }


    }


    function create($raw_data) {

        $this->new=false;
        if (!isset($raw_data['Page Code']) or  $raw_data['Page Code']=='') {

            $raw_data['Page Code']=preg_replace('/\s/','',strtolower($raw_data['Page Section'].'_'.$raw_data['Page Short Title']));
        }

        if (!isset($raw_data['Page URL']) or  $raw_data['Page URL']=='') {

            $raw_data['Page URL']="info.php?page=".$raw_data['Page Code'];
        }




        $data=$this->base_data();
        foreach($raw_data as $key=>$value) {
            if (array_key_exists($key,$data))
                $data[$key]=_trim($value);


        }



        $keys='(';
        $values='values(';
        foreach($data as $key=>$value) {
            $keys.="`$key`,";
            if (preg_match('/Page Title|Page Description|Javascript|CSS|Page Keywords/i',$key))
                $values.="'".addslashes($value)."',";
            else
                $values.=prepare_mysql($value).",";
        }
        $keys=preg_replace('/,$/',')',$keys);
        $values=preg_replace('/,$/',')',$values);
        $sql=sprintf("insert into `Page Dimension` %s %s",$keys,$values);


        if (mysql_query($sql)) {
            $this->id=mysql_insert_id();
            $this->get_data('id',$this->id);

            $this->update_valid_url();
            $this->update_working_url();







            if ($this->data['Page Type']=='Internal') {
                $this->create_internal($raw_data);
            }
            elseif($this->data['Page Type']=='Store') {
                $this->create_store_page($raw_data);
            }



        } else {
            $this->error=true;
            $this->msg='Can not insert Page Dimension';
            exit("$sql\n");
        }


    }




    function create_store_page($raw_data) {
//print_r($raw_data);

        $data=$this->store_base_data();
        foreach($raw_data as $key=>$value) {
            if (array_key_exists($key,$data)) {
                $data[$key]=$value;
                if (is_string($value)) {
                    $data[$key]=_trim($value);
                }
                elseif(is_array($value))
                $data[$key]=serialize($value);
            }
        }





        $data['Page Key']=$this->id;

        if (!is_array($data['Page Store Showcases'])) {
            $data['Page Store Showcases']=array();
        }

        if (array_key_exists('Presentation',$data['Page Store Showcases'])) {
            $data['Presentation Showcase']='Yes';
        }
        if (array_key_exists('Offers',$data['Page Store Showcases'])) {
            $data['Offers Showcase']='Yes';
        }
        if (array_key_exists('New',$data['Page Store Showcases'])) {
            $data['New Showcase']='Yes';
        }
        $data['Page Store Showcases']=serialize($data['Page Store Showcases']);
// print "-------\n";

        if (!is_array($data['Page Store Product Layouts'])) {
            $data['Page Store Product Layouts']=array();
        }

        if (array_key_exists('List',$data['Page Store Product Layouts'])) {
            $data['List Layout']='Yes';
        }
        if (array_key_exists('Slideshow',$data['Page Store Product Layouts'])) {
            $data['Product Slideshow Layout']='Yes';
        }
        if (array_key_exists('Thumbnails',$data['Page Store Product Layouts'])) {
            $data['Product Thumbnails Layout']='Yes';
        }
        if (array_key_exists('Manual',$data['Page Store Product Layouts'])) {
            $data['Product Manual Layout']='Yes';
        }

//print_r($data);


        $data['Page Store Product Layouts']=serialize($data['Page Store Product Layouts']);

        $keys='(';
        $values='values(';
        foreach($data as $key=>$value) {
            $keys.="`$key`,";
            if (preg_match('/Subtitle|Title|Resume|Presentation|Slogan|Manual Layout Data|Page Store Showcases|Page Store Showcases/i',$key))
                $values.="'".addslashes($value)."',";
            else
                $values.=prepare_mysql($value).",";
        }
        $keys=preg_replace('/,$/',')',$keys);
        $values=preg_replace('/,$/',')',$values);
        $sql=sprintf("insert into `Page Store Dimension` %s %s",$keys,$values);
//print "$sql\n";
        if (mysql_query($sql)) {

            $this->get_data('id',$this->id);
            $this->new=true;

        } else {
            $this->error=true;
            $this->msg='Can not insert Page Store Dimension';
            print "$sql\n";
            exit;
        }

    }



    function update_working_url() {
        $old_value=$this->data['Page Working URL'];
        $this->data['Page Working URL']=$this->get_url_state($this->data['Page URL']);
        if ($old_value!=$this->data['Page Working URL']) {
            $sql=sprintf("update `Page Diemension` set `Page Working URL`=%s where `Page Key`=%d"
                         ,prepare_mysql($this->data['Page Working URL'])
                         ,$this->id
                        );
            mysql_query($sql);
        }

    }

    function update_valid_url() {
        $old_value=$this->data['Page Valid URL'];
        $this->data['Page Valid URL']=($this->is_valid_url($this->data['Page URL'])?'Yes':'No');
        if ($old_value!=$this->data['Page Valid URL']) {
            $sql=sprintf("update `Page Diemension` set `Page Valid URL`=%s where `Page Key`=%d"
                         ,prepare_mysql($this->data['Page Valid URL'])
                         ,$this->id
                        );
            mysql_query($sql);
        }

    }



    function get($key) {



        switch ($key) {
        case('link'):
            return $this->display();
            break;
        default:
            if (isset($this->data[$key]))
                return $this->data[$key];
        }
        return false;
    }






    function display($tipo='link') {

        switch ($tipo) {
        case('html'):
        case('xhtml'):
        case('link'):
        default:
            return '<a href="'.$this->data['Page URL'].'">'.$this->data['Page Title'].'</a>';

        }


    }


    function get_url_state($url) {
        $state='Unknown';

        return $state;

    }

    function is_valid_url($url) {
        if (preg_match("/^(http(s?):\\/\\/|ftp:\\/\\/{1})((\w+\.)+)\w{2,}(\/?)$/i", $url))
            return true;
        else
            return false;

    }

    /*
      Function: base_data
      Initialize data  array with the default field values
     */
    function internal_base_data() {
        $data=array();
        $result = mysql_query("SHOW COLUMNS FROM `Page Internal Dimension`");
        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit;
        }
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_assoc($result)) {
                if (!in_array($row['Field'],$this->ignore_fields))
                    $data[$row['Field']]=$row['Default'];
            }
        }
        return $data;
    }
    /*
        Function: base_data
        Initialize data  array with the default field values
       */
    function store_base_data() {
        $data=array();
        $result = mysql_query("SHOW COLUMNS FROM `Page Store Dimension`");
        if (!$result) {
            echo 'Could not run query: ' . mysql_error();
            exit;
        }
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_assoc($result)) {
                if (!in_array($row['Field'],$this->ignore_fields))
                    $data[$row['Field']]=$row['Default'];
            }
        }
        return $data;
    }

    function update_thumbnail_key($image_key) {

        $old_value=$this->data['Page Thumbnail Image Key'];
        if ($old_value!=$image_key) {
            $this->updated;
            $this->data['Page Thumbnail Image Key']=$image_key;

            $sql=sprintf("update `Page Dimension` set `Page Thumbnail Image Key`=%d ,`Page Snapshot Last Update`=NOW() where `Page Key`=%d "
                         ,$this->data['Page Thumbnail Image Key']
                         ,$this->id
                        );
            mysql_query($sql);

            $sql=sprintf("delete from  `Image Bridge` where `Subject Type`='Website' and `Subject Key`=%d "
                         ,$this->id

                        );
            mysql_query($sql);

            if ($this->data['Page Thumbnail Image Key']) {
                $sql=sprintf("insert into `Image Bridge` (`Subject Type`,`Subject Key`,`Image Key`) values('Website',%d,%d)"
                             ,$this->id
                             ,$image_key
                            );
                print $sql;
                mysql_query($sql);
            }

        }

    }


    function update_show_layout($layout,$value) {
        switch ($layout) {
        case 'thumbnails':
            $field="Product Thumbnails Layout";
            break;
        case 'list':
        case 'lists':
            $field="List Layout";
            break;
        case 'slideshow':
            $field="Product Slideshow Layout";
            break;
        case 'manual':
            $field="Product Manual Layout";
            break;
        default:
            $this->error=true;
            $this->msg='Invalid field';
            return;
            break;
        }
        $value=($value=='true'?'Yes':'No');

        $sql=sprintf("update `Page Store Dimension` set `%s`=%s where `Page Key`=%d"
                     ,$field
                     ,prepare_mysql($value)
                     ,$this->id
                    );
        mysql_query($sql);
        if (mysql_affected_rows()) {
            $this->updated=true;
            $this->new_value=$value;

        } else {
            $this->msg=_('Nothing to change');

        }



    }


    function update_presentation_template_data($value,$options) {




        $myFile = "sites/templates/splinters/presentation/".$this->id.'.tpl';
        $fh = fopen($myFile, 'w');
        fwrite($fh,$value );
        fclose($fh);
        $this->update_field('Product Presentation Template Data',$value,$options);



    }


    function update_field_switcher($field,$value,$options='') {


        switch ($field) {
        case('Page Store See Also Type'):
            $this->update_field('Page Store See Also Type',$value,$options);
            if($value=='Auto'){
                $this->update_see_also();
            }
            break;
        case('code'):
        case('page_code'):
            $this->update_field('Page Code',$value,$options);
            break;
        case('url'):
            $this->update_field('Page URL',$value,$options);
            break;
         case('page_title'):   
        case('title'):
            $this->update_field('Page Title',$value,$options);
            break;

        case('link_title'):
            $this->update_field('Page Short Title',$value,$options);
            break;
        case('keywords'):
         case('page_keywords'):
            $this->update_field('Page Keywords',$value,$options);
            break;
        case('store_title'):
            $this->update_field('Page Store Title',$value,$options);
            break;
        case('subtitle'):
            $this->update_field('Page Store Subtitle',$value,$options);
            break;
        case('slogan'):
            $this->update_field('Page Store Slogan',$value,$options);
            break;
        case('resume'):
            $this->update_field('Page Store Resume',$value,$options);
            break;
        case('presentation_template_data'):
            $this->update_presentation_template_data($value,$options);
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



    function update_field($field,$value,$options='') {




        if (is_array($value))
            return;
        $value=_trim($value);

        //print "** Update Field $field $value\n";

        $old_value=_('Unknown');

        $key_field=$this->table_name." Key";

        $table_name=$this->table_name;

        if ($this->type=='Store') {
            $extra_data=$this->store_base_data();
            if (array_key_exists($field,$extra_data))
                $table_name='Page Store';
        }


        $sql="select `".$field."` as value from  `".$table_name." Dimension`  where `$key_field`=".$this->id;
        //print "$sql\n";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
            $old_value=$row['value'];
        }





        $sql="update `".$table_name." Dimension` set `".$field."`=".prepare_mysql($value)." where `$key_field`=".$this->id;
        // print $sql;


        mysql_query($sql);
        $affected=mysql_affected_rows();
        if ($affected==-1) {
            $this->msg.=' '._('Record can not be updated')."\n";
            $this->error_updated=true;
            $this->error=true;

            return;
        }
        elseif($affected==0) {
            //$this->msg.=' '._('Same value as the old record');

        } else {
            $this->data[$field]=$value;
            $this->msg.=" $field "._('Record updated').", \n";
            $this->msg_updated.=" $field "._('Record updated').", \n";
            $this->updated=true;
            $this->new_value=$value;

            $save_history=true;
            if (preg_match('/no( |\_)history|nohistory/i',$options))
                $save_history=false;
            if (
                !$this->new
                and $save_history
            ) {
                $history_data=array(
                                  'indirect_object'=>$field
                                                    ,'old_value'=>$old_value
                                                                 ,'new_value'=>$value

                              );



                $this->add_history($history_data);

            }

        }

    }

    function get_data_for_smarty($data) {


        $page_section=new PageStoreSection('code',$this->data['Page Store Section'],$this->data['Page Site Key']);
        $data=$page_section->get_data_for_smarty($data);

        $header_style=$data['header_style'];
        if ($this->data['Page Store Header Data'] and array_key_exists('style',$this->data['Page Store Header Data']))
            foreach($this->data['Page Store Header Data']['style'] as $key=>$value) {
            $header_style.="$key:$value;";
        }
        $data['header_style']=$header_style;

        $footer_style=$data['footer_style'];
        if ($this->data['Page Store Footer Data'] and array_key_exists('style',$this->data['Page Store Footer Data']))
            foreach($this->data['Page Store Footer Data']['style'] as $key=>$value) {
            $footer_style.="$key:$value;";
        }
        $data['footer_style']=$footer_style;

        $content_style=$data['content_style'];
        $showcases=array();
        if ($this->data['Page Store Content Data'] ) {

            if (array_key_exists('style',$this->data['Page Store Content Data'])) {
                foreach($this->data['Page Store Content Data']['style'] as $key=>$value) {
                    $content_style.="$key:$value;";
                }
            }

            if (array_key_exists('Showcases',$this->data['Page Store Content Data'])) {
                foreach($this->data['Page Store Content Data']['Showcases'] as $showcase_key=>$showcase) {
                    $style='';
                    if (array_key_exists('style',$showcase)) {
                        foreach($this->data['Page Store Content Data']['Showcases'][$showcase_key]['style'] as $key=>$value) {
                            $style.="$key:$value;";
                        }
                    }
                    $showcase['style']=$style;
                    $showcases[]=$showcase;

                }
            }

        }

        $data['content_style']=$content_style;
        $data['showcases']=$showcases;
        $data['resume']=$this->data['Page Store Resume'];

        $data['slogan']=$this->data['Page Store Slogan'];
        $data['subtitle']=$this->data['Page Store Subtitle'];

        $data['title']=$this->data['Page Title'];
        return $data;
    }




    function get_found_in() {

        $found_in=array();
        $sql=sprintf("select `Page Store Found In Key` from  `Page Store Found In Bridge` where `Page Store Key`=%d",
                     $this->id);

        $res=mysql_query($sql);

        while ($row=mysql_fetch_assoc($res)) {
            $found_in_page=new Page($row['Page Store Found In Key']);
            if ($found_in_page->id) {
                $found_in[]=array(
                                'found_in_label'=>$found_in_page->data['Page Short Title'],
                                'found_in_url'=>$found_in_page->data['Page URL'],
                                'found_in_key'=>$found_in_page->id,
                                'found_in_code'=>$found_in_page->data['Page Code']
                            );
            }

        }
        return $found_in;

    }



    function get_see_also() {

        $see_also=array();
        $sql=sprintf("select `Page Store See Also Key`,`Correlation Type`,`Correlation Value` from  `Page Store See Also Bridge` where `Page Store Key`=%d order by `Correlation Value` desc ",
                     $this->id);

        $res=mysql_query($sql);

        while ($row=mysql_fetch_assoc($res)) {
            $see_also_page=new Page($row['Page Store See Also Key']);
            if ($see_also_page->id) {


                switch ($row['Correlation Type']) {
                case 'Manual':
                    $formated_correlation_type=_('Manual');
                    $formated_correlation_value='';
                    break;
                case 'Sales':
                    $formated_correlation_type=_('Sales');
                    $formated_correlation_value=percentage($row['Correlation Value'],1);
                    break;
                case 'Semantic':
                    $formated_correlation_type=_('Semantic');
                    $formated_correlation_value=number($row['Correlation Value']);
                    break;
                default:
                    $formated_correlation_type=$row['Correlation Type'];
                    break;
                }


                $see_also[]=array(
                                'see_also_label'=>$see_also_page->data['Page Short Title'],
                                'see_also_url'=>$see_also_page->data['Page URL'],
                                'see_also_key'=>$see_also_page->id,
                                'see_also_code'=>$see_also_page->data['Page Code'],
                                'see_also_correlation_type'=>$row['Correlation Type'],
                                'see_also_correlation_formated'=>$formated_correlation_type,
                                'see_also_correlation_value'=>$row['Correlation Value'],
                                'see_also_correlation_formated_value'=>$formated_correlation_value,
                            );
            }

        }
        return $see_also;

    }



    function delete() {
        $this->deleted=false;
        $sql=sprintf("delete from `Page Dimension` where `Page Key`=%d",$this->id);
        // print "$sql\n";
        mysql_query($sql);
        $sql=sprintf("delete from `Page Store Dimension` where `Page Key`=%d",$this->id);
        mysql_query($sql);

        $this->deleted=true;

    }


    function update_see_also() {




        if ($this->data['Page Type']!='Store' or $this->data['Page Store See Also Type']=='Manual')
            return;

        $max_links=3;
        $min_sales_correlation_samples=20;
        $correlation_upper_limit=1/($min_sales_correlation_samples);
        $see_also=array();
        $number_links=0;
        switch ($this->data['Page Store Section']) {
        case 'Department Catalogue':
            break;
        case 'Family Catalogue':

            $family=new Family($this->data['Page Parent Key']);
            /*
            $store_key=$family->data['Product Family Store Key'];

            $sql=sprintf("select avg(`Correlation`) avg_correlation,avg(`Samples`) avg_samples, std(`Correlation`) std_correlation,std(`Samples`) std_samples  from `Product Family Sales Correlation` left join `Product Family Dimension` F on (`Product Family Key`=`Family A Key`) where `Product Family Store Key` and `Samples`>=20",
                         $this->data['Page Parent Key'],
                         $store_key
                        );
            $res=mysql_query($sql);
            if ($row=mysql_fetch_assoc($res)) {

                $correlation_upper_limit=$row['avg_correlation']/3;


            }
            */
            //print "Family: ".$family->data['Product Family Code']."\n";

            $sql=sprintf("select * from `Product Family Sales Correlation` where `Family A Key`=%d order by `Correlation` desc limit 200",
                         $this->data['Page Parent Key']);
            $res=mysql_query($sql);
            // print "$sql\n";
            while ($row=mysql_fetch_assoc($res)) {
                //    print_r($row);

                if ($row['Samples']>$min_sales_correlation_samples and $row['Correlation']>$correlation_upper_limit) {
                    $family=new Family($row['Family B Key']);
                    if ($family->data['Product Family Record Type']=='Normal' or $family->data['Product Family Record Type']=='Discontinuing') {

                        $page_keys=$family->get_pages_keys();

                        $see_also_page_key=array_pop($page_keys);
                        if ($see_also_page_key) {
                            $see_also[$see_also_page_key]=array('type'=>'Sales','value'=>$row['Correlation']);
                            $number_links=count($see_also);
                            if ($number_links>=$max_links)
                                break;
                        }
                    }
                }


            }






            if ($number_links<$max_links) {
                $sql=sprintf("select * from `Product Family Semantic Correlation` where `Family A Key`=%d order by `Weight` limit 100",
                             $this->data['Page Parent Key'],
                             $max_links
                            );
                $res=mysql_query($sql);
                // print "$sql\n";
                while ($row=mysql_fetch_assoc($res)) {
                    if (!array_key_exists($row['Family B Key'], $see_also)) {
                        $family=new Family($row['Family B Key']);
                        if ($family->data['Product Family Record Type']=='Normal' or $family->data['Product Family Record Type']=='Discontinuing') {

                            $page_keys=$family->get_pages_keys();
                            $see_also_page_key=array_pop($page_keys);
                            if ($see_also_page_key) {
                                $see_also[$see_also_page_key]=array('type'=>'Semantic','value'=>$row['Weight']);
                                $number_links=count($see_also);
                                if ($number_links>=$max_links)
                                    break;
                            }
                        }
                    }
                }


            }
            // if ($number_links==0) {
            // print_r($see_also);
            // exit("error\n");
            // }


            //    print_r($see_also);

            break;
        default:

            break;
        }


        $sql=sprintf("delete from `Page Store See Also Bridge`where `Page Store Key`=%d ",
                     $this->id);
        mysql_query($sql);

        foreach($see_also  as $see_also_page_key=>$see_also_data) {

            $sql=sprintf("insert  into `Page Store See Also Bridge` values (%d,%d,%s,%f) ",
                         $this->id,
                         $see_also_page_key,
                         prepare_mysql($see_also_data['type']),
                         $see_also_data['value']
                        );
            mysql_query($sql);
            //    print "$sql\n";
        }

    }


    function get_formated_store_section() {
        if ($this->data['Page Type']!='Store' or $this->data['Page Store See Also Type']=='Manual')
            return;


        switch ($this->data['Page Store Section']) {
        case 'Front Page Store':
            $formated_store_section=_('Front Page Store');
            break;
        case 'Search':
            $formated_store_section=_('Search');
            break;
        case 'Product Description':
            $formated_store_section=_('Product Description');
            break;
        case 'Information':
            $formated_store_section=_('Information');
            break;
        case 'Category Catalogue':
            $formated_store_section=_('Category Catalogue');
            break;
        case 'Family Catalogue':
            $formated_store_section=_('Family Catalogue').' <a href="family.php?id='.$this->data['Page Parent Key'].'">'.$this->data['Page Parent Code'].'</a>';
            break;
              case 'Department Catalogue':
            $formated_store_section=_('Department Catalogue').' <a href="department.php?id='.$this->data['Page Parent Key'].'">'.$this->data['Page Parent Code'].'</a>';
            break;
              case 'Store Catalogue':
            $formated_store_section=_('Store Catalogue').' <a href="store.php?id='.$this->data['Page Parent Key'].'">'.$this->data['Page Parent Code'].'</a>';
            break;
              case 'Registration':
            $formated_store_section=_('Registration');
            break;
              case 'Client Section':
            $formated_store_section=_('Client Section');
            break;
                 case 'Check Out':
            $formated_store_section=_('Check Out');
            break;
        default:
            $formated_store_section=$this->data['Page Store Section'];
            break;
        }

return $formated_store_section;
    }

}
?>