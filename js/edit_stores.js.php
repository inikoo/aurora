<?
include_once('../common.php');
?>




var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
function new_store_changed(o){
    if(Dom.get("new_code").value!='' && Dom.get("new_name").value!=''){

	Dom.get("add_new_store").style.display='';
    }else
	Dom.get("add_new_store").style.display='none';



}

function save_new_store(){
    var code=Dom.get('new_code').value;
    var name=Dom.get('new_name').value;
  //   var store_key=0;
	
//     for (var i=0; i<Dom.get("new_store_form").store_key.length; i++)  {
// 	if (Dom.get("new_store_form").store_key[i].checked)  {
// 	    store_key = Dom.get("new_store_form").store_key[i].value;
// 	}
//     } 
    
    var request='ar_edit_assets.php?tipo=new_store&code='+encodeURIComponent(code)+'&name='+encodeURIComponent(name);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
		    var table=tables['table0'];
		    var datasource=tables['dataSource0'];
		    var request='';
		    datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
		    Dom.get('edit_messages').innerHTML='';
		    Dom.get('new_code').value='';
		    Dom.get('new_name').value='';Dom.get("add_new_store").style.display='none';
		}else
		    Dom.get('edit_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	    
	    });

}

YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {



	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"id", label:"<?=_('Key')?>", width:20,sortable:false,isPrimaryKey:true,hidden:true} 

				    ,{key:"code", label:"<?=_('Code')?>", width:230,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'store'}
				    ,{key:"name", label:"<?=_('Name')?>", width:350,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},   editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'store'}
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
		    sort_key:"resultset.sort_key",rtext:"resultset.rtext",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		fields: [
			 'id','code','name','delete',"delete_type"
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?=$_SESSION['state']['store']['table']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?=_('Page')?> {currentPage} <?=_('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
							   ,sortedBy : {
							     key: "<?=$_SESSION['state']['store']['table']['order']?>",
							     dir: "<?=$_SESSION['state']['store']['table']['order_dir']?>"
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







 function init(){
 var Dom   = YAHOO.util.Dom;





 }

YAHOO.util.Event.onDOMReady(init);

