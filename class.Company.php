<?php
/*
  File: Company.php

  This file contains the Company Class

  About:
  Autor: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2009, Inikoo

  Version 2.0*/
include_once('class.DB_Table.php');
include_once('class.Contact.php');
include_once('class.Telecom.php');
include_once('class.Email.php');
include_once('class.Address.php');
include_once('class.HQ.php');

//include_once('Name.php');
/* class: Company
   Class to manage the *Company Dimension* table
*/
class Company extends DB_Table {


    var $candidate_companies=array();
    var $number_candidate_companies=0;
    var $last_associated_contact_key=0;
    /*
      Constructor: Company

      Initializes the class, Search/Load or Create for the data set

      Parameters:
      arg1 -    (optional) Could be the tag for the Search Options or the Company Key for a simple object key search
      arg2 -    (optional) Data used to search or create the object

      Returns:
      void

      Example:
      (start example)
      // Load data from `Company Dimension` table where  `Company Key`=3
      $key=3;
      $company = New Company($key);

      // Insert row to `Company Dimension` table
      $data=array();
      $company = New Company('new',$data);


      (end example)

    */
    function Company($arg1=false,$arg2=false) {

        $this->table_name='Company';
        $this->ignore_fields=array(
                                 'Company Key'
                                 ,'Company Total Parts Profit'
                                 ,'Company Total Parts Profit After Storing'
                                 ,'Company Total Cost'
                                 ,'Company Total Parts Sold Amount'
                                 ,'Company 1 Year Acc Parts Profit'

                             );

        if (is_numeric($arg1)) {
            $this->get_data('id',$arg1);
            return ;
        }
        if (preg_match('/^(create|new)/i',$arg1)) {
            $this->find($arg2,'create');
            return;
        }
        if (preg_match('/find/i',$arg1)) {
            $this->find($arg2,$arg1);
            return;
        }
        $this->get_data($arg1,$arg2);
        return ;

    }

    function find_fuzzy($raw_data) {

        //Timer::timing_milestone('begin  find  contact');
        $this->find_contact=new Contact("find in company fuzzy ",$raw_data);
        //Timer::timing_milestone('end find contact');
        foreach($this->find_contact->candidate as $key=>$val) {
            if (isset($this->candidate[$key]))
                $this->candidate[$key]+=$val;
            else
                $this->candidate[$key]=$val;
        }



        foreach($this->candidate as $key=>$score) {
            if ($score>5)
                continue;
            else
                unset($this->candidate[$key]);
        }

//print_r($this->candidate);
//exit;

        //addnow we have a list of  candidates, from this list make another list of companies
        $this->candidate_companies=array();
        $this->number_candidate_companies=0;
        foreach($this->candidate as $contact_key=>$score) {
            $_contact=new Contact($contact_key);

            $company_key=$_contact->data['Contact Company Key'];
            if ($company_key) {
                // print "---- $company_key\n";
                if (isset($this->candidate_companies[$company_key]))
                    $this->candidate_companies[$company_key]+=$score;
                else
                    $this->candidate_companies[$company_key]=$score;
            }
        }
//print_r($this->candidate_companies);

        if ($raw_data['Company Name']!='') {

            $max_score=80;
            $score_plus_for_match=40;




            $len=strlen($raw_data['Company Name']);
            if ($len<256) {

                $sql=sprintf("select `Company Key`,damlevlim256(UPPER(%s),UPPER(`Company Name`),$len)/$len as dist1 from `Company Dimension`   order by dist1  limit 10"
                             ,prepare_mysql($raw_data['Company Name'])

                            );
                // print "$sql\n";
                $result=mysql_query($sql);
                while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
                    if ($row['dist1']>=1)
                        break;
                    //print $row['dist1']." $max_score  po ".pow(1-  $row['dist1'] ,4  )."\n";

                    $score=$max_score*pow(1-  $row['dist1'] ,3  );
                    $extra_score=0;
                    $company_key=$row['Company Key'];

                    foreach($this->candidate as $candidate_key=>$candidate_score) {
                        $sql=sprintf("select count(*) matched from `Contact Bridge` where `Contact Key`=%d and `Subject Key`=%d  and `Subject Type`='Company' and `Is Active`='Yes'  "
                                     ,$candidate_key
                                     ,$company_key
                                    );
                        $res=mysql_query($sql);
                        $match_data=mysql_fetch_array($res);
                        if ($match_data['matched']>0) {
                            $this->candidate[$candidate_key]+=$score_plus_for_match;
                            $extra_score=$score_plus_for_match;
                        }

                    }


                    if (isset($this->candidate_companies[$company_key]))
                        $this->candidate_companies[$company_key]+=$score+$extra_score;
                    else
                        $this->candidate_companies[$company_key]=$score+$extra_score;
                }
            }


        }

        if (!empty($this->candidate_companies)) {
            arsort($this->candidate_companies);
            foreach($this->candidate_companies as $key=>$val) {
                if ($val>=200) {
                    $this->found=true;
                    $this->found_key=$key;
                    break;
                }
            }

        }

//print_r($this->candidate_companies);

        $this->number_candidate_companies=count($this->candidate_companies);

    }


    function find_complete($raw_data) {

//print_r($raw_data);

        $this->find_contact=new Contact("find in company complete ",$raw_data);

        foreach($this->find_contact->candidate as $key=>$val) {
            if (isset($this->candidate[$key]))
                $this->candidate[$key]+=$val;
            else
                $this->candidate[$key]=$val;
        }



        foreach($this->candidate as $key=>$score) {
            if ($score>5)
                continue;
            else
                unset($this->candidate[$key]);
        }


        //addnow we have a list of  candidates, from this list make another list of companies
        $this->candidate_companies=array();
        $this->number_candidate_companies=0;
        foreach($this->candidate as $contact_key=>$score) {
            $_contact=new Contact($contact_key);

            $company_key=$_contact->data['Contact Company Key'];
            if ($company_key) {
                if (isset($this->candidate_companies[$company_key]))
                    $this->candidate_companies[$company_key]+=$score;
                else
                    $this->candidate_companies[$company_key]=$score;
            }
        }
//print_r($this->candidate_companies);

        if ($raw_data['Company Name']!='') {














            $max_score=80;
            $score_plus_for_match=40;



            $companies_with_same_name=array();

            $sql=sprintf("select `Company Key` from `Company Dimension` where `Company Name`=%s   limit 50"
                         ,prepare_mysql($raw_data['Company Name'])
                        );
// print "$sql\n";
            $result=mysql_query($sql);
            while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

                $score=$max_score;
                $extra_score=0;
                $company_key=$row['Company Key'];
                $companies_with_same_name[$company_key]=$company_key;
                foreach($this->candidate as $candidate_key=>$candidate_score) {
                    $sql=sprintf("select count(*) matched from `Contact Bridge` where `Contact Key`=%d and `Subject Key`=%d  and `Subject Type`='Company' and `Is Active`='Yes'  "
                                 ,$candidate_key
                                 ,$company_key
                                );

                    //   print "$sql\n";
                    $res=mysql_query($sql);
                    $match_data=mysql_fetch_array($res);
                    if ($match_data['matched']>0) {
                        $this->candidate[$candidate_key]+=$score_plus_for_match;
                        $extra_score=$score_plus_for_match;
                    }

                }


                if (isset($this->candidate_companies[$company_key]))
                    $this->candidate_companies[$company_key]+=$score+$extra_score;
                else
                    $this->candidate_companies[$company_key]=$score+$extra_score;
            }
            if (count($companies_with_same_name)>0) {
                $sql=sprintf("select `Contact Key` from `Contact Bridge` where `Subject Type`='Company' and `Subject Key` in (%s)  ",
                             join(',',$companies_with_same_name));
                $res=mysql_query($sql);
                while ($row=mysql_fetch_assoc($res)) {
                    if (isset($this->candidate[$row['Contact Key']]))
                        $this->candidate[$row['Contact Key']]+=30;
                    else
                        $this->candidate[$row['Contact Key']]=30;

                }


            }




        }


