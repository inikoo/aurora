<?include_once('../common.php')?>
    var Dom   = YAHOO.util.Dom;
var Event = YAHOO.util.Event;
current_engine='';

jsonString='<?=$_SESSION['state']['product']['manage_stock_data'];?>';
try {
    var location_data = YAHOO.lang.JSON.parse(jsonString);
}
catch (e) {
    alert("ERROR:P_PMS_JSONDATA");
};


function exchange(i, j, tableID)
{
	var table = document.getElementByID('tableID');
	var trs = oTable.tBodies[0].getElementsByTagName("tr");
	
	if(i == j+1) {
		oTable.tBodies[0].insertBefore(trs[i], trs[j]);
	} else if(j == i+1) {
		oTable.tBodies[0].insertBefore(trs[j], trs[i]);
	} else {
		var tmpNode = oTable.tBodies[0].replaceChild(trs[i], trs[j]);
		if(typeof(trs[i]) != "undefined") {
			oTable.tBodies[0].insertBefore(tmpNode, trs[i]);
		} else {
			oTable.appendChild(tmpNode);
		}
	}
}



var refresh= function(){
    

  for (key in location_data.data)
      {
	  var location= location_data.data[key];
	  id= location.location_id;
	  

	  Dom.get("loc_name"+id).innerHTML=location.name;
	  Dom.get("loc_stock"+id).innerHTML=location.stock;

	  if(location.stock==0)
	      Dom.get("loc_del"+id).style.display='';
	  else
	       Dom.get("loc_del"+id).style.display='none';
      }

}


var location_selected= function(){

    if(current_engine=='new_location'){

	Dom.get("new_location_q1").style.display='';
    }
    
}

var clear_actions = function(){

    Dom.get('manage_stock_messages').innerHTML='';
    Dom.get('manage_stock_engine').innerHTML='';
    Dom.get('manage_stock_desktop').style.display='none';
    Dom.get('manage_stock_locations').style.display='none';
    for (key in location_data.data)
	{
	    var location= location_data.data[key];
	    Event.removeListener("loc_name"+location_data.data[key].location_id, "click");
	    Dom.get("loc_name"+location_data.data[key].location_id).className='';
	}
    

    Dom.get('move_stock').className='';
    Dom.get('damaged_stock').className='';


    
};

    
var new_location_save = function(){
    //alert('hola');
    var location_name=Dom.get("new_location_input").value;

    if(location_name=='')
	Dom.get('manage_stock_messages').innerHTML='<?=_('Select a location from the list')?>';
    var can_pick=Dom.get('new_location_can_pick').checked;
    var is_primary=Dom.get('new_location_is_primary').checked;

    var request='ar_assets.php?tipo=pml_new_location&location_name='+ escape(location_name)+'&can_pick='+escape(can_pick)+'&is_primary='+escape(is_primary);
    //    alert(request);
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		    //update all stock figures (if were changed else were)
		    location_data=r.data;
		    clear_actions('damaged_stock')
			refresh();
		    
		}else
		    Dom.get('manage_stock_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	});
	};


var new_location_q1_action=function(value){
		if(value==1){
		    Dom.get('new_location_q2').style.display='';
		    Dom.get('new_location_save').style.display=''
		}else{
		    	Dom.get('new_location_save').style.display=''
		}
	    }
	    
	var new_location=function(){
	    clear_actions(current_engine);
	    
	    current_engine='new_location';
	    this.className='selected';
	    Dom.get('manage_stock_desktop').style.display='';
	    Dom.get('manage_stock_locations').style.display='';
	    Dom.get('manage_stock_engine').innerHTML='<table><tr id="new_location_q1" style="display:none;height:30px"><td><?=_('Can products be picked from here?')?></td><td>  <?=_('Yes')?> <input type="radio" onClick="new_location_q1_action(1)" name="can_pick" id="new_location_can_pick" value="yes" style="vertical-align:bottom"> </td><td> <?=_('No')?><input style="vertical-align:bottom" onClick="new_location_q1_action(0)" type="radio" name="can_pick"  value="no"></td></tr><tr  id="new_location_q2" style="display:none;height:30px" ><td><?=_('Is this the primary picking location?')?> </td><td><?=_('Yes')?> <input id="new_location_is_primary" type="radio" name="primary" value="yes" style="vertical-align:bottom"></td><td>  No<input style="vertical-align:bottom" type="radio" name="primary" checked="checked" value="no"> </td></tr><tr  id="new_location_save" style="display:none;height:30px"  ><td colspan="3" style="cursor:pointer" onclick="new_location_save()" class="aright"><?=_('Save changes')?> <img src="art/icons/disk.png"/></td></tr></table> '
	    Dom.get('manage_stock_messages').innerHTML='<?=_('New location code');?>:';
	    
	}

