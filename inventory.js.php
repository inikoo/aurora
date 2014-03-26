<?php
include_once('common.php');

?>
 var Dom   = YAHOO.util.Dom;
var dialog_stock_history_timeline_group;

var  link='inventory.php';




function submit_choose_day(){
extra_argument = '';
    if (Dom.get('link_extra_argument') != undefined) {
        extra_argument = Dom.get('link_extra_argument').value;
    }
    pick_date = Dom.get(calendar_id+"_pick_date").value;
    location.href = "stock_history_parts.php?date=" + pick_date + extra_argument

}




function change_timeline_group(table_id, subject, mode, label) {

    Dom.removeClass(Dom.getElementsByClassName('timeline_group', 'button', subject + '_timeline_group_options'), 'selected');;
    Dom.addClass(subject + '_timeline_group_' + mode, 'selected');
    var request = '&timeline_group=' + mode;
    dialog_stock_history_timeline_group.hide();
    Dom.get('change_' + subject + '_timeline_group').innerHTML = ' &#x21b6 ' + label;
    var request = '&timeline_group=' + mode;
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
}

function show_dialog_stock_history_timeline_group() {
    region1 = Dom.getRegion('change_stock_history_timeline_group');
    region2 = Dom.getRegion('dialog_stock_history_timeline_group');
    var pos = [region1.right - region2.width, region1.bottom]
    Dom.setXY('dialog_stock_history_timeline_group', pos);
    dialog_stock_history_timeline_group.show();
}


function change_stock_history_block(e) {

	var ids = ["history_block_plot", "history_block_list"];
	var block_ids = ["stock_history_plot_subblock", "stock_history_list_subblock" ];
	Dom.setStyle(block_ids, 'display', 'none');
	block_id=this.getAttribute('block_id');
	Dom.setStyle('stock_history_'+block_id+'_subblock', 'display', '');
	Dom.removeClass(ids, 'selected');
	Dom.addClass(this, 'selected');
	YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=warehouse-stock_history_block&value=' + block_id, {});

}


YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {




 var tableid=0;
		    var tableDivEL="table"+tableid;

  var ColumnDefs = [
				      {key:"date", label:"<?php echo _('Date')?>", width:100,sortable:false,className:"aright"}
				      ,{key:"parts", label:"<?php echo _('Parts')?>", width:70,sortable:false,className:"aright"}
				      ,{key:"locations", label:"<?php echo _('Locations')?>", width:70,sortable:false,className:"aright"}
				    //  ,{key:"quantity", label:"<?php echo _('Qty')?>", width:80,sortable:false,className:"aright"}
				      ,{key:"value", label:"<?php echo _('Cost Value')?>", width:80,sortable:false,className:"aright"}
				      ,{key:"end_day_value", label:"<?php echo _('C Value (ED)')?>", width:80,sortable:false,className:"aright"}
				      ,{key:"commercial_value", label:"<?php echo _('Com Value')?>", width:80,sortable:false,className:"aright"}

				     // ,{key:"sold_qty", label:"<?php echo _('Sold')?>", width:60,sortable:false,className:"aright"}
				     // ,{key:"in_qty", label:"<?php echo _('In')?>", width:60,sortable:false,className:"aright"}
				     // ,{key:"lost_qty", label:"<?php echo _('Lost')?>", width:60,sortable:false,className:"aright"}

				      ];

		 	request="ar_parts.php?tipo=warehouse_parts_stock_history&parent=warehouse&parent_key="+Dom.get('warehouse_key').value+"&sf=0&tableid="+tableid;
		   //alert(request)
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
				 "date","locations","value","parts","end_day_value","commercial_value"

				 ]};

	    
		    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
							     this.dataSource0, {
								 //draggableColumns:true,
								 renderLoopSize: 50,generateRequest : myRequestBuilder
								 ,paginator : new YAHOO.widget.Paginator({alwaysVisible:false,
									 rowsPerPage:<?php echo$_SESSION['state']['warehouse']['stock_history']['nr']?>,containers : 'paginator0', 
									 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									 previousPageLinkLabel : "<",
									 nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
									 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
								     })
								 
								 ,sortedBy : {
								   key: "<?php echo$_SESSION['state']['warehouse']['stock_history']['order']?>",
								    dir: "<?php echo$_SESSION['state']['warehouse']['stock_history']['order_dir']?>"
								  }
								 ,dynamicData : true
								 
							     }
							     );


		    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
		    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
		    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;







	    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var LocationsColumnDefs = [
				     {key:"date", label:"<?php echo _('Date')?>", width:150,sortable:false,className:"aright"}
				      
				      ,{key:"type", label:"<?php echo _('Type')?>", width:50,sortable:false,className:"aleft"}
				       ,{key:"user", label:"<?php echo _('User')?>", width:50,sortable:false,className:"aleft"}
				     ,{key:"location", label:"<?php echo _('Location')?>", width:60,sortable:false,className:"aleft"}

				      ,{key:"note", label:"<?php echo _('Note')?>", width:300,sortable:false,className:"aleft"}
				      ,{key:"change", label:"<?php echo _('Change')?>", width:60,sortable:false,className:"aright"}
					 ];
	    //?tipo=locations&tid=0"
	    this.dataSource1 = new YAHOO.util.DataSource("ar_parts.php?tipo=part_transactions&parent=warehouse&parent_key="+Dom.get('warehouse_id').value+"&sf=0&tableid="+tableid);
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
			"date","change","type","location","note","user"
			 ]};
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, LocationsColumnDefs,
								   this.dataSource1
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['warehouse']['transactions']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['warehouse']['transactions']['order']?>",
									 dir: "<?php echo$_SESSION['state']['warehouse']['transactions']['order_dir']?>"
								     },
								     dynamicData : true
								  }
								 );
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table1.filter={key:'<?php echo$_SESSION['state']['warehouse']['transactions']['f_field']?>',value:'<?php echo$_SESSION['state']['warehouse']['transactions']['f_value']?>'};
	    
	
	    var tableid=2;
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [ 
				    {key:"sku", label:"<?php echo _('SKU')?>", width:40,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  	,{key:"reference", label:"<?php echo _('Reference')?>",width:90,<?php echo($_SESSION['state']['warehouse']['parts']['view']=='general'?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				  	,{key:"description", label:"<?php echo _('Description')?>",width:380,<?php echo($_SESSION['state']['warehouse']['parts']['view']=='general'?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"description_small", label:"<?php echo _('Description')?>",width:320,<?php echo($_SESSION['state']['warehouse']['parts']['view']!='general'?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"used_in", label:"<?php echo _('Used In')?>",width:350,<?php echo($_SESSION['state']['warehouse']['parts']['view']=='general'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"supplied_by", label:"<?php echo _('Supplied By')?>",width:200,<?php echo($_SESSION['state']['warehouse']['parts']['view']=='supplier'?'':'hidden:true,')?>sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   	,{key:"locations", label:"<?php echo _('Locations')?>", width:200,sortable:false,className:"aleft",<?php echo(($_SESSION['state']['warehouse']['parts']['view']=='locations' )  ?'':'hidden:true')?>}
				    ,{key:"stock_value", label:"<?php echo _('Stk Value')?>", width:100,sortable:true,className:"aright",<?php echo(($_SESSION['state']['warehouse']['parts']['view']=='stock' or $_SESSION['state']['warehouse']['parts']['view']=='locations')?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   	,{key:"stock", label:"<?php echo _('Stock')?>", width:80,sortable:true,className:"aright",<?php echo(($_SESSION['state']['warehouse']['parts']['view']=='stock' or $_SESSION['state']['warehouse']['parts']['view']=='locations' )  ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_days", label:"<?php echo _('Stk Days')?>", width:100,sortable:true,className:"aright",<?php echo(($_SESSION['state']['warehouse']['parts']['view']=='stock' )?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"stock_state", label:"<?php echo _('Stk State')?>", width:100,sortable:true,className:"aright",<?php echo(($_SESSION['state']['warehouse']['parts']['view']=='stock' )?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					//,{key:"avg_stock", label:"<?php echo _('AS')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   //,{key:"avg_stockvalue", label:"<?php echo _('ASV')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   //,{key:"keep_days", label:"<?php echo _('KD')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   //,{key:"outstock_days", label:"<?php echo _('OofS')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    // ,{key:"unknown_days", label:"<?php echo _('?S')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='stock'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"sold", label:"<?php echo _('Sold(Given) Qty')?>", width:120,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"delta_sold", label:"<?php echo '&Delta;'._('Sold(Given) Qty')?>", width:120,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"money_in", label:"<?php echo _('Sold')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"delta_money_in", label:"<?php echo '&Delta;'._('Sold')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='sales'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    //    ,{key:"profit", label:"<?php echo _('Profit Out')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='sale'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit_sold", label:"<?php echo _('Profit (Inc Given)')?>", width:160,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='forecast'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"margin", label:"<?php echo _('Margin')?>", width:100,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='forecast'?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   	,{key:"gmroi", label:"<?php echo _('GMROI')?>", width:70,sortable:true,className:"aright",<?php echo($_SESSION['state']['warehouse']['parts']['view']=='forecast'   ?'':'hidden:true,')?>sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				  ];
request="ar_parts.php?tipo=parts&parent=warehouse&parent_key="+Dom.get('warehouse_id').value+"&tableid=2&where=&sf=0";

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
			 "sku","reference",
			 "description","locations","description_small","delta_money_in","delta_sold","stock_days","stock_state",
			 "stock","available_for","stock_value","sold","given","money_in","profit","profit_sold","used_in","supplied_by","margin",'avg_stock','avg_stockvalue','keep_days','outstock_days','unknown_days','gmroi'
			 ]};
	    
	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource2, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['warehouse']['parts']['nr']?>,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<span id='paginator_info2'>{CurrentPageReport}</span>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo $_SESSION['state']['warehouse']['parts']['order']?>",
									 dir: "<?php echo $_SESSION['state']['warehouse']['parts']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table2.request=request;
  		this.table2.table_id=tableid;
     	this.table2.subscribe("renderEvent", part_myrenderEvent);
   		this.table2.getDataSource().sendRequest(null, {
    		success:function(request, response, payload) {
        		if(response.results.length == 0) {
            		get_part_elements_numbers()
            	} else {
            		//this.onDataReturnInitializeTable(request, response, payload);
        		}
    		},
    		scope:this.table2,
    		argument:this.table2.getState()
		});
	    
	    
	    this.table2.view='<?php echo $_SESSION['state']['warehouse']['parts']['view']?>';
	    this.table2.filter={key:'<?php echo $_SESSION['state']['warehouse']['parts']['f_field']?>',value:'<?php echo $_SESSION['state']['warehouse']['parts']['f_value']?>'};
		



	};
    });


function change_block() {
    ids = ['history', 'movements', 'parts']
    block_ids = ['block_history', 'block_movements', 'block_parts']
    Dom.setStyle(block_ids, 'display', 'none');
    Dom.setStyle('block_' + this.id, 'display', '');
    Dom.removeClass(ids, 'selected');
    Dom.addClass(this, 'selected');

  if (this.id == 'parts') {
        Dom.setStyle('calendar_container', 'display', 'none');

    } else {
        Dom.setStyle('calendar_container', 'display', '');

    }



    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=warehouse-parts_view&value=' + this.id, {});
}





 
 
 
 function get_transaction_numbers(from, to) {


    var ar_file = 'ar_parts.php';
     var request = 'tipo=number_transactions_in_interval&parent=warehouse&parent_key=' + Dom.get('warehouse_key').value + '&from=' + from + '&to=' + to;

// alert(ar_file+'?'+request)
 //   Dom.setStyle(['transactions_all_transactions_wait', 'transactions_in_transactions_wait', 'transactions_out_transactions_wait', 'transactions_audit_transactions_wait', 'transactions_oip_transactions_wait', 'transactions_move_transactions_wait'], 'display', '');
    Dom.get('transactions_type_elements_OIP_numbers').innerHTML = '<img src="art/loading.gif" style="height:11px">';
  Dom.get('transactions_type_elements_Out_numbers').innerHTML = '<img src="art/loading.gif" style="height:11px">';
    Dom.get('transactions_type_elements_In_numbers').innerHTML = '<img src="art/loading.gif" style="height:11px">';
    Dom.get('transactions_type_elements_Audit_numbers').innerHTML = '<img src="art/loading.gif" style="height:11px">';
    Dom.get('transactions_type_elements_Move_numbers').innerHTML = '<img src="art/loading.gif" style="height:11px">';

//alert(ar_file+'?'+request)


    YAHOO.util.Connect.asyncRequest('POST', ar_file, {
        success: function(o) {
            
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {

              for (i in r.transactions) {
             
              Dom.get('transactions_type_elements_'+i+'_numbers').innerHTML=r.transactions[i]
              }
            }
        },
        failure: function(o) {
        },
        scope: this
    }, request

    );
}


 









function change_plot(type){
Dom.setStyle(['change_plot_label_value','change_plot_label_end_day_value','change_plot_label_commercial_value'],'display','none')
Dom.setStyle('change_plot_label_'+type,'display','')


change_plot_menu.hide()



reloadSettings("conf/plot_general_candlestick.xml.php?tipo=part_stock_history&output="+type+"&parent=warehouse&parent_key="+Dom.get('warehouse_key').value);

YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=warehouse-stock_history-chart_output&value='+type ,{});

}
function show_dialog_change_plot(){
region1 = Dom.getRegion('change_plot'); 
    region2 = Dom.getRegion('change_plot_menu'); 
	var pos =[region1.right-region2.width,region1.bottom]
	Dom.setXY('change_plot_menu', pos);

change_plot_menu.show()
}


function post_change_period_actions(r) {
period=r.period;
to=r.to;
from=r.from;





    request = '&from=' + from + '&to=' + to;

    table_id = 0
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
    table_id = 1
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);


    Dom.get('rtext0').innerHTML='<img src="art/loading.gif" style="height:12.9px"/> <?php echo _("Processing Request") ?>'
    Dom.get('rtext_rpp0').innerHTML='';
    Dom.get('rtext1').innerHTML='<img src="art/loading.gif" style="height:12.9px"/>  <?php echo _("Processing Request") ?>'
    Dom.get('rtext_rpp1').innerHTML='';

get_transaction_numbers(from,to)

   // get_warehouse_element_transaction_numbers('all', from, to)
   // get_warehouse_element_transaction_numbers('out', from, to)
   // get_warehouse_element_transaction_numbers('in', from, to)
   // get_warehouse_element_transaction_numbers('move', from, to)
   // get_warehouse_element_transaction_numbers('audit', from, to)
   // get_warehouse_element_transaction_numbers('oip', from, to)


}

var already_clicked_transactions_type_elements_click = false
function change_transactions_type_elements() {
el=this;
var elements_type='';
    if (already_clicked_transactions_type_elements_click) {
        already_clicked_transactions_type_elements_click = false; // reset
        clearTimeout(alreadyclickedTimeout); // prevent this from happening
        change_transactions_type_elements_dblclick(el, elements_type)
    } else {
        already_clicked_transactions_type_elements_click = true;
        alreadyclickedTimeout = setTimeout(function() {
            already_clicked_transactions_type_elements_click = false; // reset when it happens
            change_transactions_type_elements_click(el, elements_type)
        }, 300); // <-- dblclick tolerance here
    }
    return false;
}

function change_transactions_type_elements_click(el,elements_type) {

    var ids = Array("transactions_type_elements_OIP", "transactions_type_elements_In", "transactions_type_elements_Out", "transactions_type_elements_Audit", "transactions_type_elements_Move");


    if (Dom.hasClass(el, 'selected')) {

        var number_selected_elements = 0;
        for (i in ids) {
            if (Dom.hasClass(ids[i], 'selected')) {
                number_selected_elements++;
            }
        }

        if (number_selected_elements > 1) {
            Dom.removeClass(el, 'selected')

        }

    } else {
        Dom.addClass(el, 'selected')

    }

    table_id = 1;
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

  //  alert(request)
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);


}

function change_transactions_type_elements_dblclick(el,elements_type) {

    var ids = Array("transactions_type_elements_OIP", "transactions_type_elements_In", "transactions_type_elements_Out", "transactions_type_elements_Audit", "transactions_type_elements_Move");


    
         Dom.removeClass(ids, 'selected')

     Dom.addClass(el, 'selected')

    table_id = 1;
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

    // alert(request)
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);


}


function init(){
 
dialog_export['parts'] = new YAHOO.widget.Dialog("dialog_export_parts", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
   dialog_export['parts'].render();
    Event.addListener("export_parts", "click", show_export_dialog, 'parts');
    Event.addListener("export_csv_parts", "click", export_table, {
        output: 'csv',
        table: 'parts',
        parent: 'warehouse',
        'parent_key': Dom.get('warehouse_key').value
    });
    Event.addListener("export_xls_parts", "click", export_table, {
        output: 'xls',
        table: 'parts',
        parent: 'warehouse',
        'parent_key': Dom.get('warehouse_key').value
    });

    Event.addListener("export_result_download_link_parts", "click", download_export_file,'parts');

 

 change_plot_menu = new YAHOO.widget.Dialog("change_plot_menu", {visible : false,close:true,underlay: "none",draggable:false});
change_plot_menu.render();
Event.addListener("change_plot", "click", show_dialog_change_plot);
 
 
  init_search('parts');
  
  
  
  
Event.addListener(['history','movements','parts'], "click",change_block);
Event.addListener(["history_block_plot", "history_block_list"], "click",change_stock_history_block);

 

 var ids = Array("transactions_type_elements_OIP", "transactions_type_elements_In", "transactions_type_elements_Out", "transactions_type_elements_Audit", "transactions_type_elements_Move");
    Event.addListener(ids, "click", change_transactions_type_elements);
  
   
  get_transaction_numbers(from,to)
  
//get_warehouse_element_transaction_numbers('all',Dom.get('from').value,Dom.get('to').value)
//get_warehouse_element_transaction_numbers('out',Dom.get('from').value,Dom.get('to').value)
//get_warehouse_element_transaction_numbers('in',Dom.get('from').value,Dom.get('to').value)
//get_warehouse_element_transaction_numbers('move',Dom.get('from').value,Dom.get('to').value)
//get_warehouse_element_transaction_numbers('audit',Dom.get('from').value,Dom.get('to').value)
//get_warehouse_element_transaction_numbers('oip',Dom.get('from').value,Dom.get('to').value)




dialog_stock_history_timeline_group = new YAHOO.widget.Dialog("dialog_stock_history_timeline_group", {visible : false,close:true,underlay: "none",draggable:false});
dialog_stock_history_timeline_group.render();
YAHOO.util.Event.addListener("change_stock_history_timeline_group", "click", show_dialog_stock_history_timeline_group);



 }

YAHOO.util.Event.onDOMReady(init);


YAHOO.util.Event.onContentReady("rppmenu2", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu2", {trigger:"rtext_rpp2" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu2", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu2", {trigger:"filter_name2"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
    
YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {  trigger: "filter_name1"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });  

 YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {  trigger: "filter_name0"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });  
    
