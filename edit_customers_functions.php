<?php
function add_customer($data) {
    //Timer::timing_milestone('begin');




    if ($data['Customer Type']=='Person') {
        $data['Customer Name']=$data['Customer Main Contact Name'];
        $data['Customer Company Name']='';

        $contact=new Contact();
        $contact->editor=$data['editor'];
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

        $contact_data=array();
        foreach($data as $key=>$val) {
            if ($key=='Customer Main Contact Name') {
                $_key='Contact Name';
            } else if (preg_match('/Customer Address/i',$key)) {
                $_key=preg_replace('/Customer Address/i','Contact Home Address',$key);
            } else {
                $_key=preg_replace('/Customer /','Contact ',$key);
            }
            $contact_data[$_key]=$val;

            if (array_key_exists($_key,$address_home_data))
                $address_home_data[$_key]=$val;
        }

        $contact->create($contact_data,$address_home_data);

        // print_r($contact_data);
        //exit;
        $data['Customer Main Contact Key']=$contact->id;
        unset($contact);

    } else {//Company
        $data['Customer Company Name']=$data['Customer Name'];

        $contact=new Contact();
        $contact->editor=$data['editor'];

        $contact_data=array();
        foreach($data as $key=>$val) {

            if ($key=='Customer Name') {
                continue;
            }
            if ($key=='Customer Main Contact Name') {
                $_key='Contact Name';
            } else {
                $_key=preg_replace('/Customer /','Contact ',$key);
            }

            if (preg_match('/telephone|fax/i',$key)) {
                $val='';
            }

            $contact_data[$_key]=$val;
        }

        $contact->create($contact_data);
        $address_data=array('Company Address Line 1'=>'','Company Address Town'=>'','Company Address Line 2'=>'','Company Address Line 3'=>'','Company Address Postal Code'=>'','Company Address Country Name'=>'','Company Address Country Code'=>'','Company Address Country First Division'=>'','Company Address Country Second Division'=>'');

        $company_data=array();
        foreach($data as $key=>$val) {
            if ($key!='Customer Type') {
                $_key=preg_replace('/Customer /','Company ',$key);
                $company_data[$_key]=$val;
            }

            if (array_key_exists($_key,$address_data))
                $address_data[$_key]=$val;

        }


        $company=new Company();
        $company->editor=$data['editor'];

        $company->create($company_data,$address_data,'use contact '.$contact->id);
        $data['Customer Main Contact Key']=$contact->id;
        $data['Customer Company Key']=$company->id;
        unset($company);
    }



    $customer=new Customer();
    $customer->editor=$data['editor'];
    $customer->create($data);

    //print_r ($data);


    if ($customer->new) {
        $store=new Store($customer->data['Customer Store Key']);


        $customer->update_orders();

        $customer->update_activity();
        $store->update_customers_data();

        // print_r($data);


        foreach($data as $data_key=>$data_value) {

            if (preg_match('/^cat\d+$/i',$data_key)) {
                //  print"$data_key\n";
                $category_key=preg_replace('/^cat/i','',$data_key);
                //  print"$category_key\n";

                if (!is_numeric($data_value)) {
                    $sql=sprintf("select `Category Key` from `Category Dimension` where `Category Parent Key`=%d and `Category Name`=%s ",
                                 $category_key,
                                 prepare_mysql($data_value)
                                );
                    //print $sql;
                    $res=mysql_query($sql);
                    if ($row=mysql_fetch_assoc($res)) {
                        $data_value=$row['Category Key'];
                    }
                }

                if ($data_value) {
                    $sql=sprintf("insert into `Category Bridge` values (%d,'Customer',%d)",
                                 $data_value,

                                 $customer->id
                                );
                    mysql_query($sql);
                    // print($sql);
                }
            }
        }
        $response= array('state'=>200,'action'=>'created','customer_key'=>$customer->id);

    } else {

        $response= array('state'=>400,'action'=>'error','customer_key'=>0,'msg'=>$customer->msg);
    }

    unset($customer);

    return $response;

}

