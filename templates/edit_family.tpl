{include file='header.tpl'}
<div id="bd" >
<div>
{include file='assets_navigation.tpl'}
<div class="branch"> 
 <span ><a  href="store.php?id={$store->id}">{$store->get('Store Name')}</a> &rarr; <a  href="department.php?id={$department->id}">{$department->get('Product Department Name')}</a> &rarr; {$family->get('Product Family Name')}  ({$family->get('Product Family Code')})</span>
 </div>


<div style="clear:left;margin:0 0px">
    <h1><span id="title_name">{$family->get('Product Family Name')}</span> (<span id="title_code">{$family->get('Product Family Code')}</span>)</h1>
</div>
</div>


<input type='hidden' id="family_key" value="{$family->id}">

<div id="msg_div"></div>
  <ul class="tabs" id="chooser_ul" style="clear:both">
    <li> <span class="item {if $edit=='details'}selected{/if}"  id="details">  <span> {t}Description{/t}</span></span></li>
    <li> <span class="item {if $edit=='discounts'}selected{/if}"  id="discounts">  <span> {t}Discounts{/t}</span></span></li>
    <li> <span class="item {if $edit=='pictures'}selected{/if}" id="pictures"  ><span>  {t}Pictures{/t}</span></span></li>
    <li> <span class="item {if $edit=='products'}selected{/if}" id="products"  ><span> {t}Products{/t}</span></span></li>
    <li> <span class="item {if $edit=='web'}selected{/if} " id="web" ><span> {t}Web Pages{/t}</span></span></li>
  </ul>
 <div class="tabbed_container" > 
   
      
      
      
    <div  id="d_details" class="edit_block" style="{if $edit!='details'}display:none{/if}"  >
      
    
      <div class="general_options" style="float:right">
	
	<span  style="margin-right:10px;visibility:hidden"  id="save_edit_family" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;visibility:hidden" id="reset_edit_family" class="state_details">{t}Reset{/t}</span>
	
      </div>



    <table style="clear:both;width:800px" class="edit">
    
    <tr>
<td></td>
<td style="text-align:right;color:#777;font-size:90%">
<div id="delete_family_warning" style="border:1px solid red;padding:5px 5px 15px 5px;color:red;display:none">
<h2>{t}Delete Family{/t}</h2>
<p>
{t}This operation cannot be undone{/t}.<br> {t}Would you like to proceed?{/t}
</p>
<p id="delete_family_msg"></p>
<span id="cancel_delete_family"  style="cursor:pointer;display:none;font-weight:800" >{t}No i dont want to delete it{/t}</span>
<span id="save_delete_family"  style="cursor:pointer;display:none;margin-left:20px;">{t}Yes, delete it!{/t}</span>
<p id="deleting" style="display:none;">{t}Deleting family, wait please{/t}</p>
</div>
<span id="delete_family" class="state_details" style="{if $family->get_number_products()}display:none{/if}">{t}Delete Family{/t}</span>

</td>
<td>
 <div class="general_options" style="float:right">
	        <span  style="margin-right:10px;visibility:hidden"  id="save_edit_family" class="state_details">{t}Save{/t}</span>
	        <span style="margin-right:10px;visibility:hidden" id="reset_edit_family" class="state_details">{t}Reset{/t}</span>
      </div>
</td>
</tr>


    
    
    
    
    
    
    
    
      <tr>
      <td class="label" style="width:100px">{t}Family Code{/t}:</td><td>
	 <div   >

	      <input  
		 id="code" 
		 changed=0 
		 type='text' 
		 class='text' 
		  
		 MAXLENGTH="16" 
		 value="{$family->get('Product Family Code')}" 
		 ovalue="{$family->get('Product Family Code')}"  
		 />
		 <div id="code_Container" style="" ></div>
         </div>
	    </td>
	     <td id="code_msg" class="edit_td_alert" style="width:300px"></td>

	  </tr>
	  <tr><td class="label">{t}Family Name{/t}:</td><td>
	      <div   >
		<input   
		   id="name" 
		  
		   changed=0 
		   type='text'  
		   MAXLENGTH="255" 
		     
		   class='text' 
		   value="{$family->get('Product Family Name')}"  
		   ovalue="{$family->get('Product Family Name')}"  
		   />
		<div id="name_Container" style="" ></div>
              </div>
	    </td>
	     <td id="name_msg" class="edit_td_alert" style="width:300px"></td>
	  </tr>
	  <tr><td class="label">{t}Family Char{/t}:</td><td>
	      <div   >
		<input   
		   id="special_char" 
		 
		   type='text'  
		   MAXLENGTH="255" 
		     
		   class='text' 
		   value="{$family->get('Product Family Special Characteristic')}"  
		   ovalue="{$family->get('Product Family Special Characteristic')}"  
		   />
		<div id="special_char_Container" style="" ></div>
              </div>
	    </td>
	     <td id="special_char_msg" class="edit_td_alert" style="width:300px"></td>
	  </tr>
      <tr style="height:80px"><td class="label">{t}Description{/t}:</td><td>
	      <div   >
		<textarea   
		   id="description" 
		   name="description" 
		   rows="5"
		     
		   class='text' 
		   value="{$family->get('Product Family Description')}"  
		   ovalue="{$family->get('Product Family Description')}"  
		   />{$family->get('Product Family Description')}</textarea>
		<div id="description_Container" style="" ></div>
              </div>

	    </td>
	     <td id="description_msg" class="edit_td_alert" style="width:300px"></td>
	  </tr>
	  
	  
	  
	  
 <tr class="title"><td colspan=5>{t}Categories{/t}</td></tr>
 
 <tr class="first"><td style="width:180px" class="label">{t}Department{/t}:</td>
   <td  style="text-align:left">
 <span id="current_department_code">{$family->get('Product Family Main Department Code')}</span> <img id="edit_family_department" id="family" style="margin-left:5px;cursor:pointer" src="art/icons/edit.gif" alt="{t}Edit{/t}" title="{t}Edit{/t}" /s>
   </td>
   <td style="width:200px" id="Product_Name_msg" class="edit_td_alert"></td>
 </tr>
 



	  
	  
	  
    </table>
    </div>
    
     <div  id="d_pictures" class="edit_block" style="{if $edit!='pictures'}display:none{/if}" >
          {include file='edit_images_splinter.tpl'}
     </div>
    
    
    <div  class="edit_block" style="margin:0;padding:0 0px;{if $edit!="discounts"}display:none{/if}"  id="d_discounts">
		<div  class="new_item_dialog"  id="new_deal_dialog" style="display:none">
	   <div id="new_deal_messages" class="messages_block"></div>
	   <table class="edit" >
	     <tr><td>{t}Deal Name{/t}:</td><td><input  id="new_deal_name" onKeyUp="new_deal_changed(this)"    onMouseUp="new_deal_changed(this)"  onChange="new_deal_changed(this)"  changed=0 type='text' class='text'  MAXLENGTH="16" value="" /></td></tr>
	     <tr><td>{t}Deal Description{/t}:</td><td><input   id="new_deal_description" onKeyUp="new_deal_changed(this)"    onMouseUp="new_deal_changed(this)"  onChange="new_deal_changed(this)" changed=0 type='text'  MAXLENGTH="255"   class='text' value="" /></td>
	     </tr>
	  </table>
	 </div>
	 
	 <div   class="data_table" sxtyle="margin:25px 10px;">
	   <span class="clean_table_title">{t}Deals{/t}</span>
	  <table class="options" style="float:right;padding:0;margin:0">
	    <tr>
	      <td  id="add_deal">Add Deal</td>
	      <td  style="display:none" id="save_new_deal">Save New Deal</td>
	      <td  style="display:none" id="cancel_add_deal">Cancel</td>
	    </tr>
	  </table>
	  <div  class="clean_table_caption"  style="clear:both;">
	    <div style="float:left;"><div id="table_info4" class="clean_table_info"><span id="rtext4"></span> <span class="rtext_rpp" id="rtext_rpp4"></span> <span class="filter_msg"  id="filter_msg4"></span></div></div>
	    <div class="clean_table_filter" style="display:none" id="clean_table_filter4"><div class="clean_table_info"><span id="filter_name4">{$filter_name4}</span>: <input style="border-bottom:none" id='f_input4' value="{$filter_value0}" size=10/><div id='f_container4'></div></div></div>
	    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator4"></span></div></div>
	  </div>
	  <div  id="table4"   class="data_table_container dtable btable "> </div>
	 </div>
      </div>
	  
      <div  class="edit_block" style="margin:0;padding:0 0px;{if $edit!="web"}display:none{/if}"  id="d_web">

      <div class="general_options" style="float:right">
	     <span style="margin-right:10px;"   id="new_family_page" class="state_details" >{t}Create Page{/t}</span>
	        <span style="margin-right:10px;{if $number_of_pages<=1}display:none{/if}" " id="page_list" class="state_details"><a href="edit_family.php?id={$family->id}">{t}Page List{/t} ({$number_of_pages})</a></span>

	   </div>
	
	
	
		<input type='hidden' id="site_key" value="{$site_key}">
				<input type='hidden' id="page_key" value="{$page_key}">



<div   class="data_table" style="clear:both;{if $page_key}display:none{/if}" >
	  <span class="clean_table_title">{t}Pages{/t}</span> 
	  <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
   {include file='table_splinter.tpl' table_id=6 filter_name=$filter_name6 filter_value=$filter_value6  }
	  <div  id="table6"  style="font-size:90%" class="data_table_container dtable btable "> </div>
	</div>

	   <table class="edit"  id="edit_family_page"  style="width:100%;clear:both;{if !$page_key}display:none{/if}" page_key="{$page_key}"   >
	     <tr class="title"><td colspan="2">{t}Page Properties{/t}
	     </td>
	     <td>
	      <div class="general_options" style="float:right">
		   
		   <span  style="margin-right:10px;visibility:hidden"  id="save_edit_family_page_properties" class="state_details">{t}Save{/t}</span>
		   <span style="margin-right:10px;visibility:hidden" id="reset_edit_family_page_properties" class="state_details">{t}Reset{/t}</span>
		   
      </div>
	     </td>
	     
	     </tr>

  <tr><td width="180px" class="label">{t}Page Code{/t}:</td>
	     <td>
		 <div   >     
		   <input  id="family_page_properties_page_code"   value="{$page_data.PageCode}" ovalue="{$page_data.PageCode}"  />
		   
		   <div id="family_page_properties_page_code_Container" style="" ></div>
		 </div>
	       </td><td><div id="family_page_properties_page_code_msg"></div></td></tr>

	     <tr><td width="180px" class="label">{t}URL{/t}:</td>
	     <td>
		 <div   >
		   <input  id="family_page_properties_url"   value="{$page_data.PageURL}" ovalue="{$page_data.PageURL}"  />
		   
		   <div id="family_page_properties_url_Container" style="" ></div>
		 </div>
	       </td><td><div id="family_page_properties_url_msg"></div>
      </td></tr>


<tr><td width="180px" class="label">{t}Link Title{/t}:</td>
	     <td>
		 <div   >
		   <input  id="family_page_properties_link_title"   value="{$page_data.PageShortTitle}" ovalue="{$page_data.PageShortTitle}"  />
		   
		   <div id="family_page_properties_link_title_Container" style="" ></div>
		 </div>
	       </td><td><div id="family_page_properties_link_title_msg"></div>
      </td></tr>


<tr><td class="label">{t}Page Type{/t}:</td>
 <td>
 <table>
 <tr><td style="padding:0;" class="label">{t}External body & HTML HEAD{/t}:</td><td><input layout="thumbnails" id="checkbox_thumbnails" type="checkbox"  {if $page_data.PageStoreType=="External Content and HTML HEAD"}checked="checked"{/if} ></td></tr>
 

 </table>
 
	
	

    </td></tr>

