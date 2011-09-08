{include file='header.tpl'}
<div id="bd" >
 {include file='assets_navigation.tpl'}
<div style="clear:left;"> 
 <span class="branch" ><a  href="store.php?id={$store->id}">{$store->get('Store Name')}</a>&rarr; <span id="title_name_bis">{$department->get('Product Department Name')}</span></span>
 </div>
 
<div style="clear:left;margin:0 0px">
    <h1>{t}Editing Department{/t}: <span id="title_name">{$department->get('Product Department Name')}</span> (<span id="title_code">{$department->get('Product Department Code')}</span>)</h1>
</div>
   
 

<div id="msg_div"></div>
  <ul class="tabs" id="chooser_ul" style="clear:both">
    <li> <span class="item {if $edit=='details'}selected{/if}"  id="details">  <span> {t}Description{/t}</span></span></li>
    <li> <span class="item {if $edit=='discounts'}selected{/if}"  id="discounts">  <span> {t}Discounts{/t}</span></span></li>
    <li> <span class="item {if $edit=='pictures'}selected{/if}" id="pictures"  ><span>  {t}Pictures{/t}</span></span></li>
    <li> <span class="item {if $edit=='families'}selected{/if}" id="families"  ><span> {t}Families{/t}</span></span></li>
    <li> <span class="item {if $edit=='web'}selected{/if} " id="web" ><span> {t}Web Pages{/t}</span></span></li>
  </ul>
 <div class="tabbed_container"> 
  <span style="display:none" id="description_num_changes"></span>
    <div id="description_errors"></div>
    
    
    
  
   <div  id="d_details" class="edit_block" style="{if $edit!='details'}display:none{/if}"  >
      
    
      <div class="general_options" style="text-align:right;xfloat:right">
	
	<span  style="margin-right:10px;visibility:hidden"  onClick="save_edit_general('department')" id="save_edit_department" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;visibility:hidden"  onClick="reset_edit_general('department')" id="reset_edit_department" class="state_details">{t}Reset{/t}</span>
	
      </div>



    <table border=0 styel="clear:both" class="edit">
      <tr><td class="label" >{t}Department Code{/t}:</td><td>
	 <div  style="width:15em" >

	      <input  
		 id="code" 
		 changed=0 
		 type='text' 
		 class='text' 
		 style="width:15em" 
		 MAXLENGTH="16" 
		 value="{$department->get('Product Department Code')}" 
		 ovalue="{$department->get('Product Department Code')}"  
		 />
		 <div id="code_Container" style="" ></div>
         </div>
	    </td>
	     <td id="code_msg" class="edit_td_alert" style="width:300px"></td>

	  </tr>
	  <tr><td class="label">{t}Department Name{/t}:</td><td>
	      <div  style="width:30em" >
		<input   
		   id="name" 
		  
		   changed=0 
		   type='text'  
		   MAXLENGTH="255" 
		   style="width:30em"  
		   class='text' 
		   value="{$department->get('Product Department Name')}"  
		   ovalue="{$department->get('Product Department Name')}"  
		   />
		<div id="name_Container" style="" ></div>
              </div>
	    </td>
	     <td id="name_msg" class="edit_td_alert" style="width:300px"></td>
	  </tr>
	 
    </table>
    </div>
   <div  id="d_pictures" class="edit_block" style="{if $edit!='pictures'}display:none{/if}" >


{include file='edit_images_splinter.tpl'}


</div>  
   <div  id="d_families" class="edit_block" style="{if $edit!="families"}display:none{/if}"  >
     

<div class="general_options" style="float:right">
		<span  style="margin-right:10px;visibility:hidden"  id="save_new_family" onClick="save_new_general('family')" class="state_details">{t}Save New Family{/t}</span>
  	    <span style="margin-right:10px;visibility:hidden" id="cancel_new_family" onClick="cancel_new_general('family')" class="state_details">{t}Cancel New Family{/t}</span>
	    <span  style="margin-right:10px;"  id="show_new_family_dialog_button" onClick="show_new_family_dialog()" class="state_details">{t}Create New Family{/t}</span>
	    <span  style="margin-right:10px;"  id="import_new_family" class="state_details">{t}Import Families (CSV){/t}</span>
