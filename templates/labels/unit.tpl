{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:    30 April 2020  02:46::02  +0800, Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3
-->
*}

{assign 'text_margin' ''}

{if $size=='EU30161'}

    {if $with_origin and  $with_account_signature and $with_custom_text }

        {assign 'signature_font_size' '1.5mm'}

    {elseif $with_origin or $with_account_signature  }

        {assign 'signature_font_size' '1.7mm'}
    {else}
        {assign 'signature_font_size' '2mm'}

    {/if}
    {assign 'font_size' '2.0mm'}
{elseif  $size=='EU30140'}
    {assign 'signature_font_size' '2mm'}
    {assign 'font_size' '4.0mm'}
    {assign 'text_margin' 'margin-left:5.0mm'}

{elseif   $size=='EU30137'}
    {assign 'signature_font_size' '3mm'}
    {assign 'font_size' '4.0mm'}
    {assign 'text_margin' 'margin-left:5.0mm;margin-right:2.5mm'}
{elseif   $size=='EU30129'}
    {assign 'signature_font_size' '4mm'}
    {assign 'font_size' '5.0mm'}
    {assign 'text_margin' 'margin-left:5.0mm;margin-right:7.5mm'}
{else}
    {assign 'signature_font_size' '2mm'}
    {assign 'font_size' '2.0mm'}

{/if}


<table border=0 style='width:100%;font-size:{$font_size};font-family: Arial, " Helvetica Neue , Helvetica, sans-serif'>
    <tr>
        <td style="height:100%;" valign="top">
            <table style="{$text_margin}">
                <tr>
                    <td>
                        <b>{$part->get('Reference')}</b></td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #000">{$part->get('Recommended Product Unit Name')}</td>
                </tr>
                <tr>
                    <td style="height: 1mm"></td>
                </tr>
                {if $part->get('Origin Country')!='' and $with_origin}
                    <tr>
                        <td style="font-size: {$signature_font_size}">
                            {if $part->get('Part Origin Country Code')!=$account->get('Account Country Code') and $part->get('Part Production')=='No' }
                                {t}Imported from{/t} {$part->get('Origin Country')} by {$account->get('Name')}
                            {elseif $part->get('Part Production')=='Yes'}
                                {t}Made in{/t} {$part->get('Origin Country')} by {$account->get('Name')}
                            {else}
                                {t}Made in{/t} {$part->get('Origin Country')}
                            {/if}
                        </td>
                    </tr>
                {/if}
                {if $with_custom_text}
                    <tr>
                        <td style="font-size: {$signature_font_size}">

                            {$custom_text|strip_tags|truncate:550}

                        </td>

                    </tr>
                {/if}
                {if $with_account_signature}
                    <tr>
                        <td style="font-size: {$signature_font_size}">
                            {$account->get('Label Signature')}

                        </td>
                    </tr>
                {/if}
            </table>

        </td>

        <td>
            <barcode code="{$part->get('Part Barcode Number')}"/>

        </td>
    </tr>

</table>
