<?php
include_once('common.php');
echo 'var user_key='.$_REQUEST['user_id'].';';
echo 'var dashboard_id='.$_REQUEST['dashboard_id'].';';
?>
var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
//var user_id=Dom.get('user_id').value;
//alert(Dom.get('user_id').value)

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
	    var OrdersColumnDefs = [
				       {key:"id", label:"<?php echo _('Widget ID')?>", width:100,sortable:true,isPrimaryKey:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"name", label:"<?php echo _('Widget Name')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				       {key:"widget_block",label:"<?php echo _('Widget Block')?>", width:240,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"widget_dimesnion", label:"<?php echo _('Widget Dimesnion')?>", width:205,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       {key:"description", label:"<?php echo _('Widget Description')?>", width:110,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
				       {key:"add", label:"",width:12,sortable:false,action:'add',object:'widget_list'},
					{key:"user_id", label:"",width:12,sortable:false, isPrimaryKey:true,hidden:true},
					{key:"dashboard_id", label:"",width:12,sortable:false, isPrimaryKey:true,hidden:true}

					 ];

//var ids =Array("restrictions_orders_cancelled","restrictions_orders_suspended","restrictions_orders_unknown","restrictions_orders_dispatched","restrictions_orders_in_process","restrictions_all_orders") ;

		//alert("ar_dashboard.php?tipo=view_widgets&user_id="+user_key+"&dashboard_id="+dashboard_id+"&where=");
	    this.dataSource0 = new YAHOO.util.DataSource("ar_dashboard.php?tipo=view_widgets&user_id="+user_key+"&dashboard_id="+dashboard_id+"&where=");
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
			 "widget_dimesnion",
			 "description",
			 "add",
			"user_id",
			"dashboard_id"
			 ]};

	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       rowsPerPage    : <?php echo $_SESSION['state']['dashboard']['table']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>"
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['dashboard']['table']['order']?>",
									 dir: "<?php echo$_SESSION['state']['dashboard']['table']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
       	    this.table0.subscribe("cellClickEvent", onCellClick);  
	    this.table0.filter={key:'<?php echo$_SESSION['state']['dashboard']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['dashboard']['table']['f_value']?>'};


	};
    });

///////



function edit_dashboard(key){
     var table=tables.table0;
     var datasource=tables.dataSource0;
     //Dom.removeClass(Dom.getElementsByClassName('dispatch','span' , 'dispatch_chooser'),'selected');;
     //Dom.addClass(this,'selected');     
     var request='&dashboard='+key;
	 //alert(request);
     datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
}

function add_widget(key){
	var request='add_widgets.php?tipo=add_widget&user_id='+user_key+'&dashboard_id='+key;
	window.location=request;
       //alert(request);
      YAHOO.util.Connect.asyncRequest('POST',request ,{
	      
	      success:function(o) {
		  	  //alert(o.responseText)
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




}

YAHOO.util.Event.onDOMReady(init);
