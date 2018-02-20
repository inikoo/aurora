{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 February 2018 at 16:03:31 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}
<div class="presentation_card">

    <table>
        <tr id="result_controls" class="controls">
            <td><span id="result_msg" class="msg success"><i class="fa fa-check"></i> {t}Success{/t}</span></td>
            <td>

                <span class=" results link" id="create_other" onClick="change_view(state.request)">{t}Add another option{/t} <i
                            class="fa fa-plus"></i>  </span>
               

            </td>
        </tr>
        <tr class="title">
            <td colspan=2>{t}Poll query option{/t} </td>
        </tr>

        <tr>
            <td class="label">   {$poll_option->get_field_label('Customer Poll Query Option Name')|capitalize}</td>
            <td> <span  class="marked_link" onClick="change_view('customers/{$poll_option->get('Store Key')}/poll_query/{$poll_option->get('Query Key')}/option/{$poll_option->id}')" >  {$poll_option->get('Name')}</span></td>
        </tr>


        <tr>
            <td class="label">   {$poll_option->get_field_label('Customer Poll Query Option Label')|capitalize}</td>
            <td>{$poll_option->get('Label')}</td>
        </tr>
        

    </table>


</div>
