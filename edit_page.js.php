<?php  include_once 'common.php';

$page_key=$_REQUEST['page_key'];
$site_key=$_REQUEST['site_key'];

?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
var select_page_operation=false;
var dialog_upload_page_content;
var dialog_upload_page_content_files;
var dialog_delete_page;
var dialog_family_list;
var dialog_add_redirection;


var content_block;
var CellEdit = function (callback, newValue) {


		var record = this.getRecord(),
		column = this.getColumn(),
		oldValue = this.value,
		datatable = this.getDataTable(),
		recordIndex = datatable.getRecordIndex(record);



			if(column.object=='product')
					request_page=	'ar_edit_assets.php';
			else
		request_page=	'ar_edit_sites.php';



		YAHOO.util.Connect.asyncRequest(
						'POST',
						request_page, {
						    success:function(o) {
							//	alert(o.responseText);
							var r = YAHOO.lang.JSON.parse(o.responseText);
							if (r.state == 200) {




							 if(column.key=='description'  ){
								 datatable.updateCell(record,'description_formated',r.newdata['description_formated']);
								callback(true, r.newvalue);

							    } else if(column.key=='order'  ){
								 datatable.updateCell(record,'order_formated',r.newdata['order_formated']);
								callback(true, r.newvalue);

							    } else if(column.key=='web_configuration'  ){
								 datatable.updateCell(record,'smallname',r.newdata['description']);
								 datatable.updateCell(record,'formated_web_configuration',r.newdata['formated_web_configuration']);
								 datatable.updateCell(record,'web_configuration',r.newdata['web_configuration']);


                             	// alert(r.newdata['web_configuration'])
								callback(true, r.newdata['web_configuration']);

							    }

							    else{

								callback(true, r.newvalue);

							    }
							} else {
							    alert(r.msg);
							    callback();
							}
						    },
							failure:function(o) {
							alert(o.statusText);
							callback();
						    },
							scope:this
							},
						'tipo=edit_'+column.object+'&key=' + column.key + '&newvalue=' +
						encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue)+
						myBuildUrl(datatable,record)

						);
 };










function save_see_also_type(value) {

    var request = 'ar_edit_sites.php?tipo=edit_page_header&newvalue=' + value + "&id=" + Dom.get('page_key').value + '&key=Page Store See Also Type&okey='



    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {

            var r = YAHOO.lang.JSON.parse(o.responseText);

            if (r.state == 200) {



                if (r.newvalue == 'Auto' || r.newvalue == 'Manual') {
                    Dom.removeClass(['see_also_type_Auto', 'see_also_type_Manual'], 'selected');

                    Dom.addClass('see_also_type_' + r.newvalue, 'selected');
                    location.href = 'edit_page.php?id=' + r.page_key + '&content_view=header';
                } else {
                    alert(r.msg)
                }
            }

        }
    });

}



function add_template(display_type,template){
var request='ar_edit_sites.php?tipo=add_template&page_key=' + Dom.get('page_key').value +'&template='+template+'&display_type='+display_type


alert(request);
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
	            success:function(o){
	           alert(o.responseText);
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.state==200){
				window.location.href='custom_template.php?id='+r.template_id+'&page_id='+r.page_key;
                        }else{
				window.location.reload();
                        }
		     }
    });
}


function set_template(display_type,template){
var request='ar_edit_sites.php?tipo=change_template&page_key=' + Dom.get('page_key').value +'&template='+template+'&display_type='+display_type


alert(request);
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
	            success:function(o){
	           alert(o.responseText);
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.state==200){
				window.location.reload();
                        }else{
				window.location.reload();
                        }
		     }
    });
}

function delete_found_in_page( page_key ) {

	var request='ar_edit_sites.php?tipo=delete_found_in_page&id=' + Dom.get('page_key').value +'&found_in_key='+page_key


//alert(request);
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
	            success:function(o){
	      //     alert(o.responseText);
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.state==200){
        location.href='edit_page.php?id='+r.page_key+'&content_view=header';;
                                  }else{



                                  }
   			}
    });


}


function change_footer_type(o, type) {
    options = Dom.getElementsByClassName('option', 'button', 'Page_Footer_Type_options')
    Dom.removeClass(options, 'selected')
    Dom.addClass(o, 'selected')

    if (type == 'None') {
        Dom.setStyle('footer_list_section', 'opacity', 0.4)


    } else {
        Dom.setStyle('footer_list_section', 'opacity', 1)

    }

    value = type;
    ovalue = Dom.get('Page_Footer_Type').getAttribute('ovalue');
    validate_scope_data['page_footer']['footer_type']['value'] = value;
    Dom.get('Page_Footer_Type').value = value

    if (ovalue != value) {
        validate_scope_data['page_footer']['footer_type']['changed'] = true;
    } else {
        validate_scope_data['page_footer']['footer_type']['changed'] = false;
    }
    validate_scope('page_footer')
}


function change_number_auto_see_also(e,operation){
	var request='ar_edit_sites.php?tipo=update_see_also_quantity&id='+Dom.get('page_key').value +'&operation='+operation
	//alert(request)
	YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o){
	                         //  alert(o.responseText);
			                    var r =  YAHOO.lang.JSON.parse(o.responseText);
			                    if(r.state==200){
			                    update_page_preview_snapshot();
                                    location.href='edit_page.php?id='+r.page_key+'&content_view=header';;
                                }else{

                                }
   			}
    });
}

function delete_see_also_page( page_key ) {
	var request='ar_edit_sites.php?tipo=delete_see_also_page&id='+Dom.get('page_key').value +'&see_also_key='+page_key
	YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o){
	                            //alert(o.responseText);
			                    var r =  YAHOO.lang.JSON.parse(o.responseText);
			                    if(r.state==200){
                                    location.href='edit_page.php?id='+r.page_key+'&content_view=header';;
                                }else{

                                }
   			}
    });
}



function show_dialog_page_list(e,type){

switch ( type ) {
	case 'found_in':
		region1 = Dom.getRegion('add_other_found_in_page');
    region2 = Dom.getRegion('dialog_page_list');
 var pos =[region1.right-region2.width,region1.bottom+2]
    Dom.setXY('dialog_page_list', pos);
		break;

	case 'see_also':
	region1 = Dom.getRegion('add_other_see_also_page');
    region2 = Dom.getRegion('dialog_page_list');
 var pos =[region1.right-region2.width,region1.bottom+2]
    Dom.setXY('dialog_page_list', pos);
	break;


}


select_page_operation=type;
dialog_page_list.show();
}



function show_dialog_template_list(){


	region1 = Dom.getRegion('show_dialog_template_list');
	var pos =[region1.right,region1.bottom+2]
	Dom.setXY('dialog_template_list', pos);

	dialog_template_list.show();
}



function select_page(oArgs){
if(select_page_operation=='found_in'){
select_found_in_page(oArgs)
}else if(select_page_operation=='see_also'){
select_see_also_page(oArgs)
}else{
 dialog_page_list.hide();
}

}



