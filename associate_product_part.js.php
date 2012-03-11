<?php
  //@author Raul Perusquia <rulovico@gmail.com>
  //Copyright (c) 2009 LW
include_once('common.php');
//include_once('class.Contact.php');
//include_once('class.Company.php');
include_once('class.Family.php');
?>

var validate_scope_data;
	

function validate_product_code(query){ validate_general('product','product_code',unescape(query));}
function validate_product_name(query){ validate_general('product','product_name',unescape(query));}
function validate_product_description(query){ validate_general('product','product_description',unescape(query));}
function validate_special_characteristics(query){ validate_general('product','special_characteristics',unescape(query));}
function validate_product_weight(query){ validate_general('product','product_weight',unescape(query));}
function validate_product_rrp(query){ validate_general('product','product_rrp',unescape(query));}
function validate_product_units(query){ validate_general('product','product_units',unescape(query));}
function validate_product_price(query){ validate_general('product','product_price',unescape(query));}


function view_store_list(e){

	region1 = Dom.getRegion(e); 
	region2 = Dom.getRegion('dialog_store_list'); 

	var pos =[region1.right-region2.width-20,region1.bottom]

	Dom.setXY('dialog_store_list', pos);

	dialog_store_list.show();
}

function view_family_list(e){

	region1 = Dom.getRegion(e); 
	region2 = Dom.getRegion('dialog_family_list'); 

	var pos =[region1.right-region2.width-20,region1.bottom]

	Dom.setXY('dialog_family_list', pos);

	dialog_family_list.show();
}

function select_part(oArgs){
var part_code=tables.table1.getRecord(oArgs.target).getData('formated_sku');
var part_key=tables.table1.getRecord(oArgs.target).getData('sku');


Dom.get('part_key').value=part_key;
Dom.get('part_code').value=part_code;


validate_scope_data['product']['part_key'].validated=true;
validate_scope_metadata['product'].key=Dom.get('part_key').value;
validate_scope_data['product']['product_code'].validated=true;
dialog_family_list.hide();

}


function select_store(oArgs){


var store_code=tables.table0.getRecord(oArgs.target).getData('code');
var store_key=tables.table0.getRecord(oArgs.target).getData('key');

Dom.get('store_key').value=store_key;
Dom.get('store_code').value=store_code;

validate_scope_data['product']['product_code'].ar_request='ar_assets.php?tipo=is_product_code&store_key='+Dom.get('store_key').value+'&query=';
validate_scope_data['product']['product_code'].validated=true;
dialog_store_list.hide();
}


YAHOO.util.Event.addListener(window, "load", function() {
   tables = new function() {


var tableid=0; 
	    var tableDivEL="table"+tableid;

	   
	    var ColumnDefs = [
			 {key:"key", label:"",width:100,hidden:true}
                    ,{key:"code", label:"<?php echo _('Code')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"name", label:"<?php echo _('Name')?>",width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
						
			];
		this.dataSource0 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=store_list&tableid="+tableid+"&nr=20&sf=0");
//alert("ar_quick_tables.php?tipo=store_list&tableid="+tableid+"&nr=20&sf=0");
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    	    this.dataSource0.table_id=tableid;

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
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			 "code",'name','key'
			 ]};

	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator0', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "code",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table0.subscribe("cellClickEvent", this.table0.onEventShowCellEditor);

 this.table0.subscribe("rowMouseoverEvent", this.table0.onEventHighlightRow);
       this.table0.subscribe("rowMouseoutEvent", this.table0.onEventUnhighlightRow);
      this.table0.subscribe("rowClickEvent", select_store);
        this.table0.table_id=tableid;
           this.table0.subscribe("renderEvent", myrenderEvent);


	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.filter={key:'code',value:''};	    





var tableid=1;
		      var tableDivEL="table"+tableid;
		      
		      var ColumnDefs = [
{key:"sku", label:"",width:100,hidden:true}
		      		,{key:"formated_sku", label:"SKU",width:60, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"description", label:"<?php echo _('Description')?>",width:200, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			     	,{key:"used_in", label:"<?php echo _('Used In')?>",width:140, sortable:false,className:"aleft"}
			     	,{key:"status", label:"",width:70, sortable:false,className:"aleft"}
                   
					];
		    
		      
		      this.dataSource1 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=part_list&tableid=1");
		      
		      
		      
		      
	
		      
		      
		      
		      
		      this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
		      this.dataSource1.connXhrMode = "queueRequests";
		      	    this.dataSource1.table_id=tableid;

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
				  "sku","description","used_in","status","formated_sku"
				   ]};
		      
		    this.table1 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource1
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator1', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								   
								   ,sortedBy : {
								      key: "formated_sku",
								       dir: ""
								   }
								   ,dynamicData : true
								 
							       }
							       );
		      this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
		      this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
		      this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
                   
                   this.table1.subscribe("rowMouseoverEvent", this.table1.onEventHighlightRow);
       this.table1.subscribe("rowMouseoutEvent", this.table1.onEventUnhighlightRow);
      this.table1.subscribe("rowClickEvent", select_part);
     

                   
	    this.table1.filter={key:'used_in',value:''};

	};
    });




