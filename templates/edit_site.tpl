{include file='header.tpl'}
<div id="bd" >
<input type="hidden" id="site_key" value="{$site->id}"/>
{include file='assets_navigation.tpl'}
<div > 
  <span   class="branch">{if $user->get_number_stores()>1}<a  href="stores.php">{t}Stores{/t}</a> &rarr; <a href="store.php?id={$store->id}">{/if}{$store->get('Store Name')}</a>  &rarr; {t}Website{/t}: {$site->get('Site URL')}</span>
</div>



    <h1>{t}Editing Site{/t}: <span id="title_name">{$site->get('Site Name')}</span> (<span id="title_url">{$site->get('Site URL')}</span>)</h1>

  <div id="msg_div"></div>

  <ul class="tabs" id="chooser_ul" style="clear:both">
    <li> <span class="item {if $block_view=='general'}selected{/if}"  id="general">  <span> {t}General{/t}</span></span></li>
    <li> <span class="item {if $block_view=='layout'}selected{/if}"  id="layout">  <span> {t}Layout{/t}</span></span></li>
    <li> <span class="item {if $block_view=='style'}selected{/if}"  id="style">  <span> {t}Style{/t}</span></span></li>
    <li> <span class="item {if $block_view=='sections'}selected{/if}"  id="sections">  <span> {t}Sections{/t}</span></span></li>
    <li> <span class="item {if $block_view=='pages'}selected{/if}"  id="pages">  <span> {t}Pages{/t}</span></span></li>

  </ul>
  
  <div class="tabbed_container" > 
   
    
    
    <div  class="edit_block" style="{if $block_view!='general'}display:none{/if}"  id="d_general">
      
      
      
      
      <div class="todo" style="font-size:80%;width:50%">


      <h1>TO DO (KAKTUS-323)</h1>

<h2>General Site Properties Edit Form</h2>
<h3>Objective</h3>
<p>
Edit Form for Site Properties
(Code,Name,URL,FTP configuration,etc)
</p>
<h3>Notes</h3>
<p>
DB updates should be done in class.Site.php. <br/> Should use Ajax (see edit_store.php or edit_customer.php)
</p>
      </div>
      
    
     
	
	
     
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
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp_with_totals({$menu},1)"> {$menu}</a></li>
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

{include file='footer.tpl'}
