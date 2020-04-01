{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 April 2018 at 21:46:49 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

<style>
    .top_up_options{
        display: flex;
        margin: 10px auto;
        width: 400px;
    }
    .top_up_options div{
        text-align: center;
        width: 100px;
        border:1px solid #ccc;
        border-left:none;
        font-size: 22px;
        padding: 10px;
        font-weight: 800;
    }


    .top_up_options div:first-child {
        border-left:1px solid #ccc;
    }
</style>

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "20"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "20"}{/if}


<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class="{$data.type} _block {if !$data.show}hide{/if}" top_margin="{$top_margin}" bottom_margin="{$bottom_margin}"
     style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">


    <div class="container">




        <div style="text-align: center;margin-top: 30px;margin-bottom: 30px">

            <h1 id="_main_title" contenteditable="true">{if empty($data.labels._main_title)}{t}Top up{/t}{else}{$data.labels._main_title}{/if}</h1>

            <div class="top_up_options" >

                <div>{$currency_symbol}20</div>
                <div>{$currency_symbol}50</div>
                <div>{$currency_symbol}100</div>
                <div>{$currency_symbol}250</div>

            </div>

        </div>

    </div>




        <div class="clear" style="margin-bottom: 30px"> </div>

    <div class="container clear">

        <ul class="tabs3">
            <li><a href="#example-3-tab-1" target="_self"><i class="fa fa-credit-card" aria-hidden="true"></i> <span contenteditable="true" id="_credit_card_label">{if empty($data.labels._credit_card_label) }{t}Credit card{/t}{else}{$data.labels._credit_card_label}{/if}</span></a></li>
            <li><a href="#example-3-tab-2" target="_self"><i class="fab fa-paypal" aria-hidden="true"></i>&nbsp; Paypal</a></li>
             <li ><a href="#example-3-tab-5" target="_self"><i class="fa fa-hand-peace" aria-hidden="true"></i> <span >Sofort</span></a></li>

        </ul>

        <div class="tabs-content3 two">

            <div id="example-3-tab-1" class="tabs-panel3">


                <form action="" class="sky-form" style="max-width: 500px;">
                    <header id="_form_title_credit_card" contenteditable="true">{$data.labels._form_title_credit_card}</header>


                    <fieldset>


                        <div class="row">
                            <section class="col col-9">
                                <label class="input">
                                    <input type="text" id="_credit_card_number" style="color:lightgrey" value="{$data.labels._credit_card_number}">
                                </label>
                            </section>
                            <section class="col col-3">
                                <label class="input">
                                    <input type="text" maxlength="4" id="_credit_card_ccv" style="color:lightgrey" value="{$data.labels._credit_card_ccv}">
                                </label>
                            </section>
                        </div>

                        <div class="row">
                            <label class="label col col-4" id="_credit_card_expiration_date" contenteditable="true">{$data.labels._credit_card_expiration_date}</label>
                            <section class="col col-5">
                                <label class="input">
                                    <input type="text" id="_credit_card_expiration_date_month_label" style="color:lightgrey" value="{$data.labels._credit_card_expiration_date_month_label}">
                                </label>
                            </section>
                            <section class="col col-3">
                                <label class="input">
                                    <input type="text" maxlength="4" id="_credit_card_expiration_date_year_label" style="color:lightgrey" value="{$data.labels._credit_card_expiration_date_year_label}">
                                </label>
                            </section>
                        </div>


                        <div class="row">

                            <section class="col col-5">

                                <i class="far fa-square"></i> <span style="	font: 14px/1.55 'Open Sans', Helvetica, Arial, sans-serif;" id="_credit_card_save" contenteditable="true"> {$data.labels._credit_card_save}</span>

                            </section>


                        </div>


                    </fieldset>

                    <footer>
                        <button class="button"><span  id="_pay_top_up_from_credit_card" contenteditable="true">{if empty($data.labels._pay_top_up_from_credit_card) } &nbsp;{else}{$data.labels._pay_top_up_from_credit_card}{/if}</span></button>
                    </footer>
                </form>


            </div>

            <div id="example-3-tab-2" class="tabs-panel3">

                <form action="" class="sky-form" style="max-width: 500px;">
                    <header id="_form_title_paypal" contenteditable="true">{if isset($data.labels._form_title_paypal) }{$data.labels._form_title_paypal}{else}{t}Checkout form{/t}{/if}</header>


                    <fieldset>


                        <img src="/art/paypal_mockup_button.png">

                    </fieldset>

                    <footer>

                    </footer>
                </form>

            </div>

            <div id="example-3-tab-5" class="tabs-panel3">


                <form action="" class="sky-form" style="max-width: 500px;">
                    <header id="_form_title_online_bank_transfer" contenteditable="true">{if !empty($data.labels._form_title_online_bank_transfer)}{{$data.labels._form_title_online_bank_transfer}}{else}Checkout form{/if}</header>


                    <div style="padding:20px">
                        <p id="_online_bank_transfer_text" contenteditable="true" >{if !empty($data.labels._online_bank_transfer_text)}{{$data.labels._online_bank_transfer_text}}{else}Pay with Sofort{/if}</p>

                    </div>


                    <footer>
                        <button class="button" id="_pay_top_up_from_online_bank_transfer" contenteditable="true">{if !empty($data.labels._pay_top_up_from_online_bank_transfer)}{{$data.labels._pay_top_up_from_online_bank_transfer}}{else}Place Order{/if}</button>
                    </footer>
                </form>


            </div>


        </div>


    </div>


        <div class="clear"> </div>
</div>

<script>
    function jQueryTabs3() {
        $(".tabs3").each(function () {
            $(".tabs-panel3").not(":first").hide(), $("li", this).removeClass("active"), $("li:first-child", this).addClass("active"), $(".tabs-panel:first-child").show(), $("li", this).on('click',function (t){
                var i=$("a",this).attr("href");
                $(this).siblings().removeClass("active"),$(this).addClass("active"),$(i).siblings().hide(),$(i).fadeIn(400),t.preventDefault()}), $(window).width() < 100 && $(".tabs-panel3").show()
        })
    }
    jQueryTabs3();
    $(".tabs3 li a").each(function(){
        var t=$(this).attr("href"),i=$(this).html();$(t+" .tab-title3").prepend("<p><strong>"+i+"</strong></p>")})
    $(window).resize(function (){
        jQueryTabs3()

    })
</script>

