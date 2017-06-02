{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 March 2017 at 16:10:07 GMT+8, Sanur, Bali, Kuala Lumpur
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}


<span id="show_img" class="button " onClick="change_webpage_element_visibility(this)">
        <i class="fa fa-image discreet" style="margin-left:20px" aria-hidden="true"></i> {t}Image{/t} <i class="fa fa-check {if $content.show_img==1}success{else}very_discreet{/if}" aria-hidden="true"></i>
    </span>


<span id="show_countdown" class="{if $content._launch_date!=''}button{/if}  " onClick="change_webpage_element_visibility(this)">
        <i class="fa fa-clock-o discreet" style="margin-left:20px" aria-hidden="true"></i> <span class="{if $content._launch_date==''}strikethrough{/if}">{t}Countdown{/t}</span> <i
                    class="fa fa-check {if $content._launch_date==''}super_discreet{/if}  {if $content.show_countdown==1}success{else}very_discreet{/if}" aria-hidden="true"></i>
    </span>


<span id="show_email_form" class="button " onClick="change_webpage_element_visibility(this)">
        <i class="fa fa-envelope-o discreet" style="margin-left:20px" aria-hidden="true"></i> {t}Email{/t} <i class="fa fa-check {if $content.show_email_form==1}success{else}very_discreet{/if}" aria-hidden="true"></i>
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