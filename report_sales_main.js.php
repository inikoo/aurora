var link="report_sales_main.php";
var category_labels={'sales':'<?php echo _('Net Sales')?>','profit':'<?php echo _('Profits')?>'};
var period_labels={'m':'<?php echo _('Montly')?>','y':'<?php echo _('Yearly')?>','w':'<?php echo _('Weekly')?>','q':'<?php echo _('Quarterly')?>'};


function change_currency() {

    var currency=this.getAttribute('currency');
    if (currency=='stores') {
        Dom.setStyle(Dom.getElementsByClassName('currency_corporate','td'),'display','none')
        Dom.setStyle(Dom.getElementsByClassName('currency_stores','td'),'display','')
        this.setAttribute('currency','stores');
        Dom.removeClass(['invoices_corporate_currency_button','profits_corporate_currency_button'],'selected')
        Dom.addClass(['invoices_stores_currency_button','profits_stores_currency_button'],'selected')
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=report_sales-currency&value=stores', {success:function(o) {}});
    } else {
        Dom.setStyle(Dom.getElementsByClassName('currency_corporate','td'),'display','')
        Dom.setStyle(Dom.getElementsByClassName('currency_stores','td'),'display','none')
        this.setAttribute('currency','corporation');
        Dom.addClass(['invoices_corporate_currency_button','profits_corporate_currency_button'],'selected')
        Dom.removeClass(['invoices_stores_currency_button','profits_stores_currency_button'],'selected')
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=report_sales-currency&value=corporation', {success:function(o) {}});
    }
}

function change_view() {

    var view=this.getAttribute('view');
    if (view=='invoices') {
        Dom.setStyle(Dom.get('report_sales_profit'),'display','none')
        Dom.setStyle(Dom.get('report_sales_invoices'),'display','')
       
       
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=report_sales-view&value=invoices', {success:function(o) {}});
    } else {
       Dom.setStyle(Dom.get('report_sales_profit'),'display','')
        Dom.setStyle(Dom.get('report_sales_invoices'),'display','none')
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=report_sales-view&value=profits', {success:function(o) {}});
    }
}

function change_plot(o){
    //  if(!Dom.hasClass(o,'selected')){

	var keys=Dom.get("plot_info").getAttribute("keys");
	var invoice_category_keys=Dom.get("plot_info").getAttribute("invoice_category_keys");


	
	var tipo=o.getAttribute("tipo");
	var category=o.getAttribute("category");
	var period=o.getAttribute("period");


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
	    
	}else if(tipo=='per_category'){
	    plot=tipo;
	    
	    //Dom.get("pie_options").style.display='none';
	    var plot_url='plot.php?tipo=invoice_categories&category='+category+'&period='+period+'&keys='+invoice_category_keys+'&wrapper=store&wrapper_keys='+keys;
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

function init_rep_sales_main(){
 YAHOO.util.Event.addListener(['invoices_corporate_currency_button','invoices_stores_currency_button','profits_corporate_currency_button','profits_stores_currency_button'], "click",change_currency,0);
 YAHOO.util.Event.addListener(['invoices_profits_button','profits_invoices_button'], "click",change_view,0);

}

YAHOO.util.Event.onDOMReady(init_rep_sales_main);


	


