<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 April 2016 at 18:02:34 GMT+8, Lovina, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/
include_once 'helpers/history/history_tab_snippet.php';
/** @var \User $user */
/** @var \Smarty $smarty */
/** @var array $state */
$tab     = 'location.history';
$ar_file = 'ar_history_tables.php';
$tipo    = 'object_history';

$default = $user->get_tab_defaults($tab);
list($table_views,$table_filters,$parameters,$smarty)=prepare_history_tab($state,$smarty);
include('utils/get_table_html.php');

