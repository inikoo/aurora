<?php
include_once('common.php');


?>
 var Dom   = YAHOO.util.Dom;
 var Event  =YAHOO.util.Event;
var tables;
function change_block(){
ids=['details','pages','hits','visitors'];
block_ids=['block_details','block_pages','block_hits','block_visitors'];
Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=site-view&value='+this.id ,{});
}

 

function change_elements(){

ids=['elements_other','elements_department_catalogue','elements_family_catalogue','elements_product_description'];


if(Dom.hasClass(this,'selected')){

var number_selected_elements=0;
for(i in ids){
if(Dom.hasClass(ids[i],'selected')){
number_selected_elements++;
}
}

if(number_selected_elements>1){
Dom.removeClass(this,'selected')

}

}else{
Dom.addClass(this,'selected')

}

table_id=0;
 var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];
var request='';
for(i in ids){
if(Dom.hasClass(ids[i],'selected')){
request=request+'&'+ids[i]+'=1'
}else{
request=request+'&'+ids[i]+'=0'

}
}
  
 // alert(request)
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       


}

YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


  var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"code", label:"<?php echo _('Code')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"type", label:"<?php echo _('Type')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				    ,{key:"title", label:"<?php echo _('Title')?>", width:270,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"url", label:"<?php echo _('URL')?>", width:330,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

						    
				    
				    
				     ];
		request="ar_sites.php?tipo=pages&parent=site&tableid=0&parent_key="+Dom.get('site_key').value;
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
			 'id','title','code','url','type'
						 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
								        
									      rowsPerPage:<?php echo$_SESSION['state']['site']['pages']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['site']['pages']['order']?>",
									 dir: "<?php echo$_SESSION['state']['site']['pages']['order_dir']?>"
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


	    
	    this.table0.filter={key:'<?php echo$_SESSION['state']['site']['pages']['f_field']?>',value:'<?php echo$_SESSION['state']['site']['pages']['f_value']?>'};
		





	};
	get_thumbnails(0)
    });



function change_table_type(parent,tipo,label){

	if(parent=='pages'){
		table_id=0
	}
	
	Dom.get('change_pages_table_type').innerHTML=label;
	
	if(tipo=='list'){
		Dom.setStyle('thumbnails'+table_id,'display','none')
		Dom.setStyle(['table'+table_id,'list_options'+table_id,'table_view_menu'+table_id],'display','')
 	}else{
		Dom.setStyle('thumbnails'+table_id,'display','')
		Dom.setStyle(['table'+table_id,'list_options'+table_id,'table_view_menu'+table_id],'display','none')
 	}
 	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=site-'+parent+'-table_type&value='+escape(tipo),{});
 	dialog_change_pages_table_type.hide();

   
}

function show_dialog_change_pages_table_type(){
	region1 = Dom.getRegion('change_pages_table_type'); 
    region2 = Dom.getRegion('change_pages_table_type_menu'); 
	var pos =[region1.right-region2.width,region1.bottom]
	Dom.setXY('change_pages_table_type_menu', pos);
	dialog_change_pages_table_type.show();
}



 function init(){

  init_search('site');
 Event.addListener(['details','pages','hits','visitors'], "click",change_block);

ids=['elements_other','elements_department_catalogue','elements_family_catalogue','elements_product_description'];
 Event.addListener(ids, "click",change_elements);





  YAHOO.util.Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 YAHOO.util.Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);
 
 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
 oAutoComp.minQueryLength = 0; 

dialog_change_pages_table_type = new YAHOO.widget.Dialog("change_pages_table_type_menu", {visible : false,close:true,underlay: "none",draggable:false});
	dialog_change_pages_table_type.render();
	YAHOO.util.Event.addListener("change_pages_table_type", "click", show_dialog_change_pages_table_type);


 }

YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {trigger:"filter_name0"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	

    });
