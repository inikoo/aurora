{assign "object" $object}
<div class="presention_card">

<table>
<tr id="result_controls" class="controls" >
<td><span id="result_msg" class="msg success"><i class="fa fa-check"  ></i> {t}Success{/t}</span></td>
<td>
 
<span class=" results link" id="create_other" onClick="change_view(state.request)">{t}Add another{/t} <i class="fa fa-plus"></i>  </span> 
<span class="hide results link" id="create_other" onClick="clone_it()">{t}Clone it{/t} <i class="fa fa-flask"></i>  </span> 

</td>
</tr>
<tr class="title">
<td colspan=2>{t}Location{/t} <i  onClick="change_view('locations/{$object->get('Location Warehouse Key')}/{$object->id}')" class="fa fa-map-marker button"></i></td>
</tr>

<tr>
<td class="label">{$object->get_field_label('Location Code')|capitalize}</td><td  ><span  onClick="change_view('locations/{$object->get('Location Warehouse Key')}/{$object->id}')" >{$object->get('Code')}</span></td>
</tr>
<tr>
<td class="label">{$object->get_field_label('Location Mainly Used For')|capitalize}</td><td>{$object->get('Mainly Used For')}</td>
</tr>


</table>


</div>