// Change stock
	var change_stock_engine=function(e,index){


	    for (i=0;i<location_data.physical_locations;i++)
		{
		    if(location_data.locations[i].stock>0){
			Dom.get("loc_name"+location_data.locations[i].id).className='';
			Event.removeListener("loc_name"+location_data.locations[i].id);
		    }
		}
	    
	    Dom.get('manage_stock_messages').innerHTML='<?=_('Indicate the new number of outers on')?> '+location_data.locations[index].name;
	    var myTable = document.createElement("table");
	    myTable.className='location_state';
	    var myTbody = document.createElement("tbody");
	    var myRow = document.createElement("tr");
	    myTable.appendChild(myTbody);
	    myTbody.appendChild(myRow);
	    var myCell = document.createElement("td");
	    myCell.innerHTML = '<?=_('Outers')?>:';
	    myRow.appendChild(myCell);
	    var myCell = document.createElement("td");
	    myCell.innerHTML = '<input type="text" size="3" id="change_stock_qty" value="" /> ('+location_data.locations[index].stock+' max)';
	    myRow.appendChild(myCell);
	    var myCell = document.createElement("td");
	    myCell.innerHTML = '<span>Now</span>';
	    myRow.appendChild(myCell);
	    var myCell = document.createElement("td");
	    myCell.innerHTML = '<img src="art/icons/disk.png"  onclick="save_change_stock('+index+')"  alt="<?=_('save')?>" title="<?=_('Save')?>" />';
	    myRow.appendChild(myCell);
	    var myCell = document.createElement("td");
	    myCell.innerHTML = '<img src="art/icons/bin.png" onclick="change_stock()" alt="<?=_('cancel')?>" title="<?=_('Cancel')?>" />';
	    myRow.appendChild(myCell);
	    var myRow = document.createElement("tr");
	    myTbody.appendChild(myRow);
	    var myCell = document.createElement("td");
	    myCell.innerHTML = '<?=_('Coment')?>'+':';
	    myRow.appendChild(myCell);
	    var myCell = document.createElement("td");
	    myCell.innerHTML = '<textarea id="change_stock_commet"></textarea>';
	    myRow.appendChild(myCell);


 	    Dom.get('manage_stock_engine').appendChild(myTable);

	    
	    
	}
	var save_change_stock = function(index){
	    var qty=1*Dom.get("change_stock_qty").value;
	    var max=location_data.locations[index].stock;
	    if(YAHOO.lang.isNumber(qty)){
		if(qty<=0)
		    Dom.get('manage_stock_messages').innerHTML='<?=_('Outers quantity should be bigger than zero')?>';
		else{
		    change=Dom.get("loc_stock"+location_data.locations[index].id).innerHTML-qty;
		    Dom.get("loc_stock"+location_data.locations[index].id).innerHTML=qty;
		    Dom.get("total_stock").innerHTML=1*Dom.get("total_stock").innerHTML-xchange;
		    clear_actions('change_stock');
		    
		}
	    }else
		Dom.get('manage_stock_messages').innerHTML='<?=_('Outers quantity should be numeric')?>';	
		    



	}
	var change_stock=function(){
	    clear_actions(current_engine);
	    current_engine='change_stock';
	    this.className='selected';
	    Dom.get('manage_stock_desktop').style.display='';
	    Dom.get('manage_stock_engine').innerHTML=''
	    Dom.get('manage_stock_messages').innerHTML='<?=_('Click the location where you want to change the stock ');?>';
	    var i=0;
	    for (i=0;i<location_data.physical_locations;i++)
		{
		    if(location_data.locations[i].stock>0){
			Dom.get("loc_name"+location_data.locations[i].id).className='selected';
			Event.addListener("loc_name"+location_data.locations[i].id, "click", change_stock_engine,i);
		    }
		}
	};
