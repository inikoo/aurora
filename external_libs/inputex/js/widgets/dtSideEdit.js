(function() {

   var lang = YAHOO.lang, Dom = YAHOO.util.Dom, Event = YAHOO.util.Event;

/**
 * Editable datatable using inputEx fields in a side editor
 * @class inputEx.widget.dtSideEdit
 * @extends inputEx.widget.DataTable
 * @constructor
 * @param {Object} options Options
 */
inputEx.widget.dtSideEdit = function(options) {
   inputEx.widget.dtSideEdit.superclass.constructor.call(this, options);
};

lang.extend(inputEx.widget.dtSideEdit, inputEx.widget.DataTable , {
   
   /**
    * Create an inputEx form next to the datatable.
    */
   initEditor: function() {
      
      var tbl = this.datatable;
      
      // Subscribe to events for row selection 
      tbl.subscribe("rowMouseoverEvent", tbl.onEventHighlightRow); 
      tbl.subscribe("rowMouseoutEvent", tbl.onEventUnhighlightRow); 
      tbl.subscribe("rowClickEvent", tbl.onEventSelectRow); 
   
      // Listener for row selection
      tbl.subscribe("rowSelectEvent", this.onEventSelectRow, this, true); 
   
      // Form container
      this.formContainer = inputEx.cn('div', {className: "inputEx-DataTable-formContainer"}, null, "&nbsp;");
      this.options.parentEl.appendChild(this.formContainer);
   
      // Build the form
      var that = this;
      this.subForm = new inputEx.Form({
         parentEl: this.formContainer,
         fields: this.options.fields,
         legend: this.options.legend,
         buttons: [ 
            { type: 'submit', onClick: function(e) {that.onSaveForm(e); }, value: inputEx.messages.saveText},
            { type: 'button', onClick: function(e) {that.onCancelForm(e);}, value: inputEx.messages.cancelText}
         ]
      });
   
      // Programmatically select the first row 
      tbl.selectRow(tbl.getTrEl(0));
   
      // Programmatically bring focus to the instance so arrow selection works immediately 
      tbl.focus(); 
   
      // Positionning
      var dt = tbl.get('element');
      Dom.setStyle(dt, "float", "left");
      
      // Hiding subform
      this.hideSubform();
      
      // Add class to style the popup
      Dom.addClass(this.subForm.divEl, "inputEx-DataTable-formWrapper");
      
      this.options.parentEl.appendChild(inputEx.cn('div', null, {"clear":"both"}));
   },
   
   onCellClick: function(ev, rowIndex) {
      this.deplaceSubForm(rowIndex);
   },
   
   /**
    * Hide the form
    */
   hideSubform: function() {
      Dom.setStyle(this.formContainer, "display", "none");
   },
   
   /**
    * Show the form
    */
   showSubform: function(rowIndex) {
       Dom.setStyle(this.formContainer, "display", "");
       this.deplaceSubForm(rowIndex);
       this.subForm.focus();
   },
   
   /**
    * Deplace the form
    */  
   deplaceSubForm: function(rowIndex) {
       var columnSet = this.datatable.getColumnSet();
       // Hack : it seems that the getTdEl function add a bug for rowIndex == 0
       if ( rowIndex == 0 ) {
           var tableFirstRow = this.datatable.getFirstTrEl();
           Dom.setY(this.formContainer,Dom.getY(tableFirstRow) - 18);
       } else {
           var column = columnSet.keys[columnSet.keys.length-1];           
           var cell = this.datatable.getTdEl({column: column, record: rowIndex});
           Dom.setY(this.formContainer,Dom.getY(cell) - 18);
       }
   },
   
   
   /**
    * Set the subForm value when a row is selected
    */
   onEventSelectRow: function(args) {
      
      if(this.editingNewRecord && this.selectedRecord != args.record) {
         this.removeUnsavedRecord();
         this.editingNewRecord = false;
      }
      
      this.selectedRecord = args.record;
      this.subForm.setValue(this.selectedRecord.getData());
   },
   
   /**
    * Save the form value in the dataset
    */
   onSaveForm: function(e) {
      // Prevent submitting the form
      Event.stopEvent(e);
      
      // Update the record
      var newvalues = this.subForm.getValue();       
      this.datatable.updateRow( this.selectedRecord , newvalues ); 
      
      // Get reference to last updated record ( record._nCount is maximum, since record updated last !)
      //  (this.selectedRecord no longer points to updated record !!!)
      var records = this.datatable.getRecordSet().getRecords();
      
      for (var i=records.length-1; i>-1; i--) {
         if (records[i].getCount() > this.selectedRecord.getCount()) {
            this.selectedRecord = records[i];
         }
      }
      
      // Hide the subForm
      this.hideSubform();
      
      if(this.editingNewRecord) {
         // Fire the modify event
         this.itemAddedEvt.fire(this.selectedRecord);
         this.editingNewRecord = false;
      }
      else {
         // Fire the modify event   
         this.itemModifiedEvt.fire(this.selectedRecord);
      }
      
   },
   
   /**
    * Called when the user clicked on modify button
    */
   onClickModify: function(rowIndex) {   
      // make the form appear
      this.showSubform(rowIndex); 
   },
   
   /**
    * Insert button event handler
    */
   onInsertButton: function(e) {
      
      var tbl = this.datatable;
      
      // Insert a new row
      tbl.addRow({});
      
      // Select the new row
      tbl.unselectRow(this.selectedRecord);
      var rs = tbl.getRecordSet();
      var row = tbl.getTrEl(rs.getLength()-1);
      tbl.selectRow(row);
      
      
      this.editingNewRecord = true;
      this.showSubform(rs.getLength()-1);
   }
   
});


})();