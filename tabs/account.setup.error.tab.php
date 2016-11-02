<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 7 March 2016 at 15:37:27 GMT+8, Yiwu, China

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


if ($state['key'] == 1 or $state['key'] == 2 or $state['key'] == 3) {
    $msg = '<i class="fa fa-exclamation-circle"></i> '._("Critical error").'<br/><br/>'._('Please contact service support').'<br/><br/>'.sprintf(
            'E%02d', $state['key']
        );

}


$smarty->assign('msg', $msg);


$html = $smarty->fetch('setup/error.tpl');


?>
