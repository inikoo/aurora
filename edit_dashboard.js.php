<?php
include_once('common.php');

?>
var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;


function changeHeight(iframe){
        try
        {
        
         
          var innerDoc = (iframe.contentDocument) ? iframe.contentDocument : iframe.contentWindow.document;
          
        
          if (innerDoc.body.offsetHeight) //ns6 syntax
          {
         // alert(innerDoc.body.offsetHeight)

            Dom.setStyle(iframe,'height',innerDoc.body.offsetHeight + 32  +'px');

             //iframe.height = innerDoc.body.offsetHeight + 32  +'px'; //Extra height FireFox
          }
          else if (iframe.Document && iframe.Document.body.scrollHeight) //ie5+ syntax
          {
                  Dom.setStyle(iframe,'height',iframe.Document.body.scrollHeight + 32  +'px');

          }else{
         alert("x")
          Dom.setStyle(iframe,'height','700px');
            
          }
        }
        catch(err)
        {
          alert(err.message);
        }
      }


function sliders(){
var panes=Dom.getElementsByClassName('pane', 'div', 'content');

for (var j = 0; j < panes.length; j++) {
Dom.setStyle(panes[j],'display','')
}


myTabs = new SlidingTabs('buttons', 'panes');
			
			// this sets up the previous/next buttons, if you want them
			$('previous').addEvent('click', myTabs.previous.bind(myTabs));
			$('next').addEvent('click', myTabs.next.bind(myTabs));
			
			// this sets it up to work even if it's width isn't a set amount of pixels
			window.addEvent('resize', myTabs.recalcWidths.bind(myTabs));
}

function select_widget_from_list(oArgs){

widget_id=tables.table0.getRecord(oArgs.target).getData('id');
//alert(widget_id);
dialog_widget_list.hide();


	var request='ar_edit_dashboard.php?tipo=add_widget_to_dashboard&dashboard_key='+Dom.get('dashboard_key').value+'&widget_key='+widget_id;
	//window.location=request;
       //alert(request);
      YAHOO.util.Connect.asyncRequest('POST',request ,{
	      
	      success:function(o) {
		  	 // alert(o.responseText)
		      var r =  YAHOO.lang.JSON.parse(o.responseText);
		  if (r.state == 200) {
			window.location.reload();
		  }else{
		      alert(r.msg);
		  }
	      }
	  });   

    

}


function change_block(o){
var buttons=Dom.getElementsByClassName('splinter_buttons', 'li', 'buttons');
var panes=Dom.getElementsByClassName('pane', 'div', 'content');

Dom.removeClass(buttons,'active');
Dom.addClass(o,'active');
Dom.setStyle(panes,'display','none');
//alert(o.getAttribute('key'))
Dom.setStyle('pane_'+o.getAttribute('key'),'display','');
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=home-display&value='+escape(o.getAttribute('key')),{});

}

