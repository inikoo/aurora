<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
//$connect_to_external=true;
include_once('common.php');
$sql=sprintf("select * from `Deal Metadata Dimension`D where D.`Deal Metadata Trigger`='Family' and D.`Deal Metadata Trigger Key`= %d ",$_REQUEST['id']);
$res=mysql_query($sql);
$deal_data="";
while($row=mysql_fetch_array($res)){
  $deal_data.=sprintf(',"%d":{"terms":{"ovalue":"%s","type":"%s"},"allowances":{"ovalue":"%s","type":"%s"}}'."\n"
		      ,$row['Deal Metadata Key']
		      ,$row['Deal Metadata Terms']
		      ,$row['Deal Metadata Terms Type']
		      ,$row['Deal Metadata Allowance']
		      ,$row['Deal Metadata Allowance Type']
		      );

}
mysql_free_result($res);
$deal_data=preg_replace('/^,/','',$deal_data);
$deal_data="var deal_data={\n$deal_data};\n";
print $deal_data;

?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
var family_id=<?php echo $_REQUEST['id']?>;

var scope_key=<?php echo $_REQUEST['id']?>;
var scope='family';
var scope_edit_ar_file='ar_edit_assets.php';
var scope_key_name='id';
var store_key=<?php echo $_REQUEST['store_key']?>;
var dialog_family_list;
var dialog_page_list;

function change_elements(){

ids=['elements_discontinued','elements_nosale','elements_private','elements_sale','elements_historic'];


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





function validate_product_code(query){validate_general('product','product_code',unescape(query));}
function validate_product_name(query){validate_general('product','product_name',unescape(query));}
function validate_product_special_char(query){validate_general('product','product_special_char',unescape(query));}
function validate_product_units(query){validate_general('product','product_units',unescape(query));}
function validate_product_price(query){validate_general('product','product_price',unescape(query));}
function validate_product_retail_price(query){validate_general('product','product_retail_price',unescape(query));}



function validate_code(query){validate_general('family','code',unescape(query));}
function validate_name(query){
 validate_general('family','name',unescape(query));
}
function validate_special_char(query){
 validate_general('family','special_char',unescape(query));
}
function validate_description(query){
   
 validate_general('family','description',unescape(query));
}


function reset_edit_family(){
 reset_edit_general('family');
}
function save_edit_family(){
 save_edit_general('family');
}

function post_item_updated_actions(branch,r){
key=r.key;
newvalue=r.newvalue;
 if(key=='name')
     Dom.get('title_name').innerHTML=newvalue;
 
 else if(key=='code')
     Dom.get('title_code').innerHTML=newvalue;

 
 var table=tables.table1;
 var datasource=tables.dataSource1;
 var request='';
 datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
 
}




function change_block(e){
   
     Dom.setStyle(['d_products','d_details','d_discounts','d_pictures','d_web'],'display','none');
 	 Dom.get('d_'+this.id).style.display='';
	 Dom.removeClass(['products','details','discounts','pictures','web'],'selected');
	 Dom.addClass(this, 'selected');
	 YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=family-editing&value='+this.id ,{});
   
}

var CellEdit = function (callback, newValue) {


		var record = this.getRecord(),
		column = this.getColumn(),
		oldValue = this.value,
		datatable = this.getDataTable(),
		recordIndex = datatable.getRecordIndex(record);


						
			if(column.object=='family_page_properties')	
					request_page=	'ar_edit_sites.php';			

			else
		request_page=	'ar_edit_assets.php';			
						
						
						
		YAHOO.util.Connect.asyncRequest(
						'POST',
						request_page, {
						    success:function(o) {
						//	alert(o.responseText);
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
								
							    }else if(column.key=='web_configuration'  ){
								 datatable.updateCell(record,'smallname',r.newdata['description']);
								 datatable.updateCell(record,'formated_web_configuration',r.newdata['formated_web_configuration']);
								 datatable.updateCell(record,'web_configuration',r.newdata['web_configuration']);


                             	// alert(r.newdata['web_configuration'])   
								callback(true, r.newdata['web_configuration']);
								
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
		table.hideColumn('sales_type');
		table.hideColumn('web_configuration');




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
	//	table.showColumn('processing');
		//table.showColumn('sales_state');
		table.showColumn('web_configuration');
		//table.showColumn('state_info');
		table.showColumn('smallname');
//table.showColumn('sales_type');

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
	

	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=family-products-edit_view&value=' + escape(tipo),{} );
	}
  }



function delete_family(){
Dom.setStyle(['save_delete_family','cancel_delete_family','delete_family_warning'],'display','');
Dom.setStyle('delete_family','display','none');
}
function cancel_delete_family(){
Dom.setStyle(['save_delete_family','cancel_delete_family','delete_family_warning'],'display','none');
Dom.setStyle('delete_family','display','');
}
function save_delete_family(){


var request='ar_edit_assets.php?tipo=delete_family&delete_type=delete&family_key=' + family_id
	           
	           Dom.setStyle('deleting','display','');
	           	           Dom.setStyle(['save_delete_family','cancel_delete_family'],'display','none');
alert(request);
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
	            success:function(o){
	           alert(o.responseText);	
			var r =  YAHOO.lang.JSON.parse(o.responseText);
			if(r.state==200){
        location.href='family.php?id='+family_id;
                                  }else{
                                   Dom.setStyle('deleting','display','none');
                                  Dom.get('delete_family_msg').innerHTML=r.msg
                                  }
   			}
    });


}





 function formater_web_configuration  (el, oRecord, oColumn, oData) {
		     el.innerHTML = oRecord.getData("formated_web_configuration");
	    }




YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

   
   


	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"pid", label:"", hidden:true,action:"none",isPrimaryKey:true}
				    ,{key:"go", label:"", width:20,action:"none"}
				    ,{key:"code",<?php echo($_SESSION['state']['family']['products']['edit_view']!='view_price'?'':'hidden:true,')?>  label:"<?php echo _('Code')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"code_price",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_price'?'':'hidden:true,')?>  label:"<?php echo _('Code')?>", width:105,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				   // ,{key:"units_info",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_price'?'':'hidden:true,')?> label:"<?php echo _('Units')?>", width:30,className:"aleft"}
				    
				    ,{key:"name", label:"<?php echo _('Name')?>",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_name'?'':'hidden:true,')?>width:340, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product'}
				    ,{key:"smallname", label:"<?php echo _('Description')?>",width:480, sortable:true,className:"aleft",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_state'?'':'hidden:true,')?>className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				 //  ,{key:"processing", label:"<?php echo _('Editing State')?>",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_state'?'':'hidden:true,')?>width:220, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},object:'product',editor: new YAHOO.widget.RadioCellEditor({asyncSubmitter: CellEdit,radioOptions:["<?php echo _('Editing')?>","<?php echo _('Live')?>"],disableBtns:true})}
				    ,{key:"sales_type", label:"",hidden:true,width:100, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},object:'product',editor: new YAHOO.widget.RadioCellEditor({asyncSubmitter: CellEdit,radioOptions:["<?php echo _('Public Sale')?>","<?php echo _('Private Sale')?>","<?php echo _('Discontinue')?>","<?php echo _('Not For Sale')?>"],disableBtns:true})}
				  
				  ,{key:"web_configuration" ,formatter: formater_web_configuration , label:"<?php echo _('Web/Sale Status')?>",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_state'?'':'hidden:true,')?>width:120, sortable:false,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},object:'product',editor: new YAHOO.widget.RadioCellEditor({asyncSubmitter: CellEdit,radioOptions:[
				    {'value':"Online Auto",'label':"<?php echo _('Auto')?><br/>"},
				    {'value':"Online Force For Sale",'label':"<?php echo _('Force online')?><br/>"},
				    {'label':"<?php echo _('Force out of stock')?><br/>",'value':"Online Force Out of Stock"},
				    {'label':"<?php echo _('Force offline')?><br/>",'value':'Offline'},
				    {'label':"<?php echo _('Private Sale')?><br/>",'value':'Private Sale'},
				    {'label':"<?php echo _('Not For Sale')?>",'value':'Not for Sale'}
				    ],disableBtns:true})}
				    ,{key:"formated_web_configuration" , label:"",hidden:true}



				    ,{key:"sdescription", label:"<?php echo _('Special Characteristic')?>",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_name'?'':'hidden:true,')?>width:285, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product'}
				    ,{key:"units", label:"<?php echo _('Units')?>",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_units'?'':'hidden:true,')?>width:40, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product'}
				    ,{key:"unit_type", label:"<?php echo _('Unit Type')?>",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_units'?'':'hidden:true,')?>width:80, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product'}
				    ,{key:"price", label:"<?php echo _('Price')?>",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_price'?'':'hidden:true,')?>width:80, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product'}
				    ,{key:"unit_price", label:"<?php echo _('U Price')?>",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_price'?'':'hidden:true,')?>width:80, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product'}
				    ,{key:"margin", label:"<?php echo _('Margin')?>",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_price'?'':'hidden:true,')?>width:80, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product'}

				    ,{key:"price_info", label:"<?php echo _('Price Notes')?>",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_price'?'':'hidden:true,')?>width:140, sortable:false,className:"aleft"}
				    ,{key:"unit_rrp", label:"<?php echo _('Unit RRP')?>",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_price'?'':'hidden:true,')?>width:80, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product'}
				    ,{key:"rrp_info", label:"<?php echo _('RRP Notes')?>",<?php echo($_SESSION['state']['family']['products']['edit_view']=='view_price'?'':'hidden:true,')?>width:120, sortable:false,className:"aleft"}
				    //,{key:"delete", label:"",width:100,className:"aleft",action:"delete",object:'product'}
				    //,{key:"delete_type", label:"",hidden:true,isTypeKey:true}

				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_edit_assets.php?tipo=edit_products&sf=0&parent=family&parent_key="+Dom.get('family_key').value);
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
			 "code","units_info","code_price",'go','smallname','sales_type','pid',
			 "name",
			 'delete','delete_type','id','sdescription','price','unit_rrp','units','unit_type','rrp_info','price_info','unit_price','margin','processing','sales_state','sales_state','formated_web_configuration','web_configuration'
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['family']['products']['nr']?>,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alpartysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['family']['products']['order']?>",
									 dir: "<?php echo$_SESSION['state']['family']['products']['order_dir']?>"
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;



        this.table0.table_id=tableid;
        this.table0.subscribe("renderEvent", myrenderEvent);

	    this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table0.subscribe("cellClickEvent", onCellClick);
	    
	    this.table0.view='<?php echo$_SESSION['state']['family']['products']['edit_view']?>';



   var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				       {key:"date",label:"<?php echo _('Date')?>", width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"author",label:"<?php echo _('Author')?>", width:70,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       //     ,{key:"tipo", label:"<?php echo _('Type')?>", width:90,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       //,{key:"diff_qty",label:"<?php echo _('Qty')?>", width:90,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"abstract", label:"<?php echo _('Description')?>", width:500,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ];
	    //?tipo=customers&tid=0"

	    this.dataSource1 = new YAHOO.util.DataSource("ar_history.php?tipo=history&type=family&tableid=1");
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
			 "id"
			 ,"note"
			 ,'author','date','tipo','abstract','details'
			 ]};
	    
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource1
						     , {

							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    : <?php echo$_SESSION['state']['family']['history']['nr']?>,containers : 'paginator1', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    key: "<?php echo$_SESSION['state']['family']['history']['order']?>",
							     dir: "<?php echo$_SESSION['state']['family']['history']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table1.table_id=tableid;
        this.table1.subscribe("renderEvent", myrenderEvent);
		    
		    
	    this.table1.filter={key:'<?php echo$_SESSION['state']['family']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['family']['history']['f_value']?>'};
	    


	    
		
		   var tableid=2; 
	    var tableDivEL="table"+tableid;

	   
	    var ColumnDefs = [
			 {key:"key", label:"",width:100,hidden:true}
                    ,{key:"code", label:"<?php echo _('Code')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"name", label:"<?php echo _('Name')?>",width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
						
			];
		this.dataSource2 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=department_list&store_key="+store_key+"&tableid="+tableid+"&nr=20&sf=0");
		//alert("ar_quick_tables.php?tipo=family_list&store_key="+store_key+"&tableid="+tableid+"&nr=20&sf=0");
	    this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource2.connXhrMode = "queueRequests";
	    	    this.dataSource2.table_id=tableid;

	    this.dataSource2.responseSchema = {
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

	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource2
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "code",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);

 this.table2.subscribe("rowMouseoverEvent", this.table2.onEventHighlightRow);
       this.table2.subscribe("rowMouseoutEvent", this.table2.onEventUnhighlightRow);
      this.table2.subscribe("rowClickEvent", select_department);
        this.table2.table_id=tableid;
           this.table2.subscribe("renderEvent", myrenderEvent);


	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table2.filter={key:'code',value:''};
		
		
    var tableid=4; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				          {key:"status",label:"", width:16,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					  ,{key:"name",label:"<?php echo _('Name')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"description",label:"<?php echo _('Description')?>", width:420,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"from",label:"<?php echo _('Valid From')?>", width:80,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"to",label:"<?php echo _('Valid Until')?>", width:80,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ];
	    //?tipo=customers&tid=0"
	    
	    this.dataSource4 = new YAHOO.util.DataSource("ar_edit_assets.php?tipo=edit_deals&parent=family&tableid=4");
	    this.dataSource4.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource4.connXhrMode = "queueRequests";
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
		    totalRecords: "resultset.total_records" 
		},
		
		
		fields: [
			 "name"
			 ,"description","from","to","status"

			 ]};
 this.table4 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource4
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    : <?php echo$_SESSION['state']['store']['deals']['nr']?>,containers : 'paginator4', alpartysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info4'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    key: "<?php echo$_SESSION['state']['store']['deals']['order']?>",
							     dir: "<?php echo$_SESSION['state']['store']['deals']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table4.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table4.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table4.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table4.table_id=tableid;
        this.table4.subscribe("renderEvent", myrenderEvent);
	  
		    
	    this.table4.filter={key:'<?php echo $_SESSION['state']['store']['deals']['f_field']?>',value:'<?php echo $_SESSION['state']['store']['deals']['f_value']?>'};


	     var tableid=5; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				       {key:"part_sku", label:"", hidden:true,action:"none",isPrimaryKey:true}
				       ,{key:"sku",label:"<?php echo _('SKU')?>", width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"picks",label:"<?php echo _('Picks')?>", width:30,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'part_new_product'}
				       ,{key:"description",label:"<?php echo _('Part Name')?>", width:230,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"notes",label:"<?php echo _('Notes for picker')?>", width:170,className:"aleft", editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'part_new_product' }
				       ,{key:"delete",label:"", width:20,className:"aleft",action:'delete',object:'part_new_product'}
				       
				       ];
	    //?tipo=customers&tid=0"
	    
	    this.dataSource5 = new YAHOO.util.DataSource("ar_edit_assets.php?tipo=part_list&product_id=0&tableid=5");
	    //this.dataSource5 =  new YAHOO.util.DataSource("ar_edit_assets.php?tipo=edit_deals&parent=family&tableid=4");
	    this.dataSource5.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource5.connXhrMode = "queueRequests";
	    this.dataSource5.responseSchema = {
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
			 "sku"
			 ,"description","picks","notes","delete","part_sku"

			 ]};

        this.table5 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource5
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    :<?php echo $_SESSION['state']['product']['parts']['nr']?> ,containers : 'paginator5', alpartysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info5'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    key: "<?php echo $_SESSION['state']['product']['parts']['order']?>",
							     dir: "<?php echo $_SESSION['state']['product']['parts']['order_dir']?>"
							 },
							 dynamicData : true
						     }
						     );
	    
	    this.table5.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table5.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table5.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table5.table_id=tableid;
        this.table5.subscribe("renderEvent", myrenderEvent);

	    this.table5.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table5.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table5.subscribe("cellClickEvent", onCellClick);
		    
	    this.table5.filter={key:'<?php echo $_SESSION['state']['product']['parts']['f_field']?>',value:'<?php echo $_SESSION['state']['product']['parts']['f_value']?>'};

   var tableid=6; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var CustomersColumnDefs = [
				       {key:"id", label:"", hidden:true,action:"none",isPrimaryKey:true}
				         ,{key:"go", label:"", width:20,action:"none"}
				         				       ,{key:"site",label:"<?php echo _('website')?>", width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"code",label:"<?php echo _('Code')?>", width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'family_page_properties'}
				       ,{key:"store_title",label:"<?php echo _('Header Title')?>", <?php echo($_SESSION['state']['family']['edit_pages']['view']=='page_header'?'':'hidden:true,')?>width:400,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'family_page_properties'}
				     	  ,{key:"link_title",label:"<?php echo _('Link Title')?>", <?php echo($_SESSION['state']['family']['edit_pages']['view']=='page_properties'?'':'hidden:true,')?>width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'family_page_properties'}
				     	  ,{key:"page_title",label:"<?php echo _('Browser Title')?>",<?php echo($_SESSION['state']['family']['edit_pages']['view']=='page_html_head'?'':'hidden:true,')?> width:300,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'family_page_properties'}
				     	  ,{key:"page_description",label:"<?php echo _('Description')?>",<?php echo($_SESSION['state']['family']['edit_pages']['view']=='page_html_head'?'':'hidden:true,')?> width:270,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'family_page_properties'}
				     ,{key:"delete", label:"",width:12,sortable:false,action:'delete',object:'page_store'}		         
				       ];
				       
	 
				       
				       
	    //?tipo=customers&tid=0"
	    
	    var request="ar_edit_sites.php?tipo=pages&parent=family&parent_key="+Dom.get('family_key').value+"&tableid=6";
	    //alert(request)
	        this.dataSource6 = new YAHOO.util.DataSource(request);

	    this.dataSource6.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource6.connXhrMode = "queueRequests";
	    this.dataSource6.responseSchema = {
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
			 ,"go","code","store_title","delete","link_title","url","page_title","page_description","site"

			 ]};

        this.table6 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource6
						     , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    :<?php echo $_SESSION['state']['family']['edit_pages']['nr']?> ,containers : 'paginator6', alpartysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info6'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    key: "<?php echo $_SESSION['state']['family']['edit_pages']['order']?>",
							     dir: "<?php echo $_SESSION['state']['family']['edit_pages']['order_dir']?>"
							 },
							 dynamicData : true
						     }
						     );
	    
	    this.table6.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table6.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table6.doBeforePaginatorChange = mydoBeforePaginatorChange;
        this.table6.table_id=tableid;
        this.table6.subscribe("renderEvent", myrenderEvent);

	    this.table6.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table6.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table6.subscribe("cellClickEvent", onCellClick);
		    
	    this.table6.filter={key:'<?php echo $_SESSION['state']['family']['edit_pages']['f_field']?>',value:'<?php echo $_SESSION['state']['family']['edit_pages']['f_value']?>'};


	  




	};
    });






function hide_add_product_dialog(){
    Dom.get('new_product_dialog').style.display='none';
    Dom.get('add_product').style.display='';
    Dom.get('save_new_product').style.display='none';
    Dom.get('cancel_add_product').style.display='none';
}
function show_add_product_dialog(){
    
    Dom.get('new_product_dialog').style.display='';
    Dom.get('add_product').style.display='none';

    Dom.get('save_new_product').style.display='';

    Dom.addClass('save_new_product','disabled');
    Dom.get('cancel_add_product').style.display='';
    Dom.get('new_code').focus();

}




function select_department(oArgs){
//alert('ss');

department_key=tables.table2.getRecord(oArgs.target).getData('key');

 dialog_family_list.hide();


        var request = 'ar_edit_assets.php?tipo=edit_family_department&key=' + 'department_key' + '&newvalue=' + department_key+ '&id=' + family_id
         //alert(request);

        YAHOO.util.Connect.asyncRequest('POST', request, {
                success: function(o) {
                        //alert(o.responseText);
                        var r = YAHOO.lang.JSON.parse(o.responseText);
                        if (r.state == 200) {

                                Dom.get('current_department_code').innerHTML=r.newdata['code'];




                        } else {


                                }
                                


                }
                


        });



}


function show_new_family_page_dialog(){

var number_sites=Dom.get('number_sites').value;
if(number_sites==0){
return;
}else if(number_sites==1){
new_family_page(Dom.get('site_key').value);
}else{
alert("todo")
}

}


function update_page_preview_snapshot(page_key){
  YAHOO.util.Connect.asyncRequest('POST','ar_edit_sites.php?tipo=update_page_preview_snapshot&id='+page_key,{
  success: function(o) {
   var r = YAHOO.lang.JSON.parse(o.responseText);
  }
  });
  }

function new_family_page(site_key){


var request='tipo=new_family_page&family_key='+Dom.get('family_key').value+'&site_key='+site_key
//alert(request)
		YAHOO.util.Connect.asyncRequest(
						'POST',
						'ar_edit_sites.php', {
						    success:function(o) {
						//alert(o.responseText)
							var r = YAHOO.lang.JSON.parse(o.responseText);
							if (r.state == 200) {
 var table=tables.table6;
 var datasource=tables.dataSource6;
 var request='';
 datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
 update_page_preview_snapshot(r.page_key);
 
								   //     window.location = "edit_family.php?id="+Dom.get('family_key').value
							    }else{
						
                                        alert(r.msg)								
								
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

function new_product_from_part(){

}
function new_product_from_scratch(){

}

function show_new_product_dialog(){
    Dom.setStyle('new_product_dialog','display','');
        Dom.setStyle('cancel_new_product','visibility','visible');
        Dom.setStyle('save_new_product','visibility','visible');
        Dom.addClass('save_new_product','disabled');

 Dom.setStyle(['show_new_product_dialog_button','import_new_product'],'display','none');
}

function save_new_product(){
save_new_general('product')
}

function cancel_new_product(){
 Dom.setStyle('new_product_dialog','display','none');
        Dom.setStyle('cancel_new_product','visibility','hidden');
        Dom.setStyle('save_new_product','visibility','hidden');
        Dom.addClass('save_new_product','disabled');

 Dom.setStyle(['show_new_product_dialog_button','import_new_product'],'display','');

cancel_new_general('product');


}


function init(){



  ids=['page_properties','page_html_head','page_header'];
 YAHOO.util.Event.addListener(ids, "click",change_edit_pages_view,{'table_id':6,'parent':'page'})

Event.addListener(['elements_discontinued','elements_nosale','elements_private','elements_sale','elements_historic'], "click",change_elements);

//dialog_new_product_choose = new YAHOO.widget.Dialog("dialog_new_product_choose", {context:["new_product_choose","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
//dialog_new_product_choose.render();
//Event.addListener("new_product_choose", "click", dialog_new_product_choose.show,dialog_new_product_choose , true);


     YAHOO.util.Event.addListener('delete_family', "click", delete_family);
        YAHOO.util.Event.addListener('cancel_delete_family', "click", cancel_delete_family);
        YAHOO.util.Event.addListener('save_delete_family', "click", save_delete_family);

 validate_scope_metadata={
     'product':{'type':'new','ar_file':'ar_edit_assets.php','key_name':'famiy_key','key':<?php echo$_REQUEST['id']?>}

    ,'family':{'type':'edit','ar_file':'ar_edit_assets.php','key_name':'id','key':<?php echo$_REQUEST['id']?>}
  
};
var product_code_validated=false;
if(Dom.get('product_code').value!=''){
product_code_validated=true;
}
 validate_scope_data={
 
 	'product':{
	'product_name':{'changed':false,'validated':false,'required':true,'group':1,'type':'item','dbname':'Product Name'
		,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Product Name')?>'}],'name':'product_name'
		,'ar':'find','ar_request':'ar_assets.php?tipo=is_product_name&store_key='+store_key+'&query='}
	
	,'product_code':{'changed':false,'validated':product_code_validated,'required':true,'group':1,'type':'item','dbname':'Product Code'
		 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Product Code')?>'}]
		 ,'name':'product_code','ar':'find','ar_request':'ar_assets.php?tipo=is_product_code&store_key='+store_key+'&query='}
	,'product_special_char':{'changed':false,'validated':false,'required':true,'group':1,'type':'item','dbname':'Product Special Characteristic'
		,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Special Characteristic')?>'}],'name':'product_special_char'
		,'ar':'find','ar_request':'ar_assets.php?tipo=is_product_special_char&family_key='+family_id+'&query='}

	,'product_units':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','dbname':'Product Units Per Case'
		,'validation':[{'regexp':"[\\d]+",'invalid_msg':'<?php echo _('Invalid Number')?>'}],'name':'product_units'
		,'ar':false,'ar_request':false}
		 
		,'product_price':{'changed':false,'validated':false,'required':true,'group':1,'type':'item','dbname':'Product Price'
		,'validation':[{'regexp':"[\\.\\d]+",'invalid_msg':'<?php echo _('Invalid Price')?>'}],'name':'product_price'
		,'ar':false,'ar_request':false}
		 	,'product_retail_price':{'changed':false,'validated':false,'required':false,'group':1,'type':'item','dbname':'Product RRP'
		,'validation':[{'regexp':"[\\.\\d]+",'invalid_msg':'<?php echo _('Invalid Price')?>'}],'name':'product_retail_price'
		,'ar':false,'ar_request':false}
		 	 
		 
		 }
 
    ,'family':{
	'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item'
		,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Family Name')?>'}],'name':'name'
		,'ar':'find','ar_request':'ar_assets.php?tipo=is_family_name&store_key='+store_key+'&query='}
	,'code':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
		 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Family Code')?>'}]
		 ,'name':'code','ar':'find','ar_request':'ar_assets.php?tipo=is_family_code&store_key='+store_key+'&query='}
	,'special_char':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
			 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Family Special Characteristic')?>'}]
			 ,'name':'special_char','ar':'find','ar_request':'ar_assets.php?tipo=is_family_special_char&store_key='+store_key+'&query='}
	,'description':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','validation':[],'name':'description','ar':false}
    }
   


};



 init_search('products_store');

    YAHOO.util.Event.addListener('new_family_page', "click", show_new_family_page_dialog);




 	YAHOO.util.Event.on('uploadButton', 'click', upload_image);

 
  

    
    var ids = ["details","products","discounts","pictures","web"]; 
    YAHOO.util.Event.addListener(ids, "click", change_block);
    
    // YAHOO.util.Event.addListener('add_product', "click", show_add_product_dialog);
    //YAHOO.util.Event.addListener('save_new_product', "click",save_new_product);
    //YAHOO.util.Event.addListener('cancel_add_product', "click", cancel_add_product);


