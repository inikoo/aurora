

function swap_radio(){
    swap_this_radio(this);
}

function swap_this_radio(o){
    if(Dom.hasClass(o,'selected'))
	return;
    else{
	var parent=o.parentNode;
	elemets=Dom.getElementsByClassName('selected', 'span', parent);
	Dom.removeClass(elemets,'selected');
	Dom.addClass(o,'selected');
	Dom.get('shelf_type_type').value=o.getAttribute('radio_value');
    }
}



  var CellEdit = function (callback, newValue) {
      
    var record = this.getRecord(),
    column = this.getColumn(),
    oldValue = this.value,
    datatable = this.getDataTable();
    
    if(column.object=='company' || column.object=='customer' || column.object=='contact' )
	ar_file='ar_edit_contacts.php';
    else if(column.object=='warehouse_area' || column.object=='part_location'|| column.object=='shelf_type')
	ar_file='ar_edit_warehouse.php';
    else if(column.object=='user' )
	ar_file='ar_edit_users.php';
     else if(column.object=='new_order' )
	ar_file='ar_edit_orders.php';
    
    else
	ar_file='ar_edit_assets.php';
    //   alert(column.object)
    var request='tipo=edit_'+column.object+'&key=' + column.key + '&newvalue=' + encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue)+ myBuildUrl(datatable,record);
    //    alert('R:'+request);

    YAHOO.util.Connect.asyncRequest(
				    'POST',
				    ar_file, {
					success:function(o) {
					     alert(o.responseText);
					    var r = YAHOO.lang.JSON.parse(o.responseText);
					    if (r.state == 200) {
						
						callback(true, r.newvalue);
					    } else {
						alert(r.msg);
						callback();
					    }
					},
							failure:function(o) {
							alert(o.statusText);
							callback();
						    },
							scope:this
							},
						request
						
						);  
	    };
var onCellClick = function(oArgs) {
		var target = oArgs.target,
		column = this.getColumn(target),
		record = this.getRecord(target);
		switch (column.action) {
		case 'delete':
		    if(record.getData('delete')!=''){

		    if (confirm('Are you sure, you want to delete this row?')) {
			if(column.object=='company')
			    ar_file='ar_edit_contacts.php';
			else if(column.object=='warehouse_area' || column.object=='location')
			    ar_file='ar_edit_warehouse.php';
			else
			    ar_file='ar_edit_assets.php';



				alert(ar_file+'?tipo=delete_'+column.object + myBuildUrl(this,record))

			YAHOO.util.Connect.asyncRequest(
							'GET',
							ar_file+'?tipo=delete_'+column.object + myBuildUrl(this,record),
							{
							    success: function (o) {
								
								if (o.responseText == 'Ok') {
								    this.deleteRow(target);
								} else {
								    alert(o.responseText);
								}
							    },
								failure: function (o) {
								alert(o.statusText);
							    },
								scope:this
								}
							);
		    }
		    }
		    break;
		    
		default:
		    
		    this.onEventShowCellEditor(oArgs);
		    break;
		}
	    };   

var highlightEditableCell = function(oArgs) {

    var target = oArgs.target;
    column = this.getColumn(target);
    record = this.getRecord(target);
   

    switch (column.action) {
    case 'delete':
	//for(x in target)
	//  alert(x+' '+target[x])
	
	//alert(record.getData('delete'))
	if(record.getData('delete')!='')
	    this.highlightRow(target);
	break;
   
    default:
	
	if(YAHOO.util.Dom.hasClass(target, "yui-dt-editable") ) {
	    this.highlightCell(target);
	}
    }
};

var unhighlightEditableCell = function(oArgs) {
		var target = oArgs.target;
		column = this.getColumn(target);

		switch (column.action) {
		case 'delete':
		    this.unhighlightRow(target);
		default:
		    if(YAHOO.util.Dom.hasClass(target, "yui-dt-editable")) {
			this.unhighlightCell(target);
		    }
		}
	    };

function radio_changed(o){
    parent=o.parentNode;

    Dom.removeClass(parent.getAttribute('prefix')+parent.getAttribute('value'),'selected');
    Dom.addClass(o,'selected');
  

    parent.setAttribute('value',o.getAttribute('name'));
}

