{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 February 2018 at 22:43:00 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}
<div class="presentation_card">

    <table>
        <tr id="result_controls" class="controls">
            <td><span id="result_msg" class="msg success"><i class="fa fa-check"></i> {t}Success{/t}</span></td>
            <td>

                <span class=" results link" id="create_other" onClick="change_view(state.request)">{t}Add another query{/t} <i
                            class="fa fa-plus"></i>  </span>
               

            </td>
        </tr>
        <tr class="title">
            <td colspan=2>{t}Poll query{/t} </td>
        </tr>

        <tr>
            <td class="label">   {$object->get_field_label('Customer Poll Query Name')|capitalize}</td>
            <td> <span  class="marked_link" onClick="change_view('customers/{$object->get('Store Key')}/poll_query/{$object->id}')" >  {$object->get('Name')}</span></td>
        </tr>


        <tr>
            <td class="label">   {$object->get_field_label('Customer Poll Query Label')|capitalize}</td>
            <td>{$object->get('Label')}</td>
        </tr>
        

    </table>


</div>
