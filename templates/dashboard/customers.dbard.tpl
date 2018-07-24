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
        <div > <i class="fa fa-seedling"></i> <span class="New_Contacts" title="{t}Amount in basket{/t}">{$object->get('New Contacts')}</span></div>

    </li>
    <li class="flex-item">

        <span>{t}Customers with orders{/t}</span>
        <div class="title"><span class="Contacts_With_Orders button"   title="{t}Number of customers with orders{/t}" >{$object->get('Contacts With Orders')}</span></div>


    </li>
    <li class="flex-item">

        <span>{t}Active customers{/t}</span>
        <div class="title"><span class="Active_Contacts button"  title="{t}Number of active customers{/t}" >{$object->get('Active Contacts')}</span></div>

    </li>

    <li class="flex-item invisible">

    </li>

    <li class="flex-item invisible">


    </li>
</ul>


<script>




    function get_dashboard_customers_data(parent,  currency) {

        var request = "/ar_dashboard.php?tipo=customers&parent=" + parent + '&currency=' + currency
        console.log(request)
        $.getJSON(request, function (r) {


            $('#customers_parent').val(parent)

            for (var record in r.data) {

                console.log(record)
                console.log(r.data[record].value)

                $('.' + record).html(r.data[record].value)

                if(r.data[record].title!= undefined ) {
                    $('.' + record).prop('title', r.data[record].title);
                }




            }


        });

    }



 </script>