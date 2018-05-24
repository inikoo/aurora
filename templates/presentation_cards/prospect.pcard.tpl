{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 May 2018 at 22:49:05 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}


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
            <td colspan=2>{t}Prospect{/t} </td>
        </tr>

        <tr>
            <td class="label">   {$prospect->get_field_label('Prospect Name')|capitalize}</td>
            <td> <span  class="marked_link" onClick="change_view('prospects/{$prospect->get('Store Key')}/{$prospect->id}')" >  {$prospect->get('Name')}</span></td>
        </tr>





    </table>


</div>
