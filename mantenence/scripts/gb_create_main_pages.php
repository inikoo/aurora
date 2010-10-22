<?php
$store_code='UK';





$page_data[$store_code]=array(
	    array(
		  'Page Code'=>'home'
		  ,'Page Section'=>'home'
		  ,'Page Source Template'=>'home.tpl'
		  ,'Page URL'=>'index.php'
		  ,'Page Description'=>'Home Page'
		  ,'Page Title'=>'Ancient Wisdom Home'
		  ,'Page Short Title'=>'Home'
		  ,'Page Store Title'=>'Welcome to Ancient Wisdom'
		  ,'Page Store Subtitle'=>'Europe\'s Biggest Online Giftware Wholesaler'
		  ,'Page Store Slogan'=>'Exotic & Esoteric'
		  ,'Page Store Resume'=>'Currently we have over 10000 exotic, interesting & unique wholesale product lines spread over approaching 1000 web pages all available to order on-line for delivery next day in the UK (well we do our best)'
		  
		  
		  )
	     ,array(
		  'Page Code'=>'register'
		  ,'Page Section'=>'registration'
		  ,'Page Source Template'=>'register.tpl'
		  ,'Page URL'=>'register.php'
		  ,'Page Description'=>'Registration Page'

		  ,'Page Title'=>'Registration'
		  ,'Page Short Title'=>'Registration'
		  ,'Page Store Title'=>'Register to Ancient Wisdom'
		  ,'Page Store Subtitle'=>''
		  ,'Page Store Slogan'=>'Hello stranger'
		  ,'Page Store Resume'=>'Please note this is a wholesale site we supply wholesale to the trade.'

		  
		  )
  ,array(
		  'Page Code'=>'lost_password'
		  ,'Page Section'=>'registration'

		  ,'Page Source Template'=>'lost_password.tpl'
		  ,'Page URL'=>'reset.php'
		  ,'Page Description'=>'Lost Password Page'

		  ,'Page Title'=>'Lost Password'
		  ,'Page Short Title'=>'Lost Pasword'
		  ,'Page Store Title'=>'Lost Pasword'
		  ,'Page Store Subtitle'=>''
		  ,'Page Store Slogan'=>'Password forgotten?'
		  ,'Page Store Resume'=>'Could be that you forgot your password or that you are a previous customer on its first login'

		  
		  )
	,array(
		  'Page Code'=>'checkout'
		  ,'Page Section'=>'basket'

		  ,'Page Source Template'=>'checkout.tpl'
		  ,'Page URL'=>'reset.php'
		  ,'Page Description'=>'Checkout Page'

		  ,'Page Title'=>'Checkout'
		  ,'Page Short Title'=>'Checkout'
		  ,'Page Store Title'=>'Checkout'
		  ,'Page Store Subtitle'=>''
		  ,'Page Store Slogan'=>'Checkout'
		  ,'Page Store Resume'=>''

		  
		  )	  
		  
		  
 ,array(
		  'Page Code'=>'login'
		  ,'Page Section'=>'registration'

		  ,'Page Source Template'=>'login.tpl'
		  ,'Page URL'=>'reset.php'
		  ,'Page Description'=>'Login Page'

		  ,'Page Title'=>'Login'
		  ,'Page Short Title'=>'Login'
		  ,'Page Store Title'=>'Login'
		  ,'Page Store Subtitle'=>''
		  ,'Page Store Slogan'=>'Login here'
		  ,'Page Store Resume'=>''

		  
		  )
		 ,array(
		  'Page Code'=>'reset'
		  ,'Page Section'=>'registration'

		  ,'Page Source Template'=>'reset.tpl'
		  ,'Page URL'=>'reset.php'
		  ,'Page Description'=>'Reset Password Page'

		  ,'Page Title'=>'Reset Password'
		  ,'Page Short Title'=>'Reset Pasword'
		  ,'Page Store Title'=>'Reset Pasword'
		  ,'Page Store Subtitle'=>''
		  ,'Page Store Slogan'=>'Set your password'
		  ,'Page Store Resume'=>'Please note this is a wholesale site we supply wholesale to the trade.'

		  
		  )  
		  
	     ,array(
		  'Page Code'=>'contact'
		  ,'Page Section'=>'info'

		  ,'Page Source Template'=>'contact.html'
		  ,'Page Description'=>'Contact information details (address, telephones, emails, and directions)'
		  
		  ,'Page Title'=>'Contact Details'
		  ,'Page Short Title'=>'Contact'
		  ,'Page Store Title'=>'Contact Page'
		  ,'Page Store Subtitle'=>''
		  ,'Page Store Slogan'=>'You know where we are'
		  ,'Page Store Resume'=>'Please don\'t hesitate to contact us if you need more information<br>In May 2008 we moved to brand new premises, you can visit us and have a look at our showroom, to make an appoiment please click <a href="info.php?page=showroom">here</a>'
		  ,'Product Presentation Template Data'=>file_get_contents('sites_data/gb_contact.html')

		  )

	     ,array(
		  'Page Code'=>'showroom'
		   ,'Page Section'=>'info'
		  ,'Page Source Template'=>'showroom.html'
		  ,'Page Description'=>'Information about our showroom'
		  
		  ,'Page Title'=>'Showroom'
		  ,'Page Short Title'=>'Showroom'
		  ,'Page Store Title'=>'Showroom'
		  ,'Page Store Subtitle'=>''
		  ,'Page Store Slogan'=>'You can visit us!'
		  ,'Page Store Resume'=>'Why not visit us... we are always delighted to see our customers.'
		  ,'Product Presentation Template Data'=>file_get_contents('sites_data/gb_showroom.html')
		  
		  )	     
 ,array(
		  'Page Code'=>'export_guide'
		  ,'Page Section'=>'info'
		  ,'Page Source Template'=>'export_guide.html'
		  ,'Page Description'=>'Information about overseas orders'
		  
		  ,'Page Title'=>'Export Guide'
		  ,'Page Short Title'=>'Export'
		  ,'Page Store Title'=>'Export Guide'
		  ,'Page Store Subtitle'=>''
		  ,'Page Store Slogan'=>'Shipping Worldwide'
		  ,'Page Store Resume'=>'We have experience in shipping to many countries on all continents.<br/>Philippe our dedicated export customer service advisor is at your services, he  speak English & French well and will try his best in any European language'
		  ,'Product Presentation Template Data'=>file_get_contents('sites_data/gb_export_guide.html')
		  )

	      ,array(
		  'Page Code'=>'terms_and_conditions'
		  ,'Page Section'=>'info'
		  ,'Page Source Template'=>'terms_and_conditions.html'
		  ,'Page Description'=>'Terms and Conditions'
		  
		  ,'Page Title'=>'Terms & Conditions'
		  ,'Page Short Title'=>'T&C'
		  ,'Page Store Title'=>'Terms & Conditions'
		  ,'Page Store Subtitle'=>''
		  ,'Page Store Slogan'=>'The small print'
		  ,'Page Store Resume'=>''
		  ,'Product Presentation Template Data'=>file_get_contents('sites_data/gb_terms_and_conditions.html')

		     ),
	       array(
		  'Page Code'=>'company_ethics'
		   ,'Page Section'=>'info'
		  ,'Page Source Template'=>'ethics.html'
		  ,'Page Description'=>'Company Ethics'
		  
		  ,'Page Title'=>'Company Ethics'
		  ,'Page Short Title'=>'Company Ethics'
		  ,'Page Store Title'=>'Company Ethics'
		  ,'Page Store Subtitle'=>''
		  ,'Page Store Slogan'=>'Not is everything about money'
		  ,'Page Store Resume'=>'These are tricky subjects, but not one we choose to ignore as a company. On fact we take this very seriously'
		  ,'Product Presentation Type'=>'Template'
		  ,'Product Presentation Template Data'=>file_get_contents('sites_data/gb_ethics.html')
		 ),
	    array(
		  'Page Code'=>'faq'
		   ,'Page Section'=>'info'
		  ,'Page Source Template'=>'faq.html'
		  ,'Page Description'=>'FAQ'
		  
		  ,'Page Title'=>'FAQ'
		  ,'Page Short Title'=>'FAQ'
		  ,'Page Store Title'=>'Frequently Asked Questions'
		  ,'Page Store Subtitle'=>'(with answers)'
		  ,'Page Store Slogan'=>'You ask we aswer'
		  ,'Page Store Resume'=>'Here we recopilate the most common queries'
		  ,'Product Presentation Template Data'=>file_get_contents('sites_data/gb_faq.html')
		  ),
	    array(
		  'Page Code'=>'fob'
		   ,'Page Section'=>'incentives'
		  ,'Page Source Template'=>'first_order_bonus.html'
		  ,'Page Description'=>'First Order Bonus'
		  
		  ,'Page Title'=>'First Order Bonus'
		  ,'Page Short Title'=>'First Order Bonus'
		  ,'Page Store Title'=>'First Order Bonus'
		  ,'Page Store Subtitle'=>''
		  ,'Page Store Slogan'=>'Just a little thank you'
		  ,'Page Store Resume'=>'When you order over £100+vat for the first time we give you over a £100 of stock. (at retail value). '
		  ,'Product Presentation Template Data'=>file_get_contents('sites_data/gb_first_order_bonus.html')
		  ),

	    array(
		  'Page Code'=>'gold'
		    ,'Page Section'=>'incentives'
		  ,'Page Source Template'=>'gold.html'
		  ,'Page Description'=>'Gold Reward Promotion'
		  
		  ,'Page Title'=>'Gold Reward'
		  ,'Page Short Title'=>'Gold Reward'
		  ,'Page Store Title'=>'Gold Reward Promotion'
		  ,'Page Store Subtitle'=>''
		  ,'Page Store Slogan'=>'Incentives for aur regular customers'
		  ,'Page Store Resume'=>'Order within 30 days to receive a Discount Upgrade '
		  ,'Product Presentation Template Data'=>file_get_contents('sites_data/gb_gold.html')
		  ),
 
	    array(
		  'Page Code'=>'ten_pence'
		    ,'Page Section'=>'incentives'
		  ,'Page Source Template'=>'ten_pence.html'
		  ,'Page Description'=>'10p Special Product'
		  
		  ,'Page Title'=>'10p Special'
		  ,'Page Short Title'=>'10p Special'
		  ,'Page Store Title'=>'10p Special'
		  ,'Page Store Subtitle'=>''
		  ,'Page Store Slogan'=>'For that bargarian hunters'
		  ,'Page Store Resume'=>'Sorry only one outer per customer.'
		  ,'Product Presentation Template Data'=>file_get_contents('sites_data/gb_ten_pence.html')
		  ),
	    array(
		  'Page Code'=>'megaclearance'
		    ,'Page Section'=>'incentives'
		  ,'Page Source Template'=>'megaclearance.html'
		  ,'Page Description'=>'Mega Clearance Page'
		  ,'Page Title'=>'10p Special'
		  ,'Page Short Title'=>'MegaClearance'
		  ,'Page Store Title'=>'Mega Clearance'
		  ,'Page Store Subtitle'=>''
		  ,'Page Store Slogan'=>"An offer you can't resists "
		  ,'Page Store Resume'=>'Every week for just a week (or until it is gone) we clear out one product line'
		  ,'Product Presentation Template Data'=>file_get_contents('sites_data/gb_megaclearance.html')
		  ),
	    array(
		  'Page Code'=>'bogof'
		    ,'Page Section'=>'incentives'
		  ,'Page Source Template'=>'bogof.html'
		  ,'Page Description'=>'Bogof Catalogue'
		  ,'Page Title'=>'BOGOF'
		  ,'Page Short Title'=>'BOGOF'
		  ,'Page Store Title'=>'Buy one get one free.'
		  ,'Page Store Subtitle'=>''
		  ,'Page Store Slogan'=>"Say BOGOFF to the credit crunch"
		  ,'Page Store Resume'=>'No-one does a BOGOF quite like us. Offers available while stocks last. Great deals you can pass on to your customers - or simply give your profit margins a bit of a boost.'
		  ,'Product Presentation Template Data'=>file_get_contents('sites_data/gb_bogof.html')
		  ),
	    array(
		  'Page Code'=>'special_deals'
		    ,'Page Section'=>'incentives'
		  ,'Page Source Template'=>'special_deals.html'
		  ,'Page Description'=>'Special Deals Catalogue'
		  ,'Page Title'=>'Special Deals'
		  ,'Page Short Title'=>'Special Deals'
		  ,'Page Store Title'=>'Special Deals'
		   ,'Page Store Subtitle'=>''
		  ,'Page Store Slogan'=>"Special Deals"
		  ,'Page Store Resume'=>'Special Deals'
		  ,'Product Presentation Template Data'=>file_get_contents('sites_data/gb_special_deals.html')
		  ),
	     array(
		  'Page Code'=>'new_products'
		    ,'Page Section'=>'inspiration'
		  ,'Page Source Template'=>'new_products.html'
		  ,'Page Description'=>'New Products excluding WSL'
		  ,'Page Title'=>'New Products'
		  ,'Page Short Title'=>'New Products'
		  ,'Page Store Title'=>'New & Recent Additions'
		  ,'Page Store Subtitle'=>''
		  ,'Page Store Slogan'=>'Recent Additions'
		  ,'Page Store Resume'=>'Recent Additions'
		  ,'Product Presentation Template Data'=>file_get_contents('sites_data/gb_new_products.html')
		  ),
	     array(
		  'Page Code'=>'new_wsl_products'
		   ,'Page Section'=>'inspiration'
		  ,'Page Source Template'=>'new_wsl_products.html'
		  ,'Page Description'=>'New WSL Products'
		  ,'Page Title'=>'New WSL Products'
		  ,'Page Short Title'=>'New WSL Products'
		  ,'Page Store Title'=>'New WSL Additions'
		  ,'Page Store Subtitle'=>''
		  ,'Page Store Slogan'=>'Recent WSL Additions'
		  ,'Page Store Resume'=>'Recent WSL Additions'
		  ,'Product Presentation Template Data'=>file_get_contents('sites_data/gb_new_wsl_products.html')
		  ),
	    array(
		  'Page Code'=>'best_sellers'
		    ,'Page Section'=>'inspiration'
		  ,'Page Source Template'=>'best_sellers.html'
		  ,'Page Description'=>'Best Sellers'
		  ,'Page Title'=>'Best Sellers'
		  ,'Page Short Title'=>'Best Sellers'
		  ,'Page Store Title'=>'Best Sellers'
		  ,'Page Store Subtitle'=>''
		  ,'Page Store Slogan'=>'Current Fast Moving Lines'
		  ,'Page Store Resume'=>'Best Sellers'
		  ,'Product Presentation Template Data'=>file_get_contents('sites_data/gb_best_sellers.html')
		  ),
	       array(
		  'Page Code'=>'newsletter'
		  ,'Page Section'=>'inspiration'
		  ,'Page Source Template'=>'newsletter.html'
		  ,'Page Description'=>'Newsletter'
		  ,'Page Title'=>'Newsletter'
		  ,'Page Short Title'=>'Newsletter'
		  ,'Page Store Title'=>'Newsletter'
		  ,'Page Store Subtitle'=>''
		  ,'Page Store Slogan'=>'Newsletter'
		  ,'Page Store Resume'=>'Newsletter'
		  ),
	    );




$store_data[$store_code]=array(
			      'Slogan'=>'UK Biggest Online Giftware Wholesaler'
			      ,'Resume'=>'Currently we have over 10000 exotic, interesting & unique wholesale product lines spread over approaching 1000 web pages all available to order on-line for delivery next day in the UK (well we do our best)'
			      
			      );



?>