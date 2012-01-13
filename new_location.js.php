<?php include_once('common.php')?>


var warehouse_key=<?php echo $_REQUEST['warehouse_key'] ?>;
var warehouse_area_id=0;

var auto= <?php echo$_REQUEST['auto']?>;
var edit_window=0;
location_draw_width=300;
location_draw_height=300;

var individual_location_data =new Object;
var current_shape='Box';
YAHOO.util.Event.addListener(window, "load", function() {
    tables = new function() {
		var tableid=0; // Change if you have more the 1 table
	    var tableDivEL="table"+tableid;



	    var LocationColumnDefs = [
				      {key:"location_key", label:"", hidden:true,action:"none",isPrimaryKey:true}
				      ,{key:"code", label:"<?php echo _('Location')?>",width:60,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'location'}
				      ,{key:"area", label:"<?php echo _('Area')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'location'}
				      ,{key:"used_for", label:"<?php echo _('Used for')?>", width:150,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'location'}
				      
				      ,{key:"max_slots", label:"<?php echo _('Max Slots')?>", width:80,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'location'}
				      ,{key:"max_weight", label:"<?php echo _('Max Weight')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'location'}
				      ,{key:"max_vol", label:"<?php echo _('Max Volume')?>", width:120,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}, editor: new YAHOO.widget.TextboxCellEditor({asyncSubmitter: CellEdit}),object:'location'}
				      ,{key:"delete", label:"", width:40,sortable:true,className:"aright",object:'location',action:'delete'}


				      ];
	    //?tipo=location&tid=0"
	    this.dataSource0 = new YAHOO.util.DataSource("ar_edit_warehouse.php?tipo=edit_locations&parent=warehouse");
	    this.dataSource0.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource0.connXhrMode = "queueRequests";
	    this.dataSource0.responseSchema = {
		resultsList: "resultset.data", 
		metaFields: {
		    rowsPerPage:"resultset.records_perpage",
		    rtext:"resultset.rtext",
		    sort_key:"resultset.sort_key",
		    sort_dir:"resultset.sort_dir",
		    tableid:"resultset.tableid",
		    filter_msg:"resultset.filter_msg",
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			 'code',
			 'area','location_key','max_slots','max_weight','max_vol','used_for','delete'
			 ]};
	    //__You shouls not change anything from here

	    //this.dataSource.doBeforeCallback = mydoBeforeCallback;



	    this.table0 = new YAHOO.widget.DataTable(tableDivEL, LocationColumnDefs,
								   this.dataSource0
								 , {

								     renderLoopSize: 50,generateRequest : myRequestBuilder
								       ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage    : <?php echo $_SESSION['state']['locations']['edit_table']['nr']?>,containers : 'paginator', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500]
									      ,template : "{FirstPageLink}{PreviousPageLink}<strong id='paginator_info0'>{CurrentPageReport}</strong>{NextPageLink}{LastPageLink}"



									  })
								     
								     ,sortedBy : {
									 key: "<?php echo $_SESSION['state']['locations']['edit_table']['order']?>",
									 dir: "<?php echo $_SESSION['state']['locations']['edit_table']['order_dir']?>"
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table0.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table0.doBeforeSortColumn = mydoBeforeSortColumn;
	    this.table0.doBeforePaginatorChange = mydoBeforePaginatorChange;

	    this.table0.subscribe("cellMouseoverEvent", highlightEditableCell);
	    this.table0.subscribe("cellMouseoutEvent", unhighlightEditableCell);
	    this.table0.subscribe("cellClickEvent", onCellClick);

	    this.table0.filter={key:'<?php echo$_SESSION['state']['locations']['edit_table']['f_field']?>',value:'<?php echo$_SESSION['state']['locations']['edit_table']['f_value']?>'};
	    YAHOO.util.Event.addListener('yui-pg0-0-page-report', "click",myRowsPerPageDropdown)


var tableid=2; 
	    var tableDivEL="table"+tableid;

	   
	    var ColumnDefs = [
			 {key:"key", label:"",width:100,hidden:true}
                    ,{key:"code", label:"<?php echo _('Code')?>",width:100,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
                   ,{key:"name", label:"<?php echo _('Name')?>",width:250,sortable:true,className:"aleft",sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_ASC}}
						
			];
		this.dataSource2 = new YAHOO.util.DataSource("ar_quick_tables.php?tipo=area_list&warehouse_key="+warehouse_key+"&tableid="+tableid+"&nr=20&sf=0");
//alert("ar_quick_tables.php?tipo=area_list&warehouse_key="+warehouse_key+"&tableid="+tableid+"&nr=20&sf=0");
	    this.dataSource2.responseType = YAHOO.util.DataSource.TYPE_JSON;
	    this.dataSource2.connXhrMode = "queueRequests";
	    	    this.dataSource2.table_id=tableid;

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
		    totalRecords: "resultset.total_records" // Access to value in the server response
		},
		
		
		fields: [
			 "code",'name','key'
			 ]};

	    this.table2 = new YAHOO.widget.DataTable(tableDivEL, ColumnDefs,
								   this.dataSource2
								 , {
								     renderLoopSize: 50,generateRequest : myRequestBuilder
								      ,paginator : new YAHOO.widget.Paginator({
									      rowsPerPage:20,containers : 'paginator2', 
 									      pageReportTemplate : '(<?php echo _('Page')?> {currentPage} <?php echo _('of')?> {totalPages})',
									      previousPageLinkLabel : "<",
 									      nextPageLinkLabel : ">",
 									      firstPageLinkLabel :"<<",
 									      lastPageLinkLabel :">>",rowsPerPageOptions : [10,25,50,100,250,500],alwaysVisible:false
									      ,template : "{PreviousPageLink}<strong id='paginator_info2'>{CurrentPageReport}</strong>{NextPageLink}"
									  })
								     
								     ,sortedBy : {
									 key: "code",
									 dir: ""
								     },
								     dynamicData : true

								  }
								   
								 );
	    
	    this.table2.handleDataReturnPayload =myhandleDataReturnPayload;
	    this.table2.doBeforeSortColumn = mydoBeforeSortColumn;
	    //this.table2.subscribe("cellClickEvent", this.table2.onEventShowCellEditor);

 this.table2.subscribe("rowMouseoverEvent", this.table2.onEventHighlightRow);
       this.table2.subscribe("rowMouseoutEvent", this.table2.onEventUnhighlightRow);
      this.table2.subscribe("rowClickEvent", select_area);
        this.table2.table_id=tableid;
           this.table2.subscribe("renderEvent", myrenderEvent);


	    this.table2.doBeforePaginatorChange = mydoBeforePaginatorChange;
	    this.table2.filter={key:'code',value:''};




	
	};
    });

