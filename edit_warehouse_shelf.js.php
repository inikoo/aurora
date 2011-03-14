
var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
var shelf_cols_data=new Object();
var shelf_rows_data=new Object();
var shelf_locations_data=new Object();


var shelf_data ={
    'code':{'dbname':'Shelf Code','name':'shelf_code'}
    ,'type':{'dbname':'Shelf Type Key','name':'shelf_shelf_type_key'}
    ,'rows':{'dbname':'Shelf Number Rows','name':'shelf_rows'}
    ,'cols':{'dbname':'Shelf Number Columns','name':'shelf_columns'}
    ,'warehouse_key':{'dbname':'Shelf Warehouse Key','name':'shelf_warehouse_key'}
    ,'area_key':{'dbname':'Shelf Area Key','name':'shelf_warehouse_area_key'}

    };



function get_location_code(row,col){
    

    var shelf_code=Dom.get('shelf_code').value;
    return shelf_code+'.'+col+'.'+row;
}


function verify_shelf_data(){
return true;
}


function verify_shelf(){

if(verify_shelf_data){
Dom.removeClass('save_shelf','disabled');

}else{
Dom.addClass('save_shelf','disabled');

}

}

function  validate_shelf_warehouse_area(sType,aArgs){
var myAC = aArgs[0]; var elLI = aArgs[1]; var oData = aArgs[2]; 
myAC.getInputEl().value = oData[1]+' ['+oData[2]+']';
Dom.get('shelf_warehouse_area_key').value=oData[0];
}

function  validate_shelf_type(sType,aArgs){

 var myAC = aArgs[0]; var elLI = aArgs[1]; var oData = aArgs[2]; 
 
 myAC.getInputEl().value = oData[1]+' ['+oData[3]+']';
 //fields : [0"key",1"name",2"description",3"type",4"rows",5"columns",6"l_height",7"l_length",8'l_deep',9'l_weight',10'l_volume']
 Dom.get('tr_layout').style.display='';
 var rows=parseFloat(oData[4]);
 var cols=parseFloat(oData[5]);
 var height=oData[6];
 var length=oData[7];
 var deep=oData[8];
 var max_weight=oData[9];
 var max_volume=oData[10];
 
 Dom.get('shelf_shelf_type_key').value=oData[0];

 Dom.get('shelf_rows').value=rows;
 Dom.get('shelf_columns').value=cols;

 //alert(rows,cols)

 
 shelf_cols_data=new Object();
 shelf_rows_data=new Object();
 shelf_locations_data=new Object();

for(i=1;i<rows+1;i++){
    shelf_rows_data[i]={'height':height}
    shelf_locations_data[i]=new Object();

	    
    for(j=1;j<cols+1;j++){
	//             {


	if(i==1){
	   
	    shelf_cols_data[j]={'length':length};   
	}
	var tmp=new Object();
	

	
	shelf_locations_data[i][j]={'Location Shape Type':'Box'
				    ,'Location Height':height
				    ,'Location Width':length
				    ,'Location Deep':deep
				    ,'Location Code':get_location_code(i,j)
				    ,'Location Max Weight':max_weight
				    ,'Location Max Volume':max_volume}
	
    }
}
Dom.get('new_warehouse_shelf_block').innerHTML=oData[11];

//{'height':height,'length':length,'deep':deep,'code':get_location_code(i,j)}


render_shelf_locatons_layout(rows,cols);
verify_shelf();

}





function render_shelf_locatons_layout(rows,cols){

  rows++;
    cols++;
  var oTbl=document.createElement("Table");
       for(i=0;i<rows;i++)
       {
            var  oTR= oTbl.insertRow(i);
               for(j=0;j<cols;j++)
                {
                                var  oTD= oTR.insertCell(j);
                                if(j>0 && i>0 ){
                                oTD.id='shelf_location_layout'+i+'_'+j;
                                var location_data='<b>'+shelf_locations_data[i][j]['Location Code']+'</b>';
                            
                                oTD.innerHTML=location_data;
                                }else if(j==0 && i>0){
                                
                                oTD.id='shelf_location_layout'+i
                                var location_data='<b>'+i+'</b>';
                                location_data+='<br>H:'+shelf_rows_data[i].height;
                                
                                oTD.innerHTML=location_data;
                                Dom.addClass(oTD,'header');
                                Dom.addClass(oTD,'rows_header');
                                }else if(i==0 && j>0){
                                 Dom.addClass(oTD,'header');
                                Dom.addClass(oTD,'cols_header');
                                oTD.id='shelf_location_layout'+j
                                var location_data='<b>'+j+'</b>';
                                location_data+='<br>W:'+shelf_cols_data[j].length;
                                
                                oTD.innerHTML=location_data;
                                
                                }else{
                                Dom.addClass(oTD,'header');

                                }
                }

        }
Dom.get('shelf_locations_layout').appendChild(oTbl);


}


