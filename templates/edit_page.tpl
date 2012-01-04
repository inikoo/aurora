{include file='header.tpl'}
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal1Container"></div>
<div id="bd" style="padding:0">
<input type="hidden" id="site_key" value="{$site->id}"/>
<input type="hidden" id="site_id" value="{$site->id}"/>

<input type="hidden" id="page_key" value="{$page->id}"/>

<input type="hidden" id="content_height" value="{$page->get('Page Content Height')}"/>

<div style="padding:0 20px">
{include file='assets_navigation.tpl'}
<div class="branch"> 
  <span >{if $user->get_number_stores()>1}<a  href="stores.php">{t}Stores{/t}</a> &rarr; <a href="store.php?id={$store->id}">{/if}{$store->get('Store Name')}</a>  &rarr; <img style="vertical-align:0px;margin-right:1px" src="art/icons/hierarchy.gif" alt=""/> <a href="site.php?id={$site->id}">{$site->get('Site URL')}</a> &rarr; <img style="vertical-align:-1px;" src="art/icons/layout_bw.png" alt=""/> {$page->get('Page Code')}</span>
</div>
<div class="top_page_menu">
    <div class="buttons">
        {if isset($next)}<img class="next" onMouseover="this.src='art/next_button.gif'"  onMouseout="this.src='art/next_button.png'"  title="{$next.title}"  onclick="window.location='{$next.link}'"   src="art/next_button.png" alt="{t}Next{/t}"  / >{/if}

        <button style="margin-left:0px"  onclick="window.location='page.php?id={$page->id}'" ><img src="art/icons/door_out.png" alt=""/> {t}Exit Edit{/t}</button>
        
                <button class="negative"  id="delete_page"><img src="art/icons/cross.png" alt=""/> {t}Delete{/t}</button>
 <button id="show_upload_page_content"> <img src="art/icons/page_save.png" alt=""/>  {t}Import{/t}</button>
        
 {if isset($referral_data)}
         <button   onclick="{$referral_data.url}'" ><img src="art/icons/door_out.png" alt=""/> {$referral_data.label}</button>

 {/if}
          <button  onclick="window.location='page_preview.php?id={$page->id}&logged=1'" ><img src="art/icons/layout.png" alt=""> {t}View Page{/t}</button>

 
 </div>
  <div class="buttons left">
          {if isset($prev)}<img class="previous" onMouseover="this.src='art/previous_button.gif'"  onMouseout="this.src='art/previous_button.png'"   title="{$prev.title}" onclick="window.location='{$prev.link}'"  src="art/previous_button.png" alt="{t}Previous{/t}"   />{/if}

  </div>
    <div style="clear:both"></div>
</div>



<div style="clear:left;margin:0 0px">
    <h1><span class="id" id="title_code">{$page->get('Page Code')}</span>  <span style="font-size:90%;color:#777" id="title_url">{$page->get('Page URL')}</span> </h1>
</div>


  
  <ul class="tabs" id="chooser_ul">
      <li> <span class="item {if $block_view=='setup'}selected{/if}" id="setup"  ><span> {t}Page Properties{/t}</span></span></li>

    <li style="display:none"><span  class="item {if $block_view=='properties'}selected{/if}" id="properties" > <span>{t}HTML Setup{/t}</span></span></li>
    <li style="display:none"> <span class="item {if $block_view=='page_header'}selected{/if}"  id="page_header">  <span> {t}Header{/t}</span></span></li>
    <li style="display:none"> <span class="item {if $block_view=='page_footer'}selected{/if}"  id="page_footer">  <span> {t}Footer{/t}</span></span></li>
    <li> <span class="item {if $block_view=='content'}selected{/if}" id="content"><span>  {if $page->get('Page Code')=='register'}Registration Form{else}{t}Content{/t}{/if}</span></span></li>
    <li> <span class="item {if $block_view=='products'}selected{/if}" id="products"  ><span> {t}Products{/t}</span></span></li>

  


    <li style="display:none"> <span class="item {if $block_view=='style'}selected{/if}" id="style"  ><span> {t}Style{/t}</span></span></li>
    <li style="display:none"> <span class="item {if $block_view=='media'}selected{/if}" id="media"  ><span> {t}Media{/t}</span></span></li>

	</ul>
  
 </div> 

     <div id="tabbed_container" class="tabbed_container" style="padding:10px 0px;margin:0px {if $block_view=='content'}0px{else}20px{/if}" > 
 
      
      
   <div class="edit_block" {if $block_view!="setup"}style="display:none"{/if}   id="d_setup">
    
    
    
    	   <table class="edit" border=0 id="edit_family_page"  style="width:880px;clear:both;margin-left:20px;margin-top:0px" page_key="{$page->id}"   >
	  
	  
	  <tr >
	  <td colspan=3 >
	       <div class="buttons left" >
		   
		   <button  id="show_more_configuration" >{t}Show Advanced Configuration{/t}</button>
		   		   <button  style="display:none" id="hide_more_configuration" >{t}Hide Advanced Configuration{/t}</button>

      </div>
	    
	      <div class="buttons" >
		   
		   <button  style="visibility:hidden"  id="save_edit_page_properties" class="positive">{t}Save{/t}</button>
		   <button style="visibility:hidden" id="reset_edit_page_properties" class="negative">{t}Reset{/t}</button>
		   
      </div>
	     </td>
	     
	     </tr>


<tr class="top"><td></td></tr>

<tbody id="advanced_configuration" style="display:none">
<tr ><td style="width:120px"  class="label">{t}Creation Method{/t}:</td>
 <td style="width:400px">
 <table>
 <tr><td style="padding:0;" class="label">{t}External body & HTML HEAD{/t}:</td><td><input layout="thumbnails" id="checkbox_thumbnails" type="checkbox"  {if $page->get('Page Store Type') =="External Content and HTML HEAD"}checked="checked"{/if} ></td></tr>
 </table>
 </td></tr>
 
 

<tr><td style="width:120px"  class="label">{t}Page Type{/t}:</td>
 <td style="width:400px">
 <table border=0>
 <tr><td style="padding:0;" class="label">{t}Front Page Store{/t}:</td><td><input layout="thumbnails" id="radio_thumbnails" type="radio"  {if $page->get('Page Store Section') =="Front Page Store"}checked="checked"{/if} ></td></tr>
