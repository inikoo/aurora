<?php
include_once('common.php');

$location_type=array('Picking'=>_('Picking'),'Storing'=>_('Storing'),'Loading'=>_('Loading'),'Displaying'=>_('Displaying'),'Other'=>_('Other'));




$l='';$ln='';
foreach($location_type as $key=>$value){
    $l.=",'$value'";
    $ln.=",'$key':'$value'";
}
$l=preg_replace('/^,/','',$l);
$ln=preg_replace('/^,/','',$ln);

print 'var location_type_options=['.$l."];\n";
print 'var location_type_name={'.$ln."};\n";

?>
var editing='<?php echo $_SESSION['state']['warehouse']['edit']?>';


var shelf_cols_data=new Object();
var shelf_rows_data=new Object();
var shelf_locations_data=new Object();

var shelf_data ={
    'code':{'dbname':'Shelf Code','name':'shelf_code'}
    ,'type':{'dbname':'Shelf Type Key','name':'shelf_shelf_type_key'}
    ,'rows':{'dbname':'Shelf Number Rows','name':'shelf_rows'}
    ,'cols':{'dbname':'Shelf Number Columns','name':'shelf_columns'}
    ,'warehouse_key':{'dbname':'Shelf Warehouse Key','name':'shelf_warehouse_key'}
    ,'area_key':{'dbname':'Shelf Area Key','name':'shelf_warehouse_area_key'}

    };



function verify_shelf_data(){
return true;
}


function verify_shelf(){

if(verify_shelf_data){
Dom.removeClass('save_shelf','disabled');

}else{
Dom.addClass('save_shelf','disabled');

}

}

function  validate_shelf_warehouse_area(sType,aArgs){
var myAC = aArgs[0]; var elLI = aArgs[1]; var oData = aArgs[2]; 
myAC.getInputEl().value = oData[1]+' ['+oData[2]+']';
Dom.get('shelf_warehouse_area_key').value=oData[0];
}

function  validate_shelf_type(sType,aArgs){

 var myAC = aArgs[0]; var elLI = aArgs[1]; var oData = aArgs[2]; 

	    myAC.getInputEl().value = oData[1]+' ['+oData[3]+']';
 	    //fields : [0"key",1"name",2"description",3"type",4"rows",5"columns",6"l_height",7"l_length",8'l_deep',9'l_weight',10'l_volume']
Dom.get('tr_layout').style.display='';
var rows=parseFloat(oData[4]);
var cols=parseFloat(oData[5]);
var height=oData[6];
var length=oData[7];
var deep=oData[8];
var max_weight=oData[9];
var max_volume=oData[10];

Dom.get('shelf_shelf_type_key').value=oData[0];

Dom.get('shelf_rows').value=rows;
Dom.get('shelf_columns').value=cols;
Dom.get('new_warehouse_shelf_block').innerHTML=oData[11];

shelf_cols_data=new Object();
shelf_rows_data=new Object();
shelf_locations_data=new Object();
 for(i=1;i<rows+1;i++){
 //alert(i)
 shelf_rows_data[i]={'height':height}
 shelf_locations_data[i]=new Object();
  for(j=1;j<cols+1;j++){
   //             {
                if(i==1){
                shelf_cols_data[j]={'length':length}
                }
               shelf_locations_data[i][j]={'Location Shape Type':'Box','Location Height':height,'Location Width':length,'Location Deep':deep,'Location Code':get_location_code(i,j),'Location Max Weight':max_weight,'Location Max Volume':max_volume}
               // alert(i+'_'+j)
            }
 }
//{'height':height,'length':length,'deep':deep,'code':get_location_code(i,j)}
render_shelf_locatons_layout(rows,cols);
verify_shelf();

}