</div>





    

    <div     style="margin:0 0 10px 0;padding:10px;border:1px solid #ccc;display:none"  id="new_family_dialog" >
      <div id="new_family_messages" class="messages_block"></div>
    <table >
        <tr><td></td><td  id="new_family_dialog_msg"></td></tr>

        <tr><td class="label" >{t}Family Code{/t}:</td><td>
	 <div  style="width:15em" >

	      <input  
		 id="family_code" 
		 changed=0 
		 type='text' 
		 class='text' 
		 style="width:15em" 
		 MAXLENGTH="16" 
		 value="" 
		 ovalue=""  
		 />
		 <div id="family_code_Container" style="" ></div>
         </div>
	    </td>
	     <td id="family_code_msg" class="edit_td_alert" style="width:300px"></td>

	  </tr>
	  <tr><td class="label">{t}Family Name{/t}:</td><td>
	      <div  style="width:30em" >
		<input   
		   id="family_name" 
		  
		   changed=0 
		   type='text'  
		   MAXLENGTH="255" 
		   style="width:30em"  
		   class='text' 
		   value=""  
		   ovalue=""  
		   />
		<div id="family_name_Container" style="" ></div>
              </div>
	    </td>
	     <td id="family_name_msg" class="edit_td_alert" style="width:300px"></td>
	  </tr>   
        <tr>
        <td class="label">{t}Special Characteristic{/t}:</td><td>
	      <div  style="width:30em" >
		<input   
		   id="family_special_char" 
		  
		   changed=0 
		   type='text'  
		   MAXLENGTH="255" 
		   style="width:30em"  
		   class='text' 
		   value=""  
		   ovalue=""  
		   />
		<div id="family_special_char_Container" style="" ></div>
              </div>
	    </td>
	     <td id="family_special_char_msg" class="edit_td_alert" style="width:300px"></td>
	  </tr>  
	   </tr>   
        <tr>
        <td class="label">{t}Description{/t}:</td><td>
	      <div  style="width:30em" >
		<textarea   
		   id="family_description" 
		  
		  
		   ovalue=""  
		   /></textarea>
	    </td>
	     <td id="family_description_msg" class="edit_td_alert" style="width:300px"></td>
	  </tr>  
	  
    </table>
  </div>


  <div   class="data_table" >
    <span class="clean_table_title">{t}Families{/t}</span>
 {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }
    <div  id="table0"   class="data_table_container dtable btable "> </div>
  </div>
  
    </div>
   <div  id="d_web" class="edit_block" style="{if $edit!="web"}display:none{/if}"  >



      <div class="general_options" style="float:right">
	     <span style="margin-right:10px;"   id="new_department_page" class="state_details" >{t}Create Page{/t}</span>
	        <span style="margin-right:10px;{if $number_of_pages<=1}display:none{/if}" " id="page_list" class="state_details"><a href="edit_department.php?id={$department->id}">{t}Page List{/t} ({$number_of_pages})</a></span>

	   </div>
	
	
	
		<input type='hidden' id="site_key" value="{$site_key}">
				<input type='hidden' id="page_key" value="{$page_key}">



