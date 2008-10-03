<?

include_once('../common.php');
// if(!(isset($_REQUEST['departments']) and is_numeric($_REQUEST['departments'])))
//     exit();


// $options=$_SESSION['tables']['departments_list'];

// $products=$_REQUEST['departments'];
// $products_perpage=($options[2]=='all'?$products:$options[2]);
// $products_offset=$options[3];
// $products_order=$options[0];
// $products_order_dir=$options[1];

?>


YAHOO.namespace ("departments"); 

function xmysort (oColumn) {

    // alert(this);
    if(oColumn.key === this.get("sortedBy").key) {
	sDir = (this.get("sortedBy").dir === YAHOO.widget.DataTable.CLASS_ASC) ?
	    YAHOO.widget.DataTable.CLASS_DESC : YAHOO.widget.DataTable.CLASS_ASC;
    }else
	sDir = oColumn.sortOptions.defaultDir;

    


   var newrequest="&sf=0&o="+oColumn.key+"&od="+sDir;

    // Dom.get('paginatormenurender0').innerHTML=newrequest;

    
   var oCallback = {
       success: this.onDataReturnInitializeTable,
       failure: this.onDataReturnInitializeTable,
       scope: this,
       argument: {
	    // Pass in sort values so UI can be updated in callback function
	   sorting: {
	       key: oColumn.key,
	       dir: sDir
	   }
       }
   }
   
  this.getDataSource().sendRequest(newrequest,oCallback);    
   //alert(this.id);
   
  //  var datatable=YAHOO.departments.XHR_JSON.DepartmentsDataTable; 
//    datatable.getDataSource().sendRequest(newrequest,oCallback);  
//    var datatable=YAHOO.departments.XHR_JSON.Departments1DataTable; 
//    datatable.getDataSource().sendRequest(newrequest,oCallback);  
//    var datatable=YAHOO.departments.XHR_JSON.Departments2DataTable; 
//    datatable.getDataSource().sendRequest(newrequest,oCallback);  

};

function xreload(){


    var Dom = YAHOO.util.Dom;
    document.getElementById('loadingicon0').style.visibility='visible'; 


    if(Dom.get('f_field0')==null)
	var extra='';
    else
    	var extra="&f_field="+Dom.get('f_field0').value+"&f_value="+Dom.get('f_input0').value;

	
    var newrequest="&sf="+this.offset+"&nr="+this.recordsperPage+"&o="+this.get("sortedBy").key+"&od="+this.get("sortedBy").dir+extra;

     var datatable=YAHOO.departments.XHR_JSON.DepartmentsDataTable; 
     var datasource=datatable.getDataSource();
     datasource.sendRequest(newrequest,{success:datatable.onDataReturnInitializeTable,scope:datatable} ,datatable);    
    var datatable=YAHOO.departments.XHR_JSON.Departments1DataTable; 
    var datasource=datatable.getDataSource();
    datasource.sendRequest(newrequest,{success:datatable.onDataReturnInitializeTable,scope:datatable} ,datatable);    
     var datatable=YAHOO.departments.XHR_JSON.Departments2DataTable; 
     var datasource=datatable.getDataSource();
     datasource.sendRequest(newrequest,{success:datatable.onDataReturnInitializeTable,scope:datatable} ,datatable);    

}



