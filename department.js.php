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

var current_store_period='<?php echo$_SESSION['state']['store']['departments']['period']?>';

var dialog_change_families_display;
var dialog_change_products_display;

function change_block(){
ids=['details','families','products','categories','deals','web'];
block_ids=['block_details','block_families','block_products','block_categories','block_deals','block_web'];

Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=department-block_view&value='+this.id ,{});
}

function change_display_mode(parent,name,label){
    if(name=='percentage'){
		var request='&percentages=1';
    }if(name=='value'){
		var request='&percentages=0&show_default_currency=0';
    }if(name=='value_default_d2d'){
		var request='&percentages=0&show_default_currency=1';
    }

    Dom.get('change_'+parent+'_display_mode').innerHTML=label;
   
   if(parent=='products'){
   var table=tables['table1'];
    var datasource=tables.dataSource1;
    dialog_change_products_display.hide();

    }else if(parent=='families'){
      var table=tables['table0'];
    var datasource=tables.dataSource0;
    dialog_change_families_display.hide();

    }else{
    return;
    }
    
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);   

}

function change_elements(){

ids=['elements_discontinued','elements_nosale','elements_private','elements_sale','elements_historic'];


if(Dom.hasClass(this,'selected')){

var number_selected_elements=0;
for(i in ids){
if(Dom.hasClass(ids[i],'selected')){
number_selected_elements++;
}
}

if(number_selected_elements>1){
Dom.removeClass(this,'selected')

}

}else{
Dom.addClass(this,'selected')

}

table_id=1;
 var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];
var request='';
for(i in ids){
if(Dom.hasClass(ids[i],'selected')){
request=request+'&'+ids[i]+'=1'
}else{
request=request+'&'+ids[i]+'=0'

}
}
  
 // alert(request)
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       


}

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


   


YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"code", label:"<?php echo _('Code')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"name", label:"<?php echo _('Name')?>",width:300, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				 ,{key:"codename", label:"<?php echo _('Code/Name')?>",width:200, sortable:true,className:"aleft",className:"aright",<?php echo($_SESSION['state']['department']['families']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				  ,{key:"active", label:"<?php echo _('Products')?>",  width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['department']['families']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"discontinued", label:"<?php echo _('Discontinued')?>",  width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['department']['families']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"todo", label:"<?php echo _('To do')?>",  width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['department']['families']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    
				    ,{key:"sales", label:"<?php echo _('Sales')?>", width:90,sortable:true,className:"aright",<?php echo($_SESSION['state']['department']['families']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit", label:"<?php echo _('Profit')?>", width:90,sortable:true,className:"aright",<?php echo($_SESSION['state']['department']['families']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_value", label:"<?php echo _('Stk Value')?>", width:90,sortable:true,className:"aright",<?php echo($_SESSION['state']['department']['families']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"outofstock", label:"<?php echo _('Out of Stk ')?>", width:90,sortable:true,className:"aright",<?php echo($_SESSION['state']['department']['families']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_error", label:"<?php echo _('Stk Error')?>", width:80,sortable:true,className:"aright",<?php echo($_SESSION['state']['department']['families']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}


				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=families&parent=department");
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
									       rowsPerPage:<?php echo $_SESSION['state']['department']['families']['nr']+1?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['department']['families']['order']?>",
									  dir: "<?php echo$_SESSION['state']['department']['families']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
       this.table0.doBeforeLoadData=mydoBeforeLoadData;
   this.table0.filter={key:'<?php echo$_SESSION['state']['department']['families']['f_field']?>',value:'<?php echo$_SESSION['state']['department']['families']['f_value']?>'};

	    
	    this.table0.view='<?php echo$_SESSION['state']['department']['families']['view']?>';

		this.table0.table_id=tableid;
     this.table0.subscribe("renderEvent", myrenderEvent);



	    var tableid=1;
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [ 
			  //    {key:"code", label:"<?php echo _('Code')?>", width:90,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			    //  ,{key:"name", label:"<?php echo _('Name')?>",width:400,<?php echo($_SESSION['state']['department']['products']['view']!='general'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			    //  ,{key:"smallname", label:"<?php echo _('Name')?>",width:150,<?php echo($_SESSION['state']['department']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			    //  ,{key:"state", label:"<?php echo _('State')?>",width:100,<?php echo(($_SESSION['state']['department']['products']['view']!='general')?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			    //  ,{key:"web", label:"<?php echo _('Web')?>",width:100,<?php echo(($_SESSION['state']['department']['products']['view']!='general')?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      
			    //  ,{key:"sold", label:"<?php echo _('Sold')?>",width:100,<?php echo($_SESSION['state']['department']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			    //  ,{key:"sales", label:"<?php echo _('Sales')?>",width:100,<?php echo($_SESSION['state']['department']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			    //  ,{key:"profit", label:"<?php echo _('Profit')?>",width:100,<?php echo($_SESSION['state']['department']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			    //  ,{key:"margin", label:"<?php echo _('Margin')?>",width:100,<?php echo($_SESSION['state']['department']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			    //  ,{key:"stock", label:"<?php echo _('Available')?>", width:70,sortable:true,className:"aright",<?php echo(($_SESSION['state']['department']['products']['view']=='stock' or $_SESSION['state']['department']['products']['view']=='general')  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			    //  ,{key:"parts", label:"<?php echo _('Parts')?>",width:200,<?php echo($_SESSION['state']['department']['products']['view']!='parts'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			    //  ,{key:"supplied", label:"<?php echo _('Supplied by')?>",width:200,<?php echo($_SESSION['state']['department']['products']['view']!='parts'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			    //  ,{key:"gmroi", label:"<?php echo _('GMROI')?>", width:100,sortable:true,className:"aright",<?php echo(($_SESSION['state']['department']['products']['view']=='parts' )  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			    //  ,{key:"family", label:"<?php echo _('Family')?>",width:120,<?php echo($_SESSION['state']['department']['products']['view']!='cats'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			    //  ,{key:"dept", label:"<?php echo _('Main Department')?>",width:300,<?php echo($_SESSION['state']['department']['products']['view']!='cats'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			    //  ,{key:"expcode", label:"<?php echo _('TC(UK)')?>",width:200,<?php echo($_SESSION['state']['department']['products']['view']!='cats'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			      
			      
			      
			      			 		{key:"code", label:"<?php echo _('Code')?> ", width:87,sortable:true, className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"name", label:"<?php echo _('Name')?>",width:390,<?php echo(($_SESSION['state']['department']['products']['view']=='general' )?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
								 ,{key:"smallname", label:"<?php echo _('Name')?>",width:340, sortable:true,className:"aleft",className:"aleft",<?php echo($_SESSION['state']['department']['products']['view']=='general'?'hidden:true,':'')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   // ,{key:"formated_record_type", label:"<?php echo _('State')?>",width:100,<?php echo(($_SESSION['state']['department']['products']['view']=='general' or $_SESSION['state']['department']['products']['view']=='stock')?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    //	,{key:"state", label:"<?php echo _('State')?>",width:100,<?php echo(($_SESSION['state']['department']['products']['view']=='general' or $_SESSION['state']['department']['products']['view']=='stock')?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"web", label:"<?php echo _('Web/Sales State')?>",width:190,<?php echo(($_SESSION['state']['department']['products']['view']=='general' or $_SESSION['state']['department']['products']['view']=='stock' )?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   
				   ,{key:"sold", label:"<?php echo _('Sold')?>",width:90,<?php echo($_SESSION['state']['department']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"sales", label:"<?php echo _('Sales')?>",width:90,<?php echo($_SESSION['state']['department']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit", label:"<?php echo _('Profit')?>",width:90,<?php echo($_SESSION['state']['department']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"margin", label:"<?php echo _('Margin')?>",width:90,<?php echo($_SESSION['state']['department']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   
				   
				   ,{key:"stock", label:"<?php echo _('Available')?>", width:65,sortable:true,className:"aright",<?php echo(($_SESSION['state']['department']['products']['view']=='stock' or $_SESSION['state']['department']['products']['view']=='general')  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_state", label:"<?php echo _('Stock State')?>", width:70,sortable:true,className:"aright",<?php echo(($_SESSION['state']['department']['products']['view']=='stock' )  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_forecast", label:"<?php echo _('Forecast')?>", width:70,sortable:true,className:"aright",<?php echo(($_SESSION['state']['department']['products']['view']=='stock' )  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   
				   ,{key:"parts", label:"<?php echo _('Parts')?>",width:130,<?php echo($_SESSION['state']['department']['products']['view']!='parts'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"supplied", label:"<?php echo _('Supplied by')?>",width:130,<?php echo($_SESSION['state']['department']['products']['view']!='parts'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"gmroi", label:"<?php echo _('GMROI')?>", width:100,sortable:true,className:"aright",<?php echo(($_SESSION['state']['department']['products']['view']=='parts' )  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				    //,{key:"dept", label:"<?php echo _('Main Department')?>",width:200,<?php echo($_SESSION['state']['department']['products']['view']!='cats'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"expcode", label:"<?php echo _('Tariff Code')?>",width:160,<?php echo($_SESSION['state']['department']['products']['view']!='cats'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

			      
			      
			      
			      
			      
			      

			       ];

	    this.dataSource1 = new YAHOO.util.DataSource("ar_assets.php?tipo=products&parent=department&tableid=1");
	    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    this.dataSource1.responseSchema = {
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
		
		fields: [
			 'id'
			 ,"code"
			 ,"name","stock","stock_value"
			 ,'sales','profit','margin','sold',"parts","supplied","gmroi","family","dept","expcode","smallname","state","web"
			 ]};
	    
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource1, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilderwithTotals
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['department']['products']['nr']+1?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['department']['products']['order']?>",
									 dir: "<?php echo$_SESSION['state']['department']['products']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

	    this.table1.filter={key:'<?php echo$_SESSION['state']['department']['products']['f_field']?>',value:'<?php echo$_SESSION['state']['department']['products']['f_value']?>'};
this.table1.table_id=tableid;
     this.table1.subscribe("renderEvent", myrenderEvent);


 var tableid=4; 
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"code", label:"<?php echo _('Code')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"type", label:"<?php echo _('Type')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				    ,{key:"title", label:"<?php echo _('Title')?>", width:300,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"url", label:"<?php echo _('URL')?>", width:300,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

						    
				    
				    
				     ];

	    this.dataSource4 = new YAHOO.util.DataSource("ar_sites.php?tipo=pages&sf=0&parent=department&tableid=4&parent_key="+Dom.get('department_key').value);
	    this.dataSource4.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource4.connXhrMode = "queueRequests";
	    this.dataSource4.responseSchema = {
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
		
		fields: [
			 'id','title','code','url','type'
						 ]};
	    
	    this.table4 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource4, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
								        
									      rowsPerPage:<?php echo$_SESSION['state']['department']['pages']['nr']?>,containers : 'paginator4', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['department']['pages']['order']?>",
									 dir: "<?php echo$_SESSION['state']['department']['pages']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table4.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table4.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table4.doBeforePaginatorChange = mydoBeforePaginatorChange;
   this.table4.table_id=tableid;
     this.table4.subscribe("renderEvent", myrenderEvent);


	    
	    this.table4.filter={key:'<?php echo$_SESSION['state']['department']['pages']['f_field']?>',value:'<?php echo$_SESSION['state']['department']['pages']['f_value']?>'};
		






	};
    });


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





function change_family_elements(){

ids=['elements_family_discontinued','elements_family_discontinuing','elements_family_normal','elements_family_inprocess','elements_family_nosale'];


if(Dom.hasClass(this,'selected')){

var number_selected_elements=0;
for(i in ids){
if(Dom.hasClass(ids[i],'selected')){
number_selected_elements++;
}
}

if(number_selected_elements>1){
Dom.removeClass(this,'selected')

}

}else{
Dom.addClass(this,'selected')

}

table_id=0;
 var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];
var request='';
for(i in ids){
if(Dom.hasClass(ids[i],'selected')){
request=request+'&'+ids[i]+'=1'
}else{
request=request+'&'+ids[i]+'=0'

}
}
  
 // alert(request)
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       


}


function show_dialog_change_products_display(){
	region1 = Dom.getRegion('change_products_display_mode'); 
    region2 = Dom.getRegion('change_products_display_menu'); 
	var pos =[region1.right-region2.width,region1.bottom]
	Dom.setXY('change_products_display_menu', pos);
	dialog_change_products_display.show();
}

function show_dialog_change_families_display(){
	region1 = Dom.getRegion('change_families_display_mode'); 
    region2 = Dom.getRegion('change_families_display_menu'); 
	var pos =[region1.right-region2.width,region1.bottom]
	Dom.setXY('change_families_display_menu', pos);
	dialog_change_families_display.show();
}


 function init(){


dialog_change_products_display = new YAHOO.widget.Dialog("change_products_display_menu", {visible : false,close:true,underlay: "none",draggable:false});
dialog_change_products_display.render();
	 YAHOO.util.Event.addListener("change_products_display_mode", "click", show_dialog_change_products_display);

dialog_change_families_display = new YAHOO.widget.Dialog("change_families_display_menu", {visible : false,close:true,underlay: "none",draggable:false});
dialog_change_families_display.render();
	 YAHOO.util.Event.addListener("change_families_display_mode", "click", show_dialog_change_families_display);


Event.addListener(['elements_family_discontinued','elements_family_discontinuing','elements_family_normal','elements_family_inprocess','elements_family_nosale'], "click",change_family_elements);
 Event.addListener(['elements_discontinued','elements_nosale','elements_private','elements_sale','elements_historic'], "click",change_elements);



 Event.addListener(['details','families','products','categories','deals','web'], "click",change_block);

  YAHOO.util.Event.addListener('export_csv0', "click",download_csv,'families_in_department');
 YAHOO.util.Event.addListener('export_csv0_in_dialog', "click",download_csv_from_dialog,{table:'export_csv_table0',tipo:'families_in_department'});
  csvMenu = new YAHOO.widget.ContextMenu("export_csv_menu0", {trigger:"export_csv0" });
	 csvMenu.render();
	 csvMenu.subscribe("show", csvMenu.focus);
   
 YAHOO.util.Event.addListener('export_csv0_close_dialog', "click",csvMenu.hide,csvMenu,true);

 init_search('products_store');
 
 

  YAHOO.util.Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 YAHOO.util.Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);
 YAHOO.util.Event.addListener('clean_table_filter_show1', "click",show_filter,1);
 YAHOO.util.Event.addListener('clean_table_filter_hide1', "click",hide_filter,1);
     
 get_thumbnails({tipo:'families',parent:'department'});

 
 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
 oAutoComp.minQueryLength = 0; 
 
  var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS1.queryMatchContains = true;
  oACDS1.table_id=1;
 var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1","f_container1", oACDS1);
 oAutoComp1.minQueryLength = 0;  
 


 ids=['family_general','family_sales','family_stock'];
 YAHOO.util.Event.addListener(ids, "click",change_family_view,{'table_id':0,'parent':'department'})




 ids=['family_period_all','family_period_year','family_period_quarter','family_period_month','family_period_week'];
    Event.addListener(ids, "click",change_period,{'table_id':0,'subject':'family'});
    ids=['family_avg_totals','family_avg_month','family_avg_week',"family_avg_month_eff","family_avg_week_eff"];
    Event.addListener(ids, "click",change_avg,{'table_id':0,'subject':'family'});
 ids=['product_general','product_sales','product_stock','product_parts','product_cats'];
    Event.addListener(ids, "click",change_product_view,{'table_id':1,'parent':'department'});
 ids=['product_period_all','product_period_year','product_period_quarter','product_period_month','product_period_week'];
    Event.addListener(ids, "click",change_period,{'table_id':1,'subject':'product'});
    ids=['product_avg_totals','product_avg_month','product_avg_week',"product_avg_month_eff","product_avg_week_eff"];
    Event.addListener(ids, "click",change_avg,{'table_id':1,'subject':'product'});



YAHOO.util.Event.addListener("info_next", "click",next_info_period,0);
YAHOO.util.Event.addListener("info_previous", "click",previous_info_period,0);



 YAHOO.util.Event.addListener('product_submit_search', "click",submit_search,"product");
 YAHOO.util.Event.addListener('product_search', "keydown", submit_search_on_enter,"product");

YAHOO.util.Event.addListener('details', "click",change_details,'store');

 YAHOO.util.Event.addListener('show_percentages', "click",show_percentages,'department');


 ids=['table_type_thumbnail','table_type_list'];
 YAHOO.util.Event.addListener(ids, "click",change_table_type,{table_id:0,parent:'department'});

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
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 });
YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {trigger:"filter_name0"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
 
 YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 });
YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {trigger:"filter_name1"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
    
    