function select_see_also_page(oArgs){


see_also_key=tables.table7.getRecord(oArgs.target).getData('key');

 dialog_page_list.hide();


	var request = 'ar_edit_sites.php?tipo=add_see_also_page&id=' + Dom.get('page_key').value + '&see_also_key=' + see_also_key;
	 //alert(request);

	YAHOO.util.Connect.asyncRequest('POST', request, {
		success: function(o) {
			//salert(o.responseText);
			var r = YAHOO.lang.JSON.parse(o.responseText);
			if (r.state == 200) {

                    location.href='edit_page.php?id='+r.page_key+'&content_view=header';;
			} else {


				}
		}
	});


}


function select_found_in_page(oArgs){


found_in_key=tables.table7.getRecord(oArgs.target).getData('key');

 dialog_page_list.hide();


	var request = 'ar_edit_sites.php?tipo=add_found_in_page&id=' + Dom.get('page_key').value + '&found_in_key=' + found_in_key;


	YAHOO.util.Connect.asyncRequest('POST', request, {
		success: function(o) {
			//alert(o.responseText);
			var r = YAHOO.lang.JSON.parse(o.responseText);
			if (r.state == 200) {

                    location.href='edit_page.php?id='+r.page_key+'&content_view=header';;
			} else {


				}
		}
	});


}


function select_family(oArgs){

family_key=tables.table4.getRecord(oArgs.target).getData('key');
 dialog_family_list.hide();

	var request = 'ar_edit_sites.php?tipo=edit_page&key=' + 'family_key' + '&newvalue=' + family_key+ '&id=' + Dom.get('page_key').value
	// alert(request);

	YAHOO.util.Connect.asyncRequest('POST', request, {
		success: function(o) {
			//alert(o.responseText);
			var r = YAHOO.lang.JSON.parse(o.responseText);
			if (r.state == 200) {
				Dom.get('current_family_code').innerHTML=r.newdata['code'];
			} else {
				}
		}
	});
}


YAHOO.util.Event.addListener(window, "load", function() {

    tables = new function() {



 var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				       {key:"date",label:"<?php echo _('Date')?>", width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"author",label:"<?php echo _('Author')?>", width:70,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					       ,{key:"abstract", label:"<?php echo _('Description')?>", width:370,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ];

	    this.dataSource1 = new YAHOO.util.DataSource("ar_history.php?tipo=history&type=page&tableid=1");
	    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    this.dataSource1.responseSchema = {
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
			 "id"
			 ,"note"
			 ,'author','date','tipo','abstract','details'
			 ]};

	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource1
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    : <?php echo$_SESSION['state']['page']['history']['nr']?>,containers : 'paginator1', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })

							 ,sortedBy : {
							    key: "<?php echo$_SESSION['state']['page']['history']['order']?>",
							     dir: "<?php echo$_SESSION['state']['page']['history']['order_dir']?>"
							 },
							 dynamicData : true

						     }

						     );

	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

		       this.table1.table_id=tableid;
     this.table1.subscribe("renderEvent", myrenderEvent);


	    this.table1.filter={key:'<?php echo$_SESSION['state']['page']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['page']['history']['f_value']?>'};



    var tableid=8;
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				       {key:"id", label:"", hidden:true,action:"none",isPrimaryKey:true}
				       ,{key:"name",label:"<?php echo _('Name')?>", width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'family_page_properties'}
				   		,{key:"image",label:"<?php echo _('Preview')?>", width:300,sortable:false,className:"aright"}
				         ,{key:"selected", label:"",width:180,sortable:false,className:"acenter"}
				       ];


	    var request="ar_edit_sites.php?tipo=page_headers&parent=page&parent_key="+Dom.get('page_key').value+"&tableid=8";
	    //alert(request)
	        this.dataSource8 = new YAHOO.util.DataSource(request);

	    this.dataSource8.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource8.connXhrMode = "queueRequests";
	    this.dataSource8.responseSchema = {
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


		fields: ["id","go","name","delete","pages","image","selected"]};

        this.table8= new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource8
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    :<?php echo $_SESSION['state']['page']['edit_headers']['nr']?> ,containers : 'paginator8', alpartysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info8'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })

							 ,sortedBy : {
							    key: "<?php echo $_SESSION['state']['page']['edit_headers']['order']?>",
							     dir: "<?php echo $_SESSION['state']['page']['edit_headers']['order_dir']?>"
							 },
							 dynamicData : true
						     }
						     );

	    this.table8.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table8.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table8.doBeforePaginatorChange = mydoBeforePaginatorChange;
   		this.table8.table_id=tableid;
     	this.table8.subscribe("renderEvent", myrenderEvent);


	    this.table8.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table8.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table8.subscribe("cellClickEvent", onCellClick);

	    this.table8.filter={key:'<?php echo $_SESSION['state']['page']['edit_headers']['f_field']?>',value:'<?php echo $_SESSION['state']['page']['edit_headers']['f_value']?>'};

    var tableid=9;
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				       {key:"id", label:"", hidden:true,action:"none",isPrimaryKey:true}
				       ,{key:"name",label:"<?php echo _('Name')?>", width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'family_page_properties'}
				   		,{key:"image",label:"<?php echo _('Preview')?>", width:300,sortable:false,className:"aright"}
				         ,{key:"selected", label:"",width:180,sortable:false,className:"aright"}
				       ];


	    var request="ar_edit_sites.php?tipo=page_footers&parent=page&parent_key="+Dom.get('page_key').value+"&tableid=9";
	    //alert(request)
	        this.dataSource9 = new YAHOO.util.DataSource(request);

	    this.dataSource9.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource9.connXhrMode = "queueRequests";
	    this.dataSource9.responseSchema = {
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


		fields: ["id","go","name","delete","pages","image","selected"]};

        this.table9= new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource9
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    :<?php echo $_SESSION['state']['page']['edit_footers']['nr']?> ,containers : 'paginator9', alpartysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info9'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })

							 ,sortedBy : {
							    key: "<?php echo $_SESSION['state']['page']['edit_footers']['order']?>",
							     dir: "<?php echo $_SESSION['state']['page']['edit_footers']['order_dir']?>"
							 },
							 dynamicData : true
						     }
						     );

	    this.table9.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table9.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table9.doBeforePaginatorChange = mydoBeforePaginatorChange;
   		this.table9.table_id=tableid;
     	this.table9.subscribe("renderEvent", myrenderEvent);


	    this.table9.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table9.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table9.subscribe("cellClickEvent", onCellClick);

	    this.table9.filter={key:'<?php echo $_SESSION['state']['page']['edit_footers']['f_field']?>',value:'<?php echo $_SESSION['state']['page']['edit_footers']['f_value']?>'};


   var tableid=7; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				       {key:"key", label:"", hidden:true,action:"none",isPrimaryKey:true}

				       ,{key:"code",label:"<?php echo _('Code')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    		,{key:"type",label:"<?php echo _('Type')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'family_page_properties'}

				      ,{key:"store_title",label:"<?php echo _('Header Title')?>", width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'family_page_properties'}

				       ];





	        this.dataSource7 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=page_list&site_key="+Dom.get('site_key').value+"&sf=0&tableid=7");

