<?include_once('../common.php');?>
    var plot='<?=$_REQUEST['current_plot']?>';

YAHOO.util.Event.addListener(window, "load", function() {
	tables = new function() {
		
		this.orderLink=  function(el, oRecord, oColumn, oData) {
		    var url="order.php?id="+oRecord.getData("id");
		    el.innerHTML = oData.link(url);
		};
		this.customerLink=  function(el, oRecord, oColumn, oData) {
		    var url="customer.php?id="+oRecord.getData("customer_id");
		    el.innerHTML = oData.link(url);
		};
		this.date=  function(el, oRecord, oColumn, oData) {
		    el.innerHTML = oRecord.getData("date");
		} ;  
		
		<?if($LU->checkRight(ORDER_VIEW)){?>


		    var tableid=0;
		    var tableDivEL="table"+tableid;
		    var ColumnDefs = [
				      {key:"public_id", label:"<?=_('Number')?>", width:100,sortable:true,formatter:this.orderLink,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"tipo", label:"<?=_('Type')?>",width:120, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"customer_name", label:"<?=_('Customer')?>", width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"date_index", label:"<?=_('Date')?>", width:200,formatter:this.date,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"dispached", label:"<?=_('Dispached')?>",width:80, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"undispached", label:"<?='&Delta;'._('Ordered')?>", width:80, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ];
		    
		    
		    
		    this.dataSource0 = new YAHOO.util.DataSource("ar_orders.php?tipo=withproduct&tableid="+tableid);
		    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
		    this.dataSource0.connXhrMode = "queueRequests";
		    this.dataSource0.responseSchema = {
			resultsList: "resultset.data", 
			metaFields: {
			    rowsPerPage:"resultset.records_perpage",
			    sort_key:"resultset.sort_key",
			    sort_dir:"resultset.sort_dir",
			    tableid:"resultset.tableid",
			    filter_msg:"resultset.filter_msg",
			    totalRecords: "resultset.total_records"
			},
			
			fields: [
				 "id","public_id","customer_name","tipo","date_index","date","dispached","undispached"
				 ]};
	    
		    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
							     this.dataSource0, {
								 //draggableColumns:true,
								 renderLoopSize: 50,generateRequest : myRequestBuilder
								 ,paginator : new YAHOO.widget.Paginator({
									 rowsPerPage:<?=$_SESSION['state']['product']['orders']['nr']?>,containers : 'paginator', 
									 pageReportTemplate : '(<?=_('Page')?> {currentPage} <?=_('of')?> {totalPages})',
									 previousPageLinkLabel : "<",
									 nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
									 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
								     })
								 
								 ,sortedBy : {
								     key: "<?=$_SESSION['state']['product']['orders']['order']?>",
								     dir: "<?=$_SESSION['state']['product']['orders']['order_dir']?>"
								 }
								 ,dynamicData : true
								 
							     }
							     );
		    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
		    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
		    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    

		    <?}?>
		    <?if($LU->checkRight(CUST_VIEW) ){?>
		    var tableid=1;
		    var tableDivEL="table"+tableid;
		    
		    var ColumnDefs = [
				      {key:"customer_name", label:"<?=_('Customer')?>",width:250, sortable:true,formatter:this.customerLink,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"orders", label:"<?=_('Orders')?>",width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"dispached", label:"<?=_('Dispached')?>",width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"todispach", label:"<?=_('To Dispach')?>",width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"nodispached", label:"<?=_('Undispached')?>", width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"charged", label:"<?=_('Charged')?>", width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ];
		    

		    this.dataSource1 = new YAHOO.util.DataSource("ar_orders.php?tipo=withcustomerproduct&tid="+tableid);
		    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
		    this.dataSource1.connXhrMode = "queueRequests";
		    this.dataSource1.responseSchema = {
			resultsList: "resultset.data", 
			metaFields: {
			    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
			    sort_dir:"resultset.sort_dir",
			    tableid:"resultset.tableid",
			    filter_msg:"resultset.filter_msg",
			    totalRecords: "resultset.total_records"
			},
			
			fields: [
				  "customer_id","customer_name","dispached","nodispached","charged","todispach","orders"
				 ]};
	    
		    this.table1 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
							     this.dataSource1, {
								 //draggableColumns:true,
								 renderLoopSize: 50,generateRequest : myRequestBuilder
								 ,paginator : new YAHOO.widget.Paginator({
									 rowsPerPage:<?=$_SESSION['state']['product']['customers']['nr']?>,containers : 'paginator', 
									 pageReportTemplate : '(<?=_('Page')?> {currentPage} <?=_('of')?> {totalPages})',
									 previousPageLinkLabel : "<",
									 nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
									 lastPageLinkLabel :">>",alwaysVisible:false
									 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
								     })
								 
								 ,sortedBy : {
								     key: "<?=$_SESSION['state']['product']['customers']['order']?>",
									 dir: "<?=$_SESSION['state']['product']['customers']['order_dir']?>"
								 }
								 ,dynamicData : true
								 
							     }
							     );
		    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
		    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
		    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    <?}?>
			<?if($LU->checkRight(PROD_STK_VIEW)){?>
		    var tableid=2;
		    var tableDivEL="table"+tableid;
		    var ColumnDefs = [
				      {key:"stock", label:"<?=_('Stock')?>", width:60,sortable:false,className:"aright"}
				      ,{key:"available", label:"<?=_('Available')?>", width:60,sortable:false,className:"aright"}
				      ,{key:"operation", label:"<?=_('Operation')?>",width:400,sortable:false,className:"aleft"}
				      ,{key:"op_date", label:"<?=_('Date')?>",width:300, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ];
		    
		    
		    this.dataSource2 = new YAHOO.util.DataSource("ar_assets.php?tipo=stock_history&tid="+tableid);
		    this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
		    this.dataSource2.connXhrMode = "queueRequests";
		    this.dataSource2.responseSchema = {
			resultsList: "resultset.data", 
			metaFields: {
			    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
			    sort_dir:"resultset.sort_dir",
			    tableid:"resultset.tableid",
			    filter_msg:"resultset.filter_msg",
			    totalRecords: "resultset.total_records"
			},
			
			fields: [
				 "id","stock","operation","op_date","available"

				 ]};
	    
		    this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
							     this.dataSource2, {
								 //draggableColumns:true,
								 renderLoopSize: 50,generateRequest : myRequestBuilder
								 ,paginator : new YAHOO.widget.Paginator({
									 rowsPerPage:<?=$_SESSION['state']['product']['stock_history']['nr']?>,containers : 'paginator', 
									 pageReportTemplate : '(<?=_('Page')?> {currentPage} <?=_('of')?> {totalPages})',
									 previousPageLinkLabel : "<",
									 nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
									 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
								     })
								 
								 ,sortedBy : {
								     key: "<?=$_SESSION['state']['product']['stock_history']['order']?>",
									 dir: "<?=$_SESSION['state']['product']['stock_history']['order_dir']?>"
								 }
								 ,dynamicData : true
								 
							     }
							     );
		    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
		    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
		    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;



		    <?}?>


	    
	    };
    });

