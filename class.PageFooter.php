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
class PageFooter extends DB_Table {

    var $new=false;

    function PageFooter($arg1=false,$arg2=false) {
        $this->table_name='Page Footer';
        $this->ignore_fields=array('Page Footer Key');


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


        $sql=sprintf("select * from `Page Footer Dimension` where  `Page Footer Key`=%d",$tag);



        $result =mysql_query($sql);
        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->id=$this->data['Page Footer Key'];

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




        $sql=sprintf("select `Page Footer Key` from `Page Footer Dimension`  where `Page Footer Name`=%s and `Site Key`=%d",
                     prepare_mysql($data['Page Footer Name']),
                     $data['Site Key']

                    );
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            $this->found=true;
            $this->found_key=$row['Page Footer Key'];
            $this->get_data('id',$this->found_key);
        }


        if (!$this->found and $create) {
            $this->create($raw_data);

        }


    }


    function create($raw_data) {
        $temporal_name=false;
        $this->new=false;
        if (!isset($raw_data['Page Footer Name']) or  $raw_data['Page Footer Name']=='') {

            $raw_data['Page Footer Name']=uniqid();
            $temporal_name='footer';

        }
        
         $sql=sprintf("select `Page Footer Name` from `Page Footer Dimension`  where `Page Footer Name`=%s and `Site Key`=%d",
                     prepare_mysql($raw_data['Page Footer Name']),
                     $raw_data['Site Key']

                    );
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
           $raw_data['Page Footer Name']=uniqid();
            $temporal_name=$row['Page Footer Name'];
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
        $sql=sprintf("insert into `Page Footer Dimension` %s %s",$keys,$values);


        if (mysql_query($sql)) {
            $this->id=mysql_insert_id();

            if ($temporal_name) {
                $sql=sprintf("update `Page Footer Dimension` set `Page Footer Name`=%s where `Page Footer Key`=%d",
                prepare_mysql($temporal_name.$this->id),
                $this->id
                );
                mysql_query($sql);

            }


            $this->get_data('id',$this->id);

            $site=new Site ($this->data['Site Key']);
            $site->update_footers($this->id);



        } else {
            $this->error=true;
            $this->msg='Can not insert Page Footer Dimension';
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






   
  function update_snapshot() {

        global $inikoo_public_url;
        $old_image_key=$this->data['Page Footer Preview Image Key'];

        $new_image_key=$old_image_key;
        //      $image=new Image($image_key);
        $url="http://localhost/".dirname($_SERVER['PHP_SELF'])."/public_footer_preview.php?id=".$this->id;


        ob_start();
        system("uname");



        $_system = ob_get_clean();



        if (preg_match('/darwin/i',$_system)) {
            $command="mantenence/scripts/webkit2png_mac.py  -C -o app_files/tmp/pfooter_image".$this->id."  --clipheight=80  --clipwidth=488  -s 0.5    ".$url;

            //       $command="mantenence/scripts/webkit2png  -C -o app_files/tmp/pfooter_image".$this->id."  --clipheight=80  --clipwidth=488  -s 0.5   http://localhost/dw/public_footer_preview.php?id=".$this->id;

        }

        elseif(preg_match('/linux/i',$_system)) {
            $command='xvfb-run --server-args="-screen 0, 1280x1024x24" python mantenence/scripts/webkit2png_linux.py --log=app_files/tmp/webkit2png_linux.log    -o app_files/tmp/pfooter_image'.$this->id.'-clipped.png -g 976 160 --scale 488 80    '.$url;



        }
        else {
            return;

        }



        ob_start();
        system($command,$retval);
        ob_get_clean();

      //  print "$command  $retval";



        $image_data=array('file'=>"app_files/tmp/pfooter_image".$this->id."-clipped.png",'source_path'=>'','name'=>'page_footer'.$this->id);
        $image=new Image('find',$image_data,'create');
      
        if ($image->id) {
            $new_image_key=$image->id;

        }

        if ($new_image_key!=$old_image_key) {
            $this->data['Page Footer Preview Image Key']=$new_image_key;
            $sql=sprintf("delete from `Image Bridge` where `Subject Type`=%s and `Subject Key`=%d and `Image Key`=%d ",
                         prepare_mysql('Page Footer Preview'),
                         $this->id,
                         $image->id
                        );
            mysql_query($sql);

            $old_image=new Image($old_image_key);
            $old_image->delete();


            $sql=sprintf("insert into `Image Bridge` values (%s,%d,%d,'Yes','')",
                         prepare_mysql('Page Footer Preview'),
                         $this->id,
                         $image->id
                     
                        );
            mysql_query($sql);

            $sql=sprintf("update `Page Footer Dimension` set `Page Footer Preview Image Key`=%d  where `Page Footer Key`=%d",
                         $this->data['Page Footer Preview Image Key'],
                         $this->id

                        );
            mysql_query($sql);
            //      print $sql;

            $this->updated=true;
            $this->new_value=$this->data['Page Footer Preview Image Key'];

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






  
  function delete() {
    
    include_once("class.Image.php");
        $this->deleted=false;
        $sql=sprintf("delete from `Page Footer Dimension` where `Page Footer Key`=%d",$this->id);
        mysql_query($sql);

        $images=array();
        $sql=sprintf("select `Image Key` from `Image Bridge` where `Subject Type` in ('Page Footer','Page Footer Preview') and `Subject Key`=%d",$this->id);
        $res=mysql_query($sql);
        while ($row=mysql_fetch_array($res)) {
            $images[$row['Image Key']]=$row['Image Key'];
        }

        $sql=sprintf("select `Page Store External File Key` from `Page Footer External File Bridge` where `Page Footer Key`=%d",$this->id);
        $res=mysql_query($sql);
        while ($row=mysql_fetch_array($res)) {
            $sql=sprintf("delete from `Page Store External File Dimension` where `Page Store External File Key`=%d ",$row['Page Store External File Key']);
            mysql_query($sql);
        }
        $sql=sprintf("delete from `Page Footer External File Bridge` where `Page Footer Key`=%d",$this->id);
        mysql_query($sql);


        $sql=sprintf("delete from `Image Bridge` where `Subject Type` in ('Page Footer','Page Footer Preview') and `Subject Key`=%d",$this->id);
        mysql_query($sql);

        foreach($images as $image_key) {
            $image=new Image($image_key);
            if($image->id)
            $image->delete();
        }


        $sql=sprintf("delete from `Page Footer Dimension` where `Page Footer Key`=%d",$this->id);
        mysql_query($sql);

        $this->deleted=true;

    }


   function get_number_pages() {
        $number_pages=0;
        $sql=sprintf("select count(*) as num from `Page Store Dimension`  where `Page Footer Key`=%d and `Page Site Key`=%d",
                     $this->id,
                     $this->data['Site Key']

                    );
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            $number_pages=$row['num'];
        }
        return $number_pages;
    }

    function update_number_pages() {
        $this->data['Number Pages']=$this->get_number_pages();
        $sql=sprintf("update `Page Footer Dimension` set `Number Pages`=%d  where `Page Footer Key`=%d",
                     $this->data['Number Pages'],
                     $this->id
                    );
        mysql_query($sql);

    }





}






?>