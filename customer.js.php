<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('common.php');
?>
    var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var dialog_note;

var customer_key=<?php echo $_SESSION['state']['customer']['id']?>;

function showdetails(o){

  

    var history_id=o.getAttribute('hid');
    var details=o.getAttribute('d');
    tr=Dom.getAncestorByTagName(o,'tr');
    row_index=tr.rowIndex+1;
    var table=Dom.getAncestorByTagName(o,'table');
    //alert(o);
    if(details=='no'){
	row_class=tr.getAttribute('class');

	var request="ar_history.php?tipo=history_details&id="+history_id;
	//alert(request)	
YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if (r.state==200) {
			var x=table.insertRow(row_index);
			x.setAttribute('class',row_class);
			x.setAttribute('id','chd'+history_id);

			var c1=x.insertCell(0);
			var c2=x.insertCell(1);
			var c3=x.insertCell(2);
			x.setAttribute('style','padding:10px 0 ;border-top:none')
			c1.innerHTML="";
			c2.innerHTML="";
			c3.setAttribute('style','padding:10px 0 ;');


			c3.setAttribute('colspan',3);
			c3.innerHTML=r.details;
			Dom.get('ch'+history_id).src='art/icons/showed.png';
			Dom.get('ch'+history_id).setAttribute('d','yes');

			
		    }
		       
		}
	    });   
    }else{
	Dom.get('ch'+history_id).src='art/icons/closed.png';
	Dom.get('ch'+history_id).setAttribute('d','no');
	table.deleteRow(row_index);

    }
     
	
}

function save(tipo){
    switch(tipo){
    case('note'):
	var value=encodeURIComponent(Dom.get(tipo+"_input").value);
	var request="ar_edit_contacts.php?tipo=edit_customer&key=Note&customer_key="+customer_key+"&newvalue="+value;
	//alert(request);
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		    //	alert(o.responseText);

		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if (r.state==200) {
			close_dialog(tipo)
			var table=tables['table0'];
			var datasource=tables['dataSource0'];
			var request='';
			datasource.sendRequest(request,table.onDataReturnInitializeTable, table);    

		    }else
			Dom.get(tipo+'_msg').innerHTML=r.msg;
		}
	    });        
	

	break;
    }
};

function change(e,o,tipo){
    switch(tipo){
    case('note'):
	if(o.value!=''){
	    enable_save(tipo);

	    if(window.event)
		key = window.event.keyCode; //IE
	    else
		key = e.which; //firefox     
	    
	    if (key == 13)
		save(tipo);


	}else
	    disable_save(tipo);
	break;
    }
};


function enable_save(tipo){
    switch(tipo){
    case('note'):
	Dom.get(tipo+'_save').style.visibility='visible';
	break;
    }
};

function disable_save(tipo){
    switch(tipo){
    case('note'):
	Dom.get(tipo+'_save').style.visibility='hidden';
	break;
    }
};


