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
    var $logged=false;
    var $snapshots_taken=0;

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

        $old_value=$this->data['Page Snapshot Image Key'];
        if ($old_value!=$image_key) {
            $this->updated;
            $this->data['Page Snapshot Image Key']=$image_key;

            $sql=sprintf("update `Page Dimension` set `Page Snapshot Image Key`=%d ,`Page Snapshot Last Update`=NOW() where `Page Key`=%d "
                         ,$this->data['Page Snapshot Image Key']
                         ,$this->id
                        );
            mysql_query($sql);

            $sql=sprintf("delete from  `Image Bridge` where `Subject Type`='Website' and `Subject Key`=%d "
                         ,$this->id

                        );
            mysql_query($sql);

            if ($this->data['Page Snapshot Image Key']) {
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

    function get_header_template() {
        $template='';
        $sql=sprintf("select `Template` from `Page Header Dimension` where `Page Header Key`=%d",$this->data['Page Header Key']);
        $result = mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $template= $row['Template'];
        }
        return $template;
    }

    function get_footer_template() {
        $template='';
        $sql=sprintf("select `Template` from `Page Footer Dimension` where `Page Footer Key`=%d",$this->data['Page Footer Key']);

        $result = mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $template= $row['Template'];
        }
        return $template;
    }


    function get_css() {
        $css='';


        $sql=sprintf("select `CSS` from `Page Header Dimension` where `Page Header Key`=%d",$this->data['Page Header Key']);
        $result = mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $css.=$row['CSS'];
        }

        $css.=$this->data['Page Store CSS'];
        return  $css;
    }
    function get_javascript() {
        return $this->data['Page Store Javascript'];
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
            if ($value=='Auto') {
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
        case('Page Store Resume'):
        case('description'):
        case('page_html_head_resume'):
            $this->update_field('Page Store Resume',$value,$options);
            break;
        case('Page Keywords'):
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
        case('Page Store Source'):
        case('Page Store CSS'):
        case('Number See Also Links'):
        case('Page Footer Height'):
        case('Page Header Height'):
        case('Page Content Height'):


            $this->update_field($field,$value,$options);
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
                                'link'=>'<a href="'.$found_in_page->data['Page URL'].'">'.$found_in_page->data['Page Short Title'].'</a>',
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
                                'link'=>'<a href="'.$see_also_page->data['Page URL'].'">'.$see_also_page->data['Page Short Title'].'</a>',
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

        $max_links=$this->data['Number See Also Links'];
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
        $count=0;
        foreach($see_also  as $see_also_page_key=>$see_also_data) {

            if ($count>=$max_links)
                break;

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

    function display_button($tag) {
        $html='';
        include_once('class.Product.php');
        $product=new Product('code_store',$tag,$this->data['Page Store Key']);

        if ($product->id) {

            if ($this->logged) {

                switch ($this->site->data['Site Checkout Method']) {
                case 'Mals':

                    $html.=$this->display_button_emals_commerce($product);
                    break;
                case 'Inikoo':
                    $html.=$this->display_button_inikoo($product);

                    break;
                default:
                    break;
                }
            } else {
                $html=$this->display_button_logged_out($product);
            }
        }
        return $html;
    }

    function display_button_emals_commerce($product) {


        if ($product->data['Product Web State']=='Out of Stock') {
            $message='<br/><span style="color:red;font-weight:800">'._('Out of Stock').'</span>';
        }
        elseif($product->data['Product Web State']=='Offline') {
            $message='<br/><span style="color:red;font-weight:800">'._('Not for Sale').'</span>';
        }
        elseif($product->data['Product Web State']=='Discontinued') {
            $message='<br/><span style="color:red;font-weight:800">'._('Discontinued').'</span>';
        }
        else {
            $message=sprintf("<br/><div class='order_but' style='text-align:left'>
                             <form action='%s' method='post'>
                             <input type='hidden' name='userid' value='%s'>
                             <input type='hidden' name='product' value='%s %sx %s'>
                             <input type='hidden' name='return' value='%s'>
                             <input type='hidden' name='price' value='%s'>
                             <input type='text' size='2' class='qty' name='qty' value=''>
                             <input type='Submit' value='%s'></form></div>",
                             $this->site->get_mals_data('url'),
                             $this->site->get_mals_data('id'),
                             $product->data['Product Code'],
                             $product->data['Product Units Per Case'],
                             $product->data['Product Name'],
                             $this->data['Page URL'],
                             $product->data['Product Price'],
                             _('Order')


                            );
        }

        $data=array(
                  'Product Price'=>$product->data['Product Price'],
                  'Product Units Per Case'=>$product->data['Product Units Per Case'],
                  'Product Currency'=>$product->get('Product Currency'),
                  'Product Unit Type'=>$product->data['Product Unit Type'],


                  'locale'=>$this->site->data['Site Locale']);

        $price= '<span class="price">'.formated_price($data).'</span><br>';

        $data=array(
                  'Product Price'=>$product->data['Product RRP'],
                  'Product Units Per Case'=>$product->data['Product Units Per Case'],
                  'Product Currency'=>$product->get('Product Currency'),
                  'Product Unit Type'=>$product->data['Product Unit Type'],
                  'Label'=>_('RRP').":",

                  'locale'=>$this->site->data['Site Locale']);

        $rrp= '<span class="rrp">'.formated_price($data).'</span><br>';




        $form=sprintf('<div  class="ind_form">
                      <span class="code">%s</span><br/>
                      <span class="name">%sx %s</span><br>
                      %s
                      %s
                      %s
                      </div>',
                      $product->data['Product Code'],
                      $product->data['Product Units Per Case'],
                      $product->data['Product Name'],
                      $price,
                      $rrp,
                      $message
                     );




        return $form;


    }
    function display_button_inikoo($product) {


    }
    function display_button_logged_out($product) {

        if ($product->data['Product Web State']=='Out of Stock') {
            $message='<br/><span style="color:red;font-weight:800">'._('Out of Stock').'</span>';
        }
        elseif($product->data['Product Web State']=='Offline') {
            $message='<br/><span style="color:red;font-weight:800">'._('Not for Sale').'</span>';
        }
        elseif($product->data['Product Web State']=='Discontinued') {
            $message='<br/><span style="color:red;font-weight:800">'._('Discontinued').'</span>';
        }
        else {
            $message=sprintf('<br/><span style="color:green;font-style:italic;">In stock, please <a style="color:green;" href="#" onclick="show_login_dialog()">login</a> or <a style="color:green;" href="#" onclick="show_register_dialog()">register</a> to see wholesale prices</span>');
        }

        $form=sprintf('<div  class="ind_form">
                      <span class="code">%s</span><br/>
                      <span class="name">%sx %s</span>%s
                      </div>',
                      $product->data['Product Code'],
                      $product->data['Product Units Per Case'],
                      $product->data['Product Name'],
                      $message
                     );


        return $form;
    }

    function display_list($list_code='default') {
        if (!$this->data['Page Type']=='Store' or !$this->data['Page Store Section']=='Family Catalogue' ) {
            return '';
        }




        $products=$this->get_products_from_list($list_code);
        $this->print_rrp=false;

        if (count($products)==0)
            return;



        if ($this->logged) {

            return $this->display_list_logged_in($products);
        } else {

            return $this->display_list_logged_out($products);

        }




    }

    function get_products_from_list($list_code) {

        $products=array();
        $sql=sprintf("select * from `Page Product List Dimension` where `Page Key`=%d and `Page Product Form Code`=%s",
                     $this->id,
                     prepare_mysql($list_code)
                    );
        $res=mysql_query($sql);
        if ($row=mysql_fetch_assoc($res)) {


            $family_key=$row['Page Product Form Parent Key'];


            if ($row['Page Product Form Type']=='FamilyList') {

                switch ($row['List Order']) {
                case 'Code':
                    $order_by='`Product Code File As`';
                    break;
                case 'Name':
                    $order_by='`Product Name`';
                    break;
                case 'Special Characteristic':
                    $order_by='`Product Special Characteristic`';
                    break;
                case 'Price':
                    $order_by='`Product Price`';
                    break;
                case 'RRP':
                    $order_by='`Product RRP`';
                    break;
                case 'Sales':
                    $order_by='`Product 1 Year Acc Quantity Ordered`';
                    break;
                case 'Date':
                    $order_by='`Product Valid From`';
                    break;
                default:
                    $order_by='`Product Code File As`';
                    break;
                }

                $limit=sprintf('limit %d',$row['List Max Items']);


                if ($row['Range']!='') {
                    $range=preg_split('/-/',$row['Range']);

                    $range_where=sprintf("and  $order_by>=%s  and $order_by<=%s ", prepare_mysql($range[0]), prepare_mysql($range[1]));
                } else {
                    $range_where='';
                }
                $sql=sprintf("select `Product Currency`,`Product Name`,`Product ID`,`Product Code`,`Product Price`,`Product RRP`,`Product Units Per Case`,`Product Unit Type`,`Product Web State`,`Product Special Characteristic` from `Product Dimension` where `Product Family Key`=%d and `Product Web State`!='Offline'  %s order by %s %s",
                             $family_key,
                             $range_where,
                             $order_by,
                             $limit);
//print $sql;
                $result=mysql_query($sql);
                while ($row2=mysql_fetch_array($result, MYSQL_ASSOC)) {

                    $products[]=$row2;
                }

            }


            switch ($row['List Product Description']) {
            case 'Units Name':
                foreach ($products as $key=>$product) {
                    $$products[$key]['description']=sprintf("%dx %s",$product['Product Units Per Case'],$product['Product Name']);
                }
                break;
            case 'Units Name RRP':




                foreach ($products as $key=>$product) {
                    $rrp=money_locale($product['Product RRP'],$this->site->data['Site Locale'],$product['Product Currency']);

                    $products[$key]['description']=sprintf("%dx %s <span class='rrp' >(%s: %s)</span>",
                                                           $product['Product Units Per Case'],
                                                           $product['Product Name'],
                                                           _('RRP'),
                                                           $rrp
                                                          );
                }
                break;
            case 'Units Special Characteristic':
                foreach ($products as $key=>$product) {
                    $products[$key]['description']=sprintf("%dx %s",$product['Product Units Per Case'],$product['Product Special Characteristic']);
                }
                break;
            case 'Units Special Characteristic RRP':




                foreach ($products as $key=>$product) {
                    $rrp=money_locale($product['Product RRP'],$this->site->data['Site Locale'],$product['Product Currency']);

                    $products[$key]['description']=sprintf("%dx %s <span class='rrp' >(%s: %s)</span>",
                                                           $product['Product Units Per Case'],
                                                           $product['Product Special Characteristic'],
                                                           _('RRP'),
                                                           $rrp
                                                          );
                }
                break;

            default:
                foreach ($products as $key=>$product) {
                    $products[$key]['description']=sprintf("%dx %s",$product['Product Units Per Case'],$product['Product Name']);
                }
                break;
            }

        }





        return $products;
    }

    function get_list_header($products) {

        $html='';

        switch ($this->data['Page Store Section']) {
        case 'Family Catalogue':
            $family=new Family($this->data['Page Parent Key']);
            $html=sprintf('<tr class="list_info"><td colspan=5>%s %s</td></tr>',$family->data['Product Family Code'],$family->data['Product Family Name']);

            break;
        default:

            break;
        }

        $html.=sprintf('<tr class="list_info price"><td style="padding-top:0;padding-bottom:0;text-align:left" colspan="6">%s </td></tr></tr>',$this->get_list_price_header_auto($products));
        $html.=sprintf('<tr class="list_info rrp"><td style="padding-top:0;padding-bottom:0;" colspan="6">%s</td></tr></tr>',$this->get_list_rrp_header_auto($products));


        return $html;

    }

    function display_list_logged_out($products) {



        $show_unit=true;
        //if (isset($options['unit'])) {
        //    $show_unit=$options['unit'];
        // }
        $print_header=true;
        $print_rrp=false;
        $print_register=true;

        $number_records=count($products);
        $out_of_stock=_('Out of Stock');
        $discontinued=_('Discontinued');
        $register=_('Please <span onclick="show_login_dialog()">login</span> or <span onclick="show_register_dialog()">register</span> to see wholesale prices');



        $form=sprintf('<table class="product_list" >' );

        if ($print_header) {

            $rrp_label='';

            if ($print_rrp) {

                if ($number_records==1) {

                } elseif ($number_records>2) {

                    $sql=sprintf("select min(`Product RRP`/`Product Units Per Case`) min, max(`Product RRP`/`Product Units Per Case`) as max ,avg(`Product RRP`/`Product Units Per Case`)  as avg from `Product Dimension` where `Product Family Key`=%d and `Product Web State` in ('For Sale','Out of Stock') ", $family->id);
                    $res=mysql_query($sql);
                    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
                        $rrp=$row['min'];


                        $rrp= $this->get_formated_rrp(array(
                                                          'Product RRP'=>$rrp,
                                                          'Product Units Per Case'=>1,
                                                          'Product Unit Type'=>''),array('prefix'=>false, 'show_unit'=>$show_unit));

                        if ($row['rrp_avg']<=0) {
                            $rrp_label='';
                            $print_rrp=false;
                        }
                        elseif ($row['avg']==$row['min'])
                        $rrp_label='<br/>RRP: '.$rrp;
                        else
                            $rrp_label='<br/>RRP from '.$rrp;



                    } else {
                        return;
                    }

                }

            }


            $form.='<tr class="list_info" ><td colspan="4"><p>FAMILY NAME'.$rrp_label.'</p></td><td>';
            if ($print_register and $number_records>10)
                $form.=sprintf('<tr class="last register"><td colspan="4">%s</td></tr>',$register);


        }
        $counter=0;

        foreach($products as $product) {



            if ($print_rrp) {

                $rrp= $this->get_formated_rrp(array(
                                                  'Product RRP'=>$product['Product RRP'],
                                                  'Product Units Per Case'=>$product['Product Units Per Case'],
                                                  'Product Unit Type'=>$product['Product Unit Type']), array('show_unit'=>$show_unit));

            } else {
                $rrp='';
            }
            if ($product['Product Web State']=='Out of Stock') {
                $class_state='out_of_stock';
                $state='('.$out_of_stock.')';

            }
            elseif ($product['Product Web State']=='Discontinued') {
                $class_state='discontinued';
                $state='('.$discontinued.')';

            }
            else {

                $class_state='';
                $state='';


            }


            if ($counter==0)
                $tr_class='class="top"';
            else
                $tr_class='';
            $form.=sprintf('<tr %s ><td class="code">%s</td><td class="description">%s   <span class="%s">%s</span></td><td class="rrp">%s</td></tr>',
                           $tr_class,
                           $product['Product Code'],
                           $product['Product Units Per Case'].'x '.$product['Product Special Characteristic'],
                           $class_state,
                           $state,
                           $rrp

                          );


            $counter++;
        }

        if ($print_register)
            $form.=sprintf('<tr class="last register"><td colspan="4">%s</td></tr>',$register);
        $form.=sprintf('</table>');
        return $form;
    }

    function display_list_logged_in($products) {

        $print_rrp=true;
        $number_records=count($products);
        $out_of_stock=_('Out of Stock');
        $discontinued=_('Discontinued');


        $form=sprintf('<table border=0  class="product_list form" >' );
        $rrp_label='';
        $price_label='';

//        $form.='<tr class="list_info" ><td colspan="4"><p>'.$price_label.$rrp_label.'</p></td></tr>';

        $form.=$this->get_list_header($products);

        switch ($this->site->data['Site Checkout Method']) {
        case 'Mals':

            $form.=$this->get_list_emals_commerce($products);
            break;
        case 'Inikoo':
            $form.=$this->get_list_inikoo($products);

            break;
        default:
            break;
        }


        $form.='</table>';
        return $form;
    }


    function get_list_inikoo($products) {
        $form='';
        $counter=0;

        $order_key=0;
        if ($this->user->data['User Type']=='Customer') {

            $sql=sprintf("select `Order Key` from `Order Dimension` where `Order Customer Key`=%d and `Order Current Dispatch State`='In Process' order by `Order Public ID` DESC", $this->user->get('User Parent Key'));
            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result))


                $order_key=$row['Order Key'];
        }


        foreach($products as $product) {

            if ($this->print_rrp) {

                $rrp= $this->get_formated_rrp(array(
                                                  'Product RRP'=>$product['Product RRP'],
                                                  'Product Units Per Case'=>$product['Product Units Per Case'],
                                                  'Product Unit Type'=>$product['Product Unit Type']), array('show_unit'=>$show_unit));

            } else {
                $rrp='';
            }




            $price= $this->get_formated_price(array(
                                                  'Product Price'=>$product['Product Price'],
                                                  'Product Units Per Case'=>1,
                                                  'Product Unit Type'=>'',
                                                  'Label'=>(''),
                                                  'price per unit text'=>''
                                              ));
            if ($counter==0)
                $tr_class='class="top"';
            else
                $tr_class='';

            $old_qty=0;
            if ($order_key) {
                $sql=sprintf("select `Order Quantity` from `Order Transaction Fact` where `Order Key`=%d and `Product ID`=%d",
                             $order_key,
                             $product['Product ID']);
                $result1=mysql_query($sql);
                if ($product1=mysql_fetch_array($result1))
                    $old_qty=$product1['Order Quantity'];
            }






            if ($product['Product Web State']=='Out of Stock') {
                //        $class_state='out_of_stock';

                //      $state=' <span class="out_of_stock">('._('Out of Stock').')</span>';

                $order_button=sprintf('<td></td><td colspan=2 style="padding:0px"><div style="background:#ffdada;color:red;display:table-cell; vertical-align:middle;font-size:90%%;text-align:center;;border:1px solid #ccc;height:18px;width:58px;">%s</div></td>',_('Sold Out'));


            }
            elseif ($product['Product Web State']=='Discontinued') {
                //    $class_state='discontinued';
                //  $state=' <span class="discontinued">('._('Discontinued').')</span>';
                $order_button=sprintf('<td colspan=3>%s</td>',_('Sold Out'));
            }
            else {
                // $class_state='';
                // $state='';

                $order_button=sprintf('
                                      <td ><span id="loading%d"></span></td>
                                      <td style="padding:0" class="input"><input style="height:20px" id="qty%s"  type="text" value="%s"  ></td>
                                      <td style="padding:0"><button style="border:1px solid #ccc;border-left:none;height:22px"><img style="pointer:cursor;position:relative;bottom:2px"src="art/icons/basket_add.png" onClick="order_single_product(%d)"/></button></td>',
                                      $product['Product ID'],
                                      $product['Product ID'],
                                      ($old_qty>0?$old_qty:''),



                                      $product['Product ID']


                                     );


            }




            $form.=sprintf('<tr %s >
                           <td class="code">%s</td>
                           %s
                           <input type="hidden" id="order_id%d" value="%d">
                           <input type="hidden" id="pid%d" value="%d">
                           <input type="hidden" id="old_qty%d" value="%d">

                           <td class="price">%s</td>



                           <td class="description">%s <span class="rrp">%s</span></td>


                           </tr>'."\n",
                           $tr_class,
                           $product['Product Code'],
                           $order_button,
                           $product['Product ID'],$order_key,
                           $product['Product ID'],$product['Product ID'],
                           $product['Product ID'],$old_qty,

                           $price,




                           $product['Product Units Per Case'].'x '.$product['Product Special Characteristic'], $rrp

                          );





            $counter++;
        }


        return $form;



    }

    function get_list_emals_commerce($products) {


        $form=sprintf('
                      <form action="%s" method="post">
                      <input type="hidden" name="userid" value="%s">
                      <input type="hidden" name="nnocart"> '
                      ,$this->site->get_mals_data('url_multi')
                      ,$this->site->get_mals_data('id')

                     );
        $counter=1;
        foreach($products as $product) {


            if ($this->print_rrp) {

                $rrp= $this->get_formated_rrp(array(
                                                  'Product RRP'=>$product['Product RRP'],
                                                  'Product Units Per Case'=>$product['Product Units Per Case'],
                                                  'Product Unit Type'=>$product['Product Unit Type']), array('show_unit'=>$show_unit));

            } else {
                $rrp='';
            }






            $price= $this->get_formated_price(array(
                                                  'Product Price'=>$product['Product Price'],
                                                  'Product Units Per Case'=>1,
                                                  'Product Unit Type'=>'',
                                                  'Label'=>(''),
                                                  'price per unit text'=>''

                                              ));






            if ($product['Product Web State']=='Out of Stock') {
                $class_state='out_of_stock';

                $input=' <span class="out_of_stock" style="font-size:70%">'._('Out of Stock').'</span>';



            }
            elseif ($product['Product Web State']=='Discontinued') {
                $class_state='discontinued';
                $input=' <span class="discontinued">('._('Discontinued').')</span>';

            }
            else {

                $input=sprintf('<input name="qty%s"  id="qty%s"  type="text" value=""  >',
                               $counter,
                               $counter
                              );


            }



            if ($counter==1)
                $tr_class='class="top"';
            else
                $tr_class='';




            $form.=sprintf('<tr %s >
                           <input type="hidden" name="price%s" value="%.2f"  >
                           <input type="hidden" name="product%s"  value="%s %s" >
                           <td class="code">%s</td>
                           <td class="price">%s</td>
                           <td class="input">
                           %s
                           </td>
                           <td class="description">%s</td>
                           </tr>'."\n",
                           $tr_class,

                           $counter,$product['Product Price'],
                           $counter,$product['Product Code'],$product['description'],

                           $product['Product Code'],
                           $price,

                           $input,



                           $product['description']



                          );





            $counter++;
        }


        $form.=sprintf('<tr class="space"><td colspan="4">
                       <input type="hidden" name="return" value="%s">
                       <input class="button" name="Submit" type="submit"  value="Order">
                       <input class="button" name="Reset" type="reset"  id="Reset" value="Reset"></td></tr></form></table>
                       '
                       ,$this->data['Page URL']);
        return $form;
    }


    function get_list_price_header_auto($products) {
        $price_label='';
        $min_price=999999999999;
        $max_price=-99999999999;
        $number_products_with_price=0;
        foreach($products as $product) {

            if ($product['Product Price']) {
                $number_products_with_price++;
                if ($min_price>$product['Product Price'])
                    $min_price=$product['Product Price'];
                if ($max_price<$product['Product Price'])
                    $max_price=$product['Product Price'];
            }



        }


        if ($number_products_with_price) {




            $price= $this->get_formated_price(
                        array(
                            'Product Price'=>$min_price,
                            'Product Units Per Case'=>1,
                            'Product Unit Type'=>'',
                            'Label'=>($min_price==$max_price?_('Price'):_('Price from')).':'
                        )
                    );

            if ($min_price==$max_price) {
                $price_label='<span class="price">'.$price.'</span>';
            } else {
                $price_label='<span class="price">'.$price.'</span>';
            }

        }

        return $price_label;
    }

    function get_list_rrp_header_auto($products) {
        $rrp_label='';
        $min_rrp=999999999999;
        $max_rrp=-99999999999;
        $number_products_with_rrp=0;
        foreach($products as $product) {

            if ($product['Product RRP']) {
                $number_products_with_rrp++;
                if ($min_rrp>$product['Product RRP'])
                    $min_rrp=$product['Product RRP'];
                if ($max_rrp<$product['Product RRP'])
                    $max_rrp=$product['Product RRP'];
            }


        }

        if ($number_products_with_rrp) {
            $rrp= $this->get_formated_price(array(
                                                'Product Price'=>$min_rrp,
                                                'Product Units Per Case'=>1,
                                                'Product Unit Type'=>'',
                                                'Label'=>($min_rrp==$max_rrp?_('RRP'):_('RRP from')).':'
                                            )
                                           );

            if ($min_rrp==$max_rrp) {
                $rrp_label='<span class="rrp">'.$rrp.'</span>';
            } else {
                $rrp_label='<span class="rrp">'.$rrp.'</span>';
            }

        }

        return $rrp_label;
    }

    function get_formated_rrp($data,$options=false) {

        $data=array(
                  'Product RRP'=>$data['Product RRP'],
                  'Product Units Per Case'=>$data['Product Units Per Case'],
                  'Product Currency'=>$this->currency,
                  'Product Unit Type'=>$data['Product Unit Type'],
                  'Label'=>$data['Label'],
                  'locale'=>$this->site->data['Site Locale']);
        if (isset($data['price per unit text']))
            $_data['price per unit text']=$data['price per unit text'];

        return formated_rrp($data,$options);
    }

    function get_formated_price($data,$options=false) {

        $_data=array(
                   'Product Price'=>$data['Product Price'],
                   'Product Units Per Case'=>$data['Product Units Per Case'],
                   'Product Currency'=>$this->currency,
                   'Product Unit Type'=>$data['Product Unit Type'],
                   'Label'=>$data['Label'],
                   'locale'=>$this->site->data['Site Locale']

               );

        if (isset($data['price per unit text']))
            $_data['price per unit text']=$data['price per unit text'];

        return formated_price($_data,$options);
    }

    function display_title() {
        return $this->get('Page Store Title');
    }

    function display_top_bar() {

    }

    function display_label() {

        return $this->get('Page Parent Code');
    }


    function update_list_products() {
        $lists=$this->get_list_products_from_source();

        $valid_list_keys=array();
        foreach($lists as $list_key) {

            $sql=sprintf("select `Page Product Form Key` from `Page Product List Dimension` where `Page Key`=%d and `Page Product Form Code`=%s  ",
                         $this->id,
                         prepare_mysql($list_key)
                        );
            $res=mysql_query($sql);
            if ($row=mysql_fetch_assoc($res)) {
                $valid_list_keys[]=prepare_mysql($row['Page Product Form Key']);

            } else {
                if ($this->data['Page Store Section']=='Family Catalogue') {
                    $sql=sprintf("insert into `Page Product List Dimension` (`Page Key`,`Page Product Form Code`,`Page Product Form Type`,`Page Product Form Parent Key`) values  (%d,%s,%s,%d)",
                                 $this->id,
                                 prepare_mysql($list_key),
                                 prepare_mysql('FamilyList'),
                                 $this->data['Page Parent Key']

                                );
                    mysql_query($sql);

                    $valid_list_keys[]=prepare_mysql(mysql_insert_id());
                }
            }

            if (count($valid_list_keys)>0) {
                $sql=sprintf("delete from `Page Product List Dimension` where `Page Key`=%d and `Page Product Form Key` not in (%s) ",$this->id,join(',',$valid_list_keys));
                mysql_query($sql);
            } else {
                $sql=sprintf("delete from `Page Product List Dimension` where `Page Key`=%d",$this->id);
                mysql_query($sql);
            }
        }




    }

    function update_number_products() {

        $products_from_family=array();
        $sql=sprintf("select `Page Product Form Code`,`Page Product Form Key` from `Page Product List Dimension` where `Page Key`=%d  ",
                     $this->id

                    );
        $res=mysql_query($sql);
        $number_lists=0;
        while ($row=mysql_fetch_assoc($res)) {
            $products=$this->get_products_from_list($row['Page Product Form Code']);

            $sql=sprintf("update `Page Product List Dimension` set `Page Product List Number Products`=%d where `Page Product Form Key`=%d",
                         count($products),
                         $row['Page Product Form Key']
                        );
            mysql_query($sql);
            foreach($products as $product) {
                $products_from_family[$product['Product ID']]=$product['Product ID'];

            }
            $number_lists++;
        }
        $this->data['Number Products In Lists']=count($products_from_family);
        $this->data['Number Lists']=$number_lists;



        $sql=sprintf("select count(*) as num from `Page Product Dimension`  where `Page Key`=%d",$this->id);
        $res=mysql_query($sql);

        if ($row=mysql_fetch_assoc($res)) {
            $this->data['Number Buttons']=$row['num'];
        }

        $this->data['Number Products']=$this->data['Number Buttons']+$this->data['Number Products In Lists'];
        $sql=sprintf("update `Page Store Dimension`  set `Number Buttons`=%d , `Number Products`=%d ,`Number Lists`=%d,`Number Products In Lists`=%d where `Page Key`=%d",
                     $this->data['Number Buttons'],
                     $this->data['Number Products'],
                     $this->data['Number Lists'],
                     $this->data['Number Products In Lists'],
                     $this->id);
        $res=mysql_query($sql);

    }

    function update_button_products() {
        include_once('class.Product.php');
        $buttons=$this->get_button_products_from_source();

        $sql=sprintf("delete from  `Page Product Dimension`  where `Page Key`=%d",$this->id);
        mysql_query($sql);

        foreach($buttons as $product_code) {



            $product=new Product('code_store',$product_code,$this->data['Page Store Key']);

            if ($product->id) {
                $sql=sprintf("insert into `Page Product Dimension` (`Page Key`,`Product ID`) values  (%d,%d)",
                             $this->id,
                             $product->pid

                            );

                mysql_query($sql);
            }
        }

        $this->update_number_products();



    }

    function get_list_products_from_source() {
        $html=$this->data['Page Store Source'];


        $lists=array();

        $regexp = '\{\s*\$page->display_list\s*\((.*)\)\s*\}';
        if (preg_match_all("/$regexp/siU", $html, $matches, PREG_SET_ORDER)) {
            foreach($matches as $match) {
                $lists[]=($match[1]==''?'default':$match[1]);
            }
        }

        return $lists;
    }

    function get_button_products_from_source() {
        $html=$this->data['Page Store Source'];



        $buttons=array();

        $regexp = '\{\s*\$page->display_button\s*\((.*)\)\s*\}';
        if (preg_match_all("/$regexp/siU", $html, $matches, PREG_SET_ORDER)) {
            foreach($matches as $match) {

                $id=($match[1]==''?'default':$match[1]);
                $id=preg_replace('/^\"/','',$id);
                $id=preg_replace('/^\'/','',$id);
                $id=preg_replace('/\"$/','',$id);
                $id=preg_replace('/\'$/','',$id);
                $buttons[]=$id;
            }
        }

        return $buttons;
    }

    function display_search() {
        print $this->site->display_search();
    }

    function display_menu() {
        print $this->site->display_menu();
    }


    function update_preview_snapshot() {

        global $inikoo_public_url;




        $r = join('',unpack('v*', fread(fopen('/dev/urandom', 'r'),25)));
        $pwd=uniqid('',true).sha1(mt_rand()).'.'.$r;

        $sql=sprintf("insert into `MasterKey Dimension` (`User Key`,`Key`,`Valid Until`,`IP`)values (%s,%s,%s,%s) "
                     ,1
                     ,prepare_mysql($pwd)
                     ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now +5 minute")))
                     ,prepare_mysql(ip())
                    );

//print $sql;
        mysql_query($sql);
  
  
  $old_image_key=$this->data['Page Preview Snapshot Image Key'];

        //   $new_image_key=$old_image_key;
        //      $image=new Image($image_key);



        $height=$this->data['Page Header Height']+$this->data['Page Content Height']+$this->data['Page Footer Height']+10;
//ar_edit_sites.php?tipo=update_page_snapshot&id=1951;

        $url="http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/authorization.php?url=".urlencode("page_preview.php?header=0&id=".$this->id).'\&mk='.$pwd;

        ob_start();
        system("uname");



        $_system = ob_get_clean();
        if (preg_match('/darwin/i',$_system)) {
            $command="mantenence/scripts/webkit2png_mac.py  -C -o app_files/tmp/pp_image".$this->id."  --clipheight=".($height*0.5)."  --clipwidth=512  -s 0.5  ".$url;

            //       $command="mantenence/scripts/webkit2png  -C -o app_files/tmp/ph_image".$this->id."  --clipheight=80  --clipwidth=488  -s 0.5   http://localhost/dw/public_header_preview.php?id=".$this->id;

        }

        elseif(preg_match('/linux/i',$_system)) {
              $command='xvfb-run --server-args="-screen 0, 1280x1024x24" python mantenence/scripts/webkit2png_linux.py --log=app_files/tmp/webkit2png_linux.log -o app_files/tmp/pp_image'.$this->id.'-clipped.png    '.$url;

          //  $command='xvfb-run --server-args="-screen 0, 1280x1024x24" python mantenence/scripts/webkit2png_linux.py --log=app_files/tmp/webkit2png_linux.log -o app_files/tmp/pp_image'.$this->id.'-clipped.png --scale  512 '.(ceil($height*0.5)).'    '.$url;
        }
        else {
            return;

        }

 print "$command  $retval\n\n";


        ob_start();
        system($command,$retval);
        ob_get_clean();
        $this->snapshots_taken++;


        $image_data=array('file'=>"app_files/tmp/pp_image".$this->id."-clipped.png",'source_path'=>'','name'=>'page_preview'.$this->id);

//   print_r($image_data);
        $image=new Image('find',$image_data,'create');
//   print "x1\n";
        unlink("app_files/tmp/pp_image".$this->id."-clipped.png");
        //  print "x2\n";
        $new_image_key=$image->id;
        if (!$new_image_key) {
            print $image->msg;
            exit("xx \n");

        }


        //    print "x3\n";
        //  print "$new_image_key $old_image_key\n";
        //  print $image->msg." x4\n";



        if ($new_image_key!=$old_image_key and $new_image_key) {
            $this->data['Page Preview Snapshot Image Key']=$new_image_key;
            $sql=sprintf("delete from `Image Bridge` where `Subject Type`=%s and `Subject Key`=%d and `Image Key`=%d ",
                         prepare_mysql('Page Preview'),
                         $this->id,
                         $image->id
                        );
            mysql_query($sql);
//print $sql;
            $old_image=new Image($old_image_key);
            $old_image->delete();


            $sql=sprintf("insert into `Image Bridge` values (%s,%d,%d,'Yes','')",
                         prepare_mysql('Page Preview'),
                         $this->id,
                         $image->id

                        );
            mysql_query($sql);
//print $sql;
            $sql=sprintf("update `Page Store Dimension` set `Page Preview Snapshot Image Key`=%d  where `Page Key`=%d",
                         $this->data['Page Preview Snapshot Image Key'],
                         $this->id

                        );
            mysql_query($sql);
            //  print $sql;

            $this->updated=true;
            $this->new_value=$this->data['Page Preview Snapshot Image Key'];

        }


            usleep(250000);
        $this->get_data('id',$this->id);
        $new_height=$this->data['Page Header Height']+$this->data['Page Content Height']+$this->data['Page Footer Height']+10;

        if ($new_height!=$height) {
            $this->update_preview_snapshot();
        }

    }

}






?>