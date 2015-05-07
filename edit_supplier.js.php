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

var dialog_country_list_bis;


var regex_valid_tel="^(\\+\\d{1,3} )?(\\(0\\)\\s*)?(?:[0-9] ?){3,13}[0-9]\\s*(\\s*(ext|x|e)\\s*\\d+)?$";

function validate_product_units_per_case(query){
validate_general('product_settings','units_per_case',unescape(query));

}
function validate_product_price_per_case(query){
validate_general('product_settings','price_per_case',unescape(query));
}

function validate_supplier_code(query){
validate_general('supplier','code',unescape(query));
}
function validate_supplier_name(query){
validate_general('supplier','name',unescape(query));
}
function validate_supplier_main_contact_name(query){
validate_general('supplier','contact',unescape(query));
}
function validate_supplier_main_email(query){
validate_general('supplier','email',unescape(query));
}
function validate_supplier_main_tel(query){
validate_general('supplier','telephone',unescape(query));
}
function validate_supplier_main_fax(query){
validate_general('supplier','fax',unescape(query));
}
function validate_supplier_qq(query){
validate_general('supplier','qq',unescape(query));
}
function validate_supplier_main_www(query){
validate_general('supplier','www',unescape(query));
}

function validate_supplier_dispatch_time(query){
validate_general('product_settings','dispatch_time',unescape(query));
}



function validate_product_code(query){
validate_general('product','code',unescape(query));
}
function validate_product_name(query){

validate_general('product','name',unescape(query));
}

function post_new_create_actions(branch,r){

var table_id=0
    var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];
    var request='&tableid='+table_id+'&sf=0';
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);  

}


function post_item_updated_actions(branch, r) {

    key = r.key;
    newvalue = r.newvalue;


    if (branch == 'supplier') {

        if (r.key == 'code') Dom.get('title_code').innerHTML = newvalue;
        else if (r.key == 'name') {

            Dom.get('title_name').innerHTML = newvalue;
            Dom.get('title_name_bis').innerHTML = newvalue;

        }else if(r.key=='origin'){
   		Dom.get('Supplier_Products_Origin_Country_Code').setAttribute('ovalue_formated',Dom.get('Supplier_Products_Origin_Country_Code_formated').innerHTML)
    	}
    }

    var table_id = 1


    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];


    var request = '&tableid=' + table_id + '&sf=0';
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
    
    //alert("x")
}

function change_supplier_products_view(e, data) {

    var table = tables['table' + data.table_id];
    var tipo = this.id;



    if (tipo == 'supplier_products_name') tipo = 'name';
    else if (tipo == 'supplier_products_cost') tipo = 'cost';
    else if (tipo == 'supplier_products_dimensions') tipo = 'dimensions';
    else if (tipo == 'supplier_products_po_data') tipo = 'po_data';

 	table.hideColumn('name');
    table.hideColumn('usedin');
    table.hideColumn('state');
    //  table.hideColumn('unit_type');
    table.hideColumn('units');
    table.hideColumn('cost');
    table.hideColumn('inners');
    table.hideColumn('currency');
    table.hideColumn('carton_cbm');
    table.hideColumn('dispatch_days');
    table.hideColumn('note_to_supplier');


    if (tipo == 'name') {
        table.showColumn('code');
        table.showColumn('name');
        table.showColumn('usedin');
        table.showColumn('state');
    } else if (tipo == 'cost') {
        //    table.showColumn('unit_type');
        table.showColumn('units');
        table.showColumn('cost');
        table.showColumn('inners');
        table.showColumn('currency');
    }else if (tipo == 'dimensions') {
        table.showColumn('carton_cbm');
      
    }else if (tipo == 'po_data') {
        table.showColumn('dispatch_days');
        table.showColumn('note_to_supplier');
     
    }

    Dom.removeClass(['supplier_products_name', 'supplier_products_cost','supplier_products_dimensions','supplier_products_po_data'], 'selected')
    Dom.addClass(this, 'selected')
    change_supplier_products_view_save(tipo)

}

function change_supplier_products_view_save(tipo) {
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=supplier-edit_supplier_products-view&value=' + escape(tipo), {});

}