function init(){
     var Event = YAHOO.util.Event;
     var Dom   = YAHOO.util.Dom;

     var change_view = function (e){
	 block=this.getAttribute('block');
	 state=this.getAttribute('state');
	 new_title=this.getAttribute('atitle');
	 old_title=this.getAttribute('title');

	 this.setAttribute('title',new_title);
	 this.setAttribute('atitle',old_title);
	 
	 if(state==1){
	     Dom.get('block_'+block).style.display='none';
	     this.setAttribute('state',0);
	     YAHOO.util.Dom.setStyle('but_logo_'+block, 'opacity', .2);
	     YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=product-display-'+block+'&value=0');
	 }else{
	     Dom.get('block_'+block).style.display='';
	     this.setAttribute('state',1);
	     YAHOO.util.Dom.setStyle('but_logo_'+block, 'opacity', 1);
	     YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=product-display-'+block+'&value=1ar');
	     
	 }

     }


  var change_plot = function (e){

      //      alert(plot)
      Dom.get("the_plot").src='plot.php?tipo='+this.id;
      this.className='selected';
      Dom.get(plot).className='opaque';
      plot=this.id;
     }

     var ids = ["change_view_details","change_view_plot","change_view_orders","change_view_customers","change_view_stock_history"]; 
     Event.addListener(ids,"click",change_view);
     var ids = ["product_week_sales","product_month_sales","product_quarter_sales","product_year_sales","product_week_outers","product_week_outers" ,"product_week_outers","product_week_outers","product_stock_history"]; 
     Event.addListener(ids,"click",change_plot);

}
 YAHOO.util.Event.onDOMReady(init);