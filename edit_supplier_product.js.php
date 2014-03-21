<?php  include_once('common.php');

$money_regex="^[^\\\\d\\\.\\\,]{0,3}(\\\\d{1,3}(\\\,\\\\d{3})*|(\\\\d+))(\\\.\\\\d{2})?$";
print 'var money_regex="'.$money_regex.'";';
$number_regex="^(\\\\d{1,3}(\\\,\\\\d{3})*|(\\\\d+))(\\\.\\\\d{1,})?$";
print 'var number_regex="'.$number_regex.'";';

//$parts=preg_split('/\,/',$_REQUEST['parts']);

//$_parts='';
//foreach($parts as $part){
//    $_parts.="'sku$part':{sku : $part, new:false, deleted:false } ,";
//}
//$_parts=preg_replace("/\,$/","",$_parts);
//print "\nvar part_list={ $_parts };";


 ?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;



function post_item_updated_actions(branch,r){
key=r.key;
newvalue=r.newvalue;
 if(key=='name')
     Dom.get('title_name').innerHTML=newvalue;
 
 else if(key=='code')
     Dom.get('title_code').innerHTML=newvalue;

 
 var table=tables.table0;
 var datasource=tables.dataSource0;
 var request='';
 datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
 
}

function validate_product_name(query){

 validate_general('product_description','name',unescape(query));
}

function validate_product_code(query){

 validate_general('product_description','code',unescape(query));
}

function validate_product_url(query){

 validate_general('product_description','url',unescape(query));
}


function validate_product_unit_net_weight(query){
 validate_general('product_description','unit_net_weight',unescape(query));
}
function validate_product_unit_gross_weight(query){
 validate_general('product_description','unit_gross_weight',unescape(query));
}
function validate_product_unit_gross_volume(query){
 validate_general('product_description','unit_gross_volume',unescape(query));
}
function validate_product_unit_mov(query){
 validate_general('product_description','unit_mov',unescape(query));
}
function validate_product_case_mov(query){
 validate_general('product_description','case_mov',unescape(query));
}
function validate_product_case_gross_weight(query){
 validate_general('product_description','case_gross_weight',unescape(query));
}
function change_block(e){

	
	var ids = ["description","pictures","prices","parts"]; 
	Dom.removeClass(ids,'selected');
	Dom.addClass(this, 'selected');
	
		var ids = ["d_description","d_pictures","d_prices","d_parts"]; 
Dom.setStyle(ids,'display','none')
	Dom.setStyle('d_'+this.id,'display','')

	
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=supplier_product-editing&value='+this.id,{} );
}
function save_edit_description(){
    save_edit_general('product_description');
}
function reset_edit_description(){
    reset_edit_general('product_description')
}
function save_edit_price(){
    save_edit_general('product_price');
}
function reset_edit_price(){
    reset_edit_general('product_price')
}
function init(){

var supplier_key=Dom.get('supplier_key').value
var product_pid=Dom.get('pid').value
var scope_key=Dom.get('pid').value
var scope='product_supplier';
var validate_scope_data=
{
    'product_description':{
	'code':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_Code',
	'validation':[{'regexp':"[a-z\\d]+",
	'invalid_msg':'<?php echo _('Invalid Product Code')?>'}],
	'ar':'find',
	'ar_request':'ar_suppliers.php?tipo=is_supplier_product_code&supplier_key='+supplier_key+'&query='
	
	}
    	,'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_Name','validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Product Name')?>'}],'ar':'find','ar_request':'ar_suppliers.php?tipo=is_supplier_product_name&supplier_key='+supplier_key+'&query='}
    	,'url':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Product_URL','ar':false,'validation':[{'regexp':regexp_valid_www,'invalid_msg':'<?php echo _('Invalid URL')?>'}]}
    	,'unit_net_weight':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Product_Unit_Weight','ar':false,'validation':[{'numeric':'positive','invalid_msg':'<?php echo _('Invalid Weight')?>'}]}
    	,'unit_gross_weight':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Product_Unit_Gross_Weight','ar':false,'validation':[{'numeric':'positive','invalid_msg':'<?php echo _('Invalid Weight')?>'}]}
	    ,'unit_gross_volume':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Product_Unit_Gross_Volume','ar':false,'validation':[{'numeric':'positive','invalid_msg':'<?php echo _('Invalid Volume')?>'}]}
    	,'unit_mov':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Product_Unit_MOV','ar':false,'validation':[{'numeric':'positive','invalid_msg':'<?php echo _('Invalid Volume')?>'}]}
    	,'case_gross_weight':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Product_Case_Gross_Weight','ar':false,'validation':[{'numeric':'positive','invalid_msg':'<?php echo _('Invalid Weight')?>'}]}
    	,'case_mov':{'changed':false,'validated':true,'required':false,'group':1,'type':'item','name':'Product_Case_MOV','ar':false,'validation':[{'numeric':'positive','invalid_msg':'<?php echo _('Invalid Volume')?>'}]}
    	,'unit_type':{'changed':false,'validated':true,'required':false,'group':1,'type':'select','name':'Product_Units_Type','ar':false,'validation':false}
    	,'unit_packing_type':{'changed':false,'validated':true,'required':false,'group':1,'type':'select','name':'Product_Unit_Package_Type','ar':false,'validation':false}

}
    , 'product_price':{
	'price':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','name':'Product_Price','ar':false,'validation':[{'regexp':money_regex,'invalid_msg':'<?php echo _('Invalid Price')?>'}]}
    }
	
 

    };
