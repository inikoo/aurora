<?
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../common.php');?>
var Event = YAHOO.util.Event;
var Dom   = YAHOO.util.Dom;
var family_id=<?=$_SESSION['state']['family']['id']?>;
editing='description';




 var CellEdit = function (callback, newValue) {


		var record = this.getRecord(),
		column = this.getColumn(),
		oldValue = this.value,
		datatable = this.getDataTable(),
		recordIndex = datatable.getRecordIndex(record);

		YAHOO.util.Connect.asyncRequest(
						'POST',
						'ar_edit_assets.php', {
						    success:function(o) {
							//	alert(o.responseText);
							var r = YAHOO.lang.JSON.parse(o.responseText);
							if (r.state == 200) {

							    if(column.key=='price' || column.key=='unit_price' || column.key=='margin' ){
								var data = record.getData();
								
								data['price']=r.newvalue.price;
								data['unit_price']=r.newvalue.unit_price;
								data['margin']=r.newvalue.margin;
								datatable.updateRow(recordIndex,data);
								callback(true);
								
							    }else if(column.key=='unit_rrp'  ){
								var data = record.getData();
								
								data['unit_rrp']=r.newvalue.unit_rrp;
								data['rrp_info']=r.newvalue.rrp_info;
								datatable.updateRow(recordIndex,data);
								callback(true);
								
							    }else
								callback(true, r.newvalue);
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
var description_num_changed=0;
var description_warnings= new Object();
var description_errors= new Object();



  var change_view=function(e){
	
	var table=tables['table0'];
	var tipo=this.id;
	//	alert(table.view+' '+tipo)
	if(table.view!=tipo){
	    table.hideColumn('name');
	    table.hideColumn('famsdescription');
	    table.hideColumn('sdescription');
	    table.hideColumn('units');
	    table.hideColumn('units_info');
	    table.hideColumn('price_info');
	    table.hideColumn('price');
	    table.hideColumn('unit_rrp');
	    table.hideColumn('rrp_info');

	    table.hideColumn('unit_type');
	    table.hideColumn('unit_price');
	    table.hideColumn('margin');

	    table.hideColumn('processing');
	    table.hideColumn('sales_state');
	    table.hideColumn('web_state');
	    table.hideColumn('state_info');


	    if(tipo=='view_name'){
		
		table.showColumn('name');
		table.showColumn('famsdescription');	
		table.showColumn('sdescription');	

	    }
	    else if(tipo=='view_units'){
		
		table.showColumn('units');
		table.showColumn('unit_type');

	    }
	     else if(tipo=='view_state'){
		
		table.showColumn('processing');
		table.showColumn('sales_state');
		table.showColumn('web state');
		table.showColumn('state_info');


	    }
	    
	    else if(tipo=='view_price'){

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
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=family-edit_view&value=' + escape(tipo) );
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


function create_part(){
    var part_description=Dom.get('new_name').value;
    if(part_description=='')
	part_description='??';
    var part_used_in=Dom.get('new_code').value;
    if(part_used_in=='')
	part_used_in='??';


    var data={sku:'TBC',description:part_description,usedin:part_used_in,partsperpick:1,notes:'',delete:'<img src="art/icons/cross.png">'}
    tables.table1.addRow(data, 0);
}

function edit_family_changed(o){
    var ovalue=o.getAttribute('ovalue');
    var name=o.name;
    if(ovalue!=o.value){
	if(name=='code'){
	    if(o.value==''){
		description_errors.code="<?=_("The family code can not be empty")?>";
	    }else if(o.value.lenght>16){
		description_errors.code="<?=_("The product code can not have more than 16 characters")?>";
	    }else
		delete description_errors.code;
	}
	if(name=='name'){
	    if(o.value==''){
		description_errors.name="<?=_("The family name  can not be empty")?>";
	    }else if(o.value.lenght>255){
		description_errors.name="<?=_("The product code can not have more than 255  characters")?>";
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

	description_num_changed=0;
	Dom.get(editing+'_save').style.display='none';
	Dom.get(editing+'_reset').style.display='none';

	Dom.get(editing+'_num_changes').innerHTML=description_num_changed;
	description_warnings= new Object();
	description_errors= new Object();
	
    }
    update_form();
}

function save(tipo){

    if(tipo=='description'){
	var keys=new Array("code","name");
	for (x in keys)
	    {
		 key=keys[x];
		 element=Dom.get(key);
		if(element.getAttribute('changed')==1){

		    newValue=element.value;
		    oldValue=element.getAttribute('ovalue');
		    
		    var request='ar_edit_assets.php?tipo=edit_family&key=' + key+ '&newvalue=' + 
			encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue)+ 
			'&id='+family_id;

		    YAHOO.util.Connect.asyncRequest('POST',request ,{
			    success:function(o) {
				//alert(o.responseText);
				var r =  YAHOO.lang.JSON.parse(o.responseText);
				if(r.state==200){
				    var element=Dom.get(r.key);
				    element.getAttribute('ovalue',r.newvalue);
				    element.value=r.newvalue;
				    element.setAttribute('changed',0);
				    description_num_changed--;
				 
				}else{
				    Dom.get('description_errors').innerHTML='<span class="error">'+r.msg+'</span>';
				    
				}
				update_form();	
			    }
			    
			});
		}
	    }
	
    }

}


function new_family_changed(o){
    if(Dom.get("new_code").value!='' && Dom.get("new_name").value!=''){
	Dom.get("add_new_family").style.display='';
    }else
	Dom.get("add_new_family").style.display='none';
}


function save_new_family(){

    var msg_div='add_family_messages';

    var code=Dom.get('new_code').value;
    var name=Dom.get('new_name').value;
    var description=Dom.get('new_description').innerHTML;
    var request='ar_edit_assets.php?tipo=new_family&code='+encodeURIComponent(code)+'&name='+encodeURIComponent(name)+'&description='+encodeURIComponent(name);
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




YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {




	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"id", label:"", hidden:true,action:"none",isPrimaryKey:true}
				    ,{key:"code", label:"<?=_('Code')?>", width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"units_info",<?=($_SESSION['state']['family']['edit_view']=='view_price'?'':'hidden:true,')?> label:"<?=_('Units')?>", width:60,className:"aleft"}
				    
				    ,{key:"name", label:"<?=_('Name')?>",<?=($_SESSION['state']['family']['edit_view']=='view_name'?'':'hidden:true,')?>width:220, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product'}
				    ,{key:"processing", label:"<?=_('Editing State')?>",<?=($_SESSION['state']['family']['edit_view']=='view_state'?'':'hidden:true,')?>width:220, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},object:'product',editor: new YAHOO.widget.RadioCellEditor({asyncSubmitter: CellEdit,radioOptions:["<?=_('Editing')?>","<?=_('Live')?>"],disableBtns:true})}
				    ,{key:"sales_state", label:"<?=_('Sale State')?>",<?=($_SESSION['state']['family']['edit_view']=='view_state'?'':'hidden:true,')?>width:220, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},object:'product',editor: new YAHOO.widget.RadioCellEditor({asyncSubmitter: CellEdit,radioOptions:["<?=_('For Sale')?>","<?=_('Discontinue')?>","<?=_('Not For Sale')?>"],disableBtns:true})}
				    ,{key:"web_state", label:"<?=_('Web State')?>",<?=($_SESSION['state']['family']['edit_view']=='view_state'?'':'hidden:true,')?>width:220, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},object:'product',editor: new YAHOO.widget.RadioCellEditor({asyncSubmitter: CellEdit,radioOptions:["<?=_('Auto')?>","<?=_('Sale')?>","<?=_('Out of Stock')?>","<?=_('Hide')?>","<?=_('Offline')?>"],disableBtns:true})}



				    ,{key:"famsdescription", label:"<?=_('Fam Short Desc')?>",<?=($_SESSION['state']['family']['edit_view']=='view_name'?'':'hidden:true,')?>width:190, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product'}
				    ,{key:"sdescription", label:"<?=_('Short Desc')?>",<?=($_SESSION['state']['family']['edit_view']=='view_name'?'':'hidden:true,')?>width:190, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product'}
				    ,{key:"units", label:"<?=_('Units')?>",<?=($_SESSION['state']['family']['edit_view']=='view_units'?'':'hidden:true,')?>width:40, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product'}
				    ,{key:"unit_type", label:"<?=_('Unit Type')?>",<?=($_SESSION['state']['family']['edit_view']=='view_units'?'':'hidden:true,')?>width:80, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product'}
				    ,{key:"price", label:"<?=_('Price')?>",<?=($_SESSION['state']['family']['edit_view']=='view_price'?'':'hidden:true,')?>width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product'}
				    ,{key:"unit_price", label:"<?=_('U Price')?>",<?=($_SESSION['state']['family']['edit_view']=='view_price'?'':'hidden:true,')?>width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product'}
				    ,{key:"margin", label:"<?=_('Margin')?>",<?=($_SESSION['state']['family']['edit_view']=='view_price'?'':'hidden:true,')?>width:80, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product'}

				    ,{key:"price_info", label:"<?=_('Price Notes')?>",<?=($_SESSION['state']['family']['edit_view']=='view_price'?'':'hidden:true,')?>width:120, sortable:false,className:"aleft"}
				    ,{key:"unit_rrp", label:"<?=_('Unit RRP')?>",<?=($_SESSION['state']['family']['edit_view']=='view_price'?'':'hidden:true,')?>width:80, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'product'}
				    ,{key:"rrp_info", label:"<?=_('RRP Notes')?>",<?=($_SESSION['state']['family']['edit_view']=='view_price'?'':'hidden:true,')?>width:120, sortable:false,className:"aleft"}
				    //,{key:"delete", label:"",width:100,className:"aleft",action:"delete",object:'product'}
				    //,{key:"delete_type", label:"",hidden:true,isTypeKey:true}

				     ];

	    this.dataSource0 = new YAHOO.util.DataSource("ar_edit_assets.php?tipo=edit_products&parent=family");
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
			 "code","units_info",
			 "name",
			 'delete','delete_type','id','sdescription','famsdescription','price','unit_rrp','units','unit_type','rrp_info','price_info','unit_price','margin','processing','sales_state','sales_state','web_state'
			 ]};
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:<?=$_SESSION['state']['family']['table']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?=_('Page')?> {currentPage} <?=_('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "<?=$_SESSION['state']['family']['table']['order']?>",
									 dir: "<?=$_SESSION['state']['family']['table']['order_dir']?>"
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
	    
	    this.table0.view='<?=$_SESSION['state']['family']['edit_view']?>';

		

	    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"sku", label:"SKU", width:100, action:"none",isPrimaryKey:true}
				    ,{key:"description", label:"<?=_('Description')?>", width:220,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"usedin", label:"<?=_('Used in')?>",width:100, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"partsperpick", label:"<?=_('Parts/Pick')?>",width:70,className:"aleft"}
				    ,{key:"notes", label:"<?=_('Notes to Pickers')?>",width:180,className:"aleft"}
				    ,{key:"delete", label:"",width:20,className:"aleft",action:"delete",object:'tmp_partlist'}



				     ];

	    this.dataSource1 = new YAHOO.util.DataSource(YAHOO.util.Dom.get("table_parts_list")); 
	    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_HTMLTABLE; 
	    this.dataSource1.responseSchema = {
		fields: [
			 "sku",
			 "description",
			 'usedin','partsperpick','notes','delete'
			 ]};
	    
	    this.table1 = new YAHOO.widget.DataTable("parts_list_container", OrdersColumnDefs,
						     this.dataSource1, {
								     sortedBy : {
									 key: "sku",
									 dir: "desc"
								     },
								     MSG_EMPTY:"<?=_('Please assign a part')?>"
						     }
						     );



	    this.table1.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table1.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table1.subscribe("cellClickEvent", tmponCellClick);
	    




	};
    });

function init(){
 var Event = YAHOO.util.Event;
    var Dom   = YAHOO.util.Dom;
    
    function mygetTerms(query) {multireload();};
    var oACDS = new YAHOO.widget.DS_JSFunction(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","filtercontainer0", oACDS);
    oAutoComp.minQueryLength = 0; 
    


    


}

YAHOO.util.Event.onDOMReady(init);

 ids=['view_name','view_price','view_state'];
 YAHOO.util.Event.addListener(ids, "click",change_view)



var dmenu_data;
YAHOO.util.Event.onContentReady("dmenu_input", function () {


	var oDS = new YAHOO.util.XHRDataSource("ar_assets.php");
 	oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
 	oDS.responseSchema = {
 	    resultsList : "data",
 	    fields : ["info","sku","description","usedin"]
 	};
 	var oAC = new YAHOO.widget.AutoComplete("dmenu_input", "dmenu_container", oDS);
 	oAC.generateRequest = function(sQuery) {

 	    return "?tipo=part_search&query=" + sQuery ;
 	};

  	var myHandler = function(sType, aArgs) {

  	    dmenu_data = aArgs[2];

  	};
  	oAC.itemSelectEvent.subscribe(myHandler);




 	oAC.forceSelection = true; 
 	oAC.itemSelectEvent.subscribe(dmenu_selected); 
    });

var dmenu_selected=function(){
     var data = {
	"sku":dmenu_data[1]
	,"description":dmenu_data[2]
	,"usedin":dmenu_data[3]
    }; 
          Dom.get("dmenu_input").value='';

     var row={sku:data.sku,description:data.description,usedin:data.usedin,partsperpick:1,notes:'',delete:'<img src="art/icons/cross.png">'}
     tables.table1.addRow(row, 0);

     alert(tables.table1);
}