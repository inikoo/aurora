/**
 * Utility to run inputEx examples
 */
YAHOO.util.Event.onDOMReady( function() {
   
   try {
   
      if(!inputEx) {
         alert("inputEx not found !");
      }
   
      if(!YAHOO.lang.isArray(inputEx.examples) ) {
         alert("inputEx examples not found or not an array !");
      }
   
      var i, n=inputEx.examples.length;
   
      // Create the example structure
      for(i = 0 ; i < n ; i++) {
         var ex = inputEx.examples[i];
      
         var exampleDiv = inputEx.cn('div', {className: 'exampleDiv'});
         var title = inputEx.cn('p', {className: 'title'}, null, ex.title);
         var desc = inputEx.cn('p', {className: 'description'}, null, ex.description);
         var container = inputEx.cn('div', {className: 'demoContainer', id: 'container'+i});
         var codeContainer = inputEx.cn('div', {className: 'codeContainer'});
         var textarea = inputEx.cn('textarea', {name: 'code', className: 'JScript'}, null, String(ex.code) );
         codeContainer.appendChild(textarea);
      
         exampleDiv.appendChild(title);
         exampleDiv.appendChild(desc);
         exampleDiv.appendChild(container);
         exampleDiv.appendChild(codeContainer);
         
         document.body.appendChild(exampleDiv);
      }
   
      dp.SyntaxHighlighter.HighlightAll('code');
      
   }
   catch(ex) {
      alert("Error while building examples");
      console.log(ex);
   }
   
   // Run the examples
   for(i = 0 ; i < n ; i++) {
      try {
         var ex = inputEx.examples[i];
         ex.code(YAHOO.util.Dom.get('container'+i) );
      }
      catch(ex) {
         console.log("Error while running the example "+i);
         console.log(ex);
      }
   }
   
});