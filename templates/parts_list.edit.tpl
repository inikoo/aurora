<table border=0 id="parts_list" class="hide">
<tr class="title">
<td class="parts_per_product"></td>
<td  class="parts">{t}Part{/t}</td>
<td class="notes">{t}Notes{/t}</td>
</tr>
{foreach from=$parts_list item=part_data}
<tr>
<td class="parts_per_product"> <input   value="{$part_data['Parts Per Product']}"> x </td>

<td class="parts">{$part_data['Part']->get('SKU')} ({$part_data['Part']->get('Reference')})</td>
<td class="notes" ><input  value="{$part_data['Note']}"></td>

</tr>
<tr id="new_part_clone" class="hide">
<td class="parts_per_product"><input value="1"> x </td>

<td class="parts"><input  value="" placeholder="{t}SKU or reference{/t}"></td>
<td class="notes"><input  value="" placeholder="{t}Note for pickers{/t}"></td>

</tr>
{/foreach}
<tr class="add_new_part_tr">
<td colspan=2 ><span class="button">{t}Add a part{/t} <i  class="fa fa-plus"></i></span></td>
<td class="aright"><span class="button" id="save_parts_list">{t}Save{/t} <i  class="fa fa-cloud"></i></span></td>
</tr>
</table>