
<div >

    <table border=0 style='margin-right: 1mm;width:100%;font-size:2.5mm;font-family: Arial, " Helvetica Neue , Helvetica, sans-serif;' >
    <tr>


        <td style="height:100%;">
            <table style="height: 100%;margin-left: 3mm">

                <tr>
                    <td style="border-bottom: 1px solid #000"><b>{$product->get('Product Name')}</b></td>
                </tr>
                <tr>
                    <td style="height: 1mm"></td>
                </tr>
                {if $product->get('Product Unit Weight')>0}
                    <tr>
                        <td>
                            {t}Weight{/t} {$product->get('Unit Smart Weight')} 	&#8494;
                        </td>
                    </tr>
                {/if}

                <{if $store->get('Label Signature')!=''}
                <tr >
                    <td style="min-height: 10mm">
                        {$store->get('Label Signature')}

                    </td>
                </tr>
                {/if}

                <tr>
                    <td style="margin-right: 3mm">
                    <barcode code="{$product->get('Product Barcode Number')}"/>
                    </td>
                </tr>


            </table>



        </td>
    </tr>
        <td style="height:100%;" valign="top">
            <img src="wi.php?id={$product->get('Product Main Image Key')}&s=270x270" width="60mm" />
        </td>
    </table>
</div>