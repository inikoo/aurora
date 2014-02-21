<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2012 LW
include_once('common.php');
?>
var Dom   = YAHOO.util.Dom;
var Event   = YAHOO.util.Event;






var period='period_<?php echo $_SESSION['state']['supplier_categories']['period']?>';
var avg='avg_<?php echo $_SESSION['state']['supplier_categories']['avg']?>';

var  subcategories_period_ids=['subcategories_period_all',
 'subcategories_period_yesterday',
 'subcategories_period_last_w',
 'subcategories_period_last_m','subcategories_period_three_year','subcategories_period_year','subcategories_period_yeartoday','subcategories_period_six_month','subcategories_period_quarter','subcategories_period_month','subcategories_period_ten_day','subcategories_period_week','subcategories_period_monthtoday','subcategories_period_weektoday','subcategories_period_today'];



var dialog_new_category;

function change_history_elements(e, table_id) {
    ids = ['elements_Changes', 'elements_Assign'];
    if (Dom.hasClass(this, 'selected')) {

        var number_selected_elements = 0;
        for (i in ids) {
            if (Dom.hasClass(ids[i], 'selected')) {
                number_selected_elements++;
            }
        }

        if (number_selected_elements > 1) {
            Dom.removeClass(this, 'selected')

        }

    } else {
        Dom.addClass(this, 'selected')

    }
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    var request = '';
    for (i in ids) {
        if (Dom.hasClass(ids[i], 'selected')) {
            request = request + '&' + ids[i] + '=1'
        } else {
            request = request + '&' + ids[i] + '=0'

        }
    }
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}






function change_block(){
ids=['subcategories','subjects','overview','history','sales','no_assigned'];
block_ids=['block_subcategories','block_subjects','block_overview','block_history','block_sales','block_no_assigned'];
Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

if(!(this.id=='sales' || (this.id=='overview'))){
Dom.setStyle('calendar_container','display','none')
}else{
Dom.setStyle('calendar_container','display','')

}

YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=supplier_categories-'+Dom.get('state_type').value+'_block_view&value='+this.id ,{});
}


function change_sales_sub_block(o) {
    Dom.removeClass(['plot_supplier_sales', 'supplier_timeseries', 'supplier_product_sales'], 'selected')
    Dom.addClass(o, 'selected')

    Dom.setStyle(['sub_block_plot_supplier_sales', 'sub_block_supplier_timeseries','sub_block_supplier_product_sales'], 'display', 'none')
    Dom.setStyle('sub_block_' + o.id, 'display', '')
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=supplier_categories-sales_block&value=' + o.id, {});
}

YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


	    var tableid=0;
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [ 
	      				 {key:"id", label:"<?php echo _('Id')?>", hidden:true, width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       	,{key:"code", label:"<?php echo _('Code')?>",width:80, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       	,{key:"name", label:"<?php echo _('Name')?>",<?php echo(($_SESSION['state']['supplier_categories']['suppliers']['view']=='general' or $_SESSION['state']['supplier_categories']['suppliers']['view']=='contact' or $_SESSION['state']['supplier_categories']['suppliers']['view']=='products' or $_SESSION['state']['supplier_categories']['suppliers']['view']=='sales')?'':'hidden:true,')?> width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       	,{key:"contact",<?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='contact'?'hidden:true,':'')?> label:"<?php echo _('Contact')?>", width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       	,{key:"email", label:"<?php echo _('Email')?>",<?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='contact'?'hidden:true,':'')?> width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       	,{key:"location", label:"<?php echo _('Location')?>",<?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='general'?'hidden:true,':'')?> width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       	,{key:"tel",<?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='contact'?'hidden:true,':'')?> label:"<?php echo _('Tel')?>", width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       	,{key:"pending_pos", <?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='general'?'hidden:true,':'')?> label:"<?php echo _('P POs')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       	,{key:"for_sale", <?php echo(($_SESSION['state']['supplier_categories']['suppliers']['view']=='products' or  $_SESSION['state']['supplier_categories']['suppliers']['view']=='general') ?'':'hidden:true,')?> label:"<?php echo _('Products')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       	,{key:"discontinued",<?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='products'?'hidden:true,':'')?>  label:"<?php echo _('Discontinued')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       	,{key:"stock_value",<?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='money'?'hidden:true,':'')?>  label:"<?php echo _('Stock Value')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
						,{key:"high",<?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='stock'?'hidden:true,':'')?>  label:"<?php echo _('High')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 	,{key:"normal",<?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='stock'?'hidden:true,':'')?>  label:"<?php echo _('Normal')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 	,{key:"low", <?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='stock'?'hidden:true,':'')?> label:"<?php echo _('Low')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 	,{key:"critical", <?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='stock'?'hidden:true,':'')?> label:"<?php echo _('Critical')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 	,{key:"outofstock", <?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='stock'?'hidden:true,':'')?> label:"<?php echo _('Out of Stock')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
						,{key:"required", <?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='sales'?'hidden:true,':'')?> label:"<?php echo _('Required')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
						,{key:"sold", <?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='sales'?'hidden:true,':'')?> label:"<?php echo _('Sold')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
						,{key:"sales", <?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='sales'?'hidden:true,':'')?> label:"<?php echo _('Sales')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
						,{key:"delta_sales",<?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='sales'?'hidden:true,':'')?> label:"<?php echo _('1y').' &Delta;'._('Sales')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
						,{key:"profit", <?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='profit'?'hidden:true,':'')?> label:"<?php echo _('Profit')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       	,{key:"profit_after_storing", <?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='profit'?'hidden:true,':'')?> label:"<?php echo _('PaS')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 	,{key:"cost", <?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='profit'?'hidden:true,':'')?> label:"<?php echo _('Cost')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 	,{key:"margin", <?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='profit'?'hidden:true,':'')?> label:"<?php echo _('Margin')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
						,{key:"sales_year0", <?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='sales_year'?'hidden:true,':'')?> label:"<?php echo date('Y')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
						,{key:"sales_year1", <?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='sales_year'?'hidden:true,':'')?> label:"<?php echo date('Y',strtotime('-1 year'))?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
						,{key:"sales_year2", <?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='sales_year'?'hidden:true,':'')?> label:"<?php echo date('Y',strtotime('-2 year'))?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
						,{key:"sales_year3", <?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='sales_year'?'hidden:true,':'')?> label:"<?php echo date('Y',strtotime('-3 year'))?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
						,{key:"delta_sales_year0", <?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='sales_year'?'hidden:true,':'')?> label:"<?php echo '&Delta;'.date('y').'/'.date('y',strtotime('-1 year'))?>", width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
						,{key:"delta_sales_year1", <?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='sales_year'?'hidden:true,':'')?> label:"<?php echo '&Delta;'.date('y',strtotime('-1 year')).'/'.date('y',strtotime('-2 year'))?>", width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
						,{key:"delta_sales_year2", <?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='sales_year'?'hidden:true,':'')?> label:"<?php echo '&Delta;'.date('y',strtotime('-2 year')).'/'.date('y',strtotime('-3 year'))?>", width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
						,{key:"delta_sales_year3", <?php echo($_SESSION['state']['supplier_categories']['suppliers']['view']!='sales_year'?'hidden:true,':'')?> label:"<?php echo '&Delta;'.date('y',strtotime('-3 year')).'/'.date('y',strtotime('-4 year'))?>", width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

			     ];
				     
				     request="ar_suppliers.php?tipo=suppliers&tableid=0&where=&parent=category&sf=0&parent_key="+Dom.get('category_key').value
				    // alert(request)
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
			 "id"
			 ,"name"
			 ,"code"
			 ,"for_sale"
			 ,"outofstock"
			 ,"low","location","email","profit",'profit_after_storing','cost',"pending_pos","sales","contact","critical","margin","delta_sales","sold","required"
			 ,"sales_year0","delta_sales_year0" ,"sales_year1","delta_sales_year1" ,"sales_year2","delta_sales_year2" ,"sales_year3","delta_sales_year3"
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['supplier_categories']['suppliers']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<span id='paginator_info0'>{CurrentPageReport}</span>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo $_SESSION['state']['supplier_categories']['suppliers']['order']?>",
									 dir: "<?php echo $_SESSION['state']['supplier_categories']['suppliers']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
  		this.table0.table_id=tableid;
        this.table0.subscribe("renderEvent", myrenderEvent);

	    
	    this.table0.view='<?php echo $_SESSION['state']['supplier_categories']['suppliers']['view']?>';
	    this.table0.filter={key:'<?php echo $_SESSION['state']['supplier_categories']['suppliers']['f_field']?>',value:'<?php echo $_SESSION['state']['supplier_categories']['suppliers']['f_value']?>'};
		


	    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				
				    {key:"code", label:"<?php echo _('Code')?>", width:90,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"label", <?php echo($_SESSION['state']['supplier_categories']['subcategories']['view']!='sales'?'hidden:true,':'')?>  label:"<?php echo _('Label')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"subjects", <?php echo($_SESSION['state']['supplier_categories']['subcategories']['view']!='sales'?'hidden:true,':'')?>  label:"<?php echo _('Suppliers')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"sales", <?php echo($_SESSION['state']['supplier_categories']['subcategories']['view']!='sales'?'hidden:true,':'')?>  label:"<?php echo _('Sales')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"delta_sales", <?php echo($_SESSION['state']['supplier_categories']['subcategories']['view']!='sales'?'hidden:true,':'')?>  label:"<?php echo _('1y').' &Delta;'._('Sales')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"sales_year0", <?php echo($_SESSION['state']['supplier_categories']['subcategories']['view']!='sales_year'?'hidden:true,':'')?> label:"<?php echo date('Y')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
						,{key:"sales_year1", <?php echo($_SESSION['state']['supplier_categories']['subcategories']['view']!='sales_year'?'hidden:true,':'')?> label:"<?php echo date('Y',strtotime('-1 year'))?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
						,{key:"sales_year2", <?php echo($_SESSION['state']['supplier_categories']['subcategories']['view']!='sales_year'?'hidden:true,':'')?> label:"<?php echo date('Y',strtotime('-2 year'))?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
						,{key:"sales_year3", <?php echo($_SESSION['state']['supplier_categories']['subcategories']['view']!='sales_year'?'hidden:true,':'')?> label:"<?php echo date('Y',strtotime('-3 year'))?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
						,{key:"delta_sales_year0", <?php echo($_SESSION['state']['supplier_categories']['subcategories']['view']!='sales_year'?'hidden:true,':'')?> label:"<?php echo '&Delta;'.date('y').'/'.date('y',strtotime('-1 year'))?>", width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
						,{key:"delta_sales_year1", <?php echo($_SESSION['state']['supplier_categories']['subcategories']['view']!='sales_year'?'hidden:true,':'')?> label:"<?php echo '&Delta;'.date('y',strtotime('-1 year')).'/'.date('y',strtotime('-2 year'))?>", width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
						,{key:"delta_sales_year2", <?php echo($_SESSION['state']['supplier_categories']['subcategories']['view']!='sales_year'?'hidden:true,':'')?> label:"<?php echo '&Delta;'.date('y',strtotime('-2 year')).'/'.date('y',strtotime('-3 year'))?>", width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
						,{key:"delta_sales_year3", <?php echo($_SESSION['state']['supplier_categories']['subcategories']['view']!='sales_year'?'hidden:true,':'')?> label:"<?php echo '&Delta;'.date('y',strtotime('-3 year')).'/'.date('y',strtotime('-4 year'))?>", width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}



				
				     ];
//alert("ar_suppliers.php?tipo=supplier_categories&sf=0&tableid=1&parent_key="+Dom.get('category_key').value)
	    request="ar_suppliers.php?tipo=supplier_categories&sf=0&tableid=1&parent_key="+Dom.get('category_key').value;
	   // alert(request)
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
			"code","subjects","sold","sales","label","delta_sales"
						 ,"sales_year0","delta_sales_year0" ,"sales_year1","delta_sales_year1" ,"sales_year2","delta_sales_year2" ,"sales_year3","delta_sales_year3"

			
			 ]};
	    
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource1, {
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['supplier_categories']['subcategories']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['supplier_categories']['subcategories']['order']?>",
									 dir: "<?php echo$_SESSION['state']['supplier_categories']['subcategories']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;


	    
	    this.table1.view='<?php echo$_SESSION['state']['supplier_categories']['view']?>';
	    this.table1.filter={key:'<?php echo$_SESSION['state']['supplier_categories']['subcategories']['f_field']?>',value:'<?php echo$_SESSION['state']['supplier_categories']['subcategories']['f_value']?>'};
		this.table1.table_id=tableid;
     	this.table1.subscribe("renderEvent", myrenderEvent);
		
 var tableid=2; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	  	  	    var SuppliersColumnDefs = [
				       {key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				      ,{key:"date", label:"<?php echo _('Date')?>",className:"aright",width:120,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"time", label:"<?php echo _('Time')?>",className:"aleft",width:50}
				      ,{key:"handle", label:"<?php echo _('Author')?>",className:"aleft",width:100,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"note", formatter:this.prepare_note,label:"<?php echo _('Notes')?>",className:"aleft",width:520}
				       ];
		request="ar_history.php?tipo=supplier_categories&sf=0&parent=category&parent_key="+Dom.get('category_key').value+"&tableid=2";
//	   	alert(request)
	   	this.dataSource2 = new YAHOO.util.DataSource(request);

	   this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource2.connXhrMode = "queueRequests";
	    this.dataSource2.responseSchema = {
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
			 "key"
			 ,"date"
			 ,'time','handle','note'
			 ]};
	    
	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, SuppliersColumnDefs,
						     this.dataSource2
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    : <?php echo$_SESSION['state']['supplier_categories']['history']['nr']?>,containers : 'paginator2', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    key: "<?php echo$_SESSION['state']['supplier_categories']['history']['order']?>",
							     dir: "<?php echo$_SESSION['state']['supplier_categories']['history']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;

		this.table2.table_id=tableid;
     	this.table2.subscribe("renderEvent", myrenderEvent);

		    
	    this.table2.filter={key:'<?php echo$_SESSION['state']['supplier_categories']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['supplier_categories']['history']['f_value']?>'};






     		  var tableid=6;
	    var tableDivEL="table"+tableid;


	    var ColumnDefs = [ 
				    {key:"code", label:"<?php echo _('Code')?>", width:60,sortable:true, className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"name", label:"<?php echo _('Name')?>",width:440, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   		//		   ,{key:"state", label:"<?php echo _('State')?>",width:60, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   ,{key:"sold", label:"<?php echo _('Sold')?>",width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"sales", label:"<?php echo _('Sales')?>",width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit", label:"<?php echo _('Profit')?>",width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   
							       ];
	request="ar_suppliers.php?tipo=supplier_product_sales_report&tableid="+tableid+"&parent=supplier_categories&sf=0"+'&parent_key='+Dom.get('category_key').value+'&from='+Dom.get('from').value+'&to='+Dom.get('to').value;
	
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
			 'id'
			 ,"code"
			 ,"name","stock","stock_value","record_type"
			 ,'sales','profit','margin','sold',"parts","supplied","gmroi","department","dept","expcode","state","web","smallname","delta_sales"
			 ]};
	    


	    this.table6 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource6, {
							 //draggableColumns:true,
							// formatRow: myRowFormatter,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage:<?php echo$_SESSION['state']['store']['family_sales']['nr']?>,containers : 'paginator6', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info6'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['store']['family_sales']['order']?>",
									 dir: "<?php echo$_SESSION['state']['store']['family_sales']['order_dir']?>"
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

		this.table6.filter={key:'<?php echo$_SESSION['state']['store']['family_sales']['f_field']?>',value:'<?php echo$_SESSION['state']['store']['family_sales']['f_value']?>'};

	 

   var tableid=7;
		    var tableDivEL="table"+tableid;

  			var ColumnDefs = [
				      {key:"date", label:"<?php echo _('Date')?>", width:200,sortable:false,className:"aright"}
				      //,{key:"invoices", label:"<?php echo _('Invoices')?>", width:100,sortable:false,className:"aright"}
				      //,{key:"customers", label:"<?php echo _('Customers')?>", width:100,sortable:false,className:"aright"}
				   
				      ,{key:"sales", label:"<?php echo _('Sales')?>", width:100,sortable:false,className:"aright"}
				      ,{key:"cost_sales", label:"<?php echo _('Cost Sales')?>", width:100,sortable:false,className:"aright"}
				      ,{key:"qty", label:"<?php echo _('Sold')?>", width:100,sortable:false,className:"aright"}
				      ,{key:"out_of_stock", label:"<?php echo _('Out of Stock')?>", width:100,sortable:false,className:"aright"}
				      ,{key:"out_of_stock_amount", label:"<?php echo _('Out of Stock')?>", width:100,sortable:false,className:"aright"}

					      ];

		 
		    request="ar_reports.php?tipo=inventory_assets_sales_history&sf=0&parent=supplier_categories&parent_key="+Dom.get('category_key').value+"&tableid="+tableid+'&from='+Dom.get('from').value+'&to='+Dom.get('to').value;
		
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
				 "date","invoices","customers","sales","qty","out_of_stock","cost_sales","out_of_stock_amount"

				 ]};

	    
	    this.table7 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource7, {
							
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage:<?php echo$_SESSION['state']['supplier_categories']['sales_history']['nr']?>,containers : 'paginator7', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info7'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['supplier_categories']['sales_history']['order']?>",
									 dir: "<?php echo$_SESSION['state']['supplier_categories']['sales_history']['order_dir']?>"
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
		this.table7.filter={key:'<?php echo$_SESSION['state']['supplier_categories']['sales_history']['f_field']?>',value:'<?php echo$_SESSION['state']['supplier_categories']['sales_history']['f_value']?>'};




	};
    });


function change_suppliers_view_save(tipo) {

    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=supplier_categories-suppliers-view&value=' + escape(tipo), {});

}



function change_sales_period(){
  tipo=this.id;
 
  ids=['category_period_yesterday','category_period_last_m','category_period_last_w','category_period_all','category_period_three_year','category_period_year','category_period_six_month','category_period_quarter','category_period_month','category_period_ten_day','category_period_week','category_period_yeartoday','category_period_monthtoday','category_period_weektoday','category_period_today'];

 Dom.removeClass(ids,"selected")
 Dom.addClass(this,"selected")
   period=this.getAttribute('period');
 YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=supplier_categories-period&value='+period ,{});



Dom.setStyle(['info_yesterday','info_last_m','info_last_w','info_all','info_three_year','info_year','info_six_month','info_quarter','info_month','info_ten_day','info_week','info_yeartoday','info_monthtoday','info_weektoday','info_today'],'display','none')


Dom.setStyle(['info2_yesterday','info2_last_m','info2_last_w','info2_all','info2_three_year','info2_year','info2_six_month','info2_quarter','info2_month','info2_ten_day','info2_week','info2_yeartoday','info2_monthtoday','info2_weektoday','info2_today'],'display','none')
Dom.setStyle(['info_'+period,'info2_'+period],'display','')

}



function change_subcategories_period(e,table_id){

  tipo=this.id;

 Dom.removeClass(subcategories_period_ids,"selected")
 Dom.addClass(this,"selected")
   
    var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];
    var request='&period=' + this.getAttribute('period');
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


function change_display_mode(name,label){
    if(name=='percentage'){
	var request='&percentages=1';
    }if(name=='value'){
	var request='&percentages=0&show_default_currency=0';
    }if(name=='value_default_d2d'){
	var request='&percentages=0&show_default_currency=1';
    }

    Dom.get('change_display_mode').innerHTML=label;
    var table=tables['table0'];
    var datasource=tables.dataSource0;
    
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);   

}

function update_supplier_category_history_elements() {

    var ar_file = 'ar_history.php';
    var request = 'tipo=get_supplier_category_history_elements&parent=category&parent_key=' + Dom.get('category_key').value;
    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            var r = YAHOO.lang.JSON.parse(o.responseText);
            for (key in r.elements_number) {
                Dom.get('elements_' + key + '_number').innerHTML = r.elements_number[key]
            }
        },
        failure: function(o) {},
        scope: this
    }, request

    );
}

function get_supplier_category_sales_data(from, to) {

    Dom.get('required').innerHTML = '<img style="height:14px" src="art/loading.gif" />';
    Dom.get('out_of_stock').innerHTML = '<img style="height:14px" src="art/loading.gif" />';
    Dom.get('not_found').innerHTML = '<img style="height:14px" src="art/loading.gif" />';
    Dom.get('sold').innerHTML = '<img style="height:14px" src="art/loading.gif" />';
    Dom.get('sales_amount').innerHTML = '<img style="height:14px" src="art/loading.gif" />';
        Dom.get('cost_sales_amount').innerHTML = '<img style="height:14px" src="art/loading.gif" />';

    Dom.get('profits').innerHTML = '<img style="height:14px" src="art/loading.gif" />';
    Dom.get('margin').innerHTML = '<img style="height:14px" src="art/loading.gif" />';
    Dom.get('gmroi').innerHTML = '<img style="height:14px" src="art/loading.gif" />';

    var request = 'ar_suppliers.php?tipo=get_supplier_category_sales_data&parent=supplier_category&parent_key=' + Dom.get('category_key').value + '&from=' + from + '&to=' + to

    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                Dom.get('sold').innerHTML = r.sold;
                Dom.get('sales_amount').innerHTML = r.sales;
                Dom.get('cost_sales_amount').innerHTML = r.cost_sales;
                Dom.get('profits').innerHTML = r.profits;
                Dom.get('margin').innerHTML = r.margin;
                Dom.get('gmroi').innerHTML = r.gmroi;
                if (r.no_supplied == 0) {
                    Dom.setStyle('no_supplied_tbody', 'display', 'none')
                } else {
                    Dom.setStyle('no_supplied_tbody', 'display', '')
                }
                Dom.get('required').innerHTML = r.required;
                Dom.get('out_of_stock').innerHTML = r.out_of_stock;
                Dom.get('not_found').innerHTML = r.not_found;
            }


        }
    });
}


