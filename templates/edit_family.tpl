{include file='header.tpl'}

<div id="bd" >

<div class="search_box" style="margin-top:15px">
  <div class="general_options">
    {foreach from=$general_options_list item=options }
        {if $options.tipo=="url"}
            <span onclick="window.location.href='{$options.url}'" >{$options.label}</span>
        {else}
            <span  id="{$options.id}" state="{$options.state}">{$options.label}</span>
        {/if}
    {/foreach}
    </div>
</div>
<div style="clear:left;margin:0 0px">
    <h1>{t}Editing Family{/t}: <span id="title_name">{$family->get('Product Family Name')}</span> (<span id="title_code">{$family->get('Product Family Code')}</span>)</h1>
</div>


<div id="msg_div"></div>
  <ul class="tabs" id="chooser_ul" style="clear:both">
    <li> <span class="item {if $edit=='details'}selected{/if}"  id="details">  <span> {t}Description{/t}</span></span></li>
    <li> <span class="item {if $edit=='discounts'}selected{/if}"  id="discounts">  <span> {t}Discounts{/t}</span></span></li>
    <li> <span class="item {if $edit=='pictures'}selected{/if}" id="pictures"  ><span>  {t}Pictures{/t}</span></span></li>
    <li> <span class="item {if $edit=='products'}selected{/if}" id="products"  ><span> {t}Products{/t}</span></span></li>
    <li> <span class="item {if $edit=='web'}selected{/if} " id="web" ><span> {t}Web Pages{/t}</span></span></li>
  </ul>
 <div class="tabbed_container" > 
    <div id="info_name" style="margin-left:20px;float:left;width:260px;{if !($edit=='discounts' or $edit=='pictures')  }display:none{/if}">
	<table    class="show_info_product">
	  <tr>
	    <td>{t}Family Code{/t}:</td><td  class="aright">{$family->get('Product Family Code')}</td>
	  </tr>
	  <tr>
	    <td>{t}Family Name{/t}:</td><td  class="aright">{$family->get('Product Family Name')}</td>
	  </tr>
	</table>
      </div>
    <div  id="d_details" class="edit_block" style="{if $edit!='details'}display:none{/if}"  >
      
    
      <div class="general_options" style="float:right">
	
	<span  style="margin-right:10px;visibility:hidden"  id="save_edit_family" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;visibility:hidden" id="reset_edit_family" class="state_details">{t}Reset{/t}</span>
	
      </div>



    <table styel="clear:both" class="edit">
      <tr><td class="label" >{t}Family Code{/t}:</td><td>
	 <div  style="width:15em" >

	      <input  
		 id="code" 
		 changed=0 
		 type='text' 
		 class='text' 
		 style="width:15em" 
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
	      <div  style="width:30em" >
		<input   
		   id="name" 
		  
		   changed=0 
		   type='text'  
		   MAXLENGTH="255" 
		   style="width:30em"  
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
	      <div  style="width:30em" >
		<input   
		   id="special_char" 
		 
		   type='text'  
		   MAXLENGTH="255" 
		   style="width:30em"  
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
	      <div  style="width:30em" >
		<textarea   
		   id="description" 
		   name="description" 
		   rows="5"
		   style="width:30em"  
		   class='text' 
		   value="{$family->get('Product Family Description')}"  
		   ovalue="{$family->get('Product Family Description')}"  
		   />{$family->get('Product Family Description')}</textarea>
		<div id="description_Container" style="" ></div>
              </div>

	    </td>
	     <td id="description_msg" class="edit_td_alert" style="width:300px"></td>
	  </tr>






    
      

    </table>
    </div>
     <div  id="d_pictures" class="edit_block" style="{if $edit!='pictures'}display:none{/if}" >


{include file='edit_images_splinter.tpl'}

  
  <div  id="images" class="edit_images" principal="{$data.principal_image}" >
    {foreach from=$images item=image  name=foo}
    <div id="image{$image.id}" class="image"  >
      <div>{$image.name}</div>
      <img class="picture" style="border:none"    src="{$image.filename}" width="160"    /> 
      <div >
	<span>{$image.caption}</span> 
	<img  onClick="show_edit_caption({$image.id})" src="art/icons/edit.gif" style="cursor:pointer;position:relative;bottom:2px">
	<div style="display:none">
	<textarea class="caption" style="width:160px;margin-bottom:5px" onkeydown="caption_changed(this)" id="img_caption{$image.id}" image_id="{$image.id}" ovalue="{$image.caption}">{$image.caption} </textarea>
	<img style="vertical-align:top;"  class="caption" id="save_img_caption{$image.id}" onClick="save_image('img_caption',{$image.id})" title="{t}Save caption{/t}" alt="{t}Save caption{/t}"   src="art/icons/disk.png">
	<img style="vertical-align:top"  class="caption" id="save_img_caption{$image.id}" onClick="save_image('img_caption',{$image.id})" title="{t}Save caption{/t}" alt="{t}Save caption{/t}"   src="art/icons/bullet_come.png">
	</div>
      </div>
      <div class="operations">

	{if $image.is_principal=='Yes'}
	<span class="img_set_principal"  ><img id="img_set_principal{$image.id}" onClick="set_image_as_principal(this)" title="{t}Main Image{/t}" image_id="{$image.id}" principal="1" src="art/icons/asterisk_orange.png"></span>
	{else}
	<span  class="img_set_principal" style="cursor:pointer"  >
	  <img id="img_set_principal{$image.id}" onClick="set_image_as_principal(this)" title="{t}Set as the principal image{/t}" image_id="{$image.id}" principal="0" src="art/icons/picture_empty.png"></span>
	{/if}
	<span style="cursor:pointer;" onClick="delete_image({$image.id},'{$image.name}')"><img src="art/icons/delete.png" alt="{t}Delete{/t}" title="{t}Delete{/t}"></span>

      </div>
     
    
    </div>
    {/foreach}
    <div style="clear:both"></div>
  </div>



</div>
      <div  class="edit_block" style="margin:0;padding:0 0px;{if $edit!="discounts"}display:none{/if}"  id="d_discounts">
		<div  class="new_item_dialog"  id="new_deal_dialog" style="display:none">
	   <div id="new_deal_messages" class="messages_block"></div>
	   <table class="edit" >
	     <tr><td>{t}Deal Name{/t}:</td><td><input  id="new_deal_name" onKeyUp="new_deal_changed(this)"    onMouseUp="new_deal_changed(this)"  onChange="new_deal_changed(this)"  changed=0 type='text' class='text' style="width:15em" MAXLENGTH="16" value="" /></td></tr>
	     <tr><td>{t}Deal Description{/t}:</td><td><input   id="new_deal_description" onKeyUp="new_deal_changed(this)"    onMouseUp="new_deal_changed(this)"  onChange="new_deal_changed(this)" changed=0 type='text'  MAXLENGTH="255" style="width:30em"  class='text' value="" /></td>
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
	     <span style="margin-right:10px;{if $family->get('Product Family Page Key')}display:none{/if}"  onclick="" id="web_create" class="state_details">{t}Create Page{/t}</span>
	  
	   </div>
	

	{if $family->get('Product Family Page Key')}

	
	   <table class="edit">
	     <tr class="title"><td colspan="2">{t}Page Properties{/t} [HTML HEAD]



	     </td></tr>
	     <tr><td class="label">{t}Title{/t}:</td><td>
		 <div  style="width:15em" >
		   <input  id="family_page_html_head_title"  style="width:30em" MAXLENGTH="64" value="{$page_data.PageTitle}" ovalue="{$page_data.PageTitle}"  />
		   <div id="family_page_html_head_title_msg"></div>
		   <div id="family_page_html_head_title_Container" style="" ></div>
		 </div>
	       </td><td>
		 <div class="general_options" style="float:right">
		   
		   <span  style="margin-right:10px;visibility:hidden"  id="save_edit_family_page_html_head" class="state_details">{t}Save{/t}</span>
		   <span style="margin-right:10px;visibility:hidden" id="reset_edit_family_page_html_head" class="state_details">{t}Reset{/t}</span>
		   
      </div>

      </td></tr>
	    
	     <tr><td class="label">{t}Keywords{/t}:</td>
	       <td>
		 <div  style="width:30em" >
		   <textarea  id="family_page_html_head_keywords"  style="width:30em" MAXLENGTH="24" value="{$page_data.PageKeywords}" ovalue="{$page_data.PageKeywords}"  >{$page_data.PageKeywords}</textarea>
		   <div id="family_page_html_head_keywords_msg"></div>
		   <div id="family_page_html_head_keywords_Container" style="" ></div>
		 </div>
		 
		 
	     </td></tr>


<tr class="title"><td colspan="2">Content (Header)</td></tr>


	     <tr><td class="label">{t}Title{/t}:</td><td>
		 <div  style="width:15em" >
		   <input  id="family_page_header_store_title"  style="width:30em" MAXLENGTH="64" value="{$page_data.PageStoreTitle}" ovalue="{$family->get('Page Store Title')}"  />
		   <div id="family_page_header_store_title_msg"></div>
		   <div id="family_page_header_store_title_Container" style="" ></div>
		 </div>
	       </td>
	       <td>
		 <div class="general_options" style="float:right">
		   
		   <span  style="margin-right:10px;visibility:hidden"  id="save_edit_family_page_header" class="state_details">{t}Save{/t}</span>
		   <span style="margin-right:10px;visibility:hidden" id="reset_edit_family_page_header" class="state_details">{t}Reset{/t}</span>
		   
		 </div>
		 
	       </td>
	       
	     </tr>
	     <tr><td class="label">{t}Subtitle{/t}:</td><td>
		 <div  style="width:15em" >
		   <input  id="family_page_header_subtitle"  style="width:30em" MAXLENGTH="64" value="{$page_data.PageStoreSubtitle}" ovalue="{$page_data.PageStoreSubtitle}"  />
		   <div id="family_page_header_subtitle_msg"></div>
		   <div id="family_page_header_subtitle_Container" style="" ></div>
		 </div>
		 
	     </td></tr>
	     <tr style="display:none"><td class="label">{t}Slogan{/t}:</td><td>
		 <div  style="width:15em" >
		 <input  id="family_page_header_slogan"  style="width:30em" MAXLENGTH="64" value="{$page_data.PageStoreSlogan}" ovalue="{$page_data.PageStoreSlogan}"  />
		 <div id="family_page_header_slogan_msg"></div>
		 <div id="family_page_header_slogan_Container" style="" ></div>
		 </div>
	     </td></tr>
	     <tr style="display:none"><td class="label">{t}Short Introduction{/t}:</td><td>
		 <div  style="width:15em" >
		   <input  id="family_page_header_resume"  style="width:30em" MAXLENGTH="64" value="{$page_data.PageStoreResume}" ovalue="{$page_data.PageStoreResume}"  />
		   <div id="family_page_header_resume_msg"></div>
		   <div id="family_page_header_resume_Container" style="" ></div>
		 </div>
	     </td></tr>
	     <tr class="title"><td colspan="2">Content</td></tr>

<tr  style="display:none" ><td class="label">{t}Offers{/t}:<td>
    <table class="options" style="float:left" >
      
      <td  {if $currency_type=='original'}class="selected"{/if} id="original"  >{t}Auto{/t}</td>
       <td {if $currency_type=='corparate_currency'}class="selected"{/if}  id="corparate_currency"  >{t}Do not show offers{/t}</td>


  </table>

</td></tr>
<tr  style="display:none" ><td class="label">{t}New Products{/t}:<td>
     <table class="options" style="float:left" >
    
       <td  {if $currency_type=='original'}class="selected"{/if} id="original"  >{t}New Products{/t}</td>
       <td {if $currency_type=='corparate_currency'}class="selected"{/if}  id="corparate_currency"  >{t}Products back in stock{/t}</td>
       <td {if $currency_type=='corparate_currency'}class="selected"{/if}  id="corparate_currency"  >{t}Do not show any{/t}</td>


  </table>

</td></tr>

	     <tr><td class="label">{t}Family Description{/t}:<br/>HTML/Smarty</td><td>
		 <div  style="width:15em" >
		 <textarea  id="family_page_content_presentation_data"  style="width:30em" MAXLENGTH="24" value="{$page_data.ProductPresentationData}" ovalue="{$page_data.ProductPresentationData}"  >{$page_data.ProductPresentationData}</textarea>
		 <div id="family_page_content_presentation_data_msg"></div>
		 <div id="family_page_content_presentation_data_Container" style="" ></div>
		 </div>
	       </td>
 <td>
		 <div class="general_options" style="float:right">
		   
		   <span  style="margin-right:10px;visibility:hidden"  id="save_edit_family_page_content" class="state_details">{t}Save{/t}</span>
		   <span style="margin-right:10px;visibility:hidden" id="reset_edit_family_page_content" class="state_details">{t}Reset{/t}</span>
		   
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
  
  
 <tr><td class="label">{t}Products Layout{/t}:</td><td>
	<div style="float:left;width:125px;text-align:center;">
	<img style="border:1px solid #ccc" src="art/page_layout_product_thumbnails.png"/>
	{t}Thumbnails{/t}
	</div>
	<div  style="float:left;width:125px;text-align:center">
	<img style="border:1px solid #ccc" src="art/page_layout_product_list.png"/>
	{t}List{/t}
	</div>
	<div  style="float:left;width:125px;text-align:center;display:none">
	<img style="border:1px solid #ccc" src="art/page_layout_slideshow.png"/>
	{t}Slideshow{/t}
	</div>
	<div  style="float:left;width:125px;text-align:center">
	<img style="border:1px solid #ccc" src="art/page_layout_manual.png"/>
	{t}Manual{/t}
	</div>
	

    </td></tr>


      </tr>
      

    </table>

