<?php include_once('common.php');




?>
var Dom   = YAHOO.util.Dom;
var link='report_out_of_stock.php';



 
		
	
			

 
YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {



	     //START OF THE TABLE=========================================================================================================================

		var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;


var CustomersColumnDefs = [
				    
				    {key:"sku", label:"<?php echo _('SKU')?>", width:50,sortable:true, className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"used_in", label:"<?php echo _('Products')?>",width:160, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
	                ,{key:"date", label:"<?php echo _('Date')?>",width:125, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"reporter", label:"<?php echo _('Reporter')?>",width:50, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"note", label:"<?php echo _('Notes')?>",width:265, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					 ];
	   
	   request="ar_reports.php?tipo=transactions_parts_marked_as_out_of_stock"
	 //  alert(request)
	    			    this.dataSource0 = new YAHOO.util.DataSource(request);

	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
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
		fields: [
		'sku','used_in','date','reporter','note'
			 ]};
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['report_part_out_of_stock']['transactions']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['report_part_out_of_stock']['transactions']['order']?>",
									 dir: "<?php echo$_SESSION['state']['report_part_out_of_stock']['transactions']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.view='<?php echo$_SESSION['state']['customers']['table']['view']?>';
	    this.table0.filter={key:'<?php echo$_SESSION['state']['report_part_out_of_stock']['transactions']['f_field']?>',value:'<?php echo$_SESSION['state']['report_part_out_of_stock']['transactions']['f_value']?>'};
	  //  YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)
	
	
	 //START OF THE TABLE=========================================================================================================================

		var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;


var CustomersColumnDefs1 = [
				    
				    {key:"sku", label:"<?php echo _('SKU')?>", width:60,sortable:true, className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"used_in", label:"<?php echo _('Products')?>",width:180, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
	                ,{key:"date", label:"<?php echo _('Last Date')?>",width:170, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"orders", label:"<?php echo _('Orders')?>",width:70, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"customers", label:"<?php echo _('Customers')?>",width:70, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					 ];
	   
	    			    this.dataSource1 = new YAHOO.util.DataSource("ar_reports.php?tipo=parts_marked_as_out_of_stock&tableid=1");

	    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    this.dataSource1.responseSchema = {
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
		fields: [
		'sku','used_in','date','orders','customers'
			 ]};
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs1,
								   this.dataSource1
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['report_part_out_of_stock']['parts']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['report_part_out_of_stock']['parts']['order']?>",
									 dir: "<?php echo$_SESSION['state']['report_part_out_of_stock']['parts']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table1.view='<?php echo$_SESSION['state']['customers']['table']['view']?>';
	    this.table1.filter={key:'<?php echo$_SESSION['state']['report_part_out_of_stock']['parts']['f_field']?>',value:'<?php echo$_SESSION['state']['report_part_out_of_stock']['parts']['f_value']?>'};
	    //YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)
	
	
	};
    });
   
    


function change_view(tipo){
  
    Dom.setStyle(['transactions','parts'],'display','none');
    Dom.setStyle(tipo,'display','');
 Dom.removeClass(['transactions_tab','parts_tab'],'selected')
  Dom.addClass(tipo+'_tab','selected')
 //alert(tipo) 
 
 YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=report_part_out_of_stock-view&value=' + escape(tipo) ,{success:function(o) {}});

//alert('ar_sessions.php?tipo=update&keys=report_part_out_of_stock-view=&value=' + escape(tipo) )
}


function export_data(){
    o=Dom.get('export');
    output=o.getAttribute('output');
    location.href='export.php?ar_file=ar_reports&tipo=customers&output='+output;
    
}


function init(){
get_out_of_stock_data()
get_out_of_stock_customer_data();
}



function get_out_of_stock_data(){

var request='ar_reports.php?tipo=out_of_stock_data&from=' + Dom.get('from').value +'&to=' + Dom.get('to').value
	          //alert(request)	 
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
	            success:function(o){
	            //alert(o.responseText);	
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.state==200){
			Dom.get('number_out_of_stock_parts').innerHTML=r.number_out_of_stock_parts;
						Dom.get('number_out_of_stock_dn').innerHTML=r.number_out_of_stock_dn;
			}else{
                                  
                                  }
   			}
    });


}


function get_out_of_stock_customer_data(){

var request='ar_reports.php?tipo=out_of_stock_customer_data&from=' + Dom.get('from').value +'&to=' + Dom.get('to').value
	          //alert(request)	 
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
	            success:function(o){
	            //alert(o.responseText);	
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.state==200){
			Dom.get('number_out_of_stock_customers').innerHTML=r.number_out_of_stock_customers;
			}else{
                                  
                                  }
   			}
    });


}

YAHOO.util.Event.onDOMReady(init);


YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {  trigger: "filter_name0"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });


YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
    
    YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {  trigger: "filter_name1"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });


YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });



