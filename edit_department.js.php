<?php include_once('common.php');?>
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
var department_id=<?php echo$_SESSION['state']['department']['id']?>;

var can_add_department=false;

var scope_key=<?php echo$_SESSION['state']['department']['id']?>;
var scope='department';
var store_key=<?php echo$_SESSION['state']['store']['id']?>;

var validate_scope_metadata={
    'department':{'type':'edit','ar_file':'ar_edit_assets.php','key_name':'id','key':<?php echo$_SESSION['state']['department']['id']?>}

};

var validate_scope_data={
    'department':{
	'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item'
		,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Department Name')?>'}],'name':'name'
		,'ar':'find','ar_request':'ar_assets.php?tipo=is_department_name&store_key='+store_key+'&query='}
	,'code':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
		 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Department Code')?>'}]
		 ,'name':'code','ar':'find','ar_request':'ar_assets.php?tipo=is_department_code&store_key='+store_key+'&query='}}
};









function change_block(e){
    
     
    // if(this.id=='pictures' || this.id=='discounts'){
	//    Dom.get('info_name').style.display='';
	//}else
	//    Dom.get('info_name').style.display='none';
     
     
	 Dom.get('d_families').style.display='none';
	 Dom.get('d_details').style.display='none';
	 Dom.get('d_discounts').style.display='none';
	 Dom.get('d_pictures').style.display='none';
	 Dom.get('d_web').style.display='none';
	 Dom.get('d_'+this.id).style.display='';
	
	 ids=['families','details','discounts','pictures','web'];

Dom.removeClass(ids,'selected');
	
	 Dom.addClass(this, 'selected');
	 YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=department-editing&value='+this.id ,{});
	
  
}
// -------------------------------strts --------------------------------------------------
var CellEdit = function (callback, newValue) {


		var record = this.getRecord(),
		column = this.getColumn(),
		oldValue = this.value,
		datatable = this.getDataTable(),
		recordIndex = datatable.getRecordIndex(record);

	//	alert(	'tipo=edit_'+column.object+'&key=' + column.key + '&newvalue=' + 
//						encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue)+ //
//						myBuildUrl(datatable,record))


		YAHOO.util.Connect.asyncRequest(
						'POST',
						'ar_edit_assets.php', {
						    success:function(o) {
								alert(o.responseText);
							var r = YAHOO.lang.JSON.parse(o.responseText);
							if (r.state == 200) {

							    if(column.key=='price' || column.key=='unit_price' || column.key=='margin' ){
								
                               datatable.updateCell(record,'unit_price',r.newdata['Unit Price']);
							   datatable.updateCell(record,'margin',r.newdata['Margin']);
                               datatable.updateCell(record,'price',r.newdata['Price']);
                               datatable.updateCell(record,'rrp_info','<?php echo _('Margin')?> '+r.newdata['RRP Margin']);

								
								
								//datatable.updateRow(recordIndex,data);
								callback(true,r.newvalue);
								
							    }else if(column.key=='unit_rrp'  ){
								 datatable.updateCell(record,'unit_rrp',r.newdata['RRP Per Unit']);
                               datatable.updateCell(record,'rrp_info','<?php echo _('Margin')?> '+r.newdata['RRP Margin']);
								
								callback(true, r.newvalue);
								
							    }else{
							
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







function new_product_changed(o){
    if(Dom.get("new_code").value!='' && Dom.get("new_code").value!='')
	Dom.get("add_new_product").style.display='';
    else
	Dom.get("add_new_product").style.display='';


}

function deal_term_save(deal_key){
deal_save(deal_key,'term');
}
function deal_allowance_save(deal_key){
deal_save(deal_key,'allowance');
}
function deal_save(deal_key,key){
	
        
       
        var newValue=Dom.get('deal_'+key+deal_key).value;
        var oldValue=Dom.get('deal_'+key+deal_key).getAttribute('ovalue');

		var request='tipo=edit_deal&key=' + key + '&newvalue=' + 
						encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue)+ '&deal_key='+deal_key

		YAHOO.util.Connect.asyncRequest(
						'POST',
						'ar_edit_assets.php', {
						    success:function(o) {
								alert(o.responseText);
							var r = YAHOO.lang.JSON.parse(o.responseText);
							if (r.state == 200) {

							 
								Dom.get('deal_description'+deal_key).innerHTML=r.description;
								Dom.get('deal_'+key+deal_key).setAttribute=('ovalue',r.newvalue);
								Dom.get('deal_'+key+deal_key).value=r.newvalue;

								Dom.get('deal_'+key+'_save'+deal_key).style.display='none';
								Dom.get('deal_'+key+'_reset'+deal_key).style.display='none';
								
							    }else{
						
								
								
							    }
						
						    },
							failure:function(o) {
							alert(o.statusText);
							callback();
						    },
							scope:this
							},
						request
						
						);  


}
function deal_term_reset(deal_key){
    var data=deal_data[deal_key]['terms'];
    old_value=data.ovalue;
    Dom.get('deal_term_term'+deal_key).value=old_value;
    Dom.get('deal_term_save'+deal_key).style.visibility='hidden';
    Dom.get('deal_term_reset'+deal_key).style.visibility='hidden';
}



function deal_term_changed(deal_key){
    var data=deal_data[deal_key]['terms'];
    old_value=Dom.get('deal_term'+deal_key).getAttribute('ovalue');
    new_value=Dom.get('deal_term'+deal_key).value;

    if(old_value!=new_value){
	Dom.get('deal_term_reset'+deal_key).style.visibility='visible';

    switch(data.type){
    case('Order Interval'):

	break;

    case('Family Quantity Ordered'):
	
	
	Dom.get('deal_term_save'+deal_key).style.visibility='visible';

	var validator=/^\d+$/;
	if(!validator.test(new_value)){
	      Dom.get('deal_term_save'+deal_key).style.visibility='hidden';
	}
	break;


    }
    }else{
	
	Dom.get('deal_term_save'+deal_key).style.visibility='hidden';
	Dom.get('deal_term_reset'+deal_key).style.visibility='hidden';

    }

}
function old_deal_allowance_save(item,deal_key){

	var request='ar_edit_assets?tipo=edit_deal&key=' + item+ '&newvalue=' + 
	    encodeURIComponent(value) +  '&oldvalue=' + 
	    '&deal_key='+deal_key;
	//		alert(request)
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		    //		   	    alert(o.responseText)
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state==200){
			
			
			
		
		    }else{
			validate_scope_data[branch][r.key].changed=true;
			validate_scope_data[branch][r.key].validated=false;
			Dom.get(validate_scope_data[branch][r.key].name+'_msg').innerHTML=r.msg;
			
		    }
		    
		}
			    
	    });
	}


function deal_allowance_changed(deal_key){
    var data=deal_data[deal_key]['allowances'];
        old_value=Dom.get('deal_allowance'+deal_key).getAttribute('ovalue');

    new_value=Dom.get('deal_allowance'+deal_key).value;
     //alert(old_value+'->'+new_value)
    if(old_value!=new_value){
	Dom.get('deal_allowance_reset'+deal_key).style.visibility='visible';

    switch(data.type){
    case('Get Same Fre'):
	break;
    case('Get Free'):
	break;
    
    case('Percentage Off'):
	
	
	Dom.get('deal_allowance_save'+deal_key).style.visibility='visible';

	var validator=/^(\d+|\.\d+|\d+.|\d+\.\d+)\s*\%?$/;
	if(!validator.test(new_value)){
	      Dom.get('deal_allowance_save'+deal_key).style.visibility='hidden';
	}
	break;


    }
    }else{
	
	Dom.get('deal_allowance_save'+deal_key).style.visibility='hidden';
	Dom.get('deal_allowance_reset'+deal_key).style.visibility='hidden';

    }

}





var description_num_changed=0;
var description_partrnings= new Object();
var description_errors= new Object();



  var change_view=function(e){
	
	var table=tables['table0'];
	var tipo=this.id;
	//	alert(table.view+' '+tipo)
	if(table.view!=tipo){
	    table.hideColumn('name');

	    table.hideColumn('sdescription');
	    table.hideColumn('units');
	    table.hideColumn('units_info');
	    table.hideColumn('price_info');
	    table.hideColumn('price');
	    table.hideColumn('unit_rrp');
	    table.hideColumn('rrp_info');
	    table.hideColumn('code');
	    table.hideColumn('code_price');

	    table.hideColumn('unit_type');
	    table.hideColumn('unit_price');
	    table.hideColumn('margin');

	    table.hideColumn('processing');
	    table.hideColumn('sales_state');
	    table.hideColumn('web_state');
	    table.hideColumn('state_info');
		table.hideColumn('smallname');


	    if(tipo=='view_name'){
		table.showColumn('code');
		table.showColumn('name');

		table.showColumn('sdescription');	

	    }
	    else if(tipo=='view_units'){
		 table.showColumn('code');
		table.showColumn('units');
		table.showColumn('unit_type');

	    }
	     else if(tipo=='view_state'){
		 table.showColumn('code');
		table.showColumn('processing');
		table.showColumn('sales_state');
		table.showColumn('web_state');
		table.showColumn('state_info');
		table.showColumn('smallname');


	    }
	    
	    else if(tipo=='view_price'){
		table.showColumn('code_price');
		table.showColumn('unit_price');
		table.showColumn('margin');
		table.showColumn('units_info');
		
		table.showColumn('price');
		table.showColumn('unit_rrp');
		table.showColumn('price_info');
		table.showColumn('rrp_info');


	    }
	    
	    


	Dom.get(table.view).className="";
	Dom.get(tipo).className="selected";
	table.view=tipo
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=department-edit_view&value=' + escape(tipo),{} );
	}
  }




function update_form(){

    
    if(editing=='description'){
	this_errors=description_errors;
	this_num_changed=description_num_changed

    }

    if(this_num_changed>0){
	Dom.get(editing+'_save').style.display='';
	Dom.get(editing+'_reset').style.display='';

    }else{
	Dom.get(editing+'_save').style.display='none';
	Dom.get(editing+'_reset').style.display='none';

    }
    Dom.get(editing+'_num_changes').innerHTML=this_num_changed;

    // Dom.get(editing+'_save_div').style.display='';
    errors_div=Dom.get(editing+'_errors');
    // alert(errors);
    errors_div.innerHTML='';


    for (x in this_errors)
	{
	    // alert(errors[x]);
	    Dom.get(editing+'_save').style.display='none';
	    errors_div.innerHTML=errors_div.innerHTML+' '+this_errors[x];
	}




}


//function create_part(){
//    var part_description=Dom.get('new_name').value;
//    if(part_description=='')
//	part_description='??';
//    var part_used_in=Dom.get('new_code').value;
//    if(part_used_in=='')
//	part_used_in='??';


//    var data={sku:'TBC',description:part_description,usedin:part_used_in,partsperpick:1,notes:'',delete:'<img src="art/icons/cross.png">'}
//    tables.table1.addRow(data, 0);
//}

function edit_department_changed(o){
    var ovalue=o.getAttribute('ovalue');
    var name=o.name;
    if(ovalue!=o.value){
	if(name=='code'){
	    if(o.value==''){
		description_errors.code="<?php echo _("The department code can not be empty")?>";
	    }else if(o.value.lenght>16){
		description_errors.code="<?php echo _("The product code can not have more than 16 characters")?>";
	    }else
		delete description_errors.code;
	}
	if(name=='name'){
	    if(o.value==''){
		description_errors.name="<?php echo _("The department name can not be empty")?>";
	    }else if(o.value.lenght>255){
		description_errors.name="<?php echo _("The product code can not have more than 255  characters")?>";
	    }else
		delete description_errors.name;
	}
	

	if(o.getAttribute('changed')==0){
	    description_num_changed++;
	    o.setAttribute('changed',1);
	}
    }else{
	if(o.getAttribute('changed')==1){
	    description_num_changed--;
	    o.setAttribute('changed',0);
	}
    }
    update_form();
}

function reset(tipo){

    if(tipo=='description'){
	tag='name';
	Dom.get(tag).value=Dom.get(tag).getAttribute('ovalue');
	Dom.get(tag).setAttribute('changed',0);
	tag='code';
	Dom.get(tag).value=Dom.get(tag).getAttribute('ovalue');
	Dom.get(tag).setAttribute('changed',0);
	
	
	Dom.get(editing+'_save').style.display='none';
	Dom.get(editing+'_reset').style.display='none';

	Dom.get(editing+'_num_changes').innerHTML=description_num_changed;
	description_partrnings= new Object();
	description_errors= new Object();
	
    }
    update_form();
}

function save(tipo){

    if(tipo=='description'){
	var keys=new Array("code","name","special_char");
	for (x in keys)
	    {
		 key=keys[x];
		 element=Dom.get(key);
		if(element.getAttribute('changed')==1){

		    newValue=element.value;
		    oldValue=element.getAttribute('ovalue');
		    
		    var request='ar_edit_assets.php?tipo=edit_department&key=' + key+ '&newvalue=' + 
			encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue)+ 
			'&id='+family_id;
		  
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
			    success:function(o) {
				alert(o.responseText);
				var r =  YAHOO.lang.JSON.parse(o.responseText);
				if(r.state==200){
				    var element=Dom.get(r.key);
				    element.getAttribute('ovalue',r.newvalue);
				    element.value=r.newvalue;
				    element.setAttribute('changed',0);
				    description_num_changed--;
				    var table=tables.table1;
				    var datasource=tables.dataSource1;
				    var request='';
				    datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
				    if(r.key=='name')
					Dom.get('title_name').innerHTML=r.newvalue;
				    
				     if(r.key=='code')
					Dom.get('title_code').innerHTML=r.newvalue;
				    


				}
				update_form();	
			    }
			    
			});
		}
	    }
	
    }

}


