
<div class="tw-text-color1 tw-bg-white tw-w-[85%] tw-mx-auto tw-py-[20px] tw-px-[50px]">
    <div id="login_form_container" class="xxlogin_form ">
        <div class="tw-grid tw-grid-cols-2 tw-gap-x-[55px]">
            <form action="" id="login_form" class="xxsky-form" novalidate="novalidate">
                <header style="font-size: 1.5rem; font-weight: 700; text-align: center; margin-bottom: 40px; letter-spacing: 0.08em">{$data.labels._title} NEW VERSION</header>
                <fieldset style="border: none; padding: 0">
                    <section class="tw-mt-5 tw-w-full tw-inline-block" style="">
                        <label class="" style="display: inline-block; font-weight: 600; margin-bottom: 10px;">{$data.labels._email_label}</label>
                        <input id="handle" type="email" name="email" class="valid tw-border tw-border-gray-400 tw-block tw-w-full tw-rounded" style="padding: 10px 14px; box-sizing: border-box">
                    </section>
                    <section class="tw-mt-5">
                        <label class="" style="display: inline-block; font-weight: 600; margin-bottom: 10px;">{$data.labels._password_label}</label>
                        <div class="input state-success">
                            <input id="pwd" type="password" name="password" class="valid tw-border tw-border-gray-400 tw-w-full tw-block tw-rounded" style="padding: 10px 14px; box-sizing: border-box">
                        </div>
                        <div style="margin-top: 3px"><span id="open_recovery" class="like_link tw-cursor-pointer" style="text-decoration: underline;">{$data.labels._forgot_password_label}</span></div>
                    </section>
            
                    <section class="hide">
                        <div class="row">
                            <div class="col col-4"></div>
                            <div class="col col-8">
                                <label class="checkbox"><input id="keep_logged" type="checkbox" name="remember">
                                    <i></i>Keep me logged in
                                </label>
                            </div>
                        </div>
                    </section>
                </fieldset>
                <div class="tw-w-full">
                    <button id="login_button" type="submit" class="tw-bg-color1 hover:tw-gray-600 tw-border-none tw-cursor-pointer tw-mb-2.5 tw-text-white tw-block tw-rounded-md tw-w-full tw-text-center" style="font-weight: 700; padding: 10px 14px; font-size: 17px;">
                        {$data.labels._log_in_label}
                    </button>
                    <div style="margin: auto">
                        <div class="tw-text-center" style="">Need Help Loging in? Hit the chat button or call us:</div>
                        <div class="tw-text-center" style="font-weight: 600;">+44 (0) 1142 729 165</div>
                    </div>
                </div>
            </form>
            <div class="xxlogin_form" style="display: flex; flex-direction: column;">
                <div style="font-size: 1.5rem; font-weight: 700; text-align: center; margin-bottom: 30px; letter-spacing: 0.08em">Don't have an account yet?</div>
            
                <div style="display: flex; flex-direction: column; align-items: center; margin-bottom: 40px">
                    <a href="/register.sys" class="hover:tw-no-underline">
                        <button id="register_button" type="submit" class="tw-bg-white hover:tw-bg-gray-100" style="border: 1px solid #4b5058; color: #4b5058; font-weight: 700; display: block; border-radius: 5px; width: fit-content; padding: 7px 14px; font-size: 17px; text-align: center; cursor: pointer; margin-bottom: 2px;">
                            {$data.labels._register_label}
                        </button>
                    </a>
                    <div class="tw-italic" style="font-size: 0.85rem; letter-spacing: 0.025em">* For trade customers only.</div>
                </div>
                <div>
                    <div class="" style="text-align: center;">
                        Register and get <span class="tw-text-color3" style="font-weight: 600;">10% OFF</span> on your first order, also:
                    </div>
            
                    <div class="tw-bg-gray-100 tw-mx-auto tw-py-[5px] tw-px-[10px] tw-w-[80%]">
                        <ul style="list-style-position: inside; margin: 0px">
                            <li style="padding: 2px 0">View Wholesale Prices.</li>
                            <li style="padding: 2px 0">See The Available Stock.</li>
                            <li style="padding: 2px 0">See What Products are Now on Offer.</li>
                            <li style="padding: 2px 0">Crate Your Favourite Product List.</li>
                            <li style="padding: 2px 0">Receive Exclusive Offers.</li>
                            <li style="padding: 2px 0">Get The Back In Stock Notification.</li>
                            <li style="padding: 2px 0">Become a Gold Reward Member.</li>
                        </ul>
            
                    </div>
                </div>
            </div>
        </div>
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
</div>