// Damaged Stock
	
var damaged_stock_save = function(index){
    var qty=Dom.get("damaged_stock_qty").value;
    var location_id=Dom.get('damaged_stock_qty').getAttribute('location_id');
    var message=Dom.get('damaged_stock_why').value;
    var request='ar_assets.php?tipo=pml_damaged_stock&from='+ escape(location_id)+'&qty='+escape(qty)+'&message='+escape(message);
 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		    //update all stock figures (if were changed else were)
		    location_data=r.data;
		    clear_actions('damaged_stock')
			refresh();
		    
		}else
		    Dom.get('manage_stock_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	});
};

var damaged_stock=function(){
    clear_actions(current_engine);
    current_engine='damaged_stock';
    this.className='selected';
    Dom.get('manage_stock_desktop').style.display='';
    Dom.get('manage_stock_engine').innerHTML=''
    Dom.get('manage_stock_messages').innerHTML='<?=_('Click the location were the stock was damaged ');?>';
    for (key in location_data.data)
	{
	    var location= location_data.data[key];
	    if(location.is_physical && location.has_stock){

		Event.addListener("loc_name"+location_data.data[key].location_id, "click", damaged_stock_from,key);
		Dom.get("loc_name"+location_data.data[key].location_id).className='selected';
	    }
		
	}
};


var damaged_stock_ready=function(){
    var qty=1*Dom.get("damaged_stock_qty").value;
    var max=Dom.get('damaged_stock_qty').getAttribute('max');
    var ok1=false;
    var ok2=false;

    if(Dom.get('damaged_stock_why').value!='')
	ok1=true;
    else{
	Dom.get('manage_stock_messages').innerHTML='<?=_('Please, try to explain why the stock where damaged and what are you are going to do with it')?>';
    }
    if(YAHOO.lang.isNumber(qty)){
	if(qty<0){
	    Dom.get('manage_stock_messages').innerHTML='<?=_('Do not give me negative numbers please')?>';
	    
	}else if(qty==0){
	    Dom.get('manage_stock_messages').innerHTML='<?=_('How many outers were damaged?')?>';
	}else if(qty>max){
	    Dom.get('manage_stock_messages').innerHTML='<?=_('Only')?> '+max+' <?=_('outers in location')?>';
	    
	}else{
	    ok2=true;
	}
    }else if(qty==''){
	Dom.get('manage_stock_messages').innerHTML='<?=_('How many outers were damaged?')?>';
	
    }else{
	Dom.get('manage_stock_messages').innerHTML='<?=_('That is not a number, how many outers were damaged?')?>';	
	
    }
    


    if(ok1 && ok2){
	 Dom.get('manage_stock_messages').innerHTML='<?=_('Save the changes please')?>';
	 Dom.get('damaged_stock_save').style.display='';
    }else
	Dom.get('damaged_stock_save').style.display='none';

};

var damaged_stock_from=function(e,index){
    Dom.get('manage_stock_messages').innerHTML='<?=_('How many outer were damaged')?>'
    Dom.get('manage_stock_engine').style.display='';



     Dom.get('manage_stock_engine').innerHTML='<table><td><?=_('Outers damaged')?>: </td><td style="padding-right:20px" id="damaged_stock_td_qty"> <input id="damaged_stock_qty" location_id="'+location_data.data[index].id+'"  type="text" max="'+location_data.data[index].stock+'" size="3"  onkeyup="damaged_stock_ready()"   /> (<span style="cursor:pointer" onclick="damaged_stock_all();">'+location_data.data[index].stock+'</span> <?=_('max')?>)</td><td id="damaged_stock_save" onclick="damaged_stock_save()" style="cursor:pointer;display:none"><?=_('Save')?> <img src="art/icons/disk.png" style="vertical-align:bottom"/></td><tr><td><?=_('Comments')?>:</td><td colspan="2"><textarea id="damaged_stock_why" onkeyup="damaged_stock_ready()" ></textarea></td></tr></table>'

  for (key in location_data.data)
	{
	    var location= location_data.data[key];
	    Event.removeListener("loc_name"+location_data.data[key].location_id, "click");
	    Dom.get("loc_name"+location_data.data[key].location_id).className='';
	}


}

