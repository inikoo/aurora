{assign "product" $object}
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
<td colspan=2>{t}Product{/t} <i  onClick="change_view('products/{$product->get('Product Store Key')}/{$product->id}')" class="fa fa-cube button"></i></td>
</tr>


<tr>
<td class="label">{$product->get_field_label('Product Code')|capitalize}</td><td>{$product->get('Code')}</td>
</tr>
<tr>
<td class="label">{$product->get_field_label('Product Name')|capitalize}</td><td>{$product->get('Name')}</td>
</tr>

</table>


</div>
