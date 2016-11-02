{assign "part" $object}
<div class="presention_card">

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
            <td colspan=2>{t}Part{/t} <i onClick="change_view('part/{$part->id}')" class="fa fa-square button"></i></td>
        </tr>


        <tr>
            <td class="label">{$part->get_field_label('Part Reference')|capitalize}</td>
            <td>{$part->get('Reference')}</td>
        </tr>
        <tr>
            <td class="label">{$part->get_field_label('Part Package Description')|capitalize}</td>
            <td>{$part->get('Package Description')}</td>
        </tr>

    </table>


</div>
