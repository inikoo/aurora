{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  26 May 2020  21:40::33  +0800, Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3
-->
*}

<table  style="float:right;text-align: left;margin-left:20px;position: relative;top:-3px" class=" progress_bar_box"      >
    <tbody class="export_dialog_container">
    <tr >
        <td class="export_progress_bar_container   progress_bar_container">
            <a href="" class="download_export" ></a>
            <span class="export_progress_bar_bg progress_bar_bg hide "></span>
            <div class="export_progress_bar progress_bar hide "></div>
            <div class="export_download object_download hide"> {t}Download{/t}</div>
        </td>
        <td class="width_20">
            <i  data-stop="0" onclick="stop_control_order_operation(this)" class="stop_export stop_control_order_operation fa button fa-hand-paper error hide padding_left_10" title="{t}Stop{/t}"></i>
            <i  data-stop="0" onclick="close_control_order_operation($('.export_dialog_container'))" class="close_export close_control_order_operation fa button fa-times discreet hide padding_left_10" title="{t}Close{/t}"></i>

        </td>
    </tr>
    </tbody>
</table>