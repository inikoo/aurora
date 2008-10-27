<?

// Array configuratin the Autentication 
$LU_conf = array(
		
		'session'           => array('name' => 'PHPSESSID','varname' => 'loginInfo'),
		'logout'            => array('destroy'  => true),
		'cookie'            => array(
					     'name' => 'loginInfo',
					     'path' => '/',
					     'domain' => '',
					     'secure' => false,
					     'lifetime' => 1,
					     'secret' => 'jfeq49rm49',
					     'savedir' => './server_files/ck/',
					     ),
		'authContainers'    => array(
					     'DB' => array(
							   'type'          => 'MDB2',
							   'expireTime'   => 43200,
							   'idleTime'     => 3600,
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
	      'name'=>'Ancient Wisdom',
	      'sname'=>'AW',
	      'country'=>'GB',
	      'lang'=>'en',
	      'country_id'=>30,
	      'home_id'=>30,
	      'extended_home_id'=>array(30,241,240,242),
	      'region_id'=>array(75,30,241,240,242),
	      'org_id'=>array(80,21,33,208,108,201,228,193,165,177,104,216,75,78,110,116,117,126,2,162,160,169,188,189,47,171,30),
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
	      'supplier_id_prefix'=>'S',
	      'po_id_prefix'=>'PO',
	      'customer_id_prefix'=>'C',
	      'order_id_prefix'=>'',
	      'data_since'=>'2004-06-14',
	      'product_code_separator'=>'-'
	      );

$default_state=array(
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
						     'order'=>'date_index',
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
				      'view'=>'general',
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
					
						  'locations'=>array(
								 'order'=>'name',
								 'order_dir'=>'',
								 'sf'=>0,
								 'nr'=>25,
								 'where'=>'where true',
								 'f_field'=>'name',
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
						   'f_field'=>'name',
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
						       'order'=>'date_index',
						       'order_dir'=>'desc',
						       'sf'=>0,
						       'nr'=>10,
						       'where'=>'where true',
						       'f_field'=>'description',
						       'f_value'=>'',
						       'from'=>'',
						       'to'=>'',
						       'elements'=>array('orden'=>1,'h_cust'=>1,'h_cont'=>1,'note'=>1)
						   )
				    ),


		      'suppliers'=>array(
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
				    'staff'=>array(
						   'order'=>'alias',
						   'order_dir'=>'',
						   'sf'=>0,
						   'nr'=>50,
						   'where'=>'where active=1',
						   'f_field'=>'alias',
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
					  'view'=>'general',
					  'table'=>array(
							 'order'=>'name',
							 'order_dir'=>'',
							 'sf'=>0,
							 'nr'=>200,
							 )
					  ),

		     
		     'department'=>array(
				       'details'=>false,
				       'view'=>'general',
				       'id'=>1,
				       'table'=>array(
						      'order'=>'name',
						      'order_dir'=>'',
						      'sf'=>0,
						      'nr'=>200,
						      'where'=>'where true',
						      'f_field'=>'id',
						      'f_value'=>''

						      )
					 ),
		     'family'=>array(
				       'details'=>false,
				       'view'=>'general',
				       'id'=>1,
				       'table'=>array(
						      'order'=>'code',
						      'order_dir'=>'',
						      'sf'=>0,
						      'nr'=>200,
						      'where'=>'where true',
						      'f_field'=>'id',
						      'f_value'=>''

						      )
				     ),
		      'product'=>array(
				       'details'=>false,
				       'plot'=>'sales_month',
				       'id'=>1,
				       'display'=>array('details'=>0,'plot'=>1,'orders'=>0,'customers'=>0,'stock_history'=>0),
				       'plot'=>'',
				       'plot_options'=>array('weeks'=>'','from'=>'','to'=>'','months'=>''),
				       'orders'=>array(
						       'order'=>'date_index',
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
							      'order'=>'op_date',
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
		      'supplier'=>array(
				       'details'=>false,
				       'plot'=>'sales_month',
				       'id'=>1,
				       'display'=>array('details'=>0,'plot'=>1,'products'=>0,'po'=>0,'dn'=>0),
				       'plot_options'=>array('weeks'=>'','from'=>'','to'=>'','months'=>''),
				       'products'=>array(
						       'order'=>'code',
						       'order_dir'=>'',
						       'sf'=>0,
						       'nr'=>15,
						       'where'=>'where true',
						       'f_field'=>'id',
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
						   'f_field'=>'id',
							  'f_value'=>'',
						   
						   ),
				       'dn'=>array(
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