//alert("ar_edit_sites.php?tipo=family_page_list&site_key="+Dom.get('site_key').value+"&parent=family&parent_key="+Dom.get('family_key').value+"&tableid=7")
	    this.dataSource7.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource7.connXhrMode = "queueRequests";
	    this.dataSource7.responseSchema = {
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
			 "key",
			 "code","store_title","type"

			 ]};

        this.table7 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource7
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    :20 ,containers : 'paginator7', alpartysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info7'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })

							 ,sortedBy : {
							   key: "code",
									 dir: ""
							 },
							 dynamicData : true
						     }
						     );

	      this.table7.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table7.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table7.subscribe("cellClickEvent", this.table7.onEventShowCellEditor);

 this.table7.subscribe("rowMouseoverEvent", this.table7.onEventHighlightRow);
       this.table7.subscribe("rowMouseoutEvent", this.table7.onEventUnhighlightRow);
      this.table7.subscribe("rowClickEvent", select_page);
        this.table7.table_id=tableid;
           this.table7.subscribe("renderEvent", myrenderEvent);


	    this.table7.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table7.filter={key:'code',value:''};





  var tableid=2;
	    var tableDivEL="table"+tableid;


function formater_description  (el, oRecord, oColumn, oData) {

		 el.innerHTML = oRecord.getData("description_formated");
	    }
function formater_order  (el, oRecord, oColumn, oData) {

		 el.innerHTML = oRecord.getData("order_formated");
	    }

	    var CustomersColumnDefs = [
				       {key:"id", label:"", hidden:true,action:"none",isPrimaryKey:true}
				       				       ,{key:"description_formated", label:"", hidden:true}
				       				       ,{key:"order_formated", label:"", hidden:true}

				       ,{key:"go", label:"", width:65,action:"none"}
				        ,{key:"code",label:"<?php echo _('Name')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					    ,{key:"type",label:"<?php echo _('Type')?>", width:60,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					    ,{key:"description",label:"<?php echo _('Description')?>", formatter: formater_description ,width:140,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},object:'page_product_list'
					      ,editor: new YAHOO.widget.RadioCellEditor({asyncSubmitter: CellEdit,radioOptions:[
				    {'value':"Units Name",'label':"<?php echo _('<i>units</i> x name')?><br/>"},
				   {'value':"Units Special Characteristic",'label':"<?php echo _('<i>units</i> x <i>description</i>')?><br/>"},
				     {'value':"Units Name RRP",'label':"<?php echo _('<i>units</i> x name RRP')?><br/>"},
				   {'value':"Units Special Characteristic RRP",'label':"<?php echo _('<i>units</i> x <i>description</i> RRP')?><br/>"},

				    ],disableBtns:true})

					    }
					    ,{key:"order",label:"<?php echo _('Order')?>", formatter: formater_order , width:100,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},object:'page_product_list'
					    ,editor: new YAHOO.widget.RadioCellEditor({asyncSubmitter: CellEdit,radioOptions:[
				    {'value':"Code",'label':"<?php echo _('Code')?><br/>"},
				   {'value':"Name",'label':"<?php echo _('Name')?><br/>"},
				    {'value':"Special Characteristic",'label':"<?php echo _('Description')?><br/>"},
				     {'value':"Price",'label':"<?php echo _('Price')?><br/>"},
				      {'value':"RRP",'label':"<?php echo _('RRP')?><br/>"},
				      	{'value':"Sales",'label':"<?php echo _('Sales')?><br/>"},
				      {'value':"Date",'label':"<?php echo _('Date')?><br/>"},

				    ],disableBtns:true})}



					    ,{key:"range",label:"<?php echo _('Range')?>", width:100,sortable:false,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'page_product_list'}
					    ,{key:"max",label:"<?php echo _('Limit')?>", width:100,sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'page_product_list'}


					    ];


	    var request="ar_edit_sites.php?tipo=page_product_lists&parent=page&parent_key="+Dom.get('page_key').value+"&tableid=2";
	   // alert(request)
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


		fields: [
			 "id"
			 ,"go","code","products","type","description","order","range","max","description_formated","order_formated"

			 ]};

        this.table2 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource2
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    :<?php echo $_SESSION['state']['page']['edit_product_list']['nr']?> ,containers : 'paginator2', alpartysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })

							 ,sortedBy : {
							    key: "<?php echo $_SESSION['state']['page']['edit_product_list']['order']?>",
							     dir: "<?php echo $_SESSION['state']['page']['edit_product_list']['order_dir']?>"
							 },
							 dynamicData : true
						     }
						     );

	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
   this.table2.table_id=tableid;
     this.table2.subscribe("renderEvent", myrenderEvent);


	    this.table2.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table2.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table2.subscribe("cellClickEvent", onCellClick);

	    this.table2.filter={key:'<?php echo $_SESSION['state']['page']['edit_product_list']['f_field']?>',value:'<?php echo $_SESSION['state']['page']['edit_product_list']['f_value']?>'};


	    function formater_web_configuration  (el, oRecord, oColumn, oData) {
		     el.innerHTML = oRecord.getData("formated_web_configuration");
	    }
	      var tableid=3;
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
	    				       {key:"id", label:"", hidden:true,action:"none",isPrimaryKey:true}

				       ,{key:"pid", label:"", hidden:true,action:"none",isTypeKey:true}
				        ,{key:"go", label:"", width:40,action:"none",className:"aleft"}
				        ,{key:"code",label:"<?php echo _('Code')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'family_page_properties'}
					    ,{key:"smallname", label:"<?php echo _('Description')?>",width:480, sortable:true,className:"aleft",className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}


	,{key:"web_configuration" ,formatter: formater_web_configuration , label:"<?php echo _('Web/Sale Status')?>",width:120, sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},object:'product',editor: new YAHOO.widget.RadioCellEditor({asyncSubmitter: CellEdit,radioOptions:[
				    {'value':"Online Auto",'label':"<?php echo _('Auto')?><br/>"},
				    {'value':"Online Force For Sale",'label':"<?php echo _('Force online')?><br/>"},
				    {'label':"<?php echo _('Force out of stock')?><br/>",'value':"Online Force Out of Stock"},
				    {'label':"<?php echo _('Force offline')?><br/>",'value':'Offline'},
				    {'label':"<?php echo _('Private Sale')?><br/>",'value':'Private Sale'},
				    {'label':"<?php echo _('Not For Sale')?>",'value':'Not for Sale'}
				    ],disableBtns:true})}
				    ,{key:"formated_web_configuration" , label:"",hidden:true}

					    ];


	    var request="ar_edit_sites.php?tipo=page_product_buttons&parent=page&parent_key="+Dom.get('page_key').value+"&tableid=3";
	   //alert(request)
	        this.dataSource3 = new YAHOO.util.DataSource(request);

	    this.dataSource3.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource3.connXhrMode = "queueRequests";
	    this.dataSource3.responseSchema = {
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
			 ,"go","code",'formated_web_configuration','web_configuration','pid','smallname'

			 ]};

        this.table3 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource3
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    :<?php echo $_SESSION['state']['page']['edit_product_list']['nr']?> ,containers : 'paginator3', alpartysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info3'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })

							 ,sortedBy : {
							    key: "<?php echo $_SESSION['state']['page']['edit_product_list']['order']?>",
							     dir: "<?php echo $_SESSION['state']['page']['edit_product_list']['order_dir']?>"
							 },
							 dynamicData : true
						     }
						     );

	    this.table3.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table3.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table3.doBeforePaginatorChange = mydoBeforePaginatorChange;
   this.table3.table_id=tableid;
     this.table3.subscribe("renderEvent", myrenderEvent);


	    this.table3.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table3.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table3.subscribe("cellClickEvent", onCellClick);

	    this.table3.filter={key:'<?php echo $_SESSION['state']['page']['edit_product_list']['f_field']?>',value:'<?php echo $_SESSION['state']['page']['edit_product_list']['f_value']?>'};




	   var tableid=4;
	    var tableDivEL="table"+tableid;


	    var ColumnDefs = [
			 {key:"key", label:"",width:100,hidden:true}
                    ,{key:"code", label:"<?php echo _('Code')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"name", label:"<?php echo _('Name')?>",width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

			];



		request="ar_quick_tables.php?tipo=family_list&store_key="+Dom.get('store_key').value+"&tableid="+tableid+"&nr=20&sf=0";

		this.dataSource4 = new YAHOO.util.DataSource(request);
		//alert("ar_quick_tables.php?tipo=family_list&store_key="+store_key+"&tableid="+tableid+"&nr=20&sf=0");
	    this.dataSource4.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource4.connXhrMode = "queueRequests";
	    	    this.dataSource4.table_id=tableid;

	    this.dataSource4.responseSchema = {
		resultsList: "resultset.data",
		metaFields: {
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},


		fields: [
			 "code",'name','key'
			 ]};

	    this.table4 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource4
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator4',
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info4'>{CurrentPageReport}</strong>{NextPageLink}"
									  })

								     ,sortedBy : {
									 key: "code",
									 dir: ""
								     },
								     dynamicData : true

								  }

								 );

	    this.table4.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table4.doBeforeSortColumn = mydoBeforeSortColumn;
        this.table4.subscribe("rowMouseoverEvent", this.table4.onEventHighlightRow);
        this.table4.subscribe("rowMouseoutEvent", this.table4.onEventUnhighlightRow);
        this.table4.subscribe("rowClickEvent", select_family);
        this.table4.table_id=tableid;
        this.table4.subscribe("renderEvent", myrenderEvent);
	    this.table4.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table4.filter={key:'code',value:''};


	};
    });



