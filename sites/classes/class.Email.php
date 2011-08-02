<?php
/*
 File: Email.php

 This file contains the Email Class

 Each email has to be associated with a contact if no contac data is provided when the Email is created an anonimous contact will be created as well.


 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/


include_once('class.Contact.php');

/* class: Email
 Class to manage the *Email Dimension* table
*/



class Email extends DB_Table {

    public  $deleted=false;
    public $updated=false;



    /*
     Constructor: Email
     Initializes the class, trigger  Search/Load/Create for the data set

     If first argument is find it will try to match the data or create if not found

     Parameters:
     arg1 -    Tag for the Search/Load/Create Options *or* the Contact Key for a simple object key search
     arg2 -    (optional) Data used to search or create the object

     Returns:
     void

     Example:
     (start example)
     // Load data from `Email Dimension` table where  `Email Key`=3
     $key=3;
     $email = New Email($key);

     // Load data from `Email Dimension` table where  `Email`='raul@gmail.com'
     $email = New Email('raul@gmail.com');

     // Insert row to `Email Dimension` table
     $data=array();
     $email = New Email('new',$data);


     (end example)

    */
    function Email($arg1=false,$arg2=false) {

        $this->table_name='Email';
        $this->ignore_fields=array('Email Key');


        if (!$arg1 and !$arg2) {
            $this->error=true;
            $this->msg='No data provided';
            return;
        }
        if (is_numeric($arg1)) {
            $this->get_data('id',$arg1);
            return;
        }
        if ($arg1=='new') {
            $this->create($arg2);
            return;
        }
        if (preg_match('/find/i',$arg1)) {
            $this->find($arg2,$arg1);
            return;
        }
        $this->get_data($arg1,$arg2);
    }
    /*
     Method: get_data
     Load the data from the database

     See Also:
     <find>
    */
    function get_data($tipo,$tag) {
        if ($tipo=='id')
            $sql=sprintf("select * from `Email Dimension` where  `Email Key`=%d",$tag);
        elseif($tipo=='email')
        $sql=sprintf("select * from `Email Dimension` where  `Email`=%s",prepare_mysql($tag));
        else
            return;
        $result=mysql_query($sql);
        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
            $this->id=$this->data['Email Key'];
    }


    /*
     Method: find
     Given a set of email components try to find it on the database updating properties, if not found creates a new record

     Parmaters:
     $raw_data - associative array with the email data (DB fields as keys)
     $options - string

     auto - the method will update/create the email with out asking for instructions
     create|update - methos will create or update the email with the data provided


    */

    function find($raw_data,$options='') {
        $find_fuzzy=false;

        if (preg_match('/fuzzy/i',$options)) {
            $find_fuzzy=true;

        }

        $this->found=false;

        $this->candiadate=array();

        $create=false;
        if (preg_match('/create|update/i',$options)) {
            $create=true;
        }
        $auto=false;
        if (preg_match('/auto/i',$options)) {
            $auto=true;
        }


        if (!$raw_data) {
            $this->new=false;
            $this->msg=_('Error no email data');
            if (preg_match('/exit on errors/',$options))
                exit($this->msg);
            return false;
        }






        if (is_string($raw_data)) {
            $tmp=$raw_data;
            unset($raw_data);
            $raw_data['Email']=$tmp;
        }

        if (isset($raw_data['editor'])) {
            foreach($raw_data['editor'] as $key=>$value) {

                if (array_key_exists($key,$this->editor))
                    $this->editor[$key]=$value;

            }
        }


        $data=$this->base_data();
        foreach($raw_data as $key=>$value) {
            if (array_key_exists($key,$data))
                $data[$key]=$value;
        }

        if ($data['Email']=='') {
            $this->msg=_('No email provided');
            return false;
        }
        elseif($this->wrong_email($data['Email'])) {
            $this->msg=_('Wrong email').": ".$data['Email'];
            $this->error=true;
            return false;
        }
        else
            $data['Email Validated']=($this->is_valid($data['Email'])?'Yes':'No');



        $data['Email']=$this->prepare_email($data['Email']);


        if ($raw_data['Email']!='') {


            $sql=sprintf("select T.`Email Key`,`Subject Key` from `Email Dimension` T left join `Email Bridge` TB  on (TB.`Email Key`=T.`Email Key`) where `Email`=%s and `Subject Type`='Contact'  "
                         ,prepare_mysql($raw_data['Email'])
                        );

            $result=mysql_query($sql);
            $num_results=mysql_num_rows($result);
            if ($num_results==1) {
                $row=mysql_fetch_array($result);
                $this->found=true;
                $this->found_key=$row['Subject Key'];
                $this->get_data('id',$row['Email Key']);
                $this->candidate[$row['Subject Key']]=1000;
            }
            elseif($num_results>1) {
                exit("error ".$raw_data['Email']." are in more than one contact\n");
            }
            elseif($find_fuzzy) {



                $email_max_score=200;
                $score_prize=800;
                $this->found=false;



                $sql=sprintf("select `Subject Key`,T.`Email Key`,damlevlim256(UPPER(%s),UPPER(`Email`),3) as dist1 from   `Email Dimension` T left join `Email Bridge` TB  on (TB.`Email Key`=T.`Email Key`)   where  `Subject Type`='Contact'  order by dist1  limit 10"
                             ,prepare_mysql($raw_data['Email'])
                            );

                $result=mysql_query($sql);
                while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
                    if ($row['dist1']>=3)
                        break;
                    $row['dist1']=    $row['dist1']/strlen($raw_data['Email']);

                    $score=$email_max_score*exp(-200*$row['dist1']*$row['dist1']);


                    $contact_key=$row['Subject Key'];
                    if (array_key_exists($contact_key,$this->candidate))
                        $this->candidate[$contact_key]+=$score;
                    else
                        $this->candidate[$contact_key]=$score;


                }

            }



        }


