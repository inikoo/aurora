{include file='header.tpl'}
<div style="display:none; position:absolute; left:10px; top:200px; z-index:2" id="cal1Container"></div>
<div id="bd" >


<div style="clear:left;margin:0 0px">
    <h1>{t}Editing Page{/t}: <span id="title_name">{$page->get('Page Store Function')}</span> (<span id="title_code">{$page->get('Page Code')}</span>)</h1>
</div>



  
  <ul class="tabs" id="chooser_ul">
    <li><span  class="item {if $edit=='properties'}selected{/if}" id="properties" > <span>{t}Properties{/t}</span></span></li>
    <li> <span class="item {if $edit=='header'}selected{/if}"  id="header">  <span> {t}Header{/t}</span></span></li>
    <li> <span class="item {if $edit=='footer'}selected{/if}"  id="footer">  <span> {t}Footer{/t}</span></span></li>
    <li> <span class="item {if $edit=='content'}selected{/if}" id="content"  ><span>  {if $page->get('Page Code')=='register'}Registration Form{else}{t}Content{/t}{/if}</span></span></li>

    <li> <span class="item {if $edit=='style'}selected{/if}" id="style"  ><span> {t}Style{/t}</span></span></li>
    <li> <span class="item {if $edit=='media'}selected{/if}" id="media"  ><span> {t}Media{/t}</span></span></li>
    <li> <span class="item {if $edit=='setup'}selected{/if}" id="setup"  ><span> {t}Setup{/t}</span></span></li>

	</ul>
  
 <input id="page_data" type="hidden" page_key={$page->id} />
     <div class="tabbed_container" > 
 
      
      
  
      

    <div class="edit_block" {if $edit!="properties"}style="display:none"{/if}   id="d_properties">
	<div class="general_options" style="float:right">
	    <span  style="margin-right:10px;visibility:hidden"  id="save_edit_properties" class="state_details">{t}Save{/t}</span>
	    <span style="margin-right:10px;visibility:hidden" id="reset_edit_properties" class="state_details">{t}Reset{/t}</span>
    </div>
    <table class="edit"  id="properties_edit_table" >
	     <tr class="title"><td colspan="2">{t}Page Properties{/t} [HTML HEAD]



	     </td></tr>


      </td></tr>
	     <tr><td class="label" style="width:200px">{t}Title{/t}:</td><td>
		 <div  style="width:15em" >
		   <input  id="family_page_html_head_title"  style="width:30em" MAXLENGTH="64" value="{$page->get('Page Title')}" ovalue="{$page->get('Page Title')}"  />
		   <div id="family_page_html_head_title_msg"></div>
		   <div id="family_page_html_head_title_Container" style="" ></div>
		 </div>
	       </td><td>
		

      </td></tr>
	    
	     <tr><td class="label">{t}Keywords{/t}:</td>
	       <td>
		 <div  style="width:30em" >
		   <textarea  id="family_page_html_head_keywords"  style="width:30em" MAXLENGTH="24" value="{$page_data.PageKeywords}" ovalue="{$page_data.PageKeywords}"  >{$page_data.PageKeywords}</textarea>
		   <div id="family_page_html_head_keywords_msg"></div>
		   <div id="family_page_html_head_keywords_Container" style="" ></div>
		 </div>
		 
		 
	     </td></tr>
	     
	  
	     
	    
	     </table>
    </div>

    <div class="edit_block" {if $edit!="header"}style="display:none"{/if}   id="d_header">
    <div class="general_options" style="float:right">
	<span  style="margin-right:10px;visibility:hidden"  id="save_edit_header" class="state_details">{t}Save{/t}</span>
	<span style="margin-right:10px;visibility:hidden" id="reset_edit_header" class="state_details">{t}Reset{/t}</span>
    </div>
    <table class="edit"  id="header_edit_table" >
        <tr class="title"><td colspan="2">Content (Header)</td></tr>


	     <tr><td class="label" style="width:100px">{t}Title{/t}:</td><td>
		 <div  style="width:15em" >
		   <input  id="family_page_header_store_title"  style="width:30em" MAXLENGTH="64" value="{$page->get('Page Store Title')}" ovalue="{$page->get('Page Store Title')}"  />
		   <div id="family_page_header_store_title_msg"></div>
		   <div id="family_page_header_store_title_Container" style="" ></div>
		 </div>
	       </td>
	    
	       
	     </tr>
	     <tr><td class="label">{t}Subtitle{/t}:</td><td>
		 <div  style="width:15em" >
		   <input  id="family_page_header_subtitle"  style="width:30em" MAXLENGTH="64" value="{$page->get('Page Store Subtitle')}" ovalue="{$page->get('Page Store Subtitle')}"  />
		   <div id="family_page_header_subtitle_msg"></div>
		   <div id="family_page_header_subtitle_Container" style="" ></div>
		 </div>
		 
	     </td></tr>
	     <tr ><td class="label">{t}Slogan{/t}:</td><td>
		 <div  style="width:15em" >
		 <input  id="family_page_header_slogan"  style="width:30em" MAXLENGTH="64" value="{$page->get('Page Store Slogan')}" ovalue="{$page->get('Page Store Slogan')}"  />
		 <div id="family_page_header_slogan_msg"></div>
		 <div id="family_page_header_slogan_Container" style="" ></div>
		 </div>
	     </td></tr>
	     <tr ><td class="label">{t}Short Introduction{/t}:</td><td>
		 <div  style="width:15em" >
		   <textarea  id="family_page_header_resume"  rows="5" style="width:30em" MAXLENGTH="64" value="{$page->get('Page Store Resume')}" ovalue="{$page->get('Page Store Resume')}"  />{$page->get('Page Store Resume')}</textarea>
		   <div id="family_page_header_resume_msg"></div>
		   <div id="family_page_header_resume_Container" style="" ></div>
		 </div>
	     </td></tr>
	     
	    

 
    
   </table>
	</div>

    <div class="edit_block" {if $edit!="footer"}style="display:none"{/if}   id="d_footer">
    </div>
    <div class="edit_block" {if $edit!="content"}style="display:none"{/if}   id="d_content">
    
    
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
	     <div {if $options.Form_Type=='Steps'}class="selected"{/if}><img style="" src="art/form_show_all{if $options.Form_Type!='Steps'}_bw{/if}.png"/><br>{t}Show all fields{/t}</div>
	     <div {if $options.Form_Type=='Show All'}class="selected"{/if} style="margin-left:20px;"><img style="" src="art/form_show_in_steps{if $options.Form_Type!='Show All'}_bw{/if}.png"/><br>{t}Show by steps{/t}</div>
	     
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
    
    
     <table class="edit"  id="content_edit_table" >
	     <tr class="title"><td colspan="2"><span style="font-weight:100" >[<span class="state_details selected">{t}Page Content{/t}</span>|Content Source]</span>
	     <div style="width:50%;float:right;text-align:right;font-weight:100">
	     <span class="state_details">Download</span>
	     <span class="state_details">Upload</span>
	     <span class="state_details">Edit</span>

         <div>   


	     </td></tr>
	     <tr><td colspan=2>
		<div>{$page->get('Product Presentation Template Data')}</div>
      </td></tr>
      
      </table>
    {/if}
    </div>
    <div class="edit_block" {if $edit!="style"}style="display:none"{/if}   id="d_style">
    </div>

    <div class="edit_block" {if $edit!="media"}style="display:none"{/if}   id="d_media">
    </div>
    <div class="edit_block" {if $edit!="setup"}style="display:none"{/if}   id="d_setup">
    </div>    
