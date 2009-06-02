(function() {

   var lang = YAHOO.lang, Dom = YAHOO.util.Dom, Event = YAHOO.util.Event;


/**
 * InPlaceEditable datatable using inputEx fields
 * @class inputEx.widget.dtInPlaceEdit
 * @extends inputEx.widget.DataTable
 * @constructor
 * @param {Object} options Options
 */
inputEx.widget.dtInPlaceEdit = function(options) {
   inputEx.widget.dtInPlaceEdit.superclass.constructor.call(this, options);
};

lang.extend(inputEx.widget.dtInPlaceEdit, inputEx.widget.DataTable , {
   
   /**
    * Additional options
    */
   setOptions: function(options) {
     inputEx.widget.dtInPlaceEdit.superclass.setOptions.call(this, options);
     
     this.options.allowModify = false;
     this.options.editableFields = options.editableFields; 
   },
   
   /**
    * Make the datatable inplace editable with inputEx fields
    */
   initEditor: function() {
      
      // Set up editing flow
      var highlightEditableCell = function(oArgs) {
          var elCell = oArgs.target;
          if(YAHOO.util.Dom.hasClass(elCell, "yui-dt-editable")) {
              this.highlightCell(elCell);
          }
      };
      this.datatable.subscribe("cellMouseoverEvent", highlightEditableCell);
      this.datatable.subscribe("cellMouseoutEvent", this.datatable.onEventUnhighlightCell);
   },
   
   /**
    * Convert a single inputEx field definition to a DataTable column definition
    */
   fieldToColumndef: function(field) {
      var columnDef = {
         key: field.inputParams.name,
         label: field.inputParams.label,
         sortable: this.options.sortable, 
         resizeable: this.options.resizeable
      };

      // In cell editing if the field is listed in this.options.editableFields
      if(lang.isArray(this.options.editableFields) ) {
         if(inputEx.indexOf(field.inputParams.name, this.options.editableFields) != -1) {
             columnDef.editor = new inputEx.widget.InputExCellEditor(field);
         }
      }
      
      // Field formatter
      if(field.formatter) {
         columnDef.formatter = field.formatter;
      }
      else {
         if(field.type == "date") {
            columnDef.formatter = YAHOO.widget.DataTable.formatDate;
         }
      }
      // TODO: other formatters
      return columnDef;
   },
   
   onCellClick: function(ev, rowIndex) {
      this.datatable.onEventShowCellEditor(ev);
   },
   
});



/**
 * The InputExCellEditor class provides functionality for inline editing
 * using the inputEx field definition.
 *
 * @class InputExCellEditor
 * @extends YAHOO.widget.BaseCellEditor 
 * @constructor
 * @param {Object} inputExFieldDef InputEx field definition object
 */
inputEx.widget.InputExCellEditor = function(inputExFieldDef) {
    this._inputExFieldDef = inputExFieldDef;
   
    this._sId = "yui-textboxceditor" + YAHOO.widget.BaseCellEditor._nCount++;
    inputEx.widget.InputExCellEditor.superclass.constructor.call(this, "inputEx", {disableBtns:true});
};

// InputExCellEditor extends BaseCellEditor
lang.extend(inputEx.widget.InputExCellEditor, YAHOO.widget.BaseCellEditor,{

   /**
    * Render the inputEx field editor
    */
   renderForm : function() {
   
      // Build the inputEx field
      this._inputExField = inputEx(this._inputExFieldDef);
      this.getContainerEl().appendChild(this._inputExField.getEl());
   
      // Save the cell value at updatedEvt
      this._inputExField.updatedEvt.subscribe(function(e, args) {
         // Hack to NOT close the field at the first updatedEvt (fired when we set the value)
         if(this._updatedEvtForSetValue) {
            this._updatedEvtForSetValue = false;
            return;
         }
         this.save();
      }, this, true);
   
      if(this.disableBtns) {
         // By default this is no-op since enter saves by default
         this.handleDisabledBtns();
      }
   },

   /**
    * Hack to NOT close the field at the first updatedEvt (fired when we set the value)
    */
   show: function() {
      inputEx.widget.InputExCellEditor.superclass.show.call(this); 
      this._updatedEvtForSetValue = true;
   },

   /**
    * Resets InputExCellEditor UI to initial state.
    */
   resetForm : function() {
       this._inputExField.setValue(lang.isValue(this.value) ? this.value.toString() : "");
   },

   /**
    * Sets focus in InputExCellEditor.
    */
   focus : function() {
      this._inputExField.focus();
   },

   /**
    * Returns new value for InputExCellEditor.
    */
   getInputValue : function() {
      return this._inputExField.getValue();
   }

});

// Copy static members to InputExCellEditor class
lang.augmentObject(inputEx.widget.InputExCellEditor, YAHOO.widget.BaseCellEditor);

})();