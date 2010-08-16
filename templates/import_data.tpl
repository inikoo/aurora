﻿{include file='header.tpl'}
<div id="bd" >
 

<div id="no_details_title" style="clear:left;{if $show_details}display:none;{/if}">
    <h1>Import Data</h1>
  </div>



<div>

<table class="edit">
<tr class="top"><td class="label">{t}Data file{/t}:</td>
<td>
 
</td></tr>
</table>
</div>

{literal}
<style>
#selectFilesLink a, #uploadFilesLink a, #clearFilesLink a {
	color: #0000CC;
	background-color: #FFFFFF;
}

#selectFilesLink a:visited, #uploadFilesLink a:visited, #clearFilesLink a:visited {
	color: #0000CC;
	background-color: #FFFFFF;
}

#uploadFilesLink a:hover, #clearFilesLink a:hover {	
	color: #FFFFFF;
	background-color: #000000;
}
</style>
{/literal}

<div id="uiElements" style="display:inline;">
		<div id="postVars">
		Set custom values for a couple POST vars:<br/>
        format:  <input type="text" id="file_format" value="csv"/><br/>
        scope:  <input type="text" id="scope" value="{$scope}"/><br/>
         scope_args:  <input type="text" id="scope_args" value="{$scope_args}"/><br/>
	
		</div>
		<div id="uploaderContainer">
			<div id="uploaderOverlay" style="position:absolute; z-index:2"></div>
			<div id="selectFilesLink" style="z-index:1"><a id="selectLink" href="#">Select File</a></div>
		</div>

		<div id="uploadFilesLink"><a id="uploadLink" onClick="upload(); return false;" href="#">Upload File</a></div><br/>
		<div id="selectedFileDisplay">
		Progress: <input type="text" cols="50" id="progressReport" value="" readonly /><br/><br/>
		</div>
		<div id="returnedDataDisplay">
		Data returned by the server:<br/>
		<textarea id="serverData" rows="5" cols="50"></textarea>

		</div>
</div>
{literal}
<script type="text/javascript">

