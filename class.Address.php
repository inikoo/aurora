<?php
/*
  File: Address.php

  This file contains the Address Class

  About:
  Autor: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2009, Inikoo

  Version 2.0
*/

include_once 'class.DB_Table.php';
include_once 'class.Country.php';
/* class: Address
   Class to manage the *Address Dimension* table
*/
class Address extends DB_Table {
	public $updated=false;
	private $scope=false;
	private $scope_key=false;
	public $default_country_code='UNK';
	/*
      Constructor: Address
      Initializes the class, trigger  Search/Load/Create for the data set

      If first argument is find it will try to match the data or create if not found

      Parameters:
      arg1 -    Tag for the Search/Load/Create Options *or* the Contact Key for a simple object key search
      arg2 -    (optional) Data used to search or create the object

      Returns:
      void

      Example:
      (start example)
      // Load data from `Address Dimension` table where  `Address Key`=3
      $key=3;
      $address = New Address($key);

      // Load data from `Address Dimension` table where  `Address`='raul@gmail.com'
      $address = New Address('raul@gmail.com');

      // Insert row to `Address Dimension` table
      $data=array();
      $address = New Address('new',$data);


      (end example)

    */
	function Address($arg1=false,$arg2=false) {

		$this->table_name='Address';
		$this->ignore_fields=array('Address Key','Address Data Last Update','Address Data Creation');



		if (!$arg1 and !$arg2 or is_array($arg1)) {
			$this->error=true;
			$this->msg='No data provided';
			return;
		}
		if (is_numeric($arg1)) {
			$this->get_data('id',$arg1);
			return;
		}
		if (preg_match('/find/i',$arg1)) {
			$this->find($arg2,$arg1);
			return;
		}

		if ($arg1=='new' or $arg1=='create') {
			$this->create($arg2);
			return;
		}

		if ($arg1=='fuzzy all') {
			$this->get_data('fuzzy all');
			return;
		}
		elseif ($arg1=='fuzzy country') {
			if (!is_numeric($arg2)) {
				$this->get_data('fuzzy all');
				return;
			}
			$country=new Country($arg2);
			if (is_numeric($arg2) and $country->get('Country Code')!='UNK') {
				$this->get_data('fuzzy country',$arg2);
				return;
			} else {
				$this->get_data('fuzzy all');
				return;
			}


		}

		$this->get_data($arg1,$arg2);

	}

	/*
      Method: get_data
      Load the data from the database
    */

