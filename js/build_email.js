function changeHeight(iframe){
        try
        {
        
         
          var innerDoc = (iframe.contentDocument) ? iframe.contentDocument : iframe.contentWindow.document;
          
        
          if (innerDoc.body.offsetHeight) //ns6 syntax
          {
         // alert(innerDoc.body.offsetHeight)

            Dom.setStyle(iframe,'height',innerDoc.body.offsetHeight + 32  +'px');

             //iframe.height = innerDoc.body.offsetHeight + 32  +'px'; //Extra height FireFox
          }
          else if (iframe.Document && iframe.Document.body.scrollHeight) //ie5+ syntax
          {
                  Dom.setStyle(iframe,'height',iframe.Document.body.scrollHeight + 32  +'px');

          }else{
         
          Dom.setStyle(iframe,'height','700px');
            
          }
        }
        catch(err)
        {
          alert(err.message);
        }
      }


function reset_edit_email_content_text(){
reset_edit_general('email_content_text');
}

function reset_edit_email_content_html(){
reset_edit_general('email_content_html');
EmailHTMLEditor.setEditorHTML(Dom.get('html_email_editor').getAttribute('ovalue'))

}
      
function save_edit_email_content_text(){



save_edit_general('email_content_text');
}

function save_edit_email_content_html(){
EmailHTMLEditor.saveHTML();
save_edit_general('email_content_html');
}

function preview_email_campaign(){
get_preview( Dom.get('preview_index').value ) 
dialog_preview_text_email.show()
}

function previous_preview(){
get_preview( parseInt(Dom.get('preview_index').value)-1 )

}

function next_preview(){
get_preview( parseInt(Dom.get('preview_index').value)+1 )
}

function get_preview( index ) {
	var email_campaign_key=Dom.get('email_campaign_key').value;
	var request='ar_marketing.php?tipo=preview_email_campaign&email_campaign_key='+encodeURIComponent(email_campaign_key)+'&index='+index;

 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
//		alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
             
                 
           Dom.setStyle(['tr_preview_plain_body','tr_preview_html_body','tr_preview_template_body'],'display','none') 
    
          
             Dom.get('preview_index').value=r.index;
             Dom.get('preview_formated_index').innerHTML=r.formated_index;
             Dom.get('preview_to').innerHTML=r.to;
             Dom.get('preview_subject').innerHTML=r.subject;
          
             if(r.type=='Plain'){
                Dom.setStyle('tr_preview_plain_body','display','')
                Dom.get('preview_plain_body').innerHTML=r.plain;

             }else if(r.type=='HTML'){
                Dom.setStyle('tr_preview_plain_body','display','')
                              Dom.get('preview_plain_body').innerHTML=r.html;

             }else{
              Dom.setStyle('tr_preview_template_body','display','')
            Dom.get('preview_html_body').src=r.html_src;
              
             }
          
                
            
		}else{
		  
	    }
	    }
	    });
	
}

function html_editor_changed(){
    
    validate_scope_data['email_content_html']['content_html']['changed']=true;
    validate_scope('email_content_html');
    
}

function show_change_email_type(){
 var pos = Dom.getXY(this);
 pos[0]=pos[0]-300
 Dom.setXY('dialog_change_email_type', pos);
dialog_change_email_type.show();
}

function close_change_email_type(){
dialog_change_email_type.hide();
}

function close_upload_header_image(){
dialog_upload_header_image.hide();
}


function close_upload_postcard(){
dialog_upload_postcard.hide();

}
function show_upload_header_image(){
 var pos = Dom.getXY(this);
 pos[0]=pos[0]-320+100
 Dom.setXY('dialog_upload_header_image', pos);

Dom.get('upload_header_image_file').value='';
Dom.get('upload_header_image_name').value='';
dialog_upload_header_image.show();
}

