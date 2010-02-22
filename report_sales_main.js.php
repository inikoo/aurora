function change_plot(o){
    //  if(!Dom.hasClass(o,'selected')){

	var keys=Dom.get("plot_info").getAttribute("keys");
	

	
	var tipo=o.getAttribute("tipo");
	var category=o.getAttribute("category");
	var period=o.getAttribute("period");
	//alert(category)

	if(tipo=='pie'){
	    plot='pie';
	
	    var forecast=o.getAttribute("forecast");
	    var date=o.getAttribute("date");

	    

	    Dom.get("the_plot").width="500px";
	    var plot_url='pie.php?tipo=children_share&item=store&period='+period+'&category='+category+'&forecast='+forecast+'&date='+date+'&keys='+keys;
	    //alert(plot_url)
	    plot_code=tipo;
	    Dom.get("pie_options").style.display='';
	    Dom.get("plot_options").style.display='none';

	    old_selected=Dom.getElementsByClassName('selected', 'td', 'pie_period_options');
	    for( var i in old_selected )
		Dom.removeClass(old_selected[i],'selected');
	    Dom.addClass("pie_period_"+period,'selected');
	    old_selected=Dom.getElementsByClassName('selected', 'td', 'pie_category_options');
	    for( var i in old_selected )
		Dom.removeClass(old_selected[i],'selected');
	    Dom.addClass("pie_category_"+category,'selected');
	    
	}else if(tipo=='growth'){
	    plot=tipo;
	    
	    //Dom.get("pie_options").style.display='none';
	    var plot_url='plot.php?tipo=store&category='+category+'&period='+period+'&keys='+keys;
	    Dom.get("the_plot").width="100%";
	    plot_code=tipo+'_'+category+'_'+period;


	    Dom.get("plot_category").innerHTML=category_labels[category];
	    Dom.get("plot_period").innerHTML=period_labels[period];
	    Dom.get("plot_options").style.display='';

	}else{
	     plot='store';
	    Dom.get("pie_options").style.display='none';
	    var plot_url='plot.php?tipo='+tipo+'&category='+category+'&period='+period+'&keys='+keys;
	    Dom.get("the_plot").width="100%";
	    plot_code=tipo+'_'+category+'_'+period;


	    Dom.get("plot_category").innerHTML=category_labels[category];
	    Dom.get("plot_period").innerHTML=period_labels[period];
	    Dom.get("plot_options").style.display='';
	}


	
        Dom.get("the_plot").src=plot_url; 
	old_selected=Dom.getElementsByClassName('selected', 'span', 'plot_chooser');
	for( var i in old_selected ){
	    Dom.removeClass(old_selected[i],'selected');
	}
	Dom.addClass(o,'selected');
	//	alert('ar_sessions.php?tipo=update&keys=store-plot_data-'+tipo+'-category&value='+category)
	
	//YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=store-plot&value='+tipo);
	
	//YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=store-plot_data-'+tipo+'-period&value='+period);
	//YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=store-plot_data-'+tipo+'-category&value='+category);

	    
	    //  }
    
}

function go_free() {

	    var from=Dom.get('v_calpop1').value;
	    var to=Dom.get('v_calpop2').value;
	    location.href='report_sales_main.php?tipo=f&from='+from+'&to='+to; 
	}
	


	