ids=['view_name','view_price','view_state'];
YAHOO.util.Event.addListener(ids, "click",change_view)





 YAHOO.util.Event.addListener('cancel_new_product', "click", cancel_new_product);
    YAHOO.util.Event.addListener('save_new_product', "click", save_new_product);


 YAHOO.util.Event.addListener('reset_edit_family', "click", reset_edit_family);
    YAHOO.util.Event.addListener('save_edit_family', "click", save_edit_family);
   
     

var product_code_oACDS = new YAHOO.util.FunctionDataSource(validate_product_code);
    product_code_oACDS.queryMatchContains = true;
    var product_code_oAutoComp = new YAHOO.widget.AutoComplete("product_code","product_code_Container", product_code_oACDS);
    product_code_oAutoComp.minQueryLength = 0; 
    product_code_oAutoComp.queryDelay = 0.1;


var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_product_name);
    product_name_oACDS.queryMatchContains = true;
    var product_name_oAutoComp = new YAHOO.widget.AutoComplete("product_name","product_name_Container", product_name_oACDS);
    product_name_oAutoComp.minQueryLength = 0; 
    product_name_oAutoComp.queryDelay = 0.1;

var product_special_char_oACDS = new YAHOO.util.FunctionDataSource(validate_product_special_char);
    product_special_char_oACDS.queryMatchContains = true;
    var product_special_char_oAutoComp = new YAHOO.widget.AutoComplete("product_special_char","product_special_char_Container", product_special_char_oACDS);
    product_special_char_oAutoComp.minQueryLength = 0; 
    product_special_char_oAutoComp.queryDelay = 0.1;

