<?php


include_once('common.php');

$store_period_title=array('year'=>_('Last Year'),'quarter'=>_('Last Quarter'),'month'=>_('Last Month'),'week'=>_('Last Week'),'all'=>_('All'));
$title='';

foreach( $store_period_title as $key=>$value){
$title.=sprintf(',%s:"%s"',$key,$value);
}
$title=preg_replace('/^,/','',$title);


?>


var link='family.php';
var info_period_title={<?php echo $title ?>};
var Dom   = YAHOO.util.Dom;
var Event   = YAHOO.util.Event;

var current_store_period='<?php echo$_SESSION['state']['department']['period']?>';
var dialog_change_products_display;
var dialog_change_products_table_type;

function change_block(){
ids=['details','products','categories','deals','sales', 'web','notes'];
block_ids=['block_details','block_products','block_categories','block_deals','block_sales', 'block_web','block_notes'];
Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=family-block_view&value='+this.id ,{});
}

function get_offers_elements_numbers(){

}

var myRowFormatter = function(elTr, oRecord) {
    if (oRecord.getData('code') =='total') {
        Dom.addClass(elTr, 'total');
    }
    return true;
}; 
YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

	    var tableid=0;
	    var tableDivEL="table"+tableid;

	    var myRowFormatter = function(elTr, oRecord) {
		if (oRecord.getData('record_type')=='Discontinued') {
		    Dom.addClass(elTr, 'discontinued');
		}
		return true;
	    }; 




	    var ColumnDefs = [ 
				    {key:"code", label:"<?php echo _('Code')?>", width:87,sortable:true, className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"name", label:"<?php echo _('Name')?>",width:390,<?php echo(($_SESSION['state']['family']['products']['view']=='general' )?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"smallname", label:"<?php echo _('Name')?>",width:300, sortable:true,className:"aleft",className:"aleft",<?php echo(($_SESSION['state']['family']['products']['view']=='general' or $_SESSION['state']['family']['products']['view']=='timeline'or $_SESSION['state']['family']['products']['view']=='properties')?'hidden:true,':'')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   // ,{key:"formated_record_type", label:"<?php echo _('State')?>",width:100,<?php echo(($_SESSION['state']['family']['products']['view']=='general' or $_SESSION['state']['family']['products']['view']=='stock')?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"state", label:"<?php echo _('State')?>",width:100,<?php echo(($_SESSION['state']['family']['products']['view']=='general' or $_SESSION['state']['family']['products']['view']=='stock')?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"web", label:"<?php echo _('Web')?>",width:190,<?php echo(($_SESSION['state']['family']['products']['view']=='general' or $_SESSION['state']['family']['products']['view']=='stock' )?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"sold", label:"<?php echo _('Sold')?>",width:100,<?php echo($_SESSION['state']['family']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"sales", label:"<?php echo _('Sales')?>",width:100,<?php echo($_SESSION['state']['family']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"delta_sales", label:"<?php echo '1y&Delta; '._('Sales')?>", width:80,sortable:true,className:"aright",<?php echo($_SESSION['state']['family']['products']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				    ,{key:"profit", label:"<?php echo _('Profit')?>",width:100,<?php echo($_SESSION['state']['family']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				 //   ,{key:"margin", label:"<?php echo _('Margin')?>",width:100,<?php echo($_SESSION['state']['family']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   
				   ,{key:"stock", label:"<?php echo _('Available')?>", width:65,sortable:true,className:"aright",<?php echo(($_SESSION['state']['family']['products']['view']=='stock' or $_SESSION['state']['family']['products']['view']=='general')  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_state", label:"<?php echo _('Stock State')?>", width:70,sortable:true,className:"aright",<?php echo(($_SESSION['state']['family']['products']['view']=='stock' )  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_forecast", label:"<?php echo _('Forecast')?>", width:70,sortable:true,className:"aright",<?php echo(($_SESSION['state']['family']['products']['view']=='stock' )  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   
				   ,{key:"parts", label:"<?php echo _('Parts')?>",width:130,<?php echo($_SESSION['state']['family']['products']['view']!='parts'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"supplied", label:"<?php echo _('Supplied by')?>",width:130,<?php echo($_SESSION['state']['family']['products']['view']!='parts'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"gmroi", label:"<?php echo _('GMROI')?>", width:100,sortable:true,className:"aright",<?php echo(($_SESSION['state']['family']['products']['view']=='parts' )  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				    //,{key:"dept", label:"<?php echo _('Main Department')?>",width:200,<?php echo($_SESSION['state']['family']['products']['view']!='cats'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"expcode", label:"<?php echo _('Tariff Code')?>",width:160,<?php echo($_SESSION['state']['family']['products']['view']!='cats'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			        ,{key:"last_update", label:"<?php echo _('Last Update')?>", width:180,sortable:true,className:"aright",<?php echo($_SESSION['state']['family']['products']['view']=='timeline'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
   				    ,{key:"from", label:"<?php echo _('Since')?>", width:190,sortable:true,className:"aright",<?php echo($_SESSION['state']['family']['products']['view']=='timeline'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"to", label:"<?php echo _('Until')?>", width:180,sortable:true,className:"aright",<?php echo($_SESSION['state']['family']['products']['view']=='timeline'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				,{key:"package_type", label:"<?php echo _('Pkg Type')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['family']['products']['view']=='properties'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"package_weight", label:"<?php echo _('Pkg Weight')?>", width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['family']['products']['view']=='properties'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"package_dimension", label:"<?php echo _('Pkg Dim')?>", width:120,sortable:true,className:"aright",<?php echo($_SESSION['state']['family']['products']['view']=='properties'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"package_volume", label:"<?php echo _('Pkg Vol')?>", width:110,sortable:true,className:"aright",<?php echo($_SESSION['state']['family']['products']['view']=='properties'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"unit_weight", label:"<?php echo _('Unit Weight')?>", width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['family']['products']['view']=='properties'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"unit_dimension", label:"<?php echo _('Unit Dim')?>", width:120,sortable:true,className:"aright",<?php echo($_SESSION['state']['family']['products']['view']=='properties'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

			     
			     ];
request="ar_assets.php?tipo=products&parent=family&sf=0"+'&parent_key='+Dom.get('family_key').value;
//alert(request);
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
		
		fields: [
			 "last_update","from","to",
			 'id',"package_type","package_weight","package_dimension","package_volume","unit_weight","unit_dimension"
			 ,"code"
			 ,"name","stock","stock_value","record_type"
			 ,'sales','profit','margin','sold',"parts","supplied","gmroi","family","dept","expcode","state","web","smallname","delta_sales"
			 ]};
	    


	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							 formatRow: myRowFormatter,
							   renderLoopSize: 50,generateRequest : myRequestBuilderwithTotals
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage:<?php echo$_SESSION['state']['family']['products']['nr']+1?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['family']['products']['order']?>",
									 dir: "<?php echo$_SESSION['state']['family']['products']['order_dir']?>"
								     }
							   ,dynamicData : true  

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginator = mydoBeforePaginatorChange;
      	this.table0.request=request;
  		this.table0.table_id=tableid;
      	this.table0.subscribe("renderEvent", products_myrenderEvent);
   		this.table0.getDataSource().sendRequest(null, {
    		success:function(request, response, payload) {
        		if(response.results.length == 0) {
            		get_products_elements_numbers()
            
        		} else {
            		//this.onDataReturnInitializeTable(request, response, payload);
        		}
    		},
    		scope:this.table0,
    		argument:this.table0.getState()
		});
	    



		this.table0.filter={key:'<?php echo$_SESSION['state']['family']['products']['f_field']?>',value:'<?php echo$_SESSION['state']['family']['products']['f_value']?>'};

	    
	    this.table0.view='<?php echo$_SESSION['state']['family']['products']['view']?>';




    var tableid=1;
	    var tableDivEL="table"+tableid;

	    var ColumnDefs = [ 
				    {key:"code", label:"<?php echo _('Code')?>", width:60,sortable:true, className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"name", label:"<?php echo _('Name')?>",width:370, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   				   ,{key:"state", label:"<?php echo _('State')?>",width:70, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   ,{key:"sold", label:"<?php echo _('Sold')?>",width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"sales", label:"<?php echo _('Sales')?>",width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit", label:"<?php echo _('Profit')?>",width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   
							       ];
request="ar_assets.php?tipo=product_sales_report&tableid=1&parent=family&sf=0"+'&parent_key='+Dom.get('family_key').value+'&from='+Dom.get('from').value+'&to='+Dom.get('to').value;
	//alert(request)
	 this.dataSource1 = new YAHOO.util.DataSource(request);
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
			 ,"name","stock","stock_value","record_type"
			 ,'sales','profit','margin','sold',"parts","supplied","gmroi","family","dept","expcode","state","web","smallname","delta_sales"
			 ]};
	    


	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource1, {
							 //draggableColumns:true,
							 formatRow: myRowFormatter,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage:<?php echo$_SESSION['state']['family']['product_sales']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['family']['product_sales']['order']?>",
									 dir: "<?php echo$_SESSION['state']['family']['product_sales']['order_dir']?>"
								     }
							   ,dynamicData : true  

						     }
						     );
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginator = mydoBeforePaginatorChange;
      this.table1.request=request;
  this.table1.table_id=tableid;
     this.table1.subscribe("renderEvent", myrenderEvent);

		this.table1.filter={key:'<?php echo$_SESSION['state']['family']['product_sales']['f_field']?>',value:'<?php echo$_SESSION['state']['family']['product_sales']['f_value']?>'};

	    



		    var tableid=2;
		    var tableDivEL="table"+tableid;

  var ColumnDefs = [
				      {key:"date", label:"<?php echo _('Date')?>", width:200,sortable:false,className:"aright"}
				      ,{key:"invoices", label:"<?php echo _('Invoices')?>", width:100,sortable:false,className:"aright"}
				      ,{key:"customers", label:"<?php echo _('Customers')?>", width:100,sortable:false,className:"aright"}
				      ,{key:"sales", label:"<?php echo _('Sales')?>", width:100,sortable:false,className:"aright"}
					      ];

		 
		    request="ar_reports.php?tipo=assets_sales_history&scope=assets&parent=family&parent_key="+Dom.get('family_key').value+"&tableid="+tableid+'&from='+Dom.get('from').value+'&to='+Dom.get('to').value;
		   //  alert(request)
		  
		  this.dataSource2 = new YAHOO.util.DataSource(request);
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
				 "date","invoices","customers","sales"

				 ]};

	  
	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource2, {
							 //draggableColumns:true,
							 formatRow: myRowFormatter,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage:<?php echo$_SESSION['state']['family']['sales_history']['nr']?>,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['family']['sales_history']['order']?>",
									 dir: "<?php echo$_SESSION['state']['family']['sales_history']['order_dir']?>"
								     }
							   ,dynamicData : true  

						     }
						     );
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.doBeforePaginator = mydoBeforePaginatorChange;
      this.table2.request=request;
  this.table2.table_id=tableid;
     this.table2.subscribe("renderEvent", myrenderEvent);

		this.table2.filter={key:'<?php echo$_SESSION['state']['family']['sales_history']['f_field']?>',value:'<?php echo$_SESSION['state']['family']['sales_history']['f_value']?>'};



		

 var tableid=4; 
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"code", label:"<?php echo _('Code')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"type", label:"<?php echo _('Type')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				    ,{key:"title", label:"<?php echo _('Title')?>", width:300,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"url", label:"<?php echo _('URL')?>", width:300,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

						    
				    
				    
				     ];


var request="ar_sites.php?tipo=pages&sf=0&parent=family&tableid=4&parent_key="+Dom.get('family_key').value;
	 // alert(request)
	  this.dataSource4 = new YAHOO.util.DataSource(request);
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
								        
									      rowsPerPage:<?php echo$_SESSION['state']['family']['pages']['nr']?>,containers : 'paginator4', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info4'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['family']['pages']['order']?>",
									 dir: "<?php echo$_SESSION['state']['family']['pages']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table4.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table4.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table4.doBeforePaginatorChange = mydoBeforePaginatorChange;
   this.table4.table_id=tableid;
     this.table4.subscribe("renderEvent", myrenderEvent);


	    
	    this.table4.filter={key:'<?php echo$_SESSION['state']['family']['pages']['f_field']?>',value:'<?php echo$_SESSION['state']['family']['pages']['f_value']?>'};
		



		    var tableid=5; 
		    var tableDivEL="table"+tableid;  
		    
		    
		    var myRowFormatter = function(elTr, oRecord) {		   
				if (oRecord.getData('type') =='Orders') {
					Dom.addClass(elTr, 'store_history_orders');
				}else if (oRecord.getData('type') =='Notes') {
					Dom.addClass(elTr, 'store_history_notes');
				}else if (oRecord.getData('type') =='Changes') {
					Dom.addClass(elTr, 'store_history_changes');
				}
				return true;
			}; 
		    
		    
		this.prepare_note = function(elLiner, oRecord, oColumn, oData) {
          
            if(oRecord.getData("strikethrough")=="Yes") { 
            Dom.setStyle(elLiner,'text-decoration','line-through');
            Dom.setStyle(elLiner,'color','#777');

            }
            elLiner.innerHTML=oData
        };
        		    
		    var ColumnDefs = [


				       {key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				       ,{key:"type", label:"", width:0,sortable:false,hidden:true} 
				      ,{key:"date", label:"<?php echo _('Date')?>",className:"aright",width:120,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"time", label:"<?php echo _('Time')?>",className:"aleft",width:70}
				      ,{key:"handle", label:"<?php echo _('Author')?>",className:"aleft",width:100,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"note", formatter:this.prepare_note,label:"<?php echo _('Notes')?>",className:"aleft",width:500}
                      ,{key:"delete", label:"",width:12,sortable:false,action:'dialog',object:'delete_note'}
                      ,{key:"edit", label:"",width:12,sortable:false,action:'edit',object:'family_history'}


					   ];
		request="ar_history.php?tipo=store_history&parent=family&parent_key="+Dom.get('family_key').value+"&sf=0&tableid="+tableid
		//alert(request)
		    this.dataSource5  = new YAHOO.util.DataSource(request);
		    this.dataSource5.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource5.connXhrMode = "queueRequests";
	    this.dataSource5.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
                  fields: ["note","date","time","handle","delete","can_delete" ,"delete_type","key","edit","type","strikethrough"]};
		    this.table5 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource5
								 , {
								 formatRow: myRowFormatter,
								     renderLoopSize: 5,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['family']['history']['nr']?>,containers : 'paginator5', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',alwaysVisible:false,
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info5'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['family']['history']['order']?>",
									 dir: "<?php echo$_SESSION['state']['family']['history']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );

	    	this.table5.handleDataReturnPayload =myhandleDataReturnPayload;
	        this.table5.doBeforeSortColumn = mydoBeforeSortColumn;
	        this.table5.doBeforePaginatorChange = mydoBeforePaginatorChange;
		    this.table5.filter={key:'<?php echo$_SESSION['state']['family']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['family']['history']['f_value']?>'};
	        this.table5.subscribe("cellMouseoverEvent", highlightEditableCell);
	        this.table5.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	        this.table5.subscribe("cellClickEvent", onCellClick);            
			this.table5.table_id=tableid;
     		this.table5.subscribe("renderEvent", myrenderEvent);
     		
     		
    var tableid=10; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var productsColumnDefs = [
	    
	    				       {key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 

                                        ,{key:"code", label:"<?php echo _('Code')?>", width:110,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                                     ,{key:"description", label:"<?php echo _('Description')?>", width:350,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"orders", label:"<?php echo _('Orders')?>",  width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"customers", label:"<?php echo _('Customers')?>",  width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"duration", label:"<?php echo _('Duration')?>",  width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				 
				 ];
	    //?tipo=products&tid=0"
	    
	    request="ar_deals.php?tipo=deals&parent=family&parent_key="+Dom.get('family_key').value+'&tableid=10&referrer=family'
	   // alert(request);
	    this.dataSource10 = new YAHOO.util.DataSource(request);
	    this.dataSource10.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource10.connXhrMode = "queueRequests";
	    this.dataSource10.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		      rowsPerPage:"resultset.records_perpage",
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		fields: ["name","key","description","duration","orders","code","customers"]};
		

	  this.table10 = new YAHOO.widget.DataTable(tableDivEL, productsColumnDefs,
								   this.dataSource10
								 , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 //,initialLoad:false
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo $_SESSION['state']['family']['offers']['nr']?>,containers : 'paginator10', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info10'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo $_SESSION['state']['family']['offers']['order']?>",
									 dir: "<?php echo $_SESSION['state']['family']['offers']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
		this.table10.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table10.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table10.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table10.request=request;
  		this.table10.table_id=tableid;
     	this.table10.subscribe("renderEvent", myrenderEvent);
		this.table10.getDataSource().sendRequest(null, {
		    success: function(request, response, payload) {
		        if (response.results.length == 0) {
		      
		             get_offers_elements_numbers()

		        } else {
		             this.onDataReturnInitializeTable(request, response, payload);
		        }
		    },
		    scope: this.table10,
		    argument: this.table10.getState()
		});
	  
	    this.table10.filter={key:'<?php echo $_SESSION['state']['family']['offers']['f_field']?>',value:'<?php echo $_SESSION['state']['family']['offers']['f_value']?>'};
	    




	};
	get_thumbnails(0)
    });


function get_sales(from,to){
var request = 'ar_assets.php?tipo=get_asset_sales_data&parent=family&parent_key=' + Dom.get('family_key').value + '&from='+from+'&to='+to
   //alert(request);
   YAHOO.util.Connect.asyncRequest('POST', request, {
   success: function(o) {
           //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);

           
                      		Dom.get('sales_amount').innerHTML=r.sales
                      		Dom.get('profits').innerHTML=r.profits
                      		Dom.get('invoices').innerHTML=r.invoices
                      		Dom.get('customers').innerHTML=r.customers
                      		Dom.get('outers').innerHTML=r.outers

           
        }
    });

}


function get_product_element_numbers() {
 var request = 'ar_assets.php?tipo=product_elements_numbers&parent=family&parent_key=' + Dom.get('family_key').value + '&from=&to='
     YAHOO.util.Connect.asyncRequest('POST', request, {
   success: function(o) {
         //  alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
           	for(i in r.elements_numbers){
           		Dom.get('elements_'+i+'_number').innerHTML=r.elements_numbers[i]
           	}
           
        }
    });
}

function get_product_sales_element_numbers() {
 var request = 'ar_assets.php?tipo=product_elements_numbers&parent=family&parent_key=' + Dom.get('family_key').value + '&from='+Dom.get('from').value+'&to='+Dom.get('to').value
     YAHOO.util.Connect.asyncRequest('POST', request, {
   success: function(o) {
         //  alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
           	for(i in r.elements_numbers){
           		Dom.get('elements_product_sales_'+i+'_number').innerHTML=r.elements_numbers[i]
           	}
           
        }
    });
}


function change_product_sales_elements(){

ids=['elements_product_sales_discontinued','elements_product_sales_nosale','elements_product_sales_private','elements_product_sales_sale','elements_product_sales_historic'];


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

function change_display_mode(parent,name,label){
    if(name=='percentage'){
		var request='&percentages=1';
    }if(name=='value'){
		var request='&percentages=0&show_default_currency=0';
    }if(name=='value_default_d2d'){
		var request='&percentages=0&show_default_currency=1';
    }

    Dom.get('change_'+parent+'_display_mode').innerHTML='&#x21b6 '+label;
   
   if(parent=='products'){
   var table=tables['table0'];
    var datasource=tables.dataSource0;
    dialog_change_products_display.hide();

    }else{
    return;
    }
    
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);   

}

function change_sales_sub_block(o) {
    Dom.removeClass(['plot_family_sales', 'family_product_sales', 'family_sales_timeseries','family_sales_calendar'], 'selected')
    Dom.addClass(o, 'selected')
    Dom.setStyle(['sub_block_plot_family_sales', 'sub_block_family_product_sales', 'sub_block_family_sales_timeseries','sub_block_family_sales_calendar'], 'display', 'none')
    Dom.setStyle('sub_block_' + o.id, 'display', '')
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=family-sales_sub_block_tipo&value=' + o.id, {});
}


function change_table_type(parent,tipo,label){

	if(parent=='products'){
		table_id=0
	}
	
	Dom.get('change_products_table_type').innerHTML='&#x21b6 '+label;
	
	if(tipo=='list'){
	
	  if (Dom.get('change_' + parent + '_display_mode') != undefined && Dom.get(parent+'_view')=='sales') {
	  		Dom.setStyle('change_' + parent + '_display_mode','display','')

	  }
	
		Dom.setStyle('thumbnails'+table_id,'display','none')
		Dom.setStyle(['table'+table_id,'list_options'+table_id,'table_view_menu_tabs'+table_id],'display','')
 	}else{
 	
 	
 	
		Dom.setStyle('thumbnails'+table_id,'display','')
		Dom.setStyle(['table'+table_id,'list_options'+table_id,'table_view_menu_tabs'+table_id,'change_products_display_mode'],'display','none')
 	}
 	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=family-'+parent+'-table_type&value='+escape(tipo),{});
 	dialog_change_products_table_type.hide();

   
}

function show_dialog_change_products_table_type(){
	region1 = Dom.getRegion('change_products_table_type'); 
    region2 = Dom.getRegion('change_products_table_type_menu'); 
	var pos =[region1.right-region2.width,region1.bottom]
	Dom.setXY('change_products_table_type_menu', pos);
	dialog_change_products_table_type.show();
}


function show_dialog_change_products_display(){
	region1 = Dom.getRegion('change_products_display_mode'); 
    region2 = Dom.getRegion('change_products_display_menu'); 
	var pos =[region1.right-region2.width,region1.bottom]
	Dom.setXY('change_products_display_menu', pos);
	dialog_change_products_display.show();
}

function edit_family(){

element=Dom.getElementsByClassName('selected', 'span', 'chooser_ul') 
window.location='edit_family.php?id='+Dom.get('family_key').value+'&edit_tab='+element[0].id
}



function change_sales_period(){
  tipo=this.id;
 
  ids=['custome_period','products_period_yesterday','products_period_last_m','products_period_last_w','products_period_all','products_period_three_year','products_period_year','products_period_six_month','products_period_quarter','products_period_month','products_period_ten_day','products_period_week','products_period_yeartoday','products_period_monthtoday','products_period_weektoday','products_period_today'];

 Dom.removeClass(ids,"selected")
 Dom.addClass(this,"selected")
   period=this.getAttribute('period');
 YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=family-products-period&value='+period ,{});

Dom.setStyle(['info_other','info_yesterday','info_last_m','info_last_w','info_all','info_three_year','info_year','info_six_month','info_quarter','info_month','info_ten_day','info_week','info_yeartoday','info_monthtoday','info_weektoday','info_today'],'display','none')

Dom.get('custome_period').innerHTML='<?php echo _('Custome Dates')?>'
Dom.setStyle(['info2_other','info2_yesterday','info2_last_m','info2_last_w','info2_all','info2_three_year','info2_year','info2_six_month','info2_quarter','info2_month','info2_ten_day','info2_week','info2_yeartoday','info2_monthtoday','info2_weektoday','info2_today'],'display','none')
Dom.setStyle(['info_'+period,'info2_'+period],'display','')

}




function change_timeseries_type(e, table_id) {

    ids = ['product_sales_history_type_year', 'product_sales_history_type_month', 'product_sales_history_type_week', 'product_sales_history_type_day'];
    Dom.removeClass(ids, 'selected')
    Dom.addClass(this, 'selected')

    type = this.getAttribute('tipo')


    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];

    var request = '&sf=0&type=' + type;
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
};


function post_change_period_actions(r) {
period=r.period;
to=r.to;
from=r.from;


    request = '&from=' + from + '&to=' + to;

    table_id = 1
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
    table_id = 2
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
 
    Dom.get('rtext1').innerHTML = '<img src="art/loading.gif" style="height:12.9px"/> <?php echo _("Processing Request") ?>'
    Dom.get('rtext_rpp1').innerHTML = '';
    Dom.get('rtext2').innerHTML = '<img src="art/loading.gif" style="height:12.9px"/>  <?php echo _("Processing Request") ?>'
    Dom.get('rtext_rpp2').innerHTML = '';
 

    get_sales(from, to)


}


function init() {

    get_sales(Dom.get('from').value, Dom.get('to').value)

    //get_product_element_numbers()
    //get_product_sales_element_numbers();
    Event.addListener(['elements_discontinued', 'elements_nosale', 'elements_private', 'elements_sale', 'elements_historic'], "click", change_elements);
    Event.addListener(['elements_product_sales_discontinued', 'elements_product_sales_nosale', 'elements_product_sales_private', 'elements_product_sales_sale', 'elements_product_sales_historic'], "click", change_product_sales_elements);


    Event.addListener(['details', 'products', 'categories', 'deals', 'sales', 'web','notes'], "click", change_block);


    init_search('products_store');


    Event.addListener('clean_table_filter_show0', "click", show_filter, 0);
    Event.addListener('clean_table_filter_hide0', "click", hide_filter, 0);




    ids = ['product_general', 'product_sales', 'product_stock', 'product_parts', 'product_cats','product_timeline','product_properties'];
    Event.addListener(ids, "click", change_product_view, {
        'table_id': 0,
        'parent': 'family'
    });

    ids = ['product_period_all', 'product_period_three_year', 'product_period_year', 'product_period_yeartoday', 'product_period_six_month', 'product_period_quarter', 'product_period_month', 'product_period_ten_day', 'product_period_week'];
    Event.addListener(ids, "click", change_table_period, {
        'table_id': 0,
        'subject': 'product'
    });
    ids = ['product_avg_totals', 'product_avg_month', 'product_avg_week', "product_avg_month_eff", "product_avg_week_eff"];
    Event.addListener(ids, "click", change_avg, {
        'table_id': 0,
        'subject': 'product'
    });
    //   ids=['table_type_thumbnail','table_type_list'];
    //  Event.addListener(ids, "click",change_table_type,{table_id:0,parent:'family'});

    //  Event.addListener("info_next", "click",next_info_period,0);
    //Event.addListener("info_previous", "click",previous_info_period,0);
    ids = ['products_period_yesterday', 'products_period_last_m', 'products_period_last_w', 'products_period_all', 'products_period_three_year', 'products_period_year', 'products_period_yeartoday', 'products_period_six_month', 'products_period_quarter', 'products_period_month', 'products_period_ten_day', 'products_period_week', 'products_period_monthtoday', 'products_period_weektoday', 'products_period_today'];
    YAHOO.util.Event.addListener(ids, "click", change_sales_period);


    dialog_change_products_display = new YAHOO.widget.Dialog("change_products_display_menu", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_change_products_display.render();
    YAHOO.util.Event.addListener("change_products_display_mode", "click", show_dialog_change_products_display);

    dialog_change_products_table_type = new YAHOO.widget.Dialog("change_products_table_type_menu", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_change_products_table_type.render();
    YAHOO.util.Event.addListener("change_products_table_type", "click", show_dialog_change_products_table_type);

  
   dialog_sales_history_timeline_group = new YAHOO.widget.Dialog("dialog_sales_history_timeline_group", {visible : false,close:true,underlay: "none",draggable:false});
dialog_sales_history_timeline_group.render();
YAHOO.util.Event.addListener("change_sales_history_timeline_group", "click", show_dialog_sales_history_timeline_group);





}


function submit_interval(){
 ids=['products_period_yesterday','products_period_last_m','products_period_last_w','products_period_all','products_period_three_year','products_period_year','products_period_six_month','products_period_quarter','products_period_month','products_period_ten_day','products_period_week','products_period_yeartoday','products_period_monthtoday','products_period_weektoday','products_period_today'];

 Dom.removeClass(ids,"selected")
 Dom.addClass('custome_period',"selected")
/*
Dom.setStyle(['info_yesterday','info_last_m','info_last_w','info_all','info_three_year','info_year','info_six_month','info_quarter','info_month','info_ten_day','info_week','info_yeartoday','info_monthtoday','info_weektoday','info_today'],'display','none')
Dom.setStyle(['info2_yesterday','info2_last_m','info2_last_w','info2_all','info2_three_year','info2_year','info2_six_month','info2_quarter','info2_month','info2_ten_day','info2_week','info2_yeartoday','info2_monthtoday','info2_weektoday','info2_today'],'display','none')
Dom.setStyle(['info_other','info2_other'],'display','')
*/
Dom.setStyle(['waiting_other_invoices','waiting_other_customers','waiting_other_sales','waiting_other_profits','waiting_other_outers'],'display','')
Dom.setStyle(['other_invoices','other_customers','other_sales','other_profits','other_outers'],'display','none')

/*
var request='ar_assets.php?tipo=family_sales_data&family_key='+Dom.get('family_key').value+'&from=' + Dom.get('in').value +'&to=' + Dom.get('out').value
	       //   alert(request)	 
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
	            success:function(o){
	        //    alert(o.responseText);	
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.state==200){
			
			
			
			Dom.get('custome_period').innerHTML=r.formated_period
			dialog_calendar.hide();
			
			Dom.setStyle(['waiting_other_invoices','waiting_other_customers','waiting_other_sales','waiting_other_profits','waiting_other_outers'],'display','none')
Dom.setStyle(['other_invoices','other_customers','other_sales','other_profits','other_outers'],'display','')

			Dom.get('other_sales').innerHTML=r.sales;
						Dom.get('other_profits').innerHTML=r.profits;
						Dom.get('other_customers').innerHTML=r.customers;
						Dom.get('other_outers').innerHTML=r.outers;
						Dom.get('other_invoices').innerHTML=r.invoices;
						
			}else{
                                  
                                  }
   			}
    });

*/
}

//function show_dialog_calendar(){
//	region1 = Dom.getRegion('custome_period'); 
//    region2 = Dom.getRegion('dialog_calendar_splinter'); 
//	var pos =[region1.right-region2.width,region1.bottom]
//	Dom.setXY('dialog_calendar_splinter', pos);
//	dialog_calendar.show();
//}


Event.onDOMReady(init);
Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {trigger:"filter_name0"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });
Event.onContentReady("info_period_menu", function () {
	 var oMenu = new YAHOO.widget.Menu("info_period_menu", { context:["info_period","tr", "br"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 Event.addListener("info_period", "click", oMenu.show, null, oMenu);
    });

Event.onContentReady("rppmenu2", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu2", {trigger:"rtext_rpp2" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
 Event.onContentReady("rppmenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });   