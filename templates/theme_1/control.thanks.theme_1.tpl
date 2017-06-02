{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 May 2017 at 09:47:19 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


<span id="show_thanks" class="button " onClick="change_webpage_element_visibility(this)">
        <i class="fa fa-smile-o discreet" style="margin-left:20px" aria-hidden="true"></i> {t}Intro{/t} <i class="fa fa-check {if $content.show_thanks==1}success{else}very_discreet{/if}" aria-hidden="true"></i>
    </span>


<span id="show_order" class="button " onClick="change_webpage_element_visibility(this)">
        <i class="fa fa-shopping-cart discreet" style="margin-left:20px" aria-hidden="true"></i> {t}Order{/t} <i class="fa fa-check {if $content.show_order==1}success{else}very_discreet{/if}" aria-hidden="true"></i>
    </span>



<span id="show_telephone" class="button " onClick="change_webpage_element_visibility(this)">
        <i class="fa fa-phone discreet" style="margin-left:20px" aria-hidden="true"></i> {t}Contact{/t} <i class="fa fa-check {if $content.show_telephone==1}success{else}very_discreet{/if}" aria-hidden="true"></i>
    </span>

<script>

    function change_webpage_element_visibility(element) {


        if ($(element).hasClass('button')) {


            if ($(element).find('i.fa-check').hasClass('success')) {
                $(element).find('i.fa-check').removeClass('success').addClass('very_discreet')
                $('#preview')[0].contentWindow.change_webpage_element_visibility($(element).attr('id'), 'hide')
            } else {
                $(element).find('i.fa-check').addClass('success').removeClass('very_discreet')
                $('#preview')[0].contentWindow.change_webpage_element_visibility($(element).attr('id'), 'show')

            }


        }
    }

 </script>