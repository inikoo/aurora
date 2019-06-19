{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 July 2017 at 14:41:12 GMT+8, Cyberjaya, Malaydsia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


<div class="webpage_showcase">
<div class="container" style="min-height:200px;padding:5px 20px">

    


    <div class="asset_container">

        <div class="block picture">
        </div>

        <div class="block sales_data">
        </div>

        <div class="block info">
            <div id="overviews">

                <table border="0" class="overview">


                    <tr class="top">
                        <td ></td>
                        <td class=" aright Webpage_State">{$webpage->get('State')}</td>
                    </tr>


                    <tr>
                        <td  title="{t}Status{/t}">{t}Product{/t}</td>
                        <td class="aright"><span onclick="change_view('products/{$product->get('Store Key')}/{$product->id}')" class="link">{$product->get('Code')}</span></td>

                    </tr>

                    <tr>
                        <td  colspan="2" style="text-align: right">{$product->get('Name')}</td>
                    </tr>


                </table>





            </div>
        </div>
        <div style="clear:both">
        </div>
    </div>


</div>
</div>