<tr><td style="padding:0;" class="label">{t}Search{/t}:</td><td><input layout="thumbnails" id="radio_thumbnails" type="radio"  {if $page->get('Page Store Section') =="Search"}checked="checked"{/if} ></td></tr>
 <tr><td style="padding:0;" class="label">{t}Product Description{/t}:</td><td><input layout="thumbnails" id="radio_thumbnails" type="radio"  {if $page->get('Page Store Section') =="Product Description"}checked="checked"{/if} ></td></tr>
 <tr><td style="padding:0;" class="label">{t}Information{/t}:</td><td><input layout="thumbnails" id="radio_thumbnails" type="radio"  {if $page->get('Page Store Section') =="Information"}checked="checked"{/if} ></td></tr>
 <tr><td style="padding:0;" class="label">{t}Category Catalogue{/t}:</td><td><input layout="thumbnails" id="radio_thumbnails" type="radio"  {if $page->get('Page Store Section') =="Category Catalogue"}checked="checked"{/if} ></td><td>{if $page->get('Page Store Section') =="Category Catalogue"}{$page->get('Page Parent Code')}{/if} </td></tr>
 <tr><td style="padding:0;" class="label">{t}Family Catalogue{/t}:</td><td><input layout="thumbnails" id="radio_thumbnails" type="radio"  {if $page->get('Page Store Section') =="Family Catalogue"}checked="checked"{/if} ></td><td style="padding:0;">{if $page->get('Page Store Section') =="Family Catalogue"}<a href="family.php?id={$page->get('Page Parent Key')}">{$page->get('Page Parent Code')}</a>{/if} </td></tr>
 <tr><td style="padding:0;" class="label">{t}Department Catalogue{/t}:</td><td><input layout="thumbnails" id="radio_thumbnails" type="radio"  {if $page->get('Page Store Section') =="Department Catalogue"}checked="checked"{/if} ></td><td style="padding:0;">{if $page->get('Page Store Section') =="Department Catalogue"}<a href="department.php?id={$page->get('Page Parent Key')}">{$page->get('Page Parent Code')}</a>{/if} </td></tr>
 <tr><td style="padding:0;" class="label">{t}Store Catalogue{/t}:</td><td><input layout="thumbnails" id="radio_thumbnails" type="radio"  {if $page->get('Page Store Section') =="Store Catalogue"}checked="checked"{/if} ></td></tr>
 <tr><td style="padding:0;" class="label">{t}Registration{/t}:</td><td><input layout="thumbnails" id="radio_thumbnails" type="radio"  {if $page->get('Page Store Section') =="Registration"}checked="checked"{/if} ></td></tr>
 <tr><td style="padding:0;" class="label">{t}Client Section{/t}:</td><td><input layout="thumbnails" id="radio_thumbnails" type="radio"  {if $page->get('Page Store Section') =="Client Section"}checked="checked"{/if} ></td></tr>
 <tr><td style="padding:0;" class="label">{t}Check Out{/t}:</td><td><input layout="thumbnails" id="radio_thumbnails" type="radio"  {if $page->get('Page Store Section') =="Check Out"}checked="checked"{/if} ></td></tr>

 
 </table>
 </td></tr>


      </td></tr>
	     <tr><td class="label" style="width:120px">{t}Browser Title{/t}:</td>
	     <td style="width:400px">
		 <div   >
		   <input  id="page_html_head_title"  style="width:100%" MAXLENGTH="64" value="{$page->get('Page Title')}" ovalue="{$page->get('Page Title')}"  />
		   <div id="page_html_head_title_msg"></div>
		   <div id="page_html_head_title_Container"  ></div>
		 </div>
	       </td><td>
		

      </td></tr>

    <tr style="height:87px"><td class="label" style="width:120px">{t}Page Keywords{/t}:</td>
	       <td style="width:400px">
		 <div >
		   <textarea  id="page_html_head_keywords"  style="width:100%;height:80px" MAXLENGTH="24" value="{$page->get('Page Keywords')}" ovalue="{$page->get('Page Keywords')}"  >{$page->get('Page Keywords')}</textarea>
		   <div id="page_html_head_keywords_msg"></div>
		   <div id="page_html_head_keywords_Container"  ></div>
		 </div>
		 
		 
	     </td></tr>

</tbody>
  <tr><td style="width:120px" class="label">{t}Page Code{/t}:</td>
	     <td style="width:400px">
		 <div   >     
		   <input  style="width:100%" id="page_properties_page_code"   value="{$page->get('Page Code')}" ovalue="{$page->get('Page Code')}"  />
		   
		   <div id="page_properties_page_code_Container"  ></div>
		 </div>
	       </td><td><div style="font-size:80%;color:red" id="page_properties_page_code_msg"></div></td></tr>

      <tr style="height:87px"><td class="label" style="width:120px">{t}Description{/t}:</td>
	       <td style="width:400px">
		 <div >
		   <textarea  id="page_html_head_resume"  style="width:100%;height:80px" MAXLENGTH="24" value="{$page->get('Page Store Resume')}" ovalue="{$page->get('Page Store Resume')}"  >{$page->get('Page Store Resume')}</textarea>
		   <div id="page_html_head_resume_msg"></div>
		   <div id="page_html_head_resume_Container"  ></div>
		 </div>
		 
		 
	     </td></tr>


	     <tr><td width:120px class="label">{t}URL{/t}:</td>
	     <td style="width:400px">
		 <div   >
		   <input style="width:100%"  id="page_properties_url"   value="{$page->get('Page URL')}" ovalue="{$page->get('Page URL')}"  />
		   
		   <div id="page_properties_url_Container"  ></div>
		 </div>
	       </td><td><div id="page_properties_url_msg"></div>
      </td></tr>


