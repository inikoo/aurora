{include file='header.tpl'}
<div id="bd"  style="padding:0px;">
<div style="padding:0 20px">

 <br><br>
<div style="clear:left;margin:0 0px">
    <h1 style="text-transform: capitalize; width:200px; float:left;">{t}Confirm Sending{/t}</h1>
    <div class="campaign_cancel"> <a href="#">cancel &amp; exit</a> </div>
</div>
<br><br><hr style="color:#DDDDDD"><br><br><br>
<table width=100%  border="1" cellpadding="10" cellspacing="10">
   <tr height="60px">
	<td><b>List : </b><br><br>Your segment of list "{$list_name}" is empty ({$recipients} recipients). <span style="float:right;">Edit</span></td>
   </tr>

   <tr height="60px">
	<td><b>Subject Line :</b> <br><br>"{$default_name}" <span style="float:right;">Edit</span></td>
   </tr>

    <tr height="60px">
	<td><b>Replies :</b> <br><br>  All replies will go to Paromita Guharoy ({$email}).<span style="float:right;">Edit</span> </td>
   </tr>

 <tr height="60px">
	<td><b>Tracking :</b> <br><br>  You chose to track clicks and opens in email. <span style="float:right;">Edit</span> </td>
   </tr>

   <tr height="60px">
	<td><b>Email Authentication : </b><br><br> Automatic email authentication will be enabled for this message<span style="float:right;">Edit</span></td>
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
