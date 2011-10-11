function change_suppliers_view(e,data){
	var table=tables['table'+data.table_id];
	var tipo=this.id;
	 if(tipo=='suppliers_general')tipo='general';
     else if(tipo=='suppliers_sales')tipo='sales';
    else if(tipo=='suppliers_profit')tipo='profit';

     else if(tipo=='suppliers_stock')tipo='stock';
	 else if(tipo=='suppliers_products')tipo='products';
    else if(tipo=='suppliers_contact')tipo='contact';
	
  			
  			
  			if(tipo=='profit' || tipo=='sales'){
  			Dom.setStyle('suppliers_period_options','display','');
  			}else{
  			Dom.setStyle('suppliers_period_options','display','none');
  			}
	
table.hideColumn('id');
	
	table.hideColumn('name');
	table.hideColumn('contact');
	table.hideColumn('email');
	table.hideColumn('location');
	table.hideColumn('tel');
	table.hideColumn('pending_pos');
	table.hideColumn('for_sale');
	
	table.hideColumn('discontinued');
	table.hideColumn('stock_value');
	table.hideColumn('high');
		table.hideColumn('normal');
table.hideColumn('low');
	table.hideColumn('critical');
	table.hideColumn('outofstock');
	table.hideColumn('sales');
	table.hideColumn('profit');
	table.hideColumn('profit_after_storing');
	table.hideColumn('cost');
	table.hideColumn('margin');

		    table.showColumn('code');

	
	if(tipo=='general'){
	    table.showColumn('name');
	    table.showColumn('location');
	    	    table.showColumn('for_sale');
	    table.showColumn('pending_pos');

	    
	}else if(tipo=='stock'){
	    table.showColumn('high');
	    table.showColumn('normal');
	    table.showColumn('low');
	    table.showColumn('critical');
	    table.showColumn('outofstock');
}else if(tipo=='contact'){
	    table.showColumn('email');
	    table.showColumn('tel');
	    table.showColumn('name');
	    table.showColumn('contact');

	}else if(tipo=='profit'){
	    table.showColumn('profit');
	    table.showColumn('profit_after_storing');
	    table.showColumn('cost');
	    	    table.showColumn('margin');

	}else if(tipo=='sales'){
	    table.showColumn('sales');
	    table.showColumn('sold');
	    table.showColumn('required');
	    table.showColumn('name');

	}else if(tipo=='products'){
	
	    table.showColumn('for_sale');
	    table.showColumn('name');
	    	table.showColumn('discontinued');

	}
	
    Dom.removeClass(['suppliers_general','suppliers_products','suppliers_stock','suppliers_sales','suppliers_contact','suppliers_profit'],'selected')
    Dom.addClass(this,'selected')
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=suppliers-suppliers-view&value=' + escape(tipo),{} );
 
 
 }

function  change_supplier_products_view(e,data){	


	tipo=this.id;
	if(tipo=='supplier_products_general')tipo='general';
    else if(tipo=='supplier_products_sales')tipo='sales';
    else if(tipo=='supplier_products_stock')tipo='stock';
	 else if(tipo=='supplier_products_profit')tipo='profit';
	var table=tables['table'+data.table_id];
	
	
	
	
	
	
	
	
	
	table.hideColumn('description');
	table.hideColumn('used_in');
	table.hideColumn('stock');
	table.hideColumn('weeks_until_out_of_stock');
	table.hideColumn('required');
	table.hideColumn('dispatched');
	table.hideColumn('sold');
	table.hideColumn('sales');
	table.hideColumn('profit');
	table.hideColumn('margin');





	if(tipo=='sales'){
	    table.showColumn('required');
	    table.showColumn('provided');
	   table.showColumn('dispatched');
	table.showColumn('sold');
	table.showColumn('sales');
	    table.showColumn('used_in');
	    
	    Dom.get('supplier_products_period_options').style.display='';
	   // Dom.get('supplier_products_avg_options').style.display='';
	    	  
	}else if(tipo=='general'){
	
	    Dom.get('supplier_products_period_options').style.display='none';
	   // Dom.get('supplier_products_avg_options').style.display='none';
	    table.showColumn('description');
	    	    table.showColumn('used_in');

	}else if(tipo=='stock'){
	
	    table.showColumn('stock');
	    table.showColumn('weeks_until_out_of_stock');
        table.showColumn('used_in');
	    Dom.get('supplier_products_period_options').style.display='none';
	   // Dom.get('supplier_products_avg_options').style.display='none';
	}else if(tipo=='profit'){
	   	    table.showColumn('margin');

	    table.showColumn('profit');
	    table.showColumn('used_in');
	    
	    Dom.get('supplier_products_period_options').style.display='';
	   // Dom.get('supplier_products_avg_options').style.display='';
	    	  
	}


    Dom.removeClass(Dom.getElementsByClassName('option','td' , this.parentNode),'selected')
    Dom.addClass(this,"selected");	
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys='+data.parent+'-supplier_products-view&value=' + escape(tipo) ,{});
	

    }