<div   class="data_table" style="clear:both;{if $page_key}display:none{/if}" >
	  <span class="clean_table_title">{t}Pages{/t}</span> 
	  <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
   {include file='table_splinter.tpl' table_id=6 filter_name=$filter_name6 filter_value=$filter_value6  }
	  <div  id="table6"  style="font-size:90%" class="data_table_container dtable btable "> </div>
	</div>

	   <table class="edit"  border=1 id="edit_department_page"  style="width:100%;clear:both;{if !$page_key}display:none{/if}" page_key="{$page_key}"   >
	     <tr class="title"><td colspan="2">{t}Page Properties{/t}
	     </td>
	     <td>
	      <div class="general_options" style="float:right">
		   
		   <span  style="margin-right:10px;visibility:hidden"  id="save_edit_department_page_properties" class="state_details">{t}Save{/t}</span>
		   <span style="margin-right:10px;visibility:hidden" id="reset_edit_department_page_properties" class="state_details">{t}Reset{/t}</span>
		   
      </div>
	     </td>
	     
	     </tr>

  <tr><td width="180px" class="label">{t}Page Code{/t}:</td>
	     <td>
		 <div   >     
		   <input  id="department_page_properties_page_code"   value="{$page_data.PageCode}" ovalue="{$page_data.PageCode}"  />
		   
		   <div id="department_page_properties_page_code_Container" style="" ></div>
		 </div>
	       </td><td><div id="department_page_properties_page_code_msg"></div></td></tr>

	     <tr><td width="180px" class="label">{t}URL{/t}:</td>
	     <td>
		 <div   >
		   <input  id="department_page_properties_url"   value="{$page_data.PageURL}" ovalue="{$page_data.PageURL}"  />
		   
		   <div id="department_page_properties_url_Container" style="" ></div>
		 </div>
	       </td><td><div id="department_page_properties_url_msg"></div>
      </td></tr>

  <tr><td width="180px" class="label">{t}Link Title{/t}:</td>
	     <td>
		 <div   >
		   <input  id="department_page_properties_link_title"   value="{$page_data.PageShortTitle}" ovalue="{$page_data.PageShortTitle}"  />
		   
		   <div id="department_page_properties_link_title_Container" style="" ></div>
		 </div>
	       </td><td><div id="department_page_properties_link_title_msg"></div>
      </td></tr>




<tr><td class="label">{t}Page Type{/t}:</td>
 <td>
 <table>
 <tr><td class="label">{t}External body & HTML HEAD{/t}:</td><td><input layout="thumbnails" id="checkbox_thumbnails" type="checkbox"  {if $page_data.PageStoreType=="External Content and HTML HEAD"}checked="checked"{/if} ></td></tr>
 

 </table>
 
	
	

    </td></tr>

<tbody id="tbody_html_head"  style="{if $page_data.PageStoreType=="External Content and HTML HEAD"}display:none{/if}">

	     <tr class="title"><td colspan="2">{t}Page Properties{/t} [HTML HEAD]
	     </td><td>
	     	 <div class="general_options" style="float:right">
		   
		   <span  style="margin-right:10px;visibility:hidden"  id="save_edit_department_page_html_head" class="state_details">{t}Save{/t}</span>
		   <span style="margin-right:10px;visibility:hidden" id="reset_edit_department_page_html_head" class="state_details">{t}Reset{/t}</span>
		   
      </div>
	     
	     </td></tr>




	     <tr><td class="label">{t}Title{/t}:</td><td>
		 <div   >
		   <input  id="department_page_html_head_title"    value="{$page_data.PageTitle}" ovalue="{$page_data.PageTitle}"  />
		   
		   <div id="department_page_html_head_title_Container" style="" ></div>
		 </div>
	       </td><td><div id="department_page_html_head_title_msg"></div> </td></tr>
	    
	     <tr><td class="label">{t}Keywords{/t}:</td>
	       <td>
		 <div  style="height:60px" >
		   <textarea  id="department_page_html_head_keywords"  value="{$page_data.PageKeywords}" ovalue="{$page_data.PageKeywords}"  >{$page_data.PageKeywords}</textarea>
		   
		   <div id="department_page_html_head_keywords_Container" style="" ></div>
		 </div>
		 
		 
	     </td><td><div id="department_page_html_head_keywords_msg"></div></td></tr>





  </tbody>




<tr class="title"><td colspan="2">Content (Header)</td>
   <td>
		 <div class="general_options" style="float:right">
		   
		   <span  style="margin-right:10px;visibility:hidden"  id="save_edit_department_page_header" class="state_details">{t}Save{/t}</span>
		   <span style="margin-right:10px;visibility:hidden" id="reset_edit_department_page_header" class="state_details">{t}Reset{/t}</span>
		   
		 </div>
		 
	       </td>
