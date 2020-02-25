{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 July 2018 at 13:40:58 GMT+8 Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3
-->
*}


<div id="dashboard_customers" style="margin-top:5px;padding:0px" class="dashboard">

    <input id="customers_currency" type="hidden" value="{$currency}">
    <input id="customers_parent" type="hidden" value="{$parent}">


</div>

<h2 class="hide dashboard">Customers</h2>

<ul class="flex-container">

    <li class="flex-item">

        <span>{t}Total customers{/t}</span>
        <div class="title"><span class="Contacts button"  onclick="go_to_orders('website')" title="{t}Number of customers{/t}" >{$object->get('Contacts')}</span></div>
        <div >
            <i class="fa fa-seedling" title="{t}New customers last 7 days{/t}"></i> <span class="New_Contacts " title="{t}New customers last 7 days{/t}">{$object->get('New Contacts')}</span>

            | <span class="New_Contacts_With_Orders" title="{t}New contacts with orders{/t}">{$object->get('New Contacts With Orders')}</span>
            <span class="Percentage_New_Contacts_With_Orders very_discreet">{$object->get('Percentage New Contacts With Orders')}</span>

        </div>

    </li>

    <li class="flex-item">

        <span>{t}Active customers{/t}</span>
        <div class="title"><span class="Active_Contacts button"  title="{t}Number of active customers{/t}" >{$object->get('Active Contacts')}</span></div>
        <div >  <span class="Percentage_Active_Contacts" title="{t}Percentage of total customers{/t}">{$object->get('Percentage Active Contacts')}</span></div>

    </li>

    <li class="flex-item">

        <span>{t}Customers with orders{/t}</span>
        <div class="title"><span class="Contacts_With_Orders button"   title="{t}Number of customers with orders{/t}" >{$object->get('Contacts With Orders')}</span></div>
        <div >  <span class="Percentage_Contacts_With_Order" title="{t}Percentage of total customers{/t}">{$object->get('Percentage Contacts With Orders')}</span></div>


    </li>

    <li class="flex-item invisible">


    </li>

    <li class="flex-item invisible">


    </li>
</ul>


<script>




    function get_dashboard_customers_data(parent,  currency) {

        var request = "/ar_dashboard.php?tipo=customers&parent=" + parent + '&currency=' + currency
        $.getJSON(request, function (r) {


            $('#customers_parent').val(parent)

            for (var record in r.data) {



                $('.' + record).html(r.data[record].value)

                if(r.data[record].title!= undefined ) {
                    $('.' + record).prop('title', r.data[record].title);
                }




            }


        });

    }



 </script>