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






var clear_actions = function(action){
    if(action=='')
	return
    Dom.get('manage_stock_messages').innerHTML='';
    Dom.get('manage_stock_engine').innerHTML='';
    Dom.get('manage_stock_desktop').style.display='none';
    
    
    var i=0;
    for (i=0;i<location_data.physical_locations;i++)
	{
	    Event.removeListener("loc_name"+location_data.locations[i].id,'click');
	    Dom.get("loc_name"+location_data.locations[i].id).className='';
	}

    Dom.get(action).className='';


    
}

// New Location
	var new_location_engine=function(e,index){


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
	    myCell.innerHTML = '<input type="text" size="3" id="new_location_qty" value="" /> ('+location_data.locations[index].stock+' max)';
	    myRow.appendChild(myCell);
	    var myCell = document.createElement("td");
	    myCell.innerHTML = '<span>Now</span>';
	    myRow.appendChild(myCell);
	    var myCell = document.createElement("td");
	    myCell.innerHTML = '<img src="art/icons/disk.png"  onclick="save_new_location('+index+')"  alt="<?=_('save')?>" title="<?=_('Save')?>" />';
	    myRow.appendChild(myCell);
	    var myCell = document.createElement("td");
	    myCell.innerHTML = '<img src="art/icons/bin.png" onclick="new_location()" alt="<?=_('cancel')?>" title="<?=_('Cancel')?>" />';
	    myRow.appendChild(myCell);
	    var myRow = document.createElement("tr");
	    myTbody.appendChild(myRow);
	    var myCell = document.createElement("td");
	    myCell.innerHTML = '<?=_('Coment')?>'+':';
	    myRow.appendChild(myCell);
	    var myCell = document.createElement("td");
	    myCell.innerHTML = '<textarea id="new_location_commet"></textarea>';
	    myRow.appendChild(myCell);


 	    Dom.get('manage_stock_engine').appendChild(myTable);

	    
	    
	}
	var save_new_location = function(index){
	    var qty=1*Dom.get("new_location_qty").value;
	    var max=location_data.locations[index].stock;
	    if(YAHOO.lang.isNumber(qty)){
		if(qty<=0)
		    Dom.get('manage_stock_messages').innerHTML='<?=_('Outers quantity should be bigger than zero')?>';
		else{
		    change=Dom.get("loc_stock"+location_data.locations[index].id).innerHTML-qty;
		    Dom.get("loc_stock"+location_data.locations[index].id).innerHTML=qty;
		    Dom.get("total_stock").innerHTML=1*Dom.get("total_stock").innerHTML-xchange;
		    clear_actions('new_location');
		    
		}
	    }else
		Dom.get('manage_stock_messages').innerHTML='<?=_('Outers quantity should be numeric')?>';	
		    



	}
	var new_location=function(){
	    clear_actions(current_engine);
	    current_engine='new_location';
	    this.className='selected';
	    Dom.get('manage_stock_desktop').style.display='';
	    Dom.get('manage_stock_engine').innerHTML=''
	    Dom.get('manage_stock_messages').innerHTML='<span style="float:right;cursor:pointer" onclick="clear_actions(\'new_location\');"><?=_('Close')?></span> <?=_('Location');?>: <input id="xnew_location_input" type="text"><div id="xnew_location_container"></div>';
	    
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
	    Dom.get('manage_stock_messages').innerHTML='<span style="float:right;cursor:pointer" onclick="clear_actions(\'manage_stock\');"><?=_('Close')?></span> <?=_('Click the location where you want to change the stock ');?>';
	    var i=0;
	    for (i=0;i<location_data.physical_locations;i++)
		{
		    if(location_data.locations[i].stock>0){
			Dom.get("loc_name"+location_data.locations[i].id).className='selected';
			Event.addListener("loc_name"+location_data.locations[i].id, "click", change_stock_engine,i);
		    }
		}
	}
