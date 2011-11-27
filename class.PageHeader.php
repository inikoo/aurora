<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Created: 25 November 2011 18:33:33 GMT
 Copyright (c) 2011, Inikoo

 Version 2.0
*/
include_once('class.DB_Table.php');
include_once('class.Page.php');
class PageHeader extends DB_Table {

    var $new=false;

    function PageHeader($arg1=false,$arg2=false) {
        $this->table_name='Page Header';
        $this->ignore_fields=array('Page Header Key');


        if (!$arg1 and !$arg2) {
            $this->error=true;
            $this->msg='No arguments';
        }
        if (is_numeric($arg1)) {
            $this->get_data('id',$arg1);
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

        $this->get_data($arg1,$arg2);

    }


    function get_data($tipo,$tag,$tag2=false) {


        $sql=sprintf("select * from `Page Header Dimension` where  `Page Header Key`=%d",$tag);



        $result =mysql_query($sql);
        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->id=$this->data['Page Header Key'];

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




        $sql=sprintf("select `Page Header Key` from `Page Header Dimension`  where `Page Header Name`=%s and `Site Key`=%d",
                     prepare_mysql($data['Page Header Name']),
                     $data['Site Key']

                    );
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            $this->found=true;
            $this->found_key=$row['Page Header Key'];
            $this->get_data('id',$this->found_key);
        }


        if (!$this->found and $create) {
            $this->create($raw_data);

        }


    }


    function create($raw_data) {
        $temporal_name=false;
        $this->new=false;
        if (!isset($raw_data['Page Header Name']) or  $raw_data['Page Header Name']=='') {

            $raw_data['Page Header Name']=uniqid();
            $temporal_name='header';

        }
        
         $sql=sprintf("select `Page Header Name` from `Page Header Dimension`  where `Page Header Name`=%s and `Site Key`=%d",
                     prepare_mysql($raw_data['Page Header Name']),
                     $raw_data['Site Key']

                    );
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
           $raw_data['Page Header Name']=uniqid();
            $temporal_name=$row['Page Header Name'];
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
            if (preg_match('/Template|Javascript|CSS/i',$key))
                $values.="'".addslashes($value)."',";
            else
                $values.=prepare_mysql($value).",";
        }
        $keys=preg_replace('/,$/',')',$keys);
        $values=preg_replace('/,$/',')',$values);
        $sql=sprintf("insert into `Page Header Dimension` %s %s",$keys,$values);


        if (mysql_query($sql)) {
            $this->id=mysql_insert_id();

            if ($temporal_name) {
                $sql=sprintf("update `Page Header Dimension` set `Page Header Name`=%s where `Page Header Key`=%d",
                prepare_mysql($temporal_name.$this->id),
                $this->id
                );
                mysql_query($sql);

            }


            $this->get_data('id',$this->id);

            $site=new Site ($this->data['Site Key']);
            $site->update_headers($this->id);



        } else {
            $this->error=true;
            $this->msg='Can not insert Page Header Dimension';
            exit("$sql\n");
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






    function display($tipo='link') {

        switch ($tipo) {
        case('html'):
        case('xhtml'):
        case('link'):
        default:
            return '<a href="'.$this->data['Page URL'].'">'.$this->data['Page Title'].'</a>';

        }


    }



    function update_thumbnail_key($image_key) {

        $old_value=$this->data['Page Thumbnail Image Key'];
        if ($old_value!=$image_key) {
            $this->updated;
            $this->data['Page Thumbnail Image Key']=$image_key;

            $sql=sprintf("update `Page Header Dimension` set `Page Thumbnail Image Key`=%d ,`Page Snapshot Last Update`=NOW() where `Page Header Key`=%d "
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




    function update_field_switcher($field,$value,$options='') {


        switch ($field) {
    
        default:
            $base_data=$this->base_data();
            if (array_key_exists($field,$base_data)) {

                if ($value!=$this->data[$field]) {

                    $this->update_field($field,$value,$options);
                }
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
        $sql=sprintf("delete from `Page Header Dimension` where `Page Header Key`=%d",$this->id);



        $this->deleted=true;

    }






}






?>