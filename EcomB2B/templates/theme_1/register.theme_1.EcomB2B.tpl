{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 June 2017 at 21:06:44 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


{include file="theme_1/_head.theme_1.EcomB2B.tpl"}


<body xmlns="http://www.w3.org/1999/html">


<div class="wrapper_boxed">

    <div class="site_wrapper">

        {include file="theme_1/header.EcomB2B.tpl"}

        <div class="content_fullwidth less2">
            <div class="container">

                <div class="reg_form" >
                    <form id="registration_form" class="sky-form">
                        <header id="_title" contenteditable="true">{$content._title}</header>

                        <fieldset>


                            <section>
                                <label class="input">
                                    <i class="icon-append fa fa-envelope-o"></i>
                                    <input class="register_field" type="email" name="email"  placeholder="{$content._email_placeholder}">
                                    <b id="_email_tooltip" class="tooltip tooltip-bottom-right">{$content._email_tooltip}</b>
                                </label>
                            </section>

                            <section>
                                <label class="input">
                                    <i id="_password"  class="icon-append icon-lock"></i>
                                    <input class="register_field" type="password" name="password" id="register_password" placeholder="{$content._password_placeholder}" >
                                    <b id="_password_tooltip"  class="tooltip tooltip-bottom-right">{$content._password_tooltip}</b>
                                </label>
                            </section>

                            <section>
                                <label class="input">
                                    <i id="_password_confirm" class="icon-append icon-lock"></i>
                                    <input class="register_field ignore" type="password" name="password_confirm" placeholder="{$content._password_confirm_placeholder}" >
                                    <b id="_password_confirm_tooltip"  class="tooltip tooltip-bottom-right">{$content._password_confirm_tooltip}</b>
                                </label>
                            </section>
                        </fieldset>

                        <fieldset>

                            <section>
                                <label class="input">
                                    <i class="icon-append fa fa-mobile" ></i>
                                    <input class="register_field" type="text" name="mobile" placeholder="{$content._mobile_placeholder}">
                                    <b id="_mobile_tooltip"  class="tooltip tooltip-bottom-right">{$content._mobile_tooltip}</b>
                                </label>
                            </section>

                            <section>
                                <label class="input">
                                    <i class="icon-append icon-user"></i>
                                    <input class="register_field" type="text" name="contact_name"  placeholder="{$content._contact_name_placeholder}">
                                    <b id="_contact_name_tooltip"  class="tooltip tooltip-bottom-right">{$content._contact_name_tooltip}</b>
                                </label>
                            </section>

                            <section>
                                <label class="input">
                                    <i class="icon-append icon-briefcase"></i>
                                    <input class="register_field" type="text" name="company"  placeholder="{$content._company_placeholder}">
                                    <b id="_company_tooltip"  class="tooltip tooltip-bottom-right">{$content._company_tooltip}</b>
                                </label>
                            </section>

                            <div class="row">
                                <section class="col col-6 " >
                                    <label id="_tax_number" class="input" >
                                        <input type="text" name="tax_number" placeholder="{if isset($content._tax_number) and $content._tax_number!=''}{$content._tax_number}{else}{t}Tax number{/t}{/if}">
                                        <b id="_tax_number_tooltip"  class="tooltip tooltip-bottom-right">{if isset($content._tax_number) and $content._tax_number!=''}{$content._tax_number}{else}{t}Tax number{/t}{/if}</b>

                                    </label>
                                </section>
                                <section class="col col-6">
                                    <label id="_registration_number" class="input" ">
                                        <input type="text" name="registration_number" placeholder="{if isset($content._registration_number) and $content._registration_number!=''}{$content._registration_number}{else}{t}Registration number{/t}{/if}">
                                        <b id="_registration_number_tooltip"  class="tooltip tooltip-bottom-right">{if isset($content._registration_number) and $content._registration_number!=''}{$content._registration_number}{else}{t}Registration number{/t}{/if}</b>

                                    </label>
                                </section>
                            </div>

                        </fieldset>

                        <fieldset id="address_fields" style="position:relative">



                            <section id="addressLine1" class="{if 'addressLine1'|in_array:$used_address_fields}{else}hide{/if}">

                                <label for="file" class="input">
                                    <input type="text"  name="addressLine1" class="{if 'addressLine1'|in_array:$used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels.address_addressLine1) and $labels.address_addressLine1!=''}{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}">
                                    <b class="tooltip tooltip-bottom-right">{if isset($labels.address_addressLine1) and $labels.address_addressLine1!=''}{$labels.address_addressLine1}{else}{t}Address Line 1{/t}{/if}</b>
                                </label>
                            </section>

                            <section id="addressLine2" class="{if 'addressLine2'|in_array:$used_address_fields}{else}hide{/if}">
                                <label for="file" class="input">
                                    <input type="text" name="addressLine2" class="{if 'addressLine2'|in_array:$used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels.address_addressLine2) and $labels.address_addressLine2!=''}{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}">
                                    <b class="tooltip tooltip-bottom-right">{if isset($labels.address_addressLine2) and $labels.address_addressLine2!=''}{$labels.address_addressLine2}{else}{t}Address Line 2{/t}{/if}</b>
                                </label>
                            </section>



                            <div id="sortingCode" class="row {if 'sortingCode'|in_array:$used_address_fields}{else}hide{/if}">
                                <section class="col col-6 " >
                                    <label class="input">
                                        <input type="text" name="sortingCode" class="{if 'sortingCode'|in_array:$used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels.address_sorting_code) and $labels.address_sorting_code!=''}{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if isset($labels.address_sorting_code) and $labels.address_sorting_code!=''}{$labels.address_sorting_code}{else}{t}Sorting code{/t}{/if}</b>
                                    </label>
                                </section>


                            </div>

                            <div id="postalCode" class="row {if 'postalCode'|in_array:$used_address_fields}{else}hide{/if}">
                                <section class="col col-6 " >
                                    <label class="input">
                                        <input type="text" name="postalCode" class="{if 'postalCode'|in_array:$used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels["postalCode_`$address_labels.postalCode.code`"]) and $labels["postalCode_`$address_labels.postalCode.code`"]!=''}{$labels["postalCode_`$address_labels.postalCode.code`"]}{else}{$address_labels.postalCode.label}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if isset($labels["postalCode_`$address_labels.postalCode.code`"]) and $labels["postalCode_`$address_labels.postalCode.code`"]!=''}{$labels["postalCode_`$address_labels.postalCode.code`"]}{else}{$address_labels.postalCode.label}{/if}</b>
                                    </label>
                                </section>


                            </div>

                            <div id="dependentLocality" class="row {if 'dependentLocality'|in_array:$used_address_fields}{else}hide{/if}">
                                <section class="col col-6 " >
                                    <label class="input">
                                        <input type="text" name="dependentLocality" class="{if 'dependentLocality'|in_array:$used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels["dependentLocality_`$address_labels.dependentLocality.code`"]) and $labels["dependentLocality_`$address_labels.dependentLocality.code`"]!=''}{$labels["dependentLocality_`$address_labels.dependentLocality.code`"]}{else}{$address_labels.dependentLocality.label}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if isset($labels["dependentLocality_`$address_labels.dependentLocality.code`"]) and $labels["dependentLocality_`$address_labels.dependentLocality.code`"]!=''}{$labels["dependentLocality_`$address_labels.dependentLocality.code`"]}{else}{$address_labels.dependentLocality.label}{/if}</b>
                                    </label>
                                </section>

                            </div>

                            <div id="locality" class="row {if 'locality'|in_array:$used_address_fields}{else}hide{/if}">
                                <section class="col col-6 " >
                                    <label class="input">
                                        <input type="text" name="locality" class="{if 'locality'|in_array:$used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels["locality_`$address_labels.locality.code`"]) and $labels["locality_`$address_labels.locality.code`"]!=''}{$labels["locality_`$address_labels.locality.code`"]}{else}{$address_labels.locality.label}{/if}">
                                        <b class="tooltip tooltip-bottom-right"></b>
                                    </label>
                                </section>

                            </div>


                            <div id="administrativeArea" class="row {if 'administrativeArea'|in_array:$used_address_fields}{else}hide{/if}">
                                <section class="col col-6 " >
                                    <label class="input">
                                        <input type="text" name="administrativeArea" class="{if 'administrativeArea'|in_array:$used_address_fields}{else}ignore{/if}" placeholder="{if isset($labels["administrativeArea_`$address_labels.administrativeArea.code`"]) and $labels["administrativeArea_`$address_labels.administrativeArea.code`"]!=''}{$labels["administrativeArea_`$address_labels.administrativeArea.code`"]}{else}{$address_labels.administrativeArea.label}{/if}">
                                        <b class="tooltip tooltip-bottom-right">{if isset($labels["administrativeArea_`$address_labels.administrativeArea.code`"]) and $labels["administrativeArea_`$address_labels.administrativeArea.code`"]!=''}{$labels["administrativeArea_`$address_labels.administrativeArea.code`"]}{else}{$address_labels.administrativeArea.label}{/if}</b>
                                    </label>
                                </section>

                            </div>


                            <div class="row" >
                                <section class="col col-5">
                                    <label class="select">
                                        <select id="country_select" name="country">
                                            <option value="0" selected disabled>{if isset($labels.address_country) and $labels.address_country!=''}{$labels.address_country}{else}{t}Country{/t}{/if}</option>

                                            {foreach from=$countries item=country}
                                                <option value="{$country.2alpha}" {if $country.2alpha==$selected_country}selected{/if} >{$country.name}</option>
                                            {/foreach}


                                            <select><i></i>
                                    </label>
                                </section>


                            </div>


                        </fieldset>

                        <fieldset class="last">


                            <section>
                                <label class="checkbox"><input type="checkbox" name="subscription" id="subscription"><i></i>{$content._subscription}</label>
                                <label class="checkbox"><input type="checkbox" name="terms" id="terms"><i></i>{$content._terms}</label>


                            </section>



                        </fieldset>
                        <footer>
                            <button type="submit" class="button" id="_submit_label" contenteditable="true">{$content._submit_label}</button>
                        </footer>
                    </form>
                </div>


            </div>
        </div>


        <div class="clearfix marb12"></div>

        {include file="theme_1/footer.EcomB2B.tpl"}

    </div>

</div>
<script>

    $( "#country_select" ).change(function() {

        var selected=$( "#country_select option:selected" )
       // console.log(selected.val())

        var request= "ar_web_addressing.php?tipo=address_format&country_code="+selected.val()+'&website_key={$website->id}'

        console.log(request)
        $.getJSON(request, function( data ) {
            console.log(data)
            $.each(data.hidden_fields, function(index, value) {
                $('#'+value).addClass('hide')
                $('#'+value).find('input').addClass('ignore')

            });

            $.each(data.used_fields, function(index, value) {
                $('#'+value).removeClass('hide')
                $('#'+value).find('input').removeClass('ignore')

            });

            $.each(data.labels, function(index, value) {
                $('#'+index).find('input').attr('placeholder',value)
                $('#'+index).find('b').html(value)

            });

            $.each(data.no_required_fields, function(index, value) {


               // console.log(value)

                    $('#'+value+' input').rules( "remove" );




            });

            $.each(data.required_fields, function(index, value) {
                console.log($('#'+value))
                //console.log($('#'+value+' input').rules())

                $('#'+value+' input').rules( "add", { required: true});

            });


        });


    });


    $("form").on('submit', function (e) {

        e.preventDefault();
        e.returnValue = false;

    });


    $("#registration_form").validate(
        {

            submitHandler: function(form)
            {


                var register_data={ }

                $("#registration_form input:not(.ignore)").each(function(i, obj) {
                    if(!$(obj).attr('name')==''){
                        register_data[$(obj).attr('name')]=$(obj).val()
                    }

                });

                $("#registration_form select:not(.ignore)").each(function(i, obj) {
                    if(!$(obj).attr('name')==''){


                        register_data[$(obj).attr('name')]=$(obj).val()
                    }

                });



                register_data['password']=sha256_digest(register_data['password']);

                var ajaxData = new FormData();

                ajaxData.append("tipo", 'register')
                ajaxData.append("store_key", '{$store->id}')
                ajaxData.append("data", JSON.stringify(register_data))


                $.ajax({
                    url: "/ar_web_register.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false,
                    complete: function () {
                    }, success: function (data) {

                        console.log(data)

                        if (data.state == '200') {


                            window.location.replace("welcome.sys");


                        } else if (data.state == '400') {
                            swal("{t}Error{/t}!", data.msg, "error")
                        }



                    }, error: function () {

                    }
                });


            },

            // Rules for form validation
            rules:
                {

                    email:
                        {
                            required: true,
                            email: true,
                            remote: {
                                url: "ar_web_validate.php",
                                data: {
                                    tipo:'validate_email_registered',
                                    website_key:'{$website->id}'
                                }
                            }

                        },
                    password:
                        {
                            required: true,
                            minlength: 8


                        },
                    password_confirm:
                        {
                            required: true,
                            minlength: 8,
                            equalTo: "#register_password"
                        },
                    contact_name:
                        {
                            required: true,

                        },
                    mobile:
                        {
                            required: true,

                        },
                    terms:
                        {
                        required: true,
                    },

                    {foreach from=$required_fields item=required_field }
                    {$required_field}: { required: true },
                    {/foreach}

                    {foreach from=$no_required_fields item=no_required_field }
                        {$no_required_field}:{   required: false},
                    {/foreach}

                },

            // Messages for form validation
            messages:
                {

                    email:
                        {

                            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                                email: '{if empty($labels._validation_email_invalid)}{t}Invalid email{/t}{else}{$labels._validation_email_invalid|escape}{/if}',
                            remote: '{if empty($labels._validation_handle_registered)}{t}Email address is already in registered{/t}{else}{$labels._validation_handle_registered|escape}{/if}',


                        },
                    password:
                        {
                            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                                minlength: '{if empty($labels._validation_minlength_password)}{t}Enter at least 8 characters{/t}{else}{$labels._validation_minlength_password|escape}{/if}',



                        },
                    password_confirm:
                        {
                            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                            equalTo: '{if empty($labels._validation_same_password)}{t}Enter the same password as above{/t}{else}{$labels._validation_same_password|escape}{/if}',

                            minlength: '{if empty($labels._validation_minlength_password)}{t}Enter at least 8 characters{/t}{else}{$labels._validation_minlength_password|escape}{/if}',
                        },
                    contact_name:
                        {
                            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                        },
                    mobile:
                        {
                           required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                        },
                    terms:
                        {
                            required: '{if empty($labels._validation_accept_terms)}{t}Please accept our terms and conditions to proceed{/t}{else}{$labels._validation_accept_terms|escape}{/if}',


                        },
                    administrativeArea:
                        {
                            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                        },
                    locality:
                        {
                            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                        },
                    dependentLocality:
                        {
                            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                        },
                    postalCode:
                        {
                            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                        },
                    addressLine1:
                        {
                            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                        },
                    addressLine2:
                        {
                            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                        },
                    sortingCode:
                        {
                            required: '{if empty($labels._validation_required)}{t}Required field{/t}{else}{$labels._validation_required|escape}{/if}',
                        }




                },

            // Do not change code below
            errorPlacement: function(error, element)
            {
                error.insertAfter(element.parent());
            }
        });


</script>

</body>

</html>