function render_shelf_locatons_layout(rows,cols){

  rows++;
    cols++;
  var oTbl=document.createElement("Table");
       for(i=0;i<rows;i++)
       {
            var  oTR= oTbl.insertRow(i);
               for(j=0;j<cols;j++)
                {
                                var  oTD= oTR.insertCell(j);
                                if(j>0 && i>0 ){
                                oTD.id='shelf_location_layout'+i+'_'+j;
                                var location_data='<b>'+shelf_locations_data[i][j]['Location Code']+'</b>';
                            
                                oTD.innerHTML=location_data;
                                }else if(j==0 && i>0){
                                
                                oTD.id='shelf_location_layout'+i
                                var location_data='<b>'+i+'</b>';
                                location_data+='<br>H:'+shelf_rows_data[i].height;
                                
                                oTD.innerHTML=location_data;
                                Dom.addClass(oTD,'header');
                                Dom.addClass(oTD,'rows_header');
                                }else if(i==0 && j>0){
                                 Dom.addClass(oTD,'header');
                                Dom.addClass(oTD,'cols_header');
                                oTD.id='shelf_location_layout'+j
                                var location_data='<b>'+j+'</b>';
                                location_data+='<br>W:'+shelf_cols_data[j].length;
                                
                                oTD.innerHTML=location_data;
                                
                                }else{
                                Dom.addClass(oTD,'header');

                                }
                }

        }
Dom.get('shelf_locations_layout').appendChild(oTbl);


}


function add_shelf(){
if(Dom.hasClass('add_shelf','disabled')){

return;
}
var values=new Object();
var s_values=new Object();

 for(key in shelf_data )
   s_values[shelf_data[key].dbname]=Dom.get(shelf_data[key].name).value;
  
  values['Shelf Data']=s_values;
  values['Locations Data']=shelf_locations_data;
  
    var json_value = YAHOO.lang.JSON.stringify(values);
    var request='ar_edit_warehouse.php?tipo=new_shelf&values=' + (json_value); 
 // alert(json_value);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.action=='created'){
		    reset_shelf_type_data();
		    var table=tables['table3']
		    var datasource=tables['dataSource3'];
		    
		    datasource.sendRequest('',table.onDataReturnInitializeTable, table);      
		}else{
		    Dom.get('new_warehouse_shelf_type_block').innerHTML=r.msg;
		}
			    

			
	    }
	});


}


 var change_shelf_type_view=function(e){

     var tipo=this.getAttribute('tipo');
      var table=tables['table3'];
      if(tipo=='general'){
	  table.hideColumn('length');
	  table.hideColumn('height');
	  table.hideColumn('deep');
	  table.hideColumn('max_weight');
	  table.hideColumn('max_vol');
	  table.showColumn('type');
	  table.showColumn('description');
	  table.showColumn('rows');
	  table.showColumn('columns');
	  table.showColumn('delete');
	  

	  Dom.get('shelf_type_dimensions_view').className='';
	  Dom.get('shelf_type_general_view').className='selected';
      }else{
	 
	  table.showColumn('length');
	  table.showColumn('height');
	  table.showColumn('deep');
	  table.showColumn('max_weight');
	  table.showColumn('max_vol');
	  table.hideColumn('type');
	  table.hideColumn('description');
	  table.hideColumn('rows');
	  table.hideColumn('columns');
	  table.hideColumn('delete');
	  

	  Dom.get('shelf_type_dimensions_view').className='selected';
	  Dom.get('shelf_type_general_view').className='';
      }
      YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=shelf_types-view&value='+escape(tipo));
  }


YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {


function location_type_formatter(el, oRecord, oColumn, oData){
el.innerHTML =location_type_name[oData];
}
	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var LocationsColumnDefs = [
	    
	       {key:"id", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
	    
	    				       ,{key:"go", label:"", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				       ,{key:"code", label:"<?php echo _('Code')?>", width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},  editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'location'}
				    
				   // ,{key:"tipo", label:"<?php echo _('Used for')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"tipo",formatter:location_type_formatter,label:"<?php echo _('Used for')?>",className:"aleft"
				, editor:new YAHOO.widget.RadioCellEditor({radioOptions:location_type_options,disableBtns:true,asyncSubmitter: CellEdit}),object:'location'
			      }
				    
				    
				    
				    ,{key:"max_weight", label:"<?php echo _('Max Weight')?>",width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},  editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'location'}
                    ,{key:"max_volumen", label:"<?php echo _('Max Vol')?>",width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},  editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'location'}

					 ];
	    //?tipo=locations&tid=0"
	    this.dataSource0 = new YAHOO.util.DataSource("ar_edit_warehouse.php?tipo=locations");
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    rtext:"resultset.rtext",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		fields: [
			 "id"
			 ,"code"
			 
			 ,'max_weight'
			 ,'max_volumen','tipo',"go"
			 ]};
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, LocationsColumnDefs,
								   this.dataSource0
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['locations']['table']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['locations']['table']['order']?>",
									 dir: "<?php echo$_SESSION['state']['locations']['table']['order_dir']?>"
								     },
								     dynamicData : true
								  }
								 );
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table0.filter={key:'<?php echo$_SESSION['state']['locations']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['locations']['table']['f_value']?>'};
	  
	  
	     this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table0.subscribe("cellClickEvent", onCellClick);

	  
	  
	  YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown);
	
	    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var LocationsColumnDefs = [
				       {key:"wa_key", label:"", hidden:true,action:"none",isPrimaryKey:true}
				       ,{key:"code", label:"<?php echo _('Code')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'warehouse_area'}
				       ,{key:"name", label:"<?php echo _('Name')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'warehouse_area'}
				       ,{key:"description", label:"<?php echo _('Description')?>",width:260,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'warehouse_area'}
				    
				       ];
	    //?tipo=locations&tid=0"
	    this.dataSource1 = new YAHOO.util.DataSource("ar_edit_warehouse.php?tipo=warehouse_areas&tableid=1");
	    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    this.dataSource1.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    rtext:"resultset.rtext",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		fields: [
			 "wa_key"
			 ,"code"
			 ,'description'
			 ,'name'
			 ]};
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, LocationsColumnDefs,
								   this.dataSource1
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['warehouse_areas']['table']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['warehouse_areas']['table']['order']?>",
									 dir: "<?php echo$_SESSION['state']['warehouse_areas']['table']['order_dir']?>"
								     },
								     dynamicData : true
								  }
								 );
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table1.filter={key:'<?php echo$_SESSION['state']['warehouse_areas']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['warehouse_areas']['table']['f_value']?>'};
	    YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)	

 
	    this.table1.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table1.subscribe("cellMouseoutEvent", this.table1.onEventUnhighlightCell);
	    this.table1.subscribe("cellClickEvent", onCellClick);



	     var tableid=3; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var LocationsColumnDefs = [
				       {key:"id", label:"", hidden:true,action:"none",isPrimaryKey:true}
				       
				       ,{key:"name", label:"<?php echo _('Name')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'shelf_type'}
				        ,{key:"type", label:"<?php echo _('Type')?>",width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'shelf_type',<?php echo($_SESSION['state']['shelf_types']['view']!='general'?'hidden:true,':'')?>}
				       ,{key:"description", label:"<?php echo _('Description')?>",width:260,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'shelf_type',<?php echo($_SESSION['state']['shelf_types']['view']!='general'?'hidden:true,':'')?>}
				       ,{key:"columns", label:"<?php echo _('Columns')?>",width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'shelf_type',<?php echo($_SESSION['state']['shelf_types']['view']!='general'?'hidden:true,':'')?>}
				       ,{key:"rows", label:"<?php echo _('Rows')?>",width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'shelf_type',<?php echo($_SESSION['state']['shelf_types']['view']!='general'?'hidden:true,':'')?>}
				       ,{key:"length", label:"<?php echo _('Length')?> (m)",width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'shelf_type',<?php echo($_SESSION['state']['shelf_types']['view']=='general'?'hidden:true,':'')?>}
				       ,{key:"height", label:"<?php echo _('Height')?> (m)",width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'shelf_type',<?php echo($_SESSION['state']['shelf_types']['view']=='general'?'hidden:true,':'')?>}
				       ,{key:"deep", label:"<?php echo _('Deep')?> (m)",width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'shelf_type',<?php echo($_SESSION['state']['shelf_types']['view']=='general'?'hidden:true,':'')?>}
				         ,{key:"max_weight", label:"<?php echo _('Max Weight')?> (Kg)",width:120,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'shelf_type',<?php echo($_SESSION['state']['shelf_types']['view']=='general'?'hidden:true,':'')?>}
				       ,{key:"max_vol", label:"<?php echo _('Max Volume')?> (L)",width:120,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC},editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'shelf_type',<?php echo($_SESSION['state']['shelf_types']['view']=='general'?'hidden:true,':'')?>}
				       ];
	    //?tipo=locations&tid=0"
	    this.dataSource3 = new YAHOO.util.DataSource("ar_edit_warehouse.php?tipo=shelf_types&tableid=3");
	    this.dataSource3.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource3.connXhrMode = "queueRequests";
	    this.dataSource3.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    rtext:"resultset.rtext",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		fields: [
			 "id"
			 ,'description'
			 ,'name','rows','columns','deep','length','height','max_vol','max_weight','type','delete'
			 ]};
	    this.table3 = new YAHOO.widget.DataTable(tableDivEL, LocationsColumnDefs,
								   this.dataSource3
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo$_SESSION['state']['shelf_types']['table']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     ,sortedBy : {
									 key: "<?php echo$_SESSION['state']['shelf_types']['table']['order']?>",
									 dir: "<?php echo$_SESSION['state']['shelf_types']['table']['order_dir']?>"
								     },
								     dynamicData : true
								  }
								 );
	    this.table3.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table3.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table3.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table3.filter={key:'<?php echo$_SESSION['state']['shelf_types']['table']['f_field']?>',value:'<?php echo$_SESSION['state']['shelf_types']['table']['f_value']?>'};
	    YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)	

 
	    this.table3.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table3.subscribe("cellMouseoutEvent", this.table3.onEventUnhighlightCell);
	    this.table3.subscribe("cellClickEvent", onCellClick);


	};
    });


