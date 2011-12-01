{include file='header.tpl'}
<div id="bd" >
<input type="hidden" id="site_key" value="{$site->id}"/>
<input type="hidden" id="store_key" value="{$store_key}"/>

{include file='assets_navigation.tpl'}
<div class="branch"> 
  <span>{if $user->get_number_stores()>1}<a  href="stores.php">{t}Stores{/t}</a> &rarr; <a href="store.php?id={$store->id}">{/if}{$store->get('Store Name')}</a>  &rarr; {t}Website{/t}: {$site->get('Site URL')}</span>
</div>
<div class="top_page_menu">
    <div class="buttons" >
        <button style="margin-left:0px"  onclick="window.location='site.php?id={$site->id}'" ><img src="art/icons/door_out.png" alt=""/> {t}Exit Edit{/t}</button>
    </div>
    <div class="buttons" style="float:right">
    </div>
    <div style="clear:both"></div>
</div>



    <h1>{t}Editing Site{/t}: <span id="title_name">{$site->get('Site Name')}</span> (<span id="title_url">{$site->get('Site URL')}</span>)</h1>

  <div id="msg_div"></div>

  <ul class="tabs" id="chooser_ul" style="clear:both">
    <li> <span class="item {if $block_view=='general'}selected{/if}"  id="general">  <span> {t}General{/t}</span></span></li>
    <li> <span class="item {if $block_view=='layout'}selected{/if}"  id="layout">  <span> {t}Layout{/t}</span></span></li>
    <li> <span class="item {if $block_view=='style'}selected{/if}"  id="style">  <span> {t}Style{/t}</span></span></li>
    <li> <span class="item {if $block_view=='sections'}selected{/if}"  id="sections">  <span> {t}Sections{/t}</span></span></li>
      <li> <span class="item {if $block_view=='headers'}selected{/if}"  id="headers">  <span> {t}Headers{/t}</span></span></li>
    <li> <span class="item {if $block_view=='footers'}selected{/if}"  id="footers">  <span> {t}Footers{/t}</span></span></li>

    <li> <span class="item {if $block_view=='pages'}selected{/if}"  id="pages">  <span> {t}Pages{/t}</span></span></li>
  
  </ul>
  
  <div class="tabbed_container" > 
   
     <div  class="edit_block" style="{if $block_view!='headers'}display:none{/if}"  id="d_headers">
     
     <div class='buttons'>
        <button id="new_header"><img src="art/icons/add.png" alt=""> {t}New Header{/t}</button>
                <button id="show_upload_header"><img src="art/icons/add.png" alt=""> {t}Import From Sources{/t}</button>

     </div>
     <div style="clear:both">
       <span class="clean_table_title">{t}Headers{/t}</span>
     {include file='table_splinter.tpl' table_id=2 filter_name=$filter_name2 filter_value=$filter_value2  }
  <div  id="table2"   class="data_table_container dtable btable "> </div>
   </div>
   </div>
        <div  class="edit_block" style="{if $block_view!='footers'}display:none{/if}"  id="d_footers">
     
     
       <span class="clean_table_title">{t}Footer{/t}</span>
     {include file='table_splinter.tpl' table_id=3 filter_name=$filter_name3 filter_value=$filter_value3  }
  <div  id="table3"   class="data_table_container dtable btable "> </div>
     
     </div>
    <div  class="edit_block" style="{if $block_view!='general'}display:none{/if}"  id="d_general">
      
      
      
      
   
	  
<table class="edit" border=0 style="width:100%">
<tr >
<td></td>
<td colspan=2>

 <div class="buttons">
	        <button  style="visibility:hidden"  id="save_edit_site" class="positive">{t}Save{/t}</button>
	        <button style="visibility:hidden" id="reset_edit_site" class="negative">{t}Reset{/t}</button>
      </div>
</td>
</tr>
<tr class="top"><td class="label">{t}Select Checkout Method{/t}:
  </td><td>
<input id="site_checkout_method" value="inikoo" type="hidden"   />
<div class="buttons" id="site_checkout_method_buttons" style="float:left">
<button  id="Mals" class="site_checkout_method {if $site->get('Site Checkout Method')=='Mals'}selected{/if}" ><img src="art/icons/cart.png" alt=""/> {t}E-Mals Commerce{/t}</button>
<button  id="Inikoo"  class="site_checkout_method {if $site->get('Site Checkout Method')=='Inikoo'}selected{/if}"><img src="art/icons/cart.png" alt=""/> {t}Inikoo{/t}</button>
</div>
     
</td>
<td style="width:300px"></td>
</tr>	
<tbody id="mals_tbody" {if $site->get('Site Checkout Method')!='Mals'}style="display:none"{/if}>
<tr>
<td class="label">{t}E-Mals Commerce ID{/t}:</td>
<td  style="text-align:left">
     <div>
       <input style="text-align:left;width:100%" id="Site_Mals_ID" value="{$site->get_mals_data('id')}" ovalue="{$site->get_mals_data('id')}" valid="0">
       <div id="Site_Mals_ID_Container"  ></div>
     </div>