<tbody id="tbody_html_head"  style="{if $page_data.PageStoreType=="External Content and HTML HEAD"}display:none{/if}">

	     <tr class="title"><td colspan="2">{t}Page Properties{/t} [HTML HEAD]
	     </td><td>
	     	 <div class="general_options" style="float:right">
		   
		   <span  style="margin-right:10px;visibility:hidden"  id="save_edit_family_page_html_head" class="state_details">{t}Save{/t}</span>
		   <span style="margin-right:10px;visibility:hidden" id="reset_edit_family_page_html_head" class="state_details">{t}Reset{/t}</span>
		   
      </div>
	     
	     </td></tr>




	     <tr><td class="label">{t}Title{/t}:</td><td>
		 <div   >
		   <input  id="family_page_html_head_title"    value="{$page_data.PageTitle}" ovalue="{$page_data.PageTitle}"  />
		   
		   <div id="family_page_html_head_title_Container" style="" ></div>
		 </div>
	       </td><td><div id="family_page_html_head_title_msg"></div> </td></tr>
	    
	     <tr><td class="label">{t}Keywords{/t}:</td>
	       <td>
		 <div  style="height:60px" >
		   <textarea  id="family_page_html_head_keywords"  value="{$page_data.PageKeywords}" ovalue="{$page_data.PageKeywords}"  >{$page_data.PageKeywords}</textarea>
		   
		   <div id="family_page_html_head_keywords_Container" ></div>
		 </div>
		 
		 
	     </td><td><div id="family_page_html_head_keywords_msg"></div></td></tr>





  </tbody>




<tr class="title"><td colspan="2">Content (Header)</td>
   <td>
		 <div class="general_options" style="float:right">
		   
		   <span  style="margin-right:10px;visibility:hidden"  id="save_edit_family_page_header" class="state_details">{t}Save{/t}</span>
		   <span style="margin-right:10px;visibility:hidden" id="reset_edit_family_page_header" class="state_details">{t}Reset{/t}</span>
		   
		 </div>
		 
	       </td>
</tr>


	     <tr><td class="label">{t}Title{/t}:</td><td>
		 <div   >
		   <input  id="family_page_header_store_title"   value="{$page_data.PageStoreTitle}" ovalue="{$page_data.PageStoreTitle}"  />
		   
		   <div id="family_page_header_store_title_Container" style="" ></div>
		 </div>
	       </td><td><div id="family_page_header_store_title_msg"></div></td>
	    
	       
	     </tr>
	     
	       <tr><td class="label">{t}Found In{/t}:</td>
	       <td>
	        <div style="float:right"  class="general_options"><span id="add_other_found_in_page" class="state_details">Add other page</span></div>
	       <table >
	       {if $page_key}
		    {foreach from=$page->found_in() item=found_in_page}
               <tr><td style="padding:0">{$found_in_page.found_in_label}</td>
               <td style="padding:0;padding-left:10px"><img onclick="delete_found_in_page({$found_in_page.found_in_key})" style="cursor:pointer" src="art/icons/cross.png" alt="{t}Remove{/t}" title="{t}Remove{/t}"  /></td>
             
               </tr>
            {/foreach}
            {/if}
	       </table>
	      
	       </td>
	       <td></td>
	       </td>
	    
	       
	     </tr>
	     
	     
	     <tr style="display:none"><td class="label">{t}Subtitle{/t}:</td><td>
		 <div   >
		   <input  id="family_page_header_subtitle"   value="{$page_data.PageStoreSubtitle}" ovalue="{$page_data.PageStoreSubtitle}"  />
		   
		   <div id="family_page_header_subtitle_Container" style="" ></div>
		 </div>
		 
	     </td><td><div id="family_page_header_subtitle_msg"></div></td></tr>
	     <tr style="display:none"><td class="label">{t}Slogan{/t}:</td><td>
		 <div   >
		 <input  id="family_page_header_slogan"   value="{$page_data.PageStoreSlogan}" ovalue="{$page_data.PageStoreSlogan}"  />
		 <div id="family_page_header_slogan_msg"></div>
		 <div id="family_page_header_slogan_Container" style="" ></div>
		 </div>
	     </td></tr>
	     <tr style="display:none"><td class="label">{t}Short Introduction{/t}:</td><td>
		 <div   >
		   <input  id="family_page_header_resume"   value="{$page_data.PageStoreResume}" ovalue="{$page_data.PageStoreResume}"  />
		   <div id="family_page_header_resume_msg"></div>
		   <div id="family_page_header_resume_Container" style="" ></div>
		 </div>
	     </td></tr>
	     
	     
	   <tbody id="tbody_content"  style="{if $page_data.PageStoreType=="External Content and HTML HEAD"}display:none{/if}">
	     
	     
	     <tr class="title"><td colspan="2">Content</td>
	     <td>
		 <div class="general_options" style="float:right">
		   
		   <span  style="margin-right:10px;visibility:hidden"  id="save_edit_family_page_content" class="state_details">{t}Save{/t}</span>
		   <span style="margin-right:10px;visibility:hidden" id="reset_edit_family_page_content" class="state_details">{t}Reset{/t}</span>
		   
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



	     <tr><td spancols=2 class="label">{t}Family Description{/t}:<br/>HTML/Smarty</td><td>
		 <div style="height:200px"  >
		 <textarea  id="family_page_content_presentation_template_data"  style="width:450px" rows="10"  value="{$page_data.ProductPresentationTemplateData}" ovalue="{$page_data.ProductPresentationTemplateData}"  >{$page_data.ProductPresentationTemplateData}</textarea>
		 <div id="family_page_content_presentation_template_data_msg"></div>
		 <div id="family_page_content_presentation_template_data_Container" style="" ></div>
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
		uploader.uploadAll("http://localhost/dw/upload_files.php", "POST", {type:"Family Page",'id':{/literal}{$page_key}{literal}}, "Filedata");

	
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
      <div  class="edit_block" style="margin:0;padding:0 0px;{if $edit!="products"}display:none{/if}"  id="d_products">
      
      
      <div class="general_options" style="float:right;text-align:right">
	    <span  style="margin-right:10px;"  id="show_new_product_dialog_button" onClick="show_new_product_dialog()" class="state_details">{t}Create New Product{/t}</span>
	    <span  style="margin-right:10px;"  id="import_new_product" class="state_details">{t}Import Products (CSV){/t}</span>

		<span  style="margin-right:10px;visibility:hidden"  id="save_new_product" class="state_details">{t}Save New Product{/t}</span>
  	    <span style="margin-right:10px;visibility:hidden" id="cancel_new_product"  class="state_details">{t}Close New Product{/t}</span>