function upload_header_image(e){
    YAHOO.util.Connect.setForm('upload_header_image_form', true,true);
    var request='ar_edit_marketing.php?tipo=upload_template_header_image';
   var uploadHandler = {
      upload: function(o) {
	   alert(o.responseText)
	    var r =  YAHOO.lang.JSON.parse(o.responseText);
	   
	    if(r.state==200){
	      table_id=11
                var table=tables['table'+table_id];
                var datasource=tables['dataSource'+table_id];
                datasource.sendRequest('',table.onDataReturnInitializeTable, table);  
                close_upload_header_image()
                
	    }else
		alert(r.msg);
	    
	    

	}
    };

    YAHOO.util.Connect.asyncRequest('POST',request, uploadHandler);



  };

function show_upload_postcard(){
 var pos = Dom.getXY(this);
 pos[0]=pos[0]-320+100
 Dom.setXY('dialog_upload_postcard', pos);

Dom.get('upload_postcard_file').value='';
Dom.get('upload_postcard_name').value='';
dialog_upload_postcard.show();
}

function upload_postcard(e){
    YAHOO.util.Connect.setForm('upload_postcard_form', true,true);
    var request='ar_edit_marketing.php?tipo=upload_postcard';
   var uploadHandler = {
      upload: function(o) {
	   alert(o.responseText)
	    var r =  YAHOO.lang.JSON.parse(o.responseText);
	   
	    if(r.state==200){
	      table_id=12
                var table=tables['table'+table_id];
                var datasource=tables['dataSource'+table_id];
                datasource.sendRequest('',table.onDataReturnInitializeTable, table);  
                close_upload_postcard()
                
	    }else
		alert(r.msg);
	    
	    

	}
    };

    YAHOO.util.Connect.asyncRequest('POST',request, uploadHandler);



  };


function delete_scheme(){
//todo, display standard dialog (are you sure? [No] [Yes])

save_delete_scheme()

}


function save_delete_scheme(){

	var color_scheme_key=Dom.get('color_edit_scheme_key').value;
	
	var request='ar_edit_marketing.php?tipo=delete_color_scheme&id='+color_scheme_key
 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
	 table_id=10
                var table=tables['table'+table_id];
                var datasource=tables['dataSource'+table_id];
                datasource.sendRequest('',table.onDataReturnInitializeTable, table);      
                       Dom.get('template_email_iframe').contentDocument.location.reload(true);
            	close_color_scheme_view_details();
	
		}else{
		  
	    }
	    }
	    });


}


function save_new_color_scheme(){


	var request='ar_edit_marketing.php?tipo=new_color_scheme&kbase_color_scheme_key=0&store_key='+Dom.get('store_key').value
 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		
		if(r.state==200){
	  table_id=10
                var table=tables['table'+table_id];
                var datasource=tables['dataSource'+table_id];
                datasource.sendRequest('',table.onDataReturnInitializeTable, table);      
                
            	show_color_scheme_view_details(r.color_scheme_key,r.data,r.name)
	
		}else{
		  
	    }
	    }
	    });


}



function save_select_postcard(template_postcard_key){

var email_campaign_key=Dom.get('email_campaign_key').value;
	var email_content_key=Dom.get('email_content_key').value;
	var request='ar_edit_marketing.php?tipo=edit_email_content&email_campaign_key='+email_campaign_key+'&email_content_key='+email_content_key+'&key=Email Content Template Postcard Key&value='+template_postcard_key;
 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
//	alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
             
                table_id=12
                var table=tables['table'+table_id];
                var datasource=tables['dataSource'+table_id];
                datasource.sendRequest('',table.onDataReturnInitializeTable, table);      
                Dom.get('template_email_iframe').contentDocument.location.reload(true);

            
		}else{
		  
	    }
	    }
	    });

}


function save_select_header_image(template_header_image_key){

var email_campaign_key=Dom.get('email_campaign_key').value;
	var email_content_key=Dom.get('email_content_key').value;
	var request='ar_edit_marketing.php?tipo=edit_email_content&email_campaign_key='+email_campaign_key+'&email_content_key='+email_content_key+'&key=Email Template Header Image Key&value='+template_header_image_key;
 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
//	alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
             
   table_id=11
                var table=tables['table'+table_id];
                var datasource=tables['dataSource'+table_id];
                datasource.sendRequest('',table.onDataReturnInitializeTable, table);      
                       Dom.get('template_email_iframe').contentDocument.location.reload(true);

            
		}else{
		  
	    }
	    }
	    });

}

function save_select_color_scheme(color_scheme_key){

var email_campaign_key=Dom.get('email_campaign_key').value;
	var email_content_key=Dom.get('email_content_key').value;
	var request='ar_edit_marketing.php?tipo=edit_email_content&email_campaign_key='+email_campaign_key+'&email_content_key='+email_content_key+'&key=Email Content Color Scheme Key&value='+color_scheme_key;
 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
	//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
             
   table_id=10
                var table=tables['table'+table_id];
                var datasource=tables['dataSource'+table_id];
                datasource.sendRequest('',table.onDataReturnInitializeTable, table);      
                       Dom.get('template_email_iframe').contentDocument.location.reload(true);

                Dom.setStyle('color_scheme_use_this','display','none');
            
		}else{
		  
	    }
	    }
	    });
	


}

function save_select_color_scheme_from_button(){


	save_select_color_scheme(Dom.get('color_edit_scheme_key').value)


}


function reset_default_color_scheme_values(){

	var color_scheme_key=Dom.get('color_edit_scheme_key').value;

	var request='ar_edit_marketing.php?tipo=reset_color_scheme&color_scheme_key='+color_scheme_key;
//alert(request)
 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
//	alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
  
              Dom.get('color_scheme_kbase_modified_'+r.color_scheme_key).value='No';
              
              
              
              for (x in r.color_scheme_data){
                Dom.setStyle('color_scheme_'+x+'_'+r.color_scheme_key,'background-color',r.color_scheme_data.x)
                Dom.get('color_scheme_'+x+'_'+r.color_scheme_key).setAttribute('alt',r.color_scheme_data.x)
              }
              
              
             
             
              
              
              
              
              
              
              
              

  show_color_scheme_view_details(r.color_scheme_key)
		}else{
		  
	    }
	    }
	    });

}

function close_edit_color_dialog(){
dialog_edit_color.hide();
}

function close_color_scheme_view_details(){


Dom.setStyle('color_schemes','display','');
Dom.setStyle(['color_scheme_details','close_color_scheme_view_details'],'display','none');


//	var color_scheme_key=Dom.get('color_edit_scheme_key').value;


//Dom.getElementsByClassName('color_scheme', 'tr', 'color_schemes');

//Dom.setStyle(color_scheme_rows,'display','');


//Dom.setStyle('color_scheme_view_details_'+color_scheme_key,'display','');


//Dom.setStyle(['color_scheme_details','close_color_scheme_view_details_'+color_scheme_key],'display','none');


}


function show_edit_color_dialog(e,element){


Dom.get('color_edit_element').value=element;

var color = new RGBColor(Dom.getStyle(this,'background-color'));


color_picker.setValue([color.r,color.g,color.b], false);
 var pos = Dom.getXY(this);
 pos[0]=pos[0]+20
 Dom.setXY('dialog_edit_color', pos);
dialog_edit_color.show();
}




function save_change_template_layout(e,value){

	var email_campaign_key=Dom.get('email_campaign_key').value;
	var email_content_key=Dom.get('email_content_key').value;
	var request='ar_edit_marketing.php?tipo=edit_email_content&email_campaign_key='+email_campaign_key+'&email_content_key='+email_content_key+'&key=Email Content Template Type&value='+value;
//alert(request)
 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
	//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
             
         if(r.key=='Email Content Template Type'){
                Dom.removeClass(['change_template_layout_basic','change_template_layout_right_column','change_template_layout_left_column','change_template_layout_postcard'],'selected');
                                Dom.setStyle(['selected_template_layout_basic','selected_template_layout_right_column','selected_template_layout_left_column','selected_template_layout_postcard'],'display','none')

                if(r.new_value=='Basic'){
                Dom.addClass('change_template_layout_basic','selected')
                Dom.setStyle('selected_template_layout_basic','display','')
                   Dom.setStyle('change_postcard','display','none');
                }else if(r.new_value=='Left Column'){
                  Dom.addClass('change_template_layout_left_column','selected')
                Dom.setStyle('selected_template_layout_left_column','display','')
                                   Dom.setStyle('change_postcard','display','none');

                }else if(r.new_value=='Right Column'){
                  Dom.addClass('change_template_layout_right_column','selected')
                Dom.setStyle('selected_template_layout_right_column','display','')
                                   Dom.setStyle('change_postcard','display','none');

                }else if(r.new_value=='Postcard'){
                  Dom.addClass('change_template_layout_postcard','selected')
                Dom.setStyle('selected_template_layout_postcard','display','')
                Dom.setStyle('change_postcard','display','');

                }
                Dom.get('template_email_iframe').contentDocument.location.reload(true);
                


                
                
         
         }
          
                
            
		}else{
		  
	    }
	    }
	    });
	


}


