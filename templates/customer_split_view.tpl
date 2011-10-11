{include file='header.tpl'}

<div id="bd" style="padding:0 20px">

 <span class="nav2 onright" style="padding:0px">{if $next.id_a>0}<a class="next" href="customer_split_view.php?{$my_parent_info}id_a={$next.id_a}&id_b={$next.id_b}&score={$next.score}&name_a={$next.name_a|escape:'url'}&name_b={$next.name_b|escape:'url'}" ><img src="art/icons/next_white.png" style="padding:0px 10px" alt=">" title="{t}Next{/t}"  /></a>{/if}</span>
{if $my_parent_url}<span class="nav2 onright"><a   href="{$my_parent_url}">{$my_parent_title}</a></span>{/if}
<span class="nav2 onright" style="margin-left:20px; padding:0px"> {if $prev.id_a>0}<a class="prev" href="customer_split_view.php?{$my_parent_info}id_a={$prev.id_a}&id_b={$prev.id_b}&score={$prev.score}&name_a={$next.name_a|escape:'url'}&name_b={$next.name_b|escape:'url'}" ><img src="art/icons/previous_white.png" style="padding:0px 10px" alt="<" title="{t}Previous{/t}"  /></a>{/if}</span>

 


  {include file='contacts_navigation.tpl'}
  <h1>{t}Customers Split View{/t} ({$store->get('Store Code')})</h1>


<div style="clear:both">


<table class="quick_button" style="clear:both;float:right;margin-top:0px;">

    <tr style="{if !$can_merge}display:none{/if}"><td  id="open_merge_dialog">{t}Merge{/t}</td></tr>
  
</table>

<table id="customers_table" style="width:620px" border=0 >

<tr>
<td class="customer_a"  style="{if $customer_a.deleted}opacity:0.5{/if}">{$customer_a.card}</td>
<td class="customer_b" style="{if $customer_b.deleted}opacity:0.5{/if}">{$customer_b.card}</td>
</tr>
<tr >
<td class="customer_a"><div style="font-size:90%;background:#e0eefd;margin:10px 0;width:250px;padding:10px;{if $customer_a.sticky_note==''}display:none{/if}">{$customer_a.sticky_note}</div></td>
<td class="customer_b"><div style="font-size:90%;background:#e0eefd;margin:10px 0;width:250px;padding:10px;{if $customer_b.sticky_note==''}display:none{/if}">{$customer_b.sticky_note}</div></td>
</tr>
<tr>
<td class="customer_a">
{if $customer_a.deleted}
{$customer_a.msg}
{else}
<table>
<tr><td>{t}Contact Since{/t}:</td><td>{$customer_a.since}</td><tr>
<tr style="{if !$customer_a.last_order_date}visibility:hidden{/if}"><td>{t}Last Order{/t}:</td><td>{$customer_a.last_order_date}</td><tr>
<tr><td>{t}Orders{/t}:</td><td>{$customer_a.orders}</td><tr>
<tr><td>{t}Notes{/t}:</td><td>{$customer_a.notes}</td><tr>
<tr>
<td colspan=2>
   {if $customer_a_object->get('Customer Send Newsletter')=='No'}<img alt="{t}Attention{/t}" width='14' src="art/icons/exclamation.png" /> <span>{t}Don't send newsletters{/t}<span><br/>{/if}
   {if $customer_a_object->get('Customer Send Email Marketing')=='No'}<img alt="{t}Attention{/t}" width='14' src="art/icons/exclamation.png" /> <span>{t}Don't send marketing by email{/t}<span><br/>{/if}
   {if $customer_a_object->get('Customer Send Postal Marketing')=='No'}<img alt="{t}Attention{/t}" width='14' src="art/icons/exclamation.png" /> <span>{t}Don't send marketing by post{/t}<span><br/>{/if}

</td>
</tr>

</table>
{/if}