{/if}

      </div>
      <div  class="edit_block" style="margin:0;padding:0 0px;{if $edit!="products"}display:none{/if}"  id="d_products">
	<div   style="margin:0 0 10px 0;padding:10px;border:1px solid #ccc;display:none"  id="new_product_dialog" >
	  <div id="new_product_messages" class="messages_block"></div>

	  <table class="edit" >
	    <tr><td class="label" style="width:7em">{t}Code{/t}:</td><td>
		<input name="code" id="new_code"  onKeyUp="new_product_changed(this)"    onMouseUp="new_product_changed(this)"  onChange="new_product_changed(this)"  name="code" changed=0 type='text' class='text' SIZE="16"  MAXLENGTH="16" value="{$family->get_next_product_code()}"/>  <span style="margin-left:20px;">{t}Family Char{/t}: {$family->get('Family Special Characeristic')}</span>	</td></tr>
    	<tr><td class="label" >{t}Name{/t}:</td><td><input name="name"  id="new_name"  onKeyUp="new_product_changed(this)"    onMouseUp="new_product_changed(this)"  onChange="new_product_changed(this)"  name="code" changed=0  type='text'  SIZE="35" MAXLENGTH="80" class='text' value=""   /></td></tr>
	    <tr><td class="label">{t}Special Char{/t}:</td><td><input name="sdescription"  id="new_sdescription"  onKeyUp="new_product_changed(this)"    onMouseUp="new_product_changed(this)"  onChange="new_product_changed(this)"  name="code" changed=0  type='text'  SIZE="35" MAXLENGTH="32" class='text' /></td></tr>
	    <tr><td class="label">{t}Units/Case{/t}:</td><td><input name="units" id="new_units"  onKeyUp="new_product_changed(this)"    onMouseUp="new_product_changed(this)"  onChange="new_product_changed(this)" SIZE="4" type='text'  MAXLENGTH="20" class='text' /><span style="margin-left:20px;">{t}Type of Unit{/t}:</span>	
		
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
	<tr><td class="label">{t}Price{/t}:</td><td>Per Outer: <input name="price" type='text'  SIZE="6" MAXLENGTH="20" class='text' /><span id="label_price_per_unit" style="margin-left:15px">Per Unit:</span> <input name="price_unit" id="nwe_price_unit"  type='text'  SIZE="6" MAXLENGTH="20" class='text' /></td></tr>
	<tr><td class="label">{t}Retail Price{/t}:</td><td>Per Outer:  <input name="rrp" type='text'  SIZE="6" MAXLENGTH="20" class='text' /><span id="label_price_per_unit" style="margin-left:15px">Per Unit:</span> <input name="rrp_unit" id="new_rrp_unit" type='text'  SIZE="6" MAXLENGTH="20" class='text' /></td></tr>

	<tr style="height:40px"><td style="vertical-align:middle" class="label">{t}Parts{/t}:</td><td style="vertical-align:middle">
	    <span class="save" onclick="create_part()">Create Part</span>
	    
	    <span class="save"  onclick="assing_part()">Assign Part</span>

	    <span style="margin-left:10px;display:none" id="dmenu_label">{t}SKU/description{/t}:</span><span id="dmenu_position"></span>
	    <div  id="dmenu" style="width:30em;position:relative;left:22.6em;bottom:17px;display:none ">
	      <input name="dmenu_input" id="dmenu_input" type='text'  SIZE="32" MAXLENGTH="20" class='text' />
	      <div id="dmenu_container"></div></div>