<tr><td style="width:120px"  class="label">{t}Link Label{/t}:</td>
	     <td style="width:400px"> 
		 <div   >
		   <input  style="width:100%" id="page_properties_link_title"   value="{$page->get('Page Short Title')}" ovalue="{$page->get('Page Short Title')}"  />
		   
		   <div id="page_properties_link_title_Container"  ></div>
		 </div>
	       </td><td><div id="page_properties_link_title_msg"></div>
      </td></tr>


    
    </table>
    
    
    
    </div>   
    <div class="edit_block" {if $block_view!="properties"}style="display:none"{/if}   id="d_properties">
	
    <table class="edit" border=0  id="properties_edit_table" style="width:100%">
	     <tr ><td colspan="3">

<div class="buttons">
	
	    <button  style="visibility:hidden"  id="save_edit_page_html_head" class="positive">{t}Save{/t}</button>
	    <button style="visibility:hidden" id="reset_edit_page_html_head" class="negative">{t}Reset{/t}</button>
    </div>

	     </td></tr>



	    
	
	    
	 
	     
	  
	  
	  
	  
	     
	    
	     </table>
	     
	     
    </div>
    <div class="edit_block" {if $block_view!="page_header"}style="display:none"{/if}   id="d_page_header">



    
	</div>
	
	
	
    <div class="edit_block" {if $block_view!="page_footer"}style="display:none"{/if}   id="d_page_footer">
    </div>
    <div class="edit_block" style="{if $block_view!="content"}display:none;{/if}padding:0px 0px;margin:0px"   id="d_content">
    
    
    {if $page->get('Page Code')=='register'}
    <div class="general_options" style="float:right">
	    <span  style="margin-right:10px;visibility:hidden"  id="save_edit_register_form" class="state_details">{t}Save{/t}</span>
	    <span style="margin-right:10px;visibility:hidden" id="reset_edit_register_form" class="state_details">{t}Reset{/t}</span>
    </div>
    <table class="edit"  id="register_form_edit_table" >
	     <tr class="title"><td colspan="2">{t}Registration Form{/t}



	     </td></tr>


      </td></tr>
	     <tr><td class="label">{t}Form Type{/t}:</td>
	     <td class="image_radio">
	     <div {if $options.Form_Type=='Steps'}class="selected"{/if}><img  src="art/form_show_all{if $options.Form_Type!='Steps'}_bw{/if}.png"/><br>{t}Show all fields{/t}</div>
	     <div {if $options.Form_Type=='Show All'}class="selected"{/if} style="margin-left:20px;"><img  src="art/form_show_in_steps{if $options.Form_Type!='Show All'}_bw{/if}.png"/><br>{t}Show by steps{/t}</div>
	     
	     </td>
	    </td></tr>
	   
	     <tr style="display:none"><td class="label">{t}No Configurable Fields{/t}:</td>
	     <td>{t}Email{/t}, {t}Password{/t}, {t}Type of business{/t}, [{t}Company Name{/t}/{t}Contact Name{/t}] ({t}at least one should be given{/t})</td>
	     </tr>
	     
	     <tr><td class="label">{t}Configurable Fields{/t}:</td>
	     <td>
	     <table class="list_options" >
	     <tr>
	     <td>{t}Tax Number{/t}</td><td onclick="change_field_show(this)"  value="{$options.Fields.Tax_Number.show}" class="option" id="option_Customer_Tax_Number">{if $options.Fields.Tax_Number.show}{t}Displayed{/t}{else}Hidden{/if}</td>
	     <td onclick="change_field_required(this)"  value="{$options.Fields.Tax_Number.show}" class="option" style="{if !$options.Fields.Tax_Number.show}visibility:hidden{/if}" >{if $options.Fields.Tax_Number.required}{t}Required{/t}{else}Optional{/if}</td>
	     </tr>
	     <tr>
	     <td>{t}Address{/t}</td><td onclick="change_field_show(this)"  value="{$options.Fields.Address.show}"class="option" id="option_Address">{if $options.Fields.Address.show}{t}Displayed{/t}{else}Hidden{/if}</td>
	     <td onclick="change_field_required(this)"  value="{$options.Fields.Address.show}" class="option" style="{if !$options.Fields.Address.show}visibility:hidden{/if}" >{if $options.Fields.Address.required}{t}Required{/t}{else}Optional{/if}</td>
	     </tr>
	     <tr>
	     <td>{t}Telephone{/t}</td><td onclick="change_field_show(this)"  value="{$options.Fields.Telephone.show}" class="option" id="option_Telephone">{if $options.Fields.Telephone.show}{t}Displayed{/t}{else}Hidden{/if}</td>
	     <td onclick="change_field_required(this)"  value="{$options.Fields.Telephone.show}" class="option" style="{if !$options.Fields.Telephone.show}visibility:hidden{/if}" >{if $options.Fields.Telephone.required}{t}Required{/t}{else}Optional{/if}</td>
	     </tr>
	     <tr>
	     <td>{t}Fax{/t}</td><td onclick="change_field_show(this)"  value="{$options.Fields.Fax.show}"  class="option" id="option_Fax">{if $options.Fields.Fax.show}{t}Displayed{/t}{else}Hidden{/if}</td>
	     <td onclick="change_field_required(this)"  value="{$options.Fields.Fax.show}" class="option" style="{if !$options.Fields.Fax.show}visibility:hidden{/if}" >{if $options.Fields.Fax.required}{t}Required{/t}{else}Optional{/if}</td>
	     </tr>
            <tr>
	     <td>{t}Where Found Us{/t}</td><td onclick="change_field_show(this)"  value="{$options.Fields.Where_Found_Us.show}" class="option" id="option_Customer_Where_Found_Us">{if $options.Fields.Where_Found_Us.show}{t}Displayed{/t}{else}Hidden{/if}</td>
	     <td onclick="change_field_required(this)"  value="{$options.Fields.Where_Found_Us.show}" class="option" style="{if !$options.Fields.Where_Found_Us.show}visibility:hidden{/if}" >{if $options.Fields.Where_Found_Us.required}{t}Required{/t}{else}Optional{/if}</td>
	     </tr>
	      <tr>
	     <td>{t}Newsletter{/t}</td><td onclick="change_field_show(this)"  value="{$options.Fields.Newsletter.show}" class="option" id="option_Customer_Newsletter">{if $options.Fields.Newsletter.show}{t}Displayed{/t}{else}Hidden{/if}</td>
	     <td onclick="change_field_required(this)"  value="{$options.Fields.Newsletter.show}" class="option" style="{if !$options.Fields.Newsletter.show}visibility:hidden{/if}" >{if $options.Fields.Newsletter.required}{t}Required{/t}{else}Optional{/if}</td>
	     </tr>
	      <tr>
	     <td>{t}E-marketing{/t}</td><td onclick="change_field_show(this)"  value="{$options.Fields.Emarketing.show}" class="option" id="option_Customer_Emarketing">{if $options.Fields.Emarketing.show}{t}Displayed{/t}{else}Hidden{/if}</td>
	     <td onclick="change_field_required(this)"  value="{$options.Fields.Emarketing.show}" class="option" style="{if !$options.Fields.Emarketing.show}visibility:hidden{/if}" >{if $options.Fields.Emarketing.required}{t}Required{/t}{else}Optional{/if}</td>
	     </tr>
	      <tr>
	     <td>{t}Catalogue{/t}</td><td onclick="change_field_show(this)"  value="{$options.Fields.Catalogue.show}" class="option" id="option_Customer_Catalogue">{if $options.Fields.Catalogue.show}{t}Displayed{/t}{else}Hidden{/if}</td>
	     <td onclick="change_field_required(this)"  value="{$options.Fields.Catalogue.show}" class="option" style="{if !$options.Fields.Catalogue.show}visibility:hidden{/if}" >{if $options.Fields.Catalogue.required}{t}Required{/t}{else}Optional{/if}</td>
	     </tr>
	     </table>
	     </td>
	     </tr>
	     </table>
	{else}
    
    
    <div style="border-bottom:1px dotted #ddd;padding-bottom:5px;margin:0 20px">
    <div class="buttons" >
        <button {if $content_view=='overview'}style="display:none;"{/if} id="show_page_content_overview_block"><img src="art/icons/layout.png" alt=""/> {t}Content Overview{/t}</button>
   
   </div>
   
   <div class="buttons left" >
        <button id="show_page_header_block" {if $content_view=='header'}class="selected"{/if}><img src="art/icons/layout_header.png" alt=""/> {t}Header{/t}</button>
        <button id="show_page_content_block" {if $content_view=='content'}class="selected"{/if}><img src="art/icons/layout_content2.png" alt=""/> {t}Content{/t}</button>
        <button id="show_page_product_list_block" {if $content_view=='product_list'}class="selected"{/if}><img src="art/icons/text_list_bullets.png" alt=""/> {t}Lists{/t}</button>
        <button id="show_page_product_buttons_block" {if $content_view=='product_buttons'}class="selected"{/if}><img src="art/icons/bricks.png" alt=""/> {t}Products{/t}</button>
        <button id="show_page_footer_block" {if $content_view=='footer'}class="selected"{/if}><img src="art/icons/layout_footer.png" alt=""/> {t}Footer{/t}</button>
  
   </div>
      <div style="clear:both"></div>
   </div>
  
   
    <div    id="page_content_overview_block" style="{if $content_view!='overview'}display:none{/if};margin:10px 20px">  
   
    <img id="page_preview_snapshot_image" style="width:470px" src="image.php?id={$page->get('Page Preview Snapshot Image Key')}" alt="{t}No Snapshot Available{/t}"/>

   
   </div>
   <div style="{if $content_view!='header'}display:none{/if};margin:10px 20px" id="page_header_block">  
   
   <table class="edit" border=0  id="header_edit_table" style="width:100%">
        <tr ><td colspan="3">
         <div class="buttons" >
	<button  style="visibility:hidden"  id="save_edit_page_header" class="positive">{t}Save{/t}</button>
	<button style="visibility:hidden" id="reset_edit_page_header" class="negative">{t}Reset{/t}</button>
    </div>
        </td></tr>





 <tr><td class="label" style="width:120px">{t}Header Title{/t}:</td>
	     <td style="width:500px">
		 <div  >
		   <input  id="page_header_store_title"  style="width:100%" MAXLENGTH="64" value="{$page->get('Page Store Title')}" ovalue="{$page->get('Page Store Title')}"  />
		   <div id="page_header_store_title_msg"></div>
		   <div id="page_header_store_title_Container"  ></div>
		 </div>
	       </td>
	    
	       
	     </tr>
  <tr  style="height:10px"><td colspan="3"></td></tr>
    
         <tr>
	       <td style="width:120px" class="label">{t}Parent Pages{/t}:</td>
	       <td style="width:500px" >
	        <div  class="buttons small">
	        <button id="add_other_found_in_page" class="positive">Add page</button></div>
	       <table >
	       
		    {foreach from=$page->get_found_in() item=found_in_page}
               <tr><td style="padding:0">{$found_in_page.found_in_label}</td>
               <td style="padding:0;padding-left:10px"><img onclick="delete_found_in_page({$found_in_page.found_in_key})" style="cursor:pointer" src="art/icons/cross.png" alt="{t}Remove{/t}" title="{t}Remove{/t}"  /></td>
             
               </tr>
            {/foreach}
          
	       </table>
	      
	       </td>
	       <td></td>
	       
	    
	       
	     </tr>
	     
	     <tr>
	       <td style="width:120px" class="label">{t}Related Pages{/t}:</td>
	       <td  style="width:500px">
	        <div id="see_also_type" default_cat=""   class="buttons left" >
                 <button  class="{if $page->get('Page Store See Also Type')=='Auto'}selected{/if}" onclick="save_see_also_type('Auto')" id="see_also_type_Auto">{t}Auto{/t}</button> 
                 <button  class="{if $page->get('Page Store See Also Type')=='Manual'}selected{/if}" onclick="save_see_also_type('Manual')" id="see_also_type_Manual">{t}Manual{/t}</button>
           </div>
           
           
	        <div style="margin-top:40px;clear:right;"  class="buttons small">
	        <button id="add_other_see_also_page" {if $page->get('Page Store See Also Type')=='Auto'}style="display:none"{/if} class="positive">{t}Add page{/t}</button>
	        <button id="add_auto_see_also_page" {if $page->get('Page Store See Also Type')!='Auto'}style="display:none"{/if} class="positive">+</button>
	        <button id="remove_auto_see_also_page" {if $page->get('Page Store See Also Type')!='Auto' or $page->get('Number See Also Links')==0}style="display:none"{/if} class="negative">-</button>

	        </div>
	       
	       <table >
	     
		    {foreach from=$page->get_see_also() item=see_also_page}
               <tr>
               <td style="padding:0">{$see_also_page.see_also_label} (<a href="page.php?id={$see_also_page.see_also_key}">{$see_also_page.see_also_code}</a>)</td>
               <td style="padding:0 10px;font-style:italic;color:#777">{$see_also_page.see_also_correlation_formated} {$see_also_page.see_also_correlation_formated_value}</td>
               <td style="padding:0;padding-left:10px;{if $page->get('Page Store See Also Type')=='Auto'}display:none{/if}"><img onclick="delete_see_also_page({$see_also_page.see_also_key})" style="cursor:pointer" src="art/icons/cross.png" alt="{t}Remove{/t}" title="{t}Remove{/t}"  /></td>
               </tr>
            {/foreach}
       
	       </table>
	      
	       </td>
	       
	       <td>
  
 </td>
	       
	     
	       
	    
	       
	     </tr>
	     



	     
	    
	
   </table>
   
   
   
   
   
   
   </div>
  
   <div style="{if $content_view!='footer'}display:none{/if};margin:10px 20px" id="page_footer_block">  
   
   </div>
   
    <div style="{if $content_view!='product_list'}display:none{/if};margin:10px 20px" id="page_product_list_block">  
    
   <div id="product_lists" style="width:890px;margin-bottom:20px">
     <span class="clean_table_title">{t}Lists{/t}</span>
     {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2  }
        <div id="table2" style="font-size:80%" class="data_table_container dtable btable "> </div>
     </div>
     
       <div id="product_lists" style="width:890px;margin-bottom:20px">
     <span class="clean_table_title">{t}List Items{/t}</span>
     {include file='table_splinter.tpl' table_id=8 filter_name=$filter_name8 filter_value=$filter_value8  }
        <div id="table8" style="font-size:80%" class="data_table_container dtable btable "> </div>
     </div>
     
     
   </div>
     <div style="{if $content_view!='product_buttons'}display:none{/if};margin:10px 20px" id="page_product_buttons_block">  
     <div id="product_buttons" style="width:890px">
     <span class="clean_table_title">{t}Buttons{/t}</span>
     {include file='table_splinter.tpl' table_id=3 filter_name=$filter_name3 filter_value=$filter_value3  }
  <div  id="table3"   class="data_table_container dtable btable "> </div>
     </div>
   </div>
  
 <div style="{if $content_view!='content'}display:none{/if};"  style="display:none" id="page_content_block">  
     <table class="edit"  id="content_edit_table" style="width:810px;padding:0px;margin:0;position:relative;left:-2px">
	     <tr class="title"><td colspan="2">
	  
	    <div class="buttons left">
	   

         </div>   
           <div style="float:right" id="html_editor_msg"></div>
          
  <div class="buttons">
	        <button id="download_page_content">{t}Download{/t}</button>
	    
	     <button class="positive" style="visibility:hidden" id="save_edit_page_content" >{t}Save{/t}</button>
	     <button class="negative" style="visibility:hidden" id="reset_edit_page_content">{t}Reset{/t}</button>

         </div>   

	     </td></tr>
	     <tr><td colspan=2 style="padding:5px 0">
		  <form onsubmit="return false;">
		<textarea id="html_editor" >{$page->get('Page Store Source')}</textarea>
		</form>
      </td></tr>
      
      </table>
  </div>
  
  
    {/if}
    </div>
    <div class="edit_block" {if $block_view!="style"}style="display:none"{/if}   id="d_style">
    </div>
    <div class="edit_block" {if $block_view!="media"}style="display:none"{/if}   id="d_media">
    </div>
     <div class="edit_block" style="{if $block_view!="products"}display:none;{/if}padding:20px;"   id="d_products">
 
 
     
    
     
    </div>
