<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('common.php');

$store_period_title=array('year'=>_('Last Year'),'quarter'=>_('Last Quarter'),'month'=>_('Last Month'),'week'=>_('Last Week'),'all'=>_('All'));
$title='';

foreach( $store_period_title as $key=>$value){
$title.=sprintf(',%s:"%s"',$key,$value);
}
$title=preg_replace('/^,/','',$title);

?>
var info_period_title={<?php echo $title ?>};
var Dom   = YAHOO.util.Dom;
var Event   = YAHOO.util.Event;

var current_store_period='<?php echo$_SESSION['state']['store']['period']?>';


var category_labels={'sales':'<?php echo _('Sales')?>','profit':'<?php echo _('Profits')?>'};
var period_labels={'m':'<?php echo _('Montly')?>','y':'<?php echo _('Yearly')?>','w':'<?php echo _('Weekly')?>','q':'<?php echo _('Quarterly')?>'};
var pie_period_labels={'m':'<?php echo _('Month')?>','y':'<?php echo _('Year')?>','w':'<?php echo _('Week')?>','q':'<?php echo _('Quarter')?>'};

var plot='<?php echo$_SESSION['state']['department']['plot']?>';


var period='period_<?php echo$_SESSION['state']['department']['period']?>';
var avg='avg_<?php echo$_SESSION['state']['department']['avg']?>';



function show_details(){

Dom.get("department_info").style.display='';
        Dom.get("plot").style.display='';
        Dom.get("no_details_title").style.display='none';
        
    Dom.get("show_details").style.display='none';
        Dom.get("hide_details").style.display='';

    YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=department-details&value=1')
}

function hide_details(){

   Dom.get("department_info").style.display='none';
        Dom.get("plot").style.display='none';
        Dom.get("no_details_title").style.display='';

    Dom.get("show_details").style.display='';
            Dom.get("hide_details").style.display='none';

    //  alert('ar_sessions.php?tipo=update&keys=store-details&value=0')
    YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=department-details&value=0')
}


    var change_view=function(e){

	var table=tables['table0'];
	var tipo=this.id;
	//	alert(table.view+' '+tipo)
	if(table.view!=tipo){
	    table.hideColumn('stock_value');
	     table.hideColumn('stock_error');
	     table.hideColumn('outofstock');
	     table.hideColumn('active'); table.hideColumn('discontinued');
	     table.hideColumn('sales');
	     table.hideColumn('profit');
	     table.hideColumn('todo');

	    if(tipo=='sales'){
		table.showColumn('profit');
		table.showColumn('sales');
			Dom.get('period_options').style.display='';
		Dom.get('avg_options').style.display='';

	    }else if(tipo=='general'){
		table.showColumn('active');
		table.showColumn('todo');	table.showColumn('discontinued');
		Dom.get('period_options').style.display='none';
		Dom.get('avg_options').style.display='none';
	    }else if(tipo=='stock'){
		    table.showColumn('stock_value');
		    table.showColumn('stock_error');
		    table.showColumn('outofstock');
		    	Dom.get('period_options').style.display='';
		Dom.get('avg_options').style.display='none';
	    }
	    

	    Dom.get(table.view).className="";
	    Dom.get(tipo).className="selected";
	    table.view=tipo
		YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=department-view&value=' + escape(tipo) );
	}
    }



YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"code", label:"<?php echo _('Code')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"name", label:"<?php echo _('Name')?>",width:300, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				 ,{key:"codename", label:"<?php echo _('Code/Name')?>",width:200, sortable:true,className:"aleft",className:"aright",<?php echo($_SESSION['state']['department']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				  ,{key:"active", label:"<?php echo _('Products')?>",  width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['department']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"discontinued", label:"<?php echo _('Discontinued')?>",  width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['department']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"todo", label:"<?php echo _('To do')?>",  width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['department']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    
				    ,{key:"sales", label:"<?php echo _('Sales')?>", width:90,sortable:true,className:"aright",<?php echo($_SESSION['state']['department']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit", label:"<?php echo _('Profit')?>", width:90,sortable:true,className:"aright",<?php echo($_SESSION['state']['department']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_value", label:"<?php echo _('Stk Value')?>", width:90,sortable:true,className:"aright",<?php echo($_SESSION['state']['department']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"outofstock", label:"<?php echo _('Out of Stk ')?>", width:90,sortable:true,className:"aright",<?php echo($_SESSION['state']['department']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_error", label:"<?php echo _('Stk Error')?>", width:80,sortable:true,className:"aright",<?php echo($_SESSION['state']['department']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}


				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=families&parent=department");
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    RecordOffset : "resultset.records_offset", 
		       rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		fields: [
			 "code",
			 "name",
			 'active',"stock_error","stock_value","outofstock","sales","profit","todo","discontinued","notforsale","codename"
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilderwithTotals
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage:<?php echo$_SESSION['state']['department']['table']['nr']+1?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									  key: "<?php echo$_SESSION['state']['department']['table']['order']?>",
									  dir: "<?php echo$_SESSION['state']['department']['table']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
       this.table0.doBeforeLoadData=mydoBeforeLoadData;
   this.table0.filter={key:'<?php echo$_SESSION['state']['department']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['department']['table']['f_value']?>'};

	    
	    this.table0.view='<?php echo$_SESSION['state']['department']['view']?>';

		






	};
    });

function change_period(e,table_id){

    tipo=this.id;
    Dom.get(period).className="";
    Dom.get(tipo).className="selected";	
    period=tipo;
    var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];
    var request='&period=' + this.getAttribute('period');
    // alert(request);
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
}
function change_avg(e,table_id){

    //  alert(avg);
    tipo=this.id;
    Dom.get(avg).className="";
    Dom.get(tipo).className="selected";	
    avg=tipo;
    var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];
    var request='&avg=' + this.getAttribute('avg');
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
}