</div>
      
      
      
	  
	
	  
	  <div   style="margin:0 0 10px 0;padding:10px;border:1px solid #ccc;padding-top:0;clear:both;xdisplay:none"  id="new_product_dialog" >
	    <h2>New Product</h2>
	  <div id="new_product_dialog_msg" style="display:none" class="messages_block"></div>

	   <table class="edit" style="width:100%" border=1 >
	  <tbody  style="display:none" >
	   <tr class="title"><td colspan=5>{t}Parts{/t}</td></tr>
	<tr style="height:40px"><td style="vertical-align:middle" class="label">{t}Parts{/t}:</td><td style="vertical-align:middle">
	    <span class="save" onclick="create_part()">Create Part</span>
	    
	    <span class="save"  onclick="assing_part()">Assign Part</span>


        </td></tr>

	  
	  	   <tr class="title"><td colspan=5>{t}Description{/t}</td></tr>
</tbody>


    <tr><td style="width:160px" class="label" >{t}Product Code{/t}:</td><td>
	 <div >

	      <input  
	       style="width:100%"
		 id="product_code" 
		 changed=0 
		 type='text' 
		 class='text' 
		 MAXLENGTH="16" 
		 value="{$family->get_next_product_code()}" 
		 ovalue=""  
		 />
		 <div id="product_code_Container" style="" ></div>
         </div>
	    </td>
	     <td id="product_code_msg" class="edit_td_alert" style="width:300px"></td>

	  </tr>
	  <tr><td class="label">{t}Product Name{/t}:</td><td>
	      <div   >
		<input   
		style="width:100%"
		   id="product_name" 
		  
		   changed=0 
		   type='text'  
		   MAXLENGTH="255" 
		     
		   class='text' 
		   value=""  
		   ovalue=""  
		   />
		<div id="product_name_Container" style="" ></div>
              </div>
	    </td>
	     <td id="product_name_msg" class="edit_td_alert" style="width:300px"></td>
	  </tr>   
        <tr >
        <td class="label">{t}Special Characteristic{/t}:</td><td>
	      <div   >
		<input   
		style="width:100%"
		   id="product_special_char" 
		  
		   changed=0 
		   type='text'  
		   MAXLENGTH="255" 
		     
		   class='text' 
		   value=""  
		   ovalue=""  
		   />
		<div id="product_special_char_Container" style="" ></div>
              </div>
	    </td>
	     <td id="product_special_char_msg" class="edit_td_alert" style="width:300px"></td>
	  </tr>  



    <tr >
        <td class="label">{t}Units Per Case{/t}:</td><td>
	      <div   >
		<input   
		
		   id="product_units" 
		  
		   changed=0 
		   type='text'  
		   MAXLENGTH="255" 
		     
		   class='text' 
		   value="1"  
		   ovalue="1"  
		   />
		<div id="product_units_Container" style="" ></div>
              </div>
	    </td>
	     <td id="product_units_msg" class="edit_td_alert" style="width:300px"></td>
	  </tr>  



    <tr >
        <td class="label">{t}Price Per Unit{/t}:</td><td>
	      <div   >
		<input   
		   id="product_price" 
		  
		   changed=0 
		   type='text'  
		   MAXLENGTH="255" 
		     
		   class='text' 
		   value=""  
		   ovalue=""  
		   />
		<div id="product_price_Container" style="" ></div>
              </div>
	    </td>
	     <td id="product_price_msg" class="edit_td_alert" style="width:300px"></td>
	  </tr>  



  <tr >
        <td class="label">{t}Retail Price{/t}:</td><td>
	      <div   >
		<input   
		   id="product_retail_price" 
		  
		   changed=0 
		   type='text'  
		   MAXLENGTH="255" 
		     
		   class='text' 
		   value=""  
		   ovalue=""  
		   />
		<div id="product_retail_price_Container" style="" ></div>
              </div>
	    </td>
	     <td id="product_retail_price_msg" class="edit_td_alert" style="width:300px"></td>
	  </tr>  




	   <tr style="display:none"><td class="label">{t}Type of Unit{/t}:</td><td>
		