///////
Event.addListener(window, "load", function() {
    tables = new function() {
    
    	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var Dom = YAHOO.util.Dom;
            var Event = YAHOO.util.Event;
            var DDM = YAHOO.util.DragDropMgr;
		OrdersColumnDefs = [
				      // {key:"id", label:"<?php echo _('Widget ID')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				    		//		    {key:"widget_order", label:"<?php echo _('Position')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},

				    {key:"name", label:"<?php echo _('Widget Name')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				     //  {key:"widget_block",label:"<?php echo _('Widget Block')?>", width:240,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				      // {key:"widget_dimension", label:"<?php echo _('Widget Dimension')?>", width:205,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"description", label:"<?php echo _('Widget Description')?>", width:110,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				       {key:"delete", label:"",width:12,sortable:false,action:'delete',object:'widget_list'},
					 {key:"id", label:"",width:100,hidden:true}

					 ];

//var ids =Array("restrictions_orders_cancelled","restrictions_orders_suspended","restrictions_orders_unknown","restrictions_orders_dispatched","restrictions_orders_in_process","restrictions_all_orders") ;

		//alert("ar_dashboard.php?tipo=list_widgets&user_id="+Dom.get('user_key').value+"&where=");
	    this.dataSource0 = new YAHOO.util.DataSource("ar_dashboard.php?tipo=list_widgets&user_id="+Dom.get('user_key').value);
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
			 "id",
			 "name",
			 "widget_block",
			 "widget_dimension",
			 "description",
			 "delete",
			"user_id",
			"dashboard_id",
			"key"
			 ]};

	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : <?php echo $_SESSION['state']['dashboards']['widgets']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['dashboards']['widgets']['order']?>",
									 dir: "<?php echo$_SESSION['state']['dashboards']['widgets']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );



	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    
	   this.table0.table_id=tableid;
     this.table0.subscribe("renderEvent", myrenderEvent);
this.table0.subscribe("rowClickEvent", select_widget_from_list);
	    
       	    this.table0.subscribe("cellClickEvent", onCellClick);  
	    this.table0.filter={key:'<?php echo$_SESSION['state']['dashboards']['widgets']['f_field']?>',value:'<?php echo$_SESSION['state']['dashboards']['widgets']['f_value']?>'};


    
	    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var Dom = YAHOO.util.Dom;
            var Event = YAHOO.util.Event;
            var DDM = YAHOO.util.DragDropMgr;
		OrdersColumnDefs = [
				   //    {key:"id", label:"<?php echo _('Widget ID')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				      {key:"widget_order", label:"<?php echo _('Position')?>", width:50,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				     				      {key:"widget_order_up", label:"", width:5,sortable:false,className:"aleft"},
				     				      {key:"widget_order_down", label:"", width:20,sortable:false,className:"aleft"},

				     {key:"widget_block",label:"<?php echo _('Columns')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				      {key:"widget_height", label:"<?php echo _('Height')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'widget'},

				     {key:"name", label:"<?php echo _('Widget Name')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				       {key:"description", label:"<?php echo _('Widget Description')?>", width:370,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				       {key:"delete", label:"",width:12,sortable:false,action:'delete',object:'widget_in_dashboard'},
					{key:"dashboard_key", label:"",width:12,sortable:false, isPrimaryKey:true,hidden:true},
										{key:"widget_key", label:"",width:12,sortable:false, isPrimaryKey:true,hidden:true},
{key:"dashboard_widget_key", label:"",width:12,sortable:false, isPrimaryKey:true,hidden:true},

					 ];

	 request="ar_edit_dashboard.php?tipo=list_widgets_in_dashboard&parent=dashboard&parent_key="+Dom.get('dashboard_key').value+'tableid='+tableid;
	//alert(request)
	 this.dataSource1 = new YAHOO.util.DataSource(request);
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
			 "id",
			 "name",
			 "widget_block",
			 "widget_height","widget_order","widget_order_up","widget_order_down",
			 "description",
			 "delete",
			"user_id",
			"dashboard_key","dashboard_widget_key",
			"widget_key"
			 ]};

	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource1, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : <?php echo $_SESSION['state']['dashboards']['active_widgets']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['dashboard']['active_widgets']['order']?>",
									 dir: "<?php echo$_SESSION['state']['dashboard']['active_widgets']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
//////////////////
	    var myDTDTargets = {};
            var onRowSelect = function(ev) {
                    var par = this.table1.getTrEl(Event.getTarget(ev)),
                        srcData,
                        srcIndex,
                        tmpIndex = null,
                        ddRow = new YAHOO.util.DDProxy(par.id);

                    ddRow.handleMouseDown(ev.event);


                    /**
                    * Once we start dragging a row, we make the proxyEl look like the src Element. We get also cache all the data related to the
                    * @return void
                    * @static
                    * @method startDrag
                    */
                    ddRow.startDrag = function () {
                        proxyEl  = this.getDragEl();
                        srcEl = this.getEl();
                        srcData = this.table1.getRecord(srcEl).getData();
                        srcIndex = srcEl.sectionRowIndex;
                        // Make the proxy look like the source element
                        Dom.setStyle(srcEl, "visibility", "hidden");
                        proxyEl.innerHTML = "<table><tbody>"+srcEl.innerHTML+"</tbody></table>";
                    };

                    /**
                    * Once we end dragging a row, we swap the proxy with the real element.
                    * @param x : The x Coordinate
                    * @param y : The y Coordinate
                    * @return void
                    * @static
                    * @method endDrag
                    */
                    ddRow.endDrag = function(x,y) {
                        Dom.setStyle(proxyEl, "visibility", "hidden");
                        Dom.setStyle(srcEl, "visibility", "");
                    };


                    /**
                    * This is the function that does the trick of swapping one row with another.
                    * @param e : The drag event
                    * @param id : The id of the row being dragged
                    * @return void
                    * @static
                    * @method onDragOver
                    */
                    ddRow.onDragOver = function(e, id) {
                        // Reorder rows as user drags

                        var destEl = Dom.get(id),
                            destIndex = destEl.sectionRowIndex;



                        if (destEl.nodeName.toLowerCase() === "tr") {
                            if(tmpIndex !==null) {
                                this.table1.deleteRow(tmpIndex);
                            }
                            else {
                                this.table1.deleteRow(srcIndex);
                            }

                        this.table1.addRow(srcData, destIndex);
                        tmpIndex = destIndex;


                        DDM.refreshCache();
                        }
                    };
            };
		
	    this.table1.subscribe('cellMousedownEvent', onRowSelect);


        //////////////////////////////////////////////////////////////////////////////
        // Create DDTarget instances when DataTable is initialized
        //////////////////////////////////////////////////////////////////////////////
        this.table1.subscribe("initEvent", function() {

            var i, id,
                allRows = this.getTbodyEl().rows;


            for(i=0; i<allRows.length; i++) {
                id = allRows[i].id;
                // Clean up any existing Drag instances
                if (myDTDTargets[id]) {
                     myDTDTargets[id].unreg();
                     delete myDTDTargets[id];
                }
                // Create a Drag instance for each row
                myDTDTargets[id] = new YAHOO.util.DDTarget(id);
            }

        });

        //////////////////////////////////////////////////////////////////////////////
        // Create DDTarget instances when new row is added
        //////////////////////////////////////////////////////////////////////////////
        this.table1.subscribe("rowAddEvent",function(e){
            var id = e.record.getId();
            myDTDTargets[id] = new YAHOO.util.DDTarget(id);
	  
        });

///////////////////


	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    
	   this.table1.table_id=tableid;
     this.table1.subscribe("renderEvent", myrenderEvent);

	    
       	    this.table1.subscribe("cellClickEvent", onCellClick);  
	    this.table1.filter={key:'<?php echo$_SESSION['state']['dashboard']['active_widgets']['f_field']?>',value:'<?php echo$_SESSION['state']['dashboard']['active_widgets']['f_value']?>'};





	};
    });





