{include file='header.tpl'}
<div id="bd" >


<span class="nav2 onleft"><a href="customers.php">{t}Customers{/t}</a></span>
<span class="nav2 onleft"><a class="selected"  href="companies.php">{t}Companies{/t}</a></span>
<span class="nav2 onleft"><a    href="contacts.php">{t}Personal Contacts{/t}</a></span>

 <div class="search_box">
    <span class="search_title">{t}Company Name{/t}:</span> <input size="8" class="text search" id="contact_search" value="" name="search"/><img align="absbottom" id="contact_submit_search" class="submitsearch" src="art/icons/zoom.png" alt="Submit search"><br/>
     <span  class="search_msg"   id="contact_search_msg"    ></span> <span  class="search_sugestion"   id="contact_search_sugestion"    ></span>
     <br/>
   
      <br><a href="companies.php?edit=1"  class="state_details" id="edit"  >{t}edit{/t}</a>
 </div>

 

 <div id="top" class="top_bar">
    <div id="short_menu" class="nodetails" style="width:100%;margin-bottom:0px">
      <table style="float:left;margin:0 0 0 20px ;padding:0"  class="options" {if $companies==0 }style="display:none"{/if}>
	<tr>
	  <td {if $view=='general'}class="selected"{/if} id="general" >{t}General{/t}</td>
	  <td {if $view=='address'}class="selected"{/if}  id="address"  >{t}Address{/t}</td>
	  <td {if $view=='telephone'}class="selected"{/if}  id="telephone"  >{t}Telephone{/t}</td>


	</tr>
      </table>
    </div>



<div class="data_table" style="margin:25px 20px;clear:both">
    <span class="clean_table_title">{t}Companies{/t}</span>
    <div  class="clean_table_caption"  style="clear:both;">
      <div style="float:left;"><div id="table_info0" class="clean_table_info"><span id="rtext0"></span> <span class="rtext_rpp" id="rtext_rpp0"></span> <span class="filter_msg"  id="filter_msg0"></span></div></div>
      <div class="clean_table_filter" id="clean_table_filter0"><div class="clean_table_info"><span id="filter_name0">{$filter_name}</span>: <input style="border-bottom:none" id='f_input0' value="{$filter_value}" size=10/><div id='f_container0'></div></div></div>
      <div class="clean_table_controls" style="" ><div><span  style="margin:0 5px" id="paginator0"></span></div></div>
    </div>
    <div  id="table0"   class="data_table_container dtable btable "> </div>		
  </div>
  
</div> 

<div id="rppmenu" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

<div id="filtermenu" class="yuimenu">
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>

</div>
{include file='footer.tpl'}
