<?include_once('../common.php');?>

YAHOO.namespace ("products"); 
YAHOO.util.Event.addListener(window, "load", function() {
    YAHOO.products.XHR_JSON = new function() {
	    
	     this.flag=  function(el, oRecord, oColumn, oData) {
		 if(oData==0)
		     el.innerHTML = '<img src="art/icons/flag_red.png">';
		 else if(oData==1)
		     el.innerHTML = '<img src="art/icons/flag_yellow.png">';
		 else if(oData==2)
		     el.innerHTML = '<img src="art/icons/flag_green.png">';
	    }
		 
		 this.alarm=  function(el, oRecord, oColumn, oData) {
		     
		     if(oColumn.key=='sprice'){
			 if(oRecord.getData("s_sprice")==1)
			     el.innerHTML ='<img src="art/icons/error.png" alt="<?=_('Attention')?>"> '+oData;
			 else if (oRecord.getData("s_sprice")==0)
			     el.innerHTML ='<img src="art/icons/exclamation.png" alt="<?=_('Error')?>"> '+oData;
			 else
			     el.innerHTML =oData;
		     }else if(oColumn.key=='scode'){
			 if(oRecord.getData("s_scode")==1)
			     el.innerHTML ='<img src="art/icons/error.png" alt="<?=_('Attention')?>"> '+oData;
			 else if (oRecord.getData("s_scode")==0)
			     el.innerHTML ='<img src="art/icons/exclamation.png" alt="<?=_('Error')?>"> '+oData;
			 else
			     el.innerHTML =oData;
		     }else if(oColumn.key=='supplier'){
			 if(oRecord.getData("s_sup")==1)
			     el.innerHTML ='<img src="art/icons/error.png" alt="<?=_('Attention')?>"> '+oData;
			 else if (oRecord.getData("s_sup")==0)
			     el.innerHTML ='<img src="art/icons/exclamation.png" alt="<?=_('Error')?>"> '+oData;
			 else
			     el.innerHTML =oData;
		     }  else if(oColumn.key=='code'){
			 if(oRecord.getData("s_code")==1)
			     el.innerHTML ='<img src="art/icons/error.png" alt="<?=_('Attention')?>"> '+oData;
			 else if (oRecord.getData("s_code")==0)
			     el.innerHTML ='<img src="art/icons/exclamation.png" alt="<?=_('Error')?>"> '+oData;
			 else
			     el.innerHTML =oData;
		     } else if(oColumn.key=='family'){
			 if(oRecord.getData("s_fam")==1)
			     el.innerHTML ='<img src="art/icons/error.png" alt="<?=_('Attention')?>"> '+oData;
			 else if (oRecord.getData("s_fam")==0)
			     el.innerHTML ='<img src="art/icons/exclamation.png" alt="<?=_('Error')?>"> '+oData;
			 else
			     el.innerHTML =oData;
		     }


		     

		 }



	     //START OF THE TABLE=========================================================================================================================
	    var DepartmentsColumnDefs = [
					 {key:"status", label:"&nbsp;", width:16,formatter:this.flag,sortable:false,className:"aleft"}
					 ,{key:"code", label:"<?=_('Code')?>", width:80,sortable:false,className:"aleft"}
					 ,{key:"name", label:"<?=_('Name')?>", sortable:false,className:"aleft"}
					 ];
	    
	    this.DepartmentsDataSource = new YAHOO.util.DataSource(YAHOO.util.Dom.get("table0"));  
	    this.DepartmentsDataSource.responseType = YAHOO.util.DataSource.TYPE_HTMLTABLE; 
	    this.DepartmentsDataSource.responseSchema = {
		resultsList: "resultset.data", 
		totalRecords: 'resultset.total_records',
		fields: [
			 "status","code","name"//,{key:"departments",parser:YAHOO.util.DataSource.parseNumber},
			 ]};
	    this.DepartmentsDataTable = new YAHOO.widget.DataTable("departmentsx",DepartmentsColumnDefs, this.DepartmentsDataSource

								   );

	    
	    var FamiliesColumnDefs = [
				      {key:"status", label:"&nbsp;", width:16,formatter:this.flag,sortable:false,className:"aleft"}
				      ,{key:"department", label:"<?=_('Department')?>", width:80,sortable:false,className:"aleft"}
				      ,{key:"code", label:"<?=_('Code')?>", width:80,sortable:false,className:"aleft"}
				      ,{key:"description", label:"<?=_('Description')?>", sortable:false,className:"aleft"}
				      ];
	    
	    this.FamiliesDataSource = new YAHOO.util.DataSource(YAHOO.util.Dom.get("table1"));  
	    this.FamiliesDataSource.responseType = YAHOO.util.DataSource.TYPE_HTMLTABLE; 
	    this.FamiliesDataSource.responseSchema = {
		resultsList: "resultset.data", 
		totalRecords: 'resultset.total_records',
		fields: [
			 "status","department","code","description"//,{key:"families",parser:YAHOO.util.DataSource.parseNumber},
			 ]};
	    this.FamiliesDataTable = new YAHOO.widget.DataTable("familiesx",FamiliesColumnDefs, this.FamiliesDataSource

								   );

	    var ProductsColumnDefs = [
				      {key:"status", label:"&nbsp;", width:16,formatter:this.flag,sortable:false,className:"aleft"}
				      ,{key:"number", label:"&nbsp;", sortable:false,className:"aleft"}
				      ,{key:"family", label:"<?=_('Family')?>", sortable:false,className:"aleft"}
				      ,{key:"code", label:"<?=_('Code')?>", sortable:false,className:"aleft"}
				      ,{key:"description", label:"<?=_('Description')?>", sortable:false,className:"aleft"}
				      ,{key:"units", label:"<?=_('U/outer')?>", sortable:false,className:"aright"}
				      ,{key:"units_carton", label:"<?=_('U/c')?>", sortable:false,className:"aright"}
				      ,{key:"type_units", label:"<?=_('Unit Type')?>", sortable:false,className:"aleft"}
				      ,{key:"price", label:"<?=_('Price/o')?>", sortable:false,className:"aright"}
				      ,{key:"rrp", label:"<?=_('RRP')?>", sortable:false,className:"aright"}
				      ,{key:"export_code", label:"<?=_('Exp Code')?>", sortable:false,className:"aleft"}

				      ];
	    
	    this.ProductsDataSource = new YAHOO.util.DataSource(YAHOO.util.Dom.get("table2"));  
	    this.ProductsDataSource.responseType = YAHOO.util.DataSource.TYPE_HTMLTABLE; 
	    this.ProductsDataSource.responseSchema = {
		fields: [
			 "status","family","code","description","units","units_carton","type_units","price","rrp","export_code","number"//,"sup_id","sup_code","sup_price"
			 
			 ]};
	    this.ProductsDataTable = new YAHOO.widget.DataTable("productsx",ProductsColumnDefs, this.ProductsDataSource);

	    
	    var ProductsdimColumnDefs = [
				      {key:"status", label:"&nbsp;", width:16,formatter:this.flag,sortable:false,className:"aleft"}
				      ,{key:"number", label:"&nbsp;", width:24, sortable:false,className:"aleft"}
				      ,{key:"code", label:"<?=_('Code')?>",width:60,  sortable:false,className:"aleft"}
				      ,{key:"w1", label:"<?=_('Wu')?>", sortable:false,className:"aright"}
				      ,{key:"d1", label:"<?=_('Du')?>", sortable:false,className:"aright"}
				      ,{key:"w2", label:"<?=_('Wo')?>", sortable:false,className:"aright"}
				      ,{key:"d2", label:"<?=_('Do')?>", sortable:false,className:"aright"}
				      ,{key:"w3", label:"<?=_('Wc')?>", sortable:false,className:"aright"}
				      ,{key:"d3", label:"<?=_('Dc')?>", sortable:false,className:"aright"}

				      ];
	    
	    this.ProductsdimDataSource = new YAHOO.util.DataSource(YAHOO.util.Dom.get("table3"));  
	    this.ProductsdimDataSource.responseType = YAHOO.util.DataSource.TYPE_HTMLTABLE; 
	    this.ProductsdimDataSource.responseSchema = {
		fields: [
			 "status","number","code","w1","d1","w2","d2","w3","d3"
			 
			 
			 ]};
	    this.ProductsdimDataTable = new YAHOO.widget.DataTable("productsdimx",ProductsdimColumnDefs, this.ProductsdimDataSource);


	    var ProductsupColumnDefs = [
					{key:"status", label:"&nbsp;", width:16,formatter:this.flag,sortable:false,className:"aleft"}
					,{key:"number", label:"&nbsp;",width:24, sortable:false,className:"aleft"}
					,{key:"code", label:"<?=_('Code')?>", width:60, formatter:this.alarm, width:76,sortable:false,className:"aleft"}
					,{key:"supplier", label:"<?=_('Supplier')?>",formatter:this.alarm, sortable:false,className:"aleft"}
					,{key:"scode", label:"<?=_('Supplier Code')?>", formatter:this.alarm,sortable:false,className:"aleft"}
					,{key:"sprice", label:"<?=_('Buying Price')?>", formatter:this.alarm,sortable:false,className:"aright"}
					];
	    
	    this.ProductsupDataSource = new YAHOO.util.DataSource(YAHOO.util.Dom.get("table3"));  
	    this.ProductsupDataSource.responseType = YAHOO.util.DataSource.TYPE_HTMLTABLE; 
	    this.ProductsupDataSource.responseSchema = {
		fields: [
			 "status","number","code","supplier_id","supplier","s_sup","s_scode","s_sprice"
			 
			 ]};
	    this.ProductsupDataTable = new YAHOO.widget.DataTable("productsupx",ProductsupColumnDefs, this.ProductsupDataSource);


							  
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
	    
	};
	var handleFailure = function(o) {
	    alert("Submission failed: " + o.status);
	};


    
    
	YAHOO.products.dialog2  = new YAHOO.widget.Dialog("upload_department_form",
							     { width : "30em",
							       visible : true, 

							       postmethod:"form",
							       buttons : [ { text:"<?=_('Upload')?>", handler:handleSubmit, isDefault:true },
								      { text:"<?=_('Cancel')?>", handler:handleCancel } ]
							     });

	//	YAHOO.products.dialog2.callback = { success: handleSuccess,failure: handleFailure };
	YAHOO.products.dialog2.render();
	YAHOO.products.dialog2.show();





}

YAHOO.util.Event.onDOMReady(init);
