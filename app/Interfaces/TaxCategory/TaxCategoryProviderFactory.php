<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 01 Aug 2021 02:29:47 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */
namespace Aurora\Interfaces\TaxCategory;

use Aurora\Interfaces\TaxCategory\Providers\ESP_TaxCategoryProvider;
use Aurora\Interfaces\TaxCategory\Providers\EUR_TaxCategoryProvider;
use Aurora\Interfaces\TaxCategory\Providers\GBR_TaxCategoryProvider;
use LogicException;

class TaxCategoryProviderFactory {
    public static function createProvider(\PDO $db,$type,$args) {
        switch ($type) {
            case 'EUR':
                return new EUR_TaxCategoryProvider($db,$args['base_country']);
            case 'ESP':
                return new ESP_TaxCategoryProvider($db,$args['RE']);
            case 'GBR':
                return new GBR_TaxCategoryProvider($db);
            default:
                throw new LogicException('Invalid Account Tax Authority '.$type);
        }

    }
}


