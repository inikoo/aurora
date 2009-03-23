  var CellEdit = function (callback, newValue) {
		var record = this.getRecord(),
		column = this.getColumn(),
		oldValue = this.value,
		datatable = this.getDataTable();

		YAHOO.util.Connect.asyncRequest(
						'POST',
						'ar_edit.php', {
						    success:function(o) {
							// alert(o.responseText);
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
						'tipo=edit_'+column.object+'&key=' + column.key + '&newvalue=' + 
						encodeURIComponent(newValue) + '&oldvalue=' + encodeURIComponent(oldValue)+ 
						myBuildUrl(datatable,record)
						
						);  
	    };
	    var onCellClick = function(oArgs) {
		var target = oArgs.target,
		column = this.getColumn(target),
		record = this.getRecord(target);
		switch (column.action) {
		case 'delete':
		    if (confirm('Are you sure?')) {

			YAHOO.util.Connect.asyncRequest(
							'GET',
							'ar_edit.php?tipo=delete_'+column.object + myBuildUrl(this,record),
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
		    break;
		default:

		    this.onEventShowCellEditor(oArgs);
		    break;
		}
	    };