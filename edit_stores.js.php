<?php
include_once('common.php');


?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
var can_add_store=false;
var editing='<?php echo $_SESSION['state']['stores']['edit']?>';



var validate_scope_data=
{
    'corporation':{
	'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'name','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid HQ Name')?>'}],dbname:'HQ Name'},
	'currency':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'currency','validation':[{'regexp':"[a-z]{3}",'invalid_msg':'<?php echo _('Invalid Currency Code')?>'}],dbname:'HQ Currency'}

  }
  
};

var validate_scope_metadata={
'corporation':{'type':'edit','ar_file':'ar_edit_contacts.php','key_name':'','key':false}

};









var todo_after_select_country= function(){
    new_store_changed()
    return;
}
function new_store_changed(){

  
    if(Dom.get("new_code").value!='' && Dom.get("new_name").value!='' &&  Dom.get("address_country_code").value!='' ){

	can_add_store=true;
	 Dom.removeClass('save_new_store','disabled');
    }else{
	
	Dom.addClass('save_new_store','disabled');
	 can_add_store=false;
    }


}




function save_new_store(){

    if(can_add_store==false){
	
	return;
    }
    var code=Dom.get('new_code').value;
    var name=Dom.get('new_name').value;
    var country_code=Dom.get('address_country_code').value;
  //   var store_key=0;
	
//     for (var i=0; i<Dom.get("new_store_form").store_key.length; i++)  {
// 	if (Dom.get("new_store_form").store_key[i].checked)  {
// 	    store_key = Dom.get("new_store_form").store_key[i].value;
// 	}
//     } 
    
    var request='ar_edit_assets.php?tipo=new_store&code='+encodeURIComponent(code)+'&name='+encodeURIComponent(name);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
		    var table=tables['table0'];
		    var datasource=tables['dataSource0'];
		    var request='';
		    datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
		    Dom.get('edit_messages').innerHTML='';
		    Dom.get('new_code').value='';
		    Dom.get('new_name').value='';
		    Dom.get('address_country_code').value='';
		    Dom.get('address_country').value='';
		    hide_add_store_dialog();
		   
		}else{
		    Dom.get('new_store_messages').innerHTML='<span class="error">'+r.msg+'</span>';

		}
	    }
	    
	    });

}

YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"id", label:"<?php echo _('Key')?>", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				    ,{key:"go", label:"", width:40,sortable:false,hidden:false} 

				    ,{key:"code", label:"<?php echo _('Code')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'store'}
				    ,{key:"name", label:"<?php echo _('Name')?>", width:350,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},   editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'store'}
				    ,{key:"delete", label:"", width:70,sortable:false,className:"aleft",action:'delete',object:'store'}
				    ,{key:"delete_type", label:"",hidden:true,isTypeKey:true}
				     ];
	    
	    this.dataSource0 = new YAHOO.util.DataSource("ar_edit_assets.php?tipo=edit_stores");
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
		    totalRecords: "resultset.total_records"		},
		
		fields: [
			 'id','code','name','delete',"delete_type","go"
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['stores']['stores']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
							   ,sortedBy : {
							    Key: "<?php echo$_SESSION['state']['stores']['stores']['order']?>",
							     dir: "<?php echo$_SESSION['state']['stores']['stores']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;


	    
 // Set up editing flow
	    var highlightEditableCell = function(oArgs) {
		var elCell = oArgs.target;
		if(YAHOO.util.Dom.hasClass(elCell, "yui-dt-editable")) {
		    this.highlightCell(elCell);
		}
	    };
	    this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table0.subscribe("cellMouseoutEvent", this.table0.onEventUnhighlightCell);
	    this.table0.subscribe("cellClickEvent", onCellClick);


		





	};
    });




function change_block(e){
     if(editing!=this.id){
	 Dom.get('d_stores').style.display='none';
	 Dom.get('d_description').style.display='none';
	 Dom.get('d_'+this.id).style.display='';
	 Dom.removeClass(editing,'selected');
	 Dom.addClass(this, 'selected');
	 YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=stores-edit&value='+this.id ,{});
	editing=this.id;
    }
}

function cancel_add_store(){
    Dom.get('new_code').value='';
    Dom.get('new_name').value='';
    Dom.get('address_country_code').value='';
    Dom.get('address_country').value='';

    hide_add_store_dialog(); 
}


function hide_add_store_dialog(){
    Dom.get('new_store_dialog').style.display='none';
    Dom.get('add_store').style.display='';
    Dom.get('save_store').style.display='none';
    Dom.get('close_add_store').style.display='none';
}

function show_add_store_dialog(){
    Dom.get('new_store_dialog').style.display='';
    Dom.get('add_store').style.display='none';
    Dom.get('save_store').style.display='';
    Dom.addClass('save_store','disabled');
   Dom.get('close_add_store').style.display='';
    
}

function init(){

  init_search('products');

     var ids = ["description","stores"]; 
     YAHOO.util.Event.addListener(ids, "click", change_block);
     YAHOO.util.Event.addListener('add_store', "click", show_add_store_dialog);

     YAHOO.util.Event.addListener('save_new_store', "click",save_new_store);
     YAHOO.util.Event.addListener('close_add_store', "click", cancel_add_store);

     var Countries_DS = new YAHOO.util.FunctionDataSource(match_country);
     Countries_DS.responseSchema = {fields: ["id", "name", "code","code2a"]}
     var Countries_AC = new YAHOO.widget.AutoComplete("address_country", "address_country_container", Countries_DS);
     Countries_AC.useShadow = true;
     Countries_AC.resultTypeList = false;
     Countries_AC.formatResult = country_formatResult;
     Countries_AC.itemSelectEvent.subscribe(onCountrySelected);
     
     
     var Countries_AC = new YAHOO.widget.AutoComplete("currency", "currency_container", Countries_DS);
     Countries_AC.useShadow = true;
     Countries_AC.resultTypeList = false;
     Countries_AC.formatResult = country_formatResult;
     Countries_AC.itemSelectEvent.subscribe(onCountrySelected);
     

}



 

YAHOO.util.Event.onDOMReady(init);

