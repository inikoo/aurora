<?php

//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2012
include_once '../../app_files/db/dns.php';

include_once '../../class.Product.php';
include_once '../../class.User.php';
include_once '../../class.Customer.php';

include_once '../../class.Store.php';
include_once '../../class.Site.php';
include_once '../../class.EmailSiteReminder.php';

error_reporting(E_ALL);

date_default_timezone_set('UTC');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}
//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
	print "Error can not access the database\n";
	exit;
}

require 'MadMimi.class.php';
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
setlocale(LC_MONETARY, 'en_GB.UTF-8');

global $myconf;



$email_data=array(

	'AW.biz'=>array(
		'promotion_name' => 'Back In Stock Notification Service',
		'from' => 'Ancient Wisdom <care@ancientwisdom.biz>',
		'subject' => 'Product back in stock',
		'subjectn' => 'Products back in stock',
	
		'email_provider_user'=>'david@ancientwisdom.biz',
		'email_provider_password'=>'447ba8315277320c130646a345136dc8',
		
	),
	'AWC.com'=>array(
		'promotion_name' => 'AW-Cadeaux Notification de retour en stock',
		'from' => 'aw-cadeaux <bruno@aw-cadeaux.com>',
		'subject' => 'Produit de retour en stock',
		'subjectn' => 'Produits de retour en stock',
		'email_provider_user'=>'david@ancientwisdom.biz',
		'email_provider_password'=>'447ba8315277320c130646a345136dc8'
	),
	
		'AWG.com'=>array(
		'promotion_name' => 'AW-Geschenke Mitteilung über wieder vorrätige Produkte',
		'from' => 'aw-geschenke <martina@aw-geschenke.com>',
			'subject' => 'Produkt wieder vorrätig',
		'subjectn' => 'Produkte wieder vorrätig',
		'email_provider_user'=>'david@ancientwisdom.biz',
		'email_provider_password'=>'447ba8315277320c130646a345136dc8'
	)
	
	
	
);


$sql=sprintf("select `Site Key`,`Site Code` from `Site Dimension`");
$res2=mysql_query($sql);
while ($row2=mysql_fetch_assoc($res2)) {
	$site=new Site($row2['Site Key']);

	if (array_key_exists($site->data['Site Code'],$email_data)) {

		$site=new Site($row2['Site Key']);
		$sql=sprintf("select GROUP_CONCAT(`Email Site Reminder Key`) as esr_keys, GROUP_CONCAT(`Trigger Scope Key`) as pids,`User Key` from `Email Site Reminder Dimension` where `Site Key`=%d and `Email Site Reminder State`='Ready' and `Email Site Reminder Subject`='User' group by `User Key`",
			$site->id
		);
		//print $sql;
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {

			$email_reminder_user=new User($row['User Key']);

			$customer= new Customer($email_reminder_user->data['User Parent Key']);


			$pids=preg_split('/,/',$row['pids']);
			$products='';
			$product_codes='';
			$number_products=0;
			foreach ($pids as $pid) {
				$product=new Product('pid',$pid);

				$url=$product->get_main_page_url($site->id);
				if ($url) {

					$products.=sprintf('<a href="http://%s">%s</a> %s,<br/>',
						$url,
						$product->data['Product Code'],
						$product->data['Product Name']
					);

					if ($number_products<=3) {
						$product_codes.=sprintf(', %s',
							$product->data['Product Code']

						);
					}elseif ($number_products==4) {
						$product_codes.=',...';
					}


					$number_products++;
				}



			}
			$products=preg_replace('/,\<br\/\>$/','',$products);
			$product_codes=preg_replace('/^, /','',$product_codes);
			$subject_data=array(
				'customer_name' => ($customer->data['Customer Name']==$customer->data['Customer Main Contact Name']?'':$customer->data['Customer Name']),
				'contact_name' => ($customer->data['Customer Main Contact Name']==''?_('Sir/Madam'):$customer->data['Customer Main Contact Name']),
				'email'=>$email_reminder_user->data['User Handle']
			);

			if ($number_products==1) {
			//	$email_data[$site->data['Site Code']]['subject']=_('Product back in stock').": ($product_codes)";
			$email_data[$site->data['Site Code']]['subject']=$email_data[$site->data['Site Code']]['subject'].": ($product_codes)";
			}else {
			//	$email_data[$site->data['Site Code']]['subject']=_('Products back in stock').": ($product_codes)";
			$email_data[$site->data['Site Code']]['subject']=$email_data[$site->data['Site Code']]['subjectn'].": ($product_codes)";

			}

			//$email_site_reminder=new EmailSiteReminder($row['Email Site Reminder Key']);

			send_email($subject_data,$products,$email_data[$site->data['Site Code']]);

			$esr_keys=preg_split('/,/',$row['esr_keys']);

			
			foreach ($esr_keys as $esr_key) {
			$email_site_reminder=new EmailSiteReminder($esr_key);
				$email_site_reminder->mark_as_send();
			
			}

		}



	}
}


function send_email($subject_data,$products,$email_data) {
	$body_array = array(
		'organization' => $subject_data['customer_name'],
		'name' => $subject_data['contact_name'],
		'products'=>$products
	);
	//$email='raul@inikoo.com';
	$options = array(
		'promotion_name' => $email_data['promotion_name'],
		'recipients' => $subject_data['email'],
		'from' => $email_data['from'],

		'subject' =>$email_data['subject'],

	);

	//print_r($body_array);
	// print_r($options);

	$mailer = new MadMimi($email_data['email_provider_user'],$email_data['email_provider_password']);
	$response = $mailer->SendMessage($options, $body_array, true);

	return $response;
	//print_r($response);

}


?>
