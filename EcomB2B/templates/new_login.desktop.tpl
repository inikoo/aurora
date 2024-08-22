
<div style="background: white; width: 85%; margin: auto; padding: 20px 50px; color: #4b5058">
    <div id="login_form_container" class="xxlogin_form" style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); column-gap: 55px">
        <form action="" id="login_form" class="xxsky-form" novalidate="novalidate">
            <header style="font-size: 1.5rem; font-weight: 700; text-align: center; margin-bottom: 40px; letter-spacing: 0.08em">{$data.labels._title} NEW VERSION</header>
    
            <fieldset style="border: none; padding: 0">
                <section style="margin-bottom: 20px; display: inline-block; width: 100%">
                    <label class="" style="display: inline-block; font-weight: 600; margin-bottom: 10px;">{$data.labels._email_label}</label>
                    <input id="handle" type="email" name="email" class="valid" style="display: block; width: 100%; border: 1px solid #4b5058; padding: 10px 14px; box-sizing: border-box">
                </section>

                <section style="margin-bottom: 20px;">
                    <label class="" style="display: inline-block; font-weight: 600; margin-bottom: 10px;">{$data.labels._password_label}</label>
                    <div class="input state-success">
                        <input id="pwd" type="password" name="password" class="valid" style="display: block; width: 100%; border: 1px solid #4b5058; padding: 10px 14px; box-sizing: border-box">
                    </div>
                    <div style="margin-top: 3px"><span id="open_recovery" class="like_link" style="text-decoration: underline;">{$data.labels._forgot_password_label}</span></div>
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
            <div style="width: 100%">
                <button id="login_button" type="submit" style="background: #4b5058; color: white; font-weight: 700; display: block; border-radius: 5px; width: 100%; padding: 10px 14px; font-size: 17px; text-align: center; cursor: pointer; margin-bottom: 10px;">
                    {$data.labels._log_in_label}
                </button>
                <div style="margin: auto">
                    <div style="text-align: center;">Need Help Loging in? Hit the chat button or call us:</div>
                    <div style="text-align: center; font-weight: 600;">+44 (0) 1142 729 165</div>
                </div>
            </div>
        </form>

        <div class="xxlogin_form" style="display: flex; flex-direction: column;">
            <div style="font-size: 1.5rem; font-weight: 700; text-align: center; margin-bottom: 30px; letter-spacing: 0.08em">Don't have an account yet?</div>
            
            <div style="display: flex; flex-direction: column; align-items: center; margin-bottom: 40px">
                <a href="/register.sys">
                    <button id="register_button" type="submit" style="background: white; border: 1px solid #4b5058; color: #4b5058; font-weight: 700; display: block; border-radius: 5px; width: fit-content; padding: 8px 14px; font-size: 17px; text-align: center; cursor: pointer; margin-bottom: 2px;">
                        {$data.labels._register_label}
                    </button>
                </a>
                <div class="tw-text-red-500" style="font-style: italic; font-size: 0.85rem; letter-spacing: 0.025em">* For trade customers only.</div>
            </div>

            <div>
                <div class="tw-text-primary" style="text-align: center;">
                    Register and get <span style="color: #e87928; font-weight: 600;">10% OFF</span> on your first order, also:
                </div>
                
                <div style="background: #ececec; width: 80%; margin: auto; padding: 5px 10px">
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