	function get_data($tipo,$id=false) {

		if ($tipo=='id')
			$sql=sprintf("select * from `Address Dimension` where  `Address Key`=%d",$id);
		elseif ('tipo'=='fuzzy country')
			$sql=sprintf("select * from `Address Dimension` where  `Address Fuzzy`='Yes' and `Address Fuzzy Type`='country' and `Address Country Key`=%d   ",$id);
		else
			$sql=sprintf("select * from `Address Dimension` where  `Address Fuzzy`='Yes' and `Address Fuzzy Type`='all' ",$id);




		$result=mysql_query($sql);
		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC))
			$this->id=$this->data['Address Key'];
		else {
			$this->msg="address do not exists";
			$this->error=true;


			// exit(" $sql\n can not open address");

		}
	}


	function prepare_data($raw_data,$options='') {




		if (isset($raw_data['Street Data'])) {

			$tmp=Address::parse_street($raw_data['Street Data']);
			foreach ($tmp as $key=>$value) {
				$raw_data[$key]=$value;
			}
			unset($raw_data['Street Data']);

		}
		/*
                if (isset($raw_data['Address Building']))
                    $raw_data['Address Line 2']=$raw_data['Address Building'];
                if (isset($raw_data['Address Internal']))
                    $raw_data['Address Line 1']=$raw_data['Address Internal'];
        */
		$data=$this->base_data();

		if (preg_match('/from Company|in company/i',$options)) {
			foreach ($raw_data as $key=>$val) {
				$_key=preg_replace('/Company /','',$key);
				if (array_key_exists($_key,$data))
					$data[$_key]=$val;
				if ($_key=='Address Line 1' or $_key=='Address Line 2' or  $_key=='Address Line 3' or $_key=='Address Input Format')
					$data[$_key]=$val;
			}
		}
		elseif (preg_match('/from contact|in contact/i',$options)) {
			foreach ($raw_data as $key=>$val) {

				$_key=preg_replace('/^Contact( Home| Work)? /i','',$key);
				// print "******** $key          ->  $_key\n";
				if (array_key_exists($_key,$data))
					$data[$_key]=$val;
				if ($_key=='Address Line 1' or $_key=='Address Line 2' or  $_key=='Address Line 3' or $_key=='Address Input Format')
					$data[$_key]=$val;
			}


		}
		else {
			foreach ($raw_data as $_key=>$val) {
				if (array_key_exists($_key,$data))
					$data[$_key]=$val;
				if ($_key=='Address Line 1' or $_key=='Address Line 2' or  $_key=='Address Line 3' or $_key=='Address Input Format')
					$data[$_key]=$val;
			}

		}

		if (!isset($data['Address Input Format'])) {
			if (isset($data['Address Line 1']))
				$data['Address Input Format']='3 Line';
			else
				$data['Address Input Format']='DB Fields';
		}



		switch ($data['Address Input Format']) {
		case('3 Line'):
			$data=$this->prepare_3line($data,'untrusted',$this->get_default('Country 2 Alpha Code'));

			$data['Address Input Format']='DB Fields';
			break;
		case('DB Fields'):
			$data=$this->prepare_DBfields($data);
			break;
		}


		return $data;

	}


	function find_in_subject($data,$subject_data) {
		$subject_type=$subject_data['subject_type'];
		$subject_object=$subject_data['subject_object'];

		$address_keys=$subject_object->get_address_keys();
		foreach ($address_keys as $address_key) {
			$address=new Address($address_key);
			$same_address=true;

			foreach ($data as $key=>$value ) {

				if ($value!='' and !preg_match('/World|Continent|Fuzzy|Location|Format|FAX|Telephone|Update/',$key)) {

					if ($data[$key]!=$address->data[$key]) {
						//  print "$key ".$data[$key].' '.$address->data[$key].'\n';
						$same_address=false;
						break;
					}


				}

			}

			if ($same_address) {

				$this->found=true;
				$this->found_key=$address_key;
				$this->get_data('id',$address_key);
				$subject_contact_keys=$subject_object->get_contact_keys();
				foreach ($subject_contact_keys as $contact_key)
					$this->candidate[$contact_key]=110;
				break;

			}

		}



	}

	function find_fast($data=false,$subject_data=false) {


	}

	function find_complete($data,$subject_data) {




		$subject_type=$subject_data['subject_type'];


		if ($subject_type!='') {

			$this->find_in_subject ($data,$subject_data);
			return;
		}



		//--------------
		if ($data['Address Fuzzy']!='Yes') {

			$fields=array('Address Fuzzy','Address Internal','Address Street Number','Address Building','Address Street Name','Address Street Type','Address Town Second Division','Address Town First Division','Address Town','Address Country First Division','Address Country Second Division','Address Country Key','Address Postal Code','Military Address','Military Installation Address','Military Installation Name');

			$sql="select A.`Address Key`,`Subject Key`,`Subject Type` from `Address Dimension`  A  left join `Address Bridge` AB  on (AB.`Address Key`=A.`Address Key`) where `Subject Type`='Contact' ";
			foreach ($fields as $field) {
				$sql.=sprintf(' and `%s`=%s',$field,prepare_mysql($data[$field],false));
			}

			$result=mysql_query($sql);
			$num_results=mysql_num_rows($result);
			if ($num_results==1) {
				$row=mysql_fetch_array($result, MYSQL_ASSOC);
				$this->found=true;
				$this->found_key=$row['Address Key'];
				$this->get_data('id',$row['Address Key']);
				$this->candidate[$row['Subject Key']]=110;
			}


		} else {

			if ( $data['Address Fuzzy Type']=='Town') {

				$fields=array('Address Fuzzy','Address Internal','Address Street Number','Address Building','Address Street Name','Address Street Type','Address Town Second Division','Address Town First Division','Address Country First Division','Address Country Second Division','Address Country Key','Address Postal Code','Military Address','Military Installation Address','Military Installation Name');

				$sql="select A.`Address Key`,`Subject Key`,`Subject Type` from `Address Dimension`  A  left join `Address Bridge` AB  on (AB.`Address Key`=A.`Address Key`) where `Subject Type`='Contact' ";

				foreach ($fields as $field) {
					$sql.=sprintf(' and `%s`=%s',$field,prepare_mysql($data[$field],false));
				}

				$result=mysql_query($sql);
				$num_results=mysql_num_rows($result);
				if ($num_results==1) {
					$row=mysql_fetch_array($result, MYSQL_ASSOC);
					$this->found=true;
					$this->found_key=$row['Address Key'];
					$this->get_data('id',$row['Address Key']);
					$this->candidate[$row['Subject Key']]=110;

				}
			}
		}

	}

	function find_fuzzy($data,$subject_data) {

		//print_r($data);

		//special cases
		// Only one data
		$fields=array('Address Street Number','Address Building','Address Street Name','Address Town Second Division','Address Town First Division','Address Town','Address Country First Division','Address Country Second Division','Address Postal Code','Address Country Code');
		$filled_fields=array();
		foreach ($fields as $field) {
			if ($data[$field]!='' ) {
				$filled_fields[$field]=$data[$field];
			}
		}


		$number_filled_fields=count($filled_fields);

		//print_r($filled_fields);


		foreach ($filled_fields as $field=>$value) {
			switch ($field) {
			case 'Address Postal Code':
				$postal_code_no_spaces=preg_replace('/\n/','',$value);
				if ($postal_code_no_spaces!=$value) {
					$postal_code=prepare_mysql($value).','.prepare_mysql($postal_code_no_spaces);
				} else {
					$postal_code=prepare_mysql($value);
				}

				$sql=sprintf("select A.`Address Key`,`Subject Key` from `Address Dimension`  A  left join `Address Bridge` AB  on (AB.`Address Key`=A.`Address Key`) where `Subject Type`='Contact' and `Address Postal Code` in (%s)",
					$postal_code
				);
				// print $sql;
				$res=mysql_query($sql);
				$num_results=mysql_num_rows($res);
				if ($num_results>100)
					continue;
				while ($row=mysql_fetch_assoc($res)) {
					$val=25;
					if (isset($this->candidate[$row['Subject Key']]))
						$this->candidate[$row['Subject Key']]+=$val;
					else
						$this->candidate[$row['Subject Key']]=$val;
				}
				break;
			case 'Address Town':

				$sql=sprintf("select A.`Address Key`,`Subject Key` from `Address Dimension`  A  left join `Address Bridge` AB  on (AB.`Address Key`=A.`Address Key`) where `Subject Type`='Contact' and `Address Town`=%s ",
					prepare_mysql($value)
				);
				// print "$sql\n";
				$res=mysql_query($sql);
				$num_results=mysql_num_rows($res);
				if ($num_results>120)
					continue;
				while ($row=mysql_fetch_assoc($res)) {
					$val=20;
					if (isset($this->candidate[$row['Subject Key']]))
						$this->candidate[$row['Subject Key']]+=$val;
					else
						$this->candidate[$row['Subject Key']]=$val;
				}
				break;

			default:

				break;
			}


			//print_r($this->candidate);

		}



		return;

		if ($number_filled_fields==1) {


		} elseif ($number_filled_fields==2) {
			$column=false;
			if (array_key_exists('Address Town',$filled_fields)) {
				$column='Address Town';
			}
			elseif (array_key_exists('Address Postal Code',$filled_fields)) {
				$column='Address Postal Code';
			}

			if ($column) {

				$sql=sprintf("select A.`Address Key` ,damlev(UPPER(`%s`),%s) as dist,  `Subject Key`as contact_key from `Address Dimension` A left join `Address Bridge` AB  on (AB.`Address Key`=A.`Address Key`) where  `Subject Type`='Contact'  and `Address Country Code`=%s and damlevlim(UPPER(`%s`),%s,2)<2  order by dist limit 20   "
					,$column
					,prepare_mysql(strtoupper($data[$column]))
					,prepare_mysql($data['Address Country Code'])
					,$column
					,prepare_mysql(strtoupper($data[$column]))
				);
				$result=mysql_query($sql);
				$len_keyword=strlen($data[$column]);

				// print $sql;
				while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
					$contact_key=$row['contact_key'];
					$dif=$row['dist']/$len_keyword;
					if ($dif>1)
						$dif=1;
					$score=10.0*(1-$dif)*(1-$dif);
					if (isset($this->candidate[$contact_key])) {
						if ($this->candidate[$contact_key]<$score)
							$this->candidate[$contact_key]=$score;
					} else {
						// print "-- $score \n";
						$this->candidate[$contact_key]=$score;
					}

				}

				$sql=sprintf("select `Address Town`, A.`Address Key` ,damlev(UPPER(`%s`),%s) as dist,  `Subject Key` as contact_key from `Address Dimension` A left join `Address Bridge` AB  on (AB.`Address Key`=A.`Address Key`) where  `Subject Type`='Contact'  and `Address Country Code`!=%s and damlevlim(UPPER(`%s`),%s,2)<2  order by dist limit 20   "
					,$column
					,prepare_mysql(strtoupper($data[$column]))
					,prepare_mysql($data['Address Country Code'])
					,$column
					,prepare_mysql(strtoupper($data[$column]))
				);
				$result=mysql_query($sql);
				$len_keyword=strlen($data[$column]);

				//    print $sql;
				while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
					$contact_key=$row['contact_key'];
					$dif=$row['dist']/$len_keyword;
					if ($dif>1)
						$dif=1;
					$score=2.5*(1-$dif)*(1-$dif);
					if (isset($this->candidate[$contact_key])) {
						if ($this->candidate[$contact_key]<$score)
							$this->candidate[$contact_key]=$score;
					} else {
						// print "-- $score \n";
						$this->candidate[$contact_key]=$score;
					}

				}


			}






		}
		else {

			$nothing_found=true;
			$found_item=array('Postal Code'=>false);
			$postal_code_max_score=20;
			$town_max_score=20;
			$street_max_score=10;
			$score_found_within_address=80;
			$country_max_score=10;
			$max_score=0;

			$sql=sprintf("select SQL_CACHE count(*) as addresses from `Address Dimension` ");
			$res=mysql_query($sql);
			$address_multiplicity_data=mysql_fetch_array($res);
			$address_multiplicity=$address_multiplicity_data['addresses'];

			$sql=sprintf("select SQL_CACHE count(*) as addresses from `Address Dimension` where   `Address Country Code`=%s "
				,prepare_mysql($data['Address Country Code'])
			);
			$res=mysql_query($sql);

			$country_multiplicity_data=mysql_fetch_array($res);
			$country_multiplicity=$country_multiplicity_data['addresses'];

			if ($address_multiplicity>0) {

				if ($country_multiplicity==0)
					$same_country_bonus=0;
				else {
					$same_country_bonus=10*(1-($country_multiplicity/$address_multiplicity));
					//print "xxxx $country_multiplicity $address_multiplicity";


				}
			} else {
				$same_country_bonus=0;

			}

			$max_score=-1;
			unset($country_multiplicity_data);


			if (  ($data['Address Street Name']!=''   ) or $data['Address Building']!=''   ) {
				$order='';
				$sql_town='';
				$sql_postal_code='';
				$sql_where_postal_code='';
				$sql_where_street_number='';
				$sql_where_internal='';
				$with_town=false;
				$with_postal_code=false;
				if ($data['Address Town']!='') {
					$sql_town=sprintf("damlev(UPPER(`Address Town`),%s) as dist_town,",prepare_mysql(strtoupper($data['Address Town'])));
					$order='dist_town';
					$with_town=true;
					$len_town=strlen($data['Address Town']);


				}


				if ($data['Address Postal Code']!='') {
					$sql_postal_code=sprintf("damlevlim(UPPER(`Address Postal Code`),%s,3) as dist_postal_code,",prepare_mysql(strtoupper($data['Address Postal Code'])));
					$sql_where_postal_code=sprintf("and  damlevlim(UPPER(`Address Postal Code`),%s,3)<3 ",prepare_mysql(strtoupper($data['Address Postal Code'])));
					$order.=($order!=''?',':'').'dist_postal_code';
					$with_postal_code=true;
				}
				if ($data['Address Street Number']!='') {
					$sql_where_street_number=sprintf("and `Address Street Number`=%s ",prepare_mysql(strtoupper($data['Address Street Number'])));
				}
				elseif ($data['Address Building']!='') {
					$sql_where_street_number=sprintf("and  damlevlim(UPPER(`Address Building`),%s,6)<6 ",prepare_mysql(strtoupper($data['Address Building'])));
				}
				if ($data['Address Internal']!='') {
					$sql_where_internal=sprintf("and `Address Internal`=%s"
						,prepare_mysql($data['Address Internal'],false)
					);
				}

				$order=($order!=''?'order by ':'').$order;

				$sql=sprintf("select `Address Town`,`Address Country Code`,`Address Postal Code`, A.`Address Key` ,%s  %s  `Subject Key` from `Address Dimension` A left join `Address Bridge` AB  on (AB.`Address Key`=A.`Address Key`) where  `Subject Type`='Contact'  and `Address Country Code`=%s %s %s %s %s limit 250 "
					,$sql_postal_code
					,$sql_town
					,prepare_mysql(strtoupper($data['Address Country Code']))
					,$sql_where_internal
					,$sql_where_street_number
					,$sql_where_postal_code
					,$order
				);

				//print "$sql<br>";

				$result=mysql_query($sql);
				$max_score=-1;
				while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

					$wrong_town_factor=1;
					$found_postal_code_bonus=20;
					$found_town_bonus=10;

					$contact_key=$row['Subject Key'];

					$score=$score_found_within_address;
					$wrong_postal_code_factor=1;
					if ($with_postal_code) {
						if ($row['dist_postal_code']==0) {
							$score+=$found_postal_code_bonus;
							$wrong_postal_code_factor=1;
						}
						elseif ($row['dist_postal_code']==1) {
							$wrong_postal_code_factor=.5;
						}
						else {
							$wrong_postal_code_factor=.25;
						}
					}

					if ($with_town) {
						if ($row['dist_town']==0) {
							$score+=$found_town_bonus;

						}
						$dif=$row['dist_town']/$len_town;
						if ($dif>1)
							$dif=1;
						//print "xxxxx $dif\n";
						$wrong_town_factor=(1-$dif)*(1-$dif);

					}
					$score=$score*$wrong_postal_code_factor*$wrong_town_factor;


					if (isset($this->candidate[$contact_key])) {
						if ($this->candidate[$contact_key]<$score)
							$this->candidate[$contact_key]=$score;
					} else {
						$this->candidate[$contact_key]=$score;
					}

					if ($this->candidate[$contact_key]>$max_score)
						$max_score=$this->candidate[$contact_key];
				}

			}


			if ($max_score>85)
				$nothing_found=false;

			if ($nothing_found) {

				if ($data['Address Postal Code']!='') {
					$sql=sprintf("select   `Address Country Code`,`Address Postal Code`, A.`Address Key` ,damlev(UPPER(`Address Postal Code`),%s) as dist1,`Subject Key` from `Address Dimension` A left join `Address Bridge` AB  on (AB.`Address Key`=A.`Address Key`)  where  `Subject Type`='Contact'  and   damlev(UPPER(`Address Postal Code`),%s) <3   order by dist1  limit 250 "
						,prepare_mysql(strtoupper($data['Address Postal Code']))
						,prepare_mysql(strtoupper($data['Address Postal Code']))
					);
					$result=mysql_query($sql);

					while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
						$contact_key=$row['Subject Key'];
						if ($row['dist1']==0) {

							$found_item['Postal Code']=true;
							$factor=1;
						}
						elseif ($row['dist1']==1) {
							$factor=.4;
						}
						else {
							$factor=.1;
						}
						$score=$factor*$postal_code_max_score;

						$bonus=0;
						if ($data['Address Country Code']==$row['Address Country Code'])
							$bonus=$same_country_bonus;


						//print "$factor $postal_code_max_score $same_country_bonus<br>";
						$score=$factor*($postal_code_max_score+$bonus);



						if (isset($this->candidate[$contact_key])) {
							if ($this->candidate[$contact_key]<$score)
								$this->candidate[$contact_key]=$score;
						} else
							$this->candidate[$contact_key]=$score;

					}
				}





				if ($data['Address Town']!='') {


					//Count if theres is a exacte match

					/* 	  $sql=sprintf('select count(*) as number from `Address Dimension` A left join `Address Bridge` AB on (AB.`Address Key`=A.`Address Key`) where `Subject Type`="Contact"  and `Address Town`=%s    ',prepare_mysql(data['Address Town'])); */
					/* 			  $result=mysql_query($sql); */
					/* 			  $row=mysql_fetch_array($result, MYSQL_ASSOC); */

					/* 			  if($row['number_of_address']>20){ */
					/* 			      $sql=sprintf('select `Subject Key` from `Address Dimension` A left join `Address Bridge` AB on (AB.`Address Key`=A.`Address Key`) where `Subject Type`="Contact"  and `Address Town`=%s     and `Subject Key in`  ',prepare_mysql(data['Address Town'])); */

					/* 			  }else{ */

					$sql=sprintf("select   `Address Country Code`,`Address Town`, A.`Address Key` ,damlev(UPPER(`Address Town`),%s) as dist1,`Subject Key` from `Address Dimension` A left join `Address Bridge` AB  on (AB.`Address Key`=A.`Address Key`)  where  `Subject Type`='Contact'     order by dist1  limit 50 "
						,prepare_mysql(strtoupper($data['Address Town']))
						,prepare_mysql(strtoupper($data['Address Town']))
					);


					$result=mysql_query($sql);
					$len_town=strlen($data['Address Town']);
					while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
						$contact_key=$row['Subject Key'];

						$dif=$row['dist1']/$len_town;
						if ($dif>=1)
							break;
						$factor=(1-$dif)*(1-$dif);
						$score=$factor*$town_max_score;

						if ($row['dist1']==0) {
							$found_item['Town']=true;
						}


						$bonus=0;
						if ($data['Address Country Code']=$row['Address Country Code'])
							$bonus=$same_country_bonus;

						$score=$factor*($town_max_score+$bonus);



						if (isset($this->candidate[$contact_key]))
							if ($this->candidate[$contact_key]<$score)
								$this->candidate[$contact_key]=$score;
							else
								$this->candidate[$contact_key]=$score;

					}
				}

				if ($data['Address Street Name']!='' ) {
					$sql=sprintf("select   `Address Country Code`,`Address Town`, A.`Address Key` ,damlev(UPPER(`Address Street Name`),%s) as dist1,`Subject Key` from `Address Dimension` A left join `Address Bridge` AB  on (AB.`Address Key`=A.`Address Key`)  where  `Subject Type`='Contact'     order by dist1  limit 50 "
						,prepare_mysql(strtoupper($data['Address Street Name']))
					);
					$result=mysql_query($sql);
					$len_street=strlen($data['Address Street Name']);
					while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
						$contact_key=$row['Subject Key'];

						$dif=$row['dist1']/$len_street;
						if ($dif>=1)
							break;
						$factor=(1-$dif)*(1-$dif);
						$score=$factor*$town_max_score;


						$bonus=0;
						if ($data['Address Country Code']=$row['Address Country Code'])
							$bonus=$same_country_bonus;

						$score=$factor*($street_max_score+$bonus);



						if (isset($this->candidate[$contact_key]))
							if ($this->candidate[$contact_key]<$score)
								$this->candidate[$contact_key]=$score;
							else
								$this->candidate[$contact_key]=$score;

					}
				}

			}







		}



		if (!$this->found and count($this->candidate)==0) {
			// foound 1 additions
			if ($data['Address Fuzzy']=='No') {
				//Special cases
				//a) when same (st number,street,town,d1,d2) but postal code on / off
				$fields=array('Address Street Number','Address Building','Address Street Name','Address Street Type','Address Town Second Division','Address Town First Division','Address Town','Address Country First Division','Address Country Second Division','Address Country Key','Military Address','Military Installation Address','Military Installation Name');

				$sql="select A.`Address Key`,`Subject Key`,`Subject Type` from `Address Dimension`  A  left join `Address Bridge` AB  on (AB.`Address Key`=A.`Address Key`) where `Subject Type`='Contact' ";
				foreach ($fields as $field) {
					$sql.=sprintf(' and `%s`=%s',$field,prepare_mysql($data[$field],false));
				}
				$result=mysql_query($sql);
				//print "No fuzzy $sql\n";
				$num_results=mysql_num_rows($result);

				if ($num_results==1) {
					$row=mysql_fetch_array($result, MYSQL_ASSOC);
					$this->found=true;
					$this->found_key=$row['Address Key'];
					$this->get_data('id',$row['Address Key']);
					if ($subject_type=='Contact' or $subject_type=='Company' or $subject_type=='Customer') {
						if (in_array($row['Subject Key'],$in_contact)) {
							$this->candidate[$row['Subject Key']]=100;
							$this->found_in=true;
							$this->found_out=false;
						} else {
							$this->candidate[$row['Subject Key']]=80;
							$this->found_in=false;
							$this->found_out=true;
						}
					} else
						$this->candidate[$row['Subject Key']]=90;

				}


			}


		}







	}


	/*
      Method: find
      Given a set of address components try to find it on the database updating properties, if not found creates a new record
    */

	function find($raw_data,$options='') {


		$find_type='complete';
		if (preg_match('/fuzzy/i',$options)) {
			$find_type='fuzzy';
		}
		elseif (preg_match('/fast/i',$options)) {
			$find_type='fast';
		}



		$this->found=false;
		$this->found_key=0;

		$this->found_in=false;
		$this->found_out=false;
		$this->candidate=array();
		$this->address_candidate=array();

		$create=false;
		$update=false;
		if (preg_match('/create/i',$options)) {
			$create=true;
		}
		if (preg_match('/update/i',$options)) {
			$update=true;
		}


		$auto=false;
		if (preg_match('/auto/i',$options)) {
			$auto=true;
		}


		if (!$raw_data) {
			$this->new=false;
			$this->msg=_('Error no address data');
			if (preg_match('/exit on errors/',$options))
				exit($this->msg);
			return false;
		}


		if (isset($raw_data['editor']) and is_array($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {

				if (array_key_exists($key,$this->editor))
					$this->editor[$key]=$value;

			}
		}
		//print_r($raw_data);
		$data=$this->prepare_data($raw_data,$options);
		//    print_r($data);
		//   print "---------------\n";
		$subject_key=0;
		$subject_type='';
		$in_contact=array();
		$subject_object=false;
		if (preg_match('/in contact \d+/i',$options,$match)) {
			$subject_key=preg_replace('/[^\d]/','',$match[0]);
			$subject_type='Contact';
			$subject_object=new Contact($subject_key);
			$in_contact=array($subject_key);
		}
		if (preg_match('/in company \d+/i',$options,$match)) {
			$subject_key=preg_replace('/[^\d]/','',$match[0]);
			$subject_type='Company';
			$subject_object=new Company($subject_key);
			$in_contact=$subject_object->get_contact_keys();


		}
		if (preg_match('/in customer \d+/i',$options,$match)) {
			$subject_key=preg_replace('/[^\d]/','',$match[0]);
			$subject_type='Customer';
			$subject_object=new Customer($subject_key);
			$in_contact=$subject_object->get_contact_keys();


		}

		$subject_data=array(
			'subject_key'=>$subject_type,
			'subject_type'=>$subject_type,
			'subject_object'=>$subject_object,
			'in_contact'=>$in_contact
		);


		switch ($find_type) {
		case 'fast':
			$this->find_fast();
			break;
		case 'complete':


			$this->find_complete($data,$subject_data);
			break;
		case 'fuzzy':

			$this->find_fuzzy($data,$subject_data);
			break;
		}

		if ($update) {
			if ($this->found) {
				$this->update($data,$options);
				return;
			}

		}

		if ($create and !$this->found) {

			$this->create($data,$options);

		}
		elseif ($create and preg_match('/force/',$options)   ) {

			$this->create($data,$options);

		}





	}


	/*Method: create
      Creates a new address record


      Parameter:
      An array with the data to be inserted in the database, a important key is *Address Input Format* which  can be: _3 Line_, _DB Fields_

      The country can be inputed using: Address Country Key, Address Country Code, Address Country 2 Alpha Code, Address Country Name, (Parsed in this order until positive match with Country Dimension table)

      Examples:
      (start example)
      // Example using 3 line input method

      $data=array(
      'Address Input Format'=>'3 Line'
      'Address Line 1'=>'3 Hobart Street'
      'Address Line 2'=>''
      'Address Line 3'=>''
      'Address Town'=>'Sheffield'
      'Address Region'=>''                      //State,county,province etc
      'Address Postal Code'=>'S11 4HD'
      'Address Country Name'=>'United Kindom')

      // Example using 3 line extended input method

      $data=array(
      'Address Input Format'=>'3 Line'
      'Address Line 1'=>'Hill House'
      'Address Line 2'=>'10 Kitchen Street'
      'Address Line 3'=>''
      'Address Town SubDivision'=>''
      'Address Town Division'=>'Wakley'
      'Address Town'=>'Sheffield'
      'Address SubRegion'=>'South Yorkshire'    //County,municipality,etc inside the region
      'Address Region'=>'England'               //State,county,province
      'Address Postal Code'=>'S11 4HD'
      'Address Country Code'=>'GBR')


      (end example)

      See Also:
      <Address>

    */



	function create($data) {

		// print_r($data);
		//exit;




		if (!isset($data['Address Input Format'])) {
			$data['Address Input Format']='DB Fields';
			if (isset($data['Address Address Line 1']))
				$data['Address Input Format']='3 Line';
			else
				$data['Address Input Format']='DB Fields';
		}


		//print_r($data);

		switch ($data['Address Input Format']) {
		case('3 Line'):

			$this->data=$this->prepare_3line($data,'untrusted',$this->get_default('Country 2 Alpha Code'));
			break;
		case('DB Fields'):
			$this->data=$this->prepare_DBfields($data);
			break;
		}

		$this->data['Address Plain']=$this->plain($this->data);
		$keys='';
		$values='';
		foreach ($this->data as $key=>$value) {

			if (!preg_match('/line \d|Address Input Format/i',$key) ) {
				if (preg_match('/Address Data Creation/i',$key) ) {
					$keys.=",`".$key."`";
					$values.=', Now()';
				} else {
					$keys.=",`".$key."`";
					$values.=','.prepare_mysql($value,false);
				}
			}
		}
		$values=preg_replace('/^,/','',$values);
		$keys=preg_replace('/^,/','',$keys);

		$sql="insert into `Address Dimension` ($keys) values ($values)";
		if (mysql_query($sql)) {
			$this->id = mysql_insert_id();
			$this->data['Address Key']= $this->id;
			$this->new=true;
		} else {
			print "Error can not create address\n $sql \n";
			exit;

		}
	}



	public function update($data,$options='') {



		if (isset($data['editor'])) {
			foreach ($data['editor'] as $key=>$value) {

				if (array_key_exists($key,$this->editor))
					$this->editor[$key]=$value;

			}
		}
		//  print "XXXXXXXXXXX\n";
		//print_r($data);
		//print"+++++".$this->updated."++++++++\n";




		$data=$this->prepare_data($data,$options);

		//   print_r($data);


		unset($data['Address Main Telephone Key']);
		unset($data['Address Main Plain Telephone']);
		unset($data['Address Main XHTML Telephone']);

		// if($this->table_name=='Telecom'){
		// print_r($data);exit;
		// }
		$base_data=$this->base_data();

		unset($data['Address Data Last Update']);

		foreach ($data as $key=>$value) {
			//print "*** $key $value \n";
			if ($key=='Address Country Code' or  $key==' Address Country 2 Alpha Code' or $key=='Address Country Name' ) {
				if ($key=='Address Country Code')
					$code=$value;
				elseif ($key=='Address Country Name') {
					$code=$this->parse_country($value);
				}
				elseif ( $key==' Address Country 2 Alpha Code' ) {
					$country=new Country('2alpha',$value);
					$code=$country->data['Country Code'];
				}


				$this->update_country_code($code);
			}
			elseif (array_key_exists($key,$base_data)) {
				//   print "xx--> $value $key\n";
				if (strcmp($value,$this->data[$key])!=0) {
					$this->update_field_switcher($key,$value,$options);
				}

			}
			elseif (preg_match('/^Street Data$/',$key)) {
				$this->update_field_switcher($key,$value,$options);
			}

			//print"+++++".$this->updated."++++++++\n";

		}
		if (!$this->updated)
			$this->msg.=' '._('Nothing to be updated')."\n";
		else {

			$sql=sprintf("update `Address Dimension` set `Address Data Last Update`=NOW where `Address Key`=%d",$this->id);
			mysql_query($sql);
			$this->get_data('id',$this->id);

			list($fuzzy,$fuzzy_type)=$this->get_fuzzines($this->data);
			$this->data['Address Fuzzy']=$fuzzy;
			$this->data['Address Fuzzy Type']=$fuzzy_type;


			$location=$this->display('location');
			$plain=$this->display('plain');




			$sql=sprintf("update `Address Dimension` set `Address Fuzzy`=%s, `Address Fuzzy Type`=%s, `Address Location`=%s,`Address Plain`=%s  where `Address Key`=%d  "
				,prepare_mysql($fuzzy)
				,prepare_mysql($fuzzy_type,false)
				,prepare_mysql($location)
				,prepare_mysql($plain)
				,$this->id


			);
			mysql_query($sql);



		}
		
		
		
		$this->get_data('id',$this->id);
		$this->update_parents();

	}


	function add_other_telecom($type='Telephone',$value,$old_telecom='') {
		if ($value=='')return;
		$telephone_data=array();
		$telephone_data['editor']=$this->editor;
		$telephone_data['Telecom Raw Number']=$value;
		$telephone_data['Telecom Type']=$type;

		//$telephone=new Telecom("find complete create country code ".$this->data['Address Country Code'],$telephone_data);
		$telephone=new Telecom('new',$telephone_data);
		$this->associate_telecom($telephone->id,$type);

		//$telephone->update_parents();<-- this will only affect main i think maybe we need to put it back
		
	
		
		
		
		
		$this->other_telecom_key=$telephone->id;
		$this->updated=true;
		$this->new_value=$telephone->display('formated');

	}

	function update_field_switcher($field,$value,$options='') {
		switch ($field) {

		case('Add Other Telephone'):
			$this->add_other_telecom('Telephone',$value);
			break;
		case('Add Other FAX'):
			$this->add_other_telecom('FAX',$value);
			break;
		case('Address First Postal Code'):
		case('Address Second Postal Code'):
		case('Address Location'):
		case('Address Plain'):
		case('Address Input Format'):
		case('Address Fuzzy'):
		case('Address Data Creation'):
			break;
		case('Address Postal Code'):
			$data=$this->parse_postcode($value,$this->data['Address Country Code']);
			foreach ($data as $postcode_field=>$postcode_value) {
				if ($postcode_field!='Address Postal Code')
					$postcode_options=$options.' no history';
				else
					$postcode_options=$options;
				$this->update_field($postcode_field,$postcode_value,$postcode_options);

			}
			break;
		case('Address Country Code'):
			$this->update_country_code($value);
			break;
		case('Street Data'):
			//      print $value;
			$data=$this->parse_street($value,$this->data['Address Country Code']);
			print_r($data);
			//exit;
			foreach ($data as $street_field=>$street_value) {
				$this->update_field($street_field,$street_value,$options);
			}

			break;
		case('Address Data Last Update'):
			$this->update_field($field,$this->editor['Date'],$options);
			break;

		default:
			$this->update_field($field,$value,$options);
		}
	}

	function update_metadata($raw_data) {


		foreach ($raw_data as $key=>$value) {
			if ($key=='Type') {
				$this->update_address_type($value);
			}
			elseif ($key=='Function') {
				$this->update_address_function($value);
			}

		}

	}


	function update_address_type($raw_new_address_types) {
		$updated=false;

		$new_address_types=array();
		$valid_types=array('Office','Shop','Warehouse','Other');
		foreach ($raw_new_address_types as $raw_new_address_type) {
			if (in_array($raw_new_address_type,$valid_types))
				$new_address_types[$raw_new_address_type]=$raw_new_address_type;
		}

		if (count($new_address_types)==0)
			$new_address_types['Other']=array('Other');
		//print_r($this->data['Type']);
		//print_r($new_address_types);

		foreach ($this->data['Type'] as $type) {
			if (!in_array($type,$new_address_types)) {
				//print "deleting $type\n";
				$sql=sprintf("delete from `Address Bridge` where `Address Key`=%s and `Subject Type`=%s and `Subject Key`=%d  and `Address Type`=%s "
					,$this->id
					,prepare_mysql($this->scope)
					,$this->scope_key
					,prepare_mysql($type)
				);
				//print "$sql\n";
				mysql_query($sql);

				$updated=true;
			}
		}

		foreach ($new_address_types as $type) {

			if (!in_array($type,$this->data['Type'])) {
				foreach ($this->data['Function'] as $function) {
					$sql=sprintf("select *  from `Address Bridge` where `Address Key`=%s and `Subject Type`=%s and `Subject Key`=%d and `Address Function`=%s "
						,$this->id
						,prepare_mysql($this->scope)
						,$this->scope_key
						,prepare_mysql($function)
					);
					$res=mysql_query($sql);
					$active='Yes';
					$main='No';
					//  print "$sql\n";

					if ($row=mysql_fetch_array($res)) {
						$active=$row['Is Active'];
						$main=$row['Is Main'];
					}

					$sql=sprintf('insert into `Address Bridge` values (%d,%s,%d,%s,%s,%s,%s)'
						,$this->id
						,prepare_mysql($this->scope)
						,$this->scope_key
						,prepare_mysql($type)
						,prepare_mysql($function)
						,prepare_mysql($active,false)
						,prepare_mysql($main,false)
					);
					//print "$sql\n";
					mysql_query($sql);

					$updated=true;
				}
			}
		}

		if ($updated) {
			// print "updated!!!";
			$this->load_metadata();

			$msg='';
			$this->msg_update.=$msg;
			$this->msg.=$msg;
			$this->updated=true;

		}

	}





	function update_address_function($value) {

	}

	function get($key) {


		if (array_key_exists($key,$this->data))
			return $this->data[$key];

		switch ($key) {
		case('Type'):
		case('Function'):


			if (!$this->scope)
				$this->set_scope();
			return $this->data['Address '.$key];
			break;


		case('country region'):
			if ($this->get('Address Country First Division')!='')
				return $this->get('Address Country First Division');
			else
				return $this->get('Address Country Second Division');
			break;


		}

		// print_r($this->data);
		$_key=ucwords($key);
		if (array_key_exists($_key,$this->data))
			return $this->data[$_key];
		print_r($this->data);
		print "Error $key not found in get from address\n";
		asdsaaaa();
		exit;
		return false;

	}

	function display($tipo='',$locale='en_GB') {


		$separator="\n";
		switch ($tipo) {

		case('3lines'):
			$lines=array('','','','');
			if ($this->data['Address Internal']!='' and $this->data['Address Building']!='') {
				$lines[1]=$this->data['Address Internal'];
				$lines[2]=$this->data['Address Building'];
				$lines[3]=$this->display('street');
			}
			elseif ($this->data['Address Internal']=='' and $this->data['Address Building']=='') {

				$lines[1]=$this->display('street');
			}
			elseif ($this->data['Address Internal']!='' or $this->data['Address Building']!='') {
				$lines[1]=_trim($this->data['Address Internal'].' '.$this->data['Address Building']);
				$lines[2]=$this->display('street');
			}
			return $lines;
			break;
		case('lines'):
			$lines=$this->display('3lines',$locale);
			$join_lines='';
			foreach ($lines as $line) {
				if ($line!='')$join_lines.=$line.', ';
			}


			$join_lines=preg_replace('/,\s*$/','',$join_lines);
			return $join_lines;
			break;
		case('Town with Divisions'):

			$town =$this->data['Address Town Second Division'];
			if ($this->data['Address Town First Division']!='')
				$town.=', '.$this->data['Address Town First Division'];
			if ($this->data['Address Town']!='')
				$town.=', '.$this->data['Address Town'];
			$town=preg_replace('/^\,\s*/','',$town);
			return $town;

			break;
		case('mini'):
			$street=$this->display('street');
			if (strlen($street)<2)
				$street=_trim($this->data['Address Internal']." ".$this->data['Address Building']);

			$max_characters=26;
			if (strlen($street)>$max_characters)
				$street=substr($street,$max_characters)."... ";
			$street.=', ';


			return $street.$this->location($this->data,'right');
			break;
		case('location'):
			return $this->location($this->data);
			break;



		case('plain'):
			return $this->plain($this->data);
			break;
		case('street'):

			if ($this->data['Address Street Number Position']=='Right') {
				return _trim($this->data['Address Street Name'].' '.$this->data['Address Street Type'].' '.$this->data['Address Street Number']);

			} else {
				return _trim($this->data['Address Street Number'].' '.$this->data['Address Street Name'].' '.$this->data['Address Street Type']);

			}

			switch ($this->data['Address Country Code']) {
			case('ESP'):
				return _trim($this->data['Address Street Type'].' '.$this->data['Address Street Name'].' '.$this->data['Address Street Number']);
				break;
			default:
				return _trim($this->data['Address Street Number'].' '.$this->data['Address Street Name'].' '.$this->data['Address Street Type']);

			}
			break;




		case('Country Divisions'):
			$division='';
			if ($this->data['Address Country Second Division']!='')
				$division.=','.$this->data['Address Country Second Division'];
			if ($this->data['Address Country First Division']!='')
				$division.=','.$this->data['Address Country First Division'];
			$division=preg_replace('/^,/','',$division);
			return $division;


		case('header'):

			$separator=', ';
			$address='';
			$header_address=_trim($this->data['Address Internal'].' '.$this->data['Address Building']);
			if ($header_address!='')
				$address.=$header_address.$separator;

			$street_address=$this->display('street');
			if ($street_address!='')
				$address.=$street_address.$separator;


			$subtown_address=$this->data['Address Town Second Division'];
			if ($this->data['Address Town First Division'])
				$subtown_address.=' ,'.$this->data['Address Town First Division'];
			$subtown_address=_trim($subtown_address);
			if ($subtown_address!='')
				$address.=$subtown_address.$separator;
			return _trim($address);
		case('label'):

			$separator="\n";
			$address=$this->get_formated_address($separator);
			$country_name=$this->get_localized_country_name($this->data['Address Country Code'],$locale);

			$address.=$country_name;
			return _trim($address);
			break;



		case('xhtml'):
		case('html'):
			$separator="<br/>";
		case('postal'):
		default:
			$address=$this->get_formated_address($separator,$locale);
			$country=new Country('code',$this->data['Address Country Code']);
			$address.=$country->get_country_name($locale);
			return _trim($address);

		}

	}


	function get_localized_country_name($code,$locale='en_GB') {


		if ($code=='ESP' and $locale=='es_ES') {
			return 'España';
		}

		$country=new Country('code',$code);




		return $country->data['Country Name'];


	}


	function get_formated_address($separator,$locale='en_GB') {
		$address='';

		if ($this->data['Military Address']=='Yes') {
			$address=$this->data['Military Installation Address'];
			$address_type=_trim($this->data['Military Installation Type']);
			if ($address_type!='')
				$address.=$separator.$address_type;
			$address_type=_trim($this->data['Address Postal Code']);
			if ($address_type!='')
				$address.=$separator.$address_type;
			$address.=$separator.$this->data['Address Country Name'];

		} else {

			$address='';
			if ($this->data['Address Contact']!='')
				$address.=_trim($this->data['Address Contact']).$separator;
			if ($this->data['Address Internal']!='')
				$address.=_trim($this->data['Address Internal']).$separator;
			if ($this->data['Address Building']!='')
				$address.=_trim($this->data['Address Building']).$separator;

			$street_address=$this->display('street',$locale);
			if ($street_address!='')
				$address.=$street_address.$separator;


			$subtown_address=$this->data['Address Town Second Division'];
			if ($this->data['Address Town First Division'])
				$subtown_address.=', '.$this->data['Address Town First Division'];

			$subtown_address=preg_replace('/^\,\s*/','',$subtown_address);
			$subtown_address=_trim($subtown_address);
			if ($subtown_address!='')
				$address.=$subtown_address.$separator;




			$town_address=_trim($this->data['Address Town']);
			if ($town_address!='')
				$address.=$town_address.$separator;

			if ($locale=='es_ES') {

				$ps_address=_trim($this->data['Address Postal Code'].' '.$this->data['Address Country Second Division']);
				if ($ps_address!='')
					$address.=$ps_address.$separator;
				if ($this->data['Address Country First Division']!='')
					$address.=$this->data['Address Country First Division'].$separator;





			}else {


				$ps_address=_trim($this->data['Address Postal Code']);
				if ($ps_address!='')
					$address.=$ps_address.$separator;
				if ($divisions=$this->display('Country Divisions',$locale)) {
					$address.=$divisions.$separator;
				}
			}

			return $address;
		}
	}


	function base_data($args='replace') {
		$data=array();

		if (preg_match('/3 line/i',$args)) {
			$data['Address Line 1']='';
			$data['Address Line 2']='';
			$data['Address Line 3']='';
			$data['Address Town SubDivision']='';
			$data['Address Town Division']='';
			$data['Address Town']='';
			$data['Address SubRegion']='';
			$data['Address Region']='';
			$data['Address Country Key']='';
			$data['Address Country Code']='';
			$data['Address Country Name']='';
			$data['Address Country Code']='';
			$data['Address Country 2 Alpha Code']='';

		} else {

			$ignore_fields=array('Address Key');

			$result = mysql_query("SHOW COLUMNS FROM `Address Dimension`");
			if (!$result) {
				echo 'Could not run query: ' . mysql_error();
				exit;
			}
			if (mysql_num_rows($result) > 0) {
				while ($row = mysql_fetch_assoc($result)) {
					if (!in_array($row['Field'],$ignore_fields))
						$data[$row['Field']]=$row['Default'];
				}
			}

		}
		return $data;
	}


	/*
          Function: location
          Get the address location

          Parameter:
          $str -  _array_ location data
        */

	public static function location($data,$flag='left') {

		if ($data['Military Address']=='Yes') {
			$location=sprintf('<img src="art/flags/%s.gif" title="%s"> %s',strtolower($data['Address Country 2 Alpha Code']),$data['Address Country Code'],$data['Military Installation Type']);
		} else {

			if ($data['Address Fuzzy']=='Yes') {
				if (preg_match('/country/i',$data['Address Fuzzy Type'])) {
					$location=sprintf('<img src="art/flags/%s.gif" title="%s"> %s',strtolower($data['Address Country 2 Alpha Code']),$data['Address Country Code'],_('Unknown'));
					return _trim($location);
				}
				elseif (preg_match('/town/i',$data['Address Fuzzy Type'])) {
					$location=sprintf('<img src="art/flags/%s.gif" title="%s"> %s',strtolower($data['Address Country 2 Alpha Code']),$data['Address Country Code'],_('Somewhere in').' '.$data['Address Country Name']);
					return _trim($location);
				}
			}

			if ($flag=='none')
				$location=sprintf('%s %s',$data['Address Town'],$data['Address Country Code']);
			else if ($flag=='right')
					$location=sprintf('%s <img src="art/flags/%s.gif" title="%s">',$data['Address Town'],strtolower($data['Address Country 2 Alpha Code']),$data['Address Country Code']);
				else
					$location=sprintf('<img src="art/flags/%s.gif" title="%s"> %s',strtolower($data['Address Country 2 Alpha Code']),$data['Address Country Code'],$data['Address Town']);


		}

		return _trim($location);
	}




	/*
          Function: is_street
          Check if the string id like a street

          Parameter:
          $str -  _string_ line to be checked
        */
	function is_street($string) {
		if ($string=='')
			return false;




		$string=_trim($string);
		// if(preg_match('/^\d+[a-z]?\s+\w|^\s*calle\s+|\s+close\s*$|/\s+lane\s*$|\s+street\s*$|\s+st\.?\s*$/i',$string))

		//print "Street: $string ";

		if (preg_match('/\s+rd\.?\s*$|\s+road\s*$|^\d+[a-z]?\s+\w|^\s*calle\s+|\s+close\s*$|\s+lane\s*$|\s+street\s*$|\s+st\.?\s*$/i',$string))
			$is_street= true;
		elseif (preg_match('/[a-z\-\#\,]{1,}\s*\d/i',$string))
			$is_street= true;

		elseif (preg_match('/\d.*[a-z]{1,}/i',$string))
			$is_street= true;

		elseif (preg_match('/^c\/\s?/i',$string))
			$is_street= true;
		else
			$is_street= false;

		// if($is_street)
		//  print "-> yes \n";
		//else
		//  print "-> no \n";

		return $is_street;

	}
	/*
          Function: is_internal
          Check if the string id like a internal address

          Parameter:
          $str -  _string_ line to be checked
        */
	function is_internal($string) {
		if ($string=='')
			return false;
		// if(preg_match('/^\d+[a-z]?\s+\w|^\s*calle\s+|\s+close\s*$|/\s+lane\s*$|\s+street\s*$|\s+st\.?\s*$/i',$string))

		if (preg_match('/lot\s*(n-)?\s*\d|suite\s*\d|shop\s*\d|apt\s*\d/i',$string))
			return true;
		else
			return false;
	}

	/*
          Function: get_country_d2_name
          Get the name of the Country SubDivision

          Parameters:
          $id - _integer_  *Country Second Division Code* in DB
        */
	function get_country_d2_name($id='') {
		if (!is_numeric($id))
			return '';
		$sql=sprintf("select `Country Second Division Name` as name from kbase.`Country Second Division Dimension` where `Country Second Division Code`=%d",$id);
		//  print $sql;
		$result = mysql_query($sql) or die('Query failedx1: ' . mysql_error());
		if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			return $row['name'];
		}
		return '';
	}
	/*
          Function: get_country_d1_name
          Get the name of the Country Division

          Parameters:
          $id - _integer_  *Country First Division Code* in DB

          function get_country_d1_name($id=''){
          if(!is_numeric($id))
          return '';
          $sql=sprintf("select `Country First Division Name` as name from kbase.`Country First Division Dimension` where `Country First Division Code`=%d",$id);
          $result = mysql_query($sql) or die('Query failedx: ' . mysql_error());
          if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
          return $row['name'];
          }
          return '';
          }



          Function: is_country_d1
          Look if the string is in Country First Division Dimension DB table

          The search will be en the following fields:Name

          Parameter:
          $str -  _string_ Country First Division Name
        */
	public static  function is_country_d1($country_d1,$country_2a_code) {
		if ($country_d1=='')
			return false;
		$extra_where='';
		if ($country_2a_code)
			$extra_where=sprintf('and `Country 2 Alpha Code`=%s',prepare_mysql($country_2a_code));
		$sql=sprintf("select `Geography Key` as id from kbase.`Country First Division Dimension` where `Country First Division Name`=%s %s"
			,prepare_mysql($country_d1)
			,$extra_where
		);


		$result = mysql_query($sql) or die('Query failedx2: ' .$sql. mysql_error());
		if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			return true;
		} else
			return false;
	}

	/*
          Function: is_country_d2
          Look if the string is in Country Second Division Dimension DB table

          The search will be en the following fields:Name

          Parameter:
          $str -  _string_ Country Second Division Name
        */
	public static  function is_country_d2($str,$country_2a_code=false,$country_d1_code=false) {
		if ($str=='')
			return false;
		$extra_where='';
		if ($country_2a_code)
			$extra_where.=sprintf('and `Country 2 Alpha Code`=%s',prepare_mysql($country_2a_code));
		if ($country_d1_code)
			$extra_where.=sprintf('and `Country First Division Code`=%s',prepare_mysql($country_d1_code));
		$sql=sprintf("select `Geography Key` as id from kbase.`Country Second Division Dimension` where `Country Second Division Name`=%s %s"
			,prepare_mysql($str)
			,$extra_where
		);


		$result = mysql_query($sql) or die('Query failedx4: ' . mysql_error());
		if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			return true;
		} else
			return false;
	}

	/*
          Function: is_country_key
          Look if is a valid country key

          Parameter:
          $key -  _integer_ Country Key in DB
        */
	public static  function is_country_key($key) {
		//    print "----------- $key -------\n";
		if (!is_numeric($key) or $key<=0) {
			return false;
		}
		$sql=sprintf("select `Country Key` from kbase.`Country Dimension`  where `Country Key`=%d",$key);
		//    PRINT $sql;
		$result = mysql_query($sql);
		if ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			return true;
		else
			return false;
	}

	public static function parse_country($country) {
		$len=strlen($country);
		if ($len==2) {
			$sql=sprintf("select `Country Code` from kbase.`Country Dimension`  where `Country 2 Alpha Code`=%s",prepare_mysql($country));
			$result = mysql_query($sql) ;
			if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				return $row['Country Code'];
			}
		}
		elseif ($len==3) {
			$sql=sprintf("select `Country Code` from kbase.`Country Dimension`  where `Country Code`=%s",prepare_mysql($country));
			$result = mysql_query($sql) ;
			if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				return $row['Country Code'];
			}
		}

		$sql=sprintf("select `Country Code` from kbase.`Country Dimension`  where `Country Name`=%s",prepare_mysql($country));
		$result = mysql_query($sql) ;
		if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			return $row['Country Code'];
		}

		$sql=sprintf("select `Country Alias Code` from kbase.`Country Alias Dimension` where `Country Alias`=%s",prepare_mysql($country));
		//  print "$sql\n";
		$result = mysql_query($sql) ;
		if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			return $row['Country Alias Code'];
		}

		return 'UNK';

	}


	/*
          Function: is_country_code
          Look if is a valid country 3 alpha code

          Parameter:
          $code -  _integer_ Country Code
        */
	public static  function is_country_code($code) {
		if (!preg_match('/^[a-z]{3}$/i',$code))
			return false;

		$sql=sprintf("select `Country Key` from kbase.`Country Dimension`  where `Country Code`=%s",prepare_mysql($code));
		$result = mysql_query($sql) ;
		if ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			return true;
		else
			return false;
	}

	/*
          Function: is_country_2alpha_code
          Look if is a valid country 2 alpha code

          Parameter:
          $code -  _integer_ Country 2 Alpha Code
        */
	public static function is_country_2alpha_code($code) {

		if (!preg_match('/^[a-z]{2}$/i',$code))
			return false;

		$sql=sprintf("select `Country Key` from kbase.`Country Dimension`  where `Country 2 Alpha Code`=%s",prepare_mysql($code));
		$result = mysql_query($sql) ;
		if ($row = mysql_fetch_array($result, MYSQL_ASSOC))
			return true;
		else
			return false;
	}


	/*
          Function: is_town
          Look if the town is registed in the DB

          Parameters:
          $town - _string_ Town name
          $country_id - (optional) _integer_ Country Key in DB
           */
	public static function is_town($town,$country_2acode=false) {
		if ($town=='')
			return false;

		if ($country_2acode)
			$sql=sprintf("select `Geography Key` as id from kbase.`Town Dimension` where `Town Name`=%s  and `Country 2 Alpha Code`=%s"
				,prepare_mysql($town)
				,prepare_mysql($country_2acode)
			);
		else
			$sql=sprintf("select `Geography Key` as id from kbase.`Town Dimension` where `Town Name`=%s "
				,prepare_mysql($town));

		//  print "$sql\n";
		$result = mysql_query($sql) or die('Query failedx6: ' . mysql_error());
		if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			return true;
		} else
			return false;
	}
	/*
         Function: parse_postcode
         Analize an beautify the postal code

         Parameters:
         $postcode - _string_ the postal code
         $country_code - (optional) _string_ Country Code

         Todo:
         In the moment only for GBR
         */
	function parse_postcode($postcode,$country_code='') {


		if (!preg_match('/^[a-z]{3}$/i',$country_code)) {
			$country_code=$this->get_default('Country Code');

		}
		$postcode=_trim($postcode);
		$data['Address Postal Code']=$postcode;
		$data['Address First Postal Code']='';
		$data['Address Second Postal Code']='';
		$data['Address Postal Code Separator']='';

		$country_code=strtoupper($country_code);
		switch ($country_code) {
		case 'GBR':
			$data['Address Postal Code Separator']=' ';
			$data['Address Postal Code']=preg_replace('/,?\s*scotland\s*$|united kingdom/i','',$data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/\s/','',$data['Address Postal Code']);
			if (preg_match('/^bfpo\s*\d/i',$data['Address Postal Code']) ) {
				$data['Address Postal Code']=preg_replace('/bfpo/i','BFPO ',$data['Address Postal Code']);
				$data['Address First Postal Code']='BFPO';
				$data['Address Second Postal Code']=preg_replace('/bfpo /i','',$data['Address Postal Code']);
			} else {
				$data['Address Postal Code']=substr($data['Address Postal Code'],0,strlen($data['Address Postal Code'])-3).' '.substr($data['Address Postal Code'],-3,3);
				$postcode_parts=preg_split('/ /',$data['Address Postal Code']);
				$data['Address First Postal Code']=$postcode_parts[0];
				$data['Address Second Postal Code']=$postcode_parts[1];
			}

			break;
		}
		return $data;

	}
	/*
         Function: is_valid_postcode
         Look if the postcode has a valid format

         Parameters:
         $postcode - _string_
         $country_id - (optional) _integer_ Country Key in DB

         Todo:
         In the moment only for GBR
         */
	public static function is_valid_postcode($postcode,$country_code) {
		// print "------------------";
		$postcode=_trim($postcode);
		switch ($country_code) {
		case 'GBR':

			if (preg_match('/^([A-PR-UWYZ][A-HK-Y0-9][A-HJKSTUW0-9]?[ABEHMNPRVWXY0-9]?\s?[0-9][ABD-HJLN-UW-Z]{2}|GIR 0AA|BT\d{2}\s*\d[a-z]2)$/i',$postcode))
				return true;
			else
				return false;
			break;
		}
		return false;

	}

	function update_country_code($code) {
		//print $code;
		$data=$this->prepare_country_data($code);
		$sql=sprintf('update `Address Dimension` set `Address Country Key`=%d,`Address Country Name`=%s,`Address Country Code`=%s,`Address Country 2 Alpha Code`=%s,`Address World Region`=%s,`Address Continent`=%s where `Address Key`=%d  '
			,$data['Address Country Key']
			,prepare_mysql($data['Address Country Name'])
			,prepare_mysql($data['Address Country Code'])

			,prepare_mysql($data['Address Country 2 Alpha Code'])
			,prepare_mysql($data['Address World Region'])
			,prepare_mysql($data['Address Continent'])
			,$this->id
		);
		mysql_query($sql);
		if (mysql_affected_rows()) {
			$this->updated=true;
		}
		$this->update=true;


	}

	/*
         Function:
         Prepare the Country Data


         */
	public static function prepare_country_data($data,$default_2alpha_code='XX') {
		//print "------\n";
		//print_r($data);
		//print "======\n";
		if (is_string($data)) {
			$code=$data;
			$data=array();
			$data['Address Country Key']='';
			$data['Address Country Code']=$code ;
			$data['Address Country 2 Alpha Code']='';
			$data['Address Country Name']='';
			$data['Address Country First Division']='';
			$data['Address Town']='';
			$data['Address Country Second Division']='';
		}

		if ($data['Address Country Key']=='' and
			$data['Address Country Code']=='' and
			$data['Address Country 2 Alpha Code']=='' and
			$data['Address Country Name']==''
		) {

			// try to get the counbtry form the town
			if ($data['Address Country First Division']!='') {
				$country_in_other=new Country('find',$data['Address Country First Division']);
				if ($country_in_other->id!=244) {
					$data['Address Country Key']=$country_in_other->id;
					$data['Address Country First Division']='';
				}
			}
			elseif ($data['Address Country Second Division']!='') {
				$country_in_other=new Country('find',$data['Address Country Second Division']);
				if ($country_in_other->id!=244) {
					$data['Address Country Key']=$country_in_other->id;
					$data['Address Country Second Division']='';
				}
			}
			else if ($data['Address Town']!='') {
					$country_in_other=new Country('find',$data['Address Town']);
					if ($country_in_other->id!=244) {
						$data['Address Country Key']=$country_in_other->id;
						$data['Address Town']='';
					}
				}

			if ($data['Address Country 2 Alpha Code']=='')
				$data['Address Country 2 Alpha Code']=$default_2alpha_code;

		}


		if ( $data['Address Country Key'] and   Address::is_country_key($data['Address Country Key'])) {

			$country=new Country('id',$data['Address Country Key']);
		} else if ( $data['Address Country Code']!='UNK'  and Address::is_country_code($data['Address Country Code'])) {

				$country=new Country('code',$data['Address Country Code']);
			}
		elseif ($data['Address Country 2 Alpha Code']!='XX' and  Address::is_country_2alpha_code($data['Address Country 2 Alpha Code'])) {
			$country=new Country('2 alpha code',$data['Address Country 2 Alpha Code']);
		}
		else {

			$country=new Country('find',$data['Address Country Name']);
		}

		//  print_r($country);
		$data['Address Country Key']=$country->id;
		$data['Address Country Code']=$country->data['Country Code'];
		$data['Address Country 2 Alpha Code']=$country->data['Country 2 Alpha Code'];
		$data['Address Country Name']=$country->data['Country Name'];
		$data['Address World Region']=$country->data['World Region'];
		$data['Address Continent']=$country->data['Continent'];
		// print_r($data);exit;
		return $data;
	}

	/*
         Function: parse_street
         Parse a street line in it components (number,street name,street type, etc)

         Parameters:
         $line - _string_
         $country_code - (optional)  Country Code in DB

         Todo:
         Country Id not used jet
         */
	public static function parse_street($line,$country_code='UNK') {

		// print "********** $line\n";

		$number='';
		$name='';
		$direction='';
		$type='';
		$position='Left';

		//extract number
		$line=_trim($line);


		if (preg_match('/^\#?\s*(\d.*\d|\d)[^\s]*/i',$line,$match)) {



			// if (preg_match('/^\#?\s*\d+(\,\d+\-\d+|\\\d+|\/\d+)?(bis)?[a-z]?\s*/i',$line,$match)) {

			$number=$match[0];
			$len=strlen($number);
			$name=substr($line,$len);
			$position='Left';
		}
		elseif (preg_match('/\#?\s*(\d.*\d|\d)[^\s]*$/i',$line,$match)) {
			// elseif(preg_match('/(\#|no\.?)?\s*\d+(bis)?[a-z]?\s*$/i',$line,$match)) {
			//  print "--------".$match[0]."-------------";
			$number=$match[0];
			$len=strlen($number);
			$name=_trim(substr($line,0,strlen($line)-$len));
			$position='Right';
		}
		else {
			$name=$line;

		}

		$name=preg_replace('/^\s*,\s*/','',$name);

		$name=_trim($name);
		$number=_trim($number);
		$regex='/\s(street|st\.?)$/i';
		if (preg_match($regex,$name,$match)) {
			$type="Street";
			$name=preg_replace($regex,'',$name);
		}

		if (preg_match('/\s(road|rd\.?)$/i',$name,$match)) {
			$type="Road";
			$name=preg_replace('/\s(road|rd\.?)$/i','',$name);
		}
		if (preg_match('/\s(close)$/i',$name,$match)) {
			$type="Close";
			$name=preg_replace('/\s(close)$/i','',$name);
		}
		$regex='/\s(Av\.?|avenue|ave\.?)$/i';
		if (preg_match($regex,$name,$match)) {
			$type="Avenue";
			$name=preg_replace($regex,'',$name);
		}


		$return_data=array(
			'Address Street Number'=>$number,
			'Address Street Name'=>$name,
			'Address Street Type'=>$type,
			'Address Street Direction'=>$direction,
			'Address Street Number Position'=>$position
		);
		//    print_r($return_data);
		return $return_data;

	}


	/*Function:prepare_DBfields
         Cleans address data, look for common errors
         */
	public static function prepare_DBfields($raw_data) {


		//  print "---------------------\n";

		$country_data=Address::prepare_country_data($raw_data);
		//print_r($country_data);
		foreach ($country_data as $key=>$value) {
			$raw_data[$key]=$value;
		}
		//  print_r($raw_data);
		//print "===================\n";
		return $raw_data;
	}
	/*Function: prepare_3line
         Cleans address data, look for common errors

         Parameters:
         $raw_data - _array_ Data to be parsed
         $untrusted - _boleean_
         */

	function prepare_3line($raw_data,$args='untrusted',$default_2alpha_code='XX') {




		if (!isset($raw_data['Address Line 1']))
			$raw_data['Address Line 1']='';
		if (!isset($raw_data['Address Line 2']))
			$raw_data['Address Line 2']='';
		if (!isset($raw_data['Address Line 3']))
			$raw_data['Address Line 3']='';
		$empty=true;

		if (count($raw_data)>0) {

			foreach ($raw_data as $key=>$val) {
				if ($val and ($key!='Address Fuzzy' and $key!='Address Data Last Update'and $key!='Address Input Format' and $key!='Military Address'  )) {
					//print "NO EMTOY KEY $key $val\n";
					$empty=false;
					break;
				}
			}
		}



		$untrusted=(preg_match('/untrusted/',$args)?true:false);
		$debug=(preg_match('/debug/',$args)?true:false);


		$data=array();
		// Equivalente to base data --------------------------------------
		$data=array();
		$ignore_fields=array('Address Key','Address Data Last Update','Address Data Creation');
		$result = mysql_query("SHOW COLUMNS FROM `Address Dimension`");
		if (!$result) {
			echo 'Could not run query: ' . mysql_error();
			exit;
		}
		if (mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_assoc($result)) {
				if (!in_array($row['Field'],$ignore_fields))
					$data[$row['Field']]=$row['Default'];
			}
		}
		//-------------------------------------------------------------------

		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key,$data)) {
				$data[$key]=_trim($value);
			}
		}



		if ($empty) {


			$country=new Country('code','UNK');
			$raw_data['Address Country Name']=$country->data['Country Name'];

			$data['Address Country Name']=$country->data['Country Name'];
			$data['Address Country Key']=$country->id;
			$data['Address Fuzzy']='Yes';
			$data['Address Fuzzy Type']='All';

			//print_r($data);
		}
		//--------------------------------------------------------------------------
		// Common errors related to the country



		if (preg_match('/^St. Thomas.*Virgin Islands$/i',$data['Address Town'])) {
			$data['Address Country Name']='Virgin Islands, U.S.';
			$data['Address Town']='St. Thomas';
		}

		if (preg_match('/Reunion|Réunion/i',$data['Address Country Name'])) {
			$data['Address Country Name']='France';
			if ($data['Address Country First Division']=='')
				$data['Address Country First Division']='Réunion';
		}
		if (preg_match('/Caledonia|Calédonie|Caledonie/i',$data['Address Country Name'])) {
			$data['Address Country Name']='France';
			if ($data['Address Country First Division']=='')
				$data['Address Country First Division']='New Caledonia';
		}
		if (preg_match('/Saint Martin|St Martin/i',$data['Address Country Name'])) {
			$data['Address Country Name']='France';
			if ($data['Address Country First Division']=='')
				$data['Address Country First Division']='Saint Martin';
		}
		//print_r($data);

		if (preg_match('/SCOTLAND|wales/i',$data['Address Country Name']))
			$data['Address Country Name']='United Kingdom';
		if (preg_match('/^england$|^inglaterra$/i',$data['Address Country Name'])) {
			$data['Address Country Name']='United Kingdom';
			if ($data['Address Country First Division']=='')
				$data['Address Country First Division']='England';
		} else if (preg_match('/^nor.*ireland$|n\.{2}ireland/i',$data['Address Country Name'])) {
				$data['Address Country Name']='United Kingdom';
				if ($data['Address Country First Division']=='')
					$data['Address Country First Division']='Northen Ireland';
			} else if (preg_match('/^r.*ireland$|^s.*ireland|^eire$/i',$data['Address Country Name'])) {
				$data['Address Country Name']='Ireland';
			} else if (preg_match('/me.ico|m.xico/i',$data['Address Country Name'])) {
				$data['Address Country Name']='Mexico';
			} else if (preg_match('/scotland|escocia/i',$data['Address Country Name'])) {

				$data['Address Country Name']='United Kingdom';
				if ($data['Address Country First Division']=='')
					$data['Address Country First Division']='Scotland';
			} else if (preg_match('/.*\s+(w|g)ales$/i',$data['Address Country Name'])) {
				$data['Address Country Name']='United Kingdom';
				if ($data['Address Country First Division']=='')
					$data['Address Country First Division']='Wales';
			} else if (preg_match('/canarias$/i',$data['Address Country Name'])) {
				$data['Address Country Name']='Spain';
				if ($data['Address Country First Division']=='')
					$data['Address Country First Division']='Canarias';
			} else if (preg_match('/^Channel Islands$/i',$data['Address Country Name'])) {

				if ($data['Address Country First Division']!='') {
					$data['Address Country Name']=$data['Address Country First Division'];
					$data['Address Country First Division']='';

				} else if ($data['Address Country Second Division']!='') {
						$data['Address Country Name']=$data['Address Country Second Division'];
						$data['Address Country Second Division']='';

					} else if ($data['Address Town']!='') {
						$data['Address Country Name']=$data['Address Town'];
						$data['Address Town']='';

					}



			}
		//-------------------------------------------------------------------------


		if ($data['Address Country Code']=='UNK') {
			$data=Address::prepare_country_data($data,$default_2alpha_code);
			$country=new Country('code','UNK');
			$raw_data['Address Country Name']=$country->data['Country Name'];

			$data['Address Country Name']=$country->data['Country Name'];
			$data['Address Country Key']=$country->id;
			$data['Address Fuzzy']='Yes';

		}



		if ($data['Address Country Name']=='') {
			if ( $default_2alpha_code   =='GB') {

				// if(preg_match('/norfork/i,'$data['Address Country Second Division]']))
				//  $data['Address Country Name']='United Kingdom';

				if (Address::is_valid_postcode($data['Address Postal Code'],'GBR')) {
					//   print "cacacaca";
					$data['Address Country First Division']=_trim($data['Address Country First Division'].' '.$data['Address Country Name']);
					$data['Address Country Name']='United Kingdom';

				}
				elseif (Address::is_valid_postcode($data['Address Country Name'],'GBR')) {
					$data['Address Country First Division']=_trim($data['Address Country First Division'].' '.$data['Address Postal Code']);
					$data['Address Postal Code']=$data['Address Country Name'];
					$data['Address Country Name']='United Kingdom';
				}
			}
			elseif ($default_2alpha_code =='ES') {

				//if( preg_match('/^\d{5}$/',_trim($raw_data['Address Country Name'])) and ( _trim($data['Address Postal Code'])=='' or preg_match('/^(spain|Espa.a)$/i',_trim($data['Address Postal Code'])))){
				if ( preg_match('/^\d{5}$/',_trim($raw_data['Address Country Name']))
					and ( _trim($data['Address Postal Code'])=='' or preg_match('/^(spain|espa.{0,2}a|ESPA.{0,2}A)$/i',_trim($data['Address Postal Code'])) )
				) {

					$data['Address Postal Code']=$data['Address Country Name'];
					$data['Address Country Code']='ESP';
					$data['Address Country Key']=false;
					if (preg_match('/^(spain|Espa.{0,2}a)$/i',_trim($data['Address Country First Division']))) {
						$data['Address Country First Division']='';
					}

					if (preg_match('/^(spain|Espa.{0,2}a)$/i',_trim($data['Address Country Second Division']))) {
						$data['Address Country Second Division']='';
					}
				}
			}
		}







		$data=Address::prepare_country_data($data,$default_2alpha_code);

		//print_r($data);
		// foreach($country as $key=>$value){
		//  if(array_key_exists($key,$data)){
		// $data[$key]=_trim($value);
		//  }
		// }

		if ($data['Address Country Code']=='UNK') {
			$_tmp=preg_replace('/^,|[,\.]$/','',$raw_data['Address Country Name']);
			$tmp=new Country('find',$_tmp);
			if ($tmp->data['Country Code']!='UNK') {
				$data['Address Country Key']=$tmp->id;
				$data=Address::prepare_country_data($data,$default_2alpha_code);
			}
		}








		$_p=$data['Address Postal Code'];

		if (preg_match('/^\s*BFPO\s*\d{1,}\s*$/i',$_p)) {
			$data['Address Country Name']='UK';
			$data=Address::prepare_country_data($data,$default_2alpha_code);


			//$data['Address Country Name']=preg_replace('/^,|[,\.]$/','',$data['Address Country Name']);
			//$tmp=new Country('find',$data['Address Country Name']);
			//$data['Address Country Key']=$tmp->id;

		}




		// Ok the country is already guessed, wat else ok depending of the country letys gloing to try to get the orthers bits of the address



		/*      print "________----------_________---------\n"; */
		/*     print_r($data); */
		/*     print_r($raw_data); */
		/*      print "___________________________________\n"; */


		// pushh all address up

		if ($untrusted) {


			if (preg_match('/^\d{1,}\s*\,\s*/',$raw_data['Address Line 1'])) {
				$raw_data['Address Line 1']=_trim(preg_replace('/,/',' ',$raw_data['Address Line 1'],1));
			}






			// if only one line put it in the first one
			$number_lines=0;
			if ($raw_data['Address Line 1']!='')
				$number_lines++;
			if ($raw_data['Address Line 2']!='')
				$number_lines++;
			if ($raw_data['Address Line 3']!='')
				$number_lines++;

			switch ($number_lines) {
			case(1):
				if ($raw_data['Address Line 2']!='') {
					$raw_data['Address Line 1']=$raw_data['Address Line 2'];
					$raw_data['Address Line 2']='';
				}
				elseif ($raw_data['Address Line 3']!='') {
					$raw_data['Address Line 1']=$raw_data['Address Line 3'];
					$raw_data['Address Line 3']='';
				}
				break;
			}


			// Special case only one line no twown no division

			if (
				$number_lines==1 and
				$data['Address Town']=='' and
				$data['Address Country First Division']=='' and
				$data['Address Country Second Division']==''
			) {
				// try to sepatate
				//split by worlds
				$words=preg_split('/\s+/',$raw_data['Address Line 1']);

				$num_words=count($words);
				if ($num_words>1) {
					if (Address::is_country_d1(
							$words[$num_words-1],
							$data['Address Country 2 Alpha Code']
						)) {
						$data['Address Country First Division']=array_pop($words);
						$num_words=count($words);
					}
				}
				if ($num_words>1) {
					if (Address::is_country_d2(
							$words[$num_words-1],
							$data['Address Country 2 Alpha Code']
						)) {
						$data['Address Country Second Division']=array_pop($words);
						$num_words=count($words);
					}
				}
				if ($num_words>1) {
					if (Address::is_town(
							$words[$num_words-1],
							$data['Address Country 2 Alpha Code']
						)) {
						$data['Address Town']=array_pop($words);
						$num_words=count($words);
					}
				}
				$raw_data['Address Line 1']=join(' ',$words);



			}



			//Change town if misplaced

			if ($data['Address Town']=='') {

				if (Address::is_town($raw_data['Address Line 3'],$data['Address Country 2 Alpha Code']) ) {
					$data['Address Town']=$raw_data['Address Line 3'];
					$raw_data['Address Line 3']='';
				} else if (Address::is_town($data['Address Country Second Division'],$data['Address Country 2 Alpha Code']) ) {
						$data['Address Town']=$data['Address Country Second Division'];
						$data['Address Country Second Division']='';
					}


			}// End town missplaced


			if (preg_match('/^\d[a-z]?(bis)?\s*,/',$raw_data['Address Line 1'])) {
				$raw_data['Address Line 1']=preg_replace('/\s*,\s*/',' ',$raw_data['Address Line 1']);
			}
			if (preg_match('/^\d[a-z]?(bis)?\s*,/',$raw_data['Address Line 2'])) {
				$raw_data['Address Line 2']=preg_replace('/\s*,\s*/',' ',$raw_data['Address Line 2']);
			}
			if (preg_match('/^\d[a-z]?(bis)?\s*,/',$raw_data['Address Line 3'])) {
				$raw_data['Address Line 3']=preg_replace('/\s*,\s*/',' ',$raw_data['Address Line 3']);
			}

			$raw_data['Address Line 1']=preg_replace('/,\s*$/',' ',$raw_data['Address Line 1']);
			$raw_data['Address Line 2']=preg_replace('/,\s*$/',' ',$raw_data['Address Line 2']);
			$raw_data['Address Line 3']=preg_replace('/,\s*$/',' ',$raw_data['Address Line 3']);


			// this is going to ve dirty
			//print_r($data);

			if ($this->is_street($raw_data['Address Line 2']) and  $raw_data['Address Line 1']!=''  and $raw_data['Address Line 3']==''  ) {
				$tmp=preg_split('/\s*,\s*/i',$raw_data['Address Line 1']);
				if (count($tmp)==2 and !preg_match('/^\d*$/i',$tmp[0])   and !preg_match('/^\d*$/i',$tmp[1]) ) {
					$raw_data['Address Line 3']=$raw_data['Address Line 2'];
					$raw_data['Address Line 1']=$tmp[0];
					$raw_data['Address Line 2']=$tmp[1];


				}

			}
			//  print_r($data);

			//print $raw_data['Address Line 1']."----------------\n";
			// print $raw_data['Address Line 2']."----------------\n";



			if ($raw_data['Address Line 1']=='') {
				if ($raw_data['Address Line 2']=='') {
					// if line 1 and 2  has not data
					$raw_data['Address Line 1']=$raw_data['Address Line 3'];
					$raw_data['Address Line 3']='';


				} else {

					if ($raw_data['Address Line 3']=='') {

						$raw_data['Address Line 1']=$raw_data['Address Line 2'];
						$raw_data['Address Line 2']='';

					} else {
						$raw_data['Address Line 1']=$raw_data['Address Line 2'];
						$raw_data['Address Line 2']=$raw_data['Address Line 3'];
						$raw_data['Address Line 3']='';
					}


				}

			} else if ($raw_data['Address Line 2']=='') {
					$raw_data['Address Line 2']=$raw_data['Address Line 3'];
					$raw_data['Address Line 3']='';
				}





			//then volter alas address


			//lets do it as an experiment if the only line is 1 has data
			// split the data in that line  to see what happens
			if ($raw_data['Address Line 1']!='' and $raw_data['Address Line 2']=='' and $raw_data['Address Line 3']=='') {// one line
				$splited_address=preg_split('/\s*,\s*/i',$raw_data['Address Line 1']);

				if (count($splited_address)==1) {
					$raw_data['Address Line 3']=$splited_address[0];
					$raw_data['Address Line 1']='';
				} else if (count($splited_address)==2) {
						// ok separeta bu on li if the sub partes are not like numbers
						$parte_0=_trim($splited_address[0]);
						$parte_1=_trim($splited_address[1]);




						$raw_data['Address Line 1']='';
						if (Address::is_internal($parte_0) and Address::is_street($parte_1)) {

							$raw_data['Address Line 1']=$parte_0;
							$raw_data['Address Line 3']=$parte_1;
						}
						elseif (Address::is_internal($parte_1) and Address::is_street($parte_0)) {
							$raw_data['Address Line 1']=$parte_1;
							$raw_data['Address Line 3']=$parte_0;
						}
						elseif (Address::is_street($parte_1) and Address::is_street($parte_0)) {
							$raw_data['Address Line 3']=$parte_0.', '.$parte_1;
						}
						elseif (Address::is_street($parte_0) and !Address::is_street($parte_1)) {
							$raw_data['Address Line 3']=$parte_0;
							$data['Address Town First Division'].=', '.$parte_1;
							$data['Address Town First Division']=preg_replace('/^, /','',$data['Address Town First Division']);
						}
						else
							$raw_data['Address Line 1']=$parte_0.', '.$parte_1;




						// exit ("$raw_data['Address Line 3']\n");
					} else if (count($splited_address)==3) {
						$raw_data['Address Line 1']=$splited_address[0];
						$raw_data['Address Line 2']=$splited_address[1];
						$raw_data['Address Line 3']=$splited_address[2];
					}

			} else if ($raw_data['Address Line 1']!='' and $raw_data['Address Line 2']!='' and  $raw_data['Address Line 3']=='') {
					$raw_data['Address Line 3']=$raw_data['Address Line 2'];
					$raw_data['Address Line 2']=$raw_data['Address Line 1'];
					$raw_data['Address Line 1']='';
				} else {

				// print_r($data);
				$raw_data['Address Line 1']=$raw_data['Address Line 1'];
				$raw_data['Address Line 2']=$raw_data['Address Line 2'];
				$raw_data['Address Line 3']=$raw_data['Address Line 3'];

			}

			// print("a1 $raw_data['Address Line 1'] a2 $raw_data['Address Line 2'] a3 $raw_data['Address Line 3'] \n");





			$data['Address Town']=$data['Address Town'];
			$data['Address Town Second Division']=$data['Address Town Second Division'];
			$data['Address Town First Division']=$data['Address Town First Division'];

			//  print "1:$raw_data['Address Line 1'] 2:$raw_data['Address Line 2'] 3:$raw_data['Address Line 3'] t:$data['Address Town'] \n";

			$f_a1=($raw_data['Address Line 1']==''?false:true);
			$f_a2=($raw_data['Address Line 2']==''?false:true);
			$f_a3=($raw_data['Address Line 2']==''?false:true);



			$f_t=($data['Address Town']==''?false:true);
			$f_ta=($data['Address Town Second Division']==''?false:true);
			$f_td=($data['Address Town First Division']==''?false:true);

			$f_c1=($data['Address Country First Division']==''?false:true);
			$f_c2=($data['Address Country Second Division']==''?false:true);
			$t_t=$this->is_town($data['Address Town']);
			$t_c1=$this->is_town($data['Address Country First Division']);
			$t_c2=$this->is_town($data['Address Country Second Division']);

			$s_a1=$this->is_street($raw_data['Address Line 1']);
			$s_a2=$this->is_street($raw_data['Address Line 2']);
			$s_a3=$this->is_street($raw_data['Address Line 3']);
			$i_a1=$this->is_internal($raw_data['Address Line 1']);
			$i_a2=$this->is_internal($raw_data['Address Line 2']);
			$i_a3=$this->is_internal($raw_data['Address Line 3']);


			// especial case when to town is presente but the first division seems to be a town
			if (!$f_t) {
				if ($t_c1 and !$f_c2) {
					// town is in first division
					$data['Address Town']=$data['Address Country First Division'];
					$data['Address Country First Division']='';
					$t_c1=false;
					$f_c1=false;
					$f_t=true;
					$t_t=true;
				}
				elseif ($t_c2 and !$f_c1) {
					$data['Address Town']=$data['Address Country Second Division'];
					$data['Address Country Second Division']='';
					$t_c2=false;
					$f_c2=false;
					$f_t=true;
					$t_t=true;


				}




			}


			if ($s_a1 and !$f_a2 and !$f_a3) {
				$raw_data['Address Line 3']=$raw_data['Address Line 1'];
				$raw_data['Address Line 1']='';
			}



			// print "Street grade 1-$s_a1 2-$s_a2 3-$s_a3 \n";
			//   print "Internal grade 1-$i_a1 2-$i_a2 3-$i_a3 \n";
			//   print "Filled grade 1-$f_a1 2-$f_a2 3-$f_a3 \n";
			//   exit;
			if (!$f_a1 and $f_a2 and $f_a3) {

				if ($s_a2 and $i_a3) {

					$_a=$raw_data['Address Line 3'];
					$raw_data['Address Line 3']=$raw_data['Address Line 2'];
					$raw_data['Address Line 2']=$_a;
				}

			}







			//   exit;

			// super special case
			//  if(!$f_a1 and $f_a2 and $f_a3 and )
			//print("a1 $raw_data['Address Line 1'] a2 $raw_data['Address Line 2'] a3 $raw_data['Address Line 3'] \n");
			$town_filled=false;
			// caso 1 all filled a1,a2 and a3
			if ($f_a1 and $f_a2 and $f_a3) { // caso 1 all filled a1,a2 and a3
				//print "AAAAAAAA\n";
				if ($s_a1 and !$s_a2 and !$s_a3) { //caso    soo  (moviing 2 )

					if (!$f_ta and !$f_td and !$f_t) { // caso ooo (towns)
						//print "AAAAAAAA\n";
						$town_filled=true;
						$data['Address Town']=$raw_data['Address Line 3'];
						$data['Address Town Second Division']=$raw_data['Address Line 2'];
						$raw_data['Address Line 3']=$raw_data['Address Line 1'];
						$raw_data['Address Line 2']='';
						$raw_data['Address Line 1']='';

					} else if (!$f_ta and !$f_td and $f_t) {// caso oot

							$data['Address Town First Division']=$raw_data['Address Line 3'];
							$data['Address Town Second Division']=$raw_data['Address Line 2'];
							$raw_data['Address Line 3']=$raw_data['Address Line 1'];
							$raw_data['Address Line 2']='';
							$raw_data['Address Line 1']='';

						} else {
						$raw_data['Address Line 3']=$raw_data['Address Line 1'].', '.$raw_data['Address Line 2'].', '.$raw_data['Address Line 3'];
						$raw_data['Address Line 2']='';
						$raw_data['Address Line 1']='';

					}
				} else if ((!$s_a1 and $s_a2 and !$s_a3) or ($s_a1 and $s_a2 and !$s_a3)) { //caso    oso OR  sso  (move one)
						//  print "HOLAAAAAAAAAAAA";
						if ($s_a1 and $s_a2 and !$f_a3 and $f_t) {
							$raw_data['Address Line 3']=$raw_data['Address Line 2'];
							$raw_data['Address Line 2']=$raw_data['Address Line 1'];
							$raw_data['Address Line 1']='';

						}
						elseif (!$f_ta and !$f_td and !$f_t) { // caso ooo (towns)
							$data['Address Town']=$raw_data['Address Line 3'];
							$raw_data['Address Line 3']=$raw_data['Address Line 2'];
							$raw_data['Address Line 2']=$raw_data['Address Line 1'];
							$raw_data['Address Line 1']='';
						}
						else if (!$f_ta and !$f_td and $f_t) {// caso oot
								$data['Address Town Second Division']=$raw_data['Address Line 3'];
								$raw_data['Address Line 3']=$raw_data['Address Line 2'];
								$raw_data['Address Line 2']=$raw_data['Address Line 1'];
								$raw_data['Address Line 1']='';
							} else {
							$raw_data['Address Line 3']=$raw_data['Address Line 2'].', '.$raw_data['Address Line 3'];
							$raw_data['Address Line 2']=$raw_data['Address Line 1'];
							$raw_data['Address Line 1']='';
						}
					}

			}
			elseif (!$f_a1 and $f_a2 and $f_a3) { // case xoo

				//print "1 ".$raw_data['Address Line 1']." 2 ".$raw_data['Address Line 2']." 3 ".$raw_data['Address Line 3']." \n";
				if ($s_a2 and   !$i_a3 and !$s_a3  ) {


					if (!$f_ta and !$f_td and !$f_t) { // caso ooo (towns)

						$data['Address Town']=$raw_data['Address Line 3'];
						$raw_data['Address Line 3']=$raw_data['Address Line 2'];
						$raw_data['Address Line 2']=$raw_data['Address Line 1'];
						$raw_data['Address Line 1']='';
					} else if (!$f_ta and !$f_td and $f_t) {// caso oot

							$data['Address Town Second Division']=$raw_data['Address Line 3'];
							$raw_data['Address Line 3']=$raw_data['Address Line 2'];
							$raw_data['Address Line 2']=$raw_data['Address Line 1'];
							$raw_data['Address Line 1']='';
							// print "*********************\n";
							// print_r($raw_data);
						} else {

						$raw_data['Address Line 3']=$raw_data['Address Line 2'].', '.$raw_data['Address Line 3'];
						$raw_data['Address Line 2']=$raw_data['Address Line 1'];
						$raw_data['Address Line 1']='';
					}


				}



			}




		}








		if (preg_match('/^P\.o\.box\s+\d+$|^po\s+\d+$|^p\.o\.\s+\d+$/i',$data['Address Town Second Division'])) {

			$po=$data['Address Town Second Division'];
			$data['Address Town Second Division']='';
			$po=preg_replace('/^P\.o\.box\s+|^po\s+|^p\.o\.\s+/i','PO BOX ',$po);
			if ($raw_data['Address Line 1']=='')
				$raw_data['Address Line 1']=$po;
			else
				$raw_data['Address Line 1']=$po.', '.$raw_data['Address Line 1'];

		}





		switch ($data['Address Country Key']) {
		case(30)://UK
			// ok try to determine the city from aour super database of cities and towns

			if (preg_match('/Andover.*\sHampshire/i',$data['Address Town']))
				$data['Address Town']='Andover';

			if ($town_filled) {
				if (Address::is_country_d2($data['Address Town'],$data['Address Country 2 Alpha Code']) and Address::is_town($data['Address Town Second Division'],$data['Address Country 2 Alpha Code'])) {
					$data['Address Country Second Division']=$data['Address Town'];
					$data['Address Town']=$data['Address Town Second Division'];
					$data['Address Town Second Division']='';
				}

			}



			if ($data['Address Town']=='') {
				if ($data['Address Town First Division']!='' ) {
					$data['Address Town']=$data['Address Town First Division'];
					$data['Address Town First Division']='';
				}
				elseif ($data['Address Town Second Division']!='') {
					$data['Address Town']=$data['Address Town Second Division'];
					$data['Address Town Second Division']='';
				}
				elseif ($raw_data['Address Line 3']!='' and ($raw_data['Address Line 2']!='' or $raw_data['Address Line 1']!='') ) {
					$data['Address Town']=$raw_data['Address Line 3'];
					$raw_data['Address Line 3']='';
				}
				else if ($raw_data['Address Line 2']!='' and $raw_data['Address Line 1']!='') {
						$data['Address Town']=$raw_data['Address Line 2'];
						$raw_data['Address Line 2']='';
					}

			}









			break;

		case(78)://Italy
			$data['Address Postal Code']=preg_replace('/italy|italia/i','',$data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/\s/i','',$data['Address Postal Code']);

			if ($data['Address Town']=='Padova') {
				$data['Address Country First Division']='Veneto';
				$data['Address Country Second Division']='Padova';
			}
			if ($data['Address Town']=='Mestre') {
				$data['Address Country First Division']='Venezia';
				$data['Address Country Second Division']='Veneto';
			}

			if (preg_match('/Genova\s*(\- Ge)?/i',$data['Address Town'])) {
				$data['Address Country First Division']='Genoa';
				$data['Address Country Second Division']='Liguria';
				$data['Address Town']='Genova';
			}

			if (preg_match('/Spilamberto/i',$raw_data['Address Line 3']) and preg_match('/Modena/i',$data['Address Town'])) {
				$data['Address Country First Division']='Emilia-Romagna';
				$data['Address Country Second Division']='Modena';
				$data['Address Town']='Spilamberto';
				$raw_data['Address Line 3']='';
			}

			if (preg_match('/Pescia/i',$raw_data['Address Line 3']) and preg_match('/Toscana/i',$data['Address Town'])) {
				$data['Address Country First Division']='Toscana';
				$data['Address Country Second Division']='Pistoia';
				$data['Address Town']='Pescia';
				$raw_data['Address Line 3']='';
			}

			if ( preg_match('/Villasor.*Cagliari/i',$data['Address Town'])) {
				$data['Address Country First Division']='Sardinia';
				$data['Address Country Second Division']='Cagliari';
				$data['Address Town']='Villasor';
			}
			if ( preg_match('/Nocera Superiore/i',$data['Address Town'])) {
				$data['Address Country First Division']='Campania';
				$data['Address Country Second Division']='Salerno';
				$data['Address Town']='Nocera Superiore';
			}
			if ( preg_match('/^Vicenza$/i',$data['Address Town'])) {
				$data['Address Country First Division']='Veneto';
				$data['Address Country Second Division']='Vicenza';
				$data['Address Town']='Vicenza';
			}

			if ( preg_match('/^Rome$/i',$data['Address Town'])) {
				$data['Address Country First Division']='Lazio';
				$data['Address Country Second Division']='Rome';
				$data['Address Town']='Rome';
			}
			$data['Address Postal Code']=_trim($data['Address Postal Code']);
			if (preg_match('/^\d{2}$/',$data['Address Postal Code']))
				$data['Address Postal Code']='000'.$data['Address Postal Code'];
			if (preg_match('/^\d{3}$/',$data['Address Postal Code']))
				$data['Address Postal Code']='00'.$data['Address Postal Code'];

			if (preg_match('/^\d{4}$/',$data['Address Postal Code']))
				$data['Address Postal Code']='0'.$data['Address Postal Code'];
			break;
		case(75)://Ireland

			// print "address1: $raw_data['Address Line 1']\n";
			//print "address2: $raw_data['Address Line 2']\n";
			//print "address3: $raw_data['Address Line 3']\n";
			//print "townarea: $data['Address Town Second Division']\n";
			//print "town: $data['Address Town']\n";
			//    print "country_d2: $data['Address Country Second Division']\n";
			//      print "postcode: $data['Address Postal Code']\n";

			$data['Address Postal Code']=_trim($data['Address Postal Code']);




			$data['Address Country Second Division']=_trim($data['Address Country Second Division']);
			$data['Address Postal Code']=preg_replace('/County COrK/i','',$data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/^co\.\s*|Republique of Ireland|Louth Ireland|ireland/i','',$data['Address Postal Code']);
			$data['Address Country Second Division']=preg_replace('/^co\.\s*|republic of ireland|republic of|ireland/i','',$data['Address Country Second Division']);
			$data['Address Country Second Division']=preg_replace('/(co|county)\s+[a-z]+$/i','',$data['Address Country Second Division']);
			$data['Address Country Second Division']=preg_replace('/(co|county)\s+[a-z]+,?\s*(ireland)?/i','',$data['Address Country Second Division']);
			$data['Address Country Second Division'] =preg_replace('/(co|county)\s+[a-z]+$/i','',$data['Address Country Second Division']);

			$data['Address Postal Code']=preg_replace('/\,+\s*^ireland$/i','',$data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/(co|county)\s+[a-z]+,?\s*(ireland)?/i','',$data['Address Postal Code']);
			$data['Address Town']=preg_replace('/(co|county)\s+[a-z]+$/i','',$data['Address Town']);

			if ($data['Address Town']=='Cork')
				$data['Address Postal Code']='';

			$data['Address Postal Code']=preg_replace('/co\s*Donegal|eire|republic of ireland|rep\? of Ireland|n\/a|^ireland$|/i','',$data['Address Postal Code']);
			$data['Address Postal Code']=_trim($data['Address Postal Code']);
			$data['Address Country Second Division']=_trim($data['Address Country Second Division']);
			//print "country_d2: $data['Address Country Second Division']\n";
			$data['Address Town']=preg_replace('/\-?\s*eire|\s*\-?\s*ireland/i','',$data['Address Town']);
			//exit;
			if ($data['Address Country Second Division']=='Wesstmeath')
				$data['Address Country Second Division']='Westmeath';

			if ($data['Address Town']=='Wesstmeath' or $data['Address Town']=='Westmeath' ) {
				$data['Address Town']='';
			}



			if (Address::is_town($data['Address Town Second Division'],$data['Address Country 2 Alpha Code'])
				and Address::is_country_d2($data['Address Town'],$data['Address Country 2 Alpha Code'])) {
				$county_d2=$data['Address Town'];
				$data['Address Town']=$data['Address Town Second Division'];
				$data['Address Town Second Division']='';

			}



			$data['Address Postal Code']=preg_replace('/Rep.?of/i','',$data['Address Postal Code']);
			$data['Address Postal Code']=str_replace(',','',$data['Address Postal Code']);
			$data['Address Postal Code']=str_replace('.','',$data['Address Postal Code']);
			$data['Address Postal Code']=str_replace('DUBLIN','',$data['Address Postal Code']);
			$data['Address Postal Code']=str_replace('N/A','',$data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/Republic\s?of/i','',$data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/Erie/i','',$data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/county/i','',$data['Address Postal Code']);

			$data['Address Postal Code']=preg_replace('/^co/i','County ',$data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/\s{2,}/',' ',$data['Address Postal Code']);
			$data['Address Postal Code']=_trim($data['Address Postal Code']);

			$valid_postalcodes=array('D1','D2','D3','D4','D5','D6','D6w','D7','D8','D9','D10','D11','D12','D13','D14','D15','D16','D17','D18','D20','D22','D24');

			if ($data['Address Postal Code']!='') {
				$sql="select `Country Second Division Name` as name from kbase.`Country Second Division Dimension` where  `Country Code`='IRL' and `Country Second Division Name` like '%".addslashes($data['Address Postal Code'])."%'";
				//print "$sql\n";

				$result=mysql_query($sql);
				if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

					$data['Address Postal Code']='';
					$data['Address Country Second Division']=$row['name'];

				}
			}
			// delete unganted  postcodes
			if (preg_match('/COMAYORepublicof|COGALWAY|RepublicofTIPPERARY|Republiqueof|NCW|eire|WD3|123|CoKerry,EIRE|COCORK|COOFFALY|WICKLOW|CoKerry/i',$data['Address Postal Code']))
				$data['Address Postal Code']='';

			if (preg_match('/^co\.?\s+|^country\s+/i',$data['Address Postal Code'])) {
				$data['Address Postal Code']='';
				if ($data['Address Country Second Division']=='')
					$data['Address Country Second Division']=$data['Address Postal Code'];
				$data['Address Postal Code']='';
			}

			$data['Address Town']=preg_replace('/\s+ireland\s*/i','',$data['Address Town']);
			$data['Address Country Second Division']=preg_replace('/\s+ireland\s*/i','',$data['Address Country Second Division']);


			$data['Address Town']=preg_replace('/co\.\s*/i','Co ',$data['Address Town']);
			$data['Address Town']=preg_replace('/county\s+/i','Co ',$data['Address Town']);

			// print "$data['Address Town']";
			$split_town=preg_split('/\s*-\s*|\s*,\s*/i',$data['Address Town']);
			if (count($split_town)==2) {
				if (preg_match('/^co\s+/i',$split_town[1])) {
					if ($data['Address Country Second Division']=='')
						$data['Address Country Second Division']=$split_town[1];
					$data['Address Town']=$split_town[0];
				}

			}


			if (preg_match('/^co\s+/i' ,$data['Address Town'])) {
				if ($data['Address Country Second Division']=='')
					$data['Address Country Second Division']=$data['Address Town'];
				$data['Address Town']=preg_replace('/^co\s+/i','',$data['Address Town']);
			}

			$data['Address Country Second Division']=preg_replace('/co\.?\s+/i','',$data['Address Country Second Division']);
			$data['Address Country Second Division']=preg_replace('/county\s+/i','',$data['Address Country Second Division']);

			if (preg_match('/\s*Cork\sCity\s*/i',$data['Address Town Second Division'])) {
				$data['Address Town Second Division']=='';
				if ($data['Address Town']=='')
					$data['Address Town']='Cock';
			}

			if (preg_match('/^dublin\s+\d+$/i',$data['Address Town Second Division'])) {

				if ($data['Address Town']=='')
					$data['Address Town']='Dublin';
				if ($data['Address Town First Division']=='')
					$data['Address Town First Division']=preg_replace('/dublin\s+/i','',$data['Address Town Second Division']);
				if ($data['Address Postal Code']==preg_replace('/dublin\s+/i','',$data['Address Town Second Division']))
					$data['Address Postal Code']='';
				$data['Address Town Second Division']=='';
			}


			if (preg_match('/^dublin\s*\d{1,2}$/i',$data['Address Postal Code'])) {
				$data['Address Postal Code']=preg_replace('/^dublin\s*/i','',$data['Address Postal Code']);
			}
			$data['Address Town']=_trim($data['Address Town']);

			//  print "$data['Address Town'] +++++++++++++++\n";
			$data['Address Town']=preg_replace('/\s*,?\s*Leinster/i','',$data['Address Town']);
			if (preg_match('/^dublin\s*6w$/i',$data['Address Town'])) {
				$data['Address Postal Code']='D6W';
				$data['Address Town']='Dublin';
			}

			//  print "$data['Address Town'] +++++++++++++++\n";
			if (preg_match('/^dublin\s*\-\s*\d$/i',$data['Address Town'])) {
				$data['Address Postal Code']=preg_replace('/^dublin\s*\-\s*/i','',$data['Address Town']);
				$data['Address Town']='Dublin';
			}

			if (preg_match('/^dublin\s*d?\d{1,2}$/i',$data['Address Town'])) {
				$data['Address Postal Code']=preg_replace('/^dublin\s*/i','',$data['Address Town']);
				$data['Address Town']='Dublin';
			}

			if (is_numeric($data['Address Postal Code']))
				$data['Address Postal Code']='D'.$data['Address Postal Code'];


			if ($data['Address Town']=='') {
				if ($data['Address Town First Division']!='' ) {
					$data['Address Town']=$data['Address Town First Division'];
					$data['Address Town First Division']='';
				}
				elseif ($data['Address Town Second Division']!='') {
					$data['Address Town']=$data['Address Town Second Division'];
					$data['Address Town Second Division']='';
				}
				elseif ($raw_data['Address Line 3']!='' and ($raw_data['Address Line 2']!='' or $raw_data['Address Line 1']!='') ) {
					$data['Address Town']=$raw_data['Address Line 3'];
					$raw_data['Address Line 3']='';
				}
				else if ($raw_data['Address Line 2']!='' and $raw_data['Address Line 1']!='') {
						$data['Address Town']=$raw_data['Address Line 2'];
						$raw_data['Address Line 2']='';
					}
			}

			$data['Address Postal Code']=str_replace('-','',$data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/MUNSTER|County RK/i','',$data['Address Postal Code']);
			$data['Address Postal Code']=_trim($data['Address Postal Code']);
			break;

		case(89)://Canada
			$data['Address Postal Code']=preg_replace('/\s*canada\s*/i','',$data['Address Postal Code']);

			if ($data['Address Country Second Division']!='' and $data['Address Country First Division']=='') {
				$data['Address Country First Division']=$data['Address Country Second Division'];
				$data['Address Country Second Division']='';
			}
			break;
		case(208)://Czech Republic
			$data['Address Postal Code']=preg_replace('/\s*Czech Republic\s*/i','',$data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/\s*/i','',$data['Address Postal Code']);
			break;
		case(108)://Cypruss
			$data['Address Postal Code']=preg_replace('/\s*cyprus\s*/i','',$data['Address Postal Code']);

			$data['Address Postal Code']=preg_replace('/^cy\-?/i','',$data['Address Postal Code']);

			if ($data['Address Town']=='Lefkosia (Nicosia)')
				$data['Address Town']='Nicosia';
			if ($data['Address Town']=='Limassol City Centre')
				$data['Address Town']='Limassol';

			if ($data['Address Town']=='Cyprus')
				$data['Address Town']='';

			if ($data['Address Town']=='') {
				if ($data['Address Town First Division']!='' ) {
					$data['Address Town']=$data['Address Town First Division'];
					$data['Address Town First Division']='';
				}
				elseif ($data['Address Town Second Division']!='') {
					$data['Address Town']=$data['Address Town Second Division'];
					$data['Address Town Second Division']='';
				}
				elseif ($raw_data['Address Line 3']!='' and ($raw_data['Address Line 2']!='' or $raw_data['Address Line 1']!='') ) {
					$data['Address Town']=$raw_data['Address Line 3'];
					$raw_data['Address Line 3']='';
				}
				else if ($raw_data['Address Line 2']!='' and $raw_data['Address Line 1']!='') {
						$data['Address Town']=$raw_data['Address Line 2'];
						$raw_data['Address Line 2']='';
					}
			}

			break;
		case(240):
			$data['Address Town']=preg_replace('/\,?\s*Guernsey Islands$/i','',$data['Address Town']);
			$data['Address Town']=preg_replace('/\,?\s*Guernsey$/i','',$data['Address Town']);
			$data['Address Town']=preg_replace('/\,?\s*Channel Islands$/i','',$data['Address Town']);
			$data['Address Town']=preg_replace('/\,?\s*CI$/i','',$data['Address Town']);
			$data['Address Town']=preg_replace('/\,?\s*C.I.$/i','',$data['Address Town']);

			if ($data['Address Town']=='') {
				if ($data['Address Town First Division']!='' ) {
					$data['Address Town']=$data['Address Town First Division'];
					$data['Address Town First Division']='';
				}
				elseif ($data['Address Town Second Division']!='') {
					$data['Address Town']=$data['Address Town Second Division'];
					$data['Address Town Second Division']='';
				}
				elseif ($raw_data['Address Line 3']!='' and ($raw_data['Address Line 2']!='' or $raw_data['Address Line 1']!='') ) {
					if (!preg_match('/^rue\s/i',$raw_data['Address Line 3'])) {
						$data['Address Town']=$raw_data['Address Line 3'];
						$raw_data['Address Line 3']=$raw_data['Address Line 2'];
						$raw_data['Address Line 2']='';
					}
				}
				else if ($raw_data['Address Line 2']!='' and $raw_data['Address Line 1']!='') {
						$data['Address Town']=$raw_data['Address Line 2'];
						$raw_data['Address Line 2']='';
					}




			}





			break;
		case(104):// Greece
			$data['Address Postal Code']=preg_replace('/greece/i','',$data['Address Postal Code']);

			$data['Address Postal Code']=preg_replace('/^(GK|T\.?k\.?)/i','',$data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/\s/i','',$data['Address Postal Code']);
			$data['Address Postal Code']=_trim($data['Address Postal Code']);

			if (preg_match('/^(Attica|Ionian Islands)$/i',$data['Address Town']))
				$data['Address Town']='';
			if ($data['Address Country First Division']=='Attoka') {
				$data['Address Country First Division']='Attica';

			}
			if ($data['Address Town']=='Athens')
				$data['Address Country First Division']='Attica';
			if ($data['Address Town']=='Salamina')
				$data['Address Country First Division']='Attica';
			if ($data['Address Town']=='Corfu') {
				$data['Address Town']='';
				$data['Address Country First Division']='Ionian Islands';
				$data['Address Country Second Division']='Corfu';
			}
			if ($data['Address Town']=='Kefalonia')
				$data['Address Country First Division']='Ionian Islands';
			if ($data['Address Town']=='Thessaloniki')
				$data['Address Country First Division']='Central Macedonia';

			if ($data['Address Town']=='Xania - Krete') {
				$data['Address Country First Division']='Crete';
				$data['Address Town']='Xania';
			}
			if ($data['Address Town']=='Salamina - Tsami') {
				$data['Address Country First Division']='Attica';
				$data['Address Town']='Salamina';
				if ($data['Address Town Second Division']=='')
					$data['Address Town Second Division']='Tsami';
			}


			break;

		case(229)://USA
			if ($data['Address Country Second Division']!='' and $data['Address Country First Division']=='') {
				$data['Address Country First Division']=$data['Address Country Second Division'];
				$data['Address Country Second Division']='';
			}
			$data['Address Town']=_trim($data['Address Town']);
			$data['Address Postal Code']=_trim($data['Address Postal Code']);
			//apo address
			if (preg_match('/^(09|96|340)\d+$/',$data['Address Postal Code'])) {


				$military_base='Yes';

				$raw_data['Address Line 1']=_trim($raw_data['Address Line 1'].' '.$raw_data['Address Line 2'].' '.$raw_data['Address Line 3'].' '.$data['Address Town'].' '.$data['Address Country Second Division'].' '.$data['Address Country First Division']);
				$raw_data['Address Line 2']='';
				$raw_data['Address Line 3']='';
				$data['Address Town']='';
				$data['Address Country Second Division']='';
				$data['Address Country First Division']='';
				$military_installation['address']=$raw_data['Address Line 1'];
				$military_installation['military base country key']='';
				$military_installation['military base postal code']=$data['Address Postal Code'];
				if (preg_match('/apo ae$/i',$raw_data['Address Line 1']) or preg_match('/\sapo ae\s+/i',$raw_data['Address Line 1'])) {
					$raw_data['Address Line 1']=_trim(preg_replace('/apo ae/i','',$raw_data['Address Line 1']));
					$military_installation['military base type']='APO AE';
				}
			}



			$data['Address Town']=preg_replace('/Lousiana/i','Louisiana',$data['Address Town']);

			$data['Address Country First Division']=_trim($data['Address Country First Division']);
			if (preg_match('/^[a-z]\s*[a-z]$/i',$data['Address Country First Division']))
				$data['Address Country First Division']=preg_replace('/\s/','',$data['Address Country First Division']);

			$data['Address Postal Code']=_trim($data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/united states of america/i','',$data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/\s*u\s*s\s*a\s*|^United States\s+|United Stated|usa|^united states$|^united states of america$|^america$/i','',$data['Address Postal Code']);
			$data['Address Postal Code']=_trim($data['Address Postal Code']);

			if ($data['Address Country First Division']=='') {
				$regex='/\s*\-?\s*[a-z]{2}\.?\s*\-?\s*/i';
				if (preg_match($regex,$data['Address Postal Code'],$match)) {
					$data['Address Country First Division']=preg_replace('/[^a-z]/i','',$match[0]);
					$data['Address Postal Code']=preg_replace($regex,'',$data['Address Postal Code']);
				}
				$regex='/\([a-z]{2}\)/i';
				if (preg_match($regex,$data['Address Town'],$match)) {
					$data['Address Country First Division']=preg_replace('/[^a-z]/i','',$match[0]);
					$data['Address Town']=preg_replace($regex,'',$data['Address Town']);
				}
				$regex='/\s{1,}\-?\s*[a-z]{2}\.?$/i';
				if (preg_match($regex,$data['Address Town'],$match)) {
					$data['Address Country First Division']=preg_replace('/[^a-z]/i','',$match[0]);
					$data['Address Town']=preg_replace($regex,'',$data['Address Town']);
				}
				if (Address::is_country_d1($data['Address Town'],$data['Address Country 2 Alpha Code']) and $data['Address Town Second Division']!='') {
					$data['Address Country First Division']=$data['Address Town'];
					$data['Address Town']=$data['Address Town Second Division'];
					$data['Address Town Second Division']='';
				}
			}


			//   print "$data['Address Postal Code'] ******** ";
			if ($data['Address Postal Code']=='' and preg_match('/\s*\d{4,5}\s*/',$data['Address Town'],$match)) {
				$data['Address Postal Code']=trim(trim($match[0]));
				$data['Address Town']=_trim(preg_replace('/\s*\d{4,5}\s*/','',$data['Address Town']));
			}

			$data['Address Town']=preg_replace('/\s*\-\s*$/','',$data['Address Town']);

			$town_split=preg_split('/\s*\-\s*|\s*,\s*/',$data['Address Town']);

			$data['Address Country First Division']=_trim($data['Address Country First Division']);

			if (count($town_split)==2 and Address::is_country_d1($town_split[1],$data['Address Country 2 Alpha Code'])) {

				$data['Address Country First Division']=$town_split[1];
				$data['Address Town']=$town_split[0];



			}



			if ($data['Address Country First Division']=='N Y')
				$data['Address Country First Division']='New York';

			$states=array('AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut','DE'=>'Delaware','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota','TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming');
			if (strlen($data['Address Country First Division'])==2) {
				if (array_key_exists(strtoupper($data['Address Country First Division']), $states)) {
					$data['Address Country First Division']=$states[strtoupper($data['Address Country First Division'])];
				}
			}

			if ($data['Address Country First Division']==$data['Address Country Second Division'])
				$data['Address Country Second Division']='';

			if ($data['Address Town First Division']=='Brooklyn' and $data['Address Town']=='New York') {
				$data['Address Country First Division']='New York';
			}
			$data['Address Postal Code']=_trim($data['Address Postal Code']);
			if (preg_match('/^d{4}$/',$data['Address Postal Code']))
				$data['Address Postal Code']='0'.$data['Address Postal Code'];

			break;
		case(105)://Croatia
			$data['Address Postal Code']=_trim($data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/croatia/i','',$data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/^hr-?/i','',$data['Address Postal Code']);
			$data['Address Postal Code']=_trim($data['Address Postal Code']);
			break;
		case(160)://Portugal

			$data['Address Postal Code']=_trim($data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/portugal/i','',$data['Address Postal Code']);
			$data['Address Town']=preg_replace('/\-?\s*portugal/i','',$data['Address Town']);


			if ($data['Address Postal Code']=='' and preg_match('/\s*\d{4}\s*/',$data['Address Town'],$match)) {
				$data['Address Postal Code']=trim(trim($match[0]));
				$data['Address Town']=_trim(preg_replace('/\s*\d{4}\s*/','',$data['Address Town']));
			}


			//   if(preg_match('/algarve/i'$data['Address Town']))


			if ($data['Address Town']=='') {
				if ($data['Address Town First Division']!='' ) {
					$data['Address Town']=$data['Address Town First Division'];
					$data['Address Town First Division']='';
				}
				elseif ($data['Address Town Second Division']!='') {
					$data['Address Town']=$data['Address Town Second Division'];
					$data['Address Town Second Division']='';
				}
				elseif ($raw_data['Address Line 3']!='' and ($raw_data['Address Line 2']!='' or $raw_data['Address Line 1']!='') ) {
					$data['Address Town']=$raw_data['Address Line 3'];
					$raw_data['Address Line 3']=$raw_data['Address Line 2'];
					$raw_data['Address Line 2']=$raw_data['Address Line 1'];
					$raw_data['Address Line 1']='';
				}
				else if ($raw_data['Address Line 2']!='' and $raw_data['Address Line 1']!='') {
						$data['Address Town']=$raw_data['Address Line 2'];
						$raw_data['Address Line 2']='';
					}
			}




			break;
		case(21)://Belgium
			$data['Address Postal Code']=_trim($data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/belgium/i','',$data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/^b\-?/i','',$data['Address Postal Code']);
			$data['Address Postal Code']=_trim($data['Address Postal Code']);
			$t=preg_split('/\s*,\s*/',$data['Address Town']);
			if (count($t)==2) {
				if (Address::is_country_d1($t[1],$data['Address Country 2 Alpha Code'])) {
					$data['Address Country First Division']=$t[1];
					$data['Address Town']=$t[0];
				}


			}

			$data['Address Town']=_trim($data['Address Town']);
			if (Address::is_country_d1($data['Address Town'],$data['Address Country 2 Alpha Code']) and $data['Address Country First Division']==''
				and ($raw_data['Address Line 2']!='' and $raw_data['Address Line 3']!='') ) {
				$data['Address Country First Division']=$data['Address Town'];
				$data['Address Town']='';

			}
			if ($data['Address Town']=='West Vlaanderen')
				$data['Address Town']=='West-Vlaanderen';

			if (Address::is_country_d1($data['Address Town'],$data['Address Country 2 Alpha Code']) and $data['Address Country First Division']==''
				and $data['Address Town Second Division']!=''  ) {
				$data['Address Country First Division']=$data['Address Town Second Division'];
				$data['Address Town Second Division']='';

			}




			break;


		case(80)://Austria
			$data['Address Postal Code']=_trim($data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/a\-?/i','',$data['Address Postal Code']);
			$data['Address Town']=_trim($data['Address Town']);
			if (Address::is_country_d1($data['Address Town'],$data['Address Country 2 Alpha Code']) and $data['Address Country First Division']==''  and ($raw_data['Address Line 2']!='' and $raw_data['Address Line 3']!='') ) {
				$data['Address Country First Division']=$data['Address Town'];
				$data['Address Town']='';

			}
			if (Address::is_country_d1($data['Address Town'],$data['Address Country 2 Alpha Code']) and $data['Address Country First Division']==''  and $data['Address Town Second Division']!=''  ) {
				$data['Address Country First Division']=$data['Address Town Second Division'];
				$data['Address Town Second Division']='';

			}




			break;
		case(15)://Australia
			$data['Address Postal Code']=preg_replace('/\s*australia\s*/i','',$data['Address Postal Code']);
			$regex='/\(QLD\)/i';
			if (preg_match($regex,$data['Address Town'])) {
				$data['Address Country First Division']='Queensland';
				$data['Address Town']=preg_replace($regex,'',$data['Address Town']);
			}
			$regex='/, Western Australia/i';
			if (preg_match($regex,$data['Address Town'])) {
				$data['Address Country First Division']='Western Australia';
				$data['Address Town']=preg_replace($regex,'',$data['Address Town']);
			}

			if ($data['Address Country Second Division']='' and $data['Address Country First Division']=='' ) {
				$data['Address Country First Division']=$data['Address Country Second Division'];
				$data['Address Country Second Division']='';
			}




			$data['Address Town']=_trim($data['Address Town']);

			if (Address::is_country_d1($data['Address Town'],$data['Address Country 2 Alpha Code']) and $data['Address Town Second Division']!='') {
				$data['Address Country First Division']=$data['Address Town'];
				$data['Address Town']=$data['Address Town Second Division'];
				$data['Address Town Second Division']='';

			}


			if (Address::is_country_d1($data['Address Town'],$data['Address Country 2 Alpha Code']) and $data['Address Country First Division']==''  and ($raw_data['Address Line 2']!='' and $raw_data['Address Line 3']!='') ) {
				$data['Address Country First Division']=$data['Address Town'];
				$data['Address Town']='';

			}

			if ($data['Address Town']=='') {
				if ($data['Address Town First Division']!='' ) {
					$data['Address Town']=$data['Address Town First Division'];
					$data['Address Town First Division']='';
				}
				elseif ($data['Address Town Second Division']!='') {
					$data['Address Town']=$data['Address Town Second Division'];
					$data['Address Town Second Division']='';
				}
				elseif ($raw_data['Address Line 3']!='' and ($raw_data['Address Line 2']!='' or $raw_data['Address Line 1']!='') ) {
					$data['Address Town']=$raw_data['Address Line 3'];
					$raw_data['Address Line 3']=$raw_data['Address Line 2'];
					$raw_data['Address Line 2']=$raw_data['Address Line 1'];
					$raw_data['Address Line 1']='';
				}
				else if ($raw_data['Address Line 2']!='' and $raw_data['Address Line 1']!='') {
						$data['Address Town']=$raw_data['Address Line 2'];
						$raw_data['Address Line 2']='';
					}
			}







			break;
		case(47)://Spain


			$raw_data['Address Line 3']=preg_replace('/^c\/\s*/i','Calle ',$raw_data['Address Line 3']);
			$regex_calle='/^C(alle)? [a-záéíóúñ\s]+,\s?\d+/i';

			if (preg_match($regex_calle,$raw_data['Address Line 3'],$match)) {

				$calle=$match[0];
				$tmp=preg_replace($regex_calle,'',$raw_data['Address Line 3']);
				$raw_data['Address Line 3']=$calle;
				$raw_data['Address Line 1'].=' '.$tmp;
			}


			//      $data['Address Line 3']=preg_replace('/^c\/\s*/i','Calle ',$raw_data['Address Line 3']);


			if (preg_match('/Majorca/i',$data['Address Town'])) {
				$data['Address Country Second Division']='Islas Baleares';
				$data['Address Country First Division']='Islas Baleares';
				$data['Address Town']='';
			}
			if (preg_match('/Balearic Islands|Balearic Island/i',$data['Address Country First Division']))
				$data['Address Country First Division']='Balearic Islands';
			if (preg_match('/Balearic Islands|Balearic Island/i',$data['Address Country Second Division']))
				$data['Address Country Second Division']='Balearic Islands';




			if (preg_match('/Baleares/i',$raw_data['Address Line 3']) and preg_match('/Palma de Mallorca/i',$raw_data['Address Line 2'])) {
				$data['Address Town']='Palma de Mallorca';
				$raw_data['Address Line 3']='';
				$raw_data['Address Line 2']='';
				$data['Address Country First Division']='Balearic Islands';
			}




			if (preg_match('/Zugena - Provincia Almeria/i',$data['Address Town'])) {
				$data['Address Country Second Division']='Almeria';
				$data['Address Town']='Zugena';
			}
			if (preg_match('/Hinojares - Juen/i',$data['Address Town'])) {
				$data['Address Country Second Division']='Jaen';
				$data['Address Town']='Hinojares';
			}


			if (preg_match('/Mijas Costa, Malaga/i',$data['Address Town'])) {
				$data['Address Country Second Division']='Malaga';
				$data['Address Town']='Mijas Costa';
			}
			if (preg_match('/Calvia - Mallorca/i',$data['Address Town'])) {
				$data['Address Town']='Calvia';
				$data['Address Country First Division']='Balearic Islands';
			}

			if (preg_match('/Ciutadella - Menorca/i',$data['Address Town'])) {
				$data['Address Town']='Ciutadella';
				$data['Address Country First Division']='Balearic Islands';
			}
			if (preg_match('/Sax\s*(Alicante)/i',$data['Address Town'])) {
				$data['Address Town']='Sax';
				$data['Address Country Second Division']='Alicante';
			}


			if (preg_match('/malaga/i',$data['Address Town'])) {
				if (preg_match('/Marbella/i',$raw_data['Address Line 3'])) {
					$raw_data['Address Line 3']='';
					$data['Address Town']='Marbella';
				}



			}

			$data['Address Postal Code']=_trim($data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/spain/i','',$data['Address Postal Code']);


			if ($data['Address Postal Code']=='' and preg_match('/\s*\d{4,5}\s*/',$data['Address Town'],$match)) {
				$data['Address Postal Code']=_trim($match[0]);
				$data['Address Town']=_trim(preg_replace('/\s*\d{4,5}\s*/','',$data['Address Town']));
			}




			if (preg_match('/^\d{4}$/',$data['Address Postal Code']))
				$data['Address Postal Code']='0'.$data['Address Postal Code'];

			$data['Address Country First Division']=_trim(preg_replace('/^Adaluc.a$/i','Andalusia',_trim($data['Address Country First Division'])));

			$data['Address Town']=_trim($data['Address Town']);

			if (preg_match('/El Cucador/i',$data['Address Town'])) {
				$data['Address Town Second Division']='El Cucador';
				$data['Address Town']='Zurgena';
				$data['Address Country Second Division']='Almeria';
				$data['Address Country First Division']='Andalusia';
				$data['Address Postal Code']='04661';
				if ($raw_data['Address Line 2']=='Cepsa Garage (Zugena)')
					$raw_data['Address Line 2']='';
			}
			if (preg_match('/^Arona$/i',$data['Address Town'])) {
				$data['Address Country Second Division']='Santa Cruz de Tenerife';
				$data['Address Country First Division']='Islas Canarias';

			}
			if (preg_match('/^Ceuta$/i',$data['Address Town'])) {

				$data['Address Country First Division']='Ceuta';

			}




			break;
		case(126)://Malta
			$data['Address Postal Code']=preg_replace('/malta/i','',$data['Address Postal Code']);
			$data['Address Postal Code']=_trim($data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/\s/i','',$data['Address Postal Code']);

			if (preg_match('/[a-z]*/i',$data['Address Postal Code'],$ap) and preg_match('/[0-9]{1,}/i',$data['Address Postal Code'],$xxx))
				$data['Address Postal Code']=$ap[0].' '.$xxx[0];

			$data['Address Town']=preg_replace('/-?\s*malta|gozo\s*\-?/i','',$data['Address Town']);

			$data['Address Town']=_trim($data['Address Town']);

			break;
		case(110)://Latvia
			$data['Address Postal Code']=_trim($data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/Latvia/i','',$data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/LV\s*\-?\s*/i','',$data['Address Postal Code']);
			$data['Address Town']=_trim($data['Address Town']);
			$data['Address Postal Code']=_trim($data['Address Postal Code']);
			if (preg_match('/^\d{4}$/',$data['Address Postal Code']))
				$data['Address Postal Code']='LV-'.$data['Address Postal Code'];
			break;

		case(117)://Luxembourg
			$data['Address Postal Code']=_trim($data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/Luxembourg/i','',$data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/L\s*\-?\s*/i','',$data['Address Postal Code']);
			$data['Address Town']=preg_replace('/\-?\s*Luxembourg/i','',$data['Address Town']);
			if ($data['Address Town']=='')
				$data['Address Town']='Luxembourg';
			$data['Address Town']=_trim($data['Address Town']);
			$data['Address Postal Code']=_trim($data['Address Postal Code']);
			if (preg_match('/^\d{4}$/',$data['Address Postal Code']))
				$data['Address Postal Code']='L-'.$data['Address Postal Code'];
			break;
		case(165)://France
			// print_r($data);
			$data['Address Postal Code']=_trim($data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/FRANCE|french republic/i','',$data['Address Postal Code']);
			if ($data['Address Postal Code']=='' and preg_match('/\s*\d{4,5}\s*/',$data['Address Town'],$match)) {
				$data['Address Postal Code']=trim(trim($match[0]));
				$data['Address Town']=preg_replace('/\s*\d{4,5}\s*/','',$data['Address Town']);
			}

			if (preg_match('/Digne les Bains|Dignes les Bains/i',$data['Address Town']))
				$data['Address Town']='Digne-les-Bains';

			$data['Address Town']=preg_replace('/,\s*france\s*$/i','',$data['Address Town']);

			if ($data['Address Town']=='St Cristophe - Charante') {
				$data['Address Town']='St Cristophe';
				$data['Address Country Second Division']='Charente';
				$data['Address Country First Division']='Poitou-Charentes';
			}
			if ($data['Address Town']=='Cauro - Corse Du Sud') {
				$data['Address Town']='Cauro';
				$data['Address Country Second Division']='Corse Du Sud';
				$data['Address Country First Division']='Corse';
			}

			if ($data['Address Town']=='Charente') {
				$data['Address Town']='';
				$data['Address Country Second Division']='Charente';
				$data['Address Country First Division']='Poitou-Charentes';
			}

			if ($data['Address Town']=='') {
				if ($data['Address Town First Division']!='' ) {
					$data['Address Town']=$data['Address Town First Division'];
					$data['Address Town First Division']='';
				}
				elseif ($data['Address Town Second Division']!='') {
					$data['Address Town']=$data['Address Town Second Division'];
					$data['Address Town Second Division']='';
				}
				elseif ($raw_data['Address Line 3']!='' and ($raw_data['Address Line 2']!='' or $raw_data['Address Line 1']!='') ) {
					$data['Address Town']=$raw_data['Address Line 3'];
					$raw_data['Address Line 3']=$raw_data['Address Line 2'];
					$raw_data['Address Line 2']=$raw_data['Address Line 1'];
					$raw_data['Address Line 1']='';
				}
				else if ($raw_data['Address Line 2']!='' and $raw_data['Address Line 1']!='') {
						$data['Address Town']=$raw_data['Address Line 2'];
						$raw_data['Address Line 2']='';
					}
			}
			$data['Address Postal Code']=_trim($data['Address Postal Code']);
			if (preg_match('/^\d{4}$/',$data['Address Postal Code']))
				$data['Address Postal Code']='0'.$data['Address Postal Code'];

			//print_r($data);


			break;

		case(196)://Switzerland
			$data['Address Postal Code']=_trim($data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/Switzerland/i','',$data['Address Postal Code']);

			if (preg_match('/^\d{4}\s+/',$data['Address Town'],$match)) {
				if ($data['Address Postal Code']=='' or $data['Address Postal Code']==trim($match[0])) {
					$data['Address Postal Code']=trim($match[0]);
					$data['Address Town']=preg_replace('/^\d{4}\s+/','',$data['Address Town']);
				}
			}

			$data['Address Postal Code']=preg_replace('/^CH\-/i','',$data['Address Postal Code']);
			break;
		case(193)://Findland
			$data['Address Postal Code']=_trim($data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/findland|finland/i','',$data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/^fi\s*\-?\s*/i','',$data['Address Postal Code']);

			if ($raw_data['Address Line 3']=='Klaukkala' and $data['Address Town']=='Nurmijarvi') {
				$raw_data['Address Line 3']='';
				$data['Address Town']='Klaukkala';
			}
			if (preg_match('/^\d{3}$/',$data['Address Postal Code']))
				$data['Address Postal Code']='00'.$data['Address Postal Code'];

			if (preg_match('/^\d{4}$/',$data['Address Postal Code']))
				$data['Address Postal Code']='0'.$data['Address Postal Code'];

			break;


		case(242)://Isle of man
			if ($data['Address Town']=='') {
				if ($data['Address Town First Division']!='' ) {
					$data['Address Town']=$data['Address Town First Division'];
					$data['Address Town First Division']='';
				}
				elseif ($data['Address Town Second Division']!='') {
					$data['Address Town']=$data['Address Town Second Division'];
					$data['Address Town Second Division']='';
				}
				elseif ($raw_data['Address Line 3']!='' and ($raw_data['Address Line 2']!='' or $raw_data['Address Line 1']!='') ) {
					$data['Address Town']=$raw_data['Address Line 3'];
					$raw_data['Address Line 3']='';
				}
				else if ($raw_data['Address Line 2']!='' and $raw_data['Address Line 1']!='') {
						$data['Address Town']=$raw_data['Address Line 2'];
						$raw_data['Address Line 2']='';
					}

			}






			break;


		case(241)://Jersey

			$data['Address Town']=preg_replace('/^jersey$|^jersey\s*c\.?i\.?$/i','',$data['Address Town']);
			$data['Address Town']=preg_replace('/\,?\s*Channel Islands$/i','',$data['Address Town']);
			$data['Address Town']=preg_replace('/\,?\s*CI$/i','',$data['Address Town']);
			$data['Address Town']=preg_replace('/\,?\s*C.I.$/i','',$data['Address Town']);
			$data['Address Town']=preg_replace('/\-?\s*jersey$/i','',$data['Address Town']);
			$data['Address Country Second Division']=preg_replace('/\-?\s*jersey$|Jersy Channel Isles/i','',$data['Address Country Second Division']);
			//  print "1$raw_data['Address Line 1'] 2$raw_data['Address Line 2'] 3$raw_data['Address Line 3']\n";
			if ($data['Address Town']=='') {
				if ($data['Address Town First Division']!='' ) {
					$data['Address Town']=$data['Address Town First Division'];
					$data['Address Town First Division']='';
				}
				elseif ($data['Address Town Second Division']!='') {
					$data['Address Town']=$data['Address Town Second Division'];
					$data['Address Town Second Division']='';
				}
				elseif ($raw_data['Address Line 3']!='' and ($raw_data['Address Line 2']!='' or $raw_data['Address Line 1']!='') ) {
					$data['Address Town']=$raw_data['Address Line 3'];
					$raw_data['Address Line 3']=$raw_data['Address Line 2'];
					$raw_data['Address Line 2']=$raw_data['Address Line 1'];
					$raw_data['Address Line 1']='';
				}
				else if ($raw_data['Address Line 2']!='' and $raw_data['Address Line 1']!='') {
						$data['Address Town']=$raw_data['Address Line 2'];
						$raw_data['Address Line 2']='';
					}
			}






			$data['Address Town']=_trim($data['Address Town']);
			if ($data['Address Town Second Division']=='' and  preg_match('/\w+\.?\s*St\.? Helier$/i',$data['Address Town']) ) {
				$data['Address Town Second Division']=_trim( preg_replace('/St\.? Helier$/i','',$data['Address Town']));
				$data['Address Town']='St Helier';
			}

			$data['Address Town Second Division']=preg_replace('/\./','',$data['Address Town Second Division']);
			$data['Address Town']=preg_replace('/^St\s{1,}/','St. ',$data['Address Town']);

			break;

		case(171)://Sweden
			$data['Address Postal Code']=_trim($data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/sweden/i','',$data['Address Postal Code']);

			$data['Address Postal Code']=preg_replace('/^SE\-?/i','',$data['Address Postal Code']);
			if ($data['Address Town']=='Malmo')
				$data['Address Town']='Malmö';
			if ($data['Address Country Second Division']=='Sweden')
				$data['Address Country Second Division']='';
			if (preg_match('/Skaraborg/i',$data['Address Town']))
				$data['Address Town']='';

			$data['Address Postal Code']=preg_replace('/\s/','',$data['Address Postal Code']);

			if (Address::is_country_d1($data['Address Town'],$data['Address Country 2 Alpha Code']) and   $raw_data['Address Line 1']='' and $raw_data['Address Line 2']!='' and $raw_data['Address Line 3']!='' ) {
				$data['Address Country First Division']=$data['Address Town'];
				$raw_data['Address Line 3']=$raw_data['Address Line 2'];
				$raw_data['Address Line 2']='';
			}
			if (Address::is_country_d1($data['Address Town'],$data['Address Country 2 Alpha Code']) and   $raw_data['Address Line 1']!='' and $raw_data['Address Line 2']!='' and $raw_data['Address Line 3']!='' ) {
				$data['Address Country First Division']=$data['Address Town'];
				$raw_data['Address Line 3']=$raw_data['Address Line 2'];
				$raw_data['Address Line 2']=$raw_data['Address Line 1'];
				$raw_data['Address Line 1']='';
			}

			if ($data['Address Country Second Division']!='' and $data['Address Country First Division']=='') {
				$data['Address Country First Division']=$data['Address Country Second Division'];
				$data['Address Country Second Division']='';
			}

			$data['Address Postal Code']=preg_replace('/\s/','',$data['Address Postal Code']);

			break;
		case(149)://Norway
			$data['Address Postal Code']=_trim($data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/norway/i','',$data['Address Postal Code']);

			if (preg_match('/^no.\d+$/i',$data['Address Town'])) {
				if ($data['Address Postal Code']=='') {
					$data['Address Postal Code']=$data['Address Town'];
					$data['Address Town']='';
				}
			}
			$data['Address Postal Code']=preg_replace('/^NO\s*\-?\s*/i','',$data['Address Postal Code']);

			$data['Address Postal Code']=preg_replace('/^N\-/i','',$data['Address Postal Code']);
			if (preg_match('/^\d{3}$/',$data['Address Postal Code']))
				$data['Address Postal Code']='0'.$data['Address Postal Code'];


			break;
		case(2)://Netherlands
			$data['Address Town']=preg_replace('/Noord Brabant/i','Noord-Brabant',$data['Address Town']);
			$data['Address Country First Division']=preg_replace('/Noord Brabant/i','Noord-Brabant',$data['Address Country First Division']);
			$data['Address Country Second Division']=preg_replace('/Noord Brabant/i','Noord-Brabant',$data['Address Country Second Division']);
			$data['Address Town']=preg_replace('/Zuid Holland/i','Zuid-Holland',$data['Address Town']);
			$data['Address Country First Division']=preg_replace('/Zuid Holland/i','Zuid-Holland',$data['Address Country First Division']);
			$data['Address Country Second Division']=preg_replace('/Zuid Holland/i','Zuid-Holland',$data['Address Country Second Division']);
			$data['Address Town']=preg_replace('/Noord Holland/i','Noord-Holland',$data['Address Town']);
			$data['Address Country First Division']=preg_replace('/Noord Holland/i','Noord-Holland',$data['Address Country First Division']);
			$data['Address Country Second Division']=preg_replace('/Noord Holland/i','Noord-Holland',$data['Address Country Second Division']);
			$data['Address Town']=preg_replace('/Gerderland/i','Gelderland',$data['Address Town']);


			$data['Address Postal Code']=_trim($data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/Netherlands|holland/i','',$data['Address Postal Code']);

			if ($data['Address Postal Code']=='') {
				if (preg_match('/\s*\d{4,6}\s*[a-z]{2}\s*/i',$data['Address Town'],$match2))
					$data['Address Postal Code']=_trim($match2[0]);
			}
			$data['Address Postal Code']=strtoupper($data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/\s/','',$data['Address Postal Code']);
			if (preg_match('/^\d{4}[a-z]{2}$/i',$data['Address Postal Code'])) {
				$data['Address Town']=str_replace($data['Address Postal Code'],'',$data['Address Town']);
				$data['Address Town']=str_replace(strtolower($data['Address Postal Code']),'',$data['Address Town']);
				$_postcode=substr($data['Address Postal Code'],0,4).' '.substr($data['Address Postal Code'],4,2);
				$data['Address Postal Code']=$_postcode;
				$data['Address Town']=str_replace($data['Address Postal Code'],'',$data['Address Town']);
				$data['Address Town']=str_replace(strtolower($data['Address Postal Code']),'',$data['Address Town']);

			}
			$data['Address Town']=_trim($data['Address Town']);
			if (Address::is_country_d1($raw_data['Address Line 3'],$data['Address Country 2 Alpha Code']) and $data['Address Country First Division']=='' and $data['Address Town']==''   and ($raw_data['Address Line 1']!='' and $raw_data['Address Line 2']!='') ) {
				$data['Address Country First Division']=$raw_data['Address Line 3'];
				$raw_data['Address Line 3']='';

			}

			if (Address::is_country_d1($data['Address Town'],$data['Address Country 2 Alpha Code']) and $data['Address Country First Division']=='' and (($raw_data['Address Line 1']!='' and $raw_data['Address Line 2']!='') or ($raw_data['Address Line 2']!='' and $raw_data['Address Line 3']!='') or ($raw_data['Address Line 1']!='' and $raw_data['Address Line 3']!='')  )   ) {
				$data['Address Country First Division']=$data['Address Town'];
				$data['Address Town']='';

			}


			if ($data['Address Town']=='NH') {
				$data['Address Country First Division']='North Holland';
				$data['Address Town']='';
			}

			if ($data['Address Town']=='Zuid Holland') {
				$data['Address Country First Division']='Zuid Holland';
				$data['Address Town']='';
			}
			similar_text($data['Address Country First Division'],$data['Address Country Second Division'],$w);
			if ($w>90) {
				$data['Address Country Second Division']='';
			}

			if ($data['Address Country First Division']=='' and $data['Address Country Second Division']!='') {
				$data['Address Country First Division']=$data['Address Country Second Division'];
				$data['Address Country Second Division']='';
			}

			if (preg_match('/Zuid.Holland|ZuidHolland/i',$data['Address Country First Division']))
				$data['Address Country First Division']='Zuid Holland';


			if ($data['Address Town']=='') {
				if ($data['Address Town First Division']!='' ) {
					$data['Address Town']=$data['Address Town First Division'];
					$data['Address Town First Division']='';
				}
				elseif ($data['Address Town Second Division']!='') {
					$data['Address Town']=$data['Address Town Second Division'];
					$data['Address Town Second Division']='';
				}
				elseif ($raw_data['Address Line 3']!='' and ($raw_data['Address Line 2']!='' or $raw_data['Address Line 1']!='') ) {
					$data['Address Town']=$raw_data['Address Line 3'];
					$raw_data['Address Line 3']=$raw_data['Address Line 2'];
					$raw_data['Address Line 2']=$raw_data['Address Line 1'];
					$raw_data['Address Line 1']='';
				}
				else if ($raw_data['Address Line 2']!='' and $raw_data['Address Line 1']!='') {
						$data['Address Town']=$raw_data['Address Line 2'];
						$raw_data['Address Line 2']='';
						$raw_data['Address Line 3']=$raw_data['Address Line 1'];
						$raw_data['Address Line 1']='';
					}
			}



			$town_split=preg_split('/\s*\-\s*|\s*,\s*/',$data['Address Town']);
			if (count($town_split)==2 and Address::is_country_d1($town_split[1],$data['Address Country 2 Alpha Code'])) {
				$data['Address Country First Division']=$town_split[1];
				$data['Address Town']=$town_split[0];
			}

			if ($raw_data['Address Line 1']!='' and $raw_data['Address Line 2']=='' and $raw_data['Address Line 3']=='') {
				$raw_data['Address Line 3']=$raw_data['Address Line 1'];
				$raw_data['Address Line 1']='';
			}




			break;


		case(177):// Germany
			$data['Address Postal Code']=_trim($data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/germany/i','',$data['Address Postal Code']);
			if ($data['Address Country Second Division']!='' and $data['Address Country First Division']=='') {
				$data['Address Country First Division']=$data['Address Country Second Division'];
				$data['Address Country Second Division']='';
			}


			$data['Address Town']=preg_replace('/NRW\s*$/i','',$data['Address Town']);


			if (preg_match('/^berlin$/i',$data['Address Town']))
				$data['Address Country First Division']='Berlin';
			if (preg_match('/^Hamburg$/i',$data['Address Town']))
				$data['Address Country First Division']='Hamburg';
			if (preg_match('/^Bremen$/i',$data['Address Town']))
				$data['Address Country First Division']='Bremen';

			if (preg_match('/^Nuernberg$/i',$data['Address Town']))
				$data['Address Town']='Nürnberg';

			if (preg_match('/^Osnabruek$/i',$data['Address Town'])) {
				$data['Address Country First Division']='Niedersachsen';
				$data['Address Town']='Osnabrück';
			}
			if (preg_match('/^bavaria$/i',$data['Address Country First Division']))
				$data['Address Country First Division']='Bayern';


			$regex='/^\s*\d{5}\s+|\s+\d{5}\s*$/';
			if (preg_match($regex,$data['Address Town'],$match)) {
				if ($data['Address Postal Code']=='')$data['Address Postal Code']=trim($match[0]);
				$data['Address Town']=preg_replace($regex,'',$data['Address Town']);
			}


			//     if($data['Address Country First Division']==''){
			// $data['Address Country First Division']=Address::get_country_d1_name($data['Address Town'],177);
			//}



			break;
		case(201)://Denmark
			// FIx postcode in town
			$data['Address Postal Code']=_trim($data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/denmark|Demnark/i','',$data['Address Postal Code']);
			$data['Address Postal Code']=preg_replace('/^dk\s*\-?\s*/i','',$data['Address Postal Code']);
			$data['Address Town']=_trim($data['Address Town']);

			if ($data['Address Postal Code']=='' and preg_match('/^\d{4}\s+/',$data['Address Town'],$match)) {
				$data['Address Postal Code']=trim($match[0]);
				$data['Address Town']=preg_replace('/^\d{4}\s+/','',$data['Address Town']);
			}

			$regex='/\s*2610 Rodovre\s*/i';
			if (preg_match($regex,$data['Address Town'],$match)) {
				$data['Address Town']='Rodovre';
				$data['Address Postal Code']='2610';
			}
			$regex='/KBH K|Kobenhavn/i';
			if (preg_match($regex,$data['Address Town'],$match)) {
				$data['Address Town']='Kobenhavn';
			}
			$regex='/Copenhagen/i';
			if (preg_match($regex,$data['Address Town'],$match)) {
				$data['Address Town']='Copenhagen';
			}
			$regex='/Aarhus C/i';
			if (preg_match($regex,$data['Address Town'],$match)) {
				$data['Address Town Second Division']='Aarhus C';
				$data['Address Town']='Aarhus';
			}


			$regex='/Odense\s*,?\s*/i';
			if (preg_match($regex,$data['Address Town'],$match)) {
				$data['Address Town']='Odense';
			}
			$regex='/\s*Odense\s*/i';
			if (preg_match($regex,$raw_data['Address Line 3'],$match)) {
				$raw_data['Address Line 3']='';
				$data['Address Town']='Odense';
			}

			$data['Address Postal Code']=_trim($data['Address Postal Code']);
			if (preg_match('/^\d{4}$/',$data['Address Postal Code'])) {
				$data['Address Postal Code']='DK-'.$data['Address Postal Code'];
			}
			if (preg_match('/^KLD$/i',$raw_data['Address Line 3']))
				$raw_data['Address Line 3']='';

			if (preg_match('/^DK\- 7470 Karup J$/i',$raw_data['Address Line 3'])) {
				$raw_data['Address Line 3']='';
				$data['Address Postal Code']='DK-7470';
				$data['Address Town']='Karup J';
			}


			if (preg_match('/Sjalland|Zealand|Sjælland|Sealand/i',$data['Address Country Second Division']))
				$data['Address Country Second Division']='';



			if (preg_match('/Sjalland|Zealand/i',$data['Address Town']))
				$data['Address Town']='';


			if ($raw_data['Address Line 3']=='' and $raw_data['Address Line 2']!='' and  $raw_data['Address Line 1']=='' ) {
				$raw_data['Address Line 3']=$raw_data['Address Line 2'];
				$raw_data['Address Line 2']=$raw_data['Address Line 1'];

			}


			if ($raw_data['Address Line 3']=='' and $raw_data['Address Line 2']!='' and  $raw_data['Address Line 1']!='' ) {
				$raw_data['Address Line 3']=$raw_data['Address Line 2'];
				$raw_data['Address Line 2']=$raw_data['Address Line 1'];
				$raw_data['Address Line 1']='';
			}

			if ($data['Address Town']=='') {
				if ($data['Address Town First Division']!='' ) {
					$data['Address Town']=$data['Address Town First Division'];
					$data['Address Town First Division']='';
				}
				elseif ($data['Address Town Second Division']!='') {
					$data['Address Town']=$data['Address Town Second Division'];
					$data['Address Town Second Division']='';
				}
				elseif ($raw_data['Address Line 3']!='' and ($raw_data['Address Line 2']!='' or $raw_data['Address Line 1']!='') ) {
					$data['Address Town']=$raw_data['Address Line 3'];
					$raw_data['Address Line 3']=$raw_data['Address Line 2'];
					$raw_data['Address Line 2']=$raw_data['Address Line 1'];
					$raw_data['Address Line 1']='';
				}
				else if ($raw_data['Address Line 2']!='' and $raw_data['Address Line 1']!='') {
						$data['Address Town']=$raw_data['Address Line 2'];
						$raw_data['Address Line 2']='';
						$raw_data['Address Line 3']=$raw_data['Address Line 1'];
						$raw_data['Address Line 1']='';
					}
			}






			break;
		default:
			$data['Address Postal Code']=$data['Address Postal Code'];
			$regex='/\s*'.$data['Address Country Name'].'\s*/i';
			$data['Address Postal Code']=preg_replace($regex,'',$data['Address Postal Code']);

		}


		if ($raw_data['Address Line 3']=='' and $raw_data['Address Line 2']!='' and  $raw_data['Address Line 1']=='' ) {
			$raw_data['Address Line 3']=$raw_data['Address Line 2'];
			$raw_data['Address Line 2']=$raw_data['Address Line 1'];

		}


		if ($raw_data['Address Line 3']=='' and $raw_data['Address Line 2']!='' and  $raw_data['Address Line 1']!='' ) {
			$raw_data['Address Line 3']=$raw_data['Address Line 2'];
			$raw_data['Address Line 2']=$raw_data['Address Line 1'];
			$raw_data['Address Line 1']='';
		}

		if ($data['Address Town']=='') {
			if ($data['Address Town First Division']!='' ) {
				$data['Address Town']=$data['Address Town First Division'];
				$data['Address Town First Division']='';
			}
			elseif ($data['Address Town Second Division']!='') {
				$data['Address Town']=$data['Address Town Second Division'];
				$data['Address Town Second Division']='';
			}
			elseif ($raw_data['Address Line 3']!='' and ($raw_data['Address Line 2']!='' or $raw_data['Address Line 1']!='') ) {
				$data['Address Town']=$raw_data['Address Line 3'];
				$raw_data['Address Line 3']=$raw_data['Address Line 2'];
				$raw_data['Address Line 2']=$raw_data['Address Line 1'];
				$raw_data['Address Line 1']='';
			}
			else if ($raw_data['Address Line 2']!='' and $raw_data['Address Line 1']!='') {
					$data['Address Town']=$raw_data['Address Line 2'];
					$raw_data['Address Line 2']='';
					$raw_data['Address Line 3']=$raw_data['Address Line 1'];
					$raw_data['Address Line 1']='';
				}
		}




		// Country ids
		if ($data['Address Country First Division']!='') {
			$sql=sprintf("select `Country First Division Code` as id  from  kbase.`Country First Division Dimension` where `Country First Division Name`=%s and `Country Code`=%s"
				,prepare_mysql($data['Address Country First Division'])
				,prepare_mysql($data['Address Country Code']));
			// print "$sql\n";

			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC))
				$data['Address Country First Division Code']=$row['id'];
		}


		if ($data['Address Country Second Division']!='') {
			$sql=sprintf("select `Country Second Division Code`  as id, `Country First Division Code`   from kbase.`Country Second Division Dimension`   where `Country Second Division Name`=%s   and `Country Code`=%s"

				,prepare_mysql($data['Address Country Second Division'])
				,prepare_mysql($data['Address Country Code'])
			);


			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {


				$data['Address Country Second Division Code']=$row['id'];
				if (mysql_num_rows($result)==1) {
					$data['Address Country First Division Code']=$row['Country First Division Code'];
				}

			} else
				$data['Address Country Second Division Code']=0;
		}



		$sql=sprintf("select `Geography Key` as id,`Country Second Division Code` , `Country First Division Code` from kbase.`Town Dimension` where (`Town Name`='%s'  ) and `Country Code`=%d",addslashes($data['Address Town']),addslashes($data['Address Town']),addslashes($data['Address Town']),$data['Address Country Code']);
		// print $sql;
		$res = mysql_query($sql);

		if (mysql_num_rows($res)==1) {

			$row=mysql_fetch_array($res, MYSQL_ASSOC);
			$data['Address Town Key']=$row['id'];
			if ($data['Address Country Second Division Code']==0)
				$data['Address Country Second Division Code']=$row['Country Second Division Code'];
			if ($data['Address Country First Division Code']==0)
				$data['Address Country First Division Code']=$row['Country First Division Code'];



		} else
			$data['Address Town Key']=0;




		if (preg_match('/\d+\s*\-\s*\d+/',$raw_data['Address Line 3'])) {
			$raw_data['Address Line 3']=preg_replace('/\s*\-\s*/','-',$raw_data['Address Line 3']);
		}
		if (preg_match('/\d+\s*\-\s*\d+/',$raw_data['Address Line 2'])) {
			$raw_data['Address Line 2']=preg_replace('/\s*\-\s*/','-',$raw_data['Address Line 2']);
		}
		$raw_data['Address Line 1']=  preg_replace('/^P\.o\.box\s+/i','PO BOX ',$raw_data['Address Line 1']);
		$raw_data['Address Line 2']=  preg_replace('/^P\.o\.box\s+/i','PO BOX ',$raw_data['Address Line 2']);
		$raw_data['Address Line 3']=  preg_replace('/^P\.o\.box\s+/i','PO BOX ',$raw_data['Address Line 3']);
		$raw_data['Address Line 3']=  preg_replace('/^p o box\s+/i','PO BOX ',$raw_data['Address Line 3']);
		$raw_data['Address Line 3']=  preg_replace('/^NULL$/i','',$raw_data['Address Line 3']);

		$raw_data['Address Line 1']=_trim($raw_data['Address Line 1']);
		$raw_data['Address Line 2']=_trim($raw_data['Address Line 2']);
		$raw_data['Address Line 3']=_trim($raw_data['Address Line 3']);
		$data['Address Town']=_trim($data['Address Town']);
		$data['Address Town First Division']=_trim($data['Address Town First Division']);
		$data['Address Town Second Division']=_trim($data['Address Town Second Division']);
		$data['Address Town']=preg_replace('/(\,|\-)$\s*/','',$data['Address Town']);

		foreach ($data as $key=>$val) {
			$data[$key]=_trim($val);
		}

		$street_data=Address::parse_street(_trim($raw_data['Address Line 3']));

		foreach ($street_data as $key=>$value) {
			if (array_key_exists($key,$data)) {
				$data[$key]=_trim($value);
			}
		}
		$data['Address Building']=$raw_data['Address Line 2'];
		$data['Address Internal']=$raw_data['Address Line 1'];

		$postcode_data=Address::parse_postcode(
			$data['Address Postal Code']
			,$data['Address Country Code']
		);

		foreach ($postcode_data as $key=>$value) {
			if (array_key_exists($key,$data)) {
				$data[$key]=_trim($value);
			}
		}



		$data['Address Fuzzy']='No';
		$data['Address Fuzzy Type']='';


		if ($raw_data['Address Line 1']==''
			and $raw_data['Address Line 2']==''
			and $raw_data['Address Line 3']=='' ) {
			$data['Address Fuzzy Type']='Street';
			$data['Address Fuzzy']='Yes';
		}




		if ($data['Address Town']=='') {
			$data['Address Fuzzy Type']='Town';
			$data['Address Fuzzy']='Yes';
		}
		if ($data['Address Country Code']=='UNK') {
			if (!$empty) {
				//print "UNKNOWN COUNTRY\n";
				//print_r($raw_data);
				// exit;
			}
			$data['Address Fuzzy Type']='All';
			$data['Address Fuzzy']='Yes';
		}


		$data['Address Fuzzy Type']=preg_replace('/^,\s*/','',$data['Address Fuzzy Type']);

		$data['Address Location']=Address::location($data);

		return $data;
	}

	/*
         Function:  plain
         OPlain addres ised as finger print and serach purpose
         */

	function plain($data,$locale='en_GB') {

		$separator=' ';
		if ($data['Military Address']=='Yes') {
			$address=$data['Military Installation Address'];
			$address_type=_trim($data['Military Installation Type']);
			if ($address_type!='')
				$address.=$separator.$address_type;
			$address_type=_trim($data['Address Postal Code']);
			if ($address_type!='')
				$address.=$separator.$address_type;


			$country=new Country('code',$this->data['Address Country Code']);
			$address.=$separator.$country->get_country_name($locale).' '.$this->data['Address Country Code'];




		} else {
			$address='';
			$header_address=_trim($data['Address Internal'].' '.$data['Address Building']);
			if ($header_address!='')
				$address.=$header_address.$separator;

			$street_address=_trim($data['Address Street Number'].' '.$data['Address Street Name'].' '.$data['Address Street Type']);
			if ($street_address!='')
				$address.=$street_address.$separator;


			$subtown_address=$data['Address Town Second Division'];
			if ($data['Address Town First Division'])
				$subtown_address.=' ,'.$data['Address Town First Division'];
			$subtown_address=_trim($subtown_address);
			if ($subtown_address!='')
				$address.=$subtown_address.$separator;



			$town_address=_trim($data['Address Town']);
			if ($town_address!='')
				$address.=$town_address.$separator;

			$ps_address=_trim($data['Address Postal Code']);
			if ($ps_address!='')
				$address.=$ps_address.$separator;

			$subcountry_address=$data['Address Country Second Division'];
			if ($data['Address Country First Division'])
				$subcountry_address.=' '.$data['Address Country First Division'];
			$subcountry_address=_trim($subcountry_address);
			if ($subcountry_address!='')
				$address.=$subcountry_address.$separator;



			$country=new Country('code',$this->data['Address Country Code']);
			$address.=$country->get_country_name($locale).' '.$this->data['Address Country Code'];






		}

		if ($data['Address Fuzzy']=='Yes') {
			$address='[FZ] '.$address;

		}

		return _trim($address);


	}


	/*
         Function: similarity
         Calculate the probability of been the same address
         Returns:
         Probability of been the same address _float_ (0-1)
         */

	function similarity($data,$address_key) {


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

		$where_scope=sprintf(' and `Subject Type`=%s',prepare_mysql($this->scope));

		$where_scope_key='';
		if ($this->scope_key)
			$where_scope_key=sprintf(' and `Subject Key`=%d',$this->scope_key);


		$sql=sprintf("select * from `Address Bridge` where `Address Key`=%d %s  %s  order by `Is Main`"
			,$this->id
			,$where_scope
			,$where_scope_key
		);
		$res=mysql_query($sql);


		$this->data['Address Type']=array();
		$this->data['Address Function']=array();
		$this->data['Address Is Main']=array();
		$this->data['Address Is Active']=array();

		$this->associated_with_scope=false;
		while ($row=mysql_fetch_array($res)) {
			$this->associated_with_scope=true;
			$this->data['Address Type'][$row['Address Type']]=$row['Address Type'];
			$this->data['Address Function'][$row['Address Function']]=$row['Address Function'];
			$this->data['Address Is Main'][$row['Address Type']]=$row['Is Main'];
			$this->data['Address Is Active'][$row['Address Type']]=$row['Is Active'];

		}



	}

	public function is_main($type=false) {
		if ($this->scope=='Company') {
			if (!$type)
				$type='Office';

		}
		elseif ($this->scope=='Contact') {
			if (!$type)
				$type='Work';
		}

		if (isset($this->data['Address Is Main'][$type]) and $this->data['Address Is Main'][$type]=='Yes')
			return true;
		else
			return false;

	}



	/*
               function: get_customer_key
               Returns the Customer Key if the contact is one
              */
	function get_customer_keys() {
		$sql=sprintf("select `Customer Key` from `Customer Dimension` where `Customer Type`='Person' and `Customer Main Address Key`=%d  ",$this->id);
		$customer_keys=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$customer_keys[$row['Customer Key']]= $row['Customer Key'];

		}
		return $customer_keys;
	}
	function get_company_key() {
		$sql=sprintf("select `Company Key` from `Company Dimension` where `Company Main Address Key`=%d  ",$this->id);
		//print $sql;
		$company_keys=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$company_keys[$row['Company Key']]= $row['Company Key'];

		}
		return $company_keys;
	}


	function get_contact_key() {
		$sql=sprintf("select `Contact Key` from `Contact Dimension` where `Contact Main Address Key`=%d  ",$this->id);
		$contact_keys=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$contact_keys[$row['Contact Key']]= $row['Contact Key'];

		}
		return $contact_keys;
	}


	function get_default($key) {

		if (!$this->default_country_code) {
			global $myconf;
			$this->default_country_code=$myconf['country_code'];
		}

		if (!isset($this->default_country))
			$this->default_country=new Country('code',$this->default_country_code);
		// print $this->default_country->get($key)." ".$this->default_country_code." $key sss\n";
		return $this->default_country->get($key);

	}





	function update_parents($parents=false,$add_parent_history=true) {

		if (!$parents) {
			$parents=array('Contact','Company','Customer','Supplier');
		}
		elseif (is_string($parents)) {
			$parents=array($parents);
		}
		foreach ($parents as $parent) {

			$sql=sprintf("select `$parent Key` as `Parent Key`   from  `$parent Dimension` where `$parent Main Address Key`=%d group by `$parent Key`",$this->id);
			//print "$sql\n";
			$res=mysql_query($sql);
			while ($row=mysql_fetch_array($res)) {
				$principal_address_changed=false;

				if ($parent=='Contact') {
					$parent_object=new Contact($row['Parent Key']);
					$old_princial_address=$parent_object->data[$parent.' Main XHTML Address'];
					$parent_label=_('Contact');
					$parent_object->editor=$this->editor;
					$parent_object->data[$parent.' Main XHTML Address']=$this->display('xhtml');
					$parent_object->data[$parent.' Main Plain Address']=$this->display('plain');

				}
				elseif ($parent=='Customer') {
					$parent_object=new Customer($row['Parent Key']);
					$old_princial_address=$parent_object->data[$parent.' Main XHTML Address'];
					$store=new Store($parent_object->data['Customer Store Key']);
					$locale=$store->data['Store Locale'];
					$parent_object->editor=$this->editor;
					$parent_label=_('Customer');


					$sql=sprintf('update `Customer Dimension` set `Customer Main Plain Postal Code`=%s, `Customer Main Address Incomplete`=%s where `Customer Key`=%d ',
						prepare_mysql(
							preg_replace('/[^a-z^A-Z^\d]/','',$this->data['Address Postal Code'])
							,false),
						prepare_mysql(($this->data['Address Fuzzy'])),
						$row['Parent Key']
					);
					mysql_query($sql);
					$lines=$this->display('3lines',$locale);

					$join_lines=$this->display('lines',$locale);

					$secondary_country_division=$this->data['Address Country Third Division'];
					if ($secondary_country_division!='')$secondary_country_division.=', ';
					$secondary_country_division.=$this->data['Address Country Second Division'];



					$this->data['Customer Main Address Line 1']=$lines[1];
					$this->data['Customer Main Address Line 2']=$lines[2];
					$this->data['Customer Main Address Line 3']=$lines[3];

					$this->data['Customer Main Address Lines']=$join_lines;
					$this->data['Customer Main Postal Code Country Second Division']=_trim($this->data['Address Postal Code'].' '.$secondary_country_division);
					$this->data['Customer Main Country Second Division']=_trim($secondary_country_division);

					$sql=sprintf('update `Customer Dimension` set `Customer Main Address Line 1`=%s,`Customer Main Address Line 2`=%s,`Customer Main Address Line 3`=%s,`Customer Main Address Lines`=%s,`Customer Main Postal Code Country Second Division`=%s,`Customer Main Country Second Division`=%s where `Customer Key`=%d '


						,prepare_mysql($this->data['Customer Main Address Line 1'],false)
						,prepare_mysql($this->data['Customer Main Address Line 2'],false)
						,prepare_mysql($this->data['Customer Main Address Line 3'],false)
						,prepare_mysql($this->data['Customer Main Address Lines'],false)
						,prepare_mysql($this->data['Customer Main Postal Code Country Second Division'],false)
						,prepare_mysql($this->data['Customer Main Country Second Division'],false)


						,$parent_object->id
					);

					mysql_query($sql);


					$sql=sprintf("select `Customer Key`  from  `Customer Dimension` where `Customer Main Delivery Address Key`=%d group by `Customer Key`",$this->id);
					$res2=mysql_query($sql);
					while ($row2=mysql_fetch_array($res2)) {




						$sql=sprintf('update `Customer Dimension` set `Customer XHTML Main Delivery Address`=%s,`Customer Main Delivery Address Lines`=%s,`Customer Main Delivery Address Town`=%s,`Customer Main Delivery Address Country`=%s ,`Customer Main Delivery Address Postal Code`=%s,`Customer Main Delivery Address Country Code`=%s,`Customer Main Delivery Address Country 2 Alpha Code`=%s,`Customer Main Delivery Address Country Key`=%d  where `Customer Key`=%d '
							,prepare_mysql($this->display('xhtml',$locale))
							,prepare_mysql($this->display('lines',$locale),false)
							,prepare_mysql($this->data['Address Town'],false)
							,prepare_mysql($this->data['Address Country Name'])
							,prepare_mysql($this->data['Address Postal Code'],false)
							,prepare_mysql($this->data['Address Country Code'])
							,prepare_mysql($this->data['Address Country 2 Alpha Code'])
							,$this->data['Address Country Key']
							,$row2['Customer Key']
						);

						mysql_query($sql);

						//print $sql."\n";

					}


					$sql=sprintf("select `Customer Key`  from  `Customer Dimension` where `Customer Billing Address Key`=%d group by `Customer Key`",$this->id);
					$res2=mysql_query($sql);
					while ($row2=mysql_fetch_array($res2)) {
$lines=$this->display('3lines',$locale);


						$sql=sprintf('update `Customer Dimension` set `Customer XHTML Billing Address`=%s,`Customer Billing Address Lines`=%s,
						`Customer Billing Address Line 1`=%s,
						`Customer Billing Address Line 2`=%s,
						`Customer Billing Address Line 3`=%s,
						
						`Customer Billing Address Town`=%s,
												`Customer Billing Address Postal Code`=%s,

						`Customer Billing Address Country Code`=%s,
						`Customer Billing Address 2 Alpha Country Code`=%s
						
						where `Customer Key`=%d '
							,prepare_mysql($this->display('xhtml',$locale))
							,prepare_mysql($this->display('lines',$locale),false)
							,prepare_mysql($lines[1],false)
							,prepare_mysql($lines[2],false)
							,prepare_mysql($lines[3],false)
							,prepare_mysql($this->data['Address Town'],false)
							,prepare_mysql($this->data['Address Postal Code'],false)

							,prepare_mysql($this->data['Address Country Code'])
							,prepare_mysql($this->data['Address Country 2 Alpha Code'])
							
							
							,$row2['Customer Key']
						);
						//print "$sql\n";
						mysql_query($sql);
					}
					$parent_object->data[$parent.' Main XHTML Address']=$this->display('xhtml',$locale);
					$parent_object->data[$parent.' Main Plain Address']=$this->display('plain',$locale);

					$parent_object->update_postal_address();




				}
				elseif ($parent=='Supplier') {
					$parent_object=new Supplier($row['Parent Key']);
					$old_princial_address=$parent_object->data[$parent.' Main XHTML Address'];
					$parent_label=_('Supplier');
					$parent_object->data[$parent.' Main XHTML Address']=$this->display('xhtml');
					$parent_object->data[$parent.' Main Plain Address']=$this->display('plain');

				}
				elseif ($parent=='Company') {
					$parent_object=new Company($row['Parent Key']);
					$old_princial_address=$parent_object->data[$parent.' Main XHTML Address'];
					$parent_label=_('Company');
					$parent_object->data[$parent.' Main XHTML Address']=$this->display('xhtml');
					$parent_object->data[$parent.' Main Plain Address']=$this->display('plain');

				}


				$parent_object->editor=$this->editor;
				

				$parent_object->data[$parent.' Main Country Key']=$this->data['Address Country Key'];
				$parent_object->data[$parent.' Main Country']=$this->data['Address Country Name'];
				$parent_object->data[$parent.' Main Country Code']=$this->data['Address Country Code'];
				$parent_object->data[$parent.' Main Location']=$this->location($this->data);


				$sql=sprintf("update `$parent Dimension` set `$parent Main Plain Address`=%s,`$parent Main XHTML Address`=%s,`$parent Main Country Key` =%d,`$parent Main Country` =%s,`$parent Main Country Code` =%s,`$parent Main Location` =%s where `$parent Key`=%d"
					,prepare_mysql($parent_object->data[$parent.' Main Plain Address'])
					,prepare_mysql($parent_object->data[$parent.' Main XHTML Address'])
					,$parent_object->data[$parent.' Main Country Key']
					,prepare_mysql($parent_object->data[$parent.' Main Country'])
					,prepare_mysql($parent_object->data[$parent.' Main Country Code'])
					,prepare_mysql($parent_object->data[$parent.' Main Location'])
					,$parent_object->id
				);
				mysql_query($sql);
				if ($parent=='Customer') {
					$sql=sprintf("update `$parent Dimension` set `$parent Main Town` =%s,`$parent Main Postal Code` =%s,`$parent Main Country First Division` =%s ,`Customer Main Country 2 Alpha Code`=%s where `$parent Key`=%d"
						,prepare_mysql($this->data['Address Town'])
						,prepare_mysql($this->data['Address Postal Code'])
						,prepare_mysql($this->data['Address Country First Division'])
						,prepare_mysql($this->data['Address Country 2 Alpha Code'])

						,$parent_object->id
					);
					mysql_query($sql);
				}



				if ($old_princial_address!=$parent_object->data[$parent.' Main XHTML Address'])
					$principal_address_changed=true;




				if ($principal_address_changed and $add_parent_history) {

					if ($old_princial_address=='') {

						$history_data['History Abstract']='Address Associated';
						$history_data['History Details']='<div class="history_address" style="border:1px solid grey;padding:5px;width:250px">'.$this->display('xhtml')."</div> "._('address associated with')." ".$parent_object->get_name()." ".$parent_label;
						$history_data['Action']='associated';
						$history_data['Direct Object']=$parent;
						$history_data['Direct Object Key']=$parent_object->id;
						$history_data['Indirect Object']='Address';
						$history_data['Indirect Object Key']=$this->id;

					} else {
						$history_data['History Abstract']='Address Changed';
						$history_data['History Details']=_('Address changed from').' <div  class="history_address" style="border:1px solid grey;padding:5px;width:250px">'.$old_princial_address.'</div> '._('to').' <div  class="history_address" style="border:1px solid grey;padding:5px;width:250px">'.$this->display('xhtml')."</div> "._('in')." ".$parent_object->get_name()." ".$parent_label;
						$history_data['Action']='changed';
						$history_data['Direct Object']=$parent;
						$history_data['Direct Object Key']=$parent_object->id;
						$history_data['Indirect Object']='Address';
						$history_data['Indirect Object Key']=$this->id;


					}

					if ($parent=='Customer') {

						$parent_object->add_customer_history($history_data);
					}else {
						
						//print_r($history_data);	
						$this->add_history($history_data);
					}

				}

			}
		}





	}



	function disassociate_telecom($telecom_key,$type,$swap_principal=true) {



		$principal_adddess=$this->get_principal_telecom_key($type);
		$sql=sprintf("delete from `Telecom Bridge` where `Subject Type`='Address' and  `Subject Key`=%d and `Telecom Key`=%d ",
			$this->id,
			$telecom_key

		);
		mysql_query($sql);

		$telecom_keys=$this->get_telecom_type_keys($type);



		if (count($telecom_keys)==0 ) {

			$sql=sprintf("update `Address Dimension` set `Address Main %s Key`=0 , `Address Main Plain %s`='', `Address Main XHTML %s`=''  where `Address Key`=%d ",
				addslashes($type),
				addslashes($type),
				addslashes($type),
				$this->id);
			mysql_query($sql);
			//   print "$sql\n";

		}else if ($principal_adddess==$telecom_key and $swap_principal) {

				$new_principal_key=array_pop($telecom_keys);

				$this->update_principal_telecom($new_principal_key,$type);

			}
		$this->updated_data['telecom_key']=$telecom_key;




	}



	function associate_telecom($telecom_key,$type,$swap_principal=true) {
		//print "$telecom_key";
		$telecom_keys=$this->get_telecom_type_keys($type);
		//print_r($telecom_keys);
		if (!array_key_exists($telecom_key,$telecom_keys)) {
			$this->create_telecom_bridge($telecom_key,$type,$swap_principal);
		}
		$this->updated_data['telecom_key']=$telecom_key;
	}



	function associate_telecom_to_parents($type='Telephone',$parent,$parent_key,$telecom_key,$set_as_main=true) {


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
		elseif ($parent=='Contact') {
			$parent_object=new Contact($parent_key);
			$parent_label=_('Contact');
		}
		$parent_object->editor=$this->editor;

		$parent_telecoms=$parent_object->get_telecom_keys($type);

		if (!array_key_exists($telecom_key,$parent_telecoms)) {
			$sql=sprintf("insert into  `Telecom Bridge` (`Telecom Key`,`Subject Type`, `Subject Key`,`Is Main`) values (%d,'$parent',%d,'No')  "
				,$telecom_key
				,$parent_object->id
			);
			mysql_query($sql);
		}
		//print "$sql\n";
		
		
	
		

		$old_principal_telecom_key=$parent_object->data[$parent.' Main '.$type.' Key'];
		if ($set_as_main and $old_principal_telecom_key!=$telecom_key) {

			$sql=sprintf("update `Telecom Bridge`  set `Is Main`='No' where `Subject Type`='$parent' and  `Subject Key`=%d ",
				$parent_object->id

			);
			mysql_query($sql);
			$sql=sprintf("update `Telecom Bridge`  set `Is Main`='Yes' where `Subject Type`='$parent' and  `Subject Key`=%d  and `Telecom Key`=%d",
				$parent_object->id
				,$telecom_key
			);
			mysql_query($sql);
			$sql=sprintf("update `$parent Dimension` set `$parent Main $type Key`=%d where `$parent Key`=%d"
				,$telecom_key
				,$parent_object->id
			);
			mysql_query($sql);
		}
	}





	function get_telecom_keys() {
		$sql=sprintf("select `Telecom Key` from `Telecom Bridge` where  `Subject Type`='Address'   and `Subject Key`=%d "

			,$this->id );

		$telecoms=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$telecoms[$row['Telecom Key']]= $row['Telecom Key'];
		}
		return $telecoms;

	}

	function get_number_of_associated_telecoms($type) {
		$sql=sprintf("select  count(*) as num  from `Telecom Bridge` TB left join `Telecom Dimension` T on (T.`Telecom Key`=TB.`Telecom Key`)  where  `Subject Type`='Address' and `Telecom Type`=%s and  `Subject Key`=%d "
			,prepare_mysql($type)
			,$this->id );
		//print $sql;
		$telecoms=0;
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$telecoms=$row['num'];
		}
		return $telecoms;

	}

	function get_telecom_type_keys($type) {
		$sql=sprintf("select TB.`Telecom Key` from `Telecom Bridge` TB left join `Telecom Dimension` T on (T.`Telecom Key`=TB.`Telecom Key`)  where  `Subject Type`='Address' and `Telecom Type`=%s and  `Subject Key`=%d "
			,prepare_mysql($type)
			,$this->id );

		$telecoms=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$telecoms[$row['Telecom Key']]= $row['Telecom Key'];
		}
		return $telecoms;

	}


	function get_formated_principal_telephone() {
		include_once 'class.Telecom.php';

		$sql=sprintf("select TB.`Telecom Key` from `Telecom Bridge` TB  left join `Telecom Dimension` T on (T.`Telecom Key`=TB.`Telecom Key`)  where  `Telecom Type`=%s and   `Subject Type`='Address' and `Subject Key`=%d and `Is Main`='Yes'"
			,prepare_mysql('Telephone')
			,$this->id );
		// print $sql;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$main_telecom_key=$row['Telecom Key'];
		} else {
			$main_telecom_key=0;
		}

		if ($main_telecom_key) {
			$telecom=new Telecom($main_telecom_key);
			return $telecom->display('html');
		} else {
			return '';
		}


	}

	function get_principal_telecom_key($type) {

		$sql=sprintf("select TB.`Telecom Key` from `Telecom Bridge` TB  left join `Telecom Dimension` T on (T.`Telecom Key`=TB.`Telecom Key`)  where  `Telecom Type`=%s and   `Subject Type`='Address' and `Subject Key`=%d and `Is Main`='Yes'"
			,prepare_mysql($type)
			,$this->id );
		//print $sql;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$main_telecom_key=$row['Telecom Key'];
		} else {
			$main_telecom_key=0;
		}

		return $main_telecom_key;
	}

	function create_telecom_bridge($telecom_key,$type,$swap_principal=true) {
		$sql=sprintf("insert into `Telecom Bridge` (`Subject Type`,`Subject Key`,`Telecom Key`) values ('Address',%d,%d)  ",
			$this->id,
			$telecom_key
		);
		mysql_query($sql);
		$this->updated=true;

		//     print 'x'..'x'.$this->get_principal_telecom_key($type).'x';

		if (!$this->get_principal_telecom_key($type) and $swap_principal) {
			$this->update_principal_telecom($telecom_key,$type);
		}

	}
	function update_principal_telephone($telecom_key) {
		$this->update_principal_telecom($telecom_key,'Telephone');

	}
	function update_principal_telecom($telecom_key,$type,$old_principal_telecom='') {

		if (preg_match('/^$(Telephone|FAX)/i',$type) ) {
			$type='Telephone';
		}
		$main_telecom_key=$this->get_principal_telecom_key($type);

		if ($main_telecom_key!=$telecom_key) {
			$telecom=new Telecom($telecom_key);
			$telecom->editor=$this->editor;
			$sql=sprintf("update `Telecom Bridge` B left join `Telecom Dimension` T on (T.`Telecom Key`=B.`Telecom Key`)   set `Is Main`='No' where `Subject Type`='Address'   and `Subject Key`=%d and `Telecom Type`='$type' "
				,$this->id

			);

			mysql_query($sql);
			$sql=sprintf("update `Telecom Bridge`  set `Is Main`='Yes' where `Subject Type`='Address'  and  `Subject Key`=%d  and `Telecom Key`=%d"
				,$this->id
				,$telecom->id
			);
			mysql_query($sql);


			$sql=sprintf("update `Address Dimension` set  `Address Main $type Key`=%d where `Address Key`=%d",$telecom->id,$this->id);
			//print "$sql\n";
			$this->data["Address Main $type Key"]=$telecom->id;
			mysql_query($sql);

			$this->update_parents_principal_telecom_keys($type);
			
			
			$telecom->update_parents($add_parent_history=true,$old_principal_telecom);
			

		}

	}

	function update_parents_principal_telecom_keys($type,$parents=false) {
		if (!is_array($parents))
			$parents=array('Contact','Company','Customer','Supplier');

		if (preg_match('/^$(Telephone|FAX)/i',$type) ) {
			$type='Telephone';
		}
		$telecom_key=$this->data["Address Main $type Key"];


		if (!$telecom_key)
			return;


		foreach ($parents as $parent) {
			$sql=sprintf("select `$parent Key` as `Parent Key`   from  `$parent Dimension` where `$parent Main Address Key`=%d group by `$parent Key`",$this->id);
			//print "$sql\n";
			$res=mysql_query($sql);
			while ($row=mysql_fetch_array($res)) {

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
				elseif ($parent=='Contact') {
					$parent_object=new Contact($row['Parent Key']);
					$parent_label=_('Contact');
				}

				$parent_telecoms=$parent_object->get_telecom_keys($type);

				if (!array_key_exists($telecom_key,$parent_telecoms)) {
					$sql=sprintf("insert into  `Telecom Bridge` (`Telecom Key`,`Subject Type`, `Subject Key`,`Is Main`) values (%d,'$parent',%d,'No')  "
						,$telecom_key
						,$parent_object->id
					);
					mysql_query($sql);
					// print "$sql\n";
				}

				$old_principal_telecom_key=$parent_object->data[$parent." Main $type Key"];
				//print " $old_principal_telecom_key -> $telecom_key \n";
				// if ($old_principal_telecom_key!=$telecom_key) {

				$sql=sprintf("update `Telecom Bridge` B left join `Telecom Dimension` T on (T.`Telecom Key`=B.`Telecom Key`) set `Is Main`='No'   where   `Subject Type`='$parent' and  `Subject Key`=%d and `Telecom Type`='$type' "
					,$parent_object->id

				);
				mysql_query($sql);
				//  print "$sql\n";
				$sql=sprintf("update `Telecom Bridge`  set `Is Main`='Yes' where  `Subject Type`='$parent' and  `Subject Key`=%d  and `Telecom Key`=%d"
					,$parent_object->id
					,$telecom_key
				);
				mysql_query($sql);
				//    print "$sql\n";






				//}

				$sql=sprintf("update `$parent Dimension` set `$parent Main $type Key`=%d where `$parent Key`=%d"
					,$telecom_key
					,$parent_object->id
				);
				//print "$sql\n";
				mysql_query($sql);

			}
		}
	}




	function update_principal_telecom_number($value,$type) {

		if ($type=='Telephone')
			$this->update_principal_telephone_number($value);
		else
			$this->update_principal_fax_number($value);

	}

	function update_principal_telephone_number($value) {

		$telephone_key=$this->get_principal_telecom_key('Telephone');

		if (!$telephone_key) {

			$this->error=true;
			$this->msg="No principal telephone\n";
			return 0;
		} else {

			$telephone=new Telecom($telephone_key);

			$telephone->update_number($value,$this->data['Address Country Code']);

			$this->updated=$telephone->updated;
			$this->new_value=$telephone->display('xhtml');
			$this->updated_data['telecom_key']=$telephone->id;
			return $telephone->id;
		}
	}

	function update_principal_fax_number($value) {
		$fax_key=$this->get_principal_telecom_key('FAX');
		if (!$fax_key) {
			$this->error=true;
			$this->msg="No principal fax\n";
		} else {

			$fax=new Telecom($fax_key);
			$fax->update_number($value,$this->data['Address Country Code']);
			$this->updated=$fax->updated;
			$this->new_value=$fax->display('xhtml');
			$this->updated_data['telecom_key']=$fax->id;
			return $fax->id;
		}
	}


	function update_telecom($telecom_key,$value) {
		$telecom=new Telecom($telecom_key);
		$telecom->update_number($value,$this->data['Address Country Code']);
		$this->updated=$telecom->updated;
	}

	function get_name() {
		return _('Address').' '.$this->id.', '.$this->data['Address Location'];
	}


	function get_fuzzines($data) {

		$total_empty=true;
		$street_empty=true;
		$country_empty=true;
		foreach ($data as $key=>$value) {
			if (preg_match('/Address Internal|Address Building|Address Street Name/',$key) and $value!='') {
				$street_empty=false;
				$total_empty=false;
			}

			if (preg_match('/Address Town/',$key )and $value!='') {

				$total_empty=false;
			}



			if ($data['Address Country Code']!='UNK') {
				$country_empty=false;
				$total_empty=false;
			}
		}


		if ($total_empty) {
			$type='All';
		}
		elseif ($country_empty) {
			$type='Country';
		}
		elseif ($street_empty) {
			$type='Steet';
		}
		else
			$type='';

		if ($type=='')
			$fuzzy='No';
		else
			$fuzzy='Yes';

		return array($fuzzy,$type);




	}


	function get_data_for_ship_to() {
		$lines=$this->display('3lines');
		$data['Ship To Line 1']=$lines[1];
		$data['Ship To Line 2']=$lines[2];
		$data['Ship To Line 3']=$lines[3];
		$data['Ship To Town']=$this->data['Address Town'];
		$line4='';
		if ($this->data['Address Country Second Division']!='')
			$line4=_trim($this->data['Address Country Second Division']);
		if ($line4!='' and $this->data['Address Country First Division']!='')
			$line4.=', '.$this->data['Address Country First Division'];
		$data['Ship To Line 4']=$line4;
		$data['Ship To Postal Code']=$this->data['Address Postal Code'];
		$data['Ship To Country Name']=$this->data['Address Country Name'];
		$data['Ship To Country Key']=$this->data['Address Country Key'];
		$data['Ship To Country Code']=$this->data['Address Country Code'];
		$data['Ship To Country 2 Alpha Code']=$this->data['Address Country 2 Alpha Code'];

		$data['Ship To XHTML Address']=$this->display('xhtml');
		return $data;
	}


	function has_parents() {
		$has_parents=false;
		$sql=sprintf("select count(*) as total from `Address Bridge`  where  `Address Key`=%d  ",$this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			if ($row['total']>0)
				$has_parents=true;
		}
		return $has_parents;
	}



	function delete() {



		$country_code=$this->data['Address Country Code'];



		$parents=array('Customer','Contact','Company','Supplier');
		foreach ($parents as $parent) {
			$sql=sprintf("select `$parent Key` as `Parent Key`   from  `$parent Dimension` where `$parent Main Address Key`=%d group by `$parent Key`",$this->id);

			$res=mysql_query($sql);
			while ($row=mysql_fetch_array($res)) {
				$this->remove_from_parent($parent,$row['Parent Key']);
			}


		}



		$sql=sprintf("select `Customer Key`  ,`Customer Main Address Key` from  `Customer Dimension` where `Customer Billing Address Key`=%d ",$this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$customer=new Customer($row['Customer Key']);
			$address_key=$row['Customer Main Address Key'];

			$customer->update_principal_billing_address($address_key);
		}


		$sql=sprintf("select `Customer Key`  ,`Customer Main Address Key` from  `Customer Dimension` where `Customer Main Delivery Address Key`=%d ",$this->id);
		//print "$sql\n";
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {
			$customer=new Customer($row['Customer Key']);
			$addresses=$customer->get_delivery_address_keys();
			unset($addresses[$row['Customer Main Address Key']]);
			unset($addresses[$this->id]);
			//print_r($addresses);
			if (count($addresses)==0) {

				$address_key=$row['Customer Main Address Key'];

			} else {
				$address_key=array_pop($addresses);
			}

			$customer->update_principal_delivery_address($address_key);
		}

		$address_telecom_keys=$this->get_telecom_keys();

		$sql=sprintf("delete from `Address Dimension` where `Address Key`=%d",$this->id);
		mysql_query($sql);

		$this->deleted=true;


		foreach ($address_telecom_keys as $address_telecom_key) {
			$telecom=new Telecom($address_telecom_key);
			if (!$telecom->has_parents()) {
				$telecom->delete();
			}

		}

		$sql=sprintf("delete from `Address Bridge`  where  `Address Key`=%d", $this->id);
		mysql_query($sql);



		/*
            $history_data['History Abstract']='Address Deleted';
            $history_data['History Details']=_('Address').' '.$this->display('plain')." "._('has been deleted');
            $history_data['Action']='deleted';
            $history_data['Direct Object']='Address';
            $history_data['Direct Object Key']=$this->id;
            $history_data['Indirect Object']='';
            $history_data['Indirect Object Key']='';
            $this->add_history($history_data);
            */
	}

	function remove_from_parent($parent,$parent_key) {



		$principal_Address_changed=false;

		if ($parent=='Contact') {
			$parent_object=new Contact($row['Parent Key']);
			$parent_label=_('Contact');
		}
		elseif ($parent=='Company') {
			$parent_object=new Company($row['Parent Key']);
			$parent_label=_('Company');
		}
		elseif ($parent=='Customer') {
			$parent_object=new Customer($parent_key);
			$parent_label=_('Customer');
		}
		elseif ($parent=='Supplier') {
			$parent_object=new Supplier($parent_key);
			$parent_label=_('Supplier');
		}
		elseif ($parent=='Staff') {
			$parent_object=new Staff($parent_key);
			$parent_label=_('Staff');
		}

		//Assign automatically other address

		$history_data['History Abstract']='Address Removed';
		$history_data['History Details']=_('Address').' '.$this->display('plain')." "._('has been deleted from')." ".$parent_object->get_name()." ".$parent_label;
		$history_data['Action']='disassociate';
		$history_data['Direct Object']=$parent;
		$history_data['Direct Object Key']=$parent_object->id;
		$history_data['Indirect Object']='Address';
		$history_data['Indirect Object Key']=$this->id;
		$this->add_history($history_data);





		$addresses=$parent_object->get_address_keys();

		if (count($addresses)==0) {
			$address=new Address('find create',array('Address Country Code'=>$country_code));
			$address_key=$address->id;

		} else {
			$address_key=array_pop($addresses);


		}





		//  $parent_object-> update_principal_address($address_key);









	}


	function get_parent_keys($type) {
		$keys=array();
		if (!preg_match('/^(Contact|Company|Supplier|User|Customer)$/',$type)) {
			return $keys;
		}
		$sql=sprintf("select `Subject Key` from `Address Bridge` where `Subject Type`=%s and `Address Key`=%d  "
			,prepare_mysql($type)
			,$this->id);
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$keys[$row['Subject Key']]= $row['Subject Key'];

		}
		return $keys;
	}




	function get_ship_to() {



		include_once 'class.Ship_To.php';


		$line=$this->display('3lines');
		$shipping_addresses['Ship To Line 1']=$line[1];
		$shipping_addresses['Ship To Line 2']=$line[2];
		$shipping_addresses['Ship To Line 3']=$line[3];
		$shipping_addresses['Ship To Town']=$this->data['Address Town'];
		$shipping_addresses['Ship To Postal Code']=$this->data['Address Postal Code'];
		$shipping_addresses['Ship To Country Name']=$this->data['Address Country Name'];
		$shipping_addresses['Ship To Country Key']=$this->data['Address Country Key'];
		$shipping_addresses['Ship To Country Code']=$this->data['Address Country Code'];
		$shipping_addresses['Ship To Country 2 Alpha Code']=$this->data['Address Country 2 Alpha Code'];
		$shipping_addresses['Ship To XHTML Address']=$this->display('xhtml');

		$shipping_addresses['Ship To Country First Division']=$this->data['Address Country First Division'];
		$shipping_addresses['Ship To Country Second Division']=$this->data['Address Country Second Division'];

		//  print_r($shipping_addresses);

		$ship_to= new Ship_To('find create',$shipping_addresses);



		return $ship_to->id;


	}

}






?>
