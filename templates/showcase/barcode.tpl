<div class="asset_profile" style="padding-top:10px;border-bottom:1px solid #ccc;padding-bottom:20px">


    <div id="asset_data"  style="float:left;" >

        <div style="clear:both">
        </div>
        <div class="data_container">

            <div class="wraptocenter" style="height:100px">
                <img src="/barcode_asset.php?number={$barcode->get('Barcode Number')}&scale=10">
            </div>
        </div>


        <div style="clear:both">
        </div>
    </div>
    <div id="info" style="float:left;margin-top:20px;width:300px;margin-left:20px">
        <div id="overviews">
            <table class="overview" >
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

