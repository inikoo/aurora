<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 October 2018 at 12:21:03 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 3.0
*/

function parse_email_status_code($type,$code){

    switch ($code){
        case '5.1.1':
            $parsed_code=_("Email doesn't exist");
            break;
        case '5.1.2':
            $parsed_code=_('Bad destination system address');
            break;
        case '5.2.1':
            $parsed_code=_('Mailbox disabled, not accepting messages');
            break;
        case '5.2.2':
            $parsed_code=_('Mailbox full');
            break;

        case '5.4.4':
            if($type=='Hard Bounce'){
                $parsed_code=_('Wrong email address');
            }else{
                $parsed_code=_('Unable to route');

            }

            break;
        case '4.4.7':
            $parsed_code=_('Delivery time expired');
            break;
        case '5.7.1':
            $parsed_code=_('Delivery not authorized, message refused');
            break;
        case '5.3.0':
            $parsed_code=_('Other or undefined mail system status');
            break;
        case '5.3.1':
            $parsed_code=_('Mail system full');
            break;
        case '5.3.2':
            $parsed_code=_('System not accepting network messages');
            break;
        case '5.5.0':
            $parsed_code=_('Other or undefined protocol status');
            break;
        case '5.5.1':
            $parsed_code=_('Invalid command');
            break;
        case '5.5.2':
            $parsed_code=_('Syntax error');
            break;
        case '5.5.3':
            $parsed_code=_('Too many recipients');
            break;
        case '5.0.0':
            $parsed_code=_("Can't deliver for unknown reason");
            break;
        default:
            $parsed_code=$code;
    }


    return $parsed_code;
}



