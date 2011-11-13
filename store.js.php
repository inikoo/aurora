<?php
include_once('common.php');
$store_period_title=array('year'=>_('Last Year'),'quarter'=>_('Last Quarter'),'month'=>_('Last Month'),'week'=>_('Last Week'),'all'=>_('All'));
$title='';

foreach( $store_period_title as $key=>$value){
$title.=sprintf(',%s:"%s"',$key,$value);
}
$title=preg_replace('/^,/','',$title);

?>
 var Dom   = YAHOO.util.Dom;
 var Event  =YAHOO.util.Event;
 var info_period_title={<?php echo $title ?>};
  var current_store_period='<?php echo$_SESSION['state']['stores']['stores']['period']?>';


var Dom = YAHOO.util.Dom;

var period='period_<?php echo$_SESSION['state']['store']['departments']['period']?>';
var avg='avg_<?php echo$_SESSION['state']['store']['departments']['avg']?>';

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

table_id=2;
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

function change_plot(o){
ids=['plot_store','plot_top_departments','plot_pie'];
block_ids=['plot_store_div','plot_top_departments_div','plot_pie_div'];
Dom.setStyle(block_ids,'display','none');
Dom.setStyle(o.id+'_div','display','');
Dom.removeClass(ids,'selected');
Dom.addClass(o,'selected');


if(o.id=='plot_store')
plot='store';
else if(o.id=='plot_top_departments')
plot='top_departments';
else if(o.id=='plot_pie')
plot='pie';

YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=store-plot&value='+plot ,{});
}


function change_block(){
ids=['details','sites','departments','families','products','categories','deals','pages'];
block_ids=['block_details','block_sites','block_departments','block_families','block_products','block_categories','block_deals','block_pages'];
Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=store-block_view&value='+this.id ,{});
}






YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {





	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"code", label:"<?php echo _('Code')?>", width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"name", label:"<?php echo _('Name')?>", width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"families", label:"<?php echo _('Families')?>", width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"active", label:"<?php echo _('Products')?>",  width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    
				     ,{key:"discontinued", label:"<?php echo _('Discontinued')?>",  width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				    ,{key:"sales", label:"<?php echo _('Sales')?>", width:80,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit", label:"<?php echo _('Profit')?>", width:80,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"aws_p", label:"<?php echo _('Aw S/P')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"awp_p", label:"<?php echo _('Aw P/P')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}


				    ,{key:"surplus", label:"<?php echo _('Surplus')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"optimal", label:"<?php echo _('OK')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"low", label:"<?php echo _('Low')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}




				    ,{key:"critical", label:"<?php echo _('Critical')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    



				    ,{key:"outofstock", label:"<?php echo _('Gone')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_error", label:"<?php echo _('Unknown')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"sales_type", label:"<?php echo _('Sales Type')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='web'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}


				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_assets.php?tipo=departments&parent=store");
		
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
			 'id',
			 "name","code","aws_p","awp_p","sales_type",
			 'families',
			 'active',"sales","stock_error","stock_value","outofstock","profit","surplus","optimal","low","critical","todo","discontinued"
			 ]};
	    

	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 20,generateRequest : myRequestBuilderwithTotals
								       ,paginator : new YAHOO.widget.Paginator({
								        alwaysVisible:true,
									      rowsPerPage:<?php echo $_SESSION['state']['store']['departments']['nr']+1?>,
									      totalRecords:YAHOO.widget.Paginator.VALUE_UNLIMITED,
									      containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",
 									      rowsPerPageOptions : [10,25,50,100,250,500],
 									     
									      template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['store']['departments']['order']?>",
									 dir: "<?php echo$_SESSION['state']['store']['departments']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
       this.table0.doBeforeLoadData=mydoBeforeLoadData;
    this.table0.table_id=tableid;
     this.table0.subscribe("renderEvent", myrenderEvent);

	    
	    this.table0.view='<?php echo$_SESSION['state']['store']['departments']['view']?>';

      this.table0.filter={key:'<?php echo$_SESSION['state']['store']['departments']['f_field']?>',value:'<?php echo$_SESSION['state']['store']['departments']['f_value']?>'};




	    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"code", label:"<?php echo _('Code')?>", width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"store", label:"<?php echo _('Store')?>",hidden:true, width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"department", label:"<?php echo _('Department')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				    ,{key:"name", label:"<?php echo _('Name')?>",<?php echo($_SESSION['state']['store']['families']['view']=='stock'?'hidden:true,':'')?> width:280,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"active", label:"<?php echo _('Products')?>",  width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['families']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"sales", label:"<?php echo _('Sales')?>", width:90,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['families']['view']!='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit", label:"<?php echo _('Profit')?>", width:90,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['families']['view']!='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"surplus", label:"<?php echo _('Surplus')?>", width:60,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['families']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"optimal", label:"<?php echo _('OK')?>", width:60,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['families']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"low", label:"<?php echo _('Low')?>", width:60,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['families']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"critical", label:"<?php echo _('Critical')?>", width:60,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['families']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"outofstock", label:"<?php echo _('Gone')?>", width:60,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['families']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_error", label:"<?php echo _('Unknown')?>", width:60,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['families']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    
				    
				    
				     ];

	    this.dataSource1 = new YAHOO.util.DataSource("ar_assets.php?tipo=families&parent=store&tableid=1");
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
			 'id',
			 "code",
			 "name",
			 'active',"stock_error","stock_value","outofstock","sales","profit","surplus","optimal","low","critical","store","department"
			 ]};
	    
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource1, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilderwithTotals
								       ,paginator : new YAHOO.widget.Paginator({
								        
									      rowsPerPage:<?php echo$_SESSION['state']['store']['families']['nr']+1?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['store']['families']['order']?>",
									 dir: "<?php echo$_SESSION['state']['store']['families']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
    this.table1.table_id=tableid;
     this.table1.subscribe("renderEvent", myrenderEvent);

	    
	    this.table1.view='<?php echo$_SESSION['state']['store']['families']['view']?>';
	    this.table1.filter={key:'<?php echo$_SESSION['state']['store']['families']['f_field']?>',value:'<?php echo$_SESSION['state']['store']['families']['f_value']?>'};
		


	    var tableid=2;
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [ 
			 		{key:"code", label:"<?php echo _('Code')?>", width:87,sortable:true, className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"name", label:"<?php echo _('Name')?>",width:390,<?php echo(($_SESSION['state']['store']['products']['view']=='general' )?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
								 ,{key:"smallname", label:"<?php echo _('Name')?>",width:340, sortable:true,className:"aleft",className:"aleft",<?php echo($_SESSION['state']['store']['products']['view']=='general'?'hidden:true,':'')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   // ,{key:"formated_record_type", label:"<?php echo _('State')?>",width:100,<?php echo(($_SESSION['state']['store']['products']['view']=='general' or $_SESSION['state']['store']['products']['view']=='stock')?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    //	,{key:"state", label:"<?php echo _('State')?>",width:100,<?php echo(($_SESSION['state']['store']['products']['view']=='general' or $_SESSION['state']['store']['products']['view']=='stock')?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"web", label:"<?php echo _('Web/Sales State')?>",width:190,<?php echo(($_SESSION['state']['store']['products']['view']=='general' or $_SESSION['state']['store']['products']['view']=='stock' )?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   
				   ,{key:"sold", label:"<?php echo _('Sold')?>",width:90,<?php echo($_SESSION['state']['store']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"sales", label:"<?php echo _('Sales')?>",width:90,<?php echo($_SESSION['state']['store']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit", label:"<?php echo _('Profit')?>",width:90,<?php echo($_SESSION['state']['store']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"margin", label:"<?php echo _('Margin')?>",width:90,<?php echo($_SESSION['state']['store']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   
				   
				   ,{key:"stock", label:"<?php echo _('Available')?>", width:65,sortable:true,className:"aright",<?php echo(($_SESSION['state']['store']['products']['view']=='stock' or $_SESSION['state']['store']['products']['view']=='general')  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_state", label:"<?php echo _('State')?>", width:70,sortable:true,className:"aright",<?php echo(($_SESSION['state']['store']['products']['view']=='stock' )  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_forecast", label:"<?php echo _('Forecast')?>", width:70,sortable:true,className:"aright",<?php echo(($_SESSION['state']['store']['products']['view']=='stock' )  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   
				   ,{key:"parts", label:"<?php echo _('Parts')?>",width:130,<?php echo($_SESSION['state']['store']['products']['view']!='parts'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"supplied", label:"<?php echo _('Supplied by')?>",width:130,<?php echo($_SESSION['state']['store']['products']['view']!='parts'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"gmroi", label:"<?php echo _('GMROI')?>", width:100,sortable:true,className:"aright",<?php echo(($_SESSION['state']['store']['products']['view']=='parts' )  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				    //,{key:"dept", label:"<?php echo _('Main Department')?>",width:200,<?php echo($_SESSION['state']['store']['products']['view']!='cats'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"expcode", label:"<?php echo _('Tariff Code')?>",width:160,<?php echo($_SESSION['state']['store']['products']['view']!='cats'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
   

			       ];

	    this.dataSource2 = new YAHOO.util.DataSource("ar_assets.php?tipo=products&parent=store&tableid=2");
	    this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource2.connXhrMode = "queueRequests";
	    this.dataSource2.responseSchema = {
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
	    
	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource2, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilderwithTotals
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['store']['products']['nr']+1?>,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['store']['products']['order']?>",
									 dir: "<?php echo$_SESSION['state']['store']['products']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
    this.table2.table_id=tableid;
     this.table2.subscribe("renderEvent", myrenderEvent);

	    
	    this.table2.view='<?php echo$_SESSION['state']['store']['products']['view']?>';
	    this.table2.filter={key:'<?php echo$_SESSION['state']['store']['products']['f_field']?>',value:'<?php echo$_SESSION['state']['store']['products']['f_value']?>'};
		

  var tableid=3; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"name", label:"<?php echo _('Name')?>", width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				 	 ,{key:"url", label:"<?php echo _('URL')?>", width:300,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

						    
				    
				    
				     ];

	    this.dataSource3 = new YAHOO.util.DataSource("ar_sites.php?tipo=sites&parent=store&tableid=3&parent_key="+Dom.get('store_id').value);
	    this.dataSource3.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource3.connXhrMode = "queueRequests";
	    this.dataSource3.responseSchema = {
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
			 'id','name','url'
						 ]};
	    
	    this.table3 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource3, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
								        
									      rowsPerPage:<?php echo$_SESSION['state']['store']['sites']['nr']?>,containers : 'paginator3', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info3'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['store']['sites']['order']?>",
									 dir: "<?php echo$_SESSION['state']['store']['sites']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table3.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table3.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table3.doBeforePaginatorChange = mydoBeforePaginatorChange;
    this.table3.table_id=tableid;
     this.table3.subscribe("renderEvent", myrenderEvent);

	    
	    this.table3.filter={key:'<?php echo$_SESSION['state']['store']['sites']['f_field']?>',value:'<?php echo$_SESSION['state']['store']['sites']['f_value']?>'};
		





  var tableid=4; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"code", label:"<?php echo _('Code')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"type", label:"<?php echo _('Type')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				    ,{key:"title", label:"<?php echo _('Title')?>", width:300,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"url", label:"<?php echo _('URL')?>", width:300,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

						    
				    
				    
				     ];

request="ar_sites.php?tipo=pages&parent=store&tableid=4&parent_key="+Dom.get('store_id').value
	    this.dataSource4 = new YAHOO.util.DataSource(request);
	   // alert(request)
	   // alert("ar_sites.php?tipo=pages&parent=store&tableid=4&parent_key="+Dom.get('store_id').value)
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
								        
									      rowsPerPage:<?php echo$_SESSION['state']['store']['pages']['nr']?>,containers : 'paginator4', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info4'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['store']['pages']['order']?>",
									 dir: "<?php echo$_SESSION['state']['store']['pages']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table4.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table4.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table4.doBeforePaginatorChange = mydoBeforePaginatorChange;
       this.table4.doBeforeLoadData=mydoBeforeLoadData;
         this.table4.table_id=tableid;
     this.table4.subscribe("renderEvent", myrenderEvent);

	    
	    this.table4.filter={key:'<?php echo$_SESSION['state']['store']['pages']['f_field']?>',value:'<?php echo$_SESSION['state']['store']['pages']['f_value']?>'};
		





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
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=stores-period&value='+period);

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

 function init(){
 Event.addListener(['elements_discontinued','elements_nosale','elements_private','elements_sale','elements_historic'], "click",change_elements);

 Event.addListener(['elements_family_discontinued','elements_family_discontinuing','elements_family_normal','elements_family_inprocess','elements_family_nosale'], "click",change_family_elements);

 
  YAHOO.util.Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 YAHOO.util.Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);
 YAHOO.util.Event.addListener('clean_table_filter_show1', "click",show_filter,1);
 YAHOO.util.Event.addListener('clean_table_filter_hide1', "click",hide_filter,1);
 YAHOO.util.Event.addListener('clean_table_filter_show2', "click",show_filter,2);
 YAHOO.util.Event.addListener('clean_table_filter_hide2', "click",hide_filter,2);

 
 Event.addListener(['details','sites','departments','families','products','categories','deals','pages'], "click",change_block);

// -------------------------Export(CSV) code for department under store --------------------
  YAHOO.util.Event.addListener('export_csv0', "click",download_csv,'departments');
 YAHOO.util.Event.addListener('export_csv0_in_dialog', "click",download_csv_from_dialog,{table:'export_csv_table0',tipo:'departments'});
  csvMenu = new YAHOO.widget.ContextMenu("export_csv_menu0", {trigger:"export_csv0" });
	 csvMenu.render();
	 csvMenu.subscribe("show", csvMenu.focus);
   
 YAHOO.util.Event.addListener('export_csv0_close_dialog', "click",csvMenu.hide,csvMenu,true);
 
  YAHOO.util.Event.addListener('export_csv1', "click",download_csv,'families');
 YAHOO.util.Event.addListener('export_csv1_in_dialog', "click",download_csv_from_dialog,{table:'export_csv_table1',tipo:'families'});
  csvMenu = new YAHOO.widget.ContextMenu("export_csv_menu1", {trigger:"export_csv1" });
	 csvMenu.render();
	 csvMenu.subscribe("show", csvMenu.focus);
   
 YAHOO.util.Event.addListener('export_csv1_close_dialog', "click",csvMenu.hide,csvMenu,true);
 
// -------------------------Export(CSV) code for department under store ends here--------------
  init_search('products_store');
 
 
   

 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
 oAutoComp.minQueryLength = 0; 
 
  var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS1.queryMatchContains = true;
  oACDS1.table_id=1;
 var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1","f_container1", oACDS1);
 oAutoComp1.minQueryLength = 0; 

 var oACDS2 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS2.queryMatchContains = true;
  oACDS2.table_id=2;
 var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input2","f_container2", oACDS2);
 oAutoComp2.minQueryLength = 0; 
 

 ids=['department_general','department_sales','department_stock'];
 YAHOO.util.Event.addListener(ids, "click",change_department_view,{'table_id':0,'parent':'store'})
 ids=['department_period_all','department_period_year','department_period_quarter','department_period_month','department_period_week'];
 YAHOO.util.Event.addListener(ids, "click",change_period,{'table_id':0,'subject':'department'});
 ids=['department_avg_totals','department_avg_month','department_avg_week',"department_avg_month_eff","department_avg_week_eff"];
 YAHOO.util.Event.addListener(ids, "click",change_avg,{'table_id':0,'subject':'department'});

ids=['family_general','family_sales','family_stock'];
 YAHOO.util.Event.addListener(ids, "click",change_family_view,{'table_id':1,'parent':'store'})
 ids=['family_period_all','family_period_year','family_period_quarter','family_period_month','family_period_week'];
 YAHOO.util.Event.addListener(ids, "click",change_period,{'table_id':1,'subject':'family'});
 ids=['family_avg_totals','family_avg_month','family_avg_week',"family_avg_month_eff","family_avg_week_eff"];
 YAHOO.util.Event.addListener(ids, "click",change_avg,{'table_id':1,'subject':'family'});

 



ids=['product_general','product_sales','product_stock','product_parts','product_cats'];
 YAHOO.util.Event.addListener(ids, "click",change_product_view,{'table_id':2,'parent':'store'})
 ids=['product_period_all','product_period_three_year','product_period_year','product_period_yeartoday','product_period_six_month','product_period_quarter','product_period_month','product_period_ten_day','product_period_week'];
 YAHOO.util.Event.addListener(ids, "click",change_period,{'table_id':2,'subject':'product'});
 ids=['product_avg_totals','product_avg_month','product_avg_week',"product_avg_month_eff","product_avg_week_eff"];
 YAHOO.util.Event.addListener(ids, "click",change_avg,{'table_id':2,'subject':'product'});




YAHOO.util.Event.addListener("info_next", "click",next_info_period,0);
YAHOO.util.Event.addListener("info_previous", "click",previous_info_period,0);

 
YAHOO.util.Event.addListener('show_percentages', "click",show_percentages,'departments');


 YAHOO.util.Event.addListener('product_submit_search', "click",submit_search,'product');
 YAHOO.util.Event.addListener('product_search', "keydown", submit_search_on_enter,'product');




 }

YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {trigger:"filter_name0"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	

    });

YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {trigger:"filter_name1"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("info_period_menu", function () {
	 var oMenu = new YAHOO.widget.Menu("info_period_menu", { context:["info_period","tr", "br"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("info_period", "click", oMenu.show, null, oMenu);
    });
    
