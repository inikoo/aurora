{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 March 2019 at 14:13:56 GMT+8, Ubud, Bali, Indonesia
 Refurbished:  01 May 2020  23:14::32  +0800, Kala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}



<div style="font-family: Arial,,serif 'Helvetica Neue', Helvetica, sans-serif;">
    {if $set_up=='single'}
        {if $with_images}

            {include file="labels/$type.with_image.tpl"}
        {else}
            {include file="labels/$type.tpl"}
        {/if}

    {else}
        <table border=0 style=" border-spacing: 0px;border-collapse: collapse;">
            {for $row=1 to $label_data.rows}
                <tr>
                    {for $col=1 to $label_data.cols}
                        <td style="width: {$label_data.width}mm;height: {$label_data.height}mm;">
                            {if $with_images}
                                {include file="labels/$type.with_image.tpl"}

                            {else}
                                {include file="labels/$type.tpl"}
                            {/if}

                        </td>
                        {if $label_data.h_spacing>0}
                        <td rowspan="{if $label_data.v_spacing>0}2{else}1{/if}" style="width:{$label_data.h_spacing}mm; " >
                        </td>
                        {/if}
                    {/for}
                </tr>
                {if $label_data.v_spacing>0}
                <tr  >
                    <td style="height:{$label_data.v_spacing}mm;border: 0px solid red"></td>
                </tr>
                {/if}
            {/for}
        </table>
    {/if}
</div>