var damaged_stock_all=function(index){
    Dom.get('damaged_stock_qty').value=Dom.get('damaged_stock_qty').getAttribute('max');
    damaged_stock_ready();
};

// MOVE STOCK -----------------------------------------------------------------------------------------
var move_stock =function(){
    clear_actions();
    current_engine='move_stock';
    this.className='selected';
    Dom.get('manage_stock_desktop').style.display='';
    Dom.get('manage_stock_engine').innerHTML=''
       Dom.get('manage_stock_messages').innerHTML='<?=_('Click the location from were the stock was moved');?>';
    
    var can_from=location_data.num_physical-location_data.num_physical_with_stock;
    var can_to=location_data.num_physical-can_from;
    if(can_from==1 && can_to==1){
	for (key in location_data.data)
	    {
		var location= location_data.data[key];
		if(location.is_physical && location.has_stock)
		    from_index= key;
		if(location.is_physical && !location.has_stock)
		    to_index= key;
	       }
	   move_stock_from('',from_index);
	   move_stock_to('',to_index);
	   
    }else {
	
	for (key in location_data.data)
	    {
		   var location= location_data.data[key];
		   if(location.is_physical && location.has_stock){
		       Event.addListener("loc_name"+location_data.data[key].location_id, "click", move_stock_from,key);
		       Dom.get("loc_name"+location_data.data[key].location_id).className='selected';
		   }
	    }
       }
};

var move_stock_save = function(){
    var qty=Dom.get("move_stock_qty").value;
    var from_id=Dom.get('move_stock_from').getAttribute('location_id')
    var to_id=Dom.get('move_stock_to').getAttribute('location_id')
    var request='ar_assets.php?tipo=pml_move_stock&from='+ escape(from_id)+'&to='+escape(to_id)+'&qty='+escape(qty);
    
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if (r.state == 200) {
		    //update all stock figures (if were changed else were)
		    location_data=r.data;
		    clear_actions('move_stock')
			refresh();
		    
		}else
		    Dom.get('manage_stock_messages').innerHTML='<span class="error">'+r.msg+'</span>';
	    }
	});
    
};

var move_stock_from=function(e,index){
    Dom.get('manage_stock_messages').innerHTML='<?=_('Choose the location where you moved the stock')?>'
    Dom.get('manage_stock_engine').style.display='';
    Dom.get('manage_stock_engine').innerHTML='<table border=0><tr style="height:30px"><td style="vertical-align:bottom" class="location_name"  id="move_stock_from" location_id="'+location_data.data[index].id+'"  >'+location_data.data[index].name+'</td><td style="vertical-align:bottom" > &rarr; </td><td style="vertical-align:bottom" id="move_stock_to" class="location_name"><b>?</b></td><td style="padding-left:30px;padding-right:30px;display:none;vertical-align:bottom" id="move_stock_td_qty"> <?=_('Outers')?>:  <input style="vertical-align:bottom" id="move_stock_qty" type="text" max="'+location_data.data[index].stock+'" size="3"  onkeyup="move_stock_ready()" />  (<span style="cursor:pointer" onclick="move_stock_all();">'+location_data.data[index].stock+'</span> <?=_('max')?>)  </td><td id="move_stock_save" onclick="move_stock_save()" style="cursor:pointer;display:none;vertical-align:bottom"><?=_('Save')?> <img src="art/icons/disk.png" style="vertical-align:bottom"/></td></tr></table>'
    
    var can_to=location_data.num_physical;
    if(can_to==2){
	for (key in location_data.data)
	    {
		var location= location_data.data[key];
		if(location.is_physical && key!=index)
		    to_index= key;
	    }
		    move_stock_to('',to_index);
    }else {
	
	for (key in location_data.data)
	    {
		var location= location_data.data[key];
		Event.removeListener("loc_name"+location_data.data[key].location_id, "click");
		if(location.is_physical && key!=index  ){
		    Event.addListener("loc_name"+location_data.data[key].location_id, "click", move_stock_to,key);
		    Dom.get("loc_name"+location_data.data[key].location_id).className='selected';
		}else
		    Dom.get("loc_name"+location_data.data[key].location_id).className='';
	    }
    }
};