</tr>


	     <tr><td class="label">{t}Title{/t}:</td><td>
		 <div   >
		   <input  id="department_page_header_store_title"   value="{$page_data.PageStoreTitle}" ovalue="{$page_data.PageStoreTitle}"  />
		   
		   <div id="department_page_header_store_title_Container" style="" ></div>
		 </div>
	       </td><td><div id="department_page_header_store_title_msg"></div></td>
	    
	       
	     </tr>
	     <tr><td class="label">{t}Subtitle{/t}:</td><td>
		 <div   >
		   <input  id="department_page_header_subtitle"   value="{$page_data.PageStoreSubtitle}" ovalue="{$page_data.PageStoreSubtitle}"  />
		   
		   <div id="department_page_header_subtitle_Container" style="" ></div>
		 </div>
		 
	     </td><td><div id="department_page_header_subtitle_msg"></div></td></tr>
	     <tr style="display:none"><td class="label">{t}Slogan{/t}:</td><td>
		 <div   >
		 <input  id="department_page_header_slogan"   value="{$page_data.PageStoreSlogan}" ovalue="{$page_data.PageStoreSlogan}"  />
		 <div id="department_page_header_slogan_msg"></div>
		 <div id="department_page_header_slogan_Container" style="" ></div>
		 </div>
	     </td></tr>
	     <tr style="display:none"><td class="label">{t}Short Introduction{/t}:</td><td>
		 <div   >
		   <input  id="department_page_header_resume"   value="{$page_data.PageStoreResume}" ovalue="{$page_data.PageStoreResume}"  />
		   <div id="department_page_header_resume_msg"></div>
		   <div id="department_page_header_resume_Container" style="" ></div>
		 </div>
	     </td></tr>
	     
	     
	   <tbody id="tbody_content"  style="{if $page_data.PageStoreType=="External Content and HTML HEAD"}display:none{/if}">
	     
	     
	     <tr class="title"><td colspan="2">Content</td>
	     <td>
		 <div class="general_options" style="float:right">
		   
		   <span  style="margin-right:10px;visibility:hidden"  id="save_edit_department_page_content" class="state_details">{t}Save{/t}</span>
		   <span style="margin-right:10px;visibility:hidden" id="reset_edit_department_page_content" class="state_details">{t}Reset{/t}</span>
		   
		 </div>
		 
	       </td>
	     </tr>
        <tr id="tr_offers" style="display:none" ><td class="label">{t}Offers{/t}:<td>
    <table class="options" style="float:left" >
      
      <td  {if $currency_type=='original'}class="selected"{/if} id="original"  >{t}Auto{/t}</td>
       <td {if $currency_type=='corparate_currency'}class="selected"{/if}  id="corparate_currency"  >{t}Do not show offers{/t}</td>


  </table>

</td></tr>
        
        <tr id="tr_new_products" style="display:none" ><td class="label">{t}New Products{/t}:<td>
     <table class="options" style="float:left" >
    
       <td  {if $currency_type=='original'}class="selected"{/if} id="original"  >{t}New Products{/t}</td>
       <td {if $currency_type=='corparate_currency'}class="selected"{/if}  id="corparate_currency"  >{t}Products back in stock{/t}</td>
       <td {if $currency_type=='corparate_currency'}class="selected"{/if}  id="corparate_currency"  >{t}Do not show any{/t}</td>


  </table>

</td></tr>



	     <tr><td spancols=2 class="label">{t}Department Description{/t}:<br/>HTML/Smarty</td><td>
		 <div style="height:200px"  >
		 <textarea  id="department_page_content_presentation_template_data"  style="width:450px" rows="10"  value="{$page_data.ProductPresentationTemplateData}" ovalue="{$page_data.ProductPresentationTemplateData}"  >{$page_data.ProductPresentationTemplateData}</textarea>
		 <div id="department_page_content_presentation_template_data_msg"></div>
		 <div id="department_page_content_presentation_template_data_Container" style="" ></div>
		 </div>
	       </td>
 

</tr>




