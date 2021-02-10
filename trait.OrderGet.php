<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3:19 pm Monday, 24 February 2020 (MYT) Kuala Lumpur Malaysia

 Copyright (c) 2020, Inikoo

 Version 3.0

*/

trait OrderGet {

    /**
     * @var PDO
     */
    public $db;

    function get_deliveries($scope = 'keys', $options = '') {


        $deliveries = array();
        $where      = sprintf(
            " WHERE `Delivery Note Order Key`=%d  ", $this->id
        );


        if ($options == 'without_cancelled') {
            $where .= ' and `Delivery Note State` != "Cancelled" ';
        }


        $sql = "SELECT `Delivery Note Key` FROM `Delivery Note Dimension` $where ORDER BY `Delivery Note Key` DESC ";


        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Delivery Note Key'] == '') {
                    continue;
                }

                if ($scope == 'objects') {

                    $deliveries[$row['Delivery Note Key']] = get_object('DeliveryNote', $row['Delivery Note Key']);

                } else {
                    $deliveries[$row['Delivery Note Key']] = $row['Delivery Note Key'];
                }
            }
        }

        return $deliveries;

    }

    function metadata($key) {
        return (isset($this->metadata[$key]) ? $this->metadata[$key] : '');
    }

    function get_invoices($scope = 'keys', $options = '') {


        $invoices = array();


        switch ($options) {
            case 'refunds_only':
                $where = " and `Invoice Type`='Refund'";
                break;
            case 'invoices_only':
                $where = " and `Invoice Type`='Refund'";
                break;
            default:
                $where = '';

        }


        $sql = sprintf(
            "SELECT `Invoice Key` FROM `Invoice Dimension` WHERE `Invoice Order Key`=%d  %s ", $this->id, $where
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Invoice Key'] == '') {
                    continue;
                }

                if ($scope == 'objects') {

                    $invoices[$row['Invoice Key']] = get_object('Invoice', $row['Invoice Key']);

                } else {
                    $invoices[$row['Invoice Key']] = $row['Invoice Key'];
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $invoices;

    }

    /**
     * @param string $scope
     *
     * @return array
     */
    public function get_returns($scope = 'keys') {


        $returns = array();
        $sql     = sprintf(
            "SELECT `Supplier Delivery Key` FROM `Supplier Delivery Dimension` WHERE `Supplier Delivery Parent`='Order' and `Supplier Delivery Parent Key`=%d ORDER BY `Supplier Delivery Key` DESC ", $this->id
        );

        if ($result = $this->db->query($sql)) {
            foreach ($result as $row) {
                if ($row['Supplier Delivery Key'] == '') {
                    continue;
                }

                if ($scope == 'objects') {

                    $returns[$row['Supplier Delivery Key']] = get_object('SupplierDelivery', $row['Supplier Delivery Key']);

                } else {
                    $returns[$row['Supplier Delivery Key']] = $row['Supplier Delivery Key'];
                }
            }
        } else {
            print_r($error_info = $this->db->errorInfo());
            exit;
        }


        return $returns;

    }

}

