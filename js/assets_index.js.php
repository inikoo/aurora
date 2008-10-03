<?

include_once('../common.php');
//if(!(isset($_REQUEST['products']) and is_numeric($_REQUEST['products'])))
//    exit();


//$options=$_SESSION['tables']['pindex_list'];

//$products=$_REQUEST['products'];
//$products_perpage=($options[2]=='all'?$products:$options[2]);
//$products_offset=$options[3];
//$products_order=$options[0];
//$products_order_dir=$options[1];

?>

(function() {

    var tableid=0; // Change if you have more the 1 table
    var tableDivEL="table"+tableid;

	
    var Dom = YAHOO.util.Dom,Event = YAHOO.util.Event,ProductsDataSource = null,ProductsDataTable = null;



    var getTerms = function(query) {
	//	alert(ProductsDataTable.reload)
	ProductsDataTable.myreload();
	
// 	//	alert("ca");
// 	var value=Dom.get('f_input'+tableid).value;
// 	var newrequest="&sf=0&f_field="+Dom.get('f_field'+tableid).value+"&f_value="+value


// 	//alert(newrequest);
// 	// Dom.get('paginatormenurender'+tableid).innerHTML=newrequest;
// 	ProductsDataSource.sendRequest(newrequest,{success:ProductsDataTable.onDataReturnInitializeTable, scope:ProductsDataTable});
// 	//	alert('caca');
	
    };



    Event.onDOMReady(function() {

        
        var oACDS = new YAHOO.widget.DS_JSFunction(getTerms);
        oACDS.queryMatchContains = true;
        var oAutoComp = new YAHOO.widget.AutoComplete("f_input"+tableid,"filtercontainer"+tableid, oACDS);
        
	oAutoComp.minQueryLength = 0; 


	departmentLink=  function(el, oRecord, oColumn, oData) {
	    var url="assets_department.php?id="+oRecord.getData("department_id");
	    el.innerHTML = oData.link(url);
	}
	famLink=  function(el, oRecord, oColumn, oData) {
	    var url="assets_family.php?id="+oRecord.getData("group_id");
	    el.innerHTML = oData.link(url);
	}
	productLink=  function(el, oRecord, oColumn, oData) {
	    var url="assets_product.php?id="+oRecord.getData("id");
	    el.innerHTML = oData.link(url);
	}

	productCondicion=  function(el, oRecord, oColumn, oData) {
	    
	    if(oData<(-9999))
		oData='?';
	    var condicion=oRecord.getData("condicion")
	    if(condicion==0)
		el.innerHTML = '<img style="float:left" align="absbottom"    src="art/icons/brick.png" /> '+oData;
	    else if(condicion==2)
		el.innerHTML = '<img  style="float:left" align="absbottom" src="art/icons/brick_old.png" /> '+oData;
	    else if(condicion==1)
		el.innerHTML = '<img  style="float:left" align="absbottom" src="art/icons/brick_unique.png" /> '+oData;
	    else
		el.innerHTML = oData;
	}



	//START OF THE TABLE=========================================================================================================================
	

	var ProductsColumnDefs = [
				  {key:"stock", label:"<?=_('Stock')?>", width:50,sortable:true,formatter:this.productCondicion,className:'aright',sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				  {key:"code", label:"<?=_('Code')?>", width:60,sortable:true,formatter:this.productLink,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				  {key:"fam", label:"<?=_('Family')?>",width:60, sortable:true,formatter:this.famLink,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				  //				  {key:"department", label:"<?=_('Department')?>",width:170, sortable:true,formatter:this.departmentLink,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},

				  {key:"description", label:"<?=_('Description')?>", width:400,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
				  {key:"awtsq", label:"<?=_('Week Sales')?>", width:100,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

				  ];
	
	ProductsDataSource = new YAHOO.util.DataSource("ar_assets.php?tipo=prodindex&tid="+tableid);
	ProductsDataSource.responseType = YAHOO.util.DataSource.TYPE_JSON;
	ProductsDataSource.connXhrMode = "queueRequests";
	ProductsDataSource.responseSchema = 
	    {
		totalRecords: 'resultset.total_records',
		resultsList: "resultset.data", 
		fields: ["id","code","description","fam","group_id","department","department_id","stock","awtsq"]
	    };


	//var recordsperpage=init_RecordsperPage;

	
	var newrequest="&f_field="+Dom.get('f_field'+tableid).value+"&f_value="+Dom.get('f_input'+tableid).value;

	ProductsDataTable = new YAHOO.widget.DataTable(tableDivEL, ProductsColumnDefs,
						       ProductsDataSource, {
							   renderLoopSize: 50,
							   sortedBy: {key:"<?=$_SESSION['tables']['pindex_list'][0]?>", dir:"<?=$_SESSION['tables']['pindex_list'][1]?>"}


							 //								       sortedBy:{key:init_Order, dir:init_Orderdir},
							   //initialRequest:newrequest
							   
}
								   );
 	ProductsDataTable.paginatorMenu = new YAHOO.widget.Menu('paginatornewmenu'+tableid,  {context:['paginatormenuselector'+tableid,"tr", "br"]  });
	ProductsDataTable.paginatorMenu.addItems([{ text: "25", onclick:{fn:changeRecordsperPage,obj:25,scope:ProductsDataTable}  } ]);
	ProductsDataTable.paginatorMenu.addItems([{ text: "50", onclick:{fn:changeRecordsperPage,obj:50,scope:ProductsDataTable}  } ]);
	ProductsDataTable.paginatorMenu.addItems([{ text: "100", onclick:{fn:changeRecordsperPage,obj:100,scope:ProductsDataTable}  } ]);
	ProductsDataTable.paginatorMenu.addItems([{ text: "250", onclick:{fn:changeRecordsperPage,obj:250,scope:ProductsDataTable}  } ]);
	ProductsDataTable.paginatorMenu.addItems([{ text: "500", onclick:{fn:changeRecordsperPage,obj:500,scope:ProductsDataTable}  } ]);
	ProductsDataTable.paginatorMenu.addItems([{ text: "all", onclick:{fn:changeRecordsperPage,obj:'all',scope:ProductsDataTable}  } ]);




 	YAHOO.util.Event.addListener('paginatormenuselector'+tableid, "click", ProductsDataTable.paginatorMenu.show, null, ProductsDataTable.paginatorMenu);
 	ProductsDataTable.paginatorMenu.render(document.body);


 	ProductsDataTable.filterMenu = new YAHOO.widget.Menu('filternewmenu'+tableid,  {context:['filterselector'+tableid,"tr", "br"]  });
 	ProductsDataTable.filterMenu.addItems([{ text: "<?=_('Product Code')?>", onclick:{fn:changeFilter,obj:{col:'p.code',text:"<?=_('Product Code')?>"},scope:ProductsDataTable}  } ]);
 	ProductsDataTable.filterMenu.addItems([{ text: "<?=_('Family Code')?>", onclick:{fn:changeFilter,obj:{col:'g.name',text:"<?=_('Family Code')?>"},scope:ProductsDataTable}  } ]);
 	ProductsDataTable.filterMenu.addItems([{ text: "<?=_('Department Name')?>", onclick:{fn:changeFilter,obj:{col:'d.code',text:"<?=_('Department Name')?>"},scope:ProductsDataTable}  } ]);
 	ProductsDataTable.filterMenu.addItems([{ text: "<?=_('Description')?>", onclick:{fn:changeFilter,obj:{col:'p.description',text:"<?=_('Description')?>"},scope:ProductsDataTable}  } ]);

	
	YAHOO.util.Event.addListener('filterselector'+tableid, "click", ProductsDataTable.filterMenu.show, null, ProductsDataTable.filterMenu);
 	ProductsDataTable.filterMenu.render(document.body);
	//ProductsDataTable.filterMenu.show();
	//	Dom.get('filternewmenu'+tableid).style.display='block';
	//	alert(Dom.get('filternewmenu'+tableid).style.display);

	ProductsDataTable.myreload=reload;
	ProductsDataTable.sortColumn = mysort;
	//ProductsDataTable.offset=init_Offset;
	//ProductsDataTable.RecordsperPage=recordsperpage;
	ProductsDataTable.id=tableid;
	//	alert(ProductsDataTable.offset);

	YAHOO.util.Event.addListener('paginator_next'+tableid, "click", nextpage, ProductsDataTable); 
	YAHOO.util.Event.addListener('paginator_prev'+tableid, "click", prevpage, ProductsDataTable); 
	YAHOO.util.Event.addListener('hidder'+tableid, "click", showtable, ProductsDataTable); 
	YAHOO.util.Event.addListener('resetfilter'+tableid, "click", resetfilter, ProductsDataTable); 
	ProductsDataTable.subscribe("initEvent", dataReturn); 

	ProductsDataSource.doBeforeCallback = mydoBeforeCallback;





    });
})();