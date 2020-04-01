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


    $contact_content_data = $blocks['text'];


    $contact_content_data['template']    = 't1';
    $contact_content_data['text_blocks'] = array(
        array('text' => 'Feel free to talk to our online representative at any time you please using our Live Chat system on our website or one of the below instant messaging programs.</p><br /><p>Please be patient while waiting for response. (24/7 Support!) <strong>Phone General Inquiries: 1-888-123-4567-8900</strong>'),
        array('text' => 'Telephone<br/>#tel<br/><br/>Email<br/>#email<br/><br/>Address<br/>#adr')
    );

    $_base = array(


        'home.sys' => array(
            'Webpage Scope'            => 'Homepage',
            'Webpage Type'             => 'Home',
            'Webpage Code'             => 'home.sys',
            //'Webpage Browser Title'    => _('Homepage'),
            'Webpage Name'             => _("Customer's homepage"),
            'Webpage Meta Description' => '',

            'Page Store Content Data' => json_encode(
                array(
                    'blocks' => array()

                )
            )


        ),

        'home_logout.sys' => array(
            'Webpage Scope'            => 'HomepageLogout',
            'Webpage Type'             => 'Home',
            'Webpage Code'             => 'home_logout.sys',
            //'Webpage Browser Title'    => _('Homepage'),
            'Webpage Name'             => _("Visitor's homepage"),
            'Webpage Meta Description' => '',

            'Page Store Content Data' => json_encode(
                array(
                    'blocks' => array()

                )
            )


        ),


        'launching.sys' => array(
            'Webpage Scope'            => 'HomepageToLaunch',
            'Webpage Type'             => 'Home',
            'Webpage Code'             => 'launching.sys',
            // 'Webpage Browser Title'    => _('Coming soon'),
            'Webpage Name'             => _('Launching website'),
            'Webpage Meta Description' => '',
            'Page Store Content Data'  => json_encode(
                array(
                    'blocks' => array(
                        array(
                            'type'          => 'launching',
                            'label'         => _('Launching website'),
                            'icon'          => 'fa-rocket',
                            'show'          => 1,
                            'top_margin'    => 40,
                            'bottom_margin' => 60,

                            'image'  => 'art/bg/launching.jpg',
                            'labels' => array(

                                '_title'  => _("We're launching soon"),
                                '_text'   => _("Our website is under construction. We'll be here soon with our new awesome site"),
                                '_footer' => _("Thanks"),


                            )
                        )


                    )
                )


            ),

        ),

        'favourites.sys' => array(
            'Webpage Scope'            => 'Favourites',
            'Webpage Scope Metadata'   => '',
            'Webpage Type'             => 'Portfolio',
            'Webpage Code'             => 'favourites.sys',
            // 'Webpage Browser Title'    => _('Favourites'),
            'Webpage Name'             => _('Favourites'),
            'Webpage Meta Description' => '',
            'Webpage Scope Metadata'   => '',
            'Page Store Content Data'  => json_encode(
                array(
                    'blocks' => array(
                        array(
                            'type'          => 'favourites',
                            'label'         => _('Favourites'),
                            'icon'          => 'fa-heart',
                            'show'          => 1,
                            'top_margin'    => 40,
                            'bottom_margin' => 60,
                            'labels'        => array(
                                'with_items' => '<h1>'._('My favourites').'</h1><p>'._('Here you can see your favourites').'</p>',
                                'no_items'   => '<h1>'._('My favourites').'</h1><p>'._('You still have no favourites').'</p>',
                            )
                        )

                    )
                )
            )

        ),


        'login.sys' => array(
            'Webpage Scope'            => 'Login',
            'Webpage Scope Metadata'   => '',
            'Webpage Type'             => 'Customer',
            'Webpage Code'             => 'login.sys',
            // 'Webpage Browser Title'    => _('Login'),
            'Webpage Name'             => _('Login'),
            'Webpage Meta Description' => '',
            'Webpage Scope Metadata'   => json_encode(
                array(
                    'emails' => array(

                        'reset_password' => array(
                            'key'           => '',
                            'published_key' => false
                        )
                    )
                )
            ),
            'Page Store Content Data'  => json_encode(


                array(
                    'blocks' => array(


                        array(
                            'type'          => 'login',
                            'label'         => _('Login'),
                            'icon'          => 'fa-sign-in-alt',
                            'show'          => 1,
                            'top_margin'    => 80,
                            'bottom_margin' => 120,
                            'labels'        => array(
                                '_title'                                          => _('Login form'),
                                '_title_recovery'                                 => _('Password recovery'),
                                '_email_label'                                    => _('E-mail'),
                                '_password_label'                                 => _('Password'),
                                '_email_recovery_label'                           => _('E-mail'),
                                '_forgot_password_label'                          => _('Forgot password?'),
                                '_keep_logged_in_label'                           => _('Keep me logged in'),
                                '_register_label'                                 => _('Register'),
                                '_log_in_label'                                   => _('Log in'),
                                '_submit_label'                                   => _('Submit'),
                                '_close_label'                                    => _('Go back'),
                                '_password_recovery_success_msg'                  => _('Your request successfully sent!'),
                                '_password_recovery_email_not_register_error_msg' => _('Email is not registered in our system'),
                                '_password_recovery_unknown_error_msg'            => _("Recovery email could't be send, please contact customer services"),
                                '_password_recovery_go_back'                      => _('Try again'),
                            )
                        )


                    )
                )


            )
        ),

        'register.sys' => array(
            'Webpage Scope'            => 'Register',
            'Webpage Scope Metadata'   => '',
            'Webpage Type'             => 'Customer',
            'Webpage Code'             => 'register.sys',
            //'Webpage Browser Title'    => _('Register'),
            'Webpage Name'             => _('Register'),
            'Webpage Meta Description' => '',
            'Webpage Scope Metadata'   => json_encode(
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
                    'blocks' => array(
                        array(
                            'type'          => 'register',
                            'label'         => _('Registration form'),
                            'icon'          => 'fa-registered',
                            'show'          => 1,
                            'top_margin'    => 40,
                            'bottom_margin' => 60,
                            'labels'        => array(
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
                                'send_email' => 1,

                            ),
                            'fields'        => array(
                                array(
                                    'field'    => 'telephone',
                                    'render'   => false,
                                    'required' => false
                                ),
                                array(
                                    'field'    => 'mobile',
                                    'render'   => true,
                                    'required' => false
                                ),
                                array(
                                    'field'    => 'name',
                                    'render'   => true,
                                    'required' => false
                                ),
                                array(
                                    'field'    => 'company',
                                    'render'   => true,
                                    'required' => false
                                ),
                                array(
                                    'field'    => 'tex_number',
                                    'render'   => true,
                                    'required' => false
                                ),
                                array(
                                    'field'    => 'registration_number',
                                    'render'   => true,
                                    'required' => false
                                )
                            )


                        )

                    )

                )


            )

        ),

        'welcome.sys'   => array(
            'Webpage Scope'            => 'Welcome',
            'Webpage Scope Metadata'   => '',
            'Webpage Type'             => 'Customer',
            'Webpage Code'             => 'welcome.sys',
            //'Webpage Browser Title'    => _('Welcome'),
            'Webpage Name'             => _('Welcome'),
            'Webpage Meta Description' => '',
            'Page Store Content Data'  => json_encode(
                array(
                    'blocks' => array(
                        $blocks['text'],
                        $blocks['telephone'],

                    )


                )

            )
        ),
        'reset_pwd.sys' => array(
            'Webpage Scope'            => 'ResetPwd',
            'Webpage Scope Metadata'   => '',
            'Webpage Type'             => 'Customer',
            'Webpage Code'             => 'reset_pwd.sys',
            //'Webpage Browser Title'    => _('Reset password'),
            'Webpage Name'             => _('Reset password'),
            'Webpage Meta Description' => '',
            'Page Store Content Data'  => json_encode(
                array(
                    'blocks' => array(
                        array(
                            'locked'        => true,
                            'type'          => 'reset_password',
                            'label'         => _('Reset password'),
                            'icon'          => 'fa-lock-open-alt',
                            'show'          => 1,
                            'top_margin'    => 40,
                            'bottom_margin' => 60,
                            'labels'        => array(
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

                    )


                )

            )
        ),


        'unsubscribe.sys' => array(
            'Webpage Scope'            => 'Unsubscribe',
            'Webpage Scope Metadata'   => '',
            'Webpage Type'             => 'Customer',
            'Webpage Code'             => 'unsubscribe.sys',
            //'Webpage Browser Title'    => _('Unsubscribe'),
            'Webpage Name'             => _('Unsubscribe'),
            'Webpage Meta Description' => '',
            'Page Store Content Data'  => json_encode(
                array(

                    'blocks' => array(
                        array(
                            'type'          => 'unsubscribe',
                            'label'         => _('Unsubscribe'),
                            'icon'          => 'fa-comment-slash',
                            'show'          => 1,
                            'top_margin'    => 40,
                            'bottom_margin' => 60,
                            'labels'        => array(
                                '_unsubscribe_title'            => _('Email subscriptions'),
                                '_unsubscribe_text'             => _('Choose which kind of emails you want to receive from us'),
                                '_save_unsubscribe_label'       => _('Save'),
                                '_newsletter'                   => _('Newsletter'),
                                '_marketing_emails'             => _('Marketing emails and special offers'),
                                '_unsubscribe_error_msg'        => _('Sorry, we could not access your record, please login to you account and unsubscribe in your profile section or contact our customer services'),
                                '_unsubscribe_error_login_link' => _('Login'),

                                '_unsubscribe_error_logged_in_msg' => _('Oops..., that link is not working properly, please click link below to unsubscribe'),
                                '_unsubscribe_error_profile_link'  => _('Profile')


                            )
                        )

                    )


                )
            )
        ),


        'profile.sys' => array(
            'Webpage Scope'            => 'UserProfile',
            'Webpage Scope Metadata'   => '',
            'Webpage Type'             => 'Customer',
            'Webpage Code'             => 'profile.sys',
            //'Webpage Browser Title'    => _('Customer section'),
            'Webpage Name'             => _('Customer section'),
            'Webpage Meta Description' => '',
            'Page Store Content Data'  => json_encode(

                array(

                    'blocks' => array(
                        array(
                            'type'          => 'profile',
                            'label'         => _('Profile'),
                            'icon'          => 'fa-user',
                            'show'          => 1,
                            'top_margin'    => 40,
                            'bottom_margin' => 60,
                            'labels'        => array(

                                '_customer_orders_title'  => _("Orders"),
                                '_customer_profile_title' => _("Profile"),


                                '_current_order_title' => _('Current order'),
                                '_last_order_title'    => _('Last order'),
                                '_orders_title'        => _('Orders'),


                                '_contact_details_title' => _('Contact details'),
                                '_email_placeholder'     => _('Email address'),
                                '_email_label'           => _('Email address (this is also your login name)'),
                                '_email_tooltip'         => _('Needed to login to your account'),


                                '_login_details_title' => _('Login details'),

                                '_invoice_address_title'      => _('Invoice address'),
                                '_invoice_address_save_label' => _('Save'),

                                '_delivery_addresses_title'                 => _('Delivery address'),
                                '_delivery_addresses_same_as_invoice_label' => _('Deliver to invoice address'),

                                '_delivery_addresses_save_label' => _('Save'),


                                '_password_placeholder' => _('New password'),
                                '_password_label'       => _('Password'),

                                '_password_tooltip' => _("Write new password"),

                                '_password_confirm_placeholder' => _('Confirm new password'),
                                '_password_confirm_label'       => _('Confirm new password'),

                                '_password_conform_tooltip' => _("Don't forget your password"),

                                '_mobile_placeholder' => _('Mobile'),
                                '_mobile_label'       => _('Mobile'),

                                '_mobile_tooltip' => _('Needed to enter your mobile/telephone'),

                                '_contact_name_placeholder' => _('Contact name'),
                                '_contact_name_label'       => _('Contact name'),

                                '_contact_name_tooltip' => _('Needed to enter your name'),

                                '_company_placeholder' => _('Company'),
                                '_company_label'       => _('Company'),

                                '_company_tooltip' => _('Enter your account company name (optional)'),

                                '_tax_number_placeholder' => _('VAT number'),
                                '_tax_number_label'       => _('VAT number'),

                                '_tax_number_tooltip' => _('Enter your VAT number (optional)'),

                                '_registration_number_placeholder' => _('Registration number'),
                                '_registration_number_label'       => _('Registration number'),

                                '_registration_number_tooltip' => _('Enter your company registration number (optional)'),


                                '_save_contact_details_label'          => _('Save'),
                                '_save_login_details_label'            => _('Save'),
                                '_save_invoice_address_details_label'  => _('Save'),
                                '_save_delivery_address_details_label' => _('Save'),


                                '_username_info' => _('Your username is your email address')


                            )
                        ),

                    )


                )
            )
        ),

        'contact.sys' => array(
            'Webpage Scope'            => 'Contact',
            'Webpage Scope Metadata'   => '',
            'Webpage Type'             => 'Info',
            'Webpage Code'             => 'contact.sys',
            //'Webpage Browser Title'    => _('Contact'),
            'Webpage Name'             => _('Contact'),
            'Webpage Meta Description' => '',
            'Page Store Content Data'  => json_encode(

                array(

                    'blocks' => array(
                        $blocks['map'],
                        $contact_content_data
                    )


                )
            )


        ),

        'basket.sys'   => array(
            'Webpage Scope'            => 'Basket',
            'Webpage Scope Metadata'   => '',
            'Webpage Type'             => 'Ordering',
            'Webpage Code'             => 'basket.sys',
            //'Webpage Browser Title'    => _('Basket'),
            'Webpage Name'             => _('Basket'),
            'Webpage Meta Description' => '',
            'Page Store Content Data'  => json_encode(
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

                            '_go_to_shop_label'  => _('Continue shopping'),
                            '_go_checkout_label' => _('Go to checkout'),
                            '_voucher_label'     => _('Add Voucher'),

                        )


                    )


                )
            )
        ),
        'checkout.sys' => array(
            'Webpage Scope'            => 'Checkout',
            'Webpage Scope Metadata'   => '',
            'Webpage Type'             => 'Ordering',
            'Webpage Code'             => 'checkout.sys',
            //'Webpage Browser Title'    => _('Checkout'),
            'Webpage Name'             => _('Checkout'),
            'Webpage Meta Description' => '',
            'Webpage Scope Metadata'   => json_encode(
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
                    'blocks' => array(
                        array(
                            'type'          => 'checkout',
                            'label'         => _('Checkout'),
                            'icon'          => 'fa-credit-card',
                            'show'          => 1,
                            'top_margin'    => 40,
                            'bottom_margin' => 60,
                            'labels'        => array(


                                '_credit_card_label' => _('Credit card'),
                                '_bank_label'        => _('Bank transfer'),

                                '_credit_card_number'                      => _('Card number'),
                                '_credit_card_ccv'                         => _('CVV'),
                                '_credit_card_expiration_date'             => _('Expiration date'),
                                '_credit_card_expiration_date_month_label' => _('Month'),
                                '_credit_card_expiration_date_year_label'  => _('Year'),
                                '_credit_card_save'                        => _('Save card'),


                                '_form_title_credit_card'          => _('Checkout form'),
                                '_form_title_paypal'               => _('Checkout form'),
                                '_form_title_cond'                 => _('Checkout form'),
                                '_form_title_sofort'               => _('Checkout form'),
                                '_form_title_bank'                 => _('Checkout form'),
                                '_form_title_other'                => _('Checkout form'),
                                '_form_title_online_bank_transfer' => _('Checkout form'),
                                '_form_title_cash_on_delivery'     => _('Checkout form'),


                                '_bank_header' => _('Please go to your bank and make a payment of <b>[Order Amount]</b>  to our bank account, details below'),
                                '_bank_footer' => _('Remember to state the order number in the payment reference').' [Order Number] . '._(
                                        'Please note, we cannot process your order until payment arrives in our account'
                                    ),


                                '_back_to_basket' => _('Go back to basket'),

                                '_place_order'                           => _('Place order'),
                                '_place_order_from_bank'                 => _('Place order'),
                                '_place_order_from_credit_card'          => _('Place order'),
                                '_place_order_from_paypal'               => _('Place order'),
                                '_place_order_from_cash_on_delivery'     => _('Place order'),
                                '_place_order_from_online_bank_transfer' => _('Place order'),


                            )
                        )
                    )

                )

            ),
        ),

        'thanks.sys' => array(
            'Webpage Scope'            => 'Thanks',
            'Webpage Scope Metadata'   => '',
            'Webpage Type'             => 'Ordering',
            'Webpage Code'             => 'thanks.sys',
            // 'Webpage Browser Title'    => _('Thanks for your order'),
            'Webpage Name'             => _('Thanks'),
            'Webpage Meta Description' => '',
            'Page Store Content Data'  => json_encode(

                array(
                    'blocks' => array(
                        array(
                            'type'          => 'thanks',
                            'label'         => _('Thanks'),
                            'icon'          => 'fa-thumbs-up',
                            'show'          => 1,
                            'top_margin'    => 40,
                            'bottom_margin' => 60,
                            'text'          => '<h1 >'._('Thank you for your order').'</h1><p>'._('Thank you!  We are delighted to receive your order').'</p><p>[Pay Info]</p><p>'._(
                                    'Your order details are listed below, if you have any questions please email our team'
                                ).'</p><p>[Order]</p>'
                        ),
                        $blocks['telephone'],
                    )

                )

            ),


        ),


        'not_found.sys' => array(
            'Webpage Scope'            => 'NotFound',
            'Webpage Scope Metadata'   => '',
            'Webpage Type'             => 'Sys',
            'Webpage Code'             => 'not_found.sys',
            //'Webpage Browser Title'    => _('Not found'),
            'Webpage Name'             => _('Not found'),
            'Webpage Meta Description' => '',
            'Page Store Content Data'  => json_encode(

                array(
                    'blocks' => array(
                        array(
                            'type'          => 'not_found',
                            'label'         => _('Not found'),
                            'icon'          => 'fa-times-octagon',
                            'show'          => 1,
                            'top_margin'    => 40,
                            'bottom_margin' => 60,
                            'labels'        => array(
                                '_strong_title' => '404',
                                '_title'        => _('Oops... Page Not Found!'),
                                '_text'         => _('Sorry the page could not be found here.'),
                                '_home_guide'   => _('Try using the button below to go to main page of the site'),
                                '_home_label'   => _('Go to homepage'),

                            )
                        )
                    )

                )

            ),


        ),
        'offline.sys'   => array(
            'Webpage Scope'            => 'Offline',
            'Webpage Scope Metadata'   => '',
            'Webpage Type'             => 'Sys',
            'Webpage Code'             => 'offline.sys',
            // 'Webpage Browser Title'    => _('Offline'),
            'Webpage Name'             => _('Offline'),
            'Webpage Meta Description' => '',

            'Page Store Content Data' => json_encode(

                array(
                    'blocks' => array(
                        array(
                            'type'          => 'offline',
                            'label'         => _('Offline page'),
                            'icon'          => 'fa-ban',
                            'show'          => 1,
                            'top_margin'    => 40,
                            'bottom_margin' => 60,
                            'labels'        => array(
                                '_strong_title' => '410',
                                '_title'        => _('Oops... This page is gone!'),
                                '_text'         => _('Sorry this page has been removed.'),
                                '_home_guide'   => _('Try using the button below to go to main page of the site'),
                                '_home_label'   => _('Go to homepage'),

                            )
                        )
                    )

                )

            ),


        ),


        'in_process.sys' => array(
            'Webpage Scope'            => 'InProcess',
            'Webpage Scope Metadata'   => '',
            'Webpage Type'             => 'Sys',
            'Webpage Code'             => 'in_process.sys',
            //'Webpage Browser Title'    => _('Under construction'),
            'Webpage Name'             => _('Under construction'),
            'Webpage Meta Description' => '',
            'Page Store Content Data'  => json_encode(
                array(
                    'blocks' => array(
                        array(
                            'type'          => 'in_process',
                            'label'         => _('Under construction'),
                            'icon'          => 'fa-seedling',
                            'show'          => 1,
                            'top_margin'    => 40,
                            'bottom_margin' => 60,
                            'labels'        => array(
                                '_title' => _('Under construction'),
                                '_text'  => _('This page is under construction. Please come back soon!.'),


                            )
                        )

                    )
                )
            )
        ),
        'search.sys'     => array(
            'Webpage Scope'            => 'Search',
            'Webpage Scope Metadata'   => '',
            'Webpage Type'             => 'Portfolio',
            'Webpage Code'             => 'search.sys',
            //'Webpage Browser Title'    => _('Search'),
            'Webpage Name'             => _('Search'),
            'Webpage Meta Description' => '',
            'Page Store Content Data'  => json_encode(
                array(
                    'blocks' => array(
                        array(
                            'type'          => 'search',
                            'label'         => _('Search'),
                            'icon'          => 'fa-search',
                            'show'          => 1,
                            'top_margin'    => 40,
                            'bottom_margin' => 60,
                            'labels'        => array()

                        )
                    )

                )
            )
        ),
        'catalogue.sys'  => array(
            'Webpage Scope'            => 'Catalogue',
            'Webpage Scope Metadata'   => '',
            'Webpage Type'             => 'Portfolio',
            'Webpage Code'             => 'catalogue.sys',
            //'Webpage Browser Title'    => _('Catalogue'),
            'Webpage Name'             => _('Catalogue'),
            'Webpage Meta Description' => '',
            'Page Store Content Data'  => json_encode(
                array(
                    'blocks' => array(
                        array(
                            'type'          => 'catalogue',
                            'label'         => _('Catalogue'),
                            'icon'          => 'fa-apple-crate',
                            'show'          => 1,
                            'top_margin'    => 20,
                            'bottom_margin' => 20,
                            'labels'        => array()

                        )
                    )

                )
            )
        ),
        'tac.sys'        => array(
            'Webpage Scope'            => 'TandC',
            'Webpage Scope Metadata'   => '',
            'Webpage Type'             => 'Info',
            'Webpage Code'             => 'tac.sys',
            // 'Webpage Browser Title'    => _('Terms & Conditions'),
            'Webpage Name'             => _('Terms & Conditions'),
            'Webpage Meta Description' => '',
            'Page Store Content Data'  => json_encode(
                array(
                    'blocks' => array(
                        $blocks['text'],
                    )

                )
            )
        ),
        'shipping.sys'   => array(
            'Webpage Scope'            => 'ShippingInfo',
            'Webpage Scope Metadata'   => '',
            'Webpage Type'             => 'Info',
            'Webpage Code'             => 'shipping.sys',
            //'Webpage Browser Title'    => _('Shipping info'),
            'Webpage Name'             => _('Shipping info'),
            'Webpage Meta Description' => '',
            'Page Store Content Data'  => json_encode(
                array(
                    'blocks' => array(
                        $blocks['text'],
                    )

                )
            ),
        ),

        'about.sys' => array(
            'Webpage Scope'            => 'About',
            'Webpage Scope Metadata'   => '',
            'Webpage Type'             => 'Info',
            'Webpage Code'             => 'about.sys',
            //'Webpage Browser Title'    => _('About us'),
            'Webpage Name'             => _('About us'),
            'Webpage Meta Description' => '',
            'Page Store Content Data'  => json_encode(

                array(
                    'blocks' => array(

                        $blocks['text'],
                    )
                )

            )
        ),


    );

    $EcomDS                  = array(
        'portfolio.sys' => array(
            'Webpage Scope'            => 'Portfolio',
            'Webpage Scope Metadata'   => '',
            'Webpage Type'             => 'Portfolio',
            'Webpage Code'             => 'portfolio.sys',
            //'Webpage Browser Title'    => _('Portfolio'),
            'Webpage Name'             => _('Portfolio'),
            'Webpage Meta Description' => '',
            'Webpage Scope Metadata'   => '',
            'Page Store Content Data'  => json_encode(
                array(
                    'blocks' => array(
                        array(
                            'type'          => 'portfolio',
                            'label'         => _('Portfolio'),
                            'icon'          => 'fa-store-alt',
                            'show'          => 1,
                            'top_margin'    => 40,
                            'bottom_margin' => 60,
                            'labels'        => array()
                        )

                    )
                )
            )

        ),

        'clients.sys'          => array(
            'Webpage Scope'            => 'Clients',
            'Webpage Scope Metadata'   => '',
            'Webpage Type'             => 'Clients',
            'Webpage Code'             => 'clients.sys',
            'Webpage Name'             => _('Clients'),
            'Webpage Meta Description' => '',
            'Webpage Scope Metadata'   => '',
            'Page Store Content Data'  => json_encode(
                array(
                    'blocks' => array(
                        array(
                            'type'          => 'clients',
                            'label'         => _('Clients'),
                            'icon'          => 'fa-user',
                            'show'          => 1,
                            'top_margin'    => 40,
                            'bottom_margin' => 60,
                            'labels'        => array()
                        )

                    )
                )
            )

        ),
        'client.sys'           => array(
            'Webpage Scope'            => 'Client',
            'Webpage Scope Metadata'   => '',
            'Webpage Type'             => 'Client',
            'Webpage Code'             => 'client.sys',
            'Webpage Name'             => _('Client'),
            'Webpage Meta Description' => '',
            'Webpage Scope Metadata'   => '',
            'Page Store Content Data'  => json_encode(
                array(
                    'blocks' => array(
                        array(
                            'type'          => 'client',
                            'label'         => _('Client'),
                            'icon'          => 'fa-user',
                            'show'          => 1,
                            'top_margin'    => 40,
                            'bottom_margin' => 60,
                            'labels'        => array()
                        )
                    )
                )
            )
        ),
        'clients_orders.sys'   => array(
            'Webpage Scope'            => 'Clients_Orders',
            'Webpage Scope Metadata'   => '',
            'Webpage Type'             => 'Clients_Orders',
            'Webpage Code'             => 'clients_orders.sys',
            // 'Webpage Browser Title'    => _("Client's orders"),
            'Webpage Name'             => _("Client's orders"),
            'Webpage Meta Description' => '',
            'Webpage Scope Metadata'   => '',
            'Page Store Content Data'  => json_encode(
                array(
                    'blocks' => array(
                        array(
                            'type'          => 'clients_orders',
                            'label'         => _("Client's orders"),
                            'icon'          => 'fa-shopping-cart',
                            'show'          => 1,
                            'top_margin'    => 40,
                            'bottom_margin' => 60,
                            'labels'        => array()
                        )

                    )
                )
            )

        ),
        'client_basket.sys'    => array(
            'Webpage Scope'            => 'Client_Basket',
            'Webpage Scope Metadata'   => '',
            'Webpage Type'             => 'Ordering',
            'Webpage Code'             => 'client_basket.sys',
            'Webpage Name'             => _("Client basket"),
            'Webpage Meta Description' => '',
            'Page Store Content Data'  => json_encode(
                array(
                    'blocks' => array(
                        array(
                            'locked'                  => true,
                            'type'                    => 'client_basket',
                            'label'                   => _('Basket'),
                            'icon'                    => 'fa-basket',
                            'show'                    => 1,
                            '_order_number_label'     => _('Order number'),
                            '_delivery_address_label' => _('Delivery address'),
                            '_items_gross'            => _('Items Gross'),
                            '_discounts'              => _('Discounts'),
                            '_items_net'              => _('Items Net'),
                            '_charges'                => _('Charges'),
                            '_shipping'               => _('Shipping'),
                            '_net'                    => _('Net'),
                            '_tax'                    => _('Tax'),
                            '_total'                  => _('Total'),
                            '_credit'                 => _('Credit'),
                            '_total_to_pay'           => _('To pay'),
                            '_special_instructions'   => _('Special Instructions'),
                            '_go_checkout_label'      => _('Go to checkout'),

                        )


                    )


                )
            )


        ),
        'client_order_new.sys' => array(
            'Webpage Scope'            => 'Client_Order_New',
            'Webpage Scope Metadata'   => '',
            'Webpage Type'             => 'Ordering',
            'Webpage Code'             => 'client_order_new.sys',
            'Webpage Name'             => _("New client order"),
            'Webpage Meta Description' => '',
            'Page Store Content Data'  => json_encode(
                array(
                    'blocks' => array(
                        array(
                            'locked'              => true,
                            'type'                => 'client_order_new',
                            'label'               => _('Basket'),
                            'icon'                => 'fa-basket',
                            'show'                => 1,
                            '_existing_customers' => _('Existing customers'),
                            '_new_customer'       => _('New customer'),
                            '_anon_customer'      => _('Anonymous order'),


                        )


                    )


                )
            )

        ),
        'client_order.sys'     => array(
            'Webpage Scope'            => 'Client_Order',
            'Webpage Scope Metadata'   => '',
            'Webpage Type'             => 'Ordering',
            'Webpage Code'             => 'client_order.sys',
            'Webpage Name'             => _("Client order"),
            'Webpage Meta Description' => '',
            'Page Store Content Data'  => json_encode(
                array(
                    'blocks' => array(
                        array(
                            'locked'                  => true,
                            'type'                    => 'client_order',
                            'label'                   => _('Client order'),
                            'icon'                    => 'fa-shopping-cart',
                            'show'                    => 1,
                            '_order_number_label'     => _('Order number'),
                            '_delivery_address_label' => _('Delivery address'),
                            '_items_gross'            => _('Items Gross'),
                            '_discounts'              => _('Discounts'),
                            '_items_net'              => _('Items Net'),
                            '_charges'                => _('Charges'),
                            '_shipping'               => _('Shipping'),
                            '_net'                    => _('Net'),
                            '_tax'                    => _('Tax'),
                            '_total'                  => _('Total'),
                            '_credit'                 => _('Credit'),
                            '_total_to_pay'           => _('To pay'),


                        )


                    )


                )
            )

        ),
        'top_up.sys'           => array(
            'Webpage Scope'            => 'Top_Up',
            'Webpage Scope Metadata'   => '',
            'Webpage Type'             => 'Ordering',
            'Webpage Code'             => 'top_up.sys',
            'Webpage Name'             => _("Top up"),
            'Webpage Meta Description' => '',
            'Page Store Content Data'  => json_encode(
                array(
                    'blocks' => array(
                        array(
                            'locked' => true,
                            'type'   => 'top_up',
                            'label'  => _('Top up'),
                            'icon'   => 'fa-piggy-bank',
                            'show'   => 1,
                            'top_up_options'=>[10,50,100,250],

                            'labels' => array(


                                '_credit_card_label' => _('Credit card'),
                                '_bank_label'        => _('Bank transfer'),

                                '_credit_card_number'                      => _('Card number'),
                                '_credit_card_ccv'                         => _('CVV'),
                                '_credit_card_expiration_date'             => _('Expiration date'),
                                '_credit_card_expiration_date_month_label' => _('Month'),
                                '_credit_card_expiration_date_year_label'  => _('Year'),
                                '_credit_card_save'                        => _('Save card'),


                                '_form_title_credit_card'          => _('Top up'),
                                '_form_title_paypal'               => _('Top up'),
                                '_form_title_cond'                 => _('Top up'),
                                '_form_title_sofort'               => _('Top up'),
                                '_form_title_bank'                 => _('Top up'),
                                '_form_title_other'                => _('Top up'),
                                '_form_title_online_bank_transfer' => _('Top up'),
                                '_form_title_cash_on_delivery'     => _('Top up'),


                                '_bank_header' => _('Please go to your bank and make a payment of <b>[Order Amount]</b>  to our bank account, details below'),
                                '_bank_footer' => _('Remember to state your customer ID in the payment reference').' [Customer ID]',


                                '_pay_top_up'                           => ('Top up'),
                                '_pay_top_up_from_bank'                 => ('Top up'),
                                '_pay_top_up_from_credit_card'          => ('Top up'),
                                '_pay_top_up_from_paypal'               => ('Top up'),
                                '_pay_top_up_from_cash_on_delivery'     => ('Top up'),
                                '_pay_top_up_from_online_bank_transfer' => ('Top up'),


                            )

                        )


                    )


                )
            )

        ),
    );
    $website_system_webpages = array(

        'EcomB2B' => $_base,
        'EcomDS'  => array_merge($_base, $EcomDS)
    );

    return $website_system_webpages[$website_type];

}


