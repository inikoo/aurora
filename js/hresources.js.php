<?

include_once('../common.php');
// if(!(isset($_REQUEST['suppliers']) and is_numeric($_REQUEST['suppliers'])))
//     exit();


// $options=$_SESSION['tables']['suppliers_list'];

// $products=$_REQUEST['suppliers'];
// $products_perpage=($options[2]=='all'?$products:$options[2]);
// $products_offset=$options[3];
// $products_order=$options[0];
// $products_order_dir=$options[1];

?>


YAHOO.namespace ("suppliers"); 


YAHOO.util.Event.addListener(window, "load", function() {
    YAHOO.suppliers.XHR_JSON = new function() {

	    
	    this.supplierLink=  function(el, oRecord, oColumn, oData) {
		var url="supplier.php?id="+oRecord.getData("id");
		el.innerHTML = oData.link(url);
	    }
	    


	     //START OF THE TABLE=========================================================================================================================

	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    
	    var SuppliersColumnDefs = [
				       // {key:"name", label:"<?=_('Name')?>", sortable:true,editor:"textbox",className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				       //					 {key:"id", label:"<?=_('Id')?>", width:30,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				       {key:"id", label:"<?=_('Id')?>",  width:60,sortable:true,formatter:this.supplierLink,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"alias", label:"<?=_('Nickname')?>", width:190,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}


					 //					 {key:"families", label:"<?=_('Families')?>", width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
					 //{key:"active", label:"<?=_('Products')?>",  width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					 

					 ];
	    this.SuppliersDataSource = new YAHOO.util.DataSource("ar_contacts.php?tipo=staff&tid="+tableid);
	    this.SuppliersDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.SuppliersDataSource.connXhrMode = "queueRequests";
	    this.SuppliersDataSource.responseSchema = {resultsList: "resultset.data", fields: [
												 "id"
												 ,"alias"

												 ]};
	    //__You shouls not change anything from here


	    var newrequest="";
	    //	    alert(newrequest);
	    this.SuppliersDataTable = new YAHOO.widget.DataTable(tableDivEL, SuppliersColumnDefs,
								   this.SuppliersDataSource, {
								       initialRequest:newrequest
								       ,renderLoopSize: 50
								   }
								   );

	    

 	this.SuppliersDataTable.paginatorMenu = new YAHOO.widget.Menu('paginatornewmenu'+tableid,  {context:['paginatormenuselector'+tableid,"tr", "br"]  });
	this.SuppliersDataTable.paginatorMenu.addItems([{ text: "25", onclick:{fn:changeRecordsperPage,obj:25,scope:this.SuppliersDataTable}  } ]);
	this.SuppliersDataTable.paginatorMenu.addItems([{ text: "50", onclick:{fn:changeRecordsperPage,obj:50,scope:this.SuppliersDataTable}  } ]);
	this.SuppliersDataTable.paginatorMenu.addItems([{ text: "100", onclick:{fn:changeRecordsperPage,obj:100,scope:this.SuppliersDataTable}  } ]);
	this.SuppliersDataTable.paginatorMenu.addItems([{ text: "250", onclick:{fn:changeRecordsperPage,obj:250,scope:this.SuppliersDataTable}  } ]);
	this.SuppliersDataTable.paginatorMenu.addItems([{ text: "500", onclick:{fn:changeRecordsperPage,obj:500,scope:this.SuppliersDataTable}  } ]);
	    this.SuppliersDataTable.paginatorMenu.addItems([{ text: "all", onclick:{fn:changeRecordsperPage,obj:'all',scope:this.SuppliersDataTable}  } ]);




	    YAHOO.util.Event.addListener('paginatormenuselector'+tableid, "click", this.SuppliersDataTable.paginatorMenu.show, null, this.SuppliersDataTable.paginatorMenu);
	    this.SuppliersDataTable.paginatorMenu.render(document.body);

	    
	    this.SuppliersDataTable.subscribe("initEvent", dataReturn); 
	    
	    
	    this.SuppliersDataTable.id=tableid;
	    
	    this.SuppliersDataTable.editmode=false;
	    this.SuppliersDataSource.doBeforeCallback = mydoBeforeCallback;
	    YAHOO.util.Event.addListener('paginator_next0', "click", nextpage, this.SuppliersDataTable); 
	    YAHOO.util.Event.addListener('paginator_prev0', "click", prevpage, this.SuppliersDataTable); 
	    YAHOO.util.Event.addListener('hidder0', "click", showtable, this.SuppliersDataTable); 
	    this.SuppliersDataTable.sortColumn = mysort;



    this.SuppliersDataTable.subscribe("cellClickEvent", this.SuppliersDataTable.onEventShowCellEditor);
	    this.SuppliersDataTable.myreload=reload;



	    
	    this.mySaveEditor = function (){
		
		

		if(this._oCellEditor.isActive) {
 		    var newData = this._oCellEditor.value;
 		    var oldData = YAHOO.widget.DataTable._cloneObject(this._oCellEditor.record.getData(this._oCellEditor.column.key));
		}

// 		this._oRecordSet.updateRecordValue(this._oCellEditor.record, this._oCellEditor.column.key, this._oCellEditor.value);
// 		this.formatCell(this._oCellEditor.cell.firstChild);
// 		this._syncColWidths(false);
		
		var request='ar_suppliers.php?tipo=updateone_s&key='+this._oCellEditor.column.getKey()+'&value=' + escape(newData) + '&id=' + escape(this._oCellEditor.record.getData("id"));

		YAHOO.util.Connect.asyncRequest(
						'POST',
						request,{
						    success: function (o) {
							alert(o.responseText);
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

		this.SuppliersDataTable.saveCellEditor =this.mySaveEditor;



	    


		
	};
    });




function init(){
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
		 YAHOO.suppliers.XHR_JSON.SuppliersDataTable.addRow(response.data,0);
		 YAHOO.suppliers.dialog1.hide();
	     }
	    
	};
	var handleFailure = function(o) {
		alert("Submission failed: " + o.status);
	};


	YAHOO.suppliers.dialog1  = new YAHOO.widget.Dialog("add_supplier_form",
							     { width : "20em",
							  fixedcenter : true,
							  visible : false, 
							  constraintoviewport : true,
							  buttons : [ { text:"<?=_('Submit')?>", handler:handleSubmit, isDefault:true },
								      { text:"<?=_('Cancel')?>", handler:handleCancel } ]
							});

	YAHOO.suppliers.dialog1.callback = { success: handleSuccess,failure: handleFailure };
	YAHOO.suppliers.dialog1.render();



    



    YAHOO.suppliers.editsupplierstable = function() {
	

	    if(YAHOO.suppliers.XHR_JSON.SuppliersDataTable.editmode){
		YAHOO.suppliers.XHR_JSON.SuppliersDataTable.editmode=false;
		document.getElementById("table0").className="dtable btable "; 
//YAHOO.suppliers.XHR_JSON.SuppliersDataTable.showColumn('delete');
		YAHOO.suppliers.XHR_JSON.SuppliersDataTable.showColumn('families');
		YAHOO.suppliers.XHR_JSON.SuppliersDataTable.showColumn('active');
		YAHOO.suppliers.XHR_JSON.SuppliersDataTable.getColumn('name').formatter=YAHOO.suppliers.XHR_JSON.supplierLink;
		YAHOO.suppliers.XHR_JSON.SuppliersDataTable.getColumn('name').editor="";

	    }else{

		YAHOO.suppliers.XHR_JSON.SuppliersDataTable.editmode=true;
		document.getElementById("table0").className="dtable btable etable"; 
		
		//		YAHOO.suppliers.XHR_JSON.SuppliersDataTable.showColumn('delete');
		YAHOO.suppliers.XHR_JSON.SuppliersDataTable.hideColumn('lowstock');
		YAHOO.suppliers.XHR_JSON.SuppliersDataTable.hideColumn('active');
		YAHOO.suppliers.XHR_JSON.SuppliersDataTable.hideColumn('outofstock');

		YAHOO.suppliers.XHR_JSON.SuppliersDataTable.getColumn('code').editor="textbox";
		YAHOO.suppliers.XHR_JSON.SuppliersDataTable.getColumn('name').editor="textbox";



	    }
	    YAHOO.suppliers.XHR_JSON.SuppliersDataTable.render();

	};

var oButtonGroupDisplayOptions = new YAHOO.widget.ButtonGroup({ id:  "buttongroup3", name:  "radiofield3", container:  "display_options" });
oButtonGroupDisplayOptions.addButtons([{ label: "<?=_('Basic')?>", value: "Radio 9", checked: true },{ label: "<?=_('Stock')?>", value: "Radio 10" }, { label: "<?=_('Sales')?>", value: "Radio 11" } ]);


	var editDeptosButton= new YAHOO.widget.Button("edit_suppliers",{ 
		type:"checkbox", 
		value:"1", 
		checked:false });
	YAHOO.util.Event.addListener("edit_suppliers", "click", YAHOO.suppliers.editsupplierstable);	
	

	var addDeptosButton= new YAHOO.widget.Button("add_supplier",{ type:"push" });
	YAHOO.util.Event.addListener("add_supplier", "click",  YAHOO.suppliers.dialog1.show, YAHOO.suppliers.dialog1, true );


	

	

	//var oButtonEditMode = new YAHOO.widget.Button({label:"<?=_("Edit Mode")?>",container:"edit_buttons"});
//var oButtonAddDeptos1= new YAHOO.widget.Button({label:"<?=_("Add Supplier (Manually)")?>",container:"edit_buttons"});
//var oButtonAddDeptos2= new YAHOO.widget.Button({label:"<?=_("Add Supplier (From file)")?>",container:"edit_buttons"});






}

YAHOO.util.Event.onDOMReady(init);