function post_change_period_actions(r) {
period=r.period;
to=r.to;
from=r.from;


    request = '&from=' + from + '&to=' + to;



    table_id = 7
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
    Dom.get('rtext'+table_id).innerHTML = '<img src="art/loading.gif" style="height:12.9px"/> <?php echo _("Processing Request") ?>'
    Dom.get('rtext_rpp'+table_id).innerHTML = '';
   table_id = 6
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
 Dom.get('rtext'+table_id).innerHTML = '<img src="art/loading.gif" style="height:12.9px"/> <?php echo _("Processing Request") ?>'
    Dom.get('rtext_rpp'+table_id).innerHTML = '';
   
   
   

    get_supplier_category_sales_data(from, to)
    
   
    
    if(Dom.get('subject_sales_pie')!= undefined)
    Dom.get('subject_sales_pie').reloadData("plot_data.csv.php?tipo=category_subjects_sales&subject=Supplier&category_key="+Dom.get('category_key').value+"&from="+from+"&to="+to); 
 
 
 if(Dom.get('categories_sales_pie')!= undefined){
 Dom.get('categories_sales_pie').innerHTML='xx';
  Dom.get('categories_sales_pie').reloadData("plot_data.csv.php?tipo=category_sales&subject=Supplier&category_key="+Dom.get('category_key').value+"&from="+from+"&to="+to); 
}


}

