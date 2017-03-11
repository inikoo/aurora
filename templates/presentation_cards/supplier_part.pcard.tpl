{assign "supplier_part" $object}
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
            <td colspan=2>{t}Supplier part{/t} <i
                        onClick="change_view('supplier/{$supplier_part->get('Supplier Part Supplier Key')}/part/{$supplier_part->id}')"
                        class="fa fa-stop link"></i></td>
        </tr>

        <tr>
            <td class="label">{$supplier_part->get_field_label('Supplier Part Reference')|capitalize}</td>
            <td>{$supplier_part->get('Reference')}</td>
        </tr>
        <tr>
            <td class="label">{$supplier_part->part->get_field_label('Part Reference')|capitalize}</td>
            <td>{$supplier_part->part->get('Reference')}</td>
        </tr>
        <tr>
            <td class="label">{$supplier_part->part->get_field_label('Part Unit Description')|capitalize}</td>
            <td>{$supplier_part->part->get('Unit Description')}</td>
        </tr>

    </table>


</div>
