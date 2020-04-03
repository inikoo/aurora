<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished:   03 April 2020  19:59::16  +0800. Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/


function get_error_message($code, $original_message) {

    switch ($code) {
        case ('81528'):
            $msg = _('Sorry, but the amount is too large').'.';
            break;
        case ('81509'):
        case ('91517'):
        case ('91734'):
            $msg = _("Sorry, we don't accept this credit card type").'.';
            break;
        case ('91577'):
            $msg = _("Sorry, we don't support this payment instrument").'.';
            break;
        case ('91518'):
            $msg = _("There was a problem processing your credit card, please provide your payment information again").'.';
            break;
        case ('92202'):
            $msg = _("Phone number is invalid").'.';
            break;

        case ('81706'):
            $msg = _("CVV is required").'.';
            break;
        case ('81707'):
            $msg = _("CVV must be 3 or 4 digits").'.';
            break;
        case ('81709'):
            $msg = _("Expiration date is required").'.';
            break;
        case ('81710'):
        case ('81711'):
        case ('81712'):
        case ('81713'):
            $msg = _("Expiration date is invalid").'.';
            break;
        case ('81706'):
            $msg = _("CVV is required").'.';
            break;

        case ('81714'):
        case ('81715'):
        case ('81716'):
        case ('81737'):
        case ('81736'):
            $msg = _("There was a problem processing your credit card, please double check your payment information and try again").'.';
            break;

        default:
            $msg = $original_message;
    }

    return $msg;
}