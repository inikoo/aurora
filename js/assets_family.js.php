<?
include_once('../common.php');
?>


YAHOO.namespace ("products"); 
YAHOO.products.editmode=false;
YAHOO.products.view=<?=$_SESSION['views']['assets_tables']?>;


function multireload(){

    var Dom = YAHOO.util.Dom;
    document.getElementById('loadingicon0').style.visibility='visible'; 

    var table=YAHOO.products.XHR_JSON.ProductsDataTable;
    var position="&sf="+this.table+"&nr="+table.recordsperPage;
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

	var table=YAHOO.products.XHR_JSON.Products1DataTable;

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

	var table=YAHOO.products.XHR_JSON.Products2DataTable;

	var oCallback = {
	    success: table.onDataReturnInitializeTable,
	    failure: table.onDataReturnInitializeTable,
	    scope: table,
	    argument: {
	    // Pass in sort values so UI can be updated in callback function
		sorting: {
		    key: 'name',
		    dir: 'desc'
		}
	    }
	}
	var data=table.getDataSource();
	data.sendRequest(newrequest,oCallback);



}






YAHOO.util.Event.addListener(window, "load", function() {
	YAHOO.products.XHR_JSON = new function() {
		
		
		this.productLink=  function(el, oRecord, oColumn, oData) {
		    var url="assets_product.php?id="+oRecord.getData("id");
		    el.innerHTML = oData.link(url);
		};




		this.description =function(el, oRecord, oColumn, oData) {
		    el.innerHTML = oRecord.getData("units")+oRecord.getData("units_tipo")+'x '+oData;
		};
		    
		var tableid=0; // Change if you have more the 1 table
		var tableDivEL="table"+tableid;
		
		var ProductsColumnDefs = [
				      {key:"code", label:"<?=_('Name')?>", width:80,sortable:true,formatter:this.productLink,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"description", label:"<?=_('Description')?>",  width:360,formatter:this.description,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"price", label:"<?=_('Price')?>",width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      //	      ,{key:"stock", label:"<?=_('Stock')?>",sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      // ,{key:"available", label:"<?=_('Available')?>",sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      // ,{key:"stock_value", label:"<?=_('Stock Value')?>",sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      // ,{key:"tsall", label:"<?=_('Total Sales')?>",sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      // ,{key:"tsy", label:"<?=_('Sales (1Y)')?>",sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      // ,{key:"tsm", label:"<?=_('Sales (1m)')?>",sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}


					  ];

		this.ProductsDataSource = new YAHOO.util.DataSource("ar_assets.php?tipo=family&tid="+tableid);
		this.ProductsDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
		this.ProductsDataSource.connXhrMode = "queueRequests";
		this.ProductsDataSource.responseSchema = {
		    resultsList: "resultset.data", 
		    totalRecords: 'resultset.total_records',
		    fields: [
			     "id",
			     "description","code","units","units_tipo","price","stock","available","stock_value","tsall","tsy","tsq","tsm"
			     ]};
		
		this.ProductsDataSource.doBeforeCallback = mydoBeforeCallback;
		
		this.ProductsDataTable = new YAHOO.widget.DataTable
		    (tableDivEL, ProductsColumnDefs,this.ProductsDataSource, {renderLoopSize: 50,
									      sortedBy: {key:"<?=$_SESSION['tables']['products_list'][0]?>", dir:"<?=$_SESSION['tables']['products_list'][1]?>"}
		    }
		    
		    );
		

		
		this.ProductsDataTable.paginatorMenu = new YAHOO.widget.Menu('paginatornewmenu'+tableid,  {context:['paginatormenuselector'+tableid,"tr", "br"]  });
		this.ProductsDataTable.paginatorMenu.addItems([{ text: "25", onclick:{fn:changeRecordsperPage,obj:25,scope:this.ProductsDataTable}  } ]);
		this.ProductsDataTable.paginatorMenu.addItems([{ text: "50", onclick:{fn:changeRecordsperPage,obj:50,scope:this.ProductsDataTable}  } ]);
		this.ProductsDataTable.paginatorMenu.addItems([{ text: "100", onclick:{fn:changeRecordsperPage,obj:100,scope:this.ProductsDataTable}  } ]);
		this.ProductsDataTable.paginatorMenu.addItems([{ text: "250", onclick:{fn:changeRecordsperPage,obj:250,scope:this.ProductsDataTable}  } ]);
		this.ProductsDataTable.paginatorMenu.addItems([{ text: "500", onclick:{fn:changeRecordsperPage,obj:500,scope:this.ProductsDataTable}  } ]);
		this.ProductsDataTable.paginatorMenu.addItems([{ text: "all", onclick:{fn:changeRecordsperPage,obj:'all',scope:this.ProductsDataTable}  } ]);
		YAHOO.util.Event.addListener('paginatormenuselector'+tableid, "click", this.ProductsDataTable.paginatorMenu.show, null, this.ProductsDataTable.paginatorMenu);
		this.ProductsDataTable.paginatorMenu.render(document.body);
		this.ProductsDataTable.filterMenu = new YAHOO.widget.Menu('filternewmenu'+tableid,  {context:['filterselector'+tableid,"tr", "br"]  });
		this.ProductsDataTable.filterMenu.addItems([{ text: "<?=_('Product Code')?>", onclick:{fn:changeFilter,obj:{col:'code',text:"<?=_('Product Code')?>"},scope:this.ProductsDataTable}  } ]);
		this.ProductsDataTable.filterMenu.addItems([{ text: "<?=_('Description')?>", onclick:{fn:changeFilter,obj:{col:'description',text:"<?=_('Description')?>"},scope:this.ProductsDataTable}  } ]);
		YAHOO.util.Event.addListener('filterselector'+tableid, "click", this.ProductsDataTable.filterMenu.show, null, this.ProductsDataTable.filterMenu);
		this.ProductsDataTable.filterMenu.render(document.body);
		
		this.ProductsDataTable.myreload=multireload;
		this.ProductsDataTable.sortColumn = mysort;
		this.ProductsDataTable.id=tableid;

		this.ProductsDataTable.subscribe("initEvent", dataReturn); 
		YAHOO.util.Event.addListener('paginator_next'+tableid, "click", nextpage, this.ProductsDataTable); 
		YAHOO.util.Event.addListener('paginator_prev'+tableid, "click", prevpage, this.ProductsDataTable); 
		YAHOO.util.Event.addListener('hidder'+tableid, "click", showtable, this.ProductsDataTable); 
		YAHOO.util.Event.addListener('resetfilter'+tableid, "click", resetfilter, this.ProductsDataTable); 
		


 this.mySaveEditor = function (){
		if(this._oCellEditor.isActive) {
 		    var newData = this._oCellEditor.value;
 		    var oldData = YAHOO.widget.DataTable._cloneObject(this._oCellEditor.record.getData(this._oCellEditor.column.key));
		}
		
		if(this._oCellEditor.column.getKey()=='code')
		    var request='ar_assets.php?tipo=update_product_name&value=' + escape(newData) + '&id=' + escape(this._oCellEditor.record.getData("id"));
		else if (this._oCellEditor.column.getKey()=='description')
		    var request='ar_assets.php?tipo=update_product_description&value=' + escape(newData) + '&id=' + escape(this._oCellEditor.record.getData("id"));
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

     this.ProductsDataTable.saveCellEditor =this.mySaveEditor;
		this.ProductsDataTable.subscribe("cellClickEvent", this.ProductsDataTable.onEventShowCellEditor);


	    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;

	    
	    var Products1ColumnDefs = [
				       {key:"code", label:"<?=_('Name')?>", width:80,sortable:true,formatter:this.productLink,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"description", label:"<?=_('Description')?>", width:400,formatter:this.description,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       
				       ,{key:"stock_value", label:"<?=$myconf['currency_symbol'].' '._('Stock')?>",width:80, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"stock", label:"<?=_('Stock')?>", width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}


				       ];
	    
	    this.Products1DataSource = new YAHOO.util.DataSource("ar_assets.php?tipo=family&tid="+tableid);
	    this.Products1DataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.Products1DataSource.connXhrMode = "queueRequests";
	    this.Products1DataSource.responseSchema = {
		resultsList: "resultset.data", 
		totalRecords: 'resultset.total_records',
		fields: [
			 "id","stock_value","stock","description","units","code","units_tipo"
			 ]};

	    //	    this.Products1DataSource.doBeforeCallback = mydoBeforeCallback;
	    this.Products1DataTable = new YAHOO.widget.DataTable(tableDivEL, Products1ColumnDefs,this.Products1DataSource, {renderLoopSize: 50
															    , sortedBy: {key:"<?=$_SESSION['tables']['products_list'][0]?>", dir:"<?=$_SESSION['tables']['products_list'][1]?>"}
});
	    
	    
	    var tableid=2; // Change if you have more the 2 table
	    var tableDivEL="table"+tableid;

	    
	    var Products2ColumnDefs = [
				       {key:"code", label:"<?=_('Name')?>", width:80,sortable:true,formatter:this.productLink,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"tsall", label:"<?=_('Total Sales')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"tsy", label:"<?=_('Sales (1y)')?>",width:80, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"tsq", label:"<?=_('Sales (1q)')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"tsm", label:"<?=_('Sales (1m)')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"awtsq", label:"<?=_('Avg Sales (w)')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}


				      ];
	    
	    this.Products2DataSource = new YAHOO.util.DataSource("ar_assets.php?tipo=family&tid="+tableid);
	    this.Products2DataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.Products2DataSource.connXhrMode = "queueRequests";
	    this.Products2DataSource.responseSchema = {
		resultsList: "resultset.data", 
		totalRecords: 'resultset.total_records',
		fields: [
			 "id","tsall","tsy","tsq","tsm","awtsq","code"
			 ]};

	    //	    this.Products2DataSource.doBeforeCallback = mydoBeforeCallback;
	    this.Products2DataTable = new YAHOO.widget.DataTable(tableDivEL, Products2ColumnDefs,this.Products2DataSource, {renderLoopSize: 50
															    , sortedBy: {key:"<?=$_SESSION['tables']['products_list'][0]?>", dir:"<?=$_SESSION['tables']['products_list'][1]?>"}
});
	    
	    this.Products2DataTable.sortColumn = mysort;
	    this.Products1DataTable.sortColumn = mysort;









	    
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
    

    
    
    YAHOO.products.changeview = function(e,new_view) {
	
	var old_view=YAHOO.products.view;


	if(old_view==new_view)
	    return;
	var Dom   = YAHOO.util.Dom;




	Dom.get("but_view"+old_view).className='';
	Dom.get("but_view"+new_view).className='selected';
	YAHOO.products.view=new_view;
	    
	

	Dom.get("table"+old_view).style.display='none';
	Dom.get("table"+new_view).style.display='';

	YAHOO.util.Connect.asyncRequest('POST','ar_assets.php?tipo=changetableview&level=2&value=' + escape(new_view) ); 
	
    }


 YAHOO.products.changeedit = function() {

	    if(YAHOO.products.editmode){


		YAHOO.products.editmode=false;

		YAHOO.products.XHR_JSON.ProductsDataTable.getColumn('code').formatter=YAHOO.products.XHR_JSON.productLink;
		YAHOO.products.XHR_JSON.ProductsDataTable.getColumn('code').editor="";
		YAHOO.products.XHR_JSON.ProductsDataTable.getColumn('description').editor="";

		Dom.get('edit_menu').style.display='none';
		Dom.get('but_view3').className='';

		Event.addListener(Dom.get('but_view0'),"click",YAHOO.products.changeview,0)
		Event.addListener(Dom.get('but_view1'),"click",YAHOO.products.changeview,1)
		Event.addListener(Dom.get('but_view2'),"click",YAHOO.products.changeview,2)
		
		Dom.get('but_view0').className='';
		Dom.get('but_view1').className='';
		Dom.get('but_view2').className='';
		Dom.get('but_view'+YAHOO.products.view).className='selected';

		old_view=YAHOO.products.view;
		Dom.get("table0").style.display='none';
		Dom.get("table"+old_view).style.display='';

	    }else{

		YAHOO.products.editmode=true;

		YAHOO.products.XHR_JSON.ProductsDataTable.getColumn('code').formatter="";

		YAHOO.products.XHR_JSON.ProductsDataTable.getColumn('code').editor="textbox";
		YAHOO.products.XHR_JSON.ProductsDataTable.getColumn('description').editor="textbox";

		Dom.get('edit_menu').style.display='';
		Dom.get('but_view3').className='edit';

		old_view=YAHOO.products.view;
	       


		Dom.get("table"+old_view).style.display='none';
		Dom.get("table0").style.display='';


		Dom.get('but_view0').className='disabled';
		Dom.get('but_view1').className='disabled';
		Dom.get('but_view2').className='disabled';

		Event.removeListener("but_view0", "click");
		Event.removeListener("but_view1", "click");
		Event.removeListener("but_view2", "click");

		


	    }
	    YAHOO.products.XHR_JSON.ProductsDataTable.render();

	};


    


  var handleSubmit = function() {
		this.submit();
	};
	var handleCancel = function() {
		this.cancel();
	};
	var handleSuccess = function(o) {
	    //alert(o.responseText);
	    var response = YAHOO.lang.JSON.parse(o.responseText);
	     if(response.state==200){
		 //alert(response.data);
		 YAHOO.products.XHR_JSON.ProductsDataTable.addRow(response.data,0);
		 YAHOO.products.dialog1.hide();
	     }else{
		 alert(response.resp);
	     }
	    
	};
	var handleFailure = function(o) {
		alert("Submission failed: " + o.status);
	};
	

	YAHOO.products.dialog1  = new YAHOO.widget.Dialog("add_product_form",
							     { width : "30em",
							  fixedcenter : true,
							  visible : false, 
							  constraintoviewport : true,
							  buttons : [ { text:"<?=_('Submit')?>", handler:handleSubmit, isDefault:true },
								      { text:"<?=_('Cancel')?>", handler:handleCancel } ]
							});

	YAHOO.products.dialog1.callback = { success: handleSuccess,failure: handleFailure };
	YAHOO.products.dialog1.render();



	YAHOO.products.dialog2  = new YAHOO.widget.Dialog("upload_product_form",
							     { width : "30em",
							  fixedcenter : true,
							  visible : false, 
							  constraintoviewport : true,
							       postmethod:"form",
							  buttons : [ { text:"<?=_('Upload')?>", handler:handleSubmit, isDefault:true },
								      { text:"<?=_('Cancel')?>", handler:handleCancel } ]
							});

	YAHOO.products.dialog2.callback = { success: handleSuccess,failure: handleFailure };
	YAHOO.products.dialog2.render();


	
	
	Event.addListener(Dom.get('but_view0'),"click",YAHOO.products.changeview,0);
	Event.addListener(Dom.get('but_view1'),"click",YAHOO.products.changeview,1);
	Event.addListener(Dom.get('but_view2'),"click",YAHOO.products.changeview,2);
	Event.addListener(Dom.get('but_view3'),"click",YAHOO.products.changeedit);



	var editProductButton= new YAHOO.widget.Button("edit_products",{ 
		type:"checkbox", 
		value:"1", 
		checked:false });
	YAHOO.util.Event.addListener("edit_products", "click", YAHOO.products.editproductstable);	
	

	//	var addProductButton= new YAHOO.widget.Button("add_product",{ type:"push" });
       Event.addListener("add_product", "click",  YAHOO.products.dialog1.show, YAHOO.products.dialog1, true );




}

YAHOO.util.Event.onDOMReady(init);
