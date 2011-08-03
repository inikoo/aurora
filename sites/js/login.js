 var Dom   = YAHOO.util.Dom;
 var Event  =YAHOO.util.Event;



function forgot_password(){

    var login_handle=Dom.get('forgot_password_handle').value;
    var store_key=Dom.get('store_key').value;

     var request='../ar_register.php?tipo=forgot_password&login_handle='+login_handle+'&store_key='+store_key;
    	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){
			
		      if(r.result=='ok'){

			
			  
		   }else if(r.result=='handle_not_found'){
			 
		      }
			    }else{
		    }
			

		},failure:function(o){
		    alert(o)
		}
	    
	    });
}



function login(){

    var input_login=Dom.get('login_handle').value;
    var pwd=sha256_digest(Dom.get('login_password').value);
    var input_epwd=Dom.get('ep').value;
    var store_key=Dom.get('store_key').value;

    
    
     var epwd=AESEncryptCtr(Dom.get('ep').value,pwd,256);
    // var epwd=AESEncryptCtr('hola1234567890123456789','caca',256);
    //    alert(AESDecryptCtr(epwd, 'caca',256)+"\n"+epwd);
    //  return;
//Dom.get('login_password').value='';
    //Dom.get('loginform').submit();
     var request='../ar_login.php?ep='+encodeURIComponent(epwd)+'&login_handle='+input_login+'&store_key='+store_key;
     // alert(request);
    	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=='200'){
			
		      if(r.result=='ok'){

			  location.reload(true);
			  
		   }else if(r.result=='no_valid'){
			  Dom.setStyle('invalid_credentials','display','');
                Dom.addClass(['login_password','login_handle'],'error');
		      }
			    }else{
			window.location='index.php?le';
		    }
			

		},failure:function(o){
		    alert(o)
		}
	    
	    });
}