var validate_scope_metadata={
    'product_description':{'type':'edit','ar_file':'ar_edit_suppliers.php','key_name':'pid','key':product_pid}
    ,'product_price':{'type':'edit','ar_file':'ar_edit_suppliers.php','key_name':'pid','key':product_pid}
  

};





Editor_add_part = new YAHOO.widget.Dialog("Editor_add_part", {close:false,visible:false,underlay: "none",draggable:false});
    Editor_add_part.render();

	

    var ids = ["description","pictures","prices","parts"]; 
    Event.addListener(ids, "click", change_block);
    
    Event.addListener('save_edit_product_description', "click", save_edit_description);
    Event.addListener('reset_edit_product_description', "click", reset_edit_description);
    
   // Event.addListener('save_edit_product_price', "click", save_edit_price);
   // Event.addListener('reset_edit_product_price', "click", reset_edit_price);

   // Event.addListener('save_edit_product_weight', "click", save_edit_weight);
  //  Event.addListener('reset_edit_product_weight', "click", reset_edit_weight);


  
    var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_product_name);
    product_name_oACDS.queryMatchContains = true;
    var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Product_Name","Product_Name_Container", product_name_oACDS);
    product_name_oAutoComp.minQueryLength = 0; 
    product_name_oAutoComp.queryDelay = 0.1;
	
	var product_code_oACDS = new YAHOO.util.FunctionDataSource(validate_product_code);
    product_code_oACDS.queryMatchContains = true;
    var product_code_oAutoComp = new YAHOO.widget.AutoComplete("Product_Code","Product_Code_Container", product_code_oACDS);
    product_code_oAutoComp.minQueryLength = 0; 
    product_code_oAutoComp.queryDelay = 0.1;

    var product_url_oACDS = new YAHOO.util.FunctionDataSource(validate_product_url);
    product_url_oACDS.queryMatchContains = true;
    var product_url_oAutoComp = new YAHOO.widget.AutoComplete("Product_URL","Product_URL_Container", product_url_oACDS);
    product_url_oAutoComp.minQueryLength = 0; 
    product_url_oAutoComp.queryDelay = 0.1;

    var product_unit_w_oACDS = new YAHOO.util.FunctionDataSource(validate_product_unit_net_weight);
    product_unit_w_oACDS.queryMatchContains = true;
    var product_unit_w_oAutoComp = new YAHOO.widget.AutoComplete("Product_Unit_Weight","Product_Unit_Weight_Container", product_unit_w_oACDS);
    product_unit_w_oAutoComp.minQueryLength = 0; 
    product_unit_w_oAutoComp.queryDelay = 0.1;


    var product_unit_gross_w_oACDS = new YAHOO.util.FunctionDataSource(validate_product_unit_gross_weight);
    product_unit_gross_w_oACDS.queryMatchContains = true;
    var product_unit_gross_w_oAutoComp = new YAHOO.widget.AutoComplete("Product_Unit_Gross_Weight","Product_Unit_Gross_Weight_Container", product_unit_gross_w_oACDS);
    product_unit_gross_w_oAutoComp.minQueryLength = 0; 
    product_unit_gross_w_oAutoComp.queryDelay = 0.1;


 var product_unit_v_oACDS = new YAHOO.util.FunctionDataSource(validate_product_unit_gross_volume);
    product_unit_v_oACDS.queryMatchContains = true;
    var product_unit_v_oAutoComp = new YAHOO.widget.AutoComplete("Product_Unit_Gross_Volume","Product_Unit_Gross_Volume_Container", product_unit_v_oACDS);
    product_unit_v_oAutoComp.minQueryLength = 0; 
    product_unit_v_oAutoComp.queryDelay = 0.1;


    var product_unit_mov_oACDS = new YAHOO.util.FunctionDataSource(validate_product_unit_mov);
    product_unit_mov_oACDS.queryMatchContains = true;
    var product_unit_mov_oAutoComp = new YAHOO.widget.AutoComplete("Product_Unit_MOV","Product_Unit_MOV_Container", product_unit_mov_oACDS);
    product_unit_mov_oAutoComp.minQueryLength = 0; 
    product_unit_mov_oAutoComp.queryDelay = 0.1;

