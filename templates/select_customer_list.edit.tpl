{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 March 2018 at 15:09:07 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}

<input class=" Customer_List_Key" id="Customer_List_Key"  value="" type="hidden">
<span class="Customer_List_Select" onclick="change_customer(this)"></span>
<input class="Customer_List_Select_value" value="" ovalue="" placeholder="{t}Customer list name{/t}" parent_key="{$store_key}"  parent="store" scope="customer_lists">
<div class="search_results_container">
    <table class="results" border="1">
        <tr class="hide search_result_template" field="" value="" formatted_value="" onclick="select_dropdown_customer_list(this)">
            <td style="padding-left:20px" class="label"></td>
            <td style="padding-left:20px" class="code"></td>
        </tr>
    </table>
</div>


<script>
    {if $mode=='new'}

    change_customer_list_in_edit_selection()
    {/if}




    function delayed_on_change_customers_dropdown_select_field(object, timeout) {

        var new_value = $(object).val()


        window.clearTimeout(object.data("timeout"));

        object.data("timeout", setTimeout(function () {

            get_customer_list_dropdown_select(object, new_value)
        }, timeout));
    }

    function get_customer_list_dropdown_select(object, new_value) {

        var parent_key = $(object).attr('parent_key')
        var parent = $(object).attr('parent')
        var scope = $(object).attr('scope')



        var request = '/ar_find.php?tipo=find_objects&query=' + fixedEncodeURIComponent(new_value) + '&scope=' + scope + '&parent=' + parent + '&parent_key=' + parent_key + '&state=' + JSON.stringify(state)
        console.log(request)
        $.getJSON(request, function (data) {

            var results_container = $(object).closest('td').find('.search_results_container')


            if (data.number_results > 0) {
                results_container.removeClass('hide').addClass('show')
            } else {


                results_container.addClass('hide').removeClass('show')
                //  $('#' + field).val('')
                // on_changed_value(field, '')
            }


            $(" .result").remove();

            var first = true;

            for (var result_key in data.results) {


                var clone = results_container.find('.search_result_template').clone()
                //         clone.prop('id', field + '_result_' + result_key);


                clone.addClass('result').removeClass('hide search_result_template')
                clone.attr('value', data.results[result_key].value)
                clone.attr('formatted_value', data.results[result_key].formatted_value)


                //  console.log(data.results[result_key].metadata)
                clone.data('metadata', data.results[result_key].metadata)


                //    clone.attr('field', field)
                if (first) {
                    clone.addClass('selected')
                    first = false
                }

                clone.children(".code").html(data.results[result_key].code)

                clone.children(".label").html(data.results[result_key].description)


                results_container.find(".results").append(clone)
                //console.log(results_container.find(".results"))
                //   console.log($('#' + field + '_result_' + result_key).data('metadata'))
            }

        })


    }


    function change_customer(element){
        $(element).html('')
        $(element).closest('td').find('.Customer_List_Key').val('')

        $(element).closest('td').find('.search_results_container').removeClass('hide')
        $(element).closest('td').find('.Customer_List_Select_value').removeClass('hide').val('')

    }

    function select_dropdown_customer_list(element) {


        $(element).closest('td').find('.Customer_List_Select').html($(element).attr('formatted_value')).removeClass('hide')

        $(element).closest('td').find('.Customer_List_Key').val($(element).attr('value'))


        $(element).closest('td').find('.search_results_container').addClass('hide')
        $(element).closest('td').find('.Customer_List_Select_value').addClass('hide')

        $(element).closest('td').find('.search_results_container').find('tr.result').remove()
        on_change_customer_list_list()

    }

    $("#customer_list_container").on("input.Customer_List_Select_value propertychange", function (evt) {

        var delay = 100;
        if (window.event && event.type == "propertychange" && event.propertyName != "value") return;
        delayed_on_change_customers_dropdown_select_field($(evt.target), delay)
    });


    function change_customer_list_in_edit_selection() {

      
        on_change_customer_list_list()

    }



    $(document).on('input propertychange', '.Customer_List_Select_value', function (evt) {
        on_change_customer_list_list()
    });

    function on_change_customer_list_list(element) {


    }




    function post_save_product_customers(data) {
        $('customers_list_items').html(data.update_metadata.customers_list_items)
    }

</script>