function exit_add_location(){
	if(edit_window==1){
		window.location.href='edit_warehouse.php?id='+warehouse_key;
	}
	else if(edit_window==2){
		window.location.href='edit_warehouse_area.php?id='+warehouse_area_id;
	}

}

function select_area(oArgs){
//alert('ss');return;

area_name=tables.table2.getRecord(oArgs.target).getData('name');
area_key=tables.table2.getRecord(oArgs.target).getData('key');
 dialog_area_list.hide();

Dom.get('location_area').value=area_name;
Dom.get('location_warehouse_area_key').value=area_key;
}

function shape_type_changed(){
    var shape=Dom.get('location_shape_type').getAttribute('value');
    if(shape!=current_shape){
	current_shape=shape;
	
	if(shape=='Box'){
	    Dom.get('tr_location_width').style.display='';
	    Dom.get('tr_location_deepth').style.display='';
	    Dom.get('tr_location_heigth').style.display='';
	    Dom.get('tr_location_radius').style.display='none';
// 	    if(!isNaN(parseFloat(Dom.get('tr_location_radius').value))){
// 		Dom.get('tr_location_width').value=2*Dom.get('tr_location_radius').value;
// 		Dom.get('tr_location_deepth').value=2*Dom.get('tr_location_radius').value;
// 	    }
	    
	}else{
	    Dom.get('tr_location_width').style.display='none';
	    Dom.get('tr_location_deepth').style.display='none';
	    Dom.get('tr_location_heigth').style.display='';
	    Dom.get('tr_location_radius').style.display='';
	    //	    if(!isNaN(parseFloat(Dom.get('tr_location_width').value)))
	    //	Dom.get('tr_location_radius').value=0.5*Dom.get('tr_location_width').value;

    }

    }

}