function new_department_changed(o){
    if(Dom.get("new_code").value!='' && Dom.get("new_name").value!=''){
	Dom.get("add_new_department").style.display='';
    }else
	Dom.get("add_new_department").style.display='none';
}


function save_new_department(){

    var msg_div='add_department_messages';

    var code=Dom.get('new_code').value;
    var name=Dom.get('new_name').value;
    var description=Dom.get('new_description').innerHTML;
    var request='ar_edit_assets.php?tipo=new_department&code='+encodeURIComponent(code)+'&name='+encodeURIComponent(name)+'&description='+encodeURIComponent(name);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
		    var table=tables['table0'];
		    var datasource=tables['dataSource0'];
		    var request='';
		    datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
		    Dom.get(msg_div).innerHTML='';
		}else
		    Dom.get(msg_div).innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	    
	    });

}



var tmponCellClick = function(oArgs) {
		var target = oArgs.target,
		column = this.getColumn(target),
		record = this.getRecord(target);
		switch (column.action) {
		case 'delete':
		    this.deleteRow(target);
		    break;
		default:

		    this.onEventShowCellEditor(oArgs);
		    break;
		}
	    };    var highlightEditableCell = function(oArgs) {
		var target = oArgs.target;
		column = this.getColumn(target);

		switch (column.action) {
		case 'delete':
		    this.highlightRow(target);
		default:
		    if(YAHOO.util.Dom.hasClass(target, "yui-dt-editable")) {
			this.highlightCell(target);
		    }
		}
	    };

	      var unhighlightEditableCell = function(oArgs) {
		var target = oArgs.target;
		column = this.getColumn(target);

		switch (column.action) {
		case 'delete':
		    this.unhighlightRow(target);
		default:
		    if(YAHOO.util.Dom.hasClass(target, "yui-dt-editable")) {
			this.unhighlightCell(target);
		    }
		}
	    };


