<?php
include_once('common.php');
?>

var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var dialog_new_email_campaign;
function change_block(e){
     var ids = ["metrics","newsletter","email","deals","post","media","follow"]; 
    var block_ids = ["block_metrics","block_newsletter","block_email","block_deals","block_post","block_media","block_follow"]; 

	Dom.setStyle(block_ids,'display','none');
	Dom.setStyle('block_'+this.id,'display','');
	Dom.removeClass(ids,'selected');
	Dom.addClass(this, 'selected');
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=marketing-view&value='+this.id ,{});
}

function change_deals_block() {
    ids = ['deals_details', 'campaigns', 'offers'];
    block_ids =['block_deals_details', 'block_campaigns', 'block_offers'];
    Dom.setStyle(block_ids, 'display', 'none');
    Dom.setStyle('block_' + this.id, 'display', '');
    Dom.removeClass(ids, 'selected');
    Dom.addClass(this, 'selected');
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=marketing-deals_block_view&value=' + this.id, {});
}




YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {
	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"name", label:"<?php echo _('Name')?>", width:350,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   	,{key:"date", label:"<?php echo _('Date')?>", width:170,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}


				     ];
	    this.dataSource0 = new YAHOO.util.DataSource("ar_marketing.php?tipo=email_campaigns&parent=store&parent_key="+Dom.get('store_key').value);
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		 rowsPerPage:"resultset.records_perpage",
		    RecordOffset : "resultset.records_offset", 
		       rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		    
		  
		},
		
		fields: [
			"date","store","name"
			 ]};
	    

	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo $_SESSION['state']['marketing']['email_campaigns']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['marketing']['email_campaigns']['order']?>",
									 dir: "<?php echo$_SESSION['state']['marketing']['email_campaigns']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
       this.table0.doBeforeLoadData=mydoBeforeLoadData;


	    

      this.table0.filter={key:'<?php echo$_SESSION['state']['marketing']['email_campaigns']['f_field']?>',value:'<?php echo$_SESSION['state']['marketing']['email_campaigns']['f_value']?>'};



   
	    var tableid=10; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var productsColumnDefs = [
	    
	    				       {key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 

                                        ,{key:"code", label:"<?php echo _('Code')?>", width:110,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                                     ,{key:"description", label:"<?php echo _('Description')?>", width:350,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"duration", label:"<?php echo _('Duration')?>",  width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"orders", label:"<?php echo _('Orders')?>",  width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"customers", label:"<?php echo _('Customers')?>",  width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				 
				 ];
	    //?tipo=products&tid=0"
	    
	    request="ar_deals.php?tipo=deals&parent=store&parent_key="+Dom.get('store_key').value+'&tableid=10&referrer=marketing'
	   // alert(request);
	    this.dataSource10 = new YAHOO.util.DataSource(request);
	    this.dataSource10.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource10.connXhrMode = "queueRequests";
	    this.dataSource10.responseSchema = {
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
		
		fields: ["name","key","description","duration","orders","code","customers"]};
		

	  this.table10 = new YAHOO.widget.DataTable(tableDivEL, productsColumnDefs,
								   this.dataSource10
								 , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 //,initialLoad:false
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo $_SESSION['state']['marketing']['offers']['nr']?>,containers : 'paginator10', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info10'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo $_SESSION['state']['marketing']['offers']['order']?>",
									 dir: "<?php echo $_SESSION['state']['marketing']['offers']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
		this.table10.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table10.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table10.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table10.request=request;
  		this.table10.table_id=tableid;
     	this.table10.subscribe("renderEvent", offers_myrenderEvent);
		this.table10.getDataSource().sendRequest(null, {
		    success: function(request, response, payload) {
		        if (response.results.length == 0) {
		            offers_myrenderEvent()

		        } else {
		             this.onDataReturnInitializeTable(request, response, payload);
		        }
		    },
		    scope: this.table10,
		    argument: this.table10.getState()
		});
	  
	    this.table10.filter={key:'<?php echo $_SESSION['state']['marketing']['offers']['f_field']?>',value:'<?php echo $_SESSION['state']['marketing']['offers']['f_value']?>'};
	    
	    
	    
	    var tableid=11; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var productsColumnDefs = [
	    
	    			{key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
                	,{key:"code", label:"<?php echo _('Code')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
	    			
					,{key:"name", label:"<?php echo _('Name')?>", width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"duration", label:"<?php echo _('Duration')?>",  width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"orders", label:"<?php echo _('Orders')?>",  width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"customers", label:"<?php echo _('Customers')?>",  width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				 
				 ];
	    //?tipo=products&tid=0"
	    request='ar_deals.php?tipo=campaigns&parent=store&parent_key='+Dom.get('store_key').value+'&tableid='+tableid+'&referrer=marketing'
	
	    this.dataSource11 = new YAHOO.util.DataSource(request);
	    this.dataSource11.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource11.connXhrMode = "queueRequests";
	    this.dataSource11.responseSchema = {
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
		
		fields: ["name","key","code","duration","orders","customers"]};
		

	  this.table11 = new YAHOO.widget.DataTable(tableDivEL, productsColumnDefs,
								   this.dataSource11
								 , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 //,initialLoad:false
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo $_SESSION['state']['marketing']['campaigns']['nr']?>,containers : 'paginator11', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info11'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo $_SESSION['state']['marketing']['campaigns']['order']?>",
									 dir: "<?php echo $_SESSION['state']['marketing']['campaigns']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
		this.table11.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table11.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table11.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table11.request=request;
  		this.table11.table_id=tableid;
     	this.table11.subscribe("renderEvent", campaigns_myrenderEvent);
		this.table11.getDataSource().sendRequest(null, {
		    success: function(request, response, payload) {
		        if (response.results.length == 0) {
		            campaigns_myrenderEvent()

		        } else {
		             this.onDataReturnInitializeTable(request, response, payload);
		        }
		    },
		    scope: this.table11,
		    argument: this.table11.getState()
		});	  
	    this.table11.filter={key:'<?php echo $_SESSION['state']['marketing']['campaigns']['f_field']?>',value:'<?php echo $_SESSION['state']['marketing']['campaigns']['f_value']?>'};

	    
	


	};
    });

  
function init(){



  init_search('marketing_store');
    var ids = ["metrics","newsletter","email","deals","post","media","follow"]; 
    YAHOO.util.Event.addListener(ids, "click", change_block);
   
       Event.addListener(['deals_details', 'campaigns', 'offers'], "click", change_deals_block);

   
    YAHOO.util.Event.addListener('clean_table_filter_show0', "click",show_filter,0);
 YAHOO.util.Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);
   

 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
 oAutoComp.minQueryLength = 0; 
 

 dialog_new_email_campaign = new YAHOO.widget.Dialog("dialog_new_email_campaign", {  visible : false,close:true,underlay: "none",draggable:false});
dialog_new_email_campaign.render();

Event.addListener(["new_email_campaign","new_email_campaign2"], "click", show_dialog_new_email_campaign);
 
Event.addListener("save_new_email_campaign", "click", save_new_email_campaign);
Event.addListener("cancel_new_email_campaign", "click", cancel_new_email_campaign);

Event.addListener(["select_text_email","select_html_from_template_email","select_html_email"], "click", change_new_email_campaign_type);

 
}

function change_new_email_campaign_type(){
types=Dom.getElementsByClassName('email_campaign_type', 'button', 'email_campaign_type_buttons')
Dom.removeClass(types,'selected');
Dom.addClass(this,'selected');
Dom.get('email_campaign_type').value=this.id

}



function save_new_email_campaign(){


var store_key=Dom.get('store_key').value;
var email_campaign_name=Dom.get('email_campaign_name').value;
var email_campaign_type=Dom.get('email_campaign_type').value;
	
	switch ( Dom.get('email_campaign_type').value ) {
		case 'select_text_email':
			email_campaign_content_type='Plain';
			break;
		
		case'select_html_email':
		email_campaign_content_type='HTML';
			break;
		
		default:
				email_campaign_content_type='HTML Template';
			break;
	}
	
	
	var data=new Object;
	data={'email_campaign_name':email_campaign_name,'email_campaign_content_type':email_campaign_content_type,'store_key':store_key,'email_campaign_type':'Marketing'}
	 var json_value = my_encodeURIComponent(YAHOO.lang.JSON.stringify(data));
	
var request='ar_edit_marketing.php?tipo=create_email_campaign&values='+json_value

 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
	  //  alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);

		if(r.state==200){
                 location.href='email_campaign.php?id='+r.email_campaign_key
	
		}else{
            Dom.setStyle('new_email_campaign_msg_tr','display','');
            Dom.get('new_email_campaign_msg').innerHTML=r.msg;
	    }
	    }
	    });


}

function cancel_new_email_campaign(){
Dom.get('email_campaign_name').value='';
Dom.get('email_campaign_type').value='select_html_from_template_email';
types=Dom.getElementsByClassName('email_campaign_type', 'button', 'email_campaign_type_buttons')
Dom.removeClass(types,'selected');
Dom.addClass('select_html_from_template_email','selected');
Dom.setStyle('new_email_campaign_msg_tr','display','none');
            Dom.get('new_email_campaign_msg').innerHTML='';
dialog_new_email_campaign.hide();
}

function show_dialog_new_email_campaign(){
var pos = Dom.getXY(this);
pos[0]=pos[0]-410+90;
pos[1]=pos[1]+25;
Dom.setXY('dialog_new_email_campaign', pos);
dialog_new_email_campaign.show()
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

YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1" });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {trigger:"filter_name1"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 
    });

