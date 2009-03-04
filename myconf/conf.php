<?

// Array configuratin the Autentication 
$LU_conf = array(
		
		'session'           => array('name' => 'PHPSESSID','varname' => 'loginInfo'),
		'logout'            => array('destroy'  => false),
		'cookie'            => array(
					     'name' => 'loginInfo',
					     'path' => '/',
					     'domain' => '',
					     'secure' => false,
					     'lifetime' => 30,
					     'secret' => 'jfeb65c5bi6ihc56iq49rm49',
					     'savedir' => 'server_files/ck',
					     ),
		'authContainers'    => array(
					     'DB' => array(
							   'type'          => 'MDB2',
							   'expireTime'   => 43200,
							   'idleTime'     => 43200,
							   'allowDuplicateHandles' => 0,
							   'allowEmptyPasswords'   => 0,     // 0=false, 1=true
							   'passwordEncryptionMode' => 'plain',
							   'storage' => array(
									      'dbc' =>&$db,
									      'alias' => array(
											       'auth_user_id' =>'authuserid',
											       'is_active' => 'isactive',
											       'name' => 'name',
											       'surname' => 'surname',
											       'email' => 'email',
											       'tipo' => 'tipo',
											       'lang' => 'lang_id',
											       'lastlogin' => 'lastlogin',
							  
											       ),
									      'fields' => array(
												'name' => 'text',
												'surname' => 'text',
												'email' => 'text',
												'tipo' => 'text',
												'is_active' => 'boolean',
												'lang' => 'text',
												'lastlogin' => 'timestamp'
												),
									      'tables' => array(
												'users' => array(
														 'fields' => array(
																   'name' => false,
																   'surname' => false,
																   'email' => false,
																   'tipo' => false,
																   'is_active' => false,
																   'lang' => false,
																   'lastlogin' => false,
																   ),
														 ),
												),
									      )
							   )
					     ),
		'permContainer' => array(
					 'type'  => 'Complex',
					 'storage' => array(
							    'MDB2' => array(
									    //'dsn' => $dsn,
									    'dbc' =>&$db,
									    'prefix' => 'liveuser_',
									    'alias' => array(),
									    'tables' => array(),
									    'fields' => array(),
									    ),
							    ),
					 ),
		);







$myconf=array(
	      'data_from'=>"2003-06-01 09:00:00",
	      'order_id_type'=>'Order Header Numeric ID',
	      
	      'max_session_time'=>36000,
	      'name'=>'Ancient Wisdom',
	      'sname'=>'AW',
	      'country'=>'GB',
	      'country_code'=>'GBR',
	      'lang'=>'en',
	      'country_id'=>30,
	      'home_id'=>30,
	      'extended_home_id'=>array(30,241,240,242),
	      'region_id'=>array(75,30,241,240,242),
	      'org_id'=>array(80,21,33,208,108,201,228,193,165,177,104,216,75,78,110,116,117,126,2,162,160,169,188,189,47,171,30),
	      'tax_obligatory'=>array(30),
	      
	      'tax_conditional0'=>array(80,21,33,208,108,201,228,193,165,177,104,216,75,78,110,116,117,126,2,162,160,169,188,189,47,171),

	      'continent_id'=>array(228,110,116,242,241,240,30,75,33,188,135,169,208,215,216,226,162,221,70,171,193,149,53,76,201,181,243,189,160,78,47,86,104,105,27,121,126,7,224,4,58,21,136,2,80,117,177,115,165,196),
	      'home'=>'United Kingdom',
	      '_home'=>'UK',

	      's_extended_home'=>'UK,CI&IM',
	      'extended_home'=>'United Kigndom, Channel Islands & Isle of Man',

	      
	      's_extended_home_nohome'=>'CI&IM',
	      'extended_home_nohome'=>'Channel Islands & Isle of Man',
	      'region'=>'British Isles',
	      'org'=>'European Union',
	      's_org'=>'EU',
	      'continent'=>'Europe',
	      'outside'=>'Ouside Europe',
	      's_outside'=>'Rest',
	      'encoding'=>'UTF-8',
	      'currency_symbol'=>'£',
	      'currency'=>'Sterling',
	      'decimal_point'=>'.',
	      'thosusand_sep'=>',',
	      
	      'theme'=>'yui-skin-sam',
	      'template_dir'=>'templates',
	      'compile_dir'=> 'server_files/smarty/templates_c',
	      'cache_dir' => 'server_files/smarty/cache',
	      'config_dir' => 'server_files/smarty/configs',
	      'images_dir' => 'server_files/images/',
	      'yui_version'=>'2.6.0',
	      'staff_prefix'=>'SF',
	      'supplier_id_prefix'=>'S',
	      'po_id_prefix'=>'PO',
	      'customer_id_prefix'=>'C',
	      'order_id_prefix'=>'',
	      'data_since'=>'14-06-2004',
	      'product_code_separator'=>'-',
	      'unknown_company'=>'Unknown Company',
	      'unknown_contact'=>'Unknown Contact',
	      'unknown_customer'=>'Unknown Customer',
	      'unknown_supplier'=>'Unknown Supplier',
	      'unknown_informal_greting'=>'Hello',
	      'unknown_formal_greting'=>'Dear Sir or Madam'
	      );