<div class="options" style="margin:5px 0;display:inline">
  {foreach from=$units_tipo item=unit_tipo key=part_id }
<span {if $unit_tipo.selected}class="selected"{/if} id="unit_tipo_{$unit_tipo.name}">{$unit_tipo.fname}</span>
{/foreach}
</div>

   <select style="display:none" name="units_tipo"  id="units_tipo" >
	      {foreach from=$units_tipo item=tipo key=tipo_id }
	      <option value="{$tipo_id}">{$tipo}</option>
	      {/foreach}
	</select></td></tr>

	
</table>


	  
	






	</div>



	<div   class="data_table" style="clear:both">
	  
	  <span class="clean_table_title">{t}Products{/t}</span> 
	  
	
	  
	  
	  
	   
      <div id="table_type" class="table_type">
        <div  style="font-size:90%"   id="transaction_chooser" >

            <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.Historic}selected{/if} label_family_products_changes"  id="elements_historic" table_type="historic"   >{t}Historic{/t} (<span id="elements_historic_number">{$elements_number.Historic}</span>)</span>
            <span style="float:right;margin-left:20px;" class=" table_type transaction_type state_details {if $elements.Discontinued}selected{/if} label_family_products_discontinued"  id="elements_discontinued" table_type="discontinued"   >{t}Discontinued{/t} (<span id="elements_discontinued_number">{$elements_number.Discontinued}</span>)</span>
            <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Private}selected{/if} label_family_products_private"  id="elements_private" table_type="private"   >{t}Private Sale{/t} (<span id="elements_private_number">{$elements_number.Private}</span>)</span>
            <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.NoSale}selected{/if} label_family_products_nosale"  id="elements_nosale" table_type="nosale"   >{t}Not for Sale{/t} (<span id="elements_nosale_number">{$elements_number.NoSale}</span>)</span>
            <span style="float:right;margin-left:20px" class=" table_type transaction_type state_details {if $elements.Sale}selected{/if} label_family_products_sale"  id="elements_sale" table_type="sale"   >{t}Public Sale{/t} (<span id="elements_notes_number">{$elements_number.Sale}</span>)</span>

        </div>
     </div>
	  
	  
	  <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
	  
  

	  <table style="float:left;margin:0 0 0 0px ;padding:0"  class="options" >
	    <tr>
	        <td  {if $view=='view_state'}class="selected"{/if} id="view_state" >{t}State{/t}</td>
	        <td {if $view=='view_name'}class="selected"{/if}  id="view_name"  >{t}Name{/t}</td>
	        <td  {if $view=='view_price'}class="selected"{/if}  id="view_price"  >{t}Price{/t}</td>
	    </tr>
	  </table>
	  </div>
	  
	  
	  
   {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0  }

	  <div  id="table0"  style="font-size:90%" class="data_table_container dtable btable "> </div>







      </div>

	 
	  
 
 
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

