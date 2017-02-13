<table>
    {foreach from=$other_delivery_addresses item=delivery_address key=delivery_address_key}
        <tr style="border:none">

            <td class="address_directory_buttons" >
                <i class="fa fa-star-o edit" onClick="set_directory_item_as_main('Customer_Other_Delivery_Address_{$delivery_address_key}')" title="{t}Set as default delivery address{/t}"></i>
                <i class="fa fa-pencil edit" onClick="show_directory_item_edit('Customer_Other_Delivery_Address_{$delivery_address_key}')" title="{t}Edit address{/t}"></i>
                <i class="fa fa-trash-o edit error" onClick="delete_directory_item('other_delivery_addresses','Customer_Other_Delivery_Address_{$delivery_address_key}')" title="{t}Delete address{/t}"></i>
            </td>
            <td class="address" >{$delivery_address.formatted_value}</td>
        </tr>
    {/foreach}
</table>