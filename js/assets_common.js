
   function  change_product_view(e,data){	
	tipo=this.id;
	
	
	
	if(tipo=='product_general')tipo='general';
    else if(tipo=='product_sales')tipo='sales';
    else if(tipo=='product_stock')tipo='stock';
	 else if(tipo=='product_parts')tipo='parts';
	else if(tipo=='product_categories')tipo='categories';

	var table=tables['table'+data.table_id];
	table.hideColumn('smallname');
	table.hideColumn('name');
	table.hideColumn('stock');
	table.hideColumn('stock_value');
	table.hideColumn('sales');
	table.hideColumn('profit');
	table.hideColumn('sold');
	table.hideColumn('margin');
	table.hideColumn('state');
	table.hideColumn('web');
	table.hideColumn('parts');
	table.hideColumn('supplied');
	table.hideColumn('gmroi');
	table.hideColumn('formated_record_type');
	table.hideColumn('family');
	table.hideColumn('dept');
	table.hideColumn('expcode');  



	if(tipo=='sales'){
	    table.showColumn('sold');
	    table.showColumn('sales');
	    table.showColumn('profit');
	    table.showColumn('margin');
	    Dom.get('product_period_options').style.display='';
	    Dom.get('product_avg_options').style.display='';
	    table.showColumn('smallname');
	}else if(tipo=='general'){
	    table.showColumn('name');
	    table.showColumn('web');
	    table.showColumn('stock');
	    	table.showColumn('formated_record_type');
	    Dom.get('product_period_options').style.display='none';
	    Dom.get('product_avg_options').style.display='none';
	    table.showColumn('gmroi');
	}else if(tipo=='stock'){
	table.showColumn('formated_record_type');
	    table.showColumn('stock');
	    table.showColumn('stock_value');
	    table.showColumn('smallname');
	    Dom.get('product_period_options').style.display='none';
	    Dom.get('product_avg_options').style.display='none';
	}else if(tipo=='parts'){
	    table.showColumn('parts');
	    table.showColumn('supplied');
	    table.showColumn('gmroi');
	    Dom.get('product_period_options').style.display='none';
	    Dom.get('product_avg_options').style.display='none';
	    
	}else if(tipo=='cats'){
	    Dom.get('product_period_options').style.display='none';
	    Dom.get('product_avg_options').style.display='none';
	    table.showColumn('family');
	    table.showColumn('dept');
	    table.showColumn('expcode');
	}



    Dom.removeClass(Dom.getElementsByClassName('table_option','button' , this.parentNode),'selected')
    Dom.addClass(this,"selected");	
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys='+data.parent+'-products-view&value=' + escape(tipo) ,{});
	

    }



function  change_family_view(e,data){

    var table=tables['table'+data.table_id];
	var tipo=this.id;
    if(tipo=='family_general')tipo='general';
    else if(tipo=='family_sales')tipo='sales';
    else if(tipo=='family_stock')tipo='stock';

  
	table.hideColumn('stock_value');
	table.hideColumn('stock_error');
	table.hideColumn('outofstock');
	table.hideColumn('active');
	table.hideColumn('sales');
	table.hideColumn('profit');
	     table.hideColumn('surplus');
	     table.hideColumn('optimal');
	     table.hideColumn('low');
	     table.hideColumn('critcal');
	     table.hideColumn('name');

	    if(tipo=='sales'){
		table.showColumn('profit');
		table.showColumn('sales');
		table.showColumn('name');

		Dom.get('family_'+'period_options').style.display='';
		Dom.get('family_'+'avg_options').style.display='';

	    }else if(tipo=='general'){
	  
		table.showColumn('name');
		  table.showColumn('active');
		  
		  Dom.get('family_period_options').style.display='none';
		Dom.get('family_avg_options').style.display='none';
	    }else if(tipo=='stock'){
		    table.showColumn('stock_error');
		    table.showColumn('outofstock');
		    table.showColumn('surplus');
		    table.showColumn('optimal');
		    table.showColumn('low');
		    table.showColumn('critcal');

		    Dom.get('family_'+'period_options').style.display='none';


		Dom.get('family_'+'avg_options').style.display='none';
	    }
	   

    Dom.removeClass(Dom.getElementsByClassName('table_option','button' , this.parentNode),'selected')
    Dom.addClass(this,"selected");	
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys='+data.parent+'-families-view&value=' + escape(tipo) ,{});
  
	
  }




function change_department_view(e,data){
    
     var table=tables['table'+data.table_id];
    var tipo=this.id;
    if(tipo=='department_general')tipo='general';
    else if(tipo=='department_sales')tipo='sales';
    else if(tipo=='department_stock')tipo='stock';
    
 
    
        table.hideColumn('awp_p');

    table.hideColumn('awp_p');
    table.hideColumn('aws_p');
    table.hideColumn('active');
	table.hideColumn('todo');
	table.hideColumn('discontinued');
	
	table.hideColumn('families');
	table.hideColumn('sales');
	table.hideColumn('profit');
	//    table.hideColumn('stock_value');
	table.hideColumn('stock_error');
	table.hideColumn('outofstock');
	table.hideColumn('surplus');
	table.hideColumn('optimal');
	table.hideColumn('low');
	table.hideColumn('critical');
		table.hideColumn('delta_sales');

	if(tipo=='sales'){
	    Dom.get('department_period_options').style.display='';
	    Dom.get('department_avg_options').style.display='';
	    //table.showColumn('awp_p');
        //table.showColumn('aws_p');
	    table.showColumn('sales');
	     table.showColumn('delta_sales');
	    table.showColumn('profit');
	}
	if(tipo=='general'){
	    Dom.get('department_period_options').style.display='none';
	    Dom.get('department_avg_options').style.display='none';
	    table.showColumn('active');
	    table.showColumn('todo');
	    table.showColumn('families');
	    table.showColumn('discontinued');
	    
	}
	if(tipo=='stock'){
	    Dom.get('department_period_options').style.display='none';
	    Dom.get('department_avg_options').style.display='none';
	    
	    table.showColumn('surplus');
	    table.showColumn('optimal');
	    table.showColumn('low');
	    table.showColumn('critical');
	    table.showColumn('stock_error');
	    table.showColumn('outofstock');
	}

   Dom.removeClass(Dom.getElementsByClassName('table_option','button' , this.parentNode),'selected')
    Dom.addClass(this,"selected");	
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys='+data.parent+'-departments-view&value=' + escape(tipo) ,{success:function(o) {}});
  
}