YAHOO.util.Event.addListener(window, "load", function() {
	tables = new function() {
	
	
		    
		var tableid=0;
		var tableDivEL="table"+tableid;
		var ColumnDefs = [
				  
				    {key:"go",label:'',width:20}
				  ,{key:"sp_id", label:"<?php echo _('Key')?>", width:20,sortable:false,isPrimaryKey:true,hidden:true} 

				  ,{key:"sph_key", label:"<?php echo _('Key')?>", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				  ,{key:"code", label:"<?php echo _('Code')?>",  width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'supplier_product'}
				  ,{key:"name", label:"<?php echo _('Name')?>",<?php echo($_SESSION['state']['supplier']['edit_supplier_products']['view']=='name'?'':'hidden:true,')?>width:280, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'supplier_product'}
		

		
		,{key:"usedin", label:"<?php echo _('Used In')?>", <?php echo($_SESSION['state']['supplier']['edit_supplier_products']['view']=='name'?'':'hidden:true,')?>width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
		,{key:"state", label:"", <?php echo($_SESSION['state']['supplier']['edit_supplier_products']['view']=='name'?'':'hidden:true,')?>width:20,sortable:false,className:"aleft",action:'dialog',object:'supplier_product'}
				    ,{key:"state_value", label:"",hidden:true}
		
			//	  ,{key:"unit_type", label:"<?php echo _('Unit')?>",<?php echo($_SESSION['state']['supplier']['edit_supplier_products']['view']=='cost'?'':'hidden:true,')?>width:50, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.DropdownCellEditor({asyncSubmitter: CellEdit,dropdownOptions:units_list,disableBtns:true}),object:'supplier_product'}
							,{key:"inners", width:400, className:"aright",label:"<?php echo _('U/Inner')?>",<?php echo($_SESSION['state']['supplier']['edit_supplier_products']['view']=='cost'?'':'hidden:true,')?>sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'supplier_product'}
	
				,{key:"units", className:"aright",label:"<?php echo _('U/Carton')?>",<?php echo($_SESSION['state']['supplier']['edit_supplier_products']['view']=='cost'?'':'hidden:true,')?>width:50, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'supplier_product'}
  ,{key:"cost", label:"<?php echo _('Cost/Unit')?>",<?php echo($_SESSION['state']['supplier']['edit_supplier_products']['view']=='cost'?'':'hidden:true,')?>width:80, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'supplier_product'}
	  ,{key:"currency", label:"<?php echo _('Currency')?>",<?php echo($_SESSION['state']['supplier']['edit_supplier_products']['view']=='cost'?'':'hidden:true,')?>width:80, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				  ,{key:"carton_cbm", label:"<?php echo _('Carton CBM')?>",<?php echo($_SESSION['state']['supplier']['edit_supplier_products']['view']=='dimensions'?'':'hidden:true,')?>width:80, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'supplier_product'}
				  ,{key:"dispatch_days", label:"<?php echo _('Dispatch days')?>",<?php echo($_SESSION['state']['supplier']['edit_supplier_products']['view']=='po_data'?'':'hidden:true,')?>width:80, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'supplier_product'}
				  ,{key:"note_to_supplier", width:500,label:"<?php echo _('Note to supplier')?>",<?php echo($_SESSION['state']['supplier']['edit_supplier_products']['view']=='po_data'?'':'hidden:true,')?> sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'supplier_product'}


				  ];
		
request="ar_edit_suppliers.php?tipo=supplier_products&parent=supplier&parent_key="+Dom.get("supplier_key").value+"&sf=0&tableid="+tableid
	//	alert(request)
		this.dataSource0 = new YAHOO.util.DataSource(request);
	
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
				 "id","code","name","cost","usedin","units","unit_type","sph_key","delete","delete_type",'go','state','sp_id','state_value','inners','currency','carton_cbm','dispatch_days','note_to_supplier'
				 ]};
		
		this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
							     this.dataSource0, {
							     //draggableColumns:true,
							     renderLoopSize: 50,generateRequest : myRequestBuilder
							     ,paginator : new YAHOO.widget.Paginator({
								     rowsPerPage:<?php echo$_SESSION['state']['supplier']['edit_supplier_products']['nr']?>,containers : 'paginator0', 
								     pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
								     previousPageLinkLabel : "<",
								     nextPageLinkLabel : ">",
								     firstPageLinkLabel :"<<",
									 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
								     ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
								 })
							     
							     ,sortedBy : {
								 key: "<?php echo$_SESSION['state']['supplier']['edit_supplier_products']['order']?>",
								 dir: "<?php echo$_SESSION['state']['supplier']['edit_supplier_products']['order_dir']?>"
							     }
							     ,dynamicData : true
							     
							 }
							 );
		
						
						 
							 	this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
		this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
		this.table0.subscribe("cellClickEvent", onCellClick);
	 
		this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
		this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
		this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
		        this.table0.request = request;

		this.table0.table_id=tableid;
     this.table0.subscribe("renderEvent", myrenderEvent);
		
		this.table0.filter={key:'<?php echo$_SESSION['state']['supplier']['edit_supplier_products']['f_field']?>',value:'<?php echo$_SESSION['state']['supplier']['edit_supplier_products']['f_value']?>'};
		
		


	




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
	    
	    this.dataSource1 = new YAHOO.util.DataSource("ar_history.php?tipo=history&type=supplier&tableid=1&sf=0&parent_key="+Dom.get('supplier_key').value);
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




   var tableid=4; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [
                    {key:"flag", label:"",width:10,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"code", label:"<?php echo _('Code')?>",width:25,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			       ,{key:"name", label:"<?php echo _('Name')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"wregion", label:"<?php echo _('Region')?>",width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

		];
			       
	    this.dataSource4 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=country_list&tableid="+tableid+"&nr=20&sf=0");
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
			 "name","flag",'code','wregion'
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
	    //this.table4.subscribe("cellClickEvent", this.table4.onEventShowCellEditor);

 this.table4.subscribe("rowMouseoverEvent", this.table4.onEventHighlightRow);
       this.table4.subscribe("rowMouseoutEvent", this.table4.onEventUnhighlightRow);
      this.table4.subscribe("rowClickEvent", change_origin_country_code);
     


	    this.table4.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table4.filter={key:'code',value:''};



   var tableid=5; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var ColumnDefs = [
                   {key:"flag", label:"",width:25,sortable:false,className:"aleft"}
                   ,{key:"code", label:"<?php echo _('Code')?>",width:25,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			       ,{key:"name", label:"<?php echo _('Name')?>",width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			       ,{key:"symbol", label:"<?php echo _('Symbol')?>",width:100,sortable:false}

		];
			       
	    this.dataSource5 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=currency_list&tableid="+tableid+"&nr=20&sf=0");
	    this.dataSource5.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource5.connXhrMode = "queueRequests";
	    	    this.dataSource5.table_id=tableid;

	    this.dataSource5.responseSchema = {
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
			 "name","flag",'code','symbol'
			 ]};

	    this.table5 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource5
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator5', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info5'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "code",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table5.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table5.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table5.subscribe("cellClickEvent", this.table5.onEventShowCellEditor);

 this.table5.subscribe("rowMouseoverEvent", this.table5.onEventHighlightRow);
       this.table5.subscribe("rowMouseoutEvent", this.table5.onEventUnhighlightRow);
      this.table5.subscribe("rowClickEvent", change_currency);
     


	    this.table5.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table5.filter={key:'code',value:''};

  var tableid=100; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

 this.remove_links = function(elLiner, oRecord, oColumn, oData) {
  elLiner.innerHTML = oData;
         //   if(oRecord.getData("field3") > 100) {
       elLiner.innerHTML=  oData.replace(/<.*?>/g, '')

        };
        
        // Add the custom formatter to the shortcuts
        YAHOO.widget.DataTable.Formatter.remove_links = this.remove_links;

	   
	    var ColumnDefs = [
                    {key:"flag", label:"",width:10,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"code",formatter:"remove_links", label:"<?php echo _('Code')?>",width:30,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			       ,{key:"name", formatter:"remove_links",label:"<?php echo _('Name')?>",width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
			];
			       
	    this.dataSource100 = new YAHOO.util.DataSource("ar_regions.php?tipo=country_list&tableid="+tableid+"&nr=20&sf=0");
	    this.dataSource100.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource100.connXhrMode = "queueRequests";
	    	    this.dataSource100.table_id=tableid;

	    this.dataSource100.responseSchema = {
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
			 "name","flag",'code','population','gnp','wregion','code3a','code2a','plain_name','postal_regex','postcode_help'
			 ]};


	    this.table100 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource100
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?php echo$_SESSION['state']['world']['countries']['nr']?>,containers : 'paginator100', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info100'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['world']['countries']['order']?>",
									 dir: "<?php echo$_SESSION['state']['world']['countries']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table100.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table100.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table100.subscribe("cellClickEvent", this.table100.onEventShowCellEditor);
this.table100.prefix='';
 this.table100.subscribe("rowMouseoverEvent", this.table100.onEventHighlightRow);
       this.table100.subscribe("rowMouseoutEvent", this.table100.onEventUnhighlightRow);
      this.table100.subscribe("rowClickEvent", select_country_from_list);
     


	    this.table100.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table100.filter={key:'<?php echo$_SESSION['state']['world']['countries']['f_field']?>',value:'<?php echo$_SESSION['state']['world']['countries']['f_value']?>'};
	   
    };});


function show_history() {
    Dom.setStyle(['show_history', ''], 'display', 'none')
    Dom.setStyle(['hide_history', 'history_table'], 'display', '')

    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=supplier-show_history&value=1', {});

}

function hide_history() {
    Dom.setStyle(['show_history', ''], 'display', '')
    Dom.setStyle(['hide_history', 'history_table'], 'display', 'none')
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=supplier-show_history&value=0', {});

}

function save_category(o) {

var parent_category_key=o.getAttribute('cat_key');
var category_key=o.options[o.selectedIndex].value;
var subject='Supplier';
var subject_key=Dom.get('supplier_id').value;

//if(Dom.hasClass(o,'selected'))
//    var operation_type='disassociate_subject_to_category';
//else


if(category_key==''){
var request='ar_edit_categories.php?tipo=disassociate_subject_from_all_sub_categories&category_key=' + parent_category_key+ '&subject=' + subject +'&subject_key=' + subject_key 

}else{
var request='ar_edit_categories.php?tipo=associate_subject_to_category&category_key=' + category_key+ '&subject=' + subject +'&subject_key=' + subject_key +"&parent_category_key="+parent_category_key+"&cat_id="+o.id


}


	//alert(request);
	
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
			    success:function(o) {
			//alert(o.responseText);
				var r =  YAHOO.lang.JSON.parse(o.responseText);
				if(r.state==200){
				}


        
    }
                                                                 });



}


function change_block(e){
 var ids = ["products","details","company","categories"]; 
    var block_ids = ["d_products","d_details","d_company","d_categories"]; 

Dom.setStyle(block_ids,'display','none');
Dom.setStyle('d_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=supplier-edit&value='+this.id ,{});
}

function delete_origin_country_code(){

Dom.get('Supplier_Products_Origin_Country_Code_formated').innerHTML=''
Dom.setStyle(['update_Supplier_Products_Origin_Country_Code','delete_Supplier_Products_Origin_Country_Code'],'display','none')
Dom.setStyle('set_Supplier_Products_Origin_Country_Code','display','')


 
   value='';
     
    validate_scope_data['supplier']['origin']['value'] = value;
   
    Dom.get('Supplier_Products_Origin_Country_Code').value = value
    ovalue = Dom.get('Supplier_Products_Origin_Country_Code').getAttribute('ovalue');

    if (ovalue != value) {
        validate_scope_data['supplier']['origin']['changed'] = true;
    } else {
        validate_scope_data['supplier']['origin']['changed'] = false;
    }
    validate_scope('supplier')

}



function show_dialog_country_list_bis(e) {

    region1 = Dom.getRegion(this);
    region2 = Dom.getRegion('dialog_country_list_bis');
    var pos = [region1.right + 5, region1.top - 120]
    Dom.setXY('dialog_country_list_bis', pos);
    dialog_country_list_bis.show()

}

function change_origin_country_code(oArgs) {

    country_code = tables.table4.getRecord(oArgs.target).getData('code').replace(/<.*?>/g, '');
    country_name = tables.table4.getRecord(oArgs.target).getData('name').replace(/<.*?>/g, '');

    Dom.get('Supplier_Products_Origin_Country_Code_formated').innerHTML = country_name
    Dom.setStyle(['update_Supplier_Products_Origin_Country_Code', 'delete_Supplier_Products_Origin_Country_Code'], 'display', '')
    Dom.setStyle('set_Supplier_Products_Origin_Country_Code', 'display', 'none')


    value = country_code;

    validate_scope_data['product_settings']['origin']['value'] = value;

    Dom.get('Supplier_Products_Origin_Country_Code').value = value
    ovalue = Dom.get('Supplier_Products_Origin_Country_Code').getAttribute('ovalue');

    if (ovalue != value) {
        validate_scope_data['product_settings']['origin']['changed'] = true;
    } else {
        validate_scope_data['product_settings']['origin']['changed'] = false;
    }

    validate_scope('product_settings')

    dialog_country_list_bis.hide();

}

function change_currency(oArgs) {
    var currency = tables.table5.getRecord(oArgs.target).getData('code').replace(/<.*?>/g, '');

    Dom.get('Supplier_Default_Currency_formated').innerHTML = currency
    Dom.setStyle(['update_Supplier_Default_Currency'], 'display', '')
    Dom.setStyle('set_Supplier_Default_Currency', 'display', 'none')



 
    value = currency;
    validate_scope_data['product_settings']['currency']['value'] = value;

    validate_scope_data.product_settings.modify_products_currency.changed=true;
     validate_scope_data.product_settings.products_currency_ratio.changed=true;
  
    
    Dom.get('Supplier_Default_Currency').value = value
    ovalue = Dom.get('Supplier_Default_Currency').getAttribute('ovalue');
    if (ovalue != value) {
        validate_scope_data['product_settings']['currency']['changed'] = true;
    } else {
        validate_scope_data['product_settings']['currency']['changed'] = false;
    }

    validate_scope('product_settings')

    dialog_currency_list.hide();

}



function show_dialog_currency_list(e) {

    region1 = Dom.getRegion(this);
    region2 = Dom.getRegion('dialog_currency_list');
    var pos = [region1.right + 5, region1.top - 120]
    Dom.setXY('dialog_currency_list', pos);
    dialog_currency_list.show()

}

function save_edit_supplier() {
    save_edit_general_bulk('supplier');
}

function save_edit_product_settings() {
    save_edit_general_bulk('product_settings');
}


function reset_edit_supplier(){
 reset_edit_general('supplier')
 
}

function reset_edit_product_settings(){
 reset_edit_general('product_settings')
  origin = Dom.get('Supplier_Products_Origin_Country_Code').getAttribute('ovalue')
      origin_formated = Dom.get('Supplier_Products_Origin_Country_Code').getAttribute('ovalue_formated')
      
      Dom.get('Supplier_Products_Origin_Country_Code_formated').innerHTML=origin_formated
      
      if(origin==''){
      
      Dom.setStyle(['update_Supplier_Products_Origin_Country_Code','delete_Supplier_Products_Origin_Country_Code'],'display','none')
      Dom.setStyle('set_Supplier_Products_Origin_Country_Code','display','')
      
      
      
      }else{
       Dom.setStyle(['update_Supplier_Products_Origin_Country_Code','delete_Supplier_Products_Origin_Country_Code'],'display','')
      Dom.setStyle('set_Supplier_Products_Origin_Country_Code','display','none')
      }
}

function save_supplier_product_state(state){
    sp_id = Dom.get('edit_supplier_product_state_sp_id').value;
    table_record_index = Dom.get('edit_supplier_product_state_table_record_index').value;
    table_id = Dom.get('edit_supplier_product_state_table_id').value;
key='Supplier Product State';

    var request = 'ar_edit_suppliers.php?tipo=edit_supplier_product&key=' + key + '&newvalue=' + state + '&sp_id=' + sp_id + '&okey=' + key + '&table_record_index=' + table_record_index
    	alert(request);
    YAHOO.util.Connect.asyncRequest('POST', request, {
        success: function(o) {
            //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);


            if (r.state == 200) {

                var table = tables['table'+Dom.get('edit_flag_table_id').value];
                record = table.getRecord(r.record_index);

                var data = record.getData();
                data['flag'] = r.flag;
                data['flag_value'] = r.flag_value;

                table.updateRow(r.record_index, data);


                for (x in r.locations_flag_data) {
                    Dom.get('elements_' + r.locations_flag_data[x].color + '_number').innerHTML = r.locations_flag_data[x].number;
                }


                dialog_edit_flag.hide()
                
                
                if(Dom.get('edit_flag_table_id').value==0){
                table_id = 3
                }else{
                table_id = 0
                }
 
                var table = tables['table' + table_id];
                var datasource = tables['dataSource' + table_id];
                datasource.sendRequest('', table.onDataReturnInitializeTable, table);

              
            }

        }
    });

}

function show_cell_dialog(datatable, oArgs) {

    var target = oArgs.target;
    var column = datatable.getColumn(target);
    var record = datatable.getRecord(target);
    var recordIndex = datatable.getRecordIndex(record);

    switch (column.object) {
    

    case 'supplier_product':

       Dom.get('edit_supplier_product_state_sp_id').value = record.getData('sp_id');
       Dom.get('edit_supplier_product_state_table_record_index').value=recordIndex;
       Dom.get('edit_supplier_product_state_table_id').value=0;
       
       
       
      
       
       Dom.removeClass(Dom.getElementsByClassName('buttons','button','supplier_product_state_operations'),'selected')
     
       Dom.addClass('supplier_product_state_'+record.getData('state_value'),'selected')
       
       

        region1 = Dom.getRegion(target);
        region2 = Dom.getRegion('dialog_edit_supplier_product_state');
        var pos = [region1.left-region2.width , region1.top]
        Dom.setXY('dialog_edit_supplier_product_state', pos);
        dialog_edit_supplier_product_state.show();
        break;
        
       
    }

}

function post_bulk_save_actions(branch) {

    if(branch=='product_settings'){
    table_id = 0;
                var table = tables['table' + table_id];
                var datasource = tables['dataSource' + table_id];
                datasource.sendRequest('', table.onDataReturnInitializeTable, table);
    }

}



function init(){

validate_scope_data = {
    'supplier': {
        'name': {
            'changed': false,
            'validated': true,
            'required': true,
            'group': 1,
            'type': 'item',
            'validation': [{
                'regexp': "[a-z\\d]+",
                'invalid_msg': '<?php echo _('Invalid Supplier Name')?>'
            }],
            'name': 'Supplier_Name',
            'ar': false
        },
        'code': {
            'changed': false,
            'validated': true,
            'required': false,
            'group': 1,
            'type': 'item',
            'validation': [{
                'regexp': "[a-z\\d]+",
                'invalid_msg': '<?php echo _('Invalid Supplier Code')?>'
            }],
            'name': 'Supplier_Code',
            'ar': 'find',
            'ar_request': 'ar_suppliers.php?tipo=is_supplier_code&query='
        },
        'contact': {
            'changed': false,
            'validated': true,
            'required': false,
            'group': 1,
            'type': 'item',
            'name': 'Supplier_Main_Contact_Name',
            'validation': [{
                'regexp': "[a-z\\d]+",
                'invalid_msg': '<?php echo _('Invalid Contact Name ')?>'
            }]
        },
        'email': {
            'changed': false,
            'validated': true,
            'required': false,
            'group': 1,
            'type': 'item',
            'name': 'Supplier_Main_Email',
            'validation': [{
                'regexp': regexp_valid_email,
                'invalid_msg': '<?php echo _('Invalid Email')?>'
            }]
        },
        'qq': {
            'changed': false,
            'validated': true,
            'required': false,
            'group': 1,
            'type': 'item',
            'name': 'Supplier_QQ',
            'validation': [{
                'numeric': 'positive',
                'invalid_msg': '<?php echo _('Invalid QQ')?>'
            }]
        },
        'telephone': {
            'changed': false,
            'validated': true,
            'required': false,
            'group': 1,
            'type': 'item',
            'name': 'Supplier_Main_Telephone',
            'validation': [{
                'regexp': regex_valid_tel,
                'invalid_msg': '<?php echo _('Invalid Telephone')?>'
            }]
        },
        'fax': {
            'changed': false,
            'validated': true,
            'required': false,
            'group': 1,
            'type': 'item',
            'name': 'Supplier_Main_Fax',
            'validation': [{
                'regexp':regex_valid_tel,
                'invalid_msg': '<?php echo _('Invalid Fax')?>'
            }]
        },
        'www': {
            'changed': false,
            'validated': true,
            'required': false,
            'group': 1,
            'type': 'item',
            'name': 'Supplier_Main_Website',
            'validation': [{
                'regexp': regexp_valid_www,
                'invalid_msg': '<?php echo _('Invalid URL')?>'
            }]
        }

    },
    'product_settings': {
        'origin': {
            'changed': false,
            'validated': true,
            'required': false,
            'group': 1,
            'type': 'item',
            'dbname': 'Supplier Products Origin Country Code',
            'name': 'Supplier_Products_Origin_Country_Code',
            'ar': false,
            'validation': false

        },
        'currency': {
            'changed': false,
            'validated': true,
            'required': false,
            'group': 1,
            'type': 'item',
            'dbname': 'Supplier Default Currency',
            'name': 'Supplier_Default_Currency',
            'ar': false,
            'validation': false

        },
        'modify_products_currency': {
            'changed': false,
            'validated': true,
            'required': true,
            'group': 1,
            'type': 'item',
            'dbname': 'modify_products_currency',
            'name': 'modify_products_currency',
            'ar': false,
            'validation': false

        },
        'products_currency_ratio': {
            'changed': false,
            'validated': true,
            'required': true,
            'group': 1,
            'type': 'item',
            'dbname': 'products_currency_ratio',
            'name': 'products_currency_ratio',
            'ar': false,
            'validation': [{
                'numeric': "money",
                'invalid_msg': 'wrong number'
            }]

        },
        'dispatch_time': {
            'changed': false,
            'validated': true,
            'required': false,
            'group': 1,
            'type': 'item',
            'name': 'Supplier_Average_Delivery_Days',
            'validation': [{
                'numeric': "positive integer",
                'invalid_msg': '<?php echo _('Invalid number')?>'
            }]
        }

    }
};


validate_scope_metadata = {
    'supplier': {
        'type': 'edit',
        'ar_file': 'ar_edit_suppliers.php',
        'key_name': 'supplier_key',
        'key': Dom.get('supplier_key').value
    },
     'product_settings': {
        'type': 'edit',
        'ar_file': 'ar_edit_suppliers.php',
        'key_name': 'supplier_key',
        'key': Dom.get('supplier_key').value
    }
};

init_search('supplier_products_supplier');

	
	

	var ids = ["products","details","company","categories"]; 
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

	  var supplier_main_contact_name_oACDS = new YAHOO.util.FunctionDataSource(validate_supplier_main_contact_name);
	    supplier_main_contact_name_oACDS.queryMatchContains = true;
	    var supplier_main_contact_name_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Main_Contact_Name","Supplier_Main_Contact_Name_Container", supplier_main_contact_name_oACDS);
	    supplier_main_contact_name_oAutoComp.minQueryLength = 0; 
	    supplier_main_contact_name_oAutoComp.queryDelay = 0.1;
	    
 var supplier_main_email_oACDS = new YAHOO.util.FunctionDataSource(validate_supplier_main_email);
	    supplier_main_email_oACDS.queryMatchContains = true;
	    var supplier_main_email_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Main_Email","Supplier_Main_Email_Container", supplier_main_email_oACDS);
	    supplier_main_email_oAutoComp.minQueryLength = 0; 
	    supplier_main_email_oAutoComp.queryDelay = 0.1;

var supplier_main_tel_oACDS = new YAHOO.util.FunctionDataSource(validate_supplier_main_tel);
	    supplier_main_tel_oACDS.queryMatchContains = true;
	    var supplier_main_tel_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Main_Telephone","Supplier_Main_Telephone_Container", supplier_main_tel_oACDS);
	    supplier_main_tel_oAutoComp.minQueryLength = 0; 
	    supplier_main_tel_oAutoComp.queryDelay = 0.1;
	    
	    var supplier_qq_oACDS = new YAHOO.util.FunctionDataSource(validate_supplier_qq);
	    supplier_qq_oACDS.queryMatchContains = true;
	    var supplier_qq_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_QQ","Supplier_QQ_Container", supplier_qq_oACDS);
	    supplier_qq_oAutoComp.minQueryLength = 0; 
	    supplier_qq_oAutoComp.queryDelay = 0.1;
	    

var supplier_main_fax_oACDS = new YAHOO.util.FunctionDataSource(validate_supplier_main_fax);
	    supplier_main_fax_oACDS.queryMatchContains = true;
	    var supplier_main_fax_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Main_Fax","Supplier_Main_Fax_Container", supplier_main_fax_oACDS);
	    supplier_main_fax_oAutoComp.minQueryLength = 0; 
	    supplier_main_fax_oAutoComp.queryDelay = 0.1;
	    
var supplier_main_www_oACDS = new YAHOO.util.FunctionDataSource(validate_supplier_main_www);
	    supplier_main_www_oACDS.queryMatchContains = true;
	    var supplier_main_www_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Main_Website","Supplier_Main_Website_Container", supplier_main_www_oACDS);
	    supplier_main_www_oAutoComp.minQueryLength = 0; 
	    supplier_main_www_oAutoComp.queryDelay = 0.1;	    


var supplier_dispatch_time_oACDS = new YAHOO.util.FunctionDataSource(validate_supplier_dispatch_time);
	    supplier_dispatch_time_oACDS.queryMatchContains = true;
	    var supplier_dispatch_time_oAutoComp = new YAHOO.widget.AutoComplete("Supplier_Average_Delivery_Days","Supplier_Average_Delivery_Days_Container", supplier_dispatch_time_oACDS);
	    supplier_dispatch_time_oAutoComp.minQueryLength = 0; 
	    supplier_dispatch_time_oAutoComp.queryDelay = 0.1;	    


		edit_address(Dom.get("main_address_key").value,'contact_')
 

	var ids = ["contact_address_description","contact_address_country_d1","contact_address_country_d2","contact_address_town","contact_address_town_d2","contact_address_town_d1","contact_address_postal_code","contact_address_street","contact_address_internal","contact_address_building"]; 
	YAHOO.util.Event.addListener(ids, "keyup", on_address_item_change,'contact_');
	YAHOO.util.Event.addListener(ids, "change",on_address_item_change,'contact_');
	YAHOO.util.Event.addListener('contact_save_address_button', "click",save_address,{prefix:'contact_',subject:'Supplier',subject_key:supplier_id,type:'contact'});
	YAHOO.util.Event.addListener('contact_reset_address_button', "click",reset_address,'contact_');
	
	
	  dialog_country_list_bis = new YAHOO.widget.Dialog("dialog_country_list_bis", {
       
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_country_list_bis.render();
    
    
    Event.addListener("set_Supplier_Products_Origin_Country_Code", "click", show_dialog_country_list_bis);
    Event.addListener("update_Supplier_Products_Origin_Country_Code", "click", show_dialog_country_list_bis);


  dialog_currency_list = new YAHOO.widget.Dialog("dialog_currency_list", {
       
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_currency_list.render();
    
    
    Event.addListener("set_Supplier_Default_Currency", "click", show_dialog_currency_list);
    Event.addListener("update_Supplier_Default_Currency", "click", show_dialog_currency_list);



 dialog_edit_supplier_product_state = new YAHOO.widget.Dialog("dialog_edit_supplier_product_state", {
       
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_edit_supplier_product_state.render();



	 Event.addListener('save_edit_supplier', "click", save_edit_supplier);
    Event.addListener('reset_edit_supplier', "click", reset_edit_supplier);

	 Event.addListener('save_edit_product_settings', "click", save_edit_product_settings);
    Event.addListener('reset_edit_product_settings', "click", reset_edit_product_settings);


 Event.addListener('clean_table_filter_show0', "click", show_filter, 0);
    Event.addListener('clean_table_filter_hide0', "click", hide_filter, 0);
    Event.addListener('clean_table_filter_show1', "click", show_filter, 1);
    Event.addListener('clean_table_filter_hide1', "click", hide_filter, 1);
    Event.addListener('clean_table_filter_show4', "click", show_filter, 4);
    Event.addListener('clean_table_filter_hide4', "click", hide_filter, 4);
     Event.addListener('clean_table_filter_show5', "click", show_filter, 5);
    Event.addListener('clean_table_filter_hide5', "click", hide_filter, 5);
    Event.addListener('clean_table_filter_show100', "click", show_filter, 100);
    Event.addListener('clean_table_filter_hide100', "click", hide_filter, 100);
    
   
    var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    oACDS.table_id = 0;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0", "f_container0", oACDS);
    oAutoComp.minQueryLength = 0;

    var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS1.queryMatchContains = true;
    oACDS1.table_id = 1;
    var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1", "f_container1", oACDS1);
    oAutoComp1.minQueryLength = 0;

    var oACDS4 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS4.queryMatchContains = true;
    oACDS4.table_id = 4;
    var oAutoComp4 = new YAHOO.widget.AutoComplete("f_input4", "f_container4", oACDS4);
    oAutoComp4.minQueryLength = 0;
    
     var oACDS5 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS5.queryMatchContains = true;
    oACDS5.table_id = 5;
    var oAutoComp5 = new YAHOO.widget.AutoComplete("f_input5", "f_container5", oACDS5);
    oAutoComp5.minQueryLength = 0;


 var oACDS100 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS100.queryMatchContains = true;
    oACDS100.table_id = 100;
    var oAutoComp100 = new YAHOO.widget.AutoComplete("f_input100", "f_container100", oACDS100);
    oAutoComp100.minQueryLength = 0;


  
    Event.addListener(['supplier_products_name','supplier_products_cost','supplier_products_dimensions','supplier_products_po_data'], "click", change_supplier_products_view, {table_id:0});


	
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
    
    
    YAHOO.util.Event.onContentReady("rppmenu4", function() {
    var oMenu = new YAHOO.widget.ContextMenu("rppmenu4", {
        trigger: "rtext_rpp4"

    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);


});

YAHOO.util.Event.onContentReady("filtermenu4", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu4", {
        trigger: "filter_name4"

    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);


});

    YAHOO.util.Event.onContentReady("rppmenu100", function() {
    var oMenu = new YAHOO.widget.ContextMenu("rppmenu100", {
        trigger: "rtext_rpp100"

    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);


});

YAHOO.util.Event.onContentReady("filtermenu100", function() {
    var oMenu = new YAHOO.widget.ContextMenu("filtermenu100", {
        trigger: "filter_name100"

    });
    oMenu.render();
    oMenu.subscribe("show", oMenu.focus);


});

