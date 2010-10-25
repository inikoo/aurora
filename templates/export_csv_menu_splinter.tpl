<div id="export_csv_menu{$id}" class="export_csv_menu" >

  <div class="bd"  style="padding:10px">
<div style="text-align:right"><span id="export_csv{$id}_in_dialog" class="state_details">{t}Download{/t}</span><span style="margin:0 10px"class="state_details" onClick="save_option_table({$id})">{t}Save{/t}</span><span style="margin:0 10px"class="state_details" onClick="close_option_table({$id})">{t}Close{/t}</span></div>

  <table class="edit" id="export_csv_table{$id}" >
   {foreach from=$export_options item=data}
   <tr class="title"><td colspan=5>{$data.title}</td></tr>
  {foreach from=$data.rows item=row}
  <tr class="options_list">
    {foreach from=$row item=col}
    <td style="width:75px" class="{if $col.label==''}empty{/if}  {if $col.selected}selected{/if}" onClick="select_radio_option_table(this)">{$col.label}</td>
    {/foreach}
   </tr>
      {/foreach}

   
   {/foreach}
   </table>
  </div>
</div>