        if ($create and !$this->found) {



            $this->create($data,$options);

        }




    }




    /*Method: create
     Creates a new email record

    */
    protected function create($data,$options='') {

        // print_r($data);

        //print $this->editor;

        if (!$data) {
            $this->new=false;
            $this->msg.=" Error no email data";
            $this->error=true;
            if (preg_match('/exit on errors/',$options))
                exit($this->msg);
            return false;
        }

        if (is_string($data))
            $data['Email']=$data;

        global $myconf;

        $this->data=$this->base_data();
        foreach($data as $key=>$value) {
            if (array_key_exists($key,$this->data))
                $this->data[$key]=$value;
        }



        if ($this->data['Email']=='') {
            $this->new=false;
            $this->msg=_('No email provided');
            return false;
        }


        $sql=sprintf("select * from `Email Dimension` where `Email`=%s"
                     ,prepare_mysql($this->data['Email'])
                    );
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            print_r($data);
            exit("Error trying to add a duplicate email:\n");
        }


        if (!preg_match('/do not validate|validated ok/',$options))
            if ($this->is_valid($this->data['Email']))
                $this->data['Email Validated']='Yes';




        $sql=sprintf("insert into `Email Dimension`  (`Email`,`Email Contact Name`,`Email Validated`,`Email Correct`) values (%s,%s,%s,%s)"
                     ,prepare_mysql($this->data['Email'])
                     ,prepare_mysql($this->data['Email Contact Name'])
                     ,prepare_mysql($this->data['Email Validated'])
                     ,prepare_mysql($this->data['Email Correct'])
                    );

        if (mysql_query($sql)) {
            $this->id = mysql_insert_id();
            $this->get_data('id',$this->id);
            $this->new=true;

            $this->msg=_('New Email');

            $history_data=array(
                              'History Abstract'=>_('Email Created')
                                                 ,'History Details'=>_trim(_('Email')." \"".$this->display('plain')."\"  "._('created'))
                                                                    ,'Action'=>'created'
                          );
            $this->add_history($history_data);


            if (preg_match('/anonimous|anonymous/',$options) ) {
                $contact=new Contact('create anonimous');
                $contact->add_email(array(
                                        'Email Key'=>$this->id
                                                    ,'Email Description'=>'Unknown'
                                    ));
            }





            return true;
        } else {
            $this->new=false;
            $this->error=true;
            $this->msg=_('Error can not create email');
            if (preg_match('/exit on errors/',$options)) {
                print "Error can not create email;\n";
                exit;
            }
        }


    }

    function get($key) {
        if (isset($this->data[$key]))
            return $this->data[$key];

        switch ($key) {
        case('link'):
            return $this->display();
            break;
        }
        $_key=ucfirst($key);
        if (isset($this->data[$_key]))
            return $this->data[$_key];
        print "Error $key not found in get from email\n";
        return false;

    }



    /*Function: update_field_switcher
      */

    protected function update_field_switcher($field,$value,$options='') {

        switch ($field) {
        case('Email'):
            $this->update_Email($value,$options);
            break;
        case('Email Validated'):
            $this->update_EmailValidated($value,$options);
            break;
        case('Email Correct'):
            $this->update_EmailCorrect($value,$options);
            break;
        case('Email Contact Name'):
            $this->update_EmailContactName($value,$options);
            break;
        default:
            $this->update_field($field,$value,$options);
        }

    }




    /*Method: update_Email
     Update email address

     Return error if no email is provided or if there is another record with the same email address, a warning is returned if email not valid

     When $options is strict return error if the email is not valid
    */

    function update_Email($data,$options='') {
        //$this->error=false;
        //$this->warning=false;
        //$this->updated=false;


        if ($data=='') {
            $this->msg.=_('Email address can not be blank')."\n";
            $this->error=true;
            return;
        }

        $is_valid=$this->is_valid($data);
        if (!$is_valid) {
            $this->msg.=_('Email is not valid')." ($data)\n";
            if (preg_match('/email strict/i',$options) ) {
                $this->error=true;
                return;
            }
            $this->warning=true;
        }
        $old_value=$this->data['Email'];
        // print "$old_value -> $data";

        if ($old_value==$data) {
            $this->msg=_('Nothing to change');
            return;


        }

        $sql=sprintf("select * from `Email Dimension` where `Email` like binary %s   ",prepare_mysql($data));
        $res=mysql_query($sql);
        // print $sql;
        while ($row=mysql_fetch_array($res)) {
            //print_r($row);
            //print"email $data alredy n the system whan trying to updating it !!!!!!!! cjack in class.Email.php\n";
            $this->msg_updated=_('Email is already associated with another contact');
            $this->error_updated=true;
            return;
        }





        $sql=sprintf("update `Email Dimension` set `Email`=%s where `Email Key`=%d ",prepare_mysql($data),$this->id);

        mysql_query($sql);
//print $sql;
        $affected=mysql_affected_rows();

        if ($affected==-1) {
            $this->msg.=_('Email address can not be updated')."\n";
            $this->error=true;
            return;
        }
        elseif($affected==0) {
            //$this->msg=_('Same value as the old record');

        } else {
            $this->msg.=_('Email updated')."\n";
            $this->data['Email']=$data;
            $this->updated=true;
            $this->new_value=$this->data['Email'];
            $this->update_EmailValidated($options);
            $this->updated_fields['Email']=array(
                                               'Old Value'=>$old_value
                                                           ,'New Value'=>$this->data['Email']
                                           );

            $history_data['History Abstract']='Email Address Changed';
            $history_data['History Details']=_('Email address changed')." ".$old_value." -> ".$this->data['Email'];
            $history_data['Direct Object']='Email';
            $history_data['Direct Object Key']=$this->id;
            $history_data['Indirect Object']='Email Address';
            $history_data['Indirect Object Key']=0;
            $this->add_history($history_data);

            $this->update_parents();

        }


    }


    function update_parents($add_parent_history=true) {

        $parents=array('Contact','Company','Customer','Supplier');
        foreach($parents as $parent) {
            $sql=sprintf("select `$parent Key` as `Parent Key`   from  `$parent Dimension` where `$parent Main Email Key`=%d group by `$parent Key`",$this->id);
//print "$sql\n";
            $res=mysql_query($sql);
            while ($row=mysql_fetch_array($res)) {
                $principal_email_changed=false;

                if ($parent=='Contact') {
                    $parent_object=new Contact($row['Parent Key']);
                    $parent_label=_('Contact');
                }
                elseif($parent=='Customer') {
                    $parent_object=new Customer($row['Parent Key']);
                    $parent_label=_('Customer');
                }
                elseif($parent=='Supplier') {
                    $parent_object=new Supplier($row['Parent Key']);
                    $parent_label=_('Supplier');
                }
                elseif($parent=='Company') {
                    $parent_object=new Company($row['Parent Key']);
                    $parent_label=_('Company');
                }
                $parent_object->editor=$this->editor;
                $old_princial_email=$parent_object->data[$parent.' Main Plain Email'];
                $parent_object->data[$parent.' Main Plain Email']=$this->display('plain');
                $parent_object->data[$parent.' Main XHTML Email']=$this->display('xhtml');
                $sql=sprintf("update `$parent Dimension` set `$parent Main Plain Email`=%s,`$parent Main XHTML Email`=%s where `$parent Key`=%d"
                             ,prepare_mysql($parent_object->data[$parent.' Main Plain Email'])
                             ,prepare_mysql($parent_object->data[$parent.' Main XHTML Email'])
                             ,$parent_object->id
                            );
                mysql_query($sql);


                if ($old_princial_email!=$parent_object->data[$parent.' Main Plain Email'])
                    $principal_email_changed=true;

                if ($principal_email_changed and $add_parent_history) {
                    if ($old_princial_email=='') {
                        $history_data['History Abstract']='Email Associated '.$this->display('plain');
                        $history_data['History Details']=$this->display('plain')." "._('associated with')." ".$parent_object->get_name()." ".$parent_label;
                        $history_data['Action']='associated';
                        $history_data['Direct Object']=$parent;
                        $history_data['Direct Object Key']=$parent_object->id;
                        $history_data['Indirect Object']='Email';
                        $history_data['Indirect Object Key']=$this->id;
                    } else {
                        $history_data['History Abstract']='Email Changed to '.$this->display('plain');
                        $history_data['History Details']=_('Email changed from').' '.$old_princial_email.' '._('to').' '.$this->display('plain')." "._('in')." ".$parent_object->get_name()." ".$parent_label;
                        $history_data['Action']='changed';
                        $history_data['Direct Object']=$parent;
                        $history_data['Direct Object Key']=$parent_object->id;
                        $history_data['Indirect Object']='Email';
                        $history_data['Indirect Object Key']=$this->id;


                    }
                    if ($parent=='Customer') {
                        // print_r($history_data);
                        $parent_object->add_customer_history($history_data);
                    } else {
                        $this->add_history($history_data);
                    }


                }

            }
        }
    }


    /*Method: update_EmailValidated
     Update email address Is Valid field
    */
    function update_EmailValidated($options='') {

        $is_valid=$this->is_valid($this->data['Email']);
        if ($is_valid)
            $valid='Yes';
        else
            $valid='No';
        $sql=sprintf("update `Email Dimension` set `Email Validated`=%s where `Email Key`=%d ",prepare_mysql($valid),$this->id);
        mysql_query($sql);
        $affected=mysql_affected_rows();

        if ($affected==-1) {
            $this->msg.=' '._('Email Validated can not be updated')."\n";
            $this->error=true;
            return;
        }
        elseif($affected==0) {
            //$this->msg.=' '._('Same value as the old record');

        } else {
            $this->msg.=' '._('Record updated')."\n";
            $this->updated=true;
            $this->updated_fields['Email Validated']=true;
        }


    }
    /*Method: update_EmailCorrect
      Update Email Correct field

    */
    function update_EmailCorrect($data,$options='') {


        if (!($data=='Yes' or $data=='No' or $data=='Unknown')) {
            $this->msg.=' '._('Field wrong value')." $data";
            $this->error=true;
            return;
        }


        $sql=sprintf("update `Email Dimension` set `Email Correct`=%s where `Email Key`=%d ",prepare_mysql($data),$this->id);

        mysql_query($sql);
        $affected=mysql_affected_rows();

        if ($affected==-1) {
            $this->msg.=' '._('Record can not be updated')."\n";
            $this->error=true;
            return;
        }
        elseif($affected==0) {
            //$this->msg.=' '._('Same value as the old record');

        } else {
            $this->data['Email Correct']=$data;
            if ($this->data['Email Correct']=='Yes')
                $this->msg.=' '._('Email confirmed as correct')."\n";
            else
                $this->msg.=' '._('Email confirmed as incorrect')."\n";

            $this->updated_fields['Email Correct']=true;
            $this->updated=true;

        }


    }

    /*Method: update_EmailContactName
     Update email contact name  field
    */
    private function update_EmailContactName($data,$options='') {
        $this->error=false;
        $this->warning=false;
        $this->updated=false;

        $sql=sprintf("update `Email Dimension` set `Email Contact Name`=%s where `Email Key`=%d ",prepare_mysql($data,false),$this->id);
        mysql_query($sql);
        $affected=mysql_affected_rows();

        if ($affected==-1) {
            $this->msg.=' '._('Record can not be updated')."\n";
            $this->error=true;
            return;
        }
        elseif($affected==0) {


        } else {
            $this->updated_fields['Email Contact Name']=true;
            $this->msg.=' '._('Record updated')."\n";
            $this->data['Email Contact Name']=$data;
            $this->updated=true;
        }
    }













    function display($tipo='link') {


        if (!isset($this->data['Email'])) {
            print_r($this);
            exit("error no email data\n");
        }

        switch ($tipo) {
        case('plain'):
            return $this->data['Email'];

        case('html'):
        case('xhtml'):
        case('link'):
        default:
            return '<a href="mailto:'.$this->data['Email'].'">'.$this->data['Email'].'</a>';

        }


    }



    /**
    function: is_valid
    Validate an email address.
    Provide email address (raw input)
    Returns true if the email address has the email
    address format and the domain exists.
    */
    public static function is_valid($email) {
        $isValid = true;
        $atIndex = strrpos($email, "@");
        if (is_bool($atIndex) && !$atIndex) {
            $isValid = false;
        } else {
            $domain = substr($email, $atIndex+1);
            $local = substr($email, 0, $atIndex);
            $localLen = strlen($local);
            $domainLen = strlen($domain);
            if ($localLen < 1 || $localLen > 64) {
                // local part length exceeded
                $isValid = false;
            } else if ($domainLen < 1 || $domainLen > 255) {
                // domain part length exceeded
                $isValid = false;
            } else if ($local[0] == '.' || $local[$localLen-1] == '.') {
                // local part starts or ends with '.'
                $isValid = false;
            } else if (preg_match('/\\.\\./', $local)) {
                // local part has two consecutive dots
                $isValid = false;
            } else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
                // character not valid in domain part
                $isValid = false;
            } else if (preg_match('/\\.\\./', $domain)) {
                // domain part has two consecutive dots
                $isValid = false;
            } else if
            (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                         str_replace("\\\\","",$local))) {
                // character not valid in local part unless
                // local part is quoted
                if (!preg_match('/^"(\\\\"|[^"])+"$/',
                                str_replace("\\\\","",$local))) {
                    $isValid = false;
                }
            }
            //if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))){$isValid = false;}
        }
        return $isValid;
    }



    public static function  prepare_email($email) {

        $email_parts=preg_split('/\@/',$email);
        if (count($email_parts)==2) {
            return $email_parts[0].'@'.strtolower($email_parts[1]);
        } else
            return $email;

    }

    public static function  wrong_email($email) {

        if (!preg_match('/\@/',$email))
            return true;
        if (preg_match('/^\@|\@$/',$email))
            return true;
        $email_parts=preg_split('/\@/',$email);

        if (count($email_parts)!=2)
            return true;
        return false;

    }

    function set_scope($raw_scope='',$scope_key=0) {

        $scope='Unknown';
        $raw_scope=_trim($raw_scope);
        if (preg_match('/^customers?$/i',$raw_scope)) {
            $scope='Customer';
        } else if (preg_match('/^(contacts?|person)$/i',$raw_scope)) {
            $scope='Contact';
        } else if (preg_match('/^(company?|bussiness)$/i',$raw_scope)) {
            $scope='Company';
        } else if (preg_match('/^(supplier)$/i',$raw_scope)) {
            $scope='Supplier';
        } else if (preg_match('/^(staff)$/i',$raw_scope)) {
            $scope='Staff';
        }

        $this->scope=$scope;
        $this->scope_key=$scope_key;
        $this->load_metadata();

    }



    function load_metadata() {
        $this->data['Type']=array();
        $where_scope=sprintf(' and `Subject Type`=%s',prepare_mysql($this->scope));

        $where_scope_key='';
        if ($this->scope_key)
            $where_scope_key=sprintf(' and `Subject Key`=%d',$this->scope_key);

        $sql=sprintf("select * from `Email Bridge` where `Email Key`=%d %s  %s "
                     ,$this->id
                     ,$where_scope
                     ,$where_scope_key
                    );
        $res=mysql_query($sql);


        //    print $sql;
        $this->associated_with_scope=false;
        while ($row=mysql_fetch_array($res)) {
            $this->associated_with_scope=true;
            $this->data['Email Description']=$row['Email Description'];
            $this->data['Email Is Main']=$row['Is Main'];
            $this->data['Email Is Active']=$row['Is Active'];
        }


    }



    function has_parents() {
        $has_parents=false;
        $sql=sprintf("select count(*) as total from `Email Bridge`  where  `Email Key`=%d  ",$this->id);
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            if ($row['total']>0)
                $has_parents=true;
        }
        return $has_parents;
    }


    function delete() {
        $sql=sprintf("delete from `Email Dimension` where `Email Key`=%d",$this->id);
        mysql_query($sql);

        $this->deleted=true;
        $history_data['History Abstract']='Email Deleted';
        $history_data['History Details']=_('Email').' '.$this->display('plain')." "._('has been deleted');
        $history_data['Action']='deleted';
        $history_data['Direct Object']='Email';
        $history_data['Direct Object Key']=$this->id;
        $history_data['Indirect Object']='';
        $history_data['Indirect Object Key']='';
        $this->add_history($history_data);


        $parents=array('Contact','Company','Customer','Supplier');
        foreach($parents as $parent) {
            $sql=sprintf("select `$parent Key` as `Parent Key`   from  `$parent Dimension` where `$parent Main Email Key`=%d group by `$parent Key`",$this->id);

            $res=mysql_query($sql);
            while ($row=mysql_fetch_array($res)) {
                $this->remove_from_parent($parent,$row['Parent Key']);
            }
        }
        $sql=sprintf("delete from `Email Bridge`  where  `Email Key`=%d  ",$this->id);
        mysql_query($sql);
    }


    function remove_from_parent($parent,$parent_key) {


        $sql=sprintf("delete from `Email Bridge`  where  `Email Key`=%d and `Subject Type`=%s and `Subject Key`=%d  ",
                     $this->id,
                     prepare_mysql($parent),
                     $parent_key

                    );
        mysql_query($sql);
        //  print $sql;

        $principal_email_changed=false;

        if ($parent=='Contact') {
            $parent_object=new Contact($parent_key);
            $parent_label=_('Contact');
        }
        elseif($parent=='Customer') {
            $parent_object=new Customer($parent_key);
            $parent_label=_('Customer');
        }
        elseif($parent=='Supplier') {
            $parent_object=new Supplier($parent_key);
            $parent_label=_('Supplier');
        }
        elseif($parent=='Company') {
            $parent_object=new Company($parent_key);
            $parent_label=_('Company');
        }
        $sql=sprintf("update `$parent Dimension` set `$parent Main Email Key`=NULL, `$parent Main Plain Email`='',`$parent Main XHTML Email`='' where `$parent Key`=%d and `$parent Main Email Key`=%d"

                     ,$parent_object->id
                     ,$this->id
                    );
        mysql_query($sql);
        
      $principal_affected=mysql_affected_rows();

        
        //print $sql;
        $history_data['History Abstract']='Email Removed';
        $history_data['History Details']=_('Email').' '.$this->display('plain')." "._('has been deleted from')." ".$parent_object->get_name()." ".$parent_label;
        $history_data['Action']='disassociate';
        $history_data['Direct Object']=$parent;
        $history_data['Direct Object Key']=$parent_object->id;
        $history_data['Indirect Object']='Email';
        $history_data['Indirect Object Key']=$this->id;
        $this->add_history($history_data);
       
   if (mysql_affected_rows() and $parent=='Contact') {
            $emails=$parent_object->get_emails();
            foreach($emails as $email) {
                $parent_object->update_principal_email($email->id);
                break;
            }
        }



    }




    function get_customer_keys() {
        $keys=array();

        $sql=sprintf("select `Subject Key`,`Subject Type` from `Email Bridge` where `Email Key`=%d  and `Subject Type`='Customer' "

                     ,$this->id);
        $result=mysql_query($sql);
        while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $keys[$row['Subject Key']]= $row['Subject Key'];
        }
        return $keys;
    }



    function get_parent_keys($type=false) {
        $where_type='';

        $keys=array();

        if ($type)  {
            if (!preg_match('/^(Contact|Company|Supplier|User|Customer)$/',$type)) {
                return $keys;
            }
            $where_type=' and `Subject Type`='.prepare_mysql($type);

        }
        $sql=sprintf("select `Subject Key`,`Subject Type` from `Email Bridge` where `Email Key`=%d  $where_type "

                     ,$this->id);
        $result=mysql_query($sql);
        while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $keys[$row['Subject Key']]= array('Subject Key'=>$row['Subject Key'],'Subject Type'=>$row['Subject Type']);

        }
        // print $sql;

        return $keys;
    }

}

?>