<tr class="title"><td>Layout</td></tr>

 <tr><td></td></tr>
 
   <tr style="display:none"  ><td class="label">{t}Header Block Layout{/t}:</td>
  <td>
	<div style="float:left;width:125px;text-align:center;">
	<img style="border:1px solid #ccc" src="art/header_block_splited.png" alt="splited"/>
	{t}Splited{/t}
	</div>
	<div  style="float:left;width:125px;text-align:center">
	<img style="border:1px solid #ccc" src="art/header_block_tabbed.png" alt="tabbed"/>
	{t}Tabbed{/t}
	</div>
	<div  style="float:left;width:125px;text-align:center">
	<img style="border:1px solid #ccc" src="art/header_block_fluid_block.png" alt="fluid_block"/>
	{t}Fluid Block{/t}
	</div>
	
  </td>
  </tr>
  
  
 <tr><td class="label">{t}Products Layout{/t}:</td>
 <td>
 <table>
 <tr><td class="label">{t}Thumbnails{/t}:</td><td><input layout="thumbnails" id="checkbox_thumbnails" type="checkbox"  {if $page_data.ProductThumbnailsLayout=="Yes"}checked="checked"{/if} ></td></tr>
  <tr><td class="label">{t}List{/t}:</td><td><input layout="lists" id="checkbox_list" type="checkbox" {if $page_data.ProductListLayout=="Yes"}checked="checked"{/if}></td></tr>
 <tr><td class="label">{t}Slideshow:{/t}</td><td><input layout="slideshow" id="checkbox_slideshow" type="checkbox" {if $page_data.ProductSlideshowLayout=="Yes"}checked="checked"{/if}></td></tr>
 <tr><td class="label">{t}Handmade:{/t}</td><td><input layout="manual" id="checkbox_manual" type="checkbox" {if $page_data.ProductManualLayout=="Yes"}checked="checked"{/if} ></td></tr>

 </table>
 
	
	

    </td></tr>

  </tr>
  
  
  
  </tbody>
  
  <tbody id="layout_slideshow_options" style="{if $page_data.ProductSlideshowLayout=="No" or $page_data.PageStoreType=="External Content and HTML HEAD"   }display:none{/if}">
    <tr class="title"><td>{t}Slideshow Layout{/t}</td></tr>
    <tr><td class="label">{t}Products{/t}:<div style="float:left"><img style="border:1px solid #ccc" src="art/page_layout_slideshow.png"/></div></td>
    
   </tr>    
  </tbody>  
    <tbody id="layout_thumbnails_options" style="{if $page_data.ProductThumbnailsLayout=="No"   or $page_data.PageStoreType=="External Content and HTML HEAD" }display:none{/if}">
    <tr class="title"><td>{t}Thumbnails Layout{/t}</td></tr>
    <tr><td class="label">{t}Products{/t}:<div style="float:left"><img style="border:1px solid #ccc" src="art/page_layout_thumbnails.png"/></div></td>
    
   </tr>  
     </tbody>  
  <tbody id="layout_lists_options" style="{if $page_data.ProductListLayout=="No"   or $page_data.PageStoreType=="External Content and HTML HEAD" }display:none{/if}">
  <tr class="title"><td>{t}List Layout{/t}</td></tr>
    <tr><td class="label">{t}Products{/t}:<div style="float:left"><img style="border:1px solid #ccc" src="art/page_layout_list.png"/></div></td>
    
   </tr> 
     </tbody>  
      <tbody id="layout_manual_options" style="{if $page_data.ProductManualLayout=="No"  or $page_data.PageStoreType=="External Content and HTML HEAD" }display:none{/if}">
    <tr class="title"><td>{t}Handmade Product Layout{/t}</td></tr>
    
    <tr><td class="label">{t}HTML files{/t}:<div style="float:left"><img style="border:1px solid #ccc" src="art/page_layout_manual.png"/></div></td><td>
<div id="uiElements" style="display:inline;">
		<div id="uploaderContainer">
			<div id="uploaderOverlay"  style="position:absolute; z-index:2"></div>
			<div id="selectFilesLink" style="z-index:1"><a id="selectLink" href="#">Select Files</a></div>
		</div>

		<div id="uploadFilesLink"><a id="uploadLink" onClick="upload(); return false;" href="#">Upload Files</a></div>
</div>

<div id="simUploads" style="display:none"> Number of simultaneous uploads:
	<select id="simulUploads">
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>

		<option value="4">4</option>
	</select>
</div>

