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

 var ids=['plot_all_stores','plot_per_store','plot_per_category'];
  var div_ids=['div_plot_all_stores','div_plot_per_store','div_plot_per_category'];

Dom.removeClass(ids,'selected');
Dom.addClass(o,'selected')
Dom.setStyle(div_ids,'display','none');
Dom.setStyle('div_'+o.id,'display','')
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=report_sales-plot&value='+o.id,{});

	    
	    //  }
    
}

function init_rep_sales_main(){
 YAHOO.util.Event.addListener(['invoices_corporate_currency_button','invoices_stores_currency_button','profits_corporate_currency_button','profits_stores_currency_button'], "click",change_currency,0);
 YAHOO.util.Event.addListener(['invoices_profits_button','profits_invoices_button'], "click",change_view,0);

}

YAHOO.util.Event.onDOMReady(init_rep_sales_main);


	


