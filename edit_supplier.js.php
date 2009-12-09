<?php
    include_once('common.php');

$unit_list='';
foreach(getEnumVals('`Supplier Product Dimension`','Supplier Product Unit Type') as $value){
    $unit_list.=",'".$value."'";
}
$unit_list=preg_replace('/^,/','',$unit_list);

 print "var supplier_id='".$_SESSION['state']['supplier']['id']."';";

 print "var units_list=[$unit_list]";
?>
  
   

  
    var Dom   = YAHOO.util.Dom;
var editing='<?php echo $_SESSION['state']['supplier']['edit']?>';


var validate_scope_data={
    'code':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','regexp':"[a-z\\d]+",'name':'Supplier_Code'}
    ,'name':{'changed':false,'validated':true,'required':true,'group':1,'type':'item','regexp':"[a-z\\d]+",'name':'Supplier_Name'}

};



function validate_scope(){
var changed=false;
var errors=false;
//alert(validate_scope_data['name'].changed+'v:'+validate_scope_data['name'].validated)


    for(item in validate_scope_data){
    
        if(validate_scope_data[item].changed==true)
            changed=true;
         if(validate_scope_data[item].validated==false)
            errors=true;
    }
    
    if(changed ){
	Dom.get('reset_edit_supplier').style.visibility='visible';
	if(!errors)
	    Dom.get('save_edit_supplier').style.visibility='visible';
	else
	    Dom.get('save_edit_supplier').style.visibility='hidden';

    }else{
        Dom.get('save_edit_supplier').style.visibility='hidden';
	Dom.get('reset_edit_supplier').style.visibility='hidden';

    }
    
    
    
}


YAHOO.util.Event.addListener(window, "load", function() {
	tables = new function() {


		 var tableid=0;
		    var tableDivEL="table"+tableid;
		var ColumnDefs = [
				  {key:"sph_key", label:"<?php echo _('Key')?>", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				  ,{key:"code", label:"<?php echo _('Code')?>",  width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product_supplier'}
				  ,{key:"name", label:"<?php echo _('Name')?>",<?php echo($_SESSION['state']['supplier']['products']['view']=='product_general'?'':'hidden:true,')?>width:280, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product_supplier'}
				  ,{key:"usedin", label:"<?php echo _('Used In')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				  ,{key:"unit_type", label:"<?php echo _('Unit')?>",<?php echo($_SESSION['state']['supplier']['products']['view']=='product_general'?'':'hidden:true,')?>width:50, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.DropdownCellEditor({dropdownOptions:units_list,disableBtns:true})}
				  ,{key:"units", className:"aright",label:"<?php echo _('U/C')?>",<?php echo($_SESSION['state']['supplier']['products']['view']=='product_general'?'':'hidden:true,')?>width:50, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product_supplier'}

				  ,{key:"cost", label:"<?php echo _('Cost/u')?>",<?php echo($_SESSION['state']['supplier']['products']['view']=='product_general'?'':'hidden:true,')?>width:80, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product_supplier'}
			


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
				 "id","code","name","cost","usedin","units","unit_type","sph_key"
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
								     key: "<?php echo$_SESSION['state']['supplier']['products']['order']?>",
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




    };});


var change_view=function(e){
	
    var table=tables['table0'];
    var tipo=this.id;

    table.hideColumn('location');
    table.hideColumn('email');
    table.hideColumn('for_sale');
    table.hideColumn('tobediscontinued');
    table.hideColumn('nosale');
    table.hideColumn('high');
    table.hideColumn('normal');
    table.hideColumn('low');
    table.hideColumn('critical');
    table.hideColumn('outofstock');
    table.hideColumn('profit');
    table.hideColumn('profit_after_storing');
    table.hideColumn('cost');
    if(tipo=='general'){
	table.showColumn('name');
	table.showColumn('location');
	table.showColumn('email');
    }else if(tipo=='stock'){
	table.showColumn('high');
	table.showColumn('normal');
	table.showColumn('low');
	table.showColumn('critical');
	table.showColumn('outofstock');


    }else if(tipo=='sales'){
	table.showColumn('profit');
	table.showColumn('profit_after_storing');
	table.showColumn('cost');

    }else if(tipo=='products'){
	table.showColumn('for_sale');
	table.showColumn('tobediscontinued');
	table.showColumn('nosale');
    }
	

    Dom.get(table.view).className="";
    Dom.get(tipo).className="selected";
    table.view=tipo
    YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=suppliers-view&value=' + escape(tipo) );
};
function change_block(e){
     if(editing!=this.id){
	

	

	Dom.get('d_products').style.display='none';
	Dom.get('d_details').style.display='none';
	Dom.get('d_company').style.display='none';

	Dom.get('d_'+this.id).style.display='';

	//	alert(this.id);
	Dom.removeClass(editing,'selected');
	Dom.addClass(this, 'selected');
	
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=supplier-edit&value='+this.id );
	
	editing=this.id;
    }



}




function validate_supplier_code(query){
 query=unescape(query);
    var old_code=Dom.get('Supplier_Code').getAttribute('ovalue');
 


    if(old_code.toLowerCase()!=trim(query.toLowerCase())){  
    validate_scope_data.code.changed=true;

	var request='ar_suppliers.php?tipo=is_supplier_code&query='+query; 
 // alert(request)
  YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
		    if(r.found==1){
		    Dom.get('Supplier_Code_msg').innerHTML=r.msg;
		    validate_scope_data.code.validated=false;
		    }else{
            Dom.get('Supplier_Code_msg').innerHTML='';
		    validate_scope_data.code.validated=true;
		    
		     var validator=new RegExp(validate_scope_data.code.regexp,"i");
            if(!validator.test(query)){
	           
	                validate_scope_data.code.validated=false;
                   Dom.get('Supplier_Code_msg').innerHTML='<?php echo _('Invalid Supplier Code')?>';

                 }
		    
		    
		    }
		    validate_scope(); 

		}else
		    Dom.get(msg_div).innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	    
	    });
}else{
                 validate_scope_data.code.validated=true;
                                  validate_scope_data.code.changed=false;
 validate_scope(); 
		    }
		    
		


}

function validate_supplier_name(query){
    query=unescape(query);
    var old_name=Dom.get('Supplier_Name').getAttribute('ovalue');
  
    //    alert(trim(query.toLowerCase())+'<-')
  if(old_name.toLowerCase()!=trim(query.toLowerCase())){  
	validate_scope_data.name.changed=true;
	var validator=new RegExp(validate_scope_data.name.regexp,"i");
	if(!validator.test(query)){
	    
	    validate_scope_data.code.validated=false;
	    Dom.get('Supplier_Name_msg').innerHTML='<?php echo _('Invalid Supplier Name')?>';
	    
	}else{
	    validate_scope_data.name.validated=true;
	    Dom.get('Supplier_Name_msg').innerHTML='';
            
	}
    }
    else{
	validate_scope_data.name.validated=true;
	validate_scope_data.name.changed=false;
	
    }
    
    validate_scope();   
    
}

function save_edit_supplier(){
    
    for(item in validate_scope_data){
	if(validate_scope_data[item].changed){
	var item_input=Dom.get(validate_scope_data[item].name);
	var request='ar_edit_suppliers.php?tipo=edit_supplier&key=' + item+ '&newvalue=' + 
	    encodeURIComponent(item_input.value) + 
	    '&supplier_key='+supplier_id;
	
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		    //alert(o.responseText)
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state==200){
			
			validate_scope_data[r.key].changed=false;
			validate_scope_data[r.key].validated=true;
			Dom.get(validate_scope_data[r.key].name).setAttribute('ovalue',r.newvalue);
			Dom.get(validate_scope_data[r.key].name).value=r.newvalue;
			Dom.get(validate_scope_data[r.key].name+'_msg').innerHTML='<?php echo _('Updated')?>';
			
			//var table=tables.table1;
			//	var datasource=tables.dataSource1;
			//var request='';
			//datasource.sendRequest(request,table.onDataReturnInitializeTable, table); 
			if(r.key=='name'){
			    Dom.get('title_name').innerHTML=r.newvalue;
			    Dom.get('name').value=r.newvalue;
			    Dom.get('name').setAttribute('ovalue',r.newvalue);

			}
			
			if(r.key=='code')
			    Dom.get('title_code').innerHTML=r.newvalue;
		    }else{
			validate_scope_data[r.key].changed=true;
			validate_scope_data[r.key].validated=false;
			Dom.get(validate_scope_data[r.key].name+'_msg').innerHTML=r.msg;
			
		    }
		    
		}
			    
	    });
	}
    }
    
    
}

