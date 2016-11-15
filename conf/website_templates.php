<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 November 2016 at 16:56:32 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

function website_templates_config() {

	$website_templates = array(

		'EcomB2B' => array(
			'template_scope'=>array(

				'header'=>array(),
				'footer'=>array(),
				'drawer'=>array(),
				'homepage'=>array(),
				'login'=>array(),
				'profile'=>array(),
				'basket'=>array(),
				'checkout'=>array(),
				'catalogue'=>array(),
				'categories'=>array(),
				'category'=>array(),
				'product'=>array(),
				'info'=>array(),
			),
			'templates'=>array(

				'header'=>array('type'=>'header'),
				'footer'=>array('type'=>'footer'),
				'homepage'=>array('type'=>'homepage'),
				'login'=>array('type'=>'login'),
				'profile'=>array('type'=>'profile'),
				'basket'=>array('type'=>'basket'),
				'checkout'=>array('type'=>'checkout'),
				'catalogue'=>array('type'=>'catalogue'),
				'categories'=>array('type'=>'categories'),
				'categories'=>array('type'=>'categories'),
				'categories'=>array('type'=>'categories'),
				'blank'=>array('type'=>'info'),
				
				'header.mobile'=>array('type'=>'header','device'=>'Mobile'),
				'drawer.mobile'=>array('type'=>'footer'),
				'homepage.mobile'=>array('type'=>'homepage'),
				'login.mobile'=>array('type'=>'login'),
				'profile.mobile'=>array('type'=>'profile'),
				'basket.mobile'=>array('type'=>'basket'),
				'checkout.mobile'=>array('type'=>'checkout'),
				'catalogue.mobile'=>array('type'=>'catalogue'),
				'categories.mobile'=>array('type'=>'categories'),
				'categories.mobile'=>array('type'=>'categories'),
				'categories.mobile'=>array('type'=>'categories'),
				'blank.mobile'=>array('type'=>'info'),
			)

		),


	);

	return $website_templates;

}


?>