var product_case_gross_w_oACDS = new YAHOO.util.FunctionDataSource(validate_product_case_gross_weight);
    product_case_gross_w_oACDS.queryMatchContains = true;
    var product_case_gross_w_oAutoComp = new YAHOO.widget.AutoComplete("Product_Case_Gross_Weight","Product_Case_Gross_Weight_Container", product_case_gross_w_oACDS);
    product_case_gross_w_oAutoComp.minQueryLength = 0; 
    product_case_gross_w_oAutoComp.queryDelay = 0.1;
 var product_case_mov_oACDS = new YAHOO.util.FunctionDataSource(validate_product_case_mov);
    product_case_mov_oACDS.queryMatchContains = true;
    var product_case_mov_oAutoComp = new YAHOO.widget.AutoComplete("Product_Case_MOV","Product_Case_MOV_Container", product_case_mov_oACDS);
    product_case_mov_oAutoComp.minQueryLength = 0; 
    product_case_mov_oAutoComp.queryDelay = 0.1;
}

YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {

 var tableid=0; 
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				       {key:"date",label:"<?php echo _('Date')?>", width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"author",label:"<?php echo _('Author')?>", width:70,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"abstract", label:"<?php echo _('Description')?>", width:370,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ];
	   

	    this.dataSource0 = new YAHOO.util.DataSource("ar_history.php?tipo=history&type=supplier_product&tableid=0");
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
			 "id"
			 ,"note"
			 ,'author','date','tipo','abstract','details'
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, CustomersColumnDefs,
						     this.dataSource0
						     , {

							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 ,paginator : new YAHOO.widget.Paginator({
								 rowsPerPage    : <?php echo$_SESSION['state']['supplier_product']['history']['nr']?>,containers : 'paginator0', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    Key: "<?php echo$_SESSION['state']['supplier_product']['history']['order']?>",
							     dir: "<?php echo$_SESSION['state']['supplier_product']['history']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	    this.table0.filter={key:'<?php echo$_SESSION['state']['supplier_product']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['supplier_product']['history']['f_value']?>'};


};
    });



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