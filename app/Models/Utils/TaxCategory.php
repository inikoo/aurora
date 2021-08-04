<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Sun, 01 Aug 2021 22:33:00 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

namespace Aurora\Models\Utils;

use PDO;

class TaxCategory
{
    private PDO $db;
    private array $data;

    public ?int $id;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->id = null;
    }

    public function loadWithKey($key): TaxCategory
    {
        $sql = "select * from kbase.`Tax Category Dimension` where `Tax Category Key`=?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($key));
        if ($row = $stmt->fetch()) {
            $this->data = $row;
            $this->id   = $this->data['Tax Category Key'];
        }

        return $this;
    }

    public function loadWithTypeCountry($type, $country_code): TaxCategory
    {
        $sql = "select * from kbase.`Tax Category Dimension` where `Tax Category Country 2 Alpha Code`=? and  `Tax Category Type`=? and `Tax Category Active`='Yes'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($country_code, $type));
        if ($row = $stmt->fetch()) {
            $this->data = $row;
            $this->id   = $this->data['Tax Category Key'];
        }

        return $this;
    }

    public function loadWithCodeCountry($code, $country_code): TaxCategory
    {
        $sql = "select * from kbase.`Tax Category Dimension` where `Tax Category Country 2 Alpha Code`=? and  `Tax Category Code`=? and `Tax Category Active`='Yes'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array($country_code, $code));
        if ($row = $stmt->fetch()) {
            $this->data = $row;
            $this->id   = $this->data['Tax Category Key'];
        }

        return $this;
    }

    public function get($field)
    {
        return $this->data[$field] ?? '';
    }

    function getMetadata($field)
    {
        $metadata = json_decode($this->data['Tax Category Metadata'], true);
        return $metadata[$field] ?? '';
    }
}