function close_dialog(tipo){
    switch(tipo){
  //   case('long_note'):
// 	//Dom.get(tipo+"_input").value='';
// 	dialog_note.hide();

// 	break;
  case('attach'):

	Dom.get(tipo+"_note").value='';
	//	Dom.get(tipo+'_save').style.visibility='hidden';
	dialog_attach.hide();

	break;
    
    case('note'):

	Dom.get(tipo+"_input").value='';
	Dom.get(tipo+'_save').style.visibility='hidden';
	dialog_note.hide();

	break;
    
 case('make_order'):

     //	Dom.get(tipo+"_input").value='';
	//Dom.get(tipo+'_save').style.visibility='hidden';
	dialog_make_order.hide();

	break;
    }


};

 
YAHOO.util.Event.addListener(window, "load", function() {
	    tables = new function() {
		    
		    var tableid=0; // Change if you have more the 1 table
		    var tableDivEL="table"+tableid;  
		    
		    var ColumnDefs = [
				      {key:"date", label:"<?php echo _('Date')?>",className:"aright",width:150,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"time", label:"<?php echo _('Time')?>",className:"aleft",width:50}
				      ,{key:"objeto", label:"<?php echo _('Type')?>", className:"aleft",width:70,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"handle", label:"<?php echo _('Author')?>",className:"aleft",width:80,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				      ,{key:"note", label:"<?php echo _('Notes')?>",className:"aleft",width:400}
					   ];
		
		    this.dataSource0  = new YAHOO.util.DataSource("ar_history.php?tipo=customer_history&tid="+tableid);
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
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		fields: ["note","date","time","handle","objeto" ]};
		    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 5,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['customer']['table']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',alwaysVisible:false,
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['customer']['table']['order']?>",
									 dir: "<?php echo$_SESSION['state']['customer']['table']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
		    this.table0.filter={key:'<?php echo$_SESSION['state']['customer']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['customer']['table']['f_value']?>'};

	    //   YAHOO.util.Event.addListener('f_input', "keyup",myFilterChangeValue,{table:this.table0,datasource:this.dataSource})
			 
	    
	    //	    var Dom   = YAHOO.util.Dom;
	    //alert(Dom.get('f_input'));

	    YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)









    var tableid=1; // Change if you have more the 1 table
		    var tableDivEL="table"+tableid;  
		    
		    var ColumnDefs = [
				      {key:"subject", label:"<?php echo _('Family')?>",className:"aleft",width:60,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   				      ,{key:"description", label:"<?php echo _('Description')?>",className:"aleft",width:270,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				   ,{key:"orders", label:"<?php echo _('Orders')?>",className:"aright",width:60}

				      ,{key:"ordered", label:"<?php echo _('Ordered')?>",className:"aright",width:60}
				      ,{key:"dispached", label:"<?php echo _('Dispached')?>", className:"aright",width:60,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

					   ];
		
		    this.dataSource1  = new YAHOO.util.DataSource("ar_contacts.php?tipo=assets_dispached_to_customer&tid="+tableid);
		    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    this.dataSource1.table_id=tableid;
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
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		fields: ["subject","ordered","dispached","orders","description" ]};
		    this.table1 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource1
								 , {
								     renderLoopSize: 5,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['customer']['assets']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',alwaysVisible:false,
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['customer']['assets']['order']?>",
									 dir: "<?php echo$_SESSION['state']['customer']['assets']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
		    this.table1.filter={key:'<?php echo$_SESSION['state']['customer']['assets']['f_field']?>',value:'<?php echo$_SESSION['state']['customer']['assets']['f_value']?>'};

	   























	
	};
    });


function take_order(){
    location.href='order.php?new=1&customer_key=<?php echo $_SESSION['state']['customer']['id']?>'; 


}

var upload_attach = function(e){
    
    var uploadHandler = {
	upload: function(o) {
	//alert(o.responseText);
	    var r =  YAHOO.lang.JSON.parse(o.responseText);
	    if (r.state==200) {
		close_dialog('attach');
		var table=tables['table0'];
		var datasource=tables['dataSource0'];
		var request='';
		datasource.sendRequest(request,table.onDataReturnInitializeTable, table);    
		
	    }else
		Dom.get('attach_msg').innerHTML=r.msg;
	    
	    
	    
      }
    }; 
    
    YAHOO.util.Connect.setForm('attach_form', true);
    var note=encodeURIComponent(Dom.get('attach_note').value)
    var request="ar_edit_contacts.php?tipo=edit_customer&key=Attach&customer_key="+customer_key+"&newvalue="+note;

    YAHOO.util.Connect.asyncRequest('POST',request, uploadHandler);
}



var oMenu;
function init(){
YAHOO.util.Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 YAHOO.util.Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);
 YAHOO.util.Event.addListener('clean_table_filter_show1', "click",show_filter,1);
 YAHOO.util.Event.addListener('clean_table_filter_hide1', "click",hide_filter,1);
 

search_scope='customers';
//Event.addListener(search_scope+'_submit_search', "click",submit_search,search_scope);
//Event.addListener(search_scope+'_search', "keydown", submit_search_on_enter,search_scope);
 
var store_name_oACDS = new YAHOO.util.FunctionDataSource(search_customers_in_store);
store_name_oACDS.queryMatchContains = true;
var store_name_oAutoComp = new YAHOO.widget.AutoComplete(search_scope+"_search",search_scope+"_search_Container", store_name_oACDS);
store_name_oAutoComp.minQueryLength = 0; 
store_name_oAutoComp.queryDelay = 0.15;


    var alt_shortcuts = function(type, args, obj) {
	if(args[0]==78){
	    window.location=Dom.get("next").href;
	}else if(args[0]==80){
	    window.location=Dom.get("next").href;
	}

    }

    kpl1 = new YAHOO.util.KeyListener(document, { alt:true ,keys:[78,80] }, { fn:alt_shortcuts } );
    kpl1.enable();

   var search_data={tipo:'customer_name',container:'customer'};
Event.addListener('customer_submit_search', "click",submit_search,search_data);
Event.addListener('customer_search', "keydown", submit_search_on_enter,search_data); 


	//Details textarea editor ---------------------------------------------------------------------
	var texteditorConfig = {
	    height: '270px',
	    width: '750px',
	    dompath: true,
	    focusAtStart: true
	};     

 	editor = new YAHOO.widget.Editor('long_note_input', texteditorConfig);

	editor._defaultToolbar.buttonType = 'basic';
 	editor.render();

	//	editor.on('editorKeyUp',change_textarea,'details' );
	//-------------------------------------------------------------


dialog_note = new YAHOO.widget.Dialog("dialog_note", {context:["note","tr","tl"]  ,visible : false,close:false,underlay: "none",draggable:false});
dialog_note.render();

dialog_attach = new YAHOO.widget.Dialog("dialog_attach", {context:["attach","tr","tl"]  ,visible : false,close:false,underlay: "none",draggable:false});
dialog_attach.render();

dialog_make_order = new YAHOO.widget.Dialog("dialog_make_order", {context:["make_order","tr","tl"]  ,visible : false,close:false,underlay: "none",draggable:false});
dialog_make_order.render();


Event.addListener("note", "click", dialog_note.show,dialog_note , true);
Event.addListener("attach", "click", dialog_attach.show,dialog_attach , true);
Event.addListener("make_order", "click", dialog_make_order.show,dialog_make_order , true);


Event.addListener("take_order", "click", take_order , true);
Event.on('upload_attach', 'click', upload_attach);


dialog_long_note = new YAHOO.widget.Dialog("dialog_long_note", {context:["customer_data","tl","tl"] ,visible : false,close:false,underlay: "none",draggable:false});
dialog_long_note.render();
Event.addListener("long_note", "click", dialog_long_note.show,dialog_long_note , true);

//Event.addListener("note", "click", dialog_note.hide,dialog_note , true);



 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
 oAutoComp.minQueryLength = 0; 
  var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS1.queryMatchContains = true;
 oACDS1.table_id=1;
var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1","f_container1", oACDS1);
 oAutoComp1.minQueryLength = 0; 








}

YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {  trigger: "filter_name0"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });

YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 rppmenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 rppmenu.render();
	 rppmenu.subscribe("show", rppmenu.focus);
    });

YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {  trigger: "filter_name1"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("filter_name1", "click", oMenu.show, null, oMenu);
    });

YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 rppmenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1" });
	 rppmenu.render();
	 rppmenu.subscribe("show", rppmenu.focus);
	 YAHOO.util.Event.addListener("rtext_rpp1", "click", rppmenu.show, null, rppmenu);


    });
