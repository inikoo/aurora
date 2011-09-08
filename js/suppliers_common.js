function change_suppliers_view(e,data){
	var table=tables['table'+data.table_id];
	var tipo=this.id;
	 if(tipo=='suppliers_general')tipo='general';
    else if(tipo=='suppliers_sales')tipo='sales';
    else if(tipo=='suppliers_stock')tipo='stock';
	    else if(tipo=='suppliers_products')tipo='products';

	
table.hideColumn('pending_pos');
	table.hideColumn('location');
	table.hideColumn('email');
	table.hideColumn('for_sale');
	table.hideColumn('tobediscontinued');
	table.hideColumn('nosale');
	table.hideColumn('high');
	table.hideColumn('normal');
	table.hideColumn('low');
	table.hideColumn('critical');
	table.hideColumn('outofstock');
	table.hideColumn('profit');
	table.hideColumn('profit_after_storing');
	table.hideColumn('cost');
	if(tipo=='general'){
	    table.showColumn('name');
	    table.showColumn('location');
	    table.showColumn('email');
	}else if(tipo=='stock'){
	    table.showColumn('high');
	    table.showColumn('normal');
	    table.showColumn('low');
	    table.showColumn('critical');
	    table.showColumn('outofstock');


	}else if(tipo=='sales'){
	    table.showColumn('profit');
	    table.showColumn('profit_after_storing');
	    table.showColumn('cost');

	}else if(tipo=='products'){
	    table.showColumn('for_sale');
	    table.showColumn('tobediscontinued');
	    table.showColumn('nosale');
	}
	
    Dom.removeClass(['suppliers_general','suppliers_products','suppliers_stock','suppliers_sales'],'selected')
    Dom.addClass(this,'selected')
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=suppliers-suppliers-view&value=' + escape(tipo),{} );
 
 
 }








function  change_supplier_products_view(e,data){	


	tipo=this.id;
	if(tipo=='supplier_products_general')tipo='general';
    else if(tipo=='supplier_products_sales')tipo='sales';
    else if(tipo=='supplier_products_stock')tipo='stock';
	
	var table=tables['table'+data.table_id];
	
	
	
	
	
	
	
	
	table.hideColumn('required');
	table.hideColumn('provided');

	table.hideColumn('profit');
    table.hideColumn('stock');
	table.hideColumn('stock_until');
	table.hideColumn('description');
	



	if(tipo=='sales'){
	    table.showColumn('required');
	    table.showColumn('provided');
	    table.showColumn('profit');
	    Dom.get('supplier_products_period_options').style.display='';
	    Dom.get('supplier_products_avg_options').style.display='';
	    table.showColumn('smallname');
	}else if(tipo=='general'){
	
	    Dom.get('supplier_products_period_options').style.display='none';
	    Dom.get('supplier_products_avg_options').style.display='none';
	    table.showColumn('description');
	}else if(tipo=='stock'){
	table.showColumn('formated_record_type');
	    table.showColumn('stock');
	    table.showColumn('stock_until');
	    Dom.get('supplier_products_period_options').style.display='none';
	    Dom.get('supplier_products_avg_options').style.display='none';
	}


    Dom.removeClass(Dom.getElementsByClassName('option','td' , this.parentNode),'selected')
    Dom.addClass(this,"selected");	
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys='+data.parent+'-supplier_products-view&value=' + escape(tipo) ,{});
	

    }




