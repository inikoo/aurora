<div class="presentation_card">

    <table>
        <tr id="result_controls" class="controls">
            <td><span id="result_msg" class="msg success"><i class="fa fa-check"></i> {t}Success{/t}</span></td>
            <td>

                <span class=" results link" onClick="change_view(state.request)">{t}Attach other file{/t} <i class="fa fa-plus"></i>  </span>

            </td>
            <td>
                <span class="marked_link" onClick="change_view('{$parent}/{$parent_key}')"   >{t}Attachment's list{/t}</span>
            </td>
        </tr>
        <tr class="title">
            <td colspan=2>{t}Attachment{/t}
        </tr>
        <tr>
            <td class="label">{$object->get_field_label('Attachment Subject Type')|capitalize}</td>
            <td>{$object->get('Subject Type')}</td>
        </tr>
        <tr>
            <td class="label">{$object->get_field_label('Attachment Caption')|capitalize}</td>
            <td><span  onClick="change_view('{$parent}/{$parent_key}/attachment/{$object->get('Attachment Bridge Key')}')"   class="marked_link" >{$object->get('Caption')}  </span> </td>
        </tr>
        <tr>
            <td class="label">{$object->get_field_label('Attachment Public')|capitalize}</td>
            <td>{$object->get('Public Info')}</td>
        </tr>
        <tr>
            <td class="label">{$object->get_field_label('Attachment File Original Name')|capitalize}</td>
            <td>{$object->get('File Original Name')}</td>
        </tr>
        <tr>
            <td class="label">{$object->get_field_label('Attachment Type')|capitalize}</td>
            <td>{$object->get('Type')}</td>
        </tr>
        <tr>
            <td class="label">{$object->get_field_label('Attachment File Size')|capitalize}</td>
            <td>{$object->get('File Size')}</td>
        </tr>
        {if $object->get('Attachment Thumbnail Image Key')  }
            <tr>
                <td class="label">{$object->get_field_label('Attachment Preview')|capitalize}</td>
                <td><img src="{$object->get('Preview')}"></td>
            </tr>
        {/if}
    </table>


</div>

