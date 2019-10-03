<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 May 2018 at 12:40:03 BST, Sheffield, UK
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';

$smarty_web               = new Smarty();
$smarty_web->template_dir = 'EcomB2B/templates';
$smarty_web->compile_dir  = 'EcomB2B/server_files/smarty/templates_c';
$smarty_web->cache_dir    = 'EcomB2B/server_files/smarty/cache';
$smarty_web->config_dir   = 'EcomB2B/server_files/smarty/configs';
$smarty->addPluginsDir('./smarty_plugins');
$smarty_web->setCaching(Smarty::CACHING_LIFETIME_CURRENT);


$smarty_web->clearAllCache();
$smarty_web->clearCompiledTemplate();