</td></tr>

	    <tr>
	      <td colspan="2">
		<div id="new_part_container"  class=""  style="padding-top:10px;border:1px solid #ccc;">
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
	      </td>
	    </tr>
	



	    <tr><td colspan="2">
		<div id="new_part_container"  class=""  style="border:1px solid #ccc">
		  <table border=1 class="edit" >
		    
		    <tr><td class="label" style="width:7em">{t}Part{/t}:</td>
		      <td  style="text-align:left">
			<div  style="width:20em;position:relative;top:00px" >
			  <input id="part" style="text-align:left;width:23em" type="text">
			  <div id="part_container" style="" ></div>
			</div>
		      </td>
		    </tr>
		    <input id="part_sku" value="" type="hidden">
		  </table>
		</div>
	    </td></tr>
	    
	    
	    <tr><td colspan="2">
		<div id="part_list"  class="data_table" >
		  <span class="clean_table_title">{t}Part List{/t}</span> 
		   <div  class="clean_table_caption"  style="clear:both;">
		     <div style="float:left;"><div id="table_info5" class="clean_table_info"><span id="rtext5"></span> <span class="rtext_rpp" id="rtext_rpp5"></span> <span class="filter_msg"  id="filter_msg5"></span></div></div>
		     <div class="clean_table_filter" style="display:none" id="clean_table_filter5"><div class="clean_table_info"><span id="filter_name5">{$filter_name5}</span>: <input style="border-bottom:none" id='f_input5' value="{$filter_value0}" size=10/><div id='f_container5'></div></div></div>
		     <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator5"></span></div></div>
		   </div>
		  
		  <div  id="table5"   class="data_table_container dtable btable "></div>
		</div>
	</td></tr>


    
	  </table>
	</div>

	<div   class="data_table" >
	  
	  <span class="clean_table_title">{t}Products{/t}</span> 
	  
	  <table class="options" style="float:right;padding:0;margin:0;">
	    <tr>
	      <td  id="add_product">Add Product</td>
	      <td  style="display:none" id="save_new_product">Save New Product</td>
	      <td  style="display:none" id="cancel_add_product">Cancel</td>
	    </tr>
	  </table>
	  <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
	  
     <span style="float:right;margin-left:10px" class="state_details"  id="restrictions_discontinued"   click="change_multiple(this)"  >{t}discontinued{/t} ({$family->get_number_products_by_sales_type('Discontinued')})</span>
	 <span style="float:right;margin-left:10px" class="state_details"  id="restrictions_not_for_sale"   click="change_multiple(this)"  >{t}not for sale{/t} ({$family->get_number_products_by_sales_type('Not for Sale')})</span>
	 <span style="float:right;margin-left:10px" class="state_details"  id="restrictions_private"   click="change_multiple(this)"  >{t}private sale{/t} ({$family->get_number_products_by_sales_type('Private Sale')})</span>

	 <span style="float:right;margin-left:10px" class="state_details"  id="restrictions_public"   click="change_multiple(this)"  >{t}public sale{/t} ({$family->get_number_products_by_sales_type('Public Sale')})</span>
     <span style="float:right;margin-left:10px" class="state_details"  id="restrictions_none"   click="change_multiple(this)"  >{t}all{/t} ({$family->get_number_products()})</span>


	  <table style="float:left;margin:0 0 0 0px ;padding:0"  class="options" >
	    <tr><td  {if $view=='view_state'}class="selected"{/if} id="view_state" >{t}State{/t}</td>
	      {if $view_stock}<td {if $view=='view_name'}class="selected"{/if}  id="view_name"  >{t}Name{/t}</td>{/if}
	      {if $view_sales}<td  {if $view=='view_price'}class="selected"{/if}  id="view_price"  >{t}Price{/t}</td>{/if}
	    </tr>
	  </table>
	  
	  
	  
	  
	  <div  class="clean_table_caption"  style="clear:both;">
	    <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
	    <div class="clean_table_filter" style="display:none" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0">{$filter_name0}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value0}" size=10/><div id='f_container0'></div></div></div>
	    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
	  </div>
	  <div  id="table0"   class="data_table_container dtable btable "> </div>
	</div>

      </div>

 </div>



 <div id="the_table1" class="data_table" style=" clear:both">
  <span class="clean_table_title">{t}History{/t}</span>
  <div  id="clean_table_caption1" class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div id="table_info1" class="clean_table_info"><span id="rtext1"></span> <span class="filter_msg"  id="filter_msg1"></span></div></div>
    <div id="clean_table_filter1" class="clean_table_filter" style="display:none">
      <div class="clean_table_info"><span id="filter_name1">{$filter_name}</span>: <input style="border-bottom:none" id='f_input1' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator1"></span></div></div>
  </div>
  <div  id="table1"   class="data_table_container dtable btable "> </div>
</div> 
  
 
 
 

  
</div> 
<div id="filtermenu0" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="rppmenu0" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

{include file='footer.tpl'}
