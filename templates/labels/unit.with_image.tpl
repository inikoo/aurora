{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:   02 May 2020  15:29::46  +0800 Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3
-->
*}

{if $size=='EU30161' or $size=='EU30040'}
    {assign 'text_margin' ''}
    {if $with_origin and  $with_account_signature and $with_custom_text }

        {assign 'signature_font_size' '1.5mm'}

    {elseif $with_origin or $with_account_signature  }

        {assign 'signature_font_size' '1.7mm'}
    {else}
        {assign 'signature_font_size' '2mm'}

    {/if}
    {assign 'font_size' '2.0mm'}
    {assign 'img_size' '20mm' }
    <table border=0 style='width:100%;font-size:{$font_size};font-family: Arial, "Helvetica Neue", Helvetica, sans-serif'>
        <tr>
            <td colspan=3 style="padding-left:1mm;height:100%;border-bottom: 1px solid #000" valign="top">

                <b>{$part->get('Reference')}</b> {$part->get('Recommended Product Unit Name')}
            </td>


        </tr>

        <tr>
            <td style="height:100%;" valign="top">
                <table style="{$text_margin}">

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
                <barcode size=".60" code="{$part->get('Part Barcode Number')}"/>

            </td>

            <td class="image_td" valign="middle">
                <img src="../image.php?id={$part->get('Part Main Image Key')}&s=270x270" style="margin-right: 0mm;vertical-align: middle;max-height: {$img_size}" width="{$img_size}" height="{$img_size}"/>
            </td>
        </tr>

    </table>
{else}

    {assign 'text_margin' ''}

    {if  $size=='EU30140'}
        {assign 'signature_font_size' '2mm'}
        {assign 'font_size' '3.0mm'}
        {assign 'text_margin' 'margin-left:5.0mm'}
        {assign 'img_size' '30mm' }



    {elseif   $size=='EU30137'}
        {assign 'signature_font_size' '3.5mm'}
        {assign 'font_size' '4.0mm'}
        {assign 'text_margin' 'margin-left:5.0mm;margin-right:2.5mm'}
        {assign 'img_size' '50mm' }

    {elseif   $size=='EU30129'}
        {assign 'signature_font_size' '4mm'}
        {assign 'font_size' '5.0mm'}
        {assign 'text_margin' 'margin-left:5.0mm;margin-right:7.5mm'}
        {assign 'img_size' '64mm' }

    {else}
        {assign 'signature_font_size' '2mm'}
        {assign 'font_size' '2.0mm'}

    {/if}
    <table border=0 style='width:100%;font-size:{$font_size};font-family: Arial, "Helvetica Neue", Helvetica, sans-serif'>
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




                                {if   $size=='EU30137' }
                                    <table style="font-size: 3.5mm;padding:0">
                                        <tr>
                                            <td valign="top" style="padding:0">
                                                {$account->get('Label Signature')}
                                            </td>
                                            <td>
                                                <barcode size=".8" code="{$part->get('Part Barcode Number')}"/>

                                            </td>
                                        </tr>
                                    </table>
                                {else}
                                    {$account->get('Label Signature')}
                                {/if}


                            </td>
                        </tr>
                    {/if}

                    {if   $size=='EU30137' and  !$with_account_signature}
                        <tr>
                            <td>
                                <barcode size="1" code="{$part->get('Part Barcode Number')}"/>

                            </td>
                        </tr>
                    {/if}

                    {if   $size=='EU30129'}
                        <tr>
                            <td>
                                <barcode size="1" code="{$part->get('Part Barcode Number')}"/>

                            </td>
                        </tr>
                    {/if}

                </table>

            </td>
            {if  !( $size=='EU30137' or $size=='EU30129')   }
                <td>
                    <barcode size=".5" code="{$part->get('Part Barcode Number')}"/>

                </td>
            {/if}
            <td class="image_td" style="" valign="middle">
                <img src="../image.php?id={$part->get('Part Main Image Key')}&s=270x270" style="margin-right: 2mm;vertical-align: middle;max-height: {$img_size}" width="{$img_size}" height="{$img_size}"/>
            </td>
        </tr>

    </table>
{/if}