//print_r($this->candidate_companies);

        if (!empty($this->candidate_companies)) {
            arsort($this->candidate_companies);
            foreach($this->candidate_companies as $key=>$val) {
                if ($val>=200) {
                    $this->found=true;
                    $this->found_key=$key;
                    break;
                }
            }

        }



        $this->number_candidate_companies=count($this->candidate_companies);

    }






    function find_fast($raw_data) {

        $this->find_contact=new Contact("find in company fast ",$raw_data);

        $this->found_details=array();
        $this->found=false;
        $this->found_key=false;
        $email=$raw_data['Company Main Plain Email'];

        if (!$email)
            return;

        $sql=sprintf("select E.`Email Key`,`Subject Type`,`Subject Key` from `Email Dimension` E left join `Email Bridge` B on (E.`Email Key`=B.`Email Key`)where `Email`=%s",prepare_mysql($email));

        $res=mysql_query($sql);

        while ($row=mysql_fetch_assoc($res)) {


            $this->found_details[$row['Email Key']]=array('Subject Type'=>$row['Subject Type'],'Subject Key'=>$row['Subject Key']);

            if ($row['Subject Type']=='Company') {
                $this->found=true;
                $this->found_key=$row['Subject Key'];

            }

        }
    }


    /*
      Method: find
      Find Company with similar data

      Returns:
    Key of the Compnay found, if create is found in the options string  returns the new key
    */
    function find($raw_data,$options) {
        $find_fuzzy=false;


        //print "XXX------------------> $options <-----------\n";
        $find_type='complete';
        if (preg_match('/fuzzy/i',$options)) {
            $find_type='fuzzy';
        }
        elseif (preg_match('/fast/i',$options)) {
            $find_type='fast';
        }



        //Timer::timing_milestone('start find');

        if (isset($raw_data['editor'])) {
            foreach($raw_data['editor'] as $key=>$value) {

                if (array_key_exists($key,$this->editor))
                    $this->editor[$key]=$value;

            }
        }

        $this->candidate=array();
        $this->found=false;

        $create='';
        $update='';
        if (preg_match('/create/i',$options)) {
            $create='create';
        }
        if (preg_match('/update/i',$options)) {
            $update='update';
        }

        $address_data=array('Company Address Line 1'=>'','Company Address Town'=>'','Company Address Line 2'=>'','Company Address Line 3'=>'','Company Address Postal Code'=>'','Company Address Country Name'=>'','Company Address Country Code'=>'','Company Address Country First Division'=>'','Company Address Country Second Division'=>'');



        if (preg_match('/(from|on|in|at) supplier/',$options)) {
            foreach($raw_data as $key=>$val) {
                $_key=preg_replace('/Supplier /','Company ',$key);
                $raw_data[$_key]=$val;
            }
            $parent='supplier';
        }

        elseif(preg_match('/(from|on|in|at) customer/',$options)) {
            foreach($raw_data as $key=>$val) {
                if ($key!='Customer Type') {
                    $_key=preg_replace('/Customer /','Company ',$key);
                    $raw_data[$_key]=$val;
                }
            }
            $parent='customer';
        }
        else {

            $parent='none';
        }





        foreach($raw_data as $key=>$value) {

            if (array_key_exists($key,$address_data))
                $address_data[$key]=$value;
        }

        //print_r($address_data);

        if (!isset($raw_data['Company Name']) or $raw_data['Company Name']=='') {
            $raw_data['Company Name']='';
        }
        if (!isset($raw_data['Company Main Contact Name'])) {
            $raw_data['Company Main Contact Name']='';
        }


        switch ($find_type) {
        case 'fast':
            $this->find_fast($raw_data);
            break;
        case 'complete':


            $this->find_complete($raw_data);
            break;
        case 'fuzzy':

            $this->find_fuzzy($raw_data);
            break;
        }

        if ($this->found )
            $this->get_data('id',$this->found_key);


        $contact_created=false;

//print_r($this->candidate);
//  print_r($this->candidate);

//exit('company,php');


        if ($create or $update) {

            // print "Company founded ".$this->found_key." ".$this->found." \n";

            //print "Contact founded ".$this->find_contact->found."  \n";




            if ($create and !$this->found) {

                if ($this->find_contact->found) {

                    if ($this->find_contact->data['Contact Company Key']) {
                        $this->get_data('id',$this->find_contact->data['Contact Company Key']);
                        // print_r($this->card());
                        $this->update_address($this->data['Company Main Address Key'],$address_data);
                        $this->update($raw_data);
                    } else {

                        $this->create($raw_data,$address_data,'use contact '.$this->find_contact->id);

                    }

                } else {
                    $this->new_contact=true;

                    $this->create($raw_data,$address_data,$find_type);

                }
                return;
            }


            if ($update and $this->found) {
                $new_principal_contact=0;

                if (!$this->find_contact->found) {
                    $this->find_contact=new Contact("find in company ".$this->found_key." $find_type ",$raw_data);

                }


                if (!$this->find_contact->found) {



                    $contact_new=new Contact("find in company create",$raw_data);
                    $this->create_contact_bridge($contact_new->id);
                    $contact_created=true;
                    $new_principal_contact=$contact_new->id;
                } else {//START updating customer

                    $contact_keys=$this->get_contact_keys();
                    if (array_key_exists($this->find_contact->id,$contact_keys)) {
                        $new_principal_contact=$this->find_contact->id;
                        $this->update_principal_contact($this->find_contact->id);
                        $update_data=array('Contact Name'=>$raw_data['Company Main Contact Name']);
                        if (isset($raw_data['Company Mobile']))
                            $update_data['Contact Main Plain Mobile']=$raw_data['Company Mobile'];
                        if (isset($raw_data['Company Main Plain Email'])) {
                            $update_data['Contact Main Plain Email']=$raw_data['Company Main Plain Email'];
                        }
                        $this->find_contact->update($update_data,$find_type);

                    } else {
                        // contact not associated with Company
                        if (preg_match('/steal contacts/i',$options)) {
                            $contact_companies=$this->find_contact->get_companies_keys();
                            if (count($contact_companies)==0) {

                                //$this->associate_contact($this->find_contact->id);

                            }

                        } else {
                            $this->error=true;
                            $this->msg='Contact found not associated with company';


                        }


                    }


                }//Finish updating customer

                unset($raw_data['Company Main Plain Email']);


                if ($new_principal_contact)
                    $this->update_principal_contact($new_principal_contact);

                $this->get_data('id',$this->found_key);


                $address=new Address($this->data['Company Main Address Key']);
                $address_data['editor']=$this->editor;
                foreach($address_data as $key=>$value) {
                    $key=preg_replace('/^Company /i','',$key);
                    $_address_data[$key]=$value;
                }
                //print_r($_address_data);
                $address->update($_address_data);
                //print_r($address->data);
                //exit;
                if ($contact_created) {
                    $contact_new->associate_address($address->id);
                }


                foreach($raw_data as $key=>$value) {
                    if (preg_match('/Address|Customer|Supplier|Location|Country|XHTML|Email/',$key))
                        unset($raw_data[$key]);
                }
                // print "*************************\n";
                // print_r($raw_data);




                $this->update($raw_data,$find_type);
                //		    print "shoooooooooooolddd be updated\n\n\n\n\n";



            }




        }





    }

    function get($key,$arg1=false) {
        //  print $key."xxxxxxxx";

        if (array_key_exists($key,$this->data))
            return $this->data[$key];

        switch ($key) {
        case("Name"):
            if (preg_match('/addslashes/i',$arg1))
                return addslashes ($this->data['Company Name']);
            return     $this->data['Company Name'];
            break;
        case("ID"):
        case("Formated ID"):

            return $this->get_formated_id();



            break;

        case('departments'):
            if (!isset($this->departments))
                $this->load('departments');
            return $this->departments;
            break;
        case('department'):
            if (!isset($this->departments))
                $this->load('departments');
            if (is_numeric($arg1)) {
                if (isset($this->departments[$arg1]))
                    return $this->departments[$arg1];
                else
                    return false;
            }
            if (is_string($arg1)) {
                foreach($this->departments as $department) {
                    if ($department['company department code']==$arg1)
                        return $department;
                }
                return false;
            }


        }

        $_key=ucfirst($key);
        if (isset($this->data[$_key]))
            return $this->data[$_key];
        print "Error $key not found in get from address\n";

        return false;

    }


    function get_data($tipo,$id) {
        $sql=sprintf("select * from `Company Dimension` where `Company Key`=%d",$id);
        // print $sql;
        $result=mysql_query($sql);
        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $this->id=$this->data['Company Key'];
        }
    }


    function create($raw_data,$raw_address_data=array(),$options='') {

        //print "create company ****** ".$options."\n";
        // print_r($raw_data);


        if (!isset($raw_data['Company Fiscal Name']) or $raw_data['Company Fiscal Name']=='' ) {
            $raw_data['Company Fiscal Name']=$raw_data['Company Name'];
        }


        $this->data=$this->base_data();
        foreach($raw_data as $key=>$value) {
            if (array_key_exists($key,$this->data)) {
                if (preg_match('/email/i',$key))
                    $value='';
                $this->data[$key]=_trim($value);
            }
        }

        $this->data['Company Main Contact Name' ]='';

        $extra_mobile_key=false;

        $this->data['Company File As']=$this->file_as($this->data['Company Name']);
        $telephone=$this->data['Company Main Plain Telephone'];
        $fax=$this->data['Company Main Plain FAX'];

        if ($telephone==$fax)
            $fax='';

        $this->data['Company Main XHTML FAX']='';
        $this->data['Company Main XHTML Telephone']='';
        $this->data['Company Main Plain FAX']='';
        $this->data['Company Main Plain Telephone']='';

        $keys='';
        $values='';
        foreach($this->data as $key=>$value) {
            $keys.=",`".$key."`";
            if (preg_match('/plain|old id/i',$key))
                $print_null=false;
            else
                $print_null=true;
            $values.=','.prepare_mysql($value,$print_null);

        }




        $values=preg_replace('/^,/','',$values);
        $keys=preg_replace('/^,/','',$keys);

        $sql="insert into `Company Dimension` ($keys) values ($values)";

        if (mysql_query($sql)) {
            $this->id = mysql_insert_id();
            $this->get_data('id',$this->id);

            //      print_r($this->data);
            $history_data=array(
                              'History Abstract'=>_('Company Created'),
                              'History Details'=>_trim(_('Company')." \"".$this->data['Company Name']."\"  "._('created')),
                              'Action'=>'created'
                          );
            $this->add_history($history_data);
            $this->new=true;


            if (_trim($this->data['Company Old ID'])) {
                $sql=sprintf("insert into `Company Old ID Bridge` values (%d,%s)",$this->id,prepare_mysql(_trim($this->data['Company Old ID'])));
                mysql_query($sql);
            }


            if (preg_match('/use address \d+/',$options,$match)) {

                $address=new Address(preg_replace('/[^\d]/','',$match[0]));
            } else {

                $address_data=array('Company Address Line 1'=>'','Company Address Town'=>'','Company Address Line 2'=>'','Company Address Line 3'=>'','Company Address Postal Code'=>'','Company Address Country Name'=>'','Company Address Country Code'=>'','Company Address Country First Division'=>'','Company Address Country Second Division'=>'');
                foreach($raw_address_data as $key=>$value) {
                    if (array_key_exists($key,$address_data))
                        $address_data[$key]=$value;
                }



                $address_data['editor']=$this->editor;

                // print "xxx crete company address $options";
                //print_r($address_data);

                $address=new Address("find in company ".$this->id." $options create",$address_data);
                $address->editor=$this->editor;
                //print_r($address);
            }
            $this->associate_address($address->id);


            $use_contact=0;
            //print "$options\n";

            if (preg_match('/use contact \d+/',$options,$match)) {
                $use_contact=preg_replace('/use contact /','',$match[0]);
            }

            if ($use_contact) {
                $contact=new contact($use_contact);
                $contact->editor=$this->editor;
                $this->create_contact_bridge($contact->id);

            } else {

                foreach($raw_data as $key=>$values) {
                    if (preg_match('/telephone|fax/i',$key)) {
                        $raw_data[$key]='';
                    }
                }

                $contact=new Contact("find in company fast create",$raw_data);
                if ($contact->found) {
                    $this->error=true;
                    $this->msg='contact already in system';
                    exit("error contact already in system\n");
                    return;
                }
                elseif($contact->id) {

                    $this->create_contact_bridge($contact->id);
                }



            }

            $this->last_associated_contact_key=$contact->id;



            $contact->associate_address($address->id);



            $telephone_keys=array();
            $fax_keys=array();
            $mobile_keys=array();


            if ($telephone!='' and $fax!='') {
                if ($telephone==$fax) {
                    $fax='';
                } else {
                    $_tel_data=Telecom::parse_plain_number($telephone);
                    $_fax_data=Telecom::parse_plain_number($fax);
                    if ($_tel_data['Telecom Plain Number']==$_fax_data['Telecom Plain Number'])
                        $fax='';
                }
            }


            if ($telephone!='') {
                $telephone_data=array();
                $telephone_data['editor']=$this->editor;
                $telephone_data['Telecom Raw Number']=$telephone;
                $telephone_data['Telecom Type']='Telephone';

                $telephone=new Telecom("find in company fast create country code ".$address->data['Address Country Code'],$telephone_data);
                if (!$telephone->error) {
                    //  if ($telephone->is_mobile())
                    //      $mobile_keys[]=$telephone->id;
                    // else
                    $telephone_keys[]=$telephone->id;

                }
            }


            if ($fax!='') {
                $telephone_data=array();
                $telephone_data['editor']=$this->editor;
                $telephone_data['Telecom Raw Number']=$fax;
                $telephone_data['Telecom Type']='FAX';

                $telephone=new Telecom("find in company fast create country code ".$address->data['Address Country Code'],$telephone_data);

                if (!$telephone->error) {

                    // if ($telephone->is_mobile()) {
                    //    $mobile_keys[]=$telephone->id;


                    //} else {
                    $fax_keys[]=$telephone->id;

                    //}

                }
            }






            foreach($telephone_keys as $telecom_key) {
                $address->associate_telecom($telecom_key,'Telephone');
            }

            foreach($fax_keys as $telecom_key) {
                $address->associate_telecom($telecom_key,'FAX');
            }


            foreach($mobile_keys as $telecom_key) {
                $contact->associate_mobile($telecom_key);
            }













            $this->get_data('id',$this->id);
        } else {
            exit("Error, company can not be created $sql\n");
        }

    }






    function load($key='',$args) {
        switch ($key) {

        case('Contact List'):
            $sql=sprintf("select `Contact Key`  from `Contact Bridge` where `Subject Type`='Company' and `Subject Key`=%d",$this->id);
            $res=mysql_query($sql);
            $this->contact_list=array();
            while ($row=mysql_fetch_array($res)) {
                $this->contact_list[$row['Contact Key']]=array('key'=>$row['Contact Key']);
            }

            break;
        case('Main Contact'):
            $this->contact=new Contact($this->data['Company Main Contact Key']);
            if ($this->contact->id) {
                $this->contact->load('telecoms');
                $this->contact->load('contacts');
            }

        }

    }




    /*Function: update_field_switcher
     */

    protected function update_field_switcher($field,$value,$options='') {




        switch ($field) {
        case('Company Tax Number'):
            $this->update_field($field,$value,$options);
            if ($this->updated) {
                $this->update_parents_tax_number();
            }
            break;
        case('Company Registration Number'):
            $this->update_field($field,$value,$options);
            if ($this->updated) {
                $this->update_parents_registration_number();
            }
            break;
        case('Company Main Contact Key'):
            $this->update_main_contact_name($value);
            break;
        case('Company Main Contact Name'):
        case('Company Main XHTML FAX'):
        case('Company Main XHTML Telephone'):

            break;


        case('Company Name'):
            $this->update_Company_Name($value,$options);
            break;
        case('Company Main Plain Email'):
            $contact=new Contact($this->data['Company Main Contact Key']);
            $contact->update(array('Contact Main Plain Email'),$value);


            break;


        case('Company Main Plain FAX'):
        case('Company Main Plain Telephone'):
            if ($field=='Company Main Plain Telephone')
                $type='Telephone';
            else
                $type='FAX';
            $address=new Address($this->data['Company Main Address Key']);


            $address->editor=$this->editor;
            if ($value=='') {	
			
				$telecom_key=$address->get_principal_telecom_key($type);
              
				if ($telecom_key) {
					//print $telecom_key;
                    $telecom=new Telecom($telecom_key);
                    $telecom->delete();
                    if ($telecom->deleted) {
                        $this->updated=true;
                        $this->new_value='';
                    }
					$this->updated_data['telecom_key']=$telecom->id;
                }
				
				
				
            } else {

                if ($address->get_principal_telecom_key($type)) {
                    $address->update_principal_telecom_number($value,$type);
                } else {

                    $telephone_data=array();
                    $telephone_data['editor']=$this->editor;
                    $telephone_data['Telecom Raw Number']=$value;
                    $telephone_data['Telecom Type']=$type;
                   // $telephone=new Telecom("find in company create country code ".$address->data['Address Country Code'],$telephone_data);

					$telephone=new Telecom('new',$telephone_data);
//print_r($telephone);             
					$address->associate_telecom($telephone->id,$type);
						
				}
                $this->updated=$address->updated;
                if ($this->updated) {
					$this->updated_data['telecom_key']=$address->updated_data['telecom_key'];
			          
				   $this->get_data('id',$this->id);
                    $this->new_value=$this->data['Company Main XHTML '.$type];
                }
            }
			
			
            break;

        case('Company Old ID'):
            $this->update_Company_Old_ID($value,$options);
            break;
        default:
            $base_data=$this->base_data();
            if (array_key_exists($field,$base_data)) {
                $this->update_field($field,$value,$options);
            }



        }

    }

    /*
      Function: update_address
      Update address
    */
    private function update_address($address_key,$data) {

        $address=new Address($address_key);
        if (!$address->id) {
            $this->error=true;
            $this->msg='Address to update not associated with Company';
            print $this->msg."\n";
            return;
        }





        $address_keys=$this->get_address_keys();
        if (!array_key_exists($address->id,$address_keys)) {
            $this->error=true;
            $this->msg='Address not associated with company';
            return;


        }

        $address->editor=$this->editor;
        $address->update($data);// Address Object would update the address not normal data;


    }


    /* Function:update_Company_Name
       Updates the company name

    */
    private function update_Company_Name($value,$options) {



        if ($value=='') {
            $this->new=false;
            $this->msg.=" Company name should have a value";
            $this->error=true;
            if (preg_match('/exit on errors/',$options))
                exit($this->msg);
            return false;
        }
        $old_value=$this->data['Company Name'];
        $this->data['Company Name']=$value;
        $this->data['Company File As']=$this->file_as($this->data['Company Name']);
        $sql=sprintf("update `Company Dimension` set `Company Name`=%s,`Company File As`=%s where `Company Key`=%d "
                     ,prepare_mysql($this->data['Company Name'])
                     ,prepare_mysql($this->data['Company File As'])
                     ,$this->id);
        mysql_query($sql);
        //print "\n $sql \n";
        $affected=mysql_affected_rows();

        if ($affected==-1) {
            $this->msg.=' '._('Company Name can not be updated')."\n";
            $this->error=true;
            return;
        }
        elseif($affected==0) {
            $this->msg.=' '._('Same value as the old record');

        }
        else {
            $this->msg.=' '._('Company name updated')."\n";
            $this->msg_updated=_('Company name updated');
            $this->updated=true;
            $this->new_value=$this->data['Company Name'];


            $history_data=array(
                              'History Abstract'=>_('Company Name Changed')
                                                 ,'History Details'=>_trim(_('Company name chaged').": ".$old_value." -> ".$this->data['Company Name'])
                                                                    ,'Indirect Object'=>'Name'
                          );

            $this->add_history($history_data);

            // update childen and parents

            $sql=sprintf("select `Contact Key` from `Contact Dimension` where `Contact Company Key`=%d  ",$this->id);
            $res=mysql_query($sql);
            while ($row=mysql_fetch_array($res)) {
                $contact=new Contact ($row['Contact Key']);
                $contact->editor=$this->editor;
                $contact->update(array('Contact Company Name'=>$this->data['Company Name']));
            }


            $sql=sprintf("select `Supplier Key` from `Supplier Dimension` where `Supplier Company Key`=%d  ",$this->id);
            $res=mysql_query($sql);
            while ($row=mysql_fetch_array($res)) {
                $supplier=new Supplier ($row['Supplier Key']);
                $supplier->editor=$this->editor;

                $supplier->update(array('Supplier Name'=>$this->data['Company Name']));
            }

            $sql=sprintf("select `Customer Key` from `Customer Dimension` where `Customer Company Key`=%d  ",$this->id);
            $res=mysql_query($sql);
            while ($row=mysql_fetch_array($res)) {
                $customer=new Customer ($row['Customer Key']);
                if ($customer->data['Customer Type']=='Company') {
                    $customer->editor=$this->editor;
                    $customer->update_name($this->data['Company Name']);
                    $customer->update_file_as($this->data['Company File As']);

                }
            }
            mysql_free_result($res);


            $sql=sprintf("select * from `HQ Dimension` where `HQ Company Key`=%d  ",$this->id);
            $res=mysql_query($sql);
            while ($row=mysql_fetch_array($res)) {
                $corporation=new HQ ();
                $corporation->editor=$this->editor;
                $corporation->update_name($this->data['Company Name']);

            }
            mysql_free_result($res);

        }

    }

    function get_company_old_id() {
        $old_ids=array();
        $sql=sprintf("select `Company Old ID` from `Company Old ID Bridge` where `Company Key`=%d",$this->id);
        $res=mysql_query($sql);
        while ($row=mysql_fetch_array($res)) {
            $old_ids[$row['Company Old ID']]=$row['Company Old ID'];
        }
        return $old_ids;

    }



    /* Function:update_Company_Old_ID
       Updates the company old id

    */
    private function update_Company_Old_ID($company_old_id,$options) {
        $company_old_id=_trim($company_old_id);
        if ($company_old_id=='') {
            $this->new=false;
            $this->msg.=" Company Old ID name should have a value";
            $this->error=true;
            if (preg_match('/exit on errors/',$options))
                exit($this->msg);
            return false;
        }

        $old_value=$this->data['Company Old ID'];
        $individual_ids=array();


        $individual_ids=$this->get_company_old_id();



        if (array_key_exists($company_old_id, $individual_ids)) {
            $this->msg.=' '._('Company Old ID already in record')."\n";
            $this->warning=true;
            return;
        }

        $sql=sprintf("insert into `Company Old ID Bridge` values (%d,%s)",$this->id,prepare_mysql(_trim($this->data['Company Old ID'])));
        mysql_query($sql);


        $affected=mysql_affected_rows();

        if ($affected==-1) {
            $this->msg.=' '._('Company Old ID  can not be updated')."\n";
            $this->error=true;
            return;
        }
        elseif($affected==0) {
            //$this->msg.=' '._('Same value as the old record');

        } else {
            $this->msg.=' '._('Record updated')."\n";
            $this->updated=true;



        }

    }



    function update_main_telecom($field,$telecom) {
        if ($field=='Company Main XHTML FAX') {
            $field_plain='Company Main Plain FAX';
            $field_key='Company Main FAX Key';
            $old_principal_key=$this->data['Company Main FAX Key'];
            $old_value=$this->data['Company Main XHTML FAX']." (Id:".$this->data['Company Main FAX Key'].")";
        } else {
            $field='Company Main XHTML Telephone';
            $field_plain='Company Main Plain Telephone';
            $field_key='Company Main Telephone Key';
            $old_principal_key=$this->data['Company Main Telephone Key'];
            $old_value=$this->data['Company Main XHTML Telephone']." (Id:".$this->data['Company Main Telephone Key'].")";
        }

        $sql=sprintf("update `Company Dimension` set `%s`=%s , `%s`=%d  , `%s`=%s  where `Company Key`=%d"
                     ,$field
                     ,prepare_mysql($telecom->display('html'))
                     ,$field_key
                     ,$telecom->id
                     ,$field_plain
                     ,prepare_mysql($telecom->display('plain'))
                     ,$this->id
                    );
        mysql_query($sql);

        // print $sql;

        $history_data=array(
                          'History Abstract'=>$field." "._('Changed')
                                             ,'History Details'=>$field." "._('changed')." "
                                                                .$old_value." -> ".$telecom->display('html')
                                                                ." (Id:"
                                                                .$telecom->id
                                                                .")"
                                                                ,'Indorect Object'=>$field
                      );
        $this->add_history($history_data);


    }




    function update_address_data($address_key=false) {



        if (!$address_key)
            return;
        $address=new Address($address_key);
        if (!$address->id)
            return;

        if ($address->id!=$this->data['Company Main Address Key']) {
            $old_value=$this->data['Company Main Address Key'];
            $this->data['Company Main Address Key']=$address->id;
            $this->data['Company Main Plain Address']=$address->display('plain');
            $this->data['Company Main XHTML Address']=$address->display('xhtml');

            $this->data['Company Main Country Key']=$address->data['Address Country Key'];
            $this->data['Company Main Country']=$address->data['Address Country Name'];
            $this->data['Company Main Location']=$address->display('location');


            $sql=sprintf("update `Company Dimension` set `Company Main Address Key`=%d,`Company Main Plain Address`=%s,`Company Main XHTML Address`=%s,`Company Main Country`=%s,`Company Main Location`=%s,`Company Main Country Key`=%d where `Company Key`=%d"

                         ,$this->data['Company Main Address Key']
                         ,prepare_mysql($this->data['Company Main Plain Address'])
                         ,prepare_mysql($this->data['Company Main XHTML Address'])
                         ,prepare_mysql($this->data['Company Main Country'])
                         ,prepare_mysql($this->data['Company Main Location'])
                         ,$this->data['Company Main Country Key']
                         ,$this->id
                        );
            //print "XX $address_key $sql\n";
            if (mysql_query($sql)) {

                $note=_('Address Changed');
                if ($old_value) {
                    $old_address=new Address($old_value);
                    $details=_('Company address changed from')." \"".$old_address->display('xhtml')."\" "._('to')." \"".$this->data['Company Main XHTML Address']."\"";
                } else {
                    $details=_('Company address set to')." \"".$this->data['Company Main XHTML Address']."\"";
                }

                $history_data=array(
                                  'Indirect Object'=>'Address'
                                                    ,'History Details'=>$details
                                                                       ,'History Abstract'=>$note
                              );
                $this->add_history($history_data);





            } else {
                $this->error=true;

            }



        }
        elseif($address->display('plain')!=$this->data['Company Main Plain Address']
               or $address->display('location')!=$this->data['Company Main Location']
              ) {
            $old_value=$this->data['Company Main XHTML Address'];

            // print_r($this->data);
            // print_r($address->data);
            //print $address->display('location');
            //  exit;

            $this->data['Company Main Plain Address']=$address->display('plain');
            $this->data['Company Main XHTML Address']=$address->display('xhtml');
            $this->data['Company Main Country Key']=$address->data['Address Country Key'];
            $this->data['Company Main Country']=$address->data['Address Country Name'];
            $this->data['Company Main Location']=$address->display('location');


            $sql=sprintf("update `Company Dimension` set `Company Main Plain Address`=%s,`Company Main XHTML Address`=%s,`Company Main Country`=%s,`Company Main Location`=%s,`Company Main Country Key`=%d where `Company Key`=%d"


                         ,prepare_mysql($this->data['Company Main Plain Address'])
                         ,prepare_mysql($this->data['Company Main XHTML Address'])
                         ,prepare_mysql($this->data['Company Main Country'])
                         ,prepare_mysql($this->data['Company Main Location'])
                         ,$this->data['Company Main Country Key']
                         ,$this->id
                        );
            if (mysql_query($sql)) {
                $field='Company Address';
                $note=$field.' '._('Changed');
                $details=$field.' '._('changed from')." \"".$old_value."\" "._('to')." \"".$this->data['Company Main XHTML Address']."\"";

                $history_data=array(
                                  'Indirect Object'=>'Address'
                                                    ,'History Details'=>$details
                                                                       ,'History Abstract'=>$note
                              );
                $this->add_history($history_data);




            } else {
                $this->error=true;
                exit($sql);
            }


        }

    }





    function add_webpage($page_data,$args='principal') {
        $url=$data['page url'];
        if (isset($data['page_type']) and preg_match('/internal/i',$data['page_type']))
            $email_type='Internal';
        else
            $email_type='External';
        $url_data=array(
                      'page description'=>'',
                      'page url'=>$url,
                      'page type'=>$email_type,
                      'page validated'=>0,
                      'page verified'=>0,
                  );

        if (isset($data['page description']) and $data['page description']!='')
            $url_data['page description']=$data['page description'];
        $page=new page('new',$url_data);
        if ($email->new) {

            $sql=sprintf("insert into  `Company Web Site Bridge` (`Page Key`, `Company Key`) values (%d,%d)  ",$page->id,$this->id);
            mysql_query($sql);
            if (preg_match('/principal/i',$args)) {
                $sql=sprintf("update `Company Dimension` set `Company Main XHTML Page`=%s where `Company Key`=%d",prepare_mysql($page->display('html')),$this->id);
                // print "$sql\n";
                mysql_query($sql);
            }

            $this->add_webpage=true;
        } else {
            $this->add_webpage=false;

        }

    }



    /* Method: remove_address
       Delete the address from Company

       Delete telecom record  this record to the Comp[any


       Parameter:
       $args -     string  options
    */
    function remove_address($address_key) {



        if (!$address_key) {
            $address_key=$this->data['Company Main Address Key'];
        }


        $address=new address($address_key);
        if (!$address->id) {
            $this->error=true;
            $this->msg='Wrong address key when trying to remove it';
            $this->msg_updated='Wrong address key when trying to remove it';
        }

        $address_keys=$this->get_address_keys();

        if (in_array($address->id,$address_keys)) {
            $address->delete();

        }
    }


    /* Method: remove_email
       Delete the email from Company

       Delete telecom record  this record to the Comp[any


       Parameter:
       $args -     string  options
    */

    /* Method: add_tel
       Add/Update an telecom to the Company

       Search for an telecom record maching the telecom data *$data* if not found create a ne telecom record then add this record to the Contact


       Parameter:
       $data  -    array   telecom data
       $args -     string  options
       Return:
       integer telecom key of the added/updated telecom
    */




    /* Method: associate_address
       Associate an address to the Company


    */

    function associate_address($address_key) {
        if (!$address_key)
            return;
        $address_keys=$this->get_address_keys();

        if (!array_key_exists($address_key,$address_keys)) {
            $this->create_address_bridge($address_key);
            $this->updated=true;
            $this->new_data=$address_key;



        }


    }

    function get_principal_address_key() {

        $sql=sprintf("select `Address Key` from `Address Bridge` where `Subject Type`='Company' and `Subject Key`=%d and `Is Main`='Yes'",$this->id );
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            $main_address_key=$row['Address Key'];
        } else {
            $main_address_key=0;
        }

        return $main_address_key;
    }
    function create_address_bridge($address_key) {
        $sql=sprintf("insert into `Address Bridge` (`Subject Type`,`Subject Key`,`Address Key`) values ('Company',%d,%d)  ",
                     $this->id,
                     $address_key

                    );
        mysql_query($sql);
        if (!$this->get_principal_address_key()) {
            $this->update_principal_address($address_key);
        }

    }

    function update_principal_address($address_key) {
        $main_address_key=$this->get_principal_address_key();

        if ($main_address_key!=$address_key) {
            $address=new Address($address_key);
            $address->editor=$this->editor;
            $address->new=$this->new;

            $sql=sprintf("update `Address Bridge`  set `Is Main`='No' where `Subject Type`='Company' and  `Subject Key`=%d  and `Address Key`!=%d",
                         $this->id
                         ,$address_key
                        );
            mysql_query($sql);
            $sql=sprintf("update `Address Bridge`  set `Is Main`='Yes' where `Subject Type`='Company' and  `Subject Key`=%d  and `Address Key`=%d",
                         $this->id
                         ,$address_key
                        );
            mysql_query($sql);

            $sql=sprintf("update `Company Dimension` set  `Company Main Address Key`=%s where `Company Key`=%d",$address->id,$this->id);


            $sql=sprintf("update `Company Dimension` set `Company Main XHTML Address`=%s , `Company Main Plain Address`=%s , `Company Main Address Key`=%d ,`Company Main Country Key`=%d ,`Company Main Country`=%s,`Company Main Location`=%s  where `Company Key`=%d"
                         ,prepare_mysql($address->display('xhtml'))
                         ,prepare_mysql($address->display('plain'))
                         ,$address->id
                         ,$address->data['Address Country Key']
                         ,prepare_mysql($address->data['Address Country Name'])
                         ,prepare_mysql($address->display('location'))
                         ,$this->id
                        );
            //	print $sql;
            mysql_query($sql);

            $this->data['Company Main XHTML Address']=$address->display('xhtml');
            $this->data['Company Main Plain Address']=$address->display('plain');
            $this->data['Company Main Address Key']=$address->id;
            $this->data['Company Main Country Key']=$address->data['Address Country Key'];
            $this->data['Company Main Country']=$address->data['Address Country Name'];
            $this->data['Company Main Location']=$address->display('location');



            $this->update_parents_principal_address_keys($address_key);

            $contacts=$this->get_contact_keys();
            foreach($contacts as $contact_key) {
                $contact=new Contact($contact_key);
                $contact->editor=$this->editor;
                $contact->new=$this->new;
                if ($contact->data['Contact Main Address Key']==$main_address_key) {
                    $contact->update_principal_address($address_key);
                }
            }


            //print "upa\n";
            //print_r($this->editor);


            $address->update_parents();
            //print "end upa\n";
            $this->updated=true;
            $this->new_value=$address_key;
        }

    }

    function get_principal_contact_key() {

        $sql=sprintf("select `Contact Key` from `Contact Bridge` where `Subject Type`='Company' and `Subject Key`=%d and `Is Main`='Yes'",$this->id );
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            $main_contact_key=$row['Contact Key'];
        } else {
            $main_contact_key=0;
        }

        return $main_contact_key;
    }
    function create_contact_bridge($contact_key) {
        $sql=sprintf("insert into  `Contact Bridge` (`Contact Key`, `Subject Type`,`Subject Key`,`Is Main`) values (%d,'Company',%d,'No')  "
                     ,$contact_key
                     ,$this->id

                    );
        mysql_query($sql);
        if (!$this->get_principal_contact_key()) {
            $this->update_principal_contact($contact_key);
        }

        $sql=sprintf("insert into  `Company Bridge` (`Company Key`, `Subject Type`,`Subject Key`,`Is Main`) values (%d,'Contact',%d,'No')  "
                     ,$this->id
                     ,$contact_key

                    );
        mysql_query($sql);
        $contact=new Contact($contact_key);
        $contact->editor=$this->editor;
        if (!$contact->get_principal_company_key()) {
            $contact->update_principal_company($this->id);
        }

    }

    function update_principal_contact($contact_key) {
        $main_contact_key=$this->get_principal_contact_key();

        if ($main_contact_key!=$contact_key) {
            $contact=new Contact($contact_key);
            $contact->editor=$this->editor;
            $sql=sprintf("update `Contact Bridge`  set `Is Main`='No' where `Subject Type`='Company' and  `Subject Key`=%d ",
                         $this->id
                        );
            mysql_query($sql);
            $sql=sprintf("INSERT INTO  `Contact Bridge`  (`Contact Key`,`Subject Type`,`Subject Key`,`Is Main`,`Is Active`)
                         value (%d,'Company',%d,'Yes','Yes')   ON DUPLICATE KEY UPDATE
                         `Is Main`='Yes' ,`Is Active`='Yes'",
                         $contact_key,
                         $this->id
                        );
            mysql_query($sql);

            $sql=sprintf("update `Company Dimension` set  `Company Main Contact Key`=%d where `Company Key`=%d",$contact->id,$this->id);
            mysql_query($sql);


            $this->data['Company Main Contact Key']=$contact->id;
            $this->update_parents_principal_contact_keys($contact_key);
            $contact->new=$contact->new;
            $contact->update_parents();
            $contact->update_parents_principal_email_keys();
            $email=new Email($contact->get_principal_email_key());
            $email->editor=$this->editor;
            $email->new=$this->new;
            if ($email->id)
                $email->update_parents();


            $this->last_associated_contact_key=$contact_key;

        }

    }


    function update_parents_principal_contact_keys($contact_key) {
        $parents=array('Customer','Supplier');
        foreach($parents as $parent) {
            $sql=sprintf("select `$parent Key` as `Parent Key`   from  `$parent Dimension` where `$parent Company Key`=%d group by `$parent Key`",$this->id);

            $res=mysql_query($sql);
            while ($row=mysql_fetch_array($res)) {


                if ($parent=='Customer') {
                    $parent_object=new Customer($row['Parent Key']);
                    $parent_label=_('Customer');
                }
                elseif($parent=='Supplier') {
                    $parent_object=new Supplier($row['Parent Key']);
                    $parent_label=_('Supplier');
                }
                $parent_object->editor=$this->editor;
                $parent_object->new=$this->new;

                $old_principal_name_key=$parent_object->data[$parent.' Main Contact Key'];
                if ($old_principal_name_key!=$contact_key) {



                    $sql=sprintf("update `Contact Bridge`  set `Is Main`='No' where `Subject Type`='$parent' and  `Subject Key`=%d ",
                                 $parent_object->id
                                );
                    mysql_query($sql);
                    $sql=sprintf("INSERT INTO  `Contact Bridge`  (`Contact Key`,`Subject Type`,`Subject Key`,`Is Main`,`Is Active`)
                                 value (%d,'$parent',%d,'Yes','Yes')   ON DUPLICATE KEY UPDATE
                                 `Is Main`='Yes',`Is Active`='Yes' ",
                                 $contact_key,
                                 $parent_object->id
                                );
                    mysql_query($sql);






                    $sql=sprintf("update `$parent Dimension` set `$parent Main Contact Key`=%d where `$parent Key`=%d"
                                 ,$contact_key
                                 ,$parent_object->id
                                );
                    mysql_query($sql);




                }
            }
        }
    }


    function update_parents_principal_address_keys($address_key) {
        $parents=array('Customer','Supplier');
        foreach($parents as $parent) {
            $sql=sprintf("select `$parent Key` as `Parent Key`   from  `$parent Dimension` where `$parent Company Key`=%d group by `$parent Key`",$this->id);
            $res=mysql_query($sql);
            while ($row=mysql_fetch_array($res)) {


                if ($parent=='Customer') {
                    $parent_object=new Customer($row['Parent Key']);
                    $parent_label=_('Customer');
                }
                elseif($parent=='Supplier') {
                    $parent_object=new Supplier($row['Parent Key']);
                    $parent_label=_('Supplier');
                }
                $parent_object->editor=$this->editor;
                $parent_object->new=$this->new;
                $old_principal_name_key=$parent_object->data[$parent.' Main Address Key'];
                if ($old_principal_name_key!=$address_key) {

                    $sql=sprintf("update `Address Bridge`  set `Is Main`='No' where `Subject Type`='$parent' and  `Subject Key`=%d  and `Address Key`=%d",
                                 $parent_object->id
                                 ,$address_key
                                );
                    mysql_query($sql);
                    $sql=sprintf("update `Address Bridge`  set `Is Main`='Yes' where `Subject Type`='$parent' and  `Subject Key`=%d  and `Address Key`=%d",
                                 $parent_object->id
                                 ,$address_key
                                );
                    mysql_query($sql);
                    $sql=sprintf("update `$parent Dimension` set `$parent Main Address Key`=%d where `$parent Key`=%d"
                                 ,$address_key
                                 ,$parent_object->id
                                );
                    mysql_query($sql);

                    $parent_object->get_data('id',$parent_object->id);

                    if ($parent=='Customer') {
                        if ($parent_object->data['Customer Delivery Address Link']=='Contact') {
                            $parent_object->update_principal_delivery_address($address_key);

                        }
                        if ($parent_object->data['Customer Billing Address Link']=='Contact') {
                            $parent_object->update_principal_billing_address($address_key);

                        }

                    }


                }




            }
        }
    }





    function remove_contact($data,$args='') {
        // print "removing contact  to ".$this->id."  ($args)";
        //print_r($data);
        if (is_numeric($data))
            $contact=new Contact('id',$data);
        else
            $contact=new Contact('find in company create',$data);

        if (!$contact->id) {
            $this->error=true;
            $this->msg="can not find/create contact";

        }

        $contacts_keys=$this->get_contact_keys('only active');


        if (!in_array($contact->id,$contacts_keys)) {
            $this->msg_updated.=_('Can not remove contact because it in not associated with the company').".";
            return;
        }

        $contact->set_scope('Company',$this->id);

        // print "Main ".$contact->data['Contact Is Main']."\n";

        if ($contact->data['Contact Is Main']=='Yes') {
            if (count($contacts_keys)==1) {
                $fuzzy_contact=new Contact('create anonymous');
                $fuzzy_contact->editor=$this->editor;
                $fuzzy_contact->add_company(array('Company Key'=>$this->id));
                $fuzzy_contact->add_address(array(
                                                'Address Key'=>$this->data['Company Main Address Key']
                                                              ,'Address Type'=>array('Work')
                                                                              ,'Address Function'=>array('Contact')
                                            ));
                if ($this->data['Company Main Telephone Key']) {
                    $fuzzy_contact->add_tel(array(
                                                'Telecom Key'=>$this->data['Company Main Telephone Key']
                                                              ,'Telecom Type'=>'Office Telephone'
                                            ));
                }
                if ($this->data['Company Main FAX Key']) {
                    $fuzzy_contact->add_tel(array(
                                                'Telecom Key'=>$this->data['Company Main FAX Key']
                                                              ,'Telecom Type'=>'Office Fax'
                                            ));
                }

                $customers_keys= $contact->get_customer_keys();
                foreach($customers_keys as $customer_key) {
                    $customer=new Customer($customer_key);
                    if ($customer->data['Customer Main Contact Key']==$contact->id) {
                        $customer->update_main_contact_key($fuzzy_contact->id);
                    }


                }


            } else {
                $this->error=true;
                $msg=_('can not remove main contact please set another contact as the main one first').".";
                $this->msg.=$msg;
                $this->msg_updated.=$msg;
            }


        }

        if (preg_match('/(remove|delete) from (db|database)/i',$args)) {

            $sql=sprintf("delete from  `Contact Bridge` where `Contact Key`=%s and  `Subject Type`='Company' and `Subject Key`=%d "
                         ,$contact->id
                         ,$this->id
                        );
            mysql_query($sql);

            $history_data=array(
                              'History Abstract'=>_('Company-Contact Relation deleted permanently')
                                                 ,'History Details'=>_trim(_('Company')." ".$this->data['Company Name'].' ('.$this->get_formated_id().') '._('relation with contact')." ".$contact->display('name')." (".$contact->get_formated_id().") "._('has been deleted permenentely') )
                                                                    ,'Action'=>'deleted'
                          );
            $this->add_history($history_data);


        } else {
            $sql=sprintf("update  `Contact Bridge` set `Is Active`='No' where `Contact Key`=%s and  `Subject Type`='Company' and `Subject Key`=%d "
                         ,$contact->id
                         ,$this->id
                        );

            mysql_query($sql);
            $history_data=array(
                              'History Abstract'=>_('Company-Contact Relation disassociated')
                                                 ,'History Details'=>_trim(_('Company')." ".$this->data['Company Name'].' ('.$this->get_formated_id().') '._('relation with contact')." ".$contact->display('name')." (".$contact->get_formated_id().") "._('has been disassociate') )
                                                                    ,'Action'=>'disassociate'
                          );
            $this->add_history($history_data);
        }



    }



    function create_code($name) {
        preg_replace('/[!a-z]/i','',$name);
        preg_replace('/^(the|el|la|les|los|a)\s+/i','',$name);
        preg_replace('/\s+(plc|inc|co|ltd)$/i','',$name);
        preg_split('/\s*/',$name);
        return $name;
    }

    function check_code($name) {
        return $name;
    }

    /*
      Function: file_as
      Parse company name to be order nicely


    */


    function file_as($name) {
        $articles_regex='/^(the|el|la|les|los|a)\s+/i';
        if (preg_match($articles_regex,$name,$match)) {
            $name=preg_replace($articles_regex,'',$name);
            $article=_trim($match[0]);
            $name.=' '.$article;
        }
        $no_standar_characters_regex='/^[^a-zA-Z0-9\!\?]*/i';
        $name=preg_replace($no_standar_characters_regex,'',$name);


        return $name;
    }

    /*
      function: card
      Returns an array with the contact details
    */
    function card() {


        $card=array(
                  'Company Name'=>$this->data['Company Name']
                                 ,'Contacts'=>array()
              );

        $sql=sprintf("select`Contact Key`,`Is Main`  from `Contact Bridge` DB where `Subject Type`='Contact' and `Subject Key`=%d order by `Is Main` desc",$this->id);
        $result=mysql_query($sql);
        while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $contact=new Contact($row['Contact Key']);
            $card['Contacts'][$row['Contact Key']]=$contact->card();
        }
        return $card;
    }
	
    function get_work_telephones($company_key=false) {
        $telephones=array();
        $in_company='';
        if ($company_key)
            $in_company=sprintf(" and `Auxiliary Key`=%s",$company_key);
        $sql=sprintf('select * from `Telecom Bridge` TB  left join `Telecom Dimension` T on T.`Telecom Key`=TB.`Telecom Key`  where `Subject Key`=%d and `Telecom Type`="Work Telephone"  and `Subject Type`="Contact" %s order by `Is Main` desc ',$this->id,$in_company);
        $res=mysql_query($sql);
        while ($row=mysql_fetch_array($res)) {
            $tel=new Telecom('id',$row['Telecom Key']);

            $telephones[]=array(
                              'id'=>$row['Telecom Key']
                                   ,'type'=>$row['Telecom Type']
                                           ,'country_code'=>$row['Telecom Country Telephone Code']
                                                           ,'national_access_code'=>$row['Telecom National Access Code']
                                                                                   ,'area_code'=>$row['Telecom Area Code']
                                                                                                ,'number'=>$row['Telecom Number']
                                                                                                          ,'extension'=>$row['Telecom Extension']
                                                                                                                       ,'formated_number'=>$tel->display('formated')

                          );
        }
        return $telephones;
    }

	function get_telephones(){
		$sql=sprintf("select TB.`Telecom Key`,`Is Main` from `Telecom Bridge` TB   left join `Telecom Dimension` T on (T.`Telecom Key`=TB.`Telecom Key`) where `Telecom Type`='Telephone'    and `Subject Type`='Company' and `Subject Key`=%d  group by TB.`Telecom Key` order by `Is Main`   ",$this->id);
        $mobiles=array();
        $result=mysql_query($sql);
		//print $sql;
        while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $mobile= new Telecom($row['Telecom Key']);
            $mobile->set_scope('Contact',$this->id);
            $mobiles[]= $mobile;
            $mobile->data['Mobile Is Main']=$row['Is Main'];

        }
        //$this->number_mobiles=count($mobiles);
        return $mobiles;
	}
	
    function update_principal_telephone($telephone_key) {


        $main_telephone_key=$this->get_principal_telephone_key();

        if ($main_telephone_key!=$telephone_key) {
            $telephone=new Telecom($telephone_key);
            $telephone->editor=$this->editor;
            $sql=sprintf("update `Telecom Bridge`  B left join `Telecom Dimension` T on (T.`Telecom Key`=B.`Telecom Key`)  set `Is Main`='No' where `Subject Type`='Contact'   and `Subject Key`=%d  and `Telecom Key`=%d and `Telecom Type`='Telephone'  "
                         ,$this->id
                         ,$main_telephone_key
                        );
            mysql_query($sql);

            $sql=sprintf("update `Telecom Bridge`  set `Is Main`='Yes' where `Subject Type`='Contact'  and  `Subject Key`=%d  and `Telecom Key`=%d"
                         ,$this->id
                         ,$telephone->id
                        );
            mysql_query($sql);


            $sql=sprintf("update `Contact Dimension` set  `Contact Main Telephone Key`=%d where `Contact Key`=%d",$telephone->id,$this->id);
            $this->data['Contact Main Telephone Key']=$telephone->id;
            mysql_query($sql);
            $this->updated=true;
            $this->new_value=$telephone->display('xhtml');

            $this->update_parents_principal_telephone_keys();
            $telephone->new=$this->new;
            $telephone->update_parents();


        }

    }
    function update_parents_principal_telephone_keys() {
        $telephone_key=$this->data['Contact Main Telephone Key'];
        if (!$telephone_key)
            return;
        //$parents=array('Company','Customer','Supplier');
        $parents=array('Customer');
        foreach($parents as $parent) {
            $sql=sprintf("select `$parent Key` as `Parent Key`   from  `$parent Dimension` where `$parent Main Contact Key`=%d group by `$parent Key`",$this->id);
            //   print "$sql\n";

            $res=mysql_query($sql);
            while ($row=mysql_fetch_array($res)) {

                if ($parent=='Customer') {
                    $parent_object=new Customer($row['Parent Key']);
                    $parent_label=_('Customer');
                }
                /*
                elseif($parent=='Supplier') {
                    $parent_object=new Supplier($row['Parent Key']);
                    $parent_label=_('Supplier');
                }
                elseif($parent=='Company') {
                    $parent_object=new Company($row['Parent Key']);
                    $parent_label=_('Company');
                }
                */
                $parent_telecoms=$parent_object->get_telecom_keys();

                if (!array_key_exists($telephone_key,$parent_telecoms)) {
                    $sql=sprintf("insert into  `Telecom Bridge` (`Telecom Key`,`Subject Type`, `Subject Key`,`Is Main`,`Is Active`) values (%d,'$parent',%d,'No','Yes')  "
                                 ,$telephone_key
                                 ,$parent_object->id
                                );
                    mysql_query($sql);
                }
                //print "$sql\n";

                $old_principal_mobile_key=$parent_object->data[$parent.' Main Telephone Key'];
                if ($old_principal_mobile_key!=$telephone_key) {

                    $sql=sprintf("update `Telecom Bridge`  B left join `Telecom Dimension` T on (T.`Telecom Key`=B.`Telecom Key`)  set `Is Main`='No' where `Subject Type`='$parent' and  `Subject Key`=%d and `Telecom Type`='Telephone'  ",
                                 $parent_object->id

                                );
                    mysql_query($sql);
                    $sql=sprintf("update `Telecom Bridge`  set `Is Main`='Yes' where `Subject Type`='$parent' and  `Subject Key`=%d  and `Telecom Key`=%d",
                                 $parent_object->id
                                 ,$telephone_key
                                );
                    mysql_query($sql);
                    $sql=sprintf("update `$parent Dimension` set `$parent Main Telephone Key`=%d where `$parent Key`=%d"
                                 ,$telephone_key
                                 ,$parent_object->id
                                );
                    mysql_query($sql);
//print "$sql\n";



                }
            }
        }
    }

    function update_parents_principal_fax_keys() {
        $fax_key=$this->data['Contact Main Fax Key'];
        if (!$fax_key)
            return;
        //$parents=array('Company','Customer','Supplier');
        $parents=array('Customer');
        foreach($parents as $parent) {
            $sql=sprintf("select `$parent Key` as `Parent Key`   from  `$parent Dimension` where `$parent Main Contact Key`=%d group by `$parent Key`",$this->id);
            //   print "$sql\n";

            $res=mysql_query($sql);
            while ($row=mysql_fetch_array($res)) {

                if ($parent=='Customer') {
                    $parent_object=new Customer($row['Parent Key']);
                    $parent_label=_('Customer');
                }
                /*
                elseif($parent=='Supplier') {
                    $parent_object=new Supplier($row['Parent Key']);
                    $parent_label=_('Supplier');
                }
                elseif($parent=='Company') {
                    $parent_object=new Company($row['Parent Key']);
                    $parent_label=_('Company');
                }
                */
                $parent_telecoms=$parent_object->get_telecom_keys();

                if (!array_key_exists($fax_key,$parent_telecoms)) {
                    $sql=sprintf("insert into  `Telecom Bridge` (`Telecom Key`,`Subject Type`, `Subject Key`,`Is Main`,`Is Active`) values (%d,'$parent',%d,'No','Yes')  "
                                 ,$fax_key
                                 ,$parent_object->id
                                );
                    mysql_query($sql);
                }
                //print "$sql\n";

                $old_principal_mobile_key=$parent_object->data[$parent.' Main FAX Key'];
                if ($old_principal_mobile_key!=$fax_key) {

                    $sql=sprintf("update `Telecom Bridge`  B left join `Telecom Dimension` T on (T.`Telecom Key`=B.`Telecom Key`)  set `Is Main`='No' where `Subject Type`='$parent' and  `Subject Key`=%d and `Telecom Type`='Fax'  ",
                                 $parent_object->id

                                );
                    mysql_query($sql);
                    $sql=sprintf("update `Telecom Bridge`  set `Is Main`='Yes' where `Subject Type`='$parent' and  `Subject Key`=%d  and `Telecom Key`=%d",
                                 $parent_object->id
                                 ,$fax_key
                                );
                    mysql_query($sql);
                    $sql=sprintf("update `$parent Dimension` set `$parent Main FAX Key`=%d where `$parent Key`=%d"
                                 ,$fax_key
                                 ,$parent_object->id
                                );
                    mysql_query($sql);
//print "$sql\n";



                }
            }
        }
    }	
	function get_faxes(){
		$sql=sprintf("select TB.`Telecom Key`,`Is Main` from `Telecom Bridge` TB   left join `Telecom Dimension` T on (T.`Telecom Key`=TB.`Telecom Key`) where `Telecom Type`='Fax'    and `Subject Type`='Contact' and `Subject Key`=%d  group by TB.`Telecom Key` order by `Is Main`   ",$this->id);
        $telephones=array();
        $result=mysql_query($sql);
		//print $sql;
        while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $telephone= new Telecom($row['Telecom Key']);
            $telephone->set_scope('Contact',$this->id);
            $telephones[]= $telephone;
            $telephone->data['Mobile Is Main']=$row['Is Main'];

        }
        //$this->number_mobiles=count($mobiles);
        return $telephones;
	}
	function get_principal_telephone_key() {

        $sql=sprintf("select TB.`Telecom Key` from `Telecom Bridge`   TB left join `Telecom Dimension` T on (T.`Telecom Key`=TB.`Telecom Key`)  where  `Telecom Type`='Telephone'  and   `Subject Type`='Contact' and `Subject Key`=%d and `Is Main`='Yes'"
                     ,$this->id );

        //print "$sql\n";

        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            $main_telephone_key=$row['Telecom Key'];
        } else {
            $main_telephone_key=0;
        }

        return $main_telephone_key;
    }

	function get_principal_fax_key() {

        $sql=sprintf("select TB.`Telecom Key` from `Telecom Bridge`   TB left join `Telecom Dimension` T on (T.`Telecom Key`=TB.`Telecom Key`)  where  `Telecom Type`='Fax'  and   `Subject Type`='Contact' and `Subject Key`=%d and `Is Main`='Yes'"
                     ,$this->id );

        //print "$sql\n";

        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            $main_fax_key=$row['Telecom Key'];
        } else {
            $main_fax_key=0;
        }

        return $main_fax_key;
    }	
    function update_principal_faxes($fax_key) {


        $main_fax_key=$this->get_principal_fax_key();

        if ($main_fax_key!=$fax_key) {
            $fax=new Telecom($fax_key);
            $fax->editor=$this->editor;
            $sql=sprintf("update `Telecom Bridge`  B left join `Telecom Dimension` T on (T.`Telecom Key`=B.`Telecom Key`)  set `Is Main`='No' where `Subject Type`='Contact'   and `Subject Key`=%d  and `Telecom Key`=%d and `Telecom Type`='Fax'  "
                         ,$this->id
                         ,$main_fax_key
                        );
            mysql_query($sql);

            $sql=sprintf("update `Telecom Bridge`  set `Is Main`='Yes' where `Subject Type`='Contact'  and  `Subject Key`=%d  and `Telecom Key`=%d"
                         ,$this->id
                         ,$fax->id
                        );
            mysql_query($sql);


            $sql=sprintf("update `Contact Dimension` set  `Contact Main Fax Key`=%d where `Contact Key`=%d",$fax->id,$this->id);
            $this->data['Contact Main Fax Key']=$fax->id;
            mysql_query($sql);
            $this->updated=true;
            $this->new_value=$fax->display('xhtml');

            $this->update_parents_principal_fax_keys();
            $fax->new=$this->new;
            $fax->update_parents();


        }

    }	
    function get_customer_keys($args='') {
        $sql=sprintf("select `Subject Key` as `Customer Key` from `Company Bridge` where `Subject Type`='Customer' and `Company Key`=%d  ",$this->id);

        $customer_keys=array();
        $result=mysql_query($sql);
        while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $customer_keys[$row['Customer Key']]= $row['Customer Key'];

        }
        return $customer_keys;
    }



    function get_email_keys() {
        $sql=sprintf("select `Email Key` from `Email Bridge` where  `Subject Type`='Company' and `Subject Key`=%d "
                     ,$this->id );

        $emails=array();
        $result=mysql_query($sql);
        while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $emails[$row['Email Key']]= $row['Email Key'];
        }
        return $emails;

    }

    /*
      function: get_contact_keys
      Returns the Contact Key if the company is one
    */
    function get_contact_keys($args='active only') {
        $extra_args='';
        if (preg_match('/only active|active only/i',$args))
            $extra_args=" and `Is Active`='Yes'";
        if (preg_match('/only main|main only/i',$args))
            $extra_args=" and `Is Main`='Yes'";
        if (preg_match('/only not? active/i',$args))
            $extra_args=" and `Is Active`='No'";
        if (preg_match('/only not? main/i',$args))
            $extra_args=" and `Is Main`='No'";

        $sql=sprintf("select * from `Contact Bridge` where  `Subject Type`='Company' and `Subject Key`=%d %s order by `Is Main` desc  "
                     ,$this->id
                     ,$extra_args
                    );
        $contacts=array();
        $result=mysql_query($sql);
        while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $contacts[$row['Contact Key']]= $row['Contact Key'];
        }
        return $contacts;
    }


    function get_contacts($args='only active') {

        $extra_args='';
        if (preg_match('/only active|active only/i',$args))
            $extra_args=" and `Is Active`='Yes'";
        if (preg_match('/only main|main only/i',$args))
            $extra_args=" and `Is Main`='Yes'";
        if (preg_match('/only not? active/i',$args))
            $extra_args=" and `Is Active`='No'";
        if (preg_match('/only not? main/i',$args))
            $extra_args=" and `Is Main`='No'";





        $sql=sprintf("select CB.`Contact Key` from `Contact Bridge` CB left join `Contact Dimension` C on (CB.`Contact Key`=C.`Contact Key`)
                     where  `Subject Type`='Company' and `Subject Key`=%d %s order by `Is Main`, `Contact File As`  ",$this->id,$extra_args);


        $contacts=array();
        $result=mysql_query($sql);
        while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $contact=new Contact($row['Contact Key']);
            $contact->set_scope('Company',$this->id);
            $contacts[]=$contact;

        }
        return $contacts;
    }



    function get_address_keys() {


        $sql=sprintf("select * from `Address Bridge` CB where   `Subject Type`='Company' and `Subject Key`=%d  group by `Address Key` order by `Is Main` desc  ",$this->id);
        $address_keys=array();
        $result=mysql_query($sql);

        while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $address_keys[$row['Address Key']]= $row['Address Key'];
        }
        return $address_keys;

    }




    function get_telecom_keys($type=false) {

        $where_type='';


        if ($type) {
            $where_type=sprintf('and `Telecom Type`=%s',prepare_mysql($type));

        }

        $sql=sprintf("select TB.`Telecom Key` from `Telecom Bridge` TB   left join `Telecom Dimension` T on (T.`Telecom Key`=TB.`Telecom Key`)  where     `Subject Type`='Company' and `Subject Key`=%d  $where_type group by `Telecom Key` order by `Is Main` desc  "

                     ,$this->id);
        $address_keys=array();
        $result=mysql_query($sql);

        while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $address_keys[$row['Telecom Key']]= $row['Telecom Key'];
        }
        return $address_keys;

    }


    function get_addresses() {


        $sql=sprintf("select * from `Address Bridge` CB where   `Subject Type`='Company' and `Subject Key`=%d  group by `Address Key` order by `Is Main` desc  ",$this->id);
        $addresses=array();
        $result=mysql_query($sql);

        while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $address= new Address($row['Address Key']);
            $address->set_scope('Company',$this->id);
            $addresses[]= $address;
        }
        return $addresses;

    }

    /*function:get_formated_id_link
      Returns formated id_link
    */
    function get_formated_id_link() {

        return sprintf('<a href="company.php?id=%d">%s</a>',$this->id, $this->get_formated_id());

    }


    /*function:get_formated_id
      Returns formated id
    */
    function get_formated_id() {
        global $myconf;
        $sql="select count(*) as num from `Company Dimension`";
        $res=mysql_query($sql);
        $min_number_zeros=$myconf['company_min_number_zeros_id'];
        if ($row=mysql_fetch_array($res)) {
            if (strlen($row['num'])-1>$min_number_zeros)
                $min_number_zeros=strlen($row['num'])-01;
        }
        if (!is_numeric($min_number_zeros))
            $min_number_zeros=4;

        return sprintf("%s%0".$min_number_zeros."d",$myconf['company_id_prefix'], $this->id);

    }

    function get_name() {
        return $this->data['Company Name'];
    }

    function get_main_email_key() {
        return $this->data['Company Main Email Key'];
    }
    function get_main_address_key() {
        return $this->data['Company Main Address Key'];
    }


    function update_main_contact_name($contact_key) {

        if (!$contact_key or !is_numeric($contact_key)) {
            $this->error=true;
            return;
        }

        $contact=new Contact($contact_key);
        if (!$contact->id) {
            $this->error=true;
            return;
        }
        // print_r($contact);
        $this->data['Company Main Contact Name']=$contact->display('Name');
        $sql=sprintf("update `Company Dimension` set `Company Main Contact Name`=%s where `Company Key`=%d",prepare_mysql($this->data['Company Main Contact Name']),$this->id);
        //print "$sql\n";
        mysql_query($sql);



    }

    function display($tipo='card') {

        global $myconf;

        switch ($tipo) {
        case('card'):


            $email_label="E:";
            $tel_label="T:";
            $fax_label="F:";
            $mobile_label="M:";
            $contact_label="C:";

            $email='';
            $tel='';
            $fax='';
            $mobile='';
            $contact='';
            $name=sprintf('<span class="name">%s</span>',$this->data['Company Name']);
            if ($this->data['Company Main Contact Name'])
                $contact=sprintf('<span class="name">%s %s</span><br/>',$contact_label,$this->data['Company Main Contact Name']);


            if ($this->data['Company Main XHTML Email'])
                $email=sprintf('<span class="email">%s</span><br/>',$this->data['Company Main XHTML Email']);
            if ($this->data['Company Main XHTML Telephone'])
                $tel=sprintf('<span class="tel">%s %s</span><br/>',$tel_label,$this->data['Company Main XHTML Telephone']);
            if ($this->data['Company Main XHTML FAX'])
                $fax=sprintf('<span class="fax">%s %s</span><br/>',$fax_label,$this->data['Company Main XHTML FAX']);


            $address=sprintf('<span class="mobile">%s</span>',$this->data['Company Main XHTML Address']);

            $card=sprintf('<div class="contact_card">%s <div  class="tels">%s %s %s %s</div><div  class="address">%s</div> </div>'
                          ,$name
                          ,$contact
                          ,$email
                          ,$tel
                          ,$fax

                          ,$address
                         );

            return $card;

        }

    }
    /*

     */



    function set_scope($raw_scope='',$scope_key=0) {
        $scope='Unknown';
        $raw_scope=_trim($raw_scope);
        if (preg_match('/^customers?$/i',$raw_scope)) {
            $scope='Customer';

        } else if (preg_match('/^(supplier)$/i',$raw_scope)) {
            $scope='Supplier';
        }

        $this->scope=$scope;
        $this->scope_key=$scope_key;
        $this->load_metadata();

    }
    function load_metadata() {





        $where_scope=sprintf(' and `Subject Type`=%s',prepare_mysql($this->scope));

        $where_scope_key='';
        if ($this->scope_key)
            $where_scope_key=sprintf(' and `Subject Key`=%d',$this->scope_key);




        $sql=sprintf("select * from `Company Bridge` where `Company Key`=%d %s  %s  order by `Is Main` desc"
                     ,$this->id
                     ,$where_scope
                     ,$where_scope_key
                    );
        $res=mysql_query($sql);



        $this->data['Company Is Main']='No';
        $this->data['Company Is Active']='No';

        $this->associated_with_scope=false;
        while ($row=mysql_fetch_array($res)) {
            $this->associated_with_scope=true;

            $this->data['Company Is Main']=$row['Is Main'];
            $this->data['Company Is Active']=$row['Is Active'];

        }


    }

    function add_area($data) {
        $data1=$data;
        print_r($data1);
        include_once('class.CompanyArea.php');
        $data['Company Key']=$this->id;
        $data['editor']=$this->editor;
        $area=new CompanyArea('find',$data,'create');
        if ($area->id) {
            $this->updated=true;

        }
    }


    function add_department($data) {
        include_once('class.CompanyDepartment.php');
        $data['Company Key']=$this->id;
        $data['editor']=$this->editor;
        $department=new CompanyDepartment('find',$data,'create');
        if ($department->id) {
            $this->updated=true;

        }
    }
    function add_position($data) {
        include_once('class.CompanyPosition.php');
        $data['Company Key']=$this->id;
        $data['editor']=$this->editor;
        $department=new CompanyPosition('find',$data,'create');
        if ($department->id) {
            $this->updated=true;

        }
    }


    function update_telephone($telecom_key) {

        $old_telecom_key=$this->data['Company Main Telephone Key'];

        $telecom=new Telecom($telecom_key);
        if (!$telecom->id) {
            $this->error=true;
            $this->msg='Telecom not found';
            $this->msg_updated.=',Telecom not found';
            return;
        }
        $old_value=$this->data['Company Main XHTML Telephone'];
        $sql=sprintf("update `Company Dimension` set `Company Main XHTML Telephone`=%s ,`Company Main Plain Telephone`=%s  ,`Company Main Telephone Key`=%d where `Company Key`=%d "
                     ,prepare_mysql($telecom->display('xhtml'))
                     ,prepare_mysql($telecom->display('plain'))
                     ,$telecom->id
                     ,$this->id
                    );
        mysql_query($sql);
        if (mysql_affected_rows()) {

            $this->updated;
            if ($old_value!=$telecom->display('xhtml'))
                $history_data=array(
                                  'Indirect Object'=>'Company Main XHTML Telephone'
                                                    ,'History Abstract'=>_('Company Main XHTML Telephone Changed')
                                                                        ,'History Details'=>_('Company Main XHTML Telephone changed from')." ".$old_value." "._('to').' '.$telecom->display('xhtml')

                              );
            $this->add_history($history_data);
        }

    }

    function update_fax($telecom_key) {


        $old_telecom_key=$this->data['Company Main FAX Key'];

        $telecom=new Telecom($telecom_key);
        if (!$telecom->id) {
            $this->error=true;
            $this->msg='Telecom not found';
            $this->msg_updated.=',Telecom not found';
            return;
        }
        $old_value=$this->data['Company Main XHTML FAX'];
        $sql=sprintf("update `Company Dimension` set `Company Main XHTML FAX`=%s ,`Company Main Plain FAX`=%s  ,`Company Main Plain FAX`=%d where `Company Key`=%d "
                     ,prepare_mysql($telecom->display('xhtml'))
                     ,prepare_mysql($telecom->display('plain'))
                     ,$telecom->id
                     ,$this->id
                    );
        mysql_query($sql);
        if (mysql_affected_rows()) {
            $this->updated;
            if ($old_value!=$telecom->display('xhtml'))
                $history_data=array(
                                  'Indirect Object'=>'Company Main XHTML FAX'
                                                    ,'History Abstract'=>_('Company Main XHTML FAX Changed')
                                                                        ,'History Details'=>_('Company Main XHTML FAX changed from')." ".$old_value." "._('to').' '.$telecom->display('xhtml')


                              );
            $this->add_history($history_data);
        }

    }

    function update_children() {

        $children=array('Contact');
        foreach($children as $child) {
            $sql=sprintf("select `$child Key` as `Parent Key`   from  `$child Dimension` where `$child Company Key`=%d group by `$child Key`",$this->id);
//print $sql."\n";
            $res=mysql_query($sql);
            while ($row=mysql_fetch_array($res)) {
                $principal_contact_changed=false;

                if ($child=='Contact') {
                    $child_object=new Contact($row['Parent Key']);
                    $child_object->editor=$this->editor;
                    $child_label=_('Contact');
                }
                $old_principal_company=$child_object->data[$child.' Company Name'];
                $child_object->data[$child.' Company Name']=$this->get_name();
                $sql=sprintf("update `$child Dimension` set `$child Company Name`=%s where `$child Key`=%d"
                             ,prepare_mysql($child_object->data[$child.' Company Name'])
                             ,$child_object->id
                            );
                mysql_query($sql);



                if ($old_principal_company!=$child_object->data[$child.' Company Name'])
                    $principal_contact_changed=true;

                if ($principal_contact_changed) {

                    if ($old_principal_company=='') {

                        $history_data['History Abstract']='Company Associated';
                        $history_data['History Details']=_('Company').' '.$this->get_name()." "._('associated with')." ".$child_object->get_name()." ".$child_label;
                        $history_data['Action']='associated';
                        $history_data['Direct Object']='Company';
                        $history_data['Direct Object Key']=$this->id;
                        $history_data['Indirect Object']=$child;
                        $history_data['Indirect Object Key']=$child_object->id;
                        $this->add_history($history_data);
                    } else {
                        $history_data['History Abstract']='Company Changed';
                        $history_data['History Details']=$child_label.' '.$child_object->get_name().' '._('main company changed from').' '.$old_principal_company.' '._('to').' '.$this->get_name();
                        $history_data['Action']='changed';
                        $history_data['Direct Object']='Company';
                        $history_data['Direct Object Key']=$this->id;
                        $history_data['Indirect Object']=$child;
                        $history_data['Indirect Object Key']=$child_object->id;
                        $this->add_history($history_data);

                    }

                }




            }
        }
    }

    function update_parents() {

        $parents=array('Customer','Supplier');
        foreach($parents as $parent) {
            $sql=sprintf("select `$parent Key` as `Parent Key` from  `$parent Dimension` where `$parent Company Key`=%d group by `$parent Key`",$this->id);

            $res=mysql_query($sql);
            while ($row=mysql_fetch_array($res)) {
                $principal_contact_changed=false;

                if ($parent=='Customer') {
                    $parent_object=new Customer($row['Parent Key']);
                    $parent_label=_('Customer');
                }
                elseif($parent=='Supplier') {
                    $parent_object=new Supplier($row['Parent Key']);
                    $parent_label=_('Supplier');
                }

                $old_principal_name=$parent_object->data[$parent.' Name'];
                $parent_object->data[$parent.' Name']=$this->data['Company Name'];
                $sql=sprintf("update `$parent Dimension` set  `$parent Company Name`=%s  where `$parent Key`=%d"
                             ,prepare_mysql($parent_object->data[$parent.' Name'])
                             ,$parent_object->id
                            );
                mysql_query($sql);

                if ($parent=='Supplier' or ( $parent=='Customer' and $parent_object->data[$parent.' Type']=='Company')) {
                    $sql=sprintf("update `$parent Dimension` set `$parent Name`=%s  , `$parent File As`=%s   where `$parent Key`=%d"
                                 ,prepare_mysql($parent_object->data[$parent.' Name'])
                                 ,prepare_mysql($parent_object->data[$parent.' Name'])

                                 ,$parent_object->id
                                );
                    mysql_query($sql);
                    //   print "$sql\n";
                }




                if ($old_principal_name!=$parent_object->data[$parent.' Name'])
                    $principal_contact_changed=true;

                if ($principal_contact_changed) {

                    if ($old_principal_name=='') {

                        $history_data['History Abstract']='Company Associated '.$this->data['Company Name'];
                        $history_data['History Details']=$this->data['Company Name']." "._('associated with')." ".$parent_object->get_name()." ".$parent_label;
                        $history_data['Action']='associated';
                        $history_data['Direct Object']='Company';
                        $history_data['Direct Object Key']=$this->id;
                        $history_data['Indirect Object']=$parent;
                        $history_data['Indirect Object Key']=$parent_object->id;

                    } else {
                        $history_data['History Abstract']='Company name changed to '.$this->data['Company Name'];
                        $history_data['History Details']=_('Name changed from').' '.$old_principal_name.' '._('to').' '.$this->data['Company Name']." "._('in')." ".$parent_object->get_name()." ".$parent_label;
                        $history_data['Action']='changed';
                        $history_data['Direct Object']=$parent;
                        $history_data['Direct Object Key']=$parent_object->id;
                        $history_data['Indirect Object']=$parent.' Name';
                        $history_data['Indirect Object Key']='';



                    }
                    if ($parent=='Customer') {
                        $parent_object->add_customer_history($history_data);
                    } else {
                        $this->add_history($history_data);
                    }

                }




            }
        }
    }
    function update_parents_tax_number() {

        $parents=array('Customer');
        foreach($parents as $parent) {
            $sql=sprintf("select `$parent Key` as `Parent Key` from  `$parent Dimension` where `$parent Company Key`=%d group by `$parent Key`",$this->id);

            $res=mysql_query($sql);
            while ($row=mysql_fetch_array($res)) {
                $principal_contact_changed=false;

                if ($parent=='Customer') {
                    $parent_object=new Customer($row['Parent Key']);
                    $parent_label=_('Customer');
                }
                elseif($parent=='Supplier') {
                    $parent_object=new Supplier($row['Parent Key']);
                    $parent_label=_('Supplier');
                }

                $old_principal_name=$parent_object->data[$parent.' Tax Number'];
                $parent_object->data[$parent.' Tax Number']=$this->data['Company Tax Number'];
                $sql=sprintf("update `$parent Dimension` set  `$parent Tax Number`=%s  where `$parent Key`=%d"
                             ,prepare_mysql($parent_object->data[$parent.' Tax Number'])
                             ,$parent_object->id
                            );
                mysql_query($sql);

                if ($parent=='Supplier' or ( $parent=='Customer' and $parent_object->data[$parent.' Type']=='Company')) {
                    $sql=sprintf("update `$parent Dimension` set `$parent Tax Number`=%s where `$parent Key`=%d"
                                 ,prepare_mysql($parent_object->data[$parent.' Tax Number'])


                                 ,$parent_object->id
                                );
                    mysql_query($sql);
                    //   print "$sql\n";
                }




                if ($old_principal_name!=$parent_object->data[$parent.' Tax Number'])
                    $principal_contact_changed=true;

                if ($principal_contact_changed) {

                    if ($old_principal_name=='') {

                        $history_data['History Abstract']='Tax Number Associated '.$this->data['Company Tax Number'];
                        $history_data['History Details']=$this->data['Company Tax Number']." "._('associated with')." ".$parent_object->get_name()." ".$parent_label;
                        $history_data['Action']='associated';
                        $history_data['Direct Object']=$parent;
                        $history_data['Direct Object Key']=$parent_object->id;
                        $history_data['Indirect Object']=$parent.' Tax Name';
                        $history_data['Indirect Object Key']='';

                    } else {
                        $history_data['History Abstract']='Tax Number changed to '.$this->data['Company Tax Number'];
                        $history_data['History Details']=_('Tax Number changed from').' '.$old_principal_name.' '._('to').' '.$this->data['Company Tax Number'].", ".$parent_label.": ".$parent_object->get_name();
                        $history_data['Action']='changed';
                        $history_data['Direct Object']=$parent;
                        $history_data['Direct Object Key']=$parent_object->id;
                        $history_data['Indirect Object']=$parent.' Tax Name';
                        $history_data['Indirect Object Key']='';



                    }
                    if ($parent=='Customer') {
                        $parent_object->add_customer_history($history_data);
                    } else {
                        $this->add_history($history_data);
                    }

                }




            }
        }
    }

     function update_parents_registration_number() {

        $parents=array('Customer');
        foreach($parents as $parent) {
            $sql=sprintf("select `$parent Key` as `Parent Key` from  `$parent Dimension` where `$parent Company Key`=%d group by `$parent Key`",$this->id);

            $res=mysql_query($sql);
            while ($row=mysql_fetch_array($res)) {
                $principal_contact_changed=false;

                if ($parent=='Customer') {
                    $parent_object=new Customer($row['Parent Key']);
                    $parent_label=_('Customer');
                }
                elseif($parent=='Supplier') {
                    $parent_object=new Supplier($row['Parent Key']);
                    $parent_label=_('Supplier');
                }
//print_r($parent_object);
                $old_principal_name=$parent_object->data[$parent.' Registration Number'];
                                                        
                $parent_object->data[$parent.' Registration Number']=$this->data['Company Registration Number'];
                $sql=sprintf("update `$parent Dimension` set  `$parent Registration Number`=%s  where `$parent Key`=%d"
                             ,prepare_mysql($parent_object->data[$parent.' Registration Number'])
                             ,$parent_object->id
                            );
                mysql_query($sql);

                if ($parent=='Supplier' or ( $parent=='Customer' and $parent_object->data[$parent.' Type']=='Company')) {
                    $sql=sprintf("update `$parent Dimension` set `$parent Registration Number`=%s where `$parent Key`=%d"
                                 ,prepare_mysql($parent_object->data[$parent.' Registration Number'])


                                 ,$parent_object->id
                                );
                    mysql_query($sql);
                    //   print "$sql\n";
                }




                if ($old_principal_name!=$parent_object->data[$parent.' Registration Number'])
                    $principal_contact_changed=true;

                if ($principal_contact_changed) {

                    if ($old_principal_name=='') {

                        $history_data['History Abstract']='Registration Number Associated '.$this->data['Company Registration Number'];
                        $history_data['History Details']=$this->data['Company Registration Number']." "._('associated with')." ".$parent_object->get_name()." ".$parent_label;
                        $history_data['Action']='associated';
                        $history_data['Direct Object']=$parent;
                        $history_data['Direct Object Key']=$parent_object->id;
                        $history_data['Indirect Object']=$parent.' Tax Name';
                        $history_data['Indirect Object Key']='';

                    } else {
                        $history_data['History Abstract']='Registration Number changed to '.$this->data['Company Registration Number'];
                        $history_data['History Details']=_('Registration Number changed from').' '.$old_principal_name.' '._('to').' '.$this->data['Company Registration Number'].", ".$parent_label.": ".$parent_object->get_name();
                        $history_data['Action']='changed';
                        $history_data['Direct Object']=$parent;
                        $history_data['Direct Object Key']=$parent_object->id;
                        $history_data['Indirect Object']=$parent.' Tax Name';
                        $history_data['Indirect Object Key']='';



                    }
                    if ($parent=='Customer') {
                        $parent_object->add_customer_history($history_data);
                    } else {
                        $this->add_history($history_data);
                    }

                }




            }
        }
    }



    function get_parent_keys($type=false) {
        $where_type='';
        $keys=array();
        if ($type)  {
            if (!preg_match('/^(Supplier|Contact|Customer|HQ)$/',$type)) {
                return $keys;
            }
            $where_type=' and `Subject Type`='.prepare_mysql($type);

        }
        $sql=sprintf("select `Subject Key` from `Company Bridge` where  `Company Key`=%d  $where_type ",
                     $this->id);
        $result=mysql_query($sql);
        while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $keys[$row['Subject Key']]= $row['Subject Key'];

        }
        return $keys;
    }


    function delete() {


        $sql=sprintf("delete from `Company Dimension` where `Company Key`=%d",$this->id);
//print "$sql\n";
        mysql_query($sql);
//mysql_query($sql);

        $address_to_delete=$this->get_address_keys();
        $emails_to_delete=$this->get_email_keys();
        $telecom_to_delete=$this->get_telecom_keys();

        $sql=sprintf("delete from `Address Bridge` where `Subject Type`='Company' and `Subject Key`=%d",$this->id);
        mysql_query($sql);
        $sql=sprintf("delete from `Category Bridge` where `Subject`='Company' and `Subject Key`=%d",$this->id);
        mysql_query($sql);

        $sql=sprintf("delete from `Contact Bridge` where `Subject Type`='Company' and `Subject Key`=%d",$this->id);
        mysql_query($sql);
        $sql=sprintf("delete from `Email Bridge` where `Subject Type`='Company' and `Subject Key`=%d",$this->id);
        mysql_query($sql);
        $sql=sprintf("delete from `Telecom Bridge` where `Subject Type`='Company' and `Subject Key`=%d",$this->id);
        mysql_query($sql);



        foreach($emails_to_delete as $email_key) {
            $email=new Email($email_key);
            if ($email->id and !$email->has_parents()) {
                $email->delete();
            }
        }



        foreach($address_to_delete as $address_key) {
            $address=new Address($address_key);
            if ($address->id and !$address->has_parents()) {
                $address->delete();
            }
        }



        foreach($telecom_to_delete as $telecom_key) {
            $telecom=new Telecom($telecom_key);
            if ($telecom->id and !$telecom->has_parents()) {
                $telecom->delete();
            }
        }



        /*
        if(!$ignore_contacts_keys){
        $ignore_contacts_keys=array();
        }

        $company_address_keys=$this->get_address_keys();
        $company_contacts_keys=$this->get_contact_keys();
        foreach($ignore_contacts_keys as $ignore_contacts_key){
        unset($company_contacts_keys[$ignore_contacts_key]);
        }

        print "address:\n";
        print_r($company_address_keys);
        print_r($company_contacts_keys);
        */
    }


}

?>
