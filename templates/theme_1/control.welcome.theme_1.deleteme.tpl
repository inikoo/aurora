{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 March 2017 at 16:10:07 GMT+8, Sanur, Bali, Kuala Lumpur
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


<span id="show_welcome" class="button " onClick="change_webpage_element_visibility(this)">
        <i class="fa fa-gift discreet" style="margin-left:20px" aria-hidden="true"></i> {t}Intro{/t} <i class="fa fa-check {if $content.show_welcome==1}success{else}very_discreet{/if}" aria-hidden="true"></i>
    </span>


<span id="show_about" class="button " onClick="change_webpage_element_visibility(this)">
        <i class="fa fa-hand-spock-o discreet" style="margin-left:20px" aria-hidden="true"></i> {t}About us{/t} <i class="fa fa-check {if $content.show_about==1}success{else}very_discreet{/if}" aria-hidden="true"></i>
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