function change_block(){
  var ids = ['properties','page_header','page_footer','content','style','media','setup','products', 'url','state'];
block_ids=['d_properties','d_page_header','d_page_footer','d_content','d_style','d_media','d_setup','d_products','d_url','d_state'];
//alert(this.id)


if(this.id=='content' && content_block=='content'){
Dom.setStyle('tabbed_container','margin','0px 0px')
Dom.setStyle('tabbed_container','border-left:','0px')
Dom.setStyle('tabbed_container','border-right:','0px')

}else{
Dom.setStyle('tabbed_container','margin','0px 20px')
Dom.setStyle('tabbed_container','border-left:','1px')
Dom.setStyle('tabbed_container','border-right:','1px')

}

Dom.setStyle(block_ids,'display','none');
Dom.setStyle('d_'+this.id,'display','');
//Dom.setStyle('d_'+this.id,'height','420px');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=page-editing&value='+this.id ,{});
}




function change_content_block(e, block) {
    Dom.setStyle('show_page_content_overview_block', 'display', '')
    Dom.removeClass(['show_page_header_block', 'show_page_content_block', 'show_page_products_block', 'show_page_footer_block', 'show_page_includes_block'], 'selected')
    Dom.addClass('show_page_' + block + '_block', 'selected')
    Dom.setStyle(['page_header_block', 'page_content_block', 'page_products_block', 'page_footer_block', 'page_content_overview_block','page_includes_block'], 'display', 'none')
    Dom.setStyle('page_' + block + '_block', 'display', '')

if(block=='content'){
Dom.setStyle('tabbed_container','margin','0px 0px')
Dom.setStyle('tabbed_container','border-left:','0px')
Dom.setStyle('tabbed_container','border-right:','0px')
Dom.setStyle('show_page_includes_block','margin-left','30px')

}else{
Dom.setStyle('tabbed_container','margin','0px 20px')
Dom.setStyle('tabbed_container','border','1px solid #ccc')

Dom.setStyle('tabbed_container','border-left:','1px solid #ccc')
Dom.setStyle('tabbed_container','border-right:','1px solid #ccc')
Dom.setStyle('show_page_includes_block','margin-left','10px')

}
content_block=block
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=page-editing_content_block&value=' + block, {});
}

function reset_edit_page_header(){ reset_edit_general('page_header');}
function save_edit_page_header(){save_edit_general('page_header');}
function reset_edit_page_footer(){ reset_edit_general('page_footer');}
function save_edit_page_footer(){save_edit_general('page_footer');}
function reset_edit_page_html_head(){ reset_edit_general('page_html_head');}
function save_edit_page_html_head(){save_edit_general('page_html_head');}
function reset_edit_page_content(){ reset_edit_general('page_content');}
function save_edit_page_content(){


EmailHTMLEditor.saveHTML();



save_edit_general('page_content');
}
function reset_edit_page_properties(){ reset_edit_general('page_properties');}
function save_edit_page_properties(){save_edit_general('page_properties');}


function validate_page_includes_head_content(query){
 validate_general('page_html_head','head_content',unescape(query));
}

function validate_page_includes_body_content(query){
 validate_general('page_html_head','body_content',unescape(query));
}

function validate_page_content_presentation_template_data(query){validate_general('page_content','presentation_template_data',unescape(query));}
function validate_page_header_store_title(query){validate_general('page_header','store_title',unescape(query));}

function validate_page_html_head_resume(query){validate_general('page_properties','resume',unescape(query));}
function validate_page_properties_link_title(query){validate_general('page_properties','link_title',unescape(query));}
function validate_page_properties_page_code(query){validate_general('page_properties','page_code',unescape(query));}
function validate_page_html_head_title(query){validate_general('page_properties','title',unescape(query));}
function validate_page_html_head_keywords(query){validate_general('page_properties','keywords',unescape(query));}