function change_block(e){
     if(editing!=this.id){
	
	

	Dom.get('description_block').style.display='none';
	Dom.get('areas_block').style.display='none';
	Dom.get('locations_block').style.display='none';
	Dom.get('shelfs_block').style.display='none';

	Dom.get('shelf_types_block').style.display='none';

	Dom.get('location_types_block').style.display='none';	
	
	Dom.get(this.id+'_block').style.display='';
	Dom.removeClass(editing,'selected');
	
	Dom.addClass(this, 'selected');
	
	YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=warehouse-edit&value='+this.id );
	
	editing=this.id;
    }



}

function show_add_area_dialog(){
Dom.get('new_warehouse_area_block').style.display='';
Dom.get('new_warehouse_area_messages').style.display='';

Dom.get('add_area_here').style.display='none';
Dom.get('close_add_area').style.display='';
Dom.get('save_area').style.display='';


}
function hide_add_area_dialog(){
reset_area_data();
Dom.get('new_warehouse_area_block').style.display='none';
Dom.get('new_warehouse_area_messages').style.display='none';

Dom.get('add_area_here').style.display='';
Dom.get('close_add_area').style.display='none';
Dom.get('save_area').style.display='none';
}

var area_data =new Object;


function get_area_data(){
    area_data['Warehouse Key']=Dom.get('warehouse_key').value;
    area_data['Warehouse Area Name']=Dom.get('area_name').value;
    area_data['Warehouse Area Code']=Dom.get('area_code').value;
    area_data['Warehouse Area Description']=Dom.get('area_description').value;

}

function reset_area_data(){
    Dom.get('warehouse_key').value=Dom.get('warehouse_key').getAttribute('ovalue');
    Dom.get('area_name').value=Dom.get('area_name').getAttribute('ovalue');
    Dom.get('area_code').value=Dom.get('area_code').getAttribute('ovalue');
    Dom.get('area_description').value=Dom.get('area_description').getAttribute('ovalue');

}

function add_area(){
    get_area_data();
  
    var json_value = YAHOO.lang.JSON.stringify(area_data);
    var request='ar_edit_warehouse.php?tipo=new_area&values=' + encodeURIComponent(json_value); 
//alert(request);    
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.action=='created'){
		    reset_area_data();
		    var table=tables['table1']
		    var datasource=tables['dataSource1'];
		    
		    datasource.sendRequest('',table.onDataReturnInitializeTable, table);      
		}else if(r.action=='error'){
		    alert(r.msg);
		}
			    

			
	    }
	});
}

function show_add_shelf_dialog(){
Dom.get('new_warehouse_shelf_block').style.display='';
Dom.get('new_warehouse_shelf_messages').style.display='';

Dom.get('add_shelf').style.display='none';
Dom.get('close_add_shelf').style.display='';
Dom.get('save_shelf').style.display='';


}
function hide_add_shelf_dialog(){
reset_shelf_data();
Dom.get('new_warehouse_shelf_block').style.display='none';
Dom.get('new_warehouse_shelf_messages').style.display='none';

Dom.get('add_shelf').style.display='';
Dom.get('close_add_shelf').style.display='none';
Dom.get('save_shelf').style.display='none';
}

