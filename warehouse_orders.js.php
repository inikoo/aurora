<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('common.php');
if(!$user->can_view('orders'))
  exit();
?>

var Dom   = YAHOO.util.Dom;
var assign_picker_dialog;
var pick_it_dialog;
var pick_assigned_dialog;

function close_dialog(dialog_name) {

    switch ( dialog_name ) {
    case 'assign_picker_dialog':
        Dom.get('Assign_Picker_Staff_Name').value='';
        Dom.get('assign_picker_staff_key').value='';
        Dom.get('Assign_Picker_Staff_Name').focus();
        Dom.get('assign_picker_sup_password').value='';
        Dom.removeClass(Dom.getElementsByClassName('assign_picker_button', 'td', 'assign_picker_buttons'),'selected');
        assign_picker_dialog.hide();
        break;
    case('pick_it_dialog'):
    
        Dom.get('pick_it_Staff_Name').value='';
        Dom.get('pick_it_staff_key').value='';
        Dom.setStyle('pick_it_pin_tr','visibility','hidden');
        Dom.get("pick_it_pin_alias").innerHTML='';
        Dom.removeClass(Dom.getElementsByClassName('pick_it_button', 'td', 'assign_picker_buttons'),'selected');
        Dom.get('pick_it_password').value='';
        pick_it_dialog.hide();
        break;
    default:

    }
}
function select_staff(o){
var staff_key=o.getAttribute('staff_id');
var staff_alias=o.innerHTML;
Dom.removeClass(Dom.getElementsByClassName('assign_picker_button', 'td', 'assign_picker_buttons'),'selected');
Dom.addClass(o,'selected');
Dom.get('Assign_Picker_Staff_Name').value=staff_alias;
Dom.get('assign_picker_staff_key').value=staff_key;
Dom.get('assign_picker_sup_password').focus();
}
function select_staff_pick_it(o){
var staff_key=o.getAttribute('staff_id');
var staff_alias=o.innerHTML;
Dom.removeClass(Dom.getElementsByClassName('pick_it_button', 'td', 'pick_it_buttons'),'selected');
Dom.addClass(o,'selected');
Dom.get('pick_it_Staff_Name').value=staff_alias;
Dom.get('pick_it_staff_key').value=staff_key;

Dom.setStyle('pick_it_pin_tr','visibility','visible');
Dom.get("pick_it_pin_alias").innerHTML=staff_alias;
Dom.get('pick_it_password').focus();
}
function assign_picker_save(){

var staff_key=Dom.get('assign_picker_staff_key').value;
 var sup_pwd=   Dom.get('assign_picker_sup_password').value;
var dn_key=Dom.get('assign_picker_dn_key').value;
    var request='ar_edit_orders.php?tipo=assign_picker&dn_key='+escape(dn_key)+'&staff_key='+escape(staff_key)+'&pin='+escape(sup_pwd);
     
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
				//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state==200) {
		    
		    if(r.action='updated'){
		    Dom.get('operations'+dn_key).innerHTML=r.operations;
		    Dom.get('dn_state'+dn_key).innerHTML=r.dn_state;
		    }
		    close_dialog('assign_picker_dialog');

		}else{
		  //  alert(r.msg);
	    }
	    }
	});    

}

function pick_it_save(){

var staff_key=Dom.get('pick_it_staff_key').value;
var sup_pwd=   Dom.get('pick_it_password').value;
var dn_key=Dom.get('pick_it_dn_key').value;
    var request='ar_edit_orders.php?tipo=pick_it&dn_key='+escape(dn_key)+'&staff_key='+escape(staff_key)+'&pin='+escape(sup_pwd);
     
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    
	    success:function(o) {
				alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state==200) {
		    
		    if(r.action='updated'){
		    location.href='order_pick_aid.php?id='+dn_key;
		    }
		    close_dialog('pick_it_dialog');

		}else{
		  alert(r.msg);
	    }
	    }
	});    

}
function assign_picker(o,dn_key){
    var y=(Dom.getY(o))
    var x=(Dom.getX(o))
    x=x-120;
    y=y+18;
    Dom.setX('assign_picker_dialog', x)
    Dom.setY('assign_picker_dialog', y)
   Dom.get('Assign_Picker_Staff_Name').focus();
   Dom.get('assign_picker_dn_key').value=dn_key;
    assign_picker_dialog.show();
}
function pick_it(o,dn_key){
    var y=(Dom.getY(o))
    var x=(Dom.getX(o))
    x=x-120;
    y=y+18;
    Dom.setX('pick_it_dialog', x)
    Dom.setY('pick_it_dialog', y)
   Dom.get('pick_it_Staff_Name').focus();
   Dom.get('pick_it_dn_key').value=dn_key;
    pick_it_dialog.show();
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
 pick_assigned_dialog = new YAHOO.widget.Dialog("pick_assigned_dialog", {visible : false,close:true,underlay: "none",draggable:false});
 pick_assigned_dialog.render();
 pick_it_dialog = new YAHOO.widget.Dialog("pick_it_dialog", {visible : false,close:true,underlay: "none",draggable:false});
 pick_it_dialog.render();

    

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