function html_editor_changed(){
    validate_scope_data['page_content']['source']['changed']=true;


    validate_scope('page_content');
}

function show_dialog_upload_page_content(e,suffix) {

    Dom.setStyle('processing_upload_page_content', 'display', 'none')
    Dom.setStyle(['upload_page_content', 'cancel_upload_page_content'], 'display', '')


    region1 = Dom.getRegion('show_upload_page_content'+suffix);
    region2 = Dom.getRegion('dialog_upload_page_content');
    var pos = [region1.right - region2.width, region1.bottom + 2]
    Dom.setXY('dialog_upload_page_content', pos);

    dialog_upload_page_content.show()

}

function close_upload_page_content(){

Dom.get('upload_page_content_use_file').value='';

dialog_upload_page_content.hide();
}


function cancel_upload_page_content_files(){
Dom.get('upload_page_content_use_file').value='';

dialog_upload_page_content_files.hide();
}
function upload_page_content_file(file){
Dom.get('upload_page_content_use_file').value=file;
upload_page_content();
}


function reload_this(page_key){

	     	      window.location='edit_page.php?id='+page_key+'&take_snapshot=1&content_view=content';

}

function redirect_to_preview(){
if(Dom.get('redirect_review').value==1)
window.location='page_preview.php?id='+Dom.get('page_key').value+'&logged=1&update_heights=1&take_snapshot=1';
}


function upload_page_content() {



    Dom.setStyle('processing_upload_page_content', 'display', '')
    Dom.setStyle(['upload_page_content', 'cancel_upload_page_content'], 'display', 'none')

    YAHOO.util.Connect.setForm('upload_page_content_form', true, true);
    var request = 'ar_upload_page_content.php?tipo=upload_page_content';

    //alert(request);
    var uploadHandler = {
        upload: function(o) {
            alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);

            if (r.state == 200) {
                //	    update_page_height_and_reload(r.page_key)
                //          Dom.get('page_preview_iframe').src='page_preview.php?id='+r.page_key+'&logged=1&update_heights=1';
                //window.location='page_preview.php?id='+r.page_key+'&logged=1&update_heights=1';
                //setTimeout("reload_this("+r.page_key+")", 250);






                window.location = 'edit_page.php?id=' + r.page_key + '&take_snapshot=1&content_view=overview&redirect_review=1&update_heights=1';


                return;

            } else if (r.state == 201) {
                dialog_upload_page_content.hide();
                region1 = Dom.getRegion('show_upload_page_content');
                region2 = Dom.getRegion('dialog_upload_page_content_files');
                var pos = [region1.right - region2.width, region1.bottom + 2]
                Dom.setXY('dialog_upload_page_content_files', pos);
                dialog_upload_page_content_files.show();
                buttons = '';
                for (var i = 0; i < r.list.length; i++) {
                    buttons = buttons + "<button onClick='upload_page_content_file(" + r.list[i] + ")' style='margin-top:0px;margin-bottom:10px' >" + r.list[i] + "</button> ";
                }
                Dom.get('upload_page_content_files').innerHTML = buttons
            } else alert(r.msg);



        }
    };

    YAHOO.util.Connect.asyncRequest('POST', request, uploadHandler);



};


  function publish() {

      Dom.addClass('publish', 'disabled')
      Dom.get('publish_icon').src = 'art/loading.gif'
      YAHOO.util.Connect.asyncRequest('POST', 'ar_edit_sites.php?tipo=publish_page&page_key=' + Dom.get('page_key').value, {
          success: function(o) {


              var r = YAHOO.lang.JSON.parse(o.responseText);
              Dom.removeClass('publish', 'disabled')
              Dom.get('publish_icon').src = 'art/icons/page_world.png'
              if (r.state == 200) {
					change_state('Online');
					save_edit_page_state()

              }

          }
      });

  }

  function update_page_height_and_reload(page_key){
//  alert('ar_edit_sites.php?tipo=update_page_preview_snapshot&id='+page_key)
  YAHOO.util.Connect.asyncRequest('POST','ar_edit_sites.php?tipo=update_page_preview_snapshot&id='+page_key,{
  success: function(o) {
  //alert(o.responseText)
  	    //  window.location='edit_page.php?id='+r.page_key+'&take_snapshot=1&content_view=content';

  }
  });

  }

    function update_page_preview_snapshot(){
  YAHOO.util.Connect.asyncRequest('POST','ar_edit_sites.php?tipo=update_page_preview_snapshot&id='+Dom.get('page_key').value,{
  success: function(o) {
   var r = YAHOO.lang.JSON.parse(o.responseText);
   Dom.get('page_preview_snapshot_image').src='image.php?id='+r.image_key

  }
  });

  }


  function post_item_updated_actions(branch,r){
  switch ( branch ) {



  	case 'page_header':

  		update_page_preview_snapshot();

  		break;


  }
 table_id = 1
                var table = tables['table' + table_id];
                var datasource = tables['dataSource' + table_id];
                datasource.sendRequest('', table.onDataReturnInitializeTable, table);



  }

function show_more_configuration(){
Dom.setStyle('advanced_configuration','display','')
}
function hide_more_configuration(){
Dom.setStyle('advanced_configuration','display','none')

}




function show_page_content_overview_block() {

    Dom.setStyle('show_page_content_overview_block', 'display', 'none')
    Dom.setStyle('page_content_overview_block', 'display', '')

    Dom.removeClass(['show_page_header_block', 'show_page_content_block', 'show_page_products_block', 'show_page_footer_block'], 'selected')
    Dom.setStyle(['page_header_block', 'page_content_block', 'page_products_block', 'page_footer_block'], 'display', 'none')

}

function set_header(header_key) {
    var request = 'ar_edit_sites.php?tipo=set_header&header_key=' + header_key + '&page_key=' + Dom.get('page_key').value
    //alert(request)
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);

            if (r.state == 200) {
                window.location = 'edit_page.php?id=' + Dom.get('page_key').value + '&take_snapshot=1&update_heights=1';
            } else {
                alert(r.msg)
            }
        }


    });

}

function set_footer(footer_key) {
    var request = 'ar_edit_sites.php?tipo=set_footer&footer_key=' + footer_key + '&page_key=' + Dom.get('page_key').value
    //alert(request)
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
      //      alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);

            if (r.state == 200) {
                window.location = 'edit_page.php?id=' + Dom.get('page_key').value + '&take_snapshot=1&update_heights=1';
            } else {
                alert(r.msg)
            }
        }


    });

}


function save_delete_page() {
    var request = 'ar_edit_sites.php?tipo=delete_page&id=' + Dom.get('page_key').value
    //alert(request)
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);

            if (r.state == 200) {

                if (r.page_key) location.href = 'page_deleted.php?id=' + r.page_key;
                else location.href = 'site.php?id=' + Dom.get('site_key').value;


            } else {
                alert(r.msg)
            }
        }


    });
}

