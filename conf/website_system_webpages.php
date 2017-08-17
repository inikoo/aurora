<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 April 2017 at 19:13:51 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

function website_system_webpages_config($website_type) {


    include_once 'conf/webpage_blocks.php';
    $blocks = get_webpage_blocks();

    $contact_content_data                         = $blocks['two_one'];
    $contact_content_data['columns'][0]['_title'] = '';
    $contact_content_data['columns'][0]['_text']  =
        '<p>Feel free to talk to our online representative at any time you please using our Live Chat system on our website or one of the below instant messaging programs.</p><br /><p>Please be patient while waiting for response. (24/7 Support!) <strong>Phone General Inquiries: 1-888-123-4567-8900</strong></p>';
    $contact_content_data['columns'][1]['_title'] = _('Our Details');
    $contact_content_data['columns'][1]['_text']  = 'Telephone<br/>#tel<br/><br/>Email<br/>#email<br/><br/>Address<br/>#adr<br/><br/> ';


    $EcomB2B = array(


        'home.sys' => array(
            'Webpage Scope'             => 'Homepage',
            'Webpage Template Filename' => 'homepage',
            'Webpage Type'              => 'Home',
            'Webpage Code'              => 'home.sys',
            'Webpage Browser Title'     => _('Homepage'),
            'Webpage Name'              => _("Customer's homepage"),
            'Webpage Meta Description'  => '',

            'Page Store Content Data' => json_encode(
                array(
                    'blocks' => array(


                        $blocks['iframe'],
                        $blocks['six_pack'],
                        $blocks['image'],
                        $blocks['counter'],
                        $blocks['three_pack'],

                    )

                )
            )


        ),

        'home_logout.sys' => array(
            'Webpage Scope'             => 'HomepageLogout',
            'Webpage Template Filename' => 'homepage_logout',
            'Webpage Type'              => 'Home',
            'Webpage Code'              => 'home_logout.sys',
            'Webpage Browser Title'     => _('Homepage'),
            'Webpage Name'              => _("Visitor's homepage"),
            'Webpage Meta Description'  => '',

            'Page Store Content Data' => json_encode(
                array(
                    'blocks' => array(


                        $blocks['iframe'],
                        $blocks['button'],

                        $blocks['six_pack'],
                        $blocks['image'],
                        $blocks['counter'],
                        $blocks['three_pack'],

                    )

                )
            )


        ),
        'home_rookie.sys' => array(
            'Webpage Scope'             => 'HomepageNoOrders',
            'Webpage Template Filename' => 'homepage_no_orders',
            'Webpage Type'              => 'Home',
            'Webpage Code'              => 'home_rookie.sys',
            'Webpage Browser Title'     => _('Homepage'),
            'Webpage Name'              => _("Prospect's homepage"),
            'Webpage Meta Description'  => '',
            'Page Store Content Data'   => json_encode(
                array(
                    'blocks' => array(
                        $blocks['iframe'],
                        $blocks['six_pack'],
                        $blocks['image'],
                        $blocks['counter'],
                        $blocks['three_pack'],

                    )

                )
            )


        ),

        'launching.sys' => array(
            'Webpage Scope'             => 'HomepageToLaunch',
            'Webpage Template Filename' => 'homepage_to_launch',
            'Webpage Type'              => 'Home',
            'Webpage Code'              => 'launching.sys',
            'Webpage Browser Title'     => _('Coming soon'),
            'Webpage Name'              => _('Launching website'),
            'Webpage Meta Description'  => '',
            'Page Store Content Data'   => '',
            'Page Store Content Data'   => json_encode(
                array(
                    'show_img'            => false,
                    'show_countdown'      => false,
                    'show_email_form'     => true,
                    '_img'                => '',
                    '_img_key'            => '',
                    '_title'              => _("We're launching soon"),
                    '_text'               => _('Our website is under construction. We\'ll be here soon with our new awesome site'),
                    '_launch_date'        => '',
                    '_email_placeholder'  => _('Enter email ...'),
                    '_email_submit_label' => _('Submit'),
                    '_day_label'          => _('Days'),
                    '_hrs_label'          => _('Hours'),
                    '_min_label'          => _('Minutes'),
                    '_sec_label'          => _('Seconds'),


                )
            )


        ),


        'login.sys' => array(
            'Webpage Scope'             => 'Login',
            'Webpage Scope Metadata'    => '',
            'Webpage Template Filename' => 'login',
            'Webpage Type'              => 'Customer',
            'Webpage Code'              => 'login.sys',
            'Webpage Browser Title'     => _('Login'),
            'Webpage Name'              => _('Login'),
            'Webpage Meta Description'  => '',
            'Webpage Scope Metadata'    => json_encode(
                array(
                    'emails' => array(

                        'reset_password' => array(
                            'key'           => '',
                            'published_key' => false
                        )
                    )
                )
            ),
            'Page Store Content Data'   => json_encode(
                array(
                    '_title'          => _('Login form'),
                    '_title_recovery' => _('Password recovery'),


                    '_email_label'          => _('E-mail'),
                    '_password_label'       => _('Password'),
                    '_email_recovery_label' => _('E-mail'),


                    '_forgot_password_label' => _('Forgot password?'),
                    '_keep_logged_in_label'  => _('Keep me logged in'),


                    '_register_label' => _('Register'),
                    '_log_in_label'   => _('Log in'),
                    '_submit_label'   => _('Submit'),
                    '_close_label'    => _('Go back'),

                    '_password_recovery_success_msg'                  => _('Your request successfully sent!'),
                    '_password_recovery_email_not_register_error_msg' => _('Email is not registered in our system'),
                    '_password_recovery_unknown_error_msg'            => _("Recovery email could't be send, please contact customer services"),
                    '_password_recovery_go_back'                      => _('Try again'),


                )
            )
        ),

        'register.sys' => array(
            'Webpage Scope'             => 'Register',
            'Webpage Scope Metadata'    => '',
            'Webpage Template Filename' => 'register',
            'Webpage Type'              => 'Customer',
            'Webpage Code'              => 'register.sys',
            'Webpage Browser Title'     => _('Register'),
            'Webpage Name'              => _('Register'),
            'Webpage Meta Description'  => '',
            'Webpage Scope Metadata'    => json_encode(
                array(
                    'emails' => array(

                        'welcome' => array(
                            'key'           => '',
                            'published_key' => false
                        )
                    )
                )
            ),

            'Page Store Content Data' => json_encode(
                array(
                    '_title'             => _('Registration form'),
                    '_email_placeholder' => _('Email address'),
                    '_email_tooltip'     => _('Needed to verify your account'),

                    '_password_placeholder' => _('Password'),
                    '_password_tooltip'     => _("Don't forget your password"),

                    '_password_confirm_placeholder' => _('Confirm password'),
                    '_password_confirm_tooltip'     => _("Confirm your password"),

                    '_mobile_placeholder' => _('Mobile'),
                    '_mobile_tooltip'     => _('Needed to enter your mobile/telephone'),

                    '_contact_name_placeholder' => _('Contact name'),
                    '_contact_name_tooltip'     => _('Needed to enter your name'),

                    '_company_placeholder' => _('Company'),
                    '_company_tooltip'     => _('Enter your account company name'),

                    '_subscription' => _('I want to receive news and special offers'),
                    '_terms'        => _('I agree with the <span class="marked_link">Terms and Conditions</span>'),
                    '_submit_label' => _('Submit'),

                    'redirect'   => 'welcome',
                    'send_email' => false,

                )
            )
        ),

        'welcome.sys'   => array(
            'Webpage Scope'             => 'Welcome',
            'Webpage Scope Metadata'    => '',
            'Webpage Template Filename' => 'welcome',
            'Webpage Type'              => 'Customer',
            'Webpage Code'              => 'welcome.sys',
            'Webpage Browser Title'     => _('Welcome'),
            'Webpage Name'              => _('Welcome'),
            'Webpage Meta Description'  => '',
            'Page Store Content Data'   => json_encode(
                array(
                    'blocks' => array(

                        $blocks['two_pack'],
                        $blocks['one_pack'],
                        $blocks['telephone'],

                    )


                )

            )
        ),
        'reset_pwd.sys' => array(
            'Webpage Scope'             => 'ResetPwd',
            'Webpage Scope Metadata'    => '',
            'Webpage Template Filename' => 'reset_password',
            'Webpage Type'              => 'Customer',
            'Webpage Code'              => 'reset_pwd.sys',
            'Webpage Browser Title'     => _('Reset password'),
            'Webpage Name'              => _('Reset password'),
            'Webpage Meta Description'  => '',
            'Page Store Content Data'   => json_encode(
                array(
                    '_title' => _('Reset password'),


                    '_submit_label' => _('Save'),

                    '_success_msg'               => _('Your request successfully sent!'),
                    '_error_email_msg'           => _('Provide a valid email please'),
                    '_error_email_not_found_msg' => _('Email not registered'),
                    '_error_captcha_msg'         => _('Error validating reCAPTCHA'),


                    '_password_tooltip'             => _('Password'),
                    '_password_confirm_tooltip'     => _('Confirm password'),
                    '_password_placeholder'         => _('password'),
                    '_password_confirm_placeholder' => _('Confirm password'),

                    'password_reset_success_msg'             => _('Your password has been changed successfully'),
                    'password_reset_expired_token_error_msg' => _('Sorry, password reset expired'),
                    'password_reset_error_msg'               => _("Sorry, password reset incorrect or expired days ago"),
                    'password_reset_logged_in_error_msg'     => _('You are already logged in'),
                    'password_reset_go_back'                 => _('Try again'),
                    'password_reset_go_home'                 => _('Go to our homepage'),


                )
            )
        ),
        'profile.sys'   => array(
            'Webpage Scope'             => 'UserProfile',
            'Webpage Scope Metadata'    => '',
            'Webpage Template Filename' => 'profile',
            'Webpage Type'              => 'Customer',
            'Webpage Code'              => 'profile.sys',
            'Webpage Browser Title'     => _('Customer section'),
            'Webpage Name'              => _('Customer section'),
            'Webpage Meta Description'  => '',
            'Page Store Content Data'   => json_encode(
                array(

                    '_customer_orders_title'  => _("Customer <i>Orders</i>"),
                    '_customer_profile_title' => _("Customer <i>Profile</i>"),


                    '_current_order_title' => _('Current order'),
                    '_last_order_title'    => _('Last order'),
                    '_orders_title'        => _('Orders'),


                    '_contact_details_title' => _('Contact details'),
                    '_email_placeholder'     => _('Email address'),
                    '_email_label'     => _('Email address (this is also your login name)'),
                    '_email_tooltip'         => _('Needed to login to your account'),


                    '_login_details_title' => _('Login details'),

                    '_invoice_address_title'      => _('Invoice address'),
                    '_invoice_address_save_label' => _('Save'),

                    '_delivery_addresses_title'                 => _('Delivery address'),
                    '_delivery_addresses_same_as_invoice_label' => _('Deliver to invoice address'),

                    '_delivery_addresses_save_label' => _('Save'),


                    '_password_placeholder' => _('New password'),
                    '_password_label' => _('Password'),

                    '_password_tooltip'     => _("Write new password"),

                    '_password_confirm_placeholder' => _('Confirm new password'),
                    '_password_confirm_label' => _('Confirm new password'),

                    '_password_conform_tooltip'     => _("Don't forget your password"),

                    '_mobile_placeholder' => _('Mobile'),
                    '_mobile_label' => _('Mobile'),

                    '_mobile_tooltip'     => _('Needed to enter your mobile/telephone'),

                    '_contact_name_placeholder' => _('Contact name'),
                    '_contact_name_label' => _('Contact name'),

                    '_contact_name_tooltip'     => _('Needed to enter your name'),

                    '_company_placeholder' => _('Company'),
                    '_company_label' => _('Company'),

                    '_company_tooltip'     => _('Enter your account company name (optional)'),

                    '_tax_number_placeholder' => _('VAT number'),
                    '_tax_number_label' => _('VAT number'),

                    '_tax_number_tooltip'     => _('Enter your VAT number (optional)'),

                    '_registration_number_placeholder' => _('Registration number'),
                    '_registration_number_label' => _('Registration number'),

                    '_registration_number_tooltip'     => _('Enter your company registration number (optional)'),


                    '_save_contact_details_label' => _('Save'),
                    '_save_login_details_label' => _('Save'),
                    '_save_invoice_address_details_label' => _('Save'),
                    '_save_delivery_address_details_label' => _('Save'),


                    '_username_info'=>_('Your username is your email address')


                )
            )
        ),

        'contact.sys' => array(
            'Webpage Scope'             => 'Contact',
            'Webpage Scope Metadata'    => '',
            'Webpage Template Filename' => 'contact',
            'Webpage Type'              => 'Info',
            'Webpage Code'              => 'contact.sys',
            'Webpage Browser Title'     => _('Contact'),
            'Webpage Name'              => _('Contact'),
            'Webpage Meta Description'  => '',
            'Page Store Content Data'   => json_encode(

                array(

                    'blocks' => array(
                        $blocks['map'],
                        $contact_content_data
                    )


                )
            )


        ),

        'basket.sys'   => array(
            'Webpage Scope'             => 'Basket',
            'Webpage Scope Metadata'    => '',
            'Webpage Template Filename' => 'basket',
            'Webpage Type'              => 'Ordering',
            'Webpage Code'              => 'basket.sys',
            'Webpage Browser Title'     => _('Basket'),
            'Webpage Name'              => _('Basket'),
            'Webpage Meta Description'  => '',
            'Page Store Content Data'   => json_encode(
                array(

                    'blocks' => array(

                        array(
                            'locked' => true,
                            'type'   => 'basket',
                            'label'  => _('Basket'),
                            'icon'   => 'fa-basket',
                            'show'   => 1,


                            '_order_number_label' => _('Order number'),


                            '_invoice_address_label'  => _('Invoice address'),
                            '_delivery_address_label' => _('Delivery address'),


                            '_items_gross' => _('Items Gross'),
                            '_discounts'   => _('Discounts'),
                            '_items_net'   => _('Items Net'),
                            '_charges'     => _('Charges'),
                            '_shipping'    => _('Shipping'),
                            '_net'         => _('Net'),
                            '_tax'         => _('Tax'),
                            '_total'       => _('Total'),


                            '_credit'       => _('Credit'),
                            '_total_to_pay' => _('To pay'),


                            '_special_instructions' => _('Special Instructions'),
                            '_voucher'              => _('Voucher'),


                            '_go_checkout_label' => _('Go to checkout'),
                            '_voucher_label'     => _('Add Voucher'),

                        )


                    )


                )
            )
        ),
        'checkout.sys' => array(
            'Webpage Scope'             => 'Checkout',
            'Webpage Scope Metadata'    => '',
            'Webpage Template Filename' => 'checkout',
            'Webpage Type'              => 'Ordering',
            'Webpage Code'              => 'checkout.sys',
            'Webpage Browser Title'     => _('Checkout'),
            'Webpage Name'              => _('Checkout'),
            'Webpage Meta Description'  => '',
            'Webpage Scope Metadata'    => json_encode(
                array(
                    'emails' => array(

                        'order_confirmation' => array(
                            'key'           => '',
                            'published_key' => false
                        )
                    )
                )
            ),


            'Page Store Content Data' => json_encode(
                array(


                    '_credit_card_label' => _('Credit card'),
                    '_bank_label'        => _('Bank transfer'),

                    '_credit_card_number'                      => _('Card number'),
                    '_credit_card_ccv'                         => _('CVV'),
                    '_credit_card_expiration_date'             => _('Expiration date'),
                    '_credit_card_expiration_date_month_label' => _('Month'),
                    '_credit_card_expiration_date_year_label'  => _('Year'),
                    '_credit_card_save'                        => _('Save card'),


                    '_form_title_credit_card' => _('Checkout form'),
                    '_form_title_paypal'      => _('Checkout form'),
                    '_form_title_cond'        => _('Checkout form'),
                    '_form_title_sofort'      => _('Checkout form'),
                    '_form_title_bank'        => _('Checkout form'),
                    '_form_title_other'       => _('Checkout form'),
                    '_form_title_online_bank_transfer' => _('Checkout form'),
                    '_form_title_cash_on_delivery' => _('Checkout form'),



                    '_bank_header' => _('Please go to your bank and make a payment of <b>[Order Amount]</b>  to our bank account, details below'),
                    '_bank_footer' => _('Remember to state the order number in the payment reference').' [Order Number] . '._(
                            'Please note, we cannot process your order until payment arrives in our account'
                        ),


                    '_back_to_basket' => _('Go back to basket'),

                    '_place_order'                  => _('Place order'),
                    '_place_order_from_bank'        => _('Place order'),
                    '_place_order_from_credit_card' => _('Place order'),
                    '_place_order_from_paypal'      => _('Place order'),
                    '_place_order_from_cash_on_delivery'        => _('Place order'),
                    '_place_order_from_online_bank_transfer'        => _('Place order'),


                )
            )
        ),

        'thanks.sys' => array(
            'Webpage Scope'             => 'Thanks',
            'Webpage Scope Metadata'    => '',
            'Webpage Template Filename' => 'thanks',
            'Webpage Type'              => 'Ordering',
            'Webpage Code'              => 'thanks.sys',
            'Webpage Browser Title'     => _('Thanks for your order'),
            'Webpage Name'              => _('Thanks'),
            'Webpage Meta Description'  => '',
            'Page Store Content Data'   => json_encode(

                array(
                    'blocks' => array(
                        $blocks['one_pack'],
                        $blocks['telephone'],
                    )

                )

            ),


        ),


        'not_found.sys'  => array(
            'Webpage Scope'             => 'NotFound',
            'Webpage Scope Metadata'    => '',
            'Webpage Template Filename' => 'not_found',
            'Webpage Type'              => 'Sys',
            'Webpage Code'              => 'not_found.sys',
            'Webpage Browser Title'     => _('Not found'),
            'Webpage Name'              => _('Not found'),
            'Webpage Meta Description'  => '',
            'Page Store Content Data'   => json_encode(
                array(
                    '_strong_title' => '404',
                    '_title'        => _('Oops... Page Not Found!'),
                    '_text'         => _('Sorry the page could not be found here.'),
                    '_home_guide'   => _('Try using the button below to go to main page of the site'),
                    '_home_label'   => _('Go to homepage'),

                )
            )
        ),
        'offline.sys'    => array(
            'Webpage Scope'             => 'Offline',
            'Webpage Scope Metadata'    => '',
            'Webpage Template Filename' => 'offline',
            'Webpage Type'              => 'Sys',
            'Webpage Code'              => 'offline.sys',
            'Webpage Browser Title'     => _('Offline'),
            'Webpage Name'              => _('Offline'),
            'Webpage Meta Description'  => '',
            'Page Store Content Data'   => json_encode(
                array(
                    '_strong_title' => '410',
                    '_title'        => _('Oops... This page is gone!'),
                    '_text'         => _('Sorry this page has been removed.'),

                    '_link_guide' => _('Click button below to go to a similar web page'),

                    '_home_guide' => _('Try using the button below to go to main page of the site'),
                    '_home_label' => _('Go to homepage'),

                )
            )
        ),
        'in_process.sys' => array(
            'Webpage Scope'             => 'InProcess',
            'Webpage Scope Metadata'    => '',
            'Webpage Template Filename' => 'in_process',
            'Webpage Type'              => 'Sys',
            'Webpage Code'              => 'in_process.sys',
            'Webpage Browser Title'     => _('Under construction'),
            'Webpage Name'              => _('Under construction'),
            'Webpage Meta Description'  => '',
            'Page Store Content Data'   => json_encode(
                array(
                    '_title' => _('Under construction'),
                    '_text'  => _('This page is under construction. Please come back soon!.')
                )
            )
        ),
        'search.sys'     => array(
            'Webpage Scope'             => 'Search',
            'Webpage Scope Metadata'    => '',
            'Webpage Template Filename' => 'search',
            'Webpage Type'              => 'Portfolio',
            'Webpage Code'              => 'search.sys',
            'Webpage Browser Title'     => _('Search'),
            'Webpage Name'              => _('Search'),
            'Webpage Meta Description'  => ''
        ),
        'catalogue.sys'  => array(
            'Webpage Scope'             => 'Catalogue',
            'Webpage Scope Metadata'    => '',
            'Webpage Template Filename' => 'catalogue',
            'Webpage Type'              => 'Portfolio',
            'Webpage Code'              => 'catalogue.sys',
            'Webpage Browser Title'     => _('Catalogue'),
            'Webpage Name'              => _('Catalogue'),
            'Webpage Meta Description'  => ''
        ),
        'tac.sys'        => array(
            'Webpage Scope'             => 'TandC',
            'Webpage Scope Metadata'    => '',
            'Webpage Template Filename' => 'terms_and_conditions',
            'Webpage Type'              => 'Info',
            'Webpage Code'              => 'tac.sys',
            'Webpage Browser Title'     => _('Terms & Conditions'),
            'Webpage Name'              => _('Terms & Conditions'),
            'Webpage Meta Description'  => '',
            'Page Store Content Data'   => json_encode(
                array(
                    'blocks' => array(
                        $blocks['one_pack'],
                    )

                )
            )
        ),
        'shipping.sys'   => array(
            'Webpage Scope'             => 'ShippingInfo',
            'Webpage Scope Metadata'    => '',
            'Webpage Template Filename' => 'shipping',
            'Webpage Type'              => 'Info',
            'Webpage Code'              => 'shipping.sys',
            'Webpage Browser Title'     => _('Shipping info'),
            'Webpage Name'              => _('Shipping info'),
            'Webpage Meta Description'  => '',
            'Page Store Content Data'   => json_encode(
                array(
                    'blocks' => array(
                        $blocks['one_pack'],
                    )

                )
            ),
        ),

        'about.sys' => array(
            'Webpage Scope'             => 'About',
            'Webpage Scope Metadata'    => '',
            'Webpage Template Filename' => 'about',
            'Webpage Type'              => 'Info',
            'Webpage Code'              => 'about.sys',
            'Webpage Browser Title'     => _('About us'),
            'Webpage Name'              => _('About us'),
            'Webpage Meta Description'  => '',
            'Page Store Content Data'   => json_encode(

                array(
                    'blocks' => array(
                        $blocks['two_pack'],
                        $blocks['one_pack'],
                    )
                )

            )
        )


    );


    $website_system_webpages = array(

        'EcomB2B' => $EcomB2B


    );

    return $website_system_webpages[$website_type];

}

?>
