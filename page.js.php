<?php
include_once('common.php');


?>
 var Dom   = YAHOO.util.Dom;
 var Event  =YAHOO.util.Event;

function change_block(){
ids=['details','hits','visitors','users'];
block_ids=['block_details','block_hits','block_visitors','block_users'];
Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');
YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=page-view&value='+this.id ,{});
}



function recapture_preview() {
    Dom.setStyle('recapture_preview_processing', 'display', '')
    Dom.setStyle('recapture_preview', 'display', 'none')
    //alert('ar_edit_sites.php?tipo=update_page_preview_snapshot&id='+Dom.get('page_key').value)


   request='ar_edit_sites.php?tipo=update_preview_snapshot&parent=Page&parent_key=' + Dom.get('page_key').value

    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
        //    alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            Dom.setStyle('recapture_preview_processing', 'display', 'none')
            Dom.setStyle('recapture_preview', 'display', '')
            Dom.get('capture_preview_date').innerHTML = ', ' + r.formated_date

            Dom.get('page_preview_snapshot').src = 'image.php?id=' + r.image_key

        }
    });
}

function recapture_page() {
    YAHOO.util.Connect.asyncRequest('POST', 'ar_edit_sites.php?tipo=update_page_preview_snapshot&id=' + Dom.get('page_key').value, {
        success: function(o) {
          //  alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);
            //Dom.get('page_preview_snapshot_image').src='image.php?id='+r.image_key
        }
    });
}


YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

 var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 

				    {key:"customer", label:"<?php echo _('Name')?>", width:180,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"date", label:"<?php echo _('Date')?>", width:150,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}		    

	//	,{key:"handle", label:"<?php echo _('Email')?>", width:150,hidden:true,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"previous_page", label:"<?php echo _('Previous Page')?>", width:500,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			//	,{key:"ip", label:"<?php echo _('IP')?>", width:100,hidden:true,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    
				    
				     ];

request="ar_sites.php?tipo=requests&parent=page&tableid=0&parent_key="+Dom.get('page_key').value

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
			 'customer','handle','ip','date','previous_page'
						 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
								        

									      rowsPerPage:<?php echo$_SESSION['state']['page']['requests']['nr']?>,containers : 'paginator0', 

 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {

									 key: "<?php echo$_SESSION['state']['page']['requests']['order']?>",
									 dir: "<?php echo$_SESSION['state']['page']['requests']['order_dir']?>"

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

	    

	    this.table0.filter={key:'<?php echo$_SESSION['state']['page']['requests']['f_field']?>',value:'<?php echo$_SESSION['state']['page']['requests']['f_value']?>'};





 var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 

				    {key:"customer", label:"<?php echo _('Customer')?>", width:160,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				,{key:"handle", label:"<?php echo _('Handle')?>", width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"last_visit", label:"<?php echo _('Last Visit')?>", width:280,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"visits", label:"<?php echo _('Visits')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

						    
				    
				    
				     ];


request="ar_sites.php?tipo=users&parent=page&tableid=1&parent_key="+Dom.get('page_key').value


	    this.dataSource1 = new YAHOO.util.DataSource(request);
	    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
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
		    totalRecords: "resultset.total_records"
		},
		
		fields: [
			 'customer','handle','visits','last_visit'
						 ]};
	    
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource1, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
								        

									      rowsPerPage:<?php echo$_SESSION['state']['page']['users']['nr']?>,containers : 'paginator1', 

 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {

									 key: "<?php echo$_SESSION['state']['page']['users']['order']?>",
									 dir: "<?php echo$_SESSION['state']['page']['users']['order_dir']?>"

								     }
							   ,dynamicData : true

						     }
						     );
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
 this.table1.request=request;
  this.table1.table_id=tableid;
     this.table1.subscribe("renderEvent", myrenderEvent);

	    

	    this.table1.filter={key:'<?php echo$_SESSION['state']['page']['users']['f_field']?>',value:'<?php echo$_SESSION['state']['page']['users']['f_value']?>'};


	};
    });
function show_edit_flag_dialog(){
	dialog_edit_flag.show();

}
function save_page_flag(key, value, page_key) {

	
    var request = 'ar_edit_sites.php?tipo=edit_page_flag&key=' + key + '&newvalue=' + value + '&id=' + page_key + '&okey=' + key
	
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);


            if (r.state == 200) {

                Dom.get('edit_flag_label').innerHTML = r.flag_label;
                Dom.get('edit_flag_icon').src = 'art/icons/' + r.flag_icon;


                Dom.removeClass(Dom.getElementsByClassName('flag'), 'selected')
                Dom.addClass('flag_' + r.newvalue, 'selected')
                dialog_edit_flag.hide()


                //	window.location.reload()
            }

        }
    });

}


function set_online(){




key='Page State';
  var request = 'ar_edit_sites.php?tipo=edit_page_state&key=' + key + '&newvalue=Online&id=' + Dom.get('page_key').value + '&okey=page_state' 
	
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            var r = YAHOO.lang.JSON.parse(o.responseText);


            if (r.state == 200) {

                Dom.get('formated_page_state').innerHTML = r.formated_state;
                Som.setStyle('set_online','display','none')
              
            }

        }
    });

}

 function init() {

     init_search('site');

     YAHOO.util.Event.addListener('recapture_page', "click", recapture_page);
     YAHOO.util.Event.addListener('recapture_preview', "click", recapture_preview);
     YAHOO.util.Event.addListener('set_online', "click", set_online);


     Event.addListener(['details', 'hits', 'visitors', 'users'], "click", change_block);


     YAHOO.util.Event.addListener('clean_table_filter_show0', "click", show_filter, 0);
     YAHOO.util.Event.addListener('clean_table_filter_hide0', "click", hide_filter, 0);

     var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
     oACDS.queryMatchContains = true;
     var oAutoComp = new YAHOO.widget.AutoComplete("f_input0", "f_container0", oACDS);
     oAutoComp.minQueryLength = 0;
     
         Event.addListener("edit_flag", "click", show_edit_flag_dialog);
    dialog_edit_flag = new YAHOO.widget.Dialog("dialog_edit_flag", {
        context: ["edit_flag", "tr", "br"],
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_edit_flag.render();


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
