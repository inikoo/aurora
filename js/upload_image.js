function edit_caption(o){

image_key=o.parentNode.parentNode.getAttribute('image_id');
Dom.get('caption'+image_key).style.display='none';
Dom.get('edit_caption'+image_key).style.display='';
Dom.get('img_reset_caption'+image_key).style.display='';
Dom.get('img_save_caption'+image_key).style.display='';
Dom.get('img_edit_caption'+image_key).style.display='none';
Dom.get('img_set_principal'+image_key).style.display='none';
Dom.get('img_principal'+image_key).style.display='none';
}

function reset_caption(o){

image_key=o.parentNode.parentNode.getAttribute('image_id');
Dom.get('edit_caption'+image_key).value=Dom.get('edit_caption'+image_key).getAttribute('ovalue');

Dom.get('caption'+image_key).style.display='';
Dom.get('edit_caption'+image_key).style.display='none';
Dom.get('img_reset_caption'+image_key).style.display='none';
Dom.get('img_save_caption'+image_key).style.display='none';
Dom.get('img_edit_caption'+image_key).style.display='';
Dom.get('img_save_caption'+image_key).src='art/icons/bullet_gray_disk.png';

if(o.parentNode.parentNode.getAttribute('is_principal')=='Yes')
Dom.get('img_principal'+image_key).style.display='';
else
Dom.get('img_set_principal'+image_key).style.display='';
}


function caption_changed(o){
image_key=o.parentNode.getAttribute('image_id');
if(o.value!=o.getAttribute('ovalue')){
Dom.get('img_save_caption'+image_key).src='art/icons/bullet_disk.png';
}else{
Dom.get('img_save_caption'+image_key).src='art/icons/bullet_gray_disk.png';
}


}


function save_caption(o){
image_key=o.parentNode.o.parentNode.getAttribute('image_id');
if(Dom.get('img_save_caption'+image_key).src=='art/icons/bullet_gray_disk.png')
    return;

  var request='ar_edit_assets.php?tipo=update_image&key=caption'+'&image_id='+escape(image_key)+'&scope='+scope+'&scope_key='+scope_key;
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state==200){
		       Dom.get('caption'+image_key).innerHTML=r.new_value;
		       Dom.get('edit_caption'+image_key).value=r.new_value;
		       Dom.get('edit_caption'+image_key).setAttribute('ovalue',r.new_value);
			   reset_caption(o);
		    }else
			alert(r.msg);
		}
		 
	    });

    
}

function set_image_as_principal(o){

image_key=o.parentNode.parentNode.getAttribute('image_id');
if(o.parentNode.parentNode.getAttribute('is_principal')=='Yes'){
return;
}



   

    var request='ar_edit_assets.php?tipo=update_image&key=principal&new_value=Yes&image_key='+escape(image_key)+'&scope='+scope+'&scope_key='+scope_key;
    YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state=200){
			var old_principal=Dom.get('images').getAttribute('principal');
			var new_principal=image_key;
			Dom.get('images').setAttribute('principal',new_principal);
			
			
			Dom.get('img_principal'+old_principal).style.display='none';
		    Dom.get('img_set_principal'+old_principal).style.display='';
            Dom.get('img_principal'+new_principal).style.display='';
		    Dom.get('img_set_principal'+new_principal).style.display='none';
			
		    }else
			alert(r.msg);
		}
		 
	    });

}



function delete_image(image_id,image_name){
    var answer = confirm('Delete');
    if (answer){

	

	var request='ar_edit_assets.php?tipo=delete_image&scope='+scope+'&scope_key='+scope_key+'&image_key='+escape(image_id);
	YAHOO.util.Connect.asyncRequest('POST',request ,{
		success:function(o) {
		    alert(o.responseText);
		    var r =  YAHOO.lang.JSON.parse(o.responseText);
		    if(r.state==200){
			Dom.get('image'+image_id).style.display='none';
			

		    }else
			alert(r.msg);
		}
		
	    });
    }


}