function show_add_shelf_type_dialog(){
Dom.get('new_warehouse_shelf_type_block').style.display='';
Dom.get('new_warehouse_shelf_type_messages').style.display='';

Dom.get('add_shelf_type').style.display='none';
Dom.get('close_add_shelf_type').style.display='';
Dom.get('save_shelf_type').style.display='';


}
function hide_add_shelf_type_dialog(){
reset_shelf_type_data();
Dom.get('new_warehouse_shelf_type_block').style.display='none';
Dom.get('new_warehouse_shelf_type_messages').style.display='none';

Dom.get('add_shelf_type').style.display='';
Dom.get('close_add_shelf_type').style.display='none';
Dom.get('save_shelf_type').style.display='none';
}

var shelf_type_data =new Object;

function get_shelf_type_data(){
   
    shelf_type_data['Shelf Type Name']=Dom.get('shelf_type_name').value;
    shelf_type_data['Shelf Type Description']=Dom.get('shelf_type_description').value;
    shelf_type_data['Shelf Type Type']=Dom.get('shelf_type_type').value;
    shelf_type_data['Shelf Type Rows']=Dom.get('shelf_type_rows').value;
    shelf_type_data['Shelf Type Columns']=Dom.get('shelf_type_columns').value;
    shelf_type_data['Shelf Type Location Length']=Dom.get('shelf_type_length').value;
    shelf_type_data['Shelf Type Location Deep']=Dom.get('shelf_type_deep').value;
    shelf_type_data['Shelf Type Location Height']=Dom.get('shelf_type_height').value;
    shelf_type_data['Shelf Type Location Max Weight']=Dom.get('shelf_type_weight').value;
    shelf_type_data['Shelf Type Location Max Volume']=Dom.get('shelf_type_volume').value;

}

function reset_shelf_type_data(){
    Dom.get('warehouse_key').value=Dom.get('warehouse_key').getAttribute('ovalue');
    Dom.get('shelf_type_name').value=Dom.get('shelf_type_name').getAttribute('ovalue');
    Dom.get('shelf_type_description').innerHTML=Dom.get('shelf_type_description').getAttribute('ovalue');
    Dom.get('shelf_type_rows').value=Dom.get('shelf_type_rows').getAttribute('ovalue');
    Dom.get('shelf_type_columns').value=Dom.get('shelf_type_columns').getAttribute('ovalue');
    Dom.get('shelf_type_deep').value=Dom.get('shelf_type_deep').getAttribute('ovalue');
    Dom.get('shelf_type_height').value=Dom.get('shelf_type_height').getAttribute('ovalue');
    Dom.get('shelf_type_length').value=Dom.get('shelf_type_length').getAttribute('ovalue');
    Dom.get('shelf_type_weight').value=Dom.get('shelf_type_weight').getAttribute('ovalue');
    Dom.get('shelf_type_volume').value=Dom.get('shelf_type_volume').getAttribute('ovalue');
    Dom.get('shelf_type_type').value=Dom.get('shelf_type_type').getAttribute('ovalue');
    swap_this_radio(Dom.get('radio_shelf_type_'+Dom.get('shelf_type_type').getAttribute('ovalue')))

}

function add_shelf_type(){
    get_shelf_type_data();
  
    var json_value = YAHOO.lang.JSON.stringify(shelf_type_data);
    var request='ar_edit_warehouse.php?tipo=new_shelf_type&values=' + encodeURIComponent(json_value); 
    //alert(request);    
    // return;
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.action=='created'){
		    reset_shelf_type_data();
		    var table=tables['table3']
		    var datasource=tables['dataSource3'];
		    
		    datasource.sendRequest('',table.onDataReturnInitializeTable, table);      
		}else{
		    Dom.get('new_warehouse_shelf_type_block').innerHTML=r.msg;
		}
			    

			
	    }
	});
}


