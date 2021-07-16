<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 December 2015 at 13:03:12 GMT, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

/**
 * @param $db          \PDO
 * @param $user        \User
 * @param $public      string
 * @param $subject     string
 * @param $subject_key integer
 *
 * @return bool
 */
function authorize_file_view(PDO $db, User $user, string $public, string $subject, int $subject_key): bool {
    if ($public == 'Yes') {
        return true;
    }


    switch ($subject) {
        case 'Customer':

            if ($user->can_view('customers')) {
                return true;
            }

            break;
        case 'Order':

            if ($user->can_view('orders')) {
                return true;
            }

            break;
        case 'Part':

            if ($user->can_view('parts')) {
                return true;
            }

            break;
        case 'Staff':

            if ($user->can_view('staff')) {
                return true;
            }


            if ($user->get('User Type') == 'Staff' and $user->get('User Parent Key') == $subject_key) {
                return true;
            }


            break;
        case 'Supplier':
        case 'Supplier Delivery':


            if ($user->can_view('suppliers')) {
                return true;
            }


            if ($user->get('User Type') == 'Agent') {

                $found = 0;
                $sql   = sprintf(
                    'SELECT count(*) AS num FROM `Agent Supplier Bridge` WHERE `Agent Supplier Agent Key`=%d AND `Agent Supplier Supplier Key`=%d ', $user->get('User Parent Key'), $subject_key
                );
                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        $found = $row['num'];
                    }
                }

                if ($found > 0) {
                    return true;
                }

            }


            break;
        default:
            return false;

    }


    return false;
}



