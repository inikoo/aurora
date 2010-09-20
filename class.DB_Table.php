<?php
abstract class DB_Table {
    protected $table_name;
    protected  $ignore_fields=array();
    public $errors_while_updating=array();
    public $updated_fields=array();
    public $data=array();
    public  $id=0;
    public $warning=false;
    public $error=false;
    public $msg='';
    public $new=false;
    public $updated=false;
    public $new_value=false;
    public $error_updated=false;
    public $msg_updated='';
    public $found=false;
    public $found_key=false;
    public $no_history=false;
    public $candidate=array();
    public $updated_field=array();

    public $editor=array(
                       'Author Name'=>false,
                       'Author Alias'=>false,
                       'Author Key'=>0,
                       'User Key'=>0,
                       'Date'=>false
                   );


    function base_data() {


        $data=array();
        $result = mysql_query("SHOW COLUMNS FROM `".$this->table_name." Dimension`");
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


    public function update($data,$options='') {
        if (!is_array($data)) {
            $this->error=true;
            return;
        }

        if (isset($data['editor'])) {
            foreach($data['editor'] as $key=>$value) {

                if (array_key_exists($key,$this->editor))
                    $this->editor[$key]=$value;

            }
        }



        foreach($data as $key=>$value) {
            $this->update_field_switcher($key,$value,$options);


        }

        if (!$this->updated)
            $this->msg.=' '._('Nothing to be updated')."\n";
    }

    protected function update_field_switcher($field,$value,$options='') {



        $base_data=$this->base_data();


        if (preg_match('/^Address.*Data$/',$field))
            $this->update_field($field,$value,$options);
        elseif(array_key_exists($field,$base_data)) {

            if ($value!=$this->data[$field]) {

                $this->update_field($field,$value,$options);
            }
        }





    }

    protected function translate_data($data,$options='') {

        $_data=array();
        foreach($data as $key => $value) {

            if (preg_match('/supplier/i',$options))
                $regeprix='/^Supplier /i';
            elseif(preg_match('/customer/i',$options))
            $regex='/^Customer /i';
            elseif(preg_match('/company/i',$options))
            $regex='/^Company /i';
            elseif(preg_match('/contact/i',$options))
            $regex='/^Contact /i';

            $rpl=$this->table_name.' ';


            $_key=preg_replace($regex,$rpl,$key);
            $_data[$_key]=$value;
        }




        return $_data;
    }

    protected function update_field($field,$value,$options='') {




        if (is_array($value))
            return;
        $value=_trim($value);


        $old_value=_('Unknown');

        $key_field=$this->table_name." Key";
        if ($this->table_name=='Supplier Product')
            $key_field='Supplier Product Current Key';

        $sql="select `".$field."` as value from  `".$this->table_name." Dimension`  where `$key_field`=".$this->id;
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
            $old_value=$row['value'];
        }


        $sql="update `".$this->table_name." Dimension` set `".$field."`=".prepare_mysql($value)." where `$key_field`=".$this->id;

        mysql_query($sql);
        $affected=mysql_affected_rows();
        if ($affected==-1) {
            $this->msg.=' '._('Record can not be updated')."\n";
            $this->error_updated=true;
            $this->error=true;

            return;
        }
        elseif($affected==0) {
            $this->data[$field]=$value;
        }
        else {
            $this->data[$field]=$value;
            $this->msg.=" $field "._('Record updated').", \n";
            $this->msg_updated.=" $field "._('Record updated').", \n";
            $this->updated=true;
            $this->new_value=$value;

            $save_history=true;
            if (preg_match('/no( |\_)history|nohistory/i',$options))
                $save_history=false;
            if (
                preg_match('/customer|contact|company|order|staff|supplier|address|telecom|user|store|product|company area|company department|position/i',$this->table_name)
                and !$this->new
                and $save_history
            ) {
                $history_data=array(
                                  'Indirect Object'=>$field
                                                    ,'old_value'=>$old_value
                                                                 ,'new_value'=>$value

                              );
                if ($this->table_name=='Product Family')
                    $history_data['direct_object']='Family';
                if ($this->table_name=='Product Department')
                    $history_data['direct_object']='Department';


                $this->add_history($history_data);

            }

        }

    }