function get_block(){
    Dom.get('welcome').innerHTML='<?php echo _('Adding new location')?>';
    Dom.get('the_chooser').style.display='none';
    Dom.get('block_'+this.id).style.display='';
}


function get_location_data(){
    individual_location_data['Location Code']=Dom.get('location_code').value; 
    individual_location_data['Location Warehouse Area Key']=Dom.get('location_warehouse_area_key').value;
    individual_location_data['Location Max Slots']=Dom.get('location_max_slots').value;


    individual_location_data['Location Mainly Used For']=Dom.get('location_used_for').getAttribute('value'); 
    individual_location_data['Location Max Weight']=Dom.get('location_max_weight').value;
    individual_location_data['Location Shape Type']=Dom.get('location_shape_type').getAttribute('value'); 

     
    individual_location_data['Location Width']=Dom.get('location_width').value;
    individual_location_data['Location Deepth']=Dom.get('location_Depth').value;
    individual_location_data['Location Heigth']=Dom.get('location_heigth').value;
     individual_location_data['Location Radius']=Dom.get('location_radius').value;
}
function reset_location_data(){
  individual_location_data['Location Code']=Dom.get('location_code').value; 
    individual_location_data['Location Warehouse Area Key']=Dom.get('location_warehouse_area_key').getAttribute('ovalue'); 
    individual_location_data['Location Max Slots']=Dom.get('location_max_slots').getAttribute('ovalue'); 


    individual_location_data['Location Mainly Used For']=Dom.get('location_used_for').getAttribute('ovalue'); 
    individual_location_data['Location Max Weight']=Dom.get('location_max_weight').getAttribute('ovalue'); 
    individual_location_data['Location Shape Type']=Dom.get('location_shape_type').getAttribute('ovalue'); 

     
    individual_location_data['Location Width']=Dom.get('location_width').getAttribute('ovalue'); 
    individual_location_data['Location Deepth']=Dom.get('location_deepth').getAttribute('ovalue'); 
    individual_location_data['Location Heigth']=Dom.get('location_heigth').getAttribute('ovalue'); 
     individual_location_data['Location Radius']=Dom.get('location_radius').getAttribute('ovalue'); 
}


function save_add_location(){

    get_location_data();
    var json_value = YAHOO.lang.JSON.stringify(individual_location_data);
    var request='ar_edit_warehouse.php?tipo=new_location&values=' + encodeURIComponent(json_value); 
    //alert(request)
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
	  //alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.action=='created'){
		
		
		    window.location='location.php?r=nl&id='+r.location_key;
		
		    //reset_location_data();
		    //var table=tables['table0']
		    //var datasource=tables['dataSource0'];    
		    //datasource.sendRequest('',table.onDataReturnInitializeTable, table);
		    
		    
		}else if(r.action=='error'){
		    alert(r.msg);
		}
			    

			
	    }
	});

}

function save_add_location_return(){

    get_location_data();
    var json_value = YAHOO.lang.JSON.stringify(individual_location_data);
    var request='ar_edit_warehouse.php?tipo=new_location&values=' + encodeURIComponent(json_value); 
    //alert(request)
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
	  //alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.action=='created'){
		
		//new_location.php?warehouse_id=1
		    window.location='new_location.php?warehouse_id=1&auto=1&window='+edit_window;
		    //reset_location_data();
		    //var table=tables['table0']
		    //var datasource=tables['dataSource0'];    
		    //datasource.sendRequest('',table.onDataReturnInitializeTable, table);
		    
		    
		}else if(r.action=='error'){
		    alert(r.msg);
		}
			    

			
	    }
	});

}

