{include file='header.tpl'}
<div id="bd">

{include file='assets_navigation.tpl'}
<div class="branch"> 
  <span   >{if $user->get_number_stores()>1}<a  href="stores.php">{t}Stores{/t}</a> &rarr; {/if}<a href="store.php?id={$store->id}">{$store->get('Store Name')}</a> &rarr; <a href="store_offers.php?store=$store->id">{t}Offers{/t}</a> &rarr; {t}New Offer{/t}</span>
</div>
<div style="clear:both;width:100%;border-bottom:1px solid #ccc;padding-bottom:3px;margin-bottom:0px">

  <div class="buttons" style="float:left">
        <button  onclick="window.location='store.php?id={$store->id}'" ><img src="art/icons/house.png" alt=""> {t}Store{/t}</button>
    </div>


<div class="buttons">
<button class="negative" onclick="window.location='store_offers.php?store={$store->id}'" >{t}Cancel{/t}</button>
</div>




<div style="clear:both"></div>
</div>

<table class="edit">
<tr>
<td class="label">{t}Code{/t}:</td>
  <td  style="text-align:left;width:400px">
     <div   >
       <input style="text-align:left;width:370px" id="email_campaign_subject" value='{$deal->get("Deal Code")|escape}' ovalue="{$deal->get("Deal Code")|escape}" >
       <div id="email_campaign_subject_Container"  ></div>
     </div>
   </td>
   <td>
  <div style="float:left;width:180px" id="email_campaign_subject_msg" class="edit_td_alert"></div>
</tr>
</table>


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



 </div>

{include file='footer.tpl'}