function add_widget(){
	region1 = Dom.getRegion('add_widget'); 
	region2 = Dom.getRegion('dialog_widget_list'); 

	var pos =[region1.right-region2.width,region1.bottom]

	Dom.setXY('dialog_widget_list', pos);

	dialog_widget_list.show();


}

function widget_order_down(dashboard_widget_key){
   var request='ar_edit_dashboard.php?tipo=widget_order_down&dashboard_widget_key='+dashboard_widget_key;
      // alert(request);
      YAHOO.util.Connect.asyncRequest('POST',request ,{
	      
	      success:function(o) {
		  	 // alert(o.responseText)
		      var r =  YAHOO.lang.JSON.parse(o.responseText);
		  if (r.state == 200) {
			window.location.reload();
		  }else{
		      alert(r.msg);
		  }
	      }
	  });   
}
function widget_order_up(dashboard_widget_key){
   var request='ar_edit_dashboard.php?tipo=widget_order_up&dashboard_widget_key='+dashboard_widget_key;
      // alert(request);
      YAHOO.util.Connect.asyncRequest('POST',request ,{
	      
	      success:function(o) {
		  	//  alert(o.responseText)
		      var r =  YAHOO.lang.JSON.parse(o.responseText);
		  if (r.state == 200) {
			window.location.reload();
		  }else{
		      alert(r.msg);
		  }
	      }
	  });   
}


function init(){


 YAHOO.util.Event.addListener('add_widget', "click",add_widget);
dialog_widget_list = new YAHOO.widget.Dialog("dialog_widget_list", {context:["widget_add","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_widget_list.render();

}

YAHOO.util.Event.onDOMReady(init);
