<?include_once('../common.php');?>

YAHOO.namespace ("families"); 
YAHOO.families.editmode=false;
YAHOO.families.view=<?=$_SESSION['views']['assets_tables']?>;



function multireload(){
    var Dom = YAHOO.util.Dom;
    document.getElementById('loadingicon0').style.visibility='visible'; 

    

    var table=YAHOO.families.XHR_JSON.FamiliesDataTable;
    var position="&sf="+table.offset+"&nr="+table.recordsperPage;

    var newrequest=position+"&f_field="+Dom.get('f_field0').value+"&f_value="+Dom.get('f_input0').value;

    var oCallback = {success: table.onDataReturnInitializeTable,failure: table.onDataReturnInitializeTable,scope: table,
		     argument: {
	    sorting: {
		key: table.get("sortedBy").key,
		dir: table.get("sortedBy").dir
	    }
	}
    }
    var data=table.getDataSource();
    data.sendRequest(newrequest,oCallback);
	
	var table=YAHOO.families.XHR_JSON.Families1DataTable;

	var oCallback = {
	    success: table.onDataReturnInitializeTable,
	    failure: table.onDataReturnInitializeTable,
	    scope: table,
	    argument: {
	    // Pass in sort values so UI can be updated in callback function
		sorting: {
		    key: table.get("sortedBy").key,
		    dir: table.get("sortedBy").dir
		}
	    }
	}
	var data=table.getDataSource();
	data.sendRequest(newrequest,oCallback);

	var table=YAHOO.families.XHR_JSON.Families2DataTable;

	var oCallback = {
	    success: table.onDataReturnInitializeTable,
	    failure: table.onDataReturnInitializeTable,
	    scope: table,
	    argument: {
	    // Pass in sort values so UI can be updated in callback function
		sorting: {
		    key: table.get("sortedBy").key,
		    dir: table.get("sortedBy").dir
		    
		}
	    }
	}
	var data=table.getDataSource();
	data.sendRequest(newrequest,oCallback);



}