function save_color(){


	var color_scheme_key=Dom.get('color_edit_scheme_key').value;
	var color_element=Dom.get('color_edit_element').value;
	var color=  color_picker.get("hex")
	var request='ar_edit_marketing.php?tipo=edit_color_scheme&color_scheme_key='+color_scheme_key+'&color_element='+color_element+'&color='+color;

 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
//	alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
		 Dom.get('color_scheme_template_email_iframe').contentDocument.location.reload(true);
		 		 Dom.get('template_email_iframe').contentDocument.location.reload(true);

            Dom.setStyle('color_scheme_'+r.element,'background-color','#'+r.color)
            Dom.get('color_scheme_'+r.element,'background-color').setAttribute('alt','#'+r.color)
            
            
            Dom.get('color_scheme_kbase_modified_'+r.color_scheme_key).value=r.kbase_modified;
            if(r.kbase_modified=='Yes'){
Dom.setStyle('reset_default_color_scheme_values','display','')
}else{
Dom.setStyle('reset_default_color_scheme_values','display','none')
}
            
            
		dialog_edit_color.hide();
		}else{
		  
	    }
	    }
	    });

}

function show_change_template_content(){

change_template_buttons=Dom.getElementsByClassName('change_template_buttons', 'button', 'change_template_buttons_tr');
Dom.removeClass(change_template_buttons,'selected')
Dom.addClass('change_template_content','selected')

Dom.setStyle(['change_template_layout_tr','change_template_color_scheme_tr','change_template_header_image_tr','change_postcard_tr'],'display','none');
Dom.setStyle('template_editor_tr','display','');
                changeHeight(Dom.get('template_email_iframe'))

}


function show_change_template_layout(){

change_template_buttons=Dom.getElementsByClassName('change_template_buttons', 'button', 'change_template_buttons_tr');
Dom.removeClass(change_template_buttons,'selected')
Dom.addClass('change_template_layout','selected')

Dom.setStyle(['template_editor_tr','change_template_color_scheme_tr','change_template_header_image_tr','change_postcard_tr'],'display','none');
Dom.setStyle('change_template_layout_tr','display','');

}

function show_change_template_color_scheme(){

change_template_buttons=Dom.getElementsByClassName('change_template_buttons', 'button', 'change_template_buttons_tr');
Dom.removeClass(change_template_buttons,'selected')
Dom.addClass('change_template_color_scheme','selected')

Dom.setStyle(['template_editor_tr','change_template_layout_tr','change_template_header_image_tr','change_postcard_tr'],'display','none');
Dom.setStyle('change_template_color_scheme_tr','display','');
}

function show_change_template_header_image(){
change_template_buttons=Dom.getElementsByClassName('change_template_buttons', 'button', 'change_template_buttons_tr');
Dom.removeClass(change_template_buttons,'selected')
Dom.addClass('change_template_header_image','selected')

Dom.setStyle(['template_editor_tr','change_template_layout_tr','change_template_color_scheme_tr','change_postcard_tr'],'display','none');
Dom.setStyle('change_template_header_image_tr','display','');
}


function show_change_postcard(){
change_template_buttons=Dom.getElementsByClassName('change_template_buttons', 'button', 'change_template_buttons_tr');
Dom.removeClass(change_template_buttons,'selected')
Dom.addClass('change_postcard','selected')

Dom.setStyle(['template_editor_tr','change_template_layout_tr','change_template_color_scheme_tr','change_template_header_image_tr'],'display','none');
Dom.setStyle('change_postcard_tr','display','');

}

