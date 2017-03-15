<div class="asset_profile" style="padding-top:10px;border-bottom:1px solid #ccc;padding-bottom:20px">


    <div id="asset_data"  style="float:left;p" >
        <div class="data_container">

            <div class="data_field">
                <h1><span class="Part_Unit_Description">{$barcode->get('Part Unit Description')}</span> <span
                            class="Store_Product_Price">{$barcode->get('Price')}</span></h1>
            </div>

        </div>
        <div class="data_container">


        </div>
        <div style="clear:both">
        </div>
        <div class="data_container">

            <div class="wraptocenter" style="height:100px">
                <img src="/barcode_asset.php?number={$barcode->get('Barcode Number')}&scale=10">
            </div>
        </div>
        {include file='sticky_note.tpl' object='Category'  key=$barcode->id sticky_note_field='Store_Product_Sticky_Note' _object=$barcode}


        <div style="clear:both">
        </div>
    </div>
    <div id="info" style="float:left;margin-top:20px;width:300px;margin-left:20px">
        <div id="overviews">
            <table border="0" class="overview" style="">
                <tr id="status_tr" class="main">
                    <td class=" highlight">{$barcode->get('Status')} </td>
                    <td class="aright"> {$barcode->get('Parts')} </td>
                </tr>


            </table>

        </div>
    </div>
    <div style="clear:both">
    </div>


</div>

