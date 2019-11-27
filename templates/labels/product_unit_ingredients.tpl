<script>

</script>
<div >

    <table border=0 style='width:100%;font-size:2.0mm;font-family: Arial, " Helvetica Neue , Helvetica, sans-serif' >
    <tr>
        <td style="height:100%;" valign="top">
            <table style="height: 100%;">

                <tr>
                    <td style="border-bottom: 1px solid #000"><b>{$product->get('Product Name')}</b></td>
                </tr>
                <tr>
                    <td style="height: 1mm"></td>
                </tr>
                {if $product->get('Product Unit Weight')>0}
                <tr>
                  <td>
                      <b>{t}Weight{/t}:</b> {$product->get('Unit Smart Weight')} 	&#8494;
                  </td>
                </tr>
                {/if}

                {assign 'materials' $product->get('Materials') }
                {if $materials!=''}
                    <tr>
                        <td>
                            <b>{t}Ingredients/Materials{/t}:</b> {$materials}
                        </td>
                    </tr>
                {/if}
                {if $store->get('Label Signature')!=''}
                <tr>
                    <td>
                        {$store->get('Label Signature')}

                    </td>
                </tr>
                {/if}
            </table>

        </td>

    </tr>

    </table>
</div>