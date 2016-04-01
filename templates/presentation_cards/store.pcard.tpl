{assign "store" $object}
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
<td colspan=2>{t}Store{/t} <i  onClick="change_view('store/{$store->id}')" class="fa fa-link link"></i></td>
</tr>

<tr>
<td class="label">{$store->get_field_label('Store Code')|capitalize}</td><td>{$store->get('Code')}</td>
</tr>
<tr>
<td class="label">{$store->get_field_label('Store Name')|capitalize}</td><td>{$store->get('Name')}</td>
</tr>


</table>


</div>