</td>
<td id="Site_Mals_ID_msg" class="edit_td_alert"></td>
</tr>

<tr>
<td class="label">{t}E-Mals Commerce URL{/t}</td>
<td  style="text-align:left">
     <div>
       <input style="text-align:left;width:100%" id="Site_Mals_URL" value="{$site->get_mals_data('url')}" ovalue="{$site->get_mals_data('url')}" valid="0">
       <div id="Site_Mals_URL_Container"  ></div>
     </div>

</td>
<td id="Site_Mals_URL_msg" class="edit_td_alert"></td>
</tr>
<tr>
<td class="label">{t}E-Mals Commerce URL (Multi){/t}</td>
<td  style="text-align:left">
     <div>
       <input style="text-align:left;width:100%" id="Site_Mals_URL_Multi" value="{$site->get_mals_data('url_multi')}" ovalue="{$site->get_mals_data('url_multi')}" valid="0">
       <div id="Site_Mals_URL_Multi_Container"  ></div>
     </div>

</td>
<td id="Site_Mals_URL_Multi_msg" class="edit_td_alert"></td>
</tr>

</tbody>

<tr><td class="label">{t}Select Registration Method{/t}:</td><td>
<input id="site_registration_method" value="sidebar" type="hidden"   />
<div class="buttons" id="site_registration_method_buttons" style="float:left">
<button  id="sidebar" class="site_registration_method {if $site->get('Site Registration Method')=='SideBar'}selected{/if}" ><img src="art/icons/layout.png" alt=""/> {t}SideBar{/t}</button>
<button  id="mainpage"  class="site_registration_method {if $site->get('Site Registration Method')=='MainPage'}selected{/if}"><img src="art/icons/layout.png" alt=""/> {t}MainPage{/t}</button>
</div>
     
</td></tr>	

<tr>
<td  class="label">{t}Site URL{/t}:</td>
<td  style="text-align:left">
     <div>
       <input style="text-align:left;width:100%" id="Site_URL" value="{$site->get('Site URL')}" ovalue="{$site->get('Site URL')}" valid="0">
       <div id="Site_URL_Container"  ></div>
     </div>

</td>
<td id="Site_URL_msg" class="edit_td_alert"></td>
</tr>

<tr>
<td  class="label">{t}Site Name{/t}:</td>
<td  style="text-align:left">
     <div>
       <input style="text-align:left;width:100%" id="Site_Name" value="{$site->get('Site Name')}" ovalue="{$site->get('Site Name')}" valid="0">
       <div id="Site_Name_Container"  ></div>
     </div>

</td>
<td id="Site_Name_msg" class="edit_td_alert"></td>
</tr>


<tr>
<td  class="label">{t}Site Slogan{/t}:</td>
<td  style="text-align:left">
     <div>
       <input style="text-align:left;width:100%" id="Site_Slogan" value="{$site->get('Site Slogan')}" ovalue="{$site->get('Site Slogan')}" valid="0">
       <div id="Site_Slogan_Container"  ></div>
     </div>

</td>
<td id="Site_Slogan_msg" class="edit_td_alert"></td>
</tr>


  <tr><td class="label">{t}Site Telephone{/t}:</td><td>
	 <div>
	   <input  style="width:100%" id="telephone" changed=0 type='text' MAXLENGTH="255"  class='text' 
	   value="{$site->get('Site Contact Telephone')}" ovalue="{$site->get('Site Contact Telephone')}" />
	   <div id="telephone_Container"  ></div>
       </div>
	   </td>
	   <td id="telephone_msg" class="edit_td_alert" ></td>
	  </tr>
	  
	 <tr><td class="label">{t}Site Address{/t}:</td>
	<td>
	  <div style="height:120px">
	   <textarea style="width:100%" id="address"  changed=0   value="{$site->get('Site Address')}"  ovalue="{$site->get('Site Contact Address')}"   rows="6" cols="42">{$site->get('Site Contact Address')}</textarea>
	   <div id="address_Container"  ></div>
       </div>
	   </td>
	   <td id="address_msg" class="edit_td_alert" ></td>
	 </tr>
	  
	  

<tr style="display:none">
<td  class="label">{t}Site FTP Credentials: {/t}</td>
<td  style="text-align:left">
     <div>
       <input style="text-align:left;width:100%" id="Site_FTP" value="{$site->get('Site FTP Credentials')}" ovalue="{$site->get('Site FTP Credentials')}" valid="0">
       <div id="Site_FTP_Container"  ></div>
     </div>

</td>
<td id="Site_FTP_msg" class="edit_td_alert"></td>
</tr>

