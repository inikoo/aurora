<?php
include_once('common.php');
?>
  //START OF THE TABLE=========================================================================================================================
YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {
		var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var Warehouse_AreaColumnDefs = [
					     {key:"wa_key", label:"", hidden:true,action:"none",isPrimaryKey:true}
					     ,{key:"code", label:"<?php echo _('Area Code')?>",width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'warehouse_area'}
					    ,{key:"name", label:"<?php echo _('Area Name')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'warehouse_area'}
					     ,{key:"description", label:"<?php echo _('Area Description')?>", width:450,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextareaCellEditor({asyncSubmitter: CellEdit}),object:'warehouse_area'}
				      

					 ];
	    //?tipo=warehouse_area&tid=0"
	    this.dataSource0 = new YAHOO.util.DataSource("ar_edit_warehouse.php?tipo=edit_warehouse_areas&parent=warehouse");
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
			 'code',
			 'name','wa_key','description'
			 ]};
	    //__You shouls not change anything from here

	    //this.dataSource.doBeforeCallback = mydoBeforeCallback;



	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, Warehouse_AreaColumnDefs,
								   this.dataSource0
								 , {

								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo $_SESSION['state']['warehouse']['warehouse_area']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo $_SESSION['state']['warehouse']['warehouse_area']['order']?>",
									 dir: "<?php echo $_SESSION['state']['warehouse']['warehouse_area']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

	    this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table0.subscribe("cellClickEvent", onCellClick);

	    this.table0.filter={key:'<?php echo$_SESSION['state']['warehouse']['warehouse_area']['f_field']?>',value:'<?php echo$_SESSION['state']['warehouse']['warehouse_area']['f_value']?>'};
	    YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)
	
	};
    });


var area_data =new Object;


function get_area_data(){
    area_data['Warehouse Key']=Dom.get('warehouse_key').value;
    area_data['Warehouse Area Name']=Dom.get('area_name').value;
    area_data['Warehouse Area Code']=Dom.get('area_code').value;
    area_data['Warehouse Area Description']=Dom.get('area_description').value;

}

function reset_area_data(){
    Dom.get('warehouse_key').value=Dom.get('warehouse_key').getAttribute('ovalue');
    Dom.get('area_name').value=Dom.get('area_name').getAttribute('ovalue');
    Dom.get('area_code').value=Dom.get('area_code').getAttribute('ovalue');
    Dom.get('area_description').value=Dom.get('area_description').getAttribute('ovalue');

}

function add_area(){

    get_area_data();
    var json_value = YAHOO.lang.JSON.stringify(area_data);
    var request='ar_edit_warehouse.php?tipo=new_area&values=' + encodeURIComponent(json_value);
    
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
	  alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.action=='created'){
		
				  window.location='warehouse_area.php?r=nc&id='+r.warehouse_area_key;
return;
		
		    reset_area_data();
		    var table=tables['table0']
		    var datasource=tables['dataSource0'];
		    
		    datasource.sendRequest('',table.onDataReturnInitializeTable, table);      
		}else if(r.action=='error'){
		    alert(r.msg);
		}
			    

			
	    }
	});
}





function init(){
   
    
    YAHOO.util.Event.addListener('add_area', "click", add_area);

   


}
YAHOO.util.Event.onDOMReady(init);