function reset_edit_supplier(){
    for(item in validate_scope_data){
	var item_input=Dom.get(validate_scope_data[item].name);
	item_input.value=item_input.getAttribute('ovalue');
	validate_scope_data[item].changed=false;
	validate_scope_data[item].validated=true;
	Dom.get(validate_scope_data[item].name+'_msg').innerHTML='';
    }
    validate_scope(); 
}

    function init(){
	var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
	oACDS.queryMatchContains = true;
	var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","f_container", oACDS);
	oAutoComp.minQueryLength = 0; 
	
	


	var ids = ["products","details","company"]; 
	YAHOO.util.Event.addListener(ids, "click", change_block);
	
	YAHOO.util.Event.addListener('save_edit_supplier', "click", save_edit_supplier);
	YAHOO.util.Event.addListener('reset_edit_supplier', "click", reset_edit_supplier);
		
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


	}
YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("filtermenu", function () {
	var oMenu = new YAHOO.widget.Menu("filtermenu", { context:["filter_name0","tr", "br"]  });
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
	YAHOO.util.Event.addListener("filter_name0", "click", oMenu.show, null, oMenu);
    });


YAHOO.util.Event.onContentReady("rppmenu", function () {
	var oMenu = new YAHOO.widget.Menu("rppmenu", { context:["filter_name0","tr", "bl"]  });
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
	YAHOO.util.Event.addListener("paginator_info0", "click", oMenu.show, null, oMenu);
    });


YAHOO.util.Event.onContentReady("filtermenu", function () {
	var oMenu = new YAHOO.widget.Menu("filtermenu", { context:["filter_name0","tr", "br"]  });
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
	YAHOO.util.Event.addListener("filter_name0", "click", oMenu.show, null, oMenu);
    });


YAHOO.util.Event.onContentReady("rppmenu", function () {
	var oMenu = new YAHOO.widget.Menu("rppmenu", { context:["filter_name0","tr", "bl"]  });
	oMenu.render();
	oMenu.subscribe("show", oMenu.focus);
	YAHOO.util.Event.addListener("paginator_info0", "click", oMenu.show, null, oMenu);
    });
