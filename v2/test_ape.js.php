function init_ape(){

	//Instantiate APE Client
	var client = new APE.Client();
	
	//1) Load APE Core
	client.load();
 
	//2) Intercept 'load' event. This event is fired when the Core is loaded and ready to connect to APE Server
	client.addEvent('load', function() {
	    //3) Call core start function to connect to APE Server, and prompt the user for a nickname
	    client.core.start({"name": prompt('Your name?')});
	});
	 
	//4) Listen to the ready event to know when your client is connected
	client.addEvent('ready', function() {
	    console.log('Your client is now connected');
	});


//1) join 'testChannel'
client.core.join('testChannel');
 
//2) Intercept pipeCreate event
client.addEvent('multiPipeCreate', function(pipe, options) {
    //3) Send the message on the pipe
    pipe.send('Hello world!');
    console.log('Sending Hello world');
});
 
//4) Intercept the reception of new message.
client.onRaw('data', function(raw, pipe) {
    console.log('Receiving : ' + unescape(raw.data.msg));
});
alert('a');
}

YAHOO.util.Event.onDOMReady(init_ape);

