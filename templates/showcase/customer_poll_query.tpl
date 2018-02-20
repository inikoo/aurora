{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 February 2018 at 19:03:04 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

<div class="name_and_categories">
    <h1 class=" Customer_Poll_Query_Label">{$poll_query->get('Label')}</h1>
    <div style="clear:both">
    </div>
</div>


<div class="subject_profile" style="padding-top:5px">
    <div style="float:left;width:400px">


        <div class="showcase">


            <table border=0 >


                <tr class="top" style="border-top:1px solid #ccc">
                    <td class="label">{t}Customers{/t}</td>
                    <td class="aright"><span title="{t}Customers who answer this poll{/t}" class="Customer_Poll_Query_Customers">{$poll_query->get('Customers')}</span></td>
                    <td class="aright padding_left_20"><span title="{t}Parentage of total customers who answer this poll{/t}" class="Customer_Poll_Query_Customers_Share">{$poll_query->get('Customers Share')}</span></td>

                </tr>

            </table>
        </div>

        <div style="clear:both">
        </div>
    </div>

    <div style="clear:both">
    </div>
</div>


