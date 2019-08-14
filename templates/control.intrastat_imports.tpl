{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13-08-2019 13:29:58 MYT Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

<style>

    .intrastat_totals {
        min-width: 400px;
        border-top: 1px solid #ccc;
        float: right;
    }

    .intrastat_totals tr {
        border-bottom: 1px solid #ccc;
    }

</style>

<div style="padding:20px;border-bottom:1px solid #ccc" class="control_panel">


    <table style="float: left">
        <tr>
            <td><span class="button unselectable" onclick="change_intrastat_element(this,'invoices_no_vat')"><i id="icon_invoices_no_vat"
                                                                                                                class="fa {if $table_state['invoices_no_vat']==1}fa-toggle-on{else}fa-toggle-off{/if} fa-fw"></i>
                    {t}Invoices with no VAT{/t}
                    <small class="discreet">({t}Customers with valid tax number{/t})</small></span>
            </td>
        </tr>
        <tr>
            <td><span class="button unselectable" onclick="change_intrastat_element(this,'invoices_vat')"><i id="icon_invoices_vat"
                                                                                                             class="fa {if $table_state['invoices_vat']==1}fa-toggle-on{else}fa-toggle-off{/if} fa-fw"></i> {t}Invoices with VAT{/t}

                    </span></td>
        </tr>

        <tr>
            <td><span class="button unselectable" onclick="change_intrastat_element(this,'invoices_null')"><i id="icon_invoices_null"
                                                                                                              class="fa {if $table_state['invoices_null']==1}fa-toggle-on{else}fa-toggle-off{/if} fa-fw"></i> {t}Replacements/Samples{/t}</span>
            </td>
        </tr>

    </table>


    <table class="intrastat_totals">
        <tr>
            <td>{t}Deliveries{/t}</td>
            <td class="aright element_total total_orders"></td>
        </tr>

        <tr>
            <td>{t}Products{/t}</td>
            <td class="aright element_total total_products"></td>
        </tr>

        <tr>
            <td>{t}Amount{/t}</td>
            <td class="aright element_total total_amount"></td>
        </tr>

        <tr>
            <td>{t}Weight{/t}</td>
            <td class="aright element_total total_weight"></td>
        </tr>

    </table>

    <div style="clear: both"></div>

</div>

<script>




    function change_intrastat_element(element, key) {

        var num_selected = 0;
        if ($('#icon_invoices_vat').hasClass('fa-toggle-on')) {
            num_selected++;
        }
        if ($('#icon_invoices_no_vat').hasClass('fa-toggle-on')) {
            num_selected++;
        }
        if ($('#icon_invoices_null').hasClass('fa-toggle-on')) {
            num_selected++;
        }

        console.log(num_selected)

        var icon = $(element).find('i')

        if (icon.hasClass('fa-toggle-on')) {

            if (num_selected == 1) {
                return;
            }

            icon.removeClass('fa-toggle-on').addClass('fa-toggle-off')
            var value = 0
        } else {
            icon.removeClass('fa-toggle-off').addClass('fa-toggle-on')
            var value = 1
        }


        $('.element_total').html('');

        var request = "/ar_state.php?tipo=update_table_state&table=intrastat&key=" + key + '&value=' + value
        $.getJSON(request, function (data) {
            if (data.state == 200) {
                get_intrastat_totals();

                var parameters = JSON.parse(rows.parameters);

                //onsole.log(key)
                //console.log(value)
                //console.log(parameters)

                parameters[key] = value;

               // console.log(parameters)


                rows.parameters = JSON.stringify(parameters)
                rows.url = '/' + rows.ar_file + '?tipo=' + rows.tipo + '&parameters=' + rows.parameters

                console.log(rows.parameters)
                rows.fetch({
                    reset: true
                });


            }
        })


    }

    function get_intrastat_totals() {
        var request = "/ar_reports_tables.php?tipo=intrastat_totals"
        $.getJSON(request, function (data) {
            if (data.state == 200) {
                for (key in data.totals) {


                    $("." + key).html(data.totals[key])


                }


            }
        })

    }

</script>