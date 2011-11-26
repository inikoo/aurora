<div id="dialog_new_category" style="padding:10px">
 {t}Create new category{/t}:
   <div id="new_category_no_name_msg" class="error" style="display:none">{t}Category Name Required{/t}</div>
  <div id="new_category_msg"></div>
 
  <input id="new_category_parent_key" value="{$category_key}" type="hidden"/>
    <input id="new_category_store_key" value="{$store->id}" type="hidden"/>
   <input id="new_category_subject" value="{$subject}" type="hidden"/>
 <table style="margin:10px">
<tr>
 <td>{t}Name{/t}:</td>
 <td> <input id="new_category_name" /></td>
</tr>
<tr>
<td colspan="2">
<div class="buttons">
<button id="new_category_save" class="positive">{t}Save{/t}</button>
<button id="new_category_cancel" class="negative">{t}Cancel{/t}</button>
</div>
</td>
</tr>

</table>


</div>
