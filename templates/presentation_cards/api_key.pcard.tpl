{assign "api_key" $object}
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
<td colspan=2>{t}API Key{/t} <i  onClick="change_view('account/user/{$api_key->get('API Key User Key')}/api_key/{$api_key->id}')" class="fa fa-link link"></i></td>
</tr>
<tr>
<td class="label">{t}Address{/t}</td><td>{$account->get('Inikoo Public URL')}api/</td>
</tr>
<tr>
<td class="label">{t}Scope{/t}</td><td>{$api_key->get('Scope')}</td>
</tr>
<tr>
<td class="label">{t}API Key{/t}</td><td><span id="show_key" class="link action" onClick="show_key()">{t}Show key{/t} <i class="fa fa-eye"></i></span><span class="hide" id="api_key">{$api_key->get('API Key User Key')}u{$api_key->id}k{$api_key->secret_key}</span>
<br><span class="warning"><i class="fa fa-exclamation-circle"></i> {t}The API key is a secret information and should be treated as a password{/t}</span></td>
</tr>

</table>


</div>

<script>
function show_key(){
    $('#show_key').addClass('hide')
        $('#api_key').removeClass('hide')

} 
</script>