var onUploadButtonClick = function(e){
    //the second argument of setForm is crucial,
    //which tells Connection Manager this is a file upload form
    YAHOO.util.Connect.setForm('testForm', true);
    var request='ar_edit_assets.php?tipo=upload_product_image&scope='+scope+'&id='+scope_key;
    var uploadHandler = {
      upload: function(o) {
	   // alert(o.responseText)
	    var r =  YAHOO.lang.JSON.parse(o.responseText);
	   
	    if(r.state==200){

		var images=Dom.get('images');
		var image_div=document.createElement("div");
		image_div.setAttribute("id", "image"+r.data.id);
		image_div.setAttribute("class",'image');

		var name_div=document.createElement("div");
		name_div.innerHTML=r.data.name;
		       
		
		var picture_img=document.createElement("img");
		picture_img.setAttribute("src", r.data.small_url);
		picture_img.setAttribute("class", 'picture');
		picture_img.setAttribute("width", '160');

		var operations_div=document.createElement("div");
		operations_div.setAttribute("class",'operations');
		var set_principal_span=document.createElement("span");
		set_principal_span.setAttribute("class",'img_set_principal');
		set_principal_span.style.cursor='pointer';
		
		var set_principal_img=document.createElement("img");
		set_principal_img.setAttribute("id", "img_set_principal"+r.data.id);
		set_principal_img.setAttribute("image_id", r.data.id);
		
		
		
		set_principal_img.setAttribute("onClick", 'set_image_as_principal(this)');
		
		if(r.is_principal==1){
		    Dom.get('images').setAttribute('principal',r.data.id)
		    set_principal_img.setAttribute("principal", 1);
		    set_principal_img.setAttribute("src", 'art/icons/asterisk_orange.png');
		    set_principal_img.setAttribute("title", r.msg.main);
		}else{
		    set_principal_img.setAttribute("principal", 0);
		    set_principal_img.setAttribute("src", 'art/icons/picture_empty.png');
		    set_principal_img.setAttribute("title", r.msg.set_main);
		}	


		set_principal_span.appendChild(set_principal_img);
		var delete_span=document.createElement("span");
		delete_span.style.cursor='pointer';
		delete_span.innerHTML=' <img src="art/icons/delete.png">';
		delete_span.setAttribute("image_id", r.data.id);
		delete_span.setAttribute("onClick", 'delete_image(this)');
		operations_div.appendChild(set_principal_span);
		operations_div.appendChild(delete_span);

		var caption_div=document.createElement("div");
		caption_div.setAttribute("class",'caption');
		var caption_tag_div=document.createElement("div");
		caption_tag_div.innerHTML=r.msg.caption;
		
		var save_caption_img=document.createElement("img");
		save_caption_img.setAttribute("src",'art/icons/disk.png');
				save_caption_img.setAttribute("alt",'art/icons/disk.png');

		save_caption_img.setAttribute("title",r.msg.save_caption);
		save_caption_img.setAttribute("id",'save_img_caption'+r.data.id);
		save_caption_img.setAttribute("onClick",'save_image("img_caption",'+r.data.id+')');
		save_caption_img.setAttribute("class",'caption');
				save_caption_img.setAttribute("style",'display:none');



		var caption_textarea=document.createElement("textarea");
		caption_textarea.setAttribute("id",'img_caption'+r.data.id);
		caption_textarea.setAttribute("image_id",r.data.id);
		caption_textarea.setAttribute("ovalue",'');
		caption_textarea.setAttribute("onkeydown",'caption_changed(this)');
		caption_textarea.setAttribute("class",'caption');
		caption_textarea.style.width='140px';

		caption_div.appendChild(caption_tag_div);
		caption_div.appendChild(save_caption_img);
		caption_div.appendChild(caption_textarea);

		image_div.appendChild(name_div);
		image_div.appendChild(picture_img);
		image_div.appendChild(operations_div);
		image_div.appendChild(caption_div);

		images.appendChild(image_div);


	    }else
		alert(r.msg);
	    
	    

	}
    };

    YAHOO.util.Connect.asyncRequest('POST',request, uploadHandler);



  };