function show_color_scheme_view_details(color_scheme_key,data,name){

data=data.split(';')

Dom.get('color_edit_scheme_key').value=color_scheme_key;
Dom.get('color_scheme_template_email_iframe').src="email_template.php?email_campaign_key="+Dom.get('email_campaign_key').value+"&email_content_key="+Dom.get('email_content_key').value+"&color_scheme_key="+color_scheme_key

if(data[0]=='Yes'){
Dom.setStyle('reset_default_color_scheme_values','display','')
}else{
Dom.setStyle('reset_default_color_scheme_values','display','none')
}

if(data[13]=='Yes'){
Dom.setStyle('color_scheme_use_this','display','none')
}else{
Dom.setStyle('color_scheme_use_this','display','')
}


Dom.get('color_scheme_details_name').innerHTML=name;

Dom.setStyle('color_schemes','display','none');
Dom.setStyle(['color_scheme_details','close_color_scheme_view_details'],'display','');

//color_scheme_rows=Dom.getElementsByClassName('color_scheme', 'tr', 'color_schemes');
//Dom.setStyle(color_scheme_rows,'display','none');
//Dom.setStyle('color_scheme_view_details_'+color_scheme_key,'display','none');

//Dom.setStyle(['close_color_scheme_view_details','color_scheme_details','color_scheme_tr_'+color_scheme_key,'close_color_scheme_view_details_'+color_scheme_key],'display','');
//Dom.setStyle(['close_color_scheme_view_details','color_scheme_details','close_color_scheme_view_details_'+color_scheme_key],'display','');

Dom.setStyle('color_scheme_details_name','background-color','#'+data[2])
Dom.setStyle('color_scheme_details_name','color','#'+data[5])


Dom.setStyle('color_scheme_Background_Body','background-color','#'+data[1])
Dom.setStyle('color_scheme_Background_Header','background-color','#'+data[2])
Dom.setStyle('color_scheme_Background_Container','background-color','#'+data[3])
Dom.setStyle('color_scheme_Background_Footer','background-color','#'+data[4])
Dom.setStyle('color_scheme_Text_Header','background-color','#'+data[5])
Dom.setStyle('color_scheme_Link_Header','background-color','#'+data[6])
Dom.setStyle('color_scheme_Text_Footer','background-color','#'+data[7])
Dom.setStyle('color_scheme_Link_Footer','background-color','#'+data[8])
Dom.setStyle('color_scheme_Text_Container','background-color','#'+data[9])
Dom.setStyle('color_scheme_Link_Container','background-color','#'+data[10])
Dom.setStyle('color_scheme_H1','background-color','#'+data[11])
Dom.setStyle('color_scheme_H2','background-color','#'+data[12])

 changeHeight(Dom.get('color_scheme_template_email_iframe'))

}

function save_change_email_type(e,value){

  


var email_campaign_key=Dom.get('email_campaign_key').value;
var email_content_key=Dom.get('email_content_key').value;

var request='ar_edit_marketing.php?tipo=edit_email_content&email_campaign_key='+email_campaign_key+'&email_content_key='+email_content_key+'&key=Email Content Type&value='+value;

 YAHOO.util.Connect.asyncRequest('POST',request ,{
	    success:function(o) {
		//alert(o.responseText)
		var r =  YAHOO.lang.JSON.parse(o.responseText);
		if(r.state==200){
            Dom.removeClass(['select_text_email','select_html_from_template_email','select_html_email'],'selected');
            Dom.setStyle(['text_email_fields','html_email_from_template_fields','html_email_fields'],'display','none')

            switch ( r.new_value ) {
            	case 'Plain':
            	    Dom.get('email_campaign_content_text').value=r.updated_data.text;
            	
                    Dom.addClass('select_text_email','selected');
                    Dom.setStyle('text_email_fields','display','')
            		break;
            	
            	case'HTML':
EmailHTMLEditor.setEditorHTML(r.updated_data.html)
            	    Dom.addClass('select_html_email','selected');
                    Dom.setStyle('html_email_fields','display','')
            	break;
            	case 'HTML Template':
            	    Dom.addClass('select_html_from_template_email','selected');
                    Dom.setStyle('html_email_from_template_fields','display','')
                                    Dom.get('template_email_iframe').contentDocument.location.reload(true);

            	    break;
            }
            
         
            
            
            close_change_email_type();

            
		}else{
		    if(r.msg!=undefined)
		        Dom.get('add_email_address_from_customer_list_msg').innerHTML='<span class="error">'+r.msg+'</span>';
	      
	    }
	    }
});



}

