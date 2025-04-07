{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 April 2018 at 00:34:51 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}


<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class="{$data.type}  {if !$data.show}hide{/if}"
     style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">


    {if true}
        {include file="new_login.desktop.tpl"}
    {else}
    <div id="login_form_container" class="login_form" >
        <form action="" id="login_form" class="sky-form">
            <header>{$data.labels._title}</header>

            <fieldset>
                <section>
                    <div class="row">
                        <label class="label col col-4">{$data.labels._email_label}</label>
                        <div class="col col-8">
                            <label class="input">
                                <i class="icon-append far fa-envelope"></i>
                                <input id="handle" type="email" name="email">
                            </label>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="row">
                        <label class="label col col-4">{$data.labels._password_label}</label>
                        <div class="col col-8">
                            <label class="input">
                                <i class="icon-append far fa-lock"></i>
                                <input id="pwd" type="password" name="password">
                            </label>
                            <div><span id="open_recovery" class="like_link">{$data.labels._forgot_password_label}</span></div>
                        </div>
                    </div>
                </section >

                <section class="hide">
                    <div class="row">
                        <div class="col col-4"></div>
                        <div class="col col-8">
                            <label class="checkbox"><input id="keep_logged" type="checkbox" name="remember" ><i></i>{$data.labels._keep_logged_in_label}</label>
                        </div>
                    </div>
                </section>
            </fieldset>
            <footer>
                <button id="login_button" type="submit" class="button">{$data.labels._log_in_label}  <i  class="fa fa-fw  fa-arrow-right" aria-hidden="true"></i> </button>
                <a href="/register.sys" class="button button-secondary">{$data.labels._register_label}</a>
            </footer>
        </form>

    </div>

    <div id="recovery_form_container" class="login_form hide" >
        <form action="" id="password_recovery_form" class="sky-form "  >
            <header>{$data.labels._title_recovery}</header>

            <fieldset>
                <section>
                    <label class="label"{$data.labels._email_recovery_label}</label>
                    <label class="input">
                        <i class="icon-append far fa-envelope"></i>
                        <input type="email" name="email" id="recovery_email">
                    </label>
                </section>
            </fieldset>

{*            {if !empty($settings.fu_key)}*}
{*                <footer>*}
{*                    <div class="cf-turnstile" data-action="reset_password_desktop" data-sitekey="{$settings.fu_key}"></div>*}
{*                </footer>*}
{*            {/if}*}
            <footer>
                <button id="recovery_button" type="submit" name="submit" class="button">{$data.labels._submit_label} <i  class="fa fa-fw  fa-arrow-right" aria-hidden="true"></i> </button>
                <button id="close_recovery" class="button button-secondary modal-closer">{$data.labels._close_label}</button>
            </footer>

            <div class="message" >
                <i class="fa fa-check"></i>
                <span class="password_recovery_msg hide" id="password_recovery_success_msg"  >{$data.labels._password_recovery_success_msg}</span>
                <span class="password_recovery_msg error hide" id="password_recovery_email_not_register_error_msg"  >{$data.labels._password_recovery_email_not_register_error_msg}</span>
                <span class="password_recovery_msg error hide" id="password_recovery_unknown_error_msg" >{$data.labels._password_recovery_unknown_error_msg}</span>
                <span class="password_recovery_msg error hide" id="password_recovery_waiting_approval_error_msg" >{if empty($data.labels._password_recovery_unknown_error_msg)}{t}Account waiting for approval{/t}{else}{$data.labels._password_recovery_unknown_error_msg}{/if}</span>



                <br>
                <a href="login"  class="modal-closer" id="password_recovery_go_back" >{$data.labels._password_recovery_go_back}</a>


            </div>
        </form>
    </div>
    {/if}

</div>


<script>



</script>


