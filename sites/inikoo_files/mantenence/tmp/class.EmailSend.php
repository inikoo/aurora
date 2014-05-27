<?php
/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once('class.DB_Table.php');
include_once('class.Email.php');
include_once('class.Customer.php');
class EmailSend extends DB_Table {



    function EmailSend($arg1=false,$arg2=false) {

        $this->secret="A390%4m*eodO)PIPOldyhs.dpdbwid";
        $this->table_name='Email Send';
        $this->ignore_fields=array('Email Send Key','Email Send First Read Date','Email Send Last Read Date','Email Send Number Reads','Email Send Date');
        if (is_numeric($arg1)) {
            $this->get_data('id',$arg1);
            return ;
        }

        $this->get_data($arg1,$arg2);



    }


    function get_data($key,$tag) {

        if ($key=='id')
            $sql=sprintf("select *  from `Email Send Dimension` where `Email Send Key`=%d",$tag);

        else
            return;
        $result=mysql_query($sql);
        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->id=$this->data['Email Send Key'];
        }
    }



    function create($data) {

        $this->new=false;
        $base_data=$this->base_data();

        foreach($data as $key=>$value) {
            if (array_key_exists($key,$base_data))
                $base_data[$key]=_trim($value);
        }

        $keys='(';
        $values='values(';
        foreach($base_data as $key=>$value) {
            $keys.="`$key`,";
            $values.=prepare_mysql($value).",";
        }
        $keys=preg_replace('/,$/',')',$keys);
        $values=preg_replace('/,$/',')',$values);
        $sql=sprintf("insert into `Email Send Dimension` %s %s",$keys,$values);
       // print $sql;
        if (mysql_query($sql)) {
            $this->id = mysql_insert_id();
            $this->msg=_("Email to be Send Created");
            $this->get_data('id',$this->id);
            $this->new=true;
            return;
        } else {
            $this->msg=_(" Error can not create email to be send");
        }
    }


    function get($key,$data=false) {
        switch ($key) {
        case('Number Reads'):
            if ($this->data['Email Send Number Reads']=='')
                return _('ND');
            else
                return number($this->data['Email Send Number Reads']);
            break;
        default:
            if (array_key_exits($key,$this->data))
                return $this->data[$key];
            else
                return '';
        }

    }

    function update_read() {

        $sql=sprintf("select count(*) as number_reads, min(`Email Send Read Date`) as first, max(`Email Send Read Date`) last from `Email Send Read Fact` where `Email Send Key`=%s  ",
                     $this->id
                    );
        $res=mysql_query($sql);
        
        if ($row=mysql_fetch_assoc($res)) {
            $this->data['Email Send Number Reads']=$row['number_reads'];
            $this->data['Email Send First Read Date']=$row['first'];
            $this->data['Email Send Last Read Date']=$row['last'];
        } else {
            $this->data['Email Send Number Reads']=0;
            $this->data['Email Send First Read Date']='';
            $this->data['Email Send Last Read Date']='';

        }
        $sql=sprintf("update `Email Send Dimension` set  `Email Send Number Reads`=%d,`Email Send First Read Date`=%s,`Email Send Last Read Date`=%s where `Email Send Key`=%d",
                     $this->data['Email Send Number Reads'],
                     prepare_mysql($this->data['Email Send First Read Date']),
                     prepare_mysql($this->data['Email Send Last Read Date']),
                     $this->id
                    );
        mysql_query($sql);

    }

}



?>