</div>





<div id="the_table1" class="data_table" style="margin:20px 20px 40px 20px; clear:both;padding-top:10px">
  <span class="clean_table_title">{t}Change log{/t}</span>
      {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1  }

  <div  id="table1"   class="data_table_container dtable btable "> </div>
</div>


<div style="clear:both"></div>
</div>



</div>




<div id="dialog_upload_page_content" style="padding:30px 10px 10px 10px;width:320px">

 <table style="margin:0 auto">
  <form enctype="multipart/form-data" method="post" id="upload_page_content_form">
<input type="hidden" name="parent_key" value="{$page->id}" />
<input type="hidden" name="parent" value="page" />
<input id="upload_page_content_use_file" type="hidden" name="use_file" value="" />


 <tr><td>{t}File{/t}:</td><td><input id="upload_page_content_file" style="border:1px solid #ddd;" type="file" name="file"/></td></tr>

  </form>
 <tr><td colspan=2>
  <div class="buttons">
    <span id="processing_upload_page_content" style="float:right;display:none" ><img src="art/loading.gif" alt=""> {t}Processing{/t}</span>
<button class="positive"  id="upload_page_content"  >{t}Upload{/t}</button>
<button  id="cancel_upload_page_content" class="negative" >{t}Cancel{/t}</button><br/>
</div>
  </td></tr>
    </table>