function build_email_init(){



 Event.addListener("select_text_email", "click", save_change_email_type,'Plain');
  
    Event.addListener("select_html_email", "click", save_change_email_type,'HTML');
    Event.addListener("select_html_from_template_email", "click", save_change_email_type,'HTML Template');
  
     
      Event.addListener('reset_edit_email_content_text', "click", reset_edit_email_content_text);
    Event.addListener('save_edit_email_content_text', "click", save_edit_email_content_text);
   
       Event.addListener('reset_edit_email_content_html', "click", reset_edit_email_content_html);
    Event.addListener('save_edit_email_content_html', "click", save_edit_email_content_html);
    
  
     
       var myConfig = {
        height: '300px',
        width: '600px',
        animate: true,
        dompath: true,
        focusAtStart: true,
    };
    
    var state = 'off';
    
  
    
        EmailHTMLEditor = new YAHOO.widget.Editor('html_email_editor', myConfig);
       
    EmailHTMLEditor.on('toolbarLoaded', function() {
    
        var codeConfig = {
            type: 'push', label: 'Edit HTML Code', value: 'editcode'
        };
        this.toolbar.addButtonToGroup(codeConfig, 'insertitem');
        
        this.toolbar.on('editcodeClick', function() {
        

        
            var ta = this.get('element'),iframe = this.get('iframe').get('element');

            if (state == 'on') {
                state = 'off';
                this.toolbar.set('disabled', false);
                          this.setEditorHTML(ta.value);
                if (!this.browser.ie) {
                    this._setDesignMode('on');
                }

                Dom.removeClass(iframe, 'editor-hidden');
                Dom.addClass(ta, 'editor-hidden');
                this.show();
                this._focusWindow();
            } else {
                state = 'on';
                
                this.cleanHTML();
               
                Dom.addClass(iframe, 'editor-hidden');
                Dom.removeClass(ta, 'editor-hidden');
                this.toolbar.set('disabled', true);
                this.toolbar.getButtonByValue('editcode').set('disabled', false);
                this.toolbar.selectButton('editcode');
                this.dompath.innerHTML = 'Editing HTML Code';
                this.hide();
            
            }
            return false;
        }, this, true);

        this.on('cleanHTML', function(ev) {
            this.get('element').value = ev.html;
        }, this, true);
        
        
        
        this.on('editorKeyUp', html_editor_changed, this, true);
        this.on('editorDoubleClick', html_editor_changed, this, true);
          this.on('editorMouseDown', html_editor_changed, this, true);
          this.on('buttonClick', html_editor_changed, this, true);

        this.on('afterRender', function() {
            var wrapper = this.get('editor_wrapper');
            wrapper.appendChild(this.get('element'));
            this.setStyle('width', '100%');
            this.setStyle('height', '100%');
            this.setStyle('visibility', '');
            this.setStyle('top', '');
            this.setStyle('left', '');
            this.setStyle('position', '');

            this.addClass('editor-hidden');
        }, this, true);
    }, EmailHTMLEditor, true);
        yuiImgUploader(EmailHTMLEditor, 'html_email_editor', 'ar_upload_file_from_editor.php','image');

    
    EmailHTMLEditor.render();
 
      Event.addListener("previous_preview", "click", previous_preview);
       
      Event.addListener("next_preview", "click", next_preview);
      Event.addListener("change_template_content", "click", show_change_template_content);
      Event.addListener("change_template_layout", "click", show_change_template_layout);
     
      Event.addListener("change_template_color_scheme", "click", show_change_template_color_scheme);
      Event.addListener("change_template_header_image", "click", show_change_template_header_image);
      Event.addListener("change_postcard", "click", show_change_postcard);
      
       
      
      Event.addListener("change_template_layout_basic", "click", save_change_template_layout,'Basic');
      Event.addListener("change_template_layout_right_column", "click", save_change_template_layout,'Right Column');
      Event.addListener("change_template_layout_left_column", "click", save_change_template_layout,'Left Column');
      Event.addListener("change_template_layout_postcard", "click", save_change_template_layout,'Postcard');

    
 color_picker = new YAHOO.widget.ColorPicker("edit_color", {
	showhsvcontrols: true,
	showhexcontrols: true,
	images: {
		PICKER_THUMB: "art/picker_thumb.png",
		HUE_THUMB: "art/hue_thumb.png"
	}
});

   dialog_edit_color = new YAHOO.widget.Dialog("dialog_edit_color", {visible : false,close:true,underlay: "none",draggable:false});
    dialog_edit_color.render();



 dialog_upload_header_image = new YAHOO.widget.Dialog("dialog_upload_header_image", {visible : false,close:true,underlay: "none",draggable:false});
    dialog_upload_header_image.render();
    
     dialog_upload_postcard = new YAHOO.widget.Dialog("dialog_upload_postcard", {visible : false,close:true,underlay: "none",draggable:false});
    dialog_upload_postcard.render();
    

                  Event.addListener("color_scheme_Background_Body", "click", show_edit_color_dialog,'Background_Body');
                  Event.addListener("color_scheme_Background_Header", "click", show_edit_color_dialog,'Background_Header');
                  Event.addListener("color_scheme_Text_Header", "click", show_edit_color_dialog,'Text_Header');
                  Event.addListener("color_scheme_Link_Header", "click", show_edit_color_dialog,'Link_Header');
                  Event.addListener("color_scheme_Background_Container", "click", show_edit_color_dialog,'Background_Container');
                  Event.addListener("color_scheme_H1", "click", show_edit_color_dialog,'H1');
                  Event.addListener("color_scheme_H2", "click", show_edit_color_dialog,'H2');
                  Event.addListener("color_scheme_Text_Container", "click", show_edit_color_dialog,'Text_Container');
                  Event.addListener("color_scheme_Link_Container", "click", show_edit_color_dialog,'Link_Container');
                  Event.addListener("color_scheme_Background_Footer", "click", show_edit_color_dialog,'Background_Footer');
                  Event.addListener("color_scheme_Text_Footer", "click", show_edit_color_dialog,'Text_Footer');
                  Event.addListener("color_scheme_Link_Footer", "click", show_edit_color_dialog,'Link_Footer');
                  Event.addListener("close_edit_color_dialog", "click", close_edit_color_dialog);
                  Event.addListener("save_color", "click", save_color);
                  Event.addListener("reset_default_color_scheme_values", "click", reset_default_color_scheme_values);
                  Event.addListener("new_color_scheme", "click", save_new_color_scheme);
                  Event.addListener("delete_scheme", "click", delete_scheme);
                  Event.addListener("close_color_scheme_view_details", "click", close_color_scheme_view_details);

                 // Event.addListener("upload_header_image", "click", upload_header_image);
Event.addListener("upload_header_image", "click", upload_header_image);
Event.addListener("upload_postcard", "click", upload_postcard);
Event.addListener("cancel_upload_header_image", "click", close_upload_header_image);
Event.addListener("cancel_upload_postcard", "click", close_upload_postcard);



                  Event.addListener("new_template_header_image", "click", show_upload_header_image);
                  Event.addListener("new_postcard", "click", show_upload_postcard);



  dialog_change_email_type = new YAHOO.widget.Dialog("dialog_change_email_type", {visible : false,close:true,underlay: "none",draggable:false});
    dialog_change_email_type.render();
                     Event.addListener(['change_type1','change_type2','change_type3'], "click", show_change_email_type);


}

Event.onDOMReady(build_email_init);

