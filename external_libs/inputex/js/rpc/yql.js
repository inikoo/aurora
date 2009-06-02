/**
 * inputEx YQL utility
 *
 * Provide functions to run YQL javascript code and get results asynchronously
 *
 * How does it work ?
 * ------------------
 *
 * YQL Execute is only available from a YQL request using a YQL Open Table XML file.
 * This script uses an appjet.com application (http://yot-xmlfromcode.appjet.net/) to generate
 * the wanted XML file from javascript code.
 * Examples:
 *    http://yot-xmlfromcode.appjet.net/?url=http://gist.github.com/106503.txt
 *    http://yot-xmlfromcode.appjet.net/?code=y.log(%22test%22)
 * Sources:
 *    http://appjet.com/app/324229066/source?plaintext=1
 *
 * We use a classic JSONP hack to get the results via a callback method.
 *
 * @static
 */
inputEx.YQL = {
	
	/**
	 * Used as an identifier for the JSONP callback hack
	 */
	query_index: 0,
	
	/**
	 * Generate the jsonp request to YQL
	 * @param {String} yql YQL query string
	 * @param {Function} callback Callback function
	 */
	query: function(yql, callback) {
		 var  ud = 'yqlexecuteconsole'+(inputEx.YQL.query_index)++,
		      API = 'http://query.yahooapis.com/v1/public/yql?q=',
		      url = API + window.encodeURIComponent(yql) + '&format=json&callback=' + ud;
		 window[ud]= function(o){ callback && callback(o); };
	    document.body.appendChild((function(){
		    var s = document.createElement('script');
          s.type = 'text/javascript';
	       s.src = url;
	       return s;
	    })());	
	},
	
	/**
	 * Dynamically build a XML from javascript code and generate a dummy request for YQL
	 * @param {String} codeStr YQL-execute javascript code
	 * @param {Function} callback Callback function
	 */
	queryCode: function(codeStr, callback) {
		var url = ("http://yot-xmlfromcode.appjet.net/?code="+window.encodeURIComponent(codeStr)).replace(/'/g,"\\'");
		var yql = "use '"+url+"' as yqlexconsole; select * from yqlexconsole;";
		inputEx.YQL.query(yql,callback);
	},
	
	/**
	 * Dynamically build a XML from a URL and generate a dummy request for YQL
	 * @param {String} codeUrl Url to a YQL-execute javascript file
	 * @param {Function} callback Callback function
	 */
	queryUrl: function(codeUrl, callback) {
	   var url = ("http://yot-xmlfromcode.appjet.net/?url="+window.encodeURIComponent(codeUrl)).replace(/'/g,"\\'");
		var yql = "use '"+url+"' as yqlexconsole; select * from yqlexconsole;";
		inputEx.YQL.query(yql,callback);
	},
	
	/**
	 * Run script type="text/yql" tags on YQL servers
	 * If you have just one script tag and one callback, pass [[function(results) {}]]
	 * If you have two script tags and two callback for each: [ [function() {},function() {}] , [function() {},function() {}]]
	 * etc...
	 * @param {Array} callbacks Array of (list of callbacks functions) (provide a list of callbacks for each script type="text/yql" tag in the page)
	 */
	init: function(callbacks) {
	   var yqlScripts = YAHOO.util.Dom.getElementsBy( function(el) {
   		return (el.type && el.type == "text/yql");
   	} , "script" );

      var genCallbackFunction = function(fcts) {
         return function(results) {
            for(var i = 0 ; i < fcts.length ; i++) {
              fcts[i].call(this, results);
            }
         };
      };

      for(var i = 0 ; i < yqlScripts.length ; i++) {
         var yqlCode = yqlScripts[i].innerHTML;
      	inputEx.YQL.queryCode(yqlCode, genCallbackFunction(callbacks[i]) );
      }
	}
	
};