function cancel_delete_page() {
    dialog_delete_page.hide();
}

function show_delete_page() {

    region1 = Dom.getRegion('delete_page');
    region2 = Dom.getRegion('dialog_delete_page');
    var pos = [region1.right - region2.width, region1.bottom + 2]

    Dom.setXY('dialog_delete_page', pos);
    dialog_delete_page.show();
}

function show_dialog_family_list() {

    region1 = Dom.getRegion('edit_parent_family');
    region2 = Dom.getRegion('dialog_family_list');
    var pos = [region1.right - region2.width + 200, region1.bottom + 2]

    Dom.setXY('dialog_family_list', pos);
    dialog_family_list.show();
}

function save_add_redirection() {
    url = Dom.get('add_redirect_source').value;
    var request = 'ar_edit_sites.php?tipo=add_redirect&url=' + escape(url) + '&page_key=' + Dom.get('page_key').value;
    //alert(request)
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            //alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                location.href = 'edit_page.php?id=' + r.page_key + '&content_view=header';;
            } else {
                Dom.get('add_redirect_msg').innerHTML = r.msg
            }
        }
    });
}

function dialog_add_redirection_chenged(e) {
    Dom.get('add_redirect_msg').innerHTML = '';

    var key;
    if (window.event) Key = window.event.keyCode; //IE
    else Key = e.which; //firefox
    if (Key == 13) {
        save_add_redirection();

    }

}

function show_dialog_add_redirection() {
    region1 = Dom.getRegion('show_dialog_add_redirection');
    region2 = Dom.getRegion('dialog_add_redirection');
    var pos = [region1.right - region2.width, region1.bottom + 2]
    Dom.setXY('dialog_add_redirection', pos);

    dialog_add_redirection.show();
    Dom.get('add_redirect_source').value = '';
    Dom.get('add_redirect_msg').innerHTML = '';
    Dom.get('add_redirect_source').focus();
}

function delete_redirect(rediect_key) {
    var request = 'ar_edit_sites.php?tipo=delete_redirect&id=' + rediect_key + '&site_key=' + Dom.get('site_key').value+'&page_key='+Dom.get('page_key').value;

    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            // alert(o.responseText);
            var r = YAHOO.lang.JSON.parse(o.responseText);
            if (r.state == 200) {
                location.href = 'edit_page.php?id=' + r.page_key + '&content_view=header';;
            } else {

            }
        }
    });
}

function cancel_add_redirection() {
    dialog_add_redirection.hide();

}
function post_save_actions(r) {



    if (r.key == 'footer_type') {
        Dom.setStyle('footer_list_section', 'opacity', 1)

        if (r.newvalue == 'None') {
            Dom.setStyle('footer_list_section', 'display', 'none')

        } else {
            Dom.setStyle('footer_list_section', 'display', '')

        }

    } else if (r.key == 'Page_State') {

        if (r.newvalue == 'Online') {
            publish();
        }
    }
}




function show_history() {
    Dom.setStyle(['show_history', ''], 'display', 'none')
    Dom.setStyle(['hide_history', 'history_table'], 'display', '')
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=page-show_history&value=1', {});

}

function hide_history() {
    Dom.setStyle(['show_history', ''], 'display', '')
    Dom.setStyle(['hide_history', 'history_table'], 'display', 'none')
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=page-show_history&value=0', {});

}



function init(){

content_block=Dom.get('content_block').value;

dialog_family_list = new YAHOO.widget.Dialog("dialog_family_list", {
    visible: false,
    close: true,
    underlay: "none",
    draggable: false
});
dialog_family_list.render();


Event.addListener("edit_parent_family", "click", show_dialog_family_list);


Event.addListener('cancel_delete_page', "click", cancel_delete_page);
Event.addListener('save_delete_page', "click", save_delete_page);

Event.addListener('delete_page', "click", show_delete_page);


Event.addListener('add_auto_see_also_page', "click", change_number_auto_see_also, 'add');
Event.addListener('remove_auto_see_also_page', "click", change_number_auto_see_also, 'remove');


Event.addListener('show_page_header_block', "click", change_content_block, 'header');
Event.addListener('show_page_content_block', "click", change_content_block, 'content');
Event.addListener('show_page_products_block', "click", change_content_block, 'products');
Event.addListener('show_page_footer_block', "click", change_content_block, 'footer');
Event.addListener('show_page_includes_block', "click", change_content_block, 'includes');



Event.addListener('show_page_content_overview_block', "click", show_page_content_overview_block);



Event.addListener('show_more_configuration', "click", show_more_configuration);
Event.addListener('hide_more_configuration', "click", hide_more_configuration);




Event.addListener('show_upload_page_content', "click", show_dialog_upload_page_content,'');
Event.addListener('show_upload_page_content_bis', "click", show_dialog_upload_page_content,'_bis');


Event.addListener("cancel_upload_page_content", "click", close_upload_page_content);
Event.addListener('upload_page_content', "click", upload_page_content);
dialog_upload_page_content = new YAHOO.widget.Dialog("dialog_upload_page_content", {
    visible: false,
    close: true,
    underlay: "none",
    draggable: false
});
dialog_upload_page_content.render();

Event.addListener('cancel_upload_page_content_files', "click", cancel_upload_page_content_files);
dialog_upload_page_content_files = new YAHOO.widget.Dialog("dialog_upload_page_content_files", {
    visible: false,
    close: true,
    underlay: "none",
    draggable: false
});
dialog_upload_page_content_files.render();

dialog_delete_page = new YAHOO.widget.Dialog("dialog_delete_page", {
    visible: false,
    close: true,
    underlay: "none",
    draggable: false
});
dialog_delete_page.render();


dialog_page_list = new YAHOO.widget.Dialog("dialog_page_list", {
    visible: false,
    close: true,
    underlay: "none",
    draggable: false
});
dialog_page_list.render();


dialog_template_list = new YAHOO.widget.Dialog("dialog_template_list", {
    visible: false,
    close: true,
    underlay: "none",
    draggable: false
});
dialog_template_list.render();

Event.addListener("show_dialog_template_list", "click", show_dialog_template_list);

Event.addListener("add_other_found_in_page", "click", show_dialog_page_list, 'found_in', true);
Event.addListener("add_other_see_also_page", "click", show_dialog_page_list, 'see_also', true);


dialog_add_redirection = new YAHOO.widget.Dialog("dialog_add_redirection", {
    visible: false,
    close: true,
    underlay: "none",
    draggable: false
});

dialog_add_redirection.render();
Event.addListener('show_dialog_add_redirection', "click", show_dialog_add_redirection);
Event.addListener('save_add_redirection', "click", save_add_redirection);
Event.addListener('cancel_add_redirection', "click", cancel_add_redirection);



   Event.addListener('save_edit_page_state', "click", save_edit_page_state);
    Event.addListener('reset_edit_page_state', "click", reset_edit_page_state);

  init_search('site');

    var ids = ['properties','page_header','page_footer','content','style','media','setup','products', 'url','state'];
    Event.addListener(ids, "click", change_block);

 validate_scope_metadata={
        'page_state':{'type':'edit','ar_file':'ar_edit_sites.php','key_name':'id','key':Dom.get('page_key').value}
        ,'page_properties':{'type':'edit','ar_file':'ar_edit_sites.php','key_name':'id','key':Dom.get('page_key').value}
        ,'page_html_head':{'type':'edit','ar_file':'ar_edit_sites.php','key_name':'id','key':Dom.get('page_key').value}
        ,'page_header':{'type':'edit','ar_file':'ar_edit_sites.php','key_name':'id','key':Dom.get('page_key').value}
        ,'page_footer':{'type':'edit','ar_file':'ar_edit_sites.php','key_name':'id','key':Dom.get('page_key').value}

        ,'page_content':{'type':'edit','ar_file':'ar_edit_sites.php','key_name':'id','key':Dom.get('page_key').value}
    };


 validate_scope_data={

 'page_state': {

		'Page_State': {
			'changed': false,
			'validated': true,
			'required': true,
			'group': 1,
			'type': 'item',
			'dbname': 'Page State',
			'name': 'Page_State',
			'ar': false,
			'validation':false

		},

		'Page_Stealth_Mode': {
			'changed': false,
			'validated': true,
			'required': true,
			'group': 1,
			'type': 'item',
			'dbname': 'Page Stealth Mode',
			'name': 'Page_Stealth_Mode',
			'ar': false,
			'validation':false

		}



	},
    'page_properties':{

	'page_code':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
		 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Code')?>'}]
		 ,'name':'page_properties_page_code'
		 ,'ar':'find','ar_request':'ar_sites.php?tipo=is_page_store_code&site_key='+Dom.get('site_key').value+'&query='
	},
	'link_title':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
		 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Link Title')?>'}]
		 ,'name':'page_properties_link_title','ar':false

	},
		'title':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
		 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Title')?>'}]
		 ,'name':'page_html_head_title','ar':false

	},
	'keywords':{
	'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'name':'page_html_head_keywords','ar':false},
	'resume':{
		'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'name':'page_html_head_resume','ar':false}

    }
     ,'page_html_head':{

	'head_content':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','dbname':'Page Head Include','name':'head_content','ar':false,'validation':false}
	,'body_content':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','dbname':'Page Body Include','name':'body_content','ar':false,'validation':false}


    }
,'page_header':{
		'store_title':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
		 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Title')?>'}]
		 ,'name':'page_header_store_title','ar':false

	}


    }