YAHOO.util.Event.onDOMReady(function () { 
var uiLayer = YAHOO.util.Dom.getRegion('selectLink');
var overlay = YAHOO.util.Dom.get('uploaderOverlay');
YAHOO.util.Dom.setStyle(overlay, 'width', uiLayer.right-uiLayer.left + "px");
YAHOO.util.Dom.setStyle(overlay, 'height', uiLayer.bottom-uiLayer.top + "px");
});

	// Custom URL for the uploader swf file (same folder).
	YAHOO.widget.Uploader.SWFURL = "assets/uploader.swf";

    // Instantiate the uploader and write it to its placeholder div.
	var uploader = new YAHOO.widget.Uploader( "uploaderOverlay" );
	
	// Add event listeners to various events on the uploader.
	// Methods on the uploader should only be called once the 
	// contentReady event has fired.
	
	uploader.addListener('contentReady', handleContentReady);
	uploader.addListener('fileSelect', onFileSelect)
	uploader.addListener('uploadStart', onUploadStart);
	uploader.addListener('uploadProgress', onUploadProgress);
	uploader.addListener('uploadCancel', onUploadCancel);
	uploader.addListener('uploadComplete', onUploadComplete);
	uploader.addListener('uploadCompleteData', onUploadResponse);
	uploader.addListener('uploadError', onUploadError);
    uploader.addListener('rollOver', handleRollOver);
    uploader.addListener('rollOut', handleRollOut);
    uploader.addListener('click', handleClick);
    	
    // Variable for holding the selected file id.
	var fileID;
	
	// When the mouse rolls over the uploader, this function
	// is called in response to the rollOver event.
	// It changes the appearance of the UI element below the Flash overlay.
	function handleRollOver () {
		YAHOO.util.Dom.setStyle(YAHOO.util.Dom.get('selectLink'), 'color', "#FFFFFF");
		YAHOO.util.Dom.setStyle(YAHOO.util.Dom.get('selectLink'), 'background-color', "#000000");
	}
	
	// On rollOut event, this function is called, which changes the appearance of the
	// UI element below the Flash layer back to its original state.
	function handleRollOut () {
		YAHOO.util.Dom.setStyle(YAHOO.util.Dom.get('selectLink'), 'color', "#0000CC");
		YAHOO.util.Dom.setStyle(YAHOO.util.Dom.get('selectLink'), 'background-color', "#FFFFFF");
	}
	
	// When the Flash layer is clicked, the "Browse" dialog is invoked.
	// The click event handler allows you to do something else if you need to.
	function handleClick () {
	}
	
	// When contentReady event is fired, you can call methods on the uploader.
	function handleContentReady () {
	    // Allows the uploader to send log messages to trace, as well as to YAHOO.log
		uploader.setAllowLogging(true);
		
		// Disallows multiple file selection in "Browse" dialog.
		uploader.setAllowMultipleFiles(false);
		
		// New set of file filters.
		var ff = new Array({description:"CSV_Data", extensions:"*.csv;*.txt"},
		                   {description:"Videos", extensions:"*.avi;*.mov;*.mpg"});
		                   
		// Apply new set of file filters to the uploader.
		uploader.setFileFilters(ff);
	}

	// Actually uploads the files. Since we are only allowing one file
	// to be selected, we use the upload function, in conjunction with the id 
	// of the selected file (returned by the fileSelect event). We are also including
	// the text of the variables specified by the user in the input UI.

	function upload() {
	if (fileID != null) {
		uploader.upload(fileID, "../get_files.php", 
		                "POST", 
		                {tipo:'upload_file'
						 
						 });
	}	
	}
	
	// Fired when the user selects files in the "Browse" dialog
	// and clicks "Ok". Here, we set the value of the progress
	// report textfield to reflect the fact that a file has been
	// selected.
	
	function onFileSelect(event) {
		for (var file in event.fileList) {
		    if(YAHOO.lang.hasOwnProperty(event.fileList, file)) {
				fileID = event.fileList[file].id;
			}
		}
		
		this.progressReport = document.getElementById("progressReport");
		this.progressReport.value = "Selected " + event.fileList[fileID].name;
	}


    // When the upload starts, we inform the user about it via
	// the progress report textfield. 
	function onUploadStart(event) {
		this.progressReport.value = "Starting upload...";
	}
	
	// As upload progresses, we report back to the user via the
	// progress report textfield.
	function onUploadProgress(event) {
		prog = Math.round(100*(event["bytesLoaded"]/event["bytesTotal"]));
		this.progressReport.value = prog + "% uploaded...";
	}
	
	// Report back to the user that the upload has completed.
	function onUploadComplete(event) {
		this.progressReport.value = "Upload complete.";
	}
	
	// Report back to the user if there has been an error.
	function onUploadError(event) {
	alert(event.status)
		this.progressReport.value = "Upload error.";
	}
	
	// Do something if an upload is canceled.
	function onUploadCancel(event) {

	}
	
	// When the data is received back from the server, display it to the user
	// in the server data text area.
	function onUploadResponse(event) {
		this.serverData = document.getElementById("serverData");
		this.serverData.value = event.data;
		
		try {
   var upload_results = YAHOO.lang.JSON.parse(event.data);
 
 var format=Dom.get('file_format').value;
  var scope=Dom.get('scope').value;
 var scope_args=Dom.get('scope_args').value;
 //format=Dom.get('file_format');
var map='';
   
   var request='ar_import_data.php?tipo=read_file&format=' + format+ '&scope=' + scope + '&map=' + map + '&files='+YAHOO.lang.JSON.stringify(upload_results.filenames)
		
		  
		    YAHOO.util.Connect.asyncRequest('POST',request ,{
			    success:function(o) {
				alert(o.responseText);
				var r =  YAHOO.lang.JSON.parse(o.responseText);
				
				
			    }
			    
			});
   
}
catch (e) {
    alert("Invalid product data");
}

		
	}
</script>
{/literal}


</div>

{include file='footer.tpl'}