function parse_company_person($posible_company_name,$posible_contact_name) {
    $company_name=$posible_company_name;
    $contact_name=$posible_contact_name;
    $person_person_factor=0;
    $person_company_factor=0;
    if ($posible_company_name!='' and $posible_contact_name!='') {
        $tipo_customer='Company';
        if ($posible_company_name==$posible_contact_name ) {
            $person_factor=is_person($posible_company_name);
            $company_factor=is_company($posible_company_name);
            if ($company_factor>$person_factor) {
                $tipo_customer='Company';
                $contact_name='';


            } else {
                $tipo_customer='Person';
                $company_name='';
            }

        } else {
            $company_person_factor=is_person($posible_company_name)+0.00001;
            $company_company_factor=is_company($posible_company_name)+0.00001;
            $person_company_factor=is_company($posible_contact_name)+0.00001;
            $person_person_factor=is_person($posible_contact_name)+0.00001;



            $company_ratio=$company_company_factor/$company_person_factor;
            $person_ratio=$person_person_factor/$person_company_factor;

            $ratio=($company_ratio+$person_ratio)/2;

            //print "** $company_ratio $person_ratio\n";

            if ($ratio<0.4)
                $swap=true;
            else
                $swap=false;



            if ($swap) {
                $_name=$posible_company_name;
                $company_name=$posible_contact_name;
                $contact_name=$_name;
            }



        }


    }
    elseif($posible_company_name!='') {
        $tipo_customer='Company';
        $company_person_factor=is_person($posible_company_name);
        $company_company_factor=is_company($posible_company_name);

        if ( $company_person_factor>$company_company_factor) {
            $tipo_customer='Person';
            $_name=$posible_company_name;
            $company_name=$posible_contact_name;
            $contact_name=$_name;
        }


    }
    elseif($posible_contact_name!='') {
        $tipo_customer='Person';
        $person_company_factor=is_company($posible_contact_name);
        $person_person_factor=is_person($posible_contact_name);

        if ($person_company_factor>$person_person_factor ) {
            $tipo_customer='Company';
            $_name=$posible_company_name;
            $company_name=$posible_contact_name;
            $contact_name=$_name;
        }


    }
    else {
        $tipo_customer='Person';

    }
    /*
    printf("Name: %s  ; Company: %s  \n is company a person %f is company a company %f\n is paerson a comapny %f  is person a person%f  \n$tipo_customer,\nName: $contact_name\nCompany:$company_name\n",
        $posible_contact_name,
            $posible_company_name,

     $company_person_factor,
                $company_company_factor,
                $person_company_factor,
                $person_person_factor



    );
    */
    return array($tipo_customer,$company_name,$contact_name);



}

function is_person($name) {
    $company_suffix="L\.?T\.?D\.?";
    $company_prefix="The";
    $company_words=array('Gifts','Chemist','Pharmacy','Company','Business','Associates','Enterprises','hotel','shop','aromatheraphy');
    $name=_trim($name);
    $probability=1;
    if (preg_match('/\d/',$name)) {
        $probability*=0.00001;
    }
    if (preg_match("/\s+".$company_suffix."$/",$name)) {
        $probability*=0.001;
    }
    if (preg_match("/\s+".$company_prefix."$/",$name)) {
        $probability*=0.001;
    }
    // print_r($company_words);
    foreach($company_words as $word) {
        if (preg_match("/\b".$word."\b/i",$name)) {
            $probability*=0.01;
        }
    }




    if ($probability>1)$probability=1;
    return $probability;

}