<div id="dialog_new_product_choose" style="padding:10px;display:none">
  <div id="new_product_choose_msg"></div>
  {t}Create new product{/t}:
  <div class="general_options" >
  <table  style="margin:10px">
    <tr>
        <td> <span  style="margin:0 10px" class="unselectable_text state_details" onClick="new_product_from_part()" >{t}From a Part{/t}</span></td>
           <td > <span class="unselectable_text state_details" onClick="new_product_from_scratch()" >{t}From Scratch{/t}</span></td>

   </tr>
  
  </table>
  </div>
</div>

	<div id="dialog_new_part"  class=""  style="display:none;padding:10px;">
		<h2>New Part</h2>
		    <div class="general_options" style="float:right">

		      <span style="margin-right:10px;display:none"  id="save_area" class="state_details">{t}Save{/t}</span>
		      <span style="margin-right:10px;" id="close_add_area" class="state_details">{t}Close Dialog{/t}</span>
		      
		    </div>
		  
		  <table class="edit" >
		    <tr><td class="label" style="width:7em">{t}Description{/t}:</td><td><input name="code" id="new_part_description"  name="code" changed=0 type='text' class='text' SIZE="16" value="" MAXLENGTH="16"/></td></tr>
    		    <tr><td class="label" >{t}Gross Weight{/t}:</td><td><input name="name"  id="new_name"     name="code" changed=0  type='text'  SIZE="6" MAXLENGTH="80" class='text' value=""   /> Kg</td></tr>
		    <tr><td class="label">{t}Supplier{/t}:</td>
		      <td  style="text-align:left">
			<div  style="width:15em;position:relative;top:00px" >
			  <input id="supplier" style="text-align:left;width:18em" type="text">
			  <div id="supplier_container" style="" ></div>
			</div>
		      </td>
		    </tr>
		    <input id="supplier_key" value="1" type="hidden">
		    <tr><td>
		      </td><td>
			<table border=0 class="edit">
			  <tr><td class="label" style="width:4em">{t}Code{/t}:</td><td><input  id="supplier_product_code"  SIZE="4" type='text'  MAXLENGTH="20" class='text' /></tr>
			  <tr><td class="label">{t}Cost{/t}:</td><td><input  id="supplier_produc_cost"  SIZE="4" type='text'  MAXLENGTH="20" class='text' /></tr>
			  <tr><td class="label">{t}Name{/t}:</td><td><input  id="supplier_produc_name"  SIZE="12" type='text'  MAXLENGTH="20" class='text' /></tr>
			  <tr><td class="label">{t}Description{/t}:</td><td><textarea  id="supplier_product_description"  SIZE="4" type='text'  MAXLENGTH="20" class='text' ></textarea></tr>
			</table>
			
		      </td>
		    </tr>
		  </table>
		  </div>


<div id="dialog_family_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Family List{/t}</span>
            {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2}
            <div  id="table2"   class="data_table_container dtable btable "> </div>
        </div>
    </div>
 </div>
 
 <div id="dialog_page_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none;width:500px">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Page List{/t}</span>
            {include file='table_splinter.tpl' table_id=7 filter_name=$filter_name7 filter_value=$filter_value7}
            <div  id="table7"   class="data_table_container dtable btable "> </div>
        </div>
    </div>
 </div>

{include file='footer.tpl'}
