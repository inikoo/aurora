 // Variable for holding the selected file ID.
	var fileID;
var uploader

	function upload() {
	if (fileID != null) {
		uploader.upload(fileID, "upload_files.php");
		fileID = null;
	}
	}

function init(){
    
    // Instantiate the uploader and write it to its placeholder div.
	
	YAHOO.widget.Uploader.SWFURL = "external_libs/yui/2.9/build/uploader/assets/uploader.swf"
	
	 uploader = new YAHOO.widget.Uploader( "uploaderUI", "art/selectFileButton.png" );
	
	// Add event listeners to various events on the uploader.
	// Methods on the uploader should only be called once the 
	// contentReady event has fired.
	
	uploader.addListener('contentReady', handleContentReady);
	uploader.addListener('fileSelect',onFileSelect)
	uploader.addListener('uploadStart',onUploadStart);
	uploader.addListener('uploadProgress',onUploadProgress);
	uploader.addListener('uploadCancel',onUploadCancel);
	uploader.addListener('uploadComplete',onUploadComplete);
	uploader.addListener('uploadCompleteData',onUploadResponse);
	uploader.addListener('uploadError', onUploadError);
    	
   
	
	function handleClearFiles() {
	uploader.clearFileList();
	uploader.enable();
	fileID = null;
	
	var filename = document.getElementById("fileName");
	filename.innerHTML = "";
	
	var progressbar = document.getElementById("progressBar");
	progressbar.innerHTML = "";
	}
		
	// When contentReady event is fired, you can call methods on the uploader.
	function handleContentReady () {
	    // Allows the uploader to send log messages to trace, as well as to YAHOO.log
		uploader.setAllowLogging(true);
		
		// Restrict selection to a single file (that's what it is by default,
		// just demonstrating how).
		uploader.setAllowMultipleFiles(false);
		
		// New set of file filters.
		var ff = new Array({description:"Images", extensions:"*.jpg;*.png;*.gif"},
		                   {description:"Videos", extensions:"*.avi;*.mov;*.mpg"});
		                   
		// Apply new set of file filters to the uploader.
		uploader.setFileFilters(ff);
	}

	// Initiate the file upload. Since there's only one file, 
	// we can use either upload() or uploadAll() call. fileList 
	// needs to have been populated by the user.

	
	// Fired when the user selects files in the "Browse" dialog
	// and clicks "Ok".
	function onFileSelect(event) {
		for (var item in event.fileList) {
		    if(YAHOO.lang.hasOwnProperty(event.fileList, item)) {
				YAHOO.log(event.fileList[item].id);
				fileID = event.fileList[item].id;
			}
		}
		uploader.disable();
		
		var filename = document.getElementById("fileName");
		filename.innerHTML = event.fileList[fileID].name;
		
		var progressbar = document.getElementById("progressBar");
		progressbar.innerHTML = "";
	}

    // Do something on each file's upload start.
	function onUploadStart(event) {
	
	}
	
	// Do something on each file's upload progress event.
	function onUploadProgress(event) {
		prog = Math.round(300*(event["bytesLoaded"]/event["bytesTotal"]));
	  	progbar = "<div style=\"background-color: #f00; height: 5px; width: " + prog + "px\"/>";

		var progressbar = document.getElementById("progressBar");
		progressbar.innerHTML = progbar;
	}
	
	// Do something when each file's upload is complete.
	function onUploadComplete(event) {
		uploader.clearFileList();
		uploader.enable();
		
		progbar = "<div style=\"background-color: #f00; height: 5px; width: 300px\"/>";
		var progressbar = document.getElementById("progressBar");
		progressbar.innerHTML = progbar;
	}
	
	// Do something if a file upload throws an error.
	// (When uploadAll() is used, the Uploader will
	// attempt to continue uploading.
	function onUploadError(event) {

	}
	
	// Do something if an upload is cancelled.
	function onUploadCancel(event) {

	}
	
	// Do something when data is received back from the server.
	function onUploadResponse(event) {
		YAHOO.log("Server response received.");
	}

}
YAHOO.util.Event.onDOMReady(init);
