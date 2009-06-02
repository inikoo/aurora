(function() {

   var lang = YAHOO.lang, Dom = YAHOO.util.Dom, Event = YAHOO.util.Event;

/**
 * Create an table where all cells are opened data editor
 * @class inputEx.widget.FastTable
 * @constructor
 * @param {Object} options Options:
 * <ul>
 *    <li>parentEl: the container element</li>
 *    <li>fields: array of inputEx Json field description</li>
 *    <li>datasource: a YUI datasource</li>
 * </ul>
 */ 
inputEx.widget.FastTable = function(options) {
   
   this.nRows = 0;
   
   this.setOptions(options);
   
   /**
    * @event Event fired when an item is updated
    */
   this.itemUpdatedEvt = new YAHOO.util.CustomEvent('itemUpdated', this);
   
   /**
    * @event Event fired when an item is created
    */
   this.itemCreatedEvt = new YAHOO.util.CustomEvent('itemCreated', this);
   
   /**
    * @event Event fired when an item is deleted
    */
   this.itemDeletedEvt = new YAHOO.util.CustomEvent('itemDeleted', this);
   
   /**
    * Keep references to the fields in the last row (Add row)
    */
   this.addRowFields = [];
   
   this.render();
   
   this.sendDataRequest();
};

inputEx.widget.FastTable.prototype = {
   
   /**
    * Set the options 
    */
   setOptions: function(opts) {
      this.options = {};
      this.options.parentEl = Dom.get(opts.parentEl);
		this.options.fields = opts.fields;
		this.options.datasource = opts.datasource;
   },
   
   /**
    * Render the widget dom
    */
   render: function() {
      
      this.el = inputEx.cn('div', {className: 'inputEx-FastTable'});
      
      this.tableEl = inputEx.cn('table', {className: 'inputEx-FastTable-table'});
      this.theadEl = document.createElement('thead');
      this.tbodyEl = document.createElement('tbody');
      
      this.tableEl.appendChild(this.theadEl);
      this.tableEl.appendChild(this.tbodyEl);
      
      this.renderThead();
      this.el.appendChild(this.tableEl);
      this.options.parentEl.appendChild(this.el);
   },
   
   /**
    * render the thead element
    */
   renderThead: function() {
      var tr = document.createElement('tr');
      
      for(var i = 0 ; i < this.options.fields.length ; i++) {
         var f = this.options.fields[i];
         var colName = f.inputParams.label || f.inputParams.name;
         
         var th = inputEx.cn('th', {className: 'inputEx-FastTable-Header'}, null, colName);
         tr.appendChild(th);
      }
      
      var th = inputEx.cn('th', {className: 'inputEx-FastTable-Header'}, null, "&nbsp;");
      tr.appendChild(th);
      
      this.theadEl.appendChild(tr);
   },
   
   /**
    * Send the datasource request
    */
   sendDataRequest: function(oRequest) {
      if (!!this.options.datasource) {
         this.options.datasource.sendRequest(oRequest, {success: this.onDatasourceSuccess, failure: this.onDatasourceFailure, scope: this});
      }
   },
   
   /**
    * Callback for request success 
    */
   onDatasourceSuccess: function(oRequest, oParsedResponse, oPayload) {
      this.populate(oParsedResponse.results);
   },
   
   /**
    * Callback for request failure 
    */
   onDatasourceFailure: function(oRequest, oParsedResponse, oPayload) {
      // TODO
   },
   
   /**
    * Insert the options
    */
   populate: function(items) {
      for( var i = 0 ; i < items.length ; i++) {
         this.addRow(items[i]);
      }
      this.addRow(); // the "add item" row
   },
   
   /**
    * Add a row
    */
   addRow: function(item) {
      var tr = inputEx.cn('tr', {className: (this.nRows % 2 == 0) ? 'inputEx-FastTable-even' : 'inputEx-FastTable-odd'});
      
      for(var i = 0 ; i < this.options.fields.length ; i++) {
         var f = this.options.fields[i];
         
         var inputExField = {
            type: f.type,
            inputParams: {}
         };
         
         for(var key in f.inputParams) {
            if( f.inputParams.hasOwnProperty(key) && key != "label") {
               inputExField.inputParams[key] = f.inputParams[key];
            }
         }
         
         var colName = f.inputParams.name;
         
         var td = inputEx.cn('td', {className: 'inputEx-FastTable-field'});
         inputExField.inputParams.parentEl = td;
         if(item) {
            inputExField.inputParams.value = item[colName];
         }
         
         var field = inputEx.buildField(inputExField);
         field._item = item;
         field.updatedEvt.subscribe( function(e,p) { 
            this.onFieldUpdated(e,p,item);
         }, this, true);
         
         // Add the reference to the created field for the addRow
         if(!item) { this.addRowFields.push(field); }
         
         tr.appendChild(td);
      }
      
      var td = inputEx.cn('td', {className: 'inputEx-FastTable-action'});
      
      if(item) {
         var deleteLink = inputEx.cn('a', {href: ""}, null, "delete");
         Event.addListener(deleteLink, 'click', function(e) { Event.stopEvent(e); this.onDeleteItem(item, tr); }, this, true);
         td.appendChild(deleteLink);
      }
      else {
         this.addTr = tr;
         var addLink = inputEx.cn('a', {href: ""}, null, "add");
         Event.addListener(addLink, 'click', function(e) { Event.stopEvent(e); this.onAddItem(); }, this, true);
         td.appendChild(addLink);
      }
      
      tr.appendChild(td);
      
      this.nRows += 1;
      
      if(this.addTr && tr != this.addTr) {
         this.tbodyEl.insertBefore(tr, this.addTr);
         
         // Clean the addRow:
         for(var i = 0 ; i < this.options.fields.length ; i++) {
            this.addRowFields[i].clear();
         }
      }
      else {
         this.tbodyEl.appendChild(tr);
      }
   },
   
   /**
    * Method to remove the row
    */
   removeRow: function(tr) {
      this.tbodyEl.removeChild(tr);
      this.nRows -= 1;
   },
   
   /**
    * Handle clicks on the add Button
    */
   onAddItem: function() {
      
      // Don't do anything if the row is not valid
      if(!this.validateAddRow()) { return; }
      
      var item = {};
      
      for(var i = 0 ; i < this.options.fields.length ; i++) {
         var f = this.options.fields[i];
         var name = f.inputParams.name;
         var value = this.addRowFields[i].getValue();
         if(typeof value != "undefined") {
            item[name] = value;
         }
      }
      
      this.itemCreatedEvt.fire(item);
   },
   
   /**
    * Validate all fields in addRow
    */
   validateAddRow: function() {
      for(var i = 0 ; i < this.addRowFields.length ; i++) {
         if(!this.addRowFields[i].validate()) {
            return false;
         }
      }
      return true;
   },
   
   /**
    * Handle clicks on the delete Button
    */
   onDeleteItem: function(item, tr) {
      this.itemDeletedEvt.fire(item, tr);
   },
   
   /**
    * Handle updated events from fields
    */
   onFieldUpdated: function(e,p,item) {
      
      var field = p[1];
      var cell = field.divEl.parentNode;
      
      var key = field.options.name;
      var item = field._item;
      var newValue = p[0];
      
      if(item) { 
         Dom.addClass(cell, "inputEx-FastTable-dirty-cell");
         this.itemUpdatedEvt.fire(item,key,newValue,cell);
      }
   },
   
   /**
    * Clean a cell marked as dirty
    */
   cleanDirtyCell: function(cell) {
      Dom.removeClass(cell, "inputEx-FastTable-dirty-cell");
   }
   
};

})();