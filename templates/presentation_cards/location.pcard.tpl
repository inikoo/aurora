{assign "object" $object}
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
            <td colspan=2>{t}Location{/t} </td>
        </tr>

        <tr>
            <td class="label">{$object->get_field_label('Location Code')|capitalize}</td>
            <td>
                <span class="marked_link" onClick="change_view('locations/{$object->get('Location Warehouse Key')}/{$object->id}')">{$object->get('Code')}</span>
            </td>
        </tr>



    </table>


</div>
