(function() {

   var lang = YAHOO.lang, Dom = YAHOO.util.Dom, Event = YAHOO.util.Event;


/**
 * Editable datatable using inputEx fields in a dialog
 * @class inputEx.widget.dtDialogEdit
 * @extends inputEx.widget.DataTable
 * @constructor
 * @param {Object} options Options
 */
inputEx.widget.dtDialogEdit = function(options) {
   inputEx.widget.dtDialogEdit.superclass.constructor.call(this, options);
};

lang.extend(inputEx.widget.dtDialogEdit, inputEx.widget.DataTable , {
   
   /**
    * Additional options
    */
   setOptions: function(options) {
      
      inputEx.widget.dtDialogEdit.superclass.setOptions.call(this, options);
      
      this.options.dialogLabel = options.dialogLabel || "";
   },
   
   
   initEditor: function() {
      // Lazy loading of the dialog
   },
   
   /**
    * Make the datatable inplace editable with inputEx fields
    */
   renderDialog: function() {
      
      var that = this;
      
     this.dialog = new inputEx.widget.Dialog({
				inputExDef: {
				         type: 'form',
				         inputParams: {
				            fields: this.options.fields,
				            buttons: [
				               {type: 'button', value: 'Insert', onClick: function() { that.onDialogInsert();} },
				               {type: 'button', value: 'Cancel', onClick: function() { that.dialog.hide(); } }
				            ]
				         }
				      },
				title: this.options.dialogLabel,
				panelConfig: {
					constraintoviewport: true, 
					underlay:"shadow", 
					close:true, 
					fixedcenter: true,
					visible:true, 
					draggable:true,
					modal: true
				}
		});
		
      
   },
   
   
   onClickModify: function(rowIndex) {
      
      if(!this.dialog) {
         this.renderDialog();
      }
      
      var record = this.datatable.getRecord(rowIndex);
      
      this.dialog.getForm().setValue(record.getData());
      
      this.dialog.show();
   },
   
   
   onInsertButton: function(e) {
      
      if(!this.dialog) {
         this.renderDialog();
      }
      
      this.dialog.getForm().clear();
      
      this.dialog.show();
   },
   
   
   onDialogInsert: function() {
     
     var value = this.dialog.getForm().getValue();
      
      console.log(value);
      
      this.dialog.hide();
   }
   
   
});


})();