</div>


<div id="dialog_upload_page_content_files" style="padding:30px 10px 10px 10px;width:420px">
    <table style="margin:0 auto">
        <tr><td >
            <div style="margin-bottom:10px">{t}Multiple files found, please select one{/t}.</div>
            </td></tr>
  <tr><td >
  <div id="upload_page_content_files" class="buttons left small"></div>
  </td></tr>
 <tr><td>
  <div class="buttons">
<button  id="cancel_upload_page_content_files" class="negative" >{t}Cancel{/t}</button><br/>
</div>
  </td></tr>
    </table>
</div>


<div id="dialog_delete_page"  style="padding:20px 10px 10px 10px;text-align:left">

<h2 style="padding-top:0px">{t}Delete Page{/t}</h2>
<p>
{t}This operation cannot be undone{/t}.<br> {t}Would you like to proceed?{/t}
</p>


<div style="display:none" id="deleting">
<img src="art/loading.gif" alt=""> {t}Deleting page, wait please{/t}
</div>

<div  id="delete_page_buttons" class="buttons">
 <button id="save_delete_page"  class="positive">{t}Yes, delete it!{/t}</button>
 <button id="cancel_delete_page"  class="negative" >{t}No i dont want to delete it{/t}</button>
 </div>
</div>

{include file='footer.tpl'}
 <div id="dialog_page_list">
    <div class="splinter_cell" style="padding:10px 15px 10px 0;border:none;width:500px">
        <div id="the_table" class="data_table" >
            <span class="clean_table_title">{t}Page List{/t}</span>
            {include file='table_splinter.tpl' table_id=7 filter_name=$filter_name7 filter_value=$filter_value7}
            <div  id="table7"   class="data_table_container dtable btable "> </div>
        </div>
    </div>
 </div>

<iframe  id="page_preview_iframe" src="page_preview.php?id={$page->id}&logged=1&take_snapshot={$take_snapshot}&update_heights={$update_heights}" frameborder=1 style="position:absolute;top:-2000px;left:200px;width:1x;height:1px;" >
<p>{t}Your browser does not support iframes{/t}.</p>
</iframe>