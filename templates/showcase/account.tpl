<div class="subject_profile">
    <div style="float:left;width:600px">
        <div class="showcase">

            <h1 class="Account_Name">{$account->get('Name')}</h1>

            <table border=0>
                <tr>
                    <td class="label">{$account->get_field_label('Account Stores')|capitalize}
                        /{$account->get_field_label('Account Websites')|capitalize}</td>
                    <td><span class="Account_Stores">{$account->get('Stores')}/<span
                                    class="Account_Websites">{$account->get('Websites')}</span></td>
                </tr>
                <tr>
                    <td class="label">{$account->get_field_label('Account Products')|capitalize}</td>
                    <td class="Account_Stores">{$account->get('Products')}</td>
                </tr>
                <tr>
                    <td class="label">{$account->get_field_label('Account Customers')|capitalize}</td>
                    <td class="Account_Stores">{$account->get('Customers')}</td>
                </tr>
                <tr>
                    <td class="label">{$account->get_field_label('Account Invoices')|capitalize}</td>
                    <td class="Account_Stores">{$account->get('Invoices')}</td>
                </tr>

            </table>
        </div>
        <div style="clear:both">
        </div>
        <div style="clear:both">
        </div>
    </div>

    <div style="clear:both">
    </div>
</div>


<div id="account" style="display:none">

    <div class="block">
        <table border="0" id="stores" class="data_list">
            <tr id="stores_tr" class="bottom-border">
                <td class="aright"> {t}Stores{/t}:</td>
                <td class="aright"><span id="stores_number">{$account->get('Stores')}</span></td>
            </tr>
            <tr id="products_tr" class="bottom-border">
                <td class="aright"> {t}Products{/t}:</td>
                <td class="aright"><span id="products_number">{$account->get('Products')}</span></td>
            </tr>
            <tr id="orders_tr">
                <td class="aright">  {t}Orders{/t}:</td>
                <td class="aright"><span id="orders_number">{$account->get('Orders')}</span></td>
            </tr>
        </table>
    </div>
    <div class="block">
        <table border="0" id="websites" class="data_list">
            <tr id="websites_tr" class="bottom-border">
                <td class="aright"> {t}Websites{/t}:</td>
                <td class="aright"><span id="websites_number">{$account->get('Websites')}</span></td>
            </tr>
            <tr id="pages_tr" class="bottom-border">
                <td class="aright"> {t}Pages{/t}:</td>
                <td class="aright"><span id="pages_number">{$account->get('Pages')}</span></td>
            </tr>
            <tr id="website_users_tr">
                <td class="aright"> {t}Users{/t}:</td>
                <td class="aright"><span id="website_users">{$account->get('Website Users')}</span></td>
            </tr>
        </table>
    </div>
    <div class="block">
        <table border="0" id="warehouses" class="data_list">
            <tr id="warehouses_tr" class="bottom-border">
                <td class="aright"> {t}Warehouses{/t}:</td>
                <td class="aright"><span id="warehouses_number">{$account->get('Warehouses')}</span></td>
            </tr>
            <tr id="locations_tr" class="bottom-border">
                <td class="aright"> {t}Locatons{/t}:</td>
                <td class="aright"><span id="locations_number">{$account->get('Locations')}</span></td>
            </tr>
            <tr id="parts_tr">
                <td class="aright"> {t}Parts{/t}:</td>
                <td class="aright"><span id="parts_number">{$account->get('Parts')}</span></td>
            </tr>
        </table>
    </div>
    <div style="clear:both">
    </div>
</div>
<script>
    var height = $('#object_showcase').height()
    $('#stores').height(height)
    $('#websites').height(height)
    $('#warehouses').height(height)
</script>