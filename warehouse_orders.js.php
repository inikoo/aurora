<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('common.php');
if(!$user->can_view('orders'))
  exit();
?>

var Dom   = YAHOO.util.Dom;
var assign_picker_dialog;

function assign_picker(o){


    var y=(Dom.getY(o))
    var x=(Dom.getX(o))
    x=x-120;
    y=y+18;
    //    alert(y);
    Dom.setX('assign_picker_dialog', x)
    Dom.setY('assign_picker_dialog', y)
    //    add_user_dialog_staff.cfg.setProperty("x", "500");
    //add_user_dialog_staff.cfg.setProperty("y", 500);

   // var user_id=o.getAttribute('user_id');
    //var user_name=o.getAttribute('user_name');
    //Dom.get("change_staff_password_alias").setAttribute('user_id',user_id);
    //Dom.get("change_staff_password_alias").innerHTML=user_name;
    assign_picker_dialog.show();
    


}


YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {
	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [
				       {key:"public_id", label:"<?php echo _('Order ID')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				   {key:"status",label:"<?php echo _('Type')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				   {key:"date", label:"<?php echo _('Last Updated')?>", width:175,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				     
				       {key:"weight", label:"<?php echo _('Weight')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"picks", label:"<?php echo _('Picks')?>", width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				       {key:"operations", label:"<?php echo _('Operations')?>", width:160,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},

				];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_edit_orders.php?tipo=ready_to_pick_orders");
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
			 "id","public_id",
			 "weight","picks",
			 "customer",
			 "date","picker","packer","status","operations"
			
			 ]};

	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : <?php echo$_SESSION['state']['orders']['ready_to_pick_dn']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['orders']['ready_to_pick_dn']['order']?>",
									 dir: "<?php echo$_SESSION['state']['orders']['ready_to_pick_dn']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.filter={key:'<?php echo$_SESSION['state']['orders']['ready_to_pick_dn']['f_field']?>',value:'<?php echo$_SESSION['state']['orders']['ready_to_pick_dn']['f_value']?>'};

	    

	};
    });




function init(){
 assign_picker_dialog = new YAHOO.widget.Dialog("assign_picker_dialog", {visible : false,close:true,underlay: "none",draggable:false});
 assign_picker_dialog.render();

    

 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
 oAutoComp.minQueryLength = 0; 



 

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
	YAHOO.util.Event.addListener("rtext_rpp0", "click",oMenu.show , null, oMenu);

    });