function is_company($name,$locale='en_GB') {

    $name=_trim($name);
    //global $person_prefix;
    $probability=1;


    if ($locale='en_GB') {
        $person_prefixes=array("Mr","Miss","Ms");
        $common_company_suffixes=array("L\.?t\.?d\.?");
        $common_company_prefixes=array("the");

        $common_company_compoments=array("HQ","Limited");
    } else {
        $person_prefixes=array();
        $common_company_suffixes=array();
        $common_company_prefixes=array();

        $common_company_compoments=array();

    }

    foreach($common_company_prefixes as $company_prefix) {
        if (preg_match("/^".$company_prefix."\s+/i",$name)) {
            $probability*=10;
            break;
        }
    }

    foreach($common_company_suffixes as $company_suffix) {
        if (preg_match("/\s+".$company_suffix."$/i",$name)) {
            $probability*=10;
            break;
        }
    }


    foreach($person_prefixes as $person_prefix) {
        if (preg_match("/^".$person_prefix."\s+/i",$name)) {
            $probability*=0.01;
        }
    }

    $components=preg_split('/\s/',$name);


    if (count($components)>1) {
        $has_sal=false;
        $saludation=preg_replace('/\./','',$components[0]);
        $sql=sprintf('select `Salutation Key` from kbase.`Salutation Dimension` where `Salutation`=%s  ',prepare_mysql($saludation));
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            $probability*=0.9;
        }



    }



    if (count($components)==2) {
        $name_ok=false;
        $surname_ok=false;
        $sql=sprintf('select `First Name Key` from kbase.`First Name Dimension` where `First Name`=%s  ',prepare_mysql($components[0]));
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            $name_ok=true;
        }
        $sql=sprintf('select `Surname Key` from kbase.`Surname Dimension` where `Surname`=%s  ',prepare_mysql($components[1]));
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            $surname_ok=true;
        }
        if ($surname_ok and $name_ok) {
            $probability*=0.75;
        }
        if ($name_ok) {
            $probability*=0.95;
        }
        if ($surname_ok) {
            $probability*=0.95;
        }

        if (strlen($components[0])==1) {
            $probability*=0.95;
        }



    }
    elseif(count($components)==3) {

        $name_ok=false;
        $surname_ok=false;
        $sql=sprintf('select `First Name Key` from kbase.`First Name Dimension` where `First Name`=%s  ',prepare_mysql($components[0]));
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            $name_ok=true;
        }
        $sql=sprintf('select `Surname Key` from kbase.`Surname Dimension` where `Surname`=%s  ',prepare_mysql($components[2]));
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            $surname_ok=true;
        }
        if ($surname_ok and $name_ok) {
            $probability*=0.75;
        }
        if ($name_ok) {
            $probability*=0.95;
        }
        if ($surname_ok) {
            $probability*=0.95;
        }

        if (strlen($components[1])==1) {
            $probability*=0.95;
        }

        if (strlen($components[1])==1 and strlen($components[0])==1 ) {
            $probability*=0.99;
        }

    }

    if ($probability>1)$probability=1;

    return $probability;
}



function unique_concecutive_list_suffix($store_key) {

    $suffix=uniqid('', true);

    $sql=sprintf('select count(*) as num from `List Dimension` where `List Use Type`="CSV Import" and `List Store Key`=%d ',
                 $store_key
                );

    $num=0;
    $res=mysql_query($sql);
    if ($row=mysql_fetch_assoc($res)) {
        $num= (float) $row['num'];
    }

    $num++;
    $top_limit=$num+500;
    $top_soft_limit=$num+490;

    for ($i = $num; $i <= $top_limit; $i++) {

        if ($i<$top_soft_limit) {
            $suffix=$i;
        } else {
            $suffix=uniqid('', true);
        }
    //    print "i: $i $suffix  \n";

        $sql=sprintf('select count(*) as num from `List Dimension` where `List Name`=%s and `List Store Key`=%d ',
                     prepare_mysql(_('CSV Imported')." ".$suffix),
                     $store_key);

      //  print "$sql \n";

        $res2=mysql_query($sql);
        if ($row2=mysql_fetch_assoc($res2)) {
            if ($row2['num']==0)
                return $suffix;
        }


    }

    return $suffix;


}

function new_imported_csv_customers_list($store_key) {





    $list_sql=sprintf("insert into `List Dimension` (`List Scope`,`List Store Key`,`List Name`,`List Type`,`List Use Type`,`List Metadata`,`List Creation Date`) values ('Customer',%d,%s,%s,%s,NULL,NOW())",
                      $store_key,
                      prepare_mysql(_('CSV Imported')." ".unique_concecutive_list_suffix($store_key)),
                      prepare_mysql('Static'),
                      prepare_mysql('CSV Import'),
                      prepare_mysql(json_encode(array()))

                     );
    mysql_query($list_sql);
    // print "$list_sql";
    $customer_list_key=mysql_insert_id();

    return $customer_list_key;



}










?>