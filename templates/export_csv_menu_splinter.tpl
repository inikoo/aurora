<div id="export_csv_menu{$id}" class="export_csv_menu" >

  <div class="bd"  style="padding:10px">




  <table class="edit" id="export_csv_table{$id}" border=0>
  <tr><td colspan=5 style="text-align:right"><span  id="export_csv{$id}_in_dialog" class="state_details">{t}Download{/t}</span><span style="margin:0 10px"class="state_details" onClick="save_option_table({literal}{{/literal}table:'export_csv_table{$id}','session_address':'{$session_address}'{literal}}{/literal})">{t}Save{/t}</span><span id="export_csv{$id}_close_dialog" style="margin:0 10px"class="state_details" >{t}Close{/t}</span></td></tr>
   {foreach from=$export_options item=data}
   <tr class="title"><td colspan=5>{$data.title}</td></tr>
  {foreach from=$data.rows item=row}
  <tr class="options_list">
    {foreach from=$row key=k item=col}
    <td style="width:75px" name="{$k}" class=" {if $col.label==''}empty{else}selectable_option{/if}  {if $col.selected}selected{/if}" onClick="select_radio_option_table(this)">{$col.label}</td>
    {/foreach}
   </tr>
      {/foreach}

   
   {/foreach}
   </table>
  </div>
</div>
