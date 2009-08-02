<?php
if(!isset($_SESSION['tables']) ){
  $_SESSION['tables']=array(	
			    'customers_list'=>array('name','yui-dt-asc','25','0','where contact_id>0 and (num_invoices+num_invoices_nd)>0' ,'cu.name',''),
			    'order_list'=>array('date_index','yui-dt-asc','25','0','where true','public_id',''),
			    'contacts_list'=>array('name','yui-dt-asc','25','0'),
			    'pindex_list'=>array('code','yui-dt-asc','25','0','where true ','p.code',''),
			    'departments_list'=>array('name','yui-dt-desc','25','0','where true','name',''),
			    
			    'families_list'=>array('name','yui-dt-desc',25,0,0,'where true','name',''),
			    'products_list'=>array('code','yui-dt-desc',25,0,0,'where true','code',''),
			    'suppliers_list'=>array('code','yui-dt-asc','25',0,'where true','code',''),
			    'product_withsupplier'=>array('code','yui-dt-asc','25','0',0,'where true','p.code',''),
			    'po_item'=>array('code','yui-dt-asc','25','0',array(0,0),'where true','p.code',''),

			    'order_withcustprod'=>array('date_index','yui-dt-asc','25','0',0,'','customer_name',''),
			    'order_withcust'=>array('date_index','yui-dt-asc','25','0',0,'','customer_name',''),

			    'stock_history'=>array('op_date','yui-dt-asc',25,0,0,'where true ','','','1,1,1,0','',''),

			    'dn_item'=>array(0),
			    
			    'users_list'=>array('handle','yui-dt-asc','25','0'),
			    'groups_list'=>array('id','yui-dt-asc','25','0'),

			    'proinvoice_list'=>array('date_index','yui-dt-asc','25','0','where tipo=1 ','max',''),
			    'dn_list'=>array('date_index','yui-dt-asc','25','0',0,'where tipo=2 ','public_id',''),
			    'po_list'=>array('date_index','yui-dt-asc','25','0',0,'where true ','id',''),

			    'staff_list'=>array('alias','yui-dt-asc','25','0','where true ','alias',''),

			    'order_withprod'=>array('date_index','yui-dt-asc','25','0',0),
			    'transaction_list'=>array('display_order','yui-dt-asc'),

			    );
 }
if(!isset($_SESSION['views'])){
  $_SESSION['views']=array(
			   'departments'=>array('detail'=>false,'view'=>'general'),
			   'edit_products_block'=>'description',
			   'assets'=>'dept',
			   'product_plot'=>'sales_week',
			   'sales_plot'=>'net_sales_month',
			   'product_blocks'=>array(0,1,1,0,1,0),
			   'supplier_blocks'=>array(1,1),
			   'po_item'=>array(0,0,1),
			   'pos_table_options'=>array(1,1,1,1),
			   'stockh_table_options'=>array(1,1,1,1,1),
			   'reports_front'=>'sales',
			   'reports_front_plot'=>array(
						       'stock'=>'plot_month_outofstock',
						       'sales'=>'net_sales_month'
						       ),
			   );
 }



if(!isset($_SESSION['state']))
  $_SESSION['state']=$default_state;
?>