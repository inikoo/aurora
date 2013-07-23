<?php
include_once('common.php');


?>
 var Dom   = YAHOO.util.Dom;
 var Event  =YAHOO.util.Event;
var tables;


function change_block(){
ids=['details','pages','hits','visitors','reports'];
block_ids=['block_details','block_pages','block_hits','block_visitors','block_reports'];
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


var change_view=function(e){
	
	var table=tables['table0'];

if(this.id=='page_visitors'){
tipo='visitors'
}else if(this.id=='page_general'){
tipo='general'

}else{
return
}




	    table.hideColumn('type');
	    table.hideColumn('title');
	    table.hideColumn('users');
	    table.hideColumn('visitors');
	    table.hideColumn('sessions');
	    table.hideColumn('requests');


	    if(tipo=='visitors'){
		Dom.get('page_period_options').style.display='';
		  table.showColumn('users');
	    table.showColumn('visitors');
	    table.showColumn('sessions');
	    table.showColumn('requests');
	    }
	    if(tipo=='general'){
		Dom.get('page_period_options').style.display='none';
		table.showColumn('title');
		table.showColumn('type');
	

	    }
	 

	      Dom.removeClass(Dom.getElementsByClassName('table_option','button' , this.parentNode),'selected')
    Dom.addClass(this,"selected");	

	
	
	    YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=site-pages-view&value=' + escape(tipo),{} );
	
  }




YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


  var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"code", label:"<?php echo _('Code')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"type", label:"<?php echo _('Type')?>",<?php echo($_SESSION['state']['site']['pages']['view']=='general'?'':'hidden:true,')?> width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"title", label:"<?php echo _('Header Title')?>",<?php echo($_SESSION['state']['site']['pages']['view']=='general'?'':'hidden:true,')?> width:270,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"link_title", label:"<?php echo _('Link Label')?>", width:330,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"users", label:"<?php echo _('Users')?>",<?php echo($_SESSION['state']['site']['pages']['view']!='general'?'':'hidden:true,')?>width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"visitors", label:"<?php echo _('Visitors')?>",<?php echo($_SESSION['state']['site']['pages']['view']!='general'?'':'hidden:true,')?> width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"sessions", label:"<?php echo _('Sessions')?>",<?php echo($_SESSION['state']['site']['pages']['view']!='general'?'':'hidden:true,')?> width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"requests", label:"<?php echo _('Requests')?>",<?php echo($_SESSION['state']['site']['pages']['view']!='general'?'':'hidden:true,')?> width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

						    
		
			    
				    
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
			 'id','title','code','url','type','link_title','visitors','sessions','requests','users'
						 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder_page_thumbnails
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
			



var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 

					    {key:"customer", label:"<?php echo _('Customer')?>", width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				,{key:"handle", label:"<?php echo _('Handle')?>", width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
									,{key:"logins", label:"<?php echo _('Logins')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

						,{key:"requests", label:"<?php echo _('Pageviews')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				,{key:"last_visit", label:"<?php echo _('Last Visit')?>", width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				    
				    
				    
				     ];
request="ar_sites.php?tipo=users_in_site&sf=0&tableid=1&parent_key="+Dom.get('site_key').value
//alert(request)
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
			 'customer','handle','requests','logins','last_visit'
						 ]};
	    
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource1, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
								        
									      rowsPerPage:<?php echo$_SESSION['state']['site']['users']['nr']?>,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['site']['users']['order']?>",
									 dir: "<?php echo$_SESSION['state']['site']['users']['order_dir']?>"
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

	    
	    this.table1.filter={key:'<?php echo$_SESSION['state']['site']['users']['f_field']?>',value:'<?php echo$_SESSION['state']['site']['users']['f_value']?>'};


	};
	get_page_thumbnails(0)
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

function update_sitemap(){

   var request = 'ar_edit_sites.php?tipo=update_sitemap&site_key=' + Dom.get('site_key').value
    //alert(request)
    Dom.setStyle("update_sitemap_wait",'display','')
        Dom.setStyle("update_sitemap",'display','none')

    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
           // alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);

            if (r.state == 200) {
Dom.setStyle("update_sitemap_wait",'display','none')
        Dom.setStyle("update_sitemap",'display','')
             Dom.get('sitemap_last_update').innerHTML=r.sitemap_last_update;
             
        Dom.setStyle("sitemap_link",'display','')

            } else {
                alert(r.msg)
            }
        }


    });
	
}

 function init() {
     //'page_period_yeartoday'
     ids = ['page_period_all', 'page_period_year', 'page_period_quarter', 'page_period_month', 'page_period_week', 'page_period_three_year', 'page_period_six_month', 'page_period_ten_day', 'page_period_day', 'page_period_hour', 'page_period_yeartoday'];
     YAHOO.util.Event.addListener(ids, "click", change_period, {
         'table_id': 0,
         'subject': 'page'
     });


     init_search('site');
     ids = ['details', 'pages', 'hits', 'visitors', 'reports'];

     Event.addListener(ids, "click", change_block);
     Event.addListener(['page_general', 'page_visitors'], "click", change_view);

     ids = ['elements_other', 'elements_department_catalogue', 'elements_family_catalogue', 'elements_product_description'];
     Event.addListener(ids, "click", change_elements);



     YAHOO.util.Event.addListener('update_sitemap', "click", update_sitemap);


     YAHOO.util.Event.addListener('clean_table_filter_show0', "click", show_filter, 0);
     YAHOO.util.Event.addListener('clean_table_filter_hide0', "click", hide_filter, 0);

     var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
     oACDS.queryMatchContains = true;
     var oAutoComp = new YAHOO.widget.AutoComplete("f_input0", "f_container0", oACDS);
     oAutoComp.minQueryLength = 0;

     dialog_change_pages_table_type = new YAHOO.widget.Dialog("change_pages_table_type_menu", {
         visible: false,
         close: true,
         underlay: "none",
         draggable: false
     });
     dialog_change_pages_table_type.render();
     YAHOO.util.Event.addListener("change_pages_table_type", "click", show_dialog_change_pages_table_type);


 }

 YAHOO.util.Event.onDOMReady(init);

 YAHOO.util.Event.onContentReady("rppmenu0", function() {
     var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {
         trigger: "rtext_rpp0"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);
 });
 YAHOO.util.Event.onContentReady("filtermenu0", function() {
     var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {
         trigger: "filter_name0"
     });
     oMenu.render();
     oMenu.subscribe("show", oMenu.focus);


 });