function get_location_code(row,col){
  var shelf_code=Dom.get('shelf_code').value;
    return shelf_code+'.'+col+'.'+row;
}



 function init(){
     var Dom   = YAHOO.util.Dom;
     var ids = ["description","areas","locations","shelfs","shelf_types","location_types"]; 
     YAHOO.util.Event.addListener(ids, "click", change_block);
     var ids = ["add_area","add_area_here"]; 
     YAHOO.util.Event.addListener(ids, "click", show_add_area_dialog);
    var ids = ["add_shelf"]; 
     YAHOO.util.Event.addListener(ids, "click", show_add_shelf_dialog);
     YAHOO.util.Event.addListener('save_shelf', "click", add_shelf);


     YAHOO.util.Event.addListener('save_area', "click", add_area);
     YAHOO.util.Event.addListener('close_add_area', "click",hide_add_area_dialog );

     YAHOO.util.Event.addListener('add_shelf_type', "click", show_add_shelf_type_dialog);
     YAHOO.util.Event.addListener('save_shelf_type', "click", add_shelf_type);
     YAHOO.util.Event.addListener('close_add_shelf_type', "click",hide_add_shelf_type_dialog );

     var ids=Dom.getElementsByClassName('radio', 'span', 'shelf_type_type_container');
     YAHOO.util.Event.addListener(ids, "click", swap_radio,'shelf_type_type');


     var ids=['shelf_type_general_view','shelf_type_dimensions_view'];
     YAHOO.util.Event.addListener(ids, "click",change_shelf_type_view);


  var oDS = new YAHOO.util.XHRDataSource("ar_warehouse.php");
 	oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
 	oDS.responseSchema = {
 	    resultsList : "data",
 	    fields : ["key","name","description","type","rows","columns","l_height","l_length",'l_deep','l_weight','l_volume','info']
 	};
 	var oAC = new YAHOO.widget.AutoComplete("shelf_shelf_type", "shelf_shelf_type_Container", oDS);
 	
 	oAC.generateRequest = function(sQuery) {
 	    return "?tipo=find_shelf_type&query=" + sQuery ;
 	};
	oAC.forceSelection = true; 

	oAC.itemSelectEvent.subscribe(validate_shelf_type); 

    oAC.formatResult = function(oResultData, sQuery, sResultMatch) {
	  return oResultData[1]+' ['+oResultData[3]+']'
	};



 var oShelfDS = new YAHOO.util.XHRDataSource("ar_warehouse.php");
 	oShelfDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
 	oShelfDS.responseSchema = {
 	    resultsList : "data",
 	    fields : ["key","code","name"]
 	};
 	var oShelfAC = new YAHOO.widget.AutoComplete("shelf_warehouse_area", "shelf_warehouse_area_Container", oShelfDS);
 	
 	oShelfAC.generateRequest = function(sQuery) {
 	//alert("?tipo=find_warehouse_area&parent_key="+Dom.get('shelf_warehouse_area_key').value+"&query=" + sQuery)
 	    return "?tipo=find_warehouse_area&parent_key="+Dom.get('shelf_warehouse_area_key').value+"&query=" + sQuery ;
 	};
	oShelfAC.forceSelection = true; 

	oShelfAC.itemSelectEvent.subscribe(validate_shelf_warehouse_area); 

    oShelfAC.formatResult = function(oResultData, sQuery, sResultMatch) {
	  return oResultData[1]+' ['+oResultData[2]+']'
	};




 var oACDS0 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS0.queryMatchContains = true;
 var oAutoComp0 = new YAHOO.widget.AutoComplete("f_input0","f_container0", oACDS0);
 oAutoComp0.minQueryLength = 0; 




//YAHOO.util.Event.addListener('submit_advanced_search', "click",submit_advanced_search);
 }

YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.Menu("filtermenu0", { context:["filter_name0","tr", "br"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("filter_name0", "click", oMenu.show, null, oMenu);
    });


YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.Menu("rppmenu0", { context:["filter_name0","tr", "bl"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("paginator_info0", "click", oMenu.show, null, oMenu);
    });

 var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS1.queryMatchContains = true;
 var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1","f_container1", oACDS1);
 oAutoComp1.minQueryLength = 0; 

YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.Menu("filtermenu1", { context:["filter_name1","tr", "br"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("filter_name1", "click", oMenu.show, null, oMenu);
    });


YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 var oMenu = new YAHOO.widget.Menu("rppmenu1", { context:["filter_name1","tr", "bl"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("paginator_info1", "click", oMenu.show, null, oMenu);
    });

 

