{include file='header.tpl'}
<div id="bd"  style="padding:0px">
<div style="padding:0 20px">

 <br><br>
<div style="clear:left;margin:0 0px">
    <h1 style="text-transform: capitalize; width:200px; float:left;">{t}Campaign Builder{/t}</h1>
    <div class="campaign_cancel"> <a href="#">cancel &amp; exit</a> </div>
</div>
<br><br><hr style="color:#DDDDDD"><br><br><br>
<table width=60%  style="margin-left:100px;">
   <tr>
	<td><div class="campaign_outer"> 
	<a href="regular_campaign.php"><div class="create-campaign">
	<span>regular ol' campaign</span><p>Send a lovely HTML email along with a plain-text alternative version</p></div></a>  </div>
	</td>
	<td><div class="campaign_outer"> 
	<a href="#"><div class="create-campaign">
	<span>plain-text campaign</span><p>Use this if you just want to send a simple plain-text email with no pictures or formatting</p></div></a>  </div></td>
   </tr>
   <tr>
	<td><div class="campaign_outer"> 
	<a href="#"><div class="create-campaign">
	<span>A/B split campaign</span><p>Campaign sent to two groups to determine the best subject line, from name, or time/day to send campaigns </p></div></a>  </div></td>
	<td><div class="campaign_outer"> 
	<a href="#"><div class="create-campaign">
	<span>RSS-driven campaign</span><p>Campaign that sends content from an RSS feed to a list </p></div></a>  </div></td>
   </tr>



</table>

	
		</div>
	</div>

















</div>
</div>

{include file='footer.tpl'}

<div id="rppmenu0" class="yuimenu" >
  <div class="bd">
    <ul class="first-of-type">
       <li style="text-align:left;margin-left:10px;border-bottom:1px solid #ddd">{t}Rows per Page{/t}:</li>
      {foreach from=$paginator_menu0 item=menu }
      <li class="yuimenuitem"><a class="yuimenuitemlabel" onClick="change_rpp_with_totals({$menu},0)"> {$menu}</a></li>
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