</div>


<div id="the_table0" class="data_table" style="margin:20px 20px 0px 20px; clear:both;padding-top:10px">
  <span class="clean_table_title">{t}History{/t}</span>
  <div  id="clean_table_caption0" class="clean_table_caption"  style="clear:both;">
    <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
    <div id="clean_table_filter0" class="clean_table_filter" style="display:none">
      <div class="clean_table_info"><span id="filter_name0" class="filter_name" >{$filter_name}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container'></div></div></div>
    <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
  </div>
  <div  id="table0"   class="data_table_container dtable btable "> </div>
</div>



</div>



</div>


<div id="Editor_add_part" style="position:fixed;top:-200px;width:280px">
  <div style="display:none" class="hd"></div>
    <div class="bd dt-editor" >
          <table border=0>
          
         
          
	    <input type="hidden" id="add_part_sku" value=0 >
	     <input type="hidden" id="add_part_key" value=0 >

	    <tr><td>{t}Add part{/t}</tr>
	    <tr>
	    
	    <td id="other_part" >
			
			<div id="add_part"  style="width:260px">
			  <input id="add_part_input" type="text" value="" >
			  <div id="add_part_container"></div>
			</div>


	      </td>
	    </tr>
	   
	  </table>
	  <div class="yui-dt-button">
	    <button style="display:none" onclick="save_add_part();" class="yui-dt-default">{t}Save{/t}</button>
	    <button onclick="close_add_part_dialog()" >{t}Cancel{/t}</button>
	  </div>
    </div>
</div>

{include file='footer.tpl'}


