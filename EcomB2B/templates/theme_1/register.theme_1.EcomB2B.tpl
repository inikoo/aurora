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
                    <form id="sky-form" class="sky-form">
                        <header id="_title" contenteditable="true">{$content._title}</header>

                        <fieldset>


                            <section>
                                <label class="input">
                                    <i class="icon-append fa fa-envelope-o"></i>
                                    <input class="register_field" type="email" name="email" id="_email_placeholder" placeholder="{$content._email_placeholder}">
                                    <b id="_email_tooltip" class="tooltip tooltip-bottom-right">{$content._email_tooltip}</b>
                                </label>
                            </section>

                            <section>
                                <label class="input">
                                    <i id="_password" onclick="show_edit_input(this)" class="icon-append icon-lock"></i>
                                    <input class="register_field" type="password" name="password" id="_password_placeholder" placeholder="{$content._password_placeholder}" >
                                    <b id="_password_tooltip"  class="tooltip tooltip-bottom-right">{$content._password_tooltip}</b>
                                </label>
                            </section>

                            <section>
                                <label class="input">
                                    <i id="_password_conform" onclick="show_edit_input(this)" class="icon-append icon-lock"></i>
                                    <input class="register_field" type="password" name="password" id="_password_confirm_placeholder" placeholder="{$content._password_confirm_placeholder}" >
                                    <b id="_password_conform_tooltip"  class="tooltip tooltip-bottom-right">{$content._password_conform_tooltip}</b>
                                </label>
                            </section>
                        </fieldset>

                        <fieldset>

                            <section>
                                <label class="input">
                                    <i class="icon-append icon-mobile-phone"></i>
                                    <input class="register_field" type="text" name="mobile" id="_mobile_placeholder" placeholder="{$content._mobile_placeholder}">
                                    <b id="_mobile_tooltip"  class="tooltip tooltip-bottom-right">{$content._mobile_tooltip}</b>
                                </label>
                            </section>

                            <section>
                                <label class="input">
                                    <i class="icon-append icon-user"></i>
                                    <input class="register_field" type="text" name="contact_name" id="_contact_name_placeholder" placeholder="{$content._contact_name_placeholder}">
                                    <b id="_contact_name_tooltip"  class="tooltip tooltip-bottom-right">{$content._contact_name_tooltip}</b>
                                </label>
                            </section>

                            <section>
                                <label class="input">
                                    <i class="icon-append icon-briefcase"></i>
                                    <input class="register_field" type="text" name="company" id="_company_placeholder" placeholder="{$content._company_placeholder}">
                                    <b id="_company_tooltip"  class="tooltip tooltip-bottom-right">{$content._company_tooltip}</b>
                                </label>
                            </section>


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


</script>

</body>

</html>

