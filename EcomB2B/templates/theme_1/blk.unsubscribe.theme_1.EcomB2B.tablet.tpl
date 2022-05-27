{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 September 2018 at 13:26:24 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}




{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "20"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "20"}{/if}


<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class="_block {if !$data.show}hide{/if}" top_margin="{$top_margin}" bottom_margin="{$bottom_margin}"
     style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">

    {if !empty($is_prospect)}

    <div style="text-align:center;margin-top: 100px;margin-bottom: 200px ">
        {if !empty($data.labels._removed_from_mailing_list)}{$data.labels._removed_from_mailing_list}{else}{t}You have been removed from the mailing list{/t}{/if}
    </div>
    {else}
    <div class="block reg_form" style="max-width: 500px;margin:auto">
        <form id="unsubscribe" class="sky-form  {if $unsubscribe_customer_key==''}submited{/if} ">
            <header  >{$data.labels._unsubscribe_title}  </header>
            {if $unsubscribe_customer_key!=''}
            <fieldset style="padding-top: 10px">

                <label  class="label"> <div >{$unsubscribe_customer->get('Name')}</div> <div style="margin-top:2px;font-size: 90%">{$unsubscribe_customer->get('Main Plain Email')}</div></label>

            </fieldset>

            <fieldset>
                <label   class="label">{$data.labels._unsubscribe_text}</label>
            </fieldset>

            <fieldset >

                <section>
                    <label style="border:none" class="checkbox "><input type="checkbox" {if $unsubscribe_customer->get('Customer Send Newsletter')=='Yes'}checked{/if} name="newsletter"><i></i>{$data.labels._newsletter}</label>
                    <label style="border:none" class="checkbox "><input type="checkbox" {if $unsubscribe_customer->get('Customer Send Email Marketing')=='Yes'}checked{/if} name="email_marketing"><i></i>{$data.labels._marketing_emails}</label>
                    <label style="border:none" class="checkbox "><input type="checkbox" {if $unsubscribe_customer->get('Customer Send Basket Emails')=='Yes'}checked{/if} name="basket_emails"><i></i>{if !empty($data.labels._basket_emails)}{$data.labels._basket_emails}{else}{t}Email engagement{/t}{/if}</label>

                </section>
            </fieldset>

            {/if}
            <footer>
                <button type="submit" class="button " id="_save_unsubscribe_label" >{$data.labels._save_unsubscribe_label}</button>
            </footer>




            <div class="message error">


                <i class="fa fa-exclamation"></i>

                {if $logged_in}
                    <span id="unsubscribe_error_msg"  class=" {if $unsubscribe_customer_key!=''}hide{/if}">{if !empty($data.labels._unsubscribe_error_logged_in_msg)}{$data.labels._unsubscribe_error_logged_in_msg}{else}{t}Please, go to your profile and unsubscribe{/t}{/if}</span>
                    <br>
                    <a href="/profile.sys" class="{if $unsubscribe_customer_key!=''}hide{/if}">{if !empty($data.labels._unsubscribe_error_profile_link)}{$data.labels._unsubscribe_error_profile_link}{else}{t}Profile{/t}{/if}</a>

                {else}
                    <span id="unsubscribe_error_msg"  class=" {if $unsubscribe_customer_key!=''}hide{/if}">{if !empty($data.labels._unsubscribe_error_msg)}{$data.labels._unsubscribe_error_msg}{else}{t}Please log in to unsubscribe{/t}{/if}</span>
                    <br>
                    <a href="/login.sys?fp" class="{if $unsubscribe_customer_key!=''}hide{/if}">{if !empty($data.labels._unsubscribe_error_login_link)}{$data.labels._unsubscribe_error_login_link}{else}{t}Log in{/t}{/if}</a>
                {/if}

            </div>



        </form>
    </div>
    <script>



        $("form").on('submit', function (e) {

            e.preventDefault();
            e.returnValue = false;

        });


        $("#unsubscribe").validate({

            submitHandler: function (form) {


                var button = $('#_save_unsubscribe_label');

                if (button.hasClass('wait')) {
                    return;
                }

                button.addClass('wait')
                button.find('i').removeClass('fa-save').addClass('fa-spinner fa-spin')


                var register_data = {}

                    $("#unsubscribe input:not(.ignore)").each(function (i, obj) {
                        if (!$(obj).attr('name') == '') {


                            if ($(obj).attr('type') == 'checkbox') {
                                register_data[$(obj).attr('name')] = $(obj).is(':checked')
                            } else {
                                register_data[$(obj).attr('name')] = $(obj).val()
                            }

                        }

                    });




                var ajaxData = new FormData();

                ajaxData.append("tipo", 'unsubscribe')
                ajaxData.append("selector", '{$selector}')

                ajaxData.append("authenticator", '{$authenticator}')

                ajaxData.append("data", JSON.stringify(register_data))


                $.ajax({
                    url: "/ar_web_unsubscribe.php", type: 'POST', data: ajaxData, dataType: 'json', cache: false, contentType: false, processData: false, complete: function () {
                    }, success: function (data) {

                        console.log(data)

                        if (data.state == '200') {

                            for (var key in data.metadata.class_html) {
                                $('.' + key).html(data.metadata.class_html[key])
                            }

                            swal("{t}Saved{/t}")
                        } else if (data.state == '400') {
                            swal("{t}Error{/t}!", data.msg, "error")
                        }


                        button.removeClass('wait').addClass('invisible')
                        button.find('i').addClass('fa-save').removeClass('fa-spinner fa-spin')


                    }, error: function () {
                        button.removeClass('wait')
                        button.find('i').addClass('fa-save').removeClass('fa-spinner fa-spin')

                    }
                });


            },

            // Rules for form validation
            rules: {



            },

            // Messages for form validation
            messages: {



            },

            // Do not change code below
            errorPlacement: function (error, element) {
                error.insertAfter(element.parent());
            }
        });




    </script>
    {/if}
</div>

