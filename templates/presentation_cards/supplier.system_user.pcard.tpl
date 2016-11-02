<div class="presention_card">
    <table>
        <tr id="result_controls" class="controls">
            <td><span id="result_msg" class="msg success"><i class="fa fa-check"></i> {t}Success{/t}</span></td>
            <td></td>
        </tr>
        <tr class="title">
            <td colspan="2">{t}Supplier (system user){/t} <i onclick="change_view('supplier/{$object->id}')"
                                                             class="fa fa-ship button"></i></td>
        </tr>
        <tr>
            <td class="label">{$object->get_field_label('Agent Code')|capitalize}</td>
            <td>{$object->get('Code')}</td>
        </tr>
        <tr>
            <td class="label">{$object->get_field_label('Agent Name')|capitalize}</td>
            <td>{$object->get('Name')}</td>
        </tr>
        <tr>
            <td class="label">{$object->get_field_label('Agent User Handle')|capitalize}</td>
            <td>{$object->get('User Handle')}</td>
        </tr>
    </table>
</div>
