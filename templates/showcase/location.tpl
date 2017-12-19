<div class="subject_profile">
    <div id="contact_data"></div>
    <div id="info">
        <div id="overviews">

            <table border="0" class="overview">

                <tr>
                    <td>{t}Stock value{/t}:</td>
                    <td class="aright"><span class="Stock_Value">{$location->get('Stock Value')}</span></td>
                </tr>


            </table>


            <table id="barcode_data" border="0" class="overview  ">
                <tr class="main">
                    <td class="label">
                        <i  class="fa fa-barcode"></i>
                    </td>
                    <td class="barcode_labels aleft ">

                        <a class="padding_left_10" title="{t}Location barcode{/t}" href="/location_label.php?object=location&key={$location->id}">{t}Barcode{/t}</a>
                    </td>

                </tr>


            </table>


        </div>
    </div>
    <div style="clear:both">
    </div>
</div>
