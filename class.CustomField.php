<?php
/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo


*/
include_once('class.DB_Table.php');


class CustomField extends DB_Table {



    function CustomField($a1,$a2=false,$a3=false) {
        $this->table_name='Custom Field';
        $this->ignore_fields=array('Custom Field Key');

        if (is_numeric($a1) and !$a2) {
            $this->get_data('id',$a1);
        }
        elseif($a1=='find') {
            $this->find($a2,$a3);

        }
        else
            $this->get_data($a1,$a2);
    }



    function get_data($key,$tag) {

        if ($key=='id') {

            $sql=sprintf("select * from `Custom Field Dimension` where `Custom Field Key`=%d",$tag);

        }

        $result=mysql_query($sql);
        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->id=$this->data['Custom Field Key'];
        }



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

        $data=$this->base_data();
        foreach($raw_data as $key=>$value) {
            if (array_key_exists($key,$data))
                $data[$key]=_trim($value);
        }


        //    print_r($raw_data);



        $sql=sprintf("select `Custom Field Key` from `Custom Field Dimension` where `Custom Field Name` =%s and `Custom Field Store Key`=%s",
                     prepare_mysql($data['Custom Field Name']),
                     prepare_mysql($data['Custom Field Store Key'])
                    );

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->found=true;
            $this->found_key=$row['Custom Field Key'];
        }


        if ($create and !$this->found) {
            $this->create($data);
            return;
        }
        if ($this->found)
            $this->get_data('id',$this->found_key);




        if ($update and $this->found) {

        }


    }

    function create($data) {
        $this->new=false;
        $base_data=$this->base_data();




        foreach($data as $key=>$value) {
            if (array_key_exists($key,$base_data))
                $base_data[$key]=_trim($value);
        }

        if($base_data['Custom Field Name']==''){
              $this->error=true;
            $this->msg=" Label can not be empty";
            return;
        }

        $keys='(';
        $values='values(';

        //print_r ($data);

        foreach($base_data as $key=>$value) {
            $keys.="`$key`,";
            
            if($key=='Default Value')
            $values.=prepare_mysql($value,'false').",";
            else
            $values.=prepare_mysql($value).",";
        }
        $keys=preg_replace('/,$/',')',$keys);
        $values=preg_replace('/,$/',')',$values);
        $sql=sprintf("insert into `Custom Field Dimension` %s %s",$keys,$values);

        //print $sql;
        if (mysql_query($sql)) {
            $sql=sprintf("select `Custom Field Key` from `Custom Field Dimension` order by `Custom Field Key` DESC");
            $res=mysql_query($sql);
            $row=mysql_fetch_array($res);
            $custom_id=$row['Custom Field Key'];

            $this->id = mysql_insert_id();
            $this->msg=_("Custom Field");
            $this->get_data('id',$this->id);
            $this->new=true;

            switch ($base_data['Custom Field Table']) {
            case 'Customer':
                    if ($base_data['Custom Field Type'] == 'Enum')
                        $base_data['Custom Field Type'] = 'Enum(\'Yes\', \'No\')';

                    $sql = sprintf("ALTER TABLE `Customer Custom Field Dimension` ADD `%s` %s",
                                   $custom_id,
                                   $base_data['Custom Field Type']);
/*
                if ($base_data['Custom Field Type'] == 'Mediumint') {
                    if ($base_data['Default Value'] == '')
                        $base_data['Default Value'] = 0;
                    $sql = sprintf("ALTER TABLE `Customer Custom Field Dimension` ADD `%s` %s(8) default '%s'",
                                   $custom_id,
                                   $base_data['Custom Field Type'],
                                   $base_data['Default Value']);

                }
                elseif($base_data['Custom Field Type'] == 'Text') {
                    $sql = sprintf("ALTER TABLE `Customer Custom Field Dimension` ADD `%s` %s(255) default '%s'",
                                   $custom_id,
                                   $base_data['Custom Field Type'],
                                   $base_data['Default Value']);
                }
*/
		
                mysql_query($sql);
                //print $sql;
                break;

            case 'Part':
                if ($base_data['Custom Field Type'] == 'Mediumint') {
                    if ($base_data['Default Value'] == '')
                        $base_data['Default Value'] = 0;
                    $sql = sprintf("ALTER TABLE `Part Custom Field Dimension` ADD `%s` %s(8) default '%s'",
                                   $custom_id,
                                   $base_data['Custom Field Type'],
                                   $base_data['Default Value']);

                }
                elseif($base_data['Custom Field Type'] == 'varchar') {
                    $sql = sprintf("ALTER TABLE `Part Custom Field Dimension` ADD `%s` %s(255) default '%s'",
                                   $custom_id,
                                   $base_data['Custom Field Type'],
                                   $base_data['Default Value']);
                }
                mysql_query($sql);
                print $sql;
                break;
            }



            return;
        } else {
        $this->error=true;
            $this->msg=" Error can not create Custom Field";
        }
    }







    function get($key,$data=false) {
        switch ($key) {

     
        default:
            if (isset($this->data[$key]))
                return $this->data[$key];
            else
                return '';
        }
        return '';
    }


   


}
?>