</table>	
     
	 
	 </div>
    <div  class="edit_block" style="{if $block_view!='layout'}display:none{/if}"  id="d_layout">
      
      
      
      
      <div class="todo" style="font-size:80%;width:50%">
     <h1>TO DO (KAKTUS-324)</h1>
<h2>Create Site Layouts</h2>
<h3>Objective</h3>
<p>
The following site layouts should hard coded in smarty
<ul>
<li>1)Header/Content Area (Left menu 20%)/ Footer. (ALREADY DONE! see the files in sites/templates)
<li>2)Header/Content Area (Right menu 20%)/ Footer. (TODO)
<li>3)Header/Content Area / Footer with site map   . (TODO)
<li>4) Any other you can imagine (TODO)
</ul>

</p>
<h3>Notes</h3>
<p>
Template 1 is already done in sites/templates (tpl files should be renamed so _left_menu is found in the tpl filename)
</p>
      </div>
      
    
          <div class="todo" style="font-size:80%;width:50%;margin-top:20px">
     <h1>TO DO (KAKTUS-325)</h1>
<h2>Edit Site Layout Form</h2>
<h3>Objective</h3>
<p>
Form to edit Site default layout properties<br/>
<ul>
<li>Choose layout type</li>
</ul>



</p>

      </div> 
	
	
     
      </div>
    <div  class="edit_block" style="{if $block_view!='style'}display:none{/if}"  id="d_style">
      
      
      
      
      <div class="todo" style="font-size:80%;width:50%">


      <h1>TO DO (KAKTUS-326)</h1>

<h2>Site Style Properties (Colour,Backgrounds,Fonts) Edit Form</h2>
<h3>Objective</h3>
<p>
Edit css properties for header, footer and content<br>
<ul>
<li>Upload background images</li>
<li>Colour Schemes</li>

</ul>
</p>

      </div>
      
    
     
	
	
     
      </div>
    <div  class="edit_block" style="{if $block_view!='sections'}display:none{/if}"  id="d_sections">
      <div class="todo" style="font-size:80%;width:50%">
      <h1>TO DO (KAKTUS-327)</h1>
<h2>Editable list of site sections</h2>
<h3>Objective</h3>
<p>
YUI dynamic table with the site sections
</p>
<h3>Notes</h3>
<p>
DB table: `Page Store Section Dimension`<br>
link to edit_site_section.php?id=
</p>
      </div>
      </div>
    <div  class="edit_block" style="{if $block_view!='pages'}display:none{/if}"  id="d_pages"> 
     <div class="general_options" style="float:right">
	        <span style="margin-right:10px;"   id="new_site_page" class="state_details" >{t}Create Page{/t}</span>
	    </div>
		
		
        <div  class="data_table" style="clear:both;" >
	        <span class="clean_table_title">{t}Pages{/t}</span> 
	        <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999"></div>
	            <table style="float:left;margin:0 0 0 0px ;padding:0"  class="options" >
	                <tr>
	                    <td {if $pages_view=='page_properties'}class="selected"{/if} id="page_properties" >{t}Page Properties{/t}</td>
	                    <td {if $pages_view=='page_html_head'}class="selected"{/if}  id="page_html_head"  >{t}HTML Head{/t}</td>
	                    <td {if $pages_view=='page_header'}class="selected"{/if}  id="page_header"  >{t}Header{/t}</td>
		            </tr>
                </table>
                {include file='table_splinter.tpl' table_id=6 filter_name=$filter_name6 filter_value=$filter_value6  }
	            <div  id="table6"  style="font-size:90%" class="data_table_container dtable btable "> </div>
	        </div>
      </div> 
      
      
</div>      





<div id="the_table1" class="data_table" >
  <span class="clean_table_title">{t}History{/t}</span>
     {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1  }

  <div  id="table1"   class="data_table_container dtable btable "> </div>
</div>

</div>
<div id="rppmenu1" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu1 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},1)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="filtermenu1" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu1 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',1)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="rppmenu6" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu6 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},6)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="filtermenu6" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu6 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',6)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="dialog_upload_header" style="padding:30px 10px 10px 10px;width:320px">

 <table style="margin:0 auto">
  <form enctype="multipart/form-data" method="post" id="upload_header_form">
<input type="hidden" name="parent_key" value="{$site->id}" />
<input type="hidden" name="parent" value="site" />

 <tr><td>{t}File{/t}:</td><td><input id="upload_header_file" style="border:1px solid #ddd;" type="file" name="file"/></td></tr>

  </form>
 <tr><td colspan=2>
  <div class="buttons">
<button class="positive"  id="upload_header"  >{t}Upload{/t}</button>
<button  id="cancel_upload_header" class="negative" >{t}Cancel{/t}</button><br/>
</div>
  </td></tr>
    </table>
</div>


{include file='footer.tpl'}
