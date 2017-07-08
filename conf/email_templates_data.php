<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 July 2017 at 21:37:40 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


$email_templates_data = array(


    'Welcome' => array(
        'key'=>'welcome',
        'name'=>_('Welcome'),
        'subject'=>_('Welcome'),
        'sender'=>'Store'

    ),
    'Reset_Password' => array(
        'key'=>'reset_password',
        'name'=>_('Password reset'),
        'subject'=>_('Password reset'),
        'text'=> "[Greetings]\n\n We received request to reset the password associated with this email account.\n\nIf you did not request to have your password reset, you can safely ignore this email. We assure that yor customer account is safe.\n\nCopy and paste the following link to your browser's address window.\n\n[Reset_Password_URL]\n\n Once you have returned our page you will be asked to choose a new password\n\nThank you \n\n[Signature]",
        'sender'=>'Store'

    ),
    'Order_Confirmation' => array(
        'key'=>'order_confirmation',
        'name'=>_('Order confirmation'),
        'subject'=>_('Order confirmation'),
        'sender'=>'Store'

    )



);


?>
