<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 13 Jun 2022 18:28:56 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */

$history_by_account = [
    'aroma.au' => ['clockings' => 160576, 'since' => 'August 2021', 'until' => 'September 2026', 'years' => 'For five years'],
    'aw.au'    => ['clockings' => 461604, 'since' => 'November 2015', 'until' => 'August 2026', 'years' => 'For almost eleven years'],
    'sk.au'    => ['clockings' => 132162, 'since' => 'July 2017', 'until' => 'September 2026', 'years' => 'For over nine years'],
    'es.au'    => ['clockings' => 54738, 'since' => 'March 2020', 'until' => 'July 2026', 'years' => 'For over six years'],
];

$history = $history_by_account[basename(dirname(__DIR__, 2))] ?? [
    'clockings' => array_sum(array_column($history_by_account, 'clockings')),
    'since'     => 'November 2015',
    'until'     => 'September 2026',
    'years'     => 'For almost eleven years',
];

echo strtr(file_get_contents(__DIR__.'/farewell.html'), [
    '{clockings}'  => number_format($history['clockings']),
    '{since}'      => strtoupper($history['since']),
    '{until}'      => strtoupper($history['until']),
    '{since_year}' => substr($history['since'], -4),
    '{until_year}' => substr($history['until'], -4),
    '{years}'      => $history['years'],
]);
