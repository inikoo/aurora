<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 October 2015 at 18:23:56 CEST, Mijas Costa, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/
/** @var \User $user */
/** @var \Smarty $smarty */
/** @var array $state */
include_once 'helpers/history/history_tab_snippet.php';

$tab     = 'product.history';
$ar_file = 'ar_history_tables.php';
$tipo    = 'object_history';

$default = $user->get_tab_defaults($tab);
list($table_views,$table_filters,$parameters,$smarty)=prepare_history_tab_with_notes($state,$smarty);
include('utils/get_table_html.php');