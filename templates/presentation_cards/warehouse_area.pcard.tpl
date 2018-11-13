{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 November 2018 at 17:22:12 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

{assign "warehouse_area" $object}
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
            <td colspan=2>{t}Warehouse area{/t}</td>
        </tr>

        <tr>
            <td class="label">{$warehouse_area->get_field_label('Warehouse Code')|capitalize}</td>
            <td><span onClick="change_view('warehouse/{$warehouse_area->get('Warehouse Area Warehouse Key')}/area/{$warehouse_area->id}')"  class="link">{$warehouse_area->get('Code')}</span>  </td>
        </tr>
        <tr>
            <td class="label">{$warehouse_area->get_field_label('Warehouse Name')|capitalize}</td>
            <td>{$warehouse_area->get('Name')}</td>
        </tr>


    </table>


</div>
