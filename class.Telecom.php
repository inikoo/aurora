<?php
/*
 File: Telecom.php

 This file contains the Telecom Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';
include_once 'class.Country.php';

class Telecom extends DB_Table {
	var $deleted=false;

	/*
      Constructor: Telecom

      Initializes the class, Search/Load or Create for the Telephone/Fax data set

    */
	function Telecom($arg1=false,$arg2=false) {

		$this->table_name='Telecom';
		$this->ignore_fields=array('Telecom Key');


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


	function get_data($tipo,$id) {

		if ($tipo=='id') {

			if ($id==0) {
				$this->msg="error telecom key can not be zero T:$tipo ID:$id\n";
				return;
			}
			$sql=sprintf("select * from `Telecom Dimension` where  `Telecom Key`=%d",$id);
			$result=mysql_query($sql);

			if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$this->id=$this->data['Telecom Key'];
			}

		}
	}


	function get_plain_number() {
		return $this->plain_number($this->data);

	}



	function display($tipo='') {

		if (!$this->id) {
			print("telecom with out nothing, fatal error, fix it\n");
		}

		switch ($tipo) {
		case('plain'):
			return   $this->plain_number($this->data);
		case('formated'):
		case('xhtml'):
		case('number'):
		default:
			return $this->get_formated_number();
		}
	}




	function get($key='') {


		if (array_key_exists($key,$this->data))
			return $this->data[$key];

		switch ($key) {
		case('spaced_number'):
			return _trim(strrev(chunk_split(strrev($this->data['Telecom Number']),4," ")));
			break;


		}


		$_key=ucwords($key);
		if (array_key_exists($_key,$this->data))

			return $this->data[$_key];

		print "Error $key not found in get from telecom\n";
		exit;

		return false;
	}


	function find_fast($data) {

		$sql=sprintf("select  `Telecom Key` from `Telecom Dimension`  where   `Telecom Plain Number`=%s   "
			,prepare_mysql($data['Telecom Plain Number'],false)
		);
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->found=true;
			$this->get_data('id',$row['Telecom Key']);
		}

	}

	function find_complete($data,$subject_data) {

		$mode=$subject_data['mode'];
		$in_contact=$subject_data['in_contact'];

		$intl_code_max_score=10;
		$ext_code_max_score=10;
		$ext_max_score=10;
		$area_code_max_score=10;
		$tel_max_score=80;
		//  $exact_match_bonus=10;
		$this->found=false;

		$this->found_number=0;
		$this->found_ext=0;
		$this->found_intl_code=0;





		if ($data['Telecom Plain Number']!='') {

			$len_tel=strlen($data['Telecom Plain Number']);




			$sql=sprintf("select  `Telecom Area Code`,`Telecom Extension`,`Telecom Country Telephone Code`,`Telecom Extension`,T.`Telecom Key`,`Subject Key` from `Telecom Dimension` T left join `Telecom Bridge` TB  on (TB.`Telecom Key`=T.`Telecom Key`)  where  `Subject Type`='Contact'  and  `Telecom Plain Number`=%s  limit 100 "
				,prepare_mysql($data['Telecom Plain Number'],false)
			);

			//print $sql;
			$result=mysql_query($sql);
			while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$contact_key=$row['Subject Key'];

				$score=$tel_max_score;
				$_score=$score;
				$this->found =1;

				if (isset($this->candidate[$contact_key]))
					$this->candidate[$contact_key]+=$score;
				else
					$this->candidate[$contact_key]=$score;


				$this->found=true;
				$this->get_data('id',$row['Telecom Key']);

				if ($mode=='Contact in' or $mode=='Company in') {
					if (in_array($row['Subject Key'],$in_contact)) {

						$this->found_in=true;
						$this->found_out=false;
					} else {

						$this->found_in=false;
						$this->found_out=true;
					}





				}






			}


		}

		if (!$this->found and $data['Telecom Area Code'].$data['Telecom Number']!='') {

			$len_tel=strlen($data['Telecom Area Code'].$data['Telecom Number']);




			$sql=sprintf("select  `Telecom Area Code`,`Telecom Extension`,`Telecom Country Telephone Code`,`Telecom Extension`,T.`Telecom Key`,`Subject Key` from `Telecom Dimension` T left join `Telecom Bridge` TB  on (TB.`Telecom Key`=T.`Telecom Key`)  where  `Subject Type`='Contact'  and  `Telecom Area Code`=%s  and `Telecom Number`=%s  limit 100 "
				,prepare_mysql($data['Telecom Area Code'],false)
				,prepare_mysql($data['Telecom Number'],false)
			);

			//print $sql;
			$result=mysql_query($sql);
			while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$contact_key=$row['Subject Key'];

				$score=$tel_max_score;
				$_score=$score;
				//print "Dat: $len_tel ".$row['dist1']." $score\n";

				//                $score+=$exact_match_bonus;
				$this->found_number=1;


				//   print "1******************* $score\n";
				if ($row['Telecom Country Telephone Code']==$data['Telecom Country Telephone Code']) {
					if ($data['Telecom Country Telephone Code']!='') {
						$this->found_intl_code=1;
						$score+= $intl_code_max_score;
					}
				} else {
					if ($data['Telecom Country Telephone Code']!='' and $row['Telecom Country Telephone Code']!='')
						$this->found_intl_code=-2;
				}
				//    print "2******************* $score\n";
				if ($row['Telecom Extension']==$data['Telecom Extension']) {
					if ($data['Telecom Extension']!='') {
						$this->found_ext=2;
						$score+= $ext_max_score;
					}
				} else {
					if ($data['Telecom Extension']!='' and $row['Telecom Extension']!='')
						$this->found_ext=-2;
				}



				//   print "3******************* $score\n";
				if (isset($this->candidate[$contact_key]))
					$this->candidate[$contact_key]+=$score;
				else
					$this->candidate[$contact_key]=$score;





				if ($this->found_number and ($this->found_ext>=0 and $this->found_intl_code>=0)) {
					$this->found=true;
					$this->get_data('id',$row['Telecom Key']);

					//print "----> ".$row['Telecom Key']."\n";
					if ($mode=='Contact in' or $mode=='Company in') {
						if (in_array($row['Subject Key'],$in_contact)) {

							$this->found_in=true;
							$this->found_out=false;
						} else {

							$this->found_in=false;
							$this->found_out=true;
						}
					}

				}


			}






		}


	}

	function find_fuzzy() {

	}


	function prepare_data($raw_data,$options) {

		if (!$raw_data) {
			$this->error=true;
			$this->msg=_('Error no telecom data');
			if (preg_match('/exit on errors/',$options))
				exit($this->msg);
			return false;
		}




		//print "OPTIONS $options\n";

		if (preg_match('/country code [a-z]{3}/i',$options,$match)) {
			$country_code=preg_replace('/country code /','',$match[0]);
		} else
			$country_code='UNK';



		$raw_number=false;
		if (isset($raw_data['Telecom Raw Number'])) {
			$raw_number=$raw_data['Telecom Raw Number'];
		}
		if (is_string($raw_data)) {
			$raw_number=$raw_data;
			$raw_data=array();
		}



		if ($raw_number!='') {


			if (strlen($raw_number)<3) {

				$this->error=true;
				$this->msg=_('Error, invalid telecom data');
				if (preg_match('/exit on errors/',$options))
					exit($this->msg);
				return false;

			}

			$tmp=$this->parse_inputed_number($raw_number,$country_code);
			//print_r($tmp);
			foreach ($tmp as $key=>$value) {

				$raw_data[$key]=$value;
			}

		} else {
			$this->error=true;
			$this->msg=_('Error, no number data');
			if (preg_match('/exit on errors/',$options))
				exit($this->msg);
			return false;

		}

		if ($raw_data['Telecom Number']=='') {
			$this->error=true;
			$this->msg=_('Error no telecom number data');
			if (preg_match('/exit on errors/',$options))
				exit($this->msg);
			return false;
		}



		$data=$this->base_data();
		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key,$data))
				$data[$key]=$value;
		}

		$data=$this->clean_data($data);



		if ($data['Telecom Number']=='') {
			$this->msg=_('Wrong telephone number');
			return false;
		}


		$data['Telecom Plain Number']=Telecom::plain_number($data);

		return $data;
	}

	/*
          Function: find
          Given a set of telephone number components try to find it on the database updating properties, if not found creates a new record

           Parmaters:
           $raw_data - associative array with the telephone number data (DB fields as keys)
           $options - string

           auto - the method will update/create the telephone number with out asking for instructions
           create|update - methos will create or update the telephone number with the data provided
        */
	function find($raw_data,$options) {

		$_raw_data=$raw_data;
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



		$this->found=false;
		$this->found_in=false;
		$this->found_out=false;
		$in_contact=false;
		$in_contacts=array();
		$mode='Contact';
		$parent='Contact';
		$subject_key=0;
		$subject_type='';


		$create=false;
		if (preg_match('/create|update/i',$options)) {
			$create=true;
		}
		$auto=false;
		if (preg_match('/auto/i',$options)) {
			$auto=true;
		}


		$data=$this->prepare_data($raw_data,$options);

		if (!$data)
			return;




		if ($data['Telecom Number']=='') {
			$this->msg=_('Wrong telephone number');
			return false;
		}


		$data['Telecom Plain Number']=Telecom::plain_number($data);

		$subject=false;
		$subject_key=0;
		$subject_type='Contact';

		if (preg_match('/in contact \d+/',$options,$match)) {
			$subject_key=preg_replace('/[^\d]/','',$match[0]);
			$subject_type='Contact';

			$mode='Contact in';
			$in_contact=array($subject_key);


		}
		if (preg_match('/in company \d+/',$options,$match)) {
			$subject_key=preg_replace('/[^\d]/','',$match[0]);
			$subject_type='Company';
			$company=new Company($subject_key);
			$in_contact=$company->get_contact_keys();
			$mode='Company in';

		}
		elseif (preg_match('/company/',$options,$match)) {
			$subject_type='Company';
			$mode='Company';
		}

		if ($mode=='Contact')
			$options.=' anonymous';


		// print_r($data);

		$subject_data=array(
			'subject_type'=>$subject_type,
			'subject_key'=>$subject_key,
			'parent'=>$parent,

			'mode'=>$mode,
			'in_contact'=>$in_contact,

		);

		switch ($find_type) {
		case 'fast':

			$this->find_fast($data);
			break;
		case 'complete':

			$this->find_complete($data,$subject_data);
			break;
		case 'fuzzy':
			$this->find_fuzzy();
			break;
		}

		
		if ($create) {
			if ($this->found) {

				//$this->update($data,$options);
			} else {
				// not found
				/*
                    if ($auto) {
                        usort($this->candidate);
                        foreach($this->candidate as $key =>$val) {
                            if ($val>=90) {
                                $this->found=true;
                                if (in_array($key,$in_contact))
                                    $this->found_in=true;
                                else
                                    $this->found_out=true;

                                $this->get_data('id',$key);
                                $this->update($data,$options);
                                return;
                            }
                        }

                    }
                    */
				$this->create($_raw_data,$options);

			}

		}

		//  print "tel:";
		//print_r($this->candidate);

	}



	/*
        Function: create
        Insert new number to the database


         */
	protected function create($raw_data,$options='') {

		$data=$this->prepare_data($raw_data,$options);

		if (!$data) {
			$this->new=false;
			$this->error=true;
			$this->msg.=" Error no telecom data";
			if (preg_match('/exit on errors/',$options))
				exit($this->msg);
			return false;
		}




		$this->data=$this->base_data();
		foreach ($data as $key=>$value) {
			if (array_key_exists($key,$this->data))
				$this->data[$key]=$value;
		}

		if ($this->data['Telecom Number']=='') {
			$this->new=false;
			$this->error=true;
			$this->msg=_('Wrong telephone number');
		}

		if ($this->data['Telecom Technology Type']=='') {
			$this->data['Telecom Technology Type']='Unknown';
		}

		$sql=sprintf("insert into `Telecom Dimension` (`Telecom Type`,`Telecom Technology Type`,`Telecom Country Telephone Code`,`Telecom National Access Code`,`Telecom Area Code`,`Telecom Number`,`Telecom Extension`,`Telecom Plain Number`) values (%s,%s,%s,%s,%s,%s,%s,%s)",
			prepare_mysql($this->data['Telecom Type']),
			prepare_mysql($this->data['Telecom Technology Type']),
			prepare_mysql($this->data['Telecom Country Telephone Code']),
			prepare_mysql($this->data['Telecom National Access Code'],false),
			prepare_mysql($this->data['Telecom Area Code'],false),
			prepare_mysql($this->data['Telecom Number']),
			prepare_mysql($this->data['Telecom Extension'],false),
			prepare_mysql($this->data['Telecom Plain Number'])
		);

		//print "$sql\n";

		if (mysql_query($sql)) {
			$this->id = mysql_insert_id();
			$this->get_data('id',$this->id);
			$this->new=true;

			// Some times some post production should be made.
			$this->postproduction();


			return true;
		} else {
			$this->error=true;

			$this->msg="Error can not create telecom\n";
			$this->new=false;
		}

	}

	/*Function: clean_data
          Parse the number in its componets

          Parameter:
          $raw_data array with telecom fields

          Returns:
          $data  array with cleaned telecom field

         */
	function clean_data($raw_data) {

		$data=Telecom::base_data();
		foreach ($raw_data as $key=>$val) {
			if (array_key_exists($key,$data)) {

				$data[$key]=$val;
			}
		}

		return $data;

	}



	function parse_inputed_number($number,$country_code='UNK') {
		$data=array(
			'Telecom Technology Type'=>'Unknown',
			'Telecom Country Telephone Code'=>'',
			'Telecom National Access Code'=>'',
			'Telecom Area Code'=>'',
			'Telecom Number'=>'',
			'Telecom Extension'=>'',
			'National Only Telecom'=>'No',
			'Telecom Plain Number'=>''
		);
		$number=_trim($number);


		//  print $number;


		if (preg_match('/^\+\d{1,3}\s{1,}(\((0|1)\)\s*)?(?:[0-9] ?){3,13}[0-9](\s*(ext|x|e)\s*\d+)?$/',$number)) {
			//print"xxxxxxxx";

			if (preg_match('/\s*(ext|x|e)\s*\d+$/i',$number,$match)) {
				$extension_length=strlen($match[0]);
				$data['Telecom Extension']=preg_replace('/[^\d]/','',$match[0]);
				$number=_trim(substr($number,0,strlen($number)-$extension_length));
			}




			if (preg_match('/^\+\d{1,3}\s/',$number,$match)) {
				$country_code_section_length=strlen($match[0]);
				$data['Telecom Country Telephone Code']=preg_replace('/[^\d]/','',$match[0]);
				$number=substr($number,$country_code_section_length);
			}
			if (preg_match('/^\s*\((0|1)\)\s*/',$number,$match)) {
				$national_access_code_section_length=strlen($match[0]);
				$data['Telecom National Access Code']=preg_replace('/[^\d]/','',$match[0]);
				$number=substr($number,$national_access_code_section_length);
			}
			$number=_trim($number);
			if (preg_match('/^\d+\s/',$number,$match)) {
				$area_code_section_length=strlen($match[0]);
				$data['Telecom Area Code']=preg_replace('/[^\d]/','',$match[0]);
				$number=substr($number,$area_code_section_length);
			}

			$data['Telecom Number']=preg_replace('/[^\d]/','',$number);

		}
		elseif (preg_match('/^(\((0|1)\)\s*)?(?:[0-9] ?){6,14}[0-9](\s*(ext|x|e)\s*\d+)?$/',$number)) {
			if (preg_match('/\s*(ext|x|e)\s*\d+$/i',$number,$match)) {
				$extension_length=strlen($match[0]);
				$data['Telecom Extension']=preg_replace('/[^\d]/','',$match[0]);
				$number=_trim(substr($number,0,strlen($number)-$extension_length));
			}
			if (preg_match('/^\s*\((0|1)\)\s*/',$number,$match)) {
				$national_access_code_section_length=strlen($match[0]);
				$data['Telecom National Access Code']=preg_replace('/[^\d]/','',$match[0]);
				$number=substr($number,$national_access_code_section_length);
			}
			$number=_trim($number);
			if (preg_match('/^\d+\s/',$number,$match)) {
				$area_code_section_length=strlen($match[0]);
				$data['Telecom Area Code']=preg_replace('/[^\d]/','',$match[0]);
				$number=substr($number,$area_code_section_length);
			}

			$data['Telecom Number']=preg_replace('/[^\d]/','',$number);


		}
		else {

			$number=preg_replace('/[^\d]/','',$number);
			$data=$this->parse_plain_number($number,$country_code);

		}
		// print_r( $data);
		$data['Telecom Plain Number']=Telecom::plain_number($data);

		return $data;
	}


	/*Function: parse_plain_number
          Parse the number in its componets

          Parameters:
          $data -  string with the number


         */
	public static  function parse_plain_number($number,$country_code='UNK') {
		//    print "parsing number $number $country_code\n";

		$data=array(
			'Telecom Technology Type'=>'Unknown',
			'Telecom Country Telephone Code'=>'',
			'Telecom National Access Code'=>'',
			'Telecom Area Code'=>'',
			'Telecom Number'=>'',
			'Telecom Extension'=>'',
			'National Only Telecom'=>'No',
			'Telecom Plain Number'=>''
		);

		$number=_trim($number);
		if (preg_match('/(e|x)/i',$number)) {
			$tmp=preg_split('/\s*(ext|e|x)\s*/i',$number);
			if (count($tmp)==2) {
				$number=$tmp[0];
				$data['Telecom Extension']=$tmp[1];
			}
			elseif (count($tmp)>2) {
				//$this->error=true;
				//$this->msg=_('Error in number');

			}
		}
		// parse common formats
		// +44 1142729165
		if (preg_match('/^\+\d+ \d/',$number)) {
			preg_match('/^\+\d+\s*/',$number,$match);

			$number=preg_replace('/^\+\d+\s*/','',$number);
			$data['Telecom Country Telephone Code']=preg_replace('/[^\d]/','',$match[0]);



		}
		// +44 (0) 114 2729165
		elseif (preg_match('/^\+\d+ \(\d+\) \d{1,3} \d/',$number)) {
			preg_match('/^\+\d+\s*/',$number,$match);

			$number=preg_replace('/^\+\d+\s*/','',$number);
			$data['Telecom Country Telephone Code']=preg_replace('/[^\d]/','',$match[0]);

			preg_match('/^\(\d+\) /',$number,$match);
			$number=preg_replace('/^\(\d+\) /','',$number);
			$data['Telecom National Access Code']=preg_replace('/[^\d]/','',$match[0]);
			preg_match('/^\d{1,3}\s*/',$number,$match);
			$number=preg_replace('/^\d{1,3}\s*/','',$number);
			$data['Telecom Area Code']=preg_replace('/[^\d]/','',$match[0]);
			$data['Telecom Number']=preg_replace('/[^\d]/','',$number);

		}
		//  +44 (0) 1142729165
		else if (preg_match('/^\+\d+ \(\d+\) \d/',$number)) {
				preg_match('/^\+\d+\s*/',$number,$match);

				$number=preg_replace('/^\+\d+\s*/','',$number);
				$data['Telecom Country Telephone Code']=preg_replace('/[^\d]/','',$match[0]);

				preg_match('/^\(\d+\) /',$number,$match);
				$number=preg_replace('/^\(\d+\) /','',$number);
				$data['Telecom National Access Code']=preg_replace('/[^\d]/','',$match[0]);


			}
		// +44 114 2729165
		elseif (preg_match('/^\+\d+ \d{1,3} \d/',$number)) {
			preg_match('/^\+\d+\s*/',$number,$match);
			$number=preg_replace('/^\+\d+\s*/','',$number);
			$data['Telecom Country Telephone Code']=preg_replace('/[^\d]/','',$match[0]);
			preg_match('/^\d{1,3}\s*/',$number,$match);
			$number=preg_replace('/^\d{1,3}\s*/','',$number);
			$data['Telecom Area Code']=preg_replace('/[^\d]/','',$match[0]);
			$data['Telecom Number']=preg_replace('/[^\d]/','',$number);

		}
		// +44 1142729165
		elseif (preg_match('/^\+\d+ \d/',$number)) {
			preg_match('/^\+\d+\s*/',$number,$match);
			$number=preg_replace('/^\+\d+\s*/','',$match[0]);
			$data['Telecom Country Telephone Code']=preg_replace('/[^\d]/','',$match[0]);


		}
		$number=preg_replace('/[^\d]/','',$number);
		$number=_trim($number);

		$data['Telecom Number']=$number;


		if ($country_code=='UNK' and isset($data['Telecom Country Telephone Code'])) {
			$country_code=Telecom::get_country_code($data['Telecom Country Telephone Code']);

		}

		/*   print "$country_code\n"; */
		/*    print_r($data); */

		/*  $raw_tel=$data['Telecom Original Number']; */
		/*    // print "org1 $data ".$raw_tel."\n"; */
		/*    $raw_tel=preg_replace('/\(/',' (',$raw_tel); */
		/*    $raw_tel=preg_replace('/\)/',') ',$raw_tel); */
		/*    $raw_tel=preg_replace('/-/','',$raw_tel); */
		/*    $raw_tel=_trim($raw_tel); */
		/*  // print "org2 $data ".$raw_tel."\n"; */

		/*    if(preg_match('/^\+\d{1,4}\s/',$raw_tel,$match)){ */
		/*      $len=strlen($match[0]); */
		/*      $data['Telecom Country Telephone Code']=preg_replace('/[^0-9]/','',$match[0]); */
		/*      $raw_tel=substr($raw_tel,$len); */
		/*    } */

		/*    if(preg_match('/^\s*\(\d+\)\s*\/',$raw_tel,$match)){ */
		/*      $len=strlen($match[0]); */
		/*      $data['Telecom National Access Code']=preg_replace('/[^0-9]/','',$match[0]); */
		/*      $raw_tel=substr($raw_tel,$len); */
		/*    } */



		/*    $data['Telecom Country Telephone Code']=preg_replace('/[^0-9]/','',$data['Telecom Original Country Telephone Code']); */
		/*    $data['Telecom Area Code']=preg_replace('/[^0-9]/','',$data['Telecom Original Area Code']); */

		/*    // fisrt try to see if it has an extension; */


		/*    $tel_ext=preg_split('/ext/i',$raw_tel); */

		/*   if(count($tel_ext)==2){ */
		/*     $data['Telecom Extension']=preg_replace('/[^0-9]/','',$tel_ext[1]); */
		/*     $data['Telecom Number']=preg_replace('/[^0-9]/','',$tel_ext[0]); */

		/*   }else{ */

		/*     $data['Telecom Extension']=preg_replace('/[^0-9]/','',$data['Telecom Original Extension']); */
		/*     $data['Telecom Number']=preg_replace('/[^0-9]/','',$raw_tel); */

		/*   } */

		/*   if($data['Telecom Country Telephone Code']!=''){ */
		/*     $regex_icode="^0{0,2}".$data['Telecom Country Telephone Code']; */
		/*     $data['Telecom Number']=preg_replace('/^0{0,2}'.$data['Telecom Country Telephone Code'].'/i','',$data['Telecom Number']); */
		/*   } */

		/*   // print_r($data); */
		/*   // country expcific */
		/*   //  $country_id=$this->date['Telecom Country Code']; */



		/*   //if($country_id==$this->unknown_country_id or $country_id=='') */
		/*   //  $country_id=$this->get_country_id(); */
		/*   //print $country_id;exit; */
		/*   //print */

		/*   //$data['Telecom Country Code']=$country_id; */

		/*   $data['is_mobile']=''; */

		//  print "parsing number $number $country_code\n";

		switch ($country_code) {

		case('GBR')://UK
			// print "---------------uk\n";
			//    print_r($data);
			$data['Telecom Number']=preg_replace('/^0/','',$data['Telecom Number']);

			if (preg_match('/^8(00\d{6,7}|08\d7|20\d{3}|45\d{3})/',$data['Telecom Number'],$match)) {
				$data['National Only Telecom']='Yes';
				$data['Telecom Country Telephone Code']='';

				preg_match('/^\d{3}/',$data['Telecom Number'],$match);
				$data['Telecom Area Code']=$match[0];
				$data['Telecom Number']=preg_replace('/^'.$data['Telecom Area Code'].'/','',$data['Telecom Number']);
				$data['Telecom Technology Type']='Non-geographic';
			} else {



				$data['Telecom Country Telephone Code']='44';
				$data['Telecom Number']=preg_replace('/^44/','',$data['Telecom Number']);


				$data['Telecom National Access Code']='0';
				if ($data['Telecom Area Code']=='') {
					$data['Telecom Number']=preg_replace('/^0/','',$data['Telecom Number']);
					$area_code=Telecom::find_area_code($data['Telecom Number'],'GBR');
					if ($area_code!='') {
						$data['Telecom Area Code']=$area_code;
						$data['Telecom Number']=preg_replace("/^".$data['Telecom Area Code']."/",'',$data['Telecom Number']);
					}
				}


				if (preg_match('/^7/',$data['Telecom Area Code'].$data['Telecom Number']))
					$data['Telecom Technology Type']='Mobile';
				else
					$data['Telecom Technology Type']='Landline';


			}

			break;
		case('IRL')://Ireland
			if (preg_match('/^0?8(2|3|5|6|7|8|9)/',$data['Telecom Number']))
				$data['is_mobile']=1;
			else
				$data['is_mobile']=0;
			$data['Telecom Country Telephone Code']='353';
			$data['Telecom Number']=preg_replace('/^353/','',$data['Telecom Number']);

			break;
			/*   case('ESP')://Spain */
			/*   case('FRA')://France */
			/*     if(preg_match('/^0?6/',$data['Telecom Number'])) */
			/*     $data['is_mobile']=1; */
			/*     else */
			/*       $data['is_mobile']=0; */
			/*     break; */
		}

		/*   if($data['is_mobile']==1) */
		/*     $data['Telecom Type']='Mobile'; */
		/*   else if($data['Telecom Original Type']=='Mobile' and $data['is_mobile']==0) */
		/*     $data['Telecom Type']='Unknown'; */
		/*   else  */
		/*     $data['Telecom Type']=$data['Telecom Original Type']; */



		$data['Telecom Plain Number']=Telecom::plain_number($data);

		// print_r($data);
		return $data;

		// print_r($this->data);
	}
	/*Function: plain_number
          Returns the telephone number with out format or international codes
         */
	public static function plain_number($data) {
		$number=preg_replace('/[^\d]/','',$data['Telecom Country Telephone Code'].$data['Telecom National Access Code'].$data['Telecom Area Code'].$data['Telecom Number']);
		$ext=preg_replace('/[^\d]/','',$data['Telecom Extension']);
		if ($ext!='')
			$number.='e'.$ext;
		return $number;
	}


	function is_mobile() {
		if ($this->data['Telecom Technology Type']=='Mobile')
			return true;
		else
			return false;

	}

	/*Function: formated_number
          Returns the formated  telephone number
         */
	function get_formated_number() {

		//  print "****";
		//print_r($data);



		$the_number=$this->data['Telecom Number'];
		/*

                switch (strlen($data['Telecom Number'])) {
                case 4:
                case 5:
                case 6:
               $the_number=$data['Telecom Number'];
                   // $the_number=preg_replace('/^\d{2}/','$0 ',$data['Telecom Number']);
                   // $the_number=preg_replace('/^\d{2} \d{2}/','$0 ',$the_number);

                    break;
                case 8:
                    $the_number=preg_replace('/^\d{2}/','$0 ',$data['Telecom Number']);
                    $the_number=preg_replace('/^\d{2} \d{2}/','$0 ',$the_number);

                    break;
                case 9:
                    $the_number=preg_replace('/^\d{2}/','$0 ',$data['Telecom Number']);
                    $the_number=preg_replace('/^\d{2} \d{3}/','$0 ',$the_number);
                    break;
                default:
                    $the_number=strrev(chunk_split(strrev($data['Telecom Number']),4," "));
                }
                $the_number=_trim($the_number)."";
            */
		if ($this->data['Telecom National Access Code']!='')
			$nac=sprintf("(%d)",$this->data['Telecom National Access Code']);
		else
			$nac='';

		// print_r($this->data);
		$tmp=($this->data['Telecom Country Telephone Code']!=''?'+'.$this->data['Telecom Country Telephone Code'].' ':'').$nac.($this->data['Telecom Area Code']!=''?$this->data['Telecom Area Code'].' ':'').$the_number.($this->data['Telecom Extension']!=''?' '._('ext').' '.$this->data['Telecom Extension']:'');

		//print "*** $tmp\n";

		return $tmp;

	}

	/*Function: get_country_id
          Returns the country key of this telephone
         */

	private function get_country_id() {
		if ($this->data['Telecom Country Telephone Code']) {
			$sql="select * from kbase.`Country Dimension` where `Country Telephone Code`=".prepare_mysql($this->data['Telecom Country Telephone Code']);
			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				return $row['Country Key'];
			}
		}
		return 244;
	}
	/*Function: get_country_code
          Returns the country code of this telephone

          Parameter:
          $tel_code - Intertnational telephone code

          Return:
          3 letter country code

         */

	public static  function get_country_code($tel_code='') {
		if ($tel_code) {
			$sql="select * from kbase.`Country Dimension` where `Country Telephone Code`=".prepare_mysql($tel_code);
			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				return $row['Country Code'];
			}

		}
		return 'UNK';
	}
	/*
          Function: find_area_code
          Find for the telephone access code in a number
         */

	static function find_area_code($number,$country_code='UNK') {
		// print "$number,$country_code\n  ";

		if (strlen($number>5)) {
			for ($i=5; $i>1; $i--) {
				$proposed_code=substr($number, 0,$i);

				$sql=sprintf("select `Telephone Local Code Key` from kbase.`Telephone Local Code` where LENGTH(`Telephone Local Code`)=%d and `Telephone Local Code`=%s and `Telephone Local Code Country Code`=%s "
					,$i,prepare_mysql($proposed_code),prepare_mysql($country_code));
				// print "$sql\n";
				$result=mysql_query($sql);
				$num_results=mysql_num_rows($result);
				if ($num_results>0)
					return $proposed_code;
			}
		}
		return '';
	}
	/*
          Function:postproduction
         */

	private function postproduction() {
		$country_code=$this->get_country_code($this->data['Telecom Country Telephone Code']);
		switch ($country_code) {

		case('GBR')://UK

			// By defaul when creating the tlecom Telecom Area Code is set to null if '' fix it for mobile
			if ($this->data['Telecom Technology Type']=='Mobile' and $this->data['Telecom Area Code']=='') {
				$sql=sprintf("update `Telecom Dimension` set `Telecom Area Code`='' where `Telecom Key`=%d",$this->id);
				mysql_query($sql);
			}
			break;
		}

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

		$sql=sprintf("select * from `Telecom Bridge` where `Telecom Key`=%d %s  %s "
			,$this->id
			,$where_scope
			,$where_scope_key
		);
		$res=mysql_query($sql);


		$this->data['Telecom Type']=array();
		$this->associated_with_scope=false;
		while ($row=mysql_fetch_array($res)) {
			$this->associated_with_scope=true;
			//$this->data['Telecom Type'][$this->data['Telecom Type']]=$this->data['Telecom Type'];
			//$this->data['Telecom Is Main'][$this->data['Telecom Type']]=$row['Is Main'];
			//$this->data['Telecom Is Active'][$this->data['Telecom Type']]=$row['Is Active'];
		}


	}


	/*
          function: is_associated
         */

	function is_associated($scope,$scope_key,$args='only valid') {
		$extra_args='';
		if (preg_match('/only active|active only/i',$args))
			$extra_args=" and `Is Active`='Yes'";
		if (preg_match('/only main|main only/i',$args))
			$extra_args=" and `Is Main`='Yes'";
		if (preg_match('/only not? active/i',$args))
			$extra_args=" and `Is Active`='No'";
		if (preg_match('/only not? main/i',$args))
			$extra_args=" and `Is Main`='No'";

		$sql=sprintf("select `Telecom Key` from `Telecom Bridge`  where `Subject Type`=%s and `Subject Key`=%d  %s  "
			,prepare_mysql($scope)
			,$scope_key
			,$extra_args
		);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			return true;
		}
		return false;

	}



	public function update($data,$options='') {

		if ($data['Telecom Number']=='') {
			$this->error=true;
			$this->msg=_('Wrong telephone number');
			return false;
		}

		$old_plain=$this->display('plain');
		$old_xhtml=$this->display('xhtml');
		if (isset($data['editor'])) {
			foreach ($data['editor'] as $key=>$value) {
				if (array_key_exists($key,$this->editor))
					$this->editor[$key]=$value;
			}
		}

		$base_data=$this->base_data();
		foreach ($data as $key=>$value) {

			//print "** $key,$value <- ".$this->data[$key]."  \n";
			if ( array_key_exists($key,$this->data) and strcmp($value,$this->data[$key]) and $key!='Telecom Plain Number') {
				//print "**to change  $key,$value <- ".$this->data[$key]."  \n";
				$this->update_field_switcher($key,strval($value),$options);
			}

		}

		if (!$this->updated)
			$this->msg.=' '._('Nothing to be updated')."\n";
		else {
			//print_r($this->data);

			$new_plain=$this->display('plain');
			$new_xhtml=$this->display('xhtml');
			// print "old:  $old_xhtml -> $new_xhtml\n";


			if ($old_xhtml!=$new_xhtml) {
				$this->update_field_switcher('Telecom Plain Number',$new_plain,$options);
				$this->update_parents();
			}

		}


	}


	function update_number($value,$country_code='UNK') {

		//print "$value,$country_code";
		$_data=preg_replace('/[^\d]/','',$value);

		if (strlen($_data)<3) {

			$this->error=true;
			$this->msg=_('Error, invalid telecom data');

			return false;

		}

		$data=$this->parse_inputed_number($value,$country_code);

		// exit;
		$this->update($data);
	}

	function update_field_switcher($field,$value,$options='') {

		//print "XXX $field,$value\n";
		// sass();
		if ($field=='Telecom Plain Number')
			$options.=' no history';
		$this->update_field($field,$value,$options);



	}

	function update_parents($add_parent_history=true) {

		//print $this->id;




		$type=$this->data['Telecom Type'];
		if ($type=='Fax')
			$type='FAX';
		if ($type=="Mobile" )
			$parents=array('Contact','Customer');
		else
			$parents=array('Address','Contact','Company','Customer','Supplier');
		foreach ($parents as $parent) {



			$sql=sprintf("select `$parent Key` as `Parent Key`   from  `$parent Dimension` where `$parent Main $type Key`=%d group by `$parent Key`",$this->id);
			//       print "$sql\n";
			$res=mysql_query($sql);
			while ($row=mysql_fetch_array($res)) {
				$principal_telecom_changed=false;

				if ($parent=="Contact") {
					$parent_object=new Contact($row["Parent Key"]);
					$parent_label=_("Contact");
				}
				elseif ($parent=="Customer") {
					$parent_object=new Customer($row["Parent Key"]);
					$parent_label=_("Customer");
				}
				elseif ($parent=="Supplier") {
					$parent_object=new Supplier($row["Parent Key"]);
					$parent_label=_("Supplier");
				}
				elseif ($parent=="Company") {
					$parent_object=new Company($row["Parent Key"]);
					$parent_label=_("Company");
				}
				elseif ($parent=="Address") {
					$parent_object=new Address($row["Parent Key"]);
					$parent_label=_("Address");
				}
				$parent_object->editor=$this->editor;
				$old_princial_telecom=$parent_object->data[$parent." Main XHTML $type"];
				$parent_object->data[$parent." Main Plain $type"]=$this->display("plain");
				$parent_object->data[$parent." Main XHTML $type"]=$this->display("xhtml");
				$sql=sprintf("update `$parent Dimension` set `$parent Main Plain $type`=%s,`$parent Main XHTML $type`=%s where `$parent Key`=%d"
					,prepare_mysql($parent_object->data[$parent." Main Plain $type"])
					,prepare_mysql($parent_object->data[$parent." Main XHTML $type"])
					,$parent_object->id
				);

				mysql_query($sql);
				//print $sql;
				//print "$old_princial_telecom -> ".$parent_object->data[$parent." Main Plain $type"]."\n";

				if ($old_princial_telecom!=$parent_object->data[$parent." Main XHTML $type"])
					$principal_telecom_changed=true;

				if ($principal_telecom_changed and $add_parent_history) {


					if ($old_princial_telecom=="") {

						$history_data["History Abstract"]="$type associated ".$this->display("xhtml");
						$history_data["History Details"]=$this->display("plain")." "._("associated with")." ".$parent_object->get_name()." ".$parent_label;
						$history_data["Action"]="associated";
						$history_data["Direct Object"]=$parent;
						$history_data["Direct Object Key"]=$parent_object->id;
						$history_data["Indirect Object"]="$type";
						$history_data["Indirect Object Key"]="";


					} else {
						$history_data["History Abstract"]="$type updated to ".$this->display("xhtml");
						$history_data["History Details"]=_("$type changed from")." ".$old_princial_telecom." "._("to")." ".$this->display("plain")." "._("in")." ".$parent_object->get_name()." ".$parent_label;
						$history_data["Action"]="changed";
						$history_data["Direct Object"]=$parent;
						$history_data["Direct Object Key"]=$parent_object->id;
						$history_data["Indirect Object"]="$type";
						$history_data["Indirect Object Key"]="";



					}
					if ($parent=='Customer') {
						$parent_object->add_customer_history($history_data);
					} else {
						$parent_object->add_history($history_data);
					}


				}

			}
		}
	}


	function has_parents() {
		$has_parents=false;
		$sql=sprintf("select count(*) as total from `Telecom Bridge`  where  `Telecom Key`=%d  and `Subject Type`in ('Customer','Contact','Staff','Company','Supplier') ",$this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			if ($row['total']>0)
				$has_parents=true;
		}
		return $has_parents;
	}

	function delete() {
		$sql=sprintf("delete from `Telecom Dimension` where `Telecom Key`=%d",$this->id);
		mysql_query($sql);

		$this->deleted=true;
		$history_data['History Abstract']='Telecom Deleted';
		$history_data['History Details']=$this->data['Telecom Type'].' '.$this->display('plain')." "._('has been deleted');
		$history_data['Action']='deleted';
		$history_data['Direct Object']='Telecom';
		$history_data['Direct Object Key']=$this->id;
		$history_data['Indirect Object']='';
		$history_data['Indirect Object Key']='';
		$this->add_history($history_data);


		$type=$this->data['Telecom Type'];
		if ($type=='Fax')
			$type="FAX";


		if ($type=='Mobile')
			$parents=array('Contact','Customer');
		else
			$parents=array('Contact','Company','Customer','Supplier');



		foreach ($parents as $parent) {


			$sql=sprintf("select `$parent Key` as `Parent Key`   from  `$parent Dimension` where `$parent Main $type Key`=%d group by `$parent Key`"
				,$this->id);
			// print "$sql";
			$res=mysql_query($sql);
			while ($row=mysql_fetch_array($res)) {
				$this->remove_from_parent($parent,$row['Parent Key'],$type);
			}


		}

		$sql=sprintf("delete from `Telecom Bridge`  where  `Telecom Key`=%d", $this->id);
		mysql_query($sql);

	}

	function remove_from_parent($parent,$parent_key,$type='') {

		if(!in_array($parent,array('Contact','Company'))){
			return;
		}


		$sql=sprintf("delete from `Telecom Bridge`  where  `Telecom Key`=%d and `Subject Type`=%s and `Subject Key`=%d  ",
			$this->id,
			prepare_mysql($parent),
			$parent_key
		);
		
		
		

		
		
		mysql_query($sql);

		$principal_Telecom_changed=false;

		if ($parent=='Contact') {
			$parent_object=new Contact($parent_key);
			$parent_label=_('Contact');
		}
		elseif ($parent=='Customer') {
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



		$sql=sprintf("update `$parent Dimension` set `$parent Main $type Key`=0, `$parent Main Plain $type`='',`$parent Main XHTML $type`='' where  `$parent Main $type Key`=%d and  `$parent Key`=%d"
			,$this->id
			,$parent_object->id
		);
		mysql_query($sql);

		$principal_affected=mysql_affected_rows();

		$history_data['History Abstract']=$this->data['Telecom Type'].' Removed';
		$history_data['History Details']=$this->data['Telecom Type'].' '.$this->display('plain')." "._('has been deleted from')." ".$parent_object->get_name()." ".$parent_label;
		$history_data['Action']='disassociate';
		$history_data['Direct Object']=$parent;
		$history_data['Direct Object Key']=$parent_object->id;
		$history_data['Indirect Object']='Telecom';
		$history_data['Indirect Object Key']=$this->id;



		if ($parent=='Customer') {
			$parent_object->add_customer_history($history_data);
		} else {
			$parent_object->add_history($history_data);
		}




		if ($parent=='Contact'   and $type=='Mobile' and $principal_affected) {


			$mobiles=$parent_object->get_mobiles();
			//print_r($mobiles);
			foreach ($mobiles as $mobile) {
				$parent_object->update_principal_mobile($mobile->id);
				break;
			}
		}

		elseif ($type=='Telephone' and $principal_affected) {


			$telephones=$parent_object->get_telephones();
			//print_r($telephones);
			foreach ($telephones as $telephone) {
				$parent_object->update_principal_telephone($telephone->id);
				break;
			}
		}
		elseif ($type=='FAX' and $principal_affected) {

			$faxes=$parent_object->get_faxes();
			//print_r($faxes);
			foreach ($faxes as $fax) {
				$parent_object->update_principal_faxes($fax->id);
				break;
			}

		}

	}

	function get_customer_keys() {
		$keys=array();

		$sql=sprintf("select `Subject Key`,`Subject Type` from `Telecom Bridge` where `Telecom Key`=%d  and `Subject Type`='Customer' "
			,$this->id);
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$keys[$row['Subject Key']]= $row['Subject Key'];
		}
		return $keys;
	}


	function get_parent_keys($type=false) {
		$where_type='';
		if ($type) {
			$where_type=' and `Subject Type`='.prepare_mysql($type);
		}

		$keys=array();
		$sql=sprintf("select `Subject Key`,`Subject Type` from `Telecom Bridge` where  `Telecom Key`=%d $where_type",
			$this->id);
		$res=mysql_query($sql);
		//print $sql;
		while ($row=mysql_fetch_assoc($res)) {
			$keys[$row['Subject Key']]=array('Subject Key'=>$row['Subject Key'],'Subject Type'=>$row['Subject Type']);
		}
		return $keys;
	}



}
?>
