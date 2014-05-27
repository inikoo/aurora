<?php
/*

  Autor: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2011, Inikoo

*/
include_once('class.Address.php');

class LightCustomer {

    var $id=false;
    var $data=array();

    function __construct($arg1=false,$arg2=false) {
        if (is_numeric($arg1) and !$arg2) {
            $this->get_data('id',$arg1);
            return;
        }

        $this->get_data($arg1,$arg2);


    }

    function get_data($tag,$id) {
        if ($tag=='id')
            $sql=sprintf("select * from `Customer Dimension` where `Customer Key`=%s",prepare_mysql($id));
        elseif($tag=='email')
        $sql=sprintf("select * from `Customer Dimension` where `Customer Email`=%s",prepare_mysql($id));
        elseif($tag=='all') {
            $this->find($id);
            return true;
        }
        else
            return false;
        $result=mysql_query($sql);

        if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
            $this->id=$this->data['Customer Key'];
        }
    }




 function get_hello($locale=false) {

        if ($locale) {

            if (preg_match('/^es_/',$locale)) {
                $unknown_name='';
                $greeting_prefix='Hola';
            } else {
                $unknown_name='';
                $greeting_prefix='Hello';
            }



        } else{
            $unknown_name='';
             $greeting_prefix='Hello';
        }
        if ($this->data['Customer Name']=='' and $this->data['Customer Main Contact Name']=='')
            return $unknown_name;
        $greeting=$greeting_prefix.' '.$this->data['Customer Main Contact Name'];
        if ($this->data['Customer Type']=='Company') {
            $greeting.=', '.$this->data['Customer Name'];
        }
        return $greeting;

    }




    function get_greetings($locale=false) {

        if ($locale) {

            if (preg_match('/^es_/',$locale)) {
                $unknown_name='A quien corresponda';
                $greeting_prefix='Estimado';
            } else {
                $unknown_name='To whom it corresponds';
                $greeting_prefix='Dear';
            }



        } else{
            $unknown_name='To whom it corresponds';
             $greeting_prefix='Dear';
        }
        if ($this->data['Customer Name']=='' and $this->data['Customer Main Contact Name']=='')
            return $unknown_name;
        $greeting=$greeting_prefix.' '.$this->data['Customer Main Contact Name'];
        if ($this->data['Customer Type']=='Company') {
            $greeting.=', '.$this->data['Customer Name'];
        }
        return $greeting;

    }


    function get($key) {


		
        switch ($key) {
        case('name'):
            return ($this->data['Customer Name']==''?_('Customer'):$this->data['Customer Name']);
            break;
        case('contact'):
            return ($this->data['Customer Main Contact Name']==''?_('Customer'):$this->data['Customer Main Contact Name']);
            break;
        case('email'):
            return $this->data['Customer Main Plain Email'];
            break;
        case('address'):
            return $this->data['Customer Main XHTML Address'];
            break;

        case('greting'):
        case('greeting'):
        case('gretings'):
        case('greetings'):
            return $this->get_greetings();

            break;

        default:
            return false;
            break;
        }



        return false;
    }
	
	function get_other_emails_data() {

        $sql=sprintf("select B.`Email Key`,`Email`,`Email Description` from `Email Bridge` B  left join `Email Dimension` E on (E.`Email Key`=B.`Email Key`) where  `Subject Type`='Customer' and `Subject Key`=%d "
                     ,$this->id );

        $email_keys=array();
        $result=mysql_query($sql);
        while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            if ($row['Email Key']!=$this->data['Customer Main Email Key'])
                $email_keys[$row['Email Key']]= array(
                                                    'email'=>$row['Email'],
                                                    'xhtml'=>'<a href="mailto:'.$row['Email'].'">'.$row['Email'].'</a>',
                                                    'label'=>$row['Email Description']

                                                );
        }
        return $email_keys;

    }

	function get_other_telephones_data() {
        return $this->get_other_telecoms_data('Telephone');
    }
	
	function get_other_telecoms_data($type='Telephone') {

        $sql=sprintf("select B.`Telecom Key`,`Telecom Description` from `Telecom Bridge` B left join `Telecom Dimension` T on (T.`Telecom Key`=B.`Telecom Key`) where `Telecom Type`=%s  and `Subject Type`='Customer' and `Subject Key`=%d ",
                     prepare_mysql($type),
                     $this->id
                    );
//print $sql;
        $telecom_keys=array();
        $result=mysql_query($sql);
        while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            if ($row['Telecom Key']!=$this->data["Customer Main $type Key"]) {

                $telecom=new Telecom($row['Telecom Key']);

                $telecom_keys[$row['Telecom Key']]= array(
                                                        'number'=>$telecom->display('plain'),
                                                        'xhtml'=>$telecom->display('xhtml'),
                                                        'label'=>$row['Telecom Description']
                                                    );

            }
        }
        return $telecom_keys;

    }
	
	function get_other_mobiles_data() {
        return $this->get_other_telecoms_data('Mobile');
    }

	function get_other_faxes_data() {
        return $this->get_other_telecoms_data('FAX');
    }
	
	function billing_address_xhtml() {


        if ($this->data['Customer Billing Address Link']=='None') {

            $address=new Address($this->data['Customer Billing Address Key']);

        } else
            $address=new Address($this->data['Customer Main Address Key']);

        return $address->display('xhtml');

    }
	
	function get_tax_number($reread=false) {
        return $this->data['Customer Tax Number'];
    }

    function get_registration_number($reread=false) {
        return $this->data['Customer Registration Number'];
    }
	
		
	function is_user_customer($data){
		$sql=sprintf("select * from `User Dimension` where `User Parent Key`=%d and `User Type`='Customer' ", $data);
		$result=mysql_query($sql);
		if($row=mysql_fetch_array($result, MYSQL_ASSOC))
			return array(true, $row);
	}
	
	function get_other_email_login_handle(){
		$other_login_handle_emails=array();
		foreach($this->get_other_emails_data() as $email){
			$sql=sprintf("select `User Key` from `User Dimension` where `User Handle`='%s'", $email['email']);

			$result=mysql_query($sql);
			
			if($row=mysql_fetch_array($result)){
				$other_login_handle_emails[$email['email']]=$email['email'];
			}
		}
		
		return $other_login_handle_emails;
	}
	
	function load($key='',$arg1=false) {
        switch ($key) {
        case('contact_data'):
        case('contact data'):
            $contact=new Contact($this->get('customer contact key'));
            if ($contact->id)
                $this->contact_data=$contact->data;
            else
                $this->errors[]='Error geting contact data object. Contact key:'.$this->get('customer contact key');
            break;
        case('ship to'):

            $sql=sprintf('select * from `Ship To Dimension` where `Ship To Key`=%d ',$arg1);

            //  print $sql;
            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
                $this->ship_to[$row['Ship To Key']]=$row;


            } else
                $this->errors[]='Error loading ship to data. Ship to Key:'.$arg1;

            break;
        }

    }
	
	function delivery_address_xhtml() {

        if ($this->data['Customer Delivery Address Link']=='None') {

            $address=new Address($this->data['Customer Main Delivery Address Key']);

        }
        elseif ($this->data['Customer Delivery Address Link']=='Billing')
        $address=new Address($this->data['Customer Billing Address Key']);
        else
            $address=new Address($this->data['Customer Main Address Key']);

        $tel=$address->get_formated_principal_telephone();
        if ($tel!='') {
            $tel=_('Tel').': '.$tel.'</br>';
        }

        return $tel.$address->display('xhtml');

    }
}
?>
