<?php
include_once('common.php');
print "var warehouse_area_key="

?>


YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var LocationsColumnDefs = [
				       {key:"code", label:"<?php echo _('Code')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"area", label:"<?php echo _('Area')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"tipo", label:"<?php echo _('Used for')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"max_weight", label:"<?php echo _('Max Weight')?>",width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"max_volumen", label:"<?php echo _('Max Volume')?>",width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       ,{key:"parts", label:"<?php echo _('Products')?>",sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ];
	    //?tipo=locations&tid=0"
	    this.dataSource0 = new YAHOO.util.DataSource("ar_warehouse.php?tipo=locations&parent=warehouse_area&parent_key="+warehouse_area_key);
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
			 "id"
			 ,"code"
			 ,'location'
			 ,'parts'
			 ,'max_weight'
			 ,'max_volumen','tipo',"area"
			 ]};
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, LocationsColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['warehouse_area']['locations']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['warehouse_area']['locations']['order']?>",
									 dir: "<?php echo$_SESSION['state']['warehouse_area']['locations']['order_dir']?>"
								     },
								     dynamicData : true
								  }
								 );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.filter={key:'<?php echo$_SESSION['state']['warehouse_area']['locations']['f_field']?>',value:'<?php echo$_SESSION['state']['warehouse_area']['locations']['f_value']?>'};
	    
	



	};
    });




 function init(){
 var Dom   = YAHOO.util.Dom;

  init_search('locations');

 var oACDS0 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS0.queryMatchContains = true;
 var oAutoComp0 = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS0);
 oAutoComp0.minQueryLength = 0; 

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {  trigger: "filter_name0"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });


YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.Menu("rppmenu0", { context:["filter_name0","tr", "bl"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("paginator_info0", "click", oMenu.show, null, oMenu);
    });

 var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS1.queryMatchContains = true;
 var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1","f_container1", oACDS1);
 oAutoComp1.minQueryLength = 0; 

YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {  trigger: "filter_name1"  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("filter_name1", "click", oMenu.show, null, oMenu);
    });


YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 var oMenu = new YAHOO.widget.Menu("rppmenu1", { context:["filter_name1","tr", "bl"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("paginator_info1", "click", oMenu.show, null, oMenu);
    });






  var change_view=function(e){
      var tipo=this.id;
      var table=tables['table0'];
      old_view=table.view;
      
      if(tipo=='general'){
	  table.hideColumn('email');
	  table.hideColumn('tel');
	  table.showColumn('location');
	  table.showColumn('last_order');
	  table.showColumn('orders');
	  table.showColumn('super_total');
	  Dom.get('contact').className='';
	  Dom.get('general').className='selected';
      }else{
	  table.showColumn('email');
	  table.showColumn('tel');
	  table.hideColumn('location');
	  table.hideColumn('last_order');
	  table.hideColumn('orders');
	  table.hideColumn('super_total');


      }


      YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=locations-view&value='+escape(tipo));
  }



YAHOO.util.Event.addListener('but_show_details', "click",show_details,'locations');
var ids=['general','contact'];
YAHOO.util.Event.addListener(ids, "click",change_view);
//YAHOO.util.Event.addListener('submit_advanced_search', "click",submit_advanced_search);



 }

YAHOO.util.Event.onDOMReady(init);