var move_stock_to=function(e,index){
    
    for (key in location_data.data)
	{
	    var location= location_data.data[key];
	    Event.removeListener("loc_name"+location_data.data[key].location_id, "click");
	    Dom.get("loc_name"+location_data.data[key].location_id).className='';
	}
    
    Dom.get('manage_stock_messages').innerHTML='<?=_('How many outers did you move?')?>'
    
    Dom.get('move_stock_to').innerHTML=location_data.data[index].name;
    Dom.get('move_stock_to').setAttribute('location_id',location_data.data[index].id);
    Dom.get('move_stock_td_qty').style.display='';
};	

var move_stock_ready=function(){
    var qty=1*Dom.get("move_stock_qty").value;
    var max=Dom.get('move_stock_qty').getAttribute('max');
    if(YAHOO.lang.isNumber(qty)){
	if(qty<0){
	    Dom.get('manage_stock_messages').innerHTML='<?=_('Do not give me negative numbers please')?>';
	    Dom.get('move_stock_save').style.display='none';
	}else if(qty==0){
	    Dom.get('manage_stock_messages').innerHTML='<?=_('How many outers did you move?')?>';
	    Dom.get('move_stock_save').style.display='none';
	}else if(qty>max){
	    Dom.get('manage_stock_messages').innerHTML='<?=_('You can not move more than')?> '+max+' <?=_('Outers')?>';
	    Dom.get('move_stock_save').style.display='none';
	    
	}else{
	    Dom.get('manage_stock_messages').innerHTML='<?=_('Save the changes please')?>';
	    Dom.get('move_stock_save').style.display='';
	}
		    }else if(qty==''){
	Dom.get('manage_stock_messages').innerHTML='<?=_('How many outers did you move?')?>';
	Dom.get('move_stock_save').style.display='none';
    }else{
	Dom.get('manage_stock_messages').innerHTML='<?=_('That is not a number, how many outers did you move?')?>';	
	Dom.get('move_stock_save').style.display='none';
    }
};

var move_stock_all=function(index){
    Dom.get('move_stock_qty').value=Dom.get('move_stock_qty').getAttribute('max');
    move_stock_ready();
};


YAHOO.util.Event.onContentReady("manage_stock_locations", function () {
	//function init(){
	

	// Use an XHRDataSource
	var oDS = new YAHOO.util.XHRDataSource("ar_assets.php");

 	oDS.responseType = YAHOO.util.XHRDataSource.TYPE_JSON;
 	oDS.responseSchema = {
 	    resultsList : "data",
 	    fields : ["name"]
 	};
	
// 	// Instantiate the AutoComplete
 	var oAC = new YAHOO.widget.AutoComplete("new_location_input", "new_location_container", oDS);

// 	// The webservice needs additional parameters
 	oAC.generateRequest = function(sQuery) {
 	    return "?tipo=locations_name&query=" + sQuery ;
 	};
	oAC.forceSelection = true; 
	


	
	oAC.itemSelectEvent.subscribe(location_selected); 
	


     //};	
	    });


//YAHOO.util.Event.onDOMReady(init);


YAHOO.util.Event.onContentReady("manage_stock", function () {

	
	 Event.addListener("damaged_stock", "click", damaged_stock);
	 Event.addListener("move_stock", "click", move_stock);
	 Event.addListener("change_stock", "click", change_stock);
	 Event.addListener("new_location", "click", new_location);

    });