// ------------------------------ends ---------------------------------------------------


YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {




	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"id", label:"", hidden:true,action:"none",isPrimaryKey:true}
				    ,{key:"go", label:"", width:20,action:"none"}
				    ,{key:"code", label:"<?php echo _('Code')?>", width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'department'}
				    ,{key:"name", label:"<?php echo _('Name')?>",width:300, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'department'}
				    ,{key:"delete", label:"",width:100,className:"aleft",action:"delete",object:'department'}
				    ,{key:"delete_type", label:"",hidden:true,isTypeKey:true}

				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_edit_assets.php?tipo=edit_departments&parent=department&tableid=0");
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
			 "code",
			 "name",
			 'delete','delete_type','id','edit','go'
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['tables']['departments_list'][2]?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['tables']['departments_list'][0]?>",
									 dir: "<?php echo$_SESSION['tables']['departments_list'][1]?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;





	    this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table0.subscribe("cellClickEvent", onCellClick);
	    
	    this.table0.view='<?php echo$_SESSION['state']['department']['view']?>';

		

 var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				       {key:"date",label:"<?php echo _('Date')?>", width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"author",label:"<?php echo _('Author')?>", width:70,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"abstract", label:"<?php echo _('Description')?>", width:370,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ];
	    //?tipo=customers&tid=0"

	    this.dataSource1 = new YAHOO.util.DataSource("ar_history.php?tipo=history&type=department&tableid=1");
	    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    this.dataSource1.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    rtext:"resultset.rtext",
		    totalRecords: "resultset.total_records" // Access to value in the server response
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
								 rowsPerPage    : <?php echo$_SESSION['state']['department']['history']['nr']?>,containers : 'paginator1', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    Key: "<?php echo$_SESSION['state']['department']['history']['order']?>",
							     dir: "<?php echo$_SESSION['state']['department']['history']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	    this.table1.filter={key:'<?php echo$_SESSION['state']['department']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['department']['history']['f_value']?>'};
	    YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown);





	};
    });


