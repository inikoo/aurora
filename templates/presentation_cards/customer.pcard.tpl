{assign "customer" $object}
<div class="presentation_card">

    <table>
        <tr id="result_controls" class="controls">
            <td><span id="result_msg" class="msg success"><i class="fa fa-check"></i> {t}Success{/t}</span></td>
            <td>

                <span class=" results link" id="create_other" onClick="change_view(state.request)">{t}Add another{/t} <i
                            class="fa fa-plus"></i>  </span>
                <span class="hide results link" id="create_other" onClick="clone_it()">{t}Clone it{/t} <i
                            class="fa fa-flask"></i>  </span>

            </td>
        </tr>
        <tr class="title">
            <td colspan=2>{t}Customer{/t} <i
                        onClick="change_view('customers/{$customer->get('Store Key')}/{$customer->id}')"
                        class="fa fa-link link"></i></td>
        </tr>

        <tr>
            <td class="label">{$customer->get_field_label('Customer Name')|capitalize}</td>
            <td>{$customer->get('Name')}</td>
        </tr>
        <tr>
            <td class="label">{$customer->get_field_label('Customer Main Plain Email')|capitalize}</td>
            <td>{$customer->get('Main Plain Email')}</td>
        </tr>


    </table>


</div>
