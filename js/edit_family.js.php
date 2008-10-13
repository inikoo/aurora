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

     {key:"code", label:"<?=_('Name')?>", width:80,sortable:true,formatter:this.productLink,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       ,{key:"description", label:"<?=_('Description')?>", width:400,formatter:this.description,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				       
				       ,{key:"stock_value", label:"<?=$myconf['currency_symbol'].' '._('Stock')?>",width:80, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"stock", label:"<?=_('Stock')?>", width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}  {key:"code", label:"<?=_('Name')?>", width:80,sortable:true,formatter:this.productLink,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"tsall", label:"<?=_('Total Sales')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"tsy", label:"<?=_('Sales (1y)')?>",width:80, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"tsq", label:"<?=_('Sales (1q)')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"tsm", label:"<?=_('Sales (1m)')?>", width:80,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"awtsq", label:"<?=_('Avg Sales (w)')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
