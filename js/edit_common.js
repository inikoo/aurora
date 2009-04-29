  var CellEdit = function (callback, newValue) {
		var record = this.getRecord(),
		column = this.getColumn(),
		oldValue = this.value,
		datatable = this.getDataTable();

		YAHOO.util.Connect.asyncRequest(
						'POST',
						'ar_edit_assets.php', {
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
							'ar_edit_assets.php?tipo=delete_'+column.object + myBuildUrl(this,record),
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
	    };    var highlightEditableCell = function(oArgs) {
		var target = oArgs.target;
		column = this.getColumn(target);

		switch (column.action) {
		case 'delete':
		    this.highlightRow(target);
		default:
		    if(YAHOO.util.Dom.hasClass(target, "yui-dt-editable")) {
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