function show_dialog_sales_history_timeline_group() {
    region1 = Dom.getRegion('change_sales_history_timeline_group');
    region2 = Dom.getRegion('dialog_sales_history_timeline_group');
    var pos = [region1.right - region2.width, region1.bottom]
    Dom.setXY('dialog_sales_history_timeline_group', pos);
    dialog_sales_history_timeline_group.show();
}

function change_timeline_group(table_id, subject, mode, label) {

    Dom.removeClass(Dom.getElementsByClassName('timeline_group', 'button', subject + '_timeline_group_options'), 'selected');;
    Dom.addClass(subject + '_timeline_group_' + mode, 'selected');
    var request = '&timeline_group=' + mode;
    dialog_sales_history_timeline_group.hide();
    
    Dom.get('change_' + subject + '_timeline_group').innerHTML = ' &#x21b6 ' + label;
    var request = '&timeline_group=' + mode;
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}


function change_subcategories_view(){
tableid=1
    var table = tables['table' + tableid];
    var tipo = this.getAttribute('name');
    
    
    


    if ( tipo == 'sales') {
        Dom.setStyle(['subcategories_period_options','subcategories_avg_options'], 'display', '');
    } else {
        Dom.setStyle(['subcategories_period_options','subcategories_avg_options'], 'display', 'none');
    }



       table.hideColumn('label');

    table.hideColumn('subjects');
    table.hideColumn('sales');
    table.hideColumn('delta_sales');
        table.hideColumn('sales_year0');

    table.hideColumn('sales_year1');
    table.hideColumn('sales_year2');
    table.hideColumn('sales_year3');

    table.hideColumn('delta_sales_year0');
    table.hideColumn('delta_sales_year1');
    table.hideColumn('delta_sales_year2');
    table.hideColumn('delta_sales_year3');
   
   


    if (tipo == 'sales') {
               table.showColumn('label');

           table.showColumn('subjects');
    table.showColumn('sales');
    table.showColumn('delta_sales');


    } else if (tipo == 'sales_year') {
      table.showColumn('sales_year0');
        table.showColumn('sales_year1');
    table.showColumn('sales_year2');
    table.hideColumn('sales_year3');

    table.showColumn('delta_sales_year0');
    table.showColumn('delta_sales_year1');
    table.showColumn('delta_sales_year2');
    table.showColumn('delta_sales_year3');
    }

    Dom.removeClass(['subcategories_sales', 'subcategories_sales_year'], 'selected')
    Dom.addClass(this, 'selected')


    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=supplier_categories-subcategories-view&value=' + escape(tipo), {});



}