YAHOO.util.Event.addListener(window, "load", function() {
    YAHOO.departments.XHR_JSON = new function() {

	    
	    this.departmentLink=  function(el, oRecord, oColumn, oData) {
		var url="assets_department.php?id="+oRecord.getData("id");
		el.innerHTML = oData.link(url);
	    }
	    


	     //START OF THE TABLE=========================================================================================================================

	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    
	    var DepartmentsColumnDefs = [
					 // {key:"name", label:"<?=_('Name')?>", sortable:true,editor:"textbox",className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
					 //	 {key:"id", label:"<?=_('Id')?>", width:30,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 {key:"name", label:"<?=_('Name')?>", width:200,sortable:true,formatter:this.departmentLink,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					 ,{key:"families", label:"<?=_('Families')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ,{key:"active", label:"<?=_('Products')?>",  width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}



					 ];
	    this.DepartmentsDataSource = new YAHOO.util.DataSource("ar_assets.php?tipo=index&tid="+tableid);
	    this.DepartmentsDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.DepartmentsDataSource.connXhrMode = "queueRequests";
	    this.DepartmentsDataSource.responseSchema = {resultsList: "resultset.data", fields: [
												 'id',
												 "name",
	    'families',
	    'active',"tsall","tsq","tsy","tsm"
												 ]};



	    this.DepartmentsDataSource.doBeforeCallback = mydoBeforeCallback;
	    this.DepartmentsDataTable = new YAHOO.widget.DataTable
		(tableDivEL, DepartmentsColumnDefs,this.DepartmentsDataSource, {renderLoopSize: 50, 
										sortedBy: {key:"<?=$_SESSION['tables']['departments_list'][0]?>", dir:"<?=$_SESSION['tables']['departments_list'][1]?>"}} );
	    
	    this.DepartmentsDataTable.paginatorMenu = new YAHOO.widget.Menu('paginatornewmenu'+tableid,  {context:['paginatormenuselector'+tableid,"tr", "br"]  });
	    this.DepartmentsDataTable.paginatorMenu.addItems([{ text: "25", onclick:{fn:changeRecordsperPage,obj:25,scope:this.DepartmentsDataTable}  } ]);
	    this.DepartmentsDataTable.paginatorMenu.addItems([{ text: "50", onclick:{fn:changeRecordsperPage,obj:50,scope:this.DepartmentsDataTable}  } ]);
	    this.DepartmentsDataTable.paginatorMenu.addItems([{ text: "100", onclick:{fn:changeRecordsperPage,obj:100,scope:this.DepartmentsDataTable}  } ]);
	    this.DepartmentsDataTable.paginatorMenu.addItems([{ text: "250", onclick:{fn:changeRecordsperPage,obj:250,scope:this.DepartmentsDataTable}  } ]);
	    this.DepartmentsDataTable.paginatorMenu.addItems([{ text: "500", onclick:{fn:changeRecordsperPage,obj:500,scope:this.DepartmentsDataTable}  } ]);
	    this.DepartmentsDataTable.paginatorMenu.addItems([{ text: "all", onclick:{fn:changeRecordsperPage,obj:'all',scope:this.DepartmentsDataTable}  } ]);
	    YAHOO.util.Event.addListener('paginatormenuselector'+tableid, "click", this.DepartmentsDataTable.paginatorMenu.show, null, this.DepartmentsDataTable.paginatorMenu);
	    this.DepartmentsDataTable.paginatorMenu.render(document.body);
	    this.DepartmentsDataTable.filterMenu = new YAHOO.widget.Menu('filternewmenu'+tableid,  {context:['filterselector'+tableid,"tr", "br"]  });
	    this.DepartmentsDataTable.filterMenu.addItems([{ text: "<?=_('Family Code')?>", onclick:{fn:changeFilter,obj:{col:'name',text:"<?=_('Family Code')?>"},scope:this.DepartmentsDataTable}  } ]);
	    this.DepartmentsDataTable.filterMenu.addItems([{ text: "<?=_('Description')?>", onclick:{fn:changeFilter,obj:{col:'description',text:"<?=_('Description')?>"},scope:this.DepartmentsDataTable}  } ]);
	    YAHOO.util.Event.addListener('filterselector'+tableid, "click", this.DepartmentsDataTable.filterMenu.show, null, this.DepartmentsDataTable.filterMenu);
	    this.DepartmentsDataTable.filterMenu.render(document.body);

	    this.DepartmentsDataTable.myreload=xreload;
	    this.DepartmentsDataTable.sortColumn = mysort;
	    this.DepartmentsDataTable.id=tableid;
	    this.DepartmentsDataTable.editmode=false;
	    //	    this.DepartmentsDataTable.subscribe("initEvent", dataReturn); 
	    YAHOO.util.Event.addListener('paginator_next'+tableid, "click", nextpage, this.DepartmentsDataTable); 
	    YAHOO.util.Event.addListener('paginator_prev'+tableid, "click", prevpage, this.DepartmentsDataTable); 
	    YAHOO.util.Event.addListener('hidder'+tableid, "click", showtable, this.DepartmentsDataTable); 
	    YAHOO.util.Event.addListener('resetfilter'+tableid, "click", resetfilter, this.DepartmentsDataTable); 

	    this.DepartmentsDataTable.subscribe("cellClickEvent", this.DepartmentsDataTable.onEventShowCellEditor);
	    






	    //__You shouls not change anything from here


// 	    var newrequest="";
// 	    //	    alert(newrequest);
// 	    this.DepartmentsDataTable = new YAHOO.widget.DataTable(tableDivEL, DepartmentsColumnDefs,
// 								   this.DepartmentsDataSource, {
// 								       initialRequest:newrequest
// 								       ,renderLoopSize: 50
// 								   }
// 								   );

	    

//  	this.DepartmentsDataTable.paginatorMenu = new YAHOO.widget.Menu('paginatornewmenu'+tableid,  {context:['paginatormenuselector'+tableid,"tr", "br"]  });
// 	this.DepartmentsDataTable.paginatorMenu.addItems([{ text: "25", onclick:{fn:changeRecordsperPage,obj:25,scope:this.DepartmentsDataTable}  } ]);
// 	this.DepartmentsDataTable.paginatorMenu.addItems([{ text: "50", onclick:{fn:changeRecordsperPage,obj:50,scope:this.DepartmentsDataTable}  } ]);
// 	this.DepartmentsDataTable.paginatorMenu.addItems([{ text: "100", onclick:{fn:changeRecordsperPage,obj:100,scope:this.DepartmentsDataTable}  } ]);
// 	this.DepartmentsDataTable.paginatorMenu.addItems([{ text: "250", onclick:{fn:changeRecordsperPage,obj:250,scope:this.DepartmentsDataTable}  } ]);
// 	this.DepartmentsDataTable.paginatorMenu.addItems([{ text: "500", onclick:{fn:changeRecordsperPage,obj:500,scope:this.DepartmentsDataTable}  } ]);
// 	    this.DepartmentsDataTable.paginatorMenu.addItems([{ text: "all", onclick:{fn:changeRecordsperPage,obj:'all',scope:this.DepartmentsDataTable}  } ]);




// 	    YAHOO.util.Event.addListener('paginatormenuselector'+tableid, "click", this.DepartmentsDataTable.paginatorMenu.show, null, this.DepartmentsDataTable.paginatorMenu);
// 	    this.DepartmentsDataTable.paginatorMenu.render(document.body);

	    
 	    this.DepartmentsDataTable.subscribe("initEvent", dataReturn); 

// 	    this.FamiliesDataTable.myreload=xreload;
// 	    this.FamiliesDataTable.sortColumn = mysort;
// 	    this.FamiliesDataTable.subscribe("initEvent", dataReturn); 

// 	    this.DepartmentsDataTable.id=tableid;
	    
// 	    this.DepartmentsDataTable.editmode=false;
// 	    this.DepartmentsDataSource.doBeforeCallback = mydoBeforeCallback;
// 	    this.DepartmentsDataTable.subscribe("cellClickEvent", this.DepartmentsDataTable.onEventShowCellEditor);


	    



	    
	    this.mySaveEditor = function (){
		
		

		if(this._oCellEditor.isActive) {
 		    var newData = this._oCellEditor.value;
 		    var oldData = YAHOO.widget.DataTable._cloneObject(this._oCellEditor.record.getData(this._oCellEditor.column.key));
		}

// 		this._oRecordSet.updateRecordValue(this._oCellEditor.record, this._oCellEditor.column.key, this._oCellEditor.value);
// 		this.formatCell(this._oCellEditor.cell.firstChild);
// 		this._syncColWidths(false);
		
		var request='ar_assets.php?tipo=update_department_name&value=' + escape(newData) + '&id=' + escape(this._oCellEditor.record.getData("id"));

		YAHOO.util.Connect.asyncRequest(
						'POST',
						request,{
						    success: function (o) {

							var r =  YAHOO.lang.JSON.parse(o.responseText);
							if (r.state == 200) {
							     this._oRecordSet.updateRecordValue(this._oCellEditor.record, this._oCellEditor.column.key, this._oCellEditor.value);
							     this.formatCell(this._oCellEditor.cell.firstChild);
							     this._syncColWidths(false);
							     this.resetCellEditor();
							}else{
							    alert(r.resp);
							}
							
						    },
							failure: function(o) {alert("Error")},
							scope: this
						}
						);
	    }

		this.DepartmentsDataTable.saveCellEditor =this.mySaveEditor;



	      var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    
	    var Departments1ColumnDefs = [

					  {key:"name", label:"<?=_('Name')?>", width:150,sortable:true,formatter:this.departmentLink,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					 ,{key:"stock_value", label:"<?=$myconf['currency_symbol'].' '._('Stock')?>",  width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ,{key:"active", label:"<?=_('Products')?>",width:80, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ,{key:"outofstock", label:"<?=_('O of S')?>",width:80, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 ,{key:"stockerror", label:"<?=_('Error in S')?>",width:80, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				       ];
	    
	    this.Departments1DataSource = new YAHOO.util.DataSource("ar_assets.php?tipo=index&tid="+tableid);
	    this.Departments1DataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.Departments1DataSource.connXhrMode = "queueRequests";
	    this.Departments1DataSource.responseSchema = {
		resultsList: "resultset.data", 
		totalRecords: 'resultset.total_records',
		fields: [
			 "id","name","stock_value","active","outofstock","stockerror"
			 ]};

	    //	    this.Departments1DataSource.doBeforeCallback = mydoBeforeCallback;
	    this.Departments1DataTable = new YAHOO.widget.DataTable(tableDivEL, Departments1ColumnDefs,this.Departments1DataSource, {renderLoopSize: 50,
																     sortedBy: {key:"<?=$_SESSION['tables']['departments_list'][0]?>", dir:"<?=$_SESSION['tables']['departments_list'][1]?>"}});

	    this.Departments1DataTable.sortColumn = mysort;
	    this.Departments1DataTable.id=tableid;


	    var tableid=2; // Change if you have more the 2 table
	    var tableDivEL="table"+tableid;

	    
	    var Departments2ColumnDefs = [

					  {key:"name", label:"<?=_('Name')?>", width:150, sortable:true,formatter:this.departmentLink,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}} 
					  ,{key:"tsall", label:"<?=_('T Sales')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					  ,{key:"tsy", label:"<?=_('S (1y)')?>",width:80, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					  ,{key:"tsq", label:"<?=_('S (1q)')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					  ,{key:"tsm", label:"<?=_('S (1m)')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					  ,{key:"awtsq", label:"<?=_('Avg S(w)')?>", width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}


				      ];
	    
	    this.Departments2DataSource = new YAHOO.util.DataSource("ar_assets.php?tipo=index&tid="+tableid);
	    this.Departments2DataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.Departments2DataSource.connXhrMode = "queueRequests";
	    this.Departments2DataSource.responseSchema = {
		resultsList: "resultset.data", 
		totalRecords: 'resultset.total_records',
		fields: [
			 "id","name","actove","tsall","tsy","tsq","tsm","awtsq"
			 ]};

	    //	    this.Departments2DataSource.doBeforeCallback = mydoBeforeCallback;
	    this.Departments2DataTable = new YAHOO.widget.DataTable(tableDivEL, Departments2ColumnDefs,this.Departments2DataSource, {renderLoopSize: 50
																     ,sortedBy: {key:"<?=$_SESSION['tables']['departments_list'][0]?>", dir:"<?=$_SESSION['tables']['departments_list'][1]?>"}}
																     
		);
	    this.Departments2DataTable.sortColumn = mysort;
	    this.Departments2DataTable.id=tableid;
	    //	    this.Departments2DataTable.subscribe("initEvent", dataReturn); 


		
	};
    });

    YAHOO.departments.editmode=false

    YAHOO.departments.view=<?=$_SESSION['views']['assets_tables']?>;

function init(){
    var Event = YAHOO.util.Event;
    var Dom   = YAHOO.util.Dom;
    

    YAHOO.departments.view=<?=$_SESSION['views']['assets_tables']?>;
    
    YAHOO.departments.changeview = function(e,new_view) {

	var old_view=YAHOO.departments.view;


	if(old_view==new_view)
	    return;
	var Dom   = YAHOO.util.Dom;




	Dom.get("but_view"+old_view).className='';
	Dom.get("but_view"+new_view).className='selected';
	YAHOO.departments.view=new_view;
	    
	

	Dom.get("table"+old_view).style.display='none';
	Dom.get("table"+new_view).style.display='';
	Dom.get("otable"+old_view).style.display='none';
	Dom.get("otable"+new_view).style.display='';


	YAHOO.util.Connect.asyncRequest('POST','ar_assets.php?tipo=changetableview&level=0&value=' + escape(new_view) ); 
	
    }









    var handleSubmit = function() {
		this.submit();
	};
	var handleCancel = function() {
		this.cancel();
	};
	var handleSuccess = function(o) {

	    var response = YAHOO.lang.JSON.parse(o.responseText);
	     if(response.state==200){
		 YAHOO.departments.XHR_JSON.DepartmentsDataTable.addRow(response.data,0);
		 YAHOO.departments.dialog1.hide();
	     }
	    
	};
	var handleFailure = function(o) {
		alert("Submission failed: " + o.status);
	};


	YAHOO.departments.dialog1  = new YAHOO.widget.Dialog("add_department_form",
							     { width : "20em",
							  fixedcenter : true,
							  visible : false, 
							  constraintoviewport : true,
							  buttons : [ { text:"<?=_('Submit')?>", handler:handleSubmit, isDefault:true },
								      { text:"<?=_('Cancel')?>", handler:handleCancel } ]
							});

	YAHOO.departments.dialog1.callback = { success: handleSuccess,failure: handleFailure };
	YAHOO.departments.dialog1.render();



	YAHOO.departments.dialog2  = new YAHOO.widget.Dialog("upload_products_form",
							     { width : "30em",
							  fixedcenter : true,
							  visible : false, 
							  constraintoviewport : true,
							       postmethod:"form",
							  buttons : [ { text:"<?=_('Upload')?>", handler:handleSubmit, isDefault:true },
								      { text:"<?=_('Cancel')?>", handler:handleCancel } ]
							});

	YAHOO.departments.dialog2.callback = { success: handleSuccess,failure: handleFailure };
	YAHOO.departments.dialog2.render();


       


    



    YAHOO.departments.changeedit = function() {


	    if(YAHOO.departments.editmode){
		YAHOO.departments.editmode=false;

		YAHOO.departments.XHR_JSON.DepartmentsDataTable.showColumn('families');
		YAHOO.departments.XHR_JSON.DepartmentsDataTable.showColumn('active');
		YAHOO.departments.XHR_JSON.DepartmentsDataTable.getColumn('name').formatter=YAHOO.departments.XHR_JSON.departmentLink;
		YAHOO.departments.XHR_JSON.DepartmentsDataTable.getColumn('name').editor="";

		Dom.get('edit_menu').style.display='none';
		Dom.get('but_view3').className='';

		Event.addListener(Dom.get('but_view0'),"click",YAHOO.departments.changeview,0)
		Event.addListener(Dom.get('but_view1'),"click",YAHOO.departments.changeview,1)
		Event.addListener(Dom.get('but_view2'),"click",YAHOO.departments.changeview,2)

		Dom.get('but_view0').className='';
		Dom.get('but_view1').className='';
		Dom.get('but_view2').className='';
		Dom.get('but_view'+YAHOO.departments.view).className='selected';

		old_view=YAHOO.departments.view;
		Dom.get("table0").style.display='none';
		Dom.get("table"+old_view).style.display='';


	    }else{
		

		
		YAHOO.departments.editmode=true;
		YAHOO.departments.XHR_JSON.DepartmentsDataTable.hideColumn('families');
		YAHOO.departments.XHR_JSON.DepartmentsDataTable.hideColumn('active');
		YAHOO.departments.XHR_JSON.DepartmentsDataTable.getColumn('name').formatter="";
		YAHOO.departments.XHR_JSON.DepartmentsDataTable.getColumn('name').editor="textbox";

		Dom.get('edit_menu').style.display='';
		Dom.get('but_view3').className='edit';
		old_view=YAHOO.departments.view;
	       
		

		Dom.get("table"+old_view).style.display='none';
		Dom.get("table0").style.display='';


		Dom.get('but_view0').className='disabled';
		Dom.get('but_view1').className='disabled';
		Dom.get('but_view2').className='disabled';

		Event.removeListener("but_view0", "click");
		Event.removeListener("but_view1", "click");
		Event.removeListener("but_view2", "click");



	    }
	    YAHOO.departments.XHR_JSON.DepartmentsDataTable.render();

	};

    //var oButtonGroupDisplayOptions = new YAHOO.widget.ButtonGroup({ id:  "buttongroup3", name:  "radiofield3", container:  "display_options" });
    //oButtonGroupDisplayOptions.addButtons([{ label: "<?=_('Basic')?>", value: "Radio 9", checked: true },{ label: "<?=_('Stock')?>", value: "Radio 10" }, { label: "<?=_('Sales')?>", value: "Radio 11" } ]);

    Event.addListener(Dom.get('but_view0'),"click",YAHOO.departments.changeview,0)
    Event.addListener(Dom.get('but_view1'),"click",YAHOO.departments.changeview,1)
    Event.addListener(Dom.get('but_view2'),"click",YAHOO.departments.changeview,2)
    Event.addListener(Dom.get('but_view3'),"click",YAHOO.departments.changeedit)


    //	oButtonGroupDisplayOptions.getButton(1).addListener("click",YAHOO.departments.changeview,1)
    //	oButtonGroupDisplayOptions.getButton(2).addListener("click",YAHOO.departments.changeview,2)



	//var editDeptosButton= new YAHOO.widget.Button("edit_departments",{ 
	//	type:"checkbox", 
	//	value:"1", 
	//	checked:false });
	//	Event.addListener("but_edit", "click", YAHOO.departments.editdepartmentstable);	


	//	var addDeptosButton= new YAHOO.widget.Button("add_department",{ type:"push" });
YAHOO.util.Event.addListener("add_department", "click",  YAHOO.departments.dialog1.show, YAHOO.departments.dialog1, true );

//	var editDeptosButton= new YAHOO.widget.Button("upload",{ 
//		type:"push" });
	YAHOO.util.Event.addListener("upload", "click", YAHOO.departments.dialog2.show, YAHOO.departments.dialog2, true);

// 	var editDeptosButton= new YAHOO.widget.Button("upload_inventory",{ 
// 		type:"push" });
// 	YAHOO.util.Event.addListener("upload_invemtory", "click", YAHOO.departments.dialog3.show, YAHOO.departments.dialog3, true);
	
	
	//var oButtonEditMode = new YAHOO.widget.Button({label:"<?=_("Edit Mode")?>",container:"edit_buttons"});
//var oButtonAddDeptos1= new YAHOO.widget.Button({label:"<?=_("Add Department (Manually)")?>",container:"edit_buttons"});
//var oButtonAddDeptos2= new YAHOO.widget.Button({label:"<?=_("Add Department (From file)")?>",container:"edit_buttons"});






}

YAHOO.util.Event.onDOMReady(init);
