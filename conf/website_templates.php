<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 November 2016 at 16:56:32 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

function website_templates_config($website_type) {

	$website_templates = array(

		'EcomB2B' => array(

			'templates'=>array(

				//'header'=>array('scope'=>'Header','device'=>'Desktop'),
				//'footer'=>array('scope'=>'Footer','device'=>'Desktop'),
				'homepage'=>array('scope'=>'Home','device'=>'Desktop'),
				'login'=>array('scope'=>'Login','device'=>'Desktop'),
				'register'=>array('scope'=>'Register','device'=>'Desktop'),
				'reset_password'=>array('scope'=>'ResetPwd','device'=>'Desktop'),
				'profile'=>array('scope'=>'Profile','device'=>'Desktop'),
				'orders'=>array('scope'=>'Orders','device'=>'Desktop'),
				//'hub'=>array('scope'=>'Hub','device'=>'Desktop'),
				'contact'=>array('scope'=>'Contact','device'=>'Desktop'),

				'basket'=>array('scope'=>'Basket','device'=>'Desktop'),
				'checkout'=>array('scope'=>'Checkout','device'=>'Desktop'),
				'categories'=>array('scope'=>'Categories','device'=>'Desktop'),
				'category'=>array('scope'=>'Category','device'=>'Desktop'),
				'product'=>array('scope'=>'Product','device'=>'Desktop'),
				'blank'=>array('scope'=>'Blank','device'=>'Desktop'),
				
				//'header.mob'=>array('scope'=>'Header','device'=>'Mobile'),
				//'drawer.mob'=>array('scope'=>'Footer','device'=>'Mobile'),
				'homepage.mob'=>array('scope'=>'Home','device'=>'Mobile'),
				'login.mob'=>array('scope'=>'Login','device'=>'Mobile'),
				'register.mob'=>array('scope'=>'Register','device'=>'Mobile'),
				'reset_password.mob'=>array('scope'=>'ResetPwd','device'=>'Mobile'),
				'profile.mob'=>array('scope'=>'Profile','device'=>'Mobile'),
				'orders.mob'=>array('scope'=>'Orders','device'=>'Mobile'),
				//'hub.mob'=>array('scope'=>'Hub','device'=>'Mobile'),
				'contact.mob'=>array('scope'=>'Contact','device'=>'Mobile'),
				'basket.mob'=>array('scope'=>'Basket','device'=>'Mobile'),
				'checkout.mob'=>array('scope'=>'Checkout','device'=>'Mobile'),
				'categories.mob'=>array('scope'=>'Categories','device'=>'Mobile'),
				'category.mob'=>array('scope'=>'Category','device'=>'Mobile'),
				'product.mob'=>array('scope'=>'Product','device'=>'Mobile'),
				'blank.mob'=>array('scope'=>'Blank','device'=>'Mobile')
			)

		),


	);

	return $website_templates[$website_type];

}

?>