YAHOO.util.Event.addListener(window, "load", function() {
    YAHOO.families.XHR_JSON = new function() {
	    this.famLink=  function(el, oRecord, oColumn, oData) {
		var url="assets_family.php?id="+oRecord.getData("id");
		el.innerHTML = oData.link(url);
	    }

	     //START OF THE TABLE=========================================================================================================================
	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    
	    var FamiliesColumnDefs = [
				      {key:"name", label:"<?=_('Code')?>", width:80,sortable:true,formatter:this.famLink,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				      {key:"description", label:"<?=_('Description')?>",width:400, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				      {key:"active", label:"<?=_('Products')?>",width:100, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				      ];
	    
	    this.FamiliesDataSource = new YAHOO.util.DataSource("ar_assets.php?tipo=department&tid="+tableid);
	    this.FamiliesDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.FamiliesDataSource.connXhrMode = "queueRequests";
	    this.FamiliesDataSource.responseSchema = {
		resultsList: "resultset.data", 
		totalRecords: 'resultset.total_records',
		fields: [
			 "id","name","description",'active'//,{key:"families",parser:YAHOO.util.DataSource.parseNumber},
			 ]};

	    this.FamiliesDataSource.doBeforeCallback = mydoBeforeCallback;
	    this.FamiliesDataTable = new YAHOO.widget.DataTable
		(tableDivEL, FamiliesColumnDefs,this.FamiliesDataSource, {renderLoopSize: 50,
									  sortedBy: {key:"<?=$_SESSION['tables']['families_list'][0]?>", dir:"<?=$_SESSION['tables']['families_list'][1]?>"}		 
		}   );
	    
	    this.FamiliesDataTable.paginatorMenu = new YAHOO.widget.Menu('paginatornewmenu'+tableid,  {context:['paginatormenuselector'+tableid,"tr", "br"]  });
	    this.FamiliesDataTable.paginatorMenu.addItems([{ text: "25", onclick:{fn:changeRecordsperPage,obj:25,scope:this.FamiliesDataTable}  } ]);
	    this.FamiliesDataTable.paginatorMenu.addItems([{ text: "50", onclick:{fn:changeRecordsperPage,obj:50,scope:this.FamiliesDataTable}  } ]);
	    this.FamiliesDataTable.paginatorMenu.addItems([{ text: "100", onclick:{fn:changeRecordsperPage,obj:100,scope:this.FamiliesDataTable}  } ]);
	    this.FamiliesDataTable.paginatorMenu.addItems([{ text: "250", onclick:{fn:changeRecordsperPage,obj:250,scope:this.FamiliesDataTable}  } ]);
	    this.FamiliesDataTable.paginatorMenu.addItems([{ text: "500", onclick:{fn:changeRecordsperPage,obj:500,scope:this.FamiliesDataTable}  } ]);
	    this.FamiliesDataTable.paginatorMenu.addItems([{ text: "all", onclick:{fn:changeRecordsperPage,obj:'all',scope:this.FamiliesDataTable}  } ]);
	    YAHOO.util.Event.addListener('paginatormenuselector'+tableid, "click", this.FamiliesDataTable.paginatorMenu.show, null, this.FamiliesDataTable.paginatorMenu);
	    this.FamiliesDataTable.paginatorMenu.render(document.body);
	    
	    //this.FamiliesDataTable.filterMenu = new YAHOO.widget.Menu('filternewmenu'+tableid,  {context:['filterselector'+tableid,"tr", "br"]  });
	    //this.FamiliesDataTable.filterMenu.addItems([{ text: "<?=_('Family Code')?>", onclick:{fn:changeFilter,obj:{col:'name',text:"<?=_('Family Code')?>"},scope:this.FamiliesDataTable}  } ]);
	    //YAHOO.util.Event.addListener('filterselector'+tableid, "click", this.FamiliesDataTable.filterMenu.show, null, this.FamiliesDataTable.filterMenu);
	    //this.FamiliesDataTable.filterMenu.render(document.body);

	    this.FamiliesDataTable.myreload=multireload;
	    this.FamiliesDataTable.sortColumn = mysort;
	    this.FamiliesDataTable.id=tableid;
	    this.FamiliesDataTable.editmode=false;
	    this.FamiliesDataTable.subscribe("initEvent", dataReturn); 
	    YAHOO.util.Event.addListener('paginator_next'+tableid, "click", nextpage, this.FamiliesDataTable); 
	    YAHOO.util.Event.addListener('paginator_prev'+tableid, "click", prevpage, this.FamiliesDataTable); 
	    YAHOO.util.Event.addListener('hidder'+tableid, "click", showtable, this.FamiliesDataTable); 
	    YAHOO.util.Event.addListener('resetfilter'+tableid, "click", resetfilter, this.FamiliesDataTable); 

	    this.FamiliesDataTable.subscribe("cellClickEvent", this.FamiliesDataTable.onEventShowCellEditor);
	    
	    this.mySaveEditor = function (){
		if(this._oCellEditor.isActive) {
 		    var newData = this._oCellEditor.value;
 		    var oldData = YAHOO.widget.DataTable._cloneObject(this._oCellEditor.record.getData(this._oCellEditor.column.key));
		}
		
		if(this._oCellEditor.column.getKey()=='name')
		    var request='ar_assets.php?tipo=update_family_name&value=' + escape(newData) + '&id=' + escape(this._oCellEditor.record.getData("id"));
		else if (this._oCellEditor.column.getKey()=='description')
		    var request='ar_assets.php?tipo=update_family_description&value=' + escape(newData) + '&id=' + escape(this._oCellEditor.record.getData("id"));
		else
		    return;
		
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

		this.FamiliesDataTable.saveCellEditor =this.mySaveEditor;




	    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    
	    var Families1ColumnDefs = [
				      {key:"name", label:"<?=_('Code')?>", width:80,sortable:true,formatter:this.famLink,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"stock_value", label:"<?=_('Stock Value')?>",width:160, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"active", label:"<?=_('Products')?>",width:100, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"outofstock", label:"<?=_('Out of Stock')?>",width:100, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"stockerror", label:"<?=_('Error in Stock')?>",width:100, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				       ];
	    
	    this.Families1DataSource = new YAHOO.util.DataSource("ar_assets.php?tipo=department&tid="+tableid);
	    this.Families1DataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.Families1DataSource.connXhrMode = "queueRequests";
	    this.Families1DataSource.responseSchema = {
		resultsList: "resultset.data", 
		totalRecords: 'resultset.total_records',
		fields: [
			 "id","name","stock_value","active","outofstock","stockerror"
			 ]};

	    //	    this.Families1DataSource.doBeforeCallback = mydoBeforeCallback;
	    this.Families1DataTable = new YAHOO.widget.DataTable(tableDivEL, Families1ColumnDefs,this.Families1DataSource, {renderLoopSize: 50,

								sortedBy: {key:"<?=$_SESSION['tables']['families_list'][0]?>", dir:"<?=$_SESSION['tables']['families_list'][1]?>"}							    
}


);
	    
	    
	    var tableid=2; // Change if you have more the 2 table
	    var tableDivEL="table"+tableid;

	    
	    var Families2ColumnDefs = [
				      {key:"name", label:"<?=_('Code')?>", width:120,sortable:true,formatter:this.famLink,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      //     ,{key:"active", label:"<?=_('Products')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"tsall", label:"<?=_('Total Sales')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"tsy", label:"<?=_('Sales (1y)')?>",width:80, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"tsq", label:"<?=_('Sales (1q)')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"tsm", label:"<?=_('Sales (1m)')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"awtsq", label:"<?=_('Avg Sales (w)')?>", width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}


				      ];
	    
	    this.Families2DataSource = new YAHOO.util.DataSource("ar_assets.php?tipo=department&tid="+tableid);
	    this.Families2DataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.Families2DataSource.connXhrMode = "queueRequests";
	    this.Families2DataSource.responseSchema = {
		resultsList: "resultset.data", 
		totalRecords: 'resultset.total_records',
		fields: [
			 "id","name","actove","tsall","tsy","tsq","tsm","awtsq"
			 ]};

	    //	    this.Families2DataSource.doBeforeCallback = mydoBeforeCallback;
	    this.Families2DataTable = new YAHOO.widget.DataTable(tableDivEL, Families2ColumnDefs,this.Families2DataSource, {renderLoopSize: 50,
															    sortedBy: {key:"<?=$_SESSION['tables']['families_list'][0]?>", dir:"<?=$_SESSION['tables']['families_list'][1]?>"}							    
});
	    
	    this.Families2DataTable.sortColumn = mysort;
	    this.Families1DataTable.sortColumn = mysort;
	    

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
    


    
    YAHOO.families.changeview = function(e,new_view) {

	var old_view=YAHOO.families.view;


	if(old_view==new_view)
	    return;
	var Dom   = YAHOO.util.Dom;




	Dom.get("but_view"+old_view).className='';
	Dom.get("but_view"+new_view).className='selected';
	YAHOO.families.view=new_view;
	    
	

	Dom.get("table"+old_view).style.display='none';
	Dom.get("table"+new_view).style.display='';
	//	Dom.get("otable"+old_view).style.display='none';
	//Dom.get("otable"+new_view).style.display='';


	YAHOO.util.Connect.asyncRequest('POST','ar_assets.php?tipo=changetableview&level=1&value=' + escape(new_view) ); 
	
    }


    YAHOO.families.changeeditmode = function() {

	    if(YAHOO.families.editmode){
		YAHOO.families.editmode=false;
		//	document.getElementById("table0").className="dtable btable "; 
		//YAHOO.families.XHR_JSON.FamiliesDataTable.hideColumn('delete');
		
		YAHOO.families.XHR_JSON.FamiliesDataTable.getColumn('name').formatter=YAHOO.families.XHR_JSON.famLink;
		YAHOO.families.XHR_JSON.FamiliesDataTable.getColumn('name').editor="";
		YAHOO.families.XHR_JSON.FamiliesDataTable.getColumn('description').editor="";
		Dom.get('edit_menu').style.display='none';
		Dom.get('but_view3').className='';

		Event.addListener(Dom.get('but_view0'),"click",YAHOO.families.changeview,0)
		Event.addListener(Dom.get('but_view1'),"click",YAHOO.families.changeview,1)
		Event.addListener(Dom.get('but_view2'),"click",YAHOO.families.changeview,2)
		
		Dom.get('but_view0').className='';
		Dom.get('but_view1').className='';
		Dom.get('but_view2').className='';
		Dom.get('but_view'+YAHOO.families.view).className='selected';

		old_view=YAHOO.families.view;
		Dom.get("table0").style.display='none';
		Dom.get("table"+old_view).style.display='';


		

	    }else{

		YAHOO.families.editmode=true;
		//document.getElementById("table0").className="dtable btable etable"; 

		//		YAHOO.families.XHR_JSON.FamiliesDataTable.showColumn('delete');
		YAHOO.families.XHR_JSON.FamiliesDataTable.getColumn('name').formatter="";
		YAHOO.families.XHR_JSON.FamiliesDataTable.getColumn('name').editor="textbox";
		YAHOO.families.XHR_JSON.FamiliesDataTable.getColumn('description').editor="textbox";
		Dom.get('edit_menu').style.display='';
		Dom.get('but_view3').className='edit';
		old_view=YAHOO.families.view;
	       
		

		Dom.get("table"+old_view).style.display='none';
		Dom.get("table0").style.display='';


		Dom.get('but_view0').className='disabled';
		Dom.get('but_view1').className='disabled';
		Dom.get('but_view2').className='disabled';

		Event.removeListener("but_view0", "click");
		Event.removeListener("but_view1", "click");
		Event.removeListener("but_view2", "click");

	    }
	    YAHOO.families.XHR_JSON.FamiliesDataTable.render();


	};


    


  var handleSubmit = function() {
		this.submit();
	};
	var handleCancel = function() {
		this.cancel();
	};
	var handleSuccess = function(o) {
	    //alert(o.responseText)
	    var response = YAHOO.lang.JSON.parse(o.responseText);
	     if(response.state==200){
		 YAHOO.families.XHR_JSON.FamiliesDataTable.addRow(response.data,0);
		 YAHOO.families.dialog1.hide();
	     }
	    
	};
	var handleFailure = function(o) {
		alert("Submission failed: " + o.status);
	};
	

	YAHOO.families.dialog1  = new YAHOO.widget.Dialog("add_family_form",
							     { width : "20em",
							  fixedcenter : true,
							  visible : false, 
							  constraintoviewport : true,
							  buttons : [ { text:"<?=_('Submit')?>", handler:handleSubmit, isDefault:true },
								      { text:"<?=_('Cancel')?>", handler:handleCancel } ]
							});

	YAHOO.families.dialog1.callback = { success: handleSuccess,failure: handleFailure };
	YAHOO.families.dialog1.render();



	YAHOO.families.dialog2  = new YAHOO.widget.Dialog("upload_family_form",
							     { width : "30em",
							  fixedcenter : true,
							  visible : false, 
							  constraintoviewport : true,
							       postmethod:"form",
							  buttons : [ { text:"<?=_('Upload')?>", handler:handleSubmit, isDefault:true },
								      { text:"<?=_('Cancel')?>", handler:handleCancel } ]
							});

	YAHOO.families.dialog2.callback = { success: handleSuccess,failure: handleFailure };
	YAHOO.families.dialog2.render();


   Event.addListener(Dom.get('but_view0'),"click",YAHOO.families.changeview,0)
    Event.addListener(Dom.get('but_view1'),"click",YAHOO.families.changeview,1)
    Event.addListener(Dom.get('but_view2'),"click",YAHOO.families.changeview,2)
    Event.addListener(Dom.get('but_view3'),"click",YAHOO.families.changeeditmode)


	YAHOO.util.Event.addListener("add_family", "click",  YAHOO.families.dialog1.show, YAHOO.families.dialog1, true );

}

YAHOO.util.Event.onDOMReady(init);
