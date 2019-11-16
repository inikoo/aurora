{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 May 2018 at 20:22:57 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3
-->
*}



<div class="subject_profile" >
    <div id="contact_data">

        <div class="data_container">
            <h1 class="strong" style="padding-bottom: 20px"> {$published_email->get('Subject')}</h1>

            <div class="data_field">
                <i  class="fa fa-male fa-fw"></i> <span
                        >{$receiver->get('Name')}</span>
            </div>
            <div class="data_field">
                <i  class="fa fa-at fa-fw"></i> <span
                        >{$receiver->get('Main Plain Email')}</span>
            </div>

        </div>


        <div style="clear:both">
        </div>
    </div>
    <div id="info">
        <div id="overviews" style="position: relative;top:-15px">


            <h1 style="margin-bottom: 10px">{$email_tracking->get('State Label')}</h1>

            <table class="overview">



                <tr class=" {if $email_tracking->get('Email Tracking Sent Date')!=''}hide{/if}">
                    <td>{t}Created{/t}:</td>
                    <td class="aright">{$email_tracking->get('Created Date')}</td>
                </tr>



                <tr class=" {if $email_tracking->get('Email Tracking Sent Date')==''}hide{/if}">
                    <td>{t}Sent{/t}</td>
                    <td class="aright ">{$email_tracking->get('Sent Date')}</td>
                </tr>

                <tr  class=" {if $email_tracking->get('Email Tracking First Read Date')==''}hide{/if}">
                    <td>{t}Open{/t}</td>
                    <td class="aright ">{$email_tracking->get('First Read Date')}{}</td>
                </tr>





            </table>

            <style>
                .not_interested_button{
                    clear:both;position:relative;top:10px;margin-top:20px;border:1px solid indianred; lightpink;padding:5px 10px
                }
                .not_interested_button:hover{
                    opacity: 1;
                }

             </style>

            <span  class="button unselectable error very_discreet not_interested_button {if $email_tracking->get('Email Tracking Status')!='Contacted'}hide{/if}" onclick="email_tracking_not_interested(this)" data-key="{$email_tracking->id}">{t}Set as not interested{/t} <i class="fal fa-frown margin_left_5 fa-fw" ></i></span>


        </div>
    </div>
    <div style="clear:both">
    </div>
</div>