    protected function get_editor_data() {



        if (isset($this->editor['Date'])  and preg_match('/^\d{4}-\d{2}-\d{2}/',$this->editor['Date']))
            $date=$this->editor['Date'];
        else
            $date=date("Y-m-d H:i:s");

        $user_key=1;



        if (isset($this->editor['User Key'])and is_numeric($this->editor['User Key'])  )
            $user_key=$this->editor['User Key'];
        else
            $user_key=0;



        return array(
                   'User Key'=>$user_key
                              ,'Date'=>$date
               );
    }



    protected function add_history($raw_data,$force=false) {

        $editor_data=$this->get_editor_data();

        if ($this->no_history)
            return;

        if ($this->new and !$force)
            return;



        if ($this->table_name=='Product Department')
            $table='Department';
        elseif($this->table_name=='Product Family')
        $table='Family';
        else
            $table=$this->table_name;


        if (!isset($raw_data['Subject']) or  !isset($raw_data['Subject Key']) ) {
            include_once('class.User.php');
            $user=new User($editor_data['User Key']);
            if ($user->id) {

                $data['Subject']=$user->data['User Type'];
                $data['Subject Key']=$user->data['User Parent Key'];
            } else {
                $data['Subject']='Staff';
                $data['Subject Key']=0;
            }

        }

        $data['User Key']=$editor_data['User Key'];
        $data['Metadata']='';

        $data['Action']='edited';
        $data['Direct Object']=$table;
        if ($this->table_name=='Product')
            $data['Direct Object Key']=$this->pid;
        else
            $data['Direct Object Key']=$this->id;
        $data['Preposition']='to';
        $data['Indirect Object']='';
        $data['Indirect Object Key']=0;
        $data['Deep']=1;
        $data['Date']=$editor_data['Date'];
        if (isset($raw_data['Indirect Object']))
            $data['History Abstract']=$raw_data['Indirect Object'].' '._('changed');
        else
            $data['History Abstract']='Unknown';
        $data['History Details']=$data['History Abstract'];

        foreach($raw_data as $key=>$value) {
            $data[$key]=$value;
        }

        if ($data['Action']=='created') {
            $data['Preposition']='';
        }

        if (isset($raw_data['old_value']) and  isset($raw_data['new_value']) ) {
            $data['History Details']=$data['Indirect Object'].' '._('changed from')." \"".$raw_data['old_value']."\" "._('to')." \"".$raw_data['new_value']."\"";
        }
        elseif(  isset($raw_data['new_value']) ) {
            $data['History Details']=$data['Indirect Object'].' '._('changed to')." \"".$raw_data['new_value']."\"";
        }

        $sql=sprintf("insert into `History Dimension` (`History Date`,`Subject`,`Subject Key`,`Action`,`Direct Object`,`Direct Object Key`,`Preposition`,`Indirect Object`,`Indirect Object Key`,`History Abstract`,`History Details`,`User Key`,`Deep`,`Metadata`) values (%s,%s,%d,%s,%s,%d,%s,%s,%d,%s,%s,%d,%s,%s)"
                     ,prepare_mysql($data['Date'])
                     ,prepare_mysql($data['Subject'])
                     , $data['Subject Key']
                     ,prepare_mysql($data['Action'])
                     ,prepare_mysql($data['Direct Object'])
                     ,$data['Direct Object Key']
                     ,prepare_mysql($data['Preposition'],false)
                     ,prepare_mysql($data['Indirect Object'],false)
                     ,$data['Indirect Object Key']
                     ,prepare_mysql($data['History Abstract'])
                     ,prepare_mysql($data['History Details'])
                     , $data['User Key']
                     ,prepare_mysql($data['Deep'])
                     ,prepare_mysql($data['Metadata'])
                    );

        mysql_query($sql);
      

    }

    function set_editor($raw_data) {
        if (isset($raw_data['editor'])) {
            foreach($raw_data['editor'] as $key=>$value) {

                if (array_key_exists($key,$this->editor))
                    $this->editor[$key]=$value;

            }
        }

    }

    function reread() {
        $this->get_data('id',$this->id);
    }


}

?>
