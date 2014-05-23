<?php
include_once('common.php');?>




Event.addListener(window, "load", function() {
    tables = new function() {





	
	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [
			     {key:"code", label:"<?php echo _('Code')?>",width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"description", label:"<?php echo _('Description')?>",width:330,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     //,{key:"tariff_code", label:"<?php echo _('Tariff Code')?>",width:80,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				     ,{key:"quantity",label:"<?php echo _('Qty')?>", width:50,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"gross",label:"<?php echo _('Amount')?>",<?php echo(($_SESSION['state']['order_in_process_by_customer']['items']['view']=='basket')  ?'':'hidden:true,')?>  width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"discount",label:"<?php echo _('Discounts')?>",<?php echo(($_SESSION['state']['order_in_process_by_customer']['items']['view']=='basket')  ?'':'hidden:true,')?>  width:70,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"to_charge",label:"<?php echo _('To Charge')?>",<?php echo(($_SESSION['state']['order_in_process_by_customer']['items']['view']=='basket')  ?'':'hidden:true,')?> width:85,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"created",label:"<?php echo _('Created')?>", <?php echo(($_SESSION['state']['order_in_process_by_customer']['items']['view']=='times')  ?'':'hidden:true,')?>width:190,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				     ,{key:"last_updated",label:"<?php echo _('Updated')?>",<?php echo(($_SESSION['state']['order_in_process_by_customer']['items']['view']=='times')  ?'':'hidden:true,')?> width:190,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ];
		//alert("ar_orders.php?tipo=orders&where=");
		request="ar_orders.php?tipo=transactions&parent=order_in_process_by_customer&parent_key="+Dom.get('order_key').value+"&tableid=0"
	   // alert(request)
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
			 "code"
			 ,"description"
			 ,"quantity"
			 ,"discount"
			 ,"to_charge","gross","tariff_code","created","last_updated"
			 // "promotion_id",
			 ]};

	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : <?php echo$_SESSION['state']['order_in_process_by_customer']['items']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['order_in_process_by_customer']['items']['order']?>",
									 dir: "<?php echo$_SESSION['state']['order_in_process_by_customer']['items']['order_dir']?>"
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

	    
	    this.table0.filter={key:'<?php echo$_SESSION['state']['order_in_process_by_customer']['items']['f_field']?>',value:'<?php echo$_SESSION['state']['order_in_process_by_customer']['items']['f_value']?>'};

	


    
    };
  });

function change_items_view(e, data) {
    tipo = this.id;



    if (tipo == 'items_basket') tipo = 'basket';
    else if (tipo == 'items_times') tipo = 'times';

    var table = tables['table' + data.table_id];
    table.hideColumn('quantity');
    table.hideColumn('gross');
    table.hideColumn('discount');
    table.hideColumn('to_charge');
    table.hideColumn('created');
    table.hideColumn('last_updated');


    if (tipo == 'basket') {
        table.showColumn('quantity');
        table.showColumn('gross');
        table.showColumn('discount');
        table.showColumn('to_charge');
        table.showColumn('smallname');
    } else if (tipo == 'times') {
        table.showColumn('created');
        table.showColumn('last_updated');
    }
    Dom.removeClass(Dom.getElementsByClassName('table_option', 'button', this.parentNode), 'selected')
    Dom.addClass(this, "selected");


    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=' + data.parent + '-items-view&value=' + escape(tipo), {});

}

function init() {

    init_search('orders_store');

    ids = ['items_basket', 'items_times'];
    
            YAHOO.util.Event.addListener(ids, "click", change_items_view, {
        'table_id': 0,
        'parent': 'order_in_process_by_customer'
    })

    }

    YAHOO.util.Event.onDOMReady(init);
