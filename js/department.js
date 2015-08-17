
var link='department.php';

var Dom   = YAHOO.util.Dom;
var Event   = YAHOO.util.Event;


var dialog_change_families_display;
var dialog_change_products_display;

function change_block(){
ids=['details','families','products','categories','deals','web','sales','notes'];
block_ids=['block_details','block_families','block_products','block_categories','block_deals','block_web','block_sales','block_notes'];

Dom.setStyle(block_ids,'display','none');
Dom.setStyle('block_'+this.id,'display','');
Dom.removeClass(ids,'selected');
Dom.addClass(this,'selected');

YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=department-block_view&value='+this.id ,{});
}

function change_display_mode(parent,name,label){
    if(name=='percentage'){
		var request='&percentages=1';
    }if(name=='value'){
		var request='&percentages=0&show_default_currency=0';
    }if(name=='value_default_d2d'){
		var request='&percentages=0&show_default_currency=1';
    }

    Dom.get('change_'+parent+'_display_mode').innerHTML='&#x21b6 '+label;
   
   if(parent=='products'){
   var table=tables['table1'];
    var datasource=tables.dataSource1;
    dialog_change_products_display.hide();

    }else if(parent=='families'){
      var table=tables['table0'];
    var datasource=tables.dataSource0;
    dialog_change_families_display.hide();

    }else{
    return;
    }
    
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);   

}

function change_elements(){

ids=['elements_discontinued','elements_ separator ','sale','elements_private','elements_sale','elements_historic'];


if(Dom.hasClass(this,'selected')){

var number_selected_elements=0;
for(i in ids){
if(Dom.hasClass(ids[i],'selected')){
number_selected_elements++;
}
}

if(number_selected_elements>1){
Dom.removeClass(this,'selected')

}

}else{
Dom.addClass(this,'selected')

}

table_id=1;
 var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];
var request='';
for(i in ids){
if(Dom.hasClass(ids[i],'selected')){
request=request+'&'+ids[i]+'=1'
}else{
request=request+'&'+ids[i]+'=0'

}
}
  
 // alert(request)
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       


}

function show_details(){

Dom.get("department_info").style.display='';
        Dom.get("plot").style.display='';
        Dom.get("no_details_title").style.display='none';
        
    Dom.get("show_details").style.display='none';
        Dom.get("hide_details").style.display='';

    YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=department-details&value=1')
}

function hide_details(){

   Dom.get("department_info").style.display='none';
        Dom.get("plot").style.display='none';
        Dom.get("no_details_title").style.display='';

    Dom.get("show_details").style.display='';
            Dom.get("hide_details").style.display='none';

    //  alert('ar_sessions.php?tipo=update&keys=store-details&value=0')
    YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=department-details&value=0')
}


   


