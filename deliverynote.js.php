<?php
include_once('common.php');
?>

    
    YAHOO.namespace ("deliverynote"); 


YAHOO.util.Event.addListener(window, "load", function() {
	YAHOO.deliverynote.XHR_JSON = new function() {
		
		
		this.productLink=  function(el, oRecord, oColumn, oData) {
		    
		    if(oRecord.getData("product_id")==0)
			el.innerHTML = oData;
		    else{
		    var url="asset_product.php?id="+oRecord.getData("product_id");
		    el.innerHTML = oData.link(url);
		    }
		    };
		this.description=  function(el, oRecord, oColumn, oData) {
		    var url="asset_family.php?id="+oRecord.getData("family_id");
		    el.innerHTML = oRecord.getData("units")+'('+oRecord.getData("units_tipof")+')'+'x '+oData;
		};

		var tableid=0; // Change if you have more the 1 table
		var tableDivEL="table"+tableid;
		alert("caca");
		var DeliverynotesColumnDefs = [
					   {key:"code", label:"<?php echo _('Our Code')?>", width:80,sortable:true,formatter:this.productLink,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					   ,{key:"sup_code", label:"<?php echo _('Code')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

					   ,{key:"description", label:"<?php echo _('Description')?>" ,width:280, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					   ,{key:"price", label:"<?php echo _('Cost Price Unit')?>" ,width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					   ,{key:"qty", label:"<?php echo _('Unit Ordered')?>" ,width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					   ,{key:"qty2", label:"<?php echo _('Unit Received')?>" ,width:60,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					   ,{key:"cost", label:"<?php echo _('Cost')?>" ,width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}


					   //					   ,{key:"description", label:"<?php echo _('Description')?>", sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					   // ,{key:"stock", label:"<?php echo _('Stock')?>",sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					   //,{key:"price_unit", label:"<?php echo _('Cost Price Unit')?>",sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

					   

					   ];
		
		this.DeliverynotesDataSource = new YAHOO.util.DataSource("ar_suppliers.php?tipo=dn_items_new&tid="+tableid);
		this.DeliverynotesDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
		this.DeliverynotesDataSource.connXhrMode = "queueRequests";
		this.DeliverynotesDataSource.responseSchema = {
		    resultsList: "resultset.data", 
		totalRecords: 'resultset.total_records',
		fields: [
			 "id","family_id","fam","code","description","sup_code","units","units_tipof","qty","price","dif","qty2","cost"
			 ]};
		
		this.DeliverynotesDataTable = new YAHOO.widget.DataTable(tableDivEL, DeliverynotesColumnDefs,
									this.DeliverynotesDataSource, {
									    renderLoopSize: 50
									}
									
									);
		

	    
	};
    });




function init(){


    function mygetTerms(query) {
	var Dom = YAHOO.util.Dom
	var table=YAHOO.deliverynote.XHR_JSON.DeliverynotesDataTable;
	var data=table.getDataSource();
	var newrequest="&sf=0&f_field="+Dom.get('f_field0').value+"&f_value="+Dom.get('f_input0').value;

	//	alert(newrequest);
	data.sendRequest(newrequest,{success:table.onDataReturnInitializeTable, scope:table});
    };
    var oACDS = new YAHOO.widget.DS_JSFunction(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0","filtercontainer0", oACDS);
    oAutoComp.minQueryLength = 0; 
    

    




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


	YAHOO.deliverynote.dialog1  = new YAHOO.widget.Dialog("upload_dn",
							     { width : "30em",
							       fixedcenter : true,
							       visible : false, 
							       constraintoviewport : true,
							       postmethod:"form",
							       
							  buttons : [ { text:"<?php echo _('Update')?>", handler:handleSubmit, isDefault:true },
								      { text:"<?php echo _('Cancel')?>", handler:handleCancel } ]
							});

	YAHOO.deliverynote.dialog1.callback = { success: handleSuccess,failure: handleFailure };
	YAHOO.deliverynote.dialog1.render();

    

    
    var newDNButton= new YAHOO.widget.Button("upload",{ type:"push" });
    YAHOO.util.Event.addListener("upload", "click", YAHOO.deliverynote.dialog1.show, YAHOO.deliverynote.dialog1, true);

    var submitButton= new YAHOO.widget.Button("submit_dn",{ type:"push" });


    YAHOO.deliverynote.ACJson = new function(){


    // Instantiate an XHR DataSource and define schema as an array:
    //     ["Multi-depth.object.notation.to.find.a.single.result.item",
    //     "Query Key",
    //     "Additional Param Name 1",
    //     ...
    //     "Additional Param Name n"]
	this.oACDS = new YAHOO.widget.DS_XHR("ar_assets.php", ["resultset.data","data"]);
    this.oACDS.queryMatchContains = true;
    this.oACDS.scriptQueryAppend = "tipo=codefromsup"; // Needed for YWS

    // Instantiate AutoComplete
    this.oAutoComp = new YAHOO.widget.AutoComplete("ysearchinput","ysearchcontainer", this.oACDS);
    this.oAutoComp.useShadow = true;
    this.oAutoComp.formatResult = function(oResultItem, sQuery) {
        return oResultItem[1].code ;
    };
    this.oAutoComp.doBeforeExpandContainer = function(oTextbox, oContainer, sQuery, aResults) {
        var pos = YAHOO.util.Dom.getXY(oTextbox);
        pos[1] += YAHOO.util.Dom.get(oTextbox).offsetHeight + 2;
        YAHOO.util.Dom.setXY(oContainer,pos);
        return true;
    };

    // Stub for form validation
    this.validateForm = function() {
        // Validation code goes here
        return true;
    };
};








}

YAHOO.util.Event.onDOMReady(init);


