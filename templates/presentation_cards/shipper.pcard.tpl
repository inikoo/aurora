{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 May 2018 at 22:49:05 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


{assign "shipper" $object}
<div class="presentation_card">

    <table>
        <tr id="result_controls" class="controls">
            <td><span id="result_msg" class="msg success"><i class="fa fa-check"></i> {t}Success{/t}</span></td>
            <td>

                <span class=" results link" id="create_other" onClick="change_view(state.request)">{t}Add another{/t} <i class="fa fa-plus"></i>  </span>
                <span class="hide results link" id="create_other" onClick="clone_it()">{t}Clone it{/t} <i class="fa fa-flask"></i>  </span>

            </td>
        </tr>
        <tr class="title">
            <td colspan=2>{t}Shipper{/t}</td>
        </tr>

        <tr>
            <td class="label">{$shipper->get_field_label('Shipper Code')|capitalize}</td>
            <td><span onClick="change_view('warehouse/{$shipper->get('Shipper Warehouse Key')}/shipper/{$shipper->id}')"  class="link">{$shipper->get('Code')}</span>  </td>
        </tr>
        <tr>
            <td class="label">{$shipper->get_field_label('Shipper Name')|capitalize}</td>
            <td>{$shipper->get('Name')}</td>
        </tr>


    </table>


</div>
