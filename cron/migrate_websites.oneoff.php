<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 28 May 2016 at 18:36:54 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


$default_DB_link=@mysql_connect($dns_host, $dns_user, $dns_pwd );
if (!$default_DB_link) {
	print "Error can not connect with database server\n";
}
$db_selected=mysql_select_db($dns_db, $default_DB_link);
if (!$db_selected) {
	print "Error can not access the database\n";
	exit;
}
mysql_set_charset('utf8');
mysql_query("SET time_zone='+0:00'");



require_once 'utils/get_addressing.php';
require_once 'utils/parse_natural_language.php';

require_once 'class.Site.php';
require_once 'class.Page.php';
require_once 'class.Website.php';
require_once 'class.WebsiteNode.php';
require_once 'class.Webpage.php';


$editor=array(
	'Author Name'=>'',
	'Author Alias'=>'',
	'Author Type'=>'',
	'Author Key'=>'',
	'User Key'=>0,
	'Date'=>gmdate('Y-m-d H:i:s')
);


//$sql=sprintf('select * from `Part Dimension` where `Part SKU`=5305');

$sql=sprintf('select * from `Site Dimension`  ');

if ($result=$db->query($sql)) {
	foreach ($result as $row) {
		$site=new Site($row['Site Key']);

		$sql=sprintf("insert into `Website Dimension` (`Website Key`,`Website Store Key`,`Website Code`,`Website Name`,`Website URL`,`Website Locale`,`Website From`) values(%d,%s,%s,%s,%s,%s,%s);",
			$site->id,
			prepare_mysql($site->get('Site Store Key')),
			prepare_mysql($site->get('Site Code')),
			prepare_mysql($site->get('Site Name')),
			prepare_mysql($site->get('Site URL')),
			prepare_mysql($site->get('Site Locale')),
			prepare_mysql($site->get('Site From'))

		);
		//print "$sql\n";


		$db->exec($sql);
		$sql="insert into `Website Data` (`Website Key`) values(".$row['Site Key'].");";
		$db->exec($sql);


		$website=new Website($row['Site Key']);
		$website->editor=$editor;

		$website_key=$website->id;

		$website_node[$website_key]['Home']=$website->create_website_node(array('Website Node Code'=>'p.Home', 'Website Node Name'=>_('Home'), 'Website Node Locked'=>'Yes', 'Website Node Type'=>'Root', 'Website Node Icon'=>'home'));
		$page=new Webpage($website_node[$website_key]['Home']->get_webpage_key());
		$page->update(array('Webpage Properties'=>
				json_encode(array('body_classes'=>'common-home page-common-home layout-fullwidth'))
			), 'no_history');



		$website_node[$website_key]['MyA']=$website_node[$website_key]['Home']->create_website_node(array('Website Node Code'=>'p.MyA', 'Website Node Name'=>_('My account'), 'Website Node Locked'=>'Yes', 'Website Node Type'=>'Root', 'Website Node Icon'=>'user'));

		$website_node[$website_key]['MyA']->create_website_node(
			array('Website Node Code'=>'p.Login', 'Website Node Name'=>_('Login'), 'Website Node Locked'=>'Yes', 'Website Node Type'=>'Head')
		);
		$website_node[$website_key]['MyA']->create_website_node(
			array('Website Node Code'=>'p.Register', 'Website Node Name'=>_('Register'), 'Website Node Locked'=>'Yes', 'Website Node Type'=>'Head')
		);
		$website_node[$website_key]['MyA']->create_website_node(
			array('Website Node Code'=>'p.Pwd', 'Website Node Name'=>_('Forgotten password'), 'Website Node Locked'=>'Yes', 'Website Node Type'=>'Head')
		);
		$website_node[$website_key]['MyA']->create_website_node(
			array('Website Node Code'=>'p.Profile', 'Website Node Name'=>_('My account'), 'Website Node Locked'=>'Yes', 'Website Node Type'=>'Head')
		);



		$website_node[$website_key]['CS']=$website_node[$website_key]['Home']->create_website_node(array('Website Node Code'=>'p.CS', 'Website Node Name'=>_('Customer services'), 'Website Node Locked'=>'Yes', 'Website Node Type'=>'Root', 'Website Node Icon'=>'thumbs-o-up'));
		$page=new Webpage($website_node[$website_key]['CS']->get_webpage_key());
		$page->update(array('Webpage Properties'=>
				json_encode(array('body_classes'=>'information-contact page-information-contact layout-fullwidth'))
			), 'no_history');

		$settings=array(
			'title'=>array('edit'=>'string', 'id'=>'title', 'value'=>_('Customer services')),
			'content'=>array('edit'=>'text', 'id'=>'content', 'value'=>_('This is a CMS block edited from admin panel'))
		);

		$page->append_block(array('Webpage Block Template'=>'info.blank', 'Webpage Block Settings'=>$settings));


		$node=$website_node[$website_key]['CS']->create_website_node(
			array('Website Node Code'=>'p.Contact', 'Website Node Name'=>_('Contact us'), 'Website Node Locked'=>'Yes', 'Website Node Type'=>'Head')
		);
		$page=new Webpage($node->get_webpage_key());
		$page->update(array('Webpage Properties'=>
				json_encode(array('body_classes'=>'information-contact page-information-contact layout-fullwidth'))
			), 'no_history');
		$page->append_block(array('Webpage Block Template'=>'contact.map', 'Webpage Block Settings'=>array()));


		$node=$website_node[$website_key]['CS']->create_website_node(
			array('Website Node Code'=>'p.Delivery', 'Website Node Name'=>_('Delivery'), 'Website Node Locked'=>'No', 'Website Node Type'=>'Head', 'Website Node Icon'=>'truck fa-flip-horizontal')
		);
		$page=new Webpage($node->get_webpage_key());
		$page->update(array('Webpage Properties'=>
				json_encode(array('body_classes'=>'information-contact page-information-contact layout-fullwidth'))
			), 'no_history');

		$settings=array(
			'title'=>array('edit'=>'string', 'id'=>'title', 'value'=>_('Delivery')),
			'content'=>array('edit'=>'text', 'id'=>'content', 'value'=>_('This is a CMS block edited from admin panel'))
		);

		$page->append_block(array('Webpage Block Template'=>'info.blank', 'Webpage Block Settings'=>$settings));



		$node=$website_node[$website_key]['CS']->create_website_node(
			array('Website Node Code'=>'p.GTC', 'Website Node Name'=>_('Terms & Conditions'), 'Website Node Locked'=>'Yes', 'Website Node Type'=>'Head')
		);
		$page=new Webpage($node->get_webpage_key());
		$page->update(array('Webpage Properties'=>
				json_encode(array('body_classes'=>'information-contact page-information-contact layout-fullwidth'))
			), 'no_history');

		$settings=array(
			'title'=>array('edit'=>'string', 'id'=>'title', 'value'=>_('General Terms & Conditions')),
			'content'=>array('edit'=>'text', 'id'=>'content', 'value'=>_('This is a CMS block edited from admin panel'))
		);

		$page->append_block(array('Webpage Block Template'=>'info.blank', 'Webpage Block Settings'=>$settings));



		$website_node[$website_key]['Cat']=$website_node[$website_key]['Home']->create_website_node(array('Website Node Code'=>'p.Cat', 'Website Node Name'=>_('Catalogue'), 'Website Node Locked'=>'Yes', 'Website Node Type'=>'Root'));
		$website_node[$website_key]['Insp']=$website_node[$website_key]['Home']->create_website_node(array('Website Node Code'=>'p.Insp', 'Website Node Name'=>_('Inspiration'), 'Website Node Locked'=>'No', 'Website Node Type'=>'Root'));






	}
}

exit;


$sql=sprintf('select * from `Page Store Dimension` PS left join `Page Store Data Dimension` PSD on (PS.`Page Key`=PSD.`Page Key`) left join `Page Dimension` P on (P.`Page Key`=PS.`Page Key`) left join `Site Dimension` S on (S.`Site Key`=PS.`Page Site Key`) ');

if ($result=$db->query($sql)) {
	foreach ($result as $row) {


		$sql=sprintf("insert into `Webpage Dimension` (`Webpage Key`,`Webpage Website Key`,`Webpage Store Key`,`Webpage Parent Key`,`Webpage Code`,`Webpage Name`,`Webpage Status`,`Webpage From`) values(%d,%d,%d,%d,%s,%s,%s,%s);",
			$row['Page Key'],
			$row['Page Site Key'],
			$row['Page Store Key'],
			$row['Page Parent Key'],
			prepare_mysql($row['Page Store Code']),
			prepare_mysql($row['Page Store Title']),
			prepare_mysql($row['Page Store State']),
			prepare_mysql($row['Page Store Creation Date'])

		);
		print "$sql\n";


		$db->exec($sql);
		$sql="insert into `Website Data` (`Website Key`) values(".$row['Site Key'].");";
		$db->exec($sql);

	}
}



?>
