{assign "api_key" $object}
<div class="presentation_card">

    <table>
        <tr id="result_controls" class="controls">
            <td><span id="result_msg" class="msg success"><i class="fa fa-check"></i> {t}Success{/t}</span></td>
            <td class="hide"><span class=" results link" id="create_other" onclick="change_view(state.request)">{t}Add another{/t} <i
                            class="fa fa-plus"></i> </span> <span class=" results link" id="create_other"
                                                                  onclick="clone_it()">{t}Clone it{/t} <i
                            class="fa fa-flask"></i> </span></td>
        </tr>
        <tr class="title">
            <td colspan="2">{t}API Key{/t} <i
                        onclick="change_view('account/user/{$api_key->get('API Key User Key')}/api_key/{$api_key->id}')"
                        class="fa fa-link link"></i></td>
        </tr>
        <tr>
            <td class="label">{t}User{/t}</td>
            <td><span class="link" onclick="change_view('account/user/{$api_key->get('API Key User Key')}')">{$api_key->user->get('User Handle')}</td>
        </tr>
        <tr>
            <td class="label">{t}Scope{/t}</td>
            <td>{$api_key->get('Scope')}</td>
        </tr>
        <tr>
            <td class="label">{t}Code{/t}</td>
            <td>{$api_key->get('Code')}</td>
        </tr>
        <tr>
            <td class="label">{t}API address{/t}</td>
            <td>{$api_key->get('Address')}</td>
        </tr>

        <tr>
            <td class="label">{t}API Key{/t}</td>
            <td>
                <span id="api_key">{$api_key->secret_key}</span>
                <br>
                <span class="warning"><i class="fa fa-exclamation-circle"></i> {t}The API key is a secret information and should be treated as a password, the key will not be shown again{/t}</span>
            </td>
        </tr>
        <tr>
            <td class="label"></td>
            <td><div id="api_key_qrcode" style="width: 300px;height: 300px;margin-top:20px;margin-bottom:10px"></div>
                <div class="small very_discreet italic">{$api_key->get('Address')},{$api_key->get('Code')},{$api_key->secret_key}</div>
            </td>
        </tr>
    </table>
</div>


<script>
    $('#api_key_qrcode').qrcode({
        size: 300,
        text:'{$qr_code}'
    });




</script>