</td>
<td class="customer_b">
{if $customer_b.deleted}
{$customer_b.msg}
{else}
<table  style="{if $customer_b.deleted}visibility:hidden{/if}">
<tr><td>{t}Contact Since{/t}:</td><td>{$customer_b.since}</td><tr>
<tr style="{if !$customer_b.last_order_date}visibility:hidden{/if}"><td>{t}Last Order{/t}:</td><td>{$customer_b.last_order_date}</td><tr>
<tr><td>{t}Orders{/t}:</td><td>{$customer_b.orders}</td><tr>
<tr><td>{t}Notes{/t}:</td><td>{$customer_b.notes}</td><tr>
<tr>
<td colspan=2>
    {if $customer_b_object->get('Customer Send Newsletter')=='No'}<img alt="{t}Attention{/t}" width='14' src="art/icons/exclamation.png" /> <span>{t}Don't send newsletters{/t}<span><br/>{/if}
   {if $customer_b_object->get('Customer Send Email Marketing')=='No'}<img alt="{t}Attention{/t}" width='14' src="art/icons/exclamation.png" /> <span>{t}Don't send marketing by email{/t}<span><br/>{/if}
   {if $customer_b_object->get('Customer Send Postal Marketing')=='No'}<img alt="{t}Attention{/t}" width='14' src="art/icons/exclamation.png" /> <span>{t}Don't send marketing by post{/t}<span><br/>{/if}

</td>
</tr>

</table>
{/if}
</td>



</tr>
</table>
</div>

</div>




{include file='footer.tpl'}

<div id="dialog_merge">
  <div id="delete_customer_warning" style="width:200px;border:1px solid red;margin:15px;padding:5px 5px 5px 5px;color:red;">
<h2>{t}Customers Merging{/t}</h2>
<p>
{t}A customer will be deleted, and its notes and orders will be transferred to the other one{/t}.<br>
{t}This operation cannot be undone{/t}.
</p>

</div>
  
  <table style="padding:10px;margin:auto;margin-bottom:20px" border=0>
 <input type="hidden" value="right" id="merge_direction">
 <input type="hidden" value="{$customer_a.id}" id="customer_a">
 <input type="hidden" value="{$customer_b.id}" id="customer_b">

    <tr><td colspan=2 style="text-align:center">
    <table style="font-size:140%;padding:20px;margin:auto" border=0>
    <tr id="right_merge">
        <td style="text-decoration:line-through;color:SteelBlue">{$customer_a.formated_id}</td>
        <td style="text-align:center"><div>&rArr;<br/><span onclick="swap_left()" style="cursor:pointer;color:#999;font-size:70%;position:relative;top:-7px">{t}swap{/t} &#8617;</span></div></td>
        <td style="color:SteelBlue;font-weight:800">{$customer_b.formated_id}</td>
    </tr>
     <tr  id="left_merge" style="display:none">
        <td style="color:SteelBlue;font-weight:800">{$customer_a.formated_id}</td>
        <td style="text-align:center"><div>&lArr;<br/><span onclick="swap_right()" style="cursor:pointer;color:#999;font-size:70%;position:relative;top:-7px">&#8618;  {t}swap{/t}</span></div></td>
        <td style="text-decoration:line-through;color:SteelBlue"> {$customer_b.formated_id}</td>

    </tr>

 </table>
    
    
      </td>
    <tr>
    <tr id="merging_buttons" class="buttons" style="font-size:100%">
  <td style="text-align:center;width:50%">
    <span  class="unselectable_text button" onClick="close_merge_dialog()" >{t}Cancel{/t}</span></td>
  <td style="text-align:center;width:50%">
    <span  onclick="merge()" id="merge_save"  class="unselectable_text button"      >{t}Merge{/t}</span>
    </tr>
    <tr id="merging" style="display:none"><td colspan=2 style="text-align:center">{t}Merging, please wait{/t}<td></tr>
         <tr ><td  colspan=2 id="merge_msg" colspan=2 style="text-align:center"><td></tr>

</table>
</div>