$default_state=array(
		     'order'=>array(
				    'id'=>''
				    
				    ),
		     
		     
		     'reports'=>array(
				      'view'=>'sales',
				      'sales'=>array(
						     'plot'=>'total_sales_month'
						     ),
				      'stock'=>array(
						     'plot'=>'total_outofstock_month'
						     )


				      ),
		     
		      'orders'=>array(
				      'details'=>false,
				      'view'=>'all',
				      'from'=>'',
				      'to'=>'',	  
				      'table'=>array(
						     'order'=>'last_date',
						     'order_dir'=>'',
						     'sf'=>0,
						     'nr'=>25,
						     'where'=>'where true',
						     'f_field'=>'customer_name',
						     'f_value'=>'',
						     'from'=>'',
						     'to'=>'',
						     'elements'=>array()
						   )
				    ),
		     'products'=>array(
				       'details'=>false,
				       'percentages'=>false,
				       'view'=>'general',
				       'from'=>'',
				       'to'=>'',
				       'period'=>'year',
				       'percentage'=>0,
				       'mode'=>'all',
				       'avg'=>'totals',
				       'table'=>array(
						      'order'=>'code',
						      'order_dir'=>'',
						      'sf'=>0,
						      'nr'=>25,
						      'where'=>'where true',
						      'f_field'=>'code',
						      'f_value'=>'',
						      'from'=>'',
						      'to'=>'',
						      'elements'=>array()
						      )
				       ),
		      'supplier_products'=>array(
				       'details'=>false,
				       'percentages'=>false,
				       'view'=>'general',
				       'from'=>'',
				       'to'=>'',
				       'period'=>'year',
				       'percentage'=>0,
				       'mode'=>'all',
				       'avg'=>'totals',
				       'table'=>array(
						      'order'=>'code',
						      'order_dir'=>'',
						      'sf'=>0,
						      'nr'=>25,
						      'where'=>'where true',
						      'f_field'=>'sup_code',
						      'f_value'=>'',
						      'from'=>'',
						      'to'=>'',
						      'elements'=>array()
						      )
				       ),


		     'report_outofstock'=>array(
						'from'=>'',
						'to'=>'',	  
						'table'=>array(
							       'order'=>'code',
							       'order_dir'=>'',
								 'sf'=>0,
								 'nr'=>25,
								 'where'=>'where true',
								 'f_field'=>'code',
								 'f_value'=>'',
								 'elements'=>array()
						   )
				    ),
		     'warehouse'=>array(
					'id'=>1,
					'locations'=>array(
								 'order'=>'code',
								 'order_dir'=>'',
								 'sf'=>0,
								 'nr'=>25,
								 'where'=>'where true',
								 'f_field'=>'code',
								 'f_value'=>'',
								 'elements'=>array()
						   )
				    ),
		     'customers'=>array(
					'view'=>'general',
					'details'=>0,
					'table'=>array(
						   'order'=>'name',
						   'order_dir'=>'',
						   'sf'=>0,
						   'nr'=>25,
						   'where'=>'where true',
						   'f_field'=>'customer name',
						   'f_value'=>''
						       ),
					'advanced_search'=>array(
						   'order'=>'name',
						   'order_dir'=>'',
						   'sf'=>0,
						   'nr'=>25,
						   'where'=>'',
						   'f_field'=>'',
						   'f_value'=>''
						   )


				    ),
		      'customer'=>array(
					'id'=>1,
				       
					'table'=>array(
						       'order'=>'date',
						       'order_dir'=>'desc',
						       'sf'=>0,
						       'nr'=>10,
						       'where'=>'where true',
						       'f_field'=>'notes',
						       'f_value'=>'',
						       'from'=>'',
						       'to'=>'',
						       'details'=>0,
						       'elements'=>array('orden'=>1,'h_cust'=>1,'h_cont'=>1,'note'=>1)
						   )
				    ),


		      'suppliers'=>array(
					 'details'=>false,
					 'view'=>'general',
					 
					 'table'=>array(
						   'order'=>'name',
						   'order_dir'=>'',
						   'sf'=>0,
						   'nr'=>25,
						   'where'=>'where true',
						   'f_field'=>'name',
						   'f_value'=>''
							)
				    ),
		     'hr'=>array(
				 'view'=>'staff',
				 'staff'=>array(
						'order'=>'name',
						'order_dir'=>'',
						'sf'=>0,
						'nr'=>50,
						'where'=>'where true',
						'f_field'=>'name',
						   'f_value'=>''
						)
				 ),
		     'users'=>array(
				    'user_list'=>array(
						       'order'=>'handle',
						       'order_dir'=>'',
						       'sf'=>0,
						       'nr'=>50,
						       'where'=>'where true',
						       'f_field'=>'name',
						       'f_value'=>''
						       ),
				    'groups'=>array(
						    'order'=>'name',
						    'order_dir'=>'',
						    'sf'=>0,
						    'nr'=>50,
						       'where'=>'where true',
						    'f_field'=>'name',
						    'f_value'=>''
						    ),
				    
				    ),

		     'departments'=>array(
					  'details'=>false,
					  'percentages'=>true,
					  'view'=>'general',
					  'period'=>'year',
					  'percentage'=>0,
					  'mode'=>'all',
					  'avg'=>'totals',
					  'edit'=>false,
					  'table'=>array(
							 'where'=>'where true',
							 'f_field'=>'code',
							 'f_value'=>'',
							 'order'=>'name',
							 'order_dir'=>'',
							 'sf'=>0,
							 'nr'=>25,
							 )
					  ),

		     
		     'department'=>array(
				       'details'=>false,
				       'percentages'=>true,
				       'view'=>'general',
				       'id'=>1,
				       'period'=>'year',
				       'percentage'=>0,
				       'mode'=>'all',
				       'avg'=>'totals',
				       'table'=>array(
						      'order'=>'code',
						      'order_dir'=>'',
						      'sf'=>0,
						      'nr'=>200,
						      'where'=>'where true',
						      'f_field'=>'code',
						      'f_value'=>''

						      )
					 ),
		     'family'=>array(
				       'details'=>false,
				       'percentages'=>false,
				       'view'=>'general',
				       'id'=>1,
				       'period'=>'year',
				       'percentage'=>0,
				       'mode'=>'all',
				       'avg'=>'totals',
				       'table'=>array(
						      'order'=>'code',
						      'order_dir'=>'',
						      'sf'=>0,
						      'nr'=>20,
						      'where'=>'where true',
						      'f_field'=>'id',
						      'f_value'=>''

						      )
				     ),
		     
		     
		     'parts'=>array(
				       'details'=>false,
				       'view'=>'general',
				       'period'=>'year',
				       'percentage'=>0,
				       'mode'=>'all',
				       'avg'=>'totals',
				       'table'=>array(
						      'order'=>'sku',
						      'order_dir'=>'',
						      'sf'=>0,
						      'nr'=>20,
						      'where'=>'where true',
						      'f_field'=>'used_in',
						      'f_value'=>''

						      )
				     ),

		     'families'=>array(
				       'details'=>false,
				       'view'=>'general',
				       'percentages'=>true,
				       'period'=>'year',
				       'percentage'=>0,
				       'mode'=>'all',
				       'avg'=>'totals',
				       'table'=>array(
						      'order'=>'code',
						      'order_dir'=>'',
						      'sf'=>0,
						      'nr'=>20,
						      'where'=>'where true',
						      'f_field'=>'code',
						      'f_value'=>''

						      )
				     ),

		      'product'=>array(
				       'details'=>false,
				       'plot'=>'product_week_outers',
				       'plot_data'=>array(
							  'months'=>12,
							  'max_sigma'=>false
							  
							  ),
				       'id'=>1,
				       'edit'=>'description',
				       'display'=>array('details'=>0,'plot'=>1,'orders'=>0,'customers'=>0,'stock_history'=>0),

				       'orders'=>array(
						       'order'=>'last_date',
						       'order_dir'=>'',
						       'sf'=>0,
						       'nr'=>15,
						       'where'=>'where true',
						       'f_field'=>'id',
						       'f_value'=>'',
						       'from'=>'',
						       'to'=>''
						       ),
				       'customers'=>array(
							  'order'=>'customer_name',
							  'order_dir'=>'',
							  'sf'=>0,
							  'nr'=>15,
							  'where'=>'where true',
							  'f_field'=>'id',
							  'f_value'=>''
							  ),
				       'stock_history'=>array(
							      'order'=>'date',
							      'order_dir'=>'',
							      'sf'=>0,
							      'nr'=>15,
							      'where'=>'where true',
							      'f_field'=>'id',
							      'f_value'=>'',
							      'from'=>'',
							      'to'=>'',
							      'elements'=>array()
							      )
				       ),
		     'part'=>array(
				       'details'=>false,
				       'plot'=>'part_stock_history',
				       'plot_data'=>array(
							  'months'=>12,
							  'max_sigma'=>false
							  
							  ),
				       'id'=>1,
				       'edit'=>'description',
				       'display'=>array('details'=>0,'plot'=>1,'orders'=>0,'customers'=>0,'stock_history'=>0),

				       'stock_history'=>array(
							      'order'=>'date',
							      'order_dir'=>'',
							      'sf'=>0,
							      'nr'=>15,
							      'where'=>'where true',
							      'f_field'=>'id',
							      'f_value'=>'',
							      'from'=>'',
							      'to'=>'',
							      'elements'=>array()
							      )
				       ),


		     'po'=>array(
				 'id'=>'',
				 'new'=>'',
				 'new_data'=>array('num_items'=>0,'name'=>'','total'=>0),

				 'new_timestamp'=>'',
				 'items'=>array(
						'order'=>'code',
						'order_dir'=>'',
						'sf'=>0,
						'nr'=>25,
						'where'=>'where true',
						'f_field'=>'p.code',
						'f_value'=>'',
						'all_products'=>false,
						'all_products_supplier'=>false
						),
				 ),
		     'location'=>array(
				       'location'=>false,
				       'parts'=>array(
							 'order'=>'sku',
							 'order_dir'=>'',
							 'sf'=>0,
							 'nr'=>25,
							 'where'=>'where true',
							 'f_field'=>'sku',
							 'f_value'=>'',
							 'elements'=>array()
							 ),
				         'stock_history'=>array(
							 'order'=>'date',
							 'order_dir'=>'desc',
							 'sf'=>0,
							 'nr'=>25,
							 'from'=>'',
							 'to'=>'',
							 'where'=>'where true',
							 'f_field'=>'author',
							 'f_value'=>'',
							 'elements'=>array()
							 )
				       ),
		     'report'=>array(
				     'sales'=>array(
						    'from'=>'',
						    'to'=>'',
						    'period'=>'',
						    'order'=>'date_index',
						    'order_dir'=>'desc',
						    'sf'=>0,
						    'nr'=>25,
						    ),
				     'pickers'=>array(
						 'order'=>'units',
						 'order_dir'=>'',
						 'sf'=>0,
						 'nr'=>10000,
						 'from'=>'',
						 'to'=>'',
						 'where'=>'where true',
						 'f_field'=>'',
						 'f_value'=>'',
						 'elements'=>array()
						 
						      ),
				     'packers'=>array(
						 'order'=>'tipo',
						 'order_dir'=>'',
						 'sf'=>0,
						 'nr'=>10000,
						 'from'=>'',
						 'to'=>'',
						 'where'=>'where true',
						 'f_field'=>'',
						 'f_value'=>'',
						 'elements'=>array()
						 
						 )

				     ),

		     'supplier'=>array(
				       'details'=>false,
				       'plot'=>'sales_month',
				       'id'=>1,
				       'display'=>array('details'=>0,'history'=>0,'products'=>1,'po'=>0),
				       'plot_options'=>array('weeks'=>'','from'=>'','to'=>'','months'=>''),
				       'products'=>array(
							 'view'=>'product_general',
							 'percentage'=>0,
							 'period'=>'year',
							 'order'=>'code',
							 'order_dir'=>'',
							 'sf'=>0,
							 'nr'=>15,
							 'where'=>'where true',
							 'f_field'=>'p.code',
							 'f_value'=>'',
							 'from'=>'',
							 'to'=>'',
							 ),
				       'po'=>array(
						   'order'=>'date_index',
						   'order_dir'=>'desc',
						   'sf'=>0,
						   'nr'=>15,
						   'where'=>'where true',
						   'f_field'=>'max',
						   'f_value'=>'',
						   'to'=>'',
						   'from'=>'',
						   'view'=>'all'
						   
						   ),
				       'history'=>array(
						   'order'=>'date_index',
						   'order_dir'=>'desc',
						   'sf'=>0,
						   'nr'=>15,
						   'where'=>'where true',
						   'f_field'=>'id',
						   'f_value'=>'',
						   'from'=>'',
						   'to'=>'',
						   'elements'=>array()
						   )
					)

		      
		     );



$yui_path="external_libs/yui/".$myconf['yui_version']."/build/";


$customers_ids[0]=_('Id');
$customers_ids[1]=_('Act Id');
$customers_ids[2]=_('Post Code');


  ?>