YAHOO.util.Event.addListener(window, "load", function() {
session_data = YAHOO.lang.JSON.parse(base64_decode(Dom.get('session_data').value));
    labels = session_data.label;
    state = session_data.state;


    tables = new function() {
 
	    var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	     var ColumnDefs = [{
            key: "code",
            label: labels.Code,
            width: 70,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }
        
        , {
            key: "name",
            label: labels.Name,
            hidden: (state.department.families.view == 'customers' || state.department.families.view == 'timeline' ? true : false),
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "products_for_sale",
            label: labels.Products,
            width: 100,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'general' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "percentage_out_of_stock",
            label: labels.OutofStock,
            width: 100,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'general' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "sales_1q",
            label: labels.Sales1q,
            width: 80,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'general' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "delta_sales_1q",
            label: '1y&Delta;',
            width: 80,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'general' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "sales",
            label: labels.Sales,
            width: 90,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'sales' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "delta_sales",
            label: '1y&Delta; ' + labels.Sales,
            width: 80,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'sales' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "profit",
            label: labels.Profit,
            width: 90,
            sortable: true,
            className: "aright",
            //hidden: (state.department.families.view == 'sales' ? false : true),
            hidden:true,
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "surplus",
            label: labels.Surplus,
            width: 60,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'stock' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "optimal",
            label: labels.OK,
            width: 60,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'stock' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "low",
            label: labels.Low,
            width: 60,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'stock' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "critical",
            label: labels.Critical,
            width: 60,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'stock' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "outofstock",
            label: labels.OutofStock,
            width: 70,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'stock' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "stock_error",
            label: labels.Unknown,
            width: 60,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'stock' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "last_update",
            label: labels.Last_Update,
            width: 180,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'timeline' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "from",
            label: labels.Since,
            width: 190,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'timeline' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "to",
            label: labels.Until,
            width: 180,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'timeline' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        },{
            key: "public_sale",
            label: labels.Public,
            width: 100,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'products' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        },{
            key: "private_sale",
            label: labels.Private,
            width: 90,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'products' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        },{
            key: "discontinued",
            label: labels.Discontinued,
            width: 90,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'products' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        },{
            key: "historic",
            label: labels.Historic,
            width: 90,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'products' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        },{
            key: "not_for_sale",
            label: labels.NotforSale,
            width: 90,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'products' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        },{
            key: "customers_active",
            label: labels.Active,
            width: 50,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'customers' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        },{
            key: "customers_active_75",
            label: labels.Active_75,
            width: 50,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'customers' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        },{
            key: "customers_active_50",
            label: labels.Active_50,
            width: 50,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'customers' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        },{
            key: "customers_active_25",
            label: labels.Active_25,
            width: 50,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'customers' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        },{
            key: "customers_losing",
            label: labels.Losing,
            width: 50,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'customers' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        },{
            key: "customers_losing_75",
            label: labels.Losing_75,
            width: 50,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'customers' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        },{
            key: "customers_losing_50",
            label: labels.Losing_50,
            width: 50,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'customers' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        },{
            key: "customers_losing_25",
            label: labels.Losing_25,
            width: 50,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'customers' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        },{
            key: "customers_lost",
            label: labels.Lost,
            width: 50,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'customers' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        },{
            key: "customers_lost_75",
            label: labels.Lost_75,
            width: 50,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'customers' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        },{
            key: "customers_lost_50",
            label: labels.Lost_50,
            width: 50,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'customers' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        },{
            key: "customers_lost_25",
            label: labels.Lost_25,
            width: 50,
            sortable: true,
            className: "aright",
            hidden: (state.department.families.view == 'customers' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }



        ];
        
         
request="ar_assets.php?tipo=families&parent=department&parent_key="+Dom.get('department_key').value;
	    this.dataSource0 = new YAHOO.util.DataSource(request);
	    //alert("ar_assets.php?tipo=families&parent=department&parent_key="+Dom.get('department_key').value)
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		   
		         rowsPerPage: "resultset.records_perpage",
                RecordOffset: "resultset.records_offset",
                rtext: "resultset.rtext",
                rtext_rpp: "resultset.rtext_rpp",
                sort_key: "resultset.sort_key",
                sort_dir: "resultset.sort_dir",
                tableid: "resultset.tableid",
                filter_msg: "resultset.filter_msg",
                totalRecords: "resultset.total_records"
		    
		},
		
		 fields: ['id', "code", "name", "delta_sales", "from", "last_update", "to", 'active', "stock_error", "stock_value", "outofstock", "sales", "profit", "surplus", "optimal", "low", "critical", "store", "department",
             'todo', 'discontinued','public_sale','private_sale','historic','not_for_sale',


            "products_for_sale","percentage_out_of_stock","sales_1q","delta_sales_1q",
              'customers_active', 'customers_active_75', 'customers_active_50', 'customers_active_25',
            'customers_losing', 'customers_losing_75', 'customers_losing_50', 'customers_losing_25',
            'customers_lost', 'customers_lost_75', 'customers_lost_50', 'customers_lost_25'
            ]
			 };
	    
	 
	    
	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource0, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilderwithTotals
								       ,paginator : new YAHOO.widget.Paginator({
									                       rowsPerPage: (state.department.families.nr + 1),

									       containers : 'paginator0', 
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									  key: state.department.families.order,
                                      dir: state.department.families.order_dir
								     }
							   ,dynamicData : true

						     }
						     );
						   
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
       this.table0.doBeforeLoadData=mydoBeforeLoadData;

	    
 this.table0.filter = {
            key: state.department.families.f_field,
            value: state.department.families.f_value
        };


		this.table0.table_id=tableid;
this.table0.request=request;


		
     	this.table0.subscribe("renderEvent", families_myrenderEvent);
   		this.table0.getDataSource().sendRequest(null, {
    		success:function(request, response, payload) {
        		if(response.results.length == 0) {
            		get_families_elements_numbers()
            
        		} else {
            		//this.onDataReturnInitializeTable(request, response, payload);
        		}
    		},
    		scope:this.table0,
    		argument:this.table0.getState()
		});
	    



	    var tableid=1;
	    var tableDivEL="table"+tableid;
	     ColumnDefs = [{
            key: "code",
            label: labels.Code,
            width: 100,
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "name",
            label: labels.Name,
            width: 400,
            hidden: (state.department.products.view == 'general' ? false : true),
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "smallname",
            label: labels.Name,
            width: 340,
            sortable: true,
            className: "aleft",
            className: "aleft",
            hidden: (state.department.products.view == 'general' || state.department.products.view == 'timeline' || state.department.products.view == 'properties' ? true : false),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "price",
            label: labels.Price,
            width: 100,
            hidden: (state.department.products.view == 'general' ? false : true),
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "web",
            label: labels.WebSales_State,
            width: 150,
            hidden: (state.department.products.view == 'general' || state.department.products.view == 'stock' ? false : true),
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "sold",
            label: labels.Sold,
            width: 90,
            hidden: (state.department.products.view == 'sales' ? false : true),
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "sales",
            label: labels.Sales,
            width: 90,
            hidden: (state.department.products.view == 'sales' ? false : true),
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "delta_sales",
            label: '1y&Delta; ' + labels.Sales,
            width: 80,
            sortable: true,
            className: "aright",
            hidden: (state.department.products.view == 'sales' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "profit",
            label: labels.Profit,
            width: 90,
            hidden: (state.department.products.view == 'sales' ? false : true),
            sortable: true,
            className: "aright",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "stock",
            label: labels.Available,
            width: 65,
            sortable: true,
            className: "aright",
            hidden: (state.department.products.view == 'general' || state.department.products.view == 'stock' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "stock_state",
            label: labels.State,
            width: 70,
            sortable: true,
            className: "aright",
            hidden: (state.department.products.view == 'stock' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "stock_forecast",
            label: labels.Forecast,
            width: 70,
            sortable: true,
            className: "aright",
            hidden: (state.department.products.view == 'stock' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "parts",
            label: labels.Parts,
            width: 130,
            hidden: (state.department.products.view == 'parts' ? false : true),
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "supplied",
            label: labels.Supplied_by,
            width: 130,
            hidden: (state.department.products.view == 'parts' ? false : true),
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "gmroi",
            label: labels.GMROI,
            width: 100,
            sortable: true,
            className: "aright",
            hidden: (state.department.products.view == 'parts' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "expcode",
            label: labels.Tariff_Code,
            width: 160,
            hidden: (state.department.products.view == 'cats' ? false : true),
            sortable: true,
            className: "aleft",
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_ASC
            }
        }, {
            key: "last_update",
            label: labels.Last_Update,
            width: 180,
            sortable: true,
            className: "aright",
            hidden: (state.department.products.view == 'timeline' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "from",
            label: labels.Since,
            width: 190,
            sortable: true,
            className: "aright",
            hidden: (state.department.products.view == 'timeline' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "to",
            label: labels.Until,
            width: 180,
            sortable: true,
            className: "aright",
            hidden: (state.department.products.view == 'timeline' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "package_type",
            label: labels.Pkg_Type,
            width: 70,
            sortable: true,
            className: "aright",
            hidden: (state.department.products.view == 'properties' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "package_weight",
            label: labels.Pkg_Weight,
            width: 100,
            sortable: true,
            className: "aright",
            hidden: (state.department.products.view == 'properties' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "package_dimension",
            label: labels.Pkg_Dim,
            width: 120,
            sortable: true,
            className: "aright",
            hidden: (state.department.products.view == 'properties' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "package_volume",
            label: labels.Pkg_Vol,
            width: 110,
            sortable: true,
            className: "aright",
            hidden: (state.department.products.view == 'properties' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "unit_weight",
            label: labels.Unit_Weight,
            width: 100,
            sortable: true,
            className: "aright",
            hidden: (state.department.products.view == 'properties' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }, {
            key: "unit_dimension",
            label: labels.Unit_Dim,
            width: 120,
            sortable: true,
            className: "aright",
            hidden: (state.department.products.view == 'properties' ? false : true),
            sortOptions: {
                defaultDir: YAHOO.widget.DataTable.CLASS_DESC
            }
        }


        ];

request="ar_assets.php?tipo=products&parent=department&tableid=1&parent_key="+Dom.get('department_key').value+'&sf=0';
	    this.dataSource1 = new YAHOO.util.DataSource(request);
	    this.dataSource1.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource1.connXhrMode = "queueRequests";
	    this.dataSource1.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		fields: [
			 "last_update","from","to",
			 'id',"package_type","package_weight","package_dimension","package_volume","unit_weight","unit_dimension"
			 ,"code"
			 ,"name","stock","stock_value"
			 ,'sales','profit','margin','sold',"parts","supplied","gmroi","family","dept","expcode","smallname","state","web","delta_sales"
			 ]};
	    
	    this.table1 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource1, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilderwithTotals
								       ,paginator : new YAHOO.widget.Paginator({
									                       rowsPerPage: (state.department.products.nr + 1),
									      containers : 'paginator1', 
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									  key: state.department.products.order,
                                      dir: state.department.products.order_dir
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;



   this.table1.filter = {
            key: state.department.products.f_field,
            value: state.department.products.f_value
        };

this.table1.table_id=tableid;
this.table1.request=request;




         	this.table1.subscribe("renderEvent", products_myrenderEvent);
   		this.table1.getDataSource().sendRequest(null, {
    		success:function(request, response, payload) {
        		if(response.results.length == 0) {
            		get_products_elements_numbers()
            
        		} else {
            		//this.onDataReturnInitializeTable(request, response, payload);
        		}
    		},
    		scope:this.table1,
    		argument:this.table1.getState()
		});
	    



		    var tableid=2;
		    var tableDivEL="table"+tableid;

  var ColumnDefs = [
				      {key:"date", label:labels.Date, width:200,sortable:false,className:"aright"}
				      ,{key:"invoices", label:labels.Invoices, width:100,sortable:false,className:"aright"}
				      ,{key:"customers", label:labels.Customers, width:100,sortable:false,className:"aright"}
				      ,{key:"sales", label:labels.Sales, width:100,sortable:false,className:"aright"}
				      
				

				      ];

		 
		    request="ar_reports.php?tipo=assets_sales_history&scope=assets&parent=department&parent_key="+Dom.get('department_key').value+"&tableid="+tableid+'&from='+Dom.get('from').value+'&to='+Dom.get('to').value;
		   // alert(request)
		  
		  this.dataSource2 = new YAHOO.util.DataSource(request);
	    this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource2.connXhrMode = "queueRequests";
 
	    this.dataSource2.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
			
		

	fields: [
				 "date","invoices","customers","sales"

				 ]};

	  
	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource2, {
							 //draggableColumns:true,
							// formatRow: myRowFormatter,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									                             rowsPerPage: state.department.sales_history.nr ,

									       containers : 'paginator2', 
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									  key: state.department.sales_history.order,
                                      dir: state.department.sales_history.order_dir
								     }
							   ,dynamicData : true  

						     }
						     );
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table2.doBeforePaginator = mydoBeforePaginatorChange;
      	this.table2.request=request;
 		this.table2.table_id=tableid;
   		this.table2.subscribe("renderEvent", myrenderEvent);




	       this.table2.filter = {
            key: state.department.sales_history.f_field,
            value: state.department.sales_history.f_value
        };



 var tableid=4; 
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
				    {key:"code", label:labels.Code, width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"type", label:labels.Type, width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				    ,{key:"title", label:labels.Title, width:300,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"url", label:labels.URL, width:300,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

						    
				    
				    
				     ];

	    this.dataSource4 = new YAHOO.util.DataSource("ar_sites.php?tipo=pages&sf=0&parent=department&tableid=4&parent_key="+Dom.get('department_key').value);
	    this.dataSource4.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource4.connXhrMode = "queueRequests";
	    this.dataSource4.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: { 
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		fields: [
			 'id','title','code','url','type'
						 ]};
	    
	    this.table4 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
						     this.dataSource4, {
							 //draggableColumns:true,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
								        
									      									                             rowsPerPage: state.department.pages.nr ,

									      
									      containers : 'paginator4', 
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									  key: state.department.pages.order,
                                      dir: state.department.pages.order_dir
								     }
							   ,dynamicData : true

						     }
						     );
	    this.table4.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table4.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table4.doBeforePaginatorChange = mydoBeforePaginatorChange;
   		this.table4.table_id=tableid;
   		this.table4.request=request;
     	this.table4.subscribe("renderEvent", myrenderEvent);

		
	    
	

	    this.table4.filter = {
            key: state.department.pages.f_field,
            value: state.department.pages.f_value
        };





    var tableid=5;
	    var tableDivEL="table"+tableid;


	    var ColumnDefs = [ 
				    {key:"code", label:labels.Code, width:60,sortable:true, className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"name", label:labels.Name,width:370, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   				   ,{key:"state", label:labels.State,width:70, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   ,{key:"sold", label:labels.Sold,width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"sales", label:labels.Sales,width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit", label:labels.Profit,width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   
							       ];
request="ar_assets.php?tipo=SUSPENDED_family_sales_report&tableid="+tableid+"&parent=department&sf=0"+'&parent_key='+Dom.get('department_key').value+'&from='+Dom.get('from').value+'&to='+Dom.get('to').value;
	
	 this.dataSource5 = new YAHOO.util.DataSource(request);
	    this.dataSource5.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource5.connXhrMode = "queueRequests";
 
	    this.dataSource5.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		fields: [
			 'id'
			 ,"code"
			 ,"name","stock","stock_value","record_type"
			 ,'sales','profit','margin','sold',"parts","supplied","gmroi","department","dept","expcode","state","web","smallname","delta_sales"
			 ]};
	    


	    this.table5 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource5, {
							 //draggableColumns:true,
							// formatRow: myRowFormatter,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       			rowsPerPage: state.department.family_sales.nr ,

									       containers : 'paginator5', 
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info5'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									  key: state.department.family_sales.order,
                                      dir: state.department.family_sales.order_dir
								     }
							   ,dynamicData : true  

						     }
						     );
	    this.table5.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table5.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table5.doBeforePaginator = mydoBeforePaginatorChange;
      this.table5.request=request;
  this.table5.table_id=tableid;
     this.table5.subscribe("renderEvent", myrenderEvent);


	    this.table5.filter = {
            key: state.department.family_sales.f_field,
            value: state.department.family_sales.f_value
        };



  var tableid=6;
	    var tableDivEL="table"+tableid;


	    var ColumnDefs = [ 
				    {key:"code", label:labels.Code, width:60,sortable:true, className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"name", label:labels.Name,width:370, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   				   ,{key:"state", label:labels.State,width:70, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   ,{key:"sold", label:labels.Sold,width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				    ,{key:"sales", label:labels.Sales,width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				    ,{key:"profit", label:labels.Profit,width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				   
							       ];
request="ar_assets.php?tipo=SUSPENDED_product_sales_report&tableid="+tableid+"&parent=department&sf=0"+'&parent_key='+Dom.get('department_key').value+'&from='+Dom.get('from').value+'&to='+Dom.get('to').value;
	//alert(request)
	 this.dataSource6 = new YAHOO.util.DataSource(request);
	    this.dataSource6.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource6.connXhrMode = "queueRequests";
 
	    this.dataSource6.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rtext:"resultset.rtext",
		    rtext_rpp:"resultset.rtext_rpp",
		    rowsPerPage:"resultset.records_perpage",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records"
		},
		
		fields: [
			 'id'
			 ,"code"
			 ,"name","stock","stock_value","record_type"
			 ,'sales','profit','margin','sold',"parts","supplied","gmroi","department","dept","expcode","state","web","smallname","delta_sales"
			 ]};
	    


	    this.table6 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
						     this.dataSource6, {
							 //draggableColumns:true,
							// formatRow: myRowFormatter,
							   renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									       									       			rowsPerPage: state.department.product_sales.nr ,

									       
									       containers : 'paginator6', 
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info6'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
									  })
								     
								     ,sortedBy : {
									  key: state.department.product_sales.order,
                                      dir: state.department.product_sales.order_dir
								     }
							   ,dynamicData : true  

						     }
						     );
	    this.table6.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table6.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table6.doBeforePaginator = mydoBeforePaginatorChange;
      this.table6.request=request;
  this.table6.table_id=tableid;
     this.table6.subscribe("renderEvent", myrenderEvent);


	    
 this.table6.filter = {
            key: state.department.product_sales.f_field,
            value: state.department.product_sales.f_value
        };

		    var tableid=7; 
		    var tableDivEL="table"+tableid;  
		    
		    
		    var myRowFormatter = function(elTr, oRecord) {		   
				if (oRecord.getData('type') =='Orders') {
					Dom.addClass(elTr, 'store_history_orders');
				}else if (oRecord.getData('type') =='Notes') {
					Dom.addClass(elTr, 'store_history_notes');
				}else if (oRecord.getData('type') =='Changes') {
					Dom.addClass(elTr, 'store_history_changes');
				}
				return true;
			}; 
		    
		    
		this.prepare_note = function(elLiner, oRecord, oColumn, oData) {
          
            if(oRecord.getData("strikethrough")=="Yes") { 
            Dom.setStyle(elLiner,'text-decoration','line-through');
            Dom.setStyle(elLiner,'color','#777');

            }
            elLiner.innerHTML=oData
        };
        		    
		    var ColumnDefs = [
				       {key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
				      ,{key:"date", label:labels.Date,className:"aright",width:120,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
				      ,{key:"time", label:labels.Time,className:"aleft",width:70}
				      ,{key:"handle", label:labels.Author,className:"aleft",width:100,sortable:true,sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				      ,{key:"note", formatter:this.prepare_note,label:labels.Notes,className:"aleft",width:500}
                      ,{key:"delete", label:"",width:12,sortable:false,action:'delete',object:'store_history'}
                      ,{key:"edit", label:"",width:12,sortable:false,action:'edit',object:'store_history'}

					   ];
		request="ar_history.php?tipo=store_history&parent=department&parent_key="+Dom.get('department_key').value+"&sf=0&tableid="+tableid
	//	alert(request)
		    this.dataSource7  = new YAHOO.util.DataSource(request);
		    this.dataSource7.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource7.connXhrMode = "queueRequests";
	    this.dataSource7.responseSchema = {
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
                  fields: ["note","date","time","handle","delete","can_delete" ,"delete_type","key","edit","type","strikethrough"]};
		    this.table7 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource7
								 , {
								 formatRow: myRowFormatter,
								     renderLoopSize: 5,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      									       									       			rowsPerPage: state.department.history.nr ,

									      
									      containers : 'paginator7', 
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
 									      
 									      alwaysVisible:false,
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info7'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 
									  key: state.department.history.order,
                                      dir: state.department.history.order_dir
									 
								     },
								     dynamicData : true

								  }
								   
								 );
	    	this.table7.handleDataReturnPayload =myhandleDataReturnPayload;
	        this.table7.doBeforeSortColumn = mydoBeforeSortColumn;
	        this.table7.doBeforePaginatorChange = mydoBeforePaginatorChange;


 this.table7.filter = {
            key: state.department.history.f_field,
            value: state.department.history.f_value
        };

	        this.table7.subscribe("cellMouseoverEvent", highlightEditableCell);
	        this.table7.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	        this.table7.subscribe("cellClickEvent", onCellClick);            
			this.table7.table_id=tableid;
     		this.table7.subscribe("renderEvent", myrenderEvent);
     		
     		

     		 var tableid=10; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var productsColumnDefs = [
	    
	    				       {key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 

                                     ,{key:"description", label:labels.Description, width:350,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
					,{key:"orders", label:labels.Orders,  width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"customers", label:labels.Customers,  width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
					,{key:"duration", label:labels.Duration,  width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

				 
				 ];
	    //?tipo=products&tid=0"
	    
	    request="ar_deals.php?tipo=deals&parent=department&parent_key="+Dom.get('department_key').value+'&tableid=10&referrer=department'
	   // alert(request);
	    this.dataSource10 = new YAHOO.util.DataSource(request);
	    this.dataSource10.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource10.connXhrMode = "queueRequests";
	    this.dataSource10.responseSchema = {
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
		
		fields: ["name","key","description","duration","orders","code","customers"]};
		

	  this.table10 = new YAHOO.widget.DataTable(tableDivEL, productsColumnDefs,
								   this.dataSource10
								 , {
							 renderLoopSize: 50,generateRequest : myRequestBuilder
							 //,initialLoad:false
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage: state.department.offers.nr ,

									      
									      containers : 'paginator10', 
                pageReportTemplate: '(' + labels.Page + ' {currentPage} ' + labels.of + ' {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info10'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     , sortedBy: {
                key: state.department.offers.order,
                dir: state.department.offers.order_dir
            },
								     
								     
								     
								     dynamicData : true

								  }
								   
								 );
	    
		this.table10.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table10.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table10.doBeforePaginatorChange = mydoBeforePaginatorChange;
		this.table10.request=request;
  		this.table10.table_id=tableid;
     	this.table10.subscribe("renderEvent", myrenderEvent);
		this.table10.getDataSource().sendRequest(null, {
		    success: function(request, response, payload) {
		        if (response.results.length == 0) {
		      
		            // get_offers_elements_numbers()

		        } else {
		             this.onDataReturnInitializeTable(request, response, payload);
		        }
		    },
		    scope: this.table10,
		    argument: this.table10.getState()
		});
	  
	    
 this.table10.filter = {
            key: state.department.offers.f_field,
            value: state.department.offers.f_value
        };
     		

	};

	get_thumbnails(1)
	get_thumbnails(0)
    });




function change_sales_period(){
  tipo=this.id;
  
 
  ids=['department_period_yesterday','department_period_last_m','department_period_last_w','department_period_all','department_period_three_year','department_period_year','department_period_six_month','department_period_quarter','department_period_month','department_period_ten_day','department_period_week','department_period_yeartoday','department_period_monthtoday','department_period_weektoday','department_period_today'];

 Dom.removeClass(ids,"selected")
 Dom.addClass(this,"selected")
   period=this.getAttribute('period');
 YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=store-departments-period&value='+period ,{});

Dom.setStyle(['info_yesterday','info_last_m','info_last_w','info_all','info_three_year','info_year','info_six_month','info_quarter','info_month','info_ten_day','info_week','info_yeartoday','info_monthtoday','info_weektoday','info_today'],'display','none')


Dom.setStyle(['info2_yesterday','info2_last_m','info2_last_w','info2_all','info2_three_year','info2_year','info2_six_month','info2_quarter','info2_month','info2_ten_day','info2_week','info2_yeartoday','info2_monthtoday','info2_weektoday','info2_today'],'display','none')
Dom.setStyle(['info_'+period,'info2_'+period],'display','')

}





function change_family_elements(){
ids=['elements_family_discontinued','elements_family_discontinuing','elements_family_normal','elements_family_inprocess','elements_family_nosale'];


if(Dom.hasClass(this,'selected')){

var number_selected_elements=0;
for(i in ids){
if(Dom.hasClass(ids[i],'selected')){
number_selected_elements++;
}
}

if(number_selected_elements>1){
Dom.removeClass(this,'selected')

}

}else{
Dom.addClass(this,'selected')

}

table_id=0;
 var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];
var request='';
for(i in ids){
if(Dom.hasClass(ids[i],'selected')){
request=request+'&'+ids[i]+'=1'
}else{
request=request+'&'+ids[i]+'=0'

}
}
  
 // alert(request)
    datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       


}




function show_dialog_change_products_display() {
    region1 = Dom.getRegion('change_products_display_mode');
    region2 = Dom.getRegion('change_products_display_menu');
    var pos = [region1.right - region2.width, region1.bottom]
    Dom.setXY('change_products_display_menu', pos);
    dialog_change_products_display.show();
}

function change_table_type(parent, tipo, label, table_id) {

    Dom.get('change_' + parent + '_table_type').innerHTML = '&#x21b6 ' + label;

    if (tipo == 'list') {

        if (Dom.get('change_' + parent + '_display_mode') != undefined && Dom.get(parent + '_view').view == 'sales') Dom.setStyle('change_' + parent + '_display_mode', 'display', '')
        Dom.setStyle('thumbnails' + table_id, 'display', 'none')
        Dom.setStyle(['table' + table_id, 'list_options' + table_id, 'table_view_menu' + table_id, 'change_products_display_mode'], 'display', '')
    } else {


        Dom.setStyle('change_' + parent + '_display_mode', 'display', 'none')

        Dom.setStyle('thumbnails' + table_id, 'display', '')
        Dom.setStyle(['table' + table_id, 'list_options' + table_id, 'table_view_menu' + table_id], 'display', 'none')
    }

    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=department-' + parent + '-table_type&value=' + escape(tipo), {});

    if (parent == 'products') {
        dialog_change_products_table_type.hide();
    } else if (parent == 'families') {
        dialog_change_families_table_type.hide();
    } 


}





function show_dialog_change_families_display(){
	region1 = Dom.getRegion('change_families_display_mode'); 
    region2 = Dom.getRegion('change_families_display_menu'); 
	var pos =[region1.right-region2.width,region1.bottom]
	Dom.setXY('change_families_display_menu', pos);
	dialog_change_families_display.show();
}

function show_dialog_change_products_table_type(){
	region1 = Dom.getRegion('change_products_table_type'); 
    region2 = Dom.getRegion('change_products_table_type_menu'); 
	var pos =[region1.right-region2.width,region1.bottom]
	Dom.setXY('change_products_table_type_menu', pos);
	dialog_change_products_table_type.show();
}

function show_dialog_change_families_table_type(){
	region1 = Dom.getRegion('change_families_table_type'); 
    region2 = Dom.getRegion('change_families_table_type_menu'); 
	var pos =[region1.right-region2.width,region1.bottom]
	Dom.setXY('change_families_table_type_menu', pos);
	dialog_change_families_table_type.show();
}


function change_sales_sub_block(o){
Dom.removeClass(['plot_department_sales','department_product_sales','department_family_sales','department_sales_timeseries','department_sales_calendar'],'selected')
Dom.addClass(o,'selected')


Dom.setStyle(['sub_block_plot_department_sales','sub_block_department_family_sales','sub_block_department_product_sales','sub_block_department_sales_timeseries','sub_block_department_sales_calendar'],'display','none')
Dom.setStyle('sub_block_'+o.id,'display','')

YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=department-sales_sub_block_tipo&value='+o.id ,{});

}

function change_timeseries_type(e,table_id) {
ids=['family_sales_history_type_year','family_sales_history_type_month','family_sales_history_type_week','family_sales_history_type_day'];
Dom.removeClass(ids,'selected')
Dom.addClass(this,'selected')

type=this.getAttribute('tipo')


    var table=tables['table'+table_id];
    var datasource=tables['dataSource'+table_id];

    var request='&sf=0&type='+type;
 datasource.sendRequest(request,table.onDataReturnInitializeTable, table);       
};




function get_sales(from,to){
var request = 'ar_assets.php?tipo=get_asset_sales_data&parent=department&parent_key=' + Dom.get('department_key').value + '&from='+from+'&to='+to
   //alert(request);
   YAHOO.util.Connect.asyncRequest('POST', request, {
   success: function(o) {
           //alert(o.responseText)
            var r = YAHOO.lang.JSON.parse(o.responseText);

           
                      		Dom.get('sales_amount').innerHTML=r.sales
                      		Dom.get('profits').innerHTML=r.profits
                      		Dom.get('invoices').innerHTML=r.invoices
                      		Dom.get('customers').innerHTML=r.customers
                      		Dom.get('outers').innerHTML=r.outers

           
        }
    });

}

function post_change_period_actions(r) {
period=r.period;
to=r.to;
from=r.from;


    request = '&from=' + from + '&to=' + to;

    table_id = 2
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
    table_id = 5
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
    table_id = 6
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);

    Dom.get('rtext2').innerHTML = '<img src="art/loading.gif" style="height:12.9px"/> '+Dom.get('wait_label').value
    Dom.get('rtext_rpp2').innerHTML = '';
    Dom.get('rtext5').innerHTML = '<img src="art/loading.gif" style="height:12.9px"/> '+Dom.get('wait_label').value
    Dom.get('rtext_rpp5').innerHTML = '';
    Dom.get('rtext6').innerHTML = '<img src="art/loading.gif" style="height:12.9px"/> '+Dom.get('wait_label').value
    Dom.get('rtext_rpp6').innerHTML = '';


    get_sales(from, to)


}


function new_deal () {
  location.href = "new_deal.php?parent=department&parent_key="+Dom.get('department_key').value;
}

function init() {

  dialog_export['products'] = new YAHOO.widget.Dialog("dialog_export_products", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_export['products'].render();
    
    dialog_export['families'] = new YAHOO.widget.Dialog("dialog_export_families", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_export['families'].render();
    
      
    
    Event.addListener("export_products", "click", show_export_dialog, 'products');
    Event.addListener("export_csv_products", "click", export_table, {
        output: 'csv',
        table: 'products',
        parent: 'department',
        'parent_key': Dom.get('department_key').value
    });
    Event.addListener("export_xls_products", "click", export_table, {
        output: 'xls',
        table: 'products',
        parent: 'department',
        'parent_key': Dom.get('department_key').value
    });
    
        Event.addListener("export_families", "click", show_export_dialog, 'families');
    Event.addListener("export_csv_families", "click", export_table, {
        output: 'csv',
        table: 'families',
        parent: 'department',
        'parent_key': Dom.get('department_key').value
    });
    Event.addListener("export_xls_families", "click", export_table, {
        output: 'xls',
        table: 'families',
        parent: 'department',
        'parent_key': Dom.get('department_key').value
    });
   
    


    Event.addListener("export_result_download_link_products", "click", download_export_file, 'products');




    get_sales(Dom.get('from').value, Dom.get('to').value)

    //get_product_element_numbers()
    //get_product_sales_element_numbers()
    //get_family_element_numbers()
    //get_family_sales_element_numbers()



    dialog_change_products_display = new YAHOO.widget.Dialog("change_products_display_menu", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_change_products_display.render();
    YAHOO.util.Event.addListener("change_products_display_mode", "click", show_dialog_change_products_display);

    dialog_change_families_display = new YAHOO.widget.Dialog("change_families_display_menu", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_change_families_display.render();

    YAHOO.util.Event.addListener("change_families_display_mode", "click", show_dialog_change_families_display);

    dialog_change_products_table_type = new YAHOO.widget.Dialog("change_products_table_type_menu", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_change_products_table_type.render();
    YAHOO.util.Event.addListener("change_products_table_type", "click", show_dialog_change_products_table_type);

  dialog_change_families_table_type = new YAHOO.widget.Dialog("change_families_table_type_menu", {
        visible: false,
        close: true,
        underlay: "none",
        draggable: false
    });
    dialog_change_families_table_type.render();
    YAHOO.util.Event.addListener("change_families_table_type", "click", show_dialog_change_families_table_type);


    Event.addListener(['elements_family_discontinued', 'elements_family_discontinuing', 'elements_family_normal', 'elements_family_inprocess', 'elements_family_nosale'], "click", change_family_elements);
   
    Event.addListener(['elements_discontinued', 'elements_nosale', 'elements_private', 'elements_sale', 'elements_historic'], "click", change_elements);



    Event.addListener(['details', 'families', 'products', 'categories', 'deals', 'web', 'sales','notes'], "click", change_block);

	
    init_search('products_store');



    YAHOO.util.Event.addListener('clean_table_filter_show0', "click", show_filter, 0);
    YAHOO.util.Event.addListener('clean_table_filter_hide0', "click", hide_filter, 0);
    YAHOO.util.Event.addListener('clean_table_filter_show1', "click", show_filter, 1);
    YAHOO.util.Event.addListener('clean_table_filter_hide1', "click", hide_filter, 1);

  
    var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS.queryMatchContains = true;
    var oAutoComp = new YAHOO.widget.AutoComplete("f_input0", "f_container0", oACDS);
    oAutoComp.minQueryLength = 0;

    var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
    oACDS1.queryMatchContains = true;
    oACDS1.table_id = 1;
    var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1", "f_container1", oACDS1);
    oAutoComp1.minQueryLength = 0;



    ids = ['family_general', 'family_sales', 'family_stock','family_timeline'];
    YAHOO.util.Event.addListener(ids, "click", change_family_view, {
        'table_id': 0,
        'parent': 'department'
    })




    ids = ['family_period_all', 'family_period_three_year', 'family_period_year', 'family_period_yeartoday', 'family_period_six_month', 'family_period_quarter', 'family_period_month', 'family_period_ten_day', 'family_period_week'];
    Event.addListener(ids, "click", change_table_period, {
        'table_id': 0,
        'subject': 'family'
    });
    ids = ['family_avg_totals', 'family_avg_month', 'family_avg_week', "family_avg_month_eff", "family_avg_week_eff"];
    Event.addListener(ids, "click", change_avg, {
        'table_id': 0,
        'subject': 'family'
    });
    ids = ['product_general', 'product_sales', 'product_stock', 'product_parts', 'product_cats','product_timeline','product_properties'];
    Event.addListener(ids, "click", change_product_view, {
        'table_id': 1,
        'parent': 'department'
    });
    ids = ['product_period_all', 'product_period_three_year', 'product_period_year', 'product_period_yeartoday', 'product_period_six_month', 'product_period_quarter', 'product_period_month', 'product_period_ten_day', 'product_period_week'];
    Event.addListener(ids, "click", change_table_period, {
        'table_id': 1,
        'subject': 'product'
    });
    ids = ['product_avg_totals', 'product_avg_month', 'product_avg_week', "product_avg_month_eff", "product_avg_week_eff"];
    Event.addListener(ids, "click", change_avg, {
        'table_id': 1,
        'subject': 'product'
    });





    YAHOO.util.Event.addListener('product_submit_search', "click", submit_search, "product");
    YAHOO.util.Event.addListener('product_search', "keydown", submit_search_on_enter, "product");

    ids = ['department_period_yesterday', 'department_period_last_m', 'department_period_last_w', 'department_period_all', 'department_period_three_year', 'department_period_year', 'department_period_yeartoday', 'department_period_six_month', 'department_period_quarter', 'department_period_month', 'department_period_ten_day', 'department_period_week', 'department_period_monthtoday', 'department_period_weektoday', 'department_period_today'];
    YAHOO.util.Event.addListener(ids, "click", change_sales_period);


 dialog_sales_history_timeline_group = new YAHOO.widget.Dialog("dialog_sales_history_timeline_group", {visible : false,close:true,underlay: "none",draggable:false});
dialog_sales_history_timeline_group.render();
YAHOO.util.Event.addListener("change_sales_history_timeline_group", "click", show_dialog_sales_history_timeline_group);



  

}

YAHOO.util.Event.onDOMReady(init);


YAHOO.util.Event.onContentReady("plot_period_menu", function () {
	 var oMenu = new YAHOO.widget.Menu("plot_period_menu", { context:["plot_period","br", "tr"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("plot_period", "click", oMenu.show, null, oMenu);
    });
YAHOO.util.Event.onContentReady("plot_category_menu", function () {
	 var oMenu = new YAHOO.widget.Menu("plot_category_menu", { context:["plot_category","br", "tr"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("plot_category", "click", oMenu.show, null, oMenu);
    });
YAHOO.util.Event.onContentReady("info_period_menu", function () {
	 var oMenu = new YAHOO.widget.Menu("info_period_menu", { context:["info_period","tr", "br"]  });
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 YAHOO.util.Event.addListener("info_period", "click", oMenu.show, null, oMenu);
    });

YAHOO.util.Event.onContentReady("rppmenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {trigger:"rtext_rpp0"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 });
YAHOO.util.Event.onContentReady("filtermenu0", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {trigger:"filter_name0"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
 
 YAHOO.util.Event.onContentReady("rppmenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {trigger:"rtext_rpp1"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
	 });
YAHOO.util.Event.onContentReady("filtermenu1", function () {
	 var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {trigger:"filter_name1"});
	 oMenu.render();
	 oMenu.subscribe("show", oMenu.focus);
    });
    
    