function add_shelf(){
    if(Dom.hasClass('add_shelf','disabled')){
	
	return;
    }
    var values=new Object();
    var s_values=new Object();
    for(key in shelf_data )
    s_values[shelf_data[key].dbname]=Dom.get(shelf_data[key].name).value;
    values['Shelf Data']=s_values;
    values['Locations Data']=shelf_locations_data;
    var json_value = YAHOO.lang.JSON.stringify(values);
    var request='ar_edit_warehouse.php?tipo=new_shelf&values=' + (json_value); 
    //alert(json_value);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.action=='created'){
		    reset_shelf_type_data();
		    var table=tables['table3'];
		    var datasource=tables['dataSource3'];

		    datasource.sendRequest('',table.onDataReturnInitializeTable, table);      
		}else{
		    Dom.get('new_warehouse_shelf_type_block').innerHTML=r.msg;
		}

	    }
	});


}


 var change_shelf_type_view=function(e){

     var tipo=this.getAttribute('tipo');
      var table=tables['table3'];
      if(tipo=='general'){
	  table.hideColumn('length');
	  table.hideColumn('height');
	  table.hideColumn('deep');
	  table.hideColumn('max_weight');
	  table.hideColumn('max_vol');
	  table.showColumn('type');
	  table.showColumn('description');
	  table.showColumn('rows');
	  table.showColumn('columns');
	  table.showColumn('delete');
	  

	  Dom.get('shelf_type_dimensions_view').className='';
	  Dom.get('shelf_type_general_view').className='selected';
      }else{
	 
	  table.showColumn('length');
	  table.showColumn('height');
	  table.showColumn('deep');
	  table.showColumn('max_weight');
	  table.showColumn('max_vol');
	  table.hideColumn('type');
	  table.hideColumn('description');
	  table.hideColumn('rows');
	  table.hideColumn('columns');
	  table.hideColumn('delete');
	  

	  Dom.get('shelf_type_dimensions_view').className='selected';
	  Dom.get('shelf_type_general_view').className='';
      }
      YAHOO.util.Connect.asyncRequest('POST','ar_sessions.php?tipo=update&keys=shelf_types-view&value='+escape(tipo),{});
  }


function show_add_shelf_dialog(){
Dom.get('new_warehouse_shelf_block').style.display='';
Dom.get('new_warehouse_shelf_messages').style.display='';


Dom.get('add_shelf').style.display='none';
Dom.get('close_add_shelf').style.display='';
Dom.get('save_shelf').style.display='';


}


function reset_shelf_data(){
    Dom.get('shelf_warehouse_area').value='';
    Dom.get('shelf_code').value='';
    Dom.get('shelf_shelf_type').value='';

}

function hide_add_shelf_dialog(){

reset_shelf_data();

Dom.get('new_warehouse_shelf_block').style.display='none';
Dom.get('new_warehouse_shelf_messages').style.display='none';

Dom.get('add_shelf').style.display='';
Dom.get('close_add_shelf').style.display='none';
Dom.get('save_shelf').style.display='none';
}

function show_add_shelf_type_dialog(){
Dom.get('new_warehouse_shelf_type_block').style.display='';
Dom.get('new_warehouse_shelf_type_messages').style.display='';

Dom.get('add_shelf_type').style.display='none';
Dom.get('close_add_shelf_type').style.display='';
Dom.get('save_shelf_type').style.display='';


}
function hide_add_shelf_type_dialog(){
reset_shelf_type_data();
Dom.get('new_warehouse_shelf_type_block').style.display='none';
Dom.get('new_warehouse_shelf_type_messages').style.display='none';

Dom.get('add_shelf_type').style.display='';
Dom.get('close_add_shelf_type').style.display='none';
Dom.get('save_shelf_type').style.display='none';
}

var shelf_type_data =new Object;

function get_shelf_type_data(){
   
    shelf_type_data['Shelf Type Name']=Dom.get('shelf_type_name').value;
    shelf_type_data['Shelf Type Description']=Dom.get('shelf_type_description').value;
    shelf_type_data['Shelf Type Type']=Dom.get('shelf_type_type').value;
    shelf_type_data['Shelf Type Rows']=Dom.get('shelf_type_rows').value;
    shelf_type_data['Shelf Type Columns']=Dom.get('shelf_type_columns').value;
    shelf_type_data['Shelf Type Location Length']=Dom.get('shelf_type_length').value;
    shelf_type_data['Shelf Type Location Deep']=Dom.get('shelf_type_deep').value;
    shelf_type_data['Shelf Type Location Height']=Dom.get('shelf_type_height').value;
    shelf_type_data['Shelf Type Location Max Weight']=Dom.get('shelf_type_weight').value;
    shelf_type_data['Shelf Type Location Max Volume']=Dom.get('shelf_type_volume').value;

}