function draw_location(){
    	iso=draw_isometric_box(100,200,50,300,300);
	var b = R.path(iso.svg_path_base);
	b.translate(iso.translate.x,iso.translate.y);
	b.attr("fill", "#a8bcd7");
	var c = R.path(iso.svg_path);
	c.translate(iso.translate.x,iso.translate.y);
	c.attr({"fill": "#fff","fill-opacity":.7});
	var t = R.path(iso.svg_path_top);
	t.translate(iso.translate.x,iso.translate.y);
	t.attr({"fill": "#fff","fill-opacity":.9});

}
function draw_isometric_box(width,height,deep,canvas_width,canvas_height){
    var path3d=new Array();
    var svg_path='';

    var minx=999999;
    var miny=999999;
    var maxx=-999999;
    var maxy=-999999;

    path3d[0]=[0,0,0];
    path3d[1]=[0,width,0];
    path3d[2]=[deep,width,0];
    path3d[3]=[deep,0,0];
    data1=isometric_transformation(path3d);
    path3d[0]=[0,0,height];
    path3d[1]=[0,width,height];
    path3d[2]=[deep,width,height];
    path3d[3]=[deep,0,height];
    data2=isometric_transformation(path3d);
   
    path3d[0]=[0,0,0];
    path3d[1]=[deep,0,0];
    path3d[2]=[deep,0,height];
    path3d[3]=[0,0,height];
    data3=isometric_transformation(path3d);
    
    path3d[0]=[0,width,0];
    path3d[1]=[deep,width,0];
    path3d[2]=[deep,width,height];
    path3d[3]=[0,width,height];
    data4=isometric_transformation(path3d);

    if(data1.minx<minx) minx=data1.minx;
    if(data2.minx<minx) minx=data2.minx;
    if(data3.minx<minx) minx=data3.minx;
    if(data4.minx<minx) minx=data4.minx;
    if(data1.miny<miny) miny=data1.miny;
    if(data2.miny<miny) miny=data2.miny;
    if(data3.miny<miny) miny=data3.miny;
    if(data4.miny<miny) miny=data4.miny;

    if(data1.maxx>maxx) maxx=data1.maxx;
    if(data2.maxx>maxx) maxx=data2.maxx;
    if(data3.maxx>maxx) maxx=data3.maxx;
    if(data4.maxx>maxx) maxx=data4.maxx;
    if(data1.maxy>maxy) maxy=data1.maxy;
    if(data2.maxy>maxy) maxy=data2.maxy;
    if(data3.maxy>maxy) maxy=data3.maxy;
    if(data4.maxy>maxy) maxy=data4.maxy;
    
    delatax=maxx-minx;
    delatay=maxy-miny;

    var svg_path_top=''
    var points=data4.points;
    for (var i=0; i<points.length; i++) {
	    if(i==0){svg_path_top=svg_path_top+'M';}else{svg_path_top=svg_path_top+'L';}
	    svg_path_top+=points[i][0]+' '+points[i][1];
	}svg_path_top+='Z';
  
    


    var points=data3.points;
    for (var i=0; i<points.length; i++) {
	    if(i==0){svg_path=svg_path+'M';}else{svg_path=svg_path+'L';}
	    svg_path+=points[i][0]+' '+points[i][1];
	}svg_path+='Z';
  
    svg_path_base=svg_path;
    svg_path='';
    var points=data2.points;
    for (var i=0; i<points.length; i++) {
	    if(i==0){svg_path=svg_path+'M';}else{svg_path=svg_path+'L';}
	    svg_path+=points[i][0]+' '+points[i][1];
	}svg_path+='Z';
     var points=data1.points;
    for (var i=0; i<points.length; i++) {
	    if(i==0){svg_path=svg_path+'M';}else{svg_path=svg_path+'L';}
	    svg_path+=points[i][0]+' '+points[i][1];
	}svg_path+='Z';
//    var points=data4.points;
//     for (var i=0; i<points.length; i++) {
// 	    if(i==0){svg_path=svg_path+'M';}else{svg_path=svg_path+'L';}
// 	    svg_path+=points[i][0]+' '+points[i][1];
// 	}svg_path+='Z';

    
    ret_data={'svg_path_base':svg_path_base,'svg_path_top':svg_path_top,'svg_path':svg_path,'translate':{'x':-minx+1,'y':-miny+1}};
    return ret_data;

}
function isometric_transformation(path3d){
    
    var sqrt3=1.73205;
    var invsqr6=0.4082;
    var minx=999999;
    var miny=999999;
    var maxx=-999999;
    var maxy=-999999;
    var path=Array();
    for (var i=0; i<path3d.length; i++) 
	{
	    // alert("caca");
	    path[i]=new Array();
	    x=invsqr6*(sqrt3*path3d[i][0]-sqrt3*path3d[i][2]);
	    y=invsqr6*(path3d[i][0]-2*path3d[i][1]+path3d[i][2]);
	    path[i][0]=x;
	    path[i][1]=y;
	    if(x<minx)
		minx=x;
	    if(y<miny)
		miny=y;
	    if(x>maxx)
	     maxx=x;
	    if(y>maxy)
		maxy=y;
	}
    
    var data=new Object;
     data={
	 'points':path
	 ,'minx':minx
	 ,'miny':miny
	 ,'maxx':maxx
	 ,'maxy':maxy
     }
     return data;
}