<div id="dataTableContainer"></div>

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
    	
    // Variable for holding the filelist.
	var fileList;
	
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
		
		// Allows multiple file selection in "Browse" dialog.
		uploader.setAllowMultipleFiles(true);
		
		// New set of file filters.
		var ff = new Array({description:"Images", extensions:"*.jpg;*.png;*.gif"},
		                   {description:"Videos", extensions:"*.avi;*.mov;*.mpg"});
		                   
		// Apply new set of file filters to the uploader.
		//uploader.setFileFilters(ff);
	}

	// Actually uploads the files. In this case,
	// uploadAll() is used for automated queueing and upload 
	// of all files on the list.
	// You can manage the queue on your own and use "upload" instead,
	// if you need to modify the properties of the request for each
	// individual file.
	function upload() {
	if (fileList != null) {
	
		uploader.setSimUploadLimit(parseInt(document.getElementById("simulUploads").value));
		uploader.uploadAll("http://localhost/dw/upload_files.php", "POST", {type:"Department Page",'id':{/literal}{$page_key}{literal}}, "Filedata");

	
	}	
	}
	
	// Fired when the user selects files in the "Browse" dialog
	// and clicks "Ok".
	function onFileSelect(event) {
		if('fileList' in event && event.fileList != null) {
			fileList = event.fileList;
			createDataTable(fileList);
		}
	}

	function createDataTable(entries) {
	  rowCounter = 0;
	  this.fileIdHash = {};
	  this.dataArr = [];
	  for(var i in entries) {
	     var entry = entries[i];
		 entry["progress"] = "<div style='height:5px;width:100px;background-color:#CCC;'></div>";
	     dataArr.unshift(entry);
	  }
	
	  for (var j = 0; j < dataArr.length; j++) {
	    this.fileIdHash[dataArr[j].id] = j;
	  }
	
	    var myColumnDefs = [
	        {key:"name", label: "File Name", sortable:false},
	     	{key:"size", label: "Size", sortable:false},
	     	{key:"progress", label: "Upload progress", sortable:false}
	    ];

	  this.myDataSource = new YAHOO.util.DataSource(dataArr);
	  this.myDataSource.responseType = YAHOO.util.DataSource.TYPE_JSARRAY;
      this.myDataSource.responseSchema = {
          fields: ["id","name","created","modified","type", "size", "progress"]
      };

	  this.singleSelectDataTable = new YAHOO.widget.DataTable("dataTableContainer",
	           myColumnDefs, this.myDataSource, {
	               caption:"Files To Upload",
	               selectionMode:"single"
	           });
	}

    // Do something on each file's upload start.
	function onUploadStart(event) {
	
	}
	
	// Do something on each file's upload progress event.
	function onUploadProgress(event) {
		rowNum = fileIdHash[event["id"]];
		prog = Math.round(100*(event["bytesLoaded"]/event["bytesTotal"]));
		progbar = "<div style='height:5px;width:100px;background-color:#CCC;'><div style='height:5px;background-color:#F00;width:" + prog + "px;'></div></div>";
		singleSelectDataTable.updateRow(rowNum, {name: dataArr[rowNum]["name"], size: dataArr[rowNum]["size"], progress: progbar});	
	}
	
	// Do something when each file's upload is complete.
	function onUploadComplete(event) {
		rowNum = fileIdHash[event["id"]];
		prog = Math.round(100*(event["bytesLoaded"]/event["bytesTotal"]));
		progbar = "<div style='height:5px;width:100px;background-color:#CCC;'><div style='height:5px;background-color:#F00;width:100px;'></div></div>";
		singleSelectDataTable.updateRow(rowNum, {name: dataArr[rowNum]["name"], size: dataArr[rowNum]["size"], progress: progbar});

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
	

	}
</script>

{/literal}

   

    
    </td></tr>
      </tbody>  
    
   </table>


      

    
   </div>   
   <div  id="d_discounts" class="edit_block" style="{if $edit!="discounts"}display:none{/if}"></div> 
   
   </div>
  
  

  
 <div id="the_table1" class="data_table" style=" clear:both">
  <span class="clean_table_title">{t}History{/t}</span>
  {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1  }
  <div  id="table1"   class="data_table_container dtable btable "> </div>
</div> 
 

  
</div> 


<div id="filtermenu0" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="rppmenu0" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

{include file='footer.tpl'}
