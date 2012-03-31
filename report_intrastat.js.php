<?php
include_once('common.php');


?>

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


YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


  var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"monthyear", label:"<?php echo _('Period')?>", width:60,sortable:false,className:"aleft"}
				    ,{key:"tariff_code", label:"<?php echo _('Comodity')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"value", label:"<?php echo _('Value')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"weight", label:"<?php echo _('Net Mass')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"country_2alpha_code", label:"<?php echo _('Country')?>",width:50,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"invoices", label:"<?php echo _('Invoices')?>", width:400,sortable:false,className:"aleft"}

				     ];

	//alert("ar_sites.php?tipo=pages&parent=site&tableid=0&parent_key="+Dom.get('site_key').value);
		request="ar_reports.php?tipo=intrastat&tableid=0";
	    this.dataSource0 = new YAHOO.util.DataSource(request);
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: { 
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		fields: ['monthyear','tariff_code','value','weight','country_2alpha_code','invoices'
						 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder_page_thumbnails
								       ,paginator : new YAHOO.widget.Paginator({
								        
									      rowsPerPage:<?php echo$_SESSION['state']['report_intrastat']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['report_intrastat']['order']?>",
									 dir: "<?php echo$_SESSION['state']['report_intrastat']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
   this.table0.request=request;
  this.table0.table_id=tableid;
     this.table0.subscribe("renderEvent", myrenderEvent);


	    
	    this.table0.filter={key:'<?php echo$_SESSION['state']['report_intrastat']['f_field']?>',value:'<?php echo$_SESSION['state']['report_intrastat']['f_value']?>'};
		





	};
	
    });

function init_rep_sales_main(){

 YAHOO.util.Event.addListener(['invoices_corporate_currency_button','invoices_stores_currency_button','profits_corporate_currency_button','profits_stores_currency_button'], "click",change_currency,0);
 YAHOO.util.Event.addListener(['invoices_profits_button','profits_invoices_button'], "click",change_view,0);



}

YAHOO.util.Event.onDOMReady(init_rep_sales_main);


	


