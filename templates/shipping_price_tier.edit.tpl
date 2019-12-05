{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 November 2018 at 14:39:02 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}



<table border=1 id="territories" class="{if $mode=='edit'}hide{/if}  ">
    <tr class="bold hide">
        <td></td>
        <td class="countries" style="width:310px">{t}Country{/t}</td>
        <td class="postal_codes">{t}Postal codes{/t}


        </td>
    </tr>
    <tbody id="territories_items">
    {foreach from=$field.value item=territory}
    <tr class="territory_tr">
        <td><i class="fa fa-trash button invisible" aria-hidden="true" onclick="remove_part(this)"></i><input type="hidden"
                                                                                                    class="part_list_value product_part_key"
                                                                                                    value="{$territory['Key']}"
                                                                                                    ovalue="{$territory['Key']}">
        </td>

        <td class="parts_per_products">





        </td>
        <td class="parts">
            <input type="hidden" class="part_list_value sku" value="{$part_data['Part SKU']}"
                   ovalue="{$part_data['Part SKU']}">
            <span class="Part_Reference">{$part_data['Part']->get('Reference')}</span>
        </td>
        <td class="notes"><input class="part_list_value note" value="{$part_data['Note']}" ovalue="{$part_data['Note']}"
                                 placeholder="{t}Note for pickers{/t}"></td>
    </tr>
{/foreach}
    </tbody>
    <tr id="new_territory_clone" class="territory_tr hide">
        <td class="width_20">
            <i class="far fa-trash-alt button" aria-hidden="true" onclick="remove_territory(this)"></i>
        </td>

        <td class="width_20 territory_country_flag">
            
        </td>
        <td class="territory_country_name">

        </td>


        <td class="postal_codes">

            <span class="button very_discreet_on_hover show_postal_code_condition_buttons" onclick="show_postal_code_condition_buttons(this)"><i class="far fa-map-signs "></i> {t}Add postal code condition{/t}</span>

                <span class="postal_code_condition_buttons hide small">
                <span class="button very_discreet_on_hover"  onclick="included_postal_codes(this)"><i class="fa fa-map-marker-check success"></i> {t}Only this postal codes{/t}</span>
                <span class="margin_left_20 button very_discreet_on_hover" onclick="exclude_postal_codes(this)"><i class="fa fa-map-marker-minus error margin_left_50"></i> {t}without this postal codes{/t}</span>
            </span>
            <span class="territories_conditional_postal_codes hide">
                  <i class="far fa-broom margin_right_10 button" onclick="clean_postal_code_conditions(this)" title="{t}Clean{/t}"></i> <i class="fa fa-map-marker-check success condition_type"></i> <input class="postal_codes  width_500" value="" ovalue="" placeholder="{t}Postal codes ranges or Regex{/t}">
            </span>
        </td>

    </tr>
    <tr class="add_new_part_tr">
        <td colspan=4>

            <input id="add_territories_country_input" clxass="input_field  country_select width_250"  value="" has_been_changed="0"  />

            <script>


                $.fn.countrySelect.setCountryData({$field.country_list});

                $("#add_territories_country_input").countrySelect();

                $("#add_territories_country_input").countrySelect("selectCountry",'{$field.default_country|lower}') ;



            </script>

            <span onclick="add_territory()" class="button">{t}Add country{/t} <i class="fa fa-plus"></i></span>
        </td>

    </tr>
</table>

<script>

    function clean_postal_code_conditions(element){

        $(element).closest('td').find('.show_postal_code_condition_buttons').removeClass('hide')
        $(element).closest('td').find('.postal_code_condition_buttons').addClass('hide')
        $(element).closest('td').find('.territories_conditional_postal_codes').addClass('hide')


        $(element).closest('td').find('.territories_conditional_postal_codes input').val('')

    }

    function included_postal_codes(element){
        $(element).closest('td').find('.postal_code_condition_buttons').addClass('hide')
        $(element).closest('td').find('.territories_conditional_postal_codes').removeClass('hide')
        $(element).closest('td').find('.territories_conditional_postal_codes i.condition_type').addClass('fa-map-marker-minus  error').removeClass('fa-map-marker-check success')

    }

    function included_postal_codes(element){
        $(element).closest('td').find('.postal_code_condition_buttons').addClass('hide')
        $(element).closest('td').find('.territories_conditional_postal_codes').removeClass('hide')
        $(element).closest('td').find('.territories_conditional_postal_codes i.condition_type').removeClass('fa-map-marker-minus  error').addClass('fa-map-marker-check success')

    }


    function show_postal_code_condition_buttons(element){
        $(element).closest('td').find('.show_postal_code_condition_buttons').addClass('hide')
        $(element).closest('td').find('.postal_code_condition_buttons').removeClass('hide')
    }

    function remove_territory(element) {





        if ($(element).closest('tr').hasClass('new')) {

            $(element).closest('tr').remove()


        } else {
            if ($(element).closest('tr').hasClass('very_discreet')) {

                $(element).closest('tr').removeClass('very_discreet')
                $(element).closest('tr').find('.Part_Reference').removeClass('deleted')
                $(element).closest('tr').find('.part_list_value').prop('readonly', false);

            } else {
                $(element).closest('tr').addClass('very_discreet')
                $(element).closest('tr').find('.Part_Reference').addClass('deleted')
                $(element).closest('tr').find('.part_list_value').prop('readonly', true);
            }
        }

      //  on_change_part_list()

    }


   

    function add_territory() {

        var territory_countries=[]

        $('#territories_items tr.territory_tr').each(function(i, obj) {
            territory_countries.push($(obj).attr('iso2'))
        });

        var selected_country = $("#add_territories_country_input").countrySelect("getSelectedCountryData");

        var all_countries_data = $.fn.countrySelect.getCountryData();




        //console.log(all_countries_data)

        var clone = $('#new_territory_clone').clone()
        clone.prop('id', '')
        clone.removeClass('hide').addClass('new')
        clone.attr('iso2', selected_country.iso2)
        clone.find('.territory_country_flag').html('<img src="/art/flags/'+selected_country.iso2+'.png" title="'+selected_country.iso2+'" />')
        clone.find('.territory_country_name').html(selected_country.name)

        $("#territories_items").append(clone);


        territory_countries.push(selected_country.iso2)

        for (var key in all_countries_data) {

            var iso2=all_countries_data[key].iso2;
            console.log(territory_countries)

            if(territory_countries.indexOf(iso2)==-1){
                $("#add_territories_country_input").countrySelect("selectCountry", iso2);
                break;
            }


        }


    }

 


</script>