var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_product_units);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("product_units","product_units_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0; 
    product_units_oAutoComp.queryDelay = 0.1;

var product_price_oACDS = new YAHOO.util.FunctionDataSource(validate_product_price);
    product_price_oACDS.queryMatchContains = true;
    var product_price_oAutoComp = new YAHOO.widget.AutoComplete("product_price","product_price_Container", product_price_oACDS);
    product_price_oAutoComp.minQueryLength = 0; 
    product_price_oAutoComp.queryDelay = 0.1;
    
    var product_retail_price_oACDS = new YAHOO.util.FunctionDataSource(validate_product_retail_price);
    product_retail_price_oACDS.queryMatchContains = true;
    var product_retail_price_oAutoComp = new YAHOO.widget.AutoComplete("product_retail_price","product_retail_price_Container", product_retail_price_oACDS);
    product_retail_price_oAutoComp.minQueryLength = 0; 
    product_retail_price_oAutoComp.queryDelay = 0.1;
    

var family_code_oACDS = new YAHOO.util.FunctionDataSource(validate_code);
    family_code_oACDS.queryMatchContains = true;
    var family_code_oAutoComp = new YAHOO.widget.AutoComplete("code","code_Container", family_code_oACDS);
    family_code_oAutoComp.minQueryLength = 0; 
    family_code_oAutoComp.queryDelay = 0.1;
    
     var family_name_oACDS = new YAHOO.util.FunctionDataSource(validate_name);
    family_name_oACDS.queryMatchContains = true;
    var family_name_oAutoComp = new YAHOO.widget.AutoComplete("name","name_Container", family_name_oACDS);
    family_name_oAutoComp.minQueryLength = 0; 
    family_name_oAutoComp.queryDelay = 0.1;

   var family_special_char_oACDS = new YAHOO.util.FunctionDataSource(validate_special_char);
    family_special_char_oACDS.queryMatchContains = true;
    var family_special_char_oAutoComp = new YAHOO.widget.AutoComplete("special_char","special_char_Container", family_special_char_oACDS);
    family_special_char_oAutoComp.minQueryLength = 0; 
    family_special_char_oAutoComp.queryDelay = 0.1;

    var family_description_oACDS = new YAHOO.util.FunctionDataSource(validate_description);
    family_description_oACDS.queryMatchContains = true;
    var family_description_oAutoComp = new YAHOO.widget.AutoComplete("description","description_Container", family_description_oACDS);
    family_description_oAutoComp.minQueryLength = 0; 
    family_description_oAutoComp.queryDelay = 0.1;


  

 var oACDS2 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS2.queryMatchContains = true;
 oACDS2.table_id=2;
 var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input2","f_container2", oACDS2);
 oAutoComp2.minQueryLength = 0; 
YAHOO.util.Event.addListener('clean_table_filter_show2', "click",show_filter,2);
 YAHOO.util.Event.addListener('clean_table_filter_hide2', "click",hide_filter,2);
    
    
       
 
	
	dialog_family_list = new YAHOO.widget.Dialog("dialog_family_list", {context:["edit_family_department","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_family_list.render();
	
		   

	
    Event.addListener("edit_family_department", "click", dialog_family_list.show,dialog_family_list , true);
 
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



