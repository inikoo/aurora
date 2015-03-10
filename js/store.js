var session_data,labels,state;


var link='store.php';

var Dom   = YAHOO.util.Dom;
var Event  =YAHOO.util.Event;
var dialog_change_families_display;
var dialog_change_products_display;
var dialog_change_departments_display;
var dialog_choose_category;
var dialog_change_departments_table_type;


var already_clicked_page_elements_click = false;

function change_page_elements() {
  el=this;
  var elements_type='';
  if (already_clicked_page_elements_click) {
        already_clicked_page_elements_click = false; // reset
        clearTimeout(alreadyclickedTimeout); // prevent this from happening
        change_page_elements_dblclick(el, elements_type);
      } else {
        already_clicked_page_elements_click = true;
        alreadyclickedTimeout = setTimeout(function() {
            already_clicked_page_elements_click = false; // reset when it happens
            change_page_elements_click(el, elements_type);
        }, 300); // <-- dblclick tolerance here
      }
      return false;
    }

    function change_page_elements_click(el,elements_type) {

     ids = ['elements_System', 'elements_Info', 'elements_Department', 'elements_Family','elements_Product', 'elements_ProductCategory', 'elements_FamilyCategory'];


     if (Dom.hasClass(el, 'selected')) {

      var number_selected_elements = 0;
      for (i in ids) {
        if (Dom.hasClass(ids[i], 'selected')) {
          number_selected_elements++;
        }
      }

      if (number_selected_elements > 1) {
        Dom.removeClass(el, 'selected')

      }

    } else {
      Dom.addClass(el, 'selected')

    }

    table_id = 4;
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    var request = '';
    for (i in ids) {
      if (Dom.hasClass(ids[i], 'selected')) {
        request = request + '&' + ids[i] + '=1'
      } else {
        request = request + '&' + ids[i] + '=0'

      }
    }

    // alert(request)
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);


  }

  function change_page_elements_dblclick(el,elements_type) {

   ids = ['elements_System', 'elements_Info', 'elements_Department', 'elements_Family','elements_Product', 'elements_ProductCategory', 'elements_FamilyCategory'];



   Dom.removeClass(ids, 'selected')

   Dom.addClass(el, 'selected')

   table_id = 4;
   var table = tables['table' + table_id];
   var datasource = tables['dataSource' + table_id];
   var request = '';
   for (i in ids) {
    if (Dom.hasClass(ids[i], 'selected')) {
      request = request + '&' + ids[i] + '=1'
    } else {
      request = request + '&' + ids[i] + '=0'

    }
  }

    // alert(request)
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);


  }





  function change_elements() {

    ids = ['elements_discontinued', 'elements_nosale', 'elements_private', 'elements_sale', 'elements_historic'];


    if (Dom.hasClass(this, 'selected')) {

      var number_selected_elements = 0;
      for (i in ids) {
        if (Dom.hasClass(ids[i], 'selected')) {
          number_selected_elements++;
        }
      }

      if (number_selected_elements > 1) {
        Dom.removeClass(this, 'selected');

      }

    } else {
      Dom.addClass(this, 'selected');

    }

    table_id = 2;
    var table = tables['table' + table_id];
    var datasource = tables['dataSource' + table_id];
    var request = '';
    for (i in ids) {
      if (Dom.hasClass(ids[i], 'selected')) {
        request = request + '&' + ids[i] + '=1'
      } else {
        request = request + '&' + ids[i] + '=0'

      }
    }

    // alert(request)
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);


  }

  function change_plot(o) {
    ids = ['plot_store', 'plot_top_departments', 'plot_pie'];
    block_ids = ['plot_store_div', 'plot_top_departments_div', 'plot_pie_div'];
    Dom.setStyle(block_ids, 'display', 'none');
    Dom.setStyle(o.id + '_div', 'display', '');
    Dom.removeClass(ids, 'selected');
    Dom.addClass(o, 'selected');


    if (o.id == 'plot_store') plot = 'store';
    else if (o.id == 'plot_top_departments') plot = 'top_departments';
    else if (o.id == 'plot_pie') plot = 'pie';

    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=store-plot&value=' + plot, {});
  }


  function change_block() {
    ids = ['details', 'departments', 'families', 'products', 'categories', 'deals', 'websites', 'sales'];
    block_ids = ['block_details', 'block_sites', 'block_departments', 'block_families', 'block_products', 'block_categories', 'block_sales', 'block_deals', 'block_websites'];
    Dom.setStyle(block_ids, 'display', 'none');
    Dom.setStyle('block_' + this.id, 'display', '');
    Dom.removeClass(ids, 'selected');
    Dom.addClass(this, 'selected');
    
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=store-block_view&value=' + this.id, {});
  }

  function change_deals_block() {
    ids = ['deals_details', 'campaigns', 'offers'];
    block_ids =['block_deals_details', 'block_campaigns', 'block_offers'];
    Dom.setStyle(block_ids, 'display', 'none');
    Dom.setStyle('block_' + this.id, 'display', '');
    Dom.removeClass(ids, 'selected');
    Dom.addClass(this, 'selected');
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=store-deals_block_view&value=' + this.id, {});
  }


  function change_websites_block() {
    ids = ['sites', 'pages'];
    block_ids =['block_websites_sites', 'block_websites_pages'];
    Dom.setStyle(block_ids, 'display', 'none');
    Dom.setStyle('block_websites_' + this.getAttribute('block_id'), 'display', '');
    Dom.removeClass(ids, 'selected');
    Dom.addClass(this, 'selected');
    
    
    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=store-websites_block_view&value=' + this.getAttribute('block_id'), {});
  }


  function change_pages_view(e) {

    var table = tables['table4'];
    if (this.id == 'page_visitors') {
      tipo = 'visitors'
    } else if (this.id == 'page_general') {
      tipo = 'general'

    } else if (this.id == 'page_products') {
      tipo = 'products'

    } else {
      return
    }



    table.hideColumn('type');
    

    
    table.hideColumn('title');
    table.hideColumn('users');
    table.hideColumn('visitors');
    table.hideColumn('sessions');
    table.hideColumn('requests');
    table.hideColumn('percentage_products_out_of_stock');
    table.hideColumn('products_out_of_stock');
    table.hideColumn('products');
    table.hideColumn('list_products');
    table.hideColumn('button_products');
    table.hideColumn('products_sold_out');
    table.hideColumn('link_title');

    

    if (tipo == 'visitors') {
      Dom.get('page_period_options').style.display = '';
      table.showColumn('users');
      table.showColumn('visitors');
      table.showColumn('sessions');
      table.showColumn('requests');
      table.showColumn('link_title');

    } else if (tipo == 'general') {
      Dom.get('page_period_options').style.display = 'none';
      table.showColumn('title');
      table.showColumn('type');
      table.showColumn('link_title');


    }
    if (tipo == 'products') {

      Dom.get('page_period_options').style.display = 'none';

      table.showColumn('percentage_products_out_of_stock');
      table.showColumn('products_out_of_stock');
      table.showColumn('products');
      table.showColumn('list_products');
      table.showColumn('button_products');
      table.showColumn('products_sold_out');
    }


    Dom.removeClass(Dom.getElementsByClassName('table_option', 'button', this.parentNode), 'selected')
    Dom.addClass(this, "selected");


    YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=store-pages-view&value=' + escape(tipo), {});

  }



  YAHOO.util.Event.addListener(window, "load", function() {

    session_data=YAHOO.lang.JSON.parse(base64_decode(Dom.get('session_data').value));
    labels=session_data.label;
    state=session_data.state;

    tables = new function() {





   var tableid=0; // Change if you have more the 1 table
   var tableDivEL="table"+tableid;
   var OrdersColumnDefs = [
   {key:"code", label:labels.Code, width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
   {key:"name", label:labels.Name,hidden:(state.store.departments.view=='timeline'?true:false),width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
   {key:"families", label:labels.Families, width:100,sortable:true,className:"aright",hidden:(state.store.departments.view=='general'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
   {key:"active", label:labels.Products,  width:100,sortable:true,className:"aright",hidden:(state.store.departments.view=='general'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
   {key:"discontinued", label:labels.Discontinued,  width:100,sortable:true,className:"aright",hidden:(state.store.departments.view=='general'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
   {key:"sales", label:labels.Sales, width:80,sortable:true,className:"aright",hidden:(state.store.departments.view=='sales'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
   {key:"delta_sales", label:'1y&Delta; '+labels.Sales, width:80,sortable:true,className:"aright",hidden:(state.store.departments.view=='sales'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
   {key:"profit", label:labels.Profit, width:80,sortable:true,className:"aright",hidden:(state.store.departments.view=='sales'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
   {key:"surplus", label:labels.Surplus, width:70,sortable:true,className:"aright",hidden:(state.store.departments.view=='stock'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
   {key:"optimal", label:labels.OK, width:70,sortable:true,className:"aright",hidden:(state.store.departments.view=='stock'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
   {key:"low", label:labels.Low, width:70,sortable:true,className:"aright",hidden:(state.store.departments.view=='stock'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
   {key:"critical", label:labels.Critical, width:70,sortable:true,className:"aright",hidden:(state.store.departments.view=='stock'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
   {key:"outofstock", label:labels.Gone, width:70,sortable:true,className:"aright",hidden:(state.store.departments.view=='stock'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
   {key:"stock_error", label:labels.Unknown, width:70,sortable:true,className:"aright",hidden:(state.store.departments.view=='stock'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
   {key:"sales_type", label:labels.Sales_Type, width:70,sortable:true,className:"aright",hidden:(state.store.departments.view=='web'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
   {key:"last_update", label:labels.Last_Update, width:180,sortable:true,className:"aright",hidden:(state.store.departments.view=='timeline'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
   {key:"from", label:labels.Since, width:190,sortable:true,className:"aright",hidden:(state.store.departments.view=='timeline'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
   {key:"to", label:labels.Until, width:180,sortable:true,className:"aright",hidden:(state.store.departments.view=='timeline'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
   ];


   request="ar_assets.php?tipo=departments&sf=0&parent=store&parent_key="+Dom.get('store_key').value;
   this.dataSource0 = new YAHOO.util.DataSource(request);
   this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
   this.dataSource0.connXhrMode = "queueRequests";
   this.dataSource0.responseSchema = {
    resultsList: "resultset.data",
    metaFields: {
     rowsPerPage:"resultset.records_perpage",
     RecordOffset : "resultset.records_offset",
     rtext:"resultset.rtext",
     rtext_rpp:"resultset.rtext_rpp",
     sort_key:"resultset.sort_key",
     sort_dir:"resultset.sort_dir",
     tableid:"resultset.tableid",
     filter_msg:"resultset.filter_msg",
     totalRecords: "resultset.total_records"


   },

   fields: [
   'id',
   "name","code","aws_p","awp_p","sales_type","delta_sales","from","last_update","to",
   'families',
   'active',"sales","stock_error","stock_value","outofstock","profit","surplus","optimal","low","critical","todo","discontinued"
   ]};


   this.table0 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
     this.dataSource0, {
      renderLoopSize: 20,generateRequest : myRequestBuilderwithTotals,
      paginator : new YAHOO.widget.Paginator({
        alwaysVisible:true,
        rowsPerPage:(state.store.departments.nr+1),
        totalRecords:YAHOO.widget.Paginator.VALUE_UNLIMITED,
        containers : 'paginator0', 
        pageReportTemplate : '('+labels.Page+' {currentPage} '+labels.of+' {totalPages})',
        previousPageLinkLabel : "<",
        nextPageLinkLabel : ">",
        firstPageLinkLabel :"<<",
        lastPageLinkLabel :">>",
        rowsPerPageOptions : [10,25,50,100,250,500],

        template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
      })

      ,sortedBy : {
        key: state.store.departments.order,
        dir: state.store.departments.order_dir
      }
      ,dynamicData : true

    }
    );
   this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
   this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
   this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;
   this.table0.doBeforeLoadData=mydoBeforeLoadData;
   this.table0.table_id=tableid;
   this.table0.request=request;
   this.table0.subscribe("renderEvent", myrenderEvent);


   this.table0.view=state.store.departments.view;

   this.table0.filter={key:state.store.departments.f_field,value:state.store.departments.f_value};




	    var tableid=1; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var OrdersColumnDefs = [ 
      {key:"code", label:labels.Code, width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
      {key:"store", label:labels.Store,hidden:true, width:70,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
      {key:"department", label:labels.Department,hidden:(state.store.families.view=='timeline'?true:false),width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
      {key:"name", label:labels.Name,hidden:(state.store.families.view=='stock'||state.store.families.view=='timeline' ?true:false),width:280,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
      {key:"active", label:labels.Products,  width:100,sortable:true,className:"aright",hidden:(state.store.families.view=='general'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
      {key:"sales", label:labels.Sales, width:90,sortable:true,className:"aright",hidden:(state.store.families.view=='stock'||state.store.families.view=='timeline' ?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
      {key:"delta_sales", label:'1y&Delta; '+labels.Sales, width:80,sortable:true,className:"aright",hidden:(state.store.families.view=='sales'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
      {key:"profit", label:labels.Profit, width:90,sortable:true,className:"aright",hidden:(state.store.families.view=='stock'||state.store.families.view=='timeline' ?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
      {key:"surplus", label:labels.Surplus, width:60,sortable:true,className:"aright",hidden:(state.store.families.view=='stock'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
      {key:"optimal", label:labels.OK, width:60,sortable:true,className:"aright",hidden:(state.store.families.view=='stock'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
      {key:"low", label:labels.Low, width:60,sortable:true,className:"aright",hidden:(state.store.families.view=='stock'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
      {key:"critical", label:labels.Critical, width:60,sortable:true,className:"aright",hidden:(state.store.families.view=='stock'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
      {key:"outofstock", label:labels.Gone, width:60,sortable:true,className:"aright",hidden:(state.store.families.view=='stock'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
      {key:"stock_error", label:labels.Unknown, width:60,sortable:true,className:"aright",hidden:(state.store.families.view=='stock'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
      {key:"last_update", label:labels.Last_Update, width:180,sortable:true,className:"aright",hidden:(state.store.families.view=='timeline'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
      {key:"from", label:labels.Since, width:190,sortable:true,className:"aright",hidden:(state.store.families.view=='timeline'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
      {key:"to", label:labels.Until, width:180,sortable:true,className:"aright",hidden:(state.store.families.view=='timeline'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}



      ];

      request="ar_assets.php?tipo=families&sf=0&parent=store&parent_key="+Dom.get('store_key').value+"&tableid="+tableid;
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
        'id',
        "code",
        "name","delta_sales","from","last_update","to",
        'active',"stock_error","stock_value","outofstock","sales","profit","surplus","optimal","low","critical","store","department"
        ]};

        this.table1 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
         this.dataSource1, {
							 //draggableColumns:true,
              renderLoopSize: 50,generateRequest : myRequestBuilderwithTotals
              ,paginator : new YAHOO.widget.Paginator({

               rowsPerPage:(state.store.families.nr+1),
               containers : 'paginator1', 
               pageReportTemplate : '('+labels.Page+' {currentPage} '+labels.of+' {totalPages})',
               previousPageLinkLabel : "<",
               nextPageLinkLabel : ">",
               firstPageLinkLabel :"<<",
               lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
               ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info1'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
             })

              ,sortedBy : {
               key: state.store.families.order,
               dir: state.store.families.order_dir
             }
             ,dynamicData : true

           }
           );
this.table1.handleDataReturnPayload =myhandleDataReturnPayload;
this.table1.doBeforeSortColumn = mydoBeforeSortColumn;
this.table1.doBeforePaginatorChange = mydoBeforePaginatorChange;
this.table1.request=request;
this.table1.table_id=tableid;

this.table1.subscribe("renderEvent", families_myrenderEvent);
this.table1.getDataSource().sendRequest(null, {
  success:function(request, response, payload) {

    if(response.results.length == 1) {
      get_families_elements_numbers()

    } else {
            		//this.onDataReturnInitializeTable(request, response, payload);
              }
            },
            scope:this.table1,
            argument:this.table1.getState()
          });


this.table1.view=state.store.families.view;
this.table1.filter={key:state.store.families.f_field,value:state.store.families.f_value};



tableid=2;
tableDivEL="table"+tableid;
ColumnDefs = [
{key:"code", label:labels.Code, width:100,sortable:true, className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
{key:"name", label:labels.Name,width:400,hidden:(state.store.products.view=='general'?false:true),sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
{key:"smallname", label:labels.Name,width:340, sortable:true,className:"aleft",className:"aleft",hidden:(state.store.families.view!='general'||state.store.families.view=='timeline'||state.store.families.view=='properties' ?true:false),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
{key:"price", label:labels.Price,width:100,hidden:(state.store.products.view=='general'?false:true),sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
{key:"web", label:labels.WebSales_State,width:150,hidden:(state.store.families.view=='general'||state.store.families.view=='stock' ?false:true),sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
{key:"sold", label:labels.Sold,width:90,hidden:(state.store.products.view=='sales'?false:true), sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
{key:"sales", label:labels.Sales,width:90,hidden:(state.store.products.view=='sales'?false:true), sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
{key:"delta_sales", label:'1y&Delta; '+labels.Sales, width:80,sortable:true,className:"aright",hidden:(state.store.products.view=='sales'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
{key:"profit", label:labels.Profit,width:90,hidden:(state.store.products.view=='sales'?false:true), sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
{key:"stock", label:labels.Available, width:65,sortable:true,className:"aright",hidden:(state.store.families.view=='general'||state.store.families.view=='stock'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
{key:"stock_state", label:labels.State, width:70,sortable:true,className:"aright",hidden:(state.store.products.view=='stock'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
{key:"stock_forecast", label:labels.Forecast, width:70,sortable:true,className:"aright",hidden:(state.store.products.view=='stock'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
{key:"parts", label:labels.Parts,width:130,hidden:(state.store.products.view=='parts'?false:true),sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
{key:"supplied", label:labels.Supplied_by,width:130,hidden:(state.store.products.view=='parts'?false:true),sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
{key:"gmroi", label:labels.GMROI, width:100,sortable:true,className:"aright",hidden:(state.store.products.view=='parts'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
{key:"expcode", label:labels.Tariff_Code,width:160,hidden:(state.store.products.view=='cats'?false:true),sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
{key:"last_update", label:labels.Last_Update, width:180,sortable:true,className:"aright",hidden:(state.store.products.view=='timeline'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
{key:"from", label:labels.Since, width:190,sortable:true,className:"aright",hidden:(state.store.products.view=='timeline'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
{key:"to", label:labels.Until, width:180,sortable:true,className:"aright",hidden:(state.store.products.view=='timeline'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
{key:"package_type", label:labels.Pkg_Type, width:70,sortable:true,className:"aright",hidden:(state.store.products.view=='properties'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
{key:"package_weight", label:labels.Pkg_Weight, width:100,sortable:true,className:"aright",hidden:(state.store.products.view=='properties'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
{key:"package_dimension", label:labels.Pkg_Dim, width:120,sortable:true,className:"aright",hidden:(state.store.products.view=='properties'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
{key:"package_volume", label:labels.Pkg_Vol, width:110,sortable:true,className:"aright",hidden:(state.store.products.view=='properties'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
{key:"unit_weight", label:labels.Unit_Weight, width:100,sortable:true,className:"aright",hidden:(state.store.products.view=='properties'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
{key:"unit_dimension", label:labels.Unit_Dim, width:120,sortable:true,className:"aright",hidden:(state.store.products.view=='properties'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}


];

request="ar_assets.php?tipo=products&sf=0&parent=store&tableid="+tableid+"&parent_key="+Dom.get('store_key').value;
//alert(request)
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
  'id',"package_type","package_weight","package_dimension","package_volume","unit_weight","unit_dimension","code","price","name","stock","stock_value","from","last_update","to",'sales','profit','margin','sold',"parts","supplied","gmroi","family","dept","expcode","smallname","state","web","delta_sales"
  ]};

  this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
   this.dataSource2, {
     renderLoopSize: 50,generateRequest : myRequestBuilderwithTotals
     ,paginator : new YAHOO.widget.Paginator({
       rowsPerPage:(state.store.products.nr+1),
       containers : 'paginator2', 
       pageReportTemplate : '('+labels.Page+' {currentPage} '+labels.of+' {totalPages})',
       previousPageLinkLabel : "<",
       nextPageLinkLabel : ">",
       firstPageLinkLabel :"<<",
       lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
       ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
     })

     ,sortedBy : {
       key: state.store.products.order,
       dir: state.store.products.order_dir
     }
     ,dynamicData : true

   }
   );
  this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
  this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
  this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
  this.table2.table_id=tableid;
  this.table2.request=request;
  this.table2.subscribe("renderEvent", products_myrenderEvent);
  this.table2.getDataSource().sendRequest(null, {
    success:function(request, response, payload) {

      if(response.results.length == 1) {
        get_products_elements_numbers();

      } else {
            		//this.onDataReturnInitializeTable(request, response, payload);
              }
            },
            scope:this.table2,
            argument:this.table2.getState()
          });



  this.table2.view=state.store.products.view;
  this.table2.filter={key:state.store.products.f_field,value:state.store.products.f_value};


  var tableid=3; // Change if you have more the 1 table
  var tableDivEL="table"+tableid;
  var OrdersColumnDefs = [ 
  {key:"code", label:labels.Code, width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
  {key:"name", label:labels.Name, width:120,hidden:(state.store.sites.view=='general'?false:true),sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
  {key:"url", label:labels.URL, width:280,hidden:(state.store.sites.view=='general'?false:true),sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
  {key:"users", label:labels.Users, width:100,hidden:(state.store.sites.view=='general'?false:true),sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
  {key:"pages", label:labels.Pages, width:100,hidden:(state.store.sites.view=='general'?false:true),sortable:true,className:"aright",hidden:(state.store.sites.view=='general'?false:true),sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
  {key:"products", label:labels.Products, width:100,hidden:(state.store.sites.view=='producys'||state.store.sites.view=='general' ?false:true),sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
  {key:"out_of_stock", label:labels.OoS, width:100,hidden:(state.store.sites.view=='products'?false:true),sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
  {key:"out_of_stock_percentage", label:"%", width:100,hidden:(state.store.sites.view=='products'?false:true),sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
  {key:"pages_products", label:labels.Pages_w_Prods, width:100,hidden:(state.store.sites.view=='products'?false:true),sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
  {key:"pages_out_of_stock", label:labels.Pages_w_OoS, width:100,hidden:(state.store.sites.view=='products'?false:true),sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
  {key:"pages_out_of_stock_percentage", label:"%", width:100,hidden:(state.store.sites.view=='products'?false:true),sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}




  ];
  request="ar_sites.php?tipo=sites&sf=0&parent=store&tableid="+tableid+"&parent_key="+Dom.get('store_id').value
  this.dataSource3 = new YAHOO.util.DataSource(request);
  this.dataSource3.responseType = YAHOO.util.DataSource.TYPE_JSON;
  this.dataSource3.connXhrMode = "queueRequests";
  this.dataSource3.responseSchema = {
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
    'id','title','code','url','type','site','name','users','pages','pages_products','pages_out_of_stock','pages_out_of_stock_percentage','products','out_of_stock','out_of_stock_percentage'
    ]};

    this.table3 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
     this.dataSource3, {
							 //draggableColumns:true,
              renderLoopSize: 50,generateRequest : myRequestBuilder,
              paginator : new YAHOO.widget.Paginator({

               rowsPerPage:state.store.sites.nr,
               containers : 'paginator3', 
               pageReportTemplate : '('+labels.Page+' {currentPage} '+labels.of+' {totalPages})',
               previousPageLinkLabel : "<",
               nextPageLinkLabel : ">",
               firstPageLinkLabel :"<<",
               lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
               ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info3'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
             })

              ,sortedBy : {
               key: state.store.sites.order,
               dir: state.store.sites.order_dir
             }
             ,dynamicData : true

           }
           );
    this.table3.handleDataReturnPayload =myhandleDataReturnPayload;
    this.table3.doBeforeSortColumn = mydoBeforeSortColumn;
    this.table3.doBeforePaginatorChange = mydoBeforePaginatorChange;
    this.table3.table_id=tableid;
    this.table3.request=request;
    this.table3.subscribe("renderEvent", myrenderEvent);


    this.table3.filter={key:state.store.sites.f_field,value:state.store.sites.f_value};






  var tableid=4; // Change if you have more the 1 table
  var tableDivEL="table"+tableid;
  var OrdersColumnDefs = [ 

  {key:"site", label:labels.Site, width:50,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
  {key:"code", label:labels.Code, width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
  {key:"type", label:labels.Type,hidden:(state.store.pages.view=='general'?false:true),width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
  {key:"title", label:labels.Header_Title,hidden:(state.store.pages.view=='general'?false:true),width:270,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
  {key:"link_title", label:labels.Link_Label,hidden:(state.store.pages.view!='products'?false:true), width:270,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}},
  {key:"users", label:labels.Users,hidden:(state.store.pages.view=='requests'?false:true),width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
  {key:"visitors", label:labels.Visitors,hidden:(state.store.pages.view='requests'?false:true),width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
  {key:"sessions", label:labels.Sessions,hidden:(state.store.pages.view='requests'?false:true),width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
  {key:"requests", label:labels.Requests,hidden:(state.store.pages.view='requests'?false:true),width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
  {key:"products", label:labels.Products,hidden:(state.store.pages.view='products'?false:true),width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
  {key:"list_products", label:labels.In_list,hidden:(state.store.pages.view='products'?false:true),width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
  {key:"button_products", label:labels.Buttons,hidden:(state.store.pages.view='products'?false:true),width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
  {key:"products_out_of_stock", label:labels.No_available,hidden:(state.store.pages.view='products'?false:true),width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
  {key:"percentage_products_out_of_stock", label:"%",hidden:(state.store.pages.view='products'?false:true),width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
  {key:"products_sold_out", label:labels.Sold_Out,hidden:(state.store.pages.view='products'?false:true),width:70,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},


  ];

  request="ar_sites.php?tipo=pages&sf=0&parent=store&sf=0&tableid="+tableid+"&parent_key="+Dom.get('store_id').value
  this.dataSource4 = new YAHOO.util.DataSource(request);
	   // alert(request)
	   // alert("ar_sites.php?tipo=pages&parent=store&tableid=4&parent_key="+Dom.get('store_id').value)
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
      'site','id','title','code','url','type','link_title','visitors','sessions','requests','users','products','products_out_of_stock','percentage_products_out_of_stock',
      'list_products','button_products','products_sold_out','flag'
      ]};


      this.table4 = new YAHOO.widget.DataTable(tableDivEL, OrdersColumnDefs,
       this.dataSource4, {
							 //draggableColumns:true,
              renderLoopSize: 50,generateRequest : myRequestBuilder,
              paginator : new YAHOO.widget.Paginator({

               rowsPerPage:state.store.pages.nr,
               containers : 'paginator4', 
               pageReportTemplate : '('+labels.Page+' {currentPage} '+labels.of+' {totalPages})',
               previousPageLinkLabel : "<",
               nextPageLinkLabel : ">",
               firstPageLinkLabel :"<<",
               lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:true
               ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info4'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
             })

              ,sortedBy : {
               key: state.store.pages.order,
               dir: state.store.pages.order_dir
             }
             ,dynamicData : true

           }
           );
      this.table4.handleDataReturnPayload =myhandleDataReturnPayload;
      this.table4.doBeforeSortColumn = mydoBeforeSortColumn;
      this.table4.doBeforePaginatorChange = mydoBeforePaginatorChange;
      this.table4.doBeforeLoadData=mydoBeforeLoadData;
      this.table4.table_id=tableid;
      this.table4.request=request;
      this.table4.subscribe("renderEvent", myrenderEvent);


      this.table4.filter={key:state.store.pages.f_field,value:state.store.pages.f_value};




      var tableid=5; 
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
    ,{key:"note", formatter:this.prepare_note,label:labels.Notes,className:"aleft",width:520}
    ,{key:"delete", label:"",width:12,sortable:false,action:'delete',object:'store_history'}
    ,{key:"edit", label:"",width:12,sortable:false,action:'edit',object:'store_history'}

    ];
    request="ar_history.php?tipo=store_history&parent=store&parent_key="+Dom.get('store_key').value+"&sf=0&tableid="+tableid
		//alert(request)
    this.dataSource5  = new YAHOO.util.DataSource(request);
    this.dataSource5.responseType = YAHOO.util.DataSource.TYPE_JSON;
    this.dataSource5.connXhrMode = "queueRequests";
    this.dataSource5.responseSchema = {
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
      this.table5 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
       this.dataSource5
       , {
         formatRow: myRowFormatter,
         renderLoopSize: 5,generateRequest : myRequestBuilder
         ,paginator : new YAHOO.widget.Paginator({
          rowsPerPage:state.store.history.nr,
          containers : 'paginator5', 
          pageReportTemplate : '('+labels.Page+' {currentPage} '+labels.of+' {totalPages})',alwaysVisible:false,
          previousPageLinkLabel : "<",
          nextPageLinkLabel : ">",
          firstPageLinkLabel :"<<",
          lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
          ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info5'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



        })

         ,sortedBy : {
          key: state.store.history.order,
          dir: state.store.history.order_dir
        },
        dynamicData : true

      }

      );
      this.table5.handleDataReturnPayload =myhandleDataReturnPayload;
      this.table5.doBeforeSortColumn = mydoBeforeSortColumn;
      this.table5.doBeforePaginatorChange = mydoBeforePaginatorChange;
      this.table5.filter={key:state.store.history.f_field,value:state.store.history.f_value};

      this.table5.subscribe("cellMouseoverEvent", highlightEditableCell);
      this.table5.subscribe("cellMouseoutEvent", unhighlightEditableCell);
      this.table5.subscribe("cellClickEvent", onCellClick);            
      this.table5.table_id=tableid;
      this.table5.subscribe("renderEvent", myrenderEvent);





      var tableid=6;
      var tableDivEL="table"+tableid;

      var ColumnDefs = [
      {key:"date", label:labels.Date, width:200,sortable:false,className:"aright"}
      ,{key:"invoices", label:labels.Invoices, width:100,sortable:false,className:"aright"}
      ,{key:"customers", label:labels.Customers, width:100,sortable:false,className:"aright"}
      ,{key:"sales", label:labels.Sales, width:100,sortable:false,className:"aright"}
      ];


      request="ar_reports.php?tipo=assets_sales_history&sf=0&scope=assets&parent=store&parent_key="+Dom.get('store_key').value+"&tableid="+tableid+'&from='+Dom.get('from').value+'&to='+Dom.get('to').value;
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
      "date","invoices","customers","sales"

      ]};

      this.table6 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
       this.dataSource6, {
							 //draggableColumns:true,

              renderLoopSize: 50,generateRequest : myRequestBuilder
              ,paginator : new YAHOO.widget.Paginator({
                rowsPerPage:state.store.sales_history.nr,
                containers : 'paginator6', 
                pageReportTemplate : '('+labels.Page+' {currentPage} '+labels.of+' {totalPages})',
                previousPageLinkLabel : "<",
                nextPageLinkLabel : ">",
                firstPageLinkLabel :"<<",
                lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
                ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info6'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
              })

              ,sortedBy : {
               key: state.store.sales_history.order,
               dir: state.store.sales_history.order_dir
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

this.table6.filter={key:state.store.sales_history.f_field,value:state.store.sales_history.f_value};

var tableid=7;
var tableDivEL="table"+tableid;


var ColumnDefs = [ 
{key:"code", label:labels.Code, width:60,sortable:true, className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
,{key:"name", label:labels.Name,width:440, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
				   		//		   ,{key:"state", label:labels.State,width:70, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
             ,{key:"sold", label:labels.Sold,width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
             ,{key:"sales", label:labels.Sales,width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
             ,{key:"profit", label:labels.Profit,width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

             ];
             request="ar_assets.php?tipo=SUSPENDED_department_sales_report&tableid="+tableid+"&parent=store&sf=0&parent_key="+Dom.get('store_key').value+'&from='+Dom.get('from').value+'&to='+Dom.get('to').value;
	//alert(request)
  this.dataSource7 = new YAHOO.util.DataSource(request);
  this.dataSource7.responseType = YAHOO.util.DataSource.TYPE_JSON;
  this.dataSource7.connXhrMode = "queueRequests";

  this.dataSource7.responseSchema = {
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



    this.table7 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
     this.dataSource7, {
							 //draggableColumns:true,
							// formatRow: myRowFormatter,
              renderLoopSize: 50,generateRequest : myRequestBuilder
              ,paginator : new YAHOO.widget.Paginator({
                rowsPerPage:state.store.family_sales.nr,
                containers : 'paginator7', 
                pageReportTemplate : '('+labels.Page+' {currentPage} '+labels.of+' {totalPages})',
                previousPageLinkLabel : "<",
                nextPageLinkLabel : ">",
                firstPageLinkLabel :"<<",
                lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
                ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info7'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
              })

              ,sortedBy : {
               key: state.store.family_sales.order,
               dir: state.store.family_sales.order_dir
             }
             ,dynamicData : true  

           }
           );
this.table7.handleDataReturnPayload =myhandleDataReturnPayload;
this.table7.doBeforeSortColumn = mydoBeforeSortColumn;
this.table7.doBeforePaginator = mydoBeforePaginatorChange;
this.table7.request=request;
this.table7.table_id=tableid;
this.table7.subscribe("renderEvent", myrenderEvent);

this.table7.filter={key:state.store.family_sales.f_field,value:state.store.family_sales.f_value};




var tableid=8;
var tableDivEL="table"+tableid;


var ColumnDefs = [ 
{key:"code", label:labels.Code, width:60,sortable:true, className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
,{key:"name", label:labels.Name,width:370, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
,{key:"state", label:labels.State,width:70, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
,{key:"sold", label:labels.Sold,width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
,{key:"sales", label:labels.Sales,width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
,{key:"profit", label:labels.Profit,width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

];
request="ar_assets.php?tipo=SUSPENDED_family_sales_report&tableid="+tableid+"&parent=store&sf=0"+'&parent_key='+Dom.get('store_key').value+'&from='+Dom.get('from').value+'&to='+Dom.get('to').value;
	//alert(request)
  this.dataSource8 = new YAHOO.util.DataSource(request);
  this.dataSource8.responseType = YAHOO.util.DataSource.TYPE_JSON;
  this.dataSource8.connXhrMode = "queueRequests";

  this.dataSource8.responseSchema = {
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



    this.table8 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
     this.dataSource8, {
							 //draggableColumns:true,
							// formatRow: myRowFormatter,
              renderLoopSize: 50,generateRequest : myRequestBuilder
              ,paginator : new YAHOO.widget.Paginator({
                rowsPerPage:state.store.family_sales.nr,
                containers : 'paginator8', 
                pageReportTemplate : '('+labels.Page+' {currentPage} '+labels.of+' {totalPages})',
                previousPageLinkLabel : "<",
                nextPageLinkLabel : ">",
                firstPageLinkLabel :"<<",
                lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
                ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info8'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
              })

              ,sortedBy : {
                key: state.store.family_sales.order,
                dir: state.store.family_sales.order_dir
              }
              ,dynamicData : true  

            }
            );
this.table8.handleDataReturnPayload =myhandleDataReturnPayload;
this.table8.doBeforeSortColumn = mydoBeforeSortColumn;
this.table8.doBeforePaginator = mydoBeforePaginatorChange;
this.table8.request=request;
this.table8.table_id=tableid;
this.table8.subscribe("renderEvent", myrenderEvent);

this.table8.filter={key:state.store.family_sales.f_field,value:state.store.family_sales.f_value};




var tableid=9;
var tableDivEL="table"+tableid;

var ColumnDefs = [ 
{key:"code", label:labels.Code, width:60,sortable:true, className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
,{key:"name", label:labels.Name,width:370, sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
,{key:"state", label:labels.State,width:70, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
,{key:"sold", label:labels.Sold,width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
,{key:"sales", label:labels.Sales,width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
,{key:"profit", label:labels.Profit,width:90, sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}

];
request="ar_assets.php?tipo=SUSPENDED_product_sales_report&tableid="+tableid+"&parent=store&sf=0"+'&parent_key='+Dom.get('store_key').value+'&from='+Dom.get('from').value+'&to='+Dom.get('to').value;
	//alert(request)
  this.dataSource9 = new YAHOO.util.DataSource(request);
  this.dataSource9.responseType = YAHOO.util.DataSource.TYPE_JSON;
  this.dataSource9.connXhrMode = "queueRequests";

  this.dataSource9.responseSchema = {
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
    ,'sales','profit','margin','sold',"parts","supplied","gmroi","family","dept","expcode","state","web","smallname","delta_sales"
    ]};



    this.table9 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
     this.dataSource9, {
							 //draggableColumns:true,
							 formatRow: myRowFormatter,
              renderLoopSize: 50,generateRequest : myRequestBuilder
              ,paginator : new YAHOO.widget.Paginator({
                rowsPerPage:state.store.product_sales.nr,
                containers : 'paginator9', 
                pageReportTemplate : '('+labels.Page+' {currentPage} '+labels.of+' {totalPages})',
                previousPageLinkLabel : "<",
                nextPageLinkLabel : ">",
                firstPageLinkLabel :"<<",
                lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
                ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info9'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"
              })

              ,sortedBy : {
                key: state.store.product_sales.order,
                dir: state.store.product_sales.order_dir
              }
              ,dynamicData : true  

            }
            );
this.table9.handleDataReturnPayload =myhandleDataReturnPayload;
this.table9.doBeforeSortColumn = mydoBeforeSortColumn;
this.table9.doBeforePaginator = mydoBeforePaginatorChange;
this.table9.request=request;
this.table9.table_id=tableid;
this.table9.subscribe("renderEvent", myrenderEvent);

this.table9.filter={key:state.store.product_sales.f_field,value:state.store.product_sales.f_value};






	    var tableid=10; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var productsColumnDefs = [
	    
      {key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 

      ,{key:"code", label:labels.Code, width:110,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
      ,{key:"description", label:labels.Description, width:350,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
      ,{key:"duration", label:labels.Duration,  width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

      ,{key:"orders", label:labels.Orders,  width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
      ,{key:"customers", label:labels.Customers,  width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}


      ];
	    //?tipo=products&tid=0"
	    
	    request="ar_deals.php?tipo=deals&parent=store&sf=0&parent_key="+Dom.get('store_key').value+'&tableid=10&referrer=store'
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
                 rowsPerPage:state.store.offers.nr,
                 containers : 'paginator10', 
                 pageReportTemplate : '('+labels.Page+' {currentPage} '+labels.of+' {totalPages})',
                 previousPageLinkLabel : "<",
                 nextPageLinkLabel : ">",
                 firstPageLinkLabel :"<<",
                 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
                 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info10'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



               })

               ,sortedBy : {
                key: state.store.offers.order,
                dir: state.store.offers.order_dir
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
    
   this.onDataReturnInitializeTable(request, response, payload);
   
 },
 scope: this.table10,
 argument: this.table10.getState()
});

this.table10.filter={key:state.store.offers.f_field,value:state.store.offers.f_value};



	    var tableid=11; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;
	    var productsColumnDefs = [
	    
      {key:"key", label:"", width:20,sortable:false,isPrimaryKey:true,hidden:true} 
      ,{key:"code", label:labels.Code, width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}

      ,{key:"name", label:labels.Name, width:200,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
      ,{key:"duration", label:labels.Duration,  width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
      ,{key:"orders", label:labels.Orders,  width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}
      ,{key:"customers", label:labels.Customers,  width:90,sortable:true,className:"aright",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}}


      ];
	    //?tipo=products&tid=0"
	    request='ar_deals.php?tipo=campaigns&parent=store&sf=0&parent_key='+Dom.get('store_id').value+'&tableid='+tableid+'&referrer=store'
	 //  alert(request)
   this.dataSource11 = new YAHOO.util.DataSource(request);
   this.dataSource11.responseType = YAHOO.util.DataSource.TYPE_JSON;
   this.dataSource11.connXhrMode = "queueRequests";
   this.dataSource11.responseSchema = {
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

   fields: ["name","key","code","duration","used","orders","customers"]};


   this.table11 = new YAHOO.widget.DataTable(tableDivEL, productsColumnDefs,
     this.dataSource11
     , {
      renderLoopSize: 50,generateRequest : myRequestBuilder
							 //,initialLoad:false
               ,paginator : new YAHOO.widget.Paginator({
                 rowsPerPage:state.store.campaigns.nr,
                 containers : 'paginator11', 
                 pageReportTemplate : '('+labels.Page+' {currentPage} '+labels.of+' {totalPages})',
                 previousPageLinkLabel : "<",
                 nextPageLinkLabel : ">",
                 firstPageLinkLabel :"<<",
                 lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
                 ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info11'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



               })

               ,sortedBy : {
                 key: state.store.campaigns.order,
                 dir: state.store.campaigns.order_dir
               },
               dynamicData : true

             }

             );

this.table11.handleDataReturnPayload =myhandleDataReturnPayload;
this.table11.doBeforeSortColumn = mydoBeforeSortColumn;
this.table11.doBeforePaginatorChange = mydoBeforePaginatorChange;
this.table11.request=request;
this.table11.table_id=tableid;
this.table11.subscribe("renderEvent", campaigns_myrenderEvent);
this.table11.getDataSource().sendRequest(null, {
  success: function(request, response, payload) {
    if (response.results.length == 1) {
      campaigns_myrenderEvent()

    } else {
     this.onDataReturnInitializeTable(request, response, payload);
   }
 },
 scope: this.table11,
 argument: this.table11.getState()
});	  
this.table11.filter={key:state.store.campaigns.f_field,value:state.store.campaigns.f_value};




};
get_thumbnails(4)
get_thumbnails(2)
get_thumbnails(1)
get_thumbnails(0)
});


function change_sales_sub_block(o) {
  Dom.removeClass(['plot_store_sales', 'store_department_sales', 'store_family_sales','store_product_sales','store_sales_timeseries','store_sales_calendar'], 'selected')
  Dom.addClass(o, 'selected')
  Dom.setStyle(['sub_block_plot_store_sales', 'sub_block_store_department_sales', 'sub_block_store_family_sales', 'sub_block_store_product_sales', 'sub_block_store_sales_timeseries', 'sub_block_store_sales_calendar'], 'display', 'none')
  Dom.setStyle('sub_block_' + o.id, 'display', '')
//alert('ar_sessions.php?tipo=update&keys=store-sales_sub_block_tipo&value=' + o.id)
YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=store-sales_sub_block_tipo&value=' + o.id, {});
}

function change_family_elements() {

  ids = ['elements_family_discontinued', 'elements_family_discontinuing', 'elements_family_normal', 'elements_family_inprocess', 'elements_family_nosale'];


  if (Dom.hasClass(this, 'selected')) {

    var number_selected_elements = 0;
    for (i in ids) {
      if (Dom.hasClass(ids[i], 'selected')) {
        number_selected_elements++;
      }
    }

    if (number_selected_elements > 1) {
      Dom.removeClass(this, 'selected')

    }

  } else {
    Dom.addClass(this, 'selected')

  }

  table_id = 1;
  var table = tables['table' + table_id];
  var datasource = tables['dataSource' + table_id];
  var request = '';
  for (i in ids) {
    if (Dom.hasClass(ids[i], 'selected')) {
      request = request + '&' + ids[i] + '=1'
    } else {
      request = request + '&' + ids[i] + '=0'

    }
  }

    // alert(request)
    datasource.sendRequest(request, table.onDataReturnInitializeTable, table);


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
     var table=tables['table2'];
     var datasource=tables.dataSource2;
     dialog_change_products_display.hide();

   }else if(parent=='families'){
    var table=tables['table1'];
    var datasource=tables.dataSource1;
    dialog_change_families_display.hide();

  }else if(parent=='departments'){
    var table=tables['table0'];
    var datasource=tables.dataSource0;
    dialog_change_departments_display.hide();

  }else{
    return;
  }

  datasource.sendRequest(request,table.onDataReturnInitializeTable, table);   

}

function change_table_type(parent, tipo, label, table_id) {

  Dom.get('change_' + parent + '_table_type').innerHTML = '&#x21b6 ' + label;

  if (tipo == 'list') {
    if (Dom.get('change_' + parent + '_display_mode') != undefined && Dom.get(parent + '_view').value == 'sales') {
      Dom.setStyle('change_' + parent + '_display_mode', 'display', '')
    }
    Dom.setStyle('thumbnails' + table_id, 'display', 'none')
    Dom.setStyle(['table' + table_id, 'list_options' + table_id, 'table_view_menu' + table_id], 'display', '')
  } else {

    if (Dom.get('change_' + parent + '_display_mode') != undefined) Dom.setStyle('change_' + parent + '_display_mode', 'display', 'none')


      Dom.setStyle('thumbnails' + table_id, 'display', '')
    Dom.setStyle(['table' + table_id, 'list_options' + table_id, 'table_view_menu' + table_id], 'display', 'none')

  }

  YAHOO.util.Connect.asyncRequest('POST', 'ar_sessions.php?tipo=update&keys=store-' + parent + '-table_type&value=' + escape(tipo), {});

  if (parent == 'products') {
    dialog_change_products_table_type.hide();
  } else if (parent == 'families') {
    dialog_change_families_table_type.hide();
  } else if (parent == 'departments') {
    dialog_change_departments_table_type.hide();
  }

}




function change_timeseries_type(e, table_id) {

  ids = ['store_sales_history_type_year', 'store_sales_history_type_month', 'store_sales_history_type_week', 'store_sales_history_type_day'];
  Dom.removeClass(ids, 'selected')
  Dom.addClass(this, 'selected')

  type = this.getAttribute('tipo')


  var table = tables['table' + table_id];
  var datasource = tables['dataSource' + table_id];

  var request = '&sf=0&type=' + type;
  datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
};

function change_history_elements() {

  ids = ['elements_store_history_changes', 'elements_store_history_notes', 'elements_store_history_attachments'];


  if (Dom.hasClass(this, 'selected')) {

    var number_selected_elements = 0;
    for (i in ids) {
      if (Dom.hasClass(ids[i], 'selected')) {
        number_selected_elements++;
      }
    }

    if (number_selected_elements > 1) {
      Dom.removeClass(this, 'selected')

    }

  } else {
    Dom.addClass(this, 'selected')

  }

  table_id = 5;
  var table = tables['table' + table_id];
  var datasource = tables['dataSource' + table_id];
  var request = '';
  for (i in ids) {
    if (Dom.hasClass(ids[i], 'selected')) {
      request = request + '&' + Dom.get(ids[i]).getAttribute('table_type') + '=1'
    } else {
      request = request + '&' + Dom.get(ids[i]).getAttribute('table_type')  + '=0'

    }
  }

  //  alert(request)
  datasource.sendRequest(request, table.onDataReturnInitializeTable, table);


}

function show_dialog_change_products_display() {
  region1 = Dom.getRegion('change_products_display_mode');
  region2 = Dom.getRegion('change_products_display_menu');
  var pos = [region1.right - region2.width, region1.bottom]
  Dom.setXY('change_products_display_menu', pos);
  dialog_change_products_display.show();
}

function show_dialog_choose_category() {
  region1 = Dom.getRegion('choose_categories');
  region2 = Dom.getRegion('dialog_choose_category');
  var pos = [region1.right - region2.width, region1.bottom]
  Dom.setXY('dialog_choose_category', pos);
  dialog_choose_category.show();
}



function show_dialog_change_families_display() {
  region1 = Dom.getRegion('change_families_display_mode');
  region2 = Dom.getRegion('change_families_display_menu');
  var pos = [region1.right - region2.width, region1.bottom]
  Dom.setXY('change_families_display_menu', pos);
  dialog_change_families_display.show();
}

function show_dialog_change_departments_display() {
  region1 = Dom.getRegion('change_departments_display_mode');
  region2 = Dom.getRegion('change_departments_display_menu');
  var pos = [region1.right - region2.width, region1.bottom]
  Dom.setXY('change_departments_display_menu', pos);
  dialog_change_departments_display.show();
}

function show_dialog_change_families_table_type(){
	region1 = Dom.getRegion('change_families_table_type'); 
  region2 = Dom.getRegion('change_families_table_type_menu'); 
  var pos =[region1.right-region2.width,region1.bottom]
  Dom.setXY('change_families_table_type_menu', pos);
  dialog_change_families_table_type.show();
}
function show_dialog_change_pages_table_type(){
	region1 = Dom.getRegion('change_pages_table_type'); 
  region2 = Dom.getRegion('change_pages_table_type_menu'); 
  var pos =[region1.right-region2.width,region1.bottom]
  Dom.setXY('change_pages_table_type_menu', pos);
  dialog_change_pages_table_type.show();
}
function show_dialog_change_departments_table_type(){
	region1 = Dom.getRegion('change_departments_table_type'); 
  region2 = Dom.getRegion('change_departments_table_type_menu'); 
  var pos =[region1.right-region2.width,region1.bottom]
  Dom.setXY('change_departments_table_type_menu', pos);
  dialog_change_departments_table_type.show();
}
function show_dialog_change_products_table_type(){
	region1 = Dom.getRegion('change_products_table_type'); 
  region2 = Dom.getRegion('change_products_table_type_menu'); 
  var pos =[region1.right-region2.width,region1.bottom]
  Dom.setXY('change_products_table_type_menu', pos);
  dialog_change_products_table_type.show();
}
function get_sales(from,to){
  var request = 'ar_assets.php?tipo=get_asset_sales_data&parent='+ Dom.get('subject').value +'&parent_key=' + Dom.get('subject_key').value + '&from='+from+'&to='+to
  YAHOO.util.Connect.asyncRequest('POST', request, {
   success: function(o) {
    var r = YAHOO.lang.JSON.parse(o.responseText);


    Dom.get('sales_amount').innerHTML=r.sales
    Dom.get('profits').innerHTML=r.profits
    Dom.get('invoices').innerHTML=r.invoices
    Dom.get('customers').innerHTML=r.customers


  }
});

}

function post_change_period_actions(r) {
  period=r.period;
  to=r.to;
  from=r.from;


  request = '&from=' + from + '&to=' + to;

  table_id = 6
  var table = tables['table' + table_id];
  var datasource = tables['dataSource' + table_id];
  datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
  table_id = 7
  var table = tables['table' + table_id];
  var datasource = tables['dataSource' + table_id];
  datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
  table_id = 8
  var table = tables['table' + table_id];
  var datasource = tables['dataSource' + table_id];
  datasource.sendRequest(request, table.onDataReturnInitializeTable, table);
  table_id = 9
  var table = tables['table' + table_id];
  var datasource = tables['dataSource' + table_id];
  datasource.sendRequest(request, table.onDataReturnInitializeTable, table);

  Dom.get('rtext6').innerHTML='<img src="art/loading.gif" style="height:12.9px"/> '+labels.Proccession_Request;
  Dom.get('rtext_rpp6').innerHTML='';
  Dom.get('rtext7').innerHTML='<img src="art/loading.gif" style="height:12.9px"/> '+labels.Proccession_Request;
  Dom.get('rtext_rpp7').innerHTML='';
  Dom.get('rtext8').innerHTML='<img src="art/loading.gif" style="height:12.9px"/> '+labels.Proccession_Request;
  Dom.get('rtext_rpp8').innerHTML='';
  Dom.get('rtext9').innerHTML='<img src="art/loading.gif" style="height:12.9px"/> '+labels.Proccession_Request;
  Dom.get('rtext_rpp9').innerHTML='';

  
  get_sales(from, to)


}

function new_deal () {
  location.href = "new_deal.php?parent=store&parent_key="+Dom.get('store_key').value;
}

function init() {

	dialog_export['products'] = new YAHOO.widget.Dialog("dialog_export_products", {
    visible: false,
    close: true,
    underlay: "none",
    draggable: false
  });
 dialog_export['products'].render();
 Event.addListener("export_products", "click", show_export_dialog, 'products');
 Event.addListener("export_csv_products", "click", export_table, {
  output: 'csv',
  table: 'products',
  parent: 'store',
  'parent_key': Dom.get('store_key').value
});
 Event.addListener("export_xlsproducts", "click", export_table, {
  output: 'xls',
  table: 'products',
  parent: 'store',
  'parent_key': Dom.get('store_key').value
});


 Event.addListener("export_result_download_link_products", "click", download_export_file,'products');

 Event.addListener(['page_general', 'page_visitors','page_products'], "click", change_pages_view);

 ids = ['elements_System', 'elements_Info', 'elements_Department', 'elements_Family','elements_Product', 'elements_ProductCategory', 'elements_FamilyCategory'];
 Event.addListener(ids, "click", change_page_elements);

 get_sales(Dom.get('from').value, Dom.get('to').value)


 init_search('products_store');


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


 dialog_choose_category = new YAHOO.widget.Dialog("dialog_choose_category", {
  visible: false,
  close: true,
  underlay: "none",
  draggable: false
});
 dialog_choose_category.render();
 YAHOO.util.Event.addListener("choose_categories", "click", show_dialog_choose_category);





 dialog_change_departments_display = new YAHOO.widget.Dialog("change_departments_display_menu", {
  visible: false,
  close: true,
  underlay: "none",
  draggable: false
});
 dialog_change_departments_display.render();
 YAHOO.util.Event.addListener("change_departments_display_mode", "click", show_dialog_change_departments_display);



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


 dialog_change_departments_table_type = new YAHOO.widget.Dialog("change_departments_table_type_menu", {
  visible: false,
  close: true,
  underlay: "none",
  draggable: false
});
 dialog_change_departments_table_type.render();
 YAHOO.util.Event.addListener("change_departments_table_type", "click", show_dialog_change_departments_table_type);

 dialog_change_pages_table_type = new YAHOO.widget.Dialog("change_pages_table_type_menu", {
  visible: false,
  close: true,
  underlay: "none",
  draggable: false
});
 dialog_change_pages_table_type.render();
 YAHOO.util.Event.addListener("change_pages_table_type", "click", show_dialog_change_pages_table_type);






 Event.addListener(['elements_discontinued', 'elements_nosale', 'elements_private', 'elements_sale', 'elements_historic'], "click", change_elements);

 Event.addListener(['elements_family_discontinued', 'elements_family_discontinuing', 'elements_family_normal', 'elements_family_inprocess', 'elements_family_nosale'], "click", change_family_elements);


 YAHOO.util.Event.addListener('clean_table_filter_show0', "click", show_filter, 0);
 YAHOO.util.Event.addListener('clean_table_filter_hide0', "click", hide_filter, 0);
 YAHOO.util.Event.addListener('clean_table_filter_show1', "click", show_filter, 1);
 YAHOO.util.Event.addListener('clean_table_filter_hide1', "click", hide_filter, 1);
 YAHOO.util.Event.addListener('clean_table_filter_show2', "click", show_filter, 2);
 YAHOO.util.Event.addListener('clean_table_filter_hide2', "click", hide_filter, 2);


 Event.addListener(['details', 'departments', 'families', 'products', 'categories', 'deals', 'websites', 'sales'], "click", change_block);
 Event.addListener(['deals_details', 'campaigns', 'offers'], "click", change_deals_block);
 Event.addListener(['sites', 'pages'], "click", change_websites_block);






 var oACDS = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS.queryMatchContains = true;
 var oAutoComp = new YAHOO.widget.AutoComplete("f_input0", "f_container0", oACDS);
 oAutoComp.minQueryLength = 0;

 var oACDS1 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS1.queryMatchContains = true;
 oACDS1.table_id = 1;
 var oAutoComp1 = new YAHOO.widget.AutoComplete("f_input1", "f_container1", oACDS1);
 oAutoComp1.minQueryLength = 0;

 var oACDS2 = new YAHOO.util.FunctionDataSource(mygetTerms);
 oACDS2.queryMatchContains = true;
 oACDS2.table_id = 2;
 var oAutoComp2 = new YAHOO.widget.AutoComplete("f_input2", "f_container2", oACDS2);
 oAutoComp2.minQueryLength = 0;


 ids = ['department_general', 'department_sales', 'department_stock','department_timeline'];
 YAHOO.util.Event.addListener(ids, "click", change_department_view, {
  'table_id': 0,
  'parent': 'store'
})
 ids = ['department_period_all', 'department_period_three_year', 'department_period_year', 'department_period_yeartoday', 'department_period_six_month', 'department_period_quarter', 'department_period_month', 'department_period_ten_day', 'department_period_week'];
 YAHOO.util.Event.addListener(ids, "click", change_table_period, {
  'table_id': 0,
  'subject': 'department'
});
 ids = ['department_avg_totals', 'department_avg_month', 'department_avg_week', "department_avg_month_eff", "department_avg_week_eff"];
 YAHOO.util.Event.addListener(ids, "click", change_avg, {
  'table_id': 0,
  'subject': 'department'
});


 ids = ['family_general', 'family_sales', 'family_stock', 'family_timeline'];
 YAHOO.util.Event.addListener(ids, "click", change_family_view, {
  'table_id': 1,
  'parent': 'store'
})

 ids = ['family_period_all', 'family_period_three_year', 'family_period_year', 'family_period_yeartoday', 'family_period_six_month', 'family_period_quarter', 'family_period_month', 'family_period_ten_day', 'family_period_week'];
 YAHOO.util.Event.addListener(ids, "click", change_table_period, {
  'table_id': 1,
  'subject': 'family'
});

 ids = ['family_avg_totals', 'family_avg_month', 'family_avg_week', "family_avg_month_eff", "family_avg_week_eff"];
 YAHOO.util.Event.addListener(ids, "click", change_avg, {
  'table_id': 1,
  'subject': 'family'
});

 ids = ['product_general', 'product_sales', 'product_stock', 'product_parts', 'product_cats','product_timeline','product_properties'];
 YAHOO.util.Event.addListener(ids, "click", change_product_view, {
  'table_id': 2,
  'parent': 'store'
})
 ids = ['product_period_all', 'product_period_three_year', 'product_period_year', 'product_period_yeartoday', 'product_period_six_month', 'product_period_quarter', 'product_period_month', 'product_period_ten_day', 'product_period_week'];
 YAHOO.util.Event.addListener(ids, "click", change_table_period, {
  'table_id': 2,
  'subject': 'product'
});
 ids = ['product_avg_totals', 'product_avg_month', 'product_avg_week', "product_avg_month_eff", "product_avg_week_eff"];
 YAHOO.util.Event.addListener(ids, "click", change_avg, {
  'table_id': 2,
  'subject': 'product'
});



 YAHOO.util.Event.addListener('show_percentages', "click", show_percentages, 'departments');




 ids = ['elements_store_history_changes', 'elements_store_history_notes', 'elements_store_history_attachments'];
 YAHOO.util.Event.addListener(ids, "click", change_history_elements);

 dialog_sales_history_timeline_group = new YAHOO.widget.Dialog("dialog_sales_history_timeline_group", {visible : false,close:true,underlay: "none",draggable:false});
 dialog_sales_history_timeline_group.render();
 YAHOO.util.Event.addListener("change_sales_history_timeline_group", "click", show_dialog_sales_history_timeline_group);

}


YAHOO.util.Event.onDOMReady(init);

YAHOO.util.Event.onContentReady("rppmenu0", function() {
  var oMenu = new YAHOO.widget.ContextMenu("rppmenu0", {
    trigger: "rtext_rpp0"
  });
  oMenu.render();
  oMenu.subscribe("show", oMenu.focus);
});
YAHOO.util.Event.onContentReady("filtermenu0", function() {
  var oMenu = new YAHOO.widget.ContextMenu("filtermenu0", {
    trigger: "filter_name0"
  });
  oMenu.render();
  oMenu.subscribe("show", oMenu.focus);


});

YAHOO.util.Event.onContentReady("rppmenu1", function() {
  var oMenu = new YAHOO.widget.ContextMenu("rppmenu1", {
    trigger: "rtext_rpp1"
  });
  oMenu.render();
  oMenu.subscribe("show", oMenu.focus);
});
YAHOO.util.Event.onContentReady("filtermenu1", function() {
  var oMenu = new YAHOO.widget.ContextMenu("filtermenu1", {
    trigger: "filter_name1"
  });
  oMenu.render();
  oMenu.subscribe("show", oMenu.focus);
});




YAHOO.util.Event.onContentReady("info_period_menu", function() {
  var oMenu = new YAHOO.widget.Menu("info_period_menu", {
    context: ["info_period", "tr", "br"]
  });
  oMenu.render();
  oMenu.subscribe("show", oMenu.focus);
  YAHOO.util.Event.addListener("info_period", "click", oMenu.show, null, oMenu);
});


YAHOO.util.Event.onContentReady("rppmenu2", function() {
  var oMenu = new YAHOO.widget.ContextMenu("rppmenu2", {
    trigger: "rtext_rpp2"
  });
  oMenu.render();
  oMenu.subscribe("show", oMenu.focus);
});
YAHOO.util.Event.onContentReady("filtermenu2", function() {
  var oMenu = new YAHOO.widget.ContextMenu("filtermenu2", {
    trigger: "filter_name2"
  });
  oMenu.render();
  oMenu.subscribe("show", oMenu.focus);
});


YAHOO.util.Event.onContentReady("rppmenu4", function() {
  var oMenu = new YAHOO.widget.ContextMenu("rppmenu4", {
    trigger: "rtext_rpp4"
  });
  oMenu.render();
  oMenu.subscribe("show", oMenu.focus);
});
YAHOO.util.Event.onContentReady("filtermenu4", function() {
  var oMenu = new YAHOO.widget.ContextMenu("filtermenu4", {
    trigger: "filter_name4"
  });
  oMenu.render();
  oMenu.subscribe("show", oMenu.focus);
});

