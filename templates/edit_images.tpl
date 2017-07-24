{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 July 2017 at 09:49:29 CEST, Trnava, Slovakia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<style>
    #edit_image_scope table td{
        border:1px solid #ccc;text-align: center;cursor:pointer

    }
 </style>

<div id="edit_image_caption" class="hide" style="position: absolute;padding:20px;background-color: #fff;z-index: 1000;border:1px solid #ccc">
    <input style="width:400px">
    <i onclick="save_image_caption(this)"   class="fa fa-cloud save valid changed" aria-hidden="true"></i>
</div>



<div id="edit_image_scope" class="hide" style="position: absolute;padding:20px;background-color: #fff;z-index: 1000;border:1px solid #ccc;width:200px">

    <i onclick="$('#edit_image_scope').closest('div').addClass('hide')" class="fa fa-window-close button" aria-hidden="true"  style="position: absolute;top:10px;left:10px;" ></i>


    <table style="margin-top:10px;width: 100%">
    {foreach from=$image_scope_options item=scope }
        <tr>
            <td onclick="save_image_scope('{$scope.value}')"  >{$scope.label}</td>
        </tr>

    {/foreach}
    </table>

</div>
