{assign "contractor" $object}
<div class="presentation_card">

    <table>
        <tr id="result_controls" class="controls">
            <td><span id="result_msg" class="msg success"><i class="fa fa-check"></i> {t}Success{/t}</span></td>
            <td>

                <span class=" results link" id="create_other" onClick="change_view(state.request)">{t}Add another{/t} <i
                            class="fa fa-plus"></i>  </span>
                <span class=" results link padding_left_20" id="create_other" onClick="clone_it()">{t}Clone it{/t} <i
                            class="fa fa-flask"></i>  </span>

            </td>
        </tr>
        <tr class="title">
            <td colspan=2>{t}Contractor{/t}</td>
        </tr>

        <tr>
            <td class="label">{$contractor->get_field_label('Staff Alias')|capitalize}</td>
            <td><span onClick="change_view('contractor/{$contractor->id}')" class="marked_link" > {$contractor->get('Alias')}</span></td>
        </tr>
        <tr>
            <td class="label">{$contractor->get_field_label('Staff Name')|capitalize}</td>
            <td>{$contractor->get('Name')}</td>
        </tr>


    </table>


</div>