function init() {

	        get_supplier_category_sales_data(Dom.get('from').value, Dom.get('to').value)


    ids = ['subcategories', 'subjects', 'overview', 'history', 'sales', 'no_assigned'];
    Event.addListener(ids, "click", change_block);

  ids = ['subcategories_sales', 'subcategories_sales_year'];
    Event.addListener(ids, "click", change_subcategories_view);




    init_search('suppliers');

    var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    oACDS.table_id = 0;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0", "f_container0", oACDS);
    oAutoComp.minQueryLength = 0;

    var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS1.queryMatchContains = true;
    oACDS1.table_id = 1;
    var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1", "f_container1", oACDS1);
    oAutoComp1.minQueryLength = 0;


    var oACDS2 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS2.queryMatchContains = true;
    oACDS2.table_id = 2;
    var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input2", "f_container2", oACDS2);
    oAutoComp2.minQueryLength = 0;

    Event.addListener('clean_table_filter_show0', "click", show_filter, 0);
    Event.addListener('clean_table_filter_hide0', "click", hide_filter, 0);
    Event.addListener('clean_table_filter_show1', "click", show_filter, 1);
    Event.addListener('clean_table_filter_hide1', "click", hide_filter, 1);
    Event.addListener('clean_table_filter_show2', "click", show_filter, 2);
    Event.addListener('clean_table_filter_hide2', "click", hide_filter, 2);

    ids=['suppliers_general','suppliers_sales','suppliers_stock','suppliers_products','suppliers_contact','suppliers_profit','suppliers_sales_year'];
 YAHOO.util.Event.addListener(ids, "click",change_suppliers_view,{'table_id':0,'parent':'suppliers'})
 YAHOO.util.Event.addListener(suppliers_period_ids, "click",change_table_period,{'table_id':0,'subject':'suppliers'});

    YAHOO.util.Event.addListener(subcategories_period_ids, "click", change_subcategories_period, 1);
    ids = ['category_period_all', 'category_period_three_year', 'category_period_year', 'category_period_yeartoday', 'category_period_six_month', 'category_period_quarter', 'category_period_month', 'category_period_ten_day', 'category_period_week', 'category_period_monthtoday', 'category_period_weektoday', 'category_period_today', 'category_period_yesterday', 'category_period_last_m', 'category_period_last_w'];
    YAHOO.util.Event.addListener(ids, "click", change_sales_period);

    ids = ['elements_Changes', 'elements_Assign'];
    Event.addListener(ids, "click", change_history_elements, 2);

		var so = new SWFObject("external_libs/amstock/amstock/amstock.swf", "amstock", "905", "500", "8", "#FFFFFF");
		so.addVariable("path", "");
		so.addVariable("settings_file", encodeURIComponent("conf/plot_asset_sales.xml.php?tipo=supplier_category_sales&category_key="+Dom.get('category_key').value));

		so.addVariable("preloader_color", "#999999");
		
		so.write("plot_supplier_sales_div");
	dialog_sales_history_timeline_group = new YAHOO.widget.Dialog("dialog_sales_history_timeline_group", {visible : false,close:true,underlay: "none",draggable:false});
dialog_sales_history_timeline_group.render();
YAHOO.util.Event.addListener("change_sales_history_timeline_group", "click", show_dialog_sales_history_timeline_group);


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

YAHOO.util.Event.onContentReady("rppmenu1", function() {
    var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {
        trigger: "rtext_rpp1"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);
});

YAHOO.util.Event.onContentReady("filtermenu1", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {
        trigger: "filter_name1"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);

});
YAHOO.util.Event.onContentReady("rppmenu2", function() {
    var oMenu = new YAHOO.widget.ContextMenu("rppmenu2", {
        trigger: "rtext_rpp2"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);
});

YAHOO.util.Event.onContentReady("filtermenu2", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu2", {
        trigger: "filter_name2"
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);

});

YAHOO.util.Event.onContentReady("change_display_menu", function() {
    var oMenu = new YAHOO.widget.Menu("change_display_menu", {
        context: ["change_display_mode", "tr", "br"]
    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);
    YAHOO.util.Event.addListener("change_display_mode", "click", oMenu.show, null, oMenu);

});
