<?php
include_once('common.php');


?>

 var Dom   = YAHOO.util.Dom;
 var Event  =YAHOO.util.Event;






function change_block() {
    ids = ['details', 'departments', 'families', 'products', 'categories', 'deals', 'websites', 'sales','payments'];
    block_ids = ['block_details', 'block_sites', 'block_departments', 'block_families', 'block_products', 'block_categories', 'block_sales', 'block_deals', 'block_websites','block_payments'];
    Dom.setStyle(block_ids, 'display', 'none');
    Dom.setStyle('block_' + this.id, 'display', '');
    Dom.removeClass(ids, 'selected');
    Dom.addClass(this, 'selected');
    
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=store-block_view&value=' + this.id, {});
}


YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {





	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"code", label:"<?php echo _('Code')?>", width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"name", label:"<?php echo _('Name')?>",<?php echo($_SESSION['state']['store']['departments']['view']=='timeline'?'hidden:true,':'')?> width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"families", label:"<?php echo _('Families')?>", width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"active", label:"<?php echo _('Products')?>",  width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    
				     ,{key:"discontinued", label:"<?php echo _('Discontinued')?>",  width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				    ,{key:"sales", label:"<?php echo _('Sales')?>", width:80,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"delta_sales", label:"<?php echo '1y&Delta; '._('Sales')?>", width:80,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}


				    ,{key:"profit", label:"<?php echo _('Profit')?>", width:80,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
//				    ,{key:"aws_p", label:"<?php echo _('Aw S/P')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
//				    ,{key:"awp_p", label:"<?php echo _('Aw P/P')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"surplus", label:"<?php echo _('Surplus')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"optimal", label:"<?php echo _('OK')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"low", label:"<?php echo _('Low')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"critical", label:"<?php echo _('Critical')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"outofstock", label:"<?php echo _('Gone')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_error", label:"<?php echo _('Unknown')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"sales_type", label:"<?php echo _('Sales Type')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='web'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
			    ,{key:"last_update", label:"<?php echo _('Last Update')?>", width:180,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='timeline'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
   				    ,{key:"from", label:"<?php echo _('Since')?>", width:190,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='timeline'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"to", label:"<?php echo _('Until')?>", width:180,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['departments']['view']=='timeline'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				     ];
				     
				     
		request="ar_assets.php?tipo=departments&sf=0&parent=store&parent_key="+Dom.get('store_key').value
	    this.dataSource0 = new YAHOO.util.DataSource(request);
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
			 "name","code","aws_p","awp_p","sales_type","delta_sales","from","last_update","to",
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
    	 this.table0.request=request;
     	this.table0.subscribe("renderEvent", myrenderEvent);

	    
	    this.table0.view='<?php echo$_SESSION['state']['store']['departments']['view']?>';

      	this.table0.filter={key:'<?php echo$_SESSION['state']['store']['departments']['f_field']?>',value:'<?php echo$_SESSION['state']['store']['departments']['f_value']?>'};




	    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"code", label:"<?php echo _('Code')?>", width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"store", label:"<?php echo _('Store')?>",hidden:true, width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"department", label:"<?php echo _('Department')?>",<?php echo($_SESSION['state']['store']['families']['view']=='timeline'?'hidden:true,':'')?> width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				    ,{key:"name", label:"<?php echo _('Name')?>",<?php echo(($_SESSION['state']['store']['families']['view']=='stock' or $_SESSION['state']['store']['families']['view']=='timeline')?'hidden:true,':'')?> width:280,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"active", label:"<?php echo _('Products')?>",  width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['families']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   
				   ,{key:"sales", label:"<?php echo _('Sales')?>", width:90,sortable:true,className:"aright",<?php echo(($_SESSION['state']['store']['families']['view']=='stock' or $_SESSION['state']['store']['families']['view']=='timeline')?'hidden:true,':'')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   
				   
				   ,{key:"delta_sales", label:"<?php echo '1y&Delta; '._('Sales')?>", width:80,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['families']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    
				    ,{key:"profit", label:"<?php echo _('Profit')?>", width:90,sortable:true,className:"aright",<?php echo(($_SESSION['state']['store']['families']['view']=='stock' or $_SESSION['state']['store']['families']['view']=='timeline')?'hidden:true,':'')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"surplus", label:"<?php echo _('Surplus')?>", width:60,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['families']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"optimal", label:"<?php echo _('OK')?>", width:60,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['families']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"low", label:"<?php echo _('Low')?>", width:60,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['families']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"critical", label:"<?php echo _('Critical')?>", width:60,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['families']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"outofstock", label:"<?php echo _('Gone')?>", width:60,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['families']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_error", label:"<?php echo _('Unknown')?>", width:60,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['families']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					
					,{key:"last_update", label:"<?php echo _('Last Update')?>", width:180,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['families']['view']=='timeline'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
   				    ,{key:"from", label:"<?php echo _('Since')?>", width:190,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['families']['view']=='timeline'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"to", label:"<?php echo _('Until')?>", width:180,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['families']['view']=='timeline'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
   
				    
				    
				     ];

		request="ar_assets.php?tipo=families&sf=0&parent=store&parent_key="+Dom.get('store_key').value+"&tableid="+tableid;
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
			 'id',
			 "code",
			 "name","delta_sales","from","last_update","to",
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
    	this.table1.request=request;
        this.table1.table_id=tableid;

     		this.table1.subscribe("renderEvent", families_myrenderEvent);
   		this.table1.getDataSource().sendRequest(null, {
    		success:function(request, response, payload) {

        		if(response.results.length == 1) {
            		get_families_elements_numbers()
            
        		} else {
            		//this.onDataReturnInitializeTable(request, response, payload);
        		}
    		},
    		scope:this.table1,
    		argument:this.table1.getState()
		});

	    
	    this.table1.view='<?php echo$_SESSION['state']['store']['families']['view']?>';
	    this.table1.filter={key:'<?php echo$_SESSION['state']['store']['families']['f_field']?>',value:'<?php echo$_SESSION['state']['store']['families']['f_value']?>'};
		


	    var tableid=2;
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [ 
			 		{key:"code", label:"<?php echo _('Code')?>", width:100,sortable:true, className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"name", label:"<?php echo _('Name')?>",width:400,<?php echo(($_SESSION['state']['store']['products']['view']=='general' )?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
								 ,{key:"smallname", label:"<?php echo _('Name')?>",width:340, sortable:true,className:"aleft",className:"aleft",<?php echo(($_SESSION['state']['store']['products']['view']=='general' or $_SESSION['state']['store']['products']['view']=='timeline')?'hidden:true,':'')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   // ,{key:"formated_record_type", label:"<?php echo _('State')?>",width:100,<?php echo(($_SESSION['state']['store']['products']['view']=='general' or $_SESSION['state']['store']['products']['view']=='stock')?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    	,{key:"price", label:"<?php echo _('Price')?>",width:100,<?php echo(($_SESSION['state']['store']['products']['view']=='general')?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"web", label:"<?php echo _('Web/Sales State')?>",width:150,<?php echo(($_SESSION['state']['store']['products']['view']=='general' or $_SESSION['state']['store']['products']['view']=='stock' )?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   
				   ,{key:"sold", label:"<?php echo _('Sold')?>",width:90,<?php echo($_SESSION['state']['store']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"sales", label:"<?php echo _('Sales')?>",width:90,<?php echo($_SESSION['state']['store']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"delta_sales", label:"<?php echo '1y&Delta; '._('Sales')?>", width:80,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['products']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				    ,{key:"profit", label:"<?php echo _('Profit')?>",width:90,<?php echo($_SESSION['state']['store']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				//    ,{key:"margin", label:"<?php echo _('Margin')?>",width:90,<?php echo($_SESSION['state']['store']['products']['view']=='sales'?'':'hidden:true,')?> sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   
				   
				   ,{key:"stock", label:"<?php echo _('Available')?>", width:65,sortable:true,className:"aright",<?php echo(($_SESSION['state']['store']['products']['view']=='stock' or $_SESSION['state']['store']['products']['view']=='general')  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_state", label:"<?php echo _('State')?>", width:70,sortable:true,className:"aright",<?php echo(($_SESSION['state']['store']['products']['view']=='stock' )  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_forecast", label:"<?php echo _('Forecast')?>", width:70,sortable:true,className:"aright",<?php echo(($_SESSION['state']['store']['products']['view']=='stock' )  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   
				   ,{key:"parts", label:"<?php echo _('Parts')?>",width:130,<?php echo($_SESSION['state']['store']['products']['view']!='parts'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"supplied", label:"<?php echo _('Supplied by')?>",width:130,<?php echo($_SESSION['state']['store']['products']['view']!='parts'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"gmroi", label:"<?php echo _('GMROI')?>", width:100,sortable:true,className:"aright",<?php echo(($_SESSION['state']['store']['products']['view']=='parts' )  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				    //,{key:"dept", label:"<?php echo _('Main Department')?>",width:200,<?php echo($_SESSION['state']['store']['products']['view']!='cats'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"expcode", label:"<?php echo _('Tariff Code')?>",width:160,<?php echo($_SESSION['state']['store']['products']['view']!='cats'?'hidden:true,':'')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"last_update", label:"<?php echo _('Last Update')?>", width:180,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['products']['view']=='timeline'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
   				    ,{key:"from", label:"<?php echo _('Since')?>", width:190,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['products']['view']=='timeline'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"to", label:"<?php echo _('Until')?>", width:180,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['products']['view']=='timeline'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"package_type", label:"<?php echo _('Pkg Type')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['products']['view']=='properties'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"package_weight", label:"<?php echo _('Pkg Weight')?>", width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['products']['view']=='properties'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"package_dimension", label:"<?php echo _('Pkg Dim')?>", width:120,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['products']['view']=='properties'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"package_volume", label:"<?php echo _('Pkg Vol')?>", width:110,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['products']['view']=='properties'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"unit_weight", label:"<?php echo _('Unit Weight')?>", width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['products']['view']=='properties'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"unit_dimension", label:"<?php echo _('Unit Dim')?>", width:120,sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['products']['view']=='properties'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}


			       ];

request="ar_assets.php?tipo=products&sf=0&parent=store&tableid="+tableid+"&parent_key="+Dom.get('store_key').value;
//alert(request)
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
			 'id',"package_type","package_weight","package_dimension","package_volume","unit_weight","unit_dimension"
			 ,"code","price"
			 ,"name","stock","stock_value","from","last_update","to"
			 ,'sales','profit','margin','sold',"parts","supplied","gmroi","family","dept","expcode","smallname","state","web","delta_sales"
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
    	this.table2.request=request;
      	this.table2.subscribe("renderEvent", products_myrenderEvent);
   		this.table2.getDataSource().sendRequest(null, {
    		success:function(request, response, payload) {

        		if(response.results.length == 1) {
            		get_products_elements_numbers()
            
        		} else {
            		//this.onDataReturnInitializeTable(request, response, payload);
        		}
    		},
    		scope:this.table2,
    		argument:this.table2.getState()
		});
	    

	    
	    this.table2.view='<?php echo$_SESSION['state']['store']['products']['view']?>';
	    this.table2.filter={key:'<?php echo$_SESSION['state']['store']['products']['f_field']?>',value:'<?php echo$_SESSION['state']['store']['products']['f_value']?>'};
		

  var tableid=3; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
	    				    {key:"code", label:"<?php echo _('Code')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"name", label:"<?php echo _('Name')?>", width:120,<?php echo($_SESSION['state']['store']['sites']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				    ,{key:"url", label:"<?php echo _('URL')?>", width:280,<?php echo($_SESSION['state']['store']['sites']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"users", label:"<?php echo _('Users')?>", width:100,<?php echo($_SESSION['state']['store']['sites']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"pages", label:"<?php echo _('Pages')?>", width:100,<?php echo($_SESSION['state']['store']['sites']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aright",<?php echo($_SESSION['state']['store']['sites']['view']=='general'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"products", label:"<?php echo _('Products')?>", width:100,<?php echo(($_SESSION['state']['store']['sites']['view']=='products' or  $_SESSION['state']['store']['sites']['view']=='general')?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"out_of_stock", label:"<?php echo _('OoS')?>", width:100,<?php echo($_SESSION['state']['store']['sites']['view']=='products'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"out_of_stock_percentage", label:"%", width:100,<?php echo($_SESSION['state']['store']['sites']['view']=='products'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"pages_products", label:"<?php echo _('Pages w Prods')?>", width:100,<?php echo($_SESSION['state']['store']['sites']['view']=='products'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"pages_out_of_stock", label:"<?php echo _('Pages w OoS')?>", width:100,<?php echo($_SESSION['state']['store']['sites']['view']=='products'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"pages_out_of_stock_percentage", label:"%", width:100,<?php echo($_SESSION['state']['store']['sites']['view']=='products'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

						    
				    
				    
				     ];
request="ar_sites.php?tipo=sites&sf=0&parent=store&tableid="+tableid+"&parent_key="+Dom.get('store_id').value
	    this.dataSource3 = new YAHOO.util.DataSource(request);
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
			 'id','title','code','url','type','site','name','users','pages','pages_products','pages_out_of_stock','pages_out_of_stock_percentage','products','out_of_stock','out_of_stock_percentage'
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
     this.table3.request=request;
     this.table3.subscribe("renderEvent", myrenderEvent);

	    
	    this.table3.filter={key:'<?php echo$_SESSION['state']['store']['sites']['f_field']?>',value:'<?php echo$_SESSION['state']['store']['sites']['f_value']?>'};
		





  var tableid=4; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 

						 						    {key:"site", label:"<?php echo _('Site')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
   
						    ,{key:"code", label:"<?php echo _('Code')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"type", label:"<?php echo _('Type')?>",<?php echo($_SESSION['state']['store']['pages']['view']=='general'?'':'hidden:true,')?> width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"title", label:"<?php echo _('Header Title')?>",<?php echo($_SESSION['state']['store']['pages']['view']=='general'?'':'hidden:true,')?> width:270,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"link_title", label:"<?php echo _('Link Label')?>",<?php echo($_SESSION['state']['store']['pages']['view']!='products'?'':'hidden:true,')?>  width:270,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"users", label:"<?php echo _('Users')?>",<?php echo($_SESSION['state']['store']['pages']['view']=='requests'?'':'hidden:true,')?>width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"visitors", label:"<?php echo _('Visitors')?>",<?php echo($_SESSION['state']['store']['pages']['view']=='requests'?'':'hidden:true,')?> width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"sessions", label:"<?php echo _('Sessions')?>",<?php echo($_SESSION['state']['store']['pages']['view']=='requests'?'':'hidden:true,')?> width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"requests", label:"<?php echo _('Requests')?>",<?php echo($_SESSION['state']['store']['pages']['view']=='requests'?'':'hidden:true,')?> width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					    ,{key:"products", label:"<?php echo _('Products')?>",<?php echo($_SESSION['state']['store']['pages']['view']=='products'?'':'hidden:true,')?> width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

					,{key:"list_products", label:"<?php echo _('In list')?>",<?php echo($_SESSION['state']['store']['pages']['view']=='products'?'':'hidden:true,')?> width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"button_products", label:"<?php echo _('Buttons')?>",<?php echo($_SESSION['state']['store']['pages']['view']=='products'?'':'hidden:true,')?> width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"products_out_of_stock", label:"<?php echo _('No available')?>",<?php echo($_SESSION['state']['store']['pages']['view']=='products'?'':'hidden:true,')?> width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"percentage_products_out_of_stock", label:"%",<?php echo($_SESSION['state']['store']['pages']['view']=='products'?'':'hidden:true,')?> width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
		    				    ,{key:"products_sold_out", label:"<?php echo _('Sold Out')?>",<?php echo($_SESSION['state']['store']['pages']['view']=='products'?'':'hidden:true,')?> width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				    
				     ];

request="ar_sites.php?tipo=pages&sf=0&parent=store&sf=0&tableid="+tableid+"&parent_key="+Dom.get('store_id').value
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
			 'site','id','title','code','url','type','link_title','visitors','sessions','requests','users','products','products_out_of_stock','percentage_products_out_of_stock',
			 'list_products','button_products','products_sold_out','flag'
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
          this.table4.request=request;
     this.table4.subscribe("renderEvent", myrenderEvent);

	    
	    this.table4.filter={key:'<?php echo$_SESSION['state']['store']['pages']['f_field']?>',value:'<?php echo$_SESSION['state']['store']['pages']['f_value']?>'};
		



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
				      ,{key:"date", label:"<?php echo _('Date')?>",className:"aright",width:120,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"time", label:"<?php echo _('Time')?>",className:"aleft",width:70}
				      ,{key:"handle", label:"<?php echo _('Author')?>",className:"aleft",width:100,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"note", formatter:this.prepare_note,label:"<?php echo _('Notes')?>",className:"aleft",width:520}
                      ,{key:"delete", label:"",width:12,sortable:false,action:'delete',object:'store_history'}
                      ,{key:"edit", label:"",width:12,sortable:false,action:'edit',object:'store_history'}

					   ];
		request="ar_history.php?tipo=store_history&parent=store&parent_key="+Dom.get('store_key').value+"&sf=0&tableid="+tableid
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
									      rowsPerPage    : <?php echo$_SESSION['state']['store']['history']['nr']?>,containers : 'paginator5', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',alwaysVisible:false,
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info5'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['store']['history']['order']?>",
									 dir: "<?php echo$_SESSION['state']['store']['history']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    	this.table5.handleDataReturnPayload =myhandleDataReturnPayload;
	        this.table5.doBeforeSortColumn = mydoBeforeSortColumn;
	        this.table5.doBeforePaginatorChange = mydoBeforePaginatorChange;
		    this.table5.filter={key:'<?php echo$_SESSION['state']['store']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['store']['history']['f_value']?>'};

	        this.table5.subscribe("cellMouseoverEvent", highlightEditableCell);
	        this.table5.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	        this.table5.subscribe("cellClickEvent", onCellClick);            
			this.table5.table_id=tableid;
     		this.table5.subscribe("renderEvent", myrenderEvent);
     		
     		
     		
     		
     		
		    var tableid=6;
		    var tableDivEL="table"+tableid;

  			var ColumnDefs = [
				      {key:"date", label:"<?php echo _('Date')?>", width:200,sortable:false,className:"aright"}
				      ,{key:"invoices", label:"<?php echo _('Invoices')?>", width:100,sortable:false,className:"aright"}
				      ,{key:"customers", label:"<?php echo _('Customers')?>", width:100,sortable:false,className:"aright"}
				      ,{key:"sales", label:"<?php echo _('Sales')?>", width:100,sortable:false,className:"aright"}
					];

		 
		    request="ar_reports.php?tipo=assets_sales_history&sf=0&scope=assets&parent=store&parent_key="+Dom.get('store_key').value+"&tableid="+tableid+'&from='+Dom.get('from').value+'&to='+Dom.get('to').value;
		  	this.dataSource6 = new YAHOO.util.DataSource(request);
	    	this.dataSource6.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    	this.dataSource6.connXhrMode = "queueRequests";
 
	    	this.dataSource6.responseSchema = {
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
			
	    this.table6 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource6, {
							 //draggableColumns:true,
							
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage:<?php echo$_SESSION['state']['store']['sales_history']['nr']?>,containers : 'paginator6', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info6'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['store']['sales_history']['order']?>",
									 dir: "<?php echo$_SESSION['state']['store']['sales_history']['order_dir']?>"
								     }
							   ,dynamicData : true  

						     }
						     );
						     
						    
						     
	    this.table6.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table6.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table6.doBeforePaginator = mydoBeforePaginatorChange;
      this.table6.request=request;
  this.table6.table_id=tableid;
     this.table6.subscribe("renderEvent", myrenderEvent);

		this.table6.filter={key:'<?php echo$_SESSION['state']['store']['sales_history']['f_field']?>',value:'<?php echo$_SESSION['state']['store']['sales_history']['f_value']?>'};

     		  var tableid=7;
	    var tableDivEL="table"+tableid;


	    var ColumnDefs = [ 
				    {key:"code", label:"<?php echo _('Code')?>", width:60,sortable:true, className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"name", label:"<?php echo _('Name')?>",width:440, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   		//		   ,{key:"state", label:"<?php echo _('State')?>",width:70, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   ,{key:"sold", label:"<?php echo _('Sold')?>",width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"sales", label:"<?php echo _('Sales')?>",width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit", label:"<?php echo _('Profit')?>",width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   
							       ];
	request="ar_assets.php?tipo=SUSPENDED_department_sales_report&tableid="+tableid+"&parent=store&sf=0&parent_key="+Dom.get('store_key').value+'&from='+Dom.get('from').value+'&to='+Dom.get('to').value;
	//alert(request)
	 this.dataSource7 = new YAHOO.util.DataSource(request);
	    this.dataSource7.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource7.connXhrMode = "queueRequests";
 
	    this.dataSource7.responseSchema = {
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
			 ,'sales','profit','margin','sold',"parts","supplied","gmroi","department","dept","expcode","state","web","smallname","delta_sales"
			 ]};
	    


	    this.table7 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource7, {
							 //draggableColumns:true,
							// formatRow: myRowFormatter,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage:<?php echo$_SESSION['state']['store']['family_sales']['nr']?>,containers : 'paginator7', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info7'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['store']['family_sales']['order']?>",
									 dir: "<?php echo$_SESSION['state']['store']['family_sales']['order_dir']?>"
								     }
							   ,dynamicData : true  

						     }
						     );
	    this.table7.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table7.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table7.doBeforePaginator = mydoBeforePaginatorChange;
      	this.table7.request=request;
  		this.table7.table_id=tableid;
    	 this.table7.subscribe("renderEvent", myrenderEvent);

		this.table7.filter={key:'<?php echo$_SESSION['state']['store']['family_sales']['f_field']?>',value:'<?php echo$_SESSION['state']['store']['family_sales']['f_value']?>'};

	 
     		
     		
    var tableid=8;
	    var tableDivEL="table"+tableid;


	    var ColumnDefs = [ 
				    {key:"code", label:"<?php echo _('Code')?>", width:60,sortable:true, className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"name", label:"<?php echo _('Name')?>",width:370, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   				   ,{key:"state", label:"<?php echo _('State')?>",width:70, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   ,{key:"sold", label:"<?php echo _('Sold')?>",width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"sales", label:"<?php echo _('Sales')?>",width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit", label:"<?php echo _('Profit')?>",width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   
							       ];
request="ar_assets.php?tipo=SUSPENDED_family_sales_report&tableid="+tableid+"&parent=store&sf=0"+'&parent_key='+Dom.get('store_key').value+'&from='+Dom.get('from').value+'&to='+Dom.get('to').value;
	//alert(request)
	 this.dataSource8 = new YAHOO.util.DataSource(request);
	    this.dataSource8.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource8.connXhrMode = "queueRequests";
 
	    this.dataSource8.responseSchema = {
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
			 ,'sales','profit','margin','sold',"parts","supplied","gmroi","department","dept","expcode","state","web","smallname","delta_sales"
			 ]};
	    


	    this.table8 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource8, {
							 //draggableColumns:true,
							// formatRow: myRowFormatter,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage:<?php echo$_SESSION['state']['store']['family_sales']['nr']?>,containers : 'paginator8', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info8'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['store']['family_sales']['order']?>",
									 dir: "<?php echo$_SESSION['state']['store']['family_sales']['order_dir']?>"
								     }
							   ,dynamicData : true  

						     }
						     );
	    this.table8.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table8.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table8.doBeforePaginator = mydoBeforePaginatorChange;
      this.table8.request=request;
  this.table8.table_id=tableid;
     this.table8.subscribe("renderEvent", myrenderEvent);

		this.table8.filter={key:'<?php echo$_SESSION['state']['store']['family_sales']['f_field']?>',value:'<?php echo$_SESSION['state']['store']['family_sales']['f_value']?>'};

	 
	

    var tableid=9;
	    var tableDivEL="table"+tableid;

	    var ColumnDefs = [ 
				    {key:"code", label:"<?php echo _('Code')?>", width:60,sortable:true, className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"name", label:"<?php echo _('Name')?>",width:370, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   	,{key:"state", label:"<?php echo _('State')?>",width:70, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   	,{key:"sold", label:"<?php echo _('Sold')?>",width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"sales", label:"<?php echo _('Sales')?>",width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit", label:"<?php echo _('Profit')?>",width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   
							       ];
request="ar_assets.php?tipo=SUSPENDED_product_sales_report&tableid="+tableid+"&parent=store&sf=0"+'&parent_key='+Dom.get('store_key').value+'&from='+Dom.get('from').value+'&to='+Dom.get('to').value;
	//alert(request)
	 this.dataSource9 = new YAHOO.util.DataSource(request);
	    this.dataSource9.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource9.connXhrMode = "queueRequests";
 
	    this.dataSource9.responseSchema = {
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
	    


	    this.table9 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource9, {
							 //draggableColumns:true,
							 formatRow: myRowFormatter,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage:<?php echo$_SESSION['state']['store']['product_sales']['nr']?>,containers : 'paginator9', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info9'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['store']['product_sales']['order']?>",
									 dir: "<?php echo$_SESSION['state']['store']['product_sales']['order_dir']?>"
								     }
							   ,dynamicData : true  

						     }
						     );
	    this.table9.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table9.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table9.doBeforePaginator = mydoBeforePaginatorChange;
      this.table9.request=request;
  this.table9.table_id=tableid;
     this.table9.subscribe("renderEvent", myrenderEvent);

		this.table9.filter={key:'<?php echo$_SESSION['state']['store']['product_sales']['f_field']?>',value:'<?php echo$_SESSION['state']['store']['product_sales']['f_value']?>'};

	    


     	 
	    
	    var tableid=10; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var productsColumnDefs = [
	    
	    				       {key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 

                                        ,{key:"code", label:"<?php echo _('Code')?>", width:110,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                                     ,{key:"description", label:"<?php echo _('Description')?>", width:350,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
							,{key:"duration", label:"<?php echo _('Duration')?>",  width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

		,{key:"orders", label:"<?php echo _('Orders')?>",  width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"customers", label:"<?php echo _('Customers')?>",  width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				 
				 ];
	    //?tipo=products&tid=0"
	    
	    request="ar_deals.php?tipo=deals&parent=store&sf=0&parent_key="+Dom.get('store_id').value+'&tableid=10&referrer=store'
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
									      rowsPerPage    : <?php echo $_SESSION['state']['store']['offers']['nr']?>,containers : 'paginator10', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info10'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo $_SESSION['state']['store']['offers']['order']?>",
									 dir: "<?php echo $_SESSION['state']['store']['offers']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
		this.table10.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table10.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table10.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table10.request=request;
  		this.table10.table_id=tableid;
     	this.table10.subscribe("renderEvent", offers_myrenderEvent);
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
	  
	    this.table10.filter={key:'<?php echo $_SESSION['state']['store']['offers']['f_field']?>',value:'<?php echo $_SESSION['state']['store']['offers']['f_value']?>'};
	    
	    
	    
	    var tableid=11; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var productsColumnDefs = [
	    
	    			{key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
                	,{key:"code", label:"<?php echo _('Code')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
	    			
					,{key:"name", label:"<?php echo _('Name')?>", width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"duration", label:"<?php echo _('Duration')?>",  width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
		,{key:"orders", label:"<?php echo _('Orders')?>",  width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"customers", label:"<?php echo _('Customers')?>",  width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				 
				 ];
	    //?tipo=products&tid=0"
	    request='ar_deals.php?tipo=campaigns&parent=store&sf=0&parent_key='+Dom.get('store_id').value+'&tableid='+tableid+'&referrer=store'
	 //  alert(request)
	    this.dataSource11 = new YAHOO.util.DataSource(request);
	    this.dataSource11.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource11.connXhrMode = "queueRequests";
	    this.dataSource11.responseSchema = {
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
		
		fields: ["name","key","code","duration","used","orders","customers"]};
		

	  this.table11 = new YAHOO.widget.DataTable(tableDivEL, productsColumnDefs,
								   this.dataSource11
								 , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 //,initialLoad:false
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo $_SESSION['state']['store']['campaigns']['nr']?>,containers : 'paginator11', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info11'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo $_SESSION['state']['store']['campaigns']['order']?>",
									 dir: "<?php echo $_SESSION['state']['store']['campaigns']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
		this.table11.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table11.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table11.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table11.request=request;
  		this.table11.table_id=tableid;
     	this.table11.subscribe("renderEvent", campaigns_myrenderEvent);
		this.table11.getDataSource().sendRequest(null, {
		    success: function(request, response, payload) {
		        if (response.results.length == 1) {
		            campaigns_myrenderEvent()

		        } else {
		             this.onDataReturnInitializeTable(request, response, payload);
		        }
		    },
		    scope: this.table11,
		    argument: this.table11.getState()
		});	  
	    this.table11.filter={key:'<?php echo $_SESSION['state']['store']['campaigns']['f_field']?>',value:'<?php echo $_SESSION['state']['store']['campaigns']['f_value']?>'};

	    var tableid=12; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var productsColumnDefs = [
	    
                	{key:"id", label:"<?php echo _('Id')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
	    			
					,{key:"method", label:"<?php echo _('Method')?>", width:140,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"date", label:"<?php echo _('Date')?>",  width:170,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
		,{key:"amount", label:"<?php echo _('Amount')?>",  width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
						,{key:"author", label:"<?php echo _('Author')?>",  width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

	,{key:"notes", label:"<?php echo _('Notes')?>",  width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				 
				 ];
	    //?tipo=products&tid=0"
	    request='ar_payments.php?tipo=payments&parent=store&sf=0&parent_key='+Dom.get('store_id').value+'&tableid='+tableid
	//  alert(request)
	    this.dataSource12 = new YAHOO.util.DataSource(request);
	    this.dataSource12.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource12.connXhrMode = "queueRequests";
	    this.dataSource12.responseSchema = {
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
		
		fields: ["id","method","date","amount","author","notes"]};
		

	  this.table12 = new YAHOO.widget.DataTable(tableDivEL, productsColumnDefs,
								   this.dataSource12
								 , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 //,initialLoad:false
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo $_SESSION['state']['store']['payments']['nr']?>,containers : 'paginator12', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info12'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo $_SESSION['state']['store']['payments']['order']?>",
									 dir: "<?php echo $_SESSION['state']['store']['payments']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
		this.table12.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table12.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table12.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table12.request=request;
  		this.table12.table_id=tableid;
     	this.table12.subscribe("renderEvent", campaigns_myrenderEvent);
	
	    this.table12.filter={key:'<?php echo $_SESSION['state']['store']['payments']['f_field']?>',value:'<?php echo $_SESSION['state']['store']['payments']['f_value']?>'};

	


	};
	get_thumbnails(4)
	get_thumbnails(2)
	get_thumbnails(1)
	get_thumbnails(0)
    });




function init() {

	
    init_search('orders_store');



    YAHOO.util.Event.addListener('clean_table_filter_show0', "click", show_filter, 0);
    YAHOO.util.Event.addListener('clean_table_filter_hide0', "click", hide_filter, 0);
  

    Event.addListener(['details', 'changelog'], "click", change_block);





    var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0", "f_container0", oACDS);
    oAutoComp.minQueryLength = 0;

}


YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("rppmenu0", function() {
    var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {
        trigger: "rtext_rpp0"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);
});

YAHOO.util.Event.onContentReady("filtermenu0", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {
        trigger: "filter_name0"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);


});
