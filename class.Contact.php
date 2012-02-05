<?php
/*
  File: Contact.php

  This file contains the Contact Class

  About:
  Autor: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2009, Inikoo

  Version 2.0
*/
include_once 'class.DB_Table.php';



include_once 'class.Telecom.php';
include_once 'class.Email.php';
include_once 'class.Address.php';
include_once 'class.Customer.php';
include_once 'class.Company.php';


/* class: Contact
   Class to manage the *Contact Dimension* table
*/

class Contact extends DB_Table {

	public $new_value=false;

	public $scope=false;
	public $scope_key=false;
	private  $new_home_telephone_keys=array();
	public $inserted_email=false;
	public $add_telecom=false;
	public $parent=false;



	/*
      Constructor: Contact

      Initializes the class, Search/Load or Create for the data set

      Parameters:
      arg1 -    (optional) Could be the tag for the Search Options or the Contact Key for a
      simple object key search
      arg2 -    (optional) Data used to search or create the object

      Returns:
      void

      Example:
      (start example)
      // Load data from `Contact Dimension` table where  `Contact Key`=3
      $key=3;
      $contact = New Contact($key);

      // Insert row to `Contact Dimension` table
      $data=array();
      $contact = New Contact('new',$data);


      (end example)

    */
	function Contact($arg1=false,$arg2=false,$arg3=false) {

		global $myconf;

		$this->table_name='Contact';
		$this->ignore_fields=array('Contact Key');

		$this->unknown_contact_name='';
		$this->unknown_informal_greting='Hello';
		$this->unknown_formal_greting='Dear Sir Madam';


		if (preg_match('/create anonymous|create anonimous$/i',$arg1)) {
			$this->create_anonymous($arg2,$arg3);
			return;
		}
		if (preg_match('/^(new|create)$/i',$arg1)) {
			$this->create($arg2);
			return;
		}
		if (preg_match('/find/i',$arg1)) {
			$this->find($arg2,$arg1);
			return;
		}



		if (is_numeric($arg1) and !$arg2) {
			$this->get_data('id',$arg1);
			return;
		}
		$this->get_data($arg1,$arg2);


	}
	/* Function: get_data
       Load the data from de Database

       Parameters:
       $key  -  string Search Field
       $id  -  mixed Search Argument
       Return: void
    */

	protected  function get_data($key,$id) {


		if ($key=='id')
			$sql=sprintf("SELECT * FROM `Contact Dimension` C where `Contact Key`=%d",$id);
		else
			return;

		$result=mysql_query($sql);
		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
			$this->id=$this->data['Contact Key'];


	}


	function find_fast($email=false) {


		$this->found=false;
		$this->found_key=false;
		$this->found_details=array();

		if (!$email)
			return;

		$sql=sprintf("select E.`Email Key`,`Subject Type`,`Subject Key` from `Email Dimension` E left join `Email Bridge` B on (E.`Email Key`=B.`Email Key`)where `Email`=%s",prepare_mysql($email));

		$res=mysql_query($sql);

		while ($row=mysql_fetch_assoc($res)) {


			$this->found_details[$row['Email Key']]=array('Subject Type'=>$row['Subject Type'],'Subject Key'=>$row['Subject Key']);

			if ($row['Subject Type']=='Contact') {
				$this->found=true;
				$this->found_key=$row['Subject Key'];

			}

		}






	}

	function find_complete($data,$parent,$parent_key,$address_home_data,$address_work_data) {

		//print_r($data);



		$this->candidate=array();
		$this->found=false;
		$this->found_key=false;

		$this->candidate=array();

		if ($data['Contact Main Plain Email']!='') {
			//Timer::timing_milestone('begin  find  contact email');
			$email=new Email("find in contact ",$data['Contact Main Plain Email']);
			//Timer::timing_milestone('end  find  contact email');

			if ($email->error) {
				$data['Contact Main Plain Email']='';

			}

			if ( $email->found) {
				$this->found=true;
				$this->found_key=$email->found_key;
			}
			foreach ($email->candidate as $key=>$val) {
				if (isset($this->candidate[$key]))
					$this->candidate[$key]+=$val;
				else
					$this->candidate[$key]=$val;
			}



		}

		//print "------------------\n";
		//print "$data,$parent,$parent_key,$address_home_data,$address_work_data\n";
		//print_r($data);


		//  print "candidates ofter telephone:\n";
		// print_r($this->candidate);
		// if(count($this->candidate)>0){
		//    print "----------------------------- candidates ofter email:\n";
		//    print_r($this->candidate);
		// }

		//Timer::timing_milestone('begin  find  contact address');
		//print_r($address_work_data);

		if ($data['Contact Name']=='')
			$data['Contact Fuzzy']='Yes';


		$country_code='UNK';

		if (!array_empty( $address_work_data)) {

			$address=new Address("find in contact complete",$address_work_data);
			// print_r($address_work_data);

			foreach ($address->candidate as $key=>$val) {
				if (isset($this->candidate[$key]))
					$this->candidate[$key]+=$val;
				else
					$this->candidate[$key]=$val;
			}
		}


		if (!array_empty( $address_home_data)) {
			$address=new Address("find in contact complete",$address_home_data);


			// $country_code=$address->raw_data['Address Country Code'];

			foreach ($address->candidate as $key=>$val) {
				if (isset($this->candidate[$key]))
					$this->candidate[$key]+=$val;
				else
					$this->candidate[$key]=$val;
			}
		}

		//Timer::timing_milestone('end  find  contact address / begin tel');




		if ($data['Contact Main Plain Telephone']!=''  ) {

			$tel=new Telecom("find in contact country code $country_code",$data['Contact Main Plain Telephone']);
			//  print_r($tel->candidate);
			foreach ($tel->candidate as $key=>$val) {

				if ($data['Contact Fuzzy']=='Yes')
					$val=$val+25;
				if (isset($this->candidate[$key]))
					$this->candidate[$key]+=$val;
				else
					$this->candidate[$key]=$val;
			}
		}
		//Timer::timing_milestone('end  find  contact tel / begin fax');


		if ($data['Contact Main Plain FAX']!='' ) {
			$tel=new Telecom("find in contact country code $country_code",$data['Contact Main Plain FAX']);

			//    exit;
			foreach ($tel->candidate as $key=>$val) {
				if ($data['Contact Fuzzy']=='Yes')
					$val=$val+25;
				if (isset($this->candidate[$key]))
					$this->candidate[$key]+=$val;
				else
					$this->candidate[$key]=$val;
			}

		}


		//if(count($this->candidate)>0){
		//print "candidates after fax:\n";
		//print_r($this->candidate);
		//}
		//Timer::timing_milestone('end  find  contact fax / begin mob');

		if ($data['Contact Main Plain Mobile']!='' and !$this->found ) {
			$tel=new Telecom("find in contact  country code $country_code",$data['Contact Main Plain Mobile']);

			foreach ($tel->candidate as $key=>$val) {
				// if($data['Contact Fuzzy']=='Yes')
				//   $val=$val+100;
				if (isset($this->candidate[$key]))
					$this->candidate[$key]+=$val;
				else
					$this->candidate[$key]=$val;
			}

		}

		///  if (count($this->candidate)>0) {
		//      print "candidates after mobile:\n";
		//  print_r($this->candidate);

		//}

		//Timer::timing_milestone('end  find  contact mob / begin other');





		if ($data['Contact Fuzzy']!='Yes') {
			if (array_key_exists('Contact Name Components',$data) and is_array($data['Contact Name Components'])) {
				$name_data=$data['Contact Name Components'];
			} else {
				$name_data=$this->parse_name($data['Contact Name']);

			}
			$name=$this->name($name_data);

			$salutation_max_semiscore=5;
			$first_name_max_score=27;
			$surname_max_score=73;


			if ($name_data['Contact First Name']!='') {
				$sql=sprintf("select `Contact Salutation`,`Contact Key` from `Contact Dimension` where  `Contact First Name`=%s  and `Contact First Name` is not null   limit 200"
					,prepare_mysql($name_data['Contact First Name'])

				);

				$result=mysql_query($sql);
				while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {


					$score=$first_name_max_score;
					if ($name_data['Contact Salutation']!='' and $name_data['Contact Salutation']=$row['Contact Salutation'])
						$score+=$salutation_max_semiscore;
					$contact_key=$row['Contact Key'];
					if (isset($this->candidate[$contact_key]))
						$this->candidate[$contact_key]+=$score;
					else
						$this->candidate[$contact_key]=$score;
				}

			}



			if ($name_data['Contact Surname']!='') {
				$sql=sprintf("select `Contact Salutation`,`Contact Key` from `Contact Dimension`  where  `Contact Surname`=%s and   `Contact Surname` is not null   limit 200"
					,prepare_mysql($name_data['Contact Surname'])

				);

				$result=mysql_query($sql);
				while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

					$score=$surname_max_score;
					if ($name_data['Contact Salutation']!='' and $name_data['Contact Salutation']=$row['Contact Salutation'])
						$score+=$salutation_max_semiscore;
					$contact_key=$row['Contact Key'];
					if (isset($this->candidate[$contact_key]))
						$this->candidate[$contact_key]+=$score;
					else
						$this->candidate[$contact_key]=$score;
				}
			}








		}




		if (isset($data['Contact Old ID']) and $data['Contact Old ID']!='') {
			$sql=sprintf("select `Contact Key` from `Contact Old ID Bridge` where `Contact Old ID`=%s",prepare_mysql($data['Contact Old ID']));
			$res=mysql_query($sql);
			while ($row=mysql_fetch_array($res)) {
				$val=50;
				$key=$row['Contact Key'];
				if (isset($this->candidate[$key]))
					$this->candidate[$key]+=$val;
				else
					$this->candidate[$key]=$val;
			}
		}
		/*

                if (isset($data['Contact Tax Number'])) {
                    $contacts_in_company=array();
                    $data['Contact Tax Number']=_trim($data['Contact Tax Number']);
                    if ($data['Contact Tax Number']!='') {
                        $sql=sprintf("select `Company Key` from `Company Dimension` where `Company Tax Number`=%s",prepare_mysql($data['Contact Tax Number']));
                        $res=mysql_query($sql);
                        while ($row=mysql_fetch_array($res)) {
                            $company=new company($row['Company Key']);
                            $company->load('Contact List');
                            foreach($this->contact_list as $key=>$val) {
                                $contacts_in_company[$key]=$key;
                            }
                        }

                        foreach($contacts_in_company as $key) {
                            $val=100;
                            $key=$row['Company Key'];
                            if (isset($this->candidate[$key]))
                                $this->candidate[$key]+=$val;
                            else
                                $this->candidate[$key]=$val;
                        }


                    }
                }
        */

		/*

        //print "*************************************\n";

                if (isset($data['Contact Company Name'])) {
        //print "*************************************\n";
        $val=40;

                    $contacts_in_company=array();
                    $data['Contact Company Name']=_trim($data['Contact Company Name']);
                    if ($data['Contact Company Name']!='') {
                        $sql=sprintf("select  `Subject Key` from `Company Bridge` B  left join `Company Dimension` C  on (B.`Company Key`=C.`Company Key`)  where `Subject Type`='Contact' and  `Company Name`=%s group by `Subject Key`",prepare_mysql($data['Contact Company Name']));
                       // print $sql;
                        $res=mysql_query($sql);
                        while ($row=mysql_fetch_array($res)) {
                           $key=$row['Subject Key'];
                            if (isset($this->candidate[$key]))
                                $this->candidate[$key]+=$val;
                            else
                                $this->candidate[$key]=$val;


                        }



                    }
                }


        //print_r($this->candidate);
        //exit;
                if (isset($data['Contact Registration Number'])) {
                    $contacts_in_company=array();
                    $data['Contact Registration Number']=_trim($data['Contact Registration Number']);
                    if ($data['Contact Registration Number']!='') {
                        $sql=sprintf("select `Company Key` from `Company Dimension` where `Company Registration Number`=%s",prepare_mysql($data['Contact Registration Number']));
                        $res=mysql_query($sql);
                        while ($row=mysql_fetch_array($res)) {
                            $company=new company($row['Company Key']);
                            $company->load('Contact List');
                            foreach($this->contact_list as $key=>$val) {
                                $contacts_in_company[$key]=$key;
                            }
                        }

                        foreach($contacts_in_company as $key) {
                            $val=100;
                            $key=$row['Company Key'];
                            if (isset($this->candidate[$key]))
                                $this->candidate[$key]+=$val;
                            else
                                $this->candidate[$key]=$val;
                        }


                    }
                }
        */
		if ($parent=='company' and $parent_key and  $data['Contact Fuzzy']!='Yes') {
			// look for down grades;

			$sql=sprintf("select  `Contact Dimension`.`Contact Key`,`Contact Salutation`,`Contact First Name`,`Contact Surname`,`Contact Suffix` from `Contact Dimension` left join `Contact Bridge` on (`Contact Dimension`.`Contact Key`=`Contact Bridge`.`Contact Key`) where `Subject Key`=%d and `Subject Type`='Company'",
				$parent_key);
			//  print $sql;
			//print_r($name_data);
			$result=mysql_query($sql);
			$_candidate=array();
			while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$_contact_key=$row['Contact Key'];
				unset($row['Contact Key']);
				$_candidate[$_contact_key]=0;
				foreach ($row as $key=>$val) {
					if ($name_data[$key]!='' and $row[$key]!='') {
						if ($name_data[$key]==$row[$key])
							$_candidate[$_contact_key]+=1;
						else
							$_candidate[$_contact_key]-=2;
					}

				}
			}
			//asort($_candidate);
			//      print_r($_candidate);ex
			foreach ($_candidate as $key=>$val) {
				if ($val>0) {
					if (isset($this->candidate[$key]))
						$this->candidate[$key]+=200*$val;
					else
						$this->candidate[$key]=200*$val;
				}
			}


		}

		//Timer::timing_milestone('end  other');


		//print_r($this->candidate);
		arsort($this->candidate);

		/*  if((!$create and !$update) and $this->found){ */
		/*        print "Candidates from #######################\n"; */
		/*       print "direct found!!!!!!\n"; */

		/*       $cont=new Contact($found_key); */
		/* 	print_r($cont->data); */

		/* 	print "Candidates from ----------------------|\n"; */
		/*     } */

		/*     if((!$create and !$update) and count($this->candidate)!=0 ){ */

		/*       print "Candidates from #######################\n"; */
		/*       print_r($data); */
		/*       foreach($this->candidate as $key => $value){ */
		/* 	print "Score: $value\n"; */
		/* 	$cont=new Contact($key); */
		/* 	print_r($cont->data); */
		/*       } */
		/*       print "Candidates from ----------------------|\n"; */
		/*     } */

		// print_r($this->candidate);
		//
		// exit;
		foreach ($this->candidate as $key => $value) {

			if ($value>=200) {
				// print "$value $key ################x#######\n";
				$this->found=true;
				$this->found_key=$key;
				break;
			}


		}
		if (!$this->found) {
			$tmp=$data;
			unset($tmp['Contact Name']);
			if (array_empty($tmp)) {
				foreach ($this->candidate as $key => $value) {
					if ($value>=100) {
						$this->found=true;
						$this->found_key=$key;
						break;
					}

				}
			}
		}



	}
	function find_fuzzy($data,$parent,$parent_key,$address_home_data,$address_work_data) {


		if ($data['Contact Name']=='')
			$data['Contact Fuzzy']='Yes';


		// print_r($data);
		$this->candidate=array();
		$this->found=false;
		$this->found_key=false;

		$this->candidate=array();

		if ($data['Contact Main Plain Email']!='') {
			//Timer::timing_milestone('begin  find  contact email');
			$email=new Email("find in contact fuzzy",$data['Contact Main Plain Email']);
			//Timer::timing_milestone('end  find  contact email');

			if ($email->error) {
				$data['Contact Main Plain Email']='';

			}

			if ( $email->found) {
				$this->found=true;
				$this->found_key=$email->found_key;
			}
			foreach ($email->candidate as $key=>$val) {
				if (isset($this->candidate[$key]))
					$this->candidate[$key]+=$val;
				else
					$this->candidate[$key]=$val;
			}



		}


		//  print "candidates ofter telephone:\n";
		// print_r($this->candidate);
		// if(count($this->candidate)>0){
		//    print "----------------------------- candidates ofter email:\n";
		//    print_r($this->candidate);
		// }

		//Timer::timing_milestone('begin  find  contact address');



		$country_code='UNK';

		if (!array_empty( $address_work_data)) {

			$address=new Address("find  fuzzy",$address_work_data);
			// print_r($address_work_data);

			foreach ($address->candidate as $key=>$val) {
				if (isset($this->candidate[$key]))
					$this->candidate[$key]+=$val;
				else
					$this->candidate[$key]=$val;
			}
		}
		// print "----------------------------- candidates ofter address:\n";
		//   print_r($this->candidate);


		if (!array_empty( $address_home_data)) {
			$address=new Address("find  fuzzy",$address_home_data);

			// $country_code=$address->raw_data['Address Country Code'];

			foreach ($address->candidate as $key=>$val) {
				if (isset($this->candidate[$key]))
					$this->candidate[$key]+=$val;
				else
					$this->candidate[$key]=$val;
			}
		}

		//Timer::timing_milestone('end  find  contact address / begin tel');




		if ($data['Contact Main Plain Telephone']!=''  ) {

			$tel=new Telecom("find in contact country code $country_code",$data['Contact Main Plain Telephone']);
			//  print_r($tel->candidate);
			foreach ($tel->candidate as $key=>$val) {

				if ($data['Contact Fuzzy']=='Yes')
					$val=$val+25;
				if (isset($this->candidate[$key]))
					$this->candidate[$key]+=$val;
				else
					$this->candidate[$key]=$val;
			}
		}
		//Timer::timing_milestone('end  find  contact tel / begin fax');


		if ($data['Contact Main Plain FAX']!='' ) {
			$tel=new Telecom("find in contact country code $country_code",$data['Contact Main Plain FAX']);

			//    exit;
			foreach ($tel->candidate as $key=>$val) {
				if ($data['Contact Fuzzy']=='Yes')
					$val=$val+25;
				if (isset($this->candidate[$key]))
					$this->candidate[$key]+=$val;
				else
					$this->candidate[$key]=$val;
			}

		}

		//if(count($this->candidate)>0){
		//print "candidates after fax:\n";
		//print_r($this->candidate);
		//}
		//Timer::timing_milestone('end  find  contact fax / begin mob');

		if ($data['Contact Main Plain Mobile']!='' and !$this->found ) {
			$tel=new Telecom("find in contact  country code $country_code",$data['Contact Main Plain Mobile']);

			foreach ($tel->candidate as $key=>$val) {
				// if($data['Contact Fuzzy']=='Yes')
				//   $val=$val+100;
				if (isset($this->candidate[$key]))
					$this->candidate[$key]+=$val;
				else
					$this->candidate[$key]=$val;
			}

		}

		if (count($this->candidate)>0) {
			//    print "candidates after mobile:\n";
			// print_r($this->candidate);

		}

		//Timer::timing_milestone('end  find  contact mob / begin other');



		if ($data['Contact Old ID']!=''  and $data['Contact Old ID']!='') {
			$sql=sprintf("select `Contact Key` from `Contact Old ID Bridge` where `Contact Old ID`=%s",prepare_mysql($data['Contact Old ID']));
			$res=mysql_query($sql);
			while ($row=mysql_fetch_array($res)) {
				$val=100;
				$key=$row['Contact Key'];
				if (isset($this->candidate[$key]))
					$this->candidate[$key]+=$val;
				else
					$this->candidate[$key]=$val;
			}


		}

		//print_r($data);

		if ($data['Contact Fuzzy']!='Yes') {
			if (array_key_exists('Contact Name Components',$data) and is_array($data['Contact Name Components'])) {
				$name_data=$data['Contact Name Components'];
			} else {
				$name_data=$this->parse_name($data['Contact Name']);

			}
			$name=$this->name($name_data);


			// `Customer Main Contact Name` REGEXP "[[:<:]]%s"







			$salutation_max_semiscore=5;
			$first_name_max_score=7;
			$surname_max_score=43;

			//print_r($name_data);

			$len_name=strlen($name_data['Contact First Name']);
			if ($name_data['Contact First Name']!=''  and $len_name<256 ) {



				$sql=sprintf('select `Contact Key` from `Contact Dimension` where    `Contact Name` REGEXP "[[:<:]]%s" limit 100',$name_data['Contact First Name']);
				$res=mysql_query($sql);
				$score=50;
				//print $sql;
				while ($row=mysql_fetch_assoc($res)) {

					$contact_key=$row['Contact Key'];
					if (isset($this->candidate[$contact_key]))
						$this->candidate[$contact_key]+=$score;
					else
						$this->candidate[$contact_key]=$score;
				}

				if ($len_name<256) {
					$sql=sprintf("select `Contact Salutation`,`Contact Key`,damlevlim256(UPPER(%s),UPPER(`Contact First Name`),$len_name)/$len_name as dist1 from `Contact Dimension` where  `Contact First Name` is not null  order by dist1  limit 80"
						,prepare_mysql($name_data['Contact First Name'])

					);

					$result=mysql_query($sql);
					while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
						if ($row['dist1']>=1)
							break;

						$score=$first_name_max_score*pow(1-  $row['dist1'],3   );
						if ($name_data['Contact Salutation']!='' and $name_data['Contact Salutation']=$row['Contact Salutation'])
							$score+=$salutation_max_semiscore;
						$contact_key=$row['Contact Key'];
						if (isset($this->candidate[$contact_key]))
							$this->candidate[$contact_key]+=$score;
						else
							$this->candidate[$contact_key]=$score;
					}
				}


			}



			$len_name=strlen($name_data['Contact Surname']);
			if ($name_data['Contact Surname']!=''and $len_name<256) {
				$sql=sprintf("select `Contact Salutation`,`Contact Key`,damlevlim256(UPPER(%s),UPPER(`Contact Surname`),$len_name)/$len_name as dist1 from `Contact Dimension`  where  `Contact Surname` is not null   order by dist1  limit 40"
					,prepare_mysql($name_data['Contact Surname'])

				);
				$result=mysql_query($sql);
				while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
					if ($row['dist1']>=1)
						break;
					$score=$surname_max_score*pow(1-  $row['dist1'],3);
					if ($name_data['Contact Salutation']!='' and $name_data['Contact Salutation']=$row['Contact Salutation'])
						$score+=$salutation_max_semiscore;
					$contact_key=$row['Contact Key'];
					if (isset($this->candidate[$contact_key]))
						$this->candidate[$contact_key]+=$score;
					else
						$this->candidate[$contact_key]=$score;
				}
			}



		}








		if (isset($data['Contact Tax Number'])) {
			$contacts_in_company=array();
			$data['Contact Tax Number']=_trim($data['Contact Tax Number']);
			if ($data['Contact Tax Number']!='') {
				$sql=sprintf("select `Company Key` from `Company Dimension` where `Company Tax Number`=%s",prepare_mysql($data['Contact Tax Number']));
				$res=mysql_query($sql);
				while ($row=mysql_fetch_array($res)) {
					$company=new company($row['Company Key']);
					$company->load('Contact List');
					foreach ($this->contact_list as $key=>$val) {
						$contacts_in_company[$key]=$key;
					}
				}

				foreach ($contacts_in_company as $key) {
					$val=100;
					$key=$row['Company Key'];
					if (isset($this->candidate[$key]))
						$this->candidate[$key]+=$val;
					else
						$this->candidate[$key]=$val;
				}


			}
		}



		if (isset($data['Contact Company Name'])) {


			$contacts_in_company=array();
			$data['Contact Company Name']=_trim($data['Contact Company Name']);
			if ($data['Contact Company Name']!='') {
				$sql=sprintf("select `Company Key` from `Company Dimension` where `Company Name`=%s",prepare_mysql($data['Contact Company Name']));
				$res=mysql_query($sql);
				while ($row=mysql_fetch_array($res)) {
					$company=new company($row['Company Key']);
					$company->load('Contact List');
					foreach ($this->contact_list as $key=>$val) {
						$contacts_in_company[$key]=$key;
					}
				}

				foreach ($contacts_in_company as $key) {
					$val=40;
					$key=$row['Company Key'];
					if (isset($this->candidate[$key]))
						$this->candidate[$key]+=$val;
					else
						$this->candidate[$key]=$val;
				}


			}
		}

		if (isset($data['Contact Registration Number'])) {
			$contacts_in_company=array();
			$data['Contact Registration Number']=_trim($data['Contact Registration Number']);
			if ($data['Contact Registration Number']!='') {
				$sql=sprintf("select `Company Key` from `Company Dimension` where `Company Registration Number`=%s",prepare_mysql($data['Contact Registration Number']));
				$res=mysql_query($sql);
				while ($row=mysql_fetch_array($res)) {
					$company=new company($row['Company Key']);
					$company->load('Contact List');
					foreach ($this->contact_list as $key=>$val) {
						$contacts_in_company[$key]=$key;
					}
				}

				foreach ($contacts_in_company as $key) {
					$val=100;
					$key=$row['Company Key'];
					if (isset($this->candidate[$key]))
						$this->candidate[$key]+=$val;
					else
						$this->candidate[$key]=$val;
				}


			}
		}

		if ($parent=='company' and $parent_key and  $data['Contact Fuzzy']!='Yes') {
			// look for down grades;

			$sql=sprintf("select  `Contact Dimension`.`Contact Key`,`Contact Salutation`,`Contact First Name`,`Contact Surname`,`Contact Suffix` from `Contact Dimension` left join `Contact Bridge` on (`Contact Dimension`.`Contact Key`=`Contact Bridge`.`Contact Key`) where `Subject Key`=%d and `Subject Type`='Company'",$parent_key);
			//  print $sql;
			//print_r($name_data);
			$result=mysql_query($sql);
			$_candidate=array();
			while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$_contact_key=$row['Contact Key'];
				unset($row['Contact Key']);
				$_candidate[$_contact_key]=0;
				foreach ($row as $key=>$val) {
					if ($name_data[$key]!='' and $row[$key]!='') {
						if ($name_data[$key]==$row[$key])
							$_candidate[$_contact_key]+=1;
						else
							$_candidate[$_contact_key]-=2;
					}

				}
			}
			//asort($_candidate);
			//      print_r($_candidate);ex
			foreach ($_candidate as $key=>$val) {
				if ($val>0) {
					if (isset($this->candidate[$key]))
						$this->candidate[$key]+=200*$val;
					else
						$this->candidate[$key]=200*$val;
				}
			}


		}

		//Timer::timing_milestone('end  other');


		//print_r($this->candidate);
		arsort($this->candidate);

		/*  if((!$create and !$update) and $this->found){ */
		/*        print "Candidates from #######################\n"; */
		/*       print "direct found!!!!!!\n"; */

		/*       $cont=new Contact($found_key); */
		/* 	print_r($cont->data); */

		/* 	print "Candidates from ----------------------|\n"; */
		/*     } */

		/*     if((!$create and !$update) and count($this->candidate)!=0 ){ */

		/*       print "Candidates from #######################\n"; */
		/*       print_r($data); */
		/*       foreach($this->candidate as $key => $value){ */
		/* 	print "Score: $value\n"; */
		/* 	$cont=new Contact($key); */
		/* 	print_r($cont->data); */
		/*       } */
		/*       print "Candidates from ----------------------|\n"; */
		/*     } */

		//    print_r($this->candidate);
		foreach ($this->candidate as $key => $value) {

			if ($value>=200) {
				// print "$value $key ################x#######\n";
				$this->found=true;
				$this->found_key=$key;
				break;
			}


		}
		if (!$this->found) {
			$tmp=$data;
			unset($tmp['Contact Name']);
			if (array_empty($tmp)) {
				foreach ($this->candidate as $key => $value) {
					if ($value>=100) {
						$this->found=true;
						$this->found_key=$key;
						break;
					}

				}
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
		//    print $options."---------------------------------------------------\n";
		//   print_r($raw_data);

		$find_type='complete';
		if (preg_match('/fuzzy/i',$options)) {
			$find_type='fuzzy';
		}
		elseif (preg_match('/fast/i',$options)) {
			$find_type='fast';
		}


		if (isset($raw_data['editor']) and is_array($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {

				if (array_key_exists($key,$this->editor))
					$this->editor[$key]=$value;

			}
		}
		$create='';
		$update='';
		$parent=false;
		$parent_key=false;
		if (preg_match('/create/i',$options)) {
			$create='create';
		}
		if (preg_match('/update/i',$options)) {
			$update='update';
		}

		$data=$this->base_data();



		$address_home_data=array(
			'Contact Home Address Line 1'=>'',
			'Contact Home Address Town'=>'',
			'Contact Home Address Line 2'=>'',
			'Contact Home Address Line 3'=>'',
			'Contact Home Address Postal Code'=>'',
			'Contact Home Address Country Name'=>'',
			'Contact Home Address Country Code'=>'',
			'Contact Home Address Country First Division'=>'',
			'Contact Home Address Country Second Division'=>''
		);
		$address_work_data=array(
			'Contact Work Address Line 1'=>'',
			'Contact Work Address Town'=>'',
			'Contact Work Address Line 2'=>'',
			'Contact Work Address Line 3'=>'',
			'Contact Work Address Postal Code'=>'',
			'Contact Work Address Country Name'=>'',
			'Contact Work Address Country First Division'=>'',
			'Contact Work Address Country Code'=>'',
			'Contact Work Address Country Second Division'=>''
		);


		if (array_key_exists('Contact Name Components',$raw_data)
			and is_array($raw_data['Contact Name Components'])) {

			$options.=' components';
			foreach ($raw_data['Contact Name Components'] as $key=>$value) {
				$data[$key]=$value;
			}
		}

		if (preg_match('/from supplier/',$options)) {
			foreach ($raw_data as $key=>$val) {


				if (preg_match('/Supplier Address/i',$key)) {
					$_key=preg_replace('/Supplier Address/i','Contact Work Address',$key);
				} else
					$_key=preg_replace('/Supplier /i','Contact ',$key);

				if (array_key_exists($_key,$address_work_data))
					$address_work_data[$_key]=$val;
				$data[$_key]=$val;
			}
			$parent='supplier';

		}
		elseif (preg_match('/from customer|in customer/i',$options)) {
			foreach ($raw_data as $key=>$val) {


				if ($key=='Customer Main Contact Name') {
					$_key='Contact Name';
				} else if (preg_match('/Customer Address/i',$key)) {
						$_key=preg_replace('/Customer Address/i','Contact Home Address',$key);
					} else
					$_key=preg_replace('/Customer /','Contact ',$key);
				$data[$_key]=$val;


				// print "$key $_key \n";


				if (array_key_exists($_key,$address_home_data))
					$address_home_data[$_key]=$val;

				// print " $key -> $_key = $val \n";

			}
			$parent='customer';


		}



		elseif (preg_match('/from staff|in staff/i',$options)) {
			foreach ($raw_data as $key=>$val) {
				if (preg_match('/Staff Address/i',$key)) {
					$_key=preg_replace('/Staff Address/i','Contact Home Address',$key);
				} else
					$_key=preg_replace('/Staff /','Contact ',$key);
				$data[$_key]=$val;


				if (array_key_exists($_key,$address_home_data))
					$address_home_data[$_key]=$val;

				// print " $key -> $_key = $val \n";

			}
			$parent='staff';
		}
		elseif (preg_match('/from Company|in company/i',$options)) {
			foreach ($raw_data as $key=>$val) {
				if ($key=='Company Name') {
					$_key='Contact Company Name';
				}
				elseif ($key=='Company Main Contact Name') {
					$_key='Contact Name';
				}
				elseif (preg_match('/Company Address/i',$key)) {
					$_key=preg_replace('/Company Address/i','Contact Work Address',$key);
				}
				else
					$_key=preg_replace('/Company /','Contact ',$key);


				if (array_key_exists($_key,$data))
					$data[$_key]=$val;




				if (array_key_exists($_key,$address_work_data))
					$address_work_data[$_key]=$val;
			}
			$parent='company';
			$parent_key=0;

			if (preg_match('/(from Company|in company) \d+/i',$options,$match)) {
				$parent_key=preg_replace('/[^\d]/','',$match[0]);

			}


		}
		else {
			$parent='none';
			foreach ($raw_data as $key=>$val) {
				if (array_key_exists($key,$data))
					$data[$key]=$val;
			}

			foreach ($raw_data as $key=>$val) {
				if (array_key_exists($key,$address_home_data)) {
					$key2=preg_replace('/Contact Home /','',$key);
					//   $address_data['Home'][$key2]=$val;
					$address_home_data[$key]=$val;
				}
			}
			foreach ($raw_data as $key=>$val) {
				if (array_key_exists($key,$address_work_data)) {
					$address_work_data[$key]=$val;
					$key2=preg_replace('/Contact Work /','',$key);
					// $address_data['Work'][$key2]=$val;
				}
			}


		}
		// print " $find_type $options 8888888\n";
		//exit;
		$this->parent=$parent;
		$options.=' parent:'.$parent;


		switch ($find_type) {
		case 'fast':

			$this->find_fast($data['Contact Main Plain Email']);
			break;
		case 'complete':
			$this->find_complete($data,$parent,$parent_key,$address_home_data,$address_work_data);
			break;
		case 'fuzzy':
			$this->find_fuzzy($data,$parent,$parent_key,$address_home_data,$address_work_data);
			break;
		}







		if ($this->found) {
			$this->get_data('id',$this->found_key);

			// print "Contact found  ".$this->found_key." --->$create-----\n";
			//  print_r($this->data);
			//print_r($this->card());
		}

		if ($create and !$this->found) {
			$this->create($data,$address_home_data,$options);


		}


		if ($update and $this->found) {
			//             print "------> updating here ,----\n";
			//print_r($data);
			$data_to_update=array(
				'Contact Name'=>$data['Contact Name'],
				'Contact Old ID'=>$data['Contact Old ID'],

				'Contact Main Plain Mobile'=>$data['Contact Main Plain Mobile'],
				'Contact Main Plain Email'=>$data['Contact Main Plain Email']
			);

			$this->update($data_to_update,$options);
			if ($main_home_address_key=$this->get_main_home_address_key()) {
				$this->update_address($main_home_address_key,$address_home_data);
			}
			$this->get_data('id',$this->id);


		}



	}


	/* Method: check_others
       Look for similar contacts in the Database

       Try to match a contact with the data provided if not found any candidate return 0

       Parameter:
       $data  -     array        Data to be compared with the contacts in the database

       Return:
       integer  - $contact_key   integer     Contact Key of the most probable match or 0 if no match found
    */
	function check_others($data) {

		$weight=array(
			'Same Other ID'=>100
			,'Same Email'=>100
			,'Similar Email'=>20
		);


		if ($data['Contact Email']!='') {
			$has_email=true;
			$sql=sprintf("select `Email Key` from `Email Dimension` where `Email`=%s",prepare_mysql($data['Contact Email']));
			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$email_key=$row['Email Key'];
				$sql=sprintf("select `Subject Key` from `Email Bridge` where `Email Key`=%s and `Subject Type`='Contact'",prepare_mysql($email_key));
				$result2=mysql_query($sql);
				if ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)) {
					// Email found assuming this is th contact
					$contact_key=$row2['Subject Key'];
					return $contact_key;
				}
			}
		} else
			$has_email=false;

		$telephone=Telephone::display(Telephone::parse_telecom(array('Telecom Original Number'=>$data['Telephone']),$data['Country Key']));
		$contact_name= $this->parse_name($data['Name']);
		// Email not found check if we have a mantch in other id
		if ($data['Customer Other ID']!='') {
			$no_other_id=false;
			$sql=sprintf("select `Contact Key`,`Contact Name`,`Contact Main XHTML Telephone ` from `Customer Dimension` CD left join `Contact Bridge` CB on (CB.`Subject Key`=CD.`Customer Key`)  where `Subject Type`='Customer' and `Customer Other ID`=%s",prepare_mysql($data['Customer Other ID']));
			$result=mysql_query($sql);
			$num_rows = mysql_num_rows($result);
			if ($num_rows==1) {
				$row=mysql_fetch_array($result, MYSQL_ASSOC);
				$contact_key=$row2['Contact Key'];
				return $contact_key;
			}
			elseif ($num_rows>1) {
				// Get the candidates

				while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
					$this->candidate[$row['Contact Key']]['field']=array('Contact Other ID');
					$this->candidate[$row['Contact Key']]['points']=$weight['Same Other ID'];
					// from this candoateed of one has the same name we wouls assume that this is the one
					if ($contact_name!='' and $contact_name==$row['Contact Name'])
						return $row2['Contact Key'];
					if ($telephone!='' and $telephone==$row['Contact Main XHTML Telephone'])
						return $row2['Contact Key'];


				}




			}
		} else
			$no_other_id=true;




		//If contact has the same name ond same address
		//$addres_finger_print=preg_replace('/[^\d]/','',$data['Full Address']).$data['Address Town'].$data['Postal Code'];


		//if thas the same name,telephone and address get it





		if ($has_email) {
			//Get similar candidates from email

			$sql=sprintf("select damlevlim256(UPPER(%s),UPPER(`Email`),6) as dist1,damlevlim256(UPPER(SOUNDEX(%s)),UPPER(SOUNDEX(`Email`)),6) as dist2, `Subject Key`  from `Email Dimension` left join `Email Bridge` on (`Email Bridge`.`Email Key`=`Email Dimension`.`Email Key`)  where dist1<=2 and  `Subject Type`='Contact'   order by dist1,dist2 limit 20"
				,prepare_mysql($data['Contact Email'])
				,prepare_mysql($data['Contact Email'])
			);
			$result=mysql_query($sql);
			while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$this->candidate[$row['Subject Key']]['field'][]='Contact Other ID';
				$dist=0.5*$row['dist1']+$row['dist2'];
				if ($dist==0)
					$this->candidate[$row['Subject Key']]['points']+=$weight['Same Other ID'];
				else
					$this->candidate[$row['Subject Key']]['points']=$weight['Similar Email']/$dist;

			}
		}


		//Get similar candidates from emailby name
		if ($data['Contact Name']!='') {
			$sql=sprintf("select damlev(UPPER(%s),UPPER(`Contact Name`)) as dist1,damlev(UPPER(SOUNDEX(%s)),UPPER(SOUNDEX(`Contact Name`))) as dist2, `Contact Key`  from `Contact Dimension`   where dist1<=3 and  `Subject Type`='Contact'   order by dist1,dist2 limit 20"
				,prepare_mysql($data['Contact Name'])
				,prepare_mysql($data['Contact Name'])
			);
			$result=mysql_query($sql);
			while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$this->candidate[$row['Subject Key']]['field'][]='Contact Name';
				$dist=0.5*$row['dist1']+$row['dist2'];
				if ($dist==0)
					$this->candidate[$row['Subject Key']]['points']+=$weight['Same Contact Name'];
				else
					$this->candidate[$row['Subject Key']]['points']=$weight['Similar Contact Name']/$dist;

			}
		}
		// Address finger print


	}




	function create($data,$address_home_data='',$options='',$scope=false,$scope_parent_key=false) {




		global $myconf;
		if (is_string($data))
			$data['Contact Name']=$data;





		$this->data=$this->base_data();
		foreach ($data as $key=>$value) {
			if (array_key_exists($key,$this->data))
				$this->data[$key]=_trim($value);
		}

		$email=false;
		if ($this->data['Contact Main Plain Email']!='') {
			$email=$this->data['Contact Main Plain Email'];
			$this->data['Contact Main Plain Email']='';
		}



		$telephone=$this->data['Contact Main Plain Telephone'];
		$fax=$this->data['Contact Main Plain FAX'];
		$mobile=$this->data['Contact Main Plain Mobile'];

		$this->data['Contact Main XHTML FAX']='';
		$this->data['Contact Main XHTML Telephone']='';
		$this->data['Contact Main Plain FAX']='';
		$this->data['Contact Main Plain Telephone']='';
		$this->data['Contact Main Plain Mobile']='';



		if (!preg_match('/components/i',$options)) {





			$parsed_data=$this->parse_name($this->data['Contact Name']);
			foreach ($parsed_data as $key=>$val) {
				if (array_key_exists($key,$this->data))
					$this->data[$key]=$val;
			}
		}


		//if ($this->data['Contact Old ID']) {
		//  $this->data['Contact Old ID']=",".$this->data['Contact Old ID'].",";
		// }

		$prepared_data=$this->prepare_name_data($this->data);
		foreach ($prepared_data as $key=>$val) {
			if (array_key_exists($key,$this->data))
				$this->data[$key]=$val;
		}
		$this->data['Contact Name']=$this->display('name');
		if (!preg_match('/gender confirmed|gender ok/i',$options))
			$this->data['Contact Gender']=$this->gender($this->data);
		if (!preg_match('/grettings confirmed|grettings ok/i',$options)) {
			$this->data['Contact Informal Greeting']=$this->display('informal gretting');
			$this->data['Contact Formal Greeting']=$this->display('formal gretting');
		}




		if ($this->data['Contact Name']=='') {
			$this->data['Contact Informal Greeting']=$this->unknown_informal_greting;
			$this->data['Contact Formal Greeting']=$this->unknown_formal_greting;
		}



		$this->data['Contact File As']=$this->display('file_as');
		//        $this->data['Contact ID']=0;


		if ($this->data['Contact Name']==$this->unknown_contact_name) {
			$this->data['Contact Fuzzy']='Yes';
		} else
			$this->data['Contact Fuzzy']='No';


		if ($this->data['Contact First Name']=='Mr')
			exit("error with salitation");


		$keys='(';
		$values='values(';
		foreach ($this->data as $key=>$value) {
			// Just insert name fields, company,email,tel,ax,address should be inserted later
			if (preg_match('/fuzzy| id| Salutation|Contact Name|file as|First Name|Surname|Suffix|Gender|Greeting|Profession|Title| plain/i',$key)) {

				$keys.="`$key`,";
				if (preg_match('/suffix|plain|old id|Identification Number/i',$key))
					$print_null=false;
				else
					$print_null=true;
				$values.=prepare_mysql($value,$print_null).",";
			}
		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);

		$sql=sprintf("insert into `Contact Dimension` %s %s",$keys,$values);

		if (mysql_query($sql)) {
			$this->id= mysql_insert_id();

			if (_trim($this->data['Contact Old ID'])) {
				$sql=sprintf("insert into `Contact Old ID Bridge` values (%d,%s)",$this->id,prepare_mysql(_trim($this->data['Contact Old ID'])));
				mysql_query($sql);
			}


			$this->get_data('id',$this->id);
			$history_data=array(
				'History Abstract'=>_('Contact Created')
				,'History Details'=>_trim(_('New contact')." \"".$this->display('name')."\"  "._('added'))
				,'Action'=>'created'
			);
			$this->add_history($history_data);
			$this->new=true;



			if ($this->data['Contact Fuzzy']=='No') {
				$history_data=array(
					'History Abstract'=>_('Contact Created'),
					'History Details'=>_trim(_('Contact')." \"".$this->display('name')."\"  "._('created')),
					'Action'=>'created'
				);
				$this->add_history($history_data);
			}



			// Has no parent add emails,tels ect to the contact
			if ($email) {

				$email_data['Email']=$email;
				$email_data['Email Contact Name']=$this->display('name');
				$email_data['editor']=$this->editor;

				$this->email_added=false;
				$email=new Email('find create',$email_data);
				if ($email->id) {

					if ($scope=='customer') {
						$sql=sprintf("select `Customer Key` from `Email Bridge`  left join `Customer Dimension` on (`Customer Key`=`Subject Key`) where  `Subject Type`='Customer' and `Email Key`=%s and `Customer Store Key`=%d",
							$email->id,
							$scope_parent_key
						);
						$res=mysql_query($sql);
						if ($row=mysql_fetch_array($res)) {
							$this->error=true;
							$this->msg='Email already in store';
						}

					}else {
						$this->associate_email($email->id);
					}



				}

			}

			$home_address_key=0;




			if (is_array($address_home_data) and !array_empty($address_home_data)) {



				$address_home_data['editor']=$this->editor;
				$home_address=new Address("find in contact ".$this->id." $options create update",$address_home_data);
				$home_address->editor=$this->editor;
				if ($home_address->error) {
					print $home_address->msg."\n";
					exit("find_contact: home address found\n");
				}
				//print_r($address_home_data);
				//print_r($home_address);
				$this->associate_address($home_address->id);

				$home_address_key=$home_address->id;

			}


			//print "***** address: $home_address_key \n";


			$telephone_keys=array();
			$fax_keys=array();
			$mobile_keys=array();


			if (  ($telephone!='' or $fax!='' )and !$home_address_key) {
				$home_address=new Address("find create update",array('Address Country Code'=>'UNK','editor'=>$this->editor));
				$home_address_key=$home_address->id;

			}


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


			if ($mobile!='') {

				$mobile_data=array();
				$mobile_data['editor']=$this->editor;
				$mobile_data['Telecom Raw Number']=$mobile;
				$mobile_data['Telecom Type']='mobile';
				//print_r($home_address);
				if (isset($home_address)) {
					$country_code=$home_address->data['Address Country Code'];
				} else
					$country_code='UNK';
				$mobile=new Telecom("find in company fast create country code $country_code",$mobile_data);
				$mobile->editor=$this->editor;
				if (!$mobile->error) {

					$mobile_keys[]=$mobile->id;


				}
			}



			if ($telephone!='') {
				$telephone_data=array();
				$telephone_data['editor']=$this->editor;
				$telephone_data['Telecom Raw Number']=$telephone;
				$telephone_data['Telecom Type']='Telephone';
				//print_r($home_address);
				$telephone=new Telecom("find in company fast create country code ".$home_address->data['Address Country Code'],$telephone_data);
				$telephone->editor=$this->editor;



				if (!$telephone->error) {
					// if ($telephone->is_mobile())
					//   $mobile_keys[]=$telephone->id;
					//else
					$telephone_keys[]=$telephone->id;

				}
			}


			if ($fax!='') {
				$telephone_data=array();
				$telephone_data['editor']=$this->editor;
				$telephone_data['Telecom Raw Number']=$fax;
				$telephone_data['Telecom Type']='FAX';

				$telephone=new Telecom("find in company fast create country code ".$home_address->data['Address Country Code'],$telephone_data);
				$telephone->editor=$this->editor;
				if (!$telephone->error) {

					//  if ($telephone->is_mobile()) {
					//    $mobile_keys[]=$telephone->id;


					//} else {
					$fax_keys[]=$telephone->id;

					//}

				}
			}





			foreach ($telephone_keys as $telecom_key) {
				// print "address $home_address_key =".$home_address->id." ; tel: $telecom_key\n";

				$home_address->associate_telecom($telecom_key,'Telephone');
			}

			foreach ($fax_keys as $telecom_key) {
				$home_address->associate_telecom($telecom_key,'FAX');
			}


			foreach ($mobile_keys as $telecom_key) {
				$this->associate_mobile($telecom_key);
			}







			$this->get_data('id',$this->id);
		} else {
			//print $sql;
			$this->msg=_("Error can not create contact");
			$this->new=false;
		}

	}

	/* Method: get
       Used to get properties of the class

       Try to match a contact with the data provided if not found any candidate return 0

       Parameter:
       $key  -     string        tag key of property to be returned
       $data -     mixed  extra data or output custimize options
       Return:
       mixed a object property
    */


	function get($key='',$data=false) {


		if (array_key_exists($key,$this->data))
			return $this->data[$key];


		switch ($key) {
		case("Salutation Key"):
			if ($data)
				$salutation=$data;
			else
				$salutation=$this->data['Contact Salutation'];

			$salutation_key=0;
			$sql=sprintf("Select `Salutation Key` from kbase.`Salutation Dimension` where `Salutation`=%s",prepare_mysql($salutation));
			$res=mysql_query($sql);
			if ($row=mysql_fetch_array($res)) {
				$salutation_key=$row['Salutation Key'];
			}
			return $salutation_key;
			break;
		case("ID"):
		case("Formated ID"):
			return $this->get_formated_id();


			break;


		case('has_email_id'):
			if (!$this->emails)
				$this->load('emails');
			return array_key_exists($data,$this->emails);
			break;
		case('main_email'):

			return $this->data['main']['email'];
			break;
		}

		exit("$key can not be found in contact class\n");

	}

	/* Method: add_company
       Assign company to to the Contact

       Search for an email record maching the email data *$data* if not found create a ne email record then add this record to the Contact


       Parameter:
       $data  -    array   contact data
       $args -     string  options


    */


	/* Method: update_main_data
       Update the redundant data

       From data directly from the bridge tables and update the apropiate fields

    */
	public function update_redundant_data($fields='all') {
		// get emails
		if (preg_match('/e?mails?/',$fields) or $fields=='all') {
			// get emails
			$sql=sprintf("select * from `Email Bridge` where `Subject Key`=%d and `Subject Type`='Contact' ",$this->id);

		}


	}





	function create_email_bridge($email_key) {
		$sql=sprintf("insert into  `Email Bridge` (`Email Key`,`Subject Type`, `Subject Key`,`Is Main`,`Email Description`) values (%d,'Contact',%d,'No','')  "
			,$email_key
			,$this->id
		);

		mysql_query($sql);
		$this->inserted_email=$email_key;
		if (!$this->get_principal_email_key()) {
			$this->update_principal_email($email_key);
		}
	}



	function update_principal_email($email_key) {
		$main_email_key=$this->get_principal_email_key();




		if ($main_email_key!=$email_key) {
			$email=new Email($email_key);

			if (!$email->id) {
				$this->error=true;
				$this->msg.='Email to set as principal not found';
				return;
			}

			$email->editor=$this->editor;
			$sql=sprintf("update `Email Bridge`  set `Is Main`='No' where `Subject Type`='Contact' and  `Subject Key`=%d ",
				$this->id

			);
			mysql_query($sql);
			$sql=sprintf("update `Email Bridge`  set `Is Main`='Yes' where `Subject Type`='Contact' and  `Subject Key`=%d  and `Email Key`=%d",
				$this->id
				,$email->id
			);
			mysql_query($sql);

			$sql=sprintf("update `Contact Dimension` set  `Contact Main Email Key`=%s where `Contact Key`=%d",$email->id,$this->id);
			$this->data['Contact Main Email Key']=$email->id;
			mysql_query($sql);
			$this->updated=true;
			$this->new_value=$email->display('plain');

			$this->update_parents_principal_email_keys();
			$email->new=$this->new;
			$email->update_parents();

		}

	}




	function associate_email_to_parents($parent,$parent_key,$email_key,$set_as_main=true) {


		if ($parent=='Customer') {
			$parent_object=new Customer($parent_key);
			$parent_label=_('Customer');
		}
		elseif ($parent=='Supplier') {
			$parent_object=new Supplier($parent_key);
			$parent_label=_('Supplier');
		}
		elseif ($parent=='Company') {
			$parent_object=new Company($parent_key);
			$parent_label=_('Company');
		}

		$parent_emails=$parent_object->get_email_keys();

		if (!array_key_exists($email_key,$parent_emails)) {
			$sql=sprintf("insert into  `Email Bridge` (`Email Key`,`Subject Type`, `Subject Key`,`Is Main`,`Email Description`) values (%d,'$parent',%d,'No','')  "
				,$email_key
				,$parent_object->id
			);
			mysql_query($sql);
		}
		//print "$sql\n";

		$old_principal_email_key=$parent_object->data[$parent.' Main Email Key'];
		if ($set_as_main and $old_principal_email_key!=$email_key) {

			$sql=sprintf("update `Email Bridge`  set `Is Main`='No' where `Subject Type`='$parent' and  `Subject Key`=%d ",
				$parent_object->id

			);
			mysql_query($sql);
			$sql=sprintf("update `Email Bridge`  set `Is Main`='Yes' where `Subject Type`='$parent' and  `Subject Key`=%d  and `Email Key`=%d",
				$parent_object->id
				,$email_key
			);
			mysql_query($sql);
			$sql=sprintf("update `$parent Dimension` set `$parent Main Email Key`=%d where `$parent Key`=%d"
				,$email_key
				,$parent_object->id
			);
			mysql_query($sql);
		}
	}


	function associate_mobile_to_parents($parent,$parent_key,$mobile_key,$set_as_main=true) {


		if ($parent=='Customer') {
			$parent_object=new Customer($parent_key);
			$parent_label=_('Customer');
		}
		elseif ($parent=='Supplier') {
			$parent_object=new Supplier($parent_key);
			$parent_label=_('Supplier');
		}
		elseif ($parent=='Company') {
			$parent_object=new Company($parent_key);
			$parent_label=_('Company');
		}

		$parent_mobiles=$parent_object->get_telecom_keys('Mobile');

		if (!array_key_exists($mobile_key,$parent_mobiles)) {
			$sql=sprintf("insert into  `Telecom Bridge` (`Telecom Key`,`Subject Type`, `Subject Key`,`Is Main`) values (%d,'$parent',%d,'No')  "
				,$mobile_key
				,$parent_object->id
			);
			mysql_query($sql);
		}
		//print "$sql\n";

		if ($parent=='Customer') {

			$old_principal_mobile_key=$parent_object->data[$parent.' Main Mobile Key'];
			if ($set_as_main and $old_principal_mobile_key!=$mobile_key) {

				$sql=sprintf("update `Mobile Bridge`  set `Is Main`='No' where `Subject Type`='$parent' and  `Subject Key`=%d ",
					$parent_object->id

				);
				mysql_query($sql);
				$sql=sprintf("update `Mobile Bridge`  set `Is Main`='Yes' where `Subject Type`='$parent' and  `Subject Key`=%d  and `Mobile Key`=%d",
					$parent_object->id
					,$mobile_key
				);
				mysql_query($sql);
				$sql=sprintf("update `$parent Dimension` set `$parent Main Mobile Key`=%d where `$parent Key`=%d"
					,$mobile_key
					,$parent_object->id
				);
				mysql_query($sql);
			}
		}

	}



	function update_parents_principal_email_keys() {
		$email_key=$this->data['Contact Main Email Key'];
		//print $this->data['Contact Main Email Key']."<-----\n";
		if (!$email_key)
			return;
		$parents=array('Company','Customer','Supplier');
		foreach ($parents as $parent) {
			$sql=sprintf("select `$parent Key` as `Parent Key`   from  `$parent Dimension` where `$parent Main Contact Key`=%d group by `$parent Key`",$this->id);
			//   print "$sql\n";

			$res=mysql_query($sql);
			while ($row=mysql_fetch_array($res)) {

				$this->associate_email_to_parents($parent,$row['Parent Key'],$email_key);

			}
		}
	}


	function update_parents_principal_mobile_keys() {
		$mobile_key=$this->data['Contact Main Mobile Key'];
		if (!$mobile_key)
			return;
		//$parents=array('Company','Customer','Supplier');
		$parents=array('Customer');
		foreach ($parents as $parent) {
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

				if (!array_key_exists($mobile_key,$parent_telecoms)) {
					$sql=sprintf("insert into  `Telecom Bridge` (`Telecom Key`,`Subject Type`, `Subject Key`,`Is Main`,`Is Active`) values (%d,'$parent',%d,'No','Yes')  "
						,$mobile_key
						,$parent_object->id
					);
					mysql_query($sql);
				}
				//print "$sql\n";

				$old_principal_mobile_key=$parent_object->data[$parent.' Main Mobile Key'];
				if ($old_principal_mobile_key!=$mobile_key) {

					$sql=sprintf("update `Telecom Bridge`  B left join `Telecom Dimension` T on (T.`Telecom Key`=B.`Telecom Key`)  set `Is Main`='No' where `Subject Type`='$parent' and  `Subject Key`=%d and `Telecom Type`='Mobile'  ",
						$parent_object->id

					);
					mysql_query($sql);
					$sql=sprintf("update `Telecom Bridge`  set `Is Main`='Yes' where `Subject Type`='$parent' and  `Subject Key`=%d  and `Telecom Key`=%d",
						$parent_object->id
						,$mobile_key
					);
					mysql_query($sql);
					$sql=sprintf("update `$parent Dimension` set `$parent Main Mobile Key`=%d where `$parent Key`=%d"
						,$mobile_key
						,$parent_object->id
					);
					mysql_query($sql);
					//print "$sql\n";



				}
			}
		}
	}

	function update_parents_principal_telephone_keys() {
		$telephone_key=$this->data['Contact Main Telephone Key'];
		if (!$telephone_key)
			return;
		//$parents=array('Company','Customer','Supplier');
		$parents=array('Customer');
		foreach ($parents as $parent) {
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
		foreach ($parents as $parent) {
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

	function get_number_emails() {
		$sql=sprintf("select count(`Email Key`) as num from `Email Bridge` where `Subject Type`='Contact' and `Subject Key`=%d ",$this->id );
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$num_emails=$row['num'];
		} else {
			$num_emails=0;
		}

		return $num_emails;

	}

	function get_email_keys() {
		$sql=sprintf("select `Email Key` from `Email Bridge` where  `Subject Type`='Contact' and `Subject Key`=%d "
			,$this->id );

		$emails=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$emails[$row['Email Key']]= $row['Email Key'];
		}
		return $emails;

	}

	function get_emails() {
		$sql=sprintf("select `Email Key`,`Is Main` from `Email Bridge` where  `Subject Type`='Contact' and `Subject Key`=%d order by `Is Main`"
			,$this->id );

		$emails=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$email=new Email($row['Email Key']);
			$email->data['Email Is Main']=$row['Is Main'];
			$emails[$row['Email Key']]= $email;
		}
		return $emails;

	}


	function get_principal_email_key() {

		$sql=sprintf("select `Email Key` from `Email Bridge` where `Subject Type`='Contact' and `Subject Key`=%d and `Is Main`='Yes'",$this->id );
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$main_email_key=$row['Email Key'];
		} else {
			$main_email_key=0;
		}

		return $main_email_key;
	}
	function get_principal_company_key() {

		$sql=sprintf("select `Company Key` from `Company Bridge` where `Subject Type`='Contact' and `Subject Key`=%d and `Is Main`='Yes'",$this->id );
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$main_company_key=$row['Company Key'];
		} else {
			$main_company_key=0;
		}

		return $main_company_key;
	}









	function update_principal_company($company_key) {

		$main_company_key=$this->get_principal_company_key();

		if ($main_company_key!=$company_key) {
			$company=new Company($company_key);
			$company->editor=$this->editor;
			$company->new=$this->new;

			$sql=sprintf("update `Company Bridge`  set `Is Main`='No' where `Subject Type`='Contact' and  `Subject Key`=%d ",
				$this->id
			);
			mysql_query($sql);

			$sql=sprintf("INSERT INTO  `Company Bridge`  (`Company Key`,`Subject Type`,`Subject Key`,`Is Main`,`Is Active`)
                         value (%d,'Contact',%d,'Yes','Yes')   ON DUPLICATE KEY UPDATE
                         `Is Main`='Yes',`Is Active`='Yes' ",
				$company->id,
				$this->id
			);
			mysql_query($sql);

			$sql=sprintf("update `Contact Dimension` set  `Contact Company Key`=%d where `Contact Key`=%d",$company->id,$this->id);
			mysql_query($sql);

			$this->data['Contact Company Key']=$company->id;
			$company->update_children();

		}

	}




	function update_parents($add_parent_history=true) {

		$parents=array('Company','Customer','Supplier','Staff');
		foreach ($parents as $parent) {

			if ($parent=='Staff') {
				$col_contact_key="Staff Contact Key";
				$col_contact_name="Staff Name";
			} else {
				$col_contact_key="$parent Main Contact Key";
				$col_contact_name="$parent Main Contact Name";
			}
			$sql=sprintf("select `$parent Key` as `Parent Key`   from  `$parent Dimension` where `$col_contact_key`=%d group by `$parent Key`",$this->id);
			$res=mysql_query($sql);
			while ($row=mysql_fetch_array($res)) {
				$principal_contact_changed=false;

				if ($parent=='Customer') {
					$parent_object=new Customer($row['Parent Key']);
					$parent_label=_('Customer');
				}
				elseif ($parent=='Supplier') {
					$parent_object=new Supplier($row['Parent Key']);
					$parent_label=_('Supplier');
				}
				elseif ($parent=='Company') {
					$parent_object=new Company($row['Parent Key']);
					$parent_label=_('Company');
				}
				elseif ($parent=='Staff') {
					$parent_object=new Staff($row['Parent Key']);
					$parent_label=_('Staff');
				}
				$parent_object->editor=$this->editor;
				$old_principal_contact=$parent_object->data[$col_contact_name];
				$parent_object->data[$col_contact_name]=$this->display('name');
				$sql=sprintf("update `$parent Dimension` set `$col_contact_name`=%s where `$parent Key`=%d"
					,prepare_mysql($parent_object->data[$col_contact_name])
					,$parent_object->id
				);
				mysql_query($sql);

				if ($parent=='Customer' and $parent_object->data['Customer Type']=='Person') {
					$sql=sprintf("update `Customer Dimension` set `Customer Name`=%s, `Customer File As`=%s  where `Customer Key`=%d"
						,prepare_mysql($this->display('name'))
						,prepare_mysql($this->data['Contact File As'])
						,$parent_object->id
					);
					mysql_query($sql);

				}

				if ($old_principal_contact!=$parent_object->data[$col_contact_name])
					$principal_contact_changed=true;


				if ($principal_contact_changed and $add_parent_history) {

					if ($old_principal_contact=='') {

						$history_data['History Abstract']='Contact Associated '.$this->display('name');
						$history_data['History Details']=$this->display('name')." "._('associated with')." ".$parent_object->get_name()." ".$parent_label;
						$history_data['Action']='associated';
						$history_data['Direct Object']='Contact';
						$history_data['Direct Object Key']=$this->id;
						$history_data['Indirect Object']=$parent;
						$history_data['Indirect Object Key']=$parent_object->id;


					} else {

						if ($this->display('name')=='') {
							$history_data['History Abstract']='Main Contact set to Unknown';
							$history_data['History Details']=_('Contact changed from').' '.$old_principal_contact.' '._('to an unknown name in')." ".$parent_object->get_name()." ".$parent_label;

						} else {

							$history_data['History Abstract']='Main Contact Changed to '.$this->display('name');
							$history_data['History Details']=_('Contact changed from').' '.$old_principal_contact.' '._('to').' '.$this->display('name')." "._('in')." ".$parent_object->get_name()." ".$parent_label;
						}
						$history_data['Action']='changed';
						$history_data['Direct Object']=$parent;
						$history_data['Direct Object Key']=$parent_object->id;
						$history_data['Indirect Object']='Contact Main Name';
						$history_data['Indirect Object Key']='';


					}
					if ($parent=='Customer') {
						$parent_object->add_customer_history($history_data);
					} else {
						$this->add_history($history_data);
					}

				}

				/*
                                $this->update_parents_principal_email_keys();
                                $email=new Email($this->get_principal_email_key());
                                $email->editor=$this->editor;
                                $email->new=$this->new;
                                if ($email->id)
                                    $email->update_parents($add_parent_history);

                 */

			}
		}
	}






	function move_home_to_work_address($address_key) {
		$address=new address($address_key);
		if (!$address->id) {
			$this->error=true;
			$this->msg='Wrong address key when trying to move it';
			$this->msg_updated='Wrong address key when trying to move it';

			return;
		}

		$address->set_scope('Contact',$this->id);
		if ( $address->associated_with_scope) {

			$sql=sprintf("update `Address Bridge` set `Address Type`='Work' where `Address Key`=%d and `Subject Key`=%d and `Address Type`='Home' and `Subject Type`='Contact' "
				,$address->id
				,$this->id
			);
			mysql_query($sql);
			if (mysql_affected_rows()) {
				$history_data['Action']='edited';
				$history_data['Direct Object']='Contact';
				$history_data['Direct Object Key']=$this->id;
				$history_data['Indirect Object']='Address';
				$history_data['Indirect Object Key']=$address->id;
				$history_data['History Abstract']='Contact Address Type Updated';
				$history_data['History Details']='Contact Home Address changed to Work Address';
				$this->add_history($history_data);
			}

		}

	}


	/* Method: remove_address
       Delete the address from Contact

       Delete telecom record  this record to the Contact


       Parameter:
       $args -     string  options
    */
	function remove_address($address_key=false,$options='') {


		if (!$address_key) {
			$address_key=$this->data['Contact Main Address Key'];
		}


		$address=new address($address_key);
		if (!$address->id) {
			$this->error=true;
			$this->msg='Wrong address key when trying to remove it';
			$this->msg_updated='Wrong address key when trying to remove it';
			return;
		}






		$address->set_scope('Contact',$this->id);
		if ( $address->associated_with_scope) {

			$this->updated=true;
			$this->msg_updated=_('Address Deleted');
		}


		if ($address->id==$this->data['Contact Main Address Key']) {
			$sql=sprintf("select `Address Key` from `Address Bridge` where `Subject Key`=%d and `Subject Type`='Contact' and `Address Description`=%s and `Address Key`!=%d "
				,$this->id
				,prepare_mysql($address->data['Address Description'])
				,$address_key
			);

			$res=mysql_query($sql);
			if ($row=mysql_fetch_array($res)) {

				$sql=sprintf('update `Address Bridge` set `Is Main`="Yes" where  `Subject Key`=%d and `Subject Type`="Contact" and `Address Description`=%s and `Address Key`=%d  '
					,$this->id
					,prepare_mysql($address->data['Address Description'])
					,$address_key
				);;
				mysql_query($sql);
				$this->update_address_data($row['Address Key']);

				// if ($company_key=$this->company_key('principal')) {
				//     $company=new Company('id',$company_key);
				//    $company->editor=$this->editor;
				//   $company->update_address($row['Address Key']);
				//  $company->remove_address($address->id);

				// $customer_found_keys=$company->get_customer_keys();
				// foreach($customer_found_keys as $customer_found_key) {
				//    $customer=new Customer($customer_found_key);
				//   $customer->editor=$this->editor;
				//  $customer->update_address($row['Address Key']);
				//  $customer->remove_address($address->id);
				//  }
				//}



			} else {
				$sql=sprintf("update `Contact Dimension` set `Contact Main XHTML Address`='' ,`Contact Main Plain Address`='' , `Contact Main Address Key`='',`Company Main Country Key`='244' ,`Company Main Country`='Unknown',`Company Main Location`='' ,`Company Main Country Code`='UNK'  where `Contact Key`=%d"
					,$this->id
				);
				mysql_query($sql);

			}

		}

		$sql=sprintf("delete from `Address Bridge` where `Subject Key`=%d and `Subject Type`='Contact'  and `Address Key`=%d ",$this->id,$address->id);
		mysql_query($sql);









	}

	function update_address($address_key,$data) {

		$address=new Address($address_key);
		if (!$address->id) {
			$this->error=true;
			return;
		}
		$address_keys=$this->get_address_keys();
		if (!array_key_exists($address->id,$address_keys)) {
			$this->error=true;
			$this->msg='Address not associated with company';
		}

		foreach ($data as $key=>$value) {
			$key=preg_replace('/^Contact (Home|Work) /i','',$key);
			$_address_data[$key]=$value;
		}

		$address->editor=$this->editor;
		$address->update($_address_data);// Address Object would update the address not normal data;


	}




	function update_address_data($address_key=false) {



		if (!$address_key)
			return;
		$address=new Address($address_key);
		if (!$address->id)
			return;

		if ($address->id!=$this->data['Contact Main Address Key']) {
			$old_value=$this->data['Contact Main Address Key'];
			$this->data['Contact Main Address Key']=$address->id;
			$this->data['Contact Main Plain Address']=$address->display('plain');
			$this->data['Contact Main XHTML Address']=$address->display('xhtml');

			$this->data['Contact Main Country Key']=$address->data['Address Country Key'];
			$this->data['Contact Main Country']=$address->data['Address Country Name'];
			$this->data['Contact Main Country Code']=$address->data['Address Country Code'];

			$this->data['Contact Main Location']=$address->display('location');


			$sql=sprintf("update `Contact Dimension` set `Contact Main Address Key`=%d,`Contact Main Plain Address`=%s,`Contact Main XHTML Address`=%s,`Contact Main Country`=%s,`Contact Main Country Code`=%s,`Contact Main Location`=%s,`Contact Main Country Key`=%d where `Contact Key`=%d"

				,$this->data['Contact Main Address Key']
				,prepare_mysql($this->data['Contact Main Plain Address'])
				,prepare_mysql($this->data['Contact Main XHTML Address'])
				,prepare_mysql($this->data['Contact Main Country'])
				,prepare_mysql($this->data['Contact Main Location'])
				,prepare_mysql($this->data['Contact Main Country Code'])

				,$this->data['Contact Main Country Key']
				,$this->id
			);
			//print "XX $address_key $sql\n";
			if (mysql_query($sql)) {

				$note=_('Address Changed');
				if ($old_value) {
					$old_address=new Address($old_value);
					$details=_('Contact address changed from')." \"".$old_address->display('xhtml')."\" "._('to')." \"".$this->data['Contact Main XHTML Address']."\"";
				} else {
					$details=_('Contact address set to')." \"".$this->data['Contact Main XHTML Address']."\"";
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
		elseif ($address->display('plain')!=$this->data['Contact Main Plain Address']
			or $address->display('location')!=$this->data['Contact Main Location']
		) {
			$old_value=$this->data['Contact Main XHTML Address'];


			$this->data['Contact Main Plain Address']=$address->display('plain');
			$this->data['Contact Main XHTML Address']=$address->display('xhtml');
			$this->data['Contact Main Country Key']=$address->data['Address Country Key'];
			$this->data['Contact Main Country']=$address->data['Address Country Name'];
			$this->data['Contact Main Location']=$address->display('location');


			$sql=sprintf("update `Contact Dimension` set `Contact Main Plain Address`=%s,`Contact Main XHTML Address`=%s,`Contact Main Country`=%s,`Contact Main Location`=%s,`Contact Main Country Key`=%d where `Contact Key`=%d"


				,prepare_mysql($this->data['Contact Main Plain Address'])
				,prepare_mysql($this->data['Contact Main XHTML Address'])
				,prepare_mysql($this->data['Contact Main Country'])
				,prepare_mysql($this->data['Contact Main Location'])
				,$this->data['Contact Main Country Key']
				,$this->id
			);
			if (mysql_query($sql)) {
				$field='Contact Address';
				$note=$field.' '._('Changed');
				$details=$field.' '._('changed from')." \"".$old_value."\" "._('to')." \"".$this->data['Contact Main XHTML Address']."\"";

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





	/*
      Function: create_anonymous
      Create an anonymous contact
    */

	private function create_anonymous($raw_data,$options='') {
		global $myconf;


		if (isset($raw_data['editor']) and is_array($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {

				if (array_key_exists($key,$this->editor))
					$this->editor[$key]=$value;

			}
		}







		$this->data['Contact Fuzzy']='Yes';
		$this->data['Contact Name']=$this->unknown_contact_name;
		$this->data['Contact Informal Greeting']=$this->unknown_informal_greting;
		$this->data['Contact Formal Greeting']=$this->unknown_formal_greting;
		$this->data['Contact File As']=$this->display('file_as');
		// $this->data['Contact ID']=0;//$this->get_new_id();






		$sql="INSERT INTO `dw`.`Contact Dimension` (`Contact Salutation`, `Contact Name`, `Contact File As`, `Contact First Name`, `Contact Surname`, `Contact Suffix`, `Contact Gender`, `Contact Informal Greeting`, `Contact Formal Greeting`, `Contact Profession`, `Contact Title`, `Contact Company Name`, `Contact Company Key`, `Contact Company Department`, `Contact Company Department Key`, `Contact Manager Name`, `Contact Manager Key`, `Contact Assistant Name`, `Contact Assistant Key`, `Contact Main Address Key`, `Contact Main Location`, `Contact Main XHTML Address`, `Contact Main Plain Address`, `Contact Main Country Key`, `Contact Main Country`, `Contact Main Country Code`, `Contact Main XHTML Telephone `, `Contact Main Plain Telephone`, `Contact Main Telephone Key`, `Contact Main XHTML Mobile `, `Contact Main Plain Mobile`, `Contact Main Mobile Key`, `Contact Main XHTML FAX `, `Contact Main Plain FAX`, `Contact Main FAX Key`, `Contact Main XHTML Email`, `Contact Main Plain Email`, `Contact Main Email Key`, `Contact Fuzzy`) VALUES ('NULL', ".prepare_mysql($this->data['Contact Name']).",".prepare_mysql($this->data['Contact File As']).", 'NULL',NULL, NULL, 'Unknown',".prepare_mysql($this->data['Contact Informal Greeting']).",".prepare_mysql($this->data['Contact Formal Greeting']).", NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, '', NULL, NULL, '', NULL, NULL, '', NULL, NULL, '', NULL, 'Yes');";
		if (mysql_query($sql)) {
			$this->id= mysql_insert_id();
			$this->new=true;
			$this->get_data('id',$this->id);




		} else {
			$this->msg="Error can not create anonymous contact";
			$this->new=false;
		}

	}


	function associate_address($address_key) {

		$address_keys=$this->get_address_keys();

		if (!array_key_exists($address_key,$address_keys)) {
			$this->create_address_bridge($address_key);

			$this->updated=true;
			$this->new_data=$address_key;


		}


	}

	function get_principal_address_key() {

		$sql=sprintf("select `Address Key` from `Address Bridge` where `Subject Type`='Contact' and `Subject Key`=%d and `Is Main`='Yes'",$this->id );
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$main_address_key=$row['Address Key'];
		} else {
			$main_address_key=0;
		}

		return $main_address_key;
	}
	function create_address_bridge($address_key) {
		$sql=sprintf("insert into `Address Bridge` (`Subject Type`,`Subject Key`,`Address Key`) values ('Contact',%d,%d)  ",
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
			$sql=sprintf("update `Address Bridge`  set `Is Main`='No' where `Subject Type`='Contact' and  `Subject Key`=%d  and `Address Key`=%d",
				$this->id
				,$main_address_key
			);
			mysql_query($sql);
			$sql=sprintf("update `Address Bridge`  set `Is Main`='Yes' where `Subject Type`='Contact' and  `Subject Key`=%d  and `Address Key`=%d",
				$this->id
				,$address->id
			);
			mysql_query($sql);

			$sql=sprintf("update `Contact Dimension` set  `Contact Main Address Key`=%s where `Contact Key`=%d",$address->id,$this->id);
			$this->data['Contact Main Address Key']=$address->id;
			mysql_query($sql);

			//$this->update_parents_principal_address_keys();

			// print "upac\n";
			// print_r($this->editor);
			$address->update_parents();
			//print "end aa\n";
			$this->updated=true;
			$this->new_value=$address_key;
		}

	}


	/* Method: add_address
       Add/Update an address to the Contact

       Search for an address record maching the address data *$data* if not found create a ne address record then add this record to the Contact


       Parameter:
       $data  -    array   address data
       $args -     string  options
       Return:
       integer address key of the added/updated address
    */

	function old_associate_address($data,$args='') {

		// print_r($data);

		$this->updated=false;

		$principal=preg_match('/principal/i',$args);
		if (count($this->get_address_keys())==0)
			$principal=true;

		$address_key=$data['Address Key'];
		//if ($address->updated or $address->new)
		//   $this->updated=true;



		foreach ($data['Address Type'] as $type) {
			foreach ($data['Address Function'] as $function) {

				$sql=sprintf("insert into `Address Bridge` (`Subject Type`,`Subject Key`,`Address Key`,`Address Type`,`Address Function`) values ('Contact',%d,%d,%s,%s)  ON DUPLICATE KEY UPDATE `Is Active`='Yes'",
					$this->id
					,$address_key
					,prepare_mysql($type)
					,prepare_mysql($function)

				);
				mysql_query($sql);
				if (mysql_affected_rows() ) {
					$this->updated=true;


				}

			}
		}





		if ($principal ) {

			$sql=sprintf("update `Address Bridge`  set `Is Main`='No' where `Subject Type`='Contact' and  `Subject Key`=%d  and `Address Key`!=%d",
				$this->id
				,$address_key
			);
			mysql_query($sql);
			$sql=sprintf("update `Address Bridge`  set `Is Main`='Yes' where `Subject Type`='Contact' and  `Subject Key`=%d  and `Address Key`=%d",
				$this->id
				,$address_key
			);

			mysql_query($sql);

			$this->update_address_data($address_key);
			// whe have to update

		}



	}


	public function update($data,$options='') {

		if (isset($data['editor'])) {
			foreach ($data['editor'] as $key=>$value) {

				if (array_key_exists($key,$this->editor))
					$this->editor[$key]=$value;

			}
		}



		$base_data=$this->base_data();

		foreach ($data as $key=>$value) {


			if (preg_match('/^(Address.*Data|Contact Main Email Key|Contact Main Telphone Key|Contact Main Mobile Key|Contact Name Components|Other Email)$/',$key)) {

				$this->update_field_switcher($key,$value,$options);


			}
			elseif (array_key_exists($key,$base_data)) {

				if ($value!=$this->data[$key]) {
					//print "xxx $key,$value,$options\n";
					$this->update_field_switcher($key,$value,$options);

				}
			}
		}


		if (!$this->updated)
			$this->msg.=' '._('Nothing to be updated')."\n";
	}


	/*Function: update_field_switcher
      Custom update switcher
    */

	protected function update_field_switcher($field,$value,$options='') {
		//print "$field,$value,$options\n";
		switch ($field) {

		case('Contact Tax Number'):
			$this->update_field($field,$value,$options);
			if ($this->updated) {
				$this->update_parents_tax_number();
			}
			break;
		case('Contact Identification Number'):
			$this->update_field($field,$value,$options);
			if ($this->updated) {
				$this->update_parents_registration_number();
			}
			break;

		case('Contact Main Plain FAX'):
		case('Contact Main Plain Telephone'):

			if ($field=='Contact Main Plain Telephone')
				$type='Telephone';
			else
				$type='FAX';
			$address=new Address($this->data['Contact Main Address Key']);


			$address->editor=$this->editor;
			if ($value=='') {
				if ($telecom_key=$address->get_principal_telecom_key($type)) {
					$telecom=new Telecom($telecom_key);
					$telecom->delete();
					if ($telecom->deleted) {
						$this->updated=true;
						$this->new_value='';
					}
				}
			} else {

				if ($address->get_principal_telecom_key($type)) {

					$address->update_principal_telecom_number($value,$type);
				} else {
					$telephone_data=array();
					$telephone_data['editor']=$this->editor;
					$telephone_data['Telecom Raw Number']=$value;
					$telephone_data['Telecom Type']=$type;
					//$telephone=new Telecom("find in contact create country code ".$address->data['Address Country Code'],$telephone_data);
					$telephone=new Telecom('new',$telephone_data);
					$address->associate_telecom($telephone->id,$type);
				}
				$this->updated=$address->updated;
				if ($this->updated) {
					$this->get_data('id',$this->id);
					$this->new_value=$this->data['Contact Main XHTML '.$type];
				}
			}

			break;


		case('Contact Main Mobile Key'):
			exit("????  shouls i delete this????");
			$this->update_mobile($value);
			break;
		case('Contact Main Telephone Key'):

			$this->update_telephone($value);
			break;
		case('Contact Main FAX Key'):
			$this->update_fax($value);
			break;
		case('Contact Name Components'):
			//$this->update_telephone($value);
			// break;

			$this->update_Contact_Name_Components($value,$options);
			break;
		case('Contact Name'):
			$old_value=$this->display('name');
			$value=_trim($value);
			if ($value=='') {
				$this->msg.=_('Warning, contact name should not be blank')."\n";
				$this->warning=true;
				$this->data['Contact Informal Greeting']=$this->unknown_informal_greting;
				$this->data['Contact Formal Greeting']=$this->unknown_formal_greting;
			}

			$parsed_data=$this->parse_name($value);
			foreach ($parsed_data as $key=>$val) {
				if (array_key_exists($key,$this->data))
					$this->data[$key]=$val;
			}
			$this->data['Contact Name']=$this->display('name');
			$this->update_Contact_Name($old_value);
			break;
		case('Add Other Mobile'):

			if ($value=='')return;



			$mobile_data=array();
			$mobile_data['editor']=$this->editor;
			$mobile_data['Telecom Raw Number']=$value;
			$mobile_data['Telecom Type']='Mobile';
			//$mobile=new Telecom("find in contact $options create country code ".$this->data['Contact Main Country Code'],$mobile_data);
			$mobile=new Telecom('new',$mobile_data);
			if ($mobile->id) {
				$this->associate_mobile($mobile->id);
				$this->other_mobile_key=$mobile->id;
				$this->updated=true;
				$this->new_value=$mobile->display('xhtml');
			} else {
				$this->msg=$mobile->msg;
				$this->error=true;
			}


			break;

		case('Add Other Email'):
			if ($value=='')return;
			$email_data['Email']=$value;
			$email_data['Email Contact Name']=$this->display('name');
			$email_data['editor']=$this->editor;
			$email=new Email('find create',$email_data);
			if ($email->id) {
				$this->associate_email($email->id);


				$this->other_email_key=$email->id;
				$this->updated=true;
				$this->new_value=$email->data['Email'];
			} else {
				$this->msg=$email->msg;
				$this->other_email_key=0;
			}

			break;

		case('Contact Main Plain Email'):

			$main_email_key=$this->get_principal_email_key();

			if ($value=='') {
				if ($main_email_key) {
					$email=new Email($main_email_key);
					$email->delete();
					$this->updated=true;
					$this->new_value='';
				}
			} else {
				$email_data['Email']=$value;
				$email_data['Email Contact Name']=$this->display('name');
				$email_data['editor']=$this->editor;
				$email=new Email('find',$email_data);
				$email=new Email('email',$value);

				//print_r($email_data);
				//print_r($email);

				//         if ($email->found) {



				//  $this->update_principal_email($email->id);
				//         return;

				/*
                    $email_contacts_keys=$email->get_parent_keys('Contact');

                    $number_contact_keys=count($email_contacts_keys);
                    if ($number_contact_keys==1) {

                        $contact_data=array_pop($email_contacts_keys);

                        if ($contact_data['Subject Key']!=$this->id) {
                            $this->error=true;
                            $this->msg=_('Email belongs to other contact');
                            $contact=new Contact($contact_data['Subject Key']);
                            $this->msg=_('Email belongs to contact').' <a href="contact.php?id='.$contact->id.'">'.$contact->display('name').'</a>';
                            return;
                        } else {
                            // print "updating principal email\n";
                            $this->update_principal_email($email->id);
                            return;
                        }


                    }
                    elseif($number_contact_keys>1) {
                      $this->error=true;
                            $this->msg=_('Error an email bellows to more than one contacts');
                            return;

                    }

                    */
				//  }




				if ($main_email_key) {
					$email=new email($main_email_key);
					$email->editor=$this->editor;

					$email->update_Email($value);
					$this->updated=$email->updated;
					$this->new_value=$email->new_value;
					$this->msg=$email->msg;

				} else {
					if (!$email->id)
						$email=new Email('find create',$email_data);

					if ($email->id)
						$this->associate_email($email->id);



				}



			}

			break;
		case('Contact Main XHTML Telephone'):
			dsdsdsdsdsdd();

			break;
		case('Contact Main XHTML FAX'):
			dasdasdasds();

			break;

		case('Contact Old ID'):
			$this->update_Contact_Old_ID($value,$options);
			break;

		case('Contact Main XHTML Mobile'):
			dadsds();

			break;
		case('Home Address'):

			break;
		case('Contact Main Plain Mobile'):
			$main_mobile_key=$this->get_principal_mobile_key();


			if ($main_mobile_key) {


				if ($value=='') {
					$telecom=new Telecom($main_mobile_key);
					//print_r($telecom);
					$telecom->delete();

					$this->updated=$telecom->deleted;
					if ($this->updated) {
						$this->get_data('id',$this->id);
						$this->new_value='';
					}
				} else {


					$mobile=new Telecom($main_mobile_key);
					$mobile->editor=$this->editor;
					$mobile->update_number($value,$this->data['Contact Main Country Code']);
					$this->updated=$mobile->updated;
				}

			} else {
				$mobile_data=array();
				$mobile_data['editor']=$this->editor;
				$mobile_data['Telecom Raw Number']=$value;
				$mobile_data['Telecom Type']='Mobile';
				//$mobile=new Telecom("find in contact $options create country code ".$this->data['Contact Main Country Code'],$mobile_data);
				$mobile=new Telecom('new',$mobile_data);
				if ($mobile->id) {
					$this->associate_mobile($mobile->id);
				}

			}
			//print_r($mobile);
			//


			break;

		default:
			$base_data=$this->base_data();
			if (array_key_exists($field,$base_data)) {
				$this->update_field($field,$value,$options);
			}
		}

	}

	/*Method:update_Contact_Name_Components
      Update contact name


    */

	function update_Contact_Name_Components($data,$options='') {


		$old_full_name=$this->data['Contact Name'];
		foreach ($data as $key=>$value) {
			$this->update_field($key,$value,$options);
		}
		$new_full_name=$this->display('name');
		if ($old_full_name!=$new_full_name)
			$this->update_Contact_Name($new_full_name,$options);


	}

	/*Method:parse_update_Contact_Name
      Update contact name


    */

	function update_Contact_Name($old_value) {







		$this->data['Contact Name']=$this->display('name');
		$this->data['Contact Informal Greeting']=$this->display('informal gretting');
		$this->data['Contact Formal Greeting']=$this->display('formal gretting');



		$this->data['Contact File As']=$this->display('file_as');

		$values='';
		foreach ($this->data as $key=>$value) {
			if (preg_match('/Contact Name|Contact File As|Greeting|Salutation|First Name|Surname|Suffix/i',$key)) {

				$values.=" `$key`=";
				if (preg_match('/suffix|plain/i',$key))
					$print_null=false;
				else
					$print_null=true;
				$values.=prepare_mysql($value,$print_null).",";
			}
		}
		$values=preg_replace('/,$/',' ',$values);

		$sql=sprintf("update `Contact Dimension` set %s where `Contact Key`=%d",$values,$this->id);
		// print $sql;
		mysql_query($sql);
		$affected=mysql_affected_rows();
		if ($affected==-1) {
			$this->msg=_('Contact name can not be updated')."\n";
			$this->error=true;
			return;
		}
		elseif ($affected==0) {
			//$this->msg=_('Same value as the old record');


		} else {

			$this->msg=_('Contact name updated');
			$this->updated=true;
			$this->new_value=$this->data['Contact Name'];
			$history_data=array(
				'History Abstract'=>_('Contact Name Changed'),
				'History Details'=>_trim(_('Contact name changed from').' '.$old_value." "._('to')." ".$this->data['Contact Name']),
				'Indirect Object'=>'Name',
				'Action'=>'edited',
			);
			$this->add_history($history_data);



			$this->update_parents();


		}

	}



	/*Method:parse_update_Contact_Name
      Update contact name


    */

	function old_parse_update_Contact_Name($data,$options='') {
		global $myconf;

		$parsed_data=$this->parse_name($data);
		foreach ($parsed_data as $key=>$val) {
			if (array_key_exists($key,$this->data))
				$this->data[$key]=$val;
		}
		$this->data['Contact Name']=$this->display('name');

		if ($data=='') {
			$this->msg.=_('Warning, contact name should not be blank')."\n";
			$this->warning=true;
			$this->data['Contact Name']=$this->unknown_contact_name;
			$this->data['Contact Informal Greeting']=$this->unknown_informal_greting;
			$this->data['Contact Formal Greeting']=$this->unknown_formal_greting;
			//    $this->data['Contact Gender']=$this->gender($this->data);
		} else {

			$this->data['Contact Name']=$this->display('name');
			$this->data['Contact File As']=$this->display('file_as');
			//$this->data['Contact Gender']=$this->gender($this->data);
			$this->data['Contact Informal Greeting']=$this->display('informal gretting');
			$this->data['Contact Formal Greeting']=$this->display('formal gretting');

		}
		$this->data['Contact File As']=$this->display('file_as');
		$values='';
		foreach ($this->data as $key=>$value) {
			if (preg_match('/Salutation|Contact Name|Contact File As|First Name|Surname|Suffix|Greeting/i',$key)) {

				$values.=" `$key`=";
				if (preg_match('/suffix|plain/i',$key))
					$print_null=false;
				else
					$print_null=true;
				$values.=prepare_mysql($value,$print_null).",";
			}
		}
		$values=preg_replace('/,$/',' ',$values);

		$sql=sprintf("update `Contact Dimension` set %s where `Contact Key`=%d",$values,$this->id);
		//print $sql;
		mysql_query($sql);
		$affected=mysql_affected_rows();
		if ($affected==-1) {
			$this->msg=_('Contact name can not be updated')."\n";
			$this->error=true;
			return;
		}
		elseif ($affected==0) {
			//$this->msg=_('Same value as the old record');

		} else {

			$this->msg=_('Contact name updated');
			$this->updated=true;
			$this->new_value=$this->display();

			//updating parents


			$sql=sprintf("select `Customer Key` as `Subject Key`  from `Customer Dimension` where `Customer Main Contact Key`=%d;",$this->id);
			$res=mysql_query($sql);
			while ($row=mysql_fetch_array($res)) {

				$customer=new Customer($row['Subject Key']);
				$customer->editor=$this->editor;
				$customer->update_main_contact_name($this->display());
				if ($customer->data['Customer Type']=='Person') {

					$customer->update_name($this->display());
				}
			}
			mysql_free_result($res);




		}

	}


	function get_contact_old_id() {
		$old_ids=array();
		$sql=sprintf("select `Contact Old ID` from `Contact Old ID Bridge` where `Contact Key`=%d",$this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$old_ids[$row['Contact Old ID']]=$row['Contact Old ID'];
		}
		return $old_ids;

	}

	/* Function:update_Contact_Old_ID
       Updates the contact old id

    */
	private function update_Contact_Old_ID($contact_old_id,$options) {
		$contact_old_id=_trim($contact_old_id);
		if ($contact_old_id=='') {
			$this->new=false;
			$this->msg.=" Contact Old ID name should have a value";
			$this->error=true;
			if (preg_match('/exit on errors/',$options))
				exit($this->msg);
			return false;
		}

		$old_value=$this->data['Contact Old ID'];
		$individual_ids=array();


		$individual_ids=$this->get_contact_old_id();



		if (array_key_exists($contact_old_id, $individual_ids)) {
			$this->msg.=' '._('Contact Old ID already in record')."\n";
			$this->warning=true;
			return;
		}

		$sql=sprintf("insert into `Contact Old ID Bridge` values (%d,%s)",$this->id,prepare_mysql(_trim($this->data['Contact Old ID'])));
		mysql_query($sql);


		$affected=mysql_affected_rows();

		if ($affected==-1) {
			$this->msg.=' '._('Contact Old ID  can not be updated')."\n";
			$this->error=true;
			return;
		}
		elseif ($affected==0) {
			//$this->msg.=' '._('Same value as the old record');

		} else {
			$this->msg.=' '._('Record updated')."\n";
			$this->updated=true;



		}

	}



	function parse_address($data) {
		$address_data=array(
			'Street Number'=>'',
			'Street Name'=>'',
			'Street Type'=>'',
			'Street Direction'=>'',
			'Post Box'=>'',
			'Suite'=>'',
			'City Subdivision'=>'',
			'City Division'=>'',
			'City'=>'',
			'District'=>'',
			'Second District'=>'',
			'State'=>'',
			'Region'=>'',
			'Address Country Key'=>'',
			'Address Country Name'=>'',
			'Address Country Code'=>'',
			'Address Country 2 Alpha Code'=>'',
			'Address World Region'=>'',
			'Address Continent'=>'',
			'Postal Code'=>'',
			'First Postal Code'=>'',
			'Second Postal Code'=>'',
			'Postal Code Separator'=>'',
			'Fuzzy Address'=>''
		);

		return $address_data;
	}
	/* Method: prepare_name_data
       Clean the Name data array
    */

	public static function prepare_name_data($raw_data) {

		if (isset($raw_data['Contact Salutation']))
			$data['Contact Salutation']=_trim($raw_data['Contact Salutation']);
		if (isset($raw_data['Contact First Name']))
			$data['Contact First Name']=_trim($raw_data['Contact First Name']);
		else
			$data['Contact First Name']='';
		if ( isset($raw_data['Contact Middle Name']))
			$data['Contact First Name'].=_trim(' '.$raw_data['Contact Middle Name']);

		if (isset($raw_data['Contact Surname']))
			$data['Contact Surname']=_trim($raw_data['Contact Surname']);
		if (isset($raw_data['Contact Suffix']))
			$data['Contact Suffix']=_trim($raw_data['Contact Suffix']);

		$data['Contact Gender']='Unknown';
		if (isset($raw_data['Contact Gender']) and ($raw_data['Contact Gender']=='Male' or $raw_data['Contact Gender']=='Female'))
			$data['Contact Gender']=_trim($raw_data['Contact Gender']);

		if ($data['Contact Gender']=='Unknown')
			$data['Contact Gender']=Contact::gender($raw_data);


		return $data;

	}

	/* Method: parse_name
       Parse a name detecting its components

       Parameter:
       string with the name to be parsed

       Returns:
       Array with the name componets: Contact Salutation, Contact First Name, Contact Surname, Contact Suffix

    */
	public static function parse_name($raw_name) {

		//  print "--> $raw_name\n";

		//fix common mistakes

		$raw_name=preg_replace('/\s*(\.|\,)\s*$/i','',$raw_name);
		//$raw_name=preg_replace('/^mrs\.?\s+/i','Mrs ',$raw_name);




		$name=array(
			'prefix'=>'',
			'first'=>'',
			'middle'=>'',
			'last'=>'',
			'suffix'=>'',
			'alias'=>''
		);

		$raw_name=_trim($raw_name);
		if (preg_match('/^(ann?a|rosa) mar(i|í)a$/i',$raw_name)) {
			$name['first']=$raw_name;
		} else {



			$forbiden_names=array('sir/madam','sir,madam');

			if (in_array(strtolower($raw_name),$forbiden_names))
				$raw_name='';
			if (Contact::is_prefix($raw_name))
				$raw_name='';



			$raw_name=_trim($raw_name);
			$raw_name=preg_replace('/\./',' ',$raw_name);
			$names=preg_split('/\s+/',$raw_name);

			//  print_r($names);




			if (Contact::is_prefix($names[0])) {
				$name['prefix']=array_shift($names);

				//   print_r($names);


			}

			$parts=count($names);


			switch ($parts) {
			case(1):
				if (Contact::is_surname($names[0]))
					$name['last']=$names[0];
				else if (Contact::is_givenname($names[0]))
						$name['first']=$names[0];
					else if (Contact::is_prefix($names[0]))
							$name['prefix']=$names[0];
						else
							$name['first']=$names[0];
						break;
				case(2):
					// firt the most obious choise

					if (Contact::is_givenname($names[0])) {
						$name['first']=$names[0];
						$name['last']=$names[1];


					} else if (Contact::is_givenname($names[0]) and   Contact::is_surname($names[1])) {
						$name['first']=$names[0];
						$name['last']=$names[1];

					} else if ( Contact::is_prefix($names[0]) and   Contact::is_surname($names[1])) {
						$name['prefix']=$names[0];
						$name['last']=$names[1];
					} else if ( Contact::is_prefix($names[0]) and   Contact::is_givenname($names[1])) {
						$name['prefix']=$names[0];
						$name['first']=$names[1];
					} else if ( Contact::is_surname($names[0]) and   Contact::is_surname($names[1])) {
						$name['last']=$names[0].' '.$names[1];
					} else {
					$name['first']=$names[0];
					$name['last']=$names[1];

				}
				break;
			case(3):
				// firt the most obious choise


				if (!Contact::is_prefix($names[0]) and  strlen($names[1])==1   and   strlen($names[2])>1  ) {
					$name['first']=$names[0];
					$name['middle']=$names[1];
					$name['last']=$names[2];
				}
				elseif ( Contact::is_prefix($names[0])) {
					$name['prefix']=$names[0];
					$name['first']=$names[1];
					$name['last']=$names[2];


					//  if(   Contact::is_givenname($names[1]) and   Contact::is_surname($names[2])){

					//    $name['first']=$names[1];
					//    $name['last']=$names[2];
					//  }else if(    strlen($names[1])==1 and   Contact::is_surname($names[2])){

					//    $name['first']=$names[1];
					//    $name['last']=$names[2];
					//  }else if(   Contact::is_givenname($names[1])    and   Contact::is_givenname($names[2])){

					//    $name['first']=$names[1].' '.$names[2];
					//  }else if(  Contact::is_surname($names[1])    and   Contact::is_surname($names[2])){

					//    $name['last']=$names[1].' '.$names[2];
					//  }else{
					//    $name['first']=$names[1];
					//    $name['last']=$names[2];

					//  }


				}
				else if (  Contact::is_givenname($names[0])   and   Contact::is_givenname($names[1])  and   Contact::is_surname($names[2])) {
						$name['first']=$names[0].' '.$names[1];
						$name['last']=$names[2];
					} else if (  Contact::is_givenname($names[0])   and   Contact::is_surname($names[1])  and   Contact::is_surname($names[2])) {
						$name['first']=$names[0];
						$name['last']=$names[1].' '.$names[2];
					} else if ( Contact::is_givenname($names[0]) and     strlen($names[1])==1 and   Contact::is_surname($names[2])) {
						$name['first']=$names[0];
						$name['middle']=$names[1];
						$name['last']=$names[2];
					} else {
					$name['first']=$names[0];
					$name['last']=$names[1].' '.$names[2];
				}
				break;
			case(4):



				if ( Contact::is_prefix($names[0])) {
					$name['prefix']=$names[0];

					if (  Contact::is_givenname($names[1]) and    strlen($names[2])==1 and  Contact::is_surname($names[3])) {

						$name['first']=$names[1];
						$name['middle']=$names[2];
						$name['last']=$names[3];
					} else if (  Contact::is_givenname($names[1]) and   Contact::is_givenname($names[2])  and  Contact::is_surname($names[3])) {

							$name['first']=$names[1].' '.$names[2];
							$name['last']=$names[3];
						} else if ( Contact::is_prefix($names[0]) and     Contact::is_givenname($names[1]) and   Contact::is_surname($names[2])  and  Contact::is_surname($names[3])) {

							$name['first']=$names[1];
							$name['last']=$names[2].' '.$names[3];

						} else
						$name['first']=$names[1].' '.$names[2];
					$name['last']=$names[3];


					// firt the most obious choise
				} else if (      Contact::is_givenname($names[0])  and    strlen($names[1])==1 and  Contact::is_surname($names[2])  and  Contact::is_surname($names[3])     ) {

						$name['first']=$names[0];
						$name['middle']=$names[1];
						$name['last']=$names[2].' '.$names[3];
					} else if (      Contact::is_givenname($names[0]) and Contact::is_givenname($names[1]) and    Contact::is_surname($names[2])  and  Contact::is_surname($names[3])     ) {

						$name['first']=$names[0].' '.$names[1];
						$name['last']=$names[2].' '.$names[3];
					} else  if (      Contact::is_givenname($names[0]) and Contact::is_givenname($names[1]) and    Contact::is_givenname($names[2])  and  Contact::is_surname($names[3])     ) {

						$name['first']=$names[0].' '.$names[1].' '.$names[2];
						$name['last']=$names[3];
					} else {
					$name['first']=$names[0];
					$name['last']=$names[1].' '.$names[2].' '.$names[3];
				}
				break;
			case(5):
				if ( Contact::is_prefix($names[0]) and     Contact::is_givenname($names[1]) and   Contact::is_givenname($names[2])   and  Contact::is_surname($names[3]) and Contact::is_surname($names[4])  ) {
					$name['prefix']=$names[0];
					$name['first']=$names[1].' '.$names[2];
					$name['first']=$names[3].' '.$names[4];
				} else
					$name['last']=join(' ',$names);
				break;
			default:
				$name['last']=join(' ',$names);

			}

		}





		$data['Contact Salutation']=_trim($name['prefix']);
		$data['Contact First Name']=_trim($name['first'].' '.$name['middle']);
		$data['Contact Surname']=_trim($name['last']);
		$data['Contact Suffix']=_trim($name['suffix']);

		//print_r($data);
		return $data;



	}


	function get_name() {
		return $this->name($this->data);

	}

	/*Function:display

     */

	public function display($tipo='name') {

		global $myconf;

		switch ($tipo) {
		case('card_principal'):
			$email_label="E:";
			$tel_label="T:";
			$fax_label="F:";
			$mobile_label="M:";

			$email='';
			$company='';
			$tel='';
			$fax='';
			$mobile='';
			$name=sprintf('<span class="name">%s</span>',$this->data['Contact Name']);
			if ($this->data['Contact Company Key'])
				$company=sprintf('<span class="company">%s</span><br/>',$this->data['Contact Company Name']);





			if ($this->data['Contact Main XHTML Email'])
				$email=sprintf('<span class="email">%s %s</span><br/>',$email_label,$this->data['Contact Main XHTML Email']);
			if ($this->data['Contact Main XHTML Telephone'])
				$tel=sprintf('<span class="tel">%s %s</span><br/>',$tel_label,$this->data['Contact Main XHTML Telephone']);
			if ($this->data['Contact Main XHTML FAX'])
				$fax=sprintf('<span class="fax">%s %s</span><br/>',$fax_label,$this->data['Contact Main XHTML FAX']);
			if ($this->data['Contact Main XHTML Mobile'])
				$mobile=sprintf('<span class="mobile">%s %s</span><br/>',$mobile_label,$this->data['Contact Main XHTML Mobile']);




			$address=sprintf('<span class="mobile">%s</span>',$this->data['Contact Main XHTML Address']);
			$card=sprintf('<div class="contact_card">%s <div  class="tels">%s %s %s %s %s</div><div  class="address">%s</div> </div>'
				,$name
				,$company
				,$email
				,$tel
				,$fax
				,$mobile
				,$address
			);

			return $card;



			break;
		case('card'):


			$email_label="E:";
			$tel_label="T:";
			$fax_label="F:";
			$mobile_label="M:";

			$email='';
			$company='';
			$tel='';
			$fax='';
			$mobile='';
			$name=sprintf('<span class="name">%s</span>',$this->data['Contact Name']);
			if ($this->data['Contact Company Key'])
				$company=sprintf('<span class="company">%s</span><br/>',$this->data['Contact Company Name']);

			$email='';

			$emails=$this->get_emails();
			$number_emails=count($emails);
			foreach ($emails as $email_object) {
				if ($email_object->data['Email Is Main']=='Yes' and $number_emails>1) {
					$main_tag='&#9733; ';
				} else {
					$main_tag='';
				}
				$email.=sprintf('%s<span class="email">%s</span><br/>',$main_tag,$email_object->display());
			}



			if ($this->data['Contact Main XHTML Telephone'])
				$tel=sprintf('<span class="tel">%s %s</span><br/>',$tel_label,$this->data['Contact Main XHTML Telephone']);
			if ($this->data['Contact Main XHTML FAX'])
				$fax=sprintf('<span class="fax">%s %s</span><br/>',$fax_label,$this->data['Contact Main XHTML FAX']);


			$mobile='';
			$mobiles=$this->get_mobiles();
			$number_mobiles=count($mobiles);



			foreach ($mobiles as $mobile_object) {
				if ($mobile_object->data['Mobile Is Main']=='Yes' and $number_mobiles>1) {
					$main_tag='&#9733; ';
				} else {
					$main_tag='';
				}
				$mobile.=sprintf('%s %s <span class="mobile">%s</span><br/>',$main_tag,$mobile_label,$mobile_object->display());
			}



			$address=sprintf('<span class="mobile">%s</span>',$this->data['Contact Main XHTML Address']);
			$card=sprintf('<div class="contact_card">%s <div  class="tels">%s %s %s %s %s</div><div  class="address">%s</div> </div>'
				,$name
				,$company
				,$email
				,$tel
				,$fax
				,$mobile
				,$address
			);

			return $card;

		case('Short Name'):
			$name=_trim($this->data['Contact Salutation'].' '.$this->data['Contact Surname']);
			if ($name=='')
				$name=$this->name($this->data);
			return $name;
			break;
		case('Name'):
		case('name'):
			return $this->get_name();

			break;
		case('file_as'):
		case('file as'):
			if ($this->data['Contact Fuzzy']=='Yes')
				$name=_trim($this->data['Contact Name']);
			else
				$name=_trim($this->data['Contact Surname'].' '.$this->data['Contact First Name']);
			return $name;


		case('informal gretting'):

			$gretting=_('Hello').' ';
			$name=_trim($this->data['Contact First Name']);
			$name=preg_replace('/\s+/',' ',$name);
			if (strlen($name)>1 and !preg_match('/^[a-z] [a-z]$/i',$name) and  !preg_match('/^[a-z] [a-z] [a-z]$/i',$name)  )
				return $gretting.$name;



			if (strlen($this->data['Contact Surname'])>1) {
				$name=_trim(
					$this->data['Contact Salutation'].' '.$this->data['Contact Surname']
				);


				$name=preg_replace('/\s+/',' ',$name);
				return $gretting.$name;
			}

			return $this->unknown_informal_greting;


		case('formal gretting'):
			$gretting=_('Dear').' ';
			if (strlen($this->data['Contact Surname'])>1) {

				if ($this->data['Contact Salutation']!='') {
					$name=_trim($this->data['Contact Salutation'].' '.$this->data['Contact Surname']);
					return $gretting.$name;
				}
				elseif ($this->data['Contact First Name']!='') {
					$name=_trim($this->data['Contact First Name'].' '.$this->data['Contact Surname']);
					return $gretting.$name;
				}
			}
			return $this->unknown_formal_greting;

		}

		return false;

	}
	/*function: name

     */
	public static function name($data) {
		global $myconf;
		// if (array_empty($data))
		//  $name=$this->unknown_contact_name;
		//else
		$name=_trim($data['Contact Salutation'].' '.$data['Contact First Name'].' '.$data['Contact Surname'].' '.$data['Contact Suffix']);



		return $name;

	}




	/*
      Method: gender
      Guess the gender from the name components

      Parameter:
      array with keys *Contact Salutation* and *Contact First Name*

      Return:
      Male,Felame,Unknown

    */

	public static function gender($data) {

		$prefix=$data['Contact Salutation'];
		$first_name=$data['Contact First Name'];
		$sql=sprintf("select `Gender` from  kbase.`Salutation Dimension`  where `Salutation`=%s ",prepare_mysql($prefix));
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			if ($row['Gender']=='Male' or $row['Gender']=='Female')
				return $row['Gender'];
		}


		$male=0;
		$felame=0;
		$names=preg_split('/\s+/',$first_name);
		foreach ($names as $name) {
			$sql=sprintf("select `Gender` as genero from  kbase.`First Name Dimension` where `First Name`=%s",prepare_mysql($name));
			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

				if ($row['genero']=='Male')
					$male++;
				if ($row['genero']=='Felame')
					$felame++;
			}
		}
		if ($felame>$male)
			return 'Felame';
		else if ($male>$felame)
				return 'Male';
			else
				return 'Unknown';

	}
	/*
      Method: is_givenname
      Look for the First Name in the DB

      Parameter:
      string First Name

      Return:
      First Name Key of the First Name Dimension  DB record or 0 if not found

    */
	public static function is_givenname($name) {
		$sql=sprintf("select `First Name Key` as id from  kbase.`First Name Dimension` where `First Name`=%s",prepare_mysql($name));
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			return $row['id'];
		} else
			return 0;
	}
	/*
      Method: is_surname
      Look for the Surname in the DB

      Parameter:
      string Surname

      Return:
    Key of the Surname Dimension  DB record or 0 if not found

    */


	public static    function is_surname($name) {

		$sql=sprintf("select `Surname` as id from  kbase.`Surname Dimension` where `Surname`=%s",prepare_mysql($name));
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			return $row['id'];
		} else
			return 0;
	}

	/*
      Method: is_prefix
      Look for the saludation in the DB

      Parameter:
      string Saludation

      Return:
    Key of the Saludation Dimension  DB record or 0 if not found

    */

	public static function is_prefix($name) {
		$sql=sprintf("select `Salutation` as id from kbase.`Salutation Dimension`  where `Salutation`=%s",prepare_mysql($name));
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			return $row['id'];
		} else
			return 0;
	}

	/*
      Function: company_key
      Returns the key of the contact company

      Parameter:
      options - string, principal return company key only if this is  the principal cpntact

      Returns:
      the key of the contact company or false if contact has not company associates

    */
	public  function company_key($options='') {
		if (preg_match('/principal/',$options)) {
			$sql=sprintf("select `Subject Key` from `Contact Bridge` where `Subject Type`='Company' and `Is Main`='Yes' and `Contact Key`=%d",$this->id);
			$result=mysql_query($sql);

			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				return $row['Subject Key'];

			}
		} else {

			return $this->data['Contact Company Key'];

		}
		return false;

	}

	/*
      function: card
      Returns an array with the contact details
    */
	function card() {


		$card=array(
			'Contact Name'=>$this->data['Contact Name'],
			'Company Name'=>$this->data['Contact Company Name'],
			'Emails'=>array(),
			'Telephones'=>array(),
			'Addresses'=>array()
		);

		$sql=sprintf("select   ED.`Email`,ED.`Email Key`,EB.`Is Main`,EB.`Email Description`  from `Email Bridge` EB left join `Email Dimension` on (EB.`Email Key`=ED.`Email Key`) where `Subject Type`='Contact' and `Subject Key`=%d and `Is Active`='Yes' order by `Is Main` desc",$this->id);
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$card['Emails'][$row['Email Key']]=array(
				'Address'=>$row['Email'],
				'Description'=>$row['Email Description'],
				'Principal'=>$row['Is Main']
			);
		}
		$sql=sprintf("select TB.`Telecom Key`,TB.`Is Main`,TB.`Telecom Description`  from `Telecom Bridge`  where `Subject Type`='Contact' and `Subject Key`=%d and `Is Active`='Yes' order by `Is Main` desc",$this->id);
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$telecom=new Telecom($row['Telecom Key']);
			$card['Telephones'][$row['Telecom Key']]=array(
				'Number'=>$telecom->display(),
				'Description'=>$row['Telecom Description'],
				'Principal'=>$row['Is Main']
			);
		}

		$sql=sprintf("select AB.`Address Key`,AB.`Is Main` from `Address Bridge`  where `Subject Type`='Contact' and `Subject Key`=%d and `Is Active`='Yes' order by `Is Main` desc",$this->id);
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$telecom=new Address($row['Address Key']);
			$card['Addresses'][$row['Address Key']]=array(
				'Address'=>$address->display(),
				'Principal'=>$row['Is Main']
			);
		}



		return $card;

	}

	/*
      function:get_work_email
      Array with the data components of the work emails
    */
	function old_get_work_emails($company_key=false) {
		$emails=array();
		$in_company='';
		if ($company_key)
			$in_company=sprintf(" and `Auxiliary Key`=%s",$company_key);
		$sql=sprintf('select * from `Email Bridge` EB  left join `Email Dimension` E on E.`Email Key`=EB.`Email Key`  where `Subject Key`=%d and `Email Description`="Work" %s order by `Is Main` desc ',$this->id,$in_company);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res))
			$emails[]=array(
				'id'=>$row['Email Key']
				,'description'=>$row['Email Description']
				,'address'=>$row['Email']
			);
		return $emails;
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

	function get_work_faxes($company_key=false) {
		$faxes=array();
		$in_company='';
		if ($company_key)
			$in_company=sprintf(" and `Auxiliary Key`=%s",$company_key);
		$sql=sprintf('select * from `Telecom Bridge` TB  left join `Telecom Dimension` T on T.`Telecom Key`=TB.`Telecom Key`  where `Subject Key`=%d and `Telecom Type`="Office Fax" and `Subject Type`="Contact"  %s order by `Is Main` desc ',$this->id,$in_company);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$tel=new Telecom('id',$row['Telecom Key']);

			$faxes[]=array(
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
		return $faxes;
	}

	/*
      function:get_main_telephone_data
      Array with the data components of the main telephone
    */
	function get_main_telephone_data() {
		$telephone=array('Telecom Country Telephone Code'=>'','Telecom Area Code'=>'','Telecom Number'=>'','Telecom Extension'=>'');
		if ($this->data['Contact Main Telephone Key']) {
			$telecom=new Telecom($this->data['Contact Main Telephone Key']);
			$telephone['Telecom Country Telephone Code']=$telecom->data['Telecom Country Telephone Code'];
			$telephone['Telecom National Access Code']=$telecom->data['Telecom Area Code'];
			$telephone['Telecom Area Code']=$telecom->data['Telecom Area Code'];
			$telephone['Telecom Number']=$telecom->data['Telecom Number'];
			$telephone['Telecom Extension']=$telecom->data['Telecom Extension'];
		}
		return $telephone;
	}

	/*
      function:get_main_fax_data
      Array with the data components of the main fax
    */
	function get_main_fax_data() {
		$fax=array('Telecom Country Fax Code'=>'','Telecom Area Code'=>'','Telecom Number'=>'','Telecom Extension'=>'');
		if ($this->data['Contact Main FAX Key']) {
			$telecom=new Telecom($this->data['Contact Main FAX Key']);
			$fax['Telecom Country Telephone Code']=$telecom->data['Telecom Country Telephone Code'];
			$fax['Telecom National Access Code']=$telecom->data['Telecom Area Code'];
			$fax['Telecom Area Code']=$telecom->data['Telecom Area Code'];
			$fax['Telecom Number']=$telecom->data['Telecom Number'];

		}
		return $fax;
	}




	function get_main_mobile_data() {
		$mobile=array('Telecom Country Mobile Code'=>'','Telecom Area Code'=>'','Telecom Number'=>'','Telecom Extension'=>'');
		if ($this->data['Contact Main Mobile Key']) {
			$telecom=new Telecom($this->data['Contact Main Mobile Key']);
			$mobile['Telecom Country Telephone Code']=$telecom->data['Telecom Country Telephone Code'];
			$mobile['Telecom National Access Code']=$telecom->data['Telecom Area Code'];
			$mobile['Telecom Area Code']=$telecom->data['Telecom Area Code'];
			$mobile['Telecom Number']=$telecom->data['Telecom Number'];

		}
		return $mobile;
	}

	function get_main_home_address_key() {

		$sql=sprintf("select * from `Address Bridge` CB where   `Subject Type`='Contact' and `Subject Key`=%d  and `Address Type`='Home' order by `Is Main` desc  ",$this->id);
		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			return $row['Address Key'];
		} else
			return 0;


	}


	function get_address_keys() {


		$sql=sprintf("select * from `Address Bridge` CB where   `Subject Type`='Contact' and `Subject Key`=%d  group by `Address Key` order by `Is Main` desc  ",$this->id);
		$address_keys=array();
		$result=mysql_query($sql);

		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$address_keys[$row['Address Key']]= $row['Address Key'];
		}
		return $address_keys;

	}



	/*
      function:get_main_address_data
      Array with the data components of the main address
    */
	function get_main_address_data() {
		$address_data=array('Country Name'=>'','Town'=>'','Internal'=>'','Bulding'=>'','Street'=>'','Country First Division'=>'','Country Second Division'=>'');
		if ($this->data['Contact Main Address Key']) {
			$address=new Address($this->data['Contact Main Address Key']);
			$address_data['Town']=$address->data['Address Town'];
			$address_data['Postal Code']=$address->data['Address Postal Code'];
			$address_data['Country Name']=$address->data['Address Country Name'];
			$address_data['Internal']=$address->data['Address Internal'];
			$address_data['Building']=$address->data['Address Building'];
			$address_data['Street']=$address->display('street');


		}
		return $address_data;
	}
	/*
      function: get_customer_key
      Returns the Customer Key if the contact is one
    */
	function get_customer_keys() {
		$sql=sprintf("select `Subject Key` as `Customer Key` from `Contact Bridge` where `Subject Type`='Customer' and `Contact Key`=%d  ",$this->id);
		$customer_keys=array();
		//print $sql;
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$customer_keys[$row['Customer Key']]= $row['Customer Key'];

		}
		return $customer_keys;
	}
	function get_companies_keys() {
		$sql=sprintf("select `Company Key` from `Company Bridge` where `Subject Type`='Contact' and `Subject Key`=%d  ",$this->id);
		//print $sql;
		$company_keys=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$company_keys[$row['Company Key']]= $row['Company Key'];

		}
		return $company_keys;
	}
	function get_supplier_key() {
		$sql=sprintf("select `Supplier Key` from `Supplier Dimension` where `Supplier Main Contact Key`=%d  ",$this->id);
		$supplier_keys=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$supplier_keys[$row['Supplier Key']]= $row['Supplier Key'];

		}
		return $supplier_keys;
	}

	function get_staff_key() {
		$sql=sprintf("select `Staff Key` from `Staff Dimension` where `Staff Contact Key`=%d  ",$this->id);
		$staff_keys=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$staff_keys[$row['Staff Key']]= $row['Staff Key'];

		}
		return $staff_keys;
	}

	/*
      function: has_company
    */

	function has_company() {
		if ($this->data['Contact Company Key'])
			return true;
		else
			return false;

	}

	/*
      function:get_addresses
    */
	function get_addresses() {


		$sql=sprintf("select * from `Address Bridge` CB where   `Subject Type`='Contact' and `Subject Key`=%d  group by `Address Key` order by `Is Main`   ",$this->id);
		$addresses=array();
		$result=mysql_query($sql);

		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$address= new Address($row['Address Key']);
			$address->set_scope('Contact',$this->id);
			$addresses[]= $address;
		}
		return $addresses;

	}




	/*
      function: get_mobiles


    */

	function get_mobiles() {


		$sql=sprintf("select TB.`Telecom Key`,`Is Main` from `Telecom Bridge` TB   left join `Telecom Dimension` T on (T.`Telecom Key`=TB.`Telecom Key`) where `Telecom Type`='Mobile'    and `Subject Type`='Contact' and `Subject Key`=%d  group by TB.`Telecom Key` order by `Is Main`   ",$this->id);
		$mobiles=array();
		$result=mysql_query($sql);
		//print $sql;
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$mobile= new Telecom($row['Telecom Key']);
			$mobile->set_scope('Contact',$this->id);
			$mobiles[]= $mobile;
			$mobile->data['Mobile Is Main']=$row['Is Main'];

		}
		$this->number_mobiles=count($mobiles);
		return $mobiles;

	}

	function get_telephones() {
		$sql=sprintf("select TB.`Telecom Key`,`Is Main` from `Telecom Bridge` TB   left join `Telecom Dimension` T on (T.`Telecom Key`=TB.`Telecom Key`) where `Telecom Type`='Telephone'    and `Subject Type`='Contact' and `Subject Key`=%d  group by TB.`Telecom Key` order by `Is Main`   ",$this->id);
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

	function get_faxes() {
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

	/*function:get_formated_id_link
      Returns formated id_link
    */
	function get_formated_id_link() {

		return sprintf('<a href="contact.php?id=%d">%s</a>',$this->id, $this->get_formated_id());

	}



	/*function:get_formated_id
      Returns formated id
    */
	function get_formated_id() {
		global $myconf;
		$sql="select count(*) as num from `Contact Dimension`";
		$res=mysql_query($sql);
		$min_number_zeros=$myconf['contact_min_number_zeros_id'];
		if ($row=mysql_fetch_array($res)) {
			if (strlen($row['num'])-1>$min_number_zeros)
				$min_number_zeros=strlen($row['num'])-01;
		}
		if (!is_numeric($min_number_zeros))
			$min_number_zeros=4;

		return sprintf("%s%0".$min_number_zeros."d",$myconf['contact_id_prefix'], $this->id);
	}


	function set_scope($raw_scope='',$scope_key=0) {
		$scope='Unknown';
		$raw_scope=_trim($raw_scope);
		if (preg_match('/^customers?$/i',$raw_scope)) {
			$scope='Customer';
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





		$where_scope=sprintf(' and `Subject Type`=%s',prepare_mysql($this->scope));

		$where_scope_key='';
		if ($this->scope_key)
			$where_scope_key=sprintf(' and `Subject Key`=%d',$this->scope_key);




		$sql=sprintf("select * from `Contact Bridge` where `Contact Key`=%d %s  %s  order by `Is Main` desc"
			,$this->id
			,$where_scope
			,$where_scope_key
		);
		$res=mysql_query($sql);



		$this->data['Contact Is Main']='No';
		$this->data['Contact Is Active']='No';

		$this->associated_with_scope=false;
		while ($row=mysql_fetch_array($res)) {
			$this->associated_with_scope=true;

			$this->data['Contact Is Main']=$row['Is Main'];
			$this->data['Contact Is Active']=$row['Is Active'];

		}


	}



	function get_main_telecom_key($type='telephone') {
		if ($type=='telephone')
			return $this->data['Contact Main Telephone Key'];
		else if ($type=='fax')
				return $this->data['Contact Main FAX Key'];
			else if ($type=='mobile')
					return $this->data['Contact Main Mobile Key'];
				else
					return false;

	}


	/*
      function: is_main
      returns true if the contact is the main in the scope context
    */
	public function is_main() {
		if ($this->data['Contact Is Main']=='Yes')
			return true;
		else
			return false;
	}

	function update_mobile_to_delete($telecom_key) {

		return;

		if ($telecom_key==$this->data['Contact Main Mobile Key']) {
			$telecom=new Telecom($telecom_key);
			if (!$telecom->id) {
				$this->error=true;
				$this->msg='Telecom not found';
				$this->msg_updated.=',Telecom not found';
				return;
			}
			$old_value=$this->data['Contact Main XHTML Mobile'];
			$sql=sprintf("update `Contact Dimension` set `Contact Main XHTML Mobile `=%s ,`Contact Main Plain Mobile`=%s where `Contact Key`=%d ",
				prepare_mysql($telecom->display('xhtml')),
				prepare_mysql($telecom->display('plain')),
				$this->id
			);
			mysql_query($sql);
			if (mysql_affected_rows() and $old_value!=$telecom->display('xhtml')) {


				$history_data=array(
					'Indirect Object'=>'Contact Main XHTML Mobile',
					'History Abstract'=>_('Contact Main XHTML Mobile Changed'),
					'History Details'=>_('Contact Main XHTML Mobile changed from')." ".$old_value." "._('to').' '.$telecom->display('xhtml')

				);
				$this->add_history($history_data);
			}
		} else {
			$this->add_tel(array(
					'Telecom Key'=>$telecom->id,
					'Telecom Type'=>'Mobile'
				));
		}
	}






	function associate_mobile($mobile_key) {

		$mobile_keys=$this->get_mobile_keys();

		if (!array_key_exists($mobile_key,$mobile_keys)) {
			$this->create_mobile_bridge($mobile_key);




		}


	}

	function associate_email($email_key) {


		if (!$email_key) {
			$this->error=true;
			$this->msg='Wrong email key';

		}

		$email_keys=$this->get_email_keys();

		if (!array_key_exists($email_key,$email_keys)) {
			$this->create_email_bridge($email_key);




		}


	}



	function get_telecom_keys($type=false) {
		$where_type='';


		if ($type) {
			$where_type=sprintf('and `Telecom Type`=%s',prepare_mysql($type));

		}



		$sql=sprintf("select TB.`Telecom Key` from `Telecom Bridge` TB   left join `Telecom Dimension` T on (T.`Telecom Key`=TB.`Telecom Key`)  where     `Subject Type`='Contact' and `Subject Key`=%d  $where_type group by `Telecom Key` order by `Is Main` desc  "

			,$this->id);
		$address_keys=array();
		$result=mysql_query($sql);

		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$address_keys[$row['Telecom Key']]= $row['Telecom Key'];
		}
		return $address_keys;

	}



	function get_mobile_keys() {

		return $this->get_telecom_keys('Mobile');
		/*
            $sql=sprintf("select TB.`Telecom Key` from `Telecom Bridge` TB left join `Telecom Dimension` T on (T.`Telecom Key`=TB.`Telecom Key`)  where  `Subject Type`='Contact' and `Telecom Technology Type`='Mobile' and  `Subject Key`=%d "
                         ,$this->id );

            $mobiles=array();
            $result=mysql_query($sql);
            while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
                $mobiles[$row['Telecom Key']]= $row['Telecom Key'];
            }
            return $mobiles;
        */
	}


	function get_principal_mobile_key() {

		$sql=sprintf("select TB.`Telecom Key` from `Telecom Bridge`   TB left join `Telecom Dimension` T on (T.`Telecom Key`=TB.`Telecom Key`)  where  `Telecom Type`='Mobile'  and   `Subject Type`='Contact' and `Subject Key`=%d and `Is Main`='Yes'"
			,$this->id );

		//print "$sql\n";

		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$main_mobile_key=$row['Telecom Key'];
		} else {
			$main_mobile_key=0;
		}

		return $main_mobile_key;
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

	function create_mobile_bridge($mobile_key) {
		$sql=sprintf("insert into `Telecom Bridge` (`Subject Type`,`Subject Key`,`Telecom Key`) values ('Contact',%d,%d)  ",
			$this->id,
			$mobile_key
		);
		mysql_query($sql);
		$this->add_telecom=true;
		if (!$this->get_principal_mobile_key()) {
			$this->update_principal_mobil($mobile_key);
		}

	}

	function update_principal_mobil($mobile_key) {


		$main_mobile_key=$this->get_principal_mobile_key();

		if ($main_mobile_key!=$mobile_key) {
			$mobile=new Telecom($mobile_key);
			$mobile->editor=$this->editor;
			$sql=sprintf("update `Telecom Bridge`  B left join `Telecom Dimension` T on (T.`Telecom Key`=B.`Telecom Key`)  set `Is Main`='No' where `Subject Type`='Contact'   and `Subject Key`=%d  and `Telecom Key`=%d and `Telecom Type`='Mobile'  "
				,$this->id
				,$main_mobile_key
			);
			mysql_query($sql);

			$sql=sprintf("update `Telecom Bridge`  set `Is Main`='Yes' where `Subject Type`='Contact'  and  `Subject Key`=%d  and `Telecom Key`=%d"
				,$this->id
				,$mobile->id
			);
			mysql_query($sql);


			$sql=sprintf("update `Contact Dimension` set  `Contact Main Mobile Key`=%d where `Contact Key`=%d",$mobile->id,$this->id);
			$this->data['Contact Main Mobile Key']=$mobile->id;
			mysql_query($sql);
			$this->updated=true;
			$this->new_value=$mobile->display('xhtml');

			$this->update_parents_principal_mobile_keys();
			$mobile->new=$this->new;
			$mobile->update_parents();


		}

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

	function update_parents_principal_address_keys($address_key) {
		$parents=array('Customer');
		foreach ($parents as $parent) {
			$sql=sprintf("select `$parent Key` as `Parent Key`   from  `$parent Dimension` where `$parent Main Contact Key`=%d group by `$parent Key`",$this->id);
			$res=mysql_query($sql);
			while ($row=mysql_fetch_array($res)) {


				if ($parent=='Customer') {
					$parent_object=new Customer($row['Parent Key']);
					$parent_label=_('Customer');
				}

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

					if ($parent=='Customer') {
						$parent_object->get_data('id',$this->id);
						if ($parent_object->data['Customer Delivery Address Link']=='Contact') {
							$parent_object->update_principal_delivery_address($address_key);

						}


					}


				}
			}
		}
	}


	function get_contact_keys() {
		return array($this>id=>$this->id);
	}











	function get_parent_keys($type=false) {
		$where_type='';
		$keys=array();

		if ($type) {
			if (!preg_match('/^(Supplier|Customer|Company|Staff)$/',$type)) {
				return $keys;
			}
			$where_type=' and `Subject Type`='.prepare_mysql($type);
		}


		$sql=sprintf("select `Subject Key` from `Contact Bridge` where  `Contact Key`=%d  $where_type "

			,$this->id);
		$result=mysql_query($sql);
		//  print $sql;
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$keys[$row['Subject Key']]= $row['Subject Key'];

		}
		return $keys;
	}




	function update_parents_tax_number() {

		$parents=array('Customer');
		foreach ($parents as $parent) {
			$sql=sprintf("select `$parent Key` as `Parent Key` from  `$parent Dimension` where `$parent Main Contact Key`=%d group by `$parent Key`",$this->id);

			$res=mysql_query($sql);
			while ($row=mysql_fetch_array($res)) {
				$principal_contact_changed=false;

				if ($parent=='Customer') {
					$parent_object=new Customer($row['Parent Key']);
					$parent_label=_('Customer');
				}
				elseif ($parent=='Supplier') {
					$parent_object=new Supplier($row['Parent Key']);
					$parent_label=_('Supplier');
				}

				$old_principal_name=$parent_object->data[$parent.' Tax Number'];
				$parent_object->data[$parent.' Tax Number']=$this->data['Contact Tax Number'];
				$sql=sprintf("update `$parent Dimension` set  `$parent Tax Number`=%s  where `$parent Key`=%d"
					,prepare_mysql($parent_object->data[$parent.' Tax Number'])
					,$parent_object->id
				);
				mysql_query($sql);

				if ($parent=='Supplier' or ( $parent=='Customer' and $parent_object->data[$parent.' Type']=='Person')) {
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

						$history_data['History Abstract']='Tax Number Associated '.$this->data['Contact Tax Number'];
						$history_data['History Details']=$this->data['Contact Tax Number']." "._('associated with')." ".$parent_object->get_name()." ".$parent_label;
						$history_data['Action']='associated';
						$history_data['Direct Object']=$parent;
						$history_data['Direct Object Key']=$parent_object->id;
						$history_data['Indirect Object']=$parent.' Tax Name';
						$history_data['Indirect Object Key']='';

					} else {
						$history_data['History Abstract']='Tax Number changed to '.$this->data['Contact Tax Number'];
						$history_data['History Details']=_('Tax Number changed from').' '.$old_principal_name.' '._('to').' '.$this->data['Contact Tax Number'].", ".$parent_label.": ".$parent_object->get_name();
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
		foreach ($parents as $parent) {
			$sql=sprintf("select `$parent Key` as `Parent Key` from  `$parent Dimension` where `$parent Main Contact Key`=%d group by `$parent Key`",$this->id);

			$res=mysql_query($sql);
			while ($row=mysql_fetch_array($res)) {
				$principal_contact_changed=false;

				if ($parent=='Customer') {
					$parent_object=new Customer($row['Parent Key']);
					$parent_label=_('Customer');
				}
				elseif ($parent=='Supplier') {
					$parent_object=new Supplier($row['Parent Key']);
					$parent_label=_('Supplier');
				}

				$old_principal_name=$parent_object->data[$parent.' Tax Number'];
				$parent_object->data[$parent.' Tax Number']=$this->data['Contact Identification Number'];
				$sql=sprintf("update `$parent Dimension` set  `$parent Tax Number`=%s  where `$parent Key`=%d"
					,prepare_mysql($parent_object->data[$parent.' Tax Number'])
					,$parent_object->id
				);
				mysql_query($sql);

				if ($parent=='Supplier' or ( $parent=='Customer' and $parent_object->data[$parent.' Type']=='Person')) {
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

						$history_data['History Abstract']='Tax Number Associated '.$this->data['Contact Identification Number'];
						$history_data['History Details']=$this->data['Contact Identification Number']." "._('associated with')." ".$parent_object->get_name()." ".$parent_label;
						$history_data['Action']='associated';
						$history_data['Direct Object']=$parent;
						$history_data['Direct Object Key']=$parent_object->id;
						$history_data['Indirect Object']=$parent.' Tax Name';
						$history_data['Indirect Object Key']='';

					} else {
						$history_data['History Abstract']='Tax Number changed to '.$this->data['Contact Identification Number'];
						$history_data['History Details']=_('Tax Number changed from').' '.$old_principal_name.' '._('to').' '.$this->data['Contact Identification Number'].", ".$parent_label.": ".$parent_object->get_name();
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





	/*

    function disassociate_email($email_key){


     $sql=sprintf("delete from `Email Bridge`  where  `Email Key`=%d and `Subject Type`='Contact' and `Subject Key`=%d", $this->id);
        mysql_query($sql);
        $this->deleted=true;



        $parents=array('Company','Customer','Supplier');
        foreach($parents as $parent) {
            $sql=sprintf("select `$parent Key` as `Parent Key`   from  `$parent Dimension` where `$parent Main Email Key`=%d group by `$parent Key`",$this->id);

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
                $sql=sprintf("update `$parent Dimension` set `$parent Main Email Key`=NULL, `$parent Main Plain Email`='',`$parent Main XHTML Email`='' where `$parent Key`=%d"

                             ,$parent_object->id
                            );
                mysql_query($sql);
                $history_data['History Abstract']='Email Removed';
                $history_data['History Details']=_('Email').' '.$this->display('plain')." "._('has been deleted from')." ".$parent_object->get_name()." ".$parent_label;
                $history_data['Action']='disassociate';
                $history_data['Direct Object']=$parent;
                $history_data['Direct Object Key']=$parent_object->id;
                $history_data['Indirect Object']='Email';
                $history_data['Indirect Object Key']=$this->id;
                $this->add_history($history_data);
                if ($parent=='Contact') {
                    $emails=$parent_object->get_emails();
                    foreach($emails as $email) {
                        $parent_object->update_principal_email($email->id);
                        break;
                    }
                }


            }
        }


    }


    function delete_email($email_key){

    $sql=sprintf("select * from `Email Bridge` where `Subject Type`='Contact'  and `Email Key`=%d",$this->id $email_key);
    $res=mysql_query($sql);
    $email_in_other_contact=false;
    $email_in_contact=false;
    while($row=mysql_fetch_assoc($res)){
    if($row[`Subject Key`]==$this->id)
    $email_in_contact=true;
    else
    $email_in_other_contact=true;
    }
    if(!$email_in_contact){
    $this->msg='Email not associated with contact';
    return;
    }

    if($email_in_other_contact){
    $this->disassociate_email($email_key);
    }else{
    $email=new Email($email_key);
    $email->delete();

    }

    }
    */
	function delete() {

		$sql=sprintf("delete from `Contact Dimension` where `Contact Key`=%d",$this->id);
		//print "$sql\n";
		mysql_query($sql);
		$address_to_delete=$this->get_address_keys();
		$emails_to_delete=$this->get_email_keys();
		$telecom_to_delete=$this->get_telecom_keys();
		$sql=sprintf("delete from `Address Bridge` where `Subject Type`='Contact' and `Subject Key`=%d",$this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `Category Bridge` where `Subject`='Contact' and `Subject Key`=%d",$this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `Company Bridge` where `Subject Type`='Contact' and `Subject Key`=%d",$this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `Contact Old ID Bridge` where  `Contact Key`=%d",$this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `Email Bridge` where `Subject Type`='Contact' and `Subject Key`=%d",$this->id);
		mysql_query($sql);
		$sql=sprintf("delete from `Telecom Bridge` where `Subject Type`='Contact' and `Subject Key`=%d",$this->id);
		mysql_query($sql);



		foreach ($emails_to_delete as $email_key) {
			$email=new Email($email_key);
			if ($email->id and !$email->has_parents()) {
				$email->delete();
			}
		}



		foreach ($address_to_delete as $address_key) {
			$address=new Address($address_key);
			if ($address->id and !$address->has_parents()) {
				$address->delete();
			}
		}



		foreach ($telecom_to_delete as $telecom_key) {
			$telecom=new Telecom($telecom_key);
			if ($telecom->id and !$telecom->has_parents()) {
				$telecom->delete();
			}
		}


		// $email_keys=$this->get_email_keys();
		// $telecom_keys=$this->get_telecom_keys();



	}


}
?>
