{include file='header.tpl'}
<div id="bd" style="padding:0px">
<div style="padding:0 20px">
{include file='assets_navigation.tpl'}
<input type="hidden" value="{$deal->id}" id="deal_key"/>
<input type="hidden" value="{$store->id}" id="store_key"/>



<div class="branch"> 
  <span><a href="index.php"><img style="vertical-align:0px;margin-right:1px" src="art/icons/home.gif" alt="home"/></a>&rarr; {if $user->get_number_stores()>1}<a href="marketing_server.php">{t}Marketing{/t}</a> &rarr;  {/if} <a href="marketing.php?store={$store->id}">{$store->get('Store Code')} {t}Marketing{/t}</a> &rarr;  <a href="store_deals.php?store={$store->id}">{t}Offers{/t}</a></span> &rarr; {$deal->get('Deal Code')}</span>
</div>
<div style="clear:both;width:100%;border-bottom:1px solid #ccc;padding-bottom:3px;margin-bottom:0px">

  <div class="buttons" style="float:left">
   <span class="main_title">{$deal->get('Deal Name')} <span class="id">{$deal->get('Deal Code')}</span></span>
   </div>


<div class="buttons">
      {if $modify}  <button   onclick="window.location='edit_deal.php?id={$deal->id}'" ><img src="art/icons/vcard_edit.png" alt=""> {t}Edit{/t}</button>{/if}

</div>




<div style="clear:both"></div>
</div>





</div>

<div style="padding:0px">
<ul class="tabs" id="chooser_ul" style="clear:both;margin-top:15px">
    <li> <span class="item {if $block_view=='details'}selected{/if}"  id="details">  <span> {t}Overview{/t}</span></span></li>
    <li> <span class="item {if $block_view=='orders'}selected{/if}"  id="orders">  <span> {t}Orders{/t}</span></span></li>
    <li> <span class="item {if $block_view=='customers'}selected{/if}"  id="customers">  <span> {t}Customers{/t}</span></span></li>
    <li> <span class="item {if $block_view=='email_remainder'}selected{/if}"  style="{if $deal->get('Deal Terms Type')!='Order Interval'}display:none{/if}"    id="email_remainder">  <span> {t}Email Remainder{/t}</span></span></li>

  </ul>
<div  style="clear:both;width:100%;border-bottom:1px solid #ccc"></div>
</div>


<div style="padding:0 20px">


<div id="block_details" style="{if $block_view!='details'}display:none;{/if}clear:both;margin:10px 0 40px 0">
<h2>{$deal->get('Deal Name')}</h2>
<p style="width:300px">
{$deal->get('Deal Description')}
</p>
</div>
<div id="block_customers" style="{if $block_view!='customers'}display:none;{/if}clear:both;margin:10px 0 40px 0">

 <span id="table_title" class="clean_table_title">{t}Customers{/t}</span>
     <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px"></div>
    {include file='table_splinter.tpl' table_id=1 filter_name=$filter_name1 filter_value=$filter_value1}
  <div  id="table1"   class="data_table_container dtable btable "> </div>


</div>
<div id="block_orders" style="{if $block_view!='orders'}display:none;{/if}clear:both;margin:10px 0 40px 0">

 <span id="table_title" class="clean_table_title">{t}Orders{/t}</span>
     <div style="clear:both;margin:0 0px;padding:0 20px ;border-bottom:1px solid #999;margin-bottom:15px"></div>
    {include file='table_splinter.tpl' table_id=0 filter_name=$filter_name0 filter_value=$filter_value0}
  <div  id="table0"   class="data_table_container dtable btable "> </div>


</div>

<div id="block_email_remainder" style="{if $block_view!='email_remainder'}display:none;{/if}clear:both;margin:10px 0 40px 0">

{if $deal->get('Deal Remainder Email Campaign Key')}


{if $deal->remainder_email_campaign->get('Email Campaign Status')=='Creating'}


<table class="edit" style="clear:both;width:100%;margin-top:10px" border=0  >

{include file='build_email_splinter.tpl' email_campaign=$deal->remainder_email_campaign}
</table>



{/if}




{else}
<div id="show_create_email_remainder_container" class="buttons left "  style="padding:10px;">
<button id="show_create_email_remainder">{t}Create Email Reminder{/t}</button>
</div>

<span id="new_email_campaign_msg_tr"></div>

{/if}



</div>

</div>

   </div> 

<div id="rppmenu0" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},0)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="filtermenu0" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',0)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
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

<div id="rppmenu2" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu2 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp({$menu},2)"> {$menu}</a></li>
      {/foreach}
    </ul>
  </div>
</div>
<div id="filtermenu2" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
      <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Filter options{/t}:</li>
      {foreach from=$filter_menu2 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_filter('{$menu.db_key}','{$menu.label}',2)"> {$menu.menu_label}</a></li>
      {/foreach}
    </ul>
  </div>
</div>



 </div>

{include file='footer.tpl'}

{if $deal->get('Deal Remainder Email Campaign Key')}
{include file='build_email_dialogs.tpl' email_campaign=$deal->remainder_email_campaign}
{/if}

<div id="dialog_new_email_campaign" style="padding:20px 10px 20px 10px;width:400px">

<table class="edit" border=0 style="margin:0px auto">
<tr>
<td colspan="2">{t}Type of email{/t}:</td>
</tr>
<tr>
<td colspan="2">
<input id="email_campaign_type" value="select_html_from_template_email" type="hidden"   />
<div class="buttons" id="email_campaign_type_buttons">
<button  id="select_text_email"  class="email_campaign_type" ><img src="art/icons/script.png" alt=""/> {t}Text Email{/t}</button>
<button  id="select_html_from_template_email" class="email_campaign_type selected" ><img src="art/icons/layout.png" alt=""/> {t}Template Email{/t}</button>
<button  id="select_html_email" class="email_campaign_type" ><img src="art/icons/html.png"  alt=""/> {t}HTML Email{/t}</button>

</div>
</td>
</tr>
<tr>
<td class="label">{t}Name{/t}:</td>
<td><input style="width:100%" id="email_campaign_name" value="{$deal->get('Deal Name')}"/></td>
</tr>
<tr>
<tr>
<td colspan="2">
<div class="buttons">
<button id="save_new_email_campaign" class="positive">{t}Save{/t}</button>
<button id="cancel_new_email_campaign" class="negative">{t}Cancel{/t}</button>
</div>
</td>
</tr>
<tr id="new_email_campaign_msg_tr" style="display:none"><td colspan=2 class="error" id="new_email_campaign_msg"></td></tr>
</table>

</div>