function show_dialog_area_list(){

 region1 = Dom.getRegion('show_area_list'); 
    region2 = Dom.getRegion('dialog_area_list'); 

 var pos =[region1.right-region2.width-20,region1.bottom]

    Dom.setXY('dialog_area_list', pos);

dialog_area_list.show();
}

function init(){

init_search('locations');

edit_window=Dom.get('window').value;
warehouse_area_id=Dom.get('warehouse_area_id').value;


if(auto==1){
    Dom.get('welcome').innerHTML='<?php echo _('Adding new location')?>';
    Dom.get('the_chooser').style.display='none';
    Dom.get('block_individual').style.display='';
}
	
    var ids = ["individual","shelf","rack","floor"]; 
    YAHOO.util.Event.addListener(ids, "click", get_block);
	
    YAHOO.util.Event.addListener('add_location', "click", save_add_location);

	YAHOO.util.Event.addListener('add_location_and_add_other', "click", save_add_location_return);



	dialog_area_list = new YAHOO.widget.Dialog("dialog_area_list", {visible : false,close:true,underlay: "none",draggable:false});
    dialog_area_list.render();
	
		   

	
    Event.addListener("show_area_list", "click", show_dialog_area_list);

YAHOO.util.Event.onContentReady("location_area", function () {
  
 
  
  var new_loc_oDS = new YAHOO.util.XHRDataSource("ar_warehouse.php");
    new_loc_oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
    new_loc_oDS.responseSchema = {
resultsList : "data"
        ,
fields :
        ["code","key","stock"]
    };
    var new_loc_oAC = new YAHOO.widget.AutoComplete("location_area", "location_area_container", new_loc_oDS);
   new_loc_oAC.maxResultsDisplayed = 5;
  
  new_loc_oAC.generateRequest = function(sQuery) {

	return "?tipo=find_warehouse_area&parent_key=0&query=" + sQuery ;
    };
    new_loc_oAC.forceSelection = true;

	wa_selected= function(sType, aArgs) {
	    var myAC = aArgs[0]; var elLI = aArgs[1]; var oData = aArgs[2]; 
	    Dom.get("location_warehouse_area_key").value = oData[2];
	    
	};

    new_loc_oAC.itemSelectEvent.subscribe(wa_selected);
    
});


}
YAHOO.util.Event.onDOMReady(init);