function radio_changed_staff(o, select_id) {
    parent=o.parentNode;
    Dom.removeClass(parent.getAttribute('prefix')+parent.getAttribute('value'),'selected');

    Dom.addClass(o,'selected');


    parent.setAttribute('value',o.getAttribute('name'));
validate_scope_data['staff'][select_id].changed=true;
validate_scope_data['staff'][select_id].validated=true;

validate_scope_new('staff')
Dom.get(select_id).value=o.getAttribute('name');

}

function reset_new_staff(){
	reset_edit_general('staff')
}



function save_new_product(){
 save_new_general('product');
}

function post_new_create_actions(branch,r){
	window.location.href='product.php?pid='+r.object_key;
}




function init(){

    init_search('products_store');

	
validate_scope_data=
{

    'product':{
	'product_code':{'changed':false,'validated':false,'required':true,'group':1,'type':'item'
	    ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Product Code')?>'}],'name':'product_code'
	    ,'ar':'find','ar_request':'ar_assets.php?tipo=is_product_code&store_key='+Dom.get('store_key').value+'&query=', 'dbname':'Product Code'}
	,'part_key':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'family_key','ar':false,'dbname':'Product Family Key', 'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Units per Case'}]}
	,'store_key':{'changed':true,'validated':true,'required':true,'dbname':'Product Store Key','group':1,'type':'item','name':'store_key','ar':false,'validation':[{'regexp':"[\\d]+",'invalid_msg':'Invalid Weight'}]}
,'product_name':{'changed':true,'validated':true,'required':true,'dbname':'Product Name','group':1,'type':'item','name':'product_name','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Product Name'}]}
,'product_description':{'changed':true,'validated':false,'required':false,'dbname':'Product Description','group':1,'type':'item','name':'product_description','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Product Description'}]}
,'special_characteristics':{'changed':true,'validated':true,'required':false,'dbname':'Product Special Characteristic','group':1,'type':'item','name':'special_characteristics','ar':false,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'Invalid Product Characteristics'}]}
,'product_weight':{'changed':true,'validated':true,'required':false,'dbname':'Product Net Weight','group':1,'type':'item','name':'product_weight','ar':false,'validation':[{'regexp':"[\\d\\.]+",'invalid_msg':'Invalid Quantity'}]}
,'product_units':{'changed':true,'validated':false,'required':true,'dbname':'Product Units','group':1,'type':'item','name':'product_units','ar':false,'validation':[{'regexp':"[\\d\\.]+",'invalid_msg':'Invalid Number'}]}
,'product_price':{'changed':true,'validated':false,'required':true,'dbname':'Product Price','group':1,'type':'item','name':'product_price','ar':false,'validation':[{'regexp':"[\\d\\.]+",'invalid_msg':'Invalid Price'}]}
,'product_rrp':{'changed':true,'validated':true,'required':false,'dbname':'Product RRP','group':1,'type':'item','name':'product_rrp','ar':false,'validation':[{'regexp':"[\\d\\.]?",'invalid_msg':'Invalid Quantity'}]}


	}
};

	
validate_scope_metadata={
    'product':{'type':'new','ar_file':'ar_edit_assets.php','key_name':'family_key', 'key':Dom.get('family_key').value}
    

};





    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_product_code);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("product_code","product_code_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0; 
    product_units_oAutoComp.queryDelay = 0.1;

    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_product_name);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("product_name","product_name_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0; 
    product_units_oAutoComp.queryDelay = 0.1;

    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_product_description);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("product_description","product_description_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0; 
    product_units_oAutoComp.queryDelay = 0.1;

    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_special_characteristics);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("special_characteristics","special_characteristics_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0; 
    product_units_oAutoComp.queryDelay = 0.1;

    var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_product_weight);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("product_weight","product_weight_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0; 
    product_units_oAutoComp.queryDelay = 0.1;
    
     var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_product_price);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("product_price","product_price_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0; 
    product_units_oAutoComp.queryDelay = 0.1;
    
     var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_product_units);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("product_units","product_units_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0; 
    product_units_oAutoComp.queryDelay = 0.1;
    
       var product_units_oACDS = new YAHOO.util.FunctionDataSource(validate_product_rrp);
    product_units_oACDS.queryMatchContains = true;
    var product_units_oAutoComp = new YAHOO.widget.AutoComplete("product_rrp","product_rrp_Container", product_units_oACDS);
    product_units_oAutoComp.minQueryLength = 0; 
    product_units_oAutoComp.queryDelay = 0.1;
    

 //  YAHOO.util.Event.addListener('reset_new_part', "click",reset_new_part)
   YAHOO.util.Event.addListener('save_new_product', "click",save_new_product)

dialog_store_list = new YAHOO.widget.Dialog("dialog_store_list", {context:["store_list","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_store_list.render();

dialog_family_list = new YAHOO.widget.Dialog("dialog_family_list", {context:["family_list","tr","tl"]  ,visible : false,close:true,underlay: "none",draggable:false});
    dialog_family_list.render();

}


YAHOO.util.Event.onDOMReady(init);