function reset_shelf_type_data(){
    Dom.get('warehouse_key').value=Dom.get('warehouse_key').getAttribute('ovalue');
    Dom.get('shelf_type_name').value=Dom.get('shelf_type_name').getAttribute('ovalue');
    Dom.get('shelf_type_description').innerHTML=Dom.get('shelf_type_description').getAttribute('ovalue');
    Dom.get('shelf_type_rows').value=Dom.get('shelf_type_rows').getAttribute('ovalue');
    Dom.get('shelf_type_columns').value=Dom.get('shelf_type_columns').getAttribute('ovalue');
    Dom.get('shelf_type_deep').value=Dom.get('shelf_type_deep').getAttribute('ovalue');
    Dom.get('shelf_type_height').value=Dom.get('shelf_type_height').getAttribute('ovalue');
    Dom.get('shelf_type_length').value=Dom.get('shelf_type_length').getAttribute('ovalue');
    Dom.get('shelf_type_weight').value=Dom.get('shelf_type_weight').getAttribute('ovalue');
    Dom.get('shelf_type_volume').value=Dom.get('shelf_type_volume').getAttribute('ovalue');
    Dom.get('shelf_type_type').value=Dom.get('shelf_type_type').getAttribute('ovalue');
    
    
    swap_this_radio(Dom.get('radio_shelf_type_'+Dom.get('shelf_type_type').getAttribute('ovalue')))

}

function add_shelf_type(){
    get_shelf_type_data();
  
    var json_value = YAHOO.lang.JSON.stringify(shelf_type_data);
    var request='ar_edit_warehouse.php?tipo=new_shelf_type&values=' + encodeURIComponent(json_value); 
    //alert(request);
    // return;
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText);
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.action=='created'){
		    reset_shelf_type_data();
		    var table=tables['table3']
		    var datasource=tables['dataSource3'];
		    
		    datasource.sendRequest('',table.onDataReturnInitializeTable, table);      
		}else{
		    Dom.get('new_warehouse_shelf_type_block').innerHTML=r.msg;
		}
			    

			
	    }
	});
}




YAHOO.util.Event.onContentReady("shelf_warehouse_area", function () {

	var oShelfDS = new YAHOO.util.XHRDataSource("ar_warehouse.php");
	 oShelfDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
	 oShelfDS.responseSchema = {
	     resultsList : "data",
	fields : ["key","code","name"]
    };
 	var oShelfAC = new YAHOO.widget.AutoComplete("shelf_warehouse_area", "shelf_warehouse_area_Container", oShelfDS);
 	
 	oShelfAC.generateRequest = function(sQuery) {
	    //alert("?tipo=find_warehouse_area&parent_key="+Dom.get('shelf_warehouse_area_key').value+"&query=" + sQuery)
 	    return "?tipo=find_warehouse_area&parent_key="+Dom.get('shelf_warehouse_area_key').value+"&query=" + sQuery ;
 	};
	oShelfAC.forceSelection = true; 
	
	oShelfAC.itemSelectEvent.subscribe(validate_shelf_warehouse_area); 
	
	oShelfAC.formatResult = function(oResultData, sQuery, sResultMatch) {
	    return oResultData[1]+' ['+oResultData[2]+']'
	};
    });

YAHOO.util.Event.onContentReady("shelf_shelf_type", function () {

 var oDS = new YAHOO.util.XHRDataSource("ar_warehouse.php");
 	oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
 	oDS.responseSchema = {
 	    resultsList : "data",
 	    fields : ["key","name","description","type","rows","columns","l_height","l_length",'l_deep','l_weight','l_volume','info']
 	};
 	var oAC = new YAHOO.widget.AutoComplete("shelf_shelf_type", "shelf_shelf_type_Container", oDS);
 	
 	oAC.generateRequest = function(sQuery) {
 	    return "?tipo=find_shelf_type&query=" + sQuery ;
 	};
	oAC.forceSelection = true; 

		oAC.itemSelectEvent.subscribe(validate_shelf_type); 

    oAC.formatResult = function(oResultData, sQuery, sResultMatch) {
	  return oResultData[1]+' ['+oResultData[3]+']'
	};

  });
// function init_here(){


// }

//YAHOO.util.Event.onDOMReady(init_here);