// Damaged Stock
	
	var damaged_stock_engine=function(e,index){


	    for (i=0;i<location_data.physical_locations;i++)
		{
		    if(location_data.locations[i].stock>0){
			Dom.get("loc_name"+location_data.locations[i].id).className='';
			Event.removeListener("loc_name"+location_data.locations[i].id);
		    }
		}
	    
	    Dom.get('manage_stock_messages').innerHTML='<?=_('Indicate the number of outers damaged on')?> '+location_data.locations[index].name;
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
	    myCell.innerHTML = '<input type="text" size="3" id="damaged_stock_qty" value="" /> ('+location_data.locations[index].stock+' max)';
	    myRow.appendChild(myCell);
	    var myCell = document.createElement("td");
	    myCell.innerHTML = '<span>Now</span>';
	    myRow.appendChild(myCell);
	    var myCell = document.createElement("td");
	    myCell.innerHTML = '<img src="art/icons/disk.png"  onclick="save_damaged_stock('+index+')"  alt="<?=_('save')?>" title="<?=_('Save')?>" />';
	    myRow.appendChild(myCell);
	    var myCell = document.createElement("td");
	    myCell.innerHTML = '<img src="art/icons/bin.png" onclick="damaged_stock()" alt="<?=_('cancel')?>" title="<?=_('Cancel')?>" />';
	    myRow.appendChild(myCell);
	    var myRow = document.createElement("tr");
	    myTbody.appendChild(myRow);
	    var myCell = document.createElement("td");
	    myCell.innerHTML = '<?=_('Coment')?>'+':';
	    myRow.appendChild(myCell);
	    var myCell = document.createElement("td");
	    myCell.innerHTML = '<textarea id="damaged_stock_commet"></textarea>';
	    myRow.appendChild(myCell);


 	    Dom.get('manage_stock_engine').appendChild(myTable);

	    
	    
	}
	var save_damaged_stock = function(index){
	    var qty=1*Dom.get("damaged_stock_qty").value;
	    var max=location_data.locations[index].stock;
	    if(YAHOO.lang.isNumber(qty)){
		if(qty<=0)
		    Dom.get('manage_stock_messages').innerHTML='<?=_('Outers quantity should be bigger than zero')?>';
		else if(qty>max){
		    Dom.get('manage_stock_messages').innerHTML='<?=_('The number of  outers damaged can not be greater than the stock on the location')?>';

		}else{
		    
		    Dom.get("loc_stock"+location_data.locations[index].id).innerHTML=Dom.get("loc_stock"+location_data.locations[index].id).innerHTML-qty;
		    Dom.get("total_stock").innerHTML=Dom.get("total_stock").innerHTML-qty;
		    clear_actions('damaged_stock');
		    
		}
	    }else
		Dom.get('manage_stock_messages').innerHTML='<?=_('Outers quantity should be numeric')?>';	
		    



	}
	var damaged_stock=function(){
	    clear_actions(current_engine);
	    current_engine='damaged_stock';
	    this.className='selected';
	    Dom.get('manage_stock_desktop').style.display='';
	    Dom.get('manage_stock_engine').innerHTML=''
	    Dom.get('manage_stock_messages').innerHTML='<span style="float:right;cursor:pointer" onclick="clear_actions(\'manage_stock\');"><?=_('Close')?></span> <?=_('Click the location were the stock was damaged ');?>';
	    var i=0;
	    for (i=0;i<location_data.physical_locations;i++)
		{
		    if(location_data.locations[i].stock>0){
			Dom.get("loc_name"+location_data.locations[i].id).className='selected';
			Event.addListener("loc_name"+location_data.locations[i].id, "click", damaged_stock_engine,i);
		    }
		}
	}
	    
	// MOVE STOCK -----------------------------------------------------------------------------------------

   var move_stock =function(){
   clear_actions(current_engine);
	    current_engine='move_stock';
	    this.className='selected';
	    Dom.get('manage_stock_desktop').style.display='';
	    Dom.get('manage_stock_engine').innerHTML=''
	    Dom.get('manage_stock_messages').innerHTML='<span style="float:right;cursor:pointer" onclick="clear_actions(\'move_stock\');"><?=_('Close')?></span> <?=_('Click the location from were the stock was moved');?>';
	    var num_locations=location_data.physical_locations;
	    if(num_locations==2){
		if(location_data.locations[0].stock>0){
		    Event.addListener("loc_name"+location_data.locations[0].id, "click", move_stock_duo,0);
		    Dom.get("loc_name"+location_data.locations[0].id).className='selected';
		}
		if(location_data.locations[1].stock>0){
		    Event.addListener("loc_name"+location_data.locations[1].id, "click", move_stock_duo,1);
		    Dom.get("loc_name"+location_data.locations[1].id).className='selected';
		}
	    }else if(num_locations>2){
	    var i=0;
	    for (i=0;i<location_data.physical_locations;i++)
		{
		    //    Event.addListener("move_stock", "click", move_stock);
		    //location_data.locations[i].id
		}
	    }
	    
	}

	var save_move_stock = function(from_index,to_index){
	    var qty=1*Dom.get("move_stock_qty").value;
	    if(YAHOO.lang.isNumber(qty)){
		if(qty<=0)
		    Dom.get('manage_stock_messages').innerHTML='<?=_('Outers quantity should be bigger than zero')?>';
		else{
		    Dom.get("loc_stock"+location_data.locations[from_index].id).innerHTML=Dom.get("loc_stock"+location_data.locations[from_index].id).innerHTML-qty;
		    Dom.get("loc_stock"+location_data.locations[to_index].id).innerHTML=Dom.get("loc_stock"+location_data.locations[to_index].id).innerHTML+qty;
		    clear_actions('move_stock');
		}
	    }else
		Dom.get('manage_stock_messages').innerHTML='<?=_('Outers quantity should be numeric')?>';	
		    

	    var max=location_data.locations[from_index].stock;

	}



	var move_stock_duo=function(e,from_index){


	    Event.removeListener("loc_name"+location_data.locations[0].id, "click" );
	    Event.removeListener("loc_name"+location_data.locations[1].id, "click" );
	    Dom.get("loc_name"+location_data.locations[0].id).className='';
	    Dom.get("loc_name"+location_data.locations[1].id).className='';





	    if(from_index==0)
		to_index=1;
	    else
		to_index=0;
	    Dom.get('manage_stock_messages').innerHTML='<?=_('Indicate the number of outers moved')?>'
	    var myTable = document.createElement("table");
	    myTable.className='location_state';
	    var myThead = document.createElement("thead");
	    myThead.style.height = '0px';
	    var myTfoot = document.createElement("tfoot");
	    myTfoot.style.height = '0px';
	    var myTbody = document.createElement("tbody");
	    var myRow = document.createElement("tr");
	    var myCell = document.createElement("td");
	    myCell.innerHTML = location_data.locations[from_index].name+' &rarr; '+location_data.locations[to_index].name;
	    myTable.appendChild(myThead);
	    myTable.appendChild(myTfoot);
	    myTable.appendChild(myTbody);
	    myTbody.appendChild(myRow);
	    myRow.appendChild(myCell);
	    var myCell = document.createElement("td");
	    myCell.innerHTML = '<?=_('Outers')?>: <input type="text" size="3" id="move_stock_qty" value="" /> ('+location_data.locations[from_index].stock+' max)';
	    myRow.appendChild(myCell);
	    var myCell = document.createElement("td");
	    myCell.innerHTML = '<span>Now</span>';
	    myRow.appendChild(myCell);
	    var myCell = document.createElement("td");
	    myCell.innerHTML = '<img src="art/icons/disk.png"  onclick="save_move_stock('+from_index+','+to_index+')"  alt="<?=_('save')?>" title="<?=_('Save')?>" />';
	    myRow.appendChild(myCell);
	    var myCell = document.createElement("td");
	    myCell.innerHTML = '<img src="art/icons/bin.png" onclick="move_stock()" alt="<?=_('cancel')?>" title="<?=_('Cancel')?>" />';

	    myRow.appendChild(myCell);


 	    Dom.get('manage_stock_engine').appendChild(myTable);
	    


	}




	//YAHOO.util.Event.onContentReady("new_location_input", function () {

RemoteCustomRequest = function() {
	    
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
 	    return "?tipo=locations&query=" + sQuery ;
 	};

 	return {oDS: oDS,oAC: oAC};
}
	
	//    });


YAHOO.util.Event.onContentReady("manage_stock", function () {

	
	 Event.addListener("damaged_stock", "click", damaged_stock);
	 Event.addListener("move_stock", "click", move_stock);
	 Event.addListener("change_stock", "click", change_stock);
	 Event.addListener("new_location", "click", new_location);

    });
