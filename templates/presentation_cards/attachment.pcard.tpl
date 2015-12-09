<div class="presention_card">

<table>
<tr id="result_controls" class="controls" >
<td><span id="result_msg" class="msg success"><i class="fa fa-check"  ></i> {t}Success{/t}</span></td>
<td>
 
<span class=" results link" id="create_other" onClick="change_view(state.request)">{t}Add another{/t} <i class="fa fa-plus"></i>  </span> 
<span class=" results link" id="create_other" onClick="clone_it()">{t}Clone it{/t} <i class="fa fa-flask"></i>  </span> 

</td>
</tr>
<tr class="title">
<td colspan=2>{t}Attachment{/t} <i  onClick="change_view('employee/{$object->id}')" class="fa fa-link link"></i></td>
</tr>
<tr>
<td class="label">{$object->get_field_label('Attachment Subject Type')|capitalize}</td><td>{$object->get('Subject Type')}</td>
</tr>
<tr>
<td class="label">{$object->get_field_label('Attachment Caption')|capitalize}</td><td>{$object->get('Caption')}</td>
</tr>
<tr>
<td class="label">{$object->get_field_label('Attachment Public')|capitalize}</td><td>{$object->get('Public')}</td>
</tr>
<tr>
<td class="label">{$object->get_field_label('Attachment File Original Name')|capitalize}</td><td>{$object->get('File Original Name')}</td>
</tr>
<tr>
<td class="label">{$object->get_field_label('Attachment Type')|capitalize}</td><td>{$object->get('Type')}</td>
</tr>
<tr>
<td class="label">{$object->get_field_label('Attachment File Size')|capitalize}</td><td>{$object->get('File Size')}</td>
</tr>
{if $object->get('Attachment Thumbnail Image Key')  }
<tr>
<td class="label">{$object->get_field_label('Attachment Preview')|capitalize}</td><td><img src="{$object->get('Preview')}"></td>
</tr>
{/if}
</table>


</div>
