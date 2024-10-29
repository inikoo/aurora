{*
<!--
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 July 2017 at 18:52:48 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3
-->
*}

{if isset($data.top_margin)}{assign "top_margin" $data.top_margin}{else}{assign "top_margin" "0"}{/if}
{if isset($data.bottom_margin)}{assign "bottom_margin" $data.bottom_margin}{else}{assign "bottom_margin" "0"}{/if}


<div id="block_{$key}" data-block_key="{$key}" block="{$data.type}" class="{$data.type}  {if !$data.show}hide{/if}"
     style="padding-top:{$top_margin}px;padding-bottom:{$bottom_margin}px">


<div class="password_reset_form" >

                    


                    <form action="" id="password_reset_form" class="sky-form {if $form_error!=''}submited{/if}">

                        <header id="_title" >{$data.labels._title}</header>


                        <fieldset>

                            <section>
                                <label id="_password" class="input " editor="password_input_editor" ">
                                    <i class="icon-append icon-lock" style="cursor:pointer"></i>
                                    <input id="password"   type="password" name="password" placeholder="{$data.labels._password_placeholder}">
                                    <b class="tooltip tooltip-bottom-right">{$data.labels._password_tooltip}</b>
                                </label>
                            </section>

                            <section>
                                <label id="_password_confirm" class="input " editor="password_confirm_input_editor" >
                                    <i class="icon-append icon-lock" style="cursor:pointer"></i>
                                    <input   type="password" name="password_confirm" placeholder="{$data.labels._password_confirm_placeholder}">
                                    <b class="tooltip tooltip-bottom-right">{$data.labels._password_confirm_tooltip}</b>
                                </label>
                            </section>

                        </fieldset>




                        <footer>


                            <button type="submit" name="submit" class="button" id="change_password_button" >{$data.labels._submit_label}  <i  class="margin_left_10 fa fa-fw fa-save" aria-hidden="true"></i>  </button>


                        </footer>


                        <div class="message {if $form_error!=''}error{/if}">


                            <i class="fa {if $form_error!=''}fa-exclamation{else}fa-check{/if}"></i>


                            <span id="password_reset_success_msg"  class=" {if $form_error!=''}hide{/if}">{$data.labels.password_reset_success_msg}</span>
                            <span id="password_reset_expired_token_error_msg"   class=" {if $form_error!='selector_expired'}hide{/if}" >{$data.labels.password_reset_expired_token_error_msg}</span>
                            <span id="password_reset_error_msg"  class=" {if !($form_error=='wrong_hash' or  $form_error=='selector_not_found')}hide{/if}" >{$data.labels.password_reset_error_msg}</span>
                            <span   class=" {if $form_error!='logged_in' }hide{/if}" >{$data.labels.password_reset_logged_in_error_msg}</span>

                            <br>
                            <a href="/login.sys?fp" class=" {if ($form_error=='' or $form_error=='logged_in')   }hide{/if}">{$data.labels.password_reset_go_back}</a>
                            <a href='/' class=" {if !($form_error=='' or $form_error=='logged_in')   }hide{/if}">{$data.labels.password_reset_go_home}</a>


                        </div>

                    </form>

                </div>
                

</div>
