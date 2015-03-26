
var Dom   = YAHOO.util.Dom;
var dialog_new_list;
var session_data,labels,state;


function change_block(){
	ids=['details','deals','orders','customers'];
	block_ids=['block_details','block_deals','block_orders','block_customers'];
	Dom.setStyle(block_ids,'display','none');
	Dom.setStyle('block_'+this.id,'display','');
	Dom.removeClass(ids,'selected');
	Dom.addClass(this,'selected');

	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=campaign-view&value='+this.id ,{});

}

YAHOO.util.Event.addListener(window, "load", function() {

session_data=YAHOO.lang.JSON.parse(base64_decode(Dom.get('session_data').value));
	labels=session_data.label;
	state=session_data.state;

	tables = new function() {

		var tableid=0;
		var tableDivEL="table"+tableid;


		var ColumnDefs = [
		{key:"order", label:labels.Number, width:90,className:"aleft", sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
		{key:"customer_name", label:labels.Customer, width:220, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
		{key:"date", label:labels.Date, sortable:true, width:100,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
		];
		request="ar_deals.php?tipo=orders&parent=campaign&parent_key="+Dom.get('campaign_key').value+"&tableid="+tableid;
		
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
			"id","order","customer_name","date","dispatched","undispatched"
			]
		};

		this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
			this.dataSource0, { renderLoopSize: 50,generateRequest : myRequestBuilder,paginator : new YAHOO.widget.Paginator({
				rowsPerPage:state.orders.nr,
				containers : 'paginator0',
				pageReportTemplate : '('+labels.Page+' {currentPage} '+labels.of+' {totalPages})',
				previousPageLinkLabel : "<",
				nextPageLinkLabel : ">",
				firstPageLinkLabel :"<<",
				lastPageLinkLabel :">>",
				rowsPerPageOptions : [10,25,50,100,250,500],
				alwaysVisible:false,
				template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
			})

			,sortedBy : {
				key: state.orders.order,
				dir: state.orders.order_dir
			}
			,dynamicData : true});
		this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
		this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
		this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table0.table_id=tableid;
		this.table0.subscribe("renderEvent", myrenderEvent);
		this.table0.filter={key:state.orders.f_field,value:state.orders.f_value};



		tableid=1;
		tableDivEL="table"+tableid;

		ColumnDefs = [
		{key:"id", label:labels.Id,width:45,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
		{key:"name", label:labels.Name,width:270, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
		{key:"location", label:labels.Location,width:250, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
		{key:"orders", label:labels.Orders,width:70, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
		];


		this.dataSource1 = new YAHOO.util.DataSource("ar_deals.php?tipo=customers&parent=campaign&parent_key="+Dom.get('campaign_key').value+"&tableid="+tableid);
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
			"name","dispatched","nodispatched","charged","to_dispatch","orders","location","id"
			]
		};



		this.table1 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
			this.dataSource1, {
						//draggableColumns:true,
						renderLoopSize: 50,generateRequest : myRequestBuilder,
						paginator : new YAHOO.widget.Paginator({
							rowsPerPage:state.customers.nr,
							containers : 'paginator1',
							pageReportTemplate : '('+labels.Page+' {currentPage} '+labels.of+' {totalPages})',
							previousPageLinkLabel : "<",
							nextPageLinkLabel : ">",
							firstPageLinkLabel :"<<",
							lastPageLinkLabel :">>",alwaysVisible:false,
							template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"}),
						sortedBy : {
							key: state.customers.order,
							dir: state.customers.order_dir
						},
						dynamicData : true

					});
		this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
		this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
		this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table1.table_id=tableid;
		this.table1.subscribe("renderEvent", myrenderEvent);
		this.table1.filter={key:state.customers.f_field,value:state.customers.f_value};

        tableid=2; // Change if you have more the 1 table
        tableDivEL="table"+tableid;
        var productsColumnDefs = [




        {key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true},
        {key:"code", label:labels.Code, width:110,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
        {key:"description", label:labels.Description, width:350,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
        {key:"orders", label:labels.Orders,  width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
        {key:"customers", label:labels.Customers,  width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
        {key:"duration", label:labels.Duration,  width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
        ];

        request="ar_deals.php?tipo=deals&parent=campaign&parent_key="+Dom.get('subject_key').value+'&tableid='+tableid;
        this.dataSource2 = new YAHOO.util.DataSource(request);
        this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
        this.dataSource2.connXhrMode = "queueRequests";
        this.dataSource2.responseSchema = {
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

        	fields: ["name","key","allowance","duration","orders","code","customers","target","terms","code","description"]};


        	this.table2 = new YAHOO.widget.DataTable(tableDivEL, productsColumnDefs,this.dataSource2
        		,
        		{
        			renderLoopSize: 50,generateRequest : myRequestBuilder,
        			paginator : new YAHOO.widget.Paginator({
        				rowsPerPage:state.offers.nr,containers : 'paginator1',
        				pageReportTemplate : '('+labels.Page+' {currentPage} '+labels.of+' {totalPages})',
        				previousPageLinkLabel : "<",
        				nextPageLinkLabel : ">",
        				firstPageLinkLabel :"<<",
        				lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
        				,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



        			})

        			,sortedBy : {
        				key: state.offers.order,
        				dir: state.offers.order_dir
        			},
        			dynamicData : true

        		}

        		);

        	this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
        	this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
        	this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
        	this.table2.request=request;
        	this.table2.table_id=tableid;
        	this.table2.subscribe("renderEvent", myrenderEvent);
        	this.table2.getDataSource().sendRequest(null, {
        		success: function(request, response, payload) {
        			if (response.results.length == 0) {
			//   get_part_elements_numbers()

		} else {
			// this.onDataReturnInitializeTable(request, response, payload);
		}
	},
	scope: this.table2,
	argument: this.table2.getState()
});
        	this.table2.filter={key:state.offers.f_field,value:state.offers.f_value};
        };
    });




function update_objects_table(){

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
	data={'email_campaign_name':email_campaign_name,'email_campaign_content_type':email_campaign_content_type,'store_key':store_key,'email_campaign_type':'Reminder','deal_key':Dom.get('deal_key').value}
	var json_value = my_encodeURIComponent(YAHOO.lang.JSON.stringify(data));
	
	var request='ar_edit_marketing.php?tipo=create_email_campaign&values='+json_value

	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
			//alert(o.responseText);
			var r =  YAHOO.lang.JSON.parse(o.responseText);

			if(r.state==200){
				location.href='deal.php.php?id='+Dom.get('deal_key').value

			}else{
				Dom.setStyle('new_email_campaign_msg_tr','display','');
				Dom.get('new_email_campaign_msg').innerHTML=r.msg;
			}
		}
	});


}

function new_deal () {
  location.href = "new_deal.php?parent=campaign&parent_key="+Dom.get('campaign_key').value;
}


function init(){

	

	ids=['details','campaigns','orders','customers','email_remainder'];

	Event.addListener(ids, "click",change_block);



	init_search('products_store');

	var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
	oACDS.queryMatchContains = true;
	oACDS.table_id=0;
	var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS);
	oAutoComp.minQueryLength = 0; 


	var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
	oACDS1.queryMatchContains = true;
	oACDS1.table_id=1;
	var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1","f_container1", oACDS1);
	oAutoComp1.minQueryLength = 0; 


	Event.addListener(['details','customers','orders','deals'], "click",change_block);


	YAHOO.util.Event.addListener('clean_table_filter_show0', "click",show_filter,0);
	YAHOO.util.Event.addListener('clean_table_filter_hide0', "click",hide_filter,0);
	YAHOO.util.Event.addListener('clean_table_filter_show1', "click",show_filter,1);
	YAHOO.util.Event.addListener('clean_table_filter_hide1', "click",hide_filter,1);
	YAHOO.util.Event.addListener('clean_table_filter_show2', "click",show_filter,2);
	YAHOO.util.Event.addListener('clean_table_filter_hide2', "click",hide_filter,2);

}

YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("rppmenu2", function () {
	var oMenu = new YAHOO.widget.ContextMenu("rppmenu2", {trigger:"rtext_rpp2" });
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
});

YAHOO.util.Event.onContentReady("filtermenu2", function () {
	var oMenu = new YAHOO.widget.ContextMenu("filtermenu2", {  trigger: "filter_name2"  });
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
});  

YAHOO.util.Event.onContentReady("rppmenu1", function () {
	var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1" });
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
});

YAHOO.util.Event.onContentReady("filtermenu1", function () {
	var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {  trigger: "filter_name1"  });
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
});  

YAHOO.util.Event.onContentReady("rppmenu0", function () {
	var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
});

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {  trigger: "filter_name0"  });
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
});  

