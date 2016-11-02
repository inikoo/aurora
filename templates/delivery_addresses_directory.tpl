<div class="address_directory">
    {foreach from=$customer->get_other_delivery_addresses_data() item=delivery_address key=delivery_address_key}
        <div class="container">

            <div class="show_buttons"><i class="fa fa-star-o edit"
                                         onClick="set_directory_item_as_main('Customer_Other_Delivery_Address_{$delivery_address_key}')"></i>
                <i class="fa fa-pencil edit"
                   onClick="show_directory_item_edit('Customer_Other_Delivery_Address_{$delivery_address_key}')"></i> <i
                        class="fa fa-trash-o edit"
                        onClick="delete_directory_item('other_delivery_addresses','Customer_Other_Delivery_Address_{$delivery_address_key}')"></i>
            </div>
            <div class="address">{$delivery_address.formatted_value}</div>
        </div>
    {/foreach}
</div>