function validate_code(query){
   
 validate_general('department','code',unescape(query));
}
function validate_name(query){
 validate_general('department','name',unescape(query));
}


function reset_edit_department(){
 reset_edit_general('department');
}
function save_edit_department(){
 save_edit_general('departmenty');
}



function post_item_updated_actions(branch,key,newvalue){

 if(key=='name')
     Dom.get('title_name').innerHTML=newvalue;
 
 else if(key=='code')
     Dom.get('title_code').innerHTML=newvalue;

 
 var table=tables.table1;
 var datasource=tables.dataSource1;
 var request='';
 datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
 
}

function init(){

//   var ids = ["checkbox_thumbnails","checkbox_list","checkbox_slideshow","checkbox_manual"]; 
 //   YAHOO.util.Event.addListener(ids, "click", select_layout);


 	//YAHOO.util.Event.on('uploadButton', 'click', onUploadButtonClick);

 


var department_code_oACDS = new YAHOO.util.FunctionDataSource(validate_code);
    department_code_oACDS.queryMatchContains = true;
    var department_code_oAutoComp = new YAHOO.widget.AutoComplete("code","code_Container", department_code_oACDS);
    department_code_oAutoComp.minQueryLength = 0; 
    department_code_oAutoComp.queryDelay = 0.1;
    
     var department_name_oACDS = new YAHOO.util.FunctionDataSource(validate_name);
    department_name_oACDS.queryMatchContains = true;
    var department_name_oAutoComp = new YAHOO.widget.AutoComplete("name","name_Container", department_name_oACDS);
    department_name_oAutoComp.minQueryLength = 0; 
    department_name_oAutoComp.queryDelay = 0.1;





 
    function mygetTerms(query) {multireload();};
    var oACDS = new YAHOO.widget.DS_JSFunction(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","filtercontainer0", oACDS);
    oAutoComp.minQueryLength = 0; 
    
    var ids = ["details","families","discounts","pictures","web"]; 
    YAHOO.util.Event.addListener(ids, "click", change_block);
   YAHOO.util.Event.addListener('add_family', "click");
}

YAHOO.util.Event.onDOMReady(init);
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