function change_plot_category(category){
    o=Dom.get('plot_'+plot);
    o.setAttribute("category",category);

    change_plot(o);
}
function change_plot_period(period){
    o=Dom.get('plot_'+plot);
    
    o.setAttribute("period",period);

    change_plot(o);
}

function change_info_period(period){
    var patt=new RegExp("^(year|month|all|week|quarter)$");
    if (patt.test(period)==true && current_store_period!=period){
	//alert('info_'+current_store_period)
	//	alert('ar_sessions.php?tipo=update&keys=store-period&value=');
	Dom.get('info_'+current_store_period).style.display='none';
	Dom.get('info_'+period).style.display='';
	current_store_period=period;

	Dom.get('info_title').innerHTML=info_period_title[period];
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=store-period&value='+period);

    }

}

function next_info_period(){
    if(current_store_period=='all')
        change_info_period('week');
    else if(current_store_period=='week')    
        change_info_period('month');
    else if(current_store_period=='month')    
        change_info_period('quarter');
    else if(current_store_period=='quarter')    
        change_info_period('year');        
    else if(current_store_period=='year')    
        change_info_period('all');
}

function previous_info_period(){
    if(current_store_period=='all')
        change_info_period('year');
    else if(current_store_period=='week')    
        change_info_period('all');
    else if(current_store_period=='month')    
        change_info_period('week');
    else if(current_store_period=='quarter')    
        change_info_period('month');        
    else if(current_store_period=='year')    
        change_info_period('quarter');
}



function change_plot(o){
    //  if(!Dom.hasClass(o,'selected')){

	var keys=Dom.get("plot_info").getAttribute("keys");
	

	
	var tipo=o.getAttribute("tipo");
	var category=o.getAttribute("category");
	var period=o.getAttribute("period");


	if(tipo=='pie'){
	    plot='pie';
	
	    var forecast=o.getAttribute("forecast");
	    var date=o.getAttribute("date");

	    

	    Dom.get("the_plot").width="500px";
	    var plot_url='pie.php?tipo=children_share&item=department&period='+period+'&category='+category+'&forecast='+forecast+'&date='+date+'&keys='+keys;
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
	    
	}else if(tipo=='top_families'){
	    plot='top_families';
	    top_children=3;
	    Dom.get("pie_options").style.display='none';
	    var plot_url='plot.php?tipo=department&top_children='+top_children+'&category='+category+'&period='+period+'&keys='+keys;
	    Dom.get("the_plot").width="100%";
	    plot_code=tipo+'_'+category+'_'+period;


	    Dom.get("plot_category").innerHTML=category_labels[category];
	    Dom.get("plot_period").innerHTML=period_labels[period];
	    Dom.get("plot_options").style.display='';

	}else{
	    plot='department';
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





 function init(){
 
 search_scope='products';
     var store_name_oACDS = new YAHOO.util.FunctionDataSource(search_products_in_store);
     store_name_oACDS.queryMatchContains = true;
     var store_name_oAutoComp = new YAHOO.widget.AutoComplete(search_scope+"_search",search_scope+"_search_Container", store_name_oACDS);
     store_name_oAutoComp.minQueryLength = 0; 
     store_name_oAutoComp.queryDelay = 0.15;
     Event.addListener(search_scope+"_search", "keyup",search_events,search_scope)
  Event.addListener(search_scope+"_clean_search", "click",clear_search,search_scope);   
     

  var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
 oAutoComp.minQueryLength = 0; 


 ids=['general','sales','stock'];
 YAHOO.util.Event.addListener(ids, "click",change_view)
 ids=['period_all','period_year','period_quarter','period_month','period_week'];
 YAHOO.util.Event.addListener(ids, "click",change_period,0);
 ids=['avg_totals','avg_month','avg_week',"avg_month_eff","avg_week_eff"];
 YAHOO.util.Event.addListener(ids, "click",change_avg,0);
YAHOO.util.Event.addListener("info_next", "click",next_info_period,0);
YAHOO.util.Event.addListener("info_previous", "click",previous_info_period,0);

 //     YAHOO.util.Event.addListener('show_details', "click",show_details,'department');

 YAHOO.util.Event.addListener('product_submit_search', "click",submit_search,"product");
 YAHOO.util.Event.addListener('product_search', "keydown", submit_search_on_enter,"product");

YAHOO.util.Event.addListener('details', "click",change_details,'store');

 YAHOO.util.Event.addListener('show_percentages', "click",show_percentages,'department');



 }

YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("plot_period_menu", function () {
	 var oMenu = new YAHOO.widget.Menu("plot_period_menu", { context:["plot_period","br", "tr"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("plot_period", "click", oMenu.show, null, oMenu);
    });
YAHOO.util.Event.onContentReady("plot_category_menu", function () {
	 var oMenu = new YAHOO.widget.Menu("plot_category_menu", { context:["plot_category","br", "tr"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("plot_category", "click", oMenu.show, null, oMenu);
    });
YAHOO.util.Event.onContentReady("info_period_menu", function () {
	 var oMenu = new YAHOO.widget.Menu("info_period_menu", { context:["info_period","tr", "br"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("info_period", "click", oMenu.show, null, oMenu);
    });

YAHOO.util.Event.onContentReady("rppmenu0", function () {

	 var oMenu = new YAHOO.widget.Menu("rppmenu0", { context:["rtext_rpp0","tl", "tr"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("rtext_rpp0", "click", oMenu.show, null, oMenu);
    });

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.Menu("filtermenu0", { context:["filter_name0","tr", "br"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("filter_name0", "click", oMenu.show, null, oMenu);
    });