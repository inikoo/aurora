<?php
include_once('common.php');
include_once('class.Supplier.php');

$unit_list='';
foreach(getEnumVals('`Supplier Product Dimension`','Supplier Product Unit Type') as $value){
    $unit_list.=",'".$value."'";
}
$unit_list=preg_replace('/^,/','',$unit_list);
print "var supplier_id='".$_SESSION['state']['supplier']['id']."';";
print "var units_list=[$unit_list];";


$supplier=new Supplier($_SESSION['state']['supplier']['id']);
$addresses=$supplier->get_address_keys();



//$addresses[$customer->data['Customer Billing Address Key']]=$customer->data['Customer Billing Address Key'];
$address_data="\n";
//$address_data.=sprintf('0:{"key":0,"country":"","country_code":"UNK","country_d1":"","country_d2":"","town":"","postal_code":"","town_d1":"","town_d2":"","fuzzy":"","street":"","building":"","internal":"","type":["Office"],"description":"","function":["Contact"] } ' );
 $address_data.="\n";
foreach($addresses as $index){
   // $address->set_scope('Customer',$cust);
    
    $address=new Address($index);


    $type="[";
    foreach($address->get('Type') as $_type){
	$type.=prepare_mysql($_type,false).",";
    }
    $type.="]";
    $type=preg_replace('/,]$/',']',$type);
    
    $function="[";
    foreach($address->get('Function') as $value){
	$function.=prepare_mysql($value,false).",";
    }
    $function.="]";
    $function=preg_replace('/,]$/',']',$function);
    

  $address_data.="\n".sprintf('Address_Data[%d]={"key":%d,"country":%s,"country_code":%s,"country_d1":%s,"country_d2":%s,"town":%s,"postal_code":%s,"town_d1":%s,"town_d2":%s,"fuzzy":%s,"street":%s,"building":%s,"internal":%s,"type":%s,"description":%s,"function":%s}; ',
			
			 $address->id
			 ,$address->id
			 ,prepare_mysql($address->data['Address Country Name'],false)
			 ,prepare_mysql($address->data['Address Country Code'],false)
			 ,prepare_mysql($address->data['Address Country First Division'],false)
			 ,prepare_mysql($address->data['Address Country Second Division'],false)
			 ,prepare_mysql($address->data['Address Town'],false)
			 ,prepare_mysql($address->data['Address Postal Code'],false)
			 ,prepare_mysql($address->data['Address Town First Division'],false)
			 ,prepare_mysql($address->data['Address Town Second Division'],false)
			 ,prepare_mysql($address->data['Address Fuzzy'],false)
			 ,prepare_mysql($address->display('street',false),false)
			 ,prepare_mysql($address->data['Address Building'],false)
			 ,prepare_mysql($address->data['Address Internal'],false)
			 ,$type
			 ,prepare_mysql($address->data['Address Description'],false)
			 ,$function

			 );
  $address_data.="\n";




}
print $address_data;








?>
var Dom   = YAHOO.util.Dom;

var editing='<?php echo $_SESSION['state']['supplier']['edit']?>';

var validate_scope_data={
    'code':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','regexp':"[a-z\\d]+",'name':'Supplier_Code'}
    ,'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','regexp':"[a-z\\d]+",'name':'Supplier_Name'}

};

var validate_scope_data=
{
    'supplier':{
    	'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item'
		,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Supplier Name')?>'}],'name':'Supplier_Name'
		,'ar':false}
	,'code':{'changed':false,'validated':true,'required':false,'group':1,'type':'item'
		 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Supplier Code')?>'}]
		 ,'name':'Supplier_Code','ar':'find','ar_request':'ar_suppliers.php?tipo=is_supplier_code&query='}
  },
  'product':{
    'code':{'changed':false,'validated':false,'required':true,'group':1,'type':'item'
		 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Product Code')?>'}]
		 ,'name':'Product_Code','ar':'find','ar_request':'ar_suppliers.php?tipo=is_product_code&supplier_key=<?php echo $_SESSION['state']['supplier']['id']?>&query='},
   'name':{'changed':false,'validated':false,'required':true,'group':1,'type':'item'
		 ,'validation':[{'regexp':"[a-z\\d]+",'invalid_msg':'<?php echo _('Invalid Product Code')?>'}]
		 ,'name':'Product_Name','ar':'find','ar_request':'ar_suppliers.php?tipo=is_product_name&supplier_key=<?php echo $_SESSION['state']['supplier']['id']?>&query='},
	'description':{'changed':false,'validated':false,'required':true,'group':1,'type':'item'
		 ,'validation':false
		 ,'name':'Product_Description','ar':false,'ar_request':false},
	 'unit':{'default':'ea', 'changed':false,'validated':true,'required':true,'group':1,'type':'select'
		 ,'validation':false
		 ,'name':'Product_Unit','ar':false,'ar_request':false},	 
	'units_per_case':{'default':1,'changed':false,'validated':false,'required':true,'group':1,'type':'item'
		 ,'validation':[{'numeric':"positive integer",'invalid_msg':'<?php echo _('Invalid Product Code')?>'}]
		 ,'name':'Product_Code','ar':'find','ar_request':'ar_suppliers.php?tipo=is_product_code&supplier_key=<?php echo $_SESSION['state']['supplier']['id']?>&query='},
	'price':{'default':'ea', 'changed':false,'validated':true,'required':true,'group':1,'type':'select'
		 ,'validation':false
		 ,'name':'Product_Unit','ar':false,'ar_request':false},	 	 
  }
  
};


var validate_scope_metadata={
'supplier':{'type':'edit','ar_file':'ar_edit_suppliers.php','key_name':'supplier_key','key':<?php echo $_SESSION['state']['supplier']['id']?>},
'product':{'type':'new','ar_file':'ar_edit_suppliers.php','key_name':'supplier_key','key':<?php echo $_SESSION['state']['supplier']['id']?>}

};

function show_new_product_dialog(){
    Dom.setStyle('new_product_dialog','display','');
        Dom.setStyle('cancel_new_product','visibility','visible');
        Dom.setStyle('save_new_product','visibility','visible');
        Dom.addClass('save_new_product','disabled');

 Dom.setStyle('show_new_product_dialog_button','display','none');
}

function validate_product_units_per_case(query){
validate_general('supplier','units_per_case',query);

}

function validate_supplier_code(query){
validate_general('supplier','code',query);
}
function validate_supplier_name(query){

validate_general('supplier','name',query);
}

function validate_product_code(query){
validate_general('product','code',query);
}
function validate_product_name(query){

validate_general('product','name',query);
}

function post_item_updated_actions(branch,key,newvalue){

	var table_id=1


    var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];

  
    var request='&tableid='+table_id+'&sf=0';
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);  
}



YAHOO.util.Event.addListener(window, "load", function() {
	tables = new function() {


		 var tableid=0;
		    var tableDivEL="table"+tableid;
		var ColumnDefs = [
						    {key:"go",label:'',width:20,}

				  ,{key:"sph_key", label:"<?php echo _('Key')?>", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				  ,{key:"code", label:"<?php echo _('Code')?>",  width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product_supplier'}
				  ,{key:"name", label:"<?php echo _('Name')?>",<?php echo($_SESSION['state']['supplier']['products']['view']=='product_general'?'':'hidden:true,')?>width:280, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product_supplier'}
				//  ,{key:"usedin", label:"<?php echo _('Used In')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				  ,{key:"unit_type", label:"<?php echo _('Unit')?>",<?php echo($_SESSION['state']['supplier']['products']['view']=='product_general'?'':'hidden:true,')?>width:50, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.DropdownCellEditor({asyncSubmitter: CellEdit,dropdownOptions:units_list,disableBtns:true}),object:'product_supplier'}
				
				,{key:"units", className:"aright",label:"<?php echo _('U/Case')?>",<?php echo($_SESSION['state']['supplier']['products']['view']=='product_general'?'':'hidden:true,')?>width:50, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product_supplier'}
  ,{key:"cost", label:"<?php echo _('Cost/u')?>",<?php echo($_SESSION['state']['supplier']['products']['view']=='product_general'?'':'hidden:true,')?>width:80, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product_supplier'}
			,{key:"delete", label:"", width:20,sortable:false,className:"aleft",action:'delete',object:'supplier_product'}
				    ,{key:"delete_type", label:"",hidden:true,isTypeKey:true}


				  ];

		this.dataSource0 = new YAHOO.util.DataSource("ar_edit_suppliers.php?tipo=supplier_products&tableid="+tableid);

   this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
		    this.dataSource0.connXhrMode = "queueRequests";
		    this.dataSource0.responseSchema = {
			resultsList: "resultset.data", 
			metaFields: {
			    rowsPerPage:"resultset.records_perpage",
			    rtext:"resultset.rtext",
			    sort_key:"resultset.sort_key",
			    sort_dir:"resultset.sort_dir",
			    tableid:"resultset.tableid",
			    filter_msg:"resultset.filter_msg",
			    totalRecords: "resultset.total_records"
			},
			
			fields: [
				 "id","code","name","cost","usedin","units","unit_type","sph_key","delete","delete_type",'go'
				 ]};
	    
		    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
							     this.dataSource0, {
								 //draggableColumns:true,
								 renderLoopSize: 50,generateRequest : myRequestBuilder
								 ,paginator : new YAHOO.widget.Paginator({
									 rowsPerPage:<?php echo$_SESSION['state']['supplier']['products']['nr']?>,containers : 'paginator0', 
									 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									 previousPageLinkLabel : "<",
									 nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
									 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
								     })
								 
								 ,sortedBy : {
								    Key: "<?php echo$_SESSION['state']['supplier']['products']['order']?>",
								     dir: "<?php echo$_SESSION['state']['supplier']['products']['order_dir']?>"
								 }
								 ,dynamicData : true
								 
							     }
							     );
		this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
		this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
		this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table0.filter={key:'<?php echo$_SESSION['state']['supplier']['products']['f_field']?>',value:'<?php echo$_SESSION['state']['supplier']['products']['f_value']?>'};
		this.table0.view='<?php echo$_SESSION['state']['supplier']['products']['view']?>';

		this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
		this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
		this.table0.subscribe("cellClickEvent", onCellClick);

var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    var CustomersColumnDefs = [
				       {key:"date",label:"<?php echo _('Date')?>", width:200,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"author",label:"<?php echo _('Author')?>", width:70,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       //     ,{key:"tipo", label:"<?php echo _('Type')?>", width:90,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       //,{key:"diff_qty",label:"<?php echo _('Qty')?>", width:90,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"abstract", label:"<?php echo _('Description')?>", width:370,sortable:true,formatter:this.customer_name,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ];
	    //?tipo=customers&tid=0"
	    
	    this.dataSource1 = new YAHOO.util.DataSource("ar_history.php?tipo=history&type=supplier&tableid=1");
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
								 rowsPerPage    : <?php echo$_SESSION['state']['supplier']['history']['nr']?>,containers : 'paginator1', alwaysVisible:false,
								 pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								 previousPageLinkLabel : "<",
								 nextPageLinkLabel : ">",
								 firstPageLinkLabel :"<<",
								 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
								 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
							     })
							 
							 ,sortedBy : {
							    Key: "<?php echo$_SESSION['state']['supplier']['history']['order']?>",
							     dir: "<?php echo$_SESSION['state']['supplier']['history']['order_dir']?>"
							 },
							 dynamicData : true
							 
						     }
						     
						     );
	    
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;

		    
		    
	    this.table1.filter={key:'<?php echo$_SESSION['state']['supplier']['history']['f_field']?>',value:'<?php echo$_SESSION['state']['supplier']['history']['f_value']?>'};




    };});


function change_block(e){
     if(editing!=this.id){
	

	

	Dom.get('d_products').style.display='none';
	Dom.get('d_details').style.display='none';
	Dom.get('d_company').style.display='none';

	Dom.get('d_'+this.id).style.display='';

	//	alert(this.id);
	Dom.removeClass(editing,'selected');
	Dom.addClass(this, 'selected');
	
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=supplier-edit&value='+this.id ,{});
	
	editing=this.id;
    }



}

function init(){
	var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
	oACDS.queryMatchContains = true;
	var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container", oACDS);
	oAutoComp.minQueryLength = 0; 
	
	

	var ids = ["products","details","company"]; 
	YAHOO.util.Event.addListener(ids, "click", change_block);

	 var supplier_code_oACDS = new YAHOO.util.FunctionDataSource(validate_supplier_code);
	    supplier_code_oACDS.queryMatchContains = true;
	    var supplier_code_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Code","Supplier_Code_Container", supplier_code_oACDS);
	    supplier_code_oAutoComp.minQueryLength = 0; 
	    supplier_code_oAutoComp.queryDelay = 0.25;
	
	
	 var supplier_name_oACDS = new YAHOO.util.FunctionDataSource(validate_supplier_name);
	    supplier_name_oACDS.queryMatchContains = true;
	    var supplier_name_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Name","Supplier_Name_Container", supplier_name_oACDS);
	    supplier_name_oAutoComp.minQueryLength = 0; 
	    supplier_name_oAutoComp.queryDelay = 0.1;

 var product_code_oACDS = new YAHOO.util.FunctionDataSource(validate_product_code);
	    product_code_oACDS.queryMatchContains = true;
	    var product_code_oAutoComp = new YAHOO.widget.AutoComplete("Product_Code","Product_Code_Container", product_code_oACDS);
	    product_code_oAutoComp.minQueryLength = 0; 
	    product_code_oAutoComp.queryDelay = 0.25;
	
	
	 var product_name_oACDS = new YAHOO.util.FunctionDataSource(validate_product_units_per_case);
	    product_name_oACDS.queryMatchContains = true;
	    var product_name_oAutoComp = new YAHOO.widget.AutoComplete("Product_Units_Per_Case","Product_Units_Per_Case_Container", product_name_oACDS);
	    product_name_oAutoComp.minQueryLength = 0; 
	    product_name_oAutoComp.queryDelay = 0.1;





	}
	
	
YAHOO.util.Event.onDOMReady(init);
YAHOO.util.Event.onContentReady("filtermenu0", function () {
	var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {trigger:"filter_name0"});
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
	
    });
YAHOO.util.Event.onContentReady("rppmenu0", function () {
	var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0" });
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
	YAHOO.util.Event.addListener("rtext_rpp0", "click",oMenu.show , null, oMenu);
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