,'page_footer':{
		'footer_type':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
		 ,'validation':false
		 ,'name':'Page_Footer_Type','ar':false

	}


    }
,'page_content':{

	'source':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'name':'html_editor','dbname':'Page Store Source','ar':false}

    }


};



 YAHOO.util.Event.addListener('reset_edit_page_html_head', "click", reset_edit_page_html_head);
    YAHOO.util.Event.addListener('save_edit_page_html_head', "click", save_edit_page_html_head);

 YAHOO.util.Event.addListener('reset_edit_page_header', "click", reset_edit_page_header);
    YAHOO.util.Event.addListener('save_edit_page_header', "click", save_edit_page_header);

     YAHOO.util.Event.addListener('reset_edit_page_footer', "click", reset_edit_page_footer);
    YAHOO.util.Event.addListener('save_edit_page_footer', "click", save_edit_page_footer);

YAHOO.util.Event.addListener('reset_edit_page_content', "click", reset_edit_page_content);
    YAHOO.util.Event.addListener('save_edit_page_content', "click", save_edit_page_content);

    YAHOO.util.Event.addListener('reset_edit_page_properties', "click", reset_edit_page_properties);
    YAHOO.util.Event.addListener('save_edit_page_properties', "click", save_edit_page_properties);



 var page_properties_page_code_oACDS = new YAHOO.util.FunctionDataSource(validate_page_properties_page_code);
    page_properties_page_code_oACDS.queryMatchContains = true;
    var page_properties_page_code_oAutoComp = new YAHOO.widget.AutoComplete("page_properties_page_code","page_properties_page_code_Container", page_properties_page_code_oACDS);
    page_properties_page_code_oAutoComp.minQueryLength = 0;
    page_properties_page_code_oAutoComp.queryDelay = 0.1;





     var page_properties_link_title_oACDS = new YAHOO.util.FunctionDataSource(validate_page_properties_link_title);
    page_properties_link_title_oACDS.queryMatchContains = true;
    var page_properties_link_title_oAutoComp = new YAHOO.widget.AutoComplete("page_properties_link_title","page_properties_link_title_Container", page_properties_link_title_oACDS);
    page_properties_link_title_oAutoComp.minQueryLength = 0;
    page_properties_link_title_oAutoComp.queryDelay = 0.1;

    var page_html_head_title_oACDS = new YAHOO.util.FunctionDataSource(validate_page_html_head_title);
    page_html_head_title_oACDS.queryMatchContains = true;
    var page_html_head_title_oAutoComp = new YAHOO.widget.AutoComplete("page_html_head_title","page_html_head_title_Container", page_html_head_title_oACDS);
    page_html_head_title_oAutoComp.minQueryLength = 0;
    page_html_head_title_oAutoComp.queryDelay = 0.1;

    var page_html_head_keywords_oACDS = new YAHOO.util.FunctionDataSource(validate_page_html_head_keywords);
    page_html_head_keywords_oACDS.queryMatchContains = true;
    var page_html_head_keywords_oAutoComp = new YAHOO.widget.AutoComplete("page_html_head_keywords","page_html_head_keywords_Container", page_html_head_keywords_oACDS);
    page_html_head_keywords_oAutoComp.minQueryLength = 0;
    page_html_head_keywords_oAutoComp.queryDelay = 0.1;


 var page_header_store_title_oACDS = new YAHOO.util.FunctionDataSource(validate_page_header_store_title);
    page_header_store_title_oACDS.queryMatchContains = true;
    var page_header_store_title_oAutoComp = new YAHOO.widget.AutoComplete("page_header_store_title","page_header_store_title_Container", page_header_store_title_oACDS);
    page_header_store_title_oAutoComp.minQueryLength = 0;
    page_header_store_title_oAutoComp.queryDelay = 0.1;



    var page_html_head_resume_oACDS = new YAHOO.util.FunctionDataSource(validate_page_html_head_resume);
    page_html_head_resume_oACDS.queryMatchContains = true;
    var page_html_head_resume_oAutoComp = new YAHOO.widget.AutoComplete("page_html_head_resume","page_html_head_resume_Container", page_html_head_resume_oACDS);
    page_html_head_resume_oAutoComp.minQueryLength = 0;
    page_html_head_resume_oAutoComp.queryDelay = 0.1;


       var page_includes_content_oACDS = new YAHOO.util.FunctionDataSource(validate_page_includes_head_content);
    page_includes_content_oACDS.queryMatchContains = true;
    var page_includes_content_oAutoComp = new YAHOO.widget.AutoComplete("head_content", "head_content_Container", page_includes_content_oACDS);
    page_includes_content_oAutoComp.minQueryLength = 0;
    page_includes_content_oAutoComp.queryDelay = 0.1;

            var page_includes_content_oACDS = new YAHOO.util.FunctionDataSource(validate_page_includes_body_content);
    page_includes_content_oACDS.queryMatchContains = true;
    var page_includes_content_oAutoComp = new YAHOO.widget.AutoComplete("body_content", "body_content_Container", page_includes_content_oACDS);
    page_includes_content_oAutoComp.minQueryLength = 0;
    page_includes_content_oAutoComp.queryDelay = 0.1;


  var oACDS7 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS7.queryMatchContains = true;
 oACDS7.table_id=7;
 var oAutoComp7 = new YAHOO.widget.AutoComplete("f_input7","f_container7", oACDS7);
 oAutoComp7.minQueryLength = 0;

    YAHOO.util.Event.addListener('clean_table_filter_show7', "click",show_filter,7);
 YAHOO.util.Event.addListener('clean_table_filter_hide7', "click",hide_filter,7);



       var myConfig = {
        height: Dom.get('content_height').value+'px',
        width: '972px',
        animate: true,
        dompath: true,
        focusAtStart: true,
         autoHeight: true
    };

    var state = 'off';



        EmailHTMLEditor = new YAHOO.widget.Editor('html_editor', myConfig);


   EmailHTMLEditor.on('toolbarLoaded', function() {

        var codeConfig = {
            type: 'push', label: 'Edit HTML Code', value: 'editcode'
        };
        this.toolbar.addButtonToGroup(codeConfig, 'insertitem');

        this.toolbar.on('editcodeClick', function() {



            var ta = this.get('element'),iframe = this.get('iframe').get('element');

            if (state == 'on') {
                state = 'off';
                this.toolbar.set('disabled', false);
                          this.setEditorHTML(ta.value);
                if (!this.browser.ie) {
                    this._setDesignMode('on');
                }

                Dom.removeClass(iframe, 'editor-hidden');
                Dom.addClass(ta, 'editor-hidden');
                this.show();
                this._focusWindow();
            } else {
                state = 'on';

                this.cleanHTML();

                Dom.addClass(iframe, 'editor-hidden');
                Dom.removeClass(ta, 'editor-hidden');
                this.toolbar.set('disabled', true);
                this.toolbar.getButtonByValue('editcode').set('disabled', false);
                this.toolbar.selectButton('editcode');
                this.dompath.innerHTML = 'Editing HTML Code';
                this.hide();

            }
            return false;
        }, this, true);

        this.on('cleanHTML', function(ev) {
            this.get('element').value = ev.html;
        }, this, true);



        this.on('editorKeyUp', html_editor_changed, this, true);
                this.on('editorDoubleClick', html_editor_changed, this, true);
                this.on('editorMouseDown', html_editor_changed, this, true);
                this.on('buttonClick', html_editor_changed, this, true);

        this.on('afterRender', function() {
            var wrapper = this.get('editor_wrapper');
            wrapper.appendChild(this.get('element'));
            this.setStyle('width', '100%');
            this.setStyle('height', '100%');
            this.setStyle('visibility', '');
            this.setStyle('top', '');
            this.setStyle('left', '');
            this.setStyle('position', '');

            this.addClass('editor-hidden');
        }, this, true);
    }, EmailHTMLEditor, true);
   yuiImgUploader(EmailHTMLEditor, 'html_editor', 'ar_upload_file_from_editor.php','image');
   EmailHTMLEditor._defaultToolbar.titlebar = "";
   EmailHTMLEditor.on('editorContentLoaded', function() {

        var head = this._getDoc().getElementsByTagName('head')[0];

<?php

$css_files=array();

$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Site External File Bridge` where `External File Type`='CSS' and `Site Key`=%d",$site_key);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$css_files[]='public_external_file.php?id='.$row['external_file_key'];

}
$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Page Store External File Bridge` where `External File Type`='CSS' and `Page Key`=%d",$page_key);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$css_files[]='public_external_file.php?id='.$row['external_file_key'];

}



foreach ($css_files as $css_file) {
	printf("var link = this._getDoc().createElement('link');\nlink.setAttribute('rel', 'stylesheet');\nlink.setAttribute('type', 'text/css');\nlink.setAttribute('href', '%s');\nhead.appendChild(link);\n\n",
		$css_file
	);
}

?>


    }, EmailHTMLEditor, true);

    EmailHTMLEditor.render();



}

function change_state(value) {

    options = Dom.getElementsByClassName('option', 'button', 'Page_State_options')
    Dom.removeClass(options, 'selected')
    Dom.addClass('Page_State_' + value, 'selected')

    //  alert('Page_State_' + type+' '+value)
    Dom.get('Page_State').value = value;

    validate_scope_data['page_state']['Page_State']['value'] = value;

    ovalue = Dom.get('Page_State').getAttribute('ovalue');

    if (ovalue != value) {
        validate_scope_data['page_state']['Page_State']['changed'] = true;
    } else {

        validate_scope_data['page_state']['Page_State']['changed'] = false;
    }
    validate_scope('page_state')

}

function change_stealth_mode(value) {

    options = Dom.getElementsByClassName('option', 'button', 'Page_Stealth_Mode_options')
    Dom.removeClass(options, 'selected')
    Dom.addClass('Page_Stealth_Mode_' + value, 'selected')

    //  alert('Page_Stealth_Mode_' + type+' '+value)
    Dom.get('Page_Stealth_Mode').value = value;

    validate_scope_data['page_state']['Page_Stealth_Mode']['value'] = value;

    ovalue = Dom.get('Page_Stealth_Mode').getAttribute('ovalue');

    if (ovalue != value) {
        validate_scope_data['page_state']['Page_Stealth_Mode']['changed'] = true;
    } else {

        validate_scope_data['page_state']['Page_Stealth_Mode']['changed'] = false;
    }
    validate_scope('page_state')

}



function save_edit_page_state() {
    save_edit_general('page_state');
}

function reset_edit_page_state() {
    reset_edit_general('page_state')

    val = Dom.get('Page_State').getAttribute('ovalue')

    options = Dom.getElementsByClassName('option', 'button', 'Page_State_options')
    Dom.removeClass(options, 'selected')
    Dom.addClass('Page_State_' + val, 'selected')

  val = Dom.get('Page_Stealth_Mode').getAttribute('ovalue')

    options = Dom.getElementsByClassName('option', 'button', 'Page_Stealth_Mode_options')
    Dom.removeClass(options, 'selected')
    Dom.addClass('Page_Stealth_Mode_' + val, 'selected')

}



YAHOO